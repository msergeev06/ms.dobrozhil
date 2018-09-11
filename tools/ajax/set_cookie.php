<?php
define('NO_HTTP_AUTH',true);
require ($_SERVER['DOCUMENT_ROOT'].'/ms/core/prolog_before.php');
header('Content-Type: application/json');
$USER = \Ms\Core\Entity\Application::getInstance()->getUser();

if (isset($_POST['cookieName']) && isset($_POST['value']) && isset($_POST['userID']))
{
	if ($USER->setUserCookie($_POST['cookieName'],$_POST['value'],intval($_POST['userID'])))
	{
		$res = 'OK';
	}
	else
	{
		$res = 'ERROR';
	}

	echo json_encode(array('result'=>$res));
}
