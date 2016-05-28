<?php
/*
 *
 * - PopojiCMS Front End File
 *
 * - File : post.php
 * - Version : 1.0
 * - Author : Jenuar Dalapang
 * - License : MIT License
 *
 *
 * Ini adalah file php yang di gunakan untuk menangani proses di bagian depan untuk halaman post.
 * This is a php file for handling front end process for post page.
 *
*/

/**
 * Router untuk menampilkan request halaman post.
 *
 * Router for display request in post page.
 *
 * $seotitle = string [a-z0-9_-]
*/
$router->match('GET|POST', '/detailpost/([a-z0-9_-]+)', function($seotitle) use ($core, $templates) {
	$lang = $core->setlang('post', WEB_LANG);
	$post = $core->podb->from('post')
		->select(array('post_description.title', 'post_description.content'))
		->leftJoin('post_description ON post_description.id_post = post.id_post')
		->where('post.seotitle', $seotitle)
		->where('post_description.id_language', WEB_LANG_ID)
		->where('post.active', 'Y')
		->where('post.publishdate < ?', date('Y-m-d H:i:s'))
		->limit(1)
		->fetch();
	if ($post) {
		if (!empty($_POST)) {
			require_once(DIR_INC.'/core/vendor/recaptcha/recaptchalib.php');
			$secret = $core->posetting[22]['value'];
			$recaptcha = new PoReCaptcha($secret);
			if (!empty($_POST["g-recaptcha-response"])) {
				$resp = $recaptcha->verifyResponse(
					$_SERVER["REMOTE_ADDR"],
					$_POST["g-recaptcha-response"]
				);
				if ($resp != null && $resp->success) {
					$core->poval->validation_rules(array(
						'id' => 'required|integer',
						'id_parent' => 'required|integer',
						'name' => 'required|max_len,100|min_len,3',
						'email' => 'required|valid_email',
						'url' => 'max_len,255|valid_url',
						'comment' => 'required|min_len,10',
						'seotitle' => 'required'
					));
					$core->poval->filter_rules(array(
						'id' => 'trim|sanitize_numbers',
						'id_parent' => 'trim',
						'name' => 'trim|sanitize_string',
						'email' => 'trim|sanitize_email',
						'url' => 'trim|sanitize_string',
						'comment' => 'trim|sanitize_string|basic_tags',
						'seotitle' => 'trim'
					));
					$validated_data = $core->poval->run($_POST);
					if ($validated_data === false) {
						$core->poflash->error($lang['front_comment_error_3']);
					} else {
						if ($core->posetting[18]['value'] == 'Y') {
							$active = 'Y';
						} else {
							$active = 'N';
						}
						$data = array(
							'id_post' => $_POST['id'],
							'id_parent' => $_POST['id_parent'],
							'name' => $_POST['name'],
							'email' => $_POST['email'],
							'url' => $_POST['url'],
							'comment' => $_POST['comment'],
							'date' => date('Y-m-d'),
							'time' => date('h:i:s'),
							'active' => $active
						);
						$query = $core->podb->insertInto('comment')->values($data);
						$query->execute();
						unset($_POST);
						$core->poflash->success($lang['front_comment_success']);
					}
				} else {
					$core->poflash->error($lang['front_comment_error_2']);
				}
			} else {
				$core->poflash->error($lang['front_comment_error_1']);
			}
		}
		$query_hits = $core->podb->update('post')
			->set(array('hits' => $post['hits']+1))
			->where('id_post', $post['id_post']);
		$query_hits->execute();
		$info = array(
			'page_title' => $post['title'],
			'page_desc' => $core->postring->cuthighlight('post', $post['content'], '150'),
			'page_key' => $post['tag'],
			'social_mod' => $lang['front_post_title'],
			'social_name' => $core->posetting[0]['value'],
			'social_url' => $core->posetting[1]['value'].'/detailpost/'.$post['seotitle'],
			'social_title' => $post['title'],
			'social_desc' => $core->postring->cuthighlight('post', $post['content'], '150'),
			'social_img' => $core->posetting[1]['value'].'/'.DIR_CON.'/uploads/'.$post['picture'],
			'page' => '1'
		);
		$adddata = array_merge($info, $lang);
		$templates->addData(
			$adddata
		);
		echo $templates->render('detailpost', compact('post','lang'));
	} else {
		$info = array(
			'page_title' => $lang['front_post_not_found'],
			'page_desc' => $core->posetting[2]['value'],
			'page_key' => $core->posetting[3]['value'],
			'social_mod' => $lang['front_post_title'],
			'social_name' => $core->posetting[0]['value'],
			'social_url' => $core->posetting[1]['value'],
			'social_title' => $lang['front_post_not_found'],
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
 * Router untuk menampilkan request halaman post dengan nomor halaman.
 *
 * Router for display request in post page with pagination.
 *
 * $seotitle = string [a-z0-9_-]
 * $page = integer
*/
$router->match('GET|POST', '/detailpost/([a-z0-9_-]+)/page/(\d+)', function($seotitle, $page) use ($core, $templates) {
	$lang = $core->setlang('post', WEB_LANG);
	$post = $core->podb->from('post')
		->select(array('post_description.title', 'post_description.content'))
		->leftJoin('post_description ON post_description.id_post = post.id_post')
		->where('post.seotitle', $seotitle)
		->where('post_description.id_language', WEB_LANG_ID)
		->where('post.active', 'Y')
		->where('post.publishdate < ?', date('Y-m-d H:i:s'))
		->limit(1)
		->fetch();
	if ($post) {
		if (!empty($_POST)) {
			require_once(DIR_INC.'/core/vendor/recaptcha/recaptchalib.php');
			$secret = $core->posetting[22]['value'];
			$recaptcha = new PoReCaptcha($secret);
			if (!empty($_POST["g-recaptcha-response"])) {
				$resp = $recaptcha->verifyResponse(
					$_SERVER["REMOTE_ADDR"],
					$_POST["g-recaptcha-response"]
				);
				if ($resp != null && $resp->success) {
					$core->poval->validation_rules(array(
						'id' => 'required|integer',
						'id_parent' => 'required|integer',
						'name' => 'required|max_len,100|min_len,3',
						'email' => 'required|valid_email',
						'url' => 'max_len,255|valid_url',
						'comment' => 'required|min_len,10',
						'seotitle' => 'required'
					));
					$core->poval->filter_rules(array(
						'id' => 'trim|sanitize_numbers',
						'id_parent' => 'trim',
						'name' => 'trim|sanitize_string',
						'email' => 'trim|sanitize_email',
						'url' => 'trim|sanitize_string',
						'comment' => 'trim|sanitize_string|basic_tags',
						'seotitle' => 'trim'
					));
					$validated_data = $core->poval->run($_POST);
					if ($validated_data === false) {
						$core->poflash->error($lang['front_comment_error_3']);
					} else {
						if ($core->posetting[18]['value'] == 'Y') {
							$active = 'Y';
						} else {
							$active = 'N';
						}
						$data = array(
							'id_post' => $_POST['id'],
							'id_parent' => $_POST['id_parent'],
							'name' => $_POST['name'],
							'email' => $_POST['email'],
							'url' => $_POST['url'],
							'comment' => $_POST['comment'],
							'date' => date('Y-m-d'),
							'time' => date('h:i:s'),
							'active' => $active
						);
						$query = $core->podb->insertInto('comment')->values($data);
						$query->execute();
						unset($_POST);
						$core->poflash->success($lang['front_comment_success']);
					}
				} else {
					$core->poflash->error($lang['front_comment_error_2']);
				}
			} else {
				$core->poflash->error($lang['front_comment_error_1']);
			}
		}
		$query_hits = $core->podb->update('post')
			->set(array('hits' => $post['hits']+1))
			->where('id_post', $post['id_post']);
		$query_hits->execute();
		$info = array(
			'page_title' => $post['title'],
			'page_desc' => $core->postring->cuthighlight('post', $post['content'], '150'),
			'page_key' => $post['tag'],
			'social_mod' => $lang['front_post_title'],
			'social_name' => $core->posetting[0]['value'],
			'social_url' => $core->posetting[1]['value'].'/detailpost/'.$post['seotitle'],
			'social_title' => $post['title'],
			'social_desc' => $core->postring->cuthighlight('post', $post['content'], '150'),
			'social_img' => $core->posetting[1]['value'].'/'.DIR_CON.'/uploads/'.$post['picture'],
			'page' => $page
		);
		$adddata = array_merge($info, $lang);
		$templates->addData(
			$adddata
		);
		echo $templates->render('detailpost', compact('post','lang'));
	} else {
		$info = array(
			'page_title' => $lang['front_post_not_found'],
			'page_desc' => $core->posetting[2]['value'],
			'page_key' => $core->posetting[3]['value'],
			'social_mod' => $lang['front_post_title'],
			'social_name' => $core->posetting[0]['value'],
			'social_url' => $core->posetting[1]['value'],
			'social_title' => $lang['front_post_not_found'],
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
 * Router untuk menampilkan request halaman pencarian.
 *
 * Router for display request in search page.
 *
*/
$router->match('GET|POST', '/search', function() use ($core, $templates) {
	$lang = $core->setlang('post', WEB_LANG);
	if (!empty($_POST['search'])) {
		$query_seo = $core->postring->seo_title($core->postring->valid($_POST['search'], 'xss'));
		header('location:'.BASE_URL.'/search/'.$query_seo);
	} else {
		$info = array(
			'page_title' => $lang['front_search_not_found'],
			'page_desc' => $core->posetting[2]['value'],
			'page_key' => $core->posetting[3]['value'],
			'social_mod' => $lang['front_search_title'],
			'social_name' => $core->posetting[0]['value'],
			'social_url' => $core->posetting[1]['value'],
			'social_title' => $lang['front_search_not_found'],
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
 * Router untuk menampilkan request halaman pencarian.
 *
 * Router for display request in search page.
 *
 * $query = string [a-z0-9_-]
*/
$router->match('GET|POST', '/search/([a-z0-9_-]+)', function($query) use ($core, $templates) {
	$lang = $core->setlang('post', WEB_LANG);
	$conditions = [
		'post_description.title LIKE "%'.str_replace('-', ' ', $query).'%"',
		'post_description.content LIKE "%'.str_replace('-', ' ', $query).'%"',
		'post.tag LIKE "%'.str_replace('-', ' ', $query).'%"',
	];
	$orWhere = implode(" OR ", $conditions);
	$search = $core->podb->from('post')
		->select(array('post_description.title', 'post_description.content'))
		->leftJoin('post_description ON post_description.id_post = post.id_post')
		->where('('.$orWhere.')')
		->where('post_description.id_language', WEB_LANG_ID)
		->where('post.active', 'Y')
		->where('post.publishdate < ?', date('Y-m-d H:i:s'))
		->limit(1)
		->fetch();
	if ($search) {
		$info = array(
			'page_title' => $lang['front_search_title'].' - '.ucfirst(str_replace('-', ' ', $query)),
			'page_desc' => ucfirst(str_replace('-', ' ', $query)).' - '.$core->posetting[2]['value'],
			'page_key' => ucfirst(str_replace('-', ' ', $query)),
			'social_mod' => $lang['front_search_title'],
			'social_name' => $core->posetting[0]['value'],
			'social_url' => $core->posetting[1]['value'].'/search/'.$query,
			'social_title' => ucfirst(str_replace('-', ' ', $query)),
			'social_desc' => ucfirst(str_replace('-', ' ', $query)).' - '.$core->posetting[2]['value'],
			'social_img' => $core->posetting[1]['value'].'/'.DIR_INC.'/images/favicon.png',
			'query' => $query,
			'page' => '1'
		);
		$adddata = array_merge($info, $lang);
		$templates->addData(
			$adddata
		);
		echo $templates->render('search', compact('search','lang'));
	} else {
		$info = array(
			'page_title' => $lang['front_search_not_found'],
			'page_desc' => $core->posetting[2]['value'],
			'page_key' => $core->posetting[3]['value'],
			'social_mod' => $lang['front_search_title'],
			'social_name' => $core->posetting[0]['value'],
			'social_url' => $core->posetting[1]['value'],
			'social_title' => $lang['front_search_not_found'],
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
 * Router untuk menampilkan request halaman pencarian dengan nomor halaman.
 *
 * Router for display request in search page with pagination.
 *
 * $query = string [a-z0-9_-]
 * $page = integer
*/
$router->match('GET|POST', '/search/([a-z0-9_-]+)/page/(\d+)', function($query, $page) use ($core, $templates) {
	$lang = $core->setlang('post', WEB_LANG);
	$conditions = [
		'post_description.title LIKE "%'.str_replace('-', ' ', $query).'%"',
		'post_description.content LIKE "%'.str_replace('-', ' ', $query).'%"',
		'post.tag LIKE "%'.str_replace('-', ' ', $query).'%"',
	];
	$orWhere = implode(" OR ", $conditions);
	$search = $core->podb->from('post')
		->select(array('post_description.title', 'post_description.content'))
		->leftJoin('post_description ON post_description.id_post = post.id_post')
		->where('('.$orWhere.')')
		->where('post_description.id_language', WEB_LANG_ID)
		->where('post.active', 'Y')
		->where('post.publishdate < ?', date('Y-m-d H:i:s'))
		->limit(1)
		->fetch();
	if ($search) {
		$info = array(
			'page_title' => $lang['front_search_title'].' - '.ucfirst(str_replace('-', ' ', $query)),
			'page_desc' => ucfirst(str_replace('-', ' ', $query)).' - '.$core->posetting[2]['value'],
			'page_key' => ucfirst(str_replace('-', ' ', $query)),
			'social_mod' => $lang['front_search_title'],
			'social_name' => $core->posetting[0]['value'],
			'social_url' => $core->posetting[1]['value'].'/search/'.$query,
			'social_title' => ucfirst(str_replace('-', ' ', $query)),
			'social_desc' => ucfirst(str_replace('-', ' ', $query)).' - '.$core->posetting[2]['value'],
			'social_img' => $core->posetting[1]['value'].'/'.DIR_INC.'/images/favicon.png',
			'query' => $query,
			'page' => $page
		);
		$adddata = array_merge($info, $lang);
		$templates->addData(
			$adddata
		);
		echo $templates->render('search', compact('search','lang'));
	} else {
		$info = array(
			'page_title' => $lang['front_search_not_found'],
			'page_desc' => $core->posetting[2]['value'],
			'page_key' => $core->posetting[3]['value'],
			'social_mod' => $lang['front_search_title'],
			'social_name' => $core->posetting[0]['value'],
			'social_url' => $core->posetting[1]['value'],
			'social_title' => $lang['front_search_not_found'],
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
 * Router untuk menampilkan request halaman post member.
 *
 * Router for display request in member post page.
 *
*/
$router->match('GET|POST', '/member/post', function() use ($core, $templates) {
	if ($core->posetting[17]['value'] == 'Y') {
		if (empty($_SESSION['namauser_member']) AND empty($_SESSION['passuser_member']) AND empty($_SESSION['login_member'])) {
			header('location:'.BASE_URL.'/member/login');
		} else {
			$alertmsg = '';
			$lang = $core->setlang('post', WEB_LANG);
			$info = array(
				'page_title' => $lang['front_member_allpost'].' - Member Area',
				'alertmsg' => $alertmsg
			);
			$adddata = array_merge($info, $lang);
			$templates->addData(
				$adddata
			);
			echo $templates->render('post', compact('lang'));
		}
	} else {
		header('location:'.BASE_URL.'/404.php');
	}
});

/**
 * Router untuk menampilkan request datatable pada halaman post member.
 *
 * Router for display request datatable in member post page.
 *
*/
$router->match('GET|POST', '/member/post/datatable', function() use ($core, $templates) {
	if ($core->posetting[17]['value'] == 'Y') {
		if (empty($_SESSION['namauser_member']) AND empty($_SESSION['passuser_member']) AND empty($_SESSION['login_member'])) {
			header('location:'.BASE_URL.'/member/login');
		} else {
			if (!$core->auth($_SESSION['leveluser_member'], 'post', 'read')) {
				header('location:'.BASE_URL.'/404.php');
			} else {
				$GLOBALS['pocore'] = $core;
				$lang = $core->setlang('post', WEB_LANG);
				$GLOBALS['polang'] = $lang;
				$table = 'post';
				$primarykey = 'id_post';
				$columns = array(
					array('db' => 'p.'.$primarykey, 'dt' => '0', 'field' => $primarykey,
						'formatter' => function($d, $row, $i){
							return "<div class='text-center'>\n
								<input type='checkbox' id='titleCheckdel' />\n
								<input type='hidden' class='deldata' name='item[".$i."][deldata]' value='".$d."' disabled />\n
							</div>\n";
						}
					),
					array('db' => 'p.'.$primarykey, 'dt' => '1', 'field' => $primarykey),
					array('db' => 'p.'.$primarykey, 'dt' => '2', 'field' => $primarykey,
						'formatter' => function($d, $row, $i){
							$post_cats = $GLOBALS['pocore']->podb->from('post_category')
								->where('id_post', $d)
								->fetchAll();
							$cats = '';
							foreach($post_cats as $post_cat) {
								$cat_desc = $GLOBALS['pocore']->podb->from('category_description')
									->where('id_category', $post_cat['id_category'])
									->fetch();
								$cats .= $cat_desc['title']." - ";
							}
							return rtrim($cats, " - ");
						}
					),
					array('db' => 'pd.title', 'dt' => '3', 'field' => 'title',
						'formatter' => function($d, $row, $i){
							if ($row['active'] == 'Y') {
								$sactive = "<i class='fa fa-eye'></i> {$GLOBALS['polang']['post_active']}";
							} else {
								$sactive = "<i class='fa fa-eye-slash'></i> {$GLOBALS['polang']['post_not_active']}";
							}
							if ($row['headline'] == 'Y') {
								$headline = "<i class='fa fa-star text-warning'></i> {$GLOBALS['polang']['post_headline']}";
							} else {
								$headline = "<i class='fa fa-star'></i> {$GLOBALS['polang']['post_not_headline']}";
							}
							return "".$d."<br /><i><a href='".WEB_URL."detailpost/".$GLOBALS['pocore']->postring->seo_title($d)."' target='_blank'>".WEB_URL."detailpost/".$GLOBALS['pocore']->postring->seo_title($d)."</a></i>";
						}
					),
					array('db' => 'p.active', 'dt' => '4', 'field' => 'active'),
					array('db' => 'p.'.$primarykey, 'dt' => '5', 'field' => $primarykey,
						'formatter' => function($d, $row, $i){
							return "<div class='text-center'>\n
								<div class='btn-group btn-group-xs'>\n
									<a href='".WEB_URL."member/post/edit/".$d."' class='btn btn-xs btn-default' id='".$d."' data-toggle='tooltip' title='{$GLOBALS['polang']['action_1']}'><i class='fa fa-pencil' style='margin-right:0px;'></i></a>
									<a class='btn btn-xs btn-danger alertdel' id='".$d."' data-toggle='tooltip' title='{$GLOBALS['polang']['action_2']}'><i class='fa fa-times' style='margin-right:0px;'></i></a>
								</div>\n
							</div>\n";
						}
					),
					array('db' => 'p.headline', 'dt' => '', 'field' => 'headline'),
					array('db' => 'u.nama_lengkap', 'dt' => '', 'field' => 'nama_lengkap')
				);
				$joinquery = "FROM post AS p JOIN post_description AS pd ON (pd.id_post = p.id_post) JOIN users AS u ON (u.id_user = p.editor)";
				$extrawhere = "pd.id_language = '1' AND p.editor = '".$_SESSION['iduser_member']."'";
				echo json_encode(SSP::simple($_POST, $GLOBALS['pocore']->poconnect, $table, $primarykey, $columns, $joinquery, $extrawhere));
			}
		}
	} else {
		header('location:'.BASE_URL.'/404.php');
	}
});

/**
 * Router untuk menampilkan request halaman member tambah post.
 *
 * Router for display request in member add post page.
 *
*/
$router->match('GET|POST', '/member/post/addnew', function() use ($core, $templates) {
	if ($core->posetting[17]['value'] == 'Y') {
		if (empty($_SESSION['namauser_member']) AND empty($_SESSION['passuser_member']) AND empty($_SESSION['login_member'])) {
			header('location:'.BASE_URL.'/member/login');
		} else {
			$alertmsg = '';
			$lang = $core->setlang('post', WEB_LANG);
			if (!empty($_POST)) {
				if (!$core->auth($_SESSION['leveluser_member'], 'post', 'create')) {
					header('location:'.BASE_URL.'/404.php');
				} else {
					if ($_POST['seotitle'] != "") {
						$seotitle = $_POST['seotitle'];
					} else {
						$seotitle = $core->postring->seo_title($core->postring->valid($_POST['post'][1]['title'], 'xss'));
					}
					$data = array(
						'seotitle' => $seotitle,
						'tag' => $core->postring->valid($_POST['tag'], 'xss'),
						'picture_description' => $_POST['picture_description'],
						'date' => $_POST['publishdate'],
						'time' => $_POST['publishtime'],
						'publishdate' => $_POST['publishdate']." ".$_POST['publishtime'],
						'editor' => $_SESSION['iduser_member'],
						'comment' => 'N',
						'active' => 'N'
					);
					if(!empty($_FILES['picture']['tmp_name'])){
						$picture_name_exp = explode('.', $_FILES['picture']['name']);
						$picture_name = $core->postring->seo_title($picture_name_exp[0]);
						$file_exists = DIR_CON.'/uploads/'.$picture_name.'.jpg';
						if (file_exists($file_exists)){
							$datapic = array(
								'picture' => $picture_name.'.jpg'
							);
						} else {
							$upload = new PoUpload($_FILES['picture']);
							if ($upload->uploaded) {
								$upload->file_new_name_body = $picture_name;
								$upload->image_convert = 'jpg';
								$upload->image_resize = true;
								$upload->image_x = 900;
								$upload->image_y = 600;
								$upload->image_ratio = true;
								$upload->process(DIR_CON.'/uploads/');
								if ($upload->processed) {
									$datapic = array(
										'picture' => $upload->file_dst_name
									);
									$upload_medium = new PoUpload($_FILES['picture']);
									if ($upload_medium->uploaded) {
										$upload_medium->file_new_name_body = 'medium_'.$picture_name;
										$upload_medium->image_convert = 'jpg';
										$upload_medium->image_resize = true;
										$upload_medium->image_x = 640;
										$upload_medium->image_y = 426;
										$upload_medium->image_ratio = true;
										$upload_medium->process(DIR_CON.'/uploads/medium/');
										if ($upload_medium->processed) {
											$upload_thumb = new PoUpload($_FILES['picture']);
											if ($upload_thumb->uploaded) {
												$upload_thumb->file_new_name_body = $picture_name;
												$upload_thumb->image_convert = 'jpg';
												$upload_thumb->image_resize = true;
												$upload_thumb->image_x = 122;
												$upload_thumb->image_y = 91;
												$upload_thumb->image_ratio = true;
												$upload_thumb->process(DIR_CON.'/thumbs/');
												if ($upload_thumb->processed) {
													$upload_thumb->clean();
													$upload_medium->clean();
													$upload->clean();
												}
											}
										}
									}
								} else {
									$datapic = array();
								}
							}
						}
					} else {
						$datapic = array();
					}
					$datafinal = array_merge($data, $datapic);
					$query_post = $core->podb->insertInto('post')->values($datafinal);
					$query_post->execute();
					$expl = explode(",", $core->postring->valid($_POST['tag'], 'xss'));
					$total = count($expl);
					for($i=0; $i<$total; $i++){
						$last_tag = $core->podb->from('tag')
							->where('tag_seo', $expl[$i])
							->limit(1)
							->fetch();
						$query_tag = $core->podb->update('tag')
							->set(array('count' => $last_tag['count']+1))
							->where('tag_seo', $expl[$i]);
						$query_tag->execute();
					}
					$last_post = $core->podb->from('post')
						->orderBy('id_post DESC')
						->limit(1)
						->fetch();
					$id_categorys = $_POST['id_category'];
					if (!empty($_POST['id_category'])) {
						foreach($id_categorys as $id_category){
							$category = array(
								'id_post' => $last_post['id_post'],
								'id_category' => $id_category,
							);
							$query_category = $core->podb->insertInto('post_category')->values($category);
							$query_category->execute();
						}
					}
					foreach ($_POST['post'] as $id_language => $value) {
						$post_description = array(
							'id_post' => $last_post['id_post'],
							'id_language' => $id_language,
							'title' => $core->postring->valid($value['title'], 'xss'),
							'content' => stripslashes(htmlspecialchars($value['content'],ENT_QUOTES))
						);
						$query_post_description = $core->podb->insertInto('post_description')->values($post_description);
						$query_post_description->execute();
					}
					unset($_POST);
					header('location:'.BASE_URL.'/member/post');
				}
			}
			$info = array(
				'page_title' => $lang['front_member_addpost'].' - Member Area',
				'alertmsg' => $alertmsg
			);
			$adddata = array_merge($info, $lang);
			$templates->addData(
				$adddata
			);
			echo $templates->render('addpost', compact('lang'));
		}
	} else {
		header('location:'.BASE_URL.'/404.php');
	}
});

/**
 * Router untuk menampilkan request halaman member edit post.
 *
 * Router for display request in member edit post page.
 *
*/
$router->match('GET|POST', '/member/post/edit/(\d+)', function($id_post) use ($core, $templates) {
	if ($core->posetting[17]['value'] == 'Y') {
		if (empty($_SESSION['namauser_member']) AND empty($_SESSION['passuser_member']) AND empty($_SESSION['login_member'])) {
			header('location:'.BASE_URL.'/member/login');
		} else {
			$post = $core->podb->from('post')
				->select('post_description.title')
				->leftJoin('post_description ON post_description.id_post = post.id_post')
				->where('post.id_post', $core->postring->valid($id_post, 'sql'))
				->where('post.editor', $_SESSION['iduser_member'])
				->limit(1)
				->fetch();
			if (empty($post)) {
				header('location:'.BASE_URL.'/404.php');
			} else {
				$alertmsg = '';
				$lang = $core->setlang('post', WEB_LANG);
				if (!empty($_POST)) {
					if (!$core->auth($_SESSION['leveluser_member'], 'post', 'update')) {
						header('location:'.BASE_URL.'/404.php');
					} else {
						if ($_POST['seotitle'] != "") {
							$seotitle = $_POST['seotitle'];
						} else {
							$seotitle = $core->postring->seo_title($core->postring->valid($_POST['post'][1]['title'], 'xss'));
						}
						$data = array(
							'seotitle' => $seotitle,
							'tag' => $core->postring->valid($_POST['tag'], 'xss'),
							'picture_description' => $_POST['picture_description'],
							'date' => $_POST['publishdate'],
							'time' => $_POST['publishtime'],
							'publishdate' => $_POST['publishdate']." ".$_POST['publishtime'],
							'comment' => $post['comment'],
							'active' => $post['active']
						);
						if(!empty($_FILES['picture']['tmp_name'])){
							$picture_name_exp = explode('.', $_FILES['picture']['name']);
							$picture_name = $core->postring->seo_title($picture_name_exp[0]);
							$file_exists = DIR_CON.'/uploads/'.$picture_name.'.jpg';
							if (file_exists($file_exists)){
								$datapic = array(
									'picture' => $picture_name.'.jpg'
								);
							} else {
								$upload = new PoUpload($_FILES['picture']);
								if ($upload->uploaded) {
									$upload->file_new_name_body = $picture_name;
									$upload->image_convert = 'jpg';
									$upload->image_resize = true;
									$upload->image_x = 900;
									$upload->image_y = 600;
									$upload->image_ratio = true;
									$upload->process(DIR_CON.'/uploads/');
									if ($upload->processed) {
										$datapic = array(
											'picture' => $upload->file_dst_name
										);
										$upload_medium = new PoUpload($_FILES['picture']);
										if ($upload_medium->uploaded) {
											$upload_medium->file_new_name_body = 'medium_'.$picture_name;
											$upload_medium->image_convert = 'jpg';
											$upload_medium->image_resize = true;
											$upload_medium->image_x = 640;
											$upload_medium->image_y = 426;
											$upload_medium->image_ratio = true;
											$upload_medium->process(DIR_CON.'/uploads/medium/');
											if ($upload_medium->processed) {
												$upload_thumb = new PoUpload($_FILES['picture']);
												if ($upload_thumb->uploaded) {
													$upload_thumb->file_new_name_body = $picture_name;
													$upload_thumb->image_convert = 'jpg';
													$upload_thumb->image_resize = true;
													$upload_thumb->image_x = 122;
													$upload_thumb->image_y = 91;
													$upload_thumb->image_ratio = true;
													$upload_thumb->process(DIR_CON.'/thumbs/');
													if ($upload_thumb->processed) {
														$upload_thumb->clean();
														$upload_medium->clean();
														$upload->clean();
													}
												}
											}
										}
									} else {
										$datapic = array();
									}
								}
							}
						} else {
							$datapic = array();
						}
						$datafinal = array_merge($data, $datapic);
						$query_post = $core->podb->update('post')
							->set($datafinal)
							->where('id_post', $post['id_post']);
						$query_post->execute();
						$expl = explode(",", $core->postring->valid($_POST['tag'], 'xss'));
						$total = count($expl);
						for($i=0; $i<$total; $i++){
							$last_tag = $core->podb->from('tag')
								->where('tag_seo', $expl[$i])
								->limit(1)
								->fetch();
							$query_tag = $core->podb->update('tag')
								->set(array('count' => $last_tag['count']+1))
								->where('tag_seo', $expl[$i]);
							$query_tag->execute();
						}
						$query_del_cats = $core->podb->deleteFrom('post_category')->where('id_post', $post['id_post']);
						$query_del_cats->execute();
						$id_categorys = $_POST['id_category'];
						if (!empty($_POST['id_category'])) {
							foreach($id_categorys as $id_category){
								$category = array(
									'id_post' => $post['id_post'],
									'id_category' => $id_category,
								);
								$query_category = $core->podb->insertInto('post_category')->values($category);
								$query_category->execute();
							}
						}
						foreach ($_POST['post'] as $id_language => $value) {
							$othlang_post = $core->podb->from('post_description')
								->where('id_post', $post['id_post'])
								->where('id_language', $id_language)
								->count();
							if ($othlang_post > 0) {
								$post_description = array(
									'title' => $core->postring->valid($value['title'], 'xss'),
									'content' => stripslashes(htmlspecialchars($value['content'],ENT_QUOTES))
								);
								$query_post_description = $core->podb->update('post_description')
									->set($post_description)
									->where('id_post_description', $core->postring->valid($value['id'], 'sql'));
							} else {
								$post_description = array(
									'id_post' => $post['id_post'],
									'id_language' => $id_language,
									'title' => $core->postring->valid($value['title'], 'xss'),
									'content' => stripslashes(htmlspecialchars($value['content'],ENT_QUOTES))
								);
								$query_post_description = $core->podb->insertInto('post_description')->values($post_description);
							}
							$query_post_description->execute();
						}
						unset($_POST);
						header('location:'.BASE_URL.'/member/post');
					}
				}
				$info = array(
					'page_title' => $lang['front_member_editpost'].' - Member Area',
					'alertmsg' => $alertmsg
				);
				$adddata = array_merge($info, $lang);
				$templates->addData(
					$adddata
				);
				echo $templates->render('editpost', compact('lang','post'));
			}
		}
	} else {
		header('location:'.BASE_URL.'/404.php');
	}
});

/**
 * Router untuk menampilkan request halaman member tambah post.
 *
 * Router for display request in member add post page.
 *
*/
$router->match('GET|POST', '/member/post/tag', function() use ($core, $templates) {
	if ($core->posetting[17]['value'] == 'Y') {
		if (empty($_SESSION['namauser_member']) AND empty($_SESSION['passuser_member']) AND empty($_SESSION['login_member'])) {
			$tags = array();
			header('Content-Type: application/json');
			echo json_encode($tags);
		} else {
			$search = $core->postring->valid($_POST['search'], 'xss');
			$tags = $core->podb->from('tag')
				->where('title LIKE "%'.$search.'%"')
				->orderBy('id_tag ASC')
				->fetchAll();
			header('Content-Type: application/json');
			echo json_encode($tags);
		}
	} else {
		header('location:'.BASE_URL.'/404.php');
	}
});

/**
 * Router untuk menampilkan request halaman member hapus post.
 *
 * Router for display request in member delete post page.
 *
*/
$router->match('GET|POST', '/member/post/delete', function() use ($core, $templates) {
	if ($core->posetting[17]['value'] == 'Y') {
		if (empty($_SESSION['namauser_member']) AND empty($_SESSION['passuser_member']) AND empty($_SESSION['login_member'])) {
			header('location:'.BASE_URL.'/member/login');
		} else {
			if (!empty($_POST)) {
				if (!$core->auth($_SESSION['leveluser_member'], 'post', 'delete')) {
					header('location:'.BASE_URL.'/404.php');
				} else {
					$current_post = $core->podb->from('post')
						->where('id_post', $core->postring->valid($_POST['id'], 'sql'))
						->where('editor', $_SESSION['iduser_member'])
						->limit(1)
						->fetch();
					if (empty($current_post)) {
						header('location:'.BASE_URL.'/404.php');
					} else {
						$query_desc = $core->podb->deleteFrom('post_description')->where('id_post', $core->postring->valid($_POST['id'], 'sql'));
						$query_desc->execute();
						$query_cats = $core->podb->deleteFrom('post_category')->where('id_post', $core->postring->valid($_POST['id'], 'sql'));
						$query_cats->execute();
						$query_gals = $core->podb->deleteFrom('post_gallery')->where('id_post', $core->postring->valid($_POST['id'], 'sql'));
						$query_gals->execute();
						$query_pag = $core->podb->deleteFrom('post')->where('id_post', $core->postring->valid($_POST['id'], 'sql'));
						$query_pag->execute();
						unset($_POST);
						header('location:'.BASE_URL.'/member/post');
					}
				}
			} else {
				header('location:'.BASE_URL.'/404.php');
			}
		}
	} else {
		header('location:'.BASE_URL.'/404.php');
	}
});

/**
 * Router untuk menampilkan request halaman member hapus multi post.
 *
 * Router for display request in member multi delete post page.
 *
*/
$router->match('GET|POST', '/member/post/multidelete', function() use ($core, $templates) {
	if ($core->posetting[17]['value'] == 'Y') {
		if (empty($_SESSION['namauser_member']) AND empty($_SESSION['passuser_member']) AND empty($_SESSION['login_member'])) {
			header('location:'.BASE_URL.'/member/login');
		} else {
			if (!empty($_POST)) {
				if (!$core->auth($_SESSION['leveluser_member'], 'post', 'delete')) {
					header('location:'.BASE_URL.'/404.php');
				} else {
					$totaldata = $core->postring->valid($_POST['totaldata'], 'xss');
					if ($totaldata != "0") {
						$items = $_POST['item'];
						foreach($items as $item){
							$current_post = $core->podb->from('post')
								->where('id_post', $core->postring->valid($item['deldata'], 'sql'))
								->where('editor', $_SESSION['iduser_member'])
								->limit(1)
								->fetch();
							if (!empty($current_post)) {
								$query_desc = $core->podb->deleteFrom('post_description')->where('id_post', $core->postring->valid($item['deldata'], 'sql'));
								$query_desc->execute();
								$query_cats = $core->podb->deleteFrom('post_category')->where('id_post', $core->postring->valid($item['deldata'], 'sql'));
								$query_cats->execute();
								$query_gals = $core->podb->deleteFrom('post_gallery')->where('id_post', $core->postring->valid($item['deldata'], 'sql'));
								$query_gals->execute();
								$query_pag = $core->podb->deleteFrom('post')->where('id_post', $core->postring->valid($item['deldata'], 'sql'));
								$query_pag->execute();
							}
						}
						unset($_POST);
						header('location:'.BASE_URL.'/member/post');
					} else {
						header('location:'.BASE_URL.'/404.php');
					}
				}
			} else {
				header('location:'.BASE_URL.'/404.php');
			}
		}
	} else {
		header('location:'.BASE_URL.'/404.php');
	}
});