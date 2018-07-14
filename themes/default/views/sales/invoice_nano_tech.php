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
            height: 70px;
			margin-top:-20px;
		}
        .bcol b {
            color:red;
        }
		
	</style>

<body>
	<div class="container" style="width: 17cm;height:21 cm;margin: 20px auto; padding:20px;box-shadow: 0 0 5px rgba(0, 0, 0, 0.5); ">
		<div class="row">
			<button type="button" class="btn btn-xs btn-default no-print pull-right" style="margin-right:15px; margin-top: 10px" onclick="window.print();">
        		<i class="fa fa-print"></i> <?= lang('print'); ?>
    		</button>
		</div>
	
		<div class="row" >
            <?php if (isset($biller->logo)) { ?>
                <div class="col-xs-3 text-center" style="margin-bottom:20px;">
                    <img src="<?= base_url() . 'assets/uploads/logos/' . $biller->logo; ?>"
                         alt="<?= $biller->company != '-' ? $biller->company : $biller->name; ?>">
                </div>
            <?php }else{ ?>
                <div class="col-xs-3 text-center" style="margin-bottom:20px;"></div>
            <?php } ?>
            <div class="col-xs-6 text-center">
                <h2 style="margin-top: -10px !important; margin-bottom: 0px !important"><?= lang("INVOICE"); ?></h2>
            </div>
            <div class="col-xs-3"></div>

            <div class="clearfix"></div>
            <br>
		</div>

		<div class="row">		
			<div class="col-sm-7 col-xs-7">
				<table>
					<tbody">
						<tr>
							<td style="font-size:15px !important;">
                                <?= $biller->address?>
							</td>
						</tr>
						<tr>
							<td style="font-size:15px !important;">
							<b>CUSTOMER:</b>
							</td>
						</tr>
						<tr>
							<td style="font-size:15px !important;">
							Name: <?= $customer->names;?>
							</td>
						</tr>

						<tr>
							<td style="font-size:12px !important;">
							Address: <?php
							echo $customer->address ."  ";
							 ?> 
							</td>
						</tr>
						<tr>
							<td style="font-size:12px !important;">
							Tel: <?= $customer->phone?>
							</td>
						</tr>
					</tbody>
				</table>
				<br>	
			</div>

			<div class="col-sm-5 col-xs-5" style="text-align:left">
				<table >
					<tbody >
						<tr>
							<td style="font-size:12px !important;">
                                <p class="bcol" >NO : <b style="font-size:17px;"><?=$inv->reference_no;?></b></p>
							</td>
						</tr>
						<tr>
							<td style="font-size:12px !important;">
								Sale Order No: <?= $inv->so_no; ?>
							</td>
						</tr>
						<tr>
							<td style="font-size:12px !important;">
								Tel:<?= $biller->phone; ?>
							</td>
						</tr>
						<tr>
							<td style="margin-top:-10px !important;font-size: 13px;">E-mail: <?= $biller->email; ?>
							</td>
						</tr>	
						<tr>	
							<td style="margin-top:-10px !important;font-size: 13px;">
								Date :<?= $inv->date; ?>
							</td>
						</tr>
						</tr>
					</tbody>
				</table>
				<br>
			</div>
		 
		</div>		
				
		<div class="row">
			<div class=" col-sm-12">
				<table class="table table-bordered table-hover"border="1">
					<thead>
					<tr style="background-color:#ccc;">
						 <th class="text-center" >No</th>
						 <th class="text-center" >DESCRIPTION</th>
						 <th class="text-center" >QTY</th>
						 <th class="text-center" style="width: 25%;">UNIT PRICE</th>
						 <th class="text-center" >AMOUNT</th>
					</tr>	 
					</thead>
				<tbody>
					<?php 
					$no=1;
					$subtotal=0;
					foreach($rows as $row){
						$subtotal+=$row->subtotal;
						//$this->erp->print_arrays($row);
					?>
					<tr>
						<td style="text-align:center; vertical-align:middle;"><?php echo $no; ?></td>
						<td style="text-align:center; vertical-align:middle;"><?php echo $row->product_name ?></td>
						<td style="text-align:center; vertical-align:middle;"><?php echo $row->quantity ?></td>
						<td style="text-align:center; vertical-align:middle;"><?php echo $row->net_unit_price ?></td>
						<td style="text-align:center; vertical-align:middle;"><?php echo $row->subtotal ?></td>
					</tr>
					<?php $no++; } ?>
				</tbody>
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
				<tfoot>
					<tr >
                        <td colspan="3" rowspan="<?= $rows; ?>" style="border-left: 1px solid #FFF !important; border-bottom: 1px solid #FFF !important"><b>Notes :</b><br>
                            <?php
                            //$this->erp->print_arrays($row);
                            if ($inv->invoice_footer || $inv->invoice_footer != "") { ?>
                                <div style="font-size:10px; line-height:25px;">
                                    <div><?= $this->erp->decode_html($inv->invoice_footer); ?></div>
                                </div>
                                <?php
                            }
                            ?>
                        </td>
						<td style="vertical-align:middle;"><b>SUBTOTAL</b></td>
						<td style="vertical-align:middle;"><?php echo  "$ ".$this->erp->formatMoney($subtotal); ?></td>
					</tr>
                    <?php if($row->order_discount != 0){ ?>
                        <tr>
                            <td style="font-weight:bold;"><?= lang("DISCOUNT"); ?>
                            </td>
                            <td style="padding-right:10px;"><?= "$ ".$this->erp->formatMoney($row->order_discount); ?></td>
                        </tr>
                    <?php } ?>
                    <?php if($row->order_tax != 0){ ?>
                        <tr>
                            <td style="font-weight:bold;"><?= lang("ORDER TAX"); ?>
                            </td>
                            <td style="padding-right:10px;"><?= "$ ".$this->erp->formatMoney($row->order_tax); ?></td>
                        </tr>
                    <?php } ?>
                    <?php if($row->shipping != 0){ ?>
                        <tr>
                            <td style="font-weight:bold;"><?= lang("SHIPPING"); ?>
                            </td>
                            <td style="padding-right:10px;"><?= "$ ".$this->erp->formatMoney($row->shipping); ?></td>
                        </tr>
                    <?php } ?>
					<tr>
						<td style="vertical-align:middle;"><b>TOTAL AMOUNT</b></td>
						<td style="vertical-align:middle;"><?php echo  "$ ".$this->erp->formatMoney($row->grand_total);?></td>
					</tr>
                    <?php if($row->paid != 0){ ?>
                        <tr>
                            <td style="font-weight:bold;"><?= lang("PAID"); ?>
                            </td>
                            <td style="padding-right:10px;"><?= "$ ".$this->erp->formatMoney($row->paid); ?></td>
                        </tr>
                    <?php } ?>
                    <?php if($row->paid <= $row->grand_total || $row->paid ==0) { ?>
					<tr>
						<td style="vertical-align:middle;"><b>BALANCE</td>
						<td style="vertical-align:middle;">$ <?php echo $this->erp->formatMoney($row->grand_total-$row->paid) ;?></td>
					</tr>
                    <?php } elseif($row->paid > $row->grand_total){ ?>
                        <tr>
                            <td style="vertical-align:middle;"><b>BALANCE</td>
                            <td style="vertical-align:middle;"><?php echo  "$ "."0.00";?></td>
                        </tr>
                    <?php } ?>

					</tfoot>
						
			
					
			
				</table>
			</div>
		</div><!--div col sm 6 -->
		 
		 
		<div class="row">
			<div class="col-sm-3 col-xs-3"></div>
			<div class="col-sm-6 col-xs-6">
				<h6 style="text-align:center;">THANK YOU FOR YOUR SUPPORT!</h6>
			</div>
			<div class="col-sm-3 col-xs-3"></div>
		</div>
        <br>
		<div class="row"> 
				<div class="col-sm-4 col-xs-4">
					<center>
						<p style="font-size: 12px; margin-top: 4px !important;">CUSTOMER</p>
						<br><br>
						<p style="margin-top:-15px; font-size: 12px">....................................................</p>
					</center>
				</div>
				<div class="col-sm-4 col-xs-4">
					<center>
						<p style=" font-size: 12px; margin-top: 4px !important">DELIVER</p>
						<br><br>
						<p  style="margin-top:-15px; font-size: 12px">....................................................</p>
					</center>
				</div>
				<div class="col-sm-4 col-xs-4">
					<center>
						<p style="font-size: 12px; margin-top: 4px !important">SELLER</p>
						<br><br>
						<p style=" margin-top:-15px; font-size: 12px!important">.....................................................</p>
					</center>
				</div> 
		</div>
        <br>
        <div class="row">
            <div class="col-sm-5 col-xs-5">
                <p style="margin-top:-15px; font-size: 12px">**Good received in good condition</p>
            </div>
            <div class="col-sm-4 col-xs-4">
            </div>
            <div class="col-sm-3 col-xs-3"></div>
        </div>

		<div style="width: 821px;margin: 20px">
			<a class="btn btn-warning no-print" href="<?= site_url('sales'); ?>" style="border-radius: 0">
				<i class="fa fa-hand-o-left" aria-hidden="true"></i>&nbsp;<?= lang("back"); ?>
			</a>
		</div>
		
	
	</div>
</body>
</html>