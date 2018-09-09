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
	'PATH_OBJECT_ADD' => array (
		'NAME' => 'Относительный путь добавления нового объекта',
		'TYPE' => 'STRING',
		'DEFAULT' => 'object_add/'
	),
);