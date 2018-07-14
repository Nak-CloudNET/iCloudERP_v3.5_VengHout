<?php
function product_name($name)
{
    return character_limiter($name, (isset($pos_settings->char_per_line) ? ($pos_settings->char_per_line-8) : 35));
}

if ($modal) {
    echo '<div class="modal-dialog no-modal-header"><div class="modal-content"><div class="modal-body"><button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i></button>';
} else { ?>
    <!doctype html>
    <html>
    <head>
        <meta charset="utf-8">
        <title><?= $page_title . " " . lang("no") . " " . $inv->id; ?></title>
        <base href="<?= base_url() ?>"/>
        <meta http-equiv="cache-control" content="max-age=0"/>
        <meta http-equiv="cache-control" content="no-cache"/>
        <meta http-equiv="expires" content="0"/>
        <meta http-equiv="pragma" content="no-cache"/>
        <link rel="shortcut icon" href="<?= $assets ?>images/icon.png"/>
        <link rel="stylesheet" href="<?= $assets ?>styles/theme.css" type="text/css"/>
        <style type="text/css" media="all">
            body {
                color: #000;
				font-size:13px !important;
            }

            #wrapper {
                max-width: 480px;
                margin: 0 auto;
                padding-top: 20px;
            }

            .btn {
                border-radius: 0;
                margin-bottom: 5px;
            }

            h3 {
                margin: 5px 0;
            } 
			.text-center {
				text-align:center;
			}
			
			.item td {
				border-bottom: 1px solid #000;
			}
			.receipt > thead > tr > th {
				font-size: 15px;
				background-color:#fff !important;color:#000 !important;
				-webkit-print-color-adjust: exact; 
				-moz-print-color-adjust: exact;
				-ms-print-color-adjust:exact;
				print-color-adjust:exact;
				color-adjust:exact;
				-webkit-color-adjust:exact;
				-moz-color-adjust:exact;
				-ms-color-adjust:exact;
			}

            @media print {
                .no-print {
                    display: none;
                }

                #wrapper {
                    /*max-width: 480px;*/
                    width: 95% !important;
                    /*min-width: 250px;*/
                    margin: 0 auto !important;
					padding: 0 !important;
					font-size: 10px !important;
                }
				.modal-content, .modal-body{
					border-bottom: none !important;
					border-top: none !important;
					border-right: none !important;
					border-left: none !important;
				}
				thead tr th {
					font-size: 10px !important;
				}
				
				tbody {
					font-size: 10px !important;
				}

				img {
					padding-right: 20px !important;
				}

			}
        </style>

    </head>

    <body>

<?php } ?>


