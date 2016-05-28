<?php
/**
 *
 * - PopojiCMS Core
 *
 * - File : datetime.php
 * - Version : 1.0
 * - Author : Jenuar Dalapang
 * - License : MIT License
 *
 *
 * Ini adalah library untuk menangani tanggal dan waktu.
 * This is library for handling date and time.
 *
 * Contoh untuk penggunaan class ini
 * Example for uses this class
 *
 *
 * $datetime = new PoDateTime;
 * echo $datetime->date_now();
 *
*/

class PoDateTime
{

	/**
	 * Deklarasi variabel utama
	 *
	 * Declaration main variable
	 *
	*/
	public $week = array();
	public $day;
	public $today;
	public $date_now;
	public $day_now;
	public $month_now;
	public $year_now;
	public $time_now;
	public $nama_bln = array();

	function __construct()
	{
		date_default_timezone_set('UTC');
		$this->week = array("Minggu","Senin","Selasa","Rabu","Kamis","Jumat","Sabtu");
		$this->day = date("w");
		$this->today = $this->week[$this->day];
		$this->date_now = date("Y-m-d");
		$this->day_now = date("d");
		$this->month_now = date("m");
		$this->year_now = date("Y");
		$this->time_now = date("H:i:s");
		$this->nama_bln = array(1=> "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
	}

	/**
	 * Fungsi ini digunakan untuk membuat daftar timezone
	 *
	 * This function use for create timezone list
	 *
	*/
	public function timezoneList()
	{
		$timezoneIdentifiers = DateTimeZone::listIdentifiers();
		$utcTime = new DateTime('now', new DateTimeZone('UTC'));
		$tempTimezones = array();
		foreach($timezoneIdentifiers as $timezoneIdentifier)
		{
			$currentTimezone = new DateTimeZone($timezoneIdentifier);
			$tempTimezones[] = array(
				'offset' => (int)$currentTimezone->getOffset($utcTime),
				'identifier' => $timezoneIdentifier
			);
		}
		function sort_list($a, $b)
		{
			return ($a['offset'] == $b['offset']) 
				? strcmp($a['identifier'], $b['identifier'])
				: $a['offset'] - $b['offset'];
		}
		usort($tempTimezones, "sort_list");
		$timezoneList = array();
		foreach($tempTimezones as $tz)
		{
			$sign = ($tz['offset'] > 0) ? '+' : '-';
			$offset = gmdate('H:i', abs($tz['offset']));
			$timezoneList[$tz['identifier']] = '(UTC ' . $sign . $offset . ') ' .
				$tz['identifier'];
		}
		return $timezoneList;
	}

	/**
	 * Fungsi ini digunakan untuk membuat tanggal dalam bahasa Indonesia
	 *
	 * This function use for create date in Indonesian language
	 *
	 * $tgl = date
	*/
	public function tgl_indo($tgl)
	{
		$tanggal = substr($tgl,8,2);
		$bulan = $this->getBulan(substr($tgl,5,2));
		$tahun = substr($tgl,0,4);
		return $tanggal.' '.$bulan.' '.$tahun;		 
	}

	/**
	 * Fungsi ini digunakan untuk membuat nama bulan dalam bahasa Indonesia
	 *
	 * This function use for create month in Indonesian language
	 *
	 * $bln = integer
	*/
	public function getBulan($bln)
	{
		switch($bln){
			case 1: 
				return "Januari";
			break;
			case 2:
				return "Februari";
			break;
			case 3:
				return "Maret";
			break;
			case 4:
				return "April";
			break;
			case 5:
				return "Mei";
			break;
			case 6:
				return "Juni";
			break;
			case 7:
				return "Juli";
			break;
			case 8:
				return "Agustus";
			break;
			case 9:
				return "September";
			break;
			case 10:
				return "Oktober";
			break;
			case 11:
				return "November";
			break;
			case 12:
				return "Desember";
			break;
		}
	}

	/**
	 * Fungsi ini digunakan untuk membuat nama bulan dalam bahasa Indonesia versi pendek
	 *
	 * This function use for create month in Indonesian language short version
	 *
	 * $bln = integer
	*/
	public function getBulanShrt($bln)
	{
		switch($bln)
		{
			case 1: 
				return "Jan";
			break;
			case 2:
				return "Feb";
			break;
			case 3:
				return "Mar";
			break;
			case 4:
				return "Apr";
			break;
			case 5:
				return "Mei";
			break;
			case 6:
				return "Jun";
			break;
			case 7:
				return "Jul";
			break;
			case 8:
				return "Agu";
			break;
			case 9:
				return "Sep";
			break;
			case 10:
				return "Okt";
			break;
			case 11:
				return "Nov";
			break;
			case 12:
				return "Des";
			break;
		}
	}

}