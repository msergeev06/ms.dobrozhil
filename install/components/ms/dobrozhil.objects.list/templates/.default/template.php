<?php  if(!defined('MS_PROLOG_INCLUDED')||MS_PROLOG_INCLUDED!==true)die('Access denied');

$arResult = &$this->arResult;

//=\MSergeev\Packages\Kuzmahome\Lib\Objects::getTreeView()
?>
<table class="table">
	<tbody>
	<tr>
		<td valign="top">
			<a
				href="#"
				id="link-#LOWER_CLASS_NAME#"
				data-comm="show"
				onclick="return showHideClasses('#LOWER_CLASS_NAME#');"
				class="show-hide-link btn btn-default btn-sm expand">+</a>
			<b>#CLASS_NAME#</b>
			<i>#NOTE#</i>
		</td>
		<td valign="top" align="right">
			<a
				href="#PATH_CLASS_EDIT#?class=#CLASS_NAME#"
				class="btn btn-default btn-sm"
				title="Редактировать"
			><i class="glyphicon glyphicon-pencil"></i></a>
			<a
				href="#PATH_CLASS_PROPERTIES_LIST#?class=#CLASS_NAME#"
				class="btn btn-default btn-sm"
				title="Свойства"
			><i class="glyphicon glyphicon-th"></i></a>
			<a
				href="#PATH_CLASS_METHODS_LIST#?class=#CLASS_NAME#"
				class="btn btn-default btn-sm"
				title="Методы"
			><i class="glyphicon glyphicon-th-list"></i></a>
			<a
				href="#PATH_CLASS_OBJECTS_LIST#?class=#CLASS_NAME#"
				class="btn btn-default btn-sm"
				title="Объекты"
			><i class="glyphicon glyphicon-th-large"></i></a>
			<a
				href="#PATH_CLASS_ADD_CHILD#?class=#CLASS_NAME#"
				class="btn btn-default btn-sm"
				title="Расширить"
			><i class=""></i>Расширить</a>
			<a
				href="#PATH_CLASS_DELETE#?deleteClass=#CLASS_NAME#"
				class="btn btn-default btn-sm"
				title="Удалить"
				onclick="return confirm('Вы действительно хотите удалить класс #CLASS_NAME#?');"
			><i class="glyphicon glyphicon-remove"></i></a>
		</td>
	</tr>
	<tr class="sublist-#LOWER_CLASS_NAME# show">
		<td valign="top" colspan="2">
			<div>
				<table border="0">
					<tbody>
					<tr>
						<td>
							<a
								href="#PATH_CLASS_OBJECT_EDIT#?class=#CLASS_NAME#&object=#OBJECT_NAME#"
							>#OBJECT_NAME#</a>
						</td>
						<td>&nbsp;#OBJECT_NOTE#</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>
							<small>
								<ul>
									<li>
										<a
											href="#PATH_CLASS_METHOD_EDIT#?class=#CLASS_NAME#&method=#METHOD_NAME#"
										>#METHOD_NAME#</a> - #METHOD_NOTE#
									</li>
								</ul>
							</small>
						</td>
					</tr>
					</tbody>
				</table>
			</div>
		</td>
	</tr>
	<tr class="sublist-#LOWER_CLASS_NAME# show">
		<td style="padding-left:40px" colspan="2">
			#GET_LIST_PARENT#
		</td>
	</tr>
	</tbody>
</table>

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
