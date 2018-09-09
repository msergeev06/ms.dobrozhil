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
//		if (!$USER->isAdmin()) die(); //TODO: Проверка на доступ к разделу

		$arResult['CUR_PAGE'] = Tools::getCurPath();
		$arResult['CUR_DIR'] = Tools::getCurDir();
		$arResult['PATH_TOOLS'] = '/ms/modules/ms.dobrozhil/tools';
		$deleteClass = (isset($_REQUEST['deleteClass']))?$_REQUEST['deleteClass']:'';
		$view = (isset($_REQUEST['view']))?$_REQUEST['view']:false;
		$page = (isset($_REQUEST['page']))?$_REQUEST['page']:null;
		if (
			strlen($deleteClass)>0
			&& Classes::checkClassExists($deleteClass)
		) {
			Classes::delete($deleteClass,true);
		}
		if ($view && ($view=='tree' || $view=='list'))
		{
			$arResult['VIEW'] = $view;
			$arResult['USER']->setUserCookie('admin_objects_page_view',$arResult['VIEW']);
		}
		elseif (!$arResult['VIEW'] = $arResult['USER']->getUserCookie('admin_objects_page_view'))
		{
			$arResult['VIEW'] = 'tree';
			$arResult['USER']->setUserCookie('admin_objects_page_view','tree');
		}

		$arTemplates = array (
			'class_add',
			'class_add_child',
			'class_delete',
			'class_edit',
			'class_method_add',
			'class_method_edit',
			'class_methods_list',
			'class_object_add',
			'class_object_edit',
			'class_objects_list',
			'class_properties_list',
			'class_property_add',
			'class_property_edit',
			'object_add',
			'object_properties_list'
		);
		if (in_array($page,$arTemplates))
		{
			$this->includeTemplate($page);
		}
		else
		{
			$this->includeTemplate();
		}
	}
}