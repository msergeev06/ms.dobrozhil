<?php
/**
 * Базовый класс объектов
 *
 * @package Ms\Dobrozhil
 * @subpackage Entity\Objects
 * @author Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright 2018 Mikhail Sergeev
 */

namespace Ms\Dobrozhil\Entity\Objects;

use Ms\Core\Lib\Tools;
use Ms\Dobrozhil\Tables;
use Ms\Core\Entity\Db\Query\QueryBase;
use Ms\Core\Entity\Db\SqlHelper;
use Ms\Core\Entity\Type\Date;

class Base
{
	public $_objectName = null;

	public $_className = null;

	public $_classType = null;

	private $arPropertyValues = array();

	public function __construct ($objectName)
	{
		$arRes = Tables\ObjectsTable::getOne(
			array(
				'select' => array(
					'NAME',
					'CLASS_NAME',
					'CLASS_NAME.TYPE' => 'CLASS_TYPE'
				),
				'filter' => array('NAME'=>$objectName)
			)
		);
		if ($arRes)
		{
			$this->_objectName = $arRes['NAME'];
			$this->_classType = $arRes['CLASS_TYPE'];
			$this->_className = $arRes['CLASS_NAME'];
		}
	}

	/* Magic */

	/**
	 * Возвращает текущее значение свойства объекта
	 *
	 * @param string $name Название свойства объекта
	 *
	 * @return null|mixed
	 */
	final public function __get ($name)
	{
		return $this->getProperty($name);
	}

	/**
	 * Сохраняет значение свойства объекта
	 *
	 * @param string $name Название свойства объекта
	 * @param mixed $value Значение свойства объекта
	 *
	 * @return \MSergeev\Core\Entity\Db\DBResult
	 */
	final public function __set ($name, $value)
	{
		return $this->setProperty ($name, $value);
	}

	/**
	 * Вызывает метод класса объекта и передает в него параметры, возвращая результат работы метода
	 *
	 * @param string $name Имя метода
	 * @param array $arguments Массив аргументов, переданных в метод
	 *
	 * @return mixed
	 */
	final public function __call ($name, $arguments)
	{
		// TODO: Implement __call() method.

		return '';
	}

	/**
	 * Возвращает true, если свойство существует и не равно null
	 *
	 * @param $name
	 * @return bool
	 */
	final public function __isset ($name)
	{
		if (isset($this->arPropertyValues[$name]))
		{
			return true;
		}

		$arRes = Tables\ObjectsPropertyValuesTable::getOne(
			array(
				'select' => array('VALUE'),
				'filter' => array('NAME'=>$this->_objectName.'.'.$name)
			)
		);

		return ($arRes && !is_null($arRes['VALUE']));
	}

	/**
	 * Удаляет значение свойства из сохраненных значений
	 *
	 * @param $name
	 */
	final public function __unset ($name)
	{
		if (isset($this->arPropertyValues[$name]))
		{
			unset($this->arPropertyValues[$name]);
		}
	}

	/**
	 * Возвращает структуру объекта при использовании в функции var_dump()
	 *
	 * @return array
	 */
	final public function __debugInfo()
	{
		$arReturn  = get_object_vars($this);
		if (isset($arReturn['arPropertyValues']))
		{
			unset($arReturn['arPropertyValues']);
		}
		$allProp = $this->getAllProperties();
		$arReturn = array_merge($arReturn,$allProp);

		return $arReturn;
	}

	/**
	 * Возвращает представление объекта в виде строки с сериализованным массивом
	 *
	 * @return string
	 */
	final public function __toString ()
	{
		$strReturn = '['.$this->_objectName.']';

		return $strReturn;
	}

	/* GETS */

	/**
	 * Возвращает требуемые свойства из таблицы объектов
	 *
	 * @param $propertyName
	 * @return array|bool|string
	 */
	final public function getObjectParams ($propertyName)
	{
		$arRes = Tables\ObjectsTable::getOne(
			array(
				'select' => $propertyName,
				'filter' => array('NAME'=>$this->_objectName)
			)
		);

		return $arRes;
	}

	/**
	 * Возвращает требуемые параметры из таблицы классов
	 *
	 * @param $propertyName
	 * @return array|bool|string
	 */
	final public function getClassParams ($propertyName)
	{
		$arRes = Tables\ClassesTable::getOne(
			array(
				'select' => $propertyName,
				'filter' => array('NAME'=>$this->_className)
			)
		);

		return $arRes;
	}

	/**
	 * Возвращает список свойств текущего класса
	 *
	 * @param null|string имя класса, по умолчанию класс объекта
	 * @param null|array|string список получаемых полей, по умолчанию ('NAME','LINKED')
	 *
	 * @return array|bool
	 */
	final public function getClassPropertiesList ($className = null, $arFields = null)
	{
		if (is_null($className))
		{
			$className = $this->_className;
		}
		if (is_null($arFields))
		{
			$arFields = array('NAME','LINKED');
		}

		$arRes = Tables\ClassPropertiesTable::getList(
			array(
				'select' => $arFields,
				'filter' => array('CLASS_NAME'=>$className)
			)
		);

		return $arRes;
	}

