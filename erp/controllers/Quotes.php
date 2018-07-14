<?php defined('BASEPATH') or exit('No direct script access allowed');

class Quotes extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            redirect('login');
        }
        if ($this->Supplier) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER["HTTP_REFERER"]);
        }
		
        $this->lang->load('quotations', $this->Settings->language);
        $this->load->library('form_validation');
        $this->load->model('quotes_model');
        $this->load->model('products_model');
		$this->load->model('sales_model');
        $this->load->model('purchases_model');
        $this->digital_upload_path = 'files/';
        $this->digital_file_types = 'zip|psd|ai|rar|pdf|doc|docx|xls|xlsx|ppt|pptx|gif|jpg|jpeg|png|tif|txt';
        $this->allowed_file_size = '1024';
        $this->data['logo'] = true;

    }

    public function index($warehouse_id = null)
    {
		$this->erp->checkPermissions('index',null,'quotes');
		$this->load->model('reports_model');
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        if ($this->Owner || $this->Admin || !$this->session->userdata('warehouse_id')) {
            $this->data['warehouses'] = $this->site->getAllWarehouses();
            $this->data['warehouse_id'] = $warehouse_id;
            $this->data['warehouse'] = $warehouse_id ? $this->site->getWarehouseByID($warehouse_id) : NULL;

        } else {

            $this->data['warehouses'] = $this->products_model->getUserWarehouses();
            if($warehouse_id){
                $this->data['warehouse_id'] = $warehouse_id;
                $this->data['warehouse'] = $warehouse_id ? $this->site->getWarehouseByID($warehouse_id) : NULL;
            }else{
                $this->data['warehouse_id'] = str_replace(',', '-', $this->session->userdata('warehouse_id'));
                $this->data['warehouse'] = $this->session->userdata('warehouse_id') ? $this->products_model->getUserWarehouses() : NULL;
            }
        }
		$this->data['billers'] = $this->site->getAllCompanies('biller');
		$this->data['users'] = $this->reports_model->getStaff();
		$this->data['products'] = $this->site->getProducts();
		$this->data['agencies'] = $this->site->getAllUsers();
	

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('quotes')));
        $meta = array('page_title' => lang('quotes'), 'bc' => $bc);
        $this->page_construct('quotes/index', $meta, $this->data);

    }

    public function getQuotes($warehouse_id = null)
    {
        $this->erp->checkPermissions('index',null,'quotes');
		if($warehouse_id){
			$warehouse_ids = explode('-',$warehouse_id);
		}
		
		if ($this->input->get('user')) {
            $user_query = $this->input->get('user');
        } else {
            $user_query = NULL;
        }
        if ($this->input->get('reference_no')) {
            $reference_no = $this->input->get('reference_no');
        } else {
            $reference_no = NULL;
        }
        if ($this->input->get('customer')) {
            $customer = $this->input->get('customer');
        } else {
            $customer = NULL;
        }
		if ($this->input->get('saleman')) {
            $saleman = $this->input->get('saleman');
        } else {
            $saleman = NULL;
        }
		if ($this->input->get('product_id')) {
            $product_id = $this->input->get('product_id');
        } else {
            $product_id = NULL;
        }
        if ($this->input->get('biller')) {
            $biller = $this->input->get('biller');
        } else {
            $biller = NULL;
        }
		if ($this->input->get('warehouse')) {
            $warehouse = $this->input->get('warehouse');
        } else {
            $warehouse = NULL;
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
	
        if ((!$this->Owner || !$this->Admin) && !$warehouse_id) {
            $user = $this->site->getUser();
            $warehouse_id = $user->warehouse_id;
        }

        $detail_link = anchor('quotes/modal_view/$1', '<i class="fa fa-file-text-o"></i> ' . lang('quote_details'), 'data-toggle="modal" data-target="#myModal"');
        $email_link = anchor('quotes/email/$1', '<i class="fa fa-envelope"></i> ' . lang('email_quote'), 'data-toggle="modal" data-target="#myModal"');
        $edit_link = anchor('quotes/edit/$1', '<i class="fa fa-edit"></i> ' . lang('edit_quote'));
		$add_sale_order = anchor('sale_order/add_sale_order/$1', '<i class="fa fa-heart"></i> ' . lang('add_sale_order'));
        $add_sale = anchor('sales/add/0/0/$1', '<i class="fa fa-heart"></i> ' . lang('add_sale'));
        //$pc_link = anchor('purchases/add/0/$1', '<i class="fa fa-star"></i> ' . lang('create_purchase'));
        $pdf_link = anchor('quotes/eang_tay_pdf/$1', '<i class="fa fa-file-pdf-o"></i> ' . lang('download_pdf'));
        //$pdf_link = anchor('sales/pdf/$1', '<i class="fa fa-file-pdf-o"></i> ' . lang('download_pdf'));
		$approve = anchor('quotes/getAuthorization/$1', '<i class="fa fa-check"></i> ' . lang('approve'), '');
		//$unapprove = anchor('quotes/getunapprove/$1', '<i class="fa fa-check"></i> ' . lang('unapprove'), '');

        $ordered_link = anchor('quotes/update_quotes/$1', '<i class="fa fa-check"></i> ' . lang('approve'), '');
		$unordered_link = anchor('quotes/Unapproved/$1', '<i class="fa fa-angle-double-left"></i> ' . lang('unapprove'), '');
		
		$rejected = anchor('quotes/getrejected/$1', '<i class="fa fa-times"></i> ' . lang('reject'), '');
		$delete_link = "<a href='#' class='po' title='<b>" . $this->lang->line("delete_quote") . "</b>' data-content=\"<p>"
        . lang('r_u_sure') . "</p><a class='btn btn-danger' href='" . site_url('quotes/delete/$1') . "'>"
        . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i> "
        . lang('delete_quote') . "</a>";
        $action = '<div class="text-center"><div class="btn-group text-left">'
        . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
        . lang('actions') . ' <span class="caret"></span></button>
			<ul class="dropdown-menu pull-right" role="menu">
				<li>' . $detail_link . '</li>'
				
				.(($this->Owner || $this->Admin) ? '<li class="edit">'.$edit_link.'</li>' : ($this->GP['quotes-edit'] ? '<li class="edit">'.$edit_link.'</li>' : '')).
				(($this->Owner || $this->Admin) ? '<li class="approved">'.$ordered_link.'</li>' : ($this->GP['quotes-authorize'] ? '<li class="approved">'.$approve.'</li>' : '')).
				(($this->Owner || $this->Admin) ? '<li class="unapproved">'.$unordered_link.'</li>' : ($this->GP['quotes-authorize'] ? '<li class="unapproved">'.$unordered_link.'</li>' : '')).
				(($this->Owner || $this->Admin) ? '<li class="rejected">'.$rejected.'</li>' : ($this->GP['quotes-authorize'] ? '<li class="rejected">'.$rejected.'</li>' : '')).
				'<li class="add_so">' . $add_sale_order . '</li>
				<li class="add_sale">' . $add_sale . '</li>'

                .(($this->Owner || $this->Admin) ? '<li>'.$pdf_link.'</li>' : ($this->GP['quotes-export'] ? '<li>'.$pdf_link.'</li>' : '')).
                 (($this->Owner || $this->Admin) ? '<li>'.$email_link.'</li>' : ($this->GP['quotes-email'] ? '<li>'.$email_link.'</li>' : '')).
				
			'</ul>
		</div></div>';
        //$action = '<div class="text-center">' . $detail_link . ' ' . $edit_link . ' ' . $email_link . ' ' . $delete_link . '</div>';
		
        $biller_id = $this->session->userdata('biller_id');
        $this->load->library('datatables');
        if ($warehouse_id) {
            $this->datatables
                ->select("quotes.id, quotes.date, quotes.reference_no, quotes.biller, quotes.customer,users.username, quotes.grand_total, quotes.issue_invoice,  quotes.status")
                ->from('quotes')
				->join('payments', 'payments.deposit_quote_id = quotes.id', 'left')
				->join('users','users.id = quotes.saleman','left')
                ->join('users bill', 'quotes.created_by = users.id', 'left')
                ->where_in('quotes.biller_id', JSON_decode($biller_id))
                ->group_by('erp_quotes.id');

                if (count($warehouse_ids) > 1) {
                    $this->datatables->where_in('quotes.warehouse_id', $warehouse_ids);
                } else {
                    $this->datatables->where('quotes.warehouse_id', $warehouse_id);
                }
                
        } else {
            $this->datatables
                ->select("quotes.id, quotes.date, quotes.reference_no, quotes.biller, IF(erp_companies.company = '',erp_quotes.customer, erp_companies.company) AS customer,users.username, quotes.grand_total, quotes.issue_invoice,  quotes.status")
                ->from('quotes')
                ->join('payments', 'payments.deposit_quote_id = quotes.id', 'left')
				->join('companies', 'quotes.customer_id = companies.id', 'left')
				->join('users','users.id = quotes.saleman','left');
        }
		
        if (!$this->Customer && !$this->Supplier && !$this->Owner && !$this->Admin && !$this->session->userdata('view_right')) {
            $this->datatables->where('quotes.created_by', $this->session->userdata('user_id'));
        } elseif ($this->Customer) {
            $this->datatables->where('customer_id', $this->session->userdata('user_id'));
        }
		
		if ($user_query) {
			$this->datatables->where('quotes.created_by', $user_query);
		}
		
		if ($product_id) {
			$this->datatables->join('quote_items', 'quote_items.quote_id = quotes.id', 'left');
			$this->datatables->where('quote_items.product_id', $product_id);
		}
		
		if ($reference_no) {
			$this->datatables->where('quotes.reference_no', $reference_no);
		}
		if ($biller) {
			$this->datatables->where('quotes.biller_id', $biller);
		}
		if ($customer) {
			$this->datatables->where('quotes.customer_id', $customer);
		}
		
		if($saleman){
			$this->datatables->where('quotes.saleman', $saleman);
		}
		
		if ($warehouse) {
			$this->datatables->where('quotes.warehouse_id', $warehouse);
		}

		if ($start_date) {
			$this->datatables->where($this->db->dbprefix('quotes').'.date BETWEEN "' . $start_date . ' 00:00:00" and "' . $end_date . ' 23:59:00"');
		}
		
        $this->datatables->add_column("Actions", $action, "quotes.id");
        echo $this->datatables->generate();
    }

    public function modal_view($quote_id = null)
    {
		
        $this->erp->checkPermissions('index', true);

        if ($this->input->get('id')) {
            $quote_id = $this->input->get('id');
        }
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $inv = $this->quotes_model->getQuoteByID($quote_id);
		/*
        if (!$this->session->userdata('view_right')) {
            $this->erp->view_rights($inv->created_by, true);
        }
		*/
		
        // $this->data['rows'] = $this->quotes_model->getAllQuoteItems($quote_id);		
        $this->data['rows'] = $this->quotes_model->getAllQuoteItems2($quote_id,$inv->warehouse_id);
		//$this->erp->print_arrays($this->quotes_model->getAllQuoteItems2($quote_id));
		
        $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);		
        $this->data['created_by'] = $this->site->getUser($inv->created_by);		
        $this->data['updated_by'] = $inv->updated_by ? $this->site->getUser($inv->updated_by) : null;	
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);				
        $this->data['inv'] = $inv;		
		$this->data['deposit'] = $this->quotes_model->getQuoteDepositByQuoteID($quote_id);		
		// $this->erp->print_arrays($this->data);
        $this->load->view($this->theme . 'quotes/modal_view', $this->data);

    }

	function quote_invoice_ppcp($id=null)
	{
        $inv = $this->quotes_model->getQuotesData($id);
		$this->data['setting'] = $this->site->get_setting();
        $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
        $this->data['user'] = $this->site->getUser($inv->created_by);
        $this->data['saleman'] = $this->site->getUser($inv->created_by);
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
        $this->data['invs'] = $inv;		
        $this->data['rows'] = $this->quotes_model->getQuoteItemsData($id);
        $this->load->view($this->theme .'quotes/quote_ppcp',$this->data);
    }
	function quote_invoice_thai_san($id=null)
	{
        $inv = $this->quotes_model->getQuotesData($id);
		$this->data['setting'] = $this->site->get_setting();
        $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
        $this->data['user'] = $this->site->getUser($inv->created_by);
        $this->data['saleman'] = $this->site->getUser($inv->created_by);
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
        $this->data['invs'] = $inv;		
        $this->data['rows'] = $this->quotes_model->getQuoteItemsData($id);
        $this->load->view($this->theme .'quotes/quote_thai_san',$this->data);
    }
    function invoice_standard($id=null)
    {
        $inv = $this->quotes_model->getQuotesData($id);
        $this->data['setting'] = $this->site->get_setting();
        $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
        $this->data['billers'] = $this->site->getCompanyByID($inv->biller_id);
        $this->data['supplier'] = $this->site->getCompanyByID($inv->biller_id);
        $this->data['user'] = $this->site->getUser($inv->created_by);
        $this->data['saleman'] = $this->site->getUser($inv->created_by);
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
        $this->data['invs'] = $inv;     
        $this->data['rows'] = $this->quotes_model->getQuoteItemsData($id);
        $this->load->view($this->theme .'quotes/invoice_st_a4',$this->data);
    }
    function invoice_iphoto($id=null)
    {
        $inv = $this->quotes_model->getQuotesData($id);
        $this->data['setting'] = $this->site->get_setting();
        $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
        $this->data['billers'] = $this->site->getCompanyByID($inv->biller_id);
        $this->data['supplier'] = $this->site->getCompanyByID($inv->biller_id);
        $this->data['user'] = $this->site->getUser($inv->created_by);
        $this->data['saleman'] = $this->site->getUser($inv->created_by);
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
        $this->data['invs'] = $inv;     
        $this->data['rows'] = $this->quotes_model->getQuoteItemsData($id);
        $this->load->view($this->theme .'quotes/invoice_iphoto',$this->data);
    }
    function invoice_camera_city($id=null)
    {
        $inv = $this->quotes_model->getQuotesData($id);
        $this->data['setting'] = $this->site->get_setting();
        $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
        $this->data['billers'] = $this->site->getCompanyByID($inv->biller_id);
        $this->data['supplier'] = $this->site->getCompanyByID($inv->biller_id);
        $this->data['user'] = $this->site->getUser($inv->created_by);
        $this->data['saleman'] = $this->site->getUser($inv->created_by);
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
        $this->data['invs'] = $inv;     
        $this->data['rows'] = $this->quotes_model->getQuoteItemsData($id);
        $this->load->view($this->theme .'quotes/invoice_camera_city',$this->data);
    }
    function quote_invoice_chim_socheat($id=null)
    {
        $inv = $this->quotes_model->getQuotesData($id);
        $this->data['setting'] = $this->site->get_setting();
        $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
        $this->data['user'] = $this->site->getUser($inv->created_by);
        $this->data['saleman'] = $this->site->getUser($inv->created_by);
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
        $this->data['invs'] = $inv;
        //$this->erp->print_arrays($this->quotes_model->getQuoteItemsData($id));
        $this->data['rows'] = $this->quotes_model->getQuoteItemsData($id);
        $this->load->view($this->theme .'quotes/quot_chim_socheat',$this->data);
    }
	function quote_vat($id=null)
	{
        $inv = $this->quotes_model->getQuotesData($id);
		$this->data['setting'] = $this->site->get_setting();
        $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
        $this->data['user'] = $this->site->getUser($inv->created_by);
        $this->data['saleman'] = $this->site->getUser($inv->created_by);
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
        $this->data['invs'] = $inv;
		//$this->erp->print_arrays($inv);
        $this->data['rows'] = $this->quotes_model->getQuoteItemsData($id);
        $this->load->view($this->theme .'quotes/quote_vat',$this->data);
		
    }
	function quote_without_vat($id=null)
	{
        $inv = $this->quotes_model->getQuotesData($id);
		$this->data['setting'] = $this->site->get_setting();
        $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
        $this->data['user'] = $this->site->getUser($inv->created_by);
        $this->data['saleman'] = $this->site->getUser($inv->created_by);
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
        $this->data['invs'] = $inv;
		//$this->erp->print_arrays($this->quotes_model->getQuoteItemsData($id));
        $this->data['rows'] = $this->quotes_model->getQuoteItemsData($id);
        $this->load->view($this->theme .'quotes/quote_without_vat',$this->data);
	}
	function quote_without_vat_logo($id=null)
	{
        $inv = $this->quotes_model->getQuotesData($id);
		$this->data['setting'] = $this->site->get_setting();
        $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
        $this->data['user'] = $this->site->getUser($inv->created_by);
        $this->data['saleman'] = $this->site->getUser($inv->created_by);
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
        $this->data['invs'] = $inv;
		//$this->erp->print_arrays($this->quotes_model->getQuoteItemsData($id));
        $this->data['rows'] = $this->quotes_model->getQuoteItemsData($id);
        $this->load->view($this->theme .'quotes/quote_without_vat_logo ',$this->data);
	}
    public function view($quote_id = null)
    {
        $this->erp->checkPermissions('index');

        if ($this->input->get('id')) {
            $quote_id = $this->input->get('id');
        }
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $inv = $this->quotes_model->getQuoteByID($quote_id);
        if (!$this->session->userdata('view_right')) {
            $this->erp->view_rights($inv->created_by);
        }
        $this->data['rows'] = $this->quotes_model->getAllQuoteItems($quote_id);
        $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
        $this->data['created_by'] = $this->site->getUser($inv->created_by);
        $this->data['updated_by'] = $inv->updated_by ? $this->site->getUser($inv->updated_by) : null;
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
        $this->data['inv'] = $inv;
		$this->data['deposit'] = $this->quotes_model->getQuoteDepositByQuoteID($quote_id);

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('quotes'), 'page' => lang('quotes')), array('link' => '#', 'page' => lang('view')));
        $meta = array('page_title' => lang('view_quote_details'), 'bc' => $bc);
        $this->page_construct('quotes/view', $meta, $this->data);

    }

    public function pdf($quote_id = null, $view = null, $save_bufffer = null)
    {
        $this->erp->checkPermissions('export', null, 'quotes');

        if ($this->input->get('id')) {
            $quote_id = $this->input->get('id');
        }
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $inv = $this->quotes_model->getQuoteByID($quote_id);
        if (!$this->session->userdata('view_right')) {
            $this->erp->view_rights($inv->created_by);
        }
        $this->data['rows'] = $this->quotes_model->getAllQuoteItems($quote_id);
        $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);       
        $this->data['created_by'] = $this->site->getUser($inv->created_by);     
        $this->data['updated_by'] = $inv->updated_by ? $this->site->getUser($inv->updated_by) : null;   
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);               
        $this->data['inv'] = $inv;
        $name = $this->lang->line("quote") . "_" . str_replace('/', '_', $inv->reference_no) . ".pdf";
        $html = $this->load->view($this->theme . 'quotes/pdf', $this->data, true);
        if ($view) {
            $this->load->view($this->theme . 'quotes/pdf', $this->data);
        } elseif ($save_bufffer) {
            return $this->erp->generate_pdf($html, $name, $save_bufffer);
        } else {
            $this->erp->generate_pdf($html, $name);
        }
    }

    public function combine_pdf($quotes_id)
    {
        // $this->erp->checkPermissions('pdf');

        foreach ($quotes_id as $quote_id) {

            $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
            $inv = $this->quotes_model->getQuoteByID($quote_id);
            if (!$this->session->userdata('view_right')) {
                $this->erp->view_rights($inv->created_by);
            }
            $this->data['rows'] = $this->quotes_model->getAllQuoteItems($quote_id);
            $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
            $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);       
            $this->data['created_by'] = $this->site->getUser($inv->created_by);     
            $this->data['updated_by'] = $inv->updated_by ? $this->site->getUser($inv->updated_by) : null;   
            $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);               
            $this->data['inv'] = $inv;

            $html[] = array(
                'content' => $this->load->view($this->theme . 'quotes/pdf', $this->data, true),
                'footer' => '',
            );
        }

        $name = lang("quotes") . ".pdf";
        $this->erp->generate_pdf($html, $name);

    }

    public function email($quote_id = null)
    {
        $this->erp->checkPermissions(false, true);

        if ($this->input->get('id')) {
            $quote_id = $this->input->get('id');
        }
        $inv = $this->quotes_model->getQuoteByID($quote_id);
        $this->form_validation->set_rules('to', $this->lang->line("to") . " " . $this->lang->line("email"), 'trim|required|valid_email');
        $this->form_validation->set_rules('subject', $this->lang->line("subject"), 'trim|required');
        $this->form_validation->set_rules('cc', $this->lang->line("cc"), 'trim');
        $this->form_validation->set_rules('bcc', $this->lang->line("bcc"), 'trim');
        $this->form_validation->set_rules('note', $this->lang->line("message"), 'trim');

        if ($this->form_validation->run() == true) {
            if (!$this->session->userdata('view_right')) {
                $this->erp->view_rights($inv->created_by);
            }
            $to = $this->input->post('to');
            $subject = $this->input->post('subject');
            if ($this->input->post('cc')) {
                $cc = $this->input->post('cc');
            } else {
                $cc = null;
            }
            if ($this->input->post('bcc')) {
                $bcc = $this->input->post('bcc');
            } else {
                $bcc = null;
            }
            $customer = $this->site->getCompanyByID($inv->customer_id);
            $this->load->library('parser');
            $parse_data = array(
                'reference_number' => $inv->reference_no,
                'contact_person' => $customer->name,
                'company' => $customer->company,
                'site_link' => base_url(),
                'site_name' => $this->Settings->site_name,
                'logo' => '<img src="' . base_url() . 'assets/uploads/logos/' . $this->Settings->logo . '" alt="' . $this->Settings->site_name . '"/>',
            );
            $msg = $this->input->post('note');
            $message = $this->parser->parse_string($msg, $parse_data);
            $attachment = $this->pdf($quote_id, null, 'S'); //delete_files($attachment);
        } elseif ($this->input->post('send_email')) {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->session->set_flashdata('error', $this->data['error']);
            redirect($_SERVER["HTTP_REFERER"]);
        }

        if ($this->form_validation->run() == true && $this->erp->send_email($to, $subject, $message, null, null, $attachment, $cc, $bcc)) {
            delete_files($attachment);
            $this->db->update('quotes', array('status' => 'sent'), array('id' => $quote_id));
            $this->session->set_flashdata('message', $this->lang->line("email_sent"));
            redirect("quotes");
        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

            if (file_exists('./themes/' . $this->theme . '/views/email_templates/quote.html')) {
                $quote_temp = file_get_contents('themes/' . $this->theme . '/views/email_templates/quote.html');
            } else {
                $quote_temp = file_get_contents('./themes/default/views/email_templates/quote.html');
            }

            $this->data['subject'] = array('name' => 'subject',
                'id' => 'subject',
                'type' => 'text',
                'value' => $this->form_validation->set_value('subject', lang('quote').' (' . $inv->reference_no . ') '.lang('from').' '.$this->Settings->site_name),
            );
            $this->data['note'] = array('name' => 'note',
                'id' => 'note',
                'type' => 'text',
                'value' => $this->form_validation->set_value('note', $quote_temp),
            );
            $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);

            $this->data['id'] = $quote_id;
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'quotes/email', $this->data);

        }
    }

    public function add()
    {
        $this->erp->checkPermissions();
        $this->form_validation->set_message('is_natural_no_zero', $this->lang->line("no_zero_required"));
        $this->form_validation->set_rules('customer_1', $this->lang->line("customer"), 'required');
        $this->form_validation->set_rules('reference_no', $this->lang->line("reference_no"), 'required|is_unique[quotes.reference_no]');
		
        if ($this->form_validation->run() == true) {
            $quantity = "quantity";
            $product = "product";
            $unit_cost = "unit_cost";
            $tax_rate = "tax_rate";
			$biller_id = $this->input->post('biller');
            $reference = $this->input->post('reference_no') ? $this->input->post('reference_no') : $this->site->getReference('qu',$biller_id);
			
            if ($this->Owner || $this->Admin || $this->Settings->allow_change_date == 1) {
                $date = $this->erp->fld(trim($this->input->post('date')));
            } else {
                $date = date('Y-m-d H:i:s');
            }
            $warehouse_id = $this->input->post('warehouse');
            $customer_id = $this->input->post('customer_1');
            $status = $this->input->post('status');
            $shipping = $this->input->post('shipping') ? $this->input->post('shipping') : 0;
            $customer_details = $this->site->getCompanyByID($customer_id);
            $customer = $customer_details->name ? $customer_details->name : $customer_details->company;
			$saleman = $this->input->post('saleman');
			
            $biller_details = $this->site->getCompanyByID($biller_id);
            $biller = $biller_details->company != '-' ? $biller_details->company : $biller_details->name;
            $note = $this->input->post('note');
			
            $total = 0;
            $product_tax = 0;
            $order_tax = 0;
            $product_discount = 0;
            $order_discount = 0;
            $percentage = '%';
            $i = isset($_POST['product_code']) ? sizeof($_POST['product_code']) : 0;
            for ($r = 0; $r < $i; $r++) {
                $item_id		  = $_POST['product_id'][$r];
				$digital_id 	  = $_POST['digital_id'][$r];
                $item_type 		  = $_POST['product_type'][$r];
                $item_code        = $_POST['product_code'][$r];
                $item_name        = $_POST['product_name'][$r];
				$item_peice       = $_POST['piece'][$r];
				$item_note 		  = $_POST['product_note'][$r];
				$item_wpeice	  = $_POST['wpiece'][$r];
				$product_group_id = $_POST['product_group_id'][$r];
                $item_option      = isset($_POST['product_option'][$r]) && $_POST['product_option'][$r] != 'false' ? $_POST['product_option'][$r] : null;
                
                $real_unit_price = $this->erp->formatDecimal($_POST['real_unit_price'][$r]);
                $unit_price = $this->erp->formatDecimal($_POST['unit_price'][$r]);
                $item_price_id = $_POST['price_id'][$r];

                $item_quantity = $_POST['quantity'][$r];
                $item_tax_rate = isset($_POST['product_tax'][$r]) ? $_POST['product_tax'][$r] : null;
                $item_discount = isset($_POST['product_discount'][$r]) ? $_POST['product_discount'][$r] : null;
                if (isset($item_code) && isset($real_unit_price) && isset($unit_price) && isset($item_quantity)) {
                    $product_details = $item_type != 'manual' ? $this->quotes_model->getProductByCode($item_code) : null;
                    //$unit_price = $real_unit_price;
                    $pr_discount = 0;

                    if (isset($item_discount)) {
                        $discount = $item_discount;
                        $dpos = strpos($discount, $percentage);
                        if ($dpos !== false) {
                            $pds = explode("%", $discount);
                            $pr_discount = (($unit_price * $item_quantity) * ((Float) ($pds[0]) / 100))/$item_quantity;
							
						} else {
                            $pr_discount = $discount/$item_quantity;
                        }
                    }
					
					
                    //$unit_price = $this->erp->formatDecimal($unit_price - $pr_discount);
                    $item_net_price = $unit_price - $pr_discount;
                    $pr_item_discount = $this->erp->formatDecimal($pr_discount * $item_quantity);
                    $product_discount += $pr_discount * $item_quantity;
					
                    $pr_tax = 0;
                    $pr_item_tax = 0;
                    $item_tax = 0;
                    $tax = "";
					
                    if (isset($item_tax_rate) && $item_tax_rate != 0) {
                        $pr_tax = $item_tax_rate;
                        $tax_details = $this->site->getTaxRateByID($pr_tax);
						
                        if ($tax_details->type == 1 && $tax_details->rate != 0) {
                            if ($product_details && $product_details->tax_method == 0) {
                                $item_tax = ($this->erp->formatDecimal($item_net_price*$tax_details->rate)) / (100 + $tax_details->rate);
                                $tax = $tax_details->rate . "%";
								$item_net_price = $item_net_price - $item_tax;
                            } else {
                                $item_tax = $this->erp->formatDecimal($item_net_price) * ($tax_details->rate / 100);
                                $tax = $tax_details->rate . "%";
                                
                            }

                        } elseif ($tax_details->type == 2) {
                            if ($product_details && $product_details->tax_method == 0) {
                                $item_tax = ($this->erp->formatDecimal($item_net_price*$tax_details->rate)) / (100 + $tax_details->rate);
                                $tax = $tax_details->rate . "%";
								$item_net_price = $item_net_price - $item_tax;
                            } else {
                                $item_tax = $this->erp->formatDecimal($item_net_price) * ($tax_details->rate / 100);
                                $tax = $tax_details->rate . "%";
                                
                            }
                        }
						
						//$unit_price = $this->erp->formatDecimal($unit_price + $pr_discount);
                        $pr_item_tax = $this->erp->formatDecimal($item_tax * $item_quantity);
                    }
					
                    $product_tax += $pr_item_tax;
					
                    $subtotal = (($item_net_price * $item_quantity) + $item_tax * $item_quantity);
					
                    $products[] = array(
                        'product_id' => $item_id,
						'digital_id' 		=> $digital_id,
                        'product_code' => $item_code,
                        'product_name' => $item_name,
                        'product_type' => $item_type,
                        'option_id' => $item_option,
                        'net_unit_price' => $item_net_price,
                        'unit_price' => $unit_price,
                        'quantity' => $item_quantity,
                        'warehouse_id' => $warehouse_id,
                        'item_tax' => $pr_item_tax,
                        'tax_rate_id' => $pr_tax,
						'piece'	=> $item_peice,
						'wpiece' => $item_wpeice,
                        'tax' => $tax,
                        'discount' => $item_discount,
						'group_price_id' =>$product_group_id,
                        'item_discount' => $pr_item_discount,
                        'subtotal' => $subtotal,
                        'real_unit_price' => $real_unit_price,
                        'price_id' => $item_price_id,
						'product_noted' 	=> $item_note
                    );
                    $total += $subtotal;
                }
            }		
			//$this->erp->print_arrays($products);
            if (empty($products)) {
                $this->form_validation->set_rules('product', lang("order_items"), 'required');
            } else {
                krsort($products);
            }
            $total = $this->erp->formatDecimal($total);
            if ($this->input->post('discount')) {
                $order_discount_id = $this->input->post('discount');
                $opos = strpos($order_discount_id, $percentage);
                if ($opos !== false) {
                    $ods = explode("%", $order_discount_id);
                    $order_discount = ((($total) * (Float) ($ods[0])) / 100);

                } else {
                    $order_discount = (($total * $order_discount_id) / 100);
                }
            } else {
                $order_discount_id = null;
            }
			
            $total_discount = $order_discount + $product_discount;

            if ($this->Settings->tax2 != 0) {
                $order_tax_id = $this->input->post('order_tax');
                if ($order_tax_details = $this->site->getTaxRateByID($order_tax_id)) {
                    if ($order_tax_details->type == 2) {
                        $order_tax = $order_tax_details->rate;
                    }
                    if ($order_tax_details->type == 1) {
						$order_tax = $this->erp->formatDecimal(((($total + $shipping - $order_discount) * $order_tax_details->rate) / 100), 4);
                    }
                }
            } else {
                $order_tax_id = null;
            }
			
			
			
            $total_tax = $product_tax + $order_tax;
            $grand_total = ($total + $order_tax + $shipping - $order_discount);
            $data = array('date' => $date,
                'reference_no' => $reference,
                'customer_id' => $customer_id,
                'customer' => $customer,
                'biller_id' => $biller_id,
                'biller' => $biller,
                'warehouse_id' => $warehouse_id,
                'note' => htmlspecialchars($note,ENT_QUOTES),
                'total' => $total,
                'product_discount' => $product_discount,
                'order_discount_id' => $order_discount_id,
                'order_discount' => $order_discount,
                'total_discount' => $total_discount,
                'product_tax' => $product_tax,
                'order_tax_id' => $order_tax_id,
                'order_tax' => $order_tax,
                'total_tax' => $total_tax,
                'shipping' => $shipping,
                'grand_total' => $grand_total,
                'status' => $this->Settings->authorization=='auto'?'approved':'pending',
                'created_by' => $this->session->userdata('user_id'),
				'saleman' => $saleman,
                'issue_invoice' => 'pending'
            );
			
            if ($_FILES['document']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->digital_upload_path;
                $config['allowed_types'] = $this->digital_file_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = false;
                $config['encrypt_name'] = true;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('document')) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                $data['attachment'] = $photo;
            }
			$payment = array();

			if($this->input->post('paid_by') == 'deposit' && $customer_details->deposit_amount){
				$payment = array(
					'date' => $date,
					'reference_no' => $this->site->getReference('sp'),
					'amount' => $this->input->post('amount'),
					'paid_by' => $this->input->post('paid_by'),
					'note' => $this->input->post('note') ? $this->input->post('note') : $customer_details->name,
					'created_by' => $this->session->userdata('user_id'),
					'type' => 'received',
					'biller_id'	=> $this->input->post('biller')
				);
			}
           
        }

        if ($this->form_validation->run() == true && ($quote_id = $this->quotes_model->addQuote($data, $products, $payment))) {
            $this->session->set_userdata('remove_q2', '1');
			$this->session->set_userdata('remove_quls', 1);
            $this->session->set_flashdata('message', $this->lang->line("quote_added"));
			
			redirect("quotes");
        } else {
			
			$this->load->model('purchases_model');
			$this->data['exchange_rate'] = $this->site->getCurrencyByCode('KHM');
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            
			$this->data['billers'] = $this->site->getAllCompanies('biller');
			if ($this->Owner || $this->Admin) {
                $date = $this->erp->fld(trim($this->input->post('date')));
            } else {
                $date = date('Y-m-d H:i:s');
            }
			
            $this->data['warehouses'] = $this->site->getAllWarehouses();
            $this->data['tax_rates'] = $this->site->getAllTaxRates();
			$this->data['drivers'] = $this->site->getAllCompanies('driver');
			$this->data['agencies'] = $this->site->getAllUsers();
			$this->data['customers'] = $this->site->getCustomers();
			$this->data['currency'] = $this->site->getCurrency();
			$this->data['categories'] = $this->site->getAllCategories();
			$this->data['areas'] = $this->site->getArea();
			$this->data['unit'] = $this->purchases_model->getUnits();
			$this->data['payment_term'] = $this->site->getAllPaymentTerm();
			$this->session->set_userdata('remove_q', 0);
			$this->data['setting'] = $this->site->get_setting();
			
			if ($this->Owner || $this->Admin || !$this->session->userdata('biller_id')){
				$biller_id = $this->site->get_setting()->default_biller;
				$this->data['reference'] = $this->site->getReference('qu',$biller_id);
			}else{
				$biller_id = $this->session->userdata('biller_id');
				$this->data['reference'] = $this->site->getReference('qu',$biller_id);
			}
			
            if ($this->session->userdata('biller_id')) {
                $biller_id = $this->session->userdata('biller_id');
            } else {
                $biller_id = $this->Settings->default_biller;
            }
            $this->data['payment_ref'] = $this->site->getReference('sp', $biller_id);
			
            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('quotes'), 'page' => lang('quotes')), array('link' => '#', 'page' => lang('add_quote')));
            $meta = array('page_title' => lang('add_quote'), 'bc' => $bc);
            $this->page_construct('quotes/add', $meta, $this->data);
        }
    }

    public function edit_old($id = null)
    {
        $this->erp->checkPermissions();

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $inv = $this->quotes_model->getQuoteByID($id);
        if (!$this->session->userdata('edit_right')) {
            $this->erp->view_rights($inv->created_by);
        }
        $this->form_validation->set_message('is_natural_no_zero', $this->lang->line("no_zero_required"));
        $this->form_validation->set_rules('reference_no', $this->lang->line("reference_no"), 'required');
        $this->form_validation->set_rules('customer', $this->lang->line("customer"), 'required');
        //$this->form_validation->set_rules('note', $this->lang->line("note"), 'xss_clean');

        if ($this->form_validation->run() == true) {
            $quantity = "quantity";
            $product = "product";
            $unit_cost = "unit_cost";
            $tax_rate = "tax_rate";
            $reference = $this->input->post('reference_no');
            if ($this->Owner || $this->Admin) {
                $date = $this->erp->fld(trim($this->input->post('date')));
            } else {
                $date = date('Y-m-d H:i:s');
            }
            $warehouse_id = $this->input->post('warehouse');
            $customer_id = $this->input->post('customer');
            $biller_id = $this->input->post('biller');
            $status = $this->input->post('status');
            $shipping = $this->input->post('shipping') ? $this->input->post('shipping') : 0;
            $customer_details = $this->site->getCompanyByID($customer_id);
            $customer = $customer_details->name ? $customer_details->name : $customer_details->company;
            $biller_details = $this->site->getCompanyByID($biller_id);
            $biller = $biller_details->company != '-' ? $biller_details->company : $biller_details->name;
            $note = $this->erp->clear_tags($this->input->post('note'));

            $total = 0;
            $product_tax = 0;
            $order_tax = 0;
            $product_discount = 0;
            $order_discount = 0;
            $percentage = '%';
            $i = isset($_POST['product_code']) ? sizeof($_POST['product_code']) : 0;
            for ($r = 0; $r < $i; $r++) {
                $item_id = $_POST['product_id'][$r];
                $item_type = $_POST['product_type'][$r];
                $item_code = $_POST['product_code'][$r];
                $item_name = $_POST['product_name'][$r];
                $item_option = isset($_POST['product_option'][$r]) && $_POST['product_option'][$r] != 'false' ? $_POST['product_option'][$r] : null;
                //$option_details = $this->quotes_model->getProductOptionByID($item_option);
                $real_unit_price = $this->erp->formatDecimal($_POST['real_unit_price'][$r]);
                $unit_price = $this->erp->formatDecimal($_POST['unit_price'][$r]);
                $item_quantity = $_POST['quantity'][$r];
                $item_tax_rate = isset($_POST['product_tax'][$r]) ? $_POST['product_tax'][$r] : null;
                $item_discount = isset($_POST['product_discount'][$r]) ? $_POST['product_discount'][$r] : null;

                if (isset($item_code) && isset($real_unit_price) && isset($unit_price) && isset($item_quantity)) {
                    $product_details = $item_type != 'manual' ? $this->quotes_model->getProductByCode($item_code) : null;
                    $unit_price = $real_unit_price;
                    $pr_discount = 0;

                    if (isset($item_discount)) {
                        $discount = $item_discount;
                        $dpos = strpos($discount, $percentage);
                        if ($dpos !== false) {
                            $pds = explode("%", $discount);
                            $pr_discount = (($this->erp->formatDecimal($unit_price)) * (Float) ($pds[0])) / 100;
                        } else {
                            $pr_discount = $this->erp->formatDecimal($discount);
                        }
                    }

                    $unit_price = $this->erp->formatDecimal($unit_price - $pr_discount);
                    $item_net_price = $unit_price;
                    $pr_item_discount = $this->erp->formatDecimal($pr_discount * $item_quantity);
                    $product_discount += $pr_item_discount;
                    $pr_tax = 0;
                    $pr_item_tax = 0;
                    $item_tax = 0;
                    $tax = "";

                    if (isset($item_tax_rate) && $item_tax_rate != 0) {
                        $pr_tax = $item_tax_rate;
                        $tax_details = $this->site->getTaxRateByID($pr_tax);
                        if ($tax_details->type == 1 && $tax_details->rate != 0) {

                            if ($product_details && $product_details->tax_method == 1) {
                                $item_tax = $this->erp->formatDecimal((($unit_price) * $tax_details->rate) / 100);
                                $tax = $tax_details->rate . "%";
                            } else {
                                $item_tax = $this->erp->formatDecimal((($unit_price) * $tax_details->rate) / (100 + $tax_details->rate));
                                $tax = $tax_details->rate . "%";
                                $item_net_price = $unit_price - $item_tax;
                            }

                        } elseif ($tax_details->type == 2) {

                            if ($product_details && $product_details->tax_method == 1) {
                                $item_tax = $this->erp->formatDecimal((($unit_price) * $tax_details->rate) / 100);
                                $tax = $tax_details->rate . "%";
                            } else {
                                $item_tax = $this->erp->formatDecimal((($unit_price) * $tax_details->rate) / (100 + $tax_details->rate));
                                $tax = $tax_details->rate . "%";
                                $item_net_price = $unit_price - $item_tax;
                            }

                            $item_tax = $this->erp->formatDecimal($tax_details->rate);
                            $tax = $tax_details->rate;

                        }
                        $pr_item_tax = $this->erp->formatDecimal($item_tax * $item_quantity);

                    }

                    $product_tax += $pr_item_tax;
                    $subtotal = (($item_net_price * $item_quantity) + $pr_item_tax);

                    $products[] = array(
                        'product_id' => $item_id,
                        'product_code' => $item_code,
                        'product_name' => $item_name,
                        'product_type' => $item_type,
                        'option_id' => $item_option,
                        'net_unit_price' => $item_net_price,
                        'unit_price' => $this->erp->formatDecimal($item_net_price + $item_tax),
                        'quantity' => $item_quantity,
                        'warehouse_id' => $warehouse_id,
                        'item_tax' => $pr_item_tax,
                        'tax_rate_id' => $pr_tax,
                        'tax' => $tax,
                        'discount' => $item_discount,
                        'item_discount' => $pr_item_discount,
                        'subtotal' => $this->erp->formatDecimal($subtotal),
                        'real_unit_price' => $real_unit_price,
                    );

                    $total += $item_net_price * $item_quantity;
                }
            }
            if (empty($products)) {
                $this->form_validation->set_rules('product', lang("order_items"), 'required');
            } else {
                krsort($products);
            }

            if ($this->input->post('discount')) {
                $order_discount_id = $this->input->post('discount');
                $opos = strpos($order_discount_id, $percentage);
                if ($opos !== false) {
                    $ods = explode("%", $order_discount_id);
                    $order_discount = (($total + $product_tax) * (Float) ($ods[0])) / 100;

                } else {
                    $order_discount = $order_discount_id;
                }
            } else {
                $order_discount_id = null;
            }
            $total_discount = $order_discount + $product_discount;

            if ($this->Settings->tax2 != 0) {
                $order_tax_id = $this->input->post('order_tax');
                if ($order_tax_details = $this->site->getTaxRateByID($order_tax_id)) {
                    if ($order_tax_details->type == 2) {
                        $order_tax = $order_tax_details->rate;
                    }
                    if ($order_tax_details->type == 1) {
                        $order_tax = (($total + $product_tax - $order_discount) * $order_tax_details->rate) / 100;
                    }
                }
            } else {
                $order_tax_id = null;
            }

            $total_tax = $product_tax + $order_tax;
            $grand_total = $item_net_price = $this->erp->formatDecimal($total + $total_tax + $shipping - $order_discount);
            $data = array('date' => $date,
                'reference_no' => $reference,
                'customer_id' => $customer_id,
                'customer' => $customer,
                'biller_id' => $biller_id,
                'biller' => $biller,
                'warehouse_id' => $warehouse_id,
                'note' => $note,
                'total' => $total,
                'product_discount' => $product_discount,
                'order_discount_id' => $order_discount_id,
                'order_discount' => $order_discount,
                'total_discount' => $total_discount,
                'product_tax' => $product_tax,
                'order_tax_id' => $order_tax_id,
                'order_tax' => $order_tax,
                'total_tax' => $total_tax,
                'shipping' => $shipping,
                'grand_total' => $grand_total,
                'status' => $status,
                'updated_by' => $this->session->userdata('user_id'),
                'updated_at' => date('Y-m-d H:i:s'),
            );

            if ($_FILES['document']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->digital_upload_path;
                $config['allowed_types'] = $this->digital_file_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = false;
                $config['encrypt_name'] = true;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('document')) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                $data['attachment'] = $photo;
            }
			
			$payment = array();

			if($this->input->post('paid_by') == 'deposit' && $customer_details->deposit_amount){
				$payment = array(
					'date' => $date,
					'reference_no' => $this->site->getReference('sp'),
					'amount' => $this->input->post('amount'),
					'paid_by' => $this->input->post('paid_by'),
					'note' => $this->input->post('note') ? $this->input->post('note') : $customer_details->name,
					'created_by' => $this->session->userdata('user_id'),
					'type' => 'received',
					'biller_id'	=> $this->input->post('biller')
				);
			}
            //$this->erp->print_arrays($data, $products, $payment);
        }

        if ($this->form_validation->run() == true && $this->quotes_model->updateQuote($id, $data, $products, $payment)) {
            $this->session->set_userdata('remove_quls', 1);
            $this->session->set_flashdata('message', $this->lang->line("quote_added"));
            redirect('quotes');
        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

            $this->data['inv'] = $this->quotes_model->getQuoteByID($id);
            $inv_items = $this->quotes_model->getAllQuoteItems($id);
			$this->data['payment'] = $this->quotes_model->getPaymentByQuoteID($id);
            $c = rand(100000, 9999999);
            foreach ($inv_items as $item) {
                $row = $this->site->getProductByID($item->product_id);
                if (!$row) {
                    $row = json_decode('{}');
                    $row->tax_method = 0;
                } else {
                    unset($row->details, $row->product_details, $row->cost, $row->supplier1price, $row->supplier2price, $row->supplier3price, $row->supplier4price, $row->supplier5price);
                }
                $row->quantity = 0;
                $pis = $this->quotes_model->getPurchasedItems($item->product_id, $item->warehouse_id, $item->option_id);
                if ($pis) {
                    foreach ($pis as $pi) {
                        $row->quantity += $pi->quantity_balance;
                    }
                }
                $row->id = $item->product_id;
                $row->code = $item->product_code;
                $row->name = $item->product_name;
                $row->type = $item->product_type;
                $row->qty = $item->quantity;
                $row->discount = $item->discount ? $item->discount : '0';
                $row->price = $this->erp->formatDecimal($item->net_unit_price + $this->erp->formatDecimal($item->item_discount / $item->quantity));
                $row->unit_price = $row->tax_method ? $item->unit_price + $this->erp->formatDecimal($item->item_discount / $item->quantity) + $this->erp->formatDecimal($item->item_tax / $item->quantity) : $item->unit_price + ($item->item_discount / $item->quantity);
                $row->real_unit_price = $item->real_unit_price;
                $row->tax_rate = $item->tax_rate_id;
                $row->option = $item->option_id;
                $options = $this->quotes_model->getProductOptions($row->id, $item->warehouse_id);

                if ($options) {
                    $option_quantity = 0;
                    foreach ($options as $option) {
                        $pis = $this->quotes_model->getPurchasedItems($row->id, $item->warehouse_id, $item->option_id);
                        if ($pis) {
                            foreach ($pis as $pi) {
                                $option_quantity += $pi->quantity_balance;
                            }
                        }
                        if ($option->quantity > $option_quantity) {
                            $option->quantity = $option_quantity;
                        }
                    }
                }

                $combo_items = false;
                if ($row->type == 'combo') {
                    $combo_items = $this->quotes_model->getProductComboItems($row->id, $item->warehouse_id);
                    $te = $combo_items;
                    foreach ($combo_items as $combo_item) {
                        $combo_item->quantity = $combo_item->qty * $item->quantity;
                    }
                }
                $ri = $this->Settings->item_addition ? $row->id : $c;
                if ($row->tax_rate) {
                    $tax_rate = $this->site->getTaxRateByID($row->tax_rate);
                    $pr[$ri] = array('id' => $c, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'combo_items' => $combo_items, 'tax_rate' => $tax_rate, 'options' => $options);
                } else {
                    $pr[$ri] = array('id' => $c, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'combo_items' => $combo_items, 'tax_rate' => false, 'options' => $options);
                }
                $c++;
            }
            $this->data['inv_items'] = json_encode($pr);
            $this->data['id'] = $id;
			$this->data['payment_deposit'] = $this->quotes_model->getPaymentByQuoteID($id);
            //$this->data['currencies'] = $this->site->getAllCurrencies();
            $this->data['billers'] = ($this->Owner || $this->Admin || !$this->session->userdata('biller_id')) ? $this->site->getAllCompanies('biller') : null;
            $this->data['tax_rates'] = $this->site->getAllTaxRates();
            $this->data['warehouses'] = ($this->Owner || $this->Admin || !$this->session->userdata('biller_id')) ? $this->site->getAllWarehouses() : null;

            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('quotes'), 'page' => lang('quotes')), array('link' => '#', 'page' => lang('edit_quote')));
            $meta = array('page_title' => lang('edit_quote'), 'bc' => $bc);
            $this->page_construct('quotes/edit', $meta, $this->data);
        }
    }
	
	public function edit($id = null)
    {
        $this->erp->checkPermissions('edit',null,'quotes');
		
		if (($this->quotes_model->getQuotesData($id)->status) == 'completed' ) {
			$this->session->set_flashdata('error', lang('quote_has_been_approved'));
			redirect($_SERVER['HTTP_REFERER']);
		}
		if ( ($this->quotes_model->getQuotesData($id)->status) == 'rejected') {
				$this->session->set_flashdata('error', lang('quote_has_been_rejected'));
				redirect($_SERVER['HTTP_REFERER']);
			} 
		
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        /*$inv = $this->quotes_model->getQuoteByID($id);
        if (!$this->session->userdata('edit_right')) {
            $this->erp->view_rights($inv->created_by);
        }*/
        $this->form_validation->set_message('is_natural_no_zero', $this->lang->line("no_zero_required"));
        $this->form_validation->set_rules('reference_no', $this->lang->line("reference_no"), 'required');
        $this->form_validation->set_rules('customer', $this->lang->line("customer"), 'required');
        //$this->form_validation->set_rules('note', $this->lang->line("note"), 'xss_clean');

        if ($this->form_validation->run() == true) {
            $quantity = "quantity";
            $product = "product";
            $unit_cost = "unit_cost";
            $tax_rate = "tax_rate";
            $reference = $this->input->post('reference_no');
			
            if ($this->Owner || $this->Admin || $this->Settings->allow_change_date == 1) {
                $date = $this->erp->fld(trim($this->input->post('date')));
            } else {
                $date = date('Y-m-d H:i:s');
            }
            $warehouse_id = $this->input->post('warehouse');
            $customer_id = $this->input->post('customer');
            $biller_id = $this->input->post('biller');
            $status = $this->input->post('status');
            $shipping = $this->input->post('shipping') ? $this->input->post('shipping') : 0;
            $customer_details = $this->site->getCompanyByID($customer_id);
            $customer = $customer_details->name ? $customer_details->name : $customer_details->company;
            $biller_details = $this->site->getCompanyByID($biller_id);
            $biller = $biller_details->company != '-' ? $biller_details->company : $biller_details->name;
            $note = $this->input->post('note');

            $total = 0;
            $product_tax = 0;
            $order_tax = 0;
            $product_discount = 0;
            $order_discount = 0;
            $percentage = '%';
            $i = isset($_POST['product_code']) ? sizeof($_POST['product_code']) : 0;
            for ($r = 0; $r < $i; $r++) {
                $item_id 			= $_POST['product_id'][$r];
				$digital_id 		= $_POST['digital_id'][$r];
                $item_type 			= $_POST['product_type'][$r];
                $item_code 			= $_POST['product_code'][$r];
                $item_name 			= $_POST['product_name'][$r];
				$item_note 		  	= $_POST['product_note'][$r];
				$item_peice  		= $_POST['piece'][$r];
				$item_wpeice  		= $_POST['wpiece'][$r];
				$product_group_id 	= $_POST['product_group_id'][$r];
                $item_option 		= isset($_POST['product_option'][$r]) && $_POST['product_option'][$r] != 'false' ? $_POST['product_option'][$r] : null;
                $real_unit_price = $this->erp->formatDecimal($_POST['real_unit_price'][$r]);
                $unit_price = $this->erp->formatDecimal($_POST['unit_price'][$r]);
                $item_quantity = $_POST['quantity'][$r];
                $item_tax_rate = isset($_POST['product_tax'][$r]) ? $_POST['product_tax'][$r] : null;
                $item_discount = isset($_POST['product_discount'][$r]) ? $_POST['product_discount'][$r] : null;
                $item_price_id = $_POST['price_id'][$r];
				
                if (isset($item_code) && isset($real_unit_price) && isset($unit_price) && isset($item_quantity)) {
                    $product_details = $item_type != 'manual' ? $this->quotes_model->getProductByCode($item_code) : null;
                    $unit_price = $real_unit_price;
                    $pr_discount = 0;

                    if (isset($item_discount)) {
                        $discount = $item_discount;
                        $dpos = strpos($discount, $percentage);
                        if ($dpos !== false) {
                            $pds = explode("%", $discount);
                            $pr_discount = (($unit_price * $item_quantity) * ((Float) ($pds[0]) / 100))/$item_quantity;
							
						} else {
                            $pr_discount = $discount/$item_quantity;
                        }
                    }
					
					$unit_price = $unit_price - $pr_discount;
                    $item_net_price = $unit_price;
                    $pr_item_discount = $this->erp->formatDecimal($pr_discount * $item_quantity);
                    $product_discount += $pr_discount * $item_quantity;
					
                    $pr_tax = 0;
                    $pr_item_tax = 0;
                    $item_tax = 0;
                    $tax = "";


                    if (isset($item_tax_rate) && $item_tax_rate != 0) {
                        $pr_tax = $item_tax_rate;
                        $tax_details = $this->site->getTaxRateByID($pr_tax);
                        if ($tax_details->type == 1 && $tax_details->rate != 0) {

                            if ($product_details && $product_details->tax_method == 1) {
                                $item_tax = (($unit_price * $tax_details->rate) / 100);
                                $tax = $tax_details->rate . "%";
								echo $pr_discount.'<br/>';
                            } else {
                                $item_tax = ((($unit_price) * $tax_details->rate) / (100 + $tax_details->rate));
                                $tax = $tax_details->rate . "%";
                                $item_net_price = $unit_price - $item_tax;
                            }

                        } elseif ($tax_details->type == 2) {

                            if ($product_details && $product_details->tax_method == 1) {
                                $item_tax = ((($unit_price) * $tax_details->rate) / 100);
                                $tax = $tax_details->rate . "%";
                            } else {
                                $item_tax = ((($unit_price) * $tax_details->rate) / (100 + $tax_details->rate));
                                $tax = $tax_details->rate . "%";
                                $item_net_price = $unit_price - $item_tax;
                            }

                            $item_tax = $this->erp->formatDecimal($tax_details->rate);
                            $tax = $tax_details->rate;

                        }
                        $unit_price = $this->erp->formatDecimal($unit_price + $pr_discount);
						$pr_item_tax = $this->erp->formatDecimal($item_tax * $item_quantity);
						
                    }		
					
					$product_tax += $pr_item_tax;
                    $subtotal = (($item_net_price * $item_quantity) + $item_tax * $item_quantity);

                    $products[] = array(
                        'product_id' 		=> $item_id,
						'digital_id' 		=> $digital_id,
                        'product_code' 		=> $item_code,
                        'product_name' 		=> $item_name,
                        'product_type' 		=> $item_type,
                        'option_id' 		=> $item_option,
                        'net_unit_price' 	=> $item_net_price,
                        'unit_price' 		=> $unit_price,
                        'quantity' 			=> $item_quantity,
                        'warehouse_id' 		=> $warehouse_id,
                        'item_tax' 			=> $pr_item_tax,
                        'tax_rate_id' 		=> $pr_tax,
						'piece'	            => $item_peice,
						'wpiece'            => $item_wpeice,
                        'tax' 				=> $tax,
						'group_price_id'    => $product_group_id,
                        'discount' 			=> $item_discount,
                        'item_discount' 	=> $pr_item_discount,
                        'subtotal' 			=> $subtotal,
                        'real_unit_price' 	=> $real_unit_price,
                        'price_id'          => $item_price_id,
                        'product_noted'     => $item_note
                    );

                    $total += $subtotal;
                }
            }
            if (empty($products)) {
                $this->form_validation->set_rules('product', lang("order_items"), 'required');
            } else {
                krsort($products);
            }
            $total = $this->erp->formatDecimal($total);
            if ($this->input->post('discount')) {
                $order_discount_id = $this->input->post('discount');
                $opos = strpos($order_discount_id, $percentage);
                if ($opos !== false) {
                    $ods = explode("%", $order_discount_id);
                    $order_discount = (($total) * (Float) ($ods[0])) / 100;

                } else {
                    $order_discount = (($order_discount_id * $total) / 100);
                }
            } else {
                $order_discount_id = null;
            }
			
            $total_discount = $order_discount + $product_discount;

            if ($this->Settings->tax2 != 0) {
                $order_tax_id = $this->input->post('order_tax');
                if ($order_tax_details = $this->site->getTaxRateByID($order_tax_id)) {
                    if ($order_tax_details->type == 2) {
                        $order_tax = $order_tax_details->rate;
                    }
                    if ($order_tax_details->type == 1) {
						$order_tax = $this->erp->formatDecimal(((($total + $shipping - $order_discount) * $order_tax_details->rate) / 100), 4);
                    }
                }
            } else {
                $order_tax_id = null;
            }
			
            $total_tax = $product_tax + $order_tax;
			$grand_total = ($total + $order_tax + $shipping - $order_discount);
			
            $data = array('date' => $date,
                'reference_no' => $reference,
                'customer_id' => $customer_id,
                'customer' => $customer,
                'biller_id' => $biller_id,
                'biller' => $biller,
                'warehouse_id' => $warehouse_id,
                'note' => htmlspecialchars($note,ENT_QUOTES),
                'total' => $total,
                'product_discount' => $product_discount,
                'order_discount_id' => $order_discount_id,
                'order_discount' => $order_discount,
                'total_discount' => $total_discount,
                'product_tax' => $product_tax,
                'order_tax_id' => $order_tax_id,
                'order_tax' => $order_tax,
                'total_tax' => $total_tax,
                'shipping' => $shipping,
                'grand_total' => $grand_total,
                'status' => 'pending',
                'updated_by' => $this->session->userdata('user_id'),
                'updated_at' => date('Y-m-d H:i:s'),
            );
			
            if ($_FILES['document']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->digital_upload_path;
                $config['allowed_types'] = $this->digital_file_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = false;
                $config['encrypt_name'] = true;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('document')) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                $data['attachment'] = $photo;
            }
			
			$payment = array();

			if($this->input->post('paid_by') == 'deposit' && $customer_details->deposit_amount){
				$payment = array(
					'date' => $date,
					'reference_no' => $this->site->getReference('sp'),
					'amount' => $this->input->post('amount'),
					'paid_by' => $this->input->post('paid_by'),
					'note' => $this->input->post('note') ? $this->input->post('note') : $customer_details->name,
					'created_by' => $this->session->userdata('user_id'),
					'type' => 'received',
					'biller_id'	=> $this->input->post('biller')
				);
			}
			
            //$this->erp->print_arrays($data, $products);
        }

        if ($this->form_validation->run() == true && $this->quotes_model->updateQuote($id, $data, $products, $payment)) {
            $this->session->set_userdata('remove_quls', 1);
			$this->session->set_userdata('remove_q2', '1');
            $this->session->set_flashdata('message', $this->lang->line("quote_added"));
            redirect('quotes');
        } else {
			
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $inv 							= $this->quotes_model->getQuoteByID($id);
			$this->data['inv'] 				= $inv;
            $inv_items 						= $this->quotes_model->getAllQuoteItems($id);
			$this->data['payment'] 			= $this->quotes_model->getPaymentByQuoteID($id);
            $c 								= rand(100000, 9999999);
			$customer 						= $this->site->getCompanyByID($inv->customer_id);
            $customer_group = $this->site->getCustomerGroupByID($customer->customer_group_id);
            foreach ($inv_items as $item) {
				
                $row = $this->site->getProductByIDWh($item->product_id,$item->warehouse_id);
				$dig = $this->site->getProductByID($item->digital_id);
				
                if (!$row) {
                    $row = json_decode('{}');
                    $row->tax_method = 0;
                } else {
                    unset($row->details, $row->product_details, $row->cost, $row->supplier1price, $row->supplier2price, $row->supplier3price, $row->supplier4price, $row->supplier5price);
                }
                $row->quantity = 0;
                $pis = $this->quotes_model->getPurchasedItems($item->product_id, $item->warehouse_id, $item->option_id);
                if ($pis) {
                    foreach ($pis as $pi) {
                        $row->quantity += $pi->quantity_balance;
                    }
                }
				$row->price_id = $item->group_price_id;
                $row->id = $item->product_id;
				$row->piece	 = $item->piece;
				$row->wpiece = $item->wpiece;
				$row->w_piece = $item->wpiece;
                $row->code = $item->product_code;
                $row->name = $item->product_name;
                $row->type = $item->product_type;
                $row->qty = $item->quantity;
				$row->digital_code 	= "";
                $row->digital_name 	= "";
                $row->digital_id   	= "";
				if($dig){
					$row->digital_code 	= $dig->code .' ['. $row->code .']';
					$row->digital_name 	= $dig->name .' ['. $row->name .']';
					$row->digital_id   	= $dig->id;
				}
				
                $row->discount = $item->discount ? $item->discount : '0';
                $row->price = $this->erp->formatDecimal($item->net_unit_price + $this->erp->formatDecimal($item->item_discount / $item->quantity));
                $row->unit_price = $row->tax_method ? $item->unit_price + $this->erp->formatDecimal($item->item_discount / $item->quantity) + $this->erp->formatDecimal($item->item_tax / $item->quantity) : $item->unit_price + ($item->item_discount / $item->quantity);
                $row->real_unit_price = $item->unit_price;
                $row->tax_rate = $item->tax_rate_id;
                $row->option = $item->option_id;
				$row->product_noted = $item->product_noted;
                $options = $this->quotes_model->getProductOptions($row->id, $item->warehouse_id);
				
				if($expiry_status = 1){
					$row->expdate = $item->expiry_id;
				}
				$group_prices = $this->sales_model->getProductPriceGroup($row->id, $customer->price_group_id);
				$all_group_prices = $this->sales_model->getProductPriceGroup($row->id);
				$dropdown_group_prices = $this->sales_model->getOptPriceGroups($row->id);
				
                if ($options) {
                    $option_quantity = 0;
                    foreach ($options as $option) {
                        $pis = $this->quotes_model->getPurchasedItems($row->id, $item->warehouse_id, $item->option_id);
                        if ($pis) {
                            foreach ($pis as $pi) {
                                $option_quantity += $pi->quantity_balance;
                            }
                        }
                        if ($option->quantity > $option_quantity) {
                            $option->quantity = $option_quantity;
                        }
                    }
                }

                $combo_items = false;
                if ($row->type == 'combo') {
                    $combo_items = $this->quotes_model->getProductComboItems($row->id, $item->warehouse_id);
                    $te = $combo_items;
                    foreach ($combo_items as $combo_item) {
                        $combo_item->quantity = $combo_item->qty * $item->quantity;
                    }
                }
				
				if($group_prices)
				{
				   $curr_by_item = $this->site->getCurrencyByCode($group_prices[0]->currency_code);
				}
                $customer_percent = $customer_group ? $customer_group->percent : 0;
				$row->rate_item_cur   = (isset($curr_by_item->rate)?$curr_by_item->rate:0);
				$row->load_item       = 1;
				$row->item_edit       = 1;

                $ri = $this->Settings->item_addition ? $c : $c;
                if ($row->tax_rate) {
                    $tax_rate = $this->site->getTaxRateByID($row->tax_rate);
                    $pr[$ri] = array('id' => $c, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'combo_items' => $combo_items, 'tax_rate' => $tax_rate, 'options' => $options,'group_prices' => $group_prices, 'all_group_prices' => $all_group_prices, 'dropdown_group_prices' => $dropdown_group_prices,'customer_percent' => $customer_percent);
                } else {
                    $pr[$ri] = array('id' => $c, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'combo_items' => $combo_items, 'tax_rate' => false, 'options' => $options,'group_prices' => $group_prices, 'all_group_prices' => $all_group_prices, 'dropdown_group_prices' => $dropdown_group_prices,'customer_percent' => $customer_percent);
                }
                $c++;
            }			
			
			$this->load->model('purchases_model');
            $this->data['inv_items'] 		= json_encode($pr);
            $this->data['id'] 				= $id;
			$this->data['categories'] = $this->site->getAllCategories();
		
			$this->data['unit'] = $this->purchases_model->getUnits();
			$this->data['payment_deposit'] 	= $this->quotes_model->getPaymentByQuoteID($id);
            $this->data['billers'] 			= ($this->Owner || $this->Admin || !$this->session->userdata('biller_id')) ? $this->site->getAllCompanies('biller') : null;
            $this->data['tax_rates'] 		= $this->site->getAllTaxRates();
            $this->data['warehouses'] = $this->site->getAllWarehouses();
			$this->data['agencies'] 		= $this->site->getAllUsers();
			$this->data['areas'] 			= $this->site->getArea();
			$this->session->set_userdata('remove_q2', '1');
            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('quotes'), 'page' => lang('quotes')), array('link' => '#', 'page' => lang('edit_quote')));
            $meta = array('page_title' => lang('edit_quote'), 'bc' => $bc);
            $this->page_construct('quotes/edit', $meta, $this->data);
        }
    }
	

    public function delete($id = null)
    {
        $this->erp->checkPermissions('delete',null,'quotes');
		if (($this->quotes_model->getQuotesData($id)->status) == 'completed') {
			$this->session->set_flashdata('error', lang('quote_cannot_delete'));
			redirect($_SERVER['HTTP_REFERER']);
		}

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        if ($this->quotes_model->deleteQuote($id)) {
            if ($this->input->is_ajax_request()) {
                echo lang("quote_deleted");die();
            }
            $this->session->set_flashdata('message', lang('quote_deleted'));
            redirect('welcome');
        }
    }
	
	function quote_alerts($warehouse_id = NULL)
	{  
		$this->load->model('reports_model');
        $this->data['warehouse_id'] = $warehouse_id;
		$this->data['users'] = $this->reports_model->getStaff();
        $this->data['warehouses'] = $this->site->getAllWarehouses();
        $this->data['billers'] = $this->site->getAllCompanies('biller');
        $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
		$bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('quotes'), 'page' => lang('quotes')), array('link' => '#', 'page' => lang('list_delivery_alerts')));
		$meta = array('page_title' => lang('list_quote_alerts'), 'bc' => $bc);
		$this->page_construct('quotes/quote_alerts', $meta, $this->data);
    }
	function getQuoteAlerts($warehouse_id = NULL)
	{
        $this->erp->checkPermissions('index',null,'quotes');
		if($warehouse_id){
			$warehouse_ids = explode('-',$warehouse_id);
		}
		
		if ($this->input->get('user')) {
            $user_query = $this->input->get('user');
        } else {
            $user_query = NULL;
        }
        if ($this->input->get('reference_no')) {
            $reference_no = $this->input->get('reference_no');
        } else {
            $reference_no = NULL;
        }
        if ($this->input->get('customer')) {
            $customer = $this->input->get('customer');
        } else {
            $customer = NULL;
        }
		if ($this->input->get('saleman')) {
            $saleman = $this->input->get('saleman');
        } else {
            $saleman = NULL;
        }
		if ($this->input->get('product_id')) {
            $product_id = $this->input->get('product_id');
        } else {
            $product_id = NULL;
        }
        if ($this->input->get('biller')) {
            $biller = $this->input->get('biller');
        } else {
            $biller = NULL;
        }
		if ($this->input->get('warehouse')) {
            $warehouse = $this->input->get('warehouse');
        } else {
            $warehouse = NULL;
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
	
        if ((!$this->Owner || !$this->Admin) && !$warehouse_id) {
            $user = $this->site->getUser();
            $warehouse_id = $user->warehouse_id;
        }
		
        $detail_link = anchor('quotes/view/$1', '<i class="fa fa-file-text-o"></i> ' . lang('quote_details'));
        $email_link = anchor('quotes/email/$1', '<i class="fa fa-envelope"></i> ' . lang('email_quote'), 'data-toggle="modal" data-target="#myModal"');
        $edit_link = anchor('quotes/edit/$1', '<i class="fa fa-edit"></i> ' . lang('edit_quote'));
		$add_sale_order = anchor('sale_order/add_sale_order/$1', '<i class="fa fa-heart"></i> ' . lang('add_sale_order'));
        $add_sale = anchor('sales/add/0/0/$1', '<i class="fa fa-heart"></i> ' . lang('add_sale'));
        $pc_link = anchor('purchases/add/0/$1', '<i class="fa fa-star"></i> ' . lang('create_purchase'));
        $pdf_link = anchor('quotes/pdf/$1', '<i class="fa fa-file-pdf-o"></i> ' . lang('download_pdf'));
        
		$approve = anchor('quotes/getAuthorization/$1', '<i class="fa fa-check"></i> ' . lang('approve'), '');
		//$unapprove = anchor('quotes/getunapprove/$1', '<i class="fa fa-check"></i> ' . lang('unapprove'), '');
		
		$ordered_link = anchor('quotes/update_quotes/$1', '<i class="fa fa-check"></i> ' . lang('approved'), '');
		$unordered_link = anchor('quotes/Unapproved/$1', '<i class="fa fa-angle-double-left"></i> ' . lang('unapprove'), '');
		
		$rejected = anchor('quotes/getrejected/$1', '<i class="fa fa-times"></i> ' . lang('reject'), '');
		$delete_link = "<a href='#' class='po' title='<b>" . $this->lang->line("delete_quote") . "</b>' data-content=\"<p>"
        . lang('r_u_sure') . "</p><a class='btn btn-danger' href='" . site_url('quotes/delete/$1') . "'>"
        . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i> "
        . lang('delete_quote') . "</a>";
        $action = '<div class="text-center"><div class="btn-group text-left">'
        . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
        . lang('actions') . ' <span class="caret"></span></button>
			<ul class="dropdown-menu pull-right" role="menu">
				<li>' . $detail_link . '</li>'
				
				.(($this->Owner || $this->Admin) ? '<li class="edit">'.$edit_link.'</li>' : ($this->GP['quotes-edit'] ? '<li class="edit">'.$edit_link.'</li>' : '')).
				(($this->Owner || $this->Admin) ? '<li class="approved">'.$ordered_link.'</li>' : ($this->GP['quotes-authorize'] ? '<li class="approved">'.$approve.'</li>' : '')).
				(($this->Owner || $this->Admin) ? '<li class="unapproved">'.$unordered_link.'</li>' : ($this->GP['quotes-authorize'] ? '<li class="unapproved">'.$unordered_link.'</li>' : '')).
				(($this->Owner || $this->Admin) ? '<li class="rejected">'.$rejected.'</li>' : ($this->GP['quotes-authorize'] ? '<li class="rejected">'.$rejected.'</li>' : '')).
				'<li class="add_so">' . $add_sale_order . '</li>
				<li class="add_sale">' . $add_sale . '</li>
				<li class="create">' . $pc_link . '</li>'

                .(($this->Owner || $this->Admin) ? '<li>'.$pdf_link.'</li>' : ($this->GP['quotes-export'] ? '<li>'.$pdf_link.'</li>' : '')).
                 (($this->Owner || $this->Admin) ? '<li>'.$email_link.'</li>' : ($this->GP['quotes-email'] ? '<li>'.$email_link.'</li>' : '')).
				
			'</ul>
		</div></div>';
        //$action = '<div class="text-center">' . $detail_link . ' ' . $edit_link . ' ' . $email_link . ' ' . $delete_link . '</div>';

        $biller_id = $this->session->userdata('biller_id');
        $this->load->library('datatables');
        if ($warehouse_id) {
            $this->datatables
                ->select("quotes.id, quotes.date, quotes.reference_no, quotes.biller, quotes.customer,users.username, quotes.grand_total, quotes.issue_invoice,  quotes.status")
                ->from('quotes')
				->join('payments', 'payments.deposit_quote_id = quotes.id', 'left')
				->join('users','users.id = quotes.saleman','left')
                ->join('users bill', 'quotes.created_by = users.id', 'left')
				->where('quotes.status','pending')
                ->where('quotes.biller_id', $biller_id);

                if (count($warehouse_ids) > 1) {
                    $this->datatables->where_in('quotes.warehouse_id', $warehouse_ids);
                } else {
                    $this->datatables->where('quotes.warehouse_id', $warehouse_id);
                }
                
        } else {
            $this->datatables
                ->select("quotes.id, quotes.date, quotes.reference_no, quotes.biller, IF(erp_companies.company = '',erp_quotes.customer, erp_companies.company) AS customer,users.username, quotes.grand_total, quotes.issue_invoice,  quotes.status")
                ->from('quotes')
                ->join('payments', 'payments.deposit_quote_id = quotes.id', 'left')
				->join('companies', 'quotes.customer_id = companies.id', 'left')
				->join('users','users.id = quotes.saleman','left')
				->where('quotes.status','pending');
        }
		
        if (!$this->Customer && !$this->Supplier && !$this->Owner && !$this->Admin && !$this->session->userdata('view_right')) {
            $this->datatables->where('quotes.created_by', $this->session->userdata('user_id'));
        } elseif ($this->Customer) {
            $this->datatables->where('customer_id', $this->session->userdata('user_id'));
        }
		
		if ($user_query) {
			$this->datatables->where('quotes.created_by', $user_query);
		}
		
		if ($product_id) {
			$this->datatables->join('quote_items', 'quote_items.quote_id = quotes.id', 'left');
			$this->datatables->where('quote_items.product_id', $product_id);
		}
		
		if ($reference_no) {
			$this->datatables->where('quotes.reference_no', $reference_no);
		}
		if ($biller) {
			$this->datatables->where('quotes.biller_id', $biller);
		}
		if ($customer) {
			$this->datatables->where('quotes.customer_id', $customer);
		}
		
		if($saleman){
			$this->datatables->where('quotes.saleman', $saleman);
		}
		
		if ($warehouse) {
			$this->datatables->where('quotes.warehouse_id', $warehouse);
		}

		if ($start_date) {
			$this->datatables->where($this->db->dbprefix('quotes').'.date BETWEEN "' . $start_date . ' 00:00:00" and "' . $end_date . ' 23:59:00"');
		}
		
        $this->datatables->add_column("Actions", $action, "quotes.id");
        echo $this->datatables->generate();
    }
	
    public function suggestions()
    {
        $term = $this->input->get('term', true);
        $warehouse_id = $this->input->get('warehouse_id', true);
        $customer_id = $this->input->get('customer_id', true);

        if (strlen($term) < 1 || !$term) {
            die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . site_url('welcome') . "'; }, 10);</script>");
        }

        $spos = strpos($term, '%');
        if ($spos !== false) {
            $st = explode("%", $term);
            $sr = trim($st[0]);
            $option = trim($st[1]);
        } else {
            $sr = $term;
            $option = '';
        }
        $customer = $this->site->getCompanyByID($customer_id);
        $customer_group = $this->site->getCustomerGroupByID($customer->customer_group_id);
        //$rows = $this->quotes_model->getProductNames($sr, $warehouse_id);

        $user_setting = $this->site->getUserSetting($this->session->userdata('user_id'));
        $rows = $this->purchases_model->getProductNames($sr, $user_setting->purchase_standard, $user_setting->purchase_combo, $user_setting->purchase_digital, $user_setting->purchase_service, $user_setting->purchase_category);

        if ($rows) {

            foreach ($rows as $row) {
                $option = false;

                $row->quantity = 0;
                $row->item_tax_method = $row->tax_method;
                $row->qty = 1;
                $row->discount = '0';
                $options = $this->purchases_model->getProductOptions($row->id);
                //$options = $this->sales_model->getProductOptions($row->id, $warehouse_id);
                // $group_prices = $this->sales_model->getProductPriceGroup($row->id, $customer->price_group_id);
                $group_prices = $this->sales_model->getProductPriceGroupId($row->id, $customer->price_group_id);
                $all_group_prices = $this->sales_model->getProductPriceGroup($row->id);
				$dropdown_group_prices = $this->sales_model->getOptPriceGroups($row->id);

                if ($options) {
					
                    $opt = $options[count($options)-1];
                    if (!$option) {
                        $option = $opt->id;
                    }
                } else {
                    $opt = json_decode('{}');
                    $opt->price = 0;
                }
                $row->option = $option;
				
                $pis = $this->quotes_model->getPurchasedItems($row->id, $warehouse_id, $row->option);
                if ($pis) {
                    foreach ($pis as $pi) {
                        $row->qoh += $pi->quantity_balance;
                    }
                }
                if ($options) {
                    $option_quantity = 0;
                    foreach ($options as $option) {
                        $pis = $this->quotes_model->getPurchasedItems($row->id, $warehouse_id, $row->option);
                        if ($pis) {
                            foreach ($pis as $pi) {
                                $option_quantity += $pi->quantity_balance;
                            }
                        }
                        if ($option->quantity > $option_quantity) {
                            $option->quantity = $option_quantity;
                        }
                    }
                }
				
				$setting = $this->sales_model->getSettings();
				
				if($row->subcategory_id)
				{
					$percent = $this->sales_model->getCustomerMakup($customer->customer_group_id,$row->id,1);
				}else{
					$percent = $this->sales_model->getCustomerMakup($customer->customer_group_id,$row->id,0);
				}
				
				//$this->erp->print_arrays($percent);
				
				//$percent =(isset(percent)?$percent:0);

                if ($opt->price != 0) {
                    if ($customer_group != NULL) {
                        if ($customer_group->makeup_cost == 1 && $percent != "") {
                            if ($setting->attributes == 1) {
                                if (isset($percent->percent)) {
                                    $row->price = ($row->cost * $opt->qty_unit) + ((($row->cost * $opt->qty_unit) * (isset($percent->percent) ? $percent->percent : 0)) / 100);
                                } else {
                                    $row->price = $opt->price + (($opt->price * $customer_group->percent) / 100);
                                }
                            }
                        } else {
                            if ($setting->attributes == 1) {
                                $row->price = $opt->price + (($opt->price * $customer_group->percent) / 100);
                            }
                        }
                    }
                } else { 
					if($customer_group->makeup_cost == 1 && $percent!=""){
						if($setting->attributes==1)
						{
							if(isset($percent->percent)) {
								$row->price = $row->cost  + (($row->cost * (isset($percent->percent)?$percent->percent:0)) / 100);
							}else {
								$row->price = $row->price + (($row->price * $customer_group->percent) / 100);
							}
						}
					}else{
						if($setting->attributes==1)
						{
							$row->price = $row->price + (($row->price * $customer_group->percent) / 100);
						}
					}
                }

                if($group_prices)
                {
                    $curr_by_item = $this->site->getCurrencyByCode($group_prices[0]->currency_code);
                    $row->price_id = $group_prices[0]->id ? $group_prices[0]->id : 0;
                    $row->price = $group_prices[0]->price ? $group_prices[0]->price : 0;

                    if ($customer_group != NULL) {
                        if ($customer_group->makeup_cost == 1) {
                            //$row->price = $row->cost + (($row->cost * $customer_group->percent) / 100);
                            $row->price = $row->cost + (($row->cost * (isset($percent->percent) ? $percent->percent : 0)) / 100);
                        } else {
                            $row->price = $group_prices[0]->price + (($group_prices[0]->price * $customer_group->percent) / 100);
                        }
                    }
                }else{
                    $row->price_id = 0;
                }
				
				$row->rate_item_cur   = (isset($curr_by_item->rate)?$curr_by_item->rate:0);
				$row->load_item   	  = 1;
				$row->item_edit   	  = 0;
				$row->piece		  	  = 0;
				$row->wpiece	  	  = $row->cf1;
				$row->w_piece	  	  = $row->cf1;
				$row->digital_id	  = 0;
				$row->digital_code	  = '';
				$row->digital_name	  = '';
                $row->real_unit_price = $row->price;
                $row->product_noted   = $row->product_details;

                $combo_items = false;
                $customer_percent = $customer_group ? $customer_group->percent : 0;
                $customer_group_makeup_cost = $customer_group ? $customer_group->makeup_cost : 0;
                if ($row->tax_rate) {
                    $tax_rate = $this->site->getTaxRateByID($row->tax_rate);
                    if ($row->type == 'combo') {
                        $combo_items = $this->quotes_model->getProductComboItems($row->id, $warehouse_id);
                    }

                    $pr[] = array('id' => str_replace(".", "", microtime(true)), 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'combo_items' => $combo_items, 'tax_rate' => $tax_rate, 'options' => $options, 'warehouse_id' => $warehouse_id, 'customer_id' => $customer_id, 'group_prices' => $group_prices, 'all_group_prices' => isset($all_group_prices), 'dropdown_group_prices' => $dropdown_group_prices, 'makeup_cost' => $customer_group_makeup_cost, 'customer_percent' => $customer_percent, 'makeup_cost_percent' => (isset($percent->percent) ? $percent->percent : 0));
					
                } else {

                    $pr[] = array('id' => str_replace(".", "", microtime(true)), 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'combo_items' => $combo_items, 'tax_rate' => false, 'options' => $options, 'warehouse_id' => $warehouse_id, 'customer_id' => $customer_id, 'group_prices' => $group_prices, 'all_group_prices' => $all_group_prices, 'dropdown_group_prices' => $dropdown_group_prices, 'makeup_cost' => $customer_group_makeup_cost, 'customer_percent' => $customer_percent, 'makeup_cost_percent' => (isset($percent->percent) ? $percent->percent : 0));
						
                }
            }
            echo json_encode($pr);
			
        } else {
            echo json_encode(array(array('id' => 0, 'label' => lang('no_match_found'), 'value' => $term)));
        }
    }
	
	function quote_($id = NULL, $view = NULL, $save_bufffer = NULL)
    {
        $this->erp->checkPermissions('add', true, 'sales');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $this->load->model('pos_model');
        $this->data['setting'] = $this->site->get_setting();
        $this->data['pos'] = $this->pos_model->getSetting();
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $inv = $this->quotes_model->getQuoteByID2($id);
        $this->data['barcode'] = "<img src='" . site_url('products/gen_barcode/' . $inv->reference_no) . "' alt='" . $inv->reference_no . "' class='pull-left' />";

        $this->data['customer'] = $this->site->getCompanyByIDCustomer($inv->customer_id);
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
        $this->data['user'] = $this->site->getUser($inv->created_by);
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
        $this->data['inv'] = $inv;

        $this->data['quote_items'] = $this->quotes_model->getQuoteItemsData($id);
		$this->data['qid'] = $id;

        $this->load->view($this->theme.'quotes/quote_',$this->data);
    }
	
    function quotes_chea_kheng($quote_id=null){
        $this->erp->checkPermissions('index', true);

        if ($this->input->get('id')) {
            $quote_id = $this->input->get('id');
        }
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $inv = $this->quotes_model->getQuoteByID($quote_id);     
        $this->data['rows'] = $this->quotes_model->getAllQuoteItems2($quote_id,$inv->warehouse_id);
        //$this->erp->print_arrays($this->quotes_model->getAllQuoteItems2($quote_id));
        
        $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);       
        $this->data['created_by'] = $this->site->getUser($inv->created_by);     
        $this->data['updated_by'] = $inv->updated_by ? $this->site->getUser($inv->updated_by) : null;   
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);               
        $this->data['inv'] = $inv;      
        $this->data['deposit'] = $this->quotes_model->getQuoteDepositByQuoteID($quote_id);
        $this->data['logo'] = true;
        $this->load->view($this->theme . 'quotes/quote_chea_kheng', $this->data);
    }
    public function quote_actions($wh=null)
    {
        if($wh){
            $wh = explode('-', $wh);
        }
        // $this->erp->print_arrays($wh);
        // if (!$this->Owner) {
        //     $this->session->set_flashdata('warning', lang('access_denied'));
        //     redirect($_SERVER["HTTP_REFERER"]);
        // }

        $this->form_validation->set_rules('form_action', lang("form_action"), 'required');

        if ($this->form_validation->run() == true) {

            if (!empty($_POST['val'])) {
                if ($this->input->post('form_action') == 'delete') {

                    foreach ($_POST['val'] as $id) {
                        $this->quotes_model->deleteQuote($id);
                    }
                    $this->session->set_flashdata('message', $this->lang->line("quotes_deleted"));
                    redirect($_SERVER["HTTP_REFERER"]);

                } elseif ($this->input->post('form_action') == 'combine') {

                    $html = $this->combine_pdf($_POST['val']);

                } elseif ($this->input->post('form_action') == 'export_excel' || $this->input->post('form_action') == 'export_pdf') {
                    if($this->Owner || $this->Admin){
                    $this->load->library('excel');
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle(lang('quotes'));
                    $this->excel->getActiveSheet()->SetCellValue('A1', lang('date'));
                    $this->excel->getActiveSheet()->SetCellValue('B1', lang('reference_no'));
                    $this->excel->getActiveSheet()->SetCellValue('C1', lang('biller'));
                    $this->excel->getActiveSheet()->SetCellValue('D1', lang('customer'));
                    $this->excel->getActiveSheet()->SetCellValue('E1', lang('total'));
                    $this->excel->getActiveSheet()->SetCellValue('F1', lang('issue_status'));
                    // $this->excel->getActiveSheet()->SetCellValue('G1', lang('balance'));
                    $this->excel->getActiveSheet()->SetCellValue('G1', lang('status'));

                    $row = 2;
                    $sum_total = $sum_balance = 0;
                    foreach ($_POST['val'] as $id) {
                        $qu = $this->quotes_model->getQuoteByID($id);
                        $sum_total += $qu->total;
                        $sum_balance += $qu->grand_total;
                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $this->erp->hrld($qu->date));
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $qu->reference_no." ");
                        $this->excel->getActiveSheet()->SetCellValue('C' . $row, $qu->biller);
                        $this->excel->getActiveSheet()->SetCellValue('D' . $row, $qu->customer);
                        $this->excel->getActiveSheet()->SetCellValue('E' . $row, $qu->grand_total);
                        $this->excel->getActiveSheet()->SetCellValue('F' . $row, $qu->issue_invoice);
                        $this->excel->getActiveSheet()->SetCellValue('G' . $row, $qu->status);
                        $new_row = $row+1;
                        // $this->excel->getActiveSheet()->SetCellValue('E' . $new_row, $sum_total);
                        $this->excel->getActiveSheet()->SetCellValue('E' . $new_row, $sum_balance);
                        $row++;
                    }
                }else{
                    // echo "user";exit();
                    $this->load->library('excel');
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle(lang('quotes'));
                    $this->excel->getActiveSheet()->SetCellValue('A1', lang('date'));
                    $this->excel->getActiveSheet()->SetCellValue('B1', lang('reference_no'));
                    $this->excel->getActiveSheet()->SetCellValue('C1', lang('biller'));
                    $this->excel->getActiveSheet()->SetCellValue('D1', lang('customer'));
                    $this->excel->getActiveSheet()->SetCellValue('E1', lang('total'));
                    $this->excel->getActiveSheet()->SetCellValue('F1', lang('issue_status'));
                    // $this->excel->getActiveSheet()->SetCellValue('G1', lang('balance'));
                    $this->excel->getActiveSheet()->SetCellValue('G1', lang('status'));
                    

                    $row = 2;
                    $sum_total = $sum_balance = 0;
                    foreach ($_POST['val'] as $id) {
                        $qu = $this->quotes_model->getQuoteByID($id);
                        $sum_total += $qu->total;
                        $sum_balance += $qu->grand_total;
                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $this->erp->hrld($qu->date));
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $qu->reference_no." ");
                        $this->excel->getActiveSheet()->SetCellValue('C' . $row, $qu->biller);
                        $this->excel->getActiveSheet()->SetCellValue('D' . $row, $qu->customer);
                        $this->excel->getActiveSheet()->SetCellValue('E' . $row, $qu->grand_total);
                        $this->excel->getActiveSheet()->SetCellValue('F' . $row, $qu->issue_invoice);
                        $this->excel->getActiveSheet()->SetCellValue('G' . $row, $qu->status);
                        $new_row = $row+1;
                        // $this->excel->getActiveSheet()->SetCellValue('E' . $new_row, $sum_total);
                        $this->excel->getActiveSheet()->SetCellValue('E' . $new_row, $sum_balance);
                        $row++;
                    }
                }

                    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
                    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
                    $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
                    $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
                    $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
                    $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
                    $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
                    // $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $filename = 'quotations_' . date('Y_m_d_H_i_s');
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
                        $styleArray = array(
                            'font'  => array(
                                'bold'  => true
                            )
                        );
                        
                        $this->excel->getActiveSheet()->getStyle('A1:G1')->applyFromArray($styleArray);
                        $this->excel->getActiveSheet()->getStyle('A1:G1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $this->excel->getActiveSheet()->getStyle('E' . $new_row.'')->getFont()->setBold(true);

                        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'PDF');
                        return $objWriter->save('php://output');
                    }
                    if ($this->input->post('form_action') == 'export_excel') {
                        header('Content-Type: application/vnd.ms-excel');
                        header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
                        header('Cache-Control: max-age=0');
                        $styleArray = array(
                            'font'  => array(
                                'bold'  => true
                            )
                        );
                        
                        $this->excel->getActiveSheet()->getStyle('A1:G1')->applyFromArray($styleArray);
                        $this->excel->getActiveSheet()->getStyle('A1:G1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

                        $this->excel->getActiveSheet()->getStyle('E' . $new_row.'')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border:: BORDER_THIN);
                        $this->excel->getActiveSheet()->getStyle('E' . $new_row.'')->getFont()->setBold(true);
                        
                        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
                        return $objWriter->save('php://output');
                    }

                    redirect($_SERVER["HTTP_REFERER"]);
                }
            } else {
                $this->session->set_flashdata('error', $this->lang->line("no_quote_selected"));
                redirect($_SERVER["HTTP_REFERER"]);
            }
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }
	
	function add_deposit()
    {
        $this->erp->checkPermissions('deposits', true);

        if ($this->Owner || $this->Admin) {
            $this->form_validation->set_rules('date', lang("date"), 'required');
        }
		$this->form_validation->set_rules('date', lang("date"), 'required');
        $this->form_validation->set_rules('amount', lang("amount"), 'required|numeric');
        
        if ($this->form_validation->run() == true) {
			$company_id = $this->input->post('customer');
			$company = $this->site->getCompanyByID($company_id);

            if ($this->Owner || $this->Admin) {
                $date = $this->erp->fld(trim($this->input->post('date')));
            } else {
                $date = date('Y-m-d H:i:s');
            }
            $data = array(
                'date' => $date,
                'amount' => $this->input->post('amount'),
                'paid_by' => $this->input->post('paid_by'),
                'note' => $this->input->post('note') ? $this->input->post('note') : $company->name,
                'company_id' => $company->id,
                'created_by' => $this->session->userdata('user_id'),
				'biller_id' => $this->input->post('biller')
            );
			$payment = array(
				'date' => $date,
				'reference_no' => $this->site->getReference('sp'),
				'amount' => $this->input->post('amount'),
				'paid_by' => $this->input->post('paid_by'),
				'cheque_no' => $this->input->post('cheque_no'),
				'cc_no' => $this->input->post('paid_by') == 'gift_card' ? $this->input->post('gift_card_no') : $this->input->post('pcc_no'),
				'cc_holder' => $this->input->post('pcc_holder'),
				'cc_month' => $this->input->post('pcc_month'),
				'cc_year' => $this->input->post('pcc_year'),
				'cc_type' => $this->input->post('pcc_type'),
				'note' => $this->input->post('note') ? $this->input->post('note') : $company->name,
				'created_by' => $company->id,
				'type' => 'received',
				'biller_id'	=> $this->input->post('biller')
			);
            $cdata = array(
                'deposit_amount' => ($company->deposit_amount+$this->input->post('amount'))
            );
        } elseif ($this->input->post('add_deposit')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER['HTTP_REFERER']);
        }

        if ($this->form_validation->run() == true && $this->quotes_model->addDeposit($data, $cdata, $payment)) {
            $this->session->set_flashdata('message', lang("deposit_added"));
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
			$this->data['billers'] = $this->site->getAllCompanies('biller');
            $this->data['modal_js'] = $this->site->modal_js();
            $this->data['customers'] = $this->site->getCustomers();
            $this->load->view($this->theme . 'quotes/add_deposit', $this->data);
        }
    }
	
	function getAuthorization($id) {
		$this->erp->checkPermissions('authorize', NULL, 'quotes');
		if (($this->quotes_model->getQuotesData($id)->status) == 'completed' ) {
			$this->session->set_flashdata('error', lang('quote_has_been_approved'));
			redirect($_SERVER['HTTP_REFERER']);
		}
		$this->erp->checkPermissions('authorize', TRUE, 'quotes');
		if($this->quotes_model->getAuthorizeQuotes($id)){
			$this->session->set_flashdata('message', $this->lang->line("quote_approved"));
			redirect($_SERVER["HTTP_REFERER"]);	
		}else{
			$this->session->set_flashdata('error', validation_errors());
			die();
		}
	}
	function getunapprove($id) {
		$this->erp->checkPermissions('authorize', NULL, 'quotes');
		if (($this->quotes_model->getQuotesData($id)->issue_invoice) != 'pending' ) {
			$this->session->set_flashdata('error', lang('quote_can_not_unapprove'));
			redirect($_SERVER['HTTP_REFERER']);
		}
		$this->erp->checkPermissions('authorize', TRUE, 'quotes');
		if($this->quotes_model->getunapproveQuotes($id)){
			$this->session->set_flashdata('message', $this->lang->line("quote_unapproved"));
			redirect($_SERVER["HTTP_REFERER"]);	
		}else{
			$this->session->set_flashdata('error', validation_errors());
			die();
		}
	}
	function getrejected($id) {
		$this->erp->checkPermissions('authorize', NULL, 'quotes');
		if (($this->quotes_model->getQuotesData($id)->issue_invoice) != 'pending' ) {
			$this->session->set_flashdata('error', lang('quote_can_not_rejecte'));
			redirect($_SERVER['HTTP_REFERER']);
		}
		$this->erp->checkPermissions('authorize', TRUE, 'quotes');
		if($this->quotes_model->getrejectedQuotes($id)){
			$this->session->set_flashdata('message', $this->lang->line("quote_rejected"));
			redirect($_SERVER["HTTP_REFERER"]);	
		}else{
			$this->session->set_flashdata('error', validation_errors());
			die();
		}
	}
	
	
	//  Test Invoice quoted
	function invoice_quotes($quote_id = NULL)
    {
         $this->erp->checkPermissions('index', true);

        if ($this->input->get('id')) {
            $quote_id = $this->input->get('id');
        }
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $inv = $this->quotes_model->getQuoteByID2($quote_id);		
        $this->data['rows'] = $this->quotes_model->getAllQuoteItems2($quote_id,$inv->warehouse_id);
        $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);		
        $this->data['created_by'] = $this->site->getUser($inv->created_by);		
        $this->data['updated_by'] = $inv->updated_by ? $this->site->getUser($inv->updated_by) : null;	
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);				
        $this->data['inv'] = $inv;		
		$this->data['deposit'] = $this->quotes_model->getQuoteDepositByQuoteID($quote_id);
        $this->load->view($this->theme . 'quotes/invoice_quotes.php', $this->data);	
    }
	
	public function update_quotes($request_id=null){
		    $statu="approved";
		    $this->db->set('status',$statu);   
			$this->db->where('id', $request_id);
        $update = $this->db->update('erp_quotes');
			$this->session->set_flashdata('message', $this->lang->line("quote is approved."));
		redirect($_SERVER["HTTP_REFERER"]);	 
	}
	
	public function Unapproved($request_id=null){
		$status="pending";
		$this->db->set('status',$status);
		$this->db->where('id',$request_id);
		$update=$this->db->update('erp_quotes');
		$this->session->set_flashdata('message', $this->lang->line("quote is unapproved."));
		redirect($_SERVER["HTTP_REFERER"]);	
	}
	public function getProductNamesDigital(){
		$id = $this->input->get('id');
		$warehouse_id = $this->input->get('ware');
		$customer_id = $this->input->get('cid');
		 $customer = $this->site->getCompanyByID($customer_id);
        $customer_group = $this->site->getCustomerGroupByID($customer->customer_group_id);
		
		$rows =  $this->quotes_model->getProductNamesDigital($id,$warehouse_id);
            foreach ($rows as $row) {
                $option = false;
				
                $row->quantity = 0;
                $row->item_tax_method = $row->tax_method;
                $row->qty = 1;
                $row->discount = '0';
				$options = $this->sales_model->getProductOptions($row->id, $warehouse_id);
				
				$group_prices = $this->sales_model->getProductPriceGroup($row->id, $customer->price_group_id);
				$all_group_prices = $this->sales_model->getProductPriceGroup($row->id);
				
				$row->price_id = 0;
				
                if ($options) {
					
                    $opt = $options[count($options)-1];
                    if (!$option) {
                        $option = $opt->id;
                    }
                } else {
                    $opt = json_decode('{}');
                    $opt->price = 0;
                }
                $row->option = $option;
				
                $pis = $this->quotes_model->getPurchasedItems($row->id, $warehouse_id, $row->option);
                if ($pis) {
                    foreach ($pis as $pi) {
                        $row->quantity += $pi->quantity_balance;
                    }
                }
                if ($options) {
                    $option_quantity = 0;
                    foreach ($options as $option) {
                        $pis = $this->quotes_model->getPurchasedItems($row->id, $warehouse_id, $row->option);
                        if ($pis) {
                            foreach ($pis as $pi) {
                                $option_quantity += $pi->quantity_balance;
                            }
                        }
                        if ($option->quantity > $option_quantity) {
                            $option->quantity = $option_quantity;
                        }
                    }
                }
				
				$setting = $this->sales_model->getSettings();
				
				if($row->subcategory_id)
				{
					$percent = $this->sales_model->getCustomerMakup($customer->customer_group_id,$row->id,1);
				}else{
					$percent = $this->sales_model->getCustomerMakup($customer->customer_group_id,$row->id,0);
				}
				
				//$this->erp->print_arrays($percent);
				
				//$percent =(isset(percent)?$percent:0);
				
				
                if ($opt->price != 0) {
					if($customer_group->makeup_cost == 1 && $percent!=""){
						if($setting->attributes==1)
						{
							$row->price = ($row->cost*$opt->qty_unit)  + ((($row->cost*$opt->qty_unit)  * (isset($percent->percent)?$percent->percent:0)) / 100);
							
						}
					}else{
						if($setting->attributes==1)
						{
							$row->price = $opt->price + (($opt->price * $customer_group->percent) / 100);
						}
					}
                } else { 
					if($customer_group->makeup_cost == 1 && $percent!=""){
						if($setting->attributes==1)
						{
							$row->price = $row->cost  + (($row->cost * (isset($percent->percent)?$percent->percent:0)) / 100);
						}
					}else{
						if($setting->attributes==1)
						{
							$row->price = $row->price + (($row->price * $customer_group->percent) / 100);
						}
					}
                }
				
				if($group_prices)
				{
				   $curr_by_item = $this->site->getCurrencyByCode($group_prices[0]->currency_code);
				}
				
				$row->rate_item_cur   = (isset($curr_by_item->rate)?$curr_by_item->rate:0);
				$row->load_item   = 1;
				$row->item_edit   = 0;
				$row->piece		  = 0;
				$row->wpiece	  = $row->cf1;
				$row->w_piece	  = $row->cf1;
                $row->real_unit_price = $row->price;
                $combo_items = false;
				
                if ($row->tax_rate) {
                    $tax_rate = $this->site->getTaxRateByID($row->tax_rate);
                    if ($row->type == 'combo') {
                        $combo_items = $this->quotes_model->getProductComboItems($row->id, $warehouse_id);
                    }
					
					$pr[] = array('id' => str_replace(".", "", microtime(true)), 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'combo_items' => $combo_items, 'tax_rate' => $tax_rate, 'options' => $options,'warehouse_id'=>$warehouse_id,'customer_id'=>$customer_id,'group_prices'=>$group_prices, 'all_group_price' => $all_group_prices ,'makeup_cost'=>$customer_group->makeup_cost, 'customer_percent' => $customer_group->percent,'makeup_cost_percent'=>(isset($percent->percent)?$percent->percent:0));
					
                } else {
						
					$pr[] = array('id' => str_replace(".", "", microtime(true)), 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'combo_items' => $combo_items, 'tax_rate' => false, 'options' => $options,'warehouse_id'=>$warehouse_id,'customer_id'=>$customer_id,'group_prices'=>$group_prices, 'all_group_price' => $all_group_prices, 'makeup_cost'=>$customer_group->makeup_cost, 'customer_percent' => $customer_group->percent,'makeup_cost_percent'=>(isset($percent->percent)?$percent->percent:0));
						
                }
	
            }
            echo json_encode($pr);
		
	}
	
	function invoice_quote_chea_kheng($id=null)
	{
        $this->erp->checkPermissions('index', true, 'quotes');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $this->load->model('pos_model');
        $this->data['setting'] = $this->site->get_setting();
        $this->data['pos'] = $this->pos_model->getSetting();
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $inv = $this->quotes_model->getQuoteByID2($id);
		
        $this->data['barcode'] = "<img src='" . site_url('products/gen_barcode/' . $inv->reference_no) . "' alt='" . $inv->reference_no . "' class='pull-left' />";

        $this->data['customer'] = $this->site->getCompanyByIDCustomer($inv->customer_id);
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
        $this->data['user'] = $this->site->getUser($inv->created_by);
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
        $this->data['inv'] = $inv;

        $this->data['quote_items'] = $this->quotes_model->getQuoteItemsData($id);
		$this->data['qid'] = $id;
        $this->data['logo'] = true;
        $this->load->view($this->theme . 'Quotes/invoice_quote_chea_kheng', $this->data);
    }
	public function eang_tay_pdf($quote_id = null, $view = null, $save_bufffer = null)
    {
        $this->erp->checkPermissions('export', null, 'quotes');

        if ($this->input->get('id')) {
            $quote_id = $this->input->get('id');
        }
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $inv = $this->quotes_model->getQuoteByID($quote_id);
        if (!$this->session->userdata('view_right')) {
            $this->erp->view_rights($inv->created_by);
        }
        $this->data['rows'] = $this->quotes_model->getAllQuoteItems($quote_id);
        $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);       
        $this->data['created_by'] = $this->site->getUser($inv->created_by);     
        $this->data['updated_by'] = $inv->updated_by ? $this->site->getUser($inv->updated_by) : null;   
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);               
        $this->data['invs'] = $inv;
        $name = $this->lang->line("quote") . "_" . str_replace('/', '_', $inv->reference_no) . ".pdf";
        $html = $this->load->view($this->theme . 'quotes/eang_tay_pdf', $this->data, true);
        if ($view) {
            $this->load->view($this->theme . 'quotes/eang_tay_pdf', $this->data);
        } elseif ($save_bufffer) {
            return $this->erp->generate_pdf($html, $name, $save_bufffer);
        } else {
            $this->erp->generate_pdf($html, $name);
        }
    }
	
	function invoice_quote_eang_tay_a4($id=null)
	{
        $inv = $this->quotes_model->getQuotesData($id);
		$this->data['setting'] = $this->site->get_setting();
        $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
        $this->data['user'] = $this->site->getUser($inv->created_by);
        $this->data['saleman'] = $this->site->getUser($inv->created_by);
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
        $this->data['invs'] = $inv;
		//$this->erp->print_arrays($this->quotes_model->getQuoteItemsData($id));
        $this->data['rows'] = $this->quotes_model->getQuoteItemsData($id);
        $this->load->view($this->theme .'quotes/invoice_quote_eang_tay_a4',$this->data);
    }
	
	function invoice_quote_eang_tay_a5($id=null)
	{
        $inv = $this->quotes_model->getQuotesData($id);
		$this->data['setting'] = $this->site->get_setting();
        $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
        $this->data['user'] = $this->site->getUser($inv->created_by);
		$this->data['saleman'] = $this->site->getUser($inv->created_by);
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
        $this->data['invs'] = $inv;
		//$this->erp->print_arrays($this->quotes_model->getQuoteItemsData($id));
        $this->data['rows'] = $this->quotes_model->getQuoteItemsData($id);
        $this->load->view($this->theme .'quotes/invoice_quote_eang_tay_a5',$this->data);
    }

}
