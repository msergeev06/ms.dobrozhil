<?php
/**
 * Описание таблицы свойств классов объектов
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
use Ms\Dobrozhil\Lib\Objects;
use Ms\Core\Lib\Loc;

Loc::includeLocFile(__FILE__);

class ClassPropertiesTable extends Lib\DataManager
{
	protected static $updateType = null;

	public static function getTableTitle()
	{
		return Loc::getModuleMessage('ms.dobrozhil','table_title'); //'Свойства классов'
	}

	protected static function getMap ()
	{
		return array(
			new Fields\StringField('NAME',array(
				'primary' => true,
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_name') //'Полное имя свойства вида класс.свойство'
			)),
			new Fields\StringField('PROPERTY_NAME',array(
				'required' => true,
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_property_name') //'Имя свойства без имени класса'
			)),
			new Fields\StringField('CLASS_NAME',array(
				'required' => true,
				'link' => ClassesTable::getTableName().'.NAME',
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_class_name') //'Имя класса без имени свойства'
			)),
			new Fields\StringField('NOTE',array(
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_note') //'Краткое описание свойства'
			)),
			new Fields\StringField('TYPE',array(
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_type') //'Тип свойства (к чему будут приводится значения)'
			)),
			new Fields\IntegerField('HISTORY',array(
				'required' => true,
				'default_create' => 0,
				'default_insert' => 0,
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_history') //'Время хранения истории значений в днях (0 - не хранить историю)'
			)),
			new Fields\BooleanField('SAVE_IDENTICAL_VALUES',array(
				'required' => true,
				'default_create' => false,
				'default_insert' => false,
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_save_identical_values') //'Сохранять ли одинаковые значения'
			)),
			new Fields\TextField('LINKED',array(
				'serialized' => true,
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_linked') //'Привязки свойства'
			)),
			new Fields\DateTimeField('CREATED',array(
				'required' => true,
				'default_insert' => new Date(),
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_created') //'Время создания свойства'
			)),
			new Fields\DateTimeField('UPDATED',array(
				'required' => true,
				'default_insert' => new Date(),
				'default_update' => new Date(),
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_updated') //'Время обновления свойства'
			))
		);
	}

	public static function getValues ()
	{
		return array(
			/* CSystem property */
			array(
				'NAME' => 'CSystem.externalIP',
				'PROPERTY_NAME' => 'externalIP',
				'CLASS_NAME' => 'CSystem',
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_external_ip'), //'Внешний IP-адрес'
				'TYPE' => 'STRING'
			),
			array(
				'NAME' => 'CSystem.homeName',
				'PROPERTY_NAME' => 'homeName',
				'CLASS_NAME' => 'CSystem',
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_home_name'), //'Как зовут Умный дом'
				'TYPE' => 'STRING'
			),
			array(
				'NAME' => 'CSystem.lastSayMessage',
				'PROPERTY_NAME' => 'lastSayMessage',
				'CLASS_NAME' => 'CSystem',
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_last_say_message'), //'Последняя сказанная фраза'
				'TYPE' => 'STRING'
			),
			array(
				'NAME' => 'CSystem.minAloudLevel',
				'PROPERTY_NAME' => 'minAloudLevel',
				'CLASS_NAME' => 'CSystem',
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_min_aloud_level'), //'Минимальный уровень сообщения, для произношения вслух'
				'TYPE' => 'INT'
			),
			array(
				'NAME' => 'CSystem.networkStatus',
				'PROPERTY_NAME' => 'networkStatus',
				'CLASS_NAME' => 'CSystem',
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_network_status'), //'Статус доступа в Интернет'
				'TYPE' => 'STRING'
			),
			array(
				'NAME' => 'CSystem.somebodyHome',
				'PROPERTY_NAME' => 'somebodyHome',
				'CLASS_NAME' => 'CSystem',
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_somebody_home'), //'Есть ли кто-то дома'
				'TYPE' => 'BOOL'
			),
			array(
				'NAME' => 'CSystem.started',
				'PROPERTY_NAME' => 'started',
				'CLASS_NAME' => 'CSystem',
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_started'), //'Время запуска системы'
				'TYPE' => 'DATETIME'
			),
			array(
				'NAME' => 'CSystem.volumeLevel',
				'PROPERTY_NAME' => 'volumeLevel',
				'CLASS_NAME' => 'CSystem',
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_volume_level'), //'Уровень громкости в процентах'
				'TYPE' => 'INT'
			),
			array(
				'NAME' => 'CSystem.sunDayTime',
				'PROPERTY_NAME' => 'sunDayTime',
				'CLASS_NAME' => 'CSystem',
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_sun_day_time'), //'Долгота дня'
				'TYPE' => 'TIME'
			),
			array(
				'NAME' => 'CSystem.sunRiseTime',
				'PROPERTY_NAME' => 'sunRiseTime',
				'CLASS_NAME' => 'CSystem',
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_sun_rise_time'), //'Время восхода солнца'
				'TYPE' => 'TIME'
			),
			array(
				'NAME' => 'CSystem.sunSetTime',
				'PROPERTY_NAME' => 'sunSetTime',
				'CLASS_NAME' => 'CSystem',
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_sun_set_time'), //'Время захода солнца'
				'TYPE' => 'TIME'
			),

			array(
				'NAME' => 'CUsers.name',
				'PROPERTY_NAME' => 'name',
				'CLASS_NAME' => 'CUsers',
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_user_name'), //'Имя пользователя, как он отображается в чате и как его называет УД'
				'TYPE' => 'STRING'
			),
			array(
				'NAME' => 'CUsers.atHome',
				'PROPERTY_NAME' => 'atHome',
				'CLASS_NAME' => 'CUsers',
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_user_at_home'), //'Флаг того, что пользователь находится сейчас дома'
				'TYPE' => 'BOOL'
			),
			array(
				'NAME' => 'CUsers.batteryLevel',
				'PROPERTY_NAME' => 'batteryLevel',
				'CLASS_NAME' => 'CUsers',
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_user_battery_level'), //'Уровень заряда мобильного телефона'
				'TYPE' => 'INT'
			),
			array(
				'NAME' => 'CUsers.isCharging',
				'PROPERTY_NAME' => 'isCharging',
				'CLASS_NAME' => 'CUsers',
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_user_is_charging'), //'Флаг того, что устройство заряжается'
				'TYPE' => 'BOOL'
			),
			array(
				'NAME' => 'CUsers.color',
				'PROPERTY_NAME' => 'color',
				'CLASS_NAME' => 'CUsers',
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_user_color'), //'Цвет пользователя'
				'TYPE' => 'COLOR'
			),
			array(
				'NAME' => 'CUsers.coordinates',
				'PROPERTY_NAME' => 'coordinates',
				'CLASS_NAME' => 'CUsers',
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_user_coordinates'), //'Последние координаты пользователя'
				'TYPE' => 'COORDINATES'
			),
			array(
				'NAME' => 'CUsers.homeDistanceM',
				'PROPERTY_NAME' => 'homeDistanceM',
				'CLASS_NAME' => 'CUsers',
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_user_home_distance_m'), //'Расстояние до дома (в метрах)'
				'TYPE' => 'INT'
			),
			array(
				'NAME' => 'CUsers.homeDistanceKm',
				'PROPERTY_NAME' => 'homeDistanceKm',
				'CLASS_NAME' => 'CUsers',
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_user_home_distance_km'), //'Расстояние до дома (в километрах)'
				'TYPE' => 'INT'
			),
			array(
				'NAME' => 'CUsers.isMoving',
				'PROPERTY_NAME' => 'isMoving',
				'CLASS_NAME' => 'CUsers',
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_user_is_moving'), //'Флаг того, что пользователь движется сейчас'
				'TYPE' => 'INT'
			),
			array(
				'NAME' => 'CUsers.lastLocation',
				'PROPERTY_NAME' => 'lastLocation',
				'CLASS_NAME' => 'CUsers',
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_user_last_location'), //'Последнее известное местонахождение'
				'TYPE' => 'INT'
			),

			array(
				'NAME' => 'CRooms.title',
				'PROPERTY_NAME' => 'title',
				'CLASS_NAME' => 'CRooms',
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_room_title'), //'Название комнаты на языке системы'
				'TYPE' => 'STRING'
			),
			array(
				'NAME' => 'CRooms.titleWhere',
				'PROPERTY_NAME' => 'titleWhere',
				'CLASS_NAME' => 'CRooms',
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_room_title_where'), //'Название комнаты на языке системы (отвечая на вопрос где?)'
				'TYPE' => 'STRING'
			),
			array(
				'NAME' => 'CRooms.latestActivity',
				'PROPERTY_NAME' => 'latestActivity',
				'CLASS_NAME' => 'CRooms',
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_room_latest_activity'), //'Время, когда была замечена последняя активность в комнате'
				'TYPE' => 'DATETIME'
			),
			array(
				'NAME' => 'CRooms.activityTimeOut',
				'PROPERTY_NAME' => 'activityTimeOut',
				'CLASS_NAME' => 'CRooms',
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_room_activity_time_out'), //'Время, через которое считается, что в комнате никого нет (в секундах)'
				'TYPE' => 'INT'
			),
			array(
				'NAME' => 'CRooms.isSomebodyHere',
				'PROPERTY_NAME' => 'isSomebodyHere',
				'CLASS_NAME' => 'CRooms',
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_room_is_somebody_here'), //'Флаг наличия кого-нибудь в комнате'
				'TYPE' => 'INT'
			),

			array(
				'NAME' => 'COperationModes.sayLevel',
				'PROPERTY_NAME' => 'sayLevel',
				'CLASS_NAME' => 'COperationModes',
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_operation_modes_say_level'), //'Уровень важности сообщений о переключении данного режима'
				'TYPE' => 'INT'
			),
			array(
				'NAME' => 'COperationModes.textActiveOff',
				'PROPERTY_NAME' => 'textActiveOff',
				'CLASS_NAME' => 'COperationModes',
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_operation_modes_text_active_off'), //'Текст фразы при выключении режима'
				'TYPE' => 'STRING'
			),
			array(
				'NAME' => 'COperationModes.textActiveOn',
				'PROPERTY_NAME' => 'textActiveOn',
				'CLASS_NAME' => 'COperationModes',
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_operation_modes_text_active_on'), //'Текст фразы при включении режима'
				'TYPE' => 'STRING'
			),
			array(
				'NAME' => 'COperationModes.title',
				'PROPERTY_NAME' => 'title',
				'CLASS_NAME' => 'COperationModes',
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_operation_modes_title'), //'Название режима работы'
				'TYPE' => 'STRING'
			),
			array(
				'NAME' => 'COperationModes.isActive',
				'PROPERTY_NAME' => 'isActive',
				'CLASS_NAME' => 'COperationModes',
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_operation_modes_is_active'), //'Флаг активности данного режима работы'
				'TYPE' => 'BOOL'
			),

			array(
				'NAME' => 'CSystemStates.state',
				'PROPERTY_NAME' => 'state',
				'CLASS_NAME' => 'CSystemStates',
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_system_states_state'), //'Текущее состояние (green - все хорошо, yellow - идет процесс решения проблем, red - критические проблемы, требуется вмешательство админа)'
				'TYPE' => 'STRING'
			),
			array(
				'NAME' => 'CSystemStates.iconGreen',
				'PROPERTY_NAME' => 'iconGreen',
				'CLASS_NAME' => 'CSystemStates',
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_system_states_icon_green'), //'Иконка для состояния green'
				'TYPE' => 'FILE'
			),
			array(
				'NAME' => 'CSystemStates.iconYellow',
				'PROPERTY_NAME' => 'iconYellow',
				'CLASS_NAME' => 'CSystemStates',
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_system_states_icon_yellow'), //'Иконка для состояния yellow'
				'TYPE' => 'FILE'
			),
			array(
				'NAME' => 'CSystemStates.iconRed',
				'PROPERTY_NAME' => 'iconRed',
				'CLASS_NAME' => 'CSystemStates',
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_system_states_icon_red'), //'Иконка для состояния red'
				'TYPE' => 'FILE'
			),
			array(
				'NAME' => 'CSystemStates.sayLevelGreen',
				'PROPERTY_NAME' => 'sayLevelGreen',
				'CLASS_NAME' => 'CSystemStates',
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_system_states_say_level_green'), //'Приоритет сообщения для состояния green'
				'TYPE' => 'INT'
			),
			array(
				'NAME' => 'CSystemStates.sayLevelYellow',
				'PROPERTY_NAME' => 'sayLevelYellow',
				'CLASS_NAME' => 'CSystemStates',
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_system_states_say_level_yellow'), //'Приоритет сообщения для состояния yellow'
				'TYPE' => 'INT'
			),
			array(
				'NAME' => 'CSystemStates.sayLevelRed',
				'PROPERTY_NAME' => 'sayLevelRed',
				'CLASS_NAME' => 'CSystemStates',
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_system_states_say_level_red'), //'Приоритет сообщения для состояния red'
				'TYPE' => 'INT'
			),
			array(
				'NAME' => 'CSystemStates.textSayGreen',
				'PROPERTY_NAME' => 'textSayGreen',
				'CLASS_NAME' => 'CSystemStates',
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_system_states_text_say_green'), //'Текст сообщения для состояния green'
				'TYPE' => 'STRING'
			),
			array(
				'NAME' => 'CSystemStates.textSayYellow',
				'PROPERTY_NAME' => 'textSayYellow',
				'CLASS_NAME' => 'CSystemStates',
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_system_states_text_say_yellow'), //'Текст сообщения для состояния yellow'
				'TYPE' => 'STRING'
			),
			array(
				'NAME' => 'CSystemStates.textSayRed',
				'PROPERTY_NAME' => 'textSayRed',
				'CLASS_NAME' => 'CSystemStates',
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_system_states_text_say_red'), //'Текст сообщения для состояния red'
				'TYPE' => 'STRING'
			)
		);
	}

	protected static function OnAfterAdd ($arAdd,$res)
	{
		static::OnAfterUpdate($arAdd['NAME'],$arAdd,$res);
	}

	protected static function OnAfterUpdate($primary,$arUpdate,$res)
	{
		if (!isset($arUpdate['TYPE']) || !is_null(static::$updateType) || !$res->getResult())
		{
			return;
		}
		static::$updateType = true;
		list ($className,$propertyName) = explode('.',$primary);
		$arObjects = Objects::getObjectsListByClassName($className);
		if (empty($arObjects))
		{
			static::$updateType = null;
			return;
		}
		foreach ($arObjects as $objectName)
		{
			$arRes = ObjectsPropertyValuesTable::getOne(
				array(
					'select' => 'TYPE',
					'filter' => array('NAME'=>$objectName.'.'.$propertyName)
				)
			);
			if (!$arRes || $arRes['TYPE'] == $arUpdate['TYPE'])
			{
				continue;
			}
			ObjectsPropertyValuesTable::update(
				$objectName.'.'.$propertyName,
				array('TYPE'=>strtoupper($arUpdate['TYPE']))
			);
		}

		static::$updateType = null;
	}
}