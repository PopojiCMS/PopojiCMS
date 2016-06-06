<?php
/*
 *
 * - PopojiCMS Admin File
 *
 * - File : route.php
 * - Version : 1.0
 * - Author : Jenuar Dalapang
 * - License : MIT License
 *
 *
 * Ini adalah file php yang di gunakan untuk mengatur route pada admin.
 * This is a php file for set route on admin.
 *
*/

/**
 * Include PopojiCMS main core
*/
require_once '../vqmod/vqmod.php';
VQMod::bootup();
include_once "../po-includes/core/core.php";
/**
 * Call class PoRequest for filtering get request
*/
$route = new PoRequest();
/**
 * @var $mod to catch select mod
*/
$mod = ucfirst($route->get('mod',FILTER_SANITIZE_STRING));
/**
 * @var $act to catch select act
*/
$act = $route->get('act',FILTER_SANITIZE_STRING);
/**
 * Switch route based on request filename
*/
switch(basename($_SERVER['PHP_SELF'])) {
	/**
	 * Route untuk halaman login
	 *
	 * Route for login page
	 *
	*/
	case "index.php":
		include_once VQMod::modCheck("login.php");
		if (class_exists($mod)) {
			$slug = new $mod();
			if (method_exists($slug, $act)) {
				$slug->$act();
			} else {
				$slug->index();
			}
		} else {
			$slug = new Login();
			$slug->index();
		}
	break;

	/**
	 * Route untuk halaman dasbor
	 *
	 * Route for admin dashboard
	 *
	*/
	case "admin.php":
		if (file_exists("../".DIR_CON."/component/".strtolower($mod)."/admin_".strtolower($mod).".php")) {
			include_once VQMod::modCheck("../".DIR_CON."/component/".strtolower($mod)."/admin_".strtolower($mod).".php");
			if (class_exists($mod)) {
				if (file_exists("../".DIR_CON."/lang/".strtolower($mod)."/".$selectlang.".php")) {
					include_once VQMod::modCheck("../".DIR_CON."/lang/".strtolower($mod)."/".$selectlang.".php");
				} else {
					if (file_exists("../".DIR_CON."/lang/".strtolower($mod)."/id.php")) {
						include_once VQMod::modCheck("../".DIR_CON."/lang/".strtolower($mod)."/id.php");
					}
				}
				$slug = new $mod();
				if (method_exists($slug, $act)) {
					$slug->$act();
				} else {
					$slug->index();
				}
			} else {
				include_once VQMod::modCheck("../".DIR_CON."/component/home/admin_home.php");
				$slug = new Home();
				$slug->index();
			}
		} else {
			include_once VQMod::modCheck("../".DIR_CON."/component/home/admin_home.php");
			$slug = new Home();
			$slug->error();
		}
	break;

	/**
	 * Route untuk permintaan dari post form atau ajax
	 *
	 * Route from post form or ajax request
	 *
	*/
	case "route.php":
		if ($mod == 'Login') {
			session_start();
			include_once VQMod::modCheck("login.php");
			if (class_exists($mod)) {
				$slug = new $mod();
				if (method_exists($slug, $act)) {
					$slug->$act();
				} else {
					$slug->index();
				}
			} else {
				$slug = new Login();
				$slug->index();
			}
		} else {
			if (file_exists("../".DIR_CON."/component/".strtolower($mod)."/admin_".strtolower($mod).".php")) {
				session_start();
				include_once VQMod::modCheck("../".DIR_CON."/component/".strtolower($mod)."/admin_".strtolower($mod).".php");
				if (class_exists($mod)) {
					$selectlang = (isset($_COOKIE['lang']) ? $_COOKIE['lang'] : 'id');
					include_once VQMod::modCheck("../".DIR_CON."/lang/main/".$selectlang.".php");
					if (file_exists("../".DIR_CON."/lang/".strtolower($mod)."/".$selectlang.".php")) {
						include_once VQMod::modCheck("../".DIR_CON."/lang/".strtolower($mod)."/".$selectlang.".php");
					} else {
						if (file_exists("../".DIR_CON."/lang/".strtolower($mod)."/id.php")) {
							include_once VQMod::modCheck("../".DIR_CON."/lang/".strtolower($mod)."/id.php");
						}
					}
					$slug = new $mod();
					if (method_exists($slug, $act)) {
						$slug->$act();
					} else {
						$slug = new PoError();
						$slug->notfound();
					}
				} else {
					$slug = new PoError();
					$slug->notfound();
				}
			} else {
				$slug = new PoError();
				$slug->notfound();
			}
		}
	break;
}