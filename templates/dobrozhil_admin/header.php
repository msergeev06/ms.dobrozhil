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

<?/*<div class="navbar navbar-fixed-top" role="navigation">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="sr-only">Toggle</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="#">Доброжил</a>
		</div>
		<div class="navbar-collapse collapse">
			<ul class="nav navbar-nav navbar-right">
				<li><a href="/"><i class="glyphicon glyphicon-home"></i> Главная</a></li>
				<li><a href="/menu.php"><i class="glyphicon glyphicon-th-list"></i> Меню</a></li>
				<li><a href="#"><i class="glyphicon glyphicon-flash"></i>Консоль</a></li>
				<?if($USER->isAuthorise()):?>
					<li>
						<a href="/ms/admin/auth.php?act=logout">
							<i class="glyphicon glyphicon-log-out"></i>&nbsp;
							<?=($USER->getParam('propFullName')!='')?'('.$USER->getParam('propFullName').') ':''?>Выйти</a>
					</li>
				<?else:?>
					<li>
						<a href="/ms/admin/auth.php?act=login"><i class="glyphicon glyphicon-log-in"></i> Войти</a>
					</li>
				<?endif;?>
			</ul>
			<form class="navbar-form navbar-right">
				<input type="text" class="form-control" placeholder="Найти...">
			</form>
		</div>
	</div>
</div>*/?>


<div class="navbar navbar-default" role="navigation" style="z-index:1">
	<div class="navbar-header">
		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
			<span class="sr-only">Toggle</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
		<a class="navbar-brand" href="/ms/admin/" style="padding:8px"><span class="h3"><img width="40" height="40" src="<?=SITE_TEMPLATE_PATH.'/images/logo.jpg'?>" border="0" align="absmiddle" class="img-circle"> Доброжил</span></a>
	</div>
	<div class="collapse navbar-collapse" id="responsive-menu">
        <?$application->includeComponent(
                'ms:dobrozhil.admin.menu.top',
                ''
        );?>
        <?$application->includeComponent(
                'ms:dobrozhil.admin.search.form',
                ''
        );?>
	</div>
</div>

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
		<?$application->includeComponent(
			'ms:dobrozhil.admin.menu.main',
			''
		);?>
		<div class="content col-md-9" style="vertical-align:top;">

