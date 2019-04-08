<?php
/**
 * Описание таблицы планировщика действий
 *
 * @package Ms\Dobrozhil
 * @subpackage Tables
 * @author Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright 2018 Mikhail Sergeev
 */

namespace Ms\Dobrozhil\Tables;

use Ms\Core\Lib\DataManager;
use Ms\Core\Entity\Db\Fields;
use Ms\Core\Lib\Loc;

Loc::includeLocFile(__FILE__);

class SchedulerTable extends DataManager
{
	public static function getTableTitle ()
	{
		//Планировщик действий
		return Loc::getModuleMessage('ms.dobrozhil','table_title');
	}

	protected static function getMap ()
	{
		return array (
			new Fields\StringField('NAME',array (
				'primary' => true,
				//'ID запланированной задачи'
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_name')
			)),
/*			new Fields\StringField('SCRIPT_NAME',array (
				'link' => ScriptsTable::getTableName().'.NAME',
				//Имя скрипта, вместо PHP кода
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_script_name')
			)),
			new Fields\TextField('CODE',array (
				//'PHP код задачи'
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_code')
			)),*/
			new Fields\DateTimeField('RUNTIME',array(
				'required' => true,
				//'Запланированное время запуска задачи'
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_runtime')
			)),
			new Fields\DateTimeField('EXPIRE',array(
				//'Время истечения задачи'
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_expire')
			)),
			new Fields\BooleanField('PROCESSED',array (
				'required' => true,
				'default_create' => false,
				'default_insert' => false,
				//'Флаг исполнения задачи'
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_processed')
			)),
			new Fields\DateTimeField('STARTED',array (
				//'Фактическое время запуска задачи'
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_started')
			))
		);
	}
}