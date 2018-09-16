<?php
/**
 * Компонент модуля ms.dobrozhil ms:dobrozhil.obj.list
 *
 * @package Ms\Dobrozhil
 * @subpackage Entity\Components
 * @author Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright 2018 Mikhail Sergeev
 */

use Ms\Core\Lib\Loc;
use \Ms\Dobrozhil\Lib\AdminPanel;

\Ms\Core\Lib\Loader::includeModule('ms.dobrozhil');
Loc::includeLocFile(__FILE__);

$adminPath = \Ms\Core\Entity\Application::getInstance()->getAppParam('admin_path');
$adminPath = (is_null($adminPath))?'/admin/':$adminPath;

return array(
	'SET_TITLE' => array(
		//'Устанавливать заголовок'
		'NAME' => Loc::getCompMess('ms:dobrozhil.obj.list','set_title'),
		'TYPE' => 'BOOL',
		'DEFAULT' => 'Y'
	),
	'ADD_NAV_CHAIN' => array (
		//'Добавлять хлебные крошки'
		'NAME' => Loc::getCompMess('ms:dobrozhil.obj.list','add_nav_chain'),
		'TYPE' => 'BOOL',
		'DEFAULT' => 'Y'
	),
	'USE_SEF' => array(
		//'Использовать ЧПУ'
		'NAME' => Loc::getCompMess('ms:dobrozhil.obj.list','use_sef'),
		'TYPE' => 'BOOL',
		'REFRESH' => true,
		'DEFAULT' => 'Y'
	),
	'VIEW' => array (
		//'Формат списка'
		'NAME' => 'Формат списка',
		'TYPE' => 'STRING',
		'DEFAULT' => 'tree'
	),
	'ADMIN_PATH' => array(
		//'Путь к панели администратора'
		'NAME' => Loc::getCompMess('ms:dobrozhil.obj.list','admin_path'),
		'TYPE' => 'STRING',
		'DEFAULT' => $adminPath
	),
	'ROOT_PATH' => array(
		//'Путь к разделу относительно корня'
		'NAME' => Loc::getCompMess('ms:dobrozhil.obj.list','root_path'),
		'TYPE' => 'STRING',
		'DEFAULT' => $adminPath.'objects/'
	),
	'PATH_CLASS_EDIT' => array (
		//'Относительный путь редактирования класса'
		'NAME' => Loc::getCompMess('ms:dobrozhil.obj.list','path_class_edit'),
		'TYPE' => 'STRING',
		'DEFAULT' => AdminPanel::getObjectsPagesDefault('class_edit')
	),
	'PATH_CLASS_PROPERTIES_LIST' => array (
		//'Относительный путь к списку свойств класса'
		'NAME' => Loc::getCompMess('ms:dobrozhil.obj.list','path_class_properties_list'),
		'TYPE' => 'STRING',
		'DEFAULT' => AdminPanel::getObjectsPagesDefault('class_properties_list')
	),
	'PATH_CLASS_METHODS_LIST' => array (
		//'Относительный путь к списку методов класса'
		'NAME' => Loc::getCompMess('ms:dobrozhil.obj.list','path_class_method_list'),
		'TYPE' => 'STRING',
		'DEFAULT' => AdminPanel::getObjectsPagesDefault('class_methods_list')
	),
	'PATH_CLASS_METHOD_EDIT' => array (
		//'Относительный путь к редактированию метода класса'
		'NAME' => Loc::getCompMess('ms:dobrozhil.obj.list','path_class_method_edit'),
		'TYPE' => 'STRING',
		'DEFAULT' => AdminPanel::getObjectsPagesDefault('class_method_edit')
	),
	'PATH_CLASS_OBJECTS_LIST' => array (
		//'Относительный путь к списку объектов класса'
		'NAME' => Loc::getCompMess('ms:dobrozhil.obj.list','path_class_objects_list'),
		'TYPE' => 'STRING',
		'DEFAULT' => AdminPanel::getObjectsPagesDefault('class_objects_list')
	),
	'PATH_CLASS_ADD_CHILD' => array (
		//'Относительный путь к добавлению наследующего класса'
		'NAME' => Loc::getCompMess('ms:dobrozhil.obj.list','path_class_add_child'),
		'TYPE' => 'STRING',
		'DEFAULT' => AdminPanel::getObjectsPagesDefault('class_add_child')
	),
	'PATH_CLASS_DELETE' => array (
		//'Относительный путь к удалению класса'
		'NAME' => Loc::getCompMess('ms:dobrozhil.obj.list','path_class_delete'),
		'TYPE' => 'STRING',
		'DEFAULT' => AdminPanel::getObjectsPagesDefault('class_delete')
	),
	'PATH_OBJECT_EDIT' => array (
		//'Относительный путь редактирования объекта'
		'NAME' => Loc::getCompMess('ms:dobrozhil.obj.list','path_object_edit'),
		'TYPE' => 'STRING',
		'DEFAULT' => AdminPanel::getObjectsPagesDefault('object_edit')
	)
);