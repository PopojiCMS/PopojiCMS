<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<!-- Your Basic Site Informations -->
	<title><?=$this->e($page_title);?></title>
    <meta charset="utf-8" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="robots" content="index, follow" />
    <meta name="description" content="<?=$this->e($page_desc);?>" />
    <meta name="keywords" content="<?=$this->e($page_key);?>" />
    <meta http-equiv="Copyright" content="popojicms" />
    <meta name="author" content="PopojiCMS" />
    <meta http-equiv="imagetoolbar" content="no" />
    <meta name="language" content="Indonesia" />
    <meta name="revisit-after" content="7" />
    <meta name="webcrawlers" content="all" />
    <meta name="rating" content="general" />
    <meta name="spiders" content="all" />

	<!-- Social Media Meta -->
	<?php include_once DIR_CON."/component/setting/meta_social.txt";?>

    <!-- Mobile Specific Meta -->
	<meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- Stylesheets -->
	<link href="http://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="<?=$this->asset('/css/bootstrap.css')?>" type="text/css" />
	<link rel="stylesheet" href="<?=$this->asset('/css/style.css')?>" type="text/css" />
	<link rel="stylesheet" href="<?=$this->asset('/css/dark.css')?>" type="text/css" />
	<link rel="stylesheet" href="<?=$this->asset('/css/font-icons.css')?>" type="text/css" />
	<link rel="stylesheet" href="<?=$this->asset('/css/animate.css')?>" type="text/css" />
	<link rel="stylesheet" href="<?=$this->asset('/css/magnific-popup.css')?>" type="text/css" />
	<link rel="stylesheet" href="<?=$this->asset('/css/responsive.css')?>" type="text/css" />

	<!-- Favicons -->
	<link rel="shortcut icon" href="<?=BASE_URL.'/'.DIR_INC;?>/images/favicon.png" />

	<!-- Javascript -->
	<script src="https://www.google.com/recaptcha/api.js"></script>
	<script type="text/javascript" src="<?=$this->asset('/js/jquery.js')?>"></script>
	<script type="text/javascript" src="<?=$this->asset('/js/plugins.js')?>"></script>
</head>
<body class="stretched no-transition">
	<div id="wrapper" class="clearfix">
		<!-- Insert Header -->
		<?=$this->insert('header');?>

		<section id="content">
			<div class="content-wrap">
				<!-- Insert Content -->
				<?=$this->section('content');?>
			</div>
		</section>

		<!-- Insert Footer -->
		<?=$this->insert('footer');?>
	</div>

	<div id="gotoTop" class="icon-angle-up"></div>

	<!-- Javascript -->
	<script type="text/javascript" src="<?=$this->asset('/js/functions.js')?>"></script>
</body>
</html>