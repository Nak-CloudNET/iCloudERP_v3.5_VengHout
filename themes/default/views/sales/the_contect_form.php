<!DOCTYPE html>
<html>
<head>
	<title>Contract Form Sale</title>
	<!-- <link rel="stylesheet" type="text/css" href="bootstrap/css/style.css"> -->
	<!-- <link href='https://fonts.googleapis.com/css?family=Moul' rel='stylesheet'> -->
	<!-- <link href='https://fonts.googleapis.com/css?family=Battambang' rel='stylesheet'> -->
	<meta charset="UTF-8">
	<!-- <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet"> -->
	<link href="<?php echo $assets ?>styles/theme.css" rel="stylesheet">
	<style type="text/css">
		html, body {
            height: 100%;
            background: #FFF;
			text-align: justify;
			word-break: break-all;

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
			width:25%;
			height:50px;
			text-align:center;
		}
		.center-div{
			float:left;
			width:25%;
			height:50px;
			text-align:center;
		}
		.right-div{
			float:left;
			width:25%;
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
				<h4 class="moul">ជាតិ​  សាសនា  ព្រះមហាក្សត្រ</h4><br>
				<h5 class="moul" style="padding-top:10px;">កិច្ចសន្យាទិញលក់ដីធ្លី និង ផ្ទះសម្បែង</h5><br>
			</div>
			<div class='row col-lg-12 font-kh-bt'​ style="line-height:20px;">				
				<p style="padding-left:40px;">
					ខ្ញុំបាទឈ្មោះ<b>&nbsp;<?php echo $saller->saller;?>&nbsp;</b>
					ឆ្នាំកំណើត<b>&nbsp;<?=$db_year;?>&nbsp;</b>
					មានទីលំនៅផ្ទះលេខ<b>&nbsp;<?php if($saller->address){ echo  $saller->address;}else{ echo "N/A";} ?>&nbsp;</b>
					ផ្លូវលេខ<b>&nbsp;<?php if($saller->street){ echo  $saller->street; }else{ echo "N/A";} ?>&nbsp;</b>						
					សង្កាត់<b>&nbsp;<?php if($saller->sangkat){ echo $saller->sangkat; }else{ echo "N/A";} ?>&nbsp;</b>
					ខណ្ឌ<b>&nbsp;<?php if($saller->district){ echo $saller->district;}else{ echo "N/A";} ?>&nbsp;</b>
					រាជធានីភ្នំពេញ។	
				</p>
				<?php $r = 1;
				
                    $tax_summary = array();
					if(is_array($rows)){
						foreach ($rows as $row):
						$free = lang('free');
						$product_unit = '';
						$total = 0;
						
						if($row->variant){
							$product_unit = $row->variant;
						}else{
							$product_unit = $row->uname;
						}
						$product_name_setting;
						if($setting->show_code == 0) {
							$product_name_setting = $row->product_name . ($row->variant ? ' (' . $row->variant . ')' : '');
						}else {
							if($setting->separate_code == 0) {
								$product_name_setting = $row->product_name . " (" . $row->product_code . ")" . ($row->variant ? ' (' . $row->variant . ')' : '');
							}else {
								$product_name_setting = $row->product_name . ($row->variant ? ' (' . $row->variant . ')' : '');
							}
						}
				?>
				<p>
					បានយល់ព្រមលក់ផ្ទះប្រភេទ<b>&nbsp;<?php echo $product_unit ?>&nbsp;</b>
					ដែលមានដីទទឹង<b>&nbsp;<?php if($height  == ""){echo "..........";}else{echo $height;}?>&nbsp;</b>ម៉ែត្រ
					បណ្តោយ<b>&nbsp;<?php if($width  == ""){echo "..........";}else{echo $width;}?>&nbsp;</b>ម៉ែត្រ
					ស្ថិតនៅផ្ទះលេខ <b>&nbsp;<?php if($saller->address){ echo $saller->address;}else{ echo "N/A";} ?>&nbsp;</b>
					ផ្លូវលេខ <b>&nbsp;<?php if($saller->street){ echo $saller->street;}else{ echo "N/A";} ?>&nbsp;</b>
					ភូមិ<b>&nbsp;<?=$saller->village;?>&nbsp;</b>
					សង្កាត់<b>&nbsp;<?php if($saller->sangkat){ echo $saller->sangkat; }else{ echo "N/A";} ?>&nbsp;</b>
					ខណ្ឌ<b>&nbsp;<?php if($saller->district){ echo $saller->district;}else{ echo "N/A";} ?>&nbsp;</b>
					រាជធានីភ្នំពេញ។		
				</p>
				<?php
					$r++;
						endforeach;
					}
					?>
				<p>
					អោយទៅឈ្មោះ<b>&nbsp;<?php if($customer->name_kh != ""){ 
								echo $customer->name_kh;}else{echo $customer->names;}?>&nbsp;</b>
					ភេទ<b>&nbsp;<?php echo $customer->gender; ?>&nbsp;</b>
					ឆ្នាំកំណើត<b>&nbsp;<?=$dbcus_year;?>&nbsp;</b>
					លេខអត្តសញ្ញាប័ណ្ណ<b>&nbsp;<?php if($customer->cf1){echo $customer->cf1;}else{echo "N/A";}?>&nbsp;</b>
					មានទីលំនៅផ្ទះលេខ <b>&nbsp;<?php if($customer->address){ echo $customer->address;}else{ echo "N/A";} ?>&nbsp;</b>
					ផ្លូវលេខ <b>&nbsp;<?php if($customer->street){ echo $customer->street;}else{ echo "N/A";} ?>&nbsp;</b>
					ភូមិ<b>&nbsp;<?php if($customer->village){ echo $customer->village;}else{ echo "N/A";}?>&nbsp;</b>
					សង្កាត់<b>&nbsp;<?php if($customer->sangkat){ echo $customer->sangkat; }else{ echo "N/A";} ?>&nbsp;</b>
					ខណ្ឌ<b>&nbsp;<?php if($customer->district){ echo $customer->district;}else{ echo "N/A";} ?>&nbsp;</b>
					ខេត្ត​ .ក្រុង<b>&nbsp;<?php if($customer->city){echo $customer->city;}else{ echo $customer->state; }?>&nbsp;</b>។
				</p>
				<p>
					ក្នុងតំលៃ$<b>&nbsp;<?= $row->subtotal!=0?$this->erp->formatMoney($row->subtotal):$free; 
									$total += $row->subtotal;
									?>&nbsp;</b>
					ជាអក្សរ<b>&nbsp;<?php echo $this->erp->numberToWordsCur($total,$kh='1',$cur="",$cur_h="");?>&nbsp;</b>
					ដុល្លា។	
				</p>
				<p>
					ហើយតំលៃដែលបានឯកភាពគ្នានេះ ភាគីអ្នកទិញបានយល់ព្រមបង់ប្រាក់តាមកិច្ចព្រមព្រៀងដូចខាងក្រោម​ ៖
				</p>
				<p style="padding-left:40px;">
					-  លើកទី ១ ចំនួន$<b>&nbsp;<?= (isset($inv->deposit)?$this->erp->formatDecimal($inv->deposit):0)?>&nbsp;(<?php echo $this->erp->numberToWordsCur($inv->deposit,$kh='1');?>)</b>
					នៅថ្ងៃទី<b>&nbsp;<?php $d=explode("-",$inv->date); $dt=explode(" ",$d[2]); echo "$dt[0]";?>&nbsp;</b>
					ខែ<b>&nbsp;<?php echo "$d[1]"?>&nbsp;</b>
					ឆ្នាំ​<b>&nbsp;<?php echo "$d[0]"?>&nbsp;</b>
				</p>
				<p style="padding-left:40px;">
					-  លើកទី ២ ចំនួន$<b>&nbsp;<?= $this->erp->formatDecimal($inv->down_amount); ?>&nbsp;(<?php echo $this->erp->numberToWordsCur($inv->down_amount,$kh='1');?>)</b>
					នៅថ្ងៃទី<b>&nbsp;<?php $dd=explode("-",$inv->down_date);echo "$dd[2]";?>&nbsp;</b>
					ខែ<b>&nbsp;<?php echo "$dd[1]"?>&nbsp;</b>
					ឆ្នាំ​<b>&nbsp;<?php echo "$dd[0]"?>&nbsp;</b>
				</p>
				
				<?php
                            
                            $total_principle = 0;
                            $total_interest = 0;
                            $total_payment = 0;
                            $total_alls = 0 ;
                            $total_haft = 0 ;
                            $total_insurence = 0;
                            $total_pay = 0;
                            $countrows = count($countloans);
                            $countrow  = count($countloans) /2;
                            $counter = 1;
							$no_interest = 0;
							$have_interest =0;
							$Principles_amount =0;
                            if(array($loan)) {
                                foreach($loan as $pt){
                                        $total_schedule += $pt->interest+$pt->principle;
                                        $total_prin += $pt->principle;
                                    
                                        $princ           = $this->erp->formatMoney($pt->principle);
                                        $interest        = $this->erp->formatMoney($pt->interest);                                  
                                        $overdue_amt     = (($pt->paid_amount > 0)? $pt->overdue_amount : 0);
                                        $payment         = $pt->payment + $overdue_amt;
                                        $paid            = $pt->paid_amount? $pt->paid_amount : 0;
                                        
                                        $paid_amount     = $paid + (($pt->paid_amount > 0)? $overdue_amt : 0);
                                        $balance         = $payment - $paid_amount;
                                        $balance_moeny   = $this->erp->formatMoney($pt->balance);
                                        
                                        $Principles      = $this->erp->formatMoney($pt->payment-$pt->interest);
                                        $interests       = $this->erp->formatMoney($pt->interest);
                                        
                                        $balances = (($pt->balance > 0)? $pt->balance : 0);
                                        $balances = str_replace(',', '', $this->erp->formatMoney($balances));
                                        $principle_amt = str_replace(',', '', $Principles);
                                        $loan_balance = $balances + $principle_amt;
                                        $haft_paid = 0;
                                        $insurences_paid = 0;
                                        $all_paid = 0;
                                    
                                        $Principles_amount = str_replace(',', '', $Principles);
                                        $interests_amount = str_replace(',', '', $interests);
                                        if($pt->period >= 1 && $pt->period <= $countrow){
                                            $payment = $Principles_amount + $interests_amount + $all_paid + $haft_paid + $insurences_paid;
                                        }else{
                                            $payment = $Principles_amount + $interests_amount + $all_paid;
                                        }
                                    if($pt->interest == 0)
									{
										$no_interest += $payment;
									} 
													 
									if($pt->interest > 0)
									{
										$have_interest +=$payment;   
									} 
									//echo $have_interest;	
                                }
								
							
								
                            }
							//echo "principle=". $no_interest."##"."have_interest=".$have_interest;
							
                        ?>
				
				<p style="padding-left:40px;">
					-  លើកទី​ ៣ ចំនួន$<b>&nbsp;<?=$this->erp->formatMoney($no_interest); ?>&nbsp;(<?php echo $this->erp->numberToWordsCur($no_interest,$kh='1');?>)</b>
					នៅថ្ងៃទី<b>&nbsp;<?php $dd=explode("-",$inv->date); $dt=explode(" ",$dd[2]); echo "$dt[0]";?>&nbsp;</b>
					ខែ<b>&nbsp;<?php echo "$dd[1]"?>&nbsp;</b>
					ឆ្នាំ​<b>&nbsp;<?php echo "$dd[0]"?>&nbsp;</b>
				</p>
				<p style="padding-left:40px;">
					-  លើកទី ៤ ចំនួន$<b>&nbsp;<?=$this->erp->formatMoney($have_interest); ?>&nbsp;(<?php echo $this->erp->numberToWordsCur($have_interest,$kh='1');?>)</b>
					បង់បង្គ្រប់ពេលផ្ទះសង់រួច។
				</p>
				<p>
					<b>ដើម្បីអោយមានសេចក្តីទុកចិត្តលើការអនុវត្តកិច្ចសន្យានេះភាគីទាំងពីរបានយល់ព្រមលក្ខខ័ណ្ឌដូចខាងក្រោម ៖</b>
				</p>
				<p style="padding-left:20px;">
					-  ភាគីអ្នកទិញមិនគោរពតាមកិច្ចសន្យានេះ ប្រាក់កក់ទាំងអស់នេះត្រូវទុកជាប្រយោជន៍របស់ភាគីអ្នកលក់ដោយស្វ័យប្រវត្តិ។
					ផ្ទុយទៅវិញភាគីអ្នកលក់កែប្រែមិនលក់វិញ នោះត្រូវបង់ប្រាក់ពិន័យទៅភាគីអ្នកទិញស្មើនិងប្រាក់កក់គុណនឹង២។
				</p>
				<p>
					<b>**ភាគីអ្នកលក់ មានភារកិច្ចធ្វើវិញ្ញាបនបត្រសម្គាល់ម្ចាស់អចលនវត្ថុ (ប្លង់.Map) 
					ជូនទៅភាគីអ្នកទិញ ប៉ុន្តែរាល់ការចំណាយធ្វើវិញ្ញាបនបត្រ កាត់់ឈ្មោះ និងបង់ពន្ធជូនរដ្ឋ ជាបន្ទុករបស់ភាគីអ្នកទិញ។</b>
				</p>
				<p style="padding-left:20px;">
					-  ការកែប្រែផ្ទះដោយគ្មានការឯកភាពនិងខុសពីស្តង់ដាក្រុមហ៊ុននោះភាគីអ្នកទិញត្រូវទទួលខុសត្រូវចំពោះមុខច្បាប់។
				</p>
				<p style="padding-left:20px;">
					-  ជាការធានាដល់សុក្រិតភាពនៃកិច្ចសន្យានេះភាគីទាំងពីរបានព្រមព្រៀងគ្នាផ្តិតស្នាមមេដៃស្តាំ ដើម្បីទុកជាភស្តុតាង ក្នុងការទទួល ខុសត្រូវចំពោះមុខច្បាប់។
				</p><br>
				<div class="row col-lg-12 font-kh-bt">
					<p>
						<span class='pull-right'>
						រាជធានីភ្នំពេញ ថ្ងៃទី<b>&nbsp;<?php echo $date_day;?>&nbsp;</b>
						ខែ  <b>&nbsp;<?php echo $month_kh;?>&nbsp;</b> 
						ឆ្នាំ​  <b>&nbsp;<?php echo $date_year;?>&nbsp;</b>
						</span>
					</p>
				</div>
				<div class="row col-lg-12" style="clear:both;margin-top:10px;">
					<div class="left-div">
						<p>ស្នាមមេដៃអ្នកទិញ</p>
					</div>
					<div class="center-div">
						<p>សាក្សី </p>
					</div>
					<div class="center-div">
						<p>សាក្សី </p>
					</div>
					<div class="right-div">
						<p>ស្នាមមេដៃអ្នកលក់ </p>
					</div>
				</div>
				
				<div class='row col-lg-12 font-kh-bt' id="set_line">
					<br>
					<br>
					<div class="col-lg-5" style="font-size:10px;">
						<p class="pull-left">លេខទូរស័ព្ទអ្នកទិញ :<b>..................................</b></p>
					</div>
					<div class="col-lg-7" style="font-size:10px;">
						<p class="pull-right">លេខទូរស័ព្ទអ្នកទិញ &nbsp;:&nbsp;<b>023 729 888   077 581 111  077631 111</b></p>
					</div>
				</div>
		    </div>
		</div>
	</div>
</body>
</html>