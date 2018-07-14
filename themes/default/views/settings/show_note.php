<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('product_note'); ?></h4>
        </div>
        <div class="modal-body">
			<div class="row">
				<?php foreach($rows as $row){?>
					<div class="list-products col-md-2 col-xs-2 col-sm-2">
						<div style="width:100%;cursor:pointer;" class="checkbox">
							<input type="checkbox" class="inputField" title="<?= base_url().'assets/uploads/'.$row->image;?>" value="<?= $row->name?>" name="inputField[]" />
							<img src="<?= base_url().'assets/uploads/'.$row->image;?>"  class="img-thumbnail" style=" width:100% !important;height:85px !important;" >
							<div class="text-center"><?= $row->name?></div>
						</div>
					</div>
				<?php }?>
			</div>
		</div>
        <div class="modal-footer">
            <?php echo form_submit('add_note', lang('add_note'), 'class="btn btn-primary addNote"'); ?>
        </div>
    </div>
	<script>
		$('.addNote').click(function(){
			var val = Array();
			var img = Array();
			var value = '';
			var i=0;
			$('#pnote').val("");
			$('input:checkbox:checked').each(function(i,e){
				val[i] = $(this).val();
				img += '<div class="col-sm-3" style="border:1px solid #ccc;float:left;"><img src="' + $(this).attr('title') +'" style="width:100%;height:100px;"></div>';
				i++;
			});
			value = val.join();
			var divimg = '<div class="col-sm-12">'+img+'</div>';
			$('#pnote').val(value);
			$('.images').html(divimg);
			$('#myModal').modal('hide');
		});
		$('.checkbox').click(function(){
			$(this).closest('div').find('.inputField').click();
			if($(this).closest('div').find('.inputField').is(':checked')){
				$(this).addClass('checkstyle')
			}else{
				$(this).removeClass('checkstyle')
			}
			value = $(".inputField").val();
		});
		
	</script>
	<style>
		.inputField{display:none;}
		.checkstyle{border:1px solid #999;}
	</style>
</div>
<?= $modal_js ?>