<?php
session_start();
require_once '../vqmod/vqmod.php';
VQMod::bootup();
include_once "../po-includes/core/core.php";
if (empty($_SESSION['namauser']) AND empty($_SESSION['passuser']) AND empty($_SESSION['login'])) {
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
    <title>Log In Panel</title>
    <link rel="shortcut icon" href="../<?=DIR_INC;?>/images/favicon.png" />

	<link type="text/css" rel="stylesheet" href="../<?=DIR_INC;?>/css/bootstrap.min.css" />
	<link type="text/css" rel="stylesheet" href="../<?=DIR_INC;?>/css/font-awesome.min.css" />
	<link type="text/css" rel="stylesheet" href="../<?=DIR_INC;?>/css/login.css" />
	<link type="text/css" rel="stylesheet" href="../<?=DIR_INC;?>/css/patternlock.css" />

	<script type="text/javascript" src="../<?=DIR_INC;?>/js/jquery/jquery-2.1.4.min.js"></script>
	<script type="text/javascript" src="../<?=DIR_INC;?>/js/bootstrap/bootstrap.min.js"></script>

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
	<section id="login">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="form-wrap">
						<div class="col-md-12 text-center">
							<img src="../<?=DIR_INC;?>/images/logo.png" class="logo" width="100" />
						</div>
						<?php
							include_once VQMod::modCheck("route.php");
						?>
						<div class="col-md-12 text-center" id="footer">
							<p><?=CONF_STRUCTURE;?> &copy; 2013-2016. MIT License</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<script type="text/javascript" src="../<?=DIR_INC;?>/js/patternlock/patternLock.js"></script>
	<script type="text/javascript" src="../<?=DIR_INC;?>/js/login-core.js"></script>
</body>
</html>
<?php
} else {
	header('location:admin.php?mod=home');
}
?>