<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $this->lang->line("quotes") . " " . $inv->reference_no; ?></title>
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
        @media print,screen{
            body {
                width: 100%;
            }
        }
    </style>
</head>

<body>
<div class="print_rec" id="wrap" style="width: 90%; margin: 0 auto;">
    <div class="row">
        <div class="col-lg-12">
            <?php if ($logo) { ?>
				<div class="col-xs-3 text-center" style="margin-bottom:20px;">
                    <img src="<?= base_url() . 'assets/uploads/logos/' . $biller->logo; ?>"
                         alt="<?= $biller->company != '-' ? $biller->company : $biller->name; ?>">
                </div>
                <div class="col-xs-6 text-center">
                    <h1><?php echo $biller->company;?></h1>
                    <?php 
                        if($biller->address){echo $biller->address."<br>";}
                        if($biller->phone){echo lang("tel") . " : ".$biller->phone;}
                        if($biller->email){echo "&nbsp &nbsp".lang("email")." : ". $biller->email;}
                    ?>           
                </div>
                <div class="col-xs-3">
                    
                </div>
            <?php } ?>
            <div class="clearfix"></div>
            <br>
            <div class="row padding10">                
				<div class="col-xs-12 text-center" style="text-align:center;margin-top:-20px">
					<h3><b><?= lang("quotes"); ?></b></h3>
				</div>                
            </div>
            <div class="row padding10">
                <div class="col-xs-5" style="float: left;font-size:14px">
                    <h4><b><?= lang("customer"); ?></b></h4>
                    <table>
                        <tr>
                            <td>
                                <p><?= lang("name");?></p>
                            </td>
                            <td>
                               <p>&nbsp;:&nbsp;</p>
                            </td>
                            <td>
                                <p><?= "<b>".$inv->customer."</b>"; ?></p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p><?= lang("address");?></p>
                            </td>
                            <td>
                                <p>&nbsp;:&nbsp;</p>
                            </td>
                            <td>
                                <p><?= "<b>".$customer->address."</b>"; ?></p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p><?= lang("phone");?></p>
                            </td>
                            <td>
                                <p>&nbsp;:&nbsp;</p>
                            </td>
                            <td>
                                <p><?= "<b>".$customer->phone."</b>"; ?></p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p><?= lang("email");?></p>
                            </td>
                            <td>
                                <p>&nbsp;:&nbsp;</p>
                            </td>
                            <td>
                                <p><?= "<b>".$customer->email."</b>"; ?></p>
                            </td>
                        </tr>
                    </table>
                    <div class="clearfix"></div>
                </div>
                <div class="col-xs-2">
                    
                </div>
                <div class="col-xs-5"  style="float: right;font-size:14px">
                    <h4><b><?= lang("reference");?></b></h4>
                    <table>
                        <tr>
                            <td>
                                <p><?= lang("quote_no"); ?></p>
                            </td>
                            <td>
                                <p>&nbsp;:&nbsp;</p>
                            </td>
                            <td>
                                <p><?= "<b>".$inv->reference_no."</b>";?></p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p><?= lang("quote_date"); ?></p>
                            </td>
                            <td>
                                <p>&nbsp;:&nbsp;</p>
                            </td>
                            <td>
                                <p><?= "<b>".$inv->date."</b>";?></p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p><?= lang("location"); ?></p>
                            </td>
                            <td>
                                <p>&nbsp;:&nbsp;</p>
                            </td>
                            <td>
                                <p><?= "<b>".$inv->location."</b>";?></p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p><?= lang("sales_person"); ?></p>
                            </td>
                            <td>
                                <p>&nbsp;:&nbsp;</p>
                            </td>
                            <td>
                                <p><?= "<b>".$inv->username."</b>";?></p>
                            </td>
                        </tr>
                    </table>
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
							<?php if($setting->show_code == 1 && $setting->separate_code == 1) { ?>
							<th><?= lang('product_code'); ?></th>
							<?php } ?>
							<th><?= lang("description"); ?> (<?= lang("code"); ?>)</th>
							<th><?= lang("units"); ?></th>
							<th><?= lang("quantity"); ?></th>
							<th><?= lang("unit_price"); ?></th>
							<?php
							if ($Settings->product_discount) {
								echo '<th>' . lang("discount") . '</th>';
							}
                            if ($Settings->tax1) {
                                echo '<th>' . lang("tax") . '</th>';
                            }
							?>
							<th><?= lang("amount"); ?>(<?= $default_currency->code; ?>)</th>
						</tr>
                    </thead>
                    <tbody style="font-size: 13px;">
                        <?php $r = 1 ;$total=0;?>
                        <?php foreach ($quote_items as $quote_item): ?>
                                <?php
                                    if($quote_item->option_id){
										$getvar = $this->sales_model->getAllProductVarain($quote_item->product_id);
										foreach($getvar as $varian){
											// $var = $this->sales_model->getVarain($varian->product_id);
											$Max_unitqty = $this->sales_model->getMaxqty($varian->product_id);
											$Min_unitqty = $this->sales_model->getMinqty($varian->product_id);
											$maxqty =  $Max_unitqty->maxqty;
											$minqty =  $quote_item->quantity;
											$Max_unit = $this->sales_model->getMaxunit($maxqty,$quote_item->product_id);
											$Min_unit = $this->sales_model->getMinunit($Min_unitqty->minqty,$quote_item->product_id);
											$maxunit = $Max_unit->name;
											$minunit = $Min_unit->name;  

										}
									}else{
										$maxunit = " ";
										$minunit = $quote_item->product_unit;                                
									}
                                ?>
							<tr>
								<td style="text-align:center; width:5%; vertical-align:middle;"><?= $r; ?></td>
								<?php if($setting->show_code == 1 && $setting->separate_code == 1) { ?>
								<td style="vertical-align:middle; width:15%;">
									<?= $quote_item->product_code ?>
								</td>
								<?php } ?>
								<td style="vertical-align:middle;width:30%" >
									<?= (isset($product_name_setting)?$product_name_setting:"") ?>
									<?= $quote_item->product_name ? '<br>' . $quote_item->product_name : ''; ?>
								</td>
								<td style="text-align:right; vertical-align:middle;width: 15px;">
                                    <?php
									
                                    if($quote_item->option_id){
                                        if($row->variant == $minunit){
                                            echo $minunit;
                                        }else{
                                            echo $maxunit;
                                        }
                                    }else{
                                        echo $minunit;
                                    }  ?>
                                </td>
								<td style="width: 10%; text-align:center; vertical-align:middle;"><?= $this->erp->formatQuantity($quote_item->quantity); ?></td>
								<td style=" text-align:left; vertical-align:middle;width: 10%;">
									<div class="col-xs-3 text-left">$</div>
									<div class="col-xs-6 text-left">
										<?=$this->erp->formatMoney($quote_item->unit_price); ?>
									</div>
								</td>
								<?php
								if ($Settings->product_discount) {
                                    $percentage = '%';
                                    $discount = $quote_item->discount;
                                    $dpos = strpos($discount, $percentage);
                                    echo '<td style="width: 100px; text-align:center; vertical-align:middle;">' .($dpos == true ? '<small>('.$discount.')</small>' : '').' '. $this->erp->formatMoney($quote_item->discount) . '</td>';
                                }
                                if ($Settings->tax1) {
                                    echo '<td style="width: 13%; text-align:right; vertical-align:middle;">' . ($quote_item->item_tax != 0 && $quote_item->taxs ? '<small>(' . $quote_item->taxs . ')</small> ' : '') . $this->erp->formatMoney($quote_item->item_tax) . '</td>';
                                }
								?>
								<td style="vertical-align:middle; text-align:right; width:20%;"><?= $quote_item->subtotal!=0?$this->erp->formatMoney($quote_item->subtotal):$free;
									$total += $quote_item->subtotal;
									?></td>
							</tr>
                            <?php $r++ ?>
						<?php endforeach;?>
                    </tbody>
                    <tfoot style="font-size: 13px;">
                    <?php
    					$discount_percentage = '';
    					if (strpos($inv->order_discount_id, '%') !== false) {
    						$discount_percentage = $inv->order_discount_id;
					}
                    ?>
                    <?php if ($inv->grand_total != $inv->total) { 
                        $row = 1;
                        
                        if($return_sale && $return_sale->surcharge != 0){
                            $row++;
                        }
                        if($inv->order_discount != 0){
                            $row++;
                        }
                        if($inv->shipping != 0){
                            $row++;
                        }
                        if($inv->shipping != 0){
                            $row++;
                        }
                        if($Settings->tax2 && $inv->order_tax != 0){
                            $row++;
                        }
					}
					$cols = 4;
					if($setting->show_code == 1 && $setting->separate_code == 1) { 
						$cols += 1;
					}
					if ($Settings->product_discount) {
						$cols += 1;
					}
					if ($Settings->tax1) {
						$cols += 1;
					}
                    ?>
					<tr>
						<td colspan="<?= $cols;?>" rowspan="<?= $row;?>">
								<b><p class="bold"><?= lang("note"); ?>:</p></b>
							<?= $this->erp->decode_html($inv->note); ?>
						</td>
						<td style="text-align:right; font-weight:bold;"><?= lang("total"); ?></td>
						<td style="text-align:right; font-weight:bold;"><?= $this->erp->formatMoney($total); ?></td>
					</tr>
                    <?php if ($inv->order_discount != 0) {
                        echo '<tr><td style="text-align:right;">' . lang("order_discount") . '</td><td style="text-align:right;">' . $this->erp->formatMoney($inv->order_discount) . '</td></tr>';
                    }
                    ?>
					<?php if ($inv->shipping != 0) {
                        echo '<tr><td style="text-align:right;">' . lang("shipping") . '</td><td style="text-align:right;">' . $this->erp->formatMoney($inv->shipping) . '</td></tr>';
                    }
                    ?>
                    <?php if ($Settings->tax2 && $inv->order_tax != 0) {
                        echo '<tr><td style="text-align:right;">' . $inv->tax_name .'</td><td style="text-align:right;">' . $this->erp->formatMoney($inv->order_tax) . '</td></tr>';
                    }
                    ?>

                    <?php if ($inv->order_discount != 0 || $inv->shipping != 0 || ($Settings->tax2 && $inv->order_tax != 0)) { ?>
						<tr>
							<td style="text-align:right; font-weight:bold;"><?= lang("total_amount"); ?></td>
							<td style="text-align:right; font-weight:bold;"><?= $this->erp->formatMoney($inv->grand_total); ?></td>
						</tr>
					<?php } ?>

                    </tfoot>
                </table>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <div class="col-lg-3 col-xs-5 text-center">
                        <p class="bold"><?= lang("customer"); ?></p>
                        <p>&nbsp;</p>
                        <p>&nbsp;</p>
                        <p style="border-bottom: 1px solid #666;">&nbsp;</p>
                        <p><?= lang("name"); ?> :  .........................................................</p>
                        <p><?= lang("date"); ?> :  ................/................../.....................</p>
                    </div>
                    <div class="col-lg-6 col-xs-2">
                        
                    </div>
                    <div class="col-lg-3 col-xs-5 text-center">
                        <p class="bold"><?= lang("authorized_by"); ?></p>
                        <p>&nbsp;</p>
                        <p>&nbsp;</p>
                        <p style="border-bottom: 1px solid #666;">&nbsp;</p>
                        <p><?= lang("name"); ?> :  .........................................................</p>
                        <p><?= lang("date"); ?> :  ................/................../.....................</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="mydiv" style="display:none;">

