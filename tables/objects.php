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

use Ms\Core\Entity\Application;
use Ms\Core\Entity\Type\Date;
use Ms\Core\Lib;
use Ms\Core\Entity\Db\Fields;
use Ms\Core\Lib\Loc;
use Ms\Core\Tables\UsersTable;

Loc::includeLocFile(__FILE__);

class ObjectsTable extends Lib\DataManager
{
	public static function getTableTitle ()
	{
		return Loc::getModuleMessage('ms.dobrozhil','table_title'); //'Объекты классов'
	}

	protected static function getMap ()
	{
		$userID = Application::getInstance()->getUser()->getID();

		return array(
			new Fields\StringField('OBJECT_NAME',array(
				'primary' => true,
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_name')
			)),
			new Fields\StringField('TITLE',array (
				'title' => 'Имя объекта на языке системы'
			)),
			new Fields\StringField(
				'CLASS_NAME',
				array(
					'required' => true,
					//'Имя класса объекта'
					'title' => Loc::getModuleMessage('ms.dobrozhil','field_class_name')
				),
				ClassesTable::getTableName().'.CLASS_NAME',
				'cascade',
				'cascade'
			),
			new Fields\TextField('NOTE',array(
				//'Краткое описание объекта класса'
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_note')
			)),
			new Fields\StringField(
				'ROOM_NAME',
				array(
					//'Объект комнаты, где расположен объект'
					'title' => Loc::getModuleMessage('ms.dobrozhil','field_room_name')
				),
				static::getTableName().'.NAME',
				'cascade',
				'set_null'
			),
			new Fields\IntegerField(
				'CREATED_BY',
				[
					'required' => true,
					'default_create' => 0,
					'default_insert' => $userID,
					//'ID пользователя, создавшего объект'
					'title' => 'ID пользователя, создавшего объект'
				],
				UsersTable::getTableName().'.ID'
			),
			new Fields\DateTimeField('CREATED_DATE',array(
				'required' => true,
				'default_insert' => new Date(),
				//'Время создания объекта'
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_created')
			)),
			new Fields\IntegerField(
				'UPDATED_BY',
				[
					'required' => true,
					'default_create' => 0,
					'default_insert' => $userID,
					'default_update' => $userID,
					//'ID пользователя, обновившего объект'
					'title' => 'ID пользователя, обновившего объект'
				],
				UsersTable::getTableName().'.ID'
			),
			new Fields\DateTimeField('UPDATED_DATE',array(
				'required' => true,
				'default_insert' => new Date(),
				'default_update' => new Date(),
				//'Время обновления объекта'
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_updated')
			))
		);
	}

	public static function getValues ()
	{
		return array(
			array(
				'OBJECT_NAME' => 'System',
				'CLASS_NAME' => 'CSystem',
				//'Системный объект'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_system'),
				'TITLE' => 'Система'
			),

			array(
				'OBJECT_NAME' => 'user_admin',
				'CLASS_NAME' => 'CUsers',
				'NOTE' => 'Admin',
				'TITLE' => 'Админ'
			),

			array(
				'OBJECT_NAME' => 'modeCinema',
				'CLASS_NAME' => 'CCinemaMode',
				//'Режим просмотра кино'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_mode_cinema'),
				'TITLE' => 'РежимКино'
			),
			array(
				'OBJECT_NAME' => 'modeDarkness',
				'CLASS_NAME' => 'CDarknessMode',
				//'Режим Темное время суток'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_mode_darkness'),
				'TITLE' => 'РежимСумерки'
			),
			array(
				'OBJECT_NAME' => 'modeEco',
				'CLASS_NAME' => 'CEcoMode',
				//'Режим экономии'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_mode_eco'),
				'TITLE' => 'РежимЭкономии'
			),
			array(
				'OBJECT_NAME' => 'modeGuests',
				'CLASS_NAME' => 'CGuestsMode',
				//'Режим Пришли гости'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_mode_guests'),
				'TITLE' => 'РежимГости'
			),
			array(
				'OBJECT_NAME' => 'modeNight',
				'CLASS_NAME' => 'CNightMode',
				//'Режим Ночной'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_mode_night'),
				'TITLE' => 'РежимНочь'
			),
			array(
				'OBJECT_NAME' => 'modeNobodyHome',
				'CLASS_NAME' => 'CNobodyHomeMode',
				//'Режим Никого нет дома'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_mode_nobody_home'),
				'TITLE' => 'РежимНикогоНетДома'
			),
			array(
				'OBJECT_NAME' => 'modeSecurityArmed',
				'CLASS_NAME' => 'COperationModes',
				//'Режим охраны'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_mode_security_armed'),
				'TITLE' => 'РежимОхраны'
			),
			array(
				'OBJECT_NAME' => 'stateSystem',
				'CLASS_NAME' => 'CSystemStates',
				//'Состояние системы'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_state_system'),
				'TITLE' => 'СостояниеСистемы'
			),
			array(
				'OBJECT_NAME' => 'stateNetwork',
				'CLASS_NAME' => 'CSystemStates',
				//'Состояние доступа в интернет'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_state_network'),
				'TITLE' => 'СостояниеДоступаВИнтернет'
			)
		);
	}
}