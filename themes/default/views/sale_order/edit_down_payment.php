<?php /* $this->erp->print_arrays($customer); */ ?>
<div class="row">

    <div class="col-sm-2">
        <div class="row">
            <div class="col-sm-12 text-center">
                <div style="max-width:200px; margin: 0 auto;">
				
                    <?php 
					
                    if(!empty($inv->attachment)){ 
						echo  '<img alt="'.$inv->attachment .'" src="' .$this->config->base_url().'assets/images/' .$inv->attachment .'" class="avatar">';
					}else{
						echo '<img alt="'.$inv->attachment .'" src="'.$this->config->base_url() .'assets/images/male.png" class="avatar">'; 
					}
                    ?>
                </div>
                <h4><?= lang('login_email'); ?></h4>
                <p><i class="fa fa-envelope"></i> <?= $inv->email; ?></p>
            </div>
        </div>
    </div>
	 <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                                echo form_open('sale_order/edit_down_payment/'.$inv->id ,$attrib);
								echo form_hidden('sale_id', (isset($inv->id) ? $inv->id : ""), 'class="form-control input-tip sale_id" id="sale_id" required="required"'); 
								echo form_hidden('biller_id', (isset($inv->biller_id) ? $inv->biller_id : ""), 'class="form-control input-tip biller_id" id="biller_id" required="required"');
								//$this->erp->print_arrays($inv);			
               ?>
    <div class="col-sm-10">

        <ul id="myTab" class="nav nav-tabs">
            <li class=""><a href="#edit" class="tab-grey"><?= lang('cust_info') ?></a></li>
            <li class=""><a href="#cpassword" class="tab-grey"><?= lang('pro_info') ?></a></li>
			<li class=""><a href="#join_lease" class="tab-grey"><?= lang('join_lease') ?></a></li>
            <li class=""><a href="#avatar" class="tab-grey"><?= lang('down_payment') ?></a></li>
			
        </ul>

        <div class="tab-content">
            <div id="edit" class="tab-pane fade in">

                <div class="box">
                    <div class="box-header">
                        <h2 class="blue"><i class="fa-fw fa fa-edit nb"></i><?= lang('Customer Info'); ?></h2>
                    </div>
                    <div class="box-content">
                        <div class="row">
                            <div class="col-lg-12">
                             
                                <div class="row">
                                    <div class="col-md-12">
										<table width="100%" style="line-height:31px">
											<tr>
												<input type="hidden" name="customer_id" class="customer_id" id="customer_id" value="<?=$inv->customer_id?>">
												<td width="25%" style="font-family:'Khmer OS Muol Light'; font-size:14px;"> <?= lang('អតិថិជន / Customer');?> </td>
												
												<td width="50%">: <?= $customer->name ? $customer->name : $customer->company; ?> </td>
												<td width="5px" rowspan="2"> </td>
												<td width="10%" rowspan="2"> <?= lang('លេខវិក្ក័យបត្រ <br/> Invoice No');?> </td>
												<td width="15%" rowspan="2" style="padding-left:0px;">: <?= $inv->reference_no; ?></td>
											</tr>
											<tr>
												<td width="25%" style="font-family:'Khmer OS'; font-size:14px;"> <?= lang('ឈ្មោះ​ក្រុមហ៊ុន ឬ អតិថិជន <br/> Company name / Customer');?> </td>
												<td width="50%">: <?= $customer->company ? $customer->company : $customer->name; ?> </td>
											</tr>
											<tr>
												<td width="25%" style="font-family:'Khmer OS Muol Light'; font-size:14px;"> <?= lang('ទូរស័ព្ទ​លេខ / Telephone No');?> </td>
												<td width="50%">: <?= $customer->phone; ?> </td>
												<td width="5px" rowspan="2"> </td>
												<td width="10%" rowspan="2"> <?= lang('កាលបរិច្ឆេទ <br/> Date');?> </td>
												<td width="15%" rowspan="2" style="padding-left:0px;">: <?= $this->erp->hrld($inv->date); ?> </td>
											</tr>
											<tr>
												<td width="25%" style="font-family:'Khmer OS'; font-size:14px;"> <?= lang('លេខអត្តសញ្ញាណកម្ម អតប  (VATTIN)');?></td>
												<td width="50%">: <?= $customer->vat_no; ?></td>
											</tr>
										</table>
									

                                    </div>
                                </div>
                                
                               
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="cpassword" class="tab-pane fade">
                <div class="box">
                    <div class="box-header">
                        <h2 class="blue"><i class="fa-fw fa fa-key nb"></i><?= lang('Product_info'); ?></h2>
                    </div>
                    <div class="box-content">
                        <div class="row">
							<div class="table-responsive">
								<table class="table table-bordered table-hover table-striped print-table order-table">

									<thead>

											<tr>
												<th><?= lang("no"); ?></th>
												<?php if($setting->show_code == 1 && $setting->separate_code == 1) { ?>
												<th><?= lang('product_code'); ?></th>
												<?php } ?>
												<th><?= lang("description"); ?></th>
												<th><?= lang("unit"); ?></th>
												<th><?= lang("quantity"); ?></th>
												<th><?= lang("unit_price"); ?></th>
												<?php
												if ($Settings->tax1) {
													echo '<th>' . lang("tax") . '</th>';
												}
												if ($Settings->product_discount && $inv->product_discount != 0) {
													echo '<th>' . lang("discount") . '</th>';
												}
												?>
												<th><?= lang("subtotal"); ?></th>
											</tr>

									</thead>

									<tbody>

									<?php $r = 1;
									$tax_summary = array();
									foreach ($rows as $row):
									$free = lang('free');
									$product_unit = '';
									$total = 0;
									
									if($row->variant){
										$product_unit = $row->variant;
									}else{
										$product_unit = $row->uname;
									}
									
									$product_name_setting;
									if($setting->show_code == 0) {
										$product_name_setting = $row->product_name . ($row->variant ? ' (' . $row->variant . ')' : '');
									}else {
										if($setting->separate_code == 0) {
											$product_name_setting = $row->product_name . " (" . $row->product_code . ")" . ($row->variant ? ' (' . $row->variant . ')' : '');
										}else {
											$product_name_setting = $row->product_name . ($row->variant ? ' (' . $row->variant . ')' : '');
										}
									}
									?>
										<tr>
											<td style="text-align:center; width:40px; vertical-align:middle;"><?= $r; ?></td>
											<?php if($setting->show_code == 1 && $setting->separate_code == 1) { ?>
											<td style="vertical-align:middle;">
												<?= $row->product_code ?>
											</td>
											<?php } ?>
											<td style="vertical-align:middle;">
												<?= $product_name_setting ?>
												<?= $row->details ? '<br>' . $row->details : ''; ?>
												<?= $row->serial_no ? '<br>' . $row->serial_no : ''; ?>
											</td>
											<td style="width: 80px; text-align:center; vertical-align:middle;"><?php echo $product_unit ?></td>
											<td style="width: 80px; text-align:center; vertical-align:middle;"><?= $this->erp->formatQuantity($row->quantity); ?></td>
											<td style="text-align:right; width:100px;"><?= $this->erp->formatMoney($row->unit_price); ?></td>
											<?php
											if ($Settings->tax1) {
												echo '<td style="width: 100px; text-align:right; vertical-align:middle;">' . ($row->item_tax != 0 && $row->tax_code ? '<small>('.$row->tax_code.')</small>' : '') . ' ' . $this->erp->formatMoney($row->item_tax) . '</td>';
											}
											if ($Settings->product_discount && $inv->product_discount != 0) {
												echo '<td style="width: 100px; text-align:right; vertical-align:middle;">' . ($row->discount != 0 ? '<small>(' . $row->discount . ')</small> ' : '') . $this->erp->formatMoney($row->item_discount) . '</td>';
											}
											?>
											<td style="text-align:right; width:120px;"><?= $row->subtotal!=0?$this->erp->formatMoney($row->subtotal):$free; 
												$total += $row->subtotal;
												?></td>
										</tr>
										<?php
										$r++;
									endforeach;
									?>
									<?php
									$col = 4;
									if($setting->show_code == 1 && $setting->separate_code == 1) {
										$col += 1;
									}
									if ($Settings->product_discount && $inv->product_discount != 0) {
										$col++;
									}
									if ($Settings->tax1) {
										$col++;
									}
									if ($Settings->product_discount && $inv->product_discount != 0 && $Settings->tax1) {
										$tcol = $col - 2;
									} elseif ($Settings->product_discount && $inv->product_discount != 0) {
										$tcol = $col - 1;
									} elseif ($Settings->tax1) {
										$tcol = $col - 1;
									} else {
										$tcol = $col;
									}
									?>
									<?php if ($inv->grand_total != $inv->total) { ?>
										<tr>
											<td></td>
											<td colspan="<?= $tcol; ?>"
												style="text-align:right; padding-right:10px;"><?= lang("total"); ?>
												(<?= $default_currency->code; ?>)
											</td>
											<?php
											if ($Settings->tax1) {
												echo '<td style="text-align:right;">' . $this->erp->formatMoney($inv->product_tax) . '</td>';
											}
											if ($Settings->product_discount && $inv->product_discount != 0) {
												echo '<td style="text-align:right;">' . $this->erp->formatMoney($inv->product_discount) . '</td>';
											}
											?>
											<td style="text-align:right; padding-right:10px;"><?= $this->erp->formatMoney($inv->total + $inv->product_tax); ?></td>
										</tr>
									<?php } ?>
									<?php if ($return_sale && $return_sale->surcharge != 0) {
										echo '<tr><td></td><td colspan="' . $col . '" style="text-align:right; padding-right:10px;;">' . lang("return_surcharge") . ' (' . $default_currency->code . ')</td><td style="text-align:right; padding-right:10px;">' . $this->erp->formatMoney($return_sale->surcharge) . '</td></tr>';
									}
									?>
									<?php if ($inv->order_discount != 0) {
										echo '<tr><td></td><td colspan="' . $col . '" style="text-align:right; padding-right:10px;;">' . lang("order_discount") . ' (' . $default_currency->code . ')</td><td style="text-align:right; padding-right:10px;">' . $this->erp->formatMoney($inv->order_discount) . '</td></tr>';
									}
									?>
									<?php if ($Settings->tax2 && $inv->order_tax != 0) {
										echo '<tr><td></td><td colspan="' . $col . '" style="text-align:right; padding-right:10px;">' . lang("order_tax") . ' (' . $default_currency->code . ')</td><td style="text-align:right; padding-right:10px;">' . $this->erp->formatMoney($inv->order_tax) . '</td></tr>';
									}
									?>
									<?php if ($inv->shipping != 0) {
										echo '<tr><td></td><td colspan="' . $col . '" style="text-align:right; padding-right:10px;;">' . lang("shipping") . ' (' . $default_currency->code . ')</td><td style="text-align:right; padding-right:10px;">' . $this->erp->formatMoney($inv->shipping) . '</td></tr>';
									}
									?>
									<tr>
										<td></td>
										<td colspan="<?= $col; ?>"
											style="text-align:right; font-weight:bold;"><?= lang("total_amount"); ?>
											(<?= $default_currency->code; ?>)
										</td>
										<td style="text-align:right; padding-right:10px; font-weight:bold;"><?= $this->erp->formatMoney($inv->grand_total); ?></td>
									</tr>
									<?php if ($inv->paid != 0) {?>
									<tr>
										<td></td>
										<td colspan="<?= $col; ?>"
											style="text-align:right; font-weight:bold;"><?= lang("deposit"); ?>
											(<?= $default_currency->code; ?>)
										</td>
										<td style="text-align:right; font-weight:bold;"><?= $this->erp->formatMoney($inv->paid); ?></td>
									</tr>
									<?php } ?>
									
									<tr>
										<td></td>
										<td colspan="<?= $col; ?>"
											style="text-align:right; font-weight:bold;"><?= lang("balance"); ?>
											(<?= $default_currency->code; ?>)
										</td>
										<td style="text-align:right; font-weight:bold;"><?= $this->erp->formatMoney($inv->grand_total - ($inv->paid)); ?></td>
									</tr>
									</tbody>
								</table>
							</div>
                        </div>
                    </div>
                </div>
            </div>
			<div id="avatar" class="tab-pane fade">

                <div class="box">
                    <div class="box-header">
                        <h2 class="blue"><i class="fa-fw fa fa-file-picture-o nb"></i><?= lang('down_payment'); ?></h2>
                    </div>
                    <div class="box-content">
						<div class="row">
							<div class="col-md-0">
								<div class="col-md-4">
									
									<div class="form-group">
										<label for="slcustomer"><?= lang('grand_total') ?></label>
										<input name="grand_total" type="text" id="grand_total" value="<?= $this->erp->formatDecimal($inv->grand_total); ?>" style="pointer-events:none"
											   class="form-control grand_total"
											   placeholder="<?= lang('Grand Total') ?>"/>
									</div>
								</div>
								<div class="col-md-4">
									
									<div class="form-group">
										<label for="slcustomer"><?= lang('deposit') ?></label>
										<input name="deposit" type="text" id="deposit" value="<?= (isset($inv->paid)?$this->erp->formatDecimal($inv->paid):0)?>" style="pointer-events:none"
											   class="form-control deposit"
											   placeholder="<?= lang('deposit') ?>"/>
									</div>
								</div>
								<div class="col-md-4">
									
									<div class="form-group">
										<label for="slcustomer"><?= lang('down_payment') ?></label>
										<input name="down_payment" type="text" id="down_payment"
											   class="form-control down_payment" value="<?=$order_down->down_amount?>"
											   placeholder="<?= lang('down_payment') ?>"/>
									</div>
								</div>
							</div>
						</div>
						
						<div class="row">
							<div class="col-md-0">
							   <div class="col-md-4">
									
									<div class="form-group">
										<label for="slcustomer"><?= lang('priciple_loan') ?></label>
										<input name="priciple_loan" type="text" id="priciple_loan" value="<?=$order_down->principle_amount?>"  class="form-control priciple_loan" placeholder="<?= lang('priciple_loan') ?>"/>
									</div>
								</div>
							</div>
							<div class="col-md-0">
							   <div class="col-md-4">
									
									<div class="form-group">
										<label for="slcustomer"><?= lang('priciple_term') ?></label>
										<input name="priciple_term" type="text" id="priciple_term" value="<?=$order_down->principle_term?>" class="form-control priciple_term" placeholder="<?= lang('priciple_term') ?>"/>
									</div>
								</div>
							</div>
							<div class="col-md-0">
							   <div class="col-md-4">
									
									<div class="form-group">
										<label for="slcustomer"><?= lang('loan_amount') ?></label>
										<input name="loan_amount" type="text" id="loan_amount" value="<?= $this->erp->formatDecimal($inv->grand_total); ?>" style="pointer-events:none" class="form-control loan_amount" placeholder="<?= lang('loan_amount') ?>"/>
									</div>
								</div>
							</div>
						</div>
                        <div class="row">
							<div class="depreciation_1" >
							
								<div class="row">
									<div class="col-md-12">
										<div class="col-md-4">
											<div class="form-group">
												<label for="slcustomer"><?= lang('rate_percentage') ?></label>
												<input name="depreciation_rate1" type="text" id="depreciation_rate_1" value="<?=$order_down->interest_rate?>"
													   class="form-control number_only depreciation_rate1"
													   placeholder="<?= lang('rate_percentage') ?>"/>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label for="slcustomer"><?= lang('term') ?></label>
											
												<select name="depreciation_term" id="depreciation_term_1" class="form-control kb-pad" placeholder="<?= lang('term') ?>">
												<?php
												
													$opt ='<option value=""></option>';
													foreach($terms AS $term)
													{
														$opt.="<option value=".$term->day ." ".($order_down->term==$term->day?"selected":"")." >".$term->description ."</option>";
													}
													echo $opt;
													?>
												
												?>
												</select>
											
												<input type="hidden" id="current_date" class="current_date" class="current_date[]" value="<?php echo date('m/d/Y'); ?>" />
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
											    <label for="slcustomer"><?= lang('frequency') ?></label>
												<select name="frequency" id="frequency"
														class="form-control frequency"
														placeholder="<?= lang('frequency') ?>">
													<?php
													$opt ='<option value=""></option>';
													foreach($frequency AS $fre)
													{
														$opt.="<option value=".$fre->day ." ".($order_down->frequency==$fre->day ?"selected":"")." >".$fre->description ."</option>";
													}
													echo $opt;
													?>
												</select>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label for="slcustomer"><?= lang('payment_type') ?></label>
												<select name="depreciation_type" id="depreciation_type_1"
														class="form-control depreciation_type"
														placeholder="<?= lang('payment_type') ?>">
													<option value=""> &nbsp; </option>
													<option value="1"  <?=($order_down->depreciation_type == '1'?"selected":""); ?> ><?= lang("normal"); ?></option>
													<option value="2"  <?=($order_down->depreciation_type == '2'?"selected":""); ?> ><?= lang("custom"); ?></option>
													<option value="3"  <?=($order_down->depreciation_type == '3'?"selected":""); ?> ><?= lang("fixed"); ?></option>
													<option value="4"  <?=($order_down->depreciation_type == '4'?"selected":""); ?> ><?= lang("normal_fixed"); ?></option>
												</select>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
											<label for="slcustomer"><?= lang('principle_type') ?></label>
												<select name="principle_type" id="principle_type_1"
														class="form-control principle_type"
														placeholder="<?= lang('principle_type') ?>">
													<option value="none"> None </option>
													<?php foreach($principle as $data){ ?>
														<option <?=($order_down->principle_type == $data->id?"selected":""); ?>   value="<?=$data->id?>"><?= $data->name; ?></option>
													<?php } ?>
												</select>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
											<label for="down_date"><?= lang('down_date') ?></label>
												 <?php echo form_input('down_date',  date("d/m/Y", strtotime($order_down->down_date)), 'class="form-control date" id="down_date" required="required"'); ?>
											</div>
										</div>
										
									</div>
									
									<div class="col-md-12">
										
										<div class="col-md-4">
											<div class="form-group" id="print_" style="display:none">
												<button type="button" class="btn btn-primary col-md-12 print_depre" id="print_depre" style="margin-bottom:5px;"><i class="fa fa-print"> &nbsp; </i>
													<?= lang('print') ?>
												</button>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group" id="export_" style="display:none">
												<button type="button" class="btn btn-primary col-md-12 export_depre" id="export_depre" style="margin-bottom:5px;"><i class="fa fa-file-excel-o"> &nbsp; </i>
													<?= lang('export') ?>
												</button>
												<div style="clear:both; height:15px;"></div>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="dep_tbl" style="display:none;">
										<table border="1" width="100%" class="table table-bordered table-condensed tbl_dep" id="tbl_dep1">
											<tbody>

											</tbody>
										</table>

									</div>
									<div class="dep_export" style="display:none;"></div>
								</div>
								<div class="form-group">
									<div class="dep_tbl" style="display:none;">
										<table border="1" width="100%" class="table table-bordered table-condensed tbl_dep" id="tbl_dep">
											<tbody>
										
											</tbody>
										</table>
										<table id="export_tbl" width="70%" style="display:none;">
										
										</table>
									</div>
								</div>
							</div>
							
                        </div>
                    </div>
                </div>
				<div class="box-content">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group" style="float:right">
								<?php echo form_submit('add_sale', lang("submit"), 'id="add_sale" class="btn btn-primary" style="padding: 6px 15px; margin:15px 0; display:none;"'); ?>
								<button type="submit" class="btn btn-primary" id="before_sub"><?= lang('submit') ?></button>
								<button type="button" class="btn btn-danger" id="reset"><?= lang('reset') ?></button>
							</div>
						</div>
					</div>
				</div>
            </div>
		<div id="join_lease"  class="tab-pane fade">
			<div class="box">
			        <div class="box-header">
                        <h2 class="blue"><i class="fa-fw fa fa-file-picture-o nb"></i><?= lang('join_lease'); ?></h2>
                    </div>
				
				<div class="box-content">
						<div class="row">
							<div class="col-lg-12">
								<div class="col-md-6">
									<div class="form-group">
										<input type="hidden" name="jl_id" value="<?=$jl_data->id ?>">
										<?= lang("identify_number", "jl_identify_type"); ?>
										<?php echo form_input('jl_gov_id', (isset($jl_data->identify_card) ? $jl_data->identify_card : '') , 'class="form-control number_only" id="jl_gov_id" '); ?>
									</div>
									<div class="form-group">
										<?= lang("name", "jl_name"); ?>
										<?php echo form_input('jl_name', (isset($jl_data->name) ? $jl_data->name : ''), 'class="form-control" id="jl_name" '); ?>
									</div>
									
									<div class="form-group">
										<?= lang("date_of_birth", "jl_dob"); ?>
										<?php echo form_input('jl_dob', (isset($jl_data->date_of_birth) ? date("d/m/Y", strtotime($jl_data->date_of_birth)) : ''), 'class="form-control date" id="jl_dob" '); ?>
									</div>													
									
								</div>
								<div class="col-md-6">															
									
									<div class="form-group">
										<?= lang("gender", "jl_gender"); ?>
										<?php
										$jl_gender[(isset($jl_data->gender) ? $jl_data->gender : '')] = (isset($jl_data->gender) ? $jl_data->gender : '');
										$jl_gender['ប្រុស'] = "ប្រុស";
										$jl_gender['ស្រី'] = "ស្រី";
										echo form_dropdown('jl_gender', $jl_gender, isset($jl_data->gender)?$jl_data->gender:'', 'class="form-control select" id="jl_gender" placeholder="' . lang("select") . ' ' . lang("gender") . '" style="width:100%" ')
										?>
									</div>
									
									<div class="form-group">
										<?= lang("phone", "jl_phone_1"); ?>
										<?php echo form_input('jl_phone_1',  (isset($jl_data->phone) ?$jl_data->phone : ''), 'class="form-control number_only" id="jl_phone_1" ');?>
									</div>
									
									<div class="form-group">
										<?= lang("age", "age"); ?>
										<?php echo form_input('jl_age', (isset($_POST['jl_age']) ? $_POST['jl_age'] : ''), 'class="form-control" id="jl_age" style="pointer-events:none;"');?>
									</div>
								</div>
								<div class="col-sm-12">
									<div class="form-group">
										<?= lang("address", "jl_address"); ?>
										<?php echo form_textarea('jl_address', (isset($jl_data->address) ? $jl_data->address : ""), 'class="form-control" id="jl_address" style="margin-top: 10px; height: 130px;"'); ?>
									</div>
								</div>
							</div>
						</div>
					
				</div>
				<div class="box-content">
					<div class="row">
						<div class="col-md-6">
								<div class="form-group">
								</div>
							</div>
						<div class="col-md-6">
							<div class="form-group" style="float:right">
								<?php echo form_submit('add_sale', lang("submit"), 'id="add_sale" class="btn btn-primary" style="padding: 6px 15px; margin:15px 0; display:none;"'); ?>
								<button type="submit" class="btn btn-primary" id="before_sub"><?= lang('submit') ?></button>
								<button type="button" class="btn btn-danger" id="reset"><?= lang('reset') ?></button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<?php echo form_close(); ?>
        </div>
    </div>

	<script type="text/javascript" src="<?= $assets ?>js/jquery-2.0.3.min.js"></script>
    <script type="text/javascript" src="<?= $assets ?>js/jquery-migrate-1.2.1.min.js"></script>
	<script type="text/javascript" src="<?= $assets ?>js/bootstrap.min.js"></script>
	<script type="text/javascript" src="<?= $assets ?>js/jquery-ui.min.js"></script>
	<script type="text/javascript" src="<?= $assets ?>js/perfect-scrollbar.min.js"></script>
	<script type="text/javascript" src="<?= $assets ?>js/select2.min.js"></script>
	<script type="text/javascript" src="<?= $assets ?>js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" src="<?= $assets ?>js/custom.js"></script>
	<script type="text/javascript" src="<?= $assets ?>js/jquery.calculator.min.js"></script>
	<script type="text/javascript" src="<?= $assets ?>js/bootstrapValidator.min.js"></script>
	<script type="text/javascript" src="<?= $assets ?>pos/js/plugins.min.js"></script>
	<script type="text/javascript" src="<?= $assets ?>pos/js/parse-track-data.js"></script>
	
	
    <script type="text/javascript" charset="utf-8">
