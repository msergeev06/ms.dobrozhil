<?php if(!defined('MS_PROLOG_INCLUDED')||MS_PROLOG_INCLUDED!==true)die('Access denied');
$arResult = &$this->arResult;
?>
<div class="left-menu col-md-3 sidebar" style="vertical-align:top;background-color: #f5f5f5;">
	<?if(isset($arResult['GENERAL']) && !empty($arResult['GENERAL'])):?>
	<?foreach($arResult['GENERAL'] as $generalName=>$arGeneral):?>
		<? //msEchoVar($generalName); msDebug($arGeneral); ?>
		<ul class="nav nav-sidebar">
			<li class="nav-header"><a href="#"><?=$arGeneral['NAME']?></a></li>
			<?if(isset($arResult['MENU']['SORT'][$generalName]) && !empty($arResult['MENU']['SORT'][$generalName])):?>
			<?foreach ($arResult['MENU']['SORT'][$generalName] as $sort=>$itemID):?>
				<?if(isset($arResult['MENU']['LIST'][$generalName][$itemID])):?>
						<li class="menu-child menu-<?=$arGeneral['CODE']?>">
							<a href="/ms/admin/<?=$arResult['MENU']['LIST'][$generalName][$itemID]['url']?>" title="<?=$arResult['MENU']['LIST'][$generalName][$itemID]['title']?>">
								<?=$arResult['MENU']['LIST'][$generalName][$itemID]['text']?>
							</a>
						</li>
				<?endif;?>
			<?endforeach;?>
			<?endif;?>
		</ul>
	<?endforeach;?>
	<?endif;?>
</div>

