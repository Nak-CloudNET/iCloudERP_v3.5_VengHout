<?php 
$emptype_id =1;
$searchF = '';
if ($this->input->post('employee')) {
	$searchF .= "&employee=" . $this->input->post('employee');
}else{
	$searchF .= NULL;
}
if ($this->input->post('month')) {
	$searchF .= "&month=" . $this->input->post('month');
	$month = $this->input->post('month');
}else{
	$searchF .= "&month=" . date('m');
	$month = date('m');
}
if ($this->input->post('year')) {
	$searchF .= "&year=" . $this->input->post('year');
	$year = $this->input->post('year');
}else{
	$searchF .= "&year=" . date('Y');
	$year = date('Y');
}
if ($this->input->post('salary_tax_type')) {
	$searchF .= "&isCompany=" . $this->input->post('salary_tax_type');
	$type = $this->input->post('salary_tax_type');
}else{
	$searchF .= "&isCompany=0";
	$type = 0;
}
if ($this->input->post('epm_type')) {
		$searchF .= "&epm_type=" . $this->input->post('epm_type');
		$emptype_type = $this->input->post('epm_type');
}
$tax_exchange_rate = $this->employee_modal->getTaxExchangeRateByMY($month, $year);
$rate = $tax_exchange_rate->salary_khm?$tax_exchange_rate->salary_khm:0;

?>

