<?php
/**
 * Описание таблицы методов классов
 *
 * @package Ms\Dobrozhil
 * @subpackage Tables
 * @author Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright 2018 Mikhail Sergeev
 */

namespace Ms\Dobrozhil\Tables;

use Ms\Core\Entity\Type\Date;
use Ms\Core\Lib;
use Ms\Core\Entity\Db\Fields;
use Ms\Core\Lib\Loc;

Loc::includeLocFile(__FILE__);

class ClassMethodsTable extends Lib\DataManager
{
	public static function getTableTitle ()
	{
		return Loc::getModuleMessage('ms.dobrozhil','table_title'); //Методы классов
	}

	public static function getMap ()
	{
		return array(
			new Fields\StringField('NAME',array(
				'primary' => true,
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_name') //'Полное имя метода вида класс.метод'
			)),
			new Fields\StringField('METHOD_NAME',array(
				'required' => true,
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_method_name') //'Имя метода без имени класса'
			)),
			new Fields\StringField('CLASS_NAME',array(
				'required' => true,
				'link' => ClassesTable::getTableName().'.NAME',
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_class_name') //'Имя класса без имени метода'
			)),
			new Fields\StringField('NOTE',array(
				'title' => Loc::getModuleMessage('ms.dobrozhil','filed_note') //'Краткое описание метода'
			)),
			new Fields\StringField('SCRIPT_NAME',array(
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_script_name') //'Имя скрипта, вместо кода метода'
			)),
			new Fields\TextField('CODE',array(
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_code') //'Код метода'
			)),
			new Fields\TextField('LAST_PARAMETERS',array(
				'serialized' => true,
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_last_parameters') //'Массив значений параметров последнего запуска'
			)),
			new Fields\DateTimeField('LAST_RUN',array(
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_last_run') //'Время последнего запуска метода'
			)),
			new Fields\DateTimeField('CREATED',array(
				'required' => true,
				'default_insert' => new Date(),
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_created') //'Время создания метода'
			)),
			new Fields\DateTimeField('UPDATED',array(
				'required' => true,
				'default_insert' => new Date(),
				'default_update' => new Date(),
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_updated') //'Время обновления метода'
			))
		);
	}

	public static function getValues ()
	{
		return array(
			array(
				'NAME' => 'CSystem.setMaxVolume',
				'METHOD_NAME' => 'setMaxVolume',
				'CLASS_NAME' => 'CSystem',
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_set_max_volume'), //'Устанавливает максимальную громкость'
				'CODE' => '$this->volumeLevel = 100;'
			),
			array(
				'NAME' => 'CSystem.setMute',
				'METHOD_NAME' => 'setMute',
				'CLASS_NAME' => 'CSystem',
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_set_mute'), //'Устанавливает громкость на 0'
				'CODE' => '$this->volumeLevel = 0;'
			),
			array(
				'NAME' => 'CSystem.OnNewDay',
				'METHOD_NAME' => 'OnNewDay',
				'CLASS_NAME' => 'CSystem',
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_on_new_day') //'Выполняется каждый новый день'
			),
			array(
				'NAME' => 'CSystem.OnNewHour',
				'METHOD_NAME' => 'OnNewHour',
				'CLASS_NAME' => 'CSystem',
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_on_new_hour') //'Выполняется каждый новый час'
			),
			array(
				'NAME' => 'CSystem.OnNewMinute',
				'METHOD_NAME' => 'OnNewMinute',
				'CLASS_NAME' => 'CSystem',
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_on_new_minute') //'Выполняется каждую новую минуту'
			),
			array(
				'NAME' => 'CSystem.OnNewMonth',
				'METHOD_NAME' => 'OnNewMonth',
				'CLASS_NAME' => 'CSystem',
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_on_new_month') //'Выполняется каждый новый месяц'
			),
			array(
				'NAME' => 'CSystem.OnNewYear',
				'METHOD_NAME' => 'OnNewYear',
				'CLASS_NAME' => 'CSystem',
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_on_new_year') //'Выполняется каждый новый год'
			),
			array(
				'NAME' => 'CSystem.OnShutDown',
				'METHOD_NAME' => 'OnShutDown',
				'CLASS_NAME' => 'CSystem',
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_on_shut_down') //'Выполняется перед выключением'
			),
			array(
				'NAME' => 'CSystem.OnStartUp',
				'METHOD_NAME' => 'OnStartUp',
				'CLASS_NAME' => 'CSystem',
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_on_start_up') //'Выполняется при включении'
			)
		);
	}
}