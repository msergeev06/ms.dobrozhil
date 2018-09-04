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
use Ms\Core\Lib\Logs;
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

	/**
	 * Проверяет существование заданного объекта
	 * @deprecated
	 * @see Objects::checkExists()
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

	/**
	 * Проверяет правильность имени объекта.
	 * Требования те же, что и для имени класса
	 * @deprecated
	 * @see Objects::checkName()
	 *
	 * @param string $sObjectName Имя объекта
	 *
	 * @uses Classes::checkName
	 *
	 * @return bool
	 */
	public static function checkObjectName ($sObjectName)
	{
		return Classes::checkName($sObjectName);
	}

	/**
	 * Проверяет правильности имени свойства объекта
	 * Требования те же, что и для имени класса
	 * @deprecated
	 * @see Classes::checkPropertyName()
	 *
	 * @param string $sPropertyName Имя свойства объекта
	 *
	 * @uses Classes::checkName
	 *
	 * @return bool
	 */
	public static function checkObjectPropertyName ($sPropertyName)
	{
		return Classes::checkPropertyName($sPropertyName);
	}

	//<editor-fold defaultstate="collapsed" desc="Check methods">
	/* CHECK */

	/**
	 * Проверяет существование заданного объекта
	 *
	 * @param string $sObjectName Имя объекта
	 *
	 * @return bool
	 */
	public static function checkExists ($sObjectName)
	{
		$arRes = ObjectsTable::getOne(
			array(
				'select' => 'NAME',
				'filter' => array('NAME'=>$sObjectName)
			)
		);

		return (!!$arRes);
	}

	/**
	 * Проверяет правильность имени объекта.
	 * Требования те же, что и для имени класса
	 *
	 * @param string $sObjectName Имя объекта
	 *
	 * @uses Classes::checkName
	 *
	 * @return bool
	 */
	public static function checkName ($sObjectName)
	{
		return Classes::checkName($sObjectName);
	}

	//</editor-fold>

	//<editor-fold defaultstate="collapsed" desc="Add methods">
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
			static::addError(
				Loc::getModuleMessage(
					'ms.dobrozhil',
					'error_no_name'
				),
				'NO_NAME'
			);
			return false;
		}
		elseif (!static::checkObjectName($sObjectName))
		{
			//'Имя объекта содержит запрещенные символы'
			static::addError(
				Loc::getModuleMessage(
					'ms.dobrozhil',
					'error_wrong_symbols'
				),
				'WRONG_SYMBOL'
			);
			return false;
		}
		else
		{
			$arAdd['NAME'] = $sObjectName;
		}

		if (!isset($sClassName))
		{
			//'Не задано имя класса объекта'
			static::addError(
				Loc::getModuleMessage(
					'ms.dobrozhil',
					'error_no_class'
				),
				'NO_CLASS'
			);
			return false;
		}
		elseif (!Classes::checkName($sClassName))
		{
			//'Имя класса содержит запрещенные символы'
			static::addError(
				Loc::getModuleMessage(
					'ms.dobrozhil',
					'error_class_wrong_symbols'
				),
				'CLASS_WRONG_SYMBOLS'
			);
			return false;
		}
		elseif (!Classes::checkClassExists($sClassName))
		{
			//'Указанный класс не существует'
			static::addError(
				Loc::getModuleMessage(
					'ms.dobrozhil',
					'error_class_no_exists'
				),
				'CLASS_NO_EXISTS'
			);
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
				static::addError(
					Loc::getModuleMessage(
						'ms.dobrozhil',
						'error_room_wrong_symbols'
					),
					'ROOM_WRONG_SYMBOLS'
				);
				return false;
			}
			elseif (!static::checkObjectExists($sRoomName))
			{
				//'Заданный объект комнаты не существует'
				static::addError(
					Loc::getModuleMessage(
						'ms.dobrozhil',
						'error_room_no_exists'
					),
					'ROOM_NO_EXISTS'
				);
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
			static::addError(
				Loc::getModuleMessage(
					'ms.dobrozhil',
					'error_no_add'
				),
				'NO_ADD'
			);
			return false;
		}

		return true;
	}
	//</editor-fold>

	//<editor-fold defaultstate="collapsed" desc="Get methods">
	/* GET */

	/**
	 * Возвращает программный объект указанного объекта
	 *
	 * @param string $sObjectName Имя объекта
	 *
	 * @return bool|Base
	 */
	public static function getObject($sObjectName)
	{
		if (!static::checkObjectName($sObjectName))
		{
			//'Имя объекта содержит запрещенные символы'
			static::addError(
				Loc::getModuleMessage(
					'ms.dobrozhil',
					'error_wrong_symbols'
				),
				'WRONG_SYMBOL'
			);
			return false;
		}
		elseif (!static::checkObjectExists($sObjectName))
		{
			//'Объект с заданным именем не был найден'
			static::addError(
				Loc::getModuleMessage(
					'ms.dobrozhil',
					'error_object_not_found'
				),
				'OBJECT_NOT_FOUND'
			);
			return false;
		}

		if (!$className = static::getClassByObject($sObjectName))
		{
			//'Не удалось определить класс объекта'
			static::addError(
				Loc::getModuleMessage(
					'ms.dobrozhil',
					'error_object_class_not_found'
				),
				'OBJECT_CLASS_NOT_FOUND'
			);
			return false;
		}

		$parentsList = Classes::getParentsList($className);
		if (!$parentsList || empty($parentsList))
		{
			//'Не удалось определить родителей класса объекта'
			static::addError(
				Loc::getModuleMessage(
					'ms.dobrozhil',
					'error_object_class_parent_not_found'
				),
				'OBJECT_CLASS_PARENT_NOT_FOUND'
			);
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
		static::addError(
			Loc::getModuleMessage(
				'ms.dobrozhil',
				'error'
			),
			'ERROR'
		);
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
		if (!static::checkObjectName($sObjectName))
		{
			//'Имя объекта содержит запрещенные символы'
			static::addError(
				Loc::getModuleMessage(
					'ms.dobrozhil',
					'error_wrong_symbols'
				),
				'WRONG_SYMBOL'
			);
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
		if (!static::checkObjectName($sObjectName))
		{
			//'Имя объекта содержит запрещенные символы'
			static::addError(
				Loc::getModuleMessage(
					'ms.dobrozhil',
					'error_wrong_symbols'
				),
				'WRONG_SYMBOL'
			);
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
		/**
		 * @var Base $object
		 */
		$obj = static::getObject($sObjectName);
		if ($obj->isObject())
		{
			return $object->getProperty($sPropertyName);
		}

		return false;
	}

	/**
	 * Возвращает параметры значения указанного свойства объекта
	 *
	 * @param string $sObjectName   Название объекта
	 * @param string $sPropertyName Название свойства
	 * @see Base::getProperty()
	 *
	 * @return array|bool|string
	 */
	public static function getPropertyValueInfo ($sObjectName,$sPropertyName)
	{
		if (!static::checkName($sObjectName))
		{
			//'Имя объекта содержит запрещенные символы'
			static::addError(
				Loc::getModuleMessage(
					'ms.dobrozhil',
					'error_wrong_symbols'
				),
				'WRONG_SYMBOL'
			);
			return false;
		}
		if (!Classes::checkPropertyName($sPropertyName))
		{
			//'Имя свойства содержит запрещенные символы'
			static::addError(
				Loc::getModuleMessage(
					'ms.dobrozhil',
					'error_property_wrong_symbols'
				),
				'PROPERTY_WRONG_SYMBOLS'
			);
			return false;
		}

		//Получаем массив параметров значения свойства
		$arPropValue = Tables\ObjectsPropertyValuesTable::getOne(
			array(
				'filter' => array('NAME'=>$sObjectName.'.'.$sPropertyName)
			)
		);
		if ($arPropValue && !is_null($arPropValue['TYPE']))
		{
			//Преобразуем значение свойства в нужный формат
			$arPropValue['VALUE'] = Types::prepareValueFrom($arPropValue['VALUE'],$arPropValue['TYPE']);
		}

		return $arPropValue;
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
		if (!static::checkObjectName($sObjectName))
		{
			//'Имя объекта содержит запрещенные символы'
			static::addError(
				Loc::getModuleMessage(
					'ms.dobrozhil',
					'error_wrong_symbols'
				),
				'WRONG_SYMBOL'
			);
			return false;
		}

		$arProperties = array();
		$objectClassName = static::getClassByObject($sObjectName);
		if (!$objectClassName)
		{
			//'Не удалось определить класс объекта'
			static::addError(
				Loc::getModuleMessage(
					'ms.dobrozhil',
					'error_no_class_object'
				),
				'NO_CLASS_OBJECT'
			);
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
		$obj = static::getObject($sObjectName);
		$arRes = Classes::getClassPropertiesList($className,'*');
		if ($arRes)
		{
			foreach ($arRes as $ar_res)
			{
				if (!isset($arProperties[$ar_res['PROPERTY_NAME']]))
				{
					$arProperties[$ar_res['PROPERTY_NAME']] = $obj->getProperty($ar_res['PROPERTY_NAME']);
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
		if (!static::checkObjectPropertyName($sPropertyName))
		{
			//'Имя свойства содержит запрещенные символы'
			static::addError(
				Loc::getModuleMessage(
					'ms.dobrozhil',
					'error_property_wrong_symbols'
				),
				'PROPERTY_WRONG_SYMBOLS'
			);
			return false;
		}

		return static::getProperty('System',$sPropertyName);
	}

	/**
	 * Возаращает список объектов указанного класса, либо false
	 *
	 * @param string $sClassName Имя класса
	 *
	 * @return array
	 */
	public static function getObjectsListByClassName ($sClassName)
	{
		if (!Classes::checkName($sClassName))
		{
			//'Имя класса содержит запрещенные символы'
			static::addError(
				Loc::getModuleMessage(
					'ms.dobrozhil',
					'error_class_wrong_symbols'
				),
				'CLASS_WRONG_SYMBOLS'
			);
//			return false;
			return array();
		}

		$arRes = ObjectsTable::getList(
			array(
				'select' => 'NAME',
				'filter' => array('CLASS_NAME'=>$sClassName)
			)
		);
		if (!$arRes || empty($arRes))
		{
/*			//'Не удалось найти ни одного объекта заданного класса'
			static::addError(
				Loc::getModuleMessage(
					'ms.dobrozhil',
					'error_no_objects_in_class'
				),
				'NO_OBJECTS_IN_CLASS'
			);
			return false;*/
			return array ();
		}

		$arList = array();
		foreach ($arRes as $obj)
		{
			$arList[] = $obj['NAME'];
		}

		return $arList;
	}
	//</editor-fold>

	//<editor-fold defaultstate="collapsed" desc="Set methods">
	/* SET */

	/**
	 * Устанавливает значение свойства объекта, также при необходимости пишет историю
	 *
	 * @param string $sObjectName   Имя объекта
	 * @param string $sPropertyName Имя свойства объекта
	 * @param mixed  $mValue        Значение свойства объекта
	 * @see Base::setProperty()
	 *
	 * @return bool
	 */
	public static function setProperty ($sObjectName, $sPropertyName, $mValue=null)
	{
		/**
		 * @var Base $obj
		 */
		$obj = Objects::getObject($sObjectName);
		if ($obj->isObject())
		{
			return $obj->setProperty($sPropertyName,$mValue);
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
		if (!static::checkObjectPropertyName($sPropertyName))
		{
			//Имя свойства содержит некорректные символы
			static::addError(
				Loc::getModuleMessage(
					'ms.dobrozhil',
					'error_property_wrong_symbols'
				),
				'PROPERTY_WRONG_SYMBOLS'
			);
			return false;
		}

		return static::setProperty('System',$sPropertyName, $mValue);
	}
	//</editor-fold>

	//<editor-fold defaultstate="collapsed" desc="Sevice methods">
	/* SERVICE */

	public static function clearOldHistory ($sObjectProperty, $historyDays=0)
	{
		$historyDays = (int)$historyDays;
		if ($historyDays > 0)
		{
			$helper = new SqlHelper(Tables\ObjectsPropertyValuesHistoryTable::getTableName());
			$nowDate = new Date();
			$nowDate->modify('-'.$historyDays.' day');
			$sql = 'DELETE FROM '.$helper->wrapTableQuotes().' WHERE '
				.$helper->wrapTableQuotes('NAME')
				.' = "'.$sObjectProperty.'" AND '
				.$helper->wrapFieldQuotes('DATETIME')
				.' < "'.$nowDate->getDateTimeDB().'"';
			$query = new QueryBase($sql);
			$query->exec();
		}
	}

	/**
	 * Добавляет новую ошибку в коллекцию
	 *
	 * @param string $sMessage Сообщение об ошибке
	 * @param string $sCode Код ошибки
	 */
	public static function addError($sMessage, $sCode=null)
	{
		if (is_null(static::$errorCollection))
		{
			static::$errorCollection = new ErrorCollection();
		}
		static::$errorCollection->setError($sMessage,$sCode);
		Logs::setError($sMessage);
	}
	//</editor-fold>

	//<editor-fold defaultstate="collapsed" desc="Private methods">
	/* PRIVATE */
	//</editor-fold>


}