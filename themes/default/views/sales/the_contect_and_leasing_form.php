<!DOCTYPE html>
<html>
<head>
	<title>Contract and Leasing Form Sale</title>
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
				<h5 class="moul" style="padding-top:10px;">កិច្ចសន្យា បង់រំលោះ​ ដីធ្លី និង ផ្ទះសម្បែង</h5><br>
			</div>
			<div class='row col-lg-12 font-kh-bt'​ style="line-height:20px;">				
				<p>
					ភាគី​ "ក" លោក<b>&nbsp;<?php echo $saller->saller;?>&nbsp;</b>
					ឆ្នាំកំណើត<b>&nbsp;<?=$db_year;?>&nbsp;</b>
					មានទីលំនៅបច្ចុប្បន្នផ្ទះលេខ<b>&nbsp;<?php if($saller->address){ echo  $saller->address;}else{ echo "N/A";} ?>&nbsp;</b>
					ផ្លូវលេខ<b>&nbsp;<?php if($saller->street){ echo  $saller->street; }else{ echo "N/A";} ?>&nbsp;</b>	
					សង្កាត់<b>&nbsp;<?php if($saller->sangkat){ echo $saller->sangkat; }else{ echo "N/A";} ?>&nbsp;</b>							
				</p>
				<p style="padding-left:40px;">
					ខណ្ឌ<b>&nbsp;<?php if($saller->district){ echo $saller->district;}else{ echo "N/A";} ?>&nbsp;</b>
					រាជធានីភ្នំពេញ ដែលជាម្ចាស់ បុរីញូវថោន ជាអ្នកលក់។	
				</p>
				<p>
					ភាគី​ "ខ"ឈ្មោះ<b>&nbsp;<?php if($customer->name_kh != ""){ 
								echo $customer->name_kh;}else{echo $customer->name;}?>&nbsp;</b>
					ភេទ<b>&nbsp;<?php echo $customer->gender; ?>&nbsp;</b>
					ឆ្នាំកំណើត<b>&nbsp;<?=$dbcus_year;?>&nbsp;</b>
					លេខ<b>&nbsp;<?php echo $customer->phone; ?>&nbsp;</b>
				</p>
				<p style="padding-left:40px;">
					ឈ្មោះ<b>&nbsp;<?php if($customer->name_kh != ""){ 
								echo $customer->name_kh;}else{echo $customer->name;}?>&nbsp;</b>
					ភេទ<b>&nbsp;<?php echo $customer->gender; ?>&nbsp;</b>
					ឆ្នាំកំណើត<b>&nbsp;<?=$dbcus_year;?>&nbsp;</b>
					លេខ<b>&nbsp;<?php echo $customer->phone; ?>&nbsp;</b>
					មានទីលំនៅផ្ទះលេខ <b>&nbsp;<?php if($customer->address){ echo $customer->address;}else{ echo "N/A";} ?>&nbsp;</b>
					ផ្លូវលេខ <b>&nbsp;<?php if($customer->street){ echo $customer->street;}else{ echo "N/A";} ?>&nbsp;</b>
					ភូមិ<b>&nbsp;<?php if($customer->village){ echo $customer->village;}else{ echo "N/A";}?>&nbsp;</b>
					ក្រុម<b>..................</b>
					ឃុំ /សង្កាត់<b>&nbsp;<?php if($customer->sangkat){ echo $customer->sangkat; }else{ echo "N/A";} ?>&nbsp;</b>
					ស្រុក /ខណ្ឌ<b>&nbsp;<?php if($customer->district){ echo $customer->district;}else{ echo "N/A";} ?>&nbsp;</b>
					ខេត្ត​ /ក្រុង<b>&nbsp;<?php if($customer->city){echo $customer->city;}else{ echo $customer->state; }?>&nbsp;</b>
					ជាអ្នកទិញ។
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
				<p style="padding-left:40px;">
					ភាគី​ "ក" បានយល់ព្រមលក់ផ្ទះប្រភេទ<b>&nbsp;<?php echo $product_unit ?>&nbsp;</b>
					ឆ្នាំកំណើត<b>&nbsp;<?=$db_year;?>&nbsp;</b>
					ផ្លូវ<b>&nbsp;<?php if($saller->street){ echo  $saller->street; }else{ echo "N/A";} ?>&nbsp;</b>	
					ស្ថិតនៅភូមិ<b>&nbsp;<?=$saller->village;?></b>		
					សង្កាត់<b>&nbsp;<?php if($saller->sangkat){ echo $saller->sangkat; }else{ echo "N/A";} ?>&nbsp;</b>
					ខណ្ឌ<b>&nbsp;<?php if($saller->district){ echo $saller->district;}else{ echo "N/A";} ?>&nbsp;</b>
					ក្រុង<b>&nbsp;<?php if($saller->city){echo $saller->city;}else{ echo $saller->state; }?>&nbsp;</b>
					
					ទៅអោយភាគី​ "ខ" ក្នុងតំលៃសរុបចំនួន<b>&nbsp;<?= $row->subtotal!=0?$this->erp->formatMoney($row->subtotal):$free; 
									$total += $row->subtotal;
									?>(<?php echo $this->erp->numberToWordsCur($total,$kh='1');?>)&nbsp;</b>។
				</p>
				<?php
					$r++;
						endforeach;
					}
					?>
				<p class="text-center">
					<b>ភាគីទាំងពីរបានព្រមព្រៀងចុះកិច្ចសន្យាបង់ប្រាក់ ដូចខាងក្រោម ៖</b>
				</p>
				<p>
					ប្រការ​ 1 ៖ ភាគី​ "ខ"​ បានបង់ប្រាក់ជូនភាគី​ "ក"
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
							$dates="";
							$status=0;
						
                            if(array($loan)) {
                                foreach($loan as $pt){
									//$this->erp->print_arrays($pt);
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
										if($status==0){
											if($pt->interest > 0)
											{  
												$dates=$this->erp->hrsd($pt->dateline);
												$status=1;
											} 
											
										}	
										//echo $have_interest;	
                                }							
                            }
							
                        ?>
				
				<p style="padding-left:40px;">
					-  លើកទី ១ ចំនួន$<b>&nbsp;<?= $this->erp->formatMoney($total1=$inv->deposit+$inv->down_amount+$no_interest);?>&nbsp;(&nbsp;<?php echo $this->erp->numberToWordsCur($total1,$kh='1');?>&nbsp;)</b>
					នៅថ្ងៃទី<b><?php echo $this->erp->hrsd($inv->date)?></b>
					។
				</p>
				<p style="padding-left:40px;">
					- ប្រាក់នៅសល់ចំនួន$<b>&nbsp;<?=$this->erp->formatMoney($totalrest=$inv->grand_total - ($inv->deposit+$order_down->down_amount+$order_down->principle_amount)) ?>&nbsp;(&nbsp;<?php echo $this->erp->numberToWordsCur($totalrest,$kh='1');?>&nbsp;)</b>
				</p>
				<p style="padding-left:40px;">
					-  ភាគី​ "ខ"​យល់ព្រមបង់រំលស់ក្នុងរយះពេល<b>&nbsp;<?php echo $duration->description;?>&nbsp;</b>
					ឆ្នាំ​ស្មើនឹង<b>&nbsp; <?php echo ((($frequency->frequency*12)*($frequency->term/365))/30); ?>&nbsp;</b>
					ខែ មានចំនួនUS $<b>&nbsp;<?= $this->erp->formatMoney($have_interest);?>&nbsp;(&nbsp;<?php echo $this->erp->numberToWordsCur($have_interest,$kh='1');?>&nbsp;)</b>
					គិតចាប់ពីថ្ងៃទី<b><?php $dl=explode("/",$dates);echo "$dl[0]";?></b>
					ខែ  <b><?php echo "$dl[1]";?></b> 
					ឆ្នាំ​  <b><?php echo "$dl[2]";?></b>
					តាមតារាងបង់ប្រាក់រំសល់ដែលភ្ជាប់មកជាមួយ។
				</p>
				<p>
					ប្រការ​ 2 ៖ ក្នុងករណីបង់ប្រាក់យឺតយ៉ាវ ភាគី​ "ខ"​ ត្រួវបង់ប្រាក់ពិន័យជូនភាគី​ "ក"ដូចខាងក្រោម ៖
				</p>
				<p style="padding-left:40px;">
					-  បើលើសកំណត់​ 5ថ្ងៃ ដល់ 30ថ្ងៃ ពិន័យ 5% បើលើសកំណត់​ 30ថ្ងៃ ដល់ 90ថ្ងៃ ពិន័យ​ 10%​​ នៃទឹកប្រាក់មិនទាន់បានបង់ (ប្រចាំខែ)។
				</p>
				<p style="padding-left:40px;">
					-  បើលើសកំណត់​ 90ថ្ងៃ  ភាគី​ "ខ"​ បានព្រមអោយភាគី​ "ក" យកផ្ទះខាងលើ ទៅលក់ឡៃឡុងដើម្បីយកប្រាក់មកសងភាគី​ "ក" វិញ។
				</p>
				<p>
					ប្រការ​ 3 ៖ ក្រោយរយះពេលណាមួយដែល ភាគី​ "ខ"​ បានបង់ប្រាក់ថ្លៃទិញផ្ទះគ្រប់ចំនួន នោះទើបភាគី​ "ក" ប្រគល់ វិញ្ញាបនប័ត្រសំគាល់ម្ចាស់អចលនវត្ថុ
				</p>
				<p style="padding-left:40px;">
					 (ប្លង់ LMap) ជូនទៅភាគី​ ​​"ខ" រាល់ការចំណាយកាត់ឈ្មោះ និង​ បង់ពន្ធជូនរដ្ឋជាបន្ទុករបស់ភាគី​ ​"ខ"។ប៉ុន្តែភាគី "ខ" មានសិទ្ធរស់នៅលើផ្ទះចាប់ពីថ្ងៃសាងសង់រួច។
				</p>
				<p>
					ប្រការ​ 4 ៖ ភាគី​ "ខ"​ សន្យាគោរពយ៉ាងម៉ឺងម៉ាត់រាល់ប្រការខាងលើ ក្នុងករណីមានការអនុវត្តន៍ផ្ទុយ ឬ រំលោភលើលក្ខខ័ណ្ឌណាមួយនៃកិច្ចសន្យានេះភាគី​ណាល្មើសត្រូវទទួលខុសត្រូវ ចំពោះមុខច្បាប់ជាធរមាន។
				</p>
				<br>
				<div class="row col-lg-12 font-kh-bt">
					<p>
						<span class='pull-right'>
						រាជធានីភ្នំពេញ ថ្ងៃទី<b>&nbsp;<?php echo $date_day;?>&nbsp;</b>
						ខែ  <b>&nbsp;<?php echo $month_kh;?>&nbsp;</b> 
						ឆ្នាំ​  <b>&nbsp;<?php echo $date_year;?>&nbsp;</b>
						</span>
					</p>
				</div>
				<div class="row col-lg-12" style="clear:both;margin-top:10px;font-size:11px;"">
					<div class="left-div">
						<p>ស្នាមមេដៃស្តាំភាគី "ខ"(១)</p>
					</div>
					<div class="center-div">
						<p>ភាគី "ខ"(២)</p>
					</div>
					<div class="center-div">
						<p>សាក្សី </p>
					</div>
					<div class="right-div">
						<p>ស្នាមមេដៃស្តាំភាគី "ក" (អ្នកតំណាង)</p>
					</div>
				</div>
				
				<div class='row col-lg-12 font-kh-bt' id="set_line">
					<br>
					<br>
					<div style="font-size:10px;">
						<p>អាស័យដ្ឋាន ៖ ភូមិអង្គ សង្កាត់ចោមចៅ ខ័ណ្ឌពោធិសែនជ័យ​ រាជធានីភ្នំពេញ</p>
					</div>
					<div style="font-size:10px;">
						<p>លេខទូរស័ព្ទទំនាក់ទំនង ៖ 023 729 888 / 077 581 111 / 077 631 111</p>
					</div>
					
				</div>
		    </div>
		</div>
	</div>
</body>
</html>