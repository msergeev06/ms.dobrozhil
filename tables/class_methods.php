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

use Ms\Core\Entity\Application;
use Ms\Core\Entity\Type\Date;
use Ms\Core\Lib;
use Ms\Core\Entity\Db\Fields;
use Ms\Core\Lib\Loc;
use Ms\Core\Tables\UsersTable;

Loc::includeLocFile(__FILE__);

class ClassMethodsTable extends Lib\DataManager
{
	public static function getTableTitle ()
	{
		//Методы классов
		return Loc::getModuleMessage('ms.dobrozhil','table_title');
	}

	public static function getInnerCreateSql ()
	{
		return static::addUnique(array ('CLASS_NAME','METHOD_NAME')).",\n\t"
			.static::addUnique(array ('CLASS_NAME','TITLE')).",\n\t"
			.static::addIndexes('METHOD_NAME');
	}

	protected static function getMap ()
	{
		$userID = Application::getInstance()->getUser()->getID();

		return array(
			Lib\TableHelper::primaryField(),
			new Fields\StringField(
				'CLASS_NAME',
				array(
					'required' => true,
					//'Имя класса без имени метода'
					'title' => Loc::getModuleMessage('ms.dobrozhil','field_class_name')
				),
				ClassesTable::getTableName().'.CLASS_NAME',
				'cascade',
				'cascade'
			),
			new Fields\StringField('METHOD_NAME',array(
				'required' => true,
				//'Имя метода без имени класса'
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_method_name')
			)),
			new Fields\StringField('TITLE',array (
				'title' => 'Название метода на языке системы'
			)),
			new Fields\TextField('NOTE',array(
				//'Краткое описание метода'
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_note')
			)),
			new Fields\TextField('LAST_PARAMETERS',array(
				'serialized' => true,
				//'Массив значений параметров последнего запуска'
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_last_parameters')
			)),
			new Fields\DateTimeField('LAST_RUN',array(
				//'Время последнего запуска метода'
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_last_run')
			)),
			new Fields\IntegerField(
				'CREATED_BY',
				[
					'required' => true,
					'default_create' => 0,
					'default_insert' => $userID,
					//'ID пользователя, создавшего метод'
					'title' => 'ID пользователя, создавшего метод'
				],
				UsersTable::getTableName().'.ID'
			),
			new Fields\DateTimeField('CREATED_DATE',array(
				'required' => true,
				'default_insert' => new Date(),
				//'Время создания метода'
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_created')
			)),
			new Fields\IntegerField(
				'UPDATED_BY',
				[
					'required' => true,
					'default_create' => 0,
					'default_insert' => $userID,
					//'ID пользователя обновившего метод'
					'title' => 'ID пользователя обновившего метод'
				],
				UsersTable::getTableName().'.ID'
			),
			new Fields\DateTimeField('UPDATED_DATE',array(
				'required' => true,
				'default_insert' => new Date(),
				//'Время обновления метода'
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_updated')
			))
		);
	}

	public static function getValues ()
	{
		return array(
			array(
				'TITLE' => 'установитьМаксимальнуюГромность',
				'CLASS_NAME' => 'CSystem',
				'METHOD_NAME' => 'setMaxVolume',
				//'Устанавливает максимальную громкость'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_set_max_volume'),
			),
			array(
				'TITLE' => 'выключитьЗвук',
				'CLASS_NAME' => 'CSystem',
				'METHOD_NAME' => 'setMute',
				//'Устанавливает громкость на 0'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_set_mute'),
			),

			array(
				'TITLE' => 'активировать',
				'CLASS_NAME' => 'COperationModes',
				'METHOD_NAME' => 'activateMode',
				//'Активирует режим'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_operation_modes_activate_mode'),
			),
			array(
				'TITLE' => 'деактивировать',
				'CLASS_NAME' => 'COperationModes',
				'METHOD_NAME' => 'deactivateMode',
				//'Деактивирует режим'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_operation_modes_deactivate_mode'),
			),
			array(
				'TITLE' => 'приИзмененииАктивности',
				'CLASS_NAME' => 'COperationModes',
				'METHOD_NAME' => 'onChange_isActive',
				//'Срабатывает при изменении свойства isActive'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_operation_modes_on_change_is_active'),
			),

			array(
				'TITLE' => 'установитьСтатусЗеленый',
				'CLASS_NAME' => 'CSystemStates',
				'METHOD_NAME' => 'setGreen',
				//'Устанавливает состояние green'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_state_set_state_green'),
			),
			array(
				'TITLE' => 'установтьСтатусЖелтый',
				'CLASS_NAME' => 'CSystemStates',
				'METHOD_NAME' => 'setYellow',
				//'Устанавливает состояние yellow'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_state_set_state_yellow'),
			),
			array(
				'TITLE' => 'установитьСтатусКрасный',
				'CLASS_NAME' => 'CSystemStates',
				'METHOD_NAME' => 'setRed',
				//'Устанавливает состояние red'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_state_set_state_red'),
			),
			array(
				'TITLE' => 'приИзмененииСтатуса',
				'CLASS_NAME' => 'CSystemStates',
				'METHOD_NAME' => 'onChange_state',
				//'Вызывается при изменении состояния'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_state_on_change_state'),
			)
		);
	}
}