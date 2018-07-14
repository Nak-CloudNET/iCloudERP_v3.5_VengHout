<?php if ($logo) { ?>
    <div class="text-center" style="margin-bottom:20px;">
             <p><b>LIST QUOTATION</b></p>
    </div>
<?php } ?>
<div class="well well-sm">
    <div class="row bold">
        <div class="col-xs-5">
        <p class="bold">
            <?= lang("ref"); ?>: <?= $inv->reference_no; ?><br>
            <?= lang("date"); ?>: <?= $this->erp->hrld($inv->date); ?><br>
            <?= lang("sale_status"); ?>:

            <?php if ($inv->status == 'approved') { ?>
                <span class="label label-success"><?= ucfirst($inv->status); ?></span>
            <?php } elseif ($inv->status == 'pending') { ?>
                <span class="label label-warning"><?= ucfirst($inv->status); ?></span>
            <?php } else { ?>
                <span class="label label-danger"><?= ucfirst($inv->status); ?></span>
            <?php } ?>
            <br>
        </p>
        </div>
        <div class="col-xs-6 text-right">
            <?php $br = $this->erp->save_barcode($inv->reference_no, 'code39', 70, false); ?>
            <img src="<?= base_url() ?>assets/uploads/barcode<?= $this->session->userdata('user_id') ?>.png"
                 alt="<?= $inv->reference_no ?>" style="width: 280px;"/>
            <?php $this->erp->qrcode('link', urlencode(site_url('quotes/view/' . $inv->id)), 2); ?>
            <img src="<?= base_url() ?>assets/uploads/qrcode<?= $this->session->userdata('user_id') ?>.png"
                 alt="<?= $inv->reference_no ?>" style="width: 70px;"/>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="clearfix"></div>
</div>

<div class="row" style="margin-bottom:15px;">
<div class="col-xs-5">
                    <table>
                        <tr>
                            <td><?=$this->lang->line("from");?></td>
                            <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                            <td><?="<span style='font-weight:bold; font-size:17px;'>". ($biller->company != '-' ? $biller->company : $biller->name) ."</span>";?></td>
                        </tr>
                        <tr>
                            <td><?=lang("address") ?></td>
                            <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                            <td><?=($biller->address ? $biller->address." " : ''). ($biller->city ? $biller->city . " " . $biller->postal_code . " " . $biller->state . " " : '') . ($biller->country ? $biller->country : ''); ?></td>
                        </tr>
                        <?php //if ($supplier->company == ""){?>
                        <tr>
                            <td>Attn</td>
                            <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                            <td><?=$biller->name;?></td>
                        </tr>
                        <?php //}?>
                        <?php if ($biller->phone !='' || $biller->email !=''): ?>
                        <tr>
                            <td><?=lang("contact") ?></td>
                            <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                            <td><?=($biller->phone ? $biller->phone.'/'.$biller->email : $biller->email) ?></td>
                        </tr>
                        <?php endif ?>
                    </table>
                </div>
    <div class="col-xs-5">
                    <table>
                        <tr>
                            <td><?=$this->lang->line("to");?></td>
                            <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                            <td><?="<span style='font-weight:bold; font-size:17px;'>". ($customer->company ? $customer->company : $customer->name)."</span>";?></td>
                        </tr>
                        <tr>
                            <td><?=lang("address") ?></td>
                            <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                            <td><?=$customer->address?></td>
                        </tr>
                        <tr>
                            <td>Attn</td>
                            <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                            <td><?=$customer->name;?></td>
                        </tr>
                        <?php if ($customer->phone !='' || $customer->email !=''): ?>
                        <tr>
                            <td><?=lang("contact") ?></td>
                            <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                            <td><?=($customer->phone ? $customer->phone.'/'.$customer->email : $customer->email) ?></td>
                        </tr>
                        <?php endif ?>
                    </table>
                </div>
</div>

