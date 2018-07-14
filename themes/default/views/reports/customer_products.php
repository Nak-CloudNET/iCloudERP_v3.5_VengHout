<?php

	$v = "";
	if ($this->input->post('group_area')) {
		$v .= "&group_area=" . $this->input->post('group_area');
	}
	if ($this->input->post('customer')) {
		$v .= "&customer=" . $this->input->post('customer');
	}
?>

<script>
    $(document).ready(function () {
        var oTable = $('#CusData1').dataTable({
            //"aaSorting": [[0, "asc"], [1, "asc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
            "iDisplayLength": <?= $Settings->rows_per_page ?>,
            'bProcessing': true, 'bServerSide': true,
            'sAjaxSource': '<?= site_url('reports/getCustomerProducts').'/?v=1'.$v ?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "<?= $this->security->get_csrf_token_name() ?>",
                    "value": "<?= $this->security->get_csrf_hash() ?>"
                });
                $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
            },
            "aoColumns": [{"bSortable": false, "mRender": checkbox}, null, null, null, null,null,{"bSortable": false}]
        }).fnSetFilteringDelay().dtFilter([
            {column_number: 1, filter_default_label: "[<?=lang('code');?>]", filter_type: "text", data: []},
            {column_number: 2, filter_default_label: "[<?=lang('name');?>]", filter_type: "text", data: []},
            {column_number: 4, filter_default_label: "[<?=lang('qoh');?>]", filter_type: "text", data: []},
            {column_number: 4, filter_default_label: "[<?=lang('unit');?>]", filter_type: "text", data: []},
            {column_number: 4, filter_default_label: "[<?=lang('cost');?>]", filter_type: "text", data: []},
        ], "footer");
    });
	
	$(document).ready(function(){
		$('#form').hide();
		$('.toggle_down').click(function () {
            $("#form").slideDown();
            return false;
        });
        $('.toggle_up').click(function () {
            $("#form").slideUp();
            return false;
        });
		
	});
</script>
<?php 
if ($Owner) {
   // echo form_open('reports/customers_actions'.($warehouse_id ? '/'.$warehouse_id : ''), 'id="action-form"');
   echo form_open('reports/customers_actions' ,'id="action-form"');
} 
?>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-users"></i><?= lang('customers'); ?></h2>

        <div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown"><a href="#" class="toggle_up tip" title="<?= lang('hide_form') ?>"><i
								class="icon fa fa-toggle-up"></i></a></li>
				<li class="dropdown"><a href="#" class="toggle_down tip" title="<?= lang('show_form') ?>"><i
								class="icon fa fa-toggle-down"></i></a></li>
				<li class="dropdown"><a href="#" id="pdf" data-action="export_pdf"  class="tip" title="<?= lang('download_pdf') ?>"><i
                            class="icon fa fa-file-pdf-o"></i></a></li>
                <li class="dropdown"><a href="#" id="excel" data-action="export_excel"  class="tip" title="<?= lang('download_xls') ?>"><i
                            class="icon fa fa-file-excel-o"></i></a></li>
                <li class="dropdown"><a href="#" id="image" class="tip" title="<?= lang('save_image') ?>"><i
                            class="icon fa fa-file-picture-o"></i></a></li>
				
			</ul>
        </div>	
    </div>
<?php if ($Owner) { ?>
    <div style="display: none;">
        <input type="hidden" name="form_action" value="" id="form_action"/>
        <?= form_submit('performAction', 'performAction', 'id="action-form-submit"') ?>
    </div>
    <?= form_close() ?>
<?php } ?> 
	<div class="box-content">
        <div class="row">
            <div class="col-lg-12">
				<p class="introtext"><?= lang('view_report_customer'); ?></p>
				<div id="form">
                    <?php echo form_open("reports/customer_products"); ?>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("group_area", "group_area") ?>
                                <?php
                                $ar['0'] = lang("all");
                                foreach ($areas as $group_area) {
                                    $ar[$group_area->areas_g_code] = $group_area->areas_group.'('.$group_area->areas_g_code.')';
                                }
                                echo form_dropdown('group_area', $ar, (isset($_POST['group_area']) ? $_POST['group_area'] : ''), 'class="form-control select" id="group_area" placeholder="' . lang("select") . " " . lang("group_area") . '" style="width:100%"')
                                ?>
                            </div>
                        </div>
						<div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("customers", "customers") ?>
                                <?php
                                $cus['0'] = lang("all");
                                foreach ($customers as $customers) {
                                    $cus[$customers->id] = $customers->name;
                                }
                                echo form_dropdown('customer', $cus, (isset($_POST['customer']) ? $_POST['customer'] : ''), 'class="form-control select" id="customers" placeholder="' . lang("select") . " " . lang("customer") . '" style="width:100%"')
                                ?>
                            </div>
                        </div>       
                    </div>
                    <div class="form-group">
                        <div
                            class="controls"> <?php echo form_submit('submit_report', $this->lang->line("submit"), 'class="btn btn-primary"'); ?> </div>
                    </div>
                    <?php echo form_close(); ?>

                </div>                

                <div class="table-responsive">
                    <table id="CusData1" cellpadding="0" cellspacing="0" border="0"
                           class="table table-bordered table-condensed table-hover table-striped reports-table">
                        <thead>
							<tr class="primary">
								<th style="min-width:30px; width: 30px; text-align: center;">
									<input class="checkbox checkth" type="checkbox" name="check"/>
								</th>
								<th><?= lang("code"); ?></th>
								<th style="width:350px;"><?= lang("name"); ?></th>
								<th><?= lang("qoh"); ?></th>
								<th><?= lang("unit"); ?></th>							
								<th><?= lang("cost"); ?></th>
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
									<input class="checkbox checkth" type="checkbox" name="check"/>
								</th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th style="width:85px;"><?= lang("actions"); ?></th>
							</tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?= $assets ?>js/html2canvas.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
		/*
        $('#pdf').click(function (event) {
            event.preventDefault();
            window.location.href = "<?=site_url('reports/getCustomers/pdf')?>";
            return false;
        });
        $('#xls').click(function (event) {
            event.preventDefault();
            window.location.href = "<?=site_url('reports/getCustomers/0/xls')?>";
            return false;
        });
		*/
        $('#image').click(function (event) {
            event.preventDefault();
            html2canvas($('.box'), {
                onrendered: function (canvas) {
                    var img = canvas.toDataURL()
                    window.open(img);
                }
            });
            return false;
        });
    });
</script>