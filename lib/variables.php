<?php

namespace Ms\Dobrozhil\Lib;

use Ms\Core\Entity\ErrorCollection;
use Ms\Core\Exception\ArgumentOutOfRangeException;
use Ms\Core\Lib\Logs;
use Ms\Dobrozhil\Tables\VariablesTable;

class Variables
{
	/**
	 * @var ErrorCollection
	 */
	private static $errorCollection = null;

	private static $arVariables = array ();

	/**
	 * Проверяет правильность имени переменной
	 * @see Classes::checkName()
	 *
	 * @param string $sName Имя переменной
	 *
	 * @return bool
	 */
	public static function checkName ($sName)
	{
		return Classes::checkName($sName,false);
	}

	/**
	 * Устанавливает переменную
	 *
	 * @param string        $sName  Имя переменной
	 * @param string        $mValue Значение переменной
	 * @param null|string   $sType  Тип переменной
	 *
	 * @return bool|\Ms\Core\Entity\Db\DBResult
	 */
	public static function set ($sName, $mValue='', $sType=null)
	{
		if (!self::checkName($sName))
		{
			//'Имя переменной не указано или использованы запрещенные символы'
			Logs::setError(
				Errors::getErrorTextByCode(Errors::ERROR_NO_CLASS_NAME),
				array (),
				static::$errorCollection,
				Errors::ERROR_NO_CLASS_NAME
			);
			return false;
		}
		$arRes = VariablesTable::getOne(array (
			'select'=>array('NAME','TYPE'),
			'filter' => array ('NAME'=>$sName)
		));
		if ($arRes)
		{
			if (is_null($sType))
			{
				if (strlen($arRes['TYPE'])>0)
				{
					$sType = $arRes['TYPE'];
				}
				else
				{
					$sType = 'S';
				}
			}
			$arUpdate = array ('VALUE'=>Types::prepareValueTo($mValue, $sType));
			if (strtoupper($sType) != $arRes['TYPE'])
			{
				$arUpdate['TYPE'] = strtoupper($sType);
			}

			return VariablesTable::update($sName,$arUpdate);
		}
		else
		{
			$arAdd['NAME'] = $sName;
			if (is_null($sType))
			{
				$sType = 'S';
			}
			$arAdd['TYPE'] = strtoupper($sType);
			if (!is_null($mValue))
			{
				$arAdd['VALUE'] = Types::prepareValueTo($mValue, $sType);
			}
			else
			{
				$arAdd['VALUE'] = '';
			}

			return VariablesTable::add($arAdd);
		}
	}

	/**
	 * Возвращает значение переменной, а также дополнительные указанные поля
	 *
	 * @param string $sName     Имя переменной
	 * @param array  $arSelect  Массив возвращаемых полей (всегда возвращаются TYPE и VALUE)
	 *
	 * @return null|mixed
	 */
	public static function get ($sName, array $arSelect=array ('VALUE', 'TYPE', 'UPDATED'))
	{
		if (!self::checkName($sName))
		{
			//'Имя переменной не указано или использованы запрещенные символы'
			Logs::setError(
				Errors::getErrorTextByCode(Errors::ERROR_NO_CLASS_NAME),
				array (),
				static::$errorCollection,
				Errors::ERROR_NO_CLASS_NAME
			);
			return null;
		}

		if (!in_array('VALUE',$arSelect))
		{
			$arSelect[] = 'VALUE';
		}
		if (!in_array('TYPE',$arSelect))
		{
			$arSelect[] = 'TYPE';
		}

		$arRes = VariablesTable::getOne(array (
			'select' => $arSelect,
			'filter' => array ('NAME'=>$sName)
		));
		if (!$arRes)
		{
			return null;
		}
		$arRes['_VALUE'] = $arRes['VALUE'];
		$arRes['VALUE'] = Types::prepareValueFrom($arRes['VALUE'],$arRes['TYPE']);

		return $arRes;
	}

}