<?php
/*
 *
 * - PopojiCMS Front End File
 *
 * - File : comment.php
 * - Version : 1.0
 * - Author : Jenuar Dalapang
 * - License : MIT License
 *
 *
 * Ini adalah file php yang di gunakan untuk menangani proses di bagian depan untuk halaman komentar.
 * This is a php file for handling front end process for comment page.
 *
*/

/**
 * Router untuk memproses request $_POST[] komentar.
 *
 * Router for process request $_POST[] comment.
 *
*/
$router->match('GET|POST', '/comment', function() use ($core, $templates) {
	$lang = $core->setlang('comment', WEB_LANG);
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
					'id' => 'trim',
					'id_parent' => 'trim',
					'name' => 'trim|sanitize_string',
					'email' => 'trim|sanitize_email',
					'url' => 'trim|sanitize_string',
					'comment' => 'trim|sanitize_string|basic_tags',
					'seotitle' => 'trim'
				));
				$validated_data = $core->poval->run($_POST);
				if ($validated_data === false) {
					$core->poflash->error($lang['front_comment_error_3'], BASE_URL.'/detailpost/'.$_POST['seotitle'].'#comments');
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
					$core->poflash->success($lang['front_comment_success'], BASE_URL.'/detailpost/'.$_POST['seotitle'].'#comments');
				}
			} else {
				$core->poflash->error($lang['front_comment_error_2'], BASE_URL.'/detailpost/'.$_POST['seotitle'].'#comments');
			}
		} else {
			$core->poflash->error($lang['front_comment_error_1'], BASE_URL.'/detailpost/'.$_POST['seotitle'].'#comments');
		}
	} else {
		header('location:'.BASE_URL.'/404.php');
	}
});