$( document ).ready(function() {	

    $('#jl_dob').trigger("livechange");
	$('#down_payment').trigger("change");
	
	var site = <?= json_encode(array('base_url' => base_url(), 'settings' => $Settings, 'dateFormats' => $dateFormats)) ?> 
	
		$('.dateline').datetimepicker({
			format: site.dateFormats.js_sdate, 
			fontAwesome: true, 
			language: 'erp', 
			todayBtn: 1, 
			autoclose: 1, 
			minView: 2 
		});
		
		$(document).on('focus','.dateline', function(t) {
			$(this).datetimepicker({format: site.dateFormats.js_sdate, fontAwesome: true, todayBtn: 1, autoclose: 1, minView: 2 });
		});
		var down_date = '<?=$order_down->down_date?>';
		if((down_date)=="")
		{
			$("#down_date").datetimepicker({
                format: site.dateFormats.js_sdate,
                fontAwesome: true,
                language: 'erp',
                weekStart: 1,
                todayBtn: 1,
                autoclose: 1,
                todayHighlight: 1,
                startView: 2,
                forceParse: 0
			}).datetimepicker('update', new Date());
		}
		
		
		$('#jl_dob').live('change', function() {
			var dob = $(this).val();
			if(dob != '') {
				var age = getAge(dob);
				$('#jl_age').val(age +' Year old');
			}else {
				$('#jl_age').val('');
			}
		}).trigger("change");
		
		$('#dob').live('change', function() {
			var dob = $(this).val();
			if(dob != '') {
				var age = getAge(dob);
				$('#age').val(age +' Year old');
			}else {
				$('#age').val('');
			}
		});
		
		$("#priciple_loan").on("change",function(){
			var ds        = $(this).val();
			var total     = $("#grand_total").val()-0;
			var deposit   = $("#deposit").val()-0;
			var down_pay  = $("#down_payment").val()-0;
			
 			if (ds.indexOf("%") !== -1) {
				var pds = ds.split("%");
				if (!isNaN(pds[0])) {
					principal_loan = parseFloat(((total) * parseFloat(pds[0])) / 100);
				
				} else {
					principal_loan = parseFloat((total * ds) / 100);
				}
				
				$(this).val((principal_loan-(deposit+down_pay)).toFixed(2));
				
			} else {
				principal_loan = parseFloat(ds);
			}
			
			var prin_loan = $("#priciple_loan").val()-0;
			
			$("#loan_amount").val((total-(prin_loan+deposit+down_pay)).toFixed(2));
			
		});
		
			
		$("#down_payment").on("change",function()
		{
			
			var ds 			  = $(this).val();
			var down_payment  = 0 ;
			var grand_total   = $("#grand_total").val()-0;
			var prin_loan     = $("#priciple_loan").val()-0;
			var deposit       = $("#deposit").val()-0;
			var balance		  = parseFloat(grand_total);
            if (ds.indexOf("%") !== -1) {
				
                var pds = ds.split("%");
                if (!isNaN(pds[0])) {
                    down_payment = parseFloat(((balance) * parseFloat(pds[0])) / 100);
                } else {
                    down_payment = parseFloat((balance * (ds-0)) / 100);
                }
				$(this).val((down_payment).toFixed(2));
            } else {
                down_payment = (parseFloat(ds-0));
				
            }
			
			$("#loan_amount").val((balance-(prin_loan+deposit+down_payment)).toFixed(2));
			$("#loan_amount").trigger("change");
		});
		
		/* ######Loan Funtion###### */
		
		$('#depreciation_type_1, #depreciation_rate_1, #depreciation_term_1, #loan_amount, #frequency, #down_date').on("change",function() {
			$("#depreciation_term_1,#frequency,#depreciation_type_1").attr("disabled",false);
			var p_type        = $('#depreciation_type_1').val();
			var pr_type       = $('#principle_type_1').val();
			var rate          = $('#depreciation_rate_1').val();
			var term          = $('#depreciation_term_1').val();
			var frequency     = $("#frequency option:selected").val();
			var option        = $("#principle_type_1 option:selected").val();
			var total_amount  = $('#grand_total').val()-0;
			var deposit       = $('#deposit').val()-0; 
			var dateString    = $("#down_date").val(); // Oct 23
			var dateParts     = dateString.split("/");
			var dateObject    = new Date(dateParts[2], dateParts[1] - 1, dateParts[0]); // month is 0-based
			var down_date     = dateObject;
			var down_pay      = $('#down_payment').val()-0;
			var loan_amount   = total_amount - down_pay;
				
			alert("ss");
			if(pr_type=='none') {
				depreciation(loan_amount,rate,term,frequency,p_type,total_amount,down_date);
			}else{
				$("#frequency,#depreciation_term_1").attr("disabled",true);
				principal(option,loan_amount,p_type,down_date,rate);
			}
		}).trigger("change");
 		
		
		$('#principle_type_1, #loan_amount,#down_date ,#down_payment').on("change",function()
		{
			var option = $("#principle_type_1 option:selected").val();
			if(option!='none')
			{
				$("#frequency,#depreciation_term_1").attr("disabled",true);
			}else{
				$("#frequency,#depreciation_term_1").attr("disabled",false);
			}
			var p_type        = $('#depreciation_type_1').val();
			var total_amount  = $('#grand_total').val()-0;
			var down_pay 	  = $('#down_payment').val()-0;
			var deposit       = $('#deposit').val()-0; 
			var rate 		  = $('#depreciation_rate_1').val();
			var dateString    = $("#down_date").val(); // Oct 23
			var dateParts     = dateString.split("/");
			var dateObject    = new Date(dateParts[2], dateParts[1] - 1, dateParts[0]); // month is 0-based
			var down_date     = dateObject;
			var loan_amount   = total_amount - down_pay;
			
			if(option!='none')
			{
				principal(option,loan_amount,p_type,down_date,rate);
			}
			
			
		});//.trigger("change");
		

	function principal(principal_id,amount,p_type,start_date,rate)
	{
			var principal_id = principal_id;
		
			$.ajax({
					type: 'get',
					url: '<?= site_url('sales/getPrinciple_id'); ?>',
					dataType: "json",
					data: {
						principal_id: principal_id
					},
					success: function (data) {
						var prin    = data.principle;
						var term    =  prin.length;
						
			frequency = parseFloat(frequency);
			var d = new Date();
			if(p_type == ''){
				$('#print_').hide();
				$('#export_').hide();
				return false;
			}else{
				$('#print_').show();
				$('#export_').show();
				if(rate == '' || rate < 0) {
					if(term == '' || term <= 0) {
						$('.dep_tbl').hide();
						alert("Please choose Rate and Term again!");
						return false;
					}else{
						$('.dep_tbl').hide();
						alert("Please choose Rate again!"); 
						return false;
					}
				}else{
					if(term == '' || term <= 0) {
						$('.dep_tbl').hide();
						alert("Please choose Term again!"); 
						return false;
					}else{
						var tr = '';
						if(p_type == 1 || p_type == 3 || p_type == 4){
							tr += '<tr>';
							tr += '<th class="text-center"> <?= lang("Pmt No."); ?> </th>';
							tr += '<th class="text-center"> <?= lang("loan_interest"); ?> </th>';
							tr += '<th class="text-center"> <?= lang("loan_principal"); ?> </th>';
							tr += '<th class="text-center"> <?= lang("loan_total_payment"); ?> </th>';
							tr += '<th class="text-center"> <?= lang("loan_balance1"); ?> </th>';
							tr += '<th class="text-center"> <?= lang("loan_note"); ?> </th>';
							tr += '<th class="text-center"> <?= lang("loan_payment_date"); ?> </th>';
							tr += '</tr>';
						}else if(p_type == 2){
							tr += '<tr>';
							tr += '<th class="text-center"> <?= lang("loan_period"); ?> </th>';
							tr += '<th class="text-center"> <?= lang("loan_rate"); ?> </th>';
							tr += '<th class="text-center"> <?= lang("loan_percentage"); ?> </th>';
							tr += '<th class="text-center"> <?= lang("loan_payment"); ?> </th>';
							tr += '<th class="text-center"> <?= lang("loan_total_payment"); ?> </th>';
							tr += '<th class="text-center"> <?= lang("loan_balance1"); ?> </th>';
							tr += '<th class="text-center"> <?= lang("loan_note"); ?> </th>';
							tr += '<th class="text-center"> <?= lang("loan_dateline"); ?> </th>';
							tr += '</tr>';
						}
					
						if(p_type == 1){
							var principle = 0;//amount/term;
							var interest = 0;
							var balance = amount;
							var payment = 0;
							var k=0;
							var total_principle = 0;
							var total_payment = 0;
							var j  = 1;
							$.each(prin, function(i, data_principle) {
									
									if(data_principle.rate==0)
									{
										 rate = 0;
									}else{
										 rate = rate;		
									}
									
									
									if(i== 0){
										interest = ((rate!=0?amount*(rate/100):0));
										var dateline = moment(data_principle.dateline).format('DD/MM/YYYY');
										
									}else{
										interest = ((rate!=0?balance*(rate/100):0));
										var dateline = moment(data_principle.dateline).format('DD/MM/YYYY');
									}
									
									var define_prin = data_principle.value;
									principle =  define_prin.replace('%', '')-0;
									percent = (principle!=0?(principle):0);
									principal_1 = (amount*(percent/100));
									payment = principal_1 + interest;
									
									balance -= principal_1;
									if(balance <= 0){
										balance = 0;
									}
									
									tr += '<tr> <td class="text-center">'+ j +'<input type="hidden" name="no[]" id="no" class="no" value="'+ i +'" /></td> ';
									tr += '<td>'+ formatMoney(interest) +'<input type="hidden" name="interest[]" id="interest" class="interest" width="90%" value="'+ formatDecimal(interest) +'"/></td>';
									tr += '<td>'+ formatMoney(principal_1) +'<input type="hidden" name="principle[]" id="principle" class="principle" width="90%" value="'+ formatDecimal(principal_1) +'"/></td>';
									tr += '<td>'+ formatMoney(payment) +'<input type="hidden" name="payment_amt[]" id="payment_amt" class="payment_amt" width="90%" value="'+ formatDecimal(payment) +'"/></td>';
									tr += '<td>'+ formatMoney(balance) +'<input type="hidden" name="balance[]" id="balance" class="balance" width="90%" value="'+ formatDecimal(balance) +'"/></td>';
									tr += '<td> <input name="note[]" class="note form-control" id="'+j+'" ></input> <input type="hidden" name="note1[]" id="note1" class="note1_'+i+'" width="90%"/></td>';
									tr += '<td>'+ dateline +'<input type="hidden" class="dateline" name="dateline[]" id="dateline" value="'+ dateline +'" /> </td> </tr>';
									total_principle += principal_1;
									total_payment += payment;
									
									j++;
								
							 
							});

							tr += '<tr> <td colspan="2"> <?= lang("total"); ?> </td>';
							tr += '<td>'+ formatMoney(total_principle) +'</td>';
							tr += '<td>'+ formatMoney(total_payment) +'</td>';
							tr += '<td colspan="3"> &nbsp; </td> </tr>';
						}else if(p_type == 2) {
							var principle = 0;
							var interest = 0;
							var percent = 0;
							var balance = amount;
							var rate_amount = ((rate/100));
							var g_total_payment = 0;
							var g_payment = 0;
							var j=1;
							
							$.each(prin, function(i, data_principle) {
								
								if(data_principle.rate==0)
								{	
									 rate = 0;	
								}else{
									 rate = rate;
								}
								
								if(i== 0){
									
									
									
									var dateline = moment(data_principle.dateline).format('DD/MM/YYYY');
									interest = ((rate!=0?amount*(rate/100):0));
									var define_prin = data_principle.value;
									principle =  define_prin.replace('%', '')-0;
									percent = (principle!=0?(principle):0);
									payment = (amount*(percent/100));
									total_payment = (payment+interest);
									balance-=payment;
									
									tr += '<tr> <td class="text-center">'+ j +'<input type="hidden" name="no[]" id="no" class="no" value="'+ j +'" /></td> ';
									tr += '<td><input type="text" name="rate[]" id="rate" class="rate" style="width:60px;" value="'+ formatDecimal(interest) +'"/><input type="hidden" name="interest[]" id="interest" class="interest" width="90%" value="'+ formatDecimal(interest) +'"/></td>';
									tr += '<td><input type="text" name="percentage[]" id="percentage" class="percentage" style="width:60px;" value="'+ percent.toFixed(4) +'"/><input type="hidden" name="percentage_[]" id="percentage_" class="percentage_" style="width:60px;" value="'+ percent +'"/></td>';
									tr += '<td><input type="text" name="pmt_principle[]" id="pmt_principle" class="pmt_principle" style="width:60px;" value="'+ formatDecimal(payment) +'" /><input type="hidden" name="principle[]" id="principle" class="principle" width="90%" value="'+ formatDecimal(principle) +'"/></td>';
									tr += '<td><input type="text" name="total_payment[]" id="total_payment" class="total_payment" style="width:60px;" value="'+ formatDecimal(total_payment) +'" readonly/><input type="hidden" name="payment_amt[]" id="payment_amt" class="payment_amt" width="90%" value="'+ formatDecimal(total_payment) +'"/></td>';
									tr += '<td><input type="text" name="amt_balance[]" id="amt_balance" class="amt_balance" style="width:60px;" value="'+ formatDecimal(balance) +'" readonly/><input type="hidden" name="balance[]" id="balance" class="balance" style="width:60px;" value="'+ formatDecimal(balance) +'"/></td>';
									tr += '<td> <input name="note[]" class="note form-control" id="'+j+'" ></input> <input type="hidden" name="note1[]" id="note1" class="note1_'+j+'" width="90%"/></td>';
									tr += '<td><input type="text" class="dateline" name="dateline[]" id="dateline" value="'+ dateline +'" size="6" /></td> </tr>';
								}else{
									
									interest = ((rate!=0?balance*(rate/100):0));
									var define_prin = data_principle.value;
									principle =  define_prin.replace('%', '')-0; 
									var dateline = moment(data_principle.dateline).format('DD/MM/YYYY');
									percent = (principle!=0?(principle):0);
									payment = (amount*(percent/100));
									total_payment = (payment+interest);
									balance-=payment;
									
									
									tr += '<tr> <td class="text-center">'+ j +'<input type="hidden" name="no[]" id="no" class="no" value="'+ j +'" /></td> ';
									tr += '<td><input type="text" name="rate[]" id="rate" class="rate" style="width:60px;" value="'+ formatDecimal(interest) +'"/><input type="hidden" name="interest[]" id="interest" class="interest" width="90%" value="'+ formatDecimal(interest) +'"/></td>';
									tr += '<td><input type="text" name="percentage[]" id="percentage" class="percentage" style="width:60px;" value="'+ percent.toFixed(4) +'"/><input type="hidden" name="percentage_[]" id="percentage_" class="percentage_" style="width:60px;" value="'+ percent +'"/></td>';
									tr += '<td><input type="text" name="pmt_principle[]" id="pmt_principle" class="pmt_principle" style="width:60px;" value="'+ formatDecimal(payment) +'" /><input type="hidden" name="principle[]" id="principle" class="principle" width="90%" value="'+ formatDecimal(principle) +'"/></td>';
									tr += '<td><input type="text" name="total_payment[]" id="total_payment" class="total_payment" style="width:60px;" value="'+ formatDecimal(total_payment) +'" readonly/><input type="hidden" name="payment_amt[]" id="payment_amt" class="payment_amt" width="90%" value="'+ formatDecimal(payment) +'"/></td>';
									tr += '<td><input type="text" name="amt_balance[]" id="amt_balance" class="amt_balance" style="width:60px;" value="'+ formatDecimal(balance) +'" readonly/><input type="hidden" name="balance[]" id="balance" class="balance" style="width:60px;" value="'+ formatDecimal(balance) +'"/></td>';
									tr += '<td> <input name="note[]" class="note form-control" id="'+j+'" ></input><input type="hidden" name="note1[]" id="note1" class="note1_'+j+'" width="90%"/></td>';
									tr += '<td><input type="text" class="dateline" name="dateline[]" id="dateline" value="'+ dateline +'" size="6" /></td> </tr>';
								
								}
								
								
								
								g_payment += payment;
								g_total_payment += total_payment;
								j++;
							});
					
							tr += '<tr> <td colspan="3"> <?= lang("Total"); ?> </td>';
							tr += '<td><input type="text" name="total_pay" id="total_pay" class="total_pay" style="width:60px;" value="'+ formatDecimal(g_payment) +'" readonly/></td>';
							tr += '<td><input type="text" name="total_amount" id="total_amount" class="total_amount" style="width:60px;" value="'+ formatDecimal(g_total_payment) +'" readonly/></td>';
							tr += '<td colspan="3"> &nbsp; </td> </tr>';
						}else if(p_type == 3) {
							var principle = 0;
							var interest = 0;
							var balance = amount;
							var rate_amount = ((rate/100));
							var payment = ((amount * rate_amount)*((Math.pow((1+rate_amount),term))/(Math.pow((1+rate_amount),term)-1)));
							var j=1;
							var total_principle = 0;
							var total_payment = 0;
							$.each(prin, function(i, data_principle) {
								
								if(data_principle.rate==0)
								{
									 rate = 0;
								}else{
									 rate = rate;		
								}
								
								
								if(i== 0){
									interest = ((rate!=0?amount*(rate/100):0));
									var dateline = moment(data_principle.dateline).format('DD/MM/YYYY');
									
								}else{
									interest = ((rate!=0?balance*(rate/100):0));
									var dateline = moment(data_principle.dateline).format('DD/MM/YYYY');
								}
								
								var define_prin = data_principle.value;
								principle  =  define_prin.replace('%', '')-0; 
								percent    = (principle!=0?(principle):0);
								payment    = (amount*(percent/100));
								
								principle_1  = payment + interest;
								balance   -= principle_1;
								
								if(balance <= 0){
									balance = 0;
								}
								tr += '<tr> <td class="text-center">'+ j +'<input type="hidden" name="no[]" id="no" class="no" value="'+ j +'" /></td> ';
								tr += '<td>'+ formatMoney(interest) +'<input type="hidden" name="interest[]" id="interest" class="interest" width="90%" value="'+ formatDecimal(interest) +'"/></td>';
								tr += '<td>'+ formatMoney(principle_1) +'<input type="hidden" name="principle[]" id="principle" class="principle" width="90%" value="'+ principle_1 +'"/></td>';
								tr += '<td>'+ formatMoney(payment) +'<input type="hidden" name="payment_amt[]" id="payment_amt" class="payment_amt" width="90%" value="'+ formatDecimal(payment) +'"/></td>';								
								tr += '<td>'+ formatMoney(balance) +'<input type="hidden" name="balance[]" id="balance" class="balance" width="90%" value="'+ formatDecimal(balance) +'"/></td>';
								tr += '<td> <input name="note[]" class="note form-control" id="'+j+'" ></input> <input type="hidden" name="note1[]" id="note1" class="note1_'+j+'" width="90%"/></td>';
								tr += '<td>'+ dateline +'<input type="hidden" class="dateline" name="dateline[]" id="dateline" value="'+ dateline +'" /></td> </tr>';
								total_principle += principle_1;
								total_payment += payment;
								j++;
								
							});
							tr += '<tr> <td colspan="2"> <?= lang("Total"); ?> </td>';
							tr += '<td>'+ formatMoney(total_principle) +'</td>';
							tr += '<td>'+ formatMoney(total_payment) +'</td>';
							tr += '<td colspan="3"> &nbsp; </td> </tr>';
						} else if(p_type == 4){
							var principle = 0;
							var interest = (amount * (rate/100));
							var balance = amount;
							var payment = 0;
							var j=1;
							var total_principle = 0;
							var total_payment = 0;
							$.each(prin, function(i, data_principle) {
								if(data_principle.rate==0)
								{
									 rate = 0;
								}else{
									 rate = rate;		
								}
								
								//interest = ((rate!=0?amount*(rate/100):0));
								
								if(i== 0){
									interest = ((rate!=0?amount*(rate/100):0));
									var dateline = moment(data_principle.dateline).format('DD/MM/YYYY');
									
								}else{
									interest = ((rate!=0?balance*(rate/100):0));
									var dateline = moment(data_principle.dateline).format('DD/MM/YYYY');
								}
								
								var define_prin = data_principle.value;
								principle  =  define_prin.replace('%', '')-0; 
								percent    = (principle!=0?(principle):0);
								principal_1    = (amount*(percent/100));
								
								payment = principal_1 + interest;
								
								balance -= principal_1;
								if(balance <= 0){
									balance = 0;
								}
								tr += '<tr> <td class="text-center">'+ j +'<input type="hidden" name="no[]" id="no" class="no" value="'+ j+'" /></td> ';
								tr += '<td>'+ formatMoney(interest) +'<input type="hidden" name="interest[]" id="interest" class="interest" width="90%" value="'+ interest +'"/></td>';
								tr += '<td>'+ formatMoney(principal_1) +'<input type="hidden" name="principle[]" id="principle" class="principle" width="90%" value="'+ principal_1 +'"/></td>';
								tr += '<td>'+ formatMoney(payment) +'<input type="hidden" name="payment_amt[]" id="payment_amt" class="payment_amt" width="90%" value="'+ payment +'"/></td>';
								tr += '<td>'+ formatMoney(balance) +'<input type="hidden" name="balance[]" id="balance" class="balance" width="90%" value="'+ balance +'"/></td>';
								tr += '<td> <input name="note[]" class="note form-control" id="'+j+'" ></input> <input type="hidden" name="note1[]" id="note1" class="note1_'+j+'" width="90%"/></td>';
								tr += '<td>'+ dateline +'<input type="hidden" class="dateline" name="dateline[]" id="dateline" value="'+ dateline +'" /> </td> </tr>';
								total_principle += principal_1;
								total_payment += payment;
								j++;
							});
							tr += '<tr> <td colspan="2"> <?= lang("total"); ?> </td>';
							tr += '<td>'+ formatMoney(total_principle) +'</td>';
							tr += '<td>'+ formatMoney(total_payment) +'</td>';
							tr += '<td colspan="3"> &nbsp; </td> </tr>';
						}
						$('.dep_tbl').show();
						$('#tbl_dep').html(tr);
						//$('#tbl_dep1').html(tr);
						$("#loan1").html(tr);
					}
				}
			}
		}
	  });   
						
	} 
		
		
		
	function depreciation(amount,rate,term_of_day,frequency,p_type,total_amount, start_date){
		
		var term = (term_of_day/frequency).toFixed(0);
		frequency = parseFloat(frequency);
		
		var priciple_term = $("#priciple_term").val()-1;
		var priciple_loan = $("#priciple_loan").val()-0;
		var d = new Date();
		if(p_type == ''){
			$('#print_').hide();
			$('#export_').hide();
			return false;
		}else{
			$('#print_').show();
			$('#export_').show();
			if(rate == '' || rate < 0) {
				if(term == '' || term <= 0) {
					$('.dep_tbl').hide();
					alert("Please choose Rate and Term again!");
					return false;
				}else{
					$('.dep_tbl').hide();
					alert("Please choose Rate again!"); 
					return false;
				}
			}else{
				if(term == '' || term <= 0) {
					$('.dep_tbl').hide();
					alert("Please choose Term again!"); 
					return false;
				}else{
					var tr = '';
					if(p_type == 1 || p_type == 3 || p_type == 4){
						tr += '<tr>';
						tr += '<th class="text-center"> <?= lang("Pmt No."); ?> </th>';
						tr += '<th class="text-center"> <?= lang("interest"); ?> </th>';
						tr += '<th class="text-center"> <?= lang("principal"); ?> </th>';
						tr += '<th class="text-center"> <?= lang("total_payment"); ?> </th>';
						tr += '<th class="text-center"> <?= lang("balance"); ?> </th>';
						tr += '<th class="text-center"> <?= lang("note"); ?> </th>';
						tr += '<th class="text-center"> <?= lang("payment_date"); ?> </th>';
						tr += '</tr>';
					}else if(p_type == 2){
						tr += '<tr>';
						tr += '<th class="text-center"> <?= lang("loan_period"); ?> </th>';
						tr += '<th class="text-center"> <?= lang("loan_percentage"); ?> </th>';
						tr += '<th class="text-center"> <?= lang("loan_rate"); ?> </th>';
						tr += '<th class="text-center"> <?= lang("loan_total_payment"); ?> </th>';
						tr += '<th class="text-center"> <?= lang("loan_principal"); ?> </th>';
						tr += '<th class="text-center"> <?= lang("loan_balance1"); ?> </th>';
						tr += '<th class="text-center"> <?= lang("loan_note"); ?> </th>';
						tr += '<th class="text-center"> <?= lang("loan_dateline"); ?> </th>';
						tr += '</tr>';
					}
					
					var priciple_loan = $("#priciple_loan").val()-0;
					var amount_loan	  = $("#loan_amount").val()-0;
					var total_loan	  = (priciple_loan+amount_loan);
					var balance 	  = Math.round(total_loan,2);
					var total_principle = 0;
					var total_payment = 0;
					var k=0;
					//Loan Priciple Calculate
					var a =1;
					for(i=1;i<=priciple_term;i++){
						
						if(i== 1){
							var dateline  = moment(start_date).format('DD/MM/YYYY');
								principle = Math.round(priciple_loan/priciple_term);
								balance  -= principle;
							if(balance   <= 0){
								balance   = 0;
							}
							
							tr += '<tr> <td class="text-center">'+ a +'<input type="hidden" name="no[]" id="no" class="no" value="'+ a +'" /></td> ';
							tr += '<td><input type="text" name="rate[]" id="rate" class="rate" style="width:60px;" value=""/><input type="hidden" name="interest[]" id="interest" class="interest" width="90%" value=""/></td>';
							tr += '<td><input type="text" name="percentage[]" id="percentage" class="percentage" style="width:60px;" value=""/><input type="hidden" name="percentage_[]" id="percentage_" class="percentage_" style="width:60px;" value=""/></td>';
							tr += '<td><input type="text" name="pmt_principle[]" id="pmt_principle" class="pmt_principle" style="width:60px;" value="'+ formatDecimal(principle) +'" /><input type="hidden" name="principle[]" id="principle" class="principle" width="90%" value="'+ formatDecimal(principle) +'"/></td>';
							tr += '<td><input type="text" name="total_payment[]" id="total_payment" class="total_payment" style="width:60px;" value="'+ formatDecimal(principle) +'" readonly/><input type="hidden" name="payment_amt[]" id="payment_amt" class="payment_amt" width="90%" value="'+ formatDecimal(principle) +'"/></td>';
							tr += '<td><input type="text" name="amt_balance[]" id="amt_balance" class="amt_balance" style="width:60px;" value="'+ formatDecimal(balance) +'" readonly/><input type="hidden" name="balance[]" id="balance" class="balance" style="width:60px;" value="'+ formatDecimal(balance) +'"/></td>';
							tr += '<td> <input name="note[]" class="note form-control" id="'+a+'" ></input> <input type="hidden" name="note1[]" id="note1" class="note1_'+a+'" width="90%"/></td>';
							tr += '<td><input type="text" class="dateline" name="dateline[]" id="dateline" value="'+ dateline +'" size="6" /></td> </tr>';
							
						}else{
						
								dateline  = moment(dateline).add(30,'days').format('DD/MM/YYYY');
								principle = Math.round(priciple_loan/priciple_term);
								balance  -= principle;
							if(balance   <= 0){
								balance   = 0;
							}
							
							tr += '<tr> <td class="text-center">'+ a +'<input type="hidden" name="no[]" id="no" class="no" value="'+ a +'" /></td> ';
							tr += '<td><input type="text" name="rate[]" id="rate" class="rate" style="width:60px;" value=""/><input type="hidden" name="interest[]" id="interest" class="interest" width="90%" value=""/></td>';
							tr += '<td><input type="text" name="percentage[]" id="percentage" class="percentage" style="width:60px;" value=""/><input type="hidden" name="percentage_[]" id="percentage_" class="percentage_" style="width:60px;" value=""/></td>';
							tr += '<td><input type="text" name="pmt_principle[]" id="pmt_principle" class="pmt_principle" style="width:60px;" value="'+ formatDecimal(principle) +'" /><input type="hidden" name="principle[]" id="principle" class="principle" width="90%" value="'+ formatDecimal(principle) +'"/></td>';
							tr += '<td><input type="text" name="total_payment[]" id="total_payment" class="total_payment" style="width:60px;" value="'+ formatDecimal(principle) +'" readonly/><input type="hidden" name="payment_amt[]" id="payment_amt" class="payment_amt" width="90%" value="'+ formatDecimal(principle) +'"/></td>';
							tr += '<td><input type="text" name="amt_balance[]" id="amt_balance" class="amt_balance" style="width:60px;" value="'+ formatDecimal(balance) +'" readonly/><input type="hidden" name="balance[]" id="balance" class="balance" style="width:60px;" value="'+ formatDecimal(balance) +'"/></td>';
							tr += '<td> <input name="note[]" class="note form-control" id="'+a+'" ></input><input type="hidden" name="note1[]" id="note1" class="note1_'+a+'" width="90%"/></td>';
							tr += '<td><input type="text" class="dateline" name="dateline[]" id="dateline" value="'+ dateline +'" size="6" /></td> </tr>';
						}
						
						total_principle += principle;
						total_payment   += principle;
						a++;
						k+=frequency;
					}
						
					//End 

					if(p_type == 1){
						var principle = total_loan/term;
						var interest  = 0;
						      balance = (balance?balance:total_loan);
						var payment   = 0;
						
						for(i=1;i<=term;i++){
							if(i== 1){
								interest = (total_loan*(rate/100));
								var dateline = moment(start_date).add(k,'days').format('DD/MM/YYYY');
							}else{
								interest = balance *((rate/100));
								var dateline = moment(start_date).add(k,'days').format('DD/MM/YYYY');
							}
							balance -= principle;
							if(balance <= 0){
								balance = 0;
							}
							payment = principle + interest;
							tr += '<tr> <td class="text-center">'+ a +'<input type="hidden" name="no[]" id="no" class="no" value="'+ a +'" /></td> ';
							tr += '<td>'+ formatMoney(interest) +'<input type="hidden" name="interest[]" id="interest" class="interest" width="90%" value="'+ formatDecimal(interest) +'"/></td>';
							tr += '<td>'+ formatMoney(principle) +'<input type="hidden" name="principle[]" id="principle" class="principle" width="90%" value="'+ formatDecimal(principle) +'"/></td>';
							tr += '<td>'+ formatMoney(payment) +'<input type="hidden" name="payment_amt[]" id="payment_amt" class="payment_amt" width="90%" value="'+ formatDecimal(payment) +'"/></td>';
							tr += '<td>'+ formatMoney(balance) +'<input type="hidden" name="balance[]" id="balance" class="balance" width="90%" value="'+ formatDecimal(balance) +'"/></td>';
							tr += '<td> <input name="note[]" class="note form-control" id="'+a+'" ></input> <input type="hidden" name="note1[]" id="note1" class="note1_'+a+'" width="90%"/></td>';
							tr += '<td>'+ dateline +'<input type="hidden" class="dateline" name="dateline[]" id="dateline" value="'+ dateline +'" /> </td> </tr>';
							total_principle += principle;
							total_payment   += payment;
							k+= frequency;
							a++;
						}
						tr += '<tr> <td colspan="2"> <?= lang("total"); ?> </td>';
						tr += '<td>'+ formatMoney(total_principle) +'</td>';
						tr += '<td>'+ formatMoney(total_payment) +'</td>';
						tr += '<td colspan="3"> &nbsp; </td> </tr>';
					}else if(p_type == 2) {
						
						//var balance = (balance?balance:total_loan);
						var interest  = 0;
						var principle = balance;
						var rate_amount = ((rate/100));
						var payment = Math.round((balance * rate_amount)*((Math.pow((1+rate_amount),term))/(Math.pow((1+rate_amount),term)-1)),2);
						
						for(i=1;i<=term;i++){
							
								if(i==1 && k>0){
									interest = (balance*(rate/100));
									var dateline = moment(start_date).add(k+31,'days').format('DD/MM/YYYY');
								}else if(i==1 && k==0){
									interest = (balance*(rate/100));
									var dateline = moment(start_date).format('DD/MM/YYYY');
								}else{
									interest = (balance*(rate/100));
									var dateline = moment(start_date).add(k+31,'days').format('DD/MM/YYYY');
								}
								
								
								if(i==1){
									percent   = (principle / (balance)) / 100;	
								}else{
									percent   = (principle / (balance)) * 100;	
								}
								
								principle = payment - interest;
								balance  -= principle;
								if(balance <= 0){
									balance = 0;
								}else if(i==term){
									principle   = principle+balance;
									balance 	= 0;
								}
								
								tr += '<tr> <td class="text-center">'+ a +'<input type="hidden" name="no[]" id="no" class="no" value="'+ a +'" /></td> ';
								
								tr += '<td><input type="text" name="percentage[]" id="percentage" class="percentage" style="width:60px;" value="'+ percent.toFixed(4) +'"/><input type="hidden" name="percentage_[]" id="percentage_" class="percentage_" style="width:60px;" value="'+ percent +'"/></td>';
								tr += '<td><input type="text" name="rate[]" id="rate" class="rate" style="width:60px;" value="'+ formatDecimal(interest) +'"/><input type="hidden" name="interest[]" id="interest" class="interest" width="90%" value="'+ formatDecimal(interest) +'"/></td>';
								tr += '<td><input type="text" name="total_payment[]" id="total_payment" class="total_payment" style="width:60px;" value="'+ formatDecimal(payment) +'" readonly/><input type="hidden" name="payment_amt[]" id="payment_amt" class="payment_amt" width="90%" value="'+ formatDecimal(payment) +'"/></td>';
								tr += '<td><input type="text" name="pmt_principle[]" id="pmt_principle" class="pmt_principle" style="width:60px;" value="'+ formatDecimal(principle) +'" /><input type="hidden" name="principle[]" id="principle" class="principle" width="90%" value="'+ formatDecimal(principle) +'"/></td>';
								tr += '<td><input type="text" name="amt_balance[]" id="amt_balance" class="amt_balance" style="width:60px;" value="'+ formatDecimal(balance) +'" readonly/><input type="hidden" name="balance[]" id="balance" class="balance" style="width:60px;" value="'+ formatDecimal(balance) +'"/></td>';
								tr += '<td> <input name="note[]" class="note form-control" id="'+a+'" ></input> <input type="hidden" name="note1[]" id="note1" class="note1_'+a+'" width="90%"/></td>';
								tr += '<td><input type="text" class="dateline" name="dateline[]" id="dateline" value="'+ dateline +'" size="6" /></td> </tr>';

							
							total_principle += principle;
							total_payment   += payment;
							k+= frequency;
							a++;
						}
						tr += '<tr> <td colspan="3"> <?= lang("Total"); ?> </td>';
						tr += '<td><input type="text" name="total_amount" id="total_amount" class="total_amount" style="width:60px;" value="'+ formatDecimal(total_payment) +'" readonly/></td>';
						
						tr += '<td><input type="text" name="total_pay" id="total_pay" class="total_pay" style="width:60px;" value="'+ formatDecimal(total_principle) +'" readonly/></td>';
						tr += '<td colspan="4"> &nbsp; </td> </tr>';
						
					}else if(p_type == 3) {
						var principle = 0;
						var interest = 0;
						var balance = (balance?balance:total_loan);
						var rate_amount = ((rate/100));
						var payment = ((total_loan * rate_amount)*((Math.pow((1+rate_amount),term))/(Math.pow((1+rate_amount),term)-1)));
						var k=0;
						var total_principle = 0;
						var total_payment = 0;
						for(i=1;i<=term;i++){
							if(i== 1){
								interest = (total_loan*(rate/100));
								var dateline = moment(start_date).format('DD/MM/YYYY');
							}else{
								interest = ( balance *(rate/100));
								var dateline = moment(start_date).add(k,'days').format('DD/MM/YYYY');
							}
							principle = payment - interest;
							balance -= principle;
							if(balance <= 0){
								balance = 0;
							}
							tr += '<tr> <td class="text-center">'+ a +'<input type="hidden" name="no[]" id="no" class="no" value="'+ a +'" /></td> ';
							tr += '<td>'+ formatMoney(interest) +'<input type="hidden" name="interest[]" id="interest" class="interest" width="90%" value="'+ formatDecimal(interest) +'"/></td>';
							tr += '<td>'+ formatMoney(principle) +'<input type="hidden" name="principle[]" id="principle" class="principle" width="90%" value="'+ principle +'"/></td>';
							tr += '<td>'+ formatMoney(payment) +'<input type="hidden" name="payment_amt[]" id="payment_amt" class="payment_amt" width="90%" value="'+ formatDecimal(payment) +'"/></td>';								
							tr += '<td>'+ formatMoney(balance) +'<input type="hidden" name="balance[]" id="balance" class="balance" width="90%" value="'+ formatDecimal(balance) +'"/></td>';
							tr += '<td> <input name="note[]" class="note form-control" id="'+a+'" ></input> <input type="hidden" name="note1[]" id="note1" class="note1_'+a+'" width="90%"/></td>';
							tr += '<td>'+ dateline +'<input type="hidden" class="dateline" name="dateline[]" id="dateline" value="'+ dateline +'" /></td> </tr>';
							total_principle += principle;
							total_payment += payment;
							k+= frequency;
							a++;
						}
						tr += '<tr> <td colspan="2"> <?= lang("Total"); ?> </td>';
						tr += '<td>'+ formatMoney(total_principle) +'</td>';
						tr += '<td>'+ formatMoney(total_payment) +'</td>';
						tr += '<td colspan="3"> &nbsp; </td> </tr>';
					} else if(p_type == 4){
						var principle = total_loan/term;
						var interest = (total_loan * (rate/100));
						var balance = (balance?balance:total_loan);
						var payment = 0;
						var k=0;
						var total_principle = 0;
						var total_payment = 0;
						for(i=1;i<=term;i++){
							if(i== 1){
								var dateline = moment(start_date).format('DD/MM/YYYY');
							}else{
								var dateline = moment(start_date).add(k,'days').format('DD/MM/YYYY');
							}
							payment = principle + interest;
							
							balance -= principle;
							if(balance <= 0){
								balance = 0;
							}
							tr += '<tr> <td class="text-center">'+ a +'<input type="hidden" name="no[]" id="no" class="no" value="'+ a+'" /></td> ';
							tr += '<td>'+ formatMoney(interest) +'<input type="hidden" name="interest[]" id="interest" class="interest" width="90%" value="'+ interest +'"/></td>';
							tr += '<td>'+ formatMoney(principle) +'<input type="hidden" name="principle[]" id="principle" class="principle" width="90%" value="'+ principle +'"/></td>';
							tr += '<td>'+ formatMoney(payment) +'<input type="hidden" name="payment_amt[]" id="payment_amt" class="payment_amt" width="90%" value="'+ payment +'"/></td>';
							tr += '<td>'+ formatMoney(balance) +'<input type="hidden" name="balance[]" id="balance" class="balance" width="90%" value="'+ balance +'"/></td>';
							tr += '<td> <input name="note[]" class="note form-control" id="'+a+'" ></input> <input type="hidden" name="note1[]" id="note1" class="note1_'+a+'" width="90%"/></td>';
							tr += '<td>'+ dateline +'<input type="hidden" class="dateline" name="dateline[]" id="dateline" value="'+ dateline +'" /> </td> </tr>';
							total_principle += principle;
							total_payment += payment;
							k+= frequency;
							a++;
						}
						tr += '<tr> <td colspan="2"> <?= lang("total"); ?> </td>';
						tr += '<td>'+ formatMoney(total_principle) +'</td>';
						tr += '<td>'+ formatMoney(total_payment) +'</td>';
						tr += '<td colspan="3"> &nbsp; </td> </tr>';
					}						

					$('.dep_tbl').show();
					$('#tbl_dep').html(tr);
					//$('#tbl_dep1').html(tr);
					$("#loan1").html(tr);
				}
			}
		}
	}
	
	$('#print_depre').click(function () {	
			PopupPayments();
	});
	
	$('#export_depre').click(function () {	
		var customer_id = $('#customer_id').val();
		var customer_name = '';
		var customer_address = '';
		var customer_tel ='';
		var customer_mail = '';
		
		
		$.ajax({
				type: "get",
				url: "<?= site_url('sales/getCustomerInfo'); ?>",
				data: {customer_id: customer_id},
				dataType: "html",
				async: false,
				success: function (data) {
					
					var obj = jQuery.parseJSON(data);
					customer_name = obj.company;
					customer_address = obj.address+', '+obj.city+', '+obj.state;
					customer_tel = obj.phone;
					customer_mail = obj.email;
				}
			});
		var issued_date = $('.current_date').val();
		var myexport  ='<table width="95%" style="line-height:31px"> '+
									'<thead>'+
										 '<tr>'+
											'<th width="5%" class="td_bor_style"><h4 style="font-family:Verdana,Geneva,sans-serif;"><?= lang("loan_amortization_schedule") ?></h4></th>'+
											
										  '</tr>'+
									'</thead>'+
									'<tbody>'+
									'<tr>'+
										'<input type="hidden" name="customer_id" class="customer_id" id="customer_id" value="<?=$inv->customer_id?>">'+
										'<td width="25%" style="font-family:Khmer OS Muol Light; font-size:14px;"> <?= lang('អតិថិជន / Customer');?> </td>'+
										'<td width="50%">: <?= $customer->name ? $customer->name : $customer->company; ?> </td>'+
										'<td width="5px" rowspan="2"> </td>'+
										'<td width="10%" rowspan="2"> <?= lang('លេខវិក្ក័យបត្រ <br/> Invoice No');?> </td>'+
										'<td width="15%" rowspan="2" style="padding-left:0px;">: <?= $inv->reference_no; ?> </td>'+
									'</tr>'+
									'<tr> '+
										'<td width="25%" style="font-family:Khmer OS; font-size:14px;"> <?= lang('ឈ្មោះ​ក្រុមហ៊ុន ឬ អតិថិជន <br/> Company name / Customer');?> </td>'+
										'<td width="50%">: <?= $customer->company ? $customer->company : $customer->name; ?> </td>'+
									'</tr>'+
									'<tr>'+
										'<td width="25%" style="font-family:Khmer OS Muol Light; font-size:14px;"> <?= lang('ទូរស័ព្ទ​លេខ / Telephone No');?> </td>'+
										'<td width="50%">: <?= $customer->phone; ?> </td>'+
										'<td width="5px" rowspan="2"> </td>'+
										'<td width="25%" rowspan="2"> <?= lang('កាលបរិច្ឆេទ <br/> Date');?> </td>'+
										'<td width="50%" rowspan="2" style="padding-left:0px;">: <?= $this->erp->hrld($inv->date); ?> </td>'+
									'</tr>'+
									'<tr>'+
										'<td width="25%" style="font-family:Khmer OS; font-size:14px;"> <?= lang('លេខអត្តសញ្ញាណកម្ម អតប  (VATTIN)');?></td>'+
										'<td width="50%">: <?= $customer->vat_no; ?></td>'+
									'</tr>'+
									'<tbody>'+
								'</table>';
		
			myexport +='<table border="2px" class="table table-bordered table-condensed table_shape" style="width:95%; font-family:Verdana,Geneva,sans-serif; font-size:12px; border-collapse:collapse;">'+
									'<thead>'+
										 '<tr>'+
											'<th width="5%" class="td_bor_style"><?= lang('Nº') ?></th>'+
											'<th width="15%" class="td_bor_style td_align_center"><?= lang('item_code') ?></th>'+
											'<th width="45%" class="td_bor_style"><?= lang('decription') ?></th>'+
											'<th width="10%" class="td_bor_style"><?= lang('unit_price') ?></th>'+
											'<th width="10%" class="td_bor_style"><?= lang('qty') ?></th>'+
											'<th width="15%" class="td_bor_botton"><?= lang('amount') ?></th>'+
										  '</tr>'+
									'</thead>'+
										'<tbody>';
										var type = $('#depreciation_type_1').val();	
										var item_data = jQuery.parseJSON('<?= ($jsrows) ?>');
										var total_amt = 0;
										var down_pay  =  $(".down_payment").val()-0;
										var loan_amount = $(".loan_amount").val()-0;
										var deposit   = ("<?=(isset($inv->deposit)?$inv->deposit:0)?>"); 
										var j =1;
										$.each(item_data, function(i) {
											var subtotal = (formatDecimal(item_data[i].quantity)*item_data[i].unit_price);
											total_amt   += subtotal;
										myexport+=  '<tr>'+
														'<td class="td_color_light td_align_center" >'+ j +'</td>'+
														'<td class="td_color_light">'+ item_data[i].product_code +'</td>'+
														'<td class="td_color_light td_align_center">'+ item_data[i].product_name +'</td>'+
														'<td class="td_color_light td_align_right">$ '+ formatMoney(item_data[i].unit_price) +'</td>'+
														'<td class="td_color_light td_align_center">'+formatDecimal(item_data[i].quantity) +'</td>'+
														'<td class="td_color_bottom_light td_align_right">$ '+ formatMoney(subtotal) +'</td>'+
													'</tr>';  
											j++;
										});
										
									if(down_pay != 0 || down_pay != ''){
			myexport+=  '<tr>'+
							'<td colspan="5" style="text-align:right; padding:5px;"><?= lang('total_amount') ?></td>'+
							'<td class="td_align_right"><b>$ '+ formatMoney(total_amt) +'</b></td>'+
						'</tr>';
			myexport+=  '<tr>'+
							'<td colspan="5" style="text-align:right; padding:5px;"><?= lang('deposit') ?></td>'+
							'<td class="td_align_right"><b>$ '+ formatMoney(deposit) +'</b></td>'+
						'</tr>';
			myexport+= '<tr>'+
							'<td colspan="5" style="text-align:right; padding:5px;"><?= lang('down_payment') ?></td>'+
							'<td class="td_align_right"><b>$ '+ formatMoney(down_pay) +'</b></td>'+
						'</tr>';
									}
			myexport+= '<tr>'+
							'<td colspan="5" style="text-align:right; padding:5px;"><?= lang('loan_amount') ?></td>'+
							'<td class="td_align_right"><b>$ '+ formatMoney(loan_amount) +'</b></td>'+
						'</tr>'+
						'<tr>';
			myexport+='</tbody>'+
									'</table><br/>';	
			myexport+='</tbody>'+
								'</table><br/>';
		myexport+= '<table border="2px" class="table table-bordered table-condensed table_shape" style="width:95%; font-family:Verdana,Geneva,sans-serif; font-size:12px; border-collapse:collapse;">';
		myexport+=			'<tr><td colspan="7" style="height:70px; vertical-align:middle; text-align:center; font-weight:bold; font-size:14px;"><?= lang('payment_term')?></td></tr>';
		myexport+=			'<tr style="height:50px; vertical-align:middle;">'+
								'<th width="10%" class="td_bor_style"><?= lang('pmt_no') ?></th>'+
								'<th width="15%" class="td_bor_style"><?= lang('loan_payment_date') ?></th>';
								if(type == 2){
		myexport+=				'<th width="10%" class="td_bor_style"><?= lang('loan_rate') ?></th>';
		myexport+=				'<th width="10%" class="td_bor_style"><?= lang('loan_percentage') ?></th>';
		myexport+=				'<th width="10%" class="td_bor_style"><?= lang('loan_payment') ?></th>'+
								'<th width="15%" class="td_bor_style"><?= lang('loan_total_payment') ?></th>';			
								}else{
		myexport+=				'<th width="10%" class="td_bor_style"><?= lang('loan_interest') ?></th>'+
								'<th width="10%" class="td_bor_style"><?= lang('loan_principal') ?></th>'+
								'<th width="15%" class="td_bor_style"><?= lang('loan_total_payment') ?></th>';
								}
		myexport+=				'<th width="10%" class="td_bor_style"><?= lang('loan_balance1') ?></th>'+
								'<th width="25%" class="td_bor_botton"><?= lang('loan_note') ?></th>'+
							  '</tr>';
		var k = 0;
		var total_interest = 0;
		var total_princ = 0;
		var amount_total_pay = 0;
		var total_pay_ = 0;
		$('.dep_tbl .no').each(function(){
			k += 1;
			var tr = $(this).parent().parent();
			var balance = formatMoney(tr.find('.balance').val()-0);
		if(type == 2){
			total_interest += formatDecimal(tr.find('.rate').val()-0);
			total_princ += formatDecimal(tr.find('.percentage').val()-0);
			amount_total_pay += formatDecimal(tr.find('.total_payment').val()-0);
		}else{
			total_interest += formatDecimal(tr.find('.interest').val()-0);
			total_princ += formatDecimal(tr.find('.principle').val()-0);
		}
			total_pay_ += formatDecimal(tr.find('.payment_amt').val()-0);
		myexport+=			'<tr>'+
								'<td class="td_color_light td_align_center" align="center">'+ k +'</td>'+
								'<td class="td_color_light td_align_center" align="center">'+ tr.find('.dateline').val() +'</td>';
			if(type == 2){
		myexport+=				'<td class="td_color_light td_align_center" align="right">$ &nbsp;'+ formatMoney(tr.find('.rate').val()-0) +'</td>';
		myexport+=				'<td class="td_color_light td_align_center" align="right">$ &nbsp;'+ formatMoney(tr.find('.percentage').val()-0) +'</td>';
		myexport+=				'<td class="td_color_light td_align_center" align="right">$ &nbsp;'+ formatMoney(tr.find('.payment_amt').val()-0) +'</td>';
		myexport+=				'<td class="td_color_light td_align_center" align="right">$ &nbsp;'+ formatMoney(tr.find('.total_payment').val()-0) +'</td>';
			}else{
		myexport+=				'<td class="td_color_light td_align_center" align="right">$ &nbsp;'+ formatMoney(tr.find('.interest').val()-0) +'</td>';
		myexport+=				'<td class="td_color_light td_align_center" align="right">$ &nbsp;'+ formatMoney(tr.find('.principle').val()-0) +'</td>';
		myexport+=				'<td class="td_color_light td_align_center" align="right">$ &nbsp;'+ formatMoney(tr.find('.payment_amt').val()-0) +'</td>';									
			}
		myexport+=				'<td class="td_color_light td_align_right" align="right">$ &nbsp;'+ balance +'</td>'+
								'<td class="td_color_bottom_light" style="padding-left:20px;">'+ tr.find('.note').val() +'</td>'+
							'</tr>';	
		});		
		if(type == 2){
		myexport+=			'<tr>'+
								'<td style="text-align:right; padding:5px;"><b> Total </b></td>'+
								'<td style="text-align:right; padding:5px;"> &nbsp; </td>'+
								'<td style="text-align:right; padding:5px;"><b>$ &nbsp;'+ formatMoney(total_princ) +'</b></td>'+
								'<td style="text-align:right; padding:5px;"><b>$ &nbsp;'+ formatMoney(total_pay_) +'</b></td>'+
								'<td style="text-align:right; padding:5px;"><b>$ &nbsp;'+ formatMoney(amount_total_pay) +'</b></td>'+
								'<td style="text-align:right; padding:5px;"> &nbsp; </td>'+
								'<td style="text-align:right; padding:5px;"> &nbsp; </td>'+
							'</tr>';								
		}else{
		myexport+=			'<tr>'+
								'<td style="text-align:right; padding:5px; border-top:1px solid black;"><b> Total </b></td>'+
								'<td style="text-align:right; padding:5px; border-top:1px solid black;"> &nbsp; </td>'+
								'<td style="text-align:right; padding:5px; border-top:1px solid black;"><b>$ &nbsp;'+ formatMoney(total_interest) +'</b></td>'+
								'<td style="text-align:right; padding:5px; border-top:1px solid black;"><b>$ &nbsp;'+ formatDecimal(total_princ) +'</b></td>'+
								'<td style="text-align:right; padding:5px; border-top:1px solid black;"><b>$ &nbsp;'+ formatMoney(total_pay_) +'</b></td>'+
								'<td style="text-align:right; padding:5px; border-top:1px solid black;"> &nbsp; </td>'+
								'<td style="text-align:right; padding:5px;"> &nbsp; </td>'+
							'</tr>';
		}
		myexport+= '</tbody>'+'</table><br/>';
		
		$('#export_tbl').append(myexport);
		var htmltable= document.getElementById('export_tbl');
		var html = htmltable.outerHTML;
		window.open('data:application/vnd.ms-excel,' + encodeURIComponent(html));
		return false;
	});
	
	function PopupPayments() {
	
		var customer_id = $('#customer_id').val();
		var customer_name = '';
		var customer_address = '';
		var customer_tel ='';
		var customer_mail = '';
		
		$.ajax({
				type: "get",
				url: "<?= site_url('sales/getCustomerInfo'); ?>",
				data: {customer_id: customer_id},
				dataType: "json",
				async: false,
				success: function (data) {
					
					customer_name = data.name;
					customer_address = data.address+', '+data.city+', '+data.state;
					customer_tel =   data.phone;
					customer_mail = data.email;
					
				}
			});
			
		
		var mywindow = window.open('', 'erp_pos_print', 'height=auto,max-width=480,min-width=250px');
		mywindow.document.write('<html><head><title>Print</title>');
		mywindow.document.write('<link rel="stylesheet" href="<?= $assets ?>styles/helpers/bootstrap.min.css" type="text/css" />');
		mywindow.document.write('</head><body >');
		mywindow.document.write('<center>');
		var issued_date = $('.current_date').val();
		
		mywindow.document.write('<div id="wrap" style="width: 794px; margin: 0 auto;">');
			mywindow.document.write('<div class="row">');
				mywindow.document.write('<div class="col-lg-12 col-xs-12"><br>');
					mywindow.document.write('<p style="margin-right: 20px !important;text-align: right;">ឧបសម្ព័ន្ធ ខ</p>');
					mywindow.document.write('<table class="table table-bordered" style="width: 95%;margin: 0 auto;">');
						mywindow.document.write('<tr>');
							mywindow.document.write('<td colspan="3" rowspan="2" style="border-top-color:white !important;border-left-color:white !important;border-right-color:white !important;">');
							mywindow.document.write('<div class="text-center" style="margin-bottom:20px;">');
							mywindow.document.write('<img src="">');
							mywindow.document.write('</td>');
							mywindow.document.write('<td rowspan="2" style="border-top-color:white !important;border-bottom-color: white !important;"></td>');
							mywindow.document.write('<td><p style="font-family: Moul, cursive;">តម្លៃផ្ទះ</p></td>');
							mywindow.document.write('<td colspan="2"><p><b>: US$ </b></p></td>');
						mywindow.document.write('</tr>');
						mywindow.document.write('<tr>');
							mywindow.document.write('<td><p style="font-family: Moul, cursive;margin-top:8px;">ទឹកប្រាក់កក់</p></td>');
							mywindow.document.write('<td><p style="margin-top:8px;"></p></td>');
							mywindow.document.write('<td><p style="margin-top:8px;"></p></td>');
						mywindow.document.write('</tr>');
						mywindow.document.write('<tr>');
							mywindow.document.write('<td style="width: 20%;">');
								mywindow.document.write('<p style="font-family: Moul, cursive;margin-top:8px;">ឈ្មោះ</p>');
							mywindow.document.write('</td>');
							mywindow.document.write('<td style="width: 25%;" colspan="2">');
								mywindow.document.write('<p style="margin-top:8px;"><b>');
								mywindow.document.write('</b></p>');
							mywindow.document.write('</td>');
							mywindow.document.write('<td style="width: 10%;border-bottom-color:white !important;"></td>');
							mywindow.document.write('<td style="width: 25%;">');
								mywindow.document.write('<p style="font-family: Moul, cursive;margin-top:8px;">រយះពេល</p>');
							mywindow.document.write('</td>');
							mywindow.document.write('<td style="width: 8%;">');
								mywindow.document.write('<p style="margin-top:8px;">: </p>');
							mywindow.document.write('</td>');
							mywindow.document.write('<td style="width: 12%;">');
								mywindow.document.write('<p style="margin-top:8px;"><span>');
								mywindow.document.write(' </span></p>');
							mywindow.document.write('</td>');
						mywindow.document.write('</tr>');
						mywindow.document.write('</table>');
					mywindow.document.write('</div>');
				mywindow.document.write('</div>');
			mywindow.document.write('</div>');
		mywindow.document.write('</body></html>');
		mywindow.print();
		//mywindow.close();
		return true;
	}
	
	
	$(document).on('keyup', '#tbl_dep .percentage', function () {
			var rate_all = $('#depreciation_rate_1').val()-0;
			var amount = 0;
			var payment = 0;
			var amount_payment = 0;
			var rate = 0;
			var balance = 0;
			var per = $(this).val()-0;
			var tr = $(this).parent().parent();
			if(per < 0 || per > 100) {
				alert("sorry you can not input the rate value less than zerro or bigger than 100");
				$(this).val('');
				$(this).focus();
				return false;
			}else {
				amount = tr.find('.balance').val()-0;
				rate = tr.find('.interest_').val()-0;
				payment = amount *(per/100);
				amount_payment = rate + payment;
				balance = amount - payment;
				tr.find('.pmt_principle').val(formatDecimal(payment));
				tr.find('.principle').val(formatDecimal(payment));
				tr.find('.total_payment').val(formatDecimal(amount_payment));
				tr.find('.payment_amt').val(formatDecimal(amount_payment));
				tr.find('.amt_balance').val(formatDecimal(balance));
				tr.find('.balance').val(formatDecimal(balance));
				
				var total_percent = 0;
				$('#tbl_dep .percentage').each(function(){
					var parent_ = $(this).parent().parent();
					var per_tage_ = parent_.find('.percentage').val()-0;
					total_percent += per_tage_;
				});
				
				var j = 1;
				var i = 1;
				var balance = 0;
				var amount_percent = 0;
				var amount_pay = 0;
				var amount_total_payment = 0;
				$('#tbl_dep .percentage').each(function(){
					var parent = $(this).parent().parent();
					var per_tage = parent.find('.percentage').val()-0;
					if(per_tage == '' || per_tage == 0) {
						per_tage = 0;
					}
					amount_percent += per_tage;
					var rate = parent.find('.rate').val()-0;
					
					if(j == 1) {
						var total_amount = $('#loan_amount').val()-0;
						balance = total_amount;
					}else {
						balance = parent.prev().find('.balance').val()-0;
					}
					
					var new_rate = balance * (rate_all/100);
					var payment = balance * (per_tage/100);
					amount_pay += payment;
					var total_payment = payment + new_rate;
					amount_total_payment += total_payment;
					var balance = balance - payment;
					
					if(total_percent != amount_percent) {
						parent.find('.rate').val(formatDecimal(new_rate));
						parent.find('.interest').val(formatDecimal(new_rate));
						parent.find('.pmt_principle').val(formatDecimal(payment));
						parent.find('.principle').val(formatDecimal(payment));
						parent.find('.total_payment').val(formatDecimal(total_payment));
						parent.find('.payment_amt').val(formatDecimal(total_payment));
						parent.find('.amt_balance').val(formatDecimal(balance));
						parent.find('.balance').val(formatDecimal(balance));
					}else{
						if(i == 1) {
							parent.find('.rate').val(formatDecimal(new_rate));
							parent.find('.interest').val(formatDecimal(new_rate));
							parent.find('.pmt_principle').val(formatDecimal(payment));
							parent.find('.principle').val(formatDecimal(payment));
							parent.find('.total_payment').val(formatDecimal(total_payment));
							parent.find('.payment_amt').val(formatDecimal(total_payment));
							parent.find('.amt_balance').val(formatDecimal(balance));
							parent.find('.balance').val(formatDecimal(balance));
						}else {
							parent.find('.rate').val(formatDecimal(new_rate));
							parent.find('.interest').val(formatDecimal(new_rate));
							parent.find('.pmt_principle').val("");
							parent.find('.principle').val(formatDecimal(payment));
							parent.find('.total_payment').val("");
							parent.find('.payment_amt').val(formatDecimal(total_payment));
							parent.find('.amt_balance').val("");
							parent.find('.balance').val(formatDecimal(balance));
						}
						i++;
					}
					j++;
				});
				$('.total_percen').val(formatDecimal(amount_percent));
				$('.total_pay').val(formatDecimal(amount_pay));
				$('.total_amount').val(formatDecimal(amount_total_payment));
			}
		});
		
		
		$(document).on('keyup','#tbl_dep  .pmt_principle', function () {
			var rate_all = $('#depreciation_rate_1').val()-0;
			var amount = 0;
			var percent = 0;
			var amount_payment = 0;
			var rate = 0;
			var balance = 0;
			var payment = $(this).val()-0;
			var tr = $(this).parent().parent();
			if(payment < 0 ) {
				alert("sorry you can not input the rate value less than zerro or bigger than 100");
				$(this).val('');
				$(this).focus();
				return false;
			}else {
				amount = tr.find('.balance').val();
				rate = tr.find('.interest').val()-0;
				percent = (payment / amount) * 100;
				amount_payment = rate + payment;
				balance = amount - payment;
				tr.find('.percentage').val(formatDecimal(percent));
				tr.find('.percentage_').val(percent);
				tr.find('.total_payment').val(formatDecimal(amount_payment));
				tr.find('.payment_amt').val(amount_payment);
				tr.find('.amt_balance').val(formatDecimal(balance));
				tr.find('.balance').val(balance);
				
				var total_pay = 0;
				$('#tbl_dep .pmt_principle').each(function(){
					var parent = $(this).parent().parent();
					var pay_amt_ = parent.find('.pmt_principle').val()-0;
					total_pay += pay_amt_;
				});
				
				var j = 1;
				var i = 1;
				var balance = 0;
				var amount_pay = 0;
				var total_per = 0;
				var amount_total_payment  = 0;
				$('#tbl_dep .pmt_principle').each(function(){
					var parent = $(this).parent().parent();
					var pay_amt = parent.find('.pmt_principle').val()-0;
					if(pay_amt == '' || pay_amt < 0) {
						pay_amt = 0;
					}
					amount_pay += pay_amt;
					var rate = parent.find('.rate').val()-0;
					
					if(j == 1) {
						var total_amount = $('#loan_amount').val()-0;
						balance = total_amount;
					}else {
						balance = parent.prev().find('.balance').val()-0;
					}
					if(rate!=0)
					{
						var new_rate = balance * (rate_all/100);
					}else{
						var new_rate = 0;
					}
					
					var percen = (pay_amt / balance) * 100;
					total_per += percen;
					
					var total_payment = pay_amt + new_rate;

				
					
					amount_total_payment += total_payment;
					var balance = balance - pay_amt;
					
				
					if(total_pay != amount_pay) {
						parent.find('.rate').val(formatDecimal(new_rate));
						parent.find('.interest').val(formatDecimal(new_rate));
						parent.find('.pmt_principle').val(formatDecimal(payment));
						parent.find('.principle').val(formatDecimal(payment));
						parent.find('.total_payment').val(formatDecimal(total_payment));
						parent.find('.payment_amt').val(formatDecimal(total_payment));
						parent.find('.amt_balance').val(formatDecimal(balance));
						parent.find('.balance').val(formatDecimal(balance));
						
					}else{
						if(i == 1) {
							parent.find('.rate').val(formatDecimal(new_rate));
							parent.find('.interest').val(formatDecimal(new_rate));
							parent.find('.pmt_principle').val(formatDecimal(payment));
							parent.find('.principle').val(formatDecimal(payment));
							parent.find('.total_payment').val(formatDecimal(total_payment));
							parent.find('.payment_amt').val(formatDecimal(total_payment));
							parent.find('.amt_balance').val(formatDecimal(balance));
							parent.find('.balance').val(formatDecimal(balance));
							
						}else {
							parent.find('.rate').val(formatDecimal(new_rate));
							parent.find('.interest').val(formatDecimal(new_rate));
							parent.find('.pmt_principle').val("");
							parent.find('.principle').val(formatDecimal(payment));
							parent.find('.total_payment').val("");
							parent.find('.payment_amt').val(formatDecimal(total_payment));
							parent.find('.amt_balance').val("");
							parent.find('.balance').val(formatDecimal(balance));
						}
						i++;
					}
					j++;
				});
				$('.total_percen').val(formatDecimal(total_per));
				$('.total_pay').val(formatDecimal(amount_pay));
				$('.total_amount').val(formatDecimal(amount_total_payment));
			}
		});
	
		$(document).on('keyup','#tbl_dep  .rate', function () {
			var rate_all = $('#depreciation_rate_1').val()-0;
			var amount = 0;
			var percent = 0;
			var amount_payment = 0;
			var rate = 0;
			var balance = 0;
			var payment = $(this).val()-0;
			var tr = $(this).parent().parent();
			if(payment < 0 ) {
				alert("sorry you can not input the rate value less than zerro or bigger than 100");
				$(this).val('');
				$(this).focus();
				return false;
			}else {
				amount = tr.find('.balance').val();
				rate = tr.find('.interest').val()-0;
				percent = (payment / amount) * 100;
				amount_payment = rate + payment;
				balance = amount - payment;
				tr.find('.total_payment').val(formatDecimal(amount_payment));
				tr.find('.payment_amt').val(amount_payment);
				tr.find('.amt_balance').val(formatDecimal(balance));
				tr.find('.balance').val(balance);
			}
		});
		
		/* ######End Funtion###### */
});		
</script>