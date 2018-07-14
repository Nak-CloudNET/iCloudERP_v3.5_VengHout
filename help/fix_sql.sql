ALTER TABLE  `erp_adjustment_items`
MODIFY COLUMN `date` timestamp(0) NULL DEFAULT CURRENT_TIMESTAMP AFTER `adjust_id`,
MODIFY COLUMN `product_id` int(11) NULL AFTER `date`,
MODIFY COLUMN `quantity` decimal(15, 4) NULL AFTER `option_id`,
MODIFY COLUMN `warehouse_id` int(11) NULL AFTER `quantity`,
MODIFY COLUMN `type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `updated_by`;


ALTER TABLE `erp_adjustments`
MODIFY COLUMN `date` timestamp(0) NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP(0) AFTER `id`,
MODIFY COLUMN `reference_no` varchar(55) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `date`,
MODIFY COLUMN `warehouse_id` int(11) NULL AFTER `reference_no`,
MODIFY COLUMN `created_by` int(11) NULL AFTER `attachment`;


ALTER TABLE `erp_gl_trans`
MODIFY COLUMN `tran_no` bigint(20) NULL DEFAULT 1 AFTER `tran_type`,
MODIFY COLUMN `reference_no` varchar(55) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' AFTER `amount`,
MODIFY COLUMN `biller_id` int(11) NULL AFTER `description`;


ALTER TABLE `erp_gl_trans_audit` 
MODIFY COLUMN `tran_id` int(11) NULL FIRST,
MODIFY COLUMN `tran_no` bigint(20) NULL DEFAULT 1 AFTER `tran_type`,
MODIFY COLUMN `reference_no` varchar(55) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' AFTER `amount`,
MODIFY COLUMN `biller_id` int(11) NULL AFTER `description`,
MODIFY COLUMN `audit_created_by` int(11) NULL AFTER `audit_id`,
MODIFY COLUMN `audit_record_date` timestamp(0) NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP(0) AFTER `audit_created_by`,
MODIFY COLUMN `audit_type` varchar(55) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `audit_record_date`;


ALTER TABLE `erp_inventory_valuation_details`
MODIFY COLUMN `biller_id` int(11) NULL AFTER `id`,
MODIFY COLUMN `product_id` int(11) NULL AFTER `biller_id`,
MODIFY COLUMN `product_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `product_id`,
MODIFY COLUMN `product_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `product_code`,
MODIFY COLUMN `category_id` int(11) NULL AFTER `product_name`,
MODIFY COLUMN `warehouse_id` int(11) NULL AFTER `sub_category_id`;

ALTER TABLE `erp_purchase_items`
MODIFY COLUMN `product_id` int(11) NULL AFTER `transfer_id`,
MODIFY COLUMN `product_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `product_id`,
MODIFY COLUMN `warehouse_id` int(11) NULL AFTER `quantity`;


ALTER TABLE `erp_purchase_items_audit`
MODIFY COLUMN `id` int(11) NULL FIRST,
MODIFY COLUMN `delivery_id` int(11) NULL AFTER `id`,
MODIFY COLUMN `product_id` int(11) NULL AFTER `transfer_id`,
MODIFY COLUMN `product_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `product_id`,
MODIFY COLUMN `product_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `product_code`,
MODIFY COLUMN `net_unit_cost` decimal(25, 4) NULL AFTER `product_type`,
MODIFY COLUMN `quantity` decimal(15, 4) NULL AFTER `net_unit_cost`,
MODIFY COLUMN `warehouse_id` int(11) NULL AFTER `quantity`,
MODIFY COLUMN `subtotal` decimal(25, 4) NULL AFTER `expiry`,
MODIFY COLUMN `date` date NULL AFTER `quantity_balance`,
MODIFY COLUMN `status` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `date`,
MODIFY COLUMN `audit_created_by` int(11) NULL AFTER `audit_id`,
MODIFY COLUMN `audit_record_date` timestamp(0) NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP(0) AFTER `audit_created_by`,
MODIFY COLUMN `audit_type` varchar(55) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `audit_record_date`;


ALTER TABLE `erp_sale_items`
MODIFY COLUMN `sale_id` int(11) UNSIGNED NULL AFTER `id`,
MODIFY COLUMN `product_id` int(11) UNSIGNED NULL AFTER `category_id`,
MODIFY COLUMN `product_code` varchar(55) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `product_id`;

ALTER TABLE `erp_user_logins`
MODIFY COLUMN `user_id` int(11) NULL AFTER `id`,
MODIFY COLUMN `ip_address` varbinary(16) NULL AFTER `company_id`,
MODIFY COLUMN `login` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `ip_address`;


ALTER TABLE `erp_warehouses_products`
MODIFY COLUMN `product_id` int(11) NULL AFTER `id`,
MODIFY COLUMN `warehouse_id` int(11) NULL AFTER `product_id`,
MODIFY COLUMN `quantity` decimal(15, 4) NULL AFTER `warehouse_id`;


ALTER TABLE `erp_stock_trans`
MODIFY COLUMN `product_id` int(11) NULL AFTER `tran_date`,
MODIFY COLUMN `warehouse_id` int(11) NULL AFTER `product_id`,
MODIFY COLUMN `tran_type` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `quantity_balance_unit`,
MODIFY COLUMN `tran_id` int(11) NULL AFTER `tran_type`,
MODIFY COLUMN `manufacture_cost` decimal(25, 8) UNSIGNED ZEROFILL NULL AFTER `tran_ref_id`,
MODIFY COLUMN `freight_cost` decimal(25, 8) UNSIGNED ZEROFILL NULL AFTER `manufacture_cost`,
MODIFY COLUMN `raw_cost` decimal(25, 8) UNSIGNED ZEROFILL NULL AFTER `freight_cost`,
MODIFY COLUMN `labor_cost` decimal(25, 8) UNSIGNED ZEROFILL NULL AFTER `raw_cost`,
MODIFY COLUMN `overhead_cost` decimal(25, 8) UNSIGNED ZEROFILL NULL AFTER `labor_cost`,
MODIFY COLUMN `total_cost` decimal(25, 8) UNSIGNED ZEROFILL NULL AFTER `overhead_cost`,
MODIFY COLUMN `is_close` tinyint(1) UNSIGNED ZEROFILL NULL AFTER `total_cost`;

