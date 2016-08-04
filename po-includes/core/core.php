<?php
/**
 *
 * - PopojiCMS Core
 *
 * - File : core.php
 * - Version : 1.1
 * - Author : Jenuar Dalapang
 * - License : MIT License
 *
 *
 * Ini adalah file core PopojiCMS yang memuat semua library penunjang.
 * This is a file core from PopojiCMS which contains all the supporting libraries.
 *
*/

/**
 * Mendeklarasi Core Path PopojiCMS
 *
 * Declaration Core Path PopojiCMS
 *
 * Thanks to Mang Aay
 *
*/
define('CORE_PATH', dirname(__FILE__));

/**
 * Memasukkan library utama PopojiCMS
 *
 * Include PopojiCMS library
 *
*/
require_once CORE_PATH."/config.php";
require_once CORE_PATH."/cookie.php";
require_once CORE_PATH."/datetime.php";
require_once CORE_PATH."/directory.php";
require_once CORE_PATH."/email.php";
require_once CORE_PATH."/error.php";
require_once CORE_PATH."/html.php";
require_once CORE_PATH."/paging.php";
require_once CORE_PATH."/request.php";
require_once CORE_PATH."/sitemap.php";
require_once CORE_PATH."/string.php";
require_once CORE_PATH."/timeout.php";

/**
 * Memasukkan library dari vendor pihak ketiga
 *
 * Include vendor library
 *
*/
require_once CORE_PATH."/vendor/abeautifulsite/SimpleImage.php";
require_once CORE_PATH."/vendor/bramus/Router.php";
require_once CORE_PATH."/vendor/browser/Browser.php";
require_once CORE_PATH."/vendor/datatables/datatables.class.php";
require_once CORE_PATH."/vendor/dynamicmenu/dashboard_menu.php";
require_once CORE_PATH."/vendor/dynamicmenu/front_menu.php";
require_once CORE_PATH."/vendor/fluentpdo/FluentPDO.php";
require_once CORE_PATH."/vendor/gump/gump.class.php";
require_once CORE_PATH."/vendor/pclzip/pclziplib.php";
require_once CORE_PATH."/vendor/phpmailer/PHPMailerAutoload.php";
require_once CORE_PATH."/vendor/plasticbrain/FlashMessages.php";
require_once CORE_PATH."/vendor/plates/autoload.php";
require_once CORE_PATH."/vendor/recaptcha/recaptchalib.php";
require_once CORE_PATH."/vendor/timeago/timeago.inc.php";
require_once CORE_PATH."/vendor/verot/class.upload.php";

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
		/**
		 * Menyamakan perbedaan waktu sistem dan database
		 *
		 * Synchronize different system time and database
		 *
		*/
		date_default_timezone_set(TIMEZONE);
		$timenow = new DateTime();
		$timemins = $timenow->getOffset() / 60;
		$timesgn = ($timemins < 0 ? -1 : 1);
		$timemins = abs($timemins);
		$timehrs = floor($timemins / 60);
		$timemins -= $timehrs * 60;
		$timeoffset = sprintf('%+d:%02d', $timehrs*$timesgn, $timemins);

		/**
		 * Menginisialisasi semua class dari popojicms dan vendor ke variabel
		 *
		 * Initialize all class from popojicms and vendor to variabel
		 *
		*/
		$this->pdo = new PDO(DATABASE_DRIVER.":host=".DATABASE_HOST.";dbname=".DATABASE_NAME."", DATABASE_USER, DATABASE_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
		$this->pdo->exec("SET time_zone='$timeoffset';");
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
			if (VQMOD == TRUE) {
				include_once VQMod::modCheck("po-content/lang/main/".$lang.".php");
				include_once VQMod::modCheck("po-content/lang/".$component."/".$lang.".php");
				return $_;
			} else {
				include_once "po-content/lang/main/".$lang.".php";
				include_once "po-content/lang/".$component."/".$lang.".php";
				return $_;
			}
		} else {
			if (VQMOD == TRUE) {
				include_once VQMod::modCheck("po-content/lang/main/id.php");
				include_once VQMod::modCheck("po-content/lang/".$component."/id.php");
				return $_;
			} else {
				include_once "po-content/lang/main/id.php";
				include_once "po-content/lang/".$component."/id.php";
				return $_;
			}
		}
	}

}