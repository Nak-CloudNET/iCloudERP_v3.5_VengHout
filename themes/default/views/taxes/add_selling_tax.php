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
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('add_salling_tax'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open_multipart("taxes/combine_tax", $attrib); ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>
			<div class="table-responsive">
                <table id="CompTable" cellpadding="0" cellspacing="0" border="0"
                       class="table table-bordered table-hover table-striped">
                    <thead>
                    <tr>
                        <th style="width:20%;"><?= $this->lang->line("date"); ?></th>
                        <th style="width:20%;"><?= $this->lang->line("reference_no"); ?></th>
						<th style="width:15%;"><?= $this->lang->line("amount"); ?></th>
                        <th style="width:15%;"><?= $this->lang->line("amount_tax"); ?></th>
						<th style="width:20%;"><?= $this->lang->line("type"); ?></th>
                        <th style="width:15%;"><?= $this->lang->line("amount"); ?></th>
						<th style="width:15%;"><?= $this->lang->line("tax_ref_no"); ?></th>
						<th style="width:10%;"><?= $this->lang->line("action"); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($combine_tax)) {
                        foreach ($combine_tax->result() as $combine_taxes) { ?>
                            <tr class="row<?= $combine_taxes->id ?>">
                                <td><?= $combine_taxes->date; ?></td>
                                <td><?= $combine_taxes->reference_no; ?></td>                           
								<td class="balance"><?= $this->erp->formatMoney($combine_taxes->balance); ?></td>
								<td>
									<input type="text" name="amount_tax[]" class="amount_tax form-control"  value="<?=$this->erp->formatMoney($combine_taxes->total_tax);?>" /> 
								</td>
								<td>
                                    <?php
										$ptype["0"] = lang('none');
										$ptype["2"] = lang('non_taxable_sales');
										$ptype["1"] = lang('taxable_sales');
										$ptype["3"] = lang('export');                 
										echo form_dropdown('purchase_type[]', $ptype, '', 'id="sale_type" data-placeholder="' . lang("select") . ' ' . lang("sale_type") . '" class="form-control input-tip select" style="width:100%;"');
                                    ?>
								</td>
								<td class="text-right">
									<span name="amount_declear[]" class="amount_declear" id="amount_declear" rate="<?= $exchange_rate->rate ?>"> </span>
									<input type="hidden" name="amount_decleared[]" class="amount_decleared" value="<?= $this->erp->formatMoney($exchange_rate->rate); ?>" />
								</td>							
                                <td class="col-xs-3">
									<input type="hidden" name="sale_id[]" class="form-control" value="<?= $combine_taxes->id ?>" id="refereno">
									<input type="hidden" name="warehouse_id[]" class="form-control" value="<?= $combine_taxes->warehouse_id ?>">
									<input type="hidden" name="amount[]" class="form-control" value="<?= $combine_taxes->balance?>">
									<input type="hidden" name="ordertax_id[]" class="form-control" value="<?= $combine_taxes->order_tax_id ?>">
									<input type="hidden" name="customer[]" class="form-control" value="<?= $combine_taxes->customer ?>">
                                    <input type="text" name="referent_line[]" class="referent_line form-control" style="width:200px" required >
                                </td>
								<td>
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

            <div class="row">
                <?php if ($Owner || $Admin) { ?>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <?= lang("date", "date"); ?>
                            <?= form_input('date', (isset($_POST['date']) ? $_POST['date'] : date('d/m/Y')), 'class="form-control datetime" id="date" required="required"'); ?>
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
});
</script>
