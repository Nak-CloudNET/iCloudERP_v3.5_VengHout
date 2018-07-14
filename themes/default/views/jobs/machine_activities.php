<?php
	$v = "";
	/* if($this->input->post('name')){
	  $v .= "&product=".$this->input->post('product');
	  } */
	if ($this->input->post('reference_no')) {
		$v .= "&reference_no=" . $this->input->post('reference_no');
	}
	if ($this->input->post('biller')) {
		$v .= "&biller=" . $this->input->post('biller');
	}
	if ($this->input->post('user')) {
		$v .= "&user=" . $this->input->post('user');
	}
	if ($this->input->post('note')) {
		$v .= "&note=" . $this->input->post('note');
	}
	if ($this->input->post('start_date')) {
		$v .= "&start_date=" . $this->input->post('start_date');
	}
	if ($this->input->post('end_date')) {
		$v .= "&end_date=" . $this->input->post('end_date');
	}
	if(isset($date)){
		$v .= "&d=" . $date;
	}
?>
<script>
    $(document).ready(function () {
        function attachment(x) {
            if (x != null) {
                return '<a href="' + site.base_url + 'assets/uploads/' + x + '" target="_blank"><i class="fa fa-chain"></i></a>';
            }
            return x;
        }

        var oTable = $('#EXPData').dataTable({
            "aaSorting": [[1, "desc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
            "iDisplayLength": <?= $Settings->rows_per_page ?>,
            'bProcessing': true, 'bServerSide': true,
            'sAjaxSource': '<?= site_url('jobs/get_machine_activities').'/?v=1'.$v; ?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "<?= $this->security->get_csrf_token_name() ?>",
                    "value": "<?= $this->security->get_csrf_hash() ?>"
                });
                $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
            },
            "aoColumns": [{
                "bSortable": false,
                "mRender": checkbox
            }, {"mRender": fld}, null, null, null, null, null, {"mRender": row_status}, {"bSortable": false}],
            'fnRowCallback': function (nRow, aData, iDisplayIndex) {
                var oSettings = oTable.fnSettings();
                nRow.id = aData[0];
                nRow.className = "expense_link";
                return nRow;
            },
            "fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {
                var total = 0;
                for (var i = 0; i < aaData.length; i++) {
                    //total += parseFloat(aaData[aiDisplay[i]][4]);
                }
                //var nCells = nRow.getElementsByTagName('th');
                //nCells[4].innerHTML = formatPurDecimal(total);
            }
        }).fnSetFilteringDelay().dtFilter([
            {column_number: 1, filter_default_label: "[<?=lang('date');?> (yyyy-mm-dd)]", filter_type: "text", data: []},
            {column_number: 2, filter_default_label: "[<?=lang('reference');?>]", filter_type: "text", data: []},
			{column_number: 3, filter_default_label: "[<?=lang('customer_name');?>]", filter_type: "text", data: []},
			{column_number: 4, filter_default_label: "[<?=lang('product_name');?>]", filter_type: "text", data: []},
            {column_number: 5, filter_default_label: "[<?=lang('quantity');?>]", filter_type: "text", data: []},
            {column_number: 6, filter_default_label: "[<?=lang('developed_quantity');?>]", filter_type: "text", data: []},
			{column_number: 7, filter_default_label: "[<?=lang('status');?>]", filter_type: "text", data: []},
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
    });
