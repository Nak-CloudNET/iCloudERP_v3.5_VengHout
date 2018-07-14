<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?= lang('view_kitchen') . " | " . $Settings->site_name; ?></title>
    <base href="<?= base_url() ?>"/>
    <meta http-equiv="cache-control" content="max-age=0"/>
    <meta http-equiv="cache-control" content="no-cache"/>
    <meta http-equiv="expires" content="0"/>
	<meta http-equiv="refresh" content="300">
    <meta http-equiv="pragma" content="no-cache"/>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="<?= $assets ?>styles/theme.css" type="text/css"/>
    <style type="text/css">
		.list-products{
			background-color:#f39c12;
			padding-top:15px;
			
		}
		.list-products:hover{
			background:#EC971F;
			cursor:pointer;
		}
		.panel-body{
			border:1px solid #bdc3c7;
			background-color:#ecf0f1;	
		}
    </style>
</head>
<body>
<div class="col-sm-12 content">
	<?php if ($page) { ?>
		<div class="modal-footer" style="padding:0;">
			<div class="page_con"><?= $page ?></div>
		</div>
	<?php } ?>
	<div class="panel panel-default no-radious">
		<div class="panel-body">
			<?php
				$i=1;
				foreach($data as $kit)
				{
			?>
				<div class="list-products col-md-2 col-xs-2 col-sm-2" style="padding:0;border:3px solid #bdc3c7;">
					<div style="padding:10px;">
						<i style="text-align:right;position:absolute; background-color:#2c3e50;color:#ecf0f1;min-width:30px;padding-right:8px;font-size:20pt;font-weight:bold;">
							<span id="<?= base_url().'pos/view_modal/'.$kit->idd?>" class="<?= $i?>" ><?= $i;?></span>
						</i>
						<img src="<?= base_url().'assets/uploads/'.$kit->image;?>"  class="img-thumbnail" style=" width:100% !important;height:200px !important;" >
					</div>
					<div class="name" style="text-align:center;color:white;"><p><?= $kit->name?></p></div>
					<div class="tnq" >
						<div style="float:left;background-color:#2c3e50;color:white;width:50%;padding:2px;border-right:1px solid #ecf0f1;font-size:9pt;"><span><?= lang('qty'); ?>: </span><span style="font-size:18pt;font-weight:bold"># <?= number_format($kit->quantity)?> </span></div>
						<div style="float:right;background-color:#ac2925;color:white;width:50%;text-align:right;padding:2px;font-size:9pt;" ><span><?= lang('table'); ?>: </span><span style="font-size:18pt;font-weight:bold"><?= number_format($kit->table)?> </span> </div>
					</div>
				</div>
			<?php
				$i++;
				}
			?>
		</div>
	</div>
	<input type="text" id="Onclick" style="opacity: 1;" />
	<a style="display:none;" data-toggle="modal" class="btn btn-info remote" href="" data-target="#myModal">Click me !</a>
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	</div>
	<button style="display:none;" type="button" id="number" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal1">Open Modal</button>
	<div class="modal fade" id="myModal1" role="dialog">
		<div class="modal-dialog modal-sm">
		  <!-- Modal content-->
		  <div class="modal-content" style="border-radius:0;">
			<div class="modal-body">
			  <p>Number Only</p>
			</div>
			<div class="modal-footer" style="border:none;">
			  <button type="button" id="close" class="btn btn-primary" style="border-radius:0;" data-dismiss="modal">Close</button>
			</div>
		  </div>
		  
		</div>
	</div>
</div>
<script type="text/javascript" src="<?= $assets ?>js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/bootstrap.min.js"></script>
<script src="https://raw.githubusercontent.com/makeusabrew/bootbox/master/bootbox.js"></script>
<script>
	$(document).ready(function () {
		$('#Onclick').focus();
		function isLetter(str) {
			return str.length === 1 && str.match(/[a-z]/i);
		}
		document.onkeydown=function(evt){
			var keyCode = evt ? (evt.which ? evt.which : evt.keyCode) : event.keyCode;
			if(keyCode == 13)
			{
				var val = $('#Onclick').val();
				if($.isNumeric(val)){
					var id  = $('.'+ val).attr('id');
					$('.remote').attr('href',id)
					$('.remote').trigger('click');
				}else if(isLetter(val)){
					$('#number').trigger('click');
				}
				$('.remote').attr('href','');
				$('#Onclick').val('');
			}
			if(keyCode == 27){
				$('#close').trigger('click');
				$('#Onclick').val('');
				$('#Onclick').focus();
				return false;
			}
		}
		$('body').click(function(){
			$('#Onclick').focus();
		});
        window.setInterval(function () {
            window.location.reload();
        }, 300000);
    });
</script>
</body>
</html>