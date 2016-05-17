<?=$this->layout('index');?>

<div class="container-fluid">
	<div class="container login-page">
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<div class="row">
					<div class="col-md-12 text-center">
						<img src="<?=BASE_URL;?>/<?=DIR_INC;?>/images/logo.png" class="logo" width="100" />
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 col-sm-6 col-md-6">
						<a href="<?=BASE_URL;?>/member/login/facebook" class="btn btn-fb btn-block"><i class="fa fa-facebook"></i>&nbsp;&nbsp;<?=$this->e($front_member_login_fb);?></a>
					</div>
					<div class="col-xs-12 col-sm-6 col-md-6">
						<a href="<?=BASE_URL;?>/member/login/twitter" class="btn btn-tw btn-block"><i class="fa fa-twitter"></i>&nbsp;&nbsp;<?=$this->e($front_member_login_tw);?></a>
					</div>
				</div>
				<div class="login-or">
					<hr class="hr-or"><span class="span-or"><?=$this->e($front_member_or);?></span>
				</div>
				<?=htmlspecialchars_decode($this->e($alertmsg));?>
				<form role="form" id="login-form" method="post" action="<?=BASE_URL;?>/member/login">
					<div class="form-group">
						<label for="username"><?=$this->e($front_member_username);?> / <?=$this->e($front_member_email);?></label>
						<input type="text" class="form-control" id="username" name="username" />
					</div>
					<div class="form-group">
						<a class="pull-right" href="<?=BASE_URL;?>/member/forgot"><?=$this->e($front_member_forgot);?></a>
						<label for="password"><?=$this->e($front_member_password);?></label>
						<input type="password" class="form-control" id="password" name="password" />
					</div>
					<div class="checkbox pull-right">
						<input type="checkbox" name="rememberme" id="rememberme" value="1" />
						<label for="rememberme"><?=$this->e($front_member_remember);?></label>
					</div>
					<button type="submit" class="btn btn btn-success"><i class="fa fa-sign-in"></i>&nbsp;&nbsp;<?=$this->e($front_member_login);?></button>
				</form>
				<div class="login-or">
					<hr class="hr-or"><span class="span-or"><?=$this->e($front_member_or);?></span>
				</div>
				<div class="row">
					<div class="col-md-12">
						<a href="<?=BASE_URL;?>/member/register" class="btn btn-danger btn-block"><i class="fa fa-user"></i>&nbsp;&nbsp;<?=$this->e($front_member_register);?></a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>