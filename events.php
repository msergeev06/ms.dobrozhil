<?php
/**
 * События, инициируемые модулем ms.dobrozhil
 *
 * @package Ms\Dobrozhil
 * @author Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright 2018 Mikhail Sergeev
 */

//TODO: Расписать подробнее все события
$arReturn['OnBeforeReboot'] = array ();
$arReturn['OnBeforeShutdown'] = array ();
$arReturn['OnStartUp'] = array ();

$arReturn['OnNewMinute'] = array ();
$arReturn['OnNewHour'] = array ();
$arReturn['OnNewDay'] = array ();
$arReturn['OnNewWeek'] = array ();
$arReturn['OnNewMonth'] = array ();
$arReturn['OnNewYear'] = array ();

$arReturn['OnBeforeGetObjectProperty'] = array ();
$arReturn['OnAfterGetObjectProperty'] = array ();
$arReturn['OnBeforeSetObjectProperty'] = array ();
$arReturn['OnAfterSetObjectProperty'] = array ();

return $arReturn;