<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
	body{
		font-family: 'Khmer OS';
	}
	.bd-gray td{
		border:1px solid gray;
	}
	.md-valign td{
		vertical-align: middle !important;
	}
	.put-border tr td{
		border:1px solid black !important;
		font-size: 10px;
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
		font-size:12px !important; 
		line-height:14px;
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
	.text-box{
		width: 100%;
		padding-top: 0;
		padding-bottom: 0;
		padding-left: 2px;
		padding-right: 2px;
		height: 30px;
		float:left;"
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
		echo form_open_multipart("taxes/prepayment_profit_tax_state_charge", $attrib)
	?>
	<div class="col-md-12 col-xs-12  col-lg-12">
		<div>
			<div class="row">
				<div class="col-md-4 col-xs-4  col-lg-4"><center>
					<img src="<?=base_url(); ?>/assets/images/logo_gpot.png" width="140px" height="143px"/>
					<p class="title-style"  style="font-family: 'Khmer OS Muol';">អគ្គនាយកដ្ឋានពន្ធដា</p>
					<p class="title-style" style="font-family: 'Khmer OS Muol';">នៃក្រសួងសេដ្ឋកិច្ច និងហិរញ្ញវត្ថុ </p></center>
				</div>
				<div class="col-md-4 col-xs-4  col-lg-4">
					<center>
						<p class="title-style" style="font-family: 'Khmer OS Muol';">ព្រះរាជាណាចក្រកម្ពុជា</p>
						<p class="title-style" style="font-family: 'Khmer OS Muol';">ជាតិ សាសនា ព្រះមហាក្សត្រ</p>
						<img src="<?=base_url(); ?>/assets/images/line-under.png" height="auto" width="130px" style="margin-bottom: 10px;"/>
						<div style="border:2px solid black;padding-top:5px;">
						<p class="title-style" style="font-family: 'Khmer OS Muol';">លិខិតប្រកាស</p>
						<p class="title-style">Tax Return</p>
						<p class="title-style" style="font-size:9px !important;">ប្រាក់រំដោះពន្ធលើប្រាក់ចំណេញ<br/>
														Prepayment of Profit Tax<br/>
														អាករពិសេសលើទំនិញនិងសេវាមួយចំនួន<br/>
														Specific Tax on Certain Merchandises and Services<br/>
														អាករលើការស្នាក់នៅ អាករសម្រាប់បំភ្លឺសាធារណៈ និងពន្ធអាករដទៃទៀត<br/>
														Accommodation Tax, Public Lighting Tax, and Other Taxes<br/>
						</p>
						</div>
						<p class="text-style" style="padding-top:5px;font-family: 'Khmer OS Muol';">(មាត្រា ៥៣ នៃច្បាប់ស្តីពីសារពើពន្ធ) <br/>(Article 53 of the Law on Taxation) </p>
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
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;DD&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;MM&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;YY<br>
							លេខចូល N<sup>o</sup>......................................................<br>
							ហត្ថលេខា<br>
							Signature<br>
							ឈ្មោះមន្ត្រីពន្ធដារ................................................<br>
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
								<input type="text" name="stD" id="stD" class="form-control stD" style="
									width: 56px;
									padding-top: 2px;
									padding-bottom: 2px;
									padding-left: 2px;
									padding-right: 2px;
									height: 20px;" readonly="readonly" value="1"><p class="text-style"  >From (DD)</p>
							</td>
							<td class="vertical-align: middle !important padding-less">
								<p class="text-style" style="margin-bottom: 0px;">ខែ</p>
							</td>
							<td  class="padding-less">
								<input type="text" name="stM" id="stM" class="form-control stM" style="
									width: 56px;
									padding-top: 2px;
									padding-bottom: 2px;
									padding-left: 2px;
									padding-right: 2px;
									height: 20px;" value=""><p class="text-style"  >Month(MM)</p>
							</td>
							<td class="vertical-align: middle !important padding-less">
								<p class="text-style" style="margin-bottom: 0px;">ឆ្នាំ</p>
							</td>
							<td  class="padding-less">
								<input type="text" name="stY" id="stY" class="form-control stY" style="
									width: 56px;
									padding-top: 2px;
									padding-bottom: 2px;
									padding-left: 2px;
									padding-right: 2px;
									height: 20px;" value=""><p class="text-style"  >Year(YYYY) </p>
							</td>
							<td class="vertical-align: middle !important padding-less"  style="border-left: 1px solid #ddd !important;">
								<p class="text-style" style="margin-bottom: 0px;">ដល់ថ្ងៃទី</p>
							</td>
							<td  class="padding-less">
								<input type="text" name="etD" id="etD" class="form-control etD" style="
									width: 56px;
									padding-top: 2px;
									padding-bottom: 2px;
									padding-left: 2px;
									padding-right: 2px;
									height: 20px;" value="" readonly="readonly" ><p class="text-style"  >To 	(DD)</p>
							</td>
							<td class="vertical-align: middle !important padding-less">
								<p class="text-style" style="margin-bottom: 0px;">ខែ</p>
							</td>
							<td  class="padding-less">
								<input type="text" name="etM" id="etM" class="form-control etM" style="
									width: 56px;
									padding-top: 2px;
									padding-bottom: 2px;
									padding-left: 2px;
									padding-right: 2px;
									height: 20px;" value="" readonly="readonly" ><p class="text-style"  >Month(MM) </p>
							</td>
							<td class="vertical-align: middle !important padding-less">
								<p class="text-style" style="margin-bottom: 0px;">ឆ្នាំ</p>
							</td>
							<td class="padding-less" style="border-right: 1px solid #ddd !important;">
								<input type="text" name="etY" id="etY" class="form-control etY" style="
									width: 56px;
									padding-top: 2px;
									padding-bottom: 2px;
									padding-left: 2px;
									padding-right: 2px;
									height: 20px;" value="" readonly="readonly" ><p class="text-style"  >Year(YYYY)</p>
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
								">ឈ្មោះសហគ្រាស 
							</p>
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
								<td>
									<p class="text-style" style="
										margin-bottom: 0px;
										margin-top: 0;
										">សកម្មភាពអាជីវកម្ម <br/>Business_Activities:
									</p>
								</td>
								<td colspan="5"><input type="text" class="form-control textbox" name="business_act" id="business_act" style="width: 100%;" readonly /></td>
							</tr>
							<tr>
								<td>
									<p class="text-style" style="
												margin-bottom: 0px;
												margin-top: 0;
											">អាស័យយដ្ឋាន Address: No
									</p>
								</td>
								<td><input type="text" id="address" name="address" class="form-control textbox" style="width: 220px;" readonly /></td>
								<td>
									<p class="text-style" style="
											margin-bottom: 0px;
											float:left;
										"> វិថី Street 
									</p>
								</td>
								<td><input type="text" name="street" id="street" class="form-control textbox" style="width: 200px;" readonly /></td>
								<td>
									<p class="text-style" style="
												margin-bottom: 0px;
												margin-top: 0;float:left;
											">ក្រុមGroup
									</p>
								</td>
								<td><input name="group" id="group" type="text" class="form-control textbox" style="width: 200px;" readonly /></td>
							</tr>
							<tr>
								<td>
									<p class="text-style" style="
											margin-bottom: 0px;
											margin-top: 0;float:left;
										">ភូមិVillage
									</p>
								</td>
								<td><input type="text" name="village" id="village" class="form-control textbox" style="width: 220px;" readonly /></td>
								<td>
									<p class="text-style" style="
												margin-bottom: 0px;
												margin-top: 0;float:left;
											">ឃុំ/សង្កាត់ Sangkat
									</p>
								</td>
								<td><input type="text" name="sangkat" id="sangkat" class="form-control textbox" style="width: 200px;" readonly /></td>
								<td>
									<p class="text-style" style="
												margin-bottom: 0px;
												margin-top: 0;float:left;
											"> ខ័ណ្ឌ /ក្រុង/ស្រុក District 
									</p>
								</td>
								<td><input type="text" name="district" id="district" class="form-control textbox" style="width: 200px;" readonly /></td>
							</tr>
							<tr>
								<td>
									<p class="text-style" style="
												margin-bottom: 0px;
												margin-top: 0;float:left;
											"> ខេត្ត/រាជធានី Municipality
									</p>
								</td>
								<td><input type="text" name="city" id="city" class="form-control textbox" style="width: 220px;" readonly /></td>
								<td>
									<p class="text-style" style="
												margin-bottom: 0px;
												margin-top: 0;float:left;
											">ទូរស័ព្ឌ/ទូរសារ Phone/Fax
									</p>
								</td>
								<td><input type="text" name="phone" id="phone" class="form-control textbox" style="width: 200px;" readonly /></td>
								<td>
									<p class="text-style" style="
												margin-bottom: 0px;
												margin-top: 0;float:left;
											">សារអេឡិចត្រូនិច Email 
									</p>
								</td>
								<td><input type="text" name="email" id="email" class="form-control textbox" style="width: 200px;" readonly /></td>
							</tr>
						</table><!--- end----->
					</div>
				</div>
			</div>
			<div class="col-md-12 col-xs-12  col-lg-12" style="margin-top:10px;">
				<p style="font-size:9px;"><u>បញ្ជាក់</u> : ប្រអប់ពីលេខ ០៨ ដល់លេខ ១៦ សម្រាប់តែសហគ្រាសណាដែលជាកម្មវត្ថុនៃពន្ធអាករទាំងនេះ។ សូមសរសេរថា"គ្មាន" ប្រសិនបើមិនមានតួលេខត្រូវបំពេញ។ <br/>
												 <u>Note </u>: Boxes from 08 to 16 are for the relevant enterprise that subject to those taxes. Please insert "Nil" if there is no figure to filled.</p>
					<table class="table table-date  put-border">
						<tr>
							<td colspan="2" style="width:50%;border-left:none !important;border-top:none !important;"><p  style="margin-bottom: 0 !important;"><b>I.</b> <u>ប្រាក់រំដោះពន្ធលើប្រាក់ចំណេញ Prepayment of Profit Tax</u>:</p></td>		
							<td colspan="2" style="padding:0 !important;height:31px;width:25%;">
							<div style="font-weight: 700;padding:15px;width:40px;height:40px;background-color:#7f8c8d;color:white;float:left;">04</div>
							<p style="margin-bottom: 0 !important;padding-top:5px !important;">ឥណទានយោងពីខែមុន<br>Credit from Last Month</p> </td>
							<td style="vertical-align: middle !important;width:25%;padding:5px !important;"><input type="text" name="credit_04"  readonly id="credit_04" class="checknb form-control text-box text-right credit_04"  value=""></td>
						</tr>
						<tr>
							<td style="padding:0 !important;height:31px;width:25%;">
							<div style="font-weight: 700;padding:15px;width:40px;height:40px;background-color:#7f8c8d;color:white;float:left;">05</div>
							<p style="margin-bottom: 0 !important;padding-top:5px !important;">មូលដ្ឋានគិតប្រាក់រំដោះក្នុងខែ<br>Prepayment Calculation Base for the Month</p> </td>
							<td style="vertical-align: middle !important;width:25%;padding:5px !important;"><input type="text" name="prepayment_05" id="prepayment_05" readonly="readonly" class="checknb form-control text-box text-right prepayment_05"  value=""></td>
							<td colspan="2" style="padding:0 !important;height:31px;width:25%;">
							<div style="font-weight: 700;padding:15px;width:40px;height:40px;background-color:#7f8c8d;color:white;float:left;">06</div>
							<p style="margin-bottom: 0 !important;padding-top:5px !important;">ប្រាក់រំដោះក្នុងខែ (អត្រា ១%)<br>Prepayment for the Month(Rate: 1%)</p> </td>
							<td style="vertical-align: middle !important;width:25%;padding:5px !important;"><input type="text" name="prepayment_06" id="prepayment_06" readonly="readonly"  class="checknb form-control text-box text-right prepayment_06" value=""></td>
						</tr>
						<tr>
							<td style="padding:0 !important;height:31px;width:25%;">
							<div style="font-weight: 700;padding:15px;width:40px;height:40px;background-color:#7f8c8d;color:white;float:left;">07</div>
							<p style="margin-bottom: 0 !important;padding-top:5px !important;">	ឥណទានយោងទៅមុខ<br>Credit Carried Forwards</p> </td>
							<td style="vertical-align: middle !important;width:25%;padding:5px !important;"><input type="text" name="credit_07" readonly id="credit_07" class="checknb form-control text-box text-right credit_07" value="">
							</td>
							<td colspan="2" style="padding:0 !important;height:31px;width:25%;">
							<div style="font-weight: 700;padding:15px;width:40px;height:40px;background-color:#7f8c8d;color:white;float:left;">08</div>
							<p style="margin-bottom: 0 !important;padding-top:5px !important;">	ប្រាក់រំដោះត្រូវបង់<br>Prepayment of Profit Tax Due</p> </td>
							<td style=" text-align:right; font-size:8px; padding-right:5px;"><span>ព្យួរទុក</span></td></td>
						</tr>
						<tr>
							<td colspan="5" style="width:50%;border-left:none !important;border-top:none !important;border-right:none !important;"><p  style="margin-bottom: 0 !important;"><b>II.</b> <u>អាករពិសេសលើទំនិញនិងសេវាមួយចំនួន Specific Tax on Certain Merchandises and Services</u> :</p></td>
						</tr>
						<tr>
							<td style="padding:0 !important;height:31px;width:25%;">
							<div style="font-weight: 700;padding:15px;width:40px;height:40px;background-color:#7f8c8d;color:white;float:left;">09</div>
							<p style="margin-bottom: 0 !important;padding-top:5px !important;">	មូលដ្ឋានគិតអាករពិសេស<br>Specific Tax Calculation Base</p> </td>
							<td style="vertical-align: middle !important;width:25%;padding:5px !important;"><input type="text" name="specific_09" readonly id="specific_09" class="checknb form-control text-right text-box specific_09" value=""></td>
							<td colspan="2" style="padding:0 !important;height:31px;width:25%;text-align:left;">
							<div style="font-weight: 700;padding:15px;width:40px;height:40px;background-color:#7f8c8d;color:white;float:left;">10</div>
							<p style="margin-bottom: 0 !important;padding-top:5px !important;float:left;">	ប្រាក់អាករពិសេសត្រូវបង់តាមអត្រា<br/>Specific Tax Due at the rate of</p><span style="margin-top:8px;float:right;"><span id="pspecific_10"></span>%<span></td>
							<td style="vertical-align: middle !important;width:25%;padding:5px !important;"><input type="text" name="specific_10" readonly id="specific_10" class="checknb form-control text-right text-box specific_10" value=""></td>
						</tr>
						<tr>
							<td style="padding:0 !important;height:31px;width:25%;">
							<div style="font-weight: 700;padding:15px;width:40px;height:40px;background-color:#7f8c8d;color:white;float:left;">11</div>
							<p style="margin-bottom: 0 !important;padding-top:5px !important;">	មូលដ្ឋានគិតអាករពិសេស<br>Specific Tax Calculation Base</p> </td>
							<td style="vertical-align: middle !important;width:25%;padding:5px !important;"><input type="text" name="specific_11" readonly id="specific_11" class="checknb form-control text-right text-box specific_11" value=""></td>
							<td colspan="2" style="padding:0 !important;height:31px;width:25%;text-align:left;">
							<div style="font-weight: 700;padding:15px;width:40px;height:40px;background-color:#7f8c8d;color:white;float:left;">12</div>
							<p style="margin-bottom: 0 !important;padding-top:5px !important;float:left;">	ប្រាក់អាករពិសេសត្រូវបង់តាមអត្រា<br/>Specific Tax Due at the rate of</p><span style="margin-top:8px;float:right;"><span id="pspecific_12"></span>%<span></td>
							<td style="vertical-align: middle !important;width:25%;padding:5px !important;"><input type="text" name="specific_12" readonly id="specific_12" class="checknb form-control text-right text-box specific_12" value=""></td>
						</tr>
						<tr>
							<td colspan="5" style="width:50%;border-left:none !important;border-top:none !important;"><p  style="margin-bottom: 0 !important;"><b>III.</b> <u> អាករលើការស្នាក់នៅ Accommodation Tax </u> :</p></td>
						</tr>
						<tr>
							<td style="padding:0 !important;height:31px;width:25%;">
							<div style="font-weight: 700;padding:15px;width:40px;height:40px;background-color:#7f8c8d;color:white;float:left;">13</div>
							<p style="margin-bottom: 0 !important;padding-top:5px !important;">មូលដ្ឋានគិតអាករលើការស្នាក់នៅ<br>Accommodation Tax Calculation Base</p> </td>
							<td style="vertical-align: middle !important;width:25%;padding:5px !important;"><input type="text" name="accommodation_13" readonly id="accommodation_13" class="checknb form-control text-right text-box accommodation_13" value=""></td>
							<td colspan="2" style="padding:0 !important;height:31px;width:25%;text-align:left;">
							<div style="font-weight: 700;padding:15px;width:40px;height:40px;background-color:#7f8c8d;color:white;float:left;">14</div>
							<p style="margin-bottom: 0 !important;padding-top:5px !important;">	ប្រាក់អាករលើការស្នាក់នៅត្រូវបង់ (អត្រា ២%)<br/>Accom Tax Due (Rate 2%)</p></td>
							<td style="vertical-align: middle !important;width:25%;padding:5px !important;"><input type="text" name="accommodation_14" readonly id="accommodation_14" class="checknb form-control text-right text-box accommodation_14" value=""></td>
						</tr>
						<tr>
							<td colspan="5"  style="width:50%;border-left:none !important;border-top:none !important;border-right:none !important;"><p  style="margin-bottom: 0 !important;"><b>IV.</b> <u> អាករបំភ្លឺសាធារណៈ Public Lighting Tax</u> :</p></td>
						</tr>
						<tr>
							<td style="padding:0 !important;height:31px;width:25%;">
							<div style="font-weight: 700;padding:15px;width:40px;height:40px;background-color:#7f8c8d;color:white;float:left;">15</div>
							<p style="margin-bottom: 0 !important;padding-top:5px !important;">មូលដ្ឋានគិតអាករបំភ្លឺសាធារណៈ<br>Public Lighting Tax Calculation Base</p> </td>
							<td style="vertical-align: middle !important;width:25%;padding:5px !important;"><input type="text" name="lighting_15" readonly id="lighting_15" class="checknb form-control text-right text-box lighting_15" value=""></td>
							<td colspan="2" style="padding:0 !important;height:31px;width:25%;text-align:left;">
							<div style="font-weight: 700;padding:15px;width:40px;height:40px;background-color:#7f8c8d;color:white;float:left;">16</div>
							<p style="margin-bottom: 0 !important;padding-top:5px !important;">ប្រាក់អាករស.បំភ្លឺសាធារណៈត្រូវបង់ (អត្រា ៣%)<br/>Public Lighting Tax Due (Rate 3%)</p></td>
							<td style="vertical-align: middle !important;width:25%;padding:5px !important;"><input type="text" name="lighting_16" readonly id="lighting_16" class="checknb form-control text-right text-box lighting_16" value=""></td>
						</tr>
						<tr>
							<td  style="padding:5px;border-left:none !important;border-top:none !important;"  ><p  style="margin-bottom: 0 !important;float:left;border-right:none !important;"><b>V.</b> <u> ពន្ធអាករដទៃទៀត Other Taxes: (បញ្ជាក់ Specify)     </u> *</p></td>
							<td colspan="4" style="padding:5px;"> <input type="text" name="other_taxes" id="other_taxes" class="form-control text-box other_taxes" value=""></td>
						</tr>
						<tr>
							<td style="padding:0 !important;height:31px;width:25%;">
							<div style="font-weight: 700;padding:15px;width:40px;height:40px;background-color:#7f8c8d;color:white;float:left;">17</div>
							<p style="margin-bottom: 0 !important;padding-top:5px !important;">	មូលដ្ឋានគិតពន្ថអាករ<br>Tax Calculation Base</p> </td>
							<td style="vertical-align: middle !important;width:25%;padding:5px !important;"><input type="text" name="tax_17" readonly id="tax_17" class="checknb form-control text-right text-box tax_17" value=""></td>
							<td colspan="2" style="padding:0 !important;height:31px;width:25%;text-align:left;">
							<div style="font-weight: 700;padding:15px;width:40px;height:40px;background-color:#7f8c8d;color:white;float:left;">18</div>
							<p style="margin-bottom: 0 !important;padding-top:5px !important;float:left;">	ប្រាក់ពន្ធអាករត្រូវបង់តាមអត្រា<br/>Tax Due at the rate of</p><span style="margin-top:8px;float:right;"><span id="ptax_due_18"></span>%<span>
							<td style="vertical-align: middle !important;width:25%;padding:5px !important;"><input type="text" name="tax_due_18" readonly id="tax_due_18" class="checknb form-control text-right text-box tax_due_18" value=""></td>
						</tr>
						<tr>
							<td colspan="2"  style="padding:0 !important;height:31px;width:25%;text-align:right;">
								<p style="padding-bottom:0;padding-bottom:0;padding-top: 9px;">សរុប ( [08] + [10] + [12] + [14] + [16] + [18] )	</p>
							</td>
							<td colspan="2" style="padding:0 !important;height:31px;width:25%;text-align:left;">
							<div style="font-weight: 700;padding:15px;width:40px;height:40px;background-color:#7f8c8d;color:white;float:left;">19</div>
							<p style="margin-bottom: 0 !important;padding-top:5px !important;float:left;">	សរុបប្រាក់ពន្ធត្រូវបង់ <br/>Total Tax Due</p>
							<td style=" text-align:right; font-size:8px; padding-right:5px;"><span>ព្យួរទុក</span></td></tr>
					</table>
					<div>
						<p style="font-size:9px;">យើងខ្ញុំបានពិនិត្យគ្រប់ចំណុចទាំងអស់នៅលើលិខិតប្រកាសនេះ និងតារាងឧបសម័្ពន្ធភ្ជាប់មកជាមួយ ។ យើងខ្ញុំមានសក្ខីប័្រតបញ្ជាក់ច្បាស់​លាស់ ត្រឹមត្រូវ ពេញលេញ ដែលធានាបានថា ព័ត៌មានទាំងអស់នៅលើលិខិតប្រកាសនេះ ពិតជាត្រឹមត្រូវប្រាកដមែន ហើយគ្មានប្រតិបត្ដិការណាមួយមិនបានប្រកាសនោះទេ ។ យើងខ្ញុំសូមទទួលខុសត្រូវទាំងស្រុងចំពោះមុខច្បាប់ទាំង ឡាយជាធរមាន ប្រសិនបើព័ត៌មានណាមួយមានការក្លែងបន្លំ ។We have examined all items on this return and the annexes attached here with. We have clear, correct, and full supporting documents to ensure that all information on this return is true and accurate and there is no business operationundeclared. We are fully responsible due to the existing Laws for any falsified information.</p>
					</div>
					<div>	
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
															<font style="font-family:'Khmer OS';"><u>សម្រាប់មន្ត្រីពន្ធដារ </u></font><u> For Tax Official</u>
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
														<font style="font-family:'Khmer OS';">ធ្វើនៅ </font><input type="text" style=" size:auto; font-size:9px;border-bottom:dotted 1px #666; border-left: 0px; border-top:0px; font-weight:bolder; text-align:center;" id="kh_made_at"  name="kh_made_at" class="kh_made_at"  value="">
														<font style="font-family:'Khmer OS';"> ថ្ងៃទី</font>
													</td>
													<td>
														<input type="text" size="6" style=" font-size:11px; border-bottom:dotted 1px #666; border-left:  0px; border-top:0px; font-family:'Times New Roman', Times, serif; text-align:center; font-weight:bolder" name="createD" id="createD" class="valid createD" value="<?=date('d')?>"> 
													   
													</td>
													<td>
														<font style="font-family:'Khmer OS';"> ខែ</font>
														<input type="text" size="6" style=" font-size:11px; border-bottom:dotted 1px #666; border-left: 0px; border-top:0px;font-family:'Times New Roman', Times, serif; text-align:center; font-weight:bolder" value="<?=date('m')?>" name="createM" id="createM" class="createM"> 
													   
													</td>
													<td>
													   <font style="font-family:'Khmer OS';"> ឆ្នាំ</font>
														<input type="text" size="6" style=" font-size:11px; border-bottom:dotted 1px #666; border-left:0px; border-top:0px;font-family:'Times New Roman', Times, serif;text-align:center; font-weight:bolder " value="<?=date('Y')?>" name="createY" id="createY" class="createY"> 
													</td>
												</tr>
												<tr style="font-size: 9px; vertical-align: top;">
													<td>
														Filed in <input type="text" style="size:auto; font-size:9px;border-bottom:dotted 0px #666; border-left: 0px; border-top:0px; font-family:'Times New Roman', Times, serif; border-bottom:dotted 1px #666; border-left: 0px; border-top:0px;  width:160px; text-align:center;" name="en_made_at" value="">
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
														<font style="font-size:9px; font-family:'Khmer OS Muol'">
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
														<font style="font-size:9px; font-family:'Khmer OS Muol'">
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
						<p style="font-size:9px;">
							<table>
								<tr>
									<td style="vertical-align:top;"><h2 style="display:inline;">*</h2></td>
									<td>
										<p style="padding-left:15px;">
											ចំពោះផ្នែក " ពន្ធអាករដទៃទៀត " ផ្នែកនេះសម្រាប់ប្រកាសប្រភេទពន្ធដទៃទៀតដែលមិនមានចែងក្នុងលិខិតប្រកាសនេះ ដូចជា ពន្ធលើប្រាក់ចំណេញសម្រាប់សហគ្រាសធានារ៉ាប់រង<br/>
											“Other Taxes” Section is used with other type of taxes that are not specified in this return such as Tax on Profit on Insurance Enterprise,<br/>
											ពន្ធលើប្រាក់ចំណេញបន្ថែមលើការបែងចែកភាគលាភជាដើម។<br/>
											Additional Profit Tax on Dividend Distribution. 
										</p>
									</td>
								</tr>
							</table>
						</p>
					</div>
					<div>
						<table   class="table table-date table-bordered put-border">
							<tr>
								<td colspan="6" style="text-align:center;font-size:12px !important;">ការផ្គត់ផ្គង់ទំនិញ/ផលិតផល Supply of Goods/Products</td>
							</tr>
							<tr class="ta-center">
								<td width="2%"><b>លរ<br/>N<sub>o</sub></b></td>
								<td width="30%"><b>បរិយាយមុខទំនិញ<br/>Description of Goods</b></td>
								<td width="16%"><b>បរិមាណ<br/>Quantity</b></td>
								<td width="21%"><b>មូលដ្ឋានសម្រាប់គិតអាករពិសេស <br/>Base for Specific Tax</b></td>
								<td width="16%"><b>ថ្លៃលក់សរុបមិនរួមបញ្ជូលពន្ធអាករ <br/>Sale Amount Excluding Taxes</b></td>
								<td width="15%"><b>វិក្កយបត្រពីលេខដល់លេខ<br/>Invoice from N<sup>o</sup> to N<sup>o</sup></b></td>
							</tr>
							<?php
							$k = 0;
							$goods_01 =  '<select name="goods_01[]" id="goods_01" class="form-control goods_01">';
							foreach($products as $product){
								if($k == 0){
									$goods_01 .=  '<option value=""></option>';
								}else{
									$goods_01 .=  '<option value="'.$product->code.'">'. $product->name .'</option>';
								}
								$k++;
							}
							$goods_01 .= '</select>';
							for($i=1;$i<=20;$i++){
							
							echo	'<tr>
									<td style="padding:15px;">'.$i.'</td>
									<td>'.$goods_01.'</td>
									<td><input type="text" name="quantity_01[]" id="quantity_01" class="checknb form-control text-box text-center quantity_01"  value=""></td>
									<td><input type="text" name="specific_tax_01[]" id="specific_tax_01" class="form-control text-box text-right specific_tax_01"  value=""></td>
									<td><input type="text" name="amount_tax_01[]" id="amount_tax_01" class="checknb form-control text-box text-right amount_tax_01"  value=""></td>
									<td><input type="text" name="invoice_01[]" id="invoice_01" class="form-control text-box invoice_01"  value=""></td>	
								</tr>';
								}
							?>	
							<tr>
								<td style="height:40px;background-color:#7f8c8d;"></td>
								<td style="text-align:right;padding-top:15px;"><b>សរុប​ Total</b></td>
								<td class="text-center"><span name="total_quantity_01" id="total_quantity_01" class="text-center total_quantity_01" style="font-size:12px; font-weight:bold;"> </span></td>
								<td class="text-right"><span name="total_specific_tax_01" id="total_specific_tax_01" class="text-right total_specific_tax_01" style="margin-right:10px; font-size:12px; font-weight:bold;"> </span></td>
								<td class="text-right"><span name="total_amount_tax_01" id="total_amount_tax_01" class="text-right total_amount_tax_01" style="margin-right:10px; font-size:12px; font-weight:bold;"> </span></td>
								<td></td>	
							</tr>
						</table>
					</div>
					<div>
						<table   class="table table-date table-bordered put-border">
							<tr>
								<td colspan="6" style="text-align:center;font-size:12px !important;">ការផ្គត់ផ្គង់ទំនិញ/ផលិតផល Supply of Service</td>
							</tr>
						<tr class="ta-center">
								<td width="2%"><b>លរ<br/>N<sub>o</sub></b></td>
								<td width="30%"><b>បរិយាយមុខទំនិញ<br/>Description of Service</b></td>
								<td width="16%"><b>បរិមាណ<br/>Quantity</b></td>
								<td width="21%"><b>មូលដ្ឋានសម្រាប់គិតអាករពិសេស <br/>Base for Specific Tax</b></td>
								<td width="16%"><b>ថ្លៃលក់សរុបមិនរួមបញ្ជូលពន្ធអាករ <br/>Sale Amount Excluding Tax</b></td>
								<td width="15%"><b>វិក្កយបត្រពីលេខដល់លេខ<br/>Invoice from N<sup>o</sup> to N<sup>o</sup></b></td>
							</tr>
	
						<?php
						$k = 0;
						$goods_02 =  '<select name="goods_02[]" id="goods_02" class="form-control goods_02">';
						foreach($products as $product){
							if($k == 0){
								$goods_02 .=  '<option value=""></option>';
							}else{
								$goods_02 .=  '<option value="'.$product->code.'">'. $product->name .'</option>';
							}
							$k++;
						}
						$goods_02 .= '</select>';
						for($i=1;$i<=20;$i++){
						
						echo	'<tr>
								<td style="padding:15px;">'.$i.'</td>
								<td>'.$goods_02.'</td>
								<td><input type="text" name="quantity_02[]" id="quantity_02" class="checknb form-control text-box text-center quantity_02"  value=""></td>
								<td><input type="text" name="specific_tax_02[]" id="specific_tax_02" class="form-control text-box text-right specific_tax_02"  value=""></td>
								<td><input type="text" name="amount_tax_02[]" id="amount_tax_02" class="checknb form-control text-box text-right amount_tax_02"  value=""></td>
								<td><input type="text" name="invoice_02[]" id="invoice_02" class="form-control text-box invoice_02"  value=""></td>	
							</tr>';
						}
						?>	
						<tr>
							<td style="height:40px;background-color:#7f8c8d;"></td>
							<td style="text-align:right;padding-top:15px;"><b>សរុប​ Total</b></td>
							<td class="text-center"><span name="total_quantity_02" id="total_quantity_02" class="text-center total_quantity_02" style="font-size:12px; font-weight:bold;"> </span></td>
							<td class="text-right"><span name="total_specific_tax_02" id="total_specific_tax_02" class="text-right total_specific_tax_02" style="margin-right:10px; font-size:12px; font-weight:bold;"> </span></td>
							<td class="text-right"><span name="total_amount_tax_02" id="total_amount_tax_02" class="text-right total_amount_tax_02" style="margin-right:10px; font-size:12px; font-weight:bold;"> </span></td>
							<td></td>	
						</tr>
					</table>
				</div>
				<div>
					<table>
						<tr>
							<td>
								<p><u><b>កំណត់សម្គាល់​ <b></u> : </p>
							</td>
							<td>
								<p>សម្រាប់ការផ្គត់ផ្គង់សេវា ពិសេសសេវាស្នាក់នៅដូចជាសណ្ឋាគារជាដើម បរិមាណគឺជាចំនួនបន្ទប់ដែលបានជួលក្នុងខែ ។</p>
							</td>
						</tr>
						<tr>
							<td style="vertical-align:top;">
								<p><u><b>Notes <b></u> : </p>
							</td>
							<td>
								<p>
									For supplying of service especially accommodation service like hotel,quantity should be number of room occupied for the month.<br/>
									ប្រសិនបើសរសេរមិនអស់ សូមភ្ជាប់មកជាមួយក្នុងក្រដាសដោយឡែក ។<br/>
									Use separate sheets if the space is insufficient.
								</p>
							</td>
						</tr>
					</table>
				</div>
			</div>
			<div class="form-group text-center">
				<?php echo form_submit('save', $this->lang->line("save"), 'class="btn btn-primary btnSave"'); ?>
			</div>
			<?= form_close(); ?>
		</div>
	</div>
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
	   $( ".stM" ).focusout(function() {
				$( "#enterprise" ).trigger( "change" );
		  })
		  $( ".stY" ).focusout(function() {
				$( "#enterprise" ).trigger( "change" );
		  })
		$('#enterprise').change(function(){
			var ent_id = $(this).val();
			var month = $('.stM').val();
			var year = $('.stY').val();
			$('#prepayment_05').val('');
			$('#prepayment_06').val('');
			if(month =='' || year == '' || ent_id==''){
				
			}else{
				$.ajax({
					type: 'get',
					url: '<?= site_url('taxes/getEnterpriceInfo'); ?>',
					dataType: "json",
					data: {
						ent_id: ent_id,
						month: month,
						year: year,
						tax_type: 3
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
						$('#prepayment_05').val(data['prepayment_05']);
						$('#prepayment_06').val(data['prepayment_06']);
						$('#prepayment_08').val(data['prepayment_06']);

						$('#specific_09').val((data['specifictax3']/1.1).toFixed(0));
						$('#specific_10').val(((data['specifictax3']/1.1)*0.03).toFixed(0));
						$('#specific_11').val((data['specifictax10']/1.1).toFixed(0));
						$('#specific_12').val(((data['specifictax10']/1.1)*0.1).toFixed(0));
						$('#accommodation_13').val(((data['total_accommodation_tax']/1.02)).toFixed(0));
						$('#lighting_15').val(((data['total_public_lighting_tax']/1.03)).toFixed(0));
						$('#accommodation_14').val(((data['total_accommodation_tax']/1.02)*0.02).toFixed(0));
						$('#lighting_16').val(((data['total_public_lighting_tax']/1.03)*0.03).toFixed(0));
						
						$('#pspecific_10').text(data['p10']);
						$('#pspecific_12').text(data['p12']);
						
						var total_tax_due=data['prepayment_06']+((data['specifictax3']/1.1)*0.03)+((data['specifictax10']/1.1)*0.1)+((data['total_accommodation_tax']/1.02)*0.02)+((data['total_public_lighting_tax']/1.03)*0.03);
						$('#total_tax_19').val(total_tax_due.toFixed(0));
					}
				});
			}
		});
		
		$(".btnSave").click(function(){
			var Enterprise = $("#enterprise").val();
			if(Enterprise==""){
				alert("Please Select Enterprise");
				return false;
			}
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
		$('#prepayment_08, #specific_10, #specific_12, #accommodation_14, #lighting_16, #tax_due_18').change(function() {
			var total_tax_19 = 0;
			if(prepayment_08 = Number($('.prepayment_08').val())) {
				total_tax_19 = prepayment_08;
			}
			if(specific_10 = Number($('.specific_10').val())) {
				total_tax_19 += specific_10;
			}
			if(specific_12 = Number($('.specific_12').val())) {
				total_tax_19 += specific_12;
			}
			if(accommodation_14 = Number($('.accommodation_14').val())) {
				total_tax_19 += accommodation_14;
			}
			if(lighting_16 = Number($('.lighting_16').val())) {
				total_tax_19 += lighting_16;
			}
			if(tax_due_18 = Number($('.tax_due_18').val())) {
				total_tax_19 += tax_due_18;
			}
			$('.total_tax_19').val(total_tax_19);
		});
		function Total(class_name) {
			var total = 0;
			$('.'+class_name).each(function() {
				if(Number($(this).val())) {
					total += Number($(this).val());
				}
			});
			return total;
		}
		$('.quantity_01, .quantity_02, .specific_tax_01, .specific_tax_02, .amount_tax_01, .amount_tax_02').change(function() {
			var name = $(this).attr('id');
			$('#total_'+name).text(Total(name));
		});
	});
</script>