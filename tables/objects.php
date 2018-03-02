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

class ObjectsTable extends Lib\DataManager
{
	public static function getTableTitle ()
	{
		return 'Объекты классов';
	}

	protected static function getMap ()
	{
		return array(
			new Fields\StringField('NAME',array(
				'primary' => true,
				'title' => 'Имя объекта'
			)),
			new Fields\StringField('CLASS_NAME',array(
				'required' => true,
				'link' => ClassesTable::getTableName().'.NAME',
				'title' => 'Имя класса объекта'
			)),
			new Fields\StringField('NOTE',array(
				'title' => 'Краткое описание объекта класса'
			)),
			new Fields\StringField('ROOM_NAME',array(
				'title' => 'Имя комнаты, где расположен объект'
			)),
			new Fields\DateTimeField('CREATED',array(
				'required' => true,
				'default_insert' => new Date(),
				'title' => 'Время создания объекта'
			)),
			new Fields\DateTimeField('UPDATED',array(
				'required' => true,
				'default_insert' => new Date(),
				'default_update' => new Date(),
				'title' => 'Время обновления объекта'
			)),
			new Fields\BooleanField('SYSTEM',array(
				'required' => true,
				'default_create' => false,
				'default_insert' => false,
				'title' => 'Флаг системного объекта'
			))
		);
	}

	public static function getValues ()
	{
		return array(
			array(
				'NAME' => 'System',
				'CLASS_NAME' => 'CSystem',
				'NOTE' => 'Системный объект',
				'SYSTEM' => true
			)
		);
	}
}