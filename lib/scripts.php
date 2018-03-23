<?php
/**
 * Класс для работы со скриптами
 *
 * @package Ms\Dobrozhil
 * @subpackage Lib
 * @author Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright 2018 Mikhail Sergeev
 */

namespace Ms\Dobrozhil\Lib;

use Ms\Core\Entity\Type\Date;
use Ms\Core\Lib\Loader;
use Ms\Core\Lib\Logs;
use Ms\Dobrozhil\Interfaces\CodeEditor;
use Ms\Dobrozhil\Tables\ScriptsTable;
use Ms\Core\Entity\ErrorCollection;

class Scripts
{
	/**
	 * @var null|ErrorCollection
	 */
	private static $errorCollection = null;

	public static function runScript($name, $arParams=array())
	{
		$arScript = ScriptsTable::getOne(
			array (
				'select' => array (
					'NAME',
					'MODULE',
					'CLASS',
					'CODE',
					'UPDATED'
				),
				'filter' => array ('NAME'=>$name)
			)
		);
		if (!$arScript)
		{
			Logs::setWarning('Скприт '.$name.' не найден',array ('ERROR_CODE'=>'SCRIPT_NOT_EXISTS'),static::$errorCollection);
			return NULL;
		}
		if ($arScript['MODULE']!='ms.dobrozhil')
		{
			if (!Loader::issetModule($arScript['MODULE']) || !Loader::includeModule($arScript['MODULE']))
			{
				Logs::setError(
					'Ошибка исполнения скрипта '.$name.'. Требуемый модуль '.$arScript['MODULE'].' не установлен, либо возникла ошибка при подключении',
					array ('ERROR_CODE'=>'ERROR_MODULE'),
					static::$errorCollection
				);
				return NULL;
			}
			elseif (!Loader::classExists($arScript['CLASS']))
			{
				Logs::setError(
					'Ошибка исполнения скрипта '.$name.'. Класс ('.$arScript['CLASS'].') модуля "'.$arScript['MODULE'].'" не найден',
					array ('ERROR_CODE'=>'CLASS_NOT_EXISTS'),
					static::$errorCollection
				);
				return NULL;
			}
			elseif (!($arScript['CLASS'] instanceof CodeEditor))
			{
				Logs::setError(
					'Ошибка исполнения скрипта '.$name.'. Описанный класс ('.$arScript['CLASS'].') модуля "'.$arScript['MODULE'].'" не реализует интерфейс редактора кода',
					array (),
					static::$errorCollection
				);
				return NULL;
			}
			else
			{
				$arScript['CODE'] = call_user_func($arScript['CLASS'].'::getCode',$name);
				if ($arScript['CODE']===false)
				{
					Logs::setError(
						'Ошибка исполнения скрипта '.$name.'. Код не был получен из стороннего редактора',
						array (),
						static::$errorCollection
					);
					return NULL;
				}
			}
		}
		$arUpdate = array (
			'UPDATED' => $arScript['UPDATED'],
			'LAST_RUN' => new Date(),
			'LAST_PARAMETERS' => $arParams
		);
		ScriptsTable::update($arScript['NAME'],$arUpdate);
		try
		{
			$result = eval($arScript['CODE']);
			if ($result === false)
			{
				Logs::setError(
					'Ошибка выполнения скрипта '.$name.'. Произошла ошибка при исполнении кода',
					array (),
					static::$errorCollection
				);
				return NULL;
			}
			return $result;
		}
		catch (\Throwable $e)
		{
			Logs::setError(
				'Ошибка исполнения кода скрипта '.$name,
				array ('EXCEPTION'=>$e),
				static::$errorCollection
			);
			return NULL;
		}
	}

	/**
	 * Проверяет существование скрипта с указанным именем
	 *
	 * @param string $scriptName Имя скрипта
	 *
	 * @return bool
	 */
	public static function issetScript($scriptName)
	{
		$arRes = ScriptsTable::getOne(array ('select'=>'NAME','filter'=>array ("NAME"=>$scriptName)));

		return (!!$arRes);
	}

	/**
	 * Удаляет скрипт с указанным именем
	 *
	 * @param string $scriptName Имя скрипта
	 *
	 * @return \Ms\Core\Entity\Db\DBResult
	 */
	public static function deleteScript ($scriptName)
	{
		return ScriptsTable::delete($scriptName,false);
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

	/**
	 * Добавляет новую ошибку в коллекцию
	 *
	 * @param string $sMessage Сообщение об ошибке
	 * @param string $sCode Код ошибки
	 */
	private static function addError($sMessage, $sCode=null)
	{
		if (is_null(static::$errorCollection))
		{
			static::$errorCollection = new ErrorCollection();
		}
		static::$errorCollection->setError($sMessage,$sCode);
	}

}
