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
	|| file_exists($_SERVER['DOCUMENT_ROOT'].'/startup')
)
{
	die();
}

include_once (dirname(__FILE__)."/../../../core/prolog_before.php");

\Ms\Core\Lib\Events::runEvents('ms.dobrozhil','OnNewMinute');
