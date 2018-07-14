<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
			<button type="button" class="btn btn-xs btn-default no-print pull-right" style="margin-right:15px;" onclick="window.print();">
                <i class="fa fa-print"></i> <?= lang('print'); ?>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo $this->lang->line('Sales (All Warehouses)'); ?></h4>
        </div>
        <div class="modal-body">
            <div class="table-responsive">
                    <table id="POData" cellpadding="0" cellspacing="0" border="0"
                           class="table table-bordered table-condensed table-striped dtable">
                        <thead>
                        <tr class="active">
                            <th><?php echo $this->lang->line("no"); ?></th>
                            <th><?php echo $this->lang->line("date"); ?></th>
                            <th><?php echo $this->lang->line("reference_no"); ?></th>
                            <th><?php echo $this->lang->line("customer"); ?></th>
                            <th><?php echo $this->lang->line("sale_status"); ?></th>
                            <th><?php echo $this->lang->line("grand_total"); ?></th>
                            <th><?php echo $this->lang->line("paid"); ?></th>
                            <th><?php echo $this->lang->line("discount"); ?></th>
                            <th><?php echo $this->lang->line("balance"); ?></th>
							<th><?php echo $this->lang->line("payment_status"); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        
						<?php
							if($sale_info->num_rows()>0){
								$total_grand_total =0;
								$total_paid =0;
								$total_balance =0;
								$total_discount=0;
								$i=0;
								foreach($sale_info->result() as $row){
									$total_grand_total+=$row->grand_total;
									$total_balance+=$row->balance;

									$data=$this->db->select("
                                        SUM(IF((erp_payments.paid_by != 'deposit' AND ISNULL(erp_payments.return_id)), erp_payments.amount, IF(NOT ISNULL(erp_payments.return_id), ((-1)*erp_payments.amount), 0))) as paid,
                                        sum(discount) as discount")
                                        ->from("payments")->where("payments.sale_id",$row->id)->get() ;
									$sale=$data->row();
									$paid=$sale->paid;
									$discount=$sale->discount;
                                    $total_paid+=$sale->paid;
                                    $total_discount+=$sale->discount;
						?>
								<tr>
									<td><?php echo ++$i;?></td>
									<td><?php echo $row->date;?></td>
									<td><?php echo $row->reference_no;?></td>
									<td><?php echo $row->customer;?></td>
									<td><?php echo row_status($row->sale_status);?></td>
									<td><?php echo $row->grand_total;?></td>
									<td><?php echo $paid?$this->erp->formatMoney($paid):$this->erp->formatMoney(0);?></td>
                                    <td><?php echo $sale->discount?$this->erp->formatMoney($sale->discount):$this->erp->formatMoney(0);?></td>
									<td><?php echo $row->balance;?></td>
									<td><?php echo row_status($row->payment_status);?></td>
								</tr>
						<?php
								}
							}else{
						?>	
								<tr>
									<td colspan="10" class="dataTables_empty"><?= lang('loading_data_from_server'); ?></td>
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
                            <th><?php echo number_format($total_grand_total,2); ?></th>
                            <th><?php echo number_format($total_paid,2); ?></th>
                            <th><?php echo number_format($total_discount,2); ?></th>
                            <th><?php echo number_format($total_balance,2); ?></th>
                            <th></th> 
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
	.dtable {white-space: nowrap; }
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