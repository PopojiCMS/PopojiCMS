<?php
/*
 *
 * - PopojiCMS Front End File
 *
 * - File : category.php
 * - Version : 1.0
 * - Author : Jenuar Dalapang
 * - License : MIT License
 *
 *
 * Ini adalah file php yang di gunakan untuk menangani proses di bagian depan untuk halaman kategori.
 * This is a php file for handling front end process for category page.
 *
*/

/**
 * Router untuk menampilkan request halaman kategori.
 *
 * Router for display request in category page.
 *
 * $seotitle = string [a-z0-9_-]
*/
$router->match('GET|POST', '/category/([a-z0-9_-]+)', function($seotitle) use ($core, $templates) {
	$lang = $core->setlang('category', WEB_LANG);
	if ($seotitle == 'all') {
		$category = array(
			'title' => $lang['front_all_category'],
			'seotitle' => 'all',
			'picture' => ''
		);
	} else {
		$category = $core->podb->from('category')
			->select(array('category_description.title'))
			->leftJoin('category_description ON category_description.id_category = category.id_category')
			->where('category.seotitle', $seotitle)
			->where('category_description.id_language', WEB_LANG_ID)
			->where('category.active', 'Y')
			->limit(1)
			->fetch();
	}
	if ($category) {
		$info = array(
			'page_title' => $category['title'],
			'page_desc' => $category['title'].' - '.$core->posetting[2]['value'],
			'page_key' => $category['title'],
			'social_mod' => $lang['front_category_title'],
			'social_name' => $core->posetting[0]['value'],
			'social_url' => $core->posetting[1]['value'].'/category/'.$category['seotitle'],
			'social_title' => $category['title'],
			'social_desc' => $category['title'].' - '.$core->posetting[2]['value'],
			'social_img' => $core->posetting[1]['value'].'/'.DIR_CON.'/uploads/'.$category['picture'],
			'page' => '1'
		);
		$adddata = array_merge($info, $lang);
		$templates->addData(
			$adddata
		);
		echo $templates->render('category', compact('category','lang'));
	} else {
		$info = array(
			'page_title' => $lang['front_category_not_found'],
			'page_desc' => $core->posetting[2]['value'],
			'page_key' => $core->posetting[3]['value'],
			'social_mod' => $lang['front_category_title'],
			'social_name' => $core->posetting[0]['value'],
			'social_url' => $core->posetting[1]['value'],
			'social_title' => $lang['front_category_not_found'],
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
 * Router untuk menampilkan request halaman category dengan nomor halaman.
 *
 * Router for display request in category page with pagination.
 *
 * $seotitle = string [a-z0-9_-]
 * $page = integer
*/
$router->match('GET|POST', '/category/([a-z0-9_-]+)/page/(\d+)', function($seotitle, $page) use ($core, $templates) {
	$lang = $core->setlang('category', WEB_LANG);
	if ($seotitle == 'all') {
		$category = array(
			'title' => $lang['front_all_category'],
			'seotitle' => 'all',
			'picture' => ''
		);
	} else {
		$category = $core->podb->from('category')
			->select(array('category_description.title'))
			->leftJoin('category_description ON category_description.id_category = category.id_category')
			->where('category.seotitle', $seotitle)
			->where('category_description.id_language', WEB_LANG_ID)
			->where('category.active', 'Y')
			->limit(1)
			->fetch();
	}
	if ($category) {
		$info = array(
			'page_title' => $category['title'],
			'page_desc' => $category['title'].' - '.$core->posetting[2]['value'],
			'page_key' => $category['title'],
			'social_mod' => $lang['front_category_title'],
			'social_name' => $core->posetting[0]['value'],
			'social_url' => $core->posetting[1]['value'].'/category/'.$category['seotitle'],
			'social_title' => $category['title'],
			'social_desc' => $category['title'].' - '.$core->posetting[2]['value'],
			'social_img' => $core->posetting[1]['value'].'/'.DIR_CON.'/uploads/'.$category['picture'],
			'page' => $page
		);
		$adddata = array_merge($info, $lang);
		$templates->addData(
			$adddata
		);
		echo $templates->render('category', compact('category','lang'));
	} else {
		$info = array(
			'page_title' => $lang['front_category_not_found'],
			'page_desc' => $core->posetting[2]['value'],
			'page_key' => $core->posetting[3]['value'],
			'social_mod' => $lang['front_category_title'],
			'social_name' => $core->posetting[0]['value'],
			'social_url' => $core->posetting[1]['value'],
			'social_title' => $lang['front_category_not_found'],
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