<title>Salary Tax</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<style>
	body{
		font-family: 'Khmer OS';
	}
	.t_r{text-align:right;}
	.t_c{
		text-align:center;
		}
	.t_l{text-align:left;}
	.bd-gray td{
		border:1px solid gray;
	}
	.md-valign td{
		vertical-align: middle !important;
	}
	.put-border tr td{
		border:1px solid black !important;font-size: 9px;
	}
	.padding-five td{
		padding:5px !important;
	}
	.ta-center td{
		text-align:center;
	}
	.ta-right td{
		text-align:right;
	}
	.float-left{
		float:left;
	}
	.title-style{
		font-size:12px !important; line-height:14px;
	}
	.text-style{
		font-size:9px !important; line-height:14px;
	}
	.input-date td{
		width:30px;height:10px;
	} 
	.table-date td{
		border:0 !important;
	} 
	.padding-less{
		padding:3px !important;
	}
	.textbox {
		padding-top: 2px;
		padding-bottom: 2px;
		padding-left: 2px;
		padding-right: 2px;
		height: 30px;
	}
	
</style>

<body>
	<?php
		if ($error) {
			echo "<div class=\"alert alert-danger\"><button type=\"button\" class=\"close fa-2x\" data-dismiss=\"alert\">&times;</button>" . $error . "</div>";
		}
		if ($message) {
			echo "<div class=\"alert alert-success\"><button type=\"button\" class=\"close fa-2x\" data-dismiss=\"alert\">&times;</button>" . $message . "</div>";
		}
		$attrib = array('class' => 'form-horizontal', 'data-toggle' => 'validator', 'role' => 'form');
		echo form_open_multipart("taxes/salary_tax", $attrib)
	?>
	<div class="col-md-12 col-xs-12  col-lg-12">
		<div>
			<div class="row">
				<div class="col-md-4 col-xs-4  col-lg-4"><center>
					<img src="<?=base_url().'/assets/images/logo_gpot.png'?>" width="140px" height="143px"/>
					<p class="title-style"  style="font-family: 'Khmer OS Muol Light';">អគ្គនាយកដ្ឋានពន្ធដា</p>
					<p class="title-style" style="font-family: 'Khmer OS Muol Light';">នៃក្រសួងសេដ្ឋកិច្ច និងហិរញ្ញវត្ថុ </p></center>
				</div>
				<div class="col-md-4 col-xs-4  col-lg-4">
					<center>
						<p class="title-style" style="font-family: 'Khmer OS Muol Light';">ព្រះរាជាណាចក្រកម្ពុជា</p>
						<p class="title-style" style="font-family: 'Khmer OS Muol Light';">ជាតិ សាសនា ព្រះមហាក្សត្រ</p>
						<img src="<?=base_url()?>/assets/images/line-under.png" height="auto" width="120px"/>
						<div style="border:2px solid black;padding-top:5px;">
						<p class="title-style" style="font-family: 'Khmer OS Muol Light';">លិខិតប្រកាសពន្ធលើប្រាក់បៀវត្ស</p>
						<p class="title-style">Return for Tax on Salary</p>
						</div>
						<p class="text-style" style="padding-top:5px;font-family: 'Khmer OS Muol Light';">(មាត្រា ៥៣ នៃច្បាប់ស្តីពីសារពើពន្ធ) <br/>(Article 53 of the Law on Taxation)</p>
					</center>
				</div>
				<div class="col-md-4 col-xs-4  col-lg-4">
					<div  style="float:right;border:2px solid black; padding:5px,10px,0,10px !important;">
						<p style="margin:5px!important;" class="title-style">ទម្រង់ ពបវ ០១</p>
						<p style="margin:5px!important;" class="title-style">From TOS 01 </p>			
					</div>
					<br/><br/><br/>
					<div style="font-size:9px;float:right; border:1px solid #666; width:6cm;  text-align:left; line-height:14px; margin-top:2px;">
										<div style="margin-left:4px; font-family:'Khmer OS';">
											<center>សម្រាប់មន្ត្រីពន្ធ For Tax Official</center>
											ថ្ងៃទី!____!____!ខែ!____!____!ឆ្នាំ!____!____!<br>
											&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;DD&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;MM&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;YY<br>
											លេខចូល N<sup>o</sup>...................................................<br>
											ហត្ថលេខា<br>
											Signature<br>
											ឈ្មោះមន្ត្រីពន្ធដារ.............................................<br>
											Tax Official name
										</div>
					 </div>	
				</div>
			</div>
		
		<div class="row">
			<div class="col-md-4 col-xs-4  col-lg-4">
			<table class="table table-date table-bordered" style="width:100%;">
				<tr style="border:1px solid black;">
					<th style="background-color:#7f8c8d;color:white; padding-bottom:0 !important;"  class="title-style">02</th>
					<th style=" padding-bottom:0 !important;" colspan="16"><center><p class="text-style">រយៈពេលជាប់ពន្ធសម្រាប់លិខិតប្រកាសនេះPeriod covered by this return </p></center></th>	
				</tr>
				<tr>
				 
						<td class="vertical-align: middle !important padding-less">
						<p class="text-style" style="margin-bottom: 0px;">ពីថ្ងៃទី</p>
						</td>
						<td class="padding-less">
						<input type="text" name="stD" id="stD" class="checknb t_c form-control textbox stD" style="
							width: 56px;
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;
							height: 20px;"><p class="text-style"  >From (DD)</p>
							</td>
						<td class="vertical-align: middle !important padding-less">
						<p class="text-style" style="margin-bottom: 0px;">ខែ</p>
						</td>
						<td  class="padding-less">
						<input type="text" name="stM" id="stM" class="checknb t_c form-control stM" style="
							width: 56px;
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;
							height: 20px;"><p class="text-style"  >Month(MM)</p>
							</td>
						<td class="vertical-align: middle !important padding-less">
						<p class="text-style" style="margin-bottom: 0px;">ឆ្នាំ</p>
						</td>
						<td  class="padding-less">
						<input type="text" name="stY" id="stY" class="checknb t_c form-control stY" style="
							width: 56px;
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;
							height: 20px;"><p class="text-style"  >Year(YYYY) </p>
						</td>
					</td>
					
			
						<td class="vertical-align: middle !important padding-less"  style="border-left: 1px solid #ddd !important;">
						<p class="text-style" style="margin-bottom: 0px;">ដល់ថ្ងៃទី</p>
						</td>
						<td  class="padding-less">
						<input type="text" name="etD" id="etD" class="checknb t_c form-control etD" style="
							width: 56px;
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;
							height: 20px;"><p class="text-style"  >To 	(DD)</p>
							</td>
						<td class="vertical-align: middle !important padding-less">
						<p class="text-style" style="margin-bottom: 0px;">ខែ</p>
						</td>
						<td  class="padding-less">
						<input type="text" name="etM" id="etM" class="checknb t_c form-control etM" style="
							width: 56px;
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;
							height: 20px;"><p class="text-style"  >Month(MM) </p>
							</td>
						<td class="vertical-align: middle !important padding-less">
						<p class="text-style" style="margin-bottom: 0px;">ឆ្នាំ</p>
						</td>
						<td class="padding-less" style="border-right: 1px solid #ddd !important;">
						<input type="text" name="etY" id="etY" class="checknb t_c form-control etY" style="
							width: 56px;
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;
							height: 20px;"><p class="text-style"  >Year(YYYY)</p>
						</td>
					</td>
					
				 </td>
				</tr>
			</table>
			</div>
			<div class="col-md-4 col-xs-4  col-lg-4">
			</div>
			<div class="col-md-offset-1 col-lg-offset-1 col-sm-offset-1 col-md-3 col-xs-3  col-lg-3">
				<table class="table table-date table-bordered  put-border" style="width:100%;">
				<tr style="border:1px solid black;">
					<td style="background-color:#7f8c8d;color:white; padding-bottom:0 !important;width:20px;"  class="title-style">01</td>
					<td style=" padding-bottom:0 !important;" colspan="16"><center><p class="text-style">លេខអត្តសញ្ញាណកម្មសារពើពន្ធ TIN</p></center></td>	
				</tr>
				<tr style="border-right: 1px solid black;">
					<?php 
					for($i=0;$i<9;$i++) { 
					?>
					<td><center><p name="vat[]" id="vat" class="text-style vat">&nbsp;</p></center></td>	
					<?php 
					} 
					?>	
					
					
				</tr>
				
					
			</table>
			</div>
		</div>
		<div class="col-md-12 col-xs-12  col-lg-12" style="border:1px solid #ccc;padding-left:0 !important;">
			<div>
				<div style="background-color:#7f8c8d;color:white; width:20px;padding:5px;float:left;margin-right: 5px;"><p style="font-size:11px;font-weight: bold;">03</p></div>
				<div>
					<div style="float:left; height:10px;">
						<p class="text-style" style="
								margin-bottom: 0px;
								margin-top: 5px;
							">ឈ្មោះសហគ្រាស </p>
						<p class="text-style">Name of Enterprise:</p>
					</div>
					<div style="width:300px; float:left; margin-left:3%;">
						<select name="enterprise" id="enterprise" class="form-control enterprise" required="required">
							<?php			
								echo '<option value=""></option>';
								foreach($enterprise as $ent){
									echo '<option value="'.$ent->id.'">'.$ent->company.'</option>';
								}
							?>
						</select>
					</div>
				</div>
			</div>
			<br/>
			<div>
				<div>
				<!--- start----->
					<table style="margin-left: 25px;">	
						<tr>
							<td><p class="text-style" style="
									margin-bottom: 0px;
									margin-top: 0;
								">សកម្មភាពអាជីវកម្ម <br/>Business_Activities:</p>
							</td>
							<td colspan="5"><input type="text" class="form-control textbox" name="business_act" id="business_act" style="width: 100%;" readonly></td>
						</tr>
						<tr>
							<td><p class="text-style" style="
							margin-bottom: 0px;
							margin-top: 0;
						">អាស័យយដ្ឋាន Address: No</p></td>
							<td><input type="text" id="address" name="address" class="form-control textbox" style="width: 220px;" readonly></td>
							
							<td><p class="text-style" style="
							margin-bottom: 0px;
							float:left;
						"> វិថី Street </p></td><td><input type="text" name="street" id="street" class="form-control textbox" style="width: 200px;" readonly></td>
							
							<td><p class="text-style" style="
							margin-bottom: 0px;
							margin-top: 0;float:left;
						">ក្រុមGroup</p></td><td><input name="group" id="group" type="text" class="form-control textbox" style="width: 200px;" readonly></td>
						</tr>
						<tr>
							<td><p class="text-style" style="
							margin-bottom: 0px;
							margin-top: 0;float:left;
						">ភូមិVillage</p></td><td><input type="text" name="village" id="village" class="form-control textbox" style="width: 220px;" readonly></td>
						
							<td><p class="text-style" style="
							margin-bottom: 0px;
							margin-top: 0;float:left;
						">ឃុំ/សង្កាត់ Sangkat</p></td>
						<td><input type="text" name="sangkat" id="sangkat" class="form-control textbox" style="width: 200px;" readonly></td>
							<td><p class="text-style" style="
							margin-bottom: 0px;
							margin-top: 0;float:left;
						"> ខ័ណ្ឌ /ក្រុង/ស្រុក District </p></td><td><input type="text" name="district" id="district" class="form-control textbox" style="width: 200px;" readonly></td>
						</tr>
						<tr>
							<td><p class="text-style" style="
							margin-bottom: 0px;
							margin-top: 0;float:left;
						"> ខេត្ត/រាជធានី Municipality </p></td><td><input type="text" name="city" id="city" class="form-control textbox" style="width: 220px;" readonly></td>
							<td><p class="text-style" style="
							margin-bottom: 0px;
							margin-top: 0;float:left;
						">ទូរស័ព្ឌ/ទូរសារ Phone/Fax</p></td><td><input type="text" name="phone" id="phone" class="form-control textbox" style="width: 200px;" readonly></td>
							<td><p class="text-style" style="
							margin-bottom: 0px;
							margin-top: 0;float:left;
						">សារអេឡិចត្រូនិច Email </p></td><td><input type="text" name="email" id="email" class="form-control textbox" style="width: 200px;" readonly></td>
						</tr>
					</table><!--- end----->
				</div>
			</div>
		</div>
		<div class="col-md-12 col-xs-12  col-lg-12" style="margin-top:10px;">
			<p>I. <u>ពន្ធលើប្រាក់បៀវត្សចំពោះនិយោជិកនិវាសនជន Tax on Salary on Resident Employees</u>:</p>
				<table class="table table-date table-bordered put-border">
					<tr class="ta-center padding-five">
						<td style="vertical-align: middle !important;background-color:#7f8c8d;color:white;"><b>04<b/></td>
						<td><b>ចំនួននិយោជិក<br/>No of Employee<b/></td>
						<td><b>ប្រាក់បៀវត្សត្រូវបើក<br/>Salary to be Paid<b/></td>
						<td><b>ចំនួនសហព័ន្ធ<br/>No of spouse<b/></td>
						<td><b>ចំនួនកូនក្នុងបន្ទុក<br/>No of M Children<b/></td>
						<td><b>មូលដ្ឋានគិតពន្ធលើប្រាក់បៀវត្ស<br/>Tax on Salary Calculation Base<b/></td>
						<td><b>អត្រាពន្ធ<br/>Tax Rate<b/></td>
						<td><b>ពន្ធលើប្រាក់បៀវត្ស<br/>Tax on Salary<b/></td>
					</tr>
					<tr class="ta-center padding-five">
						<td></td>
						<td>A</td>
						<td>B</td>
						<td>C</td>
						<td>D</td>
						<td>E= B-[(C+D)x75,000]</td>
						<td>F</td>
						<td>G</td>
					</tr>
					
					
					<?php	
					$percent = 0;
					for($i=1;$i<=5;$i++)	{
					echo	'<tr class="padding-five ta-right">
							<td style="width:2%;text-align:center;">'.$i.'</td>
							<td style="width:8%;"><input type="text" name="emp_o4[]" id="emp_04" class="checknb form-control textbox text-right emp_04" style="width: 86px; float:right;"></td>
							<td style="vertical-align: middle !important;width:15%;"><input type="text" name="sal_04_paid[]" id="sal_04_paid" class="checknb form-control textbox text-right sal_04_paid" style="width: 80%;float:left;"><p style="padding-top:5px;margin-bottom: 0px;float:right;">រៀល</p>
							</td>
							<td style="width:12%;"><input type="text" name="spouse[]" id="spouse" class="checknb form-control textbox text-center spouse" style="width: 100%;float:right;"></td>
							<td style="width:12%;"><input type="text" name="no_of_child[]" id="no_of_child" class="checknb form-control textbox text-center no_of_child" style="width: 100%;float:right;"></td>
							<td style="width:16%;"><input type="text" name="sal_cal_04[]" id="sal_cal_04" readonly="" class="checknb form-control textbox text-right sal_cal_04" style="width: 80%;float:left;"><p style="padding-top:5px;margin-bottom: 0px;float:right;">រៀល</p></td>
							<td style="width:12%;"><input type="text" name="tax_rate_04[]" id="tax_rate_04" class="checknb form-control text-center textbox tax_rate_04" value="'.$percent.'" style="width: 90%;float:left;"><p style="padding-top:5px;margin-bottom: 0px;float:right;">%</p></td>
							<td style="width:12%;"><input type="text" name="tax_on_sal_04[]" id="tax_on_sal_04" class="checknb form-control textbox text-right tax_on_sal_04" style="width: 80%;float:left;"><p style="padding-top:5px;margin-bottom: 0px;float:right;">រៀល</p></td>
						</tr>';
						$percent +=5;
						}
					?>	
				
					<tr class="md-valign ta-right">	
						<td>សរុប<br/>Total</td>
						<td><span name="total_emp_04" id="total_emp_04" style="padding-top:5px; margin-right:5px; margin-bottom: 0px;"> 0 </span></td>
						<td><span name="total_sal_04_paid" id="total_sal_04_paid" style="padding-top:5px; margin-right:10px; margin-bottom: 0px;"> 0 </span><span style="padding-top:5px; margin-bottom: 0px;"> រៀល</span></td>
						<td><span name="total_spouse" id="total_spouse" style="padding-top:5px; margin-right:5px; margin-bottom: 0px;"> 0 </span></td>
						<td><span name="total_no_of_child" id="total_no_of_child" style="padding-top:5px; margin-right:5px; margin-bottom: 0px;"> 0 </span></td>
						<td><span name="total_sal_cal_04" id="total_sal_cal_04" style="padding-top:5px; margin-right:10px; margin-bottom: 0px;"> 0 </span><span style="padding-top:5px; margin-bottom: 0px;"> រៀល</span></td>
						<td style="background-color:#7f8c8d;color:white;"></td>
						<td><span name="total_tax_on_sal_04" id="total_tax_on_sal_04" style="padding-top:5px; margin-right:5px; margin-bottom: 0px;"> 0 </span><span style="padding-top:5px; margin-bottom: 0px;"> រៀល</span></td>
					</tr>
					
				</table>
				
				<div>
					<p>II. <u>ពន្ធលើប្រាក់បៀវត្សចំពោះនិយោជិកអនិវាសនជនTax on Salary on Non-Resident Employees</u>:</p>
					<table class="table table-date table-bordered put-border">
						<tr class="ta-center padding-five">
							<td style="vertical-align: middle !important;background-color:#7f8c8d;color:white;"><b>05<b/></td>
							<td><b>ចំនួននិយោជិក<br/>No of Employee<b/></td>
							<td><b>ប្រាក់បៀវត្សត្រូវបើក<br/>Salary to be Paid<b/></td>
							<td style="background-color:#7f8c8d;color:white;"></td>
							<td style="background-color:#7f8c8d;color:white;"></td>
							<td style="background-color:#7f8c8d;color:white;"></td>
							<td><b>អត្រាពន្ធ<br/>Tax Rate<b/></td>
							<td><b>ពន្ធលើប្រាក់បៀវត្ស<br/>Tax on Salary<b/></td>
						</tr>
						<tr class="ta-center padding-five">
							<td></td>
							<td>A</td>
							<td>B</td>
							<td style="background-color:#7f8c8d;color:white;"></td>
							<td style="background-color:#7f8c8d;color:white;"></td>
							<td style="background-color:#7f8c8d;color:white;"></td>
							<td>C</td>
							<td>D = B x C</td>
						</tr>
							<tr class="padding-five ta-right">
							<td style="width:2%;text-align:center;"></td>
							<td style="width:8%;"><input type="text" name="emp_05" id="emp_05"  class="checknb form-control textbox text-right emp_05" style="width: 86px;float:right;"></td>
							<td style="vertical-align: middle !important;width:15%;"><input type="text" name="sal_05_paid" id="sal_05_paid" class="checknb form-control textbox text-right sal_05_paid" style="width: 80%;float:left;"><p style="padding-top:5px;margin-bottom: 0px;float:right;">រៀល</p>
							</td>
							<td style="width:12%;background-color:#7f8c8d;color:white;"></td>
							<td style="width:12%;background-color:#7f8c8d;color:white;"></td>
							<td style="width:16%;background-color:#7f8c8d;color:white;"></td>
							<td style="width:12%;"><input type="text" name="tax_rate_05" id="tax_rate_05" class="checknb form-control textbox text-center tax_rate_05" value="20" style="width: 90%;float:left;"><p style="padding-top:5px;margin-bottom: 0px;float:right;">%</p></td>
							<td style="width:12%;"><input type="text" name="tax_on_sal_05" id="tax_on_sal_05" readonly="" class="checknb form-control textbox text-right tax_on_sal_05" value="" style="width: 80%;float:left;"><p style="padding-top:5px;margin-bottom: 0px;float:right;">រៀល</p></td>
						</tr>
					</table>
				</div>
				<div>
					<p>III. <u>ពន្ធលើប្រាក់បៀវត្សចំពោះអត្ថប្រយោជន៍បន្ថែម Tax on Salary on Fringe Benefit</u>:</p>
					<table class="table table-date table-bordered put-border">
						<tr class="ta-center padding-five">
							<td style="vertical-align: middle !important;background-color:#7f8c8d;color:white;"><b>06<b/></td>
							<td><b>ចំនួននិយោជិក<br/>No of Employee<b/></td>
							<td><b>ប្រាក់បៀវត្សត្រូវបើក<br/>Salary to be Paid<b/></td>
							<td style="background-color:#7f8c8d;color:white;"></td>
							<td style="background-color:#7f8c8d;color:white;"></td>
							<td style="background-color:#7f8c8d;color:white;"></td>
							<td><b>អត្រាពន្ធ<br/>Tax Rate<b/></td>
							<td><b>ពន្ធលើប្រាក់បៀវត្ស<br/>Tax on Salary<b/></td>
						</tr>
						<tr class="ta-center padding-five">
							<td></td>
							<td>A</td>
							<td>B</td>
							<td style="background-color:#7f8c8d;color:white;"></td>
							<td style="background-color:#7f8c8d;color:white;"></td>
							<td style="background-color:#7f8c8d;color:white;"></td>
							<td>C</td>
							<td>D = B x C</td>
						</tr>
						<tr class="padding-five ta-right">
							<td style="width:2%;text-align:center;"></td>
							<td style="width:8%;"><input type="text" name="emp_06" id="emp_06" class="checknb form-control textbox text-right emp_06" style="width: 86px;float:right;"></td>
							<td style="vertical-align: middle !important;width:15%;"><input type="text" name="sal_06_paid" id="sal_06_paid" class="checknb form-control textbox text-right sal_06_paid" style="width: 80%;float:left;"><p style="padding-top:5px;margin-bottom: 0px;float:right;">រៀល</p>
							</td>
							<td style="width:12%;background-color:#7f8c8d;color:white;"></td>
							<td style="width:12%;background-color:#7f8c8d;color:white;"></td>
							<td style="width:16%;background-color:#7f8c8d;color:white;"></td>
							<td style="width:12%;"><input type="text" name="tax_rate_06" id="tax_rate_06" class="checknb form-control textbox text-center tax_rate_06" value="20" style="width: 90%;float:left;"><p style="padding-top:5px;margin-bottom: 0px;float:right;">%</p></td>
							<td style="width:12%;"><input type="text" name="tax_on_sal_06" id="tax_on_sal_06" readonly="" class="checknb form-control textbox text-right tax_on_sal_06" style="width: 80%;float:left;"><p style="padding-top:5px;margin-bottom: 0px;float:right;">រៀល</p></td>
						</tr>
						<tr>
							<td colspan="5" style="border:0 !important;"></td>
							<td colspan="2" style="padding:0 !important;height:31px;"><div style="font-weight: 700;padding:8px;width:31px;height:31px;background-color:#7f8c8d;color:white;float:left;">07</div><p style="margin-bottom: 0 !important;padding-top:5px !important;">សរុបប្រាក់ពន្ធត្រូវបង់<br/>Tota Tax Due</p> </td>
							<td style="text-align:right; vertical-align: middle !important;"><span id="total_tax_due" class="total_tax_due" style="padding-top:5px; margin-right:5px; margin-bottom: 0px;"> 0 </span><span style="padding-top:5px; margin-bottom: 0px;"> រៀល</span></td>
						</tr>
					</table>
				</div>
				<div>
					<p>យើងខ្ញុំបានពិនិត្យគ្រប់ចំណុចទាំងអស់នៅលើលិខិតប្រកាសនេះ និងតារាងឧបសម័្ពន្ធភ្ជាប់មកជាមួយ ។ យើងខ្ញុំមានសក្ខីប័្រតបញ្ជាក់ច្បាស់លាស់ ត្រឹមត្រូវ ពេញលេញ ដែលធានាបានថា ព័ត៌មានទាំងអស់នៅលើលិខិតប្រកាសនេះ ពិតជាត្រឹមត្រូវប្រាកដមែន ហើយគ្មានប្រតិបត្ដិការណាមួយមិនបានប្រកាសនោះទេ ។ យើងខ្ញុំសូមទទួលខុសត្រូវទាំងស្រុងចំពោះមុខច្បាប់ទាំង ឡាយជាធរមាន ប្រសិនបើព័ត៌មានណាមួយមានការក្លែងបន្លំ ។ We have examined all items on this return and the annexes attached here with. We have clear, correct, and full supporting documents to ensure that all information on this return is true and accurate and there is no business operation undeclared. We are fully responsible due to the existing Laws for any falsified information. </p>
					<table width="100%" cellspacing="0" border="0">
						<tbody>
							<tr style="vertical-align: top;">
								<td style=" width:9cm; height:160px;">
									<table width="100%" cellspacing="0" border="0" style="border:1px solid #999; height:140px;">
										<tbody>
											<tr align="center" style="font-size: 9px; vertical-align: top;">
												<td>&nbsp;</td>
												<td colspan="8">
													<font style="font-weight:bolder;">
														<font style="font-family:'Khmer OS'; padding-bottom:15px;"><u>សម្រាប់មន្ត្រីពន្ធដារ </u></font><u> For Tax Official</u>
													</font>
												</td>
												<td>&nbsp;</td>
											</tr>
											<tr  style="font-size: 9px; vertical-align: top;">
												<td></td>
												<td></td>
												<td><font style="font-family:'Khmer OS';">បែបបទនៃការបង់ប្រាក់ៈ</font></td>
												<td><div style="width:0.4cm; height:0.3cm; border:solid 1px #333;"></div></td><td>សាច់ប្រាក់</td>
												<td><div style="width:0.4cm; height:0.3cm; border:solid 1px #333;"></div></td><td>មូលប្បទានប័ត្រ</td>
												<td><div style="width:0.4cm; height:0.3cm; border:solid 1px #333;"></div></td><td>បង្វែរ</td>
												<td></td>
											</tr>
						
											<tr style="font-size: 9px; font-family: &quot;Khmer OS&quot;; vertical-align: top;">
												<td></td>
												<td colspan="8">
													ចំនួនទឹកប្រាក់ ..........................................................................................
												</td>
												<td></td>
											</tr>
											<tr style="font-size: 9px; font-family: &quot;Khmer OS&quot;; vertical-align: top;">
												<td></td>
												<td colspan="8">
													បង់តាមបង្កាន់ដៃលេខ...............................ចុះថ្ងៃទី............/............./.............
												</td>
												<td></td>
											</tr>
											<tr style="font-size: 9px; font-family: &quot;Khmer OS&quot;; vertical-align: top;">
												<td></td>
												<td colspan="8">
													លរ.លន.គណនេយ្យកម្ម.............................ចុះថ្ងៃទី............/............./............
												</td>
												<td></td>
											</tr>
											<tr style="font-size: 9px; font-family: &quot;Khmer OS&quot;; vertical-align: top;">
												<td></td>
												<td colspan="8">
													ឈ្មោះមន្ត្រីពន្ធ.........................................ហត្ថលេខា....................................
												</td>
												<td></td>
											</tr>
										</tbody>
									</table>
								</td>
								<td valign="top" align="center">
									<table cellspacing="0" border="0">
										<tbody>
											<tr style="font-size:9px; vertical-align: top;">
												<td>
													<font style="font-family:'Khmer OS';">ធ្វើនៅ </font><input type="text" style=" size:auto; font-size:9px;border-bottom:dotted 1px #666; border-left: 0px; border-top:0px; font-weight:bolder; text-align:center;" id="kh_made_at" name="kh_made_at" value="">
												   <font style="font-family:'Khmer OS';"> ថ្ងៃទី</font>
												</td>
												<td>
													<input type="text" size="6" style=" font-size:11px; border-bottom:dotted 1px #666; border-left:  0px; border-top:0px; font-family:'Times New Roman', Times, serif; text-align:center; font-weight:bolder" value="<?= date('d'); ?>" name="createD" id="createD" class="valid createD"> 
												   
												</td>
												<td>
													<font style="font-family:'Khmer OS';"> ខែ</font>
													<input type="text" size="6" style=" font-size:11px; border-bottom:dotted 1px #666; border-left: 0px; border-top:0px;font-family:'Times New Roman', Times, serif; text-align:center; font-weight:bolder" value="<?= date('m'); ?>" name="createM" id="createM" class="createM"> 
												   
												</td>
												<td>
												   <font style="font-family:'Khmer OS';"> ឆ្នាំ</font>
													<input type="text" size="6" style=" font-size:11px; border-bottom:dotted 1px #666; border-left:0px; border-top:0px;font-family:'Times New Roman', Times, serif;text-align:center; font-weight:bolder " value="<?= date('Y'); ?>" name="createY" id="createY" class="createY"> 
												</td>
											</tr>
											<tr style="font-size: 9px; vertical-align: top;">
												<td>
													Filed in <input type="text" style="size:auto; font-size:9px;border-bottom:dotted 0px #666; border-left: 0px; border-top:0px; font-family:'Times New Roman', Times, serif; border-bottom:dotted 1px #666; border-left: 0px; border-top:0px; width:160px; text-align:center;" name="en_made_at" value="">
												</td>
												<td class="text-center">
													(DD)
												</td>
												<td class="text-center">
													Month(MM)
												</td>
												<td class="text-center">
													Year(YYYY)
												</td>
											</tr>
											<tr valign="top" align="center" style="vertical-align: top;">
												<td colspan="4">
													<font style="font-size:9px; font-family:'Khmer OS Muol Light'">
														អភិបាល/បណ្ណាធិការ/កម្មសិទិ្ធករ សហគ្រាស
													</font>
												</td>
											</tr>
											<tr valign="top" align="center" style="vertical-align: top;">
												<td colspan="4">
													<font style="font-size:9px;">
														Director/Manager/Owner of Enterprises
													</font>
												</td>
											</tr>
											<tr valign="top" align="center" style="vertical-align: top;">
												<td colspan="4">
													<font style="font-size:9px; font-family:'Khmer OS Muol Light'">
														(ហត្ថលេខា និងត្រា  Signature &amp; Seal)
													</font>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div>
					<p><u>តារាងលម្អិតអំពីពន្ធលើប្រាក់បៀវត្សចំពោះនិយោជិតនិវាសនជន Details on the Tax on Salary on Resident Employees</u> :</p>
					<table  class="table table-date table-bordered put-border">
						<tr class="ta-center">
							<td width="2%"><b>លរ<br/>N<sup>o</sup></b></td>
							<td width="17%"><b>ឈ្មោះនិយោជិក<br/>Name of Employee</b></td>
							<td width="8%"><b>សញ្ជាតិ<br/>Nationality</b></td>
							<td width="8%"><b>មុខងារ <br/>Function</b></td>
							<td width="8%"><b>ប្រាក់បៀវត្សត្រូវបើក <br/>Salary to be Paid</b></td>
							<td width="5%"><b>សហព័ទ្ធ<br/>Spouse</b></td>
							<td width="5%"><b>កូនក្នុងបន្ទុក<br/>Minor Children</b></td>
							<td width="5%"><b>ទឹកប្រាក់កាត់បន្ថយ <br/>Allowance</b></td>
							<td width="5%"><b>មូលដ្ឋានគិតពន្ធ បវ <br/>Salary Tax Calculation Base</b></td>
							<td width="8%"><b>អត្រាពន្ធ<br/>Tax Rate</b></td>
							<td width="5%"><b>ពន្ធលើប្រាក់បៀវត្ស <br/>Tax on Salary</b></td>
							<td width="7%"><b>កំណត់សម្គាល់<br/>Remarks</b></td>
						</tr>
						<tr class="ta-center">
							<td></td>
							<td>A</td>
							<td>B</td>
							<td>C</td>
							<td>D</td>
							<td>E</td>
							<td>F</td>
							<td>G = ( E + F ) x 75,000</td>
							<td>H = D - G</td>
							<td>I</td>
							<td>J</td>
							<td>K</td>
							
						</tr>
						<?php		
						$k = 0;
						$emp_back_01 =  '<select name="emp_back_01[]" id="emp_back_01" class="form-control emp_back_01">';
						foreach($employees as $emp){
							if($k == 0){
								$emp_back_01 .=  '<option value=""></option>';
							}else{
								$emp_back_01 .=  '<option value="'.$emp->id.'">'. ($emp->first_name_kh ? $emp->first_name_kh : $emp->first_name) .' '. ($emp->last_name_kh ? $emp->last_name_kh : $emp->last_name) .'</option>';
							}
							$k++;
						}
						$emp_back_01 .= '</select>';
						for($i=1;$i<11;$i++){
						echo	'<tr>
								<td><p style="padding-top: 5px;margin-bottom: 0px !important;">'.$i.'</p></td>
								<td>'.$emp_back_01.'</td>
								<td><input type="text" name="national_back_01[]" id="national_back_01" class="form-control text-center textbox national_back_01" style="float:right;"></td>
								<td><input type="text" name="function_back_01[]" id="function_back_01" class="form-control text-left textbox function_back_01" style="float:right;"></td>
								<td><input type="text" name="sal_01_paid[]" id="sal_01_paid" class="checknb form-control text-right textbox sal_01_paid" style="float:right;"></td>
								<td><input type="text" name="spouse_back_01[]" id="spouse_back_01" class="checknb form-control text-center textbox spouse_back_01" style="float:right;"></td>
								<td><input type="text" name="child_back_01[]" id="child_back_01" class="checknb form-control text-center textbox child_back_01" style="float:right;"></td>
								<td><input type="text" name="allowance_back_01[]" id="allowance_back_01" readonly="" class="checknb form-control text-right textbox allowance_back_01" style="float:right;"></td>
								<td><input type="text" name="sal_cal_back_01[]" id="sal_cal_back_01" readonly=""  class="checknb form-control text-right textbox sal_cal_back_01" style="float:right;"></td>
								<td><input type="text" name="tax_rate_back_01[]" id="tax_rate_back_01" class="checknb form-control text-right textbox tax_rate_back_01" style="float:left;"><p style="padding-top: 5px;margin-bottom: 0px !important;">%</p></td>
								<td><input type="text" name="tax_on_sal_back_01[]" id="tax_on_sal_back_01" class="checknb form-control text-right textbox tax_on_sal_back_01" style="float:right;"></td>
								<td><input type="text" name="remark_back_01[]" id="remark_back_01" class="form-control text-center textbox remark_back_01" style="float:right;"></td>
							</tr>';
						}
						?>
						<tr>
							<td colspan="2"><p>វិធីគណនាពន្ធលើប្រាក់បៀវត្ស៖<br/>alculation Method of Tax on Salary:</p></td>
							<td style="text-align:center;"><p>ប្រសិនបើ :<br/>If :</p></td>
							<td colspan="9" style="font-size:9px;">- មូលដ្ឋានគិតពន្ធលើប្រាក់បៀវត្ស (H) មានចំនួនចាប់ពី សូន្យ ដល់ ៨០០,០០០ ត្រូវជាប់ពន្ធតាមអត្រា ០% <br/>
										the SalaryTax Calculation Base (H)is from 0 Riel to 800,000Riel , it is taxed at 0%<br/>
										- មូលដ្ឋានគិតពន្ធលើប្រាក់បៀវត្ស (H) មានចំនួនចាប់ពី ៨០០,០០១ ដល់ ១,២៥០,០០០ ត្រូវជាប់ពន្ធតាមអត្រា ៥% វិធីគណនាគឺ : [មូលដ្ឋានគិតពន្ធលើប្រាក់បៀវត្ស(H) x ៥%]–៤០,០០០ <br/>
										the SalaryTax Calculation Base (H)is from 800,001 Riel to 1,250,000 Riel , it is taxedat 5% and the method for calculation is : [ SalaryTax Calculation Base (H) x 5% ] –40,000<br/>
										- មូលដ្ឋានគិតពន្ធលើប្រាក់បៀវត្ស (H) មានចំនួនចាប់ពី ១,២៥០,០០១ ដល់ ៨,៥០០,០០០ ត្រូវជាប់ពន្ធតាមអត្រា ១០% វិធីគណនាគឺ : [មូលដ្ឋានគិតពន្ធលើប្រាក់បៀវត្ស (H) x ១០%]–១០២,៥០០ <br/>
										the SalaryTax Calculation Base (H)is from 1,250,001 Riel to 8,500,000 Riel , it is taxed at 10% and the method for calculation is : [SalaryTax Calculation Base (H) x 10% ] –102,500<br/>
										- មូលដ្ឋានគិតពន្ធលើប្រាក់បៀវត្ស (H) មានចំនួនចាប់ពី ៨,៥០០,០០១ ដល់ ១២,៥០០,០០០ ត្រូវជាប់ពន្ធតាមអត្រា ១៥% វិធីគណនាគឺ : [មូលដ្ឋានគិតពន្ធលើប្រាក់បៀវត្ស (H) x ១៥%]–៥២៧,៥០០ <br/>
										the SalaryTax Calculation Base (H)is from 8,500,001 Riel to 1,250,000 Riel , it is taxedat 15% and the method for calculation is : [SalaryTax Calculation Base (H) x 15% ] –527,500<br/>
										- មូលដ្ឋានគិតពន្ធលើប្រាក់បៀវត្ស (H) មានចំនួនចាប់ពី ១២,៥០០,០០១ ឡើងទៅ ត្រូវជាប់ពន្ធតាមអត្រា ២០% វិធីគណនាគឺ : [មូលដ្ឋានគិតពន្ធលើប្រាក់បៀវត្ស (H) x ២០%]–១,១៥២,៥០០<br/>
										the SalaryTax Calculation Base (H)is from 12,500,001 Riel and over , it is taxed at 20% and the method for calculation is : [ SalaryTax Calculation Base (H) x 20% ] –1,152,500
							</td>																		
						</tr>
					</table>
				</div>
				<div>
					<p style="font-size:12px;">តារាងលម្អិតអំពីពន្ធលើប្រាក់បៀវត្សចំពោះនិយោជិតអនិវាសនជន និងពន្ធលើប្រាក់បៀវត្សចំពោះអត្ថប្រយោជន៍បន្ថែម Details on the Tax on Salary on Non-Resident Employees and Taxon Salary on Fringe Benefit:</p>
					<table   class="table table-date table-bordered put-border">
						<tr class="ta-center">
							<td width="2%"><b>លរ<br/>N<sub>o</sub></b></td>
							<td width="20%"><b>ឈ្មោះនិយោជិក<br/>Name of Employee</b></td>
							<td width="10%"><b>សញ្ជាតិ<br/>Nationality</b></td>
							<td width="10%"><b>មុខងារ <br/>Function</b></td>
							<td width="10%"><b>ប្រាក់បៀវត្សត្រូវបើក/អត្ថប្រយោជន៍បន្ថែម <br/>Salary to be Paid/Fringe Benefit</b></td>
							<td width="10%"><b>អត្រាពន្ធ ២០%<br/>Tax Rate 20%</b></td>
							<td width="10%"><b>ពន្ធលើប្រាក់បៀវត្ស<br/>Tax on Salary</b></td>
							<td width="10%"><b>កំណត់សម្គាល់ <br/>Remarks</b></td>
						</tr>
						<tr class="ta-center">
							<td></td>
							<td>A</td>
							<td>B</td>
							<td>C</td>
							<td>D</td>
							<td>E</td>
							<td>F=DxE</td>
							<td>G</td>
						</tr>
						
						<?php
						$k = 0;
						$emp_back_02 =  '<select name="emp_back_02[]" id="emp_back_02" class="form-control emp_back_02">';
						foreach($employees as $emp){
							if($k == 0){
								$emp_back_02 .=  '<option value=""></option>';
							}else{
								$emp_back_02 .=  '<option value="'.$emp->id.'">'. ($emp->first_name_kh ? $emp->first_name_kh : $emp->first_name) .' '. ($emp->last_name_kh ? $emp->last_name_kh : $emp->last_name) .'</option>';
							}
							$k++;
						}
						$emp_back_02 .= '</select>';
						for($i=1;$i<=7;$i++){
						
						echo	'<tr>
								<td>'.$i.'</td>
								<td>'.$emp_back_02.'</td>
								<td><input type="text" name="national_back_02[]" id="national_back_02" class="form-control text-center textbox national_back_02" style="float:right;"></td>
								<td><input type="text" name="function_back_02[]" id="function_back_02" class="form-control text-left function_back_02" style="float:right;"></td>
								<td><input type="text" name="sal_02_benefit[]" id="sal_02_benefit" class="checknb form-control text-right textbox textbox sal_02_benefit" style="float:right;"></td>
								<td><input type="text" name="tax_rate_back_02[]" id="tax_rate_back_02" class="form-control text-right textbox tax_rate_back_02" style="float:right;text-align:center;" value="20" readonly></td>
								<td><input type="text" name="tax_on_sal_back_02[]" id="tax_on_sal_back_02" readonly="" class="checknb form-control text-right textbox tax_on_sal_back_02" style="float:right;"></td>
								<td><input type="text" name="remark_back_02[]" id="remark_back_02" class="form-control text-center textbox remark_back_02" style="float:right;"></td>		
							</tr>';
							}
						?>
						<tr>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="form-group text-center">
		<?php echo form_submit('save', $this->lang->line("save"), 'class="btn btn-primary btnSave"'); ?>
	</div>
	<?= form_close(); ?>
