<?php
/*
 *
 * - PopojiCMS Admin File
 *
 * - File : admin_home.php
 * - Version : 1.0
 * - Author : Jenuar Dalapang
 * - License : MIT License
 *
 *
 * Ini adalah file php yang di gunakan untuk menangani proses admin pada halaman home.
 * This is a php file for handling admin process for home page.
 *
*/

/**
 * Fungsi ini digunakan untuk mencegah file ini diakses langsung tanpa melalui router.
 *
 * This function use for prevent this file accessed directly without going through a router.
 *
*/
if (!defined('CONF_STRUCTURE')) {
	header('location:index.html');
	exit;
}

/**
 * Fungsi ini digunakan untuk mencegah file ini diakses langsung tanpa login akses terlebih dahulu.
 *
 * This function use for prevent this file accessed directly without access login first.
 *
*/
if (empty($_SESSION['namauser']) AND empty($_SESSION['passuser']) AND $_SESSION['login'] == 0) {
	header('location:index.php');
	exit;
}

class Home extends PoCore
{

	function index()
	{
	?>
		<div class="row">
			<div class="col-md-12">
				<div class="widget">
					<div class="mini-stats">
						<p style="text-align:left;"><?=$GLOBALS['_']['dashboard'];?></p>
						<h3 style="text-align:left;"><?=$GLOBALS['_']['home_welcome'];?></h3>
					</div>
				</div>
			</div>
			<?php
				$notif_post = $this->podb->from('post')
					->select('users.level')
					->leftJoin('users ON users.id_user = post.editor')
					->where('post.active', 'N')
					->where('users.level', '4')
					->count();
				if ($notif_post > 0) {
			?>
			<div class="col-md-12">
				<div class="widget">
					<div class="mini-stats">
						<a href="admin.php?mod=post"><span class="bg-primary"><i class="fa fa-book"></i></span></a>
						<p style="text-align:left;"><?=$GLOBALS['_']['home_notif'];?></p>
						<h3 style="text-align:left;"><?=$GLOBALS['_']['home_notif_have'];?> <?=$notif_post;?> <?=$GLOBALS['_']['home_notif_post'];?> <?=$GLOBALS['_']['home_notif_new'];?> <?=$GLOBALS['_']['home_notif_from_member'];?></h3>
					</div>
				</div>
			</div>
			<?php } ?>
			<?php
				$notif_comment = $this->podb->from('comment')
					->where('status', 'N')
					->count();
				if ($notif_comment > 0) {
			?>
			<div class="col-md-12">
				<div class="widget">
					<div class="mini-stats">
						<a href="admin.php?mod=comment"><span class="bg-primary"><i class="fa fa-comments"></i></span></a>
						<p style="text-align:left;"><?=$GLOBALS['_']['home_notif'];?></p>
						<h3 style="text-align:left;"><?=$GLOBALS['_']['home_notif_have'];?> <?=$notif_comment;?> <?=$GLOBALS['_']['home_notif_comment'];?> <?=$GLOBALS['_']['home_notif_new'];?></h3>
					</div>
				</div>
			</div>
			<?php } ?>
			<?php
				$notif_contact = $this->podb->from('contact')
					->where('status', 'N')
					->count();
				if ($notif_contact > 0) {
			?>
			<div class="col-md-12">
				<div class="widget">
					<div class="mini-stats">
						<a href="admin.php?mod=contact"><span class="bg-primary"><i class="fa fa-envelope-o"></i></span></a>
						<p style="text-align:left;"><?=$GLOBALS['_']['home_notif'];?></p>
						<h3 style="text-align:left;"><?=$GLOBALS['_']['home_notif_have'];?> <?=$notif_contact;?> <?=$GLOBALS['_']['home_notif_contact'];?> <?=$GLOBALS['_']['home_notif_new'];?></h3>
					</div>
				</div>
			</div>
			<?php } ?>
			<div class="col-md-3">
				<div class="widget">
					<div class="mini-stats">
						<a href="admin.php?mod=post"><span class="bg-info"><i class="fa fa-book"></i></span></a>
						<p><?=$GLOBALS['_']['post'];?></p>
						<h3><?=$this->podb->from('post')->count();?></h3>
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="widget">
					<div class="mini-stats">
						<a href="admin.php?mod=category"><span class="bg-danger"><i class="fa fa-tasks"></i></span></a>
						<p><?=$GLOBALS['_']['category'];?></p>
						<h3><?=$this->podb->from('category')->count();?></h3>
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="widget">
					<div class="mini-stats">
						<a href="admin.php?mod=tag"><span class="bg-success"><i class="fa fa-tags"></i></span></a>
						<p><?=$GLOBALS['_']['tag'];?></p>
						<h3><?=$this->podb->from('tag')->count();?></h3>
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="widget">
					<div class="mini-stats">
						<a href="admin.php?mod=comment"><span class="bg-warning"><i class="fa fa-comments"></i></span></a>
						<p><?=$GLOBALS['_']['comment'];?></p>
						<h3><?=$this->podb->from('comment')->count();?></h3>
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="widget">
					<div class="mini-stats">
						<a href="admin.php?mod=pages"><span class="bg-warning"><i class="fa fa-file"></i></span></a>
						<p><?=$GLOBALS['_']['pages'];?></p>
						<h3><?=$this->podb->from('pages')->count();?></h3>
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="widget">
					<div class="mini-stats">
						<a href="admin.php?mod=component"><span class="bg-success"><i class="fa fa-puzzle-piece"></i></span></a>
						<p><?=$GLOBALS['_']['component'];?></p>
						<h3><?=$this->podb->from('component')->count();?></h3>
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="widget">
					<div class="mini-stats">
						<a href="admin.php?mod=theme"><span class="bg-danger"><i class="fa fa-desktop"></i></span></a>
						<p><?=$GLOBALS['_']['theme'];?></p>
						<h3><?=$this->podb->from('theme')->count();?></h3>
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="widget">
					<div class="mini-stats">
						<a href="admin.php?mod=user"><span class="bg-info"><i class="fa fa-group"></i></span></a>
						<p><?=$GLOBALS['_']['user'];?></p>
						<h3><?=$this->podb->from('users')->count();?></h3>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-4">
				<div class="widget" style="padding-right:15px;">
					<div class="popular-post">
						<div class="widget-title">
							<h3 class="text-uppercase"><?=$GLOBALS['_']['home_popular'];?></h3>
							<span><?=$GLOBALS['_']['home_popular_desc'];?></span>
						</div>
					</div>
					<div class="popular-post-desc">
						<?php
							$post_pops = $this->podb->from('post')
								->select('post_description.title')
								->leftJoin('post_description ON post_description.id_post = post.id_post')
								->where('post_description.id_language', '1')
								->orderBy('post.hits DESC')
								->limit(5)
								->fetchAll();
							foreach($post_pops as $post_pop){
						?>
						<div class="media">
							<?php if ($post_pop['picture'] != '') { ?>
							<div class="media-left">
								<a href="<?=WEB_URL;?>detailpost/<?=$post_pop['seotitle'];?>" target="_blank">
									<img class="media-object" src="../po-content/thumbs/<?=$post_pop['picture'];?>" width="64" />
								</a>
							</div>
							<?php } ?>
							<div class="media-body">
								<span><?=date('d M Y', strtotime($post_pop['date']));?> - <?=$GLOBALS['_']['home_popular_view'];?> <?=$post_pop['hits'];?> <?=$GLOBALS['_']['home_popular_times'];?></span>
								<a href="<?=WEB_URL;?>detailpost/<?=$post_pop['seotitle'];?>" target="_blank"><?=$post_pop['title'];?></a>
							</div>
						</div>
						<?php } ?>
					</div>
				</div>
			</div>
			<div class="col-md-8">
				<div class="widget">
					<div class="stats">
						<div class="widget-title">
							<h3 class="text-uppercase"><?=$GLOBALS['_']['home_stats'];?></h3>
							<span><?=$GLOBALS['_']['home_stats_desc'];?></span>
							<div class="text-right"><span><i class="fa fa-circle" style="color:#DCDCDC;"></i> visitor</span> <span><i class="fa fa-circle" style="color:#97BBCD;"></i> hits</span></div>
						</div>
					</div>
					<div class="stats-desc">
						<canvas id="canvas-stats"></canvas>
					</div>
					<?php
						$visitor1 = $this->podb->from('traffic')->where('date', date('Y-m-d', strtotime('-6 days')))->groupBy('ip')->fetchAll();
						$visitor2 = $this->podb->from('traffic')->where('date', date('Y-m-d', strtotime('-5 days')))->groupBy('ip')->fetchAll();
						$visitor3 = $this->podb->from('traffic')->where('date', date('Y-m-d', strtotime('-4 days')))->groupBy('ip')->fetchAll();
						$visitor4 = $this->podb->from('traffic')->where('date', date('Y-m-d', strtotime('-3 days')))->groupBy('ip')->fetchAll();
						$visitor5 = $this->podb->from('traffic')->where('date', date('Y-m-d', strtotime('-2 days')))->groupBy('ip')->fetchAll();
						$visitor6 = $this->podb->from('traffic')->where('date', date('Y-m-d', strtotime('-1 days')))->groupBy('ip')->fetchAll();
						$visitor7 = $this->podb->from('traffic')->where('date', date('Y-m-d'))->groupBy('ip')->fetchAll();
						$hits1 = $this->podb->from('traffic')->select('SUM(hits) as hitstoday')->where('date', date('Y-m-d', strtotime('-6 days')))->groupBy('date')->fetch();
						$hits2 = $this->podb->from('traffic')->select('SUM(hits) as hitstoday')->where('date', date('Y-m-d', strtotime('-5 days')))->groupBy('date')->fetch();
						$hits3 = $this->podb->from('traffic')->select('SUM(hits) as hitstoday')->where('date', date('Y-m-d', strtotime('-4 days')))->groupBy('date')->fetch();
						$hits4 = $this->podb->from('traffic')->select('SUM(hits) as hitstoday')->where('date', date('Y-m-d', strtotime('-3 days')))->groupBy('date')->fetch();
						$hits5 = $this->podb->from('traffic')->select('SUM(hits) as hitstoday')->where('date', date('Y-m-d', strtotime('-2 days')))->groupBy('date')->fetch();
						$hits6 = $this->podb->from('traffic')->select('SUM(hits) as hitstoday')->where('date', date('Y-m-d', strtotime('-1 days')))->groupBy('date')->fetch();
						$hits7 = $this->podb->from('traffic')->select('SUM(hits) as hitstoday')->where('date', date('Y-m-d'))->groupBy('date')->fetch();
					?>
					<script type="text/javascript">
						var datastats = {
							labels: ["<?=date('d M', strtotime('-6 days'));?>", "<?=date('d M', strtotime('-5 days'));?>", "<?=date('d M', strtotime('-4 days'));?>", "<?=date('d M', strtotime('-3 days'));?>", "<?=date('d M', strtotime('-2 days'));?>", "<?=date('d M', strtotime('-1 days'));?>", "<?=date('d M', strtotime('0 days'));?>"],
							datasets: [
								{
									label: "Visitor",
									fillColor: "rgba(220,220,220,0.2)",
									strokeColor: "rgba(220,220,220,1)",
									pointColor: "rgba(220,220,220,1)",
									pointStrokeColor: "#fff",
									pointHighlightFill: "#fff",
									pointHighlightStroke: "rgba(220,220,220,1)",
									data: [
										<?=(empty($visitor1) ? '0' : count($visitor1));?>,
										<?=(empty($visitor2) ? '0' : count($visitor2));?>,
										<?=(empty($visitor3) ? '0' : count($visitor3));?>,
										<?=(empty($visitor4) ? '0' : count($visitor4));?>,
										<?=(empty($visitor5) ? '0' : count($visitor5));?>,
										<?=(empty($visitor6) ? '0' : count($visitor6));?>,
										<?=(empty($visitor7) ? '0' : count($visitor7));?>
									]
								},
								{
									label: "Hits",
									fillColor: "rgba(151,187,205,0.2)",
									strokeColor: "rgba(151,187,205,1)",
									pointColor: "rgba(151,187,205,1)",
									pointStrokeColor: "#fff",
									pointHighlightFill: "#fff",
									pointHighlightStroke: "rgba(151,187,205,1)",
									data: [
										<?=(empty($hits1['hitstoday']) ? '0' : $hits1['hitstoday']);?>,
										<?=(empty($hits2['hitstoday']) ? '0' : $hits2['hitstoday']);?>,
										<?=(empty($hits3['hitstoday']) ? '0' : $hits3['hitstoday']);?>,
										<?=(empty($hits4['hitstoday']) ? '0' : $hits4['hitstoday']);?>,
										<?=(empty($hits5['hitstoday']) ? '0' : $hits5['hitstoday']);?>,
										<?=(empty($hits6['hitstoday']) ? '0' : $hits6['hitstoday']);?>,
										<?=(empty($hits7['hitstoday']) ? '0' : $hits7['hitstoday']);?>
									]
								}
							]
						};
					</script>
				</div>
			</div>
		</div>
	<?php
	}

	function error()
	{
	?>
		<div class="row">
			<div class="col-lg-12 text-center">
				<h1 class="page-header">Page Not Found <small class="text-danger">Error 404</small></h1>
				<p>
					The page you requested could not be found, either contact your webmaster or try again.<br />
					Use your browsers <b>Back</b> button to navigate to the page you have previously<br />
					come from <b>or you could just press this neat little button :</b>
				</p>
				<a href="admin.php?mod=home" class="btn btn-sm btn-primary"><i class="fa fa-home"></i> Take Me Home</a>
			</div>
		</div>
	<?php
	}

	function logout()
	{
		session_destroy();
		header('location:index.php');
	}

}