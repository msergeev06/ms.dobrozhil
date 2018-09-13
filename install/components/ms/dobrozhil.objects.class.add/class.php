<?php

namespace Ms\Dobrozhil\Entity\Components;

use Ms\Core\Entity\Component;
use Ms\Dobrozhil\Lib\Classes;
use Ms\Dobrozhil\Tables\ClassesTable;

class ObjectsClassAddComponent extends Component
{
	public function __construct ($component, $template='.default', $arParams=array())
	{
		parent::__construct($component,$template,$arParams);
	}

	public function run ()
	{
		$arResult = &$this->arResult;
		$arParams = &$this->arParams;
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