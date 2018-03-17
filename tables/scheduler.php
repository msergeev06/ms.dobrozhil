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
		return Loc::getModuleMessage('ms.dobrozhil','table_title'); //Планировщик действий
	}

	protected static function getMap ()
	{
		return array (
			new Fields\StringField('NAME',array (
				'primary' => true,
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_name') //'ID запланированной задачи'
			)),
			new Fields\TextField('CODE',array (
				'required' => true,
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_code') //'PHP код задачи'
			)),
			new Fields\DateTimeField('RUNTIME',array(
				'required' => true,
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_runtime') //'Запланированное время запуска задачи'
			)),
			new Fields\DateTimeField('EXPIRE',array(
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_expire') //'Время истечения задачи'
			)),
			new Fields\BooleanField('PROCESSED',array (
				'required' => true,
				'default_create' => false,
				'default_insert' => false,
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_processed') //'Флаг исполнения задачи'
			)),
			new Fields\DateTimeField('STARTED',array (
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_started') //'Фактическое время запуска задачи'
			))
		);
	}
}