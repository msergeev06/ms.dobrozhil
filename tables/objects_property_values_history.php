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

use Ms\Core\Entity\Application;
use Ms\Core\Entity\Type\Date;
use Ms\Core\Lib;
use Ms\Core\Entity\Db\Fields;
use Ms\Core\Lib\Loc;
use Ms\Core\Tables\UsersTable;

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
		$userID = Application::getInstance()->getUser()->getID();

		return array(
			Lib\TableHelper::primaryField(),
			new Fields\StringField(
				'OBJECT_NAME',
				array (
					'required' => true,
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
			new Fields\DateTimeField('DATETIME',array(
				'required' => true,
				'default_insert' => new Date(),
				//'Время установки свойства'
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_datetime')
			)),
			new Fields\IntegerField(
				'CREATED_BY',
				[
					'required' => true,
					'default_create' => 0,
					'default_insert' => $userID,
					'title' => 'Добавлено пользователем'
				],
				UsersTable::getTableName().'.ID'
			),
			new Fields\DateTimeField('CREATED_DATE',[
				'required' => true,
				'default_insert' => new Date(),
				'title' => 'Время добавления'
			])
		);
	}
}