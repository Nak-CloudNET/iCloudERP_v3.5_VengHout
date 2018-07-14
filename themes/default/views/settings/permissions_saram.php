<?php //$this->erp->print_arrays($p); ?>

<style>
    .table td:first-child {
        font-weight: bold;
    }

    label {
        margin-right:10px;
    }
	.padding0{
		padding:0px;
	}
  
</style>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-folder-open"></i><?= lang('group_permissions'); ?></h2>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <p class="introtext"><?= lang("set_permissions"); ?></p>

                <?php if (!empty($p)) {
                    if ($p->group_id != 1) {

                        echo form_open("system_settings/permissions/" . $id); ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped">

                                <thead>
									<tr>
										<th colspan="7"
											class="text-center"><?php echo $group->description . ' ( ' . $group->name . ' ) ' . $this->lang->line("group_permissions"); ?></th>
									</tr>
									<tr>
										<th rowspan="2" class="text-center"><?= lang("module_name"); ?>
										</th>
										<th colspan="6" class="text-center"><?= lang("permissions"); ?></th>
									</tr>
									<tr>
										<th class="text-center"><?= lang("view"); ?></th>
										<th class="text-center"><?= lang("add"); ?></th>
										<th class="text-center"><?= lang("edit"); ?></th>
										<th class="text-center"><?= lang("delete"); ?></th>
										<th class="text-center"><?= lang("import"); ?></th>
										<th class="text-center"><?= lang("export"); ?></th>
										<th class="text-center"><?= lang("misc"); ?></th>
									</tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><?= lang("products"); ?></td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="products-index" <?php echo $p->{'products-index'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="products-add" <?php echo $p->{'products-add'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="products-edit" <?php echo $p->{'products-edit'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="products-delete" <?php echo $p->{'products-delete'} ? "checked" : ''; ?>>
                                    </td>
									<td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="products-import" <?php echo $p->{'products-import'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="products-export" <?php echo $p->{'products-export'} ? "checked" : ''; ?>>
                                    </td>
                                    <td>
									   <div class="container-fluid ">
												<div class="col-md-6">
                                               												
														<input type="checkbox" value="1" id="products-cost" class="checkbox"
															   name="products-cost" <?php echo $p->{'products-cost'} ? "checked" : ''; ?>>
						
                                    													
														<label
																for="products-cost" class="padding05"><?= lang('cost') ?></label>
												 
												</div>
												<div class="col-md-6">
													<input type="checkbox" value="1" id="products-price" class="checkbox"
														   name="products-price" <?php echo $p->{'products-price'} ? "checked" : ''; ?>><label
														for="products-price" class="padding05"><?= lang('price') ?></label>
												</div>
												<div class="col-md-6">
													<input type="checkbox" value="1" id="products-adjustments" class="checkbox"
														name="products-adjustments" <?php echo $p->{'products-adjustments'} ? "checked" : ''; ?>><label
														for="products-adjustments" class="padding05"><?= lang('quantity_adjustments') ?></label>
												</div>	
                                                <div class="col-md-6">												
													<input type="checkbox" value="1" id="products_convert_add" class="checkbox"
														name="products_convert_add" <?php echo $p->{'products_convert_add'} ? "checked" : ''; ?>><label
														for="products_convert_add" class="padding05"><?= lang('Convert Add') ?></label>
											   </div>
											   <div class="col-md-6">
													<input type="checkbox" value="1" id="product_items_convert" class="checkbox"
														name="product_items_convert" <?php echo $p->{'product_items_convert'} ? "checked" : ''; ?>><label
														for="product_items_convert" class="padding05"><?= lang('Items Convert') ?></label>
											  </div>
											  <div class="col-md-6">
													<input type="checkbox" value="1" id="product_print_barcodes" class="checkbox"
														name="product_print_barcodes" <?php echo $p->{'product_print_barcodes'} ? "checked" : ''; ?>><label
														for="product_print_barcodes" class="padding05"><?= lang('Print Barcodes') ?></label>
											 </div>
											 <div class="col-md-6">
													<input type="checkbox" value="1" id="product_using_stock" class="checkbox"
														name="product_using_stock" <?php echo $p->{'product_using_stock'} ? "checked" : ''; ?>><label
														for="product_using_stock" class="padding05"><?= lang('Using Stock') ?></label>
											</div>
											<div class="col-md-6">
													<input type="checkbox" value="1" id="product_list_using_stock" class="checkbox"
														name="product_list_using_stock" <?php echo $p->{'product_list_using_stock'} ? "checked" : ''; ?>><label
														for="product_list_using_stock" class="padding05"><?= lang('List Using Stock') ?></label>
											</div>
											<div class="col-md-6">
													<input type="checkbox" value="1" id="product_import" class="checkbox"
														name="product_import" <?php echo $p->{'product_import'} ? "checked" : ''; ?>><label
														for="product_import" class="padding05"><?= lang('import') ?></label>
											</div>
											<div class="col-md-6">
													<input type="checkbox" value="1" id="product_import_quantity" class="checkbox"
														name="product_import_quantity" <?php echo $p->{'product_import_quantity'} ? "checked" : ''; ?>><label
														for="product_import_quantity" class="padding05"><?= lang('import quantity') ?></label>
											</div>
											<div class="col-md-6">
													<input type="checkbox" value="1" id="product_import_price_cost" class="checkbox"
														name="product_import_price_cost" <?php echo $p->{'product_import_price_cost'} ? "checked" : ''; ?>><label
														for="product_import_price_cost" class="padding05"><?= lang('Import Price Cost') ?></label>
											</div>
											<div class="col-md-6">
													<input type="checkbox" value="1" id="product_return_list" class="checkbox"
														name="product_return_list" <?php echo $p->{'product_return_list'} ? "checked" : ''; ?>><label
														for="product_return_list" class="padding05"><?= lang('return list') ?></label>
											</div>
								    	</div>
											
                                    </td>
                                </tr>
								
								<tr>
                                    <td><?= lang("sale_order"); ?></td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="sale_order-index" <?php echo $p->{'sale_order-index'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="sale_order-add" <?php echo $p->{'sale_order-add'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="sale_order-edit" <?php echo $p->{'sale_order-edit'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="sale_order-delete" <?php echo $p->{'sale_order-delete'} ? "checked" : ''; ?>>
                                    </td>
									<td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="sale_order-import" <?php echo $p->{'sale_order-import'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="sale_order-export" <?php echo $p->{'sale_order-export'} ? "checked" : ''; ?>>
                                    </td>
									
                                </tr>
								

                                <tr>
                                    <td><?= lang("sales"); ?></td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="sales-index" <?php echo $p->{'sales-index'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="sales-add" <?php echo $p->{'sales-add'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="sales-edit" <?php echo $p->{'sales-edit'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="sales-delete" <?php echo $p->{'sales-delete'} ? "checked" : ''; ?>>
                                    </td>
									<td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="sales-import" <?php echo $p->{'sales-import'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="sales-export" <?php echo $p->{'sales-export'} ? "checked" : ''; ?>>
                                    </td>
									
                                    <td>
									    <div class="container-fluid">
												<div class="col-md-4"> 
													<input type="checkbox" value="1" id="sales-email" class="checkbox"
														   name="sales-email" <?php echo $p->{'sales-email'} ? "checked" : ''; ?>><label
														for="sales-email" class="padding05"><?= lang('email') ?></label>
												</div>
													<!--<input type="checkbox" value="1" id="sales-pdf" class="checkbox"
														   name="sales-pdf" <?php echo $p->{'sales-pdf'} ? "checked" : ''; ?>><label
														for="sales-pdf" class="padding05"><?= lang('pdf') ?></label>-->
												<div class="col-md-4">
													<?php if (POS) { ?>
														<input type="checkbox" value="1" id="pos-index" class="checkbox"
															   name="pos-index" <?php echo $p->{'pos-index'} ? "checked" : ''; ?>>
														<label for="pos-index" class="padding05"><?= lang('pos') ?></label>
													<?php } ?>
											  </div>
											  <div class="col-md-4">
													<input type="checkbox" value="1" id="sales-payments" class="checkbox"
														   name="sales-payments" <?php echo $p->{'sales-payments'} ? "checked" : ''; ?>><label
														for="sales-payments" class="padding05"><?= lang('payments') ?></label>
											 </div>
											 <div class="col-md-4">
													<input type="checkbox" value="1" id="sales-return_sales" class="checkbox"
														   name="sales-return_sales" <?php echo $p->{'sales-return_sales'} ? "checked" : ''; ?>><label
														for="sales-return_sales"
														class="padding05"><?= lang('return') ?></label>
											</div>
											<div class="col-md-4">
													<input type="checkbox" value="1" id="sales-opening_ar" class="checkbox"
															name="sales-opening_ar" <?php echo $p->{'sales-opening_ar'} ? "checked" : ''; ?>><label
														for="sales-opening_ar"
														class="padding05"><?= lang('opening/ar') ?></label>
											</div>
											<div class="col-md-4">
													<input type="checkbox" value="1" id="sales-loan" class="checkbox"
														   name="sales-loan" <?php echo $p->{'sales-loan'} ? "checked" : ''; ?>><label
														for="sales-loan"
														class="padding05"><?= lang('loans') ?></label>
											</div>
											
											<div class="col-md-4">
													<input type="checkbox" value="1" id="sales-discount" class="checkbox"
														   name="sales-discount" <?php echo $p->{'sales-discount'} ? "checked" : ''; ?>><label
														for="sales-discount"
														class="padding05"><?= lang('discount') ?></label>
											</div>
											<div class="col-md-4">
													<input type="checkbox" value="1" id="sales-price" class="checkbox"
														   name="sales-price" <?php echo $p->{'sales-price'} ? "checked" : ''; ?>><label
														for="sales-price"
														class="padding05"><?= lang('price') ?></label>
											</div>
											<div class="col-md-4">
													<input type="checkbox" value="1" id="sales-authorize" class="checkbox"
														   name="sales-authorize" <?php echo $p->{'sales-authorize'} ? "checked" : ''; ?>><label
														for="sales-authorize"
														class="padding05"><?= lang('authorize_sales') ?></label>
											</div>
									   </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td><?= lang("deliveries"); ?></td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="sales-deliveries" <?php echo $p->{'sales-deliveries'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="sales-add_delivery" <?php echo $p->{'sales-add_delivery'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="sales-edit_delivery" <?php echo $p->{'sales-edit_delivery'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="sales-delete_delivery" <?php echo $p->{'sales-delete_delivery'} ? "checked" : ''; ?>>
                                    </td>
									<td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="sales-import_delivery" <?php echo $p->{'sales-import_delivery'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="sales-export_delivery" <?php echo $p->{'sales-export_delivery'} ? "checked" : ''; ?>>
                                    </td>
                                    <td>
                                        <!--<input type="checkbox" value="1" id="sales-email" class="checkbox" name="sales-email_delivery" <?php echo $p->{'sales-email_delivery'} ? "checked" : ''; ?>><label for="sales-email_delivery" class="padding05"><?= lang('email') ?></label>
                                        <input type="checkbox" value="1" id="sales-pdf" class="checkbox"
                                               name="sales-pdf_delivery" <?php echo $p->{'sales-pdf_delivery'} ? "checked" : ''; ?>><label
                                            for="sales-pdf_delivery" class="padding05"><?= lang('pdf') ?></label>
											-->
                                    </td>
                                </tr>
                                <tr>
                                    <td><?= lang("gift_cards"); ?></td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="sales-gift_cards" <?php echo $p->{'sales-gift_cards'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="sales-add_gift_card" <?php echo $p->{'sales-add_gift_card'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="sales-edit_gift_card" <?php echo $p->{'sales-edit_gift_card'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="sales-delete_gift_card" <?php echo $p->{'sales-delete_gift_card'} ? "checked" : ''; ?>>
                                    </td>
									<td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="sales-import_gift_card" <?php echo $p->{'sales-import_gift_card'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="sales-export_gift_card" <?php echo $p->{'sales-export_gift_card'} ? "checked" : ''; ?>>
                                    </td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td><?= lang("quotes"); ?></td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="quotes-index" <?php echo $p->{'quotes-index'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="quotes-add" <?php echo $p->{'quotes-add'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="quotes-edit" <?php echo $p->{'quotes-edit'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="quotes-delete" <?php echo $p->{'quotes-delete'} ? "checked" : ''; ?>>
                                    </td>
									<td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="quotes-import" <?php echo $p->{'quotes-import'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="quotes-export" <?php echo $p->{'quotes-export'} ? "checked" : ''; ?>>
                                    </td>
                                    <td>
                                        <div class="container-fluid" style="padding-left:30px;">									
											<input type="checkbox" value="1" id="quotes-email" class="checkbox"
												   name="quotes-email" <?php echo $p->{'quotes-email'} ? "checked" : ''; ?>><label
												for="quotes-email" class="padding05"><?= lang('email') ?></label>
											<!--
											<input type="checkbox" value="1" id="quotes-pdf" class="checkbox"
												   name="quotes-pdf" <?php echo $p->{'quotes-pdf'} ? "checked" : ''; ?>><label
												for="quotes-pdf" class="padding05"><?= lang('pdf') ?></label>-->
										</div>
                                    </td>
                                </tr>

                                <tr>
                                    <td><?= lang("purchases"); ?></td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="purchases-index" <?php echo $p->{'purchases-index'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="purchases-add" <?php echo $p->{'purchases-add'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="purchases-edit" <?php echo $p->{'purchases-edit'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="purchases-delete" <?php echo $p->{'purchases-delete'} ? "checked" : ''; ?>>
                                    </td>
									<td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="purchases-import" <?php echo $p->{'purchases-import'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="purchases-export" <?php echo $p->{'purchases-export'} ? "checked" : ''; ?>>
                                    </td>
                                    <td>
									     <div class="container-fluid" >
										      <div class="col-md-6">
													<input type="checkbox" value="1" id="purchases-email" class="checkbox"
														   name="purchases-email" <?php echo $p->{'purchases-email'} ? "checked" : ''; ?>><label
														for="purchases-email" class="padding05"><?= lang('email') ?></label>
										      </div>
													<!--<input type="checkbox" value="1" id="purchases-pdf" class="checkbox"
														   name="purchases-pdf" <?php echo $p->{'purchases-pdf'} ? "checked" : ''; ?>><label
														for="purchases-pdf" class="padding05"><?= lang('pdf') ?></label>-->
											 <div class="col-md-6">
													<input type="checkbox" value="1" id="purchases-payments" class="checkbox"
														   name="purchases-payments" <?php echo $p->{'purchases-payments'} ? "checked" : ''; ?>><label
														for="purchases-payments" class="padding05"><?= lang('payments') ?></label>
											</div>
											<div class="col-md-6">
													<input type="checkbox" value="1" id="purchases-expenses" class="checkbox"
														   name="purchases-expenses" <?php echo $p->{'purchases-expenses'} ? "checked" : ''; ?>><label
														for="purchases-expenses" class="padding05"><?= lang('expenses') ?></label>
											</div>
											<div class="col-md-6">
													<input type="checkbox" value="1" id="purchases-return_list" class="checkbox"
														   name="purchases-return_list" <?php echo $p->{'purchases-return_list'} ? "checked" : ''; ?>><label
														for="purchases-return_list" class="padding05"><?= lang('purchase_return') ?></label>
											</div>
											<div class="col-md-6">
													<input type="checkbox" value="1" id="purchases-return_add" class="checkbox"
														   name="purchases-return_add" <?php echo $p->{'purchases-return_add'} ? "checked" : ''; ?>><label
														for="purchases-return_add" class="padding05"><?= lang('purchases-return_add') ?></label>
											</div>
											<div class="col-md-6">
													<input type="checkbox" value="1" id="purchases-opening_ap" class="checkbox"
														   name="purchases-opening_ap" <?php echo $p->{'purchases-opening_ap'} ? "checked" : ''; ?>><label
														for="purchases-opening_ap" class="padding05"><?= lang('purchases-opening_ap') ?></label>
											</div>
											<div class="col-md-6">
													<input type="checkbox" value="1" id="purchases-opening_ap" class="checkbox"
														   name="purchases-import_expanse" <?php echo $p->{'purchases-import_expanse'} ? "checked" : ''; ?>><label
														for="purchases-import_expanse" class="padding05"><?= lang('Import Expanse') ?></label>
											</div>
											<div class="col-md-6">
													<input type="checkbox" value="1" id="purchases-cost" class="checkbox"
														   name="purchases-cost" <?php echo $p->{'purchases-cost'} ? "checked" : ''; ?>><label
														for="purchases-cost" class="padding05"><?= lang('cost') ?></label>
											</div>
											<div class="col-md-6">
													<input type="checkbox" value="1" id="purchase-price" class="checkbox"
														   name="purchases-price" <?php echo $p->{'purchases-price'} ? "checked" : ''; ?>><label
														for="purchase-price" class="padding05"><?= lang('price') ?></label>
											</div>
											<div class="col-md-6">
													<input type="checkbox" value="1" id="purchase-authorize" class="checkbox"
														   name="purchase-authorize" <?php echo $p->{'purchase-authorize'} ? "checked" : ''; ?>><label
														for="purchase-authorize" class="padding05"><?= lang('authorize_purchase') ?></label>
											</div>
										 </div>
                                    </td>
                                </tr>
								
								<tr>
                                    <td><?= lang("purchases_order"); ?></td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="purchases_order-index" <?php echo $p->{'purchases_order-index'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="purchases_order-add" <?php echo $p->{'purchases_order-add'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="purchases_order-edit" <?php echo $p->{'purchases_order-edit'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="purchases_order-delete" <?php echo $p->{'purchases_order-delete'} ? "checked" : ''; ?>>
                                    </td>
									<td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="purchases_order-import" <?php echo $p->{'purchases_order-import'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="purchases_order-export" <?php echo $p->{'purchases_order-export'} ? "checked" : ''; ?>>
                                    </td>
									<td>
									  <div class="container-fluid" style="padding-left:30px;">
									  
                                        <input type="checkbox" value="1" id="purchases_order-email" class="checkbox"
                                               name="purchases_order-email" <?php echo $p->{'purchases_order-email'} ? "checked" : ''; ?>><label
                                            for="purchases_order-email" class="padding05"><?= lang('email') ?></label>
                                        <!--<input type="checkbox" value="1" id="purchases_order-pdf" class="checkbox"
                                               name="purchases_order-pdf" <?php echo $p->{'purchases_order-pdf'} ? "checked" : ''; ?>><label
                                            for="purchases_order-pdf" class="padding05"><?= lang('pdf') ?></label>-->
                                        <input type="checkbox" value="1" id="purchases_order-payments" class="checkbox"
                                               name="purchases_order-payments" <?php echo $p->{'purchases_order-payments'} ? "checked" : ''; ?>><label
                                            for="purchases_order-payments" class="padding05"><?= lang('payments') ?></label>
                                        <input type="checkbox" value="1" id="purchases_order-expenses" class="checkbox"
                                               name="purchases_order-expenses" <?php echo $p->{'purchases_order-expenses'} ? "checked" : ''; ?>><label
                                            for="purchases_order-expenses" class="padding05"><?= lang('expenses') ?></label>
										<input type="checkbox" value="1" id="purchase_order-cost" class="checkbox"
                                               name="purchase_order-cost" <?php echo $p->{'purchase_order-cost'} ? "checked" : ''; ?>><label
                                            for="purchase_order-cost" class="padding05"><?= lang('cost') ?></label>
										<input type="checkbox" value="1" id="purchase_order-price" class="checkbox"
                                               name="purchase_order-price" <?php echo $p->{'purchase_order-price'} ? "checked" : ''; ?>><label
                                            for="purchase_order-price" class="padding05"><?= lang('price') ?></label>
									  </div>
										
                                    </td>
									
                                </tr>
								
							    <tr>
                                    <td><?= lang("purchase_request"); ?></td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="purchase_request-index" <?php echo $p->{'purchase_request-index'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="purchase_request-add" <?php echo $p->{'purchase_request-add'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="purchase_request-edit" <?php echo $p->{'purchase_request-edit'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="purchase_request-delete" <?php echo $p->{'purchase_request-delete'} ? "checked" : ''; ?>>
                                    </td>
									<td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="purchase_request-import" <?php echo $p->{'purchase_request-import'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="purchase_request-export" <?php echo $p->{'purchase_request-export'} ? "checked" : ''; ?>>
                                    </td>
									<td>
									     <div class="container-fluid" style="padding-left:30px;">
										       
												 <input type="checkbox" value="1" id="purchase_request-cost" class="checkbox"
													   name="purchase_request-cost" <?php echo $p->{'purchase_request-cost'} ? "checked" : ''; ?>><label
													for="purchase_request-cost" class="padding05"><?= lang('cost') ?></label>
											
                                        											  
												<input type="checkbox" value="1" id="purchase_request-price" class="checkbox"
													   name="purchase_request-price" <?php echo $p->{'purchase_request-price'} ? "checked" : ''; ?>><label
													for="purchase_request-price" class="padding05"><?= lang('price') ?></label>
											
									    </div>		
                                    </td>
									
                                </tr>
								
								
								
								<!-- Permition ACC-->
								<tr>
                                    <td><?= lang("accounts"); ?></td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="accounts-index" <?php echo $p->{'accounts-index'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="accounts-add" <?php echo $p->{'accounts-add'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="accounts-edit" <?php echo $p->{'accounts-edit'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="accounts-delete" <?php echo $p->{'accounts-delete'} ? "checked" : ''; ?>>
                                    </td>
									<td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="accounts-import" <?php echo $p->{'accounts-import'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="accounts-export" <?php echo $p->{'accounts-export'} ? "checked" : ''; ?>>
                                    </td>
                                    <td>
									   <div class="container-fluid">
										 <div class="col-md-6">
											<input type="checkbox" value="1" id="account-list_receivable" class="checkbox"
											name="account-list_receivable" <?php echo $p->{'account-list_receivable'} ? "checked" : ''; ?>><label
											for="account-list_receivable" class="padding05"><?= lang('account-list_receivable') ?></label>
										</div>
										<div class="col-md-6">
											<input type="checkbox" value="1" id="account-list_ar_aging" class="checkbox"
											name="account-list_ar_aging" <?php echo $p->{'account-list_ar_aging'} ? "checked" : ''; ?>><label
											for="account-list_ar_aging" class="padding05"><?= lang('account-list_ar_aging') ?></label>
										</div>
										<div class="col-md-6">
											<input type="checkbox" value="1" id="account-ar_by_customer" class="checkbox"
											name="account-ar_by_customer" <?php echo $p->{'account-ar_by_customer'} ? "checked" : ''; ?>><label
											for="account-ar_by_customer" class="padding05"><?= lang('account-ar_by_customer') ?></label>
										</div>
										<div class="col-md-6">
											<input type="checkbox" value="1" id="account-bill_receipt" class="checkbox"
											name="account-bill_receipt" <?php echo $p->{'account-bill_receipt'} ? "checked" : ''; ?>><label
											for="account-bill_receipt" class="padding05"><?= lang('account-bill_receipt') ?></label>
										</div>
										<div class="col-md-6">
											<input type="checkbox" value="1" id="account-list_payable" class="checkbox"
											name="account-list_payable" <?php echo $p->{'account-list_payable'} ? "checked" : ''; ?>><label
											for="account-list_payable" class="padding05"><?= lang('account-list_payable') ?></label>
										</div>
										<div class="col-md-6">	
											<input type="checkbox" value="1" id="account-list_ap_aging" class="checkbox"
											   name="account-list_ap_aging" <?php echo $p->{'account-list_ap_aging'} ? "checked" : ''; ?>><label
											for="account-list_ap_aging" class="padding05"><?= lang('account-list_ap_aging') ?></label>
										</div>
										<div class="col-md-6">
											<input type="checkbox" value="1" id="account-ap_by_supplier" class="checkbox"
											name="account-ap_by_supplier" <?php echo $p->{'account-ap_by_supplier'} ? "checked" : ''; ?>><label
											for="account-ap_by_supplier" class="padding05"><?= lang('account-ap_by_supplier') ?></label>
										</div>
										<div class="col-md-6">
											<input type="checkbox" value="1" id="account-bill_payable" class="checkbox"
											name="account-bill_payable" <?php echo $p->{'account-bill_payable'} ? "checked" : ''; ?>><label
											for="account-bill_payable" class="padding05"><?= lang('account-bill_payable') ?></label>
										</div>
										<div class="col-md-6">
											<input type="checkbox" value="1" id="account-list_ac_head" class="checkbox"
											name="account-list_ac_head" <?php echo $p->{'account-list_ac_head'} ? "checked" : ''; ?>><label
											for="account-list_ac_head" class="padding05"><?= lang('account-list_ac_head') ?></label>
										</div>
										<div class="col-md-6">									   
											<input type="checkbox" value="1" id="account-add_ac_head" class="checkbox"
											   name="account-add_ac_head" <?php echo $p->{'account-add_ac_head'} ? "checked" : ''; ?>><label
											for="account-add_ac_head" class="padding05"><?= lang('account-add_ac_head') ?></label>
										</div>
										<div class="col-md-6">									  
											<input type="checkbox" value="1" id="account-list_customer_deposit" class="checkbox"
											   name="account-list_customer_deposit" <?php echo $p->{'account-list_customer_deposit'} ? "checked" : ''; ?>><label
											for="account-list_customer_deposit" class="padding05"><?= lang('account-list_customer_deposit') ?></label>
										</div>
										<div class="col-md-6">
											<input type="checkbox" value="1" id="account-add_customer_deposit" class="checkbox"
											name="account-add_customer_deposit" <?php echo $p->{'account-add_customer_deposit'} ? "checked" : ''; ?>><label
											for="account-add_customer_deposit" class="padding05"><?= lang('account-add_customer_deposit') ?></label>
										</div>
										<div class="col-md-6">
											<input type="checkbox" value="1" id="account-list_supplier_deposit" class="checkbox"
											name="account-list_supplier_deposit" <?php echo $p->{'account-list_supplier_deposit'} ? "checked" : ''; ?>><label
											for="account-list_supplier_deposit" class="padding05"><?= lang('account-list_supplier_deposit') ?></label>
										</div>
										<div class="col-md-6">
											<input type="checkbox" value="1" id="account-add_supplier_deposit" class="checkbox"
											name="account-add_supplier_deposit" <?php echo $p->{'account-add_supplier_deposit'} ? "checked" : ''; ?>><label
											for="account-add_supplier_deposit" class="padding05"><?= lang('account-add_supplier_deposit') ?></label>
										</div>
										<div class="col-md-6">
											<input type="checkbox" value="1" id="account_setting" class="checkbox"
											name="account_setting" <?php echo $p->{'account_setting'} ? "checked" : ''; ?>><label
											for="account_setting" class="padding05"><?= lang('account_setting') ?></label>
										</div>	
                                      </div>										
                                    </td>
                                </tr>

                                <tr>
                                    <td><?= lang("transfers"); ?></td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="transfers-index" <?php echo $p->{'transfers-index'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="transfers-add" <?php echo $p->{'transfers-add'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="transfers-edit" <?php echo $p->{'transfers-edit'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="transfers-delete" <?php echo $p->{'transfers-delete'} ? "checked" : ''; ?>>
                                    </td>
									<td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="transfers-import" <?php echo $p->{'transfers-import'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="transfers-export" <?php echo $p->{'transfers-export'} ? "checked" : ''; ?>>
                                    </td>
                                    <td>
									    <div class="container-fluid" style="padding-left:30px;">
											<input type="checkbox" value="1" id="transfers-email" class="checkbox"
												   name="transfers-email" <?php echo $p->{'transfers-email'} ? "checked" : ''; ?>><label
												for="transfers-email" class="padding05"><?= lang('email') ?></label>
											<!--<input type="checkbox" value="1" id="transfers-pdf" class="checkbox"
												   name="transfers-pdf" <?php echo $p->{'transfers-pdf'} ? "checked" : ''; ?>><label
												for="transfers-pdf" class="padding05"><?= lang('pdf') ?></label>-->
										</div>		
                                    </td>
                                </tr>

                                <tr>
                                    <td><?= lang("customers"); ?></td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="customers-index" <?php echo $p->{'customers-index'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="customers-add" <?php echo $p->{'customers-add'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="customers-edit" <?php echo $p->{'customers-edit'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="customers-delete" <?php echo $p->{'customers-delete'} ? "checked" : ''; ?>>
                                    </td>
									<td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="customers-import" <?php echo $p->{'customers-import'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="customers-export" <?php echo $p->{'customers-export'} ? "checked" : ''; ?>>
                                    </td>
                                    <td>
                                    </td>
                                </tr>

                                <tr>
                                    <td><?= lang("suppliers"); ?></td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="suppliers-index" <?php echo $p->{'suppliers-index'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="suppliers-add" <?php echo $p->{'suppliers-add'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="suppliers-edit" <?php echo $p->{'suppliers-edit'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="suppliers-delete" <?php echo $p->{'suppliers-delete'} ? "checked" : ''; ?>>
                                    </td>
									<td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="suppliers-import" <?php echo $p->{'suppliers-import'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="suppliers-export" <?php echo $p->{'suppliers-export'} ? "checked" : ''; ?>>
                                    </td>
                                    <td></td>
                                </tr>
								
								<tr>
                                    <td><?= lang("users"); ?></td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="users-index" <?php echo $p->{'users-index'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="users-add" <?php echo $p->{'users-add'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="users-edit" <?php echo $p->{'users-edit'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="users-delete" <?php echo $p->{'users-delete'} ? "checked" : ''; ?>>
                                    </td>
									<td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="users-import" <?php echo $p->{'users-import'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="users-export" <?php echo $p->{'users-export'} ? "checked" : ''; ?>>
                                    </td>
                                    <td></td>
                                </tr>
								
								<tr>
                                    <td><?= lang("drivers"); ?></td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="drivers-index" <?php echo $p->{'drivers-index'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="drivers-add" <?php echo $p->{'drivers-add'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="drivers-edit" <?php echo $p->{'drivers-edit'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="drivers-delete" <?php echo $p->{'drivers-delete'} ? "checked" : ''; ?>>
                                    </td>
									<td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="drivers-import" <?php echo $p->{'drivers-import'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="drivers-export" <?php echo $p->{'drivers-export'} ? "checked" : ''; ?>>
                                    </td>
                                    <td></td>
                                </tr>
								
								<tr>
                                    <td><?= lang("employees"); ?></td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="employees-index" <?php echo $p->{'employees-index'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="employees-add" <?php echo $p->{'employees-add'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="employees-edit" <?php echo $p->{'employees-edit'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="employees-delete" <?php echo $p->{'employees-delete'} ? "checked" : ''; ?>>
                                    </td>
									<td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="employees-import" <?php echo $p->{'employees-import'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="employees-export" <?php echo $p->{'employees-export'} ? "checked" : ''; ?>>
                                    </td>
                                    <td></td>
                                </tr>
								
								<tr>
                                    <td><?= lang("projects"); ?></td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="projects-index" <?php echo $p->{'projects-index'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="projects-add" <?php echo $p->{'projects-add'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="projects-edit" <?php echo $p->{'projects-edit'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="projects-delete" <?php echo $p->{'projects-delete'} ? "checked" : ''; ?>>
                                    </td>
									<td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="projects-import" <?php echo $p->{'projects-import'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="projects-export" <?php echo $p->{'projects-export'} ? "checked" : ''; ?>>
                                    </td>
                                    <td></td>
                                </tr>
								
								<tr>
                                    <td><?= lang("room || table calendar"); ?></td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="room-index" <?php echo $p->{'room-index'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="room-add" <?php echo $p->{'room-add'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="room-edit" <?php echo $p->{'room-edit'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="room-delete" <?php echo $p->{'room-delete'} ? "checked" : ''; ?>>
                                    </td>
									<td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="room-import" <?php echo $p->{'room-import'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="room-export" <?php echo $p->{'room-export'} ? "checked" : ''; ?>>
                                    </td>
                                    <td></td>
                                </tr>
								
								<tr>
                                    <td><?= lang("list sale room || table"); ?></td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="sale-room-index" <?php echo $p->{'sale-room-index'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="sale-room-add" <?php echo $p->{'sale-room-add'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="sale-room-edit" <?php echo $p->{'sale-room-edit'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="sale-room-delete" <?php echo $p->{'sale-room-delete'} ? "checked" : ''; ?>>
                                    </td>
									<td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="sale-room-import" <?php echo $p->{'sale-room-import'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="sale-room-export" <?php echo $p->{'sale-room-export'} ? "checked" : ''; ?>>
                                    </td>
                                    <td></td>
                                </tr>
								
								

                                </tbody>
                            </table>
                        </div>
                        
						<div class="table-responsive">
                            <table cellpadding="0" cellspacing="0" border="0"
                                   class="table table-bordered table-hover table-striped" style="margin-bottom: 5px;">
                                <thead>
                                <tr>
                                    <th><?= lang("reports"); ?>
                                    </th>
                                </tr>
                                <tr>
                                <td>
                                  <div class="row">
                                  <div class="col-md-12">
                                  <!-- Report Product -->
                                  <div class="col-md-4">                                    
                                    <div class="col-md-8" style="border-bottom: 2px solid #DDDDDD">
										<input type="checkbox" value="1" class="checkbox" id="products"
												name="product_report-index" <?php echo $p->{'product_report-index'} ? "checked" : ''; ?>><label
												for="product_report-index" class="padding05"><?= lang('product_report-index') ?></label>
                                    </div><br/>
                                    <div class="col-md-12">
                                        <input type="checkbox" value="1" class="checkbox" id="product_quantity_alerts"
                                           name="reports-quantity_alerts" <?php echo $p->{'reports-quantity_alerts'} ? "checked" : ''; ?>><label
                                        for="product_quantity_alerts" class="padding05"><?= lang('product_quantity_alerts') ?></label><br/>
                                        <input type="checkbox" value="1" class="checkbox" id="products_report"
                                           name="product_report-product" <?php echo $p->{'product_report-product'} ? "checked" : ''; ?>><label
                                        for="product_report-product" class="padding05"><?= lang('product_report-product') ?></label><br/>
                                        <input type="checkbox" value="1" class="checkbox" id="product_report-warehouse"
                                           name="product_report-warehouse" <?php echo $p->{'product_report-warehouse'} ? "checked" : ''; ?>><label
                                        for="warehouse_reports" id="warehouse_reports" class="padding05"><?= lang('warehouse_reports') ?></label><br/>
                                        <input type="checkbox" value="1" class="checkbox" id="product_report-in_out"
                                           name="product_report-in_out" <?php echo $p->{'product_report-in_out'} ? "checked" : ''; ?>><label
                                        for="product_report-in_out" class="padding05"><?= lang('product_report-in_out') ?></label><br/>
                                        <input type="checkbox" value="1" class="checkbox" id="product_report-monthly"
                                           name="product_report-monthly" <?php echo $p->{'product_report-monthly'} ? "checked" : ''; ?>><label
                                        for="product_report-monthly" class="padding05"><?= lang('product_report-monthly') ?></label><br/>
                                         <input type="checkbox" value="1" class="checkbox" id="product_report-daily"
                                           name="product_report-daily" <?php echo $p->{'product_report-daily'} ? "checked" : ''; ?>><label
                                        for="product_report-daily" class="padding05"><?= lang('product_report-daily') ?></label><br/>
                                        <input type="checkbox" value="1" class="checkbox" id="product_report-suppliers"
                                           name="product_report-suppliers" <?php echo $p->{'product_report-suppliers'} ? "checked" : ''; ?>><label
                                        for="product_report-suppliers" class="padding05"><?= lang('product_report-suppliers') ?></label><br/>
                                        <input type="checkbox" value="1" class="checkbox" id="product_report-categories"
                                           name="product_report-categories" <?php echo $p->{'product_report-categories'} ? "checked" : ''; ?>><label
                                        for="product_report-categories" class="padding05"><?= lang('product_report-categories') ?></label><br/>
                                        <input type="checkbox" value="1" class="checkbox" id="product_report-categories_value"
                                           name="product_report-categories_value" <?php echo $p->{'product_report-categories_value'} ? "checked" : ''; ?>><label
                                        for="product_report-categories_value" class="padding05"><?= lang('product_report-categories_value') ?></label><br/>
                                                                              
                                  </div>
                                  </div>
                                  <!-- End blog Report Product -->
                                  <!-- Open Blog Sale -->
                                  <div class="col-md-4">
                                    <div class="col-md-8" style="border-bottom: 2px solid #DDDDDD">
                                      <input type="checkbox" value="1" class="checkbox" id="sale_report-index"
                                               name="sale_report-index" <?php echo $p->{'sale_report-index'} ? "checked" : ''; ?>><label
                                            for="sale_report-index" class="padding05"><?= lang('sale_report-index') ?></label>
                                    </div><br/>
                                    <div class="col-md-12">
										  <!--
										  <input type="checkbox" value="1" class="checkbox" id="sale_report-register"
												   name="sale_report-register" <?php echo $p->{'sale_report-register'} ? "checked" : ''; ?>><label
												for="sale_report-register" class="padding05"><?= lang('sale_report-register') ?></label><br/>
										 -->
									    <input type="checkbox" value="1" class="checkbox" id="sale_report-daily"
													name="sale_report-daily" <?php echo $p->{'sale_report-daily'} ? "checked" : ''; ?>><label
												for="sale_report-daily" class="padding05"><?= lang('sale_report-daily') ?></label><br/>
										<input type="checkbox" value="1" class="checkbox" id="sale_report-monthly"
												   name="sale_report-monthly" <?php echo $p->{'sale_report-monthly'} ? "checked" : ''; ?>><label
												for="sale_report-monthly" class="padding05"><?= lang('sale_report-monthly') ?></label><br/>
										<input type="checkbox" value="1" class="checkbox" id="sale_report-report_sale"
												   name="sale_report-report_sale" <?php echo $p->{'sale_report-report_sale'} ? "checked" : ''; ?>><label
												for="sale_report-sale_report" class="padding05"><?= lang('sale_report-sale_report') ?></label><br/>
										<input type="checkbox" value="1" class="checkbox" id="sale_report-disccount"
												   name="sale_report-disccount" <?php echo $p->{'sale_report-disccount'} ? "checked" : ''; ?>><label
												for="sale_report-disccount" class="padding05"><?= lang('sale_report-disccount') ?></label><br/>
										<input type="checkbox" value="1" class="checkbox" id="sale_report-by_delivery_person"
												   name="sale_report-by_delivery_person" <?php echo $p->{'sale_report-by_delivery_person'} ? "checked" : ''; ?>><label
												for="sale_report-by_delivery_person" class="padding05"><?= lang('sale_report-by_delivery_person') ?></label><br/>
										<input type="checkbox" value="1" class="checkbox" id="sale_report-customer"
												   name="sale_report-customer" <?php echo $p->{'sale_report-customer'} ? "checked" : ''; ?>><label
												for="sale_report-customer" class="padding05"><?= lang('sale_report-customer') ?></label><br/> 
										<input type="checkbox" value="1" class="checkbox" id="sale_report-saleman"
												   name="sale_report-saleman" <?php echo $p->{'sale_report-saleman'} ? "checked" : ''; ?>><label
												for="sale_report-saleman" class="padding05"><?= lang('sale_report-saleman') ?></label><br/>  
										<input type="checkbox" value="1" class="checkbox" id="sale_report-staff"
												   name="sale_report-staff" <?php echo $p->{'sale_report-staff'} ? "checked" : ''; ?>><label
												for="sale_report-staff" class="padding05"><?= lang('sale_report-staff') ?></label><br/>
										<input type="checkbox" value="1" class="checkbox" id="sale_report-detail"
												   name="sale_report-detail" <?php echo $p->{'sale_report-detail'} ? "checked" : ''; ?>><label
												for="sale_report-detail" class="padding05"><?= lang('sale_report-detail') ?></label><br/>  
										<input type="checkbox" value="1" class="checkbox" id="sale_report-by_invoice"
												   name="sale_report-by_invoice" <?php echo $p->{'sale_report-by_invoice'} ? "checked" : ''; ?>><label
												for="sale_report-by_invoice" class="padding05"><?= lang('sale_report-by_invoice') ?></label><br/>
										<input type="checkbox" value="1" class="checkbox" id="sale_report-sale_profit"
												   name="sale_report-sale_profit" <?php echo $p->{'sale_report-sale_profit'} ? "checked" : ''; ?>><label
												for="sale_report-sale_profit" class="padding05"><?= lang('sale_report-sale_profit') ?></label><br/>
										
										<input type="checkbox" value="1" class="checkbox" id="sale_report-project"
												   name="sale_report-project" <?php echo $p->{'sale_report-project'} ? "checked" : ''; ?>><label
												for="sale_report-project" class="padding05"><?= lang('sale_report-project') ?></label><br/>
										<input type="checkbox" value="1" class="checkbox" id="sale_report-room_table"
												   name="sale_report-room_table" <?php echo $p->{'sale_report-room_table'} ? "checked" : ''; ?>><label
												for="sale_report-room_table" class="padding05"><?= lang('sale_report-room_table') ?></label><br/>
										
											
                                    </div>
                                  </div>
                                  <!-- End blog Report Sale -->
                                  <!-- Open blog Chart -->
                                  <div class="col-md-4">
                                    <div class="col-md-8" style="border-bottom: 2px solid #DDDDDD">
                                      <input type="checkbox" value="1" class="checkbox" id="chart_report-index"
                                            name="chart_report-index" <?php echo $p->{'chart_report-index'}? "checked" : '';?>>
                                            <label for="chart_report-index" class="padding05"><?= lang('chart_report-index') ?></label>
                                    </div><br/>
                                    <div class="col-md-12">
                                          <input type="checkbox" value="1" class="checkbox" id="chart_report-over_view"
                                            name="chart_report-over_view" <?php echo $p->{'chart_report-over_view'}? "checked" : '';?>>
                                            <label for="chart_report-over_view" class="padding05"><?= lang('chart_report-over_view')?></label><br/>
											
                                          <input type="checkbox" value="1" class="checkbox" id="chart_report-warehouse_stock"
                                            name="chart_report-warehouse_stock" <?php echo $p->{'chart_report-warehouse_stock'}? "checked" : '';?>>
                                            <label for="chart_report-warehouse_stock" class="padding05"><?= lang('chart_report-warehouse_stock') ?></label><br/>
										
										<input type="checkbox" value="1" class="checkbox" id="chart_report-category_stock"
                                            name="chart_report-category_stock" <?php echo $p->{'chart_report-category_stock'}? "checked" : '';?>>
                                            <label for="chart_report-category_stock" class="padding05"><?= lang('chart_report-category_stock') ?></label><br/>
											
                                          <input type="checkbox" value="1" class="checkbox" id="chart_report-profit"
                                            name="chart_report-profit" <?php echo $p->{'chart_report-profit'}? "checked" : '';?>>
                                            <label for="chart_report-profit" class="padding05"><?= lang('chart_report-profit') ?></label><br/>
											
                                          <input type="checkbox" value="1" class="checkbox" id="chart_report-cash_analysis"
                                            name="chart_report-cash_analysis" <?php echo $p->{'chart_report-cash_analysis'}? "checked" : '';?>>
                                            <label for="chart_report-cash_analysis" class="padding05"><?= lang('chart_report-cash_analysis') ?></label><br/><br/>
                                        
										<!--
                                        <input type="checkbox" value="1" class="checkbox" id="chart_report-customize"
                                              name="chart_report-customize" <?php echo $p->{'chart_report-customize'}? "checked" : '';?>>
                                              <label for="chart_report-customize" class="padding05"><?= lang('chart_report-customize') ?></label><br/>
                                        <input type="checkbox" value="1" class="checkbox" id="chart_report-room_table"
                                            name="chart_report-room_table" <?php echo $p->{'chart_report-room_table'}? "checked" : '';?>>
                                        <label for="chart_report-room_table" class="padding05"><?= lang('chart_report-room_table') ?></label><br/>
                                        <input type="checkbox" value="1" class="checkbox" id="chart_report-suspend_profit_and_lose"
                                            name="chart_report-suspend_profit_and_lose" <?php echo $p->{'chart_report-suspend_profit_and_lose'}? "checked" : '';?>>
                                            <label for="chart_report-suspend_profit_and_lose" class="padding05"><?= lang('chart_report-suspend_profit_and_lose') ?></label><br/>
										-->
										
                                    </div>
                                  </div>
                                  <!-- End blog Purchases -->
                                  <!-- Open blog Report Account -->                          
                                  </div>

                                    <div class="col-md-12">
                                        <hr>
                                      <div class="col-md-4"> 
                                        <div class="col-md-8" style="border-bottom: 2px solid #DDDDDD">                                         
                                          <input type="checkbox" value="1" class="checkbox" id="account_report-index"
                                              name="account_report-index" <?php echo $p->{'account_report-index'}? "checked" : '';?>>
                                              <label for="account_report-index" class="padding05"><?= lang('account_report-index') ?></label>
                                        </div><br/>
										
                                        <div class="col-md-12">
										
											<!--
											<input type="checkbox" value="1" class="checkbox" id="account_report-journal"
												  name="account_report-journal" <?php echo $p->{'account_report-journal'}? "checked" : '';?>>
												  <label for="account_report-journal" class="padding05"><?= lang('account_report-journal') ?></label><br/>
											<input type="checkbox" value="1" class="checkbox" id="account_report-ac_injuiry_report"
												  name="account_report-ac_injuiry_report" <?php echo $p->{'account_report-ac_injuiry_report'}? "checked" : '';?>>
												  <label for="account_report-ac_injuiry_report" class="padding05"><?= lang('account_report-ac_injuiry_report') ?></label><br/>
											<input type="checkbox" value="1" class="checkbox" id="account_report-bsh_by_month"
												  name="account_report-bsh_by_month" <?php echo $p->{'account_report-bsh_by_month'}? "checked" : '';?>>
												  <label for="account_report-bsh_by_month" class="padding05"><?= lang('account_report-bsh_by_month') ?></label><br/>
											<input type="checkbox" value="1" class="checkbox" id="account_report-bsh_by_year"
												  name="account_report-bsh_by_year" <?php echo $p->{'account_report-bsh_by_year'}? "checked" : '';?>>
												  <label for="account_report-bsh_by_year" class="padding05"><?= lang('account_report-bsh_by_year') ?></label><br/>
											<input type="checkbox" value="1" class="checkbox" id="account_report-bsh_by_project"
												  name="account_report-bsh_by_project" <?php echo $p->{'account_report-bsh_by_project'}? "checked" : '';?>>
												  <label for="account_report-bsh_by_project" class="padding05"><?= lang('account_report-bsh_by_project') ?></label><br/>
											<input type="checkbox" value="1" class="checkbox" id="account_report-bsh_by_budget"
												  name="account_report-bsh_by_budget" <?php echo $p->{'account_report-bsh_by_budget'}? "checked" : '';?>>
												  <label for="account_report-bsh_by_budget" class="padding05"><?= lang('account_report-bsh_by_budget') ?></label><br/>
											<input type="checkbox" value="1" class="checkbox" id="account_report-ins_by_month"
												  name="account_report-ins_by_month" <?php echo $p->{'account_report-ins_by_month'}? "checked" : '';?>>
												  <label for="account_report-ins_by_month" class="padding05"><?= lang('account_report-ins_by_month') ?></label><br/> 
											<input type="checkbox" value="1" class="checkbox" id="account_report-ins_by_year"
												  name="account_report-ins_by_year" <?php echo $p->{'account_report-ins_by_year'}? "checked" : '';?>>
												  <label for="account_report-ins_by_year" class="padding05"><?= lang('account_report-ins_by_year') ?></label><br/>  
											<input type="checkbox" value="1" class="checkbox" id="account_report-ins_by_project"
												  name="account_report-ins_by_project" <?php echo $p->{'account_report-ins_by_project'}? "checked" : '';?>>
												  <label for="account_report-ins_by_project" class="padding05"><?= lang('account_report-ins_by_project') ?></label><br/>   
											<input type="checkbox" value="1" class="checkbox" id="account_report-ins_by_budget"
												  name="account_report-ins_by_budget" <?php echo $p->{'account_report-ins_by_budget'}? "checked" : '';?>>
												  <label for="account_report-ins_by_budget" class="padding05"><?= lang('account_report-ins_by_budget') ?></label><br/> 
											<input type="checkbox" value="1" class="checkbox" id="account_report-cash_flow_statement"
												  name="account_report-cash_flow_statement" <?php echo $p->{'account_report-cash_flow_statement'}? "checked" : '';?>>
												  <label for="account_report-cash_flow_statement" class="padding05"><?= lang('account_report-cash_flow_statement') ?></label><br/>
											-->
										
										
											<input type="checkbox" value="1" class="checkbox" id="account_report-ledger"
												name="account_report-ledger" <?php echo $p->{'account_report-ledger'}? "checked" : '';?>>
												<label for="account_report-ledger" class="padding05"><?= lang('account_report-ledger') ?></label><br/>
											<input type="checkbox" value="1" class="checkbox" id="account_report-trail_balance"
												name="account_report-trail_balance" <?php echo $p->{'account_report-trail_balance'}? "checked" : '';?>>
												<label for="account_report-trail_balance" class="padding05"><?= lang('account_report-trail_balance') ?></label><br/>
											<input type="checkbox" value="1" class="checkbox" id="balance_sheet"
												name="account_report-balance_sheet" <?php echo $p->{'account_report-balance_sheet'}? "checked" : '';?>>
												<label for="account_report-balance_sheet" class="padding05"><?= lang('account_report-balance_sheet') ?></label><br/>	  
											<input type="checkbox" value="1" class="checkbox" id="account_report-income_statement"
												name="account_report-income_statement" <?php echo $p->{'account_report-income_statement'}? "checked" : '';?>>
												<label for="account_report-income_statement" class="padding05"><?= lang('account_report-income_statement') ?></label><br/>
											<input type="checkbox" value="1" class="checkbox" id="account_report-cash_book"
												name="account_report-cash_book" <?php echo $p->{'account_report-cash_book'}? "checked" : '';?>>
												<label for="account_report-cash_book" class="padding05"><?= lang('account_report-cash_book') ?></label><br/>
											<input type="checkbox" value="1" class="checkbox" id="account_report-payment"
												name="account_report-payment" <?php echo $p->{'account_report-payment'}? "checked" : '';?>>
												<label for="account_report-payment" class="padding05"><?= lang('account_report-payment') ?></label><br/>
											<input type="checkbox" value="1" class="checkbox" id="account_report-income_statement_detail"
												name="account_report-income_statement_detail" <?php echo $p->{'account_report-income_statement_detail'}? "checked" : '';?>>
												<label for="account_report-income_statement_detail" class="padding05"><?= lang('account_report-income_statement_detail') ?></label><br/>
											  
										</div>
									
									</div>
										
                                    <!-- Profit -->
                                        <div class="col-md-4">
                                        <div class="col-md-8" style="border-bottom: 2px solid #DDDDDD">
                                          <input type="checkbox" value="1" class="checkbox" id="report_profit-index"
                                            name="report_profit-index" <?php echo $p->{'report_profit-index'}? "checked" : '';?>>
                                            <label for="report_profit-index" class="padding05"><?= lang('report_profit-index') ?></label>
                                        </div><br/>
											<!--
											<div class="col-md-12">
											  <input type="checkbox" value="1" class="checkbox" id="report_profit-payments"
												name="report_profit-payments" <?php echo $p->{'report_profit-payments'}? "checked" : '';?>>
												<label for="report_profit-payments" class="padding05"><?= lang('report_profit-payments') ?></label><br/>
											  <input type="checkbox" value="1" class="checkbox" id="report_profit-profit_andOr_lose"
												name="report_profit-profit_andOr_lose" <?php echo $p->{'report_profit-profit_andOr_lose'}? "checked" : '';?>>
												<label for="report_profit-profit_andOr_lose" class="padding05"><?= lang('report_profit-profit_andOr_lose') ?></label><br/>
											  <input type="checkbox" value="1" class="checkbox" id="report_profit-stock"
												name="report_profit-stock" <?php echo $p->{'report_profit-stock'}? "checked" : '';?>>
												<label for="report_profit-stock" class="padding05"><?= lang('report_profit-stock') ?></label><br/> 
											  <input type="checkbox" value="1" class="checkbox" id="report_profit-category"
												name="report_profit-category" <?php echo $p->{'report_profit-category'}? "checked" : '';?>>
												<label for="report_profit-category" class="padding05"><?= lang('report_profit-category') ?></label><br/> 
											  <input type="checkbox" value="1" class="checkbox" id="report_profit-sale_profit"
												name="report_profit-sale_profit" <?php echo $p->{'report_profit-sale_profit'}? "checked" : '';?>>
												<label for="report_profit-sale_profit" class="padding05"><?= lang('report_profit-sale_profit') ?></label><br/>	
											  <input type="checkbox" value="1" class="checkbox" id="report_profit-project"
												name="report_profit-project" <?php echo $p->{'report_profit-project'}? "checked" : '';?>>
												<label for="report_profit-project" class="padding05"><?= lang('report_profit-project') ?></label><br/>
											  <input type="checkbox" value="1" class="checkbox" id="report_profit-project_profit"
												name="report_profit-project_profit" <?php echo $p->{'report_profit-project_profit'}? "checked" : '';?>>
												<label for="report_profit-project_profit" class="padding05"><?= lang('report_profit-project_profit') ?></label><br/>
											</div>
											-->
											<div class="col-md-12">
											  <input type="checkbox" value="1" class="checkbox" id="report_profit-profit_andOr_lose"
												name="report_profit-profit_andOr_lose" <?php echo $p->{'report_profit-profit_andOr_lose'}? "checked" : '';?>>
												<label for="report_profit-profit_andOr_lose" class="padding05"><?= lang('report_profit-profit_andOr_lose') ?></label><br/>
											</div>
											
										
                                        </div>
                                        <!-- Account -->
                                        <div class="col-md-4">
                                          <div class="col-md-8" style="border-bottom: 2px solid #DDDDDD">
                                            <input type="checkbox" value="1" class="checkbox" id="purchase_report-index"
                                                     name="purchase_report-index" <?php echo $p->{'purchase_report-index'} ? "checked" : ''; ?>><label
                                                  for="purchase_report-index" class="padding05"><?= lang('purchase_report-index') ?></label>
                                          </div><br/>
                                          <div class="col-md-12">
											<input type="checkbox" value="1" class="checkbox" id="purchase_report-purchas"
                                                     name="purchase_report-purchas" <?php echo $p->{'purchase_report-purchas'} ? "checked" : ''; ?>><label
                                                  for="purchase_report-purchas" class="padding05"><?= lang('purchase_report-purchas') ?></label><br/>
                                            <input type="checkbox" value="1" class="checkbox" id="purchase_report-daily"
                                                     name="purchase_report-daily" <?php echo $p->{'purchase_report-daily'} ? "checked" : ''; ?>><label
                                                  for="purchase_report-daily" class="padding05"><?= lang('purchase_report-daily') ?></label><br/>
                                            <input type="checkbox" value="1" class="checkbox" id="purchase_report-monthly"
                                                     name="purchase_report-monthly" <?php echo $p->{'purchase_report-monthly'} ? "checked" : ''; ?>><label
                                                  for="purchase_report-monthly" class="padding05"><?= lang('purchase_report-monthly') ?></label><br/>
												  <input type="checkbox" value="1" class="checkbox" id="sale_report-supplier"
												   name="sale_report-supplier" <?php echo $p->{'sale_report-supplier'} ? "checked" : ''; ?>><label
												for="sale_report-supplier" class="padding05"><?= lang('sale_report-supplier') ?></label>
											<!--
										    <input type="checkbox" value="1" class="checkbox" id="purchase_report-supplier"
                                                     name="purchase_report-supplier" <?php echo $p->{'purchase_report-supplier'} ? "checked" : ''; ?>><label
                                                  for="purchase_report-supplier" class="padding05"><?= lang('purchase_report-supplier') ?></label><br/><br/>
											-->
											
												  
                                          </div>  
                                        </div>                                      
                                    </div>
                                  </div>
                                  </td>
                                </tr>
                                </thead>
                            </table>
                        </div>
						
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary"><?=lang('update')?></button>
                        </div>
                        <?php echo form_close();
                    } else {
                        echo $this->lang->line("group_x_allowed");
                    }
                } else {
                    echo $this->lang->line("group_x_allowed");
                } ?>


            </div>
        </div>
    </div>
</div>