<style>
.error{
	color: #ef233c;
}
.margin-b-5{
	margin-bottom: 0;
}
</style>
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('edit_journal'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => "editJournal");
        echo form_open_multipart("account/updateJournal", $attrib); ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>
		<div class="row">
			<?php
			$description = '';
			$tmp_desc = '';
			$biller_id = '';
			$k = 0;
            if(isset($journals)){
                foreach($journals as $journal1){
                    if($journal1->description != ""){
						if($k == 0) {
							$tmp_desc = $journal1->description;
							$description = $journal1->description;
						}
						if($journal1->description != $tmp_desc) {
							$description = '';
						}
						$biller_id = $journal1->biller_id;
                    }
					$k++;
                }
            }
			
			?>
		
			<div class="col-md-12">
				<div class="col-md-4">
					<div class="form-group">
						<?= lang("date", "sldate"); ?>
						<?php echo form_input('date', (isset($_POST['date']) ? $_POST['date'] : date('d/m/Y h:i', strtotime($journal1->tran_date))), 'class="form-control input-tip datetime" id="sldate" required="required"'); ?>
					</div>
				</div>
				
				<div class="col-md-4">
					<div class="form-group">
						<?= lang("reference_no", "reference_no"); ?>
						<?php echo form_input('reference_no', $journal1->reference_no, 'class="form-control" id="reference_no" '); ?>
						<input type="hidden"  name="temp_reference_no"  id="temp_reference_no" value="<?= $journal1->reference_no ?>" />
					</div>
				</div>
				
				<div class="col-md-4">
					<div class="form-group">
						<label class="control-label" for="biller"><?= lang("biller"); ?></label>
						<?php
						$bl[""] = "";
						foreach ($billers as $biller) {
							$bl[$biller->id] = $biller->company != '-' ?$biller->code .'-'. $biller->company : $biller->name;
						}
						echo form_dropdown('biller_id', $bl, ($biller_id ? $biller_id : $journal1->biller_id), 'class="form-control" id="biller" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("biller") . '" required="required"');
						?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="col-md-4">
							<div class="form-group">
								<label class="control-label" for="type"><?= lang("Type"); ?></label>
								<?php
								$type_l["0"] = "None";
								foreach ($type as $type_r) {
									$type_l[$type_r->id] = $type_r->name != '-' ? $type_r->name : $type_r->name;
								}
									$type_l['emp']       = "Employee";
								echo form_dropdown('type', $type_l, ($journal1->created_type ? $journal1->created_type : ""), 'class="form-control" id="type" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("type") . '" required="required"');
								?>
							</div>
						</div>
						
						<div class="col-md-4">
								<?= lang("Name", "Name") ?>
								<?php if ($Owner || $Admin) { ?><div class="input-group"><?php } ?>
									<?php
									if ($Owner || $Admin ) { 
										echo form_input('name', $journal1->created_name, 'class="form-control" id="name"  placeholder="' . lang("select_name") . '" required="required"');
									?>
								
									<div class="input-group-addon no-print" style="padding: 2px 5px;"><a
											href="<?= site_url('system_settings/add_subcategory'); ?>" id="add-supplier"
											class="external" data-toggle="modal" data-target="#myModal"><i
												class="fa fa-2x fa-plus-circle" id="addIcon"></i></a></div>
								</div>
								<?php }else{
									echo form_input('name',  $journal1->created_name, 'class="form-control" id="name"  placeholder="' . lang("select_name") . '"');
								} ?>
						</div>
					</div>
					<div class="col-md-12">
						<div class="col-md-4">
							<div class="form-group">
								<label class="control-label" for="customer_invoice"><?= lang("customer_name"); ?></label>
								<?php
								$cust["0"] = "None";
								foreach ($customers as $customer) {
									$cust[$customer->id] = $customer->text;
								}
								echo form_dropdown('customer_invoice', $cust, ($journal1->customer_id ? $journal1->customer_id : ""), 'class="form-control" id="customer_invoice" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("customer_invoice") . '"');
								?>
							</div>
						</div>
						<div class="col-md-4">
							<?= lang("invoice_no", "customer_invoice_no") ?>
							<?php echo form_input('customer_invoice_no', $journal1->sale_id, 'class="form-control" id="customer_invoice_no"  placeholder="' . lang("select") . " " . lang("customer_invoice") . '" '); ?>
						</div>
					</div>
				</div>
				
				<div class="col-md-3"></div>
				
			</div>
			<div class="col-md-12">
				<div class="col-md-12">
					<div class="form-group">
						<?= lang("description", "description") ?>
						<?= form_textarea('description', strip_tags($description), 'class="form-control" id="description" required="required" '); ?>
					</div>
				</div>
				<div class="col-md-1"></div>
			</div>
			
			<div class="col-md-1">
				<div class="form-group">
					<button type="button" class="btn btn-primary" id="addDescription"><i class="fa fa-plus-circle"></i></button>
				</div>
			</div>
			<div class="row journalContainer">
				<div class="col-md-12">
					<div class="col-md-4"><div class="form-group margin-b-5"><?= lang("chart_account", "chart_account"); ?></div></div>
					<div class="col-md-3"><div class="form-group margin-b-5"><?= lang("note", "note"); ?></div></div>
					<div class="col-md-2"><div class="form-group margin-b-5"><?= lang("debit", "debit"); ?></div></div>
					<div class="col-md-2"><div class="form-group margin-b-5"><?= lang("credit", "credit"); ?></div></div>
				</div>
			<?php
			$n = 1;
            $debit = 0;
            $credit = 0;
			foreach($journals as $journal){
			?>
				<hr>
				<div class="col-md-12 journal-list">
					
					<div class="col-md-4">
						<div class="form-group company margin-b-5">	
							<?php
							$acc_section = array(""=>"");
							foreach($sectionacc as $section){
								$acc_section[$section->accountcode] = $section->accountcode.' | '.$section->accountname;
							}
								echo form_dropdown('account_section[]', $acc_section, $journal->account_code, 'id="account_section" class="form-control input-tip select" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("Account") . ' ' . $this->lang->line("Section") . '" required="required" style="width:100%;" ');
							?>
							<input type="hidden" name="tran_id[]" value="<?= $journal->tran_id ?>">
						</div>
					</div>
					
					<div class="col-md-3">
						<div class="form-group margin-b-5">	
							<?php echo form_input('note[]', ($description != '' ? '' : $journal->description), 'class="form-control note'. $n .'" id="note"'); ?>
						</div>
					</div>
					
					<div class="col-md-2">
						<div class="form-group margin-b-5">	
							<?php echo form_input('debit[]', $this->erp->formatDecimal($journal->debit), 'class="form-control text-right debit'. $n .' number_only" id="debit"'); ?>
						</div>
					</div>
					
					<div class="col-md-2">
						<div class="form-group margin-b-5">
							<?php echo form_input('credit[]', $this->erp->formatDecimal($journal->credit), 'class="form-control text-right credit'.$n .' number_only" id="credit"'); ?>
						</div>
					</div>
					<div class="col-md-1">
						<div class="form-group margin-b-5">
						<label><label>
							<button type="button" class="removefiles btn btn-danger">&times;</button>
						</div>
					</div>
					
				</div>

				<?php 
				$debit += $journal->debit;
				$credit += $journal->credit;
			
				$n++;
				} ?>
			</div>

				<div class="col-md-12" style="border-top:1px solid #CCC"></div>
                <div class="col-md-4">
                </div>
                <div class="col-md-3">
                </div>
				<div class="col-md-2">
					<div class="col-md-offset-9">
						<div class="form-group">
							<label id="calDebit"><?=$this->erp->formatMoney($debit)?></label>
						</div>
					</div>
				</div>
				
				<div class="col-md-2">
					<div class="col-md-offset-4">
						<div class="form-group">
							<label id="calCredit" style="margin-left:18px !important"><?=$this->erp->formatMoney($credit)?></label>
						</div>
					</div>
				</div>
			
			</div>
        </div>
        <div class="modal-footer">
            <?php echo form_submit('edit_journal', lang('edit_journal'), 'class="btn btn-primary" id="checkSave"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<?= $modal_js ?>
<script type="text/javascript">
	
	$("#customer_invoice_no").select2("destroy").empty().attr("placeholder", "<?= lang('select_customer_invoice') ?>").select2({
		placeholder: "<?= lang('select_cselect_name') ?>", data: [
			{id: '', text: 'None'},
			<?php foreach($invoices as $invoice) { ?>
				{id: '<?= $invoice->id ?>', text: '<?= $invoice->text ?>'},
			<?php } ?>
		]
	});
	
	$("#name").select2("destroy").empty().attr("placeholder", "<?= lang('select_name') ?>").select2({
		placeholder: "<?= lang('select_cselect_name') ?>", data: [
			{id: '', text: '<?= lang('select_select_name') ?>'}
		]
     });
	 
	$("#type").change(function()
	{
	 var v = $(this).val();
            $('#modal-loading').show();
            if (v) {
                $.ajax({
                    type: "get",
                    async: false,
                    url: "<?= site_url('account/getpeoplebytype') ?>/" + v,
                    dataType: "json",
                    success: function (scdata) {
						
                        if (scdata != null) {
                            $("#name").select2("destroy").empty().attr("placeholder", "<?= lang('select_name') ?>").select2({
                                placeholder: "<?= lang('select_name') ?>",
                                data: scdata
                            });
                        }else{
							$("#name").select2("destroy").empty().attr("placeholder", "<?= lang('select_name') ?>").select2({
                                placeholder: "<?= lang('select_name') ?>",
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
                $("#name").select2("destroy").empty().attr("placeholder", "<?= lang('select_name') ?>").select2({
                    placeholder: "<?= lang('select_name') ?>",
                    data: [{id: '', text: '<?= lang('select_name') ?>'}]
                });
            }
       $('#modal-loading').hide();
	
	}).trigger("change");
	
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
	
	}).trigger("change");
	
	var MaxInputs       = 30;
	var InputsWrapper   = jQuery(".journalContainer");
	var AddButton       = jQuery("#addDescription");
	
	var InputCount = jQuery(".journal-list");
	var x = (InputCount.length) + 1;
	
	var FieldCount=1;

	$(AddButton).click(function (e)
	{     
		if(x <= MaxInputs) 
		{ 
			FieldCount++; 
			
			var div = '<div class="col-md-12 journal-list divwrap'+FieldCount+'"">';
			div += '	<div class="col-md-4">';
			div += '			<div class="form-group company">';
			div += '				<select class="form-control input-tip select2" name="account_section[]" required="required">';
			div += '				<?php foreach($sectionacc as $section){ ?>';
			div += '					<option value="<?=$section->accountcode?>"><?=$section->accountcode . " | " . $section->accountname; ?></option>';
			div += '				<?php } ?>';
			div += '				</select>';
			div += '			</div>';
			div += '		</div>';
			
			div += '		<div class="col-md-3">';
			div += '			<div class="form-group">';
			div += '				<input type="text" name="note[]" value="" class="form-control note'+x+'" id="note"> ';
			div += '			</div>';
			div += '		</div>';
			
			div += '		<div class="col-md-2">';
			div += '			<div class="form-group">';
			div += '				<input type="text" name="debit[]" value="" class="form-control debit'+x+' number_only" id="debit"> ';
			div += '			</div>';
			div += '		</div>';
					
			div += '		<div class="col-md-2">';
			div += '			<div class="form-group">';
			div += '				<input type="text" name="credit[]" value="" class="form-control credit'+x+' number_only" id="credit"> ';
			div += '			</div>';
			div += '		</div>';
			div += '		<div class="col-md-1">';
			div += '			<label><button type="button" data="'+FieldCount+'" class="removefile btn btn-danger">&times;</button></label>';
			div += '		</div>';
			div += '	</div>';

			$(InputsWrapper).append(div);
			$("select").select2();
			x++;
		}
		return false;
	});

	$('.removefile').click(function(e){
		if( FieldCount == 1 ) {
			$(this).closest().find('.journal-list').remove();
			FieldCount--;
		}else{
			bootbox.alert('Journal must be at least two transaction!');
		}
		return false;
	});
	
	function AutoDebit(){
		var v_debit = 0;
		var i = 1;
		$('[name^=debit]').each(function(i, item) {
			v_debit +=  $(item).val()-0 || 0;
		});
		$("#calDebit").text(formatMoney(v_debit));
	}
	function AutoCredit(){
		var v_credit = 0;
		var j = 1;
		$('[name^=credit]').each(function(i, item) {
			v_credit +=  $(item).val()-0 || 0;
		});
		$("#calCredit").text(formatMoney(v_credit));
	}

	$(document).ready(function () {
		$('.removefiles').click(function(){
			var tr = $(this).parent().parent().parent().parent().parent();
			tr.remove();
			
			tr.remove();
			AutoDebit();
			AutoCredit();
			
			if($("#calDebit").text() != $("#calCredit").text()){
				$("#calDebit").addClass('error');
				$("#calCredit").addClass('error');
			}else{
				$("#calDebit").removeClass('error');
				$("#calCredit").removeClass('error');
			}
		});

		$('.removefile').live('click', function(){
			var divId 	= $(this).attr('data');
			$('.divwrap'+divId+'').remove();
			AutoDebit();
			AutoCredit();
			
			if($("#calDebit").text() != $("#calCredit").text()){
				$("#calDebit").addClass('error');
				$("#calCredit").addClass('error');
			}else{
				$("#calDebit").removeClass('error');
				$("#calCredit").removeClass('error');
			}
		});
		
		$('input[name="debit[]"], input[name="credit[]"]').live('change keyup paste',function(){	
			AutoDebit();
			AutoCredit();

			if($("#calDebit").text() != $("#calCredit").text()){
				$("#calDebit").addClass('error');
				$("#calCredit").addClass('error');
			}else{
				$("#calDebit").removeClass('error');
				$("#calCredit").removeClass('error');
			}
		});
		
		$("#checkSave").click(function(){
			
			var icheck = true;
			for(var i = 1; i <= x; i++) {
				if(parseFloat($('.debit'+i).val()-0) > 0 && parseFloat($('.credit'+i).val()-0) > 0) {
					icheck = false;
				}
			}
			if(!icheck) {
				alert("System doesn't allow you to input debit and credit in the same row!");
				return false;
			}
			
			var help = true;
			$('[name^=account_section]').each(function(i, item) {
				if(!$(item).val() || $(item).val() == '') {
					help = false;
				}
			});
			if(!help) {
				alert('Chart Account is required!');
				return false;
			}
			
			if($("#calDebit").text() != $("#calCredit").text()){
				alert('Your Debit Credit is difference ! \nPlease check your amount');
				return false;
			}
			
			if($("#calDebit").text() < 0 && $("#calCredit").text() < 0){
				alert('Your Debit Credit is difference ! \nPlease check your amount');
				return false;
			}
			
			if($("#biller option:selected").val() <= 0){
				alert('Project is required.');
				return false;
			}
			
		});

		$('#account_section').change(function () {
			$(".sub_textbox").show();
			$(".sub_combobox").hide();
            var v = $(this).val();
            $('#modal-loading').show();
            if (v) {
                $.ajax({
                    type: "get",
                    async: false,
                    url: "<?= site_url('account/getSubAccount') ?>/" + v,
                    dataType: "json",
                    success: function (scdata) {
                        if (scdata != null) {
                            $("#sub_account").select2("destroy").empty().attr("placeholder", "<?= lang('select_name') ?>").select2({
                                placeholder: "<?= lang('select_name') ?>",
                                data: scdata
                            });
                        }
                    },
                    error: function () {
                        bootbox.alert('<?= lang('ajax_error') ?>');
                        $('#modal-loading').hide();
                    }
                });
            }
            $('#modal-loading').hide();
        });
	});
	
	$('#account_section').select2({
            placeholder: "Select Categories",
            maximumSelectionSize: 3
     });
</script>
