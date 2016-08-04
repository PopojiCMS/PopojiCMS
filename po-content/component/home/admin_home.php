<?php
/*
 *
 * - PopojiCMS Admin File
 *
 * - File : admin_home.php
 * - Version : 1.1
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

	/**
	 * Fungsi ini digunakan untuk menampilkan halaman index home.
	 *
	 * This function use for index home page.
	 *
	*/
	public function index()
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
								<a href="<?=$this->postring->permalink(rtrim(WEB_URL, '/'), $post_pop);?>" target="_blank">
									<img class="media-object" src="../po-content/thumbs/<?=$post_pop['picture'];?>" width="64" />
								</a>
							</div>
							<?php } ?>
							<div class="media-body">
								<span><?=date('d M Y', strtotime($post_pop['date']));?> - <?=$GLOBALS['_']['home_popular_view'];?> <?=$post_pop['hits'];?> <?=$GLOBALS['_']['home_popular_times'];?></span>
								<a href="<?=$this->postring->permalink(rtrim(WEB_URL, '/'), $post_pop);?>" target="_blank"><?=$post_pop['title'];?></a>
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
						</div>
						<div class="pull-right" style="margin-top:-60px;">
							<span><i class="fa fa-circle" style="color:#DCDCDC;"></i> <?=$GLOBALS['_']['home_visitors'];?></span>
							<span><i class="fa fa-circle" style="color:#97BBCD;"></i> <?=$GLOBALS['_']['home_hits'];?></span>
							<a href="admin.php?mod=home&act=statistics" class="btn btn-sm btn-success" title="<?=$GLOBALS['_']['home_summary'];?>" style="padding:1px 3px; font-size:12px;"><i class="fa fa-line-chart"></i></a>
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

	/**
	 * Fungsi ini digunakan untuk menampilkan halaman statistic home.
	 *
	 * This function use for statistic home page.
	 *
	 * Added in v.2.0.1
	*/
	public function statistics()
	{
	?>
		<div class="block-content">
			<div class="row">
				<div class="col-md-12">
					<?=$this->pohtml->headTitle($GLOBALS['_']['home_stats']);?>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12 text-center">
					<form class="form-inline" method="get" action="admin.php" autocomplete="off">
						<input type="hidden" name="mod" value="home" />
						<input type="hidden" name="act" value="statistics" />
						<div class="form-group" style="border-bottom: none;">
							<input type="text" name="from" class="form-control" id="from_stat" value="<?=(isset($_GET['from']) ? $_GET['from'] : '');?>" placeholder="<?=$GLOBALS['_']['home_from_date'];?>" required />
						</div>
						<div class="form-group" style="border-bottom: none;">
							<input type="text" name="to" class="form-control" id="to_stat" value="<?=(isset($_GET['to']) ? $_GET['to'] : '');?>" placeholder="<?=$GLOBALS['_']['home_to_date'];?>" required />
						</div>
						<div class="form-group" style="border-bottom: none;">
							<button type="submit" class="btn btn-primary"><?=$GLOBALS['_']['action_5'];?></button>
						</div>
					</form>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="pull-right" style="margin-top:10px;">
						<span><i class="fa fa-circle" style="color:#DCDCDC;"></i> <?=$GLOBALS['_']['home_visitors'];?></span>
						<span><i class="fa fa-circle" style="color:#97BBCD;"></i> <?=$GLOBALS['_']['home_hits'];?></span>
					</div>
					<div class="stats-desc">
						<canvas id="canvas-stats"></canvas>
					</div>
					<?php if (isset($_GET['from']) && isset($_GET['to'])) { ?>
					<?php
						$label_stats = array();
						$visitor_stats = array();
						$hits_stats = array();
						$start_stats = $current_stats = strtotime($_GET['from']);
						$end_stats = strtotime($_GET['to']);
						while ($current_stats <= $end_stats) {
							$label_stats[] = date('d M', $current_stats);
							$visitor_stats[] = $this->podb->from('traffic')->where('date', date('Y-m-d', $current_stats))->groupBy('ip')->count();
							$hits_stats[] = $this->podb->from('traffic')->select('SUM(hits) as hitstoday')->where('date', date('Y-m-d', $current_stats))->groupBy('date')->fetch()['hitstoday'];
							$current_stats = strtotime('+1 days', $current_stats);
						}
					?>
					<script type="text/javascript">
						var datastats = {
							labels: <?=$this->js_array($label_stats);?>,
							datasets: [
								{
									label: "<?=$GLOBALS['_']['home_visitors'];?>",
									fillColor: "rgba(220,220,220,0.2)",
									strokeColor: "rgba(220,220,220,1)",
									pointColor: "rgba(220,220,220,1)",
									pointStrokeColor: "#fff",
									pointHighlightFill: "#fff",
									pointHighlightStroke: "rgba(220,220,220,1)",
									data: <?=$this->js_array($visitor_stats);?>
								},
								{
									label: "<?=$GLOBALS['_']['home_hits'];?>",
									fillColor: "rgba(151,187,205,0.2)",
									strokeColor: "rgba(151,187,205,1)",
									pointColor: "rgba(151,187,205,1)",
									pointStrokeColor: "#fff",
									pointHighlightFill: "#fff",
									pointHighlightStroke: "rgba(151,187,205,1)",
									data: <?=$this->js_array($hits_stats);?>
								}
							]
						};
					</script>
					<?php } else { ?>
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
									label: "<?=$GLOBALS['_']['home_visitors'];?>",
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
									label: "<?=$GLOBALS['_']['home_hits'];?>",
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
					<?php } ?>
				</div>
			</div>
			<div class="row">
				<div class="col-md-4 col-sm-4">
					<div class="widget">
						<div class="mini-stats">
							<a href="javascript:void(0)"><span class="bg-success"><i class="fa fa-eye"></i></span></a>
							<p><?=$GLOBALS['_']['home_online_today'];?></p>
							<?php $todaytime = time()-300; ?>
							<h3><?=$this->podb->from('traffic')->where('date', date('Y-m-d'))->where('online > "'.$todaytime.'"')->count();?></h3>
						</div>
					</div>
				</div>
				<div class="col-md-4 col-sm-4">
					<div class="widget">
						<div class="mini-stats">
							<a href="javascript:void(0)"><span class="bg-danger"><i class="fa fa-users"></i></span></a>
							<p><?=$GLOBALS['_']['home_visitors'];?></p>
							<?php if (isset($_GET['from']) && isset($_GET['to'])) { ?>
							<h3><?=(empty($this->podb->from('traffic')->where('date BETWEEN "'.$_GET['from'].'" AND "'.$_GET['to'].'"')->groupBy('ip')->count()) ? '0' : $this->podb->from('traffic')->where('date BETWEEN "'.$_GET['from'].'" AND "'.$_GET['to'].'"')->groupBy('ip')->count());?></h3>
							<?php } else { ?>
							<h3><?=(empty($this->podb->from('traffic')->where('date', date('Y-m-d'))->groupBy('ip')->fetchAll()) ? '0' : count($this->podb->from('traffic')->where('date', date('Y-m-d'))->groupBy('ip')->fetchAll()));?></h3>
							<?php } ?>
						</div>
					</div>
				</div>
				<div class="col-md-4 col-sm-4">
					<div class="widget">
						<div class="mini-stats">
							<a href="javascript:void(0)"><span class="bg-warning"><i class="fa fa-heart"></i></span></a>
							<p><?=$GLOBALS['_']['home_hits'];?></p>
							<?php if (isset($_GET['from']) && isset($_GET['to'])) { ?>
							<h3><?=(empty($this->podb->from('traffic')->select('SUM(hits) as hitstoday')->where('date BETWEEN "'.$_GET['from'].'" AND "'.$_GET['to'].'"')->fetch()['hitstoday']) ? '0' : $this->podb->from('traffic')->select('SUM(hits) as hitstoday')->where('date BETWEEN "'.$_GET['from'].'" AND "'.$_GET['to'].'"')->fetch()['hitstoday']);?></h3>
							<?php } else { ?>
							<h3><?=(empty($this->podb->from('traffic')->select('SUM(hits) as hitstoday')->where('date', date('Y-m-d'))->fetch()['hitstoday']) ? '0' : $this->podb->from('traffic')->select('SUM(hits) as hitstoday')->where('date', date('Y-m-d'))->fetch()['hitstoday']);?></h3>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
			<p>&nbsp;</p>
			<div class="row">
				<div class="col-md-12">
					<div class="table-responsive">
						<table class="table table-bordered table-striped table-hover">
							<tr>
								<th colspan="7" class="text-center text-uppercase"><?=$GLOBALS['_']['home_top_visitor'];?></th>
							</tr>
							<tr>
								<th class="text-center"><?=$GLOBALS['_']['home_number'];?></th>
								<th class="text-center"><?=$GLOBALS['_']['home_ip'];?></th>
								<th class="text-center"><?=$GLOBALS['_']['home_browser'];?></th>
								<th class="text-center"><?=$GLOBALS['_']['home_platform'];?></th>
								<th class="text-center"><?=$GLOBALS['_']['home_country'];?></th>
								<th class="text-center"><?=$GLOBALS['_']['home_city'];?></th>
								<th class="text-center"><?=$GLOBALS['_']['home_hits'];?></th>
							</tr>
							<?php
								$novisitor = 1;
								if (isset($_GET['from']) && isset($_GET['to'])) {
									$visitors = $this->podb->from('traffic')->select('SUM(hits) as hitstoday')->where('date BETWEEN "'.$_GET['from'].'" AND "'.$_GET['to'].'"')->orderBy('hitstoday DESC')->groupBy('ip')->limit(10)->fetchAll();
								} else {
									$visitors = $this->podb->from('traffic')->select('SUM(hits) as hitstoday')->where('date', date('Y-m-d'))->orderBy('hitstoday DESC')->groupBy('ip')->limit(10)->fetchAll();
								}
								foreach($visitors as $visitor){
							?>
							<tr>
								<td class="text-center"><?=$novisitor;?></td>
								<td class="text-center"><?=($visitor['ip'] == '' ? '-' : $visitor['ip']);?></td>
								<td class="text-center"><?=($visitor['browser'] == '' ? $GLOBALS['_']['home_others'] : $visitor['browser']);?></td>
								<td class="text-center"><?=($visitor['platform'] == '' ? $GLOBALS['_']['home_others'] : $visitor['platform']);?></td>
								<td class="text-center"><?=($visitor['country'] == '' ? $GLOBALS['_']['home_no_country'] : $visitor['country']);?></td>
								<td class="text-center"><?=($visitor['city'] == '' ? $GLOBALS['_']['home_no_city'] : $visitor['city']);?></td>
								<td class="text-center"><span class="label label-info"><?=$visitor['hitstoday'];?></span></td>
							</tr>
							<?php $novisitor++;} ?>
						</table>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6 col-sm-6">
					<div class="table-responsive">
						<table class="table table-bordered table-striped table-hover">
							<tr>
								<th colspan="3" class="text-center text-uppercase"><?=$GLOBALS['_']['home_top_browser'];?></th>
							</tr>
							<tr>
								<th class="text-center"><?=$GLOBALS['_']['home_number'];?></th>
								<th class="text-center"><?=$GLOBALS['_']['home_browser'];?></th>
								<th class="text-center"><?=$GLOBALS['_']['home_hits'];?></th>
							</tr>
							<?php
								$nobrowser = 1;
								if (isset($_GET['from']) && isset($_GET['to'])) {
									$browsers = $this->podb->from('traffic')->select('SUM(hits) as hitstoday')->where('date BETWEEN "'.$_GET['from'].'" AND "'.$_GET['to'].'"')->orderBy('hitstoday DESC')->groupBy('browser')->fetchAll();
								} else {
									$browsers = $this->podb->from('traffic')->select('SUM(hits) as hitstoday')->where('date', date('Y-m-d'))->orderBy('hitstoday DESC')->groupBy('browser')->fetchAll();
								}
								foreach($browsers as $browser){
							?>
							<tr>
								<td class="text-center"><?=$nobrowser;?></td>
								<td class="text-center"><?=($browser['browser'] == '' ? $GLOBALS['_']['home_others'] : $browser['browser']);?></td>
								<td class="text-center"><span class="label label-success"><?=$browser['hitstoday'];?></span></td>
							</tr>
							<?php $nobrowser++;} ?>
						</table>
					</div>
				</div>
				<div class="col-md-6 col-sm-6">
					<div class="table-responsive">
						<table class="table table-bordered table-striped table-hover">
							<tr>
								<th colspan="3" class="text-center text-uppercase"><?=$GLOBALS['_']['home_top_platform'];?></th>
							</tr>
							<tr>
								<th class="text-center"><?=$GLOBALS['_']['home_number'];?></th>
								<th class="text-center"><?=$GLOBALS['_']['home_platform'];?></th>
								<th class="text-center"><?=$GLOBALS['_']['home_hits'];?></th>
							</tr>
							<?php
								$noplatform = 1;
								if (isset($_GET['from']) && isset($_GET['to'])) {
									$platforms = $this->podb->from('traffic')->select('SUM(hits) as hitstoday')->where('date BETWEEN "'.$_GET['from'].'" AND "'.$_GET['to'].'"')->orderBy('hitstoday DESC')->groupBy('platform')->fetchAll();
								} else {
									$platforms = $this->podb->from('traffic')->select('SUM(hits) as hitstoday')->where('date', date('Y-m-d'))->orderBy('hitstoday DESC')->groupBy('platform')->fetchAll();
								}
								foreach($platforms as $platform){
							?>
							<tr>
								<td class="text-center"><?=$noplatform;?></td>
								<td class="text-center"><?=($platform['platform'] == '' ? $GLOBALS['_']['home_others'] : $platform['platform']);?></td>
								<td class="text-center"><span class="label label-danger"><?=$platform['hitstoday'];?></span></td>
							</tr>
							<?php $noplatform++;} ?>
						</table>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6 col-sm-6">
					<div class="table-responsive">
						<table class="table table-bordered table-striped table-hover">
							<tr>
								<th colspan="3" class="text-center text-uppercase"><?=$GLOBALS['_']['home_top_country'];?></th>
							</tr>
							<tr>
								<th class="text-center"><?=$GLOBALS['_']['home_number'];?></th>
								<th class="text-center"><?=$GLOBALS['_']['home_country'];?></th>
								<th class="text-center"><?=$GLOBALS['_']['home_hits'];?></th>
							</tr>
							<?php
								$nocountry = 1;
								if (isset($_GET['from']) && isset($_GET['to'])) {
									$countrys = $this->podb->from('traffic')->select('SUM(hits) as hitstoday')->where('date BETWEEN "'.$_GET['from'].'" AND "'.$_GET['to'].'"')->orderBy('hitstoday DESC')->groupBy('country')->limit(10)->fetchAll();
								} else {
									$countrys = $this->podb->from('traffic')->select('SUM(hits) as hitstoday')->where('date', date('Y-m-d'))->orderBy('hitstoday DESC')->groupBy('country')->limit(10)->fetchAll();
								}
								foreach($countrys as $country){
							?>
							<tr>
								<td class="text-center"><?=$nocountry;?></td>
								<td class="text-center"><?=($country['country'] == '' ? $GLOBALS['_']['home_no_country'] : $country['country']);?></td>
								<td class="text-center"><span class="label label-warning"><?=$country['hitstoday'];?></span></td>
							</tr>
							<?php $nocountry++;} ?>
						</table>
					</div>
				</div>
				<div class="col-md-6 col-sm-6">
					<div class="table-responsive">
						<table class="table table-bordered table-striped table-hover">
							<tr>
								<th colspan="3" class="text-center text-uppercase"><?=$GLOBALS['_']['home_top_city'];?></th>
							</tr>
							<tr>
								<th class="text-center"><?=$GLOBALS['_']['home_number'];?></th>
								<th class="text-center"><?=$GLOBALS['_']['home_city'];?></th>
								<th class="text-center"><?=$GLOBALS['_']['home_hits'];?></th>
							</tr>
							<?php
								$nocity = 1;
								if (isset($_GET['from']) && isset($_GET['to'])) {
									$citys = $this->podb->from('traffic')->select('SUM(hits) as hitstoday')->where('date BETWEEN "'.$_GET['from'].'" AND "'.$_GET['to'].'"')->orderBy('hitstoday DESC')->groupBy('city')->limit(10)->fetchAll();
								} else {
									$citys = $this->podb->from('traffic')->select('SUM(hits) as hitstoday')->where('date', date('Y-m-d'))->orderBy('hitstoday DESC')->groupBy('city')->limit(10)->fetchAll();
								}
								foreach($citys as $city){
							?>
							<tr>
								<td class="text-center"><?=$nocity;?></td>
								<td class="text-center"><?=($city['city'] == '' ? $GLOBALS['_']['home_no_city'] : $city['city']);?></td>
								<td class="text-center"><span class="label label-info"><?=$city['hitstoday'];?></span></td>
							</tr>
							<?php $nocity++;} ?>
						</table>
					</div>
				</div>
			</div>
		</div>
	<?php
	}

	/**
	 * Fungsi ini digunakan untuk membuat addcslashes array js.
	 *
	 * This function use for create addcslashes array js.
	 *
	 * Added in v.2.0.1
	*/
	public function js_str($s)
	{
		return '"' . addcslashes($s, "\0..\37\"\\") . '"';
	}

	/**
	 * Fungsi ini digunakan untuk membuat array php to array js.
	 *
	 * This function use for create array php to array js.
	 *
	 * Added in v.2.0.1
	*/
	public function js_array($array)
	{
		$temp = array_map(array($this, 'js_str'), $array);
		return '[' . implode(',', $temp) . ']';
	}

	/**
	 * Fungsi ini digunakan untuk menampilkan halaman error home.
	 *
	 * This function use for error home page.
	 *
	*/
	public function error()
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

	/**
	 * Fungsi ini digunakan untuk menampilkan halaman logout.
	 *
	 * This function use for logout page.
	 *
	*/
	public function logout()
	{
		session_destroy();
		header('location:index.php');
	}

}