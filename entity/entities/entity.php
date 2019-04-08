<?php

namespace Ms\Dobrozhil\Entity\Entities;

use Ms\Core\Entity\Application;
use Ms\Core\Entity\Db\SqlHelper;
use Ms\Core\Entity\ErrorCollection;
use Ms\Core\Entity\Type\Date;
use Ms\Core\Lib\Users;
use Ms\Dobrozhil\Interfaces\IEntity;
use Ms\Dobrozhil\Lib\Access;
use Ms\Dobrozhil\Lib\Entities;
use Ms\Dobrozhil\Lib\Errors;
use Ms\Dobrozhil\Lib\Types;
use Ms\Dobrozhil\Tables\EntityPropertiesTable;
use Ms\Dobrozhil\Tables\EntityPropertiesValueTable;

class Entity implements IEntity
{
	/**
	 * @var ErrorCollection
	 */
	protected $errorCollection = null;

	protected $_arPropertyValues = array ();

	public $_object = null;

	/**
	 * Entity constructor.
	 *
	 * @param string $sObjectName Имя объекта сущности
	 */
	public function __construct ($sObjectName)
	{
		if (Entities::checkObjectName($sObjectName))
		{
			$this->_object = $sObjectName;
		}
	}

	//<editor-fold defaultstate="collapse" desc=">>>Магические методы @final<<<">
	/**
	 * Возвращает текущее значение свойства сущности
	 *
	 * @param string $sPropertyName Название свойства сущности
	 *
	 * @return null|mixed
	 */
	final public function __get ($sPropertyName)
	{
		if (Entities::checkPropertyName($sPropertyName))
		{
			return $this->getProperty($sPropertyName);
		}
		else
		{
			return NULL;
		}
	}

	/**
	 * Сохраняет значение свойства сущности
	 *
	 * @param string $sPropertyName Название свойства сущности
	 * @param mixed $value Значение свойства сущности
	 *
	 * @return bool
	 */
	final public function __set ($sPropertyName, $value)
	{
		if (Entities::checkPropertyName($sPropertyName))
		{
			return $this->setProperty ($sPropertyName, $value);
		}
		else
		{
			return false;
		}
	}

	/**
	 * Возвращает TRUE, если заданное свойство существует у сущности и оно не равно NULL,
	 * в противном случае возвращает FALSE
	 *
	 * @param string $sPropertyName Имя свойства сущности
	 * @param bool   $bSaveValue    Сохранять ли значение свойства при проверке (по-умолчанию, FALSE - не сохранять)
	 *
	 * @return mixed
	 */
	final public function __isset ($sPropertyName, $bSaveValue=false)
	{
		if (isset($this->_arPropertyValues[$sPropertyName]))
		{
			return true;
		}
		else
		{
			return $this->issetProperty($sPropertyName, $bSaveValue);
		}
	}

	/**
	 * Удаляет значение свойства из сохраненных значений
	 *
	 * @param string $sPropertyName Имя свойства
	 */
	final public function __unset ($sPropertyName)
	{
		$this->unsetProperty($sPropertyName);
	}
	//</editor-fold>

	/**
	 * Возвращает структуру сущности при использовании в функции var_dump()
	 *
	 * @return array
	 */
	public function __debugInfo()
	{
		return $this->getAllProperties();
	}

	/**
	 * При приведении сущности к строке возвращает тип сущности
	 *
	 * @see $this->getEntityType()
	 *
	 * @return string
	 */
	public function __toString ()
	{
		return $this->getEntityType();
	}

	//<editor-fold defaultstate="collapse" desc=">>Методы обработки ошибок @final<<">
	/**
	 * Добавляет типовую ошибку в коллекцию
	 *
	 * @param string $errorCode Типовой код ошибки класса Errors
	 * @param array  $arReplace Массив с заменами для типовой фразы
	 *
	 * @see Errors
	 * @see Errors::getErrorTextByCode()
	 *
	 * @final
	 */
	final public function addError ($errorCode, $arReplace=[])
	{
		$this->errorCollection->setError(
			Errors::getErrorTextByCode($errorCode,$arReplace),
			$errorCode
		);
	}

