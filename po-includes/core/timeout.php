<?php
/**
 *
 * - PopojiCMS Core
 *
 * - File : timeout.php
 * - Version : 1.0
 * - Author : Jenuar Dalapang
 * - License : MIT License
 *
 *
 * Ini adalah library untuk menangani proses timeout user yang aktif.
 * This is library for handling user timeout process active.
 *
 * Contoh untuk penggunaan class ini
 * Example for uses this class
 *
 *
 * $timeout = new PoTimeout;
 * $timeout->timer();
 * $timeout->cek_login();
 *
*/

class PoTimeout
{

	function __construct(){}

	/**
	 * Fungsi ini digunakan untuk mencatat sesi aktif
	 *
	 * This function use for record active session
	 *
	*/
	public function rec_session($session = array())
	{
		$_SESSION['iduser'] = $session['id_user'];
		$_SESSION['namauser'] = $session['username'];
		$_SESSION['namalengkap'] = $session['nama_lengkap'];
		$_SESSION['passuser'] = $session['password'];
		$_SESSION['leveluser'] = $session['level'];
		$_SESSION['menuuser'] = $session['menu'];
		$_SESSION['login'] = 1;
	}

	/**
	 * Fungsi ini digunakan untuk mencatat sesi timeout user
	 *
	 * This function use for record user session timeout
	 *
	*/
	public function timer()
	{
		$time = 10000;
		$_SESSION['timeout'] = time() + $time;
	}

	/**
	 * Fungsi ini digunakan untuk mengecek sesi login user
	 *
	 * This function use for checking user session login
	 *
	*/
	public function check_login()
	{
		$timeout = $_SESSION['timeout'];
		if (time() < $timeout) {
			$this->timer();
			return true;
		} else {
			unset($_SESSION['timeout']);
			return false;
		}
	}

	/**
	 * Fungsi ini digunakan untuk mencatat sesi aktif member
	 *
	 * This function use for record active session member
	 *
	*/
	public function rec_session_member($session = array())
	{
		$_SESSION['iduser_member'] = $session['id_user'];
		$_SESSION['namauser_member'] = $session['username'];
		$_SESSION['namalengkap_member'] = $session['nama_lengkap'];
		$_SESSION['passuser_member'] = $session['password'];
		$_SESSION['leveluser_member'] = $session['level'];
		$_SESSION['login_member'] = 1;
	}

	/**
	 * Fungsi ini digunakan untuk mencatat sesi timeout member
	 *
	 * This function use for record member session timeout
	 *
	*/
	public function timer_member()
	{
		$time = 10000;
		$_SESSION['timeout_member'] = time() + $time;
	}

	/**
	 * Fungsi ini digunakan untuk mengecek sesi login member
	 *
	 * This function use for checking member session login
	 *
	*/
	public function check_login_member()
	{
		$timeout = $_SESSION['timeout_member'];
		if (time() < $timeout) {
			$this->timer();
			return true;
		} else {
			unset($_SESSION['timeout_member']);
			return false;
		}
	}

}