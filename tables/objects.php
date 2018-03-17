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
			),

			array(
				'NAME' => 'user_admin',
				'CLASS_NAME' => 'CUsers',
				'NOTE' => 'Admin',
				'SYSTEM' => false
			),

			array(
				'NAME' => 'modeCinema',
				'CLASS_NAME' => 'CCinemaMode',
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_mode_cinema'), //'Режим просмотра кино'
				'SYSTEM' => true
			),
			array(
				'NAME' => 'modeDarkness',
				'CLASS_NAME' => 'CDarknessMode',
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_mode_darkness'), //'Режим Темное время суток'
				'SYSTEM' => true
			),
			array(
				'NAME' => 'modeEco',
				'CLASS_NAME' => 'CEcoMode',
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_mode_eco'), //'Режим экономии'
				'SYSTEM' => true
			),
			array(
				'NAME' => 'modeGuests',
				'CLASS_NAME' => 'CGuestsMode',
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_mode_guests'), //'Режим Пришли гости'
				'SYSTEM' => true
			),
			array(
				'NAME' => 'modeNight',
				'CLASS_NAME' => 'CNightMode',
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_mode_night'), //'Режим Ночной'
				'SYSTEM' => true
			),
			array(
				'NAME' => 'modeNobodyHome',
				'CLASS_NAME' => 'CNobodyHomeMode',
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_mode_nobody_home'), //'Режим Никого нет дома'
				'SYSTEM' => true
			),
			array(
				'NAME' => 'modeSecurityArmed',
				'CLASS_NAME' => 'CSecurityArmedMode',
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_mode_security_armed'), //'Режим охраны'
				'SYSTEM' => true
			),

			array(
				'NAME' => 'stateSystem',
				'CLASS_NAME' => 'CSystemStates',
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_state_system'), //'Состояние системы'
				'SYSTEM' => true
			),
			array(
				'NAME' => 'stateNetwork',
				'CLASS_NAME' => 'CSystemStates',
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_state_network'), //'Состояние доступа в интернет'
				'SYSTEM' => true
			),
		);
	}
}