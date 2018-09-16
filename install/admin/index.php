<? include_once($_SERVER['DOCUMENT_ROOT']."/ms/header.php");
$app = \Ms\Core\Entity\Application::getInstance();
$app->setTitle('Панель администратора');
$app->getBreadcrumbs()->addNavChain('Панель','/ms/admin/','admin_panel');
?>



<? include_once($_SERVER['DOCUMENT_ROOT']."/ms/footer.php"); ?>
