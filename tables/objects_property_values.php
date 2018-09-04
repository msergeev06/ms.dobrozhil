<?php
/**
 * Описание таблицы значений свойств объектов
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
use Ms\Dobrozhil\Lib\Classes;
use Ms\Dobrozhil\Lib\Objects;
use Ms\Core\Lib\Loc;

Loc::includeLocFile(__FILE__);

class ObjectsPropertyValuesTable extends Lib\DataManager
{
	protected static $updateType = null;

	public static function getTableTitle ()
	{
		//'Значение свойств объектов'
		return Loc::getModuleMessage('ms.dobrozhil','table_title');
	}

	protected static function getMap()
	{
		return array(
			new Fields\StringField('NAME',array(
				'primary' => true,
				//'Полное имя свойства вида объект.свойство'
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_name')
			)),
			new Fields\StringField('TYPE',array(
				//'Тип значения свойства'
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_type')
			)),
			new Fields\TextField('VALUE',array(
				//'Значение свойства'
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_value')
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
			array(
				'NAME' => 'System.externalIP',
				'TYPE' => 'S',
				'VALUE' => '255.255.255.255'
			),
			array(
				'NAME' => 'System.homeName',
				'TYPE' => 'S',
				//'Кузя'
				'VALUE' => Loc::getModuleMessage('ms.dobrozhil','value_home_name')
			),
			array(
				'NAME' => 'System.lastSayMessage',
				'TYPE' => 'S',
				//'Первый запуск'
				'VALUE' => Loc::getModuleMessage('ms.dobrozhil','value_last_say_message')
			),
			array(
				'NAME' => 'System.minAloudLevel',
				'TYPE' => 'N',
				'VALUE' => 1
			),
			array(
				'NAME' => 'System.networkStatus',
				'TYPE' => 'S',
				'VALUE' => 'green'
			),
			array(
				'NAME' => 'System.somebodyHome',
				'TYPE' => 'B',
				'VALUE' => 'Y'
			),
			array(
				'NAME' => 'System.volumeLevel',
				'TYPE' => 'N',
				'VALUE' => 100
			)
		);
	}

	protected static function OnAfterAdd ($arAdd,$res)
	{
//		static::setType($arAdd,$res);
	}

/*	protected static function setType ($arAddUpdate, $res)
	{
		if (!is_null(static::$updateType) || !$res->getResult())
		{
			return;
		}
		static::$updateType = true;

		list($sObjectName,$sPropertyName) = explode('.',$arAddUpdate['NAME']);
		$objectClassName = Objects::getClassByObject($sObjectName);
		if ($objectClassName)
		{
			$objectClassName = $objectClassName['CLASS_NAME'];
			$type = Classes::getClassPropertiesParams($objectClassName,$sPropertyName,'TYPE');
			static::update(
				$arAddUpdate['NAME'],
				array('TYPE'=>strtoupper($type))
			);
		}

		static::$updateType = null;
	}*/
}