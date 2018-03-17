<?php
/**
 * Класс для работы с объектами
 *
 * @package Ms\Dobrozhil
 * @subpackage Lib
 * @author Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright 2018 Mikhail Sergeev
 */

namespace Ms\Dobrozhil\Lib;

use Ms\Core\Entity\Db\Query\QueryBase;
use Ms\Core\Entity\Db\SqlHelper;
use Ms\Core\Entity\ErrorCollection;
use Ms\Core\Entity\Type\Date;
use Ms\Core\Lib\Events;
use Ms\Core\Lib\Loader;
use Ms\Dobrozhil\Entity\Objects\Base;
use Ms\Dobrozhil\Tables\ObjectsTable;
use Ms\Dobrozhil\Tables;
use Ms\Core\Lib\Loc;

Loc::includeLocFile(__FILE__);

/**
 * Class Objects
 *
 * @package Ms\Dobrozhil
 * @subpackage Lib
 */
class Objects
{
	/**
	 * @var null|ErrorCollection
	 */
	private static $errorCollection = null;

	/* CHECK */

	/**
	 * Проверяет существование заданного объекта
	 *
	 * @param string $sObjectName Имя объекта
	 *
	 * @return bool
	 */
	public static function checkObjectExists ($sObjectName)
	{
		$arRes = ObjectsTable::getOne(
			array(
				'select' => 'NAME',
				'filter' => array('NAME'=>$sObjectName)
			)
		);

		return (!!$arRes);
	}

	/* ADD */

	/**
	 * Добавляет новый объект
	 *
	 * @param string      $sObjectName Имя объекта
	 * @param string      $sClassName  Имя класса объекта
	 * @param null|string $sNote       Описание объекта
	 * @param null|string $sRoomName   Имя комнаты объекта
	 *
	 * @return bool
	 */
	public static function addNewObject ($sObjectName, $sClassName, $sNote=null, $sRoomName=null)
	{
		$arAdd = array();
		if (!isset($sObjectName))
		{
			//'Не задано имя объекта'
			static::addError(Loc::getModuleMessage('ms.dobrozhil','error_no_name'),'NO_NAME');
			return false;
		}
		elseif (!Classes::checkName($sObjectName))
		{
			//'Имя объекта содержит запрещенные символы'
			static::addError(Loc::getModuleMessage('ms.dobrozhil','error_wrong_symbols'),'WRONG_SYMBOL');
			return false;
		}
		else
		{
			$arAdd['NAME'] = $sObjectName;
		}

		if (!isset($sClassName))
		{
			//'Не задано имя класса объекта'
			static::addError(Loc::getModuleMessage('ms.dobrozhil','error_no_class'),'NO_CLASS');
			return false;
		}
		elseif (!Classes::checkName($sClassName))
		{
			//'Имя класса содержит запрещенные символы'
			static::addError(Loc::getModuleMessage('ms.dobrozhil','error_class_wrong_symbols'),'CLASS_WRONG_SYMBOLS');
			return false;
		}
		elseif (!Classes::checkClassExists($sClassName))
		{
			//'Указанный класс не существует'
			static::addError(Loc::getModuleMessage('ms.dobrozhil','error_class_no_exists'),'CLASS_NO_EXISTS');
			return false;
		}
		else
		{
			$arAdd['CLASS_NAME'] = $sClassName;
		}

		if (!is_null($sNote))
		{
			$arAdd['NOTE'] = $sNote;
		}

		if (!is_null($sRoomName))
		{
			if (!Classes::checkName($sRoomName))
			{
				//'Имя объекта комнаты содержит запрещенные символы'
				static::addError(Loc::getModuleMessage('ms.dobrozhil','error_room_wrong_symbols'),'ROOM_WRONG_SYMBOLS');
				return false;
			}
			elseif (!static::checkObjectExists($sRoomName))
			{
				//'Заданный объект комнаты не существует'
				static::addError(Loc::getModuleMessage('ms.dobrozhil','error_room_no_exists'),'ROOM_NO_EXISTS');
				return false;
			}
			else
			{
				$arAdd['ROOM_NAME'] = $sRoomName;
			}
		}

		$res = ObjectsTable::add($arAdd);
		if (!$res)
		{
			//'Не удалось добавить новый объект'
			static::addError(Loc::getModuleMessage('ms.dobrozhil','error_no_add'),'NO_ADD');
			return false;
		}

		return true;
	}

