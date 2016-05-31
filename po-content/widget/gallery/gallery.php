<?php
/*
 *
 * - PopojiCMS Widget File
 *
 * - File : gallery.php
 * - Version : 1.0
 * - Author : Jenuar Dalapang
 * - License : MIT License
 *
 *
 * Ini adalah file php yang di gunakan untuk menangani proses di bagian depan untuk widget galeri.
 * This is a php file for handling front end process for gallery widget.
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
class Gallery implements ExtensionInterface
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
        $templates->registerFunction('gallery', [$this, 'getObject']);
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
	 * Fungsi ini digunakan untuk mengambil daftar semua galeri.
	 *
	 * This function use to get all list of gallery.
	 *
	 * $order = string
	 * $limit = integer
	*/
	public function getAllGallery($order = 'id_gallery DESC', $limit)
    {
		$gallery = $this->core->podb->from('gallery')->orderBy($order)->limit($limit)->fetchAll();
        return $gallery;
    }

	/**
	 * Fungsi ini digunakan untuk mengambil daftar album.
	 *
	 * This function use to get list of album.
	 *
	 * $limit = integer
	 * $order = string
	 * $page = integer from get active page
	*/
	public function getAlbum($limit, $order, $page)
    {
		$offset = $this->core->popaging->searchPosition($limit, $page);
		$album = $this->core->podb->from('album')
			->where('active', 'Y')
			->orderBy($order)
			->limit($offset.','.$limit)
			->fetchAll();
		foreach($album as $key => $alb){
			$gallery = $this->core->podb->from('gallery')
				->where('id_album', $alb['id_album'])
				->orderBy('id_gallery DESC')
				->limit(1)
				->fetch();
			$album[$key]['picture'] = $gallery['picture'];
		}
		return $album;
    }

	/**
	 * Fungsi ini digunakan untuk membuat nomor halaman pada halaman album
	 *
	 * This function use to create pagination in album page.
	 *
	 * $limit = integer
	 * $page = integer from get active page
	 * $type = 0 or 1
	 * $prev = string previous text
	 * $next = string next text
	*/
	public function getAlbumPaging($limit, $page, $type, $prev, $next)
    {
		$totaldata = $this->core->podb->from('album')->where('active', 'Y')->count();
		$totalpage = $this->core->popaging->totalPage($totaldata, $limit);
		$pagination = $this->core->popaging->navPage($page, $totalpage, BASE_URL, 'album', 'page', $type, $prev, $next);
		return $pagination;
	}

	/**
	 * Fungsi ini digunakan untuk mengambil daftar galeri berdasarkan album.
	 *
	 * This function use to get list of gallery base on album.
	 *
	 * $limit = integer
	 * $order = string
	 * $album = array of album
	 * $page = integer from get active page
	*/
	public function getGallery($limit, $order, $album, $page)
    {
		$offset = $this->core->popaging->searchPosition($limit, $page);
		$gallery = $this->core->podb->from('gallery')
			->where('id_album', $album['id_album'])
			->orderBy($order)
			->limit($offset.','.$limit)
			->fetchAll();
		return $gallery;
    }

	/**
	 * Fungsi ini digunakan untuk membuat nomor halaman pada halaman galeri
	 *
	 * This function use to create pagination in gallery page.
	 *
	 * $limit = integer
	 * $album = array of album
	 * $page = integer from get active page
	 * $type = 0 or 1
	 * $prev = string previous text
	 * $next = string next text
	*/
	public function getGalleryPaging($limit, $album, $page, $type, $prev, $next)
    {
		$totaldata = $this->core->podb->from('gallery')->where('id_album', $album['id_album'])->count();
		$totalpage = $this->core->popaging->totalPage($totaldata, $limit);
		$pagination = $this->core->popaging->navPage($page, $totalpage, BASE_URL, 'gallery/'.$album['seotitle'], 'page', $type, $prev, $next);
		return $pagination;
	}

}