<?php


?>
<style type="text/css" media="screen">
    #PRData td:nth-child(6), #PRData td:nth-child(7) {
        text-align: right;
    }
    <?php if($Owner || $Admin || $this->session->userdata('show_cost')) { ?>
    #PRData td:nth-child(8) {
        text-align: right;
    }
    <?php } ?>
</style>

<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-barcode"></i><?= lang('sales_customer_detail') ; ?>
        </h2>
		<!--<div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown">
                    <a href="javascript:void(0);" class="toggle_up tip" title="<?= lang('hide_form') ?>">
                        <i class="icon fa fa-toggle-up"></i>
                    </a>
                </li>
                <li class="dropdown">
                    <a href="javascript:void(0);" class="toggle_down tip" title="<?= lang('show_form') ?>">
                        <i class="icon fa fa-toggle-down"></i>
                    </a>
                </li>
            </ul>
        </div>-->
        

    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <p class="introtext"><?= lang('list_results'); ?></p>
                <div id="form">
				<?php echo form_open('reports/sales_customer_detail/', 'id="action-form"'); ?>
					<div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="reference_no"><?= lang("reference_no"); ?></label>
                                <?php echo form_input('reference_no', (isset($_POST['reference_no']) ? $_POST['reference_no'] : ""), 'class="form-control tip" id="reference_no"'); ?>

                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("from_date", "from_date"); ?>
                                <?php echo form_input('from_date', (isset($_POST['from_date']) ? $_POST['from_date'] : ""), 'class="form-control datetime" id="from_date"'); ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("to_date", "to_date"); ?>
                                <?php echo form_input('to_date', (isset($_POST['to_date']) ? $_POST['to_date'] : ""), 'class="form-control datetime" id="to_date"'); ?>
                            </div>
                        </div>

                    </div>
					<div class="form-group">
                        <div
                            class="controls"> <?php echo form_submit('submit_report', $this->lang->line("submit"), 'class="btn btn-primary"'); ?> </div>
                    </div>
                    <?php echo form_close(); ?>
					
                </div>
                <div class="clearfix"></div>

                <div class="table-responsive">
                    <table id="" class="table table-bordered table-hover table-striped table-condensed data_table">
                        <thead>
                        <tr class="primary">
							<th><?php echo $this->lang->line("type"); ?></th>
							<th><?php echo $this->lang->line("date"); ?></th>
							<th><?php echo $this->lang->line("reference_no"); ?></th>									
							<th><?php echo $this->lang->line("note"); ?></th>
							<th><?php echo $this->lang->line("name"); ?></th>									
							<th><?php echo $this->lang->line("product_name"); ?></th>									
							<th><?php echo $this->lang->line("quantity"); ?></th>									
							<th><?php echo $this->lang->line("unit_price"); ?></th>									
							<th><?php echo $this->lang->line("amount"); ?></th>									
							<th><?php echo $this->lang->line("balance"); ?></th>																																			
							</tr>
                        </thead>
                        <tbody>
							<?php
								 $this->db->select("sales.id,
															sales.type,
															sales.date,
															sales.reference_no,
															sales.note,
															companies.`name`,
															sale_items.product_name,
																(CASE
															WHEN erp_return_items.quantity THEN
																(-1) * erp_return_items.quantity
															ELSE
																erp_sale_items.quantity
															END) as quantity,
															sale_items.unit_price,
																erp_sale_items.unit_price,
																(CASE
															WHEN erp_return_items.quantity THEN
																((-1) * erp_return_items.quantity) * erp_sale_items.unit_price
															ELSE
																erp_sale_items.quantity * erp_sale_items.unit_price
															END) as amount")
												->from("companies")
												->join("sales","sales.customer_id = companies.id","inner")
												->join("sale_items","sale_items.sale_id = sales.id","left")
												->join("return_sales","return_sales.sale_id = sales.id","left")
												->join("return_items","return_items.return_id = return_sales.id ","left")
												->order_by('companies.id asc');
												$sale_detail=$this->db->get()->result();
												
								foreach($sale_detail as $row)
								{
									$balance+=$row->amount;
								?>
									<tr>
										<td><?= $row->type ?></td>
										<td><?= date('d/m/Y :H:i',strtotime( $row->date)) ?></td>
										<td><?= $row->reference_no ?></td>
										<td><?= $this->erp->decode_html(strip_tags($row->note)) ?></td>
										<td><?= $row->name ?></td>
										<td><?= $row->product_name ?></td>
										<td><?= $this->erp->formatDecimal($row->quantity) ?></td>
										<td><?= $this->erp->formatDecimal($row->unit_price) ?></td>
										<td><?= $this->erp->formatDecimal($row->amount) ?></td>
										<td><?= $this->erp->formatDecimal($balance) ?></td>
									</tr>
								<?php
								}								
							?>
												
                        </tbody>
                       
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('#form').hide();
    $('.toggle_down').click(function () {
        $("#form").slideDown();
        return false;
    });
    $('.toggle_up').click(function () {
        $("#form").slideUp();
        return false;
    });
	$(document).ready(function(){
		/*
		$("#excel").click(function(e){
			e.preventDefault();
			window.location.href = "<?=site_url('products/getProductAll/0/xls/')?>";
			return false;
		});
		$('#pdf').click(function (event) {
            event.preventDefault();
            window.location.href = "<?=site_url('products/getProductAll/pdf/?v=1'.$v)?>";
            return false;
        });
		*/
		$('body').on('click', '#multi_adjust', function() {
			 if($('.checkbox').is(":checked") === false){
				alert('Please select at least one.');
				return false;
			}
			var arrItems = [];
			$('.checkbox').each(function(i){
				if($(this).is(":checked")){
					if(this.value != ""){
						arrItems[i] = $(this).val();   
					}
				}
			});
			$('#myModal').modal({remote: '<?=base_url('products/multi_adjustment');?>?data=' + arrItems + ''});
			$('#myModal').modal('show');
        });
		$('#excel').on('click', function(e){
			e.preventDefault();
			if ($('.checkbox:checked').length <= 0) {
				window.location.href = "<?=site_url('products/getProductAll/0/xls/')?>";
				return false;
			}
		});
		$('#pdf').on('click', function(e){
			e.preventDefault();
			if ($('.checkbox:checked').length <= 0) {
				window.location.href = "<?=site_url('products/getProductAll/pdf/?v=1'.$v)?>";
				return false;
			}
		});
		
		$(".data_table").dataTable();
	});
</script>

