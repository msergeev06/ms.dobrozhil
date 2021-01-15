<?php

/**
 * Основные функции модуля ms.dobrozhil, частично дублирующие основной функционал процедурной форме
 *
 * @package   Ms\Dobrozhil
 * @author    Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright 2018 Mikhail Sergeev
 */

IncludeLangFile(__FILE__);

if (!function_exists('shutdown'))
{
    function shutdown ()
    {
        $logger = new \Ms\Core\Entity\Errors\FileLogger(
            'ms.dobrozhil',
            \Ms\Core\Entity\Errors\FileLogger::TYPE_NOTICE
        );
        $documentRoot = \Ms\Core\Entity\System\Application::getInstance()->getDocumentRoot();
        \Ms\Core\Lib\IO\Files::saveFile($documentRoot . '/shutdown', 'shutdown');
        //'Запуск события OnBeforeShutdown'
        $logger->addMessage(GetModuleMessage('ms.dobrozhil', 'start_event_before_shutdown'));
        \Ms\Core\Entity\Events\EventController::getInstance()->runEvents(
            'ms.dobrozhil',
            'OnBeforeShutdown'
        )
        ;
        sleep(5);
        exec('sudo shutdown now');
    }
}

if (!function_exists('reboot'))
{
    function reboot ()
    {
        $logger = new \Ms\Core\Entity\Errors\FileLogger(
            'ms.dobrozhil',
            \Ms\Core\Entity\Errors\FileLogger::TYPE_NOTICE
        );
        $documentRoot = \Ms\Core\Entity\System\Application::getInstance()->getDocumentRoot();
        \Ms\Core\Lib\IO\Files::saveFile($documentRoot . '/reboot', 'reboot');
        //'Запуск события OnBeforeReboot'
        $logger->addMessage(GetModuleMessage('ms.dobrozhil', 'start_event_before_reboot'));
        \Ms\Core\Entity\Events\EventController::getInstance()->runEvents(
            'ms.dobrozhil',
            'OnBeforeReboot'
        )
        ;
        sleep(5);
        exec('sudo reboot now');
    }
}

if (!function_exists('time_now'))
{
    function time_now ()
    {
        $now = new \Ms\Core\Entity\Type\Date();
        $h = (int)$now->format('H');
        $m = (int)$now->format('i');
        $str = $h . ' ' . \Ms\Core\Lib\Tools::sayRusRight(
                $h,
                //			'час',
                GetModuleMessage(
                    'ms.dobrozhil',
                    'hour_subjective_case'
                ),
                //			'часа',
                GetModuleMessage(
                    'ms.dobrozhil',
                    'hour_genitive_singular'
                ),
                //			'часов'
                GetModuleMessage(
                    'ms.dobrozhil',
                    'hour_genitive_plural'
                )
            );
        if ($m == 0)
        {
            $str .= ' ' . GetModuleMessage(
                //  'ровно'
                    'ms.dobrozhil',
                    'equal'
                );
        }
        else
        {
            $str .= ' ' . $m . ' ' . \Ms\Core\Lib\Tools::sayRusRight(
                    $m,
                    //				'минута',
                    GetModuleMessage(
                        'ms.dobrozhil',
                        'minute_subjective_case'
                    ),
                    //				'минуты',
                    GetModuleMessage(
                        'ms.dobrozhil',
                        'minute_genitive_singular'
                    ),
                    //				'минут'
                    GetModuleMessage(
                        'ms.dobrozhil',
                        'minute_genitive_plural'
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
        return \Ms\Core\Entity\Modules\Loader::includeModule($sModuleName);
    }
}

if (!function_exists('issetModule'))
{
    function issetModule ($sModuleName)
    {
        return \Ms\Core\Entity\Modules\Loader::issetModule($sModuleName);
    }
}

/** Scripts */

if (!function_exists('runScript'))
{
    function runScript ($sScriptName, $arParams)
    {
        return \Ms\Dobrozhil\Lib\Scripts::runScript($sScriptName, $arParams);
    }
}

if (!function_exists('issetScript'))
{
    function issetScript ($sScriptName)
    {
        return \Ms\Dobrozhil\Lib\Scripts::issetScript($sScriptName);
    }
}

