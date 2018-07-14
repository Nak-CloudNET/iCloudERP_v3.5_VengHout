<?php //$this->erp->print_arrays($Settings);?>

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
            line-height: 15px !important;
        }

        .table td {
            padding: 4px;
            line-height: 10px !important;
        }
        hr{
            border-color: #333;
            width:100px;
            margin-top: 70px;
        }
        .table, .table tbody tr td, .table tr th {
            border: 1px solid #000 !important;

        }
        .img1{
            width: 80px;height: 90px;padding-top: 20px;
        }
        .img2{
            width: 150px;height: 60px;padding-top: 20px;
        }
        .pull-right{
            float:right !important;
            margin-right: -360px !important;
            line-height: 25px;font-size: 12px;
            margin-bottom: 10px !important;
        }
        .pull-left{
            margin-left: -13px !important;
            margin-bottom: 10px !important;
        }
        tbody ,tr,td{
            line-height: 20px;
            font-size: 12px !important;
        }
        h3{
            font-family: "Khmer OS Muol";
        }
        h4{
            font-family: "Khmer OS Muol";
        }
        @media print{
            .table {
                font-size: 11px !important;
            }
            .table thead {
                font-size: 11px !important;
            }
            .container {
                width: 98% !important;
                height: 27.7cm !important;
                margin: 0 auto !important;
            }
            .right_hr{
                margin-left : -5px !important;
                width : 150px !important;
            }
            .left_hr{
                margin-left : -55px !important;
                width : 150px !important;
            }
            tbody ,tr,td{
                line-height: 20px !important;
                font-size: 12px !important;
            }
            #footer div {
                padding-left: 5px !important;
            }
            #customer-table {
                width: 7.50cm !important;
            }
            .table th {
                text-align: center;
                padding: 5px;
                line-height: 15px !important;
            }

            .table td {
                padding: 4px;
                line-height: 10px !important;
            }
            .pull-right{
                float:right !important;
                margin-right: -70px !important;
                font-size: 12px !important;
                line-height: 18px !important;
            }
            .pull-left{
                margin-left: -30px !important;
                line-height: 18px !important;
                font-size: 12px !important;
            }
        }
    </style>
</head>

