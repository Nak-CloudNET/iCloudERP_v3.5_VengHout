<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
			<button type="button" class="btn btn-xs btn-default no-print pull-right" style="margin-right:15px;" onclick="window.print();">
                <i class="fa fa-print"></i> <?= lang('print'); ?>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo $this->lang->line('Purchases (All Warehouses)'); ?></h4>
        </div>
        <div class="modal-body" style="overflow-x: scroll">
            <div class="table-responsive">
                <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered table-condensed table-striped dtable responsive">
                        <thead>
                        <tr class="active">
                            <th><?php echo $this->lang->line("no"); ?></th>
                            <th><?php echo $this->lang->line("date"); ?></th>
                            <th><?php echo $this->lang->line("reference_no"); ?></th>
                            <th><?php echo $this->lang->line("supplier"); ?></th>
                            <th><?php echo $this->lang->line("purchase_status"); ?></th>
                            <th><?php echo $this->lang->line("grand_total"); ?></th>
                            <th><?php echo $this->lang->line("discount"); ?></th>
                            <th><?php echo $this->lang->line("paid"); ?></th>
                            <th><?php echo $this->lang->line("balance"); ?></th>
                            <th><?php echo $this->lang->line("payment_status"); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        
						<?php
							if($purchase_info->num_rows()>0){
								$total_grand_total  = 0;
								$total_discount     = 0;
								$total_paid         = 0;
								$total_balance      = 0;
								$i=0;
								foreach($purchase_info->result() as $row){
									$total_grand_total  +=$row->grand_total;
									$total_discount     +=$row->discount;
									$total_paid         +=$row->paid;
									$total_balance      +=$row->balance;
						?>
								<tr style="text-align: right">
									<td><?php echo ++$i;?></td>
									<td><?php echo $row->date;?></td>
									<td style="text-align: left"><?php echo $row->reference_no;?></td>
									<td><?php echo $row->supplier;?></td>
									<td><?php echo row_status($row->status);?></td>
									<td><?php echo number_format($row->grand_total,2);?></td>
                                    <td><?php echo number_format($row->discount,2);?></td>
									<td><?php echo number_format($row->paid,2);?></td>
									<td><?php echo number_format($row->balance,2);?></td>
									<td><?php echo row_status($row->payment_status);?></td>
								</tr>
						<?php
								}
							}else{
						?>	
								<tr>
									<td colspan="9" class="dataTables_empty"><?= lang('loading_data_from_server'); ?></td>
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
                            <th style="text-align: right"><?php echo number_format($total_grand_total,2); ?></th>
                            <th style="text-align: right"><?php echo number_format($total_discount,2); ?></th>
                            <th style="text-align: right"><?php echo number_format($total_paid,2); ?></th>
                            <th style="text-align: right"><?php echo number_format($total_balance,2); ?></th>
                            <th style="text-align: right"></th>
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
	.dtable { white-space: nowrap !important; }
</style>
<script type="text/javascript" charset="UTF-8">
    $(document).ready(function () {
		$(".dtable").dataTable();
        $(document).on('click', '.po-delete', function () {
            var id = $(this).attr('id');
            $(this).closest('tr').remove();
        });
    });
</script>    