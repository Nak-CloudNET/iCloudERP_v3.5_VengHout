<script type="text/javascript">
$(document).ready(function(){
	
	$('body').on('click', '#excel', function(e) {
	   e.preventDefault();
	   var k = false;
	   $.each($("input[name='val[]']:checked"), function(){
	    k = true;

	   });
	   $('#form_action').val($('#excel').attr('data-action'));
	   $('#action-form-submit').trigger('click');
  	});
  	$('body').on('click', '#pdf', function(e) {
	   e.preventDefault();
	   var k = false;
	   $.each($("input[name='val[]']:checked"), function(){
	    
	    k = true;
	   });
	   $('#form_action').val($('#pdf').attr('data-action'));
	   $('#action-form-submit').trigger('click');
  	});
	$('body').on('click', '.p', function(e) {
		
		
	});
});
</script>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-barcode"></i><?= lang('daily_products') ; ?>
        </h2>
		<div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown">
                    <a href="javascript:void(0);" class="toggle_up tip" title="<?= lang('hide_form') ?>">
                        <i class="icon fa fa-toggle-up"></i>
                    </a>
                </li>
                <li class="dropdown">
                    <a href="javascript:void(0);" class="toggle_down tip" title="<?= lang('show_form') ?>">
                        <i class="icon fa fa-toggle-down"></i>
                    </a>
                </li>
				<li class="dropdown">
					<a href="#" id="pdf" data-action="export_pdf"  class="tip" title="<?= lang('download_pdf') ?>">
						<i class="icon fa fa-file-pdf-o"></i>
					</a>
				</li>
                <li class="dropdown">
					<a href="#" id="excel" data-action="export_excel"  class="tip" title="<?= lang('download_xls') ?>">
						<i class="icon fa fa-file-excel-o"></i>
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
				<?php echo form_open('reports/product_dailyinout', 'id="action-form" method="GET"'); ?>
					<div class="row">
                       <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="pro"><?= lang("products"); ?></label>
                                <?php
								$pro[""] = "ALL";
                                foreach ($products as $product) {
                                    $pro[$product->id] = $product->code.' / '.$product->name;
                                }
                                echo form_dropdown('product', $pro, (isset($_GET['product']) ? $_GET['product'] : ''), 'class="form-control" id="product" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("producte") . '"');
                                ?>
								
                            </div>
                        </div>
                        
						<div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("category", "category") ?>
                                <?php
                                $cat[''] = "ALL";
                                foreach ($categories as $category) {
                                    $cat[$category->id] = $category->name;
                                }
                                echo form_dropdown('category', $cat, (isset($_GET['category']) ? $_GET['category'] : ''), 'class="form-control select" id="category" placeholder="' . lang("select") . " " . lang("category") . '" style="width:100%"')
                                ?>

                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("warehouse", "warehouse") ?>
                                <?php
                                $waee[''] = "ALL";
                                foreach ($warefull as $wa) {
                                    $waee[$wa->id] = $wa->code.' / '.$wa->name;
                                }
                                echo form_dropdown('warehouse', $waee, (isset($_GET['warehouse']) ? $_GET['warehouse'] : ''), 'class="form-control select" id="warehouse" placeholder="' . lang("select") . " " . lang("warehouse") . '" style="width:100%"')
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
                                echo form_dropdown('in_out', $in_out, (isset($_GET['in_out']) ? $_GET['in_out'] :''), 'class="form-control select" id="in_out" placeholder="' . lang("select") . " " . lang("in_out") . '" style="width:100%"')
                                ?>
                            </div>
                        </div>
						<div class="col-sm-4">
							<div class="form-group">
								<?= lang("month", "month"); ?>
								<?php 
								$months = array(
											'01' => 'January',
											'02' => 'February',
											'03' => 'March',
											'04' => 'April',
											'05' => 'May',
											'06' => 'June',
											'07' => 'July',
											'08' => 'August',
											'09' => 'September',
											'10' => 'October',
											'11' => 'November',
											'12' => 'December',
										);
								echo form_dropdown('month', $months, (isset($_GET['month'])? $_GET['month'] : $month2), 'id="month" class="form-control"') ?>
								
							</div>
						</div>
						
					<div class="col-sm-4">
							<div class="form-group">
								<?= lang("year", "year"); ?>
								<?php echo form_input('year', (isset($_GET['year']) ? $_GET['year'] : $year2), 'class="form-control date-year" id="year"'); ?>
							</div>
						</div>
						<!--<div class="col-sm-4">
							<div class="form-group">
							<?= lang("day", "day"); ?>
								<?php
								$da = array(""=>"select a day");
								$str = "";
									for($i=1;$i<=$days;$i++){
										if(strlen($i) == 1){
											$str = "0$i";
										}else{
											$str = "$i";
										}
										$da[$str] = $str;
									}
									echo form_dropdown('dayy', $da, ($_GET['dayy']? $_GET['dayy'] :$dayy2), 'id="dayy" class="form-control" placeholder="select a day"') ;
									
								?>
								
								
							</div>
						</div>-->
						</div>
					<div class="form-group">
                        <div
                            class="controls"> <?php echo form_submit('submit_report', $this->lang->line("submit"), 'class="btn btn-primary sub"'); ?> </div>
                    </div>
                    <?php echo form_close(); ?>
					
                </div>
                <div class="clearfix"></div>

                <div class="table-responsive" style="width:100%;overflow:auto;overflow-y: hidden;">
                    <table id="tbstock" class="table table-condensed table-bordered table-hover table-striped" >
                        <thead>
							<tr>
                                <th><?= lang("image") ?></th>
								<th><?= lang("product_code") ?></th>
								<th><?= lang("product_name") ?></th>

								<?php
									for($i=1;$i<=$days;$i++){
										if(strlen($i) == 1){
											echo "<th style='padding-right: 30px;padding-left: 30px;' id='0$i'>0$i</th>";
										}else{
											echo "<th style='padding-right: 30px;padding-left: 30px;' id='$i'>$i</th>";
										}
									}
								?>								
							</tr>                        
						</thead>
                        <tbody>
							<?php
								$total = array();
								foreach($stocks as $row){
							?>		
							<tr class="rows">
                                <!--<td class="name"><img src="<?= base_url()?>/assets/uploads/thumbs/<?=$row->image ?>"  class="img-responsive"/></td> -->
                                <td><ul class="enlarge">
                                        <li>
                                            <img src="<?= base_url()?>/assets/uploads/thumbs/<?=$row->image ?>"  class="img-responsive"/>
                                            <span>
                                                <a href="<?= base_url()?>/assets/uploads/thumbs/<?=$row->image ?>" data-toggle="lightbox">
                                                    <img src="<?= base_url()?>/assets/uploads/thumbs/<?=$row->image ?>"  style="width:200px;z-index: 9999999999999;" class="img-thumbnail"/>
                                                </a>
                                            </span>
                                        </li>
                                    </ul>
                                </td>
								<td class="code"><?=$row->code?$row->code:'ID:'.$row->product_id?></td>
								<td class="name"><?=$row->name?><?=" (".$row->name_unit.")"?></td>
								<?php
								
								$am = 0;
								
									for($i=1;$i<=$days;$i++){
										
										$this->db->select("SUM(COALESCE((-1)*quantity_balance,0)) AS outt")
										->join("products","products.id=purchase_items.product_id","LEFT")
										->join("erp_purchases","erp_purchases.id=purchase_items.purchase_id","LEFT")
										->where("DATE_FORMAT(erp_purchase_items.date, '%Y-%m')=",$row->dater)
										->where("purchase_items.product_id",$row->product_id)
										->where("quantity_balance<",0);
										if($biller2){
											$this->db->where("erp_purchases.biller_id",$biller2);
										}
										if($warehouse2){
											$this->db->where("erp_purchase_items.warehouse_id",$warehouse2);
										}else{		
											if($wid2){
												$this->db->where("erp_purchase_items.warehouse_id IN ($wid2)");
											}
										}
										$this->db->where("DATE_FORMAT(erp_purchase_items.date, '%d')=",$i);
										$q = $this->db->get("purchase_items")->row();
										
										$this->db->select("SUM(COALESCE(quantity_balance,0)) AS inn")
										->join("products","products.id=purchase_items.product_id","LEFT")
										->join("erp_purchases","erp_purchases.id=purchase_items.purchase_id","LEFT")
										->where("DATE_FORMAT(erp_purchase_items.date, '%Y-%m')=",$row->dater)
										->where("purchase_items.product_id",$row->product_id)
										->where("quantity_balance>",0)
										->where("DATE_FORMAT(erp_purchase_items.date, '%d')=",$i);
										if($warehouse2){
											$this->db->where("erp_purchase_items.warehouse_id",$warehouse2);
										}else{		
											if($wid2){
												$this->db->where("erp_purchase_items.warehouse_id IN ($wid2)");
											}
										}
										if($biller2){
											$this->db->where("erp_purchases.biller_id",$biller2);
										}
										$q2 = $this->db->get("purchase_items")->row();
										/////////////////////
										$this->db->select("SUM(COALESCE((-1)*quantity_balance,0)) AS outt")
										->join("products","products.id=purchase_items.product_id","LEFT")
										->join("erp_purchases","erp_purchases.id=purchase_items.purchase_id","LEFT")
										->where("DATE_FORMAT(erp_purchase_items.date, '%Y-%m')=",$row->dater)
										->where("purchase_items.product_id",$row->product_id)
										->where("quantity_balance<",0);
										if($warehouse2){
											$this->db->where("erp_purchase_items.warehouse_id",$warehouse2);
										}else{		
											if($wid2){
												$this->db->where("erp_purchase_items.warehouse_id IN ($wid2)");
											}
										}
										if($biller2){
											$this->db->where("erp_purchases.biller_id",$biller2);
										}
										$this->db->where("DATE_FORMAT(erp_purchase_items.date, '%d')=",$i);
										$q_unit = $this->db->get("purchase_items")->row();
										//////////////////////
										$this->db->select("SUM(COALESCE(quantity_balance,0)) AS inn")
										->join("products","products.id=purchase_items.product_id","LEFT")
										->join("erp_purchases","erp_purchases.id=purchase_items.purchase_id","LEFT")
										->where("DATE_FORMAT(erp_purchase_items.date, '%Y-%m')=",$row->dater)
										->where("purchase_items.product_id",$row->product_id)
										->where("quantity_balance>",0);
										if($warehouse2){
											$this->db->where("erp_purchase_items.warehouse_id",$warehouse2);
										}else{		
											if($wid2){
												$this->db->where("erp_purchase_items.warehouse_id IN ($wid2)");
											}
										}
										if($biller2){
											$this->db->where("erp_purchases.biller_id",$biller2);
										}
										$this->db->where("DATE_FORMAT(erp_purchase_items.date, '%d')=",$i);
										$q_unit2 = $this->db->get("purchase_items")->row();
										/////////////////////////
										$this->db->select("SUM(COALESCE(quantity_balance,0)) AS inou")
										->join("products","products.id=purchase_items.product_id","LEFT")
										->where("DATE_FORMAT(erp_purchase_items.date, '%Y-%m')=",$row->dater)
										->where("purchase_items.product_id",$row->product_id);
										if($warehouse2){
											$this->db->where("erp_purchase_items.warehouse_id",$warehouse2);
										}else{		
											if($wid2){
												$this->db->where("erp_purchase_items.warehouse_id IN ($wid2)");
											}
										}
										$this->db->where("DATE_FORMAT(erp_purchase_items.date, '%d')=",$i);
										$q_unit3 = $this->db->get("purchase_items")->row();
										
								?>
									<?php 
									
									$am = $q2->inn - $q->outt;
									if($in_out2 == "all"){	
										if($q2->inn || $q->outt){?>
											<td style='text-align:right; '>
											<?php if($am){?>
											<span style="color:blue;"><?=$this->erp->formatDecimal($am)?></span>
											<br>
											<?php
													 
													if($q_unit3->inou){
															echo   $this->erp->convert_unit_2_string($row->product_id,$q_unit3->inou);
													}
													$total[$i] += $am;
											}
											
													?>
											</td>
											<?php
											
										}else{
											echo "<td></td>";
										}
									}else if($in_out2 == "in"){
										if($q2->inn){?>
											<td style='text-align:right; '>
											<?php  if($q2->inn){?>
											<span style="color:blue;"><?=$this->erp->formatDecimal($q2->inn)?></span><br>
											<?php
													if($q_unit2->inn){
														echo   $this->erp->convert_unit_2_string($row->product_id,$q_unit2->inn);
													}
													
													$total[$i] += $q2->inn;
											}
													?>
											
											</td>
											<?php
											
										}else{
											echo "<td></td>";
										}
									}else{
										if($q->outt){?>
										<td style='text-align:right;'>
										<?php  if($q->outt){?>
										<span style="color:blue;"><?=$this->erp->formatDecimal($q->outt)?></span><br>
										 <?php
													
														 if($q_unit->outt){
															echo   $this->erp->convert_unit_2_string($row->product_id,$q_unit->outt);
														}
													$total[$i] += $q->outt;
														
										}
													?>
										</td>
										<?php
											
										}else{
											echo "<td></td>";
										}
									}
									?>
								<?php } ?>
							</tr>
							<?php
									
								}
							
							?>
							<tr>
								<td colspan="3" style="text-align:right; background-color: #428BCA;color:white;border-color: #357EBD;"><b>Total:</b></td>
								<?php 
								for($i=1;$i<=$days;$i++){
								?>
									<td style='text-align:right; background-color: #428BCA;color:white;border-color: #357EBD;'>
									<?php if(isset($total[$i])){ ?>
									<b>
									<?=$this->erp->formatDecimal($total[$i])?>
									</b>
									<?php } ?>
									</td>
								<?php
								}
								?>
							</tr>
							
                        </tbody>                       
                    </table>
                </div>
				<div class=" text-right">
					<div class="dataTables_paginate paging_bootstrap">
						<?= $pagination; ?>
					</div>
				</div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
 
$(document).ready(function () {
	
	$(document).on('focus','.date-year', function(t) {
			$(this).datetimepicker({
				format: "yyyy",
				startView: 'decade',
				minView: 'decade',
				viewSelect: 'decade',
				autoclose: true,
			});
	});
    $('#form').hide();
    $('.toggle_down').click(function () {
        $("#form").slideDown();
        return false;
    });
    $('.toggle_up').click(function () {
        $("#form").slideUp();
        return false;
    });
	 $('#excel').on('click', function (e) {
		e.preventDefault();
		if ($('.checkbox:checked').length <= 0) {
			window.location.href = "<?= site_url('reports/productDailyInOutReport/0/xls/'.$product1.'/'.$month2.'/'.$year2.'/'.$wid1.'/'.$warehouse1.'/'.$category1.'/'.$in_out2.'/'.$biller1) ?>";
			return false;
		}
	});
	$('#pdf').on('click', function (e) {
		e.preventDefault();
		if ($('.checkbox:checked').length <= 0) {
			window.location.href = "<?= site_url('reports/productDailyInOutReport/pdf/0/'.$product1.'/'.$month2.'/'.$year2.'/'.$wid1.'/'.$warehouse1.'/'.$category1.'/'.$in_out2.'/'.$biller1) ?>";
			return false;
		}
	});
});
</script>