ALTER TABLE `erp_warehouses_products_variants`
MODIFY COLUMN `option_id` int(11) NULL AFTER `id`,
MODIFY COLUMN `product_id` int(11) NULL AFTER `option_id`,
MODIFY COLUMN `warehouse_id` int(11) NULL AFTER `product_id`,
MODIFY COLUMN `quantity` decimal(15, 4) NULL AFTER `warehouse_id`;

ALTER TABLE `erp_sales`
MODIFY COLUMN `date` timestamp(0) NULL DEFAULT CURRENT_TIMESTAMP AFTER `id`,
MODIFY COLUMN `reference_no` varchar(55) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `date`,
MODIFY COLUMN `customer_id` int(11) NULL AFTER `reference_no`,
MODIFY COLUMN `biller_id` int(11) NULL AFTER `group_areas_id`,
MODIFY COLUMN `updated_count` int(4) UNSIGNED ZEROFILL NULL AFTER `updated_at`;

ALTER TABLE `erp_payments`
MODIFY COLUMN `reference_no` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `transfer_owner_id`,
MODIFY COLUMN `paid_by` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `reference_no`,
MODIFY COLUMN `amount` decimal(25, 4) NULL AFTER `cc_type`,
MODIFY COLUMN `created_by` int(11) NULL AFTER `currency`,
MODIFY COLUMN `type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `attachment`,
MODIFY COLUMN `updated_count` int(4) UNSIGNED ZEROFILL NULL AFTER `updated_by`,
MODIFY COLUMN `opening` tinyint(1) UNSIGNED ZEROFILL NULL DEFAULT 0 AFTER `interest_paid`;


ALTER TABLE `erp_sale_order_audit`
MODIFY COLUMN `id` int(11) NULL FIRST,
MODIFY COLUMN `date` timestamp(0) NULL DEFAULT CURRENT_TIMESTAMP AFTER `id`,
MODIFY COLUMN `reference_no` varchar(55) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `quote_id`,
MODIFY COLUMN `customer_id` int(11) NULL AFTER `reference_no`,
MODIFY COLUMN `customer` varchar(55) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `customer_id`,
MODIFY COLUMN `biller_id` int(11) NULL AFTER `group_areas_id`,
MODIFY COLUMN `biller` varchar(55) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `biller_id`,
MODIFY COLUMN `total` decimal(25, 4) NULL AFTER `staff_note`,
MODIFY COLUMN `grand_total` decimal(25, 4) NULL AFTER `shipping`,
MODIFY COLUMN `total_cost` decimal(25, 4) NULL AFTER `total_items`,
MODIFY COLUMN `pos` tinyint(1) NULL DEFAULT 0 AFTER `total_cost`,
MODIFY COLUMN `surcharge` decimal(25, 4) NULL DEFAULT 0.0000 AFTER `return_id`,
MODIFY COLUMN `reference_no_tax` varchar(55) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `saleman_by`,
MODIFY COLUMN `audit_created_by` int(11) NULL AFTER `audit_id`,
MODIFY COLUMN `audit_record_date` timestamp(0) NULL DEFAULT CURRENT_TIMESTAMP AFTER `audit_created_by`,
MODIFY COLUMN `audit_type` varchar(55) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `audit_record_date`;


ALTER TABLE `erp_stock_count_items`
MODIFY COLUMN `stock_count_id` int(11) NULL AFTER `id`,
MODIFY COLUMN `product_id` int(11) NULL AFTER `stock_count_id`,
MODIFY COLUMN `expected` decimal(15, 4) NULL AFTER `product_variant_id`,
MODIFY COLUMN `counted` decimal(15, 4) NULL AFTER `expected`,
MODIFY COLUMN `cost` decimal(25, 4) NULL AFTER `counted`;

ALTER TABLE `erp_sale_order_items_audit`
MODIFY COLUMN `id` int(11) NULL FIRST,
MODIFY COLUMN `sale_order_id` int(11) UNSIGNED NULL AFTER `id`,
MODIFY COLUMN `product_id` int(11) UNSIGNED NULL AFTER `sale_order_id`,
MODIFY COLUMN `product_code` varchar(55) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `product_id`,
MODIFY COLUMN `product_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `product_code`,
MODIFY COLUMN `net_unit_price` decimal(25, 4) NULL AFTER `option_id`,
MODIFY COLUMN `quantity_received` decimal(15, 4) NULL AFTER `unit_price`,
MODIFY COLUMN `quantity` decimal(15, 4) NULL AFTER `quantity_received`,
MODIFY COLUMN `subtotal` decimal(25, 4) NULL AFTER `item_discount`,
MODIFY COLUMN `audit_created_by` int(11) NULL AFTER `audit_id`,
MODIFY COLUMN `audit_record_date` timestamp(0) NULL DEFAULT CURRENT_TIMESTAMP AFTER `audit_created_by`,
MODIFY COLUMN `audit_type` varchar(55) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `audit_record_date`;



ALTER TABLE `erp_purchases`
MODIFY COLUMN `biller_id` int(11) NULL AFTER `id`,
MODIFY COLUMN `reference_no` varchar(55) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `biller_id`,
MODIFY COLUMN `date` timestamp(0) NULL DEFAULT CURRENT_TIMESTAMP AFTER `reference_no`,
MODIFY COLUMN `supplier_id` int(11) NULL AFTER `date`,
MODIFY COLUMN `supplier` varchar(55) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `supplier_id`;