	/**
	 * Возвращает список со значениями всех свойств объекта, включая наследуемые свойства
	 *
	 * @return array
	 */
	final public function getAllProperties ()
	{
		$arProperties = array();
		$arClassParent = $this->getClassParams('PARENT_LIST');
		if ($arClassParent)
		{
			$arClassParent = $arClassParent['PARENT_LIST'];
		}
		if ($arClassParent && is_array($arClassParent))
		{
			$arClassParent[] = $this->_className;
		}
		else
		{
			$arClassParent = array($this->_className);
		}
		while (count($arClassParent)>0)
		{
			$className = array_pop($arClassParent);
			$arRes = $this->getClassPropertiesList($className,'*');
			if ($arRes)
			{
				foreach ($arRes as $ar_res)
				{
					$arRes2 = Tables\ObjectsPropertyValuesTable::getOne(
						array(
							'select' => array('VALUE'),
							'filter' => array('NAME'=>$this->_objectName.'.'.$ar_res['PROPERTY_NAME'])
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

		return $arProperties;
	}

	/**
	 * Возвращает требуемые свойства для таблицы свойства класса 
	 * 
	 * @param        $propertyName
	 * @param string $fields
	 * @return array|bool|string
	 */
	final public function getClassPropertiesParams ($propertyName, $fields='*')
	{
		return Tables\ClassPropertiesTable::getOne(
			array(
				'select' => $fields,
				'filter' => array('NAME'=>$this->_className.'.'.$propertyName)
			)
		);
	}

	/**
	 * Возвращает значение указанного свойства
	 *
	 * @param string $name Название свойства объекта
	 *
	 * @return mixed|null
	 */
	final public function getProperty ($name)
	{
		if (isset($this->arPropertyValues[$name]))
		{
			return $this->arPropertyValues[$name];
		}

		$arRes = Tables\ObjectsPropertyValuesTable::getOne(
			array(
				'select' => array('VALUE'),
				'filter' => array('NAME'=>$this->_objectName.'.'.$name)
			)
		);

		if (!$arRes || is_null($arRes['VALUE']))
		{
			return null;
		}
		else
		{
			//Получаем тип значения свойства
			if ($valueType = $this->getClassPropertiesParams($name,'TYPE'))
			{
				$bOk = false;
				if (isset($valueType['TYPE']) && !is_null($valueType['TYPE']))
				{
					$valueType = strtolower($valueType['TYPE']);
					$bOk = true;
				}
				elseif (isset($valueType['TYPE']))
				{
					$valueType = 'string';
					$bOk = true;
				}
				//Если тип свойства установлен
				if ($bOk)
				{
					switch ($valueType)
					{
						case 'int':
							$arRes['VALUE'] = Tools::validateIntVal($arRes['VALUE']);
							break;
						case 'float':
							$arRes['VALUE'] = Tools::validateFloatVal($arRes['VALUE']);
							break;
						case 'string':
							$arRes['VALUE'] = Tools::validateStringVal($arRes['VALUE']);
							break;
						case 'bool':
							$arRes['VALUE'] = Tools::validateBoolVal($arRes['VALUE']);
							break;
						case 'date':
							$arRes['VALUE'] = Tools::validateDateVal($arRes['VALUE']);
							break;
						default:
							$arRes['VALUE'] = $this->validatePropertyValue($arRes['VALUE'],$valueType);
							break;
					}
				}
			}
			$this->arPropertyValues[$name] = $arRes['VALUE'];
			return $arRes['VALUE'];
		}
	}

	/**
	 * Возвращает параметры значения указанного свойства объекта
	 *
	 * @param string $name Название свойства
	 *
	 * @return array|bool|string
	 */
	final public function getPropertyInfo ($name)
	{
		return Tables\ObjectsPropertyValuesTable::getOne(
			array(
				'filter' => array('NAME'=>$this->_objectName.'.'.$name)
			)
		);
	}

	/* SETS */

	/**
	 * Устанавливает новое значение свойства объекта. Значение также записывается в базу
	 *
	 * @param string $name Название свойства
	 * @param mixed $value Новое значение свойства
	 *
	 * @return \MSergeev\Core\Entity\Db\DBResult
	 */
	final public function setProperty ($name, $value)
	{
		//Сначала получаем текущее значение свойства
		$arNowValue = $this->getPropertyInfo($name);

		//Получаем параметры свойства
		$arParams = $this->getClassPropertiesParams($name,array('SAVE_IDENTICAL_VALUES','HISTORY'));

		//Если такого свойства не существует, автоматически создаем новое свойство объекта и записываем в него null
		if (!$arNowValue)
		{
			Tables\ObjectsPropertyValuesTable::add(array('NAME' => $this->_objectName.'.'.$name));
			$arNowValue = $this->getPropertyInfo($name);
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
		if ($arNowValue['VALUE'] == $value)
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
			$helper = new SqlHelper(Tables\ObjectsPropertyValuesHistory::getTableName());
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
				Tables\ObjectsPropertyValuesHistory::add(
					array(
						'NAME'=>$this->_objectName.'.'.$name,
						'VALUE' => $arNowValue['VALUE'],
						'DATETIME' => $arNowValue['UPDATED']
					)
				);
			}

			$arUpdate['VALUE'] = $value;
			$this->arPropertyValues[$name] = $value;
		}

		//Обновляем либо значение, либо только время обновления свойства
		return Tables\ObjectsPropertyValuesTable::update($this->_objectName.'.'.$name,$arUpdate);
	}

	private function validatePropertyValue($value, $valueType)
	{
		//Здесь будет подключаться функция валидации значения свойства указанного типа
		return $value;
	}

}