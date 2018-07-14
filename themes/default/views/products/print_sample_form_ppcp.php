<?php
    //$this->erp->print_arrays($stock_item);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $this->lang->line("enter_using_stock") ; ?></title>
    <link href="<?php echo $assets ?>styles/theme.css" rel="stylesheet">
    <style type="text/css">
        body {
		font-size: 14px !important;
		font-family:Times New Roman !important;
		font-family:Khmer OS Battambang !important;
	}
		
	.container {
		width: 29.7cm;
		margin: 20px auto;
		/*padding: 10px;*/
		box-shadow: 0 0 5px rgba(0, 0, 0, 0.5);
	}
	table > tbody > tr> td{
		font-size:14px;
		
	}
	@media print {

		.container {
			height: 27cm !important;
		}
		.customer_label {
			padding-left: 0 !important;
		}
		.container {
		height: 27cm !important;
		}
		.invoice_label {
			padding-left: 0 !important;
		}
		#footer{
			position:absolute !important;
   			bottom:0 !important;
		}
		.row table tr td {
			font-size: 10px !important;
		}
		
		.table thead > tr > th, .table tbody > tr > th, .table tfoot > tr > th {
			background-color: #444 !important;
			color: #FFF !important;
		}
		table > tbody > tr> td{
		font-size:20px;
		
		}
		table > thead > tr> th{
		font-size:50px;
		
		}
		.row .col-xs-7 table tr td, .col-sm-5 table tr td{
			font-size: 10px !important;
		}
		
		#note{
			max-width: 95% !important;
			margin: 0 auto !important;
			border-radius: 5px 5px 5px 5px !important;
			margin-left: 26px !important;
		}
		
		table th, td {
			font-size: 9px !important;
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
		font-size: 20px !important;
	}

	button {
		border-radius: 0 !important;
	}
	
        }
		

    </style>
</head>

