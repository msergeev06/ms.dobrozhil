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
use Ms\Dobrozhil\Lib\Classes;
use Ms\Dobrozhil\Lib\Objects;
use Ms\Dobrozhil\Lib\Types;
use Ms\Dobrozhil\Tables;
use Ms\Core\Entity\Db\Query\QueryBase;
use Ms\Core\Entity\Db\SqlHelper;
use Ms\Core\Entity\Type\Date;

class Base
{
	public $_objectName = null;

	public $_className = null;

	public $_classType = null;

	private $_arPropertyValues = array();

	private $_arLastMethodName = null;

	private $_arLastMethodParams = null;

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
	 * @return bool
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
		return $this->runMethod($name,$arguments);
	}

	/**
	 * Возвращает true, если свойство существует и не равно null
	 *
	 * @param $name
	 * @return bool
	 */
	final public function __isset ($name)
	{
		if (isset($this->_arPropertyValues[$name]))
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
		if (isset($this->_arPropertyValues[$name]))
		{
			unset($this->_arPropertyValues[$name]);
		}
	}

	/**
	 * Возвращает структуру объекта при использовании в функции var_dump()
	 *
	 * @return array
	 */
	public function __debugInfo()
	{
		$arReturn  = get_object_vars($this);
		if (isset($arReturn['_arPropertyValues']))
		{
			unset($arReturn['_arPropertyValues']);
		}
		if (isset($arReturn['_arLastMethodName']))
		{
			unset($arReturn['_arLastMethodName']);
		}
		if (isset($arReturn['_arLastMethodParams']))
		{
			unset($arReturn['_arLastMethodParams']);
		}
		$allProp = Objects::getAllProperties($this->_objectName);
		if ($allProp && !empty($allProp))
		{
			$arReturn = array_merge($arReturn,$allProp);
		}

		return $arReturn;
	}

	/**
	 * Возвращает представление объекта в виде строки с сериализованным массивом
	 *
	 * @return string
	 */
	final public function __toString ()
	{
		$strReturn = '['.$this->_className.'.'.$this->_objectName.']';

		return $strReturn;
	}

	/* RUN */

	/**
	 * Выполняет метод класса объекта
	 *
	 * @param string      $methodName Имя метода
	 * @param array      $arParams   Список параметров
	 * @param null|string $className  Имя класса, если не задано - класс объекта
	 *
	 * @return mixed|null
	 */
	final public function runMethod ($methodName, $arParams=array(), $className=null)
	{
		if (is_null($className))
		{
			$this->_className;
		}

		$arMethod = Classes::getClassMethod(
			$className,
			$methodName,
			array(
				'SCRIPT_NAME',
				'CODE',
				'UPDATED'
			)
		);

		if (!is_null($arMethod['SCRIPT_NAME']))
		{
			//Здесь будет выполнятся скрипт
		}
		elseif (!is_null($arMethod['CODE']))
		{
			$parentClass = Classes::getClassParams($className,'PARENT_CLASS');
			if ($parentClass)
			{
				$this->_arLastMethodName[$parentClass] = $methodName;
				$this->_arLastMethodParams[$parentClass] = $arParams;
			}

			//Тут выволняется код
			$result = eval($arMethod['CODE']);

			$arUpdate = array(
				'LAST_PARAMETERS' => $arParams,
				'LAST_RUN' => new Date(),
				'UPDATED' => $arMethod['UPDATED']
			);

			Tables\ClassMethodsTable::update($className.'.'.$methodName,$arUpdate);

			return $result;
		}

		return null;
	}

	/**
	 * Выполняет метод родительского класса объекта, если он существует
	 *
	 * @return mixed|null
	 */
	final public function runParent()
	{
		$parentClass = Classes::getClassParams($this->_className,'PARENT_CLASS');
		if (!$parentClass)
		{
			return null;
		}
		$methodName = $this->_arLastMethodName[$parentClass];
		$arParams = $this->_arLastMethodParams[$parentClass];

		return $this->runMethod($methodName,$arParams,$parentClass);
	}

	/* GETS */

	/**
	 * Возвращает значение указанного свойства
	 *
	 * @param string $name Название свойства объекта
	 *
	 * @return mixed|null
	 */
	final public function getProperty ($name)
	{
		if (isset($this->_arPropertyValues[$name]))
		{
			return $this->_arPropertyValues[$name];
		}

		$propertyValue = Objects::getProperty($this->_objectName,$name);
		if (is_null($propertyValue))
		{
			return null;
		}

		$this->_arPropertyValues[$name] = $propertyValue;

		return $propertyValue;
	}

	/* SETS */

	/**
	 * Устанавливает новое значение свойства объекта. Значение также записывается в базу
	 *
	 * @param string $name Название свойства
	 * @param mixed $value Новое значение свойства
	 *
	 * @return bool
	 */
	final public function setProperty ($name, $value)
	{
		$res = Objects::setProperty($this->_objectName,$name,$value);
		if ($res === true)
		{
			$this->_arPropertyValues[$name] = $value;
		}

		return $res;
	}
}