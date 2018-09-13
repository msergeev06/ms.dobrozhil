<?php

return array(
	'SET_TITLE' => array(
		'NAME' => 'Устанавливать заголовок',
		'TYPE' => 'BOOL',
		'DEFAULT' => 'Y'
	),
	'USE_SEF' => array(
		'NAME' => 'Использовать ЧПУ',
		'TYPE' => 'BOOL',
		'REFRESH' => true,
		'DEFAULT' => 'Y'
	),
	'ROOT_PATH' => array(
		'NAME' => 'Путь к разделу относительно корня',
		'TYPE' => 'STRING',
		'DEFAULT' => '/ms/admin/objects/'
	),
	'PATH_CLASS_ADD' => array (
		'NAME' => 'Относительный путь добавления нового класса',
		'TYPE' => 'STRING',
		'DEFAULT' => 'class_add/'
	),
	'PATH_CLASS_EDIT' => array (
		'NAME' => 'Относительный путь редактирования класса',
		'TYPE' => 'STRING',
		'DEFAULT' => 'class_edit/#CLASS_NAME#/'
	),
	'PATH_CLASS_PROPERTIES_LIST' => array (
		'NAME' => 'Относительный путь к списку свойств класса',
		'TYPE' => 'STRING',
		'DEFAULT' => 'class_properties_list/#CLASS_NAME#/'
	),
	'PATH_CLASS_METHODS_LIST' => array (
		'NAME' => 'Относительный путь к списку методов класса',
		'TYPE' => 'STRING',
		'DEFAULT' => 'class_methods_list/#CLASS_NAME#/'
	),
	'PATH_CLASS_METHOD_EDIT' => array (
		'NAME' => 'Относительный путь к редактированию метода класса',
		'TYPE' => 'STRING',
		'DEFAULT' => 'class_method_edit/#METHOD_NAME#/'
	),
	'PATH_CLASS_OBJECTS_LIST' => array (
		'NAME' => 'Относительный путь к списку объектов класса',
		'TYPE' => 'STRING',
		'DEFAULT' => 'class_objects_list/#CLASS_NAME#/'
	),
	'PATH_CLASS_ADD_CHILD' => array (
		'NAME' => 'Относительный путь к добавлению наследующего класса',
		'TYPE' => 'STRING',
		'DEFAULT' => 'class_add_child/#CLASS_NAME#/'
	),
	'PATH_CLASS_DELETE' => array (
		'NAME' => 'Относительный путь к удалению класса',
		'TYPE' => 'STRING',
		'DEFAULT' => 'class_delete/#CLASS_NAME#/'
	),
	'PATH_OBJECT_ADD' => array (
		'NAME' => 'Относительный путь добавления нового объекта',
		'TYPE' => 'STRING',
		'DEFAULT' => 'object_add/'
	),
	'PATH_OBJECT_EDIT' => array (
		'NAME' => 'Относительный путь редактирования объекта',
		'TYPE' => 'STRING',
		'DEFAULT' => 'object_edit/#OBJECT_NAME#/'
	),
);