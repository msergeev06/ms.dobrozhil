<? include_once($_SERVER['DOCUMENT_ROOT']."/ms/header.php");

\Ms\Core\Entity\Application::getInstance()->includeComponent(
	'ms:dobrozhil.objects',
	''
);

include_once($_SERVER['DOCUMENT_ROOT']."/ms/footer.php");
