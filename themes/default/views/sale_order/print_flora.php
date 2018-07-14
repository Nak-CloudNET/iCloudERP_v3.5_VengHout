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
			font-size: 12px;
			color: #2b4666;
			line-height: 1.5;
			
		}

		/*p{
    	text-align:justify;
		}
		p:after {
		    content: "";
		    display: inline-block;
		    width: 100%;    
		}*/
		
		@page {
			size: A4;
			margin: 50px;
		}
		@media print{
			#ftext{
				font-size: 12px !important;
				color: #2B4666 !important;
			}
			p{
				line-height: 20px !important;
			}	
			
		}
		.foot{
			font-family: Khmer OS Battambang;
		}
		li{
		  text-indent: -2em;
		  color:black !important;
		}
		input[type=checkbox]{
			 font-size:15px;
		}
		
		.container {
			width: 21cm;
			margin: 20px auto;
			box-shadow: 0 0 5px rgba(0, 0, 0, 0.5);
		}
		
		tr, td{
			font-family: Khmer OS Battambang;
			font-size: 12px;
			line-height: 25px !important;
			color:black !important;
		}
		
		hr{
			border-bottom: 1px dotted;	
			margin-bottom:0;
			margin-top:10px;
		}
		p{
			line-height:25px !important;
			color:black !important;
		}
    </style>
</head>

