<style type="text/css">
        html, body {
            height: 100%;
            background: #FFF;
        }

        body:before, body:after {
            display: none !important;
        }

        .thborder td{
            border:1px solid black;
			height:30px;
        }
		.tddotted{
			border-bottom:1px dotted black;
	}		
	.styletd td{
		height:25px;
		border-left:1px solid black;
		border-right:1px solid black;
		border-bottom:1px dotted black;
	}
    </style>
<div class="modal-dialog modal-lg tax-model" style="width:100%;">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('employee_tax_salary_list'); ?></h4>
        </div>
	<div class="modal-body" style="	overflow-x: auto;">

	
		<div class="stock" id="wrap" style="width: 95%; margin: 0 auto;">			
			<div class="col-lg-12">
					<div class="text-center" style="margin-bottom:15px;">
						<h4><b>ពន្ធកាត់ទុកលើបៀវត្ស</b></h4>
						<h4><b>Salary Tax</b></h4>
						<h4><b>សម្រាប់ខែ<?= $khMonth ?> ឆ្នំា<?= $khYear ?></b></h4>						
						<h4><b>For <?= $enMonth." ".$enYear ?> </b></h4>
					</div>
					
					<div class="col-xs-8" style="float: left;font-size:12px;margin-top:15px;margin-left:-30px;">
							<h5>នាមករណ៍សហគ្រាសៈ  <b><?= $biller->cf1 ?></b></h5>
							<h5>Company Name: <b><?= $biller->company ?></b></h5>
							<h5>លេខអត្តសញ្ញាណកម្មអតបៈ K00៣-<?= $this->erp->KhmerNumDate($biller->vat_no) ?></h5>
							<h5>VAT TIN: K003-<?= $biller->vat_no ?></h5>
							<h5>អាស័យដ្ធានៈ <?= $biller->cf4 ?></h5>
							<h5>Address: <?= $biller->address.", ".$biller->state.", ".$biller->city.", ".$biller->country ?></h5>
							<div><br/></div>
					</div>	
					
			</div>	
			
			<div>
                <table style="width:100%;font-size:12px;">                    
                    <tbody style="font-size:11px;text-align:center;">					
						<tr class="thborder">
							<td style="width:3%;">ល.រ</td>
							<td style="width:30%;">ឈ្មោះ</td>
							<td style="width:10%;">សញ្ជាតិ</td>
							<td style="width:10%;">តួនាទី</td>
							<td style="width:5%;">&nbsp;ទឹកប្រាក់ជាដុល្លារ&nbsp;</td>
							<td style="width:5%;">&nbsp;ប្រាក់បៀរត្សត្រូវបង់&nbsp;</td>
							<td style="width:5%;">&nbsp;សហព័ន្ធ&nbsp;</td>
							<td style="width:5%;">&nbsp;កូនក្នុងបន្ទុក&nbsp;</td>
							<td style="width:5%;">&nbsp;ទឹកប្រាក់កាត់បន្ថយ&nbsp;</td>
							<td style="width:5%;">&nbsp;មូលដ្ធានគិតពន្ធបៀវត្ស&nbsp;</td>
							<td style="width:5%;">&nbsp;អត្រាពន្ធ&nbsp;</td>
							<td style="width:5%;">&nbsp;ពន្ធលើបៀវត្ស&nbsp;</td>
							<td style="width:5%;">&nbsp;កំនត់សម្គាល់&nbsp;</td>										
						</tr>
						<tr class="thborder">
							<td style="width:3%;">No.</td>
							<td style="width:30%;">Name of Employee</td>
							<td style="width:10%;">Nationality</td>
							<td style="width:10%;">Position</td>
							<td style="width:5%;">Amount USD</td>
							<td style="width:5%;">Salary to be paid(Riel)</td>
							<td style="width:5%;">Spouse</td>
							<td style="width:5%;">Minor Children</td>
							<td style="width:5%;">Allowance</td>
							<td style="width:5%;">Salary tax calculation base</td>
							<td style="width:5%;">Tax on Salary</td>
							<td style="width:5%;">Salary Tax(Riel)</td>
							<td style="width:5%;">Remark</td>										
						</tr>
						<tr class="thborder">
							<td style="width:3%;">A</td>
							<td style="width:30%;">B</td>
							<td style="width:10%;">C</td>
							<td style="width:10%;">D</td>
							<td style="width:5%;">E</td>
							<td style="width:5%;">F</td>
							<td style="width:5%;">G</td>
							<td style="width:5%;">H</td>
							<td style="width:5%;">=(G+H)x75,000Riel</td>
							<td style="width:5%;">J=F-1</td>
							<td style="width:5%;">K</td>
							<td style="width:5%;">M=(JxK)-H</td>
							<td style="width:5%;">N</td>										
						</tr>
						<?php 
						$i=1; 
						foreach($datas as $data){
								$allowance = ($data->spouse + $data->minor_children)*75000;
								$salary_base = ($data->salary_tax*$data->khm_rate)-$allowance;
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
									
									$total_basic_salary += $data->basic_salary;
									$total_salary_tax += $data->salary_tax;
									$total_salary_tax_to_be_paid += $data->salary_tax_to_be_paid;
									$total_salary_base += $salary_base;
									$total_salary_tax_riel += $salary_tax;
						?>
						<tr class="styletd">
							<td style="width:3%;"><?= $i ?></td>
							<td style="width:30%;"><?= $data->fullname ?></td>
							<td style="width:10%;"><?= $data->national ?></td>
							<td style="width:10%;"><?= $data->position ?></td>
							<td style="width:5%;"><?= $this->erp->formatMoney($data->salary_tax) ?></td>
							<td style="width:5%;"><?= number_format($data->salary_tax_to_be_paid, 0) ?></td>
							<td style="width:5%;"><?= $data->spouse ?></td>
							<td style="width:5%;"><?= $data->minor_children ?></td>
							<td style="width:5%;"><?= number_format($allowance, 0) ?></td>
							<td style="width:5%;"><?= number_format($salary_base, 0) ?></td>
							<td style="width:5%;"><?= $tax_percent ?> %</td>
							<td style="width:5%;"><?= number_format($salary_tax, 0) ?></td>
							<td style="width:5%;"><?= $data->remark ?></td>				
						</tr>
						<?php 
							if($data->location != ''){
								$location = $data->location;
							}else{
								$location = '';
							}
							if($data->date_print != ''){
								$date_print = $data->date_print;
							}else{
								$date_print = '';
							}
								
							$i++; 
						}//end loop//
						
						if($date_print != ''){
							$date_print = explode('-', $date_print);
							$date_day = $date_print[2];
							$date_month = $date_print[1];
							$date_year = $date_print[0];
							$dateObj=DateTime::createFromFormat('!m', $date_month);
							$date_month_en = $dateObj->format('F');
						}else{
							$date_day = $date_month = $date_month_en = $date_year = '......';
						}
						if($location != ''){
							$location = explode("|", $location);
							$location_kh = $location[0];
							$location_en = $location[1];
						}else{
							$location_kh = $location_en = '.......................';
						}
						?>
						<tr class="thborder" style="font-weight:bold;">
							<td colspan="4" style="width:10%;text-align:right;">Total: </td>
							<td><?= $this->erp->formatMoney($total_salary_tax) ?></td>						
							<td><?= number_format($total_salary_tax_to_be_paid, 0) ?></td>						
							<td></td>						
							<td></td>						
							<td><?= number_format($allowance, 0) ?></td>
							<td><?= number_format($total_salary_base, 0) ?></td>						
							<td></td>											
							<td><?= number_format($total_salary_tax_riel, 0) ?></td>						
							<td></td>						
						</tr>
                    </tbody>
				   </table>
				</div>
				<div><br/></div>
		<div style="text-align:right;font-size:12px;">
			<div class="col-xs-6">
			
			</div>
			<div class="col-xs-6 pull right">				
				<p>ធ្វើនៅ<?= $location_kh."  ថ្ងៃទី  ".$date_day." ខែ ".$this->erp->KhmerMonth($date_month)." ឆ្នំា ".$this->erp->KhmerNumDate($date_year) ?><p>
				<p><?= $location_en.", ".$date_day." ".$date_month_en." ".$date_year ?></p>
				<p>ហត្ថលេខា និង ត្រា Signature & Stamp</p>
			</div>
		</div>
		</div>
		</div> <!-- model content-->
	<div class="modal-footer">
            <?php //echo form_submit('add_purchasing_tax', lang('add_purchasing_tax'), 'class="btn btn-primary"'); ?>
    </div>	