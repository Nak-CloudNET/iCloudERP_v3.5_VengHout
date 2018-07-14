<script>
    $(document).ready(function () {
		function center(x){
            return '<div style="text-align:center">' + x +'</div>';
        }
        var oTable = $('#SupData').dataTable({
            "aaSorting": [[1, "asc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
            "iDisplayLength": <?= $Settings->rows_per_page ?>,
            'bProcessing': true, 'bServerSide': true,
            'sAjaxSource': '<?= site_url('billers/getBillers') ?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "<?= $this->security->get_csrf_token_name() ?>",
                    "value": "<?= $this->security->get_csrf_hash() ?>"
                });
                $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
            },
            "aoColumns": [{
                "bSortable": false,
                "mRender": checkbox
            }, {"mRender": center}, ((site.settings.show_company_code== 1)? null:{"bVisible": false}), null, null, null, null,null, null, null, {"bSortable": false}]
        }).dtFilter([
			{column_number: 1, filter_default_label: "[<?=lang('no');?>]", filter_type: "text", data: []},
            {column_number: 2, filter_default_label: "[<?=lang('code');?>]", filter_type: "text", data: []},
            {column_number: 3, filter_default_label: "[<?=lang('company');?>]", filter_type: "text", data: []},
            {column_number: 4, filter_default_label: "[<?=lang('name');?>]", filter_type: "text", data: []},
            {column_number: 5, filter_default_label: "[<?=lang('vat_no');?>]", filter_type: "text", data: []},
            {column_number: 6, filter_default_label: "[<?=lang('phone');?>]", filter_type: "text", data: []},
            {column_number: 7, filter_default_label: "[<?=lang('email_address');?>]", filter_type: "text", data: []},
            {column_number: 8, filter_default_label: "[<?=lang('city');?>]", filter_type: "text", data: []},
			//{column_number: 4, filter_default_label: "[<?=lang('start_date');?>]", filter_type: "text", data: []},
			//{column_number: 5, filter_default_label: "[<?=lang('end_date');?>]", filter_type: "text", data: []},
			//{column_number: 6, filter_default_label: "[<?=lang('period');?>]", filter_type: "text", data: []},
			//{column_number: 7, filter_default_label: "[<?=lang('amount');?>]", filter_type: "text", data: []},
            {column_number: 9, filter_default_label: "[<?=lang('country');?>]", filter_type: "text", data: []},
        ], "footer");
    });
</script>
<style>
	
	#SupData th:nth-child(2) {
		width: 5%;
	}
	#SupData th:nth-child(3) {
		width: 5%;
	}
	#SupData th:nth-child(4) {
		width:20%;
	}
	#SupData th:nth-child(9) {
		width:10%;
	}
</style>
<?php if ($Owner || $GP['bulk_actions']) {
    echo form_open('billers/biller_actions', 'id="action-form"');
} ?>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-users"></i><?= lang('list_billers'); ?></h2>

        <div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon fa fa-tasks tip"
                                                                                  data-placement="left"
                                                                                  title="<?= lang("actions") ?>"></i></a>
                    <ul class="dropdown-menu pull-right tasks-menus" role="menu" aria-labelledby="dLabel">
                        <li><a href="<?= site_url('billers/add'); ?>" data-toggle="modal" data-target="#myModal"
                               id="add"><i class="fa fa-plus-circle"></i> <?= lang("add_biller"); ?></a></li>
                        <li><a href="#" id="excel" data-action="export_excel"><i
                                    class="fa fa-file-excel-o"></i> <?= lang('export_to_excel') ?></a></li>
                        <li><a href="#" id="pdf" data-action="export_pdf"><i
                                    class="fa fa-file-pdf-o"></i> <?= lang('export_to_pdf') ?></a></li>
                        <li class="divider"></li>
                        <li><a href="#" class="bpo" title="<?= $this->lang->line("delete_billers") ?>"
                               data-content="<p><?= lang('r_u_sure') ?></p><button type='button' class='btn btn-danger' id='delete' data-action='delete'><?= lang('i_m_sure') ?></a> <button class='btn bpo-close'><?= lang('no') ?></button>"
                               data-html="true" data-placement="left"><i
                                    class="fa fa-trash-o"></i> <?= lang('delete_biller') ?></a></li>
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
                            <th style="min-width:30px; width: 30px; text-align: center;">
                                <input class="checkbox checkth" type="checkbox" name="check"/>
                            </th>
							<th style="min-width:10px; width:10px;"><?= lang("no"); ?></th>
							<th><?= lang("code"); ?></th>
                            <th><?= lang("company"); ?></th>
                            <th><?= lang("name"); ?></th>
                            <th><?= lang("vat_no"); ?></th>
                            <th><?= lang("phone"); ?></th>
                            <th><?= lang("email_address"); ?></th>
                            <th><?= lang("city"); ?></th>
							<!--<th><?= lang("start_date"); ?></th>
							<th><?= lang("end_date"); ?></th>
							<th><?= lang("period"); ?></th>
							<th><?= lang("amount");?></th>-->
                            <th><?= lang("country"); ?></th>
                            <th style="width:85px;"><?= lang("actions"); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td colspan="9" class="dataTables_empty"><?= lang('loading_data_from_server') ?></td>
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
                            <th style="width:85px;" class="text-center"><?= lang("actions"); ?></th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php if ($Owner || $GP['bulk_actions']) { ?>
    <div style="display: none;">
        <input type="hidden" name="form_action" value="" id="form_action"/>
        <?= form_submit('performAction', 'performAction', 'id="action-form-submit"') ?>
    </div>
    <?= form_close() ?>
<?php } ?>
<?php if ($action && $action == 'add') {
    echo '<script>$(document).ready(function(){$("#add").trigger("click");});</script>';
}
?>
	

