<?php
/**
 * Crontab задание выполняющееся каждую минуту
 *
 * @package Ms\Dobrozhil
 * @author Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright 2018 Mikhail Sergeev
 */


$_SERVER['DOCUMENT_ROOT'] = dirname(__FILE__).'/../../../../';

set_time_limit(0);

if (file_exists($_SERVER['DOCUMENT_ROOT'].'/reboot')
	|| file_exists($_SERVER['DOCUMENT_ROOT'].'/shutdown')
	|| file_exists($_SERVER['DOCUMENT_ROOT'].'/backup')
)
{
	die();
}

include_once (dirname(__FILE__)."/../../../core/prolog_before.php");

if (file_exists($_SERVER['DOCUMENT_ROOT'].'/startup'))
{
	\Ms\Dobrozhil\Lib\Cron::initOnStartUp(null);

	if (file_exists($_SERVER['DOCUMENT_ROOT'].'/startup'))
	{
		unlink($_SERVER['DOCUMENT_ROOT'].'/startup');
	}

	\Ms\Core\Lib\Logs::setInfo('Все стартовые действия завершены. Система запущена.');
}

\Ms\Dobrozhil\Lib\Cron::initOnNewMinute();
