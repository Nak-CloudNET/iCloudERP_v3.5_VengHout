<?php
$v = "";
/* if($this->input->post('name')){
  $v .= "&name=".$this->input->post('name');
} */

//$c = $card_no;
if($card_no) {
	$v .= "&no=".$card_no;
}
?>
<script>
	$.fn.dataTableExt.afnFiltering.push(
	function( oSettings, aData, iDataIndex ) {
		var iFini = document.getElementById('fdate').value;
		var iFfin = document.getElementById('tdate').value;
		var iStartDateCol = 2;
		var iEndDateCol = 2;

    iFini=iFini.substring(0,2) + iFini.substring(3,5)+ iFini.substring(6,10)
    iFfin=iFfin.substring(0,2) + iFfin.substring(3,5)+ iFfin.substring(6,10)       

    var datofini=aData[iStartDateCol].substring(0,2) + aData[iStartDateCol].substring(3,5)+ aData[iStartDateCol].substring(6,10);
    var datoffin=aData[iEndDateCol].substring(0,2) + aData[iEndDateCol].substring(3,5)+ aData[iEndDateCol].substring(6,10);


			if ( iFini == "" && iFfin == "" )
			{
				return true;
			}
			else if ( iFini <= datofini && iFfin == "")
			{
				return true;
			}
			else if ( iFfin >= datoffin && iFini == "")
			{
				return true;
			}
			else if (iFini <= datofini && iFfin >= datoffin)
			{
				return true;
			}
			return false;
		}
	);
    $(document).ready(function () {
        var pb = ['<?=lang('cash')?>', '<?=lang('CC')?>', '<?=lang('Cheque')?>', '<?=lang('paypal_pro')?>', '<?=lang('stripe')?>', '<?=lang('gift_card')?>'];

        function paid_by(x) {
            if (x == 'cash') {
                return pb[0];
            } else if (x == 'CC') {
                return pb[1];
            } else if (x == 'Cheque') {
                return pb[2];
            } else if (x == 'ppp') {
                return pb[3];
            } else if (x == 'stripe') {
                return pb[4];
            } else if (x == 'gift_card') {
                return pb[5];
            } else {
                return x;
            }
        }

        function ref(x) {
            return (x != null) ? x : ' ';
        }
		$('#btn-search').click(function(){
			var start_date = $('#fdate').val();
			var end_date = $('#tdate').val();
			
			/*$.ajax({
				type: "GET",
				url: '<?= site_url('sales/getGiftCardsHistory/?v=1' . $v) ?>',
				data: ({start:start_date,end:end_date}),
				success: function() {
					//alert('form was submitted');
				}
			});*/
		});
        var oTable = $('#PayRData').dataTable({
			"bJQueryUI": true,
            "aaSorting": [[0, "desc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
            "iDisplayLength": <?= $Settings->rows_per_page ?>,
            'bProcessing': true, 'bServerSide': true,
            'sAjaxSource': '<?= site_url('sales/getGiftCardsHistory/?v=1'.$v) ?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "<?= $this->security->get_csrf_token_name() ?>",
                    "value": "<?= $this->security->get_csrf_hash() ?>"
                });
                $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
            },
            "aoColumns": [{"mRender": fld}, null, {"mRender": ref}, {"mRender": ref}, {"mRender": currencyFormat}, {"mRender": row_status}],
            'fnRowCallback': function (nRow, aData, iDisplayIndex) {
                var oSettings = oTable.fnSettings();
                if (aData[5] == 'sent') {
                    nRow.className = "warning";
                } else if (aData[5] == 'returned') {
                    nRow.className = "danger";
                }
                return nRow;
            },
            "fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {
                var total = 0;
                for (var i = 0; i < aaData.length; i++) {
                    if (aaData[aiDisplay[i]][5] != 'received' || aaData[aiDisplay[i]][5] != 'received')
                        total -= parseFloat(aaData[aiDisplay[i]][4]);
                    else
                        total += parseFloat(aaData[aiDisplay[i]][4]);
                }
                var nCells = nRow.getElementsByTagName('th');
                nCells[4].innerHTML = currencyFormat(parseFloat(total));
            }
        }).fnSetFilteringDelay().dtFilter([
            {column_number: 0, filter_default_label: "[<?=lang('date');?> (yyyy-mm-dd)]", filter_type: "text", data: []},
			{column_number: 1, filter_default_label: "[<?=lang('card_no');?>]", filter_type: "text", data: []},
            {column_number: 2, filter_default_label: "[<?=lang('payment_ref');?>]", filter_type: "text", data: []},
            {column_number: 3, filter_default_label: "[<?=lang('sale_ref');?>]", filter_type: "text", data: []},
            //{column_number: 4, filter_default_label: "[<?=lang('purchase_ref');?>]", filter_type: "text", data: []},            
            {column_number: 5, filter_default_label: "[<?=lang('type');?>]", filter_type: "text", data: []},
        ], "footer");
		
		//$('#fdate').keyup( function() { oTable.fnDraw();});
		//$('#tdate').keyup( function() { oTable.fnDraw();});
    });
	
	$('#fdate, #tdate').keyup( function() {
        table.draw();
    } );
	
	
</script>

<div class="modal-dialog" style="width:70% !important;">
    <div class="modal-content">
        <div class="modal-header no-print">
			<!--<div class="box-icon" style="width:42%;position:absolute;right:190px;top:10px;">
				<div class="form-group" style="float:left;">
					<?php echo form_input('date', (isset($_POST['date']) ? $_POST['date'] : ""), 'class="form-control input-tip datetime" id="fdate" placeholder="From Date" style="float: left; width: 170px;"'); ?>
				</div>
				<div class="form-group">
					<?php echo form_input('date', (isset($_POST['date']) ? $_POST['date'] : ""), 'class="form-control input-tip datetime" id="tdate" placeholder="To Date" style="width: 170px;"'); ?>
				</div>
			</div>
			<button type="button" class="btn btn-default" id="btn-search" style="float:left;position:absolute;left:625px;top:10px;" ><?= lang('search'); ?></button>
			<button type="button" class="btn btn-default" onClick="window.print();" style="float:left;position:absolute;left:700px;top:10px;" ><?= lang('print'); ?></button>-->
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?= lang('view_gift_card_history'); ?></h4>
        </div>
        <div class="modal-body">
            <div class="table-responsive">
				<table id="PayRData" class="table table-bordered table-hover table-striped table-condensed reports-table">

					<thead>
						<tr>
							<th><?= lang("date"); ?></th>
							<th><?= lang("card_no"); ?></th>
							<th><?= lang("payment_ref"); ?></th>
							<th><?= lang("sale_ref"); ?></th>
							<th><?= lang("amount"); ?></th>
							<th><?= lang("type"); ?></th>
						</tr>
					</thead>
					
					<tbody>
						<tr>
							<td colspan="7" class="dataTables_empty"><?= lang('loading_data_from_server') ?></td>
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
						</tr>
					</tfoot>
				</table>
			</div>
        </div>
    </div>
</div>
