<?php 
	//$this->erp->print_arrays($rows);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $this->lang->line("sales") . " " . $inv->reference_no; ?></title>
    <link href="<?php echo $assets ?>styles/theme.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Moul|Raleway" rel="stylesheet"> 
    <style type="text/css">
        html, body {
            height: 100%;
            background: #FFF;
        }

        body:before, body:after {
            display: none !important;
        }

        .table th {
            text-align: center;
            padding: 5px;
        }

        .table td {
            padding: 4px;
        }

        table,tr,td{
            font-size: 13px !important;
        }
		
		.description{
			width: 21cm;
			margin: 0px auto;
			box-shadow: 0 0 5px rgba(0, 0, 0, 0.5);
			font-family: Khmer OS Battambang;
			font-size: 12px;
		}
    </style>
</head>

<body>
<div id="wrap" style="width: 794px; margin: 0 auto;">
    <div class="row description">
		<div class="col-lg-12">
			<button class="pull-right no-print" id="print_receipt" onclick="window.print();"><?= lang("Print"); ?>&nbsp;<i class="fa fa-print"></i></button>
		</div>
        <div class="col-lg-12 col-xs-12">
            <br>
            <p style="margin-right: 20px !important;text-align: right;">ឧបសម្ព័ន្ធ ខ</p>
            <table class="table table-bordered" style="width: 95%;margin: 0 auto;">
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
                    <td colspan="3" rowspan="2" style="border-top-color:white !important;border-left-color:white !important;border-right-color:white !important;">
                        <?php if ($logo) { ?>
                            <div class="text-center" style="margin-bottom:20px;">
                                <img src="<?= base_url() . 'assets/uploads/logos/' . $biller->logo; ?>"
                                     alt="<?= $biller->company != '-' ? $biller->company : $biller->name; ?>">
                            </div>
                        <?php } ?>
                    </td>
                    <td rowspan="2" style="border-top-color:white !important;border-bottom-color: white !important;"></td>
                    <td><p style="font-family: 'Moul', cursive;">តម្លៃផ្ទះ</p></td>
                    <td colspan="2"><p><b>: US$ <?php echo $this->erp->formatMoney($rows->unit_price); ?></b></p></td>
                </tr>
                <tr>
                    <td><p style="font-family: 'Moul', cursive;margin-top:8px;">ទឹកប្រាក់កក់</p></td>
                    <td><p style="margin-top:8px;"><?php echo $this->erp->formatMoney($payments->amount*100/$rows->unit_price); ?>%</p></td>
                    <td><p style="margin-top:8px;">$<?php echo $this->erp->formatMoney($payments->amount); ?></p></td>
                </tr>
                <tr>
                    <td style="width: 20%;">
                        <p style="font-family: 'Moul', cursive;margin-top:8px;">ឈ្មោះ</p>
                    </td>
                    <td style="width: 25%;" colspan="2">
                        <p style="margin-top:8px;"><b>
                            <?php if($customer->name_kh){echo $customer->name_kh;}else{echo $customer->name;} ?>
                        </b></p>
                    </td>
                    <td style="width: 10%;border-bottom-color:white !important;"></td>
                    <td style="width: 25%;">
                        <p style="font-family: 'Moul', cursive;margin-top:8px;">រយះពេល</p>
                    </td>
                    <td style="width: 8%;">
                        <p style="margin-top:8px;">: <?php echo $frequency->frequency; ?></p>
                    </td>
                    <td style="width: 12%;">
                        <p style="margin-top:8px;"><span>
                            <?php if($frequency->principle_type == 0){
                                echo "$     ".$total_sched;
                            }else{
                                echo "$     ".$this->erp->formatMoney($total_prin);
                                } ?>
                        </span></p>
                    </td>
                </tr>
                <tr>
                    <td><p style="font-family: 'Moul', cursive;margin-top:8px;">ទូរស័ព្ទ</p></td>
                    <td colspan="2"><p style="margin-top: 8px;"><?php echo $customer->phone; ?></p></td>
                    <td style="border-bottom-color:white !important;"></td>
                    <td><p style="font-family: 'Moul', cursive;margin-top:8px;">ទឹកប្រាក់បង់រម្លស់</p></td>
                    <td colspan="2"><p style="margin-top:8px;"><span>$</span >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span><?php echo $this->erp->formatMoney($total_balance); ?></span></p></td>
                </tr>
                <tr>
                    <td><p style="font-family: 'Moul', cursive;margin-top:8px;">លេខផ្ទះ. ផ្លូវ</p></td>
                    <td><p style="margin-top:8px;"><b><?php echo $customer->cf3; ?></b></p></td>
                    <td><p style="margin-top:8px;"><b><?php echo $customer->cf4; ?></b></p></td>
                    <td style="border-bottom-color:white !important;"></td>
                    <td><p style="font-family: 'Moul', cursive;margin-top:8px;">អត្រាការប្រាក់</p></td>
                    <td colspan="2"><p style="margin-top:8px;">: <?php echo $rate; ?>%</p></td>
                </tr>
                <tr>
                    <td><p style="font-family: 'Moul', cursive;margin-top:8px;">អត្តសញ្ញាណប័ណ្ណ</p></td>
                    <td colspan="2"><p style="margin-top:8px;"><?php echo $customer->cf1; ?></p></td>
                    <td style="border-bottom-color:white !important;"></td>
                    <td><p style="font-family: 'Moul', cursive;margin-top:8px;">រយះពេល</p></td>
                    <td><p style="margin-top:8px;">: <?php echo $frequency->term; ?></p></td>
                    <td><p style="margin-top:8px;">
                        <?php if($frequency->principle_type == 0){
                                echo "$     ".$grandtotal;
                            }else{
                                echo "$     ".$this->erp->formatMoney($grandtotal_prin);
                                } ?>
                    </p></td>
                </tr>
                <tr>
                    <td><p style="font-family: 'Moul', cursive;margin-top:8px;">កាលបរិច្ឆេទ</p></td>
                    <td colspan="2"><p style="margin-top:8px;">: <?php echo $this->erp->hrsd($date); ?></p></td>
                    <td style="border-bottom-color:white !important;"></td>
                    <td><p style="font-family: 'Moul', cursive;margin-top:8px;">ប្រាក់បង់ប្រចាំខែ</p></td>
                    <td colspan="2"><p style="margin-top:8px;">: US$ <?php echo $this->erp->formatMoney($monthly_paid); ?></p></td>
                </tr> 
            </table>
         </div>
         <div class="col-lg-12 col-xs-12">
            <br>
            <table class="table table-bordered" style="width: 95%;margin: 0 auto;">
                <thead  style="font-size: 12px;font-family: 'Moul', cursive;">
                    <tr>
                        <td style="width: 5%;text-align: center;">
                            <p><u>លេខ</u></p>
                            <p><u>រៀង</u></p>
                        </td>
                        <td style="width: 15%;text-align: center;">
                            <p><u>កាលបរិច្ឆេទ</u></p>
                            <p><u>បង់ប្រាក់</u></p>
                        </td>
                        <td style="width: 15%;text-align: center;">
                            <p><u>ទឹកប្រាក់បង់</u></p>
                            <p><u>ប្រចាំខែ</u></p>
                        </td>
                        <td style="width: 8%;text-align: center;">
                            <p></p>
                            <p><u>ការប្រាក់</u></p>
                        </td>
                        <td style="width: 10%;text-align: center;">
                            <p></p>
                            <p><u>ប្រាក់ដើម</u></p>
                        </td>
                        <td style="width: 18%;text-align: center;">
                            <p></p>
                            <p><u>សមតុល្យ</u></p>
                        </td>
                        <td style="width: 10%;text-align: center;">
                            <p><u>ចំណាយ</u></p>
                            <p><u>ផ្សេងៗ</u></p>
                        </td>
                        <td style="width: 10%;text-align: center;">
                            <p></p>
                            <p><u>ថ្ងៃបង់ប្រាក់</u></p>
                        </td>
                        <td style="width: 10%;text-align: center;">
                            <p></p>
                            <p><u>ផ្សេងៗ</u></p>
                        </td>
                    </tr>
                <thead  style="font-size: 13px;">
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
							$Principles_amount =0;
							
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
                                  
								   
                                    echo '<tr class="row-data" '.(($pt->paid_amount > 0)? 'style="pointer-events:none;" disabled="disabled"':'').'>
                                        <td class="t_c" style="padding-left:5px; padding-right: 5px; height: 25px;text-align:center;">'. $pt->period .'</td>
                                        <td class="t_c" style="padding-left:5px; padding-right:5px;text-align:center;">'. $this->erp->hrsd($pt->dateline) .'</td>';
                                        $balances = (($pt->balance > 0)? $pt->balance : 0);
                                        $balances = str_replace(',', '', $this->erp->formatMoney($balances));
                                        $principle_amt = str_replace(',', '', $Principles);
                                        $loan_balance = $balances + $principle_amt;
                                        $haft_paid = 0;
                                        $insurences_paid = 0;
                                        $all_paid = 0;
                                    
                                        $Principles_amount = str_replace(',', '', $Principles);
                                        $interests_amount = str_replace(',', '', $interests);
                                        if($pt->period > 1 && $pt->period <= $countrow){
                                            $payment = $Principles_amount + $interests_amount + $all_paid + $haft_paid + $insurences_paid;
                                        }else{
                                            $payment = $Principles_amount + $interests_amount + $all_paid;
                                        }
                                        $balances = (($pt->balance > 0)? $pt->balance : 0);
                                    echo '<td class="t_r" style="padding-left:5px;padding-right:5px;text-align:center;">'. $this->erp->formatMoney($payment) .'</td>
									<td class="t_r" style="padding-left:5px; padding-right:5px;text-align:center;">'. $interests .'</td>
									 <td class="t_r" style="padding-left:5px; padding-right:5px;text-align:center;">'. $Principles .'</td>
                                        
                                          <td class="t_r" style="padding-left:5px;padding-right:5px;text-align:center;">'. $this->erp->formatMoney($balances) .'</td><td></td><td></td><td></td>
                                         '; 
                                  
                                }
								
								
								
                            }
							//echo "principle=". $no_interest."##"."have_interest=".$have_interest;
							
                        ?>
             </table>
			 
             <br>
        </div>
        <div class="col-lg-12 col-xs-12">
            <div class="col-lg-4 col-xs-4">
                <p class="text-center"><b>អ្នករៀបរៀង</b></p>
            </div>
            <div class="col-lg-4 col-xs-4">
                <p class="text-center"><b>អ្នកអនុម័ត</b></p>
            </div>
            <div class="col-lg-4 col-xs-4">
                <p class="text-center"><b>អតិថិជន(<span style="font-size: 11px;">យល់ព្រម</span>)</b></p>
            </div>
        </div>
        <div class="col-lg-12 col-xs-12">
            <br><br><br><br>
            <div class="col-lg-4 col-xs-4">
                <p class="text-center"><b>លន់ សុពណ៌វត្តី</b></p>
            </div>
            <div class="col-lg-4 col-xs-4">
                <p class="text-center" style="margin-top:3% !important;">....................................................</p>
            </div>
            <div class="col-lg-4 col-xs-4">
                <p class="text-center"><b>ទួន សុខា/ឈិន ចាន់ណាក់</b></p>
            </div>
        </div>
        <div class="col-lg-12 col-xs-12">
            <div class="col-lg-4 col-xs-4">
                <p class="text-center" style="margin-top:3% !important;">....................................................</p>
            </div>
            <div class="col-lg-4 col-xs-4">
                <p class="text-center" style="margin-top:3% !important;">....................................................</p>
            </div>
            <div class="col-lg-4 col-xs-4">
                <p class="text-center" style="margin-top:3% !important;">....................................................</p>
            </div>
        </div>
    </div>
</div>
</body>
</html>