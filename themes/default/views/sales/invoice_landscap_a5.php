<link href="<?= $assets ?>styles/helpers/bootstrap.min.css" rel="stylesheet"/>
<style>
        @media print{
			  #box{
				  width:100% !important; 
				  margin:0px auto !important;
			  }
			  #pd{
				  padding-top:5px !important;
			  }
			 
		}
         #head-box tr td {
				  font-size:12px !important;
		 }
</style>


 <div id ="box" style="max-width:100%;margin:0px auto;">
 <?php for($i=0;$i<2;$i++){ ?>
	<table class="table-responsive" width="48%" border="0" cellspacing="0" style="float:left;margin-left:1.5%;">
		
		<tr>
			<td colspan="5" width="65%" align="center" style="padding-top:5px;">
				<div style="font-family:'Khmer OS Muol Light'; font-size:20px;"><?= lang("វិក័យប័ត្រ"); ?></div>
				<div style="font-family:'Arial'; font-size:20px;"><?= lang("invoice"); ?></div>
			</td>
		</tr>
		<tr>
			<td colspan="5" width="65%" align="center" style="padding-top:5px;">
				<table id="head-box" width="100%">
					<tr> 
					     <td width="22%"><?= lang('អតិថិជន / Customer');?></td>
						 <td width="38%">: <?= $customer->name ? $customer->name : $customer->company; ?> </td>
						 <td width="20%"><?= lang('លេខវិក្ក័យបត្រ / Invoice No');?></td>
						 <td width="20%">: <?= $inv->reference_no; ?></td>
					</tr>
					<tr>
					     <td width="22%"><?= lang('ឈ្មោះ​ក្រុមហ៊ុន ឬ អតិថិជន <br/> Company name / Customer');?> </td>
						 <td width="38%">: <?= $customer->company ? $customer->company : $customer->name; ?></td>
						 <td width="20%"><?= lang('កាលបរិច្ឆេទ <br/> Date');?></td>
						 <td width="20%">: <?= $this->erp->hrld($inv->date);?></td>	
					</tr>
					<tr>
						 <td width="22%"><?= lang('ទូរស័ព្ទ​លេខ / Telephone No');?>  </td>
						 <td width="38%">: <?= $customer->phone; ?></td>
						 <td width="20%"></td>
						 <td width="20%"></td>	
					</tr>
					<tr>
						
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="5">
				<div class="table-responsive">
					<table class="table table-bordered table-hover table-striped print-table order-table">

						<thead> 
						<tr>
							<th style="text-align:center;"><?= lang("ល-រ <br/> Nº"); ?></th>
							<th style="text-align:center;"><?= lang("បរិយាយមុខទំនិញ <br/> Description"); ?></th>
							<th style="text-align:center;"><?= lang("ខ្នាត <br/> Unit"); ?></th>
							<th style="text-align:center;"><?= lang("បរិមាណ <br/> Quantity"); ?></th>
							<th style="text-align:center;"><?= lang("ថ្លៃ​ឯកតា <br/> Unit_Price"); ?></th>
							<?php 
							if ($Settings->product_discount && $inv->total_discount != 0) {
								echo '<th style="text-align:center;">' . lang("បញ្ចុះតម្លៃឯកតា​ <br/> Discount") . '</th>';
							}
							?>
							<th style="text-align:center;"><?= lang("តម្លៃ <br/> Amount"); ?></th>
						</tr>

						</thead>

						<tbody>

						<?php 
						$r = 1;$n=0;
						foreach($rows as $row){
							if($row->discount){
								$n++;
							}
						}
						//echo $Settings->product_discount .'dd'. $inv->product_discount;exit;
						$tax_summary = array();
						foreach ($rows as $row):
						$free = lang('free');
						$product_unit = '';
						if($row->variant){
							$product_unit = $row->variant;
						}else{
							$product_unit = $row->unit;
						}
						
						$product_name_setting;
						if($pos->show_product_code == 0) {
							$product_name_setting = $row->product_name . ($row->variant ? ' (' . $row->variant . ')' : '');
						}else{
							$product_name_setting = $row->product_name . " (" . $row->product_code . ")" . ($row->variant ? ' (' . $row->variant . ')' : '');
						}
						?>
							<tr>
								<td style="text-align:center; width:40px; vertical-align:middle;"><?= $r; ?></td>
								<td style="vertical-align:middle;">
									<?= $product_name_setting ?>
									<?= $row->details ? '<br>' . $row->details : ''; ?>
									<?= $row->serial_no ? '<br>' . $row->serial_no : ''; ?>
								</td>
								<td style="width: 80px; text-align:center; vertical-align:middle;"><?php echo $product_unit ?></td>
								<td style="width: 80px; text-align:center; vertical-align:middle;"><?= $this->erp->formatQuantity($row->quantity); ?></td>
								<!-- <td style="text-align:right; width:100px;"><?= $this->erp->formatMoney($row->net_unit_price); ?></td> -->
								<td style="text-align:right; vertical-align:middle; width:100px;"><?= $row->subtotal!=0?$this->erp->formatMoney($row->unit_price):$free; ?></td>
								<?php
								 
								if ($Settings->product_discount && $n != 0) {
									echo '<td style="width: 100px; text-align:right; vertical-align:middle;">' . ('<small>(' . $row->discount . ')</small> ').$this->erp->formatNumber($row->item_discount) . '</td>';
								}
								?>
								<td style="text-align:right; vertical-align:middle; width:120px;"><?= $row->subtotal!=0?$this->erp->formatMoney($row->subtotal):$free; ?></td>
							</tr> 
							<?php 
							   
							$r++;
						endforeach;
						?>
						
						</tbody>
						<tfoot>
						     <?php 
								$col =3;
                                $tcol=1;							 
								if ($Settings->product_discount && $inv->total_discount != 0) {
									$col 	= $col+1; 
									$tcol 	= $tcol +1;
								}
							  else{
								  $col 	= $col;
								  $tcol = $tcol+1;
							  }
							  ?>
						     <?php if ($inv->order_discount != 0){?>
							    <tr> 
								   <td colspan="<?= $col;?>"></td> 
								   <td colspan="<?= $tcol;?>"><?= lang("បញ្ចុះតម្លៃ​រួម  <br/> Order_Discount"); ?></td> 
								   <td class="text-right"><?= $this->erp->formatMoney($inv->order_discount); ?></td>
                               </tr>
					       <?php } ?>
						     <tr> 
							   <td colspan="<?= $col;?>"></td> 
							   <td colspan="<?= $tcol; ?>"><?= lang("សរុបរួម <br/> Grand_Total"); ?></td> 
							   <td class="text-right"><?= $this->erp->formatMoney($inv->grand_total); ?></td>
                             </tr>
                             <?php if($inv->paid != 0) {?>
							 <tr> 
							    <td colspan="<?= $col;?>"></td> 
							    <td colspan="<?= $tcol; ?>"><?= lang("បានបង់​ <br/> Paid"); ?></td> 
							    <td class="text-right"><?= $this->erp->formatMoney($inv->paid); ?></td>
                             </tr>
							 <tr> 
							    <td colspan="<?= $col;?>"></td> 
							    <td colspan="<?= $tcol; ?>"><?= lang("សមតុល្យ​ទឹកប្រាក់ <br/> Balance"); ?></td> 
							    <td class="text-right"><?= $this->erp->formatMoney($inv->grand_total - $inv->paid); ?></td>
                             </tr
                             <?php }?>
						</tfoot>
					</table>
				</div>
			</td>
		</tr>
		<tr>
			<td colspan="5">
				<table border="0" cellspacing="0">
					<tr>
						<td id="pd" colspan="3" width="33%" valign="bottom" style="text-align:center;padding-top:20px;">
							<hr style="border:dotted 1px; width:160px; vertical-align:bottom !important; " />
							<b style="font-size:10px;text-align:center;margin-left:3px;"><?= lang('​ហត្ថលេខា និង ឈ្មោះ​អ្នក​ទិញ​  <br/> Customer`s Signature & Name'); ?></b>
						</td><td>&nbsp;</td><td>&nbsp;</td>
						<td colspan="3" width="33%" valign="bottom" style="text-align:center;padding-top:20px;">
							&nbsp;
						</td><td>&nbsp;</td><td>&nbsp;</td>
						<td colspan="3" width="33%" valign="bottom" style="text-align:center;padding-top:20px;">
							<hr style="border:dotted 1px; width:160px; vertical-align:bottom !important; " />
							<b style="font-size:10px;text-align:center;margin-left:3px;"><?= lang('​ហត្ថលេខា និង ឈ្មោះ​អ្នក​លក់​  <br/> Seller`s Signature & Name'); ?></b>
						</td> 
					</tr>						
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="5" style="font-size:12px; font-family:'Khmer OS'; padding-top:20px;">
				<b><u>សម្គាល់​</u> : </b> ច្បាប់​​ដើម​សម្រាប់​អ្នក​ទិញ​ ច្បាប់​ចម្លង​សម្រាប់​អ្ន​ក​លក់​។ <br/>
				<b><u>Note</u> : </b> Original invoice for customer copy invoice for seller. 
			</td>
		</tr>
	</table> 
 <?php } ?> 
</div>
<script type="text/javascript">
 window.onload = function() { window.print(); }
</script>