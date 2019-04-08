<?php

namespace Ms\Dobrozhil\Tables;

use Ms\Core\Entity\Application;
use Ms\Core\Entity\Db\Fields;
use Ms\Core\Entity\Type\Date;
use Ms\Core\Entity\User;
use Ms\Core\Lib\DataManager;
use Ms\Core\Lib\TableHelper;
use Ms\Core\Lib\Users;
use Ms\Dobrozhil\Lib\Types;

class EntityPropertiesTable extends DataManager
{
	public static function getTableTitle ()
	{
		return 'Свойства сущностей';
	}

	public static function getInnerCreateSql ()
	{
		return static::addUnique(array ('ENTITY_TYPE','PROPERTY_NAME'))
			.",\n\t".static::addIndexes('PROPERTY_NAME');
	}

	protected static function getMap ()
	{
		$userID = Application::getInstance()->getUser()->getID();

		return array(
			TableHelper::primaryField(),
			new Fields\StringField('ENTITY_TYPE',array (
				'required' => true,
				'title' => 'Тип сущности'
			)),
			new Fields\StringField('PROPERTY_NAME',array (
				'required' => true,
				'title' => 'Имя свойства'
			)),
			new Fields\StringField('PROPERTY_TYPE',array (
				'required' => true,
				'default_create' => Types::BASE_TYPE_STRING,
				'default_insert' => Types::BASE_TYPE_STRING,
				'title' => 'Тип значения свойства'
			)),
			new Fields\IntegerField('CREATED_BY',array (
				'required' => true,
				'default_insert' => $userID,
				'title' => 'Создатель свойства'
			)),
			new Fields\DateTimeField('CREATED_DATE',array (
				'required' => true,
				'default_insert' => new Date(),
				'title' => 'Дата добавления свойства'
			)),
			new Fields\IntegerField('UPDATED_BY',array (
				'required' => true,
				'default_insert' => $userID,
				'default_update' => $userID,
				'title' => 'ID пользователя, обновившего свойство'
			)),
			new Fields\DateTimeField('UPDATED_DATE',array (
				'required' => true,
				'default_insert' => new Date(),
				'default_update' => new Date(),
				'title' => 'Дата обновления значения свойства'
			))
		);
	}

}