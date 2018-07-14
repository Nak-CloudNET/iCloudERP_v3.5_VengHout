<style type="text/css">
	#SLData { white-space: nowrap;}
</style>
<?php
function row_status($x){
	if($x == 'completed' || $x == 'paid' || $x == 'sent' || $x == 'received' || $x == 'deposit') {
		return '<div class="text-center"><span class="label label-success">'.lang($x).'</span></div>';
	}elseif($x == 'pending' || $x == 'book' || $x == 'free'){
		return '<div class="text-center"><span class="label label-warning">'.lang($x).'</span></div>';
	}elseif($x == 'partial' || $x == 'transferring' || $x == 'ordered'  || $x == 'busy'  || $x == 'processing'){
		return '<div class="text-center"><span class="label label-info">'.lang($x).'</span></div>';
	}elseif($x == 'due' || $x == 'returned' || $x == 'regular'){
		return '<div class="text-center"><span class="label label-danger">'.lang($x).'</span></div>';
	}else{
		return '<div class="text-center"><span class="label label-default">'.lang($x).'</span></div>';
	}
}
?>
<div class="modal-dialog modal-lg no-modal-header">
    <div class="modal-content">
        <div class="modal-body">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                <i class="fa fa-2x">&times;</i>
            </button>
            <button type="button" class="btn btn-xs btn-default no-print pull-right" style="margin-right:15px;" onclick="window.print();">
                <i class="fa fa-print"></i> <?= lang('print'); ?>
            </button>

            <?php
                if ($Settings->system_management == 'project') { ?>
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
                <div class="well well-sm">
                    <div class="row bold" style="font-size:12px;">
                        <div class="col-xs-5">
                            <p class="bold">
                                <?= lang("ref"); ?>: <?= $inv->reference_no; ?><br>
                                <?= lang("date"); ?>: <?= $this->erp->hrld($inv->date); ?><br>
                                <?= lang("sale_status"); ?>: <?= lang($inv->sale_status); ?><br>
                                <?= lang("payment_status"); ?>: <?= lang($inv->payment_status); ?>
                            </p>
                        </div>
                        <div class="col-xs-7 text-right">
                          <p style="font-size:16px; margin:0 !important;"><?= lang("INVOICE"); ?></p>
                          <?php $br = $this->erp->save_barcode($inv->reference_no, 'code39', 70, false); ?>
                          <img height="45px" src="<?= base_url() ?>assets/uploads/barcode<?= $this->session->userdata('user_id') ?>.png"
                          alt="<?= $inv->reference_no ?>"/>
                          <?php $this->erp->qrcode('link', urlencode(site_url('sales/view/' . $inv->id)), 2); ?>
                          <img height="45px" src="<?= base_url() ?>assets/uploads/qrcode<?= $this->session->userdata('user_id') ?>.png"
                          alt="<?= $inv->reference_no ?>"/>
                      </div>
                      <div class="clearfix"></div>
                  </div>
                  <div class="clearfix"></div>
              </div>

              <div class="row" style="margin-bottom:15px;">
                <div class="col-xs-6">
                    <?php echo $this->lang->line("from"); ?>:
                    <?php if ($Settings->system_management == 'project') { ?>
                        <h2 style="margin-top:10px;"><?= $Settings->site_name; ?></h2>
                    <?php } else { ?>
                        <h2 style="margin-top:10px;"><?= $biller->company != '-' ? $biller->company : $biller->name; ?></h2>
                    <?php } ?>
                    <?= $biller->company ? "" : "Attn: " . $biller->name ?>

                    <?php
                    echo $biller->address . "<br>" . $biller->city . " " . $biller->postal_code . " " . $biller->state . "<br>" . $biller->country;

                    echo lang("tel") . ": " . $biller->phone . "<br>" . lang("email") . ": " . $biller->email;
                    ?>
                </div>
                <div class="col-xs-6">
                    <?php echo $this->lang->line("to"); ?>:<br/>
                    <h2 style="margin-top:10px;"><?= $customer->company ? $customer->company : $customer->name; ?></h2>
                    <?= $customer->company ? "" : "Attn: " . $customer->name ?>

                    <?php
                    echo $customer->address . "<br>" . $customer->city . " " . $customer->postal_code . " " . $customer->state . "<br>" . $customer->country;

                    echo "<p>";

                    if ($customer->cf1 != "-" && $customer->cf1 != "") {
                        echo "<br>" . lang("ccf1") . ": " . $customer->cf1;
                    }
                    if ($customer->cf2 != "-" && $customer->cf2 != "") {
                        echo "<br>" . lang("ccf2") . ": " . $customer->cf2;
                    }
                    if ($customer->cf3 != "-" && $customer->cf3 != "") {
                        echo "<br>" . lang("ccf3") . ": " . $customer->cf3;
                    }
                    if ($customer->cf4 != "-" && $customer->cf4 != "") {
                        echo "<br>" . lang("ccf4") . ": " . $customer->cf4;
                    }
                    if ($customer->cf5 != "-" && $customer->cf5 != "") {
                        echo "<br>" . lang("ccf5") . ": " . $customer->cf5;
                    }
                    if ($customer->cf6 != "-" && $customer->cf6 != "") {
                        echo "<br>" . lang("ccf6") . ": " . $customer->cf6;
                    }

                    echo "</p>";
                    echo lang("tel") . ": " . $customer->phone . "<br>" . lang("email") . ": " . $customer->email;
                    ?>
                </div>
            </div>

            <div class="table-responsive">
                <table id="SLData" class="table table-bordered table-condensed table-hover table-striped">
                    <thead>
                        <tr>
                            <th><?php echo $this->lang->line("date"); ?></th>
                            <th><?php echo $this->lang->line("reference_no"); ?></th>
                            <th><?php echo $this->lang->line("customer"); ?></th>
                            <th><?php echo $this->lang->line("sale_status"); ?></th>
                            <th><?php echo $this->lang->line("grand_total"); ?></th>
                            <th><?php echo $this->lang->line("paid"); ?></th>
                            <th><?php echo $this->lang->line("balance"); ?></th>
                            <th><?php echo $this->lang->line("payment_status"); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $this->db
                        ->select("id, date, reference_no, biller, customer, sale_status, grand_total, paid, (grand_total-paid) as balance, payment_status")
                        ->from('sales')
                        ->where('payment_status !=', 'Returned')
                        ->where('payment_status !=', 'paid')
                        ->where('(grand_total-paid) <> ', 0)
                        ->where('customer_id', $cust_id);
                        
                        if($type_view == 'ar_0_30'){
                            $this->db->where('date(date) > CURDATE() AND date(date) <= DATE_ADD(now(), INTERVAL + 30 DAY)');

                        } else if ($type_view == 'ar_30_60'){
                            $this->db->where('date(date) > DATE_ADD(now(), INTERVAL + 30 DAY) AND date(date) <= DATE_ADD(now(), INTERVAL + 60 DAY)');

                        } else if ($type_view == 'ar_60_90'){
                            $this->db->where('date(date) > DATE_ADD(now(), INTERVAL + 60 DAY) AND date(date) <= DATE_ADD(now(), INTERVAL + 90 DAY)');

                        } else if ($type_view == 'ar_90_over'){
                            $this->db->where('date(date) >= DATE_ADD(now(), INTERVAL + 90 DAY)');

                        } else {
                            $this->db->where('DATE_SUB(date, INTERVAL 1 DAY) <= CURDATE()');
                        }
                        
                        $ar_aping = $this->db->get()->result();

                        $grandTotal = 0;
                        $paidTotal = 0;
                        $balanceTotal = 0;
                        if(count($ar_aping) > 0):
                            foreach ($ar_aping as $rws) {
                                echo '<tr class="link_ar_to" id="' . $rws->id . '">';
                                echo '<td>' . $rws->date . '</td>';
                                echo '<td>' . $rws->reference_no . '</td>';
                                echo '<td>' . $rws->customer . '</td>';
                                echo '<td>' . row_status($rws->sale_status) . '</td>';
                                echo '<td>' . $this->erp->formatMoney($rws->grand_total) . '</td>';
                                echo '<td>' . $this->erp->formatMoney($rws->paid) . '</td>';
                                echo '<td>' . $this->erp->formatMoney($rws->balance) . '</td>';
                                echo '<td>' . row_status($rws->payment_status) . '</td>';
                                echo '</tr>';
                                $grandTotal += $rws->grand_total;
                                $paidTotal += $rws->paid;
                                $balanceTotal += $rws->balance;
                            }
                            else:
                                ?>
                            <tr>
                                <td colspan="9"
                                class="dataTables_empty"><?php echo $this->lang->line("loading_data"); ?></td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                    <tfoot class="dtFilter">
                    <tr class="active">
                            <th><?php echo $this->lang->line("date"); ?></th>
                            <th><?php echo $this->lang->line("reference_no"); ?></th>                            
                            <th><?php echo $this->lang->line("customer"); ?></th>
                            <th><?php echo $this->lang->line("sale_status"); ?></th>
                            <th><?php echo $grandTotal; ?></th>
                            <th><?php echo $paidTotal; ?></th>
                            <th><?php echo $balanceTotal; ?></th>
                            <th><?php echo $this->lang->line("payment_status"); ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <?php
                    if ($inv->note || $inv->note != "") { ?>
                        <div class="well well-sm">
                            <p class="bold"><?= lang("note"); ?>:</p>
                            <div><?= $this->erp->decode_html($inv->note); ?></div>
                        </div>
                        <?php
                    }
                    if ($inv->staff_note || $inv->staff_note != "") { ?>
                        <div class="well well-sm staff_note">
                            <p class="bold"><?= lang("staff_note"); ?>:</p>
                            <div><?= $this->erp->decode_html($inv->staff_note); ?></div>
                        </div>
                        <?php } ?>
                    </div>
                    <br/>

                    <br/>
                    <div class="row">
                     <div class="clearfix"></div>
                     <div class="col-xs-3  pull-left" style="text-align:center">
                      <hr/>
                      <p><?= lang("seller"); ?>
                       <!--: <?= $biller->company != '-' ? $biller->company : $biller->name; ?> --></p>
                       <!--<p><?= lang("stamp_sign"); ?></p>-->
                   </div>
                   <div class="col-xs-3  pull-right" style="text-align:center">
                      <hr/>
                      <p><?= lang("customer"); ?>
                         <!-- : <?= $customer->company ? $customer->company : $customer->name; ?> --></p>
                         <!--<p><?= lang("stamp_sign"); ?></p>-->
                     </div>
                     <div class="col-xs-3  pull-right" style="text-align:center">
                      <hr/>
                      <p><?= lang("Account"); ?>
                       <!--: <?= $customer->company ? $customer->company : $customer->name; ?>--> </p>
                       <!--<p><?= lang("stamp_sign"); ?></p>-->
                   </div>
                   <div class="col-xs-3  pull-right" style="text-align:center">
                      <hr/>
                      <p><?= lang("Ware House"); ?>
                       <!--: <?= $warehouse->company ? $warehouse->company : $warehouse->name; ?>--> </p>
                       <!--<p><?= lang("stamp_sign"); ?></p>-->
                   </div>
               </div>
               <div class="col-xs-5 pull-right no-print" >
                <div class="well well-sm">
                    <p>
                        <?= lang("created_by"); ?>: <?= $created_by->first_name . ' ' . $created_by->last_name; ?> <br>
                        <?= lang("date"); ?>: <?= $this->erp->hrld($inv->date); ?>
                    </p>
                    <?php if ($inv->updated_by) { ?>
                        <p>
                            <?= lang("updated_by"); ?>: <?= $updated_by->first_name . ' ' . $updated_by->last_name;; ?><br>
                            <?= lang("update_at"); ?>: <?= $this->erp->hrld($inv->updated_at); ?>
                        </p>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <?php if (!$Supplier || !$Customer) { ?>
                <div class="buttons">
                    <div class="btn-group btn-group-justified">
                      <div class="btn-group">
                        <a href="<?= site_url('sales/tax_invoice/' . $inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('tax_invoice') ?>">
                            <i class="fa fa-print"></i>
                            <span class="hidden-sm hidden-xs"><?= lang('print_tax_invoice') ?></span>
                        </a>
                    </div>
					<!--
                    <div class="btn-group">
                        <a href="<?= site_url('sales/print_hch/' . $inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('Print_HCH_Invoice') ?>">
                            <i class="fa fa-print"></i>
                            <span class="hidden-sm hidden-xs"><?= lang('Print_HCH_Invoice') ?></span>
                        </a>
                    </div>
					-->
                    <div class="btn-group">
                        <a href="<?= site_url('sales/invoice/' . $inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('invoice') ?>">
                            <i class="fa fa-print"></i>
                            <span class="hidden-sm hidden-xs"><?= lang('invoice') ?></span>
                        </a>
                    </div>
                    <!--
                    <div class="btn-group">
                       <a href="<?=base_url()?>sales/cabon_print/<?=$inv->id?>" target="_blank" class="tip btn btn-primary" title="<?= lang('print_cabon') ?>">
                        <i class="fa fa-print"></i>
                        <span class="hidden-sm hidden-xs"><?= lang('print_cabon') ?></span>
                    </a>
                    -->
                </div>

                <div class="btn-group">
                    <a href="<?= site_url('sales/view/' . $inv->id) ?>" class="tip btn btn-primary" title="<?= lang('view') ?>">
                        <i class="fa fa-file-text-o"></i>
                        <span class="hidden-sm hidden-xs"><?= lang('view') ?></span>
                    </a>
                </div>
                <?php if ($inv->attachment) { ?>
                    <div class="btn-group">
                        <a href="<?= site_url('welcome/download/' . $inv->attachment) ?>" class="tip btn btn-primary" title="<?= lang('attachment') ?>">
                            <i class="fa fa-chain"></i>
                            <span class="hidden-sm hidden-xs"><?= lang('attachment') ?></span>
                        </a>
                    </div>
                    <?php } ?>
                    <div class="btn-group">
                        <a href="<?= site_url('sales/email/' . $inv->id) ?>" data-toggle="modal" data-target="#myModal2" class="tip btn btn-primary" title="<?= lang('email') ?>">
                            <i class="fa fa-envelope-o"></i>
                            <span class="hidden-sm hidden-xs"><?= lang('email') ?></span>
                        </a>
                    </div>
                    <div class="btn-group">
                        <a href="<?= site_url('sales/pdf/' . $inv->id) ?>" class="tip btn btn-primary" title="<?= lang('download_pdf') ?>">
                            <i class="fa fa-download"></i>
                            <span class="hidden-sm hidden-xs"><?= lang('pdf') ?></span>
                        </a>
                    </div>
                    <div class="btn-group">
                        <a href="<?= site_url('sales/edit/' . $inv->id) ?>" class="tip btn btn-warning sledit" title="<?= lang('edit') ?>">
                            <i class="fa fa-edit"></i>
                            <span class="hidden-sm hidden-xs"><?= lang('edit') ?></span>
                        </a>
                    </div>
                    <div class="btn-group">
                        <a href="#" class="tip btn btn-danger bpo" title="<b><?= $this->lang->line("delete_sale") ?></b>"
                            data-content="<div style='width:150px;'><p><?= lang('r_u_sure') ?></p><a class='btn btn-danger' href='<?= site_url('sales/delete/' . $inv->id) ?>'><?= lang('i_m_sure') ?></a> <button class='btn bpo-close'><?= lang('no') ?></button></div>"
                            data-html="true" data-placement="top">
                            <i class="fa fa-trash-o"></i>
                            <span class="hidden-sm hidden-xs"><?= lang('delete') ?></span>
                        </a>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready( function() {
        $('.tip').tooltip();
        $('.link_ar_to').on('click', function(){
            var id = $(this).attr('id');
            window.open('<?=base_url()?>account/list_ac_recevable?id=' + id, '_blank');
        });
    });
</script>
