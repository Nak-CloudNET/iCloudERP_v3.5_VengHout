<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('add_expense'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open_multipart("purchases/add_expense", $attrib); ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>

            <div class="form-group">
                <?= get_dropdown_project('biller', 'posbiller'); ?>
            </div>

            <?php if ($Owner || $Admin) { ?>

                <div class="form-group">
                    <?= lang("date", "date"); ?>
                    <?= form_input('date', (isset($_POST['date']) ? $_POST['date'] : ""), 'class="form-control datetime" id="date_expense" required="required"'); ?>
                </div>
            <?php } ?>
			<!--
            <div class="form-group">
                <?= lang("reference", "reference"); ?>
                <?= form_input('reference', (isset($_POST['reference']) ? $_POST['reference'] : $exnumber), 'class="form-control tip" id="reference"'); ?>
            </div>
			-->
			
			<div class="form-group">
				<label class="control-label" for="customer_invoice"><?= lang("customer_name"); ?></label>
				<?php
				$cust["0"] = "None";
				foreach ($customers as $customer) {
					$cust[$customer->id] = $customer->text;
				}
				echo form_dropdown('customer_invoice', $cust, (isset($_POST['customer_invoice']) ? $_POST['customer_invoice'] : ""), 'class="form-control" id="customer_invoice" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("customer_invoice") . '"');
				?>
			</div>
			
			<div class="form-group">
				<?= lang("invoice_no", "invoice_no") ?>
				<?php echo form_input('customer_invoice_no', '', 'class="form-control" id="customer_invoice_no"  placeholder="' . lang("select") . " " . lang("customer_invoice") . '" '); ?>
			</div>
			
			<div class="form-group">
				<?= lang("reference_no", "slref"); ?>
				<div style="float:left;width:100%;">
					<div class="form-group">
						<div class="input-group">  
								<?php echo form_input('reference_no', $reference_no?$reference_no:"",'  class="form-control input-tip" id="slref"'); ?>
								<input type="hidden"  name="temp_reference_no"  id="temp_reference_no" value="<?= $reference_no?$reference_no:"" ?>" />
							<div class="input-group-addon no-print" style="padding: 2px 5px;background-color:white;">
								<input type="checkbox" name="ref_status" id="ref_st" value="1" style="margin-top:3px;">
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<div class="form-group">
				<?= lang("category_expense", "chart_account"); ?>
				<?php
				
				$acc_section = array(""=>"");
				foreach($chart_accounts as $section){
					$acc_section[$section->accountcode] = $section->accountcode.' | '.$section->accountname;
				}
					echo form_dropdown('account_section', $acc_section, '' ,'id="account_section" class="form-control input-tip select" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("Account") . ' ' . $this->lang->line("Section") . '" required="required" style="width:100%;" ');
				?>
			</div>
			
			<div class="form-group">
				<?= lang("paid_by", "paid_by"); ?>
				<?php
				
				$acc_section = array(""=>"");
				foreach($paid_by as $section){
					$acc_section[$section->accountcode] = $section->accountcode.' | '.$section->accountname;
				}
					echo form_dropdown('paid_by', $acc_section, '' ,'id="paid_by" class="form-control input-tip select" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("paid_by") . '" required="required" style="width:100%;" ');
				?>
			</div>

            <div class="form-group">
                <?= lang("amount", "amount"); ?> (USD)
                <input name="amount" type="text" id="amount" value="" class="pa form-control kb-pad amount number_only" 
                       required="required"/>
            </div>
			
			<?php
				foreach($currency as $money){
					if($money->code == 'USD'){

					}else{
			?>
				<div class="form-group">
					<?= lang("amount", "amount").($money->code == 'USD' ? ' (USD)' : ' (Rate: USD1 = '.$money->code.' '.number_format($money->rate).')'); ?>
					<input name="other_amount[]" type="text" id="<?=$money->code;?>" value="" rate="<?=$money->rate?>" class="pa form-control kb-pad amount_other"/>
				</div>
			<?php
					}
				}
			?>
			
            <div class="form-group">
                <?= lang("attachment", "attachment") ?>
                <input id="attachment" type="file" name="userfile" data-show-upload="false" data-show-preview="false"
                       class="form-control file">
            </div>

            <div class="form-group">
                <?= lang("note", "note"); ?>
                <?php echo form_textarea('note', (isset($_POST['note']) ? $_POST['note'] : ""), 'class="form-control" id="note"'); ?>
            </div>

        </div>
        <div class="modal-footer">
            <?php echo form_submit('add_expense', lang('add_expense'), 'class="btn btn-primary"'); ?>
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

	$("#slref").attr('readonly', true);
	$('#ref_st').on('ifChanged', function() {
		
	  if ($(this).is(':checked')) {
		// $("#slref").prop('disabled', false);
		$("#slref").attr('readonly', false);
		$("#slref").val("");
	  }else{
		$("#slref").prop('disabled', true);
		var temp = $("#temp_reference_no").val();
		$("#slref").val(temp);
		
	  }
	});

	$("#customer_invoice_no").select2("destroy").empty().attr("placeholder", "<?= lang('select_customer_invoice') ?>").select2({
		placeholder: "<?= lang('select_customer_invoice') ?>", data: [
			{id: '', text: 'None'},
			<?php foreach($invoices as $invoice) { ?>
				{id: '<?= $invoice->id ?>', text: '<?= $invoice->text ?>'},
			<?php } ?>
		]
    });
	
	$("#customer_invoice").change(function()
	{
	 var v = $(this).val();
            $('#modal-loading').show();
            if (v) {
                $.ajax({
                    type: "get",
                    async: false,
                    url: "<?= site_url('account/getCustomerInvoices') ?>/" + v,
                    dataType: "json",
                    success: function (scdata) {
						
                        if (scdata != null) {
                            $("#customer_invoice_no").select2("destroy").empty().attr("placeholder", "<?= lang('select_customer_invoice') ?>").select2({
                                placeholder: "<?= lang('select_customer_to_load') ?>",
                                data: scdata
                            });
                        }else{
							$("#customer_invoice_no").select2("destroy").empty().attr("placeholder", "<?= lang('select_customer_invoice') ?>").select2({
                                placeholder: "<?= lang('select_customer_to_load') ?>",
                                data: 'not found'
                            });
						}
                    },
                    error: function () {
                        bootbox.alert('<?= lang('ajax_error') ?>');
                        $('#modal-loading').hide();
                    }
                });
            } else {
                $("#customer_invoice_no").select2("destroy").empty().attr("placeholder", "<?= lang('select_category_to_load') ?>").select2({
                    placeholder: "<?= lang('select_customer_to_load') ?>",
                    data: [{id: '', text: '<?= lang('select_customer_to_load') ?>'}]
                });
            }
       $('#modal-loading').hide();
	
	});	
	
    $(document).ready(function () {		
        $.fn.datetimepicker.dates['erp'] = <?=$dp_lang?>;
        $("#date_expense").datetimepicker({
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
				
		function formatDecimals(x) {
			return parseFloat(parseFloat(x).toFixed(7));
		}
		var code = 0;
		var value = 0;
		var rate = 0;
		function autotherMoney(value){
            $(".amount_other").each(function(){
                var rate = $(this).attr('rate');
				if(value != 0){
					$(this).val(formatDecimals(value*rate));
				}else{
					$(this).val('0');
				}
            });
        }
		
        function autoMoney(value, rate){
        	$(".amount_other").each(function(){
				if(value != 0){
					$('input[name="amount"]').val(formatDecimals(value / rate));
				}else{
					$('input[name="amount"]').val('0');
				}
            });
        }
		
		$('input[name="amount"]').live('change keyup paste',function(){
			value = $(this).val();
			autotherMoney(value);
		});

		$('input[name="other_amount[]"]').live('change keyup paste',function(){
			value = $(this).val();
			rate = $(this).attr('rate');
			var val = value / rate;
			autoMoney(value, rate);
			autotherMoney(val);
		});
    });
</script>
