<?php //$this->erp->print_arrays($quote_items);?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $this->lang->line("delivery_note") . " " . $inv->do_reference_no; ?></title>
    <link href="<?php echo $assets ?>styles/theme.css" rel="stylesheet">
    <style type="text/css">
        html, body {
            height: 100%;
            background: #FFF;
        }
		.container {
			width: 29.7cm;
			margin: 20px auto;
			height: 29cm !important;
			/*padding: 10px;*/
			box-shadow: 0 0 5px rgba(0, 0, 0, 0.5);
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
		.table thead > tr > th, .table tbody > tr > th, .table tfoot > tr > th, .table thead > tr > td, .table tbody > tr > td, .table tfoot > tr > td {
		border: 1px solid #000 !important;
		}
        @media print{
			.container {
				width: 98% !important;
				margin: 0 auto !important;
			}
			
			.line {
				width:50% !important;
			}
			#footer {
				position: absolute !important;
				bottom: 0 !important;
			}
        }
    </style>
</head>

<body>
<div class="container" style="width:50%;margin: 0 auto;">

        <div class="row">
			<div class="col-lg-3 col-sm-3 col-xs-3">
				<?php if ($logo) { ?>
				<img src="<?= base_url() . 'assets/uploads/logos/' . $biller->logo; ?>" alt="" width="150px !important;">
			</div>			
			<div class="col-lg-8 col-sm-8 col-xs-8" style="text-align:center !important; margin-left:-60px !important;margin-top:25px !important;">
				<?php 
				if($biller->address){echo $biller->address;}
				echo '<br>';
				if($biller->phone){echo lang("tel") . " : ".$biller->phone;}
				if($biller->email){echo "&nbsp &nbsp".lang("email")." : ". $biller->email;}
				?> 
			</div>
			<!--<h2><strong><?= $biller->company ?></strong></h2>-->
			<div class="col-lg-1 col-sm-1 col-xs-1" style="margin:30px !important;">
				<button type="button" class="btn btn-xs btn-default no-print pull-right" style="margin-right:15px;" onclick="window.print();">
                		<i class="fa fa-print"></i> <?= lang('print'); ?>
            	</button>
			</div>
					
		</div>
		<center><h2><?= lang("deliver_note")?></h2></center>
             
                <div class="col-xs-3">
                   
                </div>
            <?php } ?>
            <div class="clearfix"></div>
          
            <div class="row padding10">
                <div class="col-xs-5" style="float: left;font-size:14px">
                    <table>
                        <tr>
                            <td>
                                <p><?= lang("customer");?></p>
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
                                <p><?= lang("tel");?></p>
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
                    <table>
                        <tr>
                            <td>
                                <p><?= lang("Deli_n"); ?><sup>o</sup></p>
                            </td>
                            <td>
                                <p>&nbsp;:&nbsp;</p>
                            </td>
                            <td>
                                <p><?= "<b>".$inv->do_reference_no."</b>";?></p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p><?= lang("Invoice_Nº"); ?></p>
                            </td>
                            <td>
                                <p>&nbsp;:&nbsp;</p>
                            </td>
                            <td>
                                <p><?= "<b>".$inv->sale_reference_no ."</b>";?></p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p><?= lang("date"); ?></p>
                            </td>
                            <td>
                                <p>&nbsp;:&nbsp;</p>
                            </td>
                            <td>
                                <p><?= "<b>".$this->erp->hrsd($inv->date)."</b>";?></p>
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
			
                <table class="table table-bordered">
                    <thead  style="font-size: 13px;">
						<tr >
							<th>ល.រ (<?= lang("n");?><sup>o</sup>)</th>
							<?php if($setting->show_code == 1 && $setting->separate_code == 1) { ?>
							<th style="width:30% !important">លេខកូដទំនិញ​<br> (<?= lang('p_n'); ?>)</th>
							<?php } ?>
							<th style="width:50% !important">បរិយាមុខទំនិញ ឫសេវាកម្ម <br>(<?= lang("descript"); ?>)</th>
							<th>ឯកតាគិត​ (<?= lang("unit"); ?>)</th>
							<th>បរិមាណ​ (<?= lang("qty"); ?>)</th>
						</tr>
                    </thead>
                    <tbody style="font-size: 13px;">
                        <?php
                            $no = 1;
                            $total_amount = 0;
                            $row = 0;
                        ?>
                       <?php foreach($inv_items as $inv_item) { ?>
                            <?php
                                $str_unit = "";
                                if($inv_item->option_id){
                                    $var = $this->sale_order_model->getVar($inv_item->option_id);
                                    $str_unit = $var->name;
                                }else{
                                    $str_unit = $inv_item->unit;
                                }
                            ?>
							<tr>
								<td style="text-align:center; width:5%; vertical-align:middle;"><?= $no; ?></td>
								<?php if($setting->show_code == 1 && $setting->separate_code == 1) { ?>
								<td style="vertical-align:middle; width:15%;" >
									<?= $inv_item->code ?>
								</td>
								<?php } ?>
								<td style="vertical-align:middle;width:30%">
									<?= $inv_item->description?>
								</td>
                                <td style="vertical-align:middle;width:30%" class="text-center" >
                                    <?= $str_unit ?>
                                </td>
                                <td style="vertical-align:middle;width:30%" class="text-center">
                                    <?= $this->erp->formatMoney($inv_item->qty)?>
                                </td>
							</tr>
                            <?php
                            $no++;
                            if($inv_item->option_id){
                                $total_amount +=  $inv_item->variant_qty;
                            }else{
                                $total_amount += $inv_item->qty;
                            }
                            
                            $row++;
                         }
                            if ($no < 13) {
                                $k = 13 - $no;
                                for ($j=1; $j <= $k; $j++) {
                                    echo
                                        '<tr>
                                            <td height="34px" class="text-center"></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
											
                                        </tr>';
                                }
                                
                            }
                        ?>
                    </tbody>
                    <tfoot style="font-size: 13px;">
                    <?php
    					$discount_percentage = '';
    					if (strpos(isset($inv->order_discount_id), '%') !== false) {
    						$discount_percentage = $inv->order_discount_id;
					}
                    ?>
                    <?php if (isset($inv->grand_total) != isset($inv->total)) { 
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
                    ?>
                        <tr>
                            
                            <td colspan="5" rowspan="<?= $row;?>">
                                <?php if ($inv->note || $inv->note != "") { ?>
                                    <b><p class="bold"><?= lang("note"); ?>:</p></b>
                                <?= $this->erp->decode_html($inv->note); ?>
                                <?php } ?>
                            </td>
                            <td style="text-align:right;"><?= lang("total"); ?>
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
                        echo '<tr><td colspan="5"></td><td colspan="3" style="text-align:right;">' . lang("surcharge") . ' (' . $default_currency->code . ')</td><td style="text-align:right;">' . $this->erp->formatMoney($return_sale->surcharge) . '</td></tr>';
                    }
                    ?>
                    <?php if (isset($inv->order_discount) != 0) {
                        echo '<tr><td colspan="3" style="text-align:right;">' . lang("order_discount") . ' (' . $default_currency->code . ')</td><td style="text-align:right;"><span class="pull-left">'.($discount_percentage?"(" . $discount_percentage . ")" : '').'</span>' . $this->erp->formatMoney($inv->order_discount) . '</td></tr>';
                    }
                    ?>
					<?php if (isset($inv->shipping) != 0) {
                        echo '<tr><td colspan="3" style="text-align:right;">' . lang("shipping") . ' (' . $default_currency->code . ')</td><td style="text-align:right;">' . $this->erp->formatMoney($inv->shipping) . '</td></tr>';
                    }
                    ?>
                    <?php if (isset($Settings->tax2) && isset($inv->order_tax) != 0) {
                        echo '<tr><td colspan="3" style="text-align:right;">' . lang("order_tax") . ' (' . $default_currency->code . ')</td><td style="text-align:right;">' . $this->erp->formatMoney($inv->order_tax) . '</td></tr>';
                    }
                    ?>
                    
                    <tr>
                        <td colspan="3">
                            <div >
                            <?php if ($inv->note || $inv->note != "") { ?>
                                <div>
                                    <p style="text-align:left; font-weight:bold;"><?= lang("note"); ?>:<?= $this->erp->decode_html($inv->note); ?></p>
                            <?php } ?>
                            </div>
                        </td>
                        <td  style="text-align:right; font-weight:bold;"><?= lang("total_amount"); ?>
                        </td>
                        <td style="text-align:center; font-weight:bold;"><?= $this->erp->formatMoney($total_amount); ?></td>

                    </tr>

                    </tfoot>
                </table>
				<div class="row" id="footer">
						<div class="col-lg-4 col-sm-4 col-xs-4" style="font-size: 12px">
							<center>
							<p class="bold">
								អ្នកទទួលទំនិញ<br>
								Receive by
							</p>
							<br><br><br><br>
							<p><?= lang("name"); ?> : ...............................</p>
							<p><?= lang("date"); ?> :  ..../......./...................</p>
							</center>
						</div>
						<div class="col-lg-4 col-sm-4 col-xs-4" style="font-size: 12px">
							<center>
							<p class="bold">
								អ្នកដឹកជញ្ជូនទំនិញ <br>
								Delivery by
							</p>
							<br><br><br><br>
							<p><?= lang("name"); ?> : ...............................</p>
							<p><?= lang("date"); ?> :  ..../......./...................</p>
							</center>
						</div>
						<div class="col-lg-4 col-sm-4 col-xs-4" style="font-size: 12px">
							<center>
							<p class="bold">
								អ្នកបញ្ចេញទំនិញ <br>
								stock_keeper/issued_by
							</p>
							<br><br><br><br>
							<p><?= lang("name"); ?> : ...............................</p>
							<p><?= lang("date"); ?> :  ..../......./...................</p>
							</center>
						</div>
				</div>
            </div>
            


</div>

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
