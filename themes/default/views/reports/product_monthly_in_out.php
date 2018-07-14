<?php
$v = "";

if ($this->input->post('product')) {
    $v .= "&product=" . $this->input->post('product');
}
if ($this->input->post('category')) {
    $v .= "&category=" . $this->input->post('category');
}
if ($this->input->post('in_out')) {
    $v .= "&in_out=" . $this->input->post('in_out');
}
if(isset($warehouse_id)){
	$v .= "&warehouse=" . $warehouse_id;
}
?>

<style>
	#PrRData1 {
		white-space: nowrap;
    }
</style>

<script>
    $(document).ready(function () {
        function spb(x) {
            v = x.split('__');
            return formatQuantity2(v[0]);
        }
        var oTable = $('#PrRData1').dataTable({
            "aaSorting": [[0, "desc"]],
			scrollY:        '50vh',
			scrollCollapse: true,
			paging:         false,
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
            "iDisplayLength": <?= $Settings->rows_per_page ?>,
            'bProcessing': true, 
			'bServerSide': true,
            'sAjaxSource': '<?= site_url('reports/getProductsMonthly/?v=1'.$v) ?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "<?= $this->security->get_csrf_token_name() ?>",
                    "value": "<?= $this->security->get_csrf_hash() ?>"
                });
                $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
            },
            "aoColumns": [null, null,
			{"mRender": formatQuantity2, "bSortable" : false , "bSearchable" : false},
			{"mRender": formatQuantity2, "bSortable" : false , "bSearchable" : false}, 
			{"mRender": formatQuantity2, "bSortable" : false , "bSearchable" : false}, 
			{"mRender": formatQuantity2, "bSortable" : false , "bSearchable" : false},
			{"mRender": formatQuantity2, "bSortable" : false , "bSearchable" : false},
			{"mRender": formatQuantity2, "bSortable" : false , "bSearchable" : false},
			{"mRender": formatQuantity2, "bSortable" : false , "bSearchable" : false},
			{"mRender": formatQuantity2, "bSortable" : false , "bSearchable" : false},
			{"mRender": formatQuantity2, "bSortable" : false , "bSearchable" : false},
			{"mRender": formatQuantity2, "bSortable" : false , "bSearchable" : false},
			{"mRender": formatQuantity2, "bSortable" : false , "bSearchable" : false},
			{"mRender": formatQuantity2, "bSortable" : false , "bSearchable" : false}
			],
            "fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {
                var jan = 0, feb = 0, mar = 0, apr = 0, may = 0, jun = 0, jul = 0, aug = 0, sep = 0, oct = 0, nov = 0, dec = 0;
                for (var i = 0; i < aaData.length; i++) {
					
                    a1 = (aaData[aiDisplay[i]][2]);
                    b1 = (aaData[aiDisplay[i]][3]);
                    c1 = (aaData[aiDisplay[i]][4]);
                    d1 = (aaData[aiDisplay[i]][5]);
					e1 = (aaData[aiDisplay[i]][6]);
					f1 = (aaData[aiDisplay[i]][7]);
					g1 = (aaData[aiDisplay[i]][8]);
					h1 = (aaData[aiDisplay[i]][9]);
					i1 = (aaData[aiDisplay[i]][10]);
					j1 = (aaData[aiDisplay[i]][11]);
					k1 = (aaData[aiDisplay[i]][12]);
					l1 = (aaData[aiDisplay[i]][13]);
					
					jan += parseFloat(a1);
                    feb += parseFloat(b1);
                    mar += parseFloat(c1);
                    apr += parseFloat(d1);
					may +=parseFloat(e1);
					jun += parseFloat(f1);
					jul += parseFloat(g1);
					aug += parseFloat(h1);
					sep += parseFloat(i1);
					nov += parseFloat(j1);
					oct += parseFloat(k1);
					dec += parseFloat(l1);
					
                }
                var nCells = nRow.getElementsByTagName('th');
				
                nCells[2].innerHTML = '<div class="text-right">'+formatQuantity2(jan)+'</div>';
                nCells[3].innerHTML = '<div class="text-right">'+formatQuantity2(feb)+'</div>';
                nCells[4].innerHTML = '<div class="text-right">'+formatQuantity2(mar)+'</div>';
				nCells[5].innerHTML = '<div class="text-right">'+formatQuantity2(apr)+'</div>';
                nCells[6].innerHTML = '<div class="text-right">'+formatQuantity2(may)+'</div>';
                nCells[7].innerHTML = '<div class="text-right">'+formatQuantity2(jun)+'</div>';
				nCells[8].innerHTML = '<div class="text-right">'+formatQuantity2(jul)+'</div>';
                nCells[9].innerHTML = '<div class="text-right">'+formatQuantity2(aug)+'</div>';              
                nCells[10].innerHTML = '<div class="text-right">'+formatQuantity2(sep)+'</div>';
				nCells[11].innerHTML = '<div class="text-right">'+formatQuantity2(nov)+'</div>';
				nCells[12].innerHTML = '<div class="text-right">'+formatQuantity2(oct)+'</div>';
				nCells[13].innerHTML = '<div class="text-right">'+formatQuantity2(dec)+'</div>';

            }
        }).fnSetFilteringDelay().dtFilter([
            {column_number: 0, filter_default_label: "[<?=lang('product_code');?>]", filter_type: "text", data: []},
            {column_number: 1, filter_default_label: "[<?=lang('product_name');?>]", filter_type: "text", data: []},
        ], "footer");
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
                //$(this).val(ui.item.label);
            },
            minLength: 1,
            autoFocus: false,
            delay: 300,
        });
    });
