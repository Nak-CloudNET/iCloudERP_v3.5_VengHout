<?php
function product_name($name)
{
    return character_limiter($name, (isset($pos_settings->char_per_line) ? ($pos_settings->char_per_line-8) : 35));
}

if (isset($modal)) {
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
    <style type="text/css" media="all">
        body {
            color: #000;
            font-size: 13px !important;
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
            text-align: center;
        }

        .item td {
            border-bottom: 1px solid #000;
        }

        .receipt > thead > tr > th {
            font-size: 15px;
            background-color: #fff !important;
            color: #000 !important;
            -webkit-print-color-adjust: exact;
            -moz-print-color-adjust: exact;
            -ms-print-color-adjust: exact;
            print-color-adjust: exact;
            color-adjust: exact;
            -webkit-color-adjust: exact;
            -moz-color-adjust: exact;
            -ms-color-adjust: exact;
        }

        .shadow {
            margin-top: 50%;
            position: absolute;
            webkit-transform: rotate(-30deg);
            -moz-transform: rotate(-30deg);
            -ms-transform: rotate(-30deg);
            -o-transform: rotate(-30deg);
            text-align: center;
            vertical-align: middle;
        }

        .shadow h1 {
            opacity: 0.06;
            font-size: 170px;
            z-index: -1;
            margin-left: 40px;
            height: -webkit-calc(100% - 2);
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

            .modal-dialog #payment {
                display: none !important;
            }
        }
    </style>

</head>
<body>

