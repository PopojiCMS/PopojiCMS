<?php
session_start();
/**
 * Memanggil library utama
 *
 * Call main library
 *
*/
require_once 'vqmod/vqmod.php';
VQMod::bootup();

include_once VQMod::modCheck("po-includes/core/core.php");
$core = new PoCore();

/**
 * Alihkan ke index.php jika mode pemeliharaan tidak aktif
 *
 * Redirect to index.php if maintenance mode not activated
 *
*/
if ($core->posetting[16]['value'] == 'N') {
	header('location:./');
} else {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="imagetoolbar" content="no" />
    <meta http-equiv="Copyright" content="<?=CONF_STRUCTURE;?>" />
	<meta name="robots" content="index, follow" />
    <meta name="description" content="Log In Panel <?=CONF_STRUCTURE;?>" />
    <meta name="generator" content="<?=CONF_STRUCTURE;?> <?=CONF_VER;?>.<?=CONF_BUILD;?>" />
    <meta name="author" content="Dwira Survivor" />
    <meta name="language" content="Indonesia" />
    <meta name="revisit-after" content="7" />
    <meta name="webcrawlers" content="all" />
    <meta name="rating" content="general" />
    <meta name="spiders" content="all" />
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1.0" />
    <!--[if gt IE 8]>
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <![endif]-->
    <title>Under Maintenance</title>
    <link rel="shortcut icon" href="<?=DIR_INC;?>/images/favicon.png" />

	<link type="text/css" rel="stylesheet" href="<?=DIR_INC;?>/css/bootstrap.min.css" />
	<link type="text/css" rel="stylesheet" href="<?=DIR_INC;?>/css/font-awesome.min.css" />
	<link type="text/css" rel="stylesheet" href="<?=DIR_INC;?>/css/login.css" />
	<link type="text/css" rel="stylesheet" href="<?=DIR_INC;?>/css/patternlock.css" />

	<script type="text/javascript" src="<?=DIR_INC;?>/js/jquery/jquery-2.1.4.min.js"></script>
	<script type="text/javascript" src="<?=DIR_INC;?>/js/bootstrap/bootstrap.min.js"></script>

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

	<style>
		body { background-color: #f1f1f1; text-align: center; padding: 150px 30px; }
		h1 { letter-spacing: -1px; line-height: 60px; font-size: 40px; }
		body { font: 20px "Helvetica Neue", Helvetica, Arial, sans-serif; color: #666666; }
		article { display: block; text-align: left; margin: 0 auto; }
		a { color: #4183c4; text-decoration: none; }
		a:hover { color: #666666; text-decoration: none; }
		.text-small { font-size: 18px; }
	</style>
</head>
<body>
	<article class="col-md-8 col-md-offset-2 text-center">
		<h1>We&rsquo;ll be back soon!</h1>
		<div>
			<p>Sorry for the inconvenience but we&rsquo;re performing some maintenance at the moment. If you need to you can always <a href="mailto:<?=$core->posetting[5]['value'];?>">contact us</a>, otherwise we&rsquo;ll be back online shortly!</p>
			<p class="text-small">&mdash; <?=$core->posetting[0]['value'];?></p>
		</div>
	</article>
</body>
</html>
<?php } ?>