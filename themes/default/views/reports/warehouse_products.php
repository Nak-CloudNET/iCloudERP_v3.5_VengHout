<?php
	$v = "";

	if ($this->input->post('product')) {
		$v .= "&product=" . $this->input->post('product');
	}
	if ($this->input->post('category')) {
		$v .= "&category=" . $this->input->post('category');
	}
	if ($this->input->post('supplier')) {
		$v .= "&supplier=" . $this->input->post('supplier');
	}
	if ($this->input->post('in_out')) {
		$v .= "&in_out=" . $this->input->post('in_out');
	}
	if ($this->input->post('start_date')) {
		$v .= "&start_date=" . $this->input->post('start_date');
	}
	if ($this->input->post('end_date')) {
		$v .= "&end_date=" . $this->input->post('end_date');
	}
?>
<script>
    $(document).ready(function () {
		function format(x){
			if (x != null) {
				return '<div class="text-right">'+x.toFixed(2)+'</div>';
			} else {
				return '<div class="text-right">0</div>';
			}
		}
		$('#PrRData1').DataTable({
			"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?=lang('all')?>"]],
			"scrollY": 200,
			"scrollX": true,
		    "fnFooterCallback": function ( nRow, aaData, iStart, iEnd, aiDisplay ) {				
				var total = 0;
				for ( var i= 0 ; i < aaData.length ; i++ )
				{
					
				}				
				
			}
		}).fnSetFilteringDelay().dtFilter([
            {column_number: 1, filter_default_label: "[<?=lang('product_code');?>]", filter_type: "text", data: []},
            {column_number: 2, filter_default_label: "[<?=lang('product_name');?>]", filter_type: "text", data: []},
        ], "footer");
		$('.ware').each(function(){
			var id = $(this).val();
			var val = 0;
			$('.ware'+id).each(function(){
				val += parseFloat($(this).text()); 
			});
			$('.get'+id).text(val);
		});
		var val = 0;
		$('.total').each(function(){
			val += parseFloat($(this).text());
		});
		$('.gettoal').text(val);
    });
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#form').hide();
        $('.toggle_down').click(function () {
            $("#form").slideDown();
            return false;
        });
        $('.toggle_up').click(function () {
            $("#form").slideUp();
            return false;
        });
        $("#product").autocomplete({
            source: '<?= site_url('reports/suggestions'); ?>',
            select: function (event, ui) {
				
                $('#product_id').val(ui.item.id);
            },
            minLength: 1,
            autoFocus: false,
            delay: 300,
        });
    });
</script>
<?php if ($Owner) {
    echo form_open('reports/warehouse_actions', 'id="action-form"');
} ?>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-barcode"></i><?= lang('warehouse_reports'); ?> 
		    <?php
            if ($this->input->post('start_date')) {
                echo "From " . $this->input->post('start_date') . " to " . $this->input->post('end_date');
            }
            ?></h2>

        <div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown">
                    <a href="#" class="toggle_up tip" title="<?= lang('hide_form') ?>">
                        <i class="icon fa fa-toggle-up"></i>
                    </a>
                </li>
                <li class="dropdown">
                    <a href="#" class="toggle_down tip" title="<?= lang('show_form') ?>">
                        <i class="icon fa fa-toggle-down"></i>
                    </a>
                </li>
            </ul>
        </div>
        <div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown">
                    <a href="#" id="pdf" data-action="export_pdf" class="tip" title="<?= lang('download_pdf') ?>">
                        <i class="icon fa fa-file-pdf-o"></i>
                    </a>
                </li>
                <li class="dropdown">
                    <a href="#" id="excel"" class="tip" data-action="export_excel" title="<?= lang('download_xls') ?>">
                        <i class="icon fa fa-file-excel-o"></i>
                    </a>
                </li>
               
            </ul>
        </div>
    </div>
