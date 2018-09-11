<?php if(!defined('MS_PROLOG_INCLUDED')||MS_PROLOG_INCLUDED!==true)die('Access denied');

$arResult = &$this->arResult;
$arParams = &$this->arParams;

?>
<ul class="nav nav-tabs">
	<li<?=(($arResult['VIEW']=='tree')?' class="active"':'')?>><a href="<?=$arParams['ROOT_PATH']?>?view=tree">В виде Дерева</a></li>
	<li<?=(($arResult['VIEW']=='list')?' class="active"':'')?>><a href="<?=$arParams['ROOT_PATH']?>?view=list">В виде Списка</a></li>
</ul>
<br>
<a href="<?=$arParams['ROOT_PATH'].$arParams['PATH_CLASS_ADD']?>" class="btn btn-default"><i class="glyphicon glyphicon-plus"></i> Добавить новый класс</a>
<a href="<?=$arParams['ROOT_PATH'].$arParams['PATH_OBJECT_ADD']?>" class="btn btn-default"><i class="glyphicon glyphicon-plus"></i> Добавить новый объект</a>
<br>
<?
\Ms\Core\Entity\Application::getInstance()->includeComponent(
    'ms:dobrozhil.objects.list',
    '',
    array (
        'SET_TITLE'                     => $arParams['SET_TITLE'],
        'USE_SEF'                       => $arParams['USE_SEF'],
        'VIEW'                          => $arResult['VIEW'],
        'ROOT_PATH'                     => $arParams['ROOT_PATH'],
	    'PATH_CLASS_EDIT'               => $arParams['PATH_CLASS_EDIT'],
	    'PATH_CLASS_PROPERTIES_LIST'    => $arParams['PATH_CLASS_PROPERTIES_LIST'],
	    'PATH_CLASS_METHODS_LIST'       => $arParams['PATH_CLASS_METHODS_LIST'],
	    'PATH_CLASS_METHOD_EDIT'        => $arParams['PATH_CLASS_METHOD_EDIT'],
	    'PATH_CLASS_OBJECTS_LIST'       => $arParams['PATH_CLASS_OBJECTS_LIST'],
	    'PATH_CLASS_ADD_CHILD'          => $arParams['PATH_CLASS_ADD_CHILD'],
	    'PATH_CLASS_DELETE'             => $arParams['PATH_CLASS_DELETE'],
	    'PATH_OBJECT_EDIT'              => $arParams['PATH_OBJECT_EDIT']
    )
);
?>
