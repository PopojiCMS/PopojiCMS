<?=$this->layout('index');?>

<section id="page-title" class="page-title-mini" style="margin-top:-80px;">
	<div class="container clearfix">
		<ol class="breadcrumb">
			<li><a href="<?=BASE_URL;?>"><?=$this->e($front_home);?></a></li>
			<li><?=$this->e($front_search_title);?></li>
			<li><a href="<?=$this->e($social_url);?>"><?=$this->e($page_title);?></a></li>
		</ol>
	</div>
</section>

<section id="content">
	<div class="content-wrap">
		<div class="container clearfix">
			<div class="col-md-8 nobottommargin clearfix">
				<div id="posts" class="small-thumbs">
				<?php
					$search = $this->post()->getPostFromSearch('6', 'post.id_post DESC', $this->e($query), $this->e($page), WEB_LANG_ID);
					foreach($search as $post){
				?>
					<div class="entry clearfix">
						<div class="entry-image">
							<a href="<?=BASE_URL;?>/<?=DIR_CON;?>/uploads/<?=$post['picture'];?>" data-lightbox="image"><img class="image_fade" src="<?=BASE_URL;?>/<?=DIR_CON;?>/uploads/medium/medium_<?=$post['picture'];?>" alt="<?=$post['title'];?>"></a>
						</div>
						<div class="entry-c">
							<div class="entry-title">
								<h2><a href="<?=BASE_URL;?>/detailpost/<?=$post['seotitle'];?>"><?=$post['title'];?></a></h2>
							</div>
							<ul class="entry-meta clearfix">
								<li><i class="icon-calendar3"></i> <?=$this->pocore()->call->podatetime->tgl_indo($post['date']);?></li>
								<li><a href="javascript:void(0)"><i class="icon-user"></i> <?=$this->post()->getAuthorName($post['editor']);?></a></li>
								<li><i class="icon-eye"></i> <?=$post['hits'];?></li>
								<li><a href="<?=BASE_URL;?>/detailpost/<?=$post['seotitle'];?>#comments"><i class="icon-comments"></i> <?=$this->post()->getCountComment($post['id_post']);?></a></li>
							</ul>
							<ul class="entry-meta clearfix">
								<li><i class="icon-folder-open"></i> <?=$this->post()->getPostTag($post['tag']);?></li>
							</ul>
							<div class="entry-content">
								<p><?=$this->pocore()->call->postring->cuthighlight('post', $post['content'], '200');?>...</p>
								<a href="<?=BASE_URL;?>/detailpost/<?=$post['seotitle'];?>"class="more-link"><?=$this->e($front_readmore);?></a>
							</div>
						</div>
					</div>
				<?php } ?>
				</div>
				<div class="col-md-12 text-center" style="margin-top:30px;">
					<ul class="pagination nobottommargin">
						<?=$this->post()->getSearchPaging('6', $this->e($query), $this->e($page), WEB_LANG_ID, '1', $this->e($front_paging_prev), $this->e($front_paging_next));?>
					</ul>
				</div>
			</div>
			<div class="col-md-4 nobottommargin clearfix">
				<!-- Insert Sidebar -->
				<?=$this->insert('sidebar');?>
			</div>
		</div>
	</div>
</section>