<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $this->lang->line("purchase") . " " . $inv->reference_no; ?></title>
    <link href="<?php echo $assets ?>styles/style.css" rel="stylesheet">
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
    </style>
</head>

<body>
<div id="wrap">
    <div class="row">
        <div class="col-lg-12">
            <?php if ($logo) {?>
                <div class="text-center" style="margin-bottom:20px;">
                    <img src="<?=base_url() . 'assets/uploads/logos/' . $Settings->logo;?>"
                         alt="<?=$Settings->site_name;?>">
                </div>
            <?php }
            ?>
            <div class="well well-sm">
                <div class="row bold">
                    <div class="col-xs-4"><?=lang("date");?>: <?=$this->erp->hrld($inv->date);?>
                        <br><?=lang("ref");?>: <?=$inv->reference_no;?><br><?= lang("status"); ?>:
                        <?php if ($inv->status == 'pending') { ?>
                            <span class="label label-warning"><?= ucfirst($inv->status); ?></span>
                        <?php } else if ($inv->status == 'reject') { ?>
                            <span class="label label-danger"><?= ucfirst($inv->status); ?></span>
                        <?php } else { ?>
                            <span class="label label-success"><?= ucfirst($inv->status); ?></span>
                        <?php } ?>
                        <br>

                        <?//= lang("payment_status"); ?>
                        <?php //if ($inv->order_status == 'completed') { ?>
                            <!-- <span class="label label-success"><?//= ucfirst($inv->order_status); ?></span> -->
                        <?php //} elseif ($inv->order_status == 'partial') { ?>
                            <!-- <span class="label label-info"><?//= ucfirst($inv->order_status); ?></span> -->
                        <?php //} else { ?>
                            <!-- <span class="label label-warning"><?//= ucfirst($inv->order_status); ?></span> -->
                        <?php //} ?></div>
                    <div class="col-xs-7 pull-right text-right">
                        <?php $br = $this->erp->save_barcode($inv->reference_no, 'code39', 35, false, $inv->id);?>
                        <img src="<?=base_url()?>assets/uploads/barcode<?=$this->session->userdata('user_id') . $inv->id;?>.png"
                             alt="<?=$inv->reference_no?>"/>
                        <?php $this->erp->qrcode('link', urlencode(site_url('purchases/view/' . $inv->id)), 1, false, $inv->id);?>
                        <img src="<?=base_url()?>assets/uploads/qrcode<?=$this->session->userdata('user_id') . $inv->id;?>.png"
                             alt="<?=$inv->reference_no?>"/>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="clearfix"></div>
            </div>

            <div class="clearfix"></div>
            <div class="row padding10">
                <div class="col-xs-6">
                <table>
                    <tr>
                        <td><?=$this->lang->line("from");?></td>
                        <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                        <td><?="<span style='font-weight:bold; font-size:17px;'>". $inv->company  ."</span>";?></td>
                    </tr>
                    <?php //if ($inv->username != ""){?>
                    <tr>
                        <td>Attn</td>
                        <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                        <td><?=$inv->username;?></td>
                    </tr>
                    <?php //}?>
                    <tr>
                        <td><?=lang("address") ?></td>
                        <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                        <td><?=($warehouse->address ? $warehouse->address." " : ''). ($warehouse->city ? $warehouse->city . " " . $warehouse->postal_code . " " . $warehouse->state . " " : '') . ($warehouse->country ? $warehouse->country : ''); ?></td>
                    </tr>
                    <?php if ($warehouse->phone !='' || $warehouse->email !=''): ?>
                    <tr>
                        <td><?=lang("contact") ?></td>
                        <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                        <td><?=($warehouse->phone ? $warehouse->phone.'/'.$warehouse->email : $warehouse->email) ?></td>
                    </tr>
                    <?php endif ?>
                </table>
                </div>
                <div class="col-xs-5">
                    <table>
                    <tr>
                        <td><?=$this->lang->line("to");?></td>
                        <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                        <td><?="<span style='font-weight:bold; font-size:17px;'>". ($supplier->company ? $supplier->company : $supplier->name) ."</span>";?></td>
                    </tr>
                    <?php //if ($supplier->company == ""){?>
                    <tr>
                        <td>Attn</td>
                        <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                        <td><?=$supplier->name;?></td>
                    </tr>
                    <?php //}?>
                    <tr>
                        <td><?=lang("address") ?></td>
                        <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                        <td><?=($supplier->address ? $supplier->address : ""). ($supplier->city ? "<br>" . $supplier->city : "") . ($supplier->postal_code ? " " .$supplier->postal_code : "") . ($supplier->state ? " " .$supplier->state : "") .  ($supplier->country ? "<br>" .$supplier->country : ""); ?></td>
                    </tr>
                    <?php if ($supplier->phone !='' || $supplier->email !=''): ?>
                    <tr>
                        <td><?=lang("contact") ?></td>
                        <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                        <td><?=($supplier->phone ? $supplier->phone.'/'.$supplier->email : $supplier->email) ?></td>
                    </tr>
                    <?php endif ?>
                </table>
                </div>
            </div>
            <p>&nbsp;</p>

            <div class="clearfix"></div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead>

                    <tr>
                        <th><?= lang("no"); ?></th>
                        <th><?= lang("description"); ?></th> 
                        <th><?= lang("quantity"); ?></th>
                        <th><?= lang("unit"); ?></th> 
                        <?php if ($Settings->shipping == '1') { ?>
                            <!-- <th><?= lang("qty_received"); ?></th> -->
                        <?php } ?>
                        <?php
                            if ($inv->status == 'partial') {
                                echo '<th>'.lang("received").'</th>';
                            }
                        ?> 
                        <?php if($Owner || $Admin || $GP['purchase_order-cost']) { ?>
                            <th><?= lang("unit_cost"); ?></th>
                        <?php } ?>
                        
                        <?php
                            if ($Settings->product_discount) {
                                echo '<th>' . lang("discount") . '</th>';
                            }
                            if ($Settings->tax1) {
                                echo '<th>' . lang("tax") . '</th>';
                            }
                        ?>
                        <th><?= lang("amount").'('.$default_currency->code.')'; ?></th>

                    </tr>

                    </thead>
                    <tbody>

                    <?php $r = 1;
                    $tax_summary = array();
                    foreach ($rows as $row):
                    ?>
                        <tr>
                            <td style="text-align:center; width:40px; vertical-align:middle;"><?= $r; ?></td>
                            <td style="vertical-align:middle;width: 180px;">
                                <?= $row->product_name . " (" . $row->product_code . ")"; ?>
                                <?= $row->details ? '<br>' . $row->details : ''; ?>
                                <?= ($row->expiry && $row->expiry != '0000-00-00') ? '<br>' . $this->erp->hrsd($row->expiry) : ''; ?>
                            </td>
                            <td style="width: 80px; text-align:center; vertical-align:middle;"><?= $this->erp->formatQuantity($row->quantity); ?></td>
                            <td style="text-align:center; width:40px; vertical-align:middle;">
                                <?php
                                    if ($row->variant != '') {
                                        echo $row->variant;
                                    } else {
                                        echo $row->unit;
                                    }
                                ?>
                            </td>
                            <?php
                            // if ($Settings->shipping == '1') {
                            //     echo '<td style="text-align:center;vertical-align:middle;width:80px;">'.$this->erp->formatQuantity($row->quantity_received).'</td>';
                            // }
                            ?>
                            <?php if($Owner || $Admin || $GP['purchase_order-cost']) {?>
                                <td style="text-align:right; width:100px;"><?= $this->erp->formatMoney($row->unit_cost); ?></td>
                            <?php } ?>
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
                    if ($Settings->shipping == '1') {
                        $col = 4;
                    } else {
                        $col = 3;
                    }
                    if($Owner || $Admin || $GP['purchase_order-cost']){
                        $col++;
                    }
                    if ($inv->status == 'partial') {
                        $col++;
                    }
                    if ($Settings->product_discount) {
                        $col++;
                    }
                    if ($Settings->tax1) {
                        $col++;
                    }
                    if ($Settings->product_discount && $Settings->tax1) {
                        $tcol = $col - 2;
                    } elseif ($Settings->product_discount) {
                        $tcol = $col - 1;
                    } elseif ($Settings->tax1) {
                        $tcol = $col - 1;
                    } else {
                        $tcol = $col;
                    }
                    ?>
                        <?php if ($inv->grand_total != $inv->total) { ?>
                            <tr>
                                <td colspan="7"
                                    style="text-align:right; padding-right:10px;"><?= lang("total"); ?>
                                    <!-- (<?= $default_currency->code; ?>) -->
                                </td>
                                <?php
                                // if ($Settings->product_discount) {
                                //     echo '<td style="text-align:right;">' . $this->erp->formatMoney($inv->product_discount) . '</td>';
                                // }
                                // if ($Settings->tax1) {
                                //     echo '<td style="text-align:right;">' . $this->erp->formatMoney($inv->product_tax) . '</td>';
                                // }
                                ?>
                                <td style="text-align:right;"><?= $this->erp->formatMoney($inv->total); ?></td>
                            </tr>
                        <?php } ?>

                        <?php if ($inv->order_discount != 0) {
                            echo '<tr><td colspan="' . $col . '" style="text-align:right; ">' . lang("order_discount") .'</td><td style="text-align:right;">' . $this->erp->formatMoney($inv->order_discount) . '</td></tr>';
                        }
                        ?>
                        <?php if ($inv->shipping != 0) {
                            echo '<tr><td colspan="' . $col . '" style="text-align:right;">' . lang("shipping") . '</td><td style="text-align:right;">' . $this->erp->formatMoney($inv->shipping) . '</td></tr>';
                        }
                        ?>
                        <?php if ($Settings->tax2 && $inv->order_tax != 0) {
                            echo '<tr><td colspan="' . $col . '" style="text-align:right; ">' .$inv->tax_name.'</td><td style="text-align:right;">' . $this->erp->formatMoney($inv->order_tax) . '</td></tr>';
                        }
                        ?>
                        <tr>
                            <td colspan="<?= $col; ?>"
                                style="text-align:right; font-weight:bold;"><?= lang("total_amount"); ?>
                                <!-- (<?= $default_currency->code; ?>) -->
                            </td>
                            <td style="text-align:right; font-weight:bold;"><?= $this->erp->formatMoney($inv->grand_total); ?></td>
                        </tr>
                        <?php if($inv->paid >0){ ?>
                        <!--<tr>
                            <td colspan="<?= $col; ?>"
                                style="text-align:right; font-weight:bold;"><?= lang("Deposit"); ?>
                                (<?= $default_currency->code; ?>)
                            </td>
                            <td style="text-align:right; font-weight:bold;"><?= $this->erp->formatMoneyPurchase($inv->paid); ?></td>
                        </tr>
                        <tr>
                            <td colspan="<?= $col; ?>"
                                style="text-align:right; font-weight:bold;"><?= lang("balance"); ?>
                                (<?= $default_currency->code; ?>)
                            </td>
                            <td style="text-align:right; font-weight:bold;"><?= $this->erp->formatMoneyPurchase($inv->grand_total - $inv->paid); ?></td>
                        </tr>-->
                        <?php } ?>
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
                        <?php
                        }
                        ?>
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

        </div>
    </div>
</div>
</body>
</html>