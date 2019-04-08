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

use Ms\Core\Entity\Application;
use Ms\Core\Entity\Type\Date;
use Ms\Core\Lib;
use Ms\Core\Entity\Db\Fields;
use Ms\Core\Tables\UsersTable;
use Ms\Dobrozhil\Lib\Objects;
use Ms\Core\Lib\Loc;
use Ms\Dobrozhil\Lib\Types;

Loc::includeLocFile(__FILE__);

class ClassPropertiesTable extends Lib\DataManager
{
	protected static $updateType = null;

	public static function getTableTitle()
	{
		//'Свойства классов'
		return Loc::getModuleMessage('ms.dobrozhil','table_title');
	}

	public static function getInnerCreateSql ()
	{
		return static::addUnique(array ('CLASS_NAME','PROPERTY_NAME'))
			.",\n\t".static::addUnique(array ('CLASS_NAME','TITLE'))
			.",\n\t".static::addIndexes('PROPERTY_NAME');
	}

	protected static function getMap ()
	{
		$userID = Application::getInstance()->getUser()->getID();

		return array(
			Lib\TableHelper::primaryField(),
			new Fields\StringField(
				'CLASS_NAME',
				array(
					'required' => true,
					//'Имя класса без имени свойства'
					'title' => Loc::getModuleMessage('ms.dobrozhil','field_class_name')
				),
				ClassesTable::getTableName().'.CLASS_NAME',
				'cascade',
				'cascade'
			),
			new Fields\StringField('PROPERTY_NAME',array(
				'required' => true,
				//'Имя свойства без имени класса'
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_property_name')
			)),
			new Fields\StringField('TITLE',array (
				'title' => 'Название свойства на языке системы'
			)),
			new Fields\TextField('NOTE',array(
				//'Краткое описание свойства'
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_note')
			)),
			new Fields\StringField('TYPE',array(
				'required' => true,
				'default_create' => Types::BASE_TYPE_STRING,
				'default_insert' => Types::BASE_TYPE_STRING,
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
			new Fields\IntegerField(
				'CREATED_BY',
				[
					'required' => true,
					'default_create' => 0,
					'default_insert' => $userID,
					//'ID пользователя, создавшего свойство'
					'title' => 'ID пользователя, создавшего свойство'
				],
				UsersTable::getTableName().'ID'
			),
			new Fields\DateTimeField('CREATED_DATE',array(
				'required' => true,
				'default_insert' => new Date(),
				//'Время создания свойства'
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_created')
			)),
			new Fields\IntegerField(
				'UPDATED_BY',
				[
					'required' => true,
					'default_create' => 0,
					'default_insert' => $userID,
					'default_update' => $userID,
					//'ID пользователя, обновившего свойство'
					'title' => 'ID пользователя, обновившего свойство'
				],
				UsersTable::getTableName().'ID'
			),
			new Fields\DateTimeField('UPDATED_DATE',array(
				'required' => true,
				'default_insert' => new Date(),
				'default_update' => new Date(),
				//'Время обновления свойства'
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_updated')
			)),
		);
	}

	public static function getValues ()
	{
		return array(
			//<editor-fold defaultstate="collapse" desc="TODO: Переделать свойства в переменные, либо переименовать">
			/* CSystem property */
			//в переменные
/*			array(
				'CLASS_NAME' => 'CSystem',
				'PROPERTY_NAME' => 'externalIP',
				'TITLE' => 'Внешний IP',
				//'Внешний IP-адрес'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_external_ip'),
				'TYPE' => Types::BASE_TYPE_STRING
			),*/
/*			array(
				'CLASS_NAME' => 'CSystem',
				'PROPERTY_NAME' => 'homeName',
				'TITLE' => 'Имя Умного дома',
				//'Как зовут Умный дом'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_home_name'),
				'TYPE' => Types::BASE_TYPE_STRING
			),*/
			//в переменные
/*			array(
				'CLASS_NAME' => 'CSystem',
				'PROPERTY_NAME' => 'lastSayMessage',
				'TITLE' => 'Последняя фраза',
				//'Последняя сказанная фраза'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_last_say_message'),
				'TYPE' => Types::BASE_TYPE_STRING
			),*/
			//в переменные
/*			array(
				'TITLE' => 'Мин. произносимый ур. сообщений',
				'CLASS_NAME' => 'CSystem',
				'PROPERTY_NAME' => 'minAloudLevel',
				//'Минимальный уровень сообщения, для произношения вслух'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_min_aloud_level'),
				'TYPE' => Types::TYPE_N_INT
			),*/
			//в переменные
/*			array(
				'TITLE' => 'Статус доступа в Интернет',
				'CLASS_NAME' => 'CSystem',
				'PROPERTY_NAME' => 'networkStatus',
				//'Статус доступа в Интернет'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_network_status'),
				'TYPE' => Types::BASE_TYPE_STRING
			),*/
/*			array(
				'TITLE' => 'Есть кто дома?',
				'CLASS_NAME' => 'CSystem',
				'PROPERTY_NAME' => 'somebodyHome',
				//'Есть ли кто-то дома'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_somebody_home'),
				'TYPE' => Types::BASE_TYPE_BOOL
			),*/
/*			array(
				'TITLE' => 'Время старта системы',
//				'NAME' => 'CSystem.started',
				'CLASS_NAME' => 'CSystem',
				'PROPERTY_NAME' => 'started',
				//'Время запуска системы'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_started'),
				'TYPE' => Types::TYPE_S_DATETIME
			),*/
/*			array(
				'TITLE' => 'Уровень громкости',
//				'NAME' => 'CSystem.volumeLevel',
				'CLASS_NAME' => 'CSystem',
				'PROPERTY_NAME' => 'volumeLevel',
				//'Уровень громкости в процентах'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_volume_level'),
				'TYPE' => Types::TYPE_N_INT
			),*/
/*			array(
				'TITLE' => 'Продолжительность дня',
//				'NAME' => 'CSystem.sunDayTime',
				'CLASS_NAME' => 'CSystem',
				'PROPERTY_NAME' => 'sunDayTime',
				//'Долгота дня'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_sun_day_time'),
				'TYPE' => Types::TYPE_S_TIME
			),*/
/*			array(
				'TITLE' => 'Время восхода',
//				'NAME' => 'CSystem.sunRiseTime',
				'CLASS_NAME' => 'CSystem',
				'PROPERTY_NAME' => 'sunRiseTime',
				//'Время восхода солнца'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_sun_rise_time'),
				'TYPE' => Types::TYPE_S_TIME
			),*/
/*			array(
				'TITLE' => 'Время захода',
//				'NAME' => 'CSystem.sunSetTime',
				'CLASS_NAME' => 'CSystem',
				'PROPERTY_NAME' => 'sunSetTime',
				//'Время захода солнца'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_sun_set_time'),
				'TYPE' => Types::TYPE_S_TIME
			),*/
			//</editor-fold>



			/** CUsers property */
			array(
				'CLASS_NAME' => 'CUsers',
				'PROPERTY_NAME' => 'name',
				'TITLE' => 'имя',
				//'Имя пользователя, как он отображается в чате и как его называет УД'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_user_name'),
				'TYPE' => Types::BASE_TYPE_STRING
			),
			array(
				'CLASS_NAME' => 'CUsers',
				'PROPERTY_NAME' => 'atHome',
				'TITLE' => 'домаЛиСейчас',
				//'Флаг того, что пользователь находится сейчас дома'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_user_at_home'),
				'TYPE' => Types::BASE_TYPE_BOOL
			),
			array(
				'CLASS_NAME' => 'CUsers',
				'PROPERTY_NAME' => 'batteryLevel',
				'TITLE' => 'уровеньЗарядаТелефона',
				//'Уровень заряда мобильного телефона'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_user_battery_level'),
				'TYPE' => Types::TYPE_N_INT
			),
			array(
				'CLASS_NAME' => 'CUsers',
				'PROPERTY_NAME' => 'isCharging',
				'TITLE' => 'наЗарядкеЛиТелефон',
				//'Флаг того, что устройство заряжается'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_user_is_charging'),
				'TYPE' => Types::BASE_TYPE_BOOL
			),
			array(
				'CLASS_NAME' => 'CUsers',
				'PROPERTY_NAME' => 'color',
				'TITLE' => 'цвет',
				//'Цвет пользователя'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_user_color'),
				'TYPE' => Types::TYPE_S_COLOR
			),
			array(
				'CLASS_NAME' => 'CUsers',
				'PROPERTY_NAME' => 'coordinates',
				'TITLE' => 'последниеКоординаты',
				//'Последние координаты пользователя'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_user_coordinates'),
				'TYPE' => Types::TYPE_S_COORDINATES
			),
			array(
				'CLASS_NAME' => 'CUsers',
				'PROPERTY_NAME' => 'homeDistanceM',
				'TITLE' => 'расстояниеДоДомаВМетрах',
				//'Расстояние до дома (в метрах)'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_user_home_distance_m'),
				'TYPE' => Types::BASE_TYPE_NUMERIC
			),
			array(
				'CLASS_NAME' => 'CUsers',
				'PROPERTY_NAME' => 'homeDistanceKm',
				'TITLE' => 'расстояниеДоДомаВКилометрах',
				//'Расстояние до дома (в километрах)'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_user_home_distance_km'),
				'TYPE' => Types::BASE_TYPE_NUMERIC
			),
			array(
				'CLASS_NAME' => 'CUsers',
				'PROPERTY_NAME' => 'isMoving',
				'TITLE' => 'вПути',
				//'Флаг того, что пользователь движется сейчас'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_user_is_moving'),
				'TYPE' => Types::BASE_TYPE_BOOL
			),
			array(
				'CLASS_NAME' => 'CUsers',
				'PROPERTY_NAME' => 'lastLocation',
				'TITLE' => 'последнееИзвестноеМестонахождение',
				//'Последнее известное местонахождение'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_user_last_location'),
				'TYPE' => Types::BASE_TYPE_STRING
			),

			/** CRooms property */
			array(
				'CLASS_NAME' => 'CRooms',
				'PROPERTY_NAME' => 'title',
				'TITLE' => 'название',
				//'Название комнаты на языке системы'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_room_title'),
				'TYPE' => Types::BASE_TYPE_STRING
			),
			array(
				'CLASS_NAME' => 'CRooms',
				'PROPERTY_NAME' => 'titleWhere',
				'TITLE' => 'названиеГде',
				//'Название комнаты на языке системы (отвечая на вопрос где?)'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_room_title_where'),
				'TYPE' => Types::BASE_TYPE_STRING
			),
			array(
				'CLASS_NAME' => 'CRooms',
				'PROPERTY_NAME' => 'latestActivity',
				'TITLE' => 'времяПоследнейАктивности',
				//'Время, когда была замечена последняя активность в комнате'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_room_latest_activity'),
				'TYPE' => Types::TYPE_S_DATETIME
			),
			array(
				'CLASS_NAME' => 'CRooms',
				'PROPERTY_NAME' => 'activityTimeOut',
				'TITLE' => 'таймаутАктивности',
				//'Время, через которое считается, что в комнате никого нет (в секундах)'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_room_activity_time_out'),
				'TYPE' => Types::TYPE_N_INT
			),
			array(
				'CLASS_NAME' => 'CRooms',
				'PROPERTY_NAME' => 'isSomebodyHere',
				'TITLE' => 'естьЛиЗдесьСейчасКтото',
				//'Флаг наличия кого-нибудь в комнате'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_room_is_somebody_here'),
				'TYPE' => Types::BASE_TYPE_BOOL
			),

			/** COperationModes property */
			array(
				'CLASS_NAME' => 'COperationModes',
				'PROPERTY_NAME' => 'sayLevel',
				'TITLE' => 'важностьСообщенийПриПереключении',
				//'Уровень важности сообщений о переключении данного режима'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_operation_modes_say_level'),
				'TYPE' => Types::TYPE_N_INT
			),
			array(
				'CLASS_NAME' => 'COperationModes',
				'PROPERTY_NAME' => 'textActiveOff',
				'TITLE' => 'фразаВыключения',
				//'Текст фразы при выключении режима'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_operation_modes_text_active_off'),
				'TYPE' => Types::BASE_TYPE_STRING
			),
			array(
				'CLASS_NAME' => 'COperationModes',
				'PROPERTY_NAME' => 'textActiveOn',
				'TITLE' => 'фразаВключения',
				//'Текст фразы при включении режима'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_operation_modes_text_active_on'),
				'TYPE' => Types::BASE_TYPE_STRING
			),
			array(
				'CLASS_NAME' => 'COperationModes',
				'PROPERTY_NAME' => 'title',
				'TITLE' => 'название',
				//'Название режима работы'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_operation_modes_title'),
				'TYPE' => Types::BASE_TYPE_STRING
			),
			array(
				'CLASS_NAME' => 'COperationModes',
				'PROPERTY_NAME' => 'isActive',
				'TITLE' => 'активенЛиРежим',
				//'Флаг активности данного режима работы'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_operation_modes_is_active'),
				'TYPE' => Types::BASE_TYPE_BOOL
			),

			/** CSystemStates property */
			array(
				'CLASS_NAME' => 'CSystemStates',
				'PROPERTY_NAME' => 'state',
				'TITLE' => 'статус',
				//'Текущее состояние (green - все хорошо, yellow - идет процесс решения проблем, red - критические проблемы, требуется вмешательство админа)'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_system_states_state'),
				'TYPE' => Types::BASE_TYPE_STRING
			),
			array(
				'CLASS_NAME' => 'CSystemStates',
				'PROPERTY_NAME' => 'iconGreen',
				'TITLE' => 'иконкаСтатусаЗеленый',
				//'Иконка для состояния green'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_system_states_icon_green'),
				'TYPE' => Types::TYPE_N_FILE
			),
			array(
				'CLASS_NAME' => 'CSystemStates',
				'PROPERTY_NAME' => 'iconYellow',
				'TITLE' => 'иконкаСтатусаЖелтый',
				//'Иконка для состояния yellow'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_system_states_icon_yellow'),
				'TYPE' => Types::TYPE_N_FILE
			),
			array(
				'CLASS_NAME' => 'CSystemStates',
				'PROPERTY_NAME' => 'iconRed',
				'TITLE' => 'иконкаСтатусаКрасный',
				//'Иконка для состояния red'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_system_states_icon_red'),
				'TYPE' => Types::TYPE_N_FILE
			),
			array(
				'CLASS_NAME' => 'CSystemStates',
				'PROPERTY_NAME' => 'sayLevelGreen',
				'TITLE' => 'уровеньСообщенияСтатусаЗеленый',
				//'Приоритет сообщения для состояния green'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_system_states_say_level_green'),
				'TYPE' => Types::TYPE_N_INT
			),
			array(
				'CLASS_NAME' => 'CSystemStates',
				'PROPERTY_NAME' => 'sayLevelYellow',
				'TITLE' => 'уровеньСообщенияСтатусаЖелтый',
				//'Приоритет сообщения для состояния yellow'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_system_states_say_level_yellow'),
				'TYPE' => Types::TYPE_N_INT
			),
			array(
				'CLASS_NAME' => 'CSystemStates',
				'PROPERTY_NAME' => 'sayLevelRed',
				'TITLE' => 'уровеньСообщенияСтатусаКрасный',
				//'Приоритет сообщения для состояния red'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_system_states_say_level_red'),
				'TYPE' => Types::TYPE_N_INT
			),
			array(
				'CLASS_NAME' => 'CSystemStates',
				'PROPERTY_NAME' => 'textSayGreen',
				'TITLE' => 'текстСтатусаЗеленый',
				//'Текст сообщения для состояния green'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_system_states_text_say_green'),
				'TYPE' => Types::BASE_TYPE_STRING
			),
			array(
				'CLASS_NAME' => 'CSystemStates',
				'PROPERTY_NAME' => 'textSayYellow',
				'TITLE' => 'текстСтатусаЖелтый',
				//'Текст сообщения для состояния yellow'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_system_states_text_say_yellow'),
				'TYPE' => Types::BASE_TYPE_STRING
			),
			array(
				'CLASS_NAME' => 'CSystemStates',
				'PROPERTY_NAME' => 'textSayRed',
				'TITLE' => 'текстСтатусаКрасный',
				//'Текст сообщения для состояния red'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_system_states_text_say_red'),
				'TYPE' => Types::BASE_TYPE_STRING
			)
		);
	}
}