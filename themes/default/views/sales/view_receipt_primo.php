<link href="<?= $assets ?>styles/helpers/bootstrap.min.css" rel="stylesheet"/>
<style>
        @media print{
			  table {
			  	margin-top: -10px;
			  }
		}
		#inform table tr td {
			font-size: 11px;
		}
</style>


 <div id ="box" style="max-width:100%;">
 	<div class="col-md-6 col-xs-12 col-md-offset-3">
 		<div class="row">
 			<div class="col-md-12 col-xs-12">
 				<div class="col-md-4 col-xs-4">
 					<?php if($biller->logo){?>
						<img src="<?= base_url() . 'assets/uploads/logos/' . $biller->logo; ?>" alt="<?= $biller->company; ?>" style="width: 87%;">
						<p style="font-weight: bold; text-align: center;"><?= $biller->phone;?></p>
	            	<?php }?>
 				</div>
 				<div class="col-md-4 col-xs-4 text-center">
 					<h3 style="font-size: 16px;"><?= lang("ប័ណ្ណទទួលប្រាក់"); ?></h3>
					<?= lang("Official Receipt"); ?>
 				</div>
 				<div class="col-md-4 col-xs-4" id="inform">
 					<table style="margin-top: 10px;">
 						<tr style="font-size: 13px;">
 							<td><?= lang("លេខ");?>/No</td>
 							<td>:</td>
 							<td><?= $payment->reference_no;?></td>
 						</tr>
 						<tr style="font-size: 13px;">
 							<td><?= strtoupper("ref");?></td>
 							<td>:</td>
 							<td><?= $inv->reference_no;?></td>
 						</tr>
 						<tr style="font-size: 13px;">
 							<td><?= lang("ថ្ងៃខែឆ្នាំ");?>/Date</td>
 							<td>:</td>
 							<td><?=$this->erp->hrld($payment->date);?></td>
 						</tr>
 					</table>
 				</div>
 			</div>
 		</div>
 		<div class="row">
 			<table class="table table-bordered" style="font-size: 13px;">
 				<tr>
 					<td style="width: 230px;"><?= lang("ទទួលពី");?> / Received from</td>
 					<td><?= $customer->company ? $customer->company : $customer->name; ?></td>
 				</tr>
 			</table>
 			<table class="table table-bordered" style="font-size: 13px;">
 				<tr>
 					<td rowspan="2" style="width: 230px; vertical-align: middle;"><?= lang("ប្រភេទចំណូល");?> / Kind of Income</td>
 					<td style="text-align: left;">.</td>
 				</tr>
 				<tr>
 					<td style="text-align: left;">.</td>
 				</tr>
 			</table>
 			<table style="font-size: 13px;">
 				<tr>
 					<td style="vertical-align: middle;">
 						<input type="checkbox" name="cash" id="cash"> 
 						<?= lang("សាច់ប្រាក់");?> / Cash
 						<input type="checkbox" name="cash" id="cash">
 						 <?= lang("សែក");?> / Cheque / ធនាគារ / Bank:.................លេខសែក: ............ 
 					</td>
 					<td style="border:1px solid #CCC; width: 206; vertical-align: middle; font-size: 10px;">
 						<?= lang("ប្រាក់ដុល្លារ");?> / USD &nbsp; <?=$this->erp->formatMoney($payment->amount);?>
 					</td>
 				</tr>
 				<tr>
 					<td>
 					</td>
 					<td style="border:1px solid #CCC; width: 206; vertical-align: middle; font-size: 10px;">
 						<?= lang("ប្រាក់រៀល");?> / RIEL &nbsp; <?=$this->erp->formatMoney($payment->amount* $exchange_rate_kh_c->rate);?>
 					</td>
 				</tr>
 			</table>
 			<br>
 			<table class="table table-bordered" style="font-size: 13px;">
 				<tr>
 					<td rowspan="2" style="width: 230px; vertical-align: middle;"><?= lang("ទឹកប្រាក់ជាអក្សរ");?> / The Sum of</td>
 					<td style="text-align: left;"><?=$this->erp->convert_number_to_words($payment->amount);?>
 					</td>
 				</tr>
 				<tr>
 					<td style="text-align: left;"><?=$this->erp->convert_number_to_words($payment->amount* $exchange_rate_kh_c->rate);?></td>
 				</tr>
 			</table>
 			<table class="table table-bordered" style="font-size: 13px;">
 				<thead>
 					<th style="text-align: center;"><?= lang("ពិនិត្យដោយ");?> / Approved by</th>
 					<th style="text-align: center;"><?= lang("ទទូលដោយ");?> / Received by</th>
 					<th style="text-align: center;"><?= lang("បង់ប្រាក់ដោយ");?> / Paid by</th>
 				</thead>
 				<tbody>
 					<td style="text-align: center;">
 						<br>
 						<br>
 						<br>
 						<br>
 						<?= lang("ហត្ថលេខា​ និង កាលបរិច្ឆេទ");?></br>Sign and Date
 					</td>
 					<td style="text-align: center;">
 						<br>
 						<br>
 						<br>
 						<br>
 						<?= lang("ហត្ថលេខា​ និង កាលបរិច្ឆេទ");?></br>Sign and Date
 					</td>
 					<td style="text-align: center;">
 						<br>
 						<br>
 						<br>
 						<br>
 						<?= lang("ហត្ថលេខា​ និង កាលបរិច្ឆេទ");?></br>Sign and Date
 					</td>
 				</tbody>
 			</table>
 		</div>
 	</div>
	<!-- <table class="table-responsive" width="48%" cellspacing="0" style="margin: 0 auto;">
				
	</table> -->
</div>
<script type="text/javascript">
 window.onload = function() { window.print(); }
</script>