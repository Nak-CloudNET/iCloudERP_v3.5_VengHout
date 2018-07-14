<div class="modal-dialog modal-lg no-modal-header">
    <div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                <i class="fa fa-2x">&times;</i>
            </button>
            <button type="button" class="btn btn-xs btn-default no-print pull-right" style="margin-right:15px;" onclick="window.print();">
                <i class="fa fa-print"></i> <?= lang('print'); ?>
            </button>
			<h4 class="modal-title" id="myModalLabel"><?php echo lang('get_job_employee'); ?></h4>
		</div>
        <div class="modal-body">
            <div class="row">
				<div class="col-lg-12">
					<div class="table-responsive">
						<table id="EXPData" cellpadding="0" cellspacing="0" border="0"
							   class="table table-bordered table-hover table-striped">
							<thead>
								<tr class="active">
									<th class="col-xs-2"><?php echo $this->lang->line("date"); ?></th>
									<th class="col-xs-2"><?php echo $this->lang->line("reference"); ?></th>
									<th class="col-xs-2"><?php echo $this->lang->line("Cashiers"); ?></th>
									<th class="col-xs-2"><?php echo $this->lang->line("customer_name"); ?></th>
									<th class="col-xs-2"><?php echo $this->lang->line("price"); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php
									if($employee == null){
										
									}else{
										foreach($employee as $emp){
											echo '<tr>';
												echo '<td>'.$emp->created_at.'</td>';
												echo '<td>'.$emp->reference_no.'</td>';
												echo '<td>'.$emp->uName.'</td>';
												echo '<td>'.$emp->customer.'</td>';
												echo '<td>'.$emp->total.'</td>';
											echo '</tr>';
										}
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
								</tr>
							</tfoot>
						</table>
					</div>
				</div>	
			</div>
		</div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready( function() {
        $('.tip').tooltip();
    });
</script>