	/**
	 * Добавляет стандартную ошибку с указанным кодом, для указанного свойства (если необходимо)
	 *
	 * @param string      $errorCode     Код ошибки
	 * @param null|string $sPropertyName Имя свойства
	 */
	final public function addErrorProperty ($errorCode, $sPropertyName=null)
	{
		$this->addError(
			$errorCode,
			['ERROR_VIEW'=>'свойства '.(!is_null($sPropertyName)?'"'.$sPropertyName.'" ':'').'объекта "'.$this->_object.'" сущности "'.static::getEntityType().'"']
		);
	}

	/**
	 * Добавляет произсольную ошибку в коллекцию
	 *
	 * @param string          $sErrorText Текст ошибки
	 * @param null|string|int $mErrorCode Код ошибки (необязательно)
	 */
	final public function addCustomError ($sErrorText,$mErrorCode=null)
	{
		$this->errorCollection->setError($sErrorText, $mErrorCode);
	}

	/**
	 * Очищает коллекцию ошибок
	 */
	final public function clearErrors ()
	{
		$this->errorCollection = NULL;
	}

	/**
	 * Возвращает массив последних ошибок, либо FALSE
	 *
	 * @return array|bool
	 */
	final public function getErrors()
	{
		$arErrors = $this->errorCollection->toArray();
		if (!empty($arErrors))
		{
			return $arErrors;
		}

		return false;
	}
	//</editor-fold>



	/**
	 * Обрабатывает полученный ID пользователя
	 *
	 * @param null|int &$userID ID пользователя, если NULL, заменяет ID текущего пользователя
	 */
	final public function normalizeUserID (&$userID=null)
	{
		if (is_null($userID))
		{
			$userID = Application::getInstance()->getUser()->getID();
		}

		$userID = (int)$userID;
	}

	/**
	 * Возвращает пространство имен с именем класса сущности
	 *
	 * @return string
	 */
	public static function getEntityNamespace ()
	{
		return __CLASS__;
	}

	/**
	 * Возвращает тип сущности
	 *
	 * @return string
	 */
	public static function getEntityType ()
	{
		return 'Entity';
	}

	/**
	 * Возвращает значение свойства сущности, либо NULL
	 *
	 * @param string   $sPropertyName Имя свойства сущности
	 * @param null|int $userID        ID пользователя, из под которого будет запрошено свойство
	 *
	 * @return mixed
	 */
	public function getProperty($sPropertyName, $userID=null)
	{
		$this->clearErrors();
		$this->normalizeUserID($userID);

		if (!Entities::checkPropertyName($sPropertyName))
		{
			return NULL;
		}

		if (isset($this->_arPropertyValues[$this->_object][$sPropertyName]))
		{
			if ($this->canViewProperty($this->getPropertyCreatedBy($sPropertyName),$userID))
			{
				return $this->getPropertyValue($sPropertyName);
			}
		}

		if (!$this->canViewObjectProperty($userID))
		{
			$this->addErrorProperty(Errors::ERROR_ACCESS_VIEW,$sPropertyName);
			return NULL;
		}

		$arRes = EntityPropertiesTable::getOne([
			'select' => ['PROPERTY_TYPE','CREATED_BY'],
			'filter' => [
				'ENTITY_TYPE'=>static::getEntityType(),
				'PROPERTY_NAME'=>$sPropertyName,
			]
		]);
		$arRes2 = EntityPropertiesValueTable::getOne([
			'select' => ['VALUE','UPDATED_BY','UPDATED_DATE'],
			'filter' => [
				'OBJECT_NAME' => $this->_object,
				'PROPERTY_NAME' => $sPropertyName,
			]
		]);
		if (!$arRes || !$arRes2 || is_null($arRes2['VALUE']))
		{
			return NULL;
		}
		else
		{
			$this->addPropertyToArray(
				$sPropertyName,
				$arRes['CREATED_BY'],
				$arRes['PROPERTY_TYPE'],
				Types::prepareValueFrom($arRes2['VALUE'],$arRes['PROPERTY_TYPE']),
				$arRes2['UPDATED_BY'],
				$arRes2['UPDATED_DATE']
			);
			if ($this->canViewProperty($sPropertyName,$userID))
			{
				return $this->getPropertyValue($sPropertyName);
			}
			else
			{
				$this->addErrorProperty(Errors::ERROR_ACCESS_VIEW,$sPropertyName);
				return NULL;
			}
		}
	}

