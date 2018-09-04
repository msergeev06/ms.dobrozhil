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
				'title' => Loc::getModuleMessage('ms.dobrozhil','class_name') //'Имя класса'
			)),
			Lib\TableHelper::sortField(),
			new Fields\TextField('NOTE',array(
				'title' => Loc::getModuleMessage('ms.dobrozhil','class_note') //'Краткое описание класса'
			)),
			new Fields\StringField('PARENT_CLASS',array(
				'link' => static::getTableName().'.NAME',
				'title' => Loc::getModuleMessage('ms.dobrozhil','class_parent_class') //'Родительский класс'
			)),
			new Fields\TextField('PARENT_LIST',array(
				'serialized' => true,
				'title' => Loc::getModuleMessage('ms.dobrozhil','class_parent_list') //'Список родителей класса'
			)),
			new Fields\TextField('CHILDREN_LIST',array(
				'serialized' => true,
				'title' => Loc::getModuleMessage('ms.dobrozhil','class_children_list') //'Список потомков класса'
			)),
			new Fields\StringField('MODULE',array(
				'title' => Loc::getModuleMessage('ms.dobrozhil','class_module') //'Модуль, который добавил класс'
			)),
			new Fields\StringField('NAMESPACE',array(
				'title' => Loc::getModuleMessage('ms.dobrozhil','class_namespace') //'Путь для создания объекта класса'
			)),
			new Fields\StringField('TYPE',array(
				'required' => true,
				'size' => 1,
				'default_create' => 'U',
				'default_insert' => 'U',
				'title' => Loc::getModuleMessage('ms.dobrozhil','class_type') //'Тип класса ("U" - пользовательский, "P" - программный, "S" - системный)'
			)),
			new Fields\DateTimeField('CREATED',array(
				'required' => true,
				'default_insert' => new Date(),
				'title' => Loc::getModuleMessage('ms.dobrozhil','class_created') //'Дата создания класса'
			)),
			new Fields\DateTimeField('UPDATED',array(
				'required' => true,
				'default_insert' => new Date(),
				'default_update' => new Date(),
				'title' => Loc::getModuleMessage('ms.dobrozhil','class_updated') //'Дата последнего редактирования класса'
			))
		);
	}

	public static function getValues ()
	{
		return array(
			array(
				'NAME' => 'CSystem',
				'SORT' => 10,
				//'Основной класс системы'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_c_system'),
				'TYPE' => 'S'
			),
			array(
				'NAME' => 'CRooms',
				'SORT' => 15,
				//'Класс комнат дома'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_c_rooms'),
				'MODULE' => 'ms.dobrozhil',
				'NAMESPACE' => 'Ms\Dobrozhil\Entity\Objects\Rooms',
				'TYPE' => 'S'
			),
			array(
				'NAME' => 'CUsers',
				'SORT' => 20,
				//'Класс пользователей системы'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_c_users'),
				'MODULE' => 'ms.dobrozhil',
				'NAMESPACE' => 'Ms\Dobrozhil\Entity\Objects\Users',
				'TYPE' => 'S'
			),
			array(
				'NAME' => 'CSystemStates',
				'SORT' => 25,
				//'Класс системных статусов'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_c_system_states'),
				'MODULE' => 'ms.dobrozhil',
				'NAMESPACE' => 'Ms\Dobrozhil\Entity\Objects\SystemStates',
				'TYPE' => 'S'
			),
			array(
				'NAME' => 'COperationModes',
				'SORT' => 30,
				//'Класс режимов работы'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_c_operation_modes'),
				'MODULE' => 'ms.dobrozhil',
				'NAMESPACE' => 'Ms\Dobrozhil\Entity\Objects\OperationModes',
				'TYPE' => 'S'
			)
/*			array(
				'NAME' => 'CDarknessMode',
				'PARENT_CLASS' => 'COperationModes',
				'PARENT_LIST' => array('COperationModes'),
				//'Режим Темное время суток'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_c_darkness_mode'),
				'TYPE' => 'S'
			),
			array(
				'NAME' => 'CCinemaMode',
				'PARENT_CLASS' => 'COperationModes',
				'PARENT_LIST' => array('COperationModes'),
				//'Режим кино'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_c_cinema_mode'),
				'TYPE' => 'S'
			),
			array(
				'NAME' => 'CEcoMode',
				'PARENT_CLASS' => 'COperationModes',
				'PARENT_LIST' => array('COperationModes'),
				//'Режим экономии'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_c_eco_mode'),
				'TYPE' => 'S'
			),
			array(
				'NAME' => 'CGuestsMode',
				'PARENT_CLASS' => 'COperationModes',
				'PARENT_LIST' => array('COperationModes'),
				//'Режим Пришли гости'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_c_guests_mode'),
				'TYPE' => 'S'
			),
			array(
				'NAME' => 'CNightMode',
				'PARENT_CLASS' => 'COperationModes',
				'PARENT_LIST' => array('COperationModes'),
				//'Режим Ночной'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_c_night_mode'),
				'TYPE' => 'S'
			),
			array(
				'NAME' => 'CNobodyHomeMode',
				'PARENT_CLASS' => 'COperationModes',
				'PARENT_LIST' => array('COperationModes'),
				//'Режим Никого нет дома'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_c_nobody_home_mode'),
				'TYPE' => 'S'
			),
			array(
				'NAME' => 'CSecurityArmedMode',
				'PARENT_CLASS' => 'COperationModes',
				'PARENT_LIST' => array('COperationModes'),
				//'Режим Охраны'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_c_security_armed_mode'),
				'TYPE' => 'S'
			),*/

		);
	}
}