	/* GET */

	public static function getObject($sObjectName)
	{
		if (!static::checkObjectExists($sObjectName))
		{
			//'Объект с заданным именем не был найден'
			static::addError(Loc::getModuleMessage('ms.dobrozhil','error_object_not_found'),'OBJECT_NOT_FOUND');
			return false;
		}

		if (!$className = static::getClassByObject($sObjectName))
		{
			//'Не удалось определить класс объекта'
			static::addError(Loc::getModuleMessage('ms.dobrozhil','error_object_class_not_found'),'OBJECT_CLASS_NOT_FOUND');
			return false;
		}

		$parentsList = Classes::getParentsList($className);
		if (!$parentsList || empty($parentsList))
		{
			//'Не удалось определить родителей класса объекта'
			static::addError(Loc::getModuleMessage('ms.dobrozhil','error_object_class_parent_not_found'),'OBJECT_CLASS_PARENT_NOT_FOUND');
			return false;
		}

		$parentsList = array_reverse($parentsList);
		foreach ($parentsList as $ar_parent)
		{
			$parentParams = Classes::getClassParams($ar_parent['CLASS_NAME']);
			//TODO: Протестировать родительские программные классы
			if (!is_null($parentParams['NAMESPACE']))
			{
				if (!is_null($parentParams['MODULE']) && $parentParams['MODULE']!='ms.dobrozhil')
				{
					if (Loader::issetModule($parentParams['MODULE']) && Loader::includeModule($parentParams))
					{
						return new $parentParams['NAMESPACE']($sObjectName);
					}
				}
				else
				{
					return new $parentParams['NAMESPACE']($sObjectName);
				}
			}
			elseif (is_null($parentParams['PARENT_CLASS']))
			{
				return new Base($sObjectName);
			}
		}

		//'Непредвиденная ошибка'
		static::addError(Loc::getModuleMessage('ms.dobrozhil','error'),'ERROR');
		return false;
	}

	/**
	 * Возвращает список ошибок, возникших в ходе работы методов класса
	 *
	 * @return ErrorCollection|null
	 */
	public static function getErrors ()
	{
		return static::$errorCollection;
	}

	/**
	 * Возвращает заданные параметры объекта, либо false
	 *
	 * @param string $sObjectName    Имя объекта
	 * @param string|array $arParams Имя или массив параметров объекта
	 *
	 * @return array|bool|string
	 */
	public static function getObjectParams ($sObjectName, $arParams='*')
	{
		if (!Classes::checkName($sObjectName))
		{
			//'Имя объекта содержит запрещенные символы'
			static::addError(Loc::getModuleMessage('ms.dobrozhil','error_wrong_symbols'),'WRONG_SYMBOL');
			return false;
		}

		$arRes = Tables\ObjectsTable::getOne(
			array(
				'select' => $arParams,
				'filter' => array('NAME'=>$sObjectName)
			)
		);
		if ($arRes)
		{
			if (!is_array($arParams) && $arParams != '*')
			{
				return $arRes[$arParams];
			}
		}

		return $arRes;
	}

	/**
	 * Возвращает параметры класса, через таблицу объектов
	 *
	 * @param string $sObjectName Имя объекта
	 * @param array  $arParams    Имя или массив параметров класса
	 *
	 * @return array|bool|string
	 */
	public static function getClassByObject($sObjectName, $arParams=array())
	{
		if (!Classes::checkName($sObjectName))
		{
			//'Имя объекта содержит запрещенные символы'
			static::addError(Loc::getModuleMessage('ms.dobrozhil','error_wrong_symbols'),'WRONG_SYMBOL');
			return false;
		}

		$arSelect = array('NAME','CLASS_NAME');
		if (!is_array($arParams))
		{
			$arParams = array($arParams);
		}
		if (!empty($arParams))
		{
			foreach ($arParams as $param)
			{
				$arSelect['CLASS_NAME.'.$param] = 'CLASS_'.$param;
			}
		}

		return static::getObjectParams($sObjectName,$arSelect);
	}

