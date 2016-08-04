<?php
/*
 *
 * - PopojiCMS Front End File
 *
 * - File : home.php
 * - Version : 1.1
 * - Author : Jenuar Dalapang
 * - License : MIT License
 *
 *
 * Ini adalah file php yang di gunakan untuk menangani proses di bagian depan untuk halaman home.
 * This is a php file for handling front end process for home page.
 *
*/

/**
 * Router untuk menampilkan request halaman beranda.
 *
 * Router for display request in home page.
 *
*/
$router->match('GET|POST', '/', function() use ($core, $templates) {
	$lang = $core->setlang('home', WEB_LANG);
	$info = array(
		'page_title' => $core->posetting[0]['value'],
		'page_desc' => $core->posetting[2]['value'],
		'page_key' => $core->posetting[3]['value'],
		'social_mod' => $lang['front_home'],
		'social_name' => $core->posetting[0]['value'],
		'social_url' => $core->posetting[1]['value'],
		'social_title' => $core->posetting[0]['value'],
		'social_desc' => $core->posetting[2]['value'],
		'social_img' => $core->posetting[1]['value'].'/'.DIR_INC.'/images/favicon.png'
	);
	$adddata = array_merge($info, $lang);
	$templates->addData(
		$adddata
	);
	echo $templates->render('home', compact('lang'));
});

/**
 * Router untuk memproses request $_POST[] subscribe.
 *
 * Router for process request $_POST[] subscribe.
 *
*/
$router->match('POST', '/subscribe', function() use ($core, $templates) {
	$lang = $core->setlang('home', WEB_LANG);
	if (!empty($_POST)) {
		if (!empty($_POST['email'])) {
			$subscribe = $core->podb->from('subscribe')->where('email', $core->postring->valid($_POST['email'], 'xss'))->count();
			if ($subscribe > 0) {
				echo "<script language='javascript'>
					window.alert('".$lang['front_subscribe_error']."')
					window.location.href='./';
				</script>";
			} else {
				$core->poval->validation_rules(array(
					'email' => 'required|valid_email'
				));
				$core->poval->filter_rules(array(
					'email' => 'trim|sanitize_email'
				));
				$validated_data = $core->poval->run($_POST);
				if ($validated_data === false) {
					header('location:'.BASE_URL.'/404.php');
				} else {
					$name = explode('@', $core->postring->valid($_POST['email'], 'xss'));
					$data = array(
						'email' => $core->postring->valid($_POST['email'], 'xss'),
						'name' => ucfirst($name[0])
					);
					$query = $core->podb->insertInto('subscribe')->values($data);
					$query->execute();
					unset($_POST);
					echo "<script language='javascript'>
						window.alert('".$lang['front_subscribe_success']."')
						window.location.href='./';
					</script>";
				}
			}
		} else {
			header('location:'.BASE_URL.'/404.php');
		}
	} else {
		header('location:'.BASE_URL.'/404.php');
	}
});

