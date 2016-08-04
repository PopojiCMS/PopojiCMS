<?=$this->layout('index');?>

<div class="container profile-page">
	<div class="row">
		<div class="panel">
			<div class="cover-photo">
				<div class="fb-timeline-img">
					<img src="<?=BASE_URL.'/'.DIR_INC;?>/images/bg-profile.jpg" alt="" />
				</div>
				<div class="fb-name">
					<h2><a href="<?=BASE_URL;?>/member/profile/<?=$this->e($user['username']);?>"><?=$this->e($user['nama_lengkap']);?></a></h2>
				</div>
			</div>
			<div class="panel-body">
				<div class="profile-thumb">
					<?php
						$avatar = DIR_CON."/uploads/user-".$this->e($user['id_user']).".jpg";
						$avatarimg = (file_exists($avatar) ? $this->e($user['id_user']) : 'editor');
					?>
					<img src="<?=BASE_URL;?>/<?=DIR_CON;?>/uploads/user-<?=$avatarimg;?>.jpg" alt="" />
				</div>
				<p class="fb-user-mail">
					<span class="label label-success"><i class="fa fa-envelope"></i> <?=$this->e($user['email']);?></span>
					<span class="label label-warning"><i class="fa fa-phone"></i> <?=$this->e($user['no_telp']);?></span>
				</p>
				<p class="fb-user-bio"><?=html_entity_decode($user['bio']);?></p>
			</div>
		</div>
		<?php
			$posts = $this->post()->getPostFromEditor($this->e($user['id_user']), '5', 'DESC', $this->e($page), WEB_LANG_ID);
			foreach($posts as $post){
		?>
		<div class="panel">
			<div class="panel-body">
				<div class="fb-user-thumb">
					<img src="<?=BASE_URL;?>/<?=DIR_CON;?>/thumbs/<?=$post['picture'];?>" alt="" />
				</div>
				<div class="fb-user-details">
					<h3><a href="<?=$this->pocore()->call->postring->permalink(rtrim(BASE_URL, '/'), $post);?>"><?=$post['title'];?></a></h3>
					<p><?=$this->pocore()->call->podatetime->tgl_indo($post['date']);?></p>
				</div>
				<div class="clearfix"></div>
				<p class="fb-user-status"><?=$this->pocore()->call->postring->cuthighlight('post', $post['content'], '500');?>...</p>
				<div class="fb-status-container fb-border">
					<div class="fb-time-action">
						<?=$this->category()->getCategory($post['id_post'], WEB_LANG_ID);?>
						<span>-</span>
						<a href="<?=$this->pocore()->call->postring->permalink(rtrim(BASE_URL, '/'), $post);?>" target="_blank"><?=$post['hits'];?> <?=$this->e($front_hits);?></a>
						<span>-</span>
						<a href="<?=$this->pocore()->call->postring->permalink(rtrim(BASE_URL, '/'), $post);?>" target="_blank"><?=$this->post()->getCountComment($post['id_post']);?> <?=$this->e($front_comment);?></a>
						<span>-</span>
						<a href="<?=$this->pocore()->call->postring->permalink(rtrim(BASE_URL, '/'), $post);?>" target="_blank"><?=$this->post()->getPostTag($post['tag']);?></a>
					</div>
				</div>
			</div>
		</div>
		<?php } ?>
		<div class="col-md-12 text-center">
			<ul class="pagination pagination-sm">
				<?=$this->post()->getPostFromEditorPaging($this->e($user['id_user']), $this->e($user['username']), '5', $this->e($page), WEB_LANG_ID, '1', $this->e($front_paging_prev), $this->e($front_paging_next));?>
			</ul>
		</div>
	</div>
</div>