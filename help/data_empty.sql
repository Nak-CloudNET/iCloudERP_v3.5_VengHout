-- phpMyAdmin SQL Dump
-- version 4.4.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: March 10, 2018 at 9:42 AM
-- Server version: 5.6.26
-- PHP Version: 5.5.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `iclouderp_v3_4`
--
TRUNCATE `erp_gl_trans_audit`;
TRUNCATE `erp_gl_trans`;
TRUNCATE `erp_gl_charts`;
TRUNCATE `erp_stock_trans`;
TRUNCATE `erp_order_ref`;
TRUNCATE `erp_inventory_valuation_details`;
TRUNCATE `erp_purchase_items`;
TRUNCATE `erp_purchase_items_audit`;
TRUNCATE `erp_sale_items`;
TRUNCATE `erp_warehouses_products`;
TRUNCATE `erp_product_variants`;
TRUNCATE `erp_warehouses_products_variants`;
TRUNCATE `erp_sessions`;
TRUNCATE `erp_sale_items_audit`;
TRUNCATE `erp_products`;
TRUNCATE `erp_user_logins`;
TRUNCATE `erp_sales`;
TRUNCATE `erp_gl_sections`;
TRUNCATE `erp_users_bank_account`;
TRUNCATE `erp_companies`;
TRUNCATE `erp_adjustment_items`;
TRUNCATE `erp_date_format`;
TRUNCATE `erp_users`;
TRUNCATE `erp_groups`;
TRUNCATE `erp_adjustments`;
TRUNCATE `erp_variants`;
TRUNCATE `erp_convert_items`;
TRUNCATE `erp_units`;
TRUNCATE `erp_pos_register`;
TRUNCATE `erp_payments`;
TRUNCATE `erp_categories`;
TRUNCATE `erp_warehouses`;
TRUNCATE `erp_tax_rates`;
TRUNCATE `erp_purchases`;
TRUNCATE `erp_enter_using_stock`;
TRUNCATE `erp_bom_items`;
TRUNCATE `erp_permissions`;
TRUNCATE `erp_enter_using_stock_items`;
TRUNCATE `erp_customer_groups`;
TRUNCATE `erp_currencies`;
TRUNCATE `erp_convert`;
TRUNCATE `erp_transfers`;
TRUNCATE `erp_transfer_items`;
TRUNCATE `erp_login_attempts`;
TRUNCATE `erp_transfer_customers`;
TRUNCATE `erp_terms`;
TRUNCATE `erp_term_types`;
TRUNCATE `erp_taxation_type_of_product`;
TRUNCATE `erp_taxation_type_of_account`;
TRUNCATE `erp_tax_purchase_vat`;
TRUNCATE `erp_tax_exchange_rate`;
TRUNCATE `erp_suspended_items`;
TRUNCATE `erp_suspended_bills`;
TRUNCATE `erp_suspended`;
TRUNCATE `erp_suspend_layout`;
TRUNCATE `erp_subcategories`;
TRUNCATE `erp_stock_counts`;
TRUNCATE `erp_stock_count_items`;
TRUNCATE `erp_skrill`;
TRUNCATE `erp_settings`;
TRUNCATE `erp_serial`;
TRUNCATE `erp_salesman_clear_money`;
TRUNCATE `erp_salesman_assign`;
TRUNCATE `erp_sales_types`;
TRUNCATE `erp_sales_audit`;
TRUNCATE `erp_sale_tax`;
TRUNCATE `erp_sale_order_items_audit`;
TRUNCATE `erp_sale_order_items`;
TRUNCATE `erp_sale_order_audit`;
TRUNCATE `erp_sale_order`;
TRUNCATE `erp_sale_dev_items_audit`;
TRUNCATE `erp_sale_dev_items`;
TRUNCATE `erp_sale_areas`;
TRUNCATE `erp_salary_tax_front`;
TRUNCATE `erp_salary_tax_back`;
TRUNCATE `erp_salary_tax`;
TRUNCATE `erp_revenues`;
TRUNCATE `erp_return_withholding_tax_front`;
TRUNCATE `erp_return_withholding_tax_back`;
TRUNCATE `erp_return_withholding_tax`;
TRUNCATE `erp_return_value_added_tax_back`;
TRUNCATE `erp_return_value_added_tax`;
TRUNCATE `erp_return_tax_front`;
TRUNCATE `erp_return_tax_back`;
TRUNCATE `erp_return_sales`;
TRUNCATE `erp_return_purchases`;
TRUNCATE `erp_return_purchase_items`;
TRUNCATE `erp_return_items`;
TRUNCATE `erp_related_products`;
TRUNCATE `erp_recieved_transfers`;
TRUNCATE `erp_recieved_transfer_items`;
TRUNCATE `erp_quotes`;
TRUNCATE `erp_quote_items`;
TRUNCATE `erp_purchasing_taxes`;
TRUNCATE `erp_purchases_request`;
TRUNCATE `erp_purchases_order_audit`;
TRUNCATE `erp_purchases_order`;
TRUNCATE `erp_purchases_audit`;
TRUNCATE `erp_purchase_tax`;
TRUNCATE `erp_purchase_request_items`;
TRUNCATE `erp_purchase_order_items`;
TRUNCATE `erp_public_charge_detail`;
TRUNCATE `erp_promotions`;
TRUNCATE `erp_promotion_categories`;
TRUNCATE `erp_project_plan_items`;
TRUNCATE `erp_project_plan`;
TRUNCATE `erp_product_prices`;
TRUNCATE `erp_product_photos`;
TRUNCATE `erp_product_note`;
TRUNCATE `erp_principles`;
TRUNCATE `erp_price_groups`;
TRUNCATE `erp_position`;
TRUNCATE `erp_pos_settings`;
TRUNCATE `erp_plan_address`;
TRUNCATE `erp_paypal`;
TRUNCATE `erp_payments_audit`;
TRUNCATE `erp_payment_term`;
TRUNCATE `erp_pack_lists`;
TRUNCATE `erp_order_loans`;
TRUNCATE `erp_notifications`;
TRUNCATE `erp_migrations`;
TRUNCATE `erp_marchine_logs`;
TRUNCATE `erp_marchine`;
TRUNCATE `erp_loans`;
TRUNCATE `erp_group_areas`;
TRUNCATE `erp_gl_charts_tax`;
TRUNCATE `erp_gift_cards_audit`;
TRUNCATE `erp_gift_cards`;
TRUNCATE `erp_frequency`;
TRUNCATE `erp_expenses_audit`;
TRUNCATE `erp_expenses`;
TRUNCATE `erp_expense_categories`;
TRUNCATE `erp_employee_type`;
TRUNCATE `erp_employee_salary_tax_trigger`;
TRUNCATE `erp_employee_salary_tax_small_taxpayers_trigger`;
TRUNCATE `erp_employee_salary_tax_small_taxpayers`;
TRUNCATE `erp_employee_salary_tax`;
TRUNCATE `erp_documents`;
TRUNCATE `erp_document_photos`;
TRUNCATE `erp_digital_items`;
TRUNCATE `erp_deposits`;
TRUNCATE `erp_delivery_items`;
TRUNCATE `erp_deliveries`;
TRUNCATE `erp_define_public_charge`;
TRUNCATE `erp_customer_public_charge`;
TRUNCATE `erp_costing`;
TRUNCATE `erp_condition_tax`;
TRUNCATE `erp_combo_items`;
TRUNCATE `erp_combine_items`;
TRUNCATE `erp_categories_note`;
TRUNCATE `erp_categories_group`;
TRUNCATE `erp_cash_advances`;
TRUNCATE `erp_captcha`;
TRUNCATE `erp_calendar`;
TRUNCATE `erp_brands`;
TRUNCATE `erp_bom`;
TRUNCATE `erp_account_settings`;

