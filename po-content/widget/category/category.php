<?php
/*
 *
 * - PopojiCMS Widget File
 *
 * - File : category.php
 * - Version : 1.0
 * - Author : Jenuar Dalapang
 * - License : MIT License
 *
 *
 * Ini adalah file php yang di gunakan untuk menangani proses di bagian depan untuk widget kategori.
 * This is a php file for handling front end process for category widget.
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
class Category implements ExtensionInterface
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
        $templates->registerFunction('category', [$this, 'getObject']);
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
	 * Fungsi ini digunakan untuk mengambil daftar kategori berdasarkan id_post.
	 *
	 * This function use to get list of category base on id_post.
	 *
	 * $id_post = integer
	 * $lang = WEB_LANG_ID
	 * $sep = string separator
	 * $link = boolean
	*/
    public function getCategory($id_post, $lang, $sep = ', ', $link = true)
    {
		$post_cats = $this->core->podb->from('post_category')
			->where('id_post', $id_post)
			->fetchAll();
		$category = '';
		foreach($post_cats as $post_cat){
			$categorys = $this->core->podb->from('category')
				->select('category_description.title')
				->leftJoin('category_description ON category_description.id_category = category.id_category')
				->where('category.id_category', $post_cat['id_category'])
				->where('category_description.id_language', $lang)
				->where('category.active', 'Y')
				->limit(1)
				->fetch();
			if ($link) {
				$category .= '<a href="'.WEB_URL.'category/'.$categorys['seotitle'].'">'.$categorys['title'].'</a>'.$sep;
			} else {
				$category .= $categorys['title'].$sep;
			}
		}
        return rtrim($category, $sep);
    }

	/**
	 * Fungsi ini digunakan untuk mengambil satu kategori.
	 *
	 * This function use to get one category.
	 *
	 * $id = integer
	 * $lang = WEB_LANG_ID
	*/
	public function getOneCategory($id, $lang)
    {
		$category = $this->core->podb->from('category')
				->select('category_description.title')
				->leftJoin('category_description ON category_description.id_category = category.id_category')
				->where('category.id_category', $id)
				->where('category_description.id_language', $lang)
				->where('category.active', 'Y')
				->limit(1)
				->fetch();
        return $category;
    }

	/**
	 * Fungsi ini digunakan untuk mengambil semua daftar kategori.
	 *
	 * This function use to get all list of category.
	 *
	 * $lang = WEB_LANG_ID
	*/
	public function getAllCategory($lang)
    {
		$category = $this->core->podb->from('category')
				->select('category_description.title')
				->leftJoin('category_description ON category_description.id_category = category.id_category')
				->where('category_description.id_language', $lang)
				->where('category.active', 'Y')
				->fetchAll();
        return $category;
    }

}