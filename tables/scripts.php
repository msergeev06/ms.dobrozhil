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
/*			new Fields\TextField('CODE',array (
				//'Код скрипта модуля ms.dobrozhil'
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_code')
			)),*/
			new Fields\TextField('NOTE',array (
				//'Краткое описание скрипта'
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_note')
			)),
			new Fields\IntegerField('CATEGORY_ID',array (
				'link' => ScriptsCategoriesTable::getTableName().'.ID',
				//'ID категории скрипта'
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_category_id')
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
				'CATEGORY_ID' => 1
			),
			array(
				'NAME'=>'CSystem.setMute',
				'CATEGORY_ID' => 1
			),
			array(
				'NAME'=>'COperationModes.activateMode',
				'CATEGORY_ID' => 1
			),
			array(
				'NAME'=>'COperationModes.deactivateMode',
				'CATEGORY_ID' => 1
			),
			array(
				'NAME'=>'COperationModes.onChange_isActive',
				'CATEGORY_ID' => 1
			),
			array(
				'NAME'=>'CSystemStates.setGreen',
				'CATEGORY_ID' => 1
			),
			array(
				'NAME'=>'CSystemStates.setYellow',
				'CATEGORY_ID' => 1
			),
			array(
				'NAME'=>'CSystemStates.setRed',
				'CATEGORY_ID' => 1
			),
			array(
				'NAME'=>'CSystemStates.onChange_state',
				'CATEGORY_ID' => 1
			)
		);
	}


}