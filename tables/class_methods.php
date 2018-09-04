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
use Ms\Core\Lib\Loc;

Loc::includeLocFile(__FILE__);

class ClassMethodsTable extends Lib\DataManager
{
	public static function getTableTitle ()
	{
		//Методы классов
		return Loc::getModuleMessage('ms.dobrozhil','table_title');
	}

	public static function getMap ()
	{
		return array(
			new Fields\StringField('NAME',array(
				'primary' => true,
				//'Полное имя метода вида класс.метод'
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_name')
			)),
			new Fields\StringField('METHOD_NAME',array(
				'required' => true,
				//'Имя метода без имени класса'
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_method_name')
			)),
			new Fields\StringField('CLASS_NAME',array(
				'required' => true,
				'link' => ClassesTable::getTableName().'.NAME',
				//'Имя класса без имени метода'
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_class_name')
			)),
			new Fields\TextField('NOTE',array(
				//'Краткое описание метода'
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_note')
			)),
/*			new Fields\StringField('SCRIPT_NAME',array(
				'link' => ScriptsTable::getTableName().'.NAME',
				//'Имя скрипта, вместо кода метода'
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_script_name')
			)),
			new Fields\TextField('CODE',array(
				//'Код метода'
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_code')
			)),*/
			new Fields\TextField('LAST_PARAMETERS',array(
				'serialized' => true,
				//'Массив значений параметров последнего запуска'
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_last_parameters')
			)),
			new Fields\DateTimeField('LAST_RUN',array(
				//'Время последнего запуска метода'
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_last_run')
			)),
			new Fields\DateTimeField('CREATED',array(
				'required' => true,
				'default_insert' => new Date(),
				//'Время создания метода'
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_created')
			)),
			new Fields\DateTimeField('UPDATED',array(
				'required' => true,
				'default_insert' => new Date(),
//				'default_update' => new Date(),
				//'Время обновления метода'
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_updated')
			))
		);
	}

	public static function getValues ()
	{
		return array(
			array(
				'NAME' => 'CSystem.setMaxVolume',
				'METHOD_NAME' => 'setMaxVolume',
				'CLASS_NAME' => 'CSystem',
				//'Устанавливает максимальную громкость'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_set_max_volume'),
			),
			array(
				'NAME' => 'CSystem.setMute',
				'METHOD_NAME' => 'setMute',
				'CLASS_NAME' => 'CSystem',
				//'Устанавливает громкость на 0'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_set_mute'),
			),

			array(
				'NAME' => 'COperationModes.activateMode',
				'METHOD_NAME' => 'activateMode',
				'CLASS_NAME' => 'COperationModes',
				//'Активирует режим'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_operation_modes_activate_mode'),
			),
			array(
				'NAME' => 'COperationModes.deactivateMode',
				'METHOD_NAME' => 'deactivateMode',
				'CLASS_NAME' => 'COperationModes',
				//'Деактивирует режим'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_operation_modes_deactivate_mode'),
			),
			array(
				'NAME' => 'COperationModes.onChange_isActive',
				'METHOD_NAME' => 'onChange_isActive',
				'CLASS_NAME' => 'COperationModes',
				//'Срабатывает при изменении свойства isActive'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_operation_modes_on_change_is_active'),
			),

			array(
				'NAME' => 'CSystemStates.setGreen',
				'METHOD_NAME' => 'setGreen',
				'CLASS_NAME' => 'CSystemStates',
				//'Устанавливает состояние green'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_state_set_state_green'),
			),
			array(
				'NAME' => 'CSystemStates.setYellow',
				'METHOD_NAME' => 'setYellow',
				'CLASS_NAME' => 'CSystemStates',
				//'Устанавливает состояние yellow'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_state_set_state_yellow'),
			),
			array(
				'NAME' => 'CSystemStates.setRed',
				'METHOD_NAME' => 'setRed',
				'CLASS_NAME' => 'CSystemStates',
				//'Устанавливает состояние red'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_state_set_state_red'),
			),
			array(
				'NAME' => 'CSystemStates.onChange_state',
				'METHOD_NAME' => 'onChange_state',
				'CLASS_NAME' => 'CSystemStates',
				//'Вызывается при изменении состояния'
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_state_on_change_state'),
			)
		);
	}
}