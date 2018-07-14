<?php if ($logo) { ?>
    <div class="text-center" style="margin-bottom:20px;">
             <p><b>LIST SALE</b></p>
    </div>
<?php } ?>
<div class="well well-sm">
    <div class="row bold" style="font-size:12px;">
        <div class="col-xs-5">
        <p class="bold">
            <?= lang("ref"); ?>: <?= $inv->reference_no; ?><br>
            <?= lang("date"); ?>: <?= $this->erp->hrld($inv->date); ?><br>
            
            <?= lang("sale_status"); ?>:
            <?php if ($inv->sale_status == 'completed') { ?>
                <span class="label label-success" ><?= lang($inv->sale_status); ?></span>
            <?php } elseif ($inv->sale_status == 'pending') { ?>
                <span class="label label-warning" ><?= lang($inv->sale_status); ?></span>
            <?php } else { ?>
                <span class="label label-danger" ><?= lang($inv->sale_status); ?></span>
            <?php } ?>
            <br>

            <?= lang("payment_status"); ?>:
            <?php if ($inv->payment_status == 'paid') { ?>
                <span class="label label-success" ><?= lang($inv->payment_status); ?></span>
            <?php } elseif ($inv->payment_status == 'partial') { ?>
                <span class="label label-info" ><?= lang($inv->payment_status); ?></span>
            <?php } elseif($inv->payment_status == 'pending'){?>
                <span class="label label-warning" ><?= lang($inv->payment_status); ?></span>
            <?php }else { ?>
                <span class="label label-danger" ><?= lang($inv->payment_status); ?></span>
            <?php } ?>

        </p>
        </div>
        <div class="col-xs-6 text-right">
            <p style="font-size:16px; margin:0 !important;"><?= lang("INVOICE"); ?></p>
            <?php $br = $this->erp->save_barcode($inv->reference_no, 'code39', 70, false); ?>
            <img height="45px" src="<?= base_url() ?>assets/uploads/barcode<?= $this->session->userdata('user_id') ?>.png"
                 alt="<?= $inv->reference_no ?>"/>
            <?php $this->erp->qrcode('link', urlencode(site_url('sales/view/' . $inv->id)), 2); ?>
            <img height="45px" src="<?= base_url() ?>assets/uploads/qrcode<?= $this->session->userdata('user_id') ?>.png"
                 alt="<?= $inv->reference_no ?>"/>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="clearfix"></div>
</div>

<div class="row" style="margin-bottom:15px;">
                <div class="col-xs-5">
                    <?php echo $this->lang->line("from"); ?>:<br/>
                    <?php if ($Settings->system_management == 'project') { ?>
                        <h2 style="margin-top:10px;"><?= $Settings->site_name; ?></h2>
                    <?php } else { ?>
                        <h2 style="margin-top:10px;"><?= $biller->company != '-' ? $biller->company : $biller->name; ?></h2>
                    <?php } ?>

                    <?= $biller->company ? "" : "Attn: " . $biller->name ?>

                    <?php
                    echo $biller->address . "<br>" . $biller->city . " " . $biller->postal_code . " " . $biller->state . "<br>" . $biller->country;
                    echo lang("tel") . ": " . $biller->phone . "<br>" . lang("email") . ": " . $biller->email;
                    ?>
                </div>
                <div class="col-xs-6">
                    <?php echo $this->lang->line("to"); ?>:<br/>
                    <h2 style="margin-top:10px;"><?= $customer->company ? $customer->company : $customer->name; ?></h2>
                    <?= $customer->company ? "" : "Attn: " . $customer->name ?>

                    <?php
                    echo $customer->address . "<br>" . $customer->city . " " . $customer->postal_code . " " . $customer->state . "<br>" . $customer->country;
                    echo lang("tel") . ": " . $customer->phone . "<br>" . lang("email") . ": " . $customer->email;
                    ?>
                </div>
            </div>

