<?php
/**
 * Описание таблицы планировщика повторяющихся задач
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

class CronTable extends DataManager
{
	public static function getTableTitle ()
	{
		return Loc::getModuleMessage('ms.dobrozhil','table_title');
	}

	protected static function getMap ()
	{
		return array (
			TableHelper::primaryField(),
			TableHelper::activeField(),
			new Fields\StringField('NAME',array (
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_name')
			)),
			new Fields\StringField('CRON_EXPRESSION',array (
				'required' => true,
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_cron_expression')
			)),
			new Fields\StringField('NOTE',array (
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_note')
			)),
			new Fields\TextField('CODE_CONDITION',array (
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_code_condition')
			)),
			new Fields\TextField('CODE',array (
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_code')
			)),
			new Fields\StringField('SCRIPT_NAME',array (
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_script_name')
			)),
			new Fields\DateTimeField('NEXT_RUN',array (
				'required' => true,
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_next_run')
			)),
			new Fields\BooleanField('RUNNING',array (
				'required' => true,
				'default_create' => false,
				'default_insert' => false,
				'default_update' => false,
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_running')
			)),
			new Fields\DateTimeField('LAST_RUN',array (
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_last_run')
			)),
			new Fields\DateTimeField('CHANGED',array (
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_changed')
			))
		);
	}
}
