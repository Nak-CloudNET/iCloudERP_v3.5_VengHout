<div class="box">
   <div class="box-header">
      <h2 class="blue"><i class="fa fa-list-ol"></i><?= lang('stock_count'); ?></h2>
   </div>
   <div class="box-content">
		<div class="row">
        <div class="col-lg-12">
            <div id="pos">
				<div id="left-top">
                  <div style="position: absolute; <?= $Settings->rtl ? 'right:-9999px;' : 'left:-9999px;'; ?>"><?php echo form_input('test', '', 'id="test" class="kb-pad"'); ?></div>
                  <div class="no-print">
						<div class="well well-sm">
							<div class="col-sm-6">
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
							<div class="col-sm-6">
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
										<th width="3%" class='center'><input type="checkbox" /></th>
										<th><?= lang("num_"); ?></th>
										<th><?= lang("product"); ?></th>
										<th><?= lang("stock_in_hand"); ?></th>    
										<th><?= lang("quantity_count"); ?></th>
									</tr>
								</thead>
								<tbody class="tbody"></tbody>
							</table>
							<input type="hidden" name="table_no" class=" table_no" id="table_no" value="<?=(isset($arrSuspend[$sid]['suspend_not']) ? $arrSuspend[$sid]['suspend_not'] : '')?>"/>
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
						warehouse_id: $("#powarehouse").val(),
						customer_id: $("#poscustomer").val()
					},
					success: function (data) {
						response(data);
						$('#powarehouse').attr("disabled", true);
						$('#add_item').val("");
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
			
			if(item.id != poitems.id)
			{
				var item_id = item.item_id;
				if (poitems[item_id]) {
					poitems[item_id].qty = parseFloat(poitems[item_id].qty) + 1;
				} else {
					poitems[item_id] = item;
				}		
				__setItem('poitems', JSON.stringify(poitems));
				loadStorage();
			}
		}
		
		loadStorage();

		function loadStorage(){			
			var poitems = JSON.parse(__getItem('poitems'));	
			
			if(poitems != null)
			{
				var v = "";
				var k = 1;
				$.each(poitems, function (i,e) {
						var qty = e.quantity;
						v += "<tr><td class='center'><input type='checkbox' /></td><td class='center'>"+k+"</td><td>"+e.label+"</td><td class='center'>"+qty+"</td><td style='font-size:28px; font-weight:bold; color:red;' class='center'>"+e.qty+"</td></tr>";
					k++;
				});			
				$(".tbody").html(v);
			}
		}
		
		$("document").ready(function(){
			$("#excel").click(function(e){
				
				e.preventDefault();
				var poitems = JSON.parse(__getItem('poitems'));
				//window.location.href="<?= site_url('products/getStocktoexcel/xls/') ?>" + '/'+ poitems;
				//return false;
				
				$.ajax({
					url : '<?= site_url('products/stock_count_excel') ?>',
					type : 'GET',
					dataType : 'json',
					data : { excel : poitems  }, 
					contentType : 'application/json; charset=utf-8',
					success:function(d){
						var data = d;
						if(data == '')
							return;
						
						JSONToCSVConvertor(data, "Stock Count", true);
					}
				});
			});
			
			function JSONToCSVConvertor(JSONData, ReportTitle, ShowLabel) {
				//If JSONData is not an object then JSON.parse will parse the JSON string in an Object
				var arrData = typeof JSONData != 'object' ? JSON.parse(JSONData) : JSONData;
				
				var qoh = '';
				var name = '';
				var cost = '';
				var price = '';
				var real_qty = '';
				var result = [];
				var a = 0 ;
				$.each(arrData, function (i, e) {
					result[a] = {'pro_name': e.label, 'quantity':e.quantity, 'qty':e.qty};
					a++;
				});	
				var arrData1 = typeof result != 'object' ? JSON.parse(result) : result;
				
				var CSV = '';    
				//Set Report title in first row or line
				
				CSV += ReportTitle + '\r\n\n';

				//This condition will generate the Label/Header
				if (ShowLabel) {
					var row = "";
					
					//This loop will extract the label from 1st index of on array
					for (var index in arrData1[0]) {
						
						//Now convert each value to string and comma-seprated
						row += index + ',';
					}

					row = row.slice(0, -1);
					
					//append Label row with line break
					CSV += row + '\r\n';
				}
				
				//1st loop is to extract each row
				for (var i = 0; i < arrData1.length; i++) {
					var row = "";
					
					//2nd loop will extract each column and convert it in string comma-seprated
					for (var index in arrData1[i]) {
						row += '"' + arrData1[i][index] + '",';
					}

					row.slice(0, row.length - 1);
					
					//add a line break after each row
					CSV += row + '\r\n';
				}

				if (CSV == '') {        
					alert("Invalid data");
					return;
				}   
				
				//Generate a file name
				var fileName = "MyReport_";
				//this will remove the blank-spaces from the title and replace it with an underscore
				fileName += ReportTitle.replace(/ /g,"_");   
				
				//Initialize file format you want csv or xls
				var uri = 'data:text/csv;charset=utf-8,' + escape(CSV);
				
				// Now the little tricky part.
				// you can use either>> window.open(uri);
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


