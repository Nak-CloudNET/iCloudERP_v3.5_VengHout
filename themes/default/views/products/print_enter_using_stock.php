<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $this->lang->line("enter_using_stock") ; ?></title>
    <link href="<?php echo $assets ?>styles/theme.css" rel="stylesheet">
    <style type="text/css">
        html, body {
            height: 100%;
            background: #FFF;
        }

        body:before, body:after {
            display: none !important;
        }

        .table th {
            text-align: center;
            padding: 5px;
        }

        .table td {
            padding: 4px;
        }
		hr{
			border-color: #333;
			width:100px;
			margin-top: 70px;
		}
    </style>
</head>

<body>
<div class="print_rec" id="wrap" style="width: 90%; margin: 40px auto;">
    <div class="row">
        <div class="col-lg-12">
            <div class="clearfix"></div>
				
			<div class="text-center" style="margin-bottom:20px;">
				<img src="<?= base_url() . 'assets/uploads/logos/logo.png'?>">
			</div>	
				
			<div class="row padding10">
                <div class="col-xs-4" style="float: left;font-size:14px">
				
                </div>
				<div class="col-xs-4" style="text-align:center;margin-top:-20px">
					<h4><b><?= lang("using_stock_issue_form"); ?></b></h4>
				</div>
                <div class="col-xs-4"  style="float: right;font-size:14px">
					
                </div>
            </div>
            <div class="row padding10">
                <div class="col-xs-4" style="float: left;font-size:14px">
					<p>Reference No: <b><?=$using_stock->reference_no; ?></b></p>
					<p>Date: <b><?=$using_stock->date; ?></b></p>
					<p>Location: <b><?=$using_stock->name; ?></b></p>
					<?php
						if(isset($using_stock->using_reference_no)){
						?>
						<p>From Using Reference: <b><?=$using_stock->using_reference_no; ?></b></p>
					<?php
						}					
					?>
					<p>Employee: <b><?=$using_stock->first_name.' '.$using_stock->last_name; ?></b></p>
				 </div>
				<div class="col-xs-4" style="text-align:center;margin-top:-20px">
				</div>
                <div class="col-xs-4"  style="float: right;font-size:14px">
					
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
			<div><br/></div>
            <div class="-table-responsive">
                <table class="table table-bordered table-hover table-striped" style="width: 100%;">
                    <thead  style="font-size: 13px;">
						<tr>
							<th><?= lang("no"); ?></th>
							<th><?= lang("item_code"); ?></th>
							<th><?= lang("category_expense"); ?></th>
							<th><?= lang("item_description"); ?></th>
							<th><?= lang("reason"); ?></th>
							<th><?= lang("quantity"); ?></th>
							<th><?= lang("unit"); ?></th>									
							<th><?= lang("cost"); ?></th>								
							<th><?= lang("total"); ?></th>								
						</tr>
                    </thead>
                    <tbody style="font-size: 13px;">
						<?php
						$i=1;
							foreach($stock_item as $si){
								echo '
									<tr>
										<td style="text-align:center;">'.$i.'</td>
										<td>'.$si->product_name.' ( '.$si->code.' ) </td>
										<td>'. $si->exp_cate_name .'</td>
										<td>'.$si->pname.'</td>
										<td>'.$si->rdescription.'</td>
										<td style="text-align:center;">'.number_format($si->qty_by_unit,0).'</td>
										<td>'.$si->unit_name.'</td>
										<td style="text-align:right;">'. ($si->option_id ? $si->cost * $si->variant_qty : $si->cost) .'</td>
										<td style="text-align:right;">'.$this->erp->formatMoney($si->qty_use * $si->cost).'</td>
									</tr>
								
								';
								$i++;
							}
						?>
							<tr>
								<td colspan="6"><span><b>Note : </b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo strip_tags($using_stock->note); ?></span></td>
								<td colspan="2" style="text-align:right"><b>Total Cost</b></td>
								<td  style="text-align:right"><b><?= $this->erp->formatMoney($using_stock->total_cost) ?></b></td>
							</tr>
                    </tfoot>
                </table>
            </div>

            <div class="row">
                <div class="col-md-6  pull-left" style="text-align:center">
					<hr/>
                    <p><b><?= lang("manager"); ?></b></p>
                </div>
				<div class="col-md-6 " style="text-align:center">
                    <hr/>
					<p><b><?= lang("stock_controller"); ?></b></p>
                </div>
            </div>
        </div>
		<div class="col-md-12">
			
		</div>
    </div>
</div>
<div id="mydiv" style="">
	
<div id="wrap" style="width: 90%; margin: 0 auto;">
    <div class="row">
        <div class="col-lg-12">
				<button type="button" class="btn btn-primary btn-default no-print pull-left" onclick="window.print();">
						<i class="fa fa-print"></i> <?= lang('print'); ?>
				</button>&nbsp;&nbsp;
			<a href="<?= site_url('products/view_enter_using_stock'); ?>"><button class="btn btn-warning no-print" ><i class="fa fa-backward "></i>&nbsp;<?= lang("back"); ?></button></a>

        </div>
    </div>
</div>
</div>
<br/><br/>
<div id="wrap" style="width: 90%; margin:0px auto;">
<div class="col-md-12" style="margin-bottom:20px;">
</div>
</div>
</body>
</html>