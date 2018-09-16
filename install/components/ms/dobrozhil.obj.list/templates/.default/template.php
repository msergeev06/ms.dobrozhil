<?php  if(!defined('MS_PROLOG_INCLUDED')||MS_PROLOG_INCLUDED!==true)die('Access denied');
/**
 * Компонент модуля ms.dobrozhil ms:dobrozhil.obj.list
 *
 * @package Ms\Dobrozhil
 * @subpackage Entity\Components
 * @author Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright 2018 Mikhail Sergeev
 */

$arResult = &$this->arResult;
$arParams = &$this->arParams;

use Ms\Core\Lib\Loc;

Loc::includeLocFile(__FILE__);

if (!function_exists('replace'))
{
    function replace ($string,$arReplace=array())
    {
        if (!empty($arReplace))
        {
            foreach ($arReplace as $field=>$replace)
            {
                $string = str_replace('#'.$field.'#',$replace,$string);
            }
        }

        return $string;
    }
}

function msShowClasses ($arClasses, $arParams=array())
{
	?>
	<?if(!empty($arClasses)):?>
    <table class="table">
        <tbody>
		<?foreach($arClasses as $ar_class):?>
			<? $lowerClassName = strtolower($ar_class['NAME']); ?>
            <tr>
                <td valign="top">
                    <a href="#" id="link-<?=$lowerClassName?>" <?=($ar_class['SHOW'])?'data-comm="hide"':'data-comm="show"'?> onclick="return showHideClasses('<?=$lowerClassName?>');"
                            class="show-hide-link btn btn-default btn-sm expand"><?=($ar_class['SHOW'])?'-':'+'?></a>
                    <b><?=$ar_class['NAME']?></b><br>
					<?=(strlen($ar_class['NOTE'])>0)?'<i>'.$ar_class['NOTE'].'</i>':''?>
                </td>
                <td valign="top">
                    <a
                            href="<?=$arParams['ROOT_PATH']?><?=replace($arParams['PATH_CLASS_PROPERTIES_LIST'],array ('CLASS_NAME'=>$ar_class['NAME']))?>"
                            class="btn btn-default btn-sm"
                            title="<?=Loc::getCompMess('ms:dobrozhil.obj.list','properties');//Свойства?>">
                        <i class="glyphicon glyphicon-th"></i>
                    </a>
                    <a
                            href="<?=$arParams['ROOT_PATH']?><?=replace($arParams['PATH_CLASS_OBJECTS_LIST'],array ('CLASS_NAME'=>$ar_class['NAME']))?>"
                            class="btn btn-default btn-sm"
                            title="<?=Loc::getCompMess('ms:dobrozhil.obj.list','objects');//Объекты?>"
                    ><i class="glyphicon glyphicon-th-large"></i></a>
                    <a
                            href="<?=$arParams['ROOT_PATH']?><?=replace($arParams['PATH_CLASS_METHODS_LIST'],array ('CLASS_NAME'=>$ar_class['NAME']))?>"
                            class="btn btn-default btn-sm"
                            title="<?=Loc::getCompMess('ms:dobrozhil.obj.list','methods');//Методы?>"
                    ><i class="glyphicon glyphicon-th-list"></i></a>
                    <a href="<?=$arParams['ROOT_PATH']?><?=replace($arParams['PATH_CLASS_EDIT'],array ('CLASS_NAME'=>$ar_class['NAME']));?>"
                       class="btn btn-default btn-sm"
                       title="<?=Loc::getCompMess('ms:dobrozhil.obj.list','edit');//Редактировать?>">
                        <i class="glyphicon glyphicon-pencil"></i>
                    </a>
                    <a
                            href="<?=$arParams['ROOT_PATH']?><?=replace($arParams['PATH_CLASS_ADD_CHILD'],array ('CLASS_NAME'=>$ar_class['NAME']))?>"
                            class="btn btn-default btn-sm"
                            title="<?=Loc::getCompMess('ms:dobrozhil.obj.list','add_child');//Расширить?>"
                    ><i class="glyphicon glyphicon-fullscreen"></i></a>
                        <a
                                href="<?=$arParams['ROOT_PATH']?><?=replace($arParams['PATH_CLASS_DELETE'],array ('CLASS_NAME'=>$ar_class['NAME']))?>"
                                class="btn btn-default btn-sm<?=((!isset($ar_class['OBJECTS']) || empty($ar_class['OBJECTS']))&&(!isset($ar_class['CHILDREN']) && empty($ar_class['CHILDREN'])))?'':' disabled'?>"
                                title="<?=Loc::getCompMess('ms:dobrozhil.obj.list','delete');//Удалить?>"
                                onclick="return confirm('<?=Loc::getCompMess('ms:dobrozhil.obj.list','delete_confirm',array ('CLASS_NAME'=>$ar_class['NAME']));?>');"
                        ><i class="glyphicon glyphicon-remove"></i></a>
                </td>
            </tr>
            <tr class="sublist-<?=$lowerClassName?><?=($ar_class['SHOW'])?' show':' hide'?>">
                <td valign="top" colspan="2">
                    <div><b><?=Loc::getCompMess('ms:dobrozhil.obj.list','objects');?>:</b>
                        <table border="0" width="100%">
                            <tbody>
							<?if(isset($ar_class['OBJECTS']) &&!empty($ar_class['OBJECTS'])):?>
								<?foreach ($ar_class['OBJECTS'] as $ar_object):?>
                                    <tr>
                                        <td>
                                            <a href="<?=$arParams['ROOT_PATH']?><?=replace($arParams['PATH_OBJECT_EDIT'],array('OBJECT_NAME'=>$ar_object['NAME']))?>"
                                            ><?=$ar_object['NAME']?></a>
                                        </td>
                                        <td>&nbsp;<?=$ar_object['NOTE']?></td>
                                    </tr>
								<?endforeach;?>
							<?endif;?>
							<?if(isset($ar_class['METHODS']) && !empty($ar_class['METHODS'])):?>
                                <tr>
                                    <td colspan="2"><hr><b><?=Loc::getCompMess('ms:dobrozhil.obj.list','methods');?>:</b></td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <?//<small>?>
                                            <ul>
												<?foreach ($ar_class['METHODS'] as $ar_method):?>
                                                    <li>
                                                        <a href="<?=$arParams['ROOT_PATH']?><?=replace($arParams['PATH_CLASS_METHOD_EDIT'],array ('METHOD_NAME'=>$ar_method['NAME']))?>"
                                                        ><?=$ar_method['NAME']?></a> - <?=$ar_method['NOTE']?>
                                                    </li>
												<?endforeach;?>
                                            </ul>
                                        <?//</small>?>
                                    </td>
                                </tr>
							<?endif;?>
                            </tbody>
                        </table>
                    </div>
                </td>
            </tr>
			<?if(isset($ar_class['CHILDREN']) && !empty($ar_class['CHILDREN'])):?>
                <tr class="sublist-<?=$lowerClassName?><?=($ar_class['SHOW'])?' show':' hide'?>">
                    <td style="padding-left:40px" colspan="2">
						<?msShowClasses($ar_class['CHILDREN'],$arParams);?>
                    </td>
                </tr>
			<?endif;?>
		<?endforeach;?>
        </tbody>
    </table>
<?endif;?>
	<?
}

