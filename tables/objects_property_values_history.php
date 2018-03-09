<?php
/**
 * Описание таблицы исторических значений свойств объектов
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
use Ms\Core\Entity\Db\DBResult;

class ObjectsPropertyValuesHistoryTable extends Lib\DataManager
{
	protected static $updateType = null;

	public static function getTableTitle ()
	{
		return 'Исторические значения свойств объектов';
	}

	protected static function getMap ()
	{
		return array(
			Lib\TableHelper::primaryField(),
			new Fields\StringField('NAME',array(
				'required' => true,
				'title' => 'Полное имя свойства вида объект.свойство'
			)),
			new Fields\StringField('TYPE',array(
				'title' => 'Тип значения свойства'
			)),
			new Fields\TextField('VALUE',array(
				'title' => 'Значение свойства'
			)),
			new Fields\DateTimeField('DATETIME',array(
				'required' => true,
				'default_insert' => new Date(),
			))
		);
	}

	protected static function OnAfterInsert ($arAdd,$res)
	{
		static::setType($arAdd,$res);
	}

	/**
	 * @param $arAddUpdate
	 * @param DBResult $res
	 */
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
				$res->getInsertId(),
				array('TYPE'=>strtoupper($type))
			);
		}

		static::$updateType = null;
	}
}