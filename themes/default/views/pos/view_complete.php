<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?= lang('view_complete')?></title>
    <base href="<?= base_url() ?>"/>
    <meta http-equiv="cache-control" content="max-age=0"/>
    <meta http-equiv="cache-control" content="no-cache"/>
    <meta http-equiv="expires" content="0"/>
	<meta http-equiv="refresh" content="300">
    <meta http-equiv="pragma" content="no-cache"/>
    <link rel="stylesheet" href="<?= $assets ?>styles/kitchen.css" type="text/css"/>
    <link rel="stylesheet" href="<?= $assets ?>styles/helpers/bootstrap.min.css" type="text/css" />
	<link rel="stylesheet" href="<?= $assets ?>pos/css/posajax.css" type="text/css"/>
</head>
<body>
<div class="wrapper">
    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="containers">
                    <table>
                        <?php
                            $i=1;
                            foreach($data as $kit)
                            {
                        ?>
                            <tr>
                                <td>
                                    <div class="tables">
                                        <div style="padding-top:5px;"><span><?= lang('table'); ?>: </span>
                                        <span style="font-size:18pt;font-weight:bold"><?= number_format($kit->name)?> </span></div>
                                        <div>
                                            <span><?= lang('qty'); ?>: </span>
                                            <span style="font-size:18pt;font-weight:bold">
                                                <?php
                                                    $this->db->select("count('*') as num")
                                                        ->from('suspended_items')
                                                        ->join('suspended_bills', 'suspended_bills.id = suspended_items.suspend_id', 'left')
                                                        ->where('erp_suspended_bills.suspend_id', $kit->id);
                                                    $q = $this->db->get();
                                                    if ($q->num_rows() > 0) {
                                                        foreach ($q->result() as $row) {
                                                            echo $row->num;
                                                        }
                                                    }
                                                ?>										
                                            </span>
                                        </div>
										<div class="action">
											<div class="col-sm-6 col-xs-6  dollor" id="<?=$kit->idd?>"><i class="fa fa-usd" aria-hidden="true"></i></div>
											<div class="col-sm-6 col-xs-6 addplus new" text="<?=$kit->ware?>" id="<?=$kit->idd?>"><i class="fa fa-plus" aria-hidden="true"></i></div>
										</div>
                                    </div>
                                </td>
                                <?php
                                $this->db->select('erp_suspended_items.id as idd, product_code, product_name, erp_products.image, erp_suspended_items.quantity, erp_suspended_bills.suspend_name as table, erp_suspended_items.status as complete ')
                                         ->from('suspended_items')
                                         ->join('products', 'products.id = suspended_items.product_id', 'left')
                                         ->join('suspended_bills', 'suspended_bills.id = suspended_items.suspend_id', 'left')
                                         ->where('erp_suspended_bills.suspend_id', $kit->id);
                                $q = $this->db->get();
                                if ($q->num_rows() > 0) {
                                    foreach ($q->result() as $frow) {
										$qty = '';
										if($frow->quantity != 0){
											$qty = number_format($frow->quantity);
										}else{
											$qty = 0;
										}
                                        if($frow->complete == 1){
                            ?>
                                <td>
                                    <div class="foods">
                                        <!--<span id="<?=$frow->idd?>" class="clear"><i class="fa fa-times icon" aria-hidden="true"></i></span>-->
                                        <img src="<?=base_url().'assets/uploads/'.$frow->image?>" class="img-thumbnail"/>
										<!--
										<span class="qty">
											<div class="col-sm-4 col-xs-4 minus"><i class="fa fa-minus icons" aria-hidden="true"></i>
                                            </div>
											<div class="col-sm-4 col-xs-4 no-padd">
												<input class="num" type="text" value="1" />
											</div>
											<div class="col-sm-4 col-xs-4 plus"><i class="fa fa-plus iconplus" aria-hidden="true"></i>
                                            </div>
										</span>
										-->
                                    </div>
                                </td>
                            <?php
                                        }else{
                            ?>
                                <td>
                                    <div class="food">
                                        <span id="<?=base_url()."pos/delete_item/".$frow->idd?>" class="clear"><i class="fa fa-times icon" aria-hidden="true"></i></span>
                                        <img src="<?=base_url().'assets/uploads/'.$frow->image?>" class="img-thumbnail"/>
										<span class="qty">
											<div id="<?=$frow->idd?>" class="col-sm-4 col-xs-4 minus">
												<i class="fa fa-minus icons" aria-hidden="true"></i>
                                            </div>
											<div class="col-sm-4 col-xs-4 no-padd">
												<input id="val<?=$frow->idd?>" class="num" type="text" value="<?=$qty;?>" />
											</div>
											<div id="<?=$frow->idd?>" class="col-sm-4 col-xs-4 plus">
												<i class="fa fa-plus iconplus" aria-hidden="true"></i>
                                            </div>
										</span>
                                    </div>
                                </td>
                            <?php
                                        }
                                    }
                                }
                            ?>    
                            </tr>
                        <?php
                            $i++;
                            }
                        ?>
                    </table>
                </div>
            </div>
        </div>	
    </div>
	
    <div class="modal" id="seFoModal" tabindex="-1" role="dialog" aria-labelledby="prModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true"><i class="fa fa-2x">&times;</i></span><span class="sr-only"><?=lang('close');?></span></button>
                    <h4 class="modal-title" id="prModalLabel">Add Products</h4>
                </div>
				<?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'pos-sale-form');
                echo form_open("pos/add_item", $attrib); ?>
					<div class="result"></div>
				<?php echo form_close()?>
                <div class="modal-body" id="pr_popover_content">
					<div class="well well-sm">
						<div style="margin-bottom:0;" class="form-group">
							<div class="input-group wide-tip">
								<div style="padding-left: 10px; padding-right: 10px;" class="input-group-addon">
									<i class="fa fa-2x fa-barcode addIcon"></i>
								</div>
								<input type="text" placeholder="Please add products to order list" id="add_item" class="form-control input-lg ui-autocomplete-input" value="" name="add_item" autocomplete="off"/>
							</div>
						</div>
						<div class="clearfix"></div>
					</div>
                    <form class="form-horizontal" role="form" id="s_seModal">
                        <table class="table table-bordered colors">
                            <thead>
                                <tr>
                                    <th style="width:45px;">
                                        <center>
                                            <input class="checkbox checkth input-xs" type="checkbox" name="check"/>
                                        </center>
                                    </th>
                                    <th><?php echo lang('code'); ?></th>
                                    <th><?php echo lang('name'); ?></th>
                                    <th><?php echo lang('quantity'); ?></th>
                                </tr>
                            </thead>
                            <tbody class="floor"></tbody>
                        </table>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="addItem"><?= lang('submit') ?></button>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?= $assets ?>js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?= $assets ?>pos/js/plugins.min.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/bootstrap.min.js"></script>
