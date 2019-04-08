<?php

namespace Ms\Dobrozhil\Lib;

use Ms\Core\Interfaces\AllErrors;

class Errors implements AllErrors
{
	const ERROR_ERROR                               = 1000;
	const ERROR_NO_NAME                             = 1001;
	const ERROR_WRONG_NAME                          = 1002;
	const ERROR_CHANGE_SYSTEM                       = 1003;
	const ERROR_NOT_EXISTS                          = 1004;
	const ERROR_ACCESS_VIEW                         = 1005;
	const ERROR_ACCESS_WRITE                        = 1006;
	const ERROR_ACCESS_CREATE                       = 1007;
	const ERROR_ACCESS_EDIT                         = 1008;
	const ERROR_ACCESS_DELETE                       = 1009;
	const ERROR_ACCESS_RUN                          = 1010;
	const ERROR_ACCESS_ALL                          = 1011;

	//<editor-fold defaultstate="collapse" desc="deprecated">
	/**
	 * @deprecated
	 */
	const ERROR_NO_CLASS_NAME                       = 101;
	/**
	 * @deprecated
	 */
	const ERROR_CLASS_NAME_WRONG_SYMBOLS            = 102;
	/**
	 * @deprecated
	 */
	const ERROR_CLASS_NO_PROPERTY_NAME              = 103;
	/**
	 * @deprecated
	 */
	const ERROR_CLASS_PROPERTY_NAME_WRONG_SYMBOLS   = 104;
	/**
	 * @deprecated
	 */
	const ERROR_CLASS_NO_METHOD_NAME                = 105;
	/**
	 * @deprecated
	 */
	const ERROR_CLASS_METHOD_NAME_WRONG_SYMBOLS     = 106;
	/**
	 * @deprecated
	 */
	const ERROR_CLASS_NOT_EXIST                     = 107;
	/**
	 * @deprecated
	 */
	const ERROR_CLASS_PROPERTY_NOT_EXIST            = 108;
	/**
	 * @deprecated
	 */
	const ERROR_CLASS_METHOD_NOT_EXIST              = 109;
	/**
	 * @deprecated
	 */
	const ERROR_CLASS_EXIST                         = 110;
	/**
	 * @deprecated
	 */
	const ERROR_CLASS_PROPERTY_EXIST                = 111;
	/**
	 * @deprecated
	 */
	const ERROR_CLASS_ADD                           = 112;
	/**
	 * @deprecated
	 */
	const ERROR_CLASS_METHOD_CODE_SYNTAX            = 113;

	/**
	 * @deprecated
	 */
	const ERROR_VARIABLES_WRONG_NAME                = 150;

	/**
	 * @deprecated
	 */
	const ERROR_NO_ENTITY_NAME                      = 200;
	/**
	 * @deprecated
	 */
	const ERROR_WRONG_ENTITY_NAME                   = 201;
	/**
	 * @deprecated
	 */
	const ERROR_NO_ENTITY_PROPERTY_NAME             = 202;
	/**
	 * @deprecated
	 */
	const ERROR_WRONG_ENTITY_PROPERTY_NAME          = 203;
	/**
	 * @deprecated
	 */
	const ERROR_NO_ENTITY_METHOD_NAME               = 204;
	/**
	 * @deprecated
	 */
	const ERROR_WRONG_ENTITY_METHOD_NAME            = 205;
	/**
	 * @deprecated
	 */
	const ERROR_NO_ENTITY_OBJECT_NAME               = 206;
	/**
	 * @deprecated
	 */
	const ERROR_WRONG_ENTITY_OBJECT_NAME            = 207;
	//</editor-fold>


	public static function getError ($iErrorCode,$arReplace=array())
	{
		return '['.$iErrorCode.'] '.static::getErrorTextByCode($iErrorCode,$arReplace);
	}

