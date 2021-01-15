<?php
/**
 * Основные настройки для работы с заданиями крона
 *
 * @package    SHF "Доброжил"
 * @subpackage Ms\Dobrozhil
 * @author     Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright  2021 Mikhail Sergeev
 */

$arSettingsMs = include (dirname(__FILE__).'/../../../.settings.php');
$arSettingsLocal = [];
if (file_exists(dirname(__FILE__).'/../../../../local/.settings.php'))
{
    $arSettingsLocal = include (dirname(__FILE__).'/../../../../local/.settings.php');
}



define('NO_HTTP_AUTH',true);
define('RUN_CRONTAB_JOB',true);
define('NO_CHECK_AGENTS',true);

