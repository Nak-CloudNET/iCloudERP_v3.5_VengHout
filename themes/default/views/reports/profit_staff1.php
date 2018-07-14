<?php
	function row_status($x){
		if($x == 'completed' || $x == 'paid' || $x == 'sent' || $x == 'received') {
			return '<div class="text-center"><span class="label label-success">'.lang($x).'</span></div>';
		}elseif($x == 'pending' || $x == 'book' || $x == 'free'){
			return '<div class="text-center"><span class="label label-warning">'.lang($x).'</span></div>';
		}elseif($x == 'partial' || $x == 'transferring' || $x == 'ordered'  || $x == 'busy'  || $x == 'processing'){
			return '<div class="text-center"><span class="label label-info">'.lang($x).'</span></div>';
		}elseif($x == 'due' || $x == 'returned'){
			return '<div class="text-center"><span class="label label-danger">'.lang($x).'</span></div>';
		}else{
			return '<div class="text-center"><span class="label label-default">'.lang($x).'</span></div>';
		}
	}
?>
<div class="modal-dialog modal-lg" style="width:1000px;">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <button type="button" class="btn btn-xs btn-default no-print pull-right" style="margin-right:15px;" onclick="window.print();">
                <i class="fa fa-print"></i> <?= lang('print'); ?>
            </button>
			<a href="#" class="btn btn-xs btn-default no-print pull-right" style="margin-right:15px;" onclick="sendEmail();">
				<i class="fa fa-send"></i> <?= lang('Send'); ?>
			</a>
            <h4 class="modal-title" id="myModalLabel"><?= lang('sale_dialy').' ('.$this->erp->hrsd($date).')'; ?></h4>
        </div>
        <div class="modal-body">
            <div class="table-responsive">
				<table id="POData" cellpadding="0" cellspacing="0" border="0" class="table table-condensed table-bordered table-hover table-striped">
                    <thead>
                        <tr class="active">
                            <th><?php echo $this->lang->line("date"); ?></th>
                            <th><?php echo $this->lang->line("reference_no"); ?></th>
                            <th><?php echo $this->lang->line("supplier"); ?></th>
                            <th><?php echo $this->lang->line("status"); ?></th>
                            <th><?php echo $this->lang->line("grand_total"); ?></th>
							<th><?php echo $this->lang->line("discount"); ?></th>
                            <th><?php echo $this->lang->line("paid"); ?></th>
                            <th><?php echo $this->lang->line("balance"); ?></th>
                            <th><?php echo $this->lang->line("payment_status"); ?></th>
                        </tr>
                    </thead>
                    <tbody>
						<?php
							$total = 0;
							$pay   = 0;
							$balances = 0;
							$total_discount = 0;
							foreach($sales as $sale){
								$total += $sale->grand_total;
								$pay += $sale->paid;
								$balances += $sale->balance;
								$total_discount += $sale->total_discount;
						?>
							<tr>
								<td><?= $sale->date; ?></td>
								<td><?= $sale->reference_no; ?></td>
								<td><?= $sale->supplier; ?></td>
								<td><?= row_status($sale->status); ?></td>
								<td><?= number_format($sale->grand_total,2); ?></td>
								<td><?= number_format($sale->total_discount,2); ?></td>
								<td><?= number_format($sale->paid,2); ?></td>
								<td><?= number_format($sale->balance,2); ?></td>
								<td><?= row_status($sale->payment_status); ?></td>
							</tr>
						<?php
							}
						?>
                    </tbody>
                    <tfoot class="dtFilter">
                        <tr class="active">
                            <th><?php echo $this->lang->line("date"); ?></th>
                            <th><?php echo $this->lang->line("reference_no"); ?></th>
                            <th><?php echo $this->lang->line("customer"); ?></th>
                            <th><?php echo $this->lang->line("sale_status"); ?></th>
                            <th><?php echo number_format($total,2); ?></th>
							<th><?php echo number_format($total_discount,2); ?></th>
                            <th><?php echo number_format($pay,2); ?></th>
                            <th><?php echo number_format($balances,2); ?></th>
                            <th><?php echo $this->lang->line("payment_status"); ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

</div>
<style type="text/css">
	table { 
		white-space: nowrap;
	}
</style>
<script>
	$(function(){
		$("#POData").dataTable({
			"iDisplayLength": 20,
		});
	})
	function sendEmail(){
	var email = prompt("<?= lang("email_address"); ?>", "<?= isset($customer->email)?$customer->email:''; ?>");
	if (email != null) {
		$.ajax({
			type: "post",
			url: "<?= site_url('reports/email_receipt') ?>",
			data: {<?= $this->security->get_csrf_token_name(); ?>: "<?= $this->security->get_csrf_hash(); ?>", email: email},
			dataType: "json",
			success: function (data) {
				alert(data.msg);
			},
			error: function () {
				alert('<?= lang('ajax_request_failed'); ?>');
				return false;
			}
		});
	}
	return false;
	}
</script>
