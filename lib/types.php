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
use Ms\Core\Entity\ErrorCollection;
use Ms\Core\Entity\Type\Date;
use Ms\Core\Lib\Events;
use Ms\Core\Lib\Loader;
use Ms\Core\Lib\Logs;
use Ms\Core\Lib\Tools;
use Ms\Dobrozhil\Interfaces\TypeProcessing;

class Types
{
	/**
	 * @var ErrorCollection
	 */
	protected static $errorCollection = null;

	private static $arTypeHandlers = array ();

	/**
	 * Возвращает полученный сторонний обработчик свойства класса заданного типа, либо типа Строка
	 *
	 * @param string $sType
	 *
	 * @return TypeProcessing
	 */
	public static function getHandler ($sType='S')
	{
		$handler = null;
		$sType = strtoupper($sType);
		if (!isset(static::$arTypeHandlers[$sType]))
		{
			Events::runEvents(
				'ms.dobrozhil',
				'OnGetClassPropertyTypeHandler',
				array ($sType,&$handler)
			);
			if (is_null($handler)||!($handler instanceof TypeProcessing))
			{
				Logs::setWarning(
					'Обработчик типа "#PROPERTY_TYPE#" свойства класса не найден',
					array (
						'PROPERTY_TYPE'=>$sType,
						'ERROR_CODE'=>'NO_CLASS_PROPERTY_TYPE_HANDLER'
					),
					self::$errorCollection
				);
				if (strpos($sType,':')!==false)
				{
					$arType = explode(':',$sType);
				}
				else
				{
					$arType = array ($sType);
				}
				switch ($arType[0])
				{
					case 'N':
						/**
						 * @var TypeProcessing $handler
						 */
						$handler = call_user_func('Ms\Dobrozhil\Entity\Types\TypeFloat::getInstance');
						break;
					case 'B':
						/**
						 * @var TypeProcessing $handler
						 */
						$handler = call_user_func('Ms\Dobrozhil\Entity\Types\TypeBool::getInstance');
						break;
					default: //S
						/**
						 * @var TypeProcessing $handler
						 */
						$handler = call_user_func('Ms\Dobrozhil\Entity\Types\TypeString::getInstance');
						break;
				}
			}
			static::$arTypeHandlers[$sType] = $handler;
		}

		return static::$arTypeHandlers[$sType];
	}

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
		$handler = static::getHandler($sType);

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
		$handler = static::getHandler($sType);

		return $handler->processingValueFromDB($mValue);
	}

	public static function getTitle ($sType)
	{
		/**
		 * @var TypeProcessing $handler
		 */
		$handler = static::getHandler($sType);

		return $handler->getTitle();
	}

	/* Events */

	/**
	 * Обработчик события получения обработчика заданного типа свойства класса
	 *
	 * @param string $sType Тип свойства класса
	 * @param TypeProcessing $handler
	 *
	 * @return void
	 */
	public static function OnGetClassPropertyTypeHandler ($sType, &$handler)
	{
		$sType = strtolower($sType);

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

		switch ($sType)
		{
			case 'B':
				$handler = call_user_func('Ms\Dobrozhil\Entity\Types\TypeBool::getInstance');
				break;
			case 'S:DATE':
				$handler = call_user_func('Ms\Dobrozhil\Entity\Types\TypeDate::getInstance');
				break;
			case 'S:DATETIME':
				$handler = call_user_func('Ms\Dobrozhil\Entity\Types\TypeDatetime::getInstance');
				break;
			case 'S:TIME':
				$handler = call_user_func('Ms\Dobrozhil\Entity\Types\TypeTime::getInstance');
				break;
			case 'S:COLOR':
				$handler = call_user_func('Ms\Dobrozhil\Entity\Types\TypeColor::getInstance');
				break;
			case 'S:COORDINATES':
				$handler = call_user_func('Ms\Dobrozhil\Entity\Types\TypeCoordinates::getInstance');
				break;
			case 'N':
				$handler = call_user_func('Ms\Dobrozhil\Entity\Types\TypeFloat::getInstance');
				break;
			case 'N:INT':
				$handler = call_user_func('Ms\Dobrozhil\Entity\Types\TypeInt::getInstance');
				break;
			case 'N:FILE':
				$handler = call_user_func('Ms\Dobrozhil\Entity\Types\TypeFile::getInstance');
				break;
			case 'N:TIMESTAMP':
				$handler = call_user_func('Ms\Dobrozhil\Entity\Types\TypeTimestamp::getInstance');
				break;
			default:
				$handler = call_user_func('Ms\Dobrozhil\Entity\Types\TypeString::getInstance');
				break;
		}
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

	protected static function addError($sMessage, $sCode=null)
	{
		if (is_null(static::$errorCollection))
		{
			static::$errorCollection = new ErrorCollection();
		}
		static::$errorCollection->setError($sMessage,$sCode);
	}
}
 