ALTER TABLE `erp_products`
MODIFY COLUMN `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `id`,
MODIFY COLUMN `name` char(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `name_kh`,
MODIFY COLUMN `price` decimal(25, 8) NULL AFTER `cost`,
MODIFY COLUMN `category_id` int(11) NULL AFTER `image`,
MODIFY COLUMN `barcode_symbology` varchar(55) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'code128' AFTER `warehouse`,
MODIFY COLUMN `type` varchar(55) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'standard' AFTER `tax_method`,
MODIFY COLUMN `inactived` tinyint(1) UNSIGNED NULL DEFAULT 0 AFTER `currentcy_code`;


ALTER TABLE `erp_product_variants`
MODIFY COLUMN `product_id` int(11) NULL AFTER `id`,
MODIFY COLUMN `name` varchar(55) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `product_id`;


ALTER TABLE `erp_order_ref`
MODIFY COLUMN `biller_id` int(11) NULL AFTER `ref_id`,
MODIFY COLUMN `date` date NULL AFTER `biller_id`,
MODIFY COLUMN `so` int(11) NULL DEFAULT 1 COMMENT 'sale order' AFTER `date`,
MODIFY COLUMN `qu` int(11) NULL DEFAULT 1 COMMENT 'quote' AFTER `so`,
MODIFY COLUMN `po` int(11) NULL DEFAULT 1 COMMENT 'purchase order' AFTER `qu`,
MODIFY COLUMN `to` int(11) NULL DEFAULT 1 COMMENT 'transfer to' AFTER `po`,
MODIFY COLUMN `pos` int(11) NULL DEFAULT 1 COMMENT 'pos' AFTER `to`,
MODIFY COLUMN `do` int(11) NULL DEFAULT 1 COMMENT 'delivery order' AFTER `pos`,
MODIFY COLUMN `pay` int(11) NULL DEFAULT 1 COMMENT 'expense payment' AFTER `do`,
MODIFY COLUMN `re` int(11) NULL DEFAULT 1 COMMENT 'sale return' AFTER `pay`,
MODIFY COLUMN `ex` int(11) NULL DEFAULT 1 COMMENT 'expense' AFTER `re`,
MODIFY COLUMN `sp` int(11) NULL DEFAULT 1 COMMENT 'sale payement' AFTER `ex`,
MODIFY COLUMN `pp` int(11) NULL DEFAULT 1 COMMENT 'purchase payment' AFTER `sp`,
MODIFY COLUMN `sl` int(11) NULL DEFAULT 1 COMMENT 'sale loan' AFTER `pp`,
MODIFY COLUMN `tr` int(11) NULL DEFAULT 1 COMMENT 'transfer' AFTER `sl`,
MODIFY COLUMN `rep` int(11) NULL DEFAULT 1 COMMENT 'purchase return' AFTER `tr`,
MODIFY COLUMN `con` int(11) NULL DEFAULT 1 COMMENT 'convert product' AFTER `rep`,
MODIFY COLUMN `pj` int(11) NULL DEFAULT 1 COMMENT 'prouduct job' AFTER `con`,
MODIFY COLUMN `sd` int(11) NULL DEFAULT 1 AFTER `pj`,
MODIFY COLUMN `es` int(11) NULL DEFAULT 1 AFTER `sd`,
MODIFY COLUMN `esr` int(11) NULL DEFAULT 1 AFTER `es`,
MODIFY COLUMN `sao` int(11) NULL DEFAULT 1 AFTER `esr`,
MODIFY COLUMN `poa` int(11) NULL DEFAULT 1 AFTER `sao`,
MODIFY COLUMN `pq` int(11) NULL DEFAULT 1 AFTER `poa`,
MODIFY COLUMN `jr` int(11) NULL AFTER `pq`,
MODIFY COLUMN `qa` int(11) NULL DEFAULT 1 AFTER `jr`,
MODIFY COLUMN `tx` int(11) UNSIGNED NULL DEFAULT 1 COMMENT 'TAX' AFTER `adc`,
MODIFY COLUMN `pro` int(11) NULL AFTER `tx`,
MODIFY COLUMN `cus` int(11) NULL AFTER `pro`,
MODIFY COLUMN `sup` int(11) NULL AFTER `cus`,
MODIFY COLUMN `emp` int(11) NULL AFTER `sup`;


ALTER TABLE `erp_sale_order_items`
MODIFY COLUMN `sale_order_id` int(11) UNSIGNED NULL AFTER `digital_id`,
MODIFY COLUMN `product_id` int(11) UNSIGNED NULL AFTER `sale_order_id`,
MODIFY COLUMN `product_code` varchar(55) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `product_id`,
MODIFY COLUMN `product_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `product_code`,
MODIFY COLUMN `net_unit_price` decimal(25, 4) NULL AFTER `option_id`,
MODIFY COLUMN `quantity_received` decimal(15, 4) NULL AFTER `unit_price`,
MODIFY COLUMN `quantity` decimal(15, 4) NULL AFTER `quantity_received`,
MODIFY COLUMN `subtotal` decimal(25, 4) NULL AFTER `item_discount`;


ALTER TABLE `erp_delivery_items`
MODIFY COLUMN `delivery_id` int(11) NULL AFTER `id`,
MODIFY COLUMN `do_reference_no` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `delivery_id`,
MODIFY COLUMN `product_id` int(11) UNSIGNED NULL AFTER `do_reference_no`,
MODIFY COLUMN `product_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `product_type`,
MODIFY COLUMN `category_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `option_id`,
MODIFY COLUMN `quantity_received` decimal(15, 4) NULL AFTER `begining_balance`,
MODIFY COLUMN `updated_count` int(4) UNSIGNED ZEROFILL NULL AFTER `expiry`,
MODIFY COLUMN `cost` decimal(8, 6) UNSIGNED ZEROFILL NULL AFTER `updated_count`;


