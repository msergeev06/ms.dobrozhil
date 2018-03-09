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

class ClassPropertiesTable extends Lib\DataManager
{
	protected static $updateType = null;

	public static function getTableTitle()
	{
		return 'Свойства классов';
	}

	protected static function getMap ()
	{
		return array(
			new Fields\StringField('NAME',array(
				'primary' => true,
				'title' => 'Полное имя свойства вида класс.свойство'
			)),
			new Fields\StringField('PROPERTY_NAME',array(
				'required' => true,
				'title' => 'Имя свойства без имени класса'
			)),
			new Fields\StringField('CLASS_NAME',array(
				'required' => true,
				'link' => ClassesTable::getTableName().'.NAME',
				'title' => 'Имя класса без имени свойства'
			)),
			new Fields\StringField('NOTE',array(
				'title' => 'Краткое описание свойства'
			)),
			new Fields\StringField('TYPE',array(
				'title' => 'Тип свойства (к чему будут приводится значения)'
			)),
			new Fields\IntegerField('HISTORY',array(
				'required' => true,
				'default_create' => 0,
				'default_insert' => 0,
				'title' => 'Время хранения истории значений в днях (0 - не хранить историю)'
			)),
			new Fields\BooleanField('SAVE_IDENTICAL_VALUES',array(
				'required' => true,
				'default_create' => false,
				'default_insert' => false,
				'title' => 'Сохранять ли одинаковые значения'
			)),
			new Fields\TextField('LINKED',array(
				'serialized' => true,
				'title' => 'Привязки свойства'
			)),
			new Fields\DateTimeField('CREATED',array(
				'required' => true,
				'default_insert' => new Date(),
				'title' => 'Время создания свойства'
			)),
			new Fields\DateTimeField('UPDATED',array(
				'required' => true,
				'default_insert' => new Date(),
				'default_update' => new Date(),
				'title' => 'Время обновления свойства'
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
				'NOTE' => 'Внешний IP-адрес',
				'TYPE' => 'STRING'
			),
			array(
				'NAME' => 'CSystem.homeName',
				'PROPERTY_NAME' => 'homeName',
				'CLASS_NAME' => 'CSystem',
				'NOTE' => 'Как зовут Умный дом',
				'TYPE' => 'STRING'
			),
			array(
				'NAME' => 'CSystem.lastSayMessage',
				'PROPERTY_NAME' => 'lastSayMessage',
				'CLASS_NAME' => 'CSystem',
				'NOTE' => 'Последняя сказанная фраза',
				'TYPE' => 'STRING'
			),
			array(
				'NAME' => 'CSystem.minAloudLevel',
				'PROPERTY_NAME' => 'minAloudLevel',
				'CLASS_NAME' => 'CSystem',
				'NOTE' => 'Минимальный уровень сообщения, для произношения вслух',
				'TYPE' => 'INT'
			),
			array(
				'NAME' => 'CSystem.networkStatus',
				'PROPERTY_NAME' => 'networkStatus',
				'CLASS_NAME' => 'CSystem',
				'NOTE' => 'Статус доступа в Интернет',
				'TYPE' => 'STRING'
			),
			array(
				'NAME' => 'CSystem.somebodyHome',
				'PROPERTY_NAME' => 'somebodyHome',
				'CLASS_NAME' => 'CSystem',
				'NOTE' => 'Есть ли кто-то дома',
				'TYPE' => 'BOOL'
			),
			array(
				'NAME' => 'CSystem.started',
				'PROPERTY_NAME' => 'started',
				'CLASS_NAME' => 'CSystem',
				'NOTE' => 'Время запуска системы',
				'TYPE' => 'DATETIME'
			),
			array(
				'NAME' => 'CSystem.volumeLevel',
				'PROPERTY_NAME' => 'volumeLevel',
				'CLASS_NAME' => 'CSystem',
				'NOTE' => 'Уровень громкости в процентах',
				'TYPE' => 'INT'
			),
			array(
				'NAME' => 'CSystem.sunDayTime',
				'PROPERTY_NAME' => 'sunDayTime',
				'CLASS_NAME' => 'CSystem',
				'NOTE' => 'Долгота дня',
				'TYPE' => 'TIME'
			),
			array(
				'NAME' => 'CSystem.sunRiseTime',
				'PROPERTY_NAME' => 'sunRiseTime',
				'CLASS_NAME' => 'CSystem',
				'NOTE' => 'Время восхода солнца',
				'TYPE' => 'TIME'
			),
			array(
				'NAME' => 'CSystem.sunSetTime',
				'PROPERTY_NAME' => 'sunSetTime',
				'CLASS_NAME' => 'CSystem',
				'NOTE' => 'Время захода солнца',
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