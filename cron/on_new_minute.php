<?php
/**
 * Crontab задание выполняющееся каждую минуту
 *
 * @package Ms\Dobrozhil
 * @author Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright 2018 Mikhail Sergeev
 */

include ('crontab.php');

set_time_limit(0);

if (file_exists($_SERVER['DOCUMENT_ROOT'].'/reboot')
	|| file_exists($_SERVER['DOCUMENT_ROOT'].'/shutdown')
	|| file_exists($_SERVER['DOCUMENT_ROOT'].'/backup')
)
{
	die();
}

define('NO_HTTP_AUTH',true);
define('RUN_CRONTAB_JOB',true);
define('NO_CHECK_AGENTS',true);

include_once (dirname(__FILE__)."/../../../core/prolog_before.php");

use Ms\Dobrozhil\Events\CronController;

if (file_exists($_SERVER['DOCUMENT_ROOT'].'/startup'))
{
    CronController::getInstance()->initOnStartUp(null);

	if (file_exists($_SERVER['DOCUMENT_ROOT'].'/startup'))
	{
		unlink($_SERVER['DOCUMENT_ROOT'].'/startup');
	}

	(new \Ms\Core\Entity\Errors\FileLogger('ms.dobrozhil','debug'))
        ->addMessage('Все стартовые действия завершены. Система запущена.')
    ;
}

CronController::getInstance()->initOnNewMinute();
