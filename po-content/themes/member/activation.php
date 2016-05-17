<?=$this->layout('index');?>

<div class="container-fluid">
	<div class="container login-page">
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
			<?php if ($this->e($alertmsg) == '1') { ?>
				<div class="alert alert-success alert-dismissable">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<h4><i class="fa fa-check-circle"></i> <?=$this->e($front_member_notif_11);?></h4>
					<?=$this->e($front_member_notif_15);?> <a href="javascript:void(0)" class="alert-link"><?=$this->e($front_member_notif_18);?></a> !
				</div>
				<div class="row">
					<div class="col-md-12">
						<a href="<?=BASE_URL;?>/member/login" class="btn btn-info btn-block"><i class="fa fa-user"></i>&nbsp;&nbsp;<?=$this->e($front_member_login);?></a>
					</div>
				</div>
			<?php } else if ($this->e($alertmsg) == '2') { ?>
				<div class="alert alert-info alert-dismissable">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<h4><i class="fa fa-exclamation-circle"></i> <?=$this->e($front_member_notif_12);?></h4>
					<?=$this->e($front_member_notif_16);?> <a href="javascript:void(0)" class="alert-link"><?=$this->e($front_member_notif_18);?></a> !
				</div>
				<div class="row">
					<div class="col-md-12">
						<a href="<?=BASE_URL;?>/member/login" class="btn btn-info btn-block"><i class="fa fa-user"></i>&nbsp;&nbsp;<?=$this->e($front_member_login);?></a>
					</div>
				</div>
			<?php } else { ?>
				<div class="alert alert-danger alert-dismissable">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<h4><i class="fa fa-check-circle"></i> <?=$this->e($front_member_notif_13);?></h4>
					<?=$this->e($front_member_notif_17);?> <a href="javascript:void(0)" class="alert-link"><?=$this->e($front_member_notif_18);?></a> !
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