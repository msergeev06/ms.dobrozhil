<?php

namespace Ms\Dobrozhil\Lib;

use Ms\Core\Entity\Application;
use Ms\Core\Entity\User;
use Ms\Core\Lib\Events;

class Main
{
	/**
	 * Возвращает массив с меню административной части, либо пустой массив
	 *
	 * @return array
	 */
	public static function getAdminMenuArray ()
	{
		$arMenu = $arReturn = array();
		Events::runEvents(
			'ms.dobrozhil',
			'OnBuildAdminMainMenu',
			array (&$arMenu)
		);
		if (!empty($arMenu))
		{
			foreach ($arMenu as $ar_menu)
			{
				if (self::checkSectionMenu($ar_menu['parent_menu']))
				{
					$arReturn['SORT'][$ar_menu['parent_menu']][(int)$ar_menu['sort']] = $ar_menu['items_id'];
					$arReturn['LIST'][$ar_menu['parent_menu']][$ar_menu['items_id']] = $ar_menu;
				}
			}
		}
		unset($arMenu);
		if (isset($arReturn['SORT']) && !empty($arReturn['SORT']))
		{
			foreach ($arReturn['SORT'] as $group=>&$arSort)
			{
				ksort($arSort);
			}
			unset($arSort);
		}

		return $arReturn;
	}

	public static function getGeneralAdminMenuArray()
	{
		return array (
			'admin_main_menu_general' => array('NAME'=>'Основное','CODE'=>'general','ICON'=>'/ms/images/ms_dobrozhil/general_icon.png'),
			'admin_main_menu_apps' => array('NAME'=>'Приложения','CODE'=>'apps','ICON'=>'/ms/images/ms_dobrozhil/apps_icon.png'),
			'admin_main_menu_devices' => array('NAME'=>'Устройства','CODE'=>'devices','ICON'=>'/ms/images/ms_dobrozhil/devices_icon.png'),
			'admin_main_menu_setup' => array('NAME'=>'Установки','CODE'=>'setup','ICON'=>'/ms/images/ms_dobrozhil/setup_icon.png'),
			'admin_main_menu_system' => array('NAME'=>'Система','CODE'=>'system','ICON'=>'/ms/images/ms_dobrozhil/system_icon.png')
		);
	}

	public static function checkSectionMenu ($section)
	{
		if (
			strtolower($section)=='admin_main_menu_general'
			|| strtolower($section)=='admin_main_menu_devices'
			|| strtolower($section)=='admin_main_menu_apps'
			|| strtolower($section)=='admin_main_menu_setup'
			|| strtolower($section)=='admin_main_menu_system'
		) {
			return TRUE;
		}

		return FALSE;
	}

	public static function checkShowMenu ($section, $item, User $user=null)
	{
		if (is_null($user))
		{
			$user = Application::getInstance()->getUser();
		}
		$section = strtolower($section);
		$item = strtolower($item);
		if (!self::checkSectionMenu($section))
		{
			return false;
		}
		if ($section=='admin_main_menu_setup' && $item=='users')
		{
			return true;
		}
		else
		{
			if ($user->isAdmin())
			{
				return true;
			}
			else
			{
				return false;
			}
		}
	}
}