ALTER TABLE `erp_product_prices`
MODIFY COLUMN `product_id` int(11) NULL AFTER `id`,
MODIFY COLUMN `price_group_id` int(11) NULL AFTER `unit_type`,
MODIFY COLUMN `price` decimal(25, 4) NULL AFTER `price_group_id`;

ALTER TABLE `erp_gl_charts`
MODIFY COLUMN `lineage` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `acc_level`,
MODIFY COLUMN `value` decimal(55, 2) NULL DEFAULT 0.00 AFTER `bank`;

ALTER TABLE `erp_loans`
MODIFY COLUMN `period` smallint(6) NULL AFTER `id`,
MODIFY COLUMN `dateline` date NULL AFTER `period`,
MODIFY COLUMN `reference_no` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `sale_id`,
MODIFY COLUMN `customer_id` int(11) NULL AFTER `reference_no`,
MODIFY COLUMN `payment` decimal(25, 10) NULL AFTER `rated`,
MODIFY COLUMN `principle` decimal(25, 10) NULL AFTER `payment`,
MODIFY COLUMN `interest` decimal(25, 10) NULL AFTER `principle`,
MODIFY COLUMN `paid_amount` decimal(25, 4) NULL AFTER `paid_by`,
MODIFY COLUMN `updated_by` varchar(55) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `biller_id`;


ALTER TABLE `erp_purchases_audit`
MODIFY COLUMN `id` int(11) NULL FIRST,
MODIFY COLUMN `biller_id` int(11) NULL AFTER `id`,
MODIFY COLUMN `reference_no` varchar(55) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `biller_id`,
MODIFY COLUMN `date` timestamp(0) NULL DEFAULT CURRENT_TIMESTAMP AFTER `reference_no`,
MODIFY COLUMN `supplier_id` int(11) NULL AFTER `date`,
MODIFY COLUMN `supplier` varchar(55) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `supplier_id`,
MODIFY COLUMN `warehouse_id` int(11) NULL AFTER `supplier`,
MODIFY COLUMN `note` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `warehouse_id`,
MODIFY COLUMN `grand_total` decimal(25, 4) NULL AFTER `shipping`,
MODIFY COLUMN `paid` decimal(25, 4) NULL DEFAULT 0.0000 AFTER `grand_total`,
MODIFY COLUMN `surcharge` decimal(25, 4) NULL DEFAULT 0.0000 AFTER `return_id`,
MODIFY COLUMN `reference_no_tax` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `suspend_note`,
MODIFY COLUMN `audit_created_by` int(11) NULL AFTER `audit_id`,
MODIFY COLUMN `audit_record_date` timestamp(0) NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP(0) AFTER `audit_created_by`,
MODIFY COLUMN `audit_type` varchar(55) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `audit_record_date`;

ALTER TABLE `erp_sale_items_audit`
MODIFY COLUMN `id` int(11) NULL FIRST,
MODIFY COLUMN `sale_id` int(11) UNSIGNED NULL AFTER `id`,
MODIFY COLUMN `product_id` int(11) UNSIGNED NULL AFTER `category_id`,
MODIFY COLUMN `product_code` varchar(55) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `product_id`,
MODIFY COLUMN `product_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `product_code`,
MODIFY COLUMN `net_unit_price` decimal(25, 4) NULL AFTER `option_id`,
MODIFY COLUMN `quantity_received` decimal(15, 4) NULL AFTER `unit_price`,
MODIFY COLUMN `quantity` decimal(15, 4) NULL AFTER `quantity_received`,
MODIFY COLUMN `subtotal` decimal(25, 4) NULL AFTER `item_discount`,
MODIFY COLUMN `audit_created_by` int(11) NULL AFTER `audit_id`,
MODIFY COLUMN `audit_record_date` timestamp(0) NULL DEFAULT CURRENT_TIMESTAMP AFTER `audit_created_by`,
MODIFY COLUMN `audit_type` varchar(55) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `audit_record_date`;

ALTER TABLE `erp_purchase_order_items`
MODIFY COLUMN `product_id` int(11) NULL AFTER `transfer_id`,
MODIFY COLUMN `product_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `product_id`,
MODIFY COLUMN `product_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `product_code`,
MODIFY COLUMN `net_unit_cost` decimal(25, 8) NULL AFTER `option_id`,
MODIFY COLUMN `quantity` decimal(15, 8) NULL AFTER `net_unit_cost`,
MODIFY COLUMN `quantity_po` decimal(15, 8) NULL AFTER `quantity`,
MODIFY COLUMN `warehouse_id` int(11) NULL AFTER `quantity_po`,
MODIFY COLUMN `subtotal` decimal(25, 8) NULL AFTER `expiry`,
MODIFY COLUMN `date` date NULL AFTER `quantity_balance`,
MODIFY COLUMN `status` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `date`,
MODIFY COLUMN `quantity_received` decimal(15, 8) UNSIGNED NULL DEFAULT 0.00000000 AFTER `real_unit_cost`;

ALTER TABLE `erp_sale_order`
MODIFY COLUMN `date` timestamp(0) NULL DEFAULT CURRENT_TIMESTAMP AFTER `id`,
MODIFY COLUMN `reference_no` varchar(55) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `quote_id`,
MODIFY COLUMN `customer_id` int(11) NULL AFTER `reference_no`,
MODIFY COLUMN `customer` varchar(55) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `customer_id`,
MODIFY COLUMN `biller_id` int(11) NULL AFTER `group_areas_id`,
MODIFY COLUMN `biller` varchar(55) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `biller_id`,
MODIFY COLUMN `total` decimal(25, 4) NULL AFTER `staff_note`,
MODIFY COLUMN `grand_total` decimal(25, 4) NULL AFTER `shipping`,
MODIFY COLUMN `total_cost` decimal(25, 4) NULL AFTER `total_items`,
MODIFY COLUMN `pos` tinyint(1) NULL DEFAULT 0 AFTER `total_cost`,
MODIFY COLUMN `surcharge` decimal(25, 4) NULL DEFAULT 0.0000 AFTER `return_id`,
MODIFY COLUMN `reference_no_tax` varchar(55) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `saleman_by`;

ALTER TABLE `erp_return_items`
MODIFY COLUMN `sale_id` int(11) UNSIGNED NULL AFTER `id`,
MODIFY COLUMN `return_id` int(11) UNSIGNED NULL AFTER `sale_id`,
MODIFY COLUMN `product_id` int(11) UNSIGNED NULL AFTER `category_id`,
MODIFY COLUMN `product_code` varchar(55) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `product_id`,
MODIFY COLUMN `product_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `product_code`,
MODIFY COLUMN `net_unit_price` decimal(25, 4) NULL AFTER `option_id`,
MODIFY COLUMN `subtotal` decimal(25, 4) NULL AFTER `item_discount`;

