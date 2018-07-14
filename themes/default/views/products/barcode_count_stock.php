<div class="box">
	<div class="box-header">
		<h2 class="blue"><i class="fa fa-list-ol"></i><?= lang('barcode_count_stock'); ?></h2>
	</div>
	<div class="box-content">
		<div class="row">
        <div class="col-lg-12">
            <div id="pos">
				<div id="left-top">
                  <div style="position: absolute; <?= $Settings->rtl ? 'right:-9999px;' : 'left:-9999px;'; ?>"><?php echo form_input('test', '', 'id="test" class="kb-pad"'); ?></div>
                  <div class="no-print">
						<div class="well well-sm">
							<div class="col-sm-4">
								<div class="form-group">
                                    <?= lang("warehouse", "powarehouse"); ?>
                                    <?php
                                    $wh[''] = '';
                                    foreach ($warehouses as $warehouse) {
                                        $wh[$warehouse->id] = $warehouse->name;
                                    }
                                    echo form_dropdown('warehouse', $wh, (isset($_POST['warehouse']) ? $_POST['warehouse'] : $Settings->default_warehouse), 'id="powarehouse" class="form-control input-tip select" data-placeholder="' . lang("select") . ' ' . lang("warehouse") . '" style="width:100%;" ');
                                    ?>
                                </div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
                                    <?= lang("category", "category"); ?>
                                    <?php
                                    $ca['0'] = 'All';
                                    foreach ($category as $categories) {
                                        $ca[$categories->id] = $categories->name;
                                    }
                                    echo form_dropdown('categories', $ca, '', 'id="pocategories" class="form-control input-tip select" data-placeholder="' . lang("select") . ' ' . lang("categories") . '" style="width:100%;" ');
                                    ?>
                                </div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<?= lang("product", "product"); ?>
									<?php echo form_input('add_item', '', 'class="form-control" id="add_item" placeholder="' . $this->lang->line("add_product_to_order") . '"'); ?>
								</div>
							</div>
							<div class="clearfix"></div>
						</div>
                  </div>
				</div>
				<div id="print">
					<div id="left-middle">
						<div id="product-list">
							<table id="dvData" class="table items table-striped table-bordered table-condensed table-hover" style="margin-bottom: 0;">
								<thead>
									<tr>
										<th><?= lang("num"); ?></th>
										<th><?= lang("product_code"); ?></th>
										<th><?= lang("product_name"); ?></th>
										<th><?= lang("variant"); ?></th>
										<th><?= lang("expected"); ?></th>    
										<th><?= lang("counted"); ?></th>
									</tr>
								</thead>
								<tbody class="tbody"></tbody>
							</table>
							<input type="hidden" name="table_no" class=" table_no" id="table_no" value="<?=(isset($arrSuspend['suspend_not']) ? $arrSuspend['suspend_not'] : '')?>"/>
							<input type="hidden" name="suspend_" id="suspend_id" value="<?=(isset($sid) ? $sid : 0)?>" />
							<input type="hidden" name="other_cur_paid" class="other_cur_paid" value="" />
							<div style="clear:both;"></div>
						</div>
					</div>
					<div style="clear:both;"></div>
				</div>
            </div>
        </div>
		<div style="clear:both;"></div>
			<iframe id="txtArea1" style="display:none"></iframe>
		</div>
		<div style="clear:both;"></div>
		<br/>
		<div class="row">
			<div class="col-lg-12">
				<input class="btn btn-primary" type="submit" value="Export Excel" id="excel" data-action="export_excel">
				<button type="button" style="width:100px;" class="btn btn-danger" id="reset"><?= lang('reset') ?>
			</div>
		</div>
	</div>
</div>
</div>
</div>
</div>
</div>

