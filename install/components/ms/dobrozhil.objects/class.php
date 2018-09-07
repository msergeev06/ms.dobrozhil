<?php

namespace Ms\Dobrozhil\Entity\Components;

use Ms\Core\Entity\Application;
use Ms\Core\Entity\Component;
use Ms\Core\Lib\Tools;
use Ms\Dobrozhil\Lib\Classes;

class ObjectsComponent extends Component
{
	public function __construct ($component, $template='.default', $arParams=array())
	{
		parent::__construct($component,$template,$arParams);
	}

	public function run ()
	{
		$arResult = &$this->arResult;

		$arResult['USER'] = Application::getInstance()->getUser();
		$request = Application::getInstance()->getContext()->getRequest();
//		if (!$USER->isAdmin()) die(); //TODO: Проверка на доступ к разделу

		$arResult['CUR_PAGE'] = Tools::getCurPath();
		$arResult['CUR_DIR'] = Tools::getCurDir();
		$arResult['PATH_TOOLS'] = '/ms/modules/ms.dobrozhil/tools';
		$deleteClass = $request->getQuery('deleteClass');
		$view = $request->getQuery('view');
		if (
			strlen($deleteClass)>0
			&& Classes::checkClassExists($deleteClass)
		) {
			Classes::delete($deleteClass,true);
		}
		if ($view && ($view=='tree' || $view=='list'))
		{
			$arResult['VIEW'] = $_REQUEST['view'];
			$arResult['USER']->setUserCookie('admin_objects_page_view',$arResult['VIEW']);
		}
		elseif (!$arResult['VIEW'] = $arResult['USER']->getUserCookie('admin_objects_page_view'))
		{
			$arResult['VIEW'] = 'tree';
			$arResult['USER']->setUserCookie('admin_objects_page_view','tree');
		}

		$this->includeTemplate();
	}
}