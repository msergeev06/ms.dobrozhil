<?php if(!defined('MS_PROLOG_INCLUDED')||MS_PROLOG_INCLUDED!==true)die('Access denied');

$arResult = &$this->arResult;

?>
<ul class="nav nav-tabs">
	<li<?=(($arResult['VIEW']=='tree')?' class="active"':'')?>><a href="<?=$arResult['CUR_PAGE']?>?view=tree">В виде Дерева</a></li>
	<li<?=(($arResult['VIEW']=='list')?' class="active"':'')?>><a href="<?=$arResult['CUR_PAGE']?>?view=list">В виде Списка</a></li>
</ul>
<br>
<a href="<?=$arResult['CUR_DIR']?>/class_add.php" class="btn btn-default"><i class="glyphicon glyphicon-plus"></i> Добавить новый класс</a>
<a href="<?=$arResult['CUR_DIR']?>/object_add.php" class="btn btn-default"><i class="glyphicon glyphicon-plus"></i> Добавить новый объект</a>
<br>
<?if($arResult['VIEW']=='tree'):?>
	<?//=\MSergeev\Packages\Kuzmahome\Lib\Objects::getTreeView()?>
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
                setCookieShowHideClasses(<?=intval($arResult['USER']->getID())?>,classID,1);
            }
            else
            {
                classDel="show";
                classAdd="hide";
                link.text('+');
                link.attr('data-comm','show');
                setCookieShowHideClasses(<?=intval($arResult['USER']->getID())?>,classID,0);
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
                url: '<?=$arResult['PATH_TOOLS']?>ajax/set_cookie.php',
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
<?elseif($arResult['VIEW']=='list'):?>
<?endif;?>
