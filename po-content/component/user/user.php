<?php
/*
 *
 * - PopojiCMS Front End File
 *
 * - File : user.php
 * - Version : 1.0
 * - Author : Jenuar Dalapang
 * - License : MIT License
 *
 *
 * Ini adalah file php yang di gunakan untuk menangani proses di bagian depan untuk halaman pengguna.
 * This is a php file for handling front end process for user page.
 *
*/

/**
 * Router untuk menampilkan request halaman edit pengguna.
 *
 * Router for display request in edit user page.
 *
*/
$router->match('GET|POST', '/member/user/edit', function() use ($core, $templates) {
	if ($core->posetting[17]['value'] == 'Y') {
		if (empty($_SESSION['namauser_member']) AND empty($_SESSION['passuser_member']) AND empty($_SESSION['login_member'])) {
			header('location:'.BASE_URL.'/member/login');
		} else {
			$alertmsg = '';
			$lang = $core->setlang('user', WEB_LANG);
			if (!empty($_POST)) {
				if (!$core->auth($_SESSION['leveluser_member'], 'user', 'update')) {
					header('location:'.BASE_URL.'/404.php');
				} else {
					$core->poval->validation_rules(array(
						'nama_lengkap' => 'required|max_len,255|min_len,3',
						'email' => 'required|valid_email',
						'no_telp' => 'required'
					));
					$core->poval->filter_rules(array(
						'nama_lengkap' => 'trim|sanitize_string',
						'email' => 'trim|sanitize_email',
						'no_telp' => 'trim'
					));
					$validated_data = $core->poval->run(array_merge($_POST, $_FILES));
					if ($validated_data === false) {
						$alertmsg = '<div class="alert alert-warning">'.$lang['front_member_notif_1'].'<a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a></div>';
					} else {
						$data = array(
							'nama_lengkap' => $_POST['nama_lengkap'],
							'email' => $_POST['email'],
							'no_telp' => $_POST['no_telp'],
							'bio' => htmlspecialchars($_POST['bio'], ENT_QUOTES)
						);
						if(!empty($_FILES['picture']['tmp_name'])){
							$file_exists = DIR_CON.'/uploads/user-'.$_SESSION['iduser_member'];
							if (file_exists($file_exists)){
								unlink(DIR_CON.'/uploads/user-'.$_SESSION['iduser_member']);
							}
							$upload = new PoUpload($_FILES['picture']);
							if ($upload->uploaded) {
								$upload->file_new_name_body = 'user-'.$_SESSION['iduser_member'];
								$upload->image_convert = 'jpg';
								$upload->image_resize = true;
								$upload->image_x = 512;
								$upload->image_y = 512;
								$upload->image_ratio = true;
								$upload->process(DIR_CON.'/uploads/');
								if ($upload->processed) {
									$datapic = array(
										'picture' => $upload->file_dst_name
									);
									$upload->clean();
								} else {
									$datapic = array();
								}
							}
						} else {
							$datapic = array();
						}
						$datafinal = array_merge($data, $datapic);
						$query = $core->podb->update('users')
							->set($datafinal)
							->where('username', $_SESSION['namauser_member']);
						$query->execute();
						$user = $core->podb->from('users')
							->where('username', $_SESSION['namauser_member'])
							->where('level', '4')
							->where('block', 'N')
							->limit(1)
							->fetch();
						$timeout = new PoTimeout;
						$timeout->rec_session_member($user);
						$timeout->timer_member();
						$alertmsg = '<div class="alert alert-success">'.$lang['front_member_notif_11'].'...'.$lang['user_message_2'].'<a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a></div>';
					}
				}
			}
			$info = array(
				'page_title' => $lang['front_member_edit_account'].' - Member Area',
				'alertmsg' => $alertmsg
			);
			$adddata = array_merge($info, $lang);
			$templates->addData(
				$adddata
			);
			$user = $core->podb->from('users')
				->where('username', $_SESSION['namauser_member'])
				->where('level', '4')
				->limit(1)
				->fetch();
			echo $templates->render('editmember', compact('lang','user'));
		}
	} else {
		header('location:'.BASE_URL.'/404.php');
	}
});

