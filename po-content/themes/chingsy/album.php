<?=$this->layout('index');?>

<section id="page-title" class="page-title-mini" style="margin-top:-80px;">
	<div class="container clearfix">
		<ol class="breadcrumb">
			<li><a href="<?=BASE_URL;?>"><?=$this->e($front_home);?></a></li>
			<li><a href="<?=BASE_URL.'/album';?>"><?=$this->e($front_gallery);?></a></li>
		</ol>
	</div>
</section>

<section id="content">
	<div class="content-wrap">
		<div class="container clearfix">
			<div id="portfolio" class="portfolio-masonry clearfix">
			<?php
				$albums = $this->gallery()->getAlbum('8', 'id_album ASC', $this->e($page));
				foreach($albums as $alb){
			?>
				<article class="portfolio-item">
					<div class="portfolio-image">
						<a href="<?=BASE_URL.'/gallery/'.$this->e($alb['seotitle']);?>">
							<img src="<?=BASE_URL.'/'.DIR_CON.'/uploads/medium/medium_'.$alb['picture'];?>" alt="<?=$alb['title'];?>">
						</a>
						<div class="portfolio-overlay">
							<a href="<?=BASE_URL.'/'.DIR_CON.'/uploads/'.$alb['picture'];?>" class="left-icon" data-lightbox="image"><i class="icon-line-plus"></i></a>
							<a href="<?=BASE_URL.'/gallery/'.$this->e($alb['seotitle']);?>" class="right-icon"><i class="icon-line-ellipsis"></i></a>
						</div>
					</div>
					<div class="portfolio-desc">
						<h3><a href="<?=BASE_URL.'/gallery/'.$this->e($alb['seotitle']);?>"><?=$alb['title'];?></a></h3>
					</div>
				</article>
			<?php } ?>
			</div>
			<div class="col-md-12 text-center" style="margin-top:30px;">
				<ul class="pagination nobottommargin">
					<?=$this->gallery()->getAlbumPaging('8', $this->e($page), '1', $this->e($front_paging_prev), $this->e($front_paging_next));?>
				</ul>
			</div>
			<script type="text/javascript">
				jQuery(window).load(function(){
					var $container = $('#portfolio');
					$container.isotope({ transitionDuration: '0.65s' });
					$(window).resize(function() {
						$container.isotope('layout');
					});
				});
			</script>
		</div>
	</div>
</section>