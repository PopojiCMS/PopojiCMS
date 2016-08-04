<?=$this->layout('index');?>

<div class="section header-stick bottommargin-lg clearfix" style="padding: 20px 0;">
	<div>
		<div class="container clearfix">
			<span class="label label-danger bnews-title"><?=$this->e($front_breaking_news);?>:</span>
			<div class="fslider bnews-slider nobottommargin" data-speed="800" data-pause="6000" data-arrows="false" data-pagi="false">
				<div class="flexslider">
					<div class="slider-wrap">
					<?php
						$headlines = $this->post()->getHeadline('5', 'DESC', WEB_LANG_ID);
						foreach($headlines as $headline){
					?>
						<div class="slide"><a href="<?=$this->pocore()->call->postring->permalink(rtrim(BASE_URL, '/'), $headline);?>"><strong><?=$headline['title'];?>:</strong> <?=$this->pocore()->call->postring->cuthighlight('post', $headline['content'], '80');?>...</a></div>
					<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="container clearfix">
	<div class="row">
		<div class="col-md-8 bottommargin">
			<div class="col_full bottommargin-lg">
				<div class="fslider flex-thumb-grid grid-6" data-animation="fade" data-arrows="true" data-thumbs="true">
					<div class="flexslider">
						<div class="slider-wrap">
						<?php
							$sliders_post = $this->post()->getPost('6', 'DESC', WEB_LANG_ID);
							foreach($sliders_post as $slider_post){
							$slider_category = $this->category()->getCategory($slider_post['id_post'], WEB_LANG_ID);
						?>
							<div class="slide" data-thumb="<?=BASE_URL;?>/<?=DIR_CON;?>/thumbs/<?=$slider_post['picture'];?>">
								<a href="<?=$this->pocore()->call->postring->permalink(rtrim(BASE_URL, '/'), $slider_post);?>">
									<img src="<?=BASE_URL;?>/<?=DIR_CON;?>/uploads/<?=$slider_post['picture'];?>" alt="">
									<div class="overlay">
										<div class="text-overlay">
											<div class="text-overlay-title"><h3><?=$slider_post['title'];?></h3></div>
											<div class="text-overlay-meta"><span><?=$slider_category;?></span></div>
										</div>
									</div>
								</a>
							</div>
						<?php } ?>
						</div>
					</div>
				</div>
			</div>

			<div class="clear"></div>

			<div class="col_full bottommargin-lg clearfix">
				<?php $category_title = $this->category()->getOneCategory('1', WEB_LANG_ID); ?>
				<div class="fancy-title title-border"><h3><?=$category_title['title'];?></h3></div>
				<?php
					$post_by_categorys = $this->post()->getPostByCategory('1', '1', 'DESC', WEB_LANG_ID);
					foreach($post_by_categorys as $list_post){
				?>
				<div class="ipost clearfix">
					<div class="col_half bottommargin-sm">
						<div class="entry-image"><a href="<?=$this->pocore()->call->postring->permalink(rtrim(BASE_URL, '/'), $list_post);?>"><img class="image_fade" src="<?=BASE_URL;?>/<?=DIR_CON;?>/uploads/medium/medium_<?=$list_post['picture'];?>" alt=""></a></div>
					</div>
					<div class="col_half bottommargin-sm col_last">
						<div class="entry-title">
							<h3><a href="<?=$this->pocore()->call->postring->permalink(rtrim(BASE_URL, '/'), $list_post);?>"><?=$list_post['title'];?></a></h3>
						</div>
						<ul class="entry-meta clearfix">
							<li><i class="icon-calendar3"></i> <?=$this->pocore()->call->podatetime->tgl_indo($list_post['date']);?></li>
							<li><a href="<?=$this->pocore()->call->postring->permalink(rtrim(BASE_URL, '/'), $list_post);?>#comments"><i class="icon-comments"></i> <?=$this->post()->getCountComment($list_post['id_post']);?></a></li>
						</ul>
						<div class="entry-content">
							<p><?=$this->pocore()->call->postring->cuthighlight('post', $list_post['content'], '250');?>...</p>
						</div>
					</div>
				</div>
				<?php } ?>

				<div class="clear"></div>

				<div class="col_half nobottommargin">
				<?php
					$post_by_categorys2 = $this->post()->getPostByCategory('1', '1,2', 'DESC', WEB_LANG_ID);
					foreach($post_by_categorys2 as $list_post2){
				?>
					<div class="spost clearfix">
						<div class="entry-image"><a href="<?=$this->pocore()->call->postring->permalink(rtrim(BASE_URL, '/'), $list_post2);?>"><img src="<?=BASE_URL;?>/<?=DIR_CON;?>/thumbs/<?=$list_post2['picture'];?>" alt=""></a></div>
						<div class="entry-c">
							<div class="entry-title">
								<h4><a href="<?=$this->pocore()->call->postring->permalink(rtrim(BASE_URL, '/'), $list_post2);?>"><?=$list_post2['title'];?></a></h4>
							</div>
							<ul class="entry-meta">
								<li><i class="icon-calendar3"></i> <?=$this->pocore()->call->podatetime->tgl_indo($list_post2['date']);?></li>
								<li><a href="<?=$this->pocore()->call->postring->permalink(rtrim(BASE_URL, '/'), $list_post2);?>#comment"><i class="icon-comments"></i> <?=$this->post()->getCountComment($list_post2['id_post']);?></a></li>
							</ul>
						</div>
					</div>
				<?php } ?>
				</div>

				<div class="col_half nobottommargin col_last">
				<?php
					$post_by_categorys3 = $this->post()->getPostByCategory('1', '3,2', 'DESC', WEB_LANG_ID);
					foreach($post_by_categorys3 as $list_post3){
				?>
					<div class="spost clearfix">
						<div class="entry-image"><a href="<?=$this->pocore()->call->postring->permalink(rtrim(BASE_URL, '/'), $list_post3);?>"><img src="<?=BASE_URL;?>/<?=DIR_CON;?>/thumbs/<?=$list_post3['picture'];?>" alt=""></a></div>
						<div class="entry-c">
							<div class="entry-title">
								<h4><a href="<?=$this->pocore()->call->postring->permalink(rtrim(BASE_URL, '/'), $list_post3);?>"><?=$list_post3['title'];?></a></h4>
							</div>
							<ul class="entry-meta">
								<li><i class="icon-calendar3"></i> <?=$this->pocore()->call->podatetime->tgl_indo($list_post3['date']);?></li>
								<li><a href="<?=$this->pocore()->call->postring->permalink(rtrim(BASE_URL, '/'), $list_post3);?>#comment"><i class="icon-comments"></i> <?=$this->post()->getCountComment($list_post3['id_post']);?></a></li>
							</ul>
						</div>
					</div>
				<?php } ?>
				</div>
			</div>

			<div class="bottommargin-lg">
				<img src="<?=BASE_URL;?>/<?=DIR_CON;?>/uploads/ad-long.jpg" alt="" class="aligncenter notopmargin nobottommargin">
			</div>

			<div class="col_full bottommargin-lg clearfix">
				<?php $category_title2 = $this->category()->getOneCategory('2', WEB_LANG_ID); ?>
				<div class="fancy-title title-border"><h3><?=$category_title2['title'];?></h3></div>
				<?php
					$post_by_categorys4 = $this->post()->getPostByCategory('2', '1', 'DESC', WEB_LANG_ID);
					foreach($post_by_categorys4 as $list_post4){
				?>
				<div class="ipost clearfix">
					<div class="col_half bottommargin-sm">
						<div class="entry-image"><a href="<?=$this->pocore()->call->postring->permalink(rtrim(BASE_URL, '/'), $list_post4);?>"><img class="image_fade" src="<?=BASE_URL;?>/<?=DIR_CON;?>/uploads/medium/medium_<?=$list_post4['picture'];?>" alt=""></a></div>
					</div>
					<div class="col_half bottommargin-sm col_last">
						<div class="entry-title">
							<h3><a href="<?=$this->pocore()->call->postring->permalink(rtrim(BASE_URL, '/'), $list_post4);?>"><?=$list_post4['title'];?></a></h3>
						</div>
						<ul class="entry-meta clearfix">
							<li><i class="icon-calendar3"></i> <?=$this->pocore()->call->podatetime->tgl_indo($list_post4['date']);?></li>
							<li><a href="<?=$this->pocore()->call->postring->permalink(rtrim(BASE_URL, '/'), $list_post4);?>#comments"><i class="icon-comments"></i> <?=$this->post()->getCountComment($list_post4['id_post']);?></a></li>
						</ul>
						<div class="entry-content">
							<p><?=$this->pocore()->call->postring->cuthighlight('post', $list_post4['content'], '250');?>...</p>
						</div>
					</div>
				</div>
				<?php } ?>

				<div class="clear"></div>

				<div class="col_half nobottommargin">
				<?php
					$post_by_categorys5 = $this->post()->getPostByCategory('2', '1,2', 'DESC', WEB_LANG_ID);
					foreach($post_by_categorys5 as $list_post5){
				?>
					<div class="spost clearfix">
						<div class="entry-image"><a href="<?=$this->pocore()->call->postring->permalink(rtrim(BASE_URL, '/'), $list_post5);?>"><img src="<?=BASE_URL;?>/<?=DIR_CON;?>/thumbs/<?=$list_post5['picture'];?>" alt=""></a></div>
						<div class="entry-c">
							<div class="entry-title">
								<h4><a href="<?=$this->pocore()->call->postring->permalink(rtrim(BASE_URL, '/'), $list_post5);?>"><?=$list_post5['title'];?></a></h4>
							</div>
							<ul class="entry-meta">
								<li><i class="icon-calendar3"></i> <?=$this->pocore()->call->podatetime->tgl_indo($list_post5['date']);?></li>
								<li><a href="<?=$this->pocore()->call->postring->permalink(rtrim(BASE_URL, '/'), $list_post5);?>#comment"><i class="icon-comments"></i> <?=$this->post()->getCountComment($list_post5['id_post']);?></a></li>
							</ul>
						</div>
					</div>
				<?php } ?>
				</div>

				<div class="col_half nobottommargin col_last">
				<?php
					$post_by_categorys6 = $this->post()->getPostByCategory('2', '3,2', 'DESC', WEB_LANG_ID);
					foreach($post_by_categorys6 as $list_post6){
				?>
					<div class="spost clearfix">
						<div class="entry-image"><a href="<?=$this->pocore()->call->postring->permalink(rtrim(BASE_URL, '/'), $list_post6);?>"><img src="<?=BASE_URL;?>/<?=DIR_CON;?>/thumbs/<?=$list_post6['picture'];?>" alt=""></a></div>
						<div class="entry-c">
							<div class="entry-title">
								<h4><a href="<?=$this->pocore()->call->postring->permalink(rtrim(BASE_URL, '/'), $list_post6);?>"><?=$list_post6['title'];?></a></h4>
							</div>
							<ul class="entry-meta">
								<li><i class="icon-calendar3"></i> <?=$this->pocore()->call->podatetime->tgl_indo($list_post6['date']);?></li>
								<li><a href="<?=$this->pocore()->call->postring->permalink(rtrim(BASE_URL, '/'), $list_post6);?>#comment"><i class="icon-comments"></i> <?=$this->post()->getCountComment($list_post6['id_post']);?></a></li>
							</ul>
						</div>
					</div>
				<?php } ?>
				</div>
			</div>

			<div class="col_full nobottommargin clearfix">
				<?php $category_title3 = $this->category()->getOneCategory('3', WEB_LANG_ID); ?>
				<div class="fancy-title title-border"><h3><?=$category_title3['title'];?></h3></div>
				<?php
					$post_by_categorys7 = $this->post()->getPostByCategory('3', '1', 'DESC', WEB_LANG_ID);
					foreach($post_by_categorys7 as $list_post7){
				?>
				<div class="col-md-6 bottommargin">
					<div class="ipost clearfix">
						<div class="entry-image">
							<a href="<?=$this->pocore()->call->postring->permalink(rtrim(BASE_URL, '/'), $list_post7);?>"><img class="image_fade" src="<?=BASE_URL;?>/<?=DIR_CON;?>/uploads/medium/medium_<?=$list_post7['picture'];?>" alt=""></a>
						</div>
						<div class="entry-title">
							<h3><a href="<?=$this->pocore()->call->postring->permalink(rtrim(BASE_URL, '/'), $list_post7);?>"><?=$list_post7['title'];?></a></h3>
						</div>
						<ul class="entry-meta clearfix">
							<li><i class="icon-calendar3"></i> <?=$this->pocore()->call->podatetime->tgl_indo($list_post7['date']);?></li>
							<li><a href="<?=$this->pocore()->call->postring->permalink(rtrim(BASE_URL, '/'), $list_post7);?>#comments"><i class="icon-comments"></i> <?=$this->post()->getCountComment($list_post7['id_post']);?></a></li>
						</ul>
						<div class="entry-content">
							<p><?=$this->pocore()->call->postring->cuthighlight('post', $list_post7['content'], '150');?>...</p>
						</div>
					</div>
				</div>
				<?php } ?>
				<div class="col-md-6 bottommargin">
				<?php
					$post_by_categorys8 = $this->post()->getPostByCategory('3', '1,4', 'DESC', WEB_LANG_ID);
					foreach($post_by_categorys8 as $list_post8){
				?>
					<div class="spost clearfix">
						<div class="entry-image"><a href="<?=$this->pocore()->call->postring->permalink(rtrim(BASE_URL, '/'), $list_post8);?>"><img src="<?=BASE_URL;?>/<?=DIR_CON;?>/uploads/medium/medium_<?=$list_post8['picture'];?>" alt=""></a></div>
						<div class="entry-c">
							<div class="entry-title">
								<h4><a href="<?=$this->pocore()->call->postring->permalink(rtrim(BASE_URL, '/'), $list_post8);?>"><?=$list_post8['title'];?></a></h4>
							</div>
							<ul class="entry-meta">
								<li><i class="icon-calendar3"></i> <?=$this->pocore()->call->podatetime->tgl_indo($list_post8['date']);?></li>
								<li><a href="<?=$this->pocore()->call->postring->permalink(rtrim(BASE_URL, '/'), $list_post8);?>"><i class="icon-comments"></i> <?=$this->post()->getCountComment($list_post8['id_post']);?></a></li>
							</ul>
						</div>
					</div>
				<?php } ?>
				</div>
			</div>

			<div class="col_full nobottommargin clearfix">
				<?php $category_title4 = $this->category()->getOneCategory('4', WEB_LANG_ID); ?>
				<div class="fancy-title title-border"><h3><?=$category_title4['title'];?></h3></div>
				<?php
					$post_by_categorys9 = $this->post()->getPostByCategory('4', '1', 'DESC', WEB_LANG_ID);
					foreach($post_by_categorys9 as $list_post9){
				?>
				<div class="col-md-6 bottommargin">
					<div class="ipost clearfix">
						<div class="entry-image">
							<a href="<?=$this->pocore()->call->postring->permalink(rtrim(BASE_URL, '/'), $list_post9);?>"><img class="image_fade" src="<?=BASE_URL;?>/<?=DIR_CON;?>/uploads/medium/medium_<?=$list_post9['picture'];?>" alt=""></a>
						</div>
						<div class="entry-title">
							<h3><a href="<?=$this->pocore()->call->postring->permalink(rtrim(BASE_URL, '/'), $list_post9);?>"><?=$list_post9['title'];?></a></h3>
						</div>
						<ul class="entry-meta clearfix">
							<li><i class="icon-calendar3"></i> <?=$this->pocore()->call->podatetime->tgl_indo($list_post9['date']);?></li>
							<li><a href="<?=$this->pocore()->call->postring->permalink(rtrim(BASE_URL, '/'), $list_post9);?>#comments"><i class="icon-comments"></i> <?=$this->post()->getCountComment($list_post9['id_post']);?></a></li>
						</ul>
						<div class="entry-content">
							<p><?=$this->pocore()->call->postring->cuthighlight('post', $list_post9['content'], '150');?>...</p>
						</div>
					</div>
				</div>
				<?php } ?>
				<div class="col-md-6 bottommargin">
				<?php
					$post_by_categorys10 = $this->post()->getPostByCategory('4', '1,4', 'DESC', WEB_LANG_ID);
					foreach($post_by_categorys10 as $list_post10){
				?>
					<div class="spost clearfix">
						<div class="entry-image"><a href="<?=$this->pocore()->call->postring->permalink(rtrim(BASE_URL, '/'), $list_post10);?>"><img src="<?=BASE_URL;?>/<?=DIR_CON;?>/uploads/medium/medium_<?=$list_post10['picture'];?>" alt=""></a></div>
						<div class="entry-c">
							<div class="entry-title">
								<h4><a href="<?=$this->pocore()->call->postring->permalink(rtrim(BASE_URL, '/'), $list_post10);?>"><?=$list_post10['title'];?></a></h4>
							</div>
							<ul class="entry-meta">
								<li><i class="icon-calendar3"></i> <?=$this->pocore()->call->podatetime->tgl_indo($list_post10['date']);?></li>
								<li><a href="<?=$this->pocore()->call->postring->permalink(rtrim(BASE_URL, '/'), $list_post10);?>"><i class="icon-comments"></i> <?=$this->post()->getCountComment($list_post10['id_post']);?></a></li>
							</ul>
						</div>
					</div>
				<?php } ?>
				</div>
			</div>
		</div>

		<div class="col-md-4">
			<!-- Insert Sidebar -->
			<?=$this->insert('sidebar');?>
		</div>
	</div>
</div>