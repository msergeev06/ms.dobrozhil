<?php

/**
 * Основные функции модуля ms.dobrozhil, частично дублирующие основной функционал процедурной форме
 *
 * @package Ms\Dobrozhil
 * @author Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright 2018 Mikhail Sergeev
 */

use Ms\Core\Lib\Loc;

Loc::includeLocFile(__FILE__);

if (!function_exists('shutdown'))
{
	function shutdown ()
	{
		$documentRoot = \Ms\Core\Entity\Application::getInstance()->getDocumentRoot();
		\Ms\Core\Lib\IO\Files::saveFile($documentRoot.'/shutdown','shutdown');
		\Ms\Core\Lib\Logs::setInfo(
			//'Запуск события OnBeforeShutdown'
			Loc::getModuleMessage(
				'ms.dobrozhil',
				'START_EVENT_BEFORE_SHUTDOWN'
			)
		);
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
		\Ms\Core\Lib\Logs::setInfo(
			//'Запуск события OnBeforeReboot'
			Loc::getModuleMessage(
				'ms.dobrozhil',
				'START_EVENT_BEFORE_REBOOT'
			)
		);
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
		$str = $h.' '.\Ms\Core\Lib\Tools::sayRusRight(
			$h,
//			'час',
			Loc::getModuleMessage(
				'ms.dobrozhil',
				'HOUR_SUBJECTIVE_CASE'
			),
//			'часа',
			Loc::getModuleMessage(
				'ms.dobrozhil',
				'HOUR_GENITIVE_SINGULAR'
			),
//			'часов'
			Loc::getModuleMessage(
				'ms.dobrozhil',
				'HOUR_GENITIVE_PLURAL'
			)
			);
		if ($m == 0)
		{
			$str .= ' '.Loc::getModuleMessage(
				//  'ровно'
					'ms.dobrozhil',
					'EQUAL'
				);
		}
		else
		{
			$str .= ' '.$m.' '.\Ms\Core\Lib\Tools::sayRusRight(
				$m,
//				'минута',
				Loc::getModuleMessage(
					'ms.dobrozhil',
					'MINUTE_SUBJECTIVE_CASE'
				),
//				'минуты',
				Loc::getModuleMessage(
					'ms.dobrozhil',
					'MINUTE_GENITIVE_SINGULAR'
				),
//				'минут'
				Loc::getModuleMessage(
					'ms.dobrozhil',
					'MINUTE_GENITIVE_PLURAL'
				)
				);
		}

		return $str;
	}
}

/** Modules */

if (!function_exists('includeModule'))
{
	function includeModule ($sModuleName)
	{
		return \Ms\Core\Lib\Loader::includeModule($sModuleName);
	}
}

if (!function_exists('issetModule'))
{
	function issetModule ($sModuleName)
	{
		return \Ms\Core\Lib\Loader::issetModule($sModuleName);
	}
}

/** Scripts */

if (!function_exists('runScript'))
{
	function runScript ($sScriptName,$arParams)
	{
		return \Ms\Dobrozhil\Lib\Scripts::runScript($sScriptName,$arParams);
	}
}

if (!function_exists('issetScript'))
{
	function issetScript ($sScriptName)
	{
		return \Ms\Dobrozhil\Lib\Scripts::issetScript($sScriptName);
	}
}

