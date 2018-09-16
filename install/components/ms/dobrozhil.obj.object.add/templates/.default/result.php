<?php if(!defined('MS_PROLOG_INCLUDED')||MS_PROLOG_INCLUDED!==true)die('Access denied');
/**
 * Компонент модуля ms.dobrozhil ms:dobrozhil.obj.object.add
 *
 * @package Ms\Dobrozhil
 * @subpackage Entity\Components
 * @author Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright 2018 Mikhail Sergeev
 */

use Ms\Core\Lib\Loc;

Loc::includeLocFile(__FILE__);

$arResult = &$this->arResult;

if ($arResult['RESULT']=='success'):?>
	<div class="text-success">
        <?=Loc::getCompMess('ms:dobrozhil.obj.object.add','success')?>
    </div>
<?else:?>
	<div class="text-danger">
		<?=Loc::getCompMess('ms:dobrozhil.obj.object.add','error')?>
    </div>
<?endif;?>