<body>
<div class="container print_rec" id="wrap">
    <div class="row">
        <div class="col-lg-12">
            <?php if (isset($biller->logo)) { ?>
				<div class="col-xs-3 text-center" style="margin-bottom:20px;">
                    <img src="<?= base_url() . 'assets/uploads/logos/' . $biller->logo; ?>"
                         alt="<?= $biller->company != '-' ? $biller->company : $biller->name; ?>">
                </div>
			 <?php }else{ ?>
				<div class="col-xs-3 text-center" style="margin-bottom:20px;"></div>
			 <?php } ?>
                <div class="col-xs-6 text-center">
                    <h3 style="font-family: Khmer M1"><?= lang("invoice_kh"); ?></h3>
                    <h3 style="margin-top: -10px !important; margin-bottom: 0px !important"><?= lang("sales_receipt"); ?></h3>
                </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12" style="margin-top: 20px !important;">
                    <div class="col-xs-6 col-sm-6 pull-left">
                        <table>
                            <tr>
                                <td><?= lang("អតិថិជន​"); ?></td>
                                <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                                <td><b><?= $customer->name​?></b></td>
                            </tr>
                            <tr>
                                <td><?= lang("អាស័យដ្ឋាន"); ?></td>
                                <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                                <td><b><?= $customer->address ?></b></td>
                            </tr>
                            <tr>
                                <td><?= lang("លេខទូរស័ព្ទ"); ?></td>
                                <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                                <td><b><?= $customer->phone ?></b></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-xs-6 col-sm-6 pull-right">
                        <table>
                            <tr>
                                <td><?= lang("លេខ"); ?></td>
                                <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                                <td><b><?= $inv->reference_no;?></b></td>
                            </tr>
                            <tr>
                                <td><?= lang("ថ្ងៃ-ខែ-ឆ្នាំ"); ?></td>
                                <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                                <td><b><?= $this->erp->hrld($inv->date);?></b></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="-table-responsive">
                <table class="table table-bordered table-striped" style="width: 100%;">
                    <thead  style="font-size: 13px;">
                    <tr>
                        <th style="width: 5% !important;"><?= lang("ល.រ"); ?><br /><span style="font-size: 11px">No</span></th>
                        <th style="width: 30% !important;"><?= lang("description_kh"); ?><br><span style="font-size: 11px"><?= lang("descript");  ?></span></th>
                        <th style="width: 10% !important;"><?= lang("qty_kh"); ?><br><span style="font-size: 11px"><?= lang("qty");  ?></span></th>
                        <th style="width: 10% !important;"><?= lang("unit_kh"); ?><br><span style="font-size: 11px"><?= lang("unit");  ?></span></th>
                        <?php if($Owner || $Admin || $GP['sales-price']){ ?>
                            <th style="width: 17% !important;"><?= lang("unit_price_kh"); ?><br><span style="font-size: 11px"><?= lang("UNIT_PRICE");  ?></span></th>
                        <?php }?>
                        <th style="width: 15% !important;"><?= lang("បញ្ចុះតម្លៃ"); ?><br><span style="font-size: 11px"><?= lang("DISCOUNT");  ?></span></th>
                        <th style="width: 13% !important;"><?= lang("total_kh"); ?><br><span style="font-size: 11px"><?= lang("total_capital");  ?></span></th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php $r = 1; $row2 = 1;
                        $tax_summary = array();
						$grand_total = 0;
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
                           $variant = $this->sales_model->getProductVariantByOptionID($row->product_id);

                        ?>
                            <tr>
                                <td style="text-align:center; width:40px; vertical-align:middle;"><?= $r; ?></td>
                                <td style="vertical-align:middle;">
                                    <?= $row->product_name ?>
                                </td>
								<td style="text-align:center;">
                                    <?= number_format($row->quantity, 2) ?>
                                </td>
                                <td style=" width: 80px; text-align:center;"><?= $str_unit; ?></td>
								<?php if($Owner || $Admin || $GP['sales-price']){ ?>
									<td class="text-center">
										<?= "$ ".$this->erp->formatMoney($row->unit_price) ?>
									</td>
								<?php }?>
								<td style="text-align:center;">
                                    <?php
										if ($Settings->product_discount && $inv->product_discount != 0) {
											 if (strpos($row->discount , '%')){
												echo  ($row->discount != 0 ? '<small>(' . $row->discount . ')</small> ' : '') . $this->erp->formatMoney($row->item_discount);
											} else{
												echo $this->erp->formatMoney($row->item_discount);
											}
										}else{
											echo $this->erp->formatMoney($inv->product_discount);
										}

									?>
                                </td>
                                <td class="text-right">
                                    <?= "$ ".$this->erp->formatMoney(($row->quantity)*($row->unit_price)) ?>
                                </td>

                            </tr>
                            <?php
                            $r++;
                            $row2++;
                        endforeach;
                                if ($row < 8) {
                                    $k = 8 - $row2;
                                    for ($j=1; $j <= $k; $j++) {
                                        echo
                                            '<tr>
                                                <td style=" height:26px !important"></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
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
								<td colspan="4" rowspan="<?= $rows; ?>" style="padding-top:20px;border-left: 1px solid #FFF !important;​border-bottom: 1px solid #FFF !important">
									<?php
									if ($inv->invoice_footer || $inv->invoice_footer != "") { ?>
										<div>
											<div style="font-size:11px !important;"><b><?= lang("note_kh"); ?>: </b> <?= $this->erp->decode_html(nl2br($inv->invoice_footer)); ?></div>
										</div>
									<?php
									}
									?>
								</td>
							<?php }else{?>
								<td colspan="5" rowspan="<?= $rows; ?>" style="border-left: 1px solid #FFF !important; border-bottom: 1px solid #FFF !important">
									<?php
									if ($inv->invoice_footer || $inv->invoice_footer != "") { ?>
										<div>
											<p><b><?= lang("note_kh"); ?>:</b></p>
											<div><?= $this->erp->decode_html($inv->invoice_footer); ?></div>
										</div>
									<?php
									}
									?>
								</td>
							<?php } ?>
                            <td colspan="2" style="text-align:right; font-weight:bold;"><?= lang("total_kh"); ?>
                            </td>
                            <td colspan="2" style="text-align:right; padding-right:10px; font-weight:bold;"><?= "$ ".$this->erp->formatMoney($grand_total); ?></td>
                        </tr>

						<?php if($inv->order_discount != 0){ ?>
							<tr>
								<td colspan="2" style="text-align:right; font-weight:bold;"><?= lang("discount_kh"); ?>
								</td>
								<td colspan="2" style="text-align:right; padding-right:10px; font-weight:bold;"><?= "$ ".$this->erp->formatMoney($inv->order_discount); ?></td>
							</tr>
						<?php } ?>
                        <tr>
                            <td colspan="2" style="text-align:right; font-weight:bold;"><?= lang("totalpaid_kh"); ?>
                            </td>
                            <td colspan="2" style="text-align:right; padding-right:10px; font-weight:bold;"><?= "$ ".$this->erp->formatMoney($grand_total-$inv->total_discount); ?></td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align:right; font-weight:bold;"><?= lang("deposit_kh"); ?>
                            </td>
                            <td colspan="2" style="text-align:right; padding-right:10px; font-weight:bold;"><?= "$ ".$this->erp->formatMoney($inv->paid); ?></td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align:right; font-weight:bold;"><?= lang("balance_kh"); ?>
                            </td>
                            <td colspan="2" style="text-align:right; padding-right:10px; font-weight:bold;"><?= "$ ".$this->erp->formatMoney(($grand_total-$inv->total_discount)-$inv->paid); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <br>
    <div id="footer" class="row" style="margin-top: 80px !important;">
        <div class="col-sm-4 col-xs-4 text-center">
                <p><?= lang('អ្នកទិញ'); ?>/The Buyer</p><br>
                <hr style="border:1px solid #000;width:150px;">
        </div>
        <div class="col-sm-2 col-xs-2">

        </div>
        <div class="col-sm-2 col-xs-2">

        </div>
        <div class="col-sm-4 col-xs-4 text-center">
                <p><?= lang('seller_kh'); ?>/The Seller</p><br>
                <hr style="border:1px solid #000;width:150px;">
        </div>

    </div>
</div>
<br>
	<div style="width: 76%;margin: 0 auto;">
		<a class="btn btn-warning no-print" href="<?= site_url('sales'); ?>">
        	<i class="fa fa-hand-o-left" aria-hidden="true"></i>&nbsp;<?= lang("back"); ?>
     	</a>
	</div>
	<br>
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
