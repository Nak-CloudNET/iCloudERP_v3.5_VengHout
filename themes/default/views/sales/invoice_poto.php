<meta charset="utf-8">
<style>
page[size="A5"] {
  width: 14.8cm;
  height: 21cm;
}
body{
margin:0 0 0 0;
    background-repeat: no-repeat;
}
table{
font-size:10px;
}
.info p{
position:absolute;
font-size:12px;
}
</style>

    <div  style="float:left; width:10cm">
		<table style="margin-top:210px;margin-left:37px;">
		<?php 
			$r = 1;
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
				<td style="width:0.5cm;padding-right:2px;"><?= $r; ?></td>
				<td style="width:3.85cm">
					<?= $product_name_setting ?>
					<?= $row->details ? '<br>' . $row->details : ''; ?>
					<?= $row->serial_no ? '<br>' . $row->serial_no : ''; ?>
				</td> 
				<td style="width:1.3cm;"><?= $this->erp->formatQuantity($row->quantity); ?></td>
				<td style="width:1.4cm;"><?= $row->subtotal!=0?$this->erp->formatMoney($row->unit_price):$free; ?></td>
				<td style="width:1.4cm;"><?= $row->subtotal!=0?$this->erp->formatMoney($row->subtotal):$free; ?></td>
				</tr>
			<?php
				$r++;
			endforeach;
			?>
		</table>
	</div>
	<div  style="float:left; width:10cm">
		<table style="margin-top:210px;margin-left:57px;">
			<?php 
			$r = 1;
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
				<td style="width:0.5cm;padding-right:2px;"><?= $r; ?></td>
				<td style="width:3.85cm">
					<?= $product_name_setting ?>
					<?= $row->details ? '<br>' . $row->details : ''; ?>
					<?= $row->serial_no ? '<br>' . $row->serial_no : ''; ?>
				</td> 
				<td style="width:1.3cm;"><?= $this->erp->formatQuantity($row->quantity); ?></td>
				<td style="width:1.4cm;"><?= $row->subtotal!=0?$this->erp->formatMoney($row->unit_price):$free; ?></td>
				<td style="width:1.4cm;"><?= $row->subtotal!=0?$this->erp->formatMoney($row->subtotal):$free; ?></td>
				</tr>
			<?php
				$r++;
			endforeach;
			?>
		</table>
	</div>
	
	
	<div class="info">
	<!-------------------- head --------------->
	<!---name_l--->
	<p class="name_l" style="top:148px;left:110px;"><?= $customer->name ? $customer->name : $customer->company; ?></p>
	<!---name_r--->
	<p class="name_r" style="top:148px;left:500px;"><?= $customer->name ? $customer->name : $customer->company; ?></p>
	
	<!---phone_l--->
	<p class="phone_l" style="top:164px;left:110px;"><?= $customer->phone; ?></p>
	<!---phone_r--->
	<p class="name_r"  style="top:164px;left:500px;"><?= $customer->phone; ?></p>
	
	<!---no_l--->
	<p class="no_l" style="top:147px;left:278px;"><?= $inv->reference_no; ?></p>
	<!---no_r--->
	<p class="no_r"  style="top:147px;left:670px;"><?= $inv->reference_no; ?></p>
	
	<!---belakor_l--->
	<p class="br_l" style="top:163px;left:280px;"><?= $cashier->username; ?></p>
	<!---belakor_r--->
	<p class="br_r"  style="top:163px;left:670px;"><?= $cashier->username; ?></p>
	
	
	
	<!-------------------- footer --------------->
	
	<!---date_l--->
	<p class="date_l" style="top:364px;left:92px;"><?= date('d/m/Y'); ?></p>
	<!---date_r--->
	<p class="date_r" style="top:364px;left:488px;"><?= date('d/m/Y'); ?></p>
	
	<!---r_date_l--->
	<p class="r_date_l" style="top:385px;left:92px;""><?= $this->erp->hrld($inv->date); ?></p>
	<!---r_date_r--->
	<p class="r_date_r" style="top:385px;left:488px;""><?= $this->erp->hrld($inv->date); ?></p>
	
	<!---total_l--->
	<p class="total_l" style="top:362px;left:278px;"><?= $this->erp->formatMoney($inv->grand_total); ?></p>
	<!---total_r--->
	<p class="total_r" style="top:362px;left:673px;"><?= $this->erp->formatMoney($inv->grand_total); ?></p>
	
	<!---bk_l--->
	<p class="bk_l" style="top:383px;left:278px;"><?= $this->erp->formatMoney($inv->paid); ?></p>
	<!---bk_r--->
	<p class="bk_r" style="top:383px;left:673px;"><?= $this->erp->formatMoney($inv->paid); ?></p>
	
	<!---bkv_l--->
	<p class="bkv_l" style="top:403px;left:278px;"><?= $this->erp->formatMoney($inv->grand_total - $inv->paid); ?></p>
	<!---bkv_r--->
	<p class="bkv_r" style="top:403px;left:673px;"><?= $this->erp->formatMoney($inv->grand_total - $inv->paid); ?></p>
	
	</div>
	
	
	
	
	
	