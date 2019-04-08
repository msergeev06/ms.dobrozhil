<?php

namespace Ms\Dobrozhil\Lib;

use Ms\Core\Entity\ErrorCollection;
use Ms\Core\Lib\Logs;

class Entities
{
	//	const NAME_REGULAR = '/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/';
	const NAME_REGULAR = '/^[a-zA-Z_][a-zA-Z0-9_]*$/';

	/**
	 * @var ErrorCollection
	 */
	private static $errorCollection = null;

	public static function checkName ($sEntityName, $bAddErrors=TRUE)
	{
		if (
			!isset($sEntityName)
			|| is_null($sEntityName)
			|| strlen($sEntityName)<=0
		) {
			if ($bAddErrors)
			{
				//'Не указано название сущности'
				Logs::setError(
					Errors::getErrorTextByCode(Errors::ERROR_NO_ENTITY_NAME),
					array (),
					static::$errorCollection,
					Errors::ERROR_NO_ENTITY_NAME
				);
			}
			return FALSE;
		}

		$bOk = (!!preg_match(self::NAME_REGULAR, $sEntityName));

		if (!$bOk)
		{
			if ($bAddErrors)
			{
				//'Неверное имя сущности'
				Logs::setError(
					Errors::getErrorTextByCode(Errors::ERROR_WRONG_ENTITY_NAME),
					array (),
					static::$errorCollection,
					Errors::ERROR_WRONG_ENTITY_NAME
				);
			}
			return FALSE;
		}

		return true;
	}

	public static function checkObjectName ($sObjectName, $bAddErrors=TRUE)
	{
		if (
			!isset($sObjectName)
			|| is_null($sObjectName)
			|| strlen($sObjectName) <= 0
		) {
			if ($bAddErrors)
			{
				//Не указано название объекта сущности
				Logs::setError(
					Errors::getErrorTextByCode(Errors::ERROR_NO_ENTITY_OBJECT_NAME),
					array (),
					static::$errorCollection,
					Errors::ERROR_NO_ENTITY_OBJECT_NAME
				);
			}
			return FALSE;
		}

		$bOk = Objects::checkName($sObjectName,FALSE);

		if (!$bOk)
		{
			if ($bAddErrors)
			{
				//'Неверное имя объекта сущности'
				Logs::setError(
					Errors::getErrorTextByCode(Errors::ERROR_WRONG_ENTITY_OBJECT_NAME),
					array (),
					static::$errorCollection,
					Errors::ERROR_WRONG_ENTITY_OBJECT_NAME
				);
			}
			return FALSE;
		}

		return TRUE;
	}

	public static function checkPropertyName ($sPropertyName, $bAddErrors=TRUE)
	{
		if (
			!isset($sPropertyName)
			|| is_null($sPropertyName)
			|| strlen($sPropertyName)<=0
		) {
			if ($bAddErrors)
			{
				//'Не указано название свойства сущности'
				Logs::setError(
					Errors::getErrorTextByCode(Errors::ERROR_NO_ENTITY_PROPERTY_NAME),
					array (),
					static::$errorCollection,
					Errors::ERROR_NO_ENTITY_PROPERTY_NAME
				);
			}
			return FALSE;
		}

		$bOk = Classes::checkPropertyName($sPropertyName, FALSE);

		if (!$bOk)
		{
			if ($bAddErrors)
			{
				//'Неверное имя свойства сущности'
				Logs::setError(
					Errors::getErrorTextByCode(Errors::ERROR_WRONG_ENTITY_PROPERTY_NAME),
					array (),
					static::$errorCollection,
					Errors::ERROR_WRONG_ENTITY_PROPERTY_NAME
				);
			}
			return FALSE;
		}

		return TRUE;
	}

	public static function checkMethodName ($sMethodName, $bAddErrors=TRUE)
	{
		if (
			!isset($sMethodName)
			|| is_null($sMethodName)
			|| strlen($sMethodName)<=0
		) {
			if ($bAddErrors)
			{
				//'Не указано название метода сущности'
				Logs::setError(
					Errors::getErrorTextByCode(Errors::ERROR_NO_ENTITY_METHOD_NAME),
					array (),
					static::$errorCollection,
					Errors::ERROR_NO_ENTITY_METHOD_NAME
				);
			}
			return FALSE;
		}

		$bOk = Classes::checkMethodName($sMethodName, FALSE);

		if (!$bOk)
		{
			if ($bAddErrors)
			{
				//'Неверное имя метода сущности'
				Logs::setError(
					Errors::getErrorTextByCode(Errors::ERROR_WRONG_ENTITY_METHOD_NAME),
					array (),
					static::$errorCollection,
					Errors::ERROR_WRONG_ENTITY_METHOD_NAME
				);
			}
			return FALSE;
		}

		return TRUE;
	}
}