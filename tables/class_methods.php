<?php
/**
 * Описание таблицы методов классов
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

class ClassMethodsTable extends Lib\DataManager
{
	public static function getTableTitle ()
	{
		return 'Методы классов';
	}

	public static function getMap ()
	{
		return array(
			new Fields\StringField('NAME',array(
				'primary' => true,
				'title' => 'Полное имя метода вида класс.метод'
			)),
			new Fields\StringField('METHOD_NAME',array(
				'required' => true,
				'title' => 'Имя метода без имени класса'
			)),
			new Fields\StringField('CLASS_NAME',array(
				'required' => true,
				'link' => ClassesTable::getTableName().'.NAME',
				'title' => 'Имя класса без имени метода'
			)),
			new Fields\StringField('NOTE',array(
				'title' => 'Краткое описание метода'
			)),
			new Fields\StringField('SCRIPT_NAME',array(
				'title' => 'Имя скрипта, вместо кода метода'
			)),
			new Fields\TextField('CODE',array(
				'title' => 'Код метода'
			)),
			new Fields\TextField('LAST_PARAMETERS',array(
				'serialized' => true,
				'title' => 'Массив значений параметров последнего запуска'
			)),
			new Fields\DateTimeField('LAST_RUN',array(
				'title' => 'Время последнего запуска метода'
			)),
			new Fields\DateTimeField('CREATED',array(
				'required' => true,
				'default_insert' => new Date(),
				'title' => 'Время создания метода'
			)),
			new Fields\DateTimeField('UPDATED',array(
				'required' => true,
				'default_insert' => new Date(),
				'default_update' => new Date(),
				'title' => 'Время обновления метода'
			))
		);
	}
}