	/**
	 * Возвращает значение свойства объекта
	 *
	 * @param string $sObjectName   Имя объекта
	 * @param string $sPropertyName Имя свойства объекта
	 *
	 * @return mixed
	 */
	public static function getProperty($sObjectName, $sPropertyName)
	{
		if (!Classes::checkName($sObjectName))
		{
			//'Имя объекта содержит запрещенные символы'
			static::addError(Loc::getModuleMessage('ms.dobrozhil','error_wrong_symbols'),'WRONG_SYMBOL');
			return false;
		}
		if (!Classes::checkName($sPropertyName))
		{
			//'Имя свойства содержит запрещенные символы'
			static::addError(Loc::getModuleMessage('ms.dobrozhil','error_property_wrong_symbols'),'PROPERTY_WRONG_SYMBOLS');
			return false;
		}

		$arRes = static::getPropertyValueInfo($sObjectName,$sPropertyName);

		if (!$arRes || is_null($arRes['VALUE']))
		{
			return null;
		}
		elseif (!is_null($arRes['TYPE']))
		{
			$arRes['VALUE'] = Types::prepareValueFrom($arRes['VALUE'],strtolower($arRes['TYPE']));
			return $arRes['VALUE'];
		}
		else
		{
			//Получаем имя класса объекта
			$arClass = static::getClassByObject($sObjectName);
			if (!$arClass)
			{
				return $arRes['VALUE'];
			}
			//Получаем тип значения свойства
			if ($valueType = Classes::getClassPropertiesParams($arClass['CLASS_NAME'],$sPropertyName,'TYPE'))
			{
				if (!is_null($valueType))
				{
					$valueType = strtolower($valueType);
				}
				else
				{
					$valueType = 'string';
				}
				$arRes['VALUE'] = Types::prepareValueFrom($arRes['VALUE'],$valueType);
			}
			return $arRes['VALUE'];
		}
	}

	/**
	 * Возвращает параметры значения указанного свойства объекта
	 *
	 * @param string $sObjectName   Название объекта
	 * @param string $sPropertyName Название свойства
	 *
	 * @return array|bool|string
	 */
	public static function getPropertyValueInfo ($sObjectName,$sPropertyName)
	{
		if (!Classes::checkName($sObjectName))
		{
			//'Имя объекта содержит запрещенные символы'
			static::addError(Loc::getModuleMessage('ms.dobrozhil','error_wrong_symbols'),'WRONG_SYMBOL');
			return false;
		}
		if (!Classes::checkName($sPropertyName))
		{
			//'Имя свойства содержит запрещенные символы'
			static::addError(Loc::getModuleMessage('ms.dobrozhil','error_property_wrong_symbols'),'PROPERTY_WRONG_SYMBOLS');
			return false;
		}

		return Tables\ObjectsPropertyValuesTable::getOne(
			array(
				'filter' => array('NAME'=>$sObjectName.'.'.$sPropertyName)
			)
		);
	}

	/**
	 * Возвращает список со значениями всех свойств объекта, включая наследуемые свойства
	 *
	 * @param string $sObjectName Имя объекта
	 *
	 * @return array|bool
	 */
	public static function getAllProperties ($sObjectName)
	{
		if (!Classes::checkName($sObjectName))
		{
			//'Имя объекта содержит запрещенные символы'
			static::addError(Loc::getModuleMessage('ms.dobrozhil','error_wrong_symbols'),'WRONG_SYMBOL');
			return false;
		}

		$arProperties = array();
		$objectClassName = static::getClassByObject($sObjectName);
		if (!$objectClassName)
		{
			//'Не удалось определить класс объекта'
			static::addError(Loc::getModuleMessage('ms.dobrozhil','error_no_class_object'),'NO_CLASS_OBJECT');
			return false;
		}
		$objectClassName = $objectClassName['CLASS_NAME'];
		$parent = Classes::getClassParams($objectClassName,'PARENT_CLASS');
		if (Classes::checkName($parent))
		{
			$arClassParent = Classes::getParentsList($parent);
			$arClassParent[] = $objectClassName;
			while (count($arClassParent)>0)
			{
				$className = array_pop($arClassParent);
				static::getProperties($className, $sObjectName, $arProperties);
			}
		}
		else
		{
			static::getProperties($objectClassName,$sObjectName,$arProperties);
		}

		return $arProperties;
	}

