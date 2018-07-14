<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
			<button type="button" class="btn btn-xs btn-default no-print pull-right" style="margin-right:15px;" onclick="window.print();">
                <i class="fa fa-print"></i> <?= lang('print'); ?>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo $this->lang->line('Payments Sent (All Warehouses)'); ?></h4>
        </div>
        <div class="modal-body">
            <div class="table-responsive">
                    <table id="POData" cellpadding="0" cellspacing="0" border="0"
                           class="table table-bordered table-condensed table-striped">
                        <thead>
                        <tr class="active">
                            <th><?php echo $this->lang->line("no"); ?></th>
                            <th><?php echo $this->lang->line("date"); ?></th>
                            <th><?php echo $this->lang->line("payment_reference"); ?></th>
                            <th><?php echo $this->lang->line("sale_reference"); ?></th>
                            <th><?php echo $this->lang->line("purchase_reference"); ?></th>
                            <th><?php echo $this->lang->line("paid_by"); ?></th>
                            <th><?php echo $this->lang->line("amount"); ?></th>
                            <th><?php echo $this->lang->line("type"); ?></th>
							<!--<th style="width:100px;"><?php //echo $this->lang->line("actions"); ?></th>-->
                        </tr>
                        </thead>
                        <tbody>
                        
						<?php
							$total_grand_total =0;
							$total_paid =0;
							$total_balance =0;
							$amount=0;
							if($payment_sent_info->num_rows()>0){
								$i=0;
								$type="";
								foreach($payment_sent_info->result() as $row){
									/*$total_grand_total+=$row->grand_total;
									$total_paid+=$row->paid;
									$total_balance+=$row->balance;
									
									$type+=$row->type;*/
									$amount+=$row->amount;
									
						?>
									<tr>
										<td><?php echo ++$i;?></td>
										<td><?php echo $row->date;?></td>
										<td><?php echo $row->ref_no;?></td>
										<td><?php echo $row->sale_ref;?></td>
										<td><?php echo $row->pur_ref;?></td>
										<td><?php echo row_status($row->paid_by);?></td>
										<td><?php echo $row->amount;?></td>
										<td><?php echo row_status($row->type);?></td>
									</tr>
						<?php
								}
							}else{
						?>	
								<tr>
									<td colspan="8" class="dataTables_empty"><?= lang('loading_data_from_server'); ?></td>
								</tr>
						<?php
							}
						?>
                            
                        
                        </tbody>
                        <tfoot class="dtFilter">
                        <tr class="active">
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
							<th></th>
							<th></th>
                            <th><?php echo number_format($amount,2); ?></th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
        </div>
    </div>
</div>
<?php
function row_status($x){
	if($x == 'completed' || $x == 'paid' || $x == 'sent' || $x == 'received' || $x == 'deposit') {
		return '<div class="text-center"><span class="label label-success">'.lang($x).'</span></div>';
	}elseif($x == 'pending' || $x == 'book' || $x == 'free'){
		return '<div class="text-center"><span class="label label-warning">'.lang($x).'</span></div>';
	}elseif($x == 'partial' || $x == 'transferring' || $x == 'ordered'  || $x == 'busy'  || $x == 'processing'){
		return '<div class="text-center"><span class="label label-info">'.lang($x).'</span></div>';
	}elseif($x == 'due' || $x == 'returned' || $x == 'regular'){
		return '<div class="text-center"><span class="label label-danger">'.lang($x).'</span></div>';
	}else{
		return '<div class="text-center"><span class="label label-default">'.lang($x).'</span></div>';
	}
}
?>
<style type="text/css">
	#POData {white-space: nowrap; }
</style>
<script type="text/javascript" charset="UTF-8">
    $(document).ready(function () {
		$("#POData").dataTable();
        $(document).on('click', '.po-delete', function () {
            var id = $(this).attr('id');
            $(this).closest('tr').remove();
        });
    });
</script>    