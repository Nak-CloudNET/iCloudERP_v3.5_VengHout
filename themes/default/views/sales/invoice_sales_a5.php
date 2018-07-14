<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $this->lang->line("sales_invoice") . " " . $inv->reference_no; ?></title>
    <link href="<?php echo $assets ?>styles/theme.css" rel="stylesheet">
    <style type="text/css">
        html, body {
            height: 100%;
            background: #FFF;
            font-family: Khmer OS Content;
        }

        body:before, body:after {
            display: none !important;
        }
        .container {
            min-height: 27.7cm;
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
        .table, .table tr td, .table tr th {
            border: 1px solid #000 !important;
        }
        @media print{
			.print_rec{
				page-break-after: always;
			}
            .table {
                font-size: 11px !important;
            }
            .table thead {
                font-size: 11px !important;
            }
            .container {
                width: 95% !important;
                height: 27.7cm !important;
                margin: 0 auto !important;
            }
            hr {
                width: 150px !important;
            }
            
            #footer div {
                padding-left: 5px !important;
            }
            #customer {
                padding-left: 0 !important;
            }
            #customer-table {
                width: 190px !important;
            }
			.set_wid{
				width:100px !important;
			}
			.mar_lef{
				margin-left:-20px !important;
			}
        }
    </style>
</head>

