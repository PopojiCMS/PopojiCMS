<?php
/*
 *
 * - PopojiCMS Admin File
 *
 * - File : admin_home.php
 * - Version : 1.0
 * - Author : Jenuar Dalapang
 * - License : MIT License
 *
 *
 * Ini adalah file php yang di gunakan untuk menangani proses admin pada halaman menu manager.
 * This is a php file for handling admin process for menu manager page.
 *
*/

/**
 * Fungsi ini digunakan untuk mencegah file ini diakses langsung tanpa melalui router.
 *
 * This function use for prevent this file accessed directly without going through a router.
 *
*/
if (!defined('CONF_STRUCTURE')) {
	header('location:index.html');
	exit;
}

/**
 * Fungsi ini digunakan untuk mencegah file ini diakses langsung tanpa login akses terlebih dahulu.
 *
 * This function use for prevent this file accessed directly without access login first.
 *
*/
if (empty($_SESSION['namauser']) AND empty($_SESSION['passuser']) AND $_SESSION['login'] == 0) {
	header('location:index.php');
	exit;
}

/**
 * Include file konfigurasi dan fungsi untuk menu manager.
 *
 * Include file config and function for the menu manager.
 *
*/
include_once '../'.DIR_CON.'/component/menumanager/includes/config.php';
include_once '../'.DIR_CON.'/component/menumanager/includes/functions.php';

class Menumanager extends PoCore
{

	/**
	 * Fungsi ini digunakan untuk menginisialisasi class utama.
	 *
	 * This function use to initialize the main class.
	 *
	*/
	function __construct()
	{
		parent::__construct();
	}

	/**
	 * Fungsi ini digunakan untuk menginisialisasi fungsi view khusus menu manager.
	 *
	 * This function use to initialize function view special for menu manager.
	 *
	*/
	public function view($view_file, $data = '')
	{
		if (is_array($data)) {
			extract($data);
		}
		$file = '../'.DIR_CON.'/component/menumanager/templates/' . $view_file . '.php';
		if (file_exists($file)) {
			include_once $file;
		} else {
			die("Cannot include $view_file");
		}
	}

	/**
	 * Fungsi ini digunakan untuk menampilkan halaman index menu manager.
	 *
	 * This function use for index menu manager page.
	 *
	*/
	public function index()
	{
		if (!$this->auth($_SESSION['leveluser'], 'menumanager', 'read')) {
			echo $this->pohtml->error();
			exit;
		}
		$menus = $this->podb->from('menu_group')->count();
		if ($menus != "0"){
			$controller = 'menu';
			$method = 'menumanager';
			if (isset($_GET['act'])) {
				$act = explode('.', $_GET['act']);
				$controller = $act[0];
				if (isset($act[1])) {
					$method = $act[1];
				}
			}
			$controller_file = '../'.DIR_CON.'/component/menumanager/modules/' . $controller . '.php';
			if (file_exists($controller_file)) {
				include_once $controller_file;
				$Class_name = ucfirst($controller);
				$instance = new $Class_name;
				if (!is_callable(array($instance, $method))) {
					die("Cannot call method $method");
				}
				$instance->$method();
			} else {
				$controller_file = '../'.DIR_CON.'/component/menumanager/modules/menu.php';
				include_once $controller_file;
				$instance = new Menu;
				$instance->menumanager();
			}
		}else{
			$data = array(
				'id' => '1',
				'title' => 'Main Menu'
			);
			$query = $this->podb->insertInto(MENUGROUP_TABLE)->values($data);
			$query->execute();
			$controller = 'menu';
			$method = 'menumanager';
			if (isset($_GET['act'])) {
				$act = explode('.', $_GET['act']);
				$controller = $act[0];
				if (isset($act[1])) {
					$method = $act[1];
				}
			}
			$controller_file = '../'.DIR_CON.'/component/menumanager/modules/' . $controller . '.php';
			if (file_exists($controller_file)) {
				include_once $controller_file;
				$Class_name = ucfirst($controller);
				$instance = new $Class_name;
				if (!is_callable(array($instance, $method))) {
					die("Cannot call method $method");
				}
				$instance->$method();
			} else {
				$controller_file = '../'.DIR_CON.'/component/menumanager/modules/menu.php';
				include_once $controller_file;
				$instance = new Menu;
				$instance->menumanager();
			}
		}
	}

