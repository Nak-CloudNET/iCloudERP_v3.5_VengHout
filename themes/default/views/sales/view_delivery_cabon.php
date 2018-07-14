<style type="text/css" media="all">
.receipt > thead > tr > th {
				font-size: 15px;
				background-color:#000 !important;color:#fff !important;
				-webkit-print-color-adjust: exact; 
				-moz-print-color-adjust: exact;
				-ms-print-color-adjust:exact;
				print-color-adjust:exact;
				color-adjust:exact;
				-webkit-color-adjust:exact;
				-moz-color-adjust:exact;
				-ms-color-adjust:exact;
				
			}
			</style>
<div class="modal-dialog modal-lg no-modal-header" style = "max-width: 480px !important; width: 100% !important; min-width: 250px !important; margin: 0 auto !important;">
    <div class="modal-content">
        <div class="modal-body">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
			<button type="button" class="btn btn-xs btn-default no-print pull-right" style="margin-right:15px;" onclick="window.print();">
                <i class="fa fa-print"></i> <?= lang('print'); ?>
            </button>
            <?php
                if($Settings->system_management == 'project'){ ?>
                    <div class="text-center" style="margin-bottom:20px;">
                        <img src="<?= base_url() . 'assets/uploads/logos/' . $Settings->logo2; ?>"
                             alt="<?= $Settings->site_name; ?>">
                    </div>
            <?php } else { ?>
                    <?php if ($logo) { ?>
                        <div class="text-center" style="margin-bottom:20px;">
                            <img src="<?= base_url() . 'assets/uploads/logos/' . $biller->logo; ?>"
                                 alt="<?= $biller->company != '-' ? $biller->company : $biller->name; ?>">
                        </div>
                    <?php } ?>
            <?php } ?>
            <div class="table-responsive">
                <table class="table table-bordered">

                    <tbody>
                    <tr>
                        <td width="30%"><?php echo $this->lang->line("date"); ?></td>
                        <td width="70%"><?php echo $this->erp->hrld(isset($delivery->date)); ?></td>
                    </tr>
                    <tr>
                        <td><?php echo $this->lang->line("do_reference_no"); ?></td>
                        <td><?php echo isset($delivery->do_reference_no); ?></td>
                    </tr>
					<tr>
                        <td><?php echo $this->lang->line("sale_reference_no"); ?></td>
                        <td><?php echo isset($delivery->sale_reference_no); ?></td>
                    </tr>
                    <tr>
                        <td><?php echo $this->lang->line("customer"); ?></td>
                        <td><?php echo isset($delivery->customer); ?></td>
                    </tr>
                    <tr>
                        <td><?php echo $this->lang->line("address"); ?></td>
                        <td><?php echo isset($delivery->address); ?></td>
                    </tr> 
                    <?php if (isset($delivery->note)) { ?>
                        <tr>
                            <td><?php echo $this->lang->line("note"); ?></td>
                            <td><?php echo $this->erp->decode_html(isset($delivery->note)); ?></td>
                        </tr>
                    <?php } ?>
                    </tbody>

                </table>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-condensed receipt">

                    <!-- <h3><?php echo $this->lang->line("items"); ?></h3> -->
                    <thead>

                    <tr style="font-weight:bold;">

                        <th style="text-align:center; vertical-align:middle;"><?php echo $this->lang->line("no"); ?></th>
                        <th style="vertical-align:middle;"><?php echo $this->lang->line("description"); ?></th>
						<th style="vertical-align:middle;"><?php echo $this->lang->line("price"); ?></th>
                        <th style="text-align:center; vertical-align:middle;"><?php echo $this->lang->line("quantity"); ?></th>
                    </tr>

                    </thead>

                    <tbody>

                    <?php $r = 1;
                    if(is_array($rows)){
                    foreach ($rows as $row): ?>
                        <tr>
                            <td style="text-align:center; width:40px; vertical-align:middle;"><?php echo $r; ?></td>
                            <td style="vertical-align:middle;"><?php echo isset($row->product_name) . " (" . $row->product_code . ")";
                                if (isset($row->details)) {
                                    echo '<br><strong>' . $this->lang->line("product_details") . '</strong> ' . html_entity_decode($row->details);
                                }
                                ?></td>
							<td style="width: 70px; text-align:center; vertical-align:middle;"><?php echo $this->erp->formatQuantity($row->price); ?></td>

                            <td style="width: 70px; text-align:center; vertical-align:middle;"><?php echo $this->erp->formatQuantity($row->quantity); ?></td>
                        </tr>
                        <?php
                        $r++;
                    endforeach;}
                    ?>
                    </tbody>
                </table>
            </div>
			<br/>
			<br/>
			<br/>
            <div class="row">
                <div class="col-xs-4">
                    <p style="height:100px;"><?= lang("prepared_by"); ?>
                        : <?= $user->first_name . ' ' . $user->last_name; ?> </p>
                    <hr style = "padding-top:30px";>
                </div>
                <div class="col-xs-4">
                    <p style="height:100px;"><?= lang("delivered_by"); ?>: </p>
                    <hr style = "padding-top:30px";>
                </div>
                <div class="col-xs-4">
                    <p style="height:100px;"><?= lang("received_by"); ?>: </p>
                    <hr style = "padding-top:30px";>  
                </div>
            </div>

        </div>
    </div>
</div>

