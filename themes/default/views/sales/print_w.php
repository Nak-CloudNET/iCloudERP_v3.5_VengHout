
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $this->lang->line("sales") . " " . $inv->reference_no; ?></title>
    <link href="<?php echo $assets ?>styles/theme.css" rel="stylesheet">
    <style type="text/css">
		#table_w tr th{
			text-align:center;
			font-family:'Khmer Os';
			padding:5px;
		}
		#table_w tr td{
			font-family:'Khmer Os';
			padding:3px;
		}
		#table_h tr th,#table_h tr td{
			font-family:'Khmer Os';
		}
		h2,p{
			font-family:'Khmer Os';
		}
    </style>
</head>

<body>
	<div style="width:480px; margin:0 auto;">
		<button type="button" class="btn btn-xs btn-default no-print pull-right" style="margin-right:15px;" onclick="window.print();">
                <i class="fa fa-print"></i> <?= lang('print'); ?>
            </button>
		<div style="width:480px;">
			<h2><?=$biller->company?></h2>
			<p>អាស័យដ្ឋាន : <?=$biller->address?></p>
			<p>ទូរស័ព្ទ : <?=$biller->phone?></p>
			<p style="border-top:2px solid black;"></p>
			<table id="table_h">
				<tr>
					<td>អតិថិជន</td>
					<td>: <?=$customer->name?></td>
				</tr>
				<tr>
					<td>ទូរស័ព្ទ</td>
					<td>: <?=$customer->phone?></td>
				</tr>
				<tr>
					<td>កាលបរិច្ឆេទ</td>
					<td>: <?=$this->erp->hrsd($inv->date)?></td>
				</tr>
				<tr>
					<td>លេខបុង</td>
					<td>: <?=$inv->reference_no?></td>
				</tr>
			</table>
			<table id="table_w" border="1" border-collapse: collapse; style="width:480px;">
				<tr>
					<th>ល.រ</th>
					<th>មុខទំនិញ</th>
					<th>ចំនួន</th>
				</tr>
				<?php
				
				if(is_array($rows)){
					$i = 1;
					foreach($rows as $row){
						
						$datas = $this->sales_model->getInvoiceItemByID($id,$row->digital_id);
						foreach($datas as $data){
							if($data->option_id){
								$str = $data->vname;
							}else{
								$str = $data->uname;
							}
				?>
						<tr>
							<td class="text-center" style="width:60px;"><?=$i?></td>
							<td><?=$data->product_name?></td>
							<td class="text-right"><?=$this->erp->formatDecimal($data->quantity).' '.$str?></td>
						</tr>
				<?php
						$i++;
						}
					}
				}
				?>
			</table>
		</div>
		<div style="width:480px;">
			<div style="width:240px;float:left;text-align:center;">
				<br/>
				<p>អ្នកចេញទំនេញ</p>
			</div>
			<div style="width:240px;float:right;text-align:center;">
				<br/>
				<p>អ្នកបើកបរ</p>
			</div>
		</div>
	</div>
</body>
</html>