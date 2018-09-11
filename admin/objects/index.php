<? include_once($_SERVER['DOCUMENT_ROOT']."/ms/header.php");

\Ms\Core\Entity\Application::getInstance()->includeComponent(
	'ms:dobrozhil.objects',
	''//,
//	array ()
/*	array (
		'SET_TITLE' => 'Y',
		'USE_SEF' => 'Y',
		'ROOT_PATH' => '/ms/admin/objects/'
	)*/
);

include_once($_SERVER['DOCUMENT_ROOT']."/ms/footer.php");
