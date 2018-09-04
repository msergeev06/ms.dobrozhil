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

use Ms\Core\Entity\Type\Date;
use Ms\Core\Lib\Events;
use Ms\Core\Lib\Loc;
use Ms\Core\Lib\Logs;
use Ms\Dobrozhil\Entity\Script;
use Ms\Dobrozhil\Lib\Classes;
use Ms\Dobrozhil\Lib\Objects;
use Ms\Dobrozhil\Lib\Scripts;
use Ms\Dobrozhil\Lib\Types;
use Ms\Dobrozhil\Tables;

Loc::includeLocFile(__FILE__);

class Base
{
	//<editor-fold defaultstate="collapsed" desc="Init params">

	//<editor-fold defaultstate="collapsed" desc="public params">
	/**
	 * Имя объекта
	 * @var string|null
	 */
	public $_objectName = null;

	/**
	 * Имя класса объекта
	 * @var string|null
	 */
	public $_className = null;

	/**
	 * Тип класса объекта
	 * "U" - пользовательский, "P" - программный, "S" - системный
	 * @var string|null
	 */
	public $_classType = null;
	//</editor-fold>

	//<editor-fold defaultstate="collapsed" desc="private params">
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
	//</editor-fold>

	//</editor-fold>

	//<editor-fold desc="Base methods">
	/**
	 * Base constructor.
	 * Создает программный объект заданного виртуального объекта
	 * Может быть переопределен
	 *
	 * @param string $objectName Имя объекта
	 */
	public function __construct ($objectName)
	{
		if (Objects::checkName($objectName))
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
				$this->_className = $arRes['CLASS_NAME'];
				$this->_classType = $arRes['CLASS_TYPE'];
			}
		}
	}

	/**
	 * Проверяет успешное создание объекта
	 * Может быть переопределен
	 *
	 * @return bool
	 */
	public function isObject ()
	{
		if (is_null($this->_objectName))
		{
			return false;
		}
		else
		{
			return true;
		}
	}

	//<editor-fold defaultstate="collapsed" desc="Protected methods">
	/**
	 * Вызывается перед получением значения свойства объекта
	 * Может быть переопределен
	 *
	 * @param string $propertyName Имя свойства объекта
	 * @param bool   $bFull        Флаг необходимости получения параметров значения свойства
	 */
	protected function onGet ($propertyName,$bFull=false){}

	/**
	 * Вызывается перед изменением значения свойства объекта
	 * Может быть переопределен
	 *
	 * @param string $propertyName Имя свойства объекта
	 * @param mixed  $value        Значение свойства объекта
	 */
	protected function onSet ($propertyName, $value){}

	/**
	 * Вызывается перед вызовом метода класса объекта
	 *
	 * @param string $name     Имя метода
	 * @param array  $arParams Массив параметров
	 */
	protected function onCall ($name, $arParams=array()) {}
	//</editor-fold>

	//</editor-fold>

	//<editor-fold defaultstate="collapsed" desc="Magic methods">
	/**
	 * Возвращает текущее значение свойства объекта
	 *
	 * @param string $propertyName Название свойства объекта
	 *
	 * @return null|mixed
	 */
	final public function __get ($propertyName)
	{
		if (Classes::checkPropertyName($propertyName))
		{
			return $this->getProperty($propertyName);
		}
		else
		{
			return null;
		}
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
	 * @param string $name Имя свойства
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
	 * @param string $name Имя свойства
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
	 * Возвращает представление объекта в виде строки вида "[имя_класса.имя_объекта]"
	 *
	 * @return string
	 */
	final public function __toString ()
	{
		$strReturn = '['.$this->_className.'.'.$this->_objectName.']';

		return $strReturn;
	}
	//</editor-fold>

	//<editor-fold defaultstate="collapsed" desc="Run methods">
	/**
	 * Выполняет метод класса объекта
	 *
	 * @param string      $methodName Имя метода
	 * @param array       $arParams   Список параметров
	 * @param null|string $className  Имя класса, если не задано - класс объекта
	 *
	 * @return mixed|null
	 * @throws
	 */
	final public function runMethod ($methodName, $arParams=array(), $className=null)
	{
		if (is_null($className))
		{
			$this->_className;
		}

		$this->onCall ($methodName, $arParams);

		$arScriptName = $this->getScriptNameArray($className,$methodName);
		if (!$arScriptName)
		{
			return null;
		}
		$obScript = new Script($arScriptName['SCRIPT']);

		$code = $obScript->getCode();

		if (!is_null($code) && $code != '')
		{
			/*
			 * Тут выполняется код
			 * В коде доступны переменные:
			 * $this - ссылка на объект
			 * $arParams - массив переданных параметров
			 * $className - имя класса объекта
			 */
			$this->_arLastMethodName[$arScriptName['CLASS']] = $arScriptName['METHOD'];
			$this->_arLastMethodParams[$arScriptName['CLASS']] = $arParams;

			try
			{
				$result = eval($code);
			}
			catch (\ParseError $p)
			{
				Logs::setError(
					//'Ошибка синтаксиса кода метода #METHOD_NAME# класса #CLASS_NAME#'
					Loc::getModuleMessage(
						'ms.dobrozhil',
						'error_method_syntax',
						array(
							'METHOD_NAME'=>$arScriptName['METHOD'],
							'CLASS_NAME'=>$arScriptName['CLASS']
						)
					),
					array ('ERROR_CODE'=>'METHOD_CODE_SYNTAX')
				);

				return null;
			}

			return $result;
		}

		return null;
	}

	/**
	 * Выполняет метод родительского класса объекта, если он существует
	 *
	 * @param array $arParams Массив измененных параметров для родительского класса (не обязательно)
	 *
	 * @return mixed|null
	 */
	final public function runParent($arParams=null)
	{
		$parentClass = Classes::getClassParams($this->_className,'PARENT_CLASS');
		if (!$parentClass)
		{
			return null;
		}
		$methodName = $this->_arLastMethodName[$this->_className];
		if (is_null($arParams) || !is_array($arParams))
		{
			$arParams = $this->_arLastMethodParams[$this->_className];
		}

		return $this->runMethod($methodName,$arParams,$parentClass);
	}
	//</editor-fold>

	//<editor-fold defaultstate="collapsed" desc="Gets methods">
	/**
	 * Возвращает значение указанного свойства
	 *
	 * @param string $sPropertyName Название свойства объекта
	 * @param bool   $bFull         Флаг, указывающий что необходимо кроме значения получить параметры
	 *
	 * @return mixed|null
	 */
	final public function getProperty ($sPropertyName, $bFull=false)
	{
		if (!Classes::checkPropertyName($sPropertyName))
		{
			return null;
		}

		//Если значение данного свойства уже было получено, возвращаем его
		if (isset($this->_arPropertyValues[$sPropertyName]) && !$bFull)
		{
			return $this->_arPropertyValues[$sPropertyName];
		}

		//Запускаем при наличае метод onBeforeGetProperty_[propertyName]
		$bNext = null;
		$bNext = $this->runMethod(
			'onBeforeGetProperty_'.$sPropertyName,
			array('FULL'=>$bFull)
		);
		if (!is_null($bNext) && !$bNext)
		{
			return null;
		}

		//Вызываем событие OnBeforeGetObjectProperty
		$bStop = !Events::runEvents(
			'ms.dobrozhil',
			'OnBeforeGetObjectProperty',
			array(
				'NAME'=>$sPropertyName,
				'FULL'=>$bFull
			)
		);
		if ($bStop)
		{
			return null;
		}

		//Выполняем наследуемый метод $this->onGet(propertyName)
		$this->onGet($sPropertyName,$bFull);

		//Получаем значение свойства
//		$propertyValue = Objects::getProperty($this->_objectName,$sPropertyName);
		$propertyValue = $this->getPropertyValue($sPropertyName,$bFull);
		if (is_null($propertyValue))
		{
			return null;
		}
		if ($bFull)
		{
			$this->_arPropertyValues[$sPropertyName] = $propertyValue['VALUE'];
		}
		else
		{
			$this->_arPropertyValues[$sPropertyName] = $propertyValue;
		}

		//Вызываем событие OnAfterGetObjectProperty
		$bStop = !Events::runEvents(
			'ms.dobrozhil',
			'OnAfterGetObjectProperty',
			array(
				'NAME'=>$sPropertyName,
				'VALUE'=>$propertyValue,
				'FULL'=>$bFull
			)
		);
		if ($bStop)
		{
			return null;
		}

		//Запускаем при наличае метод onAfterGetProperty_[propertyName]
		$bNext = null;
		$bNext = $this->runMethod(
			'onAfterGetProperty_'.$sPropertyName,
			array('VALUE'=>$propertyValue)
		);
		if (!is_null($bNext) && !$bNext)
		{
			return null;
		}

		return $propertyValue;
	}

	/**
	 * Возвращает имя скрипта, содержащего код метода класса объекта
	 *
	 * @param string $sClassName Имя класса объекта
	 * @param string $sMethodName Имя метода класса объекта
	 *
	 * @return array|bool Массив (CLASS => имя класса, METHOD => имя метода, SCRIPT => имя скрипта)
	 */
	final public function  getScriptNameArray ($sClassName, $sMethodName)
	{
		if (Scripts::issetScript($sClassName.'.'.$sMethodName))
		{
			return array(
				'CLASS'     => $sClassName,
				'METHOD'    => $sMethodName,
				'SCRIPT'    => $sClassName.'.'.$sMethodName
			);
		}
		else
		{
			$parentClass = Classes::getClassParams($sClassName,'PARENT_CLASS');
			if ($parentClass !== false)
			{
				return $this->getScriptNameArray($parentClass,$sMethodName);
			}
			else
			{
				return false;
			}
		}
	}
	//</editor-fold>

	//<editor-fold defaultstate="collapsed" desc="Sets methods">
	/**
	 * Устанавливает новое значение свойства объекта. Значение также записывается в базу
	 *
	 * @param string $sPropertyName Название свойства
	 * @param mixed  $propertyValue Новое значение свойства
	 *
	 * @return bool
	 */
	final public function setProperty ($sPropertyName, $propertyValue)
	{
		if (!Classes::checkPropertyName($sPropertyName))
		{
			return null;
		}

		//Запускаем при наличае метод onBeforeSetProperty_[propertyName]
		$bNext = null;
		$bNext = $this->runMethod(
			'onBeforeSetProperty_'.$sPropertyName,
			array ('VALUE'=>$propertyValue)
		);
		if (!is_null($bNext) && !$bNext)
		{
			return null;
		}

		//Вызываем событие OnBeforeSetObjectProperty
		$bStop = !Events::runEvents(
			'ms.dobrozhil',
			'OnBeforeSetObjectProperty',
			array(
				'NAME'=>$sPropertyName,
				'VALUE'=>$propertyValue
			)
		);
		if ($bStop)
		{
			return null;
		}

		//Выполняем наследуемый метод $this->onSet(sPropertyName,propertyValue)
		$this->onSet($sPropertyName,$propertyValue);

		//Устанавливаем новое значение свойства
		$res = $this->setPropertyValue($sPropertyName,$propertyValue);
		if ($res === true)
		{
			$this->_arPropertyValues[$sPropertyName] = $propertyValue;
		}

		//Вызываем событие OnAfterSetObjectProperty
		$bStop = !Events::runEvents(
			'ms.dobrozhil',
			'OnAfterSetObjectProperty',
			array('NAME'=>$sPropertyName,'VALUE'=>$propertyValue)
		);
		if ($bStop)
		{
			return null;
		}

		//Запускаем при наличае метод onAfterSetProperty_[propertyName]
		$bNext = null;
		$bNext = $this->runMethod(
			'onAfterSetProperty_'.$sPropertyName,
			array('VALUE'=>$propertyValue)
		);
		if (!is_null($bNext) && !$bNext)
		{
			return null;
		}

		return $res;
	}
	//</editor-fold>

	//<editor-fold defaultstate="collapsed" desc="Private methods">
	/**
	 * Возвращает значение свойства объекта
	 *
	 * @param      $sPropertyName
	 * @param bool $bFull
	 * @uses Objects::getPropertyValueInfo()
	 *
	 * @return array|bool|null|string
	 */
	private function getPropertyValue ($sPropertyName, $bFull=false)
	{
		if (!Classes::checkPropertyName($sPropertyName))
		{
			//'Имя свойства содержит запрещенные символы'
			Objects::addError(
				Loc::getModuleMessage(
					'ms.dobrozhil',
					'error_property_wrong_symbols'
				),
				'PROPERTY_WRONG_SYMBOLS'
			);
			return false;
		}

		$arRes = Objects::getPropertyValueInfo($this->_objectName,$sPropertyName);

		if ($bFull && isset($arRes['VALUE']))
		{
			return $arRes;
		}
		elseif (!$bFull && isset($arRes['VALUE']))
		{
			return $arRes['VALUE'];
		}
		else
		{
			return null;
		}
	}

	/**
	 * Устанавливает значение свойства объекта
	 *
	 * @param $sPropertyName
	 * @param $propertyValue
	 *
	 * @return bool
	 */
	private function setPropertyValue ($sPropertyName, $propertyValue)
	{
		if (!Classes::checkPropertyName($sPropertyName))
		{
			//'Имя свойства объекта содержит запрещенные символы'
			Objects::addError(
				Loc::getModuleMessage(
					'ms.dobrozhil',
					'error_property_wrong_symbols'
				),
				'PROPERTY_WRONG_SYMBOL'
			);
			return false;
		}

		/*
		 * Сначала получаем текущее значение свойства, либо FALSE:
		 * NAME
		 * TYPE
		 * VALUE
		 * UPDATED
		 */
		$arNowValue = Objects::getPropertyValueInfo($this->_objectName,$sPropertyName);
		$newValue = $propertyValue;

		/*
		 * Получаем параметры свойства:
		 * SAVE_IDENTICAL_VALUES (bool) - Сохранять ли одинаковые значения
		 * HISTORY (int) - Время хранения истории значений в днях (0 - не хранить историю)
		 * TYPE (string) - Тип свойства (к чему будут приводится значения)
		 */
		$arPropertyParams = Classes::getClassPropertiesParams(
			$this->_className,
			$sPropertyName,
			array('SAVE_IDENTICAL_VALUES','HISTORY','TYPE')
		);

		//Получаем тип данных для свойства класса
		$valueType = strtolower($arPropertyParams['TYPE']);

		//Преобразуем новое значение в значение для БД
		$propertyValue = Types::prepareValueTo($propertyValue,$valueType);

		//Если такого свойства не существует, но оно есть в классе объекта:
		//автоматически создаем новое свойство объекта и записываем в него null
		if (!$arNowValue && Classes::issetClassProperty($this->_className,$sPropertyName))
		{
			Tables\ObjectsPropertyValuesTable::add(array('NAME' => $this->_objectName.'.'.$sPropertyName));
			$arNowValue = Objects::getPropertyValueInfo($this->_objectName,$sPropertyName);
		}
		elseif (!$arNowValue && !Classes::issetClassProperty($this->_className,$sPropertyName))
		{
			//Создаем новое свойство класса
			$arPropertyParams = array(
				'SAVE_IDENTICAL_VALUES' => false,
				'HISTORY' => 0,
				'TYPE' => 'S',
				'NAME' => $this->_className.'.'.$sPropertyName,
				'PROPERTY_NAME' => $sPropertyName,
				'CLASS_NAME' => $this->_className
			);
			Tables\ClassPropertiesTable::add($arPropertyParams);

			//Записываем в свойство null
			Tables\ObjectsPropertyValuesTable::add(array('NAME' => $this->_objectName.'.'.$sPropertyName));
			$arNowValue = Objects::getPropertyValueInfo($this->_objectName,$sPropertyName);
		}

/*		if ($arNowValue && !$arPropertyParams)
		{
			//Создаем новое свойство класса
			$arPropertyParams = array(
				'SAVE_IDENTICAL_VALUES' => false,
				'HISTORY' => 0,
				'TYPE' => 'S',
				'NAME' => $this->_className.'.'.$sPropertyName,
				'PROPERTY_NAME' => $sPropertyName,
				'CLASS_NAME' => $this->_className
			);
			Tables\ClassPropertiesTable::add($arPropertyParams);
		}*/

		$bSave = false;

		//Если значения равны
		if ($arNowValue['VALUE'] == $propertyValue)
		{
			//Если требуется сохранять равные значения
			if ($arPropertyParams['SAVE_IDENTICAL_VALUES']===true)
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
		Objects::clearOldHistory(
			$this->_objectName.'.'.$sPropertyName,
			$arPropertyParams['HISTORY']
		);

		//Если нужно записывать значение
		if ($bSave)
		{
			//Если ведется история, пишем старое значение в историю
			if ((int)$arPropertyParams['HISTORY']>0)
			{
				Tables\ObjectsPropertyValuesHistoryTable::add(
					array(
						'NAME'=>$this->_objectName.'.'.$sPropertyName,
						'VALUE' => $arNowValue['VALUE'],
						'DATETIME' => $arNowValue['UPDATED']
					)
				);
			}

			$arUpdate['VALUE'] = $propertyValue;
		}

		//Обновляем либо значение, либо только время обновления свойства
		$res = Tables\ObjectsPropertyValuesTable::update($this->_objectName.'.'.$sPropertyName,$arUpdate);
		if ($res->getResult())
		{
			if ($bSave)
			{
				//При изменении свойства запускаем метод класса объекта
				$this->runMethod(
					'onChange_'.$sPropertyName,
					array ('OLD_VALUE'=>$arNowValue['VALUE'],'VALUE'=>$newValue)
				);

				//А также запускаем событие изменения свойства
				Events::runEvents(
					'ms.dobrozhil',
					'OnChangeObjectProperty_'.$sPropertyName,
					array ('OLD_VALUE'=>$arNowValue['VALUE'],'VALUE'=>$newValue)
				);

				return true;
			}
		}

		return false;
	}
	//</editor-fold>
}