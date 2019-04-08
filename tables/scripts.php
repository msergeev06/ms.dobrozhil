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
		//'Библиотека скриптов'
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
				//'Уникальное имя скрипта'
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_name')
			)),
			new Fields\StringField('MODULE',array (
				'required' => true,
				'default_create' => 'ms.dobrozhil',
				'default_insert' => 'ms.dobrozhil',
				//'Модуль, который предоставляет код скрипта'
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_module')
			)),
			new Fields\StringField('CLASS',array (
				'required' => true,
				'default_create' => '\Ms\Dobrozhil\Entity\Code\TextEditor',
				'default_insert' => '\Ms\Dobrozhil\Entity\Code\TextEditor',
				//'Класс, который предоставляет код скрипта'
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_class')
			)),
			new Fields\StringField(
				'CLASS_NAME',
				array (
					'title' => 'Имя класса метода'
				),
				ClassesTable::getTableName().'.NAME',
				'cascade',
				'cascade'
			),
			new Fields\StringField(
				'METHOD_NAME',
				array (
					'title' => 'Имя метода класса'
				),
				ClassMethodsTable::getTableName().'.METHOD_NAME',
				'cascade',
				'cascade'
			),
			new Fields\IntegerField(
				'CRON_JOB_ID',
				array (
					'title' => 'Задание крона'
				),
				CronTable::getTableName().'.ID',
				'cascade',
				'cascade'
			),
			new Fields\StringField(
				'SCHEDULER_NAME',
				array (
					'title' => 'Задание планировщика'
				),
				SchedulerTable::getTableName().'.NAME',
				'cascade',
				'cascade'
			),
/*			new Fields\TextField('CODE',array (
				//'Код скрипта модуля ms.dobrozhil'
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_code')
			)),*/
			new Fields\TextField('NOTE',array (
				//'Краткое описание скрипта'
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_note')
			)),
			new Fields\IntegerField(
				'CATEGORY_ID',
				array (
					//'ID категории скрипта'
					'title' => Loc::getModuleMessage('ms.dobrozhil','field_category_id')
				),
				ScriptsCategoriesTable::getTableName().'.ID',
				'cascade',
				'set_null'
			),
			new Fields\BooleanField('SYSTEM',array (
				'required' => true,
				'default_create' => false,
				'default_insert' => false,
				'title' => 'Флаг системного метода'
			)),
			new Fields\DateTimeField('LAST_RUN',array (
				//'Время последнего запуска скрипта'
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_last_run')
			)),
			new Fields\TextField('LAST_PARAMETERS',array (
				'serialized' => true,
				//'Параметры последнего запуска скрипта'
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_last_parameters')
			)),
			new Fields\DateTimeField('CREATED',array (
				'required' => true,
				'default_insert' => new Date(),
				//'Время создания скрипта'
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_created')
			)),
			new Fields\DateTimeField('UPDATED',array (
				'required' => true,
				'default_insert' => new Date(),
//				'default_update' => new Date(),
				//'Время изменения скрипта'
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_updated')
			))
		);
	}

	public static function getValues ()
	{
		return array(
			array(
				'NAME'=>'CSystem.setMaxVolume',
				'CLASS_NAME' => 'CSystem',
				'METHOD_NAME' => 'setMaxVolume',
				'SYSTEM' => true
			),
			array(
				'NAME'=>'CSystem.setMute',
				'CLASS_NAME' => 'CSystem',
				'METHOD_NAME' => 'setMute',
				'SYSTEM' => true
			),
			array(
				'NAME'=>'COperationModes.activateMode',
				'CLASS_NAME' => 'COperationModes',
				'METHOD_NAME' => 'activateMode',
				'SYSTEM' => true
			),
			array(
				'NAME'=>'COperationModes.deactivateMode',
				'CLASS_NAME' => 'COperationModes',
				'METHOD_NAME' => 'deactivateMode',
				'SYSTEM' => true
			),
			array(
				'NAME'=>'COperationModes.onChange_isActive',
				'CLASS_NAME' => 'COperationModes',
				'METHOD_NAME' => 'onChange_isActive',
				'SYSTEM' => true
			),
			array(
				'NAME'=>'CSystemStates.setGreen',
				'CLASS_NAME' => 'CSystemStates',
				'METHOD_NAME' => 'setGreen',
				'SYSTEM' => true
			),
			array(
				'NAME'=>'CSystemStates.setYellow',
				'CLASS_NAME' => 'CSystemStates',
				'METHOD_NAME' => 'setYellow',
				'SYSTEM' => true
			),
			array(
				'NAME'=>'CSystemStates.setRed',
				'CLASS_NAME' => 'CSystemStates',
				'METHOD_NAME' => 'setRed',
				'SYSTEM' => true
			),
			array(
				'NAME'=>'CSystemStates.onChange_state',
				'CLASS_NAME' => 'CSystemStates',
				'METHOD_NAME' => 'onChange_state',
				'SYSTEM' => true
			)
		);
	}


}