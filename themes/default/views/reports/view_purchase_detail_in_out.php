<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
			<button type="button" class="btn btn-xs btn-default no-print pull-right" style="margin-right:15px;" onclick="window.print();">
                <i class="fa fa-print"></i> <?= lang('print'); ?>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo $this->lang->line('View Purchase Detail'); ?></h4>
        </div>
        <div class="modal-body">
            <div class="table-responsive">
                <table id="CompTable" cellpadding="0" cellspacing="0" border="0"
                       class="table table-bordered table-hover table-striped">
                    <thead>
                    <tr>
						<th style="width:30%;"><?= $this->lang->line("supplier_name"); ?></th>
                        <th style="width:20%;"><?= $this->lang->line("product_code"); ?></th>
                        <th style="width:30%;"><?= $this->lang->line("product_name"); ?></th>
						<th style="width:20%;"><?= $this->lang->line("date"); ?></th>
                        <th style="width:15%;"><?= $this->lang->line("tax"); ?></th>
						<th style="width:15%;"><?= $this->lang->line("quantity"); ?></th>
                        
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($purchase_details)) {
                        foreach ($purchase_details as $pur_detail) { ?>
                            <tr>
								<td><?= $pur_detail->supplier; ?></td>
								<td><?= $pur_detail->product_code; ?></td>
								<td><?= $pur_detail->product_name; ?></td>
								<td><?= $pur_detail->date; ?></td>
								<td><?= $this->erp->formatDecimal($pur_detail->item_tax); ?></td>
                                
                                <td><?= $this->erp->formatQuantity($pur_detail->quantity); ?></td>
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
