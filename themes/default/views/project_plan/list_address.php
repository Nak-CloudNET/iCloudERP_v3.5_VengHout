<script>
	$(document).ready(function () {
		var oTable = $('#PNData').dataTable({
            "aaSorting": [[1, "desc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
            "iDisplayLength": '<?= $Settings->rows_per_page ?>',
            'bProcessing': true, 'bServerSide': true,
			'sAjaxSource': '<?=site_url('project_plan/getAddress') ?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "<?= $this->security->get_csrf_token_name() ?>",
                    "value": "<?= $this->security->get_csrf_hash() ?>"
                });
                $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
            },
            'fnRowCallback': function (nRow, aData, iDisplayIndex) {
                nRow.className = "project_address_link";
                return nRow;
            },
            "aoColumns": [
				{"bSortable": false, "mRender": checkbox}, 
				null, null,
				{"bSortable": false}
			]
        }).fnSetFilteringDelay().dtFilter([
            {column_number: 1, filter_default_label: "[<?=lang('plan');?> ]", filter_type: "text", data: []},
            {column_number: 2, filter_default_label: "[<?=lang('address');?>]", filter_type: "text", data: []},
        ], "footer");
	});
</script>
<div class="box">
    <div class="box-header">
		<h2 class="blue">
			<i class="fa-fw fa fa-barcode"></i>
			<?= lang('list_address'); ?>
		</h2>
    </div>
	
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <p class="introtext"><?= lang('list_results'); ?></p>
				<div class="clearfix"></div>
                <div class="table-responsive">
                    <table id="PNData" class="table table-bordered table-hover table-striped">
                        <thead>
							<tr class="active">
								<th style="min-width:30px; width: 30px; text-align: center;">
									<input class="checkbox checkft" type="checkbox" name="check"/>
								</th>
								<th><?php echo $this->lang->line("plan"); ?></th>
								<th><?php echo $this->lang->line("address"); ?></th>
								<th style="width:100px; text-align:center;"><?php echo $this->lang->line("actions"); ?></th>
							</tr>
                        </thead>
                        <tbody>
							<tr>
								<td colspan="4" class="dataTables_empty">
									<?php echo $this->lang->line("loading_data"); ?>
								</td>
							</tr>
                        </tbody>
                        <tfoot class="dtFilter">
							<tr class="active">
								<th style="min-width:30px; width: 30px; text-align: center;">
									<input class="checkbox checkft" type="checkbox" name="check"/>
								</th>
								<th></th>
								<th></th>
								<th style="width:115px; text-align:center;">
									<?php echo $this->lang->line("actions"); ?>
								</th>
							</tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>