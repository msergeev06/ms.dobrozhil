<?php
/**
 * Описание таблицы свойств классов объектов
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

class ClassPropertiesTable extends Lib\DataManager
{
	public static function getTableTitle()
	{
		return 'Свойства классов';
	}

	protected static function getMap ()
	{
		return array(
			new Fields\StringField('NAME',array(
				'primary' => true,
				'title' => 'Полное имя свойства вида класс.свойство'
			)),
			new Fields\StringField('PROPERTY_NAME',array(
				'required' => true,
				'title' => 'Имя свойства без имени класса'
			)),
			new Fields\StringField('CLASS_NAME',array(
				'required' => true,
				'link' => ClassesTable::getTableName().'.NAME',
				'title' => 'Имя класса без имени свойства'
			)),
			new Fields\StringField('NOTE',array(
				'title' => 'Краткое описание свойства'
			)),
			new Fields\StringField('TYPE',array(
				'title' => 'Тип свойства (к чему будут приводится значения)'
			)),
			new Fields\IntegerField('HISTORY',array(
				'required' => true,
				'default_create' => 0,
				'default_insert' => 0,
				'title' => 'Время хранения истории значений в днях (0 - не хранить историю)'
			)),
			new Fields\BooleanField('SAVE_IDENTICAL_VALUES',array(
				'required' => true,
				'default_create' => false,
				'default_insert' => false,
				'title' => 'Сохранять ли одинаковые значения'
			)),
			new Fields\TextField('LINKED',array(
				'serialized' => true,
				'title' => 'Привязки свойства'
			)),
			new Fields\DateTimeField('CREATED',array(
				'required' => true,
				'default_insert' => new Date(),
				'title' => 'Время создания свойства'
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