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
use Ms\Core\Lib\Loc;

Loc::includeLocFile(__FILE__);

class ObjectsPropertyValuesHistoryTable extends Lib\DataManager
{
	protected static $updateType = null;

	public static function getTableTitle ()
	{
		return Loc::getModuleMessage('ms.dobrozhil','table_title'); //'Исторические значения свойств объектов'
	}

	protected static function getMap ()
	{
		return array(
			Lib\TableHelper::primaryField(),
			new Fields\StringField('NAME',array(
				'required' => true,
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_name') //'Полное имя свойства вида объект.свойство'
			)),
			new Fields\StringField('TYPE',array(
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_type') //'Тип значения свойства'
			)),
			new Fields\TextField('VALUE',array(
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_value') //'Значение свойства'
			)),
			new Fields\DateTimeField('DATETIME',array(
				'required' => true,
				'default_insert' => new Date(),
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_datetime') //'Время установки свойства'
			))
		);
	}

	protected static function OnAfterAdd ($arAdd,$res)
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