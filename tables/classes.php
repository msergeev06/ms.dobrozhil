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
use Ms\Core\Entity\Db\Fields;
use Ms\Core\Entity\Type\Date;
use Ms\Core\Lib\Loc;
use Ms\Core\Lib\TableHelper;
use Ms\Core\Tables\TreeTable;
use Ms\Core\Tables\UsersTable;
use Ms\Dobrozhil\Lib\Classes;

Loc::includeLocFile(__FILE__);

class ClassesTable extends TreeTable
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

	public static function getInnerCreateSql ()
	{
		return static::addIndexes('TITLE');
	}

	protected static function getMap ()
	{
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
					'title' => Loc::getModuleMessage('ms.dobrozhil','class_title') //'Название класса на языке системы'
				]
			),
//			TableHelper::sortField(),
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
					'title' => Loc::getModuleMessage('ms.dobrozhil','class_hidden') //'Флаг скрытого из общего списка класса'
				]
			),
			new Fields\IntegerField(
				'CREATED_BY',
				[
					'required' => true,
					'default_create' => 0,
					'default_insert' => Application::getInstance()->getUser()->getID(),
					'title' => Loc::getModuleMessage('ms.dobrozhil','class_created_by') //'ID пользователя создавшего класс'
				],
				UsersTable::getTableName().'.ID'
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
					'default_insert' => Application::getInstance()->getUser()->getID(),
					'default_update' => Application::getInstance()->getUser()->getID(),
					'title' => Loc::getModuleMessage('ms.dobrozhil','class_updated_by') //'ID пользователя изменившего параметры класса'
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
			),
			TableHelper::activeField(),
			new Fields\IntegerField('LEFT_MARGIN',[
				'required' => true,
				'default_create' => 1,
				'title' => Loc::getModuleMessage('ms.dobrozhil','class_left_margin') //'Левая граница'
			]),
			new Fields\IntegerField('RIGHT_MARGIN',[
				'required' => true,
				'default_create' => 2,
				'title' => Loc::getModuleMessage('ms.dobrozhil','class_right_margin')  //'Правая граница'
			]),
			new Fields\IntegerField('DEPTH_LEVEL',[
				'required' => true,
				'default_create' => 1,
				'default_insert' => 1,
				'title' => Loc::getModuleMessage('ms.dobrozhil','class_depth_level') //'Уровень вложенности'
			])
		);

		return $arClassFields;
	}

	public static function getValues ()
	{
		return array(
			array(
				'CLASS_NAME' => 'CSystem',
				//'Системы Умный дом'
				'TITLE' => Loc::getModuleMessage('ms.dobrozhil','title_c_system'),
				//'Класс систем Умного дома'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_c_system'),
				'MODULE' => 'ms.dobrozhil',
				'NAMESPACE' => 'Ms\Dobrozhil\Entity\Objects\System',
				'TYPE' => Classes::CLASS_TYPE_PROGRAM,
				'HIDDEN' => TRUE,
				'LEFT_MARGIN' => 55,
				'RIGHT_MARGIN' => 56,
				'DEPTH_LEVEL' => 1
			),
			array(
				'CLASS_NAME' => 'CRooms',
				//'Комнаты'
				'TITLE' => Loc::getModuleMessage('ms.dobrozhil','title_c_rooms'),
				//'Класс комнат дома'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_c_rooms'),
				'MODULE' => 'ms.dobrozhil',
				'NAMESPACE' => 'Ms\Dobrozhil\Entity\Objects\Rooms',
				'TYPE' => Classes::CLASS_TYPE_PROGRAM,
				'HIDDEN' => TRUE,
				'LEFT_MARGIN' => 53,
				'RIGHT_MARGIN' => 54,
				'DEPTH_LEVEL' => 1
			),
			array(
				'CLASS_NAME' => 'CUsers',
				//'Пользователи системы'
				'TITLE' => Loc::getModuleMessage('ms.dobrozhil','title_c_users'),
				//'Класс пользователей системы'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_c_users'),
				'MODULE' => 'ms.dobrozhil',
				'NAMESPACE' => 'Ms\Dobrozhil\Entity\Objects\Users',
				'TYPE' => Classes::CLASS_TYPE_PROGRAM,
				'HIDDEN' => TRUE,
				'LEFT_MARGIN' => 59,
				'RIGHT_MARGIN' => 60,
				'DEPTH_LEVEL' => 1
			),
			array(
				'CLASS_NAME' => 'CSystemStates',
				//'Статусы системы'
				'TITLE' => Loc::getModuleMessage('ms.dobrozhil','title_c_system_states'),
				//'Класс системных статусов'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_c_system_states'),
				'MODULE' => 'ms.dobrozhil',
				'NAMESPACE' => 'Ms\Dobrozhil\Entity\Objects\SystemStates',
				'TYPE' => Classes::CLASS_TYPE_PROGRAM,
				'HIDDEN' => TRUE,
				'LEFT_MARGIN' => 57,
				'RIGHT_MARGIN' => 58,
				'DEPTH_LEVEL' => 1
			),
			array(
				'CLASS_NAME' => 'COperationModes',
				//'Режимы работы'
				'TITLE' => Loc::getModuleMessage('ms.dobrozhil','title_c_operation_modes'),
				//'Класс режимов работы'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_c_operation_modes'),
				'MODULE' => 'ms.dobrozhil',
				'NAMESPACE' => 'Ms\Dobrozhil\Entity\Objects\OperationModes',
				'TYPE' => Classes::CLASS_TYPE_PROGRAM,
				'HIDDEN' => TRUE,
				'LEFT_MARGIN' => 37,
				'RIGHT_MARGIN' => 52,
				'DEPTH_LEVEL' => 1
			),
			array(
				'CLASS_NAME' => 'CDarknessMode',
				//'Режимы работы Сумерки'
				'TITLE' => Loc::getModuleMessage('ms.dobrozhil','title_c_darkness_mode'),
				'PARENT_CLASS' => 'COperationModes',
				//'Класс режимов работы Сумерки'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_c_darkness_mode'),
				'MODULE' => 'ms.dobrozhil',
				'NAMESPACE' => 'Ms\Dobrozhil\Entity\Objects\DarknessMode',
				'TYPE' => Classes::CLASS_TYPE_PROGRAM,
				'HIDDEN' => TRUE,
				'LEFT_MARGIN' => 40,
				'RIGHT_MARGIN' => 41,
				'DEPTH_LEVEL' => 2
			),
			array(
				'CLASS_NAME' => 'CCinemaMode',
				//'Режимы работы Кино'
				'TITLE' => Loc::getModuleMessage('ms.dobrozhil','title_c_cinema_mode'),
				'PARENT_CLASS' => 'COperationModes',
				//'Класс режимов работы Кино'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_c_cinema_mode'),
				'MODULE' => 'ms.dobrozhil',
				'NAMESPACE' => 'Ms\Dobrozhil\Entity\Objects\CinemaMode',
				'TYPE' => Classes::CLASS_TYPE_PROGRAM,
				'HIDDEN' => TRUE,
				'LEFT_MARGIN' => 38,
				'RIGHT_MARGIN' => 39,
				'DEPTH_LEVEL' => 2
			),
			array(
				'CLASS_NAME' => 'CEcoMode',
				//'Режимы работы Экономичный'
				'TITLE' => Loc::getModuleMessage('ms.dobrozhil','title_c_eco_mode'),
				'PARENT_CLASS' => 'COperationModes',
				//'Класс режимов работы Экономичный'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_c_eco_mode'),
				'MODULE' => 'ms.dobrozhil',
				'NAMESPACE' => 'Ms\Dobrozhil\Entity\Objects\EcoMode',
				'TYPE' => Classes::CLASS_TYPE_PROGRAM,
				'HIDDEN' => TRUE,
				'LEFT_MARGIN' => 42,
				'RIGHT_MARGIN' => 43,
				'DEPTH_LEVEL' => 2
			),
			array(
				'CLASS_NAME' => 'CGuestsMode',
				//'Режимы работы Гости'
				'TITLE' => Loc::getModuleMessage('ms.dobrozhil','title_c_guests_mode'),
				'PARENT_CLASS' => 'COperationModes',
				//'Класс режимов работы Гости'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_c_guests_mode'),
				'MODULE' => 'ms.dobrozhil',
				'NAMESPACE' => 'Ms\Dobrozhil\Entity\Objects\GuestsMode',
				'TYPE' => Classes::CLASS_TYPE_PROGRAM,
				'HIDDEN' => TRUE,
				'LEFT_MARGIN' => 44,
				'RIGHT_MARGIN' => 45,
				'DEPTH_LEVEL' => 2
			),
			array(
				'CLASS_NAME' => 'CNightMode',
				//'Режимы работы Ночь'
				'TITLE' => Loc::getModuleMessage('ms.dobrozhil','title_c_night_mode'),
				'PARENT_CLASS' => 'COperationModes',
				//'Класс режимов работы Ночь'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_c_night_mode'),
				'MODULE' => 'ms.dobrozhil',
				'NAMESPACE' => 'Ms\Dobrozhil\Entity\Objects\NightMode',
				'TYPE' => Classes::CLASS_TYPE_PROGRAM,
				'HIDDEN' => TRUE,
				'LEFT_MARGIN' => 46,
				'RIGHT_MARGIN' => 47,
				'DEPTH_LEVEL' => 2
			),
			array(
				'CLASS_NAME' => 'CNobodyHomeMode',
				//'Режимы работы Никого нет дома'
				'TITLE' => Loc::getModuleMessage('ms.dobrozhil','title_c_nobody_home_mode'),
				'PARENT_CLASS' => 'COperationModes',
				//'Класс режимов работы Никого нет дома'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_c_nobody_home_mode'),
				'MODULE' => 'ms.dobrozhil',
				'NAMESPACE' => 'Ms\Dobrozhil\Entity\Objects\NobodyHomeMode',
				'TYPE' => Classes::CLASS_TYPE_PROGRAM,
				'HIDDEN' => TRUE,
				'LEFT_MARGIN' => 48,
				'RIGHT_MARGIN' => 49,
				'DEPTH_LEVEL' => 2
			),
			array(
				'CLASS_NAME' => 'CSecurityArmedMode',
				//'Режимы работы Охрана'
				'TITLE' => Loc::getModuleMessage('ms.dobrozhil','title_c_security_armed_mode'),
				'PARENT_CLASS' => 'COperationModes',
				//'Класс режимов работы Охрана'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_c_security_armed_mode'),
				'MODULE' => 'ms.dobrozhil',
				'NAMESPACE' => 'Ms\Dobrozhil\Entity\Objects\SecurityArmedMode',
				'TYPE' => Classes::CLASS_TYPE_PROGRAM,
				'HIDDEN' => TRUE,
				'LEFT_MARGIN' => 50,
				'RIGHT_MARGIN' => 51,
				'DEPTH_LEVEL' => 2
			),
			array(
				'CLASS_NAME' => 'CDevices',
				//'Устройства'
				'TITLE' => Loc::getModuleMessage('ms.dobrozhil','title_c_devices'),
				//'Класс устройств'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_c_devices'),
				'MODULE' => 'ms.dobrozhil',
				'NAMESPACE' => 'Ms\Dobrozhil\Entity\Objects\Devices',
				'TYPE' => Classes::CLASS_TYPE_PROGRAM,
				'HIDDEN' => TRUE,
				'LEFT_MARGIN' => 1,
				'RIGHT_MARGIN' => 36,
				'DEPTH_LEVEL' => 1
			),
			array(
				'CLASS_NAME' => 'CCompositeDevices',
				//'Составные устройства'
				'TITLE' => Loc::getModuleMessage('ms.dobrozhil','title_c_devices_composite'),
				'PARENT_CLASS' => 'CDevices',
				//'Класс составных устройств'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_c_devices_composite'),
				'MODULE' => 'ms.dobrozhil',
				'NAMESPACE' => 'Ms\Dobrozhil\Entity\Objects\CompositeDevices',
				'TYPE' => Classes::CLASS_TYPE_PROGRAM,
				'HIDDEN' => TRUE,
				'LEFT_MARGIN' => 4,
				'RIGHT_MARGIN' => 5,
				'DEPTH_LEVEL' => 2
			),
			array(
				'CLASS_NAME' => 'CSwitches',
				//'Выключатели'
				'TITLE' => Loc::getModuleMessage('ms.dobrozhil','title_c_devices_switches'),
				'PARENT_CLASS' => 'CDevices',
				//'Класс выключатлей'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_c_devices_switches'),
				'MODULE' => 'ms.dobrozhil',
				'NAMESPACE' => 'Ms\Dobrozhil\Entity\Objects\Switches',
				'TYPE' => Classes::CLASS_TYPE_PROGRAM,
				'HIDDEN' => TRUE,
				'LEFT_MARGIN' => 34,
				'RIGHT_MARGIN' => 35,
				'DEPTH_LEVEL' => 2
			),
			array(
				'CLASS_NAME' => 'CDimmers',
				//'Диммеры'
				'TITLE' => Loc::getModuleMessage('ms.dobrozhil','title_c_devices_dimmers'),
				'PARENT_CLASS' => 'CDevices',
				//'Класс диммеров'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_c_devices_dimmers'),
				'MODULE' => 'ms.dobrozhil',
				'NAMESPACE' => 'Ms\Dobrozhil\Entity\Objects\Dimmers',
				'TYPE' => Classes::CLASS_TYPE_PROGRAM,
				'HIDDEN' => TRUE,
				'LEFT_MARGIN' => 8,
				'RIGHT_MARGIN' => 9,
				'DEPTH_LEVEL' => 2
			),
			array(
				'CLASS_NAME' => 'CButtons',
				//'Кнопки'
				'TITLE' => Loc::getModuleMessage('ms.dobrozhil','title_c_devices_buttons'),
				'PARENT_CLASS' => 'CDevices',
				//'Класс кнопок'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_c_devices_buttons'),
				'MODULE' => 'ms.dobrozhil',
				'NAMESPACE' => 'Ms\Dobrozhil\Entity\Objects\Buttons',
				'TYPE' => Classes::CLASS_TYPE_PROGRAM,
				'HIDDEN' => TRUE,
				'LEFT_MARGIN' => 2,
				'RIGHT_MARGIN' => 3,
				'DEPTH_LEVEL' => 2
			),
			array(
				'CLASS_NAME' => 'CControlledRelays',
				//'Управляемые реле'
				'TITLE' => Loc::getModuleMessage('ms.dobrozhil','title_c_devices_controlled_relays'),
				'PARENT_CLASS' => 'CDevices',
				//'Класс управляемых реле'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_c_devices_controlled_relays'),
				'MODULE' => 'ms.dobrozhil',
				'NAMESPACE' => 'Ms\Dobrozhil\Entity\Objects\ControlledRelays',
				'TYPE' => Classes::CLASS_TYPE_PROGRAM,
				'HIDDEN' => TRUE,
				'LEFT_MARGIN' => 6,
				'RIGHT_MARGIN' => 7,
				'DEPTH_LEVEL' => 2
			),
			array(
				'CLASS_NAME' => 'CSensors',
				//'Датчики'
				'TITLE' => Loc::getModuleMessage('ms.dobrozhil','title_c_devices_sensors'),
				'PARENT_CLASS' => 'CDevices',
				//'Класс датчиков'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_c_devices_sensors'),
				'MODULE' => 'ms.dobrozhil',
				'NAMESPACE' => 'Ms\Dobrozhil\Entity\Objects\Sensors',
				'TYPE' => Classes::CLASS_TYPE_PROGRAM,
				'HIDDEN' => TRUE,
				'LEFT_MARGIN' => 10,
				'RIGHT_MARGIN' => 33,
				'DEPTH_LEVEL' => 2
			),
			array(
				'CLASS_NAME' => 'CAtmosphericPressureSensors',
				//'Датчики атмосферного давления'
				'TITLE' => Loc::getModuleMessage('ms.dobrozhil','title_c_devices_sensors_atmospheric_pressure'),
				'PARENT_CLASS' => 'CSensors',
				//'Класс датчиков атмосферного давления'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_c_devices_sensors_atmospheric_pressure'),
				'MODULE' => 'ms.dobrozhil',
				'NAMESPACE' => 'Ms\Dobrozhil\Entity\Objects\AtmosphericPressureSensors',
				'TYPE' => Classes::CLASS_TYPE_PROGRAM,
				'HIDDEN' => TRUE,
				'LEFT_MARGIN' => 11,
				'RIGHT_MARGIN' => 12,
				'DEPTH_LEVEL' => 3
			),
			array(
				'CLASS_NAME' => 'CHumiditySensors',
				//'Датчики влажности'
				'TITLE' => Loc::getModuleMessage('ms.dobrozhil','title_c_devices_sensors_humidity'),
				'PARENT_CLASS' => 'CSensors',
				//'Класс датчиков влажности'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_c_devices_sensors_humidity'),
				'MODULE' => 'ms.dobrozhil',
				'NAMESPACE' => 'Ms\Dobrozhil\Entity\Objects\HumiditySensors',
				'TYPE' => Classes::CLASS_TYPE_PROGRAM,
				'HIDDEN' => TRUE,
				'LEFT_MARGIN' => 19,
				'RIGHT_MARGIN' => 20,
				'DEPTH_LEVEL' => 3
			),
			array(
				'CLASS_NAME' => 'CFluidPressureSensors',
				//'Датчики давления жидкости'
				'TITLE' => Loc::getModuleMessage('ms.dobrozhil','title_c_devices_sensors_fluid_pressure'),
				'PARENT_CLASS' => 'CSensors',
				//'Класс датчиков давления жидкости'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_c_devices_sensors_fluid_pressure'),
				'MODULE' => 'ms.dobrozhil',
				'NAMESPACE' => 'Ms\Dobrozhil\Entity\Objects\FluidPressureSensors',
				'TYPE' => Classes::CLASS_TYPE_PROGRAM,
				'HIDDEN' => TRUE,
				'LEFT_MARGIN' => 17,
				'RIGHT_MARGIN' => 18,
				'DEPTH_LEVEL' => 3
			),
			array(
				'CLASS_NAME' => 'CMotionSensors',
				//'Датчики движения'
				'TITLE' => Loc::getModuleMessage('ms.dobrozhil','title_c_devices_sensors_motion'),
				'PARENT_CLASS' => 'CSensors',
				//'Класс датчиков движения'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_c_devices_sensors_motion'),
				'MODULE' => 'ms.dobrozhil',
				'NAMESPACE' => 'Ms\Dobrozhil\Entity\Objects\MotionSensors',
				'TYPE' => Classes::CLASS_TYPE_PROGRAM,
				'HIDDEN' => TRUE,
				'LEFT_MARGIN' => 21,
				'RIGHT_MARGIN' => 22,
				'DEPTH_LEVEL' => 3
			),
			array(
				'CLASS_NAME' => 'CRainSensors',
				//'Датчики дождя'
				'TITLE' => Loc::getModuleMessage('ms.dobrozhil','title_c_devices_sensors_rain'),
				'PARENT_CLASS' => 'CSensors',
				//'Класс датчиков дождя'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_c_devices_sensors_rain'),
				'MODULE' => 'ms.dobrozhil',
				'NAMESPACE' => 'Ms\Dobrozhil\Entity\Objects\RainSensors',
				'TYPE' => Classes::CLASS_TYPE_PROGRAM,
				'HIDDEN' => TRUE,
				'LEFT_MARGIN' => 25,
				'RIGHT_MARGIN' => 26,
				'DEPTH_LEVEL' => 3
			),
			array(
				'CLASS_NAME' => 'CSmokeSensors',
				//'Датчики дыма'
				'TITLE' => Loc::getModuleMessage('ms.dobrozhil','title_c_devices_sensors_smoke'),
				'PARENT_CLASS' => 'CSensors',
				//'Класс датчиков дыма'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_c_devices_sensors_smoke'),
				'MODULE' => 'ms.dobrozhil',
				'NAMESPACE' => 'Ms\Dobrozhil\Entity\Objects\SmokeSensors',
				'TYPE' => Classes::CLASS_TYPE_PROGRAM,
				'HIDDEN' => TRUE,
				'LEFT_MARGIN' => 27,
				'RIGHT_MARGIN' => 28,
				'DEPTH_LEVEL' => 3
			),
			array(
				'CLASS_NAME' => 'CTemperatureSensors',
				//'Датчики температуры'
				'TITLE' => Loc::getModuleMessage('ms.dobrozhil','title_c_devices_sensors_temperature'),
				'PARENT_CLASS' => 'CSensors',
				//'Класс датчиков температуры'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_c_devices_sensors_temperature'),
				'MODULE' => 'ms.dobrozhil',
				'NAMESPACE' => 'Ms\Dobrozhil\Entity\Objects\TemperatureSensors',
				'TYPE' => Classes::CLASS_TYPE_PROGRAM,
				'HIDDEN' => TRUE,
				'LEFT_MARGIN' => 29,
				'RIGHT_MARGIN' => 30,
				'DEPTH_LEVEL' => 3
			),
			array(
				'CLASS_NAME' => 'CFlameSensors',
				//'Датчики пламени'
				'TITLE' => Loc::getModuleMessage('ms.dobrozhil','title_c_devices_sensors_flame'),
				'PARENT_CLASS' => 'CSensors',
				//'Класс датчиков пламени'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_c_devices_sensors_flame'),
				'MODULE' => 'ms.dobrozhil',
				'NAMESPACE' => 'Ms\Dobrozhil\Entity\Objects\FlameSensors',
				'TYPE' => Classes::CLASS_TYPE_PROGRAM,
				'HIDDEN' => TRUE,
				'LEFT_MARGIN' => 15,
				'RIGHT_MARGIN' => 16,
				'DEPTH_LEVEL' => 3
			),
			array(
				'CLASS_NAME' => 'CRadiationSensors',
				//'Датчики радиационного фона'
				'TITLE' => Loc::getModuleMessage('ms.dobrozhil','title_c_devices_sensors_radiation'),
				'PARENT_CLASS' => 'CSensors',
				//'Класс датчиков радиационного фона'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_c_devices_sensors_radiation'),
				'MODULE' => 'ms.dobrozhil',
				'NAMESPACE' => 'Ms\Dobrozhil\Entity\Objects\BackgroundRadiationSensors',
				'TYPE' => Classes::CLASS_TYPE_PROGRAM,
				'HIDDEN' => TRUE,
				'LEFT_MARGIN' => 23,
				'RIGHT_MARGIN' => 24,
				'DEPTH_LEVEL' => 3
			),
			array(
				'CLASS_NAME' => 'CWaterLevelSensors',
				//'Датчики уровня воды'
				'TITLE' => Loc::getModuleMessage('ms.dobrozhil','title_c_devices_sensors_water_level'),
				'PARENT_CLASS' => 'CSensors',
				//'Класс датчиков уровня воды'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_c_devices_sensors_water_level'),
				'MODULE' => 'ms.dobrozhil',
				'NAMESPACE' => 'Ms\Dobrozhil\Entity\Objects\WaterLevelSensors',
				'TYPE' => Classes::CLASS_TYPE_PROGRAM,
				'HIDDEN' => TRUE,
				'LEFT_MARGIN' => 31,
				'RIGHT_MARGIN' => 32,
				'DEPTH_LEVEL' => 3
			),
			array(
				'CLASS_NAME' => 'CCo2Sensors',
				//'Датчики CO2'
				'TITLE' => Loc::getModuleMessage('ms.dobrozhil','title_c_devices_sensors_co2'),
				'PARENT_CLASS' => 'CSensors',
				//'Класс датчиков co2'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_c_devices_sensors_co2'),
				'MODULE' => 'ms.dobrozhil',
				'NAMESPACE' => 'Ms\Dobrozhil\Entity\Objects\Co2Sensors',
				'TYPE' => Classes::CLASS_TYPE_PROGRAM,
				'HIDDEN' => TRUE,
				'LEFT_MARGIN' => 13,
				'RIGHT_MARGIN' => 14,
				'DEPTH_LEVEL' => 3
			)
		);
	}

	public static function getParentFieldName ()
	{
		return 'PARENT_CLASS';
	}

