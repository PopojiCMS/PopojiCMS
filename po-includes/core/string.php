<?php
/**
 *
 * - PopojiCMS Core
 *
 * - File : string.php
 * - Version : 1.0
 * - Author : Jenuar Dalapang
 * - License : MIT License
 *
 *
 * Ini adalah library untuk menangani pengolahan string.
 * This is library for handling string process.
 *
 * Contoh untuk penggunaan class ini
 * Example for uses this class
 *
 *
 * $string = new PoString;
 * echo $string->seo_title("Example SEO Title");
 *
*/

class PoString
{

	function __construct(){}

	/**
	 * Fungsi ini digunakan untuk membuat seo title
	 *
	 * This function use for create seo title
	 *
	 * $s = string
	*/
	public function seo_title($s)
	{
		$c = array (' ');
		$d = array ('-','/','\\',',','.','#',':',';','\'','"','[',']','{','}',')','(','|','`','~','!','@','%','$','^','&','*','=','?','+');
		$s = str_replace($d, '', $s);
		$s = strtolower(str_replace($c, '-', $s));
		return $s;
	}

	/**
	 * Fungsi ini digunakan untuk membuat link http
	 *
	 * This function use for create http link
	 *
	 * $url = string
	*/
	public function addhttp($url)
	{
		if (!preg_match("@^[hf]tt?ps?://@", $url)) {
			$url = "http://" . $url;
		}
		return $url;
	}

	/**
	 * Fungsi ini digunakan untuk membuat link otomatis
	 *
	 * This function use for create automatic link
	 *
	 * $str = string
	*/
	public function autolink($str)
	{
		$str = eregi_replace("([[:space:]])((f|ht)tps?:\/\/[a-z0-9~#%@\&:=?+\/\.,_-]+[a-z0-9~#%@\&=?+\/_.;-]+)", "\\1<a href=\"\\2\" target=\"_blank\">\\2</a>", $str); //http
		$str = eregi_replace("([[:space:]])(www\.[a-z0-9~#%@\&:=?+\/\.,_-]+[a-z0-9~#%@\&=?+\/_.;-]+)", "\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>", $str); // www.
		$str = eregi_replace("([[:space:]])([_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,3})","\\1<a href=\"mailto:\\2\">\\2</a>", $str); // mail
		$str = eregi_replace("^((f|ht)tp:\/\/[a-z0-9~#%@\&:=?+\/\.,_-]+[a-z0-9~#%@\&=?+\/_.;-]+)", "<a href=\"\\1\" target=\"_blank\">\\1</a>", $str); //http
		$str = eregi_replace("^(www\.[a-z0-9~#%@\&:=?+\/\.,_-]+[a-z0-9~#%@\&=?+\/_.;-]+)", "<a href=\"http://\\1\" target=\"_blank\">\\1</a>", $str); // www.
		$str = eregi_replace("^([_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,3})","<a href=\"mailto:\\1\">\\1</a>", $str); // mail
		return $str;
	}

	/**
	 * Fungsi ini digunakan untuk memotong suatu string
	 *
	 * This function use for cut a string
	 *
	 * $option = string post or title
	 * $data = string
	 * $long = integer
	*/
	public function cuthighlight($option, $data, $long)
	{
		$content = $data;
		if ($option == "post") {
			$content = html_entity_decode($content);
			$content = strip_tags($content);
			$content = substr($content,0,$long);
			$content = substr($content,0,strrpos($content," "));
		} else {
			$content = substr($content,0,$long);
		}
		return $content;
	}

	/**
	 * Fungsi ini digunakan untuk memvalidasi inputan
	 *
	 * This function use for input validation
	 *
	 * $str = string or integer
	 * $type = string xss or sql
	*/
	public function valid($str, $type)
	{
        switch($type)
		{
			default:
			case 'sql':
				$d = array('-','/','\\',',','#',':',';','\'','"','[',']','{','}',')','(','|','`','~','!','%','$','^','&','*','=','?','+');
				$str = str_replace($d, '', $str);
				$str = stripcslashes($str);	
				$str = htmlspecialchars($str);				
				$str = preg_replace('/[^A-Za-z0-9]/','',$str);				
				return intval($str);
			break;
			case 'xss':
				$d = array ('\\','#',';','\'','"','[',']','{','}',')','(','|','`','~','!','%','$','^','*','=','?','+');
				$str = str_replace($d, '', $str);
				$str = stripcslashes($str);	
				$str = htmlspecialchars($str);
				return $str;
			break;
		}
	}

	/**
	 * Fungsi ini digunakan untuk filter ekstensi file
	 *
	 * This function use for filtering file extension
	 *
	 * $path = string
	*/
	public function extension($path)
	{
		$file = pathinfo($path);
		if (file_exists($file['dirname'].'/'.$file['basename'])) {
			return $file['basename'];
		}
	}

	/**
	 * Fungsi ini digunakan untuk mencari nilai dalam array
	 *
	 * This function use for searching value in array
	 *
	 * $needle = string
	 * $needle = array
	*/
	public function search_array($needle, $haystack)
	{
		if (in_array($needle, $haystack)) {
			return true;
		}
		foreach($haystack as $element) {
			if(is_array($element) && $this->search_array($needle, $element))
				return true;
		}
		return false;
	}

	/**
	 * Fungsi ini digunakan untuk mengetahui file gambar
	 *
	 * This function use for know image file
	 *
	 * $img = image path
	*/
	public function isImage($img)
	{
		if (!getimagesize($img)){
			return false;
		} else {
			return true;
		}
	}

}