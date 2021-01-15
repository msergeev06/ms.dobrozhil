<?php

namespace Ms\Dobrozhil\Access;

use Ms\Core\Entity\User\GroupAccess;
use Ms\Core\Lib\Tools;
use Ms\Dobrozhil\General\Multiton;

/**
 * Класс Ms\Dobrozhil\Access\Access
 * Правила доступов пользователей для модулей системы Доброжил
 */
class Access extends Multiton
{
	/**
	 * Уровень доступа "Просматривать свои"
	 */
	const LEVEL_VIEW_OWN = 'RO';

	/**
	 * Уровень доступа "Просмотр"
	 */
	const LEVEL_VIEW = 'RA';

	/**
	 * Уровень доступа "Создавать"
	 */
	const LEVEL_CREATE = 'CR';

	/**
	 * Уровень доступа "Редактировать свои"
	 */
	const LEVEL_EDIT_OWN = 'EO';

	/**
	 * Уровень доступа "Редактирование"
	 */
	const LEVEL_EDIT = 'EA';

	/**
	 * Уровень доступа "Писать в свои"
	 */
	const LEVEL_WRITE_OWN = 'WO';

	/**
	 * Уровень доступа "Писать"
	 */
	const LEVEL_WRITE = 'WA';

	/**
	 * Уровень доступа "Удаление своих"
	 */
	const LEVEL_DELETE_OWN = 'DO';

	/**
	 * Уровень доступа "Удаление"
	 */
	const LEVEL_DELETE = 'DA';

	/**
	 * Уровень досутпа "Запускать свои"
	 */
	const LEVEL_RUN_OWN = 'UO';

	/**
	 * Уровень досутпа "Запускать"
	 */
	const LEVEL_RUN = 'UA';

	/**
	 * Уровень доступа "Полный"
	 */
	const LEVEL_ALL = 'AA';

	/**
	 * Проверяет, может ли пользователь просматривать свои записи
	 * Если пользователь администратор, проверка не происходит, так как у него и так есть доступ
	 *
	 * @param string   $sAccessName Тип доступа
	 * @param null|int $userID      ID пользователя, либо NULL (текущий пользователь)
	 *
	 * @return bool TRUE - есть необходимые права, FALSE - нет прав
	 */
	public function canViewOwn ($sAccessName, $userID=null)
	{
		return self::canLevel($sAccessName, $userID,self::LEVEL_VIEW_OWN);
	}

	/**
	 * Проверяет, может ли пользователь просматривать все существующие записи
	 * Если пользователь администратор, проверка не происходит, так как у него и так есть доступ
	 *
	 * @param string   $sAccessName Тип доступа
	 * @param null|int $userID      ID пользователя, либо NULL (текущий пользователь)
	 *
	 * @return bool TRUE - есть необходимые права, FALSE - нет прав
	 */
	public function canView ($sAccessName, $userID=null)
	{
		return self::canLevel($sAccessName, $userID,self::LEVEL_VIEW);
	}

	/**
	 * Проверяет, может ли пользователь создавать записи
	 * Если пользователь администратор, проверка не происходит, так как у него и так есть доступ
	 *
	 * @param string   $sAccessName Тип доступа
	 * @param null|int $userID      ID пользователя, либо NULL (текущий пользователь)
	 *
	 * @return bool TRUE - есть необходимые права, FALSE - нет прав
	 */
	public function canCreate ($sAccessName, $userID=null)
	{
		return self::canLevel($sAccessName, $userID,self::LEVEL_CREATE);
	}

	/**
	 * Проверяет, может ли пользователь редактировать все записи
	 * Если пользователь администратор, проверка не происходит, так как у него и так есть доступ
	 *
	 * @param string   $sAccessName Тип доступа
	 * @param null|int $userID      ID пользователя, либо NULL (текущий пользователь)
	 *
	 * @return bool TRUE - есть необходимые права, FALSE - нет прав
	 */
	public function canEdit ($sAccessName, $userID=null)
	{
		return self::canLevel($sAccessName, $userID,self::LEVEL_EDIT);
	}

	/**
	 * Проверяет, может ли пользователь редактировать свои записи
	 * Если пользователь администратор, проверка не происходит, так как у него и так есть доступ
	 *
	 * @param string   $sAccessName Тип доступа
	 * @param null|int $userID      ID пользователя, либо NULL (текущий пользователь)
	 *
	 * @return bool TRUE - есть необходимые права, FALSE - нет прав
	 */
	public function canEditOwn ($sAccessName, $userID=null)
	{
		return self::canLevel($sAccessName, $userID,self::LEVEL_EDIT_OWN);
	}

	/**
	 * Проверяет, может ли пользователь писать в свои записи
	 * Если пользователь администратор, проверка не происходит, так как у него и так есть доступ
	 *
	 * @param string   $sAccessName Тип доступа
	 * @param null|int $userID      ID пользователя, либо NULL (текущий пользователь)
	 *
	 * @return bool TRUE - есть необходимые права, FALSE - нет прав
	 */
	public function canWriteOwn ($sAccessName, $userID=null)
	{
		return self::canLevel($sAccessName, $userID, self::LEVEL_WRITE_OWN);
	}

