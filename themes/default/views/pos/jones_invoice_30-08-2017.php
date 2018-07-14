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
            	font-family: MS Reference Sans Serif;
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
                    width: 95% !important;
                    margin: 0 auto !important;
					padding: 0 !important;
					font-size: 10px !important;
					margin-top: 10px !important;
                }
				
				thead tr th {
					font-size: 10px !important;
				}
				
				tbody {
					font-size: 10px !important;
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
            <button style="position: absolute;" class="btn btn-xs btn-default no-print pull-left" onclick="window.print()"><i class="fa fa-print"></i>&nbsp;<?= lang("print"); ?></button>
        <div class="text-center">
            <!-- <?php if ($Settings->system_management == 'project') { ?>
				<img src="<?= base_url() . 'assets/uploads/logos/' . $Settings->logo2; ?>" alt="<?= $Settings->site_name; ?>" style="padding-right: 50px" >
			<?php } else { ?>
				<img src="<?= base_url() . 'assets/uploads/logos/' . $biller->logo; ?>" alt="<?= $biller->company; ?>">
			<?php } ?> -->
			<!--<img style="width:100%;" src="<?= base_url() . 'assets/Aylogo/logo501.jpg'; ?>"> -->
			<!--<h3 style="text-transform:uppercase;"><?= $biller->company != '-' ? $biller->company : $biller->name; ?></h3>-->
				<?php if($biller->logo)?>
					<img src="<?= base_url() . 'assets/uploads/logos/' . $biller->logo; ?>" alt="<?= $biller->company; ?>">
				<?php
				echo "<p></p>";
				echo "<p>".lang('siem_reap_store')."<p>";
				if($biller->vat_no){
					echo "<p>".lang('tax_id').$biller->vat_no."</p>";
				} ?>
			<?php
            echo "<p>" . $biller->address . " " . $biller->city . " " . $biller->postal_code . " " . $biller->state . " " . $biller->country .
                "<br>" . lang("tel") . " : " . $biller->phone.",&nbsp;&nbsp;".lang('email'). " : " . $biller->email; 
			
            ?>
			<!--<div style="font-size:13px;">#138E0, St. 13. Sangkat Phsa kandal Phnom Penh<br/>Tel: 092/093/069 311 138 www.a-ycollection.com</div>
			<hr style="border: 1px solid black; margin-top: 10px; margin-bottom: 10px;"/>-->
			
			<div>
				<div class="col-xs-12">	
					<div class="pull-left">
						<table class="text-left">
							<tr>
								<td><?=lang('date');?></td>
								<td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
								<td><?=$this->erp->hrsd($inv->date)?></td>
							</tr>
							<tr>
								<td><?=lang('terminal');?></td>
								<td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
								<td></td>
							</tr>
							<tr>
								<td><?=lang('partner');?></td>
								<td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
								<td></td>
							</tr>
							<tr>
								<td><?=lang('table');?></td>
								<td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
								<td><?=$inv->suspend_note;?></td>
							</tr>
							<tr>
								<td><?=lang('cust.name');?></td>
								<td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
								<td><?=$inv->customer;?></td>
							</tr>
							<tr>
								<td><?=lang('ref_no');?></td>
								<td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
								<td><?= $inv->reference_no ?></td>
							</tr>
						</table>
					</div>				
					<div class="pull-right">
						<table>
							<tr>
								<td><?=lang('time');?></td>
								<td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
								<td><?=date('H:i',strtotime($inv->date))?></td>
							</tr>
						</table>
					</div>
					
				</div>
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
						<!-- <th><?= lang("no"); ?></th> -->
						<th style="width: 300px;"><?= lang("items_name"); ?></th>
						<!--<th style="color:white !important;"><?= lang("serial"); ?></th>-->
						<th style="text-align:center;"><?= lang("qty"); ?></th>
						<!-- <th style="text-align:right;"><?= lang("Price"); ?></th> -->
						<!-- <?php if ($inv->order_discount != 0 || $total_disc != '') {
							//echo '<th>'.lang('discount').'</th>';
						} ?> -->
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
					if(is_array($rows)){
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
						echo '	<td class="text-left">' . product_name($row->product_name) . ($row->variant ? ' (' . $row->variant . ')' : '') . ($row->product_noted ? ' <br/>(' . $row->product_noted . ')' : '') . '</td>';
						//echo '	<td style="text-align:left;">' . $row->serial_no . '</td>';
						echo '	<td class="text-center">' . $this->erp->formatQuantity($row->quantity) . '</td>';
						
						//echo '	<td class="text-center"  style="text-align:right; width:65px !important">$ ' . $this->erp->formatMoney($row->unit_price) . '</td>';
					
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
			<table style="width: 100%; margin-top: 5px; border-bottom: 1px solid black;">
				<tr>
					<td class="text-left">Total (<?= $default_currency->code; ?>)</td>
					<td style="text-align:right;">$ <?=$this->erp->formatMoney($inv->total+$inv->order_tax);?></td>
				</tr>
				<tr>
					<td class="text-left">Total Riel (RIEL)</td>
					<td style="text-align:right;">R <?=$this->erp->formatMoney($sub_total*$inv->other_cur_paid_rate)?></td>
				</tr>
				<tr>
					<td class="text-left">Exchange Rates</td>
					<td style="text-align:right;">R <?=$this->erp->formatMoney($inv->other_cur_paid_rate)?></td>
				</tr>
				<tr>
					<td class="text-left"><?=$inv->tax?></td>
					<td style="text-align:right;">$ <?=$this->erp->formatMoney($inv->order_tax);?>
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
				<tr>
					<td class="text-left">
						<?php
							if($inv->paid_by == 'cash'){
								echo "Cash(USD)";
							}elseif ($inv->paid_by == 'CC') {

								echo "Credit Card".($inv->cc_type ? '('.$inv->cc_type.')':'');


							}elseif ($inv->paid_by == 'Cheque') {
								echo "Cheque".($inv->cheque_no? '('.$inv->cheque_no.')':'');
							}elseif ($inv->paid_by == 'gift_card') {
								echo "Member Card".($inv->cc_no? '('.$inv->cc_no.')':'');
							}elseif ($inv->paid_by == 'deposit') {
								echo "Deposit";
							}elseif ($inv->paid_by == 'depreciation') {
								echo "Loan";
							}elseif ($inv->paid_by == 'Voucher') {
								echo "Voucher".($inv->cheque_no? '('.$inv->cheque_no.')':'');
							}else{
								echo "Other";
							}
						?>
					</td>
					<td style="text-align:right;">$ <?= $this->erp->formatMoney($inv->paid); ?></td>
				</tr>
				<?php if($inv->paid-($inv->total-$inv->order_tax) >= 0){?>
				<tr>
					<td class="text-left">Change Given (<?= $default_currency->code; ?>)</td>
					<td style="text-align:right;">$ <?=$this->erp->formatMoney($inv->pos_balance)?></td>
					<!-- <td style="text-align:right;">$ <?=$this->erp->formatMoney($inv->paid-($inv->total+$inv->order_tax))?></td> -->
				</tr>
				<tr>
					<td class="text-left">Change Given (RIEL)</td>
					<td style="text-align:right;">R <?=$this->erp->formatMoney($inv->pos_balance * $inv->pos_paid_other_rate); ?></td>
					<!-- <td style="text-align:right;">R <?=$this->erp->formatMoney(($inv->paid-($inv->total+$inv->order_tax))*$inv->other_cur_paid_rate)?></td> -->
				</tr>
				<?php }?>
				<?php if($inv->paid-($inv->total-$inv->order_tax) < 0){?>
					<tr>
						<td class="text-left">Remaining (<?= $default_currency->code; ?>)</td>
						<td style="text-align:right;">$ <?=$this->erp->formatMoney(abs($inv->paid-($inv->total+$inv->order_tax)))?></td>
					</tr>
					<tr>
						<td class="text-left">Remaining (RIEL)</td>
						<td style="text-align:right;">R <?=$this->erp->formatMoney(abs(($inv->paid-($inv->total+$inv->order_tax))*$inv->other_cur_paid_rate))?></td>
					</tr>
				<?php }?>
			</table>
			<div class="col-xs-12 text-center">
				<h5>Thank you! Please come again.</h5>
				<p>Please visit us at <b><u>jonesthegrocer.com</u><b></p>
				<p>===============================</p>
				<p>Free WIFI access code:</p>
				<p><?=$biller->wifi_code?></p>
			</div>

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