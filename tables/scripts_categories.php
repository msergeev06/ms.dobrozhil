<?php
/**
 * Описание таблицы категорий скриптов
 *
 * @package Ms\Dobrozhil
 * @subpackage Tables
 * @author Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright 2018 Mikhail Sergeev
 */

namespace Ms\Dobrozhil\Tables;

use Ms\Core\Lib\DataManager;
use Ms\Core\Entity\Db\Fields;
use Ms\Core\Lib\TableHelper;
use Ms\Core\Lib\Loc;

Loc::includeLocFile(__FILE__);

class ScriptsCategoriesTable extends DataManager
{
	public static function getTableTitle ()
	{
		//'Категории скриптов'
		return Loc::getModuleMessage('ms.dobrozhil','table_title');
	}

	public static function getTableLinks ()
	{
		return array (
			'ID' => array (
				ScriptsTable::getTableName() => 'CATEGORY_ID'
			)
		);
	}

	protected static function getMap ()
	{
		return array (
			TableHelper::primaryField(),
			new Fields\StringField('TITLE',array (
				'required' => true,
				//'Название категории'
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_title')
			))
		);
	}
}