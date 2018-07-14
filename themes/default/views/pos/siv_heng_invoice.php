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
				/*border-bottom: 1px solid #000;*/
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
			.tbl-foonter tr td{
				font-weight: bold;
			}

            @media print {
                .no-print {
                    display: none;
                }
                #bg{
					background-color:#414141 !important;
				}
				#bg td {
					color: #fff !important;
				}
                #wrapper {
                    /*max-width: 480px;*/
                    width: 95% !important;
                    /*min-width: 250px;*/
                    margin: 0 auto !important;
					padding: 0 !important;
					font-size: 10px !important;
					margin-top: 20px !important;
                }
				
				table thead tr td {
					font-size: 10px !important;
					/*font-weight: bold !important;*/
				}
				
				table tbody tr td {
					font-size: 10px !important;
				}

				img {
					width: 100% !important;
					margin-left: -50px;
					margin-top: -10px;
				}
				#footer-text p {
					font-size: 8px !important;
				}
				h2 {
					font-size: 11px !important;
					margin-top: -5px;
				}
				h5 {
					margin-top: 10px !important;
					text-align: right;
				}
				h3 {
					font-size: 10px !important;
					margin-top: -10px;
				}
				.footer-invioce, #contact, .tbl-foonter tr td, h5 {
					font-size: 9px !important;
				}
				#contact {
					margin-top: -15px !important;
				}
				.barcode #img-barcode {
					margin-top: 10px;
					margin-left: 8px;
					width: 70% !important;
				}
				/*.barcode #img-qrCode {
					margin-top: 10px;
					margin-left: 8px;
					width: 12% !important;
				}*/
				.tbl-foonter tr td{
				font-weight: normal !important;
				}
				#total {
					background-color: #d8d8d8 !important;
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
             <div class="row">
	            <div class="col-xs-12">
	            	<div class="col-xs-4">
	            		<?php if($biller->logo){?>
							<img src="<?= base_url() . 'assets/uploads/logos/' . $biller->logo; ?>" alt="<?= $biller->company; ?>" style="width: 85%;">
	            		<?php }?>
	            	</div>
	            	<div class="col-xs-4">
	            		<h2 style="font-family:'Khmer OS Muol Light"><?=lang("ស៊ីវហេង")?></h2>
	            		<h3>SIV HENG</h3>
	            	</div>
	            	<div class="col-xs-4">
	            		<h5 style="margin-top: 70px;">Orginal</h5>
	            	</div>
				</div>
			 </div>
			 <div class="row">
            	<div class="col-xs-12 text-center" id="contact">
            		<?php
		           		 echo "<p>". lang("Phone/Fax") . " : " . $biller->phone."</p>";
		            ?>
            	</div>
			 </div>
			 
			<!--<div style="font-size:13px;">#138E0, St. 13. Sangkat Phsa kandal Phnom Penh<br/>Tel: 092/093/069 311 138 www.a-ycollection.com</div>
			<hr style="border: 1px solid black; margin-top: 10px; margin-bottom: 10px;"/>-->
			
			<div style="font-size:11px;">
				<table style="width: 100%;">				
					<tbody style="font-size: 12px;">						
						<tr style="text-align:center;">	
							<td style="vertical-align:middle;text-align:left;width: 80px">​<?=lang(អ្នកគិតលុយ)?> :</td>
							<td style="vertical-align:middle;text-align:left;"><?= $inv->username ?></td>
							<td style="vertical-align:middle;text-align:left;">​</td>
							<td style="vertical-align:middle;text-align:left;"></td>
							<td class="date-time" style="vertical-align:middle;text-align:right;"><?=lang("កាលបរិច្ឆេទ")?>:</td>
							<td style="vertical-align:middle;text-align:right; width: 95px;"><?= $this->erp->hrld($inv->date) ?></td>
						</tr>
						<tr style="text-align:center;">	
						    <td style="vertical-align:middle;text-align:left;width: 67px;"><?=lang("អតិថិជន")?> :</td>
							<td style="vertical-align:middle;text-align:left;">general</td>
						</tr>						
					</tbody>				
				</table>
			</div>
            <?php
			$total_disc = 0;
			foreach ($rows as $d) {
				
				if($d->discount != 0){
					$total_disc = $d->discount;
				}
			}
            ?>
			
            <div style="clear:both;"></div>
			
			<style>
			.no_border_btm tr td{
				border:none !important;
			}
			</style>
            <table class="table-condensed receipt" style="width:100%; margin-top: 5px;">
				<thead>
					<tr id="bg" style="background-color:#414141;color: #fff;">
						<td class="text-left"style="width:10px"></td>
						<td><?= lang("បរិយាយ <br> Description"); ?></td>
						<!--<td style="color:white !important;"><?= lang("serial"); ?></td>-->
						<td style="text-align:center;"><?= lang("តំលៃ<br>Price"); ?></td>
						<td style="text-align:center;"><?= lang("ចំនួន<br>Qty"); ?></td>
						<td style="padding-left:10px;padding-right:10px;text-align:right;"><?= lang("សរុប<br>Amount"); ?> </td>
					</tr>
				</thead>
                <tbody style="border-bottom:2px solid black;">
                <?php
					$r = 1;
					$m_us = 0;
					$total_quantity = 0;
					$tax_summary = array();
					$sub_total=0;
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

						echo '<tr ' . ($row->product_type === 'combo' ? '' : 'class="item"') . '>';
						echo '	<td class="text-left" style="vertical-align:top;">' .$r.'.'.'</td>';

						if($row->name_kh != '' && $row->product_name != '') {
							echo '	<td class="text-left">' . ($row->name_kh) . ($row->variant ? ' (' . $row->variant . ')' : '') . ($row->product_name ? ' <br/>' . $row->product_name : '') . '</td>';
						}elseif ($row->product_name != '') {
							echo '	<td class="text-left">' . ($row->product_name) . ($row->variant ? ' (' . $row->variant . ')' : '') . '</td>';
						}else {
							echo '	<td class="text-left">' . ($row->name_kh) . ($row->variant ? ' (' . $row->variant . ')' : '') . '</td>';
						}
						// echo '	<td class="text-left">' . ($row->name_kh && $row->product_name ? $row->name_kh.'<br>'. $row->product_name : '') . ($row->variant ? ' (' . $row->variant . ')' : '') . ($row->product_name ? ' <br/>' . $row->product_name : '') . '</td>';
						//echo '	<td style="text-align:left;">' . $row->serial_no . '</td>';
						
						echo '	<td class="text-center"  style="text-align:right; width:65px !important">$ ' . $this->erp->formatMoney($row->unit_price) . '</td>';
						echo '	<td class="text-center">' . $this->erp->formatQuantity($row->quantity) . '</td>';
					
						$colspan = 5;
						// if ($inv->order_discount != 0 || $row->item_discount != 0) {
						// echo '	<td class="text-center">';
						// 	echo 	'<span>' ;
						// 		if(strpos($row->discount, '%') !== false){
						// 			echo $row->discount;
						// 		}else{
						// 			echo $row->discount;
						// 		}
								
						// 	echo '</span> ';
						// 	$colspan = 6;
						// 	$total_col = 4;
						// 	echo '</td>';
						// }else{
						// 	if($total_disc != ''){
						// 		echo '<td class="text-center"></td>';
						// 		$colspan = 6;
						// 		$total_col = 4;
						// 	}else{
						// 		$colspan = 5;
						// 		$total_col = 3;
						// 	}
						// }
						echo '<td class="text-right">$ ' . ($this->erp->formatMoney($row->subtotal) == 0 ? $free:$this->erp->formatMoney($row->subtotal)) . '</td>';
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
								// echo '<td></td>';
								echo '<td></td>';
							echo '</tr>';
								$c++;
							}
						}
					}
                ?>
				
                </tbody>
                <tfoot>
                </tfoot>
            </table>
			<table  class="tbl-foonter" style="width: 100%; margin-top:5px;">
				<tr>
					<td style="text-align:left;width:50%;">សរុប (ដុល្លារ /)<?=lang("TOTAL")?> (<?= $default_currency->code; ?>)</td>
					<td style="text-align:center;">:</td>
					<td style="text-align:right;">$ <?=$this->erp->formatMoney($sub_total)?></td>
				</tr>
				<tr>
					<td style="text-align:left;width:50%;">បញ្ចុះតម្លៃ /<?=lang("discount")?></td>
					<td style="text-align:center;">:</td>
					<td style="text-align:right;">$ <?=$this->erp->formatMoney($inv->order_discount)?></td>
				</tr>
				<tr>
					<td style="text-align:left;width:50%;">សរុបចុងក្រោយ /<?=lang("g.total")?> ($)</td>
					<td style="text-align:center;">:</td>
					<td style="text-align:right;">$ <?=$this->erp->formatMoney($inv->grand_total);?></td>
				</tr>
				<tr>
					<td style="text-align:left;width:50%;">សរុបចុងក្រោយ /<?=lang("g.total")?> (៛)</td>
					<td style="text-align:center;">:</td>
					<td style="text-align:right;">៛ <?=$this->erp->formatMoney($inv->grand_total*$inv->other_cur_paid_rate);?></td>
				</tr>
				<?php
				if ($pos_paid < $inv->grand_total){ 
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
				?> 

			<!-- </table>
			<table class="received" style="width:100%;margin-top: 5px;"> -->
			<?php 
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
					<!-- <tr>
						<th colspan="<?//= $colspan + 1 ?>"> -->
				<?php 
					foreach($payments as $payment) {
				?>
						<table style="width: 100%;">
						<tr style="border:dotted 1px black; background: #d8d8d8" id="total">
							<td style="text-align:left;width:50%;">ប្រាក់ទទួល / Cash Tender ($)</td>
							<td style="text-align:center;">:</td>
							<td class="text-right">$ <?= $this->erp->formatMoney($payment->pos_paid); ?></td>
						</tr>
						<?php
							if($inv->other_cur_paid){
						?>
						<tr style="border:dotted 1px black; background: #d8d8d8" id="total">
							<td style="text-align:left;width:50%;">ប្រាក់ទទួល / Cash Tender (៛)</td>
							<td style="text-align:center;">:</td>
							<td class="text-right">៛ <?= $inv->other_cur_paid; ?></td>
						</tr>
						<?php
							}
						?>
					</table>
					
				<?php
					}
				?>
						<!-- </th>
					</tr> -->
					<?php 
					}else{
						
					
					if($inv->other_cur_paid)
					{
						$khr_paid = ($inv->other_cur_paid/$inv->other_cur_paid_rate);
					}else{
						$khr_paid = 0;
					}	
				?>
					<?php if($inv->recieve_usd != 0) {?>
					<tr style="border:dotted 1px black; background: #d8d8d8" id="total">
                        <td style="text-align:left;width:50%;">ប្រាក់ទទួល / Cash Tender ($)</td>
						<td style="text-align:center;">:</td>
                        <td class="text-right">$ <?= $this->erp->formatMoney($inv->recieve_usd); ?></td>
                    </tr>
                    <?php }?>
                    <?php if($inv->recieve_real != 0) {?>
                    <tr style="border:dotted 1px black; background: #d8d8d8" id="total">
                        <td style="text-align:left;width:50%;">ប្រាក់ទទួល / Cash Tender (៛)</td>
						<td style="text-align:center;">:</td>
                        <td class="text-right">៛ <?= $this->erp->formatMoney($inv->recieve_real) ; ?></td>
                    </tr>
					<?php }?>
					<?php 
					}
					?>
					<!-- //////////////////////////////// -->
					<?php
				
				if($inv->paid-($inv->total+$inv->order_tax-$inv->order_discount) >= 0){?>
				<tr>
					<td style="text-align:left;width:50%;"><?= lang("ប្រាក់អាប់"); ?> / Channge</td>
						<td style="text-align:center;">:</td>
					<td style="text-align:right;">$ <?=$this->erp->formatMoney($inv->pos_balance)?></td>
					<!-- <td style="text-align:right;">$ <?=$this->erp->formatMoney($inv->paid-($inv->total+$inv->order_tax))?></td> -->
				</tr>
				<!-- <tr>
					<td style="text-align:left;width:50%;"><?= lang("ប្រាក់អាប់"); ?> / Channge</td>
						<td style="text-align:center;">:</td>
					<td style="text-align:right;">៛ <?=$this->erp->formatMoney($inv->pos_balance * $inv->pos_paid_other_rate); ?></td>
					<!-- <td style="text-align:right;">R <?=$this->erp->formatMoney(($inv->paid-($inv->total+$inv->order_tax))*$inv->other_cur_paid_rate)?></td> -->
				</tr>
				<?php }?>

					<!-- /////////////////////////////// -->
				<?php
				}
                if ($pos_paidd < $grand_totals) {
					//separate payments
					if(count($payments) > 1){
				?>
					<!-- <tr>
						<th colspan="<?= $colspan + 2 ?>"> -->
				<?php 
					foreach($payments as $payment) {
						if($payment->pos_paid>0){
				?>
					<table style="width:100%;">
						<tr style="border:dotted 1px black; background: #d8d8d8" id="total">
							<td style="text-align:left;width:50%;">ប្រាក់ទទួល / Cash Tender ($)</td>
							<td style="text-align:center;">:</td>
							<td class="text-right">$ <?= $this->erp->formatMoney($payment->pos_paid); ?></td>
						</tr>
						<?php
							if($inv->other_cur_paid){
						?>
						<tr style="border:dotted 1px black; background: #d8d8d8" id="total">
							<td style="text-align:left;width:50%;">ប្រាក់ទទួល / Cash Tender (៛)</td>
							<td style="text-align:center;">:</td>
							<td class="text-right">៛ <?= $inv->other_cur_paid; ?></td>
						</tr>
						<?php
							}else{}
						?>
					</table>
				<?php
						}
					}
				?>
						<!-- </th>
					</tr> -->
				<?php 
					}else{
				?>
					<tr style="border:dotted 1px black; background: #d8d8d8" id="total">
                        <td style="text-align:left;width:50%;">ប្រាក់ទទួល / Cash Tender ($)</td>
						<td style="text-align:center;">:</td>
                        <td  class="text-right">$ <?= $this->erp->formatMoney($inv->paid); ?></td>
                    </tr>
					<?php
						if($inv->other_cur_paid){
					?>
                    <tr style="border:dotted 1px black; background: #d8d8d8" id="total">
                        <td style="text-align:left;width:50%;">ប្រាក់ទទួល / Cash Tender (៛)</td>
						<td style="text-align:center;">:</td>
                        <td class="text-right">៛ <?=$this->erp->formatMoney($inv->recieve_real); ?></td>
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
                        <td style="text-align:left;width:50%;"><?= lang("ប្រាក់ជំពាក់"); ?> / Remaining</td>
						<td style="text-align:center;">:</td>
                        <td class="text-right">$ 
						<?php
							$money_kh = $pay_kh / (($pos_settings->in_out_rate) ? $outexchange_rate->rate:$exchange_rate->rate);
							echo $this->erp->formatMoney(abs(($pay+$money_kh) - $grand_totals));
							$total_us_b = $this->erp->formatMoney(($pay+$money_kh) - $grand_totals);
							$m_us = $this->erp->fraction($total_us_b);
						?>
						</td>
                    </tr>
                    <!-- <tr>
                        <td style="text-align:left;width:50%;"><?//= lang("ប្រាក់ជំពាក់"); ?> / Remaining</td>
						<td style="text-align:center;">:</td>
                        <td style="border-top:2px dotted #000" class="text-right">៛ <?//= number_format(abs((($pay+$money_kh) - $grand_totals)*(($pos_settings->in_out_rate) ? $outexchange_rate->rate:$exchange_rate->rate))); ?></td>
                    </tr> -->
				<?php
						}
					}else{
					if((($pos_paid+$amount_kh_to_us) - $grand_totals) != 0 || ($this->erp->formatMoney((($pos_paid+$amount_kh_to_us) - $grand_totals)*(($pos_settings->in_out_rate) ? $outexchange_rate->rate:$exchange_rate->rate) )) != 0){ ?>
                    <tr>
                        <td style="text-align:left;width:50%;"><?= lang("ប្រាក់ជំពាក់"); ?> / Remaining</td>
						<td style="text-align:center;">:</td>
                        <td class="text-right">$ 
						<?php
							echo $this->erp->formatMoney(abs(($pos_paid+$amount_kh_to_us) - $grand_totals));
							$total_us_b = $this->erp->formatMoney(($pos_paid+$amount_kh_to_us) - $grand_totals);
							$m_us = $this->erp->fraction($total_us_b);
						?>
						</td>
                    </tr>
                    <!-- <tr>
                        <td style="text-align:left;width:50%;"><?//= lang("ប្រាក់ជំពាក់"); ?> / Remaining</td>
						<td style="text-align:center;">:</td>
                        <td style="border-top:2px dotted #000" class="text-right">៛ <?//= number_format(abs((($pos_paid+$amount_kh_to_us) - $grand_totals)*(($pos_settings->in_out_rate) ? $outexchange_rate->rate:$exchange_rate->rate))); ?></td>
                    </tr> -->
				
				<?php }
					}
				}
				?>
				<tr style="border-bottom:dotted 1px black;"></tr>
			</table>
			<!-- footer -->
			<div id="footer-text" style="width:100%;float:left;margin-top:10px; font-size: 12px;">
				  <p style="margin-bottom:0px !important;">រាល់ទំនិញទិញរួចមិនអាចដូរប្រាក់ រឺ ទំនិញវិញបានទេ! </p>
				  <p style="margin-bottom:0px !important;">Goods sold are  not  returnable or refundable!</p>
			      <p style="margin-top:10px;margin-bottom:0px !important;">អរគុណចំពោះការទិញទំនិញនៅហាងយើងខ្ញុំ សូមអញ្ចើញមកម្តងទៀត!</p>
			      <p style="margin-bottom:0px !important;">Thank you for shopping  at  our shop, Please Come Agani!</p>
			</div> 
			<div style="width:100%;text-align:left;margin-top:10px;display:none">
				ពិន្ទុចាស់ - Old Point 	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <b></b><br/>
				ពិន្ទុសរុប - Total Point 	&nbsp;&nbsp;: <b></b>
			</div>
			<div class="barcode">
				<?php if($pos->display_qrcode) {
					$ref = strtoupper($inv->reference_no);
				?>
        			<?php $br = $this->erp->save_barcode($ref, 'code39'); ?>
        			<img id="img-barcode" src="<?= base_url() ?>assets/uploads/barcode<?= $this->session->userdata('user_id') ?>.png" alt="<?= $ref ?>" style="width: 70%; margin-top: 10px;"/>
				<?php } ?>
				<!-- <?php $this->erp->qrcode('link', urlencode(site_url('products/view/' . $product->id)), 1); ?>
                    <img id="img-qrCode" src="<?= base_url() ?>assets/uploads/qrcode<?= $this->session->userdata('user_id') ?>.png" alt="<?= $row->product_name ?>" class="pull-right" style="width: 12%; margin-top: 10px; margin-right: 36px;"/> -->
			</div>
			<div style="text-align:center;font-size:11px;" class="footer-invioce">
				<?=  $biller->invoice_footer; ?>	
				~ ~ ~ <b>CloudNet</b> &nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size:12px;">www.cloudnet.com.kh</span> ~ ~ ~
			</div>
			
			<!--<div class="alert alert-success">
				<?php $rate = (($pos_settings->in_out_rate) ? $outexchange_rate->rate:$exchange_rate->rate); ?>
				<h4>US <?=$this->erp->fraction($pos_paid)?> = <?=number_format($this->erp->fraction($pos_paid)*$rate)?> ៛</h4>
			</div>-->
            <?php
            if ($Settings->invoice_view == 1) {
                if (!empty($tax_summary)) {
                    echo '<h4 style="font-weight:bold;">' . lang('tax_summary') . '</h4>';
                    echo '<table class="table table-condensed"><tdead><tr><td>' . lang('name') . '</td><td>' . lang('code') . '</td><td>' . lang('qty') . '</td><td>' . lang('tax_excl') . '</td><td>' . lang('tax_amt') . '</td></tr></td><tbody>';
                    foreach ($tax_summary as $summary) {
                        echo '<tr><td>' . $summary['name'] . '</td><td class="text-center">' . $summary['code'] . '</td><td class="text-center">' . $this->erp->formatQuantity($summary['items']) . '</td><td class="text-right">' . $this->erp->formatMoney($summary['amt']) . '</td><td class="text-right">' . $this->erp->formatMoney($summary['tax']) . '</td></tr>';
                    }
                    echo '</tbody></tfoot>';
                    echo '<tr><td colspan="4" class="text-right">' . lang('total_tax_amount') . '</td><td class="text-right">' . $this->erp->formatMoney($inv->product_tax) . '</td></tr>';
                    echo '</tfoot></table>';
                }
            }
            ?>

            <?= $inv->note ? '<p class="text-left"><strong>'.lang("note").': '. $this->erp->decode_html($inv->note) . '</strong></p>' : ''; ?>
            <?= $inv->staff_note ? '<p class="no-print"><strong>' . lang('staff_note') . ':</strong> ' . $this->erp->decode_html($inv->staff_note) . '</p>' : ''; ?>
           
        </div>
        <?php $this->erp->qrcode('link', urlencode(site_url('pos/view/' . $inv->id)), 2); ?>
        <div style="clear:both;"></div>
    </div>
<?php if ($modal) {
    echo '</div></div></div></div>';
} else { ?>

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