<footer id="footer" class="dark">
	<div class="container">
		<div class="footer-widgets-wrap clearfix">
			<div class="col_two_third">
				<div class="col_one_third">
					<div class="widget clearfix">
						<div style="background: url('<?=$this->asset('/images/world-map.png', false)?>') no-repeat center center; background-size: 100%;">
							<address>
								<?=htmlspecialchars_decode($this->pocore()->call->posetting[8]['value']);?>
							</address>
							<abbr title="Phone Number"><strong>Phone:</strong></abbr> <?=$this->pocore()->call->posetting[6]['value'];?><br>
							<abbr title="Fax"><strong>Fax:</strong></abbr> <?=$this->pocore()->call->posetting[7]['value'];?><br>
							<abbr title="Email Address"><strong>Email:</strong></abbr> <?=$this->pocore()->call->posetting[5]['value'];?>
						</div>
					</div>
				</div>
				<div class="col_one_third">
					<div class="widget widget_links clearfix">
						<h4><?=$this->e($front_quick_link);?></h4>
						<ul>
							<li><a href="<?=BASE_URL;?>/category/indonesiaku"><?=$this->e($front_indonesia);?></a></li>
							<li><a href="<?=BASE_URL;?>/category/motivasi"><?=$this->e($front_motivation);?></a></li>
							<li><a href="<?=BASE_URL;?>/category/hubungan"><?=$this->e($front_relationship);?></a></li>
							<li><a href="<?=BASE_URL;?>/category/sukses"><?=$this->e($front_success);?></a></li>
							<li><a href="<?=BASE_URL;?>/album"><?=$this->e($front_gallery);?></a></li>
							<li><a href="<?=BASE_URL;?>/contact"><?=$this->e($front_contact);?></a></li>
						</ul>
					</div>
				</div>
				<div class="col_one_third col_last">
					<div class="widget clearfix">
						<h4><?=$this->e($front_popular);?></h4>
						<div id="post-list-footer">
						<?php
							$populars = $this->post()->getPopular('2', 'DESC', WEB_LANG_ID);
							foreach($populars as $popular){
						?>
							<div class="spost clearfix">
								<div class="entry-c">
									<div class="entry-title">
										<h4><a href="<?=BASE_URL;?>/detailpost/<?=$popular['seotitle'];?>"><?=$popular['title'];?></a></h4>
									</div>
									<ul class="entry-meta">
										<li><?=$this->pocore()->call->podatetime->tgl_indo($popular['date']);?></li>
									</ul>
								</div>
							</div>
						<?php } ?>
						</div>
					</div>
				</div>
			</div>
			<div class="col_one_third col_last">
				<div class="widget subscribe-widget clearfix">
					<h5><strong><?=$this->e($front_subscribe);?></strong> <?=$this->e($front_txt_subscribe);?>:</h5>
					<form action="<?=BASE_URL;?>/subscribe" role="form" method="post" class="nobottommargin">
						<div class="input-group divcenter">
							<span class="input-group-addon"><i class="icon-email2"></i></span>
							<input type="email" name="email" class="form-control" placeholder="<?=$this->e($front_email);?>">
							<span class="input-group-btn">
								<button class="btn btn-success" type="submit"><?=$this->e($front_subscribe);?></button>
							</span>
						</div>
					</form>
				</div>
				<div class="widget clearfix" style="margin-bottom: -20px;">
					<div class="row">
						<div class="col-md-6 clearfix bottommargin-sm">
							<a href="#" class="social-icon si-dark si-colored si-facebook nobottommargin" style="margin-right: 10px;">
								<i class="icon-facebook"></i>
								<i class="icon-facebook"></i>
							</a>
							<a href="#"><small style="display: block; margin-top: 3px;"><strong>Like us</strong><br>on Facebook</small></a>
						</div>
						<div class="col-md-6 clearfix">
							<a href="#" class="social-icon si-dark si-colored si-rss nobottommargin" style="margin-right: 10px;">
								<i class="icon-rss"></i>
								<i class="icon-rss"></i>
							</a>
							<a href="#"><small style="display: block; margin-top: 3px;"><strong>Subscribe</strong><br>to RSS Feeds</small></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div id="copyrights">
		<div class="container clearfix">
			<div class="col_half">
				Copyrights &copy; <?=date('Y');?> All Rights Reserved by <?=$this->pocore()->call->posetting[0]['value'];?><br>
				<div class="copyright-links"><a href="#">Terms of Use</a> / <a href="#">Privacy Policy</a></div>
			</div>
			<div class="col_half col_last tright">
				<div class="fright clearfix">
					<a href="#" class="social-icon si-small si-borderless si-facebook">
						<i class="icon-facebook"></i>
						<i class="icon-facebook"></i>
					</a>
					<a href="#" class="social-icon si-small si-borderless si-twitter">
						<i class="icon-twitter"></i>
						<i class="icon-twitter"></i>
					</a>
					<a href="#" class="social-icon si-small si-borderless si-gplus">
						<i class="icon-gplus"></i>
						<i class="icon-gplus"></i>
					</a>
				</div>
				<div class="clear"></div>
				<i class="icon-envelope2"></i> <?=$this->pocore()->call->posetting[5]['value'];?> <span class="middot">&middot;</span> <i class="icon-headphones"></i> <?=$this->pocore()->call->posetting[6]['value'];?>
			</div>
		</div>
	</div>
</footer>