/**
 * Router untuk menampilkan request halaman member.
 *
 * Router for display request in member page.
 *
*/
$router->match('GET|POST', '/member', function() use ($core, $templates) {
	if ($core->posetting[17]['value'] == 'Y') {
		if (empty($_SESSION['namauser_member']) AND empty($_SESSION['passuser_member']) AND empty($_SESSION['login_member'])) {
			header('location:'.BASE_URL.'/member/login');
		} else {
			$lang = $core->setlang('home', WEB_LANG);
			$info = array(
				'page_title' => $lang['front_member_dashboard'].' - Member Area'
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
			echo $templates->render('home', compact('lang','user'));
		}
	} else {
		header('location:'.BASE_URL.'/404.php');
	}
});

/**
 * Router untuk menampilkan request halaman login member.
 *
 * Router for display request in login member page.
 *
*/
$router->match('GET|POST', '/member/login', function() use ($core, $templates) {
	if ($core->posetting[17]['value'] == 'Y') {
		if (empty($_SESSION['namauser_member']) AND empty($_SESSION['passuser_member']) AND empty($_SESSION['login_member'])) {
			if (isset($_COOKIE['member_cookie'])) {
				$username = $_COOKIE['member_cookie']['username'];
				$pass = $_COOKIE['member_cookie']['password'];
				$count_user = $core->podb->from('users')
					->where('username', $username)
					->where('password', $pass)
					->where('level', '4')
					->where('block', 'N')
					->count();
				if ($count_user > 0) {
					$user = $core->podb->from('users')
						->where('username', $username)
						->where('password', $pass)
						->where('level', '4')
						->where('block', 'N')
						->limit(1)
						->fetch();
					$timeout = new PoTimeout;
					$timeout->rec_session_member($user);
					$timeout->timer_member();
					$sid_lama = session_id();
					session_regenerate_id();
					$sid_baru = session_id();
					$sesi = array(
						'id_session' => $sid_baru
					);
					$query = $core->podb->update('users')
						->set($sesi)
						->where('username', $username);
					$query->execute();
					header('location:'.BASE_URL.'/member');
				}
			} else {
				$alertmsg = '';
				$lang = $core->setlang('home', WEB_LANG);
				if (!empty($_POST)) {
					$_POST = $core->poval->sanitize($_POST);
					$core->poval->validation_rules(array(
						'username' => 'required|max_len,50|min_len,3',
						'password' => 'required|max_len,50|min_len,6'
					));
					$core->poval->filter_rules(array(
						'username' => 'trim|sanitize_string',
						'password' => 'trim'
					));
					$validated_data = $core->poval->run($_POST);
					if ($validated_data === false) {
						$alertmsg = '<div class="alert alert-warning">'.$lang['front_member_notif_1'].'<a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a></div>';
					} else {
						$count_user = $core->podb->from('users')
							->where('username', $_POST['username'])
							->where('password', md5($_POST['password']))
							->where('level', '4')
							->where('block', 'N')
							->count();
						if ($count_user > 0) {
							$user = $core->podb->from('users')
								->where('username', $_POST['username'])
								->where('password', md5($_POST['password']))
								->where('level', '4')
								->where('block', 'N')
								->limit(1)
								->fetch();
							$timeout = new PoTimeout;
							$timeout->rec_session_member($user);
							$timeout->timer_member();
							$sid_lama = session_id();
							session_regenerate_id();
							$sid_baru = session_id();
							$sesi = array(
								'id_session' => $sid_baru
							);
							$query = $core->podb->update('users')
								->set($sesi)
								->where('username', $_POST['username']);
							$query->execute();
							if(isset($_POST['rememberme']) || $_POST['rememberme'] == "1"){
								setcookie("member_cookie[username]", $user['username'], time() + 86400);
								setcookie("member_cookie[password]", $user['password'], time() + 86400);
							}
							header('location:'.BASE_URL.'/member');
						} else {
							$count_user_by_email = $core->podb->from('users')
								->where('email', $_POST['username'])
								->where('password', md5($_POST['password']))
								->where('level', '4')
								->where('block', 'N')
								->count();
							if ($count_user_by_email > 0) {
								$user = $core->podb->from('users')
									->where('email', $_POST['username'])
									->where('password', md5($_POST['password']))
									->where('level', '4')
									->where('block', 'N')
									->limit(1)
									->fetch();
								$timeout = new PoTimeout;
								$timeout->rec_session_member($user);
								$timeout->timer_member();
								$sid_lama = session_id();
								session_regenerate_id();
								$sid_baru = session_id();
								$sesi = array(
									'id_session' => $sid_baru
								);
								$query = $core->podb->update('users')
									->set($sesi)
									->where('email', $_POST['username']);
								$query->execute();
								if(isset($_POST['rememberme']) || $_POST['rememberme'] == "1"){
									setcookie("member_cookie[username]", $user['username'], time() + 86400);
									setcookie("member_cookie[password]", $user['password'], time() + 86400);
								}
								header('location:'.BASE_URL.'/member');
							} else {
								$alertmsg = '<div class="alert alert-warning">'.$lang['front_member_notif_2'].'<a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a></div>';
							}
						}
					}
				}
				$info = array(
					'page_title' => $lang['front_member_login'].' - Member Area',
					'alertmsg' => $alertmsg
				);
				$adddata = array_merge($info, $lang);
				$templates->addData(
					$adddata
				);
				echo $templates->render('login', compact('lang'));
			}
		} else {
			header('location:'.BASE_URL.'/member');
		}
	} else {
		header('location:'.BASE_URL.'/404.php');
	}
});

/**
 * Router untuk menampilkan request halaman register member.
 *
 * Router for display request in register member page.
 *
*/
$router->match('GET|POST', '/member/register', function() use ($core, $templates) {
	if ($core->posetting[17]['value'] == 'Y') {
		if (empty($_SESSION['namauser_member']) AND empty($_SESSION['passuser_member']) AND empty($_SESSION['login_member'])) {
			$alertmsg = '';
			$lang = $core->setlang('home', WEB_LANG);
			if (!empty($_POST)) {
				$core->poval->validation_rules(array(
					'username' => 'required|alpha_dash|max_len,50|min_len,3',
					'password' => 'required|max_len,50|min_len,6',
					'repassword' => 'required|max_len,50|min_len,6',
					'email' => 'required|valid_email'
				));
				$core->poval->filter_rules(array(
					'username' => 'trim|sanitize_string',
					'password' => 'trim',
					'repassword' => 'trim',
					'email' => 'trim|sanitize_email'
				));
				$validated_data = $core->poval->run($_POST);
				if ($validated_data === false) {
					$alertmsg = '<div class="alert alert-warning">'.$lang['front_member_notif_1'].'<a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a></div>';
				} else {
					$count_user_email = $core->podb->from('users')
						->where('email', $_POST['email'])
						->count();
					if ($count_user_email > 0) {
						$alertmsg = '<div class="alert alert-warning">'.$lang['front_member_notif_3'].'<a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a></div>';
					} else {
						if (strlen($_POST['password']) >= 6) {
							if (md5($_POST['password']) != md5($_POST['repassword'])) {
								$alertmsg = '<div class="alert alert-warning">'.$lang['front_member_notif_4'].'<a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a></div>';
							} else {
								$count_user_name = $core->podb->from('users')
									->where('username', strtolower($_POST['username']))
									->count();
								if ($count_user_name > 0) {
									$alertmsg = '<div class="alert alert-warning">'.$lang['front_member_notif_5'].'<a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a></div>';
								} else {
									$last_user = $core->podb->from('users')->limit(1)->orderBy('id_user DESC')->fetch();
									$data = array(
										'id_user' => $last_user['id_user']+1,
										'username' => strtolower($_POST['username']),
										'password' => md5($_POST['password']),
										'nama_lengkap' => ucfirst(strtolower($_POST['username'])),
										'email' => $_POST['email'],
										'level' => '4',
										'tgl_daftar' => date('Ymd'),
										'block' => 'Y',
										'id_session' => md5($_POST['password'])
									);
									$query = $core->podb->insertInto('users')->values($data);
									$query->execute();
									$website_name = $core->posetting[0]['value'];
									$website_url = $core->posetting[1]['value'];
									$username = strtolower($_POST['username']);
									$email = $_POST['email'];
									$pass = $_POST['password'];
									$passmd5 = md5($_POST['password']);
									$subject = "Email Account Activation For $website_name";
									$from = $core->posetting[5]['value'];
									$message = "<html>
										<body>
											Indonesia :<br />
											-----------<br />
											Hi $username,<br />
											Jika anda tidak pernah mendaftarkan akun di $website_name, silahkan untuk menghiraukan email ini.<br />
											Tetapi jika benar Anda telah membuat akun di $website_name, maka silahkan untuk mengklik tautan (link) di bawah ini untuk mengaktifkan akun Anda :<br /><br />
											<a href=\"$website_url/member/activation/$username/$passmd5\" title=\"Account Activation\">$website_url/member/activation/$username/$passmd5</a><br /><br />
											Setelah link tersebut diklik maka akun Anda telah diaktifkan dan telah terverifikasi, silahkan login dengan data berikut :<br /><br />
											--------------------<br />
											Username : $username<br />
											Password : $pass<br />
											--------------------<br /><br />
											Salam hangat,<br />
											$website_name.<br /><br /><br />
											English :<br />
											-----------<br />
											Hi $username,<br />
											If you have never registered account in $website_name, please to ignore this email.<br />
											But if you really are registered account in $website_name, please to click on a link below to activated yout account :<br /><br />
											<a href=\"$website_url/member/activation/$username/$passmd5\" title=\"Account Activation\">$website_url/member/activation/$username/$passmd5</a><br /><br />
											Then automatically after you click a link above, your account have registered and verificated, please login with data :<br /><br />
											--------------------<br />
											Username : $username<br />
											Password : $pass<br />
											--------------------<br /><br />
											Warm regards,<br />
											$website_name.
										</body>
									</html>";
									if ($core->posetting[23]['value'] != 'SMTP') {
										$poemail = new PoEmail;
										$send = $poemail
											->setOption(
												array(
													messageType => 'html'
												)
											)
											->to($email)
											->subject($subject)
											->message($message)
											->from($from)
											->mail();
									} else {
										$core->pomail->isSMTP();
										$core->pomail->SMTPDebug = 0;
										$core->pomail->Debugoutput = 'html';
										$core->pomail->Host = $core->posetting[24]['value'];
										$core->pomail->Port = $core->posetting[27]['value'];
										$core->pomail->SMTPAuth = true;
										$core->pomail->SMTPSecure = 'ssl';
										$core->pomail->IsHTML(true);
										$core->pomail->Username = $core->posetting[25]['value'];;
										$core->pomail->Password = $core->posetting[26]['value'];
										$core->pomail->setFrom($core->posetting[5]['value'], $core->posetting[0]['value']);
										$core->pomail->addAddress($email, $username);
										$core->pomail->Subject = $subject;
										$core->pomail->msgHTML($message);
										$core->pomail->send();
									}
									unset($_POST);
									$alertmsg = '<div class="alert alert-info"><i class="fa fa-info"></i>&nbsp;&nbsp;'.$lang['front_member_notif_6'].'<a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a></div>';
								}
							}
						} else {
							$alertmsg = '<div class="alert alert-warning">'.$lang['front_member_notif_7'].'<a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a></div>';
						}
					}
				}
			}
			$info = array(
				'page_title' => $lang['front_member_register'].' - Member Area',
				'alertmsg' => $alertmsg
			);
			$adddata = array_merge($info, $lang);
			$templates->addData(
				$adddata
			);
			echo $templates->render('register', compact('lang'));
		} else {
			header('location:'.BASE_URL.'/member');
		}
	} else {
		header('location:'.BASE_URL.'/404.php');
	}
});

/**
 * Router untuk menampilkan request halaman activation member.
 *
 * Router for display request in activation member page.
 *
*/
$router->match('GET|POST', '/member/activation/([a-z0-9_-]+)/([a-z0-9_-]+)', function($username, $pass) use ($core, $templates) {
	if ($core->posetting[17]['value'] == 'Y') {
		if (!empty($username) && !empty($pass)){
			$alertmsg = '';
			$lang = $core->setlang('home', WEB_LANG);
			$username = $core->postring->valid($username, 'xss');
			$pass = $core->postring->valid($pass, 'xss');
			$count_user = $core->podb->from('users')
				->where('username', $username)
				->where('id_session', $pass)
				->where('level', '4')
				->count();
			if ($count_user > 0) {
				$user = $core->podb->from('users')
					->where('username', $username)
					->where('id_session', $pass)
					->where('level', '4')
					->limit(1)
					->fetch();
				if ($user['block'] == 'Y'){
					$data = array(
						'block' => 'N'
					);
					$query = $core->podb->update('users')
						->set($data)
						->where('username', $username)
						->where('id_session', $pass);
					$query->execute();
					$alertmsg = '1';
				} else {
					$alertmsg = '2';
				}
			} else {
				$alertmsg = '3';
			}
			$info = array(
				'page_title' => $lang['front_member_register'].' - Member Area',
				'alertmsg' => $alertmsg
			);
			$adddata = array_merge($info, $lang);
			$templates->addData(
				$adddata
			);
			echo $templates->render('activation', compact('lang'));
		} else {
			header('location:'.BASE_URL.'/404.php');
		}
	} else {
		header('location:'.BASE_URL.'/404.php');
	}
});

/**
 * Router untuk menampilkan request halaman forgot member.
 *
 * Router for display request in forgot member page.
 *
*/
$router->match('GET|POST', '/member/forgot', function() use ($core, $templates) {
	if ($core->posetting[17]['value'] == 'Y') {
		if (empty($_SESSION['namauser_member']) AND empty($_SESSION['passuser_member']) AND empty($_SESSION['login_member'])) {
			$alertmsg = '';
			$lang = $core->setlang('home', WEB_LANG);
			if (!empty($_POST)) {
				$_POST = $core->poval->sanitize($_POST);
				$core->poval->validation_rules(array(
					'email' => 'required|valid_email'
				));
				$core->poval->filter_rules(array(
					'email' => 'trim|sanitize_email'
				));
				$validated_data = $core->poval->run($_POST);
				if ($validated_data === false) {
					$alertmsg = '<div class="alert alert-warning">'.$lang['front_member_notif_8'].'<a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a></div>';
				} else {
					$count_user = $core->podb->from('users')
						->where('email', $_POST['email'])
						->where('level', '4')
						->count();
					if ($count_user > 0) {
						$user = $core->podb->from('users')
							->where('email', $_POST['email'])
							->where('level', '4')
							->limit(1)
							->fetch();
						$forgotkey = md5(microtime() . $_SERVER['REMOTE_ADDR'] . '#$&^%$#' . mt_rand());
						$data = array(
							'forget_key' => $forgotkey
						);
						$query = $core->podb->update('users')
							->set($data)
							->where('email', $_POST['email']);
						$query->execute();
						$website_name = $core->posetting[0]['value'];
						$website_url = $core->posetting[1]['value'];
						$username = $user['username'];
						$nama_lengkap = $user['nama_lengkap'];
						$subject = "Recovery Password For $website_name";
						$from = $core->posetting[5]['value'];
						$message = "<html>
							<body>
								Indonesia :<br />
								-----------<br />
								Hi $nama_lengkap,<br />
								Jika anda tidak pernah meminta pesan informasi tentang lupa password di $website_name, silahkan untuk menghiraukan email ini.<br />
								Tetapi jika anda memang yang meminta pesan informasi ini, maka silahkan untuk mengklik tautan (link) di bawah ini :<br /><br />
								<a href=\"".$website_url."/member/recover/$username/$forgotkey\" title=\"Recover Password\">".$website_url."/member/recover/$username/$forgotkey</a><br /><br />
								Kemudian secara otomatis setelah anda mengklik tautan (link) di atas, password anda akan diganti menjadi password default yaitu : <b>123456</b>.<br />
								Silahkan untuk login dengan password tersebut kemudian ganti password default ini dengan password yang lebih aman.<br /><br />
								Salam hangat,<br />
								$website_name.<br /><br /><br />
								English :<br />
								-----------<br />
								Hi $nama_lengkap,<br />
								If you have never requested message information about forgotten password in $website_name, please to ignore this email.<br />
								But if you really are asking for messages of this information, then please to click on a link below :<br /><br />
								<a href=\"".$website_url."/member/recover/$username/$forgotkey\" title=\"Recover Password\">".$website_url."/member/recover/$username/$forgotkey</a><br /><br />
								Then automatically after you click a link above, your password will be changed to the default password is : <b>123456</b>.<br />
								Please to log in with the password and then change the default password to a more secure password.<br /><br />
								Warm regards,<br />
								$website_name.
							</body>
						</html>";
						if ($core->posetting[23]['value'] != 'SMTP') {
							$poemail = new PoEmail;
							$send = $poemail
								->setOption(
									array(
										messageType => 'html'
									)
								)
								->to($user['email'])
								->subject($subject)
								->message($message)
								->from($from)
								->mail();
						} else {
							$core->pomail->isSMTP();
							$core->pomail->SMTPDebug = 0;
							$core->pomail->Debugoutput = 'html';
							$core->pomail->Host = $core->posetting[24]['value'];
							$core->pomail->Port = $core->posetting[27]['value'];
							$core->pomail->SMTPAuth = true;
							$core->pomail->SMTPSecure = 'ssl';
							$core->pomail->IsHTML(true);
							$core->pomail->Username = $core->posetting[25]['value'];;
							$core->pomail->Password = $core->posetting[26]['value'];
							$core->pomail->setFrom($core->posetting[5]['value'], $core->posetting[0]['value']);
							$core->pomail->addAddress($user['email'], $nama_lengkap);
							$core->pomail->Subject = $subject;
							$core->pomail->msgHTML($message);
							$core->pomail->send();
						}
						$alertmsg = '<div class="alert alert-info">'.$lang['front_member_notif_9'].'<a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a></div>';
					} else {
						$alertmsg = '<div class="alert alert-warning">'.$lang['front_member_notif_10'].'<a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a></div>';
					}
				}
			}
			$info = array(
				'page_title' => $lang['front_member_forgot'].' - Member Area',
				'alertmsg' => $alertmsg
			);
			$adddata = array_merge($info, $lang);
			$templates->addData(
				$adddata
			);
			echo $templates->render('forgot', compact('lang'));
		} else {
			header('location:'.BASE_URL.'/member');
		}
	} else {
		header('location:'.BASE_URL.'/404.php');
	}
});

/**
 * Router untuk menampilkan request halaman recover member.
 *
 * Router for display request in recover member page.
 *
*/
$router->match('GET|POST', '/member/recover/([a-z0-9_-]+)/([a-z0-9_-]+)', function($username, $forgotkey) use ($core, $templates) {
	if ($core->posetting[17]['value'] == 'Y') {
		if (!empty($username) && !empty($forgotkey)){
			$alertmsg = '';
			$lang = $core->setlang('home', WEB_LANG);
			$forgetuser = $core->postring->valid($username, 'xss');
			$forgetkey = $core->postring->valid($forgotkey, 'xss');
			$count_user = $core->podb->from('users')
				->where('username', $forgetuser)
				->where('forget_key', $forgetkey)
				->where('level', '4')
				->count();
			if ($count_user > 0) {
				$user = $core->podb->from('users')
					->where('username', $forgetuser)
					->where('forget_key', $forgetkey)
					->where('level', '4')
					->limit(1)
					->fetch();
				if ($user['blokir'] == 'N'){
					$newcode = "123456";
					$pass = md5($newcode);
					$data = array(
						'password' => $pass,
						'forget_key' => ''
					);
					$query = $core->podb->update('users')
						->set($data)
						->where('username', $forgetuser)
						->where('forget_key', $forgetkey);
					$query->execute();
					$alertmsg = '1';
				} else {
					$alertmsg = '2';
				}
				$info = array(
					'page_title' => $lang['front_member_forgot'].' - Member Area',
					'alertmsg' => $alertmsg
				);
				$adddata = array_merge($info, $lang);
				$templates->addData(
					$adddata
				);
				echo $templates->render('recover', compact('lang'));
			} else {
				header('location:'.BASE_URL.'/404.php');
			}
		} else {
			header('location:'.BASE_URL.'/404.php');
		}
	} else {
		header('location:'.BASE_URL.'/404.php');
	}
});

/**
 * Router untuk menampilkan request halaman login member dengan facebook.
 *
 * Router for display request in login member page with facebook.
 *
*/
$router->match('GET|POST', '/member/login/facebook', function() use ($core, $templates) {
	require_once DIR_CON.'/component/oauth/facebook/Facebook/autoload.php';

	$currentOauthfb = $core->podb->from('oauth')->fetchAll();
	$appIdOauthfb = $currentOauthfb[0]['oauth_key'];
	$secretOauthfb = $currentOauthfb[0]['oauth_secret'];

	$fb = new Facebook\Facebook([
		'app_id'  => $appIdOauthfb,
		'app_secret' => $secretOauthfb,
		'default_graph_version' => 'v2.5'
	]);

	$helper = $fb->getRedirectLoginHelper();
	$loginUrl = $helper->getLoginUrl(BASE_URL.'/'.DIR_CON.'/themes/member/facebook/oauth.php', ['public_profile', 'email', 'manage_pages', 'publish_actions']);
	header('location:'.$loginUrl);
});

/**
 * Router untuk menampilkan request halaman login member dengan twitter.
 *
 * Router for display request in login member page with twitter.
 *
*/
$router->match('GET|POST', '/member/login/twitter', function() use ($core, $templates) {
	require_once DIR_CON.'/component/oauth/twitter/Twitter/twitteroauth.php';

	$currentOauthtw = $core->podb->from('oauth')->fetchAll();
	$conkeyOauthtw = $currentOauthtw[1]['oauth_key'];
	$consecretOauthtw = $currentOauthtw[1]['oauth_secret'];

	define('CONSUMER_KEY', ''.$conkeyOauthtw.'');
	define('CONSUMER_SECRET', ''.$consecretOauthtw.'');
	define('OAUTH_CALLBACK', ''.BASE_URL.'/member/login/twitter');

	$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, @$_SESSION['oauth_token'], @$_SESSION['oauth_token_secret']);

	if (isset($_REQUEST['oauth_verifier'])) {
		$access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);
	}

	if ($connection->http_code == 200) {
		$tokenuser = $access_token['oauth_token'];
		$tokenuser_secret = $access_token['oauth_token_secret'];
		$twuserid = $access_token['user_id'];
		$twusername = $access_token['screen_name'];

		unset($_SESSION['oauth_token']);
		unset($_SESSION['oauth_token_secret']);

		$user_count = $core->podb->from('users')
			->where('username', strtolower(str_replace(' ', '', $twusername)))
			->where('level', '4')
			->where('block', 'Y')
			->count();
		if ($user_count > 0) {
			$user_data = $core->podb->from('users')
				->where('username', strtolower(str_replace(' ', '', $twusername)))
				->where('level', '4')
				->where('block', 'Y')
				->limit(1)
				->fetch();
			$timeout = new PoTimeout;
			$timeout->rec_session_member($user_data);
			$timeout->timer_member();
			$sid_lama = session_id();
			session_regenerate_id();
			$sid_baru = session_id();
			$sesi = array(
				'id_session' => $sid_baru
			);
			$query = $core->podb->update('users')
				->set($sesi)
				->where('username', strtolower(str_replace(' ', '', $twusername)));
			$query->execute();
			header('location:'.BASE_URL.'/member');
		} else {
			$last_user = $core->podb->from('users')->limit(1)->orderBy('id_user DESC')->fetch();
			$data = array(
				'id_user' => $last_user['id_user']+1,
				'username' => strtolower(str_replace(' ', '', $twusername)),
				'password' => '',
				'nama_lengkap' => $twusername,
				'email' => '',
				'level' => '4',
				'tgl_daftar' => date('Ymd'),
				'block' => 'Y',
				'id_session' => md5($twuserid)
			);
			$query = $core->podb->insertInto('users')->values($data);
			$query->execute();
			$user_data = $core->podb->from('users')
				->where('username', strtolower(str_replace(' ', '', $twusername)))
				->where('level', '4')
				->where('block', 'Y')
				->limit(1)
				->fetch();
			$timeout = new PoTimeout;
			$timeout->rec_session_member($user_data);
			$timeout->timer_member();
			header('location:'.BASE_URL.'/member');
		}
	} else {
		header('location:'.BASE_URL.'/'.DIR_CON.'/themes/member/twitter/oauth.php');
	}
});

