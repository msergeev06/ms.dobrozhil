<?php

namespace Ms\Dobrozhil\Lib;

use Ms\Core\Interfaces\IAllErrors;

/**
 * Класс Ms\Dobrozhil\Lib\Errors
 * Коды и описания ошибок системы "Доброжил"
 */
class Errors implements IAllErrors
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
	const ERROR_EXISTS                              = 1012;
	const ERROR_ADD                                 = 1013;
	const ERROR_NOT_EMPTY                           = 1014;
	const ERROR_NOT_SUPPLIED_ACTION                 = 1015;
	const ERROR_SET                                 = 1016;

	public static function getError ($iErrorCode,$arReplace=[])
	{
		return '['.$iErrorCode.'] '.static::getErrorTextByCode($iErrorCode,$arReplace);
	}

	public static function getErrorTextByCode ($iErrorCode,$arReplace=[]): string
	{
		switch ((int)$iErrorCode)
		{
			case self::ERROR_NO_NAME:
				$text = 'Имя не указано';
				break;
			case self::ERROR_WRONG_NAME:
				$text = 'В имени использованы запрещенные символы';
				break;
			case self::ERROR_CHANGE_SYSTEM:
				$text = 'Запрещено изменять системные данные';
				break;
			case self::ERROR_NOT_EXISTS:
				$text = 'Запрашиваемый объект не существует';
				break;
			case self::ERROR_EXISTS:
				$text = 'Запрашиваемый объект существует';
				break;
			case self::ERROR_ADD:
				$text = 'Ошибка при добавлении данных';
				break;
			case self::ERROR_NOT_SUPPLIED_ACTION:
				$text = 'Попытка совершения неподдерживаемого действия';
				break;
			case self::ERROR_SET:
				$text = 'Ошибка установки значения';
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
				break;
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