<header>
	<div class="cd-logo">
		<a href="<?=BASE_URL;?>/member">
			<?php
				$avatar = DIR_CON."/uploads/user-".$_SESSION['iduser_member'].".jpg";
				$avatarimg = (file_exists($avatar) ? $_SESSION['iduser_member'] : 'editor');
			?>
			<img src="<?=BASE_URL;?>/<?=DIR_CON;?>/uploads/user-<?=$avatarimg;?>.jpg" alt="<?=$_SESSION['namauser_member'];?>" width="50" height="50" class="img-circle" />
		</a>
	</div>
	<nav class="cd-main-nav-wrapper">
		<ul class="cd-main-nav">
			<li><a href="<?=BASE_URL;?>/member"><i class="fa fa-dashboard">&nbsp;</i> <?=$this->e($front_member_dashboard);?></a></li>
			<li><a href="<?=BASE_URL;?>" target="_blank"><i class="fa fa-home">&nbsp;</i> <?=$this->e($front_member_mainweb);?></a></li>
			<li><a href="<?=BASE_URL;?>/member/logout"><i class="fa fa-power-off">&nbsp;</i> <?=$this->e($front_member_logout);?></a></li>
			<li>
				<a href="#0" class="cd-subnav-trigger"><span><i class="fa fa-bars">&nbsp;</i> <?=$this->e($front_member_menu);?></span></a>
				<ul>
					<li class="go-back"><a href="#0"><?=$this->e($front_member_back);?></a></li>
					<li><a href="<?=BASE_URL;?>/member/post"><i class="fa fa-pencil">&nbsp;</i> <?=$this->e($front_member_allpost);?></a></li>
					<li><a href="<?=BASE_URL;?>/member/post/addnew"><i class="fa fa-plus">&nbsp;</i> <?=$this->e($front_member_addpost);?></a></li>
					<li><a href="<?=BASE_URL;?>/member/user/edit"><i class="fa fa-user">&nbsp;</i> <?=$this->e($front_member_edit_account);?></a></li>
					<li><a href="<?=BASE_URL;?>/member/user/changepass"><i class="fa fa-unlock-alt">&nbsp;</i> <?=$this->e($front_member_change_password);?></a></li>
					<li><a href="<?=BASE_URL;?>/member/user/delaccount"><i class="fa fa-user-times">&nbsp;</i> <?=$this->e($front_member_delete_account);?></a></li>
					<li><a href="#0" class="placeholder">Placeholder</a></li>
				</ul>
			</li>
		</ul>
	</nav>
	<a href="#0" class="cd-nav-trigger">&nbsp;<span></span></a>
</header>