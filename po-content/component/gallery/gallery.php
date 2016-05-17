<?php
/*
 *
 * - PopojiCMS Front End File
 *
 * - File : gallery.php
 * - Version : 1.0
 * - Author : Jenuar Dalapang
 * - License : MIT License
 *
 *
 * Ini adalah file php yang di gunakan untuk menangani proses di bagian depan untuk halaman galeri.
 * This is a php file for handling front end process for gallery page.
 *
*/

/**
 * Router untuk menampilkan request halaman album.
 *
 * Router for display request in album page.
 *
*/
$router->match('GET|POST', '/album', function() use ($core, $templates) {
	$lang = $core->setlang('gallery', WEB_LANG);
	$info = array(
		'page_title' => $lang['front_gallery'],
		'page_desc' => $core->posetting[2]['value'],
		'page_key' => $core->posetting[3]['value'],
		'social_mod' => $lang['front_gallery'],
		'social_name' => $core->posetting[0]['value'],
		'social_url' => $core->posetting[1]['value'].'/album',
		'social_title' => $core->posetting[0]['value'],
		'social_desc' => $core->posetting[2]['value'],
		'social_img' => $core->posetting[1]['value'].'/'.DIR_INC.'/images/favicon.png',
		'page' => '1'
	);
	$adddata = array_merge($info, $lang);
	$templates->addData(
		$adddata
	);
	echo $templates->render('album', compact('lang'));
});

/**
 * Router untuk menampilkan request halaman album dengan nomor halaman.
 *
 * Router for display request in album page with pagination.
 *
 * $page = integer
*/
$router->match('GET|POST', '/album/page/(\d+)', function($page) use ($core, $templates) {
	$lang = $core->setlang('gallery', WEB_LANG);
	$info = array(
		'page_title' => $lang['front_gallery'],
		'page_desc' => $core->posetting[2]['value'],
		'page_key' => $core->posetting[3]['value'],
		'social_mod' => $lang['front_gallery'],
		'social_name' => $core->posetting[0]['value'],
		'social_url' => $core->posetting[1]['value'].'/album',
		'social_title' => $core->posetting[0]['value'],
		'social_desc' => $core->posetting[2]['value'],
		'social_img' => $core->posetting[1]['value'].'/'.DIR_INC.'/images/favicon.png',
		'page' => $page
	);
	$adddata = array_merge($info, $lang);
	$templates->addData(
		$adddata
	);
	echo $templates->render('album', compact('lang'));
});

/**
 * Router untuk menampilkan request halaman galeri.
 *
 * Router for display request in gallery page.
 *
 * $alb = string [a-z0-9_-]
*/
$router->match('GET|POST', '/gallery/([a-z0-9_-]+)', function($alb) use ($core, $templates) {
	$lang = $core->setlang('gallery', WEB_LANG);
	$album = $core->podb->from('album')
		->where('seotitle', $core->postring->valid($alb, 'xss'))
		->where('active', 'Y')
		->limit(1)
		->fetch();
	if ($album) {
		$info = array(
			'page_title' => $lang['front_gallery'].' '.$album['title'],
			'page_desc' => $core->posetting[2]['value'],
			'page_key' => $core->posetting[3]['value'],
			'social_mod' => $lang['front_gallery'].' '.$album['title'],
			'social_name' => $core->posetting[0]['value'],
			'social_url' => $core->posetting[1]['value'].'/gallery/'.$album['seotitle'],
			'social_title' => $core->posetting[0]['value'],
			'social_desc' => $core->posetting[2]['value'],
			'social_img' => $core->posetting[1]['value'].'/'.DIR_INC.'/images/favicon.png',
			'page' => '1'
		);
		$adddata = array_merge($info, $lang);
		$templates->addData(
			$adddata
		);
		echo $templates->render('gallery', compact('album','lang'));
	} else {
		$info = array(
			'page_title' => $lang['front_gallery_not_found'],
			'page_desc' => $core->posetting[2]['value'],
			'page_key' => $core->posetting[3]['value'],
			'social_mod' => $lang['front_gallery'],
			'social_name' => $core->posetting[0]['value'],
			'social_url' => $core->posetting[1]['value'],
			'social_title' => $lang['front_gallery_not_found'],
			'social_desc' => $core->posetting[2]['value'],
			'social_img' => $core->posetting[1]['value'].'/'.DIR_INC.'/images/favicon.png'
		);
		$adddata = array_merge($info, $lang);
		$templates->addData(
			$adddata
		);
		echo $templates->render('404', compact('lang'));
	}
});

/**
 * Router untuk menampilkan request halaman galeri dengan nomor halaman.
 *
 * Router for display request in gallery page with pagination.
 *
 * $alb = string [a-z0-9_-]
 * $page = integer
*/
$router->match('GET|POST', '/gallery/([a-z0-9_-]+)/page/(\d+)', function($alb, $page) use ($core, $templates) {
	$lang = $core->setlang('gallery', WEB_LANG);
	$album = $core->podb->from('album')
		->where('seotitle', $core->postring->valid($alb, 'xss'))
		->where('active', 'Y')
		->limit(1)
		->fetch();
	if ($album) {
		$info = array(
			'page_title' => $lang['front_gallery'].' '.$album['title'],
			'page_desc' => $core->posetting[2]['value'],
			'page_key' => $core->posetting[3]['value'],
			'social_mod' => $lang['front_gallery'].' '.$album['title'],
			'social_name' => $core->posetting[0]['value'],
			'social_url' => $core->posetting[1]['value'].'/gallery/'.$album['seotitle'],
			'social_title' => $core->posetting[0]['value'],
			'social_desc' => $core->posetting[2]['value'],
			'social_img' => $core->posetting[1]['value'].'/'.DIR_INC.'/images/favicon.png',
			'page' => $page
		);
		$adddata = array_merge($info, $lang);
		$templates->addData(
			$adddata
		);
		echo $templates->render('gallery', compact('album','lang'));
	} else {
		$info = array(
			'page_title' => $lang['front_gallery_not_found'],
			'page_desc' => $core->posetting[2]['value'],
			'page_key' => $core->posetting[3]['value'],
			'social_mod' => $lang['front_gallery'],
			'social_name' => $core->posetting[0]['value'],
			'social_url' => $core->posetting[1]['value'],
			'social_title' => $lang['front_gallery_not_found'],
			'social_desc' => $core->posetting[2]['value'],
			'social_img' => $core->posetting[1]['value'].'/'.DIR_INC.'/images/favicon.png'
		);
		$adddata = array_merge($info, $lang);
		$templates->addData(
			$adddata
		);
		echo $templates->render('404', compact('lang'));
	}
});