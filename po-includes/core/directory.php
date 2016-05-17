<?php
/**
 *
 * - PopojiCMS Core
 *
 * - File : directory.php
 * - Version : 1.0
 * - Author : Jenuar Dalapang
 * - License : MIT License
 *
 *
 * Ini adalah library untuk menangani proses direktori.
 * This is library for handling directory process.
 *
 * Contoh untuk penggunaan class ini
 * Example for uses this class
 *
 *
 * $dir = new PoDirectory;
 * $dir->deleteDir(upload/images);
 *
*/

class PoDirectory
{

	function __construct(){}

	/**
	 * Fungsi ini digunakan untuk menghapus direktori
	 *
	 * This function use for delete directory
	 *
	 * $dirname = string
	*/
	public function deleteDir($dirname)
	{
		// Cek direktori ada
		// Sanity check
		if (!file_exists($dirname)) {
			return false;
		}
		// Hapus file atau direktori
		// Simple delete for a file
		if (is_file($dirname) || is_link($dirname)) {
			return unlink($dirname);
		}
		// Membuat iterate stack
		// Create and iterate stack
		$stack = array($dirname);
		while($entry = array_pop($stack))
		{
			// Melihat symlinks
			// Watch for symlinks
			if (is_link($entry)){
				unlink($entry);
				continue;
			}
			// Menghapus direktori
			// Attempt to remove the directory
			if (@rmdir($entry)) {
				continue;
			}
			// Jika tidak ditambahkan ke stack
			// Otherwise add it to the stack
			$stack[] = $entry;
			$dh = opendir($entry);
			while(false !== $child = readdir($dh))
			{
				// Abaikan pointer
				// Ignore pointers
				if ($child === '.' || $child === '..') {
					continue;
				}
				// Hapus file dan menambah direktori ke stack
				// Unlink files and add directories to stack
				$child = $entry . DIRECTORY_SEPARATOR . $child;
				if (is_dir($child) && !is_link($child)) {
					$stack[] = $child;
				} else {
					unlink($child);
				}
			}
			closedir($dh);
		}
		return true;
	}

	/**
	 * Fungsi ini digunakan untuk menampilkan daftar direktori
	 *
	 * This function use for showing directory list
	 *
	 * $dirname = string
	*/
	public function listDir($dirname)
	{
		$list_dir = array();
		if((file_exists($dirname)) && (is_dir($dirname))) {
			$handle = opendir ($dirname);
			if ($handle) {
				while (($file = readdir($handle)) !== false){
					if ($file != "." AND $file != "..") {
						$list_dir[] = $file;
					}
				}
				closedir($handle);
			}
		}
		return($list_dir);
	}

}