ALTER TABLE `erp_quote_items`
MODIFY COLUMN `quote_id` int(11) NULL AFTER `id`,
MODIFY COLUMN `product_id` int(11) NULL AFTER `digital_id`,
MODIFY COLUMN `product_code` varchar(55) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `product_id`,
MODIFY COLUMN `product_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `product_code`,
MODIFY COLUMN `net_unit_price` decimal(25, 4) NULL AFTER `option_id`,
MODIFY COLUMN `quantity` decimal(15, 4) NULL AFTER `unit_price`,
MODIFY COLUMN `quantity_received` decimal(25, 4) UNSIGNED ZEROFILL NULL AFTER `quantity`,
MODIFY COLUMN `subtotal` decimal(25, 4) NULL AFTER `item_discount`;

ALTER TABLE `erp_costing`
MODIFY COLUMN `date` date NULL AFTER `id`,
MODIFY COLUMN `sale_item_id` int(11) NULL AFTER `product_code`,
MODIFY COLUMN `quantity` decimal(15, 4) NULL AFTER `purchase_item_id`,
MODIFY COLUMN `sale_net_unit_price` decimal(25, 4) NULL AFTER `purchase_unit_cost`,
MODIFY COLUMN `sale_unit_price` decimal(25, 4) NULL AFTER `sale_net_unit_price`;


ALTER TABLE `erp_order_loans`
MODIFY COLUMN `period` smallint(6) NULL AFTER `id`,
MODIFY COLUMN `dateline` date NULL AFTER `period`,
MODIFY COLUMN `customer_id` int(11) NULL AFTER `sale_id`,
MODIFY COLUMN `payment` decimal(25, 10) NULL AFTER `rated`,
MODIFY COLUMN `principle` decimal(25, 10) NULL AFTER `payment`,
MODIFY COLUMN `interest` decimal(25, 10) NULL AFTER `principle`;

ALTER TABLE `erp_purchases_order_audit`
MODIFY COLUMN `id` int(11) NULL FIRST,
MODIFY COLUMN `biller_id` int(11) NULL AFTER `id`,
MODIFY COLUMN `reference_no` varchar(55) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `biller_id`,
MODIFY COLUMN `date` timestamp(0) NULL DEFAULT CURRENT_TIMESTAMP AFTER `purchase_ref`,
MODIFY COLUMN `supplier_id` int(11) NULL AFTER `date`,
MODIFY COLUMN `supplier` varchar(55) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `supplier_id`,
MODIFY COLUMN `warehouse_id` int(11) NULL AFTER `supplier`,
MODIFY COLUMN `note` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `warehouse_id`,
MODIFY COLUMN `grand_total` decimal(25, 4) NULL AFTER `shipping`,
MODIFY COLUMN `paid` decimal(25, 4) NULL DEFAULT 0.0000 AFTER `grand_total`,
MODIFY COLUMN `surcharge` decimal(25, 4) NULL DEFAULT 0.0000 AFTER `return_id`,
MODIFY COLUMN `reference_no_tax` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `suspend_note`,
MODIFY COLUMN `audit_created_by` int(11) NULL AFTER `audit_id`,
MODIFY COLUMN `audit_record_date` timestamp(0) NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP(0) AFTER `audit_created_by`,
MODIFY COLUMN `audit_type` varchar(55) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `audit_record_date`;


ALTER TABLE `erp_deliveries`
MODIFY COLUMN `date` timestamp(0) NULL DEFAULT CURRENT_TIMESTAMP AFTER `id`,
MODIFY COLUMN `sale_id` int(11) NULL AFTER `date`,
MODIFY COLUMN `do_reference_no` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `sale_id`,
MODIFY COLUMN `sale_reference_no` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `do_reference_no`,
MODIFY COLUMN `address` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `customer`,
MODIFY COLUMN `updated_count` int(4) UNSIGNED ZEROFILL NULL AFTER `updated_at`;

ALTER TABLE `erp_companies`
MODIFY COLUMN `group_name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `group_id`;

ALTER TABLE `erp_enter_using_stock`
MODIFY COLUMN `address_id` int(11) NULL AFTER `plan_id`;

