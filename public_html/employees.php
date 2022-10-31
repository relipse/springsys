<?php
/**
 * Employees Page
 *  - show all employees with the company they are in
 *
 * Code Challenge
 * @author Jim A Kinsman <relipse@gmail.com>
 * @copyright 2022 Jim A Kinsman
 */
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
