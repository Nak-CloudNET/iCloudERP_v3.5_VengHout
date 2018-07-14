<script>
    $(document).ready(function() {
		$('#TCustomer').DataTable({
			"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?=lang('all')?>"]],
		    
		}).fnSetFilteringDelay().dtFilter([
            {column_number: 0, filter_default_label: "[Date]", filter_type: "text", data: []},
            {column_number: 1, filter_default_label: "[Discription]", filter_type: "text", data: []},
        ], "footer");
	
		var val = 0;
		$('.Tquantity').each(function(){
			val += parseFloat($(this).text());
		});
		var val1=0.00;
		$('.Tprice').each(function(){
			val1 += parseFloat($(this).text());
		});
		 
		$('#getQuantity').text(val);
		var getp =val1.toFixed(3)
		$('#getPrice').text(getp);
	});
</script>
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header"> 
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                <i class="fa fa-2x">&times;</i>
            </button>
			<button type="button" class="btn btn-primary btn-xs no-print pull-right " onclick="window.print()">
				<i class="fa fa-print"></i>&nbsp;<?= lang("print"); ?>
			</button>
            <h4 class="modal-title" id="myModalLabel"><?= $customer->company && $customer->company != '-' ? $customer->company : $customer->name; ?></h4>
        </div>
        <div class="modal-body">
			<table class="table table-striped table-bordered" style="margin-bottom:0;">
                    <tbody>
                    <tr>
                        <td><strong><?= lang("company"); ?></strong></td>
                        <td><?= $customer->company; ?></td>
                    </tr>
                    <tr>
                        <td><strong><?= lang("name"); ?></strong></td>
                        <td><?= $customer->name; ?></td>
                    </tr>
                    <tr>
                        <td><strong><?= lang("customer_group"); ?></strong></td>
                        <td><?= $customer->customer_group_name; ?></td>
                    </tr>
                   
                    </tbody>

                </table>
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="TCustomer">
                    <thead>
						<tr>
							<th>Date</th>
							<th>Discription</th>
							<th>Quantity</th>
							<th>Price</th>
							
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th></th>
							<th></th>
							<th id="getQuantity"></th>
							<th id="getPrice"></th>
							
						</tr>
					</tfoot>
					<tbody>
					<?php 
					if(isset($customer_history)){
					foreach($customer_history as $row){?>
                    <tr>
						 <td><?=$row->date;?></td>
                        <td><?= $row->product_code;?> <?=$row->product_name;?></td>
                        <td class="Tquantity"><?= $this->erp->formatQuantity($row->quantity);?></td>
						<td class="Tprice"><?= $this->erp->formatMoney($row->unit_price);?></td>
						
                    </tr>
					<?php }} ?>
    
                    </tbody>
                </table>
            </div>
           <!-- <div class="modal-footer no-print">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><?= lang('close'); ?></button>
                <?php if ($Owner || $Admin || $GP['reports-customers']) { ?>
                    <a href="<?=site_url('reports/customer_report/'.$customer->id);?>" target="_blank" class="btn btn-primary"><?= lang('customers_report'); ?></a>
                <?php } ?>
                <?php if ($Owner || $Admin || $GP['customers-edit']) { ?>
                    <a href="<?=site_url('customers/edit/'.$customer->id);?>" data-toggle="modal" data-target="#myModal2" class="btn btn-primary"><?= lang('edit_customer'); ?></a>
                <?php } ?>
            </div>-->
            <div class="clearfix"></div>
        </div>
    </div>
</div>