	public static function getErrorTextByCode ($iErrorCode,$arReplace=array())
	{
		switch ((int)$iErrorCode)
		{
			case self::ERROR_NO_CLASS_NAME:
				$text = 'Не указано название класса';
				break;
			case self::ERROR_CLASS_NAME_WRONG_SYMBOLS:
				$text = 'Имя класса "#CLASS_NAME#" содержит запрещенные символы';
				break;
			case self::ERROR_CLASS_NO_PROPERTY_NAME:
				$text = 'Не указано название свойства класса';
				break;
			case self::ERROR_CLASS_PROPERTY_NAME_WRONG_SYMBOLS:
				$text = 'Имя свойства "#PROPERTY_NAME#" содержит запрещенные символы';
				break;
			case self::ERROR_CLASS_NO_METHOD_NAME:
				$text = 'Не указано название метода класса';
				break;
			case self::ERROR_CLASS_METHOD_NAME_WRONG_SYMBOLS:
				$text = 'Имя метода "#METHOD_NAME#" содержит запрещенные символы';
				break;
			case self::ERROR_CLASS_NOT_EXIST:
				$text = 'Класс с именем #CLASS_NAME# не существует';
				break;
			case self::ERROR_CLASS_PROPERTY_NOT_EXIST:
				$text = 'Свойства #PROPERTY_NAME# нет у класса #CLASS_NAME# и у его предков';
				break;
			case self::ERROR_CLASS_METHOD_NOT_EXIST:
				$text = 'Метода #METHOD_NAME# нет у класса #CLASS_NAME# и у его предков';
				break;
			case self::ERROR_CLASS_EXIST:
				$text = 'Класс с именем "#CLASS_NAME#" уже существует';
				break;
			case self::ERROR_CLASS_PROPERTY_EXIST:
				$text = 'Свойство с именем "#PROPERTY_NAME#" уже существует в классе "#CLASS_NAME#"';
				break;
			case self::ERROR_CLASS_ADD:
				$text = 'Ошибка добавления нового класса "#CLASS_NAME#"';
				break;
			case self::ERROR_CLASS_METHOD_CODE_SYNTAX:
				$text = 'Ошибка синтаксиса кода метода #METHOD_NAME# класса #CLASS_NAME#';
				break;
			case self::ERROR_VARIABLES_WRONG_NAME:
				$text = 'Имя переменной не указано или использованы запрещенные символы';
				break;

			case self::ERROR_NO_ENTITY_NAME:
				$text = 'Имя сущности не указано';
				break;
			case self::ERROR_WRONG_ENTITY_NAME:
				$text = 'Неверное имя сущности';
				break;
			case self::ERROR_NO_ENTITY_PROPERTY_NAME:
				$text = 'Не указано название свойства сущности';
				break;
			case self::ERROR_WRONG_ENTITY_PROPERTY_NAME:
				$text = 'Неверное имя свойства сущности';
				break;
			case self::ERROR_NO_ENTITY_METHOD_NAME:
				$text = 'Не указано название метода сущности';
				break;
			case self::ERROR_WRONG_ENTITY_METHOD_NAME:
				$text = 'Неверное имя метода сущности';
				break;
			case self::ERROR_NO_ENTITY_OBJECT_NAME:
				$text = 'Не указано название объекта сущности';
				break;
			case self::ERROR_WRONG_ENTITY_OBJECT_NAME:
				$text = 'Неверное имя объекта сущности';
				break;

			case self::ERROR_ACCESS_VIEW:
				$text = 'У вас нет прав на просмотр: #ERROR_VIEW#';
				break;
			case self::ERROR_ACCESS_CREATE:
				$text = 'У вас нет прав на создание: #ERROR_CREATE#';
				break;
			case self::ERROR_ACCESS_EDIT:
				$text = 'У вас нет прав на редактирование: #ERROR_EDIT#';
				break;
			case self::ERROR_ACCESS_DELETE:
				$text = 'У вас нет прав на удаление: #ERROR_DELETE#';
				break;
			case self::ERROR_ACCESS_RUN:
				$text = 'У вас нет прав на запуск: #ERROR_RUN#';
				break;
			case self::ERROR_ACCESS_ALL:
				$text = 'У вас нет полного доступа: #ERROR_ALL#';
				break;
			case self::ERROR_ACCESS_WRITE:
				$text = 'У вас нет прав на запись: #ERROR_EDIT#';
				break;

			default://ERROR_ERROR
				$text = 'Неизвестная ошибка';
		}

		if (!empty($arReplace))
		{
			foreach ($arReplace as $code=>$sReplace)
			{
				$text = str_replace('#'.strtoupper($code).'#',$sReplace,$text);
			}
		}

		return $text;
	}
}