<div id="wrapper">
    <div id="receiptData">
    <div class="no-print">
        <?php if ($message) { ?>
            <div class="alert alert-success">
                <button data-dismiss="alert" class="close" type="button">×</button>
                <?= is_array($message) ? print_r($message, true) : $message; ?>
            </div>
        <?php } ?>
    </div>
    <div id="receipt-data">
        <div class="text-center">
            <button class="btn btn-xs btn-default no-print pull-left" onclick="window.print()"><i class="fa fa-print"></i>&nbsp;<?= lang("print"); ?></button>
            <?php if ($Settings->system_management == 'project') { ?>
				<img src="<?= base_url() . 'assets/uploads/logos/' . $Settings->logo2; ?>" alt="<?= $Settings->site_name; ?>" style="padding-right: 50px" >
			<?php } else { ?>
				<img src="<?= base_url() . 'assets/uploads/logos/' . $biller->logo; ?>" alt="<?= $biller->company; ?>">
			<?php } ?>
			<!--<img style="width:100%;" src="<?= base_url() . 'assets/Aylogo/logo501.jpg'; ?>"> -->
			<!--<h3 style="text-transform:uppercase;"><?= $biller->company != '-' ? $biller->company : $biller->name; ?></h3>-->
            <?php
            echo "<p>#" . $biller->address . " " . $biller->city . " " . $biller->postal_code . " " . $biller->state . " " . $biller->country;
            echo ($biller->phone ? lang('tel').': '.$biller->phone : '').'<br>'.($biller->email ? lang('email').': '.$biller->email : '');
            // echo "lang("tel") . ": " . $biller->phone .",&nbsp;&nbsp;".lang('email'). " : " . $biller->email;"
			
            ?>
			<!--<div style="font-size:13px;">#138E0, St. 13. Sangkat Phsa kandal Phnom Penh<br/>Tel: 092/093/069 311 138 www.a-ycollection.com</div>
			<hr style="border: 1px solid black; margin-top: 10px; margin-bottom: 10px;"/>-->
			
			<div style="font-size:11px;">
				<table style="width: 100%;">				
					<tbody style="font-size: 12px;">						
						<tr style="text-align:center;">	
							<td style="vertical-align:middle;text-align:left;padding:3px;">អតិថិជន</td>							
							<td style="vertical-align:middle;text-align:left;"><?= $inv->customer; ?></td>
							<td style="vertical-align:middle;text-align:left;">លេខរង់ចាំ </td>
							<td style="vertical-align:middle;text-align:right;"><?= $inv->queue; ?></td>
						</tr>
						<tr style="text-align:center;">	
							<td style="vertical-align:middle;text-align:left;padding:3px;">អត្រាប្ដូរប្រាក់</td>							
							<td style="vertical-align:middle;text-align:left;">$1 = <?= $inv->other_cur_paid_rate. '  ៛'; ?></td>
							<td style="vertical-align:middle;text-align:left;">ថ្ងៃ&#8203;ខែឆ្នាំ</td>
							<td style="vertical-align:middle;text-align:right;"><?= $this->erp->hrsd($inv->date) ?></td>	
							
						</tr>
						
						<tr style="text-align:center;">	
							
							<td style="vertical-align:middle;text-align:left;padding:3px;">អ្នកលក់</td>							
							<td style="vertical-align:middle;text-align:left;"><?= $inv->username ?></td>
							<td style="vertical-align:middle;text-align:left;">លេខវិក័យបត្រ</td>
							<td style="vertical-align:middle;text-align:right;"><?= $inv->reference_no ?></td>							
						</tr>						
					</tbody>				
				</table>
			</div>
			
            <?php
			$total_disc = 0;
			if(is_array($rows)){
				foreach ($rows as $d) {				
					if($d->discount != 0){
						$total_disc = $d->discount;
					}
				}
			}
            ?>
			
            <div style="clear:both;"></div>
			
			<style>
			.no_border_btm tr td{
				border:none !important;
			}
			</style>
			
            <table class="table-condensed receipt no_border_btm" style="width:100%;">
				<thead>
					<tr style="border:1px dotted black !important;">
						<th><?= lang("no"); ?></th>
						<th><?= lang("description"); ?></th>
						<!--<th style="color:white !important;"><?= lang("serial"); ?></th>-->
						<th style="text-align:center;"><?= lang("qty"); ?></th>
						<th style="text-align:right;"><?= lang("Price"); ?></th>
						<?php if ($inv->order_discount != 0 || $total_disc != '') {
							echo '<th>'.lang('discount').'</th>';
						} ?>
						<th style="padding-left:10px;padding-right:10px;text-align:right;"><?= lang("amount"); ?> </th>
					</tr>
				</thead>
                <tbody style="border-bottom:2px solid black;">
                <?php
					$r = 1;
					$m_us = 0;
					$total_quantity = 0;
					$tax_summary = array();
					$sub_total=0;
					if (is_array($rows)) {
					foreach ($rows as $row) {
                        $free = lang('free');

						//$this->erp->print_arrays($row);
						if (isset($tax_summary[$row->tax_code])) {
							$tax_summary[$row->tax_code]['items'] += $row->quantity;
							$tax_summary[$row->tax_code]['tax'] += $row->item_tax;
							$tax_summary[$row->tax_code]['amt'] += ($row->quantity * $row->net_unit_price) - $row->item_discount;
						} else {
							$tax_summary[$row->tax_code]['items'] = $row->quantity;
							$tax_summary[$row->tax_code]['tax'] = $row->item_tax;
							$tax_summary[$row->tax_code]['amt'] = ($row->quantity * $row->net_unit_price) - $row->item_discount;
							$tax_summary[$row->tax_code]['name'] = $row->tax_name;
							$tax_summary[$row->tax_code]['code'] = $row->tax_code;
							$tax_summary[$row->tax_code]['rate'] = $row->tax_rate;
						}

						echo '<tr ' . ($row->product_type === 'combo' ? '' : 'class="item"') . '>
								<td class="text-left">' . $r . "</td>";
						echo '	<td class="text-left">' . product_name($row->product_name) . ($row->variant ? ' (' . $row->variant . ')' : '') . ($row->product_noted ? ' <br/>(' . $row->product_noted . ')' : '') . '</td>';
						//echo '	<td style="text-align:left;">' . $row->serial_no . '</td>';
						echo '	<td class="text-center">' . $this->erp->formatQuantity($row->quantity) . '</td>';
						
						echo '	<td class="text-center"  style="text-align:right; width:65px !important">$ ' . $this->erp->formatMoney($row->unit_price) . '</td>';
					
						$colspan = 5;
						if ($inv->order_discount != 0 || $row->item_discount != 0) {
						echo '	<td class="text-center">';
							echo 	'<span>' ;
								if(strpos($row->discount, '%') !== false){
									echo $row->discount;
								}else{
									echo $row->discount;
								}
								
							echo '</span> ';
							$colspan = 6;
							$total_col = 4;
							echo '</td>';
						}else{
							if($total_disc != ''){
								echo '<td class="text-center"></td>';
								$colspan = 6;
								$total_col = 4;
							}else{
								$colspan = 5;
								$total_col = 3;
							}
						}
						echo '<td class="text-right">' . ($this->erp->formatMoney($row->subtotal) == 0 ? $free: '$ '.$this->erp->formatMoney($row->subtotal)) . '</td>';
						$sub_total+=$row->subtotal;
						$r++;
						$total_quantity += $row->quantity;
						
						if($row->product_type === 'combo')
						{
							$this->db->select('*, (select name from erp_products p where p.id = erp_combo_items.product_id) as p_name ');
							$this->db->where('erp_combo_items.product_id = "' . $row->product_id . '"');
							$comboLoop = $this->db->get('erp_combo_items');
							$c = 1;
							$cTotal = count($comboLoop->result());
							foreach ($comboLoop->result() as $val) {
							echo '<tr ' . ($c === $cTotal ? 'class="item"' : '') . '>';
								echo '<td></td>';
								echo '<td><span style="padding-right: 5px;">' . $c . '. ' . $val->p_name . '</span></td>';
								echo '<td class="text-center"></td>';
								echo '<td class="text-center"></td>';
								echo '<td></td>';
								echo '<td></td>';
							echo '</tr>';
								$c++;
							}
						}
					}}
                ?>
				
                </tbody>
                <tfoot>
                </tfoot>
            </table>
			<table style="width: 100%; margin-top: 5px;">
				<tr>
					<td style="text-align:left;width:25%;">សរុប</td>
					<td style="text-align:right;width:35%;">Sub Total (<?= $default_currency->code; ?>) :</td>
					<td style="text-align:right;">$ <?=$this->erp->formatMoney($sub_total)?></td>
				</tr>
				<tr>
					<td style="text-align:left;">ពន្ធ</td>
					<td style="text-align:right;width:35%;">Order Tax (<?= $default_currency->code; ?>) :</td>
					<td style="text-align:right;">$ <?=$this->erp->formatMoney($inv->order_tax)?></td>
				</tr>
				<tr>
					<td style="text-align:left;">បញ្ចុះតំលៃ</td>
					<td style="text-align:right;width:35%;">Discount (<?= $default_currency->code; ?>) :</td>
					<td style="text-align:right;">$ <?=$this->erp->formatMoney($inv->order_discount)?></td>
				</tr>
				<tr>
					<td style="text-align:left;">សរុបចុងក្រោយ</td>
					<td style="text-align:right;width:40%;">Grand Total (<?= $default_currency->code; ?>) :</td>
					<td style="text-align:right;">$ 
					<?php
					/*if ($inv->order_discount != 0 || $inv->order_tax != 0) { 
					echo $this->erp->formatMoney($inv->grand_total + $rounding); 
					}
					else { 
						 if ($inv->order_discount != 0 || $inv->order_tax != 0) {
							echo   $this->erp->formatMoney($inv->grand_total); 
						}
						if ($inv->shipping != 0) { 
							echo $this->erp->formatMoney($inv->shipping); 
						}			
					}*/
					echo $this->erp->formatMoney($inv->grand_total);
					?>
				
					</td>
				</tr>
				<!-- <?php
				if ($pos_paid < $inv->grand_total) { 
					if(count($payments) > 1){
						foreach($payments as $payment) {
							$us_paid=$this->erp->formatMoney($payment->pos_paid);
							if($inv->other_cur_paid){
							$riel_paid=$inv->other_cur_paid . '  ៛' ; 
							}else{}
						}
				}else{
					$us_paid=$this->erp->formatMoney($pos_paid); 
							if($inv->other_cur_paid){
					$riel_paid=$inv->other_cur_paid . '  ៛' ; 
							}else{}
					}
				}					
				?> -->
				
			</table>
			<table class="received" style="width:100%;margin-top: 5px;">
			<tr style="display:none;">
				<th></th>
				<th style="display:none;">
					<?php 
						if($exchange_rate_kh_c->rate == 0){
							
						}else{
						if($pos_settings->in_out_rate == 0 or $pos_settings->in_out_rate == ""){
							echo lang("exchange_rate"); ?> : <?php echo $exchange_rate_kh_c->rate?number_format($exchange_rate_kh_c->rate):0 .' ៛ |'; 
						}
						}
					?>
					<?= lang("qty"); ?>= (<?=$this->erp->formatQuantity($total_quantity)?>)
				</th>
				<th  style="display:none;"colspan="<?=$total_col?>" class="text-right"><?= lang("total"); ?></th>
				<th  style="display:none;"class="text-right"><?= $this->erp->formatMoney($inv->grand_total); ?></th>
			</tr>
				
                <?php
                if ($inv->order_tax != 0) {
                    echo '<tr  style="display:none;"><th colspan="' . $colspan . '" class="text-right">' . lang("tax") . '</th>';
					echo '<th style="display:none;" class="text-right">' . $this->erp->formatMoney($inv->order_tax) . '</th></tr>';
                }
               
				if($pos_settings->in_out_rate){
					$amount_kh_to_us = ($inv->other_cur_paid != null ? $inv->other_cur_paid/$outexchange_rate->rate : 0);
				}else{
					$amount_kh_to_us = ($inv->other_cur_paid != null ? $inv->other_cur_paid/$exchange_rate->rate : 0);
				}
				
                if ($pos_settings->rounding){ 
                    $round_total = $this->erp->roundNumber($inv->grand_total, $pos_settings->rounding);
                    $rounding = $this->erp->formatMoney($round_total - $inv->grand_total);
                ?>
                    <tr style="display:none;">
                        <th style="display:none;border-top:2px dotted #000; padding-right: 12px;" colspan="<?= $colspan ?>" class="text-right"><?= lang("rounding"); ?></th>
                        <th style="display:none;border-top:2px dotted #000" class="text-right"><?= $rounding; ?></th>
                    </tr>
					
					<?php if ($inv->order_discount != 0 || $inv->order_tax != 0) { ?>
                    <tr style="display:none;">
                        <th style="display:none;border-top:2px dotted #000;padding-right: 12px;" colspan="<?= $colspan ?>" class="text-right"><?= lang("grand_total"); ?></th>
                        <th style="display:none;border-top:2px dotted #000" class="text-right"><?= $this->erp->formatMoney($inv->grand_total + $rounding); ?></th>
                    </tr>
					
                <?php 
					}
				} else{ ?>
				<?php if ($inv->order_discount != 0 || $inv->order_tax != 0) { ?>
                    <tr style="display:none;">
                        <th style="border-top:2px dotted #000;padding-right: 12px;" colspan="<?= $colspan ?>" class="text-right"><?= lang("grand_total"); ?></th>
                        <th style="border-top:2px dotted #000" class="text-right"><?= $this->erp->formatMoney($inv->grand_total); ?></th>
                    </tr>
                <?php }
                        if ($inv->shipping != 0) { ?>
                    <tr style="display:none;">
                        <th style="border-top:2px dotted #000;padding-right: 12px;" colspan="<?= $colspan ?>" class="text-right"><?= lang("shipping"); ?></th>
                        <th style="border-top:2px dotted #000" class="text-right"><?= $this->erp->formatMoney($inv->shipping); ?></th>
                    </tr>
                <?php }
				}
				$pos_paid = 0;
				$pos_paidd = 0;
				$colspan = 0;
				if($payments){
					foreach($payments as $payment) {
						
						$pos_paid = $payment->pos_paid;
						if($pos_settings->in_out_rate){
							$pos_paid_other = ($payment->pos_paid_other != null ? $payment->pos_paid_other/$outexchange_rate->rate : 0);
						}else{
							$pos_paid_other = ($payment->pos_paid_other != null ? $payment->pos_paid_other/$exchange_rate->rate : 0);
						}
					}
					$pos_paidd = $pos_paid + $pos_paid_other;
				}
				//echo $payment->pos_paid; 
				$grand_totals=$inv->grand_total; 
				if($pos_paidd >= $grand_totals){
					 
					if(count($payments) > 1){
						//separate payments
					?>
					<tr>
						<th colspan="<?= $colspan + 1 ?>">
				<?php 
					foreach($payments as $payment) {
				?>
						<table style="width: 100%;">
						<caption style="float: left; padding-left: 13px;">
							<?= lang('paid_by').' '.lang($payment->paid_by);?>
						</caption>
						<tr>
							<th style="border-left:2px solid #000;border-top:2px solid #000;border-right:none;padding-right: 12px;width:64%;"  class="text-right">Received (USD) :</th>
							<th style="border-right:2px solid #000;border-top:2px solid #000;border-left:none;" class="text-right"><?= $this->erp->formatMoney($payment->pos_paid); ?></th>
						</tr>
						<?php
							if($inv->other_cur_paid){
						?>
						<tr>
							<th style="border-left:2px solid #000;border-bottom:2px solid #000;padding-right: 12px;width:64%;"  class="text-right">Received (Riel) :</th>
							<th style="border-bottom:2px solid #000;border-right:2px solid #000;border-left:none;" class="text-right"><?= $inv->other_cur_paid . '  ៛' ; ?></th>
						</tr>
						<?php
							}
						?>
					</table>
					
				<?php
					}
				?>
						</th>
					</tr>
					<?php 
					}else{
						
					
					if($inv->other_cur_paid)
					{
						$khr_paid = ($inv->other_cur_paid/$inv->other_cur_paid_rate);
					}else{
						$khr_paid = 0;
					}	
				?>
					<tr>
                        <th style="border-left:2px solid #000;border-top:2px solid #000;border-right:none;padding-right: 12px;width:64%;"  class="text-right">Received (<?= $default_currency->code; ?>) :</th>
                        <th style="border-right:2px solid #000;border-top:2px solid #000;border-left:none;" class="text-right"><?= $this->erp->formatMoney($inv->recieve_usd); ?></th>
                    </tr>
                    <tr>
                        <th style="border-left:2px solid #000;border-bottom:2px solid #000;border-right:none;padding-right: 12px;width:64%;"  class="text-right">Received (Riel) :</th>
                        <th style="border-bottom:2px solid #000;border-right:2px solid #000;border-left:none;" class="text-right"><?= $this->erp->formatMoney($inv->recieve_real) ; ?></th>
                    </tr>
					
					<?php 
					}
					if(count($payments) > 1){
						$pay = '';
						$pay_kh = '';
						foreach($payments as $payment){
							$pay += $payment->pos_paid;
							$pay_kh += $payment->pos_paid_other;
						}

						if((($pay + ($pay_kh / (($pos_settings->in_out_rate) ? $outexchange_rate->rate : $exchange_rate->rate))) - $grand_totals) != 0){
						
				?>

					<tr>
                        <th style="border-top:2px dotted #000;padding-right: 12px;" class="text-right"><?= lang("change_amount_us"); ?>:</th>
                        <th style="border-top:2px dotted #000" class="text-right">
						<?php
							echo $this->erp->formatMoney(($pay+$pay_kh) - $grand_totals);
							$total_us_b = $this->erp->formatMoney(($pay+$pay_kh) - $grand_totals);
							$m_us = $this->erp->fraction($total_us_b);
						?>
						</th>
                    </tr>
                    <tr>
                        <th style="border-top:2px dotted #000;padding-right: 12px;" colspan="<?= $colspan ?>" class="text-right"><?= lang("change_amount_kh"); ?></th>
						<th style="border-top:2px dotted #000" class="text-right"><?= number_format(round((($pay+$pay_kh) - $grand_totals)*(($pos_settings->in_out_rate) ? $outexchange_rate->rate:$exchange_rate->rate)), -3) . '៛' ; ?></th>
                    </tr>
				<?php
						}
					}else{
						// $this->erp->print_arrays($inv);
					if((($pos_paid+$amount_kh_to_us) - ($inv->recieve_usd - $grand_totals)) != 0 || ($this->erp->formatMoney((($pos_paid+$amount_kh_to_us) - $grand_totals) * $exchange_rate->rate)) != 0) { ?>
					<tr>
                        <th style="border-top:2px dotted #000;padding-right: 12px;" class="text-right"><?= lang("change_amount_us"); ?></th>
                        <th style="border-top:2px dotted #000" class="text-right">
						<?php
							echo $this->erp->formatMoney($payment->pos_balance);
							// echo $this->erp->formatMoney(($pos_paid+$amount_kh_to_us) - $grand_totals);
							$total_us_b = $this->erp->formatMoney(($pos_paid+$amount_kh_to_us) - $inv->grand_total);
							$m_us = $this->erp->fraction($total_us_b);
						?>
						</th>
                    </tr>
                    <tr>
                        <th style="border-top:2px dotted #000;padding-right: 12px;" class="text-right"><?= lang("change_amount_kh"); ?></th>
						<th style="border-top:2px dotted #000" class="text-right">
							<?= number_format(round($payment->pos_balance * $payment->pos_paid_other_rate)) ; ?>
							
						</th>
                    </tr>
				
					<?php
					}
					}
				}
                if ($pos_paidd < $grand_totals) {
					//separate payments
					if(count($payments) > 1){
				?>
					<tr>
						<th colspan="<?= $colspan + 2 ?>">
				<?php 
					foreach($payments as $payment) {
						if($payment->pos_paid>0){
				?>
					<table style="width:100%;">
						<caption style="float: left; padding-left: 13px;"><?= lang('paid_by').' '.lang($payment->paid_by);?></caption>
						<tr>
							<th style="border:2px solid #000;border-right:none;padding-right: 12px;width:81%;" colspan="<?= $colspan ?>" class="text-right">Received (<?= $default_currency->code; ?>):</th>
							<th style="border:2px solid #000;border-left:none;" class="text-right"><?= $this->erp->formatMoney($payment->pos_paid); ?></th>
						</tr>
						<?php
							if($inv->other_cur_paid){
						?>
						<tr>
							<th style="border:2px solid #000;border-right:none;padding-right: 12px;width:81%;" colspan="<?= $colspan ?>" class="text-right">Received (Riel):</th>
							<th style="border:2px solid #000;border-left:none;" class="text-right"><?= $inv->other_cur_paid . '  ៛' ; ?></th>
						</tr>
						<?php
							}else{}
						?>
					</table>
				<?php
						}
					}
				?>
						</th>
					</tr>
				<?php 
					}else{
				?>
					<tr>
                        <th style="border:2px solid #000;border-right:none;padding-right: 12px;" colspan="<?= $colspan ?>" class="text-right">Received (<?= $default_currency->code; ?>):</th>
                        <th style="border:2px solid #000;border-left:none;" class="text-right"><?= $this->erp->formatMoney($inv->paid); ?></th>
                    </tr>
					<?php
						if($inv->other_cur_paid){
					?>
                    <tr>
                        <th style="border:2px solid #000;border-right:none;padding-right: 12px;" colspan="<?= $colspan ?>" class="text-right">Received (Riel):</th>
                        <th style="border:2px solid #000;border-left:none;" class="text-right"><?= $inv->other_cur_paid . '  ៛' ; ?></th>
                    </tr>
					<?php
						}else{}
					?>
				<?php
					}
					if(count($payments) > 1){
						$pay = '';
						$pay_kh = '';
						foreach($payments as $payment) {
							$pay += $payment->pos_paid;
							$pay_kh += $payment->pos_paid_other;
						}
						if((($pay + ($pay_kh / (($pos_settings->in_out_rate) ? $outexchange_rate->rate:$exchange_rate->rate))) - $grand_totals) != 0){ 
				?>
					<tr>
                        <th style="border-top:2px dotted #000;padding-right: 12px;" colspan="<?= $colspan ?>" class="text-right"><?= lang("remaining_us"); ?></th>
                        <th style="border-top:2px dotted #000" class="text-right">
						<?php
							$money_kh = $pay_kh / (($pos_settings->in_out_rate) ? $outexchange_rate->rate:$exchange_rate->rate);
							echo $this->erp->formatMoney(abs(($pay+$money_kh) - $grand_totals));
							$total_us_b = $this->erp->formatMoney(($pay+$money_kh) - $grand_totals);
							$m_us = $this->erp->fraction($total_us_b);
						?>
						</th>
                    </tr>
                    <tr>
                        <th style="border-top:2px dotted #000;padding-right: 12px;" colspan="<?= $colspan ?>" class="text-right"><?= lang("remaining_kh"); ?></th>
                        <th style="border-top:2px dotted #000" class="text-right"><?= number_format(abs((($pay+$money_kh) - $grand_totals)*(($pos_settings->in_out_rate) ? $outexchange_rate->rate:$exchange_rate->rate))) . ' ៛' ; ?></th>
                    </tr>
				<?php
						}
					}else{
					if((($pos_paid+$amount_kh_to_us) - $grand_totals) != 0 || ($this->erp->formatMoney((($pos_paid+$amount_kh_to_us) - $grand_totals)*(($pos_settings->in_out_rate) ? $outexchange_rate->rate:$exchange_rate->rate) )) != 0){ ?>
                    <tr>
                        <th style="border-top:2px dotted #000;padding-right: 12px;" colspan="<?= $colspan ?>" class="text-right"><?= lang("remaining_us"); ?></th>
                        <th style="border-top:2px dotted #000" class="text-right">
						<?php
							echo $this->erp->formatMoney(abs(($pos_paid+$amount_kh_to_us) - $grand_totals));
							$total_us_b = $this->erp->formatMoney(($pos_paid+$amount_kh_to_us) - $grand_totals);
							$m_us = $this->erp->fraction($total_us_b);
						?>
						</th>
                    </tr>
                    <tr>
                        <th style="border-top:2px dotted #000;padding-right: 12px;" colspan="<?= $colspan ?>" class="text-right"><?= lang("remaining_kh"); ?></th>
                        <th style="border-top:2px dotted #000" class="text-right"><?= number_format(abs((($pos_paid+$amount_kh_to_us) - $grand_totals)*(($pos_settings->in_out_rate) ? $outexchange_rate->rate:$exchange_rate->rate))) . ' ៛' ; ?></th>
                    </tr>
				
				<?php }
					}
				}
				?>
			
			</table>
			<div style="width:100%;text-align:left;margin-top:10px;display:none">
				ពិន្ទុចាស់ - Old Point 	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <b></b><br/>
				ពិន្ទុសរុប - Total Point 	&nbsp;&nbsp;: <b></b>
			</div>
			<div style="text-align:center;font-size:11px;margin-top:10px;">
				<?=  $biller->invoice_footer; ?> <br>	
				~ ~ ~ <b>CloudNet</b> &nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size:12px;">www.cloudnet.com.kh</span> ~ ~ ~
			</div>
			<!-- <div class="row-content no-print">
				<div class="col-md-12" style="margin:15px 0px;">
					<a href="<?=base_url()?>sales/sales_invoice_a5/<?=$sid?>" target="_blank">
					<button type="button" class="btn btn-primary btn-default     pull-left">
					<i class="fa fa-print"></i> <?= lang('invoice_a5'); ?>
					</button>
				 </div>
			</div> -->
			<!--<div class="alert alert-success">
				<?php $rate = (($pos_settings->in_out_rate) ? $outexchange_rate->rate:$exchange_rate->rate); ?>
				<h4>US <?=$this->erp->fraction($pos_paid)?> = <?=number_format($this->erp->fraction($pos_paid)*$rate)?> ៛</h4>
			</div>-->
            <?php
            if ($Settings->invoice_view == 1) {
                if (!empty($tax_summary)) {
                    echo '<h4 style="font-weight:bold;">' . lang('tax_summary') . '</h4>';
                    echo '<table class="table table-condensed"><thead><tr><th>' . lang('name') . '</th><th>' . lang('code') . '</th><th>' . lang('qty') . '</th><th>' . lang('tax_excl') . '</th><th>' . lang('tax_amt') . '</th></tr></td><tbody>';
                    foreach ($tax_summary as $summary) {
                        echo '<tr><td>' . $summary['name'] . '</td><td class="text-center">' . $summary['code'] . '</td><td class="text-center">' . $this->erp->formatQuantity($summary['items']) . '</td><td class="text-right">' . $this->erp->formatMoney($summary['amt']) . '</td><td class="text-right">' . $this->erp->formatMoney($summary['tax']) . '</td></tr>';
                    }
                    echo '</tbody></tfoot>';
                    echo '<tr><th colspan="4" class="text-right">' . lang('total_tax_amount') . '</th><th class="text-right">' . $this->erp->formatMoney($inv->product_tax) . '</th></tr>';
                    echo '</tfoot></table>';
                }
            }
            ?>

            <?= $inv->note ? '<p class="text-left"><strong>'.lang("note").': '. $this->erp->decode_html($inv->note) . '</strong></p>' : ''; ?>
            <?= $inv->staff_note ? '<p class="no-print"><strong>' . lang('staff_note') . ':</strong> ' . $this->erp->decode_html($inv->staff_note) . '</p>' : ''; ?>
           
        </div>
        <?php $this->erp->qrcode('link', urlencode(site_url('pos/view/' . $inv->id)), 2); ?>
        <div class="text-center">
		<?php 
		if($pos->display_qrcode) {
		?>
			<img
                src="<?= base_url() ?>assets/uploads/qrcode<?= $this->session->userdata('user_id') ?>.png"
                alt="<?= $inv->reference_no ?>"/></div>
        <?php $br = $this->erp->save_barcode($inv->reference_no, 'code39'); ?>
        <div class="text-center"><img
                src="<?= base_url() ?>assets/uploads/barcode<?= $this->session->userdata('user_id') ?>.png"
                alt="<?= $inv->reference_no ?>"/></div>
		<?php } ?>
        <div style="clear:both;"></div>
    </div>