	/**
	 * Проверяет, может ли пользователь писать во все записи
	 * Если пользователь администратор, проверка не происходит, так как у него и так есть доступ
	 *
	 * @param string   $sAccessName Тип доступа
	 * @param null|int $userID      ID пользователя, либо NULL (текущий пользователь)
	 *
	 * @return bool TRUE - есть необходимые права, FALSE - нет прав
	 */
	public function canWrite ($sAccessName, $userID=null)
	{
		return self::canLevel($sAccessName, $userID, self::LEVEL_WRITE);
	}

	/**
	 * Проверяет, может ли пользователь удалять все записи
	 * Если пользователь администратор, проверка не происходит, так как у него и так есть доступ
	 *
	 * @param string   $sAccessName Тип доступа
	 * @param null|int $userID      ID пользователя, либо NULL (текущий пользователь)
	 *
	 * @return bool TRUE - есть необходимые права, FALSE - нет прав
	 */
	public function canDelete ($sAccessName, $userID=null)
	{
		return self::canLevel($sAccessName, $userID,self::LEVEL_DELETE);
	}

	/**
	 * Проверяет, может ли пользователь удалять свои записи
	 * Если пользователь администратор, проверка не происходит, так как у него и так есть доступ
	 *
	 * @param string   $sAccessName Тип доступа
	 * @param null|int $userID      ID пользователя, либо NULL (текущий пользователь)
	 *
	 * @return bool TRUE - есть необходимые права, FALSE - нет прав
	 */
	public function canDeleteOwn ($sAccessName, $userID=null)
	{
		return self::canLevel($sAccessName, $userID,self::LEVEL_DELETE_OWN);
	}

	/**
	 * Проверяет, может ли пользователь запускать любые элементы
	 * Если пользователь администратор, проверка не происходит, так как у него и так есть доступ
	 *
	 * @param string   $sAccessName Тип доступа
	 * @param null|int $userID      ID пользователя, либо NULL (текущий пользователь)
	 *
	 * @return bool TRUE - есть необходимые права, FALSE - нет прав
	 */
	public function canRun ($sAccessName, $userID=null)
	{
		return self::canLevel($sAccessName, $userID,self::LEVEL_RUN);
	}

	/**
	 * Проверяет, может ли пользователь запускать свои элементы
	 * Если пользователь администратор, проверка не происходит, так как у него и так есть доступ
	 *
	 * @param string   $sAccessName Тип доступа
	 * @param null|int $userID      ID пользователя, либо NULL (текущий пользователь)
	 *
	 * @return bool TRUE - есть необходимые права, FALSE - нет прав
	 */
	public function canRunOwn ($sAccessName, $userID=null)
	{
		return self::canLevel($sAccessName, $userID,self::LEVEL_RUN_OWN);
	}

	/**
	 * Проверяет, есть ли у пользователя полный доступ
	 * Если пользователь администратор, проверка не происходит, так как у него и так есть доступ
	 *
	 * @param string   $sAccessName Тип доступа
	 * @param null|int $userID      ID пользователя, либо NULL (текущий пользователь)
	 *
	 * @return bool TRUE - есть необходимые права, FALSE - нет прав
	 */
	public function canAll ($sAccessName, $userID=null)
	{
		return self::canLevel($sAccessName, $userID,self::LEVEL_ALL);
	}

/*	public static function canMultiLevel ($arAccessName, $arLevels, $userID=null)
	{
		if (!is_array($arAccessName))
		{
			$arAccessName = [$arAccessName];
		}
		if (!is_array($arLevels))
		{
			$arLevels = [$arLevels];
		}
		$arUserGroups = [];
		if (\Ms\Core\Lib\Access::can($userID, $arUserGroups))
		{
			return true;
		}

		$arAccessGroups = GroupAccess::getMultiAccess('ms.dobrozhil',$arAccessName,$arUserGroups);
		if (!$arAccessGroups || !is_array($arAccessGroups) || empty($arAccessGroups))
		{
			return false;
		}
		$arAccess = [];
		foreach ($arAccessGroups as $groupID=>$ar_access)
		{
			if (in_array($groupID,$arUserGroups))
			{
				if (!empty($ar_access))
				{
					$arAccess = array_merge($arAccess,$ar_access);
				}
			}
		}
	}*/

	public function getAllUserRights ($arAccessName, $userID=null)
	{
		$arReturn = [];
		if (!is_array($arAccessName))
		{
			$arAccessName = [$arAccessName];
		}
		$arUserGroups = [];
		if (\Ms\Core\Entity\Modules\Access::getInstance()->can($userID, $arUserGroups))
		{
			foreach ($arAccessName as $sAccessName)
			{
				$arReturn[$sAccessName] = [self::LEVEL_ALL];
			}
			return $arReturn;
		}

		$arAccessGroups = GroupAccess::getInstance()->getMultiAccess('ms.dobrozhil',$arAccessName,$arUserGroups);
		if (!$arAccessGroups || !is_array($arAccessGroups) || empty($arAccessGroups))
		{
			return false;
		}

		foreach ($arAccessGroups as $groupID=>$arAccess)
		{
			if (!empty($arAccess))
			{
				foreach ($arAccess as $accessCode=>$arRights)
				{
					if (!empty($arRights))
					{
						foreach ($arRights as $right)
						{
							if (!isset($arReturn[$accessCode]))
							{
								$arReturn[$accessCode] = [];
							}
							if (!in_array($right,$arReturn[$accessCode]))
							{
								$arReturn[$accessCode][] = $right;
							}
						}
					}
				}
			}
		}

		return $arReturn;
	}

