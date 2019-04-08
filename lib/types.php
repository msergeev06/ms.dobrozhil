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
	/*
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
	/**
	 * Базовый тип Строка (string)
	 */
	const BASE_TYPE_STRING = 'S';
	/**
	 * Базовый тип Число (float)
	 */
	const BASE_TYPE_NUMERIC = 'N';
	/**
	 * Базовый тип Флаг (bool)
	 */
	const BASE_TYPE_BOOL = 'B';

	/**
	 * Тип Строка->Дата (Date)
	 */
	const TYPE_S_DATE = 'S:DATE';
	/**
	 * Тип Строка->Дата/Время (Date)
	 */
	const TYPE_S_DATETIME = 'S:DATETIME';
	/**
	 * Тип Строка->Время (Date)
	 */
	const TYPE_S_TIME = 'S:TIME';
	/**
	 * Тип Строка->Цвет (Color)
	 */
	const TYPE_S_COLOR = 'S:COLOR';
	/**
	 * Тип Строка->Координаты (Coordinates)
	 */
	const TYPE_S_COORDINATES = 'S:COORDINATES';

	/**
	 * Тип Число->Целое число (int)
	 */
	const TYPE_N_INT = 'N:INT';
	/**
	 * Тип Число->Файл (File)
	 */
	const TYPE_N_FILE = 'N:FILE';
	/**
	 * Тип Число->Метка времени UNIX (Date)
	 */
	const TYPE_N_TIMESTAMP = 'N:TIMESTAMP';

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
	public static function getHandler ($sType=self::BASE_TYPE_STRING)
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
					case self::BASE_TYPE_NUMERIC:
						/**
						 * @var TypeProcessing $handler
						 */
						$handler = call_user_func('Ms\Dobrozhil\Entity\Types\TypeFloat::getInstance');
						break;
					case self::BASE_TYPE_BOOL:
						/**
						 * @var TypeProcessing $handler
						 */
						$handler = call_user_func('Ms\Dobrozhil\Entity\Types\TypeBool::getInstance');
						break;
					default: //self::BASE_TYPE_STRING
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
	public static function prepareValueFrom ($mValue, $sType=self::BASE_TYPE_STRING)
	{
		if (is_null($sType))
		{
			$sType = static::BASE_TYPE_STRING;
		}
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
	public static function prepareValueTo ($mValue, $sType=self::BASE_TYPE_STRING)
	{
		if (!is_null($mValue))
		{
			$handler = static::getHandler($sType);

			return $handler->processingValueFromDB($mValue);
		}
		else
		{
			return null;
		}
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
			case self::BASE_TYPE_BOOL:
				$handler = call_user_func('Ms\Dobrozhil\Entity\Types\TypeBool::getInstance');
				break;
			case self::TYPE_S_DATE:
				$handler = call_user_func('Ms\Dobrozhil\Entity\Types\TypeDate::getInstance');
				break;
			case self::TYPE_S_DATETIME:
				$handler = call_user_func('Ms\Dobrozhil\Entity\Types\TypeDatetime::getInstance');
				break;
			case self::TYPE_S_TIME:
				$handler = call_user_func('Ms\Dobrozhil\Entity\Types\TypeTime::getInstance');
				break;
			case self::TYPE_S_COLOR:
				$handler = call_user_func('Ms\Dobrozhil\Entity\Types\TypeColor::getInstance');
				break;
			case self::TYPE_S_COORDINATES:
				$handler = call_user_func('Ms\Dobrozhil\Entity\Types\TypeCoordinates::getInstance');
				break;
			case self::BASE_TYPE_NUMERIC:
				$handler = call_user_func('Ms\Dobrozhil\Entity\Types\TypeFloat::getInstance');
				break;
			case self::TYPE_N_INT:
				$handler = call_user_func('Ms\Dobrozhil\Entity\Types\TypeInt::getInstance');
				break;
			case self::TYPE_N_FILE:
				$handler = call_user_func('Ms\Dobrozhil\Entity\Types\TypeFile::getInstance');
				break;
			case self::TYPE_N_TIMESTAMP:
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
 