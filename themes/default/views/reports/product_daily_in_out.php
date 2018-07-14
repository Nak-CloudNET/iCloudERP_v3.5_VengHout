<?php
	$currentMonth = date('m');
	$list=array();
	$month = date('m');
	$year = date('Y');

	for($d=1; $d<=31; $d++)
	{
		$time=mktime(12, 0, 0, $month, $d, $year);          
		if (date('m', $time)==$month)       
			$list[]= date('d', $time);
	}
	$v = "";

	if ($this->input->post('year')) {
		$v .= "&year=" . $this->input->post('year');
	} else {
		$v .= "&year=" . $year;
	}

	if ($this->input->post('month')) {
		$v .= "&month=" . $this->input->post('month');
	} else {
		$v .= "&month=" . $month;
	}

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
	#PrRData2 {
        overflow-x: scroll;
		max-width: 100%;
		min-height: 300px;
		display: block;
		cursor: pointer;
		white-space: nowrap;
       
    }
</style>
<script>
    $(document).ready(function () {
        function spb(x) {
            v = x.split('__');
            return formatQuantity2(v[0]);
        }
        var oTable = $('#PrRData2').dataTable({
            "aaSorting": [[0, "asc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
            "iDisplayLength": <?= $Settings->rows_per_page ?>,
            'bProcessing': true, 'bServerSide': true,
            'sAjaxSource': '<?= site_url('reports/getProductsDaily/?v=1'.$v) ?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "<?= $this->security->get_csrf_token_name() ?>",
                    "value": "<?= $this->security->get_csrf_hash() ?>"
                });
                $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
            },
            "aoColumns": [null, null 

                /*,{"mRender": formatQuantity2},{"mRender": formatQuantity2},{"mRender": formatQuantity2},{"mRender": formatQuantity2},{"mRender": formatQuantity2},{"mRender": formatQuantity2},{"mRender": formatQuantity2},{"mRender": formatQuantity2},{"mRender": formatQuantity2},{"mRender": formatQuantity2},{"mRender": formatQuantity2},{"mRender": formatQuantity2},{"mRender": formatQuantity2},{"mRender": formatQuantity2},{"mRender": formatQuantity2},{"mRender": formatQuantity2},{"mRender": formatQuantity2},{"mRender": formatQuantity2},{"mRender": formatQuantity2},{"mRender": formatQuantity2},{"mRender": formatQuantity2},{"mRender": formatQuantity2},{"mRender": formatQuantity2},{"mRender": formatQuantity2},{"mRender": formatQuantity2},{"mRender": formatQuantity2},{"mRender": formatQuantity2},{"mRender": formatQuantity2},{"mRender": formatQuantity2},{"mRender": formatQuantity2} */
                        <?php
                        $r = 1;
                        foreach($list as $rws)
                        {
                            ?>
                            <?=(",")?>{"mRender": formatQuantity2, "bSortable" : false}
                            <?php
                            $r ++;
                        }
                        ?>
                ],
            "fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {
                <?php
                $r = 1;
                foreach($list as $rws)
                {
                    echo "var d$r = 0;";
                    $r ++;
                }
                ?>
                /*var d1 = 0;
                var d2 = 0;
                var d3 = 0;
                var d4 = 0;
                var d5 = 0;
                var d6 = 0;
                var d7 = 0;
                var d8 = 0;
                var d9 = 0;
                var d10 = 0;
                var d11 = 0;
                var d12 = 0;
                var d13 = 0;
                var d14 = 0;
                var d15 = 0;
                var d16 = 0;
                var d17 = 0;
                var d18 = 0;
                var d19 = 0;
                var d20 = 0;
                var d21 = 0;
                var d22 = 0;
                var d23 = 0;
                var d24 = 0;
                var d25 = 0;
                var d26 = 0;
                var d27 = 0;
                var d28 = 0;
                var d29 = 0;
                var d30 = 0;*/

                for (var i = 0; i < aaData.length; i++) {
                    <?php
                    $r = 1;
                    foreach($list as $rws)
                    {
                        ?>
                        r<?=($r)?> = (aaData[aiDisplay[i]][<?=($rws + 1)?>]);
                        <?php
                        $r ++;
                    }
                    ?>
                    /*r1 = (aaData[aiDisplay[i]][2]);
                    r2 = (aaData[aiDisplay[i]][3]);
                    r3 = (aaData[aiDisplay[i]][4]);
                    r4 = (aaData[aiDisplay[i]][5]);
                    r5 = (aaData[aiDisplay[i]][6]);
                    r6 = (aaData[aiDisplay[i]][7]);
                    r7 = (aaData[aiDisplay[i]][8]);
                    r8 = (aaData[aiDisplay[i]][9]);
                    r9 = (aaData[aiDisplay[i]][10]);
                    r10 = (aaData[aiDisplay[i]][11]);
                    r11 = (aaData[aiDisplay[i]][12]);
                    r12 = (aaData[aiDisplay[i]][13]);
                    r13 = (aaData[aiDisplay[i]][14]);
                    r14 = (aaData[aiDisplay[i]][15]);
                    r15 = (aaData[aiDisplay[i]][16]);
                    r16 = (aaData[aiDisplay[i]][17]);
                    r17 = (aaData[aiDisplay[i]][18]);
                    r18 = (aaData[aiDisplay[i]][19]);
                    r19 = (aaData[aiDisplay[i]][20]);
                    r20 = (aaData[aiDisplay[i]][21]);
                    r21 = (aaData[aiDisplay[i]][22]);
                    r22 = (aaData[aiDisplay[i]][23]);
                    r23 = (aaData[aiDisplay[i]][24]);
                    r24 = (aaData[aiDisplay[i]][25]);
                    r25 = (aaData[aiDisplay[i]][26]);
                    r26 = (aaData[aiDisplay[i]][27]);
                    r27 = (aaData[aiDisplay[i]][28]);
                    r28 = (aaData[aiDisplay[i]][29]);
                    r29 = (aaData[aiDisplay[i]][30]);
                    r30 = (aaData[aiDisplay[i]][31]);*/

                    <?php
                    $r = 1;
                    foreach($list as $rws)
                    {
                        ?>

                        d<?=($r)?> += parseFloat(r<?=($r)?>);
                        
                        <?php
                        $r ++;
                    }
                    ?>
                    /*d1 += parseFloat(r1);
                    d2 += parseFloat(r2);
                    d3 += parseFloat(r3);
                    d4 += parseFloat(r4);
                    d5 += parseFloat(r5);
                    d6 += parseFloat(r6);
                    d7 += parseFloat(r7);
                    d8 += parseFloat(r8);
                    d9 += parseFloat(r9);
                    d10 += parseFloat(r10);
                    d11 += parseFloat(r11);
                    d12 += parseFloat(r12);
                    d13 += parseFloat(r13);
                    d14 += parseFloat(r14);
                    d15 += parseFloat(r15);
                    d16 += parseFloat(r16);
                    d17 += parseFloat(r17);
                    d18 += parseFloat(r18);
                    d19 += parseFloat(r19);
                    d20 += parseFloat(r20);
                    d21 += parseFloat(r21);
                    d22 += parseFloat(r22);
                    d23 += parseFloat(r23);
                    d24 += parseFloat(r24);
                    d25 += parseFloat(r25);
                    d26 += parseFloat(r26);
                    d27 += parseFloat(r27);
                    d28 += parseFloat(r28);
                    d29 += parseFloat(r29);
                    d30 += parseFloat(r30);*/
                }
                var nCells = nRow.getElementsByTagName('th');
				
                <?php
                $r = 1;
                foreach($list as $rws)
                {
                    ?>
                    nCells[<?=($r + 1)?>].innerHTML = '<div class="text-right">'+formatQuantity2(d<?=($r)?>)+'</div>';
                    
                    <?php
                    $r ++;
                }
                ?>
                /*nCells[2].innerHTML = '<div class="text-right">'+formatQuantity2(d1)+'</div>';
                nCells[3].innerHTML = '<div class="text-right">'+formatQuantity2(d2)+'</div>';
                nCells[4].innerHTML = '<div class="text-right">'+formatQuantity2(d3)+'</div>';
                nCells[5].innerHTML = '<div class="text-right">'+formatQuantity2(d4)+'</div>';
                nCells[6].innerHTML = '<div class="text-right">'+formatQuantity2(d5)+'</div>';
                nCells[7].innerHTML = '<div class="text-right">'+formatQuantity2(d6)+'</div>';
                nCells[8].innerHTML = '<div class="text-right">'+formatQuantity2(d7)+'</div>';
                nCells[9].innerHTML = '<div class="text-right">'+formatQuantity2(d8)+'</div>';
                nCells[10].innerHTML = '<div class="text-right">'+formatQuantity2(d9)+'</div>';
                nCells[11].innerHTML = '<div class="text-right">'+formatQuantity2(d10)+'</div>';
                nCells[12].innerHTML = '<div class="text-right">'+formatQuantity2(d11)+'</div>';
                nCells[13].innerHTML = '<div class="text-right">'+formatQuantity2(d12)+'</div>';
                nCells[14].innerHTML = '<div class="text-right">'+formatQuantity2(d13)+'</div>';
                nCells[15].innerHTML = '<div class="text-right">'+formatQuantity2(d14)+'</div>';
                nCells[16].innerHTML = '<div class="text-right">'+formatQuantity2(d15)+'</div>';
                nCells[17].innerHTML = '<div class="text-right">'+formatQuantity2(d16)+'</div>';
                nCells[18].innerHTML = '<div class="text-right">'+formatQuantity2(d17)+'</div>';
                nCells[19].innerHTML = '<div class="text-right">'+formatQuantity2(d18)+'</div>';
                nCells[20].innerHTML = '<div class="text-right">'+formatQuantity2(d19)+'</div>';
                nCells[21].innerHTML = '<div class="text-right">'+formatQuantity2(d20)+'</div>';
                nCells[22].innerHTML = '<div class="text-right">'+formatQuantity2(d21)+'</div>';
                nCells[23].innerHTML = '<div class="text-right">'+formatQuantity2(d22)+'</div>';
                nCells[24].innerHTML = '<div class="text-right">'+formatQuantity2(d23)+'</div>';
                nCells[25].innerHTML = '<div class="text-right">'+formatQuantity2(d24)+'</div>';
                nCells[26].innerHTML = '<div class="text-right">'+formatQuantity2(d25)+'</div>';
                nCells[27].innerHTML = '<div class="text-right">'+formatQuantity2(d26)+'</div>';
                nCells[28].innerHTML = '<div class="text-right">'+formatQuantity2(d27)+'</div>';
                nCells[29].innerHTML = '<div class="text-right">'+formatQuantity2(d28)+'</div>';
                nCells[30].innerHTML = '<div class="text-right">'+formatQuantity2(d29)+'</div>';
                nCells[31].innerHTML = '<div class="text-right">'+formatQuantity2(d30)+'</div>';*/
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
        <h2 class="blue"><i class="fa-fw fa fa-barcode"></i><?= lang('daily_products'); ?> 
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
		<!--
        <div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown">
                    <a href="#" id="pdf" class="tip" title="<?= lang('download_pdf') ?>">
                        <i class="icon fa fa-file-pdf-o"></i>
                    </a>
                </li>
                <li class="dropdown">
                    <a href="#" id="xls" class="tip" title="<?= lang('download_xls') ?>">
                        <i class="icon fa fa-file-excel-o"></i>
                    </a>
                </li>
                <li class="dropdown">
                    <a href="#" id="image" class="tip" title="<?= lang('save_image') ?>">
                        <i class="icon fa fa-file-picture-o"></i>
                    </a>
                </li>
            </ul>
        </div>
		-->
		
		<div class="box-icon">
            <ul class="btn-tasks">
                <?php if (!empty($warehouses)) { ?>
                    <li class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon fa fa-building-o tip" data-placement="left" title="<?= lang("warehouses") ?>"></i></a>
                        <ul class="dropdown-menu pull-right" class="tasks-menus" role="menu" aria-labelledby="dLabel">
                            <li><a href="<?= site_url('reports/product_daily_in_out') ?>"><i class="fa fa-building-o"></i> <?= lang('all_warehouses') ?></a></li>
                            <li class="divider"></li>
                            <?php
                            foreach ($warehouses as $warehouse) {
                               echo '<li><a href="' . site_url('reports/product_daily_in_out/' . $warehouse->id) . '"><i class="fa fa-building"></i>' . $warehouse->name . '</a></li>';
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
            <div class="col-lg-12" >

                <p class="introtext"><?= lang('customize_report'); ?></p>

                <div id="form">

                    <?php echo form_open("reports/product_daily_in_out"); ?>
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
								<?php
								$m = date('m');
								$y = date('Y');
								$d=cal_days_in_month(CAL_GREGORIAN,$m,$y);
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
						<div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("month", "month") ?>
                                <?php
                                $month = array(
                                    '01' => '01',
                                    '02' => '02',
                                    '03' => '03',
                                    '04' => '04',
                                    '05' => '05',
                                    '06' => '06',
                                    '07' => '07',
                                    '08' => '08',
                                    '09' => '09',
                                    '10' => '10',
                                    '11' => '11',
                                    '12' => '12'
                                );
                                echo form_dropdown('month', $month, (isset($_POST['month']) ? $_POST['month'] : $month), 'class="form-control select" id="month" placeholder="' . lang("select") . " " . lang("month") . '" style="width:100%"')
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

                <div class="table-responsive" id="style">
                    <table id="PrRData2" class="table table-striped table-bordered table-condensed table-hover dfTable reports-table"
                           style="margin-bottom:5px;">
                        <thead>
							<tr class="active">
								<th><?= lang("product_code"); ?></th>
								<th style="width:200px;"><?= lang("product_name"); ?></th>

								<?php
								foreach($list as $rws)
								{
									echo "<th style='width:150px'>" . lang($rws) . "</th>";
								}
								?>
							</tr>
                        </thead>
                        <tbody>
							<tr>
								<td colspan="6" class="dataTables_empty"><?= lang('loading_data_from_server') ?></td>
							</tr>
                        </tbody>
                        <tfoot class="dtFilter">
							<tr class="active">
								<th></th>
								<th></th>

								<?php
								foreach($list as $rws)
								{
									echo "<th>" . lang($rws) . "</th>";
								}
								?>
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
		    $('#style').css('width', '100%');
			$('#style').css('overflow-x', 'scroll');
			$('#style').css('white-space', 'nowrap');
		}
    });
</script>