	/**
	 * Записывает значение в свойство сущности
	 *
	 * @param string   $sPropertyName Имя свойства сущности
	 * @param mixed    $value         Новое значение свойства сущности
	 * @param string   $sPropertyType Тип свойства
	 * @param null|int $userID        ID пользователя, под которым будет выполнен метод, NULL - текущий пользователь
	 *
	 * @return bool
	 */
	public function setProperty ($sPropertyName, $value, $sPropertyType=Types::BASE_TYPE_STRING, $userID=null)
	{
		$this->clearErrors();
		$this->normalizeUserID($userID);

		if (!Entities::checkPropertyName($sPropertyName))
		{
			$this->addError(Errors::ERROR_WRONG_ENTITY_PROPERTY_NAME);
			return FALSE;
		}

		if (isset($this->_arPropertyValues[$this->_object][$sPropertyName]))
		{
			if ($this->getPropertyValue($sPropertyName)==$value)
			{
				return TRUE;
			}
		}

		$arRes = EntityPropertiesTable::getOne([
			'select' => ['PROPERTY_TYPE','CREATED_BY'],
			'filter' => [
				'ENTITY_TYPE' => static::getEntityType(),
				'PROPERTY_NAME' => $sPropertyName
			]
		]);
		$arRes2 = EntityPropertiesValueTable::getOne([
			'select' => ['VALUE','UPDATED_BY','UPDATED_DATE'],
			'filter' => [
				'OBJECT_NAME' => $this->_object,
				'PROPERTY_NAME' => $sPropertyName
			]
		]);
		if ($arRes)
		{
			if ($arRes2)
			{
				$arRes2['VALUE'] = Types::prepareValueFrom($arRes2['VALUE'],$arRes['PROPERTY_TYPE']);
				$this->addPropertyToArray(
					$sPropertyName,
					$arRes['CREATED_BY'],
					$arRes['PROPERTY_TYPE'],
					$arRes2['VALUE'],
					$arRes2['UPDATED_BY'],
					$arRes2['UPDATED_DATE']
				);
				//Если новое значение равно старому, ничего менять не нужно
				if ($value == $arRes2['VALUE'])
				{
					return TRUE;
				}
				//Иначе ставим флаг необходимости обновления свойства
				else
				{
					if (!$this->canWriteProperty($sPropertyName,$userID))
					{
						$this->addErrorProperty(Errors::ERROR_ACCESS_WRITE,$sPropertyName);
						$this->unsetProperty($sPropertyName);
						return FALSE;
					}
					else
					{
						$helper = new SqlHelper(EntityPropertiesValueTable::getTableName());
						$res = EntityPropertiesValueTable::update(
							1,
							[
								'VALUE'=> Types::prepareValueTo(
									$this->getPropertyValue($sPropertyName),
									$this->getPropertyType($sPropertyName)
								),
								'PROPERTY_TYPE' => $this->getPropertyType($sPropertyName),
								'UPDATED_BY' => $userID
							],
							FALSE,
							$helper->wrapFieldQuotes('OBJECT_NAME').' = "'.$this->_object.'"'
							."\n\tAND".$helper->wrapFieldQuotes('PROPERTY_NAME').' = "'.$sPropertyName.'"'
						);
						if ($res->getResult())
						{
							return TRUE;
						}

						$this->addCustomError(
							'Не удалось обновить свойство "'.$sPropertyName.'" объекта "'.$this->_object.'" сущности "'.static::getEntityType().'"'
						);
						$this->unsetProperty($sPropertyName);
						return FALSE;
					}
				}
			}
			else
			{
				if (!$this->canWriteProperty($sPropertyName,$userID))
				{
					$this->addErrorProperty(Errors::ERROR_ACCESS_WRITE,$sPropertyName);
					$this->unsetProperty($sPropertyName);
					return FALSE;
				}
				else
				{
					$arAdd = [
						'OBJECT_NAME' => $this->_object,
						'PROPERTY_NAME' => $sPropertyName,
						'VALUE'=> Types::prepareValueTo(
							$this->getPropertyValue($sPropertyName),
							$this->getPropertyType($sPropertyName)
						),
						'PROPERTY_TYPE' => $this->getPropertyType($sPropertyName),
						'UPDATED_BY' => $userID
					];
					$res = EntityPropertiesValueTable::add($arAdd);
					if ($res->getResult())
					{
						return TRUE;
					}

					$this->addCustomError(
						'Не удалось обновить свойство "'.$sPropertyName.'" объекта "'.$this->_object.'" сущности "'.static::getEntityType().'"'
					);
					$this->unsetProperty($sPropertyName);
					return FALSE;
				}
			}
		}
		//Если такого свойства вообще не существует
		else
		{
			return static::addProperty($sPropertyName, $sPropertyType, $value, $userID);
		}
	}

