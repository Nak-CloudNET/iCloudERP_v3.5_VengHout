<?php //$this->erp->print_arrays($quote_items);?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $this->lang->line("invoice_purchase_request") . " " . $inv->reference_no; ?></title>
    <link href="<?php echo $assets ?>styles/theme.css" rel="stylesheet">
    <style type="text/css">
		.container {
			width: 29.7cm;
			margin: 20px auto;
			/*padding: 10px;*/
			box-shadow: 0 0 5px rgba(0, 0, 0, 0.5);
		}
		.table thead > tr > th, .table tbody > tr > th, .table tfoot > tr > th, .table thead > tr > td, .table tbody > tr > td, .table tfoot > tr > td {
		border: 1px solid #000 !important;
	}
		.supinfo {
			line-height: 0.9 !important;
		}
		#shopinfo p {
			font-size: 12px;
			line-height: 0.7;
		}
		@media print {
			.container {
				width: 98% !important;
				height: 29cm !important;
				margin: 0 auto !important;
			}
			
			#footer {
				position: absolute !important;
				bottom: 0 !important;
			}
		}
		.customer_label {
			padding-left: 0 !important;
			
		
		}
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
	
	@media print {
		.customer_label {
			padding-left: 0 !important;
		}
		
		.invoice_label {
			padding-left: 0 !important;
		}
		#footer  hr p {
			font-size: 1px !important;
			position:absolute !important;
   			bottom:0 !important;
   			/*margin-top: -30px !important;*/
		}
		.row table tr td {
			font-size: 10px !important;
		}
		/*.row table tr th {
			font-size: 8px !important;
		}*/
		.table thead > tr > th, .table tbody > tr > th, .table tfoot > tr > th {
			background-color: #444 !important;
			color: #FFF !important;
		}
		footer {page-break-after: always;}
		.row .col-xs-7 table tr td, .col-sm-5 table tr td{
			font-size: 10px !important;
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
	
</style>
		
		
		
    </style>
</head>

<body>
<div class="container" style="width: 821px;margin: 0 auto;">
	<button type="button" class="btn btn-xs btn-default no-print pull-right" style="margin:5px; border-radius:0" onclick="window.print();">
		<i class="fa fa-print"></i> <?= lang('print'); ?>
	</button>
    <div class="row">
            <?php if ($logo) { ?>
				<div class="col-xs-3 col-sm-3 text-center" style="margin-top:5px;">
                    <img src="<?= base_url() . 'assets/uploads/logos/' . $billers->logo; ?>"
                         alt="" width="180px">
                </div>
                <div class="col-xs-9 col-sm-9" id="shopinfo">
                  
					<h3 style=" font-family:Times New Roman !important;font-weight:bold !important;margin-left:50px !important;"><?= $billers->company;?></h3>
				  
                    <p style="margin-left:-50px !important;"><?= $billers->address ?></p>
                    <P>
                        <?php 
                            if($billers->phone){
                                echo lang("tell")."&nbsp;&nbsp;:&nbsp;&nbsp;".$billers->phone.",";
                            }
                            if ($billers->email) {
                                echo lang("email")."&nbsp;&nbsp;:&nbsp;&nbsp;".$billers->email;
                            }
                        ?>
                    </p>
                </div>
                
            <?php } ?>
	</div>
	
					<h4 style="font-family:Times New Roman !important;font-weight:bold !important; text-align:center"><?= lang("PURCHASE REQUESE")?></h4>
            <br>
			<div class="col-lg-12">
            <div class="row padding10">
                <div class="col-xs-8" style="float: left;font-size:13px">
                  
                    <table class="supinfo">
                        <tr>
                            <td>
                                <p><?= lang("name");?></p>
                            </td>
                            <td>
                               <p>&nbsp;:&nbsp;</p>
                            </td>
                            <td>
                                <p><?=$supplier->name?></p>
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
                                <p><?=$supplier->address?></p>
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
                                <p><?=$supplier->phone?></p>
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
                                <p><?=$supplier->email?></p>
                            </td>
                        </tr>
                    </table>
                    <div class="clearfix"></div>
                </div>
               
                <div class="col-xs-4"  style="float: right;font-size:13px">
                   
                    <table class="supinfo">
                        <tr>
                            <td>
                                <p><?= lang("pr_no"); ?></p>
                            </td>
                            <td>
                                <p>&nbsp;:&nbsp;</p>
                            </td>
                            <td>
                                <p><?=$warehouse->reference_no?></p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p><?= lang("pr_date"); ?></p>
                            </td>
                            <td>
                                <p>&nbsp;:&nbsp;</p>
                            </td>
                            <td>
                                <p><?=$warehouse->date."</b>";?></p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p><?= lang("delivery"); ?></p>
                            </td>
                            <td>
                                <p>&nbsp;:&nbsp;</p>
                            </td>
                            <td>
                                <p><?=$warehouse->name?></p>
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
			<div class="row">
            <div class="col-sm-12 col-xs-12">
                <table class="table table-bordered" style="width: 100%;">
                    <tbody style="font-size: 13px;">
					
					<tr class="thead" style="font-size: 13px;background-color: #444 !important; color: #FFF !important;">
							<th><?= lang("no"); ?></th>
                            <th style="width:150px !important;"><?= lang("description"); ?></th> 
                            <th><?= lang("unit"); ?></th> 
                            <th><?= lang("quantity"); ?></th>
                            <?php
                                if ($inv->status == 'partial') {
                                    echo '<th>'.lang("received").'</th>';
                                }
                            ?> 
                            <?php if($Owner || $Admin || $GP['purchase_request-cost']) {?>
                                <th><?= lang("unit_cost"); ?></th>
                            <?php } ?>
                            
                            <?php
                            if ($Settings->tax1) {
                                echo '<th>' . lang("tax") . '</th>';
                            }
                            if ($Settings->product_discount) {
                                echo '<th>' . lang("discount") . '</th>';
                            }
                            ?>
                            <th><?= lang("subtotal"); ?></th>
							<th><?= lang("remark"); ?></th>
					</tr>
					
                        <?php $r = 1;
                        $tax_summary = array();
                        foreach ($rows as $row):
                        ?>
                            <tr>
                                <td style="text-align:center; width:40px; vertical-align:middle;"><?= $r; ?></td>
                                <td style="vertical-align:middle;">
                                    <?= $row->product_name. " (" . $row->product_code . ")";?>
                                    <?= $row->details ? '<br>' . $row->details : ''; ?>
                                    <?= ($row->expiry && $row->expiry != '0000-00-00') ? '<br>' . $this->erp->hrsd($row->expiry) : ''; ?>
                                </td> 
                                <td style="vertical-align: middle; text-align: center;">
                                    <?php if($row->variant){ echo $row->variant;}else{echo $row->pro_unit;}?>
                                </td>
                                <td style="width: 80px; text-align:center; vertical-align:middle;"><?= $this->erp->formatQuantity($row->quantity); ?></td>
                                <?php
                                if ($inv->status == 'partial') {
                                    echo '<td style="text-align:center;vertical-align:middle;width:80px;">'.$this->erp->formatQuantity($row->quantity_received).'</td>';
                                }
                                ?>
                                <?php if($Owner || $Admin || $GP['purchase_request-cost']) {?>
                                    <td style="text-align:right; width:100px; vertical-align: middle;"><?= $this->erp->formatMoney($row->unit_cost); ?></td>
                                <?php } ?>
                                <?php
                                if ($Settings->tax1) {
                                    echo '<td style="width: 100px; text-align:right; vertical-align:middle;">' . ($row->item_tax != 0 && $row->tax_code ? '<small>('.$row->tax_code.')</small>' : '') . ' ' . $this->erp->formatMoney($row->item_tax) . '</td>';
                                }
                                if ($Settings->product_discount) {
                                    echo '<td style="width: 100px; text-align:right; vertical-align:middle;">' . ($row->discount != 0 ? '<small>(' . $row->discount . ')</small> ' : '') . $this->erp->formatMoney($row->item_discount) . '</td>';
                                }
                                ?>
                                <td style="text-align:right; width:120px; vertical-align: middle;"><?= $this->erp->formatMoney($row->subtotal); ?></td>
								<td style="text-align:right; width:120px; vertical-align: middle;">
								<?= $row->note; ?></td>
                            </tr>
                            <?php
                            $r++;
							$no++;
                        endforeach;
                        ?>
						<?php
								if($no<14){
									$k=14 - $no;
									for($j=1;$j<=$k;$j++){
										if($discount != 0) {
											echo  '<tr>
													<td height="34px"></td>
													<td></td>
													<td></td>
													<td></td>
													<td></td>
													<td></td>
													<td></td>
													<td></td>
													<td></td>
													<td></td>
												</tr>';
										}else {
											echo  '<tr>
													<td height="34px"></td>
													<td></td>
													<td></td>
													<td></td>
													<td></td>
													<td></td>
													<td></td>
													<td></td>
													<td></td>
												</tr>';
										}
										
									}
								}
							?>
                    </tbody>
                    <tfoot style="font-size: 13px;">
                        <?php
                            $col = 4;
                            if($Owner || $Admin || $GP['purchase_request-cost']) {
                                $col = 4;
                            } else {
                                $col = 3;
                            }
                            
                            if ($Settings->product_discount) {
                                $col++;
                            }
                            if ($Settings->tax1) {
                                $col++;
                            }
                        ?>

                        <tr>
                            <td colspan="<?= $col; ?>" style="border-left:1px solid #fff !important;border-bottom:1px solid #fff !important;">
                                <p style="text-align:left; font-weight:bold;"><?= lang("note"); ?>:<?= $this->erp->decode_html($inv->note); ?></p>
                            </td>
                            <td
                                style="text-align:right; padding-right:10px; font-weight:bold; vertical-align: middle;"><?= lang("total"); ?>
                                (<?= $default_currency->code; ?>)
                            </td>
                            <td style="text-align:right; padding-right:10px; font-weight:bold; vertical-align: middle;"><?= $this->erp->formatMoney($inv->grand_total); ?></td>
							 <td style="text-align:right; padding-right:10px; font-weight:bold; vertical-align: middle;"></td>
                        </tr>

                    </tfoot>
                </table>
            </div>
        </div>
    </div>
	<div class="row" id="footer">    
		<div class="col-lg-4 col-xs-4 col-sm-4 pull-left">
			<p class="bold"><?= lang("request_by"); ?></p>
			<p>&nbsp;</p>
			<p>&nbsp;</p>
			<p style="border-bottom: 1px solid #666;">&nbsp;</p>
			<p><?= lang("name"); ?> :  ...................................</p>
			<p><?= lang("date"); ?> :  ......../........./.................</p>
		</div>
		<div class="col-lg-4 col-xs-4 col-sm-4 pull-left">
			<p class="bold"><?= lang("checked_by"); ?></p>
			<p>&nbsp;</p>
			<p>&nbsp;</p>
			<p style="border-bottom: 1px solid #666;">&nbsp;</p>
			<p><?= lang("name"); ?> :  ...................................</p>
			<p><?= lang("date"); ?> :  ......../........./.................</p>
		</div>
		<div class="col-lg-4 col-xs-4 col-sm-4 pull-right">
			<p class="bold"><?= lang("approve_by"); ?></p>
			<p>&nbsp;</p>
			<p>&nbsp;</p>
			<p style="border-bottom: 1px solid #666;">&nbsp;</p>
			<p><?= lang("name"); ?> :  ...................................</p>
			<p><?= lang("date"); ?> :  ......../........./.................</p>
		</div>
	</div>
</div>
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
