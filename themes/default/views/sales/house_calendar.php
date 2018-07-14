<script>
    $(document).ready(function () {
        function attachment(x) {
            if (x != null) {
                return '<a href="' + site.base_url + 'assets/uploads/' + x + '" target="_blank"><i class="fa fa-chain"></i></a>';
            }
            return x;
        }
        
        var oTable = $('#SupData').dataTable({
            "aaSorting": [[1, "asc"], [2, "asc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
            "iDisplayLength": <?= $Settings->rows_per_page ?>,
            'bProcessing': true, 'bServerSide': true,
            'sAjaxSource': '<?= site_url('sales/getHouse_calendar') ?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "<?= $this->security->get_csrf_token_name() ?>",
                    "value": "<?= $this->security->get_csrf_hash() ?>"
                });
                $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
            },
            "aoColumns": [{
                "bSortable": false,
                "mRender": attachment,
                "mRender": checkbox
            }, null,null,null, null, {"mRender": currencyFormatNoZero}, {"mRender": currencyFormatNoZero}, {"mRender": currencyFormatNoZero},{"mRender": currencyFormatNoZero},null ,{"mRender": fld}, {"mRender": fld}, null, {"mRender": house_calendar_status}, {"mRender": contruction_status}, {"mRender": attachment}],
			'fnRowCallback': function (nRow, aData, iDisplayIndex) {
				/* if(aData[12] == 'free'){
					nRow.id = aData[0];
				}else{
					nRow.id = aData[0];
				}
                return nRow; */
            }
        }).dtFilter([
			{column_number: 1, filter_default_label: "[<?=lang('house_type');?>]", filter_type: "text", data: []},
			{column_number: 2, filter_default_label: "[<?=lang('street');?>]", filter_type: "text", data: []},
            {column_number: 3, filter_default_label: "[<?=lang('house');?>]", filter_type: "text", data: []},
            {column_number: 4, filter_default_label: "[<?=lang('customer');?>]", filter_type: "text", data: []},
			{column_number: 5, filter_default_label: "[<?=lang('price');?>]", filter_type: "text", data: []},
			{column_number: 6, filter_default_label: "[<?=lang('deposit');?>]", filter_type: "text", data: []},
			{column_number: 7, filter_default_label: "[<?=lang('down_payment');?>]", filter_type: "text", data: []},
			{column_number: 8, filter_default_label: "[<?=lang('loan_amount');?>]", filter_type: "text", data: []},
            {column_number: 9, filter_default_label: "[<?=lang('suspend_desciption');?>]", filter_type: "text", data: []},
			{column_number: 10, filter_default_label: "[<?=lang('start_date');?>]", filter_type: "text", data: []},
            {column_number: 11, filter_default_label: "[<?=lang('end_date');?>]", filter_type: "text", data: []},
			{column_number: 12, filter_default_label: "[<?=lang('term');?>]", filter_type: "text", data: []},
            {column_number: 13, filter_default_label: "[<?=lang('status');?>]", filter_type: "text", data: []},
			{column_number: 14, filter_default_label: "[<?=lang('contruction_status');?>]", filter_type: "text", data: []},
        ], "footer");
    });
</script>
<?php if ($Owner) {
    echo form_open('sales/suppend_actions', 'id="action-form"');
} ?>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-users"></i><?= lang('house_calendar'); ?></h2>

        <div class="box-icon">
            <ul class="btn-tasks"> 
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
					<i class="icon fa fa-tasks tip" data-placement="left" title="<?= lang("actions") ?>"></i></a>
                    <ul class="dropdown-menu pull-right" class="tasks-menus" role="menu" aria-labelledby="dLabel">
                        <li>
							<a href="<?= site_url('system_settings/addSuppend'); ?>" data-toggle="modal" data-target="#myModal" id="add">
							<i class="fa fa-plus-circle"></i> <?= lang("add_suppend"); ?></a>
						</li>
                        <li>
							<a href="<?= site_url('system_settings/import_chart_csv'); ?>" data-toggle="modal" data-target="#myModal">
							<i class="fa fa-plus-circle"></i> <?= lang("add_suppend_csv"); ?> </a></li>
                        <li>
							<a href="#" id="excel" data-action="export_excel">
							<i class="fa fa-file-excel-o"></i> <?= lang('export_to_excel') ?></a>
						</li>
                        <li>
							<a href="#" id="pdf" data-action="export_pdf">
							<i class="fa fa-file-pdf-o"></i> <?= lang('export_to_pdf') ?></a></li>
                        <li class="divider"></li>
                        <li>
							<a href="#" class="bpo" title="<b><?= $this->lang->line("delete_suppend") ?></b>"
                               data-content="<p><?= lang('r_u_sure') ?></p><button type='button' class='btn btn-danger' id='delete' data-action='delete'><?= lang('i_m_sure') ?></a> <button class='btn bpo-close'><?= lang('no') ?></button>"
                               data-html="true" data-placement="left"><i
                                    class="fa fa-trash-o"></i> <?= lang('delete_suppend') ?></a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <p class="introtext"><?= lang('list_results'); ?></p>

                <div class="table-responsive">
                    <table id="SupData" cellpadding="0" cellspacing="0" border="0"
                           class="table table-bordered table-condensed table-hover table-striped">
                        <thead>
                            <tr class="primary">
                                <th style="min-width:10%; width: 10%; text-align: center;">
                                    <input class="checkbox checkth" type="checkbox" name="check"/>
                                </th>
								<th style="width:10%;"><?= lang("house_type"); ?></th>
								<th style="width:10%;"><?= lang("street"); ?></th>
                                <th style="width:10%;"><?= lang("house"); ?></th>
    							<th style="width:30%;"><?= lang("customer_name"); ?></th>
    							<th style="width:10%;"><?= lang("price"); ?></th>
    							<th style="width:10%;"><?= lang("deposit"); ?></th>
    							<th style="width:10%;"><?= lang("down_payment"); ?></th>
    							<th style="width:10%;"><?= lang("loan_amount"); ?></th>
    							<th style="width:30%;"><?= lang("description"); ?></th>
    							<th style="width:15%;"><?= lang("start_date"); ?></th>
                                <th style="width:15%;"><?= lang("end_date"); ?></th>
    							<th style="width:15%;"><?= lang("term"); ?></th>
                                <th style="width:10%;"><?= lang("status"); ?></th>
								<th style="width:10%;"><?= lang("contruction_status"); ?></th>
                                <th style="min-width:30px; width: 30px; text-align: center;"><i class="fa fa-chain"></i>
                            </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td colspan="10" class="dataTables_empty"><?= lang('loading_data_from_server') ?></td>
                        </tr>
                        </tbody>
                        <tfoot class="dtFilter">
                            <tr class="active">
                                <th style="min-width:30px; width: 30px; text-align: center;">
                                    <input class="checkbox checkft" type="checkbox" name="check"/>
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
								<th></th>
								<th></th>
								<th></th>
                                <th style="min-width:30px; width: 30px; text-align: center;"><i class="fa fa-chain"></i>
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php if ($Owner) { ?>
    <div style="display: none;">
        <input type="hidden" name="form_action" value="" id="form_action"/>
        <?= form_submit('performAction', 'performAction', 'id="action-form-submit"') ?>
    </div>
    <?= form_close() ?>
<?php } ?>
<script type="text/javascript">
/*
	$("document").ready(function(){
		$("#excel").click(function(e){
			e.preventDefault();
			window.location.href="<?= site_url('products/getProductAll/0/xls/') ?>";
			return false;
		});
			
	});
*/
</script>
	

