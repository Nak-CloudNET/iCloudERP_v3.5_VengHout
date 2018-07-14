<!doctype>
<html>
	<head>
		<title>Invoice Quote</title>
		<meta charset="utf-8">
		<style>
			@media print{
			
				#tb tr th{
					background-color: #DCDCDC !important;
				}
				#body{
					width:1000px;
					height:100%;
					margin:0 auto;
					background:#fff !important;
				}
				#print{
					display:none;
				}
				#foot{
					width:100%;
					background:#fff !important;
				}	
				.fon{
					color: rgba(0, 0, 0, 0.3) !important;
				}
				.left_ch{
					 left: 80px !important;
				}
			}
			#print{
				
				width:60px;
				height:45px;
				border:0px;
				background: #4169E1;
				color:#fff;
				cursor:pointer;
				-webkit-box-shadow: 0px 4px 5px 0px rgba(0,0,0,0.75);
				-moz-box-shadow: 0px 4px 5px 0px rgba(0,0,0,0.75);
				box-shadow: 0px 4px 5px 0px rgba(0,0,0,0.75);
			}
			#body,h2,h3,h4,h5,p{
				margin:0;
				padding:5px;
				
			}
			.all{
				width:100%;
				margin:0 auto;
				
				height:100%;
			}
			
			#body{
				width:100%;
				height:100%;
				margin:0 auto;
				
				
				
			}		

		
			#top{
				width:95%;
				height:100px;
				margin:0 auto;
				
				padding-top:20px;
			}
			#top_l{
				width:220px;
				float:left;
			}
			#top_r{
				width:200px;
				float:right;
				text-align:center;
			}
			h1,h2,h3,h4{
				font-family:"Khmer OS Muol";
			}
			
			p{
				font-size:15px;
				font-family:"Arial Narrow";
			}
			#top2{
				width:95%;
				margin:0 auto;
				text-align:center;
				height:315px;
				
				margin-bottom:10px;
			}
			#top2_l{
				width:30%;
				margin:0 auto;
				text-align:center;
				
				float:left;
				height:150px;
			}
			#top2_c{
				width:30%;
				margin:0 auto;
				text-align:center;
				height:150px;
				
				float:left;
			}
			#top2_r{
				width:30%;
				margin:0 auto;
				text-align:center;
				
				float:left;
				height:210px;
			}
			#top2 h5{
				font-family:"Khmer OS Muol";
			}
			#tb tr th{
				font-size:16px;
				padding:5px;
				font-family:"Arial Narrow";
				 font-weight: bold;
			}
			#tb tr td{
					font-size:15px;
					padding:4px;
					font-family:"Arial Narrow";
				}
				#tb {
					width:100%;
					margin:0 auto;
				}
				#foot{
				width:100%;
				height:150px;
				background:#F0F8FF;	
			}		
			#tb2 tr td,#tb3 tr td{
				font-size:15px;
				border-radius:20px;
				font-family:"Arial Narrow";
				text-align:left;
				padding-left:10px;
			}
			.tal_left{
				font-size:15px;
				margin-left:530px;
				margin-top:-163px;
			}
			@media print {
                .no-print {
                    display: none;
                }
			}

		</style>
	</head>
	<body>
	<div class="all">
	
	<div id="body">
		
		<button type="button" class="btn btn-xs btn-default no-print pull-right" style="margin-right:15px;" onclick="window.print();">
                <i class="fa fa-print"></i> <?= lang('print'); ?>
            </button>
			
		<div id="top2">
			<?php
		        if ($Settings->system_management == 'project') { ?>
		            <div class="text-center" style="margin-bottom:20px;">
		                <img src="<?= base_url() . 'assets/uploads/logos/' . $Settings->logo2; ?>"
		                     alt="<?= $Settings->site_name; ?>">
		            </div>
		    <?php } else { ?>
		            <?php if ($logo) { ?>
		                <div class="text-center" style="margin-bottom:20px;">
		                    <img src="<?= base_url() . 'assets/uploads/logos/' . $biller->logo; ?>"
		                         alt="<?= $biller->company != '-' ? $biller->company : $biller->name; ?>">
		                </div>
		            <?php } ?>
		    <?php } ?>
		    <?php if ($Settings->system_management == 'project') { ?>
				<h3><?= $Settings->site_name; ?></h3>
			<?php } else { ?>
				<h3><?= $biller->company != '-' ? $biller->company : $biller->name; ?></h3>
				<?php } ?>
			<p><?= $biller->address?> <?= (isset($biller->contry)?$biller->contry:"");?></p>
			<p>(855) <?= $biller->phone?>  E-Mail <?= $biller->email?></p>
			<h4>QUOTATION</h4>
			<div style="float:left; width:100%; margin:0 auto;border-radius: 10px 10px 10px 10px;
