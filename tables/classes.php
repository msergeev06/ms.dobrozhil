<?php
/**
 * Описание таблицы классов объектов
 *
 * @package Ms\Dobrozhil
 * @subpackage Tables
 * @author Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright 2018 Mikhail Sergeev
 */

namespace Ms\Dobrozhil\Tables;

use Ms\Core\Lib;
use Ms\Core\Entity\Db\Fields;
use Ms\Core\Entity\Type\Date;
use Ms\Core\Lib\Loc;

Loc::includeLocFile(__FILE__);

class ClassesTable extends Lib\DataManager
{
	public static function getTableTitle()
	{
		return Loc::getModuleMessage('ms.dobrozhil','table_title'); //'Классы'
	}

	public static function getTableLinks ()
	{
		return array(
			'NAME' => array(
				static::getTableName()                  => 'PARENT_CLASS',
				ObjectsTable::getTableName()            => 'CLASS_NAME',
				ClassPropertiesTable::getTableName()    => 'CLASS_NAME',
				ClassMethodsTable::getTableName()       => 'CLASS_NAME'
			)
		);
	}

	protected static function getMap ()
	{
		return array(
			new Fields\StringField('NAME',array(
				'primary' => true,
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_name') //'Имя класса'
			)),
			Lib\TableHelper::sortField(),
			new Fields\StringField('NOTE',array(
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_note') //'Краткое описание класса'
			)),
			new Fields\StringField('PARENT_CLASS',array(
				'link' => static::getTableName().'.NAME',
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_parent_class') //'Родительский класс'
			)),
			new Fields\TextField('PARENT_LIST',array(
				'serialized' => true,
				'title' => Loc::getModuleMessage('ms.dobrozhil','note_parent_list') //'Список родителей класса'
			)),
			new Fields\TextField('CHILDREN_LIST',array(
				'serialized' => true,
				'title' => Loc::getModuleMessage('ms.dobrozhil','note_children_list') //'Список потомков класса'
			)),
			new Fields\StringField('MODULE',array(
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_module') //'Модуль, который добавил класс'
			)),
			new Fields\StringField('NAMESPACE',array(
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_namespace') //'Путь для создания объекта класса'
			)),
			new Fields\StringField('TYPE',array(
				'required' => true,
				'size' => 1,
				'default_create' => 'U',
				'default_insert' => 'U',
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_type') //'Тип класса ("U" - пользовательский, "P" - программный, "S" - системный)'
			)),
			new Fields\DateTimeField('CREATED',array(
				'required' => true,
				'default_insert' => new Date(),
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_created') //'Дата создания класса'
			)),
			new Fields\DateTimeField('UPDATED',array(
				'required' => true,
				'default_insert' => new Date(),
				'default_update' => new Date(),
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_updated') //'Дата последнего редактирования класса'
			))
		);
	}

	public static function getValues ()
	{
		return array(
			array(
				'NAME' => 'CSystem',
				'SORT' => 10,
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_c_system'), //'Основной класс системы'
				'TYPE' => 'S'
			)
		);
	}
}