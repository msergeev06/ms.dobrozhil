<?if(!defined('MS_PROLOG_INCLUDED')||MS_PROLOG_INCLUDED!==true)die('Access denied');
$application = \Ms\Core\Entity\Application::getInstance();
//define("SHOW_SQL_WORK_TIME",true);
$application->includePlugin('ms.jquery');
$application->includePlugin('ms.bootstrap-css-min');
$application->includePlugin('ms.bootstrap-js-min');
$application->addCSS(dirname(__FILE__).'/css/style.css');
\Ms\Core\Lib\Loader::includeModule('ms.dobrozhil');
$USER = $application->getUser();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Доброжил - <?$application->showTitle("Администрирование");?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<?=$application->showMeta()?>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
</head>
<body>
<nav class="navbar navbar-default" role="navigation" style="z-index:1">
	<div class="navbar-header">
		<a class="navbar-brand" href="/ms/admin/" style="padding:8px"><span class="h3"><img width="40" height="40" src="<?=SITE_TEMPLATE_PATH.'/images/logo.jpg'?>" border="0" align="absmiddle" class="img-circle"> Доброжил</span></a>
	</div>
	<div class="collapse navbar-collapse" id="responsive-menu">
		<ul class="nav navbar-nav navbar-right">
			<li>
				<a href="/"><i class="glyphicon glyphicon-home"></i> Главная</a>
			</li>
			<li>
				<a href="/menu.php" target="_blank"><i class="glyphicon glyphicon-th-list"></i> Меню</a>
			</li>
			<li>
				<a href="#" <?//onclick="$(&quot;#console&quot;).toggle();return false;"?>><i class="glyphicon glyphicon-flash"></i> Console</a>
			</li>
			<?/*
				<li>
					<a href="<?=$pathAdmin?>"><i class="glyphicon glyphicon-dashboard"></i> X-Ray</a>
				</li>
				<li>
					<a href="#" <?//onclick="return openModalTWindow('tdWiki', 'Wiki', '/panel/popup/app_tdwiki.html', 1000, 800)"?>><i class="glyphicon glyphicon-globe"></i> Wiki</a>
				</li>
				*/?>
			<?if($USER->isAuthorise()):?>
				<li>
					<a href="/ms/admin/auth.php?act=logout"><i class="glyphicon glyphicon-log-out"></i>&nbsp;(<?=$USER->getParam('propFullName')?>) Выйти</a>
				</li>
			<?else:?>
				<li>
					<a href="/ms/admin/auth.php?act=login"><i class="glyphicon glyphicon-log-in"></i> Войти</a>
				</li>
			<?endif;?>
			<li>&nbsp;&nbsp;</li>

		</ul>
	</div>
