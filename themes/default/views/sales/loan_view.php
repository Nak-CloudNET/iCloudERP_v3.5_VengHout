<script>
    $(document).ready(function (e) {
		
		var oTable = $('#Loan_List').dataTable({
            "aaSorting": [[1, "asc"], [0, "desc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?=lang('all')?>"]],
            "iDisplayLength": 100,
            'bProcessing': true, 'bServerSide': true,
			'bFilter': false,
            'sAjaxSource': '<?=site_url('sales/list_loan_data/'.$sale_id)?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "<?=$this->security->get_csrf_token_name()?>",
                    "value": "<?=$this->security->get_csrf_hash()?>"
                });
                $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
            },
            'fnRowCallback': function (nRow, aData, iDisplayIndex) {
                var oSettings = oTable.fnSettings();
                nRow.id = aData[0];
				var action = $('td:eq(12)', nRow);
				if(aData[8] == 0 && aData[9] == 0) {
					   action.find('.add_m_payment').remove();
				}else {
					
					if(aData[9] == 0) {
						action.find('.add_m_payment').remove();
					}
				}
                return nRow;
            },
            "aoColumns": [{
                "bSortable": false,
                "mRender": checkbox
		}, {"mRender": textCenter}, {"mRender": currencyFormat_loan}, {"mRender": currencyFormat_loan}, {"mRender": currencyFormat_loan}, {"mRender": currencyFormat_loan}, {"mRender": fld}, {"mRender": currencyFormat_loan}, {"mRender": currencyFormat_loan}, {"mRender": currencyFormat_loan}, {"sClass": "owed"}, {"sClass": "pay_interest_status"}, {"bSortable": false}],
            "fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {
				
				
               
			   var interest = 0, principle = 0, payment = 0, paid = 0, discount = 0, balance = 0;
                for (var i = 0; i < aaData.length; i++) {
                    interest += parseFloat(aaData[aiDisplay[i]][2]);
                    principle += parseFloat(aaData[aiDisplay[i]][3]);
                    payment += parseFloat(aaData[aiDisplay[i]][4]);
					paid += parseFloat(aaData[aiDisplay[i]][7]);
					discount += parseFloat(aaData[aiDisplay[i]][8]);
					balance += parseFloat(aaData[aiDisplay[i]][9]);
                }
                var nCells = nRow.getElementsByTagName('th');
                nCells[2].innerHTML = currencyFormat_loan(parseFloat(interest));
                nCells[3].innerHTML = currencyFormat_loan(parseFloat(principle));
                nCells[4].innerHTML = currencyFormat_loan(parseFloat(payment));
                nCells[7].innerHTML = currencyFormat_loan(parseFloat(paid));
                nCells[8].innerHTML = currencyFormat_loan(parseFloat(discount));
                nCells[9].innerHTML = currencyFormat_loan(parseFloat(balance));
            },
			"fnInitComplete": function (oSettings, json) {
				alerts();
			}
        }).fnSetFilteringDelay().dtFilter([
            {column_number: 1, filter_default_label: "[<?=lang('Pmt No.');?>]", filter_type: "text", data: []},
            {column_number: 5, filter_default_label: "[<?=lang('balance');?>]", filter_type: "text", data: []},
            {column_number: 6, filter_default_label: "[<?=lang('date');?> (yyyy-mm-dd)]", filter_type: "text", data: []},
			
        ], "footer");

    });
	
	function alerts() {
		$('.bb .checkbox').each(function(){
			var parent = $(this).parent().parent().parent().parent();
			var paid = parent.children("td:nth-child(8)").html();
			var discount = parent.children("td:nth-child(9)").html();
			var balance  = parent.children("td:nth-child(10)").html();
	
			if(paid != 0  && balance!=0) {
				parent.css('background-color', '#d7edeb !important');

			}else if(paid != 0 || discount != 0 && balance==0){
				parent.css('background-color', '#d7edeb !important');
				$(this).attr('disabled',true);
			}
		});
	}
	
</script>
<style>
	.owed, .pay_interest_status{
		display:none;
	}
