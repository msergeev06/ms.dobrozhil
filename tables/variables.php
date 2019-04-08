<?php

namespace Ms\Dobrozhil\Tables;

use Ms\Core\Entity\Db\Fields;
use Ms\Core\Entity\Type\Date;
use Ms\Core\Lib\DataManager;
use Ms\Dobrozhil\Lib\Types;

class VariablesTable extends DataManager
{
	public static function getTableTitle ()
	{
		return 'Переменные';
	}

	protected static function getMap ()
	{
		return array (
			new Fields\StringField('NAME',array (
				'primary' => true,
				'title' => 'Уникальное имя переменной'
			)),
			new Fields\StringField('TYPE',array (
				'required' => true,
				'default_create' => 'S',
				'default_insert' => 'S',
				'title' => 'Тип переменной'
			)),
			new Fields\TextField('VALUE',array (
				'title' => 'Значение переменной'
			)),
			new Fields\DateTimeField('CREATED',array (
				'required' => true,
				'default_insert' => new Date()
			)),
			new Fields\DateTimeField('UPDATED',array (
				'required' => true,
				'default_insert' => new Date(),
				'default_update' => new Date()
			))
		);
	}

	public static function getValues ()
	{
		return array(
			array(
				'NAME' => 'messagesLevel',
				'TYPE' => Types::TYPE_N_INT,
				'VALUE' => '100'
			)
		);
	}


}