<?php
/**
 * Компонент модуля ms.dobrozhil ms:dobrozhil.obj.object.add
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
use Ms\Dobrozhil\Lib\Objects;
use Ms\Dobrozhil\Tables\ClassesTable;
use Ms\Dobrozhil\Tables\ObjectsTable;

Loc::includeLocFile(__FILE__);

class ObjObjectAddComponent extends Component
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
//				'Добавление объекта'
				Loc::getCompMess('ms:dobrozhil.obj.object.add','page_title')
			);
		}
		if ($arParams['ADD_NAV_CHAIN'])
		{
			Application::getInstance()->getBreadcrumbs()->addNavChain(
//				'Добавление объекта',
				Loc::getCompMess('ms:dobrozhil.obj.object.add','page_title'),
				$arParams['PATH_OBJECT_ADD'],
				'admin_objects_object_add'
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
			$arRes = ObjectsTable::getList(
				array (
					'select' => array ('NAME'),
					'filter' => array ('CLASS_NAME'=> 'CRooms'),
					'order' => array ('NAME'=>'ASC')
				)
			);
			$arResult['ROOMS_LIST'] = array();
			if ($arRes && !empty($arRes))
			{
				foreach ($arRes as $ar_res)
				{
					$arResult['ROOMS_LIST'][] = array (
						'NAME' => $ar_res['NAME'],
						'VALUE' => $ar_res['NAME']
					);
				}
			}

			$this->includeTemplate();
//			msDebug($arParams);
		}
		elseif ((int)$_POST['form_action']==1)
		{
			$arParams['STEP'] = 1;
			$arResult['ADD'] = array (
				'NAME' => $_POST['NAME'],
				'CLASS_NAME' => $_POST['CLASS_NAME'],
				'NOTE' => (isset($_POST['NOTE'])&&strlen($_POST['NOTE'])>0)?$_POST['NOTE']:null,
				'ROOM_NAME' => (isset($_POST['ROOM_NAME'])&&strlen($_POST['ROOM_NAME'])>0)?$_POST['ROOM_NAME']:null
			);
			$res = Objects::addNewObject(
				$_POST['NAME'],
				$_POST['CLASS_NAME'],
				(strlen($_POST['NOTE'])>0)?$_POST['NOTE']:null,
				(strlen($_POST['ROOM_NAME'])>0)?$_POST['ROOM_NAME']:null
			);
			if ($res)
			{
				$arResult['RESULT'] = 'success';

				$this->includeTemplate('result');
				Application::getInstance()->setRefresh($arParams['ROOT_PATH'],5);
			}
			else
			{
				$arResult['RESULT'] = 'error';

				$this->includeTemplate('result');
				Application::getInstance()->setRefresh($arParams['ROOT_PATH'],5);
			}
		}
	}

}