<script type="text/javascript">
	$(function(){
		
		$("#add_item").autocomplete({
            source: function (request, response) {
				var test = request.term;
				$.ajax({
					type: 'get',
					url: '<?= site_url('purchases/suggestionsStock'); ?>',
					dataType: "json",
					data: {
						term: request.term,
						warehouse_id : $("#powarehouse").val(),
						category_id  : $("#pocategories").val(),
						customer_id  : $("#poscustomer").val()
					},
					success: function (data) {
						response(data);
						$('#add_item').val("");
						$('#powarehouse').attr("disabled", true);
					}
				});
				
            },
			minLength: 1,
            autoFocus: false,
            delay: 200,
            response: function (event, ui) {
                if ($(this).val().length >= 16 && ui.content[0].id == 0) {
                   bootbox.alert('<?= lang('no_match_found') ?>', function () {
                        $('#add_item').focus();
                    });
                    $(this).val('');
                }
                else if (ui.content.length == 1 && ui.content[0].id != 0) {
                    ui.item = ui.content[0];
                    $(this).data('ui-autocomplete')._trigger('select', 'autocompleteselect', ui);
                    $(this).autocomplete('close');
                    $(this).removeClass('ui-autocomplete-loading');
                }
                else if (ui.content.length == 1 && ui.content[0].id == 0) {
                    bootbox.alert('<?= lang('no_match_found') ?>', function () {
                        $('#add_item').focus();
                    });
                }
            },
            select: function (event, ui) {
                event.preventDefault();
                if (ui.item.id !== 0) {
                    var row = add_stock_item(ui.item);
                    if (row)
                        $(this).val('');
                } else {
                    bootbox.alert('<?= lang('no_match_found') ?>');
                }
            }
        });

		// add store item
		function add_stock_item(item)
		{
			var poitems = JSON.parse(__getItem('poitems'));
			if(poitems == null){
				poitems = {};
			}

			var rounded = item.id;
			$( ".pro_id" ).each(function() {
				var rid = $(this).val();
				row     = $(this).closest('tr');
				var opt = row.find('.roption').val();
				if ((parseFloat(rid) === parseFloat(item.item_id) && parseFloat(opt) === parseFloat(item.option_id)) || (parseFloat(rid) === parseFloat(item.item_id) && item.option_id === "") ) {
					rounded = row.find('.count').val();
				}
			});

			var item_id = site.settings.item_addition == 1 ? rounded : rounded;

			if (poitems[item_id]) {
				poitems[item_id].qty = parseFloat(poitems[item_id].qty) + 1;
			} else {
				poitems[item_id] = item;
			}
			__setItem('poitems', JSON.stringify(poitems));
			loadStorage();
		}

		loadStorage();

		function loadStorage(){
			var poitems = JSON.parse(__getItem('poitems'));

			if(poitems != null)
			{
				var v = "";
				var k = 1;
				$.each(poitems, function (i,e) {
					var item_id = site.settings.item_addition == 1 ? e.id : e.id;
					var qty 	= e.quantity;
					v += "<tr><td class='center'>"+k+"<input type='hidden' class='count' value='"+item_id+"' ><input type='hidden' class='roption' value='"+e.option_id+"' ><input type='hidden' class='pro_id' value='"+e.item_id+"' ></td><td>"+e.code+"</td><td>"+e.label+"</td><td>"+e.variant+"</td><td class='center'>"+formatQuantity(qty)+"</td><td style='font-size:58px; font-weight:bold; color:red;' class='center pro_qty'>"+e.qty+"</td></tr>";
					k++;
				});
				$(".tbody").html(v);
			}
		}
		
		$('#reset').click(function (){
			if (__getItem('poitems')) {
				__removeItem('poitems');
			}
			$('#modal-loading').show();
            location.reload();
		});
		
		$("document").ready(function(){
			$("#excel").click(function(e){
				e.preventDefault();
				var proId  = [];
				
				$('.pro_id').each(function(i){
					proId[i] = $(this).val();					
				});
				
				var proQty = [];				
				$('.pro_qty').each(function(i){
					proQty[i] = $(this).text();
				});
				
				$.ajax({
					type: 'get',
					url: '<?= site_url('products/exportStock'); ?>',
					dataType: "json",
					data: {
						pro_id      : proId,
						warehouse_id: $("#powarehouse").val(),
						category_id : $("#pocategories").val(),
						pro_qty     : proQty
					},
					success: function (data) {
						JSONToCSVConvertor(data, "", true);
					}
				});
			});
			
			function JSONToCSVConvertor(JSONData, ReportTitle, ShowLabel) {
				var arrData = typeof JSONData != 'object' ? JSON.parse(JSONData) : JSONData;
				var qoh = '';
				var name = '';
				var cost = '';
				var price = '';
				var real_qty = '';
				var result = [];
				var a = 0 ;
				
				$.each(arrData, function (i, e) {
					result[a] = {'Product Code': e.code, 'Product Name':e.label, 'Variant':e.variant,'Expected':e.quantity, 'Counted':e.qty};
					a++;
				});	
				var arrData1 = typeof result != 'object' ? JSON.parse(result) : result;
				
				var CSV = '';    
				
				if (ShowLabel) {
					var row = "";
					for (var index in arrData1[0]) {
						row += index + ',';
					}

					row = row.slice(0, -1);
					
					CSV += row + '\r\n';
				}
				
				for (var i = 0; i < arrData1.length; i++) {
					var row = [];
					
					for (var index in arrData1[i]) {
						var elem = '"' + arrData1[i][index] + '"';
						row.push(elem);
					}

					row.slice(0, row.length - 1);
					CSV += row + '\r\n';
				}
				
				if (CSV == '') {        
					alert("Invalid data");
					return;
				}   
				
				//Generate a file name
				var fileName = "Stock_count_";
				//this will remove the blank-spaces from the title and replace it with an underscore
				fileName += ReportTitle.replace(/ /g,"_");   
				
				//Initialize file format you want csv or xls
				var uri = 'data:text/csv;charset=utf-8,' + escape(CSV);
				
				// Now the little tricky part.
				// you can use either >> window.open(uri);
				// but this will not work in some browsers
				// or you will not get the correct file extension    
				
				//this trick will generate a temp <a /> tag
				var link = document.createElement("a");    
				link.href = uri;
				
				//set the visibility hidden so it will not effect on your web-layout
				link.style = "visibility:hidden";
				link.download = fileName + ".csv";
				
				//this part will append the anchor tag and remove it after automatic click
				document.body.appendChild(link);
				link.click();
				document.body.removeChild(link);
			}
		});
		
	});
</script>


