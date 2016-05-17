<?php
/*
 *
 * - PopojiCMS Widget File
 *
 * - File : oauth.php
 * - Version : 1.0
 * - Author : Jenuar Dalapang
 * - License : MIT License
 *
 *
 * Ini adalah file php yang di gunakan untuk menangani proses di bagian depan untuk widget media sosial.
 * This is a php file for handling front end process for social media.
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

require_once('po-content/widget/oauth/twitter.php');

/**
 * Mendeklarasikan class widget diharuskan dengan mengimplementasikan class ExtensionInterface (diharuskan).
 *
 * Declaration widget class must with implements ExtensionInterface class (require).
 *
*/
class Oauth implements ExtensionInterface
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
        $templates->registerFunction('oauth', [$this, 'getObject']);
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
	 * Fungsi ini digunakan untuk mengambil jumlah share facebook.
	 *
	 * This function use to get facebook share count.
	 *
	 * $url = string url
	*/
    public function getFacebookCount($url)
    {
		if ($this->core->porequest->check_internet_connection()) {
			$rest_url = "http://api.facebook.com/restserver.php?format=json&method=links.getStats&urls=".urlencode($url);
			$json = json_decode(file_get_contents($rest_url), true);
			return $json[0]['share_count'];
		} else {
			return 0;
		}
    }

	/**
	 * Fungsi ini digunakan untuk mengambil jumlah share twitter.
	 *
	 * This function use to get twitter share count.
	 *
	 * $screen_name = string twitter name
	*/
	public function getTwitterCount($screen_name)
    {
		if ($this->core->porequest->check_internet_connection()) {
			$count = -1;
			$twoauth = $this->core->podb->from('oauth')
				->where('id_oauth', '2')
				->limit(1)
				->fetch();
			$settings = array(
				'twitter_user' => $screen_name,
				'consumer_key' => $twoauth['oauth_key'],
				'consumer_secret' => $twoauth['oauth_secret'],
				'oauth_access_token' => $twoauth['oauth_token1'],
				'oauth_access_token_secret' => $twoauth['oauth_token2'],
			);
			$apiUrl = "https://api.twitter.com/1.1/users/show.json";
			$requestMethod = 'GET';
			$getField = '?screen_name='.$settings['twitter_user'];
			$twitter = new TwitterAPIExchange($settings);
			$response = $twitter->setGetfield($getField)->buildOauth($apiUrl, $requestMethod)->performRequest();
			$followers = json_decode($response);
			$count = (empty($followers->followers_count) ? '0' : $followers->followers_count);
			return $count;
		} else {
			return 0;
		}
    }

	/**
	 * Fungsi ini digunakan untuk mengambil jumlah langganan.
	 *
	 * This function use to get subscriber count.
	 *
	*/
	public function getSubscribeCount()
    {
		$subscribe = $this->core->podb->from('subscribe')->count();
        return $subscribe;
	}

}