<script>
	$(document).ready(function () {
        CURI = '<?= site_url('reports/getSaleReportByInvoice'); ?>';
    });
</script>
<?php
	$v = "";
	if ($this->input->post('project')) {
    $v .= "&project=" . $this->input->post('project');
	}
	if ($this->input->post('start_date')) {
    $v .= "&start_date=" . $this->input->post('start_date');
	}
	if ($this->input->post('end_date')) {
    $v .= "&end_date=" . $this->input->post('end_date');
	}
	$start_date=date('Y-m-d',strtotime($start));
	$rep_space_end=str_replace(' ','_',$end);
	$end_date=str_replace(':','-',$rep_space_end);
	
?>
<script>
	$(document).ready(function(){
		$('#form').hide();
		$('.toggle_down').click(function () {
            $("#form").slideDown();
            return false;
        });
        $('.toggle_up').click(function () {
            $("#form").slideUp();
            return false;
        });
	});
</script>
<script>
    $(document).ready(function () {	
		var oTable = $('.DOData1').dataTable({
            "aaSorting": [[0, "desc"]],
            "aLengthMenu": [[100, -1], [100, "<?= lang('all') ?>"]],
            "iDisplayLength": 100,
            "fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {
			 	var qty = 0, price = 0, discount =0, amount = 0;
                for (var i = 0; i < aaData.length; i++) 
                {	
                	if(aiDisplay.indexOf(0) != -1)
                	{
                		qty += parseFloat(aaData[aiDisplay[i]][4]);
                		price += parseFloat(aaData[aiDisplay[i]][5]);
                		discount += parseFloat(aaData[aiDisplay[i]][6]);
                		amount += parseFloat(aaData[aiDisplay[i]][7]);
                	}                    	
                }
                var nCells = nRow.getElementsByTagName('th');
                nCells[4].innerHTML = currencyFormat(parseFloat(qty));
                nCells[5].innerHTML = currencyFormat(parseFloat(price));
                nCells[6].innerHTML = currencyFormat(parseFloat(discount));
                nCells[7].innerHTML = currencyFormat(parseFloat(amount));
            }
		});
	
	});
</script>
<style type="text/css">
	.dataTables_info{ display: none;}
</style>
<?php
	echo form_open('reports/saleReportDetail_actions', 'id="action-form"');
?>
<div class="box">
    <div class="box-header">
		<h2 class="blue"><i class="fa-fw fa fa-money"></i><?= lang('sales_report_by_invoice'); ?></h2>   
		<div class="box-icon" style="">       
			<div class="box-icon">
				<ul class="btn-tasks">
					<li class="dropdown"><a href="#" id="pdf" data-action="export_pdf" class="tip" title="<?= lang('download_pdf') ?>"><i class="icon fa fa-file-pdf-o"></i></a></li>
					<li class="dropdown"><a href="#" id="excel" data-action="export_excel" class="tip" title="<?= lang('download_xls') ?>"><i class="icon fa fa-file-excel-o"></i></a></li>				
				</ul>
			</div>			
		</div>
    </div>	
<?php if ($Owner) { ?>
    <div style="display: none;">
        <input type="hidden" name="form_action" value="" id="form_action"/>
        <?= form_submit('performAction', 'performAction', 'id="action-form-submit"') ?>
    </div>
    <?php echo form_close(); ?>
<?php } ?>
	<div class="box-content">
        <div class="row">
            <div class="col-lg-12">
                <p class="introtext"><?= lang('customize_report'); ?></p>
            </div>
        </div>
    </div>
	<div class="box-content">
        <div class="row">
		    <div class="col-lg-12" style="margin-top: -46px;">
			<?php 
				foreach($billers as $biller){ ?>			
					<table class="DOData1 table table-bordered table-hover table-striped table-condensed">
						<thead>
							<tr>						
								<th style="min-width:30px; width: 30px; text-align: center;">
                                	<input class="checkbox checkth" value="<?= $biller->id; ?>" type="checkbox" name="check"/>
                            	</th>
								<th style="width:200px;"><?= strtoupper($biller->company); ?> >> (<?php echo $this->lang->line("date"); ?>)</th>
								<th style="width:200px;"><?php echo $this->lang->line("invoice_no"); ?></th>                       
								<th style="width:150px;"><?php echo $this->lang->line("item"); ?></th>
								<th style="width:150px;"><?php echo $this->lang->line("qty"); ?></th>
								<th style="width:150px;"><?php echo $this->lang->line("price"); ?></th>
								<th style="width:150px;"><?php echo $this->lang->line("dis"); ?></th>
								<th style="width:150px;"><?php echo $this->lang->line("amount"); ?></th>								                     
							</tr>
						</thead>
						<tbody>
							<?php 				
								$this->load->library("pagination");			
							 	$result = $this->reports_model->getSearchInvoice($biller->id);
							 	$total_quantity = $result['total_quantity'];
							 	$total_price = $result['total_price'];
							 	$total_discount_item = $result['total_discount_item'];
							 	$total_amount = $result['total_amount'];
							 	echo $result['html'];								 	
							?>
						</tbody>
						<tfoot class="dtFilter">
	                        <tr class="active">               
	                            <th><input type="checkbox" class="check checkth" name="check[]" /></th>
	                        	<th><?= lang("date") ?></th>
	                            <th><?= lang("reference_no") ?></th>
	                            <th><?= lang("item") ?></th>
	                            <th class="right total_quantity"><?= $total_quantity ?></th>
	                            <th class="right total_price"><?= $total_price ?></th>
								<th class="right total_discount_item"><?= $total_discount_item ?></th>
	                            <th class="right total_amount"><?= $total_amount ?></th>
	                        </tr>
                        </tfoot>
					</table>															
				  <?php				
				}	
			?>
            </div>
        </div>
    </div>
</div>