//TODO: Тестируем прямо отсюда
	final public static function addClass (array $arClass)
	{
		if (!isset($arClass['CLASS_NAME'])||strlen($arClass['CLASS_NAME'])<0)
		{
			return false;
		}
		if (!isset($arClass['TYPE'])||!in_array($arClass['TYPE'],[Classes::CLASS_TYPE_USER,Classes::CLASS_TYPE_PROGRAM]))
		{
			$arClass['TYPE'] = Classes::CLASS_TYPE_USER;
		}
		if (!isset($arClass['CREATED_BY'])||(int)$arClass['CREATED_BY']<=0)
		{
			$arClass['CREATED_BY'] = Application::getInstance()->getUser()->getID();
		}
		if (isset($arClass['CREATED_DATE']))
		{
			unset($arClass['CREATED_DATE']);
		}
		$arClass['CREATED_DATE'] = new Date();
		if (!isset($arClass['UPDATED_BY'])||(int)$arClass['UPDATED_BY']<=0)
		{
			$arClass['UPDATED_BY'] = $arClass['CREATED_BY'];
		}
		if (isset($arClass['UPDATED_DATE']))
		{
			unset($arClass['UPDATED_DATE']);
		}
		$arClass['UPDATED_DATE'] = $arClass['CREATED_DATE'];

		if ($sClassName = static::addNode($arClass))
		{
			static::sortClasses($sClassName);
		}

		return $sClassName;
	}

	final public static function sortClasses ($sClassName)
	{
		return static::sortNode($sClassName, 'CLASS_NAME','ASC');
	}
}