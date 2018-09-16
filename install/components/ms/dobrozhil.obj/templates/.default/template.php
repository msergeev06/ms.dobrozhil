<?php if(!defined('MS_PROLOG_INCLUDED')||MS_PROLOG_INCLUDED!==true)die('Access denied');
/**
 * Компонент модуля ms.dobrozhil ms:dobrozhil.obj
 *
 * @package Ms\Dobrozhil
 * @subpackage Entity\Components
 * @author Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright 2018 Mikhail Sergeev
 */

use Ms\Core\Lib\Loc;

Loc::includeLocFile(__FILE__);

$arResult = &$this->arResult;
$arParams = &$this->arParams;

?>
<ul class="nav nav-tabs">
	<li<?=(($arResult['VIEW']=='tree')?' class="active"':'')?>>
        <a href="<?=$arParams['ROOT_PATH']?>?view=tree">
            <?=Loc::getCompMess('ms:dobrozhil.obj','view_as_tree')?>
        </a>
    </li>
	<li<?=(($arResult['VIEW']=='list')?' class="active"':'')?>>
        <a href="<?=$arParams['ROOT_PATH']?>?view=list">
	        <?=Loc::getCompMess('ms:dobrozhil.obj','view_as_list')?>
        </a>
    </li>
</ul>
<br>
<a href="<?=$arParams['ROOT_PATH'].$arParams['PATH_CLASS_ADD']?>" class="btn btn-default">
    <i class="glyphicon glyphicon-plus"></i>&nbsp;<?=Loc::getCompMess('ms:dobrozhil.obj','add_new_class')?>
</a>
<a href="<?=$arParams['ROOT_PATH'].$arParams['PATH_OBJECT_ADD']?>" class="btn btn-default">
    <i class="glyphicon glyphicon-plus"></i>&nbsp;<?=Loc::getCompMess('ms:dobrozhil.obj','add_new_object')?>
</a>
<br>
<?
\Ms\Core\Entity\Application::getInstance()->includeComponent(
    'ms:dobrozhil.obj.list',
    '',
    array (
        'SET_TITLE'                     => $arParams['SET_TITLE'],
	    'ADD_NAV_CHAIN'                 => $arParams['ADD_NAV_CHAIN'],
        'USE_SEF'                       => $arParams['USE_SEF'],
        'VIEW'                          => $arResult['VIEW'],
	    'ADMIN_PATH'                    => $arParams['ADMIN_PATH'],
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
