<?php if(!defined('MS_PROLOG_INCLUDED')||MS_PROLOG_INCLUDED!==true)die('Access denied');

$arResult = &$this->arResult;

\Ms\Core\Entity\Application::getInstance()->includeComponent(
    'ms:dobrozhil.objects.class.add',
    '',
    array ()
);
?>
