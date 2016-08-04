<?php
/*
 *
 * - PopojiCMS Widget File
 *
 * - File : menu.php
 * - Version : 1.1
 * - Author : Jenuar Dalapang
 * - License : MIT License
 *
 *
 * Ini adalah file php yang di gunakan untuk menangani proses di bagian depan untuk widget menu.
 * This is a php file for handling front end process for menu widget.
 *
*/

/**
 * Memanggil class utama PoTemplate (diharuskan).
 *
 * Call main class PoTemplate (require).
 *
*/
use PoTemplate\Engine;
use PoTemplate\Extension\ExtensionInterface;

/**
 * Mendeklarasikan class widget diharuskan dengan mengimplementasikan class ExtensionInterface (diharuskan).
 *
 * Declaration widget class must with implements ExtensionInterface class (require).
 *
*/
class Menu implements ExtensionInterface
{

	/**
	 * Fungsi ini digunakan untuk menginisialisasi class utama (diharuskan).
	 *
	 * This function use to initialize the main class (require).
	 *
	*/
	public function __construct()
	{
		$this->core = new PoCore();
		$this->frontmenu = new FrontMenu();
	}

	/**
	 * Fungsi ini digunakan untuk mendaftarkan semua fungsi widget (diharuskan).
	 *
	 * This function use to register all widget function (require).
	 *
	*/
    public function register(Engine $templates)
    {
        $templates->registerFunction('menu', [$this, 'getObject']);
    }

	/**
	 * Fungsi ini digunakan untuk menangkap semua fungsi widget (diharuskan).
	 *
	 * This function use to catch all widget function (require).
	 *
	*/
    public function getObject()
    {
        return $this;
    }

	/**
	 * Fungsi ini digunakan untuk membuat menu pada bagian depan.
	 *
	 * This function use to generate menu in frontend.
	 *
	 * $set_lang = string code of language
	 * $attr = id or class in list (ex: <ul>)
	 * $attrs = id or class in child list (ex: <li>)
	 * $attrss = id or class in link (ex: <a>)
	 * $wrapper = html tag (ex: <div>)
	 * $endwrapper = end html tag (ex: </div>)
	*/
    public function getFrontMenu($set_lang, $attr = '', $attrs = '', $attrss = '', $wrapper = '<div>', $endwrapper = '</div>')
    {
		$group_id = $this->core->podb->from('menu_group')
			->where('title', $set_lang)
			->limit(1)
			->fetch();
		$front_menu = $this->frontmenu->menu($group_id['id'], $attr, $attrs, $attrss, $wrapper, $endwrapper);
        return $front_menu;
    }

}