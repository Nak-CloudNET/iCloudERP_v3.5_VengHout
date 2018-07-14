
  <title>Value Added Tax</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
 


<style>.t_r{text-align:right;}
.t_c{
	text-align:center;
	}
.t_l{text-align:left;}
.table-body{font-size:11px;}
.dak-border{border:1px solid black;}
.black-box{background-color:#7f8c8d;width:34px;height:34px;text-align:center;}.float-left{float:left;}.put-border tr td{border:1px solid black !important;font-size: 9px;}.put-border th{border:1px solid black !important;font-size: 9px;}.title-style{font-size:12px !important; line-height:14px;}.text-style{font-size:9px !important; line-height:14px;} .input-date td{width:30px;height:10px;} .table-date td{border:0 !important;} .padding-less{padding:3px !important;}</style>

<body>
<?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open_multipart("taxes/value_added_tax", $attrib); ?>
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
			<img src="<?=base_url()?>/assets/images/line-under.png" height="auto" width="120px"/>
			<div style="border:2px solid black;padding-top:5px;">
			<p class="title-style" style="font-family: 'Khmer OS Muol';">លិខិតប្រកាសអាករលើតម្លៃបន្ថែម(បន្ទុករបស់រដ្ឋ)</p>
			<p class="title-style">Return for Value Added Tax(State Change)</p>
			</div>
			<p class="text-style" style="padding-top:5px;font-family: 'Khmer OS Muol';">សូមអានសៀវភៅជំនួយស្មារតីលេខ ០៣ មុននឹងបំពេញទម្រង់។<br/>
Read VAT leaflet No. 03 before completing this form </p>
		</center></div>
		<div class="col-md-4 col-xs-4  col-lg-4">

			<div  style="float:right;border:2px solid black; padding:5px,10px,0,10px !important;">
				<p style="margin:5px!important;" class="title-style">ទម្រង់ អតប​ ​២០០</p>
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
			<table class="table table-date table-bordered " style="width:100%;">
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
							text-align: center;
							height: 20px;" value="<?=date('d',strtotime($front->covreturn_start))?>" readonly="readonly"><p class="text-style"  >From (DD)</p>
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
							text-align: center;
							height: 20px;" value="<?=date('m',strtotime($front->covreturn_start))?>" ><p class="text-style"  >Month(MM)</p>
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
							text-align: center;
							height: 20px;" value="<?=date('Y',strtotime($front->covreturn_start))?>" ><p class="text-style"  >Year(YYYY) </p>
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
							text-align: center;
							height: 20px;" value="<?=date('d',strtotime($front->covreturn_end))?>" ><p class="text-style"  >To 	(DD)</p>
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
							text-align: center;
							height: 20px;" value="<?=date('m',strtotime($front->covreturn_end))?>" ><p class="text-style"  >Month(MM) </p>
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
							text-align: center;
							height: 20px;" value="<?=date('Y',strtotime($front->covreturn_end))?>" ><p class="text-style"  >Year(YYYY)</p>
						</td>
					</td>
				 </td>
				</tr>
			</table>
			</div>
			<div class="col-md-4 col-xs-4  col-lg-4">
			</div>
			<div class="col-md-4 col-xs-4  col-lg-4">
				<table class="table table-date table-bordered put-border" style="width:100%;">
				<tr style="border:1px solid black;">
					<th style="background-color:#7f8c8d;color:white; padding-bottom:0 !important;"  class="title-style">01</th>
					<th style=" padding-bottom:0 !important;" colspan="16"><center><p class="text-style">លេខអត្តសញ្ញាណកម្មសារពើពន្ធ TIN </p></center></th>	
				</tr>
				<tr style="border:1px solid black;">
					<?php 
					$vat = $front->vat_no;
					for($i=0;$i<9;$i++) { 
					?>
					<td><center><p name="vat[]" id="vat" class="text-style vat"><?=$vat[$i]?></p></center></td>	
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
					<div style="float:left;">
					<p class="text-style" style="
							margin-bottom: 0px;
							margin-top: 5px;
						">ឈ្មោះសហគ្រាស </p>
					<p class="text-style">Name of Enterprise:</p>
					</div>
					<div>
					<select class="validate[required] enterprise" style="width:150px;margin-left: 10px;margin-top: 10px;" id="enterprise"  name="enterprise">
								<option value=""> </option>  
								<?php
								
								foreach($enterprise as $com){
									if($com->id == $front->id) {
										echo "<option value='".$com->id."' selected >".$com->company."</option>";
									} else {
										echo "<option value='".$com->id."'>".$com->company."</option>";
									}
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
					<input type="text" class="form-control business_act" name="business_act" value="<?=$front->business_activity?>" readonly ></td>
						</tr>
						<tr>
							<td><p class="text-style" style="
							margin-bottom: 0px;
							margin-top: 0;
						">អាស័យយដ្ឋាន Address: No</p></td>
							<td style="padding-top:4px"><input type="text" name="address"  class="form-control address" style="
							width: 220px;
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;margin-bottom: 5px;
							height: 35px;" value="<?=$front->address?>" readonly></td>
							
							<td><p class="text-style" style="
							margin-bottom: 0px;
							float:left;
						"> វិថី Street </p></td><td style="padding-top:4px"><input type="text" name="street"  class="form-control street" style="
							width: 220px;
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;margin-bottom: 5px;
							height: 35px;" value="<?=$front->street?>" readonly></td>
							
							<td><p class="text-style" style="
							margin-bottom: 0px;
							margin-top: 0;float:left;
						">ក្រុមGroup</p></td><td style="padding-top:4px"><input type="text" class="form-control group" name="group"  style="
							width: 220px;
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;margin-bottom: 5px;
							height: 35px;" value="<?=$front->group?>" readonly></td>
						</td>
						<tr>
							<td><p class="text-style" style="
							margin-bottom: 0px;
							margin-top: 0;float:left;
						">ភូមិVillage</p></td><td><input type="text" class="form-control village"  name="village" style="
							width: 220px;
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;margin-bottom: 5px;
							height: 35px;" value="<?=$front->village?>" readonly></td>
						
							<td><p class="text-style" style="
							margin-bottom: 0px;
							margin-top: 0;float:left;
						">ឃុំ/សង្កាត់ Sangkat</p></td>
						<td><input type="text" class="form-control sangkat" name="sangkat" style="
							width: 220px;
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;margin-bottom: 5px;
							height: 35px;" value="<?=$front->sangkat?>" readonly></td>
							<td><p class="text-style" style="
							margin-bottom: 0px;
							margin-top: 0;float:left;
						" > ខ័ណ្ឌ /ក្រុង/ស្រុក District </p></td><td><input type="text" name="district" class="form-control district" style="
							width: 220px;
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;margin-bottom: 5px;
							height: 35px;" value="<?=$front->district?>" readonly></td>
						</tr>
						<tr>
							<td><p class="text-style" style="
							margin-bottom: 0px;
							margin-top: 0;float:left;
						"> ខេត្ត/រាជធានី Municipality </p></td><td><input type="text" name="municipality" class="form-control municipality" style="
							width: 220px;
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;margin-bottom: 5px;
							height: 35px;" value="<?=$front->city?>"  readonly></td>
						
							<td>
							<p class="text-style" style="
							margin-bottom: 0px;
							margin-top: 0;float:left;
						">ទូរស័ព្ឌ/ទូរសារ Phone/Fax</p></td>
						<td><input type="text" class="form-control phone" name="phone" style="
							width: 220px;
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;margin-bottom: 5px;
							height: 35px;" value="<?=$front->phone?>" readonly>
							</td>
							<td>
							<p class="text-style" style="
							margin-bottom: 0px;
							margin-top: 0;float:left;
						">សារអេឡិចត្រូនិច Email </p></td>
						<td><input type="text" class="form-control email" name="email" style="
							width: 220px;
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;margin-bottom: 5px;
							height: 35px;" value="<?=$front->email?>" readonly>
							</td>
						</tr>
					</table><!--- end----->
				</div>
				<div></div>
			</div>
	</div>
			<div class="col-md-12 col-xs-12  col-lg-12" style="margin-top:10px;">
				
					
					<div class="table-body">
						<table width="">
							<tr width="214px">
								<td>ប្រសិនបើលោក/លោកស្រីគ្មានសកម្មភាពទិញលក់ទេ សូមសរសេរថា "គ្មាន" ក្នុងប្រអប់<br/>If you have made no purchases and no sales, insert "NIL" in this box. </td>
								<td class="black-box dak-border">04</td>
								<td width="214px" class="dak-border"><input type="text" class=" t_r form-control text-box pusa_act04" name="pusa_act04" style="
							width: 220px;
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;
							height: 35px;" value="<?=$front->pusa_act04?>" ></td>
							</tr>
						
						</table>
						<p class="title-style" >ប្រសិនបើគ្មានទិន្នន័យអ្វីត្រូវបំពេញក្នុងប្រអប់ណាមួយទេ សូមសរសេរថា "គ្មាន" កុំទុកប្រអប់ចោលទំនេរ លើកលែងតែលោក/លោកស្រីសរសេរថា"គ្មាន" ក្នុងប្រអប់លេខ 04<br/>​
							If you have no entry for a box, insert "NIL". Do not leave any box blank unless you insert "NIL" in box 04.</p>
						
						
						
						<table width="100%">
							<tr>
								<td>ឥណទានអាករលើធាតុចូលពីខែមុន Input tax credit form previous month.</td>
								<td class="black-box dak-border">05</td>
								<td width="214px" class="dak-border"><input type="text" class="checknb t_r form-control text-box tax_credit_premonth05" name="tax_credit_premonth05" style="
							width: 220px;
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;
							height: 35px;" value="<?=$front->tax_credit_premonth05?>" readonly="readonly" ></td>
							</tr>
						</table>
						<p class="title-style"><u>ការទិញទំនិញឬសេវាក្នុងខែ</u>(ធាតុចូល) <u>Month's purchases of goods and services</u> (Input) តំលៃមិនរួមបញ្ចូលអាករ Value exclusive of VAT </p>
					<table width="100%">
						<tr>
							<td>ការទិញមិនជាប់អាករឬការទិញមិនអនុញ្ញាត ឥណទាន Non-taxable or non-creditable purchase</td>
							<td class="black-box dak-border">06</td>
							<td width="214px" class="dak-border"><input type="text" class="checknb t_r form-control text-box ncredit_purch06" name="ncredit_purch06" style="
							width: 220px;
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;float:left;
							height: 35px;" value="<?=$front->ncredit_purch06?>" readonly="readonly" ></td>
							<td width="214px"  class="dak-border" colspan="2" style="background-color: #7f8c8d;"></td>
						</tr>
						
						<tr>
							<td>ការទិញក្នុងស្រុកតាមអត្រាធម្មតា 10% Standard rated local purchases</td>
							<td class="black-box dak-border">07</td>
							<td width="214px"  class="dak-border"><input type="text" class="checknb t_r form-control text-box strate_purch07" name="strate_purch07" style="
							width: 220px;
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;
							height: 35px;" value="<?=$front->strate_purch07?>" readonly="readonly" ></td>
							<td class="black-box dak-border">08</td>
							<td width="214px"  class="dak-border"><input type="text" class="checknb t_r form-control text-box strate_purch08" name="strate_purch08" style="
							width: 220px;
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;float:left;
							padding-right: 2px;
							height: 35px;" value="<?=$front->strate_purch08?>" readonly="readonly" >	</td>
						</tr>
						<tr>
							<td>ការនាំចូលជាបន្ទុករដ្ឋ(មិនអាចកាត់កងបាន)  State changes' imports(non-deductible)</td>
							<td class="black-box dak-border">09</td>
							<td width="214px"  class="dak-border"><input type="text" class="checknb t_r form-control text-box strate_imports09" name="strate_imports09" style="
							width: 220px;
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;
							height: 35px;" value="<?=$front->strate_imports09?>"  readonly="readonly"></td>
							<td class="black-box dak-border">10</td>
							<td width="214px"  class="dak-border"><input type="text" class="checknb t_r form-control strate_imports10 text-box" name="strate_imports10" style="
							width: 220px;
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;
							height: 35px;" value="<?=$front->strate_imports10?>"  readonly="readonly"></td>
						</tr>
						<tr>
							<td colspan="3" style="text-align:right;">សរុបទឹកប្រាក់អាករលើធាតុចូល Total amount of input tax (05+08+10)</td>
						
							<td class="black-box dak-border">11</td>
							<td width="214px" class="dak-border"><input type="text" class="checknb t_r form-control text-box total_intax11" name="total_intax11" style="
							width: 220px;
							padding-top: 2px;
							padding-bottom: ;
							padding-left: 2px;
							padding-right: 2px;
							height: 35px;" value="<?=$front->total_intax11?>"  readonly="readonly"></td>
						</tr>
					</table>
					<p class="title-style"><u>ការលក់ទំនិញនិងសេវាឬការនាំចេញក្នុងខែ</u>(ធាតុចេញ) <u>Month's sales of goods and services or exports</u> (Output) តំលៃមិនរួមបញ្ចូលអាករ Value exclusive of VAT </p>			
					<table width="100%">
						<tr>
							<td>ការលក់មិនជាប់អាករ Non-taxable sales  </td>
							<td class="black-box dak-border">12</td>
							<td width="214px" class="dak-border"><input type="text" class="checknb t_r form-control text-box ntaxa_sales12" name="ntaxa_sales12" style="
							width: 220px;
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;float:left;
							padding-right: 2px;
							height: 35px;" value="<?=$front->ntaxa_sales12?>"  readonly="readonly"></td>
							<td width="214px"  class="dak-border" colspan="2" style="background-color: #7f8c8d;"></td>
						</tr>
						<tr>
							<td>ការលក់តាមអត្រា 0% Exports  </td>
							<td class="black-box dak-border">13</td>
							<td width="214px"  class="dak-border"><input type="text" class="checknb t_r form-control text-box exports13" name="exports13" style="
							width: 220px;
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;
							height: 35px;" value="<?=$front->exports13?>" readonly="readonly"></td>
							<td colspan="2" class="dak-border" style="background-color:#7f8c8d;"></td>
							
						</tr>
						<tr>
							<td>ការលក់តាមអត្រាធម្មតា 10% Standard rated sales </td>
							<td class="black-box dak-border">14</td>
							<td width="214px"  class="dak-border"><input type="text" class="checknb t_r form-control text-box strate_sales14" name="strate_sales14" style="
							width: 220px;
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;
							height: 35px;" value="<?=$front->strate_sales14?>"  readonly="readonly"></td>
							<td class="black-box dak-border">15</td>
							<td width="214px"  class="dak-border"><input type="text" class="checknb t_r form-control text-box strate_sales15" name="strate_sales15" style="
							width: 220px;
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;float:left;
							padding-right: 2px;
							height: 35px;" value="<?=$front->strate_sales15?>"  readonly="readonly"></td>
						</tr>
						<tr>
							<td colspan="3" style="text-align:left;">ប្រសិនបើចំនួនក្នុងប្រអប់លេស 15 លើសពីចំនួនក្នុងប្រអប់លេស 11 ប្រូវបង់ចំនួនលម្អៀង If box 15 exceeds box 11,pay the difference</td>
						
							<td class="black-box dak-border">16</td>
							<td width="214px" class="dak-border"><input type="text" class="checknb t_r form-control text-box pay_difference16" name="pay_difference16" style="
							width: 220px;
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;
							height: 35px;" value="<?=$front->pay_difference16?>"  readonly="readonly"></td>
						</tr>
					
					</table>
						
					<p class="title-style">ប្រសិនបើចំនួនក្នុងប្រអប់ 11 លើសពីចំនួនក្នុងប្រអប់លេខ 15 ត្រូវស្នើសុំចំនួនលំអៀងដើម្បី: If box 11 exceeds box 15, claim the difference for: </p>
					
					<table width="100%">
						<tr>
							<td>បង្វិលសង Refund</td>
							<td class="black-box dak-border">17</td>
							<td width="184px" class="dak-border"><input type="text" class="checknb t_r form-control text-box refund17" name="refund17" style="
							width: 190px;
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;float:left;
							padding-right: 2px;
							height: 35px;" value="<?=$front->refund17?>" ></td>
							<td style="float:right;padding-top:10px;">ជាឥណទានសម្រាប់យោងទៅមុខ Credit carried forward </td>
							<td class="black-box dak-border">18</td>
							<td width="184px" class=" dak-border"><input type="text" class="checknb credit_forward18 text-box t_r " name="credit_forward18" style="
							width: 190px;
							padding-top: 2px;
							padding-bottom: 2px;
							padding-left: 2px;
							padding-right: 2px;
							height: 35px;" value="<?=$front->credit_forward18?>"  readonly="readonly"></td>
						</tr>
					</table>
				</div>
				<p class="title-style">
					យើងខ្ញុំបានពិនិត្យគ្រប់ចំណុចទាំងអស់នៅលើលិខិតប្រកាសនេះ និងតារាងឧបសម័្ពន្ធភ្ជាប់មកជាមួយ ។ 
					យើងខ្ញុំមានសក្ខីប័្រតបញ្ជាក់ច្បាស់លាស់ ត្រឹមត្រូវ ពេញលេញ ដែលធានាបានថា ព័ត៌មាន ទាំងអស់នៅលើលិខិតប្រកាសនេះ ពិតជាត្រឹមត្រូវប្រាកដមែន ហើយគ្មានប្រតិបត្ដិការណាមួយមិនបានប្រកាសនោះទេ ។ យើងខ្ញុំសូមទទួលខុសត្រូវទាំងស្រុងចំពោះមុខច្បាប់ទាំងឡាយជាធរមាន ប្រសិនបើព័ត៌មានណាមួយមានការក្លែងបន្លំ ។ <br/> We have examined all items on this return and the annexes attached here with. We have clear, correct, and full supporting documents to ensure that all information on this return is true 
					and accurate and there is no business operation undeclared. We are fully responsible due to the existing Laws for any falsified information.
					</p>
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
														<font style="font-family:'Khmer OS';">ធ្វើនៅ </font><input type="text" style=" size:auto; font-size:9px;border-bottom:dotted 1px #666; border-left: 0px; border-top:0px; font-weight:bolder; text-align:center;" id="kh_made_at"  name="kh_made_at" class="kh_made_at"  value="<?=$front->field_in_kh?>">
														<font style="font-family:'Khmer OS';"> ថ្ងៃទី</font>
													</td>
													<td>
														<input type="text" size="6" style=" font-size:11px; border-bottom:dotted 1px #666; border-left:  0px; border-top:0px; font-family:'Times New Roman', Times, serif; text-align:center; font-weight:bolder" name="cr_dd" id="createD" class="valid cr_dd" value="<?=date('d',strtotime($front->created_date))?>"> 
													   
													</td>
													<td>
														<font style="font-family:'Khmer OS';"> ខែ</font>
														<input type="text" size="6" style=" font-size:11px; border-bottom:dotted 1px #666; border-left: 0px; border-top:0px;font-family:'Times New Roman', Times, serif; text-align:center; font-weight:bolder" value="<?=date('m',strtotime($front->created_date))?>" name="cr_mm" id="cr_mm" class="cr_mm"> 
													   
													</td>
													<td>
													   <font style="font-family:'Khmer OS';"> ឆ្នាំ</font>
														<input type="text" size="6" style=" font-size:11px; border-bottom:dotted 1px #666; border-left:0px; border-top:0px;font-family:'Times New Roman', Times, serif;text-align:center; font-weight:bolder " value="<?=date('Y',strtotime($front->created_date))?>" name="cr_yy" id="cr_yy" class="cr_yy"> 
													</td>
												</tr>
												<tr style="font-size: 9px; vertical-align: top;">
													<td>
														Filed in <input type="text" style="size:auto; font-size:9px;border-bottom:dotted 0px #666; border-left: 0px; border-top:0px; font-family:'Times New Roman', Times, serif; border-bottom:dotted 1px #666; border-left: 0px; border-top:0px;  width:160px; text-align:center;" name="en_made_at" value="<?=$front->field_in_en?>">
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
			</div>
			<div>
						<table>
							<tr>
							<td style="font-size:12px;" valign="top">
                        	<font style="font-family:'Khmer OS'; font-size:12px;"><u>កំណត់សម្គាល់</u></font> Please note:<br>
							<i>1.លោក/លោកស្រីត្រូវដាក់លិខិតប្រកាសនេះព្រមទាំងបង់ប្រាក់អាករយ៉ាងយឺតបំផុតត្រឹមថ្ងៃទី ២០ នៃខែបន្ទាប់ពីខែដែលចុះក្នុងប្រអប់លេខ 02.<br>
							This return and payment must be presented by the 20th day of the month following the month of box 02.<br>
							2.លោក/លោកស្រីនឹងទទួលរងការផាកពិន័យ ទៅតាមច្បាប់ស្តីពីសារពើពន្ធ ប្រសិនបើលោក/លោកស្រី
							You will be, according to the Law on Taxation, subject to penalties if you:<br>

                            	&nbsp;&nbsp;- មិនបានដាក់លិខិតប្រកាស​នៅការិយាល័យរបបពិត ទោះបីជាគ្មានប្រាក់អាករត្រូវបង់ក៏ដោយ Fail to file the tax return to the Real Regime Tax Office even it is a nil return.<br>

                                &nbsp;&nbsp;- មិនបង់ឬបង់យឺត នូវប្រាក់អាករដែលបានប្រកាស do not pay or pay lately the tax declared.<br>
                               &nbsp;&nbsp; - ធ្វើការប្រកាសក្លែងបន្លំ Making a false declaration.
                                </i>

                        	
                        </td>
							</tr>
						</table>
			</div>
			<br/><br/>
			<div>
			<center><p  style="font-family: 'Khmer OS Muol';">សងេ្ខបប្រតិបត្ដិការ ទិញដែលអនុញ្ញាតឥណទាន និងការលក់ជាប់អាករ ប្រចាំខែ *<br/>
			SUMMARY OF CREDITABLE PURCHASES AND TAXABLE SALES FOR THE MONTH </p>
				
			<table class=" table table-bordered put-border">
    <tr>
        <td style="background-color:#7f8c8d; color:white;font-weight:bold;text-align:center;">20</td>
        <td colspan="6" style="text-align:center">ការនាំចូលឬការទិញ ទំនិញឬសេវាដែលបានស្នើសុំឥណទាន GOODS OR SERVICES IMPORTED OR PURCHASED ON WHICH A CREDIT IS CLAIMED</td>
    </tr>
    <tr>
        <td style="text-align:center">លរ <br>N<sup>o</sup></td>
        <td style="text-align:center">បរិយាយមុខទំនិញឬសេវា <br>Description of Goods or Services</td>
        <td style="text-align:center">បរិមាណ <br>Quantity</td>
        <td style="text-align:center">កាលបរិចេ្អទទិញ <br>Date of Purchase</td>
        <td style="text-align:center">លេខវិក្កយបត្រ/ប្រតិវេទន៍គយ<br>Invoice / Customs Declaration Number</td>
        <td style="text-align:center">អ្នកផ្គត់ផ្គង<br>Suppliers</td>
        <td style="text-align:center">តម្លៃមិនរួមបញ្ចូលអាករ <br>Value exclusive of Vat</td>
    </tr>

	<?php
		$bl[""] = "";
		foreach ($Product as $biller) {
			$bl[$biller->code] = $biller->code != '-' ? $biller->name : $biller->name;
		}
		$j = 1;
		$total_qty = 0;
		$total_val_vat = 0;
		foreach($back_20 as $b20) {
		$opt="";
		foreach($suppid as $row) {
			if($row->id == $b20->supp_exp_inn) {
				$opt.="<option value=".$row->id." selected >".$row->name."</option>";	
			} else {
				$opt.="<option value=".$row->id.">".$row->name."</option>";	
			}
		}
		echo '<tr><td style="text-align:center">'.$j.'</td>
				<td>
				'.form_dropdown('product_1[]', $bl, $b20->productid, 'id="product_1"  required="required" class="form-control input-tip select" style="width:100%;"').'</td>
				<td><input type="text" class="checknb t_c form-control qty_1" name="qty_1[]" value="'. $b20->qty .'"></td>
				<td>'.form_input('date_1[]', $this->erp->hrsd($b20->date), 'class="form-control date date_1"  id="date_1" required="required"').'</td>
				<td><input type="text" class="form-control inv_declare_1" name="inv_declare_1[]" value="'. $b20->inv_cust_desc .'" ></td>
				<td><select class="form-control input-sm suppid_1" name="suppid_1[]" style="width:200px"><option value="" selected></option>'.$opt.'</select></td>
				<td><input type="text" class="checknb t_r  form-control VAT_1" name="VAT_1[]" value="'. $b20->val_vat .'" ></td>
			</tr>';
			$total_qty += $b20->qty;
			$total_val_vat += $b20->val_vat;
			$j++;
		}
		if($j < 11) {
			for($i=$j;$i<=11;$i++) {
			$opt="";
			foreach($suppid as $row) {
				$opt.="<option value=".$row->id.">".$row->name."</option>";	
			}
			echo '<tr><td style="text-align:center">'.$i.'</td>
					<td>
					'.form_dropdown('product_1[]', $bl, "", 'id="product_1"  required="required" class="form-control input-tip select" style="width:100%;"').'</td>
					<td><input type="text" class="checknb t_c form-control qty_1" name="qty_1[]"></td>
					<td>'.form_input('date_1[]', "", 'class="form-control date date_1"  id="date_1" required="required"').'</td>
					<td><input type="text" class="form-control inv_declare_1" name="inv_declare_1[]"></td>
					<td><select class="form-control input-sm suppid_1" name="suppid_1[]" style="width:200px"><option value="" selected></option>'.$opt.'</select></td>
					<td><input type="text" class="checknb t_r  form-control VAT_1" name="VAT_1[]"></td>
				</tr>';
			}
		}
    ?>
    <tr>
        <td></td>
        <td style="text-align:right">សរុប ​Total:</td>
        <td class="totalQTY_1 text-center"><?=$total_qty?></td>
        <td style="background-color:#7f8c8d;"></td>
        <td style="background-color:#7f8c8d;"></td>
        <td style="background-color:#7f8c8d;"></td>
        <td class="totalVAT_1 text-right" style="padding-right:10px;"><?=$total_val_vat?></td>
    </tr>
</table>	
			</center>
			</div>
			
			<div>
			<p>*សូមគូសរង្វង់ជុំវិញលេខរៀងមុខទំនិញណាដែលបានទិញឬនាំចូលដើម្បីប្រើប្រាស់ជាទ្រព្យសកមμរបស់សហគ្រាស<br/>
Please circle any serial No of the items purchased or imported for the enterprise’s fixed assets. </p>
			<center>
				
			<table class=" table table-bordered put-border">
    <tr>
        <td style="background-color:#7f8c8d; color:white;font-weight:bold;text-align:center;">21</td>
        <td colspan="6" style="text-align:center;font-family: 'Khmer OS Muol';">សរុប​ការ​នាំ​ចេញ​ប្រចាំខែ ដែល​បាន​ស្នើ​សុំ​អត្រា​សូន្យ GOODS OR SERVICES EXPORTED ON WHICH ZERO RATE IS CLAIMED</td>
    </tr>
    <tr>
        <td style="text-align:center">លរ <br>N<sup>o</sup></td>
        <td style="text-align:center">បរិយាយមុខទំនិញឬសេវា <br>Description of Goods or Services</td>
        <td style="text-align:center">បរិមាណ <br>Quantity</td>
        <td style="text-align:center">កាលបរិចេ្អទនាំចេញ<br/>Date of Export</td>
        <td style="text-align:center">លេខប្រតិវេទន៍គយ<br/>
Invoice / Customs Declaration number</td>
        <td style="text-align:center">ច្រក​នាំចេញ​<br/>
Exit Point</td>
        <td style="text-align:center">តម្លៃ​នាំ​ចេញ<br/>
Export Value</td>
    </tr>

	<?php
		$bl[""] = "";
		foreach ($Product as $biller) {
			$bl[$biller->code] = $biller->code != '-' ? $biller->name : $biller->name;
		}
		$j = 1;
		$total_qty = 0;
		$total_export_val = 0;
		foreach($back_21 as $b21) {
		echo '<tr><td style="text-align:center">'.$j.'</td>
					<td>'.form_dropdown('product_2[]', $bl, $b21->productid, 'id="product_2"  required="required" class="form-control input-tip select" style="width:100%;"').'</td>
					<td><input type="text" class="checknb t_c form-control qty_2" name="qty_2[]" value="'. $b21->qty .'" ></td>
					<td>'.form_input('date_2[]', $this->erp->hrsd($b21->date) , 'class="form-control date" id="date_2" required="required"').'</td>
					<td><input type="text" class="form-control inv_declare_2" name="inv_declare_2[]" value="'. $b21->inv_cust_desc .'" ></td>
					<td><input type="text" class="form-control exp_2" name="exp_2[]" value="'. $b21->supp_exp_inn .'" ></td>
					<td><input type="text" class="checknb t_r form-control exv_2" name="exv_2[]" value="'. $b21->val_vat .'" ></td>
				</tr>';
			$total_qty += $b21->qty;
			$total_export_val += $b21->val_vat;
			$j++;
		}
		if($j < 11) {
			for($i=$j;$i<=11;$i++) {
				echo '<tr><td style="text-align:center">'.$i.'</td>
							<td>'.form_dropdown('product_2[]', $bl, "", 'id="product_2"  required="required" class="form-control input-tip select" style="width:100%;"').'</td>
							<td><input type="text" class="checknb t_c form-control qty_2" name="qty_2[]"></td>
							<td>'.form_input('date_2[]', "" , 'class="form-control date" id="date_2" required="required"').'</td>
							<td><input type="text" class="form-control inv_declare_2" name="inv_declare_2[]"></td>
							<td><input type="text" class="form-control exp_2" name="exp_2[]"></td>
							<td><input type="text" class="checknb t_r form-control exv_2" name="exv_2[]"></td>
						</tr>';
			}
		}
    ?>
    <tr>
        <td></td>
        <td style="text-align:right">សរុប ​Total:</td>
        <td class="totalQTY_2 text-center"><?=$total_qty?></td>
        <td style="background-color:#7f8c8d;"></td>
        <td style="background-color:#7f8c8d;"></td>
        <td style="background-color:#7f8c8d;"></td>
        <td class="totalEXV_2 text-right" style="padding-right: 10px;"><?=$total_export_val?></td>
    </tr>
</table>	
			</center>
			</div>
			<div>
			<center>
				
			<table class=" table table-bordered put-border">
    <tr>
        <td style="background-color:#7f8c8d; color:white;font-weight:bold;text-align:center;">22</td>
        <td colspan="6" style="text-align:center;font-family: 'Khmer OS Muol';">សរុប​ការ​នាំ​ចេញ​ប្រចាំខែ ដែល​បាន​ស្នើ​សុំ​អត្រា​សូន្យ GOODS OR SERVICES EXPORTED ON WHICH ZERO RATE IS CLAIMED</td>
    </tr>
	<tr>
	<td colspan="4" style="text-align:center;">ទំនិញ GOODS​ </td>
	<td colspan="4" style="text-align:center;">សេវាកម្ម SERVICES </td>
	</tr>
    <tr>
        <td style="text-align:center">លរ <br>N<sup>o</sup></td>
        <td style="text-align:center">បរិយាយមុខទំនិញឬសេវា <br>Description of Goods or Services</td>
        <td style="text-align:center">បរិមាណ <br>Quantity</td>
        <td style="text-align:center">តម្លៃ​​មិន​រួម​បញ្ចូល​អាករ<br/>
Value exclusive of VAT</td>
        <td style="text-align:center">បរិយាយ<br/>
Description</td>
        <td style="text-align:center">វិក្ក័យបត្រ​ពី​លេខ​ដល់​លេខ<br/>
Invoice from Nº to Nº</td>
        <td style="text-align:center">តម្លៃ​មិន​រួមបញ្ចូល​អាករ<br/>
Value exclusive of VAT</td>
    </tr>

	<?php
		$bl[""] = "";
		foreach ($Product as $biller) {
			$bl[$biller->code] = $biller->code != '-' ? $biller->name : $biller->name;
		}
		$j = 1;
		$total_qty = 0;
		$total_val_vat_g = 0;
		foreach($back_22 as $b22) {
		echo '<tr><td style="text-align:center">'.$j.'</td>
				<td>'.form_dropdown('product_3[]', $bl, $b22->productid, 'id="product_3"  required="required" class="form-control input-tip select" style="width:100%;"').'</td>
				<td><input type="text"  class="checknb t_c form-control qty_3" name="qty_3[]" value="'. $b22->qty .'"></td>
				<td><input type="text" class="checknb t_r form-control VAT_3" name="VAT_3[]" value="'. $b22->val_vat .'"></td>
				<td><input type="text" class="form-control DESC_3" name="DESC_3[]" value="'. $b22->supp_exp_inn .'"></td>
				<td><input type="text" class="form-control INV_3" name="INV_3[]" value="'. $b22->inv_cust_desc .'"></td>
				<td><input type="text" class="checknb t_r form-control VAT2_3" name="VAT2_3[]" value="'. $b22->val_vat_g .'"></td>
			</tr>';
			$total_qty += $b22->qty;
			$total_val_vat_g += $b22->val_vat_g;
			$j++;
		}
		if($j < 11) {
			for($i=1;$i<=11;$i++) {
			echo '<tr><td style="text-align:center"></td>
					<td>'.form_dropdown('product_3[]', $bl, "", 'id="product_3"  required="required" class="form-control input-tip select" style="width:100%;"').'</td>
					<td><input type="text"  class="checknb t_c form-control qty_3" name="qty_3[]"></td>
					<td><input type="text" class="checknb t_r form-control VAT_3" name="VAT_3[]"></td>
					<td><input type="text" class="form-control DESC_3" name="DESC_3[]"></td>
					<td><input type="text" class="form-control INV_3" name="INV_3[]"></td>
					<td><input type="text" class="checknb t_r form-control VAT2_3" name="VAT2_3[]"></td>
				</tr>';
			}
		}
    ?>
    <tr>
        <td></td>
        <td style="text-align:right">សរុប ​Total:</td>
        <td class="totalQTY_3 text-center"><?=$total_qty?></td>
        <td style="background-color:#7f8c8d;"></td>
        <td style="background-color:#7f8c8d;text-align:right;color:white;">សរុប​ Total</td>
        <td style="background-color:#7f8c8d;"></td>
        <td class="TotalVAT2_3 text-right" style="padding-right: 10px;"><?=$total_val_vat_g?></td>
    </tr>
		</table>	
	</center>
  </div>
</div>

	<div class="form-group text-center ">
		
            <?php echo form_submit('save', lang('save'), 'class="btn btn-primary btnSave" id="add_submit"'); ?>
        </div>

    </div>
    <?php echo form_close(); ?>

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
					$('.business_act').val(data['business_activity']);
					$('.address').val(data['address']);	
					$('.street').val(data['street']);
					$('.group').val(data['group']);
					$('.village').val(data['village']);
					$('.sangkat').val(data['sangkat']);
					$('.district').val(data['district']);
					$('.municipality').val(data['']);
					$('.municipality').val(data['city']);
					$('.phone').val(data['phone']);
					$('.email').val(data['email']);
				}
			});
		});
		
		$(".qty_1").change(function(){
			$(".totalQTY_1").html(SumQTY(".qty_1").toFixed(2));
		});
		
		$(".VAT_1").change(function(){
			$(".totalVAT_1").html(SumQTY(".VAT_1").toFixed(2));
		});
		
		$(".qty_2").change(function(){
			$(".totalQTY_2").html(SumQTY(".qty_2").toFixed(2));
		});
		
		$(".exv_2").change(function(){
			$(".totalEXV_2").html(SumQTY(".exv_2").toFixed(2));
		});
		
		$(".qty_3").change(function(){
			$(".totalQTY_3").html(SumQTY(".qty_3").toFixed(2));
		});
		
		$(".VAT2_3").change(function(){
			$(".TotalVAT2_3").html(SumQTY(".VAT2_3").toFixed(2));
		});
		
		function SumQTY(cls=""){
			var total=0;
			$(""+cls+"").each(function(){
				total+= $(this).val()-0;
			});
			return total;
		}
		
		
	});
</script>