	/**
	 * Получает список свойств для заданного объекта в рамках заданного класса
	 * и возвращает их в массиве, переданном третьим параметром
	 *
	 * @param string $className     Имя класса
	 * @param string $sObjectName   Имя объекта
	 * @param array &$arProperties Результирующий массив со списком свойств
	 *
	 * @return void
	 */
	public static function getProperties($className, $sObjectName, &$arProperties)
	{
		$arRes = Classes::getClassPropertiesList($className,'*');
		if ($arRes)
		{
			foreach ($arRes as $ar_res)
			{
				$arRes2 = Tables\ObjectsPropertyValuesTable::getOne(
					array(
						'select' => array('VALUE'),
						'filter' => array('NAME'=>$sObjectName.'.'.$ar_res['PROPERTY_NAME'])
					)
				);
				if ($arRes2)
				{
					if (!isset($arProperties[$ar_res['PROPERTY_NAME']]))
					{
						$arProperties[$ar_res['PROPERTY_NAME']] = $arRes2['VALUE'];
					}
				}
				else
				{
					if (!isset($arProperties[$ar_res['PROPERTY_NAME']]))
					{
						$arProperties[$ar_res['PROPERTY_NAME']] = null;
					}
				}
			}
		}
	}

	/**
	 * Возвращает значение системного свойства
	 *
	 * @param string $sPropertyName Имя свойства системного объекта
	 *
	 * @return bool|mixed
	 */
	public static function getSystem ($sPropertyName)
	{
		if (!Classes::checkName($sPropertyName))
		{
			//'Имя свойства содержит запрещенные символы'
			static::addError(Loc::getModuleMessage('ms.dobrozhil','error_property_wrong_symbols'),'PROPERTY_WRONG_SYMBOLS');
			return false;
		}

		return static::getProperty('System',$sPropertyName);
	}

	/**
	 * Возаращает список объектов указанного класса, либо false
	 *
	 * @param string $sClassName
	 *
	 * @return array|bool
	 */
	public static function getObjectsListByClassName ($sClassName)
	{
		if (!Classes::checkName($sClassName))
		{
			//'Имя класса содержит запрещенные символы'
			static::addError(Loc::getModuleMessage('ms.dobrozhil','error_class_wrong_symbols'),'CLASS_WRONG_SYMBOLS');
			return false;
		}

		$arRes = ObjectsTable::getList(
			array(
				'select' => 'NAME',
				'filter' => array('CLASS_NAME'=>$sClassName)
			)
		);
		if (!$arRes || empty($arRes))
		{
			//'Не удалось найти ни одного объекта заданного класса'
			static::addError(Loc::getModuleMessage('ms.dobrozhil','error_no_objects_in_class'),'NO_OBJECTS_IN_CLASS');
			return false;
		}

		$arList = array();
		foreach ($arRes as $obj)
		{
			$arList[] = $obj['NAME'];
		}

		return $arList;
	}

	/* SET */

