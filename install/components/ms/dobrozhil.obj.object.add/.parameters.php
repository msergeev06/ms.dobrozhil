<?php
/**
 * Компонент модуля ms.dobrozhil ms:dobrozhil.obj.object.add
 *
 * @package Ms\Dobrozhil
 * @subpackage Entity\Components
 * @author Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright 2018 Mikhail Sergeev
 */

use Ms\Core\Lib\Loc;
use \Ms\Dobrozhil\Lib\AdminPanel;

\Ms\Core\Lib\Loader::includeModule('ms.dobrozhil');
$adminPath = \Ms\Core\Entity\Application::getInstance()->getAppParam('admin_path');
$adminPath = (!is_null($adminPath))?$adminPath:'/admin/';

return array(
	'SET_TITLE' => array(
		//'Устанавливать заголовок'
		'NAME' => Loc::getCompMess('ms:dobrozhil.obj.object.add','set_title'),
		'TYPE' => 'BOOL',
		'DEFAULT' => 'Y'
	),
	'ADD_NAV_CHAIN' => array (
		//'Добавлять хлебные крошки'
		'NAME' => Loc::getCompMess('ms:dobrozhil.obj.object.add','add_nav_chain'),
		'TYPE' => 'BOOL',
		'DEFAULT' => 'Y'
	),
	'ROOT_PATH' => array(
		//'Путь к разделу относительно корня'
		'NAME' => Loc::getCompMess('ms:dobrozhil.obj.object.add','root_path'),
		'TYPE' => 'STRING',
		'DEFAULT' => $adminPath.'objects/'
	),
	'PATH_OBJECT_ADD' => array (
		//'Относительный путь добавления нового объекта'
		'NAME' => Loc::getCompMess('ms:dobrozhil.obj.object.add','object_add'),
		'TYPE' => 'STRING',
		'DEFAULT' => AdminPanel::getObjectsPagesDefault('object_add')
	)
);