-moz-border-radius: 10px 10px 10px 10px;
-webkit-border-radius: 10px 10px 10px 10px;
border: 1px solid #000000;">
			<table id="tb2" style=" width:100%;">
				<tr>
					<td style="font-family:'Khmer OS Muol Light'; font-size:14px;"> <?= lang(' To');?> </td>
					<td>: <?= $customer->company ? $customer->company : $customer->name; ?> </td>
				
					<td> <?= lang('Our Ref:');?> </td>
					<td style="padding-left:0px;">: <?= $inv->reference_no; ?> </td>
				</tr>
				<tr>
						<td style="font-family:'Khmer OS'; font-size:14px;"> <?= lang('Add');?> </td>
						<td>: <?= $customer->address ? $customer->address : $customer->address; ?>  </td>
						<td> <?= lang('Date:');?> </td>
						<td><?= $this->erp->hrld($inv->date); ?></td>
				</tr>
				<tr>
					<td style="font-family:'Khmer OS Muol Light'; font-size:14px;"> <?= lang('Att');?> </td>
					<td>: <?= $customer->name; ?> </td>
						<td> <?= lang('VAT No:');?> </td>
					<td> <?= $biller->vat_no; ?> </td>
					
				</tr>
				<tr>
					<td style="font-family:'Khmer OS Muol Light'; font-size:14px;"> <?= lang('Email:');?> </td>
					<td>: <?= $customer->email; ?> </td>
					
				
					
				</tr>
				<tr>
					<td style="font-family:'Khmer OS'; font-size:14px;"> <?= lang('Tel');?></td>
					<td>: <?= $customer->phone; ?></td>
				</tr>
				<tr>
					<td style="font-family:'Khmer OS'; font-size:14px;"> <?= lang('VAT');?></td>
					<td>: <?= $customer->vat_no; ?></td>
				</tr>
				<tr>
					<td style="font-family:'Khmer OS'; font-size:14px;"> <?= lang('Sub');?></td>
					<td>: </td>
				</tr>
				</table>
			</div>
			
		</div>
		<br/>
		<p><b>Dear</b>&nbsp;&nbsp;&nbsp;&nbsp; Sir/Madam<br/> We thank you for your enquiry and we are pleased to submit the following quote for your kind consideration</p><br/>
			
			<table id="tb" style="border-collapse: collapse;text-align:center;" border="1" >
			<thead>
				<tr>
					<th style="text-align:center;">No</th>
					<th style="text-align:center;">Illustrated Image</th>
					<th style="text-align:center;">Descriptions</th>
					<th style="text-align:center;">Qty</th>
					<th style="text-align:center;">Unit Price</th>
					<?php
                        if ($Settings->product_discount) {
                            echo '<th>' . lang("discount") . '</th>';
                        }
                        if ($Settings->tax1) {
                            echo '<th>' . lang("tax") . '</th>';
                        }
                        ?>
                    <th style="text-align:center;">Total Amount</th>
				</tr>
			</thead>
			
			<tbody>
				<?php $r = 1;
                    $tax_summary = array();
					$tt = 0;
                    foreach ($rows as $row):
                    ?>
				<tr>
					<td><?= $r; ?></td>
					<td><img class="img-rounded img-thumbnail" style="width:60px;height:60px;" src="<?= base_url() . 'assets/uploads/' . $row->image; ?>"></td>
					<td style="text-align: left !important;"><?= $row->product_name . " (" . $row->product_code . ")" . ($row->variant ? ' (' . $row->variant . ')' : ''); ?>
                                <?= $row->details ? '<br>' . $row->details : ''; ?><br/></td>
					<td><?= $this->erp->formatQuantity($row->quantity); ?></td>
					<td style="text-align: right !important;">$<?= $this->erp->formatMoney($row->unit_price); ?></td>
					<?php
                            if ($Settings->product_discount) {
                                echo '<td style="width: 100px; text-align:right; vertical-align:middle;">' . ($row->discount != 0 ? '<small>(' . $row->discount . ')</small> ' : '') . $this->erp->formatMoney($row->item_discount) . '</td>';
                            }
                            if ($Settings->tax1) {
                                echo '<td style="width: 100px; text-align:right; vertical-align:middle;">' . ($row->item_tax != 0 && $row->tax_code ? '<small>('.$row->tax_code.')</small>' : '') . ' ' . $this->erp->formatMoney($row->item_tax) . '</td>';
                            }
                            ?>
                    <td style="text-align: right !important;">$<?= $this->erp->formatMoney($row->quantity*$row->net_unit_price); ?></td>
					
					
				</tr>
				<?php
                        $r++;
						$tt+=$row->quantity*$row->net_unit_price;
                    endforeach;
                    ?>
			</tbody>

			<tfoot>
				<?php
				$col = 5;
				if ($Settings->product_discount) {
					$col++;
				}
				if ($Settings->tax1) {
					$col++;
				}
				if ($Settings->product_discount && $inv->product_discount != 0 && $Settings->tax1) {
					$tcol = $col - 2;
				} elseif ($Settings->product_discount && $inv->product_discount != 0) {
					$tcol = $col - 1;
				} elseif ($Settings->tax1) {
					$tcol = $col - 1;
				} else {
					$tcol = $col;
				}
				?>
				<?php if ($inv->grand_total != $inv->total) { ?>
					<tr>
						<td colspan="<?= $tcol; ?>"
							style="text-align:right; padding-right:10px;"><?= lang("total"); ?>
						</td>
						<?php
						if ($Settings->product_discount && $inv->product_discount != 0) {
							echo '<td style="text-align:right;">' . $this->erp->formatMoney($inv->product_discount) . '</td>';
						}
						if ($Settings->tax1) {
							echo '<td style="text-align:right;">' . $this->erp->formatMoney($inv->product_tax) . '</td>';
						}
						?>
						<td style="text-align:right; padding-right:10px;"><?= $this->erp->formatMoney($inv->total); ?></td>
					</tr>
				<?php } ?>

				<?php if ($inv->order_discount != 0) {
					echo '<tr><td colspan="' . $col . '" style="text-align:right; padding-right:10px;;">' . lang("order_discount") . '</td><td style="text-align:right; padding-right:10px;">' . $this->erp->formatMoney($inv->order_discount) . '</td></tr>';
				}
				?>
				<?php if ($inv->shipping != 0) {
					echo '<tr><td colspan="' . $col . '" style="text-align:right; padding-right:10px;;">' . lang("shipping") . '</td><td style="text-align:right; padding-right:10px;">' . $this->erp->formatMoney($inv->shipping) . '</td></tr>';
				}
				?>
				<?php if ($Settings->tax2 && $inv->order_tax != 0) {
					echo '<tr><td colspan="' . $col . '" style="text-align:right; padding-right:10px;">' . lang("order_tax") . '</td><td style="text-align:right; padding-right:10px;">' . $this->erp->formatMoney($inv->order_tax) . '</td></tr>';
				}
				?>
				
				<tr>
					<td colspan="<?= $col; ?>"
						style="text-align:right; font-weight:bold;"><?= lang("total_amount"); ?>
					</td>
					<td style="text-align:right; padding-right:10px; font-weight:bold;"><?= $this->erp->formatMoney($inv->grand_total - (isset($deposit->deposit_amount)?$deposit->deposit_amount:0)); ?></td>
				</tr>
			</tfoot>
			
			</table>
				
			
		
		</div>
		
	</div>	
	</body>
</html>