<?php } ?>
<div id="wrapper">
    <div id="receiptData">
        <div id="receipt-data" style="background-color: #FFF; padding: 20px; position: relative;">
            <div class="shadow"><h1>Paid</h1></div>
            <div class="text-center">
                <button class="btn btn-xs btn-default no-print pull-left" onclick="window.print()"><i
                            class="fa fa-print"></i>&nbsp;<?= lang("print"); ?></button>
                <button type="button" class="close pull-right" data-dismiss="modal" aria-hidden="true"><i
                            class="fa fa-2x"></i>&times;
                </button>
                <!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i> -->
                <?php if ($Settings->system_management == 'project') { ?>
                    <img src="<?= base_url() . 'assets/uploads/logos/' . $Settings->logo2; ?>"
                         alt="<?= $Settings->site_name; ?>" style="padding-right: 50px">
                <?php } else { ?>
                    <img src="<?= base_url() . 'assets/uploads/logos/' . $biller->logo; ?>"
                         alt="<?= $biller->company; ?>">
                <?php } ?>
                <!--<img style="width:100%;" src="<?= base_url() . 'assets/Aylogo/logo501.jpg'; ?>"> -->
                <!--<h3 style="text-transform:uppercase;"><?= $biller->company != '-' ? $biller->company : $biller->name; ?></h3>-->
                <?php
                echo "<p>#" . $biller->address . " " . $biller->city . " " . $biller->postal_code . " " . $biller->state . " " . $biller->country .
                    "<br>" . lang("tel") . ": " . $biller->phone . ",&nbsp;&nbsp;" . lang('email') . " : " . $biller->email;

                ?>
                <!--<div style="font-size:13px;">#138E0, St. 13. Sangkat Phsa kandal Phnom Penh<br/>Tel: 092/093/069 311 138 www.a-ycollection.com</div>
                <hr style="border: 1px solid black; margin-top: 10px; margin-bottom: 10px;"/>-->
                <?php
                $total = 0;
                foreach ($rowpay as $row) {

                    ?>
                    <div style="font-size:11px;">
                        <table style="width: 100%;">
                            <tbody style="font-size: 12px;">
                            <tr style="text-align:center;">
                                <td style="vertical-align:middle;text-align:left;padding:3px;"></td>
                                <td style="vertical-align:middle;text-align:left;"></td>
                                <td style="vertical-align:middle;text-align:left;">ថ្ងៃ&#8203;ខែឆ្នាំ</td>
                                <td style="vertical-align:middle;text-align:right;"><?= $this->erp->hrsd($row->date) ?></td>
                            </tr>
                            <tr style="text-align:center;">
                                <td style="vertical-align:middle;text-align:left;padding:3px;">អ្នកលក់</td>
                                <td style="vertical-align:middle;text-align:left;"><?= $row->name ?></td>
                                <td style="vertical-align:middle;text-align:left;">លេខវិក័យបត្រ</td>
                                <td style="vertical-align:middle;text-align:right;"><?= $row->reference_no ?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <?php
                    $total_disc = 0;
                    if (is_array($rows)) {
                        foreach ($rows as $d) {
                            if ($d->discount != 0) {
                                $total_disc = $d->discount;
                            }
                        }
                    }
                    ?>

                    <div style="clear:both;"></div>

                    <style>
                        .no_border_btm tr td {
                            border: none !important;
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
                            <?php if ($row->order_discount != 0 || $total_disc != '') {
                                echo '<th>' . lang('discount') . '</th>';
                            } ?>
                            <th style="padding-left:10px;padding-right:10px;text-align:right;"><?= lang("amount"); ?> </th>
                        </tr>
                        </thead>
                        <tbody style="border-bottom:2px solid black;">
                        <?php
                        $r = 1;
                        $sub_total = 0;
                        $total_quantity = 0;

                        $this->db->select("sale_items.*, erp_product_variants.name as variant")
                            ->from("sale_items")->where("sale_id", $row->sale_id)
                            ->join('erp_product_variants', 'sale_items.option_id=erp_product_variants.id', 'left')
                            ->join('products', 'sale_items.product_id= products.id', 'left');
                        $q2 = $this->db->get();
                        $g = 1;
                        foreach (($q2->result()) as $row2) {
                            // $this->erp->print_arrays($row2);
                            // if (isset($tax_summary[$row2->tax_code])) {
                            // $tax_summary[$row->tax_code]['items'] += $row->quantity;
                            // 	$tax_summary[$row->tax_code]['tax'] += $row->item_tax;
                            // 	$tax_summary[$row->tax_code]['amt'] += ($row->quantity * $row->net_unit_price) - $row->item_discount;
                            //  } else {
                            //  	$tax_summary[$row->tax_code]['items'] = $row->quantity;
                            //  	$tax_summary[$row->tax_code]['tax'] = $row->item_tax;
                            //  	$tax_summary[$row->tax_code]['amt'] = ($row->quantity * $row->net_unit_price) - $row->item_discount;
                            //  	$tax_summary[$row->tax_code]['name'] = $row->tax_name;
                            //  	$tax_summary[$row->tax_code]['code'] = $row->tax_code;
                            //  	$tax_summary[$row->tax_code]['rate'] = $row->tax_rate;
                            //  }

                            echo '<tr ' . ($row2->product_type === 'combo' ? '' : 'class="item"') . '>
						 		<td class="text-left">' . $r . "</td>";
                            echo '	<td class="text-left">' . product_name($row2->product_name) . ($row2->variant ? ' (' . $row2->variant . ')' : '') . ($row2->product_noted ? ' <br/>(' . $row2->product_noted . ')' : '') . '</td>';
                            //echo '	<td style="text-align:left;">' . $row->serial_no . '</td>';
                            echo '	<td class="text-center">' . $this->erp->formatQuantity($row2->quantity) . '</td>';

                            echo '	<td class="text-center"  style="text-align:right; width:65px !important">$ ' . $this->erp->formatMoney($row2->unit_price) . '</td>';

                            $colspan = 5;
                            if ($row->order_discount != 0 || $row2->item_discount != 0) {
                                echo '	<td class="text-center">';
                                echo '<span>';
                                if (strpos($row2->discount, '%') !== false) {
                                    echo $row2->discount;
                                } else {
                                    echo $row2->discount;
                                }

                                echo '</span> ';
                                $colspan = 6;
                                $total_col = 4;
                                echo '</td>';
                            } else {
                                if ($total_disc != '') {
                                    echo '<td class="text-center"></td>';
                                    $colspan = 6;
                                    $total_col = 4;
                                } else {
                                    $colspan = 5;
                                    $total_col = 3;
                                }
                            }
                            echo '<td class="text-right" style="text-align:right;">$ ' . ($this->erp->formatMoney($row2->subtotal) == 0 ? $free : $this->erp->formatMoney($row2->subtotal)) . '</td>';

                            $r++;
                            $sub_total += $row2->subtotal;
                            $total_quantity += $row2->quantity;

                            if ($row2->product_type === 'combo') {
                                $this->db->select('*, (select name from erp_products p where p.id = erp_combo_items.product_id) as p_name ');
                                $this->db->where('erp_combo_items.product_id = "' . $row2->product_id . '"');
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
                        }
                        //}}

                        ?>

                        </tbody>
                        <tfoot>
                        </tfoot>
                    </table>

                    <table style="width: 100%; margin-top: 5px;">
                        <tr>
                            <td style="text-align:left;width:25%;">សរុប</td>
                            <td style="text-align:right;width:35%;">Sub Total (<?= $default_currency->code; ?>) :</td>
                            <td style="text-align:right;">$ <?= $this->erp->formatMoney($sub_total) ?></td>
                        </tr>
                        <tr>
                            <td style="text-align:left;">ពន្ធ</td>
                            <td style="text-align:right;width:35%;">Order Tax (<?= $default_currency->code; ?>) :</td>
                            <td style="text-align:right;">$ <?= $this->erp->formatMoney($row->order_tax) ?></td>
                        </tr>
                        <tr>
                            <td style="text-align:left;">បញ្ចុះតំលៃ</td>
                            <td style="text-align:right;width:35%;">Discount (<?= $default_currency->code; ?>) :</td>
                            <td style="text-align:right;">$ <?= $this->erp->formatMoney($row->order_discount) ?></td>
                        </tr>
                        <tr>
                            <td style="text-align:left;">សរុបចុងក្រោយ</td>
                            <td style="text-align:right;width:40%;">Grand Total (<?= $default_currency->code; ?>) :</td>
                            <td style="text-align:right;">$ <?= $this->erp->formatMoney($row->grand_total); ?></td>
                        </tr>
                        <?php
                        // if ($pos_paid < $inv->grand_total) {
                        // 	if(count($payments) > 1){
                        // 		foreach($payments as $payment) {
                        // 			$us_paid=$this->erp->formatMoney($payment->pos_paid);
                        // 			if($inv->other_cur_paid){
                        // 			$riel_paid=$inv->other_cur_paid . '  ៛' ;
                        // 			}else{}
                        // 		}
                        // }else{
                        // 	$us_paid=$this->erp->formatMoney($pos_paid);
                        // 			if($inv->other_cur_paid){
                        // 	$riel_paid=$inv->other_cur_paid . '  ៛' ;
                        // 			}else{}
                        // 	}
                        // }
                        ?>

                    </table>
                    <br>
                    <br>
                    <hr>
                    <?php
                    $total += $row->grand_total;
                } ?>
                <table class="received" style="width:100%;margin-top: 5px;">
                    <tr>
                        <th style="border-left:2px solid #000;border-top:2px solid #000;border-right:none;padding-right: 12px;width:64%; text-align: left;"
                            class="text-right">Total Amount (USD)
                        </th>
                        <th style="border-top:2px solid #000;">:</th>
                        <th style="border-right:2px solid #000;border-top:2px solid #000;border-left:none; text-align: right;"
                            class="text-right">$<?= $this->erp->formatMoney($total); ?></th>
                    </tr>
                    <?php if ($paid->amount != 0) { ?>
                        <tr>
                            <th style="border-left:2px solid #000;border-top:none; border-right:none;padding-right: 12px;width:64%; text-align: left;"
                                class="text-right">Received (USD)
                            </th>
                            <th>:</th>
                            <th style="border-right:2px solid #000;border-top:none;border-left:none; text-align: right;"
                                class="text-right">$<?= $this->erp->formatMoney($paid->amount); ?></th>
                        </tr>
                        <tr>
                            <th style="border-left:2px solid #000;border-top:none; border-bottom:2px solid; border-right:none;padding-right: 12px;width:64%; text-align: left;"
                                class="text-right">Balance (USD)
                            </th>
                            <th style="border-bottom:2px solid #000;">:</th>
                            <th style="border-right:2px solid #000;border-top:none; border-bottom:2px solid;border-left:none; text-align: right;"
                                class="text-right">$<?= $this->erp->formatMoney($total - $paid->amount); ?></th>
                        </tr>
                    <?php } ?>
                </table>
                <div style="text-align:center;font-size:11px;margin-top:10px;">
                    <?= $biller->invoice_footer; ?> <br>
                    ~ ~ ~ <b>CloudNet</b> &nbsp;&nbsp;&nbsp;&nbsp;<span
                            style="font-size:12px;">www.cloudnet.com.kh</span> ~ ~ ~
                </div>

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

                <?= $row->note ? '<p class="text-left"><strong>' . lang("note") . ': ' . $this->erp->decode_html($row->note) . '</strong></p>' : ''; ?>
                <?= $row->staff_note ? '<p class="no-print"><strong>' . lang('staff_note') . ':</strong> ' . $this->erp->decode_html($row->staff_note) . '</p>' : ''; ?>
            </div>
            <?php $this->erp->qrcode('link', urlencode(site_url('pos/view/' . $row->id)), 2); ?>
            <div class="text-center">
                <div style="clear:both;"></div>
            </div>

        </div>
    </div>
</div>
</body>
</html>