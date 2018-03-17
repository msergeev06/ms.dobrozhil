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
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_parent_list') //'Список родителей класса'
			)),
			new Fields\TextField('CHILDREN_LIST',array(
				'serialized' => true,
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_children_list') //'Список потомков класса'
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
			),
			array(
				'NAME' => 'CRooms',
				'SORT' => 15,
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_c_rooms'), //'Класс комнат дома'
				'MODULE' => 'ms.dobrozhil',
				'NAMESPACE' => 'Ms\Dobrozhil\Entity\Objects\Rooms',
				'TYPE' => 'S'
			),
			array(
				'NAME' => 'CUsers',
				'SORT' => 20,
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_c_users'), //'Класс пользователей системы'
				'MODULE' => 'ms.dobrozhil',
				'NAMESPACE' => 'Ms\Dobrozhil\Entity\Objects\Users',
				'TYPE' => 'S'
			),
			array(
				'NAME' => 'CSystemStates',
				'SORT' => 25,
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_c_system_states'), //'Класс системных статусов'
				'MODULE' => 'ms.dobrozhil',
				'NAMESPACE' => 'Ms\Dobrozhil\Entity\Objects\SystemStates',
				'TYPE' => 'S'
			),
			array(
				'NAME' => 'COperationModes',
				'SORT' => 30,
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_c_operation_modes'), //'Класс режимов работы'
				'MODULE' => 'ms.dobrozhil',
				'NAMESPACE' => 'Ms\Dobrozhil\Entity\Objects\OperationModes',
				'TYPE' => 'S'
			),
			array(
				'NAME' => 'CDarknessMode',
				'PARENT_CLASS' => 'COperationModes',
				'PARENT_LIST' => array('COperationModes'),
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_c_darkness_mode'), //'Режим Темное время суток'
				'TYPE' => 'S'
			),
			array(
				'NAME' => 'CCinemaMode',
				'PARENT_CLASS' => 'COperationModes',
				'PARENT_LIST' => array('COperationModes'),
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_c_cinema_mode'), //'Режим кино'
				'TYPE' => 'S'
			),
			array(
				'NAME' => 'CEcoMode',
				'PARENT_CLASS' => 'COperationModes',
				'PARENT_LIST' => array('COperationModes'),
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_c_eco_mode'), //'Режим экономии'
				'TYPE' => 'S'
			),
			array(
				'NAME' => 'CGuestsMode',
				'PARENT_CLASS' => 'COperationModes',
				'PARENT_LIST' => array('COperationModes'),
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_c_guests_mode'), //'Режим Пришли гости'
				'TYPE' => 'S'
			),
			array(
				'NAME' => 'CNightMode',
				'PARENT_CLASS' => 'COperationModes',
				'PARENT_LIST' => array('COperationModes'),
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_c_night_mode'), //'Режим Ночной'
				'TYPE' => 'S'
			),
			array(
				'NAME' => 'CNobodyHomeMode',
				'PARENT_CLASS' => 'COperationModes',
				'PARENT_LIST' => array('COperationModes'),
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_c_nobody_home_mode'), //'Режим Никого нет дома'
				'TYPE' => 'S'
			),
			array(
				'NAME' => 'CSecurityArmedMode',
				'PARENT_CLASS' => 'COperationModes',
				'PARENT_LIST' => array('COperationModes'),
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_c_security_armed_mode'), //'Режим Охраны'
				'TYPE' => 'S'
			),

		);
	}
}