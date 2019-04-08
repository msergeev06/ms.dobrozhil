<?php

namespace Ms\Dobrozhil\Tables;

use Ms\Core\Entity\Application;
use Ms\Core\Entity\Db\Fields;
use Ms\Core\Entity\Type\Date;
use Ms\Core\Lib\DataManager;

class EntityObjectsTable extends DataManager
{
	public static function getTableTitle ()
	{
		return 'Объекты сущностей';
	}

	protected static function getMap ()
	{
		$userID = Application::getInstance()->getUser()->getID();

		return array (
			new Fields\StringField('OBJECT_NAME',array (
				'primary' => true,
				'title' => 'Имя объекта'
			)),
			new Fields\StringField('ENTITY_TYPE',array (
				'required' => true,
				'title' => 'Тип сущности'
			)),
			new Fields\StringField('TITLE',array (
				'title' => 'Название сущности'
			)),
			new Fields\IntegerField('CREATED_BY',array (
				'required' => true,
				'default_insert' => $userID,
				'title' => 'ID пользователя, кем создан'
			)),
			new Fields\DateTimeField('CREATED_DATE',array (
				'required' => true,
				'default_insert' => new Date(),
				'title' => 'Дата/время создания объекта'
			)),
			new Fields\IntegerField('UPDATED_BY',array (
				'required' => true,
				'default_insert' => $userID,
				'default_update' => $userID,
				'title' => 'ID пользователя, обновившего объект'
			)),
			new Fields\DateTimeField('UPDATED_DATE',array (
				'required' => true,
				'default_insert' => new Date(),
				'default_update' => new Date(),
				'title' => 'Дата/время обновления объекта'
			))
		);
	}

}