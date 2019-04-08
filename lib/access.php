<?php

namespace Ms\Dobrozhil\Lib;

use Ms\Core\Lib\GroupAccess;

class Access
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
	public static function canViewOwn ($sAccessName, $userID=null)
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
	public static function canView ($sAccessName, $userID=null)
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
	public static function canCreate ($sAccessName, $userID=null)
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
	public static function canEdit ($sAccessName, $userID=null)
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
	public static function canEditOwn ($sAccessName, $userID=null)
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
	public static function canWriteOwn ($sAccessName, $userID=null)
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
	public static function canWrite ($sAccessName, $userID=null)
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
	public static function canDelete ($sAccessName, $userID=null)
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
	public static function canDeleteOwn ($sAccessName, $userID=null)
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
	public static function canRun ($sAccessName, $userID=null)
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
	public static function canRunOwn ($sAccessName, $userID=null)
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
	public static function canAll ($sAccessName, $userID=null)
	{
		return self::canLevel($sAccessName, $userID,self::LEVEL_ALL);
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
	private static function canLevel ($sAccessName, $userID, $sLevel)
	{
		$sAccessName = strtoupper($sAccessName);
		$arUserGroups = [];
		if (\Ms\Core\Lib\Access::can($userID,$arUserGroups))
		{
			return TRUE;
		}

		$arAccessGroups = GroupAccess::getAccess(
			'ms.dobrozhil',
			$sAccessName,
			$arUserGroups
		);
		if (!$arAccessGroups || !is_array($arAccessGroups) || empty($arAccessGroups))
		{
			return FALSE;
		}
		$arAccess = [];
		foreach ($arUserGroups as $groupID=>$ar_access)
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