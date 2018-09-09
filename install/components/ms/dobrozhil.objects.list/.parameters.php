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
	'VIEW' => array (
		'NAME' => 'Формат списка',
		'TYPE' => 'STRING',
		'DEFAULT' => 'tree'
	),
	'ROOT_PATH' => array(
		'NAME' => 'Путь к разделу относительно корня',
		'TYPE' => 'STRING',
		'DEFAULT' => '/ms/admin/objects/'
	)
);