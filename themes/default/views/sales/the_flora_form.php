<!DOCTYPE html>
<html>
<head>
	<title>Contract Home Sale</title>
	<!-- <link rel="stylesheet" type="text/css" href="bootstrap/css/style.css"> -->
	<!-- <link href='https://fonts.googleapis.com/css?family=Moul' rel='stylesheet'> -->
	<!-- <link href='https://fonts.googleapis.com/css?family=Battambang' rel='stylesheet'> -->
	<meta charset="UTF-8">
	<!-- <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet"> -->
	<link href="<?php echo $assets ?>styles/theme.css" rel="stylesheet">
	<style type="text/css">
		body{
			text-align: justify;
			text-justify: inter-word;
		}
		.moul{
			font-family: Khmer OS Muol Light !important;
			font-weight:bold;
		}
		.font-kh-bt{
			font-family: Khmer OS Battambang !important;
		}
		@page {
			size: A4;
			margin: 50px;
		}
		@media print{
			.set_line{
				text-align:center !important;
			}
			#set_line{
				margin:0px !important;
				padding:0px !important;
			}
			
		}
		
		.container {
			width: 21cm;
			margin: 0px auto;
			box-shadow: 0 0 5px rgba(0, 0, 0, 0.5);
			font-family: Khmer OS Battambang;
			font-size: 12px;
			//color: #2b4666;
		}
		.container h4 {
			font-family: Khmer OS Muol!important; 
		}
		
		.both{
			line-height:0px;
		}
		.left-div{
			float:left;
			width:33%;
			height:50px;
			text-align:center;
		}
		.center-div{
			float:left;
			width:33%;
			height:50px;
			text-align:center;
		}
		.right-div{
			float:left;
			width:33%;
			height:50px;
			text-align:center;
		}
		
	</style>
