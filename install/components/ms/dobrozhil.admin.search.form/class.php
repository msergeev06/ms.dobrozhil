<?php

namespace Ms\Dobrozhil\Entity\Components;

use Ms\Core\Entity\Component;

class AdminSearchFormComponent extends Component
{
	public function __construct ($component, $template='.default', $arParams=array())
	{
		parent::__construct($component,$template,$arParams);
	}

	public function run ()
	{
		$this->includeTemplate();
	}
}