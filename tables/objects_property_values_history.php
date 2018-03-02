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

class ObjectsPropertyValuesHistory extends Lib\DataManager
{
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
			new Fields\TextField('VALUE',array(
				'title' => 'Значение свойства'
			)),
			new Fields\DateTimeField('DATETIME',array(
				'required' => true,
				'default_insert' => new Date(),
			))
		);
	}
}