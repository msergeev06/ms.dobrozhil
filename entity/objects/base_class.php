<?php

namespace Ms\Dobrozhil\Entity\Objects;

use Ms\Core\Entity\Application;
use Ms\Core\Entity\ErrorCollection;
use Ms\Core\Entity\Type\Date;
use Ms\Core\Lib\Events;
use Ms\Core\Lib\Logs;
use Ms\Dobrozhil\Lib\Access;
use Ms\Dobrozhil\Lib\Classes;
use Ms\Dobrozhil\Lib\Errors;
use Ms\Dobrozhil\Lib\Objects;
use Ms\Dobrozhil\Lib\Types;
use Ms\Dobrozhil\Tables\ClassesTable;
use Ms\Dobrozhil\Tables\ClassPropertiesTable;
use Ms\Dobrozhil\Tables\ObjectsPropertyValuesTable;
use Ms\Dobrozhil\Tables\ObjectsTable;

class BaseClass
{
	//<editor-fold defaultstate="collapse" desc=">>Базовые публичные свойства<<">
	/**
	 * @var string
	 */
	public $_sObjectName = null;

	/**
	 * Виртуальное свойство, хранящее имя класса объекта
	 */
//	public $_sClassName = null;

	/**
	 * Вируальное свойство, хранящее тип класса объекта
	 * "U" пользовательский, "P" - программный
	 */
//	public $_sClassType = null;

	/**
	 * Виртуальное свойство, хранящее название класса на языке системы
	 */
//	public $_sClassTitle = null;
	//</editor-fold>

	//<editor-fold defaultstate="collapse" desc=">>Приватные свойства<<">
	/**
	 * ID пользователя из под которого инициализирован объект (0 - системный пользователь)
	 *
	 * @var null|int
	 */
	private $iUserID = null;

	/**
	 * Флаг проверки инициализированного пользователя на то, что он является системным пользователем
	 *
	 * @var null|bool
	 */
	private $isSysUser = null;

	/**
	 * Коллекция ошибок
	 *
	 * @var ErrorCollection
	 */
	private $errorCollection = null;

	/**
	 * Права на доступ
	 *
	 * @var array
	 */
	private $_arAccess = [];

	/**
	 * Сохраненные значения свойств объекта
	 * @var array
	 */
	private $_arPropertyValues = array();

	/**
	 * Массив со списком вызванных методов класса
	 * @var array|null
	 */
	private $_arLastMethodName = null;

	/**
	 * Массив со списком параметров вызванных методов класса
	 * @var array|null
	 */
	private $_arLastMethodParams = null;

	/**
	 * Массив со списком существующих свойств и их классов
	 *
	 * @var array
	 */
	private $_arIssetProperty = [];
	//</editor-fold>

	/**
	 * BaseClass constructor.
	 * Создает программный объект заданного виртуального объекта
	 * Может быть переопределен
	 *
	 *
	 * @param null|string $sObjectName Имя объекта
	 * @param null|int $mUserID ID пользователя, из-под которого будет обрабатываться объект.
	 *                          Если NULL - текущий пользователь, 0 - системный пользователь
	 */
	public function __construct ($sObjectName=null, $mUserID=null)
	{
		$this->normalizeUserID($mUserID);
		$this->iUserID = (int)$mUserID;

		if (!is_null($sObjectName) && $this->checkObjectName($sObjectName))
		{
			$this->_sObjectName = $sObjectName;
		}
	}

	/**
	 * Проверяет успешное создание объекта
	 * Может быть переопределен
	 *
	 * @return bool
	 */
	public function isObject()
	{
		return (!is_null($this->_sObjectName));
	}

	/**
	 * Вычисляет ID пользователя, из под которого инициализируется объект.
	 * Если NULL - текущий пользователь, 0 - системный пользователь
	 *
	 * @param null|int $mUserID
	 *
	 * @return $this
	 */
	final public function normalizeUserID (&$mUserID=null)
	{
		if (is_null($mUserID))
		{
			$mUserID = Application::getInstance()->getUser()->getID();
		}
		elseif ((int)$mUserID < 0)
		{
			$mUserID = 0;
		}
		else
		{
			$mUserID = (int)$mUserID;
		}
		$this->isSysUser = null;

		return $this;
	}

