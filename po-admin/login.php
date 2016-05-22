<?php
/*
 *
 * - PopojiCMS Admin File
 *
 * - File : login.php
 * - Version : 1.0
 * - Author : Jenuar Dalapang
 * - License : MIT License
 *
 *
 * Ini adalah file php yang di gunakan untuk menangani semua proses login.
 * This is a php file for handling all login process.
 *
*/

class Login extends PoCore
{

	/**
	 * Fungsi ini digunakan untuk menginsialisasi class utama.
	 *
	 * This function use to initialize the main class.
	 *
	*/
	function __construct()
	{
		parent::__construct();
	}

	/**
	 * Fungsi ini digunakan untuk menampilkan halaman index login.
	 *
	 * This function use for index login page.
	 *
	*/
	public function index()
	{
		$errormsg = '';
		if (!empty($_GET['errormsg'])) {
			if ($_GET['errormsg'] == 1) {
				$errormsg = "<div class='alert alert-warning'>Please complete all form!<a class='close' data-dismiss='alert' href='#' aria-hidden='true'>&times;</a></div>";
			} elseif($_GET['errormsg'] == 2) {
				$errormsg = "<div class='alert alert-warning'>Username or password not correct!<a class='close' data-dismiss='alert' href='#' aria-hidden='true'>&times;</a></div>";
			} else {
				header('location:index.php?mod=login&act=index');
			}
		}
		?>
		<h1>Log In Panel</h1>
		<?=$errormsg;?>
		<form role="form" action="route.php?mod=login&act=proclogin" method="post" id="form-login" autocomplete="off">
			<div class="form-group">
				<label for="login-username" class="sr-only">Username</label>
				<input type="text" name="username" id="login-username" class="form-control" placeholder="Username">
			</div>
			<div class="form-group box-password" style="display:none;">
				<label for="password" class="sr-only">Password</label>
				<div class="box-con-password"></div>
			</div>
			<div class="checkbox box-checkbox" style="display:none;">
				<span class="character-checkbox" onclick="showPassword()"></span>
				<span class="label">Show password</span>
			</div>
			<div class="form-group box-password-lock" style="display:none;">
				<div id="patternHolder" style="margin:0 auto;"></div>
				<div class="box-con-password-lock"></div>
			</div>
			<div class="form-group form-actions box-action" style="display:none;">
				<input type="submit" id="btn-login" class="btn btn-custom btn-block" value="Log in">
			</div>
		</form>
		<a href="index.php?mod=login&act=forgot" class="forget" title="Forgot your password?">Forgot your password?</a><hr>
		<?php
	}

	/**
	 * Fungsi ini digunakan untuk menampilkan halaman lupa password.
	 *
	 * This function use for forgot password page.
	 *
	*/
	public function forgot()
	{
		$errormsg = '';
		if (!empty($_GET['errormsg'])) {
			if ($_GET['errormsg'] == 1) {
				$errormsg = "<div class='alert alert-warning'>Please enter the correct email!<a class='close' data-dismiss='alert' href='#' aria-hidden='true'>&times;</a></div>";
			} elseif($_GET['errormsg'] == 2) {
				$errormsg = "<div class='alert alert-warning'>User with this email not found!<a class='close' data-dismiss='alert' href='#' aria-hidden='true'>&times;</a></div>";
			} elseif($_GET['errormsg'] == 3) {
				$errormsg = "<div class='alert alert-info'>Check your email for next step to recovery. Thank you!<a class='close' data-dismiss='alert' href='#' aria-hidden='true'>&times;</a></div>";
			} else {
				header('location:index.php?mod=login&act=forgot');
			}
		}
		?>
		<h1>Forgot Password</h1>
		<?=$errormsg;?>
		<form role="form" action="route.php?mod=login&act=procforgot" method="post" id="form-forgot" autocomplete="off">
			<div class="form-group">
				<label for="email" class="sr-only">Email</label>
				<input type="email" name="email" id="email" class="form-control" placeholder="somebody@example.com">
			</div>
			<input type="submit" id="btn-login" class="btn btn-custom btn-block" value="Send Email Activation">
		</form>
		<a href="index.php" class="forget" title="Back to login">Back to login</a><hr>
		<?php
	}

