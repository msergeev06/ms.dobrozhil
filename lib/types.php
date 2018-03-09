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
use Ms\Core\Lib\Loader;
use Ms\Core\Lib\Tools;

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
	public static function prepareValueFrom ($mValue, $sType='string')
	{
		if (strtolower($sType) == 'string')
		{
			return call_user_func('Ms\Dobrozhil\Lib\Types::handlerStringFrom',$mValue);
		}
		if ($handler = static::getHandler('from',$sType))
		{
			return call_user_func($handler,$mValue);
		}
		else
		{
			return call_user_func('Ms\Dobrozhil\Lib\Types::handlerStringFrom',$mValue);
		}
	}

	/**
	 * Обрабатывает значение свойства заданного типа перед сохранением в БД
	 *
	 * @param mixed  $mValue Значение свойства
	 * @param string $sType  Тип свойства
	 *
	 * @return mixed
	 */
	public static function prepareValueTo ($mValue, $sType='string')
	{
		if (strtolower($sType) == 'string')
		{
			return call_user_func('Ms\Dobrozhil\Lib\Types::handlerStringTo',$mValue);
		}
		if ($handler = static::getHandler('to',$sType))
		{
			return call_user_func($handler,$mValue);
		}
		else
		{
			return call_user_func('Ms\Dobrozhil\Lib\Types::handlerStringTo',$mValue);
		}
	}

	/**
	 * Определяет и возвращает обработчик значения свойства, либо false
	 *
	 * @param string $fromTo Откуда/куда значение свойства (из БД/в БД) ('from' или 'to')
	 * @param string $sType  Тип свойства
	 *
	 * @return string|bool
	 */
	protected static function getHandler ($fromTo, $sType='string')
	{
		$sType = strtolower($sType);
		$fromTo = strtolower($fromTo);
		if ($fromTo != 'from' && $fromTo != 'to')
		{
			return false;
		}
		$fileName = Application::getInstance()->getSettings()->getModulesRoot()
			.'/ms.dobrozhil/include/types/type_'.$sType.'.php';
		if (!file_exists($fileName))
		{
			return false;
		}
		else
		{
			$arType = include($fileName);
			if (isset($arType['module']) && Loader::issetModule($arType['module']))
			{
				if (!Loader::includeModule($arType['module']))
				{
					return false;
				}
			}
			elseif (isset($arType['module']))
			{
				return false;
			}
			if (!isset($arType[$fromTo]))
			{
				return false;
			}
			else
			{
				if (!is_callable($arType[$fromTo]))
				{
					return false;
				}
				else
				{
					return $arType[$fromTo];
				}
			}
		}
	}

	/* HANDLERS */

	/**
	 * Преобразует значение свойства из БД в тип string
	 *
	 * @param mixed $value Исходное значение свойства
	 *
	 * @return string
	 */
	public static function handlerStringFrom ($value)
	{
		return (string)$value;
	}

	/**
	 * Преобразует значение типа string в тип string, для сохранения в БД
	 *
	 * @param mixed $value Исходное значение свойства
	 *
	 * @return string
	 */
	public static function handlerStringTo ($value)
	{
		return (string)$value;
	}

	/**
	 * Преобразует значение свойства из БД в тип int
	 *
	 * @param mixed $value Исходное значение свойства
	 *
	 * @return int
	 */
	public static function handlerIntFrom ($value)
	{
		return Tools::validateIntVal($value);
	}

	/**
	 * Преобразует значение типа int в тип string, для сохранения в БД
	 *
	 * @param mixed $value Исходное значение свойства
	 *
	 * @return string
	 */
	public static function handlerIntTo ($value)
	{
		return Tools::validateIntVal($value);
	}

	/**
	 * Преобразует значение свойства из БД в тип float
	 *
	 * @param mixed $value Исходное значение свойства
	 *
	 * @return float
	 */
	public static function handlerFloatFrom ($value)
	{
		return Tools::validateFloatVal($value);
	}

	/**
	 * Преобразует значение типа float в тип string, для сохранения в БД
	 *
	 * @param mixed $value Исходное значение свойства
	 *
	 * @return string
	 */
	public static function handlerFloatTo ($value)
	{
		return Tools::validateFloatVal($value);
	}

	/**
	 * Преобразует значение свойства из БД в тип bool
	 *
	 * @param mixed $value Исходное значение свойства
	 *
	 * @return bool
	 */
	public static function handlerBoolFrom ($value)
	{
		return Tools::validateBoolVal($value);
	}

	/**
	 * Преобразует значение типа bool в тип string, для сохранения в БД
	 *
	 * @param mixed $value Исходное значение свойства
	 *
	 * @return string
	 */
	public static function handlerBoolTo ($value)
	{
		return ($value)?'Y':'N';
	}

	/**
	 * Преобразует значение свойства из БД в тип date
	 *
	 * @param mixed $value Исходное значение свойства
	 *
	 * @return Date
	 */
	public static function handlerDateFrom ($value)
	{
		return new Date($value);
	}

	/**
	 * Преобразует значение типа date в тип string, для сохранения в БД
	 *
	 * @param Date|string $value
	 *
	 * @return string
	 */
	public static function handlerDateTo ($value)
	{
		if ($value instanceof Date)
		{
			return $value->getDateDB();
		}
		else
		{
			return (string)$value;
		}
	}

	/**
	 * Преобразует значение свойства из БД в тип datetime
	 *
	 * @param mixed $value Исходное значение свойства
	 *
	 * @return Date
	 */
	public static function handlerDateTimeFrom ($value)
	{
		return new Date($value,'db_datetime');
	}

	/**
	 * Преобразует значение типа datetime в тип string, для сохранения в БД
	 *
	 * @param Date|string $value
	 *
	 * @return string;
	 */
	public static function handlerDateTimeTo ($value)
	{
		if ($value instanceof Date)
		{
			return $value->getDateTimeDB();
		}
		else
		{
			return (string)$value;
		}
	}

	/**
	 * Преобразует значение свойства из БД в тип time
	 *
	 * @param mixed $value Исходное значение свойства
	 *
	 * @return Date
	 */
	public static function handlerTimeFrom ($value)
	{
		return new Date($value,'site_time');
	}

	/**
	 * Преобразует значение типа time в тип string, для сохранения в БД
	 *
	 * @param Date|string $value
	 *
	 * @return string
	 */
	public static function handlerTimeTo ($value)
	{
		if ($value instanceof Date)
		{
			return $value->getTimeSite();
		}
		else
		{
			return (string)$value;
		}
	}

	/**
	 * Преобразует значение свойства из БД в тип timestamp
	 *
	 * @param mixed $value Исходное значение свойства
	 *
	 * @return Date
	 */
	public static function handlerTimestampFrom ($value)
	{
		return new Date($value,'time');
	}

	/**
	 * Преобразует значение типа timestamp в тип string, для сохранения в БД
	 *
	 * @param Date|int|string $value
	 *
	 * @return string
	 */
	public static function handlerTimestampTo ($value)
	{
		if ($value instanceof Date)
		{
			return $value->getTimestamp();
		}
		else
		{
			return (string)(int)$value;
		}
	}
}
 