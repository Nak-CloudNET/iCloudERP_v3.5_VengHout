<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('add_payment'); ?></h4>
        </div>

        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open_multipart("purchases/add_payment/" . $inv->id, $attrib); ?>
        <div class="modal-body">
            <p><?= lang('enter_info');  ?><?=$inv->id;?></p>

            <div class="row">
                <?php if ($Owner || $Admin) { ?>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <?= lang("date", "date"); ?>
                            <?= form_input('date', (isset($_POST['date']) ? $_POST['date'] : ""), 'class="form-control datetime" id="date" required="required"'); ?>
                        </div>
                    </div>
                <?php } ?>

                <?php if ($this->Settings->system_management == 'biller') { ?>
                    <div class="col-sm-6 col-xs-6">
                        <?= get_dropdown_project('biller', 'posbiller'); ?>
                    </div>
                <?php } ?>

                <div class="col-sm-6">
                    <div class="form-group">
                        <?= lang("reference_no", "slref"); ?>
                        <div class="input-group">
                            <?php echo form_input('reference_no', $payment_ref ? $payment_ref : "", '  class="form-control input-tip" id="slref"'); ?>
                            <input type="hidden" name="temp_reference_no" id="temp_reference_no"
                                   value="<?= $payment_ref ? $payment_ref : "" ?>"/>
                            <input type="hidden" name="quote_id" id="quote_id" value=""/>
                            <div class="input-group-addon no-print" style="padding: 2px 5px;background-color:white;">
                                <input type="checkbox" name="ref_status" id="ref_st" value="1" style="margin-top:3px;">
                            </div>
                        </div>
                    </div>
                </div>

                <input type="hidden" value="<?php echo $inv->id; ?>" name="purchase_id"/>
                <input type="hidden" value="<?php echo $inv->biller_id; ?>" name="biller_id"/>
            </div>
            <div class="clearfix"></div>
            <div id="payments">

                <div class="well well-sm well_1">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="payment">
                                    <div class="form-group">
                                        <?= lang("discount", "discount"); ?>
                                        <input name="discount" value="0.00" type="text" class="form-control" id="discount"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="payment">
                                    <div class="form-group">
                                        <?= lang("amount", "amount_1"); ?>
                                        <input name="amount-paid" type="text" id="amount_1" amount="<?= $this->erp->formatDecimal($inv->grand_total - $inv->paid); ?>"
                                               value="<?= $this->erp->formatDecimal($inv->grand_total - $inv->paid); ?>"
                                               class="pa form-control kb-pad amount" required="required"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <?= lang("paying_by", "paid_by_1"); ?>
                                    <select name="paid_by" id="paid_by_1" class="form-control paid_by"
                                            required="required">
                                        <option value="cash"><?= lang("cash"); ?></option>
                                        <option value="CC"><?= lang("cc"); ?></option>
                                        <option value="Cheque"><?= lang("cheque"); ?></option>
                                        <option value="deposit"><?= lang("deposit"); ?></option>
                                        <option value="other"><?= lang("other"); ?></option>
                                    </select>

                                </div>
                            </div>
                            <div class="col-sm-6 bank_o">
                                <div class="form-group">
                                    <?= lang("bank_account", "bank_account_1"); ?>
                                    <?php
                                    $bank = array('0' => '-- Select Bank Account --');
                                    if ($Owner || $Admin) {
                                        foreach($bankAccounts as $bankAcc) {
                                            $bank[$bankAcc->accountcode] = $bankAcc->accountcode . ' | '. $bankAcc->accountname;
                                        }
                                        echo form_dropdown('bank_account', $bank, '', 'id="bank_account_1" class="ba form-control kb-pad bank_account" required="true"');
                                    } else {
                                        $ubank = array('0' => '-- Select Bank Account --');
                                        foreach($userBankAccounts as $userBankAccount) {
                                            $ubank[$userBankAccount->accountcode] = $userBankAccount->accountcode . ' | '. $userBankAccount->accountname;
                                        }
                                        echo form_dropdown('bank_account', $ubank, '', 'id="bank_account_1" class="ba form-control kb-pad bank_account" required="true"');
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>

                        <div class="form-group dp" style="display: none;">
                            <?= lang("supplier", "supplier1"); ?>
                            <?php
                            //$suppliers1[] = array();
                            //foreach($suppliers as $supplier){
                            //	$suppliers1[$supplier->id] = $supplier->name;
                            //}
                            //echo form_dropdown('supplier', $suppliers1, $suppliers_id , 'class="form-control" id="supplier1"');
                            ?>
                            <input type="hidden" name="suppliers_id" id="suppliers_id" value="<?=$inv->supplier_id;?>">

                            <input type="hidden" name="paid_o" id="paid_o" value="<?=$inv->paid_by;?>">
                            <input type="hidden" name="amount_o" id="amount_o" value="<?=$inv->paid;?>">
                            <?= lang("deposit_amount", "deposit_amount"); ?>
                            <div id="dp_details"></div>
                        </div>

                        <div class="pcc_1" style="display:none;">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input name="pcc_no" type="text" id="pcc_no_1" class="form-control"
                                               placeholder="<?= lang('cc_no') ?>"/>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">

                                        <input name="pcc_holder" type="text" id="pcc_holder_1" class="form-control"
                                               placeholder="<?= lang('cc_holder') ?>"/>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <select name="pcc_type" id="pcc_type_1" class="form-control pcc_type"
                                                placeholder="<?= lang('card_type') ?>">
                                            <option value="Visa"><?= lang("Visa"); ?></option>
                                            <option value="MasterCard"><?= lang("MasterCard"); ?></option>
                                            <option value="Amex"><?= lang("Amex"); ?></option>
                                            <option value="Discover"><?= lang("Discover"); ?></option>
                                        </select>
                                        <!-- <input type="text" id="pcc_type_1" class="form-control" placeholder="<?= lang('card_type') ?>" />-->
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input name="pcc_month" type="text" id="pcc_month_1" class="form-control"
                                               placeholder="<?= lang('month') ?>"/>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">

                                        <input name="pcc_year" type="text" id="pcc_year_1" class="form-control"
                                               placeholder="<?= lang('year') ?>"/>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">

                                        <input name="pcc_ccv" type="text" id="pcc_cvv2_1" class="form-control"
                                               placeholder="<?= lang('cvv2') ?>"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="pcheque_1" style="display:none;">
                            <div class="form-group"><?= lang("cheque_no", "cheque_no_1"); ?>
                                <input name="cheque_no" type="text" id="cheque_no_1" class="form-control cheque_no"/>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>

            </div>

            <div class="form-group">
                <?= lang("attachment", "attachment") ?>
                <input id="attachment" type="file" name="userfile" data-show-upload="false" data-show-preview="false"
                       class="form-control file">
            </div>

            <div class="form-group">
                <?= lang("note", "note"); ?>
                <?php echo form_textarea('note', (isset($_POST['note']) ? $_POST['note'] : ""), 'class="form-control" id="note"'); ?>

            </div>

        </div>
        <div class="modal-footer">
            <?php echo form_submit('add_payment', lang('add_payment'), 'class="btn btn-primary" id="add_payment" style="display:none;"'); ?>
            <button class="btn btn-primary" id="add_payment_test">Add Payment</button>
        </div>
    </div>
    <?php echo form_close(); ?>

</div>
<script type="text/javascript" src="<?= $assets ?>js/custom.js"></script>
<script type="text/javascript" charset="UTF-8">
    $.fn.datetimepicker.dates['erp'] = <?=$dp_lang?>;
</script>
<?= $modal_js ?>
<script type="text/javascript" charset="UTF-8">
    $(document).ready(function () {

        $("#slref").attr('readonly', 'readonly');
        $('#ref_st').on('ifChanged', function () {
            if ($(this).is(':checked')) {
                $("#slref").prop('readonly', false);
                $("#slref").val("");
            } else {
                $("#slref").prop('readonly', true);
                var temp = $("#temp_reference_no").val();
                $("#slref").val(temp);

            }
        });

        $('#posbiller').change(function () {
            billerChange();
        });
        var $biller = $("#posbiller");
        $(window).load(function () {
            billerChange();
        });

        function billerChange() {
            var id = $biller.val();
            $.ajax({
                url: '<?= base_url() ?>sales/getReferenceByProject/pp/' + id,
                dataType: 'json',
                success: function (data) {
                    $("#slref").val(data);
                    $("#temp_reference_no").val(data);
                }
            });

        }

        $.fn.datetimepicker.dates['erp'] = <?=$dp_lang?>;
        $(document).on('change', '.paid_by', function () {
            var p_val = $(this).val();
            __setItem('paid_by', p_val);
            $('#rpaidby').val(p_val);
            if (p_val == 'cash') {
                $('.pcheque_1').hide();
                $('.pcc_1').hide();
                $('.pcash_1').show();
                $('#amount_1').focus();
                $(".refer_o").show();
                $(".bank_o").show();
                $(".refer").hide();
            } else if (p_val == 'CC') {
                $('.pcheque_1').hide();
                $('.pcash_1').hide();
                $('.pcc_1').show();
                $('#pcc_no_1').focus();
                $(".refer_o").show();
                $(".bank_o").show();
                $(".refer").hide();
            } else if (p_val == 'Cheque') {
                $('.pcc_1').hide();
                $('.pcash_1').hide();
                $('.pcheque_1').show();
                $('#cheque_no_1').focus();
                $(".refer_o").show();
                $(".bank_o").show();
                $(".refer").hide();
            } else {
                $('.pcheque_1').hide();
                $('.pcc_1').hide();
                $('.pcash_1').hide();
            }
            if(p_val == 'deposit') {
                $('.dp').show();
                $('#supplier1').trigger('change');
                $(".bank_o").hide();
                $(".refer_o").hide();
                $(".refer").show();
                checkDeposit();
                $('#amount_1').trigger('change');
            }else{
                $('.dp').hide();
                $('#dp_details').html('');
            }
        });


        $("#pay_ref").attr('readonly','readonly');
        $('#ref_st').on('ifChanged', function() {
            if ($(this).is(':checked')) {
                $("#pay_ref").prop('readonly', false);
                $("#pay_ref").val("");
            }else{
                $("#pay_ref").prop('readonly', true);
                var temp = $("#temp_reference_no").val();
                $("#pay_ref").val(temp);

            }
        });

        /*$(document).on('change', '#supplier1', function(){
            checkDeposit();
            $('#amount_1').trigger('change');
        });*/

        $(document).on('click', '#add_payment_test', function(){
            /*var deposit_balance = 0;
            var us_paid = $('#amount_1').val()-0;
            var deposit_amount = parseFloat($(".deposit_amount").val()-0);
            deposit_balance = (deposit_amount - Math.abs(us_paid));
            $(".deposit_total_balance").text(deposit_balance);

            if(deposit_balance > deposit_amount || deposit_balance < 0 || deposit_amount == 0){
                bootbox.alert('Your Deposit Limited: ' + deposit_amount);
                $('#amount_1').val(deposit_amount);
                $(".deposit_total_balance").text(deposit_amount - $('#amount_1').val()-0);
                return false;
            }*/
            var paidby = $("#paid_by_1").val();
            var bank = $("#bank_account_1").val();
            var am1 = $("#amount_1").val()-0;
            var am2 = $("#amount_2").val()-0;

            if(paidby == "deposit"){
                alert(0);
                if(am1<=0){
                    bootbox.alert('Amount is invalid '+am1);
                    return false;
                }
                if(am1>am2){
                    bootbox.alert('Sorry you can not add payment more. You paid already.');
                    return false;
                }

            }else{

                if(am1<=0){
                    bootbox.alert('Amount is invalid '+am1);
                    return false;
                }
                if(am1>am2){
                    bootbox.alert('Balance must less only '+am2);
                    return false;
                }
                if(bank==0){
                    bootbox.alert('Please select Bank Account.');
                    return false;
                }

            }
            $("#add_payment").trigger("click");

        });

        function checkDeposit() {
            var supplier_id = $("#suppliers_id").val();
            if (supplier_id != '') {
                $.ajax({
                    type: "get", async: false,
                    url: site.base_url + "sales/validate_deposit/" + supplier_id,
                    dataType: "json",
                    success: function (data) {
                        if (data === false) {
                            $('#deposit_no_1').parent('.form-group').addClass('has-error');
                            bootbox.alert('<?=lang('invalid_supplier')?>');
                        } else if (data.id !== null && data.id !== supplier_id) {
                            $('#deposit_no_1').parent('.form-group').addClass('has-error');
                            bootbox.alert('<?=lang('this_supplier_has_no_deposit')?>');
                        } else {
                            var amount = $("#amount_1").val();
                            var deposit_amount =  (data.dep_amount==null?0: data.dep_amount);
                            var deposit_balance = (data.deposit_amount - amount);
                            if(deposit_balance>0){
                                $('#dp_details').html('<small>Supplier Name : ' + data.name + '<br>Deposit Amount :  <span val='+data.deposit_amount+' class="deposit_total_amount">' + formatDecimal(data.deposit_amount) + '</span><br>Balance : <span class="deposit_total_balance">' + deposit_balance + '</span><input type="hidden" name="deposit_amount" class="deposit_amount" value="'+deposit_amount+'" ></small>');
                                $('#deposit_no').parent('.form-group').removeClass('has-error');
                            }else{
                                $("#amount_1").val(Number(data.deposit_amount));
                                $('#dp_details').html('<small>Supplier Name : ' + data.name + '<br>Deposit Amount :  <span val='+data.deposit_amount+' class="deposit_total_amount">' + formatDecimal(data.deposit_amount) + '</span><br>Balance : <span class="deposit_total_balance">' + 0 + '</span><input type="hidden" name="deposit_amount" class="deposit_amount" value="'+deposit_amount+'" ></small>');
                                $('#deposit_no').parent('.form-group').removeClass('has-error');
                            }


                        }
                    }
                });
            }
        }

        $('#amount_1').on('keyup change', function () {
            var us_paid = parseFloat($('#amount_1').val()-0);
            var disc = parseFloat($('#discount').val() - 0);
            var amount = parseFloat($('#amount_1').attr('amount')-0);
            amount -= disc;
            var p_val = $('#paid_by_1').val();
            var new_deposit_balance = 0;
            if(p_val == 'deposit') {
                var deposit_balance = parseFloat($('#amount_1').attr('deposit_balance')-0);
                new_deposit_balance = deposit_balance - us_paid;
                if(!us_paid) {
                    $('#amount_1').val(0);
                    $(".deposit_total_balance").text(deposit_balance);
                    $('#amount_1').select();
                }else if(new_deposit_balance < 0) {
                    if(deposit_balance > amount) {
                        $('#amount_1').val(amount);
                        $(".deposit_total_balance").text(formatDecimal(new_deposit_balance));
                    }else {
                        $('#amount_1').val(deposit_balance);
                        $(".deposit_total_balance").text(0);
                    }
                    $('#amount_1').select();
                }else if(us_paid > amount){
                    $('#amount_1').val(amount);
                    $(".deposit_total_balance").text(formatDecimal(new_deposit_balance));
                    $('#amount_1').select();
                }else {
                    $(".deposit_total_balance").text(formatDecimal(new_deposit_balance));
                }

            }else {
                if(!us_paid) {
                    $('#amount_1').val(0);
                    $('#amount_1').select();
                }else if(us_paid > amount) {
                    $('#amount_1').val(amount);
                    $('#amount_1').select();
                }
            }
        });

        $('#discount').on('keyup change', function() {
            var disc = parseFloat($(this).val() - 0);
            var amount = parseFloat($('#amount_1').attr('amount') - 0);
            var paid = amount - disc;
            if(paid < 0) {
                $(this).val(formatDecimal(amount));
                $('#amount_1').val(formatDecimal(0));
                $('#amount_1').trigger('change');
            }else {
                $('#amount_1').val(formatDecimal(paid));
                $('#amount_1').trigger('change');
            }
        });

        /*$(document).on('keyup', '#amount_1', function () {
            var deposit_balance = 0;
            var us_paid = $('#amount_1').val()-0;
            var deposit_total_amount = $('.deposit_total_amount').attr('val');
            if(us_paid > deposit_total_amount){
                $(this).val(Number(deposit_total_amount));
                $(".deposit_total_balance").text(0);
            }else{
                deposit_balance = (deposit_total_amount - us_paid);
                $(".deposit_total_balance").text(deposit_balance);
            }

        });
        */
        $('#pcc_no_1').change(function (e) {
            var pcc_no = $(this).val();
            __setItem('pcc_no_1', pcc_no);
            var CardType = null;
            var ccn1 = pcc_no.charAt(0);
            if (ccn1 == 4)
                CardType = 'Visa';
            else if (ccn1 == 5)
                CardType = 'MasterCard';
            else if (ccn1 == 3)
                CardType = 'Amex';
            else if (ccn1 == 6)
                CardType = 'Discover';
            else
                CardType = 'Visa';

            $('#pcc_type_1').select2("val", CardType);
        });
        $("#date").datetimepicker({
            format: site.dateFormats.js_ldate,
            fontAwesome: true,
            language: 'erp',
            weekStart: 1,
            todayBtn: 1,
            autoclose: 1,
            todayHighlight: 1,
            startView: 2,
            forceParse: 0
        }).datetimepicker('update', new Date());
    });
</script>
