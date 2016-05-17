<?=$this->layout('index');?>

<section id="page-title" class="page-title-mini" style="margin-top:-80px;">
	<div class="container clearfix">
		<ol class="breadcrumb">
			<li><a href="<?=BASE_URL;?>"><?=$this->e($front_home);?></a></li>
			<li><a href="<?=BASE_URL.'/pages/'.$this->e($pages['seotitle']);?>"><?=$this->e($front_pages);?></a></li>
			<li class="active"><?=$this->e($pages['title']);?></li>
		</ol>
	</div>
</section>

<section id="content">
	<div class="content-wrap">
		<div class="container clearfix">
			<div class="col_full">
				<div class="heading-block center nobottomborder">
					<h2><?=$this->e($pages['title']);?></h2>
				</div>
				<?php if ($this->e($pages['picture']) != '') { ?>
				<div class="col-md-12 text-center" style="margin-bottom:30px;">
					<img src="<?=BASE_URL.'/'.DIR_CON.'/uploads/'.$this->e($pages['picture']);?>" alt="" />
				</div>
				<?php } ?>
				<div class="col-md-12">
					<?=htmlspecialchars_decode(html_entity_decode($this->e($pages['content'])));?>
				</div>
			</div>
		</div>
	</div>
</section>