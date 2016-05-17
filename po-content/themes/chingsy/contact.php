<?=$this->layout('index');?>

<section id="page-title" class="page-title-mini" style="margin-top:-80px;">
	<div class="container clearfix">
		<ol class="breadcrumb">
			<li><a href="<?=BASE_URL;?>"><?=$this->e($front_home);?></a></li>
			<li><a href="<?=BASE_URL.'/contact';?>"><?=$this->e($front_contact);?></a></li>
		</ol>
	</div>
</section>

<section id="google-map" class="gmap slider-parallax"></section>

<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript" src="<?=$this->asset('/js/jquery.gmap.js')?>"></script>
<script type="text/javascript">
	$('#google-map').gMap({
		<?=$this->pocore()->call->posetting[9]['value'];?>
		maptype: 'ROADMAP',
		zoom: 14,
		markers: [
			{
				<?=$this->pocore()->call->posetting[9]['value'];?>
			}
		],
		doubleclickzoom: false,
		controls: {
			panControl: true,
			zoomControl: true,
			mapTypeControl: true,
			scaleControl: false,
			streetViewControl: false,
			overviewMapControl: false
		}
	});
</script>

<section id="content">
	<div class="content-wrap">
		<div class="container clearfix">
			<div class="postcontent nobottommargin">
				<?=htmlspecialchars_decode($this->e($alertmsg));?>
				<form class="nobottommargin" id="template-contactform" name="template-contactform" action="<?=BASE_URL;?>/contact" method="post">
					<div class="col_one_third">
						<label for="template-contactform-name"><?=$this->e($contact_name);?> <small>*</small></label>
						<input type="text" id="template-contactform-name" name="contact_name" value="<?=(isset($_POST['contact_name']) ? $_POST['contact_name'] : '');?>" class="sm-form-control required" />
					</div>
					<div class="col_one_third">
						<label for="template-contactform-email"><?=$this->e($contact_email);?> <small>*</small></label>
						<input type="email" id="template-contactform-email" name="contact_email" value="<?=(isset($_POST['contact_email']) ? $_POST['contact_email'] : '');?>" class="required email sm-form-control" />
					</div>
					<div class="col_one_third col_last">
						<label for="template-contactform-subject"><?=$this->e($contact_subject);?> <small>*</small></label>
						<input type="text" id="template-contactform-subject" name="contact_subject" value="<?=(isset($_POST['contact_subject']) ? $_POST['contact_subject'] : '');?>" class="required sm-form-control" />
					</div>
					<div class="clear"></div>
					<div class="col_full">
						<label for="template-contactform-message"><?=$this->e($contact_message);?> <small>*</small></label>
						<textarea class="required sm-form-control" id="template-contactform-message" name="contact_message" rows="6" cols="30"><?=(isset($_POST['contact_message']) ? $_POST['contact_message'] : '');?></textarea>
					</div>
					<div class="col_full hidden">
						<input type="text" id="template-contactform-botcheck" name="template-contactform-botcheck" value="" class="sm-form-control" />
					</div>
					<div class="col_full">
						<button class="button button-3d nomargin" type="submit" id="template-contactform-submit" name="contact_submit" value="submit"><?=$this->e($front_contact_btn);?></button>
					</div>
				</form>
				<script type="text/javascript">
					$("#template-contactform").validate();
				</script>
			</div>

			<div class="sidebar col_last nobottommargin">
				<address>
					<?=htmlspecialchars_decode($this->pocore()->call->posetting[8]['value']);?>
				</address>
				<abbr title="Phone Number"><strong>Phone:</strong></abbr> <?=$this->pocore()->call->posetting[6]['value'];?><br>
				<abbr title="Fax"><strong>Fax:</strong></abbr> <?=$this->pocore()->call->posetting[7]['value'];?><br>
				<abbr title="Email Address"><strong>Email:</strong></abbr> <?=$this->pocore()->call->posetting[5]['value'];?>
				<div class="widget noborder notoppadding">
					<a href="javascript:void(0)" class="social-icon si-small si-dark si-facebook"><i class="icon-facebook"></i><i class="icon-facebook"></i></a>
					<a href="javascript:void(0)" class="social-icon si-small si-dark si-twitter"><i class="icon-twitter"></i><i class="icon-twitter"></i></a>
					<a href="javascript:void(0)" class="social-icon si-small si-dark si-gplus"><i class="icon-gplus"></i><i class="icon-gplus"></i></a>
				</div>
			</div>
		</div>
	</div>
</section>