<div id="wrap" style="width: 90%; margin: 0 auto;">
    <div class="row">
        <div class="col-lg-12">
            <?php if ($logo) { ?>
                <div class="text-center" style="margin-bottom:20px; text-align: center;">
                    <!--<img src="<?= base_url() . 'assets/uploads/logos/' . $Settings->logo; ?>" alt="<?= $Settings->site_name; ?>">-->
                    <img src="<?= base_url() . 'assets/uploads/logos/' . $biller->logo; ?>"
                         alt="<?= $biller->company != '-' ? $biller->company : $biller->name; ?>">
                </div>
            <?php } ?>
            <div class="clearfix"></div>
            <div class="row padding10">
                <div class="col-xs-5" style="float: left;">
                    <h2 class=""><?= $biller->company != '-' ? $biller->company : $biller->name; ?></h2>
                    <?= $biller->company ? "" : "Attn: " . $biller->name ?>
                    <?php
                    echo $biller->address . "<br />" . $biller->city . " " . $biller->postal_code . " " . $biller->state . "<br />" . $biller->country;
                    echo "<p>";
                    if ($biller->cf1 != "-" && $biller->cf1 != "") {
                        echo "<br>" . lang("bcf1") . ": " . $biller->cf1;
                    }
                    if ($biller->cf2 != "-" && $biller->cf2 != "") {
                        echo "<br>" . lang("bcf2") . ": " . $biller->cf2;
                    }
                    if ($biller->cf3 != "-" && $biller->cf3 != "") {
                        echo "<br>" . lang("bcf3") . ": " . $biller->cf3;
                    }
                    if ($biller->cf4 != "-" && $biller->cf4 != "") {
                        echo "<br>" . lang("bcf4") . ": " . $biller->cf4;
                    }
                    if ($biller->cf5 != "-" && $biller->cf5 != "") {
                        echo "<br>" . lang("bcf5") . ": " . $biller->cf5;
                    }
                    if ($biller->cf6 != "-" && $biller->cf6 != "") {
                        echo "<br>" . lang("bcf6") . ": " . $biller->cf6;
                    }
                    echo "</p>";
                    echo lang("tel") . ": " . $biller->phone . "<br />" . lang("email") . ": " . $biller->email;
                    ?>
                    <div class="clearfix"></div>
                </div>
                <div class="col-xs-5"  style="float: right;">
                    <h2 class=""><?= $customer->company ? $customer->company : $customer->name; ?></h2>
                    <?= $customer->company ? "" : "Attn: " . $customer->name ?>
                    <?php
                    echo $customer->address . "<br />" . $customer->city . " " . $customer->postal_code . " " . $customer->state . "<br />" . $customer->country;
                    echo "<p>";
                    if ($customer->cf1 != "-" && $customer->cf1 != "") {
                        echo "<br>" . lang("ccf1") . ": " . $customer->cf1;
                    }
                    if ($customer->cf2 != "-" && $customer->cf2 != "") {
                        echo "<br>" . lang("ccf2") . ": " . $customer->cf2;
                    }
                    if ($customer->cf3 != "-" && $customer->cf3 != "") {
                        echo "<br>" . lang("ccf3") . ": " . $customer->cf3;
                    }
                    if ($customer->cf4 != "-" && $customer->cf4 != "") {
                        echo "<br>" . lang("ccf4") . ": " . $customer->cf4;
                    }
                    if ($customer->cf5 != "-" && $customer->cf5 != "") {
                        echo "<br>" . lang("ccf5") . ": " . $customer->cf5;
                    }
                    if ($customer->cf6 != "-" && $customer->cf6 != "") {
                        echo "<br>" . lang("ccf6") . ": " . $customer->cf6;
                    }
                    echo "</p>";
                    echo lang("tel") . ": " . $customer->phone . "<br />" . lang("email") . ": " . $customer->email;
                    ?>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="row padding10">
                <div class="col-xs-5" style="float: left;">
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
            <div class="-table-responsive">
                <table class="table table-bordered table-hover table-striped" style="width: 100%;">
                    <thead  style="font-size: 13px;">
                    <tr>
                        <th><?= lang("no"); ?></th>
                        <th><?= lang("description"); ?> (<?= lang("code"); ?>)</th>
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
                    foreach ($rows as $row):
                        ?>
                        <tr>
                            <td style="text-align:center; width:40px; vertical-align:middle;"><?= $r; ?></td>
                            <td style="vertical-align:middle;"><?= $row->product_name . " (" . $row->product_code . ")" . ($row->variant ? ' (' . $row->variant . ')' : ''); ?>
                                <?= $row->details ? '<br>' . $row->details : ''; ?>
                            </td>
                            <td style="width: 80px; text-align:center; vertical-align:middle;"><?= $this->erp->formatQuantity($row->quantity); ?></td>
                            <?php
                            if ($Settings->product_serial) {
                                echo '<td>' . $row->serial_no . '</td>';
                            }
                            ?>
                            <td style="text-align:right; width:90px;"><?= $this->erp->formatMoney($row->real_unit_price); ?></td>
                            <?php
                            if ($Settings->tax1) {
                                echo '<td style="width: 90px; text-align:right; vertical-align:middle;">' . ($row->taxs != 0 && $row->taxs ? '<small>(' . $row->taxs . ')</small> ' : '') . $this->erp->formatMoney($row->item_tax) . '</td>';
                            }
                            if ($Settings->product_discount) {
                                echo '<td style="width: 90px; text-align:right; vertical-align:middle;">' . ($row->discount != 0 ? '<small>(' . $row->discount . ')</small> ' : '') . $this->erp->formatMoney($row->item_discount) . '</td>';
                            }
                            ?>
                            <td style="vertical-align:middle; text-align:right; width:110px;"><?= $this->erp->formatMoney($row->subtotal);
                                ?></td>
                        </tr>
                        <?php
                        $r++;
                    endforeach;
                    ?>
                    </tbody>
                    <tfoot style="font-size: 13px;">
                    <?php
                    $col = 3;
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
                            <td style="text-align:right;"><?= $this->erp->formatMoney($inv->total + $inv->product_tax); ?></td>
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
					<?php if ($inv->shipping != 0) {
                        echo '<tr><td colspan="' . $col . '" style="text-align:right;">' . lang("shipping") . ' (' . $default_currency->code . ')</td><td style="text-align:right;">' . $this->erp->formatMoney($inv->shipping) . '</td></tr>';
                    }
                    ?>
                    <?php if ($Settings->tax2 && $inv->order_tax != 0) {
                        echo '<tr><td colspan="' . $col . '" style="text-align:right;">' . lang("order_tax") . ' (' . $default_currency->code . ')</td><td style="text-align:right;">' . $this->erp->formatMoney($inv->order_tax) . '</td></tr>';
                    }
                    ?>
                    <tr>
                        <td colspan="<?= $col; ?>"
                            style="text-align:right; font-weight:bold;"><?= lang("total_amount"); ?>
                            (<?= $default_currency->code; ?>)
                        </td>
                        <td style="text-align:right; font-weight:bold;"><?= $this->erp->formatMoney($inv->grand_total)."mouyleang"; ?></td>
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
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <?php if ($inv->note || $inv->note != "") { ?>
                        <div class="well well-sm">
                            <p class="bold"><?= lang("note"); ?>:</p>

                            <div><?= $this->erp->decode_html($inv->note); ?></div>
                        </div>
                    <?php } ?>
                </div>
                <div class="clearfix"></div>
                <div class="col-xs-4  pull-left" style="float: left;">
                    <p style="height: 80px;"><?= lang("seller"); ?>
                        : <?= $biller->company != '-' ? $biller->company : $biller->name; ?> </p>
                    <hr>
                    <p><?= lang("stamp_sign"); ?></p>
                </div>
                <div class="col-xs-4  pull-right" style="float: right;">
                    <p style="height: 80px;"><?= lang("customer"); ?>
                        : <?= $customer->company ? $customer->company : $customer->name; ?> </p>
                    <hr>
                    <p><?= lang("stamp_sign"); ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<br/><br/>
