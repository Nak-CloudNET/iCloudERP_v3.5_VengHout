<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
			<button type="button" class="btn btn-xs btn-default no-print pull-right" style="margin-right:15px;" onclick="window.print();">
                <i class="fa fa-print"></i> <?= lang('print'); ?>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('customer') . " (" . $company->name . ")"; ?></h4>
        </div>
        <div class="modal-body">
           
			<div class="table-responsive">
				<table id="POData" cellpadding="0" cellspacing="0" border="0"
                           class="table table-bordered table-hover table-striped">
					<?php 
						if($customer_info->num_rows()>0){
							foreach($customer_info->result() as $row){		
					?>
								<tr>
									<td><?php echo $this->lang->line("company"); ?></td>
									<td><?php echo $row->company;?></td>
								</tr>
								<tr>
									<th><?php echo $this->lang->line("name"); ?></th>
									<td><?php echo $row->name;?></td>
								</tr>
								<tr>
									<th><?php echo $this->lang->line("gender"); ?></th>
									<td><?php echo $row->gender;?></td>
								</tr>
								<tr>
									<th><?php echo $this->lang->line("date_of_birth"); ?></th>
									<td><?php echo $row->dob;?></td>
								</tr>
								<tr>
									<th><?php echo $this->lang->line("status"); ?></th>
									<td><?php echo $row->status;?></td>
								</tr>
								<tr>
									<th><?php echo $this->lang->line("phone"); ?></th>
									<td><?php echo $row->phone;?></td>
								</tr>
								<tr>
									<th><?php echo $this->lang->line("address"); ?></th>
									<td><?php echo $row->address;?>&nbsp;<?php echo $row->city;?>&nbsp;<?php echo $row->country;?></td>
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