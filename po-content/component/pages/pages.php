<?php
/*
 *
 * - PopojiCMS Front End File
 *
 * - File : pages.php
 * - Version : 1.0
 * - Author : Jenuar Dalapang
 * - License : MIT License
 *
 *
 * Ini adalah file php yang di gunakan untuk menangani proses di bagian depan untuk halaman pages.
 * This is a php file for handling front end process for pages page.
 *
*/

/**
 * Router untuk menampilkan request halaman pages.
 *
 * Router for display request in pages page.
 *
 * $seotitle = string [a-z0-9_-]
*/
$router->match('GET|POST', '/pages/([a-z0-9_-]+)', function($seotitle) use ($core, $templates) {
	$lang = $core->setlang('pages', WEB_LANG);
	$pages = $core->podb->from('pages')
		->select(array('pages_description.title', 'pages_description.content'))
		->leftJoin('pages_description ON pages_description.id_pages = pages.id_pages')
		->where('pages.seotitle', $seotitle)
		->where('pages_description.id_language', WEB_LANG_ID)
		->where('pages.active', 'Y')
		->limit(1)
		->fetch();
	if ($pages) {
		$info = array(
			'page_title' => $pages['title'],
			'page_desc' => $core->postring->cuthighlight('post', $pages['content'], '60'),
			'page_key' => $pages['title'],
			'social_mod' => $lang['front_pages_title'],
			'social_name' => $core->posetting[0]['value'],
			'social_url' => $core->posetting[1]['value'].'/pages/'.$pages['seotitle'],
			'social_title' => $pages['title'],
			'social_desc' => $core->postring->cuthighlight('post', $pages['content'], '60'),
			'social_img' => $core->posetting[1]['value'].'/'.DIR_CON.'/uploads/'.$pages['picture']
		);
		$adddata = array_merge($info, $lang);
		$templates->addData(
			$adddata
		);
		echo $templates->render('pages', compact('pages','lang'));
	} else {
		$info = array(
			'page_title' => $lang['front_pages_not_found'],
			'page_desc' => $core->posetting[2]['value'],
			'page_key' => $core->posetting[3]['value'],
			'social_mod' => $lang['front_pages_title'],
			'social_name' => $core->posetting[0]['value'],
			'social_url' => $core->posetting[1]['value'],
			'social_title' => $lang['front_pages_not_found'],
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