</style>		
<div class="modal-dialog modal-lg no-modal-header" style="width:80% !important;">
    <div class="modal-content">
        <div class="modal-body">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                <i class="fa fa-2x">&times;</i>
            </button>
            <?php if ($logo) { ?>
                <div class="text-center" style="margin-bottom:20px;">
                    <img src="<?= base_url() . 'assets/uploads/logos/' . $biller->logo; ?>"
                         alt="<?= $biller->company != '-' ? $biller->company : $biller->name; ?>">
                </div>
            <?php } ?>
            <div class="well well-sm">
                <div class="row bold">
                    <div class="col-xs-5">
                    <p class="bold">
                        <?= lang("ref"); ?>: <?= $inv->reference_no; ?><br>
                        <?= lang("date"); ?>: <?= $this->erp->hrld($inv->date); ?><br>
                        <?= lang("sale_status"); ?>: <?= lang($inv->sale_status); ?><br>
                        <?= lang("payment_status"); ?>: <?= lang($inv->payment_status); ?><br>
						<?= lang("customer"); ?>: <?= lang($cust_info->company); ?><br>
						<?= lang("address"); ?>: <?= lang($cust_info->address).','.lang($cust_info->city).', '.lang($cust_info->state); ?><br>
						<?= lang("tel"); ?>: <?= lang($cust_info->phone); ?><br>
						<?= lang("email"); ?>: <?= lang($cust_info->email); ?>
                    </p>
                    </div>
                    <div class="col-xs-7 text-right">
						<!--
                        <?php $br = $this->erp->save_barcode($inv->reference_no, 'code39', 70, false); ?>
                        <img src="<?= base_url() ?>assets/uploads/barcode<?= $this->session->userdata('user_id') ?>.png"
                             alt="<?= $inv->reference_no ?>"/>
							-->
                        <?php $this->erp->qrcode('link', urlencode(site_url('sales/view/' . $inv->id)), 2); ?>
                        <img src="<?= base_url() ?>assets/uploads/qrcode<?= $this->session->userdata('user_id') ?>.png"
                             alt="<?= $inv->reference_no ?>"/>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="clearfix"></div>
            </div>
			
            <div class="row" style="margin-bottom:15px;padding:0 15px;">
                <table class="table table-bordered table-hover table-striped print-table order-table">

                    <thead>
						<tr>
							<th width="5%"><?= lang("No"); ?></th>
							<th width="20%"><?= lang("item_code"); ?></th>
							<th width="35%"><?= lang("description"); ?></th>
							<th width="15%"><?= lang("unit_price"); ?></th>
							<th width="10%"><?= lang("quantity"); ?></th>
							<th width="15%"><?= lang("amount"); ?></th>
						</tr>
                    </thead>

                    <tbody>

                    <?php $n = 1;
                    $tax_summary = array();
					$total_amount = 0;
                    foreach ($list_items as $item):
						$total_amount += ($item->quantity * $item->unit_price);
					?>
                        <tr>
                            <td style="text-align:center; width:40px; vertical-align:middle;"><?= $n; ?></td>
                            <td style="vertical-align:middle;"><?=$item->product_code?></td>
                            <td style="width: 80px; vertical-align:middle;"><?=$item->product_name?></td>
                            <td style="text-align:right; width:100px;"><?=$this->erp->formatMoney($item->unit_price)?></td>
                            <td style="text-align:right; width:120px;"><?=$this->erp->formatQuantity($item->quantity)?></td>
							<td style="text-align:right; width:120px;"><?=$this->erp->formatMoney(($item->quantity * $item->unit_price))?></td>
                        </tr>
                    <?php
                        $n++;
                    endforeach;
					$loan_amount = $total_amount - $deposit - $down_payment;
					if($total_amount > $loan_amount){
                    ?>
						<tr>
							<td colspan="5" style="vertical-align:middle; text-align:right; font-weight:bold;"><?=lang("total_amount")?></td>
							<td style="vertical-alignA:middle; text-align:right; font-weight:bold;"><?=$this->erp->formatMoney($total_amount)?></td>
						</tr>
						<tr>
							<td colspan="5" style="vertical-align:middle; text-align:right; font-weight:bold;"><?=lang("deposit")?></td>
							<td style="vertical-alignA:middle; text-align:right; font-weight:bold;"><?=$this->erp->formatMoney($deposit)?></td>
						</tr>
						<tr>
							<td colspan="5" style="vertical-align:middle; text-align:right; font-weight:bold;"><?=lang("down_payment")?></td>
							<td style="vertical-alignA:middle; text-align:right; font-weight:bold;"><?=$this->erp->formatMoney($down_payment)?></td>
						</tr>
					<?php } ?>
						<tr>
							<td colspan="5" style="vertical-align:middle; text-align:right; font-weight:bold;"><?=lang("loan_amount")?></td>
							<td style="vertical-alignA:middle; text-align:right; font-weight:bold;"><?=$this->erp->formatMoney($loan_amount)?></td>
						</tr>
						<tr>
							<td colspan="5" style="vertical-align:middle; text-align:right; font-weight:bold;"><?=lang("interest_rate_per_month")?></td>
							<td style="vertical-alignA:middle; text-align:right; font-weight:bold;"><?=$this->erp->formatMoney(($current_interest->interest))?></td>
						</tr>
                    </tbody>
                    <tfoot>
						
					</tfoot>
				
            </div>
			
            <div class="table-responsive">
                <table id="Loan_List" class="table table-bordered">
                        <thead>
                        <tr>
                            <th style="min-width:30px; width: 30px; text-align: center;">
                                <!--<input class="checkbox checkft" type="checkbox" name="check"/>-->
                            </th>
                            <th><?php echo $this->lang->line("Pmt No."); ?></th>
                            <th><?php echo $this->lang->line("Interest"); ?></th>
                            <th><?php echo $this->lang->line("Principal"); ?></th>
                            <th><?php echo $this->lang->line("Total Payment"); ?></th>
                            <th><?php echo $this->lang->line("Balance"); ?></th>
                            <th><?php echo $this->lang->line("Payment Date"); ?></th>
                            <th><?php echo $this->lang->line("paid"); ?></th>
                            <th><?php echo $this->lang->line("discount"); ?></th>
                            <th><?php echo $this->lang->line("balance"); ?></th>
                            <th></th>
                            <th></th>
							<th style="width:85px;"><?= lang("actions"); ?></th>
                        </tr>
                        </thead>
                        <tbody class="bb">
                        <tr>
                            <td colspan="12"
                                class="dataTables_empty"><?php echo $this->lang->line("loading_data"); ?></td>
                        </tr>
                        </tbody>
                        <tfoot class="dtFilter">
                        <tr class="active">
                            <th style="min-width:30px; width: 30px; text-align: center;">
                                <!--<input class="checkbox checkft" type="checkbox" name="check"/>-->
                            </th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
							<th style="width:80px; text-align:center;"><?php echo $this->lang->line("actions"); ?></th>
                        </tr>
                        </tfoot>
                    </table>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <?php
                        if ($inv->note || $inv->note != "") { ?>
                            <div class="well well-sm">
                                <p class="bold"><?= lang("note"); ?>:</p>
                                <div><?= $this->erp->decode_html($inv->note); ?></div>
                            </div>
                        <?php
                        }
                        if ($inv->staff_note || $inv->staff_note != "") { ?>
                            <div class="well well-sm staff_note">
                                <p class="bold"><?= lang("staff_note"); ?>:</p>
                                <div><?= $this->erp->decode_html($inv->staff_note); ?></div>
                            </div>
                        <?php } ?>
                </div>

                <div class="col-xs-5 pull-right">
                    <div class="well well-sm">
                        <p>
                            <?= lang("created_by"); ?>: <?= $created_by->first_name . ' ' . $created_by->last_name; ?> <br>
                            <?= lang("date"); ?>: <?= $this->erp->hrld($inv->date); ?>
                        </p>
                        <?php if ($inv->updated_by) { ?>
                        <p>
                            <?= lang("updated_by"); ?>: <?= $updated_by->first_name . ' ' . $updated_by->last_name;; ?><br>
                            <?= lang("update_at"); ?>: <?= $this->erp->hrld($inv->updated_at); ?>
                        </p>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <?php if (!$Supplier || !$Customer) { ?>
                <div class="buttons">
                    <div class="btn-group btn-group-justified">
						
						<!-- Add Payment -->
						<div class="btn-group">
							<a href="#" data-toggle="modal" data-target="#myModal2" class="add_payment_list tip btn btn-primary pay" title="<?= lang('add_payment') ?>">
								<i class="fa fa-money"></i>
								<span class="hidden-sm hidden-xs"><?= lang('add_payment') ?></span>
							</a>
						</div>
						<div class="btn-group">
							<a href="#" data-toggle="modal" data-target="#myModal2" class="change_date tip btn btn-primary" title="<?= lang('change_date') ?>">
								<i class="fa fa-edit"></i>
								<span class="hidden-sm hidden-xs"><?= lang('change_date') ?></span>
							</a>
						</div>
						<div class="btn-group">
							<a href="#" data-toggle="modal" data-target="#myModal2" class="change_term tip btn btn-primary" title="<?= lang('change_term') ?>">
								<i class="fa fa-edit"></i>
								<span class="hidden-sm hidden-xs"><?= lang('change_term') ?></span>
							</a>
						</div>
					
                        <div class="btn-group">
                            <a href="<?= site_url('sales/view/' . $inv->id) ?>" class="tip btn btn-primary" title="<?= lang('view') ?>">
                                <i class="fa fa-file-text-o"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('view') ?></span>
                            </a>
                        </div>
                        <?php if ($inv->attachment) { ?>
                            <div class="btn-group">
                                <a href="<?= site_url('welcome/download/' . $inv->attachment) ?>" class="tip btn btn-primary" title="<?= lang('attachment') ?>">
                                    <i class="fa fa-chain"></i>
                                    <span class="hidden-sm hidden-xs"><?= lang('attachment') ?></span>
                                </a>
                            </div>
                        <?php } ?>
                        <div class="btn-group">
                            <a href="<?= site_url('sales/email/' . $inv->id) ?>" data-toggle="modal" data-target="#myModal2" class="tip btn btn-primary" title="<?= lang('email') ?>">
                                <i class="fa fa-envelope-o"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('email') ?></span>
                            </a>
                        </div>
	
                        <div class="btn-group">
                            <a href="<?= site_url('sales/pdf/' . $inv->id) ?>" class="tip btn btn-primary" title="<?= lang('download_pdf') ?>">
                                <i class="fa fa-download"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('pdf') ?></span>
                            </a>
                        </div>
                        <div class="btn-group">
                            <a class="tip btn btn-warning" title="<?= lang('print') ?>" onclick="window.print();">
                                <i class="fa fa-print"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('print') ?></span>
                            </a>
                        </div>
                       
                    </div>
                </div>
            <?php } ?>
			<div id="popup" ></div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready( function() {
	
	$(".add_payment_list").bind('click',function(){
		//alert($(".bb .checkbox:checked").length); return false;
		var total_payment = 0;
		var id = '';
		var paid_amount = '';
		var principle = '';
		var sale_id = <?= $sale_id; ?>;
		if($(".bb .checkbox:checked").length > 0){
			
			$(".bb .checkbox:checked").each(function(){	
				var tr = $(this).parent().parent().parent().parent();
				id += $(this).val() +'_';
				
				total_payment += parseFloat((tr.children("td:nth-child(5)").html()).replace(',', ''));
				paid_amount += parseFloat((tr.children("td:nth-child(5)").html()).replace(',', '')) +'_';
				principle += parseFloat((tr.children("td:nth-child(4)").html()).replace(',', '')) +'_';
			});
			
			$(this).attr('href', "<?= site_url('sales/add_payment_loan') ?>/"+total_payment+"/"+id+"/"+paid_amount+"/"+principle+"/"+sale_id);
			
		}else {
			
			alert("Please check..");
			return false;
		}
		
	});
	
	
	$(".change_date").bind('click',function(){
		var id = '';
		if($(".bb .checkbox:checked").length > 0){
			
			$(".bb .checkbox:checked").each(function(){	
				var parent = $(this).parent().parent().parent().parent();
				id += $(this).val() +'_';
			});
			$(this).attr('href', "<?= site_url('sales/changePaymentDate') ?>/"+id);
		}else {
			alert("Please check..");
			return false;
		}
		
	});
	
	$(".change_term").bind('click',function(){
		var id = <?= $sale_id; ?>;
		$(this).attr('href', "<?= site_url('sales/changeLoanTerm') ?>/"+id);
	});
	
});
</script>
