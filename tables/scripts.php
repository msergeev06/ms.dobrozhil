<?php
/**
 * Описание таблицы библиотеки скриптов
 *
 * @package Ms\Dobrozhil
 * @subpackage Tables
 * @author Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright 2018 Mikhail Sergeev
 */

namespace Ms\Dobrozhil\Tables;

use Ms\Core\Entity\Db\Fields;
use Ms\Core\Entity\Type\Date;
use Ms\Core\Lib\DataManager;
use Ms\Core\Lib\Loc;

Loc::includeLocFile(__FILE__);

class ScriptsTable extends DataManager
{
	public static function getTableTitle ()
	{
		return Loc::getModuleMessage('ms.dobrozhil','table_title');
	}

	public static function getTableLinks ()
	{
		return array (
			'NAME' => array (
				ClassMethodsTable::getTableName() => 'SCRIPT_NAME',
				SchedulerTable::getTableName() => 'SCRIPT_NAME',
				CronTable::getTableName() => 'SCRIPT_NAME'
			)
		);
	}


	protected static function getMap ()
	{
		return array (
			new Fields\StringField('NAME',array (
				'primary' => true,
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_name')
			)),
			new Fields\StringField('MODULE',array (
				'required' => true,
				'default_create' => 'ms.dobrozhil',
				'default_insert' => 'ms.dobrozhil',
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_module')
			)),
			new Fields\StringField('CLASS',array (
				'required' => true,
				'default_create' => 'Ms\Dobrozhil\Lib\Scripts',
				'default_insert' => 'Ms\Dobrozhil\Lib\Scripts',
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_class')
			)),
			new Fields\TextField('CODE',array (
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_code')
			)),
			new Fields\StringField('NOTE',array (
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_note')
			)),
			new Fields\IntegerField('CATEGORY_ID',array (
				'link' => ScriptsCategoriesTable::getTableName().'.ID',
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_category_id')
			)),
			new Fields\DateTimeField('LAST_RUN',array (
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_last_run')
			)),
			new Fields\TextField('LAST_PARAMETERS',array (
				'serialized' => true,
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_last_parameters')
			)),
			new Fields\DateTimeField('CREATED',array (
				'required' => true,
				'default_insert' => new Date(),
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_created')
			)),
			new Fields\DateTimeField('UPDATED',array (
				'required' => true,
				'default_insert' => new Date(),
				'default_update' => new Date(),
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_updated')
			))
		);
	}


}