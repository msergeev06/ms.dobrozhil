<?php

namespace Ms\Dobrozhil\Ui;

use Ms\Core\Entity\System\Multiton;

class Show extends Multiton
{
	public function showDangerMessage ($sMessage)
	{
		$sMessage = '<div class="clearfix"></div><div class="alert alert-danger">'.$sMessage.'</div>';

		echo $sMessage;
	}
}