	/**
	 * Fungsi ini digunakan untuk mencari password login type.
	 *
	 * This function use for searching password login type.
	 *
	*/
	public function searchlocktype()
	{
		$_POST = $this->poval->sanitize($_POST);
		$user = $this->podb->from('users')
			->where('username', $_POST['username'])
			->where('block', 'N')
			->where('level', array('1','2','3'))
			->limit(1)
			->fetch();
		echo $user['locktype'];
	}

	/**
	 * Fungsi ini digunakan untuk menangani proses login user.
	 *
	 * This function use for handling user login process.
	 *
	*/
	public function proclogin()
	{
		$_POST = $this->poval->sanitize($_POST);
		$this->poval->validation_rules(array(
			'username' => 'required|max_len,50|min_len,3',
			'password' => 'required|max_len,50|min_len,6'
		));
		$this->poval->filter_rules(array(
			'username' => 'trim|sanitize_string',
			'password' => 'trim'
		));
		$validated_data = $this->poval->run($_POST);
		if($validated_data === false) {
			header('location:index.php?mod=login&act=index&errormsg=1');
		} else {
			$count_user = $this->podb->from('users')
				->where('username', $_POST['username'])
				->where('password', md5($_POST['password']))
				->where('block', 'N')
				->where('level', array('1','2','3'))
				->count();
			if ($count_user > 0) {
				$user = $this->podb->from('users')
					->select('user_level.menu')
					->leftJoin('user_level ON user_level.id_level = users.level')
					->where('username', $_POST['username'])
					->where('password', md5($_POST['password']))
					->where('block', 'N')
					->where('users.level', array('1','2','3'))
					->limit(1)
					->fetch();
				$timeout = new PoTimeout;
				$timeout->rec_session($user);
				$timeout->timer();
				$sid_lama = session_id();
				session_regenerate_id();
				$sid_baru = session_id();
				$sesi = array(
					'id_session' => $sid_baru
				);
				$query = $this->podb->update('users')
					->set($sesi)
					->where('username', $_POST['username']);
				$query->execute();
				header('location:admin.php?mod=home');
			} else {
				header('location:index.php?mod=login&act=index&errormsg=2');
			}
		}
	}

