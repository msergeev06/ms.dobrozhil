<?php
/**
 * Компонент модуля ms.dobrozhil ms:dobrozhil.obj.class.add
 *
 * @package Ms\Dobrozhil
 * @subpackage Entity\Components
 * @author Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright 2018 Mikhail Sergeev
 */

namespace Ms\Dobrozhil\Entity\Components;

use Ms\Core\Entity\Application;
use Ms\Core\Entity\Component;
use Ms\Core\Lib\Loc;
use Ms\Dobrozhil\Lib\Classes;
use Ms\Dobrozhil\Tables\ClassesTable;

Loc::includeLocFile(__FILE__);

class ObjClassAddComponent extends Component
{
	public function __construct ($component, $template='.default', $arParams=array())
	{
		parent::__construct($component,$template,$arParams);
	}

	public function run ()
	{
		$arResult = &$this->arResult;
		$arParams = &$this->arParams;
		if ($arParams['SET_TITLE'])
		{
			Application::getInstance()->setTitle(
//				'Добавление класса'
				Loc::getCompMess('ms:dobrozhil.obj.class.add','page_title')
			);
		}
		if ($arParams['ADD_NAV_CHAIN'])
		{
			Application::getInstance()->getBreadcrumbs()->addNavChain(
//				'Добавление класса',
				Loc::getCompMess('ms:dobrozhil.obj.class.add','page_title'),
				$arParams['PATH_CLASS_ADD'],
				'admin_objects_class_add'
			);
		}
		if (!isset($_POST['form_action']) || (int)$_POST['form_action']==0)
		{
			$arParams['STEP'] = 0;
			$arRes = ClassesTable::getList(array (
				'select' => array ('NAME'),
				'order' => array ('NAME'=>'ASC')
			));
			$arResult['CLASSES_LIST'] = array ();
			if ($arRes && !empty($arRes))
			{
				foreach ($arRes as $ar_res)
				{
					$arResult['CLASSES_LIST'][] = array (
						'NAME' => $ar_res['NAME'],
						'VALUE' => $ar_res['NAME']
					);
				}
			}

			$this->includeTemplate();
		}
		elseif ((int)$_POST['form_action']==1)
		{
			$arParams['STEP'] = 1;
			$arResult['ADD'] = array (
				'NAME' => $_POST['NAME'],
				'NOTE' => (strlen($_POST['NOTE'])>0)?$_POST['NOTE']:null,
				'PARENT_CLASS' => (strlen($_POST['PARENT_CLASS'])>0)?$_POST['PARENT_CLASS']:null
			);
			;
			if ($res = Classes::addNewClass($arResult['ADD']))
			{
				$arResult['RESULT'] = 'success';
				$this->includeTemplate('result');
			}
			else
			{
				$arResult['RESULT'] = 'error';
				$this->includeTemplate('result');
			}
		}
	}

}