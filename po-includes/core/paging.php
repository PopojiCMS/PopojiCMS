<?php
/**
 *
 * - PopojiCMS Core
 *
 * - File : paging.php
 * - Version : 1.0
 * - Author : Jenuar Dalapang
 * - License : MIT License
 *
 *
 * Ini adalah library untuk membuat paging halaman otomatis.
 * This is library for create automatic paging.
 *
 * Contoh untuk penggunaan class ini
 * Example for uses this class
 *
 *
 * $p = new PoPaging;
 * $getpage = $_GET['page'];
 * $limit = 5;
 * $position = $p->searchPosition($limit, $getpage);
 * $totaldata = 20;
 * $totalpage = $p->totalPage($totaldata, $limit);
 * $linkPage = $p->navPage($getpage, $totalpage, "http://www.domain.com", "category", "sport-title", "1", "Prev", "Next");
 * echo $linkPage;
 *
*/

class PoPaging
{

	function __construct(){}

	/**
	 * Fungsi ini digunakan untuk mencari posisi paging
	 *
	 * This function use for search paging position
	 *
	 * $limit = integer
	 * $active_page = integer
	*/
	public function searchPosition($limit, $active_page)
	{
		if(empty($active_page)){
			$position = 0;
			$active_page = 1;
		}else{
			$position = ($active_page-1) * $limit;
		}
		return $position;
	}

	/**
	 * Fungsi ini digunakan untuk menghitung jumlah halaman
	 *
	 * This function use for count total page
	 *
	 * $totaldata = integer
	 * $limit = integer
	*/
	public function totalPage($totaldata, $limit)
	{
		$totalpage = ceil($totaldata/$limit);
		return $totalpage;
	}

	/**
	 * Fungsi ini digunakan untuk membuat element paging
	 *
	 * This function use for create paging element
	 *
	 * $active_page = integer
	 * $totalpage = integer
	 * $website_url = string
	 * $mod = string
	 * $title = string
	 * $pagetype = type pagging integer 0 or 1
	*/
	public function navPage($active_page, $totalpage, $website_url, $mod, $title, $pagetype, $prevtxt, $nexttxt)
	{
		$link_page = "";

		if ($active_page > 1) {
			$prev = $active_page-1;
			$link_page .= "<li><a href=\"{$website_url}/{$mod}/{$title}/{$prev}\">{$prevtxt}</a></li>";
		} else {
			$link_page .= "<li class=\"disabled\"><a>{$prevtxt}</a></li>";
		}

		if ($pagetype == "1") {
			$num = ($active_page > 3 ? "<li class=\"disabled\"><a>...</a></li>" : " ");
			for ($i=$active_page-2; $i<$active_page; $i++)
			{
				if ($i < 1)
				continue;
				$num .= "<li><a href=\"{$website_url}/{$mod}/{$title}/{$i}\">{$i}</a></li>";
			}
			$num .= "<li class=\"active\"><a>{$active_page}</a></li>";
			for ($i=$active_page+1; $i<($active_page+3); $i++)
			{
				if($i > $totalpage)
				break;
				$num .= "<li><a href=\"{$website_url}/{$mod}/{$title}/{$i}\">{$i}</a></li>";
			}
			$num .= ($active_page+2<$totalpage ? "<li class=\"disabled\"><a>...</a></li><li><a href=\"{$website_url}/{$mod}/{$title}/{$totalpage}\">{$totalpage}</a></li>" : " ");
			$link_page .= "{$num}";
		}

		if ($active_page < $totalpage) {
			$next = $active_page+1;
			$link_page .= "<li><a href=\"{$website_url}/{$mod}/{$title}/{$next}\">{$nexttxt}</a></li>";
		} else {
			$link_page .= "<li class=\"disabled\"><a>{$nexttxt}</a></li>";
		}
		return $link_page;
	}

}