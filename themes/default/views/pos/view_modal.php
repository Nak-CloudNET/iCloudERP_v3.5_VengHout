<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <title>Remote file for Bootstrap Modal</title>  
  <style type="text/css">
		.list-products{
			background-color:#f39c12;
			padding-top:15px;
			
		}
		.list-products:hover{
			background:#EC971F;
			cursor:pointer;
		}
    </style>
</head>
<body>
	<div class="modal-dialog">
		<div class="modal-content" style="border-radius:0;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" style="font-size:20pt;font-weight:bold;"><?=$data->product_name;?></h4>
            </div>
			<?php 
				$attrib = array('data-toggle' => 'validator', 'role' => 'form');
				echo form_open_multipart("pos/complete_kitchen/" . $data->idd, $attrib); 
			?>
            <div class="modal-body">
				<div class="list-products">
					<div style="padding:10px;">
						<img src="<?= base_url().'assets/uploads/'.$data->image;?>"  class="img-thumbnail" style=" width:100% !important;height:350px !important;" >
					</div>
					<div class="tnq" style="margin-bottom:30px;">
						<div style="float:left;background-color:#ac2925;color:white;width:50%;padding:2px;border-right:1px solid #ecf0f1;font-size:9pt;"><span><?= lang('table'); ?>: </span><span style="font-size:18pt;font-weight:bold"><?= number_format($data->table)?> </span></div>
						<div style="float:right;background-color:#2c3e50;color:white;width:50%;text-align:right;padding:2px;font-size:9pt;" ><span><?= lang('qty'); ?>: </span><span style="font-size:18pt;font-weight:bold"><?= number_format($data->quantity)?> </span> </div>
					</div>
				</div>
            </div>	
			
            <div class="modal-footer">
                <button type="button" style="border-radius:0;" class="btn btn-primary">Save</button>
            </div>	
			<?php echo form_close(); ?>
		</div>
	</div>
	<script>
		$(document).ready(function(){
			$(document).keypress(function(e) { 
				if (e.keyCode == 27) { 
					e.preventDefault();
					closeVideoPopup();
				} 
				if (e.keyCode == 13){
					$('form').submit();
				}
			});
			function closeVideoPopup() {
				location.reload();
			}
		});
	</script>
</body>
</html>