<div class="table-responsive">
    <table class="table table-bordered table-hover table-striped print-table order-table">

        <thead>

        <tr>
            <th style="text-align: center;"><?= lang("no"); ?></th>
            <th style="text-align: center;"><?= lang("image"); ?></th>
            <th style="text-align: center;width: 200px;"><?= lang("description"); ?></th>
            <th style="text-align: center;"><?= lang("qoh"); ?></th>
            <th style="text-align: center;"><?= lang("quantity"); ?></th>
            <th style="text-align: center;"><?= lang("unit_price"); ?></th>
            <?php
            if ($Settings->product_discount) {
                echo '<th style="text-align: center;">' . lang("discount") . '</th>';
            }
            if ($Settings->tax1) {
                echo '<th style="text-align: center;">' . lang("tax") . '</th>';
            }
            ?>
            <th style="text-align: center;"><?= lang("amount"); ?>(<?= $default_currency->code; ?>)</th>
        </tr>

        </thead>

        <tbody>
        
        <?php $r = 1;
        $tax_summary = array();
        foreach ($rows as $row):
        ?>
            <tr>
                <td style="text-align:center; width:40px; vertical-align:middle;"><?= $r; ?></td>
                <td style="text-align:center; vertical-align:middle;"><?= '<img class="img-rounded img-thumbnail" style="width:60px;height:60px;" src="assets/uploads/thumbs/'.$row->image.'">' ?></td>
                <td style="vertical-align:middle;">
                    <?= $row->product_name . " (" . $row->product_code . ")" . ($row->variant ? ' (' . $row->variant . ')' : ''); ?>
                    <?= $row->details ? '<br>' . $row->details : ''; ?>
                </td>
                <td style="width: 80px; text-align:center; vertical-align:middle;"><?= $this->erp->formatQuantity($row->qoh); ?></td>
                <td style="width: 80px; text-align:center; vertical-align:middle;"><?= $this->erp->formatQuantity($row->quantity); ?></td>
                
                
                <td style="text-align:right; width:100px;"><?= $this->erp->formatMoney($row->unit_price); ?></td>
                <?php
                if ($Settings->product_discount) {
                    $percentage = '%';
                    $discount = $row->discount;
                    $dpos = strpos($discount, $percentage);
                    echo '<td style="width: 100px; text-align:right; vertical-align:middle;">' .($dpos == true ? '<small>('.$discount.')</small>' : '').' '. $this->erp->formatMoney($row->item_discount) . '</td>';
                }
                if ($Settings->tax1) {
                    echo '<td style="width: 120px; text-align:right; vertical-align:middle;">' . ($row->item_tax != 0 && $row->tax_name ? '<small>('.$row->tax_name.')</small>' : '') . ' ' . $this->erp->formatMoney($row->item_tax) . '</td>';
                }
                ?>
                <td style="text-align:right; width:120px;"><?= $this->erp->formatMoney($row->subtotal); ?></td>
            </tr>
            <?php
            $r++;
        endforeach;
        ?>
        </tbody>
        <tfoot>
        <?php
        $col = 6;
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
                <td colspan="8"
                    style="text-align:right;"><?= lang("total"); ?>
                    
                </td>
                <?php
                // if ($Settings->tax1) {
                //     echo '<td style="text-align:right;">' . $this->erp->formatMoney($inv->product_tax) . '</td>';
                // }
                // if ($Settings->product_discount && $inv->product_discount != 0) {
                //     echo '<td style="text-align:right;">' . $this->erp->formatMoney($inv->product_discount) . '</td>';
                // }
                ?>
                <td style="text-align:right;"><?= $this->erp->formatMoney($inv->total); ?></td>
            </tr>
        <?php } ?>

        <?php if ($inv->order_discount != 0) {
            echo '<tr><td colspan="' . 8 . '" style="text-align:right;">' . lang("order_discount") . '</td><td style="text-align:right;">' . $this->erp->formatMoney($inv->order_discount) . '</td></tr>';
        }
        ?>
        <?php if ($inv->shipping != 0) {
            echo '<tr><td colspan="' . 8 . '" style="text-align:right;">' . lang("shipping") . '</td><td style="text-align:right;">' . $this->erp->formatMoney($inv->shipping) . '</td></tr>';
        }
        ?>
        <?php if ($Settings->tax2 && $inv->order_tax != 0) {
            echo '<tr><td colspan="' . 8 . '" style="text-align:right;">' . $inv->tax_name . '</td><td style="text-align:right;">' . $this->erp->formatMoney($inv->order_tax) . '</td></tr>';
        }
        ?>
        
        <tr>
            <td colspan="8"
                style="text-align:right; font-weight:bold;"><?= lang("total_amount"); ?>
            </td>
            <td style="text-align:right; font-weight:bold;"><?= $this->erp->formatMoney($inv->grand_total - $deposit->deposit_amount); ?></td>
        </tr>
        </tfoot>
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
            <?php } ?>
    </div>

    <div class="col-xs-5 pull-right">
        <div class="well well-sm">
            <p>
                <?= lang("created_by"); ?>: <?= $created_by->first_name . ' ' . $created_by->last_name; ?> <br>
                <?= lang("date"); ?>: <?= $this->erp->hrld($inv->date); ?>
            </p>
            <?php if ($inv->updated_by) { ?>
            <p>
                <?= lang("updated_by"); ?>: <?= $updated_by->first_name . ' ' . $updated_by->last_name;; ?><br>
                <?= lang("update_at"); ?>: <?= $this->erp->hrld($inv->updated_at); ?>
            </p>
            <?php } ?>
        </div>
    </div>
</div>
<?php if (!$Supplier || !$Customer) { ?>
    <div class="buttons">
        <?php if ($inv->attachment) { ?>
            <div class="btn-group">
                <a href="<?= site_url('welcome/download/' . $inv->attachment) ?>" class="tip btn btn-primary" title="<?= lang('attachment') ?>">
                    <i class="fa fa-chain"></i>
                    <span class="hidden-sm hidden-xs"><?= lang('attachment') ?></span>
                </a>
            </div>
        <?php } ?>        
    </div>
<?php } ?>