ALTER TABLE `erp_return_sales`
MODIFY COLUMN `date` timestamp(0) NULL DEFAULT CURRENT_TIMESTAMP AFTER `sale_id`,
MODIFY COLUMN `reference_no` varchar(55) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `date`,
MODIFY COLUMN `customer_id` int(11) NULL AFTER `reference_no`,
MODIFY COLUMN `customer` varchar(55) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `customer_id`,
MODIFY COLUMN `biller_id` int(11) NULL AFTER `customer`,
MODIFY COLUMN `biller` varchar(55) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `biller_id`,
MODIFY COLUMN `total` decimal(25, 4) NULL AFTER `total_cost`,
MODIFY COLUMN `grand_total` decimal(25, 4) NULL AFTER `surcharge`;

ALTER TABLE `erp_purchase_request_items`
MODIFY COLUMN `delivery_id` int(11) NULL AFTER `id`,
MODIFY COLUMN `product_id` int(11) NULL AFTER `transfer_id`,
MODIFY COLUMN `product_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `product_id`,
MODIFY COLUMN `product_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `product_code`,
MODIFY COLUMN `net_unit_cost` decimal(25, 8) NULL AFTER `product_type`,
MODIFY COLUMN `quantity` decimal(15, 8) NULL AFTER `net_unit_cost`,
MODIFY COLUMN `warehouse_id` int(11) NULL AFTER `quantity`,
MODIFY COLUMN `subtotal` decimal(25, 8) NULL AFTER `expiry`,
MODIFY COLUMN `date` date NULL AFTER `quantity_balance`,
MODIFY COLUMN `status` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `date`;

ALTER TABLE `erp_pos_register`
MODIFY COLUMN `date` timestamp(0) NULL DEFAULT CURRENT_TIMESTAMP AFTER `id`,
MODIFY COLUMN `user_id` int(11) NULL AFTER `date`,
MODIFY COLUMN `cash_in_hand` decimal(25, 4) NULL AFTER `user_id`,
MODIFY COLUMN `status` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `cash_in_hand`;

ALTER TABLE `erp_purchases_order`
MODIFY COLUMN `biller_id` int(11) NULL AFTER `id`,
MODIFY COLUMN `reference_no` varchar(55) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `biller_id`,
MODIFY COLUMN `date` timestamp(0) NULL DEFAULT CURRENT_TIMESTAMP AFTER `purchase_ref`,
MODIFY COLUMN `supplier_id` int(11) NULL AFTER `date`,
MODIFY COLUMN `supplier` varchar(55) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `supplier_id`,
MODIFY COLUMN `warehouse_id` int(11) NULL AFTER `supplier`,
MODIFY COLUMN `note` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `warehouse_id`,
MODIFY COLUMN `grand_total` decimal(25, 4) NULL AFTER `shipping`,
MODIFY COLUMN `paid` decimal(25, 4) NULL DEFAULT 0.0000 AFTER `grand_total`,
MODIFY COLUMN `surcharge` decimal(25, 4) NULL DEFAULT 0.0000 AFTER `return_id`,
MODIFY COLUMN `reference_no_tax` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `suspend_note`;

ALTER TABLE `erp_transfer_items`
MODIFY COLUMN `transfer_id` int(11) NULL AFTER `id`,
MODIFY COLUMN `product_id` int(11) NULL AFTER `transfer_id`,
MODIFY COLUMN `product_code` varchar(55) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `product_id`,
MODIFY COLUMN `product_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `product_code`,
MODIFY COLUMN `quantity` decimal(15, 4) NULL AFTER `expiry`,
MODIFY COLUMN `quantity_balance` decimal(15, 4) NULL AFTER `subtotal`;

ALTER TABLE `erp_deposits`
MODIFY COLUMN `date` timestamp(0) NULL DEFAULT CURRENT_TIMESTAMP AFTER `id`,
MODIFY COLUMN `company_id` int(11) NULL AFTER `date`,
MODIFY COLUMN `amount` decimal(25, 4) NULL AFTER `company_id`,
MODIFY COLUMN `created_by` int(11) NULL AFTER `note`,
MODIFY COLUMN `opening` tinyint(1) UNSIGNED ZEROFILL NULL DEFAULT 0 AFTER `deposit_id`;

ALTER TABLE `erp_quotes`
MODIFY COLUMN `date` timestamp(0) NULL DEFAULT CURRENT_TIMESTAMP AFTER `id`,
MODIFY COLUMN `reference_no` varchar(55) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `date`,
MODIFY COLUMN `customer_id` int(11) NULL AFTER `reference_no`,
MODIFY COLUMN `customer` varchar(55) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `customer_id`,
MODIFY COLUMN `biller_id` int(11) NULL AFTER `warehouse_id`,
MODIFY COLUMN `biller` varchar(55) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `biller_id`,
MODIFY COLUMN `total` decimal(25, 4) NULL AFTER `internal_note`,
MODIFY COLUMN `grand_total` decimal(25, 4) NULL AFTER `shipping`;

ALTER TABLE `erp_digital_items`
MODIFY COLUMN `digital_pro_id` int(11) NULL AFTER `id`,
MODIFY COLUMN `product_id` int(11) NULL AFTER `digital_pro_id`;

ALTER TABLE `erp_stock_counts`
MODIFY COLUMN `date` timestamp(0) NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP(0) AFTER `id`,
MODIFY COLUMN `reference_no` varchar(55) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `date`,
MODIFY COLUMN `warehouse_id` int(11) NULL AFTER `reference_no`,
MODIFY COLUMN `type` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `warehouse_id`,
MODIFY COLUMN `initial_file` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `type`,
MODIFY COLUMN `created_by` int(11) NULL AFTER `missing`;

ALTER TABLE `erp_transfers`
MODIFY COLUMN `transfer_no` varchar(55) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `id`,
MODIFY COLUMN `date` timestamp(0) NULL DEFAULT CURRENT_TIMESTAMP AFTER `transfer_no`,
MODIFY COLUMN `from_warehouse_id` int(11) NULL AFTER `date`,
MODIFY COLUMN `from_warehouse_code` varchar(55) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `from_warehouse_id`,
MODIFY COLUMN `from_warehouse_name` varchar(55) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `from_warehouse_code`,
MODIFY COLUMN `to_warehouse_id` int(11) NULL AFTER `from_warehouse_name`,
MODIFY COLUMN `to_warehouse_code` varchar(55) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `to_warehouse_id`,
MODIFY COLUMN `to_warehouse_name` varchar(55) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `to_warehouse_code`,
MODIFY COLUMN `status` varchar(55) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'pending' AFTER `employee_id`;

ALTER TABLE `erp_units`
MODIFY COLUMN `code` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `id`,
MODIFY COLUMN `name` varchar(55) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `code`;

ALTER TABLE `erp_users`
MODIFY COLUMN `ip_address` varbinary(45) NULL AFTER `last_ip_address`,
MODIFY COLUMN `username` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `ip_address`,
MODIFY COLUMN `password` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `username`,
MODIFY COLUMN `email` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `salt`,
MODIFY COLUMN `created_on` int(11) UNSIGNED NULL AFTER `remember_code`,
MODIFY COLUMN `group_id` int(10) UNSIGNED NULL AFTER `gender`,
MODIFY COLUMN `race_kh` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `nationality_kh`,
MODIFY COLUMN `advance_amount` decimal(25, 8) UNSIGNED ZEROFILL NULL AFTER `user_type`;


ALTER TABLE `erp_variants`
MODIFY COLUMN `name` varchar(55) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `id`;

ALTER TABLE `erp_categories`
MODIFY COLUMN `code` varchar(55) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `categories_note_id`,
MODIFY COLUMN `name` varchar(55) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `code`;

ALTER TABLE `erp_combo_items`
MODIFY COLUMN `product_id` int(11) NULL AFTER `id`,
MODIFY COLUMN `item_code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `product_id`,
MODIFY COLUMN `quantity` decimal(12, 4) NULL AFTER `item_code`;