</script>

<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-barcode"></i><?= lang('monthly_products'); ?> 
		    <?php
				if ($this->input->post('start_date')) {
					echo "From " . $this->input->post('start_date') . " to " . $this->input->post('end_date');
				}
            ?>
		</h2>

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
                <?php if (!empty($warehouses)) { ?>
                    <li class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon fa fa-building-o tip" data-placement="left" title="<?= lang("warehouses") ?>"></i></a>
                        <ul class="dropdown-menu pull-right" class="tasks-menus" role="menu" aria-labelledby="dLabel">
                            <li><a href="<?= site_url('reports/product_monthly_in_out') ?>"><i class="fa fa-building-o"></i> <?= lang('all_warehouses') ?></a></li>
                            <li class="divider"></li>
                            <?php
                            foreach ($warehouses as $warehouse) {
                               echo '<li><a href="' . site_url('reports/product_monthly_in_out/' . $warehouse->id) . '"><i class="fa fa-building"></i>' . $warehouse->name . '</a></li>';
                            }
                            ?>
                        </ul>
                    </li>
                <?php } ?>
			</ul>
		</div>
		
    </div>
    
	<div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <p class="introtext"><?= lang('customize_report'); ?></p>

                <div id="form">

                    <?php echo form_open("reports/product_monthly_in_out"); ?>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("product", "product"); ?>
                                <?php echo form_input('sproduct', (isset($_POST['sproduct']) ? $_POST['sproduct'] : ""), 'class="form-control" id="product"'); ?>
                                <input type="hidden" name="product"
                                       value="<?= isset($_POST['product']) ? $_POST['product'] : "" ?>"
                                       id="product_id"/>
                            </div>
                        </div>
                        
						<div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("category", "category") ?>
                                <?php
                                $cat[''] = "";
                                foreach ($categories as $category) {
                                    $cat[$category->id] = $category->name;
                                }
                                echo form_dropdown('category', $cat, (isset($_POST['category']) ? $_POST['category'] : ''), 'class="form-control select" id="category" placeholder="' . lang("select") . " " . lang("category") . '" style="width:100%"')
                                ?>
                            </div>
                        </div>
                        
                        <div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("in_out", "in_out") ?>
                                <?php
                                $in_out = array(
                                    'all' => lang('all'),
                                    'in' => lang('in'),
                                    'out' => lang('out')
                                );
                                echo form_dropdown('in_out', $in_out, (isset($_POST['in_out']) ? $_POST['in_out'] : ''), 'class="form-control select" id="in_out" placeholder="' . lang("select") . " " . lang("in_out") . '" style="width:100%"')
                                ?>
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
								<th style="width:250px;"><?= lang("product_code"); ?></th>
								<th style="width:350px;"><?= lang("product_name"); ?></th>
								<th style="width:180px;"><?= lang("january"); ?></th>
								<th style="width:180px;"><?= lang("february"); ?></th>
								<th style="width:180px;"><?= lang("march"); ?></th>
								<th style="width:180px;"><?= lang("april"); ?></th>
								<th style="width:180px;"><?= lang("may"); ?></th>
								<th style="width:180px;"><?= lang("june"); ?></th>
								<th style="width:180px;"><?= lang("july"); ?></th>
								<th style="width:180px;"><?= lang("august"); ?></th>
								<th style="width:180px;"><?= lang("september"); ?></th>
								<th style="width:180px;"><?= lang("october"); ?></th>
								<th style="width:180px;"><?= lang("november"); ?></th>
								<th style="width:180px;"><?= lang("december"); ?></th>
							</tr>
                        </thead>
                        <tbody>
							<tr>
								<td colspan="14" class="dataTables_empty"><?= lang('loading_data_from_server') ?></td>
							</tr>
                        </tbody>
                        <tfoot class="dtFilter">
							<tr class="active">
								<th></th>
								<th></th>
								<th><?= lang("january"); ?></th>
								<th><?= lang("february"); ?></th>
								<th><?= lang("march"); ?></th>
								<th><?= lang("april"); ?></th>
								<th><?= lang("may"); ?></th>
								<th><?= lang("june"); ?></th>
								<th><?= lang("july"); ?></th>
								<th><?= lang("august"); ?></th>
								<th><?= lang("september"); ?></th>
								<th><?= lang("october"); ?></th>
								<th><?= lang("november"); ?></th>
								<th><?= lang("december"); ?></th>
							</tr>
                        </tfoot>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?= $assets ?>js/html2canvas.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#pdf').click(function (event) {
            event.preventDefault();
            window.location.href = "<?=site_url('reports/getProductsReportInOut/pdf/?v=1'.$v)?>";
            return false;
        });
        $('#xls').click(function (event) {
            event.preventDefault();
            window.location.href = "<?=site_url('reports/getProductsReportInOut/0/xls/?v=1'.$v)?>";
            return false;
        });
        $('#image').click(function (event) {
            event.preventDefault();
            html2canvas($('.box'), {
                onrendered: function (canvas) {
                    var img = canvas.toDataURL()
                    window.open(img);
                }
            });
            return false;
        });
		if ($(window).width() < 1024) {
		    $('#PrRData_wrapper').css('width', '100%');
			$('#PrRData_wrapper').css('overflow-x', 'scroll');
			$('#PrRData_wrapper').css('white-space', 'nowrap');
		}
    });
</script>
