<?php

namespace Ms\Dobrozhil\Entity\Code;

use Ms\Dobrozhil\Interfaces\CodeEditor;
use Ms\Core\Lib\Loc;
use Ms\Dobrozhil\Lib\Scripts;
use Ms\Dobrozhil\Tables\TextEditorCodeTable;

Loc::includeLocFile(__FILE__);

class TextEditor implements CodeEditor
{
	/**
	 * Возвращает код скрипта
	 *
	 * @param string $scriptName Имя скрипта
	 *
	 * @return string|null
	 */
	public function getCode ($scriptName)
	{
		if (Scripts::checkScriptName($scriptName))
		{
			$arRes = TextEditorCodeTable::getOne(array (
				'filter' => array ('NAME'=>$scriptName)
			));

			if (isset($arRes['CODE']) && !is_null($arRes['CODE']))
			{
				return $arRes['CODE'];
			}
		}

		return null;
	}

	/**
	 * Сохраняет код скрипта
	 *
	 * @param string $scriptName Имя скрипта
	 * @param string $code Код
	 *
	 * @return bool
	 */
	public function saveCode ($scriptName, $code = '')
	{
		return true;
	}


/*
	public static function getName ()
	{

	}

	public static function runEditor ($code = NULL)
	{

	}
*/

}