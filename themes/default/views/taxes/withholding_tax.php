
  <title>Withholding Tax</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

<style>.t_r{text-align:right;}
.t_c{
	text-align:center;
	}
.t_l{text-align:left;}.float-left{float:left;}.put-border tr td{border:1px solid black !important;font-size: 9px;}.put-border th{border:1px solid black !important;font-size: 9px;}.title-style{font-size:12px !important; line-height:14px;}.text-style{font-size:9px !important; line-height:14px;} .input-date td{width:30px;height:10px;} .table-date td{border:0 !important;} .padding-less{padding:3px !important;}</style>
<?php
		if ($error) {
			echo "<div class=\"alert alert-danger\"><button type=\"button\" class=\"close fa-2x\" data-dismiss=\"alert\">&times;</button>" . $error . "</div>";
		}
		if ($message) {
			echo "<div class=\"alert alert-success\"><button type=\"button\" class=\"close fa-2x\" data-dismiss=\"alert\">&times;</button>" . $message . "</div>";
		}
		$attrib = array('class' => 'form-horizontal', 'data-toggle' => 'validator', 'role' => 'form');
		echo form_open_multipart("taxes/withholding_tax", $attrib)
?>
<body>
<div class="col-md-12 col-xs-12  col-lg-12">
	<div>
		<div class="row">
		<div class="col-md-4 col-xs-4  col-lg-4"><center>
			<img src="<?=site_url()?>/assets/images/logo_gpot.png" width="140px" height="143px"/>
			<p class="title-style"  style="font-family: 'Khmer OS Muol';">អគ្គនាយកដ្ឋានពន្ធដា</p>
			<p class="title-style" style="font-family: 'Khmer OS Muol';">នៃក្រសួងសេដ្ឋកិច្ច និងហិរញ្ញវត្ថុ </p></center>
		</div>
		<div class="col-md-4 col-xs-4  col-lg-4"><center>
			<p class="title-style" style="font-family: 'Khmer OS Muol';">ព្រះរាជាណាចក្រកម្ពុជា</p>
			<p class="title-style" style="font-family: 'Khmer OS Muol';">ជាតិ សាសនា ព្រះមហាក្សត្រ</p>
			<img src="<?=site_url()?>/assets/images/line-under.png" height="auto" width="130px" style="margin-bottom: 10px;"/>
			<div style="border:2px solid black;padding-top:5px;">
			<p class="title-style" style="font-family: 'Khmer OS Muol';">លិខិតប្រកាសពន្ធកាត់ទុក</p>
			<p class="title-style">Return for Withholding Tax</p>
			</div>
			<p class="text-style" style="padding-top:5px;font-family: 'Khmer OS Muol';">(មាត្រា២៥ថ្មី មាត្រា២៦ថ្មី និងមាត្រា៣១ថ្មី នៃច្បាប់ស្តីពីសារពើពន្ធ)<br/>
			(Article 25"New", 26"New" and 31"New" of the Law on Taxation) </p>
		</center></div>
		<div class="col-md-4 col-xs-4  col-lg-4">

			<div  style="float:right;border:2px solid black; padding:5px,10px,0,10px !important;">
				<p style="margin:5px!important;" class="title-style">ទម្រង់ ពបវ ០១</p>
				<p style="margin:5px!important;" class="title-style">From TOS 01 </p>			
			</div>
				<br/><br/><br/>
			<div style="font-size:9px;float:right; border:1px solid #666; width:5cm;  text-align:left; line-height:14px; margin-top:2px;">
                            	<div style="margin-left:0px; font-family:'Khmer OS';">
                                    <center>សម្រាប់មន្ត្រីពន្ធ For Tax Official</center>
                                    ថ្ងៃទី!____!____!ខែ!____!____!ឆ្នាំ!____!____!<br>
              <div style="display:inline-flex"> <p style="margin-left:32px">DD</p><p style="margin-left:46px">MM</p><p style="margin-left:46px">YY</p></div><br>
                                    លេខចូល N<sup>o</sup>...........................................<br>
                                    ហត្ថលេខា<br>
                                    Signature<br>
                                    ឈ្មោះមន្ត្រីពន្ធដារ.....................................<br>
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
						<input type="text" class="checknb t_c form-control st_dd" name="st_dd" style="
							width: 56px;
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;
							height: 20px;" value="1" readonly><p class="text-style"  >From (DD)</p>
							</td>
						<td class="vertical-align: middle !important padding-less">
						<p class="text-style" style="margin-bottom: 0px;">ខែ</p>
						</td>
						<td  class="padding-less">
						<input type="text" class="checknb t_c form-control st_mm" name="st_mm"style="
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
						<input type="text" class="checknb t_c form-control st_yy" name="st_yy" style="
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
						<input type="text" class="checknb t_c form-control en_dd" name="en_dd" style="
							width: 56px;
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;
							height: 20px;" readonly><p class="text-style"  >To 	(DD)</p>
							</td>
						<td class="vertical-align: middle !important padding-less">
						<p class="text-style" style="margin-bottom: 0px;">ខែ</p>
						</td>
						<td  class="padding-less">
						<input type="text" class="checknb t_c form-control en_mm" name="en_mm" style="
							width: 56px;
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;
							height: 20px;" readonly><p class="text-style"  >Month(MM) </p>
							</td>
						<td class="vertical-align: middle !important padding-less">
						<p class="text-style" style="margin-bottom: 0px;">ឆ្នាំ</p>
						</td>
						<td class="padding-less" style="border-right: 1px solid #ddd !important;">
						<input type="text" class="checknb t_c form-control en_yy" name="en_yy" style="
							width: 56px;
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;
							height: 20px;" readonly><p class="text-style"  >Year(YYYY)</p>
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
					<td style="background-color:#7f8c8d;color:white; padding-bottom:0 !important;width:35px;"  class="title-style">01</td>
					<td style=" padding-bottom:0 !important;" colspan="16"><center><p class="text-style">លេខអត្តសញ្ញាណកម្មសារពើពន្ធ TIN</p></center></td>	
				</tr>
				<tr style="border:1px solid black;">
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
				<div style="background-color:#7f8c8d;color:white; width:35px;padding:5px;float:left;margin-right: 5px;"><p style="font-size:11px;font-weight: bold;">03</p></div>
				<div>
					<div style="float:left;">
					<p class="text-style" style="
							margin-bottom: 0px;
							margin-top: 5px;
						">ឈ្មោះសហគ្រាស </p>
					<p class="text-style">Name of :</p>
					</div>
					<div>
					<select class="validate[required] enterprise" style="width:150px;margin-left: 10px;margin-top: 10px;" id="enterprise"  name="enterprise">
								<option value=""></option>  
								<?php
								
								foreach($enterprise as $com){
									echo "<option value='".$com->id."'>".$com->company."</option>";
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
					<table style="
    margin-left: 25px;
">	
						<tr>
							<td><p class="text-style" style="
							margin-bottom: 0px;
							margin-top: 0;
						">សកម្មភាពអាជីវកម្ម <br/>Business_Activities:</p>
					</td>
					<td colspan="5">
					<input type="text" class="form-control business_act" name="business_act" readonly></td>
						</tr>
						<tr>
							<td><p class="text-style" style="
							margin-bottom: 0px;
							margin-top: 0;
						">អាស័យយដ្ឋាន Address: No</p></td>
							<td style="padding-top:4px"><input type="text" name="address" class="form-control address" style="
							width: 235px;
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;margin-bottom: 5px;
							height: 35px;" readonly></td>
							
							<td><p class="text-style" style="
							margin-bottom: 0px;
							float:left;
						"> វិថី Street </p></td><td style="padding-top:4px"><input type="text" name="street"  class="form-control street" style="
							width: 235px;
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;margin-bottom: 5px;
							height: 35px;" readonly></td>
							
							<td><p class="text-style" style="
							margin-bottom: 0px;
							margin-top: 0;float:left;
						">ក្រុមGroup</p></td><td style="padding-top:4px"><input type="text" class="form-control group" name="group"  style="
							width: 235px;
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;margin-bottom: 5px;
							height: 35px;" readonly></td>
						</td>
						<tr>
							<td><p class="text-style" style="
							margin-bottom: 0px;
							margin-top: 0;float:left;
						">ភូមិVillage</p></td><td><input type="text" class="form-control village"  name="village" style="
							width: 235px;
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;margin-bottom: 5px;
							height: 35px;" readonly></td>
						
							<td><p class="text-style" style="
							margin-bottom: 0px;
							margin-top: 0;float:left;
						">ឃុំ/សង្កាត់ Sangkat</p></td>
						<td><input type="text" class="form-control sangkat" name="sangkat" style="
							width: 235px;
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;margin-bottom: 5px;
							height: 35px;" readonly></td>
							<td><p class="text-style" style="
							margin-bottom: 0px;
							margin-top: 0;float:left;
						"> ខ័ណ្ឌ /ក្រុង/ស្រុក District </p></td><td><input type="text" name="district" class="form-control district" style="
							width: 235px;
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;margin-bottom: 5px;
							height: 35px;" readonly></td>
						</tr>
						<tr>
							<td><p class="text-style" style="
							margin-bottom: 0px;
							margin-top: 0;float:left;
						"> ខេត្ត/រាជធានី Municipality </p></td><td><input type="text" name="municipality" class="form-control municipality" style="
							width: 235px;
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;margin-bottom: 5px;
							height: 35px;" readonly></td>
						
							<td>
							<p class="text-style" style="
							margin-bottom: 0px;
							margin-top: 0;float:left;
						">ទូរស័ព្ឌ/ទូរសារ Phone/Fax</p></td>
						<td><input type="text" class="form-control phone" name="phone" style="
							width: 235px;
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;margin-bottom: 5px;
							height: 35px;" readonly>
							</td>
							<td>
							<p class="text-style" style="
							margin-bottom: 0px;
							margin-top: 0;float:left;
						">សារអេឡិចត្រូនិច Email </p></td>
						<td><input type="text" class="form-control email" name="email" style="
							width: 235px;
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;margin-bottom: 5px;
							height: 35px;" readonly>
							</td>
						</tr>
					</table><!--- end----->
				</div>
				<div></div>
			</div>
	</div>
			<div class="col-md-12 col-xs-12  col-lg-12" style="margin-top:10px;">
			<p><b>I.</b> <u>ពន្លត់កាត់ទុកលើនិវាសនជន(មាត្រា ២៥ "ថ្មី") Withholding Tax on Resident(Article 25 "New")</u>:</p>
					<table class="table table-date table-bordered put-border">
						<tr class="ta-center padding-five">
							<td style="vertical-align: middle !important;background-color:#7f8c8d;color:white;"><b>04<b/></td>
							<td><b>កម្មវត្ថុនៃការទូទាត់ប្រាក់<br/>Object of Payment<b/></td>
							<td><b>ទឹកប្រាក់ត្រូវបើក<br/>Amount to be Paid<b/></td>
							<td><b>អត្រាពន្ធ<br/>Tax Rate<b/></td>
							<td><b>ពន្ធកាត់ទុក<br/>Withholding Tax<b/></td>
							<td><b>កំណត់សម្គាល់<br/>Remarks<b/></td>
						</tr>
						<tr class="ta-center padding-five">
							<td></td>
							<td>A</td>
							<td>B</td>
							<td>C</td>
							<td>D = B x C </td>	
							<td></td>	
						</tr>
						
						
					<tr class="padding-five ta-right">
							<td style="width:2%;text-align:center;vertical-align: middle !important;">1</td>
							<td style="width:40%;text-align:left;"> ការបំពេញសេវាកម្ម សួយសារចំពោះទ្រព្យអរូបី ភាគកម្មក្នុងេធនធានរ៉ែ<br/>
							Performance of Services, Royalty for Intangibles, Interests in Minerals
							<input type="hidden" name="type_of_oop[]" value=" ការបំពេញសេវាកម្ម សួយសារចំពោះទ្រព្យអរូបី ភាគកម្មក្នុងេធនធានរ៉ែ<br/>
							Performance of Services, Royalty for Intangibles, Interests in Minerals"></td>
							
							<td style="width:16%;"><input type="text" class="checknb t_r form-control salarypaid" name="salarypaid[]" id="rb1"  readonly style="
							width: 80%;
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;
							height: 35px;float:left;">
							<p style="padding-top:5px;margin-bottom: 0px;float:right;">រៀល</p></td>
							<td style="width:12%;">
							<input type="text" class="checknb t_r form-control persent" name="persent[]" style="
							width: 90%;
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;
							text-align:right;
							height: 35px;float:left;" value="15" readonly><p style="padding-top:5px;margin-bottom: 0px;float:right;">%</p></td>
							<td style="width:16%;"><input type="text" readonly="" class="checknb t_r form-control withholding_box"  id="rd1"  name="withholding_box[]" style="
							width: 80%;
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;
							height: 35px;float:left;"><p style="padding-top:5px;margin-bottom: 0px;float:right;">រៀល</p></td>
							<td style="width:16%;"><input type="text" class="form-control remark" name="remark[]" style="
							width: 100%;
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;
							height: 35px;float:left;"></td>
					</tr>
					<tr class="padding-five ta-right">
							<td style="width:2%;text-align:center;vertical-align: middle !important;">2</td>
							<td style="width:40%;text-align:left;">ការបង់ការប្រាក់ឲ្យទៅអ្នកជាប់ពន្ធមិនមែនជាធនាគារឬស្ថាប័នសញ្ជ័យធន <br/>
							Payment of Interest to Non-Bank or Saving Institution Taxpayers
							<input type="hidden" name="type_of_oop[]" value="ការបង់ការប្រាក់ឲ្យទៅអ្នកជាប់ពន្ធមិនមែនជាធនាគារឬស្ថាប័នសញ្ជ័យធន <br/>
							Payment of Interest to Non-Bank or Saving Institution Taxpayers"></td>
							<td style="width:16%;"><input type="text" class="checknb t_r form-control salarypaid" id="rb2" readonly name="salarypaid[]" style="
							width: 80%;
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;
							height: 35px;float:left;"><p style="padding-top:5px;margin-bottom: 0px;float:right;">រៀល</p></td>
							<td style="width:12%;"><input type="text" class="checknb t_r form-control persent" name="persent[]" style="
							width: 90%;
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;
							text-align:right;
							height: 35px;float:left;" value="15" readonly><p style="padding-top:5px;margin-bottom: 0px;float:right;">%</p></td>
							<td style="width:16%;"><input type="text" readonly="" class="checknb t_r form-control withholding_box"   id="rd2" name="withholding_box[]" style="
							width: 80%;
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;
							height: 35px;float:left;" ><p style="padding-top:5px;margin-bottom: 0px;float:right;">រៀល</p></td>
							<td style="width:16%;"><input type="text" class="form-control remark" name="remark[]" style="
							width: 100%;
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;
							height: 35px;float:left;"></td>
					</tr>
					<tr class="padding-five ta-right">
							<td style="width:2%;text-align:center;vertical-align: middle !important;">3</td>
							<td style="width:40%;text-align:left;">ការបង់ការប្រាក់ឲ្យអ្នកជាប់ពន្ធដែលមានគណនីសន្សំមានកាលកំណត់ *<br/>
							Payment of Interest to Taxpayers who have Fixed Term Deposit Accounts
							<input type="hidden" name="type_of_oop[]" value="ការបង់ការប្រាក់ឲ្យអ្នកជាប់ពន្ធដែលមានគណនីសន្សំមានកាលកំណត់ *<br/>
							Payment of Interest to Taxpayers who have Fixed Term Deposit Accounts"></td>
							<td style="width:16%;"><input type="text" class="checknb t_r form-control salarypaid" readonly id="rb3" name="salarypaid[]"style="
							width: 80%;
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;
							height: 35px;float:left;"><p style="padding-top:5px;margin-bottom: 0px;float:right;">រៀល</p></td>
							<td style="width:12%;"><input type="text" class="checknb t_r form-control persent" name="persent[]" style="
							width: 90%;
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;
							text-align:right;
							height: 35px;float:left;" value="6" readonly><p style="padding-top:5px;margin-bottom: 0px;float:right;">%</p></td>
							<td style="width:16%;"><input  type="text" readonly="" class="checknb t_r  form-control withholding_box"    id="rd3"   name="withholding_box[]" style="
							width: 80%;
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;
							height: 35px;float:left;" ><p style="padding-top:5px;margin-bottom: 0px;float:right;">រៀល</p></td>
							<td style="width:6%;"><input type="text" class=" form-control remark" name="remark[]" style="
							width: 100%;
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;
							height: 35px;float:left;"></td>
					</tr>
					<tr class="padding-five ta-right">
							<td style="width:2%;text-align:center;vertical-align: middle !important;">4</td>
							<td style="width:40%;text-align:left;">ការបង់ការប្រាក់ឲ្យអ្នកជាប់ពន្ធដែលមានគណនីសន្សំគ្មានកាលកំណត់* <br/>
							Payment of Interest to Taxpayers who have Non-Fixed Term Saving
							<input type="hidden" name="type_of_oop[]" value="ការបង់ការប្រាក់ឲ្យអ្នកជាប់ពន្ធដែលមានគណនីសន្សំគ្មានកាលកំណត់* <br/>
							Payment of Interest to Taxpayers who have Non-Fixed Term Saving"></td>
							<td style="width:16%;"><input type="text" class="checknb t_r form-control salarypaid" id="rb4" readonly name="salarypaid[]" style="
							width: 80%;
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;
							height: 35px;float:left;"><p style="padding-top:5px;margin-bottom: 0px;float:right;">រៀល</p></td>
							<td style="width:12%;"><input type="text" class="checknb t_r form-control persent" name="persent[]" style="
							width: 90%;
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;
							text-align:right;
							height: 35px;float:left;" value="4" readonly><p style="padding-top:5px;margin-bottom: 0px;float:right;">%</p></td>
							<td style="width:16%;"><input type="text" readonly="" class="checknb t_r form-control withholding_box"    id="rd4"  name="withholding_box[]" style="
							width: 80%;
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;
							height: 35px;float:left;" ><p style="padding-top:5px;margin-bottom: 0px;float:right;">រៀល</p></td>
							<td style="width:16%;"><input type="text" class="form-control remark" name="remark[]" style="
							width: 100%;
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;
							height: 35px;float:left;"></td>
					</tr>
					<tr class="padding-five ta-right">
							<td style="width:2%;text-align:center;vertical-align: middle !important;">5</td>
							<td style="width:40%;text-align:left;">ការបង់ថ្លៃឈ្នួលចលននិងអចលនទ្រព្យ <br/>
							Payment of Rental/Lease of Movabel and Immovable Property
							<input type="hidden" name="type_of_oop[]" value="ការបង់ថ្លៃឈ្នួលចលននិងអចលនទ្រព្យ <br/>
							Payment of Rental/Lease of Movabel and Immovable Property"></td>
							<td style="width:16%;"><input type="text" class="checknb t_r form-control salarypaid"   id="rb5" readonly name="salarypaid[]" style="
							width: 80%;
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;
							height: 35px;float:left;"><p style="padding-top:5px;margin-bottom: 0px;float:right;">រៀល</p></td>
							<td style="width:12%;"><input type="text" class="checknb t_r form-control persent" name="persent[]" style="
							width: 90%;
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;
							text-align:right;
							height: 35px;float:left;" value="10" readonly><p style="padding-top:5px;margin-bottom: 0px;float:right;">%</p></td>
							<td style="width:16%;"><input type="text" readonly="" class="checknb t_r form-control withholding_box"    id="rd5" name="withholding_box[]" style="
							width: 80%;
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;
							height: 35px;float:left;" ><p style="padding-top:5px;margin-bottom: 0px;float:right;">រៀល</p></td>
							<td style="width:16%;"><input type="text" class="form-control remark" name="remark[]" style="
							width: 100%;
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;
							height: 35px;float:left;"></td>
					</tr>
					<tr class="padding-five ta-right">
							<td style="width:2%;text-align:center;vertical-align: middle !important;"></td>
							<td style="width:40%;text-align:left;">សរុប <br/>
							Total</td>
							<td style="width:16%;" class="TotalSP_AMT"><p style="padding-top:5px;margin-bottom: 0px;float:right;"><span id="total_r"></span>រៀល</p></td>
							<td style="width:12%;background-color:#7f8c8d;"></td>
							<td style="width:16%;" class="TotalWidhholding"><p style="padding-top:5px;margin-bottom: 0px;float:right;">
							<span id="total_rt"></span>
							<input type="hidden" id="total_tr_box" name="total_tr_box" />
							រៀល</p></td>
							<td style="width:16%;"></td>
					</tr>
						
					</table>
					
					<div>
					<p><b>II.</b> <u>ពន្ធកាត់ទុកលើអនិវាសនជន (មាត្រា ២៦"ថ្មី") Withholding Tax on Non-Resident(Article 26 "New")</u>:</p>
						<table class="table table-date table-bordered put-border">
						<tr class="ta-center padding-five">
							<td style="vertical-align: middle !important;background-color:#7f8c8d;color:white;"><b>05<b/></td>
							<td><b>កម្មវត្ថុនៃការទូទាត់ប្រាក់<br/>Object of Payment<b/></td>
							<td><b>ទឹកប្រាក់ត្រូវបើក<br/>Amount to be Paid<b/></td>
							<td><b>អត្រាពន្ធ<br/>Tax Rate<b/></td>
							<td><b>ពន្ធកាត់ទុក<br/>Withholding Tax<b/></td>
							<td><b>កំណត់សម្គាល់<br/>Remarks<b/></td>
						</tr>
						<tr class="ta-center padding-five">
							<td></td>
							<td>A</td>
							<td>B</td>
							<td>C</td>
							<td>D = B x C </td>	
							<td></td>	
						</tr>
						
						
					<tr class="padding-five ta-right">
							<td style="width:2%;text-align:center;vertical-align: middle !important;">1</td>
							<td style="width:40%;text-align:left;">ការបង់ការប្រាក់<br/>
							Payment of Interest
							<input type="hidden" name="type_of_oop[]" value="ការបង់ការប្រាក់<br/>
							Payment of Interest"></td>
							<td style="width:16%;"><input type="text" class="checknb t_r form-control salary_nre" readonly name="salary_nre[]"id="nrb1" style="
							width: 80%;
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;
							height: 35px;float:left;"><p style="padding-top:5px;margin-bottom: 0px;float:right;">រៀល</p></td>
							<td style="width:12%;"><input type="text" class="checknb t_r form-control persent_nre" readonly name="persent_nre[]" style="
							width: 90%;
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;
							text-align:right;
							height: 35px;float:left;" value="14"><p style="padding-top:5px;margin-bottom: 0px;float:right;">%</p></td>
							<td style="width:16%;"><input type="text" class="checknb t_r form-control withholding_box_nre" readonly name="withholding_box_nre[]" id="nrd1" style="
							width: 80%;
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;
							height: 35px;float:left;"  ><p style="padding-top:5px;margin-bottom: 0px;float:right;">រៀល</p></td>
							<td style="width:16%;"><input type="text" class="form-control remark_nre" name="remark_nre[]" style="
							width: 100%;
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;
							height: 35px;float:left;"></td>
					</tr>
					<tr class="padding-five ta-right">
							<td style="width:2%;text-align:center;vertical-align: middle !important;">2</td>
							<td style="width:40%;text-align:left;">ការបង់សួយសារ ថ្លៃឈ្នូល ចំណូលផ្សេងៗទាក់ទិននឹងការប្រើប្រាស់ទ្រព្យសម្បត្តិ<br/>
							Payment of Royaly, Rental/Leasign, and Income related to use of property
							<input type="hidden" name="type_of_oop[]" value="ការបង់សួយសារ ថ្លៃឈ្នូល ចំណូលផ្សេងៗទាក់ទិននឹងការប្រើប្រាស់ទ្រព្យសម្បត្តិ<br/>
							Payment of Royaly, Rental/Leasign, and Income related to use of property"></td>
							<td style="width:16%;"><input type="text" class="checknb t_r form-control salary_nre" readonly name="salary_nre[]"  id="nrb2" style="
							width: 80%;
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;
							height: 35px;float:left;"><p style="padding-top:5px;margin-bottom: 0px;float:right;">រៀល</p></td>
							<td style="width:12%;"><input type="text" class="checknb t_r form-control persent_nre" readonly name="persent_nre[]"  style="
							width: 90%;
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;
							text-align:right;
							height: 35px;float:left;" value="14"><p style="padding-top:5px;margin-bottom: 0px;float:right;">%</p></td>
							<td style="width:16%;"><input type="text" class="checknb t_r form-control withholding_box_nre" readonly id="nrd2" name="withholding_box_nre[]" style="
							width: 80%;
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;
							height: 35px;float:left;"  ><p style="padding-top:5px;margin-bottom: 0px;float:right;">រៀល</p></td>
							<td style="width:16%;"><input type="text" class="form-control remark_nre" name="remark_nre[]" style="
							width: 100%;
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;
							height: 35px;float:left;"></td>
					</tr>
					<tr class="padding-five ta-right">
							<td style="width:2%;text-align:center;vertical-align: middle !important;">3</td>
							<td style="width:40%;text-align:left;">ការទូទាត់ថ្លៃសេវាគ្រប់គ្រងនិងសេវាបច្ចេកទេសនានា <br/>
							Payment of Management Fee and Techical Services
							<input type="hidden" name="type_of_oop[]" value="ការទូទាត់ថ្លៃសេវាគ្រប់គ្រងនិងសេវាបច្ចេកទេសនានា <br/>
							Payment of Management Fee and Techical Services"></td>
							<td style="width:16%;"><input type="text" class="checknb t_r form-control salary_nre" readonly name="salary_nre[]"  id="nrb3" style="
							width: 80%;
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;
							height: 35px;float:left;"><p style="checknb t_c padding-top:5px;margin-bottom: 0px;float:right;">រៀល</p></td>
							<td style="width:12%;"><input type="text" class="checknb t_r form-control persent_nre" readonly name="persent_nre[]" style="
							width: 90%;
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;
							text-align:right;
							height: 35px;float:left;" value="14"><p style="padding-top:5px;margin-bottom: 0px;float:right;">%</p></td>
							<td style="width:16%;"><input type="text" class="checknb t_r form-control withholding_box_nre" readonly id="nrd3"  name="withholding_box_nre[]" style="
							width: 80%;
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;
							height: 35px;float:left;"  ><p style="padding-top:5px;margin-bottom: 0px;float:right;">រៀល</p></td>
							<td style="width:6%;"><input type="text" class="form-control remark_nre" name="remark_nre[]" style="
							width: 100%;
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;
							height: 35px;float:left;"></td>
					</tr>
					<tr class="padding-five ta-right">
							<td style="width:2%;text-align:center;vertical-align: middle !important;">4</td>
							<td style="width:40%;text-align:left;">ការបង់ភាគលាភ <br/>
							Payment of Dividend
							<input type="hidden" name="type_of_oop[]" value="ការបង់ភាគលាភ <br/>
							Payment of Dividend"></td>
							<td style="width:16%;"><input type="text" class="checknb t_r form-control salary_nre"  readonly name="salary_nre[]"  id="nrb4"  style="
							width: 80%;
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;
							height: 35px;float:left;"><p style="padding-top:5px;margin-bottom: 0px;float:right;">រៀល</p></td>
							<td style="width:12%;"><input type="text" class="checknb t_r form-control persent_nre" readonly name="persent_nre[]" style="
							width: 90%;
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;
							text-align:right;
							height: 35px;float:left;" value="14"><p style="padding-top:5px;margin-bottom: 0px;float:right;">%</p></td>
							<td style="width:16%;"><input type="text" class="checknb t_r form-control withholding_box_nre" readonly  id="nrd4" name="withholding_box_nre[]"  style="
							width: 80%;
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;
							height: 35px;float:left;"  ><p style="padding-top:5px;margin-bottom: 0px;float:right;">រៀល</p></td>
							<td style="width:16%;"><input type="text" class="form-control remark_nre" name="remark_nre[]" style="
							width: 100%;
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;
							height: 35px;float:left;"></td>
					</tr>
					
					<tr class="padding-five ta-right">
							<td style="width:2%;text-align:center;vertical-align: middle !important;"></td>
							<td style="width:40%;text-align:left;">សរុប <br/>
							Total</td>
							<td style="width:16%;" class="TotalSP_NRE"><p style="padding-top:5px;margin-bottom: 0px;float:right;"><span id="total_nr"></span>រៀល</p></td>
							<td style="width:12%;background-color:#7f8c8d;"></td>
							<td style="width:16%;" class="TotalWidhholding_nre"><p style="padding-top:5px;margin-bottom: 0px;float:right;">
							<span id="total_nrt"></span>
							<input type="hidden" id="total_nrt_box" name="total_nrt_box" />
							រៀល</p></td>
							<td style="width:16%;"></td>
					</tr>
						
					</table>
					</div>
					<div>
						<p>យើងខ្ញុំបានពិនិត្យគ្រប់ចំណុចទាំងអស់នៅលើលិខិតប្រកាសនេះ និងតារាងឧបសម័្ពន្ធភ្ជាប់មកជាមួយ ។ យើងខ្ញុំមានសក្ខីប័្រតបញ្ជាក់ច្បាស់លាស់ ត្រឹមត្រូវ ពេញលេញ ដែលធានាបានថា ព័ត៌មានទាំងអស់នៅលើលិខិតប្រកាសនេះ ពិតជាត្រឹមត្រូវប្រាកដមែន ហើយគ្មានប្រតិបត្ដិការណាមួយមិនបានប្រកាសនោះទេ ។ យើងខ្ញុំសូមទទួលខុសត្រូវទាំងស្រុងចំពោះមុខច្បាប់ទាំង ឡាយជាធរមាន ប្រសិនបើព័ត៌មានណាមួយមានការក្លែងបន្លំ ។
						We have examined all items on this return and the annexes attached here with. We have clear, correct, and full supporting documents to ensure that all information on this return is true and accurate and there is no business operation​ undeclared.We are fully responsible due to the existing Laws for any falsified information.</p>
						<div style="float:left;">
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
									<td style="width:111px"></td>
									<td valign="top" align="center">
										<table cellspacing="0" border="0">
											<tbody>
												<tr style="font-size:9px; vertical-align: top;">
													<td>
														<font style="font-family:'Khmer OS';">ធ្វើនៅ </font><input type="text" style=" size:auto; font-size:9px;border-bottom:dotted 1px #666; border-left: 0px; border-top:0px; font-weight:bolder; text-align:center;" id="kh_made_at"  name="kh_made_at" class="kh_made_at"  value="">
														<font style="font-family:'Khmer OS';"> ថ្ងៃទី</font>
													</td>
													<td>
														<input type="text" size="6" style=" font-size:11px; border-bottom:dotted 1px #666; border-left:  0px; border-top:0px; font-family:'Times New Roman', Times, serif; text-align:center; font-weight:bolder" name="cr_dd" id="createD" class="valid cr_dd" value="<?=date('d')?>"> 
													   
													</td>
													<td>
														<font style="font-family:'Khmer OS';"> ខែ</font>
														<input type="text" size="6" style=" font-size:11px; border-bottom:dotted 1px #666; border-left: 0px; border-top:0px;font-family:'Times New Roman', Times, serif; text-align:center; font-weight:bolder" value="<?=date('m')?>" name="cr_mm" id="cr_mm" class="cr_mm"> 
													   
													</td>
													<td>
													   <font style="font-family:'Khmer OS';"> ឆ្នាំ</font>
														<input type="text" size="6" style=" font-size:11px; border-bottom:dotted 1px #666; border-left:0px; border-top:0px;font-family:'Times New Roman', Times, serif;text-align:center; font-weight:bolder " value="<?=date('Y')?>" name="cr_yy" id="cr_yy" class="cr_yy"> 
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
					</div>
			    <div  style="clear: both;">
			<p style="font-size:9px;">* ចំនុច ៣ និង ៤ គឺជាការបង់ការប្រាក់ដោយធនាកាឬស្ថាប័នសញ្ជ័យធនក្នុងស្រុកឲ្យទៅអតិថិជនជាអ្នកផ្ញើរប្រាក់ <br/>
  Point 3 and 4 are the payment of interest by domestic banks and saving institution to their clients whow have deposit or saving accounts.</p>
			   </div>
					
					<div>
					<p style="font-size:12px;"><u>តារាងលម្អិតអំពីពន្ធលើប្រាក់បៀវត្សចំពោះនិយោជិតអនិវាសនជន និងពន្ធលើប្រាក់បៀវត្សចំពោះអត្ថប្រយោជន៍បន្ថែម Details on the Tax on Salary on Non-Resident Employees and Taxon Salary on Fringe Benefit:</u></p>
					
					<table   class="table table-date table-bordered put-border">
						<tr class="ta-center">
							<td><b>លរ<br/>N<sub>o</sub></b></td>
							<td><b>ឈ្មោះអ្នកទទួលប្រាក់​<br/>Name of Recipients</b></td>
							<td><b>កម្មវត្ថុនៃការទូទាត់ប្រាក់<br/>Object of Payment</b></td>
							<td><b>លេខវិក្កយបត្រ <br/>Invoice/Payment Note</b></td>
							<td><b>ទឹកប្រាក់ត្រូវបើកមុនការកាត់ទុក<br/>Amount Before Tax Withheld</b></td>
							<td><b>អត្រាពន្ធ<br/>Tax Rate</b></td>
							<td><b>ពន្ធកាត់ទុក<br/>Withholding Tax</b></td>
						</tr>
						<tr class="ta-center">
							<td>A</td>
							<td>B</td>
							<td>C</td>
							<td>D</td>
							<td>E</td>
							<td>F</td>
							<td>G= E x F</td>							
						</tr>
						
						<?php
						for($i=1;$i<=20;$i++){
						
					echo	'<tr>
							<td>'.$i.'</td>
							<td><input type="text" class="form-control nor" name="nor[]" style="
							
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;
							height: 35px;float:right;"></td>
							<td><input type="text" class="form-control oop" name="oop[]" style="
							
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;
							height: 35px;float:right;"></td>
							<td><input type="text" class="form-control ipn" name="ipn[]" style="
							
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;
							height: 35px;float:right;"></td>
							<td><input type="text" class="checknb form-control  abtw" name="abtw[]" style="
							
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;
							height: 35px;float:right;"></td>
							<td><input type="text" class="checknb form-control tax_r" name="tax_r[]" style="
							width:95%;
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;
							height: 35px;float:left;text-align:center;"  ><p style="padding-top: 5px;margin-bottom: 0px !important;">%</p></td>
							<td><input type="text" class="checknb form-control wdt_tax"  readonly name="wdt_tax[]" readonly style="
							
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;
							height: 35px;float:right;"></td>
								
						</tr>';
						}
						?>		
					</table>
					
				</div>
					
					<div>
					<p style="font-size:12px;"><u>តារាងលម្អិតអំពីពន្ធកាត់​ទុកចំពោះអ្នកជាប់ពន្ធអនិវាសនជន Details on the Withholding Tax on Non-Resident Taxpayers:</u></p>
					
					<table   class="table table-date table-bordered put-border">
						<tr class="ta-center">
							<td><b>លរ<br/>N<sub>o</sub></b></td>
							<td><b>ឈ្មោះអ្នកទទួលប្រាក់​<br/>Name of Recipients</b></td>
							<td><b>កម្មវត្ថុនៃការទូទាត់ប្រាក់<br/>Object of Payment</b></td>
							<td><b>លេខវិក្កយបត្រ <br/>Invoice/Payment Note</b></td>
							<td><b>ទឹកប្រាក់ត្រូវបើកមុនការកាត់ទុក<br/>Amount Before Tax Withheld</b></td>
							<td><b>អត្រាពន្ធ ១៤%<br/>Tax Rate 14%</b></td>
							<td><b>ពន្ធកាត់ទុក<br/>Withholding Tax</b></td>
						</tr>
						<tr class="ta-center">
							<td>A</td>
							<td>B</td>
							<td>C</td>
							<td>D</td>
							<td>E</td>
							<td>F</td>
							<td>G= E x F</td>							
						</tr>
						
						<?php
						for($i=1;$i<=20;$i++){
						
					echo	'<tr>
							<td>'.$i.'</td>
							<td><input type="text" class="form-control nor_nre" name="nor_nre[]" style="
							
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;
							height: 35px;float:right;"></td>
							<td><input type="text" class="form-control oop_nre" name="oop_nre[]" style="
							
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;
							height: 35px;float:right;"></td>
							<td><input type="text" class="form-control ipn_nre" name="ipn_nre[]" style="
							
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;
							height: 35px;float:right;"></td>
							<td><input type="text" class="checknb form-control abtw_nre" name="abtw_nre[]" style="
							
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;
							height: 35px;float:right;"></td>
							<td><input type="text" class="checknb form-control tax_r_nre" name="tax_r_nre[]" style="
							width:95%;
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;
							height: 35px;float:left;text-align:center;" value="14" ><p style="padding-top: 5px;margin-bottom: 0px !important;">%</p></td>
							<td><input type="text" class="checknb form-control wdt_tax_nre" readonly name="wdt_tax_nre[]" style="
							
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;
							height: 35px;float:right;"></td>
								
						</tr>';
						}
						?>		
					</table>
					<p style="font-size:9px;"><u>កំណត់សម្គាល់​​</u> : ប្រសិនបើសរសេរមិនអស់ សូមភ្ជាប់ជាមួយក្រដាសដោយឡែក។<br/>
						<u>Notes</u> : Use separate sheetd if the space is insufficient</p>
				</div>	
				<div class="form-group text-center">
				<?php echo form_submit('save', $this->lang->line("save"), 'id="btnSave" class="btn btn-primary"'); ?>
			</div>
			<?= form_close(); ?>
			</div>
</body>
<script type="text/javascript">
	$(document).ready(function(){
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
		$('.st_dd, .st_mm, .st_yy').change(function(){
			var st_day = $('.st_dd').val();
			var st_month = $('.st_mm').val();
			var st_year = $('.st_yy').val();
			var et_dday = '';
			var et_month = '';
			var et_year = '';
			if(st_day != '' && st_month != '' && st_year != '') {
				var dd = new Date(st_year, st_month, st_day-1);
				var et_day = dd.getDate();
				var et_month = dd.getMonth()+1;
				var et_year = dd.getFullYear();
				$('.en_dd').val(et_day);
				$('.en_mm').val((et_month<10? '0'+et_month:et_month));
				$('.en_yy').val(et_year);
			}
		});
		
		$("#btnSave").click(function(){
			var Enterprise = $("#enterprise").val();
			if(Enterprise==""){
				alert("Please Select Enterprise");
				return false;
			}
		});
		
		
		$(".abtw,.tax_r").change(function(){
			var total   = 0;
			var tr      = $(this).parent().parent();
			var amt_t   = tr.find(".abtw").val()-0;
			var persent = tr.find(".tax_r").val()-0;
			total       = (amt_t*(persent/100));
			tr.find(".wdt_tax").val(total.toFixed(0));
		});
		
		$(".abtw_nre,.tax_r_nre").change(function(){
			var total   = 0;
			var tr      = $(this).parent().parent();
			var amt_t   = tr.find(".abtw_nre").val()-0;
			var persent = tr.find(".tax_r_nre").val()-0;
			total       = (amt_t*(persent/100));
			tr.find(".wdt_tax_nre").val(total.toFixed(0));
		});
		

		
		$(".salarypaid").change(function(){
			var tr = $(this).parent().parent();
			var total = 0;
			var salarypaid = tr.find(".salarypaid").val()-0;
			var persent    = tr.find(".persent").val()-0;
			total = (salarypaid*(persent/100));
			tr.find(".withholding_box").val(total.toFixed(0));
			$(".TotalSP_AMT").html(SumAmt(".salarypaid").toFixed(0));
			$(".TotalWidhholding").html(SumAmt(".withholding_box").toFixed(0));
		});
		
		$(".salary_nre").change(function(){
			var tr = $(this).parent().parent();
			var total = 0;
			var salarypaid = tr.find(".salary_nre").val()-0;
			var persent    = tr.find(".persent_nre").val()-0;
			total = (salarypaid*(persent/100));
			tr.find(".withholding_box_nre").val(total.toFixed(0));
			$(".TotalSP_NRE").html(SumAmt(".salary_nre").toFixed(0));
			$(".TotalWidhholding_nre").html(SumAmt(".withholding_box_nre").toFixed(0));
		});
		
		
		function SumAmt(cls=""){
			var total=0;
			$(""+cls+"").each(function(){
				total+= $(this).val()-0;
			});
			return total;
		}
		
		
		 $( ".st_mm" ).focusout(function() {
				$( "#enterprise" ).trigger( "change" );
		  })
		  $( ".st_yy" ).focusout(function() {
				$( "#enterprise" ).trigger( "change" );
		  })
			$('#enterprise').change(function(){
				var ent_id = $(this).val();
				var month = $('.st_mm').val();
				var year = $('.st_yy').val();
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
							tax_type: 2
						},
						success: function (data) {
							$('.vat').text('');
							var str = data['vat_no'];
							var i = 0;
							$('.vat').each(function() {
								$(this).text(str[i]);
								i++;
							});
							$('.business_act').val(data['business_activity']);
							$('.address').val(data['address']);	
							$('.street').val(data['street']);
							$('.group').val(data['group']);
							$('.village').val(data['village']);
							$('.sangkat').val(data['sangkat']);
							$('.district').val(data['district']);
							$('.city').val(data['city']);
							$('.phone').val(data['phone']);
							$('.email').val(data['email']);
							
							$('#rb1').val((data['wr1']).toFixed(0));
							$('#rd1').val((data['wr1']*0.15).toFixed(0));
							$('#rb2').val((data['wr2']).toFixed(0));
							$('#rd2').val((data['wr2']*0.15).toFixed(0));
							$('#rb3').val((data['wr3']).toFixed(0));
							$('#rd3').val((data['wr3']*0.06).toFixed(0));
							$('#rb4').val((data['wr4']).toFixed(0));
							$('#rd4').val((data['wr4']*0.04).toFixed(0));
							$('#rb5').val((data['wr5']).toFixed(0));
							$('#rd5').val((data['wr5']*0.1).toFixed(0));
							
							var total_r=parseInt(data['wr1'])+parseInt(data['wr2'])+parseInt(data['wr3'])+parseInt(data['wr4'])+parseInt(data['wr5']);
							
							var total_rt=parseInt(data['wr1']*0.15)+parseInt(data['wr2']*0.15)+parseInt(data['wr3']*0.06)+parseInt(data['wr4']*0.14)+parseInt(data['wr5']*0.10);
							
							
							$('#total_r').text(total_r);
							$('#total_rt').text(total_rt);
							$('#total_tr_box').val(total_rt);
							
							
							$('#nrb1').val((data['wnr1']).toFixed(0));
							$('#nrd1').val((data['wnr1']*0.14).toFixed(0));
							$('#nrb2').val((data['wnr2']).toFixed(0));
							$('#nrd2').val((data['wnr2']*0.14).toFixed(0));
							$('#nrb3').val((data['wnr3']).toFixed(0));
							$('#nrd3').val((data['wnr3']*0.14).toFixed(0));
							$('#nrb4').val((data['wnr4']).toFixed(0));
							$('#nrd4').val((data['wnr4']*0.14).toFixed(0));
							
							var total_nr=(data['wnr1'])+(data['wnr2'])+(data['wnr3'])+(data['wnr4']);
							var total_nrt=((data['wnr1']*0.14)+(data['wnr2']*0.14)+(data['wnr3']*0.14)+(data['wnr4']*0.14)).toFixed(0);
							
							$('#total_nr').text(total_nr);
							$('#total_nrt').text(total_nrt);
							$('#total_nrt_box').val(total_nrt);
							
						}
						});
					}
				});
			});
</script>

