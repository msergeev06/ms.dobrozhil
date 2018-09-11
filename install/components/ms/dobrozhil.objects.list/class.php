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
		$this->checkParams();

		Loader::includeModule('ms.dobrozhil');

		if ($this->arParams['SET_TITLE']=='Y')
		{
			Application::getInstance()->setTitle('Классы и объекты');
		}

		$arResult['DATA'] = Classes::getList(true);

//		msDebug($arResult);
		if ($this->arParams['VIEW']=='list')
		{
			$this->includeTemplate('list');
		}
		else
		{
			$this->includeTemplate();
		}
	}

	private function checkParams ()
	{
		if (!isset($this->arParams['USER_ID']) || (int)$this->arParams['USER_ID']<=0)
		{
			$this->arParams['USER_ID'] = Application::getInstance()->getUser()->getID();
		}

		if (!isset($this->arParams['SET_TITLE']))
		{
			$this->arParams['SET_TITLE'] = true;
		}

		if (!isset($this->arParams['USE_SEF']))
		{
			$this->arParams['USE_SEF'] = true;
		}

		if (
			!isset($this->arParams['VIEW'])
			|| ($this->arParams['VIEW']!='tree' && $this->arParams['VIEW']!='list')
		) {
			$this->arParams['VIEW'] = 'tree';
		}

		if (!isset($this->arParams['ROOT_PATH']))
		{
			$this->arParams['ROOT_PATH'] = '/ms/admin/objects/';
		}

		if (!isset($this->arParams['PATH_CLASS_EDIT']))
		{
			$this->arParams['PATH_CLASS_EDIT'] = 'class_edit/#CLASS_NAME#/';
		}

		if (!isset($this->arParams['PATH_CLASS_PROPERTIES_LIST']))
		{
			$this->arParams['PATH_CLASS_PROPERTIES_LIST'] = 'class_properties_list/#CLASS_NAME#/';
		}

		if (!isset($this->arParams['PATH_CLASS_METHODS_LIST']))
		{
			$this->arParams['PATH_CLASS_METHODS_LIST'] = 'class_methods_list/#CLASS_NAME#/';
		}

		if (!isset($this->arParams['PATH_CLASS_METHOD_EDIT']))
		{
			$this->arParams['PATH_CLASS_METHOD_EDIT'] = 'class_method_edit/#CLASS_NAME#/#METHOD_NAME#/';
		}

		if (!isset($this->arParams['PATH_CLASS_OBJECTS_LIST']))
		{
			$this->arParams['PATH_CLASS_OBJECTS_LIST'] = 'class_objects_list/#CLASS_NAME#/';
		}

		if (!isset($this->arParams['PATH_CLASS_ADD_CHILD']))
		{
			$this->arParams['PATH_CLASS_ADD_CHILD'] = 'class_add_child/#CLASS_NAME#/';
		}

		if (!isset($this->arParams['PATH_CLASS_DELETE']))
		{
			$this->arParams['PATH_CLASS_DELETE'] = 'class_delete/#CLASS_NAME#/';
		}

		if (!isset($this->arParams['PATH_OBJECT_EDIT']))
		{
			$this->arParams['PATH_OBJECT_EDIT'] = 'object_edit/#OBJECT_NAME#/';
		}

		if (!isset($this->arParams['PATH_TOOLS']))
		{
			$this->arParams['PATH_TOOLS'] = '/ms/modules/ms.dobrozhil/tools';
		}
	}
}