<?php

namespace Ms\Dobrozhil\Entity\Components;

use Ms\Core\Entity\Application;
use Ms\Core\Entity\Component;
use Ms\Core\Lib\Loader;
use Ms\Core\Lib\Tools;
use Ms\Dobrozhil\Lib\Classes;

class ObjectsListComponent extends Component
{
	public function __construct ($component, $template='.default', $arParams=array())
	{
		parent::__construct($component,$template,$arParams);
	}

	public function run ()
	{
		$arResult = &$this->arResult;

		Loader::includeModule('ms.dobrozhil');

		if ($this->arParams['SET_TITLE']=='Y')
		{
			Application::getInstance()->setTitle('Классы и объекты');
		}

		$arResult['DATA'] = Classes::getList(true);

		msDebug($arResult);
		if ($this->arParams['VIEW']=='list')
		{
			$this->includeTemplate('list');
		}
		else
		{
			$this->includeTemplate();
		}
	}
}