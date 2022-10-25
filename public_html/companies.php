<?php
require_once(__DIR__.'/../inc/autoloader.inc.php');

$cfg = new \SpringSys\Config();
$db = new SpringSys\Db($cfg);
$tpl = new \SpringSys\Template($cfg);

$title = 'Companies';
$tpl->header($title);
?>
List of Companies with number of Employees
<?php
$comp = new \SpringSys\Company($db);

$companies = $comp->getAll();

echo array2table($companies);

$tpl->footer();;
