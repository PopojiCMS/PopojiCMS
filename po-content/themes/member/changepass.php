<?=$this->layout('index');?>

<main class="cd-main-content">
	<div class="container-fluid">
		<div class="container member-page">
			<div class="row">
				<div class="col-md-6 col-md-offset-3">
					<div class="page-header">
						<h3><i class="fa fa-unlock-alt"></i> <?=$this->e($front_member_change_password);?></h3>
					</div>
					<div class="member-content">
						<?=htmlspecialchars_decode($this->e($alertmsg));?>
						<form method="post" action="<?=BASE_URL;?>/member/user/changepass">
							<div class="form-group">
								<label for="oldpassword"><?=$this->e($front_member_old_password);?></label>
								<input class="form-control" type="password" name="oldpassword" id="oldpassword" required />
								<span class="help-block text-danger"><small><i>* <?=$this->e($front_member_password_help);?></i></small></span>
							</div>
							<div class="form-group">
								<label for="newpassword"><?=$this->e($front_member_new_password);?></label>
								<input class="form-control" type="password" name="newpassword" id="newpassword" required />
							</div>
							<div class="form-group">
								<label for="repassword"><?=$this->e($front_member_new_password_again);?></label>
								<input class="form-control" type="password" name="repassword" id="repassword" required />
							</div>
							<div class="action-border">&nbsp;</div>
							<button class="btn btn-success" type="submit" name="submit"><i class="fa fa-edit"></i> <?=$this->e($front_member_change_password);?></button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>