</script>
<?php if ($Owner) {
    //echo form_open('jobs/jobs_actions', 'id="action-form"');
} ?>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa fa-barcode"></i><?= lang('jobs'); ?></h2>
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
                    <a href="#" id="image" class="tip" title="<?= lang('save_image') ?>">
                        <i class="icon fa fa-file-picture-o"></i>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">
                <p class="introtext"><?= lang('list_results'); ?></p>
				<div id="form">

                    <?php echo form_open("jobs/marchine_activities"); ?>
                    <div class="row">
                        
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
                        <div class="controls"> <?php echo form_submit('submit_report', $this->lang->line("submit"), 'class="btn btn-primary"'); ?> </div>
                    </div>
                    <?php echo form_close(); ?>

                </div>
                <div class="table-responsive">
                    <table id="EXPData__" cellpadding="0" cellspacing="0" border="0"
                           class="table table-bordered table-hover table-striped">
                        <thead>
							<tr class="active">
								<th style="min-width:30px; width: 30px; text-align: center;">
									<input class="checkbox checkft" type="checkbox" name="check"/>
								</th>
								<th class="col-xs-2"><?php echo $this->lang->line("ទំហំ"); ?></th>
								<th class="col-xs-2"><?php echo $this->lang->line("ចំនួនផ្តិត"); ?></th>
								<th class="col-xs-2"><?php echo $this->lang->line("ចំនួនខូច"); ?></th>
								<th class="col-xs-2"><?php echo $this->lang->line("អ៊ីនឌិច"); ?></th>
								<th class="col-xs-2"><?php echo $this->lang->line("ចំនួនផ្តិតសរុប"); ?></th>
								<th class="col-xs-2"><?php echo $this->lang->line("ទំហំក្រដាស់(ម)"); ?></th>
							</tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($this->input->POST('start_date')) {
                                $start_date = $this->input->POST('start_date');
                            } else {
                                $start_date = NULL;
                            }
                            if ($this->input->POST('end_date')) {
                                $end_date = $this->input->POST('end_date');
                            } else {
                                $end_date = NULL;
                            }
                            if ($start_date) {
                                $start_date = $this->erp->fld($start_date);
                                $end_date = $this->erp->fld($end_date);
                            }
                            $wheres = "";
                            if ($start_date && $start_date != "0000-00-00 00:00:00") {
                                $wheres = " and s.created_at > '$start_date' ";
                            }
                            if ($end_date && $end_date != "0000-00-00 00:00:00") {
                                $wheres = ($wheres != "" ? $wheres . " and s.created_at < '$end_date' " : $wheres);
                            }

                            $sdv = $this->db;
                                $sdv->select("s.cf1, s.cf2, s.quantity, s.quantity_index, s.quantity_break, s.quantity_index, (s.quantity + s.quantity_index + s.quantity_break ) as totalqty, s.quantity, s.product_name")->from('sale_dev_items s');
                                if($wheres != "") {
                                    $sdv->where( '1=1' . $wheres);
                                }
                                $sdv = $sdv->get()->result();
                            $i      = 01;
                            $tq     = 0;
                            $tb     = 0;
                            $tin    = 0;
                            $tqua     = 0;
                            foreach ($sdv as $rows) {
                               ?>
                                <tr class="active">
                                    <th style="min-width:30px; width: 30px; text-align: center;">
                                        <?=$i++?>
                                    </th>
                                    <th><?=$rows->cf1 . 'x' . $rows->cf2?></th>
                                    <th><?= number_format($rows->quantity);?></th>
                                    <th><?= number_format($rows->quantity_break);?></th>
                                    <th><?= number_format($rows->quantity_index); ?></th>
                                    <th><?= number_format($rows->totalqty); ?></th>
                                    <th><?= number_format($rows->cf2*$rows->totalqty); ?></th>
                                </tr>
                               <?php
                               $tq += $rows->quantity;
                               $tb += $rows->quantity_break;
                               $tin += $rows->quantity_index;
                               $tqua += $rows->totalqty;
                            }
                            ?>
                            <tr>
                                <td><?php echo $this->lang->line("សរុប"); ?></td>

                                <th></th>
                                <th><?=$tq?></th>
                                <th><?=$tb?></th>
                                <th><?=$tin?></th>
                                <th><?=$tqua?></th>
                                <th></th>
                            </tr>
							<!-- <tr>
								<td colspan="8" class="dataTables_empty"><?= lang('loading_data_from_server'); ?></td>
							</tr> -->
                        </tbody>
                        <tfoot class="dtFilter">
							<tr class="active">
								<th style="min-width:30px; width: 30px; text-align: center;">
									<input class="checkbox checkft" type="checkbox" name="check"/>
								</th>
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

                <div class="table-responsive">
                    <table id="EXPData__" cellpadding="0" cellspacing="0" border="0"
                           class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <td><?=$this->lang->line("ឈ្មោះម៉ាស៊ីន"); ?></td>
                                <td><?=$this->lang->line("លេខថ្មី"); ?></td>
                                <td><?=$this->lang->line("លេខចាស់"); ?></td>
                                <td><?=$this->lang->line("ចំនួនផ្ទិតបាន"); ?></td>
                                <td><?=$this->lang->line("13(ម)"); ?></td>
                                <td><?=$this->lang->line("15(ម)"); ?></td>
                                <td><?=$this->lang->line("25(ម)"); ?></td>
                                <td><?=$this->lang->line("30(ម)"); ?></td>
                                <td><?=$this->lang->line("50(ម)"); ?></td>
                                <td><?=$this->lang->line("60(ម)"); ?></td>
                                <td><?=$this->lang->line("76(ម)"); ?></td>
                                <td><?=$this->lang->line("80(ម)"); ?></td>
                                <td><?=$this->lang->line("100(ម)"); ?></td>
                                <td><?=$this->lang->line("120(ម)"); ?></td>
                                <td><?=$this->lang->line("150(ម)"); ?></td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            
                            $marchine = $this->db->select("m.name, m.description, m.type, m.name,
                                    (SELECT IFNULL(sum(s.quantity), 0) FROM erp_sale_dev_items s 
                                        WHERE cf1 = 13 and s.category_name = m.name $wheres) as t13,
                                    (SELECT IFNULL(sum(s.cf2), 0) FROM erp_sale_dev_items s 
                                        WHERE cf1 = 13 and s.category_name = m.name $wheres) as t131,

                                    (SELECT IFNULL(sum(s.quantity), 0) FROM erp_sale_dev_items s WHERE cf1 = 15 and s.category_name = m.name $wheres) as t15,
                                    (SELECT IFNULL(sum(s.cf2), 0) FROM erp_sale_dev_items s WHERE cf1 = 15 and s.category_name = m.name $wheres) as t151,

                                    (SELECT IFNULL(sum(s.quantity), 0) FROM erp_sale_dev_items s WHERE cf1 = 25 and s.category_name = m.name $wheres) as t25,
                                    (SELECT IFNULL(sum(s.cf2), 0) FROM erp_sale_dev_items s WHERE cf1 = 25 and s.category_name = m.name $wheres) as t251,

                                    (SELECT IFNULL(sum(s.quantity), 0) FROM erp_sale_dev_items s WHERE cf1 = 30 and s.category_name = m.name $wheres) as t30,
                                    (SELECT IFNULL(sum(s.cf2), 0) FROM erp_sale_dev_items s WHERE cf1 = 30 and s.category_name = m.name $wheres) as t301,

                                    (SELECT IFNULL(sum(s.quantity), 0) FROM erp_sale_dev_items s WHERE cf1 = 50 and s.category_name = m.name $wheres) as t50,
                                    (SELECT IFNULL(sum(s.cf2), 0) FROM erp_sale_dev_items s WHERE cf1 = 50 and s.category_name = m.name $wheres) as t501,

                                    (SELECT IFNULL(sum(s.quantity), 0) FROM erp_sale_dev_items s WHERE cf1 = 60 and s.category_name = m.name $wheres) as t60,
                                    (SELECT IFNULL(sum(s.cf2), 0) FROM erp_sale_dev_items s WHERE cf1 = 60 and s.category_name = m.name $wheres) as t601,

                                    (SELECT IFNULL(sum(s.quantity), 0) FROM erp_sale_dev_items s WHERE cf1 = 76 and s.category_name = m.name $wheres) as t76,
                                    (SELECT IFNULL(sum(s.cf2), 0) FROM erp_sale_dev_items s WHERE cf1 = 76 and s.category_name = m.name $wheres) as t761,

                                    (SELECT IFNULL(sum(s.quantity), 0) FROM erp_sale_dev_items s WHERE cf1 = 80 and s.category_name = m.name $wheres) as t80,
                                    (SELECT IFNULL(sum(s.cf2), 0) FROM erp_sale_dev_items s WHERE cf1 = 80 and s.category_name = m.name $wheres) as t801,

                                    (SELECT IFNULL(sum(s.quantity), 0) FROM erp_sale_dev_items s WHERE cf1 = 100 and s.category_name = m.name $wheres) as t100,
                                    (SELECT IFNULL(sum(s.cf2), 0) FROM erp_sale_dev_items s WHERE cf1 = 100 and s.category_name = m.name $wheres) as t1001,

                                    (SELECT IFNULL(sum(s.quantity), 0) FROM erp_sale_dev_items s WHERE cf1 = 120 and s.category_name = m.name $wheres) as t120,
                                    (SELECT IFNULL(sum(s.cf2), 0) FROM erp_sale_dev_items s WHERE cf1 = 120 and s.category_name = m.name $wheres) as t1201,

                                    (SELECT IFNULL(sum(s.quantity), 0) FROM erp_sale_dev_items s WHERE cf1 = 150 and s.category_name = m.name $wheres) as t150,
                                    (SELECT IFNULL(sum(s.cf2), 0) FROM erp_sale_dev_items s WHERE cf1 = 150 and s.category_name = m.name $wheres) as t1501")
                                    ->from('marchine m')->get()->result();
                            $i = 01;
                            foreach ($marchine as $rows) {
                               ?>
                                <tr class="active">
                                    <th><?=$rows->name?></th>
                                    <th><?=$rows->description?></th>
                                    <th><?=$rows->type?></th>
                                    <th><?=($rows->t13+$rows->t15+$rows->t25+$rows->t30+$rows->t50+$rows->t60+$rows->t76+$rows->t80+$rows->t100+$rows->t120+$rows->t150)?></th>
                                    <th><?=$rows->t13 . " [" . $rows->t131 . "]"?></th>
                                    <th><?=$rows->t15 . " [" . $rows->t151 . "]"?></th>
                                    <th><?=$rows->t25 . " [" . $rows->t251 . "]"?></th>
                                    <th><?=$rows->t30 . " [" . $rows->t301 . "]"?></th>
                                    <th><?=$rows->t50 . " [" . $rows->t501 . "]"?></th>
                                    <th><?=$rows->t60 . " [" . $rows->t601 . "]"?></th>
                                    <th><?=$rows->t76 . " [" . $rows->t761 . "]"?></th>
                                    <th><?=$rows->t80 . " [" . $rows->t801 . "]"?></th>
                                    <th><?=$rows->t100 . " [" . $rows->t1001 . "]"?></th>
                                    <th><?=$rows->t120 . " [" . $rows->t1201 . "]"?></th>
                                    <th><?=$rows->t150 . " [" . $rows->t1501 . "]"?></th>
                                </tr>
                               <?php 
                            }
                            ?>
                            <!-- <tr>
                                <td colspan="8" class="dataTables_empty"><?= lang('loading_data_from_server'); ?></td>
                            </tr> -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php if ($Owner) { ?>
    <div style="display: none;">
        <input type="hidden" name="form_action" value="" id="form_action"/>
        <?= form_submit('performAction', 'performAction', 'id="action-form-submit"') ?>
    </div>
    <?= form_close() ?>
<?php } ?>

<script type="text/javascript" src="<?= $assets ?>js/html2canvas.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#pdf').click(function (event) {
            event.preventDefault();
            window.location.href = "<?=site_url('jobs/marchine_activities/pdf')?>";
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
    });
</script>


