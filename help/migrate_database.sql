ALTER TABLE `erp_users`
MODIFY COLUMN `biller_id`  varchar(500) NULL DEFAULT NULL AFTER `warehouse_id`;


CREATE TABLE `erp_stock_trans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `purchase_item_id` int(10) unsigned zerofill DEFAULT '0000000000',
  `tran_date` date DEFAULT NULL,
  `product_id` int(11) NOT NULL,
  `warehouse_id` int(11) NOT NULL,
  `option_id` int(11) DEFAULT NULL,
  `quantity` decimal(8,2) unsigned zerofill DEFAULT '000000.00',
  `quantity_balance_unit` decimal(8,2) DEFAULT NULL,
  `tran_type` varchar(100) NOT NULL,
  `tran_id` int(11) NOT NULL,
  `tran_ref_type` varchar(100) DEFAULT NULL,
  `tran_ref_id` int(11) DEFAULT NULL,
  `manufacture_cost` decimal(25,8) unsigned zerofill NOT NULL,
  `freight_cost` decimal(25,8) unsigned zerofill NOT NULL,
  `raw_cost` decimal(25,8) unsigned zerofill NOT NULL,
  `labor_cost` decimal(25,8) unsigned zerofill NOT NULL,
  `overhead_cost` decimal(25,8) unsigned zerofill NOT NULL,
  `total_cost` decimal(25,8) unsigned zerofill NOT NULL,
  `is_close` tinyint(1) unsigned zerofill NOT NULL,
  `close_date` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=utf8;

