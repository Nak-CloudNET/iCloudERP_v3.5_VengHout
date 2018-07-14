<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
			<button type="button" class="btn btn-xs btn-default no-print pull-right" style="margin-right:15px;" onclick="window.print();">
                <i class="fa fa-print"></i> <?= lang('print'); ?>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo $this->lang->line('Payments (All Warehouses)'); ?></h4>
        </div>
        <div class="modal-body">
            <div class="table-responsive">
                    <table id="POData" cellpadding="0" cellspacing="0" border="0"
                           class="table table-bordered table-hover table-striped">
                        <thead>
                        <tr class="active">
                            <th><?php echo $this->lang->line("no"); ?></th>
                            <th><?php echo $this->lang->line("date"); ?></th>
                            <th><?php echo $this->lang->line("payment_reference"); ?></th>
                            <th><?php echo $this->lang->line("sale_reference"); ?></th>
                            <th><?php echo $this->lang->line("purchase reference"); ?></th>
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
							if($payment_info->num_rows()>0){
								$i=0;
								foreach($payment_info->result() as $row){
									$amount+=$row->amount1;
						?>
								<tr>
									<td><?php echo ++$i;?></td>
									<td><?php echo $row->date1;?></td>
									<td><?php echo $row->ref_no1;?></td>
									<td><?php echo $row->sale_ref1;?></td>
									<td><?php echo $row->pur_ref1;?></td>
									<td><?php echo $row->paid_by1;?></td>
									<td><?php echo $row->amount1;?></td>
									<td><?php echo $row->type1;?></td>
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
                            <!--<th><?php //echo number_format($total_paid,2); ?></th>
                            <th><?php //echo number_format($total_balance,2); ?></th>-->
                            <th></th>
                            <!--<th style="width:100px; text-align: center;"><?php //echo $this->lang->line("actions"); ?></th>-->
                        </tr>
                        </tfoot>
                    </table>
                </div>
        </div>
    </div>
</div>
<script type="text/javascript" charset="UTF-8">
    $(document).ready(function () {
        $(document).on('click', '.po-delete', function () {
            var id = $(this).attr('id');
            $(this).closest('tr').remove();
        });
    });
</script>    