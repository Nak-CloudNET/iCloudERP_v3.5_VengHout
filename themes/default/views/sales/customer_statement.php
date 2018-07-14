<script>
    $(document).ready(function (e) {
		
		var oTable = $('#Loan_List').dataTable({
            "aaSorting": [[1, "asc"], [0, "desc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?=lang('all')?>"]],
            "iDisplayLength": 100,
            'bProcessing': true, 'bServerSide': true,
			'bFilter': false,
            'sAjaxSource': '<?=site_url('sales/list_house_data/'.$sale_id)?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "<?=$this->security->get_csrf_token_name()?>",
                    "value": "<?=$this->security->get_csrf_hash()?>"
                });
                $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
            }
        }).fnSetFilteringDelay().dtFilter([
            {column_number: 1, filter_default_label: "[<?=lang('Pmt No.');?>] ", filter_type: "text", data: []},
            {column_number: 5, filter_default_label: "[<?=lang('balance');?>]", filter_type: "text", data: []},
            {column_number: 6, filter_default_label: "[<?=lang('date');?> (yyyy-mm-dd)]", filter_type: "text", data: []},
            {column_number: 7, filter_default_label: "[<?=lang('note');?>]", filter_type: "text", data: []},
            {column_number: 8, filter_default_label: "[<?=lang('received_by');?>]", filter_type: "text", data: []},
			{column_number: 9, filter_default_label: "[<?=lang('date');?> (yyyy-mm-dd)]", filter_type: "text", data: []},
        ], "footer");

    });
	
</script>
<style>
	.owed, .pay_interest_status{
		display:none;
	}
