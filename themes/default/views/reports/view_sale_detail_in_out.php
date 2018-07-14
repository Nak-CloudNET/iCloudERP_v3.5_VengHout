<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
			<button type="button" class="btn btn-xs btn-default no-print pull-right" style="margin-right:15px;" onclick="window.print();">
                <i class="fa fa-print"></i> <?= lang('print'); ?>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo $this->lang->line('View Sale Detail'); ?></h4>
        </div>
        <div class="modal-body">
            <div class="table-responsive">
                <table id="CompTable" cellpadding="0" cellspacing="0" border="0"
                       class="table table-bordered table-hover table-striped">
                    <thead>
                    <tr>
						<th style="width:30%;"><?= $this->lang->line("customer_name"); ?></th>
                        <th style="width:10%;"><?= $this->lang->line("product_code"); ?></th>
                        <th style="width:25%;"><?= $this->lang->line("product_name"); ?></th>
						<th style="width:10%;"><?= $this->lang->line("date"); ?></th>
                        <th style="width:10%;"><?= $this->lang->line("product_type"); ?></th>
                        <th style="width:15%;"><?= $this->lang->line("unit_price"); ?></th>
						<th style="width:15%;"><?= $this->lang->line("quantity"); ?></th>
						<th style="width:15%;"><?= $this->lang->line("total_price"); ?></th>
                        
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($sale_details)) {
                        foreach ($sale_details as $sale_detail) { ?>
                            <tr>
								<td><?= $sale_detail->customer; ?></td>
								<td><?= $sale_detail->product_code; ?></td>
								<td><?= $sale_detail->product_name; ?></td>
								<td><?= $sale_detail->date; ?></td>
								<td><?= $sale_detail->product_type; ?></td>
                                <td><?= $this->erp->formatDecimal($sale_detail->unit_price); ?></td>
                                <td><?= $this->erp->formatQuantity($sale_detail->quantity); ?></td>
								<td><?= $this->erp->formatMoney($sale_detail->unit_price*$sale_detail->quantity); ?></td>
                            </tr>
                        <?php }
                    } else {
                        echo "<tr><td colspan='5'>" . lang('no_data_available') . "</td></tr>";
                    } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" charset="UTF-8">
    $(document).ready(function () {
        $(document).on('click', '.po-delete', function () {
            var id = $(this).attr('id');
            $(this).closest('tr').remove();
        });
    });
</script>