<body>
	<div class="container" style="width: 830px;margin: 0 auto;">
		
			<div class="row">
				<div class="col-lg-12" style="width:810px !important;">
					<div class="clearfix"></div>
					<div class="row" style="margin-top: 20px;">
		
						<div class="col-sm-3 col-xs-3">
							<?php if(!empty($biller->logo)) { ?>
								<img src="<?= base_url() ?>assets/uploads/logos/<?= $biller->logo; ?>" style="width: 165px; margin-left: 25px;" />
							<?php } ?>
						</div>
			
						<div class="col-sm-6 col-xs-6 company_addr" style="margin-top:40px !important">
							<center>
								<?php if(!empty($biller->cf1)) { ?>
									<h3><?= $biller->cf1 ?></h3>
								<?php }else { ?>
									
								<?php } ?>
							
								<?php if(!empty($biller->vat_no)) { ?>
									<p style="font-size: 11px;">លេខអត្តសញ្ញាណកម្ម អតប (VAT No):&nbsp;<?= $biller->vat_no; ?></p>
								<?php } ?>
								
								<?php if(!empty($biller->address)) { ?>
									<p style="margin-top:-10px !important;font-size: 11px;">អាសយដ្ឋាន ៖ &nbsp;<?= $biller->address; ?></p>
								<?php } ?>
								
								<?php if(!empty($biller->phone)) { ?>
									<p style="margin-top:-10px !important;font-size: 11px;">ទូរស័ព្ទលេខ (Tel):&nbsp;<?= $biller->phone; ?></p>
								<?php } ?>
								
								<?php if(!empty($biller->email)) { ?>
									<p style="margin-top:-10px !important;font-size: 11px;">សារអេឡិចត្រូនិច (E-mail):&nbsp;<?= $biller->email; ?></p>
								<?php } ?>
							</center>
						</div>
						<div class="col-sm-3 col-xs-3">
							<button type="button" class="btn btn-xs btn-default no-print pull-right" style="margin-right:15px;" onclick="window.print();">
								<i class="fa fa-print"></i> <?= lang('print'); ?>
							</button>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12 col-sm-12 col-xs-12">
							<center><h2 style="font-family:Times New Roman !important; font-weight: bold !important;margin-top:-20px !important;">Request Sample </h2></center>
						</div>
					</div>
					<div class="clearfix"></div>	
					<div class="row" style="margin-top:20px !important;">
						<div class="col-lg-7 col-sm-7 col-xs-7" style="font-size:12px">
							<b><p style="font-size: 17px;"><?= lang('information');?></p></b>
							
							<table style="font-size: 13px !important;">
								<?php if(!empty($customer->company)) { ?>
								<tr>
									<td style="width: 7%; font-size:14px !important;"> Company Name</td>
									<td style="width: 5%;">:</td>
									<td style="width: 30%;font-size:14px !important;"><?= $customer->company ?></td>
								</tr>
								<?php } ?>
								<?php if(!empty($customer->name_kh || $customer->name)) { ?>
								<tr>
									<td style="width: 9%;font-size:14px !important;">Customer Name</td>
									<td>:</td>
									<?php if(!empty($customer->name_kh)) { ?>
									<td><?= $customer->name_kh ?></td>
									<?php }else { ?>
									<td>(<?= $customer->code ?>) <?= $customer->name ?></td>
									<?php } ?>
								</tr>
								<?php } ?>
								<?php if(!empty($customer->address_kh || $customer->address)) { ?>
								<tr>
									<td style="width: 9%;font-size:14px !important;">Address</td>
									<td>:</td>
									<?php if(!empty($customer->address_kh)) { ?>
									<td style="font-size:14px !important;"><?= $customer->address_kh?></td>
									<?php }else { ?>
									<td  style="font-size:14px !important;"><?= $customer->address ?></td>
									<?php } ?>
								</tr>
								<?php } ?>
								<?php if(!empty($customer->address_kh || $customer->address)) { ?>
								<tr>
									<td style="width: 9%;font-size:14px !important;">Tel</td>
									<td>:</td>
									<td style="width: 9%;font-size:14px !important;"><?= $customer->phone ?></td>
								</tr>
								<?php } ?>
								<?php if(!empty($customer->vat_no)) { ?>
								<tr>
									<td>លេខអត្តសញ្ញាណកម្ម អតប </br> VAT No.</td>
									<td>:</td>
									<td><?= $customer->vat_no ?></td>
								</tr>
								<?php } ?>
							</table>
						 </div>
						<div class="col-lg-5 col-sm-5 col-xs-5"  style="float:right !important;font-size:14px">
							<b><p style="font-size: 17px;"><?= lang('reference');?></p></b>
							<table>
							<tr>
								<td style="width: 20%;font-size:14px !important;">Reference No</td>
								<td style="width: 5%">:</td>
								<td style="font-size:14px !important;"><?=$using_stock->reference_no; ?></b></td>
							</tr>
							<tr>
								<td style="width: 20%;font-size:14px !important;">Date</td>
								<td style="width: 5%">:</td>
								<td style="width: 5%;font-size:14px !important;"><b><?=$this->erp->hrsd($using_stock->date); ?></b></td>
							</tr>
							<tr>
								<td  style="width: 20%;font-size:14px !important;">Requester Name</td>
								<td style="width: 5%">:</td>
								<td style="width: 5%;font-size:14px !important;"><?=$using_stock->first_name ." ".$using_stock->last_name; ?></td>
							</tr>
							<tr>
								<td style="width: 20%;font-size:14px !important;">Approved Name</td>
								<td style="width: 5%">:</td>
								<td style="width: 5%;font-size:14px !important;"><?=$au_info->username; ?></td>
							</tr>
							<tr>
								<td style="width: 20%;font-size:14px !important;">Warehouse</td>
								<td style="width: 5%">:</td>
								<td style="width: 5%;font-size:14px !important;"><?=$using_stock->name; ?></td>
							</tr>
							
							</table>
						</div>
					</div>
					</div>
					<div class="clearfix"></div>
					<div class="row padding10" style="display:none">
						<div class="col-xs-6" style="float: left;">
						   
						</div>
						<div class="col-xs-5" style="float: right;">
						  
						</div>
					</div>

					<div class="clearfix"></div>
					<div class="-table-responsive" style="margin: 10px">
						<table class="table table-bordered">
							<thead  style="background-color: #444 !important; color: #FFF !important;">
								<tr>
									<th class="text-center" style="font-size:14px !important;"><?= lang("no"); ?></th>
									<th class="text-center" style="font-size:14px !important; width:150px !important;"><?= lang("products_code"); ?></th>
									<th class="text-center" style="font-size:14px !important;"><?= lang("products_name"); ?></th>  
									<th class="text-center" style="font-size:14px !important;"><?= lang("expiry_date"); ?></th>
									<th class="text-center" style="font-size:14px !important;"><?= lang("unit"); ?></th>
									<th class="text-center" style="font-size:14px !important;width:100px !important;"><?= lang("quantity"); ?></th>   
									
								</tr>
							</thead>
							<tbody style="font-size: 13px;">
								<?php
								$i=1;
								$erow=1;
								$total = 0;
									foreach($stock_item as $si){
										echo '
											<tr>
												<td style="text-align:center;">'.$i.'</td>
												<td style="text-align:center;">'.$si->code.'</td>
												<td>'.$si->product_name.' </td>
												<td class='."text-center".'>'.$this->erp->hrsd($si->expiry).'</td>
												<td style="text-align:center;">'.$si->unit_name.'</td>
												<td style="text-align:center;">' . $this->erp->formatQuantity($si->qty_by_unit) . '</td>
												
											</tr>
										
										';
										$total += $si->qty_by_unit;
										$i++;
										$erow++;
										

									}
									
									if($erow < 13){
										$k=13 - $erow;
										for($j=1;$j<=$k;$j++){
											if($discount != 0) {
												echo  '<tr>
														<td height="34px" class="text-center">'.$i.'</td>
														<td></td>
														<td></td>
														<td></td>
														<td></td>
														<td></td>
														
													</tr>';
											}else {
												echo  '<tr>
														<td height="34px" class="text-center">'.$i.'</td>
														<td></td>
														<td></td>
														<td></td>
														<td></td>
														<td></td>
													</tr>';
											}
											$i++;
										}
									}
									
								?>
								
							</tbody>
						</table>
					</div>
					
						<?php if (!empty($using_stock->note)) { ?>
						<div class="not" style="width:98% !important;margin:auto;font-size:14px !important;border:1px solid black;border-radius:5px!important; padding:10px">
							<strong><u>Note:</u></strong> <?= strip_tags($using_stock->note); ?>		
						</div>			
						<?php } ?>
				</div>
					<div id="footer" class="row">
				
				<div class="col-sm-3 col-xs-3">
					<center>
						<p style="margin-bottom:65px !important;font-weight:bold !important; font-size: 13px !important">Requested By</P>
						<p>Name :......................</p>
						<p>Date :....../......../......</p>
					</center>
				</div>
				<div class="col-sm-3 col-xs-3">
					<center>
						<p style="margin-bottom:65px !important;font-weight:bold !important; font-size: 13px !important">Checked By</P>
						<p>Name :......................</p>
						<p>Date :....../......../......</p>
					</center>
				</div>
				<div class="col-sm-3 col-xs-3">
					<center>
						<p style="margin-bottom:65px !important;font-weight:bold !important; font-size: 13px !important">Verify By</P>
						<p>Name :......................</p>
						<p>Date :....../......../......</p>
					</center>
				</div>
				<div class="col-sm-3 col-xs-3">
					<center>
						<p style="margin-bottom:65px !important;font-weight:bold !important; font-size: 13px !important">Approved By</P>
						<p>Name :......................</p>
						<p>Date :....../......../......</p>
					</center>
				</div>
			</div>
	
			</div>
			
<div id="wrap" style="width: 90%; margin:0px auto;">
<div class="col-md-12" style="margin-bottom:20px;">
</div>
</div>

</script>
</body>
</html>