<?php if ($modal) {
    echo '</div></div></div></div>';
} else { ?>
<div id="buttons" style="padding-top:10px; text-transform:uppercase;" class="no-print">
    <hr>
    <?php if ($message) { ?>
    <div class="alert alert-success">
        <button data-dismiss="alert" class="close" type="button">×</button>
        <?= is_array($message) ? print_r($message, true) : $message; ?>
    </div>
<?php } ?>
	<span class="pull-right col-xs-12">
        <a href="<?=base_url()?>pos/jones_invoice/<?=$sid?>" target="_blank" class="btn btn-block btn-primary" ><?= lang("jones_the_grocer"); ?></a>
    </span>
    <span class="pull-right col-xs-12">
        <a href="<?=base_url()?>pos/view_dragon_fly/<?=$sid?>" target="_blank" class="btn btn-block btn-primary" ><?= lang("dragon_fly"); ?></a>
    </span>
    <span class="pull-right col-xs-12">
        <a href="<?=base_url()?>pos/view_cabon/<?=$sid?>" target="_blank" class="btn btn-block btn-primary" ><?= lang("tree_try"); ?></a>
    </span>
    <span class="pull-right col-xs-12">
        <a href="<?=base_url()?>pos/cabon_siv_heng/<?=$sid?>" target="_blank" class="btn btn-block btn-primary" ><?= lang("siv_heng"); ?></a>
    </span>
    <span class="pull-right col-xs-12">
        <a href="<?=base_url()?>pos/chp_invoice/<?=$sid?>" target="_blank" class="btn btn-block btn-primary" ><?= lang("chp_invoice"); ?></a>
    </span>
    <?php if ($pos_settings->java_applet) { ?>
        <span class="col-xs-12"><a class="btn btn-block btn-primary" onClick="printReceipt()"><?= lang("print"); ?></a></span>
        <span class="col-xs-12"><a class="btn btn-block btn-info" type="button" onClick="openCashDrawer()">Open Cash
                Drawer</a></span>
        <div style="clear:both;"></div>
    <?php } else { ?>
        <span class="pull-right col-xs-12">
        <a href="javascript:window.print()" id="web_print" class="btn btn-block btn-primary"
           onClick="window.print();return false;"><?= lang("web_print"); ?></a>
    </span>
    <?php } ?>
    <span class="pull-right col-xs-12">
        <a href="<?=base_url()?>sales/print_/<?=$sid?>" target="_blank" class="btn btn-block btn-primary" ><?= lang("print_a4"); ?></a>
    </span>
	<span class="pull-right col-xs-12">
        <a href="<?=base_url()?>sales/p_invoice/<?=$sid?>" target="_blank" class="btn btn-block btn-primary" ><?= lang("print_invoice"); ?></a>
    </span>	
	<span class="pull-right col-xs-12">
        <a href="<?=base_url()?>sales/print_rks/<?=$sid?>" target="_blank" class="btn btn-block btn-primary" ><?= lang("print_a4_rks"); ?></a>
    </span>
	<span class="pull-right col-xs-12">
        <a href="<?=base_url()?>sales/invoice_landscap_a5/<?=$sid?>" target="_blank" class="btn btn-block btn-primary" ><?= lang("print_a5"); ?></a>
    </span>
    <!--<span class="pull-right col-xs-12">
        <a href="<?=base_url()?>sales/tax_invoice/<?=$sid?>" target="_blank" class="btn btn-block btn-primary" ><?= lang("print_tax_invoice"); ?></a>
    </span>	-->
	<span class="pull-right col-xs-12">
        <a href="<?=base_url()?>sales/print_receipt/<?=$sid?>" target="_blank" class="btn btn-block btn-primary" ><?= lang("print_receipt"); ?></a>
    </span>
	<?php if(isset($payments->id) != '') { ?>
	<span class="pull-right col-xs-12">
        <a href="<?=base_url()?>sales/cash_receipt/<?=$payment->id?>" target="_blank" class="btn btn-block btn-primary" ><?= lang("cash_receipt"); ?></a>
    </span>
	<?php } ?>
	<!--<span class="pull-right col-xs-12">
        <a href="<?=base_url()?>sales/print_cabon/<?=$sid?>" target="_blank" class="btn btn-block btn-primary" ><?= lang("print_Cabon"); ?></a>
    </span>-->
    <span class="pull-left col-xs-12"><a class="btn btn-block btn-success" href="#" id="email"><?= lang("email"); ?></a></span>

    <span class="col-xs-12">
        <a class="btn btn-block btn-warning" href="<?= site_url('pos'); ?>"><?= lang("back_to_pos"); ?></a>
    </span>
    <?php if (!$pos_settings->java_applet) { ?>
        <div style="clear:both;"></div>
        <div class="col-xs-12" style="background:#F5F5F5; padding:10px;">
            <p style="font-weight:bold;">Please don't forget to disble the header and footer in browser print
                settings.</p>

            <p style="text-transform: capitalize;"><strong>FF:</strong> File &gt; Print Setup &gt; Margin &amp;
                Header/Footer Make all --blank--</p>

            <p style="text-transform: capitalize;"><strong>chrome:</strong> Menu &gt; Print &gt; Disable Header/Footer
                in Option &amp; Set Margins to None</p></div>
    <?php } ?>
    <div style="clear:both;"></div>

</div>

</div>
<canvas id="hidden_screenshot" style="display:none;">

</canvas>
<div class="canvas_con" style="display:none;"></div>
<script type="text/javascript" src="<?= $assets ?>pos/js/jquery-1.7.2.min.js"></script>
<?php if ($pos_settings->java_applet) {
        function drawLine()
        {
            $size = $pos_settings->char_per_line;
            $new = '';
            for ($i = 1; $i < $size; $i++) {
                $new .= '-';
            }
            $new .= ' ';
            return $new;
        }

        function printLine($str, $sep = ":", $space = NULL)
        {
            $size = $space ? $space : $pos_settings->char_per_line;
            $lenght = strlen($str);
            list($first, $second) = explode(":", $str, 2);
            $new = $first . ($sep == ":" ? $sep : '');
            for ($i = 1; $i < ($size - $lenght); $i++) {
                $new .= ' ';
            }
            $new .= ($sep != ":" ? $sep : '') . $second;
            return $new;
        }

        function printText($text)
        {
            $size = $pos_settings->char_per_line;
            $new = wordwrap($text, $size, "\\n");
            return $new;
        }

        function taxLine($name, $code, $qty, $amt, $tax)
        {
            return printLine(printLine(printLine(printLine($name . ':' . $code, '', 18) . ':' . $qty, '', 25) . ':' . $amt, '', 35) . ':' . $tax, ' ');
        }

        ?>

        <script type="text/javascript" src="<?= $assets ?>pos/qz/js/deployJava.js"></script>
        <script type="text/javascript" src="<?= $assets ?>pos/qz/qz-functions.js"></script>
        <script type="text/javascript">
            deployQZ('themes/<?=$Settings->theme?>/assets/pos/qz/qz-print.jar', '<?= $assets ?>pos/qz/qz-print_jnlp.jnlp');
            usePrinter("<?= $pos_settings->receipt_printer; ?>");
            <?php /*$image = $this->erp->save_barcode($inv->reference_no);*/ ?>
            function printReceipt() {
                //var barcode = 'data:image/png;base64,<?php /*echo $image;*/ ?>';
                receipt = "";
                receipt += chr(27) + chr(69) + "\r" + chr(27) + "\x61" + "\x31\r";
                receipt += "<?= $biller->company; ?>" + "\n";
                receipt += " \x1B\x45\x0A\r ";
                receipt += "<?= $biller->address . " " . $biller->city . " " . $biller->country; ?>" + "\n";
                receipt += "<?= $biller->phone; ?>" + "\n";
                receipt += "<?php if ($pos_settings->cf_title1 != "" && $pos_settings->cf_value1 != "") { echo printLine($pos_settings->cf_title1 . ": " . $pos_settings->cf_value1); } ?>" + "\n";
                receipt += "<?php if ($pos_settings->cf_title2 != "" && $pos_settings->cf_value2 != "") { echo printLine($pos_settings->cf_title2 . ": " . $pos_settings->cf_value2); } ?>" + "\n";
                receipt += "<?=drawLine();?>\r\n";
                receipt += "<?php if($Settings->invoice_view == 1) { echo lang('tax_invoice'); } ?>\r\n";
                receipt += "<?php if($Settings->invoice_view == 1) { echo drawLine(); } ?>\r\n";
                receipt += "\x1B\x61\x30";
                receipt += "<?= printLine(lang("reference_no") . ": " . $inv->reference_no) ?>" + "\n";
                receipt += "<?= printLine(lang("sales_person") . ": " . $biller->name); ?>" + "\n";
                receipt += "<?= printLine(lang("customer") . ": " . $inv->customer); ?>" + "\n";
                receipt += "<?= printLine(lang("date") . ": " . date($dateFormats['php_ldate'], strtotime($inv->date))) ?>" + "\n\n";
                receipt += "<?php $r = 1;
            foreach ($rows as $row): ?>";
                receipt += "<?= "#" . $r ." "; ?>";
                receipt += "<?= printLine(product_name(addslashes($row->product_name)).($row->variant ? ' ('.$row->variant.')' : '').":".$row->tax_code, '*'); ?>" + "\n";
                receipt += "<?= printLine($this->erp->formatQuantity($row->quantity)."x".$this->erp->formatMoney($row->net_unit_price+($row->item_tax/$row->quantity)) . ":  ". $this->erp->formatMoney($row->subtotal), ' ') . ""; ?>" + "\n";
                receipt += "<?php $r++;
            endforeach; ?>";
                receipt += "\x1B\x61\x31";
                receipt += "<?=drawLine();?>\r\n";
                receipt += "\x1B\x61\x30";
                receipt += "<?= printLine(lang("total") . ": " . $this->erp->formatMoney($inv->total+$inv->product_tax)); ?>" + "\n";
                <?php if ($inv->order_tax != 0) { ?>
                receipt += "<?= printLine(lang("tax") . ": " . $this->erp->formatMoney($inv->order_tax)); ?>" + "\n";
                <?php } ?>
                <?php if ($inv->total_discount != 0) { ?>
                receipt += "<?= printLine(lang("discount") . ": (" . $this->erp->formatMoney($inv->product_discount).") ".$this->erp->formatMoney($inv->order_discount)); ?>" + "\n";
                <?php } ?>
                <?php if($pos_settings->rounding) { ?>
                receipt += "<?= printLine(lang("rounding") . ": " . $rounding); ?>" + "\n";
                receipt += "<?= printLine(lang("grand_total") . ": " . $this->erp->formatMoney($this->erp->roundMoney($inv->grand_total+$rounding))); ?>" + "\n";
                <?php } else { ?>
                receipt += "<?= printLine(lang("grand_total") . ": " . $this->erp->formatMoney($inv->grand_total)); ?>" + "\n";
                <?php } ?>
                <?php if($inv->paid < $inv->grand_total) { ?>
                receipt += "<?= printLine(lang("paid_amount") . ": " . $this->erp->formatMoney($inv->paid)); ?>" + "\n";
                receipt += "<?= printLine(lang("due_amount") . ": " . $this->erp->formatMoney($inv->grand_total-$inv->paid)); ?>" + "\n\n";
                <?php } ?>
                <?php
                if($payments) {
                    foreach($payments as $payment) {
                        if ($payment->paid_by == 'cash' && $payment->pos_paid) { ?>
                receipt += "<?= printLine(lang("paid_by") . ": " . lang($payment->paid_by)); ?>" + "\n";
                receipt += "<?= printLine(lang("amount") . ": " . $this->erp->formatMoney($payment->pos_paid)); ?>" + "\n";
                receipt += "<?= printLine(lang("change") . ": " . ($payment->pos_balance > 0 ? $this->erp->formatMoney($payment->pos_balance) : 0)); ?>" + "\n";
                <?php  } if (($payment->paid_by == 'CC' || $payment->paid_by == 'ppp' || $payment->paid_by == 'stripe') && $payment->cc_no) { ?>
                receipt += "<?= printLine(lang("paid_by") . ": " . lang($payment->paid_by)); ?>" + "\n";
                receipt += "<?= printLine(lang("amount") . ": " . $this->erp->formatMoney($payment->pos_paid)); ?>" + "\n";
                receipt += "<?= printLine(lang("card_no") . ": xxxx xxxx xxxx " . substr($payment->cc_no, -4)); ?>" + "\n";
                <?php } if ($payment->paid_by == 'Cheque' && $payment->cheque_no) { ?>
                receipt += "<?= printLine(lang("paid_by") . ": " . lang($payment->paid_by)); ?>" + "\n";
                receipt += "<?= printLine(lang("amount") . ": " . $this->erp->formatMoney($payment->pos_paid)); ?>" + "\n";
                receipt += "<?= printLine(lang("cheque_no") . ": " . $payment->cheque_no); ?>" + "\n";
                <?php if ($payment->paid_by == 'other' && $payment->amount) { ?>
                receipt += "<?= printLine(lang("paid_by") . ": " . lang($payment->paid_by)); ?>" + "\n";
                receipt += "<?= printLine(lang("amount") . ": " . $this->erp->formatMoney($payment->amount)); ?>" + "\n";
                receipt += "<?= printText(lang("payment_note") . ": " . $payment->note); ?>" + "\n";
                <?php }
            }

        }
    }

    if($Settings->invoice_view == 1) {
        if(!empty($tax_summary)) {
    ?>
                receipt += "\n" + "<?= lang('tax_summary'); ?>" + "\n";
                receipt += "<?= taxLine(lang('name'),lang('code'),lang('qty'),lang('tax_excl'),lang('tax_amt')); ?>" + "\n";
                receipt += "<?php foreach ($tax_summary as $summary): ?>";
                receipt += "<?= taxLine($summary['name'],$summary['code'],$this->erp->formatQuantity($summary['items']),$this->erp->formatMoney($summary['amt']),$this->erp->formatMoney($summary['tax'])); ?>" + "\n";
                receipt += "<?php endforeach; ?>";
                receipt += "<?= printLine(lang("total_tax_amount") . ":" . $this->erp->formatMoney($inv->product_tax)); ?>" + "\n";
                <?php
                    }
                }
                ?>
                receipt += "\x1B\x61\x31";
                receipt += "\n" + "<?= $biller->invoice_footer ? printText(str_replace(array('\n', '\r'), ' ', $this->erp->decode_html($biller->invoice_footer))) : '' ?>" + "\n";
                receipt += "\x1B\x61\x30";
                <?php if(isset($pos_settings->cash_drawer_cose)) { ?>
                print(receipt, '', '<?=$pos_settings->cash_drawer_cose;?>');
                <?php } else { ?>
                print(receipt, '', '');
                <?php } ?>

            }

        </script>
    <?php } ?>
            <script type="text/javascript">			
                $(document).ready(function () {
                    $('#email').click(function () {
                        var email = prompt("<?= lang("email_address"); ?>", "<?= $customer->email; ?>");
                        if (email != null) {
                            $.ajax({
                                type: "post",
                                url: "<?= site_url('pos/email_receipt') ?>",
                                data: {<?= $this->security->get_csrf_token_name(); ?>: "<?= $this->security->get_csrf_hash(); ?>", email: email, id: <?= $inv->id; ?>},
                                dataType: "json",
                                success: function (data) {
                                    alert(data.msg);
                                },
                                error: function () {
                                    alert('<?= lang('ajax_request_failed'); ?>');
                                    return false;
                                }
                            });
                        }
                        return false;
                    });
                });
         <?php if (!$pos_settings->java_applet) { ?>
			$(window).load(function () {
				window.print();
				<?php
				if($Settings->auto_print){?>
					setTimeout('window.close()', 5000);
					document.location.href = "<?=base_url()?>pos"; 
				<?php }	?>
			});
    <?php } ?>
            </script>
</body>
</html>
<?php } ?>