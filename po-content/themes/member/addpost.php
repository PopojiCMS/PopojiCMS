<?=$this->layout('index');?>

<main class="cd-main-content">
	<div class="container-fluid">
		<div class="container member-page">
			<div class="row">
				<div class="col-md-12">
					<div class="page-header">
						<h3><i class="fa fa-plus"></i> <?=$this->e($front_member_addpost);?></h3>
					</div>
					<div class="member-content">
						<?=htmlspecialchars_decode($this->e($alertmsg));?>
						<div class="row">
							<form method="post" action="<?=BASE_URL;?>/member/post/addnew" enctype="multipart/form-data" autocomplete="off">
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
													<div class="form-group">
														<label><?=$this->e($post_title_2);?> <span class="text-danger">*</span></label>
														<input class="form-control" type="text" id="title-<?=$lang['id_language'];?>" name="post[<?=$lang['id_language'];?>][title]" required />
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
														<textarea class="form-control" id="po-wysiwyg-<?=$lang['id_language'];?>" name="post[<?=$lang['id_language'];?>][content]" style="height:450px;"></textarea>
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
												<input class="form-control" type="text" id="seotitle" name="seotitle" required />
												<span class="help-block text-danger"><small><i>Permalink : <?=BASE_URL;?>/detailpost/<span id="permalink"></span></i></small></span>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<label for="id_category"><?=$this->e($post_category);?> <span class="text-danger">*</span></label>
												<div class="box-category">
													<?=$this->post()->generate_checkbox(0, 'add');?>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<label><?=$this->e($post_tag);?></label>
												<input class="form-control" type="text" id="tag" name="tag" />
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
												<textarea class="form-control mceNoEditor" id="picture_description" name="picture_description" rows="3" cols=""></textarea>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label><?=$this->e($post_date);?> <span class="text-danger">*</span></label>
												<input class="form-control" type="text" id="publishdate" name="publishdate" value="<?=date('Y-m-d');?>" required />
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label><?=$this->e($post_time);?> <span class="text-danger">*</span></label>
												<input class="form-control" type="text" id="publishtime" name="publishtime" value="<?=date('h:i:s');?>" required />
											</div>
										</div>
									</div>
								</div>
								<div class="clearfix"></div>
								<div class="col-md-12">
									<div class="action-border">&nbsp;</div>
									<button class="btn btn-success" type="submit" name="submit"><i class="fa fa-plus"></i> <?=$this->e($front_member_addpost);?></button>
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