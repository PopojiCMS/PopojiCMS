<div id="top-bar">
	<div class="container clearfix">
		<div class="col_half nobottommargin">
			<div class="top-links">
				<ul>
					<li><a href="<?=BASE_URL;?>/pages/tentang-kami"><?=$this->e($front_about);?></a></li>
					<li><a href="<?=BASE_URL;?>/contact"><?=$this->e($front_contact);?></a></li>
					<li><a href="javascript:void(0)"><?=$this->e($front_select_lang);?></a>
						<div class="top-link-section">
							<form method="post" action="<?=BASE_URL;?>/./" role="form" style="margin-bottom:0px;">
								<input type="hidden" name="refer" value="<?=((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] && !in_array(strtolower($_SERVER['HTTPS']),array('off','no'))) ? 'https' : 'http').'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];?>" />
								<div class="form-group">
									<select class="form-control" name="lang" required="">
									<?php
										$languages = $this->language()->getLanguage('ASC');
										foreach($languages as $language){
											echo "<option value='".$language['code']."' ".($language['code'] == WEB_LANG ? 'selected' : '').">".$language['title']."</option>";
										}
									?>
									</select>
								</div>
								<button class="btn btn-danger btn-block" type="submit"><?=$this->e($front_change_lang);?></button>
							</form>
						</div>
					</li>
					<?php if ($this->pocore()->call->posetting[17]['value'] == 'Y') { ?>
						<?php if (empty($_SESSION['namauser_member']) AND empty($_SESSION['passuser_member']) AND empty($_SESSION['login_member'])) { ?>
						<li><a href="<?=BASE_URL;?>/member/login"><?=$this->e($front_member_login);?></a></li>
						<li><a href="<?=BASE_URL;?>/member/register"><?=$this->e($front_member_register);?></a></li>
						<?php } else { ?>
						<li><a href="<?=BASE_URL;?>/member"><?=$_SESSION['namauser_member'];?></a></li>
						<?php } ?>
					<?php } ?>
				</ul>
			</div>
		</div>

		<div class="col_half fright col_last nobottommargin">
			<div id="top-social">
				<ul>
					<li><a href="javascript:void(0)" class="si-facebook"><span class="ts-icon"><i class="icon-facebook"></i></span><span class="ts-text">Facebook</span></a></li>
					<li><a href="javascript:void(0)" class="si-twitter"><span class="ts-icon"><i class="icon-twitter"></i></span><span class="ts-text">Twitter</span></a></li>
					<li><a href="javascript:void(0)" class="si-instagram"><span class="ts-icon"><i class="icon-instagram2"></i></span><span class="ts-text">Instagram</span></a></li>
					<li><a href="tel:+62 000 0000 0000" class="si-call"><span class="ts-icon"><i class="icon-call"></i></span><span class="ts-text"><?=$this->pocore()->call->posetting[6]['value'];?></span></a></li>
					<li><a href="mailto:<?=$this->pocore()->call->posetting[5]['value'];?>" class="si-email3"><span class="ts-icon"><i class="icon-email3"></i></span><span class="ts-text"><?=$this->pocore()->call->posetting[5]['value'];?></span></a></li>
				</ul>
			</div>
		</div>
	</div>
</div>

<header id="header" class="sticky-style-2">
	<div class="container clearfix">
		<div id="logo">
			<a href="<?=BASE_URL;?>" class="standard-logo" data-dark-logo="<?=BASE_URL.'/'.DIR_INC;?>/images/logo.png"><img src="<?=BASE_URL.'/'.DIR_INC;?>/images/logo.png" alt="Logo" /></a>
			<a href="<?=BASE_URL;?>" class="retina-logo" data-dark-logo="<?=BASE_URL.'/'.DIR_INC;?>/images/logo.png"><img src="<?=BASE_URL.'/'.DIR_INC;?>/images/logo.png" alt="Logo" /></a>
		</div>
		<div class="top-advert">
			<img src="<?=BASE_URL;?>/<?=DIR_CON;?>/uploads/ad-long.jpg" alt="">
		</div>
	</div>

	<div id="header-wrap">
		<nav id="primary-menu" class="style-2">
			<div class="container clearfix">
				<div id="primary-menu-trigger"><i class="icon-reorder"></i></div>
				<?php
					echo $this->menu()->getFrontMenu(WEB_LANG, '', '', '');
				?>
				<div id="top-search">
					<a href="javascript:void(0)" id="top-search-trigger"><i class="icon-search3"></i><i class="icon-line-cross"></i></a>
					<form action="<?=BASE_URL;?>/search" method="post">
						<input type="text" name="search" class="form-control" value="" placeholder="<?=$this->e($front_search);?>...">
					</form>
				</div>
			</div>
		</nav>
	</div>
</header>