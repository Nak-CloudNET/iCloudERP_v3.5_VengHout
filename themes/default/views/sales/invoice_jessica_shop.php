<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Invoice&nbsp;<?= $invs->reference_no ?></title>
	<link href="<?php echo $assets ?>styles/theme.css" rel="stylesheet">
	<link href="<?php echo $assets ?>styles/bootstrap.min.css" rel="stylesheet">
	<link href="<?php echo $assets ?>styles/custome.css" rel="stylesheet">
	

</head>
<style>
		body {
			font-size: 14px !important;
		}
			
		.container {
			width: 29.7cm;
			margin: 20px auto;
			/*padding: 10px;*/
			box-shadow: 0 0 5px rgba(0, 0, 0, 0.5);
		}
        @page {
            size: A5;
        }
		@media print {
			.pageBreak {
				page-break-after: always;
			}
			.container {
				height: 29.5cm !important;
			}
			.customer_label {
				padding-left: 0 !important;
			}
			
			.invoice_label {
				padding-left: 0 !important;
			}
            .bcol b {
                color:red !important;

            }
			#footer {
				position: fixed !important;
				bottom: 10px !important;
			}
			.row table tr td {
				font-size: 12px !important;
			}
			img{
				margin-left:40px !important;
			}
			.table thead > tr > th, .table tbody > tr > th, .table tfoot > tr > th {
				background-color: #444 !important;
				color: #FFF !important;
			}
			
			.row .col-xs-7 table tr td, .col-sm-5 table tr td{
				font-size: 12px !important;
			}
			#note{
					max-width: 95% !important;
					margin: 0 auto !important;
					border-radius: 5px 5px 5px 5px !important;
					margin-left: 26px !important;
				}
		}
		.thead th {
			text-align: center !important;
		}
		
		.table thead > tr > th, .table tbody > tr > th, .table tfoot > tr > th, .table thead > tr > td, .table tbody > tr > td, .table tfoot > tr > td {
			border: 1px solid #000 !important;
		}
		
		.company_addr h3:first-child {
			font-family: Khmer OS Muol !important;
			//padding-left: 12% !important;
		}
		
		.company_addr h3:nth-child(2) {
			margin-top:-2px !important;
			//padding-left: 130px !important;
			font-size: 26px !important;
			font-weight: bold;
		}
		
		.company_addr h3:last-child {
			margin-top:-2px !important;
			//padding-left: 100px !important;
		}
		
		.company_addr p {
			font-size: 12px !important;
			margin-top:-10px !important;
			padding-left: 20px !important;
		}
		
		.inv h4:first-child {
			font-family: Khmer OS Muol !important;
			font-size: 14px !important;
		}
		
		.inv h4:last-child {
			margin-top:-5px !important;
			font-size: 14px !important;
		}
		
		button {
			border-radius: 0 !important;
		}
		img{
            width: 150px;
            height: 50px;
			margin-top:-20px;
		}
        .bcol b {
            color:red;
        }
		
	</style>

