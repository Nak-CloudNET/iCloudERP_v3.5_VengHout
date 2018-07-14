<style>
	hr{
	border-color:#333;
	
	}
	@media print{
		.modal-content,.modal-body{
			border:none !important;
		}
		.bd{
			width:100% !important;
		}
		#hd th{
			background-color:#428BCA !important;
			color:white !important;
		}
	}
	#border tr th{
			border:solid 1px gray !important;
	 }
	 #border tr td{
			border:solid 1px gray !important;
	 }
</style>
<div class="modal-dialog modal-lg no-modal-header bd">
    <div class="modal-content">
        <div class="modal-body">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                <i class="fa fa-2x">&times;</i>
            </button>
            <button type="button" class="btn btn-xs btn-default no-print pull-right" style="margin-right:15px;" onclick="window.print();">
                <i class="fa fa-print"></i> <?= lang('print'); ?>
            </button>
			<!--<div class="text-center">
				<h1><?=lang('invoice')?></h1>
			</div>-->
            <?php
                if ($Settings->system_management == 'project') { ?>
                    <div class="text-center" style="margin-bottom:20px;">
                        <img src="<?= base_url() . 'assets/uploads/logos/' . $Settings->logo2; ?>"
                             alt="<?= $Settings->site_name != '-' ? $Settings->site_name : $Settings->site_name; ?>">
                    </div>
            <?php } else { ?>
                    <?php if ($logo) { ?>
                        <div class="text-center" style="margin-bottom:20px;">
                            <img src="<?= base_url() . 'assets/uploads/logos/' . $biller->logo; ?>"
                                 alt="<?= $biller->company != '-' ? $biller->company : $biller->name; ?>">
                        </div>
                    <?php } ?>
            <?php } ?>
             

            <div class="row" style="margin-bottom:15px;text-align:center">
                 <h2><?= lang("list_product_by_customer")?></h2>
               
            </div>

            <div class="table-responsive">
                <table id="border" border="1" width="100%" >

                    <thead> 
						<tr id ="hd" height="30px" style="background-color:#428BCA;color:white">
							<th class="text-center"><?= lang("no"); ?></th>  
							<th class="text-center"><?= lang("reference_no"); ?></th>
							<th class="text-center"><?= lang("product_code"); ?></th>
							<th class="text-center"><?= lang("product_name"); ?></th>
							<th class="text-center"><?= lang("customer"); ?></th>
							<th class="text-center"><?= lang("create_by"); ?></th>
                            <th class="text-center"><?= lang("quantity"); ?></th>  
                            <th class="text-center"><?= lang("cost"); ?></th>  
                            <th class="text-center"><?= lang("price"); ?></th>  
							<th class="text-center"><?= lang("profit"); ?></th>  
						</tr> 
                    </thead> 
                    <tbody> 
					    <?php 
						$r=1;
                        $total_qty =0;
                        $total_price =0;
                        $total_cost =0;
						$profit =0;
						  foreach($rows as $row){ 
						?>
                       <tr height="30px">
                             <td class="text-center"><?= $r ?></td>
							 <td class="text-center"><?= $row->reference_no?></td>
							 <td class="text-center"><?= $row->product_code?></td>
							 <td class="text-center"><?= $row->product_name ?></td>
							 <td class="text-center"><?= $row->customer?></td>
							 <td class="text-center"><?= $row->name?></td>
                             <td class="text-center"><?= $this->erp->formatQuantity($row->quantity) ?></td>
                             <td class="text-center"><?= $this->erp->formatMoney($row->unit_cost * $row->quantity) ?></td>
                             <td class="text-center"><?= $this->erp->formatMoney($row->unit_price * $row->quantity) ?></td>
							 <td class="text-center"><?= $this->erp->formatMoney(($row->unit_price - $row->unit_cost) * $row->quantity) ?></td>
                       </tr> 
					    <?php
                            $total_qty += $row->quantity;
                            $total_price += $row->unit_price * $row->quantity;
                            $total_cost += $row->unit_cost * $row->quantity;
                            $profit = $total_price - $total_cost;
                            

                            $r++; 
                            // $this->erp->print_arrays($this->erp->formatMoney($total_cost));
                        }
                        ?>
                    </tbody>
                    <tfoot> 
					   <tr height="30">
					        <td colspan="5"></td> 
							<td><b><?= lang("total")?></b></td>
                            <td class="text-center"><b><?= $this->erp->formatQuantity($total_qty) ?></b></td>
                            <td class="text-center"><b><?= $this->erp->formatMoney($total_cost) ?></b></td>
                            <td class="text-center"><b><?= $this->erp->formatMoney($total_price) ?></b></td>
							<td class="text-center"><b><?= $this->erp->formatMoney($profit) ?></b></td>
					   </tr>
                    </tfoot>
                </table>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <?php
                        if ($inv->note || $inv->note != "") { ?>
                            <div class="well well-sm">
                                <p class="bold"><?= lang("note"); ?>:</p>
                                <div><?= $this->erp->decode_html($inv->note); ?></div>
                            </div>
                        <?php
                        }
                        if ($inv->staff_note || $inv->staff_note != "") { ?>
                            <div class="well well-sm staff_note">
                                <p class="bold"><?= lang("staff_note"); ?>:</p>
                                <div><?= $this->erp->decode_html($inv->staff_note); ?></div>
                            </div>
                        <?php } ?>
                </div>
				<br/>
				
				<br/>
				 
                
            </div>
           
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready( function() {
        $('.tip').tooltip();
    });
</script>
