<style type="text/css">
    @media print {
        #myModal .modal-content {
            display: none !important;
        }
		#myModal .modal-content .noprint {
			display: none !important;
		}
    }
</style>
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('deposits') . " (" . $company->name . ")"; ?></h4>
        </div>

        <div class="modal-body">
            <!--<p><?= lang('deposits_subheading'); ?></p>-->
            <div class="alerts-con"></div>

            <div class="table-responsive">
                <table id="DepData" cellpadding="0" cellspacing="0" border="0"
                       class="table table-bordered table-condensed table-hover table-striped">
                    <thead>
                    <tr class="primary">
                        <th class="col-xs-3"><?= lang("date"); ?></th>
						<th class="col-xs-3"><?= lang("reference_no"); ?></th>
                        <th class="col-xs-2"><?= lang("amount"); ?></th>
                        <th class="col-xs-3"><?= lang("note"); ?></th>
                        <th class="col-xs-3"><?= lang("created_by"); ?></th>
                        <th class="col-xs-3"><?= lang("order_status"); ?></th>
                        <th style="width:85px;"><?= lang("actions"); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="5" class="dataTables_empty"><?= lang('loading_data_from_server') ?></td>
                        </tr>
                    </tbody>
					<tfoot class="dtFilter">
                        <tr class="active">
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th style="width:80px; text-align:center;"><?php echo $this->lang->line("actions"); ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><?= lang('close') ?></button>
        </div>
    </div>
    <?= $modal_js ?>
    <script type="text/javascript">
        $(document).ready(function () {
            $('.tip').tooltip();
            var oTable = $('#DepData').dataTable({
                "aaSorting": [[1, "asc"]],
                "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
                "iDisplayLength": <?= $Settings->rows_per_page ?>,
                'bProcessing': true, 'bServerSide': true,
                'sAjaxSource': '<?= site_url('customers/get_deposits/'.$company->id .'/'. $so_id) ?>',
                'fnServerData': function (sSource, aoData, fnCallback) {
                    aoData.push({
                        "name": "<?= $this->security->get_csrf_token_name() ?>",
                        "value": "<?= $this->security->get_csrf_hash() ?>"
                    });
                    $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
                },
            'fnRowCallback': function (nRow, aData, iDisplayIndex) {
                
				var sale_status = aData[5];
				
				var action = $('td:eq(6)', nRow);
				if(sale_status != 'order') {
					action.find('.edit').remove();
				}
            },
                "aoColumns": [{"mRender": fld}, null, {"mRender": currencyFormat}, null, null, {"bVisible": false}, {"bSortable": false}],
				"fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {
					var total = 0;
					for (var i = 0; i < aaData.length; i++) {
						total += parseFloat(aaData[aiDisplay[i]][2]);
					}
					var nCells = nRow.getElementsByTagName('th');
					nCells[2].innerHTML = currencyFormat(parseFloat(total));
				}
            }).fnSetFilteringDelay().dtFilter([
				{column_number: 0, filter_default_label: "[<?=lang('date');?> (yyyy-mm-dd)]", filter_type: "text", data: []},
				{column_number: 1, filter_default_label: "[<?=lang('reference_no');?>]", filter_type: "text", data: []},
				{column_number: 3, filter_default_label: "[<?=lang('note');?>]", filter_type: "text", data: []},
				{column_number: 4, filter_default_label: "[<?=lang('created_by');?>]", filter_type: "text", data: []},
				{column_number: 5, filter_default_label: "[<?=lang('order_status');?>]", filter_type: "text", data: []},
			], "footer");
            $('div.dataTables_length select').addClass('form-control');
            $('div.dataTables_length select').addClass('select2');
            $('div.dataTables_filter input').attr('placeholder', 'Date (yyyy-mm-dd)');
            $('select.select2').select2({minimumResultsForSearch: 7});
        });
    </script>

