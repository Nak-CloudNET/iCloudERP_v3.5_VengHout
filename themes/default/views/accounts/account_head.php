<style type="text/css">
    @media print {
        #myModal .modal-content {
            display: none !important;
        }
    }
</style>
<script>
	$(document).ready(function () {
		$('#submit_report').click(function(){
			$('#show_data').empty();
			var start_date = $('#start_date').val();
			var end_date = $('#end_date').val();
			var id = $('#id').val();
			$.ajax({
				type:"GET",
				url: "<?php echo base_url()?>account/dataLedger",
				dataType: "json",
				data:{
					start_date:start_date,
					end_date:end_date,
					id:id
				},
				success: function(msg){
					$('#show_data').html(msg);
				}
			});
		});
	});
</script>
<div class="modal-dialog modal-lg no-modal-header">
    <div class="modal-content">
		<div class="modal-header">
			<b>Account Transation</b>
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
		</div>
        <div class="modal-body">
			<div class="row">
				<input type="hidden" value="<?=$id;?>" id="id"/>
				<div class="col-sm-5">
					<div class="form-group">
						<?= lang("start_date", "start_date"); ?>
						<?php echo form_input('start_date', (isset($_POST['start_date']) ? $_POST['start_date'] : ""), 'class="form-control date" id="start_date"'); ?>
					</div>
				</div>
				<div class="col-sm-5">
					<div class="form-group">
						<?= lang("end_date", "end_date"); ?>
						<?php echo form_input('end_date', (isset($_POST['end_date']) ? $_POST['end_date'] : ""), 'class="form-control date" id="end_date"'); ?>
					</div>
				</div>
				<div class="col-sm-2">
					<div class="form-group">
						<button type="button" class="btn btn-primary" id="submit_report" style="margin-top:30px;"><?=$this->lang->line("submit");?></button>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
				<div class="table-responsive">
                    <table id="registerTable" cellpadding="0" cellspacing="0" border="0"
                           class="table table-bordered table-hover table-striped reports-table">
                        <thead>
							<tr>
								<th><?= lang('batch'); ?></th>
								<th><?= lang('ref'); ?></th>
								<th><?= lang('Seq'); ?></th>
								<th width="250"><?= lang('description'); ?></th>
								<th><?= lang('date'); ?></th>
								<th><?= lang('type'); ?></th>
								<th style="width: 100px !important;"><?= lang('debit_amount'); ?></th>
								<th style="width: 100px !important;"><?= lang('credit_amount'); ?></th>
							</tr>
                        </thead>
                        <tbody id="show_data">
							<tr>
								<td colspan="8" class="dataTables_empty"><?= lang('loading_data_from_server') ?></td>
							</tr>
                        </tbody>
                        <tfoot class="dtFilter">
							<tr class="active">
								<th></th>
								<th></th>
								<th></th>
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