ALTER TABLE `erp_purchases_request`
MODIFY COLUMN `biller_id` int(11) NULL AFTER `id`,
MODIFY COLUMN `reference_no` varchar(55) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `biller_id`,
MODIFY COLUMN `date` timestamp(0) NULL DEFAULT CURRENT_TIMESTAMP AFTER `reference_no`,
MODIFY COLUMN `supplier_id` int(11) NULL AFTER `date`,
MODIFY COLUMN `supplier` varchar(55) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `supplier_id`,
MODIFY COLUMN `warehouse_id` int(11) NULL AFTER `supplier`,
MODIFY COLUMN `note` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `warehouse_id`,
MODIFY COLUMN `grand_total` decimal(25, 4) NULL AFTER `shipping`,
MODIFY COLUMN `paid` decimal(25, 4) NULL DEFAULT 0.0000 AFTER `grand_total`,
MODIFY COLUMN `surcharge` decimal(25, 4) NULL DEFAULT 0.0000 AFTER `return_id`,
MODIFY COLUMN `reference_no_tax` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `suspend_note`;

ALTER TABLE `erp_gift_cards`
MODIFY COLUMN `date` timestamp(0) NULL DEFAULT CURRENT_TIMESTAMP AFTER `id`,
MODIFY COLUMN `card_no` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `date`,
MODIFY COLUMN `value` decimal(25, 4) NULL AFTER `card_no`,
MODIFY COLUMN `balance` decimal(25, 4) NULL AFTER `customer`,
MODIFY COLUMN `created_by` varchar(55) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `expiry`;

ALTER TABLE `erp_convert_items`
MODIFY COLUMN `convert_id` int(11) NULL AFTER `id`,
MODIFY COLUMN `product_id` int(11) NULL AFTER `convert_id`,
MODIFY COLUMN `product_code` varchar(55) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `option_id`,
MODIFY COLUMN `product_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `product_code`,
MODIFY COLUMN `quantity` decimal(25, 4) NULL AFTER `product_name`,
MODIFY COLUMN `cost` decimal(25, 4) NULL DEFAULT 0.0000 AFTER `quantity`,
MODIFY COLUMN `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `cost`;


ALTER TABLE `erp_groups`
MODIFY COLUMN `name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `id`,
MODIFY COLUMN `description` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `name`;

ALTER TABLE `erp_categories_group`
MODIFY COLUMN `sub_cate` int(11) NULL DEFAULT 0 AFTER `percent`;

ALTER TABLE `erp_date_format`
MODIFY COLUMN `js` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `id`,
MODIFY COLUMN `php` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `js`;

ALTER TABLE `erp_convert`
MODIFY COLUMN `date` timestamp(0) NULL DEFAULT CURRENT_TIMESTAMP AFTER `reference_no`;

ALTER TABLE `erp_price_groups`
MODIFY COLUMN `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `id`;

ALTER TABLE `erp_gift_cards_audit`
MODIFY COLUMN `id` int(11) NULL FIRST,
MODIFY COLUMN `date` timestamp(0) NULL DEFAULT CURRENT_TIMESTAMP AFTER `id`,
MODIFY COLUMN `card_no` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `date`,
MODIFY COLUMN `value` decimal(25, 4) NULL AFTER `card_no`,
MODIFY COLUMN `balance` decimal(25, 4) NULL AFTER `customer`,
MODIFY COLUMN `created_by` varchar(55) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `expiry`,
MODIFY COLUMN `audit_created_by` int(11) NULL AFTER `audit_id`,
MODIFY COLUMN `audit_record_date` timestamp(0) NULL DEFAULT CURRENT_TIMESTAMP AFTER `audit_created_by`,
MODIFY COLUMN `audit_type` varchar(55) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `audit_record_date`;


