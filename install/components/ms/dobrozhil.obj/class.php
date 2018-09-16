<?php
/**
 * Компонент модуля ms.dobrozhil ms:dobrozhil.obj
 *
 * @package Ms\Dobrozhil
 * @subpackage Entity\Components
 * @author Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright 2018 Mikhail Sergeev
 */

namespace Ms\Dobrozhil\Entity\Components;

use Ms\Core\Entity\Application;
use Ms\Core\Entity\Component;
use Ms\Core\Lib\Loader;
use Ms\Core\Lib\Tools;
use Ms\Core\Lib\Loc;

Loc::includeLocFile(__FILE__);

class ObjComponent extends Component
{
	public function __construct ($component, $template='.default', $arParams=array())
	{
		parent::__construct($component,$template,$arParams);
	}

	public function run ()
	{
		$arResult = &$this->arResult;
		$arParams = &$this->arParams;
		Loader::includeModule('ms.dobrozhil');

		if ($arParams['SET_TITLE'])
		{
			Application::getInstance()->setTitle(
				//'Классы и объекты'
				Loc::getCompMess('ms:dobrozhil.obj','classes_n_objects')
			);
		}

		if ($arParams['ADD_NAV_CHAIN'])
		{
			Application::getInstance()->getBreadcrumbs()->addNavChain(
				//'Панель'
				Loc::getCompMess('ms:dobrozhil.obj','panel'),
				'/admin/',
				'admin'
			);
			Application::getInstance()->getBreadcrumbs()->addNavChain(
				//'Классы и объекты'
				Loc::getCompMess('ms:dobrozhil.obj','classes_n_objects'),
				$arParams['ROOT_PATH'],
				'admin_objects'
			);
		}

		$arResult['USER'] = Application::getInstance()->getUser();
//		if (!$USER->isAdmin()) die(); //TODO: Проверка на доступ к разделу

//		$deleteClass = (isset($_REQUEST['deleteClass']))?$_REQUEST['deleteClass']:'';
		$view = (isset($_REQUEST['view']))?$_REQUEST['view']:false;
		$page = (isset($_REQUEST['page']))?$_REQUEST['page']:null;
/*		if (
			strlen($deleteClass)>0
			&& Classes::checkClassExists($deleteClass)
		) {
			Classes::delete($deleteClass,true);
		}*/
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
			'object_add',
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

	protected function checkArParams ()
	{
		$this->arParams['CUR_PAGE'] = Tools::getCurPath();
		$this->arParams['CUR_DIR'] = Tools::getCurDir();
		$this->arParams['PATH_TOOLS'] = '/ms/modules/ms.dobrozhil/tools';
	}

	/*	private function checkParams ()
		{
			if (!isset($this->arParams['SET_TITLE']))
			{
				$this->arParams['SET_TITLE'] = true;
			}

			if (!isset($this->arParams['USE_SEF']))
			{
				$this->arParams['USE_SEF'] = true;
			}

			if (!isset($this->arParams['ROOT_PATH']))
			{
				$this->arParams['ROOT_PATH'] = '/admin/objects/';
			}

			if (!isset($this->arParams['PATH_CLASS_ADD']))
			{
				$this->arParams['PATH_CLASS_ADD'] = 'class_add/';
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

			if (!isset($this->arParams['PATH_OBJECT_ADD']))
			{
				$this->arParams['PATH_OBJECT_ADD'] = 'object_add/';
			}

			if (!isset($this->arParams['PATH_OBJECT_EDIT']))
			{
				$this->arParams['PATH_OBJECT_EDIT'] = 'object_edit/#OBJECT_NAME#/';
			}
		}*/
}