	/**
	 * Возвращает TRUE, если заданное свойство существует у сущности и оно не равно NULL,
	 * в противном случае возвращает FALSE
	 *
	 * @param string   $sPropertyName Имя свойства сущности
	 * @param bool     $bSaveValue    Сохранять ли значение свойства при проверке (по-умолчанию, FALSE - не сохранять)
	 *
	 * @return bool
	 */
	public function issetProperty ($sPropertyName, $bSaveValue = FALSE)
	{
		$this->clearErrors();

		if (!Entities::checkPropertyName($sPropertyName))
		{
			$this->addError(Errors::ERROR_WRONG_ENTITY_PROPERTY_NAME);
			return FALSE;
		}

		$arRes = EntityPropertiesTable::getOne([
			'select' => ['PROPERTY_TYPE','CREATED_BY'],
			'filter' => [
				'ENTITY_TYPE' => static::getEntityType(),
				'PROPERTY_NAME' => $sPropertyName
			]
		]);
		if (!$arRes)
		{
			return FALSE;
		}
		else
		{
			$arRes2 = EntityPropertiesValueTable::getOne([
				'select' => ['VALUE','UPDATED_BY','UPDATED_DATE'],
				'filter' => [
					'OBJECT_NAME' => $this->_object,
					'PROPERTY_NAME' => $sPropertyName
				]
			]);
			if (!$arRes2)
			{
				return FALSE;
			}

			if ($bSaveValue)
			{
				$this->addPropertyToArray(
					$sPropertyName,
					$arRes['CREATED_BY'],
					$arRes['PROPERTY_TYPE'],
					Types::prepareValueFrom($arRes2['VALUE'],$arRes['PROPERTY_TYPE']),
					$arRes2['UPDATED_BY'],
					$arRes2['UPDATED_DATE']
				);
			}
			return TRUE;
		}
	}

