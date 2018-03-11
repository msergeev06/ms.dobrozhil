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