</style>		
<div class="modal-dialog modal-lg no-modal-header" style="width:80% !important;">
    <div class="modal-content">
        <div class="modal-body">
            <div class="col-lg-12 col-xs-12">
            <br>
			<? $recieved = $biller->company != '-' ? $biller->company : $biller->name;?>
            <p style="margin-right: 20px !important; padding-left: 24px;"><b><?=$recieved?> (គំរោង ឌឹផ្លរ៉ា)</b></p>
            <table class="table table-bordered" style="width: 95%;margin: 0 auto;">
                <?php foreach($loan as $lo){
                                        $total_sched = $this->erp->formatMoney($lo->interest)+$this->erp->formatMoney($lo->principle);
                                        $grandtotal += $total_sched; 
                                        $total_prin = $lo->principle;
                                        $grandtotal_prin += $total_prin;
                                        $rate = $lo->rated;
                                        $total_balance += $lo->balance;
                                        $monthly_paid = $lo->payment;
                    } ?>

                <tr>
                    <td style="width: 20%;">
                        <p style="font-family: 'Moul', cursive;margin-top:8px;">ឈ្មោះ</p>
                    </td>
                    <td style="width: 25%;" colspan="2">
                        <p style="margin-top:8px;"><b>:
                            <?php if($customer->name_kh){echo $customer->name_kh;}else{echo $customer->name;} ?>
                        </b></p>
                    </td>
                     <td style="border-top-color:white !important;border-bottom-color: white !important;"></td>
                    <td><p style="font-family: 'Moul', cursive;">តម្លៃផ្ទះ</p></td>
                    <td colspan="2"><p><b>:  <?php echo $rows->unit_price?> US$</b></p></td>
               </tr>
                <tr>
                    <td><p style="font-family: 'Moul', cursive;margin-top:8px;">ទូរស័ព្ទ</p></td>
                    <td colspan="2"><p style="margin-top: 8px;"><b>: <?php echo $customer->phone; ?></b></p></td>
                    <td style="border-bottom-color:white !important;"></td>
					<td><p style="font-family: 'Moul', cursive;margin-top:8px;">បញ្ចុះ</p></td>
                    <td colspan="2"><p style="margin-top:8px;"><span style="padding-right: 52px !important;">
					<b>: <?php echo $this->erp->formatMoney($rows->order_discount);?></b></span></p>
					</td>
                </tr>
                <tr>
                    <td><p style="font-family: 'Moul', cursive;margin-top:8px;">ប្រភេទផ្ទះ. </p></td>
                    <td colspan="2"><p style="margin-top:8px;"><b>: <?php echo $products->cf1; ?></b></p></td>
                    <td style="border-bottom-color:white !important;"></td>
					<?php
						$sale_price = ($rows->unit_price - $rows->order_discount);
					?>
                    <td><p style="font-family: 'Moul', cursive;margin-top:8px;">តំលៃលក់</p></td>
                    <td colspan="2"><p style="margin-top:8px;"><b>: <?php echo $sale_price ?></b></p></td>
                </tr>
                <tr>
                    <td><p style="font-family: 'Moul', cursive;margin-top:8px;">ផ្ទះលេខ</p></td>
                    <td  colspan="2"><p style="margin-top:8px;"><p style="margin-top:8px;"><b>: <?php echo $products->cf3; ?></b></p></td>
                    <td style="border-bottom-color:white !important;"></td>
                   <td><p style="font-family: 'Moul', cursive;margin-top:8px;">ការបង់ប្រាក់​</p></td>
                    <td colspan="2"><p style="margin-top:8px;"><b>: <?php echo $rows->description; ?></b></p></td>
                </tr>
                <tr>
                    <td><p style="font-family: 'Moul', cursive;margin-top:8px;">ផ្លូវលេខ</p></td>
                    <td colspan="2"><b>: <?php echo $products->cf4; ?></b></td>
                    <td style="border-bottom-color:white !important;"></td>
                     <td><p style="font-family: 'Moul', cursive;margin-top:8px;">កាលបរិច្ឆេទ</p></td>
                    <td colspan="2"><p style="margin-top:8px;"><b>: <?php echo $this->erp->hrsd($date); ?></b></p></td>
                    
                </tr> 
            </table>
         </div>
			<div class="col-lg-12 col-xs-12">
            <br>
            <table class="table table-bordered" style="width: 95%;margin: 0 auto;">
                <thead  style="font-size: 12px;font-family: 'Moul', cursive;">
                    <tr>
                        <td style="width: 5%;text-align: center;">
                            <p><u>ល.រ</u></p>
                        </td>
                        <td style="width: 15%;text-align: center;">
                            <p><u>កាលបរិច្ឆេទ</u></p>
                        </td>
                       ​<td style="width: 8%;text-align: center;">
                           <p><u>ការប្រាក់/ផ្សេងៗ</u></p>
                        </td>
                        <td style="width: 10%;text-align: center;">
                           <p><u>ប្រាក់ដើម</u></p>
                        </td>
						<td style="width: 10%;text-align: center;">
                            <p><u>សរុបទឹកប្រាក់</u></p>
                        </td>
                        <td style="width: 18%;text-align: center;">
                            <p><u>សមតុល្យ</u></p>
                        </td>
                        <td style="width: 10%;text-align: center;">
                            <p><u>ការទូរទាត់</u></p>
                        </td>
						 <td style="width: 10%;text-align: center;">
                            <p><u>បញ្ចុះតម្លៃ</u></p>
                        </td>
						<td style="width: 10%;text-align: center;">
                            <p><u>នៅសល់</u></p>
                        </td>
						<td style="width: 10%;text-align: center;">
                            <p><u>ថ្ងៃទូរទាត់</u></p>
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
                            
                            if(array($loan)){
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
										$payment_card	 = ($pt->payment - $pt->paid_amount);
										$balance_pay_card= ($pt->payment - $payment_card);
										
                                   	if($paid > 0){								 
										echo '<tr class="row-data" '.(($pt->paid_amount > 0)? 'style="pointer-events:none;" disabled="disabled"':'').'>
											<td class="t_c" style="padding-left:5px; padding-right: 5px; height: 25px;text-align:center;">'. $pt->period .'</td>
											<td class="t_c" style="padding-left:5px; padding-right:5px;text-align:center;">'. $this->erp->hrsd($pt->dateline) .'</td>
											<td class="t_r" style="padding-left:5px; padding-right:5px;text-align:center;">'. $interests .'</td>';
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
										echo '<td class="t_r" style="padding-left:5px;padding-right:5px;text-align:center;">'. $this->erp->formatMoney($pt->principle) .'</td>
											  <td>'.$this->erp->formatMoney($pt->payment).'</td>
											  <td class="t_r" style="padding-left:5px;padding-right:5px;text-align:center;">'. $this->erp->formatMoney($balances) .'</td>
											  <td>'.$this->erp->formatMoney($payment_card).'</td>
											  <td>'.$this->erp->formatMoney($pt->discount).'</td>
											  <td>'.$this->erp->formatMoney($balance_pay_card).'</td>
											  <td>'.$this->erp->hrsd($pt->paid_date).'</td>'; 
                                    }   
                                }
                            }
                        ?>
             </table>
             <br>
        </div>

            <div class="row">
                <div class="col-xs-12">
                    <?php
                        if ($inv->note || $inv->note != "") { ?>
                            <div class="well well-sm">
                                <p class="bold"><?= lang("note"); ?>:</p>
                                <div><?= $this->erp->decode_html($inv->note); ?></div>
                            </div>
                        <?php
                        }
                        if ($inv->staff_note || $inv->staff_note != "") { ?>
                            <div class="well well-sm staff_note">
                                <p class="bold"><?= lang("staff_note"); ?>:</p>
                                <div><?= $this->erp->decode_html($inv->staff_note); ?></div>
                            </div>
                        <?php } ?>
                </div>

            </div>
           
			<div id="popup" ></div>
        </div>
    </div>
</div>
