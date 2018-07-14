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
		img{
			width:150px;height:100px;
			margin-top:20px;
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
                height:auto !important;
                margin: 0 auto !important;
            }
			div.no{
				font-size:10px !important; 
			}
			
            hr {
                width: 150px !important;
            }
            
            #footer div {
                padding-left: 5px !important;
				bottom:10px !important;
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
            .hrtop{
                margin-top: 30px;border:1px solid #000;width:160px !important;
            }
            .hrdown{
                border:2px solid #000;width:160px !important;margin-top:-18px;
            }
            .sh3{
                margin-top: 20px;padding-left: 20px;
            }
            .hrtop1{
                margin-top: 30px;border:1px solid #000;width:160px !important;
            }
            .hrdown1{
                border:2px solid #000;width:160px !important;margin-top:-18px;
            }
            div.sma1{
                margin-left:0px;
            }
            .boxhead{
                width: 50%!important;float: left;padding-bottom: 20px;margin-left:2px !important;height:100px; border: 1px solid black;
                border-radius: 5px;font-size: 14px;line-height:27px;padding: 10px;margin-bottom: 5px!important;margin-top: -20px !important;
            }
            .boxhead1{
                width: 48%!important;float: left;height:100px;border: 1px solid black;border-radius: 5px;
                font-size: 14px;line-height:27px;padding: 10px;margin-bottom: 5px!important; margin-top: -20px !important;
            }
            div.bot{
                margin-bottom: 5px !important;
            }
        }
        img{
            width:150px;height:50px;margin-top:20px;margin-left
        }
        .boxhead{
            width: 50%;float: left;padding-bottom: 20px;height:100px; border: 1px solid black;
            border-radius: 5px;font-size: 14px;line-height:27px;padding: 10px;margin-bottom: 5px;margin-top: -25px !important;
        }
        .boxhead1{
            width: 49%;float: left;margin-left: 6px;height:100px;border: 1px solid black;border-radius: 5px;
            font-size: 14px;line-height:27px;padding: 10px;margin-bottom: 5px;margin-top: -25px !important;
        }
        .hrtop{
            margin-top: 30px;border:1px solid #000;width:240px;
        }
        .hrdown{
            border:2px solid #000;width:240px;margin-top:-18px;border-size:
        }
        .sh3{
            margin-top: 15px;padding-left: 15px;
        }
        .hrtop1{
            margin-top: 30px;border:1px solid #000;width:240px;
        }
        .hrdown1{
           border:2px solid #000;width:240px;margin-top:-18px;
        }
        .sma1{
              margin-left: -30px;
          }
    </style>
</head>

