<?php
/**
 * Crontab задание выполняющееся каждый час
 *
 * Инициирует события в этом порядке:
 * OnNewYear
 * OnNewMonth
 * OnNewWeek
 * OnNewDay
 * OnNewHour
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
	sleep(5);
}

define('MS_NO_CHECK_AGENTS',true);
include_once (dirname(__FILE__)."/../../../core/prolog_before.php");

$now = new \Ms\Core\Entity\Type\Date();

//Если начался новый год
if ($now->format('d.m.H') == '01.01.00')
{
	\Ms\Dobrozhil\Lib\Cron::initOnNewYear();
}
//Если начался новый месяц
if ($now->format('d.H') == '01.00')
{
	\Ms\Dobrozhil\Lib\Cron::initOnNewMonth();
}
//Если началась новая неделя
if ((int)$now->format('w') == 1 && $now->format('H') == '00')
{
	\Ms\Dobrozhil\Lib\Cron::initOnNewWeek();
}
//Если начался новый день
if ($now->format('H') == '00')
{
	\Ms\Dobrozhil\Lib\Cron::initOnNewDay();
}

\Ms\Dobrozhil\Lib\Cron::initOnNewHour();