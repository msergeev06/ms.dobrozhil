<?php if(!defined('MS_PROLOG_INCLUDED')||MS_PROLOG_INCLUDED!==true)die('Access denied');
/**
 * Компонент модуля ms.dobrozhil ms:dobrozhil.obj
 *
 * @package Ms\Dobrozhil
 * @subpackage Entity\Components
 * @author Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright 2018 Mikhail Sergeev
 */

\Ms\Core\Entity\Application::getInstance()->includeComponent(
    'ms:dobrozhil.obj.class.add',
    '',
    array(
    	'SET_TITLE'         => $this->arParams['SET_TITLE'],
	    'ADD_NAV_CHAIN'     => $this->arParams['ADD_NAV_CHAIN'],
	    'ROOT_PATH'         => $this->arParams['ROOT_PATH'],
	    'PATH_CLASS_ADD'    => $this->arParams['PATH_CLASS_ADD']
    )
);
?>
