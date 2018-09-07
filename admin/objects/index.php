<? include_once($_SERVER['DOCUMENT_ROOT']."/ms/header.php");
$app = \Ms\Core\Entity\Application::getInstance();
$app->setTitle('Классы и объекты');
$app->includeComponent('ms:dobrozhil.objects','');
?>



<? include_once($_SERVER['DOCUMENT_ROOT']."/ms/footer.php"); ?>
