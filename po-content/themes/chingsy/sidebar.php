<div class="line hidden-lg hidden-md"></div>

<div class="sidebar-widgets-wrap clearfix">
	<div class="widget clearfix">
		<div class="col_one_third nobottommargin">
			<a href="#" class="social-icon si-dark si-colored si-facebook nobottommargin" style="margin-right: 10px;">
				<i class="icon-facebook"></i>
				<i class="icon-facebook"></i>
			</a>
			<small style="display: block; margin-top: 3px;"><strong><div class="counter counter-inherit"><span data-from="500" data-to="<?=$this->oauth()->getFacebookCount("http://www.popojicms.org");?>" data-refresh-interval="50" data-speed="2500" data-comma="true"></span></div></strong>Share</small>
		</div>
		<div class="col_one_third nobottommargin">
			<a href="#" class="social-icon si-dark si-colored si-twitter nobottommargin" style="margin-right: 10px;">
				<i class="icon-twitter"></i>
				<i class="icon-twitter"></i>
			</a>
			<small style="display: block; margin-top: 3px;"><strong><div class="counter counter-inherit"><span data-from="500" data-to="<?=$this->oauth()->getTwitterCount("DwiraSurvivor");?>" data-refresh-interval="50" data-speed="2500" data-comma="true"></span></div></strong>Followers</small>
		</div>
		<div class="col_one_third nobottommargin col_last">
			<a href="#" class="social-icon si-dark si-colored si-rss nobottommargin" style="margin-right: 10px;">
				<i class="icon-rss"></i>
				<i class="icon-rss"></i>
			</a>
			<small style="display: block; margin-top: 3px;"><strong><div class="counter counter-inherit"><span data-from="500" data-to="<?=$this->oauth()->getSubscribeCount();?>" data-refresh-interval="50" data-speed="2500" data-comma="true"></span></div></strong>Subscribe</small>
		</div>
	</div>

	<div class="widget clearfix">
		<img class="aligncenter" src="<?=BASE_URL;?>/<?=DIR_CON;?>/uploads/ad-square.png" alt="">
	</div>

	<div class="widget widget_links clearfix">
		<h4><?=$this->e($front_categories);?></h4>
		<div class="col_half nobottommargin col_last">
			<ul>
			<?php
				$categorys_side = $this->category()->getAllCategory(WEB_LANG_ID);
				foreach($categorys_side as $category_side){
			?>
				<li><a href="<?=BASE_URL;?>/category/<?=$category_side['seotitle'];?>"><?=$category_side['title'];?></a></li>
			<?php } ?>
			</ul>
		</div>
	</div>

	<div class="widget clearfix">
		<div class="tabs nobottommargin clearfix" id="sidebar-tabs">
			<ul class="tab-nav clearfix">
				<li><a href="#tabs-1"><?=$this->e($front_popular);?></a></li>
				<li><a href="#tabs-2"><?=$this->e($front_recent);?></a></li>
				<li><a href="#tabs-3"><i class="icon-comments-alt norightmargin"></i></a></li>
			</ul>
			<div class="tab-container">
				<div class="tab-content clearfix" id="tabs-1">
					<div id="popular-post-list-sidebar">
					<?php
						$populars_side = $this->post()->getPopular('5', 'DESC', WEB_LANG_ID);
						foreach($populars_side as $popular_side){
					?>
						<div class="spost clearfix">
							<div class="entry-image">
								<a href="<?=BASE_URL;?>/detailpost/<?=$popular_side['seotitle'];?>" class="nobg"><img class="img-circle" src="<?=BASE_URL;?>/<?=DIR_CON;?>/thumbs/<?=$popular_side['picture'];?>" alt=""></a>
							</div>
							<div class="entry-c">
								<div class="entry-title">
									<h4><a href="<?=BASE_URL;?>/detailpost/<?=$popular_side['seotitle'];?>"><?=$popular_side['title'];?></a></h4>
								</div>
								<ul class="entry-meta">
									<li><i class="icon-comments-alt"></i> <?=$this->post()->getCountComment($popular_side['id_post']);?> <?=$this->e($front_comment);?></li>
								</ul>
							</div>
						</div>
					<?php } ?>
					</div>
				</div>

				<div class="tab-content clearfix" id="tabs-2">
					<div id="recent-post-list-sidebar">
					<?php
						$recents_side = $this->post()->getRecent('5', 'DESC', WEB_LANG_ID);
						foreach($recents_side as $recent_side){
					?>
						<div class="spost clearfix">
							<div class="entry-image">
								<a href="<?=BASE_URL;?>/detailpost/<?=$recent_side['seotitle'];?>" class="nobg"><img class="img-circle" src="<?=BASE_URL;?>/<?=DIR_CON;?>/thumbs/<?=$recent_side['picture'];?>" alt=""></a>
							</div>
							<div class="entry-c">
								<div class="entry-title">
									<h4><a href="<?=BASE_URL;?>/detailpost/<?=$recent_side['seotitle'];?>"><?=$recent_side['title'];?></a></h4>
								</div>
								<ul class="entry-meta">
									<li><?=$this->pocore()->call->podatetime->tgl_indo($recent_side['date']);?></li>
								</ul>
							</div>
						</div>
					<?php } ?>
					</div>
				</div>

				<div class="tab-content clearfix" id="tabs-3">
					<div id="recent-post-list-sidebar">
					<?php
						$comments_side = $this->post()->getComment('5', 'DESC');
						foreach($comments_side as $comment_side){
						$comment_post = $this->post()->getPostById($comment_side['id_post'], WEB_LANG_ID);
					?>
						<div class="spost clearfix">
							<div class="entry-image">
								<a href="<?=BASE_URL;?>/detailpost/<?=$comment_post['seotitle'];?>#comment" class="nobg"><img class="img-circle" src="<?=BASE_URL;?>/<?=DIR_CON;?>/uploads/medium/medium_avatar.jpg" alt=""></a>
							</div>
							<div class="entry-c">
								<strong><?=$comment_side['name'];?>:</strong> <?=$this->pocore()->call->postring->cuthighlight('post', $comment_side['comment'], '80');?>...
							</div>
						</div>
					<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="widget clearfix">
		<img class="aligncenter" src="<?=BASE_URL;?>/<?=DIR_CON;?>/uploads/ad-square.png" alt="">
	</div>

	<div id="tags" class="widget clearfix">
		<h4 class="highlight-me"><?=$this->e($front_tag);?></h4>
		<div class="tagcloud">
			<?=$this->post()->getAllTag('RAND()', '30', '');?>
		</div>
	</div>

	<div class="widget clearfix">
		<iframe src="//www.facebook.com/plugins/likebox.php?href=https%3A%2F%2Fwww.facebook.com%2Fpopojicms&amp;width=350&amp;height=240&amp;colorscheme=light&amp;show_faces=true&amp;header=true&amp;stream=false&amp;show_border=true&amp;appId=499481203443583" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:350px; height:240px; max-width: 100% !important;" allowTransparency="true"></iframe>
	</div>
</div>