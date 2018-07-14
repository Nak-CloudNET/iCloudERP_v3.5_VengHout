<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $this->lang->line("purchase") . " " . $inv->reference_no; ?></title>
    <link href="<?php echo $assets ?>styles/theme.css" rel="stylesheet">
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
		hr{
			border-color: #333;
			width:100px;
			margin-top: 70px;
		}
    </style>
</head>

<body>
<div class="print_rec" id="wrap" style="width: 90%; margin: 0 auto;">
    <div class="row">
        <div class="col-lg-12">
            <?php if ($logo) { ?>
                <div class="well well-sm" style="height:70px; margin-bottom:5px;">
					<div class="row bold" style="font-size:12px">
						<div class="col-xs-6">
						<font style="font-famlily:'Adobe Gothic Std B'; font-size:28px; font-weight:bold;">
							<?= $biller->company != '-' ? $biller->company : $biller->name; ?>
						</font>
						</div>
						<div class="col-xs-6 text-right">
							<font style="font-famlily:'Adobe Gothic Std B'; font-size:20px; font-weight:bold;">
								<?= lang("invoice"); ?>
							</font>
						</div>
					</div>
				</div>
				<div class="well well-sm" style="height:70px;">
					<div class="row bold" style="font-size:12px">
						<div class="col-xs-4">
							<p class="bold">
								<?= $biller->address; ?><br>
								<?= $biller->postal_code .", ". $biller->state ?><br>
								<?= $biller->city .", ". $biller->country ?>
							</p>
						</div>
						<div class="col-xs-4 text-right">
							
						</div>
						<div class="col-xs-4 text-right">
							<p class="bold">
								<?= lang("phone"); ?>: <?= lang($biller->phone); ?>
							</p>
							<p class="bold">
								<?= lang("email"); ?>: <?= lang($biller->email); ?>
							</p>
						</div>
					</div>
				</div>
            <?php } ?>
            <div class="clearfix"></div>
            <div class="row padding10">
                <div class="col-xs-5" style="float: left;font-size:14px">
					<?= lang("bill_to"); ?> : &nbsp;&nbsp;&nbsp; <?= $customer->name ? $customer->name : $customer->company; ?><br/>
					<?= lang("suspend") . " : " . $inv->suspend_note; ?><br/>
                    <?= lang("phone"); ?> : &nbsp;&nbsp;&nbsp; <?= $customer->phone; ?><br/>
					<?= lang("email"); ?> : &nbsp;&nbsp;&nbsp; <?= $customer->email; ?><br/>
					<div class="clearfix"></div>
                </div>
                <div class="col-xs-5 text-right"  style="float: right;font-size:14px">
					<?= lang('invoice_no');?>: <?= $inv->reference_no; ?><br/>
					<?= lang("inv_date"); ?> : <?= $this->erp->hrld($inv->date); ?><br/>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="row padding10" style="display:none">
                <div class="col-xs-6" style="float: left;">
                    <span class="bold"><?= $Settings->site_name; ?></span><br>
                    <?= $warehouse->name ?>

                    <?php
                    echo $warehouse->address . "<br>";
                    echo ($warehouse->phone ? lang("tel") . ": " . $warehouse->phone . "<br>" : '') . ($warehouse->email ? lang("email") . ": " . $warehouse->email : '');
                    ?>
                    <div class="clearfix"></div>
                </div>
                <div class="col-xs-5" style="float: right;">
                    <div class="bold">
                        <?= lang("date"); ?>: <?= $this->erp->hrld($inv->date); ?><br>
                        <?= lang("ref"); ?>: <?= $inv->reference_no; ?>
                        <div class="clearfix"></div>
                        <?php $this->erp->qrcode('link', urlencode(site_url('sales/view/' . $inv->id)), 1); ?>
                        <img src="<?= base_url() ?>assets/uploads/qrcode<?= $this->session->userdata('user_id') ?>.png"
                             alt="<?= $inv->reference_no ?>" class="pull-right"/>
                        <?php $br = $this->erp->save_barcode($inv->reference_no, 'code39', 50, false); ?>
                        <img src="<?= base_url() ?>assets/uploads/barcode<?= $this->session->userdata('user_id') ?>.png"
                             alt="<?= $inv->reference_no ?>" class="pull-left"/>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>

            <div class="clearfix"></div>
			<div><br/></div>
            <div class="-table-responsive">
                <table class="table table-bordered table-hover table-striped" style="width: 100%;">
                    <thead  style="font-size: 13px;">
                    <tr>
                        <th><?= lang("no"); ?></th>
                        <th><?= lang("description"); ?> (<?= lang("code"); ?>)</th>
						<th><?= lang("unit"); ?></th>
                        <th><?= lang("quantity"); ?></th>
                        <?php
                        if ($Settings->product_serial) {
                            echo '<th style="text-align:center; vertical-align:middle;">' . lang("serial_no") . '</th>';
                        }
                        ?>
                        <th><?= lang("unit_price"); ?></th>
                        <?php
                        if ($Settings->tax1) {
                            echo '<th>' . lang("tax") . '</th>';
                        }
                        if ($Settings->product_discount) {
                            echo '<th>' . lang("discount") . '</th>';
                        }
                        ?>
                        <th><?= lang("subtotal"); ?></th>
                    </tr>
                    </thead>
                    <tbody style="font-size: 13px;">
                    <?php $r = 1;
                    $total = 0;
                    foreach ($rows as $row):
					$free = lang('free');
					$product_unit = '';
					if($row->variant){
						$product_unit = $row->variant;
					}else{
						$product_unit = $row->unit;
					}
					
					$product_name_setting;
					if($pos->show_product_code == 0) {
						$product_name_setting = $row->product_name . ($row->variant ? ' (' . $row->variant . ')' : '');
					}else{
						$product_name_setting = $row->product_name . " (" . $row->product_code . ")" . ($row->variant ? ' (' . $row->variant . ')' : '');
					}
                        ?>
                        <tr>
                            <td style="text-align:center; width:5%; vertical-align:middle;"><?= $r; ?></td>
                            <td style="vertical-align:middle;width:35%" ><?= $product_name_setting ?>
                                <?= $row->details ? '<br>' . $row->details : ''; ?>
                            </td>
							<td style="width: 10%; text-align:center; vertical-align:middle;"><?php echo $product_unit ?></td>
                            <td style="width: 10%; text-align:center; vertical-align:middle;"><?= $this->erp->formatQuantity($row->quantity); ?></td>
                            <?php
                            if ($Settings->product_serial) {
                                echo '<td>' . $row->serial_no . '</td>';
                            }
                            ?>
                            <td style="text-align:center; width:15%;vertical-align:middle;"><?= $row->subtotal!=0?$this->erp->formatMoney($row->unit_price):$free; ?></td>
                            <?php
                            if ($Settings->tax1) {
                                echo '<td style="width: 8%; text-align:right; vertical-align:middle;">' . ($row->item_tax != 0 && $row->tax_code ? '<small>(' . $row->tax_code . ')</small> ' : '') . $this->erp->formatMoney($row->item_tax) . '</td>';
                            }
                            if ($Settings->product_discount) {
                                echo '<td style="width: 7%; text-align:right; vertical-align:middle;">' . ($row->discount != 0 ? '<small>(' . $row->discount . ')</small> ' : '') . $this->erp->formatMoney($row->item_discount) . '</td>';
                            }
                            ?>
                            <td style="vertical-align:middle; text-align:right; width:20%;"><?= $row->subtotal!=0?$this->erp->formatMoney($row->subtotal):$free; 
                                $total += $row->subtotal;
                                ?></td>
                        </tr>
                        <?php
                        $r++;
                    endforeach;
					if(count($rows) < 17) {
						for($i=$r;$i<=16;$i++){
					?>
							<tr>
								<td style="text-align:center; width:5%; vertical-align:middle;">&nbsp;</td>
								<td style="vertical-align:middle;width:35%" ></td>
								<td style="width: 10%; text-align:center; vertical-align:middle;"></td>
								<td style="width: 10%; text-align:center; vertical-align:middle;"></td>
								<td style="text-align:center; width:15%;vertical-align:middle;"></td>
								<td style="width: 8%; text-align:right; vertical-align:middle;"></td>
								<td style="width: 7%; text-align:right; vertical-align:middle;"></td>
								<td style="vertical-align:middle; text-align:right; width:20%;"></td>
							</tr>
					<?php
						}
					}
                    ?>
                    </tbody>
                    <tfoot style="font-size: 13px;">
                    <?php
                    $col = 5;
                    if ($Settings->product_serial) {
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
                            <td colspan="<?= $tcol; ?>" style="text-align:right;"><?= lang("total"); ?>
                                (<?= $default_currency->code; ?>)
                            </td>
                            <?php
                            if ($Settings->tax1) {
                                echo '<td style="text-align:right;">' . $this->erp->formatMoney($inv->product_tax) . '</td>';
                            }
                            if ($Settings->product_discount) {
                                echo '<td style="text-align:right;">' . $this->erp->formatMoney($inv->product_discount) . '</td>';
                            }
                            ?>
                            <!-- <td style="text-align:right;"><?= $this->erp->formatMoney($inv->total + $inv->product_tax); ?></td> -->
                            <td style="text-align:right;"><?= $this->erp->formatMoney($total); ?></td>
                        </tr>
                    <?php } ?>
                    <?php if ($return_sale && $return_sale->surcharge != 0) {
                        echo '<tr><td colspan="' . $col . '" style="text-align:right;">' . lang("surcharge") . ' (' . $default_currency->code . ')</td><td style="text-align:right;">' . $this->erp->formatMoney($return_sale->surcharge) . '</td></tr>';
                    }
                    ?>
                    <?php if ($inv->order_discount != 0) {
                        echo '<tr><td colspan="' . $col . '" style="text-align:right;">' . lang("order_discount") . ' (' . $default_currency->code . ')</td><td style="text-align:right;">' . $this->erp->formatMoney($inv->order_discount) . '</td></tr>';
                    }
                    ?>
                    <?php if ($Settings->tax2 && $inv->order_tax != 0) {
                        echo '<tr><td colspan="' . $col . '" style="text-align:right;">' . lang("order_tax") . ' (' . $default_currency->code . ')</td><td style="text-align:right;">' . $this->erp->formatMoney($inv->order_tax) . '</td></tr>';
                    }
                    ?>
                    <?php if ($inv->shipping != 0) {
                        echo '<tr><td colspan="' . $col . '" style="text-align:right;">' . lang("shipping") . ' (' . $default_currency->code . ')</td><td style="text-align:right;">' . $this->erp->formatMoney($inv->shipping) . '</td></tr>';
                    }
                    ?>
                    <tr>
                        <td colspan="<?= $col; ?>"
                            style="text-align:right; font-weight:bold;"><?= lang("total_amount"); ?>
                            (<?= $default_currency->code; ?>)
                        </td>
                        <td style="text-align:right; font-weight:bold;"><?= $this->erp->formatMoney($inv->grand_total); ?></td>
                    </tr>

                    <tr>
                        <td colspan="<?= $col; ?>" style="text-align:right; font-weight:bold;"><?= lang("paid"); ?>
                            (<?= $default_currency->code; ?>)
                        </td>
                        <td style="text-align:right; font-weight:bold;"><?= $this->erp->formatMoney($inv->paid); ?></td>
                    </tr>
                    <tr>
                        <td colspan="<?= $col; ?>" style="text-align:right; font-weight:bold;"><?= lang("balance"); ?>
                            (<?= $default_currency->code; ?>)
                        </td>
                        <td style="text-align:right; font-weight:bold;"><?= $this->erp->formatMoney($inv->grand_total - $inv->paid); ?></td>
                    </tr>

                    </tfoot>
                </table>
				<div class="row" style="padding-left:20px;">
					<p style="font-size:12px; font-weight:bold; font-famlily:'Arial';">
						All invoice must be settled by the 5th of every month to avoid suspending <br/>
						If payment made by check, please refer to Mrs. Am Sothy
					</p>
				</div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="http://jqueryjs.googlecode.com/files/jquery-1.3.1.min.js" > </script>
</script>
</body>
</html>