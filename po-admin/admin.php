<?php
session_start();
require_once '../vqmod/vqmod.php';
VQMod::bootup();
include_once "../po-includes/core/core.php";
if ($_SESSION['login'] == 0) {
	session_destroy();
	header('location:index.php');
} else {
	if (empty($_SESSION['namauser']) AND empty($_SESSION['passuser']) AND $_SESSION['login'] == 0) {
		header('location:index.php');
	}else{
		$timeout = new PoTimeout;
		if (!$timeout->check_login()) {
			$_SESSION['login'] = 0;
		}
		$selectlang = (isset($_COOKIE['lang']) ? $_COOKIE['lang'] : 'id');
		include_once VQMod::modCheck("../".DIR_CON."/lang/main/".$selectlang.".php");
		if(isset($_POST['language'])) {
			setcookie('lang', $_POST['language'], 1719241200, '/');
			$langcore = new PoCore();
			$langcore->poflash->success($GLOBALS['_']['setting_message'], 'admin.php?mod=setting');
		}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="imagetoolbar" content="no" />
    <meta http-equiv="Copyright" content="<?=CONF_STRUCTURE;?>" />
    <meta name="robots" content="index, follow" />
    <meta name="description" content="Administrator <?=CONF_STRUCTURE;?>" />
    <meta name="generator" content="<?=CONF_STRUCTURE;?> <?=CONF_VER;?>.<?=CONF_BUILD;?>" />
    <meta name="author" content="Dwira Survivor" />
    <meta name="language" content="Indonesia" />
    <meta name="revisit-after" content="7" />
    <meta name="webcrawlers" content="all" />
    <meta name="rating" content="general" />
    <meta name="spiders" content="all" />
	<title><?php $titcomponame = ucfirst($_GET['mod']); if ($_SESSION['leveluser']=='1' OR $_SESSION['leveluser']=='2'){ echo $titcomponame; ?> - Administrator Dashboard<?php }else{ echo $titcomponame; ?> - Member Dashboard<?php } ?></title>
	<link rel="shortcut icon" href="../<?=DIR_INC;?>/images/favicon.png" />

	<link type="text/css" rel="stylesheet" href="../<?=DIR_INC;?>/css/bootstrap.min.css" />
	<link type="text/css" rel="stylesheet" href="../<?=DIR_INC;?>/css/font-awesome.min.css" />
	<link type="text/css" rel="stylesheet" href="../<?=DIR_INC;?>/css/dashboard.css" />
	<link type="text/css" rel="stylesheet" href="../<?=DIR_INC;?>/css/responsive.css" />
	<link type="text/css" rel="stylesheet" href="../<?=DIR_INC;?>/css/dataTables.bootstrap.min.css" />
	<link type="text/css" rel="stylesheet" href="../<?=DIR_INC;?>/css/responsive.bootstrap.min.css" />
	<link type="text/css" rel="stylesheet" href="../<?=DIR_INC;?>/js/filemanager/fancybox/jquery.fancybox.css" />
	<link type="text/css" rel="stylesheet" href="../<?=DIR_INC;?>/css/patternlock.css" />
	<link type="text/css" rel="stylesheet" href="../<?=DIR_INC;?>/css/bootstrap-tagsinput.css" />
	<link type="text/css" rel="stylesheet" href="../<?=DIR_INC;?>/css/bootstrap-datetimepicker.min.css" />
	<link type="text/css" rel="stylesheet" href="../<?=DIR_INC;?>/css/bootstrap-editable.css" />
	<link type="text/css" rel="stylesheet" href="../<?=DIR_INC;?>/css/dropzone.css" />

	<?php if ($_GET['mod'] == "theme" || $_GET['mod'] == "setting") { ?>
	<link type="text/css" rel="stylesheet" href="../<?=DIR_INC;?>/js/codemirror/lib/codemirror.css" />
	<link type="text/css" rel="stylesheet" href="../<?=DIR_INC;?>/js/codemirror/theme/github.css" />
	<link type="text/css" rel="stylesheet" href="../<?=DIR_INC;?>/js/codemirror/addon/display/fullscreen.css" />
	<link type="text/css" rel="stylesheet" href="../<?=DIR_INC;?>/js/codemirror/addon/hint/show-hint.css" />
	<link type="text/css" rel="stylesheet" href="../<?=DIR_INC;?>/js/codemirror/addon/dialog/dialog.css" />
	<?php } ?>

	<script type="text/javascript" src="../<?=DIR_INC;?>/js/jquery/jquery-2.1.4.min.js"></script>
	<script type="text/javascript" src="../<?=DIR_INC;?>/js/bootstrap/bootstrap.min.js"></script>

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="fixed-left">
	<div id="wrapper">
		<div class="topbar">
			<div class="topbar-left">
				<div class="text-center">
					<a href="admin.php?mod=home" class="logo"><span>Administrator</span></a>
				</div>
			</div>
			<div class="navbar navbar-default" role="navigation">
				<div class="container">
					<div class="row-menu">
						<div class="pull-left hidden-xs hidden-sm">
							<button class="button-menu-mobile open-left"><i class="fa fa-navicon"></i></button>
							<span class="clearfix"></span>
						</div>
						<div class="pull-left visible-xs visible-sm">
							<a href="admin.php?mod=home" class="button-menu-mobile"><i class="fa fa-home"></i></a>
							<span class="clearfix"></span>
						</div>
						<ul class="nav navbar-nav navbar-right pull-right">
							<?php if ($_GET['mod'] != 'home') { ?>
							<li style="float:left;">
								<a href="admin.php?mod=<?=$_GET['mod'];?>&act=addnew" style="color:#3498db !important;">
									<span class="fa-stack fa-lg">
										<i class="fa fa-circle fa-stack-2x"></i>
										<i class="fa fa-plus fa-stack-1x fa-inverse"></i>
									</span>
								</a>
							</li>
							<?php } ?>
							<li class="dropdown" style="float:left;">
								<a href="" class="dropdown-toggle profile" data-toggle="dropdown" aria-expanded="true">
									<?php
										$avatar = "../".DIR_CON."/uploads/user-".$_SESSION['iduser'].".jpg";
										$avatarimg = (file_exists($avatar) ? $_SESSION['iduser'] : 'editor');
									?>
									<img src="../<?=DIR_CON;?>/uploads/user-<?=$avatarimg;?>.jpg" alt="<?=$_SESSION['namauser'];?>" width="30" height="30" class="img-circle" title="<?=$_['quickaccess'];?>" />
								</a>
								<ul class="dropdown-menu">
									<li><a href="<?=WEB_URL;?>" target="_blank"><i class="fa fa-desktop"></i>&nbsp;&nbsp;<?=$_['tofrontend'];?></a></li>
									<li><a href="admin.php?mod=contact"><i class="fa fa-envelope"></i>&nbsp;&nbsp;<?=$_['message'];?></a></li>
									<li class="divider visible-lg"></li>
									<li><a href="admin.php?mod=user"><i class="fa fa-user"></i>&nbsp;&nbsp;<?=$_['user'];?></a></li>
									<li><a href="admin.php?mod=setting"><i class="fa fa-cog"></i>&nbsp;&nbsp;<?=$_['setting'];?></a></li>
									<li class="divider visible-lg"></li>
									<li><a href="route.php?mod=home&act=logout"><i class="fa fa-ban"></i>&nbsp;&nbsp;<?=$_['logout'];?></a></li>
								</ul>
							</li>
							<li class="menu-btn"><a href="javascript:void(0)"><i class="fa fa-bars"></i></a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>

		<div class="left side-menu">
			<div class="sidebar-inner slimscrollleft">
				<div id="sidebar-menu">
					<?php
						$instance = new DashboardMenu;
						$menu = $instance->menu($_SESSION['menuuser'], 'id="main_menu"', 'class="has_sub"', 'class="list-unstyled"');
						echo $menu;
					?>
					<div class="clearfix"></div>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
        
		<div class="content-page">
			<nav class="dark-sidebar">
				<?php
					$instance2 = new DashboardMenu;
					$menu2 = $instance2->menu($_SESSION['menuuser'], 'class="dark-sidebar-menu"', '', '');
					echo $menu2;
				?>
			</nav>
			<div class="content">
				<div class="container">
					<?php
						$alertflash = new PoCore();
						$alertflash->poflash->display();
						include_once VQMod::modCheck("route.php");
					?>
				</div>
			</div>
		</div>
	</div>

	<?php
		$alertalldel = new PoCore();
		echo $alertalldel->pohtml->dialogDeleteAll();
	?>

	<a href="#0" class="cd-top">Top</a>

	<script type="text/javascript">
		var resizefunc = [];
	</script>
	<script type="text/javascript" src="../<?=DIR_INC;?>/js/tinymce/tinymce.min.js"></script>
	<script type="text/javascript" src="../<?=DIR_INC;?>/js/detect/detect.js"></script>
	<script type="text/javascript" src="../<?=DIR_INC;?>/js/fastclick/fast-click.js"></script>
	<script type="text/javascript" src="../<?=DIR_INC;?>/js/slimscroll/jquery.slimscroll.js"></script>
	<script type="text/javascript" src="../<?=DIR_INC;?>/js/datatables/jquery.dataTables.min.js"></script>
	<script type="text/javascript" src="../<?=DIR_INC;?>/js/datatables/dataTables.bootstrap.min.js"></script>
	<script type="text/javascript" src="../<?=DIR_INC;?>/js/datatables/extensions/Responsive/js/dataTables.responsive.min.js"></script>
	<script type="text/javascript" src="../<?=DIR_INC;?>/js/datatables/extensions/Responsive/js/responsive.bootstrap.min.js"></script>
	<script type="text/javascript" src="../<?=DIR_INC;?>/js/filemanager/fancybox/jquery.fancybox.js"></script>
	<script type="text/javascript" src="../<?=DIR_INC;?>/js/patternlock/patternLock.js"></script>
	<script type="text/javascript" src="../<?=DIR_INC;?>/js/tagsinput/bootstrap-tagsinput.js"></script>
	<script type="text/javascript" src="../<?=DIR_INC;?>/js/tagsinput/typeahead.bundle.min.js"></script>
	<script type="text/javascript" src="../<?=DIR_INC;?>/js/datetime/moment.js"></script>
	<script type="text/javascript" src="../<?=DIR_INC;?>/js/datetime/bootstrap-datetimepicker.min.js"></script>
	<script type="text/javascript" src="../<?=DIR_INC;?>/js/maskedinput/jquery.maskedinput.min.js"></script>
	<script type="text/javascript" src="../<?=DIR_INC;?>/js/xeditable/bootstrap-editable.min.js"></script>
	<script type="text/javascript" src="../<?=DIR_INC;?>/js/filestyle/bootstrap-filestyle.min.js"></script>
	<script type="text/javascript" src="../<?=DIR_INC;?>/js/chartjs/chart.min.js"></script>
	<script type="text/javascript" src="../<?=DIR_INC;?>/js/dropzone/dropzone.js"></script>

	<?php if ($_GET['mod'] == "theme" || $_GET['mod'] == "setting") { ?>
	<script type="text/javascript" src="../<?=DIR_INC;?>/js/codemirror/lib/codemirror.js"></script>
	<script type="text/javascript" src="../<?=DIR_INC;?>/js/codemirror/addon/fold/xml-fold.js"></script>
	<script type="text/javascript" src="../<?=DIR_INC;?>/js/codemirror/addon/edit/matchtags.js"></script>
	<script type="text/javascript" src="../<?=DIR_INC;?>/js/codemirror/addon/edit/closetag.js"></script>
	<script type="text/javascript" src="../<?=DIR_INC;?>/js/codemirror/addon/edit/closebrackets.js"></script>
	<script type="text/javascript" src="../<?=DIR_INC;?>/js/codemirror/addon/selection/active-line.js"></script>
	<script type="text/javascript" src="../<?=DIR_INC;?>/js/codemirror/addon/display/fullscreen.js"></script>
	<script type="text/javascript" src="../<?=DIR_INC;?>/js/codemirror/addon/hint/show-hint.js"></script>
	<script type="text/javascript" src="../<?=DIR_INC;?>/js/codemirror/addon/hint/xml-hint.js"></script>
	<script type="text/javascript" src="../<?=DIR_INC;?>/js/codemirror/addon/hint/html-hint.js"></script>
	<script type="text/javascript" src="../<?=DIR_INC;?>/js/codemirror/addon/dialog/dialog.js"></script>
	<script type="text/javascript" src="../<?=DIR_INC;?>/js/codemirror/addon/search/searchcursor.js"></script>
	<script type="text/javascript" src="../<?=DIR_INC;?>/js/codemirror/addon/search/search.js"></script>
	<script type="text/javascript" src="../<?=DIR_INC;?>/js/codemirror/mode/clike/clike.js"></script>
	<script type="text/javascript" src="../<?=DIR_INC;?>/js/codemirror/mode/css/css.js"></script>
	<script type="text/javascript" src="../<?=DIR_INC;?>/js/codemirror/mode/htmlmixed/htmlmixed.js"></script>
	<script type="text/javascript" src="../<?=DIR_INC;?>/js/codemirror/mode/javascript/javascript.js"></script>
	<script type="text/javascript" src="../<?=DIR_INC;?>/js/codemirror/mode/php/php.js"></script>
	<script type="text/javascript" src="../<?=DIR_INC;?>/js/codemirror/mode/xml/xml.js"></script>
	<?php } ?>

	<script type="text/javascript" src="../<?=DIR_INC;?>/js/dashboard-core.js"></script>

	<script type="text/javascript">
		function initMCEall(){
			tinymce.init({
				mode: "textareas",
				editor_deselector : "mceNoEditor",
				skin: "lightgray",
				height: "800",
				content_css : "<?=WEB_URL.DIR_INC;?>/css/bootstrap.min.css,<?=WEB_URL.DIR_INC;?>/css/font-awesome.min.css",
				plugins: [
					"advlist autolink link image lists charmap print preview hr anchor pagebreak",
					"searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
					"table contextmenu directionality emoticons paste textcolor responsivefilemanager",
					"code fullscreen youtube codemirror codesample"
				],
				menubar : false,
				toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist | outdent indent table",
				toolbar2: "| fontsizeselect | styleselect | link unlink anchor | responsivefilemanager image media youtube | forecolor backcolor | code codesample fullscreen ",
				image_advtab: true,
				fontsize_formats: "8px 10px 12px 14px 18px 24px 36px",
				relative_urls: false,
				remove_script_host: false,
				external_filemanager_path: "<?=WEB_URL.DIR_INC;?>/js/filemanager/",
				filemanager_title: "File Manager",
				external_plugins: {
					"filemanager" : "<?=WEB_URL.DIR_INC;?>/js/filemanager/plugin.min.js"
				},
				codemirror: {
					indentOnInit: true,
					path: "<?=WEB_URL.DIR_INC;?>/js/codemirror"
				}
			});
		}
	</script>
	<?php if (file_exists("../".DIR_CON."/component/".strtolower($mod)."/admin_javascript.js")) { ?>
	<script type="text/javascript" src="../<?=DIR_CON;?>/component/<?=strtolower($mod);?>/admin_javascript.js"></script>
	<?php } ?>
	<script type="text/javascript">
		tinymce.init({
			selector: "#po-wysiwyg",
			editor_deselector : "mceNoEditor",
			skin: "lightgray",
			content_css : "<?=WEB_URL.DIR_INC;?>/css/bootstrap.min.css,<?=WEB_URL.DIR_INC;?>/css/font-awesome.min.css",
			plugins: [
				"advlist autolink link image lists charmap print preview hr anchor pagebreak",
				"searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
				"table contextmenu directionality emoticons paste textcolor responsivefilemanager",
				"code fullscreen youtube autoresize codemirror codesample"
			],
			menubar : false,
			toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist | outdent indent table",
			toolbar2: "| fontsizeselect | styleselect | link unlink anchor | responsivefilemanager image media youtube | forecolor backcolor | code codesample fullscreen ",
			image_advtab: true,
			fontsize_formats: "8px 10px 12px 14px 18px 24px 36px",
			relative_urls: false,
			remove_script_host: false,
			external_filemanager_path: "<?=WEB_URL.DIR_INC;?>/js/filemanager/",
			filemanager_title: "File Manager",
			external_plugins: {
				"filemanager" : "<?=WEB_URL.DIR_INC;?>/js/filemanager/plugin.min.js"
			},
			codemirror: {
				indentOnInit: true,
				path: "<?=WEB_URL.DIR_INC;?>/js/codemirror"
			}
		});
	</script>
</body>
</html>
<?php
	}
}
?>