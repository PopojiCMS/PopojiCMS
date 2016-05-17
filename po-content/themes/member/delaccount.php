<?=$this->layout('index');?>

<main class="cd-main-content">
	<div class="container-fluid">
		<div class="container member-page">
			<div class="row">
				<div class="col-md-6 col-md-offset-3">
					<div class="page-header">
						<h3><i class="fa fa-user-times"></i> <?=$this->e($front_member_delete_account);?></h3>
					</div>
					<div class="member-content">
						<div class="alert alert-danger alert-dismissable"><?=$this->e($front_member_delete_account_text);?>.</div>
						<?=htmlspecialchars_decode($this->e($alertmsg));?>
						<div class="action-border">&nbsp;</div>
						<p class="text-center"><button class="btn btn-danger" data-toggle="modal" data-target="#delalert"><i class="fa fa-user-times"></i> <?=$this->e($front_member_delete_account);?></button></p>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>

<div class="modal fade" id="delalert" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog">
		<form method="post" action="<?=BASE_URL;?>/member/user/delaccount">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><i aria-hidden="true">&times;</i></button>
					<h4 class="modal-title" id="myModalLabel"><i class="fa fa-alert"></i> <?=$this->e($dialogdel_1);?></h4>
				</div>
				<div class="modal-body">
					<p><?=$this->e($dialogdel_5);?></p>
					<div class="form-group">
						<label for="oldpassword"><?=$this->e($front_member_password);?></label>
						<input class="form-control" type="password" name="password" id="password" required />
						<span class="help-block text-danger"><small><i>* <?=$this->e($front_member_password_help);?></i></small></span>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-user-times"></i> <?=$this->e($dialogdel_3);?></a>
					<button type="button" class="btn btn-sm btn-default" data-dismiss="modal"><?=$this->e($dialogdel_4);?></button>
				</div>
			</div>
		</form>
	</div>
</div>