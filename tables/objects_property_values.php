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

class ObjectsPropertyValuesTable extends Lib\DataManager
{
	protected static $updateType = null;

	public static function getTableTitle ()
	{
		return 'Значение свойств объектов';
	}

	protected static function getMap()
	{
		return array(
			new Fields\StringField('NAME',array(
				'primary' => true,
				'title' => 'Полное имя свойства вида объект.свойство'
			)),
			new Fields\StringField('TYPE',array(
				'title' => 'Тип значения свойства'
			)),
			new Fields\TextField('VALUE',array(
				'title' => 'Значение свойства'
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
			array(
				'NAME' => 'System.externalIP',
				'TYPE' => 'STRING',
				'VALUE' => '255.255.255.255'
			),
			array(
				'NAME' => 'System.homeName',
				'TYPE' => 'STRING',
				'VALUE' => 'Кузя'
			),
			array(
				'NAME' => 'System.lastSayMessage',
				'TYPE' => 'STRING',
				'VALUE' => 'Первый запуск'
			),
			array(
				'NAME' => 'System.minAloudLevel',
				'TYPE' => 'INT',
				'VALUE' => 1
			),
			array(
				'NAME' => 'System.networkStatus',
				'TYPE' => 'STRING',
				'VALUE' => 'green'
			),
			array(
				'NAME' => 'System.somebodyHome',
				'TYPE' => 'BOOL',
				'VALUE' => 'Y'
			),
			array(
				'NAME' => 'System.volumeLevel',
				'TYPE' => 'INT',
				'VALUE' => 100
			)
		);
	}

	protected static function OnAfterAdd ($arAdd,$res)
	{
		static::setType($arAdd,$res);
	}

	protected static function setType ($arAddUpdate, $res)
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
	}
}