</nav>
<div class="container-fluid">
	<div id="console" style="display:none">
		<script language="javascript">
            /*
			var cmd='';
			function sendConsoleCommand() {
				cmd=$('#command').val();
				$('#command').val('');

				var url="/admin.php?pd=pz_&md=panel&inst=&";
				url+='&ajax_panel=1&op=console&command='+encodeURIComponent(cmd);

				$.ajax({
					url: url
				}).done(function(data) {
					$('#console_output').html('<pre>Command: <b>'+cmd+'</b><br/>Result:<br/>'+data+'</pre>');
				});


				return false;
			}
			*/
		</script>
		<form class="form-inline" role="form" action="" method="post"<?// onsubmit="return sendConsoleCommand();"?>>
			<div class="form-group col-lg-6">
				<input type="text" name="command" value="" id="command" class="form-control" placeholder="Code, method, expression...">
			</div>
			<input type="submit" name="submit" value="Send" class="btn btn-default">
			<input type="hidden" name="pd" value="pz_">
			<input type="hidden" name="md" value="panel">
			<input type="hidden" name="inst" value="">
		</form><!-- modified -->
		&nbsp;
		<div id="console_output" style="margin-left:15px">&nbsp;</div>
	</div>
	<div class="row">
		<div class="left-menu col-md-3 sidebar" style="vertical-align:top;background-color: #f5f5f5;">
			<?if($USER->isAdmin()):?>
				<ul class="nav nav-sidebar">
					<?//<li class="active"><a href="#">Overview</a></li>?>
					<li class="nav-header"><a href="#">Объекты</a></li>
					<li class="menu-child menu-objects"><a href="#">Меню управления</a></li>
					<li class="menu-child menu-objects"><a href="/ms/admin/objects/">Объекты</a></li>
					<li class="menu-child menu-objects"><a href="/ms/admin/patterns/">Шаблоны поведения</a></li>
					<li class="menu-child menu-objects"><a href="#">Сцены</a></li>
					<li class="menu-child menu-objects"><a href="/ms/admin/scripts/">Сценарии</a></li>
					<li class="menu-child menu-objects"><a href="/ms/admin/webvars/">Веб-переменные</a></li>
				</ul>
				<ul class="nav nav-sidebar">
					<li class="nav-header" id="menu-gadjet"><a href="#">Устройства</a></li>
					<li class="menu-child menu-gadjet"><a href="#">Bluetooth-устройства</a></li>
					<li class="menu-child menu-gadjet"><a href="#">ModBus</a></li>
					<li class="menu-child menu-gadjet"><a href="#">MQTT</a></li>
					<li class="menu-child menu-gadjet"><a href="#">1-Wire</a></li>
					<li class="menu-child menu-gadjet"><a href="#">Устройства Online</a></li>
					<li class="menu-child menu-gadjet"><a href="#">SNMP</a></li>
					<li class="menu-child menu-gadjet"><a href="#">USB-устройства</a></li>
					<li class="menu-child menu-gadjet"><a href="#">Папки</a></li>
					<li class="menu-child menu-gadjet"><a href="#">Z-Wave</a></li>
					<li class="menu-child menu-gadjet"><a href="#">KNX</a></li>
					<li class="menu-child menu-gadjet"><a href="#">MegaD</a></li>
					<li class="menu-child menu-gadjet"><a href="#">Noolite</a></li>
					<li class="menu-child menu-gadjet"><a href="#">Orvibo</a></li>
				</ul>
				<ul class="nav nav-sidebar">
					<li class="nav-header" id="menu-software"><a href="#">Приложения</a></li>
					<li class="menu-child menu-software"><a href="#">Календарь</a></li>
					<li class="menu-child menu-software"><a href="#">GPS-трекер</a></li>
					<li class="menu-child menu-software"><a href="#">Медиа</a></li>
					<li class="menu-child menu-software"><a href="#">Плеер</a></li>
					<li class="menu-child menu-software"><a href="#">Продукты</a></li>
					<li class="menu-child menu-software"><a href="#">Цитаты</a></li>
					<li class="menu-child menu-software"><a href="#">Присл. ссылки</a></li>
					<li class="menu-child menu-software"><a href="#">Блокнот</a></li>
					<li class="menu-child menu-software"><a href="#">Каналы RSS</a></li>
					<li class="menu-child menu-software"><a href="#">Radio 101.Ru</a></li>
					<li class="menu-child menu-software"><a href="#">Telegram</a></li>
					<li class="menu-child menu-software"><a href="#">Yandex TTS</a></li>
					<li class="menu-child menu-software"><a href="#">Погода от OpenWeatherMap</a></li>
				</ul>
				<ul class="nav nav-sidebar">
					<li class="nav-header" id="menu-setup"><a href="#">Установки</a></li>
					<li class="menu-child menu-setup"><a href="#">Домашние страницы</a></li>
					<li class="menu-child menu-setup"><a href="/ms/admin/locations/">Расположение</a></li>
					<li class="menu-child menu-setup"><a href="#">Мои блоки</a></li>
					<li class="menu-child menu-setup"><a href="#">Правила безопасности</a></li>
					<li class="menu-child menu-setup"><a href="#">Общие настройки</a></li>
					<li class="menu-child menu-setup"><a href="#">Звуковые файлы</a></li>
					<li class="menu-child menu-setup"><a href="/ms/admin/terminals/">Терминалы</a></li>
					<li class="menu-child menu-setup"><a href="/ms/admin/textfiles/">Текстовые файлы</a></li>
					<li class="menu-child menu-setup"><a href="#">Пользователи</a></li>
				</ul>
				<ul class="nav nav-sidebar">
					<li class="nav-header" id="menu-system"><a href="#">Настройки системы</a></li>
					<li class="menu-child menu-system"><a href="#">Модули</a></li>
					<li class="menu-child menu-system"><a href="#">Резервное копирование</a></li>
					<li class="menu-child menu-system"><a href="#">Ошибки системы</a></li>
					<li class="menu-child menu-system"><a href="#">Журнал действий</a></li>
					<li class="menu-child menu-system"><a href="#">X-Ray</a></li>
				</ul>
			<?endif;?>
		</div>
		<div class="content col-md-9" style="vertical-align:top;">

