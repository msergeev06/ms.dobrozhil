<?php
/**
 * Компонент модуля ms.dobrozhil ms:dobrozhil.obj.list
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
use Ms\Dobrozhil\Lib\Classes;
use Ms\Core\Lib\Loc;

Loc::includeLocFile(__FILE__);

class ObjListComponent extends Component
{
	public function __construct ($component, $template='.default', $arParams=array())
	{
		parent::__construct($component,$template,$arParams);
	}

	public function run ()
	{
		$arResult = &$this->arResult;

		Loader::includeModule('ms.dobrozhil');

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

	protected function checkArParams ()
	{
		if (
			!isset($this->arParams['VIEW'])
			|| ($this->arParams['VIEW']!='tree' && $this->arParams['VIEW']!='list')
		) {
			$this->arParams['VIEW'] = 'tree';
		}

		if (!isset($this->arParams['USER_ID']))
		{
			$this->arParams['USER_ID'] = Application::getInstance()->getUser()->getID();
		}

		if (!isset($this->arParams['PATH_TOOLS']))
		{
			$this->arParams['PATH_TOOLS'] = '/ms/modules/ms.dobrozhil/tools';
		}
	}
}