	/**
	 * Добавляет новое свойство сущности, при необходимости записывая в него значение
	 *
	 * @param string   $sPropertyName Имя нового свойства сущности
	 * @param string   $sPropertyType Тип значения свойства сущности
	 * @param mixed    $value         Значение свойства, если необходимо
	 * @param null|int $userID        ID пользователя, из под которого выполняется метод, NULL - текущий пользователь
	 *
	 * @return bool TRUE, если свойство было создано, FALSE - в противном случае
	 */
	public function addProperty ($sPropertyName, $sPropertyType=Types::BASE_TYPE_STRING, $value=NULL, $userID=NULL)
	{
		$this->clearErrors();
		$this->normalizeUserID($userID);

		if (!$this->canCreateProperty($userID))
		{
			$this->addError(
				Errors::ERROR_ACCESS_CREATE,
				['ERROR_CREATE'=>'свойств сущности '.static::getEntityType()]
			);
			return FALSE;
		}

		if (!Entities::checkPropertyName($sPropertyName))
		{
			$this->addError(Errors::ERROR_WRONG_ENTITY_PROPERTY_NAME);
			return FALSE;
		}

		if (!is_null($value))
		{
			$saveValue = Types::prepareValueTo($value, $sPropertyType);
		}
		else
		{
			$saveValue = NULL;
		}

		$arAdd = array (
			'ENTITY_TYPE' => static::getEntityType(),
			'PROPERTY_NAME' => $sPropertyName,
			'PROPERTY_TYPE' => $sPropertyType,
			'CREATED_BY' => $userID,
			'UPDATED_BY' => $userID
		);

		$res = EntityPropertiesTable::add($arAdd);
		if ($res->getResult())
		{
			if (!$this->canWriteProperty($sPropertyType,$userID))
			{
				$this->addErrorProperty(Errors::ERROR_ACCESS_WRITE,$sPropertyName);
				$this->unsetProperty($sPropertyName);
				return FALSE;
			}
			else
			{
				$arAdd2 = [
					'OBJECT_NAME' => $this->_object,
					'PROPERTY_NAME' => $sPropertyName,
					'PROPERTY_TYPE' => $sPropertyType,
					'VALUE' => $saveValue,
					'UPDATED_BY' => $userID
				];
				$res2 = EntityPropertiesValueTable::add($arAdd2);
				if ($res2->getResult())
				{
					if (!is_null($value))
					{
						$this->addPropertyToArray(
							$sPropertyName,
							$userID,
							$sPropertyType,
							$value,
							$userID,
							new Date()
						);
					}

					return TRUE;
				}

				$this->addCustomError('Не удалось добавить новое значение свойства "'.$sPropertyName.'" сущности '.static::getEntityType());
				return FALSE;
			}
		}

		$this->addCustomError('Не удалось добавить новое свойство сущности '.static::getEntityType());
		return FALSE;
	}

	/**
	 * Возвращает все существующие свойства сущности с их значениями
	 * ['PROPERTY_NAME','PROPERTY_TYPE','CREATED_BY','VALUE']
	 *
	 * @param null|int $userID ID пользователя, из под которого будет выполнен метод
	 *
	 * @return array|bool
	 */
	public function getAllProperties ($userID=null)
	{
		$this->normalizeUserID($userID);
		$arProperties = [];
		$arPropNameList = [];

		if (!$this->canViewObjectProperty($userID))
		{
			$this->addErrorProperty(Errors::ERROR_ACCESS_VIEW);
			return false;
		}

		$arRes = EntityPropertiesTable::getList([
			'select' => ['PROPERTY_NAME','PROPERTY_TYPE','CREATED_BY'],
			'filter' => [
				'ENTITY_TYPE' => static::getEntityType()
			]
		]);
		if (!$arRes)
		{
			return false;
		}
		else
		{
			foreach ($arRes as $ar_res)
			{
				$arProperties[$ar_res['PROPERTY_NAME']] = $ar_res;
				if (!in_array($ar_res['PROPERTY_NAME'],$arPropNameList))
				{
					$arPropNameList[] = $ar_res['PROPERTY_NAME'];
				}
			}

			if (!empty($arPropNameList))
			{
				$arRes2 = EntityPropertiesValueTable::getList([
					'select' => ['PROPERTY_NAME','VALUE','UPDATED_BY','UPDATED_DATE'],
					'filter' => [
						'OBJECT_NAME' => $this->_object,
						'PROPERTY_NAME' => $arPropNameList
					]
				]);
				if ($arRes2)
				{
					foreach ($arRes2 as $ar_res)
					{
						if (isset($arProperties[$ar_res['PROPERTY_NAME']]))
						{
							$arProperties[$ar_res['PROPERTY_NAME']]['VALUE'] = Types::prepareValueFrom(
								$ar_res['VALUE'],
								$arProperties[$ar_res['PROPERTY_NAME']]['PROPERTY_TYPE']
							);
							$arProperties[$ar_res['PROPERTY_NAME']]['UPDATED_BY'] = $ar_res['UPDATED_BY'];
							$arProperties[$ar_res['PROPERTY_NAME']]['UPDATED_DATE'] = $ar_res['UPDATED_DATE'];
						}
					}
				}
			}

			return $arProperties;
		}
	}