<?php if ($Owner) { ?>
    <div style="display: none;">
        <input type="hidden" name="form_action" value="" id="form_action"/>
        <?= form_submit('performAction', 'performAction', 'id="action-form-submit"') ?>
    </div>
    <?php echo form_close(); ?>
<?php } ?>
<?php 
?>    
	<div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <p class="introtext"><?= lang('customize_report'); ?></p>

                <div id="form">

                    <?php echo form_open("reports/warehouse_reports"); ?>
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <?= lang("product", "product"); ?>
                                <?php echo form_input('sproduct', (isset($_POST['sproduct']) ? $_POST['sproduct'] : ""), 'class="form-control" id="product"'); ?>
                                <input type="hidden" name="product"
                                       value="<?= isset($_POST['product']) ? $_POST['product'] : "" ?>"
                                       id="product_id"/>
                            </div>
                        </div>
						
                        <div class="col-sm-3">
                            <div class="form-group">
                                <?= lang("category", "category") ?>
                                <?php
                            	$cat = array("");                                
                                foreach ($categories as $category) {
                                    $cat[$category->id] = $category->name;
                                }
                                echo form_dropdown('category', $cat, (isset($_POST['category']) ? $_POST['category'] : ''), 'class="form-control select" id="category" placeholder="' . lang("select") . " " . lang("category") . '" style="width:100%"')
                                ?>
                            </div>
                        </div>
                        			
                        <div class="col-sm-3">
                            <div class="form-group">
                                <?= lang("start_date", "start_date"); ?>
                                <?php echo form_input('start_date', (isset($_POST['start_date']) ? $_POST['start_date'] : ""), 'class="form-control datetime" id="start_date"'); ?>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <?= lang("end_date", "end_date"); ?>
                                <?php echo form_input('end_date', (isset($_POST['end_date']) ? $_POST['end_date'] : ""), 'class="form-control datetime" id="end_date"'); ?>
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
                    <table id="PrRData1"
                           class="table table-striped table-bordered table-condensed table-hover dfTable reports-table"
                           style="margin-bottom:5px;">
                        <thead>
							<tr class="active">
								<th style="min-width:5%; width: 5%; text-align: center;">
									<input class="checkbox checkth" type="checkbox" name="check"/>
								</th>
								<th><?= lang('product_code');?></th>
								<th style="width:350px;"><?= lang('product_name');?></th>
								<?php
									foreach($warehouse as $ware){
										echo '<th>'.$ware->name.'</th>';
									}
								?>
								<th style="width:180px;"><?= lang('total');?></th>
							</tr>
                        </thead>
                        <tbody>
							<?php
								foreach($wreport as $treport){
							?>
							<tr>
								<th style="min-width:5%; width: 5%; text-align: center;">
									<input class="checkbox multi-select input-xs" type="checkbox" name="val[]" value="<?= $treport->product_id;?>"/>
								</th>
								<td><?= $treport->product_code;?></td>
								<td><?= $treport->name;?></td>
								<?php
									$total_wh_amount = 0;
									foreach($warehouse as $ware){
										$this->db->select('SUM(quantity) as qb');
										$this->db->from('warehouses_products');
										$this->db->where(array('warehouse_id'=>$ware->id, 'product_id'=>$treport->product_id));
										$q = $this->db->get();
										
										if ($q->num_rows() > 0) {
											echo '<input type="hidden" class="ware" value="'.$ware->id.'">';
											foreach ($q->result() as $row) {												
												if($row->qb == ""){
													echo '<td class="ware'. $ware->id .'" title="'. $ware->id .'">0.00</td>';
												}else{
													echo '<td class="ware'. $ware->id .'" title="'. $ware->id .'">'.$this->erp->formatQuantity($row->qb).'</td>';
													$total_wh_amount += $row->qb;
												}
											}
										}
									}
								?>
								<td>
									<div class="text-right">
										<div class="btn-group text-left total">
											<?= $this->erp->formatQuantity($total_wh_amount); ?>
										</div>
									</div>
								</td>
							</tr>
							<?php
								}
							?>
                        </tbody>
                        <tfoot class="dtFilter">
							<tr class="active" style="font-weight:bold; font-">
								<th style="min-width:5%; width: 5%; text-align: center;">
									<input class="checkbox checkth" type="checkbox" name="check"/>
								</th>
								<th></th>
								<th></th>
								<?php
									foreach($warehouse as $ware){
										echo '<th class=" get'.$ware->id.'">'.$ware->code .'</th>';
									}
								?>
								<th class="gettoal right"><?= lang('total');?></th>
							</tr>
                        </tfoot>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

<style type="text/css">
	.dtFilter th{ color: black; }
</style>
<script type="text/javascript" src="<?= $assets ?>js/html2canvas.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#image').click(function (event) {
            event.preventDefault();
            html2canvas($('.box'), {
                onrendered: function (canvas) {
                    var img = canvas.toDataURL();
                    window.open(img);
                }
            });
            return false;
        });
		
		$('.datetime').datetimepicker({
			format: site.dateFormats.js_ldate, 
			fontAwesome: true, 
			language: 'sma', 
			weekStart: 1, 
			todayBtn: 1, 
			autoclose: 1, 
			todayHighlight: 1, 
			startView: 2, 
			forceParse: 0
		});
		
    });
</script>
