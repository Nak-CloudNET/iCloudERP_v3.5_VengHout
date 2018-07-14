<script>
    $(document).ready(function () {
        var oTable = $('#SupData').dataTable({
            "aaSorting": [[1, "asc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
            "iDisplayLength": <?= $Settings->rows_per_page ?>,
            'bProcessing': true, 'bServerSide': true,
            'sAjaxSource': '<?= site_url('taxes/getExchangeRateTax') ?>',
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
            }, null, {"mRender": currencyFormat}, {"mRender": currencyFormat}, null, null , {"bSortable": false}],
			'fnRowCallback': function (nRow, aData, iDisplayIndex) {
				nRow.id = aData[0];
                nRow.className = "acc_head";
                return nRow;
            }
        }).dtFilter([
           // {column_number: 1, filter_default_label: "[<?=lang('code');?>]", filter_type: "text", data: []},
            {column_number: 1, filter_default_label: "[<?=lang('usd');?>]", filter_type: "text", data: []},
            {column_number: 2, filter_default_label: "[<?=lang('salary_kh');?>]", filter_type: "text", data: []},
            {column_number: 3, filter_default_label: "[<?=lang('average_kh');?>]", filter_type: "text", data: []},
			{column_number: 4, filter_default_label: "[<?=lang('month');?>]", filter_type: "text", data: []},
            {column_number: 5, filter_default_label: "[<?=lang('year');?>]", filter_type: "text", data: []},
        ], "footer");
    });
</script>
<?php if ($Owner) {
    echo form_open('taxes/account_actions', 'id="action-form"');
} ?>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-users"></i><?= lang('Exchange Rate'); ?></h2>

        <div class="box-icon">
            <ul class="btn-tasks"> 
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon fa fa-tasks tip"
                                                                                  data-placement="left"
                                                                                  title="<?= lang("actions") ?>"></i></a>
                    <ul class="dropdown-menu pull-right" class="tasks-menus" role="menu" aria-labelledby="dLabel">
                        <li><a href="<?= site_url('taxes/add_exchangerate_tax'); ?>" data-toggle="modal" data-target="#myModal"
                               id="add"><i class="fa fa-plus-circle"></i> <?= lang("add_exchangerate_tax"); ?></a></li>
						<?php if ($Owner || $Admin) { ?>
							<li>
								<a href="#" id="excel" data-action="export_excel"><i class="fa fa-file-excel-o"></i> <?= lang('export_to_excel') ?></a>
							</li>
							<li><a href="#" id="pdf" data-action="export_pdf"><i
										class="fa fa-file-pdf-o"></i> <?= lang('export_to_pdf') ?></a></li>
						<?php }else{ ?>
							<?php if($GP['accounts-export']) { ?>
								<li>
									<a href="#" id="excel" data-action="export_excel"><i class="fa fa-file-excel-o"></i> <?= lang('export_to_excel') ?></a>
								</li>
								<li><a href="#" id="pdf" data-action="export_pdf"><i
										class="fa fa-file-pdf-o"></i> <?= lang('export_to_pdf') ?></a></li>
							<?php }?>
						<?php }?>				
                        <li class="divider"></li>
                        <li style="display:none"><a href="#" class="bpo" title="<?= $this->lang->line("delete_condition_tax") ?>"
                               data-content="<p><?= lang('r_u_sure') ?></p><button type='button' class='btn btn-danger' id='delete' data-action='delete'><?= lang('i_m_sure') ?></a> <button class='btn bpo-close'><?= lang('no') ?></button>"
                               data-html="true" data-placement="left"><i
                                    class="fa fa-trash-o"></i> <?= lang('delete_condition_tax') ?></a></li>
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
                           <!-- <th style="width:20%;"><?= lang("code"); ?></th>-->
							<th style="width:20%;"><?= lang("usd"); ?></th>
                            <th style="width:20%;"><?= lang("salary_kh"); ?></th>
                            <th style="width:20%;"><?= lang("average_kh"); ?></th>
							<th style="width:20%;"><?= lang("month"); ?></th>
							<th style="width:20%;"><?= lang("year"); ?></th>
                            <th style="width:10%; text-align:center;"><?= lang("actions"); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td colspan="8" class="dataTables_empty"><?= lang('loading_data_from_server') ?></td>
                        </tr>
                        </tbody>
                        <tfoot class="dtFilter">
                        <tr class="active">
                            <th style="min-width:30px; width: 30px; text-align: center;">
                                <input class="checkbox checkft" type="checkbox" name="check"/>
                            </th>
                         <!-- <th></th>-->
                            <th></th>
                            <th></th>
							<th></th>
							<th></th>
							<th></th>
                            <th><?= lang("[actions]"); ?></th>
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
<?php if ($action && $action == 'add') {
    echo '<script>$(document).ready(function(){$("#add").trigger("click");});</script>';
}
?>
<script type="text/javascript">
	/*$("document").ready(function(){
		$("#excel").click(function(e){
			e.preventDefault();
			window.location.href="<?= site_url('products/getProductAll/0/xls/') ?>";
			return false;
		});
			
	});*/
</script>
	

