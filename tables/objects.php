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
				//'Имя объекта'
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_name')
			)),
			new Fields\StringField('CLASS_NAME',array(
				'required' => true,
				'link' => ClassesTable::getTableName().'.NAME',
				//'Имя класса объекта'
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_class_name')
			)),
			new Fields\TextField('NOTE',array(
				//'Краткое описание объекта класса'
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_note')
			)),
			new Fields\StringField('ROOM_NAME',array(
				//'Имя комнаты, где расположен объект'
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_room_name')
			)),
			new Fields\DateTimeField('CREATED',array(
				'required' => true,
				'default_insert' => new Date(),
				//'Время создания объекта'
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_created')
			)),
			new Fields\DateTimeField('UPDATED',array(
				'required' => true,
				'default_insert' => new Date(),
				'default_update' => new Date(),
				//'Время обновления объекта'
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_updated')
			)),
			new Fields\BooleanField('SYSTEM',array(
				'required' => true,
				'default_create' => false,
				'default_insert' => false,
				//'Флаг системного объекта'
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_system')
			))
		);
	}

	public static function getValues ()
	{
		return array(
			array(
				'NAME' => 'System',
				'CLASS_NAME' => 'CSystem',
				//'Системный объект'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_system'),
				'SYSTEM' => true
			),

			array(
				'NAME' => 'user_admin',
				'CLASS_NAME' => 'CUsers',
				'NOTE' => 'Admin',
				'SYSTEM' => false
			),

			array(
				'NAME' => 'modeCinema',
				'CLASS_NAME' => 'COperationModes',
				//'Режим просмотра кино'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_mode_cinema'),
				'SYSTEM' => true
			),
			array(
				'NAME' => 'modeDarkness',
				'CLASS_NAME' => 'COperationModes',
				//'Режим Темное время суток'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_mode_darkness'),
				'SYSTEM' => true
			),
			array(
				'NAME' => 'modeEco',
				'CLASS_NAME' => 'COperationModes',
				//'Режим экономии'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_mode_eco'),
				'SYSTEM' => true
			),
			array(
				'NAME' => 'modeGuests',
				'CLASS_NAME' => 'COperationModes',
				//'Режим Пришли гости'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_mode_guests'),
				'SYSTEM' => true
			),
			array(
				'NAME' => 'modeNight',
				'CLASS_NAME' => 'COperationModes',
				//'Режим Ночной'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_mode_night'),
				'SYSTEM' => true
			),
			array(
				'NAME' => 'modeNobodyHome',
				'CLASS_NAME' => 'COperationModes',
				//'Режим Никого нет дома'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_mode_nobody_home'),
				'SYSTEM' => true
			),
			array(
				'NAME' => 'modeSecurityArmed',
				'CLASS_NAME' => 'COperationModes',
				//'Режим охраны'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_mode_security_armed'),
				'SYSTEM' => true
			),

			array(
				'NAME' => 'stateSystem',
				'CLASS_NAME' => 'CSystemStates',
				//'Состояние системы'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_state_system'),
				'SYSTEM' => true
			),
			array(
				'NAME' => 'stateNetwork',
				'CLASS_NAME' => 'CSystemStates',
				//'Состояние доступа в интернет'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_state_network'),
				'SYSTEM' => true
			)
		);
	}
}