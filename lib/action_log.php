<?php

namespace Ms\Dobrozhil\Lib;

use Ms\Core\Entity\Application;
use Ms\Core\Entity\Type\Date;
use Ms\Core\Lib\Users;

class ActionLog
{
	public static function add2log ($sActionName, $sActionValue=null, $userID = null)
	{
		$date = new Date();
		self::normalizeUserID($userID);
//		$sUserName = static::getUserName($userID);
//		$description = static::getDescriptionText($sActionName, $sActionValue);

		$arAdd = [
			'DATE' => $date,
			'USER_ID' => $userID,
			'ACTION_NAME' => $sActionName,
			'ACTION_VALUE' => $sActionValue
//			'USER_NAME' => $sUserName,
//			'DESCRIPTION' => $description,
		];

		return true;
	}

	public static function getDescriptionText ($sActionName, $sActionValue=null)
	{
		return $sActionName.'. Установлено значение '.(string)$sActionValue.'. ';
	}

	private static function getUserName ($userID=null, $bAddUserID=true)
	{
		self::normalizeUserID($userID);
		if ($userID == 0)
		{
			return ($bAddUserID?'[0] ':'').'Кузя';
		}

		$userName = Users::getName($userID);
		if (!is_null($userName))
		{
			return ($bAddUserID?'['.$userID.'] ':'').$userName;
		}

		$userName = Users::getLogin($userID);
		if (!is_null($userName))
		{
			return ($bAddUserID?'['.$userID.'] ':'').$userName;
		}

		return ($bAddUserID?'['.$userID.']':'');
	}

	private static function normalizeUserID (&$userID=null)
	{
		if (is_null($userID))
		{
			$userID = Application::getInstance()->getUser()->getID();
		}
		else
		{
			$userID = (int)$userID;
		}
	}
}