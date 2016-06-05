<?php
/**
 *
 * - PopojiCMS Core
 *
 * - File : core.php
 * - Version : 1.0
 * - Author : Jenuar Dalapang
 * - License : MIT License
 *
 *
 * Ini adalah file core PopojiCMS yang memuat semua library penunjang.
 * This is a file core from PopojiCMS which contains all the supporting libraries.
 *
*/

/**
 * Memasukkan library utama PopojiCMS
 *
 * Include PopojiCMS library
 *
*/
include_once "config.php";
include_once "cookie.php";
include_once "datetime.php";
include_once "directory.php";
include_once "email.php";
include_once "error.php";
include_once "html.php";
include_once "paging.php";
include_once "request.php";
include_once "sitemap.php";
include_once "string.php";
include_once "timeout.php";

/**
 * Memasukkan library dari vendor pihak ketiga
 *
 * Include vendor library
 *
*/
include_once "vendor/abeautifulsite/SimpleImage.php";
include_once "vendor/bramus/Router.php";
include_once "vendor/browser/Browser.php";
include_once "vendor/datatables/datatables.class.php";
include_once "vendor/dynamicmenu/dashboard_menu.php";
include_once "vendor/dynamicmenu/front_menu.php";
include_once "vendor/fluentpdo/FluentPDO.php";
include_once "vendor/gump/gump.class.php";
include_once "vendor/pclzip/pclziplib.php";
include_once "vendor/phpmailer/PHPMailerAutoload.php";
include_once "vendor/plasticbrain/FlashMessages.php";
include_once "vendor/plates/autoload.php";
include_once "vendor/recaptcha/recaptchalib.php";
include_once "vendor/timeago/timeago.inc.php";
include_once "vendor/verot/class.upload.php";

/**
 * Menginisialisasi semua class dari popojicms dan vendor
 *
 * Initialize all class from popojicms and vendor
 *
*/

class PoCore
{

	public $pdo;
	public $podb;
	public $poconnect;
	public $poval;
	public $pohtml;
	public $postring;
	public $poflash;
	public $podatetime;
	public $pomail;
	public $posetting;
	public $potheme;
	public $porequest;
	public $popaging;

	public function __construct()
	{
		$this->pdo = new PDO("mysql:host=".DATABASE_HOST.";dbname=".DATABASE_NAME."", DATABASE_USER, DATABASE_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
		$this->podb = new FluentPDO($this->pdo);
		$this->poconnect = array('user' => DATABASE_USER, 'pass' => DATABASE_PASS, 'db' => DATABASE_NAME, 'host' => DATABASE_HOST);
		$this->porequest = new PoRequest();
		$this->poval = new GUMP();
		$this->pohtml = new PoHtml();
		$this->postring = new PoString();
		$this->poflash = new FlashMessages();
		$this->podatetime = new PoDateTime();
		$this->pomail = new PHPMailer();
		$this->popaging = new PoPaging();
		$this->posetting = $this->podb->from('setting')->fetchAll();
		$this->potheme = $this->podb->from('theme')->where('active', 'Y')->limit(1)->fetch();
		date_default_timezone_set(''.$this->posetting[15]['value'].'');
	}

	/**
	 * Fungsi ini digunakan untuk auntentikasi user.
	 *
	 * This function is used to user authentication.
	 *
	*/
	public function auth($level, $component, $crud)
	{
		$user_level = $this->podb->from('user_level')
			->where('id_level', $level)
			->limit(1)
			->fetch();
		$itemfusion = '';
		$roles = json_decode($user_level['role'], true);
		foreach($roles as $key => $role){
			if($roles[$key]['component'] == $component) {
				$itemfusion .= $roles[$key][$crud];
			} else {
				unset($roles[$key]);
			}
		}
		if ($itemfusion == 1) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Fungsi ini digunakan untuk memasang bahasa.
	 *
	 * This function is used to set language.
	 *
	*/
	public function setlang($component, $lang)
	{
		if (file_exists("po-content/lang/".$component."/".$lang.".php")) {
			include_once VQMod::modCheck("po-content/lang/main/".$lang.".php");
			include_once VQMod::modCheck("po-content/lang/".$component."/".$lang.".php");
			return $_;
		} else {
			return false;
		}
	}

}