<script>
	$(document).ready(function(){
		$('.clear').click(function(){
			var id = $(this).attr('id');
			window.location.href = id;
		});
		$('.dollor').click(function(){
			var sid = $(this).attr("id");
            window.location.href = "<?= site_url('pos/index') ?>/" + sid;
            
            return false;
		});
        var idd = '';
		var ware = '';
		$('.new').click(function(){
            idd = $(this).attr('id');
			ware = $(this).attr('test');
            $('#seFoModal').appendTo("body").modal('show');
            return false;
        });
		$('#addItem').click(function(){
			var arr = [];
			var test = '';
			var result = $('input[name="val"]:checked');
			if(result.length > 0){
				result.each(function(){
					var str = $(this).val();
					arr.push(str);
					test = arr.join(',');
					//window.location.href = '<?= base_url().'pos/add_item/'?>' + str +'/'+ idd;
				});
			}
			var html = '<input type="text" value="'+test+'" name="pro_id"/><input type="text" value="'+idd+'" name="sus_id"/><input type="text" value="'+ware+'" name="ware_id"/>';
			$(html).appendTo('.result');
			$('#pos-sale-form').submit();
		});
        $("#add_item").autocomplete({
			search: function(event, ui) {
				$('.floor').empty();
			},
            source: function (request, response) {
				var test = request.term;
				if($.isNumeric(test)){
					$.ajax({
						type: 'get',
						url: '<?= site_url('pos/suggests'); ?>',
						dataType: "json",
						data: {
							term: request.term,
							pros: idd
						},
						success: function (data) {
							response(data);
						}
					});
				}else{
					$.ajax({
						type: 'get',
						url: '<?= site_url('pos/suggestions'); ?>',
						dataType: "json",
						data: {
							term: request.term,
							pros: idd
						},
						success: function (data) {
							response(data);
						}
					});
				}
            },
			minLength: 1,
            autoFocus: false,
            delay: 200,
            response: function (event, ui) {
                if ($(this).val().length >= 16 && ui.content[0].id == 0) {
                    //audio_error.play();
					
                   bootbox.alert('<?= lang('no_match_found') ?>', function () {
                        $('#add_item').focus();
                    });
					
                    $(this).val('');
                }
				/*
                else if (ui.content.length == 1 && ui.content[0].id != 0) {
                    ui.item = ui.content[0];
                    $(this).data('ui-autocomplete')._trigger('select', 'autocompleteselect', ui);
                    $(this).autocomplete('close');
                    $(this).removeClass('ui-autocomplete-loading');
                }
				*/
                else if (ui.content.length == 1 && ui.content[0].id == 0) {
                    bootbox.alert('<?= lang('no_match_found') ?>', function () {
                        $('#add_item').focus();
                    });
                }
            },
            select: function (event, ui) {
                event.preventDefault();
                if (ui.item.id !== 0) {
                    var row = add_purchase_item(ui.item);
                    if (row)
                        $(this).val('');
                } else {
                    //audio_error.play();
                    bootbox.alert('<?= lang('no_match_found') ?>');
                }
            },
			open: function(event, ui) {
				$(".ui-autocomplete").css("width", "0");
				$(".ui-autocomplete").css("z-index", "99999");
			}
        }).autocomplete( "instance" )._renderItem = function( ul, item ) {
			var inner_html  = 	'<td style="width:50px;height:30px;">' +
									'<center>'+
										'<input class="checkbox multi-select input-xs" type="checkbox" name="val" value="'+ item.id +'"/>'+
									'</center>' +
								'</td>' +
								'<td style="width:140px;">'+
									item.code +
								'</td>' +
								'<td style="width:142px;">'+
									item.label +
								'</td>' +
								'<td style="width:114px;">'+
									item.qty +
								'</td>';
			return $( "<tr></tr>")
				.data( "item.autocomplete", item )
				.append(inner_html)
				.appendTo($('.floor'));
		}
		
		$('#add_item').bind('keypress', function (e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                $(this).autocomplete("search");
            }
        });

        $('.minus').click(function(){
			var minus = $(this).attr('id');
			minus_number(minus);
        });
		
		$('.plus').click(function(){
			var plus = $(this).attr('id');
			plus_number(plus);
        });
		
		function minus_number(num){
			var val   = $('#val'+num).val();
			var minus = Math.max(parseInt(val) - 1);
			$('#val'+num).val(minus);
			$.ajax({
				type: "GET",
				url: 'logtime.php',
				data: minus,
				success: function(data)
				{
					alert("success!");
				}
			});
		}
		
		function plus_number(num){
			var val   = $('#val'+num).val();
			var plus  = Math.max(parseInt(val) + 1);
			//alert(plus);
			$('#val'+num).val(plus);
		}
	});
</script>
</body>
</html>