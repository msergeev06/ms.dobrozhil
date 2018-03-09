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

	private $arPropertyValues = array();

	private $arLastMethodName = null;

	private $arLastMethodParams = null;

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
	 * Работает с PHP 5.6.0
	 *
	 * @return array
	 */
	public function __debugInfo()
	{
		$arReturn  = get_object_vars($this);
		if (isset($arReturn['arPropertyValues']))
		{
			unset($arReturn['arPropertyValues']);
		}
		$allProp = Objects::getAllProperties($this->_objectName);
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

	/* RUN */

	final public function runMethod ($methodName, $arParams)
	{
		///this[ ]*-\>[ ]*runParent[ ]*\(([ ]*)\);/
		//$res = $this ->runParent ();
		$arMethod = Classes::getClassMethod(
			$this->_className,
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
			$parentClass = Classes::getClassParams($this->_className,'PARENT_CLASS');
			if ($parentClass)
			{
				$this->arLastMethodName[$parentClass] = $methodName;
				$this->arLastMethodParams[$parentClass] = $arParams;
			}

			//Тут выволняется код
			$result = eval($arMethod['CODE']);

			$arUpdate = array(
				'LAST_PARAMETERS' => $arParams,
				'LAST_RUN' => new Date(),
				'UPDATED' => $arMethod['UPDATED']
			);

			Tables\ClassMethodsTable::update($this->_className.'.'.$methodName,$arUpdate);

			return $result;
		}

		return null;
	}

	final public function runParent()
	{

		return null;
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
		if (isset($this->arPropertyValues[$name]))
		{
			return $this->arPropertyValues[$name];
		}

		$propertyValue = Objects::getProperty($this->_objectName,$name);
		if (is_null($propertyValue))
		{
			return null;
		}

		$this->arPropertyValues[$name] = $propertyValue;

		return $propertyValue;
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
		$res = Objects::setProperty($this->_objectName,$name,$value);
		if ($res === true)
		{
			$this->arPropertyValues[$name] = $value;
		}

		return $res;
	}
}