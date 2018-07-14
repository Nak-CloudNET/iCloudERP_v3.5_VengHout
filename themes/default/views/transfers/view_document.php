<div class="modal-dialog">
	<!-- Modal content-->
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title"><?= lang('view_document')?></h4>
		</div>
		<div class="modal-body">
			<?php
				foreach($document as $doc){
					
					$path = base_url() . 'files/';
			?>
				<?php if($doc->attachment != ''){?>
				<div class="row">
					<div class="col-lg-12">
						<div class="col-lg-6">
							<?=lang('document')?>
						</div>
						<div class="col-lg-6">
							<a href="#ShowImage" data-toggle="modal" class="btn btn-primary dox1" alt="<?=$doc->attachment?>" >Show Document</a>
						</div>
					</div>
				</div>
				<br/>
				<?php }?>
				<?php if($doc->attachment1 != ''){?>
				<div class="row">
					<div class="col-lg-12">
						<div class="col-lg-6">
							<?=lang('document')?>
						</div>
						<div class="col-lg-6">
							<a href="#ShowImage" data-toggle="modal" class="btn btn-primary dox2" alt="<?=$doc->attachment1?>" >Show Document</a>
						</div>
					</div>
				</div>
				<br/>
				<?php }?>
				<?php if($doc->attachment2 != ''){?>
				<div class="row">
					<div class="col-lg-12">
						<div class="col-lg-6">
							<?=lang('document')?>
						</div>
						<div class="col-lg-6">
							<a href="#ShowImage" data-toggle="modal" class="btn btn-primary dox3" alt="<?=$doc->attachment2?>" >Show Document</a>
						</div>
					</div>
				</div>
				<?php }?>
			<?php
				}
			?>
		</div>
	</div>
</div>

<div id="ShowImage" class="modal fade" role="dialog" tabindex="-1" aria-labelledby="prModalLabel" aria-hidden="true" style="z-index:9999;">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
		<div class="modal-body">
			<button type="button" class="close" data-dismiss="modal">
				<span aria-hidden="true"><i class="fa fa-2x">×</i></span>
			</button>
			<div class="getImg"></div>
		</div>
    </div>
  </div>
</div>
<script>
	$(document).ready(function(){
		$(document).on("click", ".dox1", function () {
			var img = $(this).attr('alt');
			var image = '<img src="http://192.168.1.168:8181/CloudNET/iCloudERP_ACC/files/'+img+'" style="width:100%;"/>';
			$('.getImg').html(image);
		});
		$(document).on("click", ".dox2", function () {
			var img = $(this).attr('alt');
			var image = '<img src="http://192.168.1.168:8181/CloudNET/iCloudERP_ACC/files/'+img+'" style="width:100%;"/>';
			$('.getImg').html(image);
		});
		$(document).on("click", ".dox3", function () {
			var img = $(this).attr('alt');
			var image = '<img src="http://192.168.1.168:8181/CloudNET/iCloudERP_ACC/files/'+img+'" style="width:100%;"/>';
			$('.getImg').html(image);
		});
	});
</script>