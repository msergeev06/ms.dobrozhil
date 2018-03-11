<?php
/**
 * Описание таблицы объектов
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

class ObjectsTable extends Lib\DataManager
{
	public static function getTableTitle ()
	{
		return Loc::getModuleMessage('ms.dobrozhil','table_title'); //'Объекты классов'
	}

	protected static function getMap ()
	{
		return array(
			new Fields\StringField('NAME',array(
				'primary' => true,
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_name') //'Имя объекта'
			)),
			new Fields\StringField('CLASS_NAME',array(
				'required' => true,
				'link' => ClassesTable::getTableName().'.NAME',
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_class_name') //'Имя класса объекта'
			)),
			new Fields\StringField('NOTE',array(
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_note') //'Краткое описание объекта класса'
			)),
			new Fields\StringField('ROOM_NAME',array(
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_room_name') //'Имя комнаты, где расположен объект'
			)),
			new Fields\DateTimeField('CREATED',array(
				'required' => true,
				'default_insert' => new Date(),
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_created') //'Время создания объекта'
			)),
			new Fields\DateTimeField('UPDATED',array(
				'required' => true,
				'default_insert' => new Date(),
				'default_update' => new Date(),
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_updated') //'Время обновления объекта'
			)),
			new Fields\BooleanField('SYSTEM',array(
				'required' => true,
				'default_create' => false,
				'default_insert' => false,
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_system') //'Флаг системного объекта'
			))
		);
	}

	public static function getValues ()
	{
		return array(
			array(
				'NAME' => 'System',
				'CLASS_NAME' => 'CSystem',
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_system'), //'Системный объект'
				'SYSTEM' => true
			)
		);
	}
}