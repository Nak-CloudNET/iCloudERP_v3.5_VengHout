<?php 
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link href="<?php echo $assets ?>styles/theme.css" rel="stylesheet">
    <style type="text/css">
        html, body {
            height: 100%;
            background: #FFF;
			text-align: justify;
			word-break: break-all;

        }
        body:before, body:after {
            display: none !important;
			 
        }
        .btn {
            border-radius: 0 !important;
            margin-right: 10px;
        }
		.moul{
		  font-family: Khmer OS Muol;
		}
		p,li{
			font-family: Khmer OS Battambang;
			line-height: 1.8;
			font-size: 13px;
		   
		}
		.foot{
			 font-family: Khmer OS Battambang;
		}
		li{
		  text-indent: -2em;
		}
		@page {
			size: A4;
			margin: 50px;
		}
		@media print{
			.set_line{
				text-align:center !important;
			}
			
		}
		.container .border{
			width: 21cm;
			margin: 0px auto;
			box-shadow: 0 0 5px rgba(0, 0, 0, 0.5);
			font-family: Khmer OS Battambang;
			font-size: 12px;
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
<!-- <input type="button" value="Print Div" onclick="PrintElem('#mydiv')" /> -->

	<div class='container'>
			<div class="col-lg-8 ">
				<div class="col-lg-12 border">
					<div class="col-lg-12">
						<button class="pull-right no-print" id="print_receipt" onclick="window.print();"><?= lang("Print"); ?>&nbsp;<i class="fa fa-print"></i></button>
					</div>
					<div class='row text-center  col-lg-12'>
						<h4 class='moul'>ព្រះរាជាណាចក្រកម្ពុជា</h4>
						<h4 class='moul'>ជាតិ​  សាសនា  ព្រះមហាក្សត្រ</h4>
						<br>
						<p class='moul'>កិច្ចសន្យាលក់-ទិញផ្ទះ</p>
						<p>លេខTF0: <?php echo $inv->reference_no; ?></p>
					</div>
					<div class='row col-lg-12'>
						<p>
							<span class='moul'>កិច្ចសន្យាលក់-ទិញផ្ទះ</span>នេះត្រូវបានធ្វើនៅរាធានីភ្នំពេញថ្ងៃទី 
							<b><?php echo $date_day;?></b>
							ខែ <b><?php echo $month_kh;?></b>
							ឆ្នាំ <b><?php echo $date_year;?></b> រវាង:
						</p>
						<br>
						<p>
							<b>-អ្នកលក់ :  លោក/លោកស្រី</b> <b><?php echo $saller->saller;?></b>
							ភេទ <b><?=$saller->gender;?></b> 
							កើតនៅថ្ងៃទី <b><?=$db_date;?></b> 
							ខែ <b><?=$db_month;?></b> 
							ឆ្នាំ <b><?=$db_year;?></b>
							សញ្ជាតិ <b><?php if($saller->nationality_kh){ 
								echo $saller->nationality_kh; }else{ echo $saller->nationality;} ?></b> 
							កាន់អត្តសញ្ញាណប័ណ្ណលេខ <b><?=$saller->identify?></b>
							ជាម្ចាស់ <b>	<?php if($saller->company_kh){ 
								echo $saller->company_kh;}else{echo $saller->company;} ?></b>
							គំរោង <b><?php if($saller->company_kh){ echo $saller->company_kh;}else{ 
								echo $saller->company;} ?></b>
							មានអាស័យដ្ឋាននៅ <?php if($saller->village){?>
							ភូមិ  <?=$saller->village;}?> <?php if($saller->sangkat){?> 
							សង្កាត់  <?=$saller->sangkat;}?> <?php if($saller->district){?>
							ខ័ណ្ឌ <?=$saller->district;}?> <?php if($saller->country){?>
							ក្រុង<?=$saller->country;}?> 
							ជាភាគីអ្នកលក់ ចាប់ពីពេលនេះតទៅហៅថាភាគី<b>”ក”និង</b>
						</p><br>
						<p>
							<b>- អ្នកទិញ: លោក/លោកស្រី <?php if($customer->name_kh != ""){ 
								echo $customer->name_kh;}else{echo $customer->name;}?></b>
							ភេទ <b> <?php echo $customer->gender; ?></b> 
							កើតនៅថ្ងៃទី <b> <?=$dbcus_date;?></b> 
							ខែ <b> <?=$dbcus_month;?></b> 
							ឆ្នាំ <b> <?=$dbcus_year;?></b> 
							សញ្ជាតិ <b> ខ្មែរ </b> កាន់អត្តសញ្ញាណប័ណ្ណលេខ 
							<b><?php if($customer->cf1){echo $customer->cf1;}else{echo "N/A";}?></b>  
							ចុះថ្ងៃទី <b>	<?php echo $date_day;?></b>
							ខែ <b> <?php echo $month_kh;?></b> 
							ឆ្នាំ <b> <?php echo $date_year;?></b> 
							<b> និងលោក/លោកស្រី  <?=($jl_data->name!=""?$jl_data->name:"......................")?></b>   
							ភេទ <b><?=($jl_data->gender!=""?$jl_data->gender:"...........")?> </b>  
							កើតនៅថ្ងៃទី <b><?=$jl_date;?></b> 
							ខែ  <b><?=$jl_month;?> </b>
							ឆ្នាំ​ <b> <?=$jl_year;?> </b> 
							សញ្ជាតិខ្មែរ  កាន់អត្តសញ្ញាណប័ណ្ណលេខ  <b><?=($jl_data->identify_card!=""?$jl_data->identify_card:"...........")?></b>
							ចុះថ្ងៃទី <b><?php if($customer->identify_date){ echo date("d/m/Y",strtotime($customer->identify_date));}else{ echo " .........................";}?></b> មានអាស័យដ្ឋានរួមនៅ ផ្ទះលេខ 
							<b><?php if($customer->address){ echo $customer->address;}else{ echo "N/A";} ?></b> 
							ផ្លូវ  <b><?php if($customer->street){ echo $customer->street;}else{ echo "N/A";} ?></b> 
							ភូមិ <b><?php if($customer->village){ echo $customer->village;}else{ echo "N/A";}?></b>   សង្កាត់ <b><?php if($customer->sangkat){ echo $customer->sangkat; }else{ echo "N/A";} ?></b> ខណ្ឌ  <b><?php if($customer->district){ echo $customer->district;}else{ echo "N/A";} ?></b> ក្រុង <b><?php if($customer->city){echo $customer->city;}else{ echo $customer->state; }?></b>  ទូរស័ព្ទលេខ <b><?php if($customer->phone){ echo $customer->phone; }else{ echo "N/A";} ?></b>  ជាភាគីអ្នកទិញដែលចាប់ពីពេលនេះតទៅហៅកាត់ថាភាគី<b>”ខ” </b>។
						</p>
					</div>
					<div class="row text-center col-lg-12">
						<p><u>ភាគីទាំងពីរបានព្រមព្រៀងគ្នាផ្តិតមេដៃលើកិច្ចសន្យានេះតាមខ្លឹមសារ និងលក្ខខ័ណ្ឌដូចតទៅ :</u></p><br>
					</div>
					<div class='row col-lg-12'>
						<p><span class='moul'>១.ការទិញ-លក់ផ្ទះ :</span></p>
						<p style="padding-left: 20px;">ភាគី<b>” ក” </b>
							យល់ព្រមលក់ផ្ទះប្រភេទ <b><?php if($product->cf1 != ''){echo $product->cf1;}else{echo "…………";}?></b> 
							ទំហំផ្ទះ<b> <?php if($product->cf5 != ''){echo 		$product->cf5;}else{echo "…………";}?> </b>
							កំពស់ <b><?=$height ?></b>(m) 
							ផ្ទះលេខ <b><?php 	if($product->cf3 != ''){ echo $product->cf3; }else{echo "…………";}?></b>
							<label style="font-weight:normal;">
								ផ្លូវលេខ<b> <?php if($product->cf4 !=""){ echo $product->cf4;}else{echo "…………";} ?> ឌឹផ្លរ៉ា</b>
								1(6<b>A</b><sub>4</sub>) ដែលមានទីតាំងស្ថិតនៅ សង្កាត់បាក់ខែង ខ័ណ្ឌជ្រោយចង្វារ រាជធានីភ្នំពេញ ចាប់ពីពេលនេះតទៅហៅថា” 
								<b>អចលនវត្ថុ”</b>ទៅឲ្យភាគី<b>	”ខ” </b>” ក្នុង
								<b>តំលៃលក់សរុប USD <?=$this->erp->formatMoney($product->subtotal);?></b> ( ដុល្លារអាមេរិក)។
							</label>
						</p>

						<p><span class='moul'>២. ដំណាក់នៃការបង់ប្រាក់  :</span></p>
						<p style="padding-left: 20px;"> (តារាងបង់ប្រាក់មានភ្ជាប់នៅក្នុងឧបសម័្ពន<b>”ក”</b>)
							ក្រោយពីភាគី<b>”ខ”</b> បានបង់ប្រាក់គ្រប់ចំនួន ទើបភាគី<b>”ក”</b> រៀបចំបែបបទផ្ទេរសិទ្ទិកាន់កាប់ផ្ទះជូនភាគី”ខ”។
						</p>
						<p><span class='moul'>៣. ការបំពានកាតព្វកិច្ចបង់ប្រាក់ :</span></p>
						<p style="padding-left: 20px;">
							ក្នុងករណីភាគី<b>”ខ”</b> មិនបានបង់ប្រាក់តាមការកំណត់ក្នុងឧបសម្ព័នខាងលើក្នុងប្រការ២ នោះអ្នកទិញត្រូវបង់ប្រាក់ពិន័យចំនួន ៥(ប្រាំ)ភាគរយ ក្នុងមួយខែនៃចំនួនទឹកប្រាក់ត្រូវបង់។ ករណីភាគី<b>”ខ”</b> ខកខានមិនបានបង់ប្រាក់លើសពី៣០(សាមសិប)ថ្ងៃ នោះទឹកប្រាក់ដែលភាគី
							<b>”ខ”</b> បានបង់ទាំងអស់ នឹងត្រូវបានមកជាកម្មសិទ្ធិអ្នកលក់ ហើយកិច្ចសន្យានេះ នឹងត្រូវរំលាយចោល ដោយភាគី<b>”ខ”</b> គ្មានសិទ្ធិតវ៉ាឡើយ។ ក្នុងករណីនេះភាគី<b>”ក”</b>មានសិទ្ធិប្រើប្រាស់អាស្រ័យផល រឺលក់ រឺចាត់ចែងអចលនវត្ថុនេះ ។
						</p><br>

						<p style="padding-left: 20px;">
							<b>ករណី</b>ដែលភាគី<b>”ក”</b> កែប្រែមិនលក់វិញ នោះនឹងត្រូវប្រគល់ប្រាក់ទៅអោយភាគីអ្នកទិញវិញស្មើនឹងប្រាក់ដែលបានទទួលគុណនឹង ២ ។
						</p>
						<p><span class='moul'>៤. ករណីកិច្ច និង កិច្ចធានារបស់ភាគី: </span></p>
						<ul style='list-style-type:none'>
							<li><b>កាតព្វកិច្ចភាគី”ក”</b></li><br>
							<li><b>៤.១ </b>ភាគី<b>”ក”</b> ធានាថាខ្លួនមានសិទ្ធិស្របច្បាប់ក្នុងការចាត់ចែងអចលនវត្ថុតែម្នាក់គត់ នឹងពុំមានបុគ្គល រឺ អង្គភាពណាមួយមានផល   ប្រយោជន៍លើអចលនវត្ថុនេះឡើយ ។</li><br>
							<li><b>៤.២ </b>ភាគី<b>”ក”</b> មានភារះកិច្ចធ្វើប័ណ្ណកម្មសិទ្ធិ(<b>ប្លង់រឹង</b>)ជូនភាគី<b>”ខ”ប៉ុន្តែរាល់ការចំណាយលើការផ្ទេរកម្មសិទ្ធិនិងបង់ពន្ធជាបន្ទុករបស់ភាគី”ខ”</b>។</li><br>
							<li><b>៤.៣ </b>ភាគី<b>”ក”</b> សន្យានឹងបញ្ចប់ការសាងសង់អោយរួចរាល់ក្នុងរយៈពេល <b>20(ម្ភៃ)</b>ខែ គិតចាប់ពីថ្ងៃចុះកិច្ចសន្យានេះលើកលែងតែអ្នក ទិញមិនបានបង់ប្រាក់តាមកាលកំណត់ ឬករណីប្រធានសក្តិដែលអាចបណ្តាលអោយមានការរាំងស្ទះដល់ដំណើរការសាងសង់។</li><br>
							<li><b>៤.៤ </b> ការយឺតយ៉ាវលើសពី ១(មួយ) ខែឡើងទៅ ដូចនេះអ្នកលក់ត្រូវសងការខូចខាតចំនួន <b>US$ 300</b> (បីរយ) ដុល្លារអាមេរិកគត់ក្នុង          0១(មួយ)ខែដោយគិតចាប់ពីថ្ងៃផុតកំណត់នៅចំនុច ៤.៣។ ផ្ទុយមកវិញប្រសិនការយឺតយ៉ាវបណ្តាលមកពីអ្នកទិញ មានការកែប្រែ សំណង់គ្រប់រូបភាព រឺអ្នកទិញមិនបានបង់ប្រាក់តាមកាលកំណត់ រឺយឺតយ៉ាវក្នុងករណីប្រធានសក្តិដែលអាចបណ្តាលអោយមានការ រាំងស្ទះដល់ដំណើរការសាងសង់នោះអ្នកលក់មិនផ្តល់សំណងនោះឡើយ។</li><br>
							<li><b>កាតព្វកិច្ចភាគី”ខ”</b></li><br>
							<li><b>៤.៥ </b> រាល់ការផ្ទេរម្ចាស់កម្មសិទ្ធិពីភាគី”ខ” ទៅតតីយជនណាមួយនោះភាគី”ខ” ត្រូវបង់ថ្លៃសេវារដ្ឋបាលសេវារៀបចំឯកសារឡើងវិញចំនួន 1%(មួយភាគរយ)ហើយនឹងត្រូវមានការយល់ព្រមពីភាគី”ក” ជាមុនសិន។</li><br>
							<li><b>៤.៦ </b> ភាគី<b>”ខ”</b> មិនអាចសាងសង់សំណង់បន្ថែម ឬ កែហេដ្ឋារចនាសម្ព័ន្ធខាងក្រៅបានឡើយ។ ករណីនេះនឹងត្រូវយកមកអនុវត្តផងដែរ ចំពោះអ្នកទិញបន្ត នឹងអ្នកទទួលសិទ្ធិបន្តពីភាគីអ្នកទិញជាបន្តបន្ទាប់រៀងរហូត។</li><br>
							<li><b>៤.៧ </b> ភាគី<b>”ខ”</b>ត្រូវបង់ថ្លៃសេវាថែទាំសួនច្បារ ភ្លើងបំភ្លឺផ្លូវ ប្រចាំខែជារៀងរាល់ខែ នៅពេលភាគី<b>”ខ”</b> បានទទួលផ្ទះជាស្ថាពរ ទោះជាអ្នកទិញ ស្នាក់ នៅ រឺមិនស្នាក់នៅក៏ដោយ។ តំលៃសេវាសំរាប់ប្រភេទ <b>វីឡាឃ្វីន US</b>$50 <b>វីឡាភ្លោះ US</b>$25 <b>វីឡាកូនកាត់ និងហ្សបហ៏សS</b>$20
								<b>វីឡាកូនកាត់(ខ្នាតតូច)US</b>$15តំលៃនេះនឹងអាចប្រែប្រួលផ្អែកលើការចំណាយជាក់ស្តែង។ប្រាក់ថ្លៃសេវាដែលភាគី<b>”ខ”</b>បានបង់នេះមិន សំរាប់ធានារ៉ាប់រង លើការបាត់បង់នូវទ្រព្យសម្បត្តិរបស់ភាគី<b>”ខ”</b> (ម្ចាស់ផ្ទះ)ឡើយ។
							</li><br>
							<li><b>៤.៨ </b> ភាគី<b>”ខ”</b> យល់ព្រមបង់ថ្លៃប្រើប្រាស់ទឹកស្អាត លូទឹក អគ្គិសនីដោយខ្លួនឯងជាមួយភ្នាក់ងារ រដ្ឋាករទឹក និងភ្នាក់ងារអគ្គិសនី និងអង្គភាពលូទឹក ដែលបណ្តាញនេះភាគី<b>”ក”</b> បានធ្វើការតភ្ជាប់រួចហើយ។</li><br>
							<li><b>៤.៩ </b> ភាគី<b>”ខ”</b> មិនអាចកែប្រែទំរង់ផ្ទះផ្នែកខាងក្រៅបានឡើយ លុះត្រាតែមានការឯកភាពជាលាយលក័្ខអក្សរពី គម្រោង ឌឹផ្លរ៉ា ជាមុនសិន។</li>
							<li><b>បញ្ជាក់:</b> ផ្ទៃការ៉ូខាងក្រៅផ្ទះនីមួយៗ អ្នកលក់មានសិទ្ធិប្រើប្រាស់គ្រប់ពេលវេលាតាមតំរូវការដូចជា: ការរៀបបណ្តាញលូ ទឹកភ្លើង ។ល។ ដោយរៀបចំអោយបានល្អវិញ ក្រោយពីប្រើប្រាស់រួច។</li>
						</ul><br>
						<p><span class='moul'>៥. ច្បាប់គ្រប់គ្រង និងដោះស្រាយវិវាទ</span></p>
						<p style="padding-left: 20px;">ក្នុងករណីមានវិវាទកើតឡើងដោយសារការអនុវត្តកិច្ចសន្យានេះ ភាគីទាំងពីរបានព្រមព្រៀងគ្នាដោះស្រាយដោយសន្តិវិធី ក្រៅប្រព័ន្ធតុលាការ ប៉ុន្តែប្រសិនបើការដោះស្រាយនេះ មិនទទួលបានជោគជ័យនោះ ភាគីទាំងពីរយល់ព្រមដាក់ជម្លោះដែលកើតចេញពីកិច្ចសន្យានេះជូនទៅ តុលាការដែលមានសមត្ថកិច្ចនៅក្នុងព្រះរាជាណាចក្រកម្ពុជា។</p>
						<p><span class='moul'>៦.សុពលភាពកិច្ចសន្យា</span></p>
						<p style="padding-left: 20px;">ភាគីទាំងពីរបានអានយល់អំពីខ្លឹមសារ និងយល់ព្រមគោរពតាមកាតព្វកិច្ចនៃកិច្ចសន្យានេះទាំងស្រុង។ រាល់កិច្ចព្រមព្រៀងមុនៗដោយមាត់ ទទេក្តី រឺលាយលក័្ខណ៏អក្សរក្តី ដែលផ្ទុយនឹងកិច្ចសន្យានេះ ត្រូវទុកជាមោឃ:។ ការកែប្រែកិច្ចសន្យានេះ មិនត្រូវមានប្រសិទ្ធិភាពឡើយ លើកលែងតែធ្វើឡើងជាលាយលក័្ខណ៍អក្សរ ហើយចុះហត្ថលេខាដោយភាគីទាំងពីរ។</p>
						<p style="padding-left: 20px;">កិច្ចសន្យានេះធ្វើជាភាសាខ្មែរចំនួន 0២ច្បាប់ដោយប្រគល់ឲ្យភាគី “ក” ចំនួន០១ច្បាប់និងភាគី “ខ” ចំនួន0១ច្បាប់។ ភាគីទាំងពីរនៃ កិច្ចសន្យានេះ បានយល់ព្រមផ្តិតមេដៃស្តាំជាភស្តុងតាង។ កិច្ចសន្យានេះមានអានុភាពគតិយុត្តចាប់ពីកាលបរិច្ឆេទខាងលើ។</p>
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
					
					<div class='row col-lg-12'>
						<br/>
						<p class="set_line" style="text-align:center;"><?php echo "………………………………………………………………………………………………………………………………………………………………";?></p>
						<!--<p>………………………………………………………………………………………………………………………………………………………………</p>-->
						<p>(ភ្ជាប់តារាងបង់ប្រាក់ក្នុង ឧបសម្ព័ន ក)</p>
					</div>
				</div>
            </div>
	</div>

</body>
</html>