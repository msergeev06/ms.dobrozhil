<?php

namespace Ms\Dobrozhil\Tables;

use Ms\Core\Entity\Application;
use Ms\Core\Entity\Db\Fields;
use Ms\Core\Entity\Type\Date;
use Ms\Core\Lib\DataManager;
use Ms\Core\Lib\TableHelper;

class EntityPropertiesValueTable extends DataManager
{
	public static function getTableTitle ()
	{
		return 'Значения свойств объектов сущностей';
	}

	protected static function getMap ()
	{
		$userID = Application::getInstance()->getUser()->getID();

		return array (
			TableHelper::primaryField(),
			new Fields\StringField(
				'OBJECT_NAME',
				array (
					'required' => true,
					'title' => 'Имя объекта сущностей'
				),
				EntityObjectsTable::getTableName().'.OBJECT_NAME',
				'cascade',
				'cascade'
			),
			new Fields\StringField('PROPERTY_NAME',array (
				'required' => true,
				'title' => 'Имя свойства сущности'
			)),
			new Fields\StringField('PROPERTY_TYPE',array (
				'required' => true,
				'title' => 'Тип свойства'
			)),
			new Fields\TextField('VALUE',array (
				'title' => 'Значение свойства'
			)),
			new Fields\IntegerField('UPDATED_BY',array (
				'required' => true,
				'default_insert' => $userID,
				'default_update' => $userID,
				'title' => 'ID пользователя, изменившего значение свойства'
			)),
			new Fields\DateTimeField('UPDATED_DATE',array (
				'required' => true,
				'default_insert' => new Date(),
				'default_update' => new Date(),
				'title' => 'Дата/время обновления свойства'
			))
		);
	}

}