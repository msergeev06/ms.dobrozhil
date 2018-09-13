<?php if(!defined('MS_PROLOG_INCLUDED')||MS_PROLOG_INCLUDED!==true)die('Access denied');

$arResult = &$this->arResult;

if ($arResult['RESULT']=='success'):?>
	<div class="text-success">Класс успешно добавлен</div>
<?else:?>
	<div class="text-danger">Ошибка добавления класса</div>
<?endif;?>
