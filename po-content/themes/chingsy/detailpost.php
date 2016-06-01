<?=$this->layout('index');?>

<section id="page-title" class="page-title-mini" style="margin-top:-80px;">
	<div class="container clearfix">
		<ol class="breadcrumb">
			<li><a href="<?=BASE_URL;?>"><?=$this->e($front_home);?></a></li>
			<li><?=$this->e($front_post_title);?></li>
			<li><a href="<?=$this->e($social_url);?>"><?=$this->e($page_title);?></a></li>
		</ol>
	</div>
</section>

<section id="content">
	<div class="content-wrap">
		<div class="container clearfix">
			<div class="col-md-8 nobottommargin clearfix">
				<div class="single-post nobottommargin">
					<div class="entry clearfix">
						<div class="entry-title"><h2><?=$post['title'];?></h2></div>

						<ul class="entry-meta clearfix">
							<li><i class="icon-calendar3"></i> <?=$this->pocore()->call->podatetime->tgl_indo($post['date']);?></li>
							<li><a href="javascript:void(0)"><i class="icon-user"></i> <?=$this->post()->getAuthorName($post['editor']);?></a></li>
							<li><i class="icon-folder-open"></i> <?=$this->post()->getPostTag($post['tag']);?></li>
							<li><i class="icon-eye"></i> <?=$post['hits'];?> <?=$this->e($front_hits);?></li>
							<li><a href="#comments"><i class="icon-comments"></i> <?=$this->post()->getCountComment($post['id_post']);?> <?=$this->e($front_comment);?></a></li>
						</ul>

						<div class="entry-image">
							<a href="<?=BASE_URL;?>/<?=DIR_CON;?>/uploads/<?=$post['picture'];?>"><img src="<?=BASE_URL;?>/<?=DIR_CON;?>/uploads/<?=$post['picture'];?>" alt="<?=$post['title'];?>"></a>
							<?php if ($post['picture_description'] != '') { ?>
							<p class="text-center" style="padding:10px; background:#eee;"><i><?=$post['picture_description'];?></i></p>
							<?php } ?>
						</div>

						<div class="entry-content notopmargin">
							<?=htmlspecialchars_decode(html_entity_decode($post['content']));?>
							<div class="tagcloud clearfix">
								<?=$this->post()->getPostTag($post['tag'], '');?>
							</div>
						</div>
					</div>

					<div class="post-navigation clearfix">
						<div class="col_half nobottommargin">
						<?php
							$prevpost = $this->post()->getPrevPost($post['id_post'], WEB_LANG_ID);
							if ($prevpost) {
						?>
							<a href="<?=BASE_URL;?>/detailpost/<?=$prevpost['seotitle'];?>">&lArr; <?=$prevpost['title'];?></a>
						<?php } ?>
						</div>

						<div class="col_half col_last tright nobottommargin">
						<?php
							$nextpost = $this->post()->getNextPost($post['id_post'], WEB_LANG_ID);
							if ($nextpost) {
						?>
							<a href="<?=BASE_URL;?>/detailpost/<?=$nextpost['seotitle'];?>"><?=$nextpost['title'];?> &rArr;</a>
						<?php } ?>
						</div>
					</div>

					<div class="line"></div>

					<div class="panel panel-default">
						<?php
							$editor = $this->post()->getAuthor($post['editor']);
							if ($editor['picture'] != '') {
								$editor_avatar = BASE_URL.'/'.DIR_CON.'/uploads/'.$editor['picture'];
							} else {
								$editor_avatar = BASE_URL.'/'.DIR_CON.'/uploads/user-editor.jpg';
							}
						?>
						<div class="panel-heading">
							<h3 class="panel-title"><?=$this->e($front_post_by);?> <span><a href="javscript:void(0)"><?=$editor['nama_lengkap'];?></a></span></h3>
						</div>
						<div class="panel-body">
							<div class="author-image">
								<img src="<?=$editor_avatar;?>" alt="" class="img-circle" width="300">
							</div>
							<?=htmlspecialchars_decode(html_entity_decode($editor['bio']));?>
						</div>
					</div>

					<div class="line"></div>

					<h4><?=$this->e($front_related_post);?></h4>
					<div class="related-posts clearfix">
						<?php
							$norelated = 1;
							$relateds = $this->post()->getRelated($post['id_post'], $post['tag'], '2', 'DESC', WEB_LANG_ID);
							foreach($relateds as $related){
						?>
						<?php if ($norelated%2 == 0) { ?>
						<div class="col_half nobottommargin col_last">
						<?php } else { ?>
						<div class="col_half nobottommargin">
						<?php } ?>
							<div class="mpost clearfix">
								<div class="entry-image hidden-xs">
									<a href="<?=BASE_URL;?>/detailpost/<?=$related['seotitle'];?>"><img src="<?=BASE_URL;?>/<?=DIR_CON;?>/uploads/medium/medium_<?=$related['picture'];?>" alt="<?=$related['title'];?>"></a>
								</div>
								<div class="entry-c">
									<div class="entry-title">
										<h4><a href="<?=BASE_URL;?>/detailpost/<?=$related['seotitle'];?>"><?=$this->pocore()->call->postring->cuthighlight('title', $related['title'], '30');?>...</a></h4>
									</div>
									<ul class="entry-meta clearfix">
										<li><i class="icon-calendar3"></i> <?=$this->pocore()->call->podatetime->tgl_indo($related['date']);?></li>
										<li><a href="<?=BASE_URL;?>/detailpost/<?=$related['seotitle'];?>#comments"><i class="icon-comments"></i> <?=$this->post()->getCountComment($related['id_post']);?></a></li>
									</ul>
									<div class="entry-content"><?=$this->pocore()->call->postring->cuthighlight('post', $related['content'], '70');?>...</div>
								</div>
							</div>
						</div>
						<?php $norelated++;} ?>
					</div>

					<?php if ($post['comment'] == 'Y') { ?>
					<div id="comments" class="clearfix">
						<?php if ($this->post()->getCountComment($post['id_post']) > 0) { ?>
						<h3 id="comments-title"><span><?=$this->post()->getCountComment($post['id_post']);?></span> <?=$this->e($front_comment);?></h3>
						<?php
							$com_parent = $this->post()->getCommentByPost($post['id_post'], '6', 'DESC', $this->e($page));
							$com_template = array(
								'parent_tag_open' => '<li class="comment" id="li-comment-{$comment_id}">',
								'parent_tag_close' => '</li>',
								'child_tag_open' => '<ul class="children">',
								'child_tag_close' => '</ul>',
								'comment_list' => '
									<div id="comment-{$comment_id}" class="comment-wrap clearfix">
										<div class="comment-meta">
											<div class="comment-author vcard">
												<span class="comment-avatar clearfix">
													<img alt="" src="{$comment_avatar}" class="avatar avatar-40 photo" height="40" width="40" />
												</span>
											</div>
										</div>
										<div class="comment-content clearfix">
											<div class="comment-author">
												<a href="{$comment_url}" rel="external nofollow" class="url">{$comment_name}</a>
												<span><a href="javascript:void(0)" title="Permalink to this comment">{$comment_datetime}</a></span>
											</div>
											<p>{$comment_content}</p>
											<a class="comment-reply-link" id="{$comment_id}" href="#respond" title="'.$this->e($comment_reply).'"><i class="icon-reply"></i></a>
										</div>
									<div class="clear"></div>
									</div>
								'
							);
						?>
						<ol class="commentlist clearfix">
							<?=$this->post()->generateComment($com_parent, 'DESC', $com_template);?>
						</ol>

						<div class="col-md-12 text-center" style="margin-bottom:40px;">
							<ul class="pagination nobottommargin">
								<?=$this->post()->getCommentPaging('6', $post['id_post'], $post['seotitle'], $this->e($page), '1', $this->e($front_paging_prev), $this->e($front_paging_next));?>
							</ul>
						</div>

						<script type='text/javascript'>  
							$(function(){  
								$("a.comment-reply-link").click(function() {
									var id = $(this).attr("id");
									$("#id_parent").val(id);
								});
								return true;
							});
						</script>  

						<div class="clear"></div>
						<?php } ?>

						<div id="respond" class="clearfix">
							<h3><?=$this->e($front_leave_comment);?></h3>
							<?=$this->pocore()->call->poflash->display();?>
							<form class="clearfix" action="<?=BASE_URL;?>/detailpost/<?=$post['seotitle'];?>#comments" method="post" id="commentform">
								<input type="hidden" name="id_parent" id="id_parent" value="0" />
								<input type="hidden" name="id" name="id" value="<?=$post['id_post'];?>" />
								<input type="hidden" name="seotitle" id="seotitle" value="<?=$post['seotitle'];?>" />
								<div class="col_one_third">
									<label for="name"><?=$this->e($comment_name);?> <small>*</small></label>
									<input type="text" name="name" id="name" value="<?=(isset($_POST['name']) ? $_POST['name'] : '');?>" size="22" tabindex="1" class="sm-form-control required" required />
								</div>
								<div class="col_one_third">
									<label for="email"><?=$this->e($comment_email);?> <small>*</small></label>
									<input type="text" name="email" id="email" value="<?=(isset($_POST['email']) ? $_POST['email'] : '');?>" size="22" tabindex="2" class="sm-form-control required" required />
								</div>
								<div class="col_one_third col_last">
									<label for="url"><?=$this->e($comment_website);?></label>
									<input type="text" name="url" id="url" value="<?=(isset($_POST['url']) ? $_POST['url'] : '');?>" size="22" tabindex="3" class="sm-form-control" />
								</div>
								<div class="clear"></div>
								<div class="col_full">
									<label for="comment"><?=$this->e($comment_text);?> <small>*</small></label>
									<textarea name="comment" cols="58" rows="7" tabindex="4" class="sm-form-control required" required><?=(isset($_POST['comment']) ? $_POST['comment'] : '');?></textarea>
								</div>
								<div class="clear"></div>
								<div class="col_full">
									<div class="g-recaptcha" data-sitekey="<?=$this->pocore()->call->posetting[21]['value'];?>"></div>
								</div>
								<div class="clear"></div>
								<div class="col_full nobottommargin">
									<button name="submit" type="submit" id="submit-button" tabindex="5" value="Submit" class="button button-3d nomargin"><?=$this->e($comment_submit);?></button>
								</div>
							</form>
							<script type="text/javascript">
								$("#commentform").validate();
							</script>
						</div>
					</div>
					<?php } ?>
				</div>
			</div>
			<div class="col-md-4 nobottommargin clearfix">
				<!-- Insert Sidebar -->
				<?=$this->insert('sidebar');?>
			</div>
		</div>
	</div>
</section>