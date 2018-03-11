<?php
/**
 * Описание модуля ms.dobrozhil
 *
 * @package Ms\Dobrozhil
 * @author Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright 2018 Mikhail Sergeev
 */

use Ms\Core\Lib\Loc;

Loc::includeLocFile(__FILE__);

return array(
	'NAME' => Loc::getModuleMessage('ms.dobrozhil','name'),
	'DESCRIPTION' => Loc::getModuleMessage('ms.dobrozhil','description'),
	'URL' => 'https://dobrozhil.ru',
	'DOCS' => 'http://docs.dobrozhil.ru',
	'AUTHOR' => Loc::getModuleMessage('ms.dobrozhil','author'),
	'AUTHOR_EMAIL' => 'msergeev06@gmail.com'
);