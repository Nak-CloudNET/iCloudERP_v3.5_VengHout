<script>
//var isValid = true;
//$(window).load(function(){
	$(".referent_line").addClass('f-cus');
//});

$(".remove_line").on('click',function() {
    var row = $(this).closest('tr').focus();
    row.remove();
});
</script>

<div class="modal-dialog" style="width:80%;">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('add_large_salling_tax'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open_multipart("taxes/large_combine_tax", $attrib); ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>
			<div class="table-responsive">
                <table id="CompTable" cellpadding="0" cellspacing="0" border="0"
                       class="table table-bordered table-hover table-striped">
                    <thead>
                    <tr>
                        <th style="width:30%;"><?= $this->lang->line("date"); ?></th>
                        <th style="width:30%;"><?= $this->lang->line("reference_no"); ?></th>
                        <th style="width:30%;"><?= $this->lang->line("remark"); ?></th>
                        <th style="width:30%;"><?= $this->lang->line("description"); ?></th>
						<th style="width:15%;"><?= $this->lang->line("amount"); ?></th>
                        <th style="width:15%;"><?= $this->lang->line("vat"); ?></th>
                        <th style="width:15%;"><?= $this->lang->line("total_amount"); ?></th>			
						<th style="width:15%;display:none;"><?= $this->lang->line("amount_declare"); ?></th>
						<th style="width:15%;display:none;"><?= $this->lang->line("vat_declare"); ?></th>
						<th style="width:15%;display:none;"><?= $this->lang->line("total_amount_declare"); ?></th>
						<th style="width:15%;display:none;"><?= $this->lang->line("tax_ref_no"); ?></th>
						<th style="width:10%;"><?= $this->lang->line("action"); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($combine_tax)) {
                        foreach ($combine_tax->result() as $combine_taxes) { ?>
                            <tr class="row<?= $combine_taxes->id ?>">
                                <td><?= $combine_taxes->date; ?></td>
                                <td><?= $combine_taxes->reference_no; ?></td>  
								<td>
								<?php
								if($combine_taxes->sale_type==1){
									echo lang('taxable_sale');
								}elseif($combine_taxes->sale_type==2){
									echo lang('non_taxable_sale');
								}elseif($combine_taxes->sale_type==3){
									echo lang('export');   
								}else{
									echo lang('can_not_define_type!');   
								}
									
								?>
								</td>	
								<td>
									<?=$combine_taxes->note; ?>
								</td>
								<td class="balance">
								<input type="hidden" name="order_tax[]" class="order_tax" value="<?=$combine_taxes->order_tax; ?>" />
								<input type="hidden" name="order_tax_id[]" class="order_tax" value="<?=$combine_taxes->order_tax_id; ?>" />
								<input type="hidden" name="tax_type[]" class="tax_type" value="<?=$combine_taxes->sale_type; ?>" />
								<input type="hidden" name="note[]" class="tax_type" value="<?=$combine_taxes->note; ?>" />
								
								<?php $amount=($combine_taxes->shipping+$combine_taxes->total)-$combine_taxes->order_discount; 
								echo $this->erp->formatMoney($amount); 
								?>
								</td>
								<td class="vat">	
									<?php
									/*$tax_type=$combine_taxes->sale_type;
									$vat=$combine_taxes->total_tax; 
									if($combine_taxes->sale_type==1 && $vat<=0){
										echo ($amount/1.1)*(0.1);
										$vat=($amount/1.1)*(0.1);
									}elseif($combine_taxes->sale_type==1 && $vat>0){
									echo $this->erp->formatMoney($combine_taxes->total_tax); 
										$vat=$this->erp->formatMoney($combine_taxes->total_tax); 
									}else{
										echo $this->erp->formatMoney(0);
										$vat= $this->erp->formatMoney(0);
									}*/
									$vat= $combine_taxes->order_tax;
									echo $vat;
									?>
									
									
								</td>
								
								<td class="text-right">
								
									<?= $this->erp->formatMoney($combine_taxes->grand_total);?>
									<input type="hidden" name="total_amountbox[]" class="total_amountbox" value="<?= $this->erp->formatMoney($exchange_rate->rate); ?>" />
								
								</td>
								<td class="" style="display:none;">
									<?php
                                    if($combine_taxes->order_tax_id!=1){
										if($combine_taxes->amount_declare){
											$amount=$combine_taxes->amount_declare;
										}
										echo '<input type="text" name="amount_declare[]" class="amount_declare form-control" style="width:150px" value="'.$amount.'">	';							
									}elseif($combine_taxes->order_tax_id==1){
										if($combine_taxes->sale_type==1){
										$gtotal=$combine_taxes->grand_total;
										$vat_declare= (($gtotal/1.1)*0.1);									
										if($combine_taxes->amount_declare){
											$amount=$combine_taxes->amount_declare;
										}else{
											$amount=$gtotal-$vat_declare;
										}
										echo '<input type="hidden" name="amount_declare[]" class="amount_declare form-control" style="width:150px" value="'.$amount.'">	';							
									
										echo '<div class="amount_declare_use_for_no_vat">'.$this->erp->formatMoney($amount).'</div>';
										}else{
												if($combine_taxes->amount_declare){
											$amount=$combine_taxes->amount_declare;
										}
										echo '<input type="text" name="amount_declare[]" class="amount_declare form-control" style="width:150px" value="'.$amount.'">	';							
									
										}
									}
									?>
								</td>
								<td  class="vat_declare" style="display:none;">
									<?php
									if($combine_taxes->vat_declare){
										echo $this->erp->formatMoney($combine_taxes->vat_declare);
										$vat_declare=$combine_taxes->vat_declare;
									}else{
										if($combine_taxes->order_tax_id==1&&sale_type==1){
											$gtotal=$combine_taxes->grand_total;
											$vat_declare= (($gtotal/1.1)*0.1);
											echo $this->erp->formatMoney($vat_declare);
										}elseif($combine_taxes->sale_type==2){
											$vat_declare=0;
											echo $this->erp->formatMoney($vat_declare);
										}elseif($combine_taxes->sale_type==3){
											$vat_declare=0;
											echo $this->erp->formatMoney($vat_declare);
										}
										else{
											$vat_declare=$amount*0.1;
											echo $this->erp->formatMoney($vat_declare);
										}
										}
									?>
									
								</td>
								<td class="total_amount_declare" style="display:none;">
								<?php
									if($combine_taxes->amount_declare){
										$total_amount_declare=$combine_taxes->amount_declare+$combine_taxes->vat_declare;
									}else{
										$total_amount_declare=$amount+$vat_declare;
									}
								
									
								//	echo $total_amount_declare;
									if($combine_taxes->order_tax_id==1 && $combine_taxes->sale_type==1){
										echo '<input type="text" name="total_amount_declare[]" class="total_amount_declare_input form-control" style="width:150px" value="'.$this->erp->formatMoney($total_amount_declare).'">	';							
									}else{
										echo $this->erp->formatMoney($total_amount_declare);
									}
								?>
								</td>
                                <td class="col-xs-3" style="display:none;">
									<input type="hidden" name="vatbox_declare[]" class="vatbox_declare form-control" value="<?=$vat_declare;?>">
									<input type="hidden" name="sale_id[]" class="form-control" value="<?= $combine_taxes->id ?>" id="refereno">
									<input type="hidden" name="warehouse_id[]" class="form-control" value="<?= $combine_taxes->warehouse_id ?>">
									
									<input type="hidden" name="ordertax_id[]" class="form-control" value="<?= $combine_taxes->order_tax_id ?>">
									<input type="hidden" name="customer_id[]" class="form-control" value="<?= $combine_taxes->customer_id ?>">
									
									
									<input type="hidden" name="vatbox[]" class=" form-control" value="<?=$vat;?>">
									
									
									<input type="hidden" name="amount[]" class="form-control amount" value="<?php
									 if($combine_taxes->sale_type==1 ){
										 echo $amount;
									 }
									?>">
									<input type="hidden" name="non_taxable[]" class="non_taxable form-control" value="<?php
									 if($combine_taxes->sale_type==2 ){
										 echo $amount;
									 }
									?>">
									
									
									<input type="hidden" name="export[]" class="export form-control" value="<?php 
									 if($combine_taxes->sale_type==3 ){
										 echo $amount;
									 }
									?>">
									
									
									<input type="hidden" name="customer[]" class="form-control" value="<?= $combine_taxes->customer ?>">
                                    <input type="text" name="referent_line[]" class="referent_line form-control" style="width:150px" value="<?= $combine_taxes->tax_ref; ?>">
                                </td>
								<td>
									<!--<input type="button" class="btn btn-primary remove_line" value="Delete"/>-->
									<button type="button" class="btn btn-primary remove_line"><i class="fa fa-trash-o"></i></button>
								</td>
                            </tr>
                        <?php }
                    } else {
                        echo "<tr><td colspan='4'>" . lang('no_data_available') . "</td></tr>";
                    } ?>
                    </tbody>
                </table>
            </div>
			
			<?php if ($Owner || $Admin) { ?>
				<div class="form-group" style="display:none !important;">
					<?= lang("biller", "biller"); ?>
					<?php
					foreach ($billers as $biller) {
						$bl[$biller->id] = $biller->company != '-' ? $biller->company : $biller->name;
					}
					echo form_dropdown('biller', $bl, (isset($_POST['biller']) ? $_POST['biller'] : $pos_settings->default_biller), 'class="form-control" id="posbiller" required="required"');
					?>
				</div>
			<?php } else {
				$biller_input = array(
					'type' => 'hidden',
					'name' => 'biller',
					'id' => 'posbiller',
					'value' => $this->session->userdata('biller_id'),
				);

				echo form_input($biller_input);
			}
			?> 

            <div class="row" style="display:none;">
                <?php if ($Owner || $Admin) { ?>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <?= lang("date", "date"); ?>
                            <?= form_input('date', (isset($_POST['date']) ? $_POST['date'] : ""), 'class="form-control datetime" style="pointer-events: none;" id="date" required="required"'); ?>
                        </div>
                    </div>
                <?php } ?>
               
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="modal-footer">
            <?php echo form_submit('add_tax', lang('add_tax'), 'class="btn btn-primary" id="add_submit"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<script type="text/javascript" src="<?= $assets ?>js/custom.js"></script>
<script type="text/javascript" charset="UTF-8">
    $.fn.datetimepicker.dates['erp'] = <?=$dp_lang?>;
</script>
<?= $modal_js ?>
<script type="text/javascript" charset="UTF-8">
    $(document).ready(function () {
		
	

	$('.amount_tax').keyup(function() {
		var parent = $(this).parent().parent();
		var us_dolar = $(this).val()-0;
		var kh_rate = parent.find('.amount_declear').attr('rate')-0;
		var amount_declear = us_dolar * kh_rate;
		parent.find('.amount_declear').text(formatMoney(amount_declear));
		parent.find('.amount_decleared').val((amount_declear));
	});
		
	$("#add_submit").on('click',function(){
		var i=0;
		$('.referent_line').each(function(){
			i++
		});
		
		if(i<=0){
			alert("No data added");
			return false;
		}else{
			
		}	
	});
		
	function removeCommas(str) {
		while (str.search(",") >= 0) {
			str = (str + "").replace(',', '');
		}
		return Number(str);
	}
	$('.total_amount_declare_input').keyup(function() {
		var parent = $(this).parent().parent();
		var total_amount_declare=parent.find('.total_amount_declare_input').val();
			var vat= formatMoney((total_amount_declare/1.1)*0.1);
			var amount = total_amount_declare-vat;
			parent.find('.amount_declare_use_for_no_vat').text(amount);
			parent.find('.amount_declare').val(amount);
			parent.find('.vat_declare').text(vat);
			parent.find('.vatbox_declare').val(vat);
			
	});
		
	$('.amount_declare').keyup(function() {
		var parent = $(this).parent().parent();
		var tax_type=parent.find('.tax_type').val();
		var order_tax=parent.find('.order_tax').val();
		var order_tax_id=parent.find('.order_tax_id').val();
		var amount=$(this).val();
		
		//alert(tax_type+' | '+order_tax+' | '+order_tax_id+' | '+amount);
		if(tax_type==1){
			//alert('Able');
			var vat= formatMoney((amount)*0.1);
			parent.find('.vat_declare').text(vat);
			parent.find('.vatbox_declare').val(vat);
		}
		if(tax_type!=1){
			//alert('ex and NON');
			var vat=formatMoney(0);
			parent.find('.vat_declare').text(vat);
			parent.find('.vatbox_declare').val(vat);
		}
		var total_amount_declare=parseFloat(vat)+parseFloat(amount);;
		parent.find('.total_amount_declare').text(formatMoney(total_amount_declare));
		parent.find('.total_amountbox_declare').val((total_amount_declare));

	});
	
	
	
	if (!__getItem('date')) {
            $("#date").datetimepicker({
                format: site.dateFormats.js_ldate,
                fontAwesome: true,
                language: 'erp',
                weekStart: 1,
                todayBtn: 1,
                autoclose: 1,
                todayHighlight: 1,
                startView: 2,
                forceParse: 0
            }).datetimepicker('update', new Date());
        }
        $(document).on('change', '#date', function (e) {
            __setItem('date', $(this).val());
        });
        if (sldate = __getItem('date')) {
            $('#date').val(sldate);
        }
	
	
});
</script>
