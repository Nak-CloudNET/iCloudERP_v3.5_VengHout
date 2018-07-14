<style type="text/css">
    @media print {
        #noprint {
            display: none !important;
        }
    }
</style>
<div class="modal-dialog modal-lg no-modal-header">
    <div class="modal-content">
        <div class="modal-body">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
			<button type="button" class="btn btn-xs btn-default no-print pull-right" style="margin-right:15px;" onclick="window.print();">
                <i class="fa fa-print"></i> <?= lang('print'); ?>
            </button>
          
                <div class="text-center" style="margin-bottom:20px; font-weight:bold;">
                    បញ្ជីកែតម្រូវស្តុកទំនិញ<br/>
                    Product Adjustment​ Item<!--(<?= $biller->company != '-' ? $biller->company : $biller->name; ?>)-->
                </div>
          
            <div class="table-responsive">
                <table class="table table-bordered">

                    <tbody>
						<?php 
						//foreach($header as $head){
						?>
						<tr>
							<td width="30%"><?php echo $this->lang->line("date"); ?></td>
							<td width="70%"><?php echo $this->erp->hrld($header->date); ?></td>
							
						</tr>
						<tr>
							<td><?php echo $this->lang->line("reference"); ?></td>
							<td ><?php echo $header->reference_no; ?></td>
						</tr>
						<tr>
							<td><?php echo $this->lang->line("warehouses"); ?></td>
							<td ><?php echo $header->wh_name; ?></td>
						</tr>
						<? 
							//}
						?>
                    </tbody>

                </table>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped" style="font-size:15px;">

                    <h3><?php echo $this->lang->line("items"); ?></h3>
                    <thead>
						<tr>
							<th><?= $this->lang->line("product_code") . " (" . $this->lang->line("product_name") . ")"; ?></th>
							<th class="col-md-2"><?= $this->lang->line("variant"); ?></th>
							<th class="col-md-1"><?= $this->lang->line("type"); ?></th>
							<th class="col-md-1"><?= $this->lang->line("quantity"); ?></th>
						</tr>
                    </thead>

                    <tbody>
					

                    <?php 
					$qoh=0;
					foreach ($items as $item){ 
					
						?>
							<tr>
								<td><?php echo $item->code ." ( ". $item->name .")";?></td>
								<td><?php echo $item->variants;?></td>
								<td><?php echo $item->type;?></td>
                                <td>
                                    <?php
                                    if ($item->type == "subtraction") {
                                        echo '(' . $this->erp->formatDecimal(abs($item->quantity)) . ')';
                                    } else {
                                        echo $this->erp->formatDecimal($item->quantity);
                                    }

                                    ?>
                                </td>
							</tr>
					<?php    
                    }
                    ?>
                    </tbody>
                </table>
            </div>
			<div class="row">
                <div class="col-xs-12">
                    <div class="well well-sm">
						  <p class="bold"><?= lang("note"); ?>:</p>
                           <div><?= strip_tags(html_entity_decode($item->note)); ?></div>
					</div>
				</div>
              
			</div>
            <div class="row">
                <div class="col-xs-4">
                    <p style="height:80px;font-weight:bold;text-align:center;"><?= lang("adjustment_by"); ?> </p>
                    <hr>
					<?php if(isset($items->created_by)){ ?>
						<p><?= $items->created_by?></p>
					<?php }?>
                    <p style="font-weight:bold;text-align:center;"><?= lang("stamp_sign"); ?></p>
                </div>
                
            </div>

            <!-- Footer buttons -->
           <!-- <div class="btn-group btn-group-justified" id="noprint">
                <div class="btn-group">
                    <a href="<?= site_url('sales/add/' . $delivery->id) ?>" class="tip btn btn-primary" title="<?= lang('create_sale') ?>">
                        <i class="fa fa-heart"></i>
                        <span class="hidden-sm hidden-xs"><?= lang('create_sale') ?></span>
                    </a>
                </div>

                <div class="btn-group">
                    <a href="<?= site_url('sale_order/delivery_invoice/' . $delivery->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('print_delivery_invoice') ?>">
                        <i class="fa fa-print"></i>
                        <span class="hidden-sm hidden-xs"><?= lang('print_delivery_invoice') ?></span>
                    </a>
                </div>
                
                <div class="btn-group">
                    <a href="<?= site_url('quotes/edit/' . $delivery->id) ?>" class="tip btn btn-warning sledit" title="<?= lang('edit') ?>">
                        <i class="fa fa-edit"></i>
                        <span class="hidden-sm hidden-xs"><?= lang('edit') ?></span>
                    </a>
                </div>
            </div>-->

        </div>
    </div>
</div>