	/**
	 * Fungsi ini digunakan untuk menangani request addmenu.
	 *
	 * This function use for handle requests addmenu.
	 *
	*/
	public function addmenu()
	{
		if (!$this->auth($_SESSION['leveluser'], 'menumanager', 'create')) {
			echo $this->pohtml->error();
			exit;
		}
		include_once '../'.DIR_CON.'/component/menumanager/modules/menu.php';
		$instance = new Menu;
		$instance->add();
	}

	/**
	 * Fungsi ini digunakan untuk menangani request editmenu.
	 *
	 * This function use for handle requests editmenu.
	 *
	*/
	public function editmenu()
	{
		if (!$this->auth($_SESSION['leveluser'], 'menumanager', 'update')) {
			echo $this->pohtml->error();
			exit;
		}
		include_once '../'.DIR_CON.'/component/menumanager/modules/menu.php';
		$instance = new Menu;
		$instance->edit();
	}

	/**
	 * Fungsi ini digunakan untuk menangani request savemenu.
	 *
	 * This function use for handle requests savemenu.
	 *
	*/
	public function savemenu()
	{
		if (!$this->auth($_SESSION['leveluser'], 'menumanager', 'create')) {
			echo $this->pohtml->error();
			exit;
		}
		include_once '../'.DIR_CON.'/component/menumanager/modules/menu.php';
		$instance = new Menu;
		$instance->save();
	}

	/**
	 * Fungsi ini digunakan untuk menangani request savepositionmenu.
	 *
	 * This function use for handle requests savepositionmenu.
	 *
	*/
	public function savepositionmenu()
	{
		if (!$this->auth($_SESSION['leveluser'], 'menumanager', 'update')) {
			echo $this->pohtml->error();
			exit;
		}
		include_once '../'.DIR_CON.'/component/menumanager/modules/menu.php';
		$instance = new Menu;
		$instance->save_position();
	}

	/**
	 * Fungsi ini digunakan untuk menangani request deletemenu.
	 *
	 * This function use for handle requests deletemenu.
	 *
	*/
	public function deletemenu()
	{
		if (!$this->auth($_SESSION['leveluser'], 'menumanager', 'delete')) {
			echo $this->pohtml->error();
			exit;
		}
		include_once '../'.DIR_CON.'/component/menumanager/modules/menu.php';
		$instance = new Menu;
		$instance->delete();
	}

	/**
	 * Fungsi ini digunakan untuk menangani request addmenugroup.
	 *
	 * This function use for handle requests addmenugroup.
	 *
	*/
	public function addmenugroup()
	{
		if (!$this->auth($_SESSION['leveluser'], 'menumanager', 'create')) {
			echo $this->pohtml->error();
			exit;
		}
		include_once '../'.DIR_CON.'/component/menumanager/modules/menu_group.php';
		$instance = new Menu_group;
		$instance->add();
	}

	/**
	 * Fungsi ini digunakan untuk menangani request editmenugroup.
	 *
	 * This function use for handle requests editmenugroup.
	 *
	*/
	public function editmenugroup()
	{
		if (!$this->auth($_SESSION['leveluser'], 'menumanager', 'update')) {
			echo $this->pohtml->error();
			exit;
		}
		include_once '../'.DIR_CON.'/component/menumanager/modules/menu_group.php';
		$instance = new Menu_group;
		$instance->edit();
	}

	/**
	 * Fungsi ini digunakan untuk menangani request deletemenugroup.
	 *
	 * This function use for handle requests deletemenugroup.
	 *
	*/
	public function deletemenugroup()
	{
		if (!$this->auth($_SESSION['leveluser'], 'menumanager', 'delete')) {
			echo $this->pohtml->error();
			exit;
		}
		include_once '../'.DIR_CON.'/component/menumanager/modules/menu_group.php';
		$instance = new Menu_group;
		$instance->delete();
	}

}