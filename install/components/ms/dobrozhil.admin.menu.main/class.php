<?php

namespace Ms\Dobrozhil\Entity\Components;

use Ms\Core\Entity\Application;
use Ms\Core\Entity\Component;
use Ms\Core\Lib\Loader;
use Ms\Dobrozhil\Lib\Main;

class AdminMenuMainComponent extends Component
{
	public function __construct ($component, $template='.default', $arParams=array())
	{
		parent::__construct($component,$template,$arParams);
	}

	public function run ()
	{
		$arResult = &$this->arResult;
		$USER = Application::getInstance()->getUser();
		if (!Loader::includeModule('ms.dobrozhil')) return;
		$arResult['MENU'] = Main::getAdminMenuArray();
		if (!empty($arResult['MENU']['LIST']))
		{
			foreach ($arResult['MENU']['LIST'] as $group=>&$arList)
			{
				if (!empty($arList))
				{
					foreach ($arList as $item=>&$arItem)
					{
						if (!isset($arItem['show']))
						{
							$arItem['is_show'] = false;
						}
						else
						{
							$arItem['is_show'] = call_user_func($arItem['show'],$group,$arItem['items_id'],$USER);
						}
					}
					unset($arItem);
				}
			}
			unset($arList);
			$arResult['GENERAL'] = Main::getGeneralAdminMenuArray();
		}

		$this->includeTemplate();
	}
}