<style type="text/css">
    .topborder div { border-top: 1px solid #CCC; }
</style>
<script>
    $(document).ready(function () {
		
        function total_cash(x) {
            if(x !== null) {
                var y = x.split(' (');
                var z = y[1].split(')');
                return currencyFormat(y[0])+'<span class="text-success">'+currencyFormat(z[0])+'</span><span class="text-danger topborder">'+currencyFormat(y[0]-z[0])+'</span>';
            }
            return '';
        }
        function total_sub(x) {
            if(x !== null) {
                var y = x.split(' (');
                var z = y[0].split(')');
                return y[0]+'<br><span class="text-success">'+z[0]+'</span><span class="text-danger topborder"><div>'+(y[0]-z[0])+'</div></span>';
            }
            return '';
        }
		var rate = <?php echo $rate; ?>;
        var oTable = $('#EMPTaxTable').dataTable({
            "aaSorting": [[0, "desc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
            "iDisplayLength": -1,
            'bProcessing': true, 'bServerSide': true,
            'sAjaxSource': '<?= site_url('taxes_reports/getEmployeeSalaryTaxesReport/?v=1' . $searchF) ?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "<?= $this->security->get_csrf_token_name() ?>",
                    "value": "<?= $this->security->get_csrf_hash() ?>"
                });
                $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
            },
            "aoColumns": [null, null, null, null,  null, null, null, null, null, null, null, null, {"bSortable": false}],
			"fnRowCallback": function(nRow, aData, iDisplayIndex ) {
				var basic_salary = parseFloat(aData[3]);
				var salary_tax = parseFloat(aData[4]);
				var spouse = parseFloat(aData[6]);
				var minor_child = parseFloat(aData[7]);

				/* 
				var cal_salary_tax_to_be_paid = row.find('.salary_tax_to_be_paid').text()-0;
				var cal_allowance = row.find('.allowance').text()-0;
				var cal_salary_tax_calulation_base = row.find('.salary_tax_calulation_base').text()-0;
				var cal_tax_rate = row.find('.tax_rate').text()-0;
				var cal_salary_tax_cal = row.find('.salary_tax_cal').text()-0;
				*/
				
				var allowance_cal = 0;
				allowance_cal = (spouse + minor_child) * 75000;
				
				var salary_base_cal = 0;
				salary_base_cal = (salary_tax*rate)-allowance_cal;
				
				var tax_percent_cal = 0;
				var salary_tax_cal = 0;
				
				if( salary_base_cal >=0 && salary_base_cal <= 1000000 ){
					tax_percent_cal = 0;
				}else if( salary_base_cal >1000000 && salary_base_cal <= 1500000 ){
					tax_percent_cal = 5;
					salary_tax_cal = (salary_base_cal * tax_percent_cal / 100 )-50000;
				}else if( salary_base_cal >1500000 && salary_base_cal <= 8500000 ){
					tax_percent_cal = 10;
					salary_tax_cal = (salary_base_cal * tax_percent_cal / 100 )-125000;
				}else if( salary_base_cal >8500000 && salary_base_cal <= 12500000 ){
					tax_percent_cal = 15;
					salary_tax_cal = (salary_base_cal * tax_percent_cal / 100 )-550000;
				}else if( salary_base_cal >12500000 ){
					tax_percent_cal = 20;
					salary_tax_cal = (salary_base_cal * tax_percent_cal / 100 )-1175000;
				}

				$('td:eq(5)', nRow).html( formatNumber(parseFloat(salary_tax * rate), 0));
				$('td:eq(8)', nRow).html( formatNumber(parseFloat(allowance_cal ), 0));
				$('td:eq(9)', nRow).html( formatNumber(parseFloat(salary_base_cal), 0));
				$('td:eq(10)', nRow).html( tax_percent_cal + ' %' );
				$('td:eq(11)', nRow).html( formatNumber(parseFloat(salary_tax_cal), 0));
			},
			"fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {
				var t_basic_salary = 0, t_salary_tax = 0, t_salary_tax_to_paid = 0, qtotal = 0;
                for (var i = 0; i < aaData.length; i++) {
                    t_basic_salary += parseFloat(aaData[aiDisplay[i]][3]);
                    t_salary_tax += parseFloat(aaData[aiDisplay[i]][4]);
                }
                var nCells = nRow.getElementsByTagName('th');
                nCells[3].innerHTML = currencyFormat(parseFloat(t_basic_salary));
                nCells[4].innerHTML = currencyFormat(parseFloat(t_salary_tax));
            }
        }).fnSetFilteringDelay().dtFilter([
            {column_number: 0, filter_default_label: "[<?=lang('name');?>]", filter_type: "text", data: []},
            {column_number: 1, filter_default_label: "[yyyy-mm]", filter_type: "text", data: []},
            {column_number: 2, filter_default_label: "[<?=lang('position');?>]", filter_type: "text", data: []},
			{column_number: 12, filter_default_label: "[<?=lang('remark');?>]", filter_type: "text", data: []},
        ], "footer");
		
		$('#emp_form').click(function(e){
			e.preventDefault();
			var trigger_id = $("#trigger_id").val()[0];
			
			var empid = $("#employee").val();
			
			var emp_type = '<?=$emptype_type?>';
			if(!emp_type){
				emp_type = $("#epm_type").val()[0];
			}
			if(!empid){
				empid = 0;
			}
			var url='<?=base_url("employees/show_time_teaching_service_form")?>/'+emp_type+'/'+trigger_id+'/'+empid;
			window.open(url, '_blank');
			return false;
		});
		
		$('#create_payment').click(function(e){
			e.preventDefault();
			
			var hasCheck = false;
			var data = [];
			var json_url = '1=1';
			var i = 0;
			$.each($("input[name='c_payment[]']:checked"), function(){
				if($(this).val()){
					hasCheck = true;
					
					var str = $(this).val();
					var substr = str.split('__');
					
					var emp_id = substr[0];
					var salary_tax = substr[1];
					
					data[i] = {'customer_id': emp_id, 'salary_tax': parseFloat(salary_tax)};
					json_url += '&' + emp_id + '=' + parseFloat(salary_tax);
					i++;
				}
			});
			var default_href = "<?php echo site_url('taxes_reports/add_payment_employee_salary_tax'); ?>";
			if(hasCheck == false){
				bootbox.alert('Please select employee first!');
				$(this).attr('href',default_href );
				return false;
			}else{
				var json = JSON.stringify(data);
				var json_plain = JSON.parse(json);
				
				$(this).attr('href', default_href + "?" + json_url);
			}

		});
		
		var parseQueryString = function( queryString ) {
			var params = {}, queries, temp, i, l;
			// Split into key/value pairs
			queries = queryString.split("&amp;");
			// Convert the array of strings into an object
			for ( i = 0, l = queries.length; i < l; i++ ) {
				temp = queries[i].split('=');
				params[temp[0]] = temp[1];
			}
			return params;
		};
    });
	
	function removeCommas(str) {
		while (str.search(",") >= 0) {
			str = (str + "").replace(',', '');
		}
		return str;
	};
</script>
<style>.table td:nth-child(6) {
        text-align: center;
    }</style>