	//<editor-fold defaultstate="collapse" desc=">>final методы проверки прав пользователя<<">
	/**
	 * Проверяет есть ли у пользователя права на просмотр свойств сущности или текущего класса
	 *
	 * @param null|int $userID ID проверяемого пользователя, если NULL - текущий пользователь
	 *
	 * @return bool
	 */
	final public function canViewObjectProperty ($userID=null)
	{
		$this->normalizeUserID($userID);

		if (
			!Access::canView('ENTITY_'.static::getEntityType().'_PROPERTY',$userID)
			&& !Access::canViewOwn('ENTITY_'.static::getEntityType().'_PROPERTY',$userID)
		) {
			if (
				!Access::canView('ENTITY_'.static::getEntityType().'_OBJECT_'.$this->_object.'_PROPERTY',$userID)
				&& !Access::canViewOwn('ENTITY_'.static::getEntityType().'_OBJECT_'.$this->_object.'_PROPERTY',$userID)
			) {
				return FALSE;
			}
		}

		return TRUE;
	}

	/**
	 * Проверяет, есть ли права у пользователя на просмотр значения свойства
	 *
	 * Пользователь может просматривать свои свойства и свойства созданные системой,
	 * если у него есть право просмотра СВОИЪ свойств
	 * Для просмотра свойств, созданных другими пользователями у него должно быть право
	 * просмотра ВСЕХ свойств
	 * Проверка происходит в три этапа:
	 * 1. Сначала проверяется право на все свойства сущности
	 * 2. Затем проверяется право на все свойства объекта
	 * 3. В конце проверяется право на конкретное свойство объекта
	 * Если у пользователя есть права из верхнего пункта, остальные не проверяются
	 *
	 *
	 * @param string   $sPropertyName Имя свойства
	 * @param null|int $userID        ID проверяемого пользователя, NULL - текуший пользователь
	 *
	 * @return bool
	 */
	final public function canViewProperty ($sPropertyName, $userID=null)
	{
		$this->normalizeUserID($userID);
		$iCreatedBy = $this->getPropertyCreatedBy($sPropertyName);
		if (
			(
				(int)$iCreatedBy == $userID
				|| $this->isSystemUser($iCreatedBy)
			)
			&& (
				Access::canViewOwn('ENTITY_'.static::getEntityType().'_PROPERTY',$userID)
				|| Access::canView('ENTITY_'.static::getEntityType().'_PROPERTY',$userID)
			)
		) {
			return TRUE;
		}
		elseif (
			(int)$iCreatedBy != $userID
			&& Access::canView('ENTITY_'.static::getEntityType().'_PROPERTY',$userID)
		) {
			return TRUE;
		}
		else
		{
			if (
				(
					(int)$iCreatedBy == $userID
					|| $this->isSystemUser($iCreatedBy)
				)
				&& (
					Access::canViewOwn('ENTITY_'.static::getEntityType().'_OBJECT_'.$this->_object.'_PROPERTY',$userID)
					|| Access::canView('ENTITY_'.static::getEntityType().'_OBJECT_'.$this->_object.'_PROPERTY',$userID)
				)
			) {
				return TRUE;
			}
			elseif (
				(int)$iCreatedBy != $userID
				&& Access::canView('ENTITY_'.static::getEntityType().'_OBJECT_'.$this->_object.'_PROPERTY',$userID)
			) {
				return TRUE;
			}
			else
			{
				if (
					(
						(int)$iCreatedBy == $userID
						|| $this->isSystemUser($iCreatedBy)
					)
					&& (
						Access::canViewOwn('ENTITY_'.static::getEntityType().'_OBJECT_'.$this->_object.'_PROPERTY_'.$sPropertyName,$userID)
						|| Access::canView('ENTITY_'.static::getEntityType().'_OBJECT_'.$this->_object.'_PROPERTY_'.$sPropertyName,$userID)
					)
				) {
					return TRUE;
				}
				elseif (
					(int)$iCreatedBy != $userID
					&& Access::canView('ENTITY_'.static::getEntityType().'_OBJECT_'.$this->_object.'_PROPERTY_'.$sPropertyName,$userID)
				) {
					return TRUE;
				}
			}
		}

		return FALSE;
	}

