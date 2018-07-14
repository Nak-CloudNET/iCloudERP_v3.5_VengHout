<?php 
$searchF = '';
if ($this->input->post('employee')) {
	$searchF .= "&employee=" . $this->input->post('employee');
}else{
	$searchF .= NULL;
}
if ($this->input->post('show_tax')) {
	$searchF .= "&show_tax=" . $this->input->post('show_tax');
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
$tax_exchange_rate = $this->erp->employee_modal->getTaxExchangeRateByMY($month, $year);
$rate = $tax_exchange_rate->salary_khm?$tax_exchange_rate->salary_khm:0;
//print_r($tax_exchange_rate);
?>


<script type="text/javascript">
 $(document).ready(function () {
	$(window).on('load',function(){
			$('.basic_salary').trigger('change');
			$('.salary_tax').trigger('change');
			$('.spouse').trigger('change');
			$('.spouse').trigger('change');
			//$(".g_total_basic").html(formatNumber(grandtotal("basic_salary")));
	});
	
	function grandtotalval(cls=""){
		var gtotal = 0;
			$("."+cls).each(function(){
				gtotal+=parseFloat($(this).val()-0);
			});
		return gtotal;
	}
	
	function grandtotalhtml(cls=""){
	    var gtotal = 0;
			$("."+cls).each(function(){		
				var total = $(this).text().replace(/,/g, '');
				if(parseFloat(total) > 0){
					if($.isNumeric(total)){
						gtotal+=parseFloat(total);
					}else{
						gtotal += parseFloat(total);
					}
				}
			});
		return gtotal;
		
	}
	

        var ti = 0;
		
		$(document).on('change keyup paste', '.basic_salary, .salary_tax, .spouse, .minor_child', function () {
            var row = $(this).closest('tr');
            //row.first('td').find('input[type="checkbox"]').iCheck('check');
        });
		
		$(document).on('change keyup paste', '.basic_salary, .salary_tax, .spouse, .minor_child', function () {
            var row = $(this).closest('tr');
            //row.first('td').find('input[type="checkbox"]').iCheck('check');
			
			// Has been config!!!!
			var rate = <?php echo $rate; ?>;
			
			var basic_salary = row.find('.basic_salary').val()-0;
			var salary_tax = row.find('.salary_tax').val()-0;
			var spouse = row.find('.spouse').val()-0;
			var minor_child = row.find('.minor_child').val()-0;
			
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
			
			var tax_percent_cal=0;
			var salary_tax_cal =0;
			var deduction      =0;
			
			if( salary_base_cal >=0 && salary_base_cal <= 1000000 ){
				tax_percent_cal = 0;
			}else if( salary_base_cal >1000000 && salary_base_cal <= 1500000 ){
				tax_percent_cal = 5;
				deduction       =50000;
				salary_tax_cal = ((salary_base_cal * tax_percent_cal / 100 ))-deduction;
			}else if( salary_base_cal >1500000 && salary_base_cal <= 8500000 ){
				tax_percent_cal = 10;
				deduction       =125000;
				salary_tax_cal = ((salary_base_cal * tax_percent_cal / 100 ))-deduction;
			}else if( salary_base_cal >8500000 && salary_base_cal <= 12500000 ){
				tax_percent_cal = 15;
				deduction       =550000;
				salary_tax_cal = ((salary_base_cal * tax_percent_cal / 100 ))-deduction;
			}else if( salary_base_cal >12500000 ){
				tax_percent_cal = 20;
				deduction       =1175000;
				salary_tax_cal = ((salary_base_cal * tax_percent_cal / 100 ))-deduction;
			}
			row.find('.deduction').val(deduction);
			
			var a = $("#CesTable").find('tfoot tr th');
			if(a.hasClass('salary_tax_to_be_paid')){
				$("#CesTable").find('tfoot tr th').removeClass('salary_tax_to_be_paid');
			}
			if(a.hasClass('allowance')){
				$("#CesTable").find('tfoot tr th').removeClass('allowance');
			}
			if(a.hasClass('salary_tax_calulation_base')){
				$("#CesTable").find('tfoot tr th').removeClass('salary_tax_calulation_base');
			}
			if(a.hasClass('salary_tax_cal')){
				$("#CesTable").find('tfoot tr th').removeClass('salary_tax_cal');
			}
			
			row.find('.salary_tax_to_be_paid').html(formatNumber(parseFloat(salary_tax * rate), 0));
			row.find('.allowance').html(formatNumber(allowance_cal, 0));
			row.find('.salary_tax_calulation_base').html(formatNumber(salary_base_cal, 0));
			row.find('.tax_rate').html(tax_percent_cal + ' %');
			row.find('.salary_tax_cal').html(formatNumber(salary_tax_cal, 0));
			
			$(".g_children").html(formatNumber(grandtotalval("minor_child")));
			$(".g_total_basic").html(formatNumber(grandtotalval("basic_salary")));
			$(".g_salary_tax").html(formatNumber(grandtotalval("salary_tax")));
			
			$(".g_salary_tax_to_be_paid").text(formatNumber(grandtotalhtml("salary_tax_to_be_paid")));
			$(".g_children").text(formatNumber(grandtotalval("minor_child")));
			$(".g_spous").text(formatNumber(grandtotalval("spouse")));
			$(".g_allowance").text(formatNumber(grandtotalhtml("allowance")));
			$(".g_salary_tax_calulation_base").text(formatNumber(grandtotalhtml("salary_tax_calulation_base")));
			$(".g_deduction").text(formatNumber(grandtotalval("deduction")));
			$(".g_salary_tax_cal").text(formatNumber(grandtotalhtml("salary_tax_cal")));
        }).trigger('change');
		
		
		
		$(document).on('click', '#save_d', function () {
            var btn = $(this);
			var items = [];
			var rate = '<?php echo $rate?$rate:0; ?>';
			
			var i = 0;
			$(".employee_salary_id").each(function(){
				var row = $(this);
				var id = row.attr('id').split('_')[0];

				var basic_salary = row.find('.basic_salary').val();
				var salary_tax = row.find('.salary_tax').val();
				var spouse = row.find('.spouse').val();
				var minor_child = row.find('.minor_child').val();
				var remark_note = row.find('.remark').val();
				
				var month = $("#month").val();
				var year = $("#year").val();
				var isCompany = $("#salary_tax_type").val();
				var d = new Date();
				var day = d.getDate();
				
				var date_insert = year + '-' + month +'-'+day;
				
				var salary_tax = row.find('.salary_tax').val()-0;
				salary_tax = formatDecimal(salary_tax);
				
				var salary_tax_calulation_base = row.find('.salary_tax_calulation_base').text().replace(/,/g, '')-0;
				salary_tax_calulation_base = formatDecimal(salary_tax_calulation_base);
				
				var salary_tax_cal_riel = row.find('.salary_tax_cal').text().replace(/,/g, '')-0;
				salary_tax_cal_riel = formatDecimal(salary_tax_cal_riel);
				
				
				items[i] = {'id':id, 'basic_salary':basic_salary,'salary_tax':salary_tax,'salary_tax_cal_riel':salary_tax_cal_riel,'salary_tax_calulation_base':salary_tax_calulation_base, 'spouse':spouse, 'minor_child': minor_child, 'date_insert': date_insert, 'isCompany': isCompany, 'remark': remark_note};

				i++;
			});
			
			$.ajax({
				type: 'post',
				url: '<?= site_url('employees/update_employee_salary_small_taxpayers'); ?>',
				dataType: "json",
				async:false,
				data: {
					<?= $this->security->get_csrf_token_name() ?>: '<?= $this->security->get_csrf_hash() ?>',
					items:items
				},
				success: function (data) {
					if (data.status != 1)
						btn.removeClass('btn-primary').addClass('btn-danger').html('<i class="fa fa-times"> </i> Saving failed! try again');
					else
						btn.removeClass('btn-primary').removeClass('btn-danger').addClass('btn-success').html('<i class="fa fa-check"></i> Saved');
				},
				error: function (data) {
					btn.removeClass('btn-primary').addClass('btn-danger').html('<i class="fa fa-times"></i> Saving failed! try again');
				}
			});
			
			
			/*
            btn.html('<i class="fa fa-circle-o-notch fa-spin fa-fw"></i>');
            var row = btn.closest('tr');
            var id = row.attr('id');
            var basic_salary = row.find('.basic_salary').val();
			var salary_tax = row.find('.salary_tax').val();
			var spouse = row.find('.spouse').val();
			var minor_child = row.find('.minor_child').val();
			
			var month = $("#month").val();
			var year = $("#year").val();
			var date_insert = year + '-' + month;

            $.ajax({
                type: 'post',
                url: '<?= site_url('employees/update_employee_salary'); ?>/' + id + '/' + date_insert,
                dataType: "json",
                data: {
                    <?= $this->security->get_csrf_token_name() ?>: '<?= $this->security->get_csrf_hash() ?>',
                    id: id, basic_salary: basic_salary, salary_tax:salary_tax, spouse:spouse
					, minor_child:minor_child
                },
                success: function (data) {
                    if (data.status != 1)
                        btn.removeClass('btn-primary').addClass('btn-danger').html('<i class="fa fa-times"></i>');
                    else
                        btn.removeClass('btn-primary').removeClass('btn-danger').addClass('btn-success').html('<i class="fa fa-check"></i>');
                },
                error: function (data) {
                    btn.removeClass('btn-primary').addClass('btn-danger').html('<i class="fa fa-times"></i>');
                }
            });
			*/
            // btn.html('<i class="fa fa-check"></i>');
        });
		
		function basic_salary_input(x) {
            ti = ti+1;
            var v = x.split('__');
            return "<div class=\"text-center\"><input type=\"text\" style=\"width:100%;\" name=\" "+v[2]+"_basic_salary"+v[0]+"\" value=\""+(v[1] == 'Yes' ? '1' : formatDecimal(v[1]))+"\" class=\"form-control text-center basic_salary\" tabindex=\""+(ti)+"\" style=\"padding:2px;height:auto;\"></div>"; // onclick=\"this.select();\"
        }
		
		function salarypaidhidden(x) {
            ti = ti+1;
            var v = x.split('__');
            return "<div class=\"text-center\"><input type=\"hidden\" style=\"width:100%;\" name=\" "+v[0]+"_salarypaidhidden"+v[0]+"\" value=\""+(v[0] == 'Yes' ? '1' : formatDecimal(v[0]))+"\" class=\"form-control text-center salarypaidhidden\" tabindex=\""+(ti)+"\" style=\"padding:2px;height:auto;\"></div>"; // onclick=\"this.select();\"
        }
		
		function deduction_input(x) {
            ti = ti+1;
            var v = x.split('__');
            return "<div class=\"text-center\"><input type=\"text\" style=\"width:100%;\" name=\" "+v[0]+"_deduction"+v[0]+"\" value=\""+(v[0] == 'Yes' ? '1' : formatDecimal(v[0]))+"\" class=\"form-control text-center deduction\" tabindex=\""+(ti)+"\" style=\"padding:2px;height:auto;\"></div>"; // onclick=\"this.select();\"
        }
		
		function salary_tax_input(x) {
            ti = ti+1;
            var v = x.split('__');
            return "<div class=\"text-center\"><input type=\"text\" style=\"width:100%;\" name=\" "+v[0]+"_salary_tax"+v[0]+"\" value=\""+(v[0] == 'Yes' ? '1' : formatDecimal(v[0]))+"\" class=\"form-control text-center salary_tax\" tabindex=\""+(ti)+"\" style=\"padding:2px;height:auto;\"></div>"; // onclick=\"this.select();\"
        }
		
		function spouse_input(x) {
            ti = ti+1;
            var v = x.split('__');
            return "<div class=\"text-center\"><input type=\"text\" style=\"width:100%;\" name=\" "+v[2]+"_spouse"+v[0]+"\" value=\""+(v[1] == 'Yes' ? '1' : formatDecimal(v[1]))+"\" class=\"form-control text-center spouse\" tabindex=\""+(ti)+"\" style=\"padding:2px;height:auto;\"></div>"; // onclick=\"this.select();\"
        }
		
		function minor_child_input(x) {
            ti = ti+1;
            var v = x.split('__');
            return "<div class=\"text-center\"><input type=\"text\" style=\"width:100%;\" name=\" "+v[2]+"_minor_child"+v[0]+"\" value=\""+(v[1] == 'Yes' ? '1' : formatDecimal(v[1]))+"\" class=\"form-control text-center minor_child\" tabindex=\""+(ti)+"\" style=\"padding:2px;height:auto;\"></div>"; // onclick=\"this.select();\"
        }
		
		function show_textarea(x){
			return "<div class=\"text-center\"><textarea style=\"width:100%;height:33px !important;\" name=\"\" value=\"\" class=\"form-control text-center price\" tabindex=\""+(ti)+"\" style=\"padding:2px;height:auto;\"></textarea></div>";
		}

        $('#CesTable').dataTable({
            "aaSorting": [[2, "asc"], [3, "asc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
            "iDisplayLength": "-1",
            'bProcessing': true, 'bServerSide': true,
            'sAjaxSource': '<?= site_url('employees/getEmployeesSalarySmallTax').'/?'.$searchF ?>',
			"sDom": 'l<"toolbar">frtip',
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
            }, null, null, null, null, <?php if($settings->hide_small_basic_salary!=1){echo '{"mRender": basic_salary_input}';}else{echo '{"bVisible": false}';}?>, {"mRender": salary_tax_input}, {"mRender":formatDecimal,"sClass": "salary_tax_to_be_paid"}, {"mRender": spouse_input}, {"mRender": minor_child_input}, {"mRender":formatDecimal,"sClass": "allowance" }, {"mRender":formatDecimal,"sClass": "salary_tax_calulation_base" }, {"mRender": null, "sClass": "tax_rate"},{"mRender":deduction_input}, {"mRender":formatDecimal, "sClass": "salary_tax_cal"},null, {"mRender":show_textarea} ],
			'fnRowCallback': function (nRow, aData, iDisplayIndex) {
                nRow.id = aData[0];
			
                nRow.className = "employee_salary_id";
                return nRow;
            },
			"fnInitComplete": function(oSettings, json){
				$('.basic_salary:first').focus();
				$('.basic_salary').trigger('change');
				$('.salary_tax').trigger('change');
				$('.spouse').trigger('change');
				$('.spouse').trigger('change');
				$("div.toolbar")
				 .html('<button type="button" id="save_d" class="btn btn-primary"><i class="fa fa-floppy-o" aria-hidden="true"></i> Save</button>');           
			} 
        });
    });
	
</script>

<script type="text/javascript">
    $(document).ready(function () {
	
	
		
        $('#form').hide();
        $('.toggle_down').click(function () {
            $("#form").slideDown();
            return false;
        });
        $('.toggle_up').click(function () {
            $("#form").slideUp();
            return false;
        });

		$(document).on('focus','.date-year', function(t) {
			$(this).datetimepicker({
				format: "yyyy",
				startView: 'decade',
				minView: 'decade',
				viewSelect: 'decade',
				autoclose: true,
			});
		});
    });
</script>
<style>
	#save_d {
		float:right;
		display:inline;
	}