	/**
	 * Fungsi ini digunakan untuk menangani proses lupa password.
	 *
	 * This function use for handling forgot password process.
	 *
	*/
	public function procforgot()
	{
		$_POST = $this->poval->sanitize($_POST);
		$this->poval->validation_rules(array(
			'email' => 'required|valid_email'
		));
		$this->poval->filter_rules(array(
			'email' => 'trim|sanitize_email'
		));
		$validated_data = $this->poval->run($_POST);
		if($validated_data === false) {
			header('location:index.php?mod=login&act=forgot&errormsg=1');
		} else {
			$count_user = $this->podb->from('users')
				->where('email', $_POST['email'])
				->count();
			if ($count_user > 0) {
				$user = $this->podb->from('users')
					->where('email', $_POST['email'])
					->limit(1)
					->fetch();
				$forgotkey = md5(microtime() . $_SERVER['REMOTE_ADDR'] . '#$&^%$#' . mt_rand());
				$data = array(
					'forget_key' => $forgotkey
				);
				$query = $this->podb->update('users')
					->set($data)
					->where('email', $_POST['email']);
				$query->execute();
				$website_name = $this->posetting[0]['value'];
				$website_url = $this->posetting[1]['value']."/".DIR_ADM;
				$username = $user['username'];
				$nama_lengkap = $user['nama_lengkap'];
				$subject = "Recovery Password For $website_name";
				$message = "<html>
					<body>
						Indonesia :<br />
						-----------<br />
						Hi $nama_lengkap,<br />
						Jika anda tidak pernah meminta pesan informasi tentang lupa password di $website_name, silahkan untuk menghiraukan email ini.<br />
						Tetapi jika anda memang yang meminta pesan informasi ini, maka silahkan untuk mengklik tautan (link) di bawah ini :<br /><br />
						<a href=\"".$website_url."/index.php?mod=login&act=recovery&forgetuser=$username&forgetkey=$forgotkey\" title=\"Recover Password\">".$website_url."/index.php?mod=login&act=recovery&forgetuser=$username&forgetkey=$forgotkey</a><br /><br />
						Kemudian secara otomatis setelah anda mengklik tautan (link) di atas, password anda akan diganti menjadi password default yaitu : <b>123456</b>.<br />
						Silahkan untuk login dengan password tersebut kemudian ganti password default ini dengan password yang lebih aman.<br /><br />
						Salam hangat,<br />
						$website_name.<br /><br /><br />
						English :<br />
						-----------<br />
						Hi $nama_lengkap,<br />
						If you have never requested message information about forgotten password in $website_name, please to ignore this email.<br />
						But if you really are asking for messages of this information, then please to click on a link below :<br /><br />
						<a href=\"".$website_url."/index.php?mod=login&act=recovery&forgetuser=$username&forgetkey=$forgotkey\" title=\"Recover Password\">".$website_url."/index.php?mod=login&act=recovery&forgetuser=$username&forgetkey=$forgotkey</a><br /><br />
						Then automatically after you click a link above, your password will be changed to the default password is : <b>123456</b>.<br />
						Please to log in with the password and then change the default password to a more secure password.<br /><br />
						Warm regards,<br />
						$website_name.
					</body>
				</html>";
				if ($this->posetting[23]['value'] != 'SMTP') {
					$email = new PoEmail;
					$send = $email
						->to("$nama_lengkap <".$user['email'].">")
						->subject($subject)
						->message($message)
						->from($this->posetting[5]['value'], $this->posetting[0]['value'])
						->mail();
				} else {
					$this->pomail->isSMTP();
					$this->pomail->SMTPDebug = 0;
					$this->pomail->Debugoutput = 'html';
					$this->pomail->Host = $this->posetting[24]['value'];
					$this->pomail->Port = $this->posetting[27]['value'];
					$this->pomail->SMTPAuth = true;
					$this->pomail->Username = $this->posetting[25]['value'];;
					$this->pomail->Password = $this->posetting[26]['value'];
					$this->pomail->setFrom($this->posetting[5]['value'], $this->posetting[0]['value']);
					$this->pomail->addAddress($user['email'], $nama_lengkap);
					$this->pomail->Subject = $subject;
					$this->pomail->msgHTML($message);
					$this->pomail->send();
				}
				header('location:index.php?mod=login&act=forgot&errormsg=3');
			} else {
				header('location:index.php?mod=login&act=forgot&errormsg=2');
			}
		}
	}

	/**
	 * Fungsi ini digunakan untuk menangani proses pemulihan password.
	 *
	 * This function use for handling recovery password process.
	 *
	*/
	public function recovery()
	{
		if (!empty($_GET['forgetuser']) AND !empty($_GET['forgetkey'])){
			$forgetuser = $this->postring->valid($_GET['forgetuser'], 'xss');
			$forgetkey = $this->postring->valid($_GET['forgetkey'], 'xss');
			$count_user = $this->podb->from('users')
				->where('username', $forgetuser)
				->where('forget_key', $forgetkey)
				->count();
			if ($count_user > 0) {
				$user = $this->podb->from('users')
					->where('username', $forgetuser)
					->where('forget_key', $forgetkey)
					->limit(1)
					->fetch();
				if ($user['blokir'] == 'N'){
					$newcode = "123456";
					$pass = md5($newcode);
					$data = array(
						'password' => $pass,
						'forget_key' => ''
					);
					$query = $this->podb->update('users')
						->set($data)
						->where('username', $forgetuser)
						->where('forget_key', $forgetkey);
					$query->execute();
					?>
						<div class="alert alert-success alert-dismissable">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							<h4><i class="fa fa-check-circle"></i> Success</h4>
							Your password has been <a href="javascript:void(0)" class="alert-link">successfully reset</a> !
						</div>
						<a href="index.php" class="forget" title="Back to login">Back to login</a><hr>
					<?php
				} else {
					?>
						<div class="alert alert-warning alert-dismissable">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							<h4><i class="fa fa-exclamation-circle"></i> Warning</h4>
							Your account was <a href="javascript:void(0)" class="alert-link">blocked</a> !
						</div>
						<a href="index.php" class="forget" title="Back to login">Back to login</a><hr>
					<?php
				}
			} else {
				header('location:index.php');
			}
		} else {
			header('location:index.php');
		}
	}

}