	/**
	 * Проверяет, есть ли у пользователя права создавать свойства сущности
	 * Так как создать свойство можно только у сущности, проверяется только право на создание
	 * свойства в сущности
	 *
	 * @param null|int $userID ID проверяемого пользователя, NULL - текущий пользователь
	 *
	 * @return bool
	 */
	final public function canCreateProperty ($userID=null)
	{
		$this->normalizeUserID($userID);

		return Access::canCreate('ENTITY_'.static::getEntityType().'_PROPERTY',$userID);
	}

	/**
	 * Проверяет, есть ли у пользователя права редактировать свойства сущности
	 * Так как только у сущностей есть свойства, а объекты лишь используют их, проверяются
	 * права только в части свойств сущности в общем
	 *
	 * @param int      $iCreatedBy ID пользователя, создавшего запись
	 * @param null|int $userID     ID проверяемого пользователя, NULL - текущий пользователь
	 *
	 * @return bool
	 */
	final public function canEditProperty ($iCreatedBy, $userID=null)
	{
		$this->normalizeUserID($userID);
		if (
			(
				(int)$iCreatedBy == $userID
				|| $this->isSystemUser($iCreatedBy)
			)
			&& (
				Access::canEditOwn('ENTITY_'.static::getEntityType().'_PROPERTY',$userID)
				|| Access::canEdit('ENTITY_'.static::getEntityType().'_PROPERTY',$userID)
			)
		) {
			return TRUE;
		}
		elseif (
			(int)$iCreatedBy != $userID
			&& Access::canEdit('ENTITY_'.static::getEntityType().'_PROPERTY',$userID)
		) {
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	/**
	 * Проверяет, есть ли у пользователя право изменять значения свойства
	 *
	 * @param string   $sPropertyName Имя свойства
	 * @param null|int $userID        ID пользователя, под которым будет выполнен метод
	 *
	 * @return bool
	 */
	final public function canWriteProperty ($sPropertyName, $userID=NULL)
	{
		$this->normalizeUserID($userID);
		$iCreatedBy = $this->getPropertyCreatedBy($sPropertyName);

		if (
			(
				(int)$iCreatedBy == $userID
				|| $this->isSystemUser($iCreatedBy)
			)
			&& (
				Access::canWriteOwn('ENTITY_'.static::getEntityType().'_PROPERTY',$userID)
				|| Access::canWrite('ENTITY_'.static::getEntityType().'_PROPERTY',$userID)
			)
		) {
			return TRUE;
		}
		elseif (
			(int)$iCreatedBy != $userID
			&& Access::canWrite('ENTITY_'.static::getEntityType().'_PROPERTY',$userID)
		) {
			return TRUE;
		}
		else
		{
			if (
				(
					(int)$iCreatedBy == $userID
					|| $this->isSystemUser($iCreatedBy)
				)
				&& (
					Access::canWriteOwn('ENTITY_'.static::getEntityType().'_OBJECT_'.$this->_object.'_PROPERTY',$userID)
					|| Access::canWrite('ENTITY_'.static::getEntityType().'_OBJECT_'.$this->_object.'_PROPERTY',$userID)
				)
			) {
				return TRUE;
			}
			elseif (
				(int)$iCreatedBy != $userID
				&& Access::canWrite('ENTITY_'.static::getEntityType().'_OBJECT_'.$this->_object.'_PROPERTY',$userID)
			) {
				return TRUE;
			}
			else
			{
				if (
					(
						(int)$iCreatedBy == $userID
						|| $this->isSystemUser($iCreatedBy)
					)
					&& (
						Access::canWriteOwn('ENTITY_'.static::getEntityType().'_OBJECT_'.$this->_object.'_PROPERTY_'.$sPropertyName,$userID)
						|| Access::canWrite('ENTITY_'.static::getEntityType().'_OBJECT_'.$this->_object.'_PROPERTY_'.$sPropertyName,$userID)
					)
				) {
					return TRUE;
				}
				elseif (
					(int)$iCreatedBy != $userID
					&& Access::canWrite('ENTITY_'.static::getEntityType().'_OBJECT_'.$this->_object.'_PROPERTY_'.$sPropertyName,$userID)
				) {
					return TRUE;
				}
			}
		}

		return FALSE;
	}
	//</editor-fold>

	//<editor-fold defaultstate="collapse" desc=">>Приватные методы<<">
	/**
	 * @param string $sPropertyName
	 * @param int    $iCreatedBy
	 * @param string $sPropertyType
	 * @param mixed  $value
	 * @param int    $iUpdatedBy
	 * @param Date   $dateUpdated
	 */
	private function addPropertyToArray (string $sPropertyName, int $iCreatedBy, string $sPropertyType, mixed $value, int $iUpdatedBy, Date $dateUpdated)
	{
		$this->_arPropertyValues[$this->_object][$sPropertyName] = [
			'CREATED_BY' => $iCreatedBy,
			'PROPERTY_TYPE' => $sPropertyType,
			'VALUE' => $value,
			'UPDATED_BY' => $iUpdatedBy,
			'UPDATED_DATE' => $dateUpdated
		];
	}

	/**
	 * @param string $sPropertyName
	 */
	private function unsetProperty (string $sPropertyName)
	{
		if (isset($this->_arPropertyValues[$this->_object][$sPropertyName]))
		{
			unset($this->_arPropertyValues[$this->_object][$sPropertyName]);
		}
	}

	/**
	 * @param null|int $userID
	 *
	 * @return bool
	 */
	private function isSystemUser ($userID=null)
	{
		$this->normalizeUserID($userID);

		//TODO: Заменить на проверку как системного пользователя, так и группу системных пользователей
		return ($userID == Users::SYSTEM_USER);
	}

	/**
	 * @param string $sPropertyName
	 *
	 * @return null
	 */
	private function getPropertyCreatedBy (string $sPropertyName)
	{
		if (isset($this->_arPropertyValues[$this->_object][$sPropertyName]))
		{
			return $this->_arPropertyValues[$this->_object][$sPropertyName]['CREATED_BY'];
		}

		return NULL;
	}

	/**
	 * @param string $sPropertyName
	 *
	 * @return null
	 */
	private function getPropertyType (string $sPropertyName)
	{
		if (isset($this->_arPropertyValues[$this->_object][$sPropertyName]))
		{
			return $this->_arPropertyValues[$this->_object][$sPropertyName]['PROPERTY_TYPE'];
		}

		return NULL;
	}

	/**
	 * @param string $sPropertyName
	 *
	 * @return null
	 */
	private function getPropertyValue (string $sPropertyName)
	{
		if (isset($this->_arPropertyValues[$this->_object][$sPropertyName]))
		{
			return $this->_arPropertyValues[$this->_object][$sPropertyName]['VALUE'];
		}

		return NULL;
	}

	/**
	 * @param string $sPropertyName
	 *
	 * @return null
	 */
	private function getPropertyUpdatedBy (string $sPropertyName)
	{
		if (isset($this->_arPropertyValues[$this->_object][$sPropertyName]))
		{
			return $this->_arPropertyValues[$this->_object][$sPropertyName]['UPDATED_BY'];
		}

		return NULL;
	}

	/**
	 * @param string $sPropertyName
	 *
	 * @return null|Date
	 */
	private function getPropertyUpdatedDate (string $sPropertyName)
	{
		if (isset($this->_arPropertyValues[$this->_object][$sPropertyName]))
		{
			return $this->_arPropertyValues[$this->_object][$sPropertyName]['UPDATED_DATE'];
		}

		return NULL;
	}
	//</editor-fold>
}