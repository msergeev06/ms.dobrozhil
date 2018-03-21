<?php
/**
 * Основные функции модуля ms.dobrozhil, частично дублирующие основной функционал процедурной форме
 *
 * @package Ms\Dobrozhil
 * @author Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright 2018 Mikhail Sergeev
 */

if (!function_exists('shutdown'))
{
	function shutdown ()
	{
		$documentRoot = \Ms\Core\Entity\Application::getInstance()->getDocumentRoot();
		\Ms\Core\Lib\IO\Files::saveFile($documentRoot.'/shutdown','shutdown');
		\Ms\Core\Lib\Logs::write2Log('Запуск события OnBeforeShutdown');
		\Ms\Core\Lib\Events::runEvents('ms.dobrozhil','OnBeforeShutdown');
		sleep(5);
		exec('sudo shutdown now');
	}
}

if (!function_exists('reboot'))
{
	function reboot ()
	{
		$documentRoot = \Ms\Core\Entity\Application::getInstance()->getDocumentRoot();
		\Ms\Core\Lib\IO\Files::saveFile($documentRoot.'/reboot','reboot');
		\Ms\Core\Lib\Logs::write2Log('Запуск события OnBeforeReboot');
		\Ms\Core\Lib\Events::runEvents('ms.dobrozhil','OnBeforeReboot');
		sleep(5);
		exec ('sudo reboot now');
	}
}

if (!function_exists('time_now'))
{
	function time_now ()
	{
		$now = new \Ms\Core\Entity\Type\Date();
		$h = (int)$now->format('H');
		$m = (int)$now->format('i');
		$str = $h.' '.\Ms\Core\Lib\Tools::sayRusRight($h,'час','часа','часов');
		if ($m == 0)
		{
			$str .= ' ровно';
		}
		else
		{
			$str .= ' '.$m.' '.\Ms\Core\Lib\Tools::sayRusRight($m,'минута','минуты', 'минут');
		}

		return $str;
	}
}