<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-th-large"></i><?= lang('employee_salary_tax'); ?> <?php echo $this->input->post('month')? '('.$this->erp->numberToMonth($this->input->post('month')) . '/' . $this->input->post('year') . ')' : ''; ?></h2>
		
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

                <!--<li class="dropdown"><a href="#" id="xls" class="tip" title="<?= lang('download_xls') ?>"><i
                            class="icon fa fa-file-excel-o"></i></a></li>
                <li class="dropdown"><a href="#" id="image" class="tip" title="<?= lang('save_image') ?>"><i
                            class="icon fa fa-file-picture-o"></i></a></li>-->

            </ul>
        </div>
		<div class="box-icon">
						<ul class="btn-tasks">
							<li class="dropdown">
								<a data-toggle="dropdown" class="dropdown-toggle" href="#">
									<i class="icon fa fa-tasks tip" data-placement="left" title="<?=lang("actions")?>"></i>
								</a>
								<ul class="dropdown-menu pull-right" class="tasks-menus" role="menu" aria-labelledby="dLabel">
									<li>
										<a href="#" id="emp_form">
											<i class="fa fa-plus-circle"></i> <?=lang('add_form_by_group')?>
										</a>
									</li>
									
									<li class="divider"></li>
									
									<li>
										<a id="create_payment" href="<?php echo site_url('taxes_reports/add_payment_employee_salary_tax'); ?>" data-toggle="modal" data-target="#myModal">
											<i class="fa fa-money"></i> <?=lang('create_payment')?>
										</a>
									</li>
	
							   </ul>
						</ul>
					</div>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <p class="introtext"><?= lang('customize_report'); ?></p>

                <div id="form">

                    <?php echo form_open("taxes_reports/employee_salary_tax"); ?>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("employee", "employee"); ?>
                                <?php 
								$emps = array(
									'' => lang('show_all_employees')
								);
								foreach($employees as $emp){
									$emps[$emp->id] = $emp->first_name . ' ' . $emp->last_name;
								}
								echo form_dropdown('employee', $emps, (isset($_POST['employee'])?$_POST['employee']:''), 'class="form-control" id="employee"') ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("month", "month"); ?>
                                <?php 
								$months = array(
											'all' => 'All',
											'01' => 'January',
											'02' => 'February',
											'03' => 'March',
											'04' => 'April',
											'05' => 'May',
											'06' => 'June',
											'07' => 'July',
											'08' => 'August',
											'09' => 'September',
											'10' => 'October',
											'11' => 'November',
											'12' => 'December',
										);
								echo form_dropdown('month', $months, $month, 'id="month" class="form-control"') ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("year", "year"); ?>
                                <?php echo form_input('year', (isset($_POST['year']) ? $_POST['year'] : $year), 'class="form-control date-year" id="year"'); ?>
                            </div>
                        </div>
						<div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("Group", "type"); ?>
								<?php
								$data =array(''=>'ទាំងអស់');
								foreach($types as $type){
									$data[$type->id] = $type->type;
								}
                                echo form_dropdown('epm_type', $data,(isset($_POST['epm_type']) ? $_POST['epm_type'] : ''), 'id="epm_type" class="form-control select" style="width:100%;"');
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

                <div class="clearfix"></div>
				<?php 
	    echo form_open('', 'id="action-form"');