	public function getRightsArray ($arRightTypes, $createdBy, $userID=null)
	{
		/*
			VIEW:
			const LEVEL_VIEW_OWN = 'RO';
			const LEVEL_VIEW = 'RA';

			CREATE:
			const LEVEL_CREATE = 'CR';

			EDIT:
			const LEVEL_EDIT_OWN = 'EO';
			const LEVEL_EDIT = 'EA';

			WRITE:
			const LEVEL_WRITE_OWN = 'WO';
			const LEVEL_WRITE = 'WA';

			DELETE:
			const LEVEL_DELETE_OWN = 'DO';
			const LEVEL_DELETE = 'DA';

			RUN:
			const LEVEL_RUN_OWN = 'UO';
			const LEVEL_RUN = 'UA';
		*/

		$userID = Tools::normalizeUserID($userID);
		$arRights = [];

		//ALL:
		if (in_array(self::LEVEL_ALL,$arRightTypes))
		{
			$arRights['ALL'] = true;
		}
		else
		{
			$arRights['ALL'] = false;
		}

		//VIEW:
		if (
			in_array(self::LEVEL_VIEW,$arRightTypes)
			|| (in_array(self::LEVEL_VIEW_OWN,$arRightTypes) && (int)$createdBy == (int)$userID)
		) {
			$arRights['VIEW'] = true;
		}
		else
		{
			$arRights['VIEW'] = false;
		}

		//CREATE:
		if (in_array(self::LEVEL_CREATE,$arRightTypes))
		{
			$arRights['CREATE'] = true;
		}
		else
		{
			$arRights['CREATE'] = false;
		}

		//EDIT:
		if (
			in_array(self::LEVEL_EDIT,$arRightTypes)
			|| (in_array(self::LEVEL_EDIT_OWN,$arRightTypes) && (int)$createdBy == (int)$userID)
		) {
			$arRights['EDIT'] = true;
		}
		else
		{
			$arRights['EDIT'] = false;
		}

		//WRITE:
		if (
			in_array(self::LEVEL_WRITE,$arRightTypes)
			|| (in_array(self::LEVEL_WRITE_OWN,$arRightTypes) && (int)$createdBy == (int)$userID)
		) {
			$arRights['WRITE'] = true;
		}
		else
		{
			$arRights['WRITE'] = false;
		}

		//DELETE:
		if (
			in_array(self::LEVEL_DELETE,$arRightTypes)
			|| (in_array(self::LEVEL_DELETE_OWN,$arRightTypes) && (int)$createdBy == (int)$userID)
		) {
			$arRights['DELETE'] = true;
		}
		else
		{
			$arRights['DELETE'] = false;
		}

		//RUN:
		if (
			in_array(self::LEVEL_RUN,$arRightTypes)
			|| (in_array(self::LEVEL_RUN_OWN,$arRightTypes) && (int)$createdBy == (int)$userID)
		) {
			$arRights['RUN'] = true;
		}
		else
		{
			$arRights['RUN'] = false;
		}

		return $arRights;
	}

	/**
	 * Проверяет есть ли у пользователя требуемый доступ
	 * Если пользователь администратор, проверка не происходит, так как у него и так есть доступ
	 *
	 * @param string   $sAccessName Тип доступа
	 * @param null|int $userID      ID пользователя, либо NULL (текущий пользователь)
	 * @param string   $sLevel      Уровень доступа
	 *
	 * @return bool TRUE - есть необходимые права, FALSE - нет прав
	 */
	private function canLevel ($sAccessName, $userID, $sLevel)
	{
		$arUserGroups = [];
		if (\Ms\Core\Entity\Modules\Access::getInstance()->can($userID,$arUserGroups))
		{
			return TRUE;
		}

		$arAccessGroups = GroupAccess::getInstance()->getAccess(
			'ms.dobrozhil',
			$sAccessName,
			$arUserGroups
		);
		if (!$arAccessGroups || !is_array($arAccessGroups) || empty($arAccessGroups))
		{
			return FALSE;
		}
		$arAccess = [];
		foreach ($arAccessGroups as $groupID=>$ar_access)
		{
			if (in_array($groupID,$arUserGroups))
			{
				if (!empty($ar_access))
				{
					$arAccess = array_merge($arAccess,$ar_access);
				}
			}
		}
		if (empty($arAccess))
		{
			return FALSE;
		}

		$arAccess = array_unique($arAccess);
		if (in_array($sLevel,$arAccess))
		{
			return TRUE;
		}

		return FALSE;
	}
}