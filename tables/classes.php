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

use Ms\Core\Entity\Application;
use Ms\Core\Lib;
use Ms\Core\Entity\Db\Fields;
use Ms\Core\Entity\Type\Date;
use Ms\Core\Lib\Loc;
use Ms\Core\Tables\SectionsTable;
use Ms\Core\Tables\UsersTable;
use Ms\Dobrozhil\Lib\Classes;

Loc::includeLocFile(__FILE__);

class ClassesTable extends SectionsTable
{
	public static function getTableTitle()
	{
		return Loc::getModuleMessage('ms.dobrozhil','table_title'); //'Классы'
	}

	public static function getTableLinks ()
	{
		return [
			'NAME' => [
				static::getTableName()                  => 'PARENT_CLASS',
				ObjectsTable::getTableName()            => 'CLASS_NAME',
				ClassPropertiesTable::getTableName()    => 'CLASS_NAME',
				ClassMethodsTable::getTableName()       => 'CLASS_NAME'
			]
		];
	}

	protected static function getMap ()
	{
		$userID = Application::getInstance()->getUser()->getID();

		$arFields = parent::getMap();

		$arClassFields = array(
			new Fields\StringField(
				'CLASS_NAME',
				[
					'primary' => true,
					'title' => Loc::getModuleMessage('ms.dobrozhil','class_name') //'Имя класса'
				]
			),
			new Fields\StringField(
				'TITLE',
				[
					'unique' => true,
					'title' => 'Название класса на языке системы'
				]
			),
			Lib\TableHelper::sortField(),
			new Fields\TextField(
				'NOTE',
				[
					'title' => Loc::getModuleMessage('ms.dobrozhil','class_note') //'Краткое описание класса'
				]
			),
			new Fields\StringField(
				'PARENT_CLASS',
				[
					'title' => Loc::getModuleMessage('ms.dobrozhil','class_parent_class') //'Родительский класс'
				],
				static::getTableName().'.CLASS_NAME',
				'cascade',
				'set_null'
			),
			new Fields\StringField(
				'MODULE',
				[
					'title' => Loc::getModuleMessage('ms.dobrozhil','class_module') //'Модуль, который добавил класс'
				]
			),
			new Fields\StringField(
				'NAMESPACE',
				[
					'title' => Loc::getModuleMessage('ms.dobrozhil','class_namespace') //'Путь для создания объекта класса'
				]
			),
			new Fields\StringField(
				'TYPE',
				[
					'required' => true,
					'size' => 1,
					'default_create' => Classes::CLASS_TYPE_USER,
					'default_insert' => Classes::CLASS_TYPE_USER,
					'title' => Loc::getModuleMessage('ms.dobrozhil','class_type') //'Тип класса ("U" - пользовательский, "P" - программный)'
				]
			),
			new Fields\BooleanField(
				'HIDDEN',
				[
					'required' => true,
					'default_create' => false,
					'default_insert' => false,
					'title' => 'Флаг скрытого из общего списка класса'
				]
			),
			new Fields\IntegerField(
				'CREATED_BY',
				[
					'required' => true,
					'default_create' => 0,
					'default_insert' => $userID,
					'title' => 'ID пользователя создавшего класс'
				],
				UsersTable::getTableName().'ID'
			),
			new Fields\DateTimeField(
				'CREATED_DATE',
				[
					'required' => true,
					'default_insert' => new Date(),
					'title' => Loc::getModuleMessage('ms.dobrozhil','class_created') //'Дата создания класса'
				]
			),
			new Fields\IntegerField(
				'UPDATED_BY',
				[
					'required' => true,
					'default_create' => 0,
					'default_insert' => $userID,
					'default_update' => $userID,
					'title' => 'ID пользователя изменившего параметры класса'
				],
				UsersTable::getTableName().'.ID'
			),
			new Fields\DateTimeField(
				'UPDATED_DATE',
				[
					'required' => true,
					'default_insert' => new Date(),
					'default_update' => new Date(),
					'title' => Loc::getModuleMessage('ms.dobrozhil','class_updated') //'Дата последнего редактирования класса'
				]
			)
		);
	}

