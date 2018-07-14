<style>
	#tb1 tr th{
		        background-color: #428BCA;
				color: white;
				border-color: #357EBD;
				border-top: 1px solid #357EBD;
				text-align: center;
	}
	#tb2 tr th{
		       background-color: #428BCA;
				color: white;
				border-color: #357EBD;
				border-top: 1px solid #357EBD;
				text-align: center;
	}
	#tb2 th:nth-child(1) {
		width: 4%;
	}
	.error{
		color:red;
	}
	.error2{
		color:red;
	}
</style>
<div class="modal-dialog modal-lg">
    <div class="modal-content col-sm-12">
        <div class="modal-header col-sm-12">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="false"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('add_seperate'); ?></h4>
        </div>
       
        <div class="modal-body col-sm-12" >
				<p>Table : <?=$suspended_name->name?></p>
				<div class=" col-sm-8">
					<table id="tb1" class="table table-bordered table-hover table-striped">
						<tr>
							<th><input class="checkbox checkth2" type="checkbox" name="check"/></th>
							<th><?=lang('Product')?></th>
							<th><?=lang('price')?></th>
							<th><?=lang('price_kh')?></th>
							<th><?=lang('qty')?></th>
							<th><?=lang('discount')?></th>
							<th><?=lang('subtotal')?></th>
						</tr>
						<?php 
						$total = 0;
						if(is_array($suspended_items)){
							foreach($suspended_items as $row_item){
								?>
									<tr class="tr">
										<td><input class="checkbox checkft2" type="checkbox" name="check2[]" value="<?=$row_item->id?>"/></td>
										<td><?=$row_item->product_name?></td>
										<td style="text-align:center;"><?=$this->erp->formatMoney($row_item->unit_price)?><input style="width:80px;" type="hidden" class="form-control unit_price" name="unit_price[]" value="<?=$row_item->unit_price?>" /></td>
										<td style="text-align:center;"><?=$row_item->unit_price*$exchange_rate->rate?></td>
										<td><input style="width:80px;text-align:center;" type="text" class="form-control quantity" name="quantity[]" value="<?=$this->erp->formatDecimal($row_item->quantity)?>" />
										<input style="width:80px;" type="hidden" class="form-control quantity2" name="quantity2[]" value="<?=$this->erp->formatDecimal($row_item->quantity)?>" />
										</td>
										<td><?=$row_item->discount?><input style="width:80px;" type="hidden" class="form-control discount" name="discount[]" value="<?=$this->erp->formatDecimal($row_item->discount)?>" /></td>
										<td style="text-align:right;"><span class="subtotal"><?=$this->erp->formatMoney($row_item->subtotal)?></span></td>
									</tr>
								<?php
								$total+=$row_item->subtotal;
							}
						} 
						?>
						<tr>
							<td colspan="6" style="text-align:right;"><b>Total:</b></td>
							<td style="text-align:right;"><b><span class="total"><?=$this->erp->formatMoney($total)?></span></b></td>
						</tr>
					</table>
					<p class="error"></p>
				</div>
				<div class=" col-sm-4">
					<table id="tb2"  class="table table-bordered table-hover table-striped">
						<tr>
							<th>#</th>
							<th><?=lang('table')?></th>
							<th><?=lang('status')?></th>
						</tr>
						<?php 
						if(is_array($suspended)){
							foreach($suspended as $table){
								
						?>
						<tr>
							<td><input class="checkbox checkft3" type="radio" name="check3[]" value="<?=$table->id?>" /></td>
							<td><?=$table->name?></td>
							<td style="text-align:center;"> <?php if($table->status == 1){ echo "<span class='label label-danger'>busy</span>";}else{echo "<span class='label label-success'>free</span>";}?></td>
						</tr>
						<?php
						}
						} 
						?>
					</table>
					<p class="error2"></p>
				</div>
			
        </div>

        <div class="modal-footer col-sm-12">
				<button id="add_seperate" class="btn btn-primary"><?=lang('add_seperate')?></button>
        </div>
   
