<?php

namespace Ms\Dobrozhil\Entity\Components;

use Ms\Core\Entity\Application;
use Ms\Core\Entity\Component;

class AdminMenuTopComponent extends Component
{
	public function __construct ($component, $template='.default', $arParams=array())
	{
		parent::__construct($component,$template,$arParams);
	}

	public function run ()
	{
		$arResult = &$this->arResult;
		$arResult['USER'] = Application::getInstance()->getUser();
		$arResult['NAME'] = $arResult['USER']->getParam('propFullName');
		$arResult['SHOW_NAME'] = (strlen($arResult['NAME'])>0);
		$arResult['IS_AUTH'] = $arResult['USER']->isAuthorise();

		$this->includeTemplate();
	}
}