msShowClasses($arResult['DATA'],$arParams);
?>


<script type="text/javascript">
    function showHideClasses (classID)
    {
        var link = $("#link-"+classID);
        var comm = link.attr("data-comm");
        var classDel, classAdd;
        if (comm=="show")
        {
            classDel="hide";
            classAdd="show";
            link.text('-');
            link.attr('data-comm','hide');
            setCookieShowHideClasses(<?=(int)$arParams['USER_ID']?>,classID,1);
        }
        else
        {
            classDel="show";
            classAdd="hide";
            link.text('+');
            link.attr('data-comm','show');
            setCookieShowHideClasses(<?=(int)$arParams['USER_ID']?>,classID,0);
        }
        $(".sublist-"+classID).each(function()
        {
            if($(this).hasClass(classDel))
            {
                $(this).removeClass(classDel);
                $(this).addClass(classAdd);
            }
        });

        return false;
    }
    function setCookieShowHideClasses (userID, classID, value)
    {
        $.ajax({
            type: "POST",
            url: '<?=$arParams['PATH_TOOLS']?>/ajax/set_cookie.php',
            data: {
                cookieName: 'classes-view-'+classID,
                value: value,
                userID: userID
            },
            success: function(data){
                console.log(data);
                if (data.result=='OK')
                {
                    return true;
                }
                else
                {
                    return false;
                }
            },
            dataType: 'json'
        });
    }
</script>
