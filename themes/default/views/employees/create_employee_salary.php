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
$tax_exchange_rate = $this->employee_modal->getTaxExchangeRateByMY($month, $year);
$rate = $tax_exchange_rate->salary_khm?$tax_exchange_rate->salary_khm:0;

?>
<script>
	
	$(window).load(function(){
		$('.basic_salary, .basic_salary2, .basic_salary3').trigger('change');
		$('.salary_tax, .salary_tax2, .salary_tax3').trigger('change');
		$('.spouse').trigger('change');
		$('.spouse').trigger('change');
		
		
	});
	var total_basic_salary = 0, total_salary_tax = 0, total_tax_to_paid = 0;
    $(document).ready(function () {
        var ti = 0;
		
		$(document).on('change keyup paste', '.basic_salary, .salary_tax, .spouse, .minor_child', function () {
            var row = $(this).closest('tr');
            //row.first('td').find('input[type="checkbox"]').iCheck('check');
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
		
		function grandtotalhtml2(cls=""){
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

		//--Resident--//
		$(document).on('change keypress keyup paste', '.basic_salary, .salary_tax, .spouse, .minor_child', function () {
            var row = $(this).closest('tr');
           //row.first('td').find('input[type="checkbox"]').iCheck('check');
			
			// Has been config!!!!
			var rate = '<?php echo $rate?$rate:0; ?>';
			
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
			
			row.find('.salary_tax_to_be_paid').text(formatNumber(parseFloat(salary_tax * rate), 0));
			row.find('.allowance').text(formatNumber(allowance_cal, 0));
			row.find('.salary_tax_calulation_base').text(formatNumber(salary_base_cal, 0));
			row.find('.tax_rate').text(tax_percent_cal + ' %');
			row.find('.salary_tax_cal').text(formatNumber(salary_tax_cal, 0));
			
			$(".g_total_basic").html(formatNumber(grandtotalval("basic_salary")));
			$(".g_salary_tax").html(formatNumber(grandtotalval("salary_tax")));

			var a = $("#resTable").find('tfoot tr th');
			if(a.hasClass('salary_tax_to_be_paid')){
				$("#resTable").find('tfoot tr th').removeClass('salary_tax_to_be_paid');
			}
			if(a.hasClass('allowance')){
				$("#resTable").find('tfoot tr th').removeClass('allowance');
			}
			if(a.hasClass('salary_tax_calulation_base')){
				$("#resTable").find('tfoot tr th').removeClass('salary_tax_calulation_base');
			}
			if(a.hasClass('salary_tax_cal')){
				$("#resTable").find('tfoot tr th').removeClass('salary_tax_cal');
			}
			
			//$(".g_salary_tax_to_be_paid").text(formatNumber(g_to_paid));
			
			$(".g_salary_tax_to_be_paid").text(formatNumber(grandtotalhtml("salary_tax_to_be_paid"), 0));
			
			$(".g_allowance").text(formatNumber(grandtotalhtml("allowance")));
			$(".g_salary_tax_calulation_base").text(formatNumber(grandtotalhtml("salary_tax_calulation_base"), 0));
			$(".g_salary_tax_cal").text(formatNumber(grandtotalhtml("salary_tax_cal"), 0));
			
			//$(".g_spous").html(formatNumber(grandtotalval("spouse")));
			//$(".g_children").html(formatNumber(grandtotalval("minor_child")));
        }).trigger('change');
		
		$(".salary_tax_to_be_paid").each(function(){
				if(!isNaN($(this).text())){
					if($.isNumeric($(this).text())){
						//gtotal+=parseFloat($(this).text()-0);
					}else{
						var noCommas = $(this).text().replace(/,/g, '');
						//alert(noCommas);
					}
				}
			});
		
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
				var taxRate = row.find('.tax_rate').html();
				var salary_tax_to_be_paid = row.find('.salary_tax_to_be_paid').html().replace(/,/g, '')-0;
				salary_tax_to_be_paid = formatDecimal(salary_tax_to_be_paid);
				
				var salary_tax_cal = row.find('.salary_tax_cal').text().replace(/,/g, '')-0;
				salary_tax_cal = formatDecimal(salary_tax_cal / rate);
				
				var month = $("#month").val();
				var year = $("#year").val();
				var date = $("#date").val();	
				var isCompany = $("#salary_tax_type").val();
				var date_insert = year + '-' + month;
				
				var salary_tax_cal_base_input = row.find('.salary_tax_calulation_base').text().replace(/,/g, '')-0;
				salary_tax_cal_base_input = formatDecimal(salary_tax_cal_base_input);
				
				var salary_tax_cal_riel = row.find('.salary_tax_cal').text().replace(/,/g, '')-0;
				salary_tax_cal_riel = formatDecimal(salary_tax_cal_riel);
				
				items[i] = {'id':id, 'basic_salary':basic_salary, 'amount_usd':salary_tax , 'spouse':spouse, 'minor_child': minor_child, 'date_insert': date_insert, 'isCompany': isCompany, 'date': date, 'salary_tax_cal': salary_tax_cal, 'salary_tax_calulation_base':salary_tax_cal_base_input, 'salary_tax_cal_riel': salary_tax_cal_riel, 'remark': remark_note, 'tax_rate': taxRate, 'salary_tobe_paid': salary_tax_to_be_paid};
				i++;
			});
			
			$.ajax({
				type: 'post',
				url: '<?= site_url('employees/update_employee_salary/1'); ?>',
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
			
		});
		
		function basic_salary_input(x) {
            ti = ti+1;
            var v = x.split('__');
            return "<div class=\"text-center\"><input type=\"text\" style=\"width:100%;\" name=\" "+x+"_basic_salary"+x+"\" value=\""+(x == 'Yes' ? '1' : formatDecimal(x))+"\" class=\"form-control text-center basic_salary\" tabindex=\""+(ti)+"\" style=\"padding:2px;height:auto;\"></div>"; // onclick=\"this.select();\"
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
			return "<div class=\"text-center\"><textarea style=\"width:100%;height:33px !important;\" name=\"remark\" value=\"\" class=\"form-control text-center remark\" tabindex=\""+(ti)+"\" style=\"padding:2px;height:auto;\">"+x+"</textarea></div>";
		}
		//--end Resident--//
		
		//--Non-resident--//
		g_total_salary_cal2=0;
		$(document).on('change keypress keyup paste', '.basic_salary2, .salary_tax2', function () {
            var row = $(this).closest('tr');
       // row.first('td').find('input[type="checkbox"]').iCheck('check');
		  
			
			// Has been config!!!!
			var rate = '<?php echo $rate?$rate:0; ?>';
			
			var basic_salary = row.find('.basic_salary2').val()-0;
			var salary_tax = row.find('.salary_tax2').val()-0;
			
			var tax_percent_cal2 = 20;
			var salary_tax_cal2 = parseFloat(salary_tax * rate * tax_percent_cal2/100)-0;
			row.find('.salary_tax_to_be_paid2').text(formatNumber(parseFloat(salary_tax * rate), 0));
			row.find('.tax_rate2').text(tax_percent_cal2 + ' %');
			row.find('.salary_tax_cal2').text(formatNumber(salary_tax_cal2, 0));
			
			$(".g_total_basic2").html(formatNumber(grandtotalval("basic_salary2")));
			$(".g_salary_tax2").html(formatNumber(grandtotalval("salary_tax2")));

			var a = $("#nResTable").find('tfoot tr th');
			if(a.hasClass('salary_tax_to_be_paid2')){
				$("#nResTable").find('tfoot tr th').removeClass('salary_tax_to_be_paid2');
			}
			
			//$(".g_salary_tax_to_be_paid").text(formatNumber(g_to_paid));
			
			$(".g_salary_tax_to_be_paid2").text(formatNumber(grandtotalhtml("salary_tax_to_be_paid2"), 0));
			
			g_total_salary_cal2+=salary_tax_cal2;

			$(".g_salary_tax_cal2").text(formatNumber(g_total_salary_cal2/2, 0));
			
		 }).trigger('change');
		 
		 
		$(document).on('click', '#save_d2', function () {
			var rows = $(".employee_salary_id3");
            var btn = $(this);
			var items = [];
			var rate = '<?php echo $rate?$rate:0; ?>';

			var i = 0;
			$(".employee_salary_id2").each(function(){
				var row = $(this);
				var id = row.attr('id').split('_')[0];

				var basic_salary = row.find('.basic_salary2').val();
				var salary_tax = row.find('.salary_tax2').val();
				var remark_note = row.find('.remark2').val();
				var taxRate = 20;
				var salary_tax_to_be_paid = row.find('.salary_tax_to_be_paid2').html().replace(/,/g, '')-0;
				salary_tax_to_be_paid = formatDecimal(salary_tax_to_be_paid);
				
				var salary_tax_cal = row.find('.salary_tax_cal2').text().replace(/,/g, '')-0;
				salary_tax_cal = formatDecimal(salary_tax_cal / rate);
				
				var month = $("#month").val();
				var year = $("#year").val();
				var date = $("#date").val();	
				var isCompany = $("#salary_tax_type").val();
				var date_insert = year + '-' + month;
				
				var salary_tax_cal_riel = row.find('.salary_tax_cal2').text().replace(/,/g, '')-0;
				salary_tax_cal_riel = formatDecimal(salary_tax_cal_riel);
				
				items[i] = {'id':id, 'basic_salary':basic_salary, 'amount_usd':salary_tax , 'date_insert': date_insert, 'isCompany': isCompany, 'date': date, 'salary_tax_cal': salary_tax_cal, 'salary_tax_cal_riel': salary_tax_cal_riel, 'remark': remark_note, 'tax_rate': taxRate, 'salary_tobe_paid': salary_tax_to_be_paid};
				i++;
			});
			
			$.ajax({
				type: 'post',
				url: '<?= site_url('employees/update_employee_salary/2'); ?>',
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
			
		});
		
		function basic_salary_input2(x) {
            ti = ti+1;
            var v = x.split('__');
            return "<div class=\"text-center\"><input type=\"text\" style=\"width:100%;\" name=\" "+x+"_basic_salary"+x+"\" value=\""+(x == 'Yes' ? '1' : formatDecimal(x))+"\" class=\"form-control text-center basic_salary2\" tabindex=\""+(ti)+"\" style=\"padding:2px;height:auto;\"></div>"; // onclick=\"this.select();\"
        }
		
		function salary_tax_input2(x) {
            ti = ti+1;
            var v = x.split('__');
            return "<div class=\"text-center\"><input type=\"text\" style=\"width:100%;\" name=\" "+v[0]+"_salary_tax"+v[0]+"\" value=\""+(v[0] == 'Yes' ? '1' : formatDecimal(v[0]))+"\" class=\"form-control text-center salary_tax2\" tabindex=\""+(ti)+"\" style=\"padding:2px;height:auto;\"></div>"; // onclick=\"this.select();\"
        }
		
		function show_textarea2(x){
			return "<div class=\"text-center\"><textarea style=\"width:100%;height:33px !important;\" name=\"remark\" value=\"\" class=\"form-control text-center remark2\" tabindex=\""+(ti)+"\" style=\"padding:2px;height:auto;\">"+x+"</textarea></div>";
		}
		//--end Non-Resident--//
		
		//--Fringe Benefit--//
		var total_salary_cal3=0;
		$(document).on('change keypress keyup paste', '.basic_salary3, .salary_tax3', function () {
			
            var row = $(this).closest('tr');
           // row.first('td').find('input[type="checkbox"]').iCheck('check');
			
			// Has been config!!!!
			var rate = '<?php echo $rate?$rate:0; ?>';
			
			var basic_salary = row.find('.basic_salary3').val()-0;
			var salary_tax = row.find('.salary_tax3').val()-0;
			
			var tax_percent_cal3 = 20;
			var salary_tax_cal3 = parseFloat(salary_tax * rate * tax_percent_cal3/100)-0;
			row.find('.salary_tax_to_be_paid3').text(formatNumber(parseFloat(salary_tax * rate), 0));
			row.find('.tax_rate3').text(tax_percent_cal3 + ' %');
			row.find('.salary_tax_cal3').text(formatNumber(salary_tax_cal3, 0));
			total_salary_cal3+=salary_tax_cal3;
			$(".g_total_basic3").html(formatNumber(grandtotalval("basic_salary3")));
			$(".g_salary_tax3").html(formatNumber(grandtotalval("salary_tax3")));

			var a = $("#fbTable").find('tfoot tr th');
			if(a.hasClass('salary_tax_to_be_paid3')){
				$("#fbTable").find('tfoot tr th').removeClass('salary_tax_to_be_paid3');
			}
			
			//$(".g_salary_tax_to_be_paid").text(formatNumber(g_to_paid));
			
			$(".g_salary_tax_to_be_paid3").text(formatNumber(grandtotalhtml("salary_tax_to_be_paid3"), 0));
			
			$(".g_salary_tax_cal3").text(formatNumber(total_salary_cal3/2,0));
			
		 }).trigger('change');
		 
		 
		$(document).on('click', '#save_d3', function () {
				
            var btn = $(this);
			var items = [];
			var rate = '<?php echo $rate?$rate:0; ?>';
			
			var i = 0;
			$(".employee_salary_id3").each(function(){
				var row = $(this);
				var id = row.attr('id').split('_')[0];

				var basic_salary = row.find('.basic_salary3').val();
				var salary_tax = row.find('.salary_tax3').val();
				var remark_note = row.find('.remark3').val();
				var taxRate = 20;
				var salary_tax_to_be_paid = row.find('.salary_tax_to_be_paid3').html().replace(/,/g, '')-0;
				salary_tax_to_be_paid = formatDecimal(salary_tax_to_be_paid);
				
				var salary_tax_cal = row.find('.salary_tax_cal3').text().replace(/,/g, '')-0;
				salary_tax_cal = formatDecimal(salary_tax_cal / rate);
				
				var month = $("#month").val();
				var year = $("#year").val();
				var date = $("#date").val();	
				var isCompany = $("#salary_tax_type").val();
				var date_insert = year + '-' + month;
				
				var salary_tax_cal_riel = row.find('.salary_tax_cal3').text().replace(/,/g, '')-0;
				salary_tax_cal_riel = formatDecimal(salary_tax_cal_riel);
				
				items[i] = {'id':id, 'basic_salary':basic_salary, 'amount_usd':salary_tax , 'date_insert': date_insert, 'isCompany': isCompany, 'date': date, 'salary_tax_cal': salary_tax_cal, 'salary_tax_cal_riel': salary_tax_cal_riel, 'remark': remark_note, 'tax_rate': taxRate, 'salary_tobe_paid': salary_tax_to_be_paid};
				i++;
			});
			
			$.ajax({
				type: 'post',
				url: '<?= site_url('employees/update_employee_salary/3'); ?>',
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
			
		});
		
		function basic_salary_input3(x) {
            ti = ti+1;
            var v = x.split('__');
            return "<div class=\"text-center\"><input type=\"text\" style=\"width:100%;\" name=\" "+x+"_basic_salary"+x+"\" value=\""+(x == 'Yes' ? '1' : formatDecimal(x))+"\" class=\"form-control text-center basic_salary3\" tabindex=\""+(ti)+"\" style=\"padding:2px;height:auto;\"></div>"; // onclick=\"this.select();\"
        }
		
		function salary_tax_input3(x) {
            ti = ti+1;
            var v = x.split('__');
            return "<div class=\"text-center\"><input type=\"text\" style=\"width:100%;\" name=\" "+v[0]+"_salary_tax"+v[0]+"\" value=\""+(v[0] == 'Yes' ? '1' : formatDecimal(v[0]))+"\" class=\"form-control text-center salary_tax3\" tabindex=\""+(ti)+"\" style=\"padding:2px;height:auto;\"></div>"; // onclick=\"this.select();\"
        }
		
		function show_textarea3(x){
			return "<div class=\"text-center\"><textarea style=\"width:100%;height:33px !important;\" name=\"remark\" value=\"\" class=\"form-control text-center remark3\" tabindex=\""+(ti)+"\" style=\"padding:2px;height:auto;\">"+x+"</textarea></div>";
		}
		//--end Fringe Benefit--//
		
        $('#resTable').dataTable({
            "aaSorting": [[2, "asc"], [3, "asc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
            "iDisplayLength": "-1",
			"bLengthChange": false,
            'bProcessing': true, 'bServerSide': true,
            'sAjaxSource': '<?= site_url('employees/getEmployeesSalary').'/?tabcheck=1&'.$searchF ?>',
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
            }, null, null, null, null,<?php if($settings->hide_basic_salary!=1){echo '{"mRender": basic_salary_input}';}else{echo '{"bVisible": false}';}?> , {"mRender": salary_tax_input},{"bVisible": false}, {"bVisible": false},{"mRender":formatDecimal,"sClass": "salary_tax_to_be_paid" }, {"mRender": spouse_input}, {"mRender": minor_child_input}, {"mRender":formatDecimal,"sClass": "allowance" }, {"mRender":formatDecimal,"sClass": "salary_tax_calulation_base" }, {"mRender": null, "sClass": "tax_rate"}, {"mRender":formatDecimal, "sClass": "salary_tax_cal"},null, {"mRender":show_textarea} ,{"bVisible": false}],
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
		
        $('#nResTable').dataTable({
            "aaSorting": [[2, "asc"], [3, "asc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
            "iDisplayLength": "-1",
			"bLengthChange": false,
            'bProcessing': true, 'bServerSide': true,
            'sAjaxSource': '<?= site_url('employees/getEmployeesSalary').'/?tabcheck=2'.$searchF ?>',
			"sDom": 'l<"toolbar2">frtip',
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
            }, null, null, null, null, <?php if($settings->hide_basic_salary!=1){echo '{"mRender": basic_salary_input2}';}else{echo '{"bVisible": false}';}?>, {"mRender": salary_tax_input2}, {"bVisible": false}, {"bVisible": false},{"mRender":formatDecimal,"sClass": "salary_tax_to_be_paid2" }, {"bVisible": false}, {"bVisible": false}, {"bVisible": false}, {"bVisible": false}, {"mRender": null, "sClass": "tax_rate2"}, {"mRender":formatDecimal, "sClass": "salary_tax_cal2"},null, {"mRender":show_textarea2},{"bVisible": false} ],
			'fnRowCallback': function (nRow, aData, iDisplayIndex) {
                nRow.id = aData[0];
			
                nRow.className = "employee_salary_id2";
                return nRow;
            },
			"fnInitComplete": function(oSettings, json){
				$('.basic_salary2:first').focus();
				$('.basic_salary2').trigger('change');
				$('.salary_tax2').trigger('change');
				//$('.spouse').trigger('change');
				//$('.spouse').trigger('change');
				$("div.toolbar2")
				 .html('<button type="button" id="save_d2" class="btn btn-primary"><i class="fa fa-floppy-o" aria-hidden="true"></i> Save</button>');           
			}
        });
		
        $('#fbTable').dataTable({
            "aaSorting": [[2, "asc"], [3, "asc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
            "iDisplayLength": "-1",
			"bLengthChange": false,
            'bProcessing': true, 'bServerSide': true,
            'sAjaxSource': '<?= site_url('employees/getEmployeesSalary').'/?tabcheck=3'.$searchF ?>',
			"sDom": 'l<"toolbar3">frtip',
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
            }, null, null, null, null, {"bVisible": false}, {"bVisible": false},{"mRender": basic_salary_input3}, {"mRender": salary_tax_input3},{"mRender":formatDecimal,"sClass": "salary_tax_to_be_paid3" }, {"mRender": null, "sClass": "tax_rate3"}, {"mRender":formatDecimal, "sClass": "salary_tax_cal3"}, {"bVisible": false},  {"bVisible": false},  {"bVisible": false},  {"bVisible": false},null, {"bVisible": false},{"mRender":show_textarea3} ],
			'fnRowCallback': function (nRow, aData, iDisplayIndex) {
                nRow.id = aData[0];
			
                nRow.className = "employee_salary_id3";
                return nRow;
            },
			"fnInitComplete": function(oSettings, json){
				$('.basic_salary3:first').focus();
				$('.basic_salary3').trigger('change');
				$('.salary_tax3').trigger('change');
				$("div.toolbar3")
				 .html('<button type="button" id="save_d3" class="btn btn-primary"><i class="fa fa-floppy-o" aria-hidden="true"></i> Save</button>');           
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
	#save_d, #save_d2, #save_d3 {
		float:right;
		display:inline;
	}
	td:nth-child(6), td:nth-child(7){
		width:10%;
	}

</style>
			
	<?php echo form_open("employees/salary_tax_action"); ?>		
			<div class="box">
				<div class="box-header">
					<?php 
						$reference = '';
						$isCompany = 0;
						if($month && $year){
							$emp_trigger = $this->employee_modal->getSalaryTaxTriggerByDate($year . '-' . $month);
							$reference = $emp_trigger->reference_no;
							$isCompany = $emp_trigger->isCompany;
						}
					?>
					<h2 class="blue"><i class="fa-fw fa fa-users"></i><?= lang('create_employee_salary'); ?> <?php echo $month? '('.$month . '/' . $year . ')' : ''; ?></h2>

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

								<?php echo form_open("employees/create_employee_salary"); ?>
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
											<?= lang("date", "date"); ?>
											<?php echo form_input('date', (isset($_POST['date']) ? $_POST['date'] : date("d/m/Y")), 'class="form-control date" id="date"'); ?>
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
											$salary_tax_type = array('0' => lang('company'), '1' => lang('employee'));
											echo form_dropdown('salary_tax_type',$salary_tax_type, (isset($_POST['salary_tax_type']) ? $_POST['salary_tax_type'] : $isCompany), 'class="form-control" id="salary_tax_type"'); ?>
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
							
							<ul id="myTab" class="nav nav-tabs parentTabs">
								<li class=""><a href="#resident-con" class="tab-grey"><?= lang('create_employee_salary').' ('.lang('resident').')' ?></a></li>
								<li class=""><a href="#non-resident-con" class="tab-grey"><?= lang('create_employee_salary').' ('.lang('non_resident').')' ?></a></li>
								<li class=""><a href="#fringe-benefit-con" class="tab-grey"><?= lang('create_employee_salary').' ('.lang('fringe_benefit').')' ?></a></li>
							</ul>
							<div class="tab-content">
								
								<div id="resident-con" class="tab-pane fade in">
									<div class="tabbable">
									
										<div class="table-responsive">
											<table id="resTable" cellpadding="0" cellspacing="0" border="0"
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
													<th><?= lang("a"); ?></th>
													<th><?= lang("at"); ?></th>
													<th><?= lang("salary_paid"); ?></th>
													<th><?= lang("spouse"); ?></th>
													<th><?= lang("children"); ?></th>
													<th><?= lang("allowance"); ?></th>
													<th><?= lang("salary_culcalation"); ?></th>
													<th><?= lang("tax_rate"); ?></th>
													<th><?= lang("salary_tax_riel"); ?></th>
													<th><?= lang("status"); ?></th>
													<th style="width:10%;"><?= lang("remark"); ?></th>
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
													<th class="a"></th>
													<th class="at"></th>
													<th class="g_salary_tax_to_be_paid"></th>
													<th class="g_spous"></th>
													<th class="g_children"></th>
													<th class="g_allowance"></th>
													<th class="g_salary_tax_calulation_base"></th>
													<th class="g_tax_rate"></th>
													<th class="g_salary_tax_cal"></th>
													<th class="status"></th>
													<th></th>
													<th></th>
												</tr>
												</tfoot>
											</table>
										</div>

									</div><!--end tabbable!-->
								</div><!--end resident!-->

								<div id="non-resident-con" class="tab-pane fade in">
									<div class="tabbable">

										<div class="table-responsive">
											<table id="nResTable" cellpadding="0" cellspacing="0" border="0"
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
													<th><?= lang("a"); ?></th>
													<th><?= lang("at"); ?></th>
													<th><?= lang("salary_paid"); ?></th>
													<th><?= lang("spouse"); ?></th>
													<th><?= lang("children"); ?></th>
													<th><?= lang("allowance"); ?></th>
													<th><?= lang("salary_culcalation"); ?></th>
													<th><?= lang("tax_rate"); ?></th>
													<th><?= lang("salary_tax_riel"); ?></th>
													<th><?= lang("status"); ?></th>
													<th style="width:10%;"><?= lang("remark"); ?></th>
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
													<th class="g_total_basic2"></th>
													<th class="g_salary_tax2"></th>
													<th class="a"></th>
													<th class="at"></th>
													<th class="g_salary_tax_to_be_paid2"></th>
													<th class="g_spous2"></th>
													<th class="g_children2"></th>
													<th class="g_allowance2"></th>
													<th class="g_salary_tax_calulation_base2"></th>
													<th class="g_tax_rate2"></th>
													<th class="g_salary_tax_cal2"></th>
													<th class="status"></th>
													<th></th>
													<th></th>
												</tr>
												</tfoot>
											</table>
										</div>

									</div><!--end tabbable!-->
								</div><!--end non-resident!-->

								<div id="fringe-benefit-con" class="tab-pane fade in">
									<div class="tabbable">

										<div class="table-responsive">
											<table id="fbTable" cellpadding="0" cellspacing="0" border="0"
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
													<th><?= lang("base_salary"); ?></th>
													<th><?= lang("base_salary_tax"); ?></th>
													<th><?= lang("allowance_basic"); ?></th>
													<th><?= lang("allowance_tax"); ?></th>
													<th><?= lang("allowance_paid"); ?></th>
													<th><?= lang("tax_rate"); ?></th>
													<th><?= lang("salary_tax_riel"); ?></th>
													<th><?= lang("1"); ?></th>
													<th><?= lang("2"); ?></th>
													<th><?= lang("3"); ?></th>
													<th><?= lang("4"); ?></th>
													<th><?= lang("status"); ?></th>
													<th style="width:10%;"><?= lang("remark"); ?></th>
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
													<th class="g_total_basic3"></th>
													<th class="g_salary_tax3"></th>
													<th class="a"></th>
													<th class="at"></th>
													<th class="g_salary_tax_to_be_paid3"></th>
													<th class="g_tax_rate3"></th>
													<th class="g_salary_tax_cal3"></th>
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

									</div><!--end tabbable!-->
								</div><!--end fringe-benefit !-->
							</div><!--end tab-content !-->
							
							
							
							
							
							
							
							
							
							
							
							
							
							
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