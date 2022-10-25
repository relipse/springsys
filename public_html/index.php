<?php
require_once(__DIR__.'/../inc/autoloader.inc.php');

$tpl = new \SpringSys\Template();

$title = 'Home';
$tpl->header($title, false);
?>
<?php
$tpl->footer();;