<body>
<div class="container print_rec" id="wrap">
    <div class="row">
        <div class="col-lg-7 col-lg-offset-2 col-xs-12" style="padding:0px;">
            <?php if (isset($biller->logo)) { ?>
				<div class="col-xs-3 text-center" style="margin-bottom:20px;">
                    <img src="<?= base_url() . 'assets/uploads/logos/' . $biller->logo; ?>"
                         alt="<?= $biller->company != '-' ? $biller->company : $biller->name; ?>">
                </div>
			 <?php }else{ ?>
				<div class="col-xs-3 text-center" style="margin-bottom:20px;"></div>
			 <?php } ?>
                <div class="col-xs-5 text-center">
                    <h2 style="font-family: Khmer M1;font-size:20px"><?= lang("invoice_kh"); ?></h2>
                    <h2 style="margin-top: -10px !important; margin-bottom: 0px !importan;font-size:20px"><?= lang("sales_receipt"); ?></h2>
                </div>
                <div class="col-xs-3"></div>
           
            <div class="clearfix"></div>
            <br>
            <div class="row padding10" style="margin-top: -20px !important">
                <div class="col-xs-4" id="customer" style="float: left;font-size:14px; margin-top: -30px !important">
                    <table id="customer-table" style="font-size: 11px !important;width:190px;">
						<tr style="border: 1px solid #000">
                            <td style="padding: 5px;">
                                <p><b><?= lang("code_kh");?></b></p>
                            </td>
                            <td>
                                <p>: <?= $customer->code?></p>
                            </td>
                        </tr>
                        <tr style="border-left: 1px solid #000; border-right: 1px solid #000">
                            <td style="padding-left: 5px;">
                                <p><b><?= lang("name");?></b></p>
                            </td>
                            <td>
							<?php if($customer->name_kh){?>
                                <p>: <?= $customer->name_kh?></p>
							<?php } else {?>
								<p>: <?= $customer->name?></p>
							<?php }?>
                            </td>
							
                        </tr>
                        <tr style="border-left: 1px solid #000; border-right: 1px solid #000">
                            <td style="padding-left: 5px;">
                                <p><b><?= lang("address");?></b></p>
                            </td>
                            <td>
                                <p>: <?= $customer->address ?></p>
                            </td>
                        </tr>
                        <tr style="border-left: 1px solid #000; border-right: 1px solid #000; border-bottom: 1px solid #000">
                            <td style="padding-left: 5px;">
                                <p><b><?= lang("phone");?></b></p>
                            </td>
                            <td>
                                <p>: <?= $customer->phone ?></p>
                            </td>
                        </tr>
                    </table>
                    <div class="clearfix"></div>
                </div>
                <div class="col-xs-2">
                    
                </div>
                <div class="col-xs-5"  style="float: right;font-size:14px; margin-top: -30px !important; font-size: 12px !important">
                    <table>
                        <tr>
                            <td>
                                <p><b><?= lang("date_kh"); ?></b></p>
                            </td>
                            <td>
                                <p>&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;</p>
                            </td>
                            <td>
                                <p><?= $this->erp->hrld($inv->date);?></p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p><b><?= lang("number_kh"); ?></b></p>
                            </td>
                            <td>
                                <p>&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;</p>
                            </td>
                            <td>
                                <p><?= "<b>".$inv->reference_no."</b>";?></p>
                            </td>
                        </tr>
						<tr>
                            <td>
                                <p><b><?= lang("saleman_kh"); ?></b></p>
                            </td>
                            <td>
                                <p>&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;</p>
                            </td>
                            <td>
                                <p><?= "<b>".$inv->saleman."</b>";?></p>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="clearfix"></div>
			<div><br/></div>
            <div class="-table-responsive">
                <table class="table table-bordered table-striped" style="width: 100%;">
                    <thead  style="font-size: 11px;">
						<tr>
							<th><?= lang("ល.រ"); ?><br /><span style="font-size: 9px">No</span></th>
                            <th style="width: 80px !important"><?= lang("code_kh"); ?><br><span style="font-size: 9px"><?= lang("code");  ?></span></th> 
                            <th style="width: 350px !important"><?= lang("description_kh"); ?><br><span style="font-size: 9px"><?= lang("descript");  ?></span></th> 
                            <th><?= lang("unit_kh"); ?><br><span style="font-size: 9px"><?= lang("uom");  ?></span></th> 
                            <th><?= lang("number_kh2"); ?><br><span style="font-size: 9px"><?= lang("piece");  ?></span></th> 
                            <th><?= lang("length_piecs_kh"); ?><br><span style="font-size: 9px"><?= lang("length_piecs");  ?></span></th>
                            <th><?= lang("qty_kh"); ?><br><span style="font-size: 9px"><?= lang("qty");  ?></span></th>
							<?php if($Owner || $Admin || $GP['sales-price']){ ?>
								<th style="width: 100px !important"><?= lang("unit_price_kh"); ?><br><span style="font-size: 9px"><?= lang("unit_price");  ?></span></th>
							<?php }?>
                            <th style="width: 120px !important"><?= lang("total_kh"); ?><br><span style="font-size: 9px"><?= lang("total_capital");  ?></span></th> 
						</tr>
                    </thead>
                    <tbody style="font-size:11px">
                        <?php $r = 1; $row2 = 1;
                        $tax_summary = array();
						$grand_total = 0;
						
					
						//$this->erp->print_arrays($rows);
                        foreach ($rows as $row):
                                $str_unit = "";
                                $grand_total += ($row->quantity)*($row->unit_price);
                                if($row->option_id){
									
                                   $getvar = $this->sales_model->getAllProductVarain($row->product_id);
										
										 foreach($getvar as $varian){
											 if($varian->product_id){
												
												$var = $this->erp->sales_model->getVariantName($row->product_id,$row->option_id);
												$str_unit = $var->name;
												
											 }else{
												$str_unit = $row->uname;
											}
										}
                                }else{
                                    $str_unit = $row->uname;
								}

                            //$this->erp->print_arrays($row);
                           $variant = $this->sales_model->getProductVariantByOptionID($row->product_id);

                        ?>
                            <tr>
                                <td style="border-top:none !important;border-bottom:none !important; text-align:center; width:40px; vertical-align:middle;"><?= $r; ?></td>
                                <td style="border-top:none !important;border-bottom:none !important; vertical-align:middle;">
                                    <?= $row->product_code ?>
                                </td>
                                <td style="border-top:none !important;border-bottom:none !important; vertical-align:middle;">
                                    <?= $row->product_name ?>
                                </td>
                                <td style="border-top:none !important;border-bottom:none !important; width: 80px; text-align:center;"><?= $str_unit; ?></td>
                                <td class="text-center" style="border-top:none !important;border-bottom:none !important;">
									<?php
										if ($row->piece == 0) {
											echo number_format($row->quantity, 2);
										} else {
											echo number_format($row->piece, 2);
										}
									?>
                                </td>
                                <td class="text-center" style="border-top:none !important;border-bottom:none !important;">
									<?php
										if ($row->wpiece == 0) {
											echo '1.00';
										} else {
											echo number_format($row->wpiece, 2);
										}
									?>
                                </td>
                                 <td style="border-top:none !important;border-bottom:none !important; text-align:center;">
                                    <?= number_format($row->quantity, 2) ?>
                                </td>
								<?php if($Owner || $Admin || $GP['sales-price']){ ?>
									<td class="text-center" style="border-top:none !important;border-bottom:none !important;">
										<?= "$ ".$this->erp->formatMoney($row->unit_price) ?>
									</td>
								<?php }?>
                                <td class="text-right" style="border-top:none !important;border-bottom:none !important;">
                                    <?= "$ ".$this->erp->formatMoney(($row->quantity)*($row->unit_price)) ?>
                                </td>
                               
                            </tr>
                            <?php
                            $r++;
                            $row2++;
                        endforeach;
                                if ($row < 9) {
                                    $k = 9 - $row2;
                                    for ($j=1; $j <= $k; $j++) {
                                        echo
                                            '<tr>
                                                <td style="border-top:none !important;border-bottom:none !important; height:37px !important"></td>
                                                <td style="border-top:none !important;border-bottom:none !important; height:37px !important"></td>
                                                <td style="border-top:none !important;border-bottom:none !important; height:37px !important"></td>
                                                <td style="border-top:none !important;border-bottom:none !important; height:37px !important"></td>
                                                <td style="border-top:none !important;border-bottom:none !important; height:37px !important"></td>
                                                <td style="border-top:none !important;border-bottom:none !important; height:37px !important"></td>
                                                <td style="border-top:none !important;border-bottom:none !important; height:37px !important"></td>
                                                <td style="border-top:none !important;border-bottom:none !important; height:37px !important"></td>
                                                <td style="border-top:none !important;border-bottom:none !important; height:37px !important"></td>
                                            </tr>';
                                    }
                                    
                                }
                        ?>
                        <?php
                        $col = 3;
                        $rows = 5;
                        if($Owner || $Admin || $GP['purchases-cost']){
                            $col++;
                        }
                        if ($inv->sale_status == 'partial') {
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

                        if ($inv->order_discount != 0) {
                            $rows++;
                        }
                        ?>
                        <tr>
							<?php if($Owner || $Admin || $GP['sales-price']){?>
								<td colspan="7" rowspan="<?= $rows; ?>" style="border-left: 1px solid #FFF !important;​border-bottom: 1px solid #FFF !important">
									<?php
									if ($inv->invoice_footer || $inv->invoice_footer != "") { ?>
										<div style="font-size:11px;">
											<p><b><?= lang("note_kh"); ?>:</b></p>
											<div><?= $this->erp->decode_html(nl2br($inv->invoice_footer)); ?></div>
										</div>
									<?php
									}
									?>
								</td>
							<?php }else{?>
								<td colspan="6" rowspan="<?= $rows; ?>" style="border-left: 1px solid #FFF !important; border-bottom: 1px solid #FFF !important">
									<?php
									if ($inv->invoice_footer || $inv->invoice_footer != "") { ?>
										<div style="font-size:11px;">
											<p><b><?= lang("note_kh"); ?>:</b></p>
											<div><?= $this->erp->decode_html($inv->invoice_footer); ?></div>
										</div>
									<?php
									}
									?>
								</td>
							<?php } ?>
                            <td style="text-align:right; font-weight:bold;"><?= lang("total_kh"); ?>
                            </td>
                            <td style="text-align:right; padding-right:10px; font-weight:bold;"><?= "$ ".$this->erp->formatMoney($grand_total); ?></td>
                        </tr>
						<?php if($inv->order_discount != 0){ ?>
							<tr>
								<td style="text-align:right; font-weight:bold;"><?= lang("discount_kh"); ?>
								</td>
								<td style="text-align:right; padding-right:10px; font-weight:bold;"><?= "$ ".$this->erp->formatMoney($inv->order_discount); ?></td>
							</tr>
						<?php } ?>
                        <tr>
                            <td style="text-align:right; font-weight:bold;"><?= lang("totalpaid_kh"); ?>
                            </td>
                            <td style="text-align:right; padding-right:10px; font-weight:bold;"><?= "$ ".$this->erp->formatMoney($grand_total-$inv->total_discount); ?></td>
                        </tr>
                        <tr>
                            <td style="text-align:right; font-weight:bold;"><?= lang("deposit_kh"); ?>
                            </td>
                            <td style="text-align:right; padding-right:10px; font-weight:bold;"><?= "$ ".$this->erp->formatMoney($inv->paid); ?></td>
                        </tr>
                        <tr>
                            <td style="text-align:right; font-weight:bold;"><?= lang("balance_kh"); ?>
                            </td>
                            <td style="text-align:right; padding-right:10px; font-weight:bold;"><?= "$ ".$this->erp->formatMoney(($grand_total-$inv->total_discount)-$inv->paid); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
	<div class="clearfix"></div>
    <div id="footer" class="row" style="margin-top: 50px !important; margin: 0 !important">
		<div class="col-lg-7 col-lg-offset-2 col-xs-12" style="font-size:11px;">
			<div class="col-sm-3 col-xs-3">
				<center>
				<p class="mar_lef" style="margin-left: -30px !important;"><?= lang('receiver_kh'); ?></p>
				<hr class="set_wid" style="border:1px solid #000; width:100px; float: left;" />
				</center>
			</div>
			<div class="col-sm-3 col-xs-3">
				<center>
				<p class="mar_lef" style="margin-left: -30px !important;"><?= lang('driver_kh'); ?></p>
				<hr class="set_wid" style="border:1px solid #000; width:100px; float: left;" />
				</center>
			</div>
			<div class="col-sm-3 col-xs-3">
				<center>
				<p class="mar_lef" style="margin-left: -30px !important;"><?= lang('prepared_by_kh'); ?></p>
				<hr class="set_wid" style="border:1px solid #000; width:100px; float: left;" />
				</center>
			</div>
			<div class="col-sm-3 col-xs-3">
				<center>
				<p class="mar_lef" style="margin-left: -30px !important;"><?= lang('seller_kh'); ?></p>
				<hr class="set_wid" style="border:1px solid #000; width:100px; float: left;" />
				</center>
			</div>
		</div>
    </div>
	<div class="clearfix"></div>
</div>

<div style="margin-top: -235px;" class="no-print">
	<div class="col-xs-4" id="hide" >
		<a href="<?= site_url('pos/sales'); ?>"><button class="btn btn-warning " ><?= lang("Back to List POS Sale"); ?></button></a>&nbsp;&nbsp;&nbsp;
		<button class="btn btn-primary" id="print_receipt" onclick="window.print();"><?= lang("Print"); ?>&nbsp;<i class="fa fa-print"></i></button>
	</div>
</div>
</body>
</html>
