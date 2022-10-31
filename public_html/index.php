<?php
/**
 * Main Home Page
 *  - main page with links to other pages
 *
 * Code Challenge
 * @author Jim A Kinsman <relipse@gmail.com>
 * @copyright 2022 Jim A Kinsman
 */
require_once(__DIR__.'/../inc/autoloader.inc.php');

$tpl = new \SpringSys\Template();

$title = 'Home';
$tpl->header($title, false);
?>
<?php
$tpl->footer();
