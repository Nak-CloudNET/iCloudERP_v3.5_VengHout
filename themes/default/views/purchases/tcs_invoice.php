<?php //$this->erp->print_arrays($quote_items);?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $this->lang->line("received_item") . " " . $inv->reference_no; ?></title>
    <link href="<?php echo $assets ?>styles/theme.css" rel="stylesheet">
    <style type="text/css">
        html, body {
            height: 100%;
            background: #FFF;
        }

        .table, .table tr td, .table tr th {
            border: 1px solid #CCC !important;
        }

        .row {
            margin-top: 5px !important;
        }
        .col-xs-12 #text{
            margin-top: -10px !important;
        }
        p {
            font-size: 12px !important;
        }
        table thead th {
            font-size: 12px;
        }
        table tbody tr td, table tfoot tr td {
            font-size: 11px;
            vertical-align: middle !important;
        }
        @media print,screen{
            .row {
                margin-top: 5px !important;
            }
            #left-box {
                margin-left: 5px !important;
                width: 342px !important;
            }
            span {
                color: white !important;
                font-weight: bold;
            }
            .col-xs-3 {
                color: white !important;
                background-color: black !important;
            }
            .col-xs-12 #text{
                margin-top: -10px !important;
            }
            p {
                font-size: 11px !important;
            }
            table thead th{
                font-size: 12px !important;
            }
            table tbody tr td, table tfoot tr td {
                font-size: 10px !important;
                vertical-align: middle !important;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="col-md-3 col-xs-3 text-center" style="background-color: white !important;">
                    <!-- logo -->
                    <?php if($biller->logo) {?>
                        <img src="<?= base_url() . 'assets/uploads/logos/' . $biller->logo; ?>" alt="<?= $biller->company; ?>" style="width: 90%;">
                    <?php }?>
                </div>
                <div class="col-md-9 col-xs-9" style="border: 1px solid #CCC">
                    <?php 
                        echo "<p>".$biller->company."</p>";
                        echo "<p style='margin-top: -10px;'>".$biller->address."</p>";
                        echo "<p style='margin-top: -10px;'>".'Tel. +855 '.$biller->phone."</p>";
                        echo "<p style='margin-top: -10px;'>".'E-Mail. '.$biller->email."</p>";
                     ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="col-md-3 col-xs-3 text-center" style="border: 1px solid black"></div>
                <div class="col-md-9 col-xs-9" style="border: 1px solid black"></div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="col-md-6 col-xs-6" style="border: 1px solid #CCC; padding: 5px; height: 120px;">
                    <div class="col-xs-3" style="color: white; background-color: black; padding: 5px; text-align: center;">
                        <span><?= lang('vender')?>:</span>
                    </div>
                    <div class="col-xs-8" style="padding: 5px;">
                        
                    </div>
                    <div class="col-xs-12">
                        <?php
                            echo "<p style='font-weight:bold;'>".$supplier->company."</p>";
                            echo "<p id='text' style='font-weight:bold;'>".$supplier->address."</p>";
                            echo "<p id='text' style='font-weight:bold;'>".'Email: '.$supplier->email."</p>";
                            echo "<p id='text' style='font-weight:bold;'>".'Tel. '.$supplier->phone."</p>";
                        ?>
                    </div>
                </div>
                <div class="col-md-6 col-xs-6" style="border: 1px solid #CCC; padding: 5px; height: 120px;">
                    <div class="col-xs-4" style="color: white; padding: 5px; text-align: center;">
                       <span><?= ''?>:</span>
                    </div>
                    <div class="col-xs-8" style="padding: 5px;">
                        
                    </div>
                    <div class="col-xs-12">
                        <table>
                            <tr>
                                <td style="font-weight:bold;"><?=lang('purchase_order')?></td>
                                <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                                <td><?=$inv->reference_no?></td>
                            </tr>
                            <tr>
                                <td style="font-weight:bold;"><?=lang('date')?></td>
                                <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                                <td><?=$this->erp->hrld($inv->date)?></td>
                            </tr>
                            <tr>
                                <td style="font-weight:bold;"><?=lang('refer')?></td>
                                <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                                <td>.................................................</td>
                            </tr>
                            <tr>
                                <td style="font-weight:bold;"><?=lang('term')?></td>
                                <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                                <td>.................................................</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
               <div class="col-md-6 col-xs-6" style="border: 1px solid #CCC; padding: 5px; height: 120px;">
                    <div class="col-xs-3" style="color: white; background-color: black; padding: 5px; text-align: center;">
                        <span><?= lang('ship_to')?>:</span>
                    </div>
                    <div class="col-xs-8" style="padding: 5px;">
                        
                    </div>
                    <div class="col-xs-12">
                        <?php
                            echo "<p style='font-weight:bold;'>".$biller->company."</p>";
                            echo "<p id='text' style='font-weight:bold;'>".$biller->address."</p>";
                            echo "<p id='text' style='font-weight:bold;'>".'Email: '.$biller->email."</p>";
                            echo "<p id='text' style='font-weight:bold;'>".'Tel. '.$biller->phone."</p>";
                        ?>
                    </div>
               </div>
               <div class="col-md-6 col-xs-6" style="border: 1px solid #CCC; padding: 5px; height: 120px;">
                    <div class="col-xs-4" style="color: white; padding: 5px; text-align: center;">
                        <span><?= ''?>:</span>
                    </div>
                    <div class="col-xs-8" style="padding: 5px;">
                        
                    </div>
                    <div class="col-xs-12">
                        <table>
                            <tr>
                                <td style="font-weight: bold;"><?=lang('payment_terms')?></td>
                                <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                                <td><?=$inv->description?></td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold;"><?=lang('loading')?></td>
                                <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                                <td>................................................</td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold;"><?=lang('port')?></td>
                                <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                                <td>................................................</td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold;"><?=lang('destination')?></td>
                                <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                                <td><?=$inv->Wname?></td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold;"><?=lang('ship_by')?></td>
                                <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                                <td>................................................</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
               <div class="col-md-6 col-xs-6" style="border: 1px solid #CCC; padding: 5px; height: 70px;">
                    <div class="col-xs-12">
                        <?php
                            echo "<p style='font-weight:bold;'>".lang('note').' : '.$this->erp->decode_html(strip_tags($inv->note))."</p>";
                        ?>
                    </div>
               </div>
               <div class="col-md-6 col-xs-6" style="border: 1px solid #CCC; padding: 5px; height: 70px;">
                    <div class="col-xs-12">
                        <?php
                            echo "<p style='font-weight:bold;'>".lang('drbo').' : '."</p>";
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <table class="table table-bordered table-hover" border="1">
                    <thead>
                        <th style="width: 10px; text-align: center;vertical-align: middle;"><?=strtoupper(lang('item'))?></th>
                        <th style="width: 270px;text-align: center;vertical-align: middle;"><?=strtoupper(lang('description'))?></th>
                        <th style="text-align: center;vertical-align: middle;"><?=strtoupper(lang('qty'))?></th>
                        <th style="text-align: center;vertical-align: middle;"><?=strtoupper(lang('packing'))?></th>
                        <?php if($Owner || $Admin || $GP['purchase_order-cost']) { ?>
                            <th  style="text-align: center; width: 100px;vertical-align: middle;"><?=strtoupper(lang("unit_cost")).'(USD)'; ?></th>
                        <?php } ?>
                        
                        <?php
                            if ($Settings->tax1) {
                                echo '<th  style="text-align: center;vertical-align: middle;">' .strtoupper(lang("tax")) . '</th>';
                            }
                            if ($Settings->product_discount) {
                                echo '<th  style="text-align: center;vertical-align: middle;">' .strtoupper(lang("discount")) . '</th>';
                            }
                        ?>
                        <th style="text-align: center;vertical-align: middle;"><?=strtoupper(lang('amount'))?></th>
                    </thead>
                    <tbody>
                        <?php 
                            $r=1;
                            $sum_qty = 0;
                            foreach($rows as $row) {
                        ?>
                        
                        <tr>
                            <td style="text-align: center; border-top:none !important;border-bottom:none !important;"><?=$r;?></td>
                            <td style="border-top:none !important;border-bottom:none !important;">
                                <?= $row->product_name . " (" . $row->product_code . ")"; ?>
                                <?= $row->details ? '<br>' . $row->details : ''; ?>
                                <?= ($row->expiry && $row->expiry != '0000-00-00') ? '<br>' . $this->erp->hrsd($row->expiry) : ''; ?>
                            </td>
                            <td style="text-align: center;border-top:none !important;border-bottom:none !important;"><?=$this->erp->formatQuantity($row->quantity);?></td>
                            <td style="text-align: center;border-top:none !important;border-bottom:none !important;"><?php
                                    if ($row->variant != '') {
                                        echo $row->variant;
                                    } else {
                                        echo $row->unit;
                                    }
                                ?></td>
                            <td style="border-top:none !important;border-bottom:none !important; text-align: center;">
                                <!-- <div class="row">
                                    <div class="col-xs-2">USD</div> -->
                                    <!-- <div class="col-xs-7"> -->
                                    <?='USD '.$this->erp->formatMoney($row->net_unit_cost);?></div>
                                <!-- </div>  -->
                            </td>
                            <?php
                                if ($Settings->tax1) {
                                    echo '<td style="width: 100px; text-align:right; vertical-align:middle;border-top:none !important;border-bottom:none !important;text-align:center;">' . ($row->item_tax != 0 && $row->tax_code ? '<small>('.$row->tax_code.')</small>' : '') . ' ' .'USD '. $this->erp->formatMoney($row->item_tax) . '</td>';
                                }
                                if ($Settings->product_discount) {
                                    echo '<td style="width: 110px; text-align:right; vertical-align:middle;border-top:none !important;border-bottom:none !important;text-align:center">' . ($row->discount != 0 ? '<small>(' . $row->discount . ')</small> ' : '') .'USD '. $this->erp->formatMoney($row->item_discount) . '</td>';
                                }
                            ?>
                            <td style="width:120px;border-top:none !important;border-bottom:none !important; text-align: center;">
                                <!-- <div class="row"> -->
                                    <!-- <div class="col-xs-1" style="text-align: left;">USD</div> -->
                                    <!-- <div class="col-xs-8" style="text-align: right;"> -->
                                    <?='USD '. $this->erp->formatMoney($row->subtotal); ?>
                                        
                                    <!-- </div>
                                </div>  -->   
                            </td>
                        </tr>
                        <?php 
                            $r++;
                            $sum_qty += $row->quantity;
                        }?>
                        <?php
                            if ($row < 10) {
                                $k = 5 - $row;
                                for ($j=1; $j <= $k; $j++) {
                                    echo
                                        '<tr>
                                            <td style="border-top:none !important;border-bottom:none !important;"></td>
                                            <td style="border-top:none !important;border-bottom:none !important;"></td>
                                            <td style="border-top:none !important;border-bottom:none !important;"></td>
                                            <td style="border-top:none !important;border-bottom:none !important;"></td>
                                            <td style="border-top:none !important;border-bottom:none !important;"></td>
                                            <td style="border-top:none !important;border-bottom:none !important;"></td>
                                            <td style="border-top:none !important;border-bottom:none !important;"></td>
                                            <td style="border-top:none !important;border-bottom:none !important;"></td>
                                        </tr>';
                                }
                            }
                        ?>
                    </tbody>
                    <?php

                        $rspan = 2;

                        if ($inv->order_discount != 0) {
                            $rspan++;
                        }

                        if ($inv->shipping != 0) {
                            $rspan++;
                        }

                        if ($Settings->tax2 && $inv->order_tax != 0) {
                            $rspan++;
                        }
                    ?>
                    <tfoot>
                        <tr>
                            <td></td>
                            <td></td>
                            <td style="text-align: right;"><?=$this->erp->formatQuantity($sum_qty)?></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <!-- <tr>
                            <td colspan="8" style="border-left: none; border-right: none; height: -1px;"></td>
                        </tr> -->
                        <tr>
                            <td colspan="5" rowspan="<?= $rspan; ?>"></td>
                            <td colspan="2"><?= lang("sub_total"); ?></td>
                            <td style="text-align: center; padding-right:10px;font-weight: bold;">
                                <?= "USD ". $this->erp->formatMoney($inv->total); ?> 
                            </td>
                        </tr>
                        <?php if ($inv->order_discount != 0) { ?>
                        <tr>
                            <td colspan="2"><?= lang("order_discount"); ?></td>
                            <td style="text-align:center; padding-right:10px;font-weight: bold; vertical-align: middle;">
                                <!-- <div class="row">
                                    <div class="col-xs-1">USD</div>
                                    <div class="col-xs-8" style-"text-align: right;"> -->
                                    <?='USD '. $this->erp->formatMoney($inv->order_discount); ?>
                                        
                                    <!-- </div> -->
                                <!-- </div> -->
                            </td>
                        </tr>
                        <?php } ?>
                        <?php if ($inv->shipping != 0) { ?>
                        <tr>
                            <td colspan="2"><?= lang("shipping"); ?></td>
                            <td style="text-align:center; padding-right:10px;font-weight: bold;">
                                <!-- <div class="row">
                                    <div class="col-xs-1">USD</div>
                                    <div class="col-xs-8" style-"text-align: right;"> -->
                                    <?='USD '. $this->erp->formatMoney($inv->shipping); ?>
                                        
                                    <!-- </div>
                                </div> -->
                            </td>
                        </tr>
                        <?php } ?>
                        <?php if ($Settings->tax2 && $inv->order_tax != 0) { ?>
                        <tr>
                            <td colspan="2"><?= lang("order_tax"); ?></td>
                            <td style="text-align:center; padding-right:10px;font-weight: bold;">
                                <!-- div class="row">
                                    <div class="col-xs-1">USD</div>
                                    <div class="col-xs-8" style-"text-align: right;"> -->
                                    <?='USD '. $this->erp->formatMoney($inv->order_tax); ?>
                                    <!-- </div> -->
                                <!-- </div> -->
                            </td>
                        </tr>
                        <?php } ?>
                        <tr>
                            <td colspan="2"><?= lang("grand_total"); ?></td>
                            <td style="text-align:center; padding-right:10px;font-weight: bold;">
                                <!-- <div class="row"> -->
                                    <!-- <div class="col-xs-1"></div> -->
                                    <!-- <div class="col-xs-8" style-"text-align: right;"></div> -->
                                    <?= 'USD '.$this->erp->formatMoney($inv->grand_total); ?>
                                <!-- </div> -->
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <!-- <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <table class="table table-bordered table-hover" border="1">
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
                                <td colspan="<?= $col; ?>"></td>
                                <td colspan="<?= $col; ?>" style="text-align:right; padding-right:10px;"><?= lang("sub_total"); ?>
                                </td>
                                <td style="text-align:right; padding-right:10px;font-weight: bold;"><?= $this->erp->formatMoney($inv->total); ?></td>
                            </tr>
                        <?php } ?>
        
                        <?php if ($inv->order_discount != 0) {
                            echo '<tr><td colspan="' . $col . '" style="text-align:right; padding-right:10px;;">' . lang("order_discount") . '</td><td style="text-align:right; padding-right:10px;font-weight: bold;">' . $this->erp->formatMoney($inv->order_discount) . '</td></tr>';
                        }
                        ?>
                        <?php if ($inv->shipping != 0) {
                            echo '<tr><td colspan="' . $col . '" style="text-align:right; padding-right:10px;;">' . lang("shipping") . '</td><td style="text-align:right; padding-right:10px;font-weight: bold;">' . $this->erp->formatMoney($inv->shipping) . '</td></tr>';
                        }
                        ?>
                        <?php if ($Settings->tax2 && $inv->order_tax != 0) {
                            echo '<tr><td colspan="' . $col . '" style="text-align:right; padding-right:10px;">' . lang("order_tax") . '</td><td style="text-align:right; padding-right:10px;font-weight: bold;">' . $this->erp->formatMoney($inv->order_tax) . '</td></tr>';
                        }
                        ?>
                        <?php if($inv->paid >0){ ?>
                        <tr>
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
                        </tr>
                        <?php } ?>
                    <tr>
                        <td colspan="<?=$col?>"></td>
                        <td colspan="<?=$col?>" style="text-align:right; width: 95px;"><?= lang("grand_total"); ?>
                        </td>
                        <td style="text-align:right; padding-right:10px; font-weight:bold; width: 102px"><?= $this->erp->formatMoney($inv->grand_total); ?></td>
                    </tr>
                        
               </table>
            </div>
        </div> -->
        <br>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="col-md-4 col-xs-5 text-left">
                    <p><?=lang('Prepare By')?>:</p>
                </div>
                <div class="col-md-4 col-xs-2">
                    
                </div>
                <div class="col-md-4 col-xs-5 text-left">
                    <p><?=lang('Approved By')?>:</p>
                </div>
            </div>
        </div>
        <br>
        <br>
        <br>
        <div class="row">
            <div class="col-md-8 col-md-offset-2 text-center">
                <p><?=$biller->cf4?></p>
                <u><p>www.totalglobalservice.com</p></u>
            </div>
        </div>
    </div>


<script type="text/javascript" src="<?= $assets ?>js/jquery-2.0.3.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
  $(document).on('click', '#b-add-quote' ,function(event){
    event.preventDefault();
    __removeItem('slitems');
    window.location.href = "<?= site_url('purchases_request/add'); ?>";
  });
  $(document).on('click', '#b-view-pr' ,function(event){
    event.preventDefault();
    __removeItem('slitems');
    window.location.href = "<?= site_url('purchases_request/index'); ?>";
  });
});

</script>
</body>
</html>
