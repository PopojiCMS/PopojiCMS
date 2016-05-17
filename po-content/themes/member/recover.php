<?=$this->layout('index');?>

<div class="container-fluid">
	<div class="container login-page">
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
			<?php if ($this->e($alertmsg) == '1') { ?>
				<div class="alert alert-success alert-dismissable">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<h4><i class="fa fa-check-circle"></i> <?=$this->e($front_member_notif_11);?></h4>
					<?=$this->e($front_member_notif_19);?> <a href="javascript:void(0)" class="alert-link"><?=$this->e($front_member_notif_20);?></a> !
				</div>
				<div class="row">
					<div class="col-md-12">
						<a href="<?=BASE_URL;?>/member/login" class="btn btn-info btn-block"><i class="fa fa-user"></i>&nbsp;&nbsp;<?=$this->e($front_member_login);?></a>
					</div>
				</div>
			<?php } else { ?>
				<div class="alert alert-warning alert-dismissable">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<h4><i class="fa fa-exclamation-circle"></i> <?=$this->e($front_member_notif_14);?></h4>
					<?=$this->e($front_member_notif_21);?> <a href="javascript:void(0)" class="alert-link"><?=$this->e($front_member_notif_22);?></a> !
				</div>
				<div class="row">
					<div class="col-md-12">
						<a href="<?=BASE_URL;?>/member/login" class="btn btn-info btn-block"><i class="fa fa-user"></i>&nbsp;&nbsp;<?=$this->e($front_member_login);?></a>
					</div>
				</div>
			<?php } ?>
			</div>
		</div>
	</div>
</div>