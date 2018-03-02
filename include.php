<?php
/**
 * Основной подключаемый файл модуля
 *
 * @package Ms\Dobrozhil
 * @author Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright 2018 Mikhail Sergeev
 */

use Ms\Core\Lib\Loader;
use Ms\Core\Entity\Application;
$app = Application::getInstance();

$moduleName = 'ms.dobrozhil';
$moduleRoot = $app->getSettings()->getModulesRoot().'/'.$moduleName;
$namespaceRoot = 'Ms\Dobrozhil';

Loader::AddAutoLoadClasses(
	array(
		/** Entity\Objects */
		$namespaceRoot.'\Entity\Objects\Base' => $moduleRoot.'/entity/base.php',
		/** Lib */
		$namespaceRoot.'\Lib\Objects' => $moduleRoot.'/lib/objects.php',
		/** Tables */
		$namespaceRoot.'\Tables\ClassMethodsTable' => $moduleRoot.'/tables/class_methods.php',
		$namespaceRoot.'\Tables\ClassPropertiesTable' => $moduleRoot.'/tables/class_properties.php',
		$namespaceRoot.'\Tables\ClassesTable' => $moduleRoot.'/tables/classes.php',
		$namespaceRoot.'\Tables\ObjectsTable' => $moduleRoot.'/tables/objects.php',
		$namespaceRoot.'\Tables\ObjectsPropertyValuesTable' => $moduleRoot.'/tables/objects_property_values.php',
		$namespaceRoot.'\Tables\ObjectsPropertyValuesHistoryTable' => $moduleRoot.'/tables/objects_property_values_history.php'
	)
);

//***** Functions ********
//include_once($moduleRoot.'/functions/functions.main.php');
//include_once($moduleRoot.'/functions/functions.objects.php');

/*
$USER = &Application::getInstance()->getUser();
if ($USER->getID() != 2)
{
	$arRes = \MSergeev\Modules\Kuzmahome\Tables\UsersTable::getOne(
		array(
			'select' => array('ID','LINKED_OBJECT'),
			'filter' => array('USER_ID'=>$USER->getID())
		)
	);
	//msDebug($arRes);
	if ($arRes)
	{
		$USER->setParam('KUZMA_USER_ID',$arRes['ID']);
		$USER->setParam('LINKED_OBJECT',$arRes['LINKED_OBJECT']);
		$USER->setParam('propFullName',\MSergeev\Modules\Kuzmahome\Lib\Objects::getGlobal($arRes['LINKED_OBJECT'].'.propFullName'));
	}
}
unset($USER);
*/

//$TERMINAL = \MSergeev\Modules\Kuzmahome\Lib\Terminals::initTerminal();
//$GLOBALS['TERMINAL'] = $TERMINAL;
