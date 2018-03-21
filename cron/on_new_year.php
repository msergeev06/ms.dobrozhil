<?php
/**
 * Crontab задание выполняющееся каждый новый год
 *
 * @package Ms\Dobrozhil
 * @author Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright 2018 Mikhail Sergeev
 */

$_SERVER['DOCUMENT_ROOT'] = dirname(__FILE__).'/../../../../';

set_time_limit(0);
//Защита от ошибок при перезагрузке
while (1)
{
	if (!file_exists($_SERVER['DOCUMENT_ROOT'].'/reboot')
		&& !file_exists($_SERVER['DOCUMENT_ROOT'].'/shutdown')
		&& !file_exists($_SERVER['DOCUMENT_ROOT'].'/backup')
		&& !file_exists($_SERVER['DOCUMENT_ROOT'].'/startup')
	)
	{
		break;
	}
}

define('MS_NO_CHECK_AGENTS',true);
include_once (dirname(__FILE__)."/../../../core/prolog_before.php");

\Ms\Core\Lib\Logs::write2Log('Запуск события OnNewYear');

\Ms\Core\Lib\Events::runEvents('ms.dobrozhil','OnNewYear');
