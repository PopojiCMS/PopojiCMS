<?php
/*
 *
 * - PopojiCMS Widget File
 *
 * - File : language.php
 * - Version : 1.0
 * - Author : Jenuar Dalapang
 * - License : MIT License
 *
 *
 * Ini adalah file php yang di gunakan untuk menangani proses di bagian depan untuk widget bahasa.
 * This is a php file for handling front end process for language widget.
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
class Language implements ExtensionInterface
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
	}

	/**
	 * Fungsi ini digunakan untuk mendaftarkan semua fungsi widget (diharuskan).
	 *
	 * This function use to register all widget function (require).
	 *
	*/
    public function register(Engine $templates)
    {
        $templates->registerFunction('language', [$this, 'getObject']);
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
	 * Fungsi ini digunakan untuk mengambil bahasa yang sedang aktif.
	 *
	 * This function use to get language will active.
	 *
	 * $order = string ASC or DESC
	*/
    public function getLanguage($order)
    {
		$lang = $this->core->podb->from('language')
			->where('active', 'Y')
			->order('id_language '.$order.'')
			->fetchAll();
        return $lang;
    }

	/**
	 * Fungsi ini digunakan untuk mengambil bahasa berdasarkan code.
	 *
	 * This function use to get language base on code.
	 *
	 * $code = char
	*/
	public function getIdLanguage($code)
    {
		$id_lang = $this->core->podb->from('language')
			->where('code', $code)
			->limit(1)
			->fetch();
        return $id_lang;
    }

}