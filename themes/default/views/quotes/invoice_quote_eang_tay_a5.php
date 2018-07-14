<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $this->lang->line("quote_invoice") . " " . $inv->reference_no; ?></title>
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
                <div class="col-xs-6 text-center" style="margin-top:10px !important">
                    <h2 style="font-family: Khmer M1;"><?= lang("Quotation"); ?></h2>
                </div>
            <div class="row" >
				<div class="col-sm-12 col-xs-12 inv" style="margin-top: -30px !important">
					<center>
						<h3 style="font-size:14px !important;line-height:23px;"><b>មានទទួលកិនភ្លីធំ ភ្លីក្បឿង <br> និងផ្តត់ផ្គង់សំភារៈសំណង់ ដែក <br> ស័ង្គសី អាលុយមីញ៉ូមគ្រប់ប្រភេទ</b></h3>
						<h4></h4>
					</center>
				</div>
			</div>
			<div class="col-sm-12 col-xs-12" style="font-size:11px;">
				<div  class="col-sm-6 col-xs-6">
				
				</div>
				<div  class="col-sm-6 col-xs-6 text-right" style="font-size:12px; padding-right:40px;">
					N<sup>0</sup>&nbsp;:&nbsp;&nbsp;<b><?= $invs->reference_no?></b>
				</div>
			</div>
			
            <div class="col-sm-12 col-xs-12" style="font-size:11px;">
			<br>
				<div  class="col-sm-3 col-xs-3">
						អតិថិជន <b>&nbsp;&nbsp;<?= $customer->name?></b></div>
				<div  class="col-sm-5 col-xs-5">
						អាស័យដ្ឋាន : <b>&nbsp;&nbsp;<?= $customer->address?></b></div>
				<div  class="col-sm-4 col-xs-4">
						Tel :<b>&nbsp;&nbsp;<?= $customer->phone?></b></div>
			</div>
            <div class="clearfix"></div>
			<div><br/></div>
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
						//$this->erp->print_arrays($row);
									$free = lang('free');
									$product_unit = '';
									$total = 0;
									
									if($row->variant){
										$product_unit = $row->variant;
									}else{
										$product_unit = $row->product_unit;
									}
									$product_name_setting;
									if($setting->show_code == 0) {
										$product_name_setting = $row->product_name . ($row->variant ? ' (' . $row->variant . ')' : '');
									}else {
										if($setting->separate_code == 0) {
											$product_name_setting = $row->product_name . " (" . $row->product_code . ")" . ($row->variant ? ' (' . $row->variant . ')' : '');
										}else {
											$product_name_setting = $row->product_name . ($row->variant ? ' (' . $row->variant . ')' : '');
										}
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
									<?= round($row->quantity); ?>
                                </td>
                                 <td style="border-top:none !important;border-bottom:none !important; width: 80px; text-align:center;"><?= $product_unit; ?></td>
								<?php if($Owner || $Admin || $GP['sales-price']){ ?>
									<td class="text-center" style="border-top:none !important;border-bottom:none !important;">
										<?=  $row->unit_price>0 ? "$ ".$this->erp->formatMoney($row->unit_price) : $free; ?>
									</td>
								<?php }?>
                                <td class="text-right" style="border-top:none !important;border-bottom:none !important;">
                                    <?=  $row->subtotal!=0 ? "$ ".$this->erp->formatMoney($row->subtotal):$free; 
									
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
								<td align="right">$<?= $this->erp->formatMoney($invs->total); ?></td>
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
			<div class="col-sm-4 col-xs-4">
				<center>
					<p style="font-size: 12px; margin-top: 4px !important">អតិថិជន​ / Customer</p>
					<br>
					<p>..........................................</p>
				</center>
			</div>
			<div class="col-sm-4 col-xs-4">
				<center>
					<p style="font-size: 12px; margin-top: 4px !important">អ្នកដឹកជញ្ជូន / Deliver</p>
					<br>
					<p>..........................................</p>
				</center>
			</div>
			<div class="col-sm-4 col-xs-4">
				<center>
					<p style="font-size: 12px; margin-top: 4px !important">អ្នកលក់ / Seller</p>
					<br>
					<p>...........................................</p>
				</center>
			</div>
		</div>
		
		<div style="width: 821px;margin: 20px">
			<a class="btn btn-warning no-print" href="<?= site_url('quotes'); ?>" style="border-radius: 0">
	        	<i class="fa fa-hand-o-left" aria-hidden="true"></i>&nbsp;<?= lang("back"); ?>
	     	</a>
		</div>
        </div>
		
		
    </div>
			
</div>


</body>
</html>
