<?php

$v = "";
/* if($this->input->post('name')){
  $v .= "&product=".$this->input->post('product');
  } */
if ($this->input->post('reference_no')) {
    $v .= "&reference_no=" . $this->input->post('reference_no');
}
if ($this->input->post('customer')) {
    $v .= "&customer=" . $this->input->post('customer');
}
if ($this->input->post('biller')) {
    $v .= "&biller=" . $this->input->post('biller');
}
if ($this->input->post('warehouse')) {
    $v .= "&warehouse=" . $this->input->post('warehouse');
}
if ($this->input->post('user')) {
    $v .= "&user=" . $this->input->post('user');
}
if ($this->input->post('serial')) {
    $v .= "&serial=" . $this->input->post('serial');
}
if ($this->input->post('start_date')) {
    $v .= "&start_date=" . $this->input->post('start_date');
}
if ($this->input->post('end_date')) {
    $v .= "&end_date=" . $this->input->post('end_date');
}

?>

<script>
    $(document).ready(function () {
		function format(x){
			if (x != null) {
				return '<div class="text-right">'+x.toFixed(2)+'</div>';
			} else {
				return '<div class="text-right">0</div>';
			}
		}
			var oTable =$('#PrRData').dataTable({
			 "aaSorting": [[0, "desc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
            "iDisplayLength": <?= $Settings->rows_per_page ?>,
            
		    "fnFooterCallback": function ( nRow, aaData, iStart, iEnd, aiDisplay ){
             
            }
		}).fnSetFilteringDelay().dtFilter([
            {column_number: 0, filter_default_label: "[<?=lang('Nº');?>]", filter_type: "text", data: []},
			{column_number: 1, filter_default_label: "[<?=lang('enterprise');?>]", filter_type: "text", data: []},
            {column_number: 2, filter_default_label: "[<?=lang('Year');?>]", filter_type: "text", data: []},
			{column_number: 3, filter_default_label: "[<?=lang('Month');?>]", filter_type: "text", data: []},
			{column_number: 4, filter_default_label: "[<?=lang('Taxable Value');?>]", filter_type: "text", data: []},
			{column_number: 5, filter_default_label: "[<?=lang('Journal Date');?>]", filter_type: "text", data: []},
			{column_number: 6, filter_default_label: "[<?=lang('VAT');?>]", filter_type: "text", data: []},
			{column_number: 7, filter_default_label: "[<?=lang('Journal Location');?>]", filter_type: "text", data: []},
			{column_number: 8, filter_default_label: "[<?=lang('action');?>]", filter_type: "text", data: []},
			
        ], "footer");;
    });
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#form').hide();
        <?php if ($this->input->post('customer')) { ?>
        $('#customer').val(<?= $this->input->post('customer') ?>).select2({
            minimumInputLength: 1,
            data: [],
            initSelection: function (element, callback) {
                $.ajax({
                    type: "get", async: false,
                    url: site.base_url + "customers/suggestions/" + $(element).val(),
                    dataType: "json",
                    success: function (data) {
                        callback(data.results[0]);
                    }
                });
            },
            ajax: {
                url: site.base_url + "customers/suggestions",
                dataType: 'json',
                quietMillis: 15,
                data: function (term, page) {
                    return {
                        term: term,
                        limit: 10
                    };
                },
                results: function (data, page) {
                    if (data.results != null) {
                        return {results: data.results};
                    } else {
                        return {results: [{id: '', text: 'No Match Found'}]};
                    }
                }
            }
        });

        $('#customer').val(<?= $this->input->post('customer') ?>);
        <?php } ?>
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


<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-heart"></i><?= lang('Purchase Journal List'); ?> <?php
            if ($this->input->post('start_date')) {
                echo "From " . $this->input->post('start_date') . " to " . $this->input->post('end_date');
            }
            ?></h2>

        <div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown"><a href="#" class="toggle_up tip" title="<?= lang('hide_form') ?>"><i
                            class="icon fa fa-toggle-up"></i></a></li>
                <li class="dropdown"><a href="#" class="toggle_down tip" title="<?= lang('show_form') ?>"><i
                            class="icon fa fa-toggle-down"></i></a></li>
            </ul>
        </div>
        <div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown"><a href="#" id="pdf" class="tip" title="<?= lang('download_pdf') ?>"><i
                            class="icon fa fa-file-pdf-o"></i></a></li>
                <li class="dropdown"><a href="#" id="xls" class="tip" title="<?= lang('download_xls') ?>"><i
                            class="icon fa fa-file-excel-o"></i></a></li>
                <li class="dropdown"><a href="#" id="image" class="tip" title="<?= lang('save_image') ?>"><i
                            class="icon fa fa-file-picture-o"></i></a></li>
            </ul>
        </div>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <p class="introtext"><?= lang('customize_report'); ?></p>

                <div id="form">

                    <?php echo form_open("reports/sales"); ?>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="reference_no"><?= lang("reference_no"); ?></label>
                                <?php echo form_input('reference_no', (isset($_POST['reference_no']) ? $_POST['reference_no'] : ""), 'class="form-control tip" id="reference_no"'); ?>

                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="user"><?= lang("created_by"); ?></label>
                                <?php
                                $us[""] = "";
                                foreach ($users as $user) {
                                    $us[$user->id] = $user->first_name . " " . $user->last_name;
                                }
                                echo form_dropdown('user', $us, (isset($_POST['user']) ? $_POST['user'] : ""), 'class="form-control" id="user" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("user") . '"');
                                ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="customer"><?= lang("customer"); ?></label>
                                <?php echo form_input('customer', (isset($_POST['customer']) ? $_POST['customer'] : ""), 'class="form-control" id="customer" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("customer") . '"'); ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="biller"><?= lang("biller"); ?></label>
                                <?php
                                $bl[""] = "";
                                foreach ($billers as $biller) {
                                    $bl[$biller->id] = $biller->company != '-' ? $biller->company : $biller->name;
                                }
                                echo form_dropdown('biller', $bl, (isset($_POST['biller']) ? $_POST['biller'] : ""), 'class="form-control" id="biller" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("biller") . '"');
                                ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="warehouse"><?= lang("warehouse"); ?></label>
                                <?php
                                $wh[""] = "";
                                foreach ($warehouses as $warehouse) {
                                    $wh[$warehouse->id] = $warehouse->name;
                                }
                                echo form_dropdown('warehouse', $wh, (isset($_POST['warehouse']) ? $_POST['warehouse'] : ""), 'class="form-control" id="warehouse" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("warehouse") . '"');
                                ?>
                            </div>
                        </div>
						<?php if($this->Settings->product_serial) { ?>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <?= lang('serial_no', 'serial'); ?>
                                    <?= form_input('serial', '', 'class="form-control tip" id="serial"'); ?>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("start_date", "start_date"); ?>
                                <?php echo form_input('start_date', (isset($_POST['start_date']) ? $_POST['start_date'] : ""), 'class="form-control datetime" id="start_date"'); ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("end_date", "end_date"); ?>
                                <?php echo form_input('end_date', (isset($_POST['end_date']) ? $_POST['end_date'] : ""), 'class="form-control datetime" id="end_date"'); ?>
                            </div>
                        </div>
						<div class="col-sm-4">
							<div class="form-group">
								<label class="control-label"
									for="customer_group"><?php echo $this->lang->line("group_customer"); ?>
								</label>
								<div class="controls"> <?php
									foreach ($customer_groups as $customer_group) {
										$cgs[$customer_group->id] = $customer_group->name;
									}
									echo form_dropdown('customer_group', $cgs, $this->Settings->customer_group, 'class="form-control tip select" id="customer_group" style="width:100%;"');
									?>
								</div>
							</div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div
                            class="controls"> <?php echo form_submit('submit_report', $this->lang->line("submit"), 'class="btn btn-primary"'); ?> </div>
                    </div>
                    <?php echo form_close(); ?>

                </div>
                <div class="clearfix"></div>

                <div class="table-responsive">
                    <table id="PrRData"
                           class="table table-bordered table-hover table-striped table-condensed reports-table">
                        <thead>
                        <tr>
                            <th><?= lang("Nº"); ?></th>
                            <th><?= lang("enterprise"); ?></th>
                            <th><?= lang("Year"); ?></th>
                            <th><?= lang("Month"); ?></th>
                            <th><?= lang("Taxable Value"); ?></th>
                            <th><?= lang("VAT"); ?></th>
							<th><?= lang("Journal Date"); ?></th>
                            <th><?= lang("Journal Location"); ?></th>
                            <th><?= lang("action"); ?></th>
                           
                        </tr>
                        </thead>
                        <tbody>
						<!--Place Edit Code-->
						<?php
							$i=1;
							foreach($confirm_tax as $confirm_tax_report){	
							$monthName = date("F", mktime(0, 0, 0, $confirm_tax_report->monthly, 1));
						?>
							<tr>
								<td><?=$i;?></td><td><?=$confirm_tax_report->yearly;?><input type="hidden" class="year" value="<?=$confirm_tax_report->yearly;?>"></td>
								<td><?=$confirm_tax_report->company;?></td>
								<td><?=$monthName;?><input type="hidden" class="month" value="<?=$confirm_tax_report->monthly?>"></td>
								<td><?=$this->erp->formatMoney($confirm_tax_report->amount);?></td>
								<td><?=$this->erp->formatMoney($confirm_tax_report->amount_tax);?></td>
								<td><?=form_input('jn_date', $confirm_tax_report->journal_date, 'class="form-control datetime jn_date" id="jn_date"'); ?></td>
								<td><?=form_input('location', $confirm_tax_report->journal_location, 'class="form-control location" id="location"'); ?></td>
								<td><?="<div class='text-center'><a class=\"tip\" title='" . lang("view_report") . "'target='_blank' href='" . site_url('taxes_reports/purc_jounal_view_form/'.($confirm_tax_report->monthly).'/'.$confirm_tax_report->yearly.'/'.$confirm_tax_report->group_id) . "'><span class='label label-primary'>" . lang("view_report") . "</span></a></div>"?></td>
							</tr>
						<?php
							$i++;
						} 
						
						?>
						<!--Place Edit Code-->
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
                            <th></th>
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
		$('.datetime').datetimepicker({format: 'yyyy-mm-dd'});
		
        $('#pdf').click(function (event) {
            event.preventDefault();
            window.location.href = "<?=site_url('reports/getSalesReport/pdf/?v=1'.$v)?>";
            return false;
        });
        $('#xls').click(function (event) {
            event.preventDefault();
            window.location.href = "<?=site_url('reports/getSalesReport/0/xls/?v=1'.$v)?>";
            return false;
        });
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
	
		 $('.jn_date').live('change ',function(){
				if(confirm("Are you sure you want to update this?")){
					var journal_date = $(this).val();
					var parent = $(this).parent().parent();
					var month = parent.children("td:nth-child(3)").find(".month").val();
					var year = parent.children("td:nth-child(2)").find(".year").val();
					
					$.ajax({
						type: "get",
						async: false,
						url: "<?= site_url('taxes_reports/update_journal_date') ?>",
                        data:{journal_date:journal_date,month:month,year:year,'type':'PURC'},
						success:function(re){
						
						}
					});
				}else{
					return false;
				}
			});	
			
			$('.location').live('change ',function(){
				if(confirm("Are you sure you want to update this?")){
					var loc = $(this).val();
					var parent = $(this).parent().parent();
					var month = parent.children("td:nth-child(3)").find(".month").val();
					var year = parent.children("td:nth-child(2)").find(".year").val();
					var location = loc.replace("%20","");
					
					$.ajax({
						type: "get",
						async: false,
						url: "<?= site_url('taxes_reports/update_journal_loc') ?>",
                        data: {location:location,month:month,year:year,'type':'PURC'},
						success:function(re){
						 
						}
					});
				}else{
					return false;
				}
			});	
			
		});
</script>