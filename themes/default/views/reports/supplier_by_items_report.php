<div class="row">
    <div class="col-sm-12">
        <div class="row">

        </div>
    </div>
</div>
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
        });
        </script>

        <div class="box">

   
		<?php
			$v = "&supplier=" . $user_id;

			if ($this->input->post('product')) {
				$v .= "&product=" . $this->input->post('product');
			}
			/*
			if ($this->input->post('category')) {
				$v .= "&category=" . $this->input->post('category');
			}
			*/
			if ($this->input->post('start_date')) {
				$v .= "&start_date=" . $this->input->post('start_date');
			}
			if ($this->input->post('end_date')) {
				$v .= "&end_date=" . $this->input->post('end_date');
			}
			if (isset($biller_id)) {
				$v .= "&biller_id=" . $biller_id;
			}
		?>
		<script>
			$(document).ready(function () {
				function spb(x) {
					v = x.split('__');
					return '('+formatQuantity2(v[0])+') <strong>'+formatMoney(v[1])+'</strong>';
				}
				function checkbox(x) {
					return '<center><input class="checkbox multi-select" type="checkbox" name="val[]" value="' + x + '" /></center>';
				}
				var oTable = $('#PrData').dataTable({
					"aaSorting": [[3, "desc"], [2, "desc"]],
					"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
					"iDisplayLength": <?= $Settings->rows_per_page ?>,
					'bProcessing': true, 'bServerSide': true,
					'sAjaxSource': '<?= site_url('reports/getPurchasedSupplierItemsReport/?v=1'.$v) ?>',
					'fnServerData': function (sSource, aoData, fnCallback) {
						aoData.push({
							"name": "<?= $this->security->get_csrf_token_name() ?>",
							"value": "<?= $this->security->get_csrf_hash() ?>"
						});
						$.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
					},
					"aoColumns": [/*{"bVisible": false},*/null,null, null, {"mRender": formatQuantity2}, {"mRender": formatQuantity2},{"mRender": formatQuantity2}],
					"fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {
						var  sa = 0, ba = 0, ga = 0;
						for (var i = 0; i < aaData.length; i++) {
							
							sa += parseFloat(aaData[aiDisplay[i]][3]);
							ba += parseFloat( aaData[aiDisplay[i]][4]);
							ga += parseFloat(aaData[aiDisplay[i]][5]);
							//pl += parseFloat(aaData[aiDisplay[i]][5]);
						}
						var nCells = nRow.getElementsByTagName('th');
						nCells[3].innerHTML =formatMoney(sa);
						nCells[4].innerHTML = currencyFormat(parseFloat(ba));
						nCells[5].innerHTML =formatMoney(ga);

						//nCells[6].innerHTML = '<div class="text-right">('+formatQuantity2(bq)+') '+formatMoney(ba)+'</div>';
					}
				}).fnSetFilteringDelay().dtFilter([
                    {column_number: 0, filter_default_label: "[<?=lang('date');?>]", filter_type: "text", data: []},
                    {column_number: 1, filter_default_label: "[<?=lang('reference_no');?>]", filter_type: "text", data: []},
					{column_number: 2, filter_default_label: "[<?=lang('supplier');?>]", filter_type: "text", data: []}
				], "footer");
			});
		</script>
		<script type="text/javascript">
			$(document).ready(function () {
				$('#productform').hide();
				$('.paytoggle_down').click(function () {
					$("#productform").slideDown();
					return false;
				});
				$('.paytoggle_up').click(function () {
					$("#productform").slideUp();
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
			<div class="box-header">
				<h2 class="blue"><i class="fa fa-barcode"></i><?= lang('products_report'); ?> <?php
				if ($this->input->post('start_date')) {
					echo "From " . $this->input->post('start_date') . " to " . $this->input->post('end_date');
				}
				?></h2>

				<div class="box-icon">
					<ul class="btn-tasks">
						<li class="dropdown">
							<a href="#" class="paytoggle_up tip" title="<?= lang('hide_form') ?>">
								<i class="icon fa fa-toggle-up"></i>
							</a>
						</li>
						<li class="dropdown">
							<a href="#" class="paytoggle_down tip" title="<?= lang('show_form') ?>">
								<i class="icon fa fa-toggle-down"></i>
							</a>
						</li>
					</ul>
				</div>
				<div class="box-icon">
					<ul class="btn-tasks">
						<li class="dropdown">
							<a href="#" id="pdf1" class="tip" title="<?= lang('download_pdf') ?>">
								<i class="icon fa fa-file-pdf-o"></i>
							</a>
						</li>
						<li class="dropdown">
							<a href="#" id="xls1" class="tip" title="<?= lang('download_xls') ?>">
								<i class="icon fa fa-file-excel-o"></i>
							</a>
						</li>
						<li class="dropdown">
						<a data-toggle="dropdown" class="dropdown-toggle" href="#"><i
								class="icon fa fa-building-o tip" data-placement="left"
								title="<?= lang("billers") ?>"></i></a>
						<ul class="dropdown-menu pull-right" class="tasks-menus" role="menu"
							aria-labelledby="dLabel">
							<li><a href="<?= site_url('reports/supplier_by_items_report/' . $user_id) ?>"><i
										class="fa fa-building-o"></i> <?= lang('billers') ?></a></li>
							<li class="divider"></li>
							<?php
							foreach ($billers as $biller) {
								echo '<li ' . ($biller_id && $biller_id == $biller->id ? 'class="active"' : '') . '><a href="' . site_url('reports/supplier_by_items_report/' . $user_id . '/' . $biller->id) . '"><i class="fa fa-building"></i>' . $biller->company . '</a></li>';
							}
							?>
						</ul>
					</li>
					</ul>
				</div>
				
			</div>
			
			<div class="box-content">
				<div class="row">
					<div class="col-lg-12">

						<p class="introtext"><?= lang('customize_report'); ?></p>

						<div id="productform">

							<?php echo form_open("reports/supplier_by_items_report/" . $user_id); ?>
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
									<!--
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
									-->
									<div class="col-sm-4">
										<div class="form-group">
											<?= lang("start_date", "start_date"); ?>
											<?php echo form_input('start_date', (isset($_POST['start_date']) ? $_POST['start_date'] : ""), 'class="form-control datetime" id="start_date"'); ?>
										</div>
									</div>
									<div class="col-sm-4">
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
							<table id="PrData"
								   class="table table-striped table-bordered table-condensed table-hover dfTable reports-table"
								   style="margin-bottom:5px;">
								<thead>
									<tr class="active">
									    <th><?= lang("date"); ?></th>
									    <th><?= lang("reference_no"); ?></th>
									    <th><?= lang("supplier"); ?></th>
										<th><?= lang("grand_total"); ?></th>
										<th><?= lang("paid"); ?></th>
										<th><?= lang("balance"); ?></th>
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
	</div>

	<script type="text/javascript" src="<?= $assets ?>js/html2canvas.min.js"></script>
	
	<script type="text/javascript">
		$(document).ready(function () {
			$('#pdf').click(function (event) {
				event.preventDefault();
				window.location.href = "<?=site_url('reports/getPurchasedSupplierItemsReport/pdf/?v=1'.$v)?>";
				return false;
			});
			$('#xls').click(function (event) {
				event.preventDefault();
				window.location.href = "<?=site_url('reports/getPurchasedSupplierItemsReport/0/xls/?v=1'.$v)?>";
				return false;
			});
			$('#image').click(function (event) {
				event.preventDefault();
				html2canvas($('.purchases-table'), {
					onrendered: function (canvas) {
						var img = canvas.toDataURL()
						window.open(img);
					}
				});
				return false;
			});
			$('#pdf1').click(function (event) {
				event.preventDefault();
				window.location.href = "<?=site_url('reports/getPurchasedSupplierItemsReport/pdf/?v=1'.$p)?>";
				return false;
			});
			$('#xls1').click(function (event) {
				event.preventDefault();
				window.location.href = "<?=site_url('reports/getPurchasedSupplierItemsReport/0/xls/?v=1'.$p)?>";
				return false;
			});
			$('#image1').click(function (event) {
				event.preventDefault();
				html2canvas($('.payments-table'), {
					onrendered: function (canvas) {
						var img = canvas.toDataURL()
						window.open(img);
					}
				});
				return false;
			});
		});
	</script>