/**
 * Router untuk menampilkan request halaman ganti password pengguna.
 *
 * Router for display request in change password user page.
 *
*/
$router->match('GET|POST', '/member/user/changepass', function() use ($core, $templates) {
	if ($core->posetting[17]['value'] == 'Y') {
		if (empty($_SESSION['namauser_member']) AND empty($_SESSION['passuser_member']) AND empty($_SESSION['login_member'])) {
			header('location:'.BASE_URL.'/member/login');
		} else {
			$alertmsg = '';
			$lang = $core->setlang('user', WEB_LANG);
			if (!empty($_POST)) {
				if (!$core->auth($_SESSION['leveluser_member'], 'user', 'update')) {
					header('location:'.BASE_URL.'/404.php');
				} else {
					$core->poval->validation_rules(array(
						'oldpassword' => 'required|max_len,50|min_len,6',
						'newpassword' => 'required|max_len,50|min_len,6',
						'repassword' => 'required|max_len,50|min_len,6'
					));
					$core->poval->filter_rules(array(
						'oldpassword' => 'trim',
						'newpassword' => 'trim',
						'repassword' => 'trim'
					));
					$validated_data = $core->poval->run($_POST);
					if ($validated_data === false) {
						$alertmsg = '<div class="alert alert-warning">'.$lang['front_member_notif_1'].'<a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a></div>';
					} else {
						$user = $core->podb->from('users')
							->where('username', $_SESSION['namauser_member'])
							->where('level', '4')
							->where('block', 'N')
							->limit(1)
							->fetch();
						if ($user['password'] != md5($_POST['oldpassword'])) {
							$alertmsg = '<div class="alert alert-warning">'.$lang['front_member_notif_23'].'<a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a></div>';
						} else {
							if ($_POST['newpassword'] != $_POST['repassword']) {
								$alertmsg = '<div class="alert alert-warning">'.$lang['front_member_notif_4'].'<a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a></div>';
							} else {
								$data = array(
									'password' => md5($_POST['newpassword'])
								);
								$query = $core->podb->update('users')
									->set($data)
									->where('username', $_SESSION['namauser_member']);
								$query->execute();
								$timeout = new PoTimeout;
								$timeout->rec_session_member($user);
								$timeout->timer_member();
								$alertmsg = '<div class="alert alert-success">'.$lang['front_member_notif_11'].'...'.$lang['front_member_notif_24'].'<a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a></div>';
							}
						}
					}
				}
			}
			$info = array(
				'page_title' => $lang['front_member_change_password'].' - Member Area',
				'alertmsg' => $alertmsg
			);
			$adddata = array_merge($info, $lang);
			$templates->addData(
				$adddata
			);
			$user = $core->podb->from('users')
				->where('username', $_SESSION['namauser_member'])
				->where('level', '4')
				->limit(1)
				->fetch();
			echo $templates->render('changepass', compact('lang','user'));
		}
	} else {
		header('location:'.BASE_URL.'/404.php');
	}
});

/**
 * Router untuk menampilkan request halaman hapus pengguna.
 *
 * Router for display request in delete user page.
 *
*/
$router->match('GET|POST', '/member/user/delaccount', function() use ($core, $templates) {
	if ($core->posetting[17]['value'] == 'Y') {
		if (empty($_SESSION['namauser_member']) AND empty($_SESSION['passuser_member']) AND empty($_SESSION['login_member'])) {
			header('location:'.BASE_URL.'/member/login');
		} else {
			$alertmsg = '';
			$lang = $core->setlang('user', WEB_LANG);
			if (!empty($_POST)) {
				if (!$core->auth($_SESSION['leveluser_member'], 'user', 'delete')) {
					header('location:'.BASE_URL.'/404.php');
				} else {
					$core->poval->validation_rules(array(
						'password' => 'required|max_len,50|min_len,6'
					));
					$core->poval->filter_rules(array(
						'password' => 'trim'
					));
					$validated_data = $core->poval->run($_POST);
					if ($validated_data === false) {
						$alertmsg = '<div class="alert alert-warning">'.$lang['front_member_notif_1'].'<a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a></div>';
					} else {
						$user = $core->podb->from('users')
							->where('username', $_SESSION['namauser_member'])
							->where('level', '4')
							->limit(1)
							->fetch();
						if ($user['password'] != md5($_POST['password'])) {
							$alertmsg = '<div class="alert alert-warning">'.$lang['front_member_notif_25'].'<a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a></div>';
						} else {
							$data = array(
								'block' => 'Y'
							);
							$query = $core->podb->update('users')
								->set($data)
								->where('username', $_SESSION['namauser_member']);
							$query->execute();
							echo "<script language='javascript'>
								window.alert('".$lang['user_message_3']."')
								window.location.href='".BASE_URL."/member/logout';
							</script>";
						}
					}
				}
			}
			$info = array(
				'page_title' => $lang['front_member_delete_account'].' - Member Area',
				'alertmsg' => $alertmsg
			);
			$adddata = array_merge($info, $lang);
			$templates->addData(
				$adddata
			);
			$user = $core->podb->from('users')
				->where('username', $_SESSION['namauser_member'])
				->where('level', '4')
				->limit(1)
				->fetch();
			echo $templates->render('delaccount', compact('lang','user'));
		}
	} else {
		header('location:'.BASE_URL.'/404.php');
	}
});