<body>
<div class="container " >
    <div class="row">
        <div class="col-lg-7 col-lg-offset-2 col-xs-12" style="padding:0px;">

            <?php if (isset($biller->logo)) { ?>
                <div class="col-xs-3 text-center" style="margin-top: -10px;">
                    <img src="<?= base_url() . 'assets/uploads/logos/' . $biller->logo; ?>"
                         alt="<?= $biller->company != '-' ? $biller->company : $biller->name; ?>">
                </div>
            <?php }else{ ?>
                <div class="col-xs-3 text-center"></div>
            <?php } ?>
            <div class="col-xs-6 text-center" style="margin-bottom: -10px;">
                <h2 style="font-size:25px;"><b><?= lang("invoice_kh"); ?></b></h2><br>
                <h2><hr style="border:1px solid #000;width:120px;margin-top: -40px;"></h2>
                <h2><hr style="border:2px solid #000;width:120px;margin-top: -18px;"></h2>
            </div>
            <div class="col-sm-3 col-xs-3">
                <button type="button" class="btn btn-xs btn-default no-print pull-right" style="margin-right:15px; margin-top: 10px" onclick="window.print();">
                    <i class="fa fa-print"></i> <?= lang('print'); ?>
                </button>
            </div>

            <div class="col-xs-4 col-xs-4 text-right " style="margin-left: -12px;">
                <hr class="hrtop">
                <hr class="hrdown">
            </div>

            <div class="col-xs-4 col-xs-4 text-center" style="margin-left: 5px;">
                <h3 class="sh3"><b><?= lang("INVOICE"); ?></b></h3><br>
            </div>
            <div class="col-sm-4 col-xs-4 text-right sma1">
                <hr class="hrtop1">
                <hr class="hrdown1">
            </div>

             <div class="boxhead">
                <table style="width: 100%;">
                    <tr>
                        <td>អតិថិជន​</td>
                        <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                        <td><b><?=$customer->name?></b></td>
                    </tr>
                    <tr>
                        <td>អាស័យដ្ឋាន</td>
                        <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                        <td><b><?=$customer->address?></b></td>
                    </tr>
                    <tr>
                        <td>លេខទូរស័ព្ទ</td>
                        <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                        <td><b><?=$customer->phone?></b></td>
                    </tr>
                </table>
            </div>
            <?php //$this->erp->print_arrays($rates); ?>
            <div class="boxhead1">
                <table style="width: 100%;">
                    <tr>
                        <td>វិក្កយបត្រលេខ </td>
                        <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                        <td><b><?= $invs->reference_no ?></b></td>
                    </tr>
                    <tr>
                        <td>កាលបរិច្ឆេទ </td>
                        <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                        <td><b><?= $this->erp->hrld($invs->date); ?></b></td>
                    </tr>
                    <tr>
                        <td>ភ្នាក់ងារលក់ </td>
                        <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                        <td><b><?= $invs->saleman; ?></b></td>
                    </tr>
                </table>
            </div>


            <div class="clearfix"></div>
            <div class="-table-responsive">
                <table class="table table-bordered table-striped" style="width: 100%;">
                    <thead  style="font-size: 11px;">
						<tr>
							<th>ល.រ<br /><?= strtoupper(lang('no')) ?></th>
								<th>ឈ្មោះទំនិញ<br /><?= strtoupper(lang('description')) ?></th>
								<th>បរិមាណ<br /><?= strtoupper(lang('Quantity')) ?></th>
								<th>ខ្នាត<br /><?= strtoupper(lang('Unit')) ?></th>
								<th>តម្លៃរាយ<br /><?= strtoupper(lang('Unit Price')) ?></th>
								<th>សរុបទឹកប្រាក់<br /><?= strtoupper(lang('Total Amount')) ?></th>
						</tr>
                    </thead>
                    <tbody style="font-size:11px">
                        <?php $r = 1; $row2 = 1;
                        $tax_summary = array();
						$grand_total = 0;
						
					
						//$this->erp->print_arrays($invs);
                        foreach ($rows as $row):
								$free = lang('free');
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
                                    <?= $row->product_name ?>
                                </td>
                               
                                <td class="text-center" style="border-top:none !important;border-bottom:none !important;">
									<?php
											if ($row->piece == 0) {
												echo round($row->quantity);
											} else {
												echo round($row->wpiece);
											}
										?>
                                </td>
                                 <td style="border-top:none !important;border-bottom:none !important; width: 80px; text-align:center;"><?= $str_unit; ?></td>
								<?php if($Owner || $Admin || $GP['sales-price']){ ?>
									<td class="text-center" style="border-top:none !important;border-bottom:none !important;">
										<?= "$ ".$this->erp->formatMoney($row->unit_price) ?>
									</td>
								<?php }?>
                                <td class="text-right" style="border-top:none !important;border-bottom:none !important;">
                                    <?= $row->subtotal!=0 ? $this->erp->formatMoney($row->subtotal):$free; 
									$total += $row->subtotal;
									?>
                                </td>
                               
                            </tr>
                            <?php
                            $r++;
                            $row2++;
                        endforeach;
                                if ($row < 7) {
                                    $k =7- $row2;
                                    for ($j=1; $j <= $k; $j++) {
                                        echo
                                            '<tr>
                                                <td style="text-align: center; vertical-align: middle;border-top:none !important;border-bottom:none !important; height:37px !important">'.$r.'</td>
                                                <td style="border-top:none !important;border-bottom:none !important; height:37px !important"></td>
                                                <td style="border-top:none !important;border-bottom:none !important; height:37px !important"></td>
                                                <td style="border-top:none !important;border-bottom:none !important; height:37px !important"></td>
                                                <td style="border-top:none !important;border-bottom:none !important; height:37px !important"></td>
                                                <td style="border-top:none !important;border-bottom:none !important; height:37px !important"></td>
                                                
                                            </tr>';
											$r++;
                                    }
                                    
                                }
                        ?>
                        <?php
                        $col = 2;
                        $row =3;
                       
                        ?>
                        <tr>
								<td colspan="3" rowspan="<?= $row; ?>" style="border-left: 1px solid #FFF !important; border-bottom: 1px solid #FFF !important">
									<?php
									//$this->erp->print_arrays($invs);
									if ($invs->invoice_footer || $invs->invoice_footer != "") { ?>
										<div style="font-size:10px; line-height:25px;">
											<div><?= $this->erp->decode_html($invs->invoice_footer); ?></div>
										</div>
									<?php
									}
									?>
								</td>
								<td colspan="<?= $col; ?>" style="text-align: right; font-weight: bold;">ប្រាក់សរុប / <?= strtoupper(lang('TOTAL :')) ?>
								</td>
								<td align="right">$<?= $this->erp->formatMoney($invs->grand_total); ?></td>
							</tr>
							<tr>
								<td colspan="<?= $col; ?>" style="text-align: right; font-weight: bold;">ប្រាក់ត្រូវបង់ / <?= strtoupper(lang('paid :')) ?>	
								</td>
								<td align="right"><?= "$ ".$this->erp->formatMoney($invs->paid); ?></td>
							</tr>
							<tr>
								<td colspan="<?= $col; ?>" style="text-align: right; font-weight: bold;">នៅខ្វះ / <?= strtoupper(lang('BALANCE :')) ?>
								</td>
								<td align="right">$<?= $this->erp->formatMoney($invs->grand_total-$invs->paid); ?></td>
						</tr>
                    </tbody>
                </table>
            </div>
			<div class="col-ls-12"> 
		 
			<div class="col-sm-6 col-xs-6">
				
			</div>
			<div class="col-sm-6 col-xs-6">
					<p>
					<span class='pull-right' style="margin-right:10px;font-size:11px;">
						នៅថ្ងៃទី<b>&nbsp;<?php $d=explode("-",$invs->date); $dt=explode(" ",$d[2]); echo "$dt[0]";?>&nbsp;</b>
						ខែ<b>&nbsp;<?php echo "$d[1]"?>&nbsp;</b>
						ឆ្នាំ​<b>&nbsp;<?php echo "$d[0]"?>&nbsp;</b>
						</span>
					</p>
			</div>
			
		 </div>
		 &nbsp;
		 

		<div id="footer" class="row">
			<div class="col-sm-3 col-xs-3">
				<center>
					<p style="font-size: 12px; margin-top: 4px !important">អតិថិជន​ / Customer</p>
					<br>
					<p>.............................</p>
				</center>
			</div>
			<div class="col-sm-3 col-xs-3">
				<center>
					<p style="font-size: 12px; margin-top: 4px !important">អ្នកទទួល​ / Reciver</p>
					<br>
					<p>.............................</p>
				</center>
			</div>
			<div class="col-sm-3 col-xs-3">
				<center>
					<p style="font-size: 12px; margin-top: 4px !important">អ្នកដឹកជញ្ជូន / Deliver</p>
					<br>
					<p>............................</p>
				</center>
			</div>
			<div class="col-sm-3 col-xs-3">
				<center>
					<p style="font-size: 12px; margin-top: 4px !important">អ្នកលក់ / Seller</p>
					<br>
					<p>..............................</p>
				</center>
			</div>
		</div>
		
		<div style="width: 821px;margin: 20px">
			<a class="btn btn-warning no-print" href="<?= site_url('sales'); ?>" style="border-radius: 0">
	        	<i class="fa fa-hand-o-left" aria-hidden="true"></i>&nbsp;<?= lang("back"); ?>
	     	</a>
		</div>
        </div>
		
		
    </div>
			
</div>


</body>
</html>