--
-- Dumping data for table `erp_account_settings`
--

INSERT INTO `erp_account_settings` (`id`, `biller_id`, `default_open_balance`, `default_sale`, `default_sale_discount`, `default_sale_tax`, `default_sale_freight`, `default_sale_deposit`, `default_receivable`, `default_purchase`, `default_purchase_discount`, `default_purchase_tax`, `default_purchase_freight`, `default_purchase_deposit`, `default_payable`, `default_stock`, `default_stock_adjust`, `default_cost`, `default_payroll`, `default_cash`, `default_credit_card`, `default_gift_card`, `default_cheque`, `default_loan`, `default_retained_earnings`, `default_cost_variant`, `default_interest_income`, `default_transfer_owner`, `default_tax_expense`, `default_vat_payable`, `default_salary_tax_payable`, `default_tax_duties_expense`, `default_salary_expense`) VALUES ('1', '1', '300300', '410101', '410102', '201407', '601206', '201208', '100200', '100430', '500106', '100441', '601206', '100420', '201100', '100430', '500107', '500101', '201201', '100102', '100105', '201208', '100104', '410103', '300200', '500108', '710301', '101005', NULL, NULL, NULL, NULL, NULL);


INSERT INTO `erp_pos_settings` (`pos_id`, `cat_limit`, `pro_limit`, `default_category`, `default_customer`, `default_biller`, `display_time`, `cf_title1`, `cf_title2`, `cf_value1`, `cf_value2`, `receipt_printer`, `cash_drawer_codes`, `focus_add_item`, `add_manual_product`, `customer_selection`, `add_customer`, `toggle_category_slider`, `toggle_subcategory_slider`, `show_search_item`, `product_unit`, `cancel_sale`, `suspend_sale`, `print_items_list`, `print_bill`, `finalize_sale`, `today_sale`, `open_hold_bills`, `close_register`, `keyboard`, `pos_printers`, `java_applet`, `product_button_color`, `tooltips`, `paypal_pro`, `stripe`, `rounding`, `char_per_line`, `pin_code`, `purchase_code`, `envato_username`, `version`, `show_item_img`, `pos_layout`, `display_qrcode`, `show_suspend_bar`, `show_payment_noted`, `payment_balance`, `authorize`, `show_product_code`, `auto_delivery`, `in_out_rate`, `discount`, `count_cash`) VALUES ('1', '22', '20', '1', '3', '1', '1', 'GST Reg', 'VAT Reg', '123456789', '987654321', 'BIXOLON SRP-350II', 'x1C', 'Ctrl+F3', 'Ctrl+Shift+M', 'Ctrl+Shift+C', 'Ctrl+Shift+A', 'Ctrl+F11', 'Ctrl+F12', 'F1', 'F2', 'F4', 'F7', 'F9', 'F3', 'F8', 'Ctrl+F1', 'Ctrl+F2', 'Ctrl+F10', '0', 'BIXOLON SRP-350II, BIXOLON SRP-350II', '0', 'danger', '0', '0', '0', '0', '42', NULL, 'cloud-net', '53d35644-a36e-45cd-b7ee-8dde3a08f83d', '3.3', '1', '0', '1', '1', '1', '1', '0', '1', '0', '0', '0.0000', '0');


--
-- Dumping data for table `erp_companies`
--

INSERT INTO `erp_companies` (`code`, `group_id`, `group_name`, `customer_group_id`, `customer_group_name`, `price_group_id`, `name`, `company`, `company_kh`, `name_kh`, `vat_no`, `group_areas_id`, `address`, `address_1`, `address_2`, `address_3`, `address_4`, `address_5`, `address_kh`, `sale_man`, `city`, `state`, `postal_code`, `country`, `contact_person`, `phone`, `email`, `cf1`, `cf2`, `cf3`, `cf4`, `cf5`, `cf6`, `invoice_footer`, `payment_term`, `logo`, `award_points`, `deposit_amount`, `status`, `posta_card`, `gender`, `attachment`, `date_of_birth`, `start_date`, `end_date`, `credit_limited`, `business_activity`, `group`, `village`, `street`, `sangkat`, `district`, `period`, `amount`, `position`, `begining_balance`, `biller_prefix`, `wifi_code`, `identify_date`, `public_charge_id`) VALUES ('CN001', NULL, 'biller', NULL, NULL, NULL, 'Lay Kiry', 'CloudNET', '', NULL, '', NULL, 'Thailand , Bangkork ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', '06923225, 012 852 963, 016 963 852', '', '', '', '', '', '1', NULL, 'ទំនេញទិញហើយមិន​អាចដូរវិញបានទេ។\r\nទំនេញទិញហើយមិន​អាចដូរវិញបានទេ។\r\nទំនេញទិញហើយមិន​អាចដូរវិញបានទេ។\r\nទំនេញទិញហើយមិន​អាចដូរវិញបានទេ។\r\nទំនេញទិញហើយមិន​អាចដូរវិញបានទេ។', '0', 'logo.png', '0', NULL, NULL, NULL, NULL, NULL, NULL, '1970-01-01', '1970-01-01', NULL, '', '', '', '', '', '', '', '0.0000', NULL, '0.0000', 'CN', '', NULL, '0');
INSERT INTO `erp_companies` (`code`, `group_id`, `group_name`, `customer_group_id`, `customer_group_name`, `price_group_id`, `name`, `company`, `company_kh`, `name_kh`, `vat_no`, `group_areas_id`, `address`, `address_1`, `address_2`, `address_3`, `address_4`, `address_5`, `address_kh`, `sale_man`, `city`, `state`, `postal_code`, `country`, `contact_person`, `phone`, `email`, `cf1`, `cf2`, `cf3`, `cf4`, `cf5`, `cf6`, `invoice_footer`, `payment_term`, `logo`, `award_points`, `deposit_amount`, `status`, `posta_card`, `gender`, `attachment`, `date_of_birth`, `start_date`, `end_date`, `credit_limited`, `business_activity`, `group`, `village`, `street`, `sangkat`, `district`, `period`, `amount`, `position`, `begining_balance`, `biller_prefix`, `wifi_code`, `identify_date`, `public_charge_id`) VALUES ('CUS01', NULL, 'customer', NULL, NULL, NULL, 'Default', 'CloudNET', '', NULL, '', NULL, 'Thailand , Bangkork ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', '069 232 253, 012 852 963, 016 963 852', '', '', '', '', '', '1', NULL, NULL, '0', 'logo.png', '0', NULL, NULL, NULL, NULL, NULL, NULL, '1970-01-01', '1970-01-01', NULL, '', '', '', '', '', '', '', '0.0000', NULL, '0.0000', 'CN', '', NULL, '0');
INSERT INTO `erp_companies` (`code`, `group_id`, `group_name`, `customer_group_id`, `customer_group_name`, `price_group_id`, `name`, `company`, `company_kh`, `name_kh`, `vat_no`, `group_areas_id`, `address`, `address_1`, `address_2`, `address_3`, `address_4`, `address_5`, `address_kh`, `sale_man`, `city`, `state`, `postal_code`, `country`, `contact_person`, `phone`, `email`, `cf1`, `cf2`, `cf3`, `cf4`, `cf5`, `cf6`, `invoice_footer`, `payment_term`, `logo`, `award_points`, `deposit_amount`, `status`, `posta_card`, `gender`, `attachment`, `date_of_birth`, `start_date`, `end_date`, `credit_limited`, `business_activity`, `group`, `village`, `street`, `sangkat`, `district`, `period`, `amount`, `position`, `begining_balance`, `biller_prefix`, `wifi_code`, `identify_date`, `public_charge_id`) VALUES ('SUP01', NULL, 'supplier', NULL, NULL, NULL, 'Default', 'CloudNET', '', NULL, '', NULL, 'Thailand , Bangkork ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', '069 232 253, 012 852 963, 016 963 852', '', '', '', '', '', '1', NULL, NULL, '0', 'logo.png', '0', NULL, NULL, NULL, NULL, NULL, NULL, '1970-01-01', '1970-01-01', NULL, '', '', '', '', '', '', '', '0.0000', NULL, '0.0000', 'CN', '', NULL, '0');