<div id="wrap" style="width: 90%; margin:0px auto;">
<div class="col-xs-10" style="margin-bottom:20px;">

	<button type="button" class="btn btn-primary btn-default no-print pull-left" onclick="window.print();">
		<i class="fa fa-print"></i> <?= lang('print'); ?>
	</button>&nbsp;&nbsp;

	<a href="<?= base_url() ?>quotes"><button class="btn btn-warning no-print" ><i class="fa fa-heart"></i>&nbsp; Back To Quote</button></a>

  <a href="#"><button id="b-add-quote" class="btn btn-success no-print" ><i class="fa fa-heart"></i>&nbsp; Back To Add Quote</button></a>

</div>
</div>
<div></div>
<!--<div style="margin-bottom:50px;">
	<div class="col-xs-4" id="hide" >
		<a href="<?= site_url('sales'); ?>"><button class="btn btn-warning " ><?= lang("Back to AddSale"); ?></button></a>&nbsp;&nbsp;&nbsp;
		<button class="btn btn-primary" id="print_receipt"><?= lang("Print"); ?>&nbsp;<i class="fa fa-print"></i></button>
	</div>
</div>-->
<script type="text/javascript" src="<?= $assets ?>js/jquery-2.0.3.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
  $(document).on('click', '#b-add-quote' ,function(event){
    event.preventDefault();
    __removeItem('slitems');
    window.location.href = "<?= site_url('quotes/add'); ?>";
  });
});

</script>
</body>
</html>
