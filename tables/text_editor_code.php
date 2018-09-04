<?php

namespace Ms\Dobrozhil\Tables;

use Ms\Core\Entity\Db\Fields;
use Ms\Core\Lib\DataManager;
use Ms\Core\Lib\Loc;

Loc::includeLocFile(__FILE__);

class TextEditorCodeTable extends DataManager
{
	public static function getTableTitle ()
	{
		return Loc::getModuleMessage('ms.dobrozhil','table_title');
	}

	protected static function getMap ()
	{
		return array (
			new Fields\StringField('NAME',array (
				'primary' => true,
				'title' => Loc::getModuleMessage('ms.dobrozhil','script_name')
			)),
			new Fields\TextField('CODE',array (
				'title' => Loc::getModuleMessage('ms.dobrozhil','script_code')
			))
		);
	}

	public static function getValues ()
	{
		return array(
			array(
				'NAME' => 'CSystem.setMaxVolume',
				'CODE' => '$this->volumeLevel = 100;'
			),
			array(
				'NAME' => 'CSystem.setMute',
				'CODE' => '$this->volumeLevel = 0;'
			),
			array(
				'NAME' => 'COperationModes.activateMode',
				'CODE' => '$this->isActive = true;'
			),
			array(
				'NAME' => 'COperationModes.deactivateMode',
				'CODE' => '$this->isActive = false;'
			),
			array(
				'NAME' => 'COperationModes.onChange_isActive'
			),
			array(
				'NAME' => 'CSystemStates.setGreen',
				'CODE' => 'if ($this->state != "green")'."\n{\n"
					.'   $this->state = "green";'."\n}\n"
			),
			array(
				'NAME' => 'CSystemStates.setYellow',
				'CODE' => 'if ($this->state != "yellow")'."\n{\n"
					.'   $this->state = "yellow";'."\n}\n"
			),
			array(
				'NAME' => 'CSystemStates.setRed',
				'CODE' => 'if($this->state != "red")'."\n{\n"
					.'   $this->state = "red";'."\n}\n"
			),
			array(
				'NAME' => 'CSystemStates.onChange_state',
				'CODE' => 'switch ($this->state)'."\n{\n"
					.'   case "green":'."\n"
					.'      say($this->textSayGreen, $this->sayLevelGreen,"SystemStates");'."\n"
					.'      break;'."\n"
					.'   case "green":'."\n"
					.'      say($this->textSayYellow, $this->sayLevelYellow,"SystemStates");'."\n"
					.'      break;'."\n"
					.'   case "green":'."\n"
					.'      say($this->textSayRed, $this->sayLevelRed,"SystemStates");'."\n"
					.'      break;'."\n}\n"
			)
		);
	}


}