ALTER TABLE `erp_permissions`
MODIFY COLUMN `group_id` int(11) NULL AFTER `id`;

ALTER TABLE `erp_bom_items`
MODIFY COLUMN `bom_id` int(11) NULL AFTER `id`,
MODIFY COLUMN `product_id` int(11) NULL AFTER `bom_id`,
MODIFY COLUMN `product_code` varchar(55) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `option_id`,
MODIFY COLUMN `product_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `product_code`,
MODIFY COLUMN `quantity` decimal(25, 4) NULL AFTER `product_name`,
MODIFY COLUMN `cost` decimal(25, 4) NULL DEFAULT 0.0000 AFTER `quantity`,
MODIFY COLUMN `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `cost`;

ALTER TABLE `erp_customer_groups`
MODIFY COLUMN `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `id`,
MODIFY COLUMN `percent` int(11) NULL AFTER `name`,
MODIFY COLUMN `makeup_cost` tinyint(3) NULL DEFAULT 0 AFTER `percent`;

ALTER TABLE `erp_notifications`
MODIFY COLUMN `comment` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `id`,
MODIFY COLUMN `date` timestamp(0) NULL DEFAULT CURRENT_TIMESTAMP AFTER `comment`,
MODIFY COLUMN `scope` tinyint(1) NULL DEFAULT 3 AFTER `till_date`;

ALTER TABLE `erp_warehouses`
MODIFY COLUMN `code` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `id`,
MODIFY COLUMN `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `code`,
MODIFY COLUMN `address` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `name`;

ALTER TABLE `erp_calendar`
MODIFY COLUMN `title` varchar(55) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `id`,
MODIFY COLUMN `start` datetime(0) NULL AFTER `user_id`,
MODIFY COLUMN `color` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `description`;

ALTER TABLE `erp_currencies`
MODIFY COLUMN `code` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `id`,
MODIFY COLUMN `name` varchar(55) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `code`,
MODIFY COLUMN `rate` decimal(12, 4) NULL AFTER `in_out`,
MODIFY COLUMN `auto_update` tinyint(1) NULL DEFAULT 0 AFTER `rate`;

ALTER TABLE `erp_expenses`
MODIFY COLUMN `date` timestamp(0) NULL DEFAULT CURRENT_TIMESTAMP AFTER `id`,
MODIFY COLUMN `reference` varchar(55) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `date`,
MODIFY COLUMN `amount` decimal(25, 6) NULL AFTER `reference`,
MODIFY COLUMN `note` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `amount`;

ALTER TABLE `erp_salesman_assign`
MODIFY COLUMN `totalMoney` double NULL AFTER `getMoneyBy`,
MODIFY COLUMN `status` int(1) NULL DEFAULT 0 AFTER `isCompleted`,
MODIFY COLUMN `user_clear_amount` decimal(25, 8) NULL AFTER `total_balance`,
MODIFY COLUMN `tmpClear` decimal(25, 8) NULL AFTER `user_clear_amount`;

ALTER TABLE `erp_subcategories`
MODIFY COLUMN `category_id` int(11) NULL AFTER `id`,
MODIFY COLUMN `code` varchar(55) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `category_id`,
MODIFY COLUMN `name` varchar(55) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `code`;

ALTER TABLE `erp_suspended_bills`
MODIFY COLUMN `date` timestamp(0) NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP(0) AFTER `id`,
MODIFY COLUMN `customer_id` int(11) NULL AFTER `end_date`,
MODIFY COLUMN `count` int(11) NULL AFTER `customer`,
MODIFY COLUMN `total` decimal(25, 4) NULL AFTER `order_tax_id`,
MODIFY COLUMN `created_by` int(11) NULL AFTER `warehouse_id`,
MODIFY COLUMN `suspend_id` int(11) NULL DEFAULT 0 AFTER `created_by`;

ALTER TABLE `erp_suspended_items`
MODIFY COLUMN `suspend_id` int(11) UNSIGNED NULL AFTER `id`,
MODIFY COLUMN `product_id` int(11) UNSIGNED NULL AFTER `suspend_id`,
MODIFY COLUMN `product_code` varchar(55) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `digital_id`,
MODIFY COLUMN `product_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `product_code`,
MODIFY COLUMN `net_unit_price` decimal(25, 4) NULL AFTER `product_name`,
MODIFY COLUMN `unit_cost` decimal(25, 4) NULL AFTER `net_unit_price`,
MODIFY COLUMN `unit_price` decimal(25, 4) NULL AFTER `unit_cost`,
MODIFY COLUMN `subtotal` decimal(25, 4) NULL AFTER `item_discount`;

ALTER TABLE `erp_tax_rates`
MODIFY COLUMN `name` varchar(55) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `id`,
MODIFY COLUMN `rate` decimal(12, 4) NULL AFTER `code`,
MODIFY COLUMN `type` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `rate`;

ALTER TABLE `erp_categories_note`
MODIFY COLUMN `description` varchar(55) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `id`;

ALTER TABLE `erp_product_note`
MODIFY COLUMN `code` varchar(55) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `id`,
MODIFY COLUMN `name` varchar(55) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `code`,
MODIFY COLUMN `price` decimal(25, 4) NULL AFTER `name`;

ALTER TABLE `erp_product_photos`
MODIFY COLUMN `product_id` int(11) NULL AFTER `id`,
MODIFY COLUMN `photo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `product_id`;

ALTER TABLE `erp_public_charge_detail`
MODIFY COLUMN `pub_id` int(11) NULL AFTER `amount`;

ALTER TABLE `erp_login_attempts`
MODIFY COLUMN `ip_address` varbinary(16) NULL AFTER `id`,
MODIFY COLUMN `login` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `ip_address`;

ALTER TABLE `erp_brands`
MODIFY COLUMN `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `code`;