</style>
<?php echo form_open("employees/small_salary_tax_action"); ?>
        
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-users"></i><?= lang('create_employee_salary_small_taxpayers'); ?> <?php echo $this->input->post('month')? '('.$this->input->post('month') . '/' . $this->input->post('year') . ')' : ''; ?></h2>
		<div class="box-icon">
            <ul class="btn-tasks"> 
				<div class="box-icon">
					<ul class="btn-tasks">
						<li class="dropdown">
							<a href="#" class="toggle_up tip" title="<?= lang('hide_form') ?>">
								<i class="icon fa fa-toggle-up"></i>
							</a>
						</li>
						<li class="dropdown">
							<a href="#" class="toggle_down tip" title="<?= lang('show_form') ?>">
								<i class="icon fa fa-toggle-down"></i>
							</a>
						</li>
						<li class="dropdown">
							<a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon fa fa-tasks tip" data-placement="left" title="<?= lang("actions") ?>"></i></a>
							<ul class="dropdown-menu pull-right" class="tasks-menus" role="menu" aria-labelledby="dLabel">
								<li>
									<a href="<?= site_url('#') ?>" id="declare"  data-action="declare">
										<i class="fa fa-plus-circle"></i> <?= lang('declare') ?>
									</a>
								</li>

								<li>
									<a href="#" id="non_declare" data-action="non-declare">
										<i class="fa fa-ban"></i> <?= lang('non-declare') ?>
									</a>
								</li>
								<li>
									<a href="#" id="hide_tax" data-action="hide">
										<i class="fa fa-eye-slash"></i> <?= lang('hide') ?>
									</a>
								</li>
								<li>
									<a href="#" id="show_tax" data-action="show">
										<i class="fa fa-eye "></i> <?= lang('show') ?>
									</a>
								</li>
								<li>
									<a href="#" id="hide_basic_salary" data-action="hide_basic_salary">
										<i class="fa fa-eye-slash"></i> <?= lang('hide_basic_salary_usd') ?>
									</a>
								</li>
								<li>
									<a href="#" id="show_basic_salary" data-action="show_basic_salary">
										<i class="fa fa-eye-slash"></i> <?= lang('show_basic_salary_usd') ?>
									</a>
								</li>
							</ul>
						</li>
					</ul>
				</div>
            </ul>
        </div>
    </div>
		<input name="action_form" id="action_form" type="hidden" value="" />
		<div style="display:none"><?php echo form_submit('submit_sform', $this->lang->line("submit_sform"), 'class="btn btn-primary submit_sform"'); ?> </div>
	<?php echo form_close(); ?>	
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <p class="introtext"><?= lang('list_results'); ?></p>
				
				<div id="form">

                    <?php echo form_open("employees/create_employee_salary_small_taxpayers"); ?>
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
                                <?= lang("tax_obligation", "tax_obligation"); ?>
                                <?php 
								$salary_tax_type = array('0' => lang('Employer'), '1' => lang('employee'));
								echo form_dropdown('salary_tax_type',$salary_tax_type, (isset($_POST['salary_tax_type']) ? $_POST['salary_tax_type'] : ''), 'class="form-control" id="salary_tax_type"'); ?>
                            </div>
                        </div>
						<div class="col-sm-4">
							<div class="form-group">
								<input type="checkbox" id="show" class="form-control" 
								<?php
								if ($this->input->post('show_tax')==1) {
									echo ' checked ';
								}
								?>
								name="show_tax" value="1">
								<span>Show Hidden Tax</span>
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
                    <table id="CesTable" cellpadding="0" cellspacing="0" border="0"
                           class="table table-bordered table-condensed table-hover table-striped">
                        <thead>
                        <tr class="primary">
                            <th style="text-align: center;">
                                <input class="checkbox checkth" type="checkbox" name="check"/>
                            </th>
                            <th><?= lang("no"); ?></th>
                            <th><?= lang("name"); ?></th>
							<th><?= lang("nationality"); ?></th>
                            <th><?= lang("position"); ?></th>
							<th><?= lang("basic_salary"); ?></th>
							<th><?= lang("salary_tax"); ?></th>
                            <th><?= lang("salary_paid"); ?></th>
							<th><?= lang("spouse"); ?></th>
							<th><?= lang("children"); ?></th>
							<th><?= lang("allowance"); ?></th>
							<th><?= lang("salary_culcalation"); ?></th>
							<th><?= lang("tax_rate"); ?></th>
							<th><?= lang("deduction"); ?></th>
							<th><?= lang("salary_tax_riel"); ?></th>
							<th><?= lang("status"); ?></th>
							<th style="width:10%;"><?= lang("remark"); ?></th>
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
                            <th></th>
                            <th></th>
                            <th></th>
							<th></th>
							<th class="g_total_basic"></th>
							<th class="g_salary_tax"></th>
							<th class="g_salary_tax_to_be_paid"></th>
                            <th class="g_spous"></th>
                            <th class="g_children"></th>
							<th class="g_allowance"></th>
							<th class="g_salary_tax_calulation_base"></th>
							<th class="g_tax_rate"></th>
							<th class="g_deduction"></th>
							<th class="g_salary_tax_cal"></th>
							<th class="status"></th>
                            <th></th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
	$('#hide_tax').on('click',  function (e) {
		e.preventDefault();
		var action=$(this).attr("data-action")
		$('#action_form').val(action);
		 $('.submit_sform').click();
	});
	$('#show_tax').on('click',  function (e) {
		e.preventDefault();
		var action=$(this).attr("data-action")
		$('#action_form').val(action);
		 $('.submit_sform').click();
	});
	$('#declare').on('click',  function (e) {
		e.preventDefault();
		var action=$(this).attr("data-action")
		$('#action_form').val(action);
		 $('.submit_sform').click();
	});
	$('#non_declare').on('click',  function (e) {
		e.preventDefault();
		var action=$(this).attr("data-action")
		$('#action_form').val(action);
		 $('.submit_sform').click();
	});
	$('#hide_basic_salary').on('click',  function (e) {
		e.preventDefault();
		var action=$(this).attr("data-action")
		$('#action_form').val(action);
		 $('.submit_sform').click();
	});
	$('#show_basic_salary').on('click',  function (e) {
		e.preventDefault();
		var action=$(this).attr("data-action")
		$('#action_form').val(action);
		 $('.submit_sform').click();
	});
</script>