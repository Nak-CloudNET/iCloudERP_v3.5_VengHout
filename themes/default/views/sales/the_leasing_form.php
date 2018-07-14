<?php 
	//$this->erp->print_arrays($rows);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Leasing Form</title>
    <link href="<?php echo $assets ?>styles/theme.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Moul|Raleway" rel="stylesheet"> 
    <style type="text/css">
        html, body {
            height: 100%;
            background: #FFF;
        }
		.moul{
			font-family: Khmer OS Muol Light !important;
			font-weight:bold;
		}
		.font-kh-bt{
			font-family: Khmer OS Battambang !important;
		}
        body:before, body:after {
            display: none !important;
        }
		@page {
			size: A4;
			margin: 30px;
		}
		@media print{
			.set_line{
				text-align:center !important;
			}
			#set_line{
				margin:0px !important;
				padding:0px !important;
			}
			thead th,b {
				font-size: 15px !important;
			}
			tr td{
				font-size: 14px !important;
			}
		}
        .table th {
            text-align: center;
            padding: 5px;
			
        }

        .table td {
            padding: 4px;
        }

		.description{
			width: 1000px;
			margin: 0px auto;
			box-shadow: 0 0 5px rgba(0, 0, 0, 0.5);
			font-family: Khmer OS Battambang;
			font-size: 12px;
		}
		.tnew{
			border:solid 1px black;
		}
		.col{
			border-left:1px solid black;
		}
		.col1{
			border-right:1px solid black;
		}
		
    </style>
</head>

<body>

