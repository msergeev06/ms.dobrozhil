<?php
/**
 * Обработка типов свойств объектов
 *
 * @package Ms\Dobrozhil
 * @subpackage Lib
 * @author Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright 2018 Mikhail Sergeev
 */

namespace Ms\Dobrozhil\Lib;

use Ms\Core\Entity\Application;
use Ms\Core\Entity\Type\Date;
use Ms\Core\Lib\Events;
use Ms\Core\Lib\Loader;
use Ms\Core\Lib\Tools;
use Ms\Dobrozhil\Interfaces\TypeProcessing;

class Types
{
	/**
	 * Обрабатывает значение свойства заданного типа после получения из БД
	 *
	 * @param mixed  $mValue Значение свойства
	 * @param string $sType  Тип свойства
	 *
	 * @return mixed
	 */
	public static function prepareValueFrom ($mValue, $sType='S')
	{
		$handler = null;
		$sType = strtoupper($sType);
		//Вызываем событие, которое должно вернуть обработчик указанного типа данных
		$arEvents = Events::getModuleEvents(
			'ms.dobrozhil',
			'OnPreparePropertyValue'
		);
		if (!empty($arEvents))
		{
			foreach ($arEvents as $sort=>$ar_events)
			{
				if (!empty($ar_events))
				{
					foreach ($ar_events as $hash=>$event)
					{
						$handler = Events::executeModuleEvent(
							$event,
							array ('TYPE'=>$sType)
						);
						//Если обработчик был найден, останавливаем обработку остальных событий
						if ($handler instanceof TypeProcessing)
						{
							break 2;//Выходим из обоих циклов
						}
					}
				}
			}
		}
		//Если обработчик не был найден, считаем что значение это строка
		if (!($handler instanceof TypeProcessing))
		{
			$handler = call_user_func('Ms\Dobrozhil\Entity\Types\TypeString::getInstance');
		}

		return $handler->processingValueFromDB($mValue);
	}

	/**
	 * Обрабатывает значение свойства заданного типа перед сохранением в БД
	 *
	 * @param mixed  $mValue Значение свойства
	 * @param string $sType  Тип свойства
	 *
	 * @return mixed
	 */
	public static function prepareValueTo ($mValue, $sType='S')
	{
		$handler = null;
		$sType = strtoupper($sType);
		//Вызываем событие, которое должно вернуть обработчик указанного типа данных
		$arEvents = Events::getModuleEvents(
			'ms.dobrozhil',
			'OnPreparePropertyValue',
			array ('TYPE'=>$sType)
		);
		if (!empty($arEvents))
		{
			foreach ($arEvents as $sort=>$ar_events)
			{
				if (!empty($ar_events))
				{
					foreach ($ar_events as $hash=>$event)
					{
						$handler = Events::executeModuleEvent(
							$event,
							array ('TYPE'=>$sType)
						);
						//Если обработчик был найден, останавливаем обработку остальных событий
						if ($handler instanceof TypeProcessing)
						{
							break 2;//Выходим из обоих циклов
						}
					}
				}
			}
		}
		//Если обработчик не был найден, считаем что значение это строка
		if (!($handler instanceof TypeProcessing))
		{
			$handler = call_user_func('Ms\Dobrozhil\Entity\Types\TypeString::getInstance');
		}

		return $handler->processingValueFromDB($mValue);

	}

	/**
	 * Определяет и возвращает обработчик значения свойства, либо false
	 * @deprecated
	 * @see Types::OnPreparePropertyValueHandler()
	 *
	 * @param string $sType  Тип свойства
	 *
	 * @return string|bool
	 */
	protected static function getHandler ($sType='S')
	{
		$sType = strtolower($sType);
		$fileName = Application::getInstance()->getSettings()->getModulesRoot()
			.'/ms.dobrozhil/include/types/type_'.$sType.'.php';
		if (!file_exists($fileName))
		{
			return false;
		}
		else
		{
			$typeHandler = include($fileName);
			if (!$typeHandler)
			{
				return false;
			}
			else
			{
				return call_user_func($typeHandler);
			}
		}
	}

	/* Events */

	/**
	 * @param $arParams
	 *
	 * @return TypeProcessing
	 */
	public static function OnPreparePropertyValueHandler ($arParams)
	{
		if (isset($arParams['TYPE']))
		{
			$arParams['TYPE'] = strtoupper($arParams['TYPE']);
		}
		else
		{
			$arParams['TYPE'] = 'S';
		}

		/*
		 * Обрабатываемые типы:
		 * S - string - Строка
		 *  S:DATE - Date - Дата
		 *  S:DATETIME - Date - Дата/Время
		 *  S:TIME - Date - Время
		 *  S:COLOR - Color - HEX-цвет
		 *  S:COORDINATES - Coordinates - Координаты
		 * N - float - Число
		 *  N:INT - int - Целое число
		 *  N:FILE - File - Файл
		 *  N:TIMESTAMP - Date - Метка времени Unix
		 * B - bool - Флаг
		 */

		switch ($arParams['TYPE'])
		{
			case 'S':
				return call_user_func('Ms\Dobrozhil\Entity\Types\TypeString::getInstance');
			case 'B':
				return call_user_func('Ms\Dobrozhil\Entity\Types\TypeBool::getInstance');
			case 'S:DATE':
				return call_user_func('Ms\Dobrozhil\Entity\Types\TypeDate::getInstance');
			case 'S:DATETIME':
				return call_user_func('Ms\Dobrozhil\Entity\Types\TypeDatetime::getInstance');
			case 'S:TIME':
				return call_user_func('Ms\Dobrozhil\Entity\Types\TypeTime::getInstance');
			case 'S:COLOR':
				return call_user_func('Ms\Dobrozhil\Entity\Types\TypeColor::getInstance');
			case 'S:COORDINATES':
				return call_user_func('Ms\Dobrozhil\Entity\Types\TypeCoordinates::getInstance');
			case 'N':
				return call_user_func('Ms\Dobrozhil\Entity\Types\TypeFloat::getInstance');
			case 'N:INT':
				return call_user_func('Ms\Dobrozhil\Entity\Types\TypeInt::getInstance');
			case 'N:FILE':
				return call_user_func('Ms\Dobrozhil\Entity\Types\TypeFile::getInstance');
			case 'N:TIMESTAMP':
				return call_user_func('Ms\Dobrozhil\Entity\Types\TypeTimestamp::getInstance');
			default:
				if (strpos($arParams['TYPE'],':')!==false)
				{
					$arType = explode(':',$arParams['TYPE']);
				}
				else
				{
					$arType = array ($arParams['TYPE']);
				}

				if ($arType[0]=='N')
				{
					return call_user_func('Ms\Dobrozhil\Entity\Types\TypeFloat::getInstance');
				}
				elseif ($arType[0]=='B')
				{
					return call_user_func('Ms\Dobrozhil\Entity\Types\TypeBool::getInstance');
				}
				else //=='s'
				{
					return call_user_func('Ms\Dobrozhil\Entity\Types\TypeString::getInstance');
				}
		}

	}
}
 