/**
 * Router untuk menampilkan request halaman logout member.
 *
 * Router for display request in logout member page.
 *
*/
$router->match('GET|POST', '/member/logout', function() use ($core, $templates) {
	session_destroy();
	header('location:'.BASE_URL.'/member/login');
});

/**
 * Router untuk menampilkan request rss feed.
 *
 * Router for display request rss feed.
 *
 * Added in v.2.0.1
*/
$router->match('GET|POST', '/feed', function() use ($core, $templates) {
	header('Content-Type: text/xml; charset=utf-8', true); //Set document header content type to be XML
	$xml = new DOMDocument("1.0", "UTF-8"); //Create new DOM document.

	//Create "RSS" element
	$rss = $xml->createElement("rss");
	$rss_node = $xml->appendChild($rss); //add RSS element to XML node
	$rss_node->setAttribute("version","2.0"); //set RSS version

	//Set attributes
	$rss_node->setAttribute("xmlns:content", "http://purl.org/rss/1.0/modules/content/");
	$rss_node->setAttribute("xmlns:wfw", "http://wellformedweb.org/CommentAPI/");
	$rss_node->setAttribute("xmlns:dc", "http://purl.org/dc/elements/1.1/");
	$rss_node->setAttribute("xmlns:atom", "http://www.w3.org/2005/Atom");
	$rss_node->setAttribute("xmlns:sy", "http://purl.org/rss/1.0/modules/syndication/");
	$rss_node->setAttribute("xmlns:slash", "http://purl.org/rss/1.0/modules/slash/");
	$rss_node->setAttribute("xmlns:georss", "http://www.georss.org/georss");
	$rss_node->setAttribute("xmlns:geo", "http://www.w3.org/2003/01/geo/wgs84_pos#");
	$rss_node->setAttribute("xmlns:media", "http://search.yahoo.com/mrss/");

	//Create RFC822 Date format to comply with RFC822
	$date_f = date("D, d M Y H:i:s T", time());
	$build_date = gmdate(DATE_RFC2822, strtotime($date_f));

	//Create "channel" element under "RSS" element
	$channel = $xml->createElement("channel");  
	$channel_node = $rss_node->appendChild($channel);

	//Add general elements under "channel" node
	$channel_node->appendChild($xml->createElement("title", $core->posetting[0]['value'])); //title

	//A feed should contain an atom:link element
	$channel_atom_link = $xml->createElement("atom:link");
	$channel_atom_link->setAttribute("href", $core->posetting[1]['value'].'/feed'); //url of the feed
	$channel_atom_link->setAttribute("rel", "self");
	$channel_atom_link->setAttribute("type", "application/rss+xml");
	$channel_node->appendChild($channel_atom_link);

	//Add general elements under "channel" node
	$channel_node->appendChild($xml->createElement("link", $core->posetting[1]['value'])); //website link
	$channel_node->appendChild($xml->createElement("description", $core->posetting[2]['value']));  //description
	$channel_node->appendChild($xml->createElement("lastBuildDate", $build_date));  //last build date
	$channel_node->appendChild($xml->createElement("language", strtolower(WEB_LANG).'-'.strtoupper(WEB_LANG)));  //language
	$channel_node->appendChild($xml->createElement("generator", "http://www.popojicms.org")); //generator

	//Add general elements under "channel" node
	$image_node = $channel_node->appendChild($xml->createElement("image"));
	$url_image_node = $image_node->appendChild($xml->createElement("url", BASE_URL.'/'.DIR_INC.'/images/favicon.png'));
	$title_image_node = $image_node->appendChild($xml->createElement("title", $core->posetting[0]['value']));
	$link_image_node = $image_node->appendChild($xml->createElement("link", $core->posetting[1]['value']));

	//Fetch records from the database
	$recents = $core->podb->from('post')
		->select(array('post_description.title', 'post_description.content'))
		->leftJoin('post_description ON post_description.id_post = post.id_post')
		->where('post_description.id_language', WEB_LANG_ID)
		->where('post.active', 'Y')
		->where('post.publishdate < ?', date('Y-m-d H:i:s'))
		->orderBy('post.id_post DESC')
		->limit(20)
		->fetchAll();
	foreach($recents as $recent) {
		$item_node = $channel_node->appendChild($xml->createElement("item")); //create a new node called "item"
		$title_node = $item_node->appendChild($xml->createElement("title", $recent['title'])); //Add Title under "item"
		$link_node = $item_node->appendChild($xml->createElement("link", $core->postring->permalink(rtrim(BASE_URL, '/'), $recent))); //add link node under "item"

		//Unique identifier for the item (GUID)
		$guid_link = $xml->createElement("guid", $core->postring->permalink(rtrim(BASE_URL, '/'), $recent));
		$guid_link->setAttribute("isPermaLink", "false");
		$guid_node = $item_node->appendChild($guid_link);

		//Create "description" node under "item"
		$description_node = $item_node->appendChild($xml->createElement("description"));

		//Fill description node with CDATA content
		$description_contents = $xml->createCDATASection($core->postring->cuthighlight('post', $recent['content'], '250').'...');
		$description_node->appendChild($description_contents);

		//Published date
		$date_rfc = gmdate(DATE_RFC2822, strtotime($recent['publishdate']));
		$pub_date = $xml->createElement("pubDate", $date_rfc);
		$pub_date_node = $item_node->appendChild($pub_date);

		//Item media
		$item_media = $xml->createElement("media:content");
		$item_media->setAttribute("url", BASE_URL.'/po-content/thumbs/'.$recent['picture']);
		$item_media->setAttribute("medium", "image");
		$item_media_node = $item_node->appendChild($item_media);
		$media_title = $xml->createElement("media:title", $recent['title']);
		$media_title->setAttribute("type", "html");
		$media_title_node = $item_media_node->appendChild($media_title);
	}

	//Create xml
	echo $xml->saveXML();
});