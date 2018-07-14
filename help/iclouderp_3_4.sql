/*
Navicat MySQL Data Transfer

Source Server         : CloudNET
Source Server Version : 50626
Source Host           : 127.0.0.1:3306
Source Database       : iclouderp_3_4

Target Server Type    : MYSQL
Target Server Version : 50626
File Encoding         : 65001

Date: 2017-08-25 19:37:26
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for erp_account_settings
-- ----------------------------
DROP TABLE IF EXISTS `erp_account_settings`;
CREATE TABLE `erp_account_settings` (
  `id` int(1) NOT NULL,
  `biller_id` int(11) NOT NULL DEFAULT '0',
  `default_open_balance` varchar(20) DEFAULT NULL,
  `default_sale` varchar(20) DEFAULT 'yes',
  `default_sale_discount` varchar(20) DEFAULT NULL,
  `default_sale_tax` varchar(20) DEFAULT NULL,
  `default_sale_freight` varchar(20) DEFAULT NULL,
  `default_sale_deposit` varchar(20) DEFAULT NULL,
  `default_receivable` varchar(20) DEFAULT NULL,
  `default_purchase` varchar(20) DEFAULT NULL,
  `default_purchase_discount` varchar(20) DEFAULT NULL,
  `default_purchase_tax` varchar(20) DEFAULT NULL,
  `default_purchase_freight` varchar(20) DEFAULT NULL,
  `default_purchase_deposit` varchar(20) DEFAULT NULL,
  `default_payable` varchar(20) DEFAULT NULL,
  `default_stock` varchar(20) DEFAULT NULL,
  `default_stock_adjust` varchar(20) DEFAULT NULL,
  `default_cost` varchar(20) DEFAULT NULL,
  `default_payroll` varchar(20) DEFAULT NULL,
  `default_cash` varchar(20) DEFAULT NULL,
  `default_credit_card` varchar(20) DEFAULT NULL,
  `default_gift_card` varchar(20) DEFAULT NULL,
  `default_cheque` varchar(20) DEFAULT NULL,
  `default_loan` varchar(20) DEFAULT NULL,
  `default_retained_earnings` varchar(20) DEFAULT NULL,
  `default_cost_variant` varchar(20) DEFAULT NULL,
  `default_interest_income` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`,`biller_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_account_settings
-- ----------------------------
INSERT INTO `erp_account_settings` VALUES ('1', '1', '300300', '410101', '410102', '201407', '601206', '201208', '100200', '100430', '500106', '100441', '601206', '100420', '201100', '100430', '500107', '500101', '201201', '100102', '100105', '201208', '100104', '100501', '300200', '500108', null);

-- ----------------------------
-- Table structure for erp_adjustments
-- ----------------------------
DROP TABLE IF EXISTS `erp_adjustments`;
CREATE TABLE `erp_adjustments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `reference_no` varchar(55) NOT NULL,
  `warehouse_id` int(11) NOT NULL,
  `note` text,
  `attachment` varchar(55) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `count_id` int(11) DEFAULT NULL,
  `biller_id` int(11) DEFAULT NULL,
  `total_cost` decimal(25,4) DEFAULT '0.0000',
  PRIMARY KEY (`id`),
  KEY `warehouse_id` (`warehouse_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_adjustments
-- ----------------------------
INSERT INTO `erp_adjustments` VALUES ('1', '2017-08-25 18:51:00', '1708/00001', '1', '', null, '1', null, null, null, '1', '11.5389');
INSERT INTO `erp_adjustments` VALUES ('2', '2017-08-25 18:53:00', '1708/00002', '1', '', null, '1', null, null, null, '1', '12.8210');

-- ----------------------------
-- Table structure for erp_adjustment_items
-- ----------------------------
DROP TABLE IF EXISTS `erp_adjustment_items`;
CREATE TABLE `erp_adjustment_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `adjust_id` int(11) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `product_id` int(11) NOT NULL,
  `option_id` int(11) DEFAULT NULL,
  `quantity` decimal(15,4) NOT NULL,
  `warehouse_id` int(11) NOT NULL,
  `note` varchar(1000) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `type` varchar(20) NOT NULL,
  `cost` decimal(19,4) DEFAULT '0.0000',
  `total_cost` decimal(19,4) DEFAULT '0.0000',
  `biller_id` int(11) DEFAULT '0',
  `count_id` int(11) DEFAULT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `serial_no` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_adjustment_items
-- ----------------------------
INSERT INTO `erp_adjustment_items` VALUES ('1', '1', '2017-08-25 18:43:40', '5', null, '9.0000', '1', null, null, null, 'addition', '1.2821', '11.5389', '0', null, null, null);
INSERT INTO `erp_adjustment_items` VALUES ('2', '2', '2017-08-25 18:44:30', '5', null, '10.0000', '1', null, null, null, 'addition', '1.2821', '12.8210', '0', null, null, null);

-- ----------------------------
-- Table structure for erp_bom
-- ----------------------------
DROP TABLE IF EXISTS `erp_bom`;
CREATE TABLE `erp_bom` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(55) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `noted` varchar(200) DEFAULT NULL,
  `customer_id` int(11) NOT NULL,
  `customer` varchar(55) NOT NULL,
  `reference_no` varchar(55) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `active` tinyint(1) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_bom
-- ----------------------------
INSERT INTO `erp_bom` VALUES ('1', 'Sophon Kakada', '2017-08-10 22:35:00', '', '0', '', '', '1', null);
INSERT INTO `erp_bom` VALUES ('2', 'Food1', '2017-08-25 18:13:00', '', '0', '', '', '1', null);
INSERT INTO `erp_bom` VALUES ('3', 'Mocha', '2017-08-25 18:48:00', '', '0', '', '', '1', null);

-- ----------------------------
-- Table structure for erp_bom_items
-- ----------------------------
DROP TABLE IF EXISTS `erp_bom_items`;
CREATE TABLE `erp_bom_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bom_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `option_id` int(11) DEFAULT NULL,
  `product_code` varchar(55) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `quantity` decimal(25,4) NOT NULL,
  `cost` decimal(25,4) NOT NULL DEFAULT '0.0000',
  `status` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `transfer_id` (`bom_id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_bom_items
-- ----------------------------
INSERT INTO `erp_bom_items` VALUES ('1', '2', '5', '0', 'RW01', 'Suger', '1.0000', '1.2821', 'deduct');
INSERT INTO `erp_bom_items` VALUES ('2', '2', '6', '0', 'RW02', 'Salt', '0.5000', '0.5887', 'deduct');
INSERT INTO `erp_bom_items` VALUES ('3', '2', '2', '1', 'DK02', 'Coco cola', '1.0000', '0.3816', 'deduct');
INSERT INTO `erp_bom_items` VALUES ('4', '2', '7', '0', 'FP01', 'Food1', '1.0000', '2.2524', 'add');
INSERT INTO `erp_bom_items` VALUES ('5', '3', '5', '0', 'RW01', 'Suger', '1.0000', '1.2821', 'deduct');
INSERT INTO `erp_bom_items` VALUES ('6', '3', '6', '0', 'RW02', 'Salt', '0.5000', '0.5887', 'deduct');
INSERT INTO `erp_bom_items` VALUES ('7', '3', '1', '0', 'DK001', 'Mocha', '1.0000', '1.8708', 'add');
INSERT INTO `erp_bom_items` VALUES ('8', '3', '8', '0', 'FDC', 'F00d1 C', '0.0000', '0.0000', 'add');

-- ----------------------------
-- Table structure for erp_brands
-- ----------------------------
DROP TABLE IF EXISTS `erp_brands`;
CREATE TABLE `erp_brands` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(20) DEFAULT NULL,
  `name` varchar(50) NOT NULL,
  `image` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_brands
-- ----------------------------

-- ----------------------------
-- Table structure for erp_calendar
-- ----------------------------
DROP TABLE IF EXISTS `erp_calendar`;
CREATE TABLE `erp_calendar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(55) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `start` datetime NOT NULL,
  `end` datetime DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `activity` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `color` varchar(7) NOT NULL,
  `type` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_calendar
-- ----------------------------

-- ----------------------------
-- Table structure for erp_captcha
-- ----------------------------
DROP TABLE IF EXISTS `erp_captcha`;
CREATE TABLE `erp_captcha` (
  `captcha_id` bigint(13) unsigned NOT NULL AUTO_INCREMENT,
  `captcha_time` int(10) unsigned NOT NULL,
  `ip_address` varchar(16) CHARACTER SET latin1 NOT NULL DEFAULT '0',
  `word` varchar(20) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`captcha_id`),
  KEY `word` (`word`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_captcha
-- ----------------------------

-- ----------------------------
-- Table structure for erp_categories
-- ----------------------------
DROP TABLE IF EXISTS `erp_categories`;
CREATE TABLE `erp_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `brand_id` int(11) DEFAULT NULL,
  `code` varchar(55) NOT NULL,
  `name` varchar(55) NOT NULL,
  `image` varchar(55) DEFAULT NULL,
  `jobs` tinyint(1) unsigned DEFAULT '1',
  `auto_delivery` tinyint(1) DEFAULT NULL,
  `ac_sale` varchar(20) DEFAULT NULL,
  `ac_cost` varchar(20) DEFAULT NULL,
  `ac_stock` varchar(20) DEFAULT NULL,
  `ac_stock_adj` varchar(20) DEFAULT NULL,
  `ac_wip` varchar(20) DEFAULT NULL,
  `ac_cost_variant` varchar(20) DEFAULT NULL,
  `ac_purchase` varchar(20) DEFAULT NULL,
  `prom_startdate` datetime DEFAULT NULL,
  `prom_enddate` datetime DEFAULT NULL,
  `prom_percentage` int(4) DEFAULT NULL,
  `type` varchar(55) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_categories
-- ----------------------------
INSERT INTO `erp_categories` VALUES ('1', null, 'DK', 'Drink', 'no_image.png', '1', null, '410101', '500101', '100430', '500107', null, '500108', '100430', null, null, null, 'drink');
INSERT INTO `erp_categories` VALUES ('2', null, 'FD', 'Food', null, '1', null, '400000', '500000', '100430', '500107', null, '500108', '100430', null, null, null, 'food');
INSERT INTO `erp_categories` VALUES ('3', null, 'RW01', 'Material', null, '1', null, '', '500000', '100430', '500107', null, '', '100430', null, null, null, '');
INSERT INTO `erp_categories` VALUES ('4', null, 'FP', 'Finish Pro', null, '1', null, '400000', '500000', '100430', '500107', null, '', '', null, null, null, 'food');

-- ----------------------------
-- Table structure for erp_categories_group
-- ----------------------------
DROP TABLE IF EXISTS `erp_categories_group`;
CREATE TABLE `erp_categories_group` (
  `cate_id` int(11) DEFAULT NULL,
  `customer_group_id` int(11) DEFAULT NULL,
  `percent` double DEFAULT NULL,
  `sub_cate` int(11) NOT NULL DEFAULT '0',
  `cate_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of erp_categories_group
-- ----------------------------

-- ----------------------------
-- Table structure for erp_combine_items
-- ----------------------------
DROP TABLE IF EXISTS `erp_combine_items`;
CREATE TABLE `erp_combine_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `sale_deliveries_id` bigint(20) NOT NULL,
  `sale_deliveries_id_combine` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_combine_items
-- ----------------------------

-- ----------------------------
-- Table structure for erp_combo_items
-- ----------------------------
DROP TABLE IF EXISTS `erp_combo_items`;
CREATE TABLE `erp_combo_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `item_code` varchar(20) NOT NULL,
  `quantity` decimal(12,4) NOT NULL,
  `unit_price` decimal(25,4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_combo_items
-- ----------------------------

-- ----------------------------
-- Table structure for erp_companies
-- ----------------------------
DROP TABLE IF EXISTS `erp_companies`;
CREATE TABLE `erp_companies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) DEFAULT NULL,
  `group_id` int(10) unsigned DEFAULT NULL,
  `group_name` varchar(20) NOT NULL,
  `customer_group_id` int(11) DEFAULT NULL,
  `customer_group_name` varchar(100) DEFAULT NULL,
  `price_group_id` int(11) DEFAULT NULL,
  `name` varchar(55) NOT NULL,
  `company` varchar(255) NOT NULL,
  `company_kh` varchar(255) DEFAULT NULL,
  `name_kh` varchar(255) DEFAULT NULL,
  `vat_no` varchar(100) DEFAULT NULL,
  `group_areas_id` int(11) DEFAULT NULL,
  `address` varchar(255) NOT NULL,
  `address_1` varchar(255) DEFAULT NULL,
  `address_2` varchar(255) DEFAULT NULL,
  `address_3` varchar(255) DEFAULT NULL,
  `address_4` varchar(255) DEFAULT NULL,
  `address_5` varchar(255) DEFAULT NULL,
  `address_kh` varchar(255) DEFAULT NULL,
  `sale_man` int(11) DEFAULT NULL,
  `city` varchar(55) DEFAULT NULL,
  `state` varchar(55) DEFAULT NULL,
  `postal_code` varchar(8) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `contact_person` varchar(150) DEFAULT NULL,
  `phone` varchar(50) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `cf1` varchar(100) DEFAULT NULL,
  `cf2` varchar(100) DEFAULT NULL,
  `cf3` varchar(100) DEFAULT NULL,
  `cf4` varchar(100) DEFAULT NULL,
  `cf5` varchar(100) DEFAULT NULL,
  `cf6` varchar(100) DEFAULT NULL,
  `invoice_footer` text,
  `payment_term` int(11) DEFAULT '0',
  `logo` varchar(255) DEFAULT 'logo.png',
  `award_points` int(11) DEFAULT '0',
  `deposit_amount` decimal(25,4) DEFAULT NULL,
  `status` char(20) DEFAULT NULL,
  `posta_card` char(50) DEFAULT NULL,
  `gender` char(10) DEFAULT NULL,
  `attachment` varchar(50) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `credit_limited` decimal(25,4) DEFAULT NULL,
  `business_activity` varchar(255) DEFAULT NULL,
  `group` varchar(255) DEFAULT NULL,
  `village` varchar(255) DEFAULT NULL,
  `street` varchar(255) DEFAULT NULL,
  `sangkat` varchar(255) DEFAULT NULL,
  `district` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  `period` varchar(100) DEFAULT NULL,
  `amount` decimal(25,4) DEFAULT NULL,
  `position` varchar(255) DEFAULT NULL,
  `begining_balance` decimal(25,4) DEFAULT NULL,
  `biller_prefix` varchar(50) DEFAULT NULL,
  `wifi_code` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `group_id` (`group_id`),
  KEY `group_id_2` (`group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_companies
-- ----------------------------
INSERT INTO `erp_companies` VALUES ('1', 'COM1', null, 'biller', null, null, null, 'ABC Company', 'ABC Company', null, null, '12346756892', null, 'PP', null, null, null, null, null, null, null, 'phnom penh', '', '', 'Cambodia', 'owner', '012334345', 'iclouderp@gmail.com', '', '', '', '', '1,2', '', '', null, 'aylogo.png', null, null, null, null, null, null, null, '1970-01-01', '1970-01-01', null, '', '', '', '', '', '', '', '100.0000', null, '0.0000', 'abc', '');
INSERT INTO `erp_companies` VALUES ('2', 'CUS01', '3', 'customer', '11', 'GROUP_PRICE', '0', 'General Customer', 'General Customer', null, 'អតិថិជន', '22323452345', '0', 'PP', '', '', '', '', '', null, null, 'phnom penh', '', '', 'Cambodia', 'owner', '012334345', 'iclouderp@gmail.com', '', null, null, null, null, null, null, null, 'logo.png', null, null, '', null, '', null, '1970-01-01', '0000-00-00', '0000-00-00', null, null, null, '', '', '', '', null, null, null, null, null, null);
INSERT INTO `erp_companies` VALUES ('3', 'SUP02', '4', 'supplier', null, '', null, 'General Supplier', 'General Supplier', null, null, '13241436245', null, 'pp', null, null, null, null, null, null, null, 'phnom penh', '', '', 'Cambodia', 'owner', '012334345', 'iclouderp@gmail.com', '', '', '', '', '', '', null, null, 'logo.png', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null);
INSERT INTO `erp_companies` VALUES ('4', '00004', null, 'biller', null, null, null, 'PSC Cambodia', 'PSC Cambodia', null, null, 'PSC-1788-', null, '#369, Phum Tagov, Chbar Ampov, Phnom Penh Cambodia', null, null, null, null, null, null, null, '', 'Phnom Penh', '', 'Cambodia', 'Chantroppisey TANG', '098765431', 'psc--cambodia@gmail.com', 'ភីអេសស៊ី កម្ពុជា', 'ភីអេសស៊ី កម្ពុជា', '098568265', 'ផ្លូវលេខ369, ភូមិតាងៅ, ច្បារអំពៅ​ ភ្នំពេញ កម្ពុជា', '1', '', '', '0', 'header_logo.png', null, null, null, null, null, null, null, '2017-08-01', '1970-01-01', null, '', '', 'Tagov', 'St.369', '', 'Chbar Ampov', '', '100000000000000000000.0000', null, '0.0000', 'PSC_C-', 'pscCam1788');
INSERT INTO `erp_companies` VALUES ('5', '00030', '3', 'customer', '2', 'Reseller', '1', 'Moniroth', 'Sabay', null, 'មុន្នីរ័ត្ន', 'MNR18', '0', '#234, Phum Boerng Chuk, Doun Penh, Phnom Penh Cambodia', '', '', '', '', '', null, null, '', '', '', '', null, '070534632', 'moniroth@gmail.com', '010163977235', null, null, null, null, null, null, null, 'logo.png', null, null, '', null, 'female', null, '1999-03-11', '0000-00-00', '0000-00-00', null, null, null, '', '', '', '', null, null, null, null, null, null);
INSERT INTO `erp_companies` VALUES ('6', 'DVC010', '5', 'driver', null, null, null, 'Pu. Moe', '', null, null, null, null, '', null, null, null, null, null, null, null, null, null, null, null, null, '010783767', 'moe@gmail.com', null, null, null, null, null, null, null, '0', 'logo.png', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null);
INSERT INTO `erp_companies` VALUES ('7', '00005', '4', 'supplier', null, null, null, 'Reaksa', 'Reaksa', null, null, 'RK28', null, '#578, Phum borem, Boung Keng Kong, Phnom Penh Cambodia', null, null, null, null, null, null, null, 'Phnom Penh', '', '', 'Cambodia', null, '081737385', 'reaksa@gmail.com', '', '', '', '', '', '', null, '0', 'logo.png', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null);
INSERT INTO `erp_companies` VALUES ('8', 'P0001', null, 'biller', null, null, null, 'channa', 'channa', null, null, 'channa0001', null, 'PP', null, null, null, null, null, null, null, '', '', '', '', '069232312', '069232312', 'channa@gmail.com', '', '', '', '', '1,2,3', '1000000000', '', '0', 'Jones.png', null, null, null, null, null, null, null, '0000-00-00', '0000-00-00', null, 'channa', '', '', '271', '', '', '', '10000000.0000', null, '0.0000', 'CH', '');
INSERT INTO `erp_companies` VALUES ('9', 'CUS/00031', '3', 'customer', '3', 'Distributor', null, 'ONE TOUCH', '', null, '', '', '0', 'Takhmau Town', '', '', '', '', '', null, null, '', '', '', '', null, '015443834', '', '', null, null, null, null, null, null, null, 'logo.png', null, null, '', null, '', null, '0000-00-00', '0000-00-00', '0000-00-00', null, null, null, '', '', '', '', null, null, null, null, null, null);
INSERT INTO `erp_companies` VALUES ('13', 'CUS/00040', '3', 'customer', '1', 'General', null, 'stalin', 'stalin computer', null, null, '', null, 'Takhmau town', null, null, null, null, null, null, null, 'Takhmau town', 'PP', '58100', 'Malaysia', null, '12457896', 'stalincomputer@gmail.com', '', '', '', '', '', '', null, '0', 'logo.png', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null);
INSERT INTO `erp_companies` VALUES ('14', 'CUS/00041', '3', 'customer', '1', 'General', null, 'one touch', 'one touch phone shop', null, null, '', null, 'pp', null, null, null, null, null, null, null, 'pp', 'pp', '1', 'pp', null, '13654789', 'onetouch@gmail.com', '', '', '', '', '', '', null, '0', 'logo.png', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null);
INSERT INTO `erp_companies` VALUES ('15', 'SUP/00006', '4', 'supplier', null, null, null, '', '', null, null, null, null, '', null, null, null, null, null, null, null, null, null, null, null, null, '', null, null, null, null, null, null, null, null, '0', 'logo.png', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null);
INSERT INTO `erp_companies` VALUES ('16', 'SUP/00007', '4', 'supplier', null, null, null, '', '', null, null, null, null, '', null, null, null, null, null, null, null, null, null, null, null, null, '', null, null, null, null, null, null, null, null, '0', 'logo.png', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null);
INSERT INTO `erp_companies` VALUES ('17', 'C/00042', '3', 'customer', '3', 'Distributor', null, 'Test 1', '', null, '', '', '0', 'Takhmau Town', '', '', '', '', '', null, null, '', '', '', '', null, '011222333', '', '', null, null, null, null, null, null, null, 'logo.png', null, null, '', null, '', null, '0000-00-00', '0000-00-00', '0000-00-00', null, null, null, '', '', '', '', null, null, null, null, null, null);
INSERT INTO `erp_companies` VALUES ('18', 'CUS/00043', '3', 'customer', '3', 'Distributor', null, 'Chin Roo', 'CloudNET', null, 'ជីនរ៉ូ', '123456789', '0', 'Phnom Penh, Cambodia', '', '', '', '', '', null, null, '', '', '', '', null, '0966577371', 'chinroo@gmail.com', '', null, null, null, null, null, null, null, 'logo.png', null, null, 'single', null, 'male', null, '1994-01-01', '0000-00-00', '0000-00-00', null, null, null, '', '', '', '', null, null, null, null, null, null);
INSERT INTO `erp_companies` VALUES ('19', 'CUS/00044', '3', 'customer', '3', 'Distributor', '0', 'saothan', 'saothan technology', null, '', '', '1', 'Takeo', '', '', '', '', '', null, null, '', '', '', '', null, '0963339498', '', '', null, null, null, null, null, null, null, 'logo.png', null, null, '', null, '', null, '1970-01-01', '0000-00-00', '0000-00-00', null, null, null, '', '', '', '', null, null, null, null, null, null);
INSERT INTO `erp_companies` VALUES ('20', 'DC001', '5', 'driver', null, null, null, 'Mouy-driver', '', null, null, null, null, '', null, null, null, null, null, null, null, null, null, null, null, null, '0962216209', 'mouy.driver@gmail.com', null, null, null, null, null, null, null, '0', 'logo.png', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null);
INSERT INTO `erp_companies` VALUES ('21', 'SUP/00009', '4', 'supplier', null, null, null, 'mouy-supplier', 'CloudNet', null, null, '1234567', null, 'Sorkorng, Kang meas, Kampong Cham', null, null, null, null, null, null, null, 'Phnom Penh', 'Phnom penh', '', 'Cambodia', null, '086840042', 'mouy.supplier@gmail.com', '', '', '', '', '', '', null, '0', 'logo.png', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null);
INSERT INTO `erp_companies` VALUES ('23', 'CUS/00046', '3', 'customer', '3', 'Distributor', '0', 'mouy-customer', 'Bee Bee', null, 'មូយ-ខាស្តម័រ', '123456', '0', 'customer, Sorkorng, Kang meas, Kampong Cham', '', '', '', '', '', null, null, 'Phnom Penh', 'Cambodia', 'FC33', 'Cambodia', null, '0962216210', 'mouy.customer@gmail.com', 'cus123456', null, null, null, null, null, null, null, 'logo.png', null, null, 'single', null, 'female', null, '1996-02-16', '0000-00-00', '0000-00-00', null, null, null, 'Tropeong Chhouk', '371', 'tek tla', 'Sen sok', null, null, null, null, null, null);
INSERT INTO `erp_companies` VALUES ('24', 'CUS/00045', '3', 'customer', '3', 'Distributor', '0', 'Chan Dara', '', null, '', '', '0', 'Phnom Penh, Cambodia', '', '', '', '', '', null, null, '', '', '', '', null, '012282825', 'chan.dara@gmail.com', '', null, null, null, null, null, 'Hello, today is a bad day for me!', null, 'logo.png', null, null, '', null, 'male', null, '1970-01-01', '0000-00-00', '0000-00-00', null, null, null, '', '', '', '', null, null, null, null, null, null);
INSERT INTO `erp_companies` VALUES ('25', 'PRO/00006', null, 'biller', null, null, null, 'naga_world', 'naga_world', null, null, '', null, 'PP', null, null, null, null, null, null, null, '', '', '', '', 'naga_world', '', 'naga_world@gmail.com', '', '', '', '', '4', '', '', '0', '', null, null, null, null, null, null, null, '2017-08-24', '2018-08-24', null, 'naga_world', '', '', '', '', '', '', '1000000.0000', null, '0.0000', 'NG', '');
INSERT INTO `erp_companies` VALUES ('26', 'CUS/00046', '3', 'customer', '3', 'Distributor', null, 'naga_world', '', null, 'naga_world', 'naga_world', '0', 'pppp', '', '', '', '', '', null, null, '', '', '', '', null, '534534', 'naga_worlsd@gmail.com', '', null, null, null, null, null, '', null, 'logo.png', null, null, '', null, '', null, '0000-00-00', '0000-00-00', '0000-00-00', null, null, null, '', '', '', '', null, null, null, null, null, null);

-- ----------------------------
-- Table structure for erp_condition_tax
-- ----------------------------
DROP TABLE IF EXISTS `erp_condition_tax`;
CREATE TABLE `erp_condition_tax` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(10) NOT NULL,
  `name` varchar(55) NOT NULL,
  `rate` decimal(12,4) NOT NULL,
  `min_salary` double(19,0) DEFAULT NULL,
  `max_salary` double(19,0) DEFAULT NULL,
  `reduct_tax` double(19,0) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_condition_tax
-- ----------------------------

-- ----------------------------
-- Table structure for erp_convert
-- ----------------------------
DROP TABLE IF EXISTS `erp_convert`;
CREATE TABLE `erp_convert` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reference_no` varchar(55) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `noted` varchar(200) DEFAULT NULL,
  `warehouse_id` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `bom_id` int(11) DEFAULT NULL,
  `biller_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_convert
-- ----------------------------
INSERT INTO `erp_convert` VALUES ('1', 'CON/1708/00001', '2017-08-25 18:39:00', '', '1', '1', null, '2', '1');
INSERT INTO `erp_convert` VALUES ('2', 'CON/1708/00002', '2017-08-25 18:49:00', '', '1', '1', null, '3', '1');

-- ----------------------------
-- Table structure for erp_convert_items
-- ----------------------------
DROP TABLE IF EXISTS `erp_convert_items`;
CREATE TABLE `erp_convert_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `convert_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `option_id` int(11) DEFAULT NULL,
  `product_code` varchar(55) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `quantity` decimal(25,4) NOT NULL,
  `cost` decimal(25,4) NOT NULL DEFAULT '0.0000',
  `status` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `transfer_id` (`convert_id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_convert_items
-- ----------------------------
INSERT INTO `erp_convert_items` VALUES ('1', '1', '5', '0', 'RW01', 'Suger', '100.0000', '1.2821', 'deduct');
INSERT INTO `erp_convert_items` VALUES ('2', '1', '6', '0', 'RW02', 'Salt', '50.0000', '1.1774', 'deduct');
INSERT INTO `erp_convert_items` VALUES ('3', '1', '2', '1', 'DK02', 'Coco cola', '100.0000', '0.3816', 'deduct');
INSERT INTO `erp_convert_items` VALUES ('4', '1', '7', '0', 'FP01', 'Food1', '100.0000', '2.1868', 'add');
INSERT INTO `erp_convert_items` VALUES ('5', '1', '8', null, 'FDC', 'F00d1 C', '3.0000', '2.1868', 'add');
INSERT INTO `erp_convert_items` VALUES ('6', '2', '5', '0', 'RW01', 'Suger', '10.0000', '1.2821', 'deduct');
INSERT INTO `erp_convert_items` VALUES ('7', '2', '6', '0', 'RW02', 'Salt', '5.0000', '1.1774', 'deduct');
INSERT INTO `erp_convert_items` VALUES ('8', '2', '1', '0', 'DK001', 'Mocha', '10.0000', '1.7007', 'add');
INSERT INTO `erp_convert_items` VALUES ('9', '2', '8', '0', 'FDC', 'F00d1 C', '1.0000', '1.7007', 'add');

-- ----------------------------
-- Table structure for erp_costing
-- ----------------------------
DROP TABLE IF EXISTS `erp_costing`;
CREATE TABLE `erp_costing` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `sale_item_id` int(11) NOT NULL,
  `sale_id` int(11) DEFAULT NULL,
  `delivery_item_id` int(11) DEFAULT NULL,
  `delivery_id` int(11) DEFAULT NULL,
  `purchase_item_id` int(11) DEFAULT NULL,
  `quantity` decimal(15,4) NOT NULL,
  `purchase_net_unit_cost` decimal(25,4) DEFAULT NULL,
  `purchase_unit_cost` decimal(25,4) DEFAULT NULL,
  `sale_net_unit_price` decimal(25,4) NOT NULL,
  `sale_unit_price` decimal(25,4) NOT NULL,
  `quantity_balance` decimal(15,4) DEFAULT NULL,
  `inventory` tinyint(1) DEFAULT '0',
  `overselling` tinyint(1) DEFAULT '0',
  `option_id` int(11) DEFAULT NULL,
  `transaction_type` varchar(50) DEFAULT NULL,
  `transaction_id` varchar(50) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_costing
-- ----------------------------

-- ----------------------------
-- Table structure for erp_currencies
-- ----------------------------
DROP TABLE IF EXISTS `erp_currencies`;
CREATE TABLE `erp_currencies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(5) NOT NULL,
  `name` varchar(55) NOT NULL,
  `in_out` tinyint(1) DEFAULT NULL,
  `rate` decimal(12,4) NOT NULL,
  `auto_update` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_currencies
-- ----------------------------
INSERT INTO `erp_currencies` VALUES ('1', 'USD', 'US Dollar', '1', '1.0000', '0');
INSERT INTO `erp_currencies` VALUES ('2', 'KHM', 'Riel In', '1', '4000.0000', '0');
INSERT INTO `erp_currencies` VALUES ('3', 'KHM_o', 'Riel Out', '0', '4050.0000', '0');

-- ----------------------------
-- Table structure for erp_customer_groups
-- ----------------------------
DROP TABLE IF EXISTS `erp_customer_groups`;
CREATE TABLE `erp_customer_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `percent` int(11) NOT NULL,
  `makeup_cost` tinyint(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_customer_groups
-- ----------------------------
INSERT INTO `erp_customer_groups` VALUES ('3', 'Distributor', '0', '0');
INSERT INTO `erp_customer_groups` VALUES ('4', 'New Customer (+10)', '10', '0');
INSERT INTO `erp_customer_groups` VALUES ('5', 'Makeup (+10)', '10', '1');
INSERT INTO `erp_customer_groups` VALUES ('11', 'GROUP_PRICE', '0', '1');
INSERT INTO `erp_customer_groups` VALUES ('12', 'AAA', '0', '1');

-- ----------------------------
-- Table structure for erp_date_format
-- ----------------------------
DROP TABLE IF EXISTS `erp_date_format`;
CREATE TABLE `erp_date_format` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `js` varchar(20) NOT NULL,
  `php` varchar(20) NOT NULL,
  `sql` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_date_format
-- ----------------------------
INSERT INTO `erp_date_format` VALUES ('1', 'mm-dd-yyyy', 'm-d-Y', '%m-%d-%Y');
INSERT INTO `erp_date_format` VALUES ('2', 'mm/dd/yyyy', 'm/d/Y', '%m/%d/%Y');
INSERT INTO `erp_date_format` VALUES ('3', 'mm.dd.yyyy', 'm.d.Y', '%m.%d.%Y');
INSERT INTO `erp_date_format` VALUES ('4', 'dd-mm-yyyy', 'd-m-Y', '%d-%m-%Y');
INSERT INTO `erp_date_format` VALUES ('5', 'dd/mm/yyyy', 'd/m/Y', '%d/%m/%Y');
INSERT INTO `erp_date_format` VALUES ('6', 'dd.mm.yyyy', 'd.m.Y', '%d.%m.%Y');
INSERT INTO `erp_date_format` VALUES ('7', 'yyyy-mm-dd', 'Y-m-d', '%Y-%m-%d');

-- ----------------------------
-- Table structure for erp_deliveries
-- ----------------------------
DROP TABLE IF EXISTS `erp_deliveries`;
CREATE TABLE `erp_deliveries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `sale_id` int(11) NOT NULL,
  `do_reference_no` varchar(50) NOT NULL,
  `sale_reference_no` varchar(50) NOT NULL,
  `biller_id` int(11) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `customer` varchar(55) NOT NULL,
  `address` varchar(1000) NOT NULL,
  `note` varchar(1000) DEFAULT NULL,
  `total_cost` decimal(8,4) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_count` int(4) unsigned zerofill NOT NULL,
  `type` varchar(50) DEFAULT NULL,
  `delivery_status` varchar(50) DEFAULT NULL,
  `delivery_by` int(11) DEFAULT NULL,
  `sale_status` varchar(50) DEFAULT NULL,
  `issued_sale_id` int(11) DEFAULT '0',
  `pos` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_deliveries
-- ----------------------------

-- ----------------------------
-- Table structure for erp_delivery_items
-- ----------------------------
DROP TABLE IF EXISTS `erp_delivery_items`;
CREATE TABLE `erp_delivery_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `delivery_id` int(11) NOT NULL,
  `do_reference_no` varchar(50) NOT NULL,
  `product_id` int(11) unsigned NOT NULL,
  `item_id` int(11) DEFAULT NULL,
  `sale_id` int(11) DEFAULT NULL,
  `product_type` varchar(20) DEFAULT NULL,
  `product_name` varchar(255) NOT NULL,
  `option_id` int(11) DEFAULT NULL,
  `category_name` varchar(255) NOT NULL,
  `warehouse_id` int(11) DEFAULT NULL,
  `unit_price` decimal(25,4) DEFAULT NULL,
  `begining_balance` decimal(25,4) DEFAULT NULL,
  `quantity_received` decimal(15,4) NOT NULL,
  `ending_balance` decimal(25,4) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_count` int(4) unsigned zerofill NOT NULL,
  `cost` decimal(8,6) unsigned zerofill NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sale_id` (`do_reference_no`),
  KEY `product_id` (`product_id`),
  KEY `product_id_2` (`product_id`,`do_reference_no`),
  KEY `sale_id_2` (`do_reference_no`,`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_delivery_items
-- ----------------------------

-- ----------------------------
-- Table structure for erp_deposits
-- ----------------------------
DROP TABLE IF EXISTS `erp_deposits`;
CREATE TABLE `erp_deposits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `company_id` int(11) NOT NULL,
  `amount` decimal(25,4) NOT NULL,
  `paid_by` varchar(50) DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `account_code` varchar(20) DEFAULT NULL,
  `bank_code` varchar(20) DEFAULT NULL,
  `biller_id` int(11) DEFAULT NULL,
  `reference` varchar(50) DEFAULT NULL,
  `po_reference_no` varchar(50) DEFAULT NULL,
  `sale_id` int(11) DEFAULT NULL,
  `so_id` int(11) DEFAULT NULL,
  `payment_id` int(11) DEFAULT NULL,
  `po_id` int(11) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `deposit_id` int(11) DEFAULT NULL,
  `opening` tinyint(1) unsigned zerofill NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_deposits
-- ----------------------------

-- ----------------------------
-- Table structure for erp_documents
-- ----------------------------
DROP TABLE IF EXISTS `erp_documents`;
CREATE TABLE `erp_documents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_code` varchar(50) DEFAULT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `description` text,
  `brand_id` varchar(50) DEFAULT NULL,
  `category_id` varchar(50) DEFAULT NULL,
  `subcategory_id` varchar(50) DEFAULT NULL,
  `cost` decimal(50,0) DEFAULT NULL,
  `price` decimal(8,4) DEFAULT NULL,
  `unit` varchar(10) DEFAULT NULL,
  `image` varchar(150) DEFAULT NULL,
  `serial` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of erp_documents
-- ----------------------------

-- ----------------------------
-- Table structure for erp_document_photos
-- ----------------------------
DROP TABLE IF EXISTS `erp_document_photos`;
CREATE TABLE `erp_document_photos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `document_id` int(11) NOT NULL,
  `photo` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_document_photos
-- ----------------------------

-- ----------------------------
-- Table structure for erp_employee_salary_tax
-- ----------------------------
DROP TABLE IF EXISTS `erp_employee_salary_tax`;
CREATE TABLE `erp_employee_salary_tax` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL DEFAULT '0',
  `basic_salary` decimal(24,4) DEFAULT NULL,
  `amount_usd` decimal(24,4) DEFAULT NULL,
  `spouse` int(10) DEFAULT NULL,
  `minor_children` int(20) DEFAULT NULL,
  `position` varchar(50) DEFAULT NULL,
  `date_insert` date DEFAULT NULL,
  `status` varchar(50) NOT NULL,
  `location` varchar(300) DEFAULT NULL,
  `date_print` date NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `remark` varchar(250) DEFAULT NULL,
  `trigger_id` int(11) DEFAULT NULL,
  `tax_rate` double DEFAULT NULL,
  `salary_tax` double DEFAULT NULL,
  `salary_tobe_paid` double DEFAULT NULL,
  `declare_salary_tax` tinyint(1) NOT NULL DEFAULT '0',
  `tab` tinyint(1) DEFAULT NULL,
  `allowance` decimal(4,0) DEFAULT NULL,
  `allowance_tax` decimal(4,0) DEFAULT NULL,
  `remark_fb` text,
  `declare_tax` tinyint(1) DEFAULT '0',
  `hide_row` tinyint(1) DEFAULT '0',
  `total_time` int(11) DEFAULT NULL,
  `emp_code` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `employee_id` (`employee_id`),
  KEY `trigger_id` (`trigger_id`),
  KEY `date_insert` (`date_insert`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_employee_salary_tax
-- ----------------------------

-- ----------------------------
-- Table structure for erp_employee_salary_tax_small_taxpayers
-- ----------------------------
DROP TABLE IF EXISTS `erp_employee_salary_tax_small_taxpayers`;
CREATE TABLE `erp_employee_salary_tax_small_taxpayers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL DEFAULT '0',
  `basic_salary` decimal(24,4) DEFAULT NULL,
  `amount_usd` decimal(24,4) DEFAULT NULL,
  `spouse` int(10) DEFAULT NULL,
  `minor_children` int(20) DEFAULT NULL,
  `position` varchar(50) DEFAULT NULL,
  `date_insert` date DEFAULT NULL,
  `status` varchar(50) NOT NULL,
  `location` varchar(300) DEFAULT NULL,
  `date_print` date NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `remark` varchar(250) DEFAULT NULL,
  `trigger_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_employee_salary_tax_small_taxpayers
-- ----------------------------

-- ----------------------------
-- Table structure for erp_employee_salary_tax_small_taxpayers_trigger
-- ----------------------------
DROP TABLE IF EXISTS `erp_employee_salary_tax_small_taxpayers_trigger`;
CREATE TABLE `erp_employee_salary_tax_small_taxpayers_trigger` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reference_no` varchar(50) NOT NULL,
  `year_month` date NOT NULL,
  `isCompany` tinyint(1) unsigned NOT NULL,
  `updated_by` int(11) unsigned DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_by` int(11) unsigned DEFAULT NULL,
  `total_salary_usd` decimal(24,2) unsigned DEFAULT NULL,
  `total_salary_tax_usd` decimal(24,2) unsigned DEFAULT NULL,
  `total_salary_tax_cal_base_riel` decimal(24,2) DEFAULT NULL,
  `total_salary_tax_riel` decimal(24,2) DEFAULT NULL,
  `date_print` date DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `trigger_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of erp_employee_salary_tax_small_taxpayers_trigger
-- ----------------------------

-- ----------------------------
-- Table structure for erp_employee_salary_tax_trigger
-- ----------------------------
DROP TABLE IF EXISTS `erp_employee_salary_tax_trigger`;
CREATE TABLE `erp_employee_salary_tax_trigger` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reference_no` varchar(50) NOT NULL,
  `year_month` varchar(15) NOT NULL,
  `isCompany` tinyint(1) unsigned NOT NULL,
  `updated_by` int(11) unsigned DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_by` int(11) unsigned DEFAULT NULL,
  `total_salary_usd` decimal(24,2) unsigned DEFAULT NULL,
  `total_salary_tax_usd` decimal(24,2) unsigned DEFAULT NULL,
  `total_salary_tax_cal_base_riel` decimal(24,2) DEFAULT NULL,
  `total_salary_tax_riel` decimal(24,2) DEFAULT NULL,
  `date_print` date NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `tab` tinyint(1) DEFAULT NULL,
  `total_allowance_tax` decimal(25,4) DEFAULT NULL,
  `reference_no_j` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `reference` (`reference_no`),
  KEY `year_month` (`year_month`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of erp_employee_salary_tax_trigger
-- ----------------------------

-- ----------------------------
-- Table structure for erp_employee_type
-- ----------------------------
DROP TABLE IF EXISTS `erp_employee_type`;
CREATE TABLE `erp_employee_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(120) NOT NULL DEFAULT '',
  `code` varchar(30) DEFAULT NULL,
  `account_salary` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of erp_employee_type
-- ----------------------------

-- ----------------------------
-- Table structure for erp_enter_using_stock
-- ----------------------------
DROP TABLE IF EXISTS `erp_enter_using_stock`;
CREATE TABLE `erp_enter_using_stock` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `date` datetime DEFAULT NULL,
  `warehouse_id` int(5) DEFAULT NULL,
  `authorize_id` int(10) DEFAULT NULL,
  `employee_id` int(10) DEFAULT NULL,
  `shop` int(10) DEFAULT NULL,
  `account` int(20) DEFAULT NULL,
  `note` text,
  `create_by` int(10) DEFAULT NULL,
  `reference_no` varchar(255) DEFAULT NULL,
  `type` varchar(25) DEFAULT NULL,
  `total_cost` decimal(25,4) DEFAULT NULL,
  `using_reference_no` varchar(255) DEFAULT NULL,
  `total_using_cost` decimal(25,4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of erp_enter_using_stock
-- ----------------------------
INSERT INTO `erp_enter_using_stock` VALUES ('1', '2017-08-25 00:00:00', '1', '1', '9', '1', '110205', '', '1', 'ES/1708/00001', 'use', '65.6040', null, null);

-- ----------------------------
-- Table structure for erp_enter_using_stock_items
-- ----------------------------
DROP TABLE IF EXISTS `erp_enter_using_stock_items`;
CREATE TABLE `erp_enter_using_stock_items` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `code` varchar(255) DEFAULT NULL,
  `description` text,
  `reason` text,
  `qty_use` decimal(25,4) DEFAULT NULL,
  `unit` varchar(255) DEFAULT NULL,
  `reference_no` varchar(255) DEFAULT NULL,
  `warehouse_id` int(10) DEFAULT NULL,
  `qty_by_unit` int(25) DEFAULT NULL,
  `cost` decimal(25,4) DEFAULT NULL,
  `exp_cate_id` text,
  `option_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of erp_enter_using_stock_items
-- ----------------------------
INSERT INTO `erp_enter_using_stock_items` VALUES ('1', 'FP01', 'shut up! 7:00 ', null, '30.0000', 'Raw', 'ES/1708/00001', '1', '30', '2.1868', null, null);

-- ----------------------------
-- Table structure for erp_expenses
-- ----------------------------
DROP TABLE IF EXISTS `erp_expenses`;
CREATE TABLE `erp_expenses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `reference` varchar(55) NOT NULL,
  `amount` decimal(25,6) NOT NULL,
  `note` varchar(1000) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `attachment` varchar(55) DEFAULT NULL,
  `account_code` varchar(20) DEFAULT NULL,
  `bank_code` varchar(20) DEFAULT NULL,
  `biller_id` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `tax` tinyint(3) DEFAULT '0',
  `status` varchar(55) DEFAULT '',
  `warehouse_id` int(11) DEFAULT NULL,
  `sale_id` int(11) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_expenses
-- ----------------------------

-- ----------------------------
-- Table structure for erp_expenses_audit
-- ----------------------------
DROP TABLE IF EXISTS `erp_expenses_audit`;
CREATE TABLE `erp_expenses_audit` (
  `id` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `reference` varchar(55) NOT NULL,
  `amount` decimal(25,6) NOT NULL,
  `note` varchar(1000) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `attachment` varchar(55) DEFAULT NULL,
  `account_code` varchar(20) DEFAULT NULL,
  `bank_code` varchar(20) DEFAULT NULL,
  `biller_id` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `tax` tinyint(3) DEFAULT '0',
  `status` varchar(55) DEFAULT '',
  `warehouse_id` int(11) DEFAULT NULL,
  `audit_id` int(11) NOT NULL AUTO_INCREMENT,
  `audit_created_by` int(11) NOT NULL,
  `audit_record_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `audit_type` varchar(55) NOT NULL,
  PRIMARY KEY (`audit_id`),
  KEY `id` (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_expenses_audit
-- ----------------------------

-- ----------------------------
-- Table structure for erp_expense_categories
-- ----------------------------
DROP TABLE IF EXISTS `erp_expense_categories`;
CREATE TABLE `erp_expense_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(55) NOT NULL,
  `name` varchar(55) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_expense_categories
-- ----------------------------

-- ----------------------------
-- Table structure for erp_frequency
-- ----------------------------
DROP TABLE IF EXISTS `erp_frequency`;
CREATE TABLE `erp_frequency` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(255) DEFAULT NULL,
  `day` double DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of erp_frequency
-- ----------------------------

-- ----------------------------
-- Table structure for erp_gift_cards
-- ----------------------------
DROP TABLE IF EXISTS `erp_gift_cards`;
CREATE TABLE `erp_gift_cards` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `card_no` varchar(20) NOT NULL,
  `value` decimal(25,4) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `customer` varchar(255) DEFAULT NULL,
  `balance` decimal(25,4) NOT NULL,
  `expiry` date DEFAULT NULL,
  `created_by` varchar(55) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `card_no` (`card_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_gift_cards
-- ----------------------------

-- ----------------------------
-- Table structure for erp_gift_cards_audit
-- ----------------------------
DROP TABLE IF EXISTS `erp_gift_cards_audit`;
CREATE TABLE `erp_gift_cards_audit` (
  `id` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `card_no` varchar(20) NOT NULL,
  `value` decimal(25,4) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `customer` varchar(255) DEFAULT NULL,
  `balance` decimal(25,4) NOT NULL,
  `expiry` date DEFAULT NULL,
  `created_by` varchar(55) NOT NULL,
  `audit_id` int(11) NOT NULL AUTO_INCREMENT,
  `audit_created_by` int(11) NOT NULL,
  `audit_record_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `audit_type` varchar(55) NOT NULL,
  PRIMARY KEY (`audit_id`),
  UNIQUE KEY `card_no` (`card_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_gift_cards_audit
-- ----------------------------

-- ----------------------------
-- Table structure for erp_gl_charts
-- ----------------------------
DROP TABLE IF EXISTS `erp_gl_charts`;
CREATE TABLE `erp_gl_charts` (
  `accountcode` int(11) NOT NULL DEFAULT '0',
  `accountname` varchar(200) DEFAULT '',
  `parent_acc` int(11) DEFAULT '0',
  `sectionid` int(11) DEFAULT '0',
  `account_tax_id` int(11) DEFAULT '0',
  `acc_level` int(11) DEFAULT '0',
  `lineage` varchar(500) NOT NULL,
  `bank` tinyint(1) DEFAULT NULL,
  `value` decimal(55,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`accountcode`),
  KEY `AccountCode` (`accountcode`) USING BTREE,
  KEY `AccountName` (`accountname`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_gl_charts
-- ----------------------------
INSERT INTO `erp_gl_charts` VALUES ('100100', 'Cash', '100100', '10', '0', '0', '', '1', '0.00');
INSERT INTO `erp_gl_charts` VALUES ('100101', 'Petty Cash', '100100', '10', '0', '0', '', '1', '0.00');
INSERT INTO `erp_gl_charts` VALUES ('100102', 'Cash on Hand', '100100', '10', '0', '0', '', '1', '0.00');
INSERT INTO `erp_gl_charts` VALUES ('100103', 'ANZ Bank', '100100', '10', '0', '0', '', '1', '0.00');
INSERT INTO `erp_gl_charts` VALUES ('100104', 'Wing Account', '100100', '10', '0', '0', '', '1', '0.00');
INSERT INTO `erp_gl_charts` VALUES ('100105', 'Visa', '100100', '10', '0', '0', '', '1', '0.00');
INSERT INTO `erp_gl_charts` VALUES ('100106', 'Chequing Bank Account', '100100', '10', '0', '0', '', '1', '0.00');
INSERT INTO `erp_gl_charts` VALUES ('100200', 'Account Receivable', '0', '10', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('100400', 'Other Current Assets', '0', '10', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('100410', 'Prepaid Expense', '100400', '10', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('100420', 'Supplier Deposit', '100400', '10', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('100430', 'Inventory', '100400', '10', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('100440', 'Deferred Tax Asset', '100400', '10', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('100441', 'VAT Input', '100440', '10', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('100442', 'VAT Credit Carried Forward', '100440', '10', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('100500', 'Cash Advance', '100400', '10', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('100501', 'Loan to Related Parties', '100400', '10', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('100502', 'Staff Advance Cash', '100400', '10', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('101005', 'Own Invest', '0', '80', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('110200', 'Property, Plant and Equipment', '0', '11', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('110201', 'Furniture', '110200', '11', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('110202', 'Office Equipment', '110200', '11', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('110203', 'Machineries', '110200', '11', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('110204', 'Leasehold Improvement', '110200', '11', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('110205', 'IT Equipment & Computer', '110200', '11', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('110206', 'Vehicle', '110200', '11', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('110250', 'Less Total Accumulated Depreciation', '110200', '11', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('110251', 'Less Acc. Dep. of Furniture', '110250', '11', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('110252', 'Less Acc. Dep. of Office Equipment', '110250', '11', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('110253', 'Less Acc. Dep. of Machineries', '110250', '11', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('110254', 'Less Acc. Dep. of Leasehold Improvement', '110250', '11', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('110255', 'Less Acc. Dep. of IT Equipment & Computer', '110250', '11', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('110256', 'Less Acc. Dep of Vehicle', '110250', '11', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('201100', 'Accounts Payable', '0', '20', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('201200', 'Other Current Liabilities', '0', '20', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('201201', 'Salary Payable', '201200', '20', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('201202', 'OT Payable', '201200', '20', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('201203', 'Allowance Payable', '201200', '20', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('201204', 'Bonus Payable', '201200', '20', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('201205', 'Commission Payable', '201200', '20', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('201206', 'Interest Payable', '201200', '20', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('201207', 'Loan from Related Parties', '201200', '20', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('201208', 'Customer Deposit', '201200', '20', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('201209', 'Accrued Expense', '201200', '20', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('201400', 'Deferred Tax Liabilities', '0', '20', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('201401', 'Salary Tax Payable', '201400', '20', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('201402', 'Withholding Tax Payable', '201400', '20', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('201403', 'VAT Payable', '201400', '20', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('201404', 'Profit Tax Payable', '201400', '20', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('201405', 'Prepayment Profit Tax Payable', '201400', '20', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('201406', 'Fringe Benefit Tax Payable', '201400', '20', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('201407', 'VAT Output', '201400', '20', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('300000', 'Capital Stock', '0', '30', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('300100', 'Paid-in Capital', '300000', '30', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('300101', 'Additional Paid-in Capital', '300000', '30', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('300200', 'Retained Earnings', '0', '30', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('300300', 'Opening Balance', '0', '30', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('400000', 'Sale Revenue', '0', '40', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('400001', 'Utilities', '0', '60', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('410101', 'Income', '400000', '40', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('410102', 'Sale Discount', '400000', '40', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('410103', 'Other Income', '400000', '40', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('410104', 'Income Restaurant', '400000', '40', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('410105', 'Income Phone', '400000', '40', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('500000', 'Cost of Goods Sold', '0', '50', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('500101', 'COMS', '500000', '50', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('500102', 'Freight Expense', '500000', '60', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('500103', 'Wages & Salaries', '500000', '60', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('500104', 'Cost Restaurant', '500000', '50', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('500105', 'Cost Phones', '500000', '50', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('500106', 'Purchase Discount', '500000', '50', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('500107', 'Inventory Adjustment', '500000', '50', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('500108', 'Cost of Variance', '500000', '50', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('500109', 'Using Stock', '500101', '50', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('600000', 'Expenses', '0', '60', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('601100', 'Staff Cost', '600000', '60', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('601101', 'Salary Expense', '601100', '60', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('601102', 'OT', '601100', '60', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('601103', 'Allowance ', '601100', '60', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('601104', 'Bonus', '601100', '60', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('601105', 'Commission', '601100', '60', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('601106', 'Training/Education', '601100', '60', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('601107', 'Compensation', '601100', '60', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('601108', 'Other Staff Relation', '601100', '60', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('601200', 'Administration Cost', '600000', '60', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('601201', 'Rental Expense', '601200', '60', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('601202', 'Utilities', '601200', '60', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('601203', 'Marketing & Advertising', '601200', '60', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('601204', 'Repair & Maintenance', '601200', '60', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('601205', 'Customer Relation', '601200', '60', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('601206', 'Transportation', '601200', '60', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('601207', 'Communication', '601200', '60', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('601208', 'Insurance Expense', '601200', '60', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('601209', 'Professional Fee', '601200', '60', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('601210', 'Depreciation Expense', '601200', '60', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('601211', 'Amortization Expense', '601200', '60', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('601212', 'Stationery', '601200', '60', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('601213', 'Office Supplies', '601200', '60', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('601214', 'Donation', '601200', '60', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('601215', 'Entertainment Expense', '601200', '60', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('601216', 'Travelling & Accomodation', '601200', '60', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('601217', 'Service Computer Expenses', '601200', '60', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('601218', 'Interest Expense', '601200', '60', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('601219', 'Bank Charge', '601200', '60', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('601220', 'Miscellaneous Expense', '601200', '60', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('601221', 'Canteen Supplies', '601200', '60', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('601222', 'Registration Expenses', '601200', '60', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('710300', 'Other Income', '0', '70', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('710301', 'Interest Income', '710300', '70', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('710302', 'Other Revenue & Gain', '710300', '70', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('801300', 'Other Expenses', '0', '80', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('801301', 'Other Expense & Loss', '801300', '80', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('801302', 'Bad Dept Expense', '801300', '80', '0', '0', '', null, '0.00');
INSERT INTO `erp_gl_charts` VALUES ('801303', 'Tax & Duties Expense', '801300', '80', '0', '0', '', null, '0.00');

-- ----------------------------
-- Table structure for erp_gl_charts_tax
-- ----------------------------
DROP TABLE IF EXISTS `erp_gl_charts_tax`;
CREATE TABLE `erp_gl_charts_tax` (
  `account_tax_id` int(11) NOT NULL AUTO_INCREMENT,
  `accountcode` varchar(19) DEFAULT '0',
  `accountname` varchar(200) DEFAULT '',
  `accountname_kh` varchar(250) DEFAULT '0',
  `sectionid` int(11) DEFAULT '0',
  PRIMARY KEY (`account_tax_id`),
  KEY `AccountCode` (`accountcode`) USING BTREE,
  KEY `AccountName` (`accountname`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=91 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of erp_gl_charts_tax
-- ----------------------------
INSERT INTO `erp_gl_charts_tax` VALUES ('1', 'B1', 'Sales of manufactured products', 'ការលក់ផលិតផល', '30');
INSERT INTO `erp_gl_charts_tax` VALUES ('2', 'B2', 'Sales of goods', 'ការលក់ទំនិញ', '30');
INSERT INTO `erp_gl_charts_tax` VALUES ('3', 'B3', 'Sales/Supply of services', 'ការផ្គត់ផ្គង់សេវា', '30');
INSERT INTO `erp_gl_charts_tax` VALUES ('4', 'A2', 'Freehold Land', 'ដីធ្លីរបស់សហគ្រាស', '10');
INSERT INTO `erp_gl_charts_tax` VALUES ('5', 'A3', 'Improvements and preparation of land', 'ការរៀបចំតុបតែងលំអរដីរបស់សហគ្រាស', '10');
INSERT INTO `erp_gl_charts_tax` VALUES ('6', 'A4', 'Freehold buildings', 'សំណង់ងគាររបស់សហគ្រាស', '10');
INSERT INTO `erp_gl_charts_tax` VALUES ('7', 'B4', 'Costs of pruducts sold of production enterprises(TOP 01/V)', 'ថៃ្លដើមផលិតផលបានលក់របស់សហគ្រាសផលិតកម្ម​(TOP 01/V)', '30');
INSERT INTO `erp_gl_charts_tax` VALUES ('8', 'A5', 'Freehold buildings on leasehold land', 'សំណង់អាគារលើដីធ្លីក្រោមភតិសន្យា', '10');
INSERT INTO `erp_gl_charts_tax` VALUES ('9', 'B5', 'Costs of goods sold of​​​​ non- production enterprises (TOP 01/VI)', 'ថៃ្លដើម ទំនិញបានលក់របស់សហគ្រាសក្រៅពីផលិតកម្ម(TOP 0', '30');
INSERT INTO `erp_gl_charts_tax` VALUES ('10', 'A6', 'Non-current assets in progress', 'ទ្រព្យសកម្មរយះពេលវែងកំពុងដំណើរការ', '10');
INSERT INTO `erp_gl_charts_tax` VALUES ('11', 'A7', 'Plant and equipemt', 'រោងចក្រ​​​(ក្រៅពីអគារ)និងបរិក្ខារ', '10');
INSERT INTO `erp_gl_charts_tax` VALUES ('12', 'B5a', 'Costs of services supplied', 'ថៃ្លដើមសេវាបានផ្គត់ផ្គង់', '30');
INSERT INTO `erp_gl_charts_tax` VALUES ('13', 'A8', 'Goodwill', 'កេរ្តិ៍ឈ្មោះ/មូលនិធិពាណិជ្ជកម្ម', '10');
INSERT INTO `erp_gl_charts_tax` VALUES ('14', 'A9', 'Preliminary and formation expenses', 'ចំណាយបង្កើតសហគ្រាសដំបូង', '10');
INSERT INTO `erp_gl_charts_tax` VALUES ('15', 'B8', 'Grant/subsidy', 'ឧបត្ថម្ភកធន', '70');
INSERT INTO `erp_gl_charts_tax` VALUES ('16', 'A10', 'Leasehold assets and lease premiums', 'ទ្រព្យសកម្មក្រោមភតិសន្យា​​ និង​បុព្វលាភនៃការប្រើប្', '10');
INSERT INTO `erp_gl_charts_tax` VALUES ('17', 'A11', 'Investment in other enterprise', 'វិនិយោគក្នុងសហគ្រាសដទៃ', '10');
INSERT INTO `erp_gl_charts_tax` VALUES ('18', 'B9', 'Dividend received or receivable', 'ចំណូលពីភាគលាភបានទទួល​ ឬ ត្រូវទទួល', '70');
INSERT INTO `erp_gl_charts_tax` VALUES ('19', 'B10', 'Interest received or receivable', 'ចំំណូលពីការប្រាក់បានទទួល ឬ ត្រូវទទួល', '70');
INSERT INTO `erp_gl_charts_tax` VALUES ('20', 'A29', 'Capital/ Share capital', 'មូលធន/ មូលធនភាគហ៊ុន', '30');
INSERT INTO `erp_gl_charts_tax` VALUES ('21', 'A12', 'Other non-current assets', 'ទ្រព្យសកម្មរយ:ពេលវែងផ្សេងៗ', '10');
INSERT INTO `erp_gl_charts_tax` VALUES ('22', 'B11', 'Royalty received or receivable', 'ចំណូលពីសួយសារបានទទួល ឬ ត្រូវទទួល', '70');
INSERT INTO `erp_gl_charts_tax` VALUES ('23', 'A30', 'Share premium', 'តម្លៃលើសនៃការលក់ប័ណ្ណភាគហ៊ុន', '30');
INSERT INTO `erp_gl_charts_tax` VALUES ('24', 'B12', 'Rental received or receivable', 'ចំណូលពីការជួលបានទទួល ឬ ត្រូវទទួល', '70');
INSERT INTO `erp_gl_charts_tax` VALUES ('25', 'A31', 'Legal capital reserves', 'មូលធនបំរុងច្បាប់', '30');
INSERT INTO `erp_gl_charts_tax` VALUES ('26', 'A14', 'Stock of raw materials and supplies', 'ស្តូកវត្ធុធាតុដើម និងសំភារ:ផ្គត់ផ្គង់', '11');
INSERT INTO `erp_gl_charts_tax` VALUES ('27', 'A32', 'Reserves revaluation surplus of assets', 'លំអៀងលើសការវាយតំលៃឡើងវិញនូវទ្រព្យសកម្ម', '30');
INSERT INTO `erp_gl_charts_tax` VALUES ('28', 'A33', 'Other capital reserves', 'មូលធនបំរុងផ្សេងៗ', '30');
INSERT INTO `erp_gl_charts_tax` VALUES ('29', 'A15', 'Stock of goods', 'ស្តុកទំនិញ', '11');
INSERT INTO `erp_gl_charts_tax` VALUES ('30', 'B13', 'Gain from disposal of fixed assets (captital gain)', 'ផលចំណេញ/តំលៃលើសពីការលក់ទ្រព្យសកម្មរយះពេលវែង', '70');
INSERT INTO `erp_gl_charts_tax` VALUES ('31', 'A34', 'Profit and loss brought forward', 'លទ្ធផលចំណេញ/ ខាត យោងពីមុន', '30');
INSERT INTO `erp_gl_charts_tax` VALUES ('32', 'A16', 'Stock of finished goods', 'ស្តុកផលិតផលសម្រាច', '11');
INSERT INTO `erp_gl_charts_tax` VALUES ('33', 'A35', 'Profit and loss for the period', 'លទ្ធផងចំណេញ/ ខាត នៃកាលបរិច្ឆេត', '30');
INSERT INTO `erp_gl_charts_tax` VALUES ('34', 'B14', 'Gain from disposal of securities', 'ផលចំណេញពីការលក់មូលប័ត្រ/សញ្ញាប័ណ្ណ', '70');
INSERT INTO `erp_gl_charts_tax` VALUES ('35', 'A37', 'Loan from related parties', 'បំណុលភាគីជាប់ទាក់ទិន', '21');
INSERT INTO `erp_gl_charts_tax` VALUES ('36', 'A38', 'Loan from banks and other external parties', 'បំណុលធនាគារ និងបំណុលភាគីមិនជាប់ទាក់ទិនផ្សេងៗ', '21');
INSERT INTO `erp_gl_charts_tax` VALUES ('37', 'B15', 'Share of profit from joint venture', 'ភាគចំណេញពីប្រតិបត្តិការរួមគ្នា', '70');
INSERT INTO `erp_gl_charts_tax` VALUES ('38', 'A17', 'Products in progress', 'ផលិតផលកំពុងផលិត', '11');
INSERT INTO `erp_gl_charts_tax` VALUES ('39', 'A39', 'Provision for charges and contigencies', 'សវិធានធនសំរាប់បន្ទុក និង​ហានិភ័យ', '21');
INSERT INTO `erp_gl_charts_tax` VALUES ('40', 'B16', 'Realised exchange gain', 'ផលចំណេញពីការប្តូរប្រាក់សំរេចបាន', '70');
INSERT INTO `erp_gl_charts_tax` VALUES ('41', 'A40', 'Other non-current liabilities', 'បំណុលរយៈពេលវែងផ្សេងៗ', '21');
INSERT INTO `erp_gl_charts_tax` VALUES ('42', 'A18', 'Account receivevable/trade debtors', 'គណនីត្រូវទទួល​ /អតិថិជន', '11');
INSERT INTO `erp_gl_charts_tax` VALUES ('43', 'B17', 'Unrealised exchange gain', 'ផលចំណេញពីការប្តូរប្រាក់មិនទាន់សំរេចបាន', '70');
INSERT INTO `erp_gl_charts_tax` VALUES ('44', 'A42', 'Bank overdraft', 'សាច់ប្រាក់ដកពីធនាគារលើសប្រាក់បញ្ជី (ឥណទានរិបារូប៍រ', '20');
INSERT INTO `erp_gl_charts_tax` VALUES ('45', 'A19', 'Other account receivables', 'គណនីទទួលផ្សេងៗ', '11');
INSERT INTO `erp_gl_charts_tax` VALUES ('46', 'B18', 'Other revenues', 'ចំណូលដ៏ទៃទៀត', '70');
INSERT INTO `erp_gl_charts_tax` VALUES ('47', 'A43', 'Short-term borrowing-current portion of interest bearing borrowing', 'ចំណែកចរន្តនៃបំណុលមានការប្រាក់', '20');
INSERT INTO `erp_gl_charts_tax` VALUES ('48', 'A20', 'Prepaid expenses', 'ចំណាយបានកត់ត្រាមុន', '11');
INSERT INTO `erp_gl_charts_tax` VALUES ('49', 'B20', 'Salaries expenses', 'ចំណាយបៀវត្ស', '60');
INSERT INTO `erp_gl_charts_tax` VALUES ('50', 'A44', 'Accounts payble to relate parties', 'គណនីត្រូវសងបុគ្គលជាប់ទាក់ទិន (ភាគីសម្ព័ន្ធញាត្តិ)', '20');
INSERT INTO `erp_gl_charts_tax` VALUES ('51', 'A45', 'Other accounts payable', 'គណនីត្រូវសងផ្សេងៗ', '20');
INSERT INTO `erp_gl_charts_tax` VALUES ('52', 'A21', 'Cash on hand and at banks', 'សាច់ប្រាក់នៅក្នុងបេឡា និងនៅធនាគា', '11');
INSERT INTO `erp_gl_charts_tax` VALUES ('53', 'B21', 'Fuel, gas,electricity and water expenses', 'ចំណាយប្រេង ឧស្មន័ អគ្គីសនី និងទឹក', '60');
INSERT INTO `erp_gl_charts_tax` VALUES ('54', 'A46', 'Unearned revenue, accruals and other current liabilities', 'ចំណូលកត់មុន គណនីចំណាយបង្ករ និងបំណុលរយោៈពេលខ្លីផ្សេ', '20');
INSERT INTO `erp_gl_charts_tax` VALUES ('55', 'A47', 'Provision for changes and contigencies', 'សវិធានធនសំរាប់បន្ទុក និង​ហានិភ័យ', '20');
INSERT INTO `erp_gl_charts_tax` VALUES ('56', 'B22', 'Travelling and accommodation expenses', 'ចំណាយធើ្វដំណើរ និងចំណាយស្នាក់នៅ', '60');
INSERT INTO `erp_gl_charts_tax` VALUES ('57', 'A48', 'Profit tax payable', 'ពន្ធលើប្រាក់ចំណេញត្រូវបង់', '20');
INSERT INTO `erp_gl_charts_tax` VALUES ('58', 'A49', 'Other taxes payable', 'ពន្ទ-អាករផ្សេងៗត្រូវបង់', '20');
INSERT INTO `erp_gl_charts_tax` VALUES ('59', 'B23', 'Transporttation expenses', 'ចំណាយដឹកជញ្ជូន', '60');
INSERT INTO `erp_gl_charts_tax` VALUES ('60', 'A50', 'Differences arissing from currency translation in liabilities', 'លំអៀងពីការប្តូរប្រាក់នៃទ្រព្យសកម្ម', '20');
INSERT INTO `erp_gl_charts_tax` VALUES ('61', 'B24', 'Rental expenses', 'ចំណាយលើការជួល', '60');
INSERT INTO `erp_gl_charts_tax` VALUES ('62', 'B25', 'Repair anmaintenance expenses', 'ចំណាយលើការថែទាំ និងជួសជុល', '60');
INSERT INTO `erp_gl_charts_tax` VALUES ('63', 'B26', 'Entertament expenses', 'ចំណាយលើការកំសាន្តសប្យាយ', '60');
INSERT INTO `erp_gl_charts_tax` VALUES ('64', 'A22', 'Prepayment of profit tax credit', 'ឥណទានប្រាក់រំដោះពន្ធលើប្រាក់ចំណេញ', '11');
INSERT INTO `erp_gl_charts_tax` VALUES ('65', 'B27', 'Commission, advertising, and selling expenses', 'ចំណាយកំរៃជើងសារ ផ្សាយពាណិ្ជកម្ម និងចំណាយការលក់', '60');
INSERT INTO `erp_gl_charts_tax` VALUES ('66', 'A23', 'Value added tax credit', 'ឥណទានអាករលើតម្លៃបន្ថែម', '11');
INSERT INTO `erp_gl_charts_tax` VALUES ('67', 'B28', 'Other taxes expenses', 'ចំណាយបង់ពន្ធ និងអាករផេ្សងៗ', '60');
INSERT INTO `erp_gl_charts_tax` VALUES ('68', 'B29', 'Donation expenses', 'ចំណាយលើអំណោយ', '60');
INSERT INTO `erp_gl_charts_tax` VALUES ('69', 'A24', 'Other taxes credit', 'ឥណទានពន្ធ-អាករដដៃទៀត', '11');
INSERT INTO `erp_gl_charts_tax` VALUES ('70', 'B30', 'Management, consultant, other technical, and other similar services expenses', 'ចំណាយសេវាគ្រប់គ្រង ពិគ្រោះយោបល់ បចេ្វកទេស និងសេវាប', '60');
INSERT INTO `erp_gl_charts_tax` VALUES ('71', 'A25', 'Other current assets', 'ទ្រព្យសកម្មពេលខ្លីផ្សេងៗ', '11');
INSERT INTO `erp_gl_charts_tax` VALUES ('72', 'B31', 'Royalty expenses', 'ចំណាយលើសួយសារ', '60');
INSERT INTO `erp_gl_charts_tax` VALUES ('73', 'B32', 'Bad debts written off expenses', 'ចំណាយលើបំណុលទារមិនបាន', '60');
INSERT INTO `erp_gl_charts_tax` VALUES ('74', 'A26', 'Diffference arising from currency translation in assets', 'លំអៀងពីការប្តូរទ្រព្យសកម្ម', '11');
INSERT INTO `erp_gl_charts_tax` VALUES ('75', 'B33', 'Armortisation/depletion and depreciation expenses', 'ចំណាយរំលស់', '60');
INSERT INTO `erp_gl_charts_tax` VALUES ('76', 'B34', 'Increase /(decrease) in expenses', 'ការកើនឡើង /​ (ថយចុះ) សំវិធានធន', '60');
INSERT INTO `erp_gl_charts_tax` VALUES ('77', 'B35', 'Loss on siposal of fixed assets', 'ខាតពីការលក់ទ្រព្យសកម្មរយះពេលវែង', '60');
INSERT INTO `erp_gl_charts_tax` VALUES ('78', 'B36', 'Realised exchange loss', 'ខាតពីការប្តូរប្រាក់សំរេចបាន', '60');
INSERT INTO `erp_gl_charts_tax` VALUES ('79', 'B37', 'Unrealised exchange loss', 'ខាតពីការប្តូរប្រាក់មិនទាន់សំរេចបាន', '60');
INSERT INTO `erp_gl_charts_tax` VALUES ('80', 'B38', 'Other expenses', 'ចំណាយផេ្សងៗ', '60');
INSERT INTO `erp_gl_charts_tax` VALUES ('81', 'B40', 'Interest expenses paid to residents', 'ចំណាយការប្រាក់បង់អោយនិវាសនជន', '60');
INSERT INTO `erp_gl_charts_tax` VALUES ('82', 'B41', 'Interest expenses paid to non-residents', 'ចំណាយការប្រាក់បង់អោយអនិវាសនជន', '60');
INSERT INTO `erp_gl_charts_tax` VALUES ('83', 'A1', 'Non-current assets/ fixed assets', 'ទ្រព្យសកម្មរយៈពេលេវែង', '10');
INSERT INTO `erp_gl_charts_tax` VALUES ('84', 'A13', 'Current assets', 'ទ្រព្យសកម្មរយៈពេលខ្លី', '11');
INSERT INTO `erp_gl_charts_tax` VALUES ('85', 'A28', 'Equity', 'មូលនិធិ/ ទុនម្ចាស់ទ្រព្យ ', '30');
INSERT INTO `erp_gl_charts_tax` VALUES ('86', 'A36', 'Non-current liabilities', 'បំណុលរយៈពេលវែង', '21');
INSERT INTO `erp_gl_charts_tax` VALUES ('87', 'A41', 'Current liabilities', 'បំណុលរយៈពេលខ្លី', '20');
INSERT INTO `erp_gl_charts_tax` VALUES ('88', 'B0', 'Operating revenue', 'ចំណូលប្រតិបត្តិការ', '30');
INSERT INTO `erp_gl_charts_tax` VALUES ('89', 'B7', 'Other revenue', 'ចំណូលផ្សេងៗ', '70');
INSERT INTO `erp_gl_charts_tax` VALUES ('90', 'B19', 'Operating expenses', 'ចំណាយប្រតិបតិ្តការ', '60');

-- ----------------------------
-- Table structure for erp_gl_sections
-- ----------------------------
DROP TABLE IF EXISTS `erp_gl_sections`;
CREATE TABLE `erp_gl_sections` (
  `sectionid` int(11) NOT NULL DEFAULT '0',
  `sectionname` text,
  `sectionname_kh` text,
  `AccountType` char(2) DEFAULT NULL,
  `description` text,
  `pandl` int(11) DEFAULT '0',
  `order_stat` int(11) DEFAULT '0',
  PRIMARY KEY (`sectionid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_gl_sections
-- ----------------------------
INSERT INTO `erp_gl_sections` VALUES ('10', 'CURRENT ASSETS', 'ទ្រព្យសកម្មរយះពេលខ្លី', 'AS', 'CURRENT ASSETS', '0', '10');
INSERT INTO `erp_gl_sections` VALUES ('11', 'FIXED ASSETS', 'ទ្រព្យសកម្មរយះពេលវែង', 'AS', 'FIXED ASSETS', '0', '11');
INSERT INTO `erp_gl_sections` VALUES ('20', 'CURRENT LIABILITIES', 'បំណុលរយះពេលខ្លី', 'LI', 'CURRENT LIABILITIES', '0', '20');
INSERT INTO `erp_gl_sections` VALUES ('21', 'NON-CURRENT LIABILITIES', 'បំណុលរយះពេលវែង', 'LI', 'NON-CURRENT LIABILITIES', '0', '21');
INSERT INTO `erp_gl_sections` VALUES ('30', 'EQUITY AND RETAINED EARNING', 'មូលនិធិ/ទុនម្ចាស់ទ្រព្យ', 'EQ', 'EQUITY AND RETAINED EARNING', '0', '30');
INSERT INTO `erp_gl_sections` VALUES ('40', 'INCOME', 'ចំណូលប្រតិបត្តិការ', 'RE', 'INCOME', '1', '40');
INSERT INTO `erp_gl_sections` VALUES ('50', 'COST OF GOODS SOLD', 'ចំណាយថ្លៃដើម', 'CO', 'COST OF GOODS SOLD', '1', '50');
INSERT INTO `erp_gl_sections` VALUES ('60', 'OPERATING EXPENSES', 'ចំណាយប្រតិបត្តិការ', 'EX', 'OPERATING EXPENSES', '1', '60');
INSERT INTO `erp_gl_sections` VALUES ('70', 'OTHER INCOME', 'ចំណូលផ្សេងៗ', 'OI', 'OTHER INCOME', '1', '70');
INSERT INTO `erp_gl_sections` VALUES ('80', 'OTHER EXPENSE', 'ចំណាយផ្សេងៗ', 'OX', 'OTHER EXPENSE', '1', '80');
INSERT INTO `erp_gl_sections` VALUES ('90', 'GAIN & LOSS', 'ចំណេញខាត', 'GL', 'GAIN & LOSS', '1', '90');

-- ----------------------------
-- Table structure for erp_gl_trans
-- ----------------------------
DROP TABLE IF EXISTS `erp_gl_trans`;
CREATE TABLE `erp_gl_trans` (
  `tran_id` int(11) NOT NULL AUTO_INCREMENT,
  `tran_type` varchar(20) DEFAULT '0',
  `tran_no` bigint(20) NOT NULL DEFAULT '1',
  `tran_date` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `sectionid` int(11) DEFAULT '0',
  `account_code` int(19) DEFAULT '0',
  `narrative` varchar(100) DEFAULT '',
  `amount` decimal(25,4) DEFAULT '0.0000',
  `reference_no` varchar(55) NOT NULL DEFAULT '',
  `description` varchar(250) DEFAULT '',
  `biller_id` int(11) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `bank` tinyint(3) DEFAULT '0',
  `gov_tax` tinyint(3) DEFAULT '0',
  `reference_gov_tax` varchar(55) DEFAULT '',
  `people_id` int(11) DEFAULT NULL,
  `invoice_ref` varchar(55) DEFAULT NULL,
  `ref_type` int(11) DEFAULT NULL,
  `created_name` varchar(100) DEFAULT NULL,
  `created_type` varchar(10) DEFAULT NULL,
  `people` varchar(100) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `status_tax` varchar(50) DEFAULT NULL,
  `sale_id` int(11) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `payment_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`tran_id`),
  KEY `Account` (`account_code`) USING BTREE,
  KEY `TranDate` (`tran_date`) USING BTREE,
  KEY `TypeNo` (`tran_no`) USING BTREE,
  KEY `Type_and_Number` (`tran_type`,`tran_no`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_gl_trans
-- ----------------------------
INSERT INTO `erp_gl_trans` VALUES ('1', 'PURCHASES', '1', '2017-08-25 00:00:00', '20', '201100', 'Accounts Payable', '-231.0800', 'PO/1708/00001', '', '1', '1', null, '0', '0', '', null, null, null, null, null, null, null, null, null, null, null);
INSERT INTO `erp_gl_trans` VALUES ('2', 'PURCHASES', '1', '2017-08-25 00:00:00', '50', '500106', 'Purchase Discount', '-8.4300', 'PO/1708/00001', '', '1', '1', null, '0', '0', '', null, null, null, null, null, null, null, null, null, null, null);
INSERT INTO `erp_gl_trans` VALUES ('3', 'PURCHASES', '1', '2017-08-25 00:00:00', '10', '100441', 'VAT Input', '21.0100', 'PO/1708/00001', '', '1', '1', null, '0', '0', '', null, null, null, null, null, null, null, null, null, null, null);
INSERT INTO `erp_gl_trans` VALUES ('4', 'PURCHASES', '1', '2017-08-25 00:00:00', '10', '100430', 'Inventory', '0.0000', 'PO/1708/00001', null, '1', null, null, '0', '0', '', null, null, null, null, null, null, null, null, null, null, null);
INSERT INTO `erp_gl_trans` VALUES ('5', 'PURCHASES', '1', '2017-08-25 00:00:00', '10', '100430', 'Inventory', '46.1180', 'PO/1708/00001', null, '1', null, null, '0', '0', '', null, null, null, null, null, null, null, null, null, null, null);
INSERT INTO `erp_gl_trans` VALUES ('6', 'PURCHASES', '1', '2017-08-25 00:00:00', '10', '100430', 'Inventory', '49.3207', 'PO/1708/00001', null, '1', null, null, '0', '0', '', null, null, null, null, null, null, null, null, null, null, null);
INSERT INTO `erp_gl_trans` VALUES ('7', 'PURCHASES', '1', '2017-08-25 00:00:00', '10', '100430', 'Inventory', '58.9123', 'PO/1708/00001', null, '1', null, null, '0', '0', '', null, null, null, null, null, null, null, null, null, null, null);
INSERT INTO `erp_gl_trans` VALUES ('8', 'PURCHASES', '1', '2017-08-25 00:00:00', '10', '100430', 'Inventory', '64.1490', 'PO/1708/00001', null, '1', null, null, '0', '0', '', null, null, null, null, null, null, null, null, null, null, null);
INSERT INTO `erp_gl_trans` VALUES ('9', 'PURCHASES', '2', '2017-08-25 00:00:00', '20', '201100', 'Accounts Payable', '-231.0800', 'PO/1708/00002', '', '1', '1', null, '0', '0', '', null, null, null, null, null, null, null, null, null, null, null);
INSERT INTO `erp_gl_trans` VALUES ('10', 'PURCHASES', '2', '2017-08-25 00:00:00', '50', '500106', 'Purchase Discount', '-8.4300', 'PO/1708/00002', '', '1', '1', null, '0', '0', '', null, null, null, null, null, null, null, null, null, null, null);
INSERT INTO `erp_gl_trans` VALUES ('11', 'PURCHASES', '2', '2017-08-25 00:00:00', '10', '100441', 'VAT Input', '21.0100', 'PO/1708/00002', '', '1', '1', null, '0', '0', '', null, null, null, null, null, null, null, null, null, null, null);
INSERT INTO `erp_gl_trans` VALUES ('12', 'PURCHASES', '2', '2017-08-25 00:00:00', '10', '100430', 'Inventory', '46.1180', 'PO/1708/00002', null, '1', null, null, '0', '0', '', null, null, null, null, null, null, null, null, null, null, null);
INSERT INTO `erp_gl_trans` VALUES ('13', 'PURCHASES', '2', '2017-08-25 00:00:00', '10', '100430', 'Inventory', '49.3207', 'PO/1708/00002', null, '1', null, null, '0', '0', '', null, null, null, null, null, null, null, null, null, null, null);
INSERT INTO `erp_gl_trans` VALUES ('14', 'PURCHASES', '2', '2017-08-25 00:00:00', '10', '100430', 'Inventory', '58.9123', 'PO/1708/00002', null, '1', null, null, '0', '0', '', null, null, null, null, null, null, null, null, null, null, null);
INSERT INTO `erp_gl_trans` VALUES ('15', 'PURCHASES', '2', '2017-08-25 00:00:00', '10', '100430', 'Inventory', '64.1490', 'PO/1708/00002', null, '1', null, null, '0', '0', '', null, null, null, null, null, null, null, null, null, null, null);
INSERT INTO `erp_gl_trans` VALUES ('16', 'PURCHASES', '3', '2017-08-25 18:05:00', '20', '201100', 'Accounts Payable', '231.0800', 'PV/1708/00001', '', '1', '1', null, '1', '0', '', null, null, null, null, null, null, null, null, null, null, '1');
INSERT INTO `erp_gl_trans` VALUES ('17', 'PURCHASES', '3', '2017-08-25 18:05:00', '10', '100100', 'Cash', '-231.0800', 'PV/1708/00001', '', '1', '1', null, '1', '0', '', null, null, null, null, null, null, null, null, null, null, '1');
INSERT INTO `erp_gl_trans` VALUES ('18', 'PURCHASES', '4', '2017-08-25 18:05:00', '20', '201100', 'Accounts Payable', '168.9200', 'PV/1708/00001', '', '1', '1', null, '1', '0', '', null, null, null, null, null, null, null, null, null, null, '2');
INSERT INTO `erp_gl_trans` VALUES ('19', 'PURCHASES', '4', '2017-08-25 18:05:00', '10', '100100', 'Cash', '-168.9200', 'PV/1708/00001', '', '1', '1', null, '1', '0', '', null, null, null, null, null, null, null, null, null, null, '2');
INSERT INTO `erp_gl_trans` VALUES ('20', 'CONVERT', '5', '2017-08-25 18:39:00', '10', '100430', 'Inventory', '-128.2100', 'CON/1708/00001', '', '1', '1', null, '0', '0', '', null, null, null, null, null, null, null, null, null, null, null);
INSERT INTO `erp_gl_trans` VALUES ('21', 'CONVERT', '5', '2017-08-25 18:39:00', '10', '100430', 'Inventory', '-58.8700', 'CON/1708/00001', '', '1', '1', null, '0', '0', '', null, null, null, null, null, null, null, null, null, null, null);
INSERT INTO `erp_gl_trans` VALUES ('22', 'CONVERT', '5', '2017-08-25 18:39:00', '10', '100430', 'Inventory', '-38.1600', 'CON/1708/00001', '', '1', '1', null, '0', '0', '', null, null, null, null, null, null, null, null, null, null, null);
INSERT INTO `erp_gl_trans` VALUES ('23', 'CONVERT', '5', '2017-08-25 18:39:00', '10', '100430', 'Inventory', '218.6800', 'CON/1708/00001', '', '1', '1', null, '0', '0', '', null, null, null, null, null, null, null, null, null, null, null);
INSERT INTO `erp_gl_trans` VALUES ('24', 'CONVERT', '5', '2017-08-25 18:39:00', '10', '100430', 'Inventory', '6.5604', 'CON/1708/00001', '', '1', '1', null, '0', '0', '', null, null, null, null, null, null, null, null, null, null, null);
INSERT INTO `erp_gl_trans` VALUES ('25', 'CONVERT', '6', '2017-08-25 18:49:00', '10', '100430', 'Inventory', '-12.8210', 'CON/1708/00002', '', '1', '1', null, '0', '0', '', null, null, null, null, null, null, null, null, null, null, null);
INSERT INTO `erp_gl_trans` VALUES ('26', 'CONVERT', '6', '2017-08-25 18:49:00', '10', '100430', 'Inventory', '-5.8870', 'CON/1708/00002', '', '1', '1', null, '0', '0', '', null, null, null, null, null, null, null, null, null, null, null);
INSERT INTO `erp_gl_trans` VALUES ('27', 'CONVERT', '6', '2017-08-25 18:49:00', '10', '100430', 'Inventory', '17.0070', 'CON/1708/00002', '', '1', '1', null, '0', '0', '', null, null, null, null, null, null, null, null, null, null, null);
INSERT INTO `erp_gl_trans` VALUES ('28', 'CONVERT', '6', '2017-08-25 18:49:00', '10', '100430', 'Inventory', '1.7007', 'CON/1708/00002', '', '1', '1', null, '0', '0', '', null, null, null, null, null, null, null, null, null, null, null);
INSERT INTO `erp_gl_trans` VALUES ('29', 'STOCK_ADJUST', '7', '2017-08-25 18:51:00', '50', '500107', 'Inventory Adjustment', '-11.5389', '1708/00001', '', '1', '1', null, '0', '0', '', null, null, null, null, null, null, null, null, null, null, null);
INSERT INTO `erp_gl_trans` VALUES ('30', 'STOCK_ADJUST', '7', '2017-08-25 18:43:40', '50', '500107', 'Inventory Adjustment', '11.5389', '1708/00001', '', '1', '1', null, '0', '0', '', null, null, null, null, null, null, null, null, null, null, null);
INSERT INTO `erp_gl_trans` VALUES ('31', 'STOCK_ADJUST', '8', '2017-08-25 18:53:00', '50', '500107', 'Inventory Adjustment', '-12.8210', '1708/00002', '', '1', '1', null, '0', '0', '', null, null, null, null, null, null, null, null, null, null, null);
INSERT INTO `erp_gl_trans` VALUES ('32', 'STOCK_ADJUST', '8', '2017-08-25 18:44:30', '50', '500107', 'Inventory Adjustment', '12.8210', '1708/00002', '', '1', '1', null, '0', '0', '', null, null, null, null, null, null, null, null, null, null, null);
INSERT INTO `erp_gl_trans` VALUES ('33', 'USING STOCK', '9', '2017-08-25 00:00:00', '11', '110205', 'IT Equipment & Computer', '65.6040', 'ES/1708/00001', '', '1', '1', null, '0', '0', '', null, null, null, null, null, null, null, null, null, null, null);
INSERT INTO `erp_gl_trans` VALUES ('34', 'USING STOCK', '9', '2017-08-25 00:00:00', '10', '100430', 'Inventory', '-65.6040', 'ES/1708/00001', '', '1', '1', null, '0', '0', '', null, null, null, null, null, null, null, null, null, null, null);

-- ----------------------------
-- Table structure for erp_gl_trans_audit
-- ----------------------------
DROP TABLE IF EXISTS `erp_gl_trans_audit`;
CREATE TABLE `erp_gl_trans_audit` (
  `tran_id` int(11) NOT NULL,
  `tran_type` varchar(20) DEFAULT '0',
  `tran_no` bigint(20) NOT NULL DEFAULT '1',
  `tran_date` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `sectionid` int(11) DEFAULT '0',
  `account_code` int(19) DEFAULT '0',
  `narrative` varchar(100) DEFAULT '',
  `amount` decimal(25,4) DEFAULT '0.0000',
  `reference_no` varchar(55) NOT NULL DEFAULT '',
  `description` varchar(250) DEFAULT '',
  `biller_id` int(11) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `bank` tinyint(3) DEFAULT '0',
  `gov_tax` tinyint(3) DEFAULT '0',
  `reference_gov_tax` varchar(55) DEFAULT '',
  `people_id` int(11) DEFAULT NULL,
  `invoice_ref` varchar(55) DEFAULT NULL,
  `ref_type` int(11) DEFAULT NULL,
  `created_name` varchar(100) DEFAULT NULL,
  `created_type` varchar(10) DEFAULT NULL,
  `people` varchar(100) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `status_tax` varchar(50) DEFAULT NULL,
  `audit_id` int(11) NOT NULL AUTO_INCREMENT,
  `audit_created_by` int(11) NOT NULL,
  `audit_record_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `audit_type` varchar(55) NOT NULL,
  PRIMARY KEY (`audit_id`),
  KEY `Account` (`account_code`) USING BTREE,
  KEY `TranDate` (`tran_date`) USING BTREE,
  KEY `TypeNo` (`tran_no`) USING BTREE,
  KEY `Type_and_Number` (`tran_type`,`tran_no`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_gl_trans_audit
-- ----------------------------

-- ----------------------------
-- Table structure for erp_groups
-- ----------------------------
DROP TABLE IF EXISTS `erp_groups`;
CREATE TABLE `erp_groups` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `description` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_groups
-- ----------------------------
INSERT INTO `erp_groups` VALUES ('1', 'owner', 'Owner');
INSERT INTO `erp_groups` VALUES ('2', 'admin', 'Administrator');
INSERT INTO `erp_groups` VALUES ('3', 'customer', 'Customer');
INSERT INTO `erp_groups` VALUES ('4', 'supplier', 'Supplier');
INSERT INTO `erp_groups` VALUES ('5', 'cashier', 'Cashier');
INSERT INTO `erp_groups` VALUES ('6', 'stock', 'Stocker');
INSERT INTO `erp_groups` VALUES ('7', 'manager', 'Manager');
INSERT INTO `erp_groups` VALUES ('8', 'channthy', 'Channthy Permission');

-- ----------------------------
-- Table structure for erp_group_areas
-- ----------------------------
DROP TABLE IF EXISTS `erp_group_areas`;
CREATE TABLE `erp_group_areas` (
  `areas_g_code` int(3) NOT NULL AUTO_INCREMENT,
  `areas_group` varchar(100) DEFAULT '',
  PRIMARY KEY (`areas_g_code`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_group_areas
-- ----------------------------
INSERT INTO `erp_group_areas` VALUES ('1', 'Takeo');

-- ----------------------------
-- Table structure for erp_inventory_valuation_details
-- ----------------------------
DROP TABLE IF EXISTS `erp_inventory_valuation_details`;
CREATE TABLE `erp_inventory_valuation_details` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `biller_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_code` varchar(50) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `category_id` int(11) NOT NULL,
  `sub_category_id` int(11) DEFAULT NULL,
  `warehouse_id` int(11) NOT NULL,
  `reference_no` varchar(50) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `quantity` decimal(15,4) DEFAULT NULL,
  `cost` decimal(15,4) DEFAULT NULL,
  `qty_on_hand` decimal(15,4) DEFAULT NULL,
  `avg_cost` decimal(15,4) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `field_id` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `product_id` (`product_id`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_inventory_valuation_details
-- ----------------------------
INSERT INTO `erp_inventory_valuation_details` VALUES ('1', '1', '5', 'RW01', 'Suger', '3', null, '1', '1708/00001', 'PURCHASE', '2017-08-25 18:43:40', 'General Supplier', '1.0000', '2.1868', '0.0000', '2.1868', '1', null, '2017-08-25 19:05:58', '1');
INSERT INTO `erp_inventory_valuation_details` VALUES ('2', '1', '3', 'DK03', 'Pepsi', '1', null, '1', '1708/00002', 'PURCHASE', '2017-08-25 18:44:31', 'General Supplier', '120.0000', '1.2821', '0.0000', '1.2821', '1', null, '2017-08-25 18:44:31', '2');
INSERT INTO `erp_inventory_valuation_details` VALUES ('3', '1', '2', 'DK02', 'Coco cola', '1', null, '1', 'PO/1708/00001', 'PURCHASE', '2017-08-25 00:00:00', 'General Supplier', '120.0000', '0.3813', '0.0000', '0.3813', '1', null, '2017-08-25 17:42:57', '3');
INSERT INTO `erp_inventory_valuation_details` VALUES ('4', '1', '6', 'RW02', 'Salt', '3', null, '1', 'PO/1708/00001', 'PURCHASE', '2017-08-25 00:00:00', 'General Supplier', '50.0000', '1.1766', '0.0000', '1.1766', '1', null, '2017-08-25 17:42:57', '4');
INSERT INTO `erp_inventory_valuation_details` VALUES ('5', '1', '5', 'RW01', 'Suger', '3', null, '1', 'PO/1708/00001', 'PURCHASE', '2017-08-25 00:00:00', 'General Supplier', '50.0000', '1.2812', '0.0000', '1.2812', '1', null, '2017-08-25 17:42:58', '5');
INSERT INTO `erp_inventory_valuation_details` VALUES ('6', '1', '3', 'DK03', 'Pepsi', '1', null, '1', 'PO/1708/00002', 'PURCHASE', '2017-08-25 00:00:00', 'General Supplier', '120.0000', '0.3568', '120.0000', '0.3568', '1', null, '2017-08-25 17:55:00', '6');
INSERT INTO `erp_inventory_valuation_details` VALUES ('7', '1', '2', 'DK02', 'Coco cola', '1', null, '1', 'PO/1708/00002', 'PURCHASE', '2017-08-25 00:00:00', 'General Supplier', '120.0000', '0.3816', '120.0000', '0.3816', '1', null, '2017-08-25 17:55:01', '7');
INSERT INTO `erp_inventory_valuation_details` VALUES ('8', '1', '6', 'RW02', 'Salt', '3', null, '1', 'PO/1708/00002', 'PURCHASE', '2017-08-25 00:00:00', 'General Supplier', '50.0000', '1.1774', '50.0000', '1.1774', '1', null, '2017-08-25 17:55:01', '8');
INSERT INTO `erp_inventory_valuation_details` VALUES ('9', '1', '5', 'RW01', 'Suger', '3', null, '1', 'PO/1708/00002', 'PURCHASE', '2017-08-25 00:00:00', 'General Supplier', '50.0000', '1.2821', '51.0000', '1.2821', '1', null, '2017-08-25 17:55:02', '9');
INSERT INTO `erp_inventory_valuation_details` VALUES ('10', '1', '5', 'RW01', 'Suger', '3', null, '1', '1708/00001', 'CONVERT', '2017-08-25 18:43:40', null, '100.0000', '2.1868', '101.0000', '2.1868', '1', null, '2017-08-25 19:05:58', '1');
INSERT INTO `erp_inventory_valuation_details` VALUES ('11', '1', '6', 'RW02', 'Salt', '3', null, '1', '1708/00002', 'CONVERT', '2017-08-25 18:44:31', null, '50.0000', '1.2821', '100.0000', '1.2821', '1', null, '2017-08-25 18:44:31', '2');
INSERT INTO `erp_inventory_valuation_details` VALUES ('12', '1', '2', 'DK02', 'Coco cola', '1', null, '1', 'CON/1708/00001', 'CONVERT', '2017-08-25 18:39:00', null, '100.0000', '0.0000', '240.0000', '0.0000', '1', null, null, '3');
INSERT INTO `erp_inventory_valuation_details` VALUES ('13', '1', '7', 'FP01', 'Food1', '3', null, '1', 'CON/1708/00001', 'CONVERT', '2017-08-25 18:39:00', null, '100.0000', '0.0000', '0.0000', '0.0000', '1', null, null, '4');
INSERT INTO `erp_inventory_valuation_details` VALUES ('14', '1', '8', 'FDC', 'F00d1 C', '4', null, '1', 'CON/1708/00001', 'CONVERT', '2017-08-25 18:39:00', null, '3.0000', '0.0000', '0.0000', '0.0000', '1', null, null, '5');
INSERT INTO `erp_inventory_valuation_details` VALUES ('15', '1', '5', 'RW01', 'Suger', '3', null, '1', 'CON/1708/00002', 'CONVERT', '2017-08-25 18:49:00', null, '10.0000', '1.0000', '1.0000', '1.0000', '1', null, null, '6');
INSERT INTO `erp_inventory_valuation_details` VALUES ('16', '1', '6', 'RW02', 'Salt', '3', null, '1', 'CON/1708/00002', 'CONVERT', '2017-08-25 18:49:00', null, '5.0000', '1.0000', '50.0000', '1.0000', '1', null, null, '7');
INSERT INTO `erp_inventory_valuation_details` VALUES ('17', '1', '1', 'DK001', 'Mocha', '1', null, '1', 'CON/1708/00002', 'CONVERT', '2017-08-25 18:49:00', null, '10.0000', '0.0000', '0.0000', '0.0000', '1', null, null, '8');
INSERT INTO `erp_inventory_valuation_details` VALUES ('18', '1', '8', 'FDC', 'F00d1 C', '4', null, '1', 'CON/1708/00002', 'CONVERT', '2017-08-25 18:49:00', null, '1.0000', '2.0000', '3.0000', '2.0000', '1', null, null, '9');
INSERT INTO `erp_inventory_valuation_details` VALUES ('19', '1', '5', '', '', '3', null, '1', '1708/00001', 'ADJUSTMENT', '2017-08-25 18:43:40', null, '9.0000', '2.1868', '-9.0000', '2.1868', '1', null, '2017-08-25 19:05:58', '1');
INSERT INTO `erp_inventory_valuation_details` VALUES ('20', '1', '5', '', '', '3', null, '1', '1708/00002', 'ADJUSTMENT', '2017-08-25 18:44:31', null, '10.0000', '1.2821', '0.0000', '1.2821', '1', null, '2017-08-25 18:44:31', '2');
INSERT INTO `erp_inventory_valuation_details` VALUES ('21', '1', '7', 'FP01', 'Food1', '3', null, '1', 'ES/1708/00001', 'USING STOCK', '2017-08-25 00:00:00', null, '30.0000', '2.1868', '100.0000', '2.1868', '1', null, '2017-08-25 19:05:58', '1');

-- ----------------------------
-- Table structure for erp_loans
-- ----------------------------
DROP TABLE IF EXISTS `erp_loans`;
CREATE TABLE `erp_loans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `period` smallint(6) NOT NULL,
  `dateline` date NOT NULL,
  `sale_id` int(11) DEFAULT NULL,
  `reference_no` varchar(50) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `type` varchar(50) DEFAULT NULL,
  `rated` varchar(255) DEFAULT NULL,
  `payment` decimal(25,10) NOT NULL,
  `principle` decimal(25,10) NOT NULL,
  `interest` decimal(25,10) NOT NULL,
  `balance` decimal(25,10) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `paid_by` varchar(50) DEFAULT NULL,
  `paid_amount` decimal(25,4) NOT NULL,
  `paid_date` datetime DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `account_code` varchar(20) DEFAULT NULL,
  `bank_code` varchar(20) DEFAULT NULL,
  `biller_id` int(11) DEFAULT NULL,
  `updated_by` varchar(55) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_loans
-- ----------------------------

-- ----------------------------
-- Table structure for erp_login_attempts
-- ----------------------------
DROP TABLE IF EXISTS `erp_login_attempts`;
CREATE TABLE `erp_login_attempts` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varbinary(16) NOT NULL,
  `login` varchar(100) NOT NULL,
  `time` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_login_attempts
-- ----------------------------

-- ----------------------------
-- Table structure for erp_marchine
-- ----------------------------
DROP TABLE IF EXISTS `erp_marchine`;
CREATE TABLE `erp_marchine` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` varchar(200) DEFAULT NULL,
  `type` varchar(20) DEFAULT '0',
  `biller_id` int(11) DEFAULT '0',
  `status` int(11) DEFAULT '0',
  `13` int(11) DEFAULT '0',
  `15` int(11) DEFAULT '0',
  `25` int(11) DEFAULT '0',
  `30` int(11) DEFAULT '0',
  `50` int(11) DEFAULT '0',
  `60` int(11) DEFAULT '0',
  `76` int(11) DEFAULT '0',
  `80` int(11) DEFAULT '0',
  `100` int(11) DEFAULT '0',
  `120` int(11) DEFAULT '0',
  `150` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_marchine
-- ----------------------------

-- ----------------------------
-- Table structure for erp_marchine_logs
-- ----------------------------
DROP TABLE IF EXISTS `erp_marchine_logs`;
CREATE TABLE `erp_marchine_logs` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `marchine_id` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `old_number` int(11) DEFAULT NULL,
  `new_number` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_marchine_logs
-- ----------------------------

-- ----------------------------
-- Table structure for erp_migrations
-- ----------------------------
DROP TABLE IF EXISTS `erp_migrations`;
CREATE TABLE `erp_migrations` (
  `version` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_migrations
-- ----------------------------

-- ----------------------------
-- Table structure for erp_notifications
-- ----------------------------
DROP TABLE IF EXISTS `erp_notifications`;
CREATE TABLE `erp_notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comment` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `from_date` datetime DEFAULT NULL,
  `till_date` datetime DEFAULT NULL,
  `scope` tinyint(1) NOT NULL DEFAULT '3',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_notifications
-- ----------------------------

-- ----------------------------
-- Table structure for erp_order_ref
-- ----------------------------
DROP TABLE IF EXISTS `erp_order_ref`;
CREATE TABLE `erp_order_ref` (
  `ref_id` int(11) NOT NULL AUTO_INCREMENT,
  `biller_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `so` int(11) NOT NULL DEFAULT '1' COMMENT 'sale order',
  `qu` int(11) NOT NULL DEFAULT '1' COMMENT 'quote',
  `po` int(11) NOT NULL DEFAULT '1' COMMENT 'purchase order',
  `to` int(11) NOT NULL DEFAULT '1' COMMENT 'transfer to',
  `pos` int(11) NOT NULL DEFAULT '1' COMMENT 'pos',
  `do` int(11) NOT NULL DEFAULT '1' COMMENT 'delivery order',
  `pay` int(11) NOT NULL DEFAULT '1' COMMENT 'expense payment',
  `re` int(11) NOT NULL DEFAULT '1' COMMENT 'sale return',
  `ex` int(11) NOT NULL DEFAULT '1' COMMENT 'expense',
  `sp` int(11) NOT NULL DEFAULT '1' COMMENT 'sale payement',
  `pp` int(11) NOT NULL DEFAULT '1' COMMENT 'purchase payment',
  `sl` int(11) NOT NULL DEFAULT '1' COMMENT 'sale loan',
  `tr` int(11) NOT NULL DEFAULT '1' COMMENT 'transfer',
  `rep` int(11) NOT NULL DEFAULT '1' COMMENT 'purchase return',
  `con` int(11) NOT NULL DEFAULT '1' COMMENT 'convert product',
  `pj` int(11) NOT NULL DEFAULT '1' COMMENT 'prouduct job',
  `sd` int(11) NOT NULL DEFAULT '1',
  `es` int(11) NOT NULL DEFAULT '1',
  `esr` int(11) NOT NULL DEFAULT '1',
  `sao` int(11) NOT NULL DEFAULT '1',
  `poa` int(11) NOT NULL DEFAULT '1',
  `pq` int(11) NOT NULL DEFAULT '1',
  `jr` int(11) NOT NULL,
  `qa` int(11) NOT NULL DEFAULT '1',
  `st` int(11) DEFAULT '1',
  `adc` int(11) DEFAULT '1',
  `tx` int(11) unsigned NOT NULL DEFAULT '1' COMMENT 'TAX',
  `pro` int(11) NOT NULL,
  `cus` int(11) NOT NULL,
  `sup` int(11) NOT NULL,
  `emp` int(11) NOT NULL,
  `pn` int(11) DEFAULT '1',
  PRIMARY KEY (`ref_id`)
) ENGINE=InnoDB AUTO_INCREMENT=321 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_order_ref
-- ----------------------------
INSERT INTO `erp_order_ref` VALUES ('1', '1', '2016-02-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('2', '1', '2016-03-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('3', '1', '2016-04-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('4', '1', '2016-05-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('5', '1', '2016-06-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('6', '1', '2016-07-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('7', '1', '2016-08-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('8', '1', '2016-09-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('9', '1', '2016-10-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('10', '1', '2016-11-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('11', '1', '2016-12-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('12', '1', '2017-01-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('13', '1', '2017-02-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('14', '1', '2017-03-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('15', '1', '2017-04-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('16', '1', '2017-05-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('17', '1', '2017-06-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('18', '1', '2017-07-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('19', '1', '2017-08-01', '1', '1', '3', '1', '1', '1', '1', '1', '1', '1', '2', '1', '9', '1', '3', '1', '1', '2', '1', '1', '2', '2', '1', '3', '2', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('20', '1', '2017-09-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('21', '1', '2017-10-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('22', '1', '2017-11-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('23', '1', '2017-12-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('24', '1', '2018-01-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('25', '1', '2018-02-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('26', '1', '2018-03-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('27', '1', '2018-04-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('28', '1', '2018-05-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('29', '1', '2018-06-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('30', '1', '2018-07-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('31', '1', '2018-08-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('32', '1', '2018-09-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('33', '5', '2016-02-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('34', '5', '2016-03-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('35', '5', '2016-04-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('36', '5', '2016-05-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('37', '5', '2016-06-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('38', '5', '2016-07-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('39', '5', '2016-08-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('40', '5', '2016-09-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('41', '5', '2016-10-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('42', '5', '2016-11-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('43', '5', '2016-12-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('44', '5', '2017-01-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('45', '5', '2017-02-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('46', '5', '2017-03-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('47', '5', '2017-04-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('48', '5', '2017-05-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('49', '5', '2017-06-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('50', '5', '2017-07-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('51', '5', '2017-08-01', '1', '1', '3', '1', '1', '1', '1', '1', '1', '1', '2', '1', '9', '1', '3', '1', '1', '2', '1', '1', '2', '2', '1', '3', '2', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('52', '5', '2017-09-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('53', '5', '2017-10-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('54', '5', '2017-11-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('55', '5', '2017-12-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('56', '5', '2018-01-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('57', '5', '2018-02-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('58', '5', '2018-03-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('59', '5', '2018-04-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('60', '5', '2018-05-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('61', '5', '2018-06-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('62', '5', '2018-07-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('63', '5', '2018-08-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('64', '5', '2018-09-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('65', '25', '2016-02-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('66', '25', '2016-03-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('67', '25', '2016-04-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('68', '25', '2016-05-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('69', '25', '2016-06-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('70', '25', '2016-07-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('71', '25', '2016-08-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('72', '25', '2016-09-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('73', '25', '2016-10-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('74', '25', '2016-11-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('75', '25', '2016-12-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('76', '25', '2017-01-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('77', '25', '2017-02-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('78', '25', '2017-03-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('79', '25', '2017-04-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('80', '25', '2017-05-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('81', '25', '2017-06-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('82', '25', '2017-08-01', '1', '1', '3', '1', '1', '1', '1', '1', '1', '1', '2', '1', '9', '1', '3', '1', '1', '2', '1', '1', '2', '2', '1', '3', '2', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('83', '25', '2017-09-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('84', '25', '2017-10-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('85', '25', '2017-11-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('86', '25', '2017-12-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('87', '25', '2018-01-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('88', '25', '2018-02-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('89', '25', '2018-03-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('90', '25', '2018-04-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('91', '25', '2018-05-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('92', '25', '2018-06-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('93', '25', '2018-07-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('94', '25', '2018-08-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('95', '25', '2018-09-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('96', '25', '2017-07-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('97', '26', '2016-02-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('98', '26', '2016-03-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('99', '26', '2016-04-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('100', '26', '2016-05-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('101', '26', '2016-06-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('102', '26', '2016-07-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('103', '26', '2016-08-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('104', '26', '2016-09-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('105', '26', '2016-10-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('106', '26', '2016-11-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('107', '26', '2016-12-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('108', '26', '2017-01-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('109', '26', '2017-02-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('110', '26', '2017-03-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('111', '26', '2017-04-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('112', '26', '2017-05-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('113', '26', '2017-06-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('114', '26', '2017-07-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('115', '26', '2017-08-01', '1', '1', '3', '1', '1', '1', '1', '1', '1', '1', '2', '1', '9', '1', '3', '1', '1', '2', '1', '1', '2', '2', '1', '3', '2', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('116', '26', '2017-09-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('117', '26', '2017-10-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('118', '26', '2017-11-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('119', '26', '2017-12-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('120', '26', '2018-01-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('121', '26', '2018-02-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('122', '26', '2018-03-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('123', '26', '2018-04-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('124', '26', '2018-05-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('125', '26', '2018-06-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('126', '26', '2018-07-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('127', '26', '2018-08-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('128', '26', '2018-09-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('129', '27', '2016-02-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('130', '27', '2016-03-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('131', '27', '2016-04-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('132', '27', '2016-05-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('133', '27', '2016-06-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('134', '27', '2016-07-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('135', '27', '2016-08-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('136', '27', '2016-09-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('137', '27', '2016-10-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('138', '27', '2016-11-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('139', '27', '2016-12-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('140', '27', '2017-01-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('141', '27', '2017-02-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('142', '27', '2017-03-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('143', '27', '2017-04-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('144', '27', '2017-05-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('145', '27', '2017-06-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('146', '27', '2017-07-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('147', '27', '2017-08-01', '1', '1', '3', '1', '1', '1', '1', '1', '1', '1', '2', '1', '9', '1', '3', '1', '1', '2', '1', '1', '2', '2', '1', '3', '2', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('148', '27', '2017-09-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('149', '27', '2017-10-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('150', '27', '2017-11-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('151', '27', '2017-12-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('152', '27', '2018-01-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('153', '27', '2018-02-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('154', '27', '2018-03-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('155', '27', '2018-04-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('156', '27', '2018-05-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('157', '27', '2018-06-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('158', '27', '2018-07-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('159', '27', '2018-08-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('160', '27', '2018-09-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('161', '29', '2016-02-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('162', '29', '2016-03-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('163', '29', '2016-04-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('164', '29', '2016-05-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('165', '29', '2016-06-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('166', '29', '2016-07-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('167', '29', '2016-08-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('168', '29', '2016-09-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('169', '29', '2016-10-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('170', '29', '2016-11-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('171', '29', '2016-12-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('172', '29', '2017-01-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('173', '29', '2017-02-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('174', '29', '2017-03-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('175', '29', '2017-04-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('176', '29', '2017-05-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('177', '29', '2017-06-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('178', '29', '2017-07-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('179', '29', '2017-08-01', '1', '1', '3', '1', '1', '1', '1', '1', '1', '1', '2', '1', '9', '1', '3', '1', '1', '2', '1', '1', '2', '2', '1', '3', '2', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('180', '29', '2017-09-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('181', '29', '2017-10-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('182', '29', '2017-11-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('183', '29', '2017-12-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('184', '29', '2018-01-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('185', '29', '2018-02-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('186', '29', '2018-03-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('187', '29', '2018-04-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('188', '29', '2018-05-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('189', '29', '2018-06-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('190', '29', '2018-07-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('191', '29', '2018-08-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('192', '29', '2018-09-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('193', '67', '2016-02-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('194', '67', '2016-03-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('195', '67', '2016-04-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('196', '67', '2016-05-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('197', '67', '2016-06-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('198', '67', '2016-07-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('199', '67', '2016-08-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('200', '67', '2016-09-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('201', '67', '2016-10-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('202', '67', '2016-11-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('203', '67', '2016-12-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('204', '67', '2017-01-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('205', '67', '2017-02-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('206', '67', '2017-03-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('207', '67', '2017-04-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('208', '67', '2017-05-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('209', '67', '2017-06-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('210', '67', '2017-07-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('211', '67', '2017-08-01', '1', '1', '3', '1', '1', '1', '1', '1', '1', '1', '2', '1', '9', '1', '3', '1', '1', '2', '1', '1', '2', '2', '1', '3', '2', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('212', '67', '2017-09-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('213', '67', '2017-10-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('214', '67', '2017-11-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('215', '67', '2017-12-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('216', '67', '2018-01-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('217', '67', '2018-02-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('218', '67', '2018-03-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('219', '67', '2018-04-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('220', '67', '2018-05-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('221', '67', '2018-06-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('222', '67', '2018-07-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('223', '67', '2018-08-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('224', '67', '2018-09-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('225', '68', '2016-02-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('226', '68', '2016-03-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('227', '68', '2016-04-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('228', '68', '2016-05-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('229', '68', '2016-06-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('230', '68', '2016-07-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('231', '68', '2016-08-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('232', '68', '2016-09-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('233', '68', '2016-10-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('234', '68', '2016-11-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('235', '68', '2016-12-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('236', '68', '2017-01-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('237', '68', '2017-02-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('238', '68', '2017-03-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('239', '68', '2017-04-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('240', '68', '2017-05-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('241', '68', '2017-06-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('242', '68', '2017-07-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('243', '68', '2017-08-01', '1', '1', '3', '1', '1', '1', '1', '1', '1', '1', '2', '1', '9', '1', '3', '1', '1', '2', '1', '1', '2', '2', '1', '3', '2', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('244', '68', '2017-09-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('245', '68', '2017-10-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('246', '68', '2017-11-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('247', '68', '2017-12-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('248', '68', '2018-01-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('249', '68', '2018-02-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('250', '68', '2018-03-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('251', '68', '2018-04-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('252', '68', '2018-05-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('253', '68', '2018-06-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('254', '68', '2018-07-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('255', '68', '2018-08-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('256', '68', '2018-09-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('257', '69', '2016-02-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('258', '69', '2016-03-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('259', '69', '2016-04-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('260', '69', '2016-05-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('261', '69', '2016-06-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('262', '69', '2016-07-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('263', '69', '2016-08-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('264', '69', '2016-09-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('265', '69', '2016-10-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('266', '69', '2016-11-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('267', '69', '2016-12-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('268', '69', '2017-01-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('269', '69', '2017-02-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('270', '69', '2017-03-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('271', '69', '2017-04-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('272', '69', '2017-05-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('273', '69', '2017-06-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('274', '69', '2017-07-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('275', '69', '2017-08-01', '1', '1', '3', '1', '1', '1', '1', '1', '1', '1', '2', '1', '9', '1', '3', '1', '1', '2', '1', '1', '2', '2', '1', '3', '2', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('276', '69', '2017-09-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('277', '69', '2017-10-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('278', '69', '2017-11-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('279', '69', '2017-12-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('280', '69', '2018-01-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('281', '69', '2018-02-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('282', '69', '2018-03-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('283', '69', '2018-04-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('284', '69', '2018-05-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('285', '69', '2018-06-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('286', '69', '2018-07-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('287', '69', '2018-08-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('288', '69', '2018-09-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('289', '8', '2016-02-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('290', '8', '2016-03-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('291', '8', '2016-04-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('292', '8', '2016-05-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('293', '8', '2016-06-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('294', '8', '2016-07-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('295', '8', '2016-08-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('296', '8', '2016-09-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('297', '8', '2016-10-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('298', '8', '2016-11-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('299', '8', '2016-12-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('300', '8', '2017-01-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('301', '8', '2017-02-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('302', '8', '2017-03-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('303', '8', '2017-04-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('304', '8', '2017-05-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('305', '8', '2017-06-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('306', '8', '2017-07-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('307', '8', '2017-08-01', '1', '1', '3', '1', '1', '1', '1', '1', '1', '1', '2', '1', '9', '1', '3', '1', '1', '2', '1', '1', '2', '2', '1', '3', '2', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('308', '8', '2017-09-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('309', '8', '2017-10-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('310', '8', '2017-11-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('311', '8', '2017-12-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('312', '8', '2018-01-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('313', '8', '2018-02-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('314', '8', '2018-03-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('315', '8', '2018-04-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('316', '8', '2018-05-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('317', '8', '2018-06-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('318', '8', '2018-07-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('319', '8', '2018-08-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` VALUES ('320', '8', '2018-09-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');

-- ----------------------------
-- Table structure for erp_pack_lists
-- ----------------------------
DROP TABLE IF EXISTS `erp_pack_lists`;
CREATE TABLE `erp_pack_lists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pack_code` varchar(20) DEFAULT NULL,
  `name` varchar(55) NOT NULL,
  `description` varchar(200) DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL,
  `parent` int(11) DEFAULT '0',
  `level` int(11) DEFAULT '0',
  `status` tinyint(3) DEFAULT '0',
  `cf1` varchar(255) DEFAULT NULL,
  `cf2` varchar(255) DEFAULT NULL,
  `cf3` varchar(255) DEFAULT NULL,
  `cf4` varchar(255) DEFAULT NULL,
  `cf5` varchar(255) DEFAULT NULL,
  `cf6` varchar(255) DEFAULT NULL,
  `cf7` varchar(255) DEFAULT NULL,
  `cf8` varchar(255) DEFAULT NULL,
  `cf9` varchar(255) DEFAULT NULL,
  `cf10` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_pack_lists
-- ----------------------------

-- ----------------------------
-- Table structure for erp_payments
-- ----------------------------
DROP TABLE IF EXISTS `erp_payments`;
CREATE TABLE `erp_payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `biller_id` int(11) DEFAULT NULL,
  `date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `sale_id` int(11) DEFAULT NULL,
  `return_id` int(11) DEFAULT NULL,
  `purchase_id` int(11) DEFAULT NULL,
  `deposit_id` int(11) DEFAULT NULL,
  `purchase_deposit_id` int(11) DEFAULT NULL,
  `loan_id` int(11) DEFAULT NULL,
  `expense_id` int(11) DEFAULT NULL,
  `transaction_id` int(11) DEFAULT NULL,
  `reference_no` varchar(50) NOT NULL,
  `paid_by` varchar(20) NOT NULL,
  `cheque_no` varchar(20) DEFAULT NULL,
  `cc_no` varchar(20) DEFAULT NULL,
  `cc_holder` varchar(25) DEFAULT NULL,
  `cc_month` varchar(2) DEFAULT NULL,
  `cc_year` varchar(4) DEFAULT NULL,
  `cc_type` varchar(20) DEFAULT NULL,
  `amount` decimal(25,4) NOT NULL,
  `pos_paid` decimal(25,4) DEFAULT '0.0000',
  `currency` varchar(3) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `attachment` varchar(55) DEFAULT NULL,
  `type` varchar(20) NOT NULL,
  `note` varchar(1000) DEFAULT NULL,
  `pos_balance` decimal(25,4) DEFAULT '0.0000',
  `pos_paid_other` decimal(25,4) DEFAULT NULL,
  `pos_paid_other_rate` decimal(25,4) DEFAULT NULL,
  `approval_code` varchar(50) DEFAULT NULL,
  `purchase_return_id` int(11) DEFAULT NULL,
  `return_deposit_id` int(11) DEFAULT NULL,
  `extra_paid` decimal(25,4) DEFAULT NULL,
  `deposit_quote_id` int(11) DEFAULT NULL,
  `add_payment` tinyint(1) DEFAULT NULL,
  `discount_id` varchar(20) DEFAULT NULL,
  `discount` int(11) DEFAULT NULL,
  `tax` int(11) DEFAULT NULL,
  `commission` int(11) DEFAULT NULL,
  `bank_account` int(11) DEFAULT NULL,
  `created_by_name` varchar(100) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_count` int(4) unsigned zerofill NOT NULL,
  `old_reference_no` varchar(50) DEFAULT NULL,
  `interest_paid` decimal(25,4) DEFAULT NULL,
  `opening` tinyint(1) unsigned zerofill NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_payments
-- ----------------------------
INSERT INTO `erp_payments` VALUES ('1', '1', '2017-08-25 18:05:00', null, null, '1', null, null, null, null, null, 'PV/1708/00001', 'cash', '', '', '', '', '', 'Visa', '231.0800', '0.0000', null, '1', null, 'received', '', '0.0000', null, null, null, null, null, null, null, '1', null, null, null, null, '100100', null, null, '0000', null, null, '0');
INSERT INTO `erp_payments` VALUES ('2', '1', '2017-08-25 18:05:00', null, null, '2', null, null, null, null, null, 'PV/1708/00001', 'cash', '', '', '', '', '', 'Visa', '168.9200', '0.0000', null, '1', null, 'received', '', '0.0000', null, null, null, null, null, null, null, '1', null, null, null, null, '100100', null, null, '0000', null, null, '0');

-- ----------------------------
-- Table structure for erp_payments_audit
-- ----------------------------
DROP TABLE IF EXISTS `erp_payments_audit`;
CREATE TABLE `erp_payments_audit` (
  `id` int(11) NOT NULL,
  `biller_id` int(11) DEFAULT NULL,
  `date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `sale_id` int(11) DEFAULT NULL,
  `return_id` int(11) DEFAULT NULL,
  `purchase_id` int(11) DEFAULT NULL,
  `deposit_id` int(11) DEFAULT NULL,
  `purchase_deposit_id` int(11) DEFAULT NULL,
  `loan_id` int(11) DEFAULT NULL,
  `expense_id` int(11) DEFAULT NULL,
  `transaction_id` int(11) DEFAULT NULL,
  `reference_no` varchar(50) NOT NULL,
  `paid_by` varchar(20) NOT NULL,
  `cheque_no` varchar(20) DEFAULT NULL,
  `cc_no` varchar(20) DEFAULT NULL,
  `cc_holder` varchar(25) DEFAULT NULL,
  `cc_month` varchar(2) DEFAULT NULL,
  `cc_year` varchar(4) DEFAULT NULL,
  `cc_type` varchar(20) DEFAULT NULL,
  `amount` decimal(25,4) NOT NULL,
  `pos_paid` decimal(25,4) DEFAULT '0.0000',
  `currency` varchar(3) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `attachment` varchar(55) DEFAULT NULL,
  `type` varchar(20) NOT NULL,
  `note` varchar(1000) DEFAULT NULL,
  `pos_balance` decimal(25,4) DEFAULT '0.0000',
  `pos_paid_other` decimal(25,4) DEFAULT NULL,
  `pos_paid_other_rate` decimal(25,4) DEFAULT NULL,
  `approval_code` varchar(50) DEFAULT NULL,
  `purchase_return_id` int(11) DEFAULT NULL,
  `return_deposit_id` int(11) DEFAULT NULL,
  `extra_paid` decimal(25,4) DEFAULT NULL,
  `deposit_quote_id` int(11) DEFAULT NULL,
  `add_payment` tinyint(1) DEFAULT NULL,
  `discount_id` varchar(20) DEFAULT NULL,
  `discount` int(11) DEFAULT NULL,
  `tax` int(11) DEFAULT NULL,
  `commission` int(11) DEFAULT NULL,
  `bank_account` int(11) DEFAULT NULL,
  `created_by_name` varchar(100) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_count` int(4) unsigned zerofill NOT NULL,
  `old_reference_no` varchar(50) DEFAULT NULL,
  `audit_id` int(11) NOT NULL AUTO_INCREMENT,
  `audit_created_by` int(11) NOT NULL,
  `audit_record_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `audit_type` varchar(55) NOT NULL,
  PRIMARY KEY (`audit_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_payments_audit
-- ----------------------------

-- ----------------------------
-- Table structure for erp_payment_term
-- ----------------------------
DROP TABLE IF EXISTS `erp_payment_term`;
CREATE TABLE `erp_payment_term` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(55) DEFAULT NULL,
  `due_day` int(11) DEFAULT '0',
  `due_day_for_discount` int(11) DEFAULT '0',
  `discount` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of erp_payment_term
-- ----------------------------
INSERT INTO `erp_payment_term` VALUES ('1', 'Net 20 Days', '20', '5', '0');
INSERT INTO `erp_payment_term` VALUES ('2', 'Net 1 Month', '30', '0', '0');
INSERT INTO `erp_payment_term` VALUES ('3', 'Net 7 Days', '7', '7', '0');
INSERT INTO `erp_payment_term` VALUES ('4', 'Case On Hand', '0', '0', '0');

-- ----------------------------
-- Table structure for erp_paypal
-- ----------------------------
DROP TABLE IF EXISTS `erp_paypal`;
CREATE TABLE `erp_paypal` (
  `id` int(11) NOT NULL,
  `active` tinyint(4) NOT NULL,
  `account_email` varchar(255) NOT NULL,
  `paypal_currency` varchar(3) NOT NULL DEFAULT 'USD',
  `fixed_charges` decimal(25,4) NOT NULL DEFAULT '2.0000',
  `extra_charges_my` decimal(25,4) NOT NULL DEFAULT '3.9000',
  `extra_charges_other` decimal(25,4) NOT NULL DEFAULT '4.4000',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_paypal
-- ----------------------------

-- ----------------------------
-- Table structure for erp_permissions
-- ----------------------------
DROP TABLE IF EXISTS `erp_permissions`;
CREATE TABLE `erp_permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `products-index` tinyint(1) DEFAULT '0',
  `products-add` tinyint(1) DEFAULT '0',
  `products-edit` tinyint(1) DEFAULT '0',
  `products-delete` tinyint(1) DEFAULT '0',
  `products-cost` tinyint(1) DEFAULT '0',
  `products-price` tinyint(1) DEFAULT '0',
  `products-import` tinyint(1) DEFAULT '0',
  `products-export` tinyint(1) DEFAULT '0',
  `products_convert_add` tinyint(1) DEFAULT NULL,
  `products-items_convert` tinyint(1) DEFAULT NULL,
  `products_print_barcodes` tinyint(1) DEFAULT NULL,
  `products-using_stock` tinyint(1) DEFAULT NULL,
  `products-list_using_stock` tinyint(1) DEFAULT NULL,
  `product_import` tinyint(1) DEFAULT NULL,
  `products-import_quantity` tinyint(1) DEFAULT NULL,
  `products-import_price_cost` tinyint(1) DEFAULT NULL,
  `products-print_barcodes` tinyint(1) DEFAULT NULL,
  `products-return_list` tinyint(1) DEFAULT NULL,
  `products-sync_quantity` tinyint(1) DEFAULT NULL,
  `quotes-index` tinyint(1) DEFAULT '0',
  `quotes-add` tinyint(1) DEFAULT '0',
  `quotes-edit` tinyint(1) DEFAULT '0',
  `quotes-email` tinyint(1) DEFAULT '0',
  `quotes-delete` tinyint(1) DEFAULT '0',
  `quotes-pdf` tinyint(1) DEFAULT '0',
  `quotes-export` tinyint(1) DEFAULT '0',
  `quotes-import` tinyint(1) DEFAULT '0',
  `quotes-authorize` tinyint(1) DEFAULT NULL,
  `quotes-conbine_pdf` tinyint(1) DEFAULT NULL,
  `sales-index` tinyint(1) DEFAULT '0',
  `sales-add` tinyint(1) DEFAULT '0',
  `sales-edit` tinyint(1) DEFAULT '0',
  `sales-email` tinyint(1) DEFAULT '0',
  `sales-pdf` tinyint(1) DEFAULT '0',
  `sales-delete` tinyint(1) DEFAULT '0',
  `sales-export` tinyint(1) DEFAULT '0',
  `sales-import` tinyint(1) DEFAULT '0',
  `purchases-index` tinyint(1) DEFAULT '0',
  `purchases-add` tinyint(1) DEFAULT '0',
  `purchases-edit` tinyint(1) DEFAULT '0',
  `purchases-email` tinyint(1) DEFAULT '0',
  `purchases-pdf` tinyint(1) DEFAULT '0',
  `purchases-delete` tinyint(1) DEFAULT '0',
  `purchases-export` tinyint(1) DEFAULT '0',
  `purchases-import` tinyint(1) DEFAULT '0',
  `purchases-return_list` tinyint(1) DEFAULT NULL,
  `purchases-return_add` tinyint(1) DEFAULT NULL,
  `purchases-cost` tinyint(1) DEFAULT NULL,
  `purchases-price` tinyint(1) DEFAULT NULL,
  `purchases-opening_ap` tinyint(1) DEFAULT NULL,
  `purchases-expenses` tinyint(1) DEFAULT '0',
  `purchases-import_expanse` tinyint(1) DEFAULT NULL,
  `purchases-payments` tinyint(1) DEFAULT '0',
  `purchases-combine_pdf` tinyint(1) DEFAULT NULL,
  `purchases-supplier_balance` tinyint(1) DEFAULT NULL,
  `transfers-index` tinyint(1) DEFAULT '0',
  `transfers-add` tinyint(1) DEFAULT '0',
  `transfers-edit` tinyint(1) DEFAULT '0',
  `transfers-email` tinyint(1) DEFAULT '0',
  `transfers-delete` tinyint(1) DEFAULT '0',
  `transfers-pdf` tinyint(1) DEFAULT '0',
  `transfers-export` tinyint(1) DEFAULT '0',
  `transfers-import` tinyint(1) DEFAULT '0',
  `transfers-combine_pdf` tinyint(1) DEFAULT NULL,
  `customers-index` tinyint(1) DEFAULT '0',
  `customers-add` tinyint(1) DEFAULT '0',
  `customers-edit` tinyint(1) DEFAULT '0',
  `customers-delete` tinyint(1) DEFAULT '0',
  `customers-export` tinyint(1) DEFAULT '0',
  `customers-import` tinyint(1) DEFAULT '0',
  `suppliers-index` tinyint(1) DEFAULT '0',
  `suppliers-add` tinyint(1) DEFAULT '0',
  `suppliers-edit` tinyint(1) DEFAULT '0',
  `suppliers-delete` tinyint(1) DEFAULT '0',
  `suppliers-import` tinyint(1) DEFAULT '0',
  `suppliers-export` tinyint(1) DEFAULT '0',
  `sales-deliveries` tinyint(1) DEFAULT '0',
  `sales-add_delivery` tinyint(1) DEFAULT '0',
  `sales-edit_delivery` tinyint(1) DEFAULT '0',
  `sales-delete_delivery` tinyint(1) DEFAULT '0',
  `sales-pdf_delivery` tinyint(1) DEFAULT '0',
  `sales-email_delivery` tinyint(1) DEFAULT '0',
  `sales-export_delivery` tinyint(1) DEFAULT '0',
  `sales-import_delivery` tinyint(1) DEFAULT '0',
  `sales-gift_cards` tinyint(1) DEFAULT '0',
  `sales-add_gift_card` tinyint(1) DEFAULT '0',
  `sales-edit_gift_card` tinyint(1) DEFAULT '0',
  `sales-delete_gift_card` tinyint(1) DEFAULT '0',
  `sales-import_gift_card` tinyint(1) DEFAULT '0',
  `sales-export_gift_card` tinyint(1) DEFAULT '0',
  `sales-authorize` tinyint(1) DEFAULT NULL,
  `pos-index` tinyint(1) DEFAULT '0',
  `sales-return_sales` tinyint(1) DEFAULT '0',
  `reports-index` tinyint(1) DEFAULT '0',
  `reports-warehouse_stock` tinyint(1) DEFAULT '0',
  `reports-quantity_alerts` tinyint(1) DEFAULT '0',
  `reports-expiry_alerts` tinyint(1) DEFAULT '0',
  `reports-products` tinyint(1) DEFAULT '0',
  `reports-daily_sales` tinyint(1) DEFAULT '0',
  `reports-monthly_sales` tinyint(1) DEFAULT '0',
  `reports-sales` tinyint(1) DEFAULT '0',
  `reports-payments` tinyint(1) DEFAULT '0',
  `reports-purchases` tinyint(1) DEFAULT '0',
  `reports-profit_loss` tinyint(1) DEFAULT '0',
  `reports-customers` tinyint(1) DEFAULT '0',
  `reports-suppliers` tinyint(1) DEFAULT '0',
  `reports-staff` tinyint(1) DEFAULT '0',
  `reports-register` tinyint(1) DEFAULT '0',
  `reports-account` tinyint(1) DEFAULT '0',
  `sales-payments` tinyint(1) DEFAULT '0',
  `bulk_actions` tinyint(1) DEFAULT '0',
  `customers-deposits` tinyint(1) DEFAULT '0',
  `customers-delete_deposit` tinyint(1) DEFAULT '0',
  `products-adjustments` tinyint(1) DEFAULT '0',
  `accounts-index` tinyint(1) DEFAULT '0',
  `accounts-add` tinyint(1) DEFAULT '0',
  `accounts-edit` tinyint(1) DEFAULT '0',
  `accounts-delete` tinyint(1) DEFAULT '0',
  `accounts-import` tinyint(1) DEFAULT '0',
  `accounts-export` tinyint(1) DEFAULT '0',
  `account-list_receivable` tinyint(1) DEFAULT NULL,
  `account-list_ar_aging` tinyint(1) DEFAULT NULL,
  `account-ar_by_customer` tinyint(1) DEFAULT NULL,
  `account-bill_receipt` tinyint(1) DEFAULT NULL,
  `account-list_payable` tinyint(1) DEFAULT NULL,
  `account-list_ap_aging` tinyint(1) DEFAULT NULL,
  `account-ap_by_supplier` tinyint(1) DEFAULT NULL,
  `account-bill_payable` tinyint(1) DEFAULT NULL,
  `account-list_ac_head` tinyint(1) DEFAULT NULL,
  `account-add_ac_head` tinyint(1) DEFAULT NULL,
  `account-list_customer_deposit` tinyint(1) DEFAULT NULL,
  `account-add_customer_deposit` tinyint(1) DEFAULT NULL,
  `account-list_supplier_deposit` tinyint(1) DEFAULT NULL,
  `account-add_supplier_deposit` tinyint(1) DEFAULT NULL,
  `account_setting` tinyint(1) DEFAULT NULL,
  `sales-discount` tinyint(1) DEFAULT '0',
  `sales-price` tinyint(1) DEFAULT '0',
  `sales-opening_ar` tinyint(1) DEFAULT NULL,
  `sales-loan` tinyint(1) DEFAULT '0',
  `reports-daily_purchases` tinyint(1) DEFAULT '0',
  `reports-monthly_purchases` tinyint(1) DEFAULT '0',
  `room-index` tinyint(1) DEFAULT NULL,
  `room-add` tinyint(1) DEFAULT NULL,
  `room-edit` tinyint(1) DEFAULT NULL,
  `room-delete` tinyint(1) DEFAULT NULL,
  `room-import` tinyint(1) DEFAULT NULL,
  `room-export` tinyint(1) DEFAULT NULL,
  `sale-room-index` tinyint(1) DEFAULT NULL,
  `sale-room-add` tinyint(1) DEFAULT NULL,
  `sale-room-edit` tinyint(1) DEFAULT NULL,
  `sale-room-delete` tinyint(1) DEFAULT NULL,
  `sale-room-import` tinyint(1) DEFAULT NULL,
  `sale-room-export` tinyint(1) DEFAULT NULL,
  `sales-combine_pdf` tinyint(1) DEFAULT NULL,
  `sales-combine_delivery` tinyint(1) DEFAULT NULL,
  `sale_order-index` tinyint(1) DEFAULT NULL,
  `sale_order-add` tinyint(1) DEFAULT NULL,
  `sale_order-edit` tinyint(1) DEFAULT NULL,
  `sale_order-delete` tinyint(1) DEFAULT NULL,
  `sale_order-import` tinyint(1) DEFAULT NULL,
  `sale_order-export` tinyint(1) DEFAULT NULL,
  `sale_order-authorize` tinyint(1) DEFAULT NULL,
  `sale_order-combine_pdf` tinyint(1) DEFAULT NULL,
  `sale_order-price` tinyint(1) DEFAULT NULL,
  `sale_order-deposit` tinyint(1) DEFAULT NULL,
  `purchases_order-index` tinyint(1) DEFAULT NULL,
  `purchases_order-edit` tinyint(1) DEFAULT NULL,
  `purchases_order-add` tinyint(1) DEFAULT NULL,
  `purchases_order-delete` tinyint(1) DEFAULT NULL,
  `purchases_order-email` tinyint(1) DEFAULT NULL,
  `purchases_order-import` tinyint(1) DEFAULT NULL,
  `purchases_order-export` tinyint(1) DEFAULT NULL,
  `purchases_order-pdf` tinyint(1) DEFAULT NULL,
  `purchases_order-payments` tinyint(1) DEFAULT NULL,
  `purchases_order-expenses` tinyint(1) DEFAULT NULL,
  `purchase_order-cost` tinyint(1) DEFAULT NULL,
  `purchase_order-price` tinyint(1) DEFAULT NULL,
  `purchase_order-import_expanse` tinyint(1) DEFAULT NULL,
  `purchase_order-combine_pdf` tinyint(1) DEFAULT NULL,
  `purchase_order-authorize` tinyint(1) DEFAULT NULL,
  `purchase_request-index` tinyint(1) DEFAULT NULL,
  `purchase_request-add` tinyint(1) DEFAULT NULL,
  `purchase_request-edit` tinyint(1) DEFAULT NULL,
  `purchase_request-delete` tinyint(1) DEFAULT NULL,
  `purchase_request-export` tinyint(1) DEFAULT NULL,
  `purchase_request-import` tinyint(1) DEFAULT NULL,
  `purchase_request-cost` tinyint(1) DEFAULT NULL,
  `purchase_request-price` tinyint(1) DEFAULT NULL,
  `purchase_request-import_expanse` tinyint(1) DEFAULT NULL,
  `purchase_request-combine_pdf` tinyint(1) DEFAULT NULL,
  `purchase_request-authorize` tinyint(1) DEFAULT NULL,
  `users-index` tinyint(1) DEFAULT NULL,
  `users-edit` tinyint(1) DEFAULT NULL,
  `users-add` tinyint(1) DEFAULT NULL,
  `users-delete` tinyint(1) DEFAULT NULL,
  `users-import` tinyint(1) DEFAULT NULL,
  `users-export` tinyint(1) DEFAULT NULL,
  `drivers-index` tinyint(1) DEFAULT NULL,
  `drivers-edit` tinyint(1) DEFAULT NULL,
  `drivers-add` tinyint(1) DEFAULT NULL,
  `drivers-delete` tinyint(1) DEFAULT NULL,
  `drivers-import` tinyint(1) DEFAULT NULL,
  `drivers-export` tinyint(1) DEFAULT NULL,
  `employees-index` tinyint(1) DEFAULT NULL,
  `employees-edit` tinyint(1) DEFAULT NULL,
  `employees-add` tinyint(1) DEFAULT NULL,
  `employees-delete` tinyint(1) DEFAULT NULL,
  `employees-import` tinyint(1) DEFAULT NULL,
  `employees-export` tinyint(1) DEFAULT NULL,
  `projects-index` tinyint(1) DEFAULT NULL,
  `projects-edit` tinyint(1) DEFAULT NULL,
  `projects-add` tinyint(1) DEFAULT NULL,
  `projects-delete` tinyint(1) DEFAULT NULL,
  `projects-import` tinyint(1) DEFAULT NULL,
  `projects-export` tinyint(1) DEFAULT NULL,
  `product_report-index` tinyint(1) DEFAULT NULL,
  `product_report-quantity_alert` tinyint(1) DEFAULT NULL,
  `product_report-product` tinyint(1) DEFAULT NULL,
  `product_report-warehouse` tinyint(1) DEFAULT NULL,
  `product_report-in_out` tinyint(1) DEFAULT NULL,
  `product_report-monthly` tinyint(1) DEFAULT NULL,
  `product_report-daily` tinyint(1) DEFAULT NULL,
  `product_report-suppliers` tinyint(1) DEFAULT NULL,
  `product_report-customers` tinyint(1) DEFAULT NULL,
  `product_report-categories` tinyint(1) DEFAULT NULL,
  `product_report-categories_value` tinyint(1) DEFAULT NULL,
  `product_report-inventory_valuation_detail` tinyint(1) DEFAULT NULL,
  `sale_report-index` tinyint(1) DEFAULT NULL,
  `sale_report-register` tinyint(1) DEFAULT NULL,
  `sale_report-daily` tinyint(1) DEFAULT NULL,
  `sale_report-monthly` tinyint(1) DEFAULT NULL,
  `sale_report-report_sale` tinyint(1) DEFAULT NULL,
  `sale_report-disccount` tinyint(1) DEFAULT NULL,
  `sale_report-by_delivery_person` tinyint(1) DEFAULT NULL,
  `sale_report-delivery_detail` tinyint(1) DEFAULT NULL,
  `sale_report-customer` tinyint(1) DEFAULT NULL,
  `sale_report-saleman` tinyint(1) DEFAULT NULL,
  `sale_report-staff` tinyint(1) DEFAULT NULL,
  `sale_report-supplier` tinyint(1) DEFAULT NULL,
  `sale_report-project` tinyint(1) DEFAULT NULL,
  `sale_report-detail` tinyint(1) DEFAULT NULL,
  `sale_report-by_invoice` tinyint(1) DEFAULT NULL,
  `sale_report-sale_profit` tinyint(1) DEFAULT NULL,
  `sale_report-room_table` tinyint(1) DEFAULT NULL,
  `sale_report-project_manager` tinyint(1) DEFAULT NULL,
  `chart_report-index` tinyint(1) DEFAULT NULL,
  `chart_report-over_view` tinyint(1) DEFAULT NULL,
  `chart_report-warehouse_stock` tinyint(1) DEFAULT NULL,
  `chart_report-category_stock` tinyint(1) DEFAULT NULL,
  `chart_report-profit` tinyint(1) DEFAULT NULL,
  `chart_report-cash_analysis` tinyint(1) DEFAULT NULL,
  `chart_report-customize` tinyint(1) DEFAULT NULL,
  `chart_report-room_table` tinyint(1) DEFAULT NULL,
  `chart_report-suspend_profit_and_lose` tinyint(1) DEFAULT NULL,
  `account_report-index` tinyint(1) DEFAULT NULL,
  `account_report-ledger` tinyint(1) DEFAULT NULL,
  `account_report-trail_balance` tinyint(1) DEFAULT NULL,
  `account_report-journal` tinyint(1) DEFAULT NULL,
  `account_report-ac_injuiry_report` tinyint(1) DEFAULT NULL,
  `account_report-balance_sheet` tinyint(1) DEFAULT NULL,
  `account_report-bsh_by_month` tinyint(1) DEFAULT NULL,
  `account_report-bsh_by_year` tinyint(1) DEFAULT NULL,
  `account_report-bsh_by_project` tinyint(1) DEFAULT NULL,
  `account_report-bsh_by_budget` tinyint(1) DEFAULT NULL,
  `account_report-income_statement` tinyint(1) DEFAULT NULL,
  `account_report-income_statement_detail` tinyint(1) DEFAULT NULL,
  `account_report-ins_by_month` tinyint(1) DEFAULT NULL,
  `account_report-ins_by_year` tinyint(1) DEFAULT NULL,
  `account_report-ins_by_project` tinyint(1) DEFAULT NULL,
  `account_report-ins_by_budget` tinyint(1) DEFAULT NULL,
  `account_report-cash_flow_statement` tinyint(1) DEFAULT NULL,
  `account_report-cash_book` tinyint(1) DEFAULT NULL,
  `account_report-payment` tinyint(1) DEFAULT NULL,
  `report_profit-index` tinyint(1) DEFAULT NULL,
  `report_profit-payments` tinyint(1) DEFAULT NULL,
  `report_profit-profit_andOr_lose` tinyint(1) DEFAULT NULL,
  `report_profit-stock` tinyint(1) DEFAULT NULL,
  `report_profit-category` tinyint(1) DEFAULT NULL,
  `report_profit-sale_profit` tinyint(1) DEFAULT NULL,
  `report_profit-project` tinyint(1) DEFAULT NULL,
  `report_profit-project_profit` tinyint(1) DEFAULT NULL,
  `purchase_report-index` tinyint(1) DEFAULT NULL,
  `purchase_report-purchas` tinyint(1) DEFAULT NULL,
  `purchase_report-daily` tinyint(1) DEFAULT NULL,
  `purchase_report-monthly` tinyint(1) DEFAULT NULL,
  `purchase_report-supplier` tinyint(1) DEFAULT NULL,
  `purchase-authorize` tinyint(1) DEFAULT NULL,
  `product_using_stock` tinyint(1) DEFAULT NULL,
  `product_list_using_stock` tinyint(1) DEFAULT NULL,
  `product_return_list` tinyint(1) DEFAULT NULL,
  `sale_report-customer_transfers` tinyint(1) DEFAULT NULL,
  `purchases_add-expenses` tinyint(1) DEFAULT NULL,
  `customers_balance` tinyint(1) DEFAULT NULL,
  `report_convert` tinyint(1) DEFAULT NULL,
  `report_list_using_stock` tinyint(1) DEFAULT NULL,
  `report_transfers` tinyint(1) DEFAULT NULL,
  `product_stock_count` tinyint(1) DEFAULT NULL,
  `product_import_quantity` tinyint(1) DEFAULT NULL,
  `product_import_price_cost` varchar(1) DEFAULT NULL,
  `purchase_report-expense` varchar(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_permissions
-- ----------------------------
INSERT INTO `erp_permissions` VALUES ('1', '5', '1', null, null, null, null, '1', null, null, null, null, '1', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '1', null, '1', '1', null, null, null, null, null, '1', null, '1', null, null, null, null, null, '0', '1', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '1', '1', null, null, '0', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '1', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '1', null, null, null, null, null, null, null);
INSERT INTO `erp_permissions` VALUES ('2', '6', '1', '1', '1', null, null, null, null, null, null, null, '1', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '1', '1', '1', '1', null, '1', '1', '1', '1', '1', '1', '1', null, '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '1', '1', null, null, null, null, null, null, null, null, null, null, null, null, null, '1', '1', '1', '0', '1', null, '1', null, null, '1', null, null, null, null, null, '0', '0', '1', '1', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '1', '1', '1', '1', '0', '0', null, null, null, null, null, null, null, null, null, null, null, null, '1', null, '1', '1', '1', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', null, '1', '1', '1', '1', '1', '1', '1', '1', '1', null, '1', '1', '1', '1', '1', '1', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '1', '1', '1', '1', '1', null, null, null, null, '1', '1', '1', '1', '1', '1', null, null, null, null);
INSERT INTO `erp_permissions` VALUES ('3', '7', '1', '1', '1', null, null, null, null, '1', null, '1', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '1', '1', '1', null, null, '1', '1', '1', '1', '1', '1', null, null, null, '1', null, '1', null, '1', '1', null, null, null, '1', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '1', '1', '1', null, null, null, null, null, null, null, null, null, null, null, null, '1', '1', '1', '0', null, null, null, null, null, null, null, null, null, null, null, '0', '0', null, '1', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '1', '1', null, '0', '0', null, null, null, null, null, null, null, null, null, null, null, null, '1', null, '1', '1', null, null, null, null, null, null, '1', null, '1', '1', '1', null, null, null, '1', null, null, null, null, null, null, null, null, '1', '1', '1', null, '1', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '1', null, null, null, '1', null, null, null, null, null, null, '1', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null);
INSERT INTO `erp_permissions` VALUES ('4', '8', '1', '1', '1', '1', null, null, null, null, null, null, '1', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, null, '0', null, null, '0', null, '0', null, null, null, '0', '0', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null);
INSERT INTO `erp_permissions` VALUES ('5', '9', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '1', '1', '1', null, null, '1', '1', '1', '1', '1', '1', null, null, null, null, '1', null, null, '1', '1', '1', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '1', '1', '1', '1', null, null, '1', '1', null, null, null, null, null, null, null, null, null, '1', '0', null, null, '0', null, null, '0', null, '0', null, null, null, '0', '0', '0', null, null, null, null, '1', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '1', '1', '1', '1', null, '1', '1', null, '1', null, '1', null, null, null, null, '1', '1', '1', '1', '1', '1', '1', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '1', null, null, '1', null, null, null, null, null, null, null, '1', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '1', null, null, null, null, null, '1', null, null, null, null, null, null, null);
INSERT INTO `erp_permissions` VALUES ('6', '10', '1', null, null, null, null, '1', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '1', '1', null, null, null, null, '1', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '1', '1', null, null, '0', null, null, '0', null, null, '0', null, '0', null, null, null, '0', '0', '0', '1', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '1', '1', null, null, '0', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, '1', '1', null, null, null, '1', '1', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '1', null, null, null, null, null, null, null);
INSERT INTO `erp_permissions` VALUES ('7', '11', '1', '1', null, null, null, '1', null, '1', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '1', '1', '1', null, null, '1', '1', '1', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '1', null, null, null, '0', null, null, '0', null, null, '0', null, '0', null, null, null, '0', '0', '0', '1', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '1', '1', null, null, '0', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, '1', '1', '1', null, null, '1', '1', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null);
INSERT INTO `erp_permissions` VALUES ('8', '12', '1', null, null, null, '1', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '1', '1', '1', null, null, null, '1', null, null, null, '1', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, null, '0', null, null, '0', null, '0', null, null, null, '0', '0', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, '1', null, null, null, null, null, null, null, null, null, '1', '1', '1', null, null, null, '1', null, null, null, '1', null, null, null, null, '1', '1', '1', null, '1', null, '1', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null);
INSERT INTO `erp_permissions` VALUES ('9', '13', '1', null, null, '1', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, null, '0', null, null, '0', null, '0', null, null, null, '0', '0', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null);
INSERT INTO `erp_permissions` VALUES ('10', '8', '1', '1', '1', '1', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, null, '0', null, null, '0', null, '0', null, null, null, '0', '0', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null);
INSERT INTO `erp_permissions` VALUES ('11', '9', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', null, '1', '1', '1', '1', '1', '1', '1', '1', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '1', '1', '1', null, null, '1', '1', '1', '1', '1', '1', null, null, null, null, '1', null, null, '1', '1', '1', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '1', '1', '1', '1', null, null, '1', '1', null, null, null, null, null, null, null, null, null, '1', '0', null, null, '0', null, null, '0', null, '0', null, null, null, '0', '0', '0', null, null, null, null, '1', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '1', '1', '1', '1', null, '1', '1', null, '1', null, '1', null, null, null, null, '1', '1', '1', '1', '1', '1', '1', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '1', null, null, '1', null, null, null, null, null, null, null, '1', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '1', null, null, null, null, null, '1', null, null, null, null, null, null, null);
INSERT INTO `erp_permissions` VALUES ('12', '10', '1', null, null, null, null, '1', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '1', '1', null, null, null, null, '1', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '1', '1', null, null, '0', null, null, '0', null, null, '0', null, '0', null, null, null, '0', '0', '0', '1', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '1', '1', null, null, '0', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, '1', '1', null, null, null, '1', '1', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '1', null, null, null, null, null, null, null);
INSERT INTO `erp_permissions` VALUES ('13', '8', '1', '1', '1', '1', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, null, '0', null, null, '0', null, '0', null, null, null, '0', '0', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null);
INSERT INTO `erp_permissions` VALUES ('14', '8', '1', '1', '1', '1', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, null, '0', null, null, '0', null, '0', null, null, null, '0', '0', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null);
INSERT INTO `erp_permissions` VALUES ('15', '8', '1', '1', '1', '1', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, null, '0', null, null, '0', null, '0', null, null, null, '0', '0', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null);
INSERT INTO `erp_permissions` VALUES ('16', '9', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', null, '1', '1', '1', '1', '1', '1', '1', '1', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '1', '1', '1', null, null, '1', '1', '1', '1', '1', '1', null, null, null, null, '1', null, null, '1', '1', '1', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '1', '1', '1', '1', null, null, '1', '1', null, null, null, null, null, null, null, null, null, '1', '0', null, null, '0', null, null, '0', null, '0', null, null, null, '0', '0', '0', null, null, null, null, '1', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '1', '1', '1', '1', null, '1', '1', null, '1', null, '1', null, null, null, null, '1', '1', '1', '1', '1', '1', '1', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '1', null, null, '1', null, null, null, null, null, null, null, '1', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '1', null, null, null, null, null, '1', null, null, null, null, null, null, null);
INSERT INTO `erp_permissions` VALUES ('17', '8', '1', '1', '1', '1', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, null, '0', null, null, '0', null, '0', null, null, null, '0', '0', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null);
INSERT INTO `erp_permissions` VALUES ('18', '9', '0', '0', '0', '0', '0', '0', '0', '0', null, null, null, null, null, null, null, null, null, null, null, '0', '0', '0', '0', '0', '0', '0', '0', null, null, '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, null, null, null, null, '0', null, '0', null, null, '0', '0', '0', '0', '0', '0', '0', '0', null, '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', '0', null, '0', '0', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null);
INSERT INTO `erp_permissions` VALUES ('19', '10', '0', '0', '0', '0', '0', '0', '0', '0', null, null, null, null, null, null, null, null, null, null, null, '0', '0', '0', '0', '0', '0', '0', '0', null, null, '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, null, null, null, null, '0', null, '0', null, null, '0', '0', '0', '0', '0', '0', '0', '0', null, '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', '0', null, '0', '0', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null);
INSERT INTO `erp_permissions` VALUES ('20', '11', '0', '0', '0', '0', '0', '0', '0', '0', null, null, null, null, null, null, null, null, null, null, null, '0', '0', '0', '0', '0', '0', '0', '0', null, null, '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, null, null, null, null, '0', null, '0', null, null, '0', '0', '0', '0', '0', '0', '0', '0', null, '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', '0', null, '0', '0', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null);
INSERT INTO `erp_permissions` VALUES ('21', '12', '0', '0', '0', '0', '0', '0', '0', '0', null, null, null, null, null, null, null, null, null, null, null, '0', '0', '0', '0', '0', '0', '0', '0', null, null, '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, null, null, null, null, '0', null, '0', null, null, '0', '0', '0', '0', '0', '0', '0', '0', null, '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', '0', null, '0', '0', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null);
INSERT INTO `erp_permissions` VALUES ('22', '13', '0', '0', '0', '0', '0', '0', '0', '0', null, null, null, null, null, null, null, null, null, null, null, '0', '0', '0', '0', '0', '0', '0', '0', null, null, '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, null, null, null, null, '0', null, '0', null, null, '0', '0', '0', '0', '0', '0', '0', '0', null, '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', '0', null, '0', '0', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null);
INSERT INTO `erp_permissions` VALUES ('23', '14', '0', '0', '0', '0', '0', '0', '0', '0', null, null, null, null, null, null, null, null, null, null, null, '0', '0', '0', '0', '0', '0', '0', '0', null, null, '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, null, null, null, null, '0', null, '0', null, null, '0', '0', '0', '0', '0', '0', '0', '0', null, '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', '0', null, '0', '0', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null);
INSERT INTO `erp_permissions` VALUES ('24', '15', '0', '0', '0', '0', '0', '0', '0', '0', null, null, null, null, null, null, null, null, null, null, null, '0', '0', '0', '0', '0', '0', '0', '0', null, null, '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, null, null, null, null, '0', null, '0', null, null, '0', '0', '0', '0', '0', '0', '0', '0', null, '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', '0', null, '0', '0', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null);
INSERT INTO `erp_permissions` VALUES ('25', '16', '0', '0', '0', '0', '0', '0', '0', '0', null, null, null, null, null, null, null, null, null, null, null, '0', '0', '0', '0', '0', '0', '0', '0', null, null, '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, null, null, null, null, '0', null, '0', null, null, '0', '0', '0', '0', '0', '0', '0', '0', null, '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', '0', null, '0', '0', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null);
INSERT INTO `erp_permissions` VALUES ('26', '17', '0', '0', '0', '0', '0', '0', '0', '0', null, null, null, null, null, null, null, null, null, null, null, '0', '0', '0', '0', '0', '0', '0', '0', null, null, '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, null, null, null, null, '0', null, '0', null, null, '0', '0', '0', '0', '0', '0', '0', '0', null, '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', '0', null, '0', '0', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null);
INSERT INTO `erp_permissions` VALUES ('27', '18', '0', '0', '0', '0', '0', '0', '0', '0', null, null, null, null, null, null, null, null, null, null, null, '0', '0', '0', '0', '0', '0', '0', '0', null, null, '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, null, null, null, null, '0', null, '0', null, null, '0', '0', '0', '0', '0', '0', '0', '0', null, '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', '0', null, '0', '0', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null);
INSERT INTO `erp_permissions` VALUES ('28', '19', '0', '0', '0', '0', '0', '0', '0', '0', null, null, null, null, null, null, null, null, null, null, null, '0', '0', '0', '0', '0', '0', '0', '0', null, null, '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, null, null, null, null, '0', null, '0', null, null, '0', '0', '0', '0', '0', '0', '0', '0', null, '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', '0', null, '0', '0', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null);
INSERT INTO `erp_permissions` VALUES ('29', '20', '0', '0', '0', '0', '0', '0', '0', '0', null, null, null, null, null, null, null, null, null, null, null, '0', '0', '0', '0', '0', '0', '0', '0', null, null, '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, null, null, null, null, '0', null, '0', null, null, '0', '0', '0', '0', '0', '0', '0', '0', null, '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', '0', null, '0', '0', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null);
INSERT INTO `erp_permissions` VALUES ('30', '21', '0', '0', '0', '0', '0', '0', '0', '0', null, null, null, null, null, null, null, null, null, null, null, '0', '0', '0', '0', '0', '0', '0', '0', null, null, '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, null, null, null, null, '0', null, '0', null, null, '0', '0', '0', '0', '0', '0', '0', '0', null, '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', '0', null, '0', '0', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null);
INSERT INTO `erp_permissions` VALUES ('31', '22', '1', '1', '1', null, '1', '1', null, '1', null, '1', null, '1', '1', null, '1', null, null, '1', '1', '1', '1', null, null, null, null, '1', null, null, '1', '1', '1', '1', '1', null, '1', '1', '1', '1', '1', '1', null, null, null, null, null, null, null, '1', null, null, null, null, null, null, null, '1', '1', '1', '1', '1', null, '1', '1', '1', null, null, null, null, null, null, null, null, null, null, null, null, '1', '1', '1', '1', null, null, '1', '1', null, null, null, null, null, null, null, '1', '1', null, '0', null, null, '0', null, null, '0', null, '0', null, null, null, '0', '0', '0', '1', null, null, null, '1', '1', '1', null, null, null, '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', null, '1', null, '1', '1', '0', '0', null, null, null, null, null, null, null, null, null, null, null, null, '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', null, null, '1', '1', '1', null, '1', null, '1', null, null, null, '1', null, null, null, '1', '1', '1', '1', '1', '1', null, '1', null, null, null, '1', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '1', null, null, null, null, null, null, null);
INSERT INTO `erp_permissions` VALUES ('32', '23', '0', '0', '0', '0', '0', '0', '0', '0', null, null, null, null, null, null, null, null, null, null, null, '0', '0', '0', '0', '0', '0', '0', '0', null, null, '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, null, null, null, null, '0', null, '0', null, null, '0', '0', '0', '0', '0', '0', '0', '0', null, '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', '0', null, '0', '0', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null);
INSERT INTO `erp_permissions` VALUES ('33', '24', '0', '0', '0', '0', '0', '0', '0', '0', null, null, null, null, null, null, null, null, null, null, null, '0', '0', '0', '0', '0', '0', '0', '0', null, null, '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, null, null, null, null, '0', null, '0', null, null, '0', '0', '0', '0', '0', '0', '0', '0', null, '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', '0', null, '0', '0', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null);
INSERT INTO `erp_permissions` VALUES ('34', '25', '0', '0', '0', '0', '0', '0', '0', '0', null, null, null, null, null, null, null, null, null, null, null, '0', '0', '0', '0', '0', '0', '0', '0', null, null, '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, null, null, null, null, '0', null, '0', null, null, '0', '0', '0', '0', '0', '0', '0', '0', null, '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', '0', null, '0', '0', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null);
INSERT INTO `erp_permissions` VALUES ('35', '8', '1', '1', '1', '1', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, null, '0', null, null, '0', null, '0', null, null, null, '0', '0', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null);
INSERT INTO `erp_permissions` VALUES ('36', '8', '1', '1', '1', '1', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, null, '0', null, null, '0', null, '0', null, null, null, '0', '0', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null);

-- ----------------------------
-- Table structure for erp_position
-- ----------------------------
DROP TABLE IF EXISTS `erp_position`;
CREATE TABLE `erp_position` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_position
-- ----------------------------

-- ----------------------------
-- Table structure for erp_pos_register
-- ----------------------------
DROP TABLE IF EXISTS `erp_pos_register`;
CREATE TABLE `erp_pos_register` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` int(11) NOT NULL,
  `cash_in_hand` decimal(25,4) NOT NULL,
  `status` varchar(10) NOT NULL,
  `total_cash` decimal(25,4) DEFAULT NULL,
  `total_cheques` int(11) DEFAULT NULL,
  `total_cc_slips` int(11) DEFAULT NULL,
  `total_cash_submitted` decimal(25,4) DEFAULT NULL,
  `total_cheques_submitted` int(11) DEFAULT NULL,
  `total_cc_slips_submitted` int(11) DEFAULT NULL,
  `total_member_slips` int(11) DEFAULT NULL,
  `total_member_slips_submitted` int(11) DEFAULT NULL,
  `total_voucher_slips` int(11) DEFAULT NULL,
  `total_voucher_slips_submitted` int(11) DEFAULT NULL,
  `note` text,
  `closed_at` timestamp NULL DEFAULT NULL,
  `transfer_opened_bills` varchar(50) DEFAULT NULL,
  `closed_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_pos_register
-- ----------------------------

-- ----------------------------
-- Table structure for erp_pos_settings
-- ----------------------------
DROP TABLE IF EXISTS `erp_pos_settings`;
CREATE TABLE `erp_pos_settings` (
  `pos_id` int(1) NOT NULL,
  `cat_limit` int(11) NOT NULL,
  `pro_limit` int(11) NOT NULL,
  `default_category` int(11) NOT NULL,
  `default_customer` int(11) NOT NULL,
  `default_biller` int(11) NOT NULL,
  `display_time` varchar(3) NOT NULL DEFAULT 'yes',
  `cf_title1` varchar(255) DEFAULT NULL,
  `cf_title2` varchar(255) DEFAULT NULL,
  `cf_value1` varchar(255) DEFAULT NULL,
  `cf_value2` varchar(255) DEFAULT NULL,
  `receipt_printer` varchar(55) DEFAULT NULL,
  `cash_drawer_codes` varchar(55) DEFAULT NULL,
  `focus_add_item` varchar(55) DEFAULT NULL,
  `add_manual_product` varchar(55) DEFAULT NULL,
  `customer_selection` varchar(55) DEFAULT NULL,
  `add_customer` varchar(55) DEFAULT NULL,
  `toggle_category_slider` varchar(55) DEFAULT NULL,
  `toggle_subcategory_slider` varchar(55) DEFAULT NULL,
  `show_search_item` varchar(55) DEFAULT NULL,
  `product_unit` varchar(55) DEFAULT NULL,
  `cancel_sale` varchar(55) DEFAULT NULL,
  `suspend_sale` varchar(55) DEFAULT NULL,
  `print_items_list` varchar(55) DEFAULT NULL,
  `print_bill` varchar(55) DEFAULT NULL,
  `finalize_sale` varchar(55) DEFAULT NULL,
  `today_sale` varchar(55) DEFAULT NULL,
  `open_hold_bills` varchar(55) DEFAULT NULL,
  `close_register` varchar(55) DEFAULT NULL,
  `keyboard` tinyint(1) NOT NULL,
  `pos_printers` varchar(255) DEFAULT NULL,
  `java_applet` tinyint(1) NOT NULL,
  `product_button_color` varchar(20) NOT NULL DEFAULT 'default',
  `tooltips` tinyint(1) DEFAULT '1',
  `paypal_pro` tinyint(1) DEFAULT '0',
  `stripe` tinyint(1) DEFAULT '0',
  `rounding` tinyint(1) DEFAULT '0',
  `char_per_line` tinyint(4) DEFAULT '42',
  `pin_code` varchar(20) DEFAULT NULL,
  `purchase_code` varchar(100) DEFAULT 'purchase_code',
  `envato_username` varchar(50) DEFAULT 'envato_username',
  `version` varchar(10) DEFAULT '3.0.1.21',
  `show_item_img` tinyint(1) DEFAULT NULL,
  `pos_layout` tinyint(1) DEFAULT NULL,
  `display_qrcode` tinyint(1) DEFAULT NULL,
  `show_suspend_bar` tinyint(1) DEFAULT NULL,
  `show_payment_noted` tinyint(1) DEFAULT NULL,
  `payment_balance` tinyint(1) DEFAULT NULL,
  `authorize` tinyint(1) DEFAULT '0',
  `show_product_code` tinyint(1) unsigned DEFAULT '1',
  `auto_delivery` tinyint(1) unsigned DEFAULT '1',
  `in_out_rate` tinyint(1) DEFAULT '0',
  `discount` decimal(8,4) DEFAULT NULL,
  PRIMARY KEY (`pos_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_pos_settings
-- ----------------------------
INSERT INTO `erp_pos_settings` VALUES ('1', '22', '30', '2', '2', '1', '1', 'GST Reg', 'VAT Reg', '123456789', '987654321', 'BIXOLON SRP-350II', 'x1C', 'Ctrl+F3', 'Ctrl+Shift+M', 'Ctrl+Shift+C', 'Ctrl+Shift+A', 'Ctrl+F11', 'Ctrl+F12', 'F1', 'F2', 'F4', 'F7', 'F9', 'F3', 'F8', 'Ctrl+F1', 'Ctrl+F2', 'Ctrl+F10', '0', 'BIXOLON SRP-350II, BIXOLON SRP-350II', '0', 'primary', '0', '0', '0', '0', '42', null, 'cloud-net', '53d35644-a36e-45cd-b7ee-8dde3a08f83d', '3.3', '1', '0', '0', '1', '1', '1', '0', '0', '0', '0', '0.0000');

-- ----------------------------
-- Table structure for erp_price_groups
-- ----------------------------
DROP TABLE IF EXISTS `erp_price_groups`;
CREATE TABLE `erp_price_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_price_groups
-- ----------------------------

-- ----------------------------
-- Table structure for erp_principles
-- ----------------------------
DROP TABLE IF EXISTS `erp_principles`;
CREATE TABLE `erp_principles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `period` int(11) DEFAULT NULL,
  `term_type_id` int(11) DEFAULT NULL,
  `dateline` date DEFAULT '0000-00-00',
  `value` varchar(11) DEFAULT '0',
  `remark` varchar(150) DEFAULT NULL,
  `rate` decimal(11,0) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of erp_principles
-- ----------------------------

-- ----------------------------
-- Table structure for erp_products
-- ----------------------------
DROP TABLE IF EXISTS `erp_products`;
CREATE TABLE `erp_products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL,
  `name_kh` varchar(50) DEFAULT NULL,
  `name` char(255) NOT NULL,
  `unit` varchar(50) DEFAULT NULL,
  `cost` decimal(25,4) DEFAULT NULL,
  `price` decimal(25,4) NOT NULL,
  `alert_quantity` decimal(15,0) DEFAULT '20',
  `image` varchar(255) DEFAULT 'no_image.png',
  `category_id` int(11) NOT NULL,
  `subcategory_id` int(11) DEFAULT NULL,
  `cf1` varchar(255) DEFAULT NULL,
  `cf2` varchar(255) DEFAULT NULL,
  `cf3` varchar(255) DEFAULT NULL,
  `cf4` varchar(255) DEFAULT NULL,
  `cf5` varchar(255) DEFAULT NULL,
  `cf6` varchar(255) DEFAULT NULL,
  `quantity` decimal(15,4) DEFAULT '0.0000',
  `tax_rate` int(11) DEFAULT NULL,
  `track_quantity` tinyint(1) DEFAULT '1',
  `details` varchar(1000) DEFAULT NULL,
  `warehouse` int(11) DEFAULT NULL,
  `barcode_symbology` varchar(55) NOT NULL DEFAULT 'code128',
  `file` varchar(100) DEFAULT NULL,
  `product_details` text,
  `tax_method` tinyint(1) DEFAULT '0',
  `type` varchar(55) NOT NULL DEFAULT 'standard',
  `supplier1` int(11) DEFAULT NULL,
  `supplier1price` decimal(25,4) DEFAULT NULL,
  `supplier2` int(11) DEFAULT NULL,
  `supplier2price` decimal(25,4) DEFAULT NULL,
  `supplier3` int(11) DEFAULT NULL,
  `supplier3price` decimal(25,4) DEFAULT NULL,
  `supplier4` int(11) DEFAULT NULL,
  `supplier4price` decimal(25,4) DEFAULT NULL,
  `supplier5` int(11) DEFAULT NULL,
  `supplier5price` decimal(25,4) DEFAULT NULL,
  `no` int(11) DEFAULT NULL,
  `promotion` tinyint(1) DEFAULT '0',
  `promo_price` varchar(10) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `supplier1_part_no` varchar(50) DEFAULT NULL,
  `supplier2_part_no` varchar(50) DEFAULT NULL,
  `supplier3_part_no` varchar(50) DEFAULT NULL,
  `supplier4_part_no` varchar(50) DEFAULT NULL,
  `supplier5_part_no` varchar(50) DEFAULT NULL,
  `currentcy_code` varchar(10) DEFAULT NULL,
  `inactived` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `brand_id` int(11) DEFAULT NULL,
  `opening_stock` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `category_id` (`category_id`),
  KEY `id` (`id`),
  KEY `id_2` (`id`),
  KEY `category_id_2` (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_products
-- ----------------------------
INSERT INTO `erp_products` VALUES ('1', 'DK001', 'មូកា', 'Mocha', '1', '1.7007', '0.0000', '2', 'no_image.png', '1', '1', '', '', '', '', '', '', '10.0000', '1', '1', '', null, 'code128', null, '', '0', 'standard', null, null, null, null, null, null, null, null, null, null, null, null, '', '0000-00-00', '0000-00-00', null, null, null, null, null, 'USD', '0', null, null);
INSERT INTO `erp_products` VALUES ('2', 'DK02', '', 'Coco cola', '6', '0.3816', '1.0000', '0', 'no_image.png', '1', null, 'Sweet', '', '', '', 'Cool', '', '140.0000', '1', '1', '<p>Rith, Chin</p>', null, 'code128', null, '<p>Hello World!</p>', '1', 'standard', null, null, null, null, null, null, null, null, null, null, null, null, '', '0000-00-00', '0000-00-00', null, null, null, null, null, 'USD', '0', null, null);
INSERT INTO `erp_products` VALUES ('3', 'DK03', '', 'Pepsi', '6', '0.3568', '1.0000', '0', 'no_image.png', '1', null, '', '', '', '', '', '', '240.0000', '1', '1', '', null, 'code128', null, '', '0', 'standard', null, null, null, null, null, null, null, null, null, null, null, null, '', '0000-00-00', '0000-00-00', null, null, null, null, null, 'USD', '0', null, null);
INSERT INTO `erp_products` VALUES ('4', 'Digital 01', '', 'DG01', '1', null, '12.0000', '0', 'no_image.png', '1', null, '', '', '', '', '', '', '0.0000', '1', '0', '', null, 'code128', '0d8fb70f13f14f972f698e263bdd55f2.xlsx', '', '0', 'digital', null, null, null, null, null, null, null, null, null, null, null, null, '', '0000-00-00', '0000-00-00', null, null, null, null, null, 'USD', '0', null, null);
INSERT INTO `erp_products` VALUES ('5', 'RW01', '', 'Suger', '9', '1.2821', '1.0000', '0', 'no_image.png', '3', null, '', '', '', '', '', '', '10.0000', '1', '1', '', null, 'code128', null, '', '0', 'standard', null, null, null, null, null, null, null, null, null, null, null, null, '', '0000-00-00', '0000-00-00', null, null, null, null, null, 'USD', '0', null, null);
INSERT INTO `erp_products` VALUES ('6', 'RW02', '', 'Salt', '9', '1.1774', '1.0000', '0', 'no_image.png', '3', null, '', '', '', '', '', '', '45.0000', '1', '1', '', null, 'code128', null, '', '0', 'standard', null, null, null, null, null, null, null, null, null, null, null, null, '', '0000-00-00', '0000-00-00', null, null, null, null, null, 'USD', '0', null, null);
INSERT INTO `erp_products` VALUES ('7', 'FP01', '', 'Food1', '9', '2.1868', '0.0000', '0', 'no_image.png', '3', null, '', '', '', '', '', '', '70.0000', '1', '1', '', null, 'code128', null, '', '0', 'standard', null, null, null, null, null, null, null, null, null, null, null, null, '', '0000-00-00', '0000-00-00', null, null, null, null, null, 'USD', '0', null, null);
INSERT INTO `erp_products` VALUES ('8', 'FDC', '', 'F00d1 C', '1', '2.0653', '0.0000', '0', 'no_image.png', '4', null, '', '', '', '', '', '', '4.0000', '1', '1', '', null, 'code128', null, '', '0', 'standard', null, null, null, null, null, null, null, null, null, null, null, null, '', '0000-00-00', '0000-00-00', null, null, null, null, null, 'USD', '0', null, null);

-- ----------------------------
-- Table structure for erp_product_note
-- ----------------------------
DROP TABLE IF EXISTS `erp_product_note`;
CREATE TABLE `erp_product_note` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(55) NOT NULL,
  `name` varchar(55) NOT NULL,
  `price` decimal(25,4) NOT NULL,
  `image` varchar(55) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_product_note
-- ----------------------------
INSERT INTO `erp_product_note` VALUES ('1', 'CC', 'Chocolate', '0.0000', null);
INSERT INTO `erp_product_note` VALUES ('2', 'AP', 'Apple', '0.0000', null);

-- ----------------------------
-- Table structure for erp_product_photos
-- ----------------------------
DROP TABLE IF EXISTS `erp_product_photos`;
CREATE TABLE `erp_product_photos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `photo` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_product_photos
-- ----------------------------

-- ----------------------------
-- Table structure for erp_product_prices
-- ----------------------------
DROP TABLE IF EXISTS `erp_product_prices`;
CREATE TABLE `erp_product_prices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `price_group_id` int(11) NOT NULL,
  `price` decimal(25,4) NOT NULL,
  `currency_code` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  KEY `price_group_id` (`price_group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_product_prices
-- ----------------------------
INSERT INTO `erp_product_prices` VALUES ('1', '1', '1', '1500.0000', 'KHM');

-- ----------------------------
-- Table structure for erp_product_variants
-- ----------------------------
DROP TABLE IF EXISTS `erp_product_variants`;
CREATE TABLE `erp_product_variants` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `name` varchar(55) NOT NULL,
  `cost` decimal(25,4) DEFAULT NULL,
  `price` decimal(25,4) DEFAULT NULL,
  `quantity` decimal(15,4) DEFAULT NULL,
  `qty_unit` decimal(15,4) DEFAULT NULL,
  `currentcy_code` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_product_variants
-- ----------------------------
INSERT INTO `erp_product_variants` VALUES ('1', '2', 'can', '0.0000', '0.5000', '-100.0000', '1.0000', null);
INSERT INTO `erp_product_variants` VALUES ('2', '2', 'Case', '0.0159', '12.0000', '240.0000', '24.0000', null);
INSERT INTO `erp_product_variants` VALUES ('3', '3', 'can', '0.0000', '0.5000', '0.0000', '1.0000', null);
INSERT INTO `erp_product_variants` VALUES ('4', '3', 'Case', '0.0149', '12.0000', '240.0000', '24.0000', null);

-- ----------------------------
-- Table structure for erp_project_plan
-- ----------------------------
DROP TABLE IF EXISTS `erp_project_plan`;
CREATE TABLE `erp_project_plan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `biller_id` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `plan` varchar(150) DEFAULT NULL,
  `reference_no` varchar(150) DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of erp_project_plan
-- ----------------------------
INSERT INTO `erp_project_plan` VALUES ('1', '1', '2017-08-22 15:18:00', 'LH1', 'PN/1708/00002', '<p>Hello World!</p>', null);

-- ----------------------------
-- Table structure for erp_project_plan_items
-- ----------------------------
DROP TABLE IF EXISTS `erp_project_plan_items`;
CREATE TABLE `erp_project_plan_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_plan_id` int(11) DEFAULT NULL,
  `product_code` varchar(65) DEFAULT NULL,
  `product_name` varchar(250) DEFAULT NULL,
  `option_id` int(11) DEFAULT NULL,
  `quantity` decimal(25,8) DEFAULT NULL,
  `quantity_balance` decimal(25,8) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of erp_project_plan_items
-- ----------------------------

-- ----------------------------
-- Table structure for erp_promotions
-- ----------------------------
DROP TABLE IF EXISTS `erp_promotions`;
CREATE TABLE `erp_promotions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of erp_promotions
-- ----------------------------

-- ----------------------------
-- Table structure for erp_promotion_categories
-- ----------------------------
DROP TABLE IF EXISTS `erp_promotion_categories`;
CREATE TABLE `erp_promotion_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `promotion_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `discount` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of erp_promotion_categories
-- ----------------------------

-- ----------------------------
-- Table structure for erp_purchases
-- ----------------------------
DROP TABLE IF EXISTS `erp_purchases`;
CREATE TABLE `erp_purchases` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `biller_id` int(11) NOT NULL,
  `reference_no` varchar(55) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `supplier_id` int(11) NOT NULL,
  `supplier` varchar(55) NOT NULL,
  `warehouse_id` int(11) NOT NULL,
  `note` varchar(1000) NOT NULL,
  `total` decimal(25,4) DEFAULT NULL,
  `product_discount` decimal(25,4) DEFAULT NULL,
  `order_discount_id` varchar(20) DEFAULT NULL,
  `order_discount` decimal(25,4) DEFAULT NULL,
  `total_discount` decimal(25,4) DEFAULT NULL,
  `product_tax` decimal(25,4) DEFAULT NULL,
  `order_tax_id` int(11) DEFAULT NULL,
  `order_tax` decimal(25,4) DEFAULT NULL,
  `total_tax` decimal(25,4) DEFAULT '0.0000',
  `shipping` decimal(25,4) DEFAULT '0.0000',
  `grand_total` decimal(25,4) NOT NULL,
  `paid` decimal(25,4) NOT NULL DEFAULT '0.0000',
  `status` varchar(55) DEFAULT '',
  `payment_status` varchar(20) DEFAULT 'pending',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `attachment` varchar(55) DEFAULT NULL,
  `payment_term` tinyint(4) DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `return_id` int(11) DEFAULT NULL,
  `surcharge` decimal(25,4) NOT NULL DEFAULT '0.0000',
  `suspend_note` varchar(100) DEFAULT NULL,
  `reference_no_tax` varchar(100) NOT NULL,
  `tax_status` varchar(100) DEFAULT NULL,
  `opening_ap` tinyint(1) DEFAULT '0',
  `type_of_po` varchar(50) DEFAULT NULL,
  `order_ref` varchar(50) DEFAULT NULL,
  `request_ref` varchar(50) DEFAULT NULL,
  `paid_by` varchar(15) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  `account_code` varchar(20) DEFAULT '',
  `pur_refer` varchar(50) DEFAULT NULL,
  `purchase_type` int(1) DEFAULT NULL,
  `purchase_status` varchar(20) DEFAULT NULL,
  `tax_type` tinyint(1) DEFAULT NULL,
  `item_status` varchar(25) DEFAULT NULL,
  `container_no` varchar(50) DEFAULT NULL,
  `container_size` decimal(25,4) DEFAULT NULL,
  `invoice_no` varchar(50) DEFAULT NULL,
  `order_reference_no` int(50) DEFAULT NULL,
  `good_or_services` varchar(255) DEFAULT NULL,
  `acc_cate_separate` int(1) DEFAULT NULL,
  `cogs` decimal(25,4) DEFAULT NULL,
  `updated_count` int(4) unsigned zerofill DEFAULT '0000',
  `customer_id` int(11) DEFAULT NULL,
  `sale_id` int(11) DEFAULT NULL,
  `quote_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_purchases
-- ----------------------------
INSERT INTO `erp_purchases` VALUES ('1', '1', 'PO/1708/00001', '2017-08-25 00:00:00', '3', 'General Supplier', '1', '', '168.5000', '7.0000', '5%', '8.4300', '15.4300', '6.7700', '2', '21.0100', '27.7800', '50.0000', '231.0800', '231.0800', 'received', 'paid', '1', null, null, null, '0', '2017-09-14', null, '0.0000', null, '', null, '0', 'po', 'PAO/1708/00001', '', null, '1', null, null, null, null, null, null, null, null, null, null, null, null, '0.0000', '0000', null, null, '0');
INSERT INTO `erp_purchases` VALUES ('2', '1', 'PO/1708/00002', '2017-08-25 00:00:00', '3', 'General Supplier', '1', '', '168.5000', '7.0000', '5%', '8.4300', '15.4300', '6.7700', '2', '21.0100', '27.7800', '50.0000', '231.0800', '168.9200', 'received', 'partial', '1', null, null, null, '1', '2017-09-14', null, '0.0000', null, '', null, '0', 'po', 'PAO/1708/00001', '', null, '1', null, null, null, null, null, null, null, null, null, null, null, null, '0.0000', '0000', null, null, '0');

-- ----------------------------
-- Table structure for erp_purchases_audit
-- ----------------------------
DROP TABLE IF EXISTS `erp_purchases_audit`;
CREATE TABLE `erp_purchases_audit` (
  `id` int(11) NOT NULL,
  `biller_id` int(11) NOT NULL,
  `reference_no` varchar(55) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `supplier_id` int(11) NOT NULL,
  `supplier` varchar(55) NOT NULL,
  `warehouse_id` int(11) NOT NULL,
  `note` varchar(1000) NOT NULL,
  `total` decimal(25,4) DEFAULT NULL,
  `product_discount` decimal(25,4) DEFAULT NULL,
  `order_discount_id` varchar(20) DEFAULT NULL,
  `order_discount` decimal(25,4) DEFAULT NULL,
  `total_discount` decimal(25,4) DEFAULT NULL,
  `product_tax` decimal(25,4) DEFAULT NULL,
  `order_tax_id` int(11) DEFAULT NULL,
  `order_tax` decimal(25,4) DEFAULT NULL,
  `total_tax` decimal(25,4) DEFAULT '0.0000',
  `shipping` decimal(25,4) DEFAULT '0.0000',
  `grand_total` decimal(25,4) NOT NULL,
  `paid` decimal(25,4) NOT NULL DEFAULT '0.0000',
  `status` varchar(55) DEFAULT '',
  `payment_status` varchar(20) DEFAULT 'pending',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `attachment` varchar(55) DEFAULT NULL,
  `payment_term` tinyint(4) DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `return_id` int(11) DEFAULT NULL,
  `surcharge` decimal(25,4) NOT NULL DEFAULT '0.0000',
  `suspend_note` varchar(100) DEFAULT NULL,
  `reference_no_tax` varchar(100) NOT NULL,
  `tax_status` varchar(100) DEFAULT NULL,
  `opening_ap` tinyint(1) DEFAULT '0',
  `type_of_po` varchar(50) DEFAULT NULL,
  `order_ref` varchar(50) DEFAULT NULL,
  `request_ref` varchar(50) DEFAULT NULL,
  `paid_by` varchar(15) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  `account_code` varchar(20) DEFAULT '',
  `pur_refer` varchar(50) DEFAULT NULL,
  `purchase_type` int(1) DEFAULT NULL,
  `purchase_status` varchar(20) DEFAULT NULL,
  `tax_type` tinyint(1) DEFAULT NULL,
  `item_status` varchar(25) DEFAULT NULL,
  `container_no` varchar(50) DEFAULT NULL,
  `container_size` decimal(25,4) DEFAULT NULL,
  `invoice_no` varchar(50) DEFAULT NULL,
  `order_reference_no` int(50) DEFAULT NULL,
  `good_or_services` varchar(255) DEFAULT NULL,
  `acc_cate_separate` int(1) DEFAULT NULL,
  `audit_id` int(11) NOT NULL AUTO_INCREMENT,
  `audit_created_by` int(11) NOT NULL,
  `audit_record_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `audit_type` varchar(55) NOT NULL,
  PRIMARY KEY (`audit_id`),
  KEY `id` (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_purchases_audit
-- ----------------------------
INSERT INTO `erp_purchases_audit` VALUES ('1', '1', 'PO/1708/00001', '2017-08-25 00:00:00', '3', 'General Supplier', '1', '', '168.5000', '7.0000', '5%', '8.4300', '15.4300', '6.7700', '2', '21.0100', '27.7800', '50.0000', '231.0800', '0.0000', 'received', 'due', '1', null, null, null, '1', '2017-09-14', null, '0.0000', null, '', null, '0', 'po', 'PAO/1708/00001', '', null, '1', null, null, null, null, null, null, null, null, null, null, null, null, '1', '0', '2017-08-25 17:56:43', 'UPDATED');
INSERT INTO `erp_purchases_audit` VALUES ('2', '1', 'PO/1708/00002', '2017-08-25 00:00:00', '3', 'General Supplier', '1', '', '168.5000', '7.0000', '5%', '8.4300', '15.4300', '6.7700', '2', '21.0100', '27.7800', '50.0000', '231.0800', '0.0000', 'received', 'due', '1', null, null, null, '1', '2017-09-14', null, '0.0000', null, '', null, '0', 'po', 'PAO/1708/00001', '', null, '1', null, null, null, null, null, null, null, null, null, null, null, null, '2', '0', '2017-08-25 17:56:43', 'UPDATED');

-- ----------------------------
-- Table structure for erp_purchases_order
-- ----------------------------
DROP TABLE IF EXISTS `erp_purchases_order`;
CREATE TABLE `erp_purchases_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `biller_id` int(11) NOT NULL,
  `reference_no` varchar(55) NOT NULL,
  `purchase_ref` varchar(255) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `supplier_id` int(11) NOT NULL,
  `supplier` varchar(55) NOT NULL,
  `warehouse_id` int(11) NOT NULL,
  `note` varchar(1000) NOT NULL,
  `total` decimal(25,4) DEFAULT NULL,
  `product_discount` decimal(25,4) DEFAULT NULL,
  `order_discount_id` varchar(20) DEFAULT NULL,
  `order_discount` decimal(25,4) DEFAULT NULL,
  `total_discount` decimal(25,4) DEFAULT NULL,
  `product_tax` decimal(25,4) DEFAULT NULL,
  `order_tax_id` int(11) DEFAULT NULL,
  `order_tax` decimal(25,4) DEFAULT NULL,
  `total_tax` decimal(25,4) DEFAULT '0.0000',
  `shipping` decimal(25,4) DEFAULT '0.0000',
  `grand_total` decimal(25,4) NOT NULL,
  `paid` decimal(25,4) NOT NULL DEFAULT '0.0000',
  `status` varchar(55) DEFAULT '',
  `payment_status` varchar(20) DEFAULT 'pending',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `attachment` varchar(55) DEFAULT NULL,
  `payment_term` tinyint(4) DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `return_id` int(11) DEFAULT NULL,
  `surcharge` decimal(25,4) NOT NULL DEFAULT '0.0000',
  `suspend_note` varchar(100) DEFAULT NULL,
  `reference_no_tax` varchar(100) NOT NULL,
  `tax_status` varchar(100) DEFAULT NULL,
  `purchase_type` int(1) DEFAULT NULL,
  `purchase_status` varchar(20) DEFAULT NULL,
  `tax_type` tinyint(1) DEFAULT NULL,
  `order_status` varchar(15) DEFAULT 'pending',
  `request_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_purchases_order
-- ----------------------------
INSERT INTO `erp_purchases_order` VALUES ('1', '1', 'PAO/1708/00001', '', '2017-08-25 17:40:00', '3', 'General Supplier', '1', '', '263.5000', '12.0000', '5%', '13.1750', '25.1800', '6.7700', '2', '30.0300', '36.8000', '50.0000', '330.3600', '0.0000', 'approved', null, '1', null, null, null, null, null, null, '0.0000', null, '', null, null, null, null, 'completed', '1');

-- ----------------------------
-- Table structure for erp_purchases_order_audit
-- ----------------------------
DROP TABLE IF EXISTS `erp_purchases_order_audit`;
CREATE TABLE `erp_purchases_order_audit` (
  `id` int(11) NOT NULL,
  `biller_id` int(11) NOT NULL,
  `reference_no` varchar(55) NOT NULL,
  `purchase_ref` varchar(255) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `supplier_id` int(11) NOT NULL,
  `supplier` varchar(55) NOT NULL,
  `warehouse_id` int(11) NOT NULL,
  `note` varchar(1000) NOT NULL,
  `total` decimal(25,4) DEFAULT NULL,
  `product_discount` decimal(25,4) DEFAULT NULL,
  `order_discount_id` varchar(20) DEFAULT NULL,
  `order_discount` decimal(25,4) DEFAULT NULL,
  `total_discount` decimal(25,4) DEFAULT NULL,
  `product_tax` decimal(25,4) DEFAULT NULL,
  `order_tax_id` int(11) DEFAULT NULL,
  `order_tax` decimal(25,4) DEFAULT NULL,
  `total_tax` decimal(25,4) DEFAULT '0.0000',
  `shipping` decimal(25,4) DEFAULT '0.0000',
  `grand_total` decimal(25,4) NOT NULL,
  `paid` decimal(25,4) NOT NULL DEFAULT '0.0000',
  `status` varchar(55) DEFAULT '',
  `payment_status` varchar(20) DEFAULT 'pending',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `attachment` varchar(55) DEFAULT NULL,
  `payment_term` tinyint(4) DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `return_id` int(11) DEFAULT NULL,
  `surcharge` decimal(25,4) NOT NULL DEFAULT '0.0000',
  `suspend_note` varchar(100) DEFAULT NULL,
  `reference_no_tax` varchar(100) NOT NULL,
  `tax_status` varchar(100) DEFAULT NULL,
  `purchase_type` int(1) DEFAULT NULL,
  `purchase_status` varchar(20) DEFAULT NULL,
  `tax_type` tinyint(1) DEFAULT NULL,
  `order_status` varchar(15) DEFAULT 'pending',
  `request_id` int(11) DEFAULT NULL,
  `audit_id` int(11) NOT NULL AUTO_INCREMENT,
  `audit_created_by` int(11) NOT NULL,
  `audit_record_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `audit_type` varchar(55) NOT NULL,
  PRIMARY KEY (`audit_id`),
  KEY `id` (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_purchases_order_audit
-- ----------------------------
INSERT INTO `erp_purchases_order_audit` VALUES ('1', '1', 'PAO/1708/00001', '', '2017-08-25 17:40:00', '3', 'General Supplier', '1', '', '263.5000', '12.0000', '5%', '13.1750', '25.1800', '6.7700', '2', '30.0300', '36.8000', '50.0000', '330.3600', '0.0000', 'pending', null, '1', null, null, null, null, null, null, '0.0000', null, '', null, null, null, null, 'pending', '1', '1', '0', '2017-08-25 17:37:30', 'UPDATED');
INSERT INTO `erp_purchases_order_audit` VALUES ('1', '1', 'PAO/1708/00001', '', '2017-08-25 17:40:00', '3', 'General Supplier', '1', '', '263.5000', '12.0000', '5%', '13.1750', '25.1800', '6.7700', '2', '30.0300', '36.8000', '50.0000', '330.3600', '0.0000', 'approved', null, '1', null, null, null, null, null, null, '0.0000', null, '', null, null, null, null, 'pending', '1', '2', '0', '2017-08-25 17:42:58', 'UPDATED');
INSERT INTO `erp_purchases_order_audit` VALUES ('1', '1', 'PAO/1708/00001', '', '2017-08-25 17:40:00', '3', 'General Supplier', '1', '', '263.5000', '12.0000', '5%', '13.1750', '25.1800', '6.7700', '2', '30.0300', '36.8000', '50.0000', '330.3600', '0.0000', 'approved', null, '1', null, null, null, null, null, null, '0.0000', null, '', null, null, null, null, 'partial', '1', '3', '0', '2017-08-25 17:55:03', 'UPDATED');

-- ----------------------------
-- Table structure for erp_purchases_request
-- ----------------------------
DROP TABLE IF EXISTS `erp_purchases_request`;
CREATE TABLE `erp_purchases_request` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `biller_id` int(11) NOT NULL,
  `reference_no` varchar(55) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `supplier_id` int(11) NOT NULL,
  `supplier` varchar(55) NOT NULL,
  `warehouse_id` int(11) NOT NULL,
  `note` varchar(1000) NOT NULL,
  `total` decimal(25,4) DEFAULT NULL,
  `product_discount` decimal(25,4) DEFAULT NULL,
  `order_discount_id` varchar(20) DEFAULT NULL,
  `order_discount` decimal(25,4) DEFAULT NULL,
  `total_discount` decimal(25,4) DEFAULT NULL,
  `product_tax` decimal(25,4) DEFAULT NULL,
  `order_tax_id` int(11) DEFAULT NULL,
  `order_tax` decimal(25,4) DEFAULT NULL,
  `total_tax` decimal(25,4) DEFAULT '0.0000',
  `shipping` decimal(25,4) DEFAULT '0.0000',
  `grand_total` decimal(25,4) NOT NULL,
  `paid` decimal(25,4) NOT NULL DEFAULT '0.0000',
  `status` varchar(55) DEFAULT '',
  `payment_status` varchar(20) DEFAULT 'pending',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `attachment` varchar(55) DEFAULT NULL,
  `payment_term` tinyint(4) DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `return_id` int(11) DEFAULT NULL,
  `surcharge` decimal(25,4) NOT NULL DEFAULT '0.0000',
  `suspend_note` varchar(100) DEFAULT NULL,
  `reference_no_tax` varchar(100) NOT NULL,
  `tax_status` varchar(100) DEFAULT NULL,
  `opening_ap` tinyint(1) DEFAULT '0',
  `order_status` varchar(20) DEFAULT 'pending',
  PRIMARY KEY (`id`),
  KEY `id` (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_purchases_request
-- ----------------------------
INSERT INTO `erp_purchases_request` VALUES ('1', '1', 'PQ/1708/00001', '2017-08-25 17:40:00', '3', 'General Supplier', '1', '', '263.5000', '12.0000', null, '0.0000', '12.0000', '6.7700', null, '0.0000', '6.7700', '0.0000', '263.5000', '0.0000', 'approved', null, '1', null, null, null, null, null, null, '0.0000', null, '', null, '0', 'completed');

-- ----------------------------
-- Table structure for erp_purchase_items
-- ----------------------------
DROP TABLE IF EXISTS `erp_purchase_items`;
CREATE TABLE `erp_purchase_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `delivery_id` int(11) NOT NULL,
  `purchase_id` int(11) DEFAULT NULL,
  `transfer_id` int(11) DEFAULT NULL,
  `product_id` int(11) NOT NULL,
  `product_code` varchar(50) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `option_id` int(11) DEFAULT NULL,
  `product_type` varchar(55) DEFAULT NULL,
  `net_unit_cost` decimal(25,8) NOT NULL,
  `quantity` decimal(15,8) NOT NULL,
  `warehouse_id` int(11) NOT NULL,
  `tax_method` int(11) DEFAULT NULL,
  `item_tax` decimal(25,8) DEFAULT NULL,
  `tax_rate_id` int(11) DEFAULT NULL,
  `tax` varchar(20) DEFAULT NULL,
  `discount` varchar(20) DEFAULT NULL,
  `item_discount` decimal(25,8) DEFAULT NULL,
  `expiry` date DEFAULT NULL,
  `subtotal` decimal(25,8) NOT NULL,
  `quantity_balance` decimal(15,8) DEFAULT '0.00000000',
  `date` date NOT NULL,
  `status` varchar(50) NOT NULL,
  `unit_cost` decimal(25,8) DEFAULT NULL,
  `real_unit_cost` decimal(25,8) DEFAULT NULL,
  `quantity_received` decimal(15,8) DEFAULT NULL,
  `supplier_part_no` varchar(50) DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `job_name` varchar(255) DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `convert_id` int(11) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `reference` varchar(255) DEFAULT NULL,
  `opening_stock` int(11) DEFAULT NULL,
  `create_id` int(11) DEFAULT NULL,
  `returned` decimal(15,8) DEFAULT NULL,
  `acc_cate_separate` tinyint(1) DEFAULT NULL,
  `specific_tax_on_certain_merchandise_and_services` int(10) DEFAULT NULL,
  `accommodation_tax` int(10) DEFAULT NULL,
  `public_lighting_tax` int(10) DEFAULT NULL,
  `other_tax` int(10) DEFAULT NULL,
  `payment_of_profit_tax` int(10) DEFAULT NULL,
  `performance_royalty_intangible` int(10) DEFAULT NULL,
  `payment_interest_non_bank` int(10) DEFAULT NULL,
  `payment_interest_taxpayer_fixed` int(10) DEFAULT NULL,
  `payment_interest_taxpayer_non_fixed` int(10) DEFAULT NULL,
  `payment_rental_immovable_property` int(10) DEFAULT NULL,
  `payment_of_interest` int(10) DEFAULT NULL,
  `payment_royalty_rental_income_related` int(10) DEFAULT NULL,
  `payment_management_technical` int(10) DEFAULT NULL,
  `payment_dividend` int(10) DEFAULT NULL,
  `withholding_tax_on_resident` int(10) DEFAULT NULL,
  `withholding_tax_on_non_resident` int(10) DEFAULT NULL,
  `transaction_type` varchar(25) DEFAULT '' COMMENT 'Ex: SALE or PURCHASE',
  `transaction_id` int(11) DEFAULT NULL,
  `net_shipping` decimal(25,8) DEFAULT NULL,
  `cb_avg` decimal(25,8) DEFAULT '0.00000000' COMMENT '0',
  `cb_qty` decimal(25,8) DEFAULT '0.00000000' COMMENT '0',
  `product_noted` varchar(255) DEFAULT NULL,
  `serial_no` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `purchase_id` (`purchase_id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_purchase_items
-- ----------------------------
INSERT INTO `erp_purchase_items` VALUES ('1', '0', '1', null, '5', 'RW01', 'Suger', '0', null, '0.00000000', '1.00000000', '1', '0', '0.00000000', '1', '0', '0', '0.00000000', null, '0.00000000', '1.00000000', '2017-08-25', 'received', '0.00000000', '1.28115433', null, null, null, null, null, null, null, null, null, '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, 'PURCHASE', '1', '0.00000000', '0.00000000', '0.00000000', null, null);
INSERT INTO `erp_purchase_items` VALUES ('2', '0', '1', null, '3', 'DK03', 'Pepsi', '4', null, '6.54545455', '5.00000000', '1', '0', '3.27272727', '2', '10%', '0', '0.00000000', null, '36.00000000', '120.00000000', '2017-08-25', 'received', '7.20000000', '0.35653646', null, null, null, null, null, null, null, null, null, '4', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, 'PURCHASE', '2', '10.11804384', '0.30000000', '0.00000000', null, null);
INSERT INTO `erp_purchase_items` VALUES ('3', '0', '1', null, '2', 'DK02', 'Coco cola', '2', null, '7.00000000', '5.00000000', '1', '1', '3.50000000', '2', '10%', '1', '1.00000000', null, '38.50000000', '120.00000000', '2017-08-25', 'received', '7.20000000', '0.38129593', null, null, null, null, null, null, null, null, null, '3', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, 'PURCHASE', '3', '10.82068578', '0.30000000', '0.00000000', null, null);
INSERT INTO `erp_purchase_items` VALUES ('4', '0', '1', null, '6', 'RW02', 'Salt', '0', null, '0.90000000', '50.00000000', '1', '0', '0.00000000', '1', '0', '10%', '5.00000000', null, '45.00000000', '50.00000000', '2017-08-25', 'received', '1.00000000', '1.17657030', null, null, null, null, null, null, null, null, null, '2', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, 'PURCHASE', '4', '13.91231029', '0.00000000', '0.00000000', null, null);
INSERT INTO `erp_purchase_items` VALUES ('5', '0', '1', null, '5', 'RW01', 'Suger', '0', null, '0.98000000', '50.00000000', '1', '0', '0.00000000', '1', '0', '1', '1.00000000', null, '49.00000000', '50.00000000', '2017-08-25', 'received', '1.00000000', '1.28115433', null, null, null, null, null, null, null, null, null, '1', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, 'PURCHASE', '5', '15.14896009', '0.00000000', '0.00000000', null, null);
INSERT INTO `erp_purchase_items` VALUES ('6', '0', '2', null, '3', 'DK03', 'Pepsi', '4', null, '6.54545455', '5.00000000', '1', '0', '3.27272727', '2', '10%', '0', '0.00000000', null, '36.00000000', '120.00000000', '2017-08-25', 'received', '7.20000000', '0.35677215', null, null, null, null, null, null, null, null, null, '4', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, 'PURCHASE', '6', '10.11804384', '0.35650000', '120.00000000', null, null);
INSERT INTO `erp_purchase_items` VALUES ('7', '0', '2', null, '2', 'DK02', 'Coco cola', '2', null, '7.00000000', '5.00000000', '1', '1', '3.50000000', '2', '10%', '1', '1.00000000', null, '38.50000000', '120.00000000', '2017-08-25', 'received', '7.20000000', '0.38156952', null, null, null, null, null, null, null, null, null, '3', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, 'PURCHASE', '7', '10.82068578', '0.38130000', '120.00000000', null, null);
INSERT INTO `erp_purchase_items` VALUES ('8', '0', '2', null, '6', 'RW02', 'Salt', '0', null, '0.90000000', '50.00000000', '1', '0', '0.00000000', '1', '0', '10%', '5.00000000', null, '45.00000000', '50.00000000', '2017-08-25', 'received', '1.00000000', '1.17742310', null, null, null, null, null, null, null, null, null, '2', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, 'PURCHASE', '8', '13.91231029', '1.17660000', '50.00000000', null, null);
INSERT INTO `erp_purchase_items` VALUES ('9', '0', '2', null, '5', 'RW01', 'Suger', '0', null, '0.98000000', '50.00000000', '1', '0', '0.00000000', '1', '0', '1', '1.00000000', null, '49.00000000', '50.00000000', '2017-08-25', 'received', '1.00000000', '1.28208079', null, null, null, null, null, null, null, null, null, '1', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, 'PURCHASE', '9', '15.14896009', '1.28120000', '51.00000000', null, null);
INSERT INTO `erp_purchase_items` VALUES ('10', '0', null, null, '5', 'RW01', 'Suger', '0', null, '0.00000000', '-100.00000000', '1', null, '0.00000000', null, null, null, null, null, '0.00000000', '-100.00000000', '0000-00-00', 'received', null, null, null, null, null, null, null, '1', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, 'CONVERT', '1', null, '0.00000000', '0.00000000', null, null);
INSERT INTO `erp_purchase_items` VALUES ('11', '0', null, null, '6', 'RW02', 'Salt', '0', null, '0.00000000', '-50.00000000', '1', null, '0.00000000', null, null, null, null, null, '0.00000000', '-50.00000000', '0000-00-00', 'received', null, null, null, null, null, null, null, '1', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, 'CONVERT', '2', null, '0.00000000', '0.00000000', null, null);
INSERT INTO `erp_purchase_items` VALUES ('12', '0', null, null, '2', 'DK02', 'Coco cola', '1', null, '0.00000000', '-100.00000000', '1', null, '0.00000000', null, null, null, null, null, '0.00000000', '-100.00000000', '0000-00-00', 'received', null, null, null, null, null, null, null, '1', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, 'CONVERT', '3', null, '0.00000000', '0.00000000', null, null);
INSERT INTO `erp_purchase_items` VALUES ('13', '0', null, null, '7', 'FP01', 'Food1', '0', null, '0.00000000', '100.00000000', '1', null, '0.00000000', null, null, null, null, null, '0.00000000', '100.00000000', '0000-00-00', 'received', null, null, null, null, null, null, null, '1', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, 'CONVERT', '4', null, '0.00000000', '0.00000000', null, null);
INSERT INTO `erp_purchase_items` VALUES ('14', '0', null, null, '8', 'FDC', 'F00d1 C', null, null, '0.00000000', '3.00000000', '1', null, '0.00000000', null, null, null, null, null, '0.00000000', '3.00000000', '0000-00-00', 'received', null, null, null, null, null, null, null, '1', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, 'CONVERT', '5', null, '0.00000000', '0.00000000', null, null);
INSERT INTO `erp_purchase_items` VALUES ('15', '0', null, null, '5', 'RW01', 'Suger', '0', null, '0.00000000', '-10.00000000', '1', null, '0.00000000', null, null, null, null, null, '0.00000000', '-10.00000000', '0000-00-00', 'received', null, null, null, null, null, null, null, '2', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, 'CONVERT', '6', null, '0.00000000', '0.00000000', null, null);
INSERT INTO `erp_purchase_items` VALUES ('16', '0', null, null, '6', 'RW02', 'Salt', '0', null, '0.00000000', '-5.00000000', '1', null, '0.00000000', null, null, null, null, null, '0.00000000', '-5.00000000', '0000-00-00', 'received', null, null, null, null, null, null, null, '2', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, 'CONVERT', '7', null, '0.00000000', '0.00000000', null, null);
INSERT INTO `erp_purchase_items` VALUES ('17', '0', null, null, '1', 'DK001', 'Mocha', '0', null, '0.00000000', '10.00000000', '1', null, '0.00000000', null, null, null, null, null, '0.00000000', '10.00000000', '0000-00-00', 'received', null, null, null, null, null, null, null, '2', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, 'CONVERT', '8', null, '0.00000000', '0.00000000', null, null);
INSERT INTO `erp_purchase_items` VALUES ('18', '0', null, null, '8', 'FDC', 'F00d1 C', '0', null, '0.00000000', '1.00000000', '1', null, '0.00000000', null, null, null, null, null, '0.00000000', '1.00000000', '0000-00-00', 'received', null, null, null, null, null, null, null, '2', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, 'CONVERT', '9', null, '0.00000000', '0.00000000', null, null);
INSERT INTO `erp_purchase_items` VALUES ('19', '0', null, null, '5', '', '', null, null, '0.00000000', '9.00000000', '1', null, null, null, null, null, null, null, '0.00000000', '9.00000000', '2017-08-25', 'received', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, 'ADJUSTMENT', '1', null, '0.00000000', '0.00000000', null, null);
INSERT INTO `erp_purchase_items` VALUES ('20', '0', null, null, '5', '', '', null, null, '0.00000000', '10.00000000', '1', null, null, null, null, null, null, null, '0.00000000', '10.00000000', '2017-08-25', 'received', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, 'ADJUSTMENT', '2', null, '0.00000000', '0.00000000', null, null);
INSERT INTO `erp_purchase_items` VALUES ('21', '0', null, null, '7', 'FP01', 'Food1', '7', null, '0.00000000', '-30.00000000', '1', null, null, null, null, null, null, null, '0.00000000', '-30.00000000', '2017-08-25', 'received', null, null, null, null, null, null, null, null, null, 'ES/1708/00001', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, 'USING STOCK', '1', null, '0.00000000', '0.00000000', null, null);

-- ----------------------------
-- Table structure for erp_purchase_items_audit
-- ----------------------------
DROP TABLE IF EXISTS `erp_purchase_items_audit`;
CREATE TABLE `erp_purchase_items_audit` (
  `id` int(11) NOT NULL,
  `delivery_id` int(11) NOT NULL,
  `purchase_id` int(11) DEFAULT NULL,
  `transfer_id` int(11) DEFAULT NULL,
  `product_id` int(11) NOT NULL,
  `product_code` varchar(50) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `option_id` int(11) DEFAULT NULL,
  `product_type` varchar(55) DEFAULT NULL,
  `net_unit_cost` decimal(25,4) NOT NULL,
  `quantity` decimal(15,4) NOT NULL,
  `warehouse_id` int(11) NOT NULL,
  `item_tax` decimal(25,4) DEFAULT NULL,
  `tax_rate_id` int(11) DEFAULT NULL,
  `tax` varchar(20) DEFAULT NULL,
  `discount` varchar(20) DEFAULT NULL,
  `item_discount` decimal(25,4) DEFAULT NULL,
  `expiry` date DEFAULT NULL,
  `subtotal` decimal(25,4) NOT NULL,
  `quantity_balance` decimal(15,4) DEFAULT '0.0000',
  `date` date NOT NULL,
  `status` varchar(50) NOT NULL,
  `unit_cost` decimal(25,4) DEFAULT NULL,
  `real_unit_cost` decimal(25,4) DEFAULT NULL,
  `quantity_received` decimal(15,4) DEFAULT NULL,
  `supplier_part_no` varchar(50) DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `job_name` varchar(255) DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `convert_id` int(11) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `reference` varchar(255) DEFAULT NULL,
  `opening_stock` int(11) DEFAULT NULL,
  `create_id` int(11) DEFAULT NULL,
  `returned` decimal(15,4) DEFAULT NULL,
  `acc_cate_separate` tinyint(1) DEFAULT NULL,
  `specific_tax_on_certain_merchandise_and_services` int(10) DEFAULT NULL,
  `accommodation_tax` int(10) DEFAULT NULL,
  `public_lighting_tax` int(10) DEFAULT NULL,
  `other_tax` int(10) DEFAULT NULL,
  `payment_of_profit_tax` int(10) DEFAULT NULL,
  `performance_royalty_intangible` int(10) DEFAULT NULL,
  `payment_interest_non_bank` int(10) DEFAULT NULL,
  `payment_interest_taxpayer_fixed` int(10) DEFAULT NULL,
  `payment_interest_taxpayer_non_fixed` int(10) DEFAULT NULL,
  `payment_rental_immovable_property` int(10) DEFAULT NULL,
  `payment_of_interest` int(10) DEFAULT NULL,
  `payment_royalty_rental_income_related` int(10) DEFAULT NULL,
  `payment_management_technical` int(10) DEFAULT NULL,
  `payment_dividend` int(10) DEFAULT NULL,
  `withholding_tax_on_resident` int(10) DEFAULT NULL,
  `withholding_tax_on_non_resident` int(10) DEFAULT NULL,
  `audit_id` int(11) NOT NULL AUTO_INCREMENT,
  `audit_created_by` int(11) NOT NULL,
  `audit_record_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `audit_type` varchar(55) NOT NULL,
  PRIMARY KEY (`audit_id`),
  KEY `purchase_id` (`purchase_id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_purchase_items_audit
-- ----------------------------
INSERT INTO `erp_purchase_items_audit` VALUES ('1', '0', '1', null, '5', 'RW01', 'Suger', '0', null, '0.0000', '1.0000', '1', '0.0000', '1', '0', '0', '0.0000', null, '0.0000', '1.0000', '2017-08-25', 'received', '0.0000', '1.2812', null, null, null, null, null, null, null, null, null, '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '1', '0', '2017-08-25 17:42:56', 'UPDATED');
INSERT INTO `erp_purchase_items_audit` VALUES ('2', '0', '1', null, '3', 'DK03', 'Pepsi', '4', null, '6.5455', '5.0000', '1', '3.2727', '2', '10%', '0', '0.0000', null, '36.0000', '120.0000', '2017-08-25', 'received', '7.2000', '0.3565', null, null, null, null, null, null, null, null, null, '4', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '2', '0', '2017-08-25 17:42:56', 'UPDATED');
INSERT INTO `erp_purchase_items_audit` VALUES ('3', '0', '1', null, '2', 'DK02', 'Coco cola', '2', null, '7.0000', '5.0000', '1', '3.5000', '2', '10%', '1', '1.0000', null, '38.5000', '120.0000', '2017-08-25', 'received', '7.2000', '0.3813', null, null, null, null, null, null, null, null, null, '3', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '3', '0', '2017-08-25 17:42:57', 'UPDATED');
INSERT INTO `erp_purchase_items_audit` VALUES ('4', '0', '1', null, '6', 'RW02', 'Salt', '0', null, '0.9000', '50.0000', '1', '0.0000', '1', '0', '10%', '5.0000', null, '45.0000', '50.0000', '2017-08-25', 'received', '1.0000', '1.1766', null, null, null, null, null, null, null, null, null, '2', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '4', '0', '2017-08-25 17:42:57', 'UPDATED');
INSERT INTO `erp_purchase_items_audit` VALUES ('5', '0', '1', null, '5', 'RW01', 'Suger', '0', null, '0.9800', '50.0000', '1', '0.0000', '1', '0', '1', '1.0000', null, '49.0000', '50.0000', '2017-08-25', 'received', '1.0000', '1.2812', null, null, null, null, null, null, null, null, null, '1', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '5', '0', '2017-08-25 17:42:58', 'UPDATED');
INSERT INTO `erp_purchase_items_audit` VALUES ('6', '0', '2', null, '3', 'DK03', 'Pepsi', '4', null, '6.5455', '5.0000', '1', '3.2727', '2', '10%', '0', '0.0000', null, '36.0000', '120.0000', '2017-08-25', 'received', '7.2000', '0.3568', null, null, null, null, null, null, null, null, null, '4', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '6', '0', '2017-08-25 17:55:00', 'UPDATED');
INSERT INTO `erp_purchase_items_audit` VALUES ('7', '0', '2', null, '2', 'DK02', 'Coco cola', '2', null, '7.0000', '5.0000', '1', '3.5000', '2', '10%', '1', '1.0000', null, '38.5000', '120.0000', '2017-08-25', 'received', '7.2000', '0.3816', null, null, null, null, null, null, null, null, null, '3', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '7', '0', '2017-08-25 17:55:01', 'UPDATED');
INSERT INTO `erp_purchase_items_audit` VALUES ('8', '0', '2', null, '6', 'RW02', 'Salt', '0', null, '0.9000', '50.0000', '1', '0.0000', '1', '0', '10%', '5.0000', null, '45.0000', '50.0000', '2017-08-25', 'received', '1.0000', '1.1774', null, null, null, null, null, null, null, null, null, '2', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '8', '0', '2017-08-25 17:55:01', 'UPDATED');
INSERT INTO `erp_purchase_items_audit` VALUES ('9', '0', '2', null, '5', 'RW01', 'Suger', '0', null, '0.9800', '50.0000', '1', '0.0000', '1', '0', '1', '1.0000', null, '49.0000', '50.0000', '2017-08-25', 'received', '1.0000', '1.2821', null, null, null, null, null, null, null, null, null, '1', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '9', '0', '2017-08-25 17:55:02', 'UPDATED');

-- ----------------------------
-- Table structure for erp_purchase_order_items
-- ----------------------------
DROP TABLE IF EXISTS `erp_purchase_order_items`;
CREATE TABLE `erp_purchase_order_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `purchase_id` int(11) DEFAULT NULL,
  `transfer_id` int(11) DEFAULT NULL,
  `product_id` int(11) NOT NULL,
  `product_code` varchar(50) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `option_id` int(11) DEFAULT NULL,
  `net_unit_cost` decimal(25,8) NOT NULL,
  `quantity` decimal(15,8) NOT NULL,
  `quantity_po` decimal(15,8) NOT NULL,
  `warehouse_id` int(11) NOT NULL,
  `tax_method` int(11) DEFAULT NULL,
  `item_tax` decimal(25,8) DEFAULT NULL,
  `tax_rate_id` int(11) DEFAULT NULL,
  `tax` varchar(20) DEFAULT NULL,
  `discount` varchar(20) DEFAULT NULL,
  `item_discount` decimal(25,8) DEFAULT NULL,
  `expiry` date DEFAULT NULL,
  `subtotal` decimal(25,8) NOT NULL,
  `quantity_balance` decimal(15,8) DEFAULT '0.00000000',
  `date` date NOT NULL,
  `status` varchar(50) NOT NULL,
  `unit_cost` decimal(25,8) DEFAULT NULL,
  `real_unit_cost` decimal(25,8) DEFAULT NULL,
  `quantity_received` decimal(15,8) unsigned NOT NULL DEFAULT '0.00000000',
  `supplier_part_no` varchar(50) DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `price` decimal(25,8) DEFAULT NULL,
  `create_order` varchar(2) DEFAULT '0',
  `create_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `purchase_id` (`purchase_id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_purchase_order_items
-- ----------------------------
INSERT INTO `erp_purchase_order_items` VALUES ('1', '1', null, '5', 'RW01', 'Suger', '0', '0.99000000', '100.00000000', '0.00000000', '1', '0', '0.00000000', '1', '0', '1', '1.00000000', null, '99.00000000', '100.00000000', '2017-08-25', 'pending', '1.00000000', '1.17785579', '101.00000000', null, '0', '1.00000000', '0', null);
INSERT INTO `erp_purchase_order_items` VALUES ('2', '1', null, '6', 'RW02', 'Salt', '0', '0.90000000', '100.00000000', '0.00000000', '1', '0', '0.00000000', '1', '0', '10%', '10.00000000', null, '90.00000000', '100.00000000', '2017-08-25', 'pending', '1.00000000', '1.07077799', '100.00000000', null, '0', '1.00000000', '0', null);
INSERT INTO `erp_purchase_order_items` VALUES ('3', '1', null, '2', 'DK02', 'Coco cola', '2', '7.00000000', '5.00000000', '0.00000000', '1', '1', '3.50000000', '2', '10%', '1', '1.00000000', null, '38.50000000', '120.00000000', '2017-08-25', 'pending', '7.20000000', '0.38171252', '10.00000000', null, '0', '1.00000000', '0', null);
INSERT INTO `erp_purchase_order_items` VALUES ('4', '1', null, '3', 'DK03', 'Pepsi', '4', '6.55000000', '5.00000000', '0.00000000', '1', '0', '3.27270000', '2', '10%', '0', '0.00000000', null, '36.00000000', '120.00000000', '2017-08-25', 'pending', '7.20000000', '0.35692600', '10.00000000', null, '0', '1.00000000', '0', null);

-- ----------------------------
-- Table structure for erp_purchase_request_items
-- ----------------------------
DROP TABLE IF EXISTS `erp_purchase_request_items`;
CREATE TABLE `erp_purchase_request_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `delivery_id` int(11) NOT NULL,
  `purchase_id` int(11) DEFAULT NULL,
  `transfer_id` int(11) DEFAULT NULL,
  `product_id` int(11) NOT NULL,
  `product_code` varchar(50) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `option_id` int(11) DEFAULT NULL,
  `product_type` varchar(55) DEFAULT NULL,
  `net_unit_cost` decimal(25,8) NOT NULL,
  `quantity` decimal(15,8) NOT NULL,
  `warehouse_id` int(11) NOT NULL,
  `tax_method` int(11) DEFAULT '0',
  `item_tax` decimal(25,8) DEFAULT NULL,
  `tax_rate_id` int(11) DEFAULT NULL,
  `tax` varchar(20) DEFAULT NULL,
  `discount` varchar(20) DEFAULT NULL,
  `item_discount` decimal(25,8) DEFAULT NULL,
  `expiry` date DEFAULT NULL,
  `subtotal` decimal(25,8) NOT NULL,
  `quantity_balance` decimal(15,8) DEFAULT '0.00000000',
  `date` date NOT NULL,
  `status` varchar(50) NOT NULL,
  `unit_cost` decimal(25,8) DEFAULT NULL,
  `real_unit_cost` decimal(25,8) DEFAULT NULL,
  `quantity_received` decimal(15,8) DEFAULT NULL,
  `supplier_part_no` varchar(50) DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `job_name` varchar(255) DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `convert_id` int(11) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `reference` varchar(255) DEFAULT NULL,
  `opening_stock` int(11) DEFAULT NULL,
  `create_status` varchar(2) DEFAULT '0',
  `create_qty` int(11) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `purchase_id` (`purchase_id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_purchase_request_items
-- ----------------------------
INSERT INTO `erp_purchase_request_items` VALUES ('1', '0', '1', null, '3', 'DK03', 'Pepsi', '4', null, '6.54545455', '5.00000000', '1', '0', '3.27000000', '2', '10%', '0', '0.00000000', null, '36.00000000', '120.00000000', '2017-08-25', 'requested', '7.20000000', '0.01250000', null, null, null, null, null, null, null, null, null, '0', null, '1');
INSERT INTO `erp_purchase_request_items` VALUES ('2', '0', '1', null, '2', 'DK02', 'Coco cola', '2', null, '7.00000000', '5.00000000', '1', '1', '3.50000000', '2', '10%', '1', '1.00000000', null, '38.50000000', '120.00000000', '2017-08-25', 'requested', '7.20000000', '0.00416667', null, null, null, null, null, null, null, null, null, '0', null, '1');
INSERT INTO `erp_purchase_request_items` VALUES ('3', '0', '1', null, '6', 'RW02', 'Salt', '0', null, '0.90000000', '100.00000000', '1', '0', '0.00000000', '1', '0', '10%', '10.00000000', null, '90.00000000', '100.00000000', '2017-08-25', 'requested', '1.00000000', '0.90000000', null, null, null, null, null, null, null, null, null, '0', null, '0');
INSERT INTO `erp_purchase_request_items` VALUES ('4', '0', '1', null, '5', 'RW01', 'Suger', '0', null, '0.99000000', '100.00000000', '1', '0', '0.00000000', '1', '0', '1', '1.00000000', null, '99.00000000', '100.00000000', '2017-08-25', 'requested', '1.00000000', '0.99000000', null, null, null, null, null, null, null, null, null, '0', null, '0');

-- ----------------------------
-- Table structure for erp_purchase_tax
-- ----------------------------
DROP TABLE IF EXISTS `erp_purchase_tax`;
CREATE TABLE `erp_purchase_tax` (
  `vat_id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` varchar(100) DEFAULT NULL,
  `reference_no` varchar(100) DEFAULT NULL,
  `purchase_id` varchar(10) DEFAULT NULL,
  `purchase_ref` varchar(100) DEFAULT NULL,
  `supplier_id` varchar(100) DEFAULT NULL,
  `issuedate` datetime DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `qty` double(25,4) DEFAULT NULL,
  `vatin` varchar(100) DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `amount_tax` double DEFAULT NULL,
  `amount_declear` double DEFAULT NULL,
  `non_tax_pur` double(25,4) DEFAULT NULL,
  `tax_value` double(25,4) DEFAULT NULL,
  `vat` double(25,4) DEFAULT NULL,
  `tax_id` int(11) DEFAULT NULL,
  `journal_location` varchar(255) DEFAULT NULL,
  `journal_date` date DEFAULT NULL,
  `amount_tax_declare` decimal(25,4) DEFAULT NULL,
  `value_import` decimal(25,4) DEFAULT NULL,
  `purchase_type` tinyint(1) DEFAULT NULL,
  `status_tax` varchar(25) DEFAULT NULL,
  `tax_type` tinyint(1) DEFAULT NULL,
  `pns` tinyint(1) DEFAULT NULL,
  `type` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`vat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of erp_purchase_tax
-- ----------------------------

-- ----------------------------
-- Table structure for erp_purchasing_taxes
-- ----------------------------
DROP TABLE IF EXISTS `erp_purchasing_taxes`;
CREATE TABLE `erp_purchasing_taxes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `insert_id` int(11) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `reference_no` varchar(255) DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `supplier` varchar(150) DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `status` varchar(150) DEFAULT NULL,
  `amount` decimal(24,4) DEFAULT NULL,
  `vat_id` int(11) DEFAULT NULL,
  `vat` decimal(24,4) DEFAULT NULL,
  `balance` decimal(24,4) DEFAULT NULL,
  `remark` varchar(100) DEFAULT NULL,
  `status_tax` varchar(100) DEFAULT NULL,
  `remark_id` int(1) DEFAULT NULL,
  `invoice_no` varchar(50) DEFAULT NULL,
  `tax_type` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of erp_purchasing_taxes
-- ----------------------------

-- ----------------------------
-- Table structure for erp_quotes
-- ----------------------------
DROP TABLE IF EXISTS `erp_quotes`;
CREATE TABLE `erp_quotes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `reference_no` varchar(55) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `customer` varchar(55) NOT NULL,
  `warehouse_id` int(11) DEFAULT NULL,
  `biller_id` int(11) NOT NULL,
  `biller` varchar(55) NOT NULL,
  `note` varchar(1000) DEFAULT NULL,
  `internal_note` varchar(1000) DEFAULT NULL,
  `total` decimal(25,4) NOT NULL,
  `product_discount` decimal(25,4) DEFAULT '0.0000',
  `order_discount` decimal(25,4) DEFAULT NULL,
  `order_discount_id` varchar(20) DEFAULT NULL,
  `total_discount` decimal(25,4) DEFAULT '0.0000',
  `product_tax` decimal(25,4) DEFAULT '0.0000',
  `order_tax_id` int(11) DEFAULT NULL,
  `order_tax` decimal(25,4) DEFAULT NULL,
  `total_tax` decimal(25,4) DEFAULT NULL,
  `shipping` decimal(25,4) DEFAULT '0.0000',
  `grand_total` decimal(25,4) NOT NULL,
  `status` varchar(20) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `attachment` varchar(55) DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `supplier` varchar(55) DEFAULT NULL,
  `saleman` int(11) DEFAULT NULL,
  `issue_invoice` varchar(55) DEFAULT NULL,
  `project_manager` int(11) DEFAULT NULL,
  `quote_status` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_quotes
-- ----------------------------

-- ----------------------------
-- Table structure for erp_quote_items
-- ----------------------------
DROP TABLE IF EXISTS `erp_quote_items`;
CREATE TABLE `erp_quote_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `quote_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_code` varchar(55) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_type` varchar(20) DEFAULT NULL,
  `option_id` int(11) DEFAULT NULL,
  `net_unit_price` decimal(25,4) NOT NULL,
  `unit_price` decimal(25,4) DEFAULT NULL,
  `quantity` decimal(15,4) NOT NULL,
  `quantity_received` decimal(25,4) unsigned zerofill NOT NULL,
  `warehouse_id` int(11) DEFAULT NULL,
  `item_tax` decimal(25,4) DEFAULT NULL,
  `tax_rate_id` int(11) DEFAULT NULL,
  `tax` varchar(55) DEFAULT NULL,
  `discount` varchar(55) DEFAULT NULL,
  `item_discount` decimal(25,4) DEFAULT NULL,
  `subtotal` decimal(25,4) NOT NULL,
  `serial_no` varchar(255) DEFAULT NULL,
  `real_unit_price` decimal(25,4) unsigned zerofill DEFAULT NULL,
  `group_price_id` int(11) DEFAULT NULL,
  `piece` double DEFAULT NULL,
  `wpiece` double DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `quote_id` (`quote_id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_quote_items
-- ----------------------------

-- ----------------------------
-- Table structure for erp_reasons
-- ----------------------------
DROP TABLE IF EXISTS `erp_reasons`;
CREATE TABLE `erp_reasons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `position_id` int(11) DEFAULT NULL,
  `code` varchar(50) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_reasons
-- ----------------------------

-- ----------------------------
-- Table structure for erp_recieved_transfers
-- ----------------------------
DROP TABLE IF EXISTS `erp_recieved_transfers`;
CREATE TABLE `erp_recieved_transfers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transfer_no` varchar(55) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `from_warehouse_id` int(11) NOT NULL,
  `from_warehouse_code` varchar(55) NOT NULL,
  `from_warehouse_name` varchar(55) NOT NULL,
  `to_warehouse_id` int(11) NOT NULL,
  `to_warehouse_code` varchar(55) NOT NULL,
  `to_warehouse_name` varchar(55) NOT NULL,
  `note` varchar(1000) DEFAULT NULL,
  `total` decimal(25,4) DEFAULT NULL,
  `total_tax` decimal(25,4) DEFAULT NULL,
  `grand_total` decimal(25,4) DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `status` varchar(55) NOT NULL DEFAULT 'pending',
  `shipping` decimal(25,4) NOT NULL DEFAULT '0.0000',
  `attachment` varchar(55) DEFAULT NULL,
  `attachment1` varchar(55) DEFAULT NULL,
  `attachment2` varchar(55) DEFAULT NULL,
  `biller_id` varchar(55) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_recieved_transfers
-- ----------------------------

-- ----------------------------
-- Table structure for erp_recieved_transfer_items
-- ----------------------------
DROP TABLE IF EXISTS `erp_recieved_transfer_items`;
CREATE TABLE `erp_recieved_transfer_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `received_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_code` varchar(55) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `option_id` int(11) DEFAULT NULL,
  `expiry` date DEFAULT NULL,
  `quantity` decimal(15,4) NOT NULL,
  `tax_rate_id` int(11) DEFAULT NULL,
  `tax` varchar(55) DEFAULT NULL,
  `item_tax` decimal(25,4) DEFAULT NULL,
  `net_unit_cost` decimal(25,4) DEFAULT NULL,
  `subtotal` decimal(25,4) DEFAULT NULL,
  `quantity_balance` decimal(15,4) NOT NULL,
  `unit_cost` decimal(25,4) DEFAULT NULL,
  `real_unit_cost` decimal(25,4) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `warehouse_id` int(11) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transfer_id` (`received_id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_recieved_transfer_items
-- ----------------------------

-- ----------------------------
-- Table structure for erp_related_products
-- ----------------------------
DROP TABLE IF EXISTS `erp_related_products`;
CREATE TABLE `erp_related_products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_code` varchar(50) DEFAULT NULL,
  `related_product_code` varchar(50) DEFAULT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of erp_related_products
-- ----------------------------

-- ----------------------------
-- Table structure for erp_return_items
-- ----------------------------
DROP TABLE IF EXISTS `erp_return_items`;
CREATE TABLE `erp_return_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sale_id` int(11) unsigned NOT NULL,
  `return_id` int(11) unsigned NOT NULL,
  `sale_item_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `product_id` int(11) unsigned NOT NULL,
  `product_code` varchar(55) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_type` varchar(20) DEFAULT NULL,
  `option_id` int(11) DEFAULT NULL,
  `net_unit_price` decimal(25,4) NOT NULL,
  `quantity` decimal(15,4) DEFAULT '0.0000',
  `warehouse_id` int(11) DEFAULT NULL,
  `item_tax` decimal(25,4) DEFAULT NULL,
  `tax_rate_id` int(11) DEFAULT NULL,
  `tax` varchar(55) DEFAULT NULL,
  `discount` varchar(55) DEFAULT NULL,
  `item_discount` decimal(25,4) DEFAULT NULL,
  `subtotal` decimal(25,4) NOT NULL,
  `serial_no` varchar(255) DEFAULT NULL,
  `real_unit_price` decimal(25,4) DEFAULT NULL,
  `unit_price` decimal(55,4) DEFAULT NULL,
  `unit_cost` decimal(25,8) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sale_id` (`sale_id`),
  KEY `product_id` (`product_id`),
  KEY `product_id_2` (`product_id`,`sale_id`),
  KEY `sale_id_2` (`sale_id`,`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_return_items
-- ----------------------------

-- ----------------------------
-- Table structure for erp_return_purchases
-- ----------------------------
DROP TABLE IF EXISTS `erp_return_purchases`;
CREATE TABLE `erp_return_purchases` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `purchase_id` int(11) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `reference_no` varchar(55) NOT NULL,
  `biller_id` int(11) DEFAULT NULL,
  `supplier_id` int(11) NOT NULL,
  `supplier` varchar(55) NOT NULL,
  `warehouse_id` int(11) DEFAULT NULL,
  `note` varchar(1000) DEFAULT NULL,
  `total` decimal(25,4) NOT NULL,
  `product_discount` decimal(25,4) DEFAULT '0.0000',
  `order_discount_id` varchar(20) DEFAULT NULL,
  `total_discount` decimal(25,4) DEFAULT '0.0000',
  `order_discount` decimal(25,4) DEFAULT '0.0000',
  `product_tax` decimal(25,4) DEFAULT '0.0000',
  `order_tax_id` int(11) DEFAULT NULL,
  `order_tax` decimal(25,4) DEFAULT '0.0000',
  `total_tax` decimal(25,4) DEFAULT '0.0000',
  `surcharge` decimal(25,4) DEFAULT '0.0000',
  `old_grand_total` decimal(25,4) DEFAULT '0.0000',
  `grand_total` decimal(25,4) NOT NULL,
  `paid` decimal(25,4) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `attachment` varchar(55) DEFAULT NULL,
  `shipping` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_return_purchases
-- ----------------------------

-- ----------------------------
-- Table structure for erp_return_purchase_items
-- ----------------------------
DROP TABLE IF EXISTS `erp_return_purchase_items`;
CREATE TABLE `erp_return_purchase_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `purchase_id` int(11) unsigned NOT NULL,
  `return_id` int(11) unsigned NOT NULL,
  `purchase_item_id` int(11) DEFAULT NULL,
  `product_id` int(11) unsigned NOT NULL,
  `product_code` varchar(55) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_type` varchar(20) DEFAULT NULL,
  `option_id` int(11) DEFAULT NULL,
  `net_unit_cost` decimal(25,4) NOT NULL,
  `quantity` decimal(15,4) DEFAULT '0.0000',
  `warehouse_id` int(11) DEFAULT NULL,
  `item_tax` decimal(25,4) DEFAULT NULL,
  `tax_rate_id` int(11) DEFAULT NULL,
  `tax` varchar(55) DEFAULT NULL,
  `discount` varchar(55) DEFAULT NULL,
  `item_discount` decimal(25,4) DEFAULT NULL,
  `old_subtotal` decimal(25,4) DEFAULT NULL,
  `subtotal` decimal(25,4) NOT NULL,
  `real_unit_cost` decimal(25,4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `purchase_id` (`purchase_id`),
  KEY `product_id` (`product_id`),
  KEY `product_id_2` (`product_id`,`purchase_id`),
  KEY `purchase_id_2` (`purchase_id`,`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_return_purchase_items
-- ----------------------------

-- ----------------------------
-- Table structure for erp_return_sales
-- ----------------------------
DROP TABLE IF EXISTS `erp_return_sales`;
CREATE TABLE `erp_return_sales` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sale_id` int(11) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `reference_no` varchar(55) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `customer` varchar(55) NOT NULL,
  `biller_id` int(11) NOT NULL,
  `biller` varchar(55) NOT NULL,
  `warehouse_id` int(11) DEFAULT NULL,
  `note` varchar(1000) DEFAULT NULL,
  `total_cost` decimal(25,4) DEFAULT NULL,
  `total` decimal(25,4) NOT NULL,
  `product_discount` decimal(25,4) DEFAULT '0.0000',
  `order_discount_id` varchar(20) DEFAULT NULL,
  `total_discount` decimal(25,4) DEFAULT '0.0000',
  `order_discount` decimal(25,4) DEFAULT '0.0000',
  `product_tax` decimal(25,4) DEFAULT '0.0000',
  `order_tax_id` int(11) DEFAULT NULL,
  `order_tax` decimal(25,4) DEFAULT '0.0000',
  `total_tax` decimal(25,4) DEFAULT '0.0000',
  `shipping` decimal(25,4) DEFAULT NULL,
  `surcharge` decimal(25,4) DEFAULT '0.0000',
  `grand_total` decimal(25,4) NOT NULL,
  `paid` double(25,4) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `attachment` varchar(55) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_return_sales
-- ----------------------------

-- ----------------------------
-- Table structure for erp_return_tax_back
-- ----------------------------
DROP TABLE IF EXISTS `erp_return_tax_back`;
CREATE TABLE `erp_return_tax_back` (
  `orderlineno` int(11) DEFAULT NULL,
  `tax_return_id` int(11) DEFAULT NULL,
  `itemcode` varchar(50) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `specific_tax` double DEFAULT NULL,
  `amount_tax` double DEFAULT NULL,
  `inv_num` varchar(50) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_return_tax_back
-- ----------------------------

-- ----------------------------
-- Table structure for erp_return_tax_front
-- ----------------------------
DROP TABLE IF EXISTS `erp_return_tax_front`;
CREATE TABLE `erp_return_tax_front` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) DEFAULT '0',
  `credit_lmonth04` double DEFAULT '0',
  `precaba_month05` double DEFAULT '0',
  `premonth_rate06` double DEFAULT '0',
  `crecarry_forward07` double DEFAULT '0',
  `preprofit_taxdue08` double DEFAULT '0',
  `sptax_calbase09` double DEFAULT '0',
  `sptax_duerate10` double DEFAULT '0',
  `sptax_calbase11` double DEFAULT '0',
  `sptax_duerate12` double DEFAULT '0',
  `taxacc_calbase13` double DEFAULT '0',
  `taxacc_duerate14` double DEFAULT '0',
  `taxpuli_calbase15` double DEFAULT '0',
  `specify` varchar(100) DEFAULT '',
  `taxpuli_duerate16` double DEFAULT '0',
  `tax_calbase17` double DEFAULT '0',
  `tax_duerate18` double DEFAULT '0',
  `total_taxdue19` double DEFAULT '0',
  `covreturn_start` date DEFAULT NULL,
  `covreturn_end` date DEFAULT NULL,
  `created_date` date DEFAULT NULL,
  `year` int(11) DEFAULT '0',
  `month` int(11) DEFAULT '0',
  `filed_in_kh` varchar(100) DEFAULT NULL,
  `filed_in_en` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_return_tax_front
-- ----------------------------

-- ----------------------------
-- Table structure for erp_return_value_added_tax
-- ----------------------------
DROP TABLE IF EXISTS `erp_return_value_added_tax`;
CREATE TABLE `erp_return_value_added_tax` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) DEFAULT '0',
  `pusa_act04` varchar(100) DEFAULT '',
  `tax_credit_premonth05` varchar(100) DEFAULT '',
  `ncredit_purch06` varchar(100) DEFAULT '',
  `strate_purch07` varchar(100) DEFAULT '',
  `strate_purch08` varchar(100) DEFAULT '',
  `strate_imports09` varchar(100) DEFAULT '',
  `strate_imports10` varchar(100) DEFAULT '',
  `total_intax11` varchar(100) DEFAULT '',
  `ntaxa_sales12` varchar(100) DEFAULT '',
  `exports13` varchar(100) DEFAULT '',
  `strate_sales14` varchar(100) DEFAULT '',
  `strate_sales15` varchar(100) DEFAULT '',
  `pay_difference16` varchar(100) DEFAULT '',
  `refund17` varchar(100) DEFAULT '',
  `credit_forward18` varchar(100) DEFAULT '',
  `covreturn_start` date DEFAULT NULL,
  `covreturn_end` date DEFAULT NULL,
  `created_date` date DEFAULT NULL,
  `year` int(11) DEFAULT '0',
  `month` int(11) DEFAULT '0',
  `field_in_kh` varchar(100) DEFAULT NULL,
  `field_in_en` varchar(100) DEFAULT NULL,
  `state_change` tinyint(1) DEFAULT NULL,
  `reference_no` varchar(100) DEFAULT NULL,
  `created_by` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_return_value_added_tax
-- ----------------------------

-- ----------------------------
-- Table structure for erp_return_value_added_tax_back
-- ----------------------------
DROP TABLE IF EXISTS `erp_return_value_added_tax_back`;
CREATE TABLE `erp_return_value_added_tax_back` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `value_id` int(11) DEFAULT '0',
  `productid` varchar(255) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `inv_cust_desc` varchar(255) DEFAULT NULL,
  `supp_exp_inn` varchar(255) DEFAULT NULL,
  `val_vat` varchar(255) DEFAULT NULL,
  `type` varchar(10) DEFAULT NULL,
  `qty` double DEFAULT NULL,
  `val_vat_g` double DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_return_value_added_tax_back
-- ----------------------------

-- ----------------------------
-- Table structure for erp_return_withholding_tax
-- ----------------------------
DROP TABLE IF EXISTS `erp_return_withholding_tax`;
CREATE TABLE `erp_return_withholding_tax` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) DEFAULT '0',
  `covreturn_start` date DEFAULT NULL,
  `covreturn_end` date DEFAULT NULL,
  `created_date` date DEFAULT NULL,
  `year` year(4) DEFAULT NULL,
  `month` int(11) DEFAULT '0',
  `taxref` varchar(255) DEFAULT NULL,
  `field_in_kh` varchar(255) DEFAULT NULL,
  `field_in_en` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_return_withholding_tax
-- ----------------------------

-- ----------------------------
-- Table structure for erp_return_withholding_tax_back
-- ----------------------------
DROP TABLE IF EXISTS `erp_return_withholding_tax_back`;
CREATE TABLE `erp_return_withholding_tax_back` (
  `withholding_id` varchar(10) DEFAULT NULL,
  `emp_code` varchar(100) DEFAULT NULL,
  `object_of_payment` varchar(255) DEFAULT NULL,
  `invoice_paynote` varchar(255) DEFAULT NULL,
  `amount_paid` double DEFAULT NULL,
  `tax_rate` double DEFAULT NULL,
  `withholding_tax` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_return_withholding_tax_back
-- ----------------------------

-- ----------------------------
-- Table structure for erp_return_withholding_tax_front
-- ----------------------------
DROP TABLE IF EXISTS `erp_return_withholding_tax_front`;
CREATE TABLE `erp_return_withholding_tax_front` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `withholding_id` int(11) DEFAULT '0',
  `amount_paid` double DEFAULT '0',
  `tax_rate` double DEFAULT '1',
  `withholding_tax` double DEFAULT '0',
  `remarks` text,
  `type` varchar(10) DEFAULT NULL,
  `type_of_oop` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_return_withholding_tax_front
-- ----------------------------

-- ----------------------------
-- Table structure for erp_revenues
-- ----------------------------
DROP TABLE IF EXISTS `erp_revenues`;
CREATE TABLE `erp_revenues` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `tax_type` varchar(255) NOT NULL,
  `revenue` varchar(255) NOT NULL,
  `goods_and_service` varchar(255) DEFAULT NULL,
  `description` text,
  `created_by` int(10) NOT NULL,
  `date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_revenues
-- ----------------------------

-- ----------------------------
-- Table structure for erp_salary_tax
-- ----------------------------
DROP TABLE IF EXISTS `erp_salary_tax`;
CREATE TABLE `erp_salary_tax` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) DEFAULT '0',
  `covreturn_start` date DEFAULT NULL,
  `covreturn_end` date DEFAULT NULL,
  `created_date` date DEFAULT NULL,
  `year` int(11) DEFAULT '0',
  `month` int(11) DEFAULT '0',
  `filed_in_kh` varchar(255) DEFAULT '0',
  `filed_in_en` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_salary_tax
-- ----------------------------

-- ----------------------------
-- Table structure for erp_salary_tax_back
-- ----------------------------
DROP TABLE IF EXISTS `erp_salary_tax_back`;
CREATE TABLE `erp_salary_tax_back` (
  `orderno` int(11) NOT NULL AUTO_INCREMENT,
  `salary_tax_id` int(11) NOT NULL,
  `empcode` varchar(50) DEFAULT '0',
  `salary_paid` double(25,8) DEFAULT NULL,
  `spouse` int(10) DEFAULT NULL,
  `minor_children` int(5) DEFAULT NULL,
  `nationality` varchar(50) DEFAULT NULL,
  `position` varchar(50) DEFAULT NULL,
  `date_insert` date DEFAULT NULL,
  `tax_type` varchar(50) NOT NULL,
  `tax_rate` double(25,8) DEFAULT NULL,
  `tax_salary` double(25,8) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`orderno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_salary_tax_back
-- ----------------------------

-- ----------------------------
-- Table structure for erp_salary_tax_front
-- ----------------------------
DROP TABLE IF EXISTS `erp_salary_tax_front`;
CREATE TABLE `erp_salary_tax_front` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `salary_tax_id` int(11) DEFAULT '0',
  `emp_num` int(11) DEFAULT '0',
  `salary_paid` double DEFAULT '0',
  `spouse_num` int(11) DEFAULT '0',
  `children_num` int(11) DEFAULT '0',
  `tax_salcalbase` double DEFAULT '0',
  `tax_rate` double DEFAULT '1',
  `tax_salary` double DEFAULT '0',
  `tax_type` char(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_salary_tax_front
-- ----------------------------

-- ----------------------------
-- Table structure for erp_sales
-- ----------------------------
DROP TABLE IF EXISTS `erp_sales`;
CREATE TABLE `erp_sales` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `reference_no` varchar(55) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `customer` varchar(55) DEFAULT NULL,
  `group_areas_id` int(11) DEFAULT NULL,
  `biller_id` int(11) NOT NULL,
  `biller` varchar(55) DEFAULT NULL,
  `warehouse_id` int(11) DEFAULT NULL,
  `note` varchar(1000) DEFAULT NULL,
  `staff_note` varchar(1000) DEFAULT NULL,
  `total` decimal(25,4) NOT NULL,
  `product_discount` decimal(25,4) DEFAULT '0.0000',
  `order_discount_id` varchar(20) DEFAULT NULL,
  `total_discount` decimal(25,4) DEFAULT '0.0000',
  `order_discount` decimal(25,4) DEFAULT '0.0000',
  `product_tax` decimal(25,4) DEFAULT '0.0000',
  `order_tax_id` int(11) DEFAULT NULL,
  `order_tax` decimal(25,4) DEFAULT '0.0000',
  `total_tax` decimal(25,4) DEFAULT '0.0000',
  `shipping` decimal(25,4) DEFAULT '0.0000',
  `grand_total` decimal(25,4) NOT NULL,
  `sale_status` varchar(20) DEFAULT NULL,
  `payment_status` varchar(20) DEFAULT NULL,
  `payment_term` tinyint(4) DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `delivery_status` varchar(50) DEFAULT NULL,
  `delivery_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_count` int(4) unsigned zerofill NOT NULL,
  `total_items` tinyint(4) DEFAULT NULL,
  `total_cost` decimal(25,4) NOT NULL,
  `pos` tinyint(1) NOT NULL DEFAULT '0',
  `paid` decimal(25,4) DEFAULT '0.0000',
  `return_id` int(11) DEFAULT NULL,
  `surcharge` decimal(25,4) NOT NULL DEFAULT '0.0000',
  `attachment` varchar(55) DEFAULT NULL,
  `attachment1` varchar(55) DEFAULT NULL,
  `attachment2` varchar(55) DEFAULT NULL,
  `type` varchar(55) DEFAULT NULL,
  `type_id` int(11) DEFAULT NULL,
  `other_cur_paid` decimal(25,0) DEFAULT NULL,
  `other_cur_paid_rate` decimal(25,0) DEFAULT '1',
  `other_cur_paid1` decimal(25,4) DEFAULT NULL,
  `other_cur_paid_rate1` decimal(25,4) DEFAULT NULL,
  `saleman_by` int(11) DEFAULT NULL,
  `reference_no_tax` varchar(55) NOT NULL,
  `tax_status` varchar(255) DEFAULT NULL,
  `opening_ar` tinyint(1) DEFAULT '0',
  `sale_type` tinyint(1) DEFAULT NULL,
  `bill_to` varchar(255) DEFAULT NULL,
  `po` varchar(50) DEFAULT NULL,
  `suspend_note` varchar(255) DEFAULT NULL,
  `tax_type` varchar(40) DEFAULT NULL,
  `so_id` int(11) DEFAULT NULL,
  `revenues_type` int(10) DEFAULT NULL,
  `acc_cate_separate` tinyint(1) DEFAULT NULL,
  `hide_tax` tinyint(1) DEFAULT '0',
  `quote_id` int(11) DEFAULT NULL,
  `project_manager` int(11) DEFAULT NULL,
  `assign_to_id` int(11) DEFAULT NULL,
  `tax_reference_no` varchar(55) DEFAULT NULL,
  `service_date` varchar(20) DEFAULT NULL,
  `cheque_name` varchar(64) DEFAULT NULL,
  `term` int(4) DEFAULT NULL,
  `total_interest` double DEFAULT NULL,
  `frequency` int(11) DEFAULT NULL,
  `depreciation_type` int(11) DEFAULT NULL,
  `principle_type` int(11) DEFAULT NULL,
  `queue` int(11) DEFAULT NULL,
  `deposit_so_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_sales
-- ----------------------------

-- ----------------------------
-- Table structure for erp_sales_audit
-- ----------------------------
DROP TABLE IF EXISTS `erp_sales_audit`;
CREATE TABLE `erp_sales_audit` (
  `id` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `reference_no` varchar(55) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `customer` varchar(55) DEFAULT NULL,
  `group_areas_id` int(11) DEFAULT NULL,
  `biller_id` int(11) NOT NULL,
  `biller` varchar(55) DEFAULT NULL,
  `warehouse_id` int(11) DEFAULT NULL,
  `note` varchar(1000) DEFAULT NULL,
  `staff_note` varchar(1000) DEFAULT NULL,
  `total` decimal(25,4) NOT NULL,
  `product_discount` decimal(25,4) DEFAULT '0.0000',
  `order_discount_id` varchar(20) DEFAULT NULL,
  `total_discount` decimal(25,4) DEFAULT '0.0000',
  `order_discount` decimal(25,4) DEFAULT '0.0000',
  `product_tax` decimal(25,4) DEFAULT '0.0000',
  `order_tax_id` int(11) DEFAULT NULL,
  `order_tax` decimal(25,4) DEFAULT '0.0000',
  `total_tax` decimal(25,4) DEFAULT '0.0000',
  `shipping` decimal(25,4) DEFAULT '0.0000',
  `grand_total` decimal(25,4) NOT NULL,
  `sale_status` varchar(20) DEFAULT NULL,
  `payment_status` varchar(20) DEFAULT NULL,
  `payment_term` tinyint(4) DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `delivery_status` varchar(50) DEFAULT NULL,
  `delivery_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_count` int(4) unsigned zerofill NOT NULL,
  `total_items` tinyint(4) DEFAULT NULL,
  `total_cost` decimal(25,4) NOT NULL,
  `pos` tinyint(1) NOT NULL DEFAULT '0',
  `paid` decimal(25,4) DEFAULT '0.0000',
  `return_id` int(11) DEFAULT NULL,
  `surcharge` decimal(25,4) NOT NULL DEFAULT '0.0000',
  `attachment` varchar(55) DEFAULT NULL,
  `attachment1` varchar(55) DEFAULT NULL,
  `attachment2` varchar(55) DEFAULT NULL,
  `type` varchar(55) DEFAULT NULL,
  `type_id` int(11) DEFAULT NULL,
  `other_cur_paid` decimal(25,0) DEFAULT NULL,
  `other_cur_paid_rate` decimal(25,0) DEFAULT '1',
  `other_cur_paid1` decimal(25,4) DEFAULT NULL,
  `other_cur_paid_rate1` decimal(25,4) DEFAULT NULL,
  `saleman_by` int(11) DEFAULT NULL,
  `reference_no_tax` varchar(55) NOT NULL,
  `tax_status` varchar(255) DEFAULT NULL,
  `opening_ar` tinyint(1) DEFAULT '0',
  `sale_type` tinyint(1) DEFAULT NULL,
  `bill_to` varchar(255) DEFAULT NULL,
  `po` varchar(50) DEFAULT NULL,
  `suspend_note` varchar(255) DEFAULT NULL,
  `tax_type` varchar(40) DEFAULT NULL,
  `so_id` int(11) DEFAULT NULL,
  `revenues_type` int(10) DEFAULT NULL,
  `acc_cate_separate` tinyint(1) DEFAULT NULL,
  `hide_tax` tinyint(1) DEFAULT '0',
  `audit_id` int(11) NOT NULL AUTO_INCREMENT,
  `audit_created_by` int(11) NOT NULL,
  `audit_record_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `audit_type` varchar(55) NOT NULL,
  PRIMARY KEY (`audit_id`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_sales_audit
-- ----------------------------

-- ----------------------------
-- Table structure for erp_sales_types
-- ----------------------------
DROP TABLE IF EXISTS `erp_sales_types`;
CREATE TABLE `erp_sales_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_code` varchar(20) DEFAULT '',
  `type_name` varchar(100) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `Sales_Type` (`type_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_sales_types
-- ----------------------------

-- ----------------------------
-- Table structure for erp_sale_areas
-- ----------------------------
DROP TABLE IF EXISTS `erp_sale_areas`;
CREATE TABLE `erp_sale_areas` (
  `areacode` int(3) NOT NULL AUTO_INCREMENT,
  `areadescription` varchar(100) DEFAULT '',
  `areas_g_code` varchar(100) DEFAULT '',
  PRIMARY KEY (`areacode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_sale_areas
-- ----------------------------

-- ----------------------------
-- Table structure for erp_sale_dev_items
-- ----------------------------
DROP TABLE IF EXISTS `erp_sale_dev_items`;
CREATE TABLE `erp_sale_dev_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sale_id` int(11) unsigned NOT NULL,
  `product_id` int(11) unsigned NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `option_id` int(11) DEFAULT NULL,
  `category_name` varchar(255) NOT NULL,
  `warehouse_id` int(11) DEFAULT NULL,
  `machine_name` varchar(50) DEFAULT NULL,
  `unit_price` decimal(25,4) DEFAULT NULL,
  `quantity` decimal(15,4) NOT NULL,
  `grand_total` decimal(25,4) DEFAULT NULL,
  `quantity_break` decimal(25,4) DEFAULT NULL,
  `quantity_index` decimal(25,4) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_1` int(11) DEFAULT NULL,
  `user_2` int(11) DEFAULT NULL,
  `user_3` int(11) DEFAULT NULL,
  `user_4` int(11) DEFAULT NULL,
  `user_5` int(11) DEFAULT NULL,
  `user_6` int(11) DEFAULT NULL,
  `user_7` int(11) DEFAULT NULL,
  `user_8` int(11) DEFAULT NULL,
  `user_9` int(11) DEFAULT NULL,
  `cf1` varchar(20) DEFAULT NULL,
  `cf2` varchar(20) DEFAULT NULL,
  `cf3` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sale_id` (`sale_id`),
  KEY `product_id` (`product_id`),
  KEY `product_id_2` (`product_id`,`sale_id`),
  KEY `sale_id_2` (`sale_id`,`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_sale_dev_items
-- ----------------------------

-- ----------------------------
-- Table structure for erp_sale_dev_items_audit
-- ----------------------------
DROP TABLE IF EXISTS `erp_sale_dev_items_audit`;
CREATE TABLE `erp_sale_dev_items_audit` (
  `id` int(11) NOT NULL,
  `sale_id` int(11) unsigned NOT NULL,
  `product_id` int(11) unsigned NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `option_id` int(11) DEFAULT NULL,
  `category_name` varchar(255) NOT NULL,
  `warehouse_id` int(11) DEFAULT NULL,
  `machine_name` varchar(50) DEFAULT NULL,
  `unit_price` decimal(25,4) DEFAULT NULL,
  `quantity` decimal(15,4) NOT NULL,
  `grand_total` decimal(25,4) DEFAULT NULL,
  `quantity_break` decimal(25,4) DEFAULT NULL,
  `quantity_index` decimal(25,4) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_1` int(11) DEFAULT NULL,
  `user_2` int(11) DEFAULT NULL,
  `user_3` int(11) DEFAULT NULL,
  `user_4` int(11) DEFAULT NULL,
  `user_5` int(11) DEFAULT NULL,
  `user_6` int(11) DEFAULT NULL,
  `user_7` int(11) DEFAULT NULL,
  `user_8` int(11) DEFAULT NULL,
  `user_9` int(11) DEFAULT NULL,
  `cf1` varchar(20) DEFAULT NULL,
  `cf2` varchar(20) DEFAULT NULL,
  `cf3` varchar(20) DEFAULT NULL,
  `audit_id` int(11) NOT NULL AUTO_INCREMENT,
  `audit_created_by` int(11) NOT NULL,
  `audit_record_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `audit_type` varchar(55) NOT NULL,
  PRIMARY KEY (`audit_id`),
  KEY `sale_id` (`sale_id`),
  KEY `product_id` (`product_id`),
  KEY `product_id_2` (`product_id`,`sale_id`),
  KEY `sale_id_2` (`sale_id`,`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_sale_dev_items_audit
-- ----------------------------

-- ----------------------------
-- Table structure for erp_sale_items
-- ----------------------------
DROP TABLE IF EXISTS `erp_sale_items`;
CREATE TABLE `erp_sale_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sale_id` int(11) unsigned NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `product_id` int(11) unsigned NOT NULL,
  `product_code` varchar(55) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_type` varchar(20) DEFAULT NULL,
  `option_id` int(11) DEFAULT NULL,
  `net_unit_price` decimal(25,4) NOT NULL,
  `unit_price` decimal(25,4) DEFAULT NULL,
  `quantity_received` decimal(15,4) NOT NULL,
  `quantity` decimal(15,4) NOT NULL,
  `warehouse_id` int(11) DEFAULT NULL,
  `item_tax` decimal(25,4) DEFAULT NULL,
  `tax_rate_id` int(11) DEFAULT NULL,
  `tax` varchar(55) DEFAULT NULL,
  `discount` varchar(55) DEFAULT NULL,
  `item_discount` decimal(25,4) DEFAULT NULL,
  `subtotal` decimal(25,4) NOT NULL,
  `serial_no` varchar(255) DEFAULT NULL,
  `real_unit_price` decimal(25,4) DEFAULT NULL,
  `product_noted` varchar(255) DEFAULT NULL,
  `returned` decimal(15,4) DEFAULT NULL,
  `group_price_id` int(11) DEFAULT NULL,
  `acc_cate_separate` tinyint(1) DEFAULT NULL,
  `specific_tax_on_certain_merchandise_and_services` int(10) DEFAULT NULL,
  `accommodation_tax` int(10) DEFAULT NULL,
  `public_lighting_tax` int(10) DEFAULT NULL,
  `other_tax` int(10) DEFAULT NULL,
  `payment_of_profit_tax` int(10) DEFAULT NULL,
  `performance_royalty_intangible` int(10) DEFAULT NULL,
  `payment_interest_non_bank` int(10) DEFAULT NULL,
  `payment_interest_taxpayer_fixed` int(10) DEFAULT NULL,
  `payment_interest_taxpayer_non_fixed` int(10) DEFAULT NULL,
  `payment_rental_immovable_property` int(10) DEFAULT NULL,
  `payment_of_interest` int(10) DEFAULT NULL,
  `payment_royalty_rental_income_related` int(10) DEFAULT NULL,
  `payment_management_technical` int(10) DEFAULT NULL,
  `payment_dividend` int(10) DEFAULT NULL,
  `withholding_tax_on_resident` int(10) DEFAULT NULL,
  `withholding_tax_on_non_resident` int(10) DEFAULT NULL,
  `order_status` int(25) DEFAULT NULL,
  `unit_cost` decimal(25,8) DEFAULT NULL,
  `quantity_balance` decimal(15,8) DEFAULT NULL,
  `from_date` date DEFAULT NULL,
  `to_date` date DEFAULT NULL,
  `description` varchar(150) DEFAULT NULL,
  `piece` double DEFAULT NULL,
  `wpiece` double DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sale_id` (`sale_id`),
  KEY `product_id` (`product_id`),
  KEY `product_id_2` (`product_id`,`sale_id`),
  KEY `sale_id_2` (`sale_id`,`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_sale_items
-- ----------------------------

-- ----------------------------
-- Table structure for erp_sale_items_audit
-- ----------------------------
DROP TABLE IF EXISTS `erp_sale_items_audit`;
CREATE TABLE `erp_sale_items_audit` (
  `id` int(11) NOT NULL,
  `sale_id` int(11) unsigned NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `product_id` int(11) unsigned NOT NULL,
  `product_code` varchar(55) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_type` varchar(20) DEFAULT NULL,
  `option_id` int(11) DEFAULT NULL,
  `net_unit_price` decimal(25,4) NOT NULL,
  `unit_price` decimal(25,4) DEFAULT NULL,
  `quantity_received` decimal(15,4) NOT NULL,
  `quantity` decimal(15,4) NOT NULL,
  `warehouse_id` int(11) DEFAULT NULL,
  `item_tax` decimal(25,4) DEFAULT NULL,
  `tax_rate_id` int(11) DEFAULT NULL,
  `tax` varchar(55) DEFAULT NULL,
  `discount` varchar(55) DEFAULT NULL,
  `item_discount` decimal(25,4) DEFAULT NULL,
  `subtotal` decimal(25,4) NOT NULL,
  `serial_no` varchar(255) DEFAULT NULL,
  `real_unit_price` decimal(25,4) DEFAULT NULL,
  `product_noted` varchar(30) DEFAULT NULL,
  `returned` decimal(15,4) DEFAULT NULL,
  `group_price_id` int(11) DEFAULT NULL,
  `acc_cate_separate` tinyint(1) DEFAULT NULL,
  `specific_tax_on_certain_merchandise_and_services` int(10) DEFAULT NULL,
  `accommodation_tax` int(10) DEFAULT NULL,
  `public_lighting_tax` int(10) DEFAULT NULL,
  `other_tax` int(10) DEFAULT NULL,
  `payment_of_profit_tax` int(10) DEFAULT NULL,
  `performance_royalty_intangible` int(10) DEFAULT NULL,
  `payment_interest_non_bank` int(10) DEFAULT NULL,
  `payment_interest_taxpayer_fixed` int(10) DEFAULT NULL,
  `payment_interest_taxpayer_non_fixed` int(10) DEFAULT NULL,
  `payment_rental_immovable_property` int(10) DEFAULT NULL,
  `payment_of_interest` int(10) DEFAULT NULL,
  `payment_royalty_rental_income_related` int(10) DEFAULT NULL,
  `payment_management_technical` int(10) DEFAULT NULL,
  `payment_dividend` int(10) DEFAULT NULL,
  `withholding_tax_on_resident` int(10) DEFAULT NULL,
  `withholding_tax_on_non_resident` int(10) DEFAULT NULL,
  `audit_id` int(11) NOT NULL AUTO_INCREMENT,
  `audit_created_by` int(11) NOT NULL,
  `audit_record_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `audit_type` varchar(55) NOT NULL,
  PRIMARY KEY (`audit_id`),
  KEY `sale_id` (`sale_id`),
  KEY `product_id` (`product_id`),
  KEY `product_id_2` (`product_id`,`sale_id`),
  KEY `sale_id_2` (`sale_id`,`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_sale_items_audit
-- ----------------------------

-- ----------------------------
-- Table structure for erp_sale_order
-- ----------------------------
DROP TABLE IF EXISTS `erp_sale_order`;
CREATE TABLE `erp_sale_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `quote_id` int(11) DEFAULT NULL,
  `reference_no` varchar(55) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `customer` varchar(55) NOT NULL,
  `group_areas_id` int(10) DEFAULT NULL,
  `biller_id` int(11) NOT NULL,
  `biller` varchar(55) NOT NULL,
  `warehouse_id` int(11) DEFAULT NULL,
  `note` varchar(1000) DEFAULT NULL,
  `staff_note` varchar(1000) DEFAULT NULL,
  `total` decimal(25,4) NOT NULL,
  `product_discount` decimal(25,4) DEFAULT '0.0000',
  `order_discount_id` varchar(20) DEFAULT NULL,
  `total_discount` decimal(25,4) DEFAULT '0.0000',
  `order_discount` decimal(25,4) DEFAULT '0.0000',
  `product_tax` decimal(25,4) DEFAULT '0.0000',
  `order_tax_id` int(11) DEFAULT NULL,
  `order_tax` decimal(25,4) DEFAULT '0.0000',
  `total_tax` decimal(25,4) DEFAULT '0.0000',
  `shipping` decimal(25,4) DEFAULT '0.0000',
  `grand_total` decimal(25,4) NOT NULL,
  `order_status` varchar(20) DEFAULT NULL,
  `sale_status` varchar(20) DEFAULT NULL,
  `payment_status` varchar(20) DEFAULT NULL,
  `payment_term` tinyint(4) DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `total_items` tinyint(4) DEFAULT NULL,
  `total_cost` decimal(25,4) NOT NULL,
  `pos` tinyint(1) NOT NULL DEFAULT '0',
  `paid` decimal(25,4) DEFAULT '0.0000',
  `return_id` int(11) DEFAULT NULL,
  `surcharge` decimal(25,4) NOT NULL DEFAULT '0.0000',
  `attachment` varchar(55) DEFAULT NULL,
  `attachment1` varchar(55) DEFAULT NULL,
  `attachment2` varchar(55) DEFAULT NULL,
  `suspend_note` varchar(20) DEFAULT NULL,
  `other_cur_paid` decimal(25,0) DEFAULT NULL,
  `other_cur_paid_rate` decimal(25,0) DEFAULT '1',
  `other_cur_paid1` decimal(25,4) DEFAULT NULL,
  `other_cur_paid_rate1` decimal(25,4) DEFAULT NULL,
  `saleman_by` int(11) DEFAULT NULL,
  `reference_no_tax` varchar(55) NOT NULL,
  `tax_status` varchar(255) DEFAULT NULL,
  `opening_ar` tinyint(1) DEFAULT '0',
  `delivery_by` int(11) DEFAULT NULL,
  `sale_type` tinyint(1) DEFAULT NULL,
  `delivery_status` varchar(20) DEFAULT NULL,
  `tax_type` tinyint(1) DEFAULT NULL,
  `bill_to` varchar(255) DEFAULT NULL,
  `po` varchar(50) DEFAULT NULL,
  `project_manager` int(11) DEFAULT NULL,
  `assign_to_id` int(11) DEFAULT NULL,
  `delivery_date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_sale_order
-- ----------------------------

-- ----------------------------
-- Table structure for erp_sale_order_audit
-- ----------------------------
DROP TABLE IF EXISTS `erp_sale_order_audit`;
CREATE TABLE `erp_sale_order_audit` (
  `id` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `quote_id` int(11) DEFAULT NULL,
  `reference_no` varchar(55) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `customer` varchar(55) NOT NULL,
  `group_areas_id` int(10) DEFAULT NULL,
  `biller_id` int(11) NOT NULL,
  `biller` varchar(55) NOT NULL,
  `warehouse_id` int(11) DEFAULT NULL,
  `note` varchar(1000) DEFAULT NULL,
  `staff_note` varchar(1000) DEFAULT NULL,
  `total` decimal(25,4) NOT NULL,
  `product_discount` decimal(25,4) DEFAULT '0.0000',
  `order_discount_id` varchar(20) DEFAULT NULL,
  `total_discount` decimal(25,4) DEFAULT '0.0000',
  `order_discount` decimal(25,4) DEFAULT '0.0000',
  `product_tax` decimal(25,4) DEFAULT '0.0000',
  `order_tax_id` int(11) DEFAULT NULL,
  `order_tax` decimal(25,4) DEFAULT '0.0000',
  `total_tax` decimal(25,4) DEFAULT '0.0000',
  `shipping` decimal(25,4) DEFAULT '0.0000',
  `grand_total` decimal(25,4) NOT NULL,
  `order_status` varchar(20) DEFAULT NULL,
  `sale_status` varchar(20) DEFAULT NULL,
  `payment_status` varchar(20) DEFAULT NULL,
  `payment_term` tinyint(4) DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `total_items` tinyint(4) DEFAULT NULL,
  `total_cost` decimal(25,4) NOT NULL,
  `pos` tinyint(1) NOT NULL DEFAULT '0',
  `paid` decimal(25,4) DEFAULT '0.0000',
  `return_id` int(11) DEFAULT NULL,
  `surcharge` decimal(25,4) NOT NULL DEFAULT '0.0000',
  `attachment` varchar(55) DEFAULT NULL,
  `attachment1` varchar(55) DEFAULT NULL,
  `attachment2` varchar(55) DEFAULT NULL,
  `suspend_note` varchar(20) DEFAULT NULL,
  `other_cur_paid` decimal(25,0) DEFAULT NULL,
  `other_cur_paid_rate` decimal(25,0) DEFAULT '1',
  `other_cur_paid1` decimal(25,4) DEFAULT NULL,
  `other_cur_paid_rate1` decimal(25,4) DEFAULT NULL,
  `saleman_by` int(11) DEFAULT NULL,
  `reference_no_tax` varchar(55) NOT NULL,
  `tax_status` varchar(255) DEFAULT NULL,
  `opening_ar` tinyint(1) DEFAULT '0',
  `delivery_by` int(11) DEFAULT NULL,
  `sale_type` tinyint(1) DEFAULT NULL,
  `delivery_status` varchar(20) DEFAULT NULL,
  `tax_type` tinyint(1) DEFAULT NULL,
  `bill_to` varchar(255) DEFAULT NULL,
  `po` varchar(50) DEFAULT NULL,
  `audit_id` int(11) NOT NULL AUTO_INCREMENT,
  `audit_created_by` int(11) NOT NULL,
  `audit_record_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `audit_type` varchar(55) NOT NULL,
  PRIMARY KEY (`audit_id`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_sale_order_audit
-- ----------------------------

-- ----------------------------
-- Table structure for erp_sale_order_items
-- ----------------------------
DROP TABLE IF EXISTS `erp_sale_order_items`;
CREATE TABLE `erp_sale_order_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sale_order_id` int(11) unsigned NOT NULL,
  `product_id` int(11) unsigned NOT NULL,
  `product_code` varchar(55) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_type` varchar(20) DEFAULT NULL,
  `option_id` int(11) DEFAULT NULL,
  `net_unit_price` decimal(25,4) NOT NULL,
  `unit_price` decimal(25,4) DEFAULT NULL,
  `quantity_received` decimal(15,4) NOT NULL,
  `quantity` decimal(15,4) NOT NULL,
  `warehouse_id` int(11) DEFAULT NULL,
  `item_tax` decimal(25,4) DEFAULT NULL,
  `tax_rate_id` int(11) DEFAULT NULL,
  `tax` varchar(55) DEFAULT NULL,
  `discount` varchar(55) DEFAULT NULL,
  `item_discount` decimal(25,4) DEFAULT NULL,
  `subtotal` decimal(25,4) NOT NULL,
  `serial_no` varchar(255) DEFAULT NULL,
  `real_unit_price` decimal(25,4) DEFAULT NULL,
  `product_noted` varchar(30) DEFAULT NULL,
  `group_price_id` int(11) DEFAULT NULL,
  `piece` double DEFAULT NULL,
  `wpiece` double DEFAULT NULL,
  `sale_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sale_id` (`sale_order_id`),
  KEY `product_id` (`product_id`),
  KEY `product_id_2` (`product_id`,`sale_order_id`),
  KEY `sale_id_2` (`sale_order_id`,`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_sale_order_items
-- ----------------------------

-- ----------------------------
-- Table structure for erp_sale_order_items_audit
-- ----------------------------
DROP TABLE IF EXISTS `erp_sale_order_items_audit`;
CREATE TABLE `erp_sale_order_items_audit` (
  `id` int(11) NOT NULL,
  `sale_order_id` int(11) unsigned NOT NULL,
  `product_id` int(11) unsigned NOT NULL,
  `product_code` varchar(55) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_type` varchar(20) DEFAULT NULL,
  `option_id` int(11) DEFAULT NULL,
  `net_unit_price` decimal(25,4) NOT NULL,
  `unit_price` decimal(25,4) DEFAULT NULL,
  `quantity_received` decimal(15,4) NOT NULL,
  `quantity` decimal(15,4) NOT NULL,
  `warehouse_id` int(11) DEFAULT NULL,
  `item_tax` decimal(25,4) DEFAULT NULL,
  `tax_rate_id` int(11) DEFAULT NULL,
  `tax` varchar(55) DEFAULT NULL,
  `discount` varchar(55) DEFAULT NULL,
  `item_discount` decimal(25,4) DEFAULT NULL,
  `subtotal` decimal(25,4) NOT NULL,
  `serial_no` varchar(255) DEFAULT NULL,
  `real_unit_price` decimal(25,4) DEFAULT NULL,
  `product_noted` varchar(30) DEFAULT NULL,
  `group_price_id` int(11) DEFAULT NULL,
  `audit_id` int(11) NOT NULL AUTO_INCREMENT,
  `audit_created_by` int(11) NOT NULL,
  `audit_record_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `audit_type` varchar(55) NOT NULL,
  PRIMARY KEY (`audit_id`),
  KEY `sale_id` (`sale_order_id`),
  KEY `product_id` (`product_id`),
  KEY `product_id_2` (`product_id`,`sale_order_id`),
  KEY `sale_id_2` (`sale_order_id`,`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_sale_order_items_audit
-- ----------------------------

-- ----------------------------
-- Table structure for erp_sale_tax
-- ----------------------------
DROP TABLE IF EXISTS `erp_sale_tax`;
CREATE TABLE `erp_sale_tax` (
  `vat_id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` varchar(100) DEFAULT '',
  `sale_id` varchar(100) DEFAULT '',
  `customer_id` varchar(100) DEFAULT '',
  `issuedate` datetime DEFAULT NULL,
  `vatin` varchar(100) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `qty` double(8,4) DEFAULT NULL,
  `non_tax_sale` double(8,4) DEFAULT NULL,
  `value_export` double(8,4) DEFAULT NULL,
  `amound` double DEFAULT NULL,
  `amound_tax` double DEFAULT '0',
  `amound_declare` double(8,4) DEFAULT NULL,
  `tax_value` double(8,4) DEFAULT NULL,
  `vat` double(8,4) DEFAULT NULL,
  `tax_id` int(11) DEFAULT NULL,
  `journal_date` date DEFAULT NULL,
  `journal_location` varchar(100) DEFAULT NULL,
  `referent_no` varchar(255) DEFAULT NULL,
  `amount_tax_declare` int(100) DEFAULT NULL,
  `tax_type` tinyint(1) DEFAULT NULL,
  `pns` int(2) DEFAULT NULL,
  `sale_type` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`vat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_sale_tax
-- ----------------------------

-- ----------------------------
-- Table structure for erp_serial
-- ----------------------------
DROP TABLE IF EXISTS `erp_serial`;
CREATE TABLE `erp_serial` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) DEFAULT NULL,
  `serial_number` varchar(150) DEFAULT NULL,
  `biller_id` int(11) DEFAULT NULL,
  `warehouse` int(11) DEFAULT NULL,
  `serial_status` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of erp_serial
-- ----------------------------

-- ----------------------------
-- Table structure for erp_sessions
-- ----------------------------
DROP TABLE IF EXISTS `erp_sessions`;
CREATE TABLE `erp_sessions` (
  `id` varchar(40) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `data` blob NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_sessions
-- ----------------------------
INSERT INTO `erp_sessions` VALUES ('579aab96d9b2b45a7b8297df99451aeb1dce2790', '192.168.1.60', '1503656894', 0x5F5F63695F6C6173745F726567656E65726174657C693A313530333635333736303B6964656E746974797C733A32313A226F776E657240636C6F75646E65742E636F6D2E6B68223B757365726E616D657C733A353A226F776E6572223B656D61696C7C733A32313A226F776E657240636C6F75646E65742E636F6D2E6B68223B757365725F69647C733A313A2231223B6F6C645F6C6173745F6C6F67696E7C733A31303A2231353033363533353731223B6C6173745F69707C733A31323A223139322E3136382E312E3630223B6176617461727C733A303A22223B67656E6465727C733A343A226D616C65223B67726F75705F69647C733A313A2231223B77617265686F7573655F69647C733A303A22223B766965775F72696768747C733A313A2230223B656469745F72696768747C733A313A2230223B616C6C6F775F646973636F756E747C733A313A2230223B62696C6C65725F69647C4E3B636F6D70616E795F69647C4E3B73686F775F636F73747C733A313A2230223B73686F775F70726963657C733A313A2230223B6C6173745F61637469766974797C693A313530333635333935383B757365725F637372667C733A32303A2274774955626C7A5639474F4E5451783330355A4D223B72656D6F76655F706F6C73727C733A313A2230223B);
INSERT INTO `erp_sessions` VALUES ('5d9a27d3cbe9548c7cabb547551b029659b3cd30', '192.168.1.166', '1503653223', 0x5F5F63695F6C6173745F726567656E65726174657C693A313530333635333230363B7265717565737465645F706167657C733A393A22706F732F73616C6573223B6964656E746974797C733A32313A226F776E657240636C6F75646E65742E636F6D2E6B68223B757365726E616D657C733A353A226F776E6572223B656D61696C7C733A32313A226F776E657240636C6F75646E65742E636F6D2E6B68223B757365725F69647C733A313A2231223B6F6C645F6C6173745F6C6F67696E7C733A31303A2231353033363532363536223B6C6173745F69707C733A31323A223139322E3136382E312E3630223B6176617461727C733A303A22223B67656E6465727C733A343A226D616C65223B67726F75705F69647C733A313A2231223B77617265686F7573655F69647C733A303A22223B766965775F72696768747C733A313A2230223B656469745F72696768747C733A313A2230223B616C6C6F775F646973636F756E747C733A313A2230223B62696C6C65725F69647C4E3B636F6D70616E795F69647C4E3B73686F775F636F73747C733A313A2230223B73686F775F70726963657C733A313A2230223B6C6173745F61637469766974797C693A313530333635333231323B);
INSERT INTO `erp_sessions` VALUES ('a5cfbb383924c97ee44179c76517725ee7accfc9', '192.168.1.43', '1503653545', 0x5F5F63695F6C6173745F726567656E65726174657C693A313530333635333232383B7265717565737465645F706167657C733A373A2277656C636F6D65223B6964656E746974797C733A32313A226F776E657240636C6F75646E65742E636F6D2E6B68223B757365726E616D657C733A353A226F776E6572223B656D61696C7C733A32313A226F776E657240636C6F75646E65742E636F6D2E6B68223B757365725F69647C733A313A2231223B6F6C645F6C6173745F6C6F67696E7C733A31303A2231353033363533323039223B6C6173745F69707C733A31333A223139322E3136382E312E313636223B6176617461727C733A303A22223B67656E6465727C733A343A226D616C65223B67726F75705F69647C733A313A2231223B77617265686F7573655F69647C733A303A22223B766965775F72696768747C733A313A2230223B656469745F72696768747C733A313A2230223B616C6C6F775F646973636F756E747C733A313A2230223B62696C6C65725F69647C4E3B636F6D70616E795F69647C4E3B73686F775F636F73747C733A313A2230223B73686F775F70726963657C733A313A2230223B);
INSERT INTO `erp_sessions` VALUES ('a76a33a2b5ad920b9a8d877bd7094d0382e3e6d3', '192.168.1.22', '1503653419', 0x5F5F63695F6C6173745F726567656E65726174657C693A313530333635333338333B6964656E746974797C733A32313A226F776E657240636C6F75646E65742E636F6D2E6B68223B757365726E616D657C733A353A226F776E6572223B656D61696C7C733A32313A226F776E657240636C6F75646E65742E636F6D2E6B68223B757365725F69647C733A313A2231223B6F6C645F6C6173745F6C6F67696E7C733A31303A2231353033363533323332223B6C6173745F69707C733A31323A223139322E3136382E312E3433223B6176617461727C733A303A22223B67656E6465727C733A343A226D616C65223B67726F75705F69647C733A313A2231223B77617265686F7573655F69647C733A303A22223B766965775F72696768747C733A313A2230223B656469745F72696768747C733A313A2230223B616C6C6F775F646973636F756E747C733A313A2230223B62696C6C65725F69647C4E3B636F6D70616E795F69647C4E3B73686F775F636F73747C733A313A2230223B73686F775F70726963657C733A313A2230223B);
INSERT INTO `erp_sessions` VALUES ('e2cc62283acd836390ef65c47ead72aa73be702a', '192.168.1.60', '1503659664', 0x5F5F63695F6C6173745F726567656E65726174657C693A313530333635363931383B6964656E746974797C733A32313A226F776E657240636C6F75646E65742E636F6D2E6B68223B757365726E616D657C733A353A226F776E6572223B656D61696C7C733A32313A226F776E657240636C6F75646E65742E636F6D2E6B68223B757365725F69647C733A313A2231223B6F6C645F6C6173745F6C6F67696E7C733A31303A2231353033363533353731223B6C6173745F69707C733A31323A223139322E3136382E312E3630223B6176617461727C733A303A22223B67656E6465727C733A343A226D616C65223B67726F75705F69647C733A313A2231223B77617265686F7573655F69647C733A303A22223B766965775F72696768747C733A313A2230223B656469745F72696768747C733A313A2230223B616C6C6F775F646973636F756E747C733A313A2230223B62696C6C65725F69647C4E3B636F6D70616E795F69647C4E3B73686F775F636F73747C733A313A2230223B73686F775F70726963657C733A313A2230223B6C6173745F61637469766974797C693A313530333635333935383B757365725F637372667C733A32303A22466E557369303457326F50714172743175364B67223B72656D6F76655F706F6C73727C733A313A2231223B72656D6F76655F706F6C736F7C733A313A2231223B);
INSERT INTO `erp_sessions` VALUES ('f67aa74efbffce8f9b63fb2b0d01c0b83d4c0085', '192.168.1.60', '1503662927', 0x5F5F63695F6C6173745F726567656E65726174657C693A313530333636303133333B6964656E746974797C733A32313A226F776E657240636C6F75646E65742E636F6D2E6B68223B757365726E616D657C733A353A226F776E6572223B656D61696C7C733A32313A226F776E657240636C6F75646E65742E636F6D2E6B68223B757365725F69647C733A313A2231223B6F6C645F6C6173745F6C6F67696E7C733A31303A2231353033363533353731223B6C6173745F69707C733A31323A223139322E3136382E312E3630223B6176617461727C733A303A22223B67656E6465727C733A343A226D616C65223B67726F75705F69647C733A313A2231223B77617265686F7573655F69647C733A303A22223B766965775F72696768747C733A313A2230223B656469745F72696768747C733A313A2230223B616C6C6F775F646973636F756E747C733A313A2230223B62696C6C65725F69647C4E3B636F6D70616E795F69647C4E3B73686F775F636F73747C733A313A2230223B73686F775F70726963657C733A313A2230223B6C6173745F61637469766974797C693A313530333635333935383B757365725F637372667C733A32303A22466E557369303457326F50714172743175364B67223B72656D6F76655F706F6C73727C733A313A2231223B72656D6F76655F706F6C736F7C733A313A2231223B656E746572207573696E672073746F636B2061646465642E7C4E3B);

-- ----------------------------
-- Table structure for erp_settings
-- ----------------------------
DROP TABLE IF EXISTS `erp_settings`;
CREATE TABLE `erp_settings` (
  `setting_id` int(1) NOT NULL,
  `logo` varchar(255) NOT NULL,
  `logo2` varchar(255) NOT NULL,
  `site_name` varchar(55) NOT NULL,
  `language` varchar(20) NOT NULL,
  `default_warehouse` int(2) NOT NULL,
  `accounting_method` tinyint(4) NOT NULL DEFAULT '0',
  `default_currency` varchar(10) NOT NULL,
  `default_tax_rate` int(2) NOT NULL,
  `rows_per_page` int(2) NOT NULL,
  `version` varchar(10) NOT NULL DEFAULT '1.0',
  `default_tax_rate2` int(11) NOT NULL DEFAULT '0',
  `dateformat` int(11) NOT NULL,
  `sales_prefix` varchar(20) DEFAULT NULL,
  `quote_prefix` varchar(20) DEFAULT NULL,
  `purchase_prefix` varchar(20) DEFAULT NULL,
  `transfer_prefix` varchar(20) DEFAULT NULL,
  `delivery_prefix` varchar(20) DEFAULT NULL,
  `payment_prefix` varchar(20) DEFAULT NULL,
  `return_prefix` varchar(20) DEFAULT NULL,
  `expense_prefix` varchar(20) DEFAULT NULL,
  `transaction_prefix` varchar(10) DEFAULT NULL,
  `stock_count_prefix` varchar(25) DEFAULT NULL,
  `project_plan_prefix` varchar(25) DEFAULT NULL,
  `adjust_cost_prefix` varchar(25) DEFAULT NULL,
  `item_addition` tinyint(1) NOT NULL DEFAULT '0',
  `theme` varchar(20) NOT NULL,
  `product_serial` tinyint(4) NOT NULL,
  `default_discount` int(11) NOT NULL,
  `product_discount` tinyint(1) NOT NULL DEFAULT '0',
  `discount_method` tinyint(4) NOT NULL,
  `tax1` tinyint(4) NOT NULL,
  `tax2` tinyint(4) NOT NULL,
  `overselling` tinyint(1) NOT NULL DEFAULT '0',
  `restrict_user` tinyint(4) NOT NULL DEFAULT '0',
  `restrict_calendar` tinyint(4) NOT NULL DEFAULT '0',
  `timezone` varchar(100) DEFAULT NULL,
  `iwidth` int(11) NOT NULL DEFAULT '0',
  `iheight` int(11) NOT NULL,
  `twidth` int(11) NOT NULL,
  `theight` int(11) NOT NULL,
  `watermark` tinyint(1) DEFAULT NULL,
  `reg_ver` tinyint(1) DEFAULT NULL,
  `allow_reg` tinyint(1) DEFAULT NULL,
  `reg_notification` tinyint(1) DEFAULT NULL,
  `auto_reg` tinyint(1) DEFAULT NULL,
  `protocol` varchar(20) NOT NULL DEFAULT 'mail',
  `mailpath` varchar(55) DEFAULT '/usr/sbin/sendmail',
  `smtp_host` varchar(100) DEFAULT NULL,
  `smtp_user` varchar(100) DEFAULT NULL,
  `smtp_pass` varchar(255) DEFAULT NULL,
  `smtp_port` varchar(10) DEFAULT '25',
  `smtp_crypto` varchar(10) DEFAULT NULL,
  `corn` datetime DEFAULT NULL,
  `customer_group` int(11) NOT NULL,
  `default_email` varchar(100) NOT NULL,
  `mmode` tinyint(1) NOT NULL,
  `bc_fix` tinyint(4) NOT NULL DEFAULT '0',
  `auto_detect_barcode` tinyint(1) NOT NULL DEFAULT '0',
  `captcha` tinyint(1) NOT NULL DEFAULT '1',
  `reference_format` tinyint(1) NOT NULL DEFAULT '1',
  `racks` tinyint(1) DEFAULT '0',
  `attributes` tinyint(1) NOT NULL DEFAULT '0',
  `product_expiry` tinyint(1) NOT NULL DEFAULT '0',
  `purchase_decimals` tinyint(2) NOT NULL DEFAULT '2',
  `decimals` tinyint(2) NOT NULL DEFAULT '2',
  `qty_decimals` tinyint(2) NOT NULL DEFAULT '2',
  `decimals_sep` varchar(2) NOT NULL DEFAULT '.',
  `thousands_sep` varchar(2) NOT NULL DEFAULT ',',
  `invoice_view` tinyint(1) DEFAULT '0',
  `default_biller` int(11) DEFAULT NULL,
  `envato_username` varchar(50) DEFAULT NULL,
  `purchase_code` varchar(100) DEFAULT NULL,
  `rtl` tinyint(1) DEFAULT '0',
  `each_spent` decimal(15,4) DEFAULT NULL,
  `ca_point` tinyint(4) DEFAULT NULL,
  `each_sale` decimal(15,4) DEFAULT NULL,
  `sa_point` tinyint(4) DEFAULT NULL,
  `update` tinyint(1) DEFAULT '0',
  `sac` tinyint(1) DEFAULT '0',
  `display_all_products` tinyint(1) DEFAULT '0',
  `display_symbol` tinyint(1) DEFAULT NULL,
  `symbol` varchar(50) DEFAULT NULL,
  `item_slideshow` tinyint(1) DEFAULT NULL,
  `barcode_separator` varchar(2) NOT NULL DEFAULT '_',
  `remove_expired` tinyint(1) DEFAULT '0',
  `sale_payment_prefix` varchar(20) DEFAULT NULL,
  `purchase_payment_prefix` varchar(20) DEFAULT NULL,
  `sale_loan_prefix` varchar(20) DEFAULT NULL,
  `auto_print` tinyint(1) DEFAULT '1',
  `returnp_prefix` varchar(20) DEFAULT NULL,
  `alert_day` tinyint(3) NOT NULL DEFAULT '0',
  `convert_prefix` varchar(20) DEFAULT NULL,
  `purchase_serial` tinyint(4) NOT NULL,
  `enter_using_stock_prefix` varchar(25) DEFAULT NULL,
  `enter_using_stock_return_prefix` varchar(25) DEFAULT NULL,
  `supplier_deposit_prefix` varchar(20) DEFAULT NULL,
  `sale_order_prefix` varchar(20) DEFAULT NULL,
  `boms_method` tinyint(1) DEFAULT '0',
  `separate_code` tinyint(1) DEFAULT NULL,
  `show_code` tinyint(1) DEFAULT NULL,
  `bill_to` tinyint(1) DEFAULT NULL,
  `show_po` tinyint(1) DEFAULT NULL,
  `show_company_code` tinyint(1) DEFAULT NULL,
  `purchase_order_prefix` varchar(20) DEFAULT NULL,
  `credit_limit` int(11) DEFAULT '0',
  `purchase_request_prefix` varchar(20) DEFAULT NULL,
  `acc_cate_separate` tinyint(1) DEFAULT NULL,
  `stock_deduction` varchar(10) DEFAULT NULL,
  `delivery` varchar(25) DEFAULT NULL,
  `authorization` varchar(50) DEFAULT NULL,
  `shipping` tinyint(1) DEFAULT '0',
  `separate_ref` varchar(50) DEFAULT NULL,
  `journal_prefix` varchar(50) DEFAULT NULL,
  `adjustment_prefix` varchar(50) DEFAULT NULL,
  `system_management` varchar(50) DEFAULT NULL,
  `table_item` varchar(20) DEFAULT NULL,
  `show_logo_invoice` tinyint(1) DEFAULT '1' COMMENT 'Show Logo On Invoice',
  `show_biller_name_invoice` tinyint(1) DEFAULT '1' COMMENT 'Show Biller Name on Footer Invoice',
  `tax_prefix` varchar(20) DEFAULT NULL COMMENT 'TAX Prefix',
  `project_code_prefix` varchar(20) DEFAULT NULL,
  `customer_code_prefix` varchar(20) DEFAULT NULL,
  `supplier_code_prefix` varchar(20) DEFAULT NULL,
  `employee_code_prefix` varchar(20) DEFAULT NULL,
  `allow_change_date` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`setting_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_settings
-- ----------------------------
INSERT INTO `erp_settings` VALUES ('1', '', 'header_logo.png', 'iCloudERP Software', 'english', '1', '2', 'USD', '1', '10', '3.4', '1', '5', 'SALE', 'QUOTE', 'PO', 'TR', 'DO', 'IPAY', 'RE', 'EX', 'J', 'ST', 'PN', 'ADC', '0', 'default', '0', '1', '1', '1', '1', '1', '1', '1', '0', 'Asia/Phnom_Penh', '800', '800', '60', '60', '0', '0', '0', '0', null, 'smtp', '/usr/sbin/sendmail', 'icloud-erp.info', 'crm@icloud-erp.info', 'lsnUYbaKJor1ymn5I3OgUoQb/ZsQG9j1f6CdCDH80q5KxryobVXJ6S/AYbus89a4GrTJfxgYuxnB47OErF3Fjg==', '465', 'ssl', '0000-00-00 00:00:00', '3', 'iclouderp@gmail.com', '0', '4', '0', '0', '1', '1', '1', '0', '2', '2', '2', '.', ',', '0', '1', 'cloud-net', '53d35644-a36e-45cd-b7ee-8dde3a08f83d', '0', '10.0000', '1', '100.0000', '1', '0', '0', '0', '0', 'B', '0', '_', '0', 'RV', 'PV', 'LOAN', '0', 'PRE', '7', 'CON', '0', 'ES', 'ESR', 'SDE', 'SAO', '1', '1', '1', '0', '0', '1', 'PAO', '0', 'PQ', '1', null, 'both', 'manual', '1', '0', '', '', 'project', 'table', '1', '1', null, 'PRO', 'CUS', 'SUP', 'EMP', '1');

-- ----------------------------
-- Table structure for erp_skrill
-- ----------------------------
DROP TABLE IF EXISTS `erp_skrill`;
CREATE TABLE `erp_skrill` (
  `id` int(11) NOT NULL,
  `active` tinyint(4) NOT NULL,
  `account_email` varchar(255) NOT NULL DEFAULT 'testaccount2@moneybookers.com',
  `secret_word` varchar(20) NOT NULL DEFAULT 'mbtest',
  `skrill_currency` varchar(3) NOT NULL DEFAULT 'USD',
  `fixed_charges` decimal(25,4) NOT NULL DEFAULT '0.0000',
  `extra_charges_my` decimal(25,4) NOT NULL DEFAULT '0.0000',
  `extra_charges_other` decimal(25,4) NOT NULL DEFAULT '0.0000',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_skrill
-- ----------------------------

-- ----------------------------
-- Table structure for erp_stock_counts
-- ----------------------------
DROP TABLE IF EXISTS `erp_stock_counts`;
CREATE TABLE `erp_stock_counts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `reference_no` varchar(55) NOT NULL,
  `warehouse_id` int(11) NOT NULL,
  `type` varchar(10) NOT NULL,
  `initial_file` varchar(50) NOT NULL,
  `final_file` varchar(50) DEFAULT NULL,
  `brands` varchar(50) DEFAULT NULL,
  `brand_names` varchar(100) DEFAULT NULL,
  `categories` varchar(50) DEFAULT NULL,
  `category_names` varchar(100) DEFAULT NULL,
  `note` text,
  `products` int(11) DEFAULT NULL,
  `rows` int(11) DEFAULT NULL,
  `differences` int(11) DEFAULT NULL,
  `matches` int(11) DEFAULT NULL,
  `missing` int(11) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `finalized` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `warehouse_id` (`warehouse_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_stock_counts
-- ----------------------------
INSERT INTO `erp_stock_counts` VALUES ('1', '2017-08-25 18:51:43', 'ST/1708/00001', '1', 'full', '025c4e77d933304441c655388392b2a2.csv', 'cfe997e923de013b3923ae373f1d12c5.csv', '', '', '', '', '', '7', '9', '7', '2', '0', '1', '1', '2017-08-25 18:43:51', '1');

-- ----------------------------
-- Table structure for erp_stock_count_items
-- ----------------------------
DROP TABLE IF EXISTS `erp_stock_count_items`;
CREATE TABLE `erp_stock_count_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stock_count_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_code` varchar(50) DEFAULT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `product_variant` varchar(55) DEFAULT NULL,
  `product_variant_id` int(11) DEFAULT NULL,
  `expected` decimal(15,4) NOT NULL,
  `counted` decimal(15,4) NOT NULL,
  `cost` decimal(25,4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `stock_count_id` (`stock_count_id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_stock_count_items
-- ----------------------------
INSERT INTO `erp_stock_count_items` VALUES ('1', '1', '1', 'DK001', 'Mocha', '', null, '10.0000', '5.0000', '1.7007');
INSERT INTO `erp_stock_count_items` VALUES ('2', '1', '2', 'DK02', 'Coco cola', 'can', '1', '20.0000', '10.0000', '0.3816');
INSERT INTO `erp_stock_count_items` VALUES ('3', '1', '2', 'DK02', 'Coco cola', 'Case', '2', '5.0000', '4.0000', '0.3816');
INSERT INTO `erp_stock_count_items` VALUES ('4', '1', '8', 'FDC', 'F00d1 C', '', null, '4.0000', '0.0000', '2.0653');
INSERT INTO `erp_stock_count_items` VALUES ('5', '1', '7', 'FP01', 'Food1', '', null, '100.0000', '0.0000', '2.1868');
INSERT INTO `erp_stock_count_items` VALUES ('6', '1', '5', 'RW01', 'Suger', '', null, '10.0000', '0.0000', '1.2821');
INSERT INTO `erp_stock_count_items` VALUES ('7', '1', '6', 'RW02', 'Salt', '', null, '45.0000', '0.0000', '1.1774');

-- ----------------------------
-- Table structure for erp_subcategories
-- ----------------------------
DROP TABLE IF EXISTS `erp_subcategories`;
CREATE TABLE `erp_subcategories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `code` varchar(55) NOT NULL,
  `name` varchar(55) NOT NULL,
  `image` varchar(55) DEFAULT NULL,
  `type` varchar(55) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_subcategories
-- ----------------------------
INSERT INTO `erp_subcategories` VALUES ('1', '1', 'WE', 'Wine', null, 'drink');

-- ----------------------------
-- Table structure for erp_suspended
-- ----------------------------
DROP TABLE IF EXISTS `erp_suspended`;
CREATE TABLE `erp_suspended` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` varchar(200) DEFAULT NULL,
  `floor` varchar(20) DEFAULT '0',
  `ppl_number` int(11) DEFAULT '0',
  `status` int(11) DEFAULT '0',
  `startdate` datetime DEFAULT NULL,
  `enddate` datetime DEFAULT NULL,
  `note` varchar(200) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `inactive` int(11) DEFAULT NULL,
  `warehouse_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_suspended
-- ----------------------------
INSERT INTO `erp_suspended` VALUES ('1', 'T1', '', 'First Floor', '6', '0', null, null, null, null, '0', '1');
INSERT INTO `erp_suspended` VALUES ('2', 'T2', '<p>Hello World!!!!</p>', 'First Floor', '4', '0', null, null, null, null, '0', '1');

-- ----------------------------
-- Table structure for erp_suspended_bills
-- ----------------------------
DROP TABLE IF EXISTS `erp_suspended_bills`;
CREATE TABLE `erp_suspended_bills` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `end_date` datetime DEFAULT NULL,
  `customer_id` int(11) NOT NULL,
  `customer` varchar(55) DEFAULT NULL,
  `count` int(11) NOT NULL,
  `order_discount_id` varchar(20) DEFAULT NULL,
  `order_tax_id` int(11) DEFAULT NULL,
  `total` decimal(25,4) NOT NULL,
  `biller_id` int(11) DEFAULT NULL,
  `warehouse_id` int(11) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `suspend_id` int(11) NOT NULL DEFAULT '0',
  `suspend_name` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_suspended_bills
-- ----------------------------

-- ----------------------------
-- Table structure for erp_suspended_items
-- ----------------------------
DROP TABLE IF EXISTS `erp_suspended_items`;
CREATE TABLE `erp_suspended_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `suspend_id` int(11) unsigned NOT NULL,
  `product_id` int(11) unsigned NOT NULL,
  `product_code` varchar(55) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `net_unit_price` decimal(25,4) NOT NULL,
  `unit_cost` decimal(25,4) NOT NULL,
  `unit_price` decimal(25,4) NOT NULL,
  `quantity` decimal(15,4) DEFAULT '0.0000',
  `warehouse_id` int(11) DEFAULT NULL,
  `item_tax` decimal(25,4) DEFAULT NULL,
  `tax_rate_id` int(11) DEFAULT NULL,
  `tax` varchar(55) DEFAULT NULL,
  `discount` varchar(55) DEFAULT NULL,
  `item_discount` decimal(25,4) DEFAULT NULL,
  `subtotal` decimal(25,4) NOT NULL,
  `serial_no` varchar(255) DEFAULT NULL,
  `option_id` int(11) DEFAULT NULL,
  `product_type` varchar(20) DEFAULT NULL,
  `real_unit_price` decimal(25,4) DEFAULT NULL,
  `product_noted` varchar(30) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `printed` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_suspended_items
-- ----------------------------

-- ----------------------------
-- Table structure for erp_suspend_layout
-- ----------------------------
DROP TABLE IF EXISTS `erp_suspend_layout`;
CREATE TABLE `erp_suspend_layout` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `suspend_id` int(20) NOT NULL,
  `order` int(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of erp_suspend_layout
-- ----------------------------

-- ----------------------------
-- Table structure for erp_taxation_type_of_account
-- ----------------------------
DROP TABLE IF EXISTS `erp_taxation_type_of_account`;
CREATE TABLE `erp_taxation_type_of_account` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `account_code` int(10) NOT NULL,
  `specific_tax_on_certain_merchandise_and_services` int(10) DEFAULT NULL,
  `accommodation_tax` int(10) DEFAULT NULL,
  `public_lighting_tax` int(10) DEFAULT NULL,
  `other_tax` int(10) DEFAULT NULL,
  `withholding_tax_on_resident` varchar(10) DEFAULT NULL,
  `withholding_tax_on_non_resident` varchar(10) DEFAULT NULL,
  `account_tax_code` varchar(10) DEFAULT NULL,
  `deductible_expenses` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_taxation_type_of_account
-- ----------------------------

-- ----------------------------
-- Table structure for erp_taxation_type_of_product
-- ----------------------------
DROP TABLE IF EXISTS `erp_taxation_type_of_product`;
CREATE TABLE `erp_taxation_type_of_product` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `product_id` int(10) NOT NULL,
  `specific_tax_on_certain_merchandise_and_services` int(10) DEFAULT NULL,
  `accommodation_tax` int(10) DEFAULT NULL,
  `public_lighting_tax` int(10) DEFAULT NULL,
  `other_tax` int(10) DEFAULT NULL,
  `withholding_tax_on_resident` varchar(10) DEFAULT NULL,
  `withholding_tax_on_non_resident` varchar(10) DEFAULT NULL,
  `payment_of_profit_tax` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_taxation_type_of_product
-- ----------------------------

-- ----------------------------
-- Table structure for erp_tax_exchange_rate
-- ----------------------------
DROP TABLE IF EXISTS `erp_tax_exchange_rate`;
CREATE TABLE `erp_tax_exchange_rate` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `usd` int(10) DEFAULT NULL,
  `salary_khm` int(50) DEFAULT NULL,
  `average_khm` varchar(50) DEFAULT NULL,
  `month` int(2) DEFAULT NULL,
  `year` int(4) DEFAULT NULL,
  `tax_type` varchar(255) DEFAULT NULL,
  `usd_curency` varchar(255) DEFAULT NULL,
  `kh_curency` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of erp_tax_exchange_rate
-- ----------------------------

-- ----------------------------
-- Table structure for erp_tax_purchase_vat
-- ----------------------------
DROP TABLE IF EXISTS `erp_tax_purchase_vat`;
CREATE TABLE `erp_tax_purchase_vat` (
  `vat_id` int(11) NOT NULL,
  `bill_num` varchar(100) DEFAULT NULL,
  `debtorno` varchar(100) DEFAULT NULL,
  `locationname` varchar(100) DEFAULT NULL,
  `issuedate` date DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `amount_tax` double DEFAULT NULL,
  `tax_id` int(11) DEFAULT NULL,
  `journal_location` varchar(255) DEFAULT NULL,
  `journal_date` date DEFAULT NULL,
  PRIMARY KEY (`vat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of erp_tax_purchase_vat
-- ----------------------------

-- ----------------------------
-- Table structure for erp_tax_rates
-- ----------------------------
DROP TABLE IF EXISTS `erp_tax_rates`;
CREATE TABLE `erp_tax_rates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(55) NOT NULL,
  `code` varchar(10) DEFAULT NULL,
  `rate` decimal(12,0) NOT NULL,
  `type` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_tax_rates
-- ----------------------------
INSERT INTO `erp_tax_rates` VALUES ('1', 'No Tax', 'NT', '0', '2');
INSERT INTO `erp_tax_rates` VALUES ('2', 'VAT @10%', 'VAT10', '10', '1');
INSERT INTO `erp_tax_rates` VALUES ('3', 'GST @6%', 'GST', '6', '1');
INSERT INTO `erp_tax_rates` VALUES ('4', 'VAT @20%', 'VT20', '20', '1');
INSERT INTO `erp_tax_rates` VALUES ('5', 'TAX @10%', 'TAX', '10', '1');

-- ----------------------------
-- Table structure for erp_term_types
-- ----------------------------
DROP TABLE IF EXISTS `erp_term_types`;
CREATE TABLE `erp_term_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of erp_term_types
-- ----------------------------
INSERT INTO `erp_term_types` VALUES ('1', 'PT-0001', '12(month)');
INSERT INTO `erp_term_types` VALUES ('2', 'PT-0002', '50%');
INSERT INTO `erp_term_types` VALUES ('3', 'PT-0003', '60%');

-- ----------------------------
-- Table structure for erp_transfers
-- ----------------------------
DROP TABLE IF EXISTS `erp_transfers`;
CREATE TABLE `erp_transfers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transfer_no` varchar(55) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `from_warehouse_id` int(11) NOT NULL,
  `from_warehouse_code` varchar(55) NOT NULL,
  `from_warehouse_name` varchar(55) NOT NULL,
  `to_warehouse_id` int(11) NOT NULL,
  `to_warehouse_code` varchar(55) NOT NULL,
  `to_warehouse_name` varchar(55) NOT NULL,
  `note` varchar(1000) DEFAULT NULL,
  `total` decimal(25,4) DEFAULT NULL,
  `total_tax` decimal(25,4) DEFAULT NULL,
  `grand_total` decimal(25,4) DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `status` varchar(55) NOT NULL DEFAULT 'pending',
  `shipping` decimal(25,4) DEFAULT '0.0000',
  `attachment` varchar(55) DEFAULT NULL,
  `attachment1` varchar(55) DEFAULT NULL,
  `attachment2` varchar(55) DEFAULT NULL,
  `biller_id` varchar(55) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_transfers
-- ----------------------------

-- ----------------------------
-- Table structure for erp_transfer_items
-- ----------------------------
DROP TABLE IF EXISTS `erp_transfer_items`;
CREATE TABLE `erp_transfer_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transfer_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_code` varchar(55) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `option_id` int(11) DEFAULT NULL,
  `expiry` date DEFAULT NULL,
  `quantity` decimal(15,4) NOT NULL,
  `tax_rate_id` int(11) DEFAULT NULL,
  `tax` varchar(55) DEFAULT NULL,
  `item_tax` decimal(25,4) DEFAULT NULL,
  `net_unit_cost` decimal(25,4) DEFAULT NULL,
  `subtotal` decimal(25,4) DEFAULT NULL,
  `quantity_balance` decimal(15,4) NOT NULL,
  `unit_cost` decimal(25,4) DEFAULT NULL,
  `real_unit_cost` decimal(25,4) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `warehouse_id` int(11) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transfer_id` (`transfer_id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_transfer_items
-- ----------------------------

-- ----------------------------
-- Table structure for erp_units
-- ----------------------------
DROP TABLE IF EXISTS `erp_units`;
CREATE TABLE `erp_units` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(10) NOT NULL,
  `name` varchar(55) NOT NULL,
  `base_unit` int(11) DEFAULT NULL,
  `operator` varchar(1) DEFAULT NULL,
  `unit_value` varchar(55) DEFAULT NULL,
  `operation_value` varchar(55) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `base_unit` (`base_unit`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_units
-- ----------------------------
INSERT INTO `erp_units` VALUES ('1', 'Unit', 'Unit', null, null, null, null);
INSERT INTO `erp_units` VALUES ('2', 'Flat', 'ខ្នង', null, null, null, null);
INSERT INTO `erp_units` VALUES ('3', '004', 'Case', null, null, null, null);
INSERT INTO `erp_units` VALUES ('4', '005', 'Box', null, null, null, null);
INSERT INTO `erp_units` VALUES ('5', 'Service', 'Service', null, null, null, null);
INSERT INTO `erp_units` VALUES ('6', '001', 'Can', null, null, null, null);
INSERT INTO `erp_units` VALUES ('7', 'aa1', 'កញ្ចប់', null, null, null, null);
INSERT INTO `erp_units` VALUES ('8', '0010', 'ម៉ែត្រការ៉េ', null, null, null, null);
INSERT INTO `erp_units` VALUES ('9', 'Raw', 'Raw', null, null, null, null);

-- ----------------------------
-- Table structure for erp_users
-- ----------------------------
DROP TABLE IF EXISTS `erp_users`;
CREATE TABLE `erp_users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `last_ip_address` varbinary(45) DEFAULT NULL,
  `ip_address` varbinary(45) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(40) NOT NULL,
  `salt` varchar(40) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `activation_code` varchar(40) DEFAULT NULL,
  `forgotten_password_code` varchar(40) DEFAULT NULL,
  `forgotten_password_time` int(11) unsigned DEFAULT NULL,
  `remember_code` varchar(40) DEFAULT NULL,
  `created_on` int(11) unsigned NOT NULL,
  `last_login` int(11) unsigned DEFAULT NULL,
  `active` tinyint(1) unsigned DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `avatar` varchar(55) DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `group_id` int(10) unsigned NOT NULL,
  `warehouse_id` varchar(20) DEFAULT NULL,
  `biller_id` int(10) unsigned DEFAULT NULL,
  `company_id` int(11) DEFAULT NULL,
  `show_cost` tinyint(1) DEFAULT '0',
  `show_price` tinyint(1) DEFAULT '0',
  `award_points` int(11) DEFAULT '0',
  `view_right` tinyint(1) NOT NULL DEFAULT '0',
  `edit_right` tinyint(1) NOT NULL DEFAULT '0',
  `allow_discount` tinyint(1) DEFAULT '0',
  `annualLeave` int(11) DEFAULT '0',
  `sickday` int(11) DEFAULT '0',
  `speacialLeave` int(11) DEFAULT NULL,
  `othersLeave` int(11) DEFAULT NULL,
  `first_name_kh` varchar(50) DEFAULT NULL,
  `last_name_kh` varchar(50) DEFAULT NULL,
  `nationality_kh` varchar(50) DEFAULT NULL,
  `race_kh` varchar(20) NOT NULL,
  `pos_layout` tinyint(1) DEFAULT NULL,
  `pack_id` varchar(50) DEFAULT NULL,
  `sales_standard` tinyint(1) DEFAULT NULL,
  `sales_combo` tinyint(1) DEFAULT NULL,
  `sales_digital` tinyint(1) DEFAULT NULL,
  `sales_service` tinyint(1) DEFAULT NULL,
  `sales_category` varchar(150) DEFAULT NULL,
  `purchase_standard` tinyint(1) DEFAULT NULL,
  `purchase_combo` tinyint(1) DEFAULT NULL,
  `purchase_digital` tinyint(1) DEFAULT NULL,
  `purchase_service` tinyint(1) DEFAULT NULL,
  `purchase_category` varchar(150) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `nationality` varchar(64) DEFAULT NULL,
  `position` varchar(150) DEFAULT NULL,
  `salary` decimal(24,4) DEFAULT NULL,
  `spouse` varchar(150) DEFAULT NULL,
  `number_of_child` tinyint(4) DEFAULT NULL,
  `employeed_date` date DEFAULT NULL,
  `last_paid` date DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `note` text,
  `emergency_contact` varchar(255) DEFAULT NULL,
  `emp_code` varchar(50) DEFAULT NULL,
  `allowance` decimal(24,4) DEFAULT NULL,
  `emp_type` varchar(10) DEFAULT NULL,
  `tax_salary_type` varchar(10) DEFAULT NULL,
  `hide_row` tinyint(1) DEFAULT '0',
  `emp_group` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `group_id` (`group_id`,`warehouse_id`,`biller_id`),
  KEY `group_id_2` (`group_id`,`company_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_users
-- ----------------------------
INSERT INTO `erp_users` VALUES ('1', 0x3139322E3136382E312E3630, 0x0000, 'owner', '06e6c33bfa4496ceceb8eff15f40ec726d8d2336', '', 'owner@cloudnet.com.kh', '', '', null, '078c30f596fa50aa383a756752d503275fdc59c8', '1351661704', '1503653769', '1', 'Own', 'Owner', 'ABC Shop', '012345678', '', 'male', '1', '', null, null, '0', '0', '146396', '0', '0', '0', '0', '0', null, null, '', '', '', '', null, '5', null, null, null, '1', '', null, null, null, null, '', '1970-01-01', 'Khmer', '', '0.0000', '', '0', '2016-05-01', '2016-12-09', '', '', '', '0012', '0.0000', '', '', '0', null);
INSERT INTO `erp_users` VALUES ('2', null, 0x3139322E3136382E312E313535, 'chantroppisey tang', '1f34cc3f652881f9f4e413c1830a5d259088eca0', null, 'chantroppisey.tang@gmail.com', null, null, null, null, '1502848342', '1502848342', '1', 'Chantroppisey', 'TANG', 'PSC Cambodia', '098568265', '869ef5dda6274ba02dfa49a491da0f20.jpg', 'female', '5', '1', '4', null, '0', '0', '0', '1', '1', '1', '0', '0', null, null, null, null, null, '', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null);
INSERT INTO `erp_users` VALUES ('3', 0x3139322E3136382E312E3533, 0x3139322E3136382E312E3533, 'channa', '0d4c6087def8c99b56044084b433a686c991696d', null, 'channa@gmail.com', '5ffbb39aeb669d30c6d0c681c47d651d84d0475c', null, null, null, '1502853531', '1502868960', '1', 'channa', 'mana', 'cloud', '069232312', null, 'male', '7', '3,1', '8', null, null, null, '22', '1', '0', '0', '0', '0', null, null, null, null, null, '', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null);
INSERT INTO `erp_users` VALUES ('4', 0x3139322E3136382E312E3135, 0x3139322E3136382E312E3239, 'channthy', '4ea4976f65bd493f30844e5a8311d0630eddcaee', null, 'lychannthy@gmail.com', '2381d9238ce9c9aa934b1b8da98196f4c11c948f', null, null, null, '1502866247', '1503548260', '1', 'channthy', 'ly', 'ONE TOUCH PHONE SHOP', '015443834', 'b074ac228d02f7f25c475feba0d2a0fb.jpg', 'male', '8', '2,1', '1', null, null, null, '49', '1', '1', '1', '0', '0', null, null, null, null, null, '', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null);
INSERT INTO `erp_users` VALUES ('5', null, 0x3139322E3136382E312E313035, 'mouy.user', '02f94aca029a0686fe4420da661494000127029a', null, 'mouy.user@gmail.com', null, null, null, null, '1503281432', '1503281432', '1', 'mouy', 'user', 'CloudNet', '0716357097', '545004f72e1e257f78cca5f7e078c23e.jpg', 'female', '3', null, null, '21', '0', '0', '0', '0', '0', '0', '0', '0', null, null, null, null, null, '', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null);
INSERT INTO `erp_users` VALUES ('6', null, 0x3139322E3136382E312E313035, 'mouyuser.customer', 'edd1781624c399459894b0dc866efb276c8294b2', null, 'mouyuser.customer@gmail.com', null, null, null, null, '1503283913', '1503283913', '1', 'mouy-user', 'customer', 'Bee Bee', '0962216211', null, 'female', '3', null, null, '23', '0', '0', '0', '0', '0', '0', '0', '0', null, null, null, null, null, '', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null);
INSERT INTO `erp_users` VALUES ('7', null, 0x3139322E3136382E312E313230, 'saothan', '7a62baa6e4f8e663c0b5321680dfade7398ba83c', null, 'saothan@gmail.com', '91fa18e981c8346323cf7138dfd0f8f6da6850d0', null, null, null, '1503298389', '1503298389', '0', 'kin', 'saothan', 'CharLess Wembley', '096 333 9498', null, 'male', '5', '1', '4', null, '0', '0', '0', '1', '0', '1', '0', '0', null, null, null, null, null, '', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null);
INSERT INTO `erp_users` VALUES ('8', 0x3139322E3136382E312E3537, 0x3139322E3136382E312E313032, 'leang@gmail.com', 'e01f5392bc938035c240398517cc9d928c6ca7cf', null, 'leang@gmail.com', 'cc42f7c0d598a4907625c4e79177654a81e2dd24', null, null, 'a99f1b2460a35445cb6ac32a2d3d341554349703', '1503460606', '1503473377', '1', 'chhong', 'chhunleang', 'CloudNET', '098776655', null, 'male', '6', '2,1', '1', null, '0', '0', '0', '1', '0', '1', '0', '0', null, null, null, null, null, '', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null);
INSERT INTO `erp_users` VALUES ('9', 0x3139322E3136382E312E313034, 0x3139322E3136382E312E313034, 'mouyleang chhorn', '38d973ab3c6d0fa51f9b96b4e0758691a3bbf116', null, 'mouyleang.chhorn@gmail.com', null, null, null, '5fa616bc09b34b56e2298c7827613904a1fa74c5', '1503626206', '1503649865', '1', 'mouyleang', 'chhorn', 'ABC', '0962216209', null, 'female', '7', '4', '25', null, null, null, '0', '1', '0', '0', '0', '0', null, null, null, null, null, '', '0', null, null, null, null, null, '1', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null);

-- ----------------------------
-- Table structure for erp_users_bank_account
-- ----------------------------
DROP TABLE IF EXISTS `erp_users_bank_account`;
CREATE TABLE `erp_users_bank_account` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `bankaccount_code` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of erp_users_bank_account
-- ----------------------------
INSERT INTO `erp_users_bank_account` VALUES ('1', '7', '100103,100105');
INSERT INTO `erp_users_bank_account` VALUES ('2', '3', '100103');
INSERT INTO `erp_users_bank_account` VALUES ('3', '3', '100105');
INSERT INTO `erp_users_bank_account` VALUES ('4', '2', '100101');
INSERT INTO `erp_users_bank_account` VALUES ('5', '3', '100100');
INSERT INTO `erp_users_bank_account` VALUES ('6', '4', '100101');
INSERT INTO `erp_users_bank_account` VALUES ('7', '4', '100104');
INSERT INTO `erp_users_bank_account` VALUES ('8', '4', '100105');
INSERT INTO `erp_users_bank_account` VALUES ('9', '5', '1');
INSERT INTO `erp_users_bank_account` VALUES ('10', '6', '1');
INSERT INTO `erp_users_bank_account` VALUES ('11', '7', '100100');
INSERT INTO `erp_users_bank_account` VALUES ('12', '8', '');
INSERT INTO `erp_users_bank_account` VALUES ('13', '9', '100100');

-- ----------------------------
-- Table structure for erp_user_logins
-- ----------------------------
DROP TABLE IF EXISTS `erp_user_logins`;
CREATE TABLE `erp_user_logins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `company_id` int(11) DEFAULT NULL,
  `ip_address` varbinary(16) NOT NULL,
  `login` varchar(100) NOT NULL,
  `time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_user_logins
-- ----------------------------
INSERT INTO `erp_user_logins` VALUES ('1', '1', null, 0x3139322E3136382E312E313636, 'owner@cloudNET.com.kh', '2017-08-25 16:26:49');
INSERT INTO `erp_user_logins` VALUES ('2', '1', null, 0x3139322E3136382E312E3433, 'owner@cloudnet.com.kh', '2017-08-25 16:27:12');
INSERT INTO `erp_user_logins` VALUES ('3', '1', null, 0x3139322E3136382E312E3232, 'owner@cloudnet.com.kh', '2017-08-25 16:29:44');
INSERT INTO `erp_user_logins` VALUES ('4', '1', null, 0x3139322E3136382E312E3630, 'owner@cloudnet.com.kh', '2017-08-25 16:32:52');
INSERT INTO `erp_user_logins` VALUES ('5', '1', null, 0x3139322E3136382E312E3630, 'owner@cloudnet.com.kh', '2017-08-25 16:36:09');

-- ----------------------------
-- Table structure for erp_variants
-- ----------------------------
DROP TABLE IF EXISTS `erp_variants`;
CREATE TABLE `erp_variants` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(55) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_variants
-- ----------------------------
INSERT INTO `erp_variants` VALUES ('1', 'Unit');
INSERT INTO `erp_variants` VALUES ('2', 'Box');
INSERT INTO `erp_variants` VALUES ('3', 'm2');
INSERT INTO `erp_variants` VALUES ('4', 'can');
INSERT INTO `erp_variants` VALUES ('5', 'កញ្ចប់');
INSERT INTO `erp_variants` VALUES ('6', 'ឡូត៍');
INSERT INTO `erp_variants` VALUES ('7', 'Case');
INSERT INTO `erp_variants` VALUES ('8', 'Kg');
INSERT INTO `erp_variants` VALUES ('9', 'ក្រាម');
INSERT INTO `erp_variants` VALUES ('10', 'ម៉ែត្រការ៉េ');
INSERT INTO `erp_variants` VALUES ('11', 'កេស');

-- ----------------------------
-- Table structure for erp_warehouses
-- ----------------------------
DROP TABLE IF EXISTS `erp_warehouses`;
CREATE TABLE `erp_warehouses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `map` varchar(255) DEFAULT NULL,
  `phone` varchar(55) DEFAULT NULL,
  `email` varchar(55) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_warehouses
-- ----------------------------
INSERT INTO `erp_warehouses` VALUES ('1', 'pp', 'Phnom Penh', 'Phnom Penh', null, '012345678', 'test_pp@gmail.com');
INSERT INTO `erp_warehouses` VALUES ('2', 'kpc', 'Kampong Cham', '<p>Kampong Cham</p>', null, '012435344', 'test2@gmail.com');
INSERT INTO `erp_warehouses` VALUES ('3', 'cap', 'Chbar Ampov', '<p>#322, Phum Chroy Basak, Preak Pra, Chbar Ampov, Phnom Penh Cambodia</p>', null, '098764643', 'cap@gmail.com');
INSERT INTO `erp_warehouses` VALUES ('4', 'naga_world', 'naga_world', '<p>pp</p>', null, '', '');

-- ----------------------------
-- Table structure for erp_warehouses_products
-- ----------------------------
DROP TABLE IF EXISTS `erp_warehouses_products`;
CREATE TABLE `erp_warehouses_products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `warehouse_id` int(11) NOT NULL,
  `quantity` decimal(15,4) NOT NULL,
  `rack` varchar(55) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  KEY `warehouse_id` (`warehouse_id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_warehouses_products
-- ----------------------------
INSERT INTO `erp_warehouses_products` VALUES ('1', '1', '1', '10.0000', null);
INSERT INTO `erp_warehouses_products` VALUES ('2', '1', '2', '0.0000', null);
INSERT INTO `erp_warehouses_products` VALUES ('3', '1', '3', '0.0000', null);
INSERT INTO `erp_warehouses_products` VALUES ('4', '1', '4', '0.0000', null);
INSERT INTO `erp_warehouses_products` VALUES ('5', '2', '1', '140.0000', null);
INSERT INTO `erp_warehouses_products` VALUES ('6', '2', '2', '0.0000', null);
INSERT INTO `erp_warehouses_products` VALUES ('7', '2', '3', '0.0000', null);
INSERT INTO `erp_warehouses_products` VALUES ('8', '2', '4', '0.0000', null);
INSERT INTO `erp_warehouses_products` VALUES ('9', '3', '1', '240.0000', null);
INSERT INTO `erp_warehouses_products` VALUES ('10', '3', '2', '0.0000', null);
INSERT INTO `erp_warehouses_products` VALUES ('11', '3', '3', '0.0000', null);
INSERT INTO `erp_warehouses_products` VALUES ('12', '3', '4', '0.0000', null);
INSERT INTO `erp_warehouses_products` VALUES ('13', '4', '1', '0.0000', null);
INSERT INTO `erp_warehouses_products` VALUES ('14', '4', '2', '0.0000', null);
INSERT INTO `erp_warehouses_products` VALUES ('15', '4', '3', '0.0000', null);
INSERT INTO `erp_warehouses_products` VALUES ('16', '4', '4', '0.0000', null);
INSERT INTO `erp_warehouses_products` VALUES ('17', '5', '1', '10.0000', null);
INSERT INTO `erp_warehouses_products` VALUES ('18', '5', '2', '0.0000', null);
INSERT INTO `erp_warehouses_products` VALUES ('19', '5', '3', '0.0000', null);
INSERT INTO `erp_warehouses_products` VALUES ('20', '5', '4', '0.0000', null);
INSERT INTO `erp_warehouses_products` VALUES ('21', '6', '1', '45.0000', null);
INSERT INTO `erp_warehouses_products` VALUES ('22', '6', '2', '0.0000', null);
INSERT INTO `erp_warehouses_products` VALUES ('23', '6', '3', '0.0000', null);
INSERT INTO `erp_warehouses_products` VALUES ('24', '6', '4', '0.0000', null);
INSERT INTO `erp_warehouses_products` VALUES ('25', '7', '1', '70.0000', null);
INSERT INTO `erp_warehouses_products` VALUES ('26', '7', '2', '0.0000', null);
INSERT INTO `erp_warehouses_products` VALUES ('27', '7', '3', '0.0000', null);
INSERT INTO `erp_warehouses_products` VALUES ('28', '7', '4', '0.0000', null);
INSERT INTO `erp_warehouses_products` VALUES ('29', '8', '1', '4.0000', null);
INSERT INTO `erp_warehouses_products` VALUES ('30', '8', '2', '0.0000', null);
INSERT INTO `erp_warehouses_products` VALUES ('31', '8', '3', '0.0000', null);
INSERT INTO `erp_warehouses_products` VALUES ('32', '8', '4', '0.0000', null);

-- ----------------------------
-- Table structure for erp_warehouses_products_variants
-- ----------------------------
DROP TABLE IF EXISTS `erp_warehouses_products_variants`;
CREATE TABLE `erp_warehouses_products_variants` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `option_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `warehouse_id` int(11) NOT NULL,
  `quantity` decimal(15,4) NOT NULL,
  `rack` varchar(55) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `option_id` (`option_id`),
  KEY `product_id` (`product_id`),
  KEY `warehouse_id` (`warehouse_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of erp_warehouses_products_variants
-- ----------------------------
INSERT INTO `erp_warehouses_products_variants` VALUES ('1', '1', '2', '1', '-100.0000', null);
INSERT INTO `erp_warehouses_products_variants` VALUES ('2', '1', '2', '2', '0.0000', null);
INSERT INTO `erp_warehouses_products_variants` VALUES ('3', '1', '2', '3', '0.0000', null);
INSERT INTO `erp_warehouses_products_variants` VALUES ('4', '1', '2', '4', '0.0000', null);
INSERT INTO `erp_warehouses_products_variants` VALUES ('5', '2', '2', '1', '240.0000', null);
INSERT INTO `erp_warehouses_products_variants` VALUES ('6', '2', '2', '2', '0.0000', null);
INSERT INTO `erp_warehouses_products_variants` VALUES ('7', '2', '2', '3', '0.0000', null);
INSERT INTO `erp_warehouses_products_variants` VALUES ('8', '2', '2', '4', '0.0000', null);
INSERT INTO `erp_warehouses_products_variants` VALUES ('9', '3', '3', '1', '0.0000', null);
INSERT INTO `erp_warehouses_products_variants` VALUES ('10', '3', '3', '2', '0.0000', null);
INSERT INTO `erp_warehouses_products_variants` VALUES ('11', '3', '3', '3', '0.0000', null);
INSERT INTO `erp_warehouses_products_variants` VALUES ('12', '3', '3', '4', '0.0000', null);
INSERT INTO `erp_warehouses_products_variants` VALUES ('13', '4', '3', '1', '240.0000', null);
INSERT INTO `erp_warehouses_products_variants` VALUES ('14', '4', '3', '2', '0.0000', null);
INSERT INTO `erp_warehouses_products_variants` VALUES ('15', '4', '3', '3', '0.0000', null);
INSERT INTO `erp_warehouses_products_variants` VALUES ('16', '4', '3', '4', '0.0000', null);
DROP TRIGGER IF EXISTS `gl_trans_adjustments_insert`;
DELIMITER ;;
CREATE TRIGGER `gl_trans_adjustments_insert` AFTER INSERT ON `erp_adjustments` FOR EACH ROW BEGIN

	DECLARE v_tran_no INTEGER;
	DECLARE v_default_stock_adjust INTEGER;
	DECLARE v_default_stock INTEGER;
	DECLARE v_acc_cate_separate INTEGER;

	SET v_acc_cate_separate = (SELECT erp_settings.acc_cate_separate FROM erp_settings WHERE erp_settings.setting_id = '1');
	SET v_tran_no = (SELECT COALESCE(MAX(tran_no),0) +1 FROM erp_gl_trans);

	UPDATE erp_order_ref
		SET tr = v_tran_no
	WHERE
		DATE_FORMAT(date, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m');

	SET v_default_stock_adjust = (SELECT default_stock_adjust FROM erp_account_settings);
	SET v_default_stock = (SELECT default_stock FROM erp_account_settings);


	IF v_acc_cate_separate <> 1 THEN
		
		INSERT INTO erp_gl_trans (
			tran_type,
			tran_no,
			tran_date,
			sectionid,
			account_code,
			narrative,
			amount,
			reference_no,
			description,
			biller_id,
			created_by,
			updated_by
			)
			SELECT
			'STOCK_ADJUST',
			v_tran_no,
			NEW.date,
			erp_gl_sections.sectionid,
			erp_gl_charts.accountcode,
			erp_gl_charts.accountname,
			NEW.total_cost,
			NEW.reference_no,
			NEW.note,
			NEW.biller_id,
			NEW.created_by,
			NEW.updated_by
			FROM
				erp_account_settings
			INNER JOIN erp_gl_charts
				ON erp_gl_charts.accountcode = v_default_stock
				INNER JOIN erp_gl_sections
				ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
			WHERE
				erp_gl_charts.accountcode = v_default_stock;
				
		INSERT INTO erp_gl_trans (

			tran_type,
			tran_no,
			tran_date,
			sectionid,
			account_code,
			narrative,
			amount,
			reference_no,
			description,
			biller_id,
			created_by,
			updated_by
			)
			SELECT
			'STOCK_ADJUST',
			v_tran_no,
			NEW.date,
			erp_gl_sections.sectionid,
			erp_gl_charts.accountcode,
			erp_gl_charts.accountname,
			(-1) * NEW.total_cost,
			NEW.reference_no,
			NEW.note,
			NEW.biller_id,
			NEW.created_by,
			NEW.updated_by
			FROM
				erp_account_settings
			INNER JOIN erp_gl_charts
				ON erp_gl_charts.accountcode = v_default_stock_adjust
				INNER JOIN erp_gl_sections
				ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
			WHERE
				erp_gl_charts.accountcode = v_default_stock_adjust;
				
	ELSE
	
		INSERT INTO erp_gl_trans (

			tran_type,
			tran_no,
			tran_date,
			sectionid,
			account_code,
			narrative,
			amount,
			reference_no,
			description,
			biller_id,
			created_by,
			updated_by
			)
			SELECT
			'STOCK_ADJUST',
			v_tran_no,
			NEW.date,
			erp_gl_sections.sectionid,
			erp_gl_charts.accountcode,
			erp_gl_charts.accountname,
			(-1) * NEW.total_cost,
			NEW.reference_no,
			NEW.note,
			NEW.biller_id,
			NEW.created_by,
			NEW.updated_by
			FROM
				erp_account_settings
			INNER JOIN erp_gl_charts
				ON erp_gl_charts.accountcode = v_default_stock_adjust
				INNER JOIN erp_gl_sections
				ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
			WHERE
				erp_gl_charts.accountcode = v_default_stock_adjust;

	END IF;

END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `gl_trans_adjustments_update`;
DELIMITER ;;
CREATE TRIGGER `gl_trans_adjustments_update` AFTER UPDATE ON `erp_adjustments` FOR EACH ROW BEGIN

	DECLARE v_tran_no INTEGER;
	DECLARE v_default_stock_adjust INTEGER;
	DECLARE v_default_stock INTEGER;
	DECLARE v_acc_cate_separate INTEGER;

	SET v_acc_cate_separate = (SELECT erp_settings.acc_cate_separate FROM erp_settings WHERE erp_settings.setting_id = '1');
	SET v_tran_no = (SELECT erp_gl_trans.tran_no FROM erp_gl_trans WHERE erp_gl_trans.reference_no = NEW.reference_no LIMIT 1);


	SET v_default_stock_adjust = (SELECT default_stock_adjust FROM erp_account_settings);
	SET v_default_stock = (SELECT default_stock FROM erp_account_settings);


	IF NEW.updated_by > 0 THEN
	
		DELETE FROM erp_gl_trans WHERE erp_gl_trans.reference_no = NEW.reference_no;
	
		IF v_acc_cate_separate <> 1 THEN
			
			INSERT INTO erp_gl_trans (
				tran_type,
				tran_no,
				tran_date,
				sectionid,
				account_code,
				narrative,
				amount,
				reference_no,
				description,
				biller_id,
				created_by,
				updated_by
				)
				SELECT
				'STOCK_ADJUST',
				v_tran_no,
				NEW.date,
				erp_gl_sections.sectionid,
				erp_gl_charts.accountcode,
				erp_gl_charts.accountname,
				NEW.total_cost,
				NEW.reference_no,
				NEW.note,
				NEW.biller_id,
				NEW.created_by,
				NEW.updated_by
				FROM
					erp_account_settings
				INNER JOIN erp_gl_charts
					ON erp_gl_charts.accountcode = v_default_stock
					INNER JOIN erp_gl_sections
					ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
				WHERE
					erp_gl_charts.accountcode = v_default_stock;
					
			INSERT INTO erp_gl_trans (

				tran_type,
				tran_no,
				tran_date,
				sectionid,
				account_code,
				narrative,
				amount,
				reference_no,
				description,
				biller_id,
				created_by,
				updated_by
				)
				SELECT
				'STOCK_ADJUST',
				v_tran_no,
				NEW.date,
				erp_gl_sections.sectionid,
				erp_gl_charts.accountcode,
				erp_gl_charts.accountname,
				(-1) * NEW.total_cost,
				NEW.reference_no,
				NEW.note,
				NEW.biller_id,
				NEW.created_by,
				NEW.updated_by
				FROM
					erp_account_settings
				INNER JOIN erp_gl_charts
					ON erp_gl_charts.accountcode = v_default_stock_adjust
					INNER JOIN erp_gl_sections
					ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
				WHERE
					erp_gl_charts.accountcode = v_default_stock_adjust;
					
		ELSE
		
			INSERT INTO erp_gl_trans (

				tran_type,
				tran_no,
				tran_date,
				sectionid,
				account_code,
				narrative,
				amount,
				reference_no,
				description,
				biller_id,
				created_by,
				updated_by
				)
				SELECT
				'STOCK_ADJUST',
				v_tran_no,
				NEW.date,
				erp_gl_sections.sectionid,
				erp_gl_charts.accountcode,
				erp_gl_charts.accountname,
				(-1) * NEW.total_cost,
				NEW.reference_no,
				NEW.note,
				NEW.biller_id,
				NEW.created_by,
				NEW.updated_by
				FROM
					erp_account_settings
				INNER JOIN erp_gl_charts
					ON erp_gl_charts.accountcode = v_default_stock_adjust
					INNER JOIN erp_gl_sections
					ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
				WHERE
					erp_gl_charts.accountcode = v_default_stock_adjust;

		END IF;
		
	END IF;

END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `gl_trans_adjustment_items_insert`;
DELIMITER ;;
CREATE TRIGGER `gl_trans_adjustment_items_insert` AFTER INSERT ON `erp_adjustment_items` FOR EACH ROW BEGIN

	DECLARE v_tran_no INTEGER;
	DECLARE v_default_stock_adjust INTEGER;
	DECLARE v_default_stock INTEGER;
	DECLARE v_acc_cate_separate INTEGER;
	DECLARE v_biller_id INTEGER;
	DECLARE v_reference_no VARCHAR(50);
	DECLARE v_note VARCHAR(255);
	DECLARE v_created_by INTEGER;
	DECLARE v_updated_by INTEGER;

	SET v_acc_cate_separate = (SELECT erp_settings.acc_cate_separate FROM erp_settings WHERE erp_settings.setting_id = '1');
	SET v_reference_no = (SELECT erp_adjustments.reference_no FROM erp_adjustments WHERE erp_adjustments.id = NEW.adjust_id);
	SET v_tran_no = (SELECT erp_gl_trans.tran_no FROM erp_gl_trans WHERE erp_gl_trans.reference_no = v_reference_no LIMIT 1);
	SET v_biller_id = (SELECT erp_adjustments.biller_id FROM erp_adjustments WHERE erp_adjustments.id = NEW.adjust_id);
	SET v_note = (SELECT erp_adjustments.note FROM erp_adjustments WHERE erp_adjustments.id = NEW.adjust_id);
	SET v_created_by = (SELECT erp_adjustments.created_by FROM erp_adjustments WHERE erp_adjustments.id = NEW.adjust_id);
	SET v_updated_by = (SELECT erp_adjustments.updated_by FROM erp_adjustments WHERE erp_adjustments.id = NEW.adjust_id);

	SET v_default_stock = (SELECT 
								erp_categories.ac_stock_adj 
							FROM 
								erp_categories 
							INNER JOIN 
								erp_products 
							ON erp_categories.id = erp_products.category_id
							WHERE
								erp_products.id = NEW.product_id);


	IF v_acc_cate_separate = 1 THEN
		INSERT INTO erp_gl_trans (
			tran_type,
			tran_no,
			tran_date,
			sectionid,
			account_code,
			narrative,
			amount,
			reference_no,
			description,
			biller_id,
			created_by,
			updated_by
			)
			SELECT
			'STOCK_ADJUST',
			v_tran_no,
			NEW.date,
			erp_gl_sections.sectionid,
			erp_gl_charts.accountcode,
			erp_gl_charts.accountname,
			NEW.total_cost,
			v_reference_no,
			v_note,
			v_biller_id,
			v_created_by,
			v_updated_by
			FROM
				erp_account_settings
			INNER JOIN erp_gl_charts
				ON erp_gl_charts.accountcode = v_default_stock
				INNER JOIN erp_gl_sections
				ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
			WHERE
				erp_gl_charts.accountcode = v_default_stock;

	END IF;


END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `gl_trans_adjustment_update`;
DELIMITER ;;
CREATE TRIGGER `gl_trans_adjustment_update` AFTER UPDATE ON `erp_adjustment_items` FOR EACH ROW BEGIN

DECLARE v_tran_no INTEGER;
DECLARE v_default_stock_adjust INTEGER;
DECLARE v_default_stock INTEGER;

SET v_tran_no = (SELECT tran_no FROM erp_gl_trans WHERE tran_type='STOCK_ADJUST' AND reference_no = NEW.id LIMIT 0,1); 

DELETE FROM erp_gl_trans WHERE tran_type='STOCK_ADJUST' AND reference_no = NEW.id;

SET v_default_stock_adjust = (SELECT default_stock_adjust FROM erp_account_settings);
SET v_default_stock = (SELECT default_stock FROM erp_account_settings);


/* ==== Addition =====*/

	IF NEW.type = 'addition' THEN
		INSERT INTO erp_gl_trans (
			tran_type,
			tran_no,
			tran_date,
			sectionid,
			account_code,
			narrative,
			amount,
			reference_no,
			description,
			biller_id,
			created_by,
			updated_by
			)
			SELECT
			'STOCK_ADJUST',
			v_tran_no,
			NEW.date,
			erp_gl_sections.sectionid,
			erp_gl_charts.accountcode,
			erp_gl_charts.accountname,
			abs(NEW.total_cost),
			NEW.id,
			NEW.note,
			NEW.biller_id,
			NEW.created_by,
			NEW.updated_by
			FROM
				erp_account_settings
			INNER JOIN erp_gl_charts
				ON erp_gl_charts.accountcode = v_default_stock
				INNER JOIN erp_gl_sections
				ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
			WHERE
				erp_gl_charts.accountcode = v_default_stock;

		INSERT INTO erp_gl_trans (
			tran_type,
			tran_no,
			tran_date,
			sectionid,
			account_code,
			narrative,
			amount,
			reference_no,
			description,
			biller_id,
			created_by,
			updated_by
			)
			SELECT
			'STOCK_ADJUST',
			v_tran_no,
			NEW.date,
			erp_gl_sections.sectionid,
			erp_gl_charts.accountcode,
			erp_gl_charts.accountname,
			(-1)* abs(NEW.total_cost),
			NEW.id,
			NEW.note,
			NEW.biller_id,
			NEW.created_by,
			NEW.updated_by
			FROM
				erp_account_settings
			INNER JOIN erp_gl_charts
				ON erp_gl_charts.accountcode = v_default_stock_adjust
				INNER JOIN erp_gl_sections
				ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
			WHERE
				erp_gl_charts.accountcode = v_default_stock_adjust;

	ELSE

  		INSERT INTO erp_gl_trans (
			tran_type,
			tran_no,
			tran_date,
			sectionid,
			account_code,
			narrative,
			amount,
			reference_no,
			description,
			biller_id,
			created_by,
			updated_by
			)
			SELECT
			'STOCK_ADJUST',
			v_tran_no,
			NEW.date,
			erp_gl_sections.sectionid,
			erp_gl_charts.accountcode,
			erp_gl_charts.accountname,
			(-1)*abs(NEW.total_cost),
			NEW.id,
			NEW.note,
			NEW.biller_id,
			NEW.created_by,
			NEW.updated_by
			FROM
				erp_account_settings
			INNER JOIN erp_gl_charts
				ON erp_gl_charts.accountcode = v_default_stock
				INNER JOIN erp_gl_sections
				ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
			WHERE
				erp_gl_charts.accountcode = v_default_stock;

		INSERT INTO erp_gl_trans (
			tran_type,
			tran_no,
			tran_date,
			sectionid,
			account_code,
			narrative,
			amount,
			reference_no,
			description,
			biller_id,
			created_by,
			updated_by
			)
			SELECT
			'STOCK_ADJUST',
			v_tran_no,
			NEW.date,
			erp_gl_sections.sectionid,
			erp_gl_charts.accountcode,
			erp_gl_charts.accountname,
			abs(NEW.total_cost),
			NEW.id,
			NEW.note,
			NEW.biller_id,
			NEW.created_by,
			NEW.updated_by
			FROM
				erp_account_settings
			INNER JOIN erp_gl_charts
				ON erp_gl_charts.accountcode = v_default_stock_adjust
				INNER JOIN erp_gl_sections
				ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
			WHERE
				erp_gl_charts.accountcode = v_default_stock_adjust;
	END IF;


END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `gl_trans_adjustment_delete`;
DELIMITER ;;
CREATE TRIGGER `gl_trans_adjustment_delete` AFTER DELETE ON `erp_adjustment_items` FOR EACH ROW BEGIN

   UPDATE erp_gl_trans SET amount = 0, description = CONCAT(description,' (Cancelled)')
   WHERE tran_type='STOCK_ADJUST' AND reference_no = OLD.id;

END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `delete_gl_trans_convert_update`;
DELIMITER ;;
CREATE TRIGGER `delete_gl_trans_convert_update` AFTER UPDATE ON `erp_convert` FOR EACH ROW BEGIN

	IF NEW.updated_by THEN
	
		DELETE FROM erp_gl_trans WHERE erp_gl_trans.reference_no = NEW.reference_no;
	
	END IF;

END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `gl_trans_convert_items_insert`;
DELIMITER ;;
CREATE TRIGGER `gl_trans_convert_items_insert` AFTER INSERT ON `erp_convert_items` FOR EACH ROW BEGIN

	DECLARE v_tran_no INTEGER;
	DECLARE v_tran_date DATETIME;
	DECLARE v_created_by INTEGER;
	DECLARE v_updated_by INTEGER;
	DECLARE v_biller_id INTEGER;
	DECLARE v_reference_no VARCHAR(50);
	DECLARE v_note VARCHAR(255);
	DECLARE v_category_id INTEGER;
	
	SET v_created_by = (SELECT erp_convert.created_by FROM erp_convert WHERE erp_convert.id = NEW.convert_id LIMIT 0, 1);
	SET v_updated_by = (SELECT erp_convert.updated_by FROM erp_convert WHERE erp_convert.id = NEW.convert_id LIMIT 0, 1);
	SET v_biller_id = (SELECT erp_convert.biller_id FROM erp_convert WHERE erp_convert.id = NEW.convert_id LIMIT 0, 1);
	SET v_tran_date = (SELECT erp_convert.date FROM erp_convert WHERE erp_convert.id = NEW.convert_id LIMIT 0, 1);
	SET v_reference_no = (SELECT erp_convert.reference_no FROM erp_convert WHERE erp_convert.id = NEW.convert_id LIMIT 0, 1);
	SET v_note = (SELECT erp_convert.noted FROM erp_convert WHERE erp_convert.id = NEW.convert_id LIMIT 0, 1);
	SET v_category_id = (SELECT erp_products.category_id FROM erp_products WHERE erp_products.id = NEW.product_id);

	IF NOT ISNULL(v_updated_by) THEN
		
		SET v_tran_no = (SELECT erp_gl_trans.tran_no FROM erp_gl_trans WHERE erp_gl_trans.reference_no = v_reference_no LIMIT 0, 1);
							
	ELSE
		SET v_tran_no = (SELECT erp_gl_trans.tran_no FROM erp_gl_trans WHERE erp_gl_trans.reference_no = v_reference_no LIMIT 0, 1);
		
		IF ISNULL(v_tran_no) THEN
		
			SET v_tran_no = (SELECT COALESCE (MAX(tran_no), 0) + 1 FROM erp_gl_trans);
			UPDATE erp_order_ref SET tr = v_tran_no WHERE DATE_FORMAT(date, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m');
			
		END IF;
	
	END IF;
		
	IF NEW.status = 'deduct' THEN
		
		INSERT INTO erp_gl_trans (
			tran_type,
			tran_no,
			tran_date,
			sectionid,
			account_code,
			narrative,
			amount,
			reference_no,
			description,
			biller_id,
			created_by,
			updated_by
		) SELECT
			'CONVERT',
			v_tran_no,
			v_tran_date,
			erp_gl_sections.sectionid,
			erp_gl_charts.accountcode,
			erp_gl_charts.accountname,
			(-1) * abs(NEW.quantity * NEW.cost),
			v_reference_no,
			v_note,
			v_biller_id,
			v_created_by,
			v_updated_by
		FROM
			erp_categories
			INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_categories.ac_stock
			INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
		WHERE
			erp_categories.id = v_category_id;
	
	END IF;
	
	IF NEW.status = 'add' THEN
	
		INSERT INTO erp_gl_trans (
			tran_type,
			tran_no,
			tran_date,
			sectionid,
			account_code,
			narrative,
			amount,
			reference_no,
			description,
			biller_id,
			created_by,
			updated_by
		) SELECT
			'CONVERT',
			v_tran_no,
			v_tran_date,
			erp_gl_sections.sectionid,
			erp_gl_charts.accountcode,
			erp_gl_charts.accountname,
			abs(NEW.quantity * NEW.cost),
			v_reference_no,
			v_note,
			v_biller_id,
			v_created_by,
			v_updated_by
		FROM
			erp_categories
			INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_categories.ac_stock
			INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
		WHERE
			erp_categories.id = v_category_id;
	
	END IF;
					
END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `gl_trans_convert_items_update`;
DELIMITER ;;
CREATE TRIGGER `gl_trans_convert_items_update` AFTER UPDATE ON `erp_convert_items` FOR EACH ROW BEGIN

	DECLARE v_tran_no INTEGER;
	DECLARE v_tran_date DATETIME;
	DECLARE v_created_by INTEGER;
	DECLARE v_updated_by INTEGER;
	DECLARE v_biller_id INTEGER;
	DECLARE v_reference_no VARCHAR(50);
	DECLARE v_note VARCHAR(255);
	DECLARE v_category_id INTEGER;
	
	SET v_created_by = (SELECT erp_convert.created_by FROM erp_convert WHERE erp_convert.id = NEW.convert_id LIMIT 0, 1);
	SET v_updated_by = (SELECT erp_convert.updated_by FROM erp_convert WHERE erp_convert.id = NEW.convert_id LIMIT 0, 1);
	SET v_biller_id = (SELECT erp_convert.biller_id FROM erp_convert WHERE erp_convert.id = NEW.convert_id LIMIT 0, 1);
	SET v_tran_date = (SELECT erp_convert.date FROM erp_convert WHERE erp_convert.id = NEW.convert_id LIMIT 0, 1);
	SET v_reference_no = (SELECT erp_convert.reference_no FROM erp_convert WHERE erp_convert.id = NEW.convert_id LIMIT 0, 1);
	SET v_note = (SELECT erp_convert.noted FROM erp_convert WHERE erp_convert.id = NEW.convert_id LIMIT 0, 1);
	SET v_category_id = (SELECT erp_products.category_id FROM erp_products WHERE erp_products.id = NEW.product_id);

	IF NOT ISNULL(v_updated_by) THEN
		
		SET v_tran_no = (SELECT erp_gl_trans.tran_no FROM erp_gl_trans WHERE erp_gl_trans.reference_no = v_reference_no LIMIT 0, 1);
							
	ELSE
		SET v_tran_no = (SELECT erp_gl_trans.tran_no FROM erp_gl_trans WHERE erp_gl_trans.reference_no = v_reference_no LIMIT 0, 1);
		
		IF ISNULL(v_tran_no) THEN
		
			SET v_tran_no = (SELECT COALESCE (MAX(tran_no), 0) + 1 FROM erp_gl_trans);
			UPDATE erp_order_ref SET tr = v_tran_no WHERE DATE_FORMAT(date, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m');
			
		END IF;
	
	END IF;
		
	IF NEW.status = 'deduct' THEN
		
		INSERT INTO erp_gl_trans (
			tran_type,
			tran_no,
			tran_date,
			sectionid,
			account_code,
			narrative,
			amount,
			reference_no,
			description,
			biller_id,
			created_by,
			updated_by
		) SELECT
			'CONVERT',
			v_tran_no,
			v_tran_date,
			erp_gl_sections.sectionid,
			erp_gl_charts.accountcode,
			erp_gl_charts.accountname,
			(-1) * abs(NEW.quantity * NEW.cost),
			v_reference_no,
			v_note,
			v_biller_id,
			v_created_by,
			v_updated_by
		FROM
			erp_categories
			INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_categories.ac_stock
			INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
		WHERE
			erp_categories.id = v_category_id;
	
	END IF;
	
	IF NEW.status = 'add' THEN
	
		INSERT INTO erp_gl_trans (
			tran_type,
			tran_no,
			tran_date,
			sectionid,
			account_code,
			narrative,
			amount,
			reference_no,
			description,
			biller_id,
			created_by,
			updated_by
		) SELECT
			'CONVERT',
			v_tran_no,
			v_tran_date,
			erp_gl_sections.sectionid,
			erp_gl_charts.accountcode,
			erp_gl_charts.accountname,
			abs(NEW.quantity * NEW.cost),
			v_reference_no,
			v_note,
			v_biller_id,
			v_created_by,
			v_updated_by
		FROM
			erp_categories
			INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_categories.ac_stock
			INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
		WHERE
			erp_categories.id = v_category_id;
	
	END IF;
					
END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `gl_trans_deliveries_insert`;
DELIMITER ;;
CREATE TRIGGER `gl_trans_deliveries_insert` AFTER INSERT ON `erp_deliveries` FOR EACH ROW BEGIN

	DECLARE v_tran_no INTEGER;
	DECLARE v_tran_date DATETIME;
	DECLARE v_acc_cate_separate INTEGER;
	
	SET v_acc_cate_separate = (SELECT erp_settings.acc_cate_separate FROM erp_settings WHERE erp_settings.setting_id = '1');
	IF NEW.delivery_status = "completed" THEN

		SET v_tran_no = (
			SELECT
				COALESCE (MAX(tran_no), 0) + 1
			FROM
				erp_gl_trans
		);
		UPDATE erp_order_ref
		SET tr = v_tran_no
		WHERE
			DATE_FORMAT(date, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m');
		
		IF v_acc_cate_separate <> 1 THEN
			INSERT INTO erp_gl_trans (
				tran_type,
				tran_no,
				tran_date,
				sectionid,
				account_code,
				narrative,
				amount,

				reference_no,
				description,
				biller_id,
				created_by,
				updated_by
			) SELECT
				'DELIVERY',
				v_tran_no,
				NEW.date,
				erp_gl_sections.sectionid,
				erp_gl_charts.accountcode,
				erp_gl_charts.accountname,
				NEW.total_cost,
				NEW.do_reference_no,
				NEW.customer,
				NEW.biller_id,
				NEW.created_by,
				NEW.updated_by
			FROM
				erp_account_settings
			INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_account_settings.default_cost
			INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
			WHERE
				erp_gl_charts.accountcode = erp_account_settings.default_cost;


			INSERT INTO erp_gl_trans (
				tran_type,
				tran_no,
				tran_date,
				sectionid,
				account_code,
				narrative,
				amount,
				reference_no,
				description,
				biller_id,
				created_by,
				updated_by
			) SELECT
				'DELIVERY',
				v_tran_no,
				NEW.date,
				erp_gl_sections.sectionid,
				erp_gl_charts.accountcode,
				erp_gl_charts.accountname,
				(- 1) * abs(NEW.total_cost),
				NEW.do_reference_no,
				NEW.customer,
				NEW.biller_id,
				NEW.created_by,
				NEW.updated_by
			FROM
				erp_account_settings
			INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_account_settings.default_stock
			INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
			WHERE
				erp_gl_charts.accountcode = erp_account_settings.default_stock;	
		END IF;
		
	END IF;

END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `gl_trans_deliveries_update`;
DELIMITER ;;
CREATE TRIGGER `gl_trans_deliveries_update` AFTER UPDATE ON `erp_deliveries` FOR EACH ROW BEGIN

	DECLARE v_tran_no INTEGER;
	DECLARE v_tran_date DATETIME;
	DECLARE v_acc_cate_separate INTEGER;
	
	SET v_acc_cate_separate = (SELECT erp_settings.acc_cate_separate FROM erp_settings WHERE erp_settings.setting_id = '1');
	
	IF NEW.updated_count <> OLD.updated_count AND NEW.updated_by > 0 THEN
	
		DELETE FROM erp_gl_trans WHERE erp_gl_trans.tran_type = 'DELIVERY' AND erp_gl_trans.reference_no = NEW.do_reference_no;
		
		IF NEW.delivery_status = "completed" THEN
			SET v_tran_no = (
				SELECT
					COALESCE (MAX(tran_no), 0) + 1
				FROM
					erp_gl_trans
			);
			UPDATE erp_order_ref
			SET tr = v_tran_no
			WHERE
				DATE_FORMAT(date, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m');
			
			IF v_acc_cate_separate <> 1 THEN
				INSERT INTO erp_gl_trans (
					tran_type,
					tran_no,
					tran_date,
					sectionid,
					account_code,
					narrative,
					amount,
					reference_no,
					description,
					biller_id,
					created_by,
					updated_by
				) SELECT
					'DELIVERY',
					v_tran_no,
					NEW.date,
					erp_gl_sections.sectionid,
					erp_gl_charts.accountcode,
					erp_gl_charts.accountname,
					NEW.total_cost,
					NEW.do_reference_no,
					NEW.customer,
					NEW.biller_id,
					NEW.created_by,
					NEW.updated_by
				FROM
					erp_account_settings
				INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_account_settings.default_cost
				INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
				WHERE
					erp_gl_charts.accountcode = erp_account_settings.default_cost;


				INSERT INTO erp_gl_trans (
					tran_type,
					tran_no,
					tran_date,
					sectionid,
					account_code,
					narrative,
					amount,
					reference_no,
					description,
					biller_id,
					created_by,
					updated_by
				) SELECT
					'DELIVERY',
					v_tran_no,
					NEW.date,
					erp_gl_sections.sectionid,
					erp_gl_charts.accountcode,
					erp_gl_charts.accountname,
					(- 1) * abs(NEW.total_cost),
					NEW.do_reference_no,
					NEW.customer,
					NEW.biller_id,
					NEW.created_by,
					NEW.updated_by
				FROM
					erp_account_settings
				INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_account_settings.default_stock
				INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
				WHERE
					erp_gl_charts.accountcode = erp_account_settings.default_stock;	
			END IF;
		END IF;
	END IF;
END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `gl_trans_delivery_items_insert`;
DELIMITER ;;
CREATE TRIGGER `gl_trans_delivery_items_insert` AFTER INSERT ON `erp_delivery_items` FOR EACH ROW BEGIN

	DECLARE v_tran_no INTEGER;
	DECLARE v_tran_date DATETIME;
	DECLARE v_acc_cate_separate INTEGER;
	DECLARE v_reference_no VARCHAR(50);
	DECLARE v_customer VARCHAR(55);
	DECLARE v_biller_id INTEGER;
	DECLARE v_created_by INTEGER;
	DECLARE v_updated_by INTEGER;
	DECLARE v_category_id INTEGER;
	DECLARE v_qty_unit INTEGER;
	DECLARE v_delivery_status VARCHAR(50);
	
	SET v_acc_cate_separate = (SELECT erp_settings.acc_cate_separate FROM erp_settings WHERE erp_settings.setting_id = '1');
	SET v_delivery_status = (SELECT erp_deliveries.delivery_status FROM erp_deliveries WHERE erp_deliveries.id = NEW.delivery_id);
	
	IF v_delivery_status = "completed" THEN
		SET v_tran_date = (
					SELECT
						erp_deliveries.date
					FROM
						erp_deliveries
					WHERE
						erp_deliveries.id = NEW.delivery_id
					LIMIT 1
				);
				
		SET v_reference_no = (SELECT erp_deliveries.do_reference_no FROM erp_deliveries WHERE erp_deliveries.id = NEW.delivery_id);
		SET v_tran_no = (SELECT erp_gl_trans.tran_no FROM erp_gl_trans WHERE erp_gl_trans.tran_type = "DELIVERY" AND reference_no = v_reference_no LIMIT 1);
		IF ISNULL(v_tran_no) THEN
			SET v_tran_no = (
				SELECT
					COALESCE (MAX(tran_no), 0) + 1
				FROM
					erp_gl_trans
			);
			UPDATE erp_order_ref
			SET tr = v_tran_no
			WHERE
				DATE_FORMAT(date, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m');
		END IF;
		SET v_customer = (SELECT erp_deliveries.customer FROM erp_deliveries WHERE erp_deliveries.id = NEW.delivery_id);
		SET v_biller_id = (SELECT erp_deliveries.biller_id FROM erp_deliveries WHERE erp_deliveries.id = NEW.delivery_id);
		SET v_created_by = (SELECT erp_deliveries.created_by FROM erp_deliveries WHERE erp_deliveries.id = NEW.delivery_id);
		SET v_updated_by = (SELECT erp_deliveries.updated_by FROM erp_deliveries WHERE erp_deliveries.id = NEW.delivery_id);
		SET v_category_id = (SELECT erp_products.category_id FROM erp_products WHERE erp_products.id = NEW.product_id);
		
		IF NEW.option_id AND NEW.product_id THEN
			SET v_qty_unit = (SELECT erp_product_variants.qty_unit FROM erp_product_variants WHERE erp_product_variants.id = NEW.option_id AND erp_product_variants.product_id = NEW.product_id);
		ELSE
			SET v_qty_unit = 1;
		END IF;
		
		IF v_acc_cate_separate = 1 THEN
			INSERT INTO erp_gl_trans (
				tran_type,
				tran_no,
				tran_date,
				sectionid,
				account_code,
				narrative,
				amount,
				reference_no,
				description,
				biller_id,
				created_by,
				updated_by
			) SELECT
				'DELIVERY',
				v_tran_no,
				v_tran_date,
				erp_gl_sections.sectionid,
				erp_gl_charts.accountcode,
				erp_gl_charts.accountname,
				(NEW.cost * NEW.quantity_received),
				v_reference_no,
				v_customer,
				v_biller_id,
				v_created_by,
				v_updated_by
			FROM
				erp_categories
				INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_categories.ac_cost
				INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
			WHERE
				erp_categories.id = v_category_id;


			INSERT INTO erp_gl_trans (
				tran_type,
				tran_no,
				tran_date,
				sectionid,
				account_code,
				narrative,
				amount,
				reference_no,
				description,
				biller_id,
				created_by,
				updated_by
			) SELECT
				'DELIVERY',
				v_tran_no,
				v_tran_date,
				erp_gl_sections.sectionid,
				erp_gl_charts.accountcode,
				erp_gl_charts.accountname,
				(- 1) * (NEW.cost * NEW.quantity_received),
				v_reference_no,
				v_customer,
				v_biller_id,
				v_created_by,
				v_updated_by
			FROM
				erp_categories
				INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_categories.ac_stock
				INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
			WHERE
				erp_categories.id = v_category_id;
		END IF;
		
	END IF;

END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `gl_trans_delivery_items_update`;
DELIMITER ;;
CREATE TRIGGER `gl_trans_delivery_items_update` AFTER UPDATE ON `erp_delivery_items` FOR EACH ROW BEGIN

	DECLARE v_tran_no INTEGER;
	DECLARE v_tran_date DATETIME;
	DECLARE v_acc_cate_separate INTEGER;
	DECLARE v_reference_no VARCHAR(50);
	DECLARE v_customer VARCHAR(55);
	DECLARE v_biller_id INTEGER;
	DECLARE v_category_id INTEGER;
	DECLARE v_qty_unit INTEGER;
	DECLARE v_delivery_status VARCHAR(50);
	
	IF NEW.updated_count <> OLD.updated_count AND NEW.updated_by > 0 THEN
		
		SET v_acc_cate_separate = (SELECT erp_settings.acc_cate_separate FROM erp_settings WHERE erp_settings.setting_id = '1');
		SET v_delivery_status = (SELECT erp_deliveries.delivery_status FROM erp_deliveries WHERE erp_deliveries.id = NEW.delivery_id);
		
		IF v_delivery_status = "completed" THEN
			SET v_tran_date = (
						SELECT
							erp_deliveries.date
						FROM
							erp_deliveries
						WHERE
							erp_deliveries.id = NEW.delivery_id
						LIMIT 1
					);
					
			SET v_reference_no = (SELECT erp_deliveries.do_reference_no FROM erp_deliveries WHERE erp_deliveries.id = NEW.delivery_id);
			SET v_tran_no = (SELECT erp_gl_trans.tran_no FROM erp_gl_trans WHERE erp_gl_trans.tran_type = "DELIVERY" AND reference_no = v_reference_no LIMIT 1);
			IF ISNULL(v_tran_no) THEN
				SET v_tran_no = (
					SELECT
						COALESCE (MAX(tran_no), 0) + 1
					FROM
						erp_gl_trans
				);
				UPDATE erp_order_ref
				SET tr = v_tran_no
				WHERE
					DATE_FORMAT(date, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m');
			END IF;
			SET v_customer = (SELECT erp_deliveries.customer FROM erp_deliveries WHERE erp_deliveries.id = NEW.delivery_id);
			SET v_biller_id = (SELECT erp_deliveries.biller_id FROM erp_deliveries WHERE erp_deliveries.id = NEW.delivery_id);
			SET v_category_id = (SELECT erp_products.category_id FROM erp_products WHERE erp_products.id = NEW.product_id);
			
			IF NEW.option_id AND NEW.product_id THEN
				SET v_qty_unit = (SELECT erp_product_variants.qty_unit FROM erp_product_variants WHERE erp_product_variants.id = NEW.option_id AND erp_product_variants.product_id = NEW.product_id);
			ELSE
				SET v_qty_unit = 1;
			END IF;
		
			IF v_acc_cate_separate = 1 THEN
				INSERT INTO erp_gl_trans (
					tran_type,
					tran_no,
					tran_date,
					sectionid,
					account_code,
					narrative,
					amount,
					reference_no,
					description,
					biller_id,
					created_by,
					updated_by
				) SELECT
					'DELIVERY',
					v_tran_no,
					v_tran_date,
					erp_gl_sections.sectionid,
					erp_gl_charts.accountcode,
					erp_gl_charts.accountname,
					(NEW.cost * NEW.quantity_received),
					v_reference_no,
					v_customer,
					v_biller_id,
					NEW.created_by,
					NEW.updated_by
				FROM
					erp_categories
					INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_categories.ac_cost
					INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
				WHERE
					erp_categories.id = v_category_id;

				INSERT INTO erp_gl_trans (
					tran_type,
					tran_no,
					tran_date,
					sectionid,
					account_code,
					narrative,
					amount,
					reference_no,
					description,
					biller_id,
					created_by,
					updated_by
				) SELECT
					'DELIVERY',
					v_tran_no,
					v_tran_date,
					erp_gl_sections.sectionid,
					erp_gl_charts.accountcode,
					erp_gl_charts.accountname,
					(- 1) * (NEW.cost * NEW.quantity_received),
					v_reference_no,
					v_customer,
					v_biller_id,
					NEW.created_by,
					NEW.updated_by
				FROM
					erp_categories
					INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_categories.ac_stock
					INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
				WHERE
					erp_categories.id = v_category_id;
			END IF;
		END IF;
	END IF;

END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `gl_trans_salary_tax_small_insert`;
DELIMITER ;;
CREATE TRIGGER `gl_trans_salary_tax_small_insert` AFTER INSERT ON `erp_employee_salary_tax_small_taxpayers_trigger` FOR EACH ROW BEGIN
DECLARE
	v_tran_no INTEGER;
DECLARE
	v_default_payroll INTEGER;
DECLARE
	v_default_salary_tax_payable INTEGER;
DECLARE
	v_default_salary_expense INTEGER;
DECLARE
	v_default_tax_duties_expense INTEGER;
DECLARE
	v_bank_code INTEGER;
DECLARE
	v_account_code INTEGER;
DECLARE
	v_tran_date DATETIME;
DECLARE
	v_biller_id INTEGER;
DECLARE v_date DATE;

SET v_biller_id = (
                   SELECT default_biller FROM erp_settings
);


IF v_tran_date = DATE_FORMAT(NEW.year_month, '%Y-%m') THEN

SET v_tran_no = (
	SELECT
		MAX(tran_no)
	FROM
		erp_gl_trans
);

ELSE

SET v_tran_no = (
	SELECT
		COALESCE (MAX(tran_no), 0) + 1
	FROM
		erp_gl_trans
);


UPDATE erp_order_ref
SET tr = v_tran_no
WHERE
	DATE_FORMAT(date, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m');
END
IF;

SET v_default_payroll = (
	SELECT
		default_payroll
	FROM
		erp_account_settings
);

SET v_default_salary_tax_payable = (
	SELECT
		default_salary_tax_payable
	FROM
		erp_account_settings
);
SET v_default_tax_duties_expense = (
	SELECT
		default_tax_duties_expense
	FROM
		erp_account_settings
);
SET v_default_salary_expense = (
	SELECT
		default_salary_expense
	FROM
		erp_account_settings
);


/* ==== Employee Salary Tax =====*/
IF NEW.isCompany = 0 THEN
	INSERT INTO erp_gl_trans (
		tran_type,
		tran_no,
		tran_date,
		sectionid,
		account_code,
		narrative,
		amount,
		reference_no,
		description,
		biller_id,
		created_by,
		bank,
		updated_by
	) SELECT
		'SALARY TAX',
		v_tran_no,
		DATE_FORMAT(CONCAT(NEW.year_month,'-', DAy(CURRENT_DATE())), '%Y-%m-%d'),
		erp_gl_sections.sectionid,
		erp_gl_charts.accountcode,
		erp_gl_charts.accountname,
		(- 1) * abs(NEW.total_salary_usd),
		NEW.reference_no,
        'Employee Salary Tax',
		v_biller_id,
		NEW.created_by,
		'1',
		NEW.updated_by
	FROM
		erp_account_settings
	INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = v_default_payroll 
	INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
	WHERE
		erp_gl_charts.accountcode =v_default_payroll;

INSERT INTO erp_gl_trans (
	tran_type,
	tran_no,
	tran_date,
	sectionid,
	account_code,
	narrative,
	amount,
	reference_no,
	description,
	biller_id,
	created_by,
	bank,
	updated_by
) SELECT
	'SALARY TAX',
	v_tran_no,
	DATE_FORMAT(CONCAT(NEW.year_month,'-', DAy(CURRENT_DATE())), '%Y-%m-%d'),
	erp_gl_sections.sectionid,
	erp_gl_charts.accountcode,
	erp_gl_charts.accountname,
	(- 1) * abs(NEW.total_salary_tax_usd),
	NEW.reference_no,
	'Employee Salary Tax',
	v_biller_id,
	NEW.created_by,
	'1',
	NEW.updated_by
FROM
	erp_account_settings
INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = v_default_salary_tax_payable
INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
WHERE
	erp_gl_charts.accountcode =v_default_salary_tax_payable;


INSERT INTO erp_gl_trans (
	tran_type,
	tran_no,
	tran_date,
	sectionid,
	account_code,
	narrative,
	amount,
	reference_no,
	description,
	biller_id,
	created_by,
	bank,
	updated_by
) SELECT
	'SALARY TAX',
	v_tran_no,
	DATE_FORMAT(CONCAT(NEW.year_month,'-', DAy(CURRENT_DATE())), '%Y-%m-%d'),
	erp_gl_sections.sectionid,
	erp_gl_charts.accountcode,
	erp_gl_charts.accountname,
	NEW.total_salary_tax_usd,
	NEW.reference_no,
	'Employee Salary Tax',
	v_biller_id,
	NEW.created_by,
	'1',
	NEW.updated_by
FROM
	erp_account_settings
INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = v_default_tax_duties_expense
INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
WHERE
	erp_gl_charts.accountcode =v_default_tax_duties_expense ;


INSERT INTO erp_gl_trans (
	tran_type,
	tran_no,
	tran_date,
	sectionid,
	account_code,
	narrative,
	amount,
	reference_no,
	description,
	biller_id,
	created_by,
	bank,
	updated_by
) SELECT
	'SALARY TAX',
	v_tran_no,
	DATE_FORMAT(CONCAT(NEW.year_month,'-', DAy(CURRENT_DATE())), '%Y-%m-%d'),
	erp_gl_sections.sectionid,
	erp_gl_charts.accountcode,
	erp_gl_charts.accountname,
	NEW.total_salary_usd,
	NEW.reference_no,
	'Employee Salary Tax',
	v_biller_id,
	NEW.created_by,
	'1',
	NEW.updated_by
FROM
	erp_account_settings
INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = v_default_salary_expense
INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
WHERE
	erp_gl_charts.accountcode =v_default_salary_expense;

ELSE

INSERT INTO erp_gl_trans (
	tran_type,
	tran_no,
	tran_date,
	sectionid,
	account_code,
	narrative,
	amount,
	reference_no,
	description,
	biller_id,
	created_by,
	bank,
	updated_by
) SELECT
	'SALARY TAX',
	v_tran_no,
	DATE_FORMAT(CONCAT(NEW.year_month,'-', DAy(CURRENT_DATE())), '%Y-%m-%d'),

	erp_gl_sections.sectionid,
	erp_gl_charts.accountcode,
	erp_gl_charts.accountname,
	NEW.total_salary_usd,
	NEW.reference_no,
	'Employee Salary Tax',
	v_biller_id,
	NEW.created_by,
	'1',
	NEW.updated_by
FROM
	erp_account_settings
INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = v_default_salary_expense
INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
WHERE
	erp_gl_charts.accountcode =v_default_salary_expense ;

	INSERT INTO erp_gl_trans (
		tran_type,
		tran_no,
		tran_date,
		sectionid,
		account_code,
		narrative,
		amount,
		reference_no,
		description,
		biller_id,
		created_by,
		bank,
		updated_by
	) SELECT
		'SALARY TAX',
		v_tran_no,
		DATE_FORMAT(CONCAT(NEW.year_month,'-', DAY(CURRENT_DATE())), '%Y-%m-%d'),
		erp_gl_sections.sectionid,
		erp_gl_charts.accountcode,
		erp_gl_charts.accountname,
		(- 1) * (NEW.total_salary_usd - NEW.total_salary_tax_usd),
		NEW.reference_no,
        'Employee Salary Tax',
		v_biller_id,
		NEW.created_by,
		'1',
		NEW.updated_by
	FROM
		erp_account_settings
	INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = v_default_payroll 
	INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
	WHERE
		erp_gl_charts.accountcode =v_default_payroll;

INSERT INTO erp_gl_trans (
	tran_type,
	tran_no,
	tran_date,
	sectionid,
	account_code,
	narrative,
	amount,
	reference_no,
	description,
	biller_id,
	created_by,
	bank,
	updated_by
) SELECT
	'SALARY TAX',
	v_tran_no,
	DATE_FORMAT(CONCAT(NEW.year_month,'-', DAy(CURRENT_DATE())), '%Y-%m-%d'),
	erp_gl_sections.sectionid,
	erp_gl_charts.accountcode,
	erp_gl_charts.accountname,
	(- 1) * (NEW.total_salary_tax_usd),
	NEW.reference_no,
	'Employee Salary Tax',
	v_biller_id,
	NEW.created_by,
	'1',
	NEW.updated_by
FROM
	erp_account_settings
INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = v_default_salary_tax_payable
INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
WHERE
	erp_gl_charts.accountcode =v_default_salary_tax_payable;

END
IF;

END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `gl_trans_salary_tax_small_update`;
DELIMITER ;;
CREATE TRIGGER `gl_trans_salary_tax_small_update` AFTER UPDATE ON `erp_employee_salary_tax_small_taxpayers_trigger` FOR EACH ROW BEGIN
DECLARE
	v_tran_no INTEGER;
DECLARE
	v_default_payroll INTEGER;
DECLARE
	v_default_salary_tax_payable INTEGER;
DECLARE
	v_default_salary_expense INTEGER;
DECLARE
	v_default_tax_duties_expense INTEGER;
DECLARE
	v_bank_code INTEGER;
DECLARE
	v_account_code INTEGER;
DECLARE
	v_tran_date DATETIME;
DECLARE
	v_biller_id INTEGER;

SET v_tran_no = (SELECT tran_no FROM erp_gl_trans WHERE tran_type='SALARY TAX' AND reference_no = NEW.reference_no LIMIT 0,1); 

DELETE FROM erp_gl_trans WHERE tran_type='SALARY TAX' AND reference_no = NEW.reference_no;

SET v_biller_id = (
                   SELECT default_biller FROM erp_settings
);

SET v_default_payroll = (
	SELECT
		default_payroll
	FROM
		erp_account_settings
);

SET v_default_salary_tax_payable = (
	SELECT
		default_salary_tax_payable
	FROM
		erp_account_settings
);
SET v_default_tax_duties_expense = (
	SELECT
		default_tax_duties_expense
	FROM
		erp_account_settings
);
SET v_default_salary_expense = (
	SELECT
		default_salary_expense
	FROM
		erp_account_settings
);

/* ==== Employee Salary Tax =====*/
IF NEW.isCompany = 0 THEN
	INSERT INTO erp_gl_trans (
		tran_type,
		tran_no,
		tran_date,
		sectionid,
		account_code,
		narrative,
		amount,
		reference_no,
		description,
		biller_id,
		created_by,
		bank,
		updated_by
	) SELECT
		'SALARY TAX',
		v_tran_no,
		DATE_FORMAT(CONCAT(NEW.year_month,'-', DAy(CURRENT_DATE())), '%Y-%m-%d'),
		erp_gl_sections.sectionid,
		erp_gl_charts.accountcode,
		erp_gl_charts.accountname,
		(- 1) * abs(NEW.total_salary_usd),
		NEW.reference_no,
        'Employee Salary Tax',
		v_biller_id,
		NEW.created_by,
		'1',
		NEW.updated_by
	FROM
		erp_account_settings
	INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = v_default_payroll 
	INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
	WHERE
		erp_gl_charts.accountcode =v_default_payroll;

INSERT INTO erp_gl_trans (
	tran_type,
	tran_no,
	tran_date,
	sectionid,
	account_code,
	narrative,
	amount,
	reference_no,
	description,
	biller_id,
	created_by,
	bank,
	updated_by
) SELECT
	'SALARY TAX',
	v_tran_no,
	DATE_FORMAT(CONCAT(NEW.year_month,'-', DAy(CURRENT_DATE())), '%Y-%m-%d'),
	erp_gl_sections.sectionid,
	erp_gl_charts.accountcode,
	erp_gl_charts.accountname,
	(- 1) * abs(NEW.total_salary_tax_usd),
	NEW.reference_no,
	'Employee Salary Tax',
	v_biller_id,
	NEW.created_by,
	'1',
	NEW.updated_by
FROM
	erp_account_settings
INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = v_default_salary_tax_payable
INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
WHERE
	erp_gl_charts.accountcode =v_default_salary_tax_payable;


INSERT INTO erp_gl_trans (
	tran_type,
	tran_no,
	tran_date,
	sectionid,
	account_code,
	narrative,
	amount,
	reference_no,
	description,
	biller_id,
	created_by,
	bank,
	updated_by
) SELECT
	'SALARY TAX',
	v_tran_no,
	DATE_FORMAT(CONCAT(NEW.year_month,'-', DAy(CURRENT_DATE())), '%Y-%m-%d'),
	erp_gl_sections.sectionid,
	erp_gl_charts.accountcode,
	erp_gl_charts.accountname,
	NEW.total_salary_tax_usd,
	NEW.reference_no,
	'Employee Salary Tax',
	v_biller_id,
	NEW.created_by,
	'1',
	NEW.updated_by
FROM
	erp_account_settings
INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = v_default_tax_duties_expense
INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
WHERE
	erp_gl_charts.accountcode =v_default_tax_duties_expense ;


INSERT INTO erp_gl_trans (
	tran_type,
	tran_no,
	tran_date,
	sectionid,
	account_code,
	narrative,
	amount,
	reference_no,
	description,
	biller_id,
	created_by,
	bank,
	updated_by
) SELECT
	'SALARY TAX',
	v_tran_no,
	DATE_FORMAT(CONCAT(NEW.year_month,'-', DAy(CURRENT_DATE())), '%Y-%m-%d'),
	erp_gl_sections.sectionid,
	erp_gl_charts.accountcode,
	erp_gl_charts.accountname,
	NEW.total_salary_usd,
	NEW.reference_no,
	'Employee Salary Tax',
	v_biller_id,
	NEW.created_by,
	'1',
	NEW.updated_by
FROM
	erp_account_settings
INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = v_default_salary_expense
INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
WHERE
	erp_gl_charts.accountcode =v_default_salary_expense ;


ELSE

INSERT INTO erp_gl_trans (
	tran_type,
	tran_no,
	tran_date,
	sectionid,
	account_code,
	narrative,
	amount,
	reference_no,
	description,
	biller_id,
	created_by,
	bank,
	updated_by
) SELECT
	'SALARY TAX',
	v_tran_no,
	DATE_FORMAT(CONCAT(NEW.year_month,'-', DAy(CURRENT_DATE())), '%Y-%m-%d'),
	erp_gl_sections.sectionid,

	erp_gl_charts.accountcode,
	erp_gl_charts.accountname,
	NEW.total_salary_usd,
	NEW.reference_no,
	'Employee Salary Tax',
	v_biller_id,
	NEW.created_by,
	'1',
	NEW.updated_by
FROM
	erp_account_settings
INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = v_default_salary_expense
INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
WHERE
	erp_gl_charts.accountcode =v_default_salary_expense ;

	INSERT INTO erp_gl_trans (
		tran_type,
		tran_no,
		tran_date,
		sectionid,
		account_code,
		narrative,
		amount,
		reference_no,
		description,
		biller_id,
		created_by,
		bank,
		updated_by
	) SELECT
		'SALARY TAX',
		v_tran_no,
		DATE_FORMAT(CONCAT(NEW.year_month,'-', DAy(CURRENT_DATE())), '%Y-%m-%d'),
		erp_gl_sections.sectionid,
		erp_gl_charts.accountcode,
		erp_gl_charts.accountname,
		(- 1) * (NEW.total_salary_usd - NEW.total_salary_tax_usd),
		NEW.reference_no,
                                     'Employee Salary Tax',
		v_biller_id,
		NEW.created_by,
		'1',
		NEW.updated_by
	FROM
		erp_account_settings
	INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = v_default_payroll 
	INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
	WHERE
		erp_gl_charts.accountcode =v_default_payroll;

INSERT INTO erp_gl_trans (
	tran_type,
	tran_no,
	tran_date,
	sectionid,
	account_code,
	narrative,
	amount,
	reference_no,
	description,
	biller_id,
	created_by,
	bank,
	updated_by
) SELECT
	'SALARY TAX',
	v_tran_no,
	DATE_FORMAT(CONCAT(NEW.year_month,'-', DAy(CURRENT_DATE())), '%Y-%m-%d'),
	erp_gl_sections.sectionid,
	erp_gl_charts.accountcode,
	erp_gl_charts.accountname,
	(- 1) * (NEW.total_salary_tax_usd),
	NEW.reference_no,
	'Employee Salary Tax',
	v_biller_id,
	NEW.created_by,
	'1',
	NEW.updated_by
FROM
	erp_account_settings
INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = v_default_salary_tax_payable
INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
WHERE
	erp_gl_charts.accountcode =v_default_salary_tax_payable;

END
IF;


END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `gl_trans_salary_tax_insert`;
DELIMITER ;;
CREATE TRIGGER `gl_trans_salary_tax_insert` AFTER INSERT ON `erp_employee_salary_tax_trigger` FOR EACH ROW BEGIN
DECLARE
	v_tran_no INTEGER;
DECLARE
	v_default_fringe_benefit_tax INTEGER;	
DECLARE
	v_default_payroll INTEGER;
DECLARE
	v_default_salary_tax_payable INTEGER;
DECLARE
	v_default_salary_expense INTEGER;
DECLARE
	v_default_tax_duties_expense INTEGER;
DECLARE
	v_bank_code INTEGER;
DECLARE
	v_account_code INTEGER;
DECLARE
	v_tran_date DATETIME;
DECLARE
	v_biller_id INTEGER;
DECLARE v_date DATE;

SET v_biller_id = (
                   SELECT default_biller FROM erp_settings
);


IF v_tran_date = DATE_FORMAT(NEW.year_month, '%Y-%m') THEN

SET v_tran_no = (
	SELECT
		MAX(tran_no)
	FROM
		erp_gl_trans
);

ELSE

SET v_tran_no = (
	SELECT
		COALESCE (MAX(tran_no), 0) + 1
	FROM
		erp_gl_trans

);


UPDATE erp_order_ref
SET tr = v_tran_no
WHERE
	DATE_FORMAT(date, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m');
END
IF;

SET v_default_payroll = (
	SELECT
		default_payroll
	FROM
		erp_account_settings
);
SET v_default_fringe_benefit_tax = (
	SELECT
		default_fringe_benefit_tax
	FROM
		erp_account_settings
);
SET v_default_salary_tax_payable = (
	SELECT
		default_salary_tax_payable
	FROM
		erp_account_settings
);
SET v_default_tax_duties_expense = (
	SELECT
		default_tax_duties_expense
	FROM
		erp_account_settings
);
SET v_default_salary_expense = (
	SELECT
		default_salary_expense
	FROM
		erp_account_settings
);


/* ==== Employee Salary Tax =====*/
/* ==== Tab 1 =====*/

IF NEW.tab = 1 OR NEW.tab=2 THEN

IF NEW.isCompany = 0 THEN
	INSERT INTO erp_gl_trans (
		tran_type,
		tran_no,
		tran_date,
		sectionid,
		account_code,
		narrative,
		amount,
		reference_no,
		description,
		biller_id,
		created_by,
		bank,
		updated_by
	) SELECT
		'SALARY TAX',
		v_tran_no,
        NEW.date,
		erp_gl_sections.sectionid,
		erp_gl_charts.accountcode,
		erp_gl_charts.accountname,
		(- 1) * abs(NEW.total_salary_usd),
		NEW.reference_no,
        'Employee Salary Tax',
		v_biller_id,
		NEW.created_by,
		'1',
		NEW.updated_by
	FROM
		erp_account_settings
	INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = v_default_payroll 
	INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
	WHERE
		erp_gl_charts.accountcode =v_default_payroll;

INSERT INTO erp_gl_trans (
	tran_type,
	tran_no,
	tran_date,
	sectionid,
	account_code,
	narrative,
	amount,
	reference_no,
	description,
	biller_id,
	created_by,
	bank,
	updated_by
) SELECT
	'SALARY TAX',
	v_tran_no,
	NEW.date,
	erp_gl_sections.sectionid,
	erp_gl_charts.accountcode,
	erp_gl_charts.accountname,
	(- 1) * abs(NEW.total_salary_tax_usd),
	NEW.reference_no,
	'Employee Salary Tax',
	v_biller_id,
	NEW.created_by,
	'1',
	NEW.updated_by
FROM
	erp_account_settings
INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = v_default_salary_tax_payable
INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
WHERE
	erp_gl_charts.accountcode =v_default_salary_tax_payable;


INSERT INTO erp_gl_trans (
	tran_type,
	tran_no,
	tran_date,
	sectionid,
	account_code,
	narrative,
	amount,
	reference_no,
	description,
	biller_id,
	created_by,
	bank,
	updated_by
) SELECT
	'SALARY TAX',
	v_tran_no,
	NEW.date,
	erp_gl_sections.sectionid,
	erp_gl_charts.accountcode,
	erp_gl_charts.accountname,
	NEW.total_salary_tax_usd,
	NEW.reference_no,
	'Employee Salary Tax',
	v_biller_id,
	NEW.created_by,
	'1',
	NEW.updated_by
FROM
	erp_account_settings
INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = v_default_tax_duties_expense
INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
WHERE
	erp_gl_charts.accountcode =v_default_tax_duties_expense ;


INSERT INTO erp_gl_trans (
	tran_type,
	tran_no,
	tran_date,
	sectionid,
	account_code,
	narrative,
	amount,
	reference_no,
	description,
	biller_id,
	created_by,
	bank,
	updated_by
) SELECT
	'SALARY TAX',
	v_tran_no,
	NEW.date,
	erp_gl_sections.sectionid,
	erp_gl_charts.accountcode,
	erp_gl_charts.accountname,
	NEW.total_salary_usd,
	NEW.reference_no,
	'Employee Salary Tax',
	v_biller_id,
	NEW.created_by,
	'1',
	NEW.updated_by
FROM
	erp_account_settings
INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = v_default_salary_expense
INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
WHERE
	erp_gl_charts.accountcode =v_default_salary_expense ;


ELSE

INSERT INTO erp_gl_trans (
	tran_type,
	tran_no,
	tran_date,
	sectionid,
	account_code,
	narrative,
	amount,
	reference_no,
	description,
	biller_id,
	created_by,
	bank,
	updated_by
) SELECT
	'SALARY TAX',
	v_tran_no,
	NEW.date,
	erp_gl_sections.sectionid,
	erp_gl_charts.accountcode,
	erp_gl_charts.accountname,
	NEW.total_salary_usd,
	NEW.reference_no,
	'Employee Salary Tax',
	v_biller_id,
	NEW.created_by,
	'1',
	NEW.updated_by
FROM
	erp_account_settings
INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = v_default_salary_expense
INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
WHERE
	erp_gl_charts.accountcode =v_default_salary_expense ;

	INSERT INTO erp_gl_trans (
		tran_type,
		tran_no,
		tran_date,
		sectionid,
		account_code,
		narrative,
		amount,
		reference_no,
		description,
		biller_id,
		created_by,
		bank,
		updated_by
	) SELECT
		'SALARY TAX',
		v_tran_no,
		NEW.date,
		erp_gl_sections.sectionid,
		erp_gl_charts.accountcode,
		erp_gl_charts.accountname,
		(- 1) * (NEW.total_salary_usd - NEW.total_salary_tax_usd),
		NEW.reference_no,
		'Employee Salary Tax',
		v_biller_id,
		NEW.created_by,
		'1',
		NEW.updated_by
	FROM
		erp_account_settings
	INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = v_default_payroll 
	INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
	WHERE
		erp_gl_charts.accountcode =v_default_payroll;

INSERT INTO erp_gl_trans (
	tran_type,
	tran_no,
	tran_date,
	sectionid,
	account_code,
	narrative,
	amount,
	reference_no,
	description,
	biller_id,
	created_by,
	bank,
	updated_by
) SELECT
	'SALARY TAX',
	v_tran_no,
	NEW.date,
	erp_gl_sections.sectionid,
	erp_gl_charts.accountcode,
	erp_gl_charts.accountname,
	 (- 1) * (NEW.total_salary_tax_usd),
	NEW.reference_no,
	'Employee Salary Tax',
	v_biller_id,
	NEW.created_by,
	'1',
	NEW.updated_by
FROM
	erp_account_settings
INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = v_default_salary_tax_payable
INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
WHERE
	erp_gl_charts.accountcode =v_default_salary_tax_payable;

END IF;
END IF;
/* ==== END Tab 1 =====*/
IF NEW.tab =3 THEN

INSERT INTO erp_gl_trans (
		tran_type,
		tran_no,
		tran_date,
		sectionid,
		account_code,
		narrative,
		amount,
		reference_no,
		description,
		biller_id,
		created_by,
		bank,
		updated_by
	) SELECT
		'Fringe Benefit',
		v_tran_no,
		NEW.date,
		erp_gl_sections.sectionid,
		erp_gl_charts.accountcode,
		erp_gl_charts.accountname,
		 abs(NEW.total_allowance_tax*0.2),
		NEW.reference_no_j,
		'Employee Salary Tax',
		v_biller_id,
		NEW.created_by,
		'1',
		NEW.updated_by
	FROM
		erp_account_settings
	INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = v_default_tax_duties_expense 
	INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
	WHERE
		erp_gl_charts.accountcode =v_default_tax_duties_expense;



INSERT INTO erp_gl_trans (
		tran_type,
		tran_no,
		tran_date,
		sectionid,
		account_code,
		narrative,
		amount,
		reference_no,
		description,

		biller_id,
		created_by,
		bank,
		updated_by
	) SELECT
		'Fringe Benefit',
		v_tran_no,
		NEW.date,
		erp_gl_sections.sectionid,
		erp_gl_charts.accountcode,
		erp_gl_charts.accountname,
		(- 1) * abs(NEW.total_allowance_tax*0.2),
		NEW.reference_no_j,
		'Employee Salary Tax',
		v_biller_id,
		NEW.created_by,
		'1',
		NEW.updated_by
	FROM
		erp_account_settings
	INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = v_default_fringe_benefit_tax 
	INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
	WHERE
		erp_gl_charts.accountcode =v_default_fringe_benefit_tax;

END IF;

END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `gl_trans_salary_tax_update`;
DELIMITER ;;
CREATE TRIGGER `gl_trans_salary_tax_update` AFTER UPDATE ON `erp_employee_salary_tax_trigger` FOR EACH ROW BEGIN
DECLARE
	v_tran_no INTEGER;
DECLARE
	v_default_fringe_benefit_tax INTEGER;
DECLARE
	v_default_payroll INTEGER;
DECLARE
	v_default_salary_tax_payable INTEGER;
DECLARE
	v_default_salary_expense INTEGER;
DECLARE
	v_default_tax_duties_expense INTEGER;
DECLARE
	v_bank_code INTEGER;
DECLARE
	v_account_code INTEGER;
DECLARE
	v_tran_date DATETIME;
DECLARE
	v_biller_id INTEGER;
DECLARE
	v_tran_no_b INTEGER;

SET v_tran_no = (SELECT tran_no FROM erp_gl_trans WHERE tran_type='SALARY TAX' AND reference_no = NEW.reference_no LIMIT 0,1); 
SET v_tran_no_b = (SELECT tran_no FROM erp_gl_trans WHERE tran_type='Fringe Benefit' AND reference_no = NEW.reference_no_j LIMIT 0,1); 


DELETE FROM erp_gl_trans WHERE tran_type='SALARY TAX' AND reference_no = NEW.reference_no;

SET v_biller_id = (
                   SELECT default_biller FROM erp_settings
);


SET v_default_payroll = (
	SELECT
		default_payroll
	FROM
		erp_account_settings
);
SET v_default_fringe_benefit_tax = (
	SELECT
		default_fringe_benefit_tax
	FROM
		erp_account_settings
);
SET v_default_salary_tax_payable = (
	SELECT
		default_salary_tax_payable
	FROM
		erp_account_settings
);
SET v_default_tax_duties_expense = (
	SELECT
		default_tax_duties_expense
	FROM
		erp_account_settings
);
SET v_default_salary_expense = (
	SELECT
		default_salary_expense
	FROM
		erp_account_settings
);

/* ==== Employee Salary Tax =====*/
/* ==== Tab 1 =====*/

IF NEW.tab = 1 OR NEW.tab=2 THEN

IF NEW.isCompany = 0 THEN
	INSERT INTO erp_gl_trans (
		tran_type,
		tran_no,
		tran_date,
		sectionid,
		account_code,
		narrative,
		amount,
		reference_no,
		description,
		biller_id,
		created_by,
		bank,
		updated_by
	) SELECT
		'SALARY TAX',
		v_tran_no,
		NEW.date,
		erp_gl_sections.sectionid,
		erp_gl_charts.accountcode,
		erp_gl_charts.accountname,
		(- 1) * abs(NEW.total_salary_usd),
		NEW.reference_no,
		'Employee Salary Tax',
		v_biller_id,
		NEW.created_by,
		'1',
		NEW.updated_by
	FROM
		erp_account_settings
	INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = v_default_payroll 
	INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
	WHERE
		erp_gl_charts.accountcode =v_default_payroll;

INSERT INTO erp_gl_trans (
	tran_type,
	tran_no,
	tran_date,
	sectionid,
	account_code,
	narrative,
	amount,
	reference_no,
	description,
	biller_id,
	created_by,
	bank,
	updated_by
) SELECT
	'SALARY TAX',
	v_tran_no,
	NEW.date,
	erp_gl_sections.sectionid,
	erp_gl_charts.accountcode,
	erp_gl_charts.accountname,
	(- 1) * abs(NEW.total_salary_tax_usd),
	NEW.reference_no,
	'Employee Salary Tax',
	v_biller_id,
	NEW.created_by,
	'1',
	NEW.updated_by
FROM
	erp_account_settings
INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = v_default_salary_tax_payable
INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
WHERE
	erp_gl_charts.accountcode =v_default_salary_tax_payable;


INSERT INTO erp_gl_trans (
	tran_type,
	tran_no,
	tran_date,
	sectionid,
	account_code,
	narrative,
	amount,
	reference_no,
	description,
	biller_id,
	created_by,
	bank,
	updated_by
) SELECT
	'SALARY TAX',
	v_tran_no,
	NEW.date,
	erp_gl_sections.sectionid,
	erp_gl_charts.accountcode,
	erp_gl_charts.accountname,
	NEW.total_salary_tax_usd,
	NEW.reference_no,
	'Employee Salary Tax',
	v_biller_id,
	NEW.created_by,
	'1',
	NEW.updated_by
FROM
	erp_account_settings
INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = v_default_tax_duties_expense
INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
WHERE
	erp_gl_charts.accountcode =v_default_tax_duties_expense ;


INSERT INTO erp_gl_trans (
	tran_type,
	tran_no,
	tran_date,
	sectionid,
	account_code,
	narrative,
	amount,
	reference_no,
	description,
	biller_id,
	created_by,
	bank,
	updated_by
) SELECT
	'SALARY TAX',
	v_tran_no,
	NEW.date,
	erp_gl_sections.sectionid,
	erp_gl_charts.accountcode,
	erp_gl_charts.accountname,
	NEW.total_salary_usd,
	NEW.reference_no,
	'Employee Salary Tax',
	v_biller_id,
	NEW.created_by,
	'1',
	NEW.updated_by
FROM
	erp_account_settings
INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = v_default_salary_expense
INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
WHERE
	erp_gl_charts.accountcode =v_default_salary_expense ;


ELSE

INSERT INTO erp_gl_trans (
	tran_type,
	tran_no,
	tran_date,
	sectionid,
	account_code,
	narrative,
	amount,
	reference_no,
	description,
	biller_id,
	created_by,
	bank,
	updated_by
) SELECT
	'SALARY TAX',
	v_tran_no,
	NEW.date,
	erp_gl_sections.sectionid,
	erp_gl_charts.accountcode,
	erp_gl_charts.accountname,
	NEW.total_salary_usd,
	NEW.reference_no,
	'Employee Salary Tax',
	v_biller_id,
	NEW.created_by,
	'1',
	NEW.updated_by
FROM
	erp_account_settings
INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = v_default_salary_expense
INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
WHERE
	erp_gl_charts.accountcode =v_default_salary_expense ;

	INSERT INTO erp_gl_trans (
		tran_type,
		tran_no,
		tran_date,
		sectionid,
		account_code,
		narrative,
		amount,
		reference_no,
		description,
		biller_id,
		created_by,
		bank,
		updated_by
	) SELECT
		'SALARY TAX',
		v_tran_no,
		NEW.date,
		erp_gl_sections.sectionid,
		erp_gl_charts.accountcode,
		erp_gl_charts.accountname,
		(- 1) * (NEW.total_salary_usd - NEW.total_salary_tax_usd),
		NEW.reference_no,
		'Employee Salary Tax',
		v_biller_id,
		NEW.created_by,
		'1',
		NEW.updated_by
	FROM
		erp_account_settings
	INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = v_default_payroll 
	INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
	WHERE
		erp_gl_charts.accountcode =v_default_payroll;

INSERT INTO erp_gl_trans (
	tran_type,
	tran_no,
	tran_date,
	sectionid,
	account_code,
	narrative,
	amount,
	reference_no,
	description,

	biller_id,
	created_by,
	bank,
	updated_by
) SELECT
	'SALARY TAX',
	v_tran_no,
	NEW.date,
	erp_gl_sections.sectionid,
	erp_gl_charts.accountcode,
	erp_gl_charts.accountname,
	 (- 1) * (NEW.total_salary_tax_usd),
	NEW.reference_no,
	'Employee Salary Tax',
	v_biller_id,
	NEW.created_by,
	'1',
	NEW.updated_by
FROM
	erp_account_settings
INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = v_default_salary_tax_payable
INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
WHERE
	erp_gl_charts.accountcode =v_default_salary_tax_payable;

END IF;
END IF;
/* ==== END Tab 1 =====*/
IF NEW.tab =3 THEN

DELETE FROM erp_gl_trans WHERE tran_type='Fringe Benefit' AND reference_no = NEW.reference_no_j;


INSERT INTO erp_gl_trans (
		tran_type,
		tran_no,
		tran_date,
		sectionid,
		account_code,
		narrative,
		amount,
		reference_no,
		description,
		biller_id,
		created_by,
		bank,
		updated_by
	) SELECT
		'Fringe Benefit',
		v_tran_no_b,
                                     NEW.date,
		erp_gl_sections.sectionid,
		erp_gl_charts.accountcode,
		erp_gl_charts.accountname,
		 abs(NEW.total_allowance_tax*0.2),
		NEW.reference_no_j,
                                     'Employee Salary Tax',
		v_biller_id,
		NEW.created_by,
		'1',
		NEW.updated_by
	FROM
		erp_account_settings
	INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = v_default_tax_duties_expense 
	INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
	WHERE
		erp_gl_charts.accountcode =v_default_tax_duties_expense;



INSERT INTO erp_gl_trans (
		tran_type,
		tran_no,
		tran_date,
		sectionid,
		account_code,
		narrative,
		amount,
		reference_no,
		description,
		biller_id,
		created_by,
		bank,
		updated_by
	) SELECT
		'Fringe Benefit',
		v_tran_no_b,
		NEW.date,
		erp_gl_sections.sectionid,
		erp_gl_charts.accountcode,
		erp_gl_charts.accountname,
		(- 1) * abs(NEW.total_allowance_tax*0.2),
		NEW.reference_no_j,
		'Employee Salary Tax',
		v_biller_id,
		NEW.created_by,
		'1',
		NEW.updated_by
	FROM
		erp_account_settings
	INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = v_default_fringe_benefit_tax 
	INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
	WHERE
		erp_gl_charts.accountcode =v_default_fringe_benefit_tax;

END IF;

END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `gl_trans_enter_using_stock_insert`;
DELIMITER ;;
CREATE TRIGGER `gl_trans_enter_using_stock_insert` AFTER INSERT ON `erp_enter_using_stock` FOR EACH ROW BEGIN

	DECLARE e_tran_type VARCHAR (50);
	DECLARE e_tran_date DATETIME;
	DECLARE e_account_code INTEGER;
	DECLARE e_narrative VARCHAR (255);
	DECLARE v_tran_no INTEGER;
	DECLARE inv_account_code INTEGER;
	DECLARE inv_narrative VARCHAR (255);
	DECLARE cost_variant_account_code INTEGER;
	DECLARE cost_variant_narrative VARCHAR (255);
	DECLARE v_acc_cate_separate INTEGER;

	SET v_acc_cate_separate = (SELECT erp_settings.acc_cate_separate FROM erp_settings WHERE erp_settings.setting_id = '1');
	SET v_tran_no = (
		SELECT
			COALESCE (MAX(tran_no), 0) + 1
		FROM
			erp_gl_trans
	);

	UPDATE erp_order_ref SET tr = v_tran_no WHERE DATE_FORMAT(date, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m');

	SET e_narrative = (
		SELECT
			erp_gl_charts.accountname
		FROM
			erp_gl_charts
		WHERE
			erp_gl_charts.accountcode = NEW.account
	);

	INSERT INTO erp_gl_trans (
		tran_type,
		tran_no,
		tran_date,
		sectionid,
		account_code,
		narrative,
		amount,
		reference_no,
		description,
		biller_id,
		created_by
	)
	SELECT
		'USING STOCK',
		v_tran_no,
		NEW.date,
		erp_gl_sections.sectionid,
		erp_gl_charts.accountcode,
		erp_gl_charts.accountname,
		NEW.total_cost,
		NEW.reference_no,
		NEW.note,
		NEW.shop,
		NEW.create_by
		FROM
			erp_account_settings
			INNER JOIN erp_gl_charts
			ON erp_gl_charts.accountcode = NEW.account
			INNER JOIN erp_gl_sections
			ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
		WHERE
			erp_gl_charts.accountcode = NEW.account;

	SET inv_account_code = (
		SELECT
			erp_account_settings.default_stock
		FROM
			erp_account_settings
	);
	SET inv_narrative = (
		SELECT
			erp_gl_charts.accountname
		FROM
			erp_gl_charts
		WHERE
			erp_gl_charts.accountcode = inv_account_code 
	);

	SET cost_variant_account_code = (
		SELECT
			erp_account_settings.default_cost_variant
		FROM
			erp_account_settings
	);
	SET cost_variant_narrative = (
		SELECT
			erp_gl_charts.accountname
		FROM
			erp_gl_charts
		WHERE
			erp_gl_charts.accountcode = cost_variant_account_code 
	);

	IF v_acc_cate_separate <> 1 THEN
		IF NEW.type = 'use' THEN
			INSERT INTO erp_gl_trans (
				tran_type,
				tran_no,
				tran_date,
				sectionid,
				account_code,
				narrative,
				amount,
				reference_no,
				description,
				biller_id,
				created_by
			)
			SELECT
				'USING STOCK',
				v_tran_no,
				NEW.date,
				erp_gl_sections.sectionid,
				erp_gl_charts.accountcode,
				erp_gl_charts.accountname,
				(- 1) * abs(NEW.total_cost),
				NEW.reference_no,
				NEW.note,
				NEW.shop,
				NEW.create_by
				FROM
					erp_account_settings
					INNER JOIN erp_gl_charts
					ON erp_gl_charts.accountcode = erp_account_settings.default_stock
					INNER JOIN erp_gl_sections
					ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
				WHERE
					erp_gl_charts.accountcode = erp_account_settings.default_stock;
		END IF;
	END IF;

	IF NEW.type = 'return' THEN
		IF NEW.total_cost <> NEW.total_using_cost THEN
			INSERT INTO erp_gl_trans (
				tran_type,
				tran_no,
				tran_date,
				sectionid,
				account_code,
				narrative,
				amount,
				reference_no,
				description,
				biller_id,
				created_by
			)
			SELECT
				'USING STOCK',
				v_tran_no,
				NEW.date,
				erp_gl_sections.sectionid,
				erp_gl_charts.accountcode,
				erp_gl_charts.accountname,
				(NEW.total_using_cost)-(NEW.total_cost),
				NEW.reference_no,
				NEW.note,
				NEW.shop,
				NEW.create_by
				FROM
					erp_account_settings
					INNER JOIN erp_gl_charts
					ON erp_gl_charts.accountcode = erp_account_settings.default_cost_variant
					INNER JOIN erp_gl_sections
					ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
				WHERE
					erp_gl_charts.accountcode = erp_account_settings.default_cost_variant;
		END IF;
		INSERT INTO erp_gl_trans (
			tran_type,
			tran_no,
			tran_date,
			sectionid,
			account_code,
			narrative,
			amount,
			reference_no,
			description,
			biller_id,
			created_by
		)
		SELECT
				'USING STOCK',
				v_tran_no,
				NEW.date,
				erp_gl_sections.sectionid,
				erp_gl_charts.accountcode,
				erp_gl_charts.accountname,
				(- 1) * abs(NEW.total_using_cost),
				NEW.reference_no,
				NEW.note,
				NEW.shop,
				NEW.create_by
				FROM
					erp_account_settings
					INNER JOIN erp_gl_charts
					ON erp_gl_charts.accountcode = erp_account_settings.default_stock
					INNER JOIN erp_gl_sections
					ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
				WHERE
					erp_gl_charts.accountcode = erp_account_settings.default_stock;
	END IF;

END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `gl_trans_enter_using_stock_update`;
DELIMITER ;;
CREATE TRIGGER `gl_trans_enter_using_stock_update` AFTER UPDATE ON `erp_enter_using_stock` FOR EACH ROW BEGIN

	DECLARE e_tran_type VARCHAR (50);
	DECLARE e_tran_date DATETIME;
	DECLARE e_account_code INTEGER;
	DECLARE e_narrative VARCHAR (255);
	DECLARE v_tran_no INTEGER;
	DECLARE inv_account_code INTEGER;
	DECLARE inv_narrative VARCHAR (255);
	DECLARE cost_variant_account_code INTEGER;
	DECLARE cost_variant_narrative VARCHAR (255);
	DECLARE inv_created_by VARCHAR (255);
	DECLARE v_acc_cate_separate INTEGER;

	SET v_acc_cate_separate = (SELECT erp_settings.acc_cate_separate FROM erp_settings WHERE erp_settings.setting_id = '1');
	
	SET  inv_created_by = (SELECT created_by from erp_gl_trans where reference_no = NEW.reference_no LIMIT 1);
	SET v_tran_no = (SELECT COALESCE (MAX(tran_no), 0) + 1 FROM erp_gl_trans);

	UPDATE erp_order_ref SET tr = v_tran_no WHERE DATE_FORMAT(date, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m');

	SET e_narrative = (
		SELECT
			erp_gl_charts.accountname
		FROM
			erp_gl_charts
		WHERE
			erp_gl_charts.accountcode = NEW.account
	);

	DELETE FROM erp_gl_trans WHERE tran_type='USING STOCK' AND bank=0 AND reference_no = NEW.reference_no;

	INSERT INTO erp_gl_trans (
		tran_type,
		tran_no,
		tran_date,
		sectionid,
		account_code,
		narrative,
		amount,
		reference_no,
		description,
		biller_id,
		created_by,
		updated_by
	)
	SELECT
		'USING STOCK',
		v_tran_no,
		NEW.date,
		erp_gl_sections.sectionid,
		erp_gl_charts.accountcode,
		erp_gl_charts.accountname,
		NEW.total_cost,
		NEW.reference_no,
		NEW.note,
		NEW.shop,
		inv_created_by,
		NEW.create_by
		FROM
			erp_account_settings
			INNER JOIN erp_gl_charts
			ON erp_gl_charts.accountcode = NEW.account
			INNER JOIN erp_gl_sections
			ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
		WHERE
			erp_gl_charts.accountcode = NEW.account;

	SET inv_account_code = (
		SELECT
			erp_account_settings.default_stock
		FROM
			erp_account_settings
	);
	SET inv_narrative = (
		SELECT
			erp_gl_charts.accountname
		FROM
			erp_gl_charts
		WHERE
			erp_gl_charts.accountcode = inv_account_code 
	);
	SET cost_variant_account_code = (
		SELECT
			erp_account_settings.default_cost_variant
		FROM
			erp_account_settings
	);
	SET cost_variant_narrative = (
		SELECT
			erp_gl_charts.accountname
		FROM
			erp_gl_charts
		WHERE
			erp_gl_charts.accountcode = cost_variant_account_code 
	);

	IF v_acc_cate_separate <> 1 THEN
		IF NEW.type = 'use' THEN
			INSERT INTO erp_gl_trans (
				tran_type,
				tran_no,
				tran_date,
				sectionid,
				account_code,
				narrative,
				amount,
				reference_no,
				description,
				biller_id,
				created_by
			)
			SELECT
				'USING STOCK',
				v_tran_no,
				NEW.date,
				erp_gl_sections.sectionid,
				erp_gl_charts.accountcode,
				erp_gl_charts.accountname,
				(- 1) * abs(NEW.total_cost),
				NEW.reference_no,
				NEW.note,
				NEW.shop,
				NEW.create_by
				FROM
					erp_account_settings
					INNER JOIN erp_gl_charts
					ON erp_gl_charts.accountcode = erp_account_settings.default_stock
					INNER JOIN erp_gl_sections
					ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
				WHERE
					erp_gl_charts.accountcode = erp_account_settings.default_stock;
		END IF;
	END IF;

	IF NEW.type = 'return' THEN
		IF NEW.total_cost <> NEW.total_using_cost THEN
			INSERT INTO erp_gl_trans (
				tran_type,
				tran_no,
				tran_date,
				sectionid,
				account_code,
				narrative,
				amount,
				reference_no,
				description,
				biller_id,
				created_by
			)
			SELECT
				'USING STOCK',
				v_tran_no,
				NEW.date,
				erp_gl_sections.sectionid,
				erp_gl_charts.accountcode,
				erp_gl_charts.accountname,
				(NEW.total_using_cost)-(NEW.total_cost),
				NEW.reference_no,
				NEW.note,
				NEW.shop,
				NEW.create_by
				FROM
					erp_account_settings
					INNER JOIN erp_gl_charts
					ON erp_gl_charts.accountcode = erp_account_settings.default_cost_variant
					INNER JOIN erp_gl_sections
					ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
				WHERE
					erp_gl_charts.accountcode = erp_account_settings.default_cost_variant;
		END IF;
		INSERT INTO erp_gl_trans (
			tran_type,
			tran_no,
			tran_date,
			sectionid,
			account_code,
			narrative,
			amount,
			reference_no,
			description,
			biller_id,
			created_by
		)
		SELECT
				'USING STOCK',
				v_tran_no,
				NEW.date,
				erp_gl_sections.sectionid,
				erp_gl_charts.accountcode,
				erp_gl_charts.accountname,
				(- 1) * abs(NEW.total_using_cost),
				NEW.reference_no,
				NEW.note,
				NEW.shop,
				NEW.create_by
				FROM
					erp_account_settings
					INNER JOIN erp_gl_charts
					ON erp_gl_charts.accountcode = erp_account_settings.default_stock
					INNER JOIN erp_gl_sections
					ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
				WHERE
					erp_gl_charts.accountcode = erp_account_settings.default_stock;
	END IF;
END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `gl_trans_enter_using_stock_items_insert`;
DELIMITER ;;
CREATE TRIGGER `gl_trans_enter_using_stock_items_insert` AFTER INSERT ON `erp_enter_using_stock_items` FOR EACH ROW BEGIN

	DECLARE v_acc_cate_separate INTEGER;
	DECLARE v_tran_no INTEGER;
	DECLARE v_cate_id INTEGER;
	DECLARE v_type VARCHAR(50);
	DECLARE v_date DATETIME;
	DECLARE v_note VARCHAR(255);
	DECLARE v_shop VARCHAR(50);
	DECLARE v_created_by VARCHAR(50);

	SET v_acc_cate_separate = (SELECT erp_settings.acc_cate_separate FROM erp_settings WHERE erp_settings.setting_id = '1');
	
	IF v_acc_cate_separate = 1 THEN
		SET v_tran_no = (
			SELECT
				erp_gl_trans.tran_no
			FROM
				erp_gl_trans
			WHERE
				erp_gl_trans.reference_no = NEW.reference_no LIMIT 1
		);
		SET v_type = (SELECT
				erp_enter_using_stock.type
			FROM
				erp_enter_using_stock
			WHERE
				erp_enter_using_stock.reference_no = NEW.reference_no LIMIT 1
		);
		SET v_date = (SELECT
				erp_enter_using_stock.date
			FROM
				erp_enter_using_stock
			WHERE
				erp_enter_using_stock.reference_no = NEW.reference_no LIMIT 1
		);
		SET v_note = (SELECT
				erp_enter_using_stock.note
			FROM
				erp_enter_using_stock
			WHERE
				erp_enter_using_stock.reference_no = NEW.reference_no LIMIT 1
		);
		SET v_shop = (SELECT
				erp_enter_using_stock.shop
			FROM
				erp_enter_using_stock
			WHERE
				erp_enter_using_stock.reference_no = NEW.reference_no LIMIT 1
		);
		SET v_created_by = (SELECT
				erp_enter_using_stock.create_by
			FROM
				erp_enter_using_stock
			WHERE
				erp_enter_using_stock.reference_no = NEW.reference_no LIMIT 1
		);
		SET v_cate_id = (SELECT
				erp_products.category_id
			FROM
				erp_products
			WHERE
				erp_products.code = NEW.code LIMIT 1
		);
		
		IF v_type = 'use' THEN
			INSERT INTO erp_gl_trans (
				tran_type,
				tran_no,
				tran_date,
				sectionid,
				account_code,
				narrative,
				amount,
				reference_no,
				description,
				biller_id,
				created_by
			)
			SELECT
				'USING STOCK',
				v_tran_no,
				v_date,
				erp_gl_sections.sectionid,
				erp_gl_charts.accountcode,
				erp_gl_charts.accountname,
				(- 1) * ((SELECT erp_products.cost FROM erp_products WHERE erp_products.code = NEW.code)*NEW.qty_use),
				NEW.reference_no,
				v_note,
				v_shop,
				v_created_by
				FROM
					erp_categories
					INNER JOIN erp_gl_charts
					ON erp_gl_charts.accountcode = erp_categories.ac_stock
					INNER JOIN erp_gl_sections
					ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
				WHERE
					erp_categories.id = v_cate_id;

		END IF;
	END IF;
END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `gl_trans_expenses_insert`;
DELIMITER ;;
CREATE TRIGGER `gl_trans_expenses_insert` AFTER INSERT ON `erp_expenses` FOR EACH ROW BEGIN

	DECLARE v_tran_no INTEGER;
	DECLARE v_tran_date DATETIME;


	IF NEW.created_by THEN

		SET v_tran_date = (SELECT erp_expenses.date 
			FROM erp_payments 
			INNER JOIN erp_expenses ON erp_expenses.id = erp_payments.expense_id
			WHERE erp_expenses.id = NEW.id LIMIT 0,1);

		IF v_tran_date = NEW.date THEN
			SET v_tran_no = (SELECT MAX(tran_no) FROM erp_gl_trans);
		ELSE
			SET v_tran_no = (SELECT COALESCE(MAX(tran_no),0) +1 FROM erp_gl_trans);
		
			UPDATE erp_order_ref
			SET tr = v_tran_no
			WHERE
			DATE_FORMAT(date, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m');
		END IF;



		INSERT INTO erp_gl_trans (
				tran_type,
				tran_no,
				tran_date,
				sectionid,
				account_code,
				narrative,
				amount,
				reference_no,
				description,
				biller_id,
				created_by,
				updated_by,
				sale_id,
				customer_id
			) SELECT
				'JOURNAL',
				v_tran_no,
				NEW.date,
				erp_gl_charts.sectionid,
				erp_gl_charts.accountcode,
				erp_gl_charts.accountname,
				NEW.amount,
				NEW.reference,
				NEW.note,
				NEW.biller_id,
				NEW.created_by,
				NEW.updated_by,
				NEW.sale_id,
				NEW.customer_id
				FROM
					erp_gl_charts
				WHERE
					erp_gl_charts.accountcode = NEW.account_code;

		INSERT INTO erp_gl_trans (
				tran_type,

				tran_no,
				tran_date,
				sectionid,
				account_code,
				narrative,
				amount,
				reference_no,
				description,
				biller_id,
				created_by,
				updated_by,
				sale_id,
				customer_id
			) SELECT
				'JOURNAL',
				v_tran_no,
				NEW.date,
				erp_gl_charts.sectionid,
				erp_gl_charts.accountcode,
				erp_gl_charts.accountname,
				(-1)*NEW.amount,
				NEW.reference,
				NEW.note,
				NEW.biller_id,
				NEW.created_by,
				NEW.updated_by,
				NEW.sale_id,
				NEW.customer_id
				FROM
					erp_gl_charts
				WHERE
					erp_gl_charts.accountcode = NEW.bank_code;
		

	END IF;
END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `audit_expenses_update`;
DELIMITER ;;
CREATE TRIGGER `audit_expenses_update` BEFORE UPDATE ON `erp_expenses` FOR EACH ROW BEGIN
	IF OLD.id THEN
		INSERT INTO erp_expenses_audit (
			id,
			date,
			reference,
			amount,
			note,
			created_by,
			attachment,
			account_code,
			bank_code,
			biller_id,
			updated_by,
			updated_at,
			tax,
			status,
			warehouse_id,
			audit_type
		) SELECT 
			id,
			date,
			reference,
			amount,
			note,
			created_by,
			attachment,
			account_code,
			bank_code,
			biller_id,
			updated_by,
			updated_at,
			tax,
			status,
			warehouse_id,
			"UPDATED"
		FROM
			erp_expenses
		WHERE
			erp_expenses.id = OLD.id;
	END IF;

END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `gl_trans_expenses_update`;
DELIMITER ;;
CREATE TRIGGER `gl_trans_expenses_update` AFTER UPDATE ON `erp_expenses` FOR EACH ROW BEGIN

	DECLARE v_tran_no INTEGER;

	SET v_tran_no = (SELECT tran_no FROM erp_gl_trans WHERE reference_no = NEW.reference LIMIT 0,1);
	IF v_tran_no < 1  THEN
		SET v_tran_no = (SELECT COALESCE(MAX(tran_no),0) +1 FROM erp_gl_trans);
	                
		UPDATE erp_order_ref SET tr = v_tran_no WHERE DATE_FORMAT(date, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m');
	ELSE
	                SET v_tran_no = (SELECT MAX(tran_no) FROM erp_gl_trans);
	END IF;

	IF NEW.updated_by THEN

		
		DELETE FROM erp_gl_trans WHERE reference_no = NEW.reference;
		
		INSERT INTO erp_gl_trans (
				tran_type,
				tran_no,
				tran_date,
				sectionid,
				account_code,
				narrative,
				amount,
				reference_no,

				description,
				biller_id,
				created_by,
				updated_by,
				sale_id,
				customer_id
			) SELECT
				'JOURNAL',
				v_tran_no,
				NEW.date,
				erp_gl_charts.sectionid,
				erp_gl_charts.accountcode,
				erp_gl_charts.accountname,
				NEW.amount,
				NEW.reference,
				NEW.note,
				NEW.biller_id,
				NEW.created_by,
				NEW.updated_by,
				NEW.sale_id,
				NEW.customer_id
				FROM
					erp_gl_charts
				WHERE
					erp_gl_charts.accountcode = NEW.account_code;

		INSERT INTO erp_gl_trans (
				tran_type,
				tran_no,
				tran_date,
				sectionid,
				account_code,
				narrative,
				amount,
				reference_no,
				description,
				biller_id,
				created_by,
				updated_by,
				sale_id,
				customer_id
			) SELECT
				'JOURNAL',
				v_tran_no,
				NEW.date,
				erp_gl_charts.sectionid,
				erp_gl_charts.accountcode,
				erp_gl_charts.accountname,
				(-1)*NEW.amount,
				NEW.reference,
				NEW.note,
				NEW.biller_id,
				NEW.created_by,
				NEW.updated_by,
				NEW.sale_id,
				NEW.customer_id
				FROM
					erp_gl_charts
				WHERE
					erp_gl_charts.accountcode = NEW.bank_code;
			

	END IF;
END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `audit_expenses_delete`;
DELIMITER ;;
CREATE TRIGGER `audit_expenses_delete` BEFORE DELETE ON `erp_expenses` FOR EACH ROW BEGIN
	IF OLD.id THEN
		INSERT INTO erp_expenses_audit (
			id,
			date,
			reference,
			amount,
			note,
			created_by,
			attachment,
			account_code,
			bank_code,
			biller_id,
			updated_by,
			updated_at,
			tax,
			status,
			warehouse_id,
			audit_type
		) SELECT 
			id,
			date,
			reference,
			amount,
			note,
			created_by,
			attachment,
			account_code,
			bank_code,
			biller_id,
			updated_by,
			updated_at,
			tax,
			status,
			warehouse_id,
			"DELETED"
		FROM
			erp_expenses
		WHERE
			erp_expenses.id = OLD.id;
	END IF;

END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `gl_trans_expenses_delete`;
DELIMITER ;;
CREATE TRIGGER `gl_trans_expenses_delete` AFTER DELETE ON `erp_expenses` FOR EACH ROW BEGIN

   UPDATE erp_gl_trans SET amount = 0, description = CONCAT(description,' (Cancelled)')
   WHERE reference_no = OLD.reference;

END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `audit_gift_cards_update`;
DELIMITER ;;
CREATE TRIGGER `audit_gift_cards_update` BEFORE UPDATE ON `erp_gift_cards` FOR EACH ROW BEGIN
	IF OLD.id THEN
		INSERT INTO erp_gift_cards_audit (
			id,
			date,
			card_no,
			value,
			customer_id,
			customer,
			balance,
			expiry,
			created_by,
			audit_type
		) SELECT 
			id,
			date,
			card_no,
			value,
			customer_id,
			customer,
			balance,
			expiry,
			created_by,
			"UPDATED"
		FROM
			erp_gift_cards
		WHERE
			erp_gift_cards.id = OLD.id;
	END IF;

END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `audit_gift_cards_delete`;
DELIMITER ;;
CREATE TRIGGER `audit_gift_cards_delete` BEFORE DELETE ON `erp_gift_cards` FOR EACH ROW BEGIN
	IF OLD.id THEN
		INSERT INTO erp_gift_cards_audit (
			id,
			date,
			card_no,
			value,
			customer_id,
			customer,
			balance,
			expiry,
			created_by,
			audit_type
		) SELECT 
			id,
			date,
			card_no,
			value,
			customer_id,
			customer,
			balance,
			expiry,
			created_by,
			"DELETED"
		FROM
			erp_gift_cards
		WHERE
			erp_gift_cards.id = OLD.id;
	END IF;

END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `audit_gl_trans_update`;
DELIMITER ;;
CREATE TRIGGER `audit_gl_trans_update` BEFORE UPDATE ON `erp_gl_trans` FOR EACH ROW BEGIN
	IF OLD.tran_id THEN
		INSERT INTO erp_gl_trans_audit (
			tran_id,
			tran_type,
			tran_no,
			tran_date,
			sectionid,
			account_code,
			narrative,
			amount,
			reference_no,
			description,
			biller_id,
			created_by,
			updated_by,
			bank,
			gov_tax,
			reference_gov_tax,
			people_id,
			invoice_ref,
			ref_type,
			created_name,
			created_type,
			people,
			type,
			status_tax,
			audit_type
		) SELECT 
			tran_id,
			tran_type,
			tran_no,
			tran_date,
			sectionid,
			account_code,
			narrative,
			amount,
			reference_no,
			description,
			biller_id,
			created_by,
			updated_by,
			bank,
			gov_tax,
			reference_gov_tax,
			people_id,
			invoice_ref,
			ref_type,
			created_name,
			created_type,
			people,
			type,
			status_tax,
			"UPDATED"
		FROM
			erp_gl_trans
		WHERE
			erp_gl_trans.tran_id= OLD.tran_id;
	END IF;

END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `audit_gl_trans_delete`;
DELIMITER ;;
CREATE TRIGGER `audit_gl_trans_delete` BEFORE DELETE ON `erp_gl_trans` FOR EACH ROW BEGIN
	IF OLD.tran_id THEN
		INSERT INTO erp_gl_trans_audit (
			tran_id,
			tran_type,
			tran_no,
			tran_date,
			sectionid,
			account_code,
			narrative,
			amount,
			reference_no,
			description,
			biller_id,
			created_by,
			updated_by,
			bank,
			gov_tax,
			reference_gov_tax,
			people_id,
			invoice_ref,
			ref_type,
			created_name,
			created_type,
			people,
			type,
			status_tax,
			audit_type
		) SELECT 
			tran_id,
			tran_type,
			tran_no,
			tran_date,
			sectionid,
			account_code,
			narrative,
			amount,
			reference_no,
			description,
			biller_id,
			created_by,
			updated_by,
			bank,
			gov_tax,
			reference_gov_tax,
			people_id,
			invoice_ref,
			ref_type,
			created_name,
			created_type,
			people,
			type,
			status_tax,
			"DELETED"
		FROM
			erp_gl_trans
		WHERE
			erp_gl_trans.tran_id= OLD.tran_id;
	END IF;

END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `gl_trans_loan_delete`;
DELIMITER ;;
CREATE TRIGGER `gl_trans_loan_delete` AFTER DELETE ON `erp_loans` FOR EACH ROW BEGIN

   UPDATE erp_gl_trans SET amount = 0, description = CONCAT(description,' (Cancelled)')
   WHERE reference_no = OLD.reference_no;

END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `gl_trans_payments_insert`;
DELIMITER ;;
CREATE TRIGGER `gl_trans_payments_insert` AFTER INSERT ON `erp_payments` FOR EACH ROW BEGIN

DECLARE v_tran_no INTEGER;
DECLARE v_default_cash INTEGER;
DECLARE v_default_credit_card INTEGER;
DECLARE v_default_gift_card INTEGER;
DECLARE v_default_cheque INTEGER;
DECLARE v_default_sale_deposit INTEGER;
DECLARE v_default_purchase_deposit INTEGER;
DECLARE v_default_loan INTEGER;
DECLARE v_default_receivable INTEGER;
DECLARE v_default_payable INTEGER;
DECLARE v_bank_code INTEGER;
DECLARE v_account_code INTEGER;
DECLARE v_tran_date DATETIME;

DECLARE v_default_cost_variant INTEGER;

DECLARE v_real_amount DECIMAL(11,2);

SET v_tran_date = (SELECT erp_sales.date 
		FROM erp_payments 
		RIGHT JOIN erp_sales ON erp_sales.id = erp_payments.sale_id
		WHERE erp_sales.id = NEW.sale_id LIMIT 0,1);

IF v_tran_date = NEW.date THEN
	SET v_tran_no = (SELECT MAX(tran_no) FROM erp_gl_trans);
ELSE
	SET v_tran_no = (SELECT COALESCE(MAX(tran_no),0) +1 FROM erp_gl_trans);


	UPDATE erp_order_ref
	SET tr = v_tran_no
	WHERE
	DATE_FORMAT(date, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m');
END IF;

/*
SET v_default_cash = (SELECT default_cash FROM erp_account_settings WHERE biller_id = NEW.biller_id);
SET v_default_credit_card = (SELECT default_credit_card FROM erp_account_settings WHERE biller_id = NEW.biller_id);
SET v_default_gift_card = (SELECT default_gift_card FROM erp_account_settings WHERE biller_id = NEW.biller_id);
SET v_default_cheque = (SELECT default_cheque FROM erp_account_settings WHERE biller_id = NEW.biller_id);
SET v_default_sale_deposit = (SELECT default_sale_deposit FROM erp_account_settings WHERE biller_id = NEW.biller_id);
SET v_default_purchase_deposit = (SELECT default_purchase_deposit FROM erp_account_settings WHERE biller_id = NEW.biller_id);
SET v_default_loan = (SELECT default_loan FROM erp_account_settings WHERE biller_id = NEW.biller_id);

SET v_default_receivable = (SELECT default_receivable FROM erp_account_settings WHERE biller_id = NEW.biller_id);
SET v_default_payable = (SELECT default_payable FROM erp_account_settings WHERE biller_id = NEW.biller_id);
*/

SET v_default_cash = (SELECT default_cash FROM erp_account_settings);
SET v_default_credit_card = (SELECT default_credit_card FROM erp_account_settings);
SET v_default_gift_card = (SELECT default_gift_card FROM erp_account_settings);
SET v_default_cheque = (SELECT default_cheque FROM erp_account_settings);
SET v_default_sale_deposit = (SELECT default_sale_deposit FROM erp_account_settings);
SET v_default_purchase_deposit = (SELECT default_purchase_deposit FROM erp_account_settings);

SET v_default_loan = (SELECT default_loan FROM erp_account_settings);

SET v_default_receivable = (SELECT default_receivable FROM erp_account_settings);
SET v_default_payable = (SELECT default_payable FROM erp_account_settings);

SET v_default_cost_variant = (SELECT default_cost_variant FROM erp_account_settings);

SET v_real_amount = (SELECT SUM(old_grand_total) as am FROM erp_return_purchases WHERE purchase_id = NEW.purchase_id);

IF NEW.paid_by = 'cash' THEN 
SET v_bank_code = v_default_cash;          
END IF;

IF NEW.paid_by = 'credit_card' THEN
SET v_bank_code = v_default_credit_card;
END IF;

IF NEW.paid_by = 'gift_card' THEN
SET v_bank_code = v_default_gift_card ;
END IF;

IF NEW.paid_by = 'cheque' THEN
SET v_bank_code = v_default_cheque;
END IF;

IF NEW.paid_by = 'deposit' AND (ISNULL(NEW.sale_id) OR NEW.sale_id > 0) THEN
	SET v_bank_code = v_default_sale_deposit;
END IF;

IF NEW.paid_by = 'depreciation' AND NEW.sale_id > 0 THEN
	SET v_bank_code = v_default_sale_deposit;
END IF;

IF NEW.paid_by = 'deposit' AND NEW.purchase_id > 0    THEN
	SET v_bank_code = v_default_purchase_deposit;
END IF;

IF NEW.paid_by = 'loan' THEN
SET v_bank_code = v_default_loan;
END IF;

IF NEW.bank_account THEN
	SET v_bank_code = NEW.bank_account;
END IF;

/* ==== SALE GL =====*/
	IF NEW.sale_id>0 THEN
		IF NEW.return_id>0 AND NEW.type = 'returned' AND NEW.amount>0 THEN
			
			SET v_tran_date = (SELECT erp_return_sales.date 
					FROM erp_return_sales 
					WHERE erp_return_sales.id = NEW.return_id LIMIT 0,1);

			IF v_tran_date = NEW.date THEN
				SET v_tran_no = (SELECT MAX(tran_no) FROM erp_gl_trans);
			ELSE
				SET v_tran_no = (SELECT COALESCE(MAX(tran_no),0) +1 FROM erp_gl_trans);


				UPDATE erp_order_ref
				SET tr = v_tran_no
				WHERE
				DATE_FORMAT(date, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m');
			END IF;
			
			IF NEW.add_payment = 1 THEN
				INSERT INTO erp_gl_trans (
					tran_type,
					type,
					tran_no,
					tran_date,
					sectionid,
					account_code,
					narrative,
					amount,
					reference_no,
					description,
					biller_id,
					created_by,
					bank,
					updated_by,
					payment_id
					)
					SELECT
					'SALES-RETURN',
					'payment',
					v_tran_no,
					NEW.date,
					erp_gl_sections.sectionid,
					erp_gl_charts.accountcode,
					erp_gl_charts.accountname,
					NEW.amount,
					NEW.reference_no,
					(
						SELECT customer FROM erp_return_sales WHERE erp_return_sales.id = NEW.return_id
					),
					NEW.biller_id,
					NEW.created_by,
					'1',
					NEW.updated_by,
					NEW.id
					FROM
						erp_account_settings
					INNER JOIN erp_gl_charts
						ON erp_gl_charts.accountcode = v_default_receivable
						INNER JOIN erp_gl_sections
						ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
					WHERE
						erp_gl_charts.accountcode = v_default_receivable;
			END IF;

			IF NEW.bank_account THEN

				INSERT INTO erp_gl_trans (
				tran_type,
				type,
				tran_no,
				tran_date,
				sectionid,
				account_code,
				narrative,
				amount,
				reference_no,
				description,
				biller_id,
				created_by,
				bank,
				updated_by,
				payment_id) 
				SELECT
				'SALES-RETURN',
				'payment',
				v_tran_no,
				NEW.date,
				erp_gl_sections.sectionid,
				erp_gl_charts.accountcode,
				erp_gl_charts.accountname,
				(-1)*(NEW.amount),
				NEW.reference_no,
				NEW.note,
				NEW.biller_id,
				NEW.created_by,
				'1',
				NEW.updated_by,
				NEW.id
				FROM
					erp_account_settings
				INNER JOIN erp_gl_charts
					ON erp_gl_charts.accountcode = NEW.bank_account
					INNER JOIN erp_gl_sections
					ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
				WHERE
					erp_gl_charts.accountcode = NEW.bank_account;
			
			ELSE

				INSERT INTO erp_gl_trans (
				tran_type,
				type,
				tran_no,
				tran_date,
				sectionid,
				account_code,
				narrative,
				amount,
				reference_no,
				description,
				biller_id,
				created_by,
				bank,
				updated_by,
				payment_id) 
				SELECT
				'SALES-RETURN',
				'payment',
				v_tran_no,
				NEW.date,
				erp_gl_sections.sectionid,
				erp_gl_charts.accountcode,
				erp_gl_charts.accountname,
				(-1)*(NEW.amount),
				NEW.reference_no,
				NEW.note,
				NEW.biller_id,
				NEW.created_by,
				'1',
				NEW.updated_by,
				NEW.id
				FROM
					erp_account_settings
				INNER JOIN erp_gl_charts
					ON erp_gl_charts.accountcode = v_bank_code
					INNER JOIN erp_gl_sections
					ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
				WHERE
					erp_gl_charts.accountcode = v_bank_code;				

			END IF;


  		ELSE
			IF NEW.add_payment = 1 THEN
				INSERT INTO erp_gl_trans (
					tran_type,
					type,
					tran_no,
					tran_date,
					sectionid,
					account_code,
					narrative,
					amount,
					reference_no,
					description,
					biller_id,
					created_by,
					bank,
					updated_by,
					payment_id)
					SELECT
					'SALES',
					'payment',
					v_tran_no,
					NEW.date,
					erp_gl_sections.sectionid,
					erp_gl_charts.accountcode,
					erp_gl_charts.accountname,
					(-1)*(NEW.amount),
					NEW.reference_no,
					NEW.note,
					NEW.biller_id,
					'1',
					NEW.created_by,
					NEW.updated_by,
					NEW.id
					FROM
						erp_account_settings
						INNER JOIN erp_gl_charts
						ON erp_gl_charts.accountcode = v_default_receivable
						INNER JOIN erp_gl_sections
						ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
					WHERE
						erp_gl_charts.accountcode = v_default_receivable;
			END IF;

			IF NEW.bank_account THEN
				INSERT INTO erp_gl_trans (
					tran_type,
					type,
					tran_no,
					tran_date,
					sectionid,
					account_code,
					narrative,
					amount,
					reference_no,
					description,
					biller_id,
					created_by,
					bank,
					updated_by,
					payment_id) 
					SELECT
					'SALES',
					'payment',
					v_tran_no,
					NEW.date,
					erp_gl_sections.sectionid,
					erp_gl_charts.accountcode,
					erp_gl_charts.accountname,
					NEW.amount,
					NEW.reference_no,
					NEW.note,
					NEW.biller_id,
					NEW.created_by,
					'1',
					NEW.updated_by,
					NEW.id
					FROM
					erp_account_settings
						INNER JOIN erp_gl_charts
						ON erp_gl_charts.accountcode = NEW.bank_account
						INNER JOIN erp_gl_sections
						ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
					WHERE
						erp_gl_charts.accountcode = NEW.bank_account;
			ELSE
				INSERT INTO erp_gl_trans (
					tran_type,
					type,
					tran_no,
					tran_date,
					sectionid,
					account_code,
					narrative,
					amount,
					reference_no,
					description,
					biller_id,
					created_by,
					bank,
					updated_by,
					payment_id) 
					SELECT
					'SALES',
					'payment',
					v_tran_no,
					NEW.date,
					erp_gl_sections.sectionid,
					erp_gl_charts.accountcode,
					erp_gl_charts.accountname,
					NEW.amount,
					NEW.reference_no,
					NEW.note,
					NEW.biller_id,
					NEW.created_by,
					'1',
					NEW.updated_by,
					NEW.id
					FROM
					erp_account_settings
						INNER JOIN erp_gl_charts
						ON erp_gl_charts.accountcode = v_bank_code
						INNER JOIN erp_gl_sections
						ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
					WHERE
						erp_gl_charts.accountcode = v_bank_code;

            END IF;

		END IF;
		
	ELSE
	
		IF NEW.return_id>0 AND NEW.type = 'returned' AND NEW.amount>0 THEN
			
			SET v_tran_date = (SELECT erp_return_sales.date 
					FROM erp_return_sales 
					WHERE erp_return_sales.id = NEW.return_id LIMIT 0,1);

			IF v_tran_date = NEW.date THEN
				SET v_tran_no = (SELECT MAX(tran_no) FROM erp_gl_trans);
			ELSE
				SET v_tran_no = (SELECT COALESCE(MAX(tran_no),0) +1 FROM erp_gl_trans);

				UPDATE erp_order_ref
				SET tr = v_tran_no
				WHERE
				DATE_FORMAT(date, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m');
			END IF;
			
			IF NEW.add_payment = 1 THEN
				INSERT INTO erp_gl_trans (
					tran_type,
					type,
					tran_no,
					tran_date,
					sectionid,
					account_code,
					narrative,
					amount,
					reference_no,
					description,
					biller_id,
					created_by,

					bank,
					updated_by,
					payment_id
					)
					SELECT
					'SALES-RETURN',
					'payment',
					v_tran_no,
					NEW.date,
					erp_gl_sections.sectionid,
					erp_gl_charts.accountcode,
					erp_gl_charts.accountname,
					NEW.amount,
					NEW.reference_no,
					NEW.note,
					NEW.biller_id,
					NEW.created_by,
					'1',
					NEW.updated_by,
					NEW.id
					FROM
						erp_account_settings
					INNER JOIN erp_gl_charts
						ON erp_gl_charts.accountcode = v_default_receivable
						INNER JOIN erp_gl_sections
						ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
					WHERE
						erp_gl_charts.accountcode = v_default_receivable;
			END IF;

			INSERT INTO erp_gl_trans (
				tran_type,
				type,
				tran_no,
				tran_date,
				sectionid,
				account_code,
				narrative,
				amount,
				reference_no,
				description,
				biller_id,
				created_by,
				bank,
				updated_by,
				payment_id) 
				SELECT
				'SALES-RETURN',
				'payment',
				v_tran_no,
				NEW.date,
				erp_gl_sections.sectionid,
				erp_gl_charts.accountcode,
				erp_gl_charts.accountname,
				(-1)*(NEW.amount),
				NEW.reference_no,
				NEW.note,
				NEW.biller_id,
				NEW.created_by,
				'1',
				NEW.updated_by,
				NEW.id
				FROM
					erp_account_settings
				INNER JOIN erp_gl_charts
					ON erp_gl_charts.accountcode = v_bank_code
					INNER JOIN erp_gl_sections
					ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
				WHERE
					erp_gl_charts.accountcode = v_bank_code;
		END IF;
		
    END IF;


/* ==== SALE DEPOSIT GL =====*/
	IF NEW.deposit_id THEN

		INSERT INTO erp_gl_trans (
			tran_type,
			tran_no,
			tran_date,
			sectionid,
			account_code,
			narrative,
			amount,
			reference_no,
			description,
			biller_id,
			created_by,
			bank,
			updated_by)
			SELECT
			'DEPOSITS',
			v_tran_no,
			NEW.date,
			erp_gl_sections.sectionid,
			erp_gl_charts.accountcode,
			erp_gl_charts.accountname,
			(- 1) * abs(NEW.amount),
			NEW.reference_no,
			NEW.note,
			NEW.biller_id,
			NEW.created_by,
			'1',
			(
				SELECT updated_by FROM erp_deposits	WHERE id = NEW.deposit_id
			)
			FROM
				erp_account_settings
				INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = v_default_sale_deposit
				INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
			WHERE
				erp_gl_charts.accountcode = v_default_sale_deposit;
				
		IF NEW.bank_account THEN
		
			INSERT INTO erp_gl_trans (
				tran_type,
				tran_no,
				tran_date,
				sectionid,
				account_code,
				narrative,
				amount,
				reference_no,
				description,
				biller_id,
				created_by,
				bank,
				updated_by)
				SELECT
				'DEPOSITS',
				v_tran_no,
				NEW.date,
				erp_gl_sections.sectionid,
				erp_gl_charts.accountcode,
				erp_gl_charts.accountname,
				NEW.amount,
				NEW.reference_no,
				NEW.note,
				NEW.biller_id,
				NEW.created_by,
				'1',
				(
					SELECT updated_by FROM erp_deposits WHERE id = NEW.deposit_id
				)
				FROM
					erp_account_settings
					INNER JOIN erp_gl_charts
					ON erp_gl_charts.accountcode = NEW.bank_account
					INNER JOIN erp_gl_sections
					ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
				WHERE
					erp_gl_charts.accountcode = NEW.bank_account;
					
		ELSE
		
			INSERT INTO erp_gl_trans (
				tran_type,
				tran_no,
				tran_date,
				sectionid,
				account_code,
				narrative,
				amount,
				reference_no,
				description,
				biller_id,
				created_by,
				bank,
				updated_by)
				SELECT
				'DEPOSITS',
				v_tran_no,
				NEW.date,
				erp_gl_sections.sectionid,
				erp_gl_charts.accountcode,
				erp_gl_charts.accountname,
				NEW.amount,
				NEW.reference_no,
				NEW.note,
				NEW.biller_id,
				NEW.created_by,
				'1',
				(
					SELECT updated_by FROM erp_deposits WHERE id = NEW.deposit_id
				)
				FROM
					erp_account_settings
					INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = v_bank_code
					INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
				WHERE
					erp_gl_charts.accountcode = v_bank_code;
					
		END IF;
		
	END IF;
	
/* ==== SALE RETURN DEPOSIT GL =====*/
	IF NEW.return_deposit_id THEN
		INSERT INTO erp_gl_trans (
			tran_type,
			tran_no,
			tran_date,
			sectionid,
			account_code,
			narrative,
			amount,
			reference_no,
			description,
			biller_id,
			created_by,
			bank,
			updated_by)
			SELECT
			'DEPOSITS',
			v_tran_no,
			NEW.date,
			erp_gl_sections.sectionid,
			erp_gl_charts.accountcode,
			erp_gl_charts.accountname,
			abs(NEW.amount),
			NEW.reference_no,
			NEW.note,
			NEW.biller_id,
			NEW.created_by,
			'1',
			(
				SELECT updated_by FROM erp_deposits	WHERE id = NEW.deposit_id
			)
			FROM
				erp_account_settings
				INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = v_default_sale_deposit
				INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
			WHERE
				erp_gl_charts.accountcode = v_default_sale_deposit;
				
		IF NEW.bank_account THEN
		
			INSERT INTO erp_gl_trans (
				tran_type,
				tran_no,
				tran_date,
				sectionid,
				account_code,
				narrative,
				amount,
				reference_no,
				description,
				biller_id,
				created_by,
				bank,
				updated_by)
				SELECT
				'DEPOSITS',
				v_tran_no,
				NEW.date,
				erp_gl_sections.sectionid,
				erp_gl_charts.accountcode,
				erp_gl_charts.accountname,
				(-1) * abs(NEW.amount),
				NEW.reference_no,
				NEW.note,
				NEW.biller_id,
				NEW.created_by,
				'1',
				(
					SELECT updated_by FROM erp_deposits WHERE id = NEW.deposit_id
				)
				FROM
					erp_account_settings
					INNER JOIN erp_gl_charts
					ON erp_gl_charts.accountcode = NEW.bank_account
					INNER JOIN erp_gl_sections
					ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
				WHERE
					erp_gl_charts.accountcode = NEW.bank_account;

					
		ELSE
		
			INSERT INTO erp_gl_trans (
				tran_type,
				tran_no,
				tran_date,
				sectionid,
				account_code,
				narrative,
				amount,
				reference_no,
				description,
				biller_id,
				created_by,
				bank,
				updated_by)
				SELECT
				'DEPOSITS',
				v_tran_no,
				NEW.date,
				erp_gl_sections.sectionid,
				erp_gl_charts.accountcode,
				erp_gl_charts.accountname,
				(-1) * abs(NEW.amount),
				NEW.reference_no,
				NEW.note,
				NEW.biller_id,
				NEW.created_by,
				'1',
				(
					SELECT updated_by FROM erp_deposits WHERE id = NEW.deposit_id
				)
				FROM
					erp_account_settings
					INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = v_bank_code
					INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
				WHERE
					erp_gl_charts.accountcode = v_bank_code;
					
		END IF;
	END IF;

/* ==== PURCHASE DEPOSIT GL =====*/
	IF NEW.purchase_deposit_id THEN

		INSERT INTO erp_gl_trans (
			tran_type,
			tran_no,
			tran_date,
			sectionid,
			account_code,
			narrative,
			amount,
			reference_no,
			description,
			biller_id,
			created_by,
			bank,
			updated_by)
			SELECT
			'DEPOSITS',
			v_tran_no,
			NEW.date,
			erp_gl_sections.sectionid,
			erp_gl_charts.accountcode,
			erp_gl_charts.accountname,
			NEW.amount,
			NEW.reference_no,
			NEW.note,
			NEW.biller_id,
			NEW.created_by,
			'1',
			(
				SELECT updated_by FROM erp_deposits	WHERE id = NEW.deposit_id
			)
			FROM
				erp_account_settings
				INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = v_default_purchase_deposit
				INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
			WHERE
				erp_gl_charts.accountcode = v_default_purchase_deposit;

		

		IF NEW.bank_account THEN
                                 
            INSERT INTO erp_gl_trans (
				tran_type,
				tran_no,
				tran_date,
				sectionid,
				account_code,
				narrative,
				amount,
				reference_no,
				description,
				biller_id,
				created_by,
				bank,
				updated_by)
				SELECT
				'DEPOSITS',
				v_tran_no,
				NEW.date,
				erp_gl_sections.sectionid,
				erp_gl_charts.accountcode,
				erp_gl_charts.accountname,
				(- 1) * abs(NEW.amount),
				NEW.reference_no,
				NEW.note,
				NEW.biller_id,
				NEW.created_by,
				'1',
				(
					SELECT updated_by FROM erp_deposits WHERE id = NEW.deposit_id
				)
				FROM
					erp_account_settings
					INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = NEW.bank_account
					INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
				WHERE
					erp_gl_charts.accountcode =NEW.bank_account;

        ELSE
			
			IF NEW.opening = 1 THEN
			
				INSERT INTO erp_gl_trans (
					tran_type,
					tran_no,
					tran_date,
					sectionid,
					account_code,
					narrative,
					amount,
					reference_no,
					description,
					biller_id,
					created_by,
					bank,
					updated_by)
					SELECT
					'DEPOSITS',
					v_tran_no,
					NEW.date,
					erp_gl_sections.sectionid,
					erp_gl_charts.accountcode,
					erp_gl_charts.accountname,
					(- 1) * abs(NEW.amount),
					NEW.reference_no,
					NEW.note,
					NEW.biller_id,
					NEW.created_by,
					'1',
					(
						SELECT updated_by FROM erp_deposits WHERE id = NEW.deposit_id
					)
					FROM
						erp_account_settings
					INNER JOIN erp_gl_charts
						ON erp_gl_charts.accountcode = erp_account_settings.default_open_balance
					INNER JOIN erp_gl_sections
						ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
					WHERE
						erp_gl_charts.accountcode = erp_account_settings.default_open_balance;
			
			ELSE
			
				INSERT INTO erp_gl_trans (
					tran_type,
					tran_no,
					tran_date,
					sectionid,
					account_code,
					narrative,
					amount,
					reference_no,
					description,
					biller_id,
					created_by,
					bank,
					updated_by)
					SELECT
					'DEPOSITS',
					v_tran_no,
					NEW.date,
					erp_gl_sections.sectionid,
					erp_gl_charts.accountcode,
					erp_gl_charts.accountname,
					(- 1) * abs(NEW.amount),
					NEW.reference_no,
					NEW.note,
					NEW.biller_id,
					NEW.created_by,
					'1',
					(
						SELECT updated_by FROM erp_deposits WHERE id = NEW.deposit_id
					)
					FROM
						erp_account_settings
						INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = v_bank_code
						INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
					WHERE
						erp_gl_charts.accountcode = v_bank_code;
			END IF;
						
	END IF;
END IF;

/* ==== SALE LOAN GL =====*/
	IF NEW.loan_id > 0 THEN
		INSERT INTO erp_gl_trans (
			tran_type,
			tran_no,
			tran_date,
			sectionid,
			account_code,
			narrative,
			amount,
			reference_no,
			description,
			biller_id,
			created_by,
			bank,
			updated_by)
			SELECT
			'LOANS',
			v_tran_no,
			NEW.date,
			erp_gl_sections.sectionid,
			erp_gl_charts.accountcode,
			erp_gl_charts.accountname,
			(- 1) * abs(NEW.amount),
			(
				SELECT reference_no FROM erp_loans WHERE id = NEW.loan_id
			),
			NEW.note,
			NEW.biller_id,
			NEW.created_by,
			'1',
			(
				SELECT updated_by FROM erp_loans WHERE id = NEW.loan_id
			)
			FROM
				erp_account_settings
				INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = v_default_loan
				INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
			WHERE
				erp_gl_charts.accountcode = v_default_loan;

		INSERT INTO erp_gl_trans (
			tran_type,
			tran_no,
			tran_date,
			sectionid,
			account_code,
			narrative,
			amount,
			reference_no,
			description,
			biller_id,
			created_by,
			bank,
			updated_by)
			SELECT
			'LOANS',
			v_tran_no,
			NEW.date,
			erp_gl_sections.sectionid,
			erp_gl_charts.accountcode,
			erp_gl_charts.accountname,
			NEW.amount,
			(
				SELECT reference_no FROM erp_loans WHERE id = NEW.loan_id
			),
			NEW.note,
			NEW.biller_id,
			NEW.created_by,
			'1',
			(
				SELECT updated_by FROM erp_loans WHERE id = NEW.loan_id
			)
			FROM
				erp_account_settings
				INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = v_bank_code
				INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
			WHERE
				erp_gl_charts.accountcode = v_bank_code;
	END IF;

/* ==== PURCHASE GL =====*/
	IF NEW.purchase_id>0 THEN
		IF NEW.note = 'Returned' THEN

			IF (v_real_amount - NEW.amount) > 0 THEN

				INSERT INTO erp_gl_trans (
					tran_type,
					tran_no,
					tran_date,
					sectionid,
					account_code,
					narrative,
					amount,
					reference_no,
					description,
					biller_id,
					created_by,
					bank,
					updated_by)
					SELECT
					'PURCHASES-RETURN',
					v_tran_no,
					NEW.date,
					erp_gl_sections.sectionid,
					erp_gl_charts.accountcode,
					erp_gl_charts.accountname,
					(select if( (v_real_amount - NEW.amount) < 0 , (-1) * NEW.amount, (-1) * v_real_amount) as amount),
					(
						SELECT reference_no FROM erp_return_purchases WHERE purchase_id = NEW.purchase_id
					),
					NEW.note,
					NEW.biller_id,
					NEW.created_by,
					'1',
					(
						SELECT updated_by FROM erp_purchases WHERE id = NEW.purchase_id
					)
					FROM
						erp_account_settings
						INNER JOIN erp_gl_charts
						ON erp_gl_charts.accountcode = v_default_payable
						INNER JOIN erp_gl_sections
						ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
					WHERE
						erp_gl_charts.accountcode = v_default_payable;

				INSERT INTO erp_gl_trans (
					tran_type,
					tran_no,
					tran_date,
					sectionid,
					account_code,
					narrative,
					amount,
					reference_no,
					description,
					biller_id,
					created_by,
					bank,
					updated_by)
					SELECT
					'PURCHASES-RETURN',
					v_tran_no,
					NEW.date,
					erp_gl_sections.sectionid,
					erp_gl_charts.accountcode,
					erp_gl_charts.accountname,
					(select if( (v_real_amount - NEW.amount) < 0 , abs(v_real_amount), abs(NEW.amount)) as amount) ,
					(
						SELECT reference_no FROM erp_return_purchases WHERE purchase_id = NEW.purchase_id
					),
					NEW.note,
					NEW.biller_id,
					NEW.created_by,
					'1',
					(
						SELECT updated_by FROM erp_purchases WHERE id = NEW.purchase_id
					)
					FROM
						erp_account_settings
						INNER JOIN erp_gl_charts
						ON erp_gl_charts.accountcode = v_bank_code
						INNER JOIN erp_gl_sections
						ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
					WHERE
						erp_gl_charts.accountcode = v_bank_code;

				INSERT INTO erp_gl_trans (
					tran_type,
					tran_no,
					tran_date,
					sectionid,
					account_code,
					narrative,
					amount,
					reference_no,
					description,
					biller_id,
					created_by,
					bank,
					updated_by)
					SELECT
					'PURCHASES-RETURN',
					v_tran_no,
					NEW.date,
					erp_gl_sections.sectionid,
					erp_gl_charts.accountcode,
					erp_gl_charts.accountname,
					(v_real_amount - NEW.amount),
					(
						SELECT reference_no FROM erp_return_purchases WHERE purchase_id = NEW.purchase_id
					),
					NEW.note,
					NEW.biller_id,
					NEW.created_by,
					'1',
					(
						SELECT updated_by FROM erp_purchases WHERE id = NEW.purchase_id
					)
					FROM
						erp_account_settings
						INNER JOIN erp_gl_charts
						ON erp_gl_charts.accountcode = v_default_cost_variant
						INNER JOIN erp_gl_sections
						ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
					WHERE
						erp_gl_charts.accountcode = v_default_cost_variant;

			ELSE

				INSERT INTO erp_gl_trans (
					tran_type,
					tran_no,
					tran_date,
					sectionid,
					account_code,
					narrative,
					amount,
					reference_no,
					description,
					biller_id,
					created_by,
					bank,
					updated_by)
					SELECT
					'PURCHASES-RETURN',
					v_tran_no,
					NEW.date,
					erp_gl_sections.sectionid,
					erp_gl_charts.accountcode,
					erp_gl_charts.accountname,
					NEW.amount,
					(
						SELECT reference_no FROM erp_return_purchases WHERE purchase_id = NEW.purchase_id
					),
					NEW.note,
					NEW.biller_id,
					NEW.created_by,
					'1',
					(
						SELECT updated_by FROM erp_purchases WHERE id = NEW.purchase_id
					)
					FROM
						erp_account_settings
						INNER JOIN erp_gl_charts
						ON erp_gl_charts.accountcode = v_default_payable
						INNER JOIN erp_gl_sections
						ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
					WHERE
						erp_gl_charts.accountcode = v_default_payable;

				INSERT INTO erp_gl_trans (
					tran_type,
					tran_no,
					tran_date,
					sectionid,
					account_code,
					narrative,
					amount,
					reference_no,
					description,
					biller_id,
					created_by,
					bank,
					updated_by)
					SELECT
					'PURCHASES-RETURN',
					v_tran_no,
					NEW.date,
					erp_gl_sections.sectionid,
					erp_gl_charts.accountcode,
					erp_gl_charts.accountname,
					(-1)*abs(NEW.amount),
					(
						SELECT reference_no FROM erp_return_purchases WHERE purchase_id = NEW.purchase_id
					),
					NEW.note,
					NEW.biller_id,
					NEW.created_by,
					'1',
					(
						SELECT updated_by FROM erp_purchases WHERE id = NEW.purchase_id
					)
					FROM
						erp_account_settings
						INNER JOIN erp_gl_charts
						ON erp_gl_charts.accountcode = v_bank_code
						INNER JOIN erp_gl_sections
						ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
					WHERE
						erp_gl_charts.accountcode = v_bank_code;

			END IF;
        ELSE

			INSERT INTO erp_gl_trans (
				tran_type,
				tran_no,
				tran_date,
				sectionid,
				account_code,
				narrative,
				amount,
				reference_no,
				description,
				biller_id,
				created_by,
				bank,
				updated_by,
				payment_id)
				SELECT
				'PURCHASES',
				v_tran_no,
				NEW.date,
				erp_gl_sections.sectionid,
				erp_gl_charts.accountcode,
				erp_gl_charts.accountname,
				NEW.amount,
				(select if( NEW.paid_by = 'deposit' , (SELECT reference_no FROM erp_purchases WHERE id = NEW.purchase_id), NEW.reference_no ) as amount),
				NEW.note,
				NEW.biller_id,
				NEW.created_by,
				'1',
				(
					SELECT updated_by FROM erp_purchases WHERE id = NEW.purchase_id
				),
				NEW.id
			FROM
				erp_account_settings
				INNER JOIN erp_gl_charts
				ON erp_gl_charts.accountcode = v_default_payable
				INNER JOIN erp_gl_sections
				ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
			WHERE
				erp_gl_charts.accountcode = v_default_payable;

		INSERT INTO erp_gl_trans (
			tran_type,
			tran_no,
			tran_date,
			sectionid,
			account_code,
			narrative,
			amount,
			reference_no,
			description,
			biller_id,
			created_by,
			bank,
			updated_by,
			payment_id)
			SELECT
			'PURCHASES',
			v_tran_no,
			NEW.date,
			erp_gl_sections.sectionid,
			erp_gl_charts.accountcode,
			erp_gl_charts.accountname,
			(-1)*abs(NEW.amount),
			(select if( NEW.paid_by = 'deposit' , (SELECT reference_no FROM erp_purchases WHERE id = NEW.purchase_id), NEW.reference_no ) as amount),
			NEW.note,
			NEW.biller_id,
			NEW.created_by,
			'1',
			(
				SELECT updated_by FROM erp_purchases WHERE id = NEW.purchase_id
			),
			NEW.id
			FROM
				erp_account_settings
				INNER JOIN erp_gl_charts
				ON erp_gl_charts.accountcode = v_bank_code
				INNER JOIN erp_gl_sections
				ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
			WHERE
				erp_gl_charts.accountcode = v_bank_code;
		END IF;
	END IF;
     
END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `gl_trans_payments_update`;
DELIMITER ;;
CREATE TRIGGER `gl_trans_payments_update` AFTER UPDATE ON `erp_payments` FOR EACH ROW BEGIN

DECLARE v_tran_no INTEGER;
DECLARE v_default_cash INTEGER;
DECLARE v_default_credit_card INTEGER;
DECLARE v_default_gift_card INTEGER;
DECLARE v_default_cheque INTEGER;
DECLARE v_default_sale_deposit INTEGER;
DECLARE v_default_purchase_deposit INTEGER;
DECLARE v_default_loan INTEGER;
DECLARE v_default_receivable INTEGER;
DECLARE v_default_payable INTEGER;
DECLARE v_bank_code INTEGER;
DECLARE v_account_code INTEGER;
DECLARE v_tran_date DATETIME;
DECLARE v_referece varchar(50);

IF NEW.sale_id > 0 THEN
	SET v_tran_no = (SELECT tran_no FROM erp_gl_trans WHERE erp_gl_trans.reference_no = NEW.old_reference_no LIMIT 0,1); 
ELSE
	SET v_tran_no = (SELECT tran_no FROM erp_gl_trans WHERE erp_gl_trans.reference_no = NEW.reference_no LIMIT 0,1); 
END IF;

IF ISNULL(NEW.sale_id) THEN
	DELETE FROM erp_gl_trans WHERE tran_type='DEPOSITS' AND reference_no = NEW.reference_no;
END IF;

SET v_default_cash = (SELECT default_cash FROM erp_account_settings);
SET v_default_credit_card = (SELECT default_credit_card FROM erp_account_settings);
SET v_default_gift_card = (SELECT default_gift_card FROM erp_account_settings);
SET v_default_cheque = (SELECT default_cheque FROM erp_account_settings);
SET v_default_sale_deposit = (SELECT default_sale_deposit FROM erp_account_settings);
SET v_default_purchase_deposit = (SELECT default_purchase_deposit FROM erp_account_settings);

SET v_default_loan = (SELECT default_loan FROM erp_account_settings);

SET v_default_receivable = (SELECT default_receivable FROM erp_account_settings);
SET v_default_payable = (SELECT default_payable FROM erp_account_settings);

IF NEW.paid_by = 'cash' THEN 
SET v_bank_code = v_default_cash;          
END IF;

IF NEW.paid_by = 'credit_card' THEN
SET v_bank_code = v_default_credit_card;
END IF;

IF NEW.paid_by = 'gift_card' THEN
SET v_bank_code = v_default_gift_card ;
END IF;

IF NEW.paid_by = 'cheque' THEN
SET v_bank_code = v_default_cheque;
END IF;

IF NEW.paid_by = 'deposit' AND (ISNULL(NEW.sale_id) OR NEW.sale_id > 0) THEN
SET v_bank_code = v_default_sale_deposit;
END IF;

IF NEW.paid_by = 'depreciation' AND NEW.sale_id > 0 THEN
	SET v_bank_code = v_default_sale_deposit;
END IF;

IF NEW.paid_by = 'deposit' AND NEW.purchase_id > 0    THEN
	SET v_bank_code = v_default_purchase_deposit;
END IF;

IF NEW.paid_by = 'loan' THEN
SET v_bank_code = v_default_loan;
END IF;

IF NEW.bank_account THEN
	SET v_bank_code = NEW.bank_account;
END IF;

/* ==== SALE GL =====*/
	IF NEW.sale_id>0 THEN
	
		IF NEW.updated_by > 0 AND NEW.updated_count <> OLD.updated_count THEN
			DELETE FROM erp_gl_trans WHERE erp_gl_trans.reference_no = NEW.old_reference_no AND erp_gl_trans.type = 'payment' AND erp_gl_trans.payment_id = NEW.id;
		END IF;
		
		IF NEW.return_id>0 AND NEW.type = 'returned' AND NEW.amount>0 THEN
			
			SET v_tran_date = (SELECT erp_return_sales.date 
					FROM erp_return_sales 
					WHERE erp_return_sales.id = NEW.return_id LIMIT 0,1);

			IF v_tran_date = NEW.date THEN
				SET v_tran_no = (SELECT MAX(tran_no) FROM erp_gl_trans);
			ELSE
				SET v_tran_no = (SELECT COALESCE(MAX(tran_no),0) +1 FROM erp_gl_trans);


				UPDATE erp_order_ref
				SET tr = v_tran_no
				WHERE
				DATE_FORMAT(date, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m');
			END IF;
			
			IF NEW.add_payment = 1 THEN
				INSERT INTO erp_gl_trans (
					tran_type,
					type,
					tran_no,
					tran_date,
					sectionid,
					account_code,
					narrative,
					amount,
					reference_no,
					description,
					biller_id,
					created_by,
					bank,
					updated_by,
					payment_id
					)
					SELECT
					'SALES-RETURN',
					'payment',
					v_tran_no,
					NEW.date,
					erp_gl_sections.sectionid,
					erp_gl_charts.accountcode,
					erp_gl_charts.accountname,
					NEW.amount,
					NEW.reference_no,
					NEW.note,
					NEW.biller_id,
					NEW.created_by,
					'1',
					NEW.updated_by,
					NEW.id
					FROM
						erp_account_settings
					INNER JOIN erp_gl_charts
						ON erp_gl_charts.accountcode = v_default_receivable
						INNER JOIN erp_gl_sections
						ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
					WHERE
						erp_gl_charts.accountcode = v_default_receivable;
			END IF;

			IF NEW.bank_account THEN
			
				INSERT INTO erp_gl_trans (
				tran_type,
				type,
				tran_no,
				tran_date,
				sectionid,
				account_code,
				narrative,
				amount,
				reference_no,
				description,
				biller_id,
				created_by,
				bank,
				updated_by,
				payment_id) 
				SELECT
				'SALES-RETURN',
				'payment',
				v_tran_no,
				NEW.date,
				erp_gl_sections.sectionid,
				erp_gl_charts.accountcode,
				erp_gl_charts.accountname,
				(-1)*(NEW.amount),
				NEW.reference_no,
				NEW.note,
				NEW.biller_id,
				NEW.created_by,
				'1',
				NEW.updated_by,
				NEW.id
				FROM
					erp_account_settings
				INNER JOIN erp_gl_charts
					ON erp_gl_charts.accountcode = NEW.bank_account
					INNER JOIN erp_gl_sections
					ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
				WHERE
					erp_gl_charts.accountcode = NEW.bank_account;

			ELSE

				INSERT INTO erp_gl_trans (
				tran_type,
				type,
				tran_no,
				tran_date,
				sectionid,
				account_code,
				narrative,
				amount,
				reference_no,
				description,
				biller_id,
				created_by,
				bank,
				updated_by,
				payment_id) 
				SELECT
				'SALES-RETURN',
				'payment',
				v_tran_no,
				NEW.date,
				erp_gl_sections.sectionid,
				erp_gl_charts.accountcode,
				erp_gl_charts.accountname,
				(-1)*(NEW.amount),
				NEW.reference_no,
				NEW.note,
				NEW.biller_id,
				NEW.created_by,
				'1',
				NEW.updated_by,
				NEW.id
				FROM
					erp_account_settings
				INNER JOIN erp_gl_charts
					ON erp_gl_charts.accountcode = v_bank_code
					INNER JOIN erp_gl_sections
					ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
				WHERE
					erp_gl_charts.accountcode = v_bank_code;

			END IF;


  		ELSE
			IF NEW.add_payment = 1 THEN
				INSERT INTO erp_gl_trans (
					tran_type,
					type,
					tran_no,
					tran_date,
					sectionid,
					account_code,
					narrative,
					amount,
					reference_no,
					description,
					biller_id,
					created_by,
					bank,
					updated_by,
					payment_id)
					SELECT
					'SALES',
					'payment',
					v_tran_no,
					NEW.date,
					erp_gl_sections.sectionid,
					erp_gl_charts.accountcode,
					erp_gl_charts.accountname,
					(-1)*(NEW.amount),

					NEW.reference_no,
					NEW.note,
					NEW.biller_id,
					'1',
					NEW.created_by,
					NEW.updated_by,
					NEW.id
					FROM
						erp_account_settings
						INNER JOIN erp_gl_charts
						ON erp_gl_charts.accountcode = v_default_receivable
						INNER JOIN erp_gl_sections
						ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
					WHERE
						erp_gl_charts.accountcode = v_default_receivable;
			END IF;

			IF NEW.bank_account THEN

				INSERT INTO erp_gl_trans (
						tran_type,
						type,
						tran_no,
						tran_date,
						sectionid,
						account_code,
						narrative,
						amount,
						reference_no,
						description,
						biller_id,
						created_by,
						bank,
						updated_by,
						payment_id) 
					SELECT
						'SALES',
						'payment',
						v_tran_no,
						NEW.date,
						erp_gl_sections.sectionid,
						erp_gl_charts.accountcode,
						erp_gl_charts.accountname,
						NEW.amount,
						NEW.reference_no,
						NEW.note,
						NEW.biller_id,
						NEW.created_by,
						'1',
						NEW.updated_by,
						NEW.id
					FROM
						erp_account_settings
							INNER JOIN erp_gl_charts
							ON erp_gl_charts.accountcode =NEW.bank_account
							INNER JOIN erp_gl_sections
							ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
					WHERE
							erp_gl_charts.accountcode = NEW.bank_account;

			ELSE

				INSERT INTO erp_gl_trans (
						tran_type,
						type,
						tran_no,
						tran_date,
						sectionid,
						account_code,
						narrative,
						amount,
						reference_no,
						description,
						biller_id,
						created_by,
						bank,
						updated_by,
						payment_id) 
					SELECT
						'SALES',
						'payment',
						v_tran_no,
						NEW.date,
						erp_gl_sections.sectionid,
						erp_gl_charts.accountcode,
						erp_gl_charts.accountname,
						NEW.amount,
						NEW.reference_no,
						NEW.note,
						NEW.biller_id,
						NEW.created_by,
						'1',
						NEW.updated_by,
						NEW.id
					FROM
						erp_account_settings
							INNER JOIN erp_gl_charts
							ON erp_gl_charts.accountcode = v_bank_code
							INNER JOIN erp_gl_sections
							ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
						WHERE
							erp_gl_charts.accountcode = v_bank_code;

			END IF;
		END IF;
    END IF;

/* ==== SALE DEPOSIT GL =====*/
	IF NEW.deposit_id THEN

		INSERT INTO erp_gl_trans (
			tran_type,
			tran_no,
			tran_date,
			sectionid,
			account_code,
			narrative,
			amount,
			reference_no,
			description,
			biller_id,
			created_by,
			bank,
			updated_by)
			SELECT
			'DEPOSITS',
			v_tran_no,
			NEW.date,
			erp_gl_sections.sectionid,
			erp_gl_charts.accountcode,
			erp_gl_charts.accountname,
			(- 1) * abs(NEW.amount),
			NEW.reference_no,
			NEW.note,
			NEW.biller_id,
			NEW.created_by,

			'1',
			(
				SELECT updated_by FROM erp_deposits	WHERE id = NEW.deposit_id
			)
			FROM
				erp_account_settings
				INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = v_default_sale_deposit
				INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
			WHERE
				erp_gl_charts.accountcode = v_default_sale_deposit;
				
		IF NEW.bank_account THEN
		
			INSERT INTO erp_gl_trans (
				tran_type,
				tran_no,
				tran_date,
				sectionid,
				account_code,
				narrative,
				amount,
				reference_no,
				description,
				biller_id,
				created_by,
				bank,
				updated_by)
				SELECT
				'DEPOSITS',
				v_tran_no,
				NEW.date,
				erp_gl_sections.sectionid,
				erp_gl_charts.accountcode,
				erp_gl_charts.accountname,
				NEW.amount,
				NEW.reference_no,
				NEW.note,
				NEW.biller_id,
				NEW.created_by,
				'1',
				(
					SELECT updated_by FROM erp_deposits WHERE id = NEW.deposit_id
				)
				FROM
					erp_account_settings
					INNER JOIN erp_gl_charts
					ON erp_gl_charts.accountcode = NEW.bank_account
					INNER JOIN erp_gl_sections
					ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
				WHERE
					erp_gl_charts.accountcode = NEW.bank_account;
					
		ELSE
		
			INSERT INTO erp_gl_trans (
				tran_type,
				tran_no,
				tran_date,
				sectionid,
				account_code,
				narrative,
				amount,
				reference_no,
				description,
				biller_id,
				created_by,
				bank,
				updated_by)
				SELECT
				'DEPOSITS',
				v_tran_no,
				NEW.date,
				erp_gl_sections.sectionid,
				erp_gl_charts.accountcode,
				erp_gl_charts.accountname,
				NEW.amount,
				NEW.reference_no,
				NEW.note,
				NEW.biller_id,
				NEW.created_by,
				'1',
				(
					SELECT updated_by FROM erp_deposits WHERE id = NEW.deposit_id
				)
				FROM
					erp_account_settings
					INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = v_bank_code
					INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
				WHERE
					erp_gl_charts.accountcode = v_bank_code;
					
		END IF;
		
	END IF;

/* ==== SALE RETURN DEPOSIT GL =====*/
	IF NEW.return_deposit_id THEN
		INSERT INTO erp_gl_trans (
			tran_type,
			tran_no,
			tran_date,
			sectionid,
			account_code,
			narrative,
			amount,
			reference_no,
			description,
			biller_id,
			created_by,
			bank,
			updated_by)
			SELECT
			'DEPOSITS',
			v_tran_no,
			NEW.date,
			erp_gl_sections.sectionid,
			erp_gl_charts.accountcode,
			erp_gl_charts.accountname,
			abs(NEW.amount),
			NEW.reference_no,
			NEW.note,
			NEW.biller_id,
			NEW.created_by,
			'1',
			(
				SELECT updated_by FROM erp_deposits	WHERE id = NEW.deposit_id
			)
			FROM
				erp_account_settings
				INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = v_default_sale_deposit
				INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
			WHERE
				erp_gl_charts.accountcode = v_default_sale_deposit;
				
		IF NEW.bank_account THEN
		
			INSERT INTO erp_gl_trans (
				tran_type,
				tran_no,
				tran_date,
				sectionid,
				account_code,
				narrative,
				amount,
				reference_no,
				description,
				biller_id,
				created_by,
				bank,
				updated_by)
				SELECT
				'DEPOSITS',
				v_tran_no,
				NEW.date,
				erp_gl_sections.sectionid,
				erp_gl_charts.accountcode,
				erp_gl_charts.accountname,
				(-1) * abs(NEW.amount),
				NEW.reference_no,
				NEW.note,
				NEW.biller_id,
				NEW.created_by,
				'1',
				(
					SELECT updated_by FROM erp_deposits WHERE id = NEW.deposit_id
				)
				FROM
					erp_account_settings
					INNER JOIN erp_gl_charts
					ON erp_gl_charts.accountcode = NEW.bank_account
					INNER JOIN erp_gl_sections
					ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
				WHERE
					erp_gl_charts.accountcode = NEW.bank_account;
					
		ELSE
		
			INSERT INTO erp_gl_trans (
				tran_type,
				tran_no,
				tran_date,
				sectionid,
				account_code,
				narrative,
				amount,
				reference_no,
				description,
				biller_id,
				created_by,
				bank,
				updated_by)
				SELECT
				'DEPOSITS',
				v_tran_no,
				NEW.date,
				erp_gl_sections.sectionid,
				erp_gl_charts.accountcode,
				erp_gl_charts.accountname,
				(-1) * abs(NEW.amount),
				NEW.reference_no,
				NEW.note,
				NEW.biller_id,
				NEW.created_by,
				'1',
				(
					SELECT updated_by FROM erp_deposits WHERE id = NEW.deposit_id
				)
				FROM
					erp_account_settings
					INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = v_bank_code
					INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
				WHERE
					erp_gl_charts.accountcode = v_bank_code;
					
		END IF;
	END IF;

/* ==== PURCHASE DEPOSIT GL =====*/
	IF NEW.purchase_deposit_id THEN

		INSERT INTO erp_gl_trans (
			tran_type,
			tran_no,
			tran_date,
			sectionid,
			account_code,
			narrative,
			amount,
			reference_no,
			description,
			biller_id,
			created_by,
			bank,
			updated_by)
			SELECT
			'DEPOSITS',
			v_tran_no,
			NEW.date,
			erp_gl_sections.sectionid,
			erp_gl_charts.accountcode,
			erp_gl_charts.accountname,
			NEW.amount,
			NEW.reference_no,
			NEW.note,
			NEW.biller_id,
			NEW.created_by,
			'1',
			(
				SELECT updated_by FROM erp_deposits	WHERE id = NEW.deposit_id
			)
			FROM
				erp_account_settings
				INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = v_default_purchase_deposit
				INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
			WHERE
				erp_gl_charts.accountcode = v_default_purchase_deposit;

		INSERT INTO erp_gl_trans (
			tran_type,
			tran_no,
			tran_date,
			sectionid,
			account_code,
			narrative,
			amount,
			reference_no,
			description,
			biller_id,
			created_by,
			bank,
			updated_by)
			SELECT
			'DEPOSITS',
			v_tran_no,
			NEW.date,
			erp_gl_sections.sectionid,
			erp_gl_charts.accountcode,
			erp_gl_charts.accountname,
			(- 1) * abs(NEW.amount),
			NEW.reference_no,
			NEW.note,
			NEW.biller_id,
			NEW.created_by,
			'1',
			(
				SELECT updated_by FROM erp_deposits WHERE id = NEW.deposit_id
			)
			FROM
				erp_account_settings
				INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = v_bank_code
				INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
			WHERE
				erp_gl_charts.accountcode = v_bank_code;
	END IF;

/* ==== SALE LOAN GL =====*/
	IF NEW.loan_id > 0 THEN
		INSERT INTO erp_gl_trans (
			tran_type,
			tran_no,
			tran_date,
			sectionid,
			account_code,
			narrative,
			amount,
			reference_no,
			description,
			biller_id,
			created_by,
			bank,
			updated_by)
			SELECT
			'LOANS',
			v_tran_no,
			NEW.date,
			erp_gl_sections.sectionid,
			erp_gl_charts.accountcode,
			erp_gl_charts.accountname,
			(- 1) * abs(NEW.amount),
			(
				SELECT reference_no FROM erp_loans WHERE id = NEW.loan_id
			),
			NEW.note,
			NEW.biller_id,
			NEW.created_by,
			'1',
			(
				SELECT updated_by FROM erp_loans WHERE id = NEW.loan_id
			)
			FROM
				erp_account_settings
				INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = v_default_loan
				INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
			WHERE
				erp_gl_charts.accountcode = v_default_loan;

		INSERT INTO erp_gl_trans (
			tran_type,
			tran_no,
			tran_date,
			sectionid,
			account_code,
			narrative,
			amount,
			reference_no,
			description,
			biller_id,
			created_by,
			bank,
			updated_by)
			SELECT
			'LOANS',
			v_tran_no,
			NEW.date,
			erp_gl_sections.sectionid,
			erp_gl_charts.accountcode,
			erp_gl_charts.accountname,
			NEW.amount,
			(
				SELECT reference_no FROM erp_loans WHERE id = NEW.loan_id
			),
			NEW.note,
			NEW.biller_id,
			NEW.created_by,
			'1',
			(
				SELECT updated_by FROM erp_loans WHERE id = NEW.loan_id
			)
			FROM
				erp_account_settings
				INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = v_bank_code
				INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
			WHERE
				erp_gl_charts.accountcode = v_bank_code;
	END IF;

/* ==== PURCHASE GL =====*/
	IF NEW.purchase_id>0 THEN
	
		IF NEW.paid_by = "deposit" THEN
			SET v_referece = (SELECT reference_no FROM erp_purchases WHERE id = NEW.purchase_id);
		ELSE
			SET v_referece = NEW.reference_no;
		END IF;
		
		SET v_tran_no = (SELECT erp_gl_trans.tran_no 
				FROM erp_gl_trans 
				WHERE reference_no = v_referece AND bank = 1 LIMIT 0,1);
		
		DELETE FROM erp_gl_trans WHERE reference_no = v_referece AND bank = 1 AND payment_id = NEW.id;

		INSERT INTO erp_gl_trans (
			tran_type,
			tran_no,
			tran_date,
			sectionid,
			account_code,
			narrative,
			amount,
			reference_no,
			description,
			biller_id,
			created_by,
			bank,
			updated_by)
			SELECT
			'PURCHASES',
			v_tran_no,
			NEW.date,
			erp_gl_sections.sectionid,
			erp_gl_charts.accountcode,
			erp_gl_charts.accountname,
			NEW.amount,
			v_referece,
			(
				SELECT supplier FROM erp_purchases WHERE id = NEW.purchase_id
			),
			NEW.biller_id,
			NEW.created_by,
			'1',
			(
				SELECT updated_by FROM erp_purchases WHERE id = NEW.purchase_id
			)
			FROM
				erp_account_settings
				INNER JOIN erp_gl_charts
				ON erp_gl_charts.accountcode = v_default_payable
				INNER JOIN erp_gl_sections
				ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
			WHERE
				erp_gl_charts.accountcode = v_default_payable;

		INSERT INTO erp_gl_trans (
			tran_type,
			tran_no,
			tran_date,
			sectionid,
			account_code,
			narrative,
			amount,
			reference_no,
			description,
			biller_id,
			created_by,
			bank,
			updated_by)
			SELECT
			'PURCHASES',
			v_tran_no,
			NEW.date,
			erp_gl_sections.sectionid,
			erp_gl_charts.accountcode,
			erp_gl_charts.accountname,
			(-1)*abs(NEW.amount),
			v_referece,
			(
				SELECT supplier FROM erp_purchases WHERE id = NEW.purchase_id
			),
			NEW.biller_id,
			NEW.created_by,
			'1',
			(
				SELECT updated_by FROM erp_purchases WHERE id = NEW.purchase_id
			)
			FROM
				erp_account_settings
				INNER JOIN erp_gl_charts
				ON erp_gl_charts.accountcode = v_bank_code
				INNER JOIN erp_gl_sections
				ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
			WHERE
				erp_gl_charts.accountcode = v_bank_code;
	END IF;

END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `gl_trans_purchase_insert`;
DELIMITER ;;
CREATE TRIGGER `gl_trans_purchase_insert` AFTER INSERT ON `erp_purchases` FOR EACH ROW BEGIN
	DECLARE v_tran_no INTEGER;
	DECLARE v_tran_date DATETIME;
	DECLARE v_acc_cate_separate INTEGER;

	SET v_acc_cate_separate = (SELECT erp_settings.acc_cate_separate FROM erp_settings WHERE erp_settings.setting_id = '1');

	IF NEW.status="received" AND NEW.total > 0 THEN

		SET v_tran_date = (SELECT erp_purchases.date 
				FROM erp_payments 
				INNER JOIN erp_purchases ON erp_purchases.id = erp_payments.purchase_id
				WHERE erp_purchases.id = NEW.id LIMIT 0,1);

		IF v_tran_date = NEW.date THEN
			SET v_tran_no = (SELECT MAX(tran_no) + 1 FROM erp_gl_trans);
		ELSE
			SET v_tran_no = (SELECT COALESCE(MAX(tran_no),0) +1 FROM erp_gl_trans);
			
			UPDATE erp_order_ref
			SET tr = v_tran_no
			WHERE
			DATE_FORMAT(date, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m');
		END IF;

		IF NEW.type_of_po="po"  THEN

			IF NEW.opening_ap != 1 THEN
			
				IF  v_acc_cate_separate <> 1 THEN
				
					IF NEW.cogs <> 0 THEN
					
						INSERT INTO erp_gl_trans (
							tran_type,
							tran_no,
							tran_date,
							sectionid,
							account_code,
							narrative,
							amount,
							reference_no,
							description,
							biller_id,
							created_by,
							updated_by
						) SELECT
							'PURCHASES',
							v_tran_no,
							NEW.date,
							erp_gl_sections.sectionid,
							erp_gl_charts.accountcode,
							erp_gl_charts.accountname,
							NEW.cogs,
							NEW.reference_no,
							NEW.note,
							NEW.biller_id,
							NEW.created_by,
							NEW.updated_by
						FROM
							erp_account_settings
							INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_account_settings.default_stock
							INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
						WHERE
							erp_gl_charts.accountcode = erp_account_settings.default_stock;
						
						INSERT INTO erp_gl_trans (
							tran_type,
							tran_no,
							tran_date,
							sectionid,
							account_code,
							narrative,
							amount,
							reference_no,
							description,
							biller_id,
							created_by,
							updated_by
						) SELECT
							'PURCHASES',
							v_tran_no,
							NEW.date,
							erp_gl_sections.sectionid,
							erp_gl_charts.accountcode,
							erp_gl_charts.accountname,
							(-1) * NEW.cogs,
							NEW.reference_no,
							NEW.note,
							NEW.biller_id,
							NEW.created_by,
							NEW.updated_by
						FROM
							erp_account_settings
							INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_account_settings.default_cost
							INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
						WHERE
							erp_gl_charts.accountcode = erp_account_settings.default_cost;
						
					END IF;

					INSERT INTO erp_gl_trans (
						tran_type,
						tran_no,
						tran_date,
						sectionid,
						account_code,
						narrative,
						amount,
						reference_no,
						description,
						biller_id,
						created_by,
						updated_by
					) SELECT
						'PURCHASES',
						v_tran_no,
						NEW.date,
						erp_gl_sections.sectionid,
						erp_gl_charts.accountcode,
						erp_gl_charts.accountname,
						NEW.total + NEW.shipping,
						NEW.reference_no,
						NEW.note,
						NEW.biller_id,
						NEW.created_by,
						NEW.updated_by
					FROM
						erp_account_settings
						INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_account_settings.default_purchase
						INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
					WHERE
						erp_gl_charts.accountcode = erp_account_settings.default_purchase;
					
				END IF;
			
				INSERT INTO erp_gl_trans (
					tran_type,
					tran_no,
					tran_date,
					sectionid,
					account_code,
					narrative,
					amount,
					reference_no,
					description,
					biller_id,
					created_by,
					updated_by
				) SELECT
					'PURCHASES',
					v_tran_no,
					NEW.date,
					erp_gl_sections.sectionid,
					erp_gl_charts.accountcode,
					erp_gl_charts.accountname,
					(-1) * abs(NEW.grand_total),
					NEW.reference_no,
					NEW.note,
					NEW.biller_id,
					NEW.created_by,
					NEW.updated_by
				FROM
					erp_account_settings
					INNER JOIN erp_gl_charts
					ON erp_gl_charts.accountcode = erp_account_settings.default_payable
					INNER JOIN erp_gl_sections
					ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
				WHERE
					erp_gl_charts.accountcode = erp_account_settings.default_payable;
			
			END IF;
			
			IF NEW.order_discount THEN
				INSERT INTO erp_gl_trans (
					tran_type,
					tran_no,
					tran_date,
					sectionid,
					account_code,
					narrative,
					amount,
					reference_no,
					description,
					biller_id,
					created_by,
					updated_by
					) SELECT
					'PURCHASES',
					v_tran_no,
					NEW.date,
					erp_gl_sections.sectionid,
					erp_gl_charts.accountcode,
					erp_gl_charts.accountname,
					(-1) * abs(NEW.order_discount),

					NEW.reference_no,
					NEW.note,
					NEW.biller_id,
					NEW.created_by,
					NEW.updated_by
					FROM
						erp_account_settings
						INNER JOIN erp_gl_charts
						ON erp_gl_charts.accountcode = erp_account_settings.default_purchase_discount
						INNER JOIN erp_gl_sections

						ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
					WHERE
						erp_gl_charts.accountcode = erp_account_settings.default_purchase_discount;
			END IF;

			IF NEW.order_tax THEN
				INSERT INTO erp_gl_trans (
					tran_type,
					tran_no,
					tran_date,
					sectionid,
					account_code,
					narrative,
					amount,
					reference_no,
					description,
					biller_id,
					created_by,
					updated_by
					) SELECT
					'PURCHASES',
					v_tran_no,
					NEW.date,
					erp_gl_sections.sectionid,
					erp_gl_charts.accountcode,
					erp_gl_charts.accountname,
					NEW.order_tax,
					NEW.reference_no,
					NEW.note,
					NEW.biller_id,
					NEW.created_by,
					NEW.updated_by
					FROM
						erp_account_settings
						INNER JOIN erp_gl_charts
						ON erp_gl_charts.accountcode = erp_account_settings.default_purchase_tax
						INNER JOIN erp_gl_sections
						ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
					WHERE
						erp_gl_charts.accountcode = erp_account_settings.default_purchase_tax;
			END IF;
		
		END IF;
		
		IF NEW.opening_ap = 1 THEN
			
			INSERT INTO erp_gl_trans (
				tran_type,
				tran_no,
				tran_date,
				sectionid,
				account_code,
				narrative,
				amount,
				reference_no,
				description,
				biller_id,
				created_by,
				updated_by
				) SELECT
				'PURCHASES',
				v_tran_no,
				NEW.date,
				erp_gl_sections.sectionid,
				erp_gl_charts.accountcode,
				erp_gl_charts.accountname,
				abs(NEW.grand_total),
				NEW.reference_no,
				NEW.note,
				NEW.biller_id,
				NEW.created_by,
				NEW.updated_by
				FROM
					erp_account_settings
					INNER JOIN erp_gl_charts
					ON erp_gl_charts.accountcode = erp_account_settings.default_open_balance
					INNER JOIN erp_gl_sections
					ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
				WHERE
					erp_gl_charts.accountcode = erp_account_settings.default_open_balance;

			INSERT INTO erp_gl_trans (
				tran_type,
				tran_no,
				tran_date,
				sectionid,
				account_code,
				narrative,
				amount,
				reference_no,
				description,
				biller_id,
				created_by,
				updated_by
				) SELECT
				'PURCHASES',
				v_tran_no,
				NEW.date,
				erp_gl_sections.sectionid,
				erp_gl_charts.accountcode,
				erp_gl_charts.accountname,
				(-1) * abs(NEW.grand_total),
				NEW.reference_no,
				NEW.note,
				NEW.biller_id,
				NEW.created_by,
				NEW.updated_by
				FROM
					erp_account_settings
					INNER JOIN erp_gl_charts
					ON erp_gl_charts.accountcode = erp_account_settings.default_payable
					INNER JOIN erp_gl_sections
					ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
				WHERE
					erp_gl_charts.accountcode = erp_account_settings.default_payable;
			
		END IF;

		
	END IF;

END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `audit_purchases_update`;
DELIMITER ;;
CREATE TRIGGER `audit_purchases_update` BEFORE UPDATE ON `erp_purchases` FOR EACH ROW BEGIN
	IF OLD.id THEN
		INSERT INTO erp_purchases_audit (
			id,
			biller_id,
			reference_no,
			date,
			supplier_id,
			supplier,
			warehouse_id,
			note,
			total,
			product_discount,
			order_discount_id,
			order_discount,
			total_discount,
			product_tax,
			order_tax_id,
			order_tax,
			total_tax,
			shipping,
			grand_total,
			paid,
			status,
			payment_status,
			created_by,
			updated_by,
			updated_at,
			attachment,
			payment_term,
			due_date,
			return_id,
			surcharge,
			suspend_note,
			reference_no_tax,
			tax_status,
			opening_ap,
			type_of_po,
			order_ref,
			request_ref,
			paid_by,
			order_id,
			account_code,
			pur_refer,
			purchase_type,
			purchase_status,
			tax_type,
			item_status,
			container_no,
			container_size,
			invoice_no,
			order_reference_no,
			good_or_services,
			acc_cate_separate,
			audit_type
		) SELECT 
			id,
			biller_id,
			reference_no,
			date,
			supplier_id,
			supplier,
			warehouse_id,
			note,
			total,
			product_discount,
			order_discount_id,
			order_discount,
			total_discount,
			product_tax,
			order_tax_id,
			order_tax,
			total_tax,
			shipping,
			grand_total,
			paid,
			status,
			payment_status,
			created_by,
			updated_by,
			updated_at,
			attachment,
			payment_term,
			due_date,
			return_id,
			surcharge,
			suspend_note,
			reference_no_tax,
			tax_status,
			opening_ap,
			type_of_po,
			order_ref,
			request_ref,
			paid_by,
			order_id,
			account_code,
			pur_refer,
			purchase_type,
			purchase_status,
			tax_type,
			item_status,
			container_no,
			container_size,
			invoice_no,
			order_reference_no,
			good_or_services,
			acc_cate_separate,
			"UPDATED"
		FROM
			erp_purchases
		WHERE
			erp_purchases.id = OLD.id;
	END IF;

END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `gl_trans_purchase_update`;
DELIMITER ;;
CREATE TRIGGER `gl_trans_purchase_update` AFTER UPDATE ON `erp_purchases` FOR EACH ROW BEGIN
	DECLARE v_tran_no INTEGER;
    DECLARE v_acc_cate_separate INTEGER;
	DECLARE v_tran_date DATETIME;

	SET v_acc_cate_separate = (SELECT erp_settings.acc_cate_separate FROM erp_settings WHERE erp_settings.setting_id = '1');

	IF NEW.type_of_po = "po" THEN
	
		IF NEW.updated_by > 0 AND NEW.updated_count <> OLD.updated_count THEN
	
			DELETE FROM erp_gl_trans WHERE tran_type = 'PURCHASES' AND reference_no = NEW.reference_no;
		
		END IF;

		IF NEW.STATUS = "received" AND NEW.total > 0 AND NEW.updated_by > 0 AND NEW.updated_count <> OLD.updated_count AND ISNULL(NEW.return_id) THEN

			SET v_tran_no = (
				SELECT
					tran_no
				FROM
					erp_gl_trans
				WHERE
					tran_type = 'PURCHASES'
				AND reference_no = NEW.reference_no
				LIMIT 0,
				1
			);

			IF v_tran_no < 1 OR ISNULL(v_tran_no) THEN

				SET v_tran_no = (
					SELECT
						COALESCE (MAX(tran_no), 0) + 1
					FROM
						erp_gl_trans
				);

				UPDATE erp_order_ref
					SET tr = v_tran_no
				WHERE
					DATE_FORMAT(date, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m');

			END IF;

			IF NEW.opening_ap != 1 THEN
			
				IF  v_acc_cate_separate <> 1 THEN
				
					IF NEW.cogs <> 0 THEN
					
						INSERT INTO erp_gl_trans (
							tran_type,
							tran_no,
							tran_date,
							sectionid,
							account_code,
							narrative,
							amount,
							reference_no,
							description,
							biller_id,
							created_by,
							updated_by
						) SELECT
							'PURCHASES',
							v_tran_no,
							NEW.date,
							erp_gl_sections.sectionid,
							erp_gl_charts.accountcode,
							erp_gl_charts.accountname,
							NEW.cogs,
							NEW.reference_no,
							NEW.note,
							NEW.biller_id,
							NEW.created_by,
							NEW.updated_by
						FROM
							erp_account_settings
							INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_account_settings.default_stock
							INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
						WHERE
							erp_gl_charts.accountcode = erp_account_settings.default_stock;
						
						INSERT INTO erp_gl_trans (
							tran_type,
							tran_no,
							tran_date,
							sectionid,
							account_code,
							narrative,
							amount,
							reference_no,
							description,
							biller_id,
							created_by,
							updated_by
						) SELECT
							'PURCHASES',
							v_tran_no,
							NEW.date,
							erp_gl_sections.sectionid,
							erp_gl_charts.accountcode,
							erp_gl_charts.accountname,
							(-1) * NEW.cogs,
							NEW.reference_no,
							NEW.note,
							NEW.biller_id,
							NEW.created_by,
							NEW.updated_by
						FROM
							erp_account_settings
							INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_account_settings.default_cost
							INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
						WHERE
							erp_gl_charts.accountcode = erp_account_settings.default_cost;
						
					END IF;
				
					INSERT INTO erp_gl_trans (
						tran_type,
						tran_no,
						tran_date,
						sectionid,
						account_code,
						narrative,
						amount,
						reference_no,
						description,
						biller_id,
						created_by,
						updated_by
					) SELECT
						'PURCHASES',
						v_tran_no,
						NEW.date,
						erp_gl_sections.sectionid,
						erp_gl_charts.accountcode,
						erp_gl_charts.accountname,
						NEW.total + NEW.shipping,
						NEW.reference_no,
						NEW.note,
						NEW.biller_id,
						NEW.created_by,
						NEW.updated_by
					FROM
						erp_account_settings
					INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_account_settings.default_purchase
					INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
					WHERE
						erp_gl_charts.accountcode = erp_account_settings.default_purchase;
				END IF;
				
				INSERT INTO erp_gl_trans (
					tran_type,
					tran_no,
					tran_date,
					sectionid,
					account_code,
					narrative,
					amount,
					reference_no,
					description,
					biller_id,
					created_by,
					updated_by
				) SELECT
					'PURCHASES',
					v_tran_no,
					NEW.date,
					erp_gl_sections.sectionid,
					erp_gl_charts.accountcode,
					erp_gl_charts.accountname,
					(- 1) * abs(NEW.grand_total),
					NEW.reference_no,
					NEW.note,
					NEW.biller_id,
					NEW.created_by,
					NEW.updated_by
				FROM
					erp_account_settings
				INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_account_settings.default_payable
				INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
				WHERE
					erp_gl_charts.accountcode = erp_account_settings.default_payable;

				IF NEW.order_discount THEN
				
					INSERT INTO erp_gl_trans (
						tran_type,
						tran_no,
						tran_date,
						sectionid,
						account_code,
						narrative,
						amount,
						reference_no,
						description,
						biller_id,
						created_by,
						updated_by
					) SELECT
						'PURCHASES',
						v_tran_no,
						NEW.date,
						erp_gl_sections.sectionid,
						erp_gl_charts.accountcode,
						erp_gl_charts.accountname,
						(- 1) * abs(NEW.order_discount),
						NEW.reference_no,
						NEW.note,
						NEW.biller_id,
						NEW.created_by,
						NEW.updated_by
					FROM
						erp_account_settings
					INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_account_settings.default_purchase_discount
					INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
					WHERE
						erp_gl_charts.accountcode = erp_account_settings.default_purchase_discount;


				END IF;

				IF NEW.order_tax THEN
					INSERT INTO erp_gl_trans (
						tran_type,
						tran_no,
						tran_date,
						sectionid,
						account_code,
						narrative,
						amount,
						reference_no,
						description,
						biller_id,
						created_by,
						updated_by
					) SELECT
						'PURCHASES',
						v_tran_no,
						NEW.date,
						erp_gl_sections.sectionid,
						erp_gl_charts.accountcode,
						erp_gl_charts.accountname,
						NEW.order_tax,
						NEW.reference_no,
						NEW.note,
						NEW.biller_id,
						NEW.created_by,
						NEW.updated_by
					FROM
						erp_account_settings
					INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_account_settings.default_purchase_tax
					INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
					WHERE
						erp_gl_charts.accountcode = erp_account_settings.default_purchase_tax;


				END IF;

			END IF;
			
			IF NEW.opening_ap = 1 THEN
				
				INSERT INTO erp_gl_trans (
					tran_type,
					tran_no,
					tran_date,
					sectionid,
					account_code,
					narrative,
					amount,
					reference_no,
					description,
					biller_id,
					created_by,
					updated_by
					) SELECT
					'PURCHASES',
					v_tran_no,
					NEW.date,
					erp_gl_sections.sectionid,
					erp_gl_charts.accountcode,
					erp_gl_charts.accountname,
					abs(NEW.grand_total),
					NEW.reference_no,
					NEW.note,
					NEW.biller_id,
					NEW.created_by,
					NEW.updated_by
					FROM
						erp_account_settings
						INNER JOIN erp_gl_charts
						ON erp_gl_charts.accountcode = erp_account_settings.default_open_balance
						INNER JOIN erp_gl_sections
						ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
					WHERE
						erp_gl_charts.accountcode = erp_account_settings.default_open_balance;

				INSERT INTO erp_gl_trans (
					tran_type,
					tran_no,
					tran_date,
					sectionid,
					account_code,
					narrative,
					amount,
					reference_no,
					description,
					biller_id,
					created_by,
					updated_by
					) SELECT
					'PURCHASES',
					v_tran_no,
					NEW.date,
					erp_gl_sections.sectionid,
					erp_gl_charts.accountcode,
					erp_gl_charts.accountname,
					(-1) * abs(NEW.grand_total),
					NEW.reference_no,
					NEW.note,
					NEW.biller_id,
					NEW.created_by,
					NEW.updated_by
					FROM
						erp_account_settings
						INNER JOIN erp_gl_charts
						ON erp_gl_charts.accountcode = erp_account_settings.default_payable
						INNER JOIN erp_gl_sections
						ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
					WHERE
						erp_gl_charts.accountcode = erp_account_settings.default_payable;
				
			END IF;
			
		END IF;
		
	END IF;

END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `audit_purchases_delete`;
DELIMITER ;;
CREATE TRIGGER `audit_purchases_delete` BEFORE DELETE ON `erp_purchases` FOR EACH ROW BEGIN
	IF OLD.id THEN
		INSERT INTO erp_purchases_audit (
			id,
			biller_id,
			reference_no,
			date,
			supplier_id,
			supplier,
			warehouse_id,
			note,
			total,
			product_discount,
			order_discount_id,
			order_discount,
			total_discount,
			product_tax,
			order_tax_id,
			order_tax,
			total_tax,
			shipping,
			grand_total,
			paid,
			status,
			payment_status,
			created_by,
			updated_by,
			updated_at,
			attachment,
			payment_term,
			due_date,
			return_id,
			surcharge,
			suspend_note,
			reference_no_tax,
			tax_status,
			opening_ap,
			type_of_po,
			order_ref,
			request_ref,
			paid_by,
			order_id,
			account_code,
			pur_refer,
			purchase_type,
			purchase_status,
			tax_type,
			item_status,
			container_no,
			container_size,
			invoice_no,
			order_reference_no,
			good_or_services,
			acc_cate_separate,
			audit_type
		) SELECT 
			id,
			biller_id,
			reference_no,
			date,
			supplier_id,
			supplier,
			warehouse_id,
			note,
			total,
			product_discount,
			order_discount_id,
			order_discount,
			total_discount,
			product_tax,
			order_tax_id,
			order_tax,
			total_tax,
			shipping,
			grand_total,
			paid,
			status,
			payment_status,
			created_by,
			updated_by,
			updated_at,
			attachment,
			payment_term,
			due_date,
			return_id,
			surcharge,
			suspend_note,
			reference_no_tax,
			tax_status,
			opening_ap,
			type_of_po,
			order_ref,
			request_ref,
			paid_by,
			order_id,
			account_code,
			pur_refer,
			purchase_type,
			purchase_status,
			tax_type,
			item_status,
			container_no,
			container_size,
			invoice_no,
			order_reference_no,
			good_or_services,
			acc_cate_separate,
			"DELETED"
		FROM
			erp_purchases
		WHERE
			erp_purchases.id = OLD.id;
	END IF;

END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `gl_trans_purchase_delete`;
DELIMITER ;;
CREATE TRIGGER `gl_trans_purchase_delete` AFTER DELETE ON `erp_purchases` FOR EACH ROW BEGIN

   UPDATE erp_gl_trans SET amount = 0, description = CONCAT(description,' (Cancelled)')
   WHERE reference_no = OLD.reference_no;
END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `audit_purchases_order_update`;
DELIMITER ;;
CREATE TRIGGER `audit_purchases_order_update` BEFORE UPDATE ON `erp_purchases_order` FOR EACH ROW BEGIN
	IF OLD.id THEN
		INSERT INTO erp_purchases_order_audit (
			id,
			biller_id,
			reference_no,
			purchase_ref,
			date,
			supplier_id,
			supplier,
			warehouse_id,
			note,
			total,
			product_discount,
			order_discount_id,
			order_discount,
			total_discount,
			product_tax,
			order_tax_id,
			order_tax,
			total_tax,
			shipping,
			grand_total,
			paid,
			status,
			payment_status,
			created_by,
			updated_by,
			updated_at,
			attachment,
			payment_term,
			due_date,
			return_id,
			surcharge,
			suspend_note,
			reference_no_tax,
			tax_status,
			purchase_type,
			purchase_status,
			tax_type,
			order_status,
			request_id,
			audit_type
		) SELECT 
			id,
			biller_id,
			reference_no,
			purchase_ref,
			date,
			supplier_id,
			supplier,
			warehouse_id,
			note,
			total,
			product_discount,
			order_discount_id,
			order_discount,
			total_discount,
			product_tax,
			order_tax_id,
			order_tax,
			total_tax,
			shipping,
			grand_total,
			paid,
			status,
			payment_status,
			created_by,
			updated_by,
			updated_at,
			attachment,
			payment_term,
			due_date,
			return_id,
			surcharge,
			suspend_note,
			reference_no_tax,
			tax_status,
			purchase_type,
			purchase_status,
			tax_type,
			order_status,
			request_id,
			"UPDATED"
		FROM
			erp_purchases_order
		WHERE
			erp_purchases_order.id = OLD.id;
	END IF;

END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `audit_purchases_order_delete`;
DELIMITER ;;
CREATE TRIGGER `audit_purchases_order_delete` BEFORE DELETE ON `erp_purchases_order` FOR EACH ROW BEGIN
	IF OLD.id THEN
		INSERT INTO erp_purchases_order_audit (
			id,
			biller_id,
			reference_no,
			purchase_ref,
			date,
			supplier_id,
			supplier,
			warehouse_id,
			note,
			total,
			product_discount,
			order_discount_id,
			order_discount,
			total_discount,
			product_tax,
			order_tax_id,
			order_tax,
			total_tax,
			shipping,
			grand_total,
			paid,
			status,
			payment_status,
			created_by,
			updated_by,
			updated_at,
			attachment,
			payment_term,
			due_date,
			return_id,
			surcharge,
			suspend_note,
			reference_no_tax,
			tax_status,
			purchase_type,
			purchase_status,
			tax_type,
			order_status,
			request_id,
			audit_type
		) SELECT 
			id,
			biller_id,
			reference_no,
			purchase_ref,
			date,
			supplier_id,
			supplier,
			warehouse_id,
			note,
			total,
			product_discount,
			order_discount_id,
			order_discount,
			total_discount,
			product_tax,
			order_tax_id,
			order_tax,
			total_tax,
			shipping,
			grand_total,
			paid,
			status,
			payment_status,
			created_by,
			updated_by,
			updated_at,
			attachment,
			payment_term,
			due_date,
			return_id,
			surcharge,
			suspend_note,
			reference_no_tax,
			tax_status,
			purchase_type,
			purchase_status,
			tax_type,
			order_status,
			request_id,
			"DELETED"
		FROM
			erp_purchases_order
		WHERE
			erp_purchases_order.id = OLD.id;
	END IF;

END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `gl_trans_purchase_items_insert`;
DELIMITER ;;
CREATE TRIGGER `gl_trans_purchase_items_insert` AFTER INSERT ON `erp_purchase_items` FOR EACH ROW BEGIN
	DECLARE v_reference_no VARCHAR(55);
	DECLARE v_tran_no INTEGER;
	DECLARE v_date DATETIME;
	DECLARE v_biller_id INTEGER;
	DECLARE v_supplier VARCHAR(55);
	DECLARE v_created_by INTEGER;
	DECLARE v_updated_by INTEGER;
	DECLARE v_cost DECIMAL(4);
	DECLARE v_status VARCHAR(50);
                  DECLARE v_status_tr VARCHAR(50);
	DECLARE v_category_id INTEGER;
	DECLARE v_qoh INTEGER;
	DECLARE v_updated_at DATETIME;
	DECLARE v_transaction_id INTEGER;
	DECLARE v_acc_cate_separate INTEGER;
	DECLARE v_transaction_type VARCHAR(25);
	DECLARE v_tran_note VARCHAR(255);
	DECLARE v_chk_acc  INTEGER;

	SET v_acc_cate_separate = (SELECT erp_settings.acc_cate_separate FROM erp_settings WHERE erp_settings.setting_id = '1');
	SET v_transaction_id = NEW.transaction_id;
	SET v_transaction_type = NEW.transaction_type;

	IF NEW.product_id THEN

		IF NEW.transaction_id THEN 
			
			/* SALE */
			IF v_transaction_type = 'SALE' OR v_transaction_type = 'DELIVERY' THEN

				SET v_cost = (SELECT erp_products.cost FROM erp_products WHERE erp_products.id = NEW.product_id);
				SET v_category_id = (SELECT erp_products.category_id FROM erp_products WHERE erp_products.id = NEW.product_id);
				SET v_qoh =  (SELECT erp_products.quantity FROM erp_products WHERE erp_products.id = NEW.product_id);
			
				SET v_date = (SELECT
					erp_sales.date
				FROM
					erp_sales
					JOIN erp_sale_items ON erp_sales.id = erp_sale_items.sale_id
				WHERE
					erp_sale_items.id = v_transaction_id
				LIMIT 0,1);
														   
				SET v_reference_no = (SELECT
							erp_sales.reference_no
						FROM
							erp_sales
							JOIN erp_sale_items ON erp_sales.id = erp_sale_items.sale_id
						WHERE
							erp_sale_items.id = v_transaction_id
						LIMIT 0,1);
				SET v_supplier = (SELECT
							erp_sales.customer
						FROM
							erp_sales
							JOIN erp_sale_items ON erp_sales.id = erp_sale_items.sale_id
						WHERE
							erp_sale_items.id = v_transaction_id
						LIMIT 0,1);
				SET v_biller_id = (SELECT
							erp_sales.biller_id
						FROM
							erp_sales
							JOIN erp_sale_items ON erp_sales.id = erp_sale_items.sale_id
						WHERE
							erp_sale_items.id = v_transaction_id
						LIMIT 0,1);
				SET v_created_by = (SELECT
							erp_sales.created_by
						FROM
							erp_sales
							JOIN erp_sale_items ON erp_sales.id = erp_sale_items.sale_id
						WHERE
							erp_sale_items.id = v_transaction_id
						LIMIT 0,1);
				SET v_updated_by = (SELECT
							erp_sales.updated_by
						FROM
							erp_sales
							JOIN erp_sale_items ON erp_sales.id = erp_sale_items.sale_id
						WHERE
							erp_sale_items.id = v_transaction_id
						LIMIT 0,1);
				SET v_status = (SELECT
							erp_sales.sale_status
						FROM
							erp_sales
							JOIN erp_sale_items ON erp_sales.id = erp_sale_items.sale_id
						WHERE
							erp_sale_items.id = v_transaction_id
						LIMIT 0,1);
                                                                          SET v_status_tr = (SELECT
							erp_deliveries.delivery_status
						FROM
							erp_deliveries
							JOIN erp_sale_items ON erp_deliveries.sale_id = erp_sale_items.sale_id
						WHERE
							erp_sale_items.id = v_transaction_id
						LIMIT 0,1);
				SET v_updated_at = (SELECT
							erp_sales.updated_at
						FROM
							erp_sales
							JOIN erp_sale_items ON erp_sales.id = erp_sale_items.sale_id
						WHERE
							erp_sale_items.id = v_transaction_id
						LIMIT 0,1);
						
				IF v_status = 'completed' THEN
					INSERT INTO erp_inventory_valuation_details (
						type,
						biller_id,
						product_id,
						product_code,
						product_name,
						category_id,
						reference_no,
						date,
						NAME,
						quantity,
						warehouse_id,
						cost,
						qty_on_hand,
						avg_cost,
						created_by,
						updated_by,
						updated_at,
						field_id
						)
						VALUES
						(
						v_transaction_type,
						v_biller_id,
						NEW.product_id,
						NEW.product_code,
						NEW.product_name,
						v_category_id,
						v_reference_no,
						v_date ,
						v_supplier,
						(-1)*NEW.quantity_balance,
						NEW.warehouse_id,
						v_cost,
						v_qoh,
						v_cost,
						v_created_by,
						v_updated_by,
						v_updated_at,
						v_transaction_id
					);
				END IF;

			END IF;
		END IF;
		/* End SALE */

                                    /* DELIVERY */
		IF NEW.transaction_type = 'DELIVERY' THEN
		
			SET v_tran_no = (SELECT MAX(tran_no) FROM erp_gl_trans);
			SET v_cost = (SELECT erp_products.cost FROM erp_products WHERE erp_products.id = NEW.product_id);
			SET v_category_id  = (SELECT erp_products.category_id FROM erp_products WHERE erp_products.id = NEW.product_id);
			SET v_qoh =  (SELECT erp_products.quantity FROM erp_products WHERE erp_products.id = NEW.product_id);
			

			SET v_date = (SELECT
				erp_purchases.date
			FROM
				erp_purchases
				JOIN erp_purchase_items ON erp_purchases.id = erp_purchase_items.purchase_id
			WHERE
				erp_purchase_items.id = v_transaction_id
			LIMIT 0,1);
			
			SET v_reference_no = (SELECT
	                                                                                            erp_deliveries.do_reference_no FROM
                                                                                                    erp_deliveries
					WHERE
						erp_deliveries.id = NEW.delivery_id
					LIMIT 0,1);
			SET v_supplier = (SELECT
	                                                                                            erp_deliveries.customer FROM
                                                                                                    erp_deliveries
					WHERE
						erp_deliveries.id = NEW.delivery_id
					LIMIT 0,1);

			SET v_biller_id  = (SELECT
						erp_deliveries.biller_id
					FROM
						erp_deliveries
					WHERE
						erp_deliveries.id =  NEW.delivery_id
					LIMIT 0,1);
			SET v_created_by = (SELECT
						erp_deliveries.created_by
					FROM
						erp_deliveries
					WHERE
						erp_deliveries.id =NEW.delivery_id
					LIMIT 0,1);

			SET v_updated_by = (SELECT
						erp_deliveries.updated_by
					FROM
						erp_deliveries
					WHERE
						erp_deliveries.id =NEW.delivery_id
					LIMIT 0,1);

			SET v_updated_at = (SELECT
						erp_deliveries.updated_at
					FROM
						erp_deliveries
					WHERE
						erp_deliveries.id =NEW.delivery_id
					LIMIT 0,1);

			SET v_status = (SELECT
						erp_deliveries.delivery_status
					FROM
						erp_deliveries
					WHERE
						erp_deliveries.id =NEW.delivery_id
					LIMIT 0,1);

			SET v_tran_note = (SELECT
						erp_deliveries.note
					FROM
						erp_deliveries
					WHERE
						erp_deliveries.id =NEW.delivery_id
					LIMIT 0,1);
			IF v_status = 'completed' THEN	
				INSERT INTO erp_inventory_valuation_details (
						type,
						biller_id,
						product_id,
						product_code,
						product_name,
						category_id,
						reference_no,
						date,
						NAME,
						quantity,
						warehouse_id,
						cost,
						qty_on_hand,
						avg_cost,
						created_by,
						updated_by,
						updated_at,
						field_id
						)
						VALUES
						(
						v_transaction_type,
						v_biller_id,
						NEW.product_id,
						NEW.product_code,
						NEW.product_name,
						v_category_id,
						v_reference_no,
						v_date ,
						v_supplier,
						(-1)*NEW.quantity_balance,
						NEW.warehouse_id,
						v_cost,
						v_qoh,
						v_cost,
						v_created_by,
						v_updated_by,
						v_updated_at,
						v_transaction_id
					);
			            END IF;			
			  END IF;
                                      /* DELIVERY */
			
		/* SALE RETURN */
		IF v_transaction_type = 'SALE RETURN' THEN
			SET v_cost = (SELECT erp_products.cost FROM erp_products WHERE erp_products.id = NEW.product_id);
			SET v_category_id = (SELECT erp_products.category_id FROM erp_products WHERE erp_products.id = NEW.product_id);
			SET v_qoh =  (SELECT erp_products.quantity FROM erp_products WHERE erp_products.id = NEW.product_id);
		
			SET v_date = (SELECT
				erp_return_sales.date
			FROM
				erp_return_sales
				JOIN erp_return_items ON erp_return_sales.id = erp_return_items.return_id
			WHERE
				erp_return_items.id = v_transaction_id
			LIMIT 0,1);
			SET v_reference_no = (SELECT
						erp_return_sales.reference_no
					FROM
						erp_return_sales
						JOIN erp_return_items ON erp_return_sales.id = erp_return_items.return_id
					WHERE
						erp_return_items.id = v_transaction_id
					LIMIT 0,1);
			SET v_supplier = (SELECT
						erp_return_sales.customer
					FROM
						erp_return_sales
						JOIN erp_return_items ON erp_return_sales.id = erp_return_items.return_id
					WHERE
						erp_return_items.id = v_transaction_id
					LIMIT 0,1);
			SET v_biller_id = (SELECT
						erp_return_sales.biller_id
					FROM
						erp_return_sales
						JOIN erp_return_items ON erp_return_sales.id = erp_return_items.return_id
					WHERE
						erp_return_items.id = v_transaction_id
					LIMIT 0,1);
			SET v_created_by = (SELECT
						erp_return_sales.created_by
					FROM
						erp_return_sales
						JOIN erp_return_items ON erp_return_sales.id = erp_return_items.return_id
					WHERE
						erp_return_items.id = v_transaction_id
					LIMIT 0,1);
			SET v_updated_by = (SELECT
						erp_return_sales.updated_by
					FROM
						erp_return_sales
						JOIN erp_return_items ON erp_return_sales.id = erp_return_items.return_id
					WHERE
						erp_return_items.id = v_transaction_id
					LIMIT 0,1);
			SET v_updated_at = (SELECT
						erp_return_sales.updated_at
					FROM
						erp_return_sales
						JOIN erp_return_items ON erp_return_sales.id = erp_return_items.return_id
					WHERE
						erp_return_items.id = v_transaction_id
					LIMIT 0,1);
					
			INSERT INTO erp_inventory_valuation_details (
				type,
				biller_id,
				product_id,
				product_code,
				product_name,
				category_id,
				reference_no,
				date,
				NAME,
				quantity,
				warehouse_id,
				cost,
				qty_on_hand,
				avg_cost,
				created_by,
				updated_by,
				updated_at,
				field_id
				)
				VALUES
				(
				v_transaction_type,
				v_biller_id,
				NEW.product_id,
				NEW.product_code,
				NEW.product_name,
				v_category_id,
				v_reference_no,
				v_date ,
				v_supplier,
				NEW.quantity_balance,
				NEW.warehouse_id,
				v_cost,
				v_qoh,
				v_cost,
				v_created_by,
				v_updated_by,
				v_updated_at,
				v_transaction_id
				);
		END IF;
		/* End SALE RETURN */


		/* PURCHASE */
		IF NEW.transaction_type = 'PURCHASE' THEN
			SET v_tran_no = (SELECT MAX(tran_no) FROM erp_gl_trans);
			SET v_cost = (SELECT erp_products.cost FROM erp_products WHERE erp_products.id = NEW.product_id);
			SET v_category_id  	= (SELECT erp_products.category_id FROM erp_products WHERE erp_products.id = NEW.product_id);
			SET v_qoh =  (SELECT erp_products.quantity FROM erp_products WHERE erp_products.id = NEW.product_id);
			

			SET v_date = (SELECT
				erp_purchases.date
			FROM
				erp_purchases
				JOIN erp_purchase_items ON erp_purchases.id = erp_purchase_items.purchase_id
			WHERE
				erp_purchase_items.id = v_transaction_id
			LIMIT 0,1);
			SET v_reference_no = (SELECT
						erp_purchases.reference_no
					FROM
						erp_purchases
					WHERE
						erp_purchases.id = NEW.purchase_id
					LIMIT 0,1);
			SET v_supplier = (SELECT
						erp_purchases.supplier
					FROM
						erp_purchases
						JOIN erp_purchase_items ON erp_purchases.id = erp_purchase_items.purchase_id
					WHERE
						erp_purchase_items.id = v_transaction_id
					LIMIT 0,1);
			SET v_biller_id  = (SELECT
						erp_purchases.biller_id
					FROM
						erp_purchases
					WHERE
						erp_purchases.id =  NEW.purchase_id
					LIMIT 0,1);
			SET v_created_by = (SELECT
						erp_purchases.created_by
					FROM
						erp_purchases
						JOIN erp_purchase_items ON erp_purchases.id = erp_purchase_items.purchase_id
					WHERE
						erp_purchase_items.id = v_transaction_id
					LIMIT 0,1);
			SET v_updated_by = (SELECT
						erp_purchases.updated_by
					FROM
						erp_purchases
						JOIN erp_purchase_items ON erp_purchases.id = erp_purchase_items.purchase_id
					WHERE
						erp_purchase_items.id = v_transaction_id
					LIMIT 0,1);
			SET v_updated_at = (SELECT
						erp_purchases.updated_at
					FROM
						erp_purchases
						JOIN erp_purchase_items ON erp_purchases.id = erp_purchase_items.purchase_id
					WHERE
						erp_purchase_items.id = v_transaction_id
					LIMIT 0,1);
			SET v_status = (SELECT
						erp_purchases.status
					FROM
						erp_purchases
						JOIN erp_purchase_items ON erp_purchases.id = erp_purchase_items.purchase_id
					WHERE
						erp_purchase_items.id = v_transaction_id
					LIMIT 0,1);
			SET v_tran_note = (SELECT
						erp_purchases.note
					FROM
						erp_purchases
						JOIN erp_purchase_items ON erp_purchases.id = erp_purchase_items.purchase_id
					WHERE
						erp_purchase_items.id = v_transaction_id
					LIMIT 0,1);
					
			IF v_acc_cate_separate = 1 THEN  

				SET  v_chk_acc  =  ( SELECT
										COUNT(*) as row
									FROM
										erp_categories
									INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_categories.ac_purchase
									INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
									WHERE
										erp_categories.id = v_category_id );
										
				IF NEW.status = "received" THEN

					IF   v_chk_acc  > 0 THEN

						INSERT INTO erp_gl_trans (
							tran_type,
							tran_no,
							tran_date,
							sectionid,
							account_code,
							narrative,
							amount,
							reference_no,
							description,
							biller_id,
							created_by,
							updated_by
						) SELECT
							'PURCHASES',
							v_tran_no,
							NEW.date,
							erp_gl_sections.sectionid,
							erp_gl_charts.accountcode,
							erp_gl_charts.accountname,
							(NEW.subtotal + NEW.net_shipping),
							v_reference_no,
							v_tran_note   ,
							v_biller_id,
							v_created_by,
							v_updated_by
						FROM
							erp_categories
							INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_categories.ac_purchase
							INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
						WHERE
							erp_categories.id = v_category_id ;

						IF NEW.cb_qty < 0 THEN
						
							INSERT INTO erp_gl_trans (
								tran_type,
								tran_no,
								tran_date,
								sectionid,
								account_code,
								narrative,
								amount,
								reference_no,
								description,
								biller_id,
								created_by,
								updated_by
							) SELECT

								'PURCHASES',
								v_tran_no,
								NEW.date,
								erp_gl_sections.sectionid,
								erp_gl_charts.accountcode,
								erp_gl_charts.accountname,
								((NEW.real_unit_cost - NEW.cb_avg) * NEW.cb_qty),
								v_reference_no,
								v_tran_note   ,
								v_biller_id,
								v_created_by,
								v_updated_by
							FROM
								erp_categories
								INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_categories.ac_stock
								INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
							WHERE
								erp_categories.id = v_category_id ;
								
							INSERT INTO erp_gl_trans (
								tran_type,
								tran_no,
								tran_date,
								sectionid,
								account_code,
								narrative,
								amount,
								reference_no,
								description,
								biller_id,
								created_by,
								updated_by
							) SELECT

								'PURCHASES',
								v_tran_no,
								NEW.date,
								erp_gl_sections.sectionid,
								erp_gl_charts.accountcode,
								erp_gl_charts.accountname,
								(-1) * ((NEW.real_unit_cost - NEW.cb_avg) * NEW.cb_qty),
								v_reference_no,
								v_tran_note   ,
								v_biller_id,
								v_created_by,
								v_updated_by
							FROM
								erp_categories
								INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_categories.ac_cost
								INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
							WHERE
								erp_categories.id = v_category_id ;
								
						END IF;
							
					END IF;
					
					IF   v_chk_acc  <= 0   THEN

						INSERT INTO erp_gl_trans (
							tran_type,
							tran_no,
							tran_date,
							sectionid,
							account_code,
							narrative,
							amount,
							reference_no,
							description,
							biller_id,
							created_by,
							updated_by
						) SELECT

							'PURCHASES',
							v_tran_no,
							NEW.date,
							erp_gl_sections.sectionid,
							erp_gl_charts.accountcode,
							erp_gl_charts.accountname,
							(NEW.subtotal + NEW.net_shipping),
							v_reference_no,
							v_tran_note   ,
							v_biller_id,
							v_created_by,
							v_updated_by
						FROM
							erp_account_settings
							INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_account_settings.default_purchase
							INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
						WHERE
							erp_gl_charts.accountcode = erp_account_settings.default_purchase;
							
						IF NEW.cb_qty < 0 THEN
						
							INSERT INTO erp_gl_trans (
								tran_type,
								tran_no,
								tran_date,
								sectionid,
								account_code,
								narrative,
								amount,
								reference_no,
								description,
								biller_id,
								created_by,
								updated_by
							) SELECT

								'PURCHASES',
								v_tran_no,
								NEW.date,
								erp_gl_sections.sectionid,
								erp_gl_charts.accountcode,
								erp_gl_charts.accountname,
								((NEW.real_unit_cost - NEW.cb_avg) * NEW.cb_qty),
								v_reference_no,
								v_tran_note   ,
								v_biller_id,
								v_created_by,
								v_updated_by
							FROM
								erp_account_settings
								INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_account_settings.default_stock
								INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
							WHERE
								erp_gl_charts.accountcode = erp_account_settings.default_stock;
								
							INSERT INTO erp_gl_trans (
								tran_type,
								tran_no,
								tran_date,
								sectionid,
								account_code,
								narrative,
								amount,
								reference_no,
								description,
								biller_id,
								created_by,
								updated_by
							) SELECT

								'PURCHASES',
								v_tran_no,
								NEW.date,
								erp_gl_sections.sectionid,
								erp_gl_charts.accountcode,
								erp_gl_charts.accountname,
								(-1) * ((NEW.real_unit_cost - NEW.cb_avg) * NEW.cb_qty),
								v_reference_no,
								v_tran_note   ,
								v_biller_id,
								v_created_by,
								v_updated_by
							FROM
								erp_account_settings
								INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_account_settings.default_cost
								INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
							WHERE
								erp_gl_charts.accountcode = erp_account_settings.default_cost;
								
						END IF;
							
					END IF;
					
				END IF;
			
			END IF;

													  
			IF v_status = 'received' THEN
				INSERT INTO erp_inventory_valuation_details (
					type,
					biller_id,
					product_id,
					product_code,
					product_name,
					category_id,
					reference_no,
					date,
					NAME,
					quantity,
					warehouse_id,
					cost,
					qty_on_hand,
					avg_cost,
					created_by,
					updated_by,
					updated_at,
					field_id
					)
					VALUES
					(
					v_transaction_type,
					v_biller_id,
					NEW.product_id,
					NEW.product_code,
					NEW.product_name,
					v_category_id,
					v_reference_no,
					v_date ,
					v_supplier,
					NEW.quantity_balance,
					NEW.warehouse_id,
					v_cost,
					v_qoh,
					v_cost,
					v_created_by,
					v_updated_by,
					v_updated_at,
					v_transaction_id
				);
			END IF;
		END IF;
		/* End PURCHASE */
		
		/* PURCHASE RETURN */
		IF NEW.transaction_type = 'PURCHASE RETURN' THEN
			SET v_cost = (SELECT erp_products.cost FROM erp_products WHERE erp_products.id = NEW.product_id);
			SET v_category_id = (SELECT erp_products.category_id FROM erp_products WHERE erp_products.id = NEW.product_id);
			SET v_qoh =  (SELECT erp_products.quantity FROM erp_products WHERE erp_products.id = NEW.product_id);
			
			SET v_date = (SELECT
				erp_return_purchases.date
			FROM
				erp_return_purchases
				JOIN erp_return_purchase_items ON erp_return_purchases.id = erp_return_purchase_items.return_id
			WHERE
				erp_return_purchase_items.id = v_transaction_id
			LIMIT 0,1);
			SET v_reference_no = (SELECT
						erp_return_purchases.reference_no
					FROM
						erp_return_purchases
						JOIN erp_return_purchase_items ON erp_return_purchases.id = erp_return_purchase_items.return_id
					WHERE
						erp_return_purchase_items.id = v_transaction_id
					LIMIT 0,1);
			SET v_supplier = (SELECT
						erp_return_purchases.supplier
					FROM
						erp_return_purchases
						JOIN erp_return_purchase_items ON erp_return_purchases.id = erp_return_purchase_items.return_id
					WHERE
						erp_return_purchase_items.id = v_transaction_id
					LIMIT 0,1);
			SET v_biller_id = (SELECT
						erp_return_purchases.biller_id
					FROM
						erp_return_purchases
						JOIN erp_return_purchase_items ON erp_return_purchases.id = erp_return_purchase_items.return_id
					WHERE
						erp_return_purchase_items.id = v_transaction_id
					LIMIT 0,1);
			SET v_created_by = (SELECT
						erp_return_purchases.created_by
					FROM
						erp_return_purchases
						JOIN erp_return_purchase_items ON erp_return_purchases.id = erp_return_purchase_items.return_id
					WHERE
						erp_return_purchase_items.id = v_transaction_id
					LIMIT 0,1);
			SET v_updated_by = (SELECT
						erp_return_purchases.updated_by
					FROM
						erp_return_purchases
						JOIN erp_return_purchase_items ON erp_return_purchases.id = erp_return_purchase_items.return_id
					WHERE
						erp_return_purchase_items.id = v_transaction_id
					LIMIT 0,1);
			SET v_updated_at = (SELECT
						erp_return_purchases.updated_at
					FROM
						erp_return_purchases
						JOIN erp_return_purchase_items ON erp_return_purchases.id = erp_return_purchase_items.return_id
					WHERE
						erp_return_purchase_items.id = v_transaction_id
					LIMIT 0,1);

			INSERT INTO erp_inventory_valuation_details (
				type,
				biller_id,
				product_id,
				product_code,
				product_name,
				category_id,
				reference_no,
				date,
				NAME,
				quantity,
				warehouse_id,
				cost,
				qty_on_hand,
				avg_cost,
				created_by,
				updated_by,
				updated_at,
				field_id
				)
				VALUES
				(
				v_transaction_type,
				v_biller_id,
				NEW.product_id,
				NEW.product_code,
				NEW.product_name,
				v_category_id,
				v_reference_no,
				v_date ,
				v_supplier,
				(-1)*NEW.quantity_balance,
				NEW.warehouse_id,
				v_cost,
				v_qoh,
				v_cost,
				v_created_by,
				v_updated_by,
				v_updated_at,
				v_transaction_id
			);
		END IF;
		/* End PURCHASE RETURN */
		
		/* TRANSFER */
		IF NEW.transaction_type = 'TRANSFER' THEN
			SET v_cost = (SELECT erp_products.cost FROM erp_products WHERE erp_products.id = NEW.product_id);
			SET v_category_id = (SELECT erp_products.category_id FROM erp_products WHERE erp_products.id = NEW.product_id);
			SET v_qoh =  (SELECT erp_products.quantity FROM erp_products WHERE erp_products.id = NEW.product_id);
		
			SET v_date = (SELECT
				erp_transfers.date
			FROM
				erp_transfers
				JOIN erp_transfer_items ON erp_transfers.id = erp_transfer_items.transfer_id
			WHERE
				erp_transfer_items.id = v_transaction_id
			LIMIT 0,1);
			SET v_reference_no = (SELECT
						erp_transfers.transfer_no
					FROM
						erp_transfers
						JOIN erp_transfer_items ON erp_transfers.id = erp_transfer_items.transfer_id
					WHERE
						erp_transfer_items.id = v_transaction_id
					LIMIT 0,1);
			SET v_biller_id = (SELECT
						erp_transfers.biller_id
					FROM
						erp_transfers
						JOIN erp_transfer_items ON erp_transfers.id = erp_transfer_items.transfer_id
					WHERE
						erp_transfer_items.id = v_transaction_id
					LIMIT 0,1);
			SET v_created_by = (SELECT
						erp_transfers.created_by
					FROM
						erp_transfers
						JOIN erp_transfer_items ON erp_transfers.id = erp_transfer_items.transfer_id
					WHERE
						erp_transfer_items.id = v_transaction_id
					LIMIT 0,1);
			SET v_status = (SELECT
						erp_transfers.status
					FROM
						erp_transfers
						JOIN erp_transfer_items ON erp_transfers.id = erp_transfer_items.transfer_id
					WHERE
						erp_transfer_items.id = v_transaction_id
					LIMIT 0,1);
			INSERT INTO erp_inventory_valuation_details (
				type,
				biller_id,
				product_id,
				product_code,
				product_name,
				category_id,
				reference_no,
				date,
				NAME,
				quantity,
				warehouse_id,
				cost,
				qty_on_hand,
				avg_cost,
				created_by,
				field_id
				)
				VALUES
				(
					v_transaction_type,
					v_biller_id,
					NEW.product_id,
					NEW.product_code,
					NEW.product_name,
					v_category_id,
					v_reference_no,
					v_date,
					NULL,
					(-1)*NEW.quantity_balance,
					NEW.warehouse_id,
					v_cost,
					v_qoh,
					v_cost,
					v_created_by,
					v_transaction_id
				);
		END IF;
		/* End TRANSFER */
		
		/* USING STOCK */
		IF NEW.transaction_type = 'USING STOCK' THEN
			SET v_cost = (SELECT cost FROM erp_products WHERE id = NEW.product_id);
			SET v_category_id = (SELECT category_id FROM erp_products WHERE id = NEW.product_id);
			SET v_qoh =  (SELECT quantity FROM erp_products WHERE id = NEW.product_id);

			SET v_date = (SELECT
				erp_enter_using_stock.date
			FROM
				erp_enter_using_stock
				JOIN erp_enter_using_stock_items ON erp_enter_using_stock.reference_no = erp_enter_using_stock_items.reference_no
			WHERE
				erp_enter_using_stock_items.id = v_transaction_id
			LIMIT 0,1);
			SET v_reference_no = (SELECT
						erp_enter_using_stock.reference_no
					FROM
						erp_enter_using_stock
						JOIN erp_enter_using_stock_items ON erp_enter_using_stock.reference_no = erp_enter_using_stock_items.reference_no
					WHERE
						erp_enter_using_stock_items.id = v_transaction_id
					LIMIT 0,1);
			SET v_biller_id = (SELECT
						erp_enter_using_stock.shop
					FROM
						erp_enter_using_stock
						JOIN erp_enter_using_stock_items ON erp_enter_using_stock.reference_no = erp_enter_using_stock_items.reference_no
					WHERE
						erp_enter_using_stock_items.id = v_transaction_id
					LIMIT 0,1);
			SET v_created_by = (SELECT
						erp_enter_using_stock.create_by
					FROM
						erp_enter_using_stock
						JOIN erp_enter_using_stock_items ON erp_enter_using_stock.reference_no = erp_enter_using_stock_items.reference_no
					WHERE
						erp_enter_using_stock_items.id = v_transaction_id
					LIMIT 0,1);
			INSERT INTO erp_inventory_valuation_details (
				type,
				biller_id,
				product_id,
				product_code,
				product_name,
				category_id,
				reference_no,
				date,
				NAME,
				quantity,
				warehouse_id,
				cost,
				qty_on_hand,
				avg_cost,
				created_by,
				field_id
				)
				VALUES
				(
				v_transaction_type,
				v_biller_id,
				NEW.product_id,
				NEW.product_code,
				NEW.product_name,
				v_category_id,
				v_reference_no,
				v_date ,
				NULL,
				(-1)*NEW.quantity_balance,
				NEW.warehouse_id,
				v_cost,
				v_qoh,
				v_cost,
				v_created_by,
				v_transaction_id
			);
		END IF;
		/* End USING STOCK */
		
		/* ADJUSTMENT */
		IF NEW.transaction_type = 'ADJUSTMENT' THEN
			SET v_cost = (SELECT erp_products.cost FROM erp_products WHERE erp_products.id = NEW.product_id);
			SET v_category_id = (SELECT erp_products.category_id FROM erp_products WHERE erp_products.id = NEW.product_id);
			SET v_qoh =  (SELECT erp_products.quantity FROM erp_products WHERE erp_products.id = NEW.product_id);
			
			SET v_date = (SELECT
				erp_adjustments.date
			FROM
				erp_adjustments
			WHERE
				erp_adjustments.id = v_transaction_id
			LIMIT 0,1);
			SET v_biller_id = (SELECT
						erp_adjustments.biller_id
					FROM
						erp_adjustments
                    INNER JOIN erp_adjustment_items ON erp_adjustment_items.adjust_id = erp_adjustments.id
					WHERE
						erp_adjustment_items.id = v_transaction_id
					LIMIT 0,1);
			SET v_created_by = (SELECT
						erp_adjustments.created_by
					FROM
						erp_adjustments
                    INNER JOIN erp_adjustment_items ON erp_adjustment_items.adjust_id = erp_adjustments.id
					WHERE
						erp_adjustment_items.id  = v_transaction_id
					LIMIT 0,1);
			SET v_updated_by = (SELECT
						erp_adjustments.updated_by
					FROM
						erp_adjustments
					INNER JOIN erp_adjustment_items ON erp_adjustment_items.adjust_id = erp_adjustments.id
					WHERE
						erp_adjustment_items.id  =  v_transaction_id
					LIMIT 0,1);
			SET v_status = (SELECT
						type
					FROM
						erp_adjustment_items
					WHERE
						id = v_transaction_id
					LIMIT 0,1);
			IF v_status = 'addition' THEN
			
				INSERT INTO erp_inventory_valuation_details (
					type,
					biller_id,
					product_id,
					product_code,
					product_name,
					category_id,
					reference_no,
					date,
					NAME,
					quantity,
					warehouse_id,
					cost,
					qty_on_hand,
					avg_cost,
					created_by,
					updated_by,
					field_id
					)
					VALUES
					(
					v_transaction_type,
					v_biller_id,
					NEW.product_id,
					NEW.product_code,
					NEW.product_name,
					v_category_id,
					NULL,
					v_date ,
					v_supplier,
					NEW.quantity_balance,
					NEW.warehouse_id,
					v_cost,
					v_qoh,
					v_cost,
					v_created_by,
					v_updated_by,
					v_transaction_id
				);
				
			ELSE 
			
				INSERT INTO erp_inventory_valuation_details (
					type,
					biller_id,
					product_id,
					product_code,
					product_name,
					category_id,
					reference_no,
					date,
					NAME,
					quantity,
					warehouse_id,
					cost,
					qty_on_hand,
					avg_cost,
					created_by,
					updated_by,
					field_id
					)
					VALUES
					(
					v_transaction_type,
					v_biller_id,
					NEW.product_id,
					NEW.product_code,
					NEW.product_name,
					v_category_id,
					NULL,
					v_date ,
					v_supplier,
					NEW.quantity_balance,
					NEW.warehouse_id,
					v_cost,
					v_qoh,
					v_cost,
					v_created_by,
					v_updated_by,
					v_transaction_id
				);
			
			END IF;
			
		END IF;
		/* End ADJUSTMENT */
	    /* CONVERT */
		IF NEW.transaction_type = 'CONVERT' THEN
			SET v_cost = (SELECT erp_products.cost FROM erp_products WHERE erp_products.id = NEW.product_id);
			SET v_category_id =(SELECT erp_products.category_id FROM erp_products WHERE erp_products.id = NEW.product_id);
                                                     
			SET v_qoh =  (SELECT erp_products.quantity FROM erp_products WHERE erp_products.id = NEW.product_id);
			
			SET v_date = (SELECT
				erp_convert.date
			FROM
				erp_convert
				JOIN erp_convert_items ON erp_convert.id = erp_convert_items.convert_id
			WHERE
				erp_convert_items.id = v_transaction_id
			LIMIT 0,1);

			SET v_reference_no = (SELECT
						erp_convert.reference_no
					FROM
						erp_convert
						JOIN erp_convert_items ON erp_convert.id = erp_convert_items.convert_id
					WHERE
						erp_convert_items.id = v_transaction_id
					LIMIT 0,1);

		                 SET v_biller_id = (SELECT
						erp_convert.biller_id
					FROM
						erp_convert
						JOIN erp_convert_items ON erp_convert.id = erp_convert_items.convert_id
					WHERE
						erp_convert_items.id = v_transaction_id
					LIMIT 0,1);


			SET v_created_by = (SELECT
						erp_convert.created_by
					FROM
						erp_convert
						JOIN erp_convert_items ON erp_convert.id = erp_convert_items.convert_id
					WHERE
						erp_convert_items.id  = v_transaction_id
					LIMIT 0,1);
			SET v_updated_by = (SELECT
						erp_convert.updated_by
					FROM
						erp_convert
						JOIN erp_convert_items ON erp_convert.id = erp_convert_items.convert_id
					WHERE
						erp_convert_items.id = v_transaction_id
					LIMIT 0,1);
			SET v_status = (SELECT
						erp_convert_items.status
					FROM
						erp_convert
						JOIN erp_convert_items ON erp_convert.id = erp_convert_items.convert_id
					WHERE
						erp_convert_items.id = v_transaction_id
					LIMIT 0,1);
			IF v_status = 'add' THEN
			
				INSERT INTO erp_inventory_valuation_details (
					type,
					biller_id,
					product_id,
					product_code,
					product_name,
					category_id,
					reference_no,
					date,
					NAME,
					quantity,
					warehouse_id,
					cost,
					qty_on_hand,
					avg_cost,
					created_by,
					updated_by,
					field_id
					)
					VALUES
					(
					v_transaction_type,
					v_biller_id,
					NEW.product_id,
					NEW.product_code,
					NEW.product_name,
					v_category_id,
					v_reference_no,
					v_date ,
					NULL,
					NEW.quantity_balance,
					NEW.warehouse_id,
					v_cost,
					v_qoh,
					v_cost,
					v_created_by,
					v_updated_by,
					v_transaction_id
				);
				
			ELSE 
			
				INSERT INTO erp_inventory_valuation_details (
					type,
					biller_id,
					product_id,
					product_code,
					product_name,
					category_id,
					reference_no,
					date,
					NAME,
					quantity,
					warehouse_id,
					cost,
					qty_on_hand,
					avg_cost,
					created_by,
					updated_by,
					field_id
					)
					VALUES
					(
						v_transaction_type,
						v_biller_id,
						NEW.product_id,
						NEW.product_code,
						NEW.product_name,
						v_category_id,
						v_reference_no,
						v_date ,
						NULL,
						(-1)*NEW.quantity_balance,
						NEW.warehouse_id,
						v_cost,
						v_qoh,
						v_cost,
						v_created_by,
						v_updated_by,
						v_transaction_id
					);
			
			END IF;
			
		END IF;

		/* End CONVERT */

        /* PURCHASE OPENING QTY */
		IF NEW.transaction_type = 'OPENING QUANTITY' THEN
			SET v_cost = (SELECT erp_products.cost FROM erp_products WHERE erp_products.id = NEW.product_id);
			SET v_category_id  = (SELECT erp_products.category_id FROM erp_products WHERE erp_products.id = NEW.product_id);
			SET v_qoh =  (SELECT erp_products.quantity FROM erp_products WHERE erp_products.id = NEW.product_id);
			SET v_biller_id  = 0;
			SET v_date = CURDATE();
			
			SET v_reference_no = (SELECT
						erp_purchases.reference_no
					FROM
						erp_purchases
					WHERE
						erp_purchases.id = NEW.purchase_id
					LIMIT 0,1);

			SET v_supplier = (SELECT
						erp_purchases.supplier
					FROM
						erp_purchases
						JOIN erp_purchase_items ON erp_purchases.id = erp_purchase_items.purchase_id
					WHERE
						erp_purchase_items.id = v_transaction_id
					LIMIT 0,1);
			
			SET v_created_by = (SELECT
						erp_purchases.created_by
					FROM
						erp_purchases
						JOIN erp_purchase_items ON erp_purchases.id = erp_purchase_items.purchase_id
					WHERE
						erp_purchase_items.id = v_transaction_id
					LIMIT 0,1);
			SET v_updated_by = (SELECT
						erp_purchases.updated_by
					FROM
						erp_purchases
						JOIN erp_purchase_items ON erp_purchases.id = erp_purchase_items.purchase_id
					WHERE
						erp_purchase_items.id = v_transaction_id
					LIMIT 0,1);
			SET v_updated_at = (SELECT
						erp_purchases.updated_at
					FROM
						erp_purchases
						JOIN erp_purchase_items ON erp_purchases.id = erp_purchase_items.purchase_id
					WHERE
						erp_purchase_items.id = v_transaction_id
					LIMIT 0,1);
			
            INSERT INTO erp_inventory_valuation_details (
					type,
					biller_id,
					product_id,
					product_code,
					product_name,
					category_id,
					reference_no,
					date,
					NAME,
					quantity,
					warehouse_id,
					cost,
					qty_on_hand,
					avg_cost,
					created_by,
					updated_by,
					field_id
					)
					VALUES
					(
						v_transaction_type,
						v_biller_id,
						NEW.product_id,
						NEW.product_code,
						NEW.product_name,
						v_category_id,
						v_reference_no,
						v_date ,
						NULL,
						NEW.quantity_balance,
						NEW.warehouse_id,
						v_cost,
						v_qoh,
						v_cost,
						v_created_by,
						v_updated_by,
						v_transaction_id
					);
		END IF;
		/* End PURCHASE OPENING QTY */
	END IF;

END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `audit_purchase_items_update`;
DELIMITER ;;
CREATE TRIGGER `audit_purchase_items_update` BEFORE UPDATE ON `erp_purchase_items` FOR EACH ROW BEGIN
	IF OLD.id THEN
		INSERT INTO erp_purchase_items_audit (
			id,
			delivery_id,
			purchase_id,
			transfer_id,
			product_id,
			product_code,
			product_name,
			option_id,
			product_type,
			net_unit_cost,
			quantity,
			warehouse_id,
			item_tax,
			tax_rate_id,
			tax,
			discount,
			item_discount,
			expiry,
			subtotal,
			quantity_balance,
			date,
			status,
			unit_cost,
			real_unit_cost,
			quantity_received,
			supplier_part_no,
			supplier_id,
			job_name,
			note,
			convert_id,
			type,
			reference,
			opening_stock,
			create_id,
			returned,
			acc_cate_separate,
			specific_tax_on_certain_merchandise_and_services,
			accommodation_tax,
			public_lighting_tax,
			other_tax,
			payment_of_profit_tax,
			performance_royalty_intangible,
			payment_interest_non_bank,
			payment_interest_taxpayer_fixed,
			payment_interest_taxpayer_non_fixed,
			payment_rental_immovable_property,
			payment_of_interest,
			payment_royalty_rental_income_related,
			payment_management_technical,
			payment_dividend,
			withholding_tax_on_resident,
			withholding_tax_on_non_resident,
			audit_type
		) SELECT 
			id,
			delivery_id,
			purchase_id,
			transfer_id,
			product_id,
			product_code,
			product_name,
			option_id,
			product_type,
			net_unit_cost,
			quantity,
			warehouse_id,
			item_tax,
			tax_rate_id,
			tax,
			discount,
			item_discount,
			expiry,
			subtotal,
			quantity_balance,
			date,
			status,
			unit_cost,
			real_unit_cost,
			quantity_received,
			supplier_part_no,
			supplier_id,
			job_name,
			note,
			convert_id,
			type,
			reference,
			opening_stock,
			create_id,
			returned,
			acc_cate_separate,
			specific_tax_on_certain_merchandise_and_services,
			accommodation_tax,
			public_lighting_tax,
			other_tax,
			payment_of_profit_tax,
			performance_royalty_intangible,
			payment_interest_non_bank,
			payment_interest_taxpayer_fixed,
			payment_interest_taxpayer_non_fixed,
			payment_rental_immovable_property,
			payment_of_interest,
			payment_royalty_rental_income_related,
			payment_management_technical,
			payment_dividend,
			withholding_tax_on_resident,
			withholding_tax_on_non_resident,
			"UPDATED"
		FROM
			erp_purchase_items
		WHERE
			erp_purchase_items.id = OLD.id;
	END IF;

END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `gl_trans_purchase_items_update`;
DELIMITER ;;
CREATE TRIGGER `gl_trans_purchase_items_update` AFTER UPDATE ON `erp_purchase_items` FOR EACH ROW BEGIN
	DECLARE v_reference_no VARCHAR(55);
	DECLARE v_date DATETIME;
	DECLARE v_biller_id INTEGER;
	DECLARE v_supplier VARCHAR(55);
	DECLARE v_created_by INTEGER;
	DECLARE v_updated_by INTEGER;
	DECLARE v_cost DECIMAL(4);
	DECLARE v_status VARCHAR(50);
	DECLARE v_category_id INTEGER;
	DECLARE v_qoh INTEGER;
	DECLARE v_updated_at DATETIME;
	DECLARE v_transaction_id INTEGER;
	DECLARE v_transaction_type VARCHAR(25);
	DECLARE v_acc_cate_separate INTEGER;
	DECLARE v_tran_note VARCHAR(255);
    DECLARE v_chk_acc  INTEGER;
	DECLARE v_tran_no INTEGER;


	SET v_acc_cate_separate = (SELECT erp_settings.acc_cate_separate FROM erp_settings WHERE erp_settings.setting_id = '1');

	IF NEW.product_id THEN

		SET v_transaction_id = NEW.transaction_id;
		SET v_transaction_type = NEW.transaction_type;
		
		/* SALE */
		IF NEW.transaction_id THEN 
			IF v_transaction_type = 'SALE' THEN
				
				SET v_cost = (SELECT erp_products.cost FROM erp_products WHERE erp_products.id = NEW.product_id);
				SET v_category_id = (SELECT erp_products.category_id FROM erp_products WHERE erp_products.id = NEW.product_id);
				SET v_qoh =  (SELECT erp_products.quantity FROM erp_products WHERE erp_products.id = NEW.product_id);
															SET v_tran_no = (SELECT MAX(tran_no) FROM erp_gl_trans);			

				SET v_date = (SELECT
					erp_sales.date
				FROM
					erp_sales
					JOIN erp_sale_items ON erp_sales.id = erp_sale_items.sale_id
				WHERE
					erp_sale_items.id = v_transaction_id
				LIMIT 0,1);
				SET v_reference_no = (SELECT
							erp_sales.reference_no
						FROM
							erp_sales
							JOIN erp_sale_items ON erp_sales.id = erp_sale_items.sale_id
						WHERE
							erp_sale_items.id = v_transaction_id
						LIMIT 0,1);
				SET v_supplier = (SELECT
							erp_sales.customer
						FROM
							erp_sales
							JOIN erp_sale_items ON erp_sales.id = erp_sale_items.sale_id
						WHERE
							erp_sale_items.id = v_transaction_id
						LIMIT 0,1);
				SET v_biller_id = (SELECT
							erp_sales.biller_id
						FROM
							erp_sales
							JOIN erp_sale_items ON erp_sales.id = erp_sale_items.sale_id
						WHERE
							erp_sale_items.id = v_transaction_id
						LIMIT 0,1);
				SET v_created_by = (SELECT
							erp_sales.created_by
						FROM
							erp_sales
							JOIN erp_sale_items ON erp_sales.id = erp_sale_items.sale_id
						WHERE
							erp_sale_items = v_transaction_id
						LIMIT 0,1);
				SET v_updated_by = (SELECT
							erp_sales.updated_by
						FROM
							erp_sales
							JOIN erp_sale_items ON erp_sales.id = erp_sale_items.sale_id
						WHERE
							erp_sale_items.id = v_transaction_id
						LIMIT 0,1);
				SET v_status = (SELECT
							erp_sales.sale_status
						FROM
							erp_sales
							JOIN erp_sale_items ON erp_sales.id = erp_sale_items.sale_id
						WHERE
							erp_sale_items.id = v_transaction_id
						LIMIT 0,1);
				SET v_updated_at = (SELECT
							erp_sales.updated_at
						FROM
							erp_sales
							JOIN erp_sale_items ON erp_sales.id = erp_sale_items.sale_id
						WHERE
							erp_sale_items.id = v_transaction_id
						LIMIT 0,1);
						
				DELETE FROM erp_inventory_valuation_details WHERE reference_no = v_reference_no AND product_id = NEW.product_id AND type = 'SALE' AND field_id = v_transaction_id;
				
				IF v_status = 'completed' THEN
					INSERT INTO erp_inventory_valuation_details (
						type,
						biller_id,
						product_id,
						product_code,
						product_name,
						category_id,
						reference_no,
						date,
						NAME,
						quantity,
						warehouse_id,
						cost,
						qty_on_hand,
						avg_cost,
						created_by,
						updated_by,
						updated_at,
						field_id
						)
						VALUES
						(
						v_transaction_type,
						v_biller_id,
						NEW.product_id,
						NEW.product_code,
						NEW.product_name,
						v_category_id,
						v_reference_no,
						v_date ,
						v_supplier,
						(-1)*NEW.quantity_balance,
						NEW.warehouse_id,
						v_cost,
						v_qoh,
						v_cost,
						v_created_by,
						v_updated_by,
						v_updated_at,
						v_transaction_id
					);
				END IF;

				END IF;
		END IF;
		/* End SALE */
			
		/* SALE RETURN */
		IF v_transaction_type = 'SALE RETURN' THEN
			SET v_cost = (SELECT erp_products.cost FROM erp_products WHERE erp_products.id = NEW.product_id);
			SET v_category_id = (SELECT erp_products.category_id FROM erp_products WHERE erp_products.id = NEW.product_id);
			SET v_qoh =  (SELECT erp_products.quantity FROM erp_products WHERE erp_products.id = NEW.product_id);
		
			SET v_date = (SELECT
				erp_return_sales.date
			FROM
				erp_return_sales
				JOIN erp_return_items ON erp_return_sales.id = erp_return_items.return_id
			WHERE
				erp_return_items.id = v_transaction_id
			LIMIT 0,1);
			SET v_reference_no = (SELECT
						erp_return_sales.reference_no
					FROM
						erp_return_sales
						JOIN erp_return_items ON erp_return_sales.id = erp_return_items.return_id
					WHERE
						erp_return_items.id = v_transaction_id
					LIMIT 0,1);
			SET v_supplier = (SELECT
						erp_return_sales.customer
					FROM
						erp_return_sales
						JOIN erp_return_items ON erp_return_sales.id = erp_return_items.return_id
					WHERE
						erp_return_items.id = v_transaction_id
					LIMIT 0,1);
			SET v_biller_id = (SELECT
						erp_return_sales.biller_id
					FROM
						erp_return_sales
						JOIN erp_return_items ON erp_return_sales.id = erp_return_items.return_id
					WHERE
						erp_return_items.id = v_transaction_id
					LIMIT 0,1);
			SET v_created_by = (SELECT
						erp_return_sales.created_by
					FROM
						erp_return_sales
						JOIN erp_return_items ON erp_return_sales.id = erp_return_items.return_id
					WHERE
						erp_return_items = v_transaction_id
					LIMIT 0,1);
			SET v_updated_by = (SELECT
						erp_return_sales.updated_by
					FROM
						erp_return_sales
						JOIN erp_return_items ON erp_return_sales.id = erp_return_items.return_id
					WHERE
						erp_return_items.id = v_transaction_id
					LIMIT 0,1);
			SET v_updated_at = (SELECT
						erp_return_sales.updated_at
					FROM
						erp_return_sales
						JOIN erp_return_items ON erp_return_sales.id = erp_return_items.return_id
					WHERE
						erp_return_items.id = v_transaction_id
					LIMIT 0,1);
			
			DELETE FROM erp_inventory_valuation_details WHERE reference_no = v_reference_no AND product_id = NEW.product_id AND type = 'SALE RETURN' AND field_id = v_transaction_id;
			
			INSERT INTO erp_inventory_valuation_details (
				type,
				biller_id,
				product_id,
				product_code,
				product_name,
				category_id,
				reference_no,
				date,
				NAME,
				quantity,
				warehouse_id,
				cost,
				qty_on_hand,
				avg_cost,
				created_by,
				updated_by,
				updated_at,
				field_id
				)
				VALUES
				(
				v_transaction_type,
				v_biller_id,
				NEW.product_id,
				NEW.product_code,
				NEW.product_name,
				v_category_id,
				v_reference_no,
				v_date ,
				v_supplier,
				NEW.quantity_balance,
				NEW.warehouse_id,
				v_cost,
				v_qoh,
				v_cost,
				v_created_by,
				v_updated_by,
				v_updated_at,
				v_transaction_id
				);
		END IF;
		/* End SALE RETURN */
			
		/* PURCHASE */
		IF NEW.transaction_type = 'PURCHASE' THEN
			SET v_cost = (SELECT erp_products.cost FROM erp_products WHERE erp_products.id = NEW.product_id);
			SET v_category_id = (SELECT erp_products.category_id FROM erp_products WHERE erp_products.id = NEW.product_id);
			SET v_qoh =  (SELECT erp_products.quantity FROM erp_products WHERE erp_products.id = NEW.product_id);
			
			SET v_date = (SELECT
				erp_purchases.date
			FROM
				erp_purchases
				JOIN erp_purchase_items ON erp_purchases.id = erp_purchase_items.purchase_id
			WHERE
				erp_purchase_items.id = v_transaction_id
			LIMIT 0,1);
			SET v_reference_no = (SELECT
						erp_purchases.reference_no
					FROM
						erp_purchases
						JOIN erp_purchase_items ON erp_purchases.id = erp_purchase_items.purchase_id
					WHERE
						erp_purchase_items.id = v_transaction_id
					LIMIT 0,1);
			SET v_supplier = (SELECT
						erp_purchases.supplier
					FROM
						erp_purchases
						JOIN erp_purchase_items ON erp_purchases.id = erp_purchase_items.purchase_id
					WHERE
						erp_purchase_items.id = v_transaction_id
					LIMIT 0,1);
			SET v_biller_id = (SELECT
						erp_purchases.biller_id
					FROM
						erp_purchases
						JOIN erp_purchase_items ON erp_purchases.id = erp_purchase_items.purchase_id
					WHERE
						erp_purchase_items.id = v_transaction_id
					LIMIT 0,1);
			SET v_created_by = (SELECT
						erp_purchases.created_by
					FROM
						erp_purchases
						JOIN erp_purchase_items ON erp_purchases.id = erp_purchase_items.purchase_id
					WHERE
						erp_purchase_items.id = v_transaction_id
					LIMIT 0,1);
			SET v_updated_by = (SELECT
						erp_purchases.updated_by
					FROM
						erp_purchases
						JOIN erp_purchase_items ON erp_purchases.id = erp_purchase_items.purchase_id
					WHERE
						erp_purchase_items.id = v_transaction_id
					LIMIT 0,1);
			SET v_updated_at = (SELECT
						erp_purchases.updated_at
					FROM
						erp_purchases
						JOIN erp_purchase_items ON erp_purchases.id = erp_purchase_items.purchase_id
					WHERE
						erp_purchase_items.id = v_transaction_id
					LIMIT 0,1);
			SET v_status = (SELECT
						erp_purchases.status
					FROM
						erp_purchases
						JOIN erp_purchase_items ON erp_purchases.id = erp_purchase_items.purchase_id
					WHERE
						erp_purchase_items.id = v_transaction_id
					LIMIT 0,1);
			SET v_tran_note = (SELECT
						erp_purchases.note
					FROM
						erp_purchases
						JOIN erp_purchase_items ON erp_purchases.id = erp_purchase_items.purchase_id
					WHERE
						erp_purchase_items.id = v_transaction_id
					LIMIT 0,1);
					
			DELETE FROM erp_inventory_valuation_details WHERE reference_no = v_reference_no AND product_id = NEW.product_id AND type = 'PURCHASE' AND field_id = v_transaction_id;
			
			IF v_status = 'received' THEN
				INSERT INTO erp_inventory_valuation_details (
					type,
					biller_id,
					product_id,
					product_code,
					product_name,
					category_id,
					reference_no,
					date,
					NAME,
					quantity,
					warehouse_id,
					cost,
					qty_on_hand,
					avg_cost,
					created_by,
					updated_by,
					updated_at,
					field_id
					)
					VALUES
					(
					v_transaction_type,
					v_biller_id,
					NEW.product_id,
					NEW.product_code,
					NEW.product_name,
					v_category_id,
					v_reference_no,
					v_date ,
					v_supplier,
					NEW.quantity_balance,
					NEW.warehouse_id,
					v_cost,
					v_qoh,
					v_cost,
					v_created_by,
					v_updated_by,
					v_updated_at,
					v_transaction_id
				);
			END IF;
														
		END IF;
		/* End PURCHASE */
			
		/* PURCHASE RETURN */
		IF NEW.transaction_type = 'PURCHASE RETURN' THEN
			SET v_cost = (SELECT erp_products.cost FROM erp_products WHERE erp_products.id = NEW.product_id);
			SET v_category_id = (SELECT erp_products.category_id FROM erp_products WHERE erp_products.id = NEW.product_id);
			SET v_qoh =  (SELECT erp_products.quantity FROM erp_products WHERE erp_products.id = NEW.product_id);
			
			SET v_date = (SELECT
				erp_return_purchases.date
			FROM
				erp_return_purchases
				JOIN erp_return_purchase_items ON erp_return_purchases.id = erp_return_purchase_items.return_id
			WHERE
				erp_return_purchase_items.id = v_transaction_id
			LIMIT 0,1);
			SET v_reference_no = (SELECT
						erp_return_purchases.reference_no
					FROM
						erp_return_purchases
						JOIN erp_return_purchase_items ON erp_return_purchases.id = erp_return_purchase_items.return_id
					WHERE
						erp_return_purchase_items.id = v_transaction_id
					LIMIT 0,1);
			SET v_supplier = (SELECT
						erp_return_purchases.supplier
					FROM
						erp_return_purchases
						JOIN erp_return_purchase_items ON erp_return_purchases.id = erp_return_purchase_items.return_id
					WHERE
						erp_return_purchase_items.id = v_transaction_id
					LIMIT 0,1);
			SET v_biller_id = (SELECT
						erp_return_purchases.biller_id
					FROM
						erp_return_purchases
						JOIN erp_return_purchase_items ON erp_return_purchases.id = erp_return_purchase_items.return_id
					WHERE
						erp_return_purchase_items.id = v_transaction_id
					LIMIT 0,1);
			SET v_created_by = (SELECT
						erp_return_purchases.created_by
					FROM
						erp_return_purchases
						JOIN erp_return_purchase_items ON erp_return_purchases.id = erp_return_purchase_items.return_id
					WHERE
						erp_return_purchase_items.id = v_transaction_id
					LIMIT 0,1);
			SET v_updated_by = (SELECT
						erp_return_purchases.updated_by
					FROM
						erp_return_purchases
						JOIN erp_return_purchase_items ON erp_return_purchases.id = erp_return_purchase_items.return_id
					WHERE
						erp_return_purchase_items.id = v_transaction_id
					LIMIT 0,1);
			SET v_updated_at = (SELECT
						erp_return_purchases.updated_at
					FROM
						erp_return_purchases
						JOIN erp_return_purchase_items ON erp_return_purchases.id = erp_return_purchase_items.return_id
					WHERE
						erp_return_purchase_items.id = v_transaction_id
					LIMIT 0,1);
			
			DELETE FROM erp_inventory_valuation_details WHERE reference_no = v_reference_no AND product_id = NEW.product_id AND type = 'PURCHASE RETURN' AND field_id = v_transaction_id;
			
			INSERT INTO erp_inventory_valuation_details (
				type,
				biller_id,
				product_id,
				product_code,
				product_name,
				category_id,
				reference_no,
				date,
				NAME,
				quantity,
				warehouse_id,
				cost,
				qty_on_hand,
				avg_cost,
				created_by,
				updated_by,
				updated_at,
				field_id
				)
				VALUES
				(
				v_transaction_type,
				v_biller_id,
				NEW.product_id,
				NEW.product_code,
				NEW.product_name,
				v_category_id,
				v_reference_no,
				v_date ,
				v_supplier,
				(-1)*NEW.quantity_balance,
				NEW.warehouse_id,
				v_cost,
				v_qoh,
				v_cost,
				v_created_by,
				v_updated_by,
				v_updated_at,
				v_transaction_id
			);
		END IF;
		/* End PURCHASE RETURN */
		
		/* TRANSFER */
		IF NEW.transaction_type = 'TRANSFER' THEN
			SET v_cost = (SELECT erp_products.cost FROM erp_products WHERE erp_products.id = NEW.product_id);
			SET v_category_id = (SELECT erp_products.category_id FROM erp_products WHERE erp_products.id = NEW.product_id);
			SET v_qoh =  (SELECT erp_products.quantity FROM erp_products WHERE erp_products.id = NEW.product_id);
		
			SET v_date = (SELECT
				erp_transfers.date
			FROM
				erp_transfers
				JOIN erp_transfer_items ON erp_transfers.id = erp_transfer_items.transfer_id
			WHERE
				erp_transfer_items.id = v_transaction_id
			LIMIT 0,1);
			SET v_reference_no = (SELECT
						erp_transfers.transfer_no
					FROM
						erp_transfers
						JOIN erp_transfer_items ON erp_transfers.id = erp_transfer_items.transfer_id
					WHERE
						erp_transfer_items.id = v_transaction_id
					LIMIT 0,1);
			SET v_biller_id = (SELECT
						erp_transfers.biller_id
					FROM
						erp_transfers
						JOIN erp_transfer_items ON erp_transfers.id = erp_transfer_items.transfer_id
					WHERE
						erp_transfer_items.id = v_transaction_id
					LIMIT 0,1);
			SET v_created_by = (SELECT
						erp_transfers.created_by
					FROM
						erp_transfers
						JOIN erp_transfer_items ON erp_transfers.id = erp_transfer_items.transfer_id
					WHERE
						erp_transfer_items.id = v_transaction_id
					LIMIT 0,1);
			SET v_status = (SELECT
						erp_transfers.status
					FROM
						erp_transfers
						JOIN erp_transfer_items ON erp_transfers.id = erp_transfer_items.transfer_id
					WHERE
						erp_transfer_items.id = v_transaction_id
					LIMIT 0,1);
					
			DELETE FROM erp_inventory_valuation_details WHERE reference_no = v_reference_no AND product_id = NEW.product_id AND type = 'TRANSFER' AND field_id = v_transaction_id;
			
			INSERT INTO erp_inventory_valuation_details (
				type,
				biller_id,
				product_id,
				product_code,
				product_name,
				category_id,
				reference_no,
				date,
				NAME,
				quantity,
				warehouse_id,
				cost,
				qty_on_hand,
				avg_cost,
				created_by,
				field_id
				)
				VALUES
				(
					v_transaction_type,
					v_biller_id,
					NEW.product_id,
					NEW.product_code,
					NEW.product_name,
					v_category_id,
					v_reference_no,
					v_date,
					NULL,
					(-1)*NEW.quantity_balance,
					NEW.warehouse_id,
					v_cost,
					v_qoh,
					v_cost,
					v_created_by,
					v_transaction_id
				);
		END IF;
		/* End TRANSFER */
		
		/* USING STOCK */
		IF NEW.transaction_type = 'USING STOCK' THEN
			SET v_cost = (SELECT cost FROM erp_products WHERE id = NEW.product_id);
			SET v_category_id = (SELECT category_id FROM erp_products WHERE id = NEW.product_id);
			SET v_qoh =  (SELECT quantity FROM erp_products WHERE id = NEW.product_id);

			SET v_date = (SELECT
				erp_enter_using_stock.date
			FROM
				erp_enter_using_stock
				JOIN erp_enter_using_stock_items ON erp_enter_using_stock.reference_no = erp_enter_using_stock_items.reference_no
			WHERE
				erp_enter_using_stock_items.id = v_transaction_id
			LIMIT 0,1);
			SET v_reference_no = (SELECT
						erp_enter_using_stock.reference_no
					FROM
						erp_enter_using_stock
						JOIN erp_enter_using_stock_items ON erp_enter_using_stock.reference_no = erp_enter_using_stock_items.reference_no
					WHERE
						erp_enter_using_stock_items.id = v_transaction_id
					LIMIT 0,1);
			SET v_biller_id = (SELECT
						erp_enter_using_stock.shop
					FROM
						erp_enter_using_stock
						JOIN erp_enter_using_stock_items ON erp_enter_using_stock.reference_no = erp_enter_using_stock_items.reference_no
					WHERE
						erp_enter_using_stock_items.id = v_transaction_id
					LIMIT 0,1);
			SET v_created_by = (SELECT
						erp_enter_using_stock.create_by
					FROM
						erp_enter_using_stock
						JOIN erp_enter_using_stock_items ON erp_enter_using_stock.reference_no = erp_enter_using_stock_items.reference_no
					WHERE
						erp_enter_using_stock_items.id = v_transaction_id
					LIMIT 0,1);
					
			DELETE FROM erp_inventory_valuation_details WHERE reference_no = v_reference_no AND product_id = NEW.product_id AND type = 'USING STOCK' AND field_id = v_transaction_id;
			
			INSERT INTO erp_inventory_valuation_details (
				type,
				biller_id,
				product_id,
				product_code,
				product_name,
				category_id,
				reference_no,
				date,
				NAME,
				quantity,
				warehouse_id,
				cost,
				qty_on_hand,
				avg_cost,
				created_by,
				field_id
				)
				VALUES
				(
				v_transaction_type,
				v_biller_id,
				NEW.product_id,
				NEW.product_code,
				NEW.product_name,
				v_category_id,
				v_reference_no,
				v_date ,
				NULL,
				(-1)*NEW.quantity_balance,
				NEW.warehouse_id,
				v_cost,
				v_qoh,
				v_cost,
				v_created_by,
				v_transaction_id
			);
		END IF;
		/* End USING STOCK */
		
		/* ADJUSTMENT */
		IF NEW.transaction_type = 'ADJUSTMENT' THEN
			SET v_cost = (SELECT erp_products.cost FROM erp_products WHERE erp_products.id = NEW.product_id);
			SET v_category_id = (SELECT erp_products.category_id FROM erp_products WHERE erp_products.id = NEW.product_id);
			SET v_qoh =  (SELECT erp_products.quantity FROM erp_products WHERE erp_products.id = NEW.product_id);
			
			SET v_date = (SELECT
				erp_adjustments.date
			FROM
				erp_adjustments
			WHERE
				erp_adjustments.id = v_transaction_id
			LIMIT 0,1);
			SET v_biller_id = (SELECT
						erp_adjustments.biller_id
					FROM
						erp_adjustments
                                                                                             INNER JOIN erp_adjustment_items ON erp_adjustment_items.adjust_id = erp_adjustments.id
					WHERE
						erp_adjustment_items.id = v_transaction_id
					LIMIT 0,1);
			SET v_created_by = (SELECT
						erp_adjustments.created_by
					FROM
						erp_adjustments
                                                                                             INNER JOIN erp_adjustment_items ON erp_adjustment_items.adjust_id = erp_adjustments.id
					WHERE
						erp_adjustment_items.id  = v_transaction_id
					LIMIT 0,1);
			SET v_updated_by = (SELECT
						erp_adjustments.updated_by
					FROM
						erp_adjustments
                                                                                             INNER JOIN erp_adjustment_items ON erp_adjustment_items.adjust_id = erp_adjustments.id
					WHERE
						erp_adjustment_items.id  =  v_transaction_id
					LIMIT 0,1);
			SET v_status = (SELECT
						type
					FROM
						erp_adjustment_items
					WHERE
						id = v_transaction_id
					LIMIT 0,1);
					
			DELETE FROM erp_inventory_valuation_details WHERE reference_no = v_reference_no AND product_id = NEW.product_id AND type = 'ADJUSTMENT' AND field_id = v_transaction_id;
			
			IF v_status = 'addition' THEN
				
				INSERT INTO erp_inventory_valuation_details (
					type,
					biller_id,
					product_id,
					product_code,
					product_name,
					category_id,
					reference_no,
					date,
					NAME,
					quantity,
					warehouse_id,
					cost,
					qty_on_hand,
					avg_cost,
					created_by,
					updated_by,
					field_id
					)
					VALUES
					(
						v_transaction_type,
						v_biller_id,
						NEW.product_id,
						NEW.product_code,
						NEW.product_name,
						v_category_id,
						NULL,
						v_date ,
						v_supplier,
						NEW.quantity_balance,
						NEW.warehouse_id,
						v_cost,
						v_qoh,
						v_cost,
						v_created_by,
						v_updated_by,
						v_transaction_id
					);
				
			ELSE 
			
				INSERT INTO erp_inventory_valuation_details (
					type,
					biller_id,
					product_id,
					product_code,
					product_name,
					category_id,
					reference_no,
					date,
					NAME,
					quantity,
					warehouse_id,
					cost,
					qty_on_hand,
					avg_cost,
					created_by,
					updated_by,
					field_id
					)
					VALUES
					(
					v_transaction_type,
					v_biller_id,
					NEW.product_id,
					NEW.product_code,
					NEW.product_name,
					v_category_id,
					NULL,
					v_date ,
					v_supplier,
					NEW.quantity_balance,
					NEW.warehouse_id,
					v_cost,
					v_qoh,
					v_cost,
					v_created_by,
					v_updated_by,
					v_transaction_id
				);
			
			END IF;
			
		END IF;
		/* End ADJUSTMENT */
		
		/* CONVERT */
		IF NEW.transaction_type = 'CONVERT' THEN
			SET v_cost = (SELECT erp_products.cost FROM erp_products WHERE erp_products.id = NEW.product_id);
			SET v_category_id = (SELECT erp_products.category_id FROM erp_products WHERE erp_products.id = NEW.product_id);
			SET v_qoh =  (SELECT erp_products.quantity FROM erp_products WHERE erp_products.id = NEW.product_id);
			
			SET v_date = (SELECT
				erp_convert.date
			FROM
				erp_convert
				JOIN erp_convert_items ON erp_convert.id = erp_convert_items.convert_id
			WHERE
				erp_convert_items.id = v_transaction_id
			LIMIT 0,1);
			SET v_reference_no = (SELECT
						erp_convert.reference_no
					FROM
						erp_convert
						JOIN erp_convert_items ON erp_convert.id = erp_convert_items.convert_id
					WHERE
						erp_purchase_items.id = v_transaction_id
					LIMIT 0,1);
			SET v_supplier = (SELECT
						erp_convert.customer
					FROM
						erp_convert
						JOIN erp_convert_items ON erp_convert.id = erp_convert_items.convert_id
					WHERE
						erp_purchase_items.id = v_transaction_id
					LIMIT 0,1);
			SET v_biller_id = (SELECT
						erp_convert.biller_id
					FROM
						erp_convert
						JOIN erp_convert_items ON erp_convert.id = erp_convert_items.convert_id
					WHERE
						erp_purchase_items.id = v_transaction_id
					LIMIT 0,1);
			SET v_created_by = (SELECT
						erp_convert.created_by
					FROM
						erp_purchases
						JOIN erp_convert_items ON erp_convert.id = erp_convert_items.convert_id
					WHERE
						erp_purchase_items.id = v_transaction_id
					LIMIT 0,1);
			SET v_updated_by = (SELECT
						erp_convert.updated_by
					FROM
						erp_convert
						JOIN erp_convert_items ON erp_convert.id = erp_convert_items.convert_id
					WHERE
						erp_purchase_items.id = v_transaction_id
					LIMIT 0,1);
			SET v_status = (SELECT
						erp_convert.status
					FROM
						erp_convert
						JOIN erp_convert_items ON erp_convert.id = erp_convert_items.convert_id
					WHERE
						erp_purchase_items.id = v_transaction_id
					LIMIT 0,1);
			DELETE FROM erp_inventory_valuation_details WHERE reference_no = v_reference_no AND product_id = NEW.product_id AND type = 'CONVERT' AND field_id = v_transaction_id;
			IF v_status = 'add' THEN
			
				INSERT INTO erp_inventory_valuation_details (
					type,
					biller_id,
					product_id,
					product_code,
					product_name,
					category_id,
					reference_no,
					date,
					NAME,
					quantity,
					warehouse_id,
					cost,
					qty_on_hand,
					avg_cost,
					created_by,
					updated_by,
					field_id
					)
					VALUES
					(
					v_transaction_type,
					v_biller_id,
					NEW.product_id,
					NEW.product_code,
					NEW.product_name,
					v_category_id,
					v_reference_no,
					v_date ,
					NULL,
					NEW.quantity_balance,
					NEW.warehouse_id,
					v_cost,
					v_qoh,
					v_cost,
					v_created_by,
					v_updated_by,
					v_transaction_id
				);
				
			ELSE 
			
				INSERT INTO erp_inventory_valuation_details (
					type,
					biller_id,
					product_id,
					product_code,
					product_name,
					category_id,
					reference_no,
					date,
					NAME,
					quantity,
					warehouse_id,
					cost,
					qty_on_hand,
					avg_cost,
					created_by,
					updated_by,
					field_id
					)
					VALUES
					(
						v_transaction_type,
						v_biller_id,
						NEW.product_id,
						NEW.product_code,
						NEW.product_name,
						v_category_id,
						v_reference_no,
						v_date ,
						NULL,
						(-1)*NEW.quantity_balance,
						NEW.warehouse_id,
						v_cost,
						v_qoh,
						v_cost,
						v_created_by,
						v_updated_by,
						v_transaction_id
					);
			
			END IF;
			
		END IF;
		/* End CONVERT */
	END IF;

END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `audit_purchase_items_delete`;
DELIMITER ;;
CREATE TRIGGER `audit_purchase_items_delete` BEFORE DELETE ON `erp_purchase_items` FOR EACH ROW BEGIN
	IF OLD.id THEN
		INSERT INTO erp_purchase_items_audit (
			id,
			delivery_id,
			purchase_id,
			transfer_id,
			product_id,
			product_code,
			product_name,
			option_id,
			product_type,
			net_unit_cost,
			quantity,
			warehouse_id,
			item_tax,
			tax_rate_id,
			tax,
			discount,
			item_discount,
			expiry,
			subtotal,
			quantity_balance,
			date,
			status,
			unit_cost,
			real_unit_cost,
			quantity_received,
			supplier_part_no,
			supplier_id,
			job_name,
			note,
			convert_id,
			type,
			reference,
			opening_stock,
			create_id,
			returned,
			acc_cate_separate,
			specific_tax_on_certain_merchandise_and_services,
			accommodation_tax,
			public_lighting_tax,
			other_tax,
			payment_of_profit_tax,
			performance_royalty_intangible,
			payment_interest_non_bank,
			payment_interest_taxpayer_fixed,
			payment_interest_taxpayer_non_fixed,
			payment_rental_immovable_property,
			payment_of_interest,
			payment_royalty_rental_income_related,
			payment_management_technical,
			payment_dividend,
			withholding_tax_on_resident,
			withholding_tax_on_non_resident,
			audit_type
		) SELECT 
			id,
			delivery_id,
			purchase_id,
			transfer_id,
			product_id,
			product_code,
			product_name,
			option_id,
			product_type,
			net_unit_cost,
			quantity,
			warehouse_id,
			item_tax,
			tax_rate_id,
			tax,
			discount,
			item_discount,
			expiry,
			subtotal,
			quantity_balance,
			date,
			status,
			unit_cost,
			real_unit_cost,
			quantity_received,
			supplier_part_no,
			supplier_id,
			job_name,
			note,
			convert_id,
			type,
			reference,
			opening_stock,
			create_id,
			returned,
			acc_cate_separate,
			specific_tax_on_certain_merchandise_and_services,
			accommodation_tax,
			public_lighting_tax,
			other_tax,
			payment_of_profit_tax,
			performance_royalty_intangible,
			payment_interest_non_bank,
			payment_interest_taxpayer_fixed,
			payment_interest_taxpayer_non_fixed,
			payment_rental_immovable_property,
			payment_of_interest,
			payment_royalty_rental_income_related,
			payment_management_technical,
			payment_dividend,
			withholding_tax_on_resident,
			withholding_tax_on_non_resident,
			"DELETED"
		FROM
			erp_purchase_items
		WHERE
			erp_purchase_items.id = OLD.id;
	END IF;

END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `gl_trans_purchase_items_delete`;
DELIMITER ;;
CREATE TRIGGER `gl_trans_purchase_items_delete` AFTER DELETE ON `erp_purchase_items` FOR EACH ROW BEGIN
DECLARE v_reference_no VARCHAR(55);
DECLARE v_status VARCHAR(50);
DECLARE v_transaction_id INTEGER;
DECLARE v_transaction_type VARCHAR(25);

IF OLD.product_id THEN

	SET v_transaction_id = OLD.transaction_id;
	SET v_transaction_type = OLD.transaction_type;
	
	IF OLD.transaction_id THEN 
		
		/* SALE */
		IF v_transaction_type = 'SALE' THEN
			IF v_status = 'completed' THEN
				DELETE FROM erp_inventory_valuation_details WHERE reference_no = v_reference_no AND product_id = OLD.product_id AND type = 'SALE' AND field_id = v_transaction_id;
			END IF;
		END IF;
		/* End SALE */
		
		/* SALE RETURN */
		IF v_transaction_type = 'SALE RETURN' THEN
			DELETE FROM erp_inventory_valuation_details WHERE reference_no = v_reference_no AND product_id = OLD.product_id AND type = 'SALE RETURN' AND field_id = v_transaction_id;
		END IF;
		/* End SALE RETURN */
		
		/* PURCHASE */
		IF v_transaction_type = 'PURCHASE' THEN
			IF v_status = 'received' THEN
				DELETE FROM erp_inventory_valuation_details WHERE reference_no = v_reference_no AND product_id = OLD.product_id AND type = 'PURCHASE' AND field_id = v_transaction_id;
			END IF;
		END IF;
		/* End PURCHASE */
		
		/* PURCHASE RETURN */
		IF v_transaction_type = 'PURCHASE RETURN' THEN	
			DELETE FROM erp_inventory_valuation_details WHERE reference_no = v_reference_no AND product_id = OLD.product_id AND type = 'PURCHASE RETURN' AND field_id = v_transaction_id;
		END IF;
		/* End PURCHASE RETURN */
		
		/* TRANSFER */
		IF v_transaction_type = 'TRANSFER' THEN
			DELETE FROM erp_inventory_valuation_details WHERE reference_no = v_reference_no AND product_id = OLD.product_id AND type = 'TRANSFER' AND field_id = v_transaction_id;
		END IF;
		/* End TRANSFER */
		
		/* USING STOCK */
		IF v_transaction_type = 'USING STOCK' THEN
			DELETE FROM erp_inventory_valuation_details WHERE reference_no = v_reference_no AND product_id = OLD.product_id AND type = 'USING STOCK' AND field_id = v_transaction_id;
		END IF;
		/* End USING STOCK */
		
		/* ADJUSTMENT */
		IF v_transaction_type = 'ADJUSTMENT' THEN
			DELETE FROM erp_inventory_valuation_details WHERE reference_no = v_reference_no AND product_id = OLD.product_id AND type = 'ADJUSTMENT' AND field_id = v_transaction_id;
		END IF;
		/* End ADJUSTMENT */
		
		/* CONVERT */
		IF v_transaction_type = 'CONVERT' THEN
			DELETE FROM erp_inventory_valuation_details WHERE reference_no = v_reference_no AND product_id = OLD.product_id AND type = 'CONVERT' AND field_id = v_transaction_id;			
		END IF;
		/* End CONVERT */
	END IF;
END IF;

END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `gl_trans_return_items_insert`;
DELIMITER ;;
CREATE TRIGGER `gl_trans_return_items_insert` AFTER INSERT ON `erp_return_items` FOR EACH ROW BEGIN

	DECLARE v_tran_no INTEGER;
	DECLARE v_tran_date DATETIME;
	DECLARE v_tran_reference_no VARCHAR(55);
	DECLARE v_tran_customer VARCHAR(55);
	DECLARE v_tran_biller_id INTEGER;
	DECLARE v_tran_created_by INTEGER;
	DECLARE v_tran_updated_by INTEGER;
	DECLARE v_tax_type INTEGER;
	DECLARE v_order_tax_id INTEGER;
	DECLARE v_sale_type INTEGER;
	DECLARE v_acc_cate_separate INTEGER;
	DECLARE v_tran_note VARCHAR(255);
	DECLARE v_category_id INTEGER;
	
	SET v_acc_cate_separate = (SELECT erp_settings.acc_cate_separate FROM erp_settings WHERE erp_settings.setting_id = '1');
	
	IF v_acc_cate_separate = 1 THEN
	
		SET v_tran_date = (SELECT erp_return_sales.date 
			FROM erp_return_sales
			WHERE erp_return_sales.id = NEW.return_id LIMIT 0,1);
			
		SET v_tran_no = (SELECT MAX(tran_no) FROM erp_gl_trans);
		SET v_tran_reference_no = (SELECT erp_return_sales.reference_no FROM erp_return_sales WHERE erp_return_sales.id = NEW.return_id);
		SET v_tran_customer = (SELECT erp_return_sales.customer FROM erp_return_sales WHERE erp_return_sales.id = NEW.return_id);
		SET v_tran_biller_id = (SELECT erp_return_sales.biller_id FROM erp_return_sales WHERE erp_return_sales.id = NEW.return_id);
		SET v_tran_created_by = (SELECT erp_return_sales.created_by FROM erp_return_sales WHERE erp_return_sales.id = NEW.return_id);
		SET v_tran_updated_by = (SELECT erp_return_sales.updated_by FROM erp_return_sales WHERE erp_return_sales.id = NEW.return_id);
		SET v_tran_note = (SELECT erp_return_sales.note FROM erp_return_sales WHERE erp_return_sales.id = NEW.return_id);
		SET v_category_id = (SELECT erp_products.category_id FROM erp_products WHERE erp_products.id = NEW.product_id);
		
		
		INSERT INTO erp_gl_trans (
			tran_type,
			tran_no,
			tran_date,
			sectionid,
			account_code,
			narrative,
			amount,
			reference_no,
			description,
			biller_id,
			created_by,
			updated_by
		) SELECT
			'SALES-RETURN',
			v_tran_no,
			v_tran_date,
			erp_gl_sections.sectionid,
			erp_gl_charts.accountcode,
			erp_gl_charts.accountname,
			abs(NEW.subtotal),
			v_tran_reference_no,
			v_tran_customer,
			v_tran_biller_id,
			v_tran_created_by,
			v_tran_updated_by
			FROM
				erp_categories
				INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_categories.ac_sale
				INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
			WHERE
				erp_categories.id = v_category_id;
		
		IF (NEW.unit_cost * NEW.quantity) > 0 THEN
		
			INSERT INTO erp_gl_trans (
				tran_type,
				tran_no,
				tran_date,
				sectionid,
				account_code,
				narrative,
				amount,
				reference_no,
				description,
				biller_id,
				created_by,
				updated_by
				) SELECT
				'SALES-RETURN',
				v_tran_no,
				v_tran_date,
				erp_gl_sections.sectionid,
				erp_gl_charts.accountcode,
				erp_gl_charts.accountname,
				(-1) * (NEW.unit_cost * NEW.quantity),
				v_tran_reference_no,
				v_tran_customer,
				v_tran_biller_id,
				v_tran_created_by,
				v_tran_updated_by
				FROM
					erp_categories
					INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_categories.ac_cost
					INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
				WHERE
					erp_categories.id = v_category_id;

			INSERT INTO erp_gl_trans (
				tran_type,
				tran_no,
				tran_date,
				sectionid,
				account_code,
				narrative,
				amount,
				reference_no,
				description,
				biller_id,
				created_by,
				updated_by
				) SELECT
				'SALES-RETURN',
				v_tran_no,
				v_tran_date,
				erp_gl_sections.sectionid,
				erp_gl_charts.accountcode,
				erp_gl_charts.accountname,
				abs(NEW.unit_cost * NEW.quantity),
				v_tran_reference_no,
				v_tran_customer,
				v_tran_biller_id,
				v_tran_created_by,
				v_tran_updated_by
				FROM
					erp_categories
					INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_categories.ac_stock
					INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
				WHERE
					erp_categories.id = v_category_id;
					
		END IF;
		
	END IF;
END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `gl_trans_return_purchse_insert`;
DELIMITER ;;
CREATE TRIGGER `gl_trans_return_purchse_insert` AFTER INSERT ON `erp_return_purchases` FOR EACH ROW BEGIN

    DECLARE v_tran_no INTEGER;
	DECLARE v_tran_date DATETIME;
	DECLARE v_acc_cate_separate INTEGER;

	SET v_tran_date = (
		SELECT
			erp_purchases.date
		FROM
			erp_payments
		INNER JOIN erp_purchases ON erp_purchases.id = erp_payments.purchase_id
		WHERE
			erp_purchases.id = NEW.id
		LIMIT 0,
		1
	);

	SET v_acc_cate_separate = (
		SELECT 
			erp_settings.acc_cate_separate 
		FROM 
			erp_settings 
		WHERE 
			erp_settings.setting_id = '1'
	);
	
	IF v_tran_date = NEW.date THEN

		SET v_tran_no = (
			SELECT
				MAX(tran_no)
			FROM
				erp_gl_trans
		);

	ELSE

		SET v_tran_no = (
			SELECT
				COALESCE (MAX(tran_no), 0) + 1
			FROM
				erp_gl_trans
		);

		UPDATE erp_order_ref
		SET tr = v_tran_no
		WHERE
			DATE_FORMAT(date, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m');

	END IF;

	IF  v_acc_cate_separate <> 1 THEN
		INSERT INTO erp_gl_trans (
			tran_type,
			tran_no,
			tran_date,
			sectionid,
			account_code,
			narrative,
			amount,
			reference_no,
			description,
			biller_id,
			created_by,
			updated_by
		) SELECT
			'PURCHASES-RETURN',
			v_tran_no,
			NEW.date,
			erp_gl_sections.sectionid,
			erp_gl_charts.accountcode,
			erp_gl_charts.accountname,
			(- 1) * (
				NEW.total + NEW.product_discount + NEW.shipping
			),
			NEW.reference_no,
			NEW.supplier,
			NEW.biller_id,
			NEW.created_by,
			NEW.updated_by
		FROM
			erp_account_settings
		INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_account_settings.default_purchase
		INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
		WHERE
			erp_gl_charts.accountcode = erp_account_settings.default_purchase;
	END IF;

	INSERT INTO erp_gl_trans (
		tran_type,
		tran_no,
		tran_date,
		sectionid,
		account_code,
		narrative,
		amount,
		reference_no,
		description,
		biller_id,
		created_by,
		updated_by
	) SELECT
		'PURCHASES-RETURN',
		v_tran_no,
		NEW.date,
		erp_gl_sections.sectionid,
		erp_gl_charts.accountcode,
		erp_gl_charts.accountname,
		abs(NEW.grand_total),
		NEW.reference_no,
		NEW.supplier,
		NEW.biller_id,
		NEW.created_by,
		NEW.updated_by
	FROM
		erp_account_settings
	INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_account_settings.default_payable
	INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
	WHERE
		erp_gl_charts.accountcode = erp_account_settings.default_payable;

	IF NEW.total_discount THEN
		INSERT INTO erp_gl_trans (
			tran_type,
			tran_no,
			tran_date,
			sectionid,
			account_code,
			narrative,
			amount,
			reference_no,
			description,
			biller_id,
			created_by,
			updated_by
		) SELECT
			'PURCHASES-RETURN',
			v_tran_no,
			NEW.date,
			erp_gl_sections.sectionid,
			erp_gl_charts.accountcode,
			erp_gl_charts.accountname,
			abs(NEW.total_discount),
			NEW.reference_no,
			NEW.supplier,
			NEW.biller_id,
			NEW.created_by,
			NEW.updated_by
		FROM
			erp_account_settings
		INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_account_settings.default_purchase_discount
		INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
		WHERE
			erp_gl_charts.accountcode = erp_account_settings.default_purchase_discount;


	END IF;

	IF NEW.total_tax THEN
		INSERT INTO erp_gl_trans (
			tran_type,
			tran_no,
			tran_date,
			sectionid,
			account_code,
			narrative,
			amount,
			reference_no,
			description,
			biller_id,
			created_by,
			updated_by
		) SELECT
			'PURCHASES-RETURN',
			v_tran_no,
			NEW.date,
			erp_gl_sections.sectionid,
			erp_gl_charts.accountcode,
			erp_gl_charts.accountname,
			(- 1) * NEW.total_tax,
			NEW.reference_no,
			NEW.supplier,
			NEW.biller_id,
			NEW.created_by,
			NEW.updated_by
		FROM
			erp_account_settings
		INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_account_settings.default_purchase_tax
		INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
		WHERE
			erp_gl_charts.accountcode = erp_account_settings.default_purchase_tax;

	END IF;

END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `gl_trans_return_sales_insert`;
DELIMITER ;;
CREATE TRIGGER `gl_trans_return_sales_insert` AFTER INSERT ON `erp_return_sales` FOR EACH ROW BEGIN

	DECLARE v_tran_no INTEGER;
	DECLARE v_tran_date DATETIME;
	DECLARE v_acc_cate_separate INTEGER;
	
	SET v_acc_cate_separate = (SELECT erp_settings.acc_cate_separate FROM erp_settings WHERE erp_settings.setting_id = '1');

	SET v_tran_date = (SELECT erp_return_sales.date 
			FROM erp_payments 
			INNER JOIN erp_return_sales ON erp_return_sales.id = erp_payments.return_id
			WHERE erp_return_sales.id = NEW.id LIMIT 0,1);

	IF v_tran_date = NEW.date THEN
		SET v_tran_no = (SELECT MAX(tran_no) FROM erp_gl_trans);
	ELSE
		SET v_tran_no = (SELECT COALESCE(MAX(tran_no),0) +1 FROM erp_gl_trans);
		
		UPDATE erp_order_ref
		SET tr = v_tran_no
		WHERE
		DATE_FORMAT(date, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m');
	END IF;

	IF NEW.grand_total > 0 THEN

		INSERT INTO erp_gl_trans (
			tran_type,
			tran_no,
			tran_date,
			sectionid,
			account_code,
			narrative,
			amount,
			reference_no,
			description,
			biller_id,
			created_by,
			updated_by
			) SELECT
			'SALES-RETURN',
			v_tran_no,
			NEW.date,
			erp_gl_sections.sectionid,
			erp_gl_charts.accountcode,
			erp_gl_charts.accountname,
			(-1) * (NEW.grand_total),
			NEW.reference_no,
			NEW.customer,
			NEW.biller_id,
			NEW.created_by,
			NEW.updated_by
			FROM
				erp_account_settings
				INNER JOIN erp_gl_charts
				ON erp_gl_charts.accountcode = erp_account_settings.default_receivable
				INNER JOIN erp_gl_sections
				ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
			WHERE
				erp_gl_charts.accountcode = erp_account_settings.default_receivable;
			
	END IF;
	
	IF v_acc_cate_separate <> 1 THEN
		
		INSERT INTO erp_gl_trans (
			tran_type,
			tran_no,
			tran_date,
			sectionid,
			account_code,
			narrative,
			amount,
			reference_no,
			description,
			biller_id,
			created_by,
			updated_by
		) SELECT
			'SALES-RETURN',
			v_tran_no,
			NEW.date,
			erp_gl_sections.sectionid,
			erp_gl_charts.accountcode,
			erp_gl_charts.accountname,
			abs(NEW.total),
			NEW.reference_no,
			NEW.customer,
			NEW.biller_id,
			NEW.created_by,

			NEW.updated_by
			FROM
				erp_account_settings
				INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_account_settings.default_sale
				INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
			WHERE
				erp_gl_charts.accountcode = erp_account_settings.default_sale;
		
		IF NEW.total_cost > 0 THEN
		
			INSERT INTO erp_gl_trans (
				tran_type,
				tran_no,
				tran_date,
				sectionid,
				account_code,
				narrative,
				amount,
				reference_no,
				description,
				biller_id,
				created_by,
				updated_by
				) SELECT
				'SALES-RETURN',
				v_tran_no,
				NEW.date,
				erp_gl_sections.sectionid,
				erp_gl_charts.accountcode,
				erp_gl_charts.accountname,
				(-1) * NEW.total_cost,
				NEW.reference_no,
				NEW.customer,
				NEW.biller_id,
				NEW.created_by,
				NEW.updated_by
				FROM
					erp_account_settings
					INNER JOIN erp_gl_charts
					ON erp_gl_charts.accountcode = erp_account_settings.default_cost
					INNER JOIN erp_gl_sections   ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
				WHERE
					erp_gl_charts.accountcode = erp_account_settings.default_cost;

			INSERT INTO erp_gl_trans (
				tran_type,
				tran_no,
				tran_date,
				sectionid,
				account_code,
				narrative,
				amount,
				reference_no,
				description,
				biller_id,
				created_by,
				updated_by
				) SELECT
				'SALES-RETURN',
				v_tran_no,
				NEW.date,
				erp_gl_sections.sectionid,
				erp_gl_charts.accountcode,
				erp_gl_charts.accountname,
				abs(NEW.total_cost),
				NEW.reference_no,
				NEW.customer,
				NEW.biller_id,
				NEW.created_by,
				NEW.updated_by
				FROM
					erp_account_settings
					INNER JOIN erp_gl_charts
					ON erp_gl_charts.accountcode = erp_account_settings.default_stock
					INNER JOIN erp_gl_sections   ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
				WHERE
					erp_gl_charts.accountcode = erp_account_settings.default_stock;
					
		END IF;
		
	END IF;

	IF NEW.order_discount THEN
		INSERT INTO erp_gl_trans (
			tran_type,
			tran_no,
			tran_date,
			sectionid,
			account_code,
			narrative,
			amount,
			reference_no,
			description,
			biller_id,
			created_by,
			updated_by
			) SELECT
			'SALES-RETURN',
			v_tran_no,
			NEW.date,
			erp_gl_sections.sectionid,
			erp_gl_charts.accountcode,
			erp_gl_charts.accountname,
			(-1) * NEW.order_discount,
			NEW.reference_no,
			NEW.customer,
			NEW.biller_id,
			NEW.created_by,
			NEW.updated_by
			FROM
				erp_account_settings
				INNER JOIN erp_gl_charts
				ON erp_gl_charts.accountcode = erp_account_settings.default_sale_discount
				INNER JOIN erp_gl_sections
				ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
			WHERE
				erp_gl_charts.accountcode = erp_account_settings.default_sale_discount;
	END IF;

	IF NEW.order_tax THEN
		INSERT INTO erp_gl_trans (
			tran_type,
			tran_no,
			tran_date,
			sectionid,
			account_code,
			narrative,
			amount,
			reference_no,
			description,
			biller_id,
			created_by,
			updated_by
			) SELECT
			'SALES-RETURN',
			v_tran_no,
			NEW.date,
			erp_gl_sections.sectionid,
			erp_gl_charts.accountcode,
			erp_gl_charts.accountname,
			abs(NEW.order_tax),
			NEW.reference_no,
			NEW.customer,
			NEW.biller_id,
			NEW.created_by,
			NEW.updated_by
			FROM
				erp_account_settings
				INNER JOIN erp_gl_charts
				ON erp_gl_charts.accountcode = erp_account_settings.default_sale_tax
				INNER JOIN erp_gl_sections
				ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
			WHERE
				erp_gl_charts.accountcode = erp_account_settings.default_sale_tax;
	END IF;

	IF NEW.shipping THEN
		INSERT INTO erp_gl_trans (
			tran_type,
			tran_no,
			tran_date,
			sectionid,
			account_code,
			narrative,
			amount,
			reference_no,
			description,
			biller_id,
			created_by,
			updated_by
			) SELECT
			'SALES-RETURN',
			v_tran_no,
			NEW.date,
			erp_gl_sections.sectionid,
			erp_gl_charts.accountcode,
			erp_gl_charts.accountname,
			abs(NEW.shipping),
			NEW.reference_no,
			NEW.customer,
			NEW.biller_id,
			NEW.created_by,
			NEW.updated_by
			FROM
				erp_account_settings
				INNER JOIN erp_gl_charts
				ON erp_gl_charts.accountcode = erp_account_settings.default_sale_freight
				INNER JOIN erp_gl_sections
				ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
			WHERE
				erp_gl_charts.accountcode = erp_account_settings.default_sale_freight;
	END IF;
	
END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `gl_trans_sales_insert`;
DELIMITER ;;
CREATE TRIGGER `gl_trans_sales_insert` AFTER INSERT ON `erp_sales` FOR EACH ROW BEGIN

	DECLARE v_tran_no INTEGER;
	DECLARE v_tran_date DATETIME;
	DECLARE v_acc_cate_separate INTEGER;
	
	SET v_acc_cate_separate = (SELECT erp_settings.acc_cate_separate FROM erp_settings WHERE erp_settings.setting_id = '1');

	IF NEW.opening_ar != 2 THEN
		IF NEW.total > 0 || NEW.total_discount THEN
			SET v_tran_date = (
				SELECT
					erp_sales.date
				FROM
					erp_payments
				INNER JOIN erp_sales ON erp_sales.id = erp_payments.sale_id
				WHERE
					erp_sales.id = NEW.id
				LIMIT 0,
				1
			);


			IF v_tran_date = NEW.date THEN
				SET v_tran_no = (
					SELECT
						COALESCE (MAX(tran_no), 0) + 1
					FROM
						erp_gl_trans
				);
			ELSE
				SET v_tran_no = (
					SELECT
						COALESCE (MAX(tran_no), 0) + 1
					FROM
						erp_gl_trans
				);
				UPDATE erp_order_ref
				SET tr = v_tran_no
				WHERE
					DATE_FORMAT(date, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m');


			END IF;

			IF NEW.opening_ar = 1 THEN
				INSERT INTO erp_gl_trans (
					tran_type,
					tran_no,
					tran_date,
					sectionid,
					account_code,
					narrative,
					amount,
					reference_no,
					description,
					biller_id,
					created_by,
					updated_by
				) SELECT
					'SALES',
					v_tran_no,
					NEW.date,
					erp_gl_sections.sectionid,
					erp_gl_charts.accountcode,
					erp_gl_charts.accountname,
					(- 1) * abs(
						NEW.total - NEW.product_tax
					),
					NEW.reference_no,
					NEW.note,
					NEW.biller_id,
					NEW.created_by,
					NEW.updated_by
				FROM
					erp_account_settings
				INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_account_settings.default_open_balance
				INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
				WHERE
					erp_gl_charts.accountcode = erp_account_settings.default_open_balance;

			ELSE
				IF v_acc_cate_separate <> 1 THEN
					INSERT INTO erp_gl_trans (
							tran_type,
							tran_no,
							tran_date,
							sectionid,
							account_code,
							narrative,
							amount,
							reference_no,
							description,
							biller_id,
							created_by,
							updated_by
						) SELECT
							'SALES',
							v_tran_no,
							NEW.date,
							erp_gl_sections.sectionid,
							erp_gl_charts.accountcode,
							erp_gl_charts.accountname,
							(- 1) * abs(
								NEW.total - NEW.product_tax
							),
							NEW.reference_no,
							NEW.note,
							NEW.biller_id,
							NEW.created_by,
							NEW.updated_by
						FROM
							erp_account_settings
						INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_account_settings.default_sale
						INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
						WHERE
							erp_gl_charts.accountcode = erp_account_settings.default_sale;

					IF NEW.sale_status = "completed" AND NEW.total_cost > 0 THEN		
						INSERT INTO erp_gl_trans (
							tran_type,
							tran_no,
							tran_date,
							sectionid,
							account_code,
							narrative,
							amount,
							reference_no,
							description,
							biller_id,
							created_by,
							updated_by
						) SELECT
							'SALES',
							v_tran_no,
							NEW.date,
							erp_gl_sections.sectionid,
							erp_gl_charts.accountcode,
							erp_gl_charts.accountname,
							NEW.total_cost,
							NEW.reference_no,
							NEW.customer,
							NEW.biller_id,
							NEW.created_by,
							NEW.updated_by
						FROM
							erp_account_settings
						INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_account_settings.default_cost
						INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
						WHERE
							erp_gl_charts.accountcode = erp_account_settings.default_cost;


						INSERT INTO erp_gl_trans (
							tran_type,
							tran_no,
							tran_date,
							sectionid,
							account_code,
							narrative,
							amount,
							reference_no,
							description,
							biller_id,
							created_by,
							updated_by
						) SELECT
							'SALES',
							v_tran_no,
							NEW.date,
							erp_gl_sections.sectionid,
							erp_gl_charts.accountcode,
							erp_gl_charts.accountname,
							(- 1) * abs(NEW.total_cost),
							NEW.reference_no,
							NEW.note,
							NEW.biller_id,
							NEW.created_by,
							NEW.updated_by
						FROM
							erp_account_settings
						INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_account_settings.default_stock
						INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
						WHERE
							erp_gl_charts.accountcode = erp_account_settings.default_stock;
					END IF;
				END IF;

				IF NEW.order_discount THEN
					INSERT INTO erp_gl_trans (
						tran_type,
						tran_no,
						tran_date,
						sectionid,
						account_code,
						narrative,
						amount,
						reference_no,
						description,
						biller_id,
						created_by,
						updated_by
					) SELECT
						'SALES',
						v_tran_no,
						NEW.date,
						erp_gl_sections.sectionid,
						erp_gl_charts.accountcode,
						erp_gl_charts.accountname,
						NEW.order_discount,
						NEW.reference_no,
						NEW.note,
						NEW.biller_id,
						NEW.created_by,
						NEW.updated_by
					FROM
						erp_account_settings
					INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_account_settings.default_sale_discount
					INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
					WHERE
						erp_gl_charts.accountcode = erp_account_settings.default_sale_discount;

				END IF;

				IF NEW.shipping THEN
					INSERT INTO erp_gl_trans (
						tran_type,
						tran_no,
						tran_date,
						sectionid,
						account_code,
						narrative,
						amount,
						reference_no,
						description,
						biller_id,
						created_by,
						updated_by
					) SELECT
						'SALES',
						v_tran_no,
						NEW.date,
						erp_gl_sections.sectionid,
						erp_gl_charts.accountcode,
						erp_gl_charts.accountname,
						(- 1) * abs(NEW.shipping),
						NEW.reference_no,
						NEW.note,
						NEW.biller_id,
						NEW.created_by,
						NEW.updated_by
					FROM
						erp_account_settings
					INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_account_settings.default_sale_freight
					INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
					WHERE
						erp_gl_charts.accountcode = erp_account_settings.default_sale_freight;
						
				END IF;

				IF NEW.order_tax THEN
					INSERT INTO erp_gl_trans (
						tran_type,
						tran_no,
						tran_date,
						sectionid,
						account_code,
						narrative,
						amount,
						reference_no,
						description,
						biller_id,
						created_by,
						updated_by
					) SELECT
						'SALES',
						v_tran_no,
						NEW.date,
						erp_gl_sections.sectionid,
						erp_gl_charts.accountcode,
						erp_gl_charts.accountname,
						(- 1) * abs(NEW.order_tax),
						NEW.reference_no,
						NEW.note,
						NEW.biller_id,
						NEW.created_by,
						NEW.updated_by
					FROM
						erp_account_settings
					INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_account_settings.default_sale_tax
					INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
					WHERE
						erp_gl_charts.accountcode = erp_account_settings.default_sale_tax;


				END IF;

			END IF;
		END IF;

		IF NEW.grand_total > NEW.paid THEN
			INSERT INTO erp_gl_trans (
				tran_type,
				tran_no,
				tran_date,
				sectionid,
				account_code,
				narrative,
				amount,
				reference_no,
				description,
				biller_id,
				created_by,
				updated_by
			) SELECT
				'SALES',
				v_tran_no,
				NEW.date,
				erp_gl_sections.sectionid,
				erp_gl_charts.accountcode,
				erp_gl_charts.accountname,
				(NEW.grand_total - NEW.paid),
				NEW.reference_no,
				NEW.customer,
				NEW.biller_id,
				NEW.created_by,
				NEW.updated_by
			FROM
				erp_account_settings
			INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_account_settings.default_receivable
			INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
			WHERE
				erp_gl_charts.accountcode = erp_account_settings.default_receivable;
		END IF;

	END IF;

	IF NEW.opening_ar = 2 THEN
		SET v_tran_date = (
			SELECT
				erp_sales.date
			FROM
				erp_payments
			INNER JOIN erp_sales ON erp_sales.id = erp_payments.sale_id
			WHERE
				erp_sales.id = NEW.id
			LIMIT 0,
			1
		);
		
		IF v_tran_date = NEW.date THEN
			SET v_tran_no = (
				SELECT
					COALESCE (MAX(tran_no), 0) + 1
				FROM
					erp_gl_trans
			);
		ELSE
			SET v_tran_no = (
				SELECT
					COALESCE (MAX(tran_no), 0) + 1
				FROM
					erp_gl_trans
			);
			
			UPDATE erp_order_ref
			SET tr = v_tran_no
			WHERE
				DATE_FORMAT(date, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m');


		END IF;
		
		INSERT INTO erp_gl_trans (
			tran_type,
			tran_no,
			tran_date,
			sectionid,
			account_code,
			narrative,
			amount,
			reference_no,
			description,
			biller_id,
			created_by,
			updated_by
		) SELECT
			'SALES',
			v_tran_no,
			NEW.date,
			erp_gl_sections.sectionid,
			erp_gl_charts.accountcode,
			erp_gl_charts.accountname,
			(- 1) * abs(
				NEW.total - NEW.product_tax
			),
			NEW.reference_no,
			NEW.note,
			NEW.biller_id,
			NEW.created_by,
			NEW.updated_by
		FROM
			erp_account_settings
		INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_account_settings.default_open_balance
		INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
		WHERE
			erp_gl_charts.accountcode = erp_account_settings.default_open_balance;
			
        IF NEW.paid <= 0 THEN
			INSERT INTO erp_gl_trans (
				tran_type,
				tran_no,
				tran_date,
				sectionid,
				account_code,
				narrative,
				amount,
				reference_no,
				description,
				biller_id,
				created_by,
				updated_by
				   ) SELECT
						'SALES',
						v_tran_no,
						NEW.date,
						erp_gl_sections.sectionid,
						erp_gl_charts.accountcode,
						erp_gl_charts.accountname,
						NEW.grand_total,
						NEW.reference_no,
						NEW.note,

						NEW.biller_id,
						NEW.created_by,
						NEW.updated_by
					FROM
					erp_account_settings
					   INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_account_settings.default_receivable
					   INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
					   WHERE
					erp_gl_charts.accountcode = erp_account_settings.default_receivable;
		END IF;
		
	END IF;

END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `gl_trans_sales_update`;
DELIMITER ;;
CREATE TRIGGER `gl_trans_sales_update` AFTER UPDATE ON `erp_sales` FOR EACH ROW BEGIN

	DECLARE v_tran_no INTEGER;
	DECLARE v_tran_date DATETIME;
	DECLARE v_acc_cate_separate INTEGER;
	
	SET v_acc_cate_separate = (SELECT erp_settings.acc_cate_separate FROM erp_settings WHERE erp_settings.setting_id = '1');

	IF NEW.opening_ar != 2 THEN
		IF NEW.total > 0 AND NEW.updated_by > 0 AND NEW.updated_count <> OLD.updated_count THEN

			SET v_tran_no = (SELECT tran_no FROM erp_gl_trans WHERE tran_type='SALES' AND reference_no = NEW.reference_no LIMIT 0,1);

			IF v_tran_no < 1  THEN
				SET v_tran_no = (SELECT COALESCE(MAX(tran_no),0) +1 FROM erp_gl_trans);	                
				UPDATE erp_order_ref SET tr = v_tran_no WHERE DATE_FORMAT(date, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m');
			END IF;

			DELETE FROM erp_gl_trans WHERE tran_type='SALES' AND reference_no = NEW.reference_no;
			
			IF NEW.opening_ar = 1 THEN
				INSERT INTO erp_gl_trans (
					tran_type,
					tran_no,
					tran_date,
					sectionid,
					account_code,
					narrative,
					amount,
					reference_no,
					description,
					biller_id,
					created_by,
					updated_by
				) SELECT
					'SALES',
					v_tran_no,
					NEW.date,
					erp_gl_sections.sectionid,
					erp_gl_charts.accountcode,
					erp_gl_charts.accountname,
					(- 1) * abs(
						NEW.total - NEW.product_tax
					),
					NEW.reference_no,
					NEW.note,
					NEW.biller_id,
					NEW.created_by,
					NEW.updated_by
				FROM
					erp_account_settings
				INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_account_settings.default_open_balance
				INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
				WHERE
					erp_gl_charts.accountcode = erp_account_settings.default_open_balance;

			ELSE
				IF v_acc_cate_separate <> 1 THEN
					INSERT INTO erp_gl_trans (
							tran_type,
							tran_no,
							tran_date,
							sectionid,
							account_code,
							narrative,
							amount,
							reference_no,
							description,
							biller_id,
							created_by,
							updated_by
						) SELECT
							'SALES',
							v_tran_no,
							NEW.date,
							erp_gl_sections.sectionid,
							erp_gl_charts.accountcode,
							erp_gl_charts.accountname,
							(- 1) * abs(
								NEW.total - NEW.product_tax
							),
							NEW.reference_no,
							NEW.note,
							NEW.biller_id,
							NEW.created_by,
							NEW.updated_by
						FROM
							erp_account_settings
						INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_account_settings.default_sale
						INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
						WHERE
							erp_gl_charts.accountcode = erp_account_settings.default_sale;

					IF NEW.sale_status = "completed" AND NEW.total_cost > 0 THEN	
						INSERT INTO erp_gl_trans (
							tran_type,
							tran_no,
							tran_date,
							sectionid,
							account_code,
							narrative,
							amount,
							reference_no,
							description,
							biller_id,
							created_by,
							updated_by
						) SELECT
							'SALES',
							v_tran_no,
							NEW.date,
							erp_gl_sections.sectionid,
							erp_gl_charts.accountcode,
							erp_gl_charts.accountname,
							NEW.total_cost,
							NEW.reference_no,
							NEW.customer,
							NEW.biller_id,
							NEW.created_by,
							NEW.updated_by
						FROM
							erp_account_settings
						INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_account_settings.default_cost
						INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
						WHERE
							erp_gl_charts.accountcode = erp_account_settings.default_cost;


						INSERT INTO erp_gl_trans (
							tran_type,
							tran_no,
							tran_date,
							sectionid,
							account_code,
							narrative,
							amount,
							reference_no,
							description,
							biller_id,
							created_by,
							updated_by
						) SELECT
							'SALES',
							v_tran_no,
							NEW.date,
							erp_gl_sections.sectionid,
							erp_gl_charts.accountcode,
							erp_gl_charts.accountname,
							(- 1) * abs(NEW.total_cost),
							NEW.reference_no,
							NEW.note,
							NEW.biller_id,
							NEW.created_by,
							NEW.updated_by
						FROM
							erp_account_settings
						INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_account_settings.default_stock
						INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
						WHERE
							erp_gl_charts.accountcode = erp_account_settings.default_stock;
					END IF;
				END IF;

				IF NEW.order_discount THEN
					INSERT INTO erp_gl_trans (
						tran_type,
						tran_no,
						tran_date,
						sectionid,
						account_code,
						narrative,
						amount,
						reference_no,
						description,
						biller_id,
						created_by,
						updated_by
					) SELECT
						'SALES',
						v_tran_no,
						NEW.date,
						erp_gl_sections.sectionid,
						erp_gl_charts.accountcode,
						erp_gl_charts.accountname,
						NEW.order_discount,
						NEW.reference_no,
						NEW.note,
						NEW.biller_id,
						NEW.created_by,
						NEW.updated_by
					FROM
						erp_account_settings

					INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_account_settings.default_sale_discount
					INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
					WHERE
						erp_gl_charts.accountcode = erp_account_settings.default_sale_discount;

				END IF;

				IF NEW.shipping THEN
					INSERT INTO erp_gl_trans (
						tran_type,
						tran_no,
						tran_date,
						sectionid,
						account_code,
						narrative,
						amount,
						reference_no,
						description,
						biller_id,
						created_by,
						updated_by
					) SELECT
						'SALES',
						v_tran_no,
						NEW.date,
						erp_gl_sections.sectionid,
						erp_gl_charts.accountcode,
						erp_gl_charts.accountname,
						(- 1) * abs(NEW.shipping),
						NEW.reference_no,
						NEW.note,
						NEW.biller_id,
						NEW.created_by,
						NEW.updated_by
					FROM
						erp_account_settings
					INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_account_settings.default_sale_freight
					INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
					WHERE
						erp_gl_charts.accountcode = erp_account_settings.default_sale_freight;
						
				END IF;

				IF NEW.order_tax THEN
					INSERT INTO erp_gl_trans (
						tran_type,
						tran_no,
						tran_date,
						sectionid,
						account_code,
						narrative,
						amount,
						reference_no,
						description,
						biller_id,
						created_by,
						updated_by
					) SELECT
						'SALES',
						v_tran_no,
						NEW.date,
						erp_gl_sections.sectionid,
						erp_gl_charts.accountcode,
						erp_gl_charts.accountname,
						(- 1) * abs(NEW.order_tax),
						NEW.reference_no,
						NEW.note,
						NEW.biller_id,
						NEW.created_by,
						NEW.updated_by
					FROM
						erp_account_settings
					INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_account_settings.default_sale_tax
					INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
					WHERE
						erp_gl_charts.accountcode = erp_account_settings.default_sale_tax;


				END IF;

			END IF;
	
		END IF;
		IF NEW.total > 0 AND NEW.updated_by > 0 AND NEW.updated_count <> OLD.updated_count THEN
			IF NEW.grand_total > NEW.paid THEN
				INSERT INTO erp_gl_trans (
					tran_type,
					tran_no,
					tran_date,
					sectionid,
					account_code,
					narrative,
					amount,
					reference_no,
					description,
					biller_id,
					created_by,
					updated_by
				) SELECT
					'SALES',
					v_tran_no,
					NEW.date,
					erp_gl_sections.sectionid,
					erp_gl_charts.accountcode,
					erp_gl_charts.accountname,
					(NEW.grand_total - NEW.paid),
					NEW.reference_no,
					NEW.customer,
					NEW.biller_id,
					NEW.created_by,
					NEW.updated_by
				FROM
					erp_account_settings
				INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_account_settings.default_receivable
				INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
				WHERE
					erp_gl_charts.accountcode = erp_account_settings.default_receivable;
			END IF;
		END IF;

	END IF;

	IF NEW.opening_ar = 2 THEN
		SET v_tran_date = (
			SELECT
				erp_sales.date
			FROM
				erp_payments
			INNER JOIN erp_sales ON erp_sales.id = erp_payments.sale_id
			WHERE
				erp_sales.id = NEW.id
			LIMIT 0,
			1
		);
		
		IF v_tran_date = NEW.date THEN
			SET v_tran_no = (
				SELECT
					MAX(tran_no)
				FROM
					erp_gl_trans
			);
		ELSE
			SET v_tran_no = (
				SELECT
					COALESCE (MAX(tran_no), 0) + 1
				FROM
					erp_gl_trans
			);
			
			UPDATE erp_order_ref
			SET tr = v_tran_no
			WHERE
				DATE_FORMAT(date, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m');


		END IF;
		
		INSERT INTO erp_gl_trans (
			tran_type,
			tran_no,
			tran_date,
			sectionid,
			account_code,
			narrative,
			amount,
			reference_no,
			description,
			biller_id,
			created_by,
			updated_by
		) SELECT
			'SALES',
			v_tran_no,
			NEW.date,
			erp_gl_sections.sectionid,
			erp_gl_charts.accountcode,
			erp_gl_charts.accountname,
			(- 1) * abs(
				NEW.total - NEW.product_tax
			),
			NEW.reference_no,
			NEW.note,
			NEW.biller_id,
			NEW.created_by,
			NEW.updated_by
		FROM
			erp_account_settings
		INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_account_settings.default_open_balance
		INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
		WHERE
			erp_gl_charts.accountcode = erp_account_settings.default_open_balance;
			
        IF NEW.paid <= 0 THEN
			INSERT INTO erp_gl_trans (
				tran_type,
				tran_no,
				tran_date,
				sectionid,
				account_code,
				narrative,
				amount,
				reference_no,
				description,
				biller_id,
				created_by,
				updated_by
				   ) SELECT
						'SALES',
						v_tran_no,
						NEW.date,
						erp_gl_sections.sectionid,
						erp_gl_charts.accountcode,
						erp_gl_charts.accountname,
						NEW.grand_total,
						NEW.reference_no,
						NEW.note,
						NEW.biller_id,
						NEW.created_by,
						NEW.updated_by
					FROM
					erp_account_settings
					   INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_account_settings.default_receivable
					   INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
					   WHERE
					erp_gl_charts.accountcode = erp_account_settings.default_receivable;
		END IF;
		
	END IF;

END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `gl_trans_sales_delete`;
DELIMITER ;;
CREATE TRIGGER `gl_trans_sales_delete` AFTER DELETE ON `erp_sales` FOR EACH ROW BEGIN

   UPDATE erp_gl_trans SET amount = 0, description = CONCAT(description,' (Cancelled)')
   WHERE reference_no = OLD.reference_no;
END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `audit_sales_update`;
DELIMITER ;;
CREATE TRIGGER `audit_sales_update` BEFORE UPDATE ON `erp_sales_audit` FOR EACH ROW BEGIN
	IF OLD.id THEN
		INSERT INTO erp_sales_audit (
			id,
			date,
			reference_no,
			customer_id,
			customer,
			group_areas_id,
			biller_id,
			biller,
			warehouse_id,
			note,
			staff_note,
			total,
			product_discount,
			order_discount_id,
			total_discount,
			order_discount,
			product_tax,
			order_tax_id,
			order_tax,
			total_tax,
			shipping,
			grand_total,
			sale_status,
			payment_status,
			payment_term,
			due_date,
			delivery_status,
			delivery_by,
			created_by,
			updated_by,
			updated_at,
			updated_count,
			total_items,
			total_cost,
			pos,
			paid,
			return_id,
			surcharge,
			attachment,
			attachment1,
			attachment2,
			type,
			type_id,
			other_cur_paid,
			other_cur_paid_rate,
			other_cur_paid1,
			other_cur_paid_rate1,
			saleman_by,
			reference_no_tax,
			tax_status,
			opening_ar,
			sale_type,
			bill_to,
			po,
			suspend_note,
			tax_type,
			so_id,
			revenues_type,
			acc_cate_separate,
			hide_tax,
			audit_type
		) SELECT 
			id,
			date,
			reference_no,
			customer_id,
			customer,
			group_areas_id,
			biller_id,
			biller,
			warehouse_id,
			note,
			staff_note,
			total,
			product_discount,
			order_discount_id,
			total_discount,
			order_discount,
			product_tax,
			order_tax_id,
			order_tax,
			total_tax,
			shipping,
			grand_total,
			sale_status,
			payment_status,
			payment_term,
			due_date,
			delivery_status,
			delivery_by,
			created_by,
			updated_by,
			updated_at,
			updated_count,
			total_items,
			total_cost,
			pos,
			paid,
			return_id,
			surcharge,
			attachment,
			attachment1,
			attachment2,
			type,
			type_id,
			other_cur_paid,
			other_cur_paid_rate,
			other_cur_paid1,
			other_cur_paid_rate1,
			saleman_by,
			reference_no_tax,
			tax_status,
			opening_ar,
			sale_type,
			bill_to,
			po,
			suspend_note,
			tax_type,
			so_id,
			revenues_type,
			acc_cate_separate,
			hide_tax,
			"UPDATED"
		FROM
			erp_sales
		WHERE
			erp_sales.id = OLD.id;
	END IF;

END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `audit_sales_delete`;
DELIMITER ;;
CREATE TRIGGER `audit_sales_delete` BEFORE DELETE ON `erp_sales_audit` FOR EACH ROW BEGIN
	IF OLD.id THEN
		INSERT INTO erp_sales_audit (
			id,
			date,
			reference_no,
			customer_id,
			customer,
			group_areas_id,
			biller_id,
			biller,
			warehouse_id,
			note,
			staff_note,
			total,
			product_discount,
			order_discount_id,
			total_discount,
			order_discount,
			product_tax,
			order_tax_id,
			order_tax,
			total_tax,
			shipping,
			grand_total,
			sale_status,
			payment_status,
			payment_term,
			due_date,
			delivery_status,
			delivery_by,
			created_by,
			updated_by,
			updated_at,
			updated_count,
			total_items,
			total_cost,
			pos,
			paid,
			return_id,
			surcharge,
			attachment,
			attachment1,
			attachment2,
			type,
			type_id,
			other_cur_paid,
			other_cur_paid_rate,
			other_cur_paid1,
			other_cur_paid_rate1,
			saleman_by,
			reference_no_tax,
			tax_status,
			opening_ar,
			sale_type,
			bill_to,
			po,
			suspend_note,
			tax_type,
			so_id,
			revenues_type,
			acc_cate_separate,
			hide_tax,
			audit_type
		) SELECT 
			id,
			date,
			reference_no,
			customer_id,
			customer,
			group_areas_id,
			biller_id,
			biller,
			warehouse_id,
			note,
			staff_note,
			total,
			product_discount,
			order_discount_id,
			total_discount,
			order_discount,
			product_tax,
			order_tax_id,
			order_tax,
			total_tax,
			shipping,
			grand_total,
			sale_status,
			payment_status,
			payment_term,
			due_date,
			delivery_status,
			delivery_by,
			created_by,
			updated_by,
			updated_at,
			updated_count,
			total_items,
			total_cost,
			pos,
			paid,
			return_id,
			surcharge,
			attachment,
			attachment1,
			attachment2,
			type,
			type_id,
			other_cur_paid,
			other_cur_paid_rate,
			other_cur_paid1,
			other_cur_paid_rate1,
			saleman_by,
			reference_no_tax,
			tax_status,
			opening_ar,
			sale_type,
			bill_to,
			po,
			suspend_note,
			tax_type,
			so_id,
			revenues_type,
			acc_cate_separate,
			hide_tax,
			"DELETED"
		FROM
			erp_sales
		WHERE
			erp_sales.id = OLD.id;
	END IF;

END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `gl_trans_sale_items_insert`;
DELIMITER ;;
CREATE TRIGGER `gl_trans_sale_items_insert` AFTER INSERT ON `erp_sale_items` FOR EACH ROW BEGIN

	DECLARE v_tran_no INTEGER;
	DECLARE v_tran_sale_type INTEGER;
	DECLARE v_tran_date DATETIME;
	DECLARE v_tran_reference_no VARCHAR(55);
	DECLARE v_tran_customer VARCHAR(55);
	DECLARE v_tran_biller_id INTEGER;
	DECLARE v_tran_created_by INTEGER;
	DECLARE v_tran_updated_by INTEGER;
	DECLARE v_tax_type INTEGER;
	DECLARE v_order_tax_id INTEGER;
	DECLARE v_sale_type INTEGER;
	DECLARE v_acc_cate_separate INTEGER;
	DECLARE v_tran_note VARCHAR(255);
	DECLARE v_sale_status VARCHAR(50);
	DECLARE v_category_id INTEGER;
	DECLARE v_qoh INTEGER;
	DECLARE v_updated_at DATETIME;
	DECLARE v_product_type VARCHAR(50);
	DECLARE v_unit_quantity INTEGER;
	DECLARE v_paid DECIMAL(5,2);
	DECLARE v_grand_total DECIMAL(5,2);
	
	SET v_acc_cate_separate = (SELECT erp_settings.acc_cate_separate FROM erp_settings WHERE erp_settings.setting_id = '1');

	SET v_tran_date = (SELECT erp_sales.date 
			FROM erp_sales
			WHERE erp_sales.id = NEW.sale_id LIMIT 0,1);

                  SET v_paid = (SELECT erp_sales.paid FROM erp_sales WHERE erp_sales.id = NEW.sale_id LIMIT 0,1);
                  SET v_grand_total = (SELECT erp_sales.grand_total FROM erp_sales WHERE erp_sales.id = NEW.sale_id LIMIT 0,1);

                  IF v_grand_total > v_paid  THEN		
		SET v_tran_no = (SELECT MAX(tran_no) FROM erp_gl_trans);
	ELSE
		SET v_tran_no = (SELECT COALESCE (MAX(tran_no), 0) + 1 FROM erp_gl_trans);
	END IF;
			
	/*SET v_tran_no = (SELECT MAX(tran_no) FROM erp_gl_trans);*/

	SET v_tran_sale_type = (SELECT erp_sales.sale_type FROM erp_sales WHERE erp_sales.id = NEW.sale_id);
	SET v_tran_reference_no = (SELECT erp_sales.reference_no FROM erp_sales WHERE erp_sales.id = NEW.sale_id);
	SET v_tran_customer = (SELECT erp_sales.customer FROM erp_sales WHERE erp_sales.id = NEW.sale_id);
	SET v_tran_biller_id = (SELECT erp_sales.biller_id FROM erp_sales WHERE erp_sales.id = NEW.sale_id);
	SET v_tran_created_by = (SELECT erp_sales.created_by FROM erp_sales WHERE erp_sales.id = NEW.sale_id);
	SET v_tran_updated_by = (SELECT erp_sales.updated_by FROM erp_sales WHERE erp_sales.id = NEW.sale_id);
	SET v_tran_note = (SELECT erp_sales.note FROM erp_sales WHERE erp_sales.id = NEW.sale_id);
	SET v_sale_status = (SELECT erp_sales.sale_status FROM erp_sales WHERE erp_sales.id = NEW.sale_id);
	SET v_category_id = (SELECT erp_products.category_id FROM erp_products WHERE erp_products.id = NEW.product_id);
	SET v_qoh =  (SELECT erp_products.quantity FROM erp_products WHERE erp_products.id = NEW.product_id);
	SET v_product_type =  (SELECT erp_products.type FROM erp_products WHERE erp_products.id = NEW.product_id);
	SET v_updated_at = (SELECT erp_sales.updated_at FROM erp_sales WHERE erp_sales.id = NEW.sale_id);
	
	IF NEW.product_id AND NEW.option_id THEN
		SET v_unit_quantity = (SELECT qty_unit FROM erp_product_variants WHERE product_id = NEW.product_id AND id = NEW.option_id);
	ELSE
		SET v_unit_quantity = 1;
	END IF;
	
	IF NEW.item_tax > 0 THEN
		
		INSERT INTO erp_gl_trans (
				tran_type,
				tran_no,
				tran_date,
				sectionid,
				account_code,
				narrative,
				amount,
				reference_no,
				description,
				biller_id,
				created_by,
				updated_by
			) SELECT
				'SALES',
				v_tran_no,
				v_tran_date,
				erp_gl_sections.sectionid,
				erp_gl_charts.accountcode,
				erp_gl_charts.accountname,
				(- 1) * NEW.item_tax,
				v_tran_reference_no,
				v_tran_note,
				v_tran_biller_id,
				v_tran_created_by,
				v_tran_updated_by
				FROM
					erp_account_settings
				INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_account_settings.default_sale_tax
				INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
				WHERE
					erp_gl_charts.accountcode = erp_account_settings.default_sale_tax;
					
	END IF;
	
	IF v_acc_cate_separate = 1 THEN

		INSERT INTO erp_gl_trans (
			tran_type,
			tran_no,
			tran_date,
			sectionid,
			account_code,
			narrative,
			amount,
			reference_no,
			description,
			biller_id,
			created_by,
			updated_by
		) SELECT
			'SALES',
			v_tran_no,
			v_tran_date,
			erp_gl_sections.sectionid,
			erp_gl_charts.accountcode,
			erp_gl_charts.accountname,
			(-1) * abs(NEW.subtotal - NEW.item_tax),
			v_tran_reference_no,
			v_tran_note,
			v_tran_biller_id,
			v_tran_created_by,
			v_tran_updated_by
			FROM
					erp_categories
					INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_categories.ac_sale
					INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
				WHERE
					erp_categories.id = v_category_id;
		
		IF v_sale_status = "completed" AND (NEW.unit_cost * NEW.quantity) > 0 AND v_product_type <> "service" THEN
		
			INSERT INTO erp_gl_trans (
				tran_type,
				tran_no,
				tran_date,
				sectionid,
				account_code,
				narrative,
				amount,
				reference_no,
				description,
				biller_id,
				created_by,
				updated_by
				) SELECT
				'SALES',
				v_tran_no,
				v_tran_date,
				erp_gl_sections.sectionid,
				erp_gl_charts.accountcode,
				erp_gl_charts.accountname,
				(NEW.unit_cost * (NEW.quantity * v_unit_quantity)),
				v_tran_reference_no,
				v_tran_note,
				v_tran_biller_id,
				v_tran_created_by,
				v_tran_updated_by
				FROM
					erp_categories
					INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_categories.ac_cost
					INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
				WHERE
					erp_categories.id = v_category_id;

			INSERT INTO erp_gl_trans (
				tran_type,
				tran_no,
				tran_date,
				sectionid,
				account_code,
				narrative,
				amount,
				reference_no,
				description,
				biller_id,
				created_by,
				updated_by
				) SELECT
				'SALES',
				v_tran_no,
				v_tran_date,
				erp_gl_sections.sectionid,
				erp_gl_charts.accountcode,
				erp_gl_charts.accountname,
				(-1) * abs(NEW.unit_cost * (NEW.quantity * v_unit_quantity)),
				v_tran_reference_no,
				v_tran_note,
				v_tran_biller_id,
				v_tran_created_by,
				v_tran_updated_by
				FROM
					erp_categories
					INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_categories.ac_stock
					INNER JOIN erp_gl_sections ON erp_gl_sections.sectionid = erp_gl_charts.sectionid
				WHERE
					erp_categories.id = v_category_id;
		END IF;
	END IF;
END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `audit_sales_items_update`;
DELIMITER ;;
CREATE TRIGGER `audit_sales_items_update` BEFORE UPDATE ON `erp_sale_items` FOR EACH ROW BEGIN
	IF OLD.id THEN
		INSERT INTO erp_sale_items_audit (
			id,
			sale_id,
			category_id,
			product_id,
			product_code,
			product_name,
			product_type,
			option_id,
			net_unit_price,
			unit_price,
			quantity_received,
			quantity,
			warehouse_id,
			item_tax,
			tax_rate_id,
			tax,
			discount,
			item_discount,
			subtotal,
			serial_no,
			real_unit_price,
			product_noted,
			returned,
			group_price_id,
			acc_cate_separate,
			specific_tax_on_certain_merchandise_and_services,
			accommodation_tax,
			public_lighting_tax,
			other_tax,
			payment_of_profit_tax,
			performance_royalty_intangible,
			payment_interest_non_bank,
			payment_interest_taxpayer_fixed,
			payment_interest_taxpayer_non_fixed,
			payment_rental_immovable_property,
			payment_of_interest,
			payment_royalty_rental_income_related,
			payment_management_technical,
			payment_dividend,
			withholding_tax_on_resident,
			withholding_tax_on_non_resident,
			audit_type
		) SELECT 
			id,
			sale_id,
			category_id,
			product_id,
			product_code,
			product_name,
			product_type,
			option_id,
			net_unit_price,
			unit_price,
			quantity_received,
			quantity,
			warehouse_id,
			item_tax,
			tax_rate_id,
			tax,
			discount,
			item_discount,
			subtotal,
			serial_no,
			real_unit_price,
			product_noted,
			returned,
			group_price_id,
			acc_cate_separate,
			specific_tax_on_certain_merchandise_and_services,
			accommodation_tax,
			public_lighting_tax,
			other_tax,
			payment_of_profit_tax,
			performance_royalty_intangible,
			payment_interest_non_bank,
			payment_interest_taxpayer_fixed,
			payment_interest_taxpayer_non_fixed,
			payment_rental_immovable_property,
			payment_of_interest,
			payment_royalty_rental_income_related,
			payment_management_technical,
			payment_dividend,
			withholding_tax_on_resident,
			withholding_tax_on_non_resident,
			"UPDATED"
		FROM
			erp_sale_items
		WHERE
			erp_sale_items.id = OLD.id;
	END IF;

END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `audit_sales_items_delete`;
DELIMITER ;;
CREATE TRIGGER `audit_sales_items_delete` BEFORE DELETE ON `erp_sale_items` FOR EACH ROW BEGIN

END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `audit_sales_orders_update`;
DELIMITER ;;
CREATE TRIGGER `audit_sales_orders_update` BEFORE UPDATE ON `erp_sale_order` FOR EACH ROW BEGIN
	IF OLD.id THEN
		INSERT INTO erp_sale_order_audit (
			id,
			date,
			quote_id,
			reference_no,
			customer_id,
			customer,
			group_areas_id,
			biller_id,
			biller,
			warehouse_id,
			note,
			staff_note,
			total,
			product_discount,
			order_discount_id,
			total_discount,
			order_discount,
			product_tax,
			order_tax_id,
			order_tax,
			total_tax,
			shipping,
			grand_total,
			order_status,
			sale_status,
			payment_status,
			payment_term,
			due_date,
			created_by,
			updated_by,
			updated_at,
			total_items,
			total_cost,
			pos,
			paid,
			return_id,
			surcharge,
			attachment,
			attachment1,
			attachment2,
			suspend_note,
			other_cur_paid,
			other_cur_paid_rate,
			other_cur_paid1,
			other_cur_paid_rate1,
			saleman_by,
			reference_no_tax,
			tax_status,
			opening_ar,
			delivery_by,
			sale_type,
			delivery_status,
			tax_type,
			bill_to,
			po,
			audit_type
		) SELECT 
			id,
			date,
			quote_id,
			reference_no,
			customer_id,
			customer,
			group_areas_id,
			biller_id,
			biller,
			warehouse_id,
			note,
			staff_note,
			total,
			product_discount,
			order_discount_id,
			total_discount,
			order_discount,
			product_tax,
			order_tax_id,
			order_tax,
			total_tax,
			shipping,
			grand_total,
			order_status,
			sale_status,
			payment_status,
			payment_term,
			due_date,
			created_by,
			updated_by,
			updated_at,
			total_items,
			total_cost,
			pos,
			paid,
			return_id,
			surcharge,
			attachment,
			attachment1,
			attachment2,
			suspend_note,
			other_cur_paid,
			other_cur_paid_rate,
			other_cur_paid1,
			other_cur_paid_rate1,
			saleman_by,
			reference_no_tax,
			tax_status,
			opening_ar,
			delivery_by,
			sale_type,
			delivery_status,
			tax_type,
			bill_to,
			po,
			"UPDATED"
		FROM
			erp_sale_order
		WHERE
			erp_sale_order.id = OLD.id;
	END IF;

END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `audit_sales_orders_delete`;
DELIMITER ;;
CREATE TRIGGER `audit_sales_orders_delete` BEFORE DELETE ON `erp_sale_order` FOR EACH ROW BEGIN
	IF OLD.id THEN
		INSERT INTO erp_sale_order_audit (
			id,
			date,
			quote_id,
			reference_no,
			customer_id,
			customer,
			group_areas_id,
			biller_id,
			biller,
			warehouse_id,
			note,
			staff_note,
			total,
			product_discount,
			order_discount_id,
			total_discount,
			order_discount,
			product_tax,
			order_tax_id,
			order_tax,
			total_tax,
			shipping,
			grand_total,
			order_status,
			sale_status,
			payment_status,
			payment_term,
			due_date,
			created_by,
			updated_by,
			updated_at,
			total_items,
			total_cost,
			pos,
			paid,
			return_id,
			surcharge,
			attachment,
			attachment1,
			attachment2,
			suspend_note,
			other_cur_paid,
			other_cur_paid_rate,
			other_cur_paid1,
			other_cur_paid_rate1,
			saleman_by,
			reference_no_tax,
			tax_status,
			opening_ar,
			delivery_by,
			sale_type,
			delivery_status,
			tax_type,
			bill_to,
			po,
			audit_type
		) SELECT 
			id,
			date,
			quote_id,
			reference_no,
			customer_id,
			customer,
			group_areas_id,
			biller_id,
			biller,
			warehouse_id,
			note,
			staff_note,
			total,
			product_discount,
			order_discount_id,
			total_discount,
			order_discount,
			product_tax,
			order_tax_id,
			order_tax,
			total_tax,
			shipping,
			grand_total,
			order_status,
			sale_status,
			payment_status,
			payment_term,
			due_date,
			created_by,
			updated_by,
			updated_at,
			total_items,
			total_cost,
			pos,
			paid,
			return_id,
			surcharge,
			attachment,
			attachment1,
			attachment2,
			suspend_note,
			other_cur_paid,
			other_cur_paid_rate,
			other_cur_paid1,
			other_cur_paid_rate1,
			saleman_by,
			reference_no_tax,
			tax_status,
			opening_ar,
			delivery_by,
			sale_type,
			delivery_status,
			tax_type,
			bill_to,
			po,
			"DELETED"
		FROM
			erp_sale_order
		WHERE
			erp_sale_order.id = OLD.id;
	END IF;

END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `audit_sales_order_items_update`;
DELIMITER ;;
CREATE TRIGGER `audit_sales_order_items_update` BEFORE UPDATE ON `erp_sale_order_items` FOR EACH ROW BEGIN
	IF OLD.id THEN
		INSERT INTO erp_sale_order_items_audit (
			id,
			sale_order_id,
			product_id,
			product_code,
			product_name,
			product_type,
			option_id,
			net_unit_price,
			unit_price,
			quantity_received,
			quantity,
			warehouse_id,
			item_tax,
			tax_rate_id,
			tax,
			discount,
			item_discount,
			subtotal,
			serial_no,
			real_unit_price,
			product_noted,
			group_price_id
		) SELECT 
			id,
			sale_order_id,
			product_id,
			product_code,
			product_name,
			product_type,
			option_id,
			net_unit_price,
			unit_price,
			quantity_received,
			quantity,
			warehouse_id,
			item_tax,
			tax_rate_id,
			tax,
			discount,
			item_discount,
			subtotal,
			serial_no,
			real_unit_price,
			product_noted,
			group_price_id
		FROM
			erp_sale_order_items
		WHERE
			erp_sale_order_items.id = OLD.id;
	END IF;

END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `audit_sales_order_items_delete`;
DELIMITER ;;
CREATE TRIGGER `audit_sales_order_items_delete` BEFORE DELETE ON `erp_sale_order_items` FOR EACH ROW BEGIN
	IF OLD.id THEN
		INSERT INTO erp_sale_order_items_audit (
			id,
			sale_order_id,
			product_id,
			product_code,
			product_name,
			product_type,
			option_id,
			net_unit_price,
			unit_price,
			quantity_received,
			quantity,
			warehouse_id,
			item_tax,
			tax_rate_id,
			tax,
			discount,
			item_discount,
			subtotal,
			serial_no,
			real_unit_price,
			product_noted,
			group_price_id
		) SELECT 
			id,
			sale_order_id,
			product_id,
			product_code,
			product_name,
			product_type,
			option_id,
			net_unit_price,
			unit_price,
			quantity_received,
			quantity,
			warehouse_id,
			item_tax,
			tax_rate_id,
			tax,
			discount,
			item_discount,
			subtotal,
			serial_no,
			real_unit_price,
			product_noted,
			group_price_id
		FROM
			erp_sale_order_items
		WHERE
			erp_sale_order_items.id = OLD.id;
	END IF;

END
;;
DELIMITER ;