?>
                <div class="table-responsive">
				<table id="EMPTaxTable1" cellpadding="0" cellspacing="0" border="0"
                           class="table table-bordered table-hover table-striped">
					<?php $i=1; foreach($employee_salary_taxesHeader as $dataHeader){ 
					?>
					<thead style="background-color:#428bca; color:white; text-align:center !important; border-top: 1px solid #357ebd; border-bottom-width: 2px; vertical-align: middle !important;">
                        <tr>
                            <th style="text-align:center !important;"><?= $i; ?></th>
							<th style="text-align:center !important;"><?= lang('ID:').$dataHeader->emp_code;?><input type="hidden" id="empid" name="empid[]" value="<?=$dataHeader->employee_id?>"><input type="hidden" id="trigger_id" name="trigger_id[]" value="<?=$dataHeader->trigger_id?>"></th>
							<th style="text-align:center !important;"><?= lang('Employee Name: ').$dataHeader->fullname; ?></th>
                            <th style="text-align:center !important;"><?= lang('Nationality: ').$dataHeader->nationality; ?></th>
                            <th style="text-align:center !important;"><?= lang("Sex: ").$dataHeader->gender; ?></th>
							<th style="text-align:center !important;"><?= lang("Position: ").$dataHeader->position; ?></th>
							<th style="text-align:center !important;"><?= lang("Employed Date: ").$dataHeader->employeed_date; ?></th>
                        </tr>
				   </thead>
					<tbody>
						<tr>
							<td>
								<div class="form-group">
								<?php 
								$c_val = $dataHeader->employee_id . '__' . $dataHeader->salary_tax;
								?>
									<input type="checkbox" class="form-control c_payment" name="c_payment[]" value="<?=$c_val?>" >
								</div>
							</td>
							<td colspan="6">
								<table id="EMPTaxTable1" cellpadding="0" cellspacing="0" border="0"
									   class="table table-bordered table-hover table-striped">
									<thead>
									<tr class="table-bordered">
										<th><?= lang('date'); ?></th>
										<th><?= lang("total_hours"); ?></th>
										<th><?= lang("basic_salary"); ?></th>
										<th><?= lang("salary_tax"); ?></th>
										<th><?= lang("salary_paid"); ?></th>
										<th><?= lang("spouse"); ?></th>
										<th><?= lang("children"); ?></th>
										<th><?= lang("allowance"); ?></th>
										<th><?= lang("salary_culcalation"); ?></th>
										<th><?= lang("tax_rate"); ?></th>
										<th><?= lang("salary_tax_riel"); ?></th>
										<th style="width:10%;"><?= lang("remark"); ?></th>
										<th><?= lang("paid"); ?></th>
										<th><?= lang("balance"); ?></th>
										<th><?= lang("actions"); ?></th>
									</tr>
									</thead>
									<tbody>
									<?php 
										$employee_salary_taxes = $this->taxes_reports_model->getEmployeeSalaryTaxes($dataHeader->employee_id, $dataHeader->sub_where );
										$total_basic_salary = $total_salary_tax = $total_salary_tax_to_be_paid = $total_allowance = $total_salary_base = $total_salary_tax_cal = 0;
										foreach($employee_salary_taxes as $dataItem){
											$allowance = ($dataItem->spouse + $dataItem->minor_children)*75000;
											$salary_base = ($dataItem->salary_tax*$dataItem->khm_rate)-$allowance;
											$tax_percent = 0;
											$salary_tax = 0;
												if( $salary_base >=0 && $salary_base <= 1000000 ){
													$tax_percent = 0;
												}else
												if( $salary_base >1000000 && $salary_base <= 1500000 ){
													$tax_percent = 5;
													$salary_tax = ($salary_base * $tax_percent / 100 )-50000;
												}else
												if( $salary_base >1500000 && $salary_base <= 8500000 ){
													$tax_percent = 10;
													$salary_tax = ($salary_base * $tax_percent / 100 )-125000;
												}else
												if( $salary_base >8500000 && $salary_base <= 12500000 ){
													$tax_percent = 15;
													$salary_tax = ($salary_base * $tax_percent / 100 )-550000;
												}else
												if( $salary_base >12500000 ){
													$tax_percent = 20;
													$salary_tax = ($salary_base * $tax_percent / 100 )-1175000;
												}
												
												
												
											$total_basic_salary += $dataItem->basic_salary;
											$total_salary_tax += $dataItem->salary_tax;
											$total_salary_tax_to_be_paid += $dataItem->salary_tax_to_be_paid;
											$total_allowance += $allowance;
											$total_salary_base += $salary_base;
											$total_salary_tax_riel += $salary_tax;
									?>
										<tr style="text-align:center;">
											<td><?= $dataItem->date ?></td>
											<th><?=$dataItem->total_time?></th>
											<td><?= $this->erp->formatMoney($dataItem->basic_salary) ?></td>
											<td><?= $this->erp->formatMoney($dataItem->salary_tax) ?></td>
											<td><?=number_format($dataItem->salary_tax_to_be_paid,0) ?></td>
											<td><?= $dataItem->spouse ?></td>
											<td><?= $dataItem->minor_children ?></td>
											<td><?=number_format($allowance,0) ?></td>
											<td><?=number_format($salary_base,0) ?></td>
											<td><?= $tax_percent ?> %</td>
											<td><?=number_format($salary_tax,0) ?></td>
											<td><?= $dataItem->remark ?></td>
											<td></td>
											<td></td>
											<td><a class="tip" title="create payment" id="create_payment" href="<?php echo site_url('taxes_reports/add_payment_employee_salary_tax'); ?>" data-toggle="modal" data-target="#myModal">
											<i class="fa fa-money"></i>
										</a> | <a class="tip" title="view payment" id="create_payment" href="<?php echo site_url('taxes_reports/add_payment_employee_salary_tax'); ?>" data-toggle="modal" data-target="#myModal">
											<i class="fa fa-file-text-o"></i>
										</a></td>
										</tr>
										
									<?php } ?>
										<tr style="color:blue; text-align:center;">
											
											<td align="right">Total: </td>
											<th></th>
											<td><?= $this->erp->formatMoney($total_basic_salary) ?></td>
											<td><?= $this->erp->formatMoney($total_salary_tax) ?></td>
											<td><?= number_format($total_salary_tax_to_be_paid,0) ?></td>
											<td></td>
											<td></td>
											<td><?= number_format($total_allowance,0) ?></td>
											<td><?= number_format($total_salary_base,0) ?></td>
											<td></td>
											<td><?= number_format($total_salary_tax_riel,0) ?></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
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
										<th></th>
										<th></th>
										<th></th>
										<th></th>
										<td></td>
										<td></td>
										<td></td>
									</tr>
									</tfoot>
								</table>
							</td>
						</tr>
					</tbody>
					<?php $i++; } ?>
					<tfoot class="dtFilter">
						<tr class="active">
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
<div style="display: none;">
        <input type="hidden" name="form_actions" value="" id="form_actions"/>
        <?=form_submit('performAction', 'performAction', 'id="action_submit"')?>
    </div>
    <?= form_close()?>
            </div>

        </div>
    </div>