</div>
</div>

<?= $modal_js ?>
<script>
	Number.prototype.formatMoney = function(decPlaces, thouSeparator, decSeparator) {
    var n = this,
        decPlaces = isNaN(decPlaces = Math.abs(decPlaces)) ? 2 : decPlaces,
        decSeparator = decSeparator == undefined ? "." : decSeparator,
        thouSeparator = thouSeparator == undefined ? "," : thouSeparator,
        sign = n < 0 ? "-" : "",
        i = parseInt(n = Math.abs(+n || 0).toFixed(decPlaces)) + "",
        j = (j = i.length) > 3 ? j % 3 : 0;
    return sign + (j ? i.substr(0, j) + thouSeparator : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thouSeparator) + (decPlaces ? decSeparator + Math.abs(n - i).toFixed(decPlaces).slice(2) : "");
};
	$(document).ready(function() {
		
		$(document).on('ifChecked', '.checkth2', function(event) {
			$('.checkth2').iCheck('check');
			$('.checkft2').each(function() {
				$(this).iCheck('check');
			});
		});
		
		$(document).on('ifUnchecked', '.checkth2', function(event) {
			$('.checkth2').iCheck('uncheck');
			$('.checkft2').each(function() {
				$(this).iCheck('uncheck');
			});
		});
		
		$(document).on('ifUnchecked', '.checkft2', function(event) {
			$(this).iCheck('uncheck');
		});
		
		
		$(document).on('keyup keypress ', '.quantity', function () {
			var row = $(this).closest('tr');
			 var total = 0;
			 var tt = 0;
			 var unit_price = row.find('.unit_price').val()-0;
			  var quantity = $(this).val()-0;
			 var discount = row.find('.discount').val()-0;
			 total = (unit_price*quantity) - discount;
			 row.find('.subtotal').html(total.formatMoney(2,',','.'));
			 
			 $.each($('.subtotal'), function(){
					tt += $(this).html()-0;
			 });
			 
			$(".total").html(tt.formatMoney(2,',','.'));
			 
		}).trigger('change');	
		
		$("#add_seperate").click(function(e){
			e.preventDefault();
			var i = 0;
			var k = 0;
			var items = [];
			
			var tab = "";
			var hasCheck = false;
			var has = false;
			var qty = 0;
			var qty2 = 0;
			var b  = false;
			var k = false;
			var totalQuantity = 0;
			$.each($("input[name='check2[]']:checked"), function(){
				var str = $(this).closest('.tr');
				qty  = str.find('.quantity').val()-0;
				qty2  = str.find('.quantity2').val()-0;
				
				if(qty > qty2){
					b = true;
				}
				if(qty<=0){
					k = true;
				}
				
				items[i] = {'id':$(this).val(),'qty':qty,'qty2':qty2};
				i++;
				hasCheck = true;
				totalQuantity += qty;
			});
			
			
			if(k == true){
				$(".error").text("invalid number!");
				return false;
			}
			if(b == true){
				$(".error").text("New number cannot greater than old number!");
				return false;
			}
			if(hasCheck == false){
				//bootbox.alert('Please select!');
				$(".error").text("Please select items!");
				return false;
			}
			tab =  $("input[name='check3[]']:checked").val();
			//console.log(tab);
			if(!tab){
				$(".error2").text("Please select a table!");
				return false;
			}
			
			
			$.ajax({
				url: '<?= site_url('pos/add_seperate'); ?>',
				type : 'GET',
				dataType: "JSON",
				contentType : 'application/json;  charset=utf8',
				data: {
					items:items,
					tab:tab,
					id:'<?=$id?>',
					totaQty : totalQuantity
				},
				success: function (data) {
					if(data){
						window.location = window.location.pathname;
					}
				},error: function(err){
					alert(JSON.stringify(err));
				}
			});
				
			
		});
		
		
});
</script>