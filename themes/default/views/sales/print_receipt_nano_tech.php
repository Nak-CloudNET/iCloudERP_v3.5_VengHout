<?php
    //$this->erp->print_arrays($payments);
?>
<!doctype>
<html>
	<head>
		<title></title>
		<meta charset="utf-8">
		<link href="<?php echo $assets ?>styles/helpers/bootstrap.min.css" rel="stylesheet">
		<style>
			@media print{
				thead th {
					background: #CCC;
					font-size: 14px;
				}
                .bcol b {
                    color:red !important;

                }
				tbody tr td{
					font-size: 12px;
				}
				tfoot tr td{
					font-size: 12px;
				}
				.row #footer {
					font-size: 12px;
				}
			}
			
			thead th {
				background-color: #CCC;
			}
			img{
				margin-top:20px;
			}
			hr {
				border:solid 1px;
			}
            .bcol b {
                color:red;
            }
		</style>
	</head>
	<body>
	<div id="body">
	<div class="container">
		<div class="row">
				<div class="col-lg-12 col-xs-12">
					<div class="col-lg-4 col-xs-4">
						<?php if(!empty($biller->logo)) { ?>
							<img src="<?= base_url() ?>assets/uploads/logos/<?= $biller->logo; ?>" style="width: 165px; margin-left: 25px;" />
						<?php } ?>
					</div>
					<div class="col-lg-3 col-xs-3 text-center">
						<h2>RECEIPT</h2>
					</div>
					<div class="col-lg-5 col-xs-5" style="padding-left:30px;">
						<p class="bcol" style="padding-top:30px;font-size:17px;">NO <b>:</b> <b ><?=$payments->reference_no;?></b></p>
						<p style="font-size:17px;">DATE <b>:</b> <b><?=$this->erp->hrsd($payments->date);?></b></p>

					</div>
				</div>
		</div>
        <?php //$this->erp->print_arrays($invs); ?>
		<br>
		<div class="row">
			<?php 
						$i = 1;
						$stotal = 0;
						$unit = "";
						$sub_total = 0;
						$qty = 0;
						$col=5;
						if($inv->product_discount !=0){
							$col= $col+1;
						}
						if($inv->product_tax !=0){
							$col= $col+1;
						}
						foreach($rows as $row){
							if($row->option_id == 0 || $row->option_id==""){
								$unit = $row->unit_name;
								$qty = $row->quantity;
							}else{
								$unit = $row->variant_name;
								$qty = $row->quantity;
							}
							$sub_total += $qty*$row->unit_price;
						}
						?>
				<div class="col-lg-12 col-xs-12">
					<table style="width:100%">
						<tr>
							<td style="width:24%">RECEIVED FROM :</td>
							<td style="width:76%;padding-left:5px;line-height:2px;padding-top:20px;"><b><?=$customer->names; echo $customer;?><b/><hr></td>
						</tr>
						<tr>
							<td style="width:24%">FOR SUM OF DOLLARS :</td>
							<td style="width:76%;padding-left:5px;line-height:2px;padding-top:20px;"><b><?=$this->erp->convert_number_to_words($payments->amount);?></b><hr></td>
						</tr>
						<tr>
							<td style="width:24%">BEING PAYMENT OF :</td>
							<td style="width:76%;padding-left:5px;line-height:2px;padding-top:20px;"><b><?=$row->product_code.' - ';?>
								<strong><?=$row->pro_name; ?><b/><hr></td>
						</tr>
					</table>
				</div>
				
		</div>
		<br>
		<br>
		<div class="row">
				<div class="col-lg-6 col-xs-6">
					<table style="width:100%">
						<tr>
							<td style="width:50%">AMOUNT IN USD :</td>
							<td style="width:50%"><b><input type="text" class="text-center" value="$<?= $this->erp->formatMoney($payments->amount);?>" style="border:solid 1px;width:100%;" /></b></td>
						</tr>
						<tr>
							<td>CASH/CHEQUE NO :</td>
							<td><?= $payment->cheque_no?><hr></td>
						</tr>
						<tr style="width:100%; border:solid 1px;">
							<td style="width:100%; border:solid 1px;padding:10px;line-height:25px;" colspan="2" >Payment by Cheque to Rottna Leang 
							<br>ANZ Account Name
							<br>Name : Rottna Leang
							<br>Account number : 266 3 705</td>
						</tr>
					</table>
				</div>
				<div class="col-lg-6 col-xs-6">
					<table style="width:100%">
						<tr>
							<td class="text-center"colspan="2" style="width:100%"><b>RECEIVED BY</b></td>
						</tr>
						<tr>
							<td class="text-center" style="width:50%">Account</td>
							<td class="text-center" style="width:50%">Collector</td>
						</tr>
						<tr>
							<td class="text-center" style="width:20%"><br><br><br><br><br>..................................</td>
							<td class="text-center" style="width:20%"><br><br><br><br><br>...................................</td>
						</tr>
						<tr>
							<td style="width:20%;padding-left:70px;"><br>Date :</td>
							<td style="width:20%;padding-left:70px;"><br>Date :</td>
						</tr>
					</table>
				</div>
		</div>
</div>
</body>
</html>