	/**
	 * Устанавливает значение свойства объекта, также при необходимости пишет историю
	 *
	 * @param string $sObjectName   Имя объекта
	 * @param string $sPropertyName Имя свойства объекта
	 * @param mixed  $mValue        Значение свойства объекта
	 *
	 * @return bool
	 */
	public static function setProperty ($sObjectName, $sPropertyName, $mValue=null)
	{
		if (!isset($sObjectName))
		{
			//'Не задано имя объекта'
			static::addError(Loc::getModuleMessage('ms.dobrozhil','error_no_name'),'NO_NAME');
			return false;
		}
		elseif (!Classes::checkName($sObjectName))
		{
			//'Имя объекта содержит запрещенные символы'
			static::addError(Loc::getModuleMessage('ms.dobrozhil','error_wrong_symbols'),'WRONG_SYMBOL');
			return false;
		}
		elseif (!static::checkObjectExists($sObjectName))
		{
			//'Указанный объект не существует'
			static::addError(Loc::getModuleMessage('ms.dobrozhil','error_no_exists'),'NO_EXISTS');
			return false;
		}

		if (!isset($sPropertyName))
		{
			//'Не задано имя свойства объекта'
			static::addError(Loc::getModuleMessage('ms.dobrozhil','error_property_no_name'),'PROPERTY_NO_NAME');
			return false;
		}
		elseif (!Classes::checkName($sPropertyName))
		{
			//'Имя свойства объекта содержит запрещенные символы'
			static::addError(Loc::getModuleMessage('ms.dobrozhil','error_property_wrong_symbols'),'PROPERTY_WRONG_SYMBOL');
			return false;
		}

		//Сначала получаем текущее значение свойства
		$arNowValue = static::getPropertyValueInfo($sObjectName,$sPropertyName);

		//Получаем имя класса
		$objectClassName = static::getClassByObject($sObjectName);
		if (!$objectClassName)
		{
			//'Не удалось определить класс объекта'
			static::addError(Loc::getModuleMessage('ms.dobrozhil','error_no_class_object'),'NO_CLASS_OBJECT');
			return false;
		}
		$objectClassName = $objectClassName['CLASS_NAME'];

		//Получаем параметры свойства
		$arParams = Classes::getClassPropertiesParams($objectClassName,$sPropertyName,array('SAVE_IDENTICAL_VALUES','HISTORY','TYPE'));

		//Получаем тип данных для свойства класса
		$valueType = strtolower($arParams['TYPE']);

		//Преобразуем новое значение в значение для БД
		$mValue = Types::prepareValueTo($mValue,$valueType);

		//Если такого свойства не существует, автоматически создаем новое свойство объекта и записываем в него null
		if (!$arNowValue)
		{
			Tables\ObjectsPropertyValuesTable::add(array('NAME' => $sObjectName.'.'.$sPropertyName));
			$arNowValue = static::getPropertyValueInfo($sObjectName,$sPropertyName);
		}

		if ($arNowValue && !$arParams)
		{
			$arParams = array(
				'SAVE_IDENTICAL_VALUES' => false,
				'HISTORY' => 0
			);
		}

		$bSave = false;

		//Если значения равны
		if ($arNowValue['VALUE'] == $mValue)
		{
			//Если требуется сохранять равные значения
			if ($arParams['SAVE_IDENTICAL_VALUES']===true)
			{
				$bSave = true;
			}
		}
		else
		{
			$bSave = true;
		}

		$arUpdate = array('UPDATED'=>new Date());
		//Если ведется история, чистим от всего лишнего
		if ((int)$arParams['HISTORY']>0)
		{
			$helper = new SqlHelper(Tables\ObjectsPropertyValuesHistoryTable::getTableName());
			$nowDate = new Date();
			$nowDate->modify('-'.$arParams['HISTORY'].' day');
			$sql = 'DELETE FROM '.$helper->wrapTableQuotes().' WHERE '.$helper->wrapFieldQuotes('DATETIME')
				.' < '.$nowDate->getDateTimeDB();
			$query = new QueryBase($sql);
			$query->exec();
		}

		//Если нужно записывать значение
		if ($bSave)
		{
			//Если ведется история, пишем старое значение в историю
			if ((int)$arParams['HISTORY']>0)
			{
				Tables\ObjectsPropertyValuesHistoryTable::add(
					array(
						'NAME'=>$sObjectName.'.'.$sPropertyName,
						'VALUE' => $arNowValue['VALUE'],
						'DATETIME' => $arNowValue['UPDATED']
					)
				);
			}

			$arUpdate['VALUE'] = $mValue;
		}

		//Обновляем либо значение, либо только время обновления свойства
		$res = Tables\ObjectsPropertyValuesTable::update($sObjectName.'.'.$sPropertyName,$arUpdate);
		if ($res->getResult())
		{
			if ($bSave)
			{
				//При изменении свойства запускаем метод класса объекта
				if ($obj = static::getObject($sObjectName))
				{
					$obj->runMethod('onChange_'.$sPropertyName);
				}

				//А также запускаем событие изменения свойства
				Events::runEvents('ms.dobrozhil','OnChangeObjectProperty_'.$sPropertyName);

				return true;
			}
		}

		return false;
	}

	/**
	 * Устанавливает значение системного свойства
	 *
	 * @param string $sPropertyName Имя свойства
	 * @param mixed  $mValue        Значение свойства
	 *
	 * @return bool
	 */
	public static function setSystem ($sPropertyName, $mValue)
	{
		if (!Classes::checkName($sPropertyName))
		{
			//
			static::addError(Loc::getModuleMessage('ms.dobrozhil','error_property_wrong_symbols'),'PROPERTY_WRONG_SYMBOLS');
			return false;
		}

		return static::setProperty('System',$sPropertyName, $mValue);
	}

	/* PRIVATE */

	/**
	 * Добавляет новую ошибку в коллекцию
	 *
	 * @param string $sMessage Сообщение об ошибке
	 * @param string $sCode Код ошибки
	 */
	private static function addError($sMessage, $sCode=null)
	{
		if (is_null(static::$errorCollection))
		{
			static::$errorCollection = new ErrorCollection();
		}
		static::$errorCollection->setError($sMessage,$sCode);
	}

}