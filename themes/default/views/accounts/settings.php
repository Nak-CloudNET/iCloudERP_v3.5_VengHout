<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-cogs"></i><?= lang('account_settings'); ?></h2>
        <?php if(isset($pos->purchase_code) && ! empty($pos->purchase_code) && $pos->purchase_code != 'purchase_code') { ?>
        <div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown"><a href="<?= site_url('pos/updates') ?>" class="toggle_down"><i class="icon fa fa-upload"></i><span class="padding-right-10"><?= lang('updates'); ?></span></a>
                </li>
            </ul>
        </div>
        <?php }?>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <p class="introtext"><?php echo lang('update_info'); ?></p>

                <?php
                $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'account_setting');
                echo form_open("account/settings", $attrib);
                ?>

                <fieldset class="scheduler-border">
                    <legend class="scheduler-border"><?= lang('account_config') ?></legend>
                    
                    <?php
					              foreach($default as $data){
					          ?>
					                 <div class="col-md-4 col-sm-4">
                        <div class="form-group">
                            <?= lang("default_biller", "biller"); ?>
							<!--
                            <?= form_input('biller', (isset($_POST['biller']) ? $_POST['biller'] : $data->biller_id), 'class="form-control tip" id="biller1" data-placeholder="' . lang("select") . ' ' . lang("biller") . '" class="form-control" style="width:100%;"'); ?>
							-->
							<?php
								$acc_section = array(""=>"");
								$biller_name = "";
								foreach($get_biller_name as $getbiller){
									$biller_name = $getbiller->company;
								}
								foreach($get_biller as $biller){
									$acc_section[$biller->id] = $biller->company;
								}
								echo form_dropdown('biller', $acc_section, '' ,'id="biller" class="form-control" data-placeholder="' . lang($biller_name) . '" style="width:100%;" ');
							?>
							<input type="hidden" value="<?= $data->biller_id;?>" name="biller_id" class="form-control" style="width:100%;"/>
                        </div>
                    </div>
					<div class="col-md-4 col-sm-4">
						<div class="form-group">
							<?= lang("default_open_balance","default_open_balance"); ?>
							<!--
							<?php
                            echo form_input('default_open_balance', (isset($_POST['default_open_balance']) ? $_POST['default_open_balance'] : $data->default_open_balance), ' id="defaut_open_balance" data-placeholder="' . $data->default_open_balance . '" class="form-control tip" style="width:100%;"');
                            ?>
							-->
							<?php
								$acc_section = array(""=>"");
								foreach($chart_accounts as $section){
									$acc_section[$section->accountcode] = $section->accountcode.' | '.$section->accountname;
								}
								echo form_dropdown('default_open_balance', $acc_section, '' ,'id="default_open_balance" class="form-control" data-placeholder="' . $data->default_open_balance . ' | ' . $this->lang->line($data->accountname) . '" style="width:100%;" ');
							?>
							<input type="hidden" value="<?= $data->default_open_balance;?>" name="open_balance" class="form-control" style="width:100%;"/>
						</div>
					</div>
					<div class="col-md-4 col-sm-4">
						<div class="form-group">
							<?= lang("default_sale","default_sale"); ?>
							<?php
								$acc_section = array(""=>"");
								$salename = "";
								foreach($sale_name as $getsale){
									$salename = $getsale->accountname;
								}
								foreach($chart_accounts as $section){
									$acc_section[$section->accountcode] = $section->accountcode.' | '.$section->accountname;
								}
								echo form_dropdown('default_sale', $acc_section, '' ,'id="default_sale" class="form-control" data-placeholder="' . $data->default_sale . ' | ' . $this->lang->line($salename) . '" style="width:100%;" ');
								//echo form_input('default_sale', (isset($_POST['default_sale']) ? $_POST['default_sale'] : $data->default_sale), 'id="default_sale" class="form-control" style="width:100%"');
							?>
							<input type="hidden" value="<?= $data->default_sale;?>" name="sales" class="form-control" style="width:100%;"/>
						</div>
					</div>
					<div class="col-md-4 col-sm-4">
						<div class="form-group">
							<?= lang("default_sale_discount","default_sale_discount"); ?>
							<?php
								$acc_section = array(""=>"");
								$salediscount = "";
								foreach($sale_discount as $getdiscount){
									$salediscount = $getdiscount->accountname;
								}
								foreach($chart_accounts as $section){
									$acc_section[$section->accountcode] = $section->accountcode.' | '.$section->accountname;
								}
								echo form_dropdown('default_sale_discount', $acc_section, '' ,'id="default_sale_discount" class="form-control" data-placeholder="' . $data->default_sale_discount . ' | ' . $this->lang->line($salediscount) . '" style="width:100%;" ');
							?>
							<input type="hidden" value="<?= $data->default_sale_discount;?>" name="sale_discount" class="form-control" style="width:100%;"/>
						</div>
					</div>
					<div class="col-md-4 col-sm-4">
						<div class="form-group">
							<?= lang("default_sale_tax","default_sale_tax"); ?>
							<?php
								$acc_section = array(""=>"");
								$stax = "";
								foreach($sale_tax as $saletax){
									$stax = $saletax->accountname;
								}
								foreach($chart_accounts as $section){
									$acc_section[$section->accountcode] = $section->accountcode.' | '.$section->accountname;
								}
								echo form_dropdown('default_sale_tax', $acc_section, '' ,'id="default_sale_tax" class="form-control" data-placeholder="' . $data->default_sale_tax . ' | ' . $this->lang->line($stax) . '" style="width:100%;" ');
							?>
							<input type="hidden" value="<?= $data->default_sale_tax;?>" name="dsale_tax" class="form-control" style="width:100%;"/>
						</div>
					</div>
					<div class="col-md-4 col-sm-4">
						<div class="form-group">
							<?= lang("default_receivable","default_receivable"); ?>
							<?php
								$acc_section = array(""=>"");
								$dreceivable = "";
								foreach($receivable as $receive){
									$dreceivable = $receive->accountname;
								}
								foreach($chart_accounts as $section){
									$acc_section[$section->accountcode] = $section->accountcode.' | '.$section->accountname;
								}
								echo form_dropdown('default_receivable', $acc_section, '' ,'id="default_receivable" class="form-control" data-placeholder="' . $data->default_receivable . ' | ' . $this->lang->line($dreceivable) . '" style="width:100%;" ');
							?>
							<input type="hidden" value="<?= $data->default_receivable;?>" name="receivable" class="form-control" style="width:100%;"/>
						</div>
					</div>
					<div class="col-md-4 col-sm-4">
						<div class="form-group">
							<?= lang("default_purchase","default_purchase"); ?>
							<?php
								$acc_section = array(""=>"");
								$purchase = "";
								foreach($purchases as $buy){
									$purchase = $buy->accountname;
								}
								foreach($chart_accounts as $section){
									$acc_section[$section->accountcode] = $section->accountcode.' | '.$section->accountname;
								}
								echo form_dropdown('default_purchase', $acc_section, '' ,'id="default_purchase" class="form-control" data-placeholder="' . $data->default_purchase . ' | ' . $this->lang->line($purchase) . '" style="width:100%;" ');
							?>
							<input type="hidden" value="<?= $data->default_purchase;?>" name="dpurchase" class="form-control" style="width:100%;"/>
						</div>
					</div>
					<div class="col-md-4 col-sm-4">
						<div class="form-group">
							<?= lang("default_purchase_tax","default_purchase_tax"); ?>
							<?php
								$acc_section = array(""=>"");
								$ptax = "";
								foreach($purchase_tax as $purchasetax){
									$ptax = $purchasetax->accountname;
								}
								foreach($chart_accounts as $section){
									$acc_section[$section->accountcode] = $section->accountcode.' | '.$section->accountname;
								}
								echo form_dropdown('default_purchase_tax', $acc_section, '' ,'id="default_purchase_tax" class="form-control" data-placeholder="' . $data->default_purchase_tax . ' | ' . $this->lang->line($ptax) . '" style="width:100%;" ');
							?>
							<input type="hidden" value="<?= $data->default_purchase_tax;?>" name="dpurchase_tax" class="form-control" style="width:100%;"/>
						</div>
					</div>
					<div class="col-md-4 col-sm-4">
						<div class="form-group">
							<?= lang("default_purchase_discount","default_purchase_discount"); ?>
							<?php
								$acc_section = array(""=>"");
								$purchase_discount = "";
								foreach($purchasediscount as $buydiscount){
									$purchase_discount = $buydiscount->accountname;
								}
								foreach($chart_accounts as $section){
									$acc_section[$section->accountcode] = $section->accountcode.' | '.$section->accountname;
								}
								echo form_dropdown('default_purchase_discount', $acc_section, '' ,'id="default_purchase_discount" class="form-control" data-placeholder="' . $data->default_purchase_discount . ' | ' . $this->lang->line($purchase_discount) . '" style="width:100%;" ');
							?>
							<input type="hidden" value="<?= $data->default_purchase_discount;?>" name="dpurchase_discount" class="form-control" style="width:100%;"/>
						</div>
					</div>
					<div class="col-md-4 col-sm-4">
						<div class="form-group">
							<?= lang("default_payable","default_payable"); ?>
							<?php
								$acc_section = array(""=>"");
								$pay = "";
								foreach($payable as $payables){
									$pay = $payables->accountname;
								}
								foreach($chart_accounts as $section){
									$acc_section[$section->accountcode] = $section->accountcode.' | '.$section->accountname;
								}
								echo form_dropdown('default_payable', $acc_section, '' ,'id="default_payable" class="form-control" data-placeholder="' . $data->default_payable . ' | ' . $this->lang->line($pay) . '" style="width:100%;" ');
							?>
							<input type="hidden" value="<?= $data->default_payable;?>" name="dpayable" class="form-control" style="width:100%;"/>
						</div>
					</div>
					<div class="col-md-4 col-sm-4">
						<div class="form-group">
							<?= lang("default_sale_freight","default_sale_freight"); ?>
							<?php
								$acc_section = array(""=>"");
								$sale_freight = "";
								foreach($get_sale_freight as $sale_freights){
									$sale_freight = $sale_freights->accountname;
								}
								foreach($chart_accounts as $section){
									$acc_section[$section->accountcode] = $section->accountcode.' | '.$section->accountname;
								}
								echo form_dropdown('default_sale_freight', $acc_section, '' ,'id="default_sale_freight" class="form-control" data-placeholder="' . $data->default_sale_freight . ' | ' . $this->lang->line($sale_freight) . '" style="width:100%;" ');
							?>
							<input type="hidden" value="<?= $data->default_sale_freight;?>" name="dsale_freight" class="form-control" style="width:100%;"/>
						</div>
					</div>
					<div class="col-md-4 col-sm-4">
						<div class="form-group">
							<?= lang("default_purchase_freight","default_purchase_freight"); ?>
							<?php
								$acc_section = array(""=>"");
								$purchase_freight = "";
								foreach($get_purchase_freight as $purchase_freights){
									$purchase_freight = $purchase_freights->accountname;
								}
								foreach($chart_accounts as $section){
									$acc_section[$section->accountcode] = $section->accountcode.' | '.$section->accountname;
								}
								echo form_dropdown('default_purchase_freight', $acc_section, '' ,'id="default_purchase_freight" class="form-control" data-placeholder="' . $data->default_purchase_freight . ' | ' . $this->lang->line($purchase_freight) . '" style="width:100%;" ');
							?>
							<input type="hidden" value="<?= $data->default_purchase_freight;?>" name="dpurchase_freight" class="form-control" style="width:100%;"/>
						</div>
					</div>
					<div class="col-md-4 col-sm-4">
						<div class="form-group">
							<?= lang("default_stock","default_stock"); ?>
							<?php
								$acc_section = array(""=>"");
								$stock = "";
								foreach($getstock as $getstocks){
									$stock = $getstocks->accountname;
								}
								foreach($chart_accounts as $section){
									$acc_section[$section->accountcode] = $section->accountcode.' | '.$section->accountname;
								}
								echo form_dropdown('default_stock', $acc_section, '' ,'id="default_stock" class="form-control" data-placeholder="' . $data->default_stock . ' | ' . $this->lang->line($stock) . '" style="width:100%;" ');
							?>
							<input type="hidden" value="<?= $data->default_stock;?>" name="dstock" class="form-control" style="width:100%;"/>
						</div>
					</div>
					<div class="col-md-4 col-sm-4">
						<div class="form-group">
                            <?= lang("default_stock_adjustment", "default_stock_adjustment"); ?>
							<?php
								$acc_section = array(""=>"");
								$adjust = "";
								foreach($stock_adjust as $stockadjust){
									$adjust = $stockadjust->accountname;
								}
								foreach($chart_accounts as $section){
									$acc_section[$section->accountcode] = $section->accountcode.' | '.$section->accountname;
								}
								echo form_dropdown('default_stock_adjust', $acc_section, '' ,'id="default_stock_adjust" class="form-control" data-placeholder="' . $data->default_stock_adjust . ' | ' . $this->lang->line($adjust) . '" style="width:100%;" ');
							?>
							<input type="hidden" value="<?= $data->default_stock_adjust;?>" name="dstock_adjust" class="form-control" style="width:100%;"/>
						</div>
					</div>
					<div class="col-md-4 col-sm-4">
						<div class="form-group">
							<?= lang("default_cost","default_cost"); ?>
							<?php
								$acc_section = array(""=>"");
								$cost = "";
								foreach($getcost as $getcosts){
									$cost = $getcosts->accountname;
								}
								foreach($chart_accounts as $section){
									$acc_section[$section->accountcode] = $section->accountcode.' | '.$section->accountname;
								}
								echo form_dropdown('default_cost', $acc_section, '' ,'id="default_cost" class="form-control" data-placeholder="' . $data->default_cost . ' | ' . $this->lang->line($cost) . '" style="width:100%;" ');
							?>
							<input type="hidden" value="<?= $data->default_cost;?>" name="dcost" class="form-control" style="width:100%;"/>
						</div>
					</div>
					<div class="col-md-4 col-sm-4">
                        <div class="form-group">
                            <?= lang("default_payroll", "default_payroll"); ?>
                            <?php
								$acc_section = array(""=>"");
								$payroll = "";
								foreach($getpayroll as $payrolls){
									$payroll = $payrolls->accountname;
								}
								foreach($chart_accounts as $section){
									$acc_section[$section->accountcode] = $section->accountcode.' | '.$section->accountname;
								}
								echo form_dropdown('default_payroll', $acc_section, '' ,'id="default_payroll" class="form-control" data-placeholder="' . $data->default_payroll . ' | ' . $this->lang->line($payroll) . '" style="width:100%;" ');
                            ?>
							<input type="hidden" value="<?= $data->default_payroll;?>" name="dpayroll" class="form-control" style="width:100%;"/>
                        </div>
                    </div>
					<div class="col-md-4 col-sm-4">
                        <div class="form-group">
                            <?= lang("default_cash", "default_cash"); ?>
                            <?php
								$acc_section = array(""=>"");
								$cash = "";
								foreach($get_cashs as $get_cash){
									$cash = $get_cash->accountname;
								}
								foreach($chart_accounts as $section){
									$acc_section[$section->accountcode] = $section->accountcode.' | '.$section->accountname;
								}
								echo form_dropdown('default_cash', $acc_section, '' ,'id="default_cash" class="form-control" data-placeholder="' . $data->default_cash . ' | ' . $this->lang->line($cash) . '" style="width:100%;" ');
                            ?>
							<input type="hidden" value="<?= $data->default_cash;?>" name="dcash" class="form-control" style="width:100%;"/>
                        </div>
                    </div>
					<div class="col-md-4 col-sm-4">
                        <div class="form-group">
                            <?= lang("default_credit_card", "default_credit_card"); ?>
                            <?php
								$acc_section = array(""=>"");
								$ccard = "";
								foreach($credit_card as $creditcard){
									$ccard = $creditcard->accountname;
								}
								foreach($chart_accounts as $section){
									$acc_section[$section->accountcode] = $section->accountcode.' | '.$section->accountname;
								}
								echo form_dropdown('default_credit_card', $acc_section, '' ,'id="default_credit_card" class="form-control" data-placeholder="' . $data->default_credit_card . ' | ' . $this->lang->line($ccard) . '" style="width:100%;" ');
                            ?>
							<input type="hidden" value="<?= $data->default_credit_card;?>" name="dcredit_card" class="form-control" style="width:100%;"/>
                        </div>
                    </div>
					<div class="col-md-4 col-sm-4">
						<div class="form-group">
							<?= lang("default_gift_card","default_gift_card"); ?>
							<?php
								$acc_section = array(""=>"");
								$giftcard = "";
								foreach($gift_card as $gift_cards){
									$giftcard = $gift_cards->accountname;
								}
								foreach($chart_accounts as $section){
									$acc_section[$section->accountcode] = $section->accountcode.' | '.$section->accountname;
								}
								echo form_dropdown('default_gift_card', $acc_section, '' ,'id="default_gift_card" class="form-control" data-placeholder="' . $data->default_gift_card . ' | ' . $this->lang->line($giftcard) . '" style="width:100%;" ');
							?>
							<input type="hidden" value="<?= $data->default_gift_card;?>" name="dgift_card" class="form-control" style="width:100%;"/>
						</div>
					</div>
					<div class="col-md-4 col-sm-4">
						<div class="form-group">
							<?= lang("default_sale_deposit","default_sale_deposit"); ?>
							<?php
								$acc_section = array(""=>"");
								$get_sale_deposit = "";
								foreach($sale_deposit as $sale_deposits){
									$get_sale_deposit = $sale_deposits->accountname;
								}
								foreach($chart_accounts as $section){
									$acc_section[$section->accountcode] = $section->accountcode.' | '.$section->accountname;
								}
								echo form_dropdown('default_sale_deposit', $acc_section, '' ,'id="default_sale_deposit" class="form-control" data-placeholder="' . $data->default_sale_deposit . ' | ' . $this->lang->line($get_sale_deposit) . '" style="width:100%;" ');
							?>
							<input type="hidden" value="<?= $data->default_sale_deposit;?>" name="dsale_deposit" class="form-control" style="width:100%;"/>
						</div>
					</div>
					<div class="col-md-4 col-sm-4">
						<div class="form-group">
							<?= lang("default_purchase_deposit","default_purchase_deposit"); ?>
							<?php
								$acc_section = array(""=>"");
								$get_purchase_deposit = "";
								foreach($purchased_eposit as $purchase_deposits){
									$get_purchase_deposit = $purchase_deposits->accountname;
								}
								foreach($chart_accounts as $section){
									$acc_section[$section->accountcode] = $section->accountcode.' | '.$section->accountname;
								}
								echo form_dropdown('default_purchase_deposit', $acc_section, '' ,'id="default_purchase_deposit" class="form-control" data-placeholder="' . $data->default_purchase_deposit . ' | ' . $this->lang->line($get_purchase_deposit) . '" style="width:100%;" ');
							?>
							<input type="hidden" value="<?= $data->default_purchase_deposit;?>" name="dpurchase_deposit" class="form-control" style="width:100%;"/>
						</div>
					</div>
					<div class="col-md-4 col-sm-4">
						<div class="form-group">
							<?= lang("default_cheque","default_cheque"); ?>
							<?php
								$acc_section = array(""=>"");
								$getcheque = "";
								foreach($cheque as $cheques){
									$getcheque = $cheques->accountname;
								}
								foreach($chart_accounts as $section){
									$acc_section[$section->accountcode] = $section->accountcode.' | '.$section->accountname;
								}
								echo form_dropdown('default_cheque', $acc_section, '' ,'id="default_cheque" class="form-control" data-placeholder="' . $data->default_cheque . ' | ' . $this->lang->line($getcheque) . '" style="width:100%;" ');
							?>
							<input type="hidden" value="<?= $data->default_cheque;?>" name="dcheque" class="form-control" style="width:100%;"/>
						</div>
					</div>
					<div class="col-md-4 col-sm-4">
						<div class="form-group">
							<?= lang("default_other_paid","default_other_paid"); ?>
							<?php
								$acc_section = array(""=>"");
								$getloans = "";
								foreach($loan as $loans){
									$getloans = $loans->accountname;
								}
								foreach($chart_accounts as $section){
									$acc_section[$section->accountcode] = $section->accountcode.' | '.$section->accountname;
								}
								echo form_dropdown('default_loan', $acc_section, '' ,'id="default_loan" class="form-control" data-placeholder="' . $data->default_loan . ' | ' . $this->lang->line($getloans) . '" style="width:100%;" ');
							?>
							<input type="hidden" value="<?= $data->default_loan;?>" name="dloan" class="form-control" style="width:100%;"/>
						</div>
					</div>

					<div class="col-md-4 col-sm-4">
						<div class="form-group">
							<?= lang("default_retained_earnings","default_retained_earnings"); ?>
							<?php
								$acc_section = array(""=>"");
								$get_retained_earning = "";
								foreach($retained_earning as $retained_earnings){
									$get_retained_earning = $retained_earnings->accountname;
								}
								foreach($chart_accounts as $section){
									$acc_section[$section->accountcode] = $section->accountcode.' | '.$section->accountname;
								}
								echo form_dropdown('default_retained_earnings', $acc_section, '' ,'id="default_retained_earnings" class="form-control" data-placeholder="' . $data->default_retained_earnings . ' | ' . $this->lang->line($get_retained_earning) . '" style="width:100%;" ');
							?>
							<input type="hidden" value="<?= $data->default_retained_earnings;?>" name="dretained_earning" class="form-control" style="width:100%;"/>
						</div>
					</div>

					<div class="col-md-4 col-sm-4">
						<div class="form-group">
							<?= lang("default_cost_variant","default_cost_variant"); ?>
							<?php
								$acc_section = array(""=>"");
								$get_cost_of_variance = "";
								foreach($cost_of_variance as $cost_of_variance){
									$get_cost_of_variance = $cost_of_variance->accountname;
								}
								foreach($chart_accounts as $section){
									$acc_section[$section->accountcode] = $section->accountcode.' | '.$section->accountname;
								}
								echo form_dropdown('default_cost_variant', $acc_section, '' ,'id="default_cost_variant" class="form-control" data-placeholder="' . $data->default_cost_variant . ' | ' . $this->lang->line($get_cost_of_variance) . '" style="width:100%;" ');
							?>
							<input type="hidden" value="<?= $data->default_cost_variant;?>" name="cost_variant" class="form-control" style="width:100%;"/>
						</div>
					</div>

					<div class="col-md-4 col-sm-4">
						<div class="form-group">
							<?= lang("default_interest_income","default_interest_income"); ?>
							<?php
								$acc_section = array(""=>"");
								$default_interest_income = "";
								if($interest_income) {
									foreach($interest_income as $inc){
										$default_interest_income = $inc->accountname;
									}
								}
								foreach($chart_accounts as $section){
									$acc_section[$section->accountcode] = $section->accountcode.' | '.$section->accountname;
								}
								echo form_dropdown('default_interest_income', $acc_section, '' ,'id="default_interest_income" class="form-control" data-placeholder="' . $data->default_interest_income . ' | ' . $this->lang->line($default_interest_income) . '" style="width:100%;" ');
							?>
							<input type="hidden" value="<?= $data->default_interest_income;?>" name="interest_income" class="form-control" style="width:100%;"/>
						</div>
					</div>

                        <!--<div class="col-md-4 col-sm-4">
						<div class="form-group">
							<?/*= lang("default_transfer_owner","default_transfer_owner"); */
                        ?>
							<?php
                        /*								$acc_section = array(""=>"");
                                                        $default_transfer_owner = "";
                                                        if($transfer_owner) {
                                                            foreach($transfer_owner as $to){
                                                                $default_transfer_owner = $to->accountname;
                                                            }
                                                        }
                                                        foreach($chart_accounts as $section) {
                                                            $acc_section[$section->accountcode] = $section->accountcode.' | '.$section->accountname;
                                                        }
                                                        echo form_dropdown('default_transfer_owner', $acc_section, '' ,'id="default_transfer_owner" class="form-control" data-placeholder="' . $data->default_transfer_owner . ' | ' . $this->lang->line($default_transfer_owner) . '" style="width:100%;" ');
                                                    */
                        ?>
							<input type="hidden" value="<?/*= $data->default_transfer_owner;*/
                        ?>" name="transfer_owner" class="form-control" style="width:100%;"/>
						</div>
					</div>-->

					<?php
					}
					?>
                </fieldset>

                <?php echo form_submit('update_settings', lang('update_settings'), 'class="btn btn-primary"'); ?>

                <?php echo form_close(); ?>
            </div>

        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function (e) {
        $('#account_setting').bootstrapValidator({
            feedbackIcons: {
                valid: 'fa fa-check',
                invalid: 'fa fa-times',
                validating: 'fa fa-refresh'
            }, excluded: [':disabled']
        });
        $('select.select').select2({minimumResultsForSearch: 6});
        fields = $('.form-control');
        $.each(fields, function () {
            var id = $(this).attr('id');
            var iname = $(this).attr('name');
            var iid = '#' + id;
            if (!!$(this).attr('data-bv-notempty') || !!$(this).attr('required')) {
                $("label[for='" + id + "']").append(' *');
                $(document).on('change', iid, function () {
                    $('#account_setting').bootstrapValidator('revalidateField', iname);
                });
            }
        });
        $('input[type="checkbox"],[type="radio"]').not('.skip').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });

        $('#customer1').val('<?= $account->default_customer; ?>').select2({
            minimumInputLength: 1,
            data: [],
            initSelection: function (element, callback) {
                $.ajax({
                    type: "get", async: false,
                    url: site.base_url+"customerszz/getCustomer/" + $(element).val(),
                    dataType: "json",
                    success: function (data) {
                        callback(data[0]);
                    }
                });
            },
            ajax: {
                url: site.base_url + "customers/suggestions",
                dataType: 'json',
                quietMillis: 15,
                data: function (term, page) {
                    return {
                        term: term,
                        limit: 10
                    };
                },
                results: function (data, page) {
                    if (data.results != null) {
                        return {results: data.results};
                    } else {
                        return {results: [{id: '', text: 'No Match Found'}]};
                    }
                }
            }
        });

		$('#biller1').val('<?= $account->default_biller; ?>').select2({
            minimumInputLength: 1,
            data: [],
            initSelection: function (element, callback) {
                $.ajax({
                    type: "get", async: false,
                    url: site.base_url+"customerszz/getCustomer/" + $(element).val(),
                    dataType: "json",
                    success: function (data) {
                        callback(data[0]);
                    }
                });
            },
            ajax: {
                url: site.base_url + "customers/suggestions",
                dataType: 'json',
                quietMillis: 15,
                data: function (term, page) {
                    return {
                        term: term,
                        limit: 10
                    };
                },
                results: function (data, page) {
                    if (data.results != null) {
                        return {results: data.results};
                    } else {
                        return {results: [{id: '', text: 'No Match Found'}]};
                    }
                }
            }
        });

		$('#defaut_open_balance').val('<?= $account->default_open_balance; ?>').select2({
            minimumInputLength: 1,
            data: [],
            initSelection: function (element, callback) {
                $.ajax({
                    type: "get", async: false,
                    url: site.base_url+"customerszz/getCustomer/" + $(element).val(),
                    dataType: "json",
                    success: function (data) {
                        callback(data[0]);
                    }
                });
            },
            ajax: {
                url: site.base_url + "customers/balance_suggest",
                dataType: 'json',
                quietMillis: 15,
                data: function (term, page) {
                    return {
                        term: term,
                        limit: 10
                    };
                },
                results: function (data, page) {
                    if (data.results != null) {
                        return {results: data.results};
                    } else {
                        return {results: [{id: '', text: 'No Match Found'}]};
                    }
                }
            }
        });

    });
</script>