</body>
<script type="text/javascript">
	$(document).ready(function() {
		$(".checknb").keypress(function (e) {
			var st= $(this);
     if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
				
				setTimeout(function(){
				$({alpha:1}).animate({alpha:0}, {
					duration: 2000,
					step: function(){
						st.css('border-color','rgba(255,0,0,'+this.alpha+')');
					}
				});
				}, 10);
				   return false;
		}
	   });
		$(".btnSave").click(function(){
			var Enterprise = $("#enterprise").val();
			if(Enterprise==""){
				alert("Please Select Enterprise");
				return false;
			}
		});
		
		$('#enterprise').change(function(){
			var ent_id = $(this).val();
			$.ajax({
				type: 'get',
				url: '<?= site_url('taxes/getEnterpriceInfo'); ?>',
				dataType: "json",
				data: {
					ent_id: ent_id
				},
				success: function (data) {
					$('.vat').text('');
					var str = data['vat_no'];
					var i = 0;
					$('.vat').each(function() {
						$(this).text(str[i]);
						i++;
					});
					$('#business_act').val(data['business_activity']);
					$('#address').val(data['address']);	
					$('#street').val(data['street']);
					$('#group').val(data['group']);
					$('#village').val(data['village']);
					$('#sangkat').val(data['sangkat']);
					$('#district').val(data['district']);
					$('#city').val(data['city']);
					$('#phone').val(data['phone']);
					$('#email').val(data['email']);
				}
			});
		});
		function totalTaxDue() {
			
			var total_tax_on_sal_04 = $('#total_tax_on_sal_04').text()-0;
			var tax_on_sal_05 = $('#tax_on_sal_05').val()-0;
			var tax_on_sal_06 = $('#tax_on_sal_06').val()-0;
			
			var total_tax_due = total_tax_on_sal_04 + tax_on_sal_05 + tax_on_sal_06;
			
			$('#total_tax_due').text(total_tax_due);
		}
		$('.emp_04, .sal_04_paid, .spouse, .no_of_child, .tax_on_sal_04').change(function() {
			var tr = $(this).parent().parent();
			var A = tr.find('.emp_04').val()-0;
			var B = tr.find('.sal_04_paid').val()-0;
			var C = tr.find('.spouse').val()-0;
			var D = tr.find('.no_of_child').val()-0;
			var G = tr.find('.tax_rate_04').val()-0;
			
			var E = B - ((C + D) * 75000);
			
			tr.find('.sal_cal_04').val(E);
			
			var total_emp_04 = 0;
			var total_sal_04_paid = 0;
			var total_spouse = 0;
			var total_no_of_child = 0;
			var total_sal_cal_04 = 0;
			var total_tax_on_sal_04 = 0;
			$('.emp_04').each(function() {
				var tr = $(this).parent().parent();
				total_emp_04 += tr.find('.emp_04').val()-0;
				total_sal_04_paid += tr.find('.sal_04_paid').val()-0;
				total_spouse += tr.find('.spouse').val()-0;
				total_no_of_child += tr.find('.no_of_child').val()-0;
				total_sal_cal_04 += tr.find('.sal_cal_04').val()-0;
				total_tax_on_sal_04 += tr.find('.tax_on_sal_04').val()-0;
				//alert(total_emp_04);
			});
			$('#total_emp_04').text(total_emp_04);
			$('#total_sal_04_paid').text(total_sal_04_paid);
			$('#total_spouse').text(total_spouse);
			$('#total_no_of_child').text(total_no_of_child);
			$('#total_sal_cal_04').text(total_sal_cal_04);
			$('#total_tax_on_sal_04').text(total_tax_on_sal_04);
			totalTaxDue();
		});
		function calTaxOnSalary(B,C) {
			C = C/100;
			D = B * C;
			return D;
		}
		$('.emp_05, .sal_05_paid').change(function() {
			var D = 0;
			var tr = $(this).parent().parent();
			var B = tr.find('.sal_05_paid').val()-0;
			var C = tr.find('.tax_rate_05').val()-0;
			
			tr.find('.tax_on_sal_05').val(calTaxOnSalary(B,C));
			totalTaxDue();
		});
		$('.emp_06, .sal_06_paid').change(function() {
			var D = 0;
			var tr = $(this).parent().parent();
			var B = tr.find('.sal_06_paid').val()-0;
			var C = tr.find('.tax_rate_06').val()-0;
			
			tr.find('#tax_on_sal_06').val(calTaxOnSalary(B,C));
			totalTaxDue();
		});
		$('.emp_back_01').change(function(){
			var tr = $(this).parent().parent();
			var emp_id = $(this).val();
			$.ajax({
				type: 'get',
				url: '<?= site_url('taxes/getEmployeeInfo'); ?>',
				dataType: "json",
				data: {
					emp_id: emp_id
				},
				success: function (data) {
					tr.find('.national_back_01').val(data['nationality_kh']);
					tr.find('.function_back_01').val(data['description']);	
				}
			});
		});
		$('.sal_01_paid, .spouse_back_01, .child_back_01').change(function() {
			var tr = $(this).parent().parent();
			var D = tr.find('.sal_01_paid').val()-0;
			var E = tr.find('.spouse_back_01').val()-0;
			var F = tr.find('.child_back_01').val()-0;
			
			var G = (E + F) * 75000;
			var H = D - G;
			
			tr.find('.allowance_back_01').val(G);
			tr.find('.sal_cal_back_01').val(H);
		});
		$('.emp_back_02').change(function(){
			var tr = $(this).parent().parent();
			var emp_id = $(this).val();
			$.ajax({
				type: 'get',
				url: '<?= site_url('taxes/getEmployeeInfo'); ?>',
				dataType: "json",
				data: {
					emp_id: emp_id
				},
				success: function (data) {
					tr.find('.national_back_02').val(data['nationality_kh']);
					tr.find('.function_back_02').val(data['description']);	
				}
			});
		});
		$('.sal_02_benefit').change(function() {
			var tr = $(this).parent().parent();
			var D = tr.find('.sal_02_benefit').val()-0;
			var E = tr.find('.tax_rate_back_02').val()-0;
			E = E/100;
			
			var F = (D * E);
			
			tr.find('.tax_on_sal_back_02').val(F);
		});
		$('#stD, #stM, #stY').change(function(){
			var st_day = $('.stD').val();
			var st_month = $('.stM').val();
			var st_year = $('.stY').val();
			var et_dday = '';
			var et_month = '';
			var et_year = '';
			if(st_day != '' && st_month != '' && st_year != '') {
				var dd = new Date(st_year, st_month, st_day-1);
				var et_day = dd.getDate();
				var et_month = dd.getMonth()+1;
				var et_year = dd.getFullYear();
				$('.etD').val(et_day);
				$('.etM').val((et_month<10? '0'+et_month:et_month));
				$('.etY').val(et_year);
			}
			
		});
	});
</script>