<body>
<!-- <input type="button" value="Print Div" onclick="PrintElem('#mydiv')" /> -->
		<div class="container">
			<div class="row col-lg-12 ">
				<div class="col-lg-12">
					<button class="pull-right no-print" id="print_receipt" onclick="window.print();"><?php echo  lang("Print"); ?>&nbsp;<i class="fa fa-print"></i></button>
				</div>
				<div class="col-lg-12">
					<h4 class='text-center moul'>
						<b>ពាក្យស្នើរសុំទិញ-លក់ផ្ទះ</b>
					</h4><br>
				</div>
				<div width="100%">
						<p>ធ្វើនៅថ្ងៃទី <b><?php echo $date_day ?> </b>ខែ <b><?php echo $date_month ?></b> ឆ្នាំ <b><?php echo $date_year ?></b></p>
				</div>
				
					<div class='row col-lg-12 font-kh-bt'>	
						<p>
								១. អ្នកទិញ : លោក/លោកស្រី<b>&nbsp;<?php
									if($customer->name_kh || $customer->name){
										if($customer->name_kh){
										echo $customer->name_kh;}else{echo $customer->name;}
									}else{
										echo "...................";
									}
								?>&nbsp;</b>
								អត្ត/ប័ណ្ណ<b>&nbsp;<?php if($customer->cf1 == ""){
										echo "...................";
									}else{
										echo $customer->cf1;
									} 
								?> &nbsp;</b>
								និងឈ្មោះ<b>&nbsp;<?php echo ($jl_data->name!=""?$jl_data->name:"...................")?>&nbsp;</b>
						
								អត្ត/ប័ណ្ណ<b>&nbsp;<?php echo ($jl_data->identify_card!=""?$jl_data->identify_card:"...................")?>&nbsp;</b>
						
								អាស័យដ្ឋានផ្ទះលេខ<b>&nbsp;<?php if($customer->address == ""){echo "...................";}else{echo $customer->address;} ?>&nbsp;</b>
							
								ផ្លូវលេខ<b>&nbsp;<?php if($customer->street == ""){echo "...................";}else{echo $customer->street;} ?>&nbsp;</b>
							
								ភូមិ<b>&nbsp;<?php
									if($customer->village == ""){echo "...................";}else{echo $customer->village;} ?>&nbsp;</b>
						
								ឃុំ/សង្កាត់<b>&nbsp;<?php if($customer->sangkat == ""){echo "...................";}else{echo $customer->sangkat;} ?>&nbsp;</b>
							
								ស្រុក/ខណ្ឌ<b>&nbsp;<?php if($customer->district  == ""){echo "...................";}else{echo $customer->district ;} ?>&nbsp;</b>
								ខេត្ត/រាជធានី<b>&nbsp;<?php
									if($customer->city || $customer->state){
										if($customer->city){
											echo $customer->city;}else{echo $customer->state;}
									}else{
										echo "...................";
									}
									?>&nbsp;</b>
									។
						</p>
						<p>
								២. ការទិញលក់: ប្រភេទផ្ទះ<b>&nbsp;<?php if($rows->cf1  == ""){echo "...................";}else{echo $rows->cf1;}?></b>
							
								ដែលមានទទឹង<b>&nbsp;<?php
								if($height  == ""){echo "...................";}else{echo $height;}
								 ?>&nbsp;</b>
								ម៉ែត្រ, បណ្ដោយ<b>&nbsp;<?php if($width  == ""){echo "...................";}else{echo $width;}
								  ?>&nbsp;</b>
								ម៉ែត្រ
							
								ផ្ទះលេខ<b>&nbsp;<?php if($rows->cf3){ echo $product->cf3; }else{
									echo "...................";
								} ?>&nbsp;</b>
						
								ផ្លូវលេខ<b>&nbsp;<?php if($rows->cf4){ echo $rows->cf4; }else{
									echo "...................";
								} ?>&nbsp;</b>
								ទីតាំង<b>&nbsp;<?php if($rows->cf2){ echo $rows->cf2; }else{
									echo "...................";
								} ?>&nbsp;</b>
								រយះពេលសាងសង់<b>&nbsp;.............................&nbsp;</b>ខែ
						
								តម្លៃដើម<b>&nbsp;<?php if($rows->unit_price){ echo $this->erp->formatMoney($rows->unit_price); }else{
									echo "...................";
								} ?>&nbsp;</b>
								US$ បញ្ចុះតម្លៃ<b>&nbsp;<?php if($product->order_discount){ echo $this->erp->formatMoney($product->order_discount); }else{
									echo "...................";
								} ?>&nbsp;</b>
								US$ តម្លៃលក់ <b>&nbsp;<?php 
								$sale_price = ($rows->unit_price - $product->order_discount);								
								?>&nbsp;</b>
								<b>&nbsp;<?php if($sale_price){ echo $this->erp->formatMoney($sale_price); }else{
									echo "...................";
								} ?>&nbsp;</b>
								US$
				
								តាមរយ:<b>........................................................</b>
								បរិយាយ<b>........................................................</b>
						</p>		
					<table width='100%'>
						<tr>							
							<td class="text-right">
								<span style="padding-right: 18px !important;"><b><hr></b></span>
							</td>	
						</tr>	
					</table>
					
					<table width="100%" >
						<tr>
							
							<td width="20%" class="text-left">
								៣. គោលការណ៍បង់: 
							</td>
							<td width="80%" class="text-right">
								<?php if($jl_data->principle_type != 0){
										if($jl_data->term_name){
										?>
											<input type="checkbox" value="" checked="checked"> ដំណាក់កាល
											<span style="padding-right: 18px !important;">
											<b><?php echo $jl_data->term_name;?></b></span>
										<?php
										}
										?>
											
									<?php
									}else{
										?>
										
										<input type="checkbox" value=""> ដំណាក់កាល
											<span style="padding-right: 18px !important;">
											<b>…………………….......</b></span>
									<?php
									}
									?>
										
										<input type="checkbox" value=""> ផ្ដាច់
										<span style="padding-right: 18px !important;"><b>…………………….........</b></span>
									
									<?php if($jl_data->term_id !=0  && $jl_data->principle_type == 0 ){
										if($jl_data->description){?>
											<input type="checkbox" value="" checked="checked"> រំលស់
											<span style="padding-right: 18px !important;"><b>
												<?php echo $jl_data->description;?></b>
											</span>
								       <?php }else{ ?>
												<input type="checkbox" value=""> រំលស់
												<span style="padding-right: 18px !important;"><b>………………</b>
												</span>
											<?php
											}?>
									
									<?php
									}else if($jl_data->term_id ==0  && $jl_data->principle_term!= 0 ){
										if($jl_data->description){?>
											<input type="checkbox" value="" checked="checked"> រំលស់
											<span style="padding-right: 18px !important;"><b>
												<?php echo $jl_data->description;?></b>
											</span>
								       <?php }else{ ?>
												<input type="checkbox" value=""> រំលស់
												<span style="padding-right: 18px !important;"><b>………………</b>
												</span>
											<?php
											}
											?>
									<?php
									}else{
										?>
										<input type="checkbox" value=""> រំលស់
										<span style="padding-right: 18px !important;"><b>………………</b>
										</span>
									<?php
									}
									?>
							</td>		
						</tr>	
					</table>

				<div class="row col-lg-12 font-kh-bt">
				
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
											$without_interest = 0;
											$have_interest =0;
											$dates="";
											$dates1="";
											
											$dates2="";
											$dates3="";
											$principles=0;
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
														$balances = (($pt->balance > 0)? $pt->balance : 0);
												
														 if($pt->interest == 0) {
															 $without_interest += $payment;
															 $principles=$Principles_amount;
														 } 
														if($pt->interest > 0){
															 $have_interest +=$payment;
														}
														if($pt->period == 1){
															 $dates =$pt->dateline;
															
														}
														if($pt->period == 2){
															 $dates1 =$pt->dateline;
															
														}
														if($pt->period == 13){
															 $dates2 =$pt->dateline;
															
														}
														if($pt->period == 14){
															 $dates3 =$pt->dateline;
															
														}
														
												}
											 //echo $dates1;
											}
											
										?>
								<?php if($jl_data->principle_type != 0){?>
								<div class="col-lg-6 col-sm-6 col-xs-6">
								
									<p>
										-  ប្រាក់កក់ដំបូង<b>&nbsp;<?= (isset($inv->deposit)?$this->erp->formatDecimal($inv->deposit):0)?>&nbsp;</b>$
										ថ្ងៃទី<b>&nbsp;<?php $d=explode("-",$inv->date); $dt=explode(" ",$d[2]); echo "$dt[0]";?>&nbsp;</b>
										ខែ<b>&nbsp;<?php echo "$d[1]"?>&nbsp;</b>
										ឆ្នាំ​<b>&nbsp;<?php echo "$d[0]"?>&nbsp;</b>
										
									</p>
									<p>
										-  លើកទី​​ ១<b>&nbsp;<?= $this->erp->formatDecimal($inv->down_amount); ?>&nbsp;</b>$
										ថ្ងៃទី<b>&nbsp;<?php $d=explode("-",$inv->date); $dt=explode(" ",$d[2]); echo "$dt[0]";?>&nbsp;</b>
										ខែ<b>&nbsp;<?php echo "$d[1]"?>&nbsp;</b>
										ឆ្នាំ​<b>&nbsp;<?php echo "$d[0]"?>&nbsp;</b>
										
									</p>
									<p>
										- ​លើកទី​​ ២<b>&nbsp;<?php echo $this->erp->formatMoney($principles);?>&nbsp;</b>$
										ថ្ងៃទី<b><?php $dl=explode("-",$dates);echo "$dl[2]";?></b>
										ខែ  <b><?php echo "$dl[1]";?></b> 
										ឆ្នាំ​  <b><?php echo "$dl[0]";?></b>
										
									</p>							
									<p>
										-  លើកទី​​ ៣<b>&nbsp;<?php echo $this->erp->formatMoney($principles);?>&nbsp;</b>$
										ថ្ងៃទី<b><?php $dl=explode("-",$dates1);echo "$dl[2]";?></b>
										ខែ  <b><?php echo "$dl[1]";?></b> 
										ឆ្នាំ​  <b><?php echo "$dl[0]";?></b>
										
									</p>
								</div>
									
								<div class="col-lg-6 col-sm-6 col-xs-6">
									<p>
										-  លើកទី​​ ៤<b>.............</b>$
										ថ្ងៃទី<b>..........</b>
										ខែ  <b>..........</b> 
										ឆ្នាំ​  <b>..........</b>
										
									</p>
									<p>
										-  លើកទី​​ ៥<b>.............</b>$
										ថ្ងៃទី<b>..........</b>
										ខែ  <b>..........</b> 
										ឆ្នាំ​  <b>..........</b>
										
									</p>
									<p>
										-  លើកទី​​ ៦<b>.............</b>$
										ថ្ងៃទី<b>..........</b>
										ខែ  <b>..........</b> 
										ឆ្នាំ​  <b>..........</b>
										
									</p>							
									<p>
										-  លើកទី​​ ៧<b>.............</b>$
										ថ្ងៃទី<b>..........</b>
										ខែ  <b>..........</b> 
										ឆ្នាំ​  <b>..........</b>
										
									</p>
								</div>
								<?php
								}
								?>
							<?php if($jl_data->term_id !=0  && $jl_data->principle_type == 0){?>
								<div class="col-lg-6 col-sm-6 col-xs-6">
								
									<p>
										-  ប្រាក់កក់ដំបូង<b>&nbsp;<?= (isset($inv->deposit)?$this->erp->formatDecimal($inv->deposit):0)?>&nbsp;</b>$
										ថ្ងៃទី<b>&nbsp;<?php $d=explode("-",$inv->date); $dt=explode(" ",$d[2]); echo "$dt[0]";?>&nbsp;</b>
										ខែ<b>&nbsp;<?php echo "$d[1]"?>&nbsp;</b>
										ឆ្នាំ​<b>&nbsp;<?php echo "$d[0]"?>&nbsp;</b>
										
									</p>
									<p>
										-  លើកទី​​ ១<b>&nbsp;<?php echo $this->erp->formatMoney($payment);?>&nbsp;</b>$
										ថ្ងៃទី<b><?php $dl=explode("-",$dates);echo "$dl[2]";?></b>
										ខែ  <b><?php echo "$dl[1]";?></b> 
										ឆ្នាំ​  <b><?php echo "$dl[0]";?></b>
										
									</p>
									<p>
										- ​លើកទី​​ ២<b>.............</b>$
										ថ្ងៃទី<b>..........</b>
										ខែ  <b>..........</b> 
										ឆ្នាំ​  <b>..........</b>
										
									</p>							
									<p>
										-  លើកទី​​ ៣<b>.............</b>$
										ថ្ងៃទី<b>..........</b>
										ខែ  <b>..........</b> 
										ឆ្នាំ​  <b>..........</b>
										
									</p>
								</div>
									
								<div class="col-lg-6 col-sm-6 col-xs-6">
									<p>
										-  លើកទី​​ ៤<b>.............</b>$
										ថ្ងៃទី<b>..........</b>
										ខែ  <b>..........</b> 
										ឆ្នាំ​  <b>..........</b>
										
									</p>
									<p>
										-  លើកទី​​ ៥<b>.............</b>$
										ថ្ងៃទី<b>..........</b>
										ខែ  <b>..........</b> 
										ឆ្នាំ​  <b>..........</b>
										
									</p>
									<p>
										-  លើកទី​​ ៦<b>.............</b>$
										ថ្ងៃទី<b>..........</b>
										ខែ  <b>..........</b> 
										ឆ្នាំ​  <b>..........</b>
										
									</p>							
									<p>
										-  លើកទី​​ ៧<b>.............</b>$
										ថ្ងៃទី<b>..........</b>
										ខែ  <b>..........</b> 
										ឆ្នាំ​  <b>..........</b>
										
									</p>
								</div>
								<?php
								}
								?>
								<?php if($jl_data->term_id ==0 && $jl_data->principle_term != 0){?>
								<div class="col-lg-6 col-sm-6 col-xs-6">
								
									<p>
										-  ប្រាក់កក់ដំបូង<b>&nbsp;<?= (isset($inv->deposit)?$this->erp->formatDecimal($inv->deposit):0)?>&nbsp;</b>$
										ថ្ងៃទី<b>&nbsp;<?php $d=explode("-",$inv->date); $dt=explode(" ",$d[2]); echo "$dt[0]";?>&nbsp;</b>
										ខែ<b>&nbsp;<?php echo "$d[1]"?>&nbsp;</b>
										ឆ្នាំ​<b>&nbsp;<?php echo "$d[0]"?>&nbsp;</b>
										
									</p>
									<p>
										-  លើកទី​​ ១<b>&nbsp;<?= $this->erp->formatDecimal($inv->down_amount); ?>&nbsp;</b>$
										ថ្ងៃទី<b><?php $dl=explode("-",$dates);echo "$dl[2]";?></b>
										ខែ  <b><?php echo "$dl[1]";?></b> 
										ឆ្នាំ​  <b><?php echo "$dl[0]";?></b>
										
									</p>
									<p>
										- ​លើកទី​​ ២<b>&nbsp;<?php echo $this->erp->formatMoney($payment);?>&nbsp;</b>$
										ថ្ងៃទី<b><?php $dl=explode("-",$dates2);echo "$dl[2]";?></b>
										ខែ  <b><?php echo "$dl[1]";?></b> 
										ឆ្នាំ​  <b><?php echo "$dl[0]";?></b>
										
									</p>							
									<p>
										-  លើកទី​​ ៣<b>&nbsp;<?php echo $this->erp->formatMoney($payment);?>&nbsp;</b>$
										ថ្ងៃទី<b><?php $dl=explode("-",$dates3);echo "$dl[2]";?></b>
										ខែ  <b><?php echo "$dl[1]";?></b> 
										ឆ្នាំ​  <b><?php echo "$dl[0]";?></b>
										
									</p>
								</div>
									
								<div class="col-lg-6 col-sm-6 col-xs-6">
									<p>
										-  លើកទី​​ ៤<b>.............</b>$
										ថ្ងៃទី<b>..........</b>
										ខែ  <b>..........</b> 
										ឆ្នាំ​  <b>..........</b>
										
									</p>
									<p>
										-  លើកទី​​ ៥<b>.............</b>$
										ថ្ងៃទី<b>..........</b>
										ខែ  <b>..........</b> 
										ឆ្នាំ​  <b>..........</b>
										
									</p>
									<p>
										-  លើកទី​​ ៦<b>.............</b>$
										ថ្ងៃទី<b>..........</b>
										ខែ  <b>..........</b> 
										ឆ្នាំ​  <b>..........</b>
										
									</p>							
									<p>
										-  លើកទី​​ ៧<b>.............</b>$
										ថ្ងៃទី<b>..........</b>
										ខែ  <b>..........</b> 
										ឆ្នាំ​  <b>..........</b>
										
									</p>
								</div>
								<?php
								}
								?>
								
				</div>
				
						<div class=" col-lg-12">
						
							<p>
								
								**បង់ផ្តាច់
									<span style="padding-right: 18px !important;"><?php echo $this->erp->formatMoney($sum_payment) ;?></span>
								$ក្រោយពេលផ្ទះសាងសង់រួចរាល់<span style="padding-right: 18px !important;"> </span>
							</p>
							<?php if($jl_data->principle_type != 0){?>
							<p>
								**បង់ដំណាក់កាល
									<span>&nbsp;<?php echo $this->erp->formatMoney($without_interest) ;?>&nbsp;</span>
								$រហូតដល់ផ្ទះសាងសង់រួចរាល់​ ដោយគិតចាប់ពីថ្ងៃទី
									<span>&nbsp;<?php echo  $this->erp->KhmerNumDate($date_day); ?>&nbsp;</span>
								ខែ
									<span>&nbsp;<?php echo  $this->erp->KhmerMonth($date_month); ?>&nbsp;</span>
								ឆ្នាំ
									<span>&nbsp;<?php echo  $date_year; ?>&nbsp;</span>
							</p>
							
							<p>
								
								**រំលស់
									<span>...............</span>
								$សំរាប់រយះពេល<span>................</span>ឆ្នាំ(<span>......</span>ខែ) ដោយគិតចាប់ពីថ្ងៃទី
									<span>.....</span>
								ខែ
									<span>.....</span>
								ឆ្នាំ
									<span>.....</span>
							</p>
							<?php
								}
							?>
							<?php if($jl_data->term_id !=0  && $jl_data->principle_type == 0){?>
							<p>
								**បង់ដំណាក់កាល
									<span>................</span>
								$រហូតដល់ផ្ទះសាងសង់រួចរាល់​ ដោយគិតចាប់ពីថ្ងៃទី
									<span>.....</span>
								ខែ
									<span>.....</span>
								ឆ្នាំ
									<span>......</span>
							</p>
							
							<p>
								
								**រំលស់
									<span>&nbsp;<?php echo $this->erp->formatMoney($have_interest );?>&nbsp;</span>
								$សំរាប់រយះពេល<span> &nbsp;<?php echo round($inv->term / 365 ); ?>&nbsp;</span>ឆ្នាំ(<span>&nbsp; <?php echo round($inv->term / 365 * 12 );  ?>&nbsp;</span>ខែ) ដោយគិតចាប់ពីថ្ងៃទី
									<span>&nbsp;<?php echo $this->erp->KhmerNumDate($date_day); ?>&nbsp;</span>
								ខែ
									<span>&nbsp;<?php echo $this->erp->KhmerMonth($date_month); ?>&nbsp;</span>
								ឆ្នាំ
									<span>&nbsp;<?php echo  $date_year; ?>&nbsp;</span>
							</p>
							<?php
								}
							?>
							<?php if($jl_data->term_id ==0 && $jl_data->principle_term != 0){?>
							<p>
								**បង់ដំណាក់កាល
									<span>................</span>
								$រហូតដល់ផ្ទះសាងសង់រួចរាល់​ ដោយគិតចាប់ពីថ្ងៃទី
									<span>.....</span>
								ខែ
									<span>.....</span>
								ឆ្នាំ
									<span>......</span>
							</p>
							
							<p>
								
								**រំលស់
									<span>&nbsp;<?php echo $this->erp->formatMoney($have_interest );?>&nbsp;</span>
								$សំរាប់រយះពេល<span> &nbsp;<?php echo round($inv->term / 365 ); ?>&nbsp;</span>ឆ្នាំ(<span>&nbsp; <?php echo round($inv->term / 365 * 12 );  ?>&nbsp;</span>ខែ) ដោយគិតចាប់ពីថ្ងៃទី
									<span>&nbsp;<?php echo $this->erp->KhmerNumDate($date_day); ?>&nbsp;</span>
								ខែ
									<span>&nbsp;<?php echo $this->erp->KhmerMonth($date_month); ?>&nbsp;</span>
								ឆ្នាំ
									<span>&nbsp;<?php echo  $date_year; ?>&nbsp;</span>
							</p>
							<?php
								}
							?>
						</div>
						<div class='row col-lg-12'>
							<br/>
							<p><span style='margin-left: 7%'>អ្នកទិញ</span>
							<span style='margin-left: 14%'>អ្នកលក់</span>
							<span style='margin-left: 21%'>អ្នកពិនិត្យ</span>
							<span style='margin-left: 15%'>អ្នកអនុញ្ញាត</span></p>
						</div><br><br><br><br><br><br><br>
						
						<div>
							<table style="width:100%;font-family: Khmer OS Battambang;font-size: 12px;color: #2b4666;">
								<tr>
									<td>ឈ្មោះ</td>
									<td><b><?php echo  ($customer->name?$customer->name:"………………"); ?></b></td>
									<td>ឈ្មោះ</td>
									<td><b><?php echo ($seller->username?($seller->first_name).' '.($seller->last_name):"………………"); ?></b></td>
									<td>ឈ្មោះ</td>
									<td>………………...</td>
									<td>ឈ្មោះ</td>
									<td>…………...……</td>
									<td>ឈ្មោះ</td>
									<td>……………...…</td>
								</tr>
								<tr style="height:20px">
									<td></td>
								</tr>
								<tr>
									<td>ទូរស័ព្ទ</td>
									<td><b><?php echo ($customer->phone?$customer->phone:"………………"); ?></b></td>
									<td>ទូរស័ព្ទ</td>
									<td><b><?php echo ($seller->phone?$seller->phone:"………………"); ?></b></td>
									<td>ទូរស័ព្ទ</td>
									<td>……………...…</td>
									<td>ទូរស័ព្ទ</td>
									<td>……………...…</td>
									<td>ទូរស័ព្ទ</td>
									<td>……………...…</td>
								</tr>
									<tr style="height:20px">
									<td></td>
								</tr>
								<tr>
									<td>ថ្ងៃទី</td>
									<td><b><?php echo date("d/m/Y",strtotime($fullday));?></b></td>
									<td>ថ្ងៃទី</td>
									<td><b><?php echo date("d/m/Y",strtotime($fullday)); ?></b></td>
									<td>ថ្ងៃទី</td>
									<td>…………...……</td>
									<td>ថ្ងៃទី</td>
									<td>……...…………</td>
									<td>ថ្ងៃទី</td>
									<td>………...………</td>
								</tr>
								<tr style="height:20px">
									<td></td>
								</tr>
							</table>
						
						
						</div>
						
						<p><u>បញ្ជាក់</u> : ប្រាក់ដែលកក់រួច មិនអាចដកវិញបានទេ ។</p>
						<p class='foot'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ប្រសិនបើអ្នកទិញមិនបានបង់ប្រាក់បន្ថែមតាមកំណត់នោះ​ ប្រាក់កក់នឹងចាត់ទុកជាមោឃៈដោយស្វ័យប្រវិិត្ត</p>
						<br>
						<div style="font-size: 12px;color:#2B4666" class='foot'>
							<span id='ftext'>បុរី អាទាំងមាស​ ( គំរោងឌឹផ្លរ៉ា )</span><br>
							<span id='ftext'>ការិយាល័យកណ្ដាល: សង្កាត់បាក់ខែង ខ័ណ្ឌជ្រោយចង្វា រាជធានីភ្នំពេញ</span><br>
							<span id='ftext'>ទូរស័ព្ទលេខ: 061 77 67 67 / 097 777 0678 គេហទំព័រ : www.boreytheflora.com</span>
						</div>
				</div>
            </div>
        </div>
		
</body>
</html>