</div>


<script type="text/javascript" src="<?= $assets ?>tableExport.jquery/tableExport.js"></script>
<script type="text/javascript" src="<?= $assets ?>tableExport.jquery/jquery.base64.js"></script>

<script type="text/javascript" src="<?= $assets ?>js/html2canvas.min.js"></script>

<script type="text/javascript" src="<?= $assets ?>tableExport.jquery/jspdf/libs/sprintf.js"></script>
<script type="text/javascript" src="<?= $assets ?>tableExport.jquery/jspdf/jspdf.js"></script>
<script type="text/javascript" src="<?= $assets ?>tableExport.jquery/jspdf/libs/base64.js"></script>

<script type="text/javascript">
    $(document).ready(function () {
        $('#form').show();
        $('.toggle_down').click(function () {
            $("#form").slideDown();
            return false;
        });
        $('.toggle_up').click(function () {
            $("#form").slideUp();
            return false;
        });
		/*
        $('#pdf').click(function (event) {
            event.preventDefault();
            window.location.href = "<?=site_url('reports/getRrgisterlogs/pdf/?v=1'.$v)?>";
            return false;
        });
		*/
		

		$('#pdf').click(function (event) {
            event.preventDefault();
            $('#EMPTaxTable').tableExport({type:'pdf',escape:'false'});
            return false;
        });
        $('#xlss').click(function (event) {
            event.preventDefault();
            // $('#EMPTaxTable').tableExport({type:'excel',escape:'false'});
			$('#EMPTaxTable').tableExport({type:'excel',escape:'false'});
            return false;
        });
        $('#image').click(function (event) {
            event.preventDefault();
            $('#EMPTaxTable').tableExport({type:'png',escape:'false'});
            return false;
        });

		
		$('#xls').click(function (event) {
            event.preventDefault();
            var month = $("#month").val();
			var year = $("#year").val();
			
			var isCompany = $("#salary_tax_type").val();
			var employee_id = $("#employee").val();
			
			var date = year + '-' + month;
			
			var data = {'date':date, 'isCompany':isCompany, 'employee_id':employee_id};
				
			window.location.href = "<?=site_url('taxes_reports/export_employee_salary_tax/0/1/?v=1'.$v)?>";
			
			/*
			if(isCompany){
				$.ajax({
					type: 'post',
					url: '<?php echo base_url() ?>taxes_reports/export_employee_salary_tax/0/1',
					data: {
						<?= $this->security->get_csrf_token_name() ?>: '<?= $this->security->get_csrf_hash() ?>',
						date: date, employee_id: employee_id, isCompany:isCompany
					},
					success: function (data) {
						
					},
					error: function (data) {
						
					}
				});
			}
			*/
			
            return false;
        });
		
		
    });
</script>