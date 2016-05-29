<?=$this->layout('index');?>

<main class="cd-main-content">
	<div class="container-fluid">
		<div class="container member-page">
			<div class="row">
				<div class="col-md-12">
					<div class="page-header">
						<h3><i class="fa fa-plus"></i> <?=$this->e($front_member_editpost);?></h3>
					</div>
					<div class="member-content">
						<?=htmlspecialchars_decode($this->e($alertmsg));?>
						<div class="row">
							<form method="post" action="<?=BASE_URL;?>/member/post/edit/<?=$this->e($post['id_post']);?>" enctype="multipart/form-data" autocomplete="off">
								<div class="col-md-8">
									<div class="row">
										<div class="col-md-12">
											<?php
												$notab = 1;
												$noctab = 1;
												$langs = $this->language()->getLanguage('ASC');
											?>
											<ul class="nav nav-tabs">
												<?php foreach($langs as $lang) { ?>
												<li <?php echo ($notab == '1' ? 'class="active"' : ''); ?>><a href="#tab-content-<?=$lang['id_language'];?>" data-toggle="tab"><img src="<?=BASE_URL;?>/<?=DIR_INC;?>/images/flag/<?=$lang['code'];?>.png" /> <?=$lang['title'];?></a></li>
												<?php $notab++;} ?>
											</ul>
											<div class="tab-content">
												<?php foreach($langs as $lang) { ?>
												<div class="tab-pane <?php echo ($noctab == '1' ? 'active' : ''); ?>" id="tab-content-<?=$lang['id_language'];?>" style="margin-top:15px;">
													<?php
														$paglang = $this->pocore()->call->podb->from('post_description')
															->where('post_description.id_post', $this->e($post['id_post']))
															->where('post_description.id_language', $lang['id_language'])
															->fetch();
															$content_before = html_entity_decode($paglang['content']);
															$content_after = preg_replace_callback(
																'/(?:\<code*\>([^\<]*)\<\/code\>)/',
																create_function(
																   '$matches',
																	'return \'<code>\'.stripslashes(htmlspecialchars($matches[1],ENT_QUOTES)).\'</code>\';'
																),
																$content_before
															);
													?>
													<input type="hidden" name="post[<?=$lang['id_language'];?>][id]" value="<?=$paglang['id_post_description'];?>" />
													<div class="form-group">
														<label><?=$this->e($post_title_2);?> <span class="text-danger">*</span></label>
														<input class="form-control" type="text" id="title-<?=$lang['id_language'];?>" name="post[<?=$lang['id_language'];?>][title]" value="<?=$paglang['title'];?>" required />
													</div>
													<div class="form-group">
														<label><?=$this->e($post_content);?> <span class="text-danger">*</span></label>
														<div class="row" style="margin-top:-30px;">
															<div class="col-md-12">
																<div class="pull-right">
																	<div class="input-group">
																		<span class="btn-group">
																			<a class="btn btn-sm btn-default tiny-visual" data-lang="<?=$lang['id_language'];?>">Visual</a>
																			<a class="btn btn-sm btn-success tiny-text" data-lang="<?=$lang['id_language'];?>">Text</a>
																		</span>
																	</div>
																</div>
															</div>
														</div>
														<textarea class="form-control" id="po-wysiwyg-<?=$lang['id_language'];?>" name="post[<?=$lang['id_language'];?>][content]" style="height:450px;"><?=$content_after;?></textarea>
													</div>
												</div>
												<?php $noctab++;} ?>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<label><?=$this->e($post_seotitle);?> <span class="text-danger">*</span></label>
												<input class="form-control" type="text" id="seotitle" name="seotitle" value="<?=$this->e($post['seotitle']);?>" required />
												<span class="help-block text-danger"><small><i>Permalink : <?=BASE_URL;?>/detailpost/<span id="permalink"><?=$this->e($post['seotitle']);?></span></i></small></span>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<label for="id_category"><?=$this->e($post_category);?> <span class="text-danger">*</span></label>
												<div class="box-category">
													<?=$this->post()->generate_checkbox(0, 'update', $this->e($post['id_post']));?>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<label><?=$this->e($post_tag);?></label>
												<input class="form-control" type="text" id="tag" name="tag" value="<?=$this->e($post['tag']);?>" />
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<div class="form-group" id="image-box">
												<div class="row">
													<?php if ($this->e($post['picture']) == '') { ?>
														<div class="col-md-12"><label><?=$this->e($post_picture_2);?></label></div>
														<div class="col-md-12">
															<a href="data:image/gif;base64,R0lGODdhyACWAOMAAO/v76qqqubm5t3d3bu7u7KystXV1cPDw8zMzAAAAAAAAAAAAAAAAAAAAAAAAAAAACwAAAAAyACWAAAE/hDISau9OOvNu/9gKI5kaZ5oqq5s675wLM90bd94ru987//AoHBILBqPyKRyyWw6n9CodEqtWq/YrHbL7Xq/4LB4TC6bz+i0es1uu9/wuHxOr9vv+Lx+z+/7/4CBgoOEhYaHiImKi4yNjo+QkZKTlJWWl5iZmpucnZ6foKGio6SlpqeoqaqrrK2ur7CxsrO0tba3TAMFBQO4LAUBAQW+K8DCxCoGu73IzSUCwQECAwQBBAIVCMAFCBrRxwDQwQLKvOHV1xbUwQfYEwIHwO3BBBTawu2BA9HGwcMT1b7Vw/Dt3z563xAIrHCQnzsAAf0F6ybhwDdwgAx8OxDQgASN/sKUBWNmwQDIfwBAThRoMYDHCRYJGAhI8eRMf+4OFrgZgCKgaB4PHqg4EoBQbxgBROtlrJu4ofYm0JMQkJk/mOMkTA10Vas1CcakJrXQ1eu/sF4HWhB3NphYlNsmxOWKsWtZtASTdsVb1mhEu3UDX3RLFyVguITzolQKji/GhgXNvhU7OICgsoflJr7Qd2/isgEPGGAruTTjnSZTXw7c1rJpznobf2Y9GYBjxIsJYQbXstfRDJ1luz6t2TDvosSJSpMw4GXG3TtT+hPpEoPJ6R89B7AaUrnolgWwnUQQEKVOAy199mlonPDfr3m/GeUHFjBhAf0SUh28+P12QOIIgDbcPdwgJV+Arf0jnwTwsHOQT/Hs1BcABObjDAcTXhiCOGppKAJI6nnIwQGiKZSViB2YqB+KHtxjjXMsxijjjDTWaOONOOao44489ujjj0AGKeSQRBZp5JFIJqnkkkw26eSTUEYp5ZRUVmnllVhmqeWWXHbp5ZdghinmmGSW6UsEADs=" target="_blank"><?=$this->e($post_picture_3);?></a>
															<p><i><?=$this->e($post_picture_4);?></i></p>
														</div>
													<?php } else { ?>
														<div class="col-md-12"><label><?=$this->e($post_picture_5);?></label></div>
														<div class="col-md-12">
															<a href="<?=BASE_URL;?>/po-content/uploads/<?=$this->e($post['picture']);?>" target="_blank"><?=$this->e($post_picture_6);?></a>
															<p><i><?=$this->e($post_picture_4);?></i></p>
														</div>
													<?php } ?>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<label><?=$this->e($post_picture);?></label>
												<div class="input-group">
													<input class="form-control" type="file" name="picture" class="filestyle" data-buttonText="<?=$this->e($action_7);?>" />
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<label><?=$this->e($post_picture_description);?></label>
												<textarea class="form-control mceNoEditor" id="picture_description" name="picture_description" rows="3" cols=""><?=$this->e($post['picture_description']);?></textarea>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label><?=$this->e($post_date);?> <span class="text-danger">*</span></label>
												<input class="form-control" type="text" id="publishdate" name="publishdate" value="<?=$this->e($post['date']);?>" required />
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label><?=$this->e($post_time);?> <span class="text-danger">*</span></label>
												<input class="form-control" type="text" id="publishtime" name="publishtime" value="<?=$this->e($post['time']);?>" required />
											</div>
										</div>
									</div>
								</div>
								<div class="clearfix"></div>
								<div class="col-md-12">
									<div class="action-border">&nbsp;</div>
									<button class="btn btn-success" type="submit" name="submit"><i class="fa fa-check"></i> <?=$this->e($front_member_editpost);?></button>
									<a href="<?=BASE_URL;?>/member/post" class="btn btn-default pull-right"><?=$this->e($front_member_back);?> <i class="fa fa-angle-right" style="margin-left:10px;"></i></a>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>