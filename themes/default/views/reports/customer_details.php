<style type="text/css" media="all">
	#PRData{ 
		white-space:nowrap; 
		width:100%; 
	}
    #PRData td:nth-child(6), #PRData td:nth-child(7) {
        text-align: right;
    }
 
</style>

<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-barcode"></i><?= lang('product_customers') ; ?>
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
				<?php echo form_open('reports/customer_details', 'id="action-form"'); ?>
					<div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="reference_no"><?= lang("reference_no"); ?></label>
                                <?php echo form_input('reference_no', (isset($_POST['reference_no']) ? $_POST['reference_no'] : ""), 'class="form-control tip" id="reference_no"'); ?>

                            </div>
                        </div>

                       
						<div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="cat"><?= lang("products"); ?></label>
                                <?php                          
								$pro[""] = "ALL";
                                foreach ($products as $product) {
                                    $pro[$product->id] = $product->code.' / '.$product->name;
                                }
                                echo form_dropdown('product', $pro, (isset($_POST['product']) ? $_POST['product'] : ""), 'class="form-control" id="product" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("producte") . '"');
                                ?>
                            </div>
                        </div>
						<!--
                        <?php if(isset($biller_idd)){?>
						<div class="col-sm-4">
						 <div class="form-group">
                                    <?= lang("biller", "biller"); ?>
                                    <?php 
									$str = "";
									$q = $this->db->get_where("companies",array("id"=>$biller_idd),1);
									 if ($q->num_rows() > 0) {
										 $str = $q->row()->name.' / '.$q->row()->company;
										echo form_input('biller',$str , 'class="form-control" id="biller"');
									 }
									?>
                                </div>
						 </div>
						<?php } ?>
						-->
                        <div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("warehouse", "warehouse") ?>
                                <?php
                                $waee[''] = "ALL";
                                foreach ($warefull as $wa) {
                                    $waee[$wa->id] = $wa->code.' / '.$wa->name;
                                }
                                echo form_dropdown('warehouse', $waee, (isset($_GET['warehouse']) ? $_GET['warehouse'] : $warehouse), 'class="form-control select" id="warehouse" placeholder="' . lang("select") . " " . lang("warehouse") . '" style="width:100%"')
                                ?>

                            </div>
                        </div>
						<div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("category", "category") ?>
                                <?php
                                $cat[0] = $this->lang->line("all");
                                foreach ($categories as $category) {
                                    $cat[$category->id] = $category->name;
                                }
                                echo form_dropdown('category', $cat, (isset($_POST['category']) ? $_POST['category'] : ''), 'class="form-control select" id="category" placeholder="' . lang("select") . " " . lang("category") . '" style="width:100%"')
                                ?>
                            </div>
                        </div>
						
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="warehouse"><?= lang("customer"); ?></label>
                                <?php
								$cu = array(""=>"ALL");
								$cupp = $this->db->select("customer_id,customer")->group_by("customer_id")->get("erp_sales")->result();
                                foreach ($cupp as $cup) {
                                    $cu[$cup->customer_id] = $cup->customer;
                                }
                                echo form_dropdown('customer', $cu, (isset($_POST['customer']) ? $_POST['customer'] : ""), 'class="form-control" id="customer" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("customer") . '"');
                                ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("from_date", "from_date"); ?>
                                <?php echo form_input('from_date', (isset($_POST['from_date']) ? $_POST['from_date'] : $this->erp->hrsd($from_date)), 'class="form-control date" id="from_date"'); ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("to_date", "to_date"); ?>
                                <?php echo form_input('to_date', (isset($_POST['to_date']) ? $_POST['to_date'] : $this->erp->hrsd($to_date)), 'class="form-control date" id="to_date"'); ?>
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
                    <table id="PRData" class="table table-bordered table-hover table-striped table-condensed">
                        <thead>
                        <tr class="primary">
                            <th class="" style="width: 100px"><?= lang("image") ?></th>
							<th class=""><?= lang("type") ?></th>
							<th class=""><?= lang("date") ?></th>
							<th class=""><?= lang("reference") ?></th>
							<th class=""><?= lang("product_name") ?></th>
							<th class=""><?= lang("categories") ?></th>
							<th class=""><?= lang("qty") ?></th>
							<th class=""><?= lang("unit") ?></th>
							<th class=""><?= lang("unit_price") ?></th>
							<th class=""><?= lang("amount") ?></th>
							
                        </tr>
                        </thead>
                        <tbody>
							<?php
							$grand = 0 ;
							$gqty = 0;
							$wid = $this->reports_model->getWareByUserID();
							$this->db->select("customer_id,customer,SUM(erp_sale_items.quantity) as qty")
							->join("erp_sale_items","erp_sale_items.sale_id=erp_sales.id","LEFT");
							if($customer){
								$this->db->where("erp_sales.customer_id",$customer);
							}
							if($reference){
								$this->db->where("reference_no",$reference);
							}
							if($product_id){
								$this->db->where("product_id",$product_id);
							}
							if($from_date && $to_date){
								$this->db->where('erp_sales.date >="'.$from_date.' 00.00" AND erp_sales.date<="'.$to_date.' 23.59"');
							}
							if($warehouse){
								$this->db->where("erp_sales.warehouse_id",$warehouse);
							}else{
								if($wid){
									$this->db->where("erp_sales.warehouse_id IN ($wid)");
								}
							}
							if($category_id){
								$this->db->join('erp_products', 'erp_products.id = erp_sale_items.product_id', 'left');
								$this->db->where("erp_products.category_id", $category_id);
							}
							$this->db->group_by("customer_id");
							$customers = $this->db->get("erp_sales")->result();
							if(is_array($customers)){
							foreach($customers as $row){
								if($row->customer_id){
									if($row->qty){
								?>
							<tr>
                                <td colspan="10" style="background:#F0F8FF;"><b><?= $row->customer ?></b></td>
							</tr>
								<?php
                                        $this->db->select("product_id,product_name,erp_sale_items.quantity,net_unit_price,customer_id,reference_no,erp_sales.date,'SALE' as transaction_type,unit,option_id, erp_products.image")->join("erp_sales", "erp_sales.id = erp_sale_items.sale_id", "LEFT")->join("erp_products", "erp_products.id = erp_sale_items.product_id", "LEFT");
                                        //$this->db->join('erp_products', 'erp_products.id = erp_sale_items.product_id', 'left');
									if($reference){
										$this->db->where("reference_no",$reference);
									}
									if($customer){
										$this->db->where("customer_id",$customer);
									}
									if($product_id){
										$this->db->where("product_id",$product_id);
									}
									if($from_date && $to_date){
										$this->db->where('erp_sales.date >="'.$from_date.' 00.00" AND erp_sales.date<="'.$to_date.' 23.59"');
										//$this->db->where("erp_sales.date BETWEEN '$from_date' AND '$to_date'");
									}
									if($warehouse){
										$this->db->where("erp_sales.warehouse_id",$warehouse);
									}else{
										if($wid){
											$this->db->where("erp_sales.warehouse_id IN ($wid)");
										}
									}
									$this->db->select('erp_sale_items.*, erp_categories.name as cate_name');
									$this->db->join('erp_categories', 'erp_categories.id = erp_products.category_id', 'left');									
									if($category_id){										
										$this->db->where('erp_products.category_id', $category_id);
									}
									$sale_items = $this->db->get("erp_sale_items")->result();
									$tqty = 0 ; 
									$amount = 0 ;
									$vqty = 0;
									$unit_name = "";
									if(is_array($sale_items)){
									foreach($sale_items as $row1){
										if($row->customer_id == $row1->customer_id){
											if($row1->option_id){
												$unit_n = $this->db->get_where('erp_product_variants',array('id'=> $row1->option_id),1)->row();
												$unit_q = $unit_n->qty_unit;
												//$unit_name = ' ( '.$this->erp->formatQuantity(( abs($row1->quantity)*$unit_q)/$unit_q).' '.$unit_n->name.' )';
												
												$vqty = abs($row1->quantity)*$unit_q;				
												$unit_name = $this->erp->convert_unit_2_string($row1->product_id,$vqty);												
											}else{
												$unit = $this->reports_model->getUn($row1->unit);
												if($unit){
													$unit_name = $unit->name;
												}
												$vqty =  abs($row1->quantity);
											}
								?>
									<tr>
                                        <td style="text-align:center !important;">
                                            <ul class="enlarge">
                                                <li>
                                                    <img src="<?= base_url() ?>/assets/uploads/thumbs/<?= $row1->image ?>"
                                                         class="img-responsive" style="width:50px;"/>
                                                    <span>
                                                      <a href="<?= base_url() ?>/assets/uploads/thumbs/<?= $row1->image ?>"
                                                         data-toggle="lightbox">
                                                        <img src="<?= base_url() ?>/assets/uploads/thumbs/<?= $row1->image ?>"
                                                             style="width:150px; z-index: 9999999999999;"
                                                             class="img-thumbnail"/>
                                                      </a>
                                                    </span>
                                                </li>
                                            </ul>
                                        </td>
										<td class="text-center"><?=$row1->transaction_type?></td>
										<td><?=$this->erp->hrsd($row1->date)?></td>
										<td><?=$row1->reference_no?></td>
										<td><?=$row1->product_name?></td>
										<td style="text-align:center;"><?=$row1->cate_name?></td>
										<td class="text-right"><?=$this->erp->formatQuantity($vqty)?></td>
										<td ><?=$unit_name?></td>
										<td class="text-right"><?=$this->erp->formatMoney(abs($row1->net_unit_price))?></td>
										<td class="text-right"><?=$this->erp->formatMoney(abs($row1->quantity)*abs($row1->net_unit_price))?></td>
									</tr>
								
								<?php
									$tqty+=$vqty;
									$amount+=(abs($row1->quantity)*abs($row1->net_unit_price));
										}
									}
									}
								?>
							<tr style="background:#F0F8FF;">
								<td ><b>Total >> <?=$row->customer?></b></td>
								<td ></td>
                                <td></td>
								<td ></td>
								<td ></td>
								<td ></td>
								<td class="text-right"><b><?=$this->erp->formatQuantity($tqty)?></b></td>
								<td ></td>
								<td ></td>
								<td class="text-right"><b><?=$this->erp->formatMoney($amount)?></b></td>
								
							</tr>
							<?php
							$grand +=$amount;
							$gqty+=$tqty;
								}
								}
							}
							}
							?>
							<tr>
								<td style="background:#4682B4;color:white;"><b>Grand Total</b></td>
								<td style="background:#4682B4;color:white;"></td>
								<td style="background:#4682B4;color:white;"></td>
                                <td style="background:#4682B4;color:white;"></td>
								<td style="background:#4682B4;color:white;"></td>
								<td style="background:#4682B4;color:white;"></td>
								<td style="background:#4682B4;color:white;" class="text-right"><b><?=$this->erp->formatQuantity($gqty)?></b></td>
								<td style="background:#4682B4;color:white;"></td>
								<td style="background:#4682B4;color:white;"></td>
								<td style="background:#4682B4;color:white;" class="text-right"><b><?=$this->erp->formatMoney($grand)?></b></td>
								
							</tr>
                        </tbody>
                       
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('#form').hide();
    $('.toggle_down').click(function () {
        $("#form").slideDown();
        return false;
    });
    $('.toggle_up').click(function () {
        $("#form").slideUp();
        return false;
    });
	$(document).ready(function(){
		$('.date').datetimepicker({
			format: site.dateFormats.js_sdate, 
			fontAwesome: true, 
			language: 'erp', 
			todayBtn: 1, 
			autoclose: 1, 
			minView: 2 
		});
		
		$(document).on('focus','.date', function(t) {
			$(this).datetimepicker({format: site.dateFormats.js_sdate, fontAwesome: true, todayBtn: 1, autoclose: 1, minView: 2 });
		});
	
		$('body').on('click', '#multi_adjust', function() {
			 if($('.checkbox').is(":checked") === false){
				alert('Please select at least one.');
				return false;
			}
			var arrItems = [];
			$('.checkbox').each(function(i){
				if($(this).is(":checked")){
					if(this.value != ""){
						arrItems[i] = $(this).val();   
					}
				}
			});
			$('#myModal').modal({remote: '<?=base_url('products/multi_adjustment');?>?data=' + arrItems + ''});
			$('#myModal').modal('show');
        });
		$('#excel').on('click', function(e){
			e.preventDefault();
				window.location.href = "<?=site_url('reports/customersReportDetails/0/xls/'.$warehouse1.'/'.$customer1.'/'.$reference1.'/'.$product_id1.'/'.$from_date1.'/'.$to_date1)?>";
				return false;
		});
		$('#pdf').on('click', function(e){
			e.preventDefault();
				window.location.href = "<?=site_url('reports/customersReportDetails/pdf/0/'.$warehouse1.'/'.$customer1.'/'.$reference1.'/'.$product_id1.'/'.$from_date1.'/'.$to_date1)?>";
				return false;	
		});
	});
</script>

