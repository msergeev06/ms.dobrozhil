<?php

namespace Ms\Dobrozhil\Tables;

use Ms\Core\Entity\Db\Fields;
use Ms\Core\Lib\DataManager;
use Ms\Core\Lib\Loc;
use Ms\Core\Lib\TableHelper;

Loc::includeLocFile(__FILE__);

class TextEditorCodeTable extends DataManager
{
	public static function getTableTitle ()
	{
		return Loc::getModuleMessage('ms.dobrozhil','table_title');
	}

	public static function getInnerCreateSql ()
	{
		return static::addUnique('SCRIPT_NAME');
	}

	protected static function getMap ()
	{
		return array (
			TableHelper::primaryField(),
			new Fields\StringField(
				'SCRIPT_NAME',
				array (
					'required' => true,
					'title' => Loc::getModuleMessage('ms.dobrozhil','script_name')
				),
				ScriptsTable::getTableName().'.NAME',
				'cascade',
				'cascade'
			),
			new Fields\TextField('CODE',array (
				'title' => Loc::getModuleMessage('ms.dobrozhil','script_code')
			))
		);
	}

	public static function getValues ()
	{
		return array(
			array(
				'SCRIPT_NAME' => 'CSystem.setMaxVolume',
				'CODE' => '$this->volumeLevel = 100;'
			),
			array(
				'SCRIPT_NAME' => 'CSystem.setMute',
				'CODE' => '$this->volumeLevel = 0;'
			),
			array(
				'SCRIPT_NAME' => 'COperationModes.activateMode',
				'CODE' => '$this->isActive = true;'
			),
			array(
				'SCRIPT_NAME' => 'COperationModes.deactivateMode',
				'CODE' => '$this->isActive = false;'
			),
			array(
				'SCRIPT_NAME' => 'COperationModes.onChange_isActive'
			),
			array(
				'SCRIPT_NAME' => 'CSystemStates.setGreen',
				'CODE' => 'if ($this->state != "green")'."\n{\n"
					.'   $this->state = "green";'."\n}\n"
			),
			array(
				'SCRIPT_NAME' => 'CSystemStates.setYellow',
				'CODE' => 'if ($this->state != "yellow")'."\n{\n"
					.'   $this->state = "yellow";'."\n}\n"
			),
			array(
				'SCRIPT_NAME' => 'CSystemStates.setRed',
				'CODE' => 'if($this->state != "red")'."\n{\n"
					.'   $this->state = "red";'."\n}\n"
			),
			array(
				'SCRIPT_NAME' => 'CSystemStates.onChange_state',
				'CODE' => 'switch ($this->state)'
					."\n{\n"
					.'   case "green":'."\n"
					.'      say($this->textSayGreen, $this->sayLevelGreen,"SystemStates");'."\n"
					.'      break;'."\n"
					.'   case "green":'."\n"
					.'      say($this->textSayYellow, $this->sayLevelYellow,"SystemStates");'."\n"
					.'      break;'."\n"
					.'   case "green":'."\n"
					.'      say($this->textSayRed, $this->sayLevelRed,"SystemStates");'."\n"
					.'      break;'
					."\n}\n"
			)
		);
	}


}