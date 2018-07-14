<?php defined('BASEPATH') OR exit('No direct script access allowed');

class system_settings extends MY_Controller
{

    function __construct()
    {
        parent::__construct();

        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            redirect('login');
        }
        $this->lang->load('settings', $this->Settings->language);
        $this->load->library('form_validation');
        $this->load->model('settings_model');
		$this->load->model('products_model');
        $this->upload_path = 'assets/uploads/';
        $this->thumbs_path = 'assets/uploads/thumbs/';
        $this->image_types = 'gif|jpg|jpeg|png|tif';
        $this->digital_file_types = 'zip|psd|ai|rar|pdf|doc|docx|xls|xlsx|ppt|pptx|gif|jpg|jpeg|png|tif';
        $this->allowed_file_size = '1024';
    }

    function index()
    {
        $this->form_validation->set_rules('site_name', lang('site_name'), 'trim|required');
        $this->form_validation->set_rules('dateformat', lang('dateformat'), 'trim|required');
        //$this->form_validation->set_rules('timezone', lang('timezone'), 'trim|required');
        //$this->form_validation->set_rules('mmode', lang('maintenance_mode'), 'trim|required');
        //$this->form_validation->set_rules('logo', lang('logo'), 'trim');
        $this->form_validation->set_rules('iwidth', lang('image_width'), 'trim|numeric|required');
        $this->form_validation->set_rules('iheight', lang('image_height'), 'trim|numeric|required');
        $this->form_validation->set_rules('twidth', lang('thumbnail_width'), 'trim|numeric|required');
        $this->form_validation->set_rules('theight', lang('thumbnail_height'), 'trim|numeric|required');
        $this->form_validation->set_rules('display_all_products', lang('display_all_products'), 'trim|numeric|required');
        $this->form_validation->set_rules('watermark', lang('watermark'), 'trim|required');
        //$this->form_validation->set_rules('reg_ver', lang('reg_ver'), 'trim|required');
        //$this->form_validation->set_rules('allow_reg', lang('allow_reg'), 'trim|required');
        //$this->form_validation->set_rules('reg_notification', lang('reg_notification'), 'trim|required');
        $this->form_validation->set_rules('currency', lang('default_currency'), 'trim|required');
        $this->form_validation->set_rules('email', lang('default_email'), 'trim|required');
        $this->form_validation->set_rules('language', lang('language'), 'trim|required');
        $this->form_validation->set_rules('warehouse', lang('default_warehouse'), 'trim|required');
        $this->form_validation->set_rules('biller', lang('default_biller'), 'trim|required');
        $this->form_validation->set_rules('tax_rate', lang('product_tax'), 'trim|required');
        $this->form_validation->set_rules('tax_rate2', lang('invoice_tax'), 'trim|required');
        $this->form_validation->set_rules('sales_prefix', lang('sales_prefix'), 'trim');
        $this->form_validation->set_rules('sale_order_prefix', lang('sale_order_prefix'), 'trim');
        $this->form_validation->set_rules('quote_prefix', lang('quote_prefix'), 'trim');
        $this->form_validation->set_rules('purchase_prefix', lang('purchase_prefix'), 'trim');
        $this->form_validation->set_rules('transfer_prefix', lang('transfer_prefix'), 'trim');
        $this->form_validation->set_rules('delivery_prefix', lang('delivery_prefix'), 'trim');
        $this->form_validation->set_rules('payment_prefix', lang('payment_prefix'), 'trim');
        $this->form_validation->set_rules('return_prefix', lang('return_prefix'), 'trim');
        $this->form_validation->set_rules('expense_prefix', lang('expense_prefix'), 'trim');
        $this->form_validation->set_rules('detect_barcode', lang('detect_barcode'), 'trim|required');
        //$this->form_validation->set_rules('theme', lang('theme'), 'trim|required');
        $this->form_validation->set_rules('rows_per_page', lang('rows_per_page'), 'trim|required|greater_than[9]|less_than[501]');
        $this->form_validation->set_rules('accounting_method', lang('accounting_method'), 'trim|required');
        $this->form_validation->set_rules('product_serial', lang('product_serial'), 'trim|required');
        $this->form_validation->set_rules('product_discount', lang('product_discount'), 'trim|required');
        $this->form_validation->set_rules('bc_fix', lang('bc_fix'), 'trim|numeric|required');
        $this->form_validation->set_rules('protocol', lang('email_protocol'), 'trim|required');
        //$this->form_validation->set_rules('stock_deduction', lang('stock_deduction'), 'trim|required');
        $this->form_validation->set_rules('shipping', lang('shipping'), 'trim|required');
        $this->form_validation->set_rules('authorization', lang('authorization'), 'trim|required');
        $this->form_validation->set_rules('project_code_prefix', lang('project_code_prefix'), 'trim|required');
        $this->form_validation->set_rules('customer_code_prefix', lang('customer_code_prefix'), 'trim|required');
        $this->form_validation->set_rules('supplier_code_prefix', lang('supplier_code_prefix'), 'trim|required');
        $this->form_validation->set_rules('employee_code_prefix', lang('employee_code_prefix'), 'trim|required');
        $this->form_validation->set_rules('increase_stock_import', lang('increase_stock_import'), 'trim|required');

        if ($this->input->post('protocol') == 'smtp') {
            $this->form_validation->set_rules('smtp_host', lang('smtp_host'), 'required');
            $this->form_validation->set_rules('smtp_user', lang('smtp_user'), 'required');
            $this->form_validation->set_rules('smtp_pass', lang('smtp_pass'), 'required');
            $this->form_validation->set_rules('smtp_port', lang('smtp_port'), 'required');
        }
        if ($this->input->post('protocol') == 'sendmail') {
            $this->form_validation->set_rules('mailpath', lang('mailpath'), 'required');
        }
        $this->form_validation->set_rules('decimals', lang('decimals'), 'trim|required');
        $this->form_validation->set_rules('purchase_decimals', lang('purchase_decimals'), 'trim|required');
        $this->form_validation->set_rules('decimals_sep', lang('decimals_sep'), 'trim|required');
        $this->form_validation->set_rules('thousands_sep', lang('thousands_sep'), 'trim|required');
        $this->load->library('encrypt');

        if ($this->form_validation->run() == true) {

            $language = $this->input->post('language');

            if ((file_exists('erp/language/' . $language . '/erp_lang.php') && is_dir('erp/language/' . $language)) || $language == 'english') {
                $lang = $language;
            } else {
                $this->session->set_flashdata('error', lang('language_x_found'));
                redirect("system_settings");
                $lang = 'english';
            }

            $tax1 = ($this->input->post('tax_rate') != 0) ? 1 : 0;
            $tax2 = ($this->input->post('tax_rate2') != 0) ? 1 : 0;

            $data = array(
				'site_name' 						=> DEMO ? 'iCloudERP - POS' : $this->input->post('site_name'),
                'rows_per_page' 					=> $this->input->post('rows_per_page'),
                'dateformat' 						=> $this->input->post('dateformat'),
                'timezone' => 'Asia/Phnom_Penh',
                'mmode' 							=> trim($this->input->post('mmode')),
                'iwidth' 							=> $this->input->post('iwidth'),
                'iheight' 							=> $this->input->post('iheight'),
                'twidth' 							=> $this->input->post('twidth'),
                'theight' 							=> $this->input->post('theight'),
                'watermark' 						=> $this->input->post('watermark'),
                'reg_ver' 							=> $this->input->post('reg_ver'),
                'allow_reg' 						=> $this->input->post('allow_reg'),
                'reg_notification' 					=> $this->input->post('reg_notification'),
                'accounting_method' 				=> $this->input->post('accounting_method'),
                'default_email' 					=> DEMO ? 'icloud.erp@gmail.com' : $this->input->post('email'),
                'language' 							=> $lang,
                'default_warehouse' 				=> $this->input->post('warehouse'),
                'default_tax_rate' 					=> $this->input->post('tax_rate'),
                'default_tax_rate2' 				=> $this->input->post('tax_rate2'),
                'sales_prefix' 						=> $this->input->post('sales_prefix'),
                'quote_prefix' 						=> $this->input->post('quote_prefix'),
                'purchase_prefix' 					=> $this->input->post('purchase_prefix'),
				'sale_order_prefix' 				=> $this->input->post('sale_order_prefix'),
                'transfer_prefix' 					=> $this->input->post('transfer_prefix'),
                'delivery_prefix' 					=> $this->input->post('delivery_prefix'),
                'supplier_deposit_prefix' 			=> $this->input->post('supplier_deposit_prefix'),
                'payment_prefix' 					=> $this->input->post('payment_prefix'),
                'enter_using_stock_prefix' 			=> $this->input->post('enter_using_stock_prefix'),
                'enter_using_stock_return_prefix' 	=> $this->input->post('enter_using_stock_return_prefix'),
				'adjust_cost_prefix' 				=> $this->input->post('adjust_cost_prefix'),
				'project_plan_prefix' 				=> $this->input->post('project_plan_prefix'),
                'return_prefix' 					=> $this->input->post('return_prefix'),
                'expense_prefix' 					=> $this->input->post('expense_prefix'),
                'auto_detect_barcode'	 			=> trim($this->input->post('detect_barcode')),
                //'theme' 							=> trim($this->input->post('theme')),
                'product_serial' 					=> $this->input->post('product_serial'),
                'customer_group' 					=> $this->input->post('customer_group'),
                'product_expiry' 					=> $this->input->post('product_expiry'),
                'product_discount' 					=> $this->input->post('product_discount'),
                'default_currency' 					=> $this->input->post('currency'),
                'bc_fix' 							=> $this->input->post('bc_fix'),
                'tax1' 								=> $tax1,
                'tax2' 								=> $tax2,
                'overselling' 						=> $this->input->post('restrict_sale'),
                'reference_format' 					=> $this->input->post('reference_format'),
                'racks' 							=> $this->input->post('racks'),
                'attributes' 						=> $this->input->post('attributes'),
                'restrict_calendar' 				=> $this->input->post('restrict_calendar'),
                'captcha' 							=> $this->input->post('captcha'),
                'item_addition' 					=> $this->input->post('item_addition'),
                'protocol' 							=> DEMO ? 'mail' : $this->input->post('protocol'),
                'mailpath' 							=> $this->input->post('mailpath'),
                'smtp_host' 						=> $this->input->post('smtp_host'),
                'smtp_user' 						=> $this->input->post('smtp_user'),
                'smtp_port' 						=> $this->input->post('smtp_port'),
                'smtp_crypto' 						=> $this->input->post('smtp_crypto') ? $this->input->post('smtp_crypto') : NULL,
                'decimals' 							=> $this->input->post('decimals'),
                'purchase_decimals' 				=> $this->input->post('purchase_decimals'),
                'decimals_sep' 						=> $this->input->post('decimals_sep'),
                'thousands_sep' 					=> $this->input->post('thousands_sep'),
                'default_biller' 					=> $this->input->post('biller'),
                'invoice_view' 						=> $this->input->post('invoice_view'),
                'rtl' 								=> $this->input->post('rtl'),
                'each_spent' 						=> $this->input->post('each_spent') ? $this->input->post('each_spent') : NULL,
                'ca_point' 							=> $this->input->post('ca_point') ? $this->input->post('ca_point') : NULL,
                'each_sale' 						=> $this->input->post('each_sale') ? $this->input->post('each_sale') : NULL,
                'sa_point' 							=> $this->input->post('sa_point') ? $this->input->post('sa_point') : NULL,
                'sac' 								=> $this->input->post('sac'),
                'qty_decimals' 						=> $this->input->post('qty_decimals'),
                'display_symbol' 					=> $this->input->post('display_symbol'),
				'symbol' 							=> $this->input->post('symbol'),
				'auto_print' 						=> trim($this->input->post('auto_print')),
				'alert_day' 						=> trim($this->input->post('alert_day')),
				'purchase_serial' 					=> $this->input->post('purchase_serial'),
				'boms_method' 						=> $this->input->post('boms_method'),
				'separate_code' 					=> $this->input->post('separate_code'),
				'show_code' 						=> $this->input->post('show_code'),
				'bill_to' 							=> $this->input->post('bill_to'),
				'show_po' 							=> $this->input->post('show_po'),
				'credit_limit' 						=> $this->input->post('credit_limit'),
				'show_company_code' 				=> $this->input->post('show_com_code'),
				'purchase_order_prefix' 			=> $this->input->post('purchase_order_prefix'),
				'acc_cate_separate' 				=> $this->input->post('acc_cate_sep'),
				'purchase_request_prefix' 			=> $this->input->post('purchase_request_prefix'),
				'stock_count_prefix' 				=> $this->input->post('stock_count_prefix'),
				'stock_deduction' 					=> $this->input->post('stock_deduction'),
				'delivery' 							=> $this->input->post('delivery'),
				'authorization' 					=> $this->input->post('authorization'),
				'shipping' 							=> $this->input->post('shipping'),
                'separate_ref'                      => $this->input->post('separate_ref'),
                'journal_prefix'                    => $this->input->post('journal_prefix'),
                'adjustment_prefix'                 => $this->input->post('adjustment_prefix'),
				'system_management' 				=> $this->input->post('system_management'),
                'table_item'                        => $this->input->post('table_item'),
                'project_code_prefix'               => $this->input->post('project_code_prefix'),
                'customer_code_prefix'              => $this->input->post('customer_code_prefix'),
                'supplier_code_prefix'              => $this->input->post('supplier_code_prefix'),
                'employee_code_prefix'              => $this->input->post('employee_code_prefix'),
				'allow_change_date' 				=> $this->input->post('allow_change_date'),
				'increase_stock_import' 			=> $this->input->post('increase_stock_import'),
				'member_card_expiry' 				=> $this->input->post('member_card_expiry'),
				'tax_calculate' 					=> $this->input->post('tax_calculate'),
				'business_type'						=> $this->input->post('business_type')
            );
            //$this->erp->print_arrays($data);
            if ($this->input->post('smtp_pass')) {
                $data['smtp_pass'] = $this->encrypt->encode($this->input->post('smtp_pass'));
            }
        }

        if ($this->form_validation->run() == true && $this->settings_model->updateSetting($data)) {

			if ($this->write_index($data['timezone']) == false) {
                $this->session->set_flashdata('error', lang('setting_updated_timezone_failed'));
                redirect('system_settings');

            }

            $this->session->set_flashdata('message', lang('setting_updated'));
            redirect("system_settings");
        } else {

            $this->data['error'] 			= validation_errors();
            $this->data['billers'] 			= $this->site->getAllCompanies('biller');
            $this->data['settings'] 		= $this->settings_model->getSettings();
            //$this->erp->print_arrays($this->settings_model->getSettings());
            $this->data['currencies'] 		= $this->settings_model->getAllCurrencies();
            $this->data['date_formats'] 	= $this->settings_model->getDateFormats();
            $this->data['tax_rates'] 		= $this->settings_model->getAllTaxRates();
            $this->data['customer_groups'] 	= $this->settings_model->getAllCustomerGroups();
            $this->data['warehouses'] 		= $this->settings_model->getAllWarehouses();
            $this->data['smtp_pass'] 		= $this->encrypt->decode($this->data['settings']->smtp_pass);
            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('system_settings')));
            $meta = array('page_title' => lang('system_settings'), 'bc' => $bc);
            $this->page_construct('settings/index', $meta, $this->data);
        }
    }
	
	function payment_term()
    {
        $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('system_settings'), 'page' => lang('system_settings')), array('link' => '#', 'page' => lang('payment_term')));
        $meta = array('page_title' => lang('payment_term'), 'bc' => $bc);
        $this->page_construct('settings/payment_term', $meta, $this->data);
    }
	
	function getPaymentTerm(){
        $this->load->library('datatables');
        $this->datatables
            ->select("id, description, due_day,due_day_for_discount, discount")
            ->from("payment_term")
            ->order_by('id', 'asc')
            ->add_column("Actions", "<center><a href='" . site_url('system_settings/edit_payment_term/$1') . "' class='tip' title='" . lang("edit_payment_term") . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-edit\"></i></a> <a href='#' class='tip po' title='<b>" . lang("delete_payment_term") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('system_settings/delete_payment_term/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></center>", "id");
        //->unset_column('id');

        echo $this->datatables->generate();
    }
	
	public function edit_payment_term($id)
	{
        $this->form_validation->set_rules('description', lang("description"), 'trim|required');
        $config = array(                    
                    array(
                        'field' => 'due_day',
                        'label' => lang("due_day"),
                        'rules' => 'required',
                        ),
                    array(
                        'field'=>'due_day_for_discount',
                        'label'=>lang('due_day_for_discount'),
                        'rules'=>'numeric'
                        ),
                    );
        $this->form_validation->set_rules($config);
        if ($this->form_validation->run() == true) {
            $data = array(
                'description'  => $this->input->post('description'),
                'due_day' => $this->input->post('due_day'),
                'due_day_for_discount'=> $this->input->post('due_day_for_discount'),
                'discount'     => $this->input->post('discount') ? $this->input->post('discount'): '0'
            );
            //$this->erp->print_arrays($data);
        } elseif ($this->input->post('add_payment_term')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }

        if ($this->form_validation->run() == true && $this->settings_model->updatePaymentTerm($id, $data)) {
            $this->session->set_flashdata('message', lang("payment_term_updated"));
            redirect($_SERVER["HTTP_REFERER"]);
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['id'] = $id;
            $this->data['modal_js'] = $this->site->modal_js();
            $this->data['page_title'] = lang("new_payment_term");
            $this->data['data'] = $this->settings_model->getPaymentTermById($id);
            $this->load->view($this->theme . 'settings/edit_payment', $this->data);
        }
    }
	
	public function add_payment_term()
	{
        $this->form_validation->set_rules('description', lang("description"), 'trim|required');
        $config = array(                    
                    array(
                        'field' => 'due_day',
                        'label' => lang("due_day"),
                        'rules' => 'numeric',
                        ),
                    array(
                        'field'=>'due_day_for_discount',
                        'label'=>lang('due_day_for_discount'),
                        'rules'=>'numeric'
                        ),
                    );
        $this->form_validation->set_rules($config);
        if ($this->form_validation->run() == true) {
            $data = array(
                'description'  => $this->input->post('description'),
                'due_day' => $this->input->post('due_day'),
                'due_day_for_discount'=> $this->input->post('due_day_for_discount'),
                'discount'     => $this->input->post('discount') ? $this->input->post('discount'): '0'
            );
            //$this->erp->print_arrays($data);
        } elseif ($this->input->post('add_payment_term')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }

        if ($this->form_validation->run() == true && $this->settings_model->addPaymentTerm($data)) {
            $this->session->set_flashdata('message', lang("payment_term_added"));
            redirect($_SERVER["HTTP_REFERER"]);
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['modal_js'] = $this->site->modal_js();
            $this->data['page_title'] = lang("new_payment_term");
            $this->load->view($this->theme . 'settings/add_payment', $this->data);
        }
    }
	
	public function delete_payment_term($id)
	{
       
            //$this->session->set_flashdata('message', lang("payment_term_deleted");
            if($this->db->delete('payment_term', array('id' => $id))){
                echo "Payment term deleted";
            }
    }
	
	function  payment_term_action()
    {
        $this->form_validation->set_rules('form_action', lang("form_action"), 'required');
         if ($this->form_validation->run() == true) {
            if (!empty($_POST['val'])) {
                if ($this->input->post('form_action') == 'delete') {
                    foreach ($_POST['val'] as $id) {
                        $this->settings_model->deletepayment_term($id);
                    }
                    $this->session->set_flashdata('message', lang("payment_term deleted"));
                    redirect($_SERVER["HTTP_REFERER"]);
                }

                if ($this->input->post('form_action') == 'export_excel' || $this->input->post('form_action') == 'export_pdf') {

                    $this->load->library('excel');
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle(lang('Payment Term'));
                    $this->excel->getActiveSheet()->SetCellValue('A1', lang('Description'));
                    $this->excel->getActiveSheet()->SetCellValue('B1', lang('Due Days'));
                    $this->excel->getActiveSheet()->SetCellValue('C1', lang('Due Days for Discount '));
                    $this->excel->getActiveSheet()->SetCellValue('D1', lang('Discount'));

                    $row = 2;
                    foreach ($_POST['val'] as $id) {
                        $payment_term = $this->site->getPamentTermbyID($id);
                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $payment_term->description);
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $payment_term->due_day);
                        $this->excel->getActiveSheet()->SetCellValue('C' . $row, $payment_term->due_day_for_discount);
                        $this->excel->getActiveSheet()->SetCellValue('D' . $row, $payment_term->discount);
                        $row++;
                    }

                    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
                    $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $filename = 'payment_term_' . date('Y_m_d_H_i_s');
                    if ($this->input->post('form_action') == 'export_pdf') {
                        $styleArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
                        $this->excel->getDefaultStyle()->applyFromArray($styleArray);
                        $this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
                        require_once(APPPATH . "third_party" . DIRECTORY_SEPARATOR . "MPDF" . DIRECTORY_SEPARATOR . "mpdf.php");
                        $rendererName = PHPExcel_Settings::PDF_RENDERER_MPDF;
                        $rendererLibrary = 'MPDF';
                        $rendererLibraryPath = APPPATH . 'third_party' . DIRECTORY_SEPARATOR . $rendererLibrary;
                        if (!PHPExcel_Settings::setPdfRenderer($rendererName, $rendererLibraryPath)) {
                            die('Please set the $rendererName: ' . $rendererName . ' and $rendererLibraryPath: ' . $rendererLibraryPath . ' values' .
                                PHP_EOL . ' as appropriate for your directory structure');
                        }

                        header('Content-Type: application/pdf');
                        header('Content-Disposition: attachment;filename="' . $filename . '.pdf"');
                        header('Cache-Control: max-age=0');

                        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'PDF');
                        return $objWriter->save('php://output');
                    }
                    if ($this->input->post('form_action') == 'export_excel') {
                        header('Content-Type: application/vnd.ms-excel');
                        header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
                        header('Cache-Control: max-age=0');

                        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
                        return $objWriter->save('php://output');
                    }

                    redirect($_SERVER["HTTP_REFERER"]);
                }
            }else {
                $this->session->set_flashdata('error', lang("no_tax_rate_selected"));
                redirect($_SERVER["HTTP_REFERER"]);
            }
        }else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }

    function paypal()
    {

        $this->form_validation->set_rules('active', $this->lang->line('activate'), 'trim');
        $this->form_validation->set_rules('account_email', $this->lang->line('paypal_account_email'), 'trim|valid_email');
        if ($this->input->post('active')) {
            $this->form_validation->set_rules('account_email', $this->lang->line('paypal_account_email'), 'required');
        }
        $this->form_validation->set_rules('fixed_charges', $this->lang->line('fixed_charges'), 'trim');
        $this->form_validation->set_rules('extra_charges_my', $this->lang->line('extra_charges_my'), 'trim');
        $this->form_validation->set_rules('extra_charges_other', $this->lang->line('extra_charges_others'), 'trim');

        if ($this->form_validation->run() == true) {

            $data = array('active' => $this->input->post('active'),
                'account_email' => $this->input->post('account_email'),
                'fixed_charges' => $this->input->post('fixed_charges'),
                'extra_charges_my' => $this->input->post('extra_charges_my'),
                'extra_charges_other' => $this->input->post('extra_charges_other')
            );
        }

        if ($this->form_validation->run() == true && $this->settings_model->updatePaypal($data)) {
            $this->session->set_flashdata('message', $this->lang->line('paypal_setting_updated'));
            redirect("system_settings/paypal");
        } else {

            $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');

            $this->data['paypal'] = $this->settings_model->getPaypalSettings();

            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('system_settings'), 'page' => lang('system_settings')), array('link' => '#', 'page' => lang('paypal_settings')));
            $meta = array('page_title' => lang('paypal_settings'), 'bc' => $bc);
            $this->page_construct('settings/paypal', $meta, $this->data);
        }
    }

    function skrill()
    {

        $this->form_validation->set_rules('active', $this->lang->line('activate'), 'trim');
        $this->form_validation->set_rules('account_email', $this->lang->line('paypal_account_email'), 'trim|valid_email');
        if ($this->input->post('active')) {
            $this->form_validation->set_rules('account_email', $this->lang->line('paypal_account_email'), 'required');
        }
        $this->form_validation->set_rules('fixed_charges', $this->lang->line('fixed_charges'), 'trim');
        $this->form_validation->set_rules('extra_charges_my', $this->lang->line('extra_charges_my'), 'trim');
        $this->form_validation->set_rules('extra_charges_other', $this->lang->line('extra_charges_others'), 'trim');

        if ($this->form_validation->run() == true) {

            $data = array('active' => $this->input->post('active'),
                'account_email' => $this->input->post('account_email'),
                'fixed_charges' => $this->input->post('fixed_charges'),
                'extra_charges_my' => $this->input->post('extra_charges_my'),
                'extra_charges_other' => $this->input->post('extra_charges_other')
            );
        }

        if ($this->form_validation->run() == true && $this->settings_model->updateSkrill($data)) {
            $this->session->set_flashdata('message', $this->lang->line('skrill_setting_updated'));
            redirect("system_settings/skrill");
        } else {

            $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');

            $this->data['skrill'] = $this->settings_model->getSkrillSettings();

            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('system_settings'), 'page' => lang('system_settings')), array('link' => '#', 'page' => lang('skrill_settings')));
            $meta = array('page_title' => lang('skrill_settings'), 'bc' => $bc);
            $this->page_construct('settings/skrill', $meta, $this->data);
        }
    }
	
	function group_area()
    {
        $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('system_settings'), 'page' => lang('system_settings')), array('link' => '#', 'page' => lang('group_area')));
        $meta = array('page_title' => lang('group_area'), 'bc' => $bc);
        $this->page_construct('settings/group_areas', $meta, $this->data);
    }
	
	function getGroupArea()
    {
        $this->load->library('datatables');
        $this->datatables
            ->select("areas_g_code, areas_g_code as id, areas_group")
            ->from("erp_group_areas")
            ->add_column("Actions", "<div class=\"text-center\">  <a href='" . site_url('system_settings/edit_group_area/$1') . "' class='tip' title='" . lang("edit_group_area") . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-edit\"></i></a> <a href='#' class='tip po' title='<b>" . lang("delete_group_area") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('system_settings/delete_group_area/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></div>", "areas_g_code");
        echo $this->datatables->generate();
    }
	
	function add_group_area()
    {

        $this->form_validation->set_rules('areas_group', lang("group_area"), 'trim|required');
		
        if ($this->form_validation->run() == true) {
            $data = array('areas_group' => $this->input->post('areas_group'));
        } elseif ($this->input->post('areas_group')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect("system_settings/group_area");
        }

        if ($this->form_validation->run() == true && $this->settings_model->addGroupArea($data)) {
			$this->session->set_flashdata('message', lang("group_area_added"));
            redirect("system_settings/group_area");
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/add_group_area', $this->data);
        }
    }
	
	function delete_group_area($id = NULL)
    {
        if ($this->settings_model->deleteGroupArea($id)) {
            echo lang("group_area_deleted");
        }
    }
	
	function edit_group_area($id = NULL)
    {

        $this->form_validation->set_rules('areas_group', lang("group_area"), 'trim|required');
        $pg_details = $this->settings_model->getGroupAreaBy($id);
       if ($this->input->post('areas_group') != $pg_details->areas_group) {
            $this->form_validation->set_rules('areas_group', lang("group_area"), 'trim|required');
        } 

        if ($this->form_validation->run() == true) {
            $data = array('areas_group' => $this->input->post('areas_group'));
        } elseif ($this->input->post('areas_group')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect("system_settings/group_area");
        }

        if ($this->form_validation->run() == true && $this->settings_model->updateGroupArea($id, $data)) {
            $this->session->set_flashdata('message', lang("group_area_updated"));
            redirect("system_settings/group_area");
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

            $this->data['group_area'] = $pg_details;
            $this->data['id'] = $id;
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/edit_group_area', $this->data);
        }
    }
    
	function change_logo()
    {
        if (DEMO) {
            $this->session->set_flashdata('warning', lang('disabled_in_demo'));
            redirect($_SERVER["HTTP_REFERER"]);
        }
        $this->load->helper('security');
        $this->form_validation->set_rules('site_logo', lang("site_logo"), 'xss_clean');
        $this->form_validation->set_rules('biller_logo', lang("biller_logo"), 'xss_clean');
        if ($this->form_validation->run() == true) {

            if ($_FILES['site_logo']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->upload_path . 'logos/';
                $config['allowed_types'] = $this->image_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['max_width'] = 300;
                $config['max_height'] = 80;
                $config['overwrite'] = FALSE;
                $config['max_filename'] = 25;
                //$config['encrypt_name'] = TRUE;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('site_logo')) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;

                $this->db->update('settings', array('logo2' => $photo), array('setting_id' => 1));

                $this->session->set_flashdata('message', lang('logo_uploaded'));
                redirect($_SERVER["HTTP_REFERER"]);
            }

            if ($_FILES['biller_logo']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->upload_path . 'logos/';
                $config['allowed_types'] = $this->image_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['max_width'] = 300;
                $config['max_height'] = 80;
                $config['overwrite'] = FALSE;
                $config['max_filename'] = 25;
                //$config['encrypt_name'] = TRUE;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('biller_logo')) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;

                $this->session->set_flashdata('message', lang('logo_uploaded'));
                redirect($_SERVER["HTTP_REFERER"]);

            }

            $this->session->set_flashdata('error', lang('attempt_failed'));
            redirect($_SERVER["HTTP_REFERER"]);
            die();
        } elseif ($this->input->post('upload_logo')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        } else {
            $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/change_logo', $this->data);
        }
    }
    
	public function write_index($timezone)
    {

        $template_path = './assets/config_dumps/index.php';
        $output_path = SELF;
        $index_file = file_get_contents($template_path);
        $new = str_replace("%TIMEZONE%", $timezone, $index_file);
        $handle = fopen($output_path, 'w+');
        @chmod($output_path, 0777);

        if (is_writable($output_path)) {
            if (fwrite($handle, $new)) {
                @chmod($output_path, 0644);
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function updates()
    {
        if (DEMO) {
            $this->session->set_flashdata('warning', lang('disabled_in_demo'));
            redirect($_SERVER["HTTP_REFERER"]);
        }
        if (!$this->Owner) {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect("welcome");
        }
        $this->form_validation->set_rules('purchase_code', lang("purchase_code"), 'required');
        $this->form_validation->set_rules('envato_username', lang("envato_username"), 'required');
        if ($this->form_validation->run() == true) {
            $this->db->update('settings', array('purchase_code' => $this->input->post('purchase_code', TRUE), 'envato_username' => $this->input->post('envato_username', TRUE)), array('setting_id' => 1));
            redirect('system_settings/updates');
        } else {
            $fields = array('version' => $this->Settings->version, 'code' => $this->Settings->purchase_code, 'username' => $this->Settings->envato_username, 'site' => base_url());
            $this->load->helper('update');
            $protocol = is_https() ? 'https://' : 'http://';
            $updates = get_remote_contents($protocol.'cloudnet.com.kh/api/v1/update/', $fields);
            $this->data['updates'] = json_decode($updates);
            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('updates')));
            $meta = array('page_title' => lang('updates'), 'bc' => $bc);
            $this->page_construct('settings/updates', $meta, $this->data);
        }
    }

    function install_update($file, $m_version, $version)
    {
        if (DEMO) {
            $this->session->set_flashdata('warning', lang('disabled_in_demo'));
            redirect($_SERVER["HTTP_REFERER"]);
        }
        if (!$this->Owner) {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect("welcome");
        }
        $this->load->helper('update');
        save_remote_file($file . '.zip');
        $this->erp->unzip('./files/updates/' . $file . '.zip');
        if ($m_version) {
            $this->load->library('migration');
            if (!$this->migration->latest()) {
                $this->session->set_flashdata('error', $this->migration->error_string());
                redirect("system_settings/updates");
            }
        }
        $this->db->update('settings', array('version' => $version, 'update' => 0), array('setting_id' => 1));
        unlink('./files/updates/' . $file . '.zip');
        $this->session->set_flashdata('success', lang('update_done'));
        redirect("system_settings/updates");
    }

    function backups()
    {
        if (DEMO) {
            $this->session->set_flashdata('warning', lang('disabled_in_demo'));
            redirect($_SERVER["HTTP_REFERER"]);
        }
        if (!$this->Owner) {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect("welcome");
        }
        $this->data['files'] = glob('./files/backups/*.zip', GLOB_BRACE);
        $this->data['dbs'] = glob('./files/backups/*.txt', GLOB_BRACE);
        krsort($this->data['files']); krsort($this->data['dbs']);
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('backups')));
        $meta = array('page_title' => lang('backups'), 'bc' => $bc);
        $this->page_construct('settings/backups', $meta, $this->data);
    }

    function backup_database()
    {
        if (DEMO) {
            $this->session->set_flashdata('warning', lang('disabled_in_demo'));
            redirect($_SERVER["HTTP_REFERER"]);
        }
        if (!$this->Owner) {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect("welcome");
        }
        $this->load->dbutil();
        $prefs = array(
            'format' => 'txt',
            'filename' => 'erp_db_backup.sql'
        );
        $back = $this->dbutil->backup($prefs);
        $backup =& $back;
        $db_name = 'db-backup-on-' . date("Y-m-d-H-i-s") . '.txt';
        $save = './files/backups/' . $db_name;
        $this->load->helper('file');
        write_file($save, $backup);
        $this->session->set_flashdata('messgae', lang('db_saved'));
        redirect("system_settings/backups");
    }

    function backup_files()
    {
        if (DEMO) {
            $this->session->set_flashdata('warning', lang('disabled_in_demo'));
            redirect($_SERVER["HTTP_REFERER"]);
        }
        if (!$this->Owner) {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect("welcome");
        }
        $name = 'file-backup-' . date("Y-m-d-H-i-s");
        $this->erp->zip("./", './files/backups/', $name);
        $this->session->set_flashdata('messgae', lang('backup_saved'));
        redirect("system_settings/backups");
        exit();
    }

    function restore_database($dbfile)
    {
        if (DEMO) {
            $this->session->set_flashdata('warning', lang('disabled_in_demo'));
            redirect($_SERVER["HTTP_REFERER"]);
        }
        if (!$this->Owner) {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect("welcome");
        }

		$backup = read_file('./files/backups/' . $dbfile . '.txt');
		$trigger = file_get_contents('./files/iclouderp_trigger.sql');

		if($this->executeQueryFile($backup)){
			$this->db->conn_id->multi_query($trigger);
			$this->db->conn_id->close();
			echo json_encode(TRUE);
		}else{
			echo json_encode(FALSE);
		}
		
		
		
		/*
		$backup_trigger = file_get_contents('./files/iclouderp_trigger.sql');
		if(!$backup_trigger){
			$this->session->set_flashdata('warning', 'Trigger not found!');
            redirect($_SERVER["HTTP_REFERER"]);
		} else {
			$this->db->query($backup_trigger);
		}
		*/

		/*
        $file = file_get_contents('./files/backups/' . $dbfile . '.txt');
        $this->db->conn_id->multi_query($file);        
        $this->db->conn_id->close();
        
        $trigger = file_get_contents('./files/iclouderp_trigger.txt');
        $this->db->conn_id->multi_query($trigger);
        $this->db->conn_id->close();
		*/
        //redirect('logout/db');
    }
	
	function executeQueryFile($sql_file) 
	{
		if (!$sql_file) {
		  return false;
		}
		$sql_clean = '';
		foreach (explode("\n", $sql_file) as $line){
			if(isset($line[0]) && $line[0] != "#"){
				$sql_clean .= $line."\n";
			}
		}
		//echo $sql_clean;
		foreach (explode(";\n", $sql_clean) as $sql){
			$sql = trim($sql);
			//echo  $sql.'<br/>============<br/>';
			if($sql)
			{
				$this->db->query($sql);
			}
		}
		return true;
	}

    function download_database($dbfile)
    {
        if (DEMO) {
            $this->session->set_flashdata('warning', lang('disabled_in_demo'));
            redirect($_SERVER["HTTP_REFERER"]);
        }
        if (!$this->Owner) {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect("welcome");
        }
        $this->load->library('zip');
        $this->zip->read_file('./files/backups/' . $dbfile . '.txt');
        $name = $dbfile . '.zip';
        $this->zip->download($name);
        exit();
    }

    function download_backup($zipfile)
    {
        if (DEMO) {
            $this->session->set_flashdata('warning', lang('disabled_in_demo'));
            redirect($_SERVER["HTTP_REFERER"]);
        }
        if (!$this->Owner) {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect("welcome");
        }
        $this->load->helper('download');
        force_download('./files/backups/' . $zipfile . '.zip', NULL);
        exit();
    }

    function restore_backup($zipfile)
    {
        if (DEMO) {
            $this->session->set_flashdata('warning', lang('disabled_in_demo'));
            redirect($_SERVER["HTTP_REFERER"]);
        }
        if (!$this->Owner) {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect("welcome");
        }
        $file = './files/backups/' . $zipfile . '.zip';
        $this->erp->unzip($file, './');
        $this->session->set_flashdata('success', lang('files_restored'));
        redirect("system_settings/backups");
        exit();
    }

    function delete_database($dbfile)
    {
        if (DEMO) {
            $this->session->set_flashdata('warning', lang('disabled_in_demo'));
            redirect($_SERVER["HTTP_REFERER"]);
        }
        if (!$this->Owner) {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect("welcome");
        }
        unlink('./files/backups/' . $dbfile . '.txt');
        $this->session->set_flashdata('messgae', lang('db_deleted'));
        redirect("system_settings/backups");
    }

    function delete_backup($zipfile)
    {
        if (DEMO) {
            $this->session->set_flashdata('warning', lang('disabled_in_demo'));
            redirect($_SERVER["HTTP_REFERER"]);
        }
        if (!$this->Owner) {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect("welcome");
        }
        unlink('./files/backups/' . $zipfile . '.zip');
        $this->session->set_flashdata('messgae', lang('backup_deleted'));
        redirect("system_settings/backups");
    }

    function email_templates($template = "credentials")
    {

        $this->form_validation->set_rules('mail_body', lang('mail_message'), 'trim|required');
        $this->load->helper('file');
        $temp_path = is_dir('./themes/' . $this->theme . 'email_templates/');
        $theme = $temp_path ? $this->theme : 'default';
        if ($this->form_validation->run() == true) {
            $data = $_POST["mail_body"];
            if (write_file('./themes/' . $this->theme . 'email_templates/' . $template . '.html', $data)) {
                $this->session->set_flashdata('message', lang('message_successfully_saved'));
                redirect('system_settings/email_templates#' . $template);
            } else {
                $this->session->set_flashdata('error', lang('failed_to_save_message'));
                redirect('system_settings/email_templates#' . $template);
            }
        } else {

            $this->data['credentials'] = file_get_contents('./themes/' . $this->theme . 'email_templates/credentials.html');
            $this->data['sale'] = file_get_contents('./themes/' . $this->theme . 'email_templates/sale.html');
            $this->data['quote'] = file_get_contents('./themes/' . $this->theme . 'email_templates/quote.html');
            $this->data['purchase'] = file_get_contents('./themes/' . $this->theme . 'email_templates/purchase.html');
            $this->data['transfer'] = file_get_contents('./themes/' . $this->theme . 'email_templates/transfer.html');
            $this->data['payment'] = file_get_contents('./themes/' . $this->theme . 'email_templates/payment.html');
            $this->data['forgot_password'] = file_get_contents('./themes/' . $this->theme . 'email_templates/forgot_password.html');
            $this->data['activate_email'] = file_get_contents('./themes/' . $this->theme . 'email_templates/activate_email.html');
            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('system_settings'), 'page' => lang('system_settings')), array('link' => '#', 'page' => lang('email_templates')));
            $meta = array('page_title' => lang('email_templates'), 'bc' => $bc);
            $this->page_construct('settings/email_templates', $meta, $this->data);
        }
    }

    function create_group()
    {
        $this->form_validation->set_rules('group_name', lang('group_name'), 'required|trim');
        //$this->form_validation->set_rules('description', lang('group_description'), 'xss_clean');

        if ($this->form_validation->run() == TRUE) {
            $data = array('name' => strtolower($this->input->post('group_name')), 'description' => $this->input->post('description'), 'type' => $this->input->post('type'));
            $new_group_id = $this->settings_model->addGroup($data);
            if ($new_group_id) {
                $this->session->set_flashdata('message', lang('group_added'));
                // redirect("system_settings/permissions/" . $new_group_id);
                redirect("system_settings/user_groups/" . $new_group_id);
            }
        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

            $this->data['group_name'] = array(
                'name' => 'group_name',
                'id' => 'group_name',
                'type' => 'text',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('group_name'),
            );
            $this->data['description'] = array(
                'name' => 'description',
                'id' => 'description',
                'type' => 'text',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('description'),
            );
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/create_group', $this->data);
        }
    }

    function edit_group($id)
    {

        if (!$id || empty($id)) {
            redirect('system_settings/user_groups');
        }

        $group = $this->settings_model->getGroupByID($id);

        $this->form_validation->set_rules('group_name', lang('group_name'), 'required|alpha_dash');

        if ($this->form_validation->run() === TRUE) {
            $data = array('name' => strtolower($this->input->post('group_name')), 'description' => $this->input->post('description'));
            $group_update = $this->settings_model->updateGroup($id, $data);

            if ($group_update) {
                $this->session->set_flashdata('message', lang('group_udpated'));
            } else {
                $this->session->set_flashdata('error', lang('attempt_failed'));
            }
            redirect("system_settings/user_groups");
        } else {


            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
			if($this->data['error'] ){
				$this->session->set_flashdata('error', validation_errors());
				  redirect("system_settings/user_groups");
			}
            $this->data['group'] = $group;

            $this->data['group_name'] = array(
                'name' => 'group_name',
                'id' => 'group_name',
                'type' => 'text',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('group_name', $group->name),
            );
            $this->data['group_description'] = array(
                'name' => 'group_description',
                'id' => 'group_description',
                'type' => 'text',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('group_description', $group->description),
            );
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/edit_group', $this->data);
        }
    }

    function permissions($id = NULL)
    {
        $this->form_validation->set_rules('group', lang("group"), 'is_natural_no_zero');
        if ($this->form_validation->run() == true) {
			
            $data = array(
                'products-index' => $this->input->post('products-index'),
                'products-edit' => $this->input->post('products-edit'),
                'products-add' => $this->input->post('products-add'),
                'products-delete' => $this->input->post('products-delete'),
                'products-cost' => $this->input->post('products-cost'),
                'products-price' => $this->input->post('products-price'),
				'products-import' => $this->input->post('products-import'),
                'products-export' => $this->input->post('products-export'),
				//'products_convert_add' => $this->input->post('products_convert_add'),
				'products-items_convert' => $this->input->post('products-items_convert'),
				'products-print_barcodes' => $this->input->post('products-print_barcodes'),
				'products-adjustments' => $this->input->post('products-adjustments'),
				'products-using_stock' => $this->input->post('products-using_stock'),
				'products-list_using_stock' => $this->input->post('products-list_using_stock'),
				'product_import' => $this->input->post('product_import'),
				'products-import_quantity' => $this->input->post('products-import_quantity'),
				'products-import_price_cost' => $this->input->post('products-import_price_cost'),
				'products-return_list' => $this->input->post('products-return_list'),
                'products-sync_quantity' => $this->input->post('products-sync_quantity'),
                'products-count_stocks' => $this->input->post('products-count_stocks'),
				'product_report-customers' => $this->input->post('product_report-customers'),
				'product_report-product_value' => $this->input->post('product_report-product_value'),
                'reports-product_top_sale' => $this->input->post('reports-product_top_sale'),

				'sale_order-index' => $this->input->post('sale_order-index'),
                'sale_order-add' => $this->input->post('sale_order-add'),
				'sale_order-edit' => $this->input->post('sale_order-edit'),
                'sale_order-delete' => $this->input->post('sale_order-delete'),
				'sale_order-import' => $this->input->post('sale_order-import'),
				'sale_order-export' => $this->input->post('sale_order-export'),
				'sale_order-authorize' => $this->input->post('sale_order-authorize'),
				'sale_order-price' => $this->input->post('sale_order-price'),
                'sale_order-combine_pdf' => $this->input->post('sale_order-combine_pdf'),
				'sale_order-deposit' => $this->input->post('sale_order-deposit'),
				
				'sales-index' => $this->input->post('sales-index'),
                'sales-add' => $this->input->post('sales-add'),
				'sales-edit' => $this->input->post('sales-edit'),
                'sales-delete' => $this->input->post('sales-delete'),
				'sales-import' => $this->input->post('sales-import'),
				'sales-export' => $this->input->post('sales-export'),
                'sales-email' => $this->input->post('sales-email'),
				'sales-payments' => $this->input->post('sales-payments'),
				'sales-return_sales' => $this->input->post('sales-return_sales'),
				'sales-loan' => $this->input->post('sales-loan'),
				'sales-discount' => $this->input->post('sales-discount'),
				'sales-price' => $this->input->post('sales-price'),
				'sales-opening_ar' => $this->input->post('sales-opening_ar'),
				'sales-combine_pdf' => $this->input->post('sales-combine_pdf'),
				
                'sales-pdf' => $this->input->post('sales-pdf'),
                'sales-deliveries' => $this->input->post('sales-deliveries'),
                'sales-edit_delivery' => $this->input->post('sales-edit_delivery'),
                'sales-add_delivery' => $this->input->post('sales-add_delivery'),
                'sales-delete_delivery' => $this->input->post('sales-delete_delivery'),
                'sales-email_delivery' => $this->input->post('sales-email_delivery'),
                'sales-pdf_delivery' => $this->input->post('sales-pdf_delivery'),
				'sales-import_delivery' => $this->input->post('sales-import_delivery'),
                'sales-export_delivery' => $this->input->post('sales-export_delivery'),
                'sales-gift_cards' => $this->input->post('sales-gift_cards'),
                'sales-edit_gift_card' => $this->input->post('sales-edit_gift_card'),
                'sales-add_gift_card' => $this->input->post('sales-add_gift_card'),
                'sales-delete_gift_card' => $this->input->post('sales-delete_gift_card'),
				'sales-import_gift_card' => $this->input->post('sales-import_gift_card'),
                'sales-export_gift_card' => $this->input->post('sales-export_gift_card'),
                'sales-combine_delivery' => $this->input->post('sales-combine_delivery'),
                
                'customers-index' => $this->input->post('customers-index'),
                'customers-edit' => $this->input->post('customers-edit'),
                'customers-add' => $this->input->post('customers-add'),
                'customers-delete' => $this->input->post('customers-delete'),
				'customers-import' => $this->input->post('customers-import'),
                'customers-export' => $this->input->post('customers-export'),
                'suppliers-index' => $this->input->post('suppliers-index'),
                'suppliers-edit' => $this->input->post('suppliers-edit'),
                'suppliers-add' => $this->input->post('suppliers-add'),
                'suppliers-delete' => $this->input->post('suppliers-delete'),
				'suppliers-import' => $this->input->post('suppliers-import'),
                'suppliers-export' => $this->input->post('suppliers-export'),
				
                'users-index' => $this->input->post('users-index'),
                'users-edit' => $this->input->post('users-edit'),
                'users-add' => $this->input->post('users-add'),
                'users-delete' => $this->input->post('users-delete'),
				'users-import' => $this->input->post('users-import'),
                'users-export' => $this->input->post('users-export'),
				
				'drivers-index' => $this->input->post('drivers-index'),
                'drivers-edit' => $this->input->post('drivers-edit'),
                'drivers-add' => $this->input->post('drivers-add'),
                'drivers-delete' => $this->input->post('drivers-delete'),
				'drivers-import' => $this->input->post('drivers-import'),
                'drivers-export' => $this->input->post('drivers-export'),
				
				'employees-index' => $this->input->post('employees-index'),
                'employees-edit' => $this->input->post('employees-edit'),
                'employees-add' => $this->input->post('employees-add'),
                'employees-delete' => $this->input->post('employees-delete'),
				'employees-import' => $this->input->post('employees-import'),
                'employees-export' => $this->input->post('employees-export'),
				
				'projects-index' => $this->input->post('projects-index'),
                'projects-edit' => $this->input->post('projects-edit'),
                'projects-add' => $this->input->post('projects-add'),
                'projects-delete' => $this->input->post('projects-delete'),
				'projects-import' => $this->input->post('projects-import'),
                'projects-export' => $this->input->post('projects-export'),
				
                'quotes-index' => $this->input->post('quotes-index'),
                'quotes-edit' => $this->input->post('quotes-edit'),
                'quotes-add' => $this->input->post('quotes-add'),
                'quotes-delete' => $this->input->post('quotes-delete'),
                'quotes-email' => $this->input->post('quotes-email'),
                'quotes-pdf' => $this->input->post('quotes-pdf'),
				'quotes-import' => $this->input->post('quotes-import'),
                'quotes-export' => $this->input->post('quotes-export'),
                'quotes-authorize' => $this->input->post('quotes-authorize'),
				'quotes-conbine_pdf' => $this->input->post('quotes-conbine_pdf'),
				
                'purchases-index' => $this->input->post('purchases-index'),
                'purchases-edit' => $this->input->post('purchases-edit'),
                'purchases-add' => $this->input->post('purchases-add'),
                'purchases-delete' => $this->input->post('purchases-delete'),
                'purchases-email' => $this->input->post('purchases-email'),
                'purchases-pdf' => $this->input->post('purchases-pdf'),
				'purchases-import' => $this->input->post('purchases-import'),
                'purchases-export' => $this->input->post('purchases-export'),
				'purchases-payments' => $this->input->post('purchases-payments'),
                'purchases-expenses' => $this->input->post('purchases-expenses'),				
				'purchases_add-expenses' => $this->input->post('purchases-add_expenses'),
				'purchases-return_list' => $this->input->post('purchases-return_list'),
				// 'purchases-return_add' => $this->input->post('purchases-return_add'),
				'purchases-opening_ap' => $this->input->post('purchases-opening_ap'),
				'purchases-import_expanse' => $this->input->post('purchases-import_expanse'),
				'purchases-cost' => $this->input->post('purchases-cost'),
				'purchases-price' => $this->input->post('purchases-price'),
				'purchases-combine_pdf' => $this->input->post('purchases-combine_pdf'),
				
				'purchases_order-index' => $this->input->post('purchases_order-index'),
                'purchases_order-edit' => $this->input->post('purchases_order-edit'),
                'purchases_order-add' => $this->input->post('purchases_order-add'),
                'purchases_order-delete' => $this->input->post('purchases_order-delete'),
                'purchases_order-email' => $this->input->post('purchases_order-email'),
				'purchases_order-pdf' => $this->input->post('purchases_order-pdf'),
				'purchases_order-import' => $this->input->post('purchases_order-import'),
                'purchases_order-export' => $this->input->post('purchases_order-export'),
				'purchases_order-payments' => $this->input->post('purchases_order-payments'),
                'purchases_order-expenses' => $this->input->post('purchases_order-expenses'),
				'purchase_order-cost' => $this->input->post('purchase_order-cost'),
				'purchase_order-price' => $this->input->post('purchase_order-price'),
				'purchase_order-import_expanse' => $this->input->post('purchase_order-import_expanse'),
                'purchase_order-combine_pdf' => $this->input->post('purchase_order-combine_pdf'),
                'purchases-supplier_balance' => $this->input->post('purchases-supplier_balance'),
				'purchase_order-authorize' => $this->input->post('purchase_order-authorize'),
				
                'transfers-index' => $this->input->post('transfers-index'),
                'transfers-edit' => $this->input->post('transfers-edit'),
                'transfers-add' => $this->input->post('transfers-add'),
                'transfers-delete' => $this->input->post('transfers-delete'),
                'transfers-email' => $this->input->post('transfers-email'),
                'transfers-pdf' => $this->input->post('transfers-pdf'),
				'transfers-import' => $this->input->post('transfers-import'),
                'transfers-export' => $this->input->post('transfers-export'),
                'transfers-combine_pdf' => $this->input->post('transfers-combine_pdf'),
                'reports-quantity_alerts' => $this->input->post('reports-quantity_alerts'),
                'reports-expiry_alerts' => $this->input->post('reports-expiry_alerts'),
                
                'reports-daily_sales' => $this->input->post('reports-daily_sales'),
                'reports-monthly_sales' => $this->input->post('reports-monthly_sales'),
                'reports-payments' => $this->input->post('reports-payments'),
                'reports-customers' => $this->input->post('reports-customers'),
                'reports-suppliers' => $this->input->post('reports-suppliers'),
                
                'bulk_actions' => $this->input->post('bulk_actions'),
                'customers-deposits' => $this->input->post('customers-deposits'),
                'customers-delete_deposit' => $this->input->post('customers-delete_deposit'),
				'reports-profit_loss' => $this->input->post('reports-profit_loss'),
				
				'accounts-index' => $this->input->post('accounts-index'),
				'accounts-add' => $this->input->post('accounts-add'),
				'accounts-edit' => $this->input->post('accounts-edit'),
				'accounts-delete' => $this->input->post('accounts-delete'),
				'accounts-import' => $this->input->post('accounts-import'),
				'accounts-export' => $this->input->post('accounts-export'),
				'account-list_receivable' => $this->input->post('account-list_receivable'),
				'account-list_ar_aging' => $this->input->post('account-list_ar_aging'),
				'account-ar_by_customer' => $this->input->post('account-ar_by_customer'),
				'account-bill_receipt' => $this->input->post('account-bill_receipt'),
				'account-list_payable' => $this->input->post('account-list_payable'),
				'account-list_ap_aging' => $this->input->post('account-list_ap_aging'),
				'account-ap_by_supplier' => $this->input->post('account-ap_by_supplier'),
				'account-bill_payable' => $this->input->post('account-bill_payable'),
				'account-list_ac_head' => $this->input->post('account-list_ac_head'),
				'account-add_ac_head' => $this->input->post('account-add_ac_head'),
				'account-list_customer_deposit' => $this->input->post('account-list_customer_deposit'),
				'account-add_customer_deposit' => $this->input->post('account-add_customer_deposit'),
				'account-list_supplier_deposit' => $this->input->post('account-list_supplier_deposit'),
				'account-add_supplier_deposit' => $this->input->post('account-add_supplier_deposit'),
				'account_setting' => $this->input->post('account_setting'),
				
				'reports-index'=>$this->input->post('reports-index'),
				'room-index' => $this->input->post('room-index'),
				'room-add' => $this->input->post('room-add'),
				'room-edit' => $this->input->post('room-edit'),
				'room-delete' => $this->input->post('room-delete'),
				'room-import' => $this->input->post('room-import'),
				'room-export' => $this->input->post('room-export'),
				'sale-room-index' => $this->input->post('room-index'),
				'sale-room-add' => $this->input->post('room-add'),
				'sale-room-edit' => $this->input->post('room-edit'),
				'sale-room-delete' => $this->input->post('room-delete'),
				'sale-room-import' => $this->input->post('room-import'),
				'sale-room-export' => $this->input->post('room-export'),
				
				'product_report-index' => $this->input->post('product_report-index'),
				'product_report-quantity_alert' => $this->input->post('reports-quantity_alerts'),
				'product_report-product' => $this->input->post('product_report-product'),
				'product_report-warehouse' => $this->input->post('product_report-warehouse'),
				'product_report-in_out' => $this->input->post('product_report-in_out'),
				'product_report-monthly' => $this->input->post('product_report-monthly'),
				'product_report-daily' => $this->input->post('product_report-daily'),
				'product_report-suppliers' => $this->input->post('product_report-suppliers'),
				'product_report-customers' => $this->input->post('product_report-customers'),
				'product_report-categories' => $this->input->post('product_report-categories'),
				'product_report-categories_value' => $this->input->post('product_report-categories_value'),
				'product_report-inventory_valuation_detail' => $this->input->post('product_report-inventory_valuation_detail'),
                'reports-product_top_sale' => $this->input->post('reports-product_top_sale'),

				
				'chart_report-index' => $this->input->post('chart'),
				'chart_report-over_view' => $this->input->post('overview-chart'),
				'chart_report-warehouse_stock' => $this->input->post('reports-warehouse_stock'),
				'chart_report-category_stock' => $this->input->post('category_stock_chart'),
				'chart_report-profit' => $this->input->post('profit_chart'),
				'chart_report-cash_analysis' => $this->input->post('cash_analysis_chart'),
				'chart_report-customize' => $this->input->post('reports-customize'),
				'chart_report-room_table' => $this->input->post('suspend_report'),
				'chart_report-suspend_profit_and_lose' => $this->input->post('reports-suspend_profit_loss'),
				
				'account_report-index' => $this->input->post('account_report-index'),
				'account_report-ledger' => $this->input->post('account_report-ledger'),
				'account_report-trail_balance' => $this->input->post('account_report-trail_balance'),
				'account_report-balance_sheet' => $this->input->post('account_report-balance_sheet'),
				'account_report-income_statement' => $this->input->post('account_report-income_statement'),
				'account_report-cash_book' => $this->input->post('account_report-cash_book'),
				'account_report-payment' => $this->input->post('account_report-payment'),
				'account_report-income_statement_detail' => $this->input->post('account_report-income_statement_detail'),
				
				/*
				'account_report-journal' => $this->input->post('account_report-journal'),
				'account_report-ac_injuiry_report' => $this->input->post('account_report-ac_injuiry_report'),
				'account_report-bsh_by_month' => $this->input->post('account_report-bsh_by_month'),
				'account_report-bsh_by_year' => $this->input->post('account_report-bsh_by_year'),
				'account_report-bsh_by_project' => $this->input->post('account_report-bsh_by_project'),
				'account_report-bsh_by_budget' => $this->input->post('account_report-bsh_by_budget'),
				'account_report-ins_by_month' => $this->input->post('account_report-ins_by_month'),
				'account_report-ins_by_year' => $this->input->post('account_report-ins_by_year'),
				'account_report-ins_by_project' => $this->input->post('account_report-ins_by_project'),
				'account_report-ins_by_budget' => $this->input->post('account_report-ins_by_budget'),
				'account_report-cash_flow_statement' => $this->input->post('account_report-cash_flow_statement'),
				*/
				
				'purchase_report-index' => $this->input->post('reports-purchases'),
				'purchase_report-purchas' => $this->input->post('purchase_report-purchas'),
				'purchase_report-daily' => $this->input->post('reports-daily_purchases'),
				'purchase_report-monthly' => $this->input->post('reports-monthly_purchases'),
				//'purchase_report-supplier' => $this->input->post('reports-suppliers'),
				
                'purchase_request-index' => $this->input->post('purchase_request-index'),
                'purchase_request-add'	=> $this->input->post('purchase_request-add'),
				'purchase_request-edit'	=> $this->input->post('purchase_request-edit'),
				'purchase_request-delete' => $this->input->post('purchase_request-delete'),
				'purchase_request-import' => $this->input->post('purchase_request-import'),
				'purchase_request-export' => $this->input->post('purchase_request-export'),
				'purchase_request-cost' => $this->input->post('purchase_request-cost'),
				'purchase_request-price' => $this->input->post('purchase_request-price'),
				'purchase_request-import_expanse' => $this->input->post('purchase_request-import_expanse'),
                'purchase_request-combine_pdf' => $this->input->post('purchase_request-combine_pdf'),
				'purchase_request-authorize' => $this->input->post('purchase_request-authorize'),
				
				'sale_report-index' => $this->input->post('sale_report-index'),
				'sale_report-register' => $this->input->post('sale_report-register'),
				'sale_report-daily' => $this->input->post('sale_report-daily'),
				'sale_report-monthly' => $this->input->post('sale_report-monthly'),
				'sale_report-disccount' => $this->input->post('sale_report-disccount'),
                'sale_report-by_delivery_person' => $this->input->post('sale_report-by_delivery_person'),
				'sale_report-delivery_detail' => $this->input->post('sale_report-delivery_detail'),
				'sale_report-customer' => $this->input->post('sale_report-customer'),
				'sale_report-customer_transfers' => $this->input->post('sale_report-customer_transfers'),
                'sale_report-saleman' => $this->input->post('sale_report-saleman'),
				'sale_report-saleman_detail' => $this->input->post('sale_report-saleman_detail'),
				'sale_report-staff' => $this->input->post('sale_report-staff'),
				'sale_report-report_sale' => $this->input->post('sale_report-report_sale'),
				'sale_report-detail' => $this->input->post('sale_report-detail'),
				'sale_report-by_invoice' => $this->input->post('sale_report-by_invoice'),
				'sale_report-sale_profit' => $this->input->post('sale_report-sale_profit'),
				'sale_report-supplier' => $this->input->post('sale_report-supplier'),
				'sale_report-project' => $this->input->post('sale_report-project'),
                'sale_report-room_table' => $this->input->post('sale_report-room_table'),
				'sale_report-project_manager' => $this->input->post('sale_report-project_manager'),
				
				
				
				'chart_report-index' => $this->input->post('chart_report-index'),
				'chart_report-over_view' => $this->input->post('chart_report-over_view'),
				'chart_report-warehouse_stock' => $this->input->post('chart_report-warehouse_stock'),
				'chart_report-category_stock' => $this->input->post('chart_report-category_stock'),
				'chart_report-profit' => $this->input->post('chart_report-profit'),
				'chart_report-cash_analysis' => $this->input->post('chart_report-cash_analysis'),
				/*
				'chart_report-customize' => $this->input->post('chart_report-customize'),
				'chart_report-room_table' => $this->input->post('chart_report-room_table'),
				'chart_report-suspend_profit_and_lose' => $this->input->post('chart_report-suspend_profit_and_lose'),
				*/
                'report_profit-index' => $this->input->post('report_profit-index'),
                'report_profit-profit_andOr_lose' => $this->input->post('report_profit-profit_andOr_lose'),
				'report_profit-payments' => $this->input->post('report_profit-payments'),
				
				/*
				'report_profit-payments' => $this->input->post('report_profit-payments'),
				'report_profit-stock' => $this->input->post('report_profit-stock'),
				'report_profit-category' => $this->input->post('report_profit-category'),
				'report_profit-sale_profit' => $this->input->post('report_profit-sale_profit'),
				'report_profit-project' => $this->input->post('report_profit-project'),
				'report_profit-project_profit' => $this->input->post('report_profit-project_profit'),
				*/
				'customers_balance' => $this->input->post('customer_balance'),
				'purchase_report-index' => $this->input->post('purchase_report-index'),
				'purchase_report-daily' => $this->input->post('purchase_report-daily'),
				'purchase_report-monthly' => $this->input->post('purchase_report-monthly'),
				'purchase_report-supplier' => $this->input->post('purchase_report-supplier'),
				'purchase-authorize' => $this->input->post('purchase-authorize'),
				'sales-authorize' => $this->input->post('sales-authorize'),
				
				'report_convert' => $this->input->post('report_convert'),
                'reports-product_top_sale' => $this->input->post('reports-product_top_sale'),
				'report_list_using_stock' => $this->input->post('report_list_using_stock'),
				'report_transfers' => $this->input->post('report_transfers'),
				'purchase_report-expense' => $this->input->post('purchase_report-expense')
            );
			
            if (POS) {
                $data['pos-index'] = $this->input->post('pos-index');
            }
			//$this->erp->print_arrays($data);            
        }

		
        if ($this->form_validation->run() == true && $this->settings_model->updatePermissions($id, $data)) {
            $this->session->set_flashdata('message', lang("group_permissions_updated"));
            redirect($_SERVER["HTTP_REFERER"]);
        } else {

            $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');

            $this->data['id'] = $id;
            $this->data['p'] = $this->settings_model->getGroupPermissions($id);
            $this->data['group'] = $this->settings_model->getGroupByID($id);

            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('system_settings'), 'page' => lang('system_settings')), array('link' => '#', 'page' => lang('group_permissions')));
            $meta = array('page_title' => lang('group_permissions'), 'bc' => $bc);
            $this->page_construct('settings/permissions', $meta, $this->data);
        }
    }

    function user_groups()
    {
        if (!$this->Owner) {
            $this->session->set_flashdata('error', lang("access_denied"));
            redirect('auth');
        }
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['groups'] = $this->settings_model->getGroups();
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('system_settings'), 'page' => lang('system_settings')), array('link' => '#', 'page' => lang('groups')));
        $meta = array('page_title' => lang('groups'), 'bc' => $bc);
        $this->page_construct('settings/user_groups', $meta, $this->data);
    }

    function delete_group($id = NULL)
    {
        if (!$this->Owner) {
            $this->session->set_flashdata('warning', lang("access_denied"));
            redirect('welcome', 'refresh');
        }

        if ($this->settings_model->checkGroupUsers($id)) {
            $this->session->set_flashdata('error', lang("group_x_b_deleted"));
            redirect("system_settings/user_groups");
        }

        if ($this->settings_model->deleteGroup($id)) {
            $this->session->set_flashdata('message', lang("group_deleted"));
            redirect("system_settings/user_groups");
        }
    }

    function currencies()
    {

        $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('system_settings'), 'page' => lang('system_settings')), array('link' => '#', 'page' => lang('currencies')));
        $meta = array('page_title' => lang('currencies'), 'bc' => $bc);
        $this->page_construct('settings/currencies', $meta, $this->data);
    }

    function getCurrencies()
    {

        $this->load->library('datatables');
        $this->datatables
            ->select("id, code, name, rate, in_out")
            ->from("currencies")
            ->add_column("Actions", "<center><a href='" . site_url('system_settings/edit_currency/$1') . "' class='tip' title='" . lang("edit_currency") . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-edit\"></i></a> <a href='#' class='tip po' title='<b>" . lang("delete_currency") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('system_settings/delete_currency/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></center>", "id");
        //->unset_column('id');

        echo $this->datatables->generate();
    }

    function add_currency()
    {

        $this->form_validation->set_rules('code', lang("currency_code"), 'trim|is_unique[currencies.code]|required');
        $this->form_validation->set_rules('name', lang("name"), 'required');
        $this->form_validation->set_rules('rate', lang("exchange_rate"), 'required|numeric');

        if ($this->form_validation->run() == true) {
            $data = array(
				'code' => $this->input->post('code'),
                'name' => $this->input->post('name'),
                'rate' => $this->input->post('rate'),
				'in_out' => $this->input->post('in_out')
            );
        } elseif ($this->input->post('add_currency')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect("system_settings/currencies");
        }

        if ($this->form_validation->run() == true && $this->settings_model->addCurrency($data)) { //check to see if we are creating the customer
            $this->session->set_flashdata('message', lang("currency_added"));
            redirect("system_settings/currencies");
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['modal_js'] = $this->site->modal_js();
            $this->data['page_title'] = lang("new_currency");
            $this->load->view($this->theme . 'settings/add_currency', $this->data);
        }
    }

    function edit_currency($id = NULL)
    {

        $this->form_validation->set_rules('code', lang("currency_code"), 'trim|required');
        $cur_details = $this->settings_model->getCurrencyByID($id);
        if ($this->input->post('code') != $cur_details->code) {
            $this->form_validation->set_rules('code', lang("currency_code"), 'is_unique[currencies.code]');
        }
        $this->form_validation->set_rules('name', lang("currency_name"), 'required');
        $this->form_validation->set_rules('rate', lang("exchange_rate"), 'required|numeric');

        if ($this->form_validation->run() == true) {

            $data = array(
				'code' => $this->input->post('code'),
                'name' => $this->input->post('name'),
                'rate' => $this->input->post('rate'),
				'in_out' => $this->input->post('in_out')
            );
			//$this->erp->print_arrays($data);
        } elseif ($this->input->post('edit_currency')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect("system_settings/currencies");
        }

        if ($this->form_validation->run() == true && $this->settings_model->updateCurrency($id, $data)) { //check to see if we are updateing the customer
            $this->session->set_flashdata('message', lang("currency_updated"));
            redirect("system_settings/currencies");
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['currency'] = $this->settings_model->getCurrencyByID($id);
            $this->data['id'] = $id;
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/edit_currency', $this->data);
        }
    }

    function delete_currency($id = NULL)
    {

        if ($this->settings_model->deleteCurrency($id)) {
            echo lang("currency_deleted");
        }
    }

    function currency_actions()
    {

        $this->form_validation->set_rules('form_action', lang("form_action"), 'required');

        if ($this->form_validation->run() == true) {

            if (!empty($_POST['val'])) {
                if ($this->input->post('form_action') == 'delete') {
                    foreach ($_POST['val'] as $id) {
                        $this->settings_model->deleteCurrency($id);
                    }
                    $this->session->set_flashdata('message', lang("currencies_deleted"));
                    redirect($_SERVER["HTTP_REFERER"]);
                }

                if ($this->input->post('form_action') == 'export_excel' || $this->input->post('form_action') == 'export_pdf') {

                    $this->load->library('excel');
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle(lang('currencies'));
                    $this->excel->getActiveSheet()->SetCellValue('A1', lang('currency_code'));
                    $this->excel->getActiveSheet()->SetCellValue('B1', lang('currency_name'));
                    $this->excel->getActiveSheet()->SetCellValue('C1', lang('exchange_rate'));

                    $row = 2;
                    foreach ($_POST['val'] as $id) {
                        $sc = $this->settings_model->getCurrencyByID($id);
                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $sc->code);
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $sc->name);
                        $this->excel->getActiveSheet()->SetCellValue('C' . $row, $sc->rate);
                        $row++;
                    }

                    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $filename = 'currencies_' . date('Y_m_d_H_i_s');
                    if ($this->input->post('form_action') == 'export_pdf') {
                        $styleArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
                        $this->excel->getDefaultStyle()->applyFromArray($styleArray);
                        $this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
                        require_once(APPPATH . "third_party" . DIRECTORY_SEPARATOR . "MPDF" . DIRECTORY_SEPARATOR . "mpdf.php");
                        $rendererName = PHPExcel_Settings::PDF_RENDERER_MPDF;
                        $rendererLibrary = 'MPDF';
                        $rendererLibraryPath = APPPATH . 'third_party' . DIRECTORY_SEPARATOR . $rendererLibrary;
                        if (!PHPExcel_Settings::setPdfRenderer($rendererName, $rendererLibraryPath)) {
                            die('Please set the $rendererName: ' . $rendererName . ' and $rendererLibraryPath: ' . $rendererLibraryPath . ' values' .
                                PHP_EOL . ' as appropriate for your directory structure');
                        }

                        header('Content-Type: application/pdf');
                        header('Content-Disposition: attachment;filename="' . $filename . '.pdf"');
                        header('Cache-Control: max-age=0');

                        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'PDF');
                        return $objWriter->save('php://output');
                    }
                    if ($this->input->post('form_action') == 'export_excel') {
                        header('Content-Type: application/vnd.ms-excel');
                        header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
                        header('Cache-Control: max-age=0');

                        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
                        return $objWriter->save('php://output');
                    }

                    redirect($_SERVER["HTTP_REFERER"]);
                }
            } else {
                $this->session->set_flashdata('error', lang("no_tax_rate_selected"));
                redirect($_SERVER["HTTP_REFERER"]);
            }
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
    
	}

    function categories($parent_id = NULL)
    {

        $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
		$this->data['parent_id'] = $parent_id;
        $this->data['modal_js'] = $this->site->modal_js();
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('system_settings'), 'page' => lang('system_settings')), array('link' => '#', 'page' => lang('categories')));
        $meta = array('page_title' => lang('categories'), 'bc' => $bc);
        $this->page_construct('settings/categories', $meta, $this->data);
    }

    function getCategories($parent_id = NULL)
    {

        $print_barcode = anchor('products/print_barcodes/?category=$1', '<i class="fa fa-print"></i>', 'title="'.lang('print_barcodes').'" class="tip"');

        $this->load->library('datatables');
        $this->datatables
            ->select("id, image, code, name")
            ->from("categories")
            ->add_column("Actions", "<div class=\"text-center\"><a href='" . site_url('system_settings/subcategories/$1') . "' class='tip' title='" . lang("list_subcategories") . "'><i class=\"fa fa-list\"></i></a> ".$print_barcode." <a href='" . site_url('system_settings/edit_category/$1') . "' data-toggle='modal' data-target='#myModal' class='tip' title='" . lang("edit_category") . "'><i class=\"fa fa-edit\"></i></a> <a href='#' class='tip po' title='<b>" . lang("delete_category") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('system_settings/delete_category/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></div>", "id");
		
		if ($parent_id) {
            $this->datatables->where('brand_id', $parent_id);
        }
		
        echo $this->datatables->generate();
    }

    function add_category($parent_id = NULL)
    {

        $this->load->helper('security');
        $this->form_validation->set_rules('code', lang("category_code"), 'trim|is_unique[categories.code]|required');
        $this->form_validation->set_rules('name', lang("name"), 'required|min_length[3]');
        $this->form_validation->set_rules('userfile', lang("category_image"), 'xss_clean');

        if ($this->form_validation->run() == true) {
            $name = $this->input->post('name');
            $code = $this->input->post('code');
			$brand_id = $this->input->post('brand');
			$categories_note = $this->input->post('categories_note');
			$categories_note_id = '';
			$i = 1;
			foreach($categories_note as $cate_id){
				if(count($categories_note)==$i){
					$categories_note_id .= $cate_id;
				}else{
					$categories_note_id .= $cate_id.',';
				}
				$i++;
			}
			$account_sale = $this->input->post('account_sale');
            $account_cost = $this->input->post('account_cost');
		    $account_stock = $this->input->post('account_stock');
		    $account_stock_adj = $this->input->post('account_stock_adjust');
		    $account_sale_discount = $this->input->post('account_sale_discount');
		    $account_cost_variant = $this->input->post('account_cost_variant');
			$account_purchase = $this->input->post('account_purchase');
		
            if ($_FILES['userfile']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->upload_path;
                $config['allowed_types'] = $this->image_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['max_width'] = $this->Settings->iwidth;
                $config['max_height'] = $this->Settings->iheight;
                $config['overwrite'] = FALSE;
                $config['encrypt_name'] = TRUE;
                $config['max_filename'] = 25;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                //$data['image'] = $photo;
                $this->load->library('image_lib');
                $config['image_library'] = 'gd2';
                $config['source_image'] = $this->upload_path . $photo;
                $config['new_image'] = $this->thumbs_path . $photo;
                $config['maintain_ratio'] = TRUE;
                $config['width'] = $this->Settings->twidth;
                $config['height'] = $this->Settings->theight;
                $this->image_lib->clear();
                $this->image_lib->initialize($config);
                if (!$this->image_lib->resize()) {
                    echo $this->image_lib->display_errors();
                }
                if ($this->Settings->watermark) {
                    $this->image_lib->clear();
                    $wm['source_image'] = $this->upload_path . $photo;
                    $wm['wm_text'] = 'Copyright ' . date('Y') . ' - ' . $this->Settings->site_name;
                    $wm['wm_type'] = 'text';
                    $wm['wm_font_path'] = 'system/fonts/texb.ttf';
                    $wm['quality'] = '100';
                    $wm['wm_font_size'] = '16';
                    $wm['wm_font_color'] = '999999';
                    $wm['wm_shadow_color'] = 'CCCCCC';
                    $wm['wm_vrt_alignment'] = 'top';
                    $wm['wm_hor_alignment'] = 'right';
                    $wm['wm_padding'] = '10';
                    $this->image_lib->initialize($wm);
                    $this->image_lib->watermark();
                }
                $this->image_lib->clear();
                $config = NULL;
            } else {
                $photo = 'no_image.png';
            }

            $types = $this->input->post('cate_type');
            $cate_type = '';
            $i = 1;
            foreach($types as $type){
                if(count($types)==$i){
                    $cate_type .= $type;
                }else{
                    $cate_type .= $type.',';
                }
                $i++;
            }

			$data = array(  'code' => $code,
							'name' => $name,
                            'type' => $cate_type,
							'categories_note_id' => $categories_note_id,
							'image' => $photo, 
							'brand_id' => $brand_id,
							'ac_sale' => $account_sale, 
							'ac_cost' => $account_cost, 
							'ac_stock' =>  $account_stock,
							'ac_stock_adj' => $account_stock_adj, 
							'ac_cost_variant' => $account_cost_variant ,
							'ac_purchase' => $account_purchase
							
						 );
            
        } elseif ($this->input->post('add_category')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER['HTTP_REFERER']);
        }

        if ($this->form_validation->run() == true && $cid = $this->settings_model->addCategory($data)) {
            $this->session->set_flashdata('message', lang("category_added"));
            $ref = isset($_SERVER["HTTP_REFERER"]) ? explode('?', $_SERVER["HTTP_REFERER"]) : NULL;
                redirect($ref[0] . '?category=' . $cid);
				// redirect($_SERVER['HTTP_REFERER']);
        } else {
            $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');

            $this->data['name'] = array('name' => 'name',
                'id' => 'name',
                'type' => 'text',
                'class' => 'form-control',
                'required' => 'required',
                'value' => $this->form_validation->set_value('name'),
            );
            $this->data['code'] = array('name' => 'code',
                'id' => 'code',
                'type' => 'text',
                'class' => 'form-control',
                'required' => 'required',
                'value' => $this->form_validation->set_value('code'),
            );
			$this->load->model('accounts_model');
			$this->data['chart_accounts'] = $this->accounts_model->getAllChartAccounts();
			$this->data['parent_id'] = $parent_id;
			$this->data['brand'] = $this->site->getAllBrands();
			$this->data['categories_note'] = $this->settings_model->getAllCategoriesNote();
			$this->data['setting'] = $this->site->get_setting();
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/add_category', $this->data);
        }
    }

    function edit_category($id = NULL)
    {
        $this->load->helper('security');
        $this->form_validation->set_rules('code', lang("category_code"), 'trim|required');
        $pr_details = $this->settings_model->getCategoryByID($id);
        if ($this->input->post('code') != $pr_details->code) {
            $this->form_validation->set_rules('code', lang("category_code"), 'is_unique[categories.code]');
        }
        $this->form_validation->set_rules('name', lang("category_name"), 'required|min_length[3]');
        $this->form_validation->set_rules('userfile', lang("category_image"), 'xss_clean');

        if ($this->form_validation->run() == true) {

            if ($_FILES['userfile']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] 		= $this->upload_path;
                $config['allowed_types'] 	= $this->image_types;
                $config['max_size'] 		= $this->allowed_file_size;
                $config['max_width'] 		= $this->Settings->iwidth;
                $config['max_height'] 		= $this->Settings->iheight;
                $config['overwrite'] 		= FALSE;
                $config['encrypt_name'] 	= TRUE;
                $config['max_filename'] 	= 25;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                //$data['image'] = $photo;
                $this->load->library('image_lib');
                $config['image_library'] = 'gd2';
                $config['source_image'] = $this->upload_path . $photo;
                $config['new_image'] = $this->thumbs_path . $photo;
                $config['maintain_ratio'] = TRUE;
                $config['width'] = $this->Settings->twidth;
                $config['height'] = $this->Settings->theight;
                $this->image_lib->clear();
                $this->image_lib->initialize($config);
                if (!$this->image_lib->resize()) {
                    echo $this->image_lib->display_errors();
                }
                if ($this->Settings->watermark) {
                    $this->image_lib->clear();
                    $wm['source_image'] = $this->upload_path . $photo;
                    $wm['wm_text'] = 'Copyright ' . date('Y') . ' - ' . $this->Settings->site_name;
                    $wm['wm_type'] = 'text';
                    $wm['wm_font_path'] = 'system/fonts/texb.ttf';
                    $wm['quality'] = '100';
                    $wm['wm_font_size'] = '16';
                    $wm['wm_font_color'] = '999999';
                    $wm['wm_shadow_color'] = 'CCCCCC';
                    $wm['wm_vrt_alignment'] = 'top';
                    $wm['wm_hor_alignment'] = 'right';
                    $wm['wm_padding'] = '10';
                    $this->image_lib->initialize($wm);
                    $this->image_lib->watermark();
                }
                $this->image_lib->clear();
                $config = NULL;
            } else {
                $photo = NULL;
            }
			$categories_note = $this->input->post('categories_note');
			$categories_note_id = '';
			$i = 1;
			foreach($categories_note as $cate_id){
				if(count($categories_note)==$i){
					$categories_note_id .= $cate_id;
				}else{
					$categories_note_id .= $cate_id.',';
				}
				$i++;
			}
			$types = $this->input->post('cate_type');
            $cate_type = '';
            $i = 1;
            foreach($types as $type){
                if(count($types)==$i){
                    $cate_type .= $type;
                }else{
                    $cate_type .= $type.',';
                }
                $i++;
            }
			
			$data = array(
				'code' 					=> $this->input->post('code'),
				'name' 					=> $this->input->post('name'),
				'brand_id' 				=> $this->input->post('brand'),
				'categories_note_id' 	=> $categories_note_id,
				'ac_sale' 				=> $this->input->post('account_sale'),
				'ac_cost' 				=> $this->input->post('account_cost'),
				'ac_stock' 				=> $this->input->post('account_stock'),
				'ac_stock_adj' 			=> $this->input->post('account_stock_adjust'),
				'ac_cost_variant' 		=> $this->input->post('account_cost_variant'),
				'ac_purchase' 			=> $this->input->post('account_purchase'),
				'type' 					=> $cate_type
			 );
        } elseif ($this->input->post('edit_category')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER['HTTP_REFERER']);
        }

        if ($this->form_validation->run() == true && $this->settings_model->updateCategory($id, $data, $photo)) {
            $this->session->set_flashdata('message', lang("category_updated"));
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
            $category = $this->settings_model->getCategoryByID($id);

            $this->data['name'] = array('name' => 'name',
                'id' => 'name',
                'type' => 'text',
                'class' => 'form-control',
                'required' => 'required',
                'value' => $this->form_validation->set_value('name', $category->name),
            );
            $this->data['code'] = array('name' => 'code',
                'id' => 'code',
                'type' => 'text',
                'class' => 'form-control',
                'required' => 'required',
                'value' => $this->form_validation->set_value('code', $category->code),
            );
			$this->data['type_edit'] = array('name' => 'cate_type',
                'id' => 'code',
                'type' => 'text',
                'class' => 'form-control',
                'required' => 'required',
                'value' => $this->form_validation->set_value('type', $category->type),
            );
            // $this->erp->print_arrays($this->data['type_edit']);
			$this->load->model('accounts_model');			
			$this->data['chart_accounts'] = $this->accounts_model->getAllChartAccounts();
			$this->data['categories_note'] = $this->settings_model->getAllCategoriesNote();
			$this->data['brand_id'] = $category->brand_id;
			$this->data['category'] = $this->settings_model->getCategoryByID($id);
			$this->data['brand'] = $this->site->getAllBrands();
			$this->data['setting'] = $this->site->get_setting();
            $this->data['modal_js'] = $this->site->modal_js();
            $this->data['id'] = $id;
            $this->load->view($this->theme . 'settings/edit_category', $this->data);
        }
    }

    function delete_category($id = NULL)
    {
        if ($this->settings_model->getSubCategoriesByCategoryID($id)) {
			$this->session->set_flashdata('error', lang("category_has_subcategory"));
            redirect("system_settings/categories");

        }
		
		if($this->settings_model->hasCategoryInProduct($id)){
			$this->session->set_flashdata('error', lang("category_has_product_cannot_delete"));
			redirect("system_settings/categories");
		}
		if ($this->settings_model->deleteCategory($id)) {
			$this->session->set_flashdata('message', lang("category_deleted"));
			redirect("system_settings/categories");
		}
    }

    function category_actions()
    {
        $this->form_validation->set_rules('form_action', lang("form_action"), 'required');

        if ($this->form_validation->run() == true) {

            if (!empty($_POST['check'])) {
                if ($this->input->post('form_action') == 'delete') {
                    foreach ($_POST['check'] as $id) {
                        $this->settings_model->deleteCategory($id);
                    }
                    $this->session->set_flashdata('message', lang("categories_deleted"));
                    redirect($_SERVER["HTTP_REFERER"]);
                }

                if ($this->input->post('form_action') == 'export_excel' || $this->input->post('form_action') == 'export_pdf') {

                    $this->load->library('excel');
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle(lang('categories'));
                    $this->excel->getActiveSheet()->SetCellValue('A1', lang('code'));
                    $this->excel->getActiveSheet()->SetCellValue('B1', lang('name'));
                    $this->excel->getActiveSheet()->SetCellValue('C1', lang('type'));
                    $this->excel->getActiveSheet()->SetCellValue('D1', lang('default_sale'));
                    $this->excel->getActiveSheet()->SetCellValue('E1', lang('default_purchase'));
                    $this->excel->getActiveSheet()->SetCellValue('F1', lang('default_stock'));
                    $this->excel->getActiveSheet()->SetCellValue('G1', lang('default_stock_adjust'));
                    $this->excel->getActiveSheet()->SetCellValue('H1', lang('default_cost'));
                    $this->excel->getActiveSheet()->SetCellValue('I1', lang('default_cost_variant'));

                    $row = 2;
                    foreach ($_POST['check'] as $id) {
                        $cate = $this->settings_model->getAllCategoriesByAcc($id);
                        $subcates = $this->settings_model->getSubCategoryByIDToExport($id);

                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $cate->code);
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $cate->name);
                        $this->excel->getActiveSheet()->SetCellValue('C' . $row, $cate->type);
                        $this->excel->getActiveSheet()->SetCellValue('D' . $row, $cate->sale);
                        $this->excel->getActiveSheet()->SetCellValue('E' . $row, $cate->purchase);
                        $this->excel->getActiveSheet()->SetCellValue('F' . $row, $cate->stock);
                        $this->excel->getActiveSheet()->SetCellValue('G' . $row, $cate->stock_adjust);
                        $this->excel->getActiveSheet()->SetCellValue('H' . $row, $cate->cost);
                        $this->excel->getActiveSheet()->SetCellValue('I' . $row, $cate->cost_variant);
                        $row++;

                        foreach ($subcates as $sub) {
                            $this->excel->getActiveSheet()->SetCellValue('A' . $row, '     '.$sub->code);
                            $this->excel->getActiveSheet()->SetCellValue('B' . $row, $sub->name);

                            $this->excel->getActiveSheet()->getStyle('A' . $row.'')->getFont()->setItalic(true);
                            $this->excel->getActiveSheet()->getStyle('B' . $row.'')->getFont()->setItalic(true);
                            $this->excel->getActiveSheet()->getStyle('A' . $row.'')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border:: BORDER_THIN);
                            $this->excel->getActiveSheet()->getStyle('B' . $row.'')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border:: BORDER_THIN);
                            $this->excel->getActiveSheet()->getStyle('A' . $row.'')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border:: BORDER_THIN);
                            $this->excel->getActiveSheet()->getStyle('B' . $row.'')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border:: BORDER_THIN);

                            $row++;
                        }
                         
                    }

                    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
                    $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
                    $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
                    $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(25);
                    $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(25);
                    $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(25);
                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $filename = 'categories_' . date('Y_m_d_H_i_s');

                    if ($this->input->post('form_action') == 'export_pdf') {
                        $styleArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
                        $this->excel->getDefaultStyle()->applyFromArray($styleArray);
                        $this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
                        require_once(APPPATH . "third_party" . DIRECTORY_SEPARATOR . "MPDF" . DIRECTORY_SEPARATOR . "mpdf.php");
                        $rendererName = PHPExcel_Settings::PDF_RENDERER_MPDF;
                        $rendererLibrary = 'MPDF';
                        $rendererLibraryPath = APPPATH . 'third_party' . DIRECTORY_SEPARATOR . $rendererLibrary;
                        if (!PHPExcel_Settings::setPdfRenderer($rendererName, $rendererLibraryPath)) {
                            die('Please set the $rendererName: ' . $rendererName . ' and $rendererLibraryPath: ' . $rendererLibraryPath . ' values' .
                                PHP_EOL . ' as appropriate for your directory structure');
                        }

                        header('Content-Type: application/pdf');
                        header('Content-Disposition: attachment;filename="' . $filename . '.pdf"');
                        header('Cache-Control: max-age=0');

                        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'PDF');
                        return $objWriter->save('php://output');
                    }

                    if ($this->input->post('form_action') == 'export_excel') {
                        header('Content-Type: application/vnd.ms-excel');
                        header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
                        header('Cache-Control: max-age=0');

                        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
                        return $objWriter->save('php://output');
                    }

                    redirect($_SERVER["HTTP_REFERER"]);
                }
            } else {
                $this->session->set_flashdata('error', lang("no_category_selected"));
                redirect($_SERVER["HTTP_REFERER"]);
            }
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }

    function subcategories($parent_id = NULL)
    {

        $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');

        $this->data['parent_id'] = $parent_id;
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('system_settings'), 'page' => lang('system_settings')), array('link' => site_url('system_settings/categories'), 'page' => lang('categories')), array('link' => '#', 'page' => lang('subcategories')));
        $meta = array('page_title' => lang('subcategories'), 'bc' => $bc);
        $this->page_construct('settings/subcategories', $meta, $this->data);
    }

    function getSubcategories($parent_id = NULL)
    {
        $print_barcode = anchor('products/print_barcodes/?subcategory=$1', '<i class="fa fa-print"></i>', 'title="'.lang('print_barcodes').'" class="tip"');

        $this->load->library('datatables');
        $this->datatables
            ->select("subcategories.id as id, subcategories.image as image, subcategories.code as scode, subcategories.name as sname, categories.name as cname")
            ->from("subcategories")
            ->join('categories', 'categories.id = subcategories.category_id', 'left')
            ->group_by('subcategories.id');

        if ($parent_id) {
            $this->datatables->where('category_id', $parent_id);
        }

        $this->datatables->add_column("Actions", "<div class=\"text-center\">".$print_barcode." <a href='" . site_url('system_settings/edit_subcategory/$1') . "' data-toggle='modal' data-target='#myModal' class='tip' title='" . lang("edit_subcategory") . "'><i class=\"fa fa-edit\"></i></a> <a href='#' class='tip po' title='<b>" . lang("delete_subcategory") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('system_settings/delete_subcategory/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></div>", "id");
        // ->unset_column('id');
        echo $this->datatables->generate();
    }

    function add_subcategory($parent_id = NULL)
    {
        $this->load->helper('security');
        $this->form_validation->set_rules('category', lang("main_category"), 'required');
        $this->form_validation->set_rules('code', lang("subcategory_code"), 'trim|is_unique[categories.code]|is_unique[subcategories.code]|required');
        $this->form_validation->set_rules('name', lang("subcategory_name"), 'required|min_length[2]');
        $this->form_validation->set_rules('userfile', lang("category_image"), 'xss_clean');

        if ($this->form_validation->run() == true) {
            $name = $this->input->post('name');
            $code = $this->input->post('code');
			$type = $this->input->post('cate_type');
            $category = $this->input->post('category');
            if ($_FILES['userfile']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->upload_path;
                $config['allowed_types'] = $this->image_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['max_width'] = $this->Settings->iwidth;
                $config['max_height'] = $this->Settings->iheight;
                $config['overwrite'] = FALSE;
                $config['encrypt_name'] = TRUE;
                $config['max_filename'] = 25;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                //$data['image'] = $photo;
                $this->load->library('image_lib');
                $config['image_library'] = 'gd2';
                $config['source_image'] = $this->upload_path . $photo;
                $config['new_image'] = $this->thumbs_path . $photo;
                $config['maintain_ratio'] = TRUE;
                $config['width'] = $this->Settings->twidth;
                $config['height'] = $this->Settings->theight;
                $this->image_lib->clear();
                $this->image_lib->initialize($config);
                if (!$this->image_lib->resize()) {
                    echo $this->image_lib->display_errors();
                }
                if ($this->Settings->watermark) {
                    $this->image_lib->clear();
                    $wm['source_image'] = $this->upload_path . $photo;
                    $wm['wm_text'] = 'Copyright ' . date('Y') . ' - ' . $this->Settings->site_name;
                    $wm['wm_type'] = 'text';
                    $wm['wm_font_path'] = 'system/fonts/texb.ttf';
                    $wm['quality'] = '100';
                    $wm['wm_font_size'] = '16';
                    $wm['wm_font_color'] = '999999';
                    $wm['wm_shadow_color'] = 'CCCCCC';
                    $wm['wm_vrt_alignment'] = 'top';
                    $wm['wm_hor_alignment'] = 'right';
                    $wm['wm_padding'] = '10';
                    $this->image_lib->initialize($wm);
                    $this->image_lib->watermark();
                }
                $this->image_lib->clear();
                $config = NULL;
            } else {
                $photo = 'no_image.png';
            }
        } elseif ($this->input->post('add_subcategory')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect("system_settings/categories");
        }

        if ($this->form_validation->run() == true && $scate = $this->settings_model->addSubCategory($category, $name, $code,$type, $photo)) {
            $this->session->set_flashdata('message', lang("subcategory_added"));
			if (strpos($_SERVER['HTTP_REFERER'], 'system_settings/add_category') !== false) {
				 redirect("system_settings/add_subcategory", 'refresh');
			}else{
                $ref = isset($_SERVER["HTTP_REFERER"]) ? explode('?', $_SERVER["HTTP_REFERER"]) : NULL;
                redirect($ref[0] . '?subcategory=' . $scate);
				 // redirect("system_settings/categories");
			}
        } else {
            $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');

            $this->data['name'] = array('name' => 'name',
                'id' => 'name',
                'type' => 'text', 'class' => 'form-control',
                'class' => 'form-control',
                'required' => 'required',
                'value' => $this->form_validation->set_value('name'),
            );
            $this->data['code'] = array('name' => 'code',
                'id' => 'code',
                'type' => 'text',
                'class' => 'form-control',
                'required' => 'required',
                'value' => $this->form_validation->set_value('code'),
            );
            $this->data['parent_id'] = $parent_id;
            $this->data['modal_js'] = $this->site->modal_js();
            $this->data['categories'] = $this->settings_model->getAllCategories();
            $this->load->view($this->theme . 'settings/add_subcategory', $this->data);
        }
    }

    function edit_subcategory($id = NULL)
    {

        $this->load->helper('security');
        $this->form_validation->set_rules('category', lang("main_category"), 'required');
        $this->form_validation->set_rules('code', lang("subcategory_code"), 'trim|required');
        $pr_details = $this->settings_model->getSubCategoryByID($id);
        if ($this->input->post('code') != $pr_details->code) {
            $this->form_validation->set_rules('code', lang("subcategory_code"), 'is_unique[categories.code]');
        }
        $this->form_validation->set_rules('name', lang("subcategory_name"), 'required|min_length[2]');
        $this->form_validation->set_rules('userfile', lang("category_image"), 'xss_clean');

        if ($this->form_validation->run() == true) {

            $data = array(
                'category' => $this->input->post('category'),
                'code' => $this->input->post('code'),
                'name' => $this->input->post('name'),
				'type' => $this->input->post('cate_type')
            );
            if ($_FILES['userfile']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->upload_path;
                $config['allowed_types'] = $this->image_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['max_width'] = $this->Settings->iwidth;
                $config['max_height'] = $this->Settings->iheight;
                $config['overwrite'] = FALSE;
                $config['encrypt_name'] = TRUE;
                $config['max_filename'] = 25;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                //$data['image'] = $photo;
                $this->load->library('image_lib');
                $config['image_library'] = 'gd2';
                $config['source_image'] = $this->upload_path . $photo;
                $config['new_image'] = $this->thumbs_path . $photo;
                $config['maintain_ratio'] = TRUE;
                $config['width'] = $this->Settings->twidth;
                $config['height'] = $this->Settings->theight;
                $this->image_lib->clear();
                $this->image_lib->initialize($config);
                if (!$this->image_lib->resize()) {
                    echo $this->image_lib->display_errors();
                }
                if ($this->Settings->watermark) {
                    $this->image_lib->clear();
                    $wm['source_image'] = $this->upload_path . $photo;
                    $wm['wm_text'] = 'Copyright ' . date('Y') . ' - ' . $this->Settings->site_name;
                    $wm['wm_type'] = 'text';
                    $wm['wm_font_path'] = 'system/fonts/texb.ttf';
                    $wm['quality'] = '100';
                    $wm['wm_font_size'] = '16';
                    $wm['wm_font_color'] = '999999';
                    $wm['wm_shadow_color'] = 'CCCCCC';
                    $wm['wm_vrt_alignment'] = 'top';
                    $wm['wm_hor_alignment'] = 'right';
                    $wm['wm_padding'] = '10';
                    $this->image_lib->initialize($wm);
                    $this->image_lib->watermark();
                }
                $this->image_lib->clear();
                $config = NULL;
            } else {
                $photo = NULL;
            }
        } elseif ($this->input->post('edit_subcategory')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect("system_settings/categories");
        }

        if ($this->form_validation->run() == true && $this->settings_model->updateSubCategory($id, $data, $photo)) {
            $this->session->set_flashdata('message', lang("subcategory_updated"));
            redirect("system_settings/categories");
        } else {
             $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
            $category = $this->settings_model->getCategoryByID($id);
            $this->data['name'] = array('name' => 'name',
                'id' => 'name',
                'type' => 'text',
                'class' => 'form-control',
                'required' => 'required',
                'value' => $this->form_validation->set_value('name', $category->name),
            );
            $this->data['code'] = array('name' => 'code',
                'id' => 'code',
                'type' => 'text',
                'class' => 'form-control',
                'required' => 'required',
                'value' => $this->form_validation->set_value('code', $category->code),
            );
			 $this->data['type_edit'] = array('name' => 'cate_type',
                'id' => 'code',
                'type' => 'text',
                'class' => 'form-control',
                'required' => 'required',
                'value' => $this->form_validation->set_value('type', $category->type),
            );
			$this->load->model('accounts_model');
			$this->data['chart_accounts'] = $this->accounts_model->getAllChartAccounts();
			$this->data['brand_id'] = $category->brand_id;
			$this->data['subcategory'] = $this->settings_model->getSubCategoryByID($id);
			$this->data['brand'] = $this->site->getAllBrands();
            $this->data['modal_js'] = $this->site->modal_js();
			$this->data['categories'] = $this->settings_model->getAllCategories();
            $this->data['id'] = $id;
            $this->load->view($this->theme . 'settings/edit_subcategory', $this->data);
        }
    }

    function delete_subcategory($id = NULL)
    {
		if($this->settings_model->hasSubCategoryInProduct($id)){
			$this->session->set_flashdata('error', lang("subcategory_has_product_cannot_delete"));
			redirect("system_settings/categories");
		}
        if ($this->settings_model->deleteSubCategory($id)) {
			$this->session->set_flashdata('message', lang("subcategory_deleted"));
			redirect("system_settings/categories");
        }
    }

    function subcategory_actions()
    {

        $this->form_validation->set_rules('form_action', lang("form_action"), 'required');

        if ($this->form_validation->run() == true) {

            if (!empty($_POST['val'])) {
                if ($this->input->post('form_action') == 'delete') {
                    foreach ($_POST['val'] as $id) {
                        $this->settings_model->deleteSubcategory($id);
                    }
                    $this->session->set_flashdata('message', lang("subcategories_deleted"));
                    redirect($_SERVER["HTTP_REFERER"]);
                }

                if ($this->input->post('form_action') == 'export_excel' || $this->input->post('form_action') == 'export_pdf') {

                    $this->load->library('excel');
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle(lang('subcategories'));
                    $this->excel->getActiveSheet()->SetCellValue('A1', lang('code'));
                    $this->excel->getActiveSheet()->SetCellValue('B1', lang('name'));
                    $this->excel->getActiveSheet()->SetCellValue('C1', lang('main_category'));

                    $row = 2;
                    foreach ($_POST['val'] as $id) {
                        $sc = $this->settings_model->getSubcategoryDetails($id);
                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $sc->code);
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $sc->name);
                        $this->excel->getActiveSheet()->SetCellValue('C' . $row, $sc->parent);
                        $row++;
                    }

                    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $filename = 'subcategories_' . date('Y_m_d_H_i_s');
                    if ($this->input->post('form_action') == 'export_pdf') {
                        $styleArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
                        $this->excel->getDefaultStyle()->applyFromArray($styleArray);
                        $this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
                        require_once(APPPATH . "third_party" . DIRECTORY_SEPARATOR . "MPDF" . DIRECTORY_SEPARATOR . "mpdf.php");
                        $rendererName = PHPExcel_Settings::PDF_RENDERER_MPDF;
                        $rendererLibrary = 'MPDF';
                        $rendererLibraryPath = APPPATH . 'third_party' . DIRECTORY_SEPARATOR . $rendererLibrary;
                        if (!PHPExcel_Settings::setPdfRenderer($rendererName, $rendererLibraryPath)) {
                            die('Please set the $rendererName: ' . $rendererName . ' and $rendererLibraryPath: ' . $rendererLibraryPath . ' values' .
                                PHP_EOL . ' as appropriate for your directory structure');
                        }

                        header('Content-Type: application/pdf');
                        header('Content-Disposition: attachment;filename="' . $filename . '.pdf"');
                        header('Cache-Control: max-age=0');

                        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'PDF');
                        return $objWriter->save('php://output');
                    }
                    if ($this->input->post('form_action') == 'export_excel') {
                        header('Content-Type: application/vnd.ms-excel');
                        header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
                        header('Cache-Control: max-age=0');

                        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
                        return $objWriter->save('php://output');
                    }

                    redirect($_SERVER["HTTP_REFERER"]);
                }
            } else {
                $this->session->set_flashdata('error', lang("no_tax_rate_selected"));
                redirect($_SERVER["HTTP_REFERER"]);
            }
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }

    function tax_rates()
    {

        $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('system_settings'), 'page' => lang('system_settings')), array('link' => '#', 'page' => lang('tax_rates')));
        $meta = array('page_title' => lang('tax_rates'), 'bc' => $bc);
        $this->page_construct('settings/tax_rates', $meta, $this->data);
    }

    function getTaxRates()
    {

        $this->load->library('datatables');
        $this->datatables
            ->select("id, name, code, rate, type")
            ->from("tax_rates")
            ->add_column("Actions", "<div class=\"text-center\"><a href='" . site_url('system_settings/edit_tax_rate/$1') . "' class='tip' title='" . lang("edit_tax_rate") . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-edit\"></i></a> <a href='#' class='tip po' title='<b>" . lang("delete_tax_rate") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('system_settings/delete_tax_rate/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></div>", "id");
        //->unset_column('id');

        echo $this->datatables->generate();
    }

    function add_tax_rate()
    {

        $this->form_validation->set_rules('name', lang("name"), 'trim|is_unique[tax_rates.name]|required');
        $this->form_validation->set_rules('code', lang("code"), 'required');
        $this->form_validation->set_rules('type', lang("type"), 'required');
        $this->form_validation->set_rules('rate', lang("tax_rate"), 'required|numeric');

        if ($this->form_validation->run() == true) {
            $data = array('name' => $this->input->post('name'),
                'code' => $this->input->post('code'),
                'type' => $this->input->post('type'),
                'rate' => $this->input->post('rate'),
            );
        } elseif ($this->input->post('add_tax_rate')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect("system_settings/tax_rates");
        }

        if ($this->form_validation->run() == true && $this->settings_model->addTaxRate($data)) {
            $this->session->set_flashdata('message', lang("tax_rate_added"));
            redirect("system_settings/tax_rates");
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/add_tax_rate', $this->data);
        }
    }

    function edit_tax_rate($id = NULL)
    {

        $this->form_validation->set_rules('name', lang("name"), 'trim|required');
        $tax_details = $this->settings_model->getTaxRateByID($id);
        if ($this->input->post('name') != $tax_details->name) {
            $this->form_validation->set_rules('name', lang("name"), 'is_unique[tax_rates.name]');
        }
        $this->form_validation->set_rules('code', lang("code"), 'required');
        $this->form_validation->set_rules('type', lang("type"), 'required');
        $this->form_validation->set_rules('rate', lang("tax_rate"), 'required|numeric');

        if ($this->form_validation->run() == true) {

            $data = array('name' => $this->input->post('name'),
                'code' => $this->input->post('code'),
                'type' => $this->input->post('type'),
                'rate' => $this->input->post('rate'),
            );
        } elseif ($this->input->post('edit_tax_rate')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect("system_settings/tax_rates");
        }

        if ($this->form_validation->run() == true && $this->settings_model->updateTaxRate($id, $data)) { //check to see if we are updateing the customer
            $this->session->set_flashdata('message', lang("tax_rate_updated"));
            redirect("system_settings/tax_rates");
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

            $this->data['tax_rate'] = $this->settings_model->getTaxRateByID($id);

            $this->data['id'] = $id;
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/edit_tax_rate', $this->data);
        }
    }

    function delete_tax_rate($id = NULL)
    {
        if ($this->settings_model->deleteTaxRate($id)) {
            echo lang("tax_rate_deleted");
        }
    }

    function tax_actions()
    {

        $this->form_validation->set_rules('form_action', lang("form_action"), 'required');

        if ($this->form_validation->run() == true) {

            if (!empty($_POST['val'])) {
                if ($this->input->post('form_action') == 'delete') {
                    foreach ($_POST['val'] as $id) {
                        $this->settings_model->deleteTaxRate($id);
                    }
                    $this->session->set_flashdata('message', lang("tax_rates_deleted"));
                    redirect($_SERVER["HTTP_REFERER"]);
                }

                if ($this->input->post('form_action') == 'export_excel' || $this->input->post('form_action') == 'export_pdf') {

                    $this->load->library('excel');
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle(lang('tax_rates'));
                    $this->excel->getActiveSheet()->SetCellValue('A1', lang('name'));
                    $this->excel->getActiveSheet()->SetCellValue('B1', lang('code'));
                    $this->excel->getActiveSheet()->SetCellValue('C1', lang('tax_rate'));
                    $this->excel->getActiveSheet()->SetCellValue('D1', lang('type'));

                    $row = 2;
                    foreach ($_POST['val'] as $id) {
                        $tax = $this->settings_model->getTaxRateByID($id);
                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $tax->name);
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $tax->code);
                        $this->excel->getActiveSheet()->SetCellValue('C' . $row, $tax->rate);
                        $this->excel->getActiveSheet()->SetCellValue('D' . $row, ($tax->type == 1) ? lang('percentage') : lang('fixed'));
                        $row++;
                    }
                    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $filename = 'tax_rates_' . date('Y_m_d_H_i_s');
                    if ($this->input->post('form_action') == 'export_pdf') {
                        $styleArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
                        $this->excel->getDefaultStyle()->applyFromArray($styleArray);
                        $this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
                        require_once(APPPATH . "third_party" . DIRECTORY_SEPARATOR . "MPDF" . DIRECTORY_SEPARATOR . "mpdf.php");
                        $rendererName = PHPExcel_Settings::PDF_RENDERER_MPDF;
                        $rendererLibrary = 'MPDF';
                        $rendererLibraryPath = APPPATH . 'third_party' . DIRECTORY_SEPARATOR . $rendererLibrary;
                        if (!PHPExcel_Settings::setPdfRenderer($rendererName, $rendererLibraryPath)) {
                            die('Please set the $rendererName: ' . $rendererName . ' and $rendererLibraryPath: ' . $rendererLibraryPath . ' values' .
                                PHP_EOL . ' as appropriate for your directory structure');
                        }

                        header('Content-Type: application/pdf');
                        header('Content-Disposition: attachment;filename="' . $filename . '.pdf"');
                        header('Cache-Control: max-age=0');

                        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'PDF');
                        return $objWriter->save('php://output');
                    }
                    if ($this->input->post('form_action') == 'export_excel') {
                        header('Content-Type: application/vnd.ms-excel');
                        header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
                        header('Cache-Control: max-age=0');

                        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
                        return $objWriter->save('php://output');
                    }

                    redirect($_SERVER["HTTP_REFERER"]);
                }
            } else {
                $this->session->set_flashdata('error', lang("no_tax_rate_selected"));
                redirect($_SERVER["HTTP_REFERER"]);
            }
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }

    function customer_groups()
    {

        $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('system_settings'), 'page' => lang('system_settings')), array('link' => '#', 'page' => lang('customer_groups')));
        $meta = array('page_title' => lang('customer_groups'), 'bc' => $bc);
        $this->page_construct('settings/customer_groups', $meta, $this->data);
    }
	
	function promotion()
    {

        $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('system_settings'), 'page' => lang('system_settings')), array('link' => '#', 'page' => lang('promotion')));
        $meta = array('page_title' => lang('promotion'), 'bc' => $bc);
        $this->page_construct('settings/promotion', $meta, $this->data);
    }
	
    function getCustomerGroups()
    {

        $this->load->library('datatables');
        $this->datatables
            ->select("id, id as idd, name, percent, order_discount")
            ->from("customer_groups")
            ->add_column("Actions", "<center><a href='" . site_url('system_settings/edit_customer_group/$1') . "' class='tip' title='" . lang("edit_customer_group") . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-edit\"></i></a> <a href='#' class='tip po' title='<b>" . lang("delete_customer_group") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('system_settings/delete_customer_group/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></center>", "id");
        //->unset_column('id');

        echo $this->datatables->generate();
    }
	
	function getPromotion()
    {

        $this->load->library('datatables');
        $this->datatables
            ->select("id, description")
            ->from("promotions")
            ->add_column("Actions", "<center><a href='" . site_url('system_settings/edit_promotion/$1') . "' class='tip' title='" . lang("edit_promotion") . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-edit\"></i></a> <a href='#' class='tip po' title='<b>" . lang("delete_promotion") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('system_settings/delete_promotion/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></center>", "id");
        //->unset_column('id');

        echo $this->datatables->generate();
    }
	
    function add_customer_group()
    {

        $this->form_validation->set_rules('name', lang("group_name"), 'trim|is_unique[customer_groups.name]|required');
		$this->form_validation->set_rules('percent', lang("group_percentage"), 'required|numeric');
        $this->form_validation->set_rules('order_discount', lang("order_discount_%"), 'required|numeric');
		$mup=$this->input->post('makeup_cost');
		if($mup==""){
			$mup=0;
		}
        if ($this->form_validation->run() == true) {
            $data = array(
                'name' => $this->input->post('name'),
                'percent' => $this->input->post('percent'),
                'order_discount' => $this->input->post('order_discount'),
				'makeup_cost' => $mup
            );
			
			
			$cate_id = $this->input->post('arr_cate');
			$sub_id  = $this->input->post('arr_sub');
			$percent = $this->input->post('percent_tag');
			$cat_name = $this->input->post('arr_cate_name');
			
			for($i=0;$i<count($cate_id);$i++)
			{
				$categories[]=array('cate_id'=>$cate_id[$i],'cate_name'=>$cat_name[$i],'percent'=>$percent[$i],'sub_cate'=>$sub_id[$i]);
			}
			
			
        } elseif ($this->input->post('add_customer_group')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect("system_settings/customer_groups");
        }

        if ($this->form_validation->run() == true && $this->settings_model->addCustomerGroup($data,$categories)) {
            $this->session->set_flashdata('message', lang("customer_group_added"));
            redirect("system_settings/customer_groups");
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
			$this->data['sub_categories']   = $this->site->getAllCategoriesMakeupSub();
			$this->data['categories'] 		= $this->site->getAllCategoriesMakeup();
            $this->data['modal_js'] 		= $this->site->modal_js();
            $this->load->view($this->theme . 'settings/add_customer_group', $this->data);
        }
    }
	
	function add_promotion()
    {
		
        $this->form_validation->set_rules('description', lang("description"), 'trim|is_unique[promotions.description]|required');
		
        if ($this->form_validation->run() == true) {	
            $data = array(
				'description' => $this->input->post('description')
            );
			$cate_id = $this->input->post('arr_cate');
			$discount = $this->input->post('percent_tag');
			
			for($i=0;$i<count($cate_id);$i++)
			{
				$categories[]=array('category_id'=>$cate_id[$i],'discount'=>$discount[$i]);
			}
			//$this->erp->print_arrays($data,$categories);
			
        } elseif ($this->input->post('add_promotion')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect("system_settings/promotion");
        }

        if ($this->form_validation->run() == true && $this->settings_model->addPromotion($data,$categories)) {
            $this->session->set_flashdata('message', lang("promotion_added"));
            redirect("system_settings/promotion");
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
			$this->data['categories'] = $this->site->getAllCategoriesMakeup();
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/add_promotion', $this->data);
        }
    }
	
    function edit_customer_group($id = NULL)
    {

        $this->form_validation->set_rules('name', lang("group_name"), 'trim|required');
        $pg_details = $this->settings_model->getCustomerGroupByID($id);
        if ($this->input->post('name') != $pg_details->name) {
            $this->form_validation->set_rules('name', lang("group_name"), 'is_unique[tax_rates.name]');
        }
        $this->form_validation->set_rules('percent', lang("group_percentage"), 'required|numeric');

        if ($this->form_validation->run() == true) {

            $data = array('name' => $this->input->post('name'),
                'percent' => $this->input->post('percent'),
                'order_discount' => $this->input->post('order_discount'),
				'makeup_cost' => $this->input->post('makeup_cost')
            );
			
			$cate_id  = $this->input->post('arr_cate');
			$sub_id   = ($this->input->post('arr_sub')!=""?$this->input->post('arr_sub'):0); 
			$percent  = $this->input->post('percent_tag');
			$cat_name = $this->input->post('arr_cate_name');
			for($i=0;$i<count($cate_id);$i++)
			{
				$categories[]=array('cate_id'=>$cate_id[$i],'cate_name'=>$cat_name[$i],'percent'=>$percent[$i],'sub_cate'=>$sub_id[$i]);
			}
			
			
        } elseif ($this->input->post('edit_customer_group')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect("system_settings/customer_groups");
        }

        if ($this->form_validation->run() == true && $this->settings_model->updateCustomerGroup($id, $data,$categories)) {
            $this->session->set_flashdata('message', lang("customer_group_updated"));
            redirect("system_settings/customer_groups");
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

            $this->data['customer_group'] = $this->settings_model->getCustomerGroupByID($id);
		    $this->data['id'] = $id;
			$this->data['cate_id']	  = $this->settings_model->Old_Customer_Group($id);
			$this->data['categories'] = $this->site->getAllCategoriesMakeup();
			$this->data['sub_categories']   = $this->site->getAllCategoriesMakeupSub();
            $this->data['modal_js']   = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/edit_customer_group', $this->data);
        }
    }
	
	function edit_promotion($id = NULL)
    {

        $this->form_validation->set_rules('description', lang("description"), 'trim|required');
        $promotions = $this->settings_model->getPromotion($id);
        if ($this->input->post('promotions') != $promotions->description) {
            $this->form_validation->set_rules('promotions', lang("promotions"), 'is_unique[promotions.description]');
        }
        if ($this->form_validation->run() == true) {

            $data = array('description' => $this->input->post('description')
            );
			
			$cate_id = $this->input->post('arr_cate');
			$percent = $this->input->post('percent_tag');
			
			for($i=0;$i<count($cate_id);$i++)
			{
				$categories[]=array('category_id'=>$cate_id[$i],'discount'=>$percent[$i]);
			}
			
			
        } elseif ($this->input->post('edit_promotion')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect("system_settings/promotion");
        }

        if ($this->form_validation->run() == true && $this->settings_model->updatePromotion($id, $data,$categories)) {
            $this->session->set_flashdata('message', lang("customer_group_updated"));
            redirect("system_settings/promotion");
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

            $this->data['promotions'] = $this->settings_model->getPromotion($id);
            $this->data['id'] = $id;
			$this->data['cate_id']	  = $this->settings_model->Old_promotions($id);
			
			$this->data['categories'] = $this->site->getAllCategoriesMakeup();
            $this->data['modal_js']   = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/edit_promotion', $this->data);
        }
    }

    function delete_customer_group($id = NULL)
    {
        if ($this->settings_model->deleteCustomerGroup($id)) {
            echo lang("customer_group_deleted");
        }
    }

	function delete_promotion($id = NULL)
    {
        if ($this->settings_model->deletePromotion($id)) {
            echo lang("promotion_deleted");
        }
    }
	
    function customer_group_actions()
    {

        $this->form_validation->set_rules('form_action', lang("form_action"), 'required');

        if ($this->form_validation->run() == true) {
            if (!empty($_POST['val'])) {
                if ($this->input->post('form_action') == 'delete') {
                    foreach ($_POST['val'] as $id) {
                        $this->settings_model->deleteCustomerGroup($id);
                    }
                    $this->session->set_flashdata('message', lang("customer_groups_deleted"));
                    redirect($_SERVER["HTTP_REFERER"]);
                }

                if ($this->input->post('form_action') == 'export_excel' || $this->input->post('form_action') == 'export_pdf') {

                    $this->load->library('excel');
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle(lang('tax_rates'));
                    $this->excel->getActiveSheet()->SetCellValue('A1', lang('group_name'));
                    $this->excel->getActiveSheet()->SetCellValue('B1', lang('group_percentage'));
                    $row = 2;
                    foreach ($_POST['val'] as $id) {
                        $pg = $this->settings_model->getCustomerGroupByID($id);
                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $pg->name);
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $pg->percent);
                        $row++;
                    }
                    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $filename = 'customer_groups_' . date('Y_m_d_H_i_s');
                    if ($this->input->post('form_action') == 'export_pdf') {
                        $styleArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
                        $this->excel->getDefaultStyle()->applyFromArray($styleArray);
                        $this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
                        require_once(APPPATH . "third_party" . DIRECTORY_SEPARATOR . "MPDF" . DIRECTORY_SEPARATOR . "mpdf.php");
                        $rendererName = PHPExcel_Settings::PDF_RENDERER_MPDF;
                        $rendererLibrary = 'MPDF';
                        $rendererLibraryPath = APPPATH . 'third_party' . DIRECTORY_SEPARATOR . $rendererLibrary;
                        if (!PHPExcel_Settings::setPdfRenderer($rendererName, $rendererLibraryPath)) {
                            die('Please set the $rendererName: ' . $rendererName . ' and $rendererLibraryPath: ' . $rendererLibraryPath . ' values' .
                                PHP_EOL . ' as appropriate for your directory structure');
                        }

                        header('Content-Type: application/pdf');
                        header('Content-Disposition: attachment;filename="' . $filename . '.pdf"');
                        header('Cache-Control: max-age=0');

                        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'PDF');
                        return $objWriter->save('php://output');
                    }
                    if ($this->input->post('form_action') == 'export_excel') {
                        header('Content-Type: application/vnd.ms-excel');
                        header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
                        header('Cache-Control: max-age=0');

                        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
                        return $objWriter->save('php://output');
                    }

                    redirect($_SERVER["HTTP_REFERER"]);
                }
            } else {
                $this->session->set_flashdata('error', lang("no_customer_group_selected"));
                redirect($_SERVER["HTTP_REFERER"]);
            }
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }

    function promotion_actions()
    {
        $this->form_validation->set_rules('form_action', lang("form_action"), 'required');

        if ($this->form_validation->run() == true) {
            if (!empty($_POST['val'])) {
                if ($this->input->post('form_action') == 'delete') {
                    foreach ($_POST['val'] as $id) {
                        $this->settings_model->deletePromotion($id);
                    }
                    $this->session->set_flashdata('message', lang("promotions_deleted"));
                    redirect($_SERVER["HTTP_REFERER"]);
                }
            } else {
                $this->session->set_flashdata('error', lang("no_promotion_selected"));
                redirect($_SERVER["HTTP_REFERER"]);
            }
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }

    function warehouses()
    {

        $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('system_settings'), 'page' => lang('system_settings')), array('link' => '#', 'page' => lang('warehouses')));
        $meta = array('page_title' => lang('warehouses'), 'bc' => $bc);
        $this->page_construct('settings/warehouses', $meta, $this->data);
    }

    function getWarehouses()
    {

        $this->load->library('datatables');
        $this->datatables
            ->select("id, map,id as wid, code, name, phone, email, address")
            ->from("warehouses")
            //->edit_column("map", base_url().'assets/uploads/$1', 'map')
            ->add_column("Actions", "<div class=\"text-center\"><a href='" . site_url('system_settings/edit_warehouse/$1') . "' class='tip' title='" . lang("edit_warehouse") . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-edit\"></i></a> <a href='#' class='tip po' title='<b>" . lang("delete_warehouse") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('system_settings/delete_warehouse/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></div>", "id");
        //->unset_column('id')
        //->unset_column('map');

        echo $this->datatables->generate();
    }

    function add_warehouse()
    {
        $this->load->helper('security');
        $this->form_validation->set_rules('code', lang("code"), 'trim|is_unique[warehouses.code]|required');
        $this->form_validation->set_rules('name', lang("name"), 'required');
        $this->form_validation->set_rules('address', lang("address"), 'required');
        $this->form_validation->set_rules('userfile', lang("map_image"), 'xss_clean');

        if ($this->form_validation->run() == true) {
            if ($_FILES['userfile']['size'] > 0) {

                $this->load->library('upload');

                $config['upload_path'] = 'assets/uploads/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size'] = '1024';
                $config['max_width'] = '2000';
                $config['max_height'] = '2000';
                $config['overwrite'] = FALSE;
                $config['encrypt_name'] = TRUE;
                $config['max_filename'] = 25;
                $this->upload->initialize($config);

                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('message', $error);
                    redirect("system_settings/warehouses");
                }

                $map = $this->upload->file_name;

                $this->load->helper('file');
                $this->load->library('image_lib');
                $config['image_library'] = 'gd2';
                $config['source_image'] = 'assets/uploads/' . $map;
                $config['new_image'] = 'assets/uploads/thumbs/' . $map;
                $config['maintain_ratio'] = TRUE;
                $config['width'] = 76;
                $config['height'] = 76;

                $this->image_lib->clear();
                $this->image_lib->initialize($config);

                if (!$this->image_lib->resize()) {
                    echo $this->image_lib->display_errors();
                }
            } else {
                $map = NULL;
            }
            $data = array('code' => $this->input->post('code'),
                'name' => $this->input->post('name'),
                'phone' => $this->input->post('phone'),
                'email' => $this->input->post('email'),
                'address' => $this->input->post('address'),
                'map' => $map,
            );
        } elseif ($this->input->post('add_warehouse')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect("system_settings/warehouses");
        }

        if ($this->form_validation->run() == true && $this->settings_model->addWarehouse($data)) {
			getUserIdPermission();
            $this->session->set_flashdata('message', lang("warehouse_added"));
            redirect("system_settings/warehouses");
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/add_warehouse', $this->data);
        }
    }

    function edit_warehouse($id = NULL)
    {
        $this->load->helper('security');
        $this->form_validation->set_rules('code', lang("code"), 'trim|required');
        $wh_details = $this->settings_model->getWarehouseByID($id);
        if ($this->input->post('code') != $wh_details->code) {
            $this->form_validation->set_rules('code', lang("code"), 'is_unique[warehouses.code]');
        }
        $this->form_validation->set_rules('address', lang("address"), 'required');
        $this->form_validation->set_rules('map', lang("map_image"), 'xss_clean');

        if ($this->form_validation->run() == true) {
            $data = array('code' => $this->input->post('code'),
                'name' => $this->input->post('name'),
                'phone' => $this->input->post('phone'),
                'email' => $this->input->post('email'),
                'address' => $this->input->post('address'),
            );

            if ($_FILES['userfile']['size'] > 0) {

                $this->load->library('upload');

                $config['upload_path'] = 'assets/uploads/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size'] = '1024';
                $config['max_width'] = '2000';
                $config['max_height'] = '2000';
                $config['overwrite'] = FALSE;
                $config['encrypt_name'] = TRUE;
                $config['max_filename'] = 25;
                $this->upload->initialize($config);

                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('message', $error);
                    redirect("system_settings/warehouses");
                }

                $data['map'] = $this->upload->file_name;

                $this->load->helper('file');
                $this->load->library('image_lib');
                $config['image_library'] = 'gd2';
                $config['source_image'] = 'assets/uploads/' . $data['map'];
                $config['new_image'] = 'assets/uploads/thumbs/' . $data['map'];
                $config['maintain_ratio'] = TRUE;
                $config['width'] = 76;
                $config['height'] = 76;

                $this->image_lib->clear();
                $this->image_lib->initialize($config);

                if (!$this->image_lib->resize()) {
                    echo $this->image_lib->display_errors();
                }
            }
        } elseif ($this->input->post('edit_warehouse')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect("system_settings/warehouses");
        }

        if ($this->form_validation->run() == true && $this->settings_model->updateWarehouse($id, $data)) { 	   
			getUserIdPermission();
            $this->session->set_flashdata('message', lang("warehouse_updated"));
            redirect("system_settings/warehouses");
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

            $this->data['warehouse'] = $this->settings_model->getWarehouseByID($id);

            $this->data['id'] = $id;
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/edit_warehouse', $this->data);
        }
    }

    function delete_warehouse($id = NULL)
    {
        if ($this->settings_model->deleteWarehouse($id)) {
            echo lang("warehouse_deleted");
        }
    }

    function warehouse_actions()
    {

        $this->form_validation->set_rules('form_action', lang("form_action"), 'required');

        if ($this->form_validation->run() == true) {

            if (!empty($_POST['val'])) {
                if ($this->input->post('form_action') == 'delete') {
                    foreach ($_POST['val'] as $id) {
                        $this->settings_model->deleteWarehouse($id);
                    }
                    $this->session->set_flashdata('message', lang("warehouses_deleted"));
                    redirect($_SERVER["HTTP_REFERER"]);
                }

                if ($this->input->post('form_action') == 'export_excel' || $this->input->post('form_action') == 'export_pdf') {

                    $this->load->library('excel');
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle(lang('warehouses'));
                    $this->excel->getActiveSheet()->SetCellValue('A1', lang('code'));
                    $this->excel->getActiveSheet()->SetCellValue('B1', lang('name'));
                    $this->excel->getActiveSheet()->SetCellValue('C1', lang('phone'));
                    $this->excel->getActiveSheet()->SetCellValue('D1', lang('email'));
					$this->excel->getActiveSheet()->SetCellValue('E1', lang('address'));

                    $row = 2;
                    foreach ($_POST['val'] as $id) {
                        $wh = $this->settings_model->getWarehouseByID($id);
                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $wh->code);
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $wh->name);
                        $this->excel->getActiveSheet()->SetCellValue('C' . $row, $wh->phone);
                        $this->excel->getActiveSheet()->SetCellValue('D' . $row, $wh->email);
						$this->excel->getActiveSheet()->SetCellValue('E' . $row, $wh->address);
                        $row++;
                    }
                    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
                    $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $filename = 'warehouses_' . date('Y_m_d_H_i_s');
                    if ($this->input->post('form_action') == 'export_pdf') {
                        $styleArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
                        $this->excel->getDefaultStyle()->applyFromArray($styleArray);
                        $this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
                        require_once(APPPATH . "third_party" . DIRECTORY_SEPARATOR . "MPDF" . DIRECTORY_SEPARATOR . "mpdf.php");
                        $rendererName = PHPExcel_Settings::PDF_RENDERER_MPDF;
                        $rendererLibrary = 'MPDF';
                        $rendererLibraryPath = APPPATH . 'third_party' . DIRECTORY_SEPARATOR . $rendererLibrary;
                        if (!PHPExcel_Settings::setPdfRenderer($rendererName, $rendererLibraryPath)) {
                            die('Please set the $rendererName: ' . $rendererName . ' and $rendererLibraryPath: ' . $rendererLibraryPath . ' values' .
                                PHP_EOL . ' as appropriate for your directory structure');
                        }

                        header('Content-Type: application/pdf');
                        header('Content-Disposition: attachment;filename="' . $filename . '.pdf"');
                        header('Cache-Control: max-age=0');

                        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'PDF');
                        return $objWriter->save('php://output');
                    }
                    if ($this->input->post('form_action') == 'export_excel') {
                        header('Content-Type: application/vnd.ms-excel');
                        header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
                        header('Cache-Control: max-age=0');

                        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
                        return $objWriter->save('php://output');
                    }

                    redirect($_SERVER["HTTP_REFERER"]);
                }
            } else {
                $this->session->set_flashdata('error', lang("no_warehouse_selected"));
                redirect($_SERVER["HTTP_REFERER"]);
            }
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }

    function variants()
    {

        $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('system_settings'), 'page' => lang('system_settings')), array('link' => '#', 'page' => lang('variants')));
        $meta = array('page_title' => lang('variants'), 'bc' => $bc);
        $this->page_construct('settings/variants', $meta, $this->data);
    }
	
	function categories_note()
    {

        $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('system_settings'), 'page' => lang('system_settings')), array('link' => '#', 'page' => lang('categories_note')));
        $meta = array('page_title' => lang('categories_note'), 'bc' => $bc);
        $this->page_construct('settings/categories_note', $meta, $this->data);
    }
	
    function getVariants()
    {

        $this->load->library('datatables');
        $this->datatables
            ->select("id,id AS idd, name")
            ->from("variants")
            ->add_column("Actions", "<div class=\"text-center\"><a href='" . site_url('system_settings/edit_variant/$1') . "' class='tip' title='" . lang("edit_variant") . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-edit\"></i></a> <a href='#' class='tip po' title='<b>" . lang("delete_variant") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('system_settings/delete_variant/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></div>", "id");
        //->unset_column('id');

        echo $this->datatables->generate();
    }
	
	function getCategoriesNote()
    {

        $this->load->library('datatables');
        $this->datatables
            ->select("id,id AS idd, description")
            ->from("categories_note")
            ->add_column("Actions", "<div class=\"text-center\"><a href='" . site_url('system_settings/edit_categories_note/$1') . "' class='tip' title='" . lang("edit_categories_note") . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-edit\"></i></a> <a href='#' class='tip po' title='<b>" . lang("delete_categories_note") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('system_settings/delete_categories_note/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></div>", "id");
        //->unset_column('id');

        echo $this->datatables->generate();
    }
    
	function add_variant()
    {

        $this->form_validation->set_rules('name', lang("name"), 'trim|is_unique[variants.name]|required');

        if ($this->form_validation->run() == true) {
            $data = array('name' => $this->input->post('name'));
        } elseif ($this->input->post('add_variant')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect("system_settings/variants");
        }

        if ($this->form_validation->run() == true && $this->settings_model->addVariant($data)) {
            $this->session->set_flashdata('message', lang("variant_added"));
            redirect("system_settings/variants");
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/add_variant', $this->data);
        }
    }
	
	function add_categories_note()
    {

        $this->form_validation->set_rules('description', lang("description"), 'trim|is_unique[categories_note.description]|required');

        if ($this->form_validation->run() == true) {
            $data = array('description' => $this->input->post('description'));
        } elseif ($this->input->post('add_categories_note')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect("system_settings/categories_note");
        }

        if ($this->form_validation->run() == true && $this->settings_model->addCategoriesNote($data)) {
            $this->session->set_flashdata('message', lang("categories_note_added"));
            redirect("system_settings/categories_note");
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/add_categories_note', $this->data);
        }
    }
   
    function edit_variant($id = NULL)
    {

        $this->form_validation->set_rules('name', lang("name"), 'trim|required');
        $tax_details = $this->settings_model->getVariantByID($id);
        if ($this->input->post('name') != $tax_details->name) {
            $this->form_validation->set_rules('name', lang("name"), 'is_unique[variants.name]');
        }

        if ($this->form_validation->run() == true) {
            $data = array('name' => $this->input->post('name'));
        } elseif ($this->input->post('edit_variant')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect("system_settings/variants");
        }

        if ($this->form_validation->run() == true && $this->settings_model->updateVariant($id, $data)) {
            $this->session->set_flashdata('message', lang("variant_updated"));
            redirect("system_settings/variants");
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['variant'] = $tax_details;
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/edit_variant', $this->data);
        }
    }
	
	function edit_categories_note($id = NULL)
    {

        $this->form_validation->set_rules('description', lang("description"), 'trim|required');
        $categories_note = $this->settings_model->getCategoriesNoteByID($id);
        if ($this->input->post('description') != $categories_note->description) {
            $this->form_validation->set_rules('description', lang("description"), 'is_unique[categories_note.description]');
        }

        if ($this->form_validation->run() == true) {
            $data = array('description' => $this->input->post('description'));
        } elseif ($this->input->post('edit_categories_note')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect("system_settings/categories_note");
        }

        if ($this->form_validation->run() == true && $this->settings_model->updateCategoryNote($id, $data)) {
            $this->session->set_flashdata('message', lang("categories_note_updated"));
            redirect("system_settings/categories_note");
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['categories_note'] = $categories_note;
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/edit_categories_note', $this->data);
        }
    }
    
	function delete_variant($id = NULL)
    {
        if ($this->settings_model->deleteVariant($id)) {
            echo lang("variant_deleted");
        }
    }
	
	function delete_categories_note($id = NULL)
    {
        if ($this->settings_model->deleteCategoriesNote($id)) {
            echo lang("categories_note_deleted");
        }
    }
	
	public function edit_bom($id = null)
    {
		$this->erp->checkPermissions();
		$this->form_validation->set_rules('name', lang("name"), 'required');
		$id_convert_item = 0;
        if ($this->form_validation->run() == true) {
			$warehouse_id        = $_POST['warehouse'];
            // list bom item from
            $cIterm_from_id     = $_POST['bom_from_items_id'];
            $cIterm_from_code   = $_POST['bom_from_items_code'];
            $cIterm_from_name   = $_POST['bom_from_items_name'];
            $cIterm_from_uom    = $_POST['bom_from_items_uom'];
            $cIterm_from_qty    = $_POST['bom_from_items_qty'];
            // list convert item to
            $iterm_to_id        = $_POST['convert_to_items_id'];
            $iterm_to_code      = $_POST['convert_to_items_code'];
            $iterm_to_name      = $_POST['convert_to_items_name'];
            $iterm_to_uom      	= $_POST['convert_to_items_uom'];
            $iterm_to_qty       = $_POST['convert_to_items_qty'];
			
			$date = date("Y-m-d H:i:s", strtotime($_POST['date']));
            $data               = array(
                                        'name' => $_POST['name'],
                                        'date' => $date,
										'noted' => $_POST['note'],
                                        'created_by' => $this->session->userdata('user_id')
                                    );
			
            $idConvert          = $this->settings_model->updateBom($id, $data);
			$id_convert_item 	= $idConvert;
				
            $items = array();
            $i = isset($_POST['bom_from_items_code']) ? sizeof($_POST['bom_from_items_code']) : 0;
            for ($r = 0; $r < $i; $r++) {
                $bomitem = array(
							'bom_id' 		=> $id,
							'product_id' 	=> $cIterm_from_id[$r],
							'product_code' 	=> $cIterm_from_code[$r],
							'product_name' 	=> $cIterm_from_name[$r],
							'quantity'		=> $cIterm_from_qty[$r],
							'option_id'		=> $cIterm_from_uom[$r],
							'status' 		=> 'deduct'
						);
						
				$pic = $this->settings_model->selectBomItems($id, $cIterm_from_id[$r]);
				if($pic){
					$this->settings_model->deleteBom_items($id);
				}
				$this->settings_model->updateBom_items($bomitem);
			}
            $j = isset($_POST['convert_to_items_code']) ? sizeof($_POST['convert_to_items_code']) : 0;
            for ($r = 0; $r < $j; $r++) {
                $bomitems = array(
							'bom_id' 		=> $id,
							'product_id' 	=> $iterm_to_id[$r],
							'product_code' 	=> $iterm_to_code[$r],
							'product_name' 	=> $iterm_to_name[$r],
							'quantity' 		=> $iterm_to_qty[$r],
							'option_id'		=> $iterm_to_uom[$r],
							'status' 		=> 'add'
						);
				$pic = $this->settings_model->selectBomItems($id, $iterm_to_id[$r]);
				if($pic){
					$this->settings_model->deleteBom_items($id);
				}
				$this->settings_model->updateBom_items($bomitems);
			}
			
			if($id_convert_item != 0){
				$items 			= $this->settings_model->getConvertItemsById($id);
				$deduct 		= $this->settings_model->getConvertItemsDeduct($id);
				$adds 			= $this->settings_model->getConvertItemsAdd($id);
				$each_cost 		= 0;
				$total_item 	= count($adds);
				$total_fin_qty  = 0;
				$total_fin_cost = 0;
				$total_raw_cost = 0;
				$cost_variant   = 0;
				$qty_variant	= 0;
				
				foreach($items as $item){
					$option = $this->site->getProductVariantByOptionID($item->option_id);
					$cost 	= 0;
					$Tcost 	= 0;
					if($item->status == 'deduct'){
						$cost = $item->tcost?$item->tcost:$item->tprice;
						if($option){
							$cost_variant   = ($cost / $item->c_quantity)*$option->qty_unit;
							$qty_variant	= $item->c_quantity;
							$total_raw_cost += $cost_variant * $qty_variant;
							$Tcost = $cost * $option->qty_unit;
						}else{
							$total_raw_cost += $cost;
							$Tcost = $cost;
						}
						
						$this->db->update('bom_items', array('cost' => $cost_variant), array('product_id' => $item->product_id, 'bom_id' => $item->bom_id));
					}else{
						$cost = $item->tcost?$item->tcost:$item->tprice;
						if($option){
							$total_fin_cost += $cost * $option->qty_unit;
							$total_fin_qty  += $item->c_quantity * $option->qty_unit;
						}else{
							$total_fin_cost += $cost;
							$total_fin_qty  += $item->c_quantity;
						}
						
					}
				}
				
				//============= Cost AVG =============//	
				foreach($adds as $add){
					$qty_unit 	= 0;
					$option 	= $this->site->getProductVariantByOptionID($add->option_id);
					
					if($option){
						$unit_qty 	= $add->c_quantity * $option->qty_unit;
					}else{
						$unit_qty 	= $add->c_quantity;
					}
					
					$each_cost 	= $this->site->calculateCONAVCost($add->product_id, $total_raw_cost, $total_fin_qty, $unit_qty);
					
					$this->db->update('bom_items', array('cost' => ($each_cost['cost']/$add->c_quantity)), array('product_id' => $add->product_id, 'bom_id' => $add->bom_id));
				}
				
			}
			
            $this->session->set_flashdata('message', lang("bom_success_update"));
            redirect('system_settings/bom');
        }
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['warehouses'] = $this->site->getAllWarehouses();
        $this->data['tax_rates'] = $this->site->getAllTaxRates();
		$this->data['all_bom'] = $this->site->getAllBom($id);
		$this->data['top_bom'] = $this->site->getBom_itemsTop($id);
		$this->data['bottom_bom'] = $this->site->getBom_itemsBottom($id);
		$this->data['id'] = $id;
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('Edit_Bom')));
        $meta = array('page_title' => lang('Edit_Bom'), 'bc' => $bc);
        $this->page_construct('settings/edit_bom', $meta, $this->data);
	}
	
	public function delete_bom($id = null)
    {
        $this->erp->checkPermissions('delete', true);

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        if ($this->settings_model->deleteBom($id) && $this->settings_model->deleteBom_items($id)) {
            echo lang("bom_deleted");
        }
    }
	
	function bom_convert()
    {
        $this->erp->checkPermissions();
        $this->form_validation->set_rules('name', lang("name"), 'required');
		$id_convert_item = 0;
        if ($this->form_validation->run() == true) {
			$warehouse_id        = $_POST['warehouse'];
            // list convert item from
            $cIterm_from_id     = $_POST['bom_from_items_id'];
            $cIterm_from_code   = $_POST['bom_from_items_code'];
            $cIterm_from_name   = $_POST['bom_from_items_name'];
            $cIterm_from_uom    = $_POST['bom_from_items_uom'];
            $cIterm_from_qty    = $_POST['bom_from_items_qty'];
            // list convert item to
            $iterm_to_id        = $_POST['convert_to_items_id'];
            $iterm_to_code      = $_POST['convert_to_items_code'];
            $iterm_to_name      = $_POST['convert_to_items_name'];
            $iterm_to_uom      = $_POST['convert_to_items_uom'];
            $iterm_to_qty       = $_POST['convert_to_items_qty'];
			$date = $this->erp->fld(trim($_POST['date']));
            $data               = array(
                                        'name' => $_POST['name'],
                                        'date' => $date,
										'noted' => $_POST['note'],
                                        'created_by' => $this->session->userdata('user_id')
                                    );
			$idConvert          = $this->settings_model->insertBom($data);
			$id_convert_item = $idConvert;
				
            $items = array();
            $i = isset($_POST['bom_from_items_code']) ? sizeof($_POST['bom_from_items_code']) : 0;
            for ($r = 0; $r < $i; $r++) {
                $products   = $this->site->getProductByID($cIterm_from_id[$r]);
                if(!empty($cIterm_from_uom[$r])){
                    $product_variant    	= $this->site->getProductVariantByID($cIterm_from_id[$r], $cIterm_from_uom[$r]);
                }else{
                    $product_variant        = $this->site->getProductVariantByID($cIterm_from_id[$r]);
					
                }
                $PurchaseItemsQtyBalance    =  $this->site->getPurchaseBalanceQuantity($cIterm_from_id[$r], $warehouse_id);
				if(empty($product_variant)){
					$unit_qty = 1;
				}else{
					$unit_qty = ( !empty($product_variant->qty_unit) && $product_variant->qty_unit > 0 ? $product_variant->qty_unit : 1 );
				}
                $PurchaseItemsQtyBalance    = $PurchaseItemsQtyBalance - ($unit_qty  * $cIterm_from_qty[$r]);
                $qtyBalace                  = $product_variant->quantity - $cIterm_from_qty[$r];
				
				$purchase_items_id = 0;
				$pis = $this->site->getPurchasedItems($cIterm_from_id[$r], $warehouse_id, $option_id = NULL);
				foreach ($pis as $pi) {
					$purchase_items_id = $pi->id;
					break;
				}

				$clause = array('purchase_id' => NULL, 'product_code' => $cIterm_from_code[$r], 'product_id' => $cIterm_from_id[$r], 'warehouse_id' => $warehouse_id);
				if ($pis) {
					$this->db->update('purchase_items', array('quantity_balance' => $PurchaseItemsQtyBalance), array('id' => $purchase_items_id));
				} else {
					$clause['quantity'] = 0;
					$clause['item_tax'] = 0;
					$clause['option_id'] = null;
					$clause['transfer_id'] = null;
					$clause['product_name'] = $cIterm_from_name[$r];
					$clause['quantity_balance'] = $PurchaseItemsQtyBalance;
					$this->db->insert('purchase_items', $clause);
				}
                // UPDATE PRODUCT QUANTITY
				
                if($this->db->update('products', array('quantity' => $PurchaseItemsQtyBalance), array('code' => $cIterm_from_code[$r])))
				{
					// UPDATE WAREHOUSE_PRODUCT QUANTITY
					if ($this->site->getWarehouseProducts( $cIterm_from_id[$r], $warehouse_id)) {
						$this->db->update('warehouses_products', array('quantity' => $PurchaseItemsQtyBalance), array('product_id' => $cIterm_from_id[$r], 'warehouse_id' => $warehouse_id));
					} else {
						$this->db->insert('warehouses_products', array('quantity' => $PurchaseItemsQtyBalance, 'product_id' => $cIterm_from_id[$r], 'warehouse_id' => $warehouse_id));
					}
					// UPDATE PRODUCT_VARIANT quantity
					if(!empty($cIterm_from_uom[$r])){
						$this->db->update('product_variants', array('quantity' => $qtyBalace), array('product_id' => $cIterm_from_id[$r], 'name' => $cIterm_from_uom[$r]));
					}else{
						$this->db->update('product_variants', array('quantity' => $qtyBalace), array('product_id' => $cIterm_from_id[$r]));
					}
				} else {
					exit('error - product');
				}
			    
				//echo '<pre>';print_r($arry);echo '</pre>';exit;			
                $this->db->insert('erp_bom_items',  array(
                                                        'bom_id' => $idConvert,
                                                        'product_id' => $cIterm_from_id[$r],
                                                        'product_code' => $cIterm_from_code[$r],
                                                        'product_name' => $cIterm_from_name[$r],
                                                        'quantity' => $cIterm_from_qty[$r],
                                                        'status' => 'deduct'));
								
				//$this->site->syncQuantity(NULL, $purchase_items_id);
				$this->site->syncQuantity(NULL, NULL, NULL, $cIterm_from_id[$r]);
            }
            $j = isset($_POST['convert_to_items_code']) ? sizeof($_POST['convert_to_items_code']) : 0;
            for ($r = 0; $r < $j; $r++) {
                $products   = $this->site->getProductByID($iterm_to_id[$r]);
                if(!empty($cIterm_from_uom[$r])){
                    $product_variant        = $this->site->getProductVariantByID($iterm_to_id[$r], $iterm_to_uom[$r]);
                }else{
                    $product_variant        = $this->site->getProductVariantByID($iterm_to_id[$r]);
                }

                $PurchaseItemsQtyBalance    =  $this->site->getPurchaseBalanceQuantity($iterm_to_id[$r], $warehouse_id);
                $unit_qty = ( !empty($product_variant->qty_unit) && $product_variant->qty_unit > 0 ? $product_variant->qty_unit : 1 );
                $PurchaseItemsQtyBalance    = $PurchaseItemsQtyBalance + ($unit_qty  * $iterm_to_qty[$r]);
                $qtyBalace                  = $product_variant->quantity + $iterm_to_qty[$r];
				
                $purchase_items_id = 0;
				$pis = $this->site->getPurchasedItems($iterm_to_id[$r], $warehouse_id, $option_id = NULL);
				foreach ($pis as $pi) {
					$purchase_items_id = $pi->id;
					break;
				}
				$clause = array('purchase_id' => NULL, 'product_code' => $iterm_to_code[$r], 'product_id' => $iterm_to_id[$r], 'warehouse_id' => $warehouse_id);
				if ($pis) {
					$this->db->update('purchase_items', array('quantity_balance' => $PurchaseItemsQtyBalance), array('id' => $purchase_items_id));
				} else {
					$clause['quantity'] = 0;
					$clause['item_tax'] = 0;
					$clause['option_id'] = null;
					$clause['transfer_id'] = null;
					$clause['product_name'] = $iterm_to_name[$r];
					$clause['quantity_balance'] = $PurchaseItemsQtyBalance;
					$this->db->insert('purchase_items', $clause);
				}
                // UPDATE PRODUCT QUANTITY
				
                if($this->db->update('products', array('quantity' => $PurchaseItemsQtyBalance), array('code' => $iterm_to_code[$r])))
				{
					// UPDATE WAREHOUSE_PRODUCT QUANTITY
					if ($this->site->getWarehouseProducts($iterm_to_id[$r], $warehouse_id)) {
						$this->db->update('warehouses_products', array('quantity' => $PurchaseItemsQtyBalance), array('product_id' => $iterm_to_id[$r], 'warehouse_id' => $warehouse_id));
					} else {
						$this->db->insert('warehouses_products', array('quantity' => $PurchaseItemsQtyBalance, 'product_id' => $iterm_to_id[$r], 'warehouse_id' => $warehouse_id));
					}
					// UPDATE PRODUCT_VARIANT quantity
					if(!empty($cIterm_from_uom[$r])){
						$this->db->update('product_variants', array('quantity' => $qtyBalace), array('product_id' => $iterm_to_id[$r], 'name' => $iterm_to_uom[$r]));
					}else{
						$this->db->update('product_variants', array('quantity' => $qtyBalace), array('product_id' => $iterm_to_id[$r]));
					}
				} else {
					exit('error increase product ');
				}
				
                $this->db->insert('erp_bom_items', array(
                                                        'bom_id' => $idConvert,
                                                        'product_id' => $iterm_to_id[$r],
                                                        'product_code' => $iterm_to_code[$r],
                                                        'product_name' => $iterm_to_name[$r],
                                                        'quantity' => $iterm_to_qty[$r],
                                                        'status' => 'add'));
				
				//$this->site->syncQuantity(NULL, $purchase_items_id);
				$this->site->syncQuantity(NULL, NULL, NULL, $cIterm_from_id[$r]);
            }
			if($id_convert_item != 0){
				$items = $this->settings_model->getConvertItemsById($id_convert_item);
				$deduct = $this->settings_model->getConvertItemsDeduct($id_convert_item);
				$adds = $this->settings_model->getConvertItemsAdd($id_convert_item);
				$each_cost = 0;
				$total_item = count($adds);
				
				foreach($items as $item){
					if($item->status == 'deduct'){
						$this->db->update('bom_items', array('cost' => $item->tcost), array('product_id' => $item->product_id, 'bom_id' => $item->bom_id));
					}else{
						$each_cost = $deduct->tcost / $total_item;
						if($this->db->update('bom_items', array('cost' => $each_cost), array('product_id' => $item->product_id, 'bom_id' => $item->bom_id))){
							
							//foreach($adds as $add){
								$total_net_unit_cost = $each_cost / $item->c_quantity;
								//$total_quantity += $each_cost;
								//$total_unit_cost += ($pi->unit_cost ? ($pi->unit_cost *  $pi->quantity_balance) : ($pi->net_unit_cost + ($pi->item_tax / $pi->quantity) *  $pi->quantity_balance));
							//}
							//$avg_net_unit_cost = $total_net_unit_cost / $total_quantity;
							//$avg_unit_cost = $total_unit_cost / $total_quantity;

							//$cost2 = $each_cost * $item->p_cost;
							
							//$product_cost = ($total_net_unit_cost + $cost2) / $total_quantity;
							$this->db->update('products', array('cost' => $total_net_unit_cost), array('id' => $item->product_id));
						}
					}
				}
			}
			
            $this->session->set_flashdata('message', lang("item_conitem_convert_success"));
            redirect('system_settings/bom');
        }
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['warehouses'] = $this->site->getAllWarehouses();
        $this->data['tax_rates'] = $this->site->getAllTaxRates();
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('system_settings')));
        $meta = array('page_title' => lang('bom'), 'bc' => $bc);
        $this->page_construct('system_settings/bom', $meta, $this->data);
    }
	
	function bom(){
		$bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('list_bom')));
        $meta = array('page_title' => lang('list_bom'), 'bc' => $bc);
        $this->page_construct('settings/list_bom', $meta, $this->data);
	}
	
	function add_bom(){
		$this->erp->checkPermissions();
        $this->form_validation->set_rules('name', lang("name"), 'required');
		$id_convert_item = 0;
        if ($this->form_validation->run() == true) {
			//$warehouse_id       = $_POST['warehouse'];
            // list convert item from
            $cIterm_from_id     = $_POST['bom_from_items_id'];
            $cIterm_from_code   = $_POST['bom_from_items_code'];
            $cIterm_from_name   = $_POST['bom_from_items_name'];
            $cIterm_from_uom    = $_POST['bom_from_items_uom'];
            $cIterm_from_qty    = $_POST['bom_from_items_qty'];
            // list convert item to
            $iterm_to_id        = $_POST['convert_to_items_id'];
            $iterm_to_code      = $_POST['convert_to_items_code'];
            $iterm_to_name      = $_POST['convert_to_items_name'];
            $iterm_to_uom      	= $_POST['convert_to_items_uom'];
            $iterm_to_qty       = $_POST['convert_to_items_qty'];
			
			$date 				= $this->erp->fld(trim($_POST['date']));
            $data               = array(
									'name' 			=> $_POST['name'],
									'date' 			=> $date,
									'noted' 		=> $_POST['note'],
									'created_by' 	=> $this->session->userdata('user_id')
								);
			$idConvert          =  $this->settings_model->insertBom($data);
			$id_convert_item 	=  $idConvert;
				
            $items = array();
			
            $i = isset($_POST['bom_from_items_code']) ? sizeof($_POST['bom_from_items_code']) : 0;
            for ($r = 0; $r < $i; $r++) {			    			
                $this->db->insert('erp_bom_items',  array(
									'bom_id' 		=> $idConvert,
									'product_id' 	=> $cIterm_from_id[$r],
									'product_code' 	=> $cIterm_from_code[$r],
									'product_name' 	=> $cIterm_from_name[$r],
									'quantity' 		=> $cIterm_from_qty[$r],
									'option_id'		=> $cIterm_from_uom[$r],
									'status' 		=> 'deduct'
								));
            }
			
            $j = isset($_POST['convert_to_items_code']) ? sizeof($_POST['convert_to_items_code']) : 0;
            for ($r = 0; $r < $j; $r++) {
                $this->db->insert('erp_bom_items', array(
									'bom_id' 		=> $idConvert,
									'product_id' 	=> $iterm_to_id[$r],
									'product_code' 	=> $iterm_to_code[$r],
									'product_name' 	=> $iterm_to_name[$r],
									'quantity' 		=> $iterm_to_qty[$r],
									'option_id'		=> $iterm_to_uom[$r],
									'status' 		=> 'add'
								));
				
            }
			
			if($id_convert_item != 0){
				$items 		= $this->settings_model->getConvertItemsById($id_convert_item);
				$deduct 	= $this->settings_model->getConvertItemsDeduct($id_convert_item);
				$adds 		= $this->settings_model->getConvertItemsAdd($id_convert_item);
				$each_cost 		= 0;
				$total_item 	= count($adds);
				$total_fin_qty  = 0;
				$total_fin_cost = 0;
				$total_raw_cost = 0;
				$cost_variant   = 0;
				$qty_variant	= 0;
				
				foreach($items as $item){
					$option = $this->site->getProductVariantByOptionID($item->option_id);
					$cost = 0;
					$Tcost = 0;
					if($item->status == 'deduct'){
						$cost = $item->tcost?$item->tcost:$item->tprice;
						if($option){
							$cost_variant   = ($cost / $item->c_quantity)*$option->qty_unit;
							$qty_variant	= $item->c_quantity;
							$total_raw_cost += $cost_variant * $qty_variant;
							$Tcost = $cost * $option->qty_unit;
						}else{
							$total_raw_cost += $cost;
							$cost_variant   = $cost;
							$Tcost = $cost;
						}
						
						$this->db->update('bom_items', array('cost' => $cost_variant), array('product_id' => $item->product_id, 'bom_id' => $item->bom_id));
					}else{
						$cost = $item->tcost?$item->tcost:$item->tprice;
						if($option){
							$total_fin_cost += $cost * $option->qty_unit;
							$total_fin_qty  += $item->c_quantity * $option->qty_unit;
						}else{
							$total_fin_cost += $cost;
							$total_fin_qty  += $item->c_quantity;
						}
						
					}
				}
				
				//============= Cost AVG =============//	
				foreach($adds as $add){
					$qty_unit 	= 0;
					$option 	= $this->site->getProductVariantByOptionID($add->option_id);
					
					if($option){
						$unit_qty 	= $add->c_quantity * $option->qty_unit;
					}else{
						$unit_qty 	= $add->c_quantity;
					}
					//echo $total_raw_cost .'=='.$total_fin_qty .'=='.$unit_qty;
					$each_cost 	= $this->site->calculateCONAVCost($add->product_id, $total_raw_cost, $total_fin_qty, $unit_qty);
					
					$this->db->update('bom_items', array('cost' => ($each_cost['cost']/$add->c_quantity)), array('product_id' => $add->product_id, 'bom_id' => $add->bom_id));
				}
			}
			
            $this->session->set_flashdata('message', lang("bom_added"));
            redirect('system_settings/bom');
        }
        $this->data['error'] 		= (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['warehouses'] 	= $this->site->getAllWarehouses();
        $this->data['tax_rates'] 	= $this->site->getAllTaxRates();
        $bc = array(array('link' 	=> base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('system_settings')));
        $meta = array('page_title' 	=> lang('bom'), 'bc' => $bc);
        $this->page_construct('settings/bom', $meta, $this->data);
	}
	
	function suggestions()
    {
        $term = $this->input->get('term', TRUE);
        if (strlen($term) < 1 || !$term) {
            die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . site_url('welcome') . "'; }, 10);</script>");
        }
        $rows = $this->settings_model->getProductNames($term);
        if ($rows) {
            $uom = array();
            foreach ($rows as $row) {
				$options = $this->products_model->getProductOptions($row->id);
				
                $pr[] = array('id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'uom' => $uom, 'code' => $row->code, 'name' => $row->name, 'price' => $row->price, 'qty' => 1, 'cost' => $row->cost, 'options' => $options );
            }
			
            echo json_encode($pr);
        } else {
            echo json_encode(array(array('id' => 0, 'label' => lang('no_match_found'), 'value' => $term)));
        }
    }

	public function bom_note($id = null)
    {
        $bom = $this->settings_model->getBOmByIDs($id);
        foreach($bom as $b){
            $this->data['user'] = $this->site->getUser($b['created_by']);
        }
        $this->data['bom'] = $bom;
        $this->data['page_title'] = $this->lang->line("expense_note");
        $this->load->view($this->theme . 'settings/bom_note', $this->data);
    }
	
	public function getListBom()
    {
        $this->erp->checkPermissions();

        $detail_link = anchor('system_settings/bom_note/$1', '<i class="fa fa-file-text-o"></i> ' . lang('product_analysis'), 'data-toggle="modal" data-target="#myModal2"');
        $edit_link = anchor('system_settings/edit_bom/$1', '<i class="fa fa-edit"></i> ' . lang('edit_bom'));
        $delete_link = "<a href='#' class='po' title='<b>" . $this->lang->line("delete_bom") . "</b>' data-content=\"<p>"
        . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('system_settings/delete_bom/$1') . "'>"
        . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i> "
        . lang('delete_bom') . "</a>";
        $action = '<div class="text-center"><div class="btn-group text-left">'
        . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
        . lang('actions') . ' <span class="caret"></span></button>
			<ul class="dropdown-menu pull-right" role="menu">
				<li>' . $detail_link . '</li>
				<li>' . $edit_link . '</li>
				<li>' . $delete_link . '</li>
			</ul>
		</div></div>';

        $this->load->library('datatables');

        $this->datatables
            ->select($this->db->dbprefix('bom') . ".id as id,
					".$this->db->dbprefix('bom').".date AS Date, 
					".$this->db->dbprefix('bom').".name AS Name, 
					SUM(".$this->db->dbprefix('bom_items').".quantity) AS Quantity, 
					".$this->db->dbprefix('bom').".noted AS Note, 
					" . $this->db->dbprefix('users') . ".username", false)
            ->from('bom')
            ->join('users', 'users.id=bom.created_by', 'left')
			->join('bom_items', 'bom_items.bom_id = bom.id')
			->where('bom_items.status','add')
            ->group_by('bom_items.bom_id');
        if (!$this->Owner && !$this->Admin) {
            $this->datatables->where('created_by', $this->session->userdata('user_id'));
        }
        //$this->datatables->edit_column("attachment", $attachment_link, "attachment");
        $this->datatables->add_column("Actions", $action, "id");
        echo $this->datatables->generate();
    }
	
	public function expense_actions()
    {
        if (!$this->Owner) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER["HTTP_REFERER"]);
        }

        $this->form_validation->set_rules('form_action', lang("form_action"), 'required');
		
        if ($this->form_validation->run() == true) {
			
            if (!empty($_POST['val'])) {
                if ($this->input->post('form_action') == 'delete') {
                    foreach ($_POST['val'] as $id) {
                        $this->settings_model->deleteBom($id);
						$this->settings_model->deleteBom_items($id);
                    }
                    $this->session->set_flashdata('message', $this->lang->line("expenses_deleted"));
                    redirect($_SERVER["HTTP_REFERER"]);
                }
				
                if ($this->input->post('form_action') == 'export_excel' || $this->input->post('form_action') == 'export_pdf') {

                    $this->load->library('excel');
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle(lang('Bom'));
                    $this->excel->getActiveSheet()->SetCellValue('A1', lang('date'));
                    $this->excel->getActiveSheet()->SetCellValue('B1', lang('name'));
                    $this->excel->getActiveSheet()->SetCellValue('C1', lang('quantity'));
                    $this->excel->getActiveSheet()->SetCellValue('D1', lang('cost'));
                    $this->excel->getActiveSheet()->SetCellValue('E1', lang('noted'));
					$this->excel->getActiveSheet()->SetCellValue('F1', lang('created_by'));

                    $row = 2;
                    foreach ($_POST['val'] as $id) {
                        $bom = $this->settings_model->getBomByID($id);
                        $user = $this->site->getUser($bom->created_by);
                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $this->erp->hrld($bom->date));
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $bom->name);
                        $this->excel->getActiveSheet()->SetCellValue('C' . $row, $this->erp->formatMoneyPurchase($bom->qty));
						$this->excel->getActiveSheet()->SetCellValue('D' . $row, $this->erp->formatMoneyPurchase($bom->cost));
                        $this->excel->getActiveSheet()->SetCellValue('E' . $row, $bom->noted);
                        $this->excel->getActiveSheet()->SetCellValue('F' . $row, $user->first_name . ' ' . $user->last_name);
                        $row++;
                    }

                    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(35);
                    $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
					$this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $filename = 'Bom_' . date('Y_m_d_H_i_s');
                    if ($this->input->post('form_action') == 'export_pdf') {
                        $styleArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
                        $this->excel->getDefaultStyle()->applyFromArray($styleArray);
                        $this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
                        require_once APPPATH . "third_party" . DIRECTORY_SEPARATOR . "MPDF" . DIRECTORY_SEPARATOR . "mpdf.php";
                        $rendererName = PHPExcel_Settings::PDF_RENDERER_MPDF;
                        $rendererLibrary = 'MPDF';
                        $rendererLibraryPath = APPPATH . 'third_party' . DIRECTORY_SEPARATOR . $rendererLibrary;
                        if (!PHPExcel_Settings::setPdfRenderer($rendererName, $rendererLibraryPath)) {
                            die('Please set the $rendererName: ' . $rendererName . ' and $rendererLibraryPath: ' . $rendererLibraryPath . ' values' .
                                PHP_EOL . ' as appropriate for your directory structure');
                        }

                        header('Content-Type: application/pdf');
                        header('Content-Disposition: attachment;filename="' . $filename . '.pdf"');
                        header('Cache-Control: max-age=0');

                        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'PDF');
                        return $objWriter->save('php://output');
                    }
                    if ($this->input->post('form_action') == 'export_excel') {
                        header('Content-Type: application/vnd.ms-excel');
                        header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
                        header('Cache-Control: max-age=0');

                        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
                        return $objWriter->save('php://output');
                    }

                    redirect($_SERVER["HTTP_REFERER"]);
                }
            } else {
                $this->session->set_flashdata('error', $this->lang->line("no_expense_selected"));
                redirect($_SERVER["HTTP_REFERER"]);
            }
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
    
	}
	
	public function updateRoom()
	{
		$id = $this->input->post('id_suspend');
		$data = array('floor' => $this->input->post('floor'),
					  'name' => $this->input->post('name'),
					  'ppl_number' => $this->input->post('people'),
					  'description' => $this->input->post('description'),
					  'inactive' => $this->input->post('inactive'),
					  'warehouse_id' => $this->input->post('warehouse')
					);
		//$this->erp->print_arrays($data);
		$this->settings_model->updateRooms($id, $data);
		$this->session->set_flashdata('message', $this->lang->line("accound_updated"));
        redirect('system_settings/suspend');	
	}
	
	function suppend_actions()
    {
        if (!$this->Owner) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER["HTTP_REFERER"]);
        }

        $this->form_validation->set_rules('form_action', lang("form_action"), 'required');

        if ($this->form_validation->run() == true) {

            if (!empty($_POST['val'])) {
                if ($this->input->post('form_action') == 'delete') {
					
                    $error = false;
                    foreach ($_POST['val'] as $id) {
                        if (!$this->settings_model->deleteSuppend($id)) {
                            $error = true;
                        }
                    }
                    if ($error) {
                        $this->session->set_flashdata('warning', lang('suppliers_x_deleted_have_purchases'));
                    } else {
                        $this->session->set_flashdata('message', $this->lang->line("account_deleted_successfully"));
                    }
                    redirect($_SERVER["HTTP_REFERER"]);
                }

                if ($this->input->post('form_action') == 'export_excel' || $this->input->post('form_action') == 'export_pdf') {

                    $this->load->library('excel');
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle(lang('suspend'));
                    $this->excel->getActiveSheet()->SetCellValue('A1', lang('floor'));
                    $this->excel->getActiveSheet()->SetCellValue('B1', lang('name'));
                    $this->excel->getActiveSheet()->SetCellValue('C1', lang('people'));
                    $this->excel->getActiveSheet()->SetCellValue('D1', lang('description'));

                    $row = 2;
                    foreach ($_POST['val'] as $id) {
                        $customer = $this->settings_model->getRoomByID($id);
                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $customer->floor);
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $customer->name);
                        $this->excel->getActiveSheet()->SetCellValue('C' . $row, $customer->ppl_number);
                        $this->excel->getActiveSheet()->SetCellValue('D' . $row, $customer->description);
                        $row++;
                    }

                    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $filename = 'suspend_' . date('Y_m_d_H_i_s');
                    if ($this->input->post('form_action') == 'export_pdf') {
                        $styleArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
                        $this->excel->getDefaultStyle()->applyFromArray($styleArray);
                        $this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
                        require_once(APPPATH . "third_party" . DIRECTORY_SEPARATOR . "MPDF" . DIRECTORY_SEPARATOR . "mpdf.php");
                        $rendererName = PHPExcel_Settings::PDF_RENDERER_MPDF;
                        $rendererLibrary = 'MPDF';
                        $rendererLibraryPath = APPPATH . 'third_party' . DIRECTORY_SEPARATOR . $rendererLibrary;
                        if (!PHPExcel_Settings::setPdfRenderer($rendererName, $rendererLibraryPath)) {
                            die('Please set the $rendererName: ' . $rendererName . ' and $rendererLibraryPath: ' . $rendererLibraryPath . ' values' .
                                PHP_EOL . ' as appropriate for your directory structure');
                        }

                        header('Content-Type: application/pdf');
                        header('Content-Disposition: attachment;filename="' . $filename . '.pdf"');
                        header('Cache-Control: max-age=0');

                        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'PDF');
                        return $objWriter->save('php://output');
                    }
                    if ($this->input->post('form_action') == 'export_excel') {
                        header('Content-Type: application/vnd.ms-excel');
                        header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
                        header('Cache-Control: max-age=0');

                        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
                        return $objWriter->save('php://output');
                    }

                    redirect($_SERVER["HTTP_REFERER"]);
                }
            } else {
                $this->session->set_flashdata('error', $this->lang->line("no_supplier_selected"));
                redirect($_SERVER["HTTP_REFERER"]);
            }
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }
	
	function delete($id = NULL)
    {
        $this->erp->checkPermissions(NULL, TRUE);

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        if ($this->settings_model->deleteSuppend($id)) {
            echo $this->lang->line("deleted_suspend");
        } else {
            $this->session->set_flashdata('warning', lang('chart_account_x_deleted_have_account'));
            die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . (isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : site_url('welcome')) . "'; }, 0);</script>");
        }
    }
	
	function addSuppend()
    {
        $this->form_validation->set_rules('email', $this->lang->line("email_address"), 'is_unique[companies.email]');
        $this->form_validation->set_rules('name', $this->lang->line("name"), 'is_unique[suspended.name]');

        if ($this->form_validation->run('system_settings/addSuppend') == true) {
			
            $data = array(
				'floor'         => $this->input->post('floor'),
                'name'          => $this->input->post('name'),
                'ppl_number'    => $this->input->post('people'),
                'description'   => $this->input->post('description'),
				'inactive'      => $this->input->post('inactive'),
				'warehouse_id'  => $this->input->post('warehouse')
            );
			
        }
        if ($this->form_validation->run() == true && $sid = $this->settings_model->addSuppend($data)) {
            $this->session->set_flashdata('message', $this->lang->line("suppend_added"));
            $ref = isset($_SERVER["HTTP_REFERER"]) ? explode('?', $_SERVER["HTTP_REFERER"]) : NULL;
            redirect($ref[0] . '?system_settings/suppend=' . $sid);
        } else {
            $this->data['warehouses'] = $this->site->getAllWarehouses();
            $error = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            if ($error) {
                $this->session->set_flashdata('error', lang('name_must_be_unique'));
                redirect('system_settings/suspend');
            }
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/addSuppend', $this->data);
        }

    }
	
	function edit($id = NULL)
    {
        $this->erp->checkPermissions(false, true);
		$this->data['id'] = $id;
		$this->data['warehouses'] = $this->site->getAllWarehouses();
        $this->data['suspend'] = $this->settings_model->getRoomByID($id);
		$this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
		$this->data['modal_js'] = $this->site->modal_js();
		$this->load->view($this->theme . 'settings/edit', $this->data);
    }
	
	public function suspend()
	{
		$this->erp->checkPermissions();

        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        // $this->data['action'] = $action;
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('settings')));
        $meta = array('page_title' => lang('suppend'), 'bc' => $bc);
        $this->page_construct('settings/suspend', $meta, $this->data);
	}
	
	function getRoom()
    {
        $this->erp->checkPermissions('index');

        $this->load->library('datatables');
        $this->datatables
            ->select("id,floor,name,ppl_number,description, COALESCE(inactive,0)")
            ->from("erp_suspended")
			->add_column("Actions", "<center><a class=\"tip\" title='" . $this->lang->line("edit_suspend") . "' href='" . site_url('system_settings/edit/$1') . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-edit\"></i></a>  <a href='#' class='tip po' title='<b>" . $this->lang->line("delete_suspend") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('system_settings/delete/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></center>", "id");
        echo $this->datatables->generate();
    }
    
    function suspend_layout()
	{
        $this->erp->checkPermissions();

        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        // $this->data['action'] = $action;
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('settings')));
        $meta = array('page_title' => lang('suppend'), 'bc' => $bc);

        $this->data['suspend'] = $this->db->select("*")->from("suspended")->get()->result();
        $this->page_construct('settings/suspend_layout', $meta, $this->data);
    }

    function save_suspend_layout()
	{
        $RoomArray  = $this->input->post("data");
        exit($RoomArray);
    }

    function import_subcategories()
    {

        $this->load->helper('security');
        $this->form_validation->set_rules('userfile', lang("upload_file"), 'xss_clean');

        if ($this->form_validation->run() == true) {

            if (isset($_FILES["userfile"])) {

                $this->load->library('upload');
                $config['upload_path'] = 'files/';
                $config['allowed_types'] = 'csv';
                $config['max_size'] = '1024';
                $config['overwrite'] = TRUE;
                $this->upload->initialize($config);
				
				//$this->erp->print_arrays($_FILES["userfile"]);
				
                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect("system_settings/categories");
                }

                $csv = $this->upload->file_name;

                $arrResult = array();
                $handle = fopen('files/' . $csv, "r");
                if ($handle) {
                    while (($row = fgetcsv($handle, 5000, ",")) !== FALSE) {
                        $arrResult[] = $row;
                    }
                    fclose($handle);
                }
				
                $titles = array_shift($arrResult);
                $keys = array('code', 'name', 'category_code', 'image');
                $final = array();
                foreach ($arrResult as $key => $value) {
                    $final[] = array_combine($keys, $value);
                }
				// $this->erp->print_arrays($final);
                $rw = 2;
				$data = '';
                foreach ($final as $csv_ct) {
                    if ( ! $this->settings_model->getSubcategoryByCode(trim($csv_ct['code']))) {
                        if ($parent_actegory = $this->settings_model->getCategoryByCode(trim($csv_ct['category_code']))) {
                            $data[] = array(
                                'code' => trim($csv_ct['code']),
                                'name' => trim($csv_ct['name']),
                                'image' => trim($csv_ct['image']),
                                'category_id' => $parent_actegory->id,
                            );
							//$this->erp->print_arrays($data);
                        } else {
                            $this->session->set_flashdata('error', lang("check_category_code") . " (" . $csv_ct['category_code'] . "). " . lang("category_code_x_exist") . " " . lang("line_no") . " " . $rw);
                            redirect("system_settings/categories");
                        }
                    }
                    $rw++;
                }
            }
        }

        if ($this->form_validation->run() == true && $this->settings_model->addSubCategories($data)) {
            $this->session->set_flashdata('message', lang("subcategories_added"));
            redirect('system_settings/categories');
        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['userfile'] = array('name' => 'userfile',
                'id' => 'userfile',
                'type' => 'text',
                'value' => $this->form_validation->set_value('userfile')
            );
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme.'settings/import_subcategories', $this->data);

        }
    }

    function import_expense_categories()
    {

        $this->load->helper('security');
        $this->form_validation->set_rules('userfile', lang("upload_file"), 'xss_clean');

        if ($this->form_validation->run() == true) {

            if (isset($_FILES["userfile"])) {

                $this->load->library('upload');
                $config['upload_path'] = 'files/';
                $config['allowed_types'] = 'csv';
                $config['max_size'] = '1024';
                $config['overwrite'] = TRUE;
                $this->upload->initialize($config);

                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect("system_settings/expense_categories");
                }

                $csv = $this->upload->file_name;

                $arrResult = array();
                $handle = fopen('files/' . $csv, "r");
                if ($handle) {
                    while (($row = fgetcsv($handle, 5000, ",")) !== FALSE) {
                        $arrResult[] = $row;
                    }
                    fclose($handle);
                }
                $titles = array_shift($arrResult);
                $keys = array('code', 'name');
                $final = array();
                foreach ($arrResult as $key => $value) {
                    $final[] = array_combine($keys, $value);
                }

                foreach ($final as $csv_ct) {
                    if ( ! $this->settings_model->getExpenseCategoryByCode(trim($csv_ct['code']))) {
                        $data[] = array(
                            'code' => trim($csv_ct['code']),
                            'name' => trim($csv_ct['name']),
                            );
                    }
                }
            }

            // $this->erp->print_arrays($data);
        }

        if ($this->form_validation->run() == true && $this->settings_model->addExpenseCategories($data)) {
            $this->session->set_flashdata('message', lang("categories_added"));
            redirect('system_settings/expense_categories');
        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['userfile'] = array('name' => 'userfile',
                'id' => 'userfile',
                'type' => 'text',
                'value' => $this->form_validation->set_value('userfile')
            );
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme.'settings/import_expense_categories', $this->data);

        }
    }
	
	function audit_trail($pdf = null, $xls = null)
    {
		
        if ($this->input->post('module')) {
            $module = $this->input->post('module');
        } else {
            $module = NULL;
        }
		
        if ($this->input->post('start_date')) {
            $start_date = $this->input->post('start_date');
        } else {
            $start_date = NULL;
        }
		
        if ($this->input->post('end_date')) {
            $end_date = $this->input->post('end_date');
        } else {
            $end_date = NULL;
        }
		if($xls != ""){
			
			$this->data['xls'] = 1;
		}
		
        $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('system_settings'), 'page' => lang('system_settings')), array('link' => '#', 'page' => lang('categories')));
        $meta = array('page_title' => lang('audit_trail'), 'bc' => $bc);
        $this->page_construct('settings/audit_trail', $meta, $this->data);
    }

    function getAuditTrail()
    {

        if ($this->input->get('module')) {
            $module = $this->input->get('module');
        } else {
            $module = NULL;
        }
		
        if ($this->input->get('start_date')) {
            $start_date = $this->input->get('start_date');
        } else {
            $start_date = NULL;
        }
		
        if ($this->input->get('end_date')) {
            $end_date = $this->input->get('end_date');
        } else {
            $end_date = NULL;
        }
		if ($start_date) {
            $start_date = $this->erp->fld($start_date);
            $end_date = $this->erp->fld($end_date);
        }
		$xls = $this->input->get('xls');
        
		if($xls == 1){
			
			
			$styleArray = array(
				'font'  => array(
					'bold'  => true,
					'color' => array('rgb' => '000000'),
					'size'  => 10,
					'name'  => 'Verdana'
				)
			);
			$bold = array(
				'font' => array(
					'bold' => true
				)
			);
			
			$this->load->library('excel');
			$this->excel->setActiveSheetIndex(0);
			//$this->excel->getActiveSheet()->getStyle('A1:I1')->applyFromArray($styleArray);
			$this->excel->getActiveSheet()->setTitle(lang('products_report'));
			$this->excel->getActiveSheet()->SetCellValue('A1', lang('created_date'));
			$this->excel->getActiveSheet()->SetCellValue('B1', lang('created_by'));
			$this->excel->getActiveSheet()->SetCellValue('C1', lang('biller'));
			$this->excel->getActiveSheet()->SetCellValue('D1', lang('warehouse'));
			$this->excel->getActiveSheet()->SetCellValue('E1', lang('reference_no'));
			$this->excel->getActiveSheet()->SetCellValue('F1', lang('Type'));
			$this->excel->getActiveSheet()->SetCellValue('G1', lang('note'));
			$this->excel->getActiveSheet()->SetCellValue('H1', lang('updated_date'));
			$this->excel->getActiveSheet()->SetCellValue('I1', lang('updated_by'));
			
			//$this->excel->getActiveSheet()->getStyle('E2:F2')->applyFromArray($bold);
			//$this->excel->getActiveSheet()->getStyle('G2:H2')->applyFromArray($bold);
					
				if($module == 1){
					$this->db
					->select("sales.date as test1 , u1.first_name AS created_by1, companies.company, warehouses.name, reference_no,'Sale', customer,sales.updated_at as test2, u2.first_name AS updated_by2")
					->from("sales")
					->join('warehouses', 'warehouses.id = sales.warehouse_id', 'left')
					->join('companies', 'companies.id = sales.biller_id', 'left')
					->join('users AS u1', 'u1.id = sales.created_by', 'left')
					->join('users AS u2', 'u2.id = sales.updated_by', 'left');
					if ($start_date) {
						
						$this->db->where($this->db->dbprefix('sales').'.date BETWEEN "' . $start_date . '" and "' . $end_date . '"');
					}
				}else if ($module == 2){
					$this->db
					->select("quotes.date as test1 , u1.first_name AS created_by1, companies.company, warehouses.name, reference_no,'Quote', supplier,quotes.updated_at as test2, u2.first_name AS updated_by2")
					->from("quotes")
					->join('warehouses', 'warehouses.id = quotes.warehouse_id', 'left')
					->join('companies', 'companies.id = quotes.biller_id', 'left')
					->join('users AS u1', 'u1.id = quotes.created_by', 'left')
					->join('users AS u2', 'u2.id = quotes.updated_by', 'left');
					if ($start_date) {
						
						$this->db->where($this->db->dbprefix('quotes').'.date BETWEEN "' . $start_date . '" and "' . $end_date . '"');
					}	
				}else if ($module == 3){
					$this->db
					->select("purchases.date as test1 , u1.first_name AS created_by1, companies.company, warehouses.name, reference_no,'Purchase', supplier,purchases.updated_at as test2, u2.first_name AS updated_by2")
					->from("purchases")
					->join('warehouses', 'warehouses.id = purchases.warehouse_id', 'left')
					->join('companies', 'companies.id = purchases.biller_id', 'left')
					->join('users AS u1', 'u1.id = purchases.created_by', 'left')
					->join('users AS u2', 'u2.id = purchases.updated_by', 'left');
					if ($start_date) {
						
						$this->db->where($this->db->dbprefix('purchases').'.date BETWEEN "' . $start_date . '" and "' . $end_date . '"');
					}	
				}else{
					
					$this->db
					->select("sales.date as test1 , u1.first_name AS created_by1, companies.company, warehouses.name, reference_no,'Sale', customer,sales.updated_at as test2, u2.first_name AS updated_by2")
					->from("sales")
					->join('warehouses', 'warehouses.id = sales.warehouse_id', 'left')
					->join('companies', 'companies.id = sales.biller_id', 'left')
					->join('users AS u1', 'u1.id = sales.created_by', 'left')
					->join('users AS u2', 'u2.id = sales.updated_by', 'left');
					if ($start_date) {
						
						$this->db->where($this->db->dbprefix('sales').'.date BETWEEN "' . $start_date . '" and "' . $end_date . '"');
					}
					
				}
				$auditData = $this->db->get();
				$row = 2;
				
				
				
				foreach($auditData->result() as $rw){
						
						$this->excel->getActiveSheet()->SetCellValue('A'.$row, $rw->test1);
						$this->excel->getActiveSheet()->SetCellValue('B'.$row, $rw->created_by1);
						$this->excel->getActiveSheet()->SetCellValue('C'.$row, $rw->company);
						$this->excel->getActiveSheet()->SetCellValue('D'.$row, $rw->name);
						$this->excel->getActiveSheet()->SetCellValue('E'.$row, $rw->reference_no);
						$this->excel->getActiveSheet()->SetCellValue('F'.$row, $rw->sale);
						$this->excel->getActiveSheet()->SetCellValue('G'.$row, $rw->customer);
						$this->excel->getActiveSheet()->SetCellValue('H'.$row, $rw->test2);
						$this->excel->getActiveSheet()->SetCellValue('I'.$row, $rw->updated_by2);
						$row++;
						
				}
				
				$this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
				$this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
				$this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
				$this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
				$this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
				$this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
				$this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
				$this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
				$this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
				
				$this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$filename = 'Audit_Trail_Report' . date('Y_m_d_H_i_s');
				if ($xls) {
					header('Content-Type: application/vnd.ms-excel');
					header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
					header('Cache-Control: max-age=0');

					$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
					return $objWriter->save('php://output');
					
				}

				redirect($_SERVER["HTTP_REFERER"]);	
						
				
		}else{
			
			$this->load->library('datatables');
			if($module == 1){
				$this->datatables
				->select("sales.date as test1 , u1.first_name AS created_by1, companies.company, warehouses.name, reference_no,'Sale', customer,sales.updated_at as test2, u2.first_name AS updated_by2")
				->from("sales")
				->join('warehouses', 'warehouses.id = sales.warehouse_id', 'left')
				->join('companies', 'companies.id = sales.biller_id', 'left')
				->join('users AS u1', 'u1.id = sales.created_by', 'left')
				->join('users AS u2', 'u2.id = sales.updated_by', 'left');
				if ($start_date) {
					
					$this->datatables->where($this->db->dbprefix('sales').'.date BETWEEN "' . $start_date . '" and "' . $end_date . '"');
				}
			}else if ($module == 2){
				$this->datatables
				->select("quotes.date as test1 , u1.first_name AS created_by1, companies.company, warehouses.name, reference_no,'Quote', supplier,quotes.updated_at as test2, u2.first_name AS updated_by2")
				->from("quotes")
				->join('warehouses', 'warehouses.id = quotes.warehouse_id', 'left')
				->join('companies', 'companies.id = quotes.biller_id', 'left')
				->join('users AS u1', 'u1.id = quotes.created_by', 'left')
				->join('users AS u2', 'u2.id = quotes.updated_by', 'left');
				if ($start_date) {
					
					$this->datatables->where($this->db->dbprefix('quotes').'.date BETWEEN "' . $start_date . '" and "' . $end_date . '"');
				}	
			}else if ($module == 3){
				$this->datatables
				->select("purchases.date as test1 , u1.first_name AS created_by1, companies.company, warehouses.name, reference_no,'Purchase', supplier,purchases.updated_at as test2, u2.first_name AS updated_by2")
				->from("purchases")
				->join('warehouses', 'warehouses.id = purchases.warehouse_id', 'left')
				->join('companies', 'companies.id = purchases.biller_id', 'left')
				->join('users AS u1', 'u1.id = purchases.created_by', 'left')
				->join('users AS u2', 'u2.id = purchases.updated_by', 'left');
				if ($start_date) {
					
					$this->datatables->where($this->db->dbprefix('purchases').'.date BETWEEN "' . $start_date . '" and "' . $end_date . '"');
				}	
			}else{
				
				$this->datatables
				->select("sales.date as test1 , u1.first_name AS created_by1, companies.company, warehouses.name, reference_no,'Sale', customer,sales.updated_at as test2, u2.first_name AS updated_by2")
				->from("sales")
				->join('warehouses', 'warehouses.id = sales.warehouse_id', 'left')
				->join('companies', 'companies.id = sales.biller_id', 'left')
				->join('users AS u1', 'u1.id = sales.created_by', 'left')
				->join('users AS u2', 'u2.id = sales.updated_by', 'left');
				if ($start_date) {
					
					$this->datatables->where($this->db->dbprefix('sales').'.date BETWEEN "' . $start_date . '" and "' . $end_date . '"');
				}
				
			}
			
			echo $this->datatables->generate();
			
		}
    }
	
	function expense_categories()
    {

        $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('system_settings'), 'page' => lang('system_settings')), array('link' => '#', 'page' => lang('expense_categories')));
        $meta = array('page_title' => lang('categories'), 'bc' => $bc);
        $this->page_construct('settings/expense_categories', $meta, $this->data);
    }

    function getExpenseCategories()
    {

        $this->load->library('datatables');
        $this->datatables
            ->select("id, code, name")
            ->from("expense_categories")
            ->add_column("Actions", "<div class=\"text-center\"><a href='" . site_url('system_settings/edit_expense_category/$1') . "' data-toggle='modal' data-target='#myModal' class='tip' title='" . lang("edit_expense_category") . "'><i class=\"fa fa-edit\"></i></a> <a href='#' class='tip po' title='<b>" . lang("delete_expense_category") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('system_settings/delete_expense_category/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></div>", "id");

        echo $this->datatables->generate();
    }

    function add_expense_category()
    {

        $this->form_validation->set_rules('code', lang("category_code"), 'trim|is_unique[categories.code]|required');
        $this->form_validation->set_rules('name', lang("name"), 'required|min_length[3]');

        if ($this->form_validation->run() == true) {

            $data = array(
                'name' => $this->input->post('name'),
                'code' => $this->input->post('code'),
            );

        } elseif ($this->input->post('add_expense_category')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect("system_settings/expense_categories");
        }

        if ($this->form_validation->run() == true && $this->settings_model->addExpenseCategory($data)) {
            $this->session->set_flashdata('message', lang("expense_category_added"));
            redirect("system_settings/expense_categories");
        } else {
            $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/add_expense_category', $this->data);
        }
    }

    function edit_expense_category($id = NULL)
    {
        $this->form_validation->set_rules('code', lang("category_code"), 'trim|required');
        $category = $this->settings_model->getExpenseCategoryByID($id);
        $photo = $this->upload->file_name;
        if ($this->input->post('code') != $category->code) {
            $this->form_validation->set_rules('code', lang("category_code"), 'is_unique[expense_categories.code]');
        }
        $this->form_validation->set_rules('name', lang("category_name"), 'required|min_length[3]');

        if ($this->form_validation->run() == true) {

            $data = array(
                'code' => $this->input->post('code'),
                'name' => $this->input->post('name')
            );

        } elseif ($this->input->post('edit_expense_category')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect("system_settings/expense_categories");
        }

        if ($this->form_validation->run() == true && $this->settings_model->updateExpenseCategory($id, $data, $photo)) {
            $this->session->set_flashdata('message', lang("expense_category_updated"));
            redirect("system_settings/expense_categories");
        } else {
            $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
            $this->data['category'] = $category;
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/edit_expense_category', $this->data);
        }
    }

    function delete_expense_category($id = NULL)
    {

        if ($this->settings_model->hasExpenseCategoryRecord($id)) {
            $this->session->set_flashdata('error', lang("category_has_expenses"));
            redirect("system_settings/expense_categories", 'refresh');
        }

        if ($this->settings_model->deleteExpenseCategory($id)) {
            echo lang("expense_category_deleted");
        }
    }

    function expense_category_actions()
    {

        $this->form_validation->set_rules('form_action', lang("form_action"), 'required');

        if ($this->form_validation->run() == true) {

            if (!empty($_POST['val'])) {
                if ($this->input->post('form_action') == 'delete') {
                    foreach ($_POST['val'] as $id) {
                        $this->settings_model->deleteCategory($id);
                    }
                    $this->session->set_flashdata('message', lang("categories_deleted"));
                    redirect($_SERVER["HTTP_REFERER"]);
                }

                if ($this->input->post('form_action') == 'export_excel' || $this->input->post('form_action') == 'export_pdf') {

                    $this->load->library('excel');
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle(lang('categories'));
                    $this->excel->getActiveSheet()->SetCellValue('A1', lang('code'));
                    $this->excel->getActiveSheet()->SetCellValue('B1', lang('name'));

                    $row = 2;
                    foreach ($_POST['val'] as $id) {
                        $sc = $this->settings_model->getCategoryByID($id);
                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $sc->code);
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $sc->name);
                        $row++;
                    }

                    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $filename = 'categories_' . date('Y_m_d_H_i_s');
                    if ($this->input->post('form_action') == 'export_pdf') {
                        $styleArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
                        $this->excel->getDefaultStyle()->applyFromArray($styleArray);
                        $this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
                        require_once(APPPATH . "third_party" . DIRECTORY_SEPARATOR . "MPDF" . DIRECTORY_SEPARATOR . "mpdf.php");
                        $rendererName = PHPExcel_Settings::PDF_RENDERER_MPDF;
                        $rendererLibrary = 'MPDF';
                        $rendererLibraryPath = APPPATH . 'third_party' . DIRECTORY_SEPARATOR . $rendererLibrary;
                        if (!PHPExcel_Settings::setPdfRenderer($rendererName, $rendererLibraryPath)) {
                            die('Please set the $rendererName: ' . $rendererName . ' and $rendererLibraryPath: ' . $rendererLibraryPath . ' values' .
                                PHP_EOL . ' as appropriate for your directory structure');
                        }

                        header('Content-Type: application/pdf');
                        header('Content-Disposition: attachment;filename="' . $filename . '.pdf"');
                        header('Cache-Control: max-age=0');

                        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'PDF');
                        return $objWriter->save('php://output');
                    }
                    if ($this->input->post('form_action') == 'export_excel') {
                        header('Content-Type: application/vnd.ms-excel');
                        header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
                        header('Cache-Control: max-age=0');

                        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
                        return $objWriter->save('php://output');
                    }

                    redirect($_SERVER["HTTP_REFERER"]);
                }
            } else {
                $this->session->set_flashdata('error', lang("no_record_selected"));
                redirect($_SERVER["HTTP_REFERER"]);
            }
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }

    function import_categories()
    {
        $this->load->helper('security');
        $this->form_validation->set_rules('userfile', lang("upload_file"), 'xss_clean');
        $default_account=$this->settings_model->getAccountSettings();
        if ($this->form_validation->run() == true) {

            if (isset($_FILES["userfile"])) {

                $this->load->library('upload');
                $config['upload_path'] = 'files/';
                $config['allowed_types'] = 'csv';
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = TRUE;
                $this->upload->initialize($config);

                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect("system_settings/categories");
                }

                $csv = $this->upload->file_name;

                $arrResult = array();
                $handle = fopen('files/' . $csv, "r");
                if ($handle) {
                    while (($row = fgetcsv($handle, 5000, ",")) !== FALSE) {
                        $arrResult[] = $row;
                    }
                    fclose($handle);
                }
                $titles = array_shift($arrResult);
                $keys = array('code', 'name', 'image','ac_sale','ac_cost','ac_stock','ac_stock_adj','ac_cost_variant','ac_purchase');
                $final = array();
                foreach ($arrResult as $key => $value) {
                    $final[] = array_combine($keys, $value);
                }

                foreach ($final as $csv_ct) {
                    if ( ! $this->settings_model->getCategoryByCode(trim($csv_ct['code']))) {
                        $pcat = NULL;
                        $p_code = trim($csv_ct['pcode']);
                        if (!empty($p_code)) {
                            if ($pcategory = $this->settings_model->getCategoryByCode(trim($csv_ct['pcode']))) {
                                $data[] = array(
                                    'image'             => trim($csv_ct['image']),
                                    'code'              => trim($csv_ct['code']),
                                    'name'              => trim($csv_ct['name']),
                                    // 'parent_id'       => $pcategory->id
                                    'ac_sale'           => trim($csv_ct['ac_sale'])?trim($csv_ct['ac_sale']):$default_account->default_sale,
                                    'ac_cost'           => trim($csv_ct['ac_cost'])?trim($csv_ct['ac_cost']):$default_account->default_cost,
                                    'ac_stock'          => trim($csv_ct['ac_stock'])?trim($csv_ct['ac_stock']):$default_account->default_stock,
                                    'ac_stock_adj'      => trim($csv_ct['ac_stock_adj'])?trim($csv_ct['ac_stock_adj']):$default_account->default_stock_adjust,
                                    'ac_cost_variant'   => trim($csv_ct['ac_cost_variant'])?trim($csv_ct['ac_cost_variant']):$default_account->default_cost_variant,
                                    'ac_purchase'       => trim($csv_ct['ac_purchase'])?trim($csv_ct['ac_purchase']):$default_account->default_purchase
                                );
                            }
                        } else {
                            $data[] = array(
                                'image'             => trim($csv_ct['image']),
                                'code'              => trim($csv_ct['code']),
                                'name'              => trim($csv_ct['name']),
                                'ac_sale'           => trim($csv_ct['ac_sale'])?trim($csv_ct['ac_sale']):$default_account->default_sale,
                                'ac_cost'           => trim($csv_ct['ac_cost'])?trim($csv_ct['ac_cost']):$default_account->default_cost,
                                'ac_stock'          => trim($csv_ct['ac_stock'])?trim($csv_ct['ac_stock']):$default_account->default_stock,
                                'ac_stock_adj'      => trim($csv_ct['ac_stock_adj'])?trim($csv_ct['ac_stock_adj']):$default_account->default_stock_adjust,
                                'ac_cost_variant'   => trim($csv_ct['ac_cost_variant'])?trim($csv_ct['ac_cost_variant']):$default_account->default_cost_variant,
                                'ac_purchase'       => trim($csv_ct['ac_purchase'])?trim($csv_ct['ac_purchase']):$default_account->default_purchase

                            );
                        }
                    }else{
                        $this->session->set_flashdata('error', lang("categories_code_exist"));
                        redirect('system_settings/categories');
                    }
                }
            }
            //$this->erp->print_arrays($data);
        }

        if ($this->form_validation->run() == true && $this->settings_model->addCategories($data)) {
            $this->session->set_flashdata('message', lang("categories_added"));
            redirect('system_settings/categories');
        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['userfile'] = array('name' => 'userfile',
                'id' => 'userfile',
                'type' => 'text',
                'value' => $this->form_validation->set_value('userfile')
            );
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme.'settings/import_categories', $this->data);

        }
    }

    function units()
    {

        $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('system_settings'), 'page' => lang('system_settings')), array('link' => '#', 'page' => lang('units')));
        $meta = array('page_title' => lang('units'), 'bc' => $bc);
        $this->page_construct('settings/units', $meta, $this->data);
    }

    function getUnits()
    {
        $this->load->library('datatables');
        $this->datatables
            ->select("{$this->db->dbprefix('units')}.id as id, {$this->db->dbprefix('units')}.id as idd, {$this->db->dbprefix('units')}.code, {$this->db->dbprefix('units')}.name", FALSE)
            ->from("units")
            ->join("units b", 'b.id=units.base_unit', 'left')
            ->group_by('units.id')
            ->add_column("Actions", "<div class=\"text-center\"><a href='" . site_url('system_settings/edit_unit/$1') . "' data-toggle='modal' data-target='#myModal' class='tip' title='" . lang("edit_unit") . "'><i class=\"fa fa-edit\"></i></a> <a href='#' class='tip po' title='<b>" . lang("delete_unit") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('system_settings/delete_unit/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></div>", "id");

        echo $this->datatables->generate();
    }

    function add_unit()
    {

        $this->form_validation->set_rules('code', lang("unit_code"), 'trim|is_unique[units.code]|required');
        $this->form_validation->set_rules('name', lang("unit_name"), 'trim|required');
        if ($this->input->post('base_unit')) {
            $this->form_validation->set_rules('operator', lang("operator"), 'required');
            $this->form_validation->set_rules('operation_value', lang("operation_value"), 'trim|required');
        }

        if ($this->form_validation->run() == true) {

            $data = array(
                'name' => $this->input->post('name'),
                'code' => $this->input->post('code'),
                'base_unit' => $this->input->post('base_unit') ? $this->input->post('base_unit') : NULL,
                'operator' => $this->input->post('base_unit') ? $this->input->post('operator') : NULL,
                'operation_value' => $this->input->post('operation_value') ? $this->input->post('operation_value') : NULL,
                );

        } elseif ($this->input->post('add_unit')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect("system_settings/units");
        }

        if ($this->form_validation->run() == true && $uid = $this->settings_model->addUnit($data)) {
            $this->session->set_flashdata('message', lang("unit_added"));
            $ref = isset($_SERVER["HTTP_REFERER"]) ? explode('?', $_SERVER["HTTP_REFERER"]) : NULL;
            redirect($ref[0] . '?unit=' . $uid);
            // redirect("system_settings/units");
        } else {

            $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
            $this->data['base_units'] = $this->site->getAllBaseUnits();
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/add_unit', $this->data);

        }
    }

    function edit_unit($id = NULL)
    {

        $this->form_validation->set_rules('code', lang("code"), 'trim|required');
        $unit_details = $this->site->getUnitByID($id);
        if ($this->input->post('code') != $unit_details->code) {
            $this->form_validation->set_rules('code', lang("code"), 'is_unique[units.code]');
        }
        $this->form_validation->set_rules('name', lang("name"), 'trim|required');
        if ($this->input->post('base_unit')) {
            $this->form_validation->set_rules('operator', lang("operator"), 'required');
            $this->form_validation->set_rules('operation_value', lang("operation_value"), 'trim|required');
        }

        if ($this->form_validation->run() == true) {

            $data = array(
                'name' => $this->input->post('name'),
                'code' => $this->input->post('code'),
                'base_unit' => $this->input->post('base_unit') ? $this->input->post('base_unit') : NULL,
                'operator' => $this->input->post('base_unit') ? $this->input->post('operator') : NULL,
                'operation_value' => $this->input->post('operation_value') ? $this->input->post('operation_value') : NULL,
                );

        } elseif ($this->input->post('edit_unit')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect("system_settings/units");
        }

        if ($this->form_validation->run() == true && $this->settings_model->updateUnit($id, $data)) {
            $this->session->set_flashdata('message', lang("unit_updated"));
            redirect("system_settings/units");
        } else {

            $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
            $this->data['modal_js'] = $this->site->modal_js();
            $this->data['unit'] = $unit_details;
            $this->data['base_units'] = $this->site->getAllBaseUnits();
            $this->load->view($this->theme . 'settings/edit_unit', $this->data);

        }
    }

    function delete_unit($id = NULL)
    {

        if ($this->site->getUnitsByBUID($id)) {
            $this->session->set_flashdata('error', lang("unit_has_subunit"));
        }
        if($this->settings_model->deleteUnit($id)){
            echo lang("unit_deleted"); 
        }
    }

    function unit_actions()
    {

        $this->form_validation->set_rules('form_action', lang("form_action"), 'required');

        if ($this->form_validation->run() == true) {

            if (!empty($_POST['val'])) {
                if ($this->input->post('form_action') == 'delete') {
                    foreach ($_POST['val'] as $id) {
                        $this->settings_model->deleteUnit($id);
                    }
                    $this->session->set_flashdata('message', lang("units_deleted"));
                    redirect($_SERVER["HTTP_REFERER"]);
                }

                if ($this->input->post('form_action') == 'export_excel' || $this->input->post('form_action') == 'export_pdf') {

                    $this->load->library('excel');
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle(lang('categories'));
                    $this->excel->getActiveSheet()->SetCellValue('A1', lang('code'));
                    $this->excel->getActiveSheet()->SetCellValue('B1', lang('name'));
                    $this->excel->getActiveSheet()->SetCellValue('C1', lang('base_unit'));
                    $this->excel->getActiveSheet()->SetCellValue('D1', lang('operator'));
                    $this->excel->getActiveSheet()->SetCellValue('E1', lang('operation_value'));

                    $row = 2;
                    foreach ($_POST['val'] as $id) {
                        $unit = $this->site->getUnitByID($id);
                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $unit->code);
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $unit->name);
                        $this->excel->getActiveSheet()->SetCellValue('C' . $row, $unit->base_unit);
                        $this->excel->getActiveSheet()->SetCellValue('D' . $row, $unit->operator);
                        $this->excel->getActiveSheet()->SetCellValue('E' . $row, $unit->operation_value);
                        $row++;
                    }

                    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $filename = 'categories_' . date('Y_m_d_H_i_s');
                    if ($this->input->post('form_action') == 'export_pdf') {
                        $styleArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
                        $this->excel->getDefaultStyle()->applyFromArray($styleArray);
                        $this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
                        require_once(APPPATH . "third_party" . DIRECTORY_SEPARATOR . "MPDF" . DIRECTORY_SEPARATOR . "mpdf.php");
                        $rendererName = PHPExcel_Settings::PDF_RENDERER_MPDF;
                        $rendererLibrary = 'MPDF';
                        $rendererLibraryPath = APPPATH . 'third_party' . DIRECTORY_SEPARATOR . $rendererLibrary;
                        if (!PHPExcel_Settings::setPdfRenderer($rendererName, $rendererLibraryPath)) {
                            die('Please set the $rendererName: ' . $rendererName . ' and $rendererLibraryPath: ' . $rendererLibraryPath . ' values' .
                                PHP_EOL . ' as appropriate for your directory structure');
                        }

                        header('Content-Type: application/pdf');
                        header('Content-Disposition: attachment;filename="' . $filename . '.pdf"');
                        header('Cache-Control: max-age=0');

                        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'PDF');
                        return $objWriter->save('php://output');
                    }
                    if ($this->input->post('form_action') == 'export_excel') {
                        header('Content-Type: application/vnd.ms-excel');
                        header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
                        header('Cache-Control: max-age=0');

                        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
                        return $objWriter->save('php://output');
                    }

                    redirect($_SERVER["HTTP_REFERER"]);
                }
            } else {
                $this->session->set_flashdata('error', lang("no_record_selected"));
                redirect($_SERVER["HTTP_REFERER"]);
            }
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }

    function price_groups()
    {

        $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('system_settings'), 'page' => lang('system_settings')), array('link' => '#', 'page' => lang('price_groups')));
        $meta = array('page_title' => lang('price_groups'), 'bc' => $bc);
        $this->page_construct('settings/price_groups', $meta, $this->data);
    }

    function getPriceGroups()
    {

        $this->load->library('datatables');
        $this->datatables
            ->select("id, name")
            ->from("price_groups")
            ->add_column("Actions", "<div class=\"text-center\"><a href='" . site_url('system_settings/group_product_prices/$1') . "' class='tip' title='" . lang("group_product_prices") . "'><i class=\"fa fa-eye\"></i></a>  <a href='" . site_url('system_settings/edit_price_group/$1') . "' class='tip' title='" . lang("edit_price_group") . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-edit\"></i></a> <a href='#' class='tip po' title='<b>" . lang("delete_price_group") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('system_settings/delete_price_group/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></div>", "id");
        //->unset_column('id');

        echo $this->datatables->generate();
    }

    function add_price_group()
    {

        $this->form_validation->set_rules('name', lang("group_name"), 'trim|required');

        if ($this->form_validation->run() == true) {
            $data = array('name' => $this->input->post('name'));
        } elseif ($this->input->post('add_price_group')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect("system_settings/price_groups");
        }

        if ($this->form_validation->run() == true && $this->settings_model->addPriceGroup($data)) {
            $this->session->set_flashdata('message', lang("price_group_added"));
            redirect("system_settings/price_groups");
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/add_price_group', $this->data);
        }
    }

    function edit_price_group($id = NULL)
    {

        $this->form_validation->set_rules('name', lang("group_name"), 'trim|required|alpha_numeric_spaces');
        $pg_details = $this->settings_model->getPriceGroupByID($id);
        if ($this->input->post('name') != $pg_details->name) {
            $this->form_validation->set_rules('name', lang("group_name"), 'is_unique[price_groups.name]');
        }

        if ($this->form_validation->run() == true) {
            $data = array('name' => $this->input->post('name'));
        } elseif ($this->input->post('edit_price_group')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect("system_settings/price_groups");
        }

        if ($this->form_validation->run() == true && $this->settings_model->updatePriceGroup($id, $data)) {
            $this->session->set_flashdata('message', lang("price_group_updated"));
            redirect("system_settings/price_groups");
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

            $this->data['price_group'] = $pg_details;
            $this->data['id'] = $id;
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/edit_price_group', $this->data);
        }
    }

    function delete_price_group($id = NULL)
    {
        if ($this->settings_model->deletePriceGroup($id)) {
            echo lang("price_group_deleted");
        }
    }
	
    function product_group_price_actions($group_id)
    {
        if (!$group_id) {
            $this->session->set_flashdata('error', lang('no_price_group_selected'));
            redirect('system_settings/price_groups');
        }

        $this->form_validation->set_rules('form_action', lang("form_action"), 'required');

        if ($this->form_validation->run() == true) {

            if (!empty($_POST['val'])) {
                if ($this->input->post('form_action') == 'update_price') {
					$i = 0;
                    foreach ($_POST['val'] as $row) {
						$rw = explode('_', $row);
						$id = $rw[0];
						$unit_id = $rw[1];
						$unit_type = $rw[2];
                        $this->settings_model->setProductPriceForPriceGroup($id, $group_id, $this->input->post('price'.$id.'_'.$unit_id.'_'.$unit_type), $this->input->post('currency'.$id.'_'.$unit_id.'_'.$unit_type), $this->input->post('unit_id'.$id.'_'.$unit_id.'_'.$unit_type), $this->input->post('unit_type'.$id.'_'.$unit_id.'_'.$unit_type));
						$i++;
					}
                    $this->session->set_flashdata('message', lang("products_group_price_updated"));
                    redirect($_SERVER["HTTP_REFERER"]);

                } elseif ($this->input->post('form_action') == 'delete') {

                    foreach ($_POST['val'] as $id) {
                        $this->settings_model->deleteProductGroupPrice($id, $group_id);
                    }
                    $this->session->set_flashdata('message', lang("products_group_price_deleted"));
                    redirect($_SERVER["HTTP_REFERER"]);

                } elseif ($this->input->post('form_action') == 'export_excel' || $this->input->post('form_action') == 'export_pdf') {

                    $this->load->library('excel');
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle(lang('tax_rates'));
                    $this->excel->getActiveSheet()->SetCellValue('A1', lang('product_code'));
                    $this->excel->getActiveSheet()->SetCellValue('B1', lang('product_name'));
                    $this->excel->getActiveSheet()->SetCellValue('C1', lang('price'));
                    $this->excel->getActiveSheet()->SetCellValue('D1', lang('group_name'));
                    $row = 2;
                    $group = $this->settings_model->getPriceGroupByID($group_id);
                    foreach ($_POST['val'] as $id) {
                        $pgp = $this->settings_model->getProductGroupPriceByPID($id, $group_id);
                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $pgp->code);
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $pgp->name);
                        $this->excel->getActiveSheet()->SetCellValue('C' . $row, $pgp->price);
                        $this->excel->getActiveSheet()->SetCellValue('D' . $row, $group->name);
                        $row++;
                    }
                    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
                    $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
                    $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $filename = 'price_groups_' . date('Y_m_d_H_i_s');
                    if ($this->input->post('form_action') == 'export_pdf') {
                        $styleArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
                        $this->excel->getDefaultStyle()->applyFromArray($styleArray);
                        $this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
                        require_once(APPPATH . "third_party" . DIRECTORY_SEPARATOR . "MPDF" . DIRECTORY_SEPARATOR . "mpdf.php");
                        $rendererName = PHPExcel_Settings::PDF_RENDERER_MPDF;
                        $rendererLibrary = 'MPDF';
                        $rendererLibraryPath = APPPATH . 'third_party' . DIRECTORY_SEPARATOR . $rendererLibrary;
                        if (!PHPExcel_Settings::setPdfRenderer($rendererName, $rendererLibraryPath)) {
                            die('Please set the $rendererName: ' . $rendererName . ' and $rendererLibraryPath: ' . $rendererLibraryPath . ' values' .
                                PHP_EOL . ' as appropriate for your directory structure');
                        }

                        header('Content-Type: application/pdf');
                        header('Content-Disposition: attachment;filename="' . $filename . '.pdf"');
                        header('Cache-Control: max-age=0');

                        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'PDF');
                        return $objWriter->save('php://output');
                    }
                    if ($this->input->post('form_action') == 'export_excel') {
                        header('Content-Type: application/vnd.ms-excel');
                        header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
                        header('Cache-Control: max-age=0');

                        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
                        return $objWriter->save('php://output');
                    }

                    redirect($_SERVER["HTTP_REFERER"]);
                }
            } else {
                $this->session->set_flashdata('error', lang("no_price_group_selected"));
                redirect($_SERVER["HTTP_REFERER"]);
            }
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }
	
    function group_product_prices($group_id = NULL)
    {

        if (!$group_id) {
            $this->session->set_flashdata('error', lang('no_price_group_selected'));
            redirect('system_settings/price_groups');
        }
		$this->data['currencies'] = $this->site->getTwoCurrencies();
        $this->data['price_group'] = $this->settings_model->getPriceGroupByID($group_id);
        $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('system_settings'), 'page' => lang('system_settings')),  array('link' => site_url('system_settings/price_groups'), 'page' => lang('price_groups')), array('link' => '#', 'page' => lang('group_product_prices')));
        $meta = array('page_title' => lang('group_product_prices'), 'bc' => $bc);
        $this->page_construct('settings/group_product_prices', $meta, $this->data);
    }

	function getProductPrices($group_id = NULL)
	{
        if (!$group_id) {
            $this->session->set_flashdata('error', lang('no_price_group_selected'));
            redirect('system_settings/price_groups');
        }

        $pp = "( SELECT {$this->db->dbprefix('product_prices')}.product_id as product_id,{$this->db->dbprefix('product_prices')}.currency_code , {$this->db->dbprefix('product_prices')}.price as price FROM {$this->db->dbprefix('product_prices')} WHERE price_group_id = {$group_id} ) PP";
		$curr_code = "( SELECT {$this->db->dbprefix('product_prices')}.currency_code FROM {$this->db->dbprefix('product_prices')} WHERE {$this->db->dbprefix('product_prices')}.product_id = {$this->db->dbprefix('products')}.id AND {$this->db->dbprefix('product_prices')}.price_group_id = '".$group_id."' AND (({$this->db->dbprefix('product_prices')}.unit_id = {$this->db->dbprefix('product_variants')}.id AND {$this->db->dbprefix('product_prices')}.unit_type = 'variant') OR ({$this->db->dbprefix('product_prices')}.unit_id = {$this->db->dbprefix('units')}.id AND {$this->db->dbprefix('product_prices')}.unit_type = 'unit'))) as currency";
		$price = "( SELECT {$this->db->dbprefix('product_prices')}.price FROM {$this->db->dbprefix('product_prices')} WHERE {$this->db->dbprefix('product_prices')}.product_id = {$this->db->dbprefix('products')}.id AND {$this->db->dbprefix('product_prices')}.price_group_id = '".$group_id."' AND (({$this->db->dbprefix('product_prices')}.unit_id = {$this->db->dbprefix('product_variants')}.id AND {$this->db->dbprefix('product_prices')}.unit_type = 'variant') OR ({$this->db->dbprefix('product_prices')}.unit_id = {$this->db->dbprefix('units')}.id AND {$this->db->dbprefix('product_prices')}.unit_type = 'unit'))) as price";
        
		$this->load->library('datatables');
        $this->datatables
            ->select("{$this->db->dbprefix('products')}.id as id, {$this->db->dbprefix('products')}.code as product_code, 
						{$this->db->dbprefix('products')}.name as product_name, 
						IF({$this->db->dbprefix('product_variants')}.id, CONCAT({$this->db->dbprefix('product_variants')}.id, '___', {$this->db->dbprefix('product_variants')}.name, '___', 'variant'), CONCAT({$this->db->dbprefix('units')}.id, '___', {$this->db->dbprefix('units')}.name, '___', 'unit')) as unit,
						".$curr_code.",
						".$price.",
						IF({$this->db->dbprefix('product_variants')}.id, CONCAT({$this->db->dbprefix('product_variants')}.id, '_', 'variant'), CONCAT({$this->db->dbprefix('units')}.id, '_', 'unit')) AS h_unit")
            ->from("products")
			->join('units', 'products.unit = units.id', 'left')
			->join('product_variants', 'products.id = product_variants.product_id', 'left')
			->join('currencies', 'currencies.code = products.currentcy_code', 'left')
			->edit_column("unit", "$1_$2___$3", "id, h_unit, unit")
            ->edit_column("currency", "$1_$2__$3", 'id, h_unit, currency')
			->edit_column("price", "$1_$2__$3", 'id, h_unit, price')
			->edit_column("id", "$1_$2", "id, h_unit")
			->unset_column("h_unit")
            ->add_column("Actions", "<div class=\"text-center\"><button class=\"btn btn-primary btn-xs form-submit\" type=\"button\"><i class=\"fa fa-check\"></i></button></div>", "id");

        echo $this->datatables->generate();
    }
	
    function update_product_group_price($group_id = NULL)
    {
        if (!$group_id) {
            $this->erp->send_json(array('status' => 0));
        }

        $product_id = $this->input->post('product_id', TRUE);
        $price = $this->input->post('price', TRUE);
        if (!empty($product_id) && !empty($price)) {
            if ($this->settings_model->setProductPriceForPriceGroup($product_id, $group_id, $price)) {
                $this->erp->send_json(array('status' => 1));
            }
        }

        $this->erp->send_json(array('status' => 0));
    }
    function testGetProduct(){
        $data=$this->products_model->getProductByCode('LH1-VOL1-01');
        $id=$data->id;
        var_dump($id);die();
    }
    function update_prices_csv($group_id = NULL)
    {

        $this->load->helper('security');
        $this->form_validation->set_rules('userfile', lang("upload_file"), 'xss_clean');

        if ($this->form_validation->run() == true) {

            if (DEMO) {
                $this->session->set_flashdata('message', lang("disabled_in_demo"));
                redirect('welcome');
            }

            if (isset($_FILES["userfile"])) {

                $this->load->library('upload');
                $config['upload_path'] = 'files/';
                $config['allowed_types'] = 'csv';
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = TRUE;
                $config['encrypt_name'] = TRUE;
                $config['max_filename'] = 25;
                $this->upload->initialize($config);

                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect("system_settings/group_product_prices/".$group_id);
                }

                $csv = $this->upload->file_name;

                $arrResult = array();
                $handle = fopen('files/' . $csv, "r");
                if ($handle) {
                    while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        $arrResult[] = $row;
                    }
                    fclose($handle);
                }
                $titles = array_shift($arrResult);

                $keys = array('product_code', 'unit_id','price','currency_code');
                $final = array();

                foreach ($arrResult as $key => $value) {
                    $final[] = array_combine($keys, $value);
                }

                //$this->erp->print_arrays($final);die();
                $rw = 2;
                foreach ($final as $csv_pr) {

                    //$unit_type=$this->settings_model->getUnitTypeById(1);
                    //$this->erp->print_arrays($unit_type);die();
                    if ($product=$this->products_model->getProductByCode(($csv_pr['product_code']))) {
                        $data[] = array(
                            'id'=>'',
                            'product_id' => $product->id,
                            'unit_id' =>$csv_pr['unit_id'],
                            'unit_type' =>$this->settings_model->getUnitTypeById($csv_pr['unit_id'],$product->id),
                            'price_group_id' => $group_id,
                            'price' => $csv_pr['price'],
                            'currency_code'=> $csv_pr['currency_code']

                        );
                    } else {
                        $this->session->set_flashdata('message', lang("check_product_code") . " (" . $csv_pr['product_code'] . "). " . lang("code_x_exist") . " " . lang("line_no") . " " . $rw);
                        redirect("system_settings/group_product_prices/".$group_id);
                    }
                    $rw++;
                }
                //$this->erp->print_arrays($data);die();
               // echo $this->db->insert("product_prices",$data);die();
            }

        } elseif ($this->input->post('update_price')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect("system_settings/group_product_prices/".$group_id);
        }

        if ($this->form_validation->run() == true && !empty($data)) {
            $this->settings_model->updateGroupPrices($data);
            //var_dump($data);die();
            $this->session->set_flashdata('message', lang("price_updated"));
            redirect("system_settings/group_product_prices/".$group_id);
        } else {

            $this->data['userfile'] = array('name' => 'userfile',
                'id' => 'userfile',
                'type' => 'text',
                'value' => $this->form_validation->set_value('userfile')
            );
            $this->data['group'] = $this->site->getPriceGroupByID($group_id);
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme.'settings/update_price', $this->data);

        }
    }

    function brands()
    {
        $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('system_settings'), 'page' => lang('system_settings')), array('link' => '#', 'page' => lang('brands')));
        $meta = array('page_title' => lang('brands'), 'bc' => $bc);
        $this->page_construct('settings/brands', $meta, $this->data);
    }

    function getBrands()
    {
		$print_barcode = anchor('products/print_barcodes/?category=$1', '<i class="fa fa-print"></i>', 'title="'.lang('print_barcodes').'" class="tip"');
        $this->load->library('datatables');
        $this->datatables
            ->select("id, image, code, name", FALSE)
            ->from("brands")
            ->add_column("Actions", "<div class=\"text-center\"><a href='" . site_url('system_settings/categories/$1') . "' class='tip' title='" . lang("categories") . "'><i class=\"fa fa-list\"></i></a> ".$print_barcode." <a href='" . site_url('system_settings/edit_brand/$1') . "' data-toggle='modal' data-target='#myModal' class='tip' title='" . lang("edit_brand") . "'><i class=\"fa fa-edit\"></i></a> <a href='#' class='tip po' title='<b>" . lang("delete_brand") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('system_settings/delete_brand/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></div>", "id");

        echo $this->datatables->generate();
    }

    function add_brand()
    {

        $this->form_validation->set_rules('name', lang("brand_name"), 'trim|required|is_unique[brands.name]|alpha_numeric_spaces');

        if ($this->form_validation->run() == true) {

            $data = array(
                'name' => $this->input->post('name'),
                'code' => $this->input->post('code'),
                );

            if ($_FILES['userfile']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->upload_path;
                $config['allowed_types'] = $this->image_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['max_width'] = $this->Settings->iwidth;
                $config['max_height'] = $this->Settings->iheight;
                $config['overwrite'] = FALSE;
                $config['encrypt_name'] = TRUE;
                $config['max_filename'] = 25;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                $data['image'] = $photo;
                $this->load->library('image_lib');
                $config['image_library'] = 'gd2';
                $config['source_image'] = $this->upload_path . $photo;
                $config['new_image'] = $this->thumbs_path . $photo;
                $config['maintain_ratio'] = TRUE;
                $config['width'] = $this->Settings->twidth;
                $config['height'] = $this->Settings->theight;
                $this->image_lib->clear();
                $this->image_lib->initialize($config);
                if (!$this->image_lib->resize()) {
                    echo $this->image_lib->display_errors();
                }
                $this->image_lib->clear();
            }

        } elseif ($this->input->post('add_brand')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect("system_settings/brands");
        }

        if ($this->form_validation->run() == true && $this->settings_model->addBrand($data)) {
            $this->session->set_flashdata('message', lang("brand_added"));
            redirect("system_settings/brands");
        } else {

            $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/add_brand', $this->data);

        }
    }

    function edit_brand($id = NULL)
    {

        $this->form_validation->set_rules('name', lang("brand_name"), 'trim|required|alpha_numeric_spaces');
        $brand_details = $this->site->getBrandByID($id);
        if ($this->input->post('name') != $brand_details->name) {
            $this->form_validation->set_rules('name', lang("brand_name"), 'is_unique[brands.name]');
        }

        if ($this->form_validation->run() == true) {

            $data = array(
                'name' => $this->input->post('name'),
                'code' => $this->input->post('code'),
                );

            if ($_FILES['userfile']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->upload_path;
                $config['allowed_types'] = $this->image_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['max_width'] = $this->Settings->iwidth;
                $config['max_height'] = $this->Settings->iheight;
                $config['overwrite'] = FALSE;
                $config['encrypt_name'] = TRUE;
                $config['max_filename'] = 25;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                $data['image'] = $photo;
                $this->load->library('image_lib');
                $config['image_library'] = 'gd2';
                $config['source_image'] = $this->upload_path . $photo;
                $config['new_image'] = $this->thumbs_path . $photo;
                $config['maintain_ratio'] = TRUE;
                $config['width'] = $this->Settings->twidth;
                $config['height'] = $this->Settings->theight;
                $this->image_lib->clear();
                $this->image_lib->initialize($config);
                if (!$this->image_lib->resize()) {
                    echo $this->image_lib->display_errors();
                }
                $this->image_lib->clear();
            }

        } elseif ($this->input->post('edit_brand')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect("system_settings/brands");
        }

        if ($this->form_validation->run() == true && $this->settings_model->updateBrand($id, $data)) {
            $this->session->set_flashdata('message', lang("brand_updated"));
            redirect("system_settings/brands");
        } else {

            $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
            $this->data['modal_js'] = $this->site->modal_js();
            $this->data['brand'] = $brand_details;
            $this->load->view($this->theme . 'settings/edit_brand', $this->data);

        }
    }

    function delete_brand($id = NULL)
    {

        if ($this->settings_model->brandHasProducts($id)) {
            $this->session->set_flashdata('error', lang("brand_has_products"));
            redirect("system_settings/brands");
        }

        if ($this->settings_model->deleteBrand($id)) {
            echo lang("brand_deleted");
        }
    }

    function import_brands()
    {

        $this->load->helper('security');
        $this->form_validation->set_rules('userfile', lang("upload_file"), 'xss_clean');

        if ($this->form_validation->run() == true) {

            if (isset($_FILES["userfile"])) {

                $this->load->library('upload');
                $config['upload_path'] = 'files/';
                $config['allowed_types'] = 'csv';
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = TRUE;
                $this->upload->initialize($config);

                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect("system_settings/brands");
                }

                $csv = $this->upload->file_name;

                $arrResult = array();
                $handle = fopen('files/' . $csv, "r");
                if ($handle) {
                    while (($row = fgetcsv($handle, 5000, ",")) !== FALSE) {
                        $arrResult[] = $row;
                    }
                    fclose($handle);
                }
                $titles = array_shift($arrResult);
                $keys = array('name', 'code', 'image');
                $final = array();
                foreach ($arrResult as $key => $value) {
                    $final[] = array_combine($keys, $value);
                }

                foreach ($final as $csv_ct) {
                    if ( ! $this->settings_model->getBrandByName(trim($csv_ct['name']))) {
                        $data[] = array(
                            'code' => trim($csv_ct['code']),
                            'name' => trim($csv_ct['name']),
                            'image' => trim($csv_ct['image']),
                            );
                    }
                }
            }

            // $this->erp->print_arrays($data);
        }

        if ($this->form_validation->run() == true && !empty($data) && $this->settings_model->addBrands($data)) {
            $this->session->set_flashdata('message', lang("brands_added"));
            redirect('system_settings/brands');
        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['userfile'] = array('name' => 'userfile',
                'id' => 'userfile',
                'type' => 'text',
                'value' => $this->form_validation->set_value('userfile')
            );
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme.'settings/import_brands', $this->data);

        }
    }

    function brand_actions()
    {

        $this->form_validation->set_rules('form_action', lang("form_action"), 'required');

        if ($this->form_validation->run() == true) {

            if (!empty($_POST['val'])) {
                if ($this->input->post('form_action') == 'delete') {
                    foreach ($_POST['val'] as $id) {
                        $this->settings_model->deleteBrand($id);
                    }
                    $this->session->set_flashdata('message', lang("brands_deleted"));
                    redirect($_SERVER["HTTP_REFERER"]);
                }

                if ($this->input->post('form_action') == 'export_excel' || $this->input->post('form_action') == 'export_pdf') {

                    $this->load->library('excel');
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle(lang('brands'));
                    $this->excel->getActiveSheet()->SetCellValue('A1', lang('name'));
                    $this->excel->getActiveSheet()->SetCellValue('B1', lang('code'));
                    $this->excel->getActiveSheet()->SetCellValue('C1', lang('image'));

                    $row = 2;
                    foreach ($_POST['val'] as $id) {
                        $brand = $this->site->getBrandByID($id);
                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $brand->name);
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $brand->code);
                        $this->excel->getActiveSheet()->SetCellValue('C' . $row, $brand->image);
                        $row++;
                    }

                    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $filename = 'categories_' . date('Y_m_d_H_i_s');
                    if ($this->input->post('form_action') == 'export_pdf') {
                        $styleArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
                        $this->excel->getDefaultStyle()->applyFromArray($styleArray);
                        $this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
                        require_once(APPPATH . "third_party" . DIRECTORY_SEPARATOR . "MPDF" . DIRECTORY_SEPARATOR . "mpdf.php");
                        $rendererName = PHPExcel_Settings::PDF_RENDERER_MPDF;
                        $rendererLibrary = 'MPDF';
                        $rendererLibraryPath = APPPATH . 'third_party' . DIRECTORY_SEPARATOR . $rendererLibrary;
                        if (!PHPExcel_Settings::setPdfRenderer($rendererName, $rendererLibraryPath)) {
                            die('Please set the $rendererName: ' . $rendererName . ' and $rendererLibraryPath: ' . $rendererLibraryPath . ' values' .
                                PHP_EOL . ' as appropriate for your directory structure');
                        }

                        header('Content-Type: application/pdf');
                        header('Content-Disposition: attachment;filename="' . $filename . '.pdf"');
                        header('Cache-Control: max-age=0');

                        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'PDF');
                        return $objWriter->save('php://output');
                    }
                    if ($this->input->post('form_action') == 'export_excel') {
                        header('Content-Type: application/vnd.ms-excel');
                        header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
                        header('Cache-Control: max-age=0');

                        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
                        return $objWriter->save('php://output');
                    }

                    redirect($_SERVER["HTTP_REFERER"]);
                }
            } else {
                $this->session->set_flashdata('error', lang("no_record_selected"));
                redirect($_SERVER["HTTP_REFERER"]);
            }
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }
	
	public function product_note($parent_id=NULL)
	{
		$this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
		$this->data['parent_id'] = $parent_id;
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('system_settings'), 'page' => lang('system_settings')), array('link' => '#', 'page' => lang('product_note')));
        $meta = array('page_title' => lang('product_note'), 'bc' => $bc);
        $this->page_construct('settings/product_note', $meta, $this->data);
	}
	
	function getProductnate($parent_id = NULL)
    {
        $this->load->library('datatables');
        $this->datatables
            ->select("id, image, code, name")
            ->from("product_note")
            ->add_column("Actions", "<div class=\"text-center\"><a href='" . site_url('system_settings/edit_product_note/$1') . "' data-toggle='modal' data-target='#myModal' class='tip' title='" . lang("edit_product_note") . "'><i class=\"fa fa-edit\"></i></a> <a href='#' class='tip po' title='<b>" . lang("delete_product_note") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('system_settings/delete_product_note/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></div>", "id");
		
        echo $this->datatables->generate();
    }
	
	function add_product_note()
    {

        $this->load->helper('security');
        $this->form_validation->set_rules('code', lang("code"), 'trim|is_unique[categories.code]|required');
        $this->form_validation->set_rules('name', lang("name"), 'required|min_length[3]');
        $this->form_validation->set_rules('userfile', lang("category_image"), 'xss_clean');

        if ($this->form_validation->run() == true) {
            $name = $this->input->post('name');
            $code = $this->input->post('code');
		
            if ($_FILES['userfile']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->upload_path;
                $config['allowed_types'] = $this->image_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['max_width'] = $this->Settings->iwidth;
                $config['max_height'] = $this->Settings->iheight;
                $config['overwrite'] = FALSE;
                $config['encrypt_name'] = TRUE;
                $config['max_filename'] = 25;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                //$data['image'] = $photo;
                $this->load->library('image_lib');
                $config['image_library'] = 'gd2';
                $config['source_image'] = $this->upload_path . $photo;
                $config['new_image'] = $this->thumbs_path . $photo;
                $config['maintain_ratio'] = TRUE;
                $config['width'] = $this->Settings->twidth;
                $config['height'] = $this->Settings->theight;
                $this->image_lib->clear();
                $this->image_lib->initialize($config);
                if (!$this->image_lib->resize()) {
                    echo $this->image_lib->display_errors();
                }
                if ($this->Settings->watermark) {
                    $this->image_lib->clear();
                    $wm['source_image'] = $this->upload_path . $photo;
                    $wm['wm_text'] = 'Copyright ' . date('Y') . ' - ' . $this->Settings->site_name;
                    $wm['wm_type'] = 'text';
                    $wm['wm_font_path'] = 'system/fonts/texb.ttf';
                    $wm['quality'] = '100';
                    $wm['wm_font_size'] = '16';
                    $wm['wm_font_color'] = '999999';
                    $wm['wm_shadow_color'] = 'CCCCCC';
                    $wm['wm_vrt_alignment'] = 'top';
                    $wm['wm_hor_alignment'] = 'right';
                    $wm['wm_padding'] = '10';
                    $this->image_lib->initialize($wm);
                    $this->image_lib->watermark();
                }
                $this->image_lib->clear();
                $config = NULL;
            } else {
                $photo = NULL;
            }
			$data = array(  
					'code' => $code,
					'name' => $name, 
					'image' => $photo
				);
        } elseif ($this->input->post('add_product_note')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER['HTTP_REFERER']);
        }

        if ($this->form_validation->run() == true && $this->settings_model->addProductNote($data)) {
            $this->session->set_flashdata('message', lang("product_not_added"));
				redirect($_SERVER['HTTP_REFERER']);
        } else {
            $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');

            $this->data['name'] = array('name' => 'name',
                'id' => 'name',
                'type' => 'text',
                'class' => 'form-control',
                'required' => 'required',
                'value' => $this->form_validation->set_value('name'),
            );
            $this->data['code'] = array('name' => 'code',
                'id' => 'code',
                'type' => 'text',
                'class' => 'form-control',
                'required' => 'required',
                'value' => $this->form_validation->set_value('code'),
            );
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/add_product_note', $this->data);
        }
    }

    function edit_product_note($id = NULL)
    {
        $this->load->helper('security');
        $this->form_validation->set_rules('code', lang("code"), 'trim|required');
        $pr_details = $this->settings_model->getCategoryByID($id);
        if ($this->input->post('code') != $pr_details->code) {
            $this->form_validation->set_rules('code', lang("code"), 'is_unique[categories.code]');
        }
        $this->form_validation->set_rules('name', lang("name"), 'required|min_length[3]');
        $this->form_validation->set_rules('userfile', lang("category_image"), 'xss_clean');

        if ($this->form_validation->run() == true) {

            if ($_FILES['userfile']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->upload_path;
                $config['allowed_types'] = $this->image_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['max_width'] = $this->Settings->iwidth;
                $config['max_height'] = $this->Settings->iheight;
                $config['overwrite'] = FALSE;
                $config['encrypt_name'] = TRUE;
                $config['max_filename'] = 25;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                //$data['image'] = $photo;
                $this->load->library('image_lib');
                $config['image_library'] = 'gd2';
                $config['source_image'] = $this->upload_path . $photo;
                $config['new_image'] = $this->thumbs_path . $photo;
                $config['maintain_ratio'] = TRUE;
                $config['width'] = $this->Settings->twidth;
                $config['height'] = $this->Settings->theight;
                $this->image_lib->clear();
                $this->image_lib->initialize($config);
                if (!$this->image_lib->resize()) {
                    echo $this->image_lib->display_errors();
                }
                if ($this->Settings->watermark) {
                    $this->image_lib->clear();
                    $wm['source_image'] = $this->upload_path . $photo;
                    $wm['wm_text'] = 'Copyright ' . date('Y') . ' - ' . $this->Settings->site_name;
                    $wm['wm_type'] = 'text';
                    $wm['wm_font_path'] = 'system/fonts/texb.ttf';
                    $wm['quality'] = '100';
                    $wm['wm_font_size'] = '16';
                    $wm['wm_font_color'] = '999999';
                    $wm['wm_shadow_color'] = 'CCCCCC';
                    $wm['wm_vrt_alignment'] = 'top';
                    $wm['wm_hor_alignment'] = 'right';
                    $wm['wm_padding'] = '10';
                    $this->image_lib->initialize($wm);
                    $this->image_lib->watermark();
                }
                $this->image_lib->clear();
                $config = NULL;
            } else {
                $photo = NULL;
            }
			$data = array(
				'code' => $this->input->post('code'),
				'name' => $this->input->post('name')
			);
        } elseif ($this->input->post('edit_product_note')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER['HTTP_REFERER']);
        }

        if ($this->form_validation->run() == true && $this->settings_model->updateProductNote($id, $data, $photo)) {
            $this->session->set_flashdata('message', lang("product_note_updated"));
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
            $category = $this->settings_model->getProductNoteByID($id);
            $this->data['name'] = array('name' => 'name',
                'id' => 'name',
                'type' => 'text',
                'class' => 'form-control',
                'required' => 'required',
                'value' => $this->form_validation->set_value('name', $category->name),
            );
            $this->data['code'] = array('name' => 'code',
                'id' => 'code',
                'type' => 'text',
                'class' => 'form-control',
                'required' => 'required',
                'value' => $this->form_validation->set_value('code', $category->code),
            );
			$this->data['category'] = $this->settings_model->getProductNoteByID($id);
            $this->data['id'] = $id;
            $this->load->view($this->theme . 'settings/edit_product_note', $this->data);
        }
    }

    function delete_product_note($id = NULL)
    {
        if ($this->settings_model->delete_product_note($id)) {
            echo lang("product_note_deleted");
        }
    }

	function show_note()
	{
		$this->data['rows'] = $this->settings_model->getAllProductNote();
		$this->load->view($this->theme . 'settings/show_note', $this->data);
	}
	
	function define_principle()
    {
        if (!$this->Owner) {
            $this->session->set_flashdata('error', lang("access_denied"));
            redirect('auth');
        }
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['rows'] = $this->settings_model->getprinciple_types();
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('system_settings'), 'page' => lang('system_settings')), array('link' => '#', 'page' => lang('define_principle')));
        $meta = array('page_title' => lang('define_principle'), 'bc' => $bc);
        $this->page_construct('settings/define_principle', $meta, $this->data);
    }
	
	function create_define_principle()
    {

        $this->form_validation->set_rules('code', lang('code'), 'required|is_unique[term_types.code]');
		 $this->form_validation->set_rules('name', lang('name'), 'required|is_unique[term_types.name]');
       

        if ($this->form_validation->run() == TRUE) {
            $data = array('code' => $this->input->post('code'), 'name' => $this->input->post('name'));
            if($this->settings_model->add_define_principle($data)){
                $this->session->set_flashdata('message', lang('define_principle_added'));
                redirect("system_settings/define_principle");
            }
        } else {
			
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
			if($this->data['error']){
				$this->session->set_flashdata('warning', lang('data is duplicate.'));
				 redirect("system_settings/define_principle");
			}
          
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/create_define_principle', $this->data);
        }
    }
	
	function edit_define_principle($id)
    {

		$da = $this->settings_model->getprinciple_typesBYID($id);
		if($da->code == $this->input->post('code')){
			$this->form_validation->set_rules('code', lang('code'), 'required');
		}else{
			$this->form_validation->set_rules('code', lang('code'), 'required|is_unique[term_types.code]');
		}
		if($da->name == $this->input->post('name')){
			$this->form_validation->set_rules('name', lang('name'), 'required');
		}else{
			$this->form_validation->set_rules('name', lang('name'), 'required|is_unique[term_types.name]');
		}
        if ($this->form_validation->run() === TRUE) {
            $data = array('code' => $this->input->post('code'), 'name' => $this->input->post('name'));
            if($this->settings_model->updatedefine_principle($id, $data)){
                $this->session->set_flashdata('message', lang('define_principle_udpated'));
				 redirect("system_settings/define_principle");
            }		
           
        } else {

			
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
			if($this->data['error']){
				$this->session->set_flashdata('warning', lang('data is duplicate.'));
				 redirect("system_settings/define_principle");
			}
          //$this->erp->print_arrays($this->settings_model->getprinciple_typesBYID($id));
			$this->data['row'] =$da ;
			
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/edit_define_principle', $this->data);
        }
    }
	
	public function delete_define_principle($id)
	{
		if($this->settings_model->delete_define_principle($id)){
			 $this->session->set_flashdata('message', lang('define_principle_deleted'));
			redirect("system_settings/define_principle");
		}else{
			$this->session->set_flashdata('warning', lang('deleting is error.'));
			 redirect("system_settings/define_principle");
		}
	}
	
	function define_principle_rate($id)
    {
        if (!$this->Owner) {
            $this->session->set_flashdata('error', lang("access_denied"));
            redirect('auth');
        }
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        //$this->data['rows'] = $this->settings_model->getprinciple_types();
		$this->data['principle_data'] = $this->settings_model->getAll_define_principle_rate($id);
		$this->data['id'] = $id;
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('system_settings'), 'page' => lang('system_settings')), array('link' => '#', 'page' => lang('define_principle_rate')));
        $meta = array('page_title' => lang('define_principle_rate'), 'bc' => $bc);
        $this->page_construct('settings/define_principle_rate', $meta, $this->data);
    }
	
	function create_define_public_charge_rate($id)
    {

        $this->form_validation->set_rules('period', lang('period'), 'required');
		$this->form_validation->set_rules('dateline', lang('dateline'), 'required');
		$this->form_validation->set_rules('value', lang('value'), 'required');
		
        if ($this->form_validation->run() == TRUE) {
			if(!$this->input->post('rate')){
				$rate = 0;
			}else{
				$rate = 1;
			}
            $data = array(
				'period' => $this->input->post('period'),
				'term_type_id' =>$id,
				'dateline' => $this->erp->fld($this->input->post('dateline')),
				'value' => $this->input->post('value'),
				'remark' => $this->input->post('remark'),
				'rate' => $rate
				
			);
		
			if($this->settings_model->add_define_principle_rate($data)){
				$this->session->set_flashdata('message', lang('define_principle_rate_added'));
				redirect("system_settings/define_principle_rate/".$id);
			}
			
        } else {
			
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
			if($this->data['error']){
				$this->session->set_flashdata('warning', validation_errors() ? validation_errors() : $this->session->flashdata('error'));
				 redirect("system_settings/define_principle_rate");
			}
            
			$this->data['id'] = $id;
			$this->data['inc'] = $this->settings_model->getmaxid($id);
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/create_define_principle_rate', $this->data);
        }
    }
	
	function define_public_charge_amount($id)
	{
		
		$this->form_validation->set_rules('period', lang('period'), 'required');
		$this->form_validation->set_rules('dateline', lang('dateline'), 'required');
		$this->form_validation->set_rules('description', lang('description'), 'required');
		
        if ($this->form_validation->run() == TRUE) {
			
            $data = array(
				'period'       => $this->input->post('period'),
				'date'     => $this->erp->fld($this->input->post('dateline')),
				'description'  => $this->input->post('description'),
				'amount'       => $this->input->post('amount'),
				'pub_id'       => $this->input->post('pub_id')
			);
		
			if($this->settings_model->add_public_charge_amount($data)){
				$this->session->set_flashdata('message', lang('define_public_charge_amount_added'));
				redirect("system_settings/public_charge_amount/".$id);
			}
			
        } else {
			
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
			if($this->data['error']){
				$this->session->set_flashdata('warning', validation_errors() ? validation_errors() : $this->session->flashdata('error'));
				 redirect("system_settings/public_charge_amount");
			}
            
			$this->data['id'] = $id;
			$this->data['inc'] = $this->settings_model->getmaxid($id);
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/define_public_charge_amount', $this->data);
        }
	}
	
	function create_define_principle_rate($id)
    {

        $this->form_validation->set_rules('period', lang('period'), 'required');
		$this->form_validation->set_rules('dateline', lang('dateline'), 'required');
		$this->form_validation->set_rules('value', lang('value'), 'required');
		
        if ($this->form_validation->run() == TRUE) {
			if(!$this->input->post('rate')){
				$rate = 0;
			}else{
				$rate = 1;
			}
            $data = array(
				'period' => $this->input->post('period'),
				'term_type_id' =>$id,
				'dateline' => $this->erp->fld($this->input->post('dateline')),
				'value' => $this->input->post('value'),
				'remark' => $this->input->post('remark'),
				'rate' => $rate
				
			);
		
			if($this->settings_model->add_define_principle_rate($data)){
				$this->session->set_flashdata('message', lang('define_principle_rate_added'));
				redirect("system_settings/define_principle_rate/".$id);
			}
			
        } else {
			
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
			if($this->data['error']){
				$this->session->set_flashdata('warning', validation_errors() ? validation_errors() : $this->session->flashdata('error'));
				 redirect("system_settings/define_principle_rate");
			}
            
			$this->data['id'] = $id;
			$this->data['inc'] = $this->settings_model->getmaxid($id);
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/create_define_principle_rate', $this->data);
        }
    }
	
	function delete_define_principle_rate_byid($id,$mid)
	{
		if($this->settings_model->delete_define_principle_rate_byid($id)){
					$this->session->set_flashdata('message', lang('define_principle_rate_deleted'));
					redirect("system_settings/define_principle_rate/".$mid);
		}
	}
	
	function update_define_principle_rate_byid($id,$mid)
    {

      $this->form_validation->set_rules('period', lang('period'), 'required');
		$this->form_validation->set_rules('dateline', lang('dateline'), 'required');
		$this->form_validation->set_rules('value', lang('value'), 'required');
		
        if ($this->form_validation->run() === TRUE) {
			if(!$this->input->post('rate')){
				$rate = 0;
			}else{
				$rate = 1;
			}
           $data = array(
				'period' => $this->input->post('period'),
				'term_type_id' =>$id,
				'dateline' => $this->erp->fld($this->input->post('dateline')),
				'value' => $this->input->post('value'),
				'remark' => $this->input->post('remark'),
				'rate' => $rate
				
			);
            if($this->settings_model->update_define_principle_rate_byid($id, $data)){
                $this->session->set_flashdata('message', lang('define_principle_rate_udpated'));
				 redirect("system_settings/define_principle_rate/".$mid);
            }		
           
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
			if($this->data['error']){
				$this->session->set_flashdata('warning', validation_errors());
				 redirect("system_settings/define_principle_rate/".$mid);
			}
           $da = $this->settings_model->getdefine_principle_ratebyid($id);
           $this->data['id'] = $id;
		   $this->data['mid'] = $mid;
			  $this->data['row'] = $da;
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/update_define_principle_rate_byid', $this->data);
        }
    }
	
	public function delete_define_public_charge($pub_detial_id,$pub_id)
	{
		if($this->db->delete('public_charge_detail', array('id' => $pub_detial_id,'pub_id'=>$pub_id))){
			$this->session->set_flashdata('message', lang('public_charge_deleted'));
			redirect("system_settings/public_charge_amount/".$pub_id);
		}
	}
	
	public function delete_public_charge($pub_id)
	{
		if($this->db->delete('erp_define_public_charge', array('id'=>$pub_id))){
			$this->session->set_flashdata('message', lang('public_charge_deleted'));
			redirect("system_settings/public_charge/".$pub_id);
		}
	}
	
	public function update_define_public_charge_byid($pub_detial_id=NULL,$pub_id=NULL)
	{
		
		$this->form_validation->set_rules('period', lang('period'), 'required');
		$this->form_validation->set_rules('dateline', lang('dateline'), 'required');
		$this->form_validation->set_rules('description', lang('description'), 'required');
		$l_pub_id  = $this->input->post('pub_id');
		$l_id      = $this->input->post('period');
		$id        = NULL;
		
        if ($this->form_validation->run() == TRUE) {
			
            $data = array(
				'period'       => $this->input->post('period'),
				'date'     	   => $this->erp->fld($this->input->post('dateline')),
				'description'  => $this->input->post('description'),
				'amount'       => $this->input->post('amount'),
				'pub_id'       => $this->input->post('pub_id')
			);
		
			if($this->settings_model->edit_public_charge_amount($data,$l_id,$l_pub_id)){
				$this->session->set_flashdata('message', lang('define_public_charge_amount_edited'));
				redirect("system_settings/public_charge_amount/".$l_pub_id);
			}
			
        } else {
			
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
			if($this->data['error']){
				$this->session->set_flashdata('warning', validation_errors() ? validation_errors() : $this->session->flashdata('error'));
				 redirect("system_settings/public_charge_amount");
			}
            
			$this->data['id']   = $pub_id;
			$this->data['data'] = $this->settings_model->getPublicChargeAmountById($pub_detial_id,$pub_id);
			$this->data['inc']  = $this->settings_model->getmaxid($id);
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/edit_public_charge_amount', $this->data);
        }
	}
	
	public function updateperiod($id){
			if($id){
				if($this->input->post('period')){
					$period = $this->input->post('period');
					if($this->settings_model->updateperiod($id,$period)){
						echo json_encode(true);
					}else{
						echo json_encode(false);
					}
				}
	
			}
			echo json_encode(false);
		}
	// #chanthy ---------------------------------------------------------
	function reasons($parent_id = NULL)
    {

        $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
		$this->data['parent_id'] = $parent_id;
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('system_settings'), 'page' => lang('system_settings')), array('link' => '#', 'page' => lang('reasons')));
        $meta = array('page_title' => lang('reasons'), 'bc' => $bc);
        $this->page_construct('settings/reasons', $meta, $this->data);
    }
	
	function add_group_position($parent_id = NULL)
    {

        $this->load->helper('security');
        $this->form_validation->set_rules('code', lang("code"), 'trim|is_unique[reasons.code]|required');
        $this->form_validation->set_rules('name', lang("name"), 'required');

        if ($this->form_validation->run() == true) {
            $code = $this->input->post('code');
            $name = $this->input->post('name');
			$data = array(  
						'code' => $code,
						'name' => $name
			);
			//$this->erp->print_arrays($data);
			
        } elseif ($this->input->post('add_group_position')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER['HTTP_REFERER']);
        }

        if ($this->form_validation->run() == true && $this->settings_model->addGroupPosition($data)) {
            $this->session->set_flashdata('message', lang("group_position_added"));
			redirect($_SERVER['HTTP_REFERER']);
        } else {
            $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');

            $this->data['name'] = array('name' => 'name',
                'id' => 'name',
                'type' => 'text',
                'class' => 'form-control',
                'required' => 'required',
                'value' => $this->form_validation->set_value('name'),
            );
            $this->data['code'] = array('name' => 'code',
                'id' => 'code',
                'type' => 'text',
                'class' => 'form-control',
                'required' => 'required',
                'value' => $this->form_validation->set_value('code'),
            );
			$this->load->model('accounts_model');
			$this->data['chart_accounts'] = $this->accounts_model->getAllChartAccounts();
			$this->data['parent_id'] = $parent_id;
			$this->data['brand'] = $this->site->getAllBrands();
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/add_group_position', $this->data);
        }
    }
	
	function edit_group_position($id = NULL)
    {
        $this->load->helper('security');
        $this->form_validation->set_rules('code', lang("code"), 'trim|required');
        $pr_details = $this->settings_model->getCategoryByID($id);
        if ($this->input->post('code') != $pr_details->code) {
            $this->form_validation->set_rules('code', lang("code"), 'is_unique[categories.code]');
        }
        $this->form_validation->set_rules('name', lang("name"), 'required');

        if ($this->form_validation->run() == true) {
            
			$data = array(
							'code' => $this->input->post('code'),
							'name' => $this->input->post('name')
						 );
        } elseif ($this->input->post('edit_group_position')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER['HTTP_REFERER']);
        }

        if ($this->form_validation->run() == true && $this->settings_model->updateGroupPosition($id, $data)) {
            $this->session->set_flashdata('message', lang("group_position_updated"));
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
            $position = $this->settings_model->getPositionByID($id);
            $this->data['name'] = array('name' => 'name',
                'id' => 'name',
                'type' => 'text',
                'class' => 'form-control',
                'required' => 'required',
                'value' => $this->form_validation->set_value('name', $position->name),
            );
            $this->data['code'] = array('name' => 'code',
                'id' => 'code',
                'type' => 'text',
                'class' => 'form-control',
                'required' => 'required',
                'value' => $this->form_validation->set_value('code', $position->code),
            );
			$this->load->model('accounts_model');
			$this->data['chart_accounts'] = $this->accounts_model->getAllChartAccounts();
			$this->data['position'] = $this->settings_model->getCategoryByID($id);
			$this->data['brand'] = $this->site->getAllBrands();
            $this->data['modal_js'] = $this->site->modal_js();
            $this->data['id'] = $id;
            $this->load->view($this->theme . 'settings/edit_group_position', $this->data);
        }
    }
	
	function add_reason($parent_id = NULL)
    {
        $this->load->helper('security');
        $this->form_validation->set_rules('position', lang("position"), 'required');
        $this->form_validation->set_rules('code', lang("code"), 'trim|is_unique[categories.code]|is_unique[subcategories.code]|required');
        $this->form_validation->set_rules('description', lang("description"), 'required');

        if ($this->form_validation->run() == true) {
            $code = $this->input->post('code');
            $name = $this->input->post('description');
            $position = $this->input->post('position');
			$data = array(
						'code' => $code,
						'description' => $name,
						'position_id' => $position
			);
        } elseif ($this->input->post('add_reason')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect("system_settings/reasons");
        }

        if ($this->form_validation->run() == true && $this->settings_model->addReason($data)) {
            $this->session->set_flashdata('message', lang("reason_added"));
			if (strpos($_SERVER['HTTP_REFERER'], 'system_settings/add_group_position') !== false) {
				 redirect("system_settings/add_reason", 'refresh');
			}else{
				 redirect("system_settings/reasons");
				 //echo "You are here!";
			}
        } else {
            $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');

            $this->data['name'] = array('name' => 'description',
                'id' => 'name',
                'type' => 'text', 'class' => 'form-control',
                'class' => 'form-control',
                'required' => 'required',
                'value' => $this->form_validation->set_value('name'),
            );
            $this->data['code'] = array('name' => 'code',
                'id' => 'code',
                'type' => 'text',
                'class' => 'form-control',
                'required' => 'required',
                'value' => $this->form_validation->set_value('code'),
            );
            $this->data['parent_id'] = $parent_id;
            $this->data['modal_js'] = $this->site->modal_js();
            $this->data['positions'] = $this->settings_model->getAllPositions();
            $this->load->view($this->theme . 'settings/add_reason', $this->data);
        }
    }
	
	function edit_reason($id = NULL)
    {

        $this->load->helper('security');
        $this->form_validation->set_rules('position', lang("position"), 'required');
        $this->form_validation->set_rules('code', lang("code"), 'trim|required');
        $pr_details = $this->settings_model->getSubCategoryByID($id);
        $photo = $this->upload->file_name;
        if ($this->input->post('code') != $pr_details->code) {
            $this->form_validation->set_rules('code', lang("code"), 'is_unique[position.code]');
        }
        $this->form_validation->set_rules('description', lang("description"), 'required');
        $this->form_validation->set_rules('userfile', lang("description"), 'xss_clean');

        if ($this->form_validation->run() == true) {
            $data = array(
                'position_id' => $this->input->post('position'),
                'code' => $this->input->post('code'),
                'description' => $this->input->post('description')
            );            
        } elseif ($this->input->post('edit_reason')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect("system_settings/reasons");
        }

        if ($this->form_validation->run() == true && $this->settings_model->updateReason($id, $data, $photo)) {
            $this->session->set_flashdata('message', lang("reason_updated"));
            redirect("system_settings/reasons");
        } else {
             $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
            $position = $this->settings_model->getPositionByID($id);
            $this->data['name'] = array('name' => 'description',
                'id' => 'name',
                'type' => 'text',
                'class' => 'form-control',
                'required' => 'required',
                'value' => $this->form_validation->set_value('name', $position->description),
            );
            $this->data['code'] = array('name' => 'code',
                'id' => 'code',
                'type' => 'text',
                'class' => 'form-control',
                'required' => 'required',
                'value' => $this->form_validation->set_value('code', $position->code),
            );
			$this->load->model('accounts_model');
			$this->data['chart_accounts'] = $this->accounts_model->getAllChartAccounts();
			$this->data['positions'] = $this->settings_model->getAllPositions();
			$this->data['reason'] = $this->settings_model->getReasonByID($id);
            $this->data['modal_js'] = $this->site->modal_js();
            $this->data['id'] = $id;
            $this->load->view($this->theme . 'settings/edit_reason', $this->data);
        }
    }
	
	public function define_frequency()
	{
		if (!$this->Owner) {
            $this->session->set_flashdata('error', lang("access_denied"));
            redirect('auth');
        }
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['rows'] = $this->settings_model->getfrequency();
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('system_settings'), 'page' => lang('system_settings')), array('link' => '#', 'page' => lang('define_principle')));
        $meta = array('page_title' => lang('frequency'), 'bc' => $bc);
        $this->page_construct('settings/frequency', $meta, $this->data);
	}
	
	function create_define_frequency()
    {

        $this->form_validation->set_rules('description', lang('description'), 'required');
		 $this->form_validation->set_rules('day', lang('day'), 'required');
       

        if ($this->form_validation->run() == TRUE) {
            $data = array('description' => $this->input->post('description'), 'day' => $this->input->post('day'));
            if($this->settings_model->add_define_frequency($data)){
                $this->session->set_flashdata('message', lang('define_frequency_added'));
                redirect("system_settings/define_frequency");
            }
        } else {
			
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
			if($this->data['error']){
				$this->session->set_flashdata('warning', lang('data is duplicate.'));
				 redirect("system_settings/define_frequency");
			}
          
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/create_define_frequency', $this->data);
        }
    }
	
	function edit_define_frequency($id)
    {

       $da = $this->settings_model->getfrequencyBYID($id);
		if($da->description == $this->input->post('description')){
			$this->form_validation->set_rules('description', lang('description'), 'required');
		}else{
			$this->form_validation->set_rules('description', lang('description'), 'required');
		}
		if($da->day == $this->input->post('day')){
			$this->form_validation->set_rules('day', lang('day'), 'required');
		}else{
			$this->form_validation->set_rules('day', lang('day'), 'required');
		}
        if ($this->form_validation->run() === TRUE) {
            $data = array('description' => $this->input->post('description'), 'day' => $this->input->post('day'));
            if($this->settings_model->updatedefine_frequency($id, $data)){
                $this->session->set_flashdata('message', lang('define_frequency_updated'));
				 redirect("system_settings/define_frequency");
            }		
           
        } else {


            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
			if($this->data['error']){
				$this->session->set_flashdata('warning', lang('data is duplicate.'));
				 redirect("system_settings/define_frequency");
			}
          
			  $this->data['row'] = $da;
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/edit_define_frequency', $this->data);
        }
    }
	
	public function define_term()
	{
		if (!$this->Owner) {
            $this->session->set_flashdata('error', lang("access_denied"));
            redirect('auth');
        }
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['rows'] = $this->settings_model->getterms();
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('system_settings'), 'page' => lang('system_settings')), array('link' => '#', 'page' => lang('define_term')));
        $meta = array('page_title' => lang('term'), 'bc' => $bc);
        $this->page_construct('settings/term', $meta, $this->data);
	}
	
	public function add_public_charge()
	{

		$this->form_validation->set_rules('description', lang('description'), 'required|is_unique[define_public_charge.description]');
       
        if ($this->form_validation->run() == TRUE) {
            $data = array('description' => $this->input->post('description'));
            if($this->settings_model->add_public_charge($data)){
                $this->session->set_flashdata('message', lang('define_public_charge_added'));
                redirect("system_settings/public_charge");
            }
        } else {
			
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
			if($this->data['error']){
				$this->session->set_flashdata('warning', lang('data is duplicate.'));
				 redirect("system_settings/public_charge");
			}
          
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/add_public_charge', $this->data);
        }
	}
	
	public function edit_public_charge($id=NULL)
	{

		$this->form_validation->set_rules('description', lang('description'), 'required|is_unique[define_public_charge.description]');
       
        if ($this->form_validation->run() == TRUE) {
            $data   = array('description' => $this->input->post('description'));
			$old_id = $this->input->post('old_id');
            if($this->settings_model->edit_public_charge($data,$old_id)){
                $this->session->set_flashdata('message', lang('define_public_charge_edited'));
                redirect("system_settings/public_charge");
            }
        } else {
			
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
			if($this->data['error']){
				$this->session->set_flashdata('warning', lang('data is duplicate.'));
				 redirect("system_settings/public_charge");
			}
			$this->data['rows']		= $this->settings_model->getPublicChargeById($id);	
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/edit_public_charge', $this->data);
        }
	}
	
	public function public_charge()
	{
		if (!$this->Owner) {
            $this->session->set_flashdata('error', lang("access_denied"));
            redirect('auth');
        }
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['rows'] = $this->settings_model->getpublic_charges();
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('system_settings'), 'page' => lang('system_settings')), array('link' => '#', 'page' => lang('define_public_charge')));
        $meta = array('page_title' => lang('public_charge'), 'bc' => $bc);
        $this->page_construct('settings/public_charge', $meta, $this->data);
	}
	
	public function public_charge_amount($id=NULL)
	{
		if (!$this->Owner) {
            $this->session->set_flashdata('error', lang("access_denied"));
            redirect('auth');
        }
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['rows']  = $this->settings_model->getpublic_charge_details($id);
		$this->data['id']	 = $id;	
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('system_settings'), 'page' => lang('system_settings')), array('link' => '#', 'page' => lang('public_charge_amount')));
        $meta = array('page_title' => lang('public_charge_amount'), 'bc' => $bc);
        $this->page_construct('settings/public_charge_amount', $meta, $this->data);
	}
	
	function create_define_term()
    {

        $this->form_validation->set_rules('description', lang('description'), 'required');
		 $this->form_validation->set_rules('day', lang('day'), 'required');
       

        if ($this->form_validation->run() == TRUE) {
            $data = array('description' => $this->input->post('description'), 'day' => $this->input->post('day'));
            if($this->settings_model->add_define_term($data)){
                $this->session->set_flashdata('message', lang('define_term_added'));
                redirect("system_settings/define_term");
            }
        } else {
			
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
			if($this->data['error']){
				$this->session->set_flashdata('warning', lang('data is duplicate.'));
				 redirect("system_settings/define_term");
			}
          
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/create_define_term', $this->data);
        }
    }
	
	function edit_define_term($id)
    {

       $da = $this->settings_model->gettermsBYID($id);
		if($da->description == $this->input->post('description')){
			$this->form_validation->set_rules('description', lang('description'), 'required');
		}else{
			$this->form_validation->set_rules('description', lang('description'), 'required');
		}
		if($da->day == $this->input->post('day')){
			$this->form_validation->set_rules('day', lang('day'), 'required');
		}else{
			$this->form_validation->set_rules('day', lang('day'), 'required');
		}
        if ($this->form_validation->run() === TRUE) {
            $data = array('description' => $this->input->post('description'), 'day' => $this->input->post('day'));
            if($this->settings_model->updatedefine_term($id, $data)){
                $this->session->set_flashdata('message', lang('define_term_updated'));
				 redirect("system_settings/define_term");
            }		
           
        } else {


            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
			if($this->data['error']){
				$this->session->set_flashdata('warning', lang('data is duplicate.'));
				 redirect("system_settings/define_term");
			}
          
			$this->data['row'] = $da;
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/edit_define_term', $this->data);
        }
    }
	
	// -/--------------------------------------------------------
    function getCategoryAll($pdf = NULL, $excel = NULL)
    {
        if ($pdf || $excel) {
            // get Categories
            $this->db
                ->select("id, code, name,image")
                ->from("categories");
            $cates = $this->db->get();

            if (!empty($cates)) {

                $this->load->library('excel');
                $this->excel->setActiveSheetIndex(0);
                $this->excel->getActiveSheet()->setTitle(lang('Categories'));
                $this->excel->getActiveSheet()->SetCellValue('A1', lang('code'));
                $this->excel->getActiveSheet()->SetCellValue('B1', lang('name'));
                $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
                $this->excel->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);

                $row = 2;
                
                foreach (($cates->result()) as $cate) {
                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $cate->code);
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $cate->name);

                        // get Subcategories
                        $this->db
                            ->select("id,category_id, code, name,image")
                            ->from("subcategories")
                            ->where("category_id", $cate->id);
                        $subcates = $this->db->get();
                     $row++;
                    foreach (($subcates->result()) as $subcate) {
                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, '       '.$subcate->code);
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $subcate->name);
                        
                        $this->excel->getActiveSheet()->getStyle('A' . $row.'')->getFont()->setItalic(true);
                        $this->excel->getActiveSheet()->getStyle('B' . $row.'')->getFont()->setItalic(true);
                        $row++;
                    }
                   
                }

                $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $filename = 'categories' . date('Y_m_d_H_i_s');
                if ($pdf) {
                    $styleArray = array(
                        'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN))
                    );
                    $this->excel->getDefaultStyle()->applyFromArray($styleArray);
                    $this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
                    require_once(APPPATH . "third_party" . DIRECTORY_SEPARATOR . "MPDF" . DIRECTORY_SEPARATOR . "mpdf.php");
                    $rendererName = PHPExcel_Settings::PDF_RENDERER_MPDF;
                    $rendererLibrary = 'MPDF';
                    $rendererLibraryPath = APPPATH . 'third_party' . DIRECTORY_SEPARATOR . $rendererLibrary;
                    if (!PHPExcel_Settings::setPdfRenderer($rendererName, $rendererLibraryPath)) {
                        die('Please set the $rendererName: ' . $rendererName . ' and $rendererLibraryPath: ' . $rendererLibraryPath . ' values' .
                            PHP_EOL . ' as appropriate for your directory structure');
                    }

                    header('Content-Type: application/pdf');
                    header('Content-Disposition: attachment;filename="' . $filename . '.pdf"');
                    header('Cache-Control: max-age=0');

                    $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'PDF');
                    $objWriter->save('php://output');
                    exit();
                }
                if ($excel) {
                    ob_clean();
                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
                    header('Cache-Control: max-age=0');
                    ob_clean();
                    $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
                    $objWriter->save('php://output');
                    exit();
                }

            }

            $this->session->set_flashdata('error', lang('nothing_found'));
            redirect($_SERVER["HTTP_REFERER"]);

        }
    }
	
	function getAllCategories()
	{
		
		$cate_id = $this->site->getAllCategoriesMakeup();
		$categories=array();
		
			for($i=0;$i<sizeof($cate_id);$i++){
				$categories[] = $cate_id[$i];
			}
			
			echo json_encode($categories);
	}
	
	function tax_exchange_rate(){
		$this->load->model('Settings_model');
		$info = $this->Settings_model->get_tax_exchange_rate();	
		$this->data['info'] = $info;	
		$bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('system_settings'), 'page' => lang('system_settings')), array('link' => '#', 'page' => lang('tax_exchange_rate')));
        $meta = array('page_title' => lang('tax_exchange_rate'), 'bc' => $bc);
        $this->page_construct('settings/tax_exchange_rate', $meta, $this->data);
	}
	
	function insert_tax_exchange_rate()
	{		
		
					$usd 	 = $this->input->post('usd');
					$salary_khm 	 = $this->input->post('salary_khm');
					$average_khm 	 = $this->input->post('average_khm');
					$month 	 = $this->input->post('month');
					$year 	 = $this->input->post('year');
			$tax_exchange_rate=array(
					'usd' => $usd,
					'salary_khm' => $salary_khm,
					'average_khm' => $average_khm,
					'month' => $month,
					'year' => $year,
			);	
			$this->load->model('Settings_model');
		$input = $this->Settings_model->add_tax_exchange_rate($tax_exchange_rate);		
		
		if($input){
			$this->session->set_flashdata('message', lang("tax_exchange_rate_added"));
            redirect('system_settings/tax_exchange_rate');
		}
	}
	
	function add_tax_exchange_rate()
	{		
		$this->load->view($this->theme . 'settings/add_tax_exchange_rate');
	}
	
	function delete_tax_exchange_rate($id)
	{		
		$this->load->model('Settings_model');
		$del = $this->Settings_model->delete_tax_exchange_rate($id);	
		if($del){
			$this->session->set_flashdata('message', lang("tax_exchange_rate_deleted"));
            redirect('system_settings/tax_exchange_rate');
		}else{
			$this->session->set_flashdata('message', lang("fail_to_delete_tax_exchange_rate"));
            redirect('system_settings/tax_exchange_rate');
		}
	}
	
	function edit_tax_exchange_rate($id)
	{
		$this->load->model('Settings_model');
		$info = $this->Settings_model->get_one_tax_exchange_rate($id);	
		$this->data['info'] = $info;	
		$this->data['id'] = $id;	

		    $this->load->view($this->theme . 'settings/edit_tax_exchange_rate', $this->data);
	}
	
	function update_tax_exchange_rate($id)
	{
		$usd 	 = $this->input->post('usd');
					$salary_khm 	 = $this->input->post('salary_khm');
					$average_khm 	 = $this->input->post('average_khm');
					$month 	 = $this->input->post('month');
					$year 	 = $this->input->post('year');
			$tax_exchange_rate=array(
					'usd' => $usd,
					'salary_khm' => $salary_khm,
					'average_khm' => $average_khm,
					'month' => $month,
					'year' => $year,
			);	
			$this->load->model('Settings_model');
		$update = $this->Settings_model->update_tax_exchange_rate($id,$tax_exchange_rate);		
		
		if($update){
			$this->session->set_flashdata('message', lang("tax_exchange_rate_updated"));
            redirect('system_settings/tax_exchange_rate');
		}
	}
	
}