<div class="table-responsive">
    <table class="table table-bordered table-hover table-striped print-table order-table">
        <thead>
        <tr>
            <th><?= lang("no"); ?></th>
            <?php if($Settings->show_code == 1 && $Settings->separate_code == 1) { ?>
            <th><?= lang('product_code'); ?></th>
            <?php } ?>
            <th><?= lang("description"); ?></th>
            <th><?= lang("unit"); ?></th>
            <th><?= lang("quantity"); ?></th>
            <th><?= lang("unit_price"); ?></th>
            <?php
            if ($Settings->tax1) {
                echo '<th>' . lang("tax") . '</th>';
            }
            if ($Settings->product_discount && $inv->product_discount != 0) {
                echo '<th>' . lang("discount") . '</th>';
            }
            ?>
            <th><?= lang("subtotal"); ?></th>
        </tr>

        </thead>

        <tbody>

        <?php $r = 1;
        $tax_summary = array();
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
        if($Settings->show_code == 0) {
            $product_name_setting = $row->product_name . ($row->variant ? ' (' . $row->variant . ')' : '');
        }else {
            if($Settings->separate_code == 0) {
                $product_name_setting = $row->product_name . " (" . $row->product_code . ")" . ($row->variant ? ' (' . $row->variant . ')' : '');
            }else {
                $product_name_setting = $row->product_name . ($row->variant ? ' (' . $row->variant . ')' : '');
            }
        }
        ?>
            <tr>
                <td style="text-align:center; width:40px; vertical-align:middle;"><?= $r; ?></td>
                <?php if($Settings->show_code == 1 && $Settings->separate_code == 1) { ?>
                <td style="vertical-align:middle;">
                    <?= $row->product_code ?>
                </td>
                <?php } ?>
                <td style="vertical-align:middle;">
                    <?= $product_name_setting ?>
                    <?= $row->details ? '<br>' . $row->details : ''; ?>
                    <?= $row->serial_no ? '<br>' . $row->serial_no : ''; ?>
                </td>
                <td style="width: 80px; text-align:center; vertical-align:middle;"><?php echo $product_unit ?></td>
                <td style="width: 80px; text-align:center; vertical-align:middle;"><?= $this->erp->formatQuantity($row->quantity); ?></td>
                <td style="text-align:right; width:100px;"><?= $this->erp->formatMoney($row->unit_price); ?></td>
                <?php
                if ($Settings->tax1) {
                    echo '<td style="width: 100px; text-align:right; vertical-align:middle;">' . ($row->item_tax != 0 && $row->tax_code ? '<small>('.$row->tax_code.')</small>' : '') . ' ' . $this->erp->formatMoney($row->item_tax) . '</td>';
                }
                if ($Settings->product_discount && $inv->product_discount != 0) {
                    echo '<td style="width: 100px; text-align:right; vertical-align:middle;">' . ($row->discount != 0 ? '<small>(' . $row->discount . ')</small> ' : '') . $this->erp->formatMoney($row->item_discount) . '</td>';
                }
                ?>
                <td style="text-align:right; width:120px;"><?= $row->subtotal!=0?$this->erp->formatMoney($row->subtotal):$free; 
                    $total += $row->subtotal;
                    ?></td>
            </tr>
            <?php
            $r++;
        endforeach;
        ?>
        <?php
        $col = 4;
        if($Settings->show_code == 1 && $Settings->separate_code == 1) {
            $col += 1;
        }
        if ($Settings->product_discount && $inv->product_discount != 0) {
            $col++;
        }
        if ($Settings->tax1) {
            $col++;
        }
        if ($Settings->product_discount && $inv->product_discount != 0 && $Settings->tax1) {
            $tcol = $col - 2;
        } elseif ($Settings->product_discount && $inv->product_discount != 0) {
            $tcol = $col - 1;
        } elseif ($Settings->tax1) {
            $tcol = $col - 1;
        } else {
            $tcol = $col;
        }
        ?>
        <?php if ($inv->grand_total != $inv->total) { ?>
            <tr>
                <td></td>
                <td colspan="<?= $tcol; ?>"
                    style="text-align:right;"><?= lang("total"); ?>
                    (<?= $default_currency->code; ?>)
                </td>
                <?php
                if ($Settings->tax1) {
                    echo '<td style="text-align:right;">' . $this->erp->formatMoney($inv->product_tax) . '</td>';
                }
                if ($Settings->product_discount && $inv->product_discount != 0) {
                    echo '<td style="text-align:right;">' . $this->erp->formatMoney($inv->product_discount) . '</td>';
                }
                ?>
                <td style="text-align:right;"><?= $this->erp->formatMoney($inv->total + $inv->product_tax); ?></td>
            </tr>
        <?php } ?>
        <?php if ($return_sale && $return_sale->surcharge != 0) {
            echo '<tr><td></td><td colspan="' . $col . '" style="text-align:right;">' . lang("return_surcharge") . ' (' . $default_currency->code . ')</td><td style="text-align:right;">' . $this->erp->formatMoney($return_sale->surcharge) . '</td></tr>';
        }
        ?>
        <?php if ($inv->order_discount != 0) {
            echo '<tr><td></td><td colspan="' . $col . '" style="text-align:right;">' . lang("order_discount") . ' (' . $default_currency->code . ')</td><td style="text-align:right;">' . $this->erp->formatMoney($inv->order_discount) . '</td></tr>';
        }
        ?>
        <?php if ($Settings->tax2 && $inv->order_tax != 0) {
            echo '<tr><td></td><td colspan="' . $col . '" style="text-align:right;">' . lang("order_tax") . ' (' . $default_currency->code . ')</td><td style="text-align:right;">' . $this->erp->formatMoney($inv->order_tax) . '</td></tr>';
        }
        ?>
        <?php if ($inv->shipping != 0) {
            echo '<tr><td></td><td colspan="' . $col . '" style="text-align:right;">' . lang("shipping") . ' (' . $default_currency->code . ')</td><td style="text-align:right;">' . $this->erp->formatMoney($inv->shipping) . '</td></tr>';
        }
        ?>
        <tr>
            <td></td>
            <td colspan="<?= $col; ?>"
                style="text-align:right; font-weight:bold;"><?= lang("total_amount"); ?>
                (<?= $default_currency->code; ?>)
            </td>
            <td style="text-align:right; font-weight:bold;"><?= $this->erp->formatMoney($inv->grand_total); ?></td>
        </tr>
        <tr>
            <td></td>
            <td colspan="<?= $col; ?>"
                style="text-align:right; font-weight:bold;"><?= lang("paid"); ?>
                (<?= $default_currency->code; ?>)
            </td>
            <td style="text-align:right; font-weight:bold;"><?= $this->erp->formatMoney($inv->paid); ?></td>
        </tr>
        <tr>
            <td></td>
            <td colspan="<?= $col; ?>"
                style="text-align:right; font-weight:bold;"><?= lang("balance"); ?>
                (<?= $default_currency->code; ?>)
            </td>
            <td style="text-align:right; font-weight:bold;"><?= $this->erp->formatMoney($inv->grand_total - $inv->paid); ?></td>
        </tr>
        </tbody>
    </table>
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
    <br/>
    
    <br/>
    <div class="row">
        <div class="clearfix"></div>
        <div class="col-xs-2  pull-left" style="text-align:center">
            <hr/>
            <p><?= lang("seller"); ?>
        </div>
        <div class="col-xs-2  pull-right" style="text-align:center">
            <hr/>
            <p><?= lang("customer"); ?>
        </div>
        <div class="col-xs-3  pull-right" style="text-align:center">
            <hr/>
            <p><?= lang("Account"); ?>
        </div>
        <div class="col-xs-3  pull-right" style="text-align:center">
            <hr/>
            <p><?= lang("Ware House"); ?>
        </div>
    </div>
    <div class="col-xs-5 pull-right no-print" >
        <div class="well well-sm">
            <p>
                <?= lang("created_by"); ?>: <?= $created_by->first_name . ' ' . $created_by->last_name; ?> <br>
                <?= lang("date"); ?>: <?= $this->erp->hrld($inv->date); ?>
            </p>
            <?php if ($inv->updated_by) { ?>
            <p>
                <?= lang("updated_by"); ?>: <?= $updated_by->first_name . ' ' . $updated_by->last_name;?><br>
                <?= lang("update_at"); ?>: <?= $this->erp->hrld($inv->updated_at); ?>
            </p>
            <?php } ?>
        </div>
    </div>
</div> 