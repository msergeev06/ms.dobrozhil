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
use Ms\Dobrozhil\Entity\Script;
use Ms\Dobrozhil\Tables\ScriptsCategoriesTable;
use Ms\Dobrozhil\Tables\ScriptsTable;
use Ms\Core\Lib\Loc;

Loc::includeLocFile(__FILE__);

class Scripts
{
	/**
	 * Возвращает основные параметры скрипта с указанным именем из БД
	 *
	 * @param string $sScriptName Имя скрипта
	 *
	 * @return array|bool|string
	 */
	public static function getScriptDb ($sScriptName)
	{
		return ScriptsTable::getOne(
			array (
				'select' => array (
					'NAME',
					'MODULE',
					'CLASS',
					'NOTE',
					'CATEGORY_ID'
				),
				'filter' => array ('NAME'=>$sScriptName)
			)
		);
	}

	/**
	 * Запускает указанный скрипт с переданными параметрами
	 * Использует объект скрипта для работы
	 *
	 * @param string $sScriptName Имя скрипта
	 * @param array $arParams Массив дополнительных параметров скрипта
	 *
	 * @uses Script
	 *
	 * @return mixed|null
	 */
	public static function runScript($sScriptName, $arParams=array())
	{
		$obScript = new Script($sScriptName);
		if (!$obScript->isError())
		{
			$arUpdate = array (
				'LAST_RUN' => new Date(),
				'LAST_PARAMETERS' => $arParams
			);

			$result = $obScript->run($arParams);

			if (!$obScript->isError())
			{
				ScriptsTable::update($sScriptName,$arUpdate);
				return $result;
			}
		}

		return NULL;
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
		$arRes = ScriptsTable::getOne(
			array (
				'select'=>'NAME',
				'filter'=>array (
					"NAME"=>$scriptName
				)
			)
		);

		return (!!$arRes);
	}

	/**
	 * Удаляет скрипт с указанным именем, если он существует
	 *
	 * @param string $scriptName Имя скрипта
	 *
	 * @return bool|\Ms\Core\Entity\Db\DBResult
	 */
	public static function deleteScript ($scriptName)
	{
		if (static::issetScript($scriptName))
		{
			//TODO: Добавить проверку возможности удаления скрипта из категории 1
			return ScriptsTable::delete($scriptName,true);
		}

		return false;
	}

	/**
	 * Создает новый скрипт с указанными параметрами
	 *
	 * @param string $sName       Имя скрипта
	 * @param int    $iCategoryID ID категории скрипта
	 * @param string $sNote       Описание скрипта
	 * @param string $sModule     Модуль редактора кода
	 * @param string $sClass      Класс редактора кода
	 *
	 * @return \Ms\Core\Entity\Db\DBResult|bool
	 */
	public static function addScript ($sName, $iCategoryID=null, $sNote=null, $sModule=null, $sClass=null)
	{
		$arAdd = array();
		if (!static::checkScriptName($sName))
		{
			//Имя скрипта содержит запрещенные символы
			return false;
		}
		elseif (static::issetScript($sName))
		{
			//Скрипт с таким именем уже существует
			return false;
		}
		$arAdd['NAME'] = $sName;
		if (!is_null($iCategoryID))
		{
			if (!static::issetScriptCategoryByID((int)$iCategoryID))
			{
				//Категории ID: #ID# не существует
				return false;
			}
			$arAdd['CATEGORY_ID'] = (int)$iCategoryID;
			if ($arAdd['CATEGORY_ID']==1)
			{
				//Скрипты в категорию "Классы и объекта" может добавлять только система (не ошибка)
				if (!defined('MS_DOBROZHIL_SYSTEM_SET') || !MS_DOBROZHIL_SYSTEM_SET)
				{
					$arAdd['CATEGORY_ID'] = 0;
				}
			}
		}
		if (!is_null($sNote) && strlen($sNote)>0)
		{
			$arAdd['NOTE'] = $sNote;
		}
		if (!is_null($sModule) || !is_null($sClass))
		{
			if (is_null($sModule))
			{
				//Имя модуля редактора кода не задано. При указании имени класса обязательно требуется указание имени модуля
				return false;
			}
			elseif (is_null($sClass))
			{
				//Имя класса редактора кода не задано. При указании имени модуля обязательно требуется указание имени класса
				return false;
			}
			else
			{
				if (!Loader::issetModule($sModule))
				{
					//Модуль "#MODULE_NAME#" не установлен
					return false;
				}
				elseif (!Loader::includeModule($sModule))
				{
					//Ошибка подключения модуля "#MODULE_NAME#"
					return false;
				}
				$arAdd['MODULE'] = $sModule;
				if (!Loader::classExists($sClass))
				{
					//Класс "#CLASS_NAME#" модуля "#MODULE_NAME#" не обнаружен среди автозагружаемых классов.
					return false;
				}
				$arAdd['CLASS'] = $sClass;
			}
		}

		return ScriptsTable::add($arAdd);
	}

