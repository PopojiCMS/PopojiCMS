<?php
/*
 *
 * - PopojiCMS Front End File
 *
 * - File : contact.php
 * - Version : 1.0
 * - Author : Jenuar Dalapang
 * - License : MIT License
 *
 *
 * Ini adalah file php yang di gunakan untuk menangani proses di bagian depan untuk halaman kontak.
 * This is a php file for handling front end process for contact page.
 *
*/

/**
 * Router untuk memproses request $_POST[] komentar.
 *
 * Router for process request $_POST[] comment.
 *
*/
$router->match('GET|POST', '/contact', function() use ($core, $templates) {
	$alertmsg = '';
	$lang = $core->setlang('contact', WEB_LANG);
	if (!empty($_POST)) {
		$core->poval->validation_rules(array(
			'contact_name' => 'required|max_len,100|min_len,3',
			'contact_email' => 'required|valid_email',
			'contact_subject' => 'required|max_len,255|min_len,6',
			'contact_message' => 'required|min_len,10'
		));
		$core->poval->filter_rules(array(
			'contact_name' => 'trim|sanitize_string',
			'contact_email' => 'trim|sanitize_string',
			'contact_subject' => 'trim|sanitize_email',
			'contact_message' => 'trim|sanitize_string'
		));
		$validated_data = $core->poval->run($_POST);
		if ($validated_data === false) {
			$alertmsg = '<div id="contact-form-result">
				<div class="alert alert-warning" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					'.$lang['front_contact_error'].'
				</div>
			</div>';
		} else {
			$data = array(
				'name' => $_POST['contact_name'],
				'email' => $_POST['contact_email'],
				'subject' => $_POST['contact_subject'],
				'message' => $_POST['contact_message']
			);
			$query = $core->podb->insertInto('contact')->values($data);
			$query->execute();
			unset($_POST);
			$alertmsg = '<div id="contact-form-result">
				<div class="alert alert-success" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					'.$lang['front_contact_success'].'
				</div>
			</div>';
		}
	}
	$info = array(
		'page_title' => $lang['front_contact'],
		'page_desc' => $core->posetting[2]['value'],
		'page_key' => $core->posetting[3]['value'],
		'social_mod' => $lang['front_contact'],
		'social_name' => $core->posetting[0]['value'],
		'social_url' => $core->posetting[1]['value'],
		'social_title' => $core->posetting[0]['value'],
		'social_desc' => $core->posetting[2]['value'],
		'social_img' => $core->posetting[1]['value'].'/'.DIR_INC.'/images/favicon.png',
		'alertmsg' => $alertmsg
	);
	$adddata = array_merge($info, $lang);
	$templates->addData(
		$adddata
	);
	echo $templates->render('contact', compact('lang'));
});