	public static function getValues ()
	{
		return array(
			array(
				'NAME' => 'CSystem',
				'TITLE' => 'КлассСистемУмныйДом',
				'SORT' => 10,
				//'Основной класс системы'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_c_system'),
				'MODULE' => 'ms.dobrozhil',
				'NAMESPACE' => 'Ms\Dobrozhil\Entity\Objects\System',
				'TYPE' => Classes::CLASS_TYPE_PROGRAM,
				'HIDDEN' => TRUE
			),
			array(
				'NAME' => 'CRooms',
				'TITLE' => 'КлассКомнат',
				'SORT' => 15,
				//'Класс комнат дома'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_c_rooms'),
				'MODULE' => 'ms.dobrozhil',
				'NAMESPACE' => 'Ms\Dobrozhil\Entity\Objects\Rooms',
				'TYPE' => Classes::CLASS_TYPE_PROGRAM,
				'HIDDEN' => TRUE
			),
			array(
				'NAME' => 'CUsers',
				'TITLE' => 'КлассПользователейСистемы',
				'SORT' => 20,
				//'Класс пользователей системы'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_c_users'),
				'MODULE' => 'ms.dobrozhil',
				'NAMESPACE' => 'Ms\Dobrozhil\Entity\Objects\Users',
				'TYPE' => Classes::CLASS_TYPE_PROGRAM,
				'HIDDEN' => TRUE
			),
			array(
				'NAME' => 'CSystemStates',
				'TITLE' => 'КлассСтатусовСистемы',
				'SORT' => 25,
				//'Класс системных статусов'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_c_system_states'),
				'MODULE' => 'ms.dobrozhil',
				'NAMESPACE' => 'Ms\Dobrozhil\Entity\Objects\SystemStates',
				'TYPE' => Classes::CLASS_TYPE_PROGRAM,
				'HIDDEN' => TRUE
			),
			array(
				'NAME' => 'COperationModes',
				'TITLE' => 'КлассРежимовРаботы',
				'SORT' => 30,
				//'Класс режимов работы'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_c_operation_modes'),
				'MODULE' => 'ms.dobrozhil',
				'NAMESPACE' => 'Ms\Dobrozhil\Entity\Objects\OperationModes',
				'TYPE' => Classes::CLASS_TYPE_PROGRAM,
				'HIDDEN' => TRUE
			),
			array(
				'NAME' => 'CDarknessMode',
				'TITLE' => 'КлассРежимСумерки',
				'PARENT_CLASS' => 'COperationModes',
				//'Режим Темное время суток'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_c_darkness_mode'),
				'MODULE' => 'ms.dobrozhil',
				'NAMESPACE' => 'Ms\Dobrozhil\Entity\Objects\DarknessMode',
				'TYPE' => Classes::CLASS_TYPE_PROGRAM,
				'HIDDEN' => TRUE
			),
			array(
				'NAME' => 'CCinemaMode',
				'TITLE' => 'КлассРежимКино',
				'PARENT_CLASS' => 'COperationModes',
				//'Режим кино'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_c_cinema_mode'),
				'MODULE' => 'ms.dobrozhil',
				'NAMESPACE' => 'Ms\Dobrozhil\Entity\Objects\CinemaMode',
				'TYPE' => Classes::CLASS_TYPE_PROGRAM,
				'HIDDEN' => TRUE
			),
			array(
				'NAME' => 'CEcoMode',
				'TITLE' => 'КлассРежимЭкономии',
				'PARENT_CLASS' => 'COperationModes',
				//'Режим экономии'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_c_eco_mode'),
				'MODULE' => 'ms.dobrozhil',
				'NAMESPACE' => 'Ms\Dobrozhil\Entity\Objects\EcoMode',
				'TYPE' => Classes::CLASS_TYPE_PROGRAM,
				'HIDDEN' => TRUE
			),
			array(
				'NAME' => 'CGuestsMode',
				'TITLE' => 'КлассРежимГости',
				'PARENT_CLASS' => 'COperationModes',
				//'Режим Пришли гости'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_c_guests_mode'),
				'MODULE' => 'ms.dobrozhil',
				'NAMESPACE' => 'Ms\Dobrozhil\Entity\Objects\GuestsMode',
				'TYPE' => Classes::CLASS_TYPE_PROGRAM,
				'HIDDEN' => TRUE
			),
			array(
				'NAME' => 'CNightMode',
				'TITLE' => 'КлассРежимНочь',
				'PARENT_CLASS' => 'COperationModes',
				//'Режим Ночной'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_c_night_mode'),
				'MODULE' => 'ms.dobrozhil',
				'NAMESPACE' => 'Ms\Dobrozhil\Entity\Objects\NightMode',
				'TYPE' => Classes::CLASS_TYPE_PROGRAM,
				'HIDDEN' => TRUE
			),
			array(
				'NAME' => 'CNobodyHomeMode',
				'TITLE' => 'КлассРежимНикогоНетДома',
				'PARENT_CLASS' => 'COperationModes',
				//'Режим Никого нет дома'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_c_nobody_home_mode'),
				'MODULE' => 'ms.dobrozhil',
				'NAMESPACE' => 'Ms\Dobrozhil\Entity\Objects\NobodyHomeMode',
				'TYPE' => Classes::CLASS_TYPE_PROGRAM,
				'HIDDEN' => TRUE
			),
			array(
				'NAME' => 'CSecurityArmedMode',
				'TITLE' => 'КлассРежимОхраны',
				'PARENT_CLASS' => 'COperationModes',
				//'Режим Охраны'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_c_security_armed_mode'),
				'MODULE' => 'ms.dobrozhil',
				'NAMESPACE' => 'Ms\Dobrozhil\Entity\Objects\SecurityArmedMode',
				'TYPE' => Classes::CLASS_TYPE_PROGRAM,
				'HIDDEN' => TRUE
			)
		);
	}
}