	/**
	 * Проверяет имя скрипта на соответствие правилам именования
	 *
	 * @param string $sScriptName Имя скрипта
	 *
	 * @return bool
	 */
	public static function checkScriptName ($sScriptName)
	{
		if (strpos($sScriptName,'.')!==false)
		{
			list($first,$second)=explode('.',$sScriptName);

			return (Classes::checkName($first) && Classes::checkName($second));
		}
		else
		{
			return Classes::checkName($sScriptName);
		}
	}

	/**
	 * Проверяет существование категории скриптов по ID
	 *
	 * @param int $categoryID ID категории
	 *
	 * @return bool
	 */
	public static function issetScriptCategoryByID ($categoryID)
	{
		if ((int)$categoryID <= 0) return false;

		$arRes = ScriptsCategoriesTable::getOne(array(
			'filter' => array ('ID'=>(int)$categoryID)
		));

		return (!!$arRes);
	}

	/**
	 * Проверяет существование категории скриптов по его имени
	 *
	 * @param string $sCategoryName Имя категории
	 *
	 * @return bool
	 */
	public static function issetScriptCategoryByName ($sCategoryName)
	{
		$arRes = ScriptsCategoriesTable::getOne(array (
			'filter' => array ('TITLE'=>$sCategoryName)
		));

		return (!!$arRes);
	}

	/**
	 * Добавляет новую категорию, если ее не существует
	 *
	 * @param string $sCategoryName Имя категории
	 *
	 * @return bool|int
	 */
	public static function addNewCategory ($sCategoryName)
	{
		if (!static::issetScriptCategoryByName($sCategoryName))
		{
			$res = ScriptsCategoriesTable::add(
				array (
					'TITLE' => $sCategoryName
				)
			);
			if ($res->getResult())
			{
				return $res->getInsertId();
			}
		}

		return false;
	}

	/**
	 * Возвращает имя категории скрипта по её ID или FALSE, если не найдена
	 *
	 * @param int $categoryID ID категории
	 *
	 * @return bool|string
	 */
	public static function getCategoryNameByID ($categoryID)
	{
		if ((int)$categoryID>0)
		{
			$arRes = ScriptsCategoriesTable::getOne(array (
				'filter' => array ('ID'=>(int)$categoryID)
			));
			if (isset($arRes['TITLE']))
			{
				return $arRes['TITLE'];
			}
		}

		return false;
	}

	/**
	 * Возвращает ID категории по её имени, либо FALSE, если не найдена
	 *
	 * @param string $sCategoryName Имя категории
	 *
	 * @return bool|int
	 */
	public static function getCategoryIDByName ($sCategoryName)
	{
		$arRes = ScriptsCategoriesTable::getOne(array (
			'filter' => array ('TITLE'=>$sCategoryName)
		));
		if (isset($arRes['ID']))
		{
			return (int)$arRes['ID'];
		}

		return false;
	}

	/**
	 * Удаляет категорию по её ID или имени
	 *
	 * @param int|string $mCategory ID или имя категории
	 *
	 * @return bool
	 */
	public static function deleteCategory ($mCategory)
	{
		if (is_numeric($mCategory) && static::issetScriptCategoryByID((int)$mCategory))
		{
			if ((int)$mCategory==1)
			{
				return false;//Категорию ID=1 удалить нельзя
			}
			$res = ScriptsCategoriesTable::delete((int)$mCategory,true);
		}
		elseif (!is_numeric($mCategory) && static::issetScriptCategoryByName($mCategory))
		{
			$res = ScriptsCategoriesTable::delete(static::getCategoryIDByName($mCategory),true);
		}

		if (isset($res) && $res->getResult())
		{
			return true;
		}

		return false;
	}
}
