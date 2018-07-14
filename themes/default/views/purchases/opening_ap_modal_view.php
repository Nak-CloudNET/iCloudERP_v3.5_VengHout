<script type="text/javascript">
    $(document).ready(function () {
        <?php if ($inv) { ?>
            
             if (__getItem('posupplier')) {
                 __removeItem('posupplier');
             }
             if (__getItem('poid')) {
                 __removeItem('poid');
             }      
            __setItem('podate', '<?= date($dateFormats['php_ldate'], strtotime($inv->date))?>');
            __setItem('poexpance','<?=$inv->type_of_po?>');
            __setItem('posupplier', '<?=$inv->supplier_id?>');
            __setItem('poref', '<?=$inv->reference_no?>');
            __setItem('order_ref', '<?=$inv->order_ref?>');
            __setItem('powarehouse', '<?=$inv->warehouse_id?>');
            __setItem('edit_status', '<?=$edit_status?>');
            __setItem('postatus', '<?=$inv->status?>');
            __setItem('ponote', '<?= str_replace(array("\r", "\n"), "", $this->erp->decode_html($inv->note)); ?>');
            __setItem('podiscount', '<?=$inv->order_discount_id?>');
            __setItem('potax2', '<?=$inv->order_tax_id?>');
            __setItem('poshipping', '<?=$inv->shipping?>');
            __setItem('popayment_term', '<?=$inv->payment_term?>');
            __setItem('slpayment_status', '<?=$inv->payment_status?>');
            __setItem('balance', '<?= $this->erp->formatDecimal($inv->total) ?>');
            if (parseFloat(__getItem('potax2')) >= 1 || __getItem('podiscount').length >= 1 || parseFloat(__getItem('poshipping')) >= 1) {
                __setItem('poextras', '1');
            }
            
        <?php } ?>

         <?php if ($Owner || $Admin) { ?>
            $(document).on('change', '#podate', function (e) {
                __setItem('podate', $(this).val());
            });
            if (podate = __getItem('podate')) {
                $('#podate').val(podate);
            }
        <?php } ?>

            if (reference_no = __getItem('poref')) {
                $('#poref').val(reference_no);
            }
            if (supplier = __getItem('posupplier')) {
                $('#supplier').val(supplier);
            }
            if (balance = __getItem('balance')) {
                $('#balance').val(balance);
            }
            if (payment_term = __getItem('popayment_term')) {
                $('#payment_term').val(payment_term);
            }

    });
</script>

<style type="text/css">
    button {
        border-radius: 0 !important;
    }    
</style>

<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo $this->lang->line('edit_opening_balance'); ?></h4>
        </div>
        <div class="modal-body">
            <?php echo form_open_multipart("purchases/edit_opening_ap/" . $inv->id) ?>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <?= lang("date", "podate"); ?>
                            <?php echo form_input('date', (isset($_POST['date']) ? $_POST['date'] : $this->erp->hrld($purchase->date)), 'class="form-control input-tip datetime" id="podate" required="required"'); ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <?= lang("reference_no", "poref"); ?>
                            <?php echo form_input('reference_no', (isset($_POST['reference_no']) ? $_POST['reference_no'] : ''), 'class="form-control input-tip" id="poref" required="required" readonly'); ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <?= lang("project", "slbiller"); ?>
                            <?php
                            $bl[""] = "";
                            foreach ($billers as $biller) {
                                $bl[$biller->id] = $biller->company != '-' ?$biller->code .'-'. $biller->company : $biller->name;
                            }
                            echo form_dropdown('biller', $bl,(isset($_POST['biller']) ? $_POST['biller'] : $purchase->biller_id), 'id="slbiller" data-placeholder="' . lang("select") . ' ' . lang("biller") . '" required="required" class="form-control input-tip select" style="width:100%;"');
                            ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <?= lang("supplier", "supplier"); ?>
                            <?php 
                                $supp[''] = '';
                                foreach($suppliers as $supplier){
                                    $supp[$supplier->id] = $supplier->code .'-'. $supplier->name;
                                }
                                echo form_dropdown('supplier', $supp, (isset($_POST['supplier']) ? $_POST['supplier'] : $purchase->supplier_id), 'id="posupplier" class="form-control input-tip select" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("supplier") . '"  style="width:100%;" required="required" ');
                            ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="control-label" for="balance"><?= lang("balance"); ?></label>
                            <?php echo form_input('balance', (isset($_POST['balance']) ? $_POST['balance'] : ""), 'class="form-control tip" id="balance" '); ?>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="control-label" for="payment_term"><?= lang("payment_term"); ?></label>
                            <?php echo form_input('payment_term', (isset($_POST['payment_term']) ? $_POST['payment_term'] : ""), 'class="form-control tip" id="payment_term" '); ?>

                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary" id="edit_ap"><i class="fa fa-floppy-o" aria-hidden="true"></i>&nbsp;<?= lang('save') ?></button>
             <?php echo form_close(); ?>
        </div>
    </div>
</div>