	//<editor-fold defaultstate="collapse" desc=">>Методы различных проверок<<">
	/**
	 * Проверяет, является ли системным указанный или инициированный пользователь
	 *
	 * @param null|int $iUserID ID проверяемого пользователя, либо NULL инициированный пользователь
	 *
	 * @return bool
	 */
	private function isSystemUser ($iUserID=null)
	{
		if (is_null($iUserID) && !is_null($this->isSysUser) && is_bool($this->isSysUser))
		{
			return $this->isSysUser;
		}
		elseif (is_null($iUserID) && $this->iUserID == \Ms\Core\Lib\Users::SYSTEM_USER)
		{
			return TRUE;
		}
		elseif (!is_null($iUserID) && $iUserID == \Ms\Core\Lib\Users::SYSTEM_USER)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	/**
	 * Проверяет на заполненность и на правильность именования объекта
	 *
	 * @param string $sObjectName Имя объекта
	 *
	 * @return bool
	 */
	private function checkObjectName ($sObjectName)
	{
		if (
			!isset($sObjectName)
			|| is_null($sObjectName)
			|| strlen($sObjectName)<=0
		) {
			//'Не указано название объекта'
			Logs::setError(
				'Не указано название объекта',
				array (),
				$this->errorCollection,
				Errors::ERROR_NO_NAME
			);
			return false;
		}

		$bOk = Objects::checkName($sObjectName, false);

		if (!$bOk)
		{
			//'Имя объекта "#OBJECT_NAME#" содержит запрещенные символы',
			Logs::setError(
				'Имя объекта "#OBJECT_NAME#" содержит запрещенные символы',
				['OBJECT_NAME'=>$sObjectName],
				$this->errorCollection,
				Errors::ERROR_WRONG_NAME
			);
			return FALSE;
		}

		return TRUE;
	}

	/**
	 * Проверяет на заполненность и на правильность именования свойства объекта
	 *
	 * @param string $sPropertyName Имя свойства
	 *
	 * @return bool
	 */
	private function checkPropertyName (&$sPropertyName)
	{
		if (
			!isset($sPropertyName)
			|| is_null($sPropertyName)
			|| strlen($sPropertyName)<=0
		) {
			//'Не указано название свойства объекта'
			Logs::setError(
				'Не указано название свойства объекта',
				array (),
				$this->errorCollection,
				Errors::ERROR_NO_NAME
			);
			return FALSE;
		}

		$bOk = Classes::checkPropertyName($sPropertyName, FALSE);

		if (!$bOk)
		{
			//TODO: Добавить проверку наличия свойства в родительских классах
			$arRes = ClassPropertiesTable::getOne([
				'select' => ['PROPERTY_NAME'],
				'filter' => [
					'TITLE' => $sPropertyName,
					'CLASS_NAME' => $this->_sClassName
				]
			]);
			if (
				$arRes
				&& isset($arRes['PROPERTY_NAME'])
				&& Classes::checkPropertyName($arRes['PROPERTY_NAME'], FALSE)
			) {
				$sPropertyName = $arRes['PROPERTY_NAME'];
			}
			else
			{
				//'Имя свойства "#PROPERTY_NAME#" класса "#CLASS_NAME#" содержит запрещенные символы',
				Logs::setError(
					'Имя свойства "#PROPERTY_NAME#" класса "#CLASS_NAME#" содержит запрещенные символы',
					['CLASS_NAME' => $this->_sClassName, 'PROPERTY_NAME' => $sPropertyName],
					$this->errorCollection,
					Errors::ERROR_WRONG_NAME
				);
				return FALSE;
			}
		}

		return TRUE;
	}
	//</editor-fold>

	//<editor-fold defaultstate="collapsed" desc=">>Наследуемые методы событий получения, записи значений свойств и вызова методов<<">
	/**
	 * Вызывается перед получением значения свойства объекта
	 * Может быть переопределен
	 *
	 * @param string $sPropertyName Имя свойства объекта
	 *
	 * @return void|mixed|false
	 */
	protected function onBeforeGetProperty ($sPropertyName)
	{
		return NULL;
	}

	/**
	 * Вызывается перед получением значения свойства объекта
	 * Может быть переопределен
	 *
	 * @param string $sPropertyName     Имя свойства объекта
	 * @param array  $arPropertyParams  Массив с параметрами свойства и его значения
	 *
	 * @return void|mixed|false
	 */
	protected function onAfterGetProperty ($sPropertyName,&$arPropertyParams=[])
	{
		return NULL;
	}

	/**
	 * Вызывается перед изменением значения свойства объекта
	 * Может быть переопределен
	 *
	 * @param string $sPropertyName Имя свойства объекта
	 * @param mixed  $value         Значение свойства объекта
	 */
	protected function onBeforeSetProperty ($sPropertyName, $value){}

	/**
	 * Вызывается после установки значения свойства объекта
	 * Может быть переопределен
	 *
	 * @param string $sPropertyName Имя свойства объекта
	 * @param mixed  $value         Массив с параметрами свойства и его значения
	 */
	protected function onAfterSetProperty ($sPropertyName,$value){}

	/**
	 * Вызывается перед вызовом метода класса объекта
	 *
	 * @param string $sMethodName Имя метода
	 * @param array  $arParams    Массив параметров
	 */
	protected function onBeforeCallMethod ($sMethodName, $arParams=array()) {}

	/**
	 * Вызывается после вызова метода класса объекта
	 *
	 * @param string $sMethodName Имя метода
	 * @param array  $arParams    Массив параметров
	 */
	protected function onAfterCallMethod ($sMethodName, $arParams=array()) {}
	//</editor-fold>

	//<editor-fold defaultstate="collapsed" desc=">>Магические методы<<">
	/**
	 * Возвращает текущее значение свойства объекта
	 *
	 * @param string $sPropertyName Название свойства объекта
	 *
	 * @return null|mixed
	 */
	final public function __get ($sPropertyName)
	{
		return $this->getProperty($sPropertyName);
	}

	/**
	 * Сохраняет значение свойства объекта
	 *
	 * @param string $propertyName Название свойства объекта
	 * @param mixed $value Значение свойства объекта
	 *
	 * @return bool
	 */
	final public function __set ($propertyName, $value)
	{
		if (Classes::checkPropertyName($propertyName))
		{
			return $this->setProperty ($propertyName, $value);
		}
		else
		{
			return false;
		}
	}

	/**
	 * Вызывает метод класса объекта и передает в него параметры, возвращая результат работы метода
	 *
	 * @param string $sMethodName Имя метода
	 * @param array  $arParams    Массив аргументов, переданных в метод
	 *
	 * @return mixed
	 */
	final public function __call ($sMethodName, $arParams = array())
	{
		if (Classes::checkMethodName($sMethodName))
		{
			return $this->runMethod($sMethodName,$arParams);
		}
		else
		{
			return null;
		}
	}

	/**
	 * Возвращает true, если свойство существует и не равно null
	 *
	 * @param string $name       Имя свойства
	 * @param bool   $bSaveValue Созранить значение при проверке
	 *
	 * @return bool
	 */
	final public function __isset ($name, $bSaveValue=false)
	{
		if (isset($this->_arPropertyValues[$name]))
		{
			return true;
		}

		$arRes = ObjectsPropertyValuesTable::getOne(
			array(
				'select' => array('VALUE'),
				'filter' => array('OBJECT_NAME'=>$this->_objectName,'PROPERTY_NAME'=>$name)
			)
		);

		if ($arRes && !is_null($arRes['VALUE']))
		{
			if ($bSaveValue)
			{
				$this->_arPropertyValues[$name] = $arRes['VALUE'];
			}
			return true;
		}

		return false;
	}

	/**
	 * Удаляет значение свойства из сохраненных значений
	 *
	 * @param string $sPropertyName Имя свойства
	 */
	final public function __unset ($sPropertyName)
	{
		$this->unsetPropertyValue($sPropertyName);
	}

	/**
	 * Возвращает структуру объекта при использовании в функции var_dump()
	 *
	 * @return array
	 */
	public function __debugInfo()
	{
		return $this->getAllProperties();
	}

	/**
	 * Возвращает представление объекта в виде строки вида "[имя_класса.имя_объекта]"
	 *
	 * @return string
	 */
	final public function __toString ()
	{
		$strReturn = '['.$this->_sClassName.'.'.$this->_sObjectName.']';

		return $strReturn;
	}
	//</editor-fold>

	/**
	 * Возвращает значение свойства текущего объекта
	 *
	 * @param string $sPropertyName Имя свойства
	 *
	 * @return mixed
	 */
	final public function getProperty ($sPropertyName)
	{
		//Обрабатываем алиасы основных свойств
		if ($sPropertyName == '_class')
		{
			$sPropertyName = '_sClassName';
		}
		elseif ($sPropertyName == '_object')
		{
			return $this->_sObjectName;
		}
		elseif ($sPropertyName == '_title')
		{
			$sPropertyName = '_sObjectTitle';
		}

		//Получаем значения для виртуальных свойств класса и объекта
		if ($sPropertyName == '_sClassName')
		{
			if ($this->issetPropertyValue($sPropertyName))
			{
				return $this->getPropertyValue($sPropertyName);
			}

			$arRes = ObjectsTable::getOne([
				'select' => ['CLASS_NAME'],
				'filter' => ['NAME'=>$this->_sObjectName]
			]);

			if ($arRes && isset($arRes['CLASS_NAME']) && !is_null($arRes['CLASS_NAME']))
			{
				$this->saveSystemPropertyValue($sPropertyName,$arRes['CLASS_NAME']);

				return $arRes['CLASS_NAME'];
			}

			return null;
		}
		elseif ($sPropertyName == '_sObjectTitle')
		{
			if ($this->issetPropertyValue($sPropertyName))
			{
				return $this->getPropertyValue($sPropertyName);
			}

			$arRes = ObjectsTable::getOne([
				'select' => ['TITLE'],
				'filter' => ['NAME'=>$this->_sObjectName]
			]);

			if ($arRes && isset($arRes['TITLE']) && !is_null($arRes['TITLE']))
			{
				$this->saveSystemPropertyValue($sPropertyName, $arRes['TITLE']);

				return $arRes['TITLE'];
			}

			return null;
		}
		elseif ($sPropertyName == '_sClassType')
		{
			if ($this->issetPropertyValue($sPropertyName))
			{
				$this->_arIssetProperty[$sPropertyName] = $this->getPropertyClassName($sPropertyName);
				return $this->getPropertyValue($sPropertyName);
			}

			$arRes = ObjectsTable::getOne([
				'select' => [
					'CLASS_NAME',
					'CLASS_NAME.TYPE' => 'CLASS_TYPE'
				],
				'filter' => ['NAME'=>$this->_sObjectName]
			]);

			if (
				$arRes
				&& isset($arRes['CLASS_NAME'])
				&& !is_null($arRes['CLASS_NAME'])
				&& isset($arRes['CLASS_TYPE'])
				&& !is_null($arRes['CLASS_TYPE'])
			) {
				if (!$this->issetPropertyValue('_sClassName'))
				{
					$this->saveSystemPropertyValue('_sClassName',$arRes['CLASS_NAME']);
				}

				$this->saveSystemPropertyValue($sPropertyName,$arRes['CLASS_TYPE']);

				return $arRes['CLASS_TYPE'];
			}

			return null;
		}
		elseif ($sPropertyName == '_sClassTitle')
		{
			if ($this->issetPropertyValue($sPropertyName))
			{
				return $this->getPropertyValue($sPropertyName);
			}

			$arRes = ObjectsTable::getOne([
				'select' => [
					'CLASS_NAME',
					'CLASS_NAME.TITLE' => 'CLASS_TITLE'
				],
				'filter' => ['NAME'=>$this->_sObjectName]
			]);

			if (
				$arRes
				&& isset($arRes['CLASS_NAME'])
				&& !is_null($arRes['CLASS_NAME'])
				&& isset($arRes['CLASS_TITLE'])
				&& !is_null($arRes['CLASS_TITLE'])
			) {
				if (!$this->issetPropertyValue('_sClassName'))
				{
					$this->saveSystemPropertyValue('_sClassName',$arRes['CLASS_NAME']);
				}

				$this->saveSystemPropertyValue($sPropertyName,$arRes['CLASS_TITLE']);

				return $arRes['CLASS_TITLE'];
			}

			return null;
		}

		//Получаем остальные свойства
		if (!$this->checkPropertyName($sPropertyName))
		{
			return null;
		}

		if ($this->canViewProperty($sPropertyName))
		{
			if ($this->issetPropertyValue($sPropertyName))
			{
				$this->_arIssetProperty[$sPropertyName] = TRUE;
				return $this->getPropertyValue($sPropertyName);
			}

			//<editor-fold defaultstate="collapse" desc=">> Выполняем наследуемый метод $this->onBeforeGetProperty(propertyName)">
			$bNext = null;
			$bNext = $this->onBeforeGetProperty($sPropertyName);
			//Если наследуемый метод вернет false, значение свойства не будет возвращено
			if ($bNext === false)
			{
				return NULL;
			}
			//</editor-fold>

			//<editor-fold defaultstate="collapse" desc=">> Вызываем системное событие OnBeforeGetObjectProperty">
			$bNext = null;
			$bNext = Events::runEvents(
				'ms.dobrozhil',
				'OnBeforeGetObjectProperty',
				[
					'OBJECT_NAME' => $this->_sObjectName,
					'PROPERTY_NAME' => $sPropertyName
				]
			);
			//Если обработчик событий вернет false, значение свойства не будет возвращено
			if ($bNext === FALSE)
			{
				return null;
			}
			//</editor-fold>

			//<editor-fold defaultstate="collapse" desc=">> Запускаем при наличии метод onBeforeGetProperty_[propertyName]">
			$bNext = null;
			$bNext = $this->runMethod('onBeforeGetProperty_'.$sPropertyName);
			//Если метод вернет false, значение свойства не будет возвращено
			if ($bNext === FALSE)
			{
				return null;
			}
			//</editor-fold>

			//<editor-fold defaultstate="collapse" desc=">> Получаем значение свойства">
			$arRes = ClassPropertiesTable::getOne([
				'select' => [
					'CREATED_BY',
					'CREATED_DATE',
					'TYPE',
					'TITLE'
				],
				'filter' => [
					'CLASS_NAME' => $this->_sClassName,
					'PROPERTY_NAME' => $sPropertyName
				]
			]);
			if ($arRes)
			{
				$this->_arPropertyValues[$sPropertyName] = [
					'TYPE' => $arRes['TYPE'],
					'TITLE' => $arRes['TITLE'],
					'CREATED_BY' => (int)$arRes['CREATED_BY'],
					'CREATED_DATE' => $arRes['CREATED_DATE']
				];
			}
			else
			{
				return NULL;
			}
			$arRes = ObjectsPropertyValuesTable::getOne([
				'select' => [
					'PROPERTY_NAME',
					'VALUE',
					'UPDATED_BY',
					'UPDATED_DATE'
				],
				'filter' => [
					'OBJECT_NAME' => $this->_sObjectName,
					'PROPERTY_NAME' => $sPropertyName
				]
			]);
			if ($arRes)
			{
				$this->_arPropertyValues[$sPropertyName]['VALUE'] = Types::prepareValueFrom(
					$arRes['VALUE'],
					$this->getPropertyType($sPropertyName)
				);
				$this->_arPropertyValues[$sPropertyName]['UPDATED_BY'] = (int)$arRes['UPDATED_BY'];
				$this->_arPropertyValues[$sPropertyName]['UPDATED_DATE'] = $arRes['UPDATED_DATE'];
			}
			else
			{
				return NULL;
			}
			//</editor-fold>

			//<editor-fold defaultstate="collapse" desc=">> Выполняем наследуемый метод $this->onAfterGetProperty(propertyName)">
			$bNext = null;
			$bNext = $this->onAfterGetProperty($sPropertyName, $this->_arPropertyValues[$sPropertyName]);
			//Если наследуемый метод вернет false, значение свойства не будет возвращено
			if ($bNext === false)
			{
				return NULL;
			}
			//</editor-fold>

			//<editor-fold defaultstate="collapse" desc=">> Вызываем системное событие OnAfterGetObjectProperty">
			$bNext = null;
			$bNext = Events::runEvents(
				'ms.dobrozhil',
				'OnAfterGetObjectProperty',
				[
					'OBJECT_NAME' => $this->_sObjectName,
					'PROPERTY_NAME' => $sPropertyName,
					'PROPERTY_PARAMS' => &$this->_arPropertyValues[$sPropertyName]
				]
			);
			//Если обработчик событий вернет false, значение свойства не будет возвращено
			if ($bNext === FALSE)
			{
				return null;
			}
			//</editor-fold>

			//<editor-fold defaultstate="collapse" desc=">> Запускаем при наличии метод onAfterGetProperty_[propertyName]">
			$bNext = null;
			$bNext = $this->runMethod(
				'onAfterGetProperty_'.$sPropertyName,
				['PROPERTY_PARAMS'=>&$this->_arPropertyValues[$sPropertyName]]
			);
			//Если метод вернет false, значение свойства не будет возвращено
			if ($bNext === FALSE)
			{
				return null;
			}
			//</editor-fold>

			$this->_arIssetProperty[$sPropertyName] = TRUE;
			return $this->getPropertyValue($sPropertyName);
		}
		else
		{
			$this->addError(
				'У вас нет доступа для просмотра свойства #PROPERTY_NAME# объекта #OBJECT_NAME# класса #CLASS_NAME#',
				[
					'PROPERTY_NAME'=>$sPropertyName,
					'OBJECT_NAME' => $this->_sObjectName,
					'CLASS_NAME' => $this->_sClassName
				],
				Errors::ERROR_ACCESS_VIEW
			);
			return NULL;
		}
	}

	final public function setProperty ($sPropertyName, $value)
	{
		//Обрабатываем алиасы основных свойств
		if (
			$sPropertyName == '_class'
			|| $sPropertyName == '_sClassName'
			|| $sPropertyName == '_object'
			|| $sPropertyName == '_sObjectName'
			|| $sPropertyName == '_title'
			|| $sPropertyName == '_sObjectTitle'
		) {
			$this->addError(
				'Невозможно изменить значение системного свойства #PROPERTY_NAME#',
				['PROPERTY_NAME'=>$sPropertyName],
				Errors::ERROR_CHANGE_SYSTEM
			);
			return FALSE;
		}

		//Проверяем правильность написания имени свойства
		if (!$this->checkPropertyName($sPropertyName))
		{
			return FALSE;
		}

		//Проверяем существование свойства
		if (!$this->issetProperty($sPropertyName))
		{
			$this->addError(
				'Свойства с именем #PROPERTY_NAME# у объекта #OBJECT_NAME# не существует',
				['PROPERTY_NAME'=>$sPropertyName,'OBJECT_NAME'=>$this->_sObjectName],
				Errors::ERROR_NOT_EXISTS
			);
			return FALSE;
		}

		//Проверяем права на запись для пользователя
		if (!$this->canWriteProperty($sPropertyName))
		{
			$this->addError(
				'У вас нет прав на изменение значения свойства #PROPERTY_NAME# объекта #OBJECT_NAME#',
				['PROPERTY_NAME'=>$sPropertyName,'OBJECT_NAME'=>$this->_sObjectName],
				Errors::ERROR_ACCESS_EDIT
			);
			return FALSE;
		}


	}

	/**
	 * Проверяет существование свойства объекта
	 *
	 * @param string $sPropertyName Имя свойства
	 * @param null   $sClassName    Имя класса (для рекурсии)
	 *
	 * @return bool
	 */
	private function issetProperty ($sPropertyName, $sClassName=null)
	{
		//Проверяем флаг существования свойства
		if (isset($this->_arIssetProperty[$sPropertyName]))
		{
			return true;
		}

		//Проверяем наличие сохраненного значения свойства
		if (isset($this->_arPropertyValues[$sPropertyName]))
		{
			$this->_arIssetProperty[$sPropertyName] = TRUE;
			return true;
		}

		//Если первоначальная проверка, не рекурсия
		if (is_null($sClassName))
		{
			//Проверяем наличие значения свойства
			$arRes = ObjectsPropertyValuesTable::getOne([
				'select' => ['PROPERTY_NAME'],
				'filter' => [
					'OBJECT_NAME' => $this->_sObjectName,
					'PROPERTY_NAME' => $sPropertyName
				]
			]);
			if ($arRes)
			{
				$this->_arIssetProperty[$sPropertyName] = TRUE;
				return TRUE;
			}

			$sClassName = $this->_sClassName;
		}

		//Проверяем существование свойства класса
		$arRes = ClassPropertiesTable::getOne([
			'select' => [
				'PROPERTY_NAME',
				'CLASS_NAME'
			],
			'filter' => [
				'CLASS_NAME' => $sClassName,
				'PROPERTY_NAME' => $sPropertyName
			]
		]);
		if ($arRes)
		{
			$this->_arIssetProperty[$sPropertyName] = TRUE;
			return TRUE;
		}
		else
		{
			//Получаем имя родительского класса для проверки существования свойства рекурсией
			$arRes = ClassesTable::getOne([
				'select' => ["PARENT_CLASS"],
				'filter' => [
					'CLASS_NAME' => $sClassName
				]
			]);
			if ($arRes && isset($arRes['PARENT_CLASS']) && !is_null($arRes['PARENT_CLASS']))
			{
				return $this->issetProperty($sPropertyName, $arRes['PARENT_CLASS']);
			}
			else
			{
				return FALSE;
			}
		}
	}

	public function runMethod ($sMethodName, $arParams=[])
	{


		return null;
	}

	final public function addError ($sErrorText, $arReplace=[], $iErrorCode=null)
	{
		if (!empty($arReplace))
		{
			foreach ($arReplace as $code=>$replace)
			{
				$sErrorText = str_replace('#'.$code.'#',$replace,$sErrorText);
			}
		}
		$this->errorCollection->setError(
			$sErrorText,
			$iErrorCode
		);
	}

	private function canViewObjectProperty ()
	{
		if (
			!Access::canView('OBJECT_'.$this->_sObjectName.'_PROPERTY',$this->iUserID)
			&& !Access::canViewOwn('OBJECT_'.$this->_sObjectName.'_PROPERTY',$this->iUserID)
		) {
			if (
				!Access::canView('CLASS_'.$this->_sObjectName.'_OBJECT_'.$this->_sObjectName.'_PROPERTY',$this->iUserID)
				&& !Access::canViewOwn('CLASS_'.$this->_sObjectName.'_OBJECT_'.$this->_sObjectName.'_PROPERTY',$this->iUserID)
			) {
				return FALSE;
			}
		}

		return TRUE;
	}

	/**
	 * Сохраняет системные параметры свойства
	 *
	 * @param string $sPropertyName Имя свойства
	 * @param mixed  $value         Обработанное значение
	 * @param string $sType         Тип значения
	 */
	private function saveSystemPropertyValue ($sPropertyName, $value, $sType=Types::BASE_TYPE_STRING)
	{
		$this->_arPropertyValues[$sPropertyName] = [
			'CLASS_NAME' => NULL,
			'PROPERTY_NAME' => $sPropertyName,
			'PROPERTY_TYPE' => $sType,
			'VALUE' => $value,
			'CREATED_BY' => 0,
			'CREATED_DATE' => new Date,
			'UPDATED_BY' => 0,
			'UPDATED_DATE' => new Date()
		];

		$this->_arIssetProperty[$sPropertyName] = true;
	}

	/**
	 * Проверяет, существует ли сохраненное значение свойства
	 *
	 * @param string $sPropertyName Имя свойства
	 *
	 * @return bool
	 */
	final public function issetPropertyValue ($sPropertyName)
	{
		return (isset($this->_arPropertyValues[$sPropertyName]));
	}

	/**
	 * Удаляет сохранянное значение свойства
	 *
	 * @param string $sPropertyName Имя свойства
	 */
	final protected function unsetPropertyValue ($sPropertyName)
	{
		if (isset($this->_arPropertyValues[$sPropertyName]))
		{
			unset($this->_arPropertyValues[$sPropertyName]);
		}
	}

	/**
	 * Возвращает значение свойства, если оно сохранено, либо NULL
	 *
	 * @param string $sPropertyName Имя свойства
	 *
	 * @return null|mixed
	 */
	final protected function getPropertyValue ($sPropertyName)
	{
		if ($this->issetPropertyValue($sPropertyName))
		{
			return $this->_arPropertyValues[$sPropertyName]['VALUE'];
		}

		return null;
	}

	/**
	 * Возвращает имя класса, в котором объявлена переменная
	 *
	 * @param string $sPropertyName Имя переменной
	 *
	 * @return null
	 */
	final protected function getPropertyClassName ($sPropertyName)
	{
		if (
			isset($this->_arPropertyValues[$sPropertyName]['CLASS_NAME'])
			&& !is_null($this->_arPropertyValues[$sPropertyName]['CLASS_NAME'])
			&& $this->_arPropertyValues[$sPropertyName]['CLASS_NAME'] !== true
		) {
			return $this->_arPropertyValues[$sPropertyName]['CLASS_NAME'];
		}

		return null;
	}

	/**
	 * Возвращает тип значения свойства, либо Types::BASE_TYPE_STRING
	 *
	 * @param string $sPropertyName Имя свойства
	 *
	 * @return string
	 */
	final public function getPropertyType ($sPropertyName)
	{
		if (isset($this->_arPropertyValues[$sPropertyName]['TYPE']))
		{
			return $this->_arPropertyValues[$sPropertyName]['TYPE'];
		}

		return Types::BASE_TYPE_STRING;
	}

	/**
	 * Возвращает ID создателя свойства из массива свойств
	 *
	 * @param string $sPropertyName Имя свойства
	 *
	 * @return int
	 */
	final public function getPropertyCreatedBy($sPropertyName)
	{
		if (isset($this->_arPropertyValues[$sPropertyName]['CREATED_BY']))
		{
			return (int)$this->_arPropertyValues[$sPropertyName]['CREATED_BY'];
		}

		return 0;
	}

	/**
	 * Возвращает время создания свойства, либо NULL
	 *
	 * @param string $sPropertyName Имя свойства
	 *
	 * @return null|Date
	 */
	final public function getPropertyCreatedDate ($sPropertyName)
	{
		if (isset($this->_arPropertyValues[$sPropertyName]['CREATED_DATE']))
		{
			return $this->_arPropertyValues[$sPropertyName]['CREATED_DATE'];
		}

		return NULL;
	}

	/**
	 * Возвращает ID пользователя, обновившего значение свойства
	 *
	 * @param string $sPropertyName Имя свойства
	 *
	 * @return int
	 */
	final public function getPropertyUpdatedBy ($sPropertyName)
	{
		if (isset($this->_arPropertyValues[$sPropertyName]['UPDATED_BY']))
		{
			return $this->_arPropertyValues[$sPropertyName]['UPDATED_BY'];
		}

		return 0;
	}

	/**
	 * Возвращает время обновления значения свойства, либо NULL
	 *
	 * @param string $sPropertyName Имя свойства
	 *
	 * @return null|Date
	 */
	final public function getPropertyUpdatedDate ($sPropertyName)
	{
		if (isset($this->_arPropertyValues[$sPropertyName]['UPDATED_DATE']))
		{
			return $this->_arPropertyValues[$sPropertyName]['UPDATED_DATE'];
		}

		return NULL;
	}

	//<editor-fold defaultstate="collapse" desc=">>Методы проверки прав<<">
	/**
	 * Проверяет, есть ли у пользователя права на просмотр свойств
	 * объекта, класса объекта, либо родительских классов объекта
	 *
	 * @param string $sPropertyName Имя свойства
	 *
	 * @return bool
	 */
	private function canViewProperty ($sPropertyName)
	{
		if (isset($this->_arAccess['VIEW'][$sPropertyName]))
		{
			return $this->_arAccess['VIEW'][$sPropertyName];
		}

		$iCreatedBy = $this->getPropertyCreatedBy($sPropertyName);
		//Проверяем права на просмотр свойств объекта
		if (
			(
				(int)$iCreatedBy == $this->iUserID
				|| $this->isSystemUser($iCreatedBy)
			)
			&& (
				Access::canViewOwn('OBJECT_'.$this->_sObjectName.'_PROPERTY',$this->iUserID)
				|| Access::canView('OBJECT_'.$this->_sObjectName.'_PROPERTY',$this->iUserID)
			)
		) {
			$this->_arAccess['VIEW'][$sPropertyName] = TRUE;
			return TRUE;
		}
		elseif (
			(int)$iCreatedBy != $this->iUserID
			&& Access::canView('OBJECT_'.$this->_sObjectName.'_PROPERTY',$this->iUserID)
		) {
			$this->_arAccess['VIEW'][$sPropertyName] = TRUE;
			return TRUE;
		}
		else
		{
			//Проверяем права на просмотр свойств класса или родительских классов
			$this->_arAccess['VIEW'][$sPropertyName] = $this->canViewClassProperty($sPropertyName);
			return $this->_arAccess['VIEW'][$sPropertyName];
		}
	}

	/**
	 * Проверяет, есть ли у пользователя права на просмотр свойств
	 * класса, либо родительских классов
	 *
	 * @param string      $sPropertyName Имя свойства
	 * @param null|string $sClassName    Имя класса
	 *
	 * @return bool
	 */
	private function canViewClassProperty ($sPropertyName, $sClassName=null)
	{
		$iCreatedBy = $this->getPropertyCreatedBy($sPropertyName);
		if (is_null($sClassName))
		{
			$sClassName = $this->_sClassName;
		}
		if (
			(
				(int)$iCreatedBy == $this->iUserID
				|| $this->isSystemUser($iCreatedBy)
			)
			&& (
				Access::canViewOwn('CLASS_'.$sClassName.'_OBJECT_'.$this->_sObjectName.'_PROPERTY',$this->iUserID)
				|| Access::canView('CLASS_'.$sClassName.'_OBJECT_'.$this->_sObjectName.'_PROPERTY',$this->iUserID)
			)
		) {
			return TRUE;
		}
		elseif (
			(int)$iCreatedBy != $this->iUserID
			&& Access::canView('CLASS_'.$sClassName.'_OBJECT_'.$this->_sObjectName.'_PROPERTY',$this->iUserID)
		) {
			return TRUE;
		}
		else
		{
			$arRes = ClassesTable::getOne([
				'select' => ['PARENT_CLASS'],
				'filter' => ['NAME'=>$sClassName]
			]);
			if ($arRes && isset($arRes['CLASS_NAME']))
			{
				//Проверяем права на просмотр свойств родительских классов
				return $this->canViewClassProperty($sPropertyName, $arRes['CLASS_NAME']);
			}

			return FALSE;
		}
	}

	private function canWriteProperty ($sPropertyName)
	{
		if (isset($this->_arAccess['WRITE'][$sPropertyName]))
		{
			return $this->_arAccess['WRITE'][$sPropertyName];
		}

		$iCreatedBy = $this->getPropertyCreatedByFromDB($sPropertyName);
		//Проверяем права на просмотр свойств объекта
		if (
			(
				(int)$iCreatedBy == $this->iUserID
				|| $this->isSystemUser($iCreatedBy)
			)
			&& (
				Access::canWriteOwn('OBJECT_'.$this->_sObjectName.'_PROPERTY',$this->iUserID)
				|| Access::canWrite('OBJECT_'.$this->_sObjectName.'_PROPERTY',$this->iUserID)
			)
		) {
			$this->_arAccess['WRITE'][$sPropertyName] = TRUE;
			return TRUE;
		}
		elseif (
			(int)$iCreatedBy != $this->iUserID
			&& Access::canWrite('OBJECT_'.$this->_sObjectName.'_PROPERTY',$this->iUserID)
		) {
			$this->_arAccess['WRITE'][$sPropertyName] = TRUE;
			return TRUE;
		}
		else
		{
			//Проверяем права на просмотр свойств класса или родительских классов
			$this->_arAccess['WRITE'][$sPropertyName] = $this->canWriteClassProperty($sPropertyName);
			return $this->_arAccess['WRITE'][$sPropertyName];
		}
	}

	private function getPropertyCreatedByFromDB ($sPropertyName, $sClassName=null)
	{
		if (is_null($sClassName))
		{
			$sClassName = $this->_sClassName;
		}

		$arRes = ClassPropertiesTable::getOne([
			'select' => ['CREATED_BY'],
			'filter' => [
				'CLASS_NAME' => $sClassName,
				'PROPERTY_NAME' => $sPropertyName
			]
		]);
		if ($arRes && (int)$arRes['CREATED_BY']>0)
		{
			$iCreatedBy = (int)$arRes['CREATED_BY'];
		}
		else
		{
			$iCreatedBy = 0;
		}

		return $iCreatedBy;
	}

	private function canWriteClassProperty ($sPropertyName, $sClassName=null)
	{
		$iCreatedBy = $this->getPropertyCreatedBy($sPropertyName);
		if (is_null($sClassName))
		{
			$sClassName = $this->_sClassName;
		}
		if (
			(
				(int)$iCreatedBy == $this->iUserID
				|| $this->isSystemUser($iCreatedBy)
			)
			&& (
				Access::canViewOwn('CLASS_'.$sClassName.'_OBJECT_'.$this->_sObjectName.'_PROPERTY',$this->iUserID)
				|| Access::canView('CLASS_'.$sClassName.'_OBJECT_'.$this->_sObjectName.'_PROPERTY',$this->iUserID)
			)
		) {
			return TRUE;
		}
		elseif (
			(int)$iCreatedBy != $this->iUserID
			&& Access::canView('CLASS_'.$sClassName.'_OBJECT_'.$this->_sObjectName.'_PROPERTY',$this->iUserID)
		) {
			return TRUE;
		}
		else
		{
			$arRes = ClassesTable::getOne([
				'select' => ['PARENT_CLASS'],
				'filter' => ['NAME'=>$sClassName]
			]);
			if ($arRes && isset($arRes['CLASS_NAME']))
			{
				//Проверяем права на просмотр свойств родительских классов
				return $this->canViewClassProperty($sPropertyName, $arRes['CLASS_NAME']);
			}

			return FALSE;
		}

	}

	//</editor-fold>


}