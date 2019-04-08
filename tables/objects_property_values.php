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

use Ms\Core\Entity\Application;
use Ms\Core\Entity\Type\Date;
use Ms\Core\Lib;
use Ms\Core\Entity\Db\Fields;
use Ms\Core\Tables\UsersTable;
use Ms\Dobrozhil\Lib\Classes;
use Ms\Dobrozhil\Lib\Objects;
use Ms\Core\Lib\Loc;
use Ms\Dobrozhil\Lib\Types;

Loc::includeLocFile(__FILE__);

class ObjectsPropertyValuesTable extends Lib\DataManager
{
	protected static $updateType = null;

	public static function getTableTitle ()
	{
		//'Значение свойств объектов'
		return Loc::getModuleMessage('ms.dobrozhil','table_title');
	}

	public static function getInnerCreateSql ()
	{
		return static::addUnique(array ('OBJECT_NAME','PROPERTY_NAME'));
	}

	protected static function getMap()
	{
		$userID = Application::getInstance()->getUser()->getID();

		return array(
			Lib\TableHelper::primaryField(),
			new Fields\StringField(
				'OBJECT_NAME',
				array (
					'required' => true,
					//'Имя объекта'
					'title' => 'Имя объекта'
				),
				ObjectsTable::getTableName().'.NAME',
				'cascade',
				'cascade'
			),
			new Fields\StringField(
				'PROPERTY_NAME',
				array (
					'required' => true,
					'title' => 'Имя свойства'
				),
				ClassPropertiesTable::getTableName().'.PROPERTY_NAME',
				'cascade',
				'cascade'
			),
			new Fields\TextField('VALUE',array(
				//'Значение свойства'
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_value')
			)),
			new Fields\IntegerField(
				'UPDATED_BY',
				[
					'required' => true,
					'default_create' => 0,
					'default_insert' => $userID,
					'default_update' => $userID,
					//'Время обновления свойства'
					'title' => Loc::getModuleMessage('ms.dobrozhil','field_updated')
				],
				UsersTable::getTableName().'ID'
			),
			new Fields\DateTimeField('UPDATED_DATE',array(
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
				'OBJECT_NAME' => 'System',
				'PROPERTY_NAME' => 'externalIP',
				'VALUE' => '255.255.255.255'
			),
			array(
				'OBJECT_NAME' => 'System',
				'PROPERTY_NAME' => 'homeName',
				//'Кузя'
				'VALUE' => Loc::getModuleMessage('ms.dobrozhil','value_home_name')
			),
			array(
				'OBJECT_NAME' => 'System',
				'PROPERTY_NAME' => 'lastSayMessage',
				//'Первый запуск'
				'VALUE' => Loc::getModuleMessage('ms.dobrozhil','value_last_say_message')
			),
			array(
				'OBJECT_NAME' => 'System',
				'PROPERTY_NAME' => 'minAloudLevel',
				'VALUE' => 1
			),
			array(
				'OBJECT_NAME' => 'System',
				'PROPERTY_NAME' => 'networkStatus',
				'VALUE' => 'green'
			),
			array(
				'OBJECT_NAME' => 'System',
				'PROPERTY_NAME' => 'somebodyHome',
				'VALUE' => 'Y'
			),
			array(
				'OBJECT_NAME' => 'System',
				'PROPERTY_NAME' => 'volumeLevel',
				'VALUE' => 100
			)
		);
	}
}