<body>
	<div class="contain-wrapper" style="width: 17cm;height:auto;margin: 20px auto; padding:20px;box-shadow: 0 0 5px rgba(0, 0, 0, 0.5); ">
		<div class="row">
			<button type="button" class="btn btn-xs btn-default no-print pull-right" style="margin-right:15px; margin-top: 10px" onclick="window.print();">
        		<i class="fa fa-print"></i> <?= lang('print'); ?>
    		</button>
		</div>
	
		<div class="row" >
            <?php if (isset($biller->logo)) { ?>
                <div class="col-xs-12 text-center" >
                    <img src="<?= base_url() . 'assets/uploads/logos/' . $biller->logo; ?>"
                         alt="<?= $biller->company != '-' ? $biller->company : $biller->name; ?>">
                </div>
            <?php }else{ ?>
                <div class="col-xs-12 text-center"></div>
            <?php } ?>
		</div>
		<div class="row" >
            <div class="col-xs-12 text-center">
                <?= $biller->address?>
            </div>
		</div>
		<div class="row" >
            <div class="col-xs-12 text-center" style="margin-bottom:20px;">
                Tel &nbsp;&nbsp;:&nbsp;&nbsp; <?= $biller->phone; ?> , E-mail &nbsp;&nbsp;:&nbsp;&nbsp; <?= $biller->email; ?>
            </div>
		</div>

		<div class="row" style="line-height: 20px;">
			<div class="col-sm-6 col-xs-6">
                <?php //$this->erp->print_arrays($inv);?>
				<table>
					<tbody">
                    <tr>
                        <td style="font-size:12px !important;">
                            អ្នកលក់  &nbsp;&nbsp;:&nbsp;&nbsp; <?= $inv->saleman;?>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size:12px !important;">
                            អតិថិជន​​  &nbsp;&nbsp;:&nbsp;&nbsp; <?= $customer->names;?>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size:12px !important;">
                            អាស័យដ្ឋាន​  &nbsp;&nbsp;:&nbsp;&nbsp; <?= $customer->address;?>
                        </td>
                    </tr>
					</tbody>
				</table>
				<br>	
			</div>

			<div class="col-sm-6 col-xs-6" style="text-align:left">
				<table >
					<tbody >
                    <tr>
                        <td style="font-size: 13px;">
                            ថ្ងៃខែឆ្នាំ  &nbsp;&nbsp;:&nbsp;&nbsp;<?= $this->erp->hrld($inv->date); ?>
                        </td>
                    </tr>
						<tr>
							<td style="font-size:12px !important;">
                                <p>លេខវិក័យបត្រ &nbsp;&nbsp;:&nbsp;&nbsp;<b style="font-size:14px;"><?=$inv->reference_no;?></b></p>
							</td>
						</tr>
						</tr>
					</tbody>
				</table>
				<br>
			</div>
		 
		</div>		
				<?php
                    $totalItemDiscount = 0;
                    $totalItemTax = 0;

                foreach ($rows as $row){
                    $totalItemDiscount += $row->item_discount;
                    $totalItemTax +=$row->item_tax;
                }

                ?>
		<div class="row">
			<div class=" col-sm-12">
				<table style="width: 100%;">
					<thead>
					<tr>
						 <th style="width: 10%">កូដ<br>Code </th>
						 <th style="width: 25%">បរិយាយ<br>DESCRIPTION</th>
						 <th class="text-center" style="width: 10%">ចំនួន<br>QTY</th>
						 <th class="text-right"  style="width: 10%">តម្លៃ<br>PRICE</th>
                        <?php if ($totalItemDiscount) { ?>
                            <th class="text-center"  style="width: 20%">ចុះថ្លៃ<br /><?= strtoupper(lang('disc')) ?></th>
                        <?php } ?>
                        <?php if ($totalItemTax) { ?>
                            <th class="text-center"  style="width: 10%">ពន្ធទំនិញ<br /><?= strtoupper(lang('tax')) ?></th>
                        <?php } ?>
						 <th class="text-right" class="text-right"  style="width: 10%">សរុប<br>AMOUNT</th>

					</tr>	 
					</thead>
				    <tbody style="border-bottom:2px solid black;line-height:25px;">
                        <?php
                        $no=1;
                        $subtotal=0;
                        foreach($rows as $row){
                            $free = lang('free');
                            $subtotal+=$row->subtotal;
                            //$this->erp->print_arrays($row);
                        ?>
                        <tr>
                            <td style="vertical-align:middle;"><?php echo $row->product_code; ?></td>
                            <td style=" vertical-align:middle;"><?php echo $row->product_name ?></td>
                            <td style="text-align:center; vertical-align:middle;"><?php echo round($row->quantity) ?></td>
                            <td style="text-align:right; vertical-align:middle;"><?php echo $row->subtotal!=0 ? $this->erp->formatMoney($row->net_unit_price):$free;
                                $total += $row->subtotal; ?>
                            </td>
                            <?php if ($totalItemDiscount) {?>
                                <td style="vertical-align: middle; text-align: center">
                                    <?=($row->discount != 0 ? '<small>(' . $row->discount . ')</small> ' : '') .$this->erp->formatMoney.'$'.($row->item_discount);?></td>
                            <?php } ?>
                            <?php if ($totalItemTax) {?>
                                <td style="vertical-align: middle; text-align: center;font-size:12px !important;">
                                    $<?=$this->erp->formatMoney($row->item_tax);?></td>
                            <?php } ?>
                            <td style="text-align:right; vertical-align:middle;"><?php echo $row->subtotal!=0 ? $this->erp->formatDecimal($row->subtotal):$free;
                                $total += $row->subtotal; ?>
                            </td>
                        </tr>
                        <?php $no++; } ?>
				    </tbody>
                    <table style="width: 100%; margin-top: 5px;">
                        <tbody>
                            <tr>
                                <td style="text-align:left;width:25%;"><b>សរុប</b></td>
                                <td style="text-align:right;width:35%; padding-right: 5px"><b>Sub Total (USD) :</b></td>
                                <td style="text-align:right;"><b><?=$inv->total;?></b></td>
                            </tr>
                            <?php if($inv->order_discount != 0){?>
                            <tr>
                                <td style="text-align:left;width:25%;">បញ្ចុះតម្លៃ</td>
                                <td style="text-align:right;width:35%;padding-right: 5px;">Discount :</td>
                                <td style="text-align:right;">$ <?=$this->erp->formatMoney($inv->order_discount)?></td>
                            </tr>
                            <?php } ?>
                            <?php if($inv->shipping != 0){?>
                                <tr>
                                    <td style="text-align:left;width:25%;">ដឹកជញ្ជូន</td>
                                    <td class="text-left"​​​​ style="text-align:right;width:35%; padding-right: 5px;">Shipping :</td>
                                    <td style="text-align:right;">$ <?=$this->erp->formatMoney($inv->shipping);?>
                                    </td>
                                </tr>
                            <?php } ?>
                            <?php if($inv->order_tax != 0){?>
                                <tr>
                                    <td style="text-align:left;width:25%;">ពន្ធ</td>
                                    <td class="text-left"​​​​ style="text-align:right;width:35%; padding-right: 5px;">VAT :</td>
                                    <td style="text-align:right;">$ <?=$this->erp->formatMoney($inv->order_tax);?>
                                    </td>
                                </tr>
                            <?php } ?>
                            <tr>
                                <td style="text-align:left;width:25%;"><b>សរុបត្រូវបង់</b></td>
                                <td style="text-align:right;width:35%;padding-right: 5px;"><b>GrandTotal :</b></td>
                                <td style="text-align:right;"><b>$ <?=$this->erp->formatMoney($inv->grand_total)?></b></td>
                            </tr>

                        </tbody>
                    </table>
				</table>
			</div>
		</div>


        <table class="received" style="width:100%;margin-top: 5px;">
            <tr>
                <th style="border-left:2px solid #000;border-top:2px solid #000;border-bottom:2px solid #000;border-right:none;width:64%;"  class="text-right">ប្រាក់ទទួល/Received (<?= $default_currency->code; ?>) :</th>
                <th style="border-right:2px solid #000;border-top:2px solid #000;border-bottom:2px solid #000;border-left:none;" class="text-right"><?= '$ '. $this->erp->formatMoney($inv->paid); ?></th>
            </tr>

        </table>


        <div style="width:100%;text-align:left;margin-top:10px;display:none">
            ពិន្ទុចាស់ - Old Point 	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <b></b><br/>
            ពិន្ទុសរុប - Total Point 	&nbsp;&nbsp;: <b></b>
        </div>
        <div style="text-align:center;font-size:11px;margin-top:10px;">
            <?=  $biller->invoice_footer; ?> <br>
            ~ ~ ~ <b>CloudNet</b> &nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size:12px;">www.cloudnet.com.kh</span> ~ ~ ~
        </div>
		<div style="width: 821px;margin: 20px">
			<a class="btn btn-warning no-print" href="<?= site_url('sales'); ?>" style="border-radius: 0">
				<i class="fa fa-hand-o-left" aria-hidden="true"></i>&nbsp;<?= lang("back"); ?>
			</a>
		</div>

	
	</div>
</body>
</html>