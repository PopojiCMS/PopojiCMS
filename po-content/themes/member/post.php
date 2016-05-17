<?=$this->layout('index');?>

<main class="cd-main-content">
	<div class="container-fluid">
		<div class="container member-page">
			<div class="row">
				<div class="col-md-10 col-md-offset-1">
					<div class="page-header">
						<h3><i class="fa fa-pencil"></i> <?=$this->e($front_member_allpost);?></h3>
						<div class="pull-right" style="margin-top:-40px;"><a href="<?=BASE_URL;?>/member/post/addnew" class="btn btn-sm btn-info"><i class="fa fa-plus"></i> <?=$this->e($front_member_addpost);?></a></div>
					</div>
					<div class="member-content">
						<?=htmlspecialchars_decode($this->e($alertmsg));?>
						<div class="row">
							<div class="col-md-12">
								<form method="post" action="<?=BASE_URL;?>/member/post/multidelete" autocomplete="off" >
									<input type="hidden" name="totaldata" value="0" id="totaldata">
									<table id="table-post" class="table table-striped table-bordered" cellpadding="0" cellspacing="0" border="0" width="100%" >
										<thead>
											<tr>
												<th class="no-sort" style="width:10px;"></th>
												<th style="width:30px;">Id</th>
												<th style="width:150px;">Kategori</th>
												<th >Judul | Link</th>
												<th class="no-sort" style="width:30px;">Aktif</th>
												<th class="no-sort" style="width:80px;">Tindakan</th>
											</tr>
										</thead>
										<tfoot>
											<tr>
												<td style="width:10px;" class="text-center"><input type="checkbox" id="titleCheck" data-toggle="tooltip" title="Pilih Semua" /></td>
												<td colspan="5">
													<button class="btn btn-sm btn-danger" type="button" data-toggle="modal" data-target="#alertalldel"><i class="fa fa-trash-o"></i> Hapus Item Terpilih</button>
												</td>
											</tr>
										</tfoot>
									</table>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>

<div id="alertdel" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<form method="post" action="<?=BASE_URL;?>/member/post/delete" autocomplete="off">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 id="modal-title"><i class="fa fa-exclamation-triangle text-danger"></i> <?=$this->e($dialogdel_1);?></h4>
				</div>
				<div class="modal-body">
					<input type="hidden" id="delid" name="id" />
					<?=$this->e($dialogdel_2);?>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-trash-o"></i> <?=$this->e($dialogdel_3);?></button>
					<button type="button" class="btn btn-sm btn-default" data-dismiss="modal" aria-hidden="true"><i class="fa fa-sign-out"></i> <?=$this->e($dialogdel_4);?></button>
				</div>
			</form>
		</div>
	</div>
</div>