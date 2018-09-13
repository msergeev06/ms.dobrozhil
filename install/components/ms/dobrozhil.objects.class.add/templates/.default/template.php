<?php if(!defined('MS_PROLOG_INCLUDED')||MS_PROLOG_INCLUDED!==true)die('Access denied');

use Ms\Core\Entity\Form;

$arResult = &$this->arResult;

\Ms\Core\Entity\Application::getInstance()->includeComponent(
	'ms:core.form',
	'',
	array (
		'FORM_SUBMIT_NAME' => 'Добавить новый класс',
		'FORM_FIELDS' => array (
			new Form\InputText(
				'Имя класса',
				'имя класса может состоять из заглавных и строчных латинских символов, цифр и знака подчеркивание',
				'NAME',
				'',
				true,
				array('\Ms\Dobrozhil\Lib\Classes','checkClassAddNameField')
			),
			new Form\InputText(
				'Описание класса',
				'краткое описание назначения класса',
				'NOTE',
				'',
				false,
				null
			),
			new Form\Select(
				'Родительский класс',
				'создаваемый класс унаследует у родителя все свойства и методы',
				'PARENT_CLASS',
				null,
				false,
				'---без родителя---',
				$arResult['CLASSES_LIST'],
				null
			)
		)/*,
		'FORM_HANDLER' => '',
		'REDIRECT_IF_OK' => ''*/
	)
);
?>

<!--<form action="" method="post" enctype="multipart/form-data" name="frmEdit" class="form-horizontal">
	<div class="form-group ">
		<label class="col-lg-4 control-label">Родительский класс: <a href="http://majordomo.smartliving.ru/Hints/parent_class?skin=hint" class="wiki_hint fancybox.iframe"><i class="glyphicon glyphicon-info-sign"></i></a></label>
		<div class="col-lg-4">
			<select name="parent_id" class="form-control">
				<option value="0"<?/*if(!isset($_POST['parent_id'])):*/?> selected<?/*endif;*/?>>- no -</option>
				<?/*foreach($arClasses as $arClass):*/?>
					<option value="<?/*=$arClass['ID']*/?>"<?/*if(isset($_POST['parent__id']) && $_POST['parent_id']==$arClass['ID']):*/?> selected<?/*endif;*/?>><?/*=$arClass['TITLE']*/?></option>
				<?/*endforeach;*/?>
			</select>
		</div>
	</div>
	<div class="form-group ">
		<label class="col-lg-4 control-label">Название:<span style="color:red;">*</span> <a href="http://majordomo.smartliving.ru/Hints/title?skin=hint" class="wiki_hint fancybox.iframe"><i class="glyphicon glyphicon-info-sign"></i></a></label>
		<div class="col-lg-4"><input type="text" class="form-control " name="title" value="<?/*if(isset($_POST['title'])):*/?><?/*=$_POST['title']*/?><?/*endif;*/?>" required="true"></div>
	</div>
	<div class="form-group ">
		<label class="col-lg-4 control-label">Описание: <a href="http://majordomo.smartliving.ru/Hints/description?skin=hint" class="wiki_hint fancybox.iframe"><i class="glyphicon glyphicon-info-sign"></i></a></label>
		<div class="col-lg-4"><textarea name="description" rows="3" class="form-control"><?/*if(isset($_POST['description'])):*/?><?/*=$_POST['description']*/?><?/*endif;*/?></textarea></div>
	</div>
	<div class="form-group">
		<div class="col-lg-offset-1 col-lg-5">
			<input class="btn btn-default btn-primary" type="submit" name="subm" value="Добавить">
			&nbsp;
			<a href="<?/*=$curDir*/?>" class="btn btn-default">Отмена</a>
		</div>
	</div>
	<input type="hidden" name="action" value="1">
</form>
-->