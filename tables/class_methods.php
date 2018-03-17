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
		return Loc::getModuleMessage('ms.dobrozhil','table_title'); //Методы классов
	}

	public static function getMap ()
	{
		return array(
			new Fields\StringField('NAME',array(
				'primary' => true,
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_name') //'Полное имя метода вида класс.метод'
			)),
			new Fields\StringField('METHOD_NAME',array(
				'required' => true,
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_method_name') //'Имя метода без имени класса'
			)),
			new Fields\StringField('CLASS_NAME',array(
				'required' => true,
				'link' => ClassesTable::getTableName().'.NAME',
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_class_name') //'Имя класса без имени метода'
			)),
			new Fields\StringField('NOTE',array(
				'title' => Loc::getModuleMessage('ms.dobrozhil','filed_note') //'Краткое описание метода'
			)),
			new Fields\StringField('SCRIPT_NAME',array(
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_script_name') //'Имя скрипта, вместо кода метода'
			)),
			new Fields\TextField('CODE',array(
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_code') //'Код метода'
			)),
			new Fields\TextField('LAST_PARAMETERS',array(
				'serialized' => true,
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_last_parameters') //'Массив значений параметров последнего запуска'
			)),
			new Fields\DateTimeField('LAST_RUN',array(
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_last_run') //'Время последнего запуска метода'
			)),
			new Fields\DateTimeField('CREATED',array(
				'required' => true,
				'default_insert' => new Date(),
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_created') //'Время создания метода'
			)),
			new Fields\DateTimeField('UPDATED',array(
				'required' => true,
				'default_insert' => new Date(),
				'default_update' => new Date(),
				'title' => Loc::getModuleMessage('ms.dobrozhil','field_updated') //'Время обновления метода'
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
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_set_max_volume'), //'Устанавливает максимальную громкость'
				'CODE' => '$this->volumeLevel = 100;'
			),
			array(
				'NAME' => 'CSystem.setMute',
				'METHOD_NAME' => 'setMute',
				'CLASS_NAME' => 'CSystem',
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_set_mute'), //'Устанавливает громкость на 0'
				'CODE' => '$this->volumeLevel = 0;'
			),

			array(
				'NAME' => 'COperationModes.activateMode',
				'METHOD_NAME' => 'activateMode',
				'CLASS_NAME' => 'COperationModes',
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_operation_modes_activate_mode'), //'Активирует режим'
				'CODE' => '$this->isActive = true;'
			),
			array(
				'NAME' => 'COperationModes.deactivateMode',
				'METHOD_NAME' => 'deactivateMode',
				'CLASS_NAME' => 'COperationModes',
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_operation_modes_deactivate_mode'), //'Деактивирует режим'
				'CODE' => '$this->isActive = false;'
			),
			array(
				'NAME' => 'COperationModes.onChange_isActive',
				'METHOD_NAME' => 'onChange_isActive',
				'CLASS_NAME' => 'COperationModes',
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_operation_modes_on_change_is_active'), //'Срабатывает при изменении свойства isActive'
				'CODE' => '$this->isActive = false;'
			),

			array(
				'NAME' => 'CSystemStates.setGreen',
				'METHOD_NAME' => 'setGreen',
				'CLASS_NAME' => 'CSystemStates',
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_state_set_state_green'), //'Устанавливает состояние green'
				'CODE' => 'if($this->state != "green") $this->state = "green";'
			),
			array(
				'NAME' => 'CSystemStates.setYellow',
				'METHOD_NAME' => 'setYellow',
				'CLASS_NAME' => 'CSystemStates',
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_state_set_state_yellow'), //'Устанавливает состояние yellow'
				'CODE' => 'if($this->state != "yellow") $this->state = "yellow";'
			),
			array(
				'NAME' => 'CSystemStates.setRed',
				'METHOD_NAME' => 'setRed',
				'CLASS_NAME' => 'CSystemStates',
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_state_set_state_red'), //'Устанавливает состояние red'
				'CODE' => 'if($this->state != "red") $this->state = "red";'
			),
			array(
				'NAME' => 'CSystemStates.onChange_state',
				'METHOD_NAME' => 'onChange_state',
				'CLASS_NAME' => 'CSystemStates',
				'NOTE' => Loc::getModuleMessage('ms.dobrozhil','note_state_on_change_state'), //'Вызывается при изменении состояния'
				'CODE' => 'switch ($this->state)
{
   case "green":
      say($this->textSayGreen, $this->sayLevelGreen,"SystemStates");
      break;
   case "green":
      say($this->textSayYellow, $this->sayLevelYellow,"SystemStates");
      break;
   case "green":
      say($this->textSayRed, $this->sayLevelRed,"SystemStates");
      break;
}
'
			),
		);
	}
}