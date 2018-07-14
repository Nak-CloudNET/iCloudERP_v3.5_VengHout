<?php
	//$this->erp->print_arrays($digitalDatas);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $this->lang->line("sales") . " " . $inv->reference_no; ?></title>
    <link href="<?php echo $assets ?>styles/theme.css" rel="stylesheet">
    <style type="text/css">
		@media print {
		    .wrapper {
				width:570px;border:1px solid black;
			}
		}
		.wrapper {
			width:570px;border:1px solid black;
		}
    </style>
</head>

<body>

	<div class ="wrapper">

		<button type="button" class="btn btn-xs btn-default no-print pull-right" style="margin-right:15px;" onclick="window.print();">
                <i class="fa fa-print"></i> <?= lang('print'); ?>
            </button>
		<div style="width:570px;height:80px;">
			
		</div>
		<div style="width:570px;height:150px;">
			<div style="width:200px;float:right;">
				<p style="padding-left:60px;"><?=$inv->reference_no?></p>
				<p style="padding-left:23px;"><?=$customer->name?></p>
				<p style="padding-left:0px;line-height:12px;"><?=$customer->phone?></p>
				<p style="padding-left:20px;line-height:12px;"><?=$customer->city?></p>
			</div>
		</div>
		<div style="width:570px;">
			
			<div>
				<table style="border:1px solid black;width:100%;">
					<?php foreach($digitalDatas as $digitalData){ ?>
						<tr style="height:35px;">
							<th style="border:1px solid black;width:255px;text-align:center;"><?php echo $digitalData->name; ?></th>
							<th style="border:1px solid black;width:100px;text-align:center;"><?php echo $this->erp->formatDecimal($digitalData->quantity); ?></th>
							<th style="border:1px solid black;width:100px;"><?php echo $digitalData->price != 0? $this->erp->formatMoney($digitalData->price):"Free"; ?></th>
							<th style="border:1px solid black;width:100px;"><?php echo $digitalData->price != 0? $this->erp->formatMoney($digitalData->price * $digitalData->quantity):"Free"; ?></th>
						</tr>
						
					<?php } ?>
					
				</table>
			</div>
			
		</div>
		<div style="width:570px;">
			<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
			<div style="width:500px;margin-left:75px;padding-top:0px;">
			<table  id="table_h" border="0" border-collapse: collapse; style="width:500px;">
				<tr>
					<th style="width:200px;"></th>
					<th style="width:80px;"></th>
					<th style="width:100px;"></th>
					<th style="width:120px;"> </th>
				</tr>
				
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td><?=$this->erp->formatMoney($inv->grand_total)?></td>
				</tr>
			</table>
			<p style="margin-left:280px;margin-top:10px;"><span><?=$this->erp->KhmerNumDate(date('d', strtotime(urldecode($inv->date))));?></span><span style="padding-left:50px;"><?=$this->erp->KhmerMonth(date('m', strtotime(urldecode($inv->date))));?></span><span style="padding-left:60px;"><?=$this->erp->KhmerNumDate(date('Y', strtotime(urldecode($inv->date))));?></span></p>
			</div>
			
		</div>
	</div>
</body>
</html>