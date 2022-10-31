<?php
/**
 * Header template
 * @see footer.tpl.php
 *
 * Code Challenge
 * @author Jim A Kinsman <relipse@gmail.com>
 * @copyright 2022 Jim A Kinsman
 */
//variables used in this template
$title = $title ?? '';
$fulltitle = $fulltitle ?? $title . ' - '.$this->cfg->get('sitename');
$baseurl = $baseurl ?? $this->cfg->get('baseurl');
$show_h1 = $show_h1 ?? true;
?>
<!doctype HTML>
<html lang="en">
<head>
    <title><?=$fulltitle?></title>
    <link href="<?=$baseurl?>/css/all.css" rel="stylesheet" type="text/css" />
    <link rel="icon" type="image/x-icon" href="<?=$baseurl?>/images/favicon.ico">
    <script src="<?=$baseurl?>/js/jquery-3.6.1.min.js"></script>
    <script src="<?=$baseurl?>/js/all.js"></script>
</head>
<body>
<header>
    <?php $this->load('nav', ['baseurl'=>$baseurl]);?>
</header>
<?php if ($show_h1): ?>
    <h1><?=$title?></h1>
<?php endif; ?>
<?php
  if ($this->error_msg):
?>
<div class="error box">
  <?=$this->error_msg?>
</div>
<?php
  elseif ($this->success_msg):
?>
<div class="success box">
  <?=$this->success_msg?>
</div>
<?php
  endif;
?>



