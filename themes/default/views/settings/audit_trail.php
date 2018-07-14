<?php
	$v = "";
	
	if ($this->input->post('module')) {
		$v .= "&module=" . $this->input->post('module');
	}
	if ($this->input->post('start_date')) {
		$v .= "&start_date=" . $this->input->post('start_date');
	}
	if ($this->input->post('end_date')) {
		$v .= "&end_date=" . $this->input->post('end_date');
	}
	if(isset($date)){
		$v .= "&d=" . $date;
	}
	if ($xls) {
		$v .= "&xls=1";
	}
?>

<script>
    $(document).ready(function () {
        $('#CategoryTable').dataTable({
            "aaSorting": [[1, "asc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
            "iDisplayLength": <?= $Settings->rows_per_page ?>,
            'bProcessing': true, 'bServerSide': true,
            
			'sAjaxSource': '<?=site_url('system_settings/getAuditTrail'.'/?v=1'.$v);?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "<?= $this->security->get_csrf_token_name() ?>",
                    "value": "<?= $this->security->get_csrf_hash() ?>"
                });
                $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
            },
            "aoColumns": [null, null,null, null, null, null, null, null, {"bSortable": false}]
        });
    });
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#form_search').hide();
        
        $('.toggle_down').click(function () {
            $("#form_search").slideDown();
            return false;
        });
        $('.toggle_up').click(function () {
            $("#form_search").slideUp();
            return false;
        });
    });
</script>

<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-folder-open"></i><?= lang('audit_trail'); ?></h2>

        <div class="box-icon">
            <ul class="btn-tasks">
                
                    
                        
						<li class="dropdown"><a href="#" class="toggle_up tip" title="<?= lang('hide_form') ?>"><i
										class="icon fa fa-toggle-up"></i></a></li>
						<li class="dropdown"><a href="#" class="toggle_down tip" title="<?= lang('show_form') ?>"><i
										class="icon fa fa-toggle-down"></i></a></li>
						<!--<li class="dropdown"><a href="#" id="pdf" class="tip" title="<?= lang('download_pdf') ?>"><i
                            class="icon fa fa-file-pdf-o"></i></a></li>
						<li class="dropdown"><a href="#" id="xls" class="tip" title="<?= lang('download_xls') ?>"><i
                            class="icon fa fa-file-excel-o"></i></a></li>-->
                    
            </ul>
        </div>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">
                <p class="introtext"><?= lang('list_results'); ?></p>
				
				<div id="form_search">

                    <?php echo form_open("system_settings/audit_trail"); ?>
                    <div class="row">
						<div class="col-sm-12">
                            <div class="form-group">
                                <label class="control-label" for="module"><?= lang("Module Types"); ?></label>
                                <?php
                                /*$bl[""] = "";
                                foreach ($customers as $biller) {
                                    $bl[$biller->id] = $biller->name;
                                }*/
								$module_type = array("1"=>"Sale","2"=>"Quotation","3"=>"Purchase");
                                echo form_dropdown('module', $module_type, (isset($_POST['module']) ? $_POST['module'] : ""), 'class="form-control" id="module" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("module") . '"');
                                ?>
                            </div>
                        </div>
                    
						<div class="col-sm-6">
                            <div class="form-group">
                                <?= lang("start_date", "start_date"); ?>
                                <?php echo form_input('start_date', (isset($_POST['start_date']) ? $_POST['start_date'] : ""), 'class="form-control datetime" id="start_date"'); ?>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <?= lang("end_date", "end_date"); ?>
                                <?php echo form_input('end_date', (isset($_POST['end_date']) ? $_POST['end_date'] : ""), 'class="form-control datetime" id="end_date"'); ?>
                            </div>
                        </div>
					<div class="controls col-sm-12">
						<div class="form-group">
							<input type="hidden" name="user_post" value="1"/>
							<div class="controls col-sm-6"> <?php echo form_submit('submit_report', $this->lang->line("submit"), 'class="btn btn-primary"'); ?> </div>
						</div>
					</div>
                    <?php echo form_close(); ?>
					

                </div>
				</div>
				
				
                <div class="table-responsive">
                    <table id="CategoryTable" class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>                       
                                <th>
                                    <?= $this->lang->line("created_date"); ?>
                                </th>
								<th><?= $this->lang->line("created_by"); ?></th>
                                <th><?= $this->lang->line("biller"); ?></th>
                                <th><?= $this->lang->line("warehouse"); ?></th>
								<th><?= $this->lang->line("reference"); ?></th>
								<th><?= $this->lang->line("type"); ?></th>
								<th><?= $this->lang->line("note"); ?></th>
								<th>
                                    <?= $this->lang->line("updated_date"); ?>
                                </th>
								<th><?= $this->lang->line("updated_by"); ?></th>								                            
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="9" class="dataTables_empty">
                                    <?= lang('loading_data_from_server') ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script language="javascript">
    $(document).ready(function () {

        $('#pdf').click(function (event) {
            event.preventDefault();
            window.location.href = "<?=site_url('system_settings/audit_trail/pdf')?>";
            return false;
        });
        $('#xls').click(function (event) {
            event.preventDefault();
            window.location.href = "<?=site_url('system_settings/audit_trail/0/xls/?v=1'.$v)?>";
            return false;
        });

    });
</script>

