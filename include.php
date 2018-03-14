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
		$namespaceRoot.'\Entity\Objects\Base' => $moduleRoot.'/entity/objects/base.php',
		/** Entity\Types */
		$namespaceRoot.'\Entity\Types\TypeBool' => $moduleRoot.'/entity/types/type_bool.php',
		$namespaceRoot.'\Entity\Types\TypeDate' => $moduleRoot.'/entity/types/type_date.php',
		$namespaceRoot.'\Entity\Types\TypeDatetime' => $moduleRoot.'/entity/types/type_datetime.php',
		$namespaceRoot.'\Entity\Types\TypeFloat' => $moduleRoot.'/entity/types/type_float.php',
		$namespaceRoot.'\Entity\Types\TypeFloat' => $moduleRoot.'/entity/types/type_float.php',
		$namespaceRoot.'\Entity\Types\TypeInt' => $moduleRoot.'/entity/types/type_int.php',
		$namespaceRoot.'\Entity\Types\TypeString' => $moduleRoot.'/entity/types/type_string.php',
		$namespaceRoot.'\Entity\Types\TypeTime' => $moduleRoot.'/entity/types/type_time.php',
		$namespaceRoot.'\Entity\Types\TypeTimestamp' => $moduleRoot.'/entity/types/type_timestamp.php',
		/** Interfaces */
		$namespaceRoot.'\Interfaces\TypeProcessing' => $moduleRoot.'/interfaces/type_processing.php',
		/** Lib */
		$namespaceRoot.'\Lib\Classes' => $moduleRoot.'/lib/classes.php',
		$namespaceRoot.'\Lib\Objects' => $moduleRoot.'/lib/objects.php',
		$namespaceRoot.'\Lib\Types' => $moduleRoot.'/lib/types.php',
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
	$arRes = \Ms\Dobrozhil\Tables\UsersTable::getOne(
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
		$USER->setParam('propFullName',\Ms\Dobrozhil\Lib\Objects::getGlobal($arRes['LINKED_OBJECT'].'.propFullName'));
	}
}
unset($USER);
*/

//$TERMINAL = \Ms\Dobrozhil\Lib\Terminals::initTerminal();
//$GLOBALS['TERMINAL'] = $TERMINAL;
