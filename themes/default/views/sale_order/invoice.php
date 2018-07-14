<?php 
	//$this->erp->print_arrays($rows);
?>
<!Doctype html>
<html>
<head>
	<meta charset="UTF-8">
</head>
	<body>
	<style type="text/css">
	body {
			height: auto;
			width: 739px;
			/* to centre page on screen*/
			margin-left: auto;
			margin-right: auto;
		}
		#topleft{
			width: 40%;
			float:left;
			margin-top:5px;
			
		}
		#topright{
			width:25%;
			float:left;
			margin-top:65px;
		}
		#topcenter{
			width:34%;
			float:left;
			text-align:center;
			font-family:khmer OS Muol;
			font-size:20px;
		}
		#title{
			margin-left:100px;
		}
		#top2left{
			clear:both;
			float:left;
		}
		#top2right{
			float:right;
		}
		.tbStyle{
			width:739px;
			font-size:12px;
			font-weight:bold;
		}
		table{
			border-collapse: collapse;
		}
		table, th, td {
			border: 1px solid black;
		}
		#fleft{
			float:left;
			margin-left:30px;
			font-size:12px;
		}
		#fright{
			float:right;
			margin-right:50px;
			font-size:12px;
		}
	</style>
		
		<div style="width:100%;">
			<span id="topright">
				<strong>ការិយាល័យកណ្តាល</strong><br>
				<strong>Tell  :<?php echo $inv->customer_phone ?></strong>
			</span>
			<span id="topcenter">
				<strong>លិខិតកម៉្មង់ទំនិញ</strong>
			</span>
			<span id="topleft">
				<?php if ($Settings->system_management == 'project') { ?>
					<strong><?php echo $Settings->site_name ?></strong><br>
				<?php } else { ?>
					<strong><?php echo $inv->name ?></strong><br>
				<?php } ?>
				Add: <span><?php echo $inv->street .' | '. $inv->village ?></span>
					<span><?php echo $inv->sangkat .' | '. $inv->district ?></span>
					<span><?php echo $inv->city .' | '. $inv->country ?></span><br>
				Tel: <span><?php echo $inv->phone ?></span>
				Email: <span><?php echo $inv->email ?></span><br/>
				<strong>ឈ្មោះអតិថិជន:</strong> <span><?php echo $inv->customer_name ?></span><br>
				
			</span>
		</div>
		<div id="top2left">
			ដឹកទៅកាន់: <span style="font-weight:bold;"><strong><?php echo $inv->bill_to ?></strong></span>
		</div>
		<div id="top2right">
			លេខយោង:  <span><?php echo $inv->reference_no ?></span>   ថ្ងៃខែឆ្នាំ:  <span><?php echo $this->erp->hrsd($inv->date) ?></span>
		</div>
		<table class="tbStyle">
			<thead>
				<th>ល.រ</th>
				<th>លេខកូដ</th>
				<th>រាយមុខទំនិញ</th>
				<th>ចំនួន</th>
				<th>ឯកតា</th>
				<th>ផ្សេងៗ</th>
			</thead>
			<tbody align="center">
			<?php 
				$row_number = 1;
				$empty_row = 1;
			?>
			<?php 
			$str_unit = "";
			foreach ($rows as $row):
				if($row->option_id){
					$var = $this->sales_model->getVar($row->option_id);
					$str_unit = $var->name;
				}else{
					$str_unit = $row->product_unit;
				}
			?>
			<tr>
				<td class="text-center"><?= $row_number ?></td>
				<td><?= $row->product_code ?></td>
				<td><?= $row->product_name?></td>
				<td><?= $row->quantity ?></td>
				<td class="text-center"><?= $str_unit ?></td>
				<td class="text-center"><?= $row->product_noted ?></td>
			</tr>
			<?php
				$row_number++;
				$empty_row++;
			?>
			<?php endforeach ?>
			<?php
				if ($empty_row < 16) {
					$k=16 - $empty_row;
					for ($j = 1; $j <= $k; $j++) {
						echo  '<tr>
							<td class="text-center" height="34px">'.$row_number.'</td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>';
						$row_number++;
					}
				}
			?>
			</tbody>
		</table>
		<div style="font-size:12px">
			មិនសម្រាប់ប្រើប្រាស់ជាវិក័យប័ត្រអតិថិជនឡើយ
		</div>
		<div id="fleft">
			<strong>ស្នើរដោយ:</strong><br><br>
			
		</div>
		<div id="fright">
			<strong>បញ្ចេញដោយ:</strong><br><br>
			
		</div>
		<div class="print_d" style="clear:both;">
			<button onclick="print_document()">Print</button>
		</div>
	</body>
	<script type="text/javascript" src="<?= $assets ?>js/jquery.js"></script>
	<script>
		function print_document() {
			$('.print_d').hide();
			window.print();
		}
	</script>
</html>




















