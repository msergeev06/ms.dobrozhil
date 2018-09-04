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
		//'Свойства классов'
		return Loc::getModuleMessage('ms.dobrozhil','table_title');
	}

	protected static function getMap ()
	{
		return array(
			new Fields\StringField('NAME',array(
				'primary' => true,
				//'Полное имя свойства вида класс.свойство'
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_name')
			)),
			new Fields\StringField('PROPERTY_NAME',array(
				'required' => true,
				//'Имя свойства без имени класса'
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_property_name')
			)),
			new Fields\StringField('CLASS_NAME',array(
				'required' => true,
				'link' => ClassesTable::getTableName().'.NAME',
				//'Имя класса без имени свойства'
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_class_name')
			)),
			new Fields\TextField('NOTE',array(
				//'Краткое описание свойства'
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_note')
			)),
			new Fields\StringField('TYPE',array(
				//'Тип свойства (к чему будут приводится значения)'
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_type')
			)),
			new Fields\IntegerField('HISTORY',array(
				'required' => true,
				'default_create' => 0,
				'default_insert' => 0,
				//'Время хранения истории значений в днях (0 - не хранить историю)'
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_history')
			)),
			new Fields\BooleanField('SAVE_IDENTICAL_VALUES',array(
				'required' => true,
				'default_create' => false,
				'default_insert' => false,
				//'Сохранять ли одинаковые значения'
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_save_identical_values')
			)),
			new Fields\TextField('LINKED',array(
				'serialized' => true,
				//'Привязки свойства'
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_linked')
			)),
			new Fields\DateTimeField('CREATED',array(
				'required' => true,
				'default_insert' => new Date(),
				//'Время создания свойства'
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_created')
			)),
			new Fields\DateTimeField('UPDATED',array(
				'required' => true,
				'default_insert' => new Date(),
				'default_update' => new Date(),
				//'Время обновления свойства'
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_updated')
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
				//'Внешний IP-адрес'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_external_ip'),
				'TYPE' => 'S'
			),
			array(
				'NAME' => 'CSystem.homeName',
				'PROPERTY_NAME' => 'homeName',
				'CLASS_NAME' => 'CSystem',
				//'Как зовут Умный дом'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_home_name'),
				'TYPE' => 'S'
			),
			array(
				'NAME' => 'CSystem.lastSayMessage',
				'PROPERTY_NAME' => 'lastSayMessage',
				'CLASS_NAME' => 'CSystem',
				//'Последняя сказанная фраза'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_last_say_message'),
				'TYPE' => 'S'
			),
			array(
				'NAME' => 'CSystem.minAloudLevel',
				'PROPERTY_NAME' => 'minAloudLevel',
				'CLASS_NAME' => 'CSystem',
				//'Минимальный уровень сообщения, для произношения вслух'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_min_aloud_level'),
				'TYPE' => 'N'
			),
			array(
				'NAME' => 'CSystem.networkStatus',
				'PROPERTY_NAME' => 'networkStatus',
				'CLASS_NAME' => 'CSystem',
				//'Статус доступа в Интернет'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_network_status'),
				'TYPE' => 'S'
			),
			array(
				'NAME' => 'CSystem.somebodyHome',
				'PROPERTY_NAME' => 'somebodyHome',
				'CLASS_NAME' => 'CSystem',
				//'Есть ли кто-то дома'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_somebody_home'),
				'TYPE' => 'B'
			),
			array(
				'NAME' => 'CSystem.started',
				'PROPERTY_NAME' => 'started',
				'CLASS_NAME' => 'CSystem',
				//'Время запуска системы'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_started'),
				'TYPE' => 'S:DATETIME'
			),
			array(
				'NAME' => 'CSystem.volumeLevel',
				'PROPERTY_NAME' => 'volumeLevel',
				'CLASS_NAME' => 'CSystem',
				//'Уровень громкости в процентах'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_volume_level'),
				'TYPE' => 'N'
			),
			array(
				'NAME' => 'CSystem.sunDayTime',
				'PROPERTY_NAME' => 'sunDayTime',
				'CLASS_NAME' => 'CSystem',
				//'Долгота дня'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_sun_day_time'),
				'TYPE' => 'S:TIME'
			),
			array(
				'NAME' => 'CSystem.sunRiseTime',
				'PROPERTY_NAME' => 'sunRiseTime',
				'CLASS_NAME' => 'CSystem',
				//'Время восхода солнца'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_sun_rise_time'),
				'TYPE' => 'S:TIME'
			),
			array(
				'NAME' => 'CSystem.sunSetTime',
				'PROPERTY_NAME' => 'sunSetTime',
				'CLASS_NAME' => 'CSystem',
				//'Время захода солнца'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_sun_set_time'),
				'TYPE' => 'S:TIME'
			),

			array(
				'NAME' => 'CUsers.name',
				'PROPERTY_NAME' => 'name',
				'CLASS_NAME' => 'CUsers',
				//'Имя пользователя, как он отображается в чате и как его называет УД'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_user_name'),
				'TYPE' => 'S'
			),
			array(
				'NAME' => 'CUsers.atHome',
				'PROPERTY_NAME' => 'atHome',
				'CLASS_NAME' => 'CUsers',
				//'Флаг того, что пользователь находится сейчас дома'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_user_at_home'),
				'TYPE' => 'B'
			),
			array(
				'NAME' => 'CUsers.batteryLevel',
				'PROPERTY_NAME' => 'batteryLevel',
				'CLASS_NAME' => 'CUsers',
				//'Уровень заряда мобильного телефона'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_user_battery_level'),
				'TYPE' => 'N'
			),
			array(
				'NAME' => 'CUsers.isCharging',
				'PROPERTY_NAME' => 'isCharging',
				'CLASS_NAME' => 'CUsers',
				//'Флаг того, что устройство заряжается'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_user_is_charging'),
				'TYPE' => 'B'
			),
			array(
				'NAME' => 'CUsers.color',
				'PROPERTY_NAME' => 'color',
				'CLASS_NAME' => 'CUsers',
				//'Цвет пользователя'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_user_color'),
				'TYPE' => 'S:COLOR'
			),
			array(
				'NAME' => 'CUsers.coordinates',
				'PROPERTY_NAME' => 'coordinates',
				'CLASS_NAME' => 'CUsers',
				//'Последние координаты пользователя'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_user_coordinates'),
				'TYPE' => 'S:COORDINATES'
			),
			array(
				'NAME' => 'CUsers.homeDistanceM',
				'PROPERTY_NAME' => 'homeDistanceM',
				'CLASS_NAME' => 'CUsers',
				//'Расстояние до дома (в метрах)'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_user_home_distance_m'),
				'TYPE' => 'N'
			),
			array(
				'NAME' => 'CUsers.homeDistanceKm',
				'PROPERTY_NAME' => 'homeDistanceKm',
				'CLASS_NAME' => 'CUsers',
				//'Расстояние до дома (в километрах)'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_user_home_distance_km'),
				'TYPE' => 'N'
			),
			array(
				'NAME' => 'CUsers.isMoving',
				'PROPERTY_NAME' => 'isMoving',
				'CLASS_NAME' => 'CUsers',
				//'Флаг того, что пользователь движется сейчас'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_user_is_moving'),
				'TYPE' => 'B'
			),
			array(
				'NAME' => 'CUsers.lastLocation',
				'PROPERTY_NAME' => 'lastLocation',
				'CLASS_NAME' => 'CUsers',
				//'Последнее известное местонахождение'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_user_last_location'),
				'TYPE' => 'S'
			),

			array(
				'NAME' => 'CRooms.title',
				'PROPERTY_NAME' => 'title',
				'CLASS_NAME' => 'CRooms',
				//'Название комнаты на языке системы'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_room_title'),
				'TYPE' => 'S'
			),
			array(
				'NAME' => 'CRooms.titleWhere',
				'PROPERTY_NAME' => 'titleWhere',
				'CLASS_NAME' => 'CRooms',
				//'Название комнаты на языке системы (отвечая на вопрос где?)'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_room_title_where'),
				'TYPE' => 'S'
			),
			array(
				'NAME' => 'CRooms.latestActivity',
				'PROPERTY_NAME' => 'latestActivity',
				'CLASS_NAME' => 'CRooms',
				//'Время, когда была замечена последняя активность в комнате'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_room_latest_activity'),
				'TYPE' => 'S:DATETIME'
			),
			array(
				'NAME' => 'CRooms.activityTimeOut',
				'PROPERTY_NAME' => 'activityTimeOut',
				'CLASS_NAME' => 'CRooms',
				//'Время, через которое считается, что в комнате никого нет (в секундах)'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_room_activity_time_out'),
				'TYPE' => 'N'
			),
			array(
				'NAME' => 'CRooms.isSomebodyHere',
				'PROPERTY_NAME' => 'isSomebodyHere',
				'CLASS_NAME' => 'CRooms',
				//'Флаг наличия кого-нибудь в комнате'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_room_is_somebody_here'),
				'TYPE' => 'B'
			),

			array(
				'NAME' => 'COperationModes.sayLevel',
				'PROPERTY_NAME' => 'sayLevel',
				'CLASS_NAME' => 'COperationModes',
				//'Уровень важности сообщений о переключении данного режима'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_operation_modes_say_level'),
				'TYPE' => 'N'
			),
			array(
				'NAME' => 'COperationModes.textActiveOff',
				'PROPERTY_NAME' => 'textActiveOff',
				'CLASS_NAME' => 'COperationModes',
				//'Текст фразы при выключении режима'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_operation_modes_text_active_off'),
				'TYPE' => 'S'
			),
			array(
				'NAME' => 'COperationModes.textActiveOn',
				'PROPERTY_NAME' => 'textActiveOn',
				'CLASS_NAME' => 'COperationModes',
				//'Текст фразы при включении режима'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_operation_modes_text_active_on'),
				'TYPE' => 'S'
			),
			array(
				'NAME' => 'COperationModes.title',
				'PROPERTY_NAME' => 'title',
				'CLASS_NAME' => 'COperationModes',
				//'Название режима работы'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_operation_modes_title'),
				'TYPE' => 'S'
			),
			array(
				'NAME' => 'COperationModes.isActive',
				'PROPERTY_NAME' => 'isActive',
				'CLASS_NAME' => 'COperationModes',
				//'Флаг активности данного режима работы'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_operation_modes_is_active'),
				'TYPE' => 'B'
			),

			array(
				'NAME' => 'CSystemStates.state',
				'PROPERTY_NAME' => 'state',
				'CLASS_NAME' => 'CSystemStates',
				//'Текущее состояние (green - все хорошо, yellow - идет процесс решения проблем, red - критические проблемы, требуется вмешательство админа)'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_system_states_state'),
				'TYPE' => 'S'
			),
			array(
				'NAME' => 'CSystemStates.iconGreen',
				'PROPERTY_NAME' => 'iconGreen',
				'CLASS_NAME' => 'CSystemStates',
				//'Иконка для состояния green'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_system_states_icon_green'),
				'TYPE' => 'N:FILE'
			),
			array(
				'NAME' => 'CSystemStates.iconYellow',
				'PROPERTY_NAME' => 'iconYellow',
				'CLASS_NAME' => 'CSystemStates',
				//'Иконка для состояния yellow'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_system_states_icon_yellow'),
				'TYPE' => 'N:FILE'
			),
			array(
				'NAME' => 'CSystemStates.iconRed',
				'PROPERTY_NAME' => 'iconRed',
				'CLASS_NAME' => 'CSystemStates',
				//'Иконка для состояния red'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_system_states_icon_red'),
				'TYPE' => 'N:FILE'
			),
			array(
				'NAME' => 'CSystemStates.sayLevelGreen',
				'PROPERTY_NAME' => 'sayLevelGreen',
				'CLASS_NAME' => 'CSystemStates',
				//'Приоритет сообщения для состояния green'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_system_states_say_level_green'),
				'TYPE' => 'N'
			),
			array(
				'NAME' => 'CSystemStates.sayLevelYellow',
				'PROPERTY_NAME' => 'sayLevelYellow',
				'CLASS_NAME' => 'CSystemStates',
				//'Приоритет сообщения для состояния yellow'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_system_states_say_level_yellow'),
				'TYPE' => 'N'
			),
			array(
				'NAME' => 'CSystemStates.sayLevelRed',
				'PROPERTY_NAME' => 'sayLevelRed',
				'CLASS_NAME' => 'CSystemStates',
				//'Приоритет сообщения для состояния red'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_system_states_say_level_red'),
				'TYPE' => 'N'
			),
			array(
				'NAME' => 'CSystemStates.textSayGreen',
				'PROPERTY_NAME' => 'textSayGreen',
				'CLASS_NAME' => 'CSystemStates',
				//'Текст сообщения для состояния green'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_system_states_text_say_green'),
				'TYPE' => 'S'
			),
			array(
				'NAME' => 'CSystemStates.textSayYellow',
				'PROPERTY_NAME' => 'textSayYellow',
				'CLASS_NAME' => 'CSystemStates',
				//'Текст сообщения для состояния yellow'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_system_states_text_say_yellow'),
				'TYPE' => 'S'
			),
			array(
				'NAME' => 'CSystemStates.textSayRed',
				'PROPERTY_NAME' => 'textSayRed',
				'CLASS_NAME' => 'CSystemStates',
				//'Текст сообщения для состояния red'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_system_states_text_say_red'),
				'TYPE' => 'S'
			)
		);
	}

	protected static function OnAfterAdd ($arAdd,$res)
	{

	}
}