</head>
<body>	
	<div class='container'>
		<div class="col-lg-12">
			<div class="col-lg-12">
				<button class="pull-right no-print" id="print_receipt" onclick="window.print();"><?php echo lang("Print"); ?>&nbsp;<i class="fa fa-print"></i></button>
			</div>
			<div class="row text-center col-lg-12">
				<h4 class="moul">ព្រះរាជាណាចក្រកម្ពុជា</h4>
				<h4 class="moul">ជាតិ​  សាសនា  ព្រះមហាក្សត្រ</h4>
				<h4 class="moul" style="padding-top:10px;">កិច្ចសន្យាបង់រម្លស់</h4>
			</div>
			<div class='row col-lg-12 font-kh-bt'>
				<p>
					<b><u>យោង:</u></b>
					កិច្ចព្រមព្រៀងទិញ-លក់ផ្ទះលេខ <b><?php if(is_array($rows)){ foreach($rows as $row){echo $row->cf3.", ";}}?></b> 
					ដែលបានធ្វើឡើងនៅភ្នំពេញថ្ងៃទី <b><?php echo $date_day;?></b>
					ខែ <b><?php echo $month_kh;?></b> 
					ឆ្នាំ​ <b><?php echo $date_year?></b> 
					កិច្ចសន្យានេះធ្វើឡើងនៅថ្ងៃទី <b><?php echo $date_day;?></b>
					ខែ <b><?php echo $month_kh;?></b> 
					ឆ្នាំ​<b> <?php echo $date_year?></b> រវាង:
			    ​​​​​​</p>
				<!--Saller-->
				<p>
					<b><?php if($saller->gender == 'male'){echo '-លោក/លោកស្រី ';}else{echo '-លោក/លោកស្រី​ ';}?>
					   ​​​​​​​​​​​​​​​<?php if($saller->saller_kh !=''){echo $saller->saller_kh;}else{echo $saller->saller;}?>
					</b>  
					ភេទ <b><?php echo $saller->gender;?></b>
					កើតនៅថ្ងៃទី<b><?php echo $db_date;?></b>
					ខែ <b><?php echo $db_month;?> </b>
					ឆ្នាំ​ <b><?php echo $db_year?> </b>
					សញ្ជាតិ <b> <?php if($saller->nationality_kh){echo $saller->nationality_kh;}else{echo $saller->nationality;}?></b>
					កាន់អត្តសញ្ញាណប័ណ្ណលេខ <b><?php echo $saller->identify?></b>
					ជាម្ចាស់ <b><?php if($saller->company_kh){echo $saller->company_kh;}else{echo $saller->company;}?></b>
					គំរោង	<b><?php if($saller->company_kh){echo $saller->company_kh;}else{echo $saller->company;}?></b> មានទីលំនៅ <?php if($saller->village){?>
					ភូមិ <?php echo $saller->village;}?><?php if($saller->sangkat){?>
					សង្កាត់ <?php echo $saller->sangkat;}?><?php if($saller->district){?>
					ខ័ណ្ឌ<?php echo $saller->district;}?> 
					<?php if($saller->country){?><?php echo $saller->country;}?> 
					   ជាភាគីអ្នកលក់ ចាប់ពីពេលនេះតទៅហៅថាភាគី”ក”និង
				</p>
				<!--Customer-->
				<p>
					<b><?php if($customer->gender == 'male'){echo '-លោក/លោកស្រី ';}else{echo '-លោក/លោកស្រី ';}?>
					   <?php if($customer->name_kh !=""){echo $customer->name_kh;}else{echo $customer->name;}?>
					</b>
				 ​​​​	 ភេទ <b><?php echo $customer->gender;?></b> 
					កើតនៅថ្ងៃទី<b><?php echo $dbcus_date;?></b> 
					ខែ <b><?php echo $dbcus_month;?></b>
					ឆ្នាំ​ <b><?php echo $dbcus_year?></b>
					សញ្ជាតិ<b><?php if($customer->nationality){echo$customer->nationality;}?> </b>
					កាន់អត្តសញ្ញាណប័ណ្ណលេខ<b><?php if($customer->cf1){echo $customer->cf1;}else{echo "N/A";}?></b> 
					ចុះថ្ងៃទី <b><?php echo $date_day;?> </b>
					ខែ  <b><?php echo $month_kh;?> </b> 
					ឆ្នាំ​  <b><?php echo $date_year?> </b> 
					<b>និងលោក/លោកស្រី<?php echo ($jl_data->name!=""?$jl_data->name:"......................")?></b>
					ភេទ <b><?php echo ($jl_data->gender!=""?$jl_data->gender:"...........")?> </b>
					កើតនៅថ្ងៃទី <b><?php echo $jl_date;?></b> 
					ខែ  <b><?php echo $jl_month;?> </b>
					ឆ្នាំ​ <b> <?php echo $jl_year;?> </b>
					សញ្ជាតិ​ <b>ខ្មែរ </b>កាន់អត្តសញ្ញាណប័ណ្ណលេខ  
					<b><?php echo ($jl_data->identify_card!=""?$jl_data->identify_card:"...........")?></b>
					ចុះថ្ងៃទី <b><?php if($customer->identify_date){ echo date("d/m/Y",strtotime($customer->identify_date));}else{ echo " .........................";}?>
					</b> មានអាសយដ្ឋានរួមនៅ ផ្ទះលេខ
					<b><?php if($customer->address){ echo $customer->address;}else{ echo "N/A";}?>
					</b>
					ផ្លូវ  <b><?php if($customer->street){ echo $customer->street;}else{ echo "N/A";} ?></b>
					ភូមិ <b><?php if($customer->village){ echo $customer->village;}else{ echo "N/A";}?></b> 
					សង្កាត់ <b><?php if($customer->sangkat){ echo $customer->sangkat; }else{ echo "N/A";} ?></b> 
					ខណ្ឌ  <b><?php if($customer->district){ echo $customer->district;}else{ echo "N/A";} ?></b> 
					ក្រុង <b><?php if($customer->city){echo $customer->city;}else{ echo $customer->state; }?></b>
					ទូរស័ព្ទលេខ <b><?php if($customer->phone){ echo $customer->phone; }else{ echo "N/A";} ?></b>
					  ជាភាគីអ្នកទិញដែលចាប់ពីពេលនេះតទៅហៅកាត់ថាភាគី”ខ”។
				</p>
				<p>ភាគី“ខ” បានយល់ព្រមបង់រម្លស់<b>(ថ្លៃទិញ-លក់ផ្ទះ)</b> អោយទៅភាគី“ក” ដោយស្ម័គ្រចិត្តហើយភាគី“ក” ក៏មានឯកភាពអោយ ភាគី“ខ” បង់រម្លស់ដោយស្ម័គ្រចិត្តដូចខាងក្រោម:</p><br>
			</div><br>
			
			<div class="row text-center col-lg-12 font-kh-bt">
				<p class="both"><b><u>ភាគីទាំងពីរបានព្រមព្រៀងគ្នាដូចតទៅ :</u></b></p><br>
			</div>
			
			<div class='row col-lg-12 font-kh-bt'>
				<p><b>ប្រការទី១:រយះពេល និងទឹកប្រាក់ដែលត្រូវបង់រម្លស់</b></p>
				<p>
					<?php 
						$payment = 0;
						$total_payment = 0;
						$payment = $this->erp->formatMoney($duration->payment);
						$total_payment += $payment;
					?>
					
					ចាប់ពីចុះហត្ថលេខាលើកិច្ចព្រមព្រៀងនេះភាគី “ខ” មានកាតព្វកិច្ចត្រូវបង់រម្លស់សមតុល្យនៃថ្លៃទិញផ្ទះជាទឹកប្រាក់ចំនួន
					<?php echo $this->erp->formatMoney($product->subtotal);?>( <?php echo $this->erp->numberToWordsCur($product->subtotal,"kh","");?> ដុល្លារអាមេរិក) សម្រាប់រយះពេល <?php echo round($duration->duration);?> <b>(<?php echo $duration->description;?>)</b>ឆ្នាំក្នុងអត្រាការប្រាក់<b>12%</b>	(ដប់ពីរភាគរយ)ក្នុង១ឆ្នាំ។ភាគី“ខ”ត្រូវសងតាមឥណប្រតិទានប្រចាំខែចំនួន <b><?php echo $payment?></b>លើកចំនួនទឹកប្រាក់<b><?php echo $total_payment?></b>ដុល្លារអាមេរិកដោយបង់រម្លស់ប្រាក់ដើមបូក
					នឹងការប្រាក់ ហើយនឹងត្រូវកែសំរួលនៅឥណប្រតិទានចុងក្រោយ។
				</p>
				<p><b>ប្រការទី២:ការបំពានកាតព្វកិច្ចរបស់ភាគី “ខ”</b></p>
				<ul style='list-style-type:none'>
					<li>
					<span style="float:left;height:50px;"><p><b>២.១.</b></p></span>
					<p style="word-break: break-all">ក្នុងករណីដែលភាគី ខ មិនបានបង់ប្រាក់តាមកាលកំណត់ដូចដែលមានចែងក្នុងប្រការ១ខាងលើទេនោះភាគី ខ នឹង ត្រូវមានកាតព្វកិច្ចបង់ប្រាក់ពិន័យដូចខាងក្រោម :</p>
					</li>
					<li style="margin-left: 35px;">
					<p style="word-break: break-all">• ករណីយឺតយ៉ាវ <b>០៧ ដល់៣០ថ្ងៃ</b>ភាគី “ខ”ត្រូវបង់ប្រាក់ពិន័យឲ្យភាគី“ក” ចំនួន <b>៥%</b> (ប្រាំភាគរយ)ក្នុងមួយខែនៃ  ទឹកប្រាក់ដែលត្រូវបង់ដោយគិតតាមចំនួនថ្ងៃដែលយឺត។</p>
					</li>
					<li>
						<span style="float:left;height:50px;"><p><b>២.២.</b></p></span>
						<p style="word-break: break-all">ករណីភាគី ខ ខកខានមិនបានបង់ប្រាក់តាមកាលកំណត់តាមឥណប្រតិទានរបស់ប្រាក់បង់រម្លស់មួយលើកឬច្រើន
							លើករួមជាមួយការប្រាក់និងប្រាក់ពិន័យតាមការកំណត់ក្នុងរយះពេល<b>៣១</b>ថ្ងៃកិច្ចសន្យានេះ នឹងត្រូវរំលាយ ជាស្វ័យ ប្រវត្តិ។ក្នុងករណីនេះភាគី ក មានសិទ្ធគ្រប់គ្រាន់ទទួលយកប្រាក់ទាំងអស់ដែលភាគី ខ បានបង់និងមានសិទ្ធិគ្រប់គ្រាន់
							ប្រើប្រាស់អាស្រ័យផលនិងចាត់ចែងអចលនទ្រព្យនេះ។
						</p>
					</li>
				</ul>
				<div style="text-indent: 32px;"></div>
					<p><b>ប្រការទី៣ : អំពីសិទ្ធភាគី “ខ”</b></p>
					<ul style='list-style-type:none'>
						<li>
						<span style="float:left;height:20px;"><p><b>៣.១.</b></p></span>
						<p style="word-break: break-all">
						ភាគី ខ មានសិទ្ធបង់រម្លស់ប្រាក់ដើម អោយភាគី ក ខ្លះឬបង់ទាំងអស់មុនកាលកំណត់ នៃកិច្ចព្រមព្រៀងនេះ
						ដោយពុំ​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​មានការផាកពិន័យអ្វីឡើយហើយទឹកប្រាក់ដែលត្រូវបង់ប្រចាំខែនឹងមានការប្រែប្រួលទៅតាមតារាងបង់ប្រាក់
						ដែលភាគី ក ចេញជូនជាថ្មី។
						</p>
						</li>
						<li>
							<span style="float:left;height:20px;"><p><b>៣.២.</b></p></span>
							<p style="word-break: break-all">
							បន្ទាប់ពីភាគី  ខ បានបង់ប្រាក់ថ្លៃទិញផ្ទះគ្រប់ចំនួននោះភាគី  ក នឹងរៀបចំឯកសារផ្ទេរកម្មសិទ្ធ(ប្លង់រឹង)ជូនទៅភាគី
							ខ ដែលភាគី ខ ជាអ្នកចំណាយលើសេវាកម្មរត់ការឯកសារពន្ធប្រថាប់ត្រា និងសេវាផ្សេងៗទៀត ។
							</p>
						</li>
						<li>
							<span style="float:left;height:20px;"><p><b>៣.៣.</b></p></span>
							<p style="word-break: break-all">
							ភាគី ខ នឹងមិនអនុញ្ញាតិឲ្យមានការដាក់បញ្ចាំឬការធានាផលប្រយោជន៍លើផ្ទះដែលទិញនេះឡើយលុះត្រាតែភាគី
							ខ បានបង់ប្រាក់ចប់សព្វគ្រប់ឬមានការយល់ព្រមជាលាយលក្ខអក្សរពីភាគី“ក”ជាមុន។
							</p>
						</li>
					</ul>
					<p><b>ប្រការទី៤ : អវសានប្បញ្ញត្តិ </b></p>
					<ul style='list-style-type:none'>
						<li>
							<b>៤.១.</b>កិច្ចសន្យានេះ នឹងត្រូវគ្របដណ្តប់ដោយច្បាប់នៃព្រះរាជាណាចក្រកម្ពុជា។
						</li>
						<li>
							<p style="word-break: break-all">
							<b>៤.២.</b>កិច្ចព្រមព្រៀងនេះនឹងអាចកែប្រែបានលុះត្រាតែមានការព្រមព្រៀងគ្នាជាថ្មីហើយមានសុពលភាព ចាប់ពីពេលចុះ
							ហត្ថលេខានេះតទៅ។
							</p>
						</li>
						<li>
							<span style="float:left;height:20px;"><p><b>៤.៣.</b></p></span>
							កិច្ចព្រមព្រៀងនេះធ្វើឡើងជាភាសាខ្មែរចំនួន ០២ ច្បាប់មានតម្លៃស្មើគ្នា ដែលត្រូវតម្កល់ទុកនៅភាគី“ខ” ចំនួនមួយ 
							ច្បាប់និង ភាគី  ក  ចំនួនមួយច្បាប់ ។
						</li>
						<li>
							<span style="float:left;height:20px;"><p><b>៤.៤.</b></p></span>
							<p style="word-break: break-all">
							ដោយបានអាននិងយល់ច្បាស់អំពីខ្លឹមសារទាំងមូលនៃកិច្ចសន្យានេះភាគីទាំងពីរអនុវត្តតាមកិច្ចសន្យានេះដោយផ្តិត
							ស្នាមមេដៃនៅចំពោះមុខសាក្សីដូចខាងក្រោម។
							</p>
						</li>
					</ul>
				</div>
				<div class="row col-lg-12 font-kh-bt">
					<p>
						<span class='pull-right' style="margin-right:70px;">
						រាជធានីភ្នំពេញ ថ្ងៃទី<b><?php echo $db_down_pay_date;?> </b>
						ខែ  <b><?php echo $db_down_pay_month;?> </b> 
						ឆ្នាំ​  <b><?php echo $db_down_pay_year?> </b>
						</span>
					</p>
				</div>
				<div class="row col-lg-12" style="clear:both;margin-top:10px;">
					<div class="left-div">
						<p>ស្នាមមេដៃស្តាំ</p>
						<p style="line-height:0px;">ភាគី”ក”អ្នកលក់ </p>
					</div>
					<div class="center-div">
						<p>សាក្សី </p>
					</div>
					<div class="right-div">
						<p>ស្នាមមេដៃស្តាំ </p>
						<p style="line-height:0px;">ភាគី”ខ” អ្នកទិញ </p>
					</div>
				</div>
				
				<div class='row col-lg-12 font-kh-bt' id="set_line">
					<br>
					<br>
					<p class="set_line">…………………………………………………………………………………………………………………………………………………………</p>
					<p>(មានភ្ជាប់ជូននូវតារាងបង់ប្រាក់ ក្នុង<b>ឧបសម្ព័ន្ធ ខ</b>)</p>
				</div>
		    </div>
		</div>
	</div>
</body>
</html>