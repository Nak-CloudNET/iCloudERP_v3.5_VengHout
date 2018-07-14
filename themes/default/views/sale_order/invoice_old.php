
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
			float:left;
		}
		#topright{
			float:right;
			margin-top:20px;
			
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
		}
		#fright{
			float:right;
			margin-right:50px;
		}
	</style>
		<div class="print_d" style="float:right">
			<button onclick="print_document()">Print</button>
		</div>
		<div id="topleft">
		<img src="image/index.png" width="200px"height="100px"><br>
			ការិយាល័យកណ្តាល<br>
			Tell:<Strong style="margin-left:200px;">លិខិតកម៉្មង់ទំនិញ</strong>
		<br>
		
		</div>
		
		<div id="topright">
			<strong>Rasmei Mneang Seila Co.,Ltd</strong><br>
			Add: <span><?php echo $inv->street .' | '. $inv->village ?></span><br>
				<span><?php echo $inv->sangkat .' | '. $inv->district ?></span><br>
				<span><?php echo $inv->city .' | '. $inv->country ?></span><br>
			Tel: <span><?php echo $inv->phone ?></span>
			<br>
			Email: <span><?php echo $inv->email ?></span>  <br>
			<strong>ឈ្មោះអតិថិជន:</strong> <span><?php echo $inv->name ?></span><br>
			Nº:<br>
			
		</div>
		<div id="top2left">
			ដឺកទៅកាន់: <span><?php echo $inv->bill_to ?></span>
		</div>
		<div id="top2right">
			លេខយោង:  <span><?php echo $inv->reference_no ?></span>   ថ្ងៃខែឆ្នាំ:  <span><?php echo $inv->date ?></span>
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
				<?php for($i=0;$i<15;$i++){ ?>
					<tr>
						<td><?php echo $i; ?></td>
						<td><?php echo $rows[$i]['product_code']; ?></td>
						<td><?php echo $rows[$i]['product_name']; ?></td>
						<td><?php echo $rows[$i]['quantity']; ?></td>
						<td><span><?php echo $rows[$i]['package_name'] ?></span></td>
						<td><?php echo $rows[$i]['product_noted']; ?></td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
		<div style="font-size:13px">
			មិនសម្រាប់ប្រើប្រាស់ជាវិក័យប័ត្រអតិថិជនឡើយ
		</div>
		<div id="fleft">
			ស្នើរដោយ:<br><br>
			______________
		</div>
		<div id="fright">
			បញ្ចេញដោយ:<br><br>
			______________
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




















