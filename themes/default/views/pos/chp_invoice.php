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
		.no_border_btm tr td{
					border:none !important;
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
        	<div class="col-sm-12 text-center">
					<div class="col-sm-2">
					 	<button class="btn btn-xs btn-default no-print pull-left" onclick="window.print()"><i class="fa fa-print"></i>&nbsp;<?= lang("print"); ?></button>
					</div>
					<div class="col-sm-8 text-center">
						<?php
						echo "<p style='font-size: 32px;'>".'Queue No '. $inv->queue ."</p>";
		            	echo "<p>#" . $biller->address . " " . $biller->city . " " . $biller->postal_code . " " . $biller->state . " " . $biller->country ;
		                
		            ?>
					</div>
		           <div class="col-sm-2"></div>
		            <div class="col-sm-12">
		            	<p style="text-transform:uppercase;"><?= $biller->company != '-' ? $biller->company : $biller->name; ?></p>
		            </div>
					<!-- <div style="font-size:11px;"> -->
						<table style="width: 100%;">				
							<tbody style="font-size: 14px;">							
								<tr>
									<td style="vertical-align:middle;text-align:left;"><?= lang('date')?></td>
									<td>:</td>
									<td style="vertical-align:middle;text-align:left;"><?= $this->erp->hrsd($inv->date) ?></td>
								</tr>
								<tr>
									<td style="vertical-align:middle;text-align:left;"><?= lang('user')?></td>
									<td>:</td>
									<td style="vertical-align:middle;text-align:left;"><?= $inv->username ?></td>
								</tr>	
								<tr>
									<td style="vertical-align:middle;text-align:left;width: 70px;"><?= lang('customer')?></td>
									<td style="width: 15px;">:</td>
									<td style="vertical-align:middle;text-align:left;"><?= $customer->name?></td>
								</tr>		
							</tbody>				
						</table>
					<!-- </div> -->
				
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
				
		            <table class="table-condensed receipt no_border_btm" style="width:100%;">
						<thead style="border-top: 2px dashed black;border-bottom:2px solid black;">
							<tr>
								<th style="font-size: 11px; text-align: center;">Nº</th>
								<th style="font-size: 11px; text-align: center;"><?=lang('product_name')?></th>
								<th style="font-size: 11px; text-align: center;"><?=lang('qty')?></th>
								<th style="font-size: 11px; text-align: center;"><?=lang('unit_price')?></th>
								<?php if ($inv->order_discount != 0 || $total_disc != '') {
									echo '<th style="font-size: 11px; text-align: center;">'.lang('discount').'</th>';
								} ?>
								<th style="font-size: 11px; text-align: center;"><?=lang('amount')?></th>
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
					<table style="width: 100%; margin-top: 5px; border-bottom: 2px dashed black;">
						<div class="col-xs-12">
							<tr>
							<!-- <td style="text-align:left;width:25%;">សរុប</td> -->
							<td style="text-align:left;width:35%;">Total (<?= $default_currency->code; ?>)</td>
							<!-- <td style="text-align:center;width:35%;">:</td> -->
							<td style="text-align:right;">$ <?=$this->erp->formatMoney($sub_total)?></td>
						</tr>
						<?php if($inv->order_tax != 0){?>
						<tr>
							<td style="text-align:left;width:35%;">Order Tax (<?= $default_currency->code; ?>)</td>
							<!-- <td style="text-align:center;width:35%;">:</td> -->
							<td style="text-align:right;">$ <?=$this->erp->formatMoney($inv->order_tax)?></td>
						</tr>
						<?php }?>
						<?php if($inv->order_discount != 0){?>
						<tr>
							<td style="text-align:left;width:35%;">Discount (<?= $default_currency->code; ?>)</td>
							<!-- <td style="text-align:center;width:35%;">:</td> -->
							<td style="text-align:right;">$ <?=$this->erp->formatMoney($inv->order_discount)?></td>
						</tr>
						</div>
						<?php }?>
							<!-- <?php if ($pos_paid < $inv->grand_total) { 
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
							// $inv->grand_total=$inv->grand_total; 
							if($pos_paidd >= $inv->grand_total){
								 
								if(count($payments) > 1){
									//separate payments
								?>
								<?php 
								foreach($payments as $payment) {
							?>
								<tr>
									<td style="text-align:left;width:35%;">Recieved</td>
									<td style="text-align:right;"><?='$ '. $this->erp->formatMoney($payment->pos_paid); ?></td>
								</tr>
								<?php
									if($inv->other_cur_paid){
								?>
								<tr>
									<td style="text-align:left;width:35%;">Recieved</td>
									<td style="text-align:right;"><?= '៛  '. $inv->other_cur_paid ; ?></td>
								</tr>
								<?php
									}
								?>
								
							<?php
								}
							?>
							<?php 
								}else{
									if($inv->other_cur_paid)
								{
									$khr_paid = ($inv->other_cur_paid/$inv->other_cur_paid_rate);
								}else{
									$khr_paid = 0;
								}	
							?>
								<?php if($inv->recieve_usd != 0){ ?>
								<tr>
			                        <td style="text-align:left;width:35%;">Received</td>
			                        <td style="text-align:right;"><?='$ '. $this->erp->formatMoney($inv->recieve_usd); ?></td>
			                    </tr>
			                    <?php } ?>
			                    <?php if($inv->recieve_real != 0){ ?>
			                    <tr>
			                        <td style="text-align:left;width:35%;">Received</td>
			                        <td style="text-align:right;"><?= '៛  '.$this->erp->formatMoney($inv->recieve_real) ; ?></td>
			                    </tr>
								<?php } ?>
								<?php 
								}?>
							<?php 
								if(count($payments) > 1){
							$pay = '';
							$pay_kh = '';
							foreach($payments as $payment){
								$pay += $payment->pos_paid;
								$pay_kh += $payment->pos_paid_other;
							}

							if((($pay + ($pay_kh / (($pos_settings->in_out_rate) ? $outexchange_rate->rate : $exchange_rate->rate))) - $inv->grand_total) != 0){
							 ?>
							 <tr>
		                        <td style="text-align:left;width:35%;"><?= lang("change_amount"); ?></td>
								<td style="text-align:right;"><?='$ '. $this->erp->formatMoney(($pay+$pay_kh) - $inv->grand_total);
									$total_us_b = $this->erp->formatMoney(($pay+$pay_kh) - $inv->grand_total);
									$m_us = $this->erp->fraction($total_us_b);
								?></td>
		                    </tr>
							<?php
								}
						}else{
								// $this->erp->print_arrays($inv);
							//if((($pos_paid+$amount_kh_to_us) - ($inv->recieve_usd - $inv->grand_total)) != 0 || ($this->erp->formatMoney((($pos_paid+$amount_kh_to_us) - $inv->grand_total) * $exchange_rate->rate)) != 0) { ?>
							<?php if($payment->pos_balance != 0){ ?>
							<tr>
		                        <td style="text-align:left;width:35%;"><?= lang("change_amount"); ?></td>
								<td style="text-align:right;"><?php
									echo '$ '.$this->erp->formatMoney($payment->pos_balance);
									// echo $this->erp->formatMoney(($pos_paid+$amount_kh_to_us) - $inv->grand_total);
									$total_us_b = $this->erp->formatMoney(($pos_paid+$amount_kh_to_us) - $inv->grand_total);
									$m_us = $this->erp->fraction($total_us_b);
								?></td>
		                    </tr>
		                    <?php } ?>
							<?php
							}
						}
		                if ($pos_paidd < $inv->grand_total) {
							//separate payments
							if(count($payments) > 1){
						?>
						<?php 
							foreach($payments as $payment) {
								if($payment->pos_paid>0){
						?>
								<tr>
									<td style="text-align:left;width:35%;" colspan="<?= $colspan ?>">Received</td>
									<td style="text-align:right;"><?='$ '. $this->erp->formatMoney($payment->pos_paid); ?></td>
								</tr>
								<?php
									if($inv->other_cur_paid){
								?>
								<tr>
									<td style="text-align:left;width:35%;"colspan="<?= $colspan ?>">Received</td>
									<td style="text-align:right;"><?= '៛  '.$inv->other_cur_paid ; ?></td>
								</tr>
								<?php
									}else{}
								?>
						<?php
								}
							}
						?>
						<?php 
							}else{
						?>
							<tr>
		                        <td style="text-align:left;width:35%;" colspan="<?= $colspan ?>">Received </td>
		                        <td style="text-align:right;"><?='$ '. $this->erp->formatMoney($inv->paid); ?></td>
		                    </tr>
							<?php
								if($inv->other_cur_paid){
							?>
		                    <tr>
		                        <td style="text-align:left;width:35%;" colspan="<?= $colspan ?>">Received</td>
		                        <td style="text-align:right;"><?= '៛  '.$this->erp->formatMoney($inv->recieve_real)?></td>
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
									$pay = $payment->pos_paid;
									$pay_kh = $payment->pos_paid_other;
								}
								if(($pay + ($pay_kh / (($pos_settings->in_out_rate) ? $outexchange_rate->rate:$exchange_rate->rate)) > $inv->grand_total)){ 
						?>
							<tr>
		                        <td style="text-align:left;width:35%;" colspan="<?= $colspan ?>"><?= lang("remaining"); ?></td>
								<td style="text-align:right;"><?='$ '.$money_kh = $pay_kh / (($pos_settings->in_out_rate) ? $outexchange_rate->rate:$exchange_rate->rate);
									echo $this->erp->formatMoney(abs(($pay+$money_kh) - $inv->grand_total));
									$total_us_b = $this->erp->formatMoney(($pay+$money_kh) - $inv->grand_total);
									$m_us = $this->erp->fraction($total_us_b);
								?></td>
		                    </tr>
		                    <!-- <tr>
		                        <td style="text-align:left;width:35%;" colspan="<?= $colspan ?>"><?= lang("remaining"); ?></td>
		                        <td style="text-align:right;"><?= '៛  '. number_format(abs((($pay+$money_kh) - $inv->grand_total)*(($pos_settings->in_out_rate) ? $outexchange_rate->rate:$exchange_rate->rate))); ?></td>
		                    </tr> -->
						<?php
								}
							}else{
								if((($pos_paid+$amount_kh_to_us) - $inv->grand_total) != 0 || ($this->erp->formatMoney((($pos_paid+$amount_kh_to_us) - $inv->grand_total)*(($pos_settings->in_out_rate) ? $outexchange_rate->rate:$exchange_rate->rate) )) != 0){ ?>
		                    <tr>
		                        <td style="text-align:left;width:35%;" colspan="<?= $colspan ?>"><?= lang("remaining"); ?></td>
								<td style="text-align:right;"><?='$ '.$this->erp->formatMoney(abs(($pos_paid+$amount_kh_to_us) - $inv->grand_total));
									$total_us_b = $this->erp->formatMoney(($pos_paid+$amount_kh_to_us) - $inv->grand_total);
									$m_us = $this->erp->fraction($total_us_b);
								?></td>
		                    </tr>
						
						<?php }
							}
						}
						?>
					</table>
					<?php if($inv->product_tax != 0){?>
		            <div class="col-xs-12">
		            	<div class="pull-left" style="margin-left: -13px;">
		            		<?='VAT';?>
		            	</div>
		            	<div class="pull-right" style="margin-right: -13px;">
		            		<?='$ '.$this->erp->formatMoney($inv->product_tax);?>
		            	</div>
					</div><?php } ?>
					<div style="text-align:center;font-size:11px;margin-top:10px;">
						<?=  $biller->invoice_footer; ?> <br>	
						~ ~ ~ <b>CloudNet</b> &nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size:12px;">www.cloudnet.com.kh</span> ~ ~ ~
					</div>
					<?//php $rate = (($pos_settings->in_out_rate) ? $outexchange_rate->rate:$exchange_rate->rate); ?>
					<!-- <h4>US <?//=$this->erp->fraction($pos_paid)?> = <?=number_format($this->erp->fraction($pos_paid)*$rate)?> ៛</h4> -->
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
        	<div style="clear:both;"></div>
    	</div>
		<?php if ($modal) {
		    echo '</div></div></div></div>';
		} else { ?>
		<div id="buttons" style="padding-top:10px; text-transform:uppercase;" class="no-print">
		    <hr>
		    <span class="col-xs-12"><a href="<?= site_url('pos/view/'.$inv->id); ?>" class="btn btn-block btn-primary"><?= lang("back"); ?></a></span>
		    <span class="col-xs-12"><a href="<?= site_url('pos/sales'); ?>" class="btn btn-block btn-primary"><?= lang("list_pos"); ?></a></span>
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
		    <span class="col-xs-12">
		        <a class="btn btn-block btn-warning" href="<?= site_url('pos'); ?>"><?= lang("back_to_pos"); ?></a>
		    </span>
		    <?php if ($message) { ?>
		    <div class="alert alert-success">
		        <button data-dismiss="alert" class="close" type="button">×</button>
		        <?= is_array($message) ? print_r($message, true) : $message; ?>
		    </div>
		<?php } ?>
		    <div style="clear:both;"></div>

		</div>

	</div>
<!-- </div> -->
<canvas id="hidden_screenshot" style="display:none;"></canvas>
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