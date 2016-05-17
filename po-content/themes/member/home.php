<?=$this->layout('index');?>

<main class="cd-main-content">
	<div class="container-fluid">
		<div class="container member-page">
			<div class="row">
				<div class="col-md-10 col-md-offset-1">
					<div class="page-header">
						<h3><?=$this->e($front_member_welcome);?> <small><?=$user['nama_lengkap'];?> <?=$this->e($front_member_welcome_to);?></small></h3>
					</div>
					<div class="box-home-btn text-center">
						<ul class="ds-btn">
							<li>
								<a class="btn btn-success" href="<?=BASE_URL;?>/member/post">
								<i class="fa fa-pencil pull-left"></i><span><?=$this->e($front_member_post);?><br><small><?=$this->e($front_member_allpost);?></small></span></a>
							</li>
							<li>
								<a class="btn btn-danger" href="<?=BASE_URL;?>/member/post/addnew">
								<i class="fa fa-plus pull-left"></i><span><?=$this->e($front_member_post);?><br><small><?=$this->e($front_member_addpost);?></small></span></a>
							</li>
							<li>
								<a class="btn btn-info" href="<?=BASE_URL;?>/member/user/edit">
								<i class="fa fa-user pull-left"></i><span><?=$this->e($front_member_account);?><br><small><?=$this->e($front_member_edit_account);?></small></span></a>
							</li>
							<li>
								<a class="btn btn-warning" href="<?=BASE_URL;?>/member/user/changepass">
								<i class="fa fa-unlock-alt pull-left"></i><span><?=$this->e($front_member_account);?><br><small><?=$this->e($front_member_change_password);?></small></span></a>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>