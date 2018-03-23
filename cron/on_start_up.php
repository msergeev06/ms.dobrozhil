<?php
/**
 * Crontab задание выполняющееся при старте системы
 *
 * @package Ms\Dobrozhil
 * @author Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright 2018 Mikhail Sergeev
 */

$_SERVER['DOCUMENT_ROOT'] = dirname(__FILE__).'/../../../../';

set_time_limit(0);

$f1 = fopen($_SERVER['DOCUMENT_ROOT'].'/startup','w');
fwrite($f1,'startup');
fclose($f1);
$bGoodStart = false;
if (file_exists($_SERVER['DOCUMENT_ROOT'].'/reboot'))
{
	unlink($_SERVER['DOCUMENT_ROOT'].'/reboot');
	$bGoodStart = true;
}
if (file_exists($_SERVER['DOCUMENT_ROOT'].'/shutdown'))
{
	unlink($_SERVER['DOCUMENT_ROOT'].'/shutdown');
	$bGoodStart = true;
}

for ($i=0; $i<7; $i++)
{
	echo 'Ждем '.($i+1)."...\n";
	sleep(1);
}
exec('sudo ntpdate -u ntp.ubuntu.com',$out);
echo implode("\n",$out)."\n";

define('MS_NO_CHECK_AGENTS',true);
include_once ($_SERVER['DOCUMENT_ROOT']."/ms/core/prolog_before.php");

if (!$bGoodStart)
{
	\Ms\Core\Lib\Logs::setInfo('Система загружена после непредвиденного завершения работы. Активация события OnStartUp');
}
else
{
	\Ms\Core\Lib\Logs::setInfo('Система успешно загружена. Активация события OnStartUp');
}

\Ms\Core\Lib\Events::runEvents('ms.dobrozhil','OnStartUp',array ($bGoodStart));

if (file_exists($_SERVER['DOCUMENT_ROOT'].'/startup'))
{
	unlink($_SERVER['DOCUMENT_ROOT'].'/startup');
}

\Ms\Core\Lib\Logs::setInfo('Все стартовые действия завершены. Система запущена.');