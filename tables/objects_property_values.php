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

class ObjectsPropertyValuesTable extends Lib\DataManager
{
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
}