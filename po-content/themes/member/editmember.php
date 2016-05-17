<?=$this->layout('index');?>

<main class="cd-main-content">
	<div class="container-fluid">
		<div class="container member-page">
			<div class="row">
				<div class="col-md-8 col-md-offset-2">
					<div class="page-header">
						<h3><i class="fa fa-user"></i> <?=$this->e($front_member_edit_account);?></h3>
					</div>
					<div class="member-content">
						<?=htmlspecialchars_decode($this->e($alertmsg));?>
						<form method="post" action="<?=BASE_URL;?>/member/user/edit" enctype="multipart/form-data">
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label><?=$this->e($user_name);?> <span class="text-danger">**</span></label>
										<input class="form-control" type="text" id="username" name="username" value="<?=$user['username'];?>" disabled />
										<div class="help-block text-danger"><small><i>** <?=$this->e($user_name_note);?></i></small></div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label><?=$this->e($user_fullname);?> <span class="text-danger">*</span></label>
										<input class="form-control" type="text" id="nama_lengkap" name="nama_lengkap" value="<?=$user['nama_lengkap'];?>" required />
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label><?=$this->e($user_email);?> <span class="text-danger">*</span></label>
										<input class="form-control" type="text" id="email" name="email" value="<?=$user['email'];?>" required />
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label><?=$this->e($user_phone_number);?> <span class="text-danger">*</span></label>
										<input class="form-control" type="text" id="no_telp" name="no_telp" value="<?=$user['no_telp'];?>" required />
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label><?=$this->e($user_picture);?></label>
										<div class="input-group">
											<input class="form-control" type="file" name="picture" class="filestyle" data-buttonText="<?=$this->e($action_7);?>" />
										</div>
										<span class="help-block text-danger"><small><i><?=$this->e($user_picture_note);?></i></small></span>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label><?=$this->e($user_bio);?></label>
								<textarea class="form-control mceNoEditor" rows="3" cols="" id="bio" name="bio"><?=html_entity_decode($user['bio']);?></textarea>
								<span class="help-block text-danger text-right"><small><i><?=$this->e($user_bio_note);?></i></small></span>
							</div>
							<div class="action-border">&nbsp;</div>
							<button class="btn btn-success" type="submit" name="submit"><i class="fa fa-user"></i> <?=$this->e($front_member_edit_account);?></button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>