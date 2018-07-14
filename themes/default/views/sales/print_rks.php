<?php
	//$this->erp->print_arrays($rows);

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $this->lang->line("invoice") . " " . $inv->reference_no; ?></title>
    <link href="<?php echo $assets ?>styles/theme.css" rel="stylesheet">
    <style type="text/css">
		body {
			font-family: Khmer OS !important;
		}
		
		@media print
	   {
		 .no-print {display:none}
	   }
        html, body {
            height: 100%;
            background: #FFF;
			font-size: 18px !important;
			font-weight: bold !important;
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
		
		.table thead > tr > th, .table tbody > tr > th, .table tfoot > tr > th, .table thead > tr > td, .table tbody > tr > td, .table tfoot > tr > td { border: 3px solid #000 !important; font-size: 18px !important;}
    </style>
</head>

<body>
<div class="print_rec" id="wrap" style="width: 95%; margin: 15px auto;">
    <div class="row">
        <div class="col-lg-12">
			<div class="row">
				<div class="col-sm-12 col-md-12 col-xs-12">
					<h3 style="text-align:center;"><strong>វិក័យប័ត្រ</strong></h3>
				</div>
			</div>
			
			<div style="float: right; margin-right: 16px; margin-top: -20px;">Project Name :<?=$customer->code?$customer->code:$customer->name;?></div>
			
			<div class="row">
				<div class="col-sm-5 col-md-5 col-xs-5">
					<p>ថ្ងៃទី​ / Date: <?= $this->erp->hrsd($inv->date); ?></p>
				</div>
				<div class="col-sm-2 col-md-2 col-xs-2">
					<h3 style="text-align:center; margin-top: -10px;"><strong>Invoice</strong></h3>
				</div>
				<div class="col-sm-5 col-md-5 col-xs-5">
					<p style="float: right" class="bold"> លេខទី / No : <?= $inv->reference_no; ?></p>
				</div>
			</div>				
            <div class="clearfix"></div>
			
			<div><br/></div>
            <div class="-table-responsive" style="margin-top: -20px;">
                <table class="table table-bordered" style="width: 100%;">
                    <thead  style="font-size: 13px;">
						<tr>
							<th>ល.រ<br/>N<sup>o</sup></th>
							<th>លេខកូដទំនិញ<br/>Item Code</th>
							<th>ឈ្មោះទំនិញ<br/>Product Name</th>
							<th>ចំនួន<br/>QTY</th>
							<th>ខ្នាត<br/>Unit</th>
							<th>តម្លៃ<br/>Unit Price</th>
							<th>តម្លៃសរុប<br/>Total Price</th>
						</tr>
                    </thead>
                    <tbody style="font-size: 16px !important;">
						<?php $r = 1;
						$total = 0;
						$i=1;
						foreach ($rows as $row):
						$free = lang('free');
						$product_unit = '';
						if($row->variant){
							$product_unit = $row->variant;
						}else{
							$product_unit = $row->uname;
						}
						
						
						$product_name_setting;
						if($setting->show_code == 0) {
							$product_name_setting = ($row->promotion == 1 ? '<i class="fa fa-check-circle"></i> ' : '') . $row->product_name . ($row->variant ? ' (' . $row->variant . ')' : '');
						}else{
							if($setting->separate_code == 0) {
								$product_name_setting = ($row->promotion == 1 ? '<i class="fa fa-check-circle"></i> ' : '') . $row->product_name . ($row->variant ? ' (' . $row->variant . ')' : '');
							}else {
								$product_name_setting = ($row->promotion == 1 ? '<i class="fa fa-check-circle"></i> ' : '') . $row->product_name . " (" . $row->product_code . ")" . ($row->variant ? ' (' . $row->variant . ')' : '');
							}
						}
							?>
						
							<tr>
								<td style="text-align:center; width:5%; vertical-align:middle;border-top:none !important;border-bottom:none !important;"><?= $r; ?></td>
								<td style="border-top:none !important;border-bottom:none !important; width:15%" >
									<?= $row->product_code; ?>
									
								</td>
								<td style="border-top:none !important;border-bottom:none !important;" >
									<?//= $product_name_setting ?>
									<?= $row->product_name; ?>
									<?= $row->product_noted ? '<br>' . $row->product_noted : ''; ?>
								</td>
								<td style="width: 8%; text-align:center; vertical-align:middle;border-top:none !important;border-bottom:none !important;"><?= $this->erp->formatQuantity($row->quantity) ?></td>
								<td style="width: 8%; text-align:center; vertical-align:middle;border-top:none !important;border-bottom:none !important;"><?= $product_unit ?></td>
								<td style="text-align:center; width:8% !important;vertical-align:middle;border-top:none !important;border-bottom:none !important;"><?= $row->subtotal!=0?$this->erp->formatMoney($row->unit_price):$free; ?></td>
								<td style="vertical-align:middle; text-align:center; width:10% !important;border-top:none !important;border-bottom:none !important;"><?= $row->subtotal!=0?$this->erp->formatMoney($row->subtotal):$free;
									$total += $row->subtotal;
									?></td>
							</tr>
							<?php
							$r++;
							$i++;
							$discount += $row->item_discount;
						endforeach;
						?>
							<?php
								if($i<12){
									$k=12	-$i;
									for($j=1;$j<=$k;$j++){
										echo  '<tr>
													<td style="border-top:none !important;border-bottom:none !important;height:35px !important;"></td>
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
                    <tfoot>
						<tr>
							<td colspan="5" rowspan="8" style="border-left: solid #FFF !important; border-bottom: solid #FFF !important">
								<span style="font-size:16px !important;">
								.មុននឹងចុះហត្ថលេខាសូមពិនិត្យអោយបានច្បាស់ ។<br/>
									&nbsp;&nbsp;&nbsp;&nbsp;Receive the above goods in good condition and order:<br/>
									&nbsp;&nbsp;&nbsp;&nbsp;Goods sold are not returnable or exchangeable.<br/>
								.តម្លៃនេះមិនរួមបញ្ចូលពន្ធលើតម្លៃបន្ថែម (VAT) 10% ទេ ។<br/>
									&nbsp;&nbsp;&nbsp;&nbsp;This price is not include VAT 10%<br/>
								</span>
							</td>
						</tr>
						<?php
						$col = 1;

							$tcol = $col;
						
						$discount_percentage = '';
						if (strpos($inv->order_discount_id, '%') !== false) {
							$discount_percentage = $inv->order_discount_id;
						}
						?>
						
						<?php if ($return_sale && $return_sale->surcharge != 0) {
							echo '<tr><td colspan="' . $col . '" style="text-align:right;">' . lang("surcharge") . ' (' . $default_currency->code . ')</td><td style="text-align:right;">' . $this->erp->formatMoney($return_sale->surcharge) . '</td></tr>';
						}
						?>
						
						<tr>
							<td colspan="<?= $col; ?>" style="text-align:left;font-size:14px;" class="kh-moul">សរុប</td>
							<td style="font-weight:bold;"><?= $this->erp->formatMoney($inv->total); ?></td>
						</tr>
						<?php
							if($inv->order_discount_id){
								echo '<tr><td colspan="' . $col . '" style="text-align:left;font-size:14px;" class="kh-moul">' . lang("ចុះថ្លៃ").' ('.$inv->order_discount_id.') </td><td style="font-weight:bold;"><span class="pull-left"></span>' . $this->erp->formatMoney($inv->order_discount) . '</td></tr>';
							}else{
								echo '<tr><td colspan="' . $col . '" style="text-align:left;font-size:14px;" class="kh-moul">' . lang("ចុះថ្លៃ").$inv->order_discount_id.' </td><td style="font-weight:bold;"><span class="pull-left"></span>' . $this->erp->formatMoney($inv->order_discount) . '</td></tr>';
							}	
							
						?>

						<?php

							$sds = ($inv->total - $inv->order_discount) + $inv->shipping;
							$sst = ($sds * $row->tax) / 100;
							$grand_total = $sds + $sst;
						?>	

						<tr>
							<td colspan="<?= $col; ?>" style="text-align:left;font-size:14px;" class="kh-moul"><?= lang("ថ្លៃដឹកជញ្ជូន"); ?></td>
							<td style="font-weight:bold;"><?= $this->erp->formatMoney($inv->shipping); ?></td>
						</tr>
						<tr>
							<td colspan="<?= $col; ?>" style="text-align:left;font-size:14px;" class="kh-moul"><?= lang("ទឹកប្រាក់ត្រូវបង់"); ?></td>
							<td style="font-weight:bold;"><?= $this->erp->formatMoney($grand_total); ?></td>
						</tr>

						 <tr>
							<td colspan="<?= $col; ?>" style="text-align:left;font-size:14px;" class="kh-moul"><?= lang("បានបង់"); ?></td>
							<td style="font-weight:bold;"><?= $this->erp->formatMoney($inv->paid); ?></td>
						</tr>

						<tr>
							<td colspan="<?= $col; ?>"  style="text-align:left;font-size:14px;" class="kh-moul"><?= lang("នៅខ្វះ"); ?></td>
							<td style="font-weight:bold;"><?= $this->erp->formatMoney($grand_total - $inv->paid); ?></td>
						</tr>
                    </tfoot>
                </table>
				
				<div style="width:100%; margin-top: -20px;">
					<table class="col-sm-8 col-xs-8"  style="margin-bottom:10px; border:none !important">
						<tr style="border:none !important">
							<td style="width: 80px;" class="kh-moul">អតិថិជន</td>
							<td></td>
							<td  style="width: 80px;padding-left:20px;" class="kh-moul">អ្នកលក់</td>
							<td></td>
						</tr>
						<tr style="height:40px; border:none !important">
							<td style="width: 80px;">Customer</td>
							<td></td>
							<td  style="width: 80px;padding-left:20px;">Seller</td>
							<td></td>
						</tr>
						<tr style="border:none !important">
							<td></td>
							<td style="border-top:3px solid black;text-align:center;"><?= $customer->name .'<br/>'. $customer->company ?></td>
							<td></td>
							<td  style="border-top:3px solid black;text-align:center;"><?= ($user && $user->username != '') ? $user->username : '' ?></td>
						</tr>
						<tr style="border:none !important">
							<td colspan="2">
								Project Manager
							</td>
							<td colspan="2"> 
								: <?=$project->first_name." ".$project->last_name;?>
							</td>
						</tr>
						<tr style="border:none !important">
							<td colspan="2">
								Bill to
							</td>
							<td colspan="2"> 
								: <?=$inv->bill_to;?>
							</td>
						</tr>
						<tr style="border:none !important">
							<td colspan="2">
								Sale Order Reference 
							</td>
							<td colspan="2">
								: <?=$inv->so_no;?>
							</td>
						</tr>
					</table>
					
							<div class="clearfix"></div>
				<div style="border-top:3px solid black; font-size:16px !important;">
					<p style="margin-top:10px;">១-ក្រុមហ៊ុនយើងខ្ញុំមិនទទួលខុសត្រូវការខូចខាតក្រោយពេលប្រគល់ទំនិញ ។ ផុតកំណត់ <?=  date('d-m-Y', strtotime("+7 day", strtotime($inv->date))) ;?> ។</p>
					<p style="margin-bottom:0;">២-ទូរទាត់ប្រាក់ហួសកំណត់ ក្រុមហ៊ុនមានសិទ្ធ ដំណើរការតាមការគួរ ។ (ទំនិញទិញហើយមិនអាចដូរបាន ។)</p>
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
								<?= $row->product_noted ? '<br>' . $row->product_noted : 'ffff'; ?>
								
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
                                echo '<td style="width: 90px; text-align:right; vertical-align:middle;">' . ($row->item_tax != 0 && $row->tax_code ? '<small>(' . $row->tax_code . ')</small> ' : '') . $this->erp->formatMoney($row->item_tax) . '</td>';
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
                    $col = 4;
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
<div id="wrap" style="width: 95%; margin:0px auto;">
<div class="col-xs-10 no-print" style="margin-bottom:20px;">

	<button type="button" class="btn btn-primary btn-default btn-xs no-print pull-left" onclick="window.print();">
		<i class="fa fa-print"></i> <?= lang('print'); ?>
	</button>
	<a href="<?=base_url()?>sales"><button type="button" class="btn btn-warning btn-xs pull-left" style="margin-left: 5px;">
		<i class="fa fa-chevron-left" aria-hidden="true"></i> <?= lang('back_to_list_sale'); ?>
	</button></a>
	
	<!--<a href="<?=base_url()?>sales/tax_invoice/<?=$sid?>" target="_blank"><button class="btn btn-primary no-print" ><i class="fa fa-print"></i>&nbsp;<?= lang("print_tax_invoice"); ?></button></a>&nbsp;&nbsp;
	<a href="<?=base_url()?>sales/invoice/<?=$sid?>" target="_blank"><button class="btn btn-primary no-print" ><i class="fa fa-print"></i>&nbsp;<?= lang("invoice"); ?></button></a>&nbsp;&nbsp;
	<a href="<?=base_url()?>sales/cabon_print/<?=$sid?>" target="_blank"><button class="btn btn-primary no-print" ><i class="fa fa-print"></i>&nbsp;<?= lang("print_cabon"); ?></button></a>&nbsp;&nbsp;
	<a href="<?=base_url()?>sales/print_jewwel/<?=$sid?>" target="_blank"><button class="btn btn-primary no-print" ><i class="fa fa-print"></i>&nbsp;<?= lang("print_jewwel_apartment_invoice"); ?></button></a>&nbsp;&nbsp;
	<a href="<?=base_url()?>sales/print_hch/<?=$sid?>" target="_blank"><button class="btn btn-primary no-print" ><i class="fa fa-print"></i>&nbsp;<?= lang("print_hch_invoice"); ?></button></a>&nbsp;&nbsp;-->
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
  $(document).on('click', '#b-add-sale' ,function(event){
    event.preventDefault();
    __removeItem('slitems');
    window.location.href = "<?= site_url('sales/add'); ?>";
  });
});

</script>
</body>
</html>