--
-- Dumping data for table `erp_currencies`
--

INSERT INTO `erp_currencies` (`id`, `code`, `name`, `in_out`, `rate`, `auto_update`) VALUES ('1', 'USD', 'US Dollar', '1', '1.0000', '0');
INSERT INTO `erp_currencies` (`id`, `code`, `name`, `in_out`, `rate`, `auto_update`) VALUES ('2', 'KHM', 'Riel In', '1', '4100.0000', '0');


--
-- Dumping data for table `erp_customer_groups`
--
INSERT INTO `erp_customer_groups` (`id`, `name`, `percent`, `makeup_cost`) VALUES ('1', 'Default', '0', '0');

--
-- Dumping data for table `erp_date_format`
--

INSERT INTO `erp_date_format` (`id`, `js`, `php`, `sql`) VALUES ('1', 'mm-dd-yyyy', 'm-d-Y', '%m-%d-%Y');
INSERT INTO `erp_date_format` (`id`, `js`, `php`, `sql`) VALUES ('2', 'mm/dd/yyyy', 'm/d/Y', '%m/%d/%Y');
INSERT INTO `erp_date_format` (`id`, `js`, `php`, `sql`) VALUES ('3', 'mm.dd.yyyy', 'm.d.Y', '%m.%d.%Y');
INSERT INTO `erp_date_format` (`id`, `js`, `php`, `sql`) VALUES ('4', 'dd-mm-yyyy', 'd-m-Y', '%d-%m-%Y');
INSERT INTO `erp_date_format` (`id`, `js`, `php`, `sql`) VALUES ('5', 'dd/mm/yyyy', 'd/m/Y', '%d/%m/%Y');
INSERT INTO `erp_date_format` (`id`, `js`, `php`, `sql`) VALUES ('6', 'dd.mm.yyyy', 'd.m.Y', '%d.%m.%Y');
INSERT INTO `erp_date_format` (`id`, `js`, `php`, `sql`) VALUES ('7', 'yyyy-mm-dd', 'Y-m-d', '%Y-%m-%d');

--
-- Dumping data for table `erp_gl_charts`
--

INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('100100', 'Cash', '100100', '10', '0', '0', '', '1', '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('100101', 'Petty Cash', '100100', '10', '0', '0', '', '1', '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('100102', 'Cash on Hand', '100100', '10', '0', '0', '', '1', '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('100103', 'ANZ Bank', '100100', '10', '0', '0', '', '1', '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('100104', 'Wing Account', '100100', '10', '0', '0', '', '1', '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('100105', 'Visa', '100100', '10', '0', '0', '', '1', '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('100106', 'Chequing Bank Account', '100100', '10', '0', '0', '', '1', '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('100199', 'Offset Account', '100100', '10', '0', '0', '', '1', '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('100200', 'Account Receivable', '0', '10', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('100201', 'Advance from Employee', '100200', '10', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('100400', 'Other Current Assets', '0', '10', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('100410', 'Prepaid Expense', '100400', '10', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('100420', 'Supplier Deposit', '100400', '10', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('100430', 'Inventory', '100400', '10', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('100431', 'In-Inventory', '100430', '10', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('100440', 'Deferred Tax Asset', '100400', '10', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('100441', 'VAT Input', '100440', '10', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('100442', 'VAT Credit Carried Forward', '100440', '10', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('100500', 'Cash Advance', '100400', '10', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('100501', 'Loan to Related Parties', '100400', '10', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('100502', 'Staff Advance Cash', '100400', '10', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('101005', 'Own Invest', '0', '80', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('110200', 'Property, Plant and Equipment', '0', '11', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('110201', 'Furniture', '110200', '11', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('110202', 'Office Equipment', '110200', '11', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('110203', 'Machineries', '110200', '11', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('110204', 'Leasehold Improvement', '110200', '11', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('110205', 'IT Equipment & Computer', '110200', '11', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('110206', 'Vehicle', '110200', '11', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('110207', 'Building', '110200', '11', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('110250', 'Less Total Accumulated Depreciation', '110200', '11', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('110251', 'Less Acc. Dep. of Furniture', '110250', '11', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('110252', 'Less Acc. Dep. of Office Equipment', '110250', '11', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('110253', 'Less Acc. Dep. of Machineries', '110250', '11', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('110254', 'Less Acc. Dep. of Leasehold Improvement', '110250', '11', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('110255', 'Less Acc. Dep. of IT Equipment & Computer', '110250', '11', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('110256', 'Less Acc. Dep of Vehicle', '110250', '11', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('110257', 'Acc. Depre. Expense of Building ', '110207', '11', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('201100', 'Accounts Payable', '0', '20', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('201200', 'Other Current Liabilities', '0', '20', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('201201', 'Salary Payable', '201200', '20', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('201202', 'OT Payable', '201200', '20', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('201203', 'Allowance Payable', '201200', '20', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('201204', 'Bonus Payable', '201200', '20', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('201205', 'Commission Payable', '201200', '20', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('201206', 'Interest Payable', '201200', '20', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('201207', 'Loan from Related Parties', '201200', '20', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('201208', 'Customer Deposit', '201200', '20', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('201209', 'Accrued Expense', '201200', '20', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('201400', 'Deferred Tax Liabilities', '0', '20', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('201401', 'Salary Tax Payable', '201400', '20', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('201402', 'Withholding Tax Payable', '201400', '20', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('201403', 'VAT Payable', '201400', '20', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('201404', 'Profit Tax Payable', '201400', '20', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('201405', 'Prepayment Profit Tax Payable', '201400', '20', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('201406', 'Fringe Benefit Tax Payable', '201400', '20', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('201407', 'VAT Output', '201400', '20', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('300000', 'Capital Stock', '0', '30', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('300100', 'Paid-in Capital', '300000', '30', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('300101', 'Additional Paid-in Capital', '300000', '30', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('300200', 'Retained Earnings', '0', '30', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('300300', 'Opening Balance', '0', '30', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('400000', 'Sale Revenue', '0', '40', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('400001', 'Utilities', '0', '60', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('410101', 'Income', '400000', '40', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('410102', 'Sale Discount', '400000', '40', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('410103', 'Other Income', '400000', '40', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('410104', 'Income Restaurant', '400000', '40', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('410105', 'Income Phone', '400000', '40', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('500000', 'Cost of Goods Sold', '0', '50', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('500101', 'COMS', '500000', '50', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('500102', 'Freight Expense', '500000', '60', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('500103', 'Wages & Salaries', '500000', '60', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('500104', 'Cost Restaurant', '500000', '50', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('500105', 'Cost Phones', '500000', '50', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('500106', 'Purchase Discount', '500000', '50', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('500107', 'Inventory Adjustment', '500000', '50', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('500108', 'Cost of Variance', '500000', '50', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('500109', 'Using Stock', '500101', '50', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('600000', 'Expenses', '0', '60', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('601100', 'Staff Cost', '600000', '60', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('601101', 'Salary Expense', '601100', '60', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('601102', 'OT', '601100', '60', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('601103', 'Allowance ', '601100', '60', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('601104', 'Bonus', '601100', '60', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('601105', 'Commission', '601100', '60', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('601106', 'Training/Education', '601100', '60', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('601107', 'Compensation', '601100', '60', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('601108', 'Other Staff Relation', '601100', '60', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('601200', 'Administration Cost', '600000', '60', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('601201', 'Rental Expense', '601200', '60', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('601202', 'Utilities', '601200', '60', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('601203', 'Marketing & Advertising', '601200', '60', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('601204', 'Repair & Maintenance', '601200', '60', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('601205', 'Customer Relation', '601200', '60', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('601206', 'Transportation', '601200', '60', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('601207', 'Communication', '601200', '60', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('601208', 'Insurance Expense', '601200', '60', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('601209', 'Professional Fee', '601200', '60', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('601210', 'Depreciation Expense', '601200', '60', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('601211', 'Amortization Expense', '601200', '60', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('601212', 'Stationery', '601200', '60', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('601213', 'Office Supplies', '601200', '60', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('601214', 'Donation', '601200', '60', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('601215', 'Entertainment Expense', '601200', '60', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('601216', 'Travelling & Accomodation', '601200', '60', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('601217', 'Service Computer Expenses', '601200', '60', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('601218', 'Interest Expense', '601200', '60', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('601219', 'Bank Charge', '601200', '60', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('601220', 'Miscellaneous Expense', '601200', '60', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('601221', 'Canteen Supplies', '601200', '60', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('601222', 'Registration Expenses', '601200', '60', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('710300', 'Other Income', '0', '70', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('710301', 'Interest Income', '710300', '70', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('710302', 'Other Revenue & Gain', '710300', '70', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('801300', 'Other Expenses', '0', '80', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('801301', 'Other Expense & Loss', '801300', '80', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('801302', 'Bad Dept Expense', '801300', '80', '0', '0', '', NULL, '0.00');
INSERT INTO `erp_gl_charts` (`accountcode`, `accountname`, `parent_acc`, `sectionid`, `account_tax_id`, `acc_level`, `lineage`, `bank`, `value`) VALUES ('801303', 'Tax & Duties Expense', '801300', '80', '0', '0', '', NULL, '0.00');


--
-- Dumping data for table `erp_gl_charts_tax`
--

INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(1, 'B1', 'Sales of manufactured products', 'ការលក់ផលិតផល', 30);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(2, 'B2', 'Sales of goods', 'ការលក់ទំនិញ', 30);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(3, 'B3', 'Sales/Supply of services', 'ការផ្គត់ផ្គង់សេវា', 30);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(4, 'A2', 'Freehold Land', 'ដីធ្លីរបស់សហគ្រាស', 10);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(5, 'A3', 'Improvements and preparation of land', 'ការរៀបចំតុបតែងលំអរដីរបស់សហគ្រាស', 10);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(6, 'A4', 'Freehold buildings', 'សំណង់ងគាររបស់សហគ្រាស', 10);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(7, 'B4', 'Costs of pruducts sold of production enterprises(TOP 01/V)', 'ថៃ្លដើមផលិតផលបានលក់របស់សហគ្រាសផលិតកម្ម​(TOP 01/V)', 30);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(8, 'A5', 'Freehold buildings on leasehold land', 'សំណង់អាគារលើដីធ្លីក្រោមភតិសន្យា', 10);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(9, 'B5', 'Costs of goods sold of​​​​ non- production enterprises (TOP 01/VI)', 'ថៃ្លដើម ទំនិញបានលក់របស់សហគ្រាសក្រៅពីផលិតកម្ម(TOP 0', 30);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(10, 'A6', 'Non-current assets in progress', 'ទ្រព្យសកម្មរយះពេលវែងកំពុងដំណើរការ', 10);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(11, 'A7', 'Plant and equipemt', 'រោងចក្រ​​​(ក្រៅពីអគារ)និងបរិក្ខារ', 10);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(12, 'B5a', 'Costs of services supplied', 'ថៃ្លដើមសេវាបានផ្គត់ផ្គង់', 30);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(13, 'A8', 'Goodwill', 'កេរ្តិ៍ឈ្មោះ/មូលនិធិពាណិជ្ជកម្ម', 10);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(14, 'A9', 'Preliminary and formation expenses', 'ចំណាយបង្កើតសហគ្រាសដំបូង', 10);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(15, 'B8', 'Grant/subsidy', 'ឧបត្ថម្ភកធន', 70);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(16, 'A10', 'Leasehold assets and lease premiums', 'ទ្រព្យសកម្មក្រោមភតិសន្យា​​ និង​បុព្វលាភនៃការប្រើប្', 10);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(17, 'A11', 'Investment in other enterprise', 'វិនិយោគក្នុងសហគ្រាសដទៃ', 10);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(18, 'B9', 'Dividend received or receivable', 'ចំណូលពីភាគលាភបានទទួល​ ឬ ត្រូវទទួល', 70);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(19, 'B10', 'Interest received or receivable', 'ចំំណូលពីការប្រាក់បានទទួល ឬ ត្រូវទទួល', 70);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(20, 'A29', 'Capital/ Share capital', 'មូលធន/ មូលធនភាគហ៊ុន', 30);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(21, 'A12', 'Other non-current assets', 'ទ្រព្យសកម្មរយ:ពេលវែងផ្សេងៗ', 10);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(22, 'B11', 'Royalty received or receivable', 'ចំណូលពីសួយសារបានទទួល ឬ ត្រូវទទួល', 70);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(23, 'A30', 'Share premium', 'តម្លៃលើសនៃការលក់ប័ណ្ណភាគហ៊ុន', 30);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(24, 'B12', 'Rental received or receivable', 'ចំណូលពីការជួលបានទទួល ឬ ត្រូវទទួល', 70);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(25, 'A31', 'Legal capital reserves', 'មូលធនបំរុងច្បាប់', 30);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(26, 'A14', 'Stock of raw materials and supplies', 'ស្តូកវត្ធុធាតុដើម និងសំភារ:ផ្គត់ផ្គង់', 11);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(27, 'A32', 'Reserves revaluation surplus of assets', 'លំអៀងលើសការវាយតំលៃឡើងវិញនូវទ្រព្យសកម្ម', 30);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(28, 'A33', 'Other capital reserves', 'មូលធនបំរុងផ្សេងៗ', 30);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(29, 'A15', 'Stock of goods', 'ស្តុកទំនិញ', 11);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(30, 'B13', 'Gain from disposal of fixed assets (captital gain)', 'ផលចំណេញ/តំលៃលើសពីការលក់ទ្រព្យសកម្មរយះពេលវែង', 70);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(31, 'A34', 'Profit and loss brought forward', 'លទ្ធផលចំណេញ/ ខាត យោងពីមុន', 30);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(32, 'A16', 'Stock of finished goods', 'ស្តុកផលិតផលសម្រាច', 11);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(33, 'A35', 'Profit and loss for the period', 'លទ្ធផងចំណេញ/ ខាត នៃកាលបរិច្ឆេត', 30);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(34, 'B14', 'Gain from disposal of securities', 'ផលចំណេញពីការលក់មូលប័ត្រ/សញ្ញាប័ណ្ណ', 70);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(35, 'A37', 'Loan from related parties', 'បំណុលភាគីជាប់ទាក់ទិន', 21);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(36, 'A38', 'Loan from banks and other external parties', 'បំណុលធនាគារ និងបំណុលភាគីមិនជាប់ទាក់ទិនផ្សេងៗ', 21);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(37, 'B15', 'Share of profit from joint venture', 'ភាគចំណេញពីប្រតិបត្តិការរួមគ្នា', 70);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(38, 'A17', 'Products in progress', 'ផលិតផលកំពុងផលិត', 11);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(39, 'A39', 'Provision for charges and contigencies', 'សវិធានធនសំរាប់បន្ទុក និង​ហានិភ័យ', 21);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(40, 'B16', 'Realised exchange gain', 'ផលចំណេញពីការប្តូរប្រាក់សំរេចបាន', 70);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(41, 'A40', 'Other non-current liabilities', 'បំណុលរយៈពេលវែងផ្សេងៗ', 21);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(42, 'A18', 'Account receivevable/trade debtors', 'គណនីត្រូវទទួល​ /អតិថិជន', 11);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(43, 'B17', 'Unrealised exchange gain', 'ផលចំណេញពីការប្តូរប្រាក់មិនទាន់សំរេចបាន', 70);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(44, 'A42', 'Bank overdraft', 'សាច់ប្រាក់ដកពីធនាគារលើសប្រាក់បញ្ជី (ឥណទានរិបារូប៍រ', 20);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(45, 'A19', 'Other account receivables', 'គណនីទទួលផ្សេងៗ', 11);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(46, 'B18', 'Other revenues', 'ចំណូលដ៏ទៃទៀត', 70);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(47, 'A43', 'Short-term borrowing-current portion of interest bearing borrowing', 'ចំណែកចរន្តនៃបំណុលមានការប្រាក់', 20);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(48, 'A20', 'Prepaid expenses', 'ចំណាយបានកត់ត្រាមុន', 11);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(49, 'B20', 'Salaries expenses', 'ចំណាយបៀវត្ស', 60);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(50, 'A44', 'Accounts payble to relate parties', 'គណនីត្រូវសងបុគ្គលជាប់ទាក់ទិន (ភាគីសម្ព័ន្ធញាត្តិ)', 20);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(51, 'A45', 'Other accounts payable', 'គណនីត្រូវសងផ្សេងៗ', 20);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(52, 'A21', 'Cash on hand and at banks', 'សាច់ប្រាក់នៅក្នុងបេឡា និងនៅធនាគា', 11);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(53, 'B21', 'Fuel, gas,electricity and water expenses', 'ចំណាយប្រេង ឧស្មន័ អគ្គីសនី និងទឹក', 60);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(54, 'A46', 'Unearned revenue, accruals and other current liabilities', 'ចំណូលកត់មុន គណនីចំណាយបង្ករ និងបំណុលរយោៈពេលខ្លីផ្សេ', 20);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(55, 'A47', 'Provision for changes and contigencies', 'សវិធានធនសំរាប់បន្ទុក និង​ហានិភ័យ', 20);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(56, 'B22', 'Travelling and accommodation expenses', 'ចំណាយធើ្វដំណើរ និងចំណាយស្នាក់នៅ', 60);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(57, 'A48', 'Profit tax payable', 'ពន្ធលើប្រាក់ចំណេញត្រូវបង់', 20);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(58, 'A49', 'Other taxes payable', 'ពន្ទ-អាករផ្សេងៗត្រូវបង់', 20);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(59, 'B23', 'Transporttation expenses', 'ចំណាយដឹកជញ្ជូន', 60);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(60, 'A50', 'Differences arissing from currency translation in liabilities', 'លំអៀងពីការប្តូរប្រាក់នៃទ្រព្យសកម្ម', 20);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(61, 'B24', 'Rental expenses', 'ចំណាយលើការជួល', 60);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(62, 'B25', 'Repair anmaintenance expenses', 'ចំណាយលើការថែទាំ និងជួសជុល', 60);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(63, 'B26', 'Entertament expenses', 'ចំណាយលើការកំសាន្តសប្យាយ', 60);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(64, 'A22', 'Prepayment of profit tax credit', 'ឥណទានប្រាក់រំដោះពន្ធលើប្រាក់ចំណេញ', 11);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(65, 'B27', 'Commission, advertising, and selling expenses', 'ចំណាយកំរៃជើងសារ ផ្សាយពាណិ្ជកម្ម និងចំណាយការលក់', 60);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(66, 'A23', 'Value added tax credit', 'ឥណទានអាករលើតម្លៃបន្ថែម', 11);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(67, 'B28', 'Other taxes expenses', 'ចំណាយបង់ពន្ធ និងអាករផេ្សងៗ', 60);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(68, 'B29', 'Donation expenses', 'ចំណាយលើអំណោយ', 60);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(69, 'A24', 'Other taxes credit', 'ឥណទានពន្ធ-អាករដដៃទៀត', 11);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(70, 'B30', 'Management, consultant, other technical, and other similar services expenses', 'ចំណាយសេវាគ្រប់គ្រង ពិគ្រោះយោបល់ បចេ្វកទេស និងសេវាប', 60);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(71, 'A25', 'Other current assets', 'ទ្រព្យសកម្មពេលខ្លីផ្សេងៗ', 11);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(72, 'B31', 'Royalty expenses', 'ចំណាយលើសួយសារ', 60);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(73, 'B32', 'Bad debts written off expenses', 'ចំណាយលើបំណុលទារមិនបាន', 60);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(74, 'A26', 'Diffference arising from currency translation in assets', 'លំអៀងពីការប្តូរទ្រព្យសកម្ម', 11);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(75, 'B33', 'Armortisation/depletion and depreciation expenses', 'ចំណាយរំលស់', 60);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(76, 'B34', 'Increase /(decrease) in expenses', 'ការកើនឡើង /​ (ថយចុះ) សំវិធានធន', 60);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(77, 'B35', 'Loss on siposal of fixed assets', 'ខាតពីការលក់ទ្រព្យសកម្មរយះពេលវែង', 60);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(78, 'B36', 'Realised exchange loss', 'ខាតពីការប្តូរប្រាក់សំរេចបាន', 60);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(79, 'B37', 'Unrealised exchange loss', 'ខាតពីការប្តូរប្រាក់មិនទាន់សំរេចបាន', 60);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(80, 'B38', 'Other expenses', 'ចំណាយផេ្សងៗ', 60);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(81, 'B40', 'Interest expenses paid to residents', 'ចំណាយការប្រាក់បង់អោយនិវាសនជន', 60);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(82, 'B41', 'Interest expenses paid to non-residents', 'ចំណាយការប្រាក់បង់អោយអនិវាសនជន', 60);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(83, 'A1', 'Non-current assets/ fixed assets', 'ទ្រព្យសកម្មរយៈពេលេវែង', 10);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(84, 'A13', 'Current assets', 'ទ្រព្យសកម្មរយៈពេលខ្លី', 11);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(85, 'A28', 'Equity', 'មូលនិធិ/ ទុនម្ចាស់ទ្រព្យ ', 30);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(86, 'A36', 'Non-current liabilities', 'បំណុលរយៈពេលវែង', 21);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(87, 'A41', 'Current liabilities', 'បំណុលរយៈពេលខ្លី', 20);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(88, 'B0', 'Operating revenue', 'ចំណូលប្រតិបត្តិការ', 30);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(89, 'B7', 'Other revenue', 'ចំណូលផ្សេងៗ', 70);
INSERT INTO `erp_gl_charts_tax` (`account_tax_id`, `accountcode`, `accountname`, `accountname_kh`, `sectionid`) VALUES(90, 'B19', 'Operating expenses', 'ចំណាយប្រតិបតិ្តការ', 60);

--
-- Dumping data for table `erp_gl_sections`
--

INSERT INTO `erp_gl_sections` (`sectionid`, `sectionname`, `sectionname_kh`, `AccountType`, `description`, `pandl`, `order_stat`) VALUES(10, 'CURRENT ASSETS', 'ទ្រព្យសកម្មរយះពេលខ្លី', 'AS', 'CURRENT ASSETS', 0, 10);
INSERT INTO `erp_gl_sections` (`sectionid`, `sectionname`, `sectionname_kh`, `AccountType`, `description`, `pandl`, `order_stat`) VALUES(11, 'FIXED ASSETS', 'ទ្រព្យសកម្មរយះពេលវែង', 'AS', 'FIXED ASSETS', 0, 11);
INSERT INTO `erp_gl_sections` (`sectionid`, `sectionname`, `sectionname_kh`, `AccountType`, `description`, `pandl`, `order_stat`) VALUES(20, 'CURRENT LIABILITIES', 'បំណុលរយះពេលខ្លី', 'LI', 'CURRENT LIABILITIES', 0, 20);
INSERT INTO `erp_gl_sections` (`sectionid`, `sectionname`, `sectionname_kh`, `AccountType`, `description`, `pandl`, `order_stat`) VALUES(21, 'NON-CURRENT LIABILITIES', 'បំណុលរយះពេលវែង', 'LI', 'NON-CURRENT LIABILITIES', 0, 21);
INSERT INTO `erp_gl_sections` (`sectionid`, `sectionname`, `sectionname_kh`, `AccountType`, `description`, `pandl`, `order_stat`) VALUES(30, 'EQUITY AND RETAINED EARNING', 'មូលនិធិ/ទុនម្ចាស់ទ្រព្យ', 'EQ', 'EQUITY AND RETAINED EARNING', 0, 30);
INSERT INTO `erp_gl_sections` (`sectionid`, `sectionname`, `sectionname_kh`, `AccountType`, `description`, `pandl`, `order_stat`) VALUES(40, 'INCOME', 'ចំណូលប្រតិបត្តិការ', 'RE', 'INCOME', 1, 40);
INSERT INTO `erp_gl_sections` (`sectionid`, `sectionname`, `sectionname_kh`, `AccountType`, `description`, `pandl`, `order_stat`) VALUES(50, 'COST OF GOODS SOLD', 'ចំណាយថ្លៃដើម', 'CO', 'COST OF GOODS SOLD', 1, 50);
INSERT INTO `erp_gl_sections` (`sectionid`, `sectionname`, `sectionname_kh`, `AccountType`, `description`, `pandl`, `order_stat`) VALUES(60, 'OPERATING EXPENSES', 'ចំណាយប្រតិបត្តិការ', 'EX', 'OPERATING EXPENSES', 1, 60);
INSERT INTO `erp_gl_sections` (`sectionid`, `sectionname`, `sectionname_kh`, `AccountType`, `description`, `pandl`, `order_stat`) VALUES(70, 'OTHER INCOME', 'ចំណូលផ្សេងៗ', 'OI', 'OTHER INCOME', 1, 70);
INSERT INTO `erp_gl_sections` (`sectionid`, `sectionname`, `sectionname_kh`, `AccountType`, `description`, `pandl`, `order_stat`) VALUES(80, 'OTHER EXPENSE', 'ចំណាយផ្សេងៗ', 'OX', 'OTHER EXPENSE', 1, 80);
INSERT INTO `erp_gl_sections` (`sectionid`, `sectionname`, `sectionname_kh`, `AccountType`, `description`, `pandl`, `order_stat`) VALUES(90, 'GAIN & LOSS', 'ចំណេញខាត', 'GL', 'GAIN & LOSS', 1, 90);

--
-- Dumping data for table `erp_groups`
--

INSERT INTO `erp_groups` (`id`, `name`, `description`) VALUES(1, 'owner', 'Owner');
INSERT INTO `erp_groups` (`id`, `name`, `description`) VALUES(2, 'admin', 'Administrator');
INSERT INTO `erp_groups` (`id`, `name`, `description`) VALUES(3, 'customer', 'Customer');
INSERT INTO `erp_groups` (`id`, `name`, `description`) VALUES(4, 'supplier', 'Supplier');

--
-- Dumping data for table `erp_order_ref`
--

INSERT INTO `erp_order_ref` (`ref_id`, `biller_id`, `date`, `so`, `qu`, `po`, `to`, `pos`, `do`, `pay`, `re`, `ex`, `sp`, `pp`, `sl`, `tr`, `rep`, `con`, `pj`, `sd`, `es`, `esr`, `sao`, `poa`, `pq`, `jr`, `qa`, `st`, `adc`, `tx`, `pro`, `cus`, `sup`, `emp`, `pn`) VALUES ('1', '1', '2018-03-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` (`ref_id`, `biller_id`, `date`, `so`, `qu`, `po`, `to`, `pos`, `do`, `pay`, `re`, `ex`, `sp`, `pp`, `sl`, `tr`, `rep`, `con`, `pj`, `sd`, `es`, `esr`, `sao`, `poa`, `pq`, `jr`, `qa`, `st`, `adc`, `tx`, `pro`, `cus`, `sup`, `emp`, `pn`) VALUES ('2', '1', '2018-04-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` (`ref_id`, `biller_id`, `date`, `so`, `qu`, `po`, `to`, `pos`, `do`, `pay`, `re`, `ex`, `sp`, `pp`, `sl`, `tr`, `rep`, `con`, `pj`, `sd`, `es`, `esr`, `sao`, `poa`, `pq`, `jr`, `qa`, `st`, `adc`, `tx`, `pro`, `cus`, `sup`, `emp`, `pn`) VALUES ('3', '1', '2018-05-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` (`ref_id`, `biller_id`, `date`, `so`, `qu`, `po`, `to`, `pos`, `do`, `pay`, `re`, `ex`, `sp`, `pp`, `sl`, `tr`, `rep`, `con`, `pj`, `sd`, `es`, `esr`, `sao`, `poa`, `pq`, `jr`, `qa`, `st`, `adc`, `tx`, `pro`, `cus`, `sup`, `emp`, `pn`) VALUES ('4', '1', '2018-06-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` (`ref_id`, `biller_id`, `date`, `so`, `qu`, `po`, `to`, `pos`, `do`, `pay`, `re`, `ex`, `sp`, `pp`, `sl`, `tr`, `rep`, `con`, `pj`, `sd`, `es`, `esr`, `sao`, `poa`, `pq`, `jr`, `qa`, `st`, `adc`, `tx`, `pro`, `cus`, `sup`, `emp`, `pn`) VALUES ('5', '1', '2018-07-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` (`ref_id`, `biller_id`, `date`, `so`, `qu`, `po`, `to`, `pos`, `do`, `pay`, `re`, `ex`, `sp`, `pp`, `sl`, `tr`, `rep`, `con`, `pj`, `sd`, `es`, `esr`, `sao`, `poa`, `pq`, `jr`, `qa`, `st`, `adc`, `tx`, `pro`, `cus`, `sup`, `emp`, `pn`) VALUES ('6', '1', '2018-08-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` (`ref_id`, `biller_id`, `date`, `so`, `qu`, `po`, `to`, `pos`, `do`, `pay`, `re`, `ex`, `sp`, `pp`, `sl`, `tr`, `rep`, `con`, `pj`, `sd`, `es`, `esr`, `sao`, `poa`, `pq`, `jr`, `qa`, `st`, `adc`, `tx`, `pro`, `cus`, `sup`, `emp`, `pn`) VALUES ('7', '1', '2018-09-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` (`ref_id`, `biller_id`, `date`, `so`, `qu`, `po`, `to`, `pos`, `do`, `pay`, `re`, `ex`, `sp`, `pp`, `sl`, `tr`, `rep`, `con`, `pj`, `sd`, `es`, `esr`, `sao`, `poa`, `pq`, `jr`, `qa`, `st`, `adc`, `tx`, `pro`, `cus`, `sup`, `emp`, `pn`) VALUES ('8', '1', '2018-10-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` (`ref_id`, `biller_id`, `date`, `so`, `qu`, `po`, `to`, `pos`, `do`, `pay`, `re`, `ex`, `sp`, `pp`, `sl`, `tr`, `rep`, `con`, `pj`, `sd`, `es`, `esr`, `sao`, `poa`, `pq`, `jr`, `qa`, `st`, `adc`, `tx`, `pro`, `cus`, `sup`, `emp`, `pn`) VALUES ('9', '1', '2018-11-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` (`ref_id`, `biller_id`, `date`, `so`, `qu`, `po`, `to`, `pos`, `do`, `pay`, `re`, `ex`, `sp`, `pp`, `sl`, `tr`, `rep`, `con`, `pj`, `sd`, `es`, `esr`, `sao`, `poa`, `pq`, `jr`, `qa`, `st`, `adc`, `tx`, `pro`, `cus`, `sup`, `emp`, `pn`) VALUES ('10', '1', '2018-12-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` (`ref_id`, `biller_id`, `date`, `so`, `qu`, `po`, `to`, `pos`, `do`, `pay`, `re`, `ex`, `sp`, `pp`, `sl`, `tr`, `rep`, `con`, `pj`, `sd`, `es`, `esr`, `sao`, `poa`, `pq`, `jr`, `qa`, `st`, `adc`, `tx`, `pro`, `cus`, `sup`, `emp`, `pn`) VALUES ('11', '1', '2019-01-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` (`ref_id`, `biller_id`, `date`, `so`, `qu`, `po`, `to`, `pos`, `do`, `pay`, `re`, `ex`, `sp`, `pp`, `sl`, `tr`, `rep`, `con`, `pj`, `sd`, `es`, `esr`, `sao`, `poa`, `pq`, `jr`, `qa`, `st`, `adc`, `tx`, `pro`, `cus`, `sup`, `emp`, `pn`) VALUES ('12', '1', '2019-02-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` (`ref_id`, `biller_id`, `date`, `so`, `qu`, `po`, `to`, `pos`, `do`, `pay`, `re`, `ex`, `sp`, `pp`, `sl`, `tr`, `rep`, `con`, `pj`, `sd`, `es`, `esr`, `sao`, `poa`, `pq`, `jr`, `qa`, `st`, `adc`, `tx`, `pro`, `cus`, `sup`, `emp`, `pn`) VALUES ('13', '1', '2019-03-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` (`ref_id`, `biller_id`, `date`, `so`, `qu`, `po`, `to`, `pos`, `do`, `pay`, `re`, `ex`, `sp`, `pp`, `sl`, `tr`, `rep`, `con`, `pj`, `sd`, `es`, `esr`, `sao`, `poa`, `pq`, `jr`, `qa`, `st`, `adc`, `tx`, `pro`, `cus`, `sup`, `emp`, `pn`) VALUES ('14', '1', '2019-04-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` (`ref_id`, `biller_id`, `date`, `so`, `qu`, `po`, `to`, `pos`, `do`, `pay`, `re`, `ex`, `sp`, `pp`, `sl`, `tr`, `rep`, `con`, `pj`, `sd`, `es`, `esr`, `sao`, `poa`, `pq`, `jr`, `qa`, `st`, `adc`, `tx`, `pro`, `cus`, `sup`, `emp`, `pn`) VALUES ('15', '1', '2019-05-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` (`ref_id`, `biller_id`, `date`, `so`, `qu`, `po`, `to`, `pos`, `do`, `pay`, `re`, `ex`, `sp`, `pp`, `sl`, `tr`, `rep`, `con`, `pj`, `sd`, `es`, `esr`, `sao`, `poa`, `pq`, `jr`, `qa`, `st`, `adc`, `tx`, `pro`, `cus`, `sup`, `emp`, `pn`) VALUES ('16', '1', '2019-06-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` (`ref_id`, `biller_id`, `date`, `so`, `qu`, `po`, `to`, `pos`, `do`, `pay`, `re`, `ex`, `sp`, `pp`, `sl`, `tr`, `rep`, `con`, `pj`, `sd`, `es`, `esr`, `sao`, `poa`, `pq`, `jr`, `qa`, `st`, `adc`, `tx`, `pro`, `cus`, `sup`, `emp`, `pn`) VALUES ('17', '1', '2019-07-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` (`ref_id`, `biller_id`, `date`, `so`, `qu`, `po`, `to`, `pos`, `do`, `pay`, `re`, `ex`, `sp`, `pp`, `sl`, `tr`, `rep`, `con`, `pj`, `sd`, `es`, `esr`, `sao`, `poa`, `pq`, `jr`, `qa`, `st`, `adc`, `tx`, `pro`, `cus`, `sup`, `emp`, `pn`) VALUES ('18', '1', '2019-08-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` (`ref_id`, `biller_id`, `date`, `so`, `qu`, `po`, `to`, `pos`, `do`, `pay`, `re`, `ex`, `sp`, `pp`, `sl`, `tr`, `rep`, `con`, `pj`, `sd`, `es`, `esr`, `sao`, `poa`, `pq`, `jr`, `qa`, `st`, `adc`, `tx`, `pro`, `cus`, `sup`, `emp`, `pn`) VALUES ('19', '1', '2019-09-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` (`ref_id`, `biller_id`, `date`, `so`, `qu`, `po`, `to`, `pos`, `do`, `pay`, `re`, `ex`, `sp`, `pp`, `sl`, `tr`, `rep`, `con`, `pj`, `sd`, `es`, `esr`, `sao`, `poa`, `pq`, `jr`, `qa`, `st`, `adc`, `tx`, `pro`, `cus`, `sup`, `emp`, `pn`) VALUES ('20', '1', '2019-10-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` (`ref_id`, `biller_id`, `date`, `so`, `qu`, `po`, `to`, `pos`, `do`, `pay`, `re`, `ex`, `sp`, `pp`, `sl`, `tr`, `rep`, `con`, `pj`, `sd`, `es`, `esr`, `sao`, `poa`, `pq`, `jr`, `qa`, `st`, `adc`, `tx`, `pro`, `cus`, `sup`, `emp`, `pn`) VALUES ('21', '1', '2019-11-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` (`ref_id`, `biller_id`, `date`, `so`, `qu`, `po`, `to`, `pos`, `do`, `pay`, `re`, `ex`, `sp`, `pp`, `sl`, `tr`, `rep`, `con`, `pj`, `sd`, `es`, `esr`, `sao`, `poa`, `pq`, `jr`, `qa`, `st`, `adc`, `tx`, `pro`, `cus`, `sup`, `emp`, `pn`) VALUES ('22', '1', '2019-12-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` (`ref_id`, `biller_id`, `date`, `so`, `qu`, `po`, `to`, `pos`, `do`, `pay`, `re`, `ex`, `sp`, `pp`, `sl`, `tr`, `rep`, `con`, `pj`, `sd`, `es`, `esr`, `sao`, `poa`, `pq`, `jr`, `qa`, `st`, `adc`, `tx`, `pro`, `cus`, `sup`, `emp`, `pn`) VALUES ('23', '1', '2020-01-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` (`ref_id`, `biller_id`, `date`, `so`, `qu`, `po`, `to`, `pos`, `do`, `pay`, `re`, `ex`, `sp`, `pp`, `sl`, `tr`, `rep`, `con`, `pj`, `sd`, `es`, `esr`, `sao`, `poa`, `pq`, `jr`, `qa`, `st`, `adc`, `tx`, `pro`, `cus`, `sup`, `emp`, `pn`) VALUES ('24', '1', '2020-02-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `erp_order_ref` (`ref_id`, `biller_id`, `date`, `so`, `qu`, `po`, `to`, `pos`, `do`, `pay`, `re`, `ex`, `sp`, `pp`, `sl`, `tr`, `rep`, `con`, `pj`, `sd`, `es`, `esr`, `sao`, `poa`, `pq`, `jr`, `qa`, `st`, `adc`, `tx`, `pro`, `cus`, `sup`, `emp`, `pn`) VALUES ('25', '1', '2020-03-01', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');



--
-- Dumping data for table `erp_price_groups`
--

INSERT INTO `erp_price_groups` (`id`, `name`) VALUES(1, 'Price A');
INSERT INTO `erp_price_groups` (`id`, `name`) VALUES(2, 'Price B');

--
-- Dumping data for table `erp_settings`
--

INSERT INTO `erp_settings` (`setting_id`, `logo`, `logo2`, `site_name`, `language`, `default_warehouse`, `accounting_method`, `default_currency`, `default_tax_rate`, `rows_per_page`, `version`, `default_tax_rate2`, `dateformat`, `sales_prefix`, `quote_prefix`, `purchase_prefix`, `transfer_prefix`, `delivery_prefix`, `payment_prefix`, `return_prefix`, `expense_prefix`, `transaction_prefix`, `stock_count_prefix`, `project_plan_prefix`, `adjust_cost_prefix`, `item_addition`, `theme`, `product_serial`, `default_discount`, `product_discount`, `discount_method`, `tax1`, `tax2`, `overselling`, `restrict_user`, `restrict_calendar`, `timezone`, `iwidth`, `iheight`, `twidth`, `theight`, `watermark`, `reg_ver`, `allow_reg`, `reg_notification`, `auto_reg`, `protocol`, `mailpath`, `smtp_host`, `smtp_user`, `smtp_pass`, `smtp_port`, `smtp_crypto`, `corn`, `customer_group`, `default_email`, `mmode`, `bc_fix`, `auto_detect_barcode`, `captcha`, `reference_format`, `racks`, `attributes`, `product_expiry`, `purchase_decimals`, `decimals`, `qty_decimals`, `decimals_sep`, `thousands_sep`, `invoice_view`, `default_biller`, `envato_username`, `purchase_code`, `rtl`, `each_spent`, `ca_point`, `each_sale`, `sa_point`, `update`, `sac`, `display_all_products`, `display_symbol`, `symbol`, `item_slideshow`, `barcode_separator`, `remove_expired`, `sale_payment_prefix`, `purchase_payment_prefix`, `sale_loan_prefix`, `auto_print`, `returnp_prefix`, `alert_day`, `convert_prefix`, `purchase_serial`, `enter_using_stock_prefix`, `enter_using_stock_return_prefix`, `supplier_deposit_prefix`, `sale_order_prefix`, `boms_method`, `separate_code`, `show_code`, `bill_to`, `show_po`, `show_company_code`, `purchase_order_prefix`, `credit_limit`, `purchase_request_prefix`, `acc_cate_separate`, `stock_deduction`, `delivery`, `authorization`, `shipping`, `separate_ref`, `journal_prefix`, `adjustment_prefix`, `system_management`, `table_item`, `show_logo_invoice`, `show_biller_name_invoice`, `tax_prefix`, `project_code_prefix`, `customer_code_prefix`, `supplier_code_prefix`, `employee_code_prefix`, `allow_change_date`, `increase_stock_import`, `member_card_expiry`, `tax_calculate`, `business_type`) VALUES ('1', '', 'header_logo4.png', 'iCloudERP_v3.5_Dev', 'english', '1', '2', 'USD', '1', '10', '3.4', '1', '5', 'SALE', 'QUOTE', 'PO', 'TR', 'DO', 'IPAY', 'RE', 'EX', 'J', 'ST', 'PN', 'ADC', '0', 'default', '0', '1', '1', '1', '1', '1', '1', '1', '1', 'Asia/Phnom_Penh', '800', '800', '60', '60', '0', NULL, NULL, NULL, NULL, 'smtp', '/usr/sbin/sendmail', 'icloud-erp.info', 'crm@icloud-erp.info', '4Wb85wL+xXm+BJpaI56/dLikpwe1red5DycurmulQsMVxPVSFm27f03auAujsYvSTskhNPuQtW+Y1eTM3MGGWw==', '465', 'ssl', '0000-00-00 00:00:00', '1', 'iclouderp@gmail.com', '0', '4', '1', '0', '2', '0', '1', '0', '3', '2', '0', '.', ',', '0', '1', 'cloud-net', '53d35644-a36e-45cd-b7ee-8dde3a08f83d', NULL, NULL, NULL, NULL, NULL, '0', '0', '0', '1', '$', '0', '_', '0', 'RV', 'PV', 'LOAN', '0', 'PRE', '7', 'CON', '0', 'ES', 'ESR', 'SDE', 'SAO', '0', '1', '1', '0', '0', '1', 'PAO', '0', 'PQ', '1', NULL, 'both', 'manual', '1', '1', 'JR', 'ADJ', 'biller', 'table', '1', '1', '', 'PRO', 'CUS', 'SUP', 'EMP', '1', '1', '0', '0', 'whole_sale');


--
-- Dumping data for table `erp_tax_rates`
--

INSERT INTO `erp_tax_rates` (`id`, `name`, `code`, `rate`, `type`) VALUES(1, 'No Tax', 'NT', '0.0000', '2');
INSERT INTO `erp_tax_rates` (`id`, `name`, `code`, `rate`, `type`) VALUES(2, 'VAT @10%', 'VAT10', '10.0000', '1');
INSERT INTO `erp_tax_rates` (`id`, `name`, `code`, `rate`, `type`) VALUES(3, 'GST @6%', 'GST', '6.0000', '1');
INSERT INTO `erp_tax_rates` (`id`, `name`, `code`, `rate`, `type`) VALUES(4, 'VAT @20%', 'VT20', '20.0000', '1');
INSERT INTO `erp_tax_rates` (`id`, `name`, `code`, `rate`, `type`) VALUES(5, 'TAX @10%', 'TAX', '10.0000', '1');

--
-- Dumping data for table `erp_users`
--

INSERT INTO `erp_users` (`id`, `last_ip_address`, `ip_address`, `username`, `password`, `salt`, `email`, `activation_code`, `forgotten_password_code`, `forgotten_password_time`, `remember_code`, `created_on`, `last_login`, `active`, `first_name`, `last_name`, `company`, `phone`, `avatar`, `gender`, `group_id`, `warehouse_id`, `biller_id`, `company_id`, `show_cost`, `show_price`, `award_points`, `view_right`, `edit_right`, `allow_discount`, `annualLeave`, `sickday`, `speacialLeave`, `othersLeave`, `first_name_kh`, `last_name_kh`, `nationality_kh`, `race_kh`, `pos_layout`, `pack_id`, `sales_standard`, `sales_combo`, `sales_digital`, `sales_service`, `sales_category`, `purchase_standard`, `purchase_combo`, `purchase_digital`, `purchase_service`, `purchase_category`, `date_of_birth`, `nationality`, `position`, `salary`, `spouse`, `number_of_child`, `employeed_date`, `last_paid`, `address`, `note`, `emergency_contact`, `emp_code`, `allowance`, `emp_type`, `tax_salary_type`, `hide_row`, `emp_group`, `identify`, `identify_date`, `user_type`, `advance_amount`) VALUES ('1', '::1', '\0\0', 'owner', '06e6c33bfa4496ceceb8eff15f40ec726d8d2336', '', 'owner@cloudnet.com.kh', '', '', NULL, '078c30f596fa50aa383a756752d503275fdc59c8', '1351661704', '1520652472', '1', 'Own', 'Owner', 'ABC Shop', '012345678', '', 'male', '1', '', NULL, NULL, '0', '0', '355', '0', '0', '0', '0', '0', NULL, NULL, 'ម្ចាស់', 'ផ្ទាល់', 'ខ្មែរ', '', NULL, '5', NULL, NULL, NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, '1996-12-11', 'Khmer', '', '0.0000', '', '0', '2016-05-01', '2016-12-09', '', '', '', '0012', '0.0000', '', '', '0', NULL, '313231', NULL, NULL, NULL);


--
-- Dumping data for table `erp_warehouses`
--

INSERT INTO `erp_warehouses` (`id`, `code`, `name`, `address`, `map`, `phone`, `email`) VALUES ('1', 'WH-0001', 'CloudNET', 'Chroy Chongva, Phnom Penh, Cambodia', NULL, '09633339898', 'warehouse@cloudnet.com.kh');


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