<div id="wrap" style="width:1000px; margin: 0 auto;">
    <div class="row description">
		<div class="col-lg-12">
			<button class="pull-right no-print" id="print_receipt" onclick="window.print();"><?= lang("Print"); ?>&nbsp;<i class="fa fa-print"></i></button>
		</div>
        <div class="col-lg-12 text-center col-xs-12">
			<h4 class="moul">ព្រះរាជាណាចក្រកម្ពុជា</h4>
			<h4 class="moul">ជាតិ​  សាសនា  ព្រះមហាក្សត្រ</h4><br>
            <h5 class="moul" style="text-decoration:underline;">តារាងបង់រំលស់</h5><br>
        </div>
		
		<div class="col-lg-12">
			<table style="width:95%;margin: 0 auto;">
				 <?php foreach($loan as $lo){
                                        $total_sched = $this->erp->formatMoney($lo->interest)+$this->erp->formatMoney($lo->principle);
                                        $grandtotal += $total_sched; 
                                        $total_prin = $lo->principle;
                                        $grandtotal_prin += $total_prin;
                                        $rate = $lo->rated;
                                        $total_balance += $lo->balance;
                                        $monthly_paid = $lo->paid_amount;
                    } ?>
					
					<tr>
						<th class="tnew text-center" style="width:50%;height:30px;" colspan="4">ពត៌មានអតិថិជន</th>
						<th class="tnew text-center" style="width:25%;" colspan="2">ពត៌មានរំលស់</th>
						<th class="tnew text-center" style="width:25%;" colspan="2">ពត៌មានបង់ប្រាក់</th>
					</tr>
				
					<tr>
						<td class="tnew" style="width:10%;font-size:11px;height:20px;">ថ្ងៃខែឆ្នាំទិញផ្ទះ</td>
						<td class="tnew" style="width:25%;font-size:11px;"></td>
						<td class="tnew text-center" style="width:10%;font-size:11px;">ភេទ</td>
						<td class="tnew text-center" style="width:10%;font-size:11px;"><?php echo $customer->gender; ?></td>
						<td class="tnew" style="width:10%;font-size:11px;">ថ្ងៃចាប់ផ្តើមរំលស់</td>
						<td class="tnew" style="width:10%;font-size:11px;">&nbsp;<?php echo date("d/m/Y",strtotime($inv->down_date)); ?></td>
						<td class="tnew" style="width:10%;font-size:11px;">ប្រាក់ត្រូវបង់ប្រចាំខែ</td>
						<?php
							$totalpayment=0;
                            if(array($loan)) {
                                foreach($loan as $pt){   
										$interest        = $this->erp->formatMoney($pt->interest);    								
                                        $overdue_amt     = (($pt->paid_amount > 0)? $pt->overdue_amount : 0);
                                        $payment         = $pt->payment + $overdue_amt;    
										if($pt->interest>0){
											$totalpayment+=$payment;
										}
                                }	
                            }
						?>
						<td class="tnew" style="width:10%;font-size:11px;">&nbsp;: $&nbsp;&nbsp;&nbsp;<?php echo $this->erp->formatMoney($payment);?></td>
					</tr>
					
					<tr>
						<td class="tnew" style="width:10%;font-size:11px;height:20px;">លេខផ្ទះនិងផ្លូវ</td>
						<td class="tnew" style="width:25%;font-size:11px;">
							<?php if($customer->address){ echo $customer->address;}else{ echo "N/A";} ?>
						</td>
						<td class="tnew text-center" style="width:10%;font-size:11px;">អត្តសញ្ញាណប័ណ្ណ</td>
						<td class="tnew " style="width:10%;font-size:11px;"><?php if($customer->cf1){echo $customer->cf1;}else{echo "N/A";}?></td>
						<td class="tnew" style="width:10%;font-size:11px;">ប្រាក់កម្ចី</td>
						<td class="tnew" style="width:10%;font-size:11px;">&nbsp;: $&nbsp;&nbsp;&nbsp;<?=$this->erp->formatDecimal($inv->grand_total - ($inv->deposit+$order_down->down_amount+$order_down->principle_amount)) ?></td>
						<td class="tnew" style="width:10%;font-size:11px;">ប្រាក់ត្រូវបង់សរុប</td>
						<td class="tnew" style="width:10%;font-size:11px;">&nbsp;: $&nbsp;&nbsp;&nbsp;<?php echo $this->erp->formatMoney($totalpayment);?></td>
					</tr>
			
					<tr>
						<td class="tnew" style="width:10%;font-size:11px;height:20px;">ឈ្មោះ</td>
						<td class="tnew " style="width:25%;font-size:11px;">
							<?php if($customer->name_kh != ""){ 
								echo $customer->name_kh;}else{echo $customer->name;}
							?>
						</td>
						<td class="text-center col" style="width:10%;font-size:11px;"></td>
						<td class="col1" style="width:10%;font-size:11px;"></td>
						<td class="tnew" style="width:10%;font-size:11px;">រយះពេលរំលោះ(ឆ្នាំ)</td>
						<td class="tnew" style="width:10%;font-size:11px;">&nbsp;: <?php echo ($frequency->term/365); ?>&nbsp;ឆ្នាំ</td>
						<td class="col" style="width:10%;font-size:11px;"></td>
						<td style="width:10%;font-size:11px;"></td>
					</tr>
					
					<tr>
						<td class="tnew" style="width:10%;font-size:11px;height:20px;">ថ្ងៃខែឆ្នាំកំណើត</td>
						<td class="tnew " style="width:25%;font-size:11px;">
							<?php
								if($cus_date_of_birth){ 
									echo date("d/m/Y",strtotime($customer->identify_date));}
									else{ echo " .........................";}
							?>
						</td>
						<td class="text-center col" style="width:10%;font-size:11px;"></td>
						<td class="col1" style="width:10%;font-size:11px;"></td>
						<td class="tnew" style="width:10%;font-size:11px;">រយះពេលរំលោះ(ខែ)</td>
						<td class="tnew" style="width:10%;font-size:11px;">&nbsp;: <?php echo ((($frequency->frequency*12)*($frequency->term/365))/30); ?>&nbsp;ខែ</td>
						<td class="col" style="width:10%;font-size:11px;"></td>
						<td style="width:10%;font-size:11px;"></td>
					</tr>
					
					<tr>
						<td class="tnew" style="width:10%;font-size:11px;height:20px;">លេខទូរស័ព្ទ</td>
						<td class="tnew" style="width:25%;font-size:11px;"><?php echo $customer->phone; ?></td>
						<td class="text-center col" style="width:10%;font-size:11px;"></td>
						<td class="col1" style="width:10%;font-size:11px;"></td>
						<td class="tnew" style="width:10%;font-size:11px;">ការប្រាក់ប្រចាំខែ</td>
						<td class="tnew" style="width:10%;font-size:11px;">&nbsp;: <?php echo $rate; ?>%</td>
						<td class="col" style="width:10%;font-size:11px;"></td>
						<td style="width:10%;font-size:11px;"></td>
					</tr>
				
			</table>
			<br>
		</div>
		
         <div class="col-lg-12 col-xs-12">
            <br>
            <table class="table table-bordered" style="width: 95%;margin: 0 auto;">
                <thead  style="font-size: 12px;">
                    <tr>
                        <td style="width: 5%;text-align: center; vertical-align:middle;">
                            <p><b>ល.រ</b></p>
                        </td>
                        <td style="width: 15%;text-align: center;">
                            <p><b>ថ្ងៃបង់</b></p>
                            <p><b>ប្រាក់</b></p>
                        </td>
                        <td style="width: 15%;text-align: center;">
                            <p><b>ថ្ងៃបង់ប្រាក់</b></p>
                            <p><b>ជាក់ស្តែង</b></p>
                        </td>
                        <td style="width: 10%;text-align: center;">
                            <p></p>
                            <p><b>ប្រាក់ដើម</b></p>
                        </td>
                        <td style="width: 8%;text-align: center;">
                            <p></p>
                            <p><b>ការប្រាក់</b></p>
                        </td>
						<td style="width: 18%;text-align: center;">
                            <p><b>ទឹកប្រាក់</b></p>
                            <p><b>សរុបត្រូវបង់</b></p>
                        </td>
                        <td style="width: 18%;text-align: center;">
                            <p></p>
                            <p><b>សមតុល្យ</b></p>
                        </td>
                        <td style="width: 10%;text-align: center;">
                            <p><b>ចំនួនថ្ងៃ</b></p>
                            <p><b>បង់យឺត</b></p>
                        </td>
                        <td style="width: 10%;text-align: center;">
                            <p><b>ប្រាក់ពិន័យ</b></p>
                            <p><b>បង់យឺត</b></p>
                        </td>
                        <td style="width: 10%;text-align: center;">
                            <p><b>ហត្ថលេខាអ្នក</b></p>
                            <p><b>បង់ប្រាក់</b></p>
                        </td>
						<td style="width: 10%;text-align: center;">
                            <p><b>ហត្ថលេខាអ្នក</b></p>
                            <p><b>ទទួលប្រាក់</b></p>
                        </td>
                    </tr>
					<tr style="font-family: Arial, Helvetica, sans-serif;font-size:14px;">
						<td class="tnew text-center" style="width:15%;">
							<p><b>Payment</b></p>
							<p><b>period</b></p>
						</td>
						<td class="tnew text-center" style="width:10%;">
							<p><b>Payment</b></p>
                            <p><b>date</b></p>
						</td>
						<td class="tnew text-center" style="width:15%;">
							<p><b>Actual</b></p>
                            <p><b>payment date</b></p>
						</td>
						<td class="tnew text-center" style="width:10%;">
							 <p></p>
                            <p><b>Principle</b></p>
						</td>
						<td class="tnew text-center" style="width:15%;">
							<p></p>
                            <p><b>Interest</b></p>
						</td>
						<td class="tnew text-center" style="width:10%;">
							  <p><b>Total</b></p>
                            <p><b>payment</b></p>
						</td>
						<td class="tnew text-center" style="width:15%;">
							 <p></p>
                            <p><b>balance</b></p>
						</td>
						<td class="tnew text-center" style="width:10%;">
							<p><b>Late payment</b></p>
                            <p><b>(days)</b></p>
						</td>
						<td class="tnew text-center" style="width:10%;">
							<p><b>Late payment</b></p>
                            <p><b>(penalry)</b></p>
						</td>
						<td class="tnew text-center" style="width:15%;">
							<p><b>Payer's</b></p>
                            <p><b>signature</b></p>
						</td>
						<td class="tnew text-center" style="width:10%;">
							<p><b>receiver's</b></p>
                            <p><b>signature</b></p>
						</td>
						
					</tr>
                <thead  style="font-size: 13px;">
                <?php
                            $erow=1;
							$i=0;
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
                                        
                                    echo '<tr class="row-data" '.(($pt->paid_amount > 0)? 'style="pointer-events:none;" disabled="disabled"':'').'>
                                        <td class="t_c" style="padding-left:5px; padding-right: 5px; height: 25px;text-align:center;">'. $pt->period .'</td>
                                        <td class="t_c" style="padding-left:5px; padding-right:5px;text-align:center;">'. $this->erp->hrsd($pt->dateline) .'</td>
                                        <td></td>
                                        <td class="t_r" style="padding-left:5px; padding-right:5px;text-align:center;">'. $this->erp->formatMoney($payment) .'</td>';
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
                                    echo '<td class="t_r" style="padding-left:5px;padding-right:5px;text-align:center;">'. $this->erp->formatMoney($interests) .'</td>
                                          <td></td><td class="t_r" style="padding-left:5px;padding-right:5px;text-align:center;">'. $this->erp->formatMoney($balances) .'</td>
										  <td></td>
										  <td></td>
										  <td></td>
										  <td></td>
                                        '; 
                                        
                                }
                            }
							
                        ?>
						
						
             </table>
			 
             <br>
        </div>
    </div>
</div>
</body>
</html>