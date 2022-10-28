<?php
require_once(__DIR__.'/../inc/autoloader.inc.php');

$cfg = new \SpringSys\Config();
$db = new SpringSys\Db($cfg);
$tpl = new \SpringSys\Template($cfg);
$emp = new \SpringSys\Employee($db);
$title = 'Employees';
$tpl->header($title);
?>
List of Employees
<?php
echo array2table($emp->getAll());
$tpl->footer();
