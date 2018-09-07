<?php if(!defined('MS_PROLOG_INCLUDED')||MS_PROLOG_INCLUDED!==true)die('Access denied');

$arResult = &$this->arResult;

?>
<ul class="nav navbar-nav navbar-right">
	<li>
		<a href="/"><i class="glyphicon glyphicon-home"></i> Главная</a>
	</li>
	<li>
		<a href="/menu.php" target="_blank"><i class="glyphicon glyphicon-th-list"></i> Меню</a>
	</li>
	<li>
		<a href="#"><i class="glyphicon glyphicon-flash"></i> Консоль</a>
	</li>
	<li>
		<a href="#"><i class="glyphicon glyphicon-dashboard"></i> Логи</a>
	</li>
	<li>
		<a href="//docs.dobrozhil.ru" target="_blank"><i class="glyphicon glyphicon-globe"></i> Wiki</a>
	</li>
	<?if($arResult['IS_AUTH']):?>
		<li>
			<a href="/ms/admin/auth.php?act=logout">
				<i class="glyphicon glyphicon-log-out"></i>&nbsp;<?=($arResult['SHOW_NAME'])?'('.$arResult['NAME'].') ':''?>Выйти
			</a>
		</li>
	<?else:?>
		<li>
			<a href="/ms/admin/auth.php?act=login">
				<i class="glyphicon glyphicon-log-in"></i>&nbsp;Войти
			</a>
		</li>
	<?endif;?>
	<li>&nbsp;&nbsp;</li>
</ul>

