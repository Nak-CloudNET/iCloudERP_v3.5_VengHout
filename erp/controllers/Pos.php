<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Pos extends MY_Controller
{

    function __construct()
    {
        parent::__construct();

        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            redirect('login');
        }
        if ($this->Customer || $this->Supplier) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER["HTTP_REFERER"]);
        }
        $this->load->model('pos_model');
        $this->load->model('products_model');
		$this->load->model('settings_model');
		$this->load->model('sales_model');
		$this->load->model('purchases_model');
        $this->load->helper('text');
        $this->pos_settings = $this->pos_model->getSetting();
        $this->pos_settings->pin_code = $this->pos_settings->pin_code ? md5($this->pos_settings->pin_code) : NULL;
        $this->data['pos_settings'] = $this->pos_settings;
        $this->session->set_userdata('last_activity', now());
        $this->lang->load('pos', $this->Settings->language);
        $this->load->library('form_validation');
        if(!$this->Owner && !$this->Admin) {
            $gp = $this->site->checkPermissions();
            $this->permission = $gp[0];
            $this->permission[] = $gp[0];
        } else {
            $this->permission[] = NULL;
        }
    }

    function sales($warehouse_id = NULL)
    {
        $this->erp->checkPermissions('index');
		if(isset($_GET['d']) != ""){
			$date = $_GET['d'];
			$this->data['date'] = $date;
		}
		 
		$this->data['users']    = $this->site->getStaff();
		$this->data['products'] = $this->site->getProducts();
        $this->data['billers']  = $this->site->getAllCompanies('biller');
		$this->data['agencies'] = $this->site->getAllUsers();

        $this->data['error']    = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
		
        if ($this->Owner || $this->Admin || !$this->session->userdata('warehouse_id')) {
            $this->data['warehouses']   = $this->site->getAllWarehouses();
            $this->data['warehouse_id'] = $warehouse_id;
            $this->data['warehouse']    = $warehouse_id ? $this->site->getWarehouseByID($warehouse_id) : NULL;
			
        } else {
            $this->data['warehouses'] = $this->products_model->getUserWarehouses();
			if($warehouse_id){
				$this->data['warehouse_id'] = $warehouse_id;
				$this->data['warehouse'] = $warehouse_id ? $this->site->getWarehouseByID($warehouse_id) : NULL;
			}else{
				//sokhan
				$this->data['warehouse_id'] = str_replace(',', '-',$this->session->userdata('warehouse_id'));
				$this->data['warehouse'] = $this->session->userdata('warehouse_id') ? $this->products_model->getUserWarehouses() : NULL;
			}
        }

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('pos'), 'page' => lang('pos')), array('link' => '#', 'page' => lang('pos_sales')));
        $meta = array('page_title' => lang('pos_sales'), 'bc' => $bc);
		
        $this->page_construct('pos/sales', $meta, $this->data);
    }

	function pos_list($warehouse_id = NULL){
		$this->erp->checkPermissions('index');
		$this->data['table'] = $warehouse_id;
		$this->data['warehouse_id'] = $warehouse_id;
		$this->load->view('default/views/pos/view_list', $this->data);
	} 
    function getSales($warehouse_id = NULL)
    {
        $this->erp->checkPermissions('index');
		if($warehouse_id){
			$warehouse_id = explode('-', $warehouse_id);
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
			
			//$this->erp->print_arrays($product_id);
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

       
		
        $detail_link        = anchor('pos/view/$1', '<i class="fa fa-file-text-o"></i> ' . lang('view_receipt'));
		//$detail_link      = anchor('sales/invoice_st_a5/$1', '<i class="fa fa-file-text-o"></i> ' . lang('view_receipt'));
		$payments_link      = anchor('sales/payments/$1', '<i class="fa fa-money"></i> ' . lang('view_payments'), 'data-toggle="modal" data-target="#myModal"');
        $add_payment_link = anchor('sales/add_payment/$1', '<i class="fa fa-money"></i> ' . lang('add_payment'), 'data-toggle="modal" data-target="#myModal"');
        $add_delivery_link  = anchor('sales/add_delivery/$1', '<i class="fa fa-truck"></i> ' . lang('add_delivery'), 'data-toggle="modal" data-target="#myModal"');
        $email_link         = anchor('#', '<i class="fa fa-envelope"></i> ' . lang('email_sale'), 'class="email_receipt" data-id="$1" data-email-address="$2"');
        $edit_link          = anchor('sales/edit/$1', '<i class="fa fa-edit"></i> ' . lang('edit_sale'), 'class="sledit"');
        $return_link        = anchor('sales/return_sale/$1', '<i class="fa fa-angle-double-left"></i> ' . lang('return_sale'));
        $delete_link        = "<a href='#' class='po' title='<b>" . lang("delete_sale") . "</b>' data-content=\"<p>"
            . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('sales/delete/$1') . "'>"
            . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i> "
            . lang('delete_sale') . "</a>";
        $action = '<div class="text-center"><div class="btn-group text-left">'
            . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
            . lang('actions') . ' <span class="caret"></span></button>
        <ul class="dropdown-menu pull-right" role="menu">
             <li>' . $detail_link . '</li>'
            
            .(($this->Owner || $this->Admin) ? '<li>'.$payments_link.'</li>' : ($this->GP['sales-payments'] ? '<li>'.$payments_link.'</li>' : '')).
            (($this->Owner || $this->Admin) ? '<li>'.$add_payment_link.'</li>' : ($this->GP['sales-payments'] ? '<li>'.$add_payment_link.'</li>' : '')).
            (($this->Owner || $this->Admin) ? '<li>'.$add_delivery_link.'</li>' : ($this->GP['sales-add_delivery'] ? '<li>'.$add_delivery_link.'</li>' : '')).
            (($this->Owner || $this->Admin) ? '<li class="edit">'.$edit_link.'</li>' : ($this->GP['sales-edit'] ? '<li class="edit">'.$edit_link.'</li>' : '')).
            (($this->Owner || $this->Admin) ? '<li>'.$email_link.'</li>' : ($this->GP['sales-email'] ? '<li>'.$email_link.'</li>' : '')).
			(($this->Owner || $this->Admin) ? '<li>'.$return_link.'</li>' : ($this->GP['sales-return_sales'] ? '<li>'.$return_link.'</li>' : '')).
			(($this->Owner || $this->Admin) ? '<li>'.$delete_link.'</li>' : ($this->GP['sales-delete'] ? '<li>'.$delete_link.'</li>' : '')).
            
        '</ul>
		</div></div>';
        //$action = '<div class="text-center">' . $detail_link . ' ' . $edit_link . ' ' . $email_link . ' ' . $delete_link . '</div>';

        $this->load->library('datatables');
		
		$exchange_rate = $this->pos_model->getExchange_rate();
		
        if ($warehouse_id) {		
			$this->datatables
                ->select($this->db->dbprefix('sales').".id as id, 
				".$this->db->dbprefix('sales').".date,
				".$this->db->dbprefix('payments').".date as pdate,
				".$this->db->dbprefix('sales').".reference_no, biller.company, ".$this->db->dbprefix('sales').".customer, 
										sale_status, ".$this->db->dbprefix('sales').".grand_total,  
										COALESCE((SELECT SUM(erp_return_sales.paid) FROM erp_return_sales WHERE erp_return_sales.sale_id = erp_sales.id), 0) as return_sale,
										COALESCE((SELECT SUM(IF((erp_payments.paid_by != 'deposit' AND ISNULL(erp_payments.return_id)), erp_payments.amount, IF(NOT ISNULL(erp_payments.return_id), ((-1)*erp_payments.amount), 0))) FROM erp_payments WHERE erp_payments.sale_id = erp_sales.id),0) as paid, 
										COALESCE((SELECT SUM(IF(erp_payments.paid_by = 'deposit', erp_payments.amount, 0)) FROM erp_payments WHERE erp_payments.sale_id = erp_sales.id), 0) as deposit,
										COALESCE((SELECT SUM(erp_payments.discount) FROM erp_payments WHERE erp_payments.sale_id = erp_sales.id), 0) as discount, 
										(" . $this->db->dbprefix('sales') . ".grand_total - COALESCE((SELECT SUM(erp_return_sales.paid) FROM erp_return_sales WHERE erp_return_sales.sale_id = erp_sales.id), 0) - COALESCE((SELECT SUM(IF((erp_payments.paid_by != 'deposit' AND ISNULL(erp_payments.return_id)), erp_payments.amount, IF(NOT ISNULL(erp_payments.return_id), ((-1)*erp_payments.amount), 0))) FROM erp_payments WHERE erp_payments.sale_id = erp_sales.id),0) - COALESCE((SELECT SUM(IF(erp_payments.paid_by = 'deposit', erp_payments.amount, 0)) FROM erp_payments WHERE erp_payments.sale_id = erp_sales.id), 0) - COALESCE((SELECT SUM(erp_payments.discount) FROM erp_payments WHERE erp_payments.sale_id = erp_sales.id), 0)) as balance, 
										sales.payment_status")
                ->from('sales')
                ->join('companies', 'companies.id = sales.customer_id', 'left')
				->join('companies as erp_biller', 'biller.id = sales.biller_id', 'inner')
				->join('payments', 'payments.sale_id=sales.id', 'left')
                ->where_in('sales.warehouse_id', $warehouse_id)
                ->where_in('erp_sales.biller_id', JSON_decode($this->session->userdata('biller_id')))
                ->group_by('sales.id');
        } else {
            $this->datatables
                ->select($this->db->dbprefix('sales').".id as id, 
				".$this->db->dbprefix('sales').".date,
				".$this->db->dbprefix('payments').".date as pdate,
				".$this->db->dbprefix('sales').".reference_no, biller.company, ".$this->db->dbprefix('sales').".customer, 
										sale_status, ".$this->db->dbprefix('sales').".grand_total,  
										COALESCE((SELECT SUM(erp_return_sales.paid) FROM erp_return_sales WHERE erp_return_sales.sale_id = erp_sales.id), 0) as return_sale,
										COALESCE((SELECT SUM(IF((erp_payments.paid_by != 'deposit' AND ISNULL(erp_payments.return_id)), erp_payments.amount, IF(NOT ISNULL(erp_payments.return_id), ((-1)*erp_payments.amount), 0))) FROM erp_payments WHERE erp_payments.sale_id = erp_sales.id),0) as paid, 
										COALESCE((SELECT SUM(IF(erp_payments.paid_by = 'deposit', erp_payments.amount, 0)) FROM erp_payments WHERE erp_payments.sale_id = erp_sales.id), 0) as deposit,
										COALESCE((SELECT SUM(erp_payments.discount) FROM erp_payments WHERE erp_payments.sale_id = erp_sales.id), 0) as discount, 
										(" . $this->db->dbprefix('sales') . ".grand_total - COALESCE((SELECT SUM(erp_return_sales.paid) FROM erp_return_sales WHERE erp_return_sales.sale_id = erp_sales.id), 0) - COALESCE((SELECT SUM(IF((erp_payments.paid_by != 'deposit' AND ISNULL(erp_payments.return_id)), erp_payments.amount, IF(NOT ISNULL(erp_payments.return_id), ((-1)*erp_payments.amount), 0))) FROM erp_payments WHERE erp_payments.sale_id = erp_sales.id),0) - COALESCE((SELECT SUM(IF(erp_payments.paid_by = 'deposit', erp_payments.amount, 0)) FROM erp_payments WHERE erp_payments.sale_id = erp_sales.id), 0) - COALESCE((SELECT SUM(erp_payments.discount) FROM erp_payments WHERE erp_payments.sale_id = erp_sales.id), 0)) as balance, 
										sales.payment_status")
                ->from('sales')
				->join('payments', 'payments.sale_id=sales.id', 'left')
				->join('erp_return_sales', 'erp_return_sales.sale_id = sales.id', 'left')
                ->join('companies', 'companies.id=sales.customer_id', 'left')
				->join('companies as erp_biller', 'biller.id = sales.biller_id', 'inner')
                ->group_by('sales.id');
        }
		
		if ($product_id) {
			$this->datatables->join('sale_items', 'sale_items.sale_id = sales.id', 'left');
			$this->datatables->where('sale_items.product_id', $product_id);
		}
		
        $this->datatables->where('pos', 1);
		
        if (!$this->Customer && !$this->Supplier && !$this->Owner && !$this->Admin && !$this->session->userdata('view_right')) {
            $this->datatables->where('sales.created_by', $this->session->userdata('user_id'));
        } elseif ($this->Customer) {
            $this->datatables->where('customer_id', $this->session->userdata('user_id'));
        }
		
		if ($user_query) {
			$this->datatables->where('sales.created_by', $user_query);
		}
		
		if ($reference_no) {
			$this->datatables->where('sales.reference_no', $reference_no);
		}
		
		if ($biller) {
			$this->datatables->where('sales.biller_id', $biller);
		}
		if ($customer) {
			$this->datatables->where('sales.customer_id', $customer);
		}
		
		if($saleman){
			$this->datatables->where('sales.saleman_by', $saleman);
		}
		
		if ($warehouse) {
			$this->datatables->where('sales.warehouse_id', $warehouse);
		}

		if ($start_date) {
			$this->datatables->where($this->db->dbprefix('sales').'.date BETWEEN "' . $start_date . ' 00:00:00" and "' . $end_date . ' 23:59:00"');
		}
        $this->datatables->add_column("Actions", $action, "id, psuspend")->unset_column('psuspend');
        echo $this->datatables->generate();
    }
	
	function getPos($warehouse_id = NULL)
    {
        $this->erp->checkPermissions('index');

        if ((! $this->Owner || ! $this->Admin) && ! $warehouse_id) {
            $user = $this->site->getUser();
            $warehouse_id = $user->warehouse_id;
        }
        $detail_link        = anchor('pos/view/$1', '<i class="fa fa-file-text-o"></i> ' . lang('view_receipt'));
        $payments_link      = anchor('sales/payments/$1', '<i class="fa fa-money"></i> ' . lang('view_payments'), 'data-toggle="modal" data-target="#myModal"');
        $add_payment_link   = anchor('pos/add_payment/$1', '<i class="fa fa-money"></i> ' . lang('add_payment'), 'data-toggle="modal" data-target="#myModal"');
        $add_delivery_link  = anchor('pos/delivery_added/$1', '<i class="fa fa-truck"></i> ' . lang('add_delivery'), 'data-toggle="modal" data-target="#myModal"');
        $email_link         = anchor('#', '<i class="fa fa-envelope"></i> ' . lang('email_sale'), 'class="email_receipt" data-id="$1" data-email-address="$2"');
        $edit_link          = anchor('pos/edit/$1', '<i class="fa fa-edit"></i> ' . lang('edit_sale'), 'class="sledit"');
        $return_link        = anchor('sales/return_sale/$1', '<i class="fa fa-angle-double-left"></i> ' . lang('return_sale'));
        $delete_link        = "<a href='#' class='po' title='<b>" . lang("delete_sale") . "</b>' data-content=\"<p>"
            . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('sales/delete/$1') . "'>"
            . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i> "
            . lang('delete_sale') . "</a>";
        $action = '<div class="text-center"><div class="btn-group text-left">'
            . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
            . lang('actions') . ' <span class="caret"></span></button>
        <ul class="dropdown-menu pull-right" role="menu">
            <li>' . $detail_link . '</li>
            <li>' . $payments_link . '</li>
            <li>' . $add_payment_link . '</li>
            <li class="add_delivery">' . $add_delivery_link . '</li>
            <li>' . $email_link . '</li>
            <li>' . $return_link . '</li>
            <li>' . $delete_link . '</li>
        </ul></div></div>';
        //$action = '<div class="text-center">' . $detail_link . ' ' . $edit_link . ' ' . $email_link . ' ' . $delete_link . '</div>';

        $this->load->library('datatables');
		
		$exchange_rate = $this->pos_model->getExchange_rate();
		
        if ($warehouse_id) {
            $this->datatables
                ->select($this->db->dbprefix('sales').".id as id, date, reference_no, biller, customer, grand_total, paid, (grand_total - paid) AS balance, payment_status,sale_status,(IF(ISNULL(".$this->db->dbprefix("sales").".delivery_status), CONCAT(erp_sales.id, '___', 'delivery'),
    					CONCAT(erp_sales.id, '___', ".$this->db->dbprefix("sales").".delivery_status))) as delivery_status")
                ->from('sales')
                ->join('companies', 'companies.id=sales.customer_id', 'left')
                ->where('warehouse_id', $warehouse_id)
                ->group_by('sales.id');
        } else {
            $this->datatables
                ->select($this->db->dbprefix('sales').".id as id, date, reference_no, biller, customer, grand_total, paid, (grand_total - paid) AS balance, payment_status,sale_status,(IF(ISNULL(".$this->db->dbprefix("sales").".delivery_status), CONCAT(erp_sales.id, '___', 'delivery'),
    					CONCAT(erp_sales.id, '___', ".$this->db->dbprefix("sales").".delivery_status))) as delivery_status")
                ->from('sales')
                ->join('companies', 'companies.id=sales.customer_id', 'left')
                ->group_by('sales.id');
        }
        $this->datatables->where('pos', 1);
        if (!$this->Customer && !$this->Supplier && !$this->Owner && !$this->Admin) {
            $this->datatables->where('created_by', $this->session->userdata('user_id'));
        } elseif ($this->Customer) {
            $this->datatables->where('customer_id', $this->session->userdata('user_id'));
        }
        $this->datatables->add_column("Actions", $action, "id, cemail")->unset_column('cemail');
        echo $this->datatables->generate();
    }
	

	function getPos_old($warehouse_id = NULL)
    {
        $this->erp->checkPermissions('index');

        if ((! $this->Owner || ! $this->Admin) && ! $warehouse_id) {
            $user               = $this->site->getUser();
            $warehouse_id       = $user->warehouse_id;
        }
        $detail_link            = anchor('pos/view/$1', '<i class="fa fa-file-text-o"></i> ' . lang('view_receipt'));
        $payments_link          = anchor('sales/payments/$1', '<i class="fa fa-money"></i> ' . lang('view_payments'), 'data-toggle="modal" data-target="#myModal"');
        $add_payment_link       = anchor('pos/add_payment/$1', '<i class="fa fa-money"></i> ' . lang('add_payment'), 'data-toggle="modal" data-target="#myModal"');
        $add_delivery_link      = anchor('sales/add_delivery/$1', '<i class="fa fa-truck"></i> ' . lang('add_delivery'), 'data-toggle="modal" data-target="#myModal"');
        $email_link             = anchor('#', '<i class="fa fa-envelope"></i> ' . lang('email_sale'), 'class="email_receipt" data-id="$1" data-email-address="$2"');
        $edit_link              = anchor('pos/edit/$1', '<i class="fa fa-edit"></i> ' . lang('edit_sale'), 'class="sledit"');
        $return_link            = anchor('sales/return_sale/$1', '<i class="fa fa-angle-double-left"></i> ' . lang('return_sale'));
        $delete_link            = "<a href='#' class='po' title='<b>" . lang("delete_sale") . "</b>' data-content=\"<p>"
            . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('sales/delete/$1') . "'>"
            . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i> "
            . lang('delete_sale') . "</a>";
        $action = '<div class="text-center"><div class="btn-group text-left">'
            . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
            . lang('actions') . ' <span class="caret"></span></button>
        <ul class="dropdown-menu pull-right" role="menu">
            <li>' . $detail_link . '</li>
            <li>' . $payments_link . '</li>
            <li>' . $add_payment_link . '</li>
            <li class="add_delivery">' . $add_delivery_link . '</li>
            <li>' . $email_link . '</li>
            <li>' . $return_link . '</li>
            <li>' . $delete_link . '</li>
        </ul></div></div>';
        //$action = '<div class="text-center">' . $detail_link . ' ' . $edit_link . ' ' . $email_link . ' ' . $delete_link . '</div>';

        $this->load->library('datatables');
		
		$exchange_rate = $this->pos_model->getExchange_rate();
		
        if ($warehouse_id) {
            $this->datatables
                ->select($this->db->dbprefix('sales').".id as id, date, reference_no, biller, customer, grand_total, paid, (grand_total - paid) AS balance, payment_status,sale_status,(IF(ISNULL(".$this->db->dbprefix("sales").".delivery_status), CONCAT(erp_sales.id, '___', 'delivery'),
    					CONCAT(erp_sales.id, '___', ".$this->db->dbprefix("sales").".delivery_status))) as delivery_status")
                ->from('sales')
                ->join('companies', 'companies.id=sales.customer_id', 'left')
                ->where('warehouse_id', $warehouse_id)
                ->group_by('sales.id');
        } else {
            $this->datatables
                ->select($this->db->dbprefix('sales').".id as id, date, reference_no, biller, customer, grand_total, paid, (grand_total - paid) AS balance, payment_status,sale_status,(IF(ISNULL(".$this->db->dbprefix("sales").".delivery_status), CONCAT(erp_sales.id, '___', 'delivery'),
    					CONCAT(erp_sales.id, '___', ".$this->db->dbprefix("sales").".delivery_status))) as delivery_status")
                ->from('sales')
                ->join('companies', 'companies.id=sales.customer_id', 'left')
                ->group_by('sales.id');
        }
        $this->datatables->where('pos', 1);
        if (!$this->Customer && !$this->Supplier && !$this->Owner && !$this->Admin) {
            $this->datatables->where('created_by', $this->session->userdata('user_id'));
        } elseif ($this->Customer) {
            $this->datatables->where('customer_id', $this->session->userdata('user_id'));
        }
        $this->datatables->add_column("Actions", $action, "id, cemail")->unset_column('cemail');
        echo $this->datatables->generate();
    }
	
    /* ---------------------------------------------------------------------------------------------------- */

    function index($sid = null, $sale_order_id=null, $combine_table = null)
    {
		$this->erp->checkPermissions('index');
        if (!$this->pos_settings->default_biller || !$this->pos_settings->default_customer || !$this->pos_settings->default_category) {
            $this->session->set_flashdata('warning', lang('please_update_settings'));
            redirect('pos/settings');
        }
		if ($register = $this->pos_model->registerData($this->session->userdata('user_id'))){
            $register_data = array('register_id' => $register->id, 'cash_in_hand' => $register->cash_in_hand, 'register_open_time' => $register->date);
            $this->session->set_userdata($register_data);
        } else {
            $this->session->set_flashdata('error', lang('register_not_open'));
            redirect('pos/open_register');
        }

        $this->data['sid'] 	= $this->input->get('suspend_id') ? $this->input->get('suspend_id') : $sid;
        $did 				= $this->input->post('delete_id') ? $this->input->post('delete_id') : NULL;        
        $suspend 			= $this->input->post('suspend') ? TRUE : FALSE;
        $count 				= $this->input->post('count') ? $this->input->post('count') : NULL;

        //validate form input
        $this->form_validation->set_rules('customer', $this->lang->line("customer"), 'trim|required');
        $this->form_validation->set_rules('warehouse', $this->lang->line("warehouse"), 'required');
		$this->form_validation->set_rules('date', $this->lang->line("date"));
        $this->form_validation->set_rules('biller', $this->lang->line("biller"), 'required');
        //$this->form_validation->set_rules('reference_nob', lang("reference_no"), 'required|is_unique[sales.reference_no]');
		
        if ($this->form_validation->run() == true){

            $quantity 			= "quantity";
            $product 			= "product";
            $unit_cost 			= "unit_cost";
            $tax_rate 			= "tax_rate";
            $recieve_usd        = $this->input->post('amount[0]');
            $recieve_real       = $this->input->post('other_cur_paid[0]');
            $date 				= $this->input->post('date');
            $warehouse_id 		= $this->input->post('warehouse');
            $customer_id 		= $this->input->post('customer');
            $biller_id 			= $this->input->post('biller');
			$saleman_id 		= $this->input->post('saleman_1');
            $delivery_by 		= $this->input->post('delivery_by_1');
            $total_items 		= $this->input->post('total_items');
            $sale_status        = $this->input->post('sale_status');
            $bank_account = $this->input->post('bank_account');
            $payment_status 	= 'due';
            $payment_term 		= 0;
            $due_date 			= date('Y-m-d', strtotime('+' . $payment_term . ' days'));
            $shipping 			= $this->input->post('shipping') ? $this->input->post('shipping') : 0;
            $customer_details 	= $this->site->getCompanyByID($customer_id);
            $customer 			= $customer_details->name ? $customer_details->name : $customer_details->company;
            $biller_details 	= $this->site->getCompanyByID($biller_id);
            $biller 			= $biller_details->company != '-' ? $biller_details->company : $biller_details->name;
            $note 				= $this->erp->clear_tags($this->input->post('pos_note'));
			$suspend_room 		= $this->input->post('suspend_room');
			$combine_table_id 	= $this->input->post('combine_table_id');
			$reference 			= $this->input->post('reference_nob');
            $document = $this->input->post('document_1');

            if ($this->session->userdata('biller_id')) {
                $default_biller = JSON_decode($this->session->userdata('biller_id'));
            } else {
                $default_biller = $this->Settings->default_biller;
            }

            isCloseDate(date('Y-m-d', strtotime($date)));
			
            $total 				= 0;
            $product_tax 		= 0;
            $order_tax 			= 0;
            $product_discount 	= 0;
            $order_discount 	= 0;
            $percentage 		= '%';
            $g_total_txt1 		= 0;
			$total_discount 	= 0;
			$totalcost 			= 0;
            $amout_paid         = 0;
            
            $i = isset($_POST['product_code']) ? sizeof($_POST['product_code']) : 0;
            for ($r = 0; $r < $i; $r++){
                $item_id   		= $_POST['product_id'][$r];
                $digital_id   	= $_POST['digital_id'][$r];
                $item_type 		= $_POST['product_type'][$r];
                $item_code 		= $_POST['product_code'][$r];
				$item_note 		= $_POST['product_note'][$r];
                $item_name 		= $_POST['product_name'][$r];
				$item_cost 		= $_POST['item_cost'][$r];
                $item_option 	= isset($_POST['product_option'][$r]) && $_POST['product_option'][$r] != 'false' ? $_POST['product_option'][$r] : NULL;
			    $expire_date_id = isset($_POST['expdate'][$r]) && $_POST['expdate'][$r] != 'false' ? $_POST['expdate'][$r] : null;
				$expdate 		= isset($this->sales_model->getPurchaseItemExDateByID($expire_date_id)->expiry);
                $real_unit_price = $this->erp->formatDecimal($_POST['real_unit_price'][$r]);
                $unit_price 	= $this->erp->formatDecimal($_POST['unit_price'][$r]);
                $item_quantity 	= $_POST['quantity'][$r]; 
                $item_serial 	= isset($_POST['serial'][$r]) ? $_POST['serial'][$r] : '';
                $item_tax_rate 	= isset($_POST['product_tax'][$r]) ? $_POST['product_tax'][$r] : NULL;
                $item_discount 	= isset($_POST['product_discount'][$r]) ? $_POST['product_discount'][$r] : NULL;
				$g_total_txt 	= $_POST['grand_total'][$r];
				$item_price_id 	= $_POST['price_id'][$r];
				
                if (isset($item_code) && isset($real_unit_price) && isset($unit_price) && isset($item_quantity)) {
                    $product_details 	= $item_type != 'manual' ? $this->pos_model->getProductByCode($item_code) : NULL;
                    $unit_price 		= $real_unit_price;
                    $pr_discount 		= 0;
					$price_tax_cal      = $unit_price;
					
					if ($this->Settings->tax_calculate) {
						if (isset($item_tax_rate) && $item_tax_rate != 0) {
							$pr_tax = $item_tax_rate;
							$tax_details = $this->site->getTaxRateByID($pr_tax);
							if ($tax_details->type == 1 && $tax_details->rate != 0) {
								if ($product_details && $product_details->tax_method == 1) {
									$price_tax_cal = $unit_price;
								} else {
									$price_tax_cal = ($unit_price * 100) / (100 + $tax_details->rate);
								}
							}
						}
					}
                    if (isset($item_discount)) {
                        $discount   = $item_discount;
                        $dpos       = strpos($discount, $percentage);
                        if ($dpos !== false) {
                            $pds = explode("%", $discount);
                            $pr_discount = ((($this->erp->formatDecimal($price_tax_cal, 8)) * (Float) ($pds[0])) / 100);
                        } else {
                            $pr_discount = $this->erp->formatDecimal($discount/$item_quantity, 8);
                        }
                    }
                    
					$unitPrice 			= $unit_price;
                    $item_net_price 	= $unit_price; 
                    $pr_item_discount 	= $this->erp->formatDecimal($pr_discount * $item_quantity); 
                    $product_discount 	+= $pr_item_discount; 
                    $pr_tax = 0; $pr_item_tax = 0; $item_tax = 0; $tax = "";

                    if (isset($item_tax_rate) && $item_tax_rate != 0) {
                        $pr_tax = $item_tax_rate;
                        $tax_details = $this->site->getTaxRateByID($pr_tax);
                        if ($tax_details->type == 1 && $tax_details->rate != 0) {
                            if ($product_details && $product_details->tax_method == 1) {
                                $item_tax 		= $this->erp->formatDecimal((($unit_price - $pr_discount) * $tax_details->rate) / 100, 4);
                                $tax 			= $tax_details->rate . "%";
								$item_net_price = $unit_price;
                            } else {
                                $item_tax 		= ((($unit_price) * $tax_details->rate) / (100 + $tax_details->rate));
                                $tax 			= $tax_details->rate . "%";
                                $item_net_price = $unit_price - $item_tax;
                            }
                        } elseif ($tax_details->type == 2) {
                            if ($product_details && $product_details->tax_method == 1) {
                                $item_tax 		= ((($unit_price - $pr_discount) * $tax_details->rate) / 100);
                                $tax 			= $tax_details->rate . "%";
								$item_net_price = $unit_price;
                            } else {
                                $item_tax 		= ((($unit_price) * $tax_details->rate) / (100 + $tax_details->rate));
                                $tax 			= $tax_details->rate . "%";
                                $item_net_price = $unit_price - $item_tax;
                            }
							$item_tax 	        = $this->erp->formatDecimal($tax_details->rate);
                            $tax 		        = $tax_details->rate;
                        }
                        $pr_item_tax 	        = $this->erp->formatDecimal($item_tax * $item_quantity, 4);
                    }
					
					$product_tax 	            += $pr_item_tax;
					$unit_price 	            = $this->erp->formatDecimal($unit_price - $pr_discount, 8);

                    $quantity_balance   = $item_quantity;
					if($item_option != 0) {
						$row 			    = $this->purchases_model->getVariantQtyById($item_option);
						$item_cost   	    = $item_cost * $row->qty_unit;
						$quantity_balance   = $item_quantity * $row->qty_unit;
					}
					
					$totalcost	+= $item_cost;
					
					if( $product_details->tax_method == 0){
						$subtotal = (($unit_price * $item_quantity));
					}else{
						$subtotal = (($unit_price * $item_quantity) + $pr_item_tax);
					}
					
                    $products[] = array(
                        'product_id' 		=> $item_id,
                        'digital_id' 		=> $digital_id,
                        'product_code' 		=> $item_code,
                        'product_name' 		=> $item_name,
                        'product_type' 		=> $item_type,
                        'option_id' 		=> $item_option,
                        'net_unit_price' 	=> $item_net_price,
                        'unit_price' 		=> $this->erp->formatDecimal($unitPrice),
                        'quantity' 			=> $item_quantity,
                        'quantity_balance' 	=> $quantity_balance,
                        'warehouse_id' 		=> $warehouse_id,
                        'item_tax' 			=> $pr_item_tax,
                        'tax_rate_id' 		=> $pr_tax,
                        'tax' 				=> $tax,
						//'unit_cost' 		=> $item_cost,
                        'discount' 			=> $item_discount,
                        'item_discount' 	=> $pr_item_discount,
                        'subtotal' 			=> $this->erp->formatDecimal($subtotal),
                        'serial_no' 		=> $item_serial,
                        'real_unit_price' 	=> $real_unit_price,
						'product_noted' 	=> $item_note,
						'expiry' 			=> $expdate,
						'expiry_id' 		=> $expire_date_id,
						'price_id' 		=> $item_price_id
                    );					
                    $total += $subtotal;
					$g_total_txt1 += $subtotal;
					$total_discount+=$product_discount;
                }
				
            }
            
            if (empty($products)) {
                //$this->form_validation->set_rules('product', lang("order_items"), 'required');
            } else {
                krsort($products);
            }

            if ($this->input->post('order_discount')) {
                $order_discount_id = $this->input->post('order_discount');
                $opos = strpos($order_discount_id, $percentage);
                if ($opos !== false) {
                    $ods = explode("%", $order_discount_id);
                    $order_discount = $this->erp->formatDecimal((($total) * (Float)($ods[0])) / 100);
				 
                } else {
                    $order_discount = $this->erp->formatDecimal(($total * $order_discount_id)/100);
					
                }
            } else {
                $order_discount_id = NULL;
            }
            
            $total_discount = $this->erp->formatDecimal($order_discount + $product_discount);
            
            if ($this->Settings->tax2) {
                $order_tax_id = $this->input->post('order_tax');
                if ($order_tax_details = $this->site->getTaxRateByID($order_tax_id)) {
                    if ($order_tax_details->type == 2) {
                        $order_tax = $this->erp->formatDecimal($order_tax_details->rate);
                    } elseif ($order_tax_details->type == 1) {
                        $order_tax = $this->erp->formatDecimal(((($total + $shipping - $order_discount) * $order_tax_details->rate) / 100), 4);
                    }
                }
            } else {
                $order_tax_id = null;
            }
            
			$total_tax 		= $this->erp->formatDecimal(($product_tax + $order_tax), 4);
			
			
			$grand_total 	= $this->erp->formatDecimal((($total - $order_discount) + ($order_tax + $this->erp->formatDecimal($shipping))), 4);
			$cur_rate 		= $this->pos_model->getExchange_rate();
			$other_cur_paid = 0;
			$pos_balance 	= 0;
			$paidd 			= 0;
			$amt_p 			= 0;
			$p 				= isset($_POST['amount']) ? sizeof($_POST['amount']) : 0;
			$p_cur 			= isset($_POST['other_cur_paid']) ? sizeof($_POST['other_cur_paid']) : 0;
			
			for ($r = 0; $r < $p; $r++) {
				$amt_p 			+= $_POST['amount'][$r];
				$other_cur_paid += ($_POST['other_cur_paid'][$r]/$cur_rate->rate);
				$paidd 			= $amt_p + $other_cur_paid;
				if ($paidd >= $grand_total) {
					$paidd 		= $grand_total;
				}
			}
			
			$suppend_name 	= $this->pos_model->get_suppendName($did);

            $i				= 1;
            $query			= 0; 
			$date 			= $this->input->post('date');
            $dates 			= date('Y-m-d',strtotime($date));
            
            $this->db
                 ->select("DATE_FORMAT(date,'%d') as date, biller_id, queue")
                 ->where("DATE_FORMAT(date,'%Y-%m-%d')",$dates)
                 ->where('biller_id', $biller_id)
                 ->order_by('id DESC'); 
            $queues = $this->db->get('erp_sales')->row();
            $cdate 	= date('d');

            if ($queues->date == $cdate || $queues->biller_id == $biller_id) {
                $query = $queues->queue + $i;
            } else {
                $query = $i;
            }
             			
            $data = array(
                'date'              => $date,
                'reference_no'      => $reference,
                'customer_id'       => $customer_id,
                'customer'          => $customer,
                'biller_id'         => $biller_id,
                'biller'            => $biller,
                'warehouse_id'      => $warehouse_id,
                'note'              => $note,
                'total'             => $this->erp->formatDecimal($total),
                'total_cost'        => 0,
                'product_discount'  => $product_discount,
                'order_discount_id' => $order_discount_id,
                'order_discount'    => $order_discount,
                'total_discount'    => $total_discount,
                'product_tax'       => $this->erp->formatDecimal($product_tax),
                'order_tax_id'      => $order_tax_id,
                'order_tax'         => $order_tax,
                'total_tax'         => $total_tax,
                'shipping'          => $this->erp->formatDecimal($shipping),
                'grand_total'       => $this->erp->formatDecimal($grand_total),
                'total_items'       => $total_items,
                'sale_status'       => $sale_status,
                'payment_status'    => $payment_status,
                'recieve_usd'       => $this->erp->formatDecimal($recieve_usd),
                'recieve_real'      => $this->erp->formatDecimal($recieve_real),
				'delivery_by'       => $delivery_by,
                'payment_term'      => $payment_term,
                'pos'               => 1,
                'other_cur_paid'    => $other_cur_paid ? $other_cur_paid:0,
                'paid'              => $paidd ? $paidd:0,
                'created_by'        => $this->session->userdata('user_id'),
				'suspend_note'      => $this->input->post('suppend_name') ? $this->input->post('suppend_name') : $suppend_name->suspend_name,
				'start_date'        => isset($suppend_name->date) ? $suppend_name->date : '',
				'other_cur_paid_rate' => $cur_rate->rate,
				'saleman_by'        => $saleman_id,
				'type'              => $this->input->post('sale_type'),
				'type_id'           => $this->input->post('sale_type_id'),
				'queue'             => $query
            );
            
			if($_POST['paid_by'][0] == 'depreciation'){
				$no = sizeof($_POST['no']);
				$period = 1;
				for($m = 0; $m < $no; $m++){
					$dateline = $this->erp->fld($_POST['dateline'][$m]);
					$loans[] = array(
						'period' 	=> $period,
						'sale_id' 	=> '',
						'interest' 	=> $_POST['interest'][$m],
						'principle' => $_POST['principle'][$m],
						'payment' 	=> $_POST['payment_amt'][$m],
						'balance' 	=> $_POST['balance'][$m],
						'type' 		=> $_POST['loan_type'],
						'rated' 	=> $_POST['loan_rate'],
						'note' 		=> $_POST['note1'][$m],
						'dateline' 	=> $dateline
					);
					$period++;
				}
				$data['term'] = $no;
			}else{
				$loans = array();
			}
			
			$amount 	= 0;
			$kh_paid 	= false;
            $pos_b      = 0;
            if (!$suspend) {
                $p 		= isset($_POST['amount']) ? sizeof($_POST['amount']) : 0;
				$p_cur 	= isset($_POST['other_cur_paid']) ? sizeof($_POST['other_cur_paid']) : 0;

				$g_total= $this->erp->formatDecimal($grand_total);
                for ($r = 0; $r < $p; $r++) {
					$pos_b      += $this->erp->formatDecimal(($_POST['amount'][$r] + ($_POST['other_cur_paid'][$r]/$cur_rate->rate)));
					$paid       = ($_POST['amount'][$r] + ($_POST['other_cur_paid'][$r]/$cur_rate->rate));
                    $pos_balance= $g_total - $this->erp->formatDecimal($pos_b);
                    if ($pos_b < $g_total) {
                        $amount = $paid;
                    } else {
                        $amount = $g_total - ($pos_b - $paid);
                    }

                    $payment[] = array(
                        'biller_id'				=> $biller_id,
                        'date' 					=> $date,
                        'reference_no'          => (($_POST['paid_by'][$r] == 'deposit' || $_POST['paid_by'][$r] == 'depreciation') ? $reference : $this->site->getReference('sp', $this->session->userdata('biller_id') ? $default_biller[0] : $default_biller)),
                        'amount' 				=> $this->erp->formatDecimal($amount),
                        'paid_by' 				=> $_POST['paid_by'][$r],
                        'cheque_no' 			=> $_POST['cheque_no'][$r],
                        'cc_no' 				=> ($_POST['paid_by'][$r] == 'gift_card' ? $_POST['paying_gift_card_no'][$r] : $_POST['cc_no'][$r]),
                        'cc_holder' 			=> $_POST['cc_holder'][$r],
                        'cc_month' 				=> $_POST['cc_month'][$r],
                        'cc_year' 				=> $_POST['cc_year'][$r],
                        'cc_type' 				=> $_POST['cc_type'][$r],
                        'cc_cvv2' 				=> $_POST['cc_cvv2'][$r],
                        'created_by' 			=> $this->session->userdata('user_id'),
                        'type' 					=> 'received',
                        'note' 					=> $_POST['payment_note'][$r],
                        'pos_paid' 				=> $_POST['amount'][$r],
                        'pos_balance' 			=> ($pos_b - $this->erp->formatDecimal($grand_total)),
                        'pos_paid_other' 		=> $_POST['other_cur_paid'][$r],
                        'pos_paid_other_rate' 	=> $cur_rate->rate,
                        'bank_account' 			=> $bank_account[$r]
                    );

                    /*if (isset($_POST['amount'][$r]) && !empty($_POST['amount'][$r]) && isset($_POST['paid_by'][$r]) && !empty($_POST['paid_by'][$r])  ) {
						if(strpos($_POST['amount'][$r], '-') !== false){
							$payment[] = array(
								'biller_id'				=> $biller_id,
								'date' 					=> $date,
                                'reference_no' => (($_POST['paid_by'][$r] == 'deposit' || $_POST['paid_by'][$r] == 'depreciation') ? $reference : $this->site->getReference('sp', $this->session->userdata('biller_id') ? $default_biller[0] : $default_biller)),
								'amount' 				=> $this->erp->formatDecimal($amount),
								'paid_by' 				=> $_POST['paid_by'][$r],
                                'cheque_no' 			=> $_POST['cheque_no'][$r],
								'cc_no' 				=> ($_POST['paid_by'][$r] == 'gift_card' ? $_POST['paying_gift_card_no'][$r] : $_POST['cc_no'][$r]),
								'cc_holder' 			=> $_POST['cc_holder'][$r],
								'cc_month' 				=> $_POST['cc_month'][$r],
								'cc_year' 				=> $_POST['cc_year'][$r],
								'cc_type' 				=> $_POST['cc_type'][$r],
								'created_by' 			=> $this->session->userdata('user_id'),
								'type' 					=> 'returned',
								'note' 					=> $_POST['payment_note'][$r],
								'pos_paid' 				=> $_POST['amount'][$r],
								'pos_balance' 			=> ($pos_b - $this->erp->formatDecimal($grand_total)),
								'pos_paid_other' 		=> $_POST['other_cur_paid'][$r],
								'pos_paid_other_rate' 	=> $cur_rate->rate,
								'bank_account' 			=> $bank_account[$r]
							); 
						} else {
							
							$payment[] = array(
								'biller_id'				=> $biller_id,
								'date' 					=> $date,
                                'reference_no' => (($_POST['paid_by'][$r] == 'deposit' || $_POST['paid_by'][$r] == 'depreciation') ? $reference : $this->site->getReference('sp', $this->session->userdata('biller_id') ? $default_biller[0] : $default_biller)),
								'amount' 				=> $this->erp->formatDecimal($amount),
								'paid_by' 				=> $_POST['paid_by'][$r],
								'cheque_no' 			=> $_POST['cheque_no'][$r],
								'cc_no' 				=> ($_POST['paid_by'][$r] == 'gift_card' ? $_POST['paying_gift_card_no'][$r] : $_POST['cc_no'][$r]),
								'cc_holder' 			=> $_POST['cc_holder'][$r],
								'cc_month' 				=> $_POST['cc_month'][$r],
								'cc_year' 				=> $_POST['cc_year'][$r],
								'cc_type' 				=> $_POST['cc_type'][$r],
								'cc_cvv2' 				=> $_POST['cc_cvv2'][$r],
								'created_by' 			=> $this->session->userdata('user_id'),
								'type' 					=> 'received',
								'note' 					=> $_POST['payment_note'][$r],
								'pos_paid' 				=> $_POST['amount'][$r],
								'pos_balance' 			=> ($pos_b - $this->erp->formatDecimal($grand_total)),
								'pos_paid_other' 		=> $_POST['other_cur_paid'][$r],
								'pos_paid_other_rate' 	=> $cur_rate->rate,
								'bank_account' 			=> $bank_account[$r]
							);
							
						}
						
                        $pp[] = $paidd;
                    }*/
                }

				/*if(isset($p_cur) && empty($_POST['amount'][0])){

					$kh_paid = true;
					for ($j = 0; $j < $p_cur; $j++) {
                        $pos_balance = number_format($pos_b - $this->erp->formatDecimal($grand_total), 6);
                        //$pos_b += ($_POST['amount'][$j] + ($_POST['other_cur_paid'][$j]/$cur_rate->rate));
                        $pos_b += ($_POST['amount'][$j] - ($_POST['other_cur_paid'][$j] / $cur_rate->rate));
						$paidi  = ($_POST['amount'][$j] + ($_POST['other_cur_paid'][$j]/$cur_rate->rate));

                        if (isset($_POST['other_cur_paid'][$j]) && !empty($_POST['other_cur_paid'][$j]) && isset($_POST['paid_by'][$j]) && !empty($_POST['paid_by'][$j])) {
							$payment[] = array(
								'biller_id'				=> $biller_id,
								'date' 					=> $date,
                                'reference_no' => (($_POST['paid_by'][$r] == 'deposit' || $_POST['paid_by'][$r] == 'depreciation') ? $reference : $this->site->getReference('sp', $this->session->userdata('biller_id') ? $default_biller[0] : $default_biller)),
								'amount' 				=> $this->erp->formatDecimal($paidi),
								'paid_by' 				=> $_POST['paid_by'][$j],
								'cheque_no' 			=> $_POST['cheque_no'][$j],
								'cc_no' 				=> ($_POST['paid_by'][$j] == 'gift_card' ? $_POST['paying_gift_card_no'][$j] : $_POST['cc_no'][$j]),
								'cc_holder' 			=> $_POST['cc_holder'][$j],
								'cc_month' 				=> $_POST['cc_month'][$j],
								'cc_year' 				=> $_POST['cc_year'][$j],
								'cc_type' 				=> $_POST['cc_type'][$j],
								'cc_cvv2' 				=> $_POST['cc_cvv2'][$j],
								'created_by' 			=> $this->session->userdata('user_id'),
								'type' 					=> 'received',
								'note' 					=> $_POST['payment_note'][$j],
								'pos_paid' 				=> $_POST['amount'][$j],
                                'pos_balance' 			=> $pos_balance,
								'pos_paid_other' 		=> $_POST['other_cur_paid'][$j],
								'pos_paid_other_rate' 	=> $cur_rate->rate,
								'bank_account' 			=> $bank_account[$j]
							);
							$pp[] = $paidd;
						}
					}
                }*/

                if($kh_paid == true){
					if (!empty($pp)) {
						$paid = array_sum($pp);
						$paid = $_POST['other_cur_paid'][0]/$cur_rate->rate;
					} else {
						$paid = 0;
					}
				}else{
					if (!empty($pp)) {
						$paid = array_sum($pp);
						$paid = $paid + $_POST['other_cur_paid'][0]/$cur_rate->rate;
					} else {
						$paid = 0;
					}
				}
								
            }
			
            if (!isset($payment) || empty($payment)) {
                $payment = array();
            }
            if ($_FILES['document']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->digital_upload_path;
                $config['allowed_types'] = $this->digital_file_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = FALSE;
                $config['encrypt_name'] = TRUE;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('document')) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                $data['attachment'] = $photo;
            } else {
                $photo = $this->input->post('attachment');
                $data['attachment'] = $photo;
            }
            //$this->erp->print_arrays($data, $products, $payment);
        }
		
        if ($this->form_validation->run() == true ) {
			$cur_rate = $this->pos_model->getExchange_rate();
			if ($suspend) {
                $data['suspend_id']     = $this->input->post('suspend_id');
				$data['suspend_name']   = $this->input->post('suspend_name');

                $arr_suspend = $this->pos_model->suspendSale($data, $products, $did);
                if ($arr_suspend['suppend_id'] >0) {
                    $this->session->set_userdata('remove_posls', 1);
                    $this->session->set_flashdata('message', $this->lang->line("sale_suspended"));
                    if($arr_suspend['have_item'] == 0){
                        redirect("pos/index/".$arr_suspend['suppend_id']);
                    }else{
                        redirect("pos");
                    }
                }
				
            } else {
				$data['payment_status'] = $payment_status;

                if ($sale = $this->pos_model->addSale($data, $products, $payment, $did, $loans, $combine_table_id)) {
                    optimizeSale(date('Y-m-d', strtotime($date)));
					$paid_by = $_POST['paid_by'][0];
                    $sale_id = $this->sales_model->getInvoiceByID($sale['sale_id']);
					if($paid_by == "deposit"){
						$deposits = array(
							'date' 			=> $date,
							'reference' 	=> $reference,
							'company_id' 	=> $customer_id,
							'amount' 		=> (-1) * $amout_paid,
							'paid_by' 		=> $paid_by,
							'note' 			=> ($note? $note:$customer),
							'created_by' 	=> $this->session->userdata('user_id'),
							'biller_id' 	=> $biller_id,
							'sale_id' 		=> $sale_id,
							'bank_code' 	=> $bank_account[0],
							'status' 		=> 'paid'
						);
                        
						$this->sales_model->add_deposit($deposits);
					}
					
					$suspended_sale = $this->pos_model->getOpenBillByID($did);
					$inactive = $this->pos_model->updateSuspendactive($suspended_sale->suspend_id);
                    $this->session->set_userdata('remove_posls', 1);
                    $msg = $this->lang->line("sale_added");
                    if (!empty($sale['message'])) {
                        foreach ($sale['message'] as $m) {
                            $msg .= '<br>' . $m;
                        }
                    }
                    $this->session->set_flashdata('message', $msg);
					$sale_id    = $this->sales_model->getInvoiceByID($sale['sale_id']);
					$address    = $customer_details->address . " " . $customer_details->city . " " . $customer_details->state . " " . $customer_details->postal_code . " " . $customer_details->country . "<br>Tel: " . $customer_details->phone . " Email: " . $customer_details->email;
					$dlDetails  = array(
						'date'              => $date,
						'sale_id'           => $sale['sale_id'],
						'do_reference_no'   => $this->site->getReference('do'),
						'sale_reference_no' => $sale_id->reference_no,
						'customer'          => $customer_details->name,
						'address'           => $address,
						'created_by'        => $this->session->userdata('user_id'),
						'delivery_status'   => 'pending',
						'delivery_by'       => $delivery_by
					);
					
					$pos = $this->sales_model->getSetting();
					if($pos->auto_delivery == 1){
						$this->sales_model->addDelivery($dlDetails);
					}
                    redirect("pos/view/" . $sale['sale_id']);
                    //redirect("pos/maman_invoice/" . $sale['sale_id']);
					//redirect("sales/invoice_st_a5/" . $sale['sale_id']);
					//redirect("pos/view_teatry/" . $sale['sale_id']);
					//redirect("sales/sales_invoice_a5/" . $sale['sale_id']);
					  
					
                }
               
            }
        }else {
			//it may be run.
            $this->data['suspend_sale']     = NULL;
            $this->data['type']             = 0;
            $this->data['type_id']          = 0;
            $this->data['sale_order_id']    = 0;
			$this->data['combine_table']    = 0;
            if($sid){
                $suspended_sale = $this->pos_model->getOpenBillByID($sid);
				if($suspended_sale){
					$suspended = $this->pos_model->getSuspended($suspended_sale->suspend_id);
				}
				
                $inv_items = $this->pos_model->getSuspendedSaleItems($sid);
                $c = rand(100000, 9999999);
                foreach ($inv_items as $item) {
                    $row = $this->site->getProductByID($item->product_id);
					$dig = $this->site->getProductByID($item->digital_id);
                    if (!$row) {
                        $row = json_decode('{}');
                        $row->tax_method = 0;
                        $row->quantity = 0;
                    } else {
                        unset($row->details, $row->product_details, $row->cost, $row->supplier1price, $row->supplier2price, $row->supplier3price, $row->supplier4price, $row->supplier5price);
                    }
                    $pis = $this->pos_model->getPurchasedItems($item->product_id, $item->warehouse_id, $item->option_id);
                    if($pis){
                        foreach ($pis as $pi) {
                            $row->quantity += $pi->quantity_balance;
                        }
                    }
					$printed                = $this->pos_model->printed_update($sid,$item->product_code);
                    $row->id                = $item->product_id;
					$row->printed           = (isset($printed->printed)?($printed->printed):0);
                    $row->code              = $item->product_code;
                    $row->name              = $item->product_name;
                    $row->type              = $item->product_type;
                    $row->qty               = $item->quantity;
                    $row->quantity          += $item->quantity;
                    $row->discount          = $item->discount ? $item->discount : '0';
                    $row->price             = $this->erp->formatDecimal($item->net_unit_price+$this->erp->formatDecimal($item->item_discount/$item->quantity));
                    $row->unit_price        = $row->tax_method ? $item->unit_price+$this->erp->formatDecimal($item->item_discount/$item->quantity)+$this->erp->formatDecimal($item->item_tax/$item->quantity) : $item->unit_price+($item->item_discount/$item->quantity);
                    $row->real_unit_price   = $item->real_unit_price;
                    $row->tax_rate          = $item->tax_rate_id;
                    $row->serial            = $item->serial_no;
                    $row->option            = $item->option_id;
					$row->digital_id	    = 0;
					$row->digital_code	    = '';
					$row->digital_name	    = '';
					if($dig){
						$row->digital_code 	= $dig->code .' ['. $row->code .']';
						$row->digital_name 	= $dig->name .' ['. $row->name .']';
						$row->digital_id   	= $dig->id;
					}
                    $options = $this->pos_model->getProductOptions($row->id, $item->warehouse_id);

                    if ($options) {
                        $option_quantity = 0;
                        foreach ($options as $option) {
                            $pis = $this->pos_model->getPurchasedItems($row->id, $item->warehouse_id, $item->option_id);
                            if($pis){
                                foreach ($pis as $pi) {
                                    $option_quantity += $pi->quantity_balance;
                                }
                            }
                            if($option->quantity > $option_quantity) {
                                $option->quantity = $option_quantity;
                            }
                        }
                    }

                    $ri = $this->Settings->item_addition ? $row->id : $c;

                    if ($row->tax_rate) {
                        $tax_rate = $this->site->getTaxRateByID($row->tax_rate);
                        $pr[$ri] = array('id' => $c, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'tax_rate' => $tax_rate, 'image' => $row->image, 'options' => $options, 'makeup_cost' => 0);
                    } else {
                        $pr[$ri] = array('id' => $c, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'tax_rate' => false, 'image' => $row->image, 'options' => $options, 'makeup_cost' => 0);
                    }
                    $c++;
                }				
				$this->data['items']            = json_encode($pr);
                $this->data['sid']              = $sid;
                $this->data['suspend_sale']     = $suspended_sale;
				$this->data['cus_suspend']      = $suspended;
                $this->data['message']          = lang('suspended_sale_loaded');
                $this->data['customer']         = $this->pos_model->getCompanyByID($suspended_sale->customer_id);
            }else {
                $this->data['customer']         = $this->pos_model->getCompanyByID($this->pos_settings->default_customer);
                $this->data['reference_note']   = NULL;
            }
				
			if ($sale_order_id){
				
                $sale_order                     = $this->sales_model->getSalePOS($sale_order_id);
				$this->data['sale_order']       = $sale_order;
				$items                          = $this->sales_model->getPOSOrdItems($sale_order_id);
				$this->data['sale_order_id']    = $sale_order_id;
				$this->data['type']             = "sale_order";
				$this->data['type_id']          = $sale_order_id;
				
				$customer = $this->site->getCompanyByID($sale_order->customer_id);
				
                $c = rand(100000, 9999999);
                foreach ($items as $item) {
                    $row = $this->site->getProductByID($item->product_id);
					
                    if (!$row) {
                        $row = json_decode('{}');
                        $row->tax_method = 0;
                    } else {
                        unset($row->details, $row->product_details, $row->supplier1price, $row->supplier2price, $row->supplier3price, $row->supplier4price, $row->supplier5price);
                    }
                    $row->quantity = 0;
                    $pis = $this->sales_model->getPurchasedItems($item->product_id, $item->warehouse_id, $item->option_id);
                    if($pis){
                        foreach ($pis as $pi) {
                            $row->quantity += $pi->quantity_balance;
                        }
                    }
                    $row->id                = $item->product_id;
                    $row->code              = $item->product_code;
                    //$row->name            = $item->product_name;
                    $row->type              = $item->product_type;
                    $row->qty               = $item->quantity;
                    $row->discount          = $item->discount ? $item->discount : '0';
                    $row->price             = $this->erp->formatDecimal($item->net_unit_price+$this->erp->formatDecimal($item->item_discount/$item->quantity));
                    $row->unit_price        = $row->tax_method ? $item->unit_price+$this->erp->formatDecimal($item->item_discount/$item->quantity)+$this->erp->formatDecimal($item->item_tax/$item->quantity) : $item->unit_price+($item->item_discount/$item->quantity);
                    $row->real_unit_price   = $item->real_unit_price;
                    $row->tax_rate          = $item->tax_rate_id;
                    $row->serial            = '';
                    $row->option            = $item->option_id;
					
					$group_prices = $this->sales_model->getProductPriceGroup($item->product_id, $customer->price_group_id);
					$all_group_prices = $this->sales_model->getProductPriceGroup($item->product_id);
					$row->price_id = 0;
					
                    $options = $this->sales_model->getProductOptions($row->id, $item->warehouse_id);
                    if ($options) {
                        $option_quantity = 0;
                        foreach ($options as $option) {
                            $pis = $this->sales_model->getPurchasedItems($row->id, $item->warehouse_id, $item->option_id);
                            if($pis){
                                foreach ($pis as $pi) {
                                    $option_quantity += $pi->quantity_balance;
                                }
                            }
                            if($option->quantity > $option_quantity) {
                                $option->quantity = $option_quantity;
                            }
                        }
                    }
                    $combo_items = FALSE;
                    if ($row->type == 'combo') {
                        $combo_items = $this->sales_model->getProductComboItems($row->id, $item->warehouse_id);
                    }
                    $ri = $this->Settings->item_addition ? $row->id : $c;
                    if ($row->tax_rate) {
                        $tax_rate   = $this->site->getTaxRateByID($row->tax_rate);
                        $pr[$ri]    = array('id' => $c, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'combo_items' => $combo_items, 'tax_rate' => $tax_rate, 'options' => $options, 'makeup_cost' => 0,'group_prices'=>$group_prices, 'all_group_prices' => $all_group_prices);
                    } else {
                        $pr[$ri]    = array('id' => $c, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'combo_items' => $combo_items, 'tax_rate' => false, 'options' => $options, 'makeup_cost' => 0,'group_prices'=>$group_prices, 'all_group_prices' => $all_group_prices);
                    }
                    $c++;
                }
                $payment_deposit=0;
				$this->data['sale_order_id']    = $sale_order_id;
                $this->data['sale_order_items'] = json_encode($pr);
				$this->data['payment_deposit']  = $payment_deposit;
            }
			
			if ($combine_table) {				
                $suspended_sale = $this->pos_model->getOpenBillByArrayID($combine_table);
				$suspended 		= $this->pos_model->getSuspended($suspended_sale->suspend_id);
                $inv_items 		= $this->pos_model->getSuspendedSaleItemsByArr($combine_table);				
                $c = rand(100000, 9999999);
                foreach ($inv_items as $item) {
                    $row = $this->site->getProductByID($item->product_id);
                    if (!$row) {
                        $row = json_decode('{}');
                        $row->tax_method = 0;
                        $row->quantity = 0;
                    } else {
                        unset($row->details, $row->product_details, $row->cost, $row->supplier1price, $row->supplier2price, $row->supplier3price, $row->supplier4price, $row->supplier5price);
                    }
                    $pis = $this->pos_model->getPurchasedItems($item->product_id, $item->warehouse_id, $item->option_id);
                    if($pis){
                        foreach ($pis as $pi) {
                            $row->quantity += $pi->quantity_balance;
                        }
                    }
                    $row->id 		        = $item->product_id;
                    $row->code 		        = $item->product_code;
                    $row->name 		        = $item->product_name;
					$row->note 		        = $item->product_noted;
                    $row->type 		        = $item->product_type;
                    $row->qty 		        = $item->quantity;
                    $row->quantity          += $item->quantity;
                    $row->discount 	        = $item->discount ? $item->discount : '0';
                    $row->price 	        = $this->erp->formatDecimal($item->net_unit_price+$this->erp->formatDecimal($item->item_discount/$item->quantity));
                    $row->unit_price        = $row->tax_method ? $item->unit_price+$this->erp->formatDecimal($item->item_discount/$item->quantity)+$this->erp->formatDecimal($item->item_tax/$item->quantity) : $item->unit_price+($item->item_discount/$item->quantity);
                    $row->real_unit_price   = $item->real_unit_price;
                    $row->tax_rate 	        = $item->tax_rate_id;
                    $row->serial 	        = $item->serial_no;
                    $row->option 	        = $item->option_id;
                    $options 		        = $this->pos_model->getProductOptions($row->id, $item->warehouse_id);

                    if ($options) {
                        $option_quantity = 0;
                        foreach ($options as $option) {
                            $pis = $this->pos_model->getPurchasedItems($row->id, $item->warehouse_id, $item->option_id);
                            if($pis){
                                foreach ($pis as $pi) {
                                    $option_quantity += $pi->quantity_balance;
                                }
                            }
                            if($option->quantity > $option_quantity) {
                                $option->quantity = $option_quantity;
                            }
                        }
                    }

                    $ri = $this->Settings->item_addition ? $row->id : $c;
					if($row->note){
						$note = '('.$row->note.')';
					}else{
						$note = '';
					}
                    if ($row->tax_rate) {
                        $tax_rate = $this->site->getTaxRateByID($row->tax_rate);
                        $pr[$ri] = array('id' => $c, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")". $note, 'row' => $row, 'tax_rate' => $tax_rate, 'image' => $row->image, 'options' => $options, 'makeup_cost' => 0);
                    } else {
                        $pr[$ri] = array('id' => $c, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'tax_rate' => false, 'image' => $row->image, 'options' => $options, 'makeup_cost' => 0);
                    }
					
                    $c++;
                }
				if($combine_table){
					$str = explode('_', $combine_table);
					$ch = array();
					foreach($str as $sup_id){
						$get = $this->pos_model->get_suppendName($sup_id);
						$ch[] = $get->suspend_name;
					}
					$suppend_name = implode('+',$ch);
				}else{
					$sup_notes = $this->pos_model->get_suppendName($did);
					$suppend_name = $sup_notes->suspend_name;
				}
				
				$this->data['suppend_name'] 	= $suppend_name;
				$this->data['combine_items'] 	= json_encode($pr);
                $this->data['combine_table'] 	= $combine_table;
                $this->data['suspend_sale'] 	= $suspended_sale;
				$this->data['cus_suspend'] 		= $suspended;
                $this->data['message'] 			= lang('suspended_sale_loaded');
                $this->data['customer'] 		= $this->pos_model->getCompanyByID($suspended_sale->customer_id);
            } else {
                $this->data['customer'] = $this->pos_model->getCompanyByID($this->pos_settings->default_customer);
                $this->data['reference_note'] = NULL;
            }
			
            $this->data['error'] 			= (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['message'] 			= isset($this->data['message']) ? $this->data['message'] : $this->session->flashdata('message');

            $this->data['biller'] 			= $this->site->getCompanyByID($this->pos_settings->default_biller);
			if($this->session->userdata('biller_id')) {
                $biller_id = json_decode($this->session->userdata('biller_id'));
				$this->data['billers'] 			= $this->site->getAllCompanies('biller', $biller_id);
            }else {
                $this->data['billers'] 			= $this->site->getAllCompanies('biller');
            }
            $this->data['warehouses'] 		= $this->site->getAllWarehouses();
            
            $this->data['tax_rates'] 		= $this->site->getAllTaxRates();
			$this->data['owner_password'] 	= $this->site->getUserSetting(1);

			if ($this->Owner || $this->Admin || !$this->session->userdata('biller_id')){
				$biller_id = $this->site->get_setting()->default_biller;
				$this->data['reference'] 	= $this->site->getReference('pos',$biller_id);
			}else{
				$biller_id = JSON_decode($this->session->userdata('biller_id'));
				$this->data['reference'] 	= $this->site->getReference('pos',$biller_id);
				$this->data['user_ware'] 	= $this->site->getUserWarehouses();
			}
            
			$this->data['agencies'] 		= $this->site->getAllUsers();
			$this->data['drivers'] 			= $this->site->getAllCompanies('driver');
            $this->data['user'] 			= $this->site->getUser();
            $this->data["tcp"] 				= $this->pos_model->products_count($this->pos_settings->default_category);
            $this->data['products'] 		= $this->ajaxproducts($this->pos_settings->default_category);
			$this->data['room'] 			= $this->site->suspend_room();
			$this->data['user_settings'] 	= $this->site->getUserSetting($this->session->userdata('user_id'));
			$this->data['define_principle'] = $this->settings_model->getprinciple_types();
			$this->data['queue'] 			= $this->sales_model->getLastQueue(date('Y-m-d'), $this->pos_settings->default_biller);

            $this->data['categories'] 		= $this->site->getAllCategories();
			
            $this->data['subcategories'] 	= $this->pos_model->getSubCategoriesByCategoryID($this->pos_settings->default_category);
            $this->data['pos_settings'] 	= $this->pos_settings;
			$this->data['exchange_rate'] 	= $this->pos_model->getExchange_rate('KHM');
			$this->data['user_layout'] 		= $this->pos_model->getPosLayout($this->session->userdata('user_id'));
			$this->data['bankAccounts'] 	=  $this->site->getAllBankAccounts();
			$this->data['userBankAccounts'] =  $this->site->getAllBankAccountsByUserID();
            $this->load->view($this->theme . 'pos/add', $this->data);
        }
    }

	function view_teatry($sale_id = NULL, $modal = NULL)
    {
        $this->erp->checkPermissions('index');
        if ($this->input->get('id')) {
            $sale_id = $this->input->get('id');
        }
		
        $this->load->helper('text');
        $this->data['error'] 				= (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['message'] 				= $this->session->flashdata('message');
        $this->data['rows'] 				= $this->pos_model->getAllInvoiceItems($sale_id);
        $inv 								= $this->pos_model->getInvoicePosByID($sale_id);
        $biller_id 							= $inv->biller_id;
        $customer_id 						= $inv->customer_id;
        $this->data['biller'] 				= $this->pos_model->getCompanyByID($biller_id);
        $this->data['customer'] 			= $this->pos_model->getCompanyByID($customer_id);
        $this->data['payments'] 			= $this->pos_model->getInvoicePaymentsPOS($sale_id);
        $this->data['pos'] 					= $this->pos_model->getSetting();
        $this->data['barcode'] 				= $this->barcode($inv->reference_no, 'code39', 30);
        $this->data['inv'] 					= $inv;
		//$this->erp->print_arrays($inv);
        $this->data['sid'] 					= $sale_id;
		$this->data['exchange_rate'] 		= $this->pos_model->getExchange_rate();
		$this->data['outexchange_rate'] 	= $this->pos_model->getExchange_rate('KHM_o');
		$this->data['exchange_rate_th'] 	= $this->pos_model->getExchange_rate('THA');
		$this->data['exchange_rate_kh_c'] 	= $this->pos_model->getExchange_rate('KHM');
        $this->data['modal'] 				= $modal;
        $this->data['page_title'] 			= $this->lang->line("invoice");
        $this->load->view($this->theme . 'pos/view_teatry', $this->data);
    }
	
    function view_bill()
    {
        $this->erp->checkPermissions('index');
        $this->data['tax_rates'] = $this->site->getAllTaxRates();
        $this->load->view($this->theme . 'pos/view_bill', $this->data);
    }
	
	function view_kitchen()
    {
        $this->erp->checkPermissions('index');

        //$this->table->set_heading('Id', 'The Title', 'The Content');
        
        $this->data['data'] = $this->pos_model->getDelivers();
        $this->load->view($this->theme . 'pos/view_kitchen', $this->data);
    }

    function stripe_balance()
    {
        if (!$this->Owner) {
            return FALSE;
        }
        $this->load->model('stripe_payments');
        return $this->stripe_payments->get_balance();
    }

    function paypal_balance()
    {
        if (!$this->Owner) {
            return FALSE;
        }
        $this->load->model('paypal_payments');
        return $this->paypal_payments->get_balance();
    }

    function registers()
    {
        $this->erp->checkPermissions();
        $this->data['error']        = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['registers']    = $this->pos_model->getOpenRegisters();
        $bc                         = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('pos'), 'page' => lang('pos')), array('link' => '#', 'page' => lang('open_registers')));
        $meta                       = array('page_title' => lang('open_registers'), 'bc' => $bc);
        $this->page_construct('pos/registers', $meta, $this->data);
    }

    function open_register()
    {
        $this->erp->checkPermissions('index');
        $this->form_validation->set_rules('cash_in_hand', lang("cash_in_hand"), 'trim|required|numeric');

        if ($this->form_validation->run() == true) {
			$cash_in = array(
				'cash_in_hand_kh' => $this->input->post('cash_in_hand_kh') ? $this->input->post('cash_in_hand_kh') : 0,
				'cash_in_hand_us' => $this->input->post('cash_in_hand_us') ? $this->input->post('cash_in_hand_us') : 0
			);
			
            $data = array(
				'date' 			=> date('Y-m-d H:i:s'),
                'cash_in_hand' 	=> $this->input->post('cash_in_hand'),
                'cash_in' 		=> json_encode($cash_in),
                'user_id' 		=> $this->session->userdata('user_id'),
                'status' 		=> 'open'
            );
        }
        if ($this->form_validation->run() == true && $this->pos_model->openRegister($data)) {
            $this->session->set_flashdata('message', lang("welcome_to_pos"));
            redirect("pos");
        } else {
			$this->data['exchange_rate'] 	= $this->pos_model->getExchange_rate('KHM');
            $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('open_register')));
            $meta = array('page_title' => lang('open_register'), 'bc' => $bc);
            $this->page_construct('pos/open_register', $meta, $this->data);
        }
    }
    
	function close_register_popup($register_id){

        $this->db->select("erp_pos_register.*");
        $this->db->from("erp_pos_register");
        $this->db->where('erp_pos_register.id', $register_id);
        $q = $this->db->get();
        if($q->num_rows()>0){
            $this->register_report($q->row()->user_id, $q->row()->date, $q->row()->id, $q->row()->total_cash);
        }
        return false;
    }

    function register_report($user_id = NULL, $rdate = NULL, $id = NULL, $total_cash = NULL)
    {
        if (!$this->Owner && !$this->Admin) {
            $user_id = $this->session->userdata('user_id');
        }
        $this->form_validation->set_rules('total_cash', lang("total_cash"), 'trim|required|numeric');
        $this->form_validation->set_rules('total_cheques', lang("total_cheques"), 'trim|required|numeric');
        $this->form_validation->set_rules('total_cc_slips', lang("total_cc_slips"), 'trim|required|numeric');

        if ($this->form_validation->run() == true) {
            
            if ($this->Owner || $this->Admin) {
                $user_register = $user_id ? $this->pos_model->registerData($user_id) : NULL;
                $rid = $user_register ? $user_register->id : $this->session->userdata('register_id');
                $user_id = $user_register ? $user_register->user_id : $this->session->userdata('user_id');
            } else {
                $rid = $this->session->userdata('register_id');
                $user_id = $this->session->userdata('user_id');
            }
            $data = array('closed_at' => date('Y-m-d H:i:s'),
                'total_cash' => $this->input->post('total_cash'),
                'total_cash_submitted' => $this->input->post('total_cash_submitted'),
                
                'total_cheques' => $this->input->post('total_cheques'),
                'total_cheques_submitted' => $this->input->post('total_cheques_submitted'),
                
                'total_cc_slips' => $this->input->post('total_cc_slips'),
                'total_cc_slips_submitted' => $this->input->post('total_cc_slips_submitted'),
                
                'total_member_slips' => $this->input->post('total_member_slips'),
                'total_member_slips_submitted' => $this->input->post('total_member_slips_submitted'),
                
                'total_voucher_slips' => $this->input->post('total_voucher_slips'),
                'total_voucher_slips_submitted' => $this->input->post('total_voucher_slips_submitted'),
                
                'note' => $this->input->post('note'),
                'status' => 'close',
                'transfer_opened_bills' => $this->input->post('transfer_opened_bills'),
                'closed_by' => $this->session->userdata('user_id'),
            );
            

        } elseif ($this->input->post('close_register')) {
            $this->session->set_flashdata('error', (validation_errors() ? validation_errors() : $this->session->flashdata('error')));
            redirect("pos");
        }

        if ($this->form_validation->run() == true && $this->pos_model->closeRegister($rid, $user_id, $data)) {
            $this->session->set_flashdata('message', lang("register_closed"));
            redirect("welcome");
        } else {
            $user_register          = $user_id ? $this->pos_model->registerData($user_id) : NULL;
            $user_close_register    = $user_id ? $this->pos_model->closeRegisterData($id) : NULL;
            $register_open_time     = $user_register ? $user_register->date : $this->session->userdata('register_open_time');
            $register_close_time    = $user_close_register ? $user_close_register->closed_at : $this->session->userdata('register_close_time');
            if ($this->Owner || $this->Admin) {

                // $this->erp->print_arrays($register_open_time);
                if($rdate){
                    $register_open_time = $rdate;
                }
                $this->data['cash_in_hand']         = $user_register ? $user_register->cash_in_hand : NULL;
                $this->data['register_open_time']   = $user_register ? $register_open_time : NULL;
                $this->data['register_close_time']  = $register_close_time ? $register_close_time : NULL;
                
            } else {
                $user_register          = $user_id ? $this->pos_model->registerData($user_id) : NULL;
                // $this->erp->print_arrays($user_register);
                $register_open_time     = $user_register ? $user_register->date : $this->session->userdata('register_open_time');
                $this->data['cash_in_hand']         = NULL;
                $this->data['register_open_time']   = NULL;
                $this->data['register_close_time']  = $register_close_time ? $register_close_time : NULL;
            }

            $this->data['error']            = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['ccsales']          = $this->pos_model->getRegisterCCSales($register_open_time, $user_id);
            $this->data['cashsales']        = $this->pos_model->getRegisterCashSales($register_open_time, $user_id);
            $this->data['chsales']          = $this->pos_model->getRegisterChSales($register_open_time, $user_id);
            $this->data['memsales']         = $this->pos_model->getRegisterMemSales($register_open_time, $user_id);
            $this->data['vouchersales']     = $this->pos_model->getRegisterVoucherSales($register_open_time, $user_id);
            
            $this->data['pppsales']         = $this->pos_model->getRegisterPPPSales($register_open_time, $user_id);
            $this->data['stripesales']      = $this->pos_model->getRegisterStripeSales($register_open_time, $user_id);
            $this->data['totalsales']       = $this->pos_model->getRegisterSales($register_open_time, $user_id);
            $this->data['refunds']          = $this->pos_model->getRegisterRefunds($register_open_time);
            $this->data['cashrefunds']      = $this->pos_model->getRegisterCashRefunds($register_open_time);
            $this->data['expenses']         = $this->pos_model->getRegisterExpenses($register_open_time);
            $this->data['users']            = $this->pos_model->getUsers($user_id);
            $this->data['suspended_bills']  = $this->pos_model->getSuspendedsales($user_id);
            $this->data['user_id']          = $user_id;
            $this->data['modal_js']         = $this->site->modal_js();
            $this->data['total_cash']       = $total_cash;
            $this->load->view($this->theme . 'pos/register_report', $this->data);
        }
    }

    function close_register($user_id = NULL,$rdate = NULL, $id = NULL, $total_cash = NULL)
    {
        if (!$this->Owner && !$this->Admin) {
            $user_id = $this->session->userdata('user_id');
        }
        $this->form_validation->set_rules('total_cash', lang("total_cash"), 'trim|required|numeric');
        $this->form_validation->set_rules('total_cheques', lang("total_cheques"), 'trim|required|numeric');
        $this->form_validation->set_rules('total_cc_slips', lang("total_cc_slips"), 'trim|required|numeric');

        if ($this->form_validation->run() == true) {
            
            if ($this->Owner || $this->Admin) {
                $user_register = $user_id ? $this->pos_model->registerData($user_id) : NULL;
                $rid = $user_register ? $user_register->id : $this->session->userdata('register_id');
                $user_id = $user_register ? $user_register->user_id : $this->session->userdata('user_id');
            } else {
                $rid = $this->session->userdata('register_id');
                $user_id = $this->session->userdata('user_id');
            }
			$cash_out = array(
				'cash_out_kh' => $this->input->post('cur_kh') ? $this->input->post('cur_kh') : 0,
				'cash_out_us' => $this->input->post('cur_us') ? $this->input->post('cur_us') : 0
			);
			
            $data = array(
				'closed_at'						=> date('Y-m-d H:i:s'),
                'total_cash' 					=> $this->input->post('total_cash'),
                'total_cash_submitted' 			=> str_replace(',' , '', $this->input->post('total_cash_submitted')),
                
                'total_cheques' 				=> $this->input->post('total_cheques'),
                'total_cheques_submitted' 		=> $this->input->post('total_cheques_submitted'),
                
                'total_cc_slips' 				=> $this->input->post('total_cc_slips'),
                'total_cc_slips_submitted' 		=> $this->input->post('total_cc_slips_submitted'),
                
                'total_member_slips' 			=> $this->input->post('total_member_slips'),
                'total_member_slips_submitted' 	=> $this->input->post('total_member_slips_submitted'),
                
                'total_voucher_slips' 			=> $this->input->post('total_voucher_slips'),
                'total_voucher_slips_submitted' => $this->input->post('total_voucher_slips_submitted'),
                
                'note' 							=> $this->input->post('note'),
                'cash_out' 						=> json_encode($cash_out),
                'status' 						=> 'close',
                'transfer_opened_bills' 		=> $this->input->post('transfer_opened_bills'),
                'closed_by' 					=> $this->session->userdata('user_id'),
            );
        } elseif ($this->input->post('close_register')) {
            $this->session->set_flashdata('error', (validation_errors() ? validation_errors() : $this->session->flashdata('error')));
            redirect("pos");
        }

        if ($this->form_validation->run() == true && $this->pos_model->closeRegister($rid, $user_id, $data)) {
            $this->session->set_flashdata('message', lang("register_closed"));
            redirect("welcome");
        } else {

            $user_register          = $user_id ? $this->pos_model->registerData($user_id) : NULL;
            $user_close_register    = $user_id ? $this->pos_model->closeRegisterData($id) : NULL;
            $register_open_time     = $user_register ? $user_register->date : $this->session->userdata('register_open_time');
            $register_close_time    = $user_close_register ? $user_close_register->closed_at : $this->session->userdata('register_close_time');

            if ($this->Owner || $this->Admin) {
                if($rdate){
                    $register_open_time = $rdate;
                }
                $this->data['cash_in_hand']         = $user_register ? $user_register->cash_in_hand : NULL;
                $this->data['register_open_time']   = $user_register ? $register_open_time : NULL;
                $this->data['register_close_time']  = $register_close_time ? $register_close_time : NULL;
                
            } else {
                $register_open_time                 = $this->session->userdata('register_open_time');
				$user_register          			= $user_id ? $this->pos_model->registerData($user_id) : NULL;
                $this->data['cash_in_hand']         = $user_register ? $user_register->cash_in_hand : NULL;
                $this->data['register_open_time']   = NULL;
                $this->data['register_close_time']  = $register_close_time ? $register_close_time : NULL;
            }
			
            $this->data['error']            = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['ccsales']          = $this->pos_model->getRegisterCCSales($register_open_time, $user_id);
            $this->data['cashsales']        = $this->pos_model->getRegisterCashSales($register_open_time, $user_id);
            $this->data['chsales']          = $this->pos_model->getRegisterChSales($register_open_time, $user_id);
            $this->data['memsales']         = $this->pos_model->getRegisterMemSales($register_open_time, $user_id);
            $this->data['vouchersales']     = $this->pos_model->getRegisterVoucherSales($register_open_time, $user_id);
            
            $this->data['pppsales']         = $this->pos_model->getRegisterPPPSales($register_open_time, $user_id);
            $this->data['stripesales']      = $this->pos_model->getRegisterStripeSales($register_open_time, $user_id);
            $this->data['totalsales']       = $this->pos_model->getRegisterSales($register_open_time, $user_id);
            $this->data['refunds']          = $this->pos_model->getRegisterRefunds($register_open_time);
            $this->data['cashrefunds']      = $this->pos_model->getRegisterCashRefunds($register_open_time);
            //$this->data['expenses']         = $this->pos_model->getRegisterExpenses($register_open_time);
            $this->data['users']            = $this->pos_model->getUsers($user_id);
            $this->data['suspended_bills']  = $this->pos_model->getSuspendedsales($user_id);
			$this->data['exchange_rate'] 	= $this->pos_model->getExchange_rate('KHM');
            $this->data['user_id']          = $user_id;
            $this->data['modal_js']         = $this->site->modal_js();
            $this->data['total_cash']       = $total_cash;
            $this->load->view($this->theme . 'pos/close_register', $this->data);
        }
    }
    
	function updateQty($suspend_id = null, $item_code = null, $quantity = null, $ruprice = null)
	{
        $suspend_id         = $this->input->get('suspend_id', TRUE);
        $item_code          = $this->input->get('item_code', TRUE);
        $quantity           = $this->input->get('quantity', TRUE);
        $ruprice            = $this->input->get('ruprice', TRUE);

        $this->db->update('suspended_items',
                            array('quantity' => $quantity),
                            array('suspend_id' => $suspend_id, 'product_code' => $item_code));

        /* Select total price */
        $this->db->select('sum(unit_price * quantity) as tprice');
        $this->db->where('suspend_id', $suspend_id);
        $q = $this->db->get('suspended_items')->row();

        $pr = array('sub_total' => $q->tprice);
        echo json_encode($pr);
    }

    function clearPosItem($suspend_id = null)
    {
        $suspend_id = $this->input->get('suspend_id', TRUE);
        $this->db->delete('suspended_bills', array('id' => $suspend_id));
        $this->db->delete('suspended_items', array('suspend_id' => $suspend_id));
        exit('success');
    }

    function removeItemCount($suspend_id = null, $item_rows = null, $item_code = null, $quantity = null)
    {
        $suspend_id         = $this->input->get('suspend_id');
        $item_rows          = $this->input->get('item_rows');
        $item_code          = $this->input->get('item_code');
        $quantity           = $this->input->get('quantity');

        /* DELETE item */
        $this->db->delete('suspended_items', array('suspend_id' => $suspend_id, 'product_code' => $item_code));

        /* Select total price */
        $this->db->select('sum(unit_price * quantity) as tprice');
        $this->db->where('suspend_id', $suspend_id);
        $q = $this->db->get('suspended_items')->row();

        $this->db->update('suspended_bills', array('count' => $item_rows - 1, 'total' => $q->unit_price), array('id' => $suspend_id ));
        $pr = array('sub_total' => $q->tprice);
        echo json_encode($pr);
    }
    
	function saveItemList()
	{
        $i                      = isset($_POST['product_code']) ? sizeof($_POST['product_code']) : 0;
        $products               = array ();
        $quantity               = "quantity";
        $product                = "product";
        $unit_cost              = "unit_cost";
        $tax_rate               = "tax_rate";
        $date                   = date('Y-m-d H:i:s');
        $warehouse_id           = $this->input->post('warehouse');
        $customer_id            = $this->input->post('customer');
        $biller_id              = $this->input->post('biller');
        $total_items            = $this->input->post('total_items');
        $suspend_               = $this->input->post('suspend_');
        //$sale_status          = $this->input->post('sale_status');
        $sale_status            = 'completed';
        $payment_status         = 'due';
        $payment_term           = 0;
        $due_date               = date('Y-m-d', strtotime('+' . $payment_term . ' days'));
        $shipping               = $this->input->post('shipping') ? $this->input->post('shipping') : 0;
        $customer_details       = $this->site->getCompanyByID($customer_id);
        $customer               = $customer_details->company ? $customer_details->company : $customer_details->name;
        $biller_details         = $this->site->getCompanyByID($biller_id);
        $biller                 = $biller_details->company != '-' ? $biller_details->company : $biller_details->name;
        $note                   = $this->erp->clear_tags($this->input->post('pos_note'));
        $staff_note             = $this->erp->clear_tags($this->input->post('staff_note'));
        $reference              = $this->site->getReference('pos');
        $item_image             = "";
        $total                  = 0;
        $product_tax            = 0;
        $order_tax              = 0;
        $product_discount       = 0;
        $order_discount         = 0;
        $percentage             = '%';

        $i = isset($_POST['product_code']) ? sizeof($_POST['product_code']) : 0;
        for ($r = 0; $r < $i; $r++) {
            $item_id            = $_POST['product_id'][$r];
            $item_type          = $_POST['product_type'][$r];
            $item_code          = $_POST['product_code'][$r];
            $item_name          = $_POST['product_name'][$r];
            $item_option        = isset($_POST['product_option'][$r]) && $_POST['product_option'][$r] != 'false' ? $_POST['product_option'][$r] : NULL;
            $real_unit_price    = $this->erp->formatDecimal($_POST['real_unit_price'][$r]);
            $unit_price         = $this->erp->formatDecimal($_POST['unit_price'][$r]);
            $item_quantity      = $_POST['quantity'][$r];
            $item_serial        = isset($_POST['serial'][$r]) ? $_POST['serial'][$r] : '';
            $item_tax_rate      = isset($_POST['product_tax'][$r]) ? $_POST['product_tax'][$r] : NULL;
            $item_discount      = isset($_POST['product_discount'][$r]) ? $_POST['product_discount'][$r] : NULL;

            if (isset($item_code) && isset($real_unit_price) && isset($unit_price) && isset($item_quantity)) {
                $product_details    = $item_type != 'manual' ? $this->pos_model->getProductByCode($item_code) : NULL;
                $unit_price         = $real_unit_price;
                $pr_discount        = 0;

                if (isset($item_discount)) {
                    $discount   = $item_discount;
                    $dpos       = strpos($discount, $percentage);
                    if ($dpos !== false) {
                        $pds    = explode("%", $discount);
                        $pr_discount = (($this->erp->formatDecimal($unit_price)) * (Float)($pds[0])) / 100;
                    } else {
                        $pr_discount = $this->erp->formatDecimal($discount);
                    }
                }

                $unit_price         = $this->erp->formatDecimal($unit_price - $pr_discount);
                $item_net_price     = $unit_price;
                $pr_item_discount   = $this->erp->formatDecimal($pr_discount * $item_quantity);
                $product_discount   += $pr_item_discount;
                $pr_tax             = 0; $pr_item_tax = 0; $item_tax = 0; $tax = "";

                if (isset($item_tax_rate) && $item_tax_rate != 0) {
                    $pr_tax = $item_tax_rate;
                    $tax_details = $this->site->getTaxRateByID($pr_tax);
                    if ($tax_details->type == 1 && $tax_details->rate != 0) {

                        if ($product_details && $product_details->tax_method == 1) {
                            $item_tax   = $this->erp->formatDecimal((($unit_price) * $tax_details->rate) / 100);
                            $tax        = $tax_details->rate . "%";
                        } else {
                            $item_tax       = $this->erp->formatDecimal((($unit_price) * $tax_details->rate) / (100 + $tax_details->rate));
                            $tax            = $tax_details->rate . "%";
                            $item_net_price = $unit_price - $item_tax;
                        }

                    } elseif ($tax_details->type == 2) {

                        if ($product_details && $product_details->tax_method == 1) {
                            $item_tax   = $this->erp->formatDecimal((($unit_price) * $tax_details->rate) / 100);
                            $tax        = $tax_details->rate . "%";
                        } else {
                            $item_tax       = $this->erp->formatDecimal((($unit_price) * $tax_details->rate) / (100 + $tax_details->rate));
                            $tax            = $tax_details->rate . "%";
                            $item_net_price = $unit_price - $item_tax;
                        }

                        $item_tax = $this->erp->formatDecimal($tax_details->rate);
                        $tax = $tax_details->rate;

                    }
                    $pr_item_tax = $this->erp->formatDecimal($item_tax * $item_quantity);

                }

                $product_tax   += $pr_item_tax;
                $subtotal       = (($item_net_price * $item_quantity) + $pr_item_tax);
                $row            = $this->pos_model->getWHProduct($item_code, $warehouse_id);
				$printed        = $this->pos_model->printed_update($suspend_,$item_code);
           
				

                $products[] = array(
                    'product_id'        => $item_id,
                    'product_code'      => $item_code,
                    'product_name'      => $item_name,
                    'product_type'      => $item_type,
                    'option_id'         => $item_option,
                    'net_unit_price'    => $item_net_price,
                    'unit_price'        => $this->erp->formatDecimal($item_net_price + $item_tax),
                    'quantity'          => $item_quantity,
                    'warehouse_id'      => $warehouse_id,
                    'item_tax'          => $pr_item_tax,
                    'tax_rate_id'       => $pr_tax,
                    'tax'               => $tax,
					'printed'           => $printed->printed,
                    'discount'          => $item_discount,
                    'item_discount'     => $pr_item_discount,
                    'subtotal'          => $this->erp->formatDecimal($subtotal),
                    'serial_no'         => $item_serial,
                    'real_unit_price'   => $real_unit_price
                );

                $total += $item_net_price * $item_quantity;
            }
        }
        $susp_bill_arr = array(
                        'count' => $i,
                        'total' => $total
                        );

        if($this->pos_model->suspendItem_($susp_bill_arr, $suspend_, $products)){
            $pr = array('total_items' => $i, 'sub_total' => $total, 'image_' => $item_image);
            exit(json_encode($pr));
        }
        exit('fail');
    }
	
	function getCustomerInfo(){
		$cus_id         = $this->input->get('customer_id');
		$customer_info  = $this->pos_model->getCustomerByID($cus_id);
        exit(json_encode($customer_info));
	}

    function getProductDataByCode($code = NULL, $warehouse_id = NULL, $cust_id = null, $suspend_id = null, $item_rows = null, $sub_total = null)
    {
		
		$this->load->model('sales_model');
        $suspend_id = $this->input->get('suspend_id');
        $item_rows  = $this->input->get('item_rows');
        $sub_total  = $this->input->get('sub_total');

        $this->erp->checkPermissions('index');
        if ($this->input->get('code')) {
            $code = $this->input->get('code', TRUE);
        }
        if ($this->input->get('warehouse_id')) {
            $warehouse_id = $this->input->get('warehouse_id', TRUE);
        }
        if ($this->input->get('customer_id')) {
            $customer_id = $this->input->get('customer_id', TRUE);
        }
        if (!$code) {
            echo NULL;
            die();
        }

        $customer           = $this->site->getCompanyByID($customer_id);
        $customer_group     = $this->site->getCustomerGroupByID($customer->customer_group_id);
        $row                = $this->pos_model->getWHProduct($code, $warehouse_id);
	
		$orderqty           = $this->pos_model->getQtyOrder($row->product_id);
		$w_piece            = $this->sales_model->getProductVariantByOptionID($row->id);
		$group_prices       = $this->sales_model->getProductPriceGroup($row->id,$customer->price_group_id);
        $option             = '';
		$expiry_status      = 0;
		if($this->site->get_setting()->product_expiry == 1){
			$expiry_status = 1;
		}
		
        if($row){
			
            $combo_items            = FALSE;
            $row->item_tax_method   = $row->tax_method;
            $row->qty               = 1;
            $row->discount          = '0';
            $row->serial            = '';
            $options                = $this->pos_model->getProductOptions($row->id, $warehouse_id);
			
			if($expiry_status == 1) {
				$expdates = $this->sales_model->getProductExpireDate($row->id, $warehouse_id);
			}else{
				$expdates = NULL;
			}
			
			if ($options) {
				$opt = $options[count($options)-1];
				if (!$option) {
					$option = $opt->id;
					$optqty = $opt->qty_unit;
				}
			} else {
				$opt = json_decode('{}');
				$opt->price = 0;
			}
			
            $row->option = $option;
			if($expiry_status == 1 && $expdates != NULL){
				$row->expdate = $expdates[0]->id;
			}else{
				$row->expdate = NULL;
			}
			
			if($row->subcategory_id)
			{
				$percent = $this->sales_model->getCustomerMakup($customer->customer_group_id,$row->id,1);
			}else{
				$percent = $this->sales_model->getCustomerMakup($customer->customer_group_id,$row->id,0);
			}
			$setting = $this->sales_model->getSettings();
			
            if ($opt->price != 0) {
				if($customer_group->makeup_cost == 1 && $percent!=""){
					if($setting->attributes==1)
					{
						if(isset($percent->percent)) {
							$row->price = ($row->cost*$opt->qty_unit)  + ((($row->cost*$opt->qty_unit)  * (isset($percent->percent)?$percent->percent:0)) / 100);
						}else {
							$row->price = $opt->price + (($opt->price * $customer_group->percent) / 100);
						}
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

				if($customer_group->makeup_cost == 1){
					//$row->price = $row->cost + (($row->cost * $customer_group->percent) / 100);
					$row->price = $row->cost + (($row->cost * (isset($percent->percent)?$percent->percent:0)) / 100);

				}else{
					//$row->price = $group_prices[0]->price;
					$row->price = $group_prices[0]->price + (($group_prices[0]->price * $customer_group->percent) / 100);
				}
			}else{
				$row->price_id = 0;
			}

            $row->quantity      = 0;
            $pis                = $this->pos_model->getPurchasedItems($row->id, $warehouse_id, $row->option);
			
			$group_prices       = $this->sales_model->getProductPriceGroupId($row->id, $customer->price_group_id);
			$all_group_prices   = $this->sales_model->getProductPriceGroup($row->id, $customer->price_group_id);
			$row->price_id      = $all_group_prices[0]->id;
			
            if($pis){
				foreach ($pis as $pi) {
                    $row->quantity += $pi->quantity_balance;
                }
            }

            if ($options) {
                $option_quantity = 0;
                foreach ($options as $option) {
                    $pis = $this->pos_model->getPurchasedItems($row->id, $warehouse_id, $row->option);
                    if($pis){
                        foreach ($pis as $pi) {
                            $option_quantity += $pi->quantity_balance;
                        }
                    }
                    if($option->quantity > $option_quantity) {
                        $option->quantity = $option_quantity;
                    }
                }
            }

            $row->real_unit_price   = $row->price;
			$row->piece	            = 0;
			$row->wpiece            = 0;
			$row->digital_id	    = 0;
			$row->digital_code	    = '';
			$row->digital_name	    = '';
			$row->w_piece           = $row->cf1;
			$row->printed           = 0;
            $combo_items            = FALSE;
			$customer_percent       = $customer_group ? $customer_group->percent : 0;
            if ($row->tax_rate){
                $tax_rate = $this->site->getTaxRateByID($row->tax_rate);
                if ($row->type == 'combo') {
                    $combo_items = $this->pos_model->getProductComboItems($row->id, $warehouse_id);
                }
                $pr = array(
                    'id'                    => str_replace(".", "", microtime(true)),
                    'item_id'               => $row->code,
                    'pro_id' => $row->id,
                    'label'                 => $row->name . " (" . $row->code . ")",
                    'image'                 => $row->image,
                    'cost'                  => $row->cost,
                    'row'                   => $row,
                    'combo_items'           => $combo_items,
                    'tax_rate'              => $tax_rate,
                    'options'               => $options,
                    'expdates'              => $expdates,
                    'item_price'            => $row->price,
                    'orderqty'              =>($orderqty?$orderqty->quantity:0),
                    'group_prices'          => $group_prices,
                    'all_group_prices'      => $all_group_prices,
                    'makeup_cost'           => ($customer_group ? $customer_group->makeup_cost : 0),
                    'customer_percent'      => $customer_percent,
                    'makeup_cost_percent'   => $percent?$percent->percent:0
                );
           }else {
                $pr = array(
                    'id'                    => str_replace(".", "", microtime(true)),
                    'item_id'               => $row->code,
                    'pro_id' => $row->id,
                    'label'                 => $row->name . " (" . $row->code . ")",
                    'image'                 => $row->image,
                    'row'                   => $row,
                    'combo_items'           => $combo_items,
                    'tax_rate'              => false,
                    'options'               => $options,
                    'expdates'              => $expdates,
                    'item_price'            => $row->price,
                    'orderqty'              => $orderqty->quantity,
                    'group_prices'          => $group_prices,
                    'all_group_prices'      => $all_group_prices,
                    'makeup_cost'           => ($customer_group ? $customer_group->makeup_cost : 0),
                    'customer_percent'      => $customer_percent,
                    'makeup_cost_percent'   => $percent->percent
                );
           }
            //$this->erp->print_arrays($pr);
           echo json_encode($pr);
        } else {
			
            echo NULL;
			
        }
    }

	function getProductSearchByCode($code = NULL, $warehouse_id = NULL, $cust_id = null, $suspend_id = null, $item_rows = null, $sub_total = null)
    {
		$data       = '';
        $suspend_id = $this->input->get('suspend_id');
        $item_rows  = $this->input->get('item_rows');
        $sub_total  = $this->input->get('sub_total');

        $this->erp->checkPermissions('index');
        if ($this->input->get('code')) {
            $code = $this->input->get('code', TRUE);
        }
        if ($this->input->get('warehouse_id')) {
            $warehouse_id = $this->input->get('warehouse_id', TRUE);
        }
        if ($this->input->get('customer_id')) {
            $customer_id = $this->input->get('customer_id', TRUE);
        }
        if (!$code) {
            echo NULL;
            die();
        }

        $customer       = $this->site->getCompanyByID($customer_id);
        $customer_group = $this->site->getCustomerGroupByID($customer->customer_group_id);
        $rows           = $this->pos_model->getSESuspend($code, $warehouse_id);
		foreach($rows as $id){
			$array[] = $id['product_id'];
			
		}
		$data_=implode(',',$array);
		$result = $this->pos_model->getSEProduct($data_, $warehouse_id);
		
		
        $option = '';
        $errors = array_filter($result);
        if (!empty($errors)) {
			foreach($result as $row){
				$combo_items = FALSE;
				$row->item_tax_method = $row->tax_method;
				$row->qty = 1;
				$row->discount = '0';
				$row->serial = '';
				$options = $this->pos_model->getProductOptions($row->id, $warehouse_id);
				if ($options) {
					$opt = current($options);
					if (!$option) {
						$option = $opt->id;
					}
				} else {
					$opt = json_decode('{}');
					$opt->price = 0;
				}
				$row->option = $option;
				if ($opt->price != 0) {
					$row->price = $opt->price + (($opt->price * $customer_group->percent) / 100);
				} else {
					$row->price = $row->price + (($row->price * $customer_group->percent) / 100);
				}
				$row->quantity = 0;
				$pis = $this->pos_model->getPurchasedItems($row->id, $warehouse_id, $row->option);
				if($pis){
					foreach ($pis as $pi) {
						$row->quantity += $pi->quantity_balance;
					}
				}
				if ($options) {
					$option_quantity = 0;
					foreach ($options as $option) {
						$pis = $this->pos_model->getPurchasedItems($row->id, $warehouse_id, $row->option);
						if($pis){
							foreach ($pis as $pi) {
								$option_quantity += $pi->quantity_balance;
							}
						}
						if($option->quantity > $option_quantity) {
							$option->quantity = $option_quantity;
						}
					}
				}
				$row->piece	  = 0;
				$row->wpiece  = 0;
				$row->w_piece = $row->cf1;
				$row->real_unit_price = $row->price;
				
				$combo_items = FALSE;
				if ($row->tax_rate) {
					$tax_rate = $this->site->getTaxRateByID($row->tax_rate);
					if ($row->type == 'combo') {
						$combo_items = $this->pos_model->getProductComboItems($row->id, $warehouse_id);
					}
					$pr[] = array('id' => str_replace(".", "", microtime(true)), 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")",'image' => $row->image, 'row' => $row, 'combo_items' => $combo_items, 'tax_rate' => $tax_rate, 'options' => $options, 'item_price' => $row->price);
				} else {
					$pr[] = array('id' => str_replace(".", "", microtime(true)), 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")",'image' => $row->image, 'row' => $row, 'combo_items' => $combo_items, 'tax_rate' => false, 'options' => $options, 'item_price' => $row->price);
				}

			}

            echo json_encode($pr);
        } else {
			
            echo NULL;
			
        }
    }

	
    function ajaxproducts()
    {
        $this->erp->checkPermissions('index');
        if ($this->input->get('category_id')) {
            $category_id    = $this->input->get('category_id');
        }
        /*else {
            $category_id    = $this->pos_settings->default_category;
        }*/
        if ($this->input->get('subcategory_id')) {
            $subcategory_id = $this->input->get('subcategory_id');
        } else {
            $subcategory_id = NULL;
        }
        if ($this->input->get('per_page') == 'n') {
            $page           = 0;
        } else {
            $page           = $this->input->get('per_page');
        }

        $this->load->library("pagination");

        $config                     = array();
        $config["base_url"]         = base_url() . "pos/ajaxproducts";
        $config["total_rows"]       = $subcategory_id ? $this->pos_model->products_count($category_id, $subcategory_id) : $this->pos_model->products_count($category_id);
        $config["per_page"]         = $this->pos_settings->pro_limit;
        $config['prev_link']        = FALSE;
        $config['next_link']        = FALSE;
        $config['display_pages']    = FALSE;
        $config['first_link']       = FALSE;
        $config['last_link']        = FALSE;

        $this->pagination->initialize($config);

        $products = $subcategory_id ? $this->pos_model->fetch_products_permission($category_id, $config["per_page"], $page, $subcategory_id) : $this->pos_model->fetch_products_permission($category_id, $config["per_page"], $page);
        $pro = 1;
		$i=1;
        $prods = '<div  id=box-item>';
        if ( ! empty($products)) {
            foreach ($products as $product) {
                $count = $product->id;
                if ($count < 10) {
                    $count = "0" . ($count / 100) * 100;
                }
                if ($category_id < 10) {
                    $category_id = "0" . ($category_id / 100) * 100;
                }
				$class  = '';
                $font   = '';
				$width 	= $this->Settings->twidth;
				$height = $this->Settings->theight;
				if ($this->pos_settings->pos_layout == 6) {
					$width 	= 150;
					$height = 150;
					$class  = 'btn-prni6';
                    $font   = 'font-size:20px;';
				}

                $prods .= "<button id=\"product-" . $category_id . $count . "\" type=\"button\" value='" . $product->code . "' title=\"" . $product->name . "\" class=\"btn-prni btn-" . $this->pos_settings->product_button_color . " product pos-tip " .$class. " \" data-container=\"body\"><img src=\"" . base_url() . "assets/uploads/thumbs/" . $product->image . "\" alt=\"" . $product->name .'<br>' . "\" style='width:" . $width . "px;height:" . $height . "px;' class='img-rounded' /><p style='margin: 0; ".$font." '>" . character_limiter($product->code, 40) . "</p><p style='margin: 0; ".$font." '>". character_limiter($product->name, 40) ."</p></button>";
                $pro++;
            }
        }
        $prods .= "</div>";

        if ($this->input->get('per_page')) {
            echo $prods;
        } else {
            return $prods;
        }
    }

    function ajaxcategorydata($category_id = NULL)
    {
        $this->erp->checkPermissions('index');
        if ($this->input->get('category_id')) {
            $category_id = $this->input->get('category_id');
        } else {
            $category_id = $this->pos_settings->default_category;
        }

        $subcategories  = $this->pos_model->getSubCategoriesByCategoryID($category_id);
        $scats          = '';
        if($subcategories) {
            foreach ($subcategories as $category) {
                $scats .= "<button id=\"subcategory-" . $category->id . "\" type=\"button\" value='" . $category->id . "' class=\"btn-prni subcategory\" ><img src=\"assets/uploads/thumbs/" . ($category->image ? $category->image : 'no_image.png') . "\" style='width:" . $this->Settings->twidth . "px;height:" . $this->Settings->theight . "px;' class='img-rounded img-thumbnail' /><span>" . $category->name . "</span></button>";
			}
        }

        $products       = $this->ajaxproducts($category_id);

        if (!($tcp      = $this->pos_model->products_count($category_id))) {
            $tcp        = 0;
        }

        echo json_encode(array('products' => $products, 'subcategories' => $scats, 'tcp' => $tcp));
    }

    /* ------------------------------------------------------------------------------------ */
    function view_cabon($sale_id = NULL, $modal = NULL)
    {
        $this->erp->checkPermissions('index');
        if ($this->input->get('id')) {
            $sale_id = $this->input->get('id');
        }
		
        $this->load->helper('text');
        $this->data['error']                = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['message']              = $this->session->flashdata('message');
        $this->data['rows']                 = $this->pos_model->getAllInvoiceItems($sale_id);
        $inv                                = $this->pos_model->getInvoicePosByID($sale_id);
        $biller_id                          = $inv->biller_id;
        $customer_id                        = $inv->customer_id;
        $this->data['biller']               = $this->pos_model->getCompanyByID($biller_id);
        $this->data['customer']             = $this->pos_model->getCompanyByID($customer_id);
        $this->data['payments']             = $this->pos_model->getInvoicePaymentsPOS($sale_id);
        $this->data['pos']                  = $this->pos_model->getSetting();
        $this->data['barcode']              = $this->barcode($inv->reference_no, 'code39', 30);
        $this->data['inv']                  = $inv;
		//$this->erp->print_arrays($inv);
        $this->data['sid']                  = $sale_id;
		$this->data['exchange_rate']        = $this->pos_model->getExchange_rate();
		$this->data['outexchange_rate']     = $this->pos_model->getExchange_rate('KHM_o');
		$this->data['exchange_rate_th']     = $this->pos_model->getExchange_rate('THA');
		$this->data['exchange_rate_kh_c']   = $this->pos_model->getExchange_rate('KHM');
		$this->data['exchange_rate_kh']     = $this->pos_model->getExchange_rates();
        $this->data['modal']                = $modal;
        $this->data['page_title']           = $this->lang->line("invoice");
        $this->load->view($this->theme . 'pos/tree_try_invoice', $this->data);
    }

    function cabon_siv_heng($sale_id = NULL, $modal = NULL)
    {
        $this->erp->checkPermissions('index');
        if ($this->input->get('id')) {
            $sale_id = $this->input->get('id');
        }
        
        $this->load->helper('text');
        $this->data['error']                = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['message']              = $this->session->flashdata('message');
        $this->data['rows']                 = $this->pos_model->getAllInvoiceItems($sale_id);
        $inv                                = $this->pos_model->getInvoicePosByID($sale_id);
        $biller_id                          = $inv->biller_id;
        $customer_id                        = $inv->customer_id;
        $this->data['biller']               = $this->pos_model->getCompanyByID($biller_id);
        $this->data['customer']             = $this->pos_model->getCompanyByID($customer_id);
        $this->data['payments']             = $this->pos_model->getInvoicePaymentsPOS($sale_id);
        $this->data['pos']                  = $this->pos_model->getSetting();
        $this->data['barcode']              = $this->barcode($inv->reference_no, 'code39', 30);
        $this->data['inv']                  = $inv;
        //$this->erp->print_arrays($inv);
        $this->data['sid']                  = $sale_id;
        $this->data['exchange_rate']        = $this->pos_model->getExchange_rate();
        $this->data['outexchange_rate']     = $this->pos_model->getExchange_rate('KHM_o');
        $this->data['exchange_rate_th']     = $this->pos_model->getExchange_rate('THA');
        $this->data['exchange_rate_kh_c']   = $this->pos_model->getExchange_rate('KHM');
        $this->data['exchange_rate_kh']     = $this->pos_model->getExchange_rates();
        $this->data['modal']                = $modal;
        $this->data['page_title']           = $this->lang->line("invoice");
        $this->load->view($this->theme . 'pos/siv_heng_invoice', $this->data);
    }
    
    function jones_invoice($sale_id = NULL, $modal = NULL){
        $this->erp->checkPermissions('index');
        if ($this->input->get('id')) {
            $sale_id = $this->input->get('id');
        }
        
        $this->load->helper('text');
        $this->data['error']                = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['message']              = $this->session->flashdata('message');
        $this->data['rows']                 = $this->pos_model->getAllInvoiceItems($sale_id);
        $inv                                = $this->pos_model->getInvoicePosByID($sale_id);
        // $this->erp->print_arrays($inv);
        $biller_id                          = $inv->biller_id;
        $customer_id                        = $inv->customer_id;
        $this->data['biller']               = $this->pos_model->getCompanyByID($biller_id);
        $this->data['customer']             = $this->pos_model->getCompanyByID($customer_id);
        $this->data['payments']             = $this->pos_model->getInvoicePaymentsPOS($sale_id);

        $this->data['pos']                  = $this->pos_model->getSetting();
        $this->data['barcode']              = $this->barcode($inv->reference_no, 'code39', 30);
        $this->data['inv']                  = $inv;
        //$this->erp->print_arrays($inv);
        $this->data['sid'] = $sale_id;
        $this->data['exchange_rate']        = $this->pos_model->getExchange_rate();
        $this->data['outexchange_rate']     = $this->pos_model->getExchange_rate('KHM_o');
        $this->data['exchange_rate_th']     = $this->pos_model->getExchange_rate('THA');
        $this->data['exchange_rate_kh_c']   = $this->pos_model->getExchange_rate('KHM');
        $this->data['exchange_rate_kh']     = $this->pos_model->getExchange_rates();
        $this->data['modal']                = $modal;
        $this->data['page_title']           = $this->lang->line("invoice");
        $this->load->view($this->theme . 'pos/jones_invoice',$this->data);
    }

    function maman_invoice($sale_id = NULL, $modal = NULL){
        $this->erp->checkPermissions('index');
        if ($this->input->get('id')) {
            $sale_id = $this->input->get('id');
        }

        $this->load->helper('text');
        $this->data['error']                = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['message']              = $this->session->flashdata('message');
        $this->data['rows']                 = $this->pos_model->getAllInvoiceItems($sale_id);
        $inv                                = $this->pos_model->getInvoicePosByID($sale_id);
        //$this->erp->print_arrays($inv);
        $biller_id                          = $inv->biller_id;
        $customer_id                        = $inv->customer_id;
        $this->data['biller']               = $this->pos_model->getCompanyByID($biller_id);
        $this->data['customer']             = $this->pos_model->getCompanyByID($customer_id);
        $this->data['payments']             = $this->pos_model->getInvoicePaymentsPOS($sale_id);

        $this->data['pos']                  = $this->pos_model->getSetting();
        $this->data['barcode']              = $this->barcode($inv->reference_no, 'code39', 30);
        $this->data['inv']                  = $inv;
        //$this->erp->print_arrays($inv);
        $this->data['sid'] = $sale_id;
        $this->data['exchange_rate']        = $this->pos_model->getExchange_rate();
        $this->data['outexchange_rate']     = $this->pos_model->getExchange_rate('KHM_o');
        $this->data['exchange_rate_th']     = $this->pos_model->getExchange_rate('THA');
        $this->data['exchange_rate_kh_c']   = $this->pos_model->getExchange_rate('KHM');
        $this->data['exchange_rate_kh']     = $this->pos_model->getExchange_rates();
        $this->data['modal']                = $modal;
        $this->data['page_title']           = $this->lang->line("invoice");
        $this->load->view($this->theme . 'pos/maman_invoice',$this->data);
    }

    function view_dragon_fly($sale_id = NULL, $modal = NULL){
        // echo "Dragon Fly";exit();
        $this->erp->checkPermissions('index');
        if ($this->input->get('id')) {
            $sale_id = $this->input->get('id');
        }
        
        $this->load->helper('text');
        $this->data['error']                = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['message']              = $this->session->flashdata('message');
        $this->data['rows']                 = $this->pos_model->getAllInvoiceItems($sale_id);
        $inv                                = $this->pos_model->getInvoicePosByID($sale_id);
        // $this->erp->print_arrays($inv);
        $biller_id                          = $inv->biller_id;
        $customer_id                        = $inv->customer_id;
        $this->data['biller']               = $this->pos_model->getCompanyByID($biller_id);
        $this->data['customer']             = $this->pos_model->getCompanyByID($customer_id);
        $this->data['payments']             = $this->pos_model->getInvoicePaymentsPOS($sale_id);
        $this->data['pos']                  = $this->pos_model->getSetting();
        $this->data['barcode']              = $this->barcode($inv->reference_no, 'code39', 30);
        $this->data['inv']                  = $inv;
        //$this->erp->print_arrays($inv);
        $this->data['sid']                  = $sale_id;
        $this->data['exchange_rate']        = $this->pos_model->getExchange_rate();
        $this->data['outexchange_rate']     = $this->pos_model->getExchange_rate('KHM_o');
        $this->data['exchange_rate_th']     = $this->pos_model->getExchange_rate('THA');
        $this->data['exchange_rate_kh_c']   = $this->pos_model->getExchange_rate('KHM');
        $this->data['exchange_rate_kh']     = $this->pos_model->getExchange_rates();
        $this->data['modal']                = $modal;
        $this->data['page_title']           = $this->lang->line("invoice");
        $this->load->view($this->theme . 'pos/dragon_fly_invoice',$this->data);
    }

    function view($sale_id = NULL, $modal = NULL)
    {
        $this->erp->checkPermissions('index');
        if ($this->input->get('id')) {
            $sale_id = $this->input->get('id');
        }
		
        $this->load->helper('text');
        $this->data['error'] 				= (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['message'] 				= $this->session->flashdata('message');
        $this->data['rows'] 				= $this->pos_model->getAllInvoiceItems($sale_id);
        $inv 								= $this->pos_model->getInvoicePosByID($sale_id);
        $biller_id 							= $inv->biller_id;
        $customer_id 						= $inv->customer_id;
        $this->data['biller'] 				= $this->pos_model->getCompanyByID($biller_id);
        $this->data['customer'] 			= $this->pos_model->getCompanyByID($customer_id);
        $this->data['payments'] 			= $this->pos_model->getInvoicePaymentsPOS($sale_id);
        $this->data['pos'] 					= $this->pos_model->getSetting();
        $this->data['barcode'] 				= $this->barcode($inv->reference_no, 'code39', 30);
        $this->data['inv'] 					= $inv;
        $this->data['sid'] 					= $sale_id;
		$this->data['exchange_rate'] 		= $this->pos_model->getExchange_rate();
		$this->data['outexchange_rate'] 	= $this->pos_model->getExchange_rate('KHM_o');
		$this->data['exchange_rate_th'] 	= $this->pos_model->getExchange_rate('THA');
		$this->data['exchange_rate_kh_c'] 	= $this->pos_model->getExchange_rate('KHM');
        $this->data['modal'] 				= $modal;
        $this->data['page_title'] 			= $this->lang->line("invoice");
        $this->load->view($this->theme . 'pos/view', $this->data);
    }
	
	function invoice_ktv($sale_id = NULL, $modal = NULL)
    {
        $this->erp->checkPermissions('index');
        if ($this->input->get('id')) {
            $sale_id = $this->input->get('id');
        }
        $this->load->helper('text');
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['message'] = $this->session->flashdata('message');
        $this->data['rows'] = $this->pos_model->getAllInvoiceItems($sale_id);
        $inv = $this->pos_model->getInvoicePosByID($sale_id);
        $biller_id = $inv->biller_id;
        $customer_id = $inv->customer_id;
        $this->data['biller'] = $this->pos_model->getCompanyByID($biller_id);
        $this->data['customer'] = $this->pos_model->getCompanyByID($customer_id);
        $this->data['payments'] = $this->pos_model->getInvoicePaymentsPOS($sale_id);
        $this->data['pos'] = $this->pos_model->getSetting();
        $this->data['barcode'] = $this->barcode($inv->reference_no, 'code39', 30);
        $this->data['inv'] = $inv;
        $this->data['sid'] = $sale_id;
		$this->data['exchange_rate'] = $this->pos_model->getExchange_rate();
		$this->data['outexchange_rate'] = $this->pos_model->getExchange_rate('KHM_o');
		$this->data['exchange_rate_th'] = $this->pos_model->getExchange_rate('THA');
		$this->data['exchange_rate_kh_c'] = $this->pos_model->getExchange_rate('KHM');
        $this->data['modal'] = $modal;
        $this->data['page_title'] = $this->lang->line("invoice");
        $this->load->view($this->theme . 'pos/invoice_ktv', $this->data);
    }

    function chp_invoice($sale_id = NULL, $modal = NULL)
    {
        $this->erp->checkPermissions('index');
        if ($this->input->get('id')) {
            $sale_id = $this->input->get('id');
        }
        
        $this->load->helper('text');
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['message'] = $this->session->flashdata('message');
        $this->data['rows'] = $this->pos_model->getAllInvoiceItems($sale_id);
        $inv = $this->pos_model->getInvoicePosByID($sale_id);
        // $this->erp->print_arrays($inv);
        $biller_id = $inv->biller_id;
        $customer_id = $inv->customer_id;
        $this->data['biller'] = $this->pos_model->getCompanyByID($biller_id);
        $this->data['customer'] = $this->pos_model->getCompanyByID($customer_id);
        // $this->erp->print_arrays($this->pos_model->getCompanyByID($customer_id));
        $this->data['payments'] = $this->pos_model->getInvoicePaymentsPOS($sale_id);
        // $this->erp->print_arrays($this->pos_model->getInvoicePaymentsPOS($sale_id));
        $this->data['pos'] = $this->pos_model->getSetting();
        $this->data['barcode'] = $this->barcode($inv->reference_no, 'code39', 30);
        $this->data['inv'] = $inv;
        //$this->erp->print_arrays($inv);
        $this->data['sid'] = $sale_id;
        $this->data['exchange_rate'] = $this->pos_model->getExchange_rate();
        $this->data['outexchange_rate'] = $this->pos_model->getExchange_rate('KHM_o');
        $this->data['exchange_rate_th'] = $this->pos_model->getExchange_rate('THA');
        $this->data['exchange_rate_kh_c'] = $this->pos_model->getExchange_rate('KHM');
        $this->data['modal'] = $modal;
        $this->data['page_title'] = $this->lang->line("invoice");
        $this->load->view($this->theme . 'pos/chp_invoice', $this->data);
    }

	function cabon_print($sale_id = NULL, $modal = NULL)
    {
        $this->erp->checkPermissions('index');
        if ($this->input->get('id')) {
            $sale_id = $this->input->get('id');
        }
        $this->load->helper('text');
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['message'] = $this->session->flashdata('message');
        $this->data['rows'] = $this->pos_model->getAllInvoiceItems($sale_id);
        $inv = $this->pos_model->getInvoiceByID($sale_id);
        $biller_id = $inv->biller_id;
        $customer_id = $inv->customer_id;
        $this->data['biller'] = $this->pos_model->getCompanyByID($biller_id);
        $this->data['customer'] = $this->pos_model->getCompanyByID($customer_id);
        $this->data['payments'] = $this->pos_model->getInvoicePaymentsPOS($sale_id);
        $this->data['pos'] = $this->pos_model->getSetting();
        $this->data['barcode'] = $this->barcode($inv->reference_no, 'code39', 30);
        $this->data['inv'] = $inv;
        $this->data['sid'] = $sale_id;
		$this->data['exchange_rate'] = $this->pos_model->getExchange_rate();
		$this->data['exchange_rate_th'] = $this->pos_model->getExchange_rate('THA');
		$this->data['exchange_rate_kh_c'] = $this->pos_model->getExchange_rate('KHM');
        $this->data['modal'] = $modal;
        $this->data['page_title'] = $this->lang->line("invoice");
        $this->load->view($this->theme . 'pos/cabon_print', $this->data);
    }

    function register_details()
    {
        $this->erp->checkPermissions('index');
        $register_open_time = $this->session->userdata('register_open_time');
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['ccsales'] = $this->pos_model->getRegisterCCSales($register_open_time);
        $this->data['cashsales'] = $this->pos_model->getRegisterCashSales($register_open_time);
        $this->data['chsales'] = $this->pos_model->getRegisterChSales($register_open_time);
        $this->data['pppsales'] = $this->pos_model->getRegisterPPPSales($register_open_time);
        $this->data['stripesales'] = $this->pos_model->getRegisterStripeSales($register_open_time);
        $this->data['totalsales'] = $this->pos_model->getRegisterSales($register_open_time);
        $this->data['refunds'] = $this->pos_model->getRegisterRefunds($register_open_time);
        $this->data['expenses'] = $this->pos_model->getRegisterExpenses($register_open_time);
        $this->load->view($this->theme . 'pos/register_details', $this->data);
    }

    function today_sale()
    {
        if (!$this->Owner && !$this->Admin) {
            $this->session->set_flashdata('error', lang('access_denied'));
            $this->erp->md();
        }

        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['ccsales'] = $this->pos_model->getTodayCCSales();
        $this->data['cashsales'] = $this->pos_model->getTodayCashSales();
        $this->data['chsales'] = $this->pos_model->getTodayChSales();
        $this->data['pppsales'] = $this->pos_model->getTodayPPPSales();
        $this->data['stripesales'] = $this->pos_model->getTodayStripeSales();
        $this->data['totalsales'] = $this->pos_model->getTodaySales();
        $this->data['refunds'] = $this->pos_model->getTodayRefunds();
        $this->data['expenses'] = $this->pos_model->getTodayExpenses();
        $this->load->view($this->theme . 'pos/today_sale', $this->data);
    }
	
	function add_deliveries($start_date = NULL, $end_date = NULL)
    {
        $this->erp->checkPermissions('add_delivery',NULL,'sales');
		if (!$start_date) {
            //$start = $this->db->escape(date('Y-m') . '-1');
           // $start_date = date('Y-m') . '-1';
        } else {
            $start = $this->db->escape(urldecode($start_date));
        }
        if (!$end_date) {
            //$end = $this->db->escape(date('Y-m-d H:i'));
            //$end_date = date('Y-m-d H:i');
        } else {
            $end = $this->db->escape(urldecode($end_date));
        }

        $data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

		$this->data['start'] = urldecode($start_date);
        $this->data['end'] = urldecode($end_date);

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('sales'), 'page' => lang('sales')), array('link' => '#', 'page' => lang('deliveries')));
        $meta = array('page_title' => lang('deliveries'), 'bc' => $bc);
        $this->load->view('default/views/pos/add_deliveries', $this->data);
    }
	
    function check_pin()
    {
        $pin = $this->input->post('pw', true);
        if ($pin == $this->pos_pin) {
            echo json_encode(array('res' => 1));
        }
        echo json_encode(array('res' => 0));
    }

    function barcode($text = NULL, $bcs = 'code39', $height = 50)
    {
        return site_url('products/gen_barcode/' . $text . '/' . $bcs . '/' . $height);
    }

    function settings()
    {
        if (!$this->Owner) {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect("welcome");
        }
        $this->form_validation->set_message('is_natural_no_zero', $this->lang->line('no_zero_required'));
        $this->form_validation->set_rules('pro_limit', $this->lang->line('pro_limit'), 'required|is_natural_no_zero');
        $this->form_validation->set_rules('pin_code', $this->lang->line('delete_code'), 'numeric');
        $this->form_validation->set_rules('category', $this->lang->line('default_category'), 'required|is_natural_no_zero');
        $this->form_validation->set_rules('customer', $this->lang->line('default_customer'), 'required|is_natural_no_zero');
        $this->form_validation->set_rules('biller', $this->lang->line('default_biller'), 'required|is_natural_no_zero');

        if ($this->form_validation->run() == true) {

            $data = array(
                'pro_limit' => $this->input->post('pro_limit'),
                'pin_code' => $this->input->post('pin_code') ? $this->input->post('pin_code') : NULL,
                'default_category' => $this->input->post('category'),
                'default_customer' => $this->input->post('customer'),
                'default_biller' => $this->input->post('biller'),
                'display_time' => $this->input->post('display_time'),
                'receipt_printer' => $this->input->post('receipt_printer'),
                'cash_drawer_codes' => $this->input->post('cash_drawer_codes'),
                'cf_title1' => $this->input->post('cf_title1'),
                'cf_title2' => $this->input->post('cf_title2'),
                'cf_value1' => $this->input->post('cf_value1'),
                'cf_value2' => $this->input->post('cf_value2'),
                'focus_add_item' => $this->input->post('focus_add_item'),
                'add_manual_product' => $this->input->post('add_manual_product'),
                'customer_selection' => $this->input->post('customer_selection'),
                'add_customer' => $this->input->post('add_customer'),
                'toggle_category_slider' => $this->input->post('toggle_category_slider'),
                'toggle_subcategory_slider' => $this->input->post('toggle_subcategory_slider'),
                'cancel_sale' => $this->input->post('cancel_sale'),
                'suspend_sale' => $this->input->post('suspend_sale'),
                'print_items_list' => $this->input->post('print_items_list'),
                'finalize_sale' => $this->input->post('finalize_sale'),
				'product_unit' => $this->input->post('product_unit'),
				'show_search_item' => $this->input->post('show_search_item'),
                'today_sale' => $this->input->post('today_sale'),
                'open_hold_bills' => $this->input->post('open_hold_bills'),
                'close_register' => $this->input->post('close_register'),
                'discount' => $this->input->post('discount'),
                'tooltips' => $this->input->post('tooltips'),
                'keyboard' => $this->input->post('keyboard'),
                'pos_printers' => $this->input->post('pos_printers'),
                'java_applet' => $this->input->post('enable_java_applet'),
                'product_button_color' => $this->input->post('product_button_color'),
                'paypal_pro' => $this->input->post('paypal_pro'),
                'stripe' => $this->input->post('stripe'),
                'rounding' => $this->input->post('rounding'),
                'show_item_img' => $this->input->post('show_item_img'),
				'pos_layout' => $this->input->post('pos_layout'),
				'show_payment_noted' => $this->input->post('show_payment_noted'),
				'payment_balance' => $this->input->post('payment_balance'),
				'display_qrcode' => $this->input->post('display_qrcode'),
				'show_suspend_bar' => $this->input->post('show_suspend_bar'),
				'payment_balance' => $this->input->post('payment_balance'),
				'show_product_code' => $this->input->post('show_product_code'),
				'auto_delivery' => $this->input->post('auto_delivery'),
				'in_out_rate' => $this->input->post('in_out_rate'),
                'count_cash' => $this->input->post('count_cash')
            );
			
            $payment_config = array(
                'APIUsername' => $this->input->post('APIUsername'),
                'APIPassword' => $this->input->post('APIPassword'),
                'APISignature' => $this->input->post('APISignature'),
                'stripe_secret_key' => $this->input->post('stripe_secret_key'),
                'stripe_publishable_key' => $this->input->post('stripe_publishable_key'),
            );
        } elseif ($this->input->post('update_settings')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect("pos/settings");
        }

        if ($this->form_validation->run() == true && $this->pos_model->updateSetting($data)) {
            if ($this->write_payments_config($payment_config)) {
                $this->session->set_flashdata('message', $this->lang->line('pos_setting_updated'));
                redirect("pos/settings");
            } else {
                $this->session->set_flashdata('error', $this->lang->line('pos_setting_updated_payment_failed'));
                redirect("pos/settings");
            }
        } else {

            $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');

            $this->data['pos'] = $this->pos_model->getSetting();
            $this->data['categories'] = $this->site->getAllCategories();
            $this->data['customer'] = $this->pos_model->getCompanyByID($this->pos_settings->default_customer);
            $this->data['billers'] = $this->pos_model->getAllBillerCompanies();
            $this->config->load('payment_gateways');
            $this->data['stripe_secret_key'] = $this->config->item('stripe_secret_key');
            $this->data['stripe_publishable_key'] = $this->config->item('stripe_publishable_key');
            $this->data['APIUsername'] = $this->config->item('APIUsername');
            $this->data['APIPassword'] = $this->config->item('APIPassword');
            $this->data['APISignature'] = $this->config->item('APISignature');
            $this->data['paypal_balance'] = $this->pos_settings->paypal_pro ? $this->paypal_balance() : NULL;
            $this->data['stripe_balance'] = $this->pos_settings->stripe ? $this->stripe_balance() : NULL;
            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('pos_settings')));
            $meta = array('page_title' => lang('pos_settings'), 'bc' => $bc);
            $this->page_construct('pos/settings', $meta, $this->data);
        }
    }

    public function write_payments_config($config)
    {
        if (!$this->Owner) {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect("welcome");
        }
        $file_contents = file_get_contents('./assets/config_dumps/payment_gateways.php');
        $output_path = APPPATH . 'config/payment_gateways.php';
        $this->load->library('parser');
        $parse_data = array(
            'APIUsername' => $config['APIUsername'],
            'APIPassword' => $config['APIPassword'],
            'APISignature' => $config['APISignature'],
            'stripe_secret_key' => $config['stripe_secret_key'],
            'stripe_publishable_key' => $config['stripe_publishable_key'],
        );
        $new_config = $this->parser->parse_string($file_contents, $parse_data);

        $handle = fopen($output_path, 'w+');
        @chmod($output_path, 0777);

        if (is_writable($output_path)) {
            if (fwrite($handle, $new_config)) {
                @chmod($output_path, 0644);
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function opened_bills($per_page = 0)
    {
        $this->load->library('pagination');

        //$this->table->set_heading('Id', 'The Title', 'The Content');
        if ($this->input->get('per_page')) {
            $per_page = $this->input->get('per_page');
        }

        $config['base_url'] = site_url('pos/opened_bills');
        $config['total_rows'] = $this->pos_model->bills_count();
        $config['per_page'] = 6;
        $config['num_links'] = 3;

        $config['full_tag_open'] = '<ul class="pagination pagination-sm">';
        $config['full_tag_close'] = '</ul>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a>';
        $config['cur_tag_close'] = '</a></li>';

        $this->pagination->initialize($config);
        $data['r'] = TRUE;
        $bills = $this->pos_model->fetch_bills($config['per_page'], $per_page);
        if (!empty($bills)) {
            $html = "";
            $html .= '<ul class="ob">';
            foreach ($bills as $bill) {
                $html .= '<li><button type="button" class="btn btn-info sus_sale" id="' . $bill->id . '"><p>' . $bill->suspend_note . '</p><strong>' . $bill->customer . '</strong><br>Date: ' . $bill->date . '<br>Items: ' . $bill->count . '<br>Total: ' . $this->erp->formatMoney($bill->total) . '</button></li>';
            }
            $html .= '</ul>';
        } else {
            $html = "<h3>" . lang('no_opeded_bill') . "</h3><p>&nbsp;</p>";
            $data['r'] = FALSE;
        }

        $data['html'] = $html;

        $data['page'] = $this->pagination->create_links();
        echo $this->load->view($this->theme . 'pos/opened', $data, TRUE);

    }


    function delete($id = NULL)
    {

        $this->erp->checkPermissions('index');

        if ($this->pos_model->deleteBill($id)) {
            echo lang("suspended_sale_deleted");
        }
    }
	
	function delete_suspend($id = NULL)
    {
        $this->erp->checkPermissions('index');
        if ($this->pos_model->deleteBill($id)) {
            $this->session->set_flashdata('message', lang("suspend_table_was_cleared!"));
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }
	
	function delete_room($id = NULL)
    {

        $this->erp->checkPermissions('index');

        if ($this->pos_model->deleteBillRoom($id)) {
			echo '<div class="alert alert-success gcerror-con" style="display: block;">
                    <button data-dismiss="alert" class="close" type="button">×</button>
                    <span>Suspend Table clear!</span>
                </div>';
            redirect('pos');
        }
    }

	function complete_kitchen($id = NULL){
		$data = array(
			'status' => 1
		);
		//$this->erp->print_arrays($data);
		if($this->pos_model->kitchen_complete($id, $data)){
			redirect('pos/view_kitchen');
		}
	}
	
    function email_receipt($sale_id = NULL)
    {
        $this->erp->checkPermissions('index');
        if ($this->input->post('id')) {
            $sale_id = $this->input->post('id');
        } else {
            die();
        }
        if ($this->input->post('email')) {
            $to = $this->input->post('email');
        }
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['message'] = $this->session->flashdata('message');

        $this->data['rows'] = $this->pos_model->getAllInvoiceItems($sale_id);
        $inv = $this->pos_model->getInvoiceByID($sale_id);
        $biller_id = $inv->biller_id;
        $customer_id = $inv->customer_id;
        $this->data['biller'] = $this->pos_model->getCompanyByID($biller_id);
        $this->data['customer'] = $this->pos_model->getCompanyByID($customer_id);

        $this->data['payments'] = $this->pos_model->getInvoicePayments($sale_id);
        $this->data['pos'] = $this->pos_model->getSetting();
        $this->data['barcode'] = $this->barcode($inv->reference_no, 'code39', 30);
        $this->data['inv'] = $inv;
        $this->data['sid'] = $sale_id;
        $this->data['page_title'] = $this->lang->line("invoice");

        if (!$to) {
            $to = $this->data['customer']->email;
        }
        if (!$to) {
            echo json_encode(array('msg' => $this->lang->line("no_meil_provided")));
        }
        $receipt = $this->load->view($this->theme . 'pos/email_receipt', $this->data, TRUE);

        if ($this->erp->send_email($to, 'Receipt from ' . $this->data['biller']->company, $receipt)) {
            echo json_encode(array('msg' => $this->lang->line("email_sent")));
        } else {
            echo json_encode(array('msg' => $this->lang->line("email_failed")));
        }

    }

    public function active()
    {
        $this->session->set_userdata('last_activity', now());
        if ((now() - $this->session->userdata('last_activity')) <= 20) {
            die('Successfully updated the last activity.');
        } else {
            die('Failed to update last activity.');
        }
    }
	
	function add_payment($id = NULL)
    {
        $this->load->helper('security');
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        //$this->form_validation->set_rules('reference_no', lang("reference_no"), 'required');
        $this->form_validation->set_rules('amount-paid', lang("amount"), 'required');
        $this->form_validation->set_rules('paid_by', lang("paid_by"), 'required');
        $this->form_validation->set_rules('userfile', lang("attachment"), 'xss_clean');
        if ($this->form_validation->run() == true) {
			
            if ($this->Owner || $this->Admin) {
                $date = $this->erp->fld(trim($this->input->post('date')));
            } else {
                $date = date('Y-m-d H:i:s');
            }
			$sale_id = $this->input->post('sale_id');
			$sale_ref = $this->sales_model->getSaleById($sale_id)->reference_no; 
			$paid_by = $this->input->post('paid_by');
			$biller_id = $this->input->post('biller');
			$reference_no = $this->input->post("sale_id");
			
			if($paid_by == "deposit"){
				$payment_reference = $sale_ref;
			}else{
				$payment_reference = (($paid_by == 'deposit')? $this->site->getReference('pay',$biller_id):($this->input->post('reference_no') ? $this->input->post('reference_no') : $this->site->getReference('sp',$biller_id)));
			}
			
			$paid_amount = $this->input->post('amount-paid');
			$customer_id = $this->input->post('customer');
			$customer = '';
			if($customer_id) {
				$customer_details = $this->site->getCompanyByID($customer_id);
				$customer = $customer_details->company ? $customer_details->company : $customer_details->name;
			}
			$note = ($this->input->post('note')? $this->input->post('note'):($customer? $customer:$this->input->post('customer_name')));
			
			$payment = array(
                'date' => $date,
                'sale_id' => $sale_id,
                'reference_no' => $payment_reference,
                'amount' => $paid_amount,
				'pos_paid' => $paid_amount,
                'paid_by' => $paid_by,
                'cheque_no' => $this->input->post('cheque_no'),
                'cc_no' => $paid_by == 'gift_card' ? $this->input->post('gift_card_no') : $this->input->post('pcc_no'),
                'cc_holder' => $this->input->post('pcc_holder'),
                'cc_month' => $this->input->post('pcc_month'),
                'cc_year' => $this->input->post('pcc_year'),
                'cc_type' => $this->input->post('pcc_type'),
                'note' => $note,
                'created_by' => $this->session->userdata('user_id'),
                'type' => 'received',
				'biller_id'	=> $biller_id,
				'deposit_customer_id' => $this->input->post('customer'),
				'add_payment' => '1',
				'bank_account' => $this->input->post('bank_account')
            );
			
			//$this->erp->print_arrays($payment);
			
            if ($_FILES['userfile']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->upload_path;
                $config['allowed_types'] = $this->digital_file_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = FALSE;
                $config['encrypt_name'] = TRUE;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                $payment['attachment'] = $photo;
            }

            //$this->erp->print_arrays($payment);

        } elseif ($this->input->post('add_payment')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }

        if ($this->form_validation->run() == true && $payment_id = $this->sales_model->addPayment($payment)) {
			if($payment_id > 0) {
				if($paid_by == "deposit"){
					$deposits = array(
						'date' => $date,
						'reference' => $payment_reference,
						'company_id' => $customer_id,
						'amount' => (-1) * $paid_amount,
						'paid_by' => $paid_by,
						'note' => $note,
						'created_by' => $this->session->userdata('user_id'),
						'biller_id' => $biller_id,
						'sale_id' => $sale_id,
						'payment_id' => $payment_id,
						'status' => 'paid'
					);
					$this->sales_model->add_deposit($deposits);
				}
			}
				
            $this->session->set_flashdata('message', lang("payment_added"));
            redirect($_SERVER["HTTP_REFERER"]);
        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
			$this->data['billers'] = $this->site->getAllCompanies('biller');
            $sale = $this->sales_model->getInvoiceByID($id);
            $this->data['inv'] = $sale;
            
			if ($this->Owner || $this->Admin || !$this->session->userdata('biller_id')) {
				$biller_id = $this->site->get_setting()->default_biller;
				$this->data['biller_id'] = $biller_id;
				$this->data['reference'] = $this->site->getReference('sp',$biller_id);
			} else {
				$biller_id = $this->session->userdata('biller_id');
				$this->data['biller_id'] = $biller_id;
				$this->data['reference'] = $this->site->getReference('sp',$biller_id);
			}
			
			
            $this->data['modal_js'] = $this->site->modal_js();
			$this->data['customers'] = $this->site->getCustomers();
			$this->data['bankAccounts'] =  $this->site->getAllBankAccounts();
            $this->load->view($this->theme . 'pos/add_payment', $this->data);
        }
    }
	

    function add_payment_old($id = NULL)
    {
        $this->erp->checkPermissions('payments', true, 'sales');
        $this->load->helper('security');
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        //$this->form_validation->set_rules('reference_no', lang("reference_no"), 'required');
        $this->form_validation->set_rules('amount-paid', lang("amount"), 'required');
        $this->form_validation->set_rules('paid_by', lang("paid_by"), 'required');
        //$this->form_validation->set_rules('note', lang("note"), 'xss_clean');
        $this->form_validation->set_rules('userfile', lang("attachment"), 'xss_clean');
        if ($this->form_validation->run() == true) {
            if ($this->Owner || $this->Admin) {
                $date = $this->erp->fld(trim($this->input->post('date')));
            } else {
                $date = date('Y-m-d H:i:s');
            }
			
			$sale_id = $this->input->post('sale_id');
			$sale_ref = $this->sales_model->getSaleById($sale_id)->reference_no; 
			$paid_by = $this->input->post('paid_by');
			$biller_id = $this->input->post('biller');
			$customer_id = $this->input->post('customer');
			$customer = '';
			if($customer_id) {
				$customer_details = $this->site->getCompanyByID($customer_id);
				$customer = $customer_details->company ? $customer_details->company : $customer_details->name;
			}
			$reference_no = $this->input->post("sale_id");
			
			if($paid_by == "deposit"){
				$payment_reference = $sale_ref;
			}else{
				$payment_reference = (($paid_by == 'deposit')? $this->site->getReference('pay',$biller_id):($this->input->post('reference_no') ? $this->input->post('reference_no') : $this->site->getReference('sp',$biller_id)));
			}
			
			$paid_amount = $this->input->post('amount-paid');
			
			
			
            $payment = array(
                'date' => $date,
                'sale_id' => $this->input->post('sale_id'),
                'reference_no' => $payment_reference,
                'amount' => $paid_amount,
                'paid_by' => $paid_by,
                'cheque_no' => $this->input->post('cheque_no'),
                'cc_no' => $this->input->post('paid_by') == 'gift_card' ? $this->input->post('gift_card_no') : $this->input->post('pcc_no'),
                'cc_holder' => $this->input->post('pcc_holder'),
                'cc_month' => $this->input->post('pcc_month'),
                'cc_year' => $this->input->post('pcc_year'),
                'cc_type' => $this->input->post('pcc_type'),
                'cc_cvv2' => $this->input->post('pcc_ccv'),
                'note' => $this->input->post('note'),
                'created_by' => $this->session->userdata('user_id'),
                'type' => 'received',
				'biller_id'	=> $biller_id,
				//'deposit_customer_id' => $this->input->post('customer'),
				'add_payment' => '1',
				'bank_account' => $this->input->post('bank_account')
            );
			
			//$this->erp->print_arrays($payment);

            if ($_FILES['userfile']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->digital_upload_path;
                $config['allowed_types'] = $this->digital_file_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = FALSE;
                $config['encrypt_name'] = TRUE;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                $payment['attachment'] = $photo;
            }

            //$this->erp->print_arrays($payment);

        } elseif ($this->input->post('add_payment')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }


        if ($this->form_validation->run() == true && $msg = $this->pos_model->addPayment($payment)) {
            $note = $this->erp->clear_tags($this->input->post('pos_note'));
            $payment_id = $this->sales_model->addPayment($payment);
			if($msg){
				//add deposit
				if($paid_by == "deposit"){
					$deposits = array(
						'date' => $date,
						'reference' => $payment_reference,
						'company_id' => $customer_id,
						'amount' => (-1) * $paid_amount,
						'paid_by' => $paid_by,
						'note' => $note,
						'created_by' => $this->session->userdata('user_id'),
						'biller_id' => $biller_id,
						'sale_id' => $sale_id,
						'payment_id' => $payment_id,
						'status' => 'paid'
					);
					
					$this->sales_model->add_deposit($deposits);
				}
			}
			
			
            if ($msg) {
                if ($msg['status'] == 0) {
                    $error = '';
                    foreach ($msg as $m) {
                        $error .= '<br>' . (is_array($m) ? print_r($m, true) : $m);
                    }
                    $this->session->set_flashdata('error', '<pre>' . $error . '</pre>');
                } else {
                    $this->session->set_flashdata('message', lang("payment_added"));
                }
            } else {
                $this->session->set_flashdata('error', lang("payment_failed"));
            }
            redirect("pos/sales");
        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

            $sale = $this->pos_model->getInvoiceByID($id);
            $this->data['inv'] = $sale;
			$this->data['billers'] = $this->site->getAllCompanies('biller');
			if ($this->Owner || $this->Admin || !$this->session->userdata('biller_id')) {
				$biller_id = $this->site->get_setting()->default_biller;
				$this->data['biller_id'] = $biller_id;
				$this->data['reference'] = $this->site->getReference('sp',$biller_id);
			} else {
				$biller_id = $this->session->userdata('biller_id');
				$this->data['biller_id'] = $biller_id;
				$this->data['reference'] = $this->site->getReference('sp',$biller_id);
			}
			
            $this->data['modal_js'] = $this->site->modal_js();
			$this->data['customers'] = $this->site->getCustomers();

            $this->load->view($this->theme . 'pos/add_payment', $this->data);
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
            $this->db->update('pos_settings', array('purchase_code' => $this->input->post('purchase_code', TRUE), 'envato_username' => $this->input->post('envato_username', TRUE)), array('pos_id' => 1));
            redirect('pos/updates');
        } else {
            $fields = array('version' => $this->pos_settings->version, 'code' => $this->pos_settings->purchase_code, 'username' => $this->pos_settings->envato_username, 'site' => base_url());
            $this->load->helper('update');
            $protocol = is_https() ? 'https://' : 'http://';
            $updates = get_remote_contents($protocol.'cloudnet.com.kh/api/v1/update/', $fields);
            $this->data['updates'] = json_decode($updates);
            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('updates')));
            $meta = array('page_title' => lang('updates'), 'bc' => $bc);
            $this->page_construct('pos/updates', $meta, $this->data);
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
                redirect("pos/updates");
            }
        }
        $this->db->update('pos_settings', array('version' => $version, 'update' => 0), array('pos_id' => 1));
        unlink('./files/updates/' . $file . '.zip');
        $this->session->set_flashdata('success', lang('update_done'));
        redirect("pos/updates");
    }
	//------------------ Export to excel and pdf POS Sales --------------------------------
	function getPosSales($pdf = NULL, $excel = NULL)
    {
        $this->erp->checkPermissions('Sales');
		

        $sales = $this->input->get('sales') ? $this->input->get('sales') : NULL;

        if ($pdf || $excel) {

            $this->db
                ->select($this->db->dbprefix('sales') . ".date as dates, " . $this->db->dbprefix('sales') . ".reference_no as reference_nos,". $this->db->dbprefix('sales') .".biller as billers,
				" . $this->db->dbprefix('sales') . ".customer as customers, " . $this->db->dbprefix('sales') . ".sale_status as sale_statuses, 
				" . $this->db->dbprefix('sales') . ".grand_total as grand_totals, " . $this->db->dbprefix('sales') . ".paid as paids,
				(" . $this->db->dbprefix('sales') . ". grand_total - paid) as balances,
				" . $this->db->dbprefix('sales') . ".payment_status as payment_statuses");
				//" . $this->db->dbprefix('warehouses') . ".name as wname");
            $this->db->from('sales');
            //$this->db->join('categories', 'categories.id=products.category_id', 'left');
            //$this->db->join('warehouses', 'warehouses.id=products.warehouse', 'left');
            $this->db->group_by("sales.id")->order_by('sales.date desc');
			$this->db->where('sales.reference_no LIKE "SALE/POS/%"');
            if ($sales) {
                $this->db->where('sales.id', $sales);
            }

            $q = $this->db->get();
            if ($q->num_rows() > 0) {
                foreach (($q->result()) as $row) {
                    $data[] = $row;
                }
            } else {
                $data = NULL;
            }

            if (!empty($data)) {

                $this->load->library('excel');
                $this->excel->setActiveSheetIndex(0);
                $this->excel->getActiveSheet()->setTitle(lang('POS Sales List'));
				$this->excel->getActiveSheet()->SetCellValue('A1', lang('date'));
                $this->excel->getActiveSheet()->SetCellValue('B1', lang('reference_no'));
                $this->excel->getActiveSheet()->SetCellValue('C1', lang('biller'));
                $this->excel->getActiveSheet()->SetCellValue('D1', lang('customer'));
                $this->excel->getActiveSheet()->SetCellValue('E1', lang('sale_status'));
                $this->excel->getActiveSheet()->SetCellValue('F1', lang('grand_total'));
                $this->excel->getActiveSheet()->SetCellValue('G1', lang('paid'));
                $this->excel->getActiveSheet()->SetCellValue('H1', lang('balance'));
				$this->excel->getActiveSheet()->SetCellValue('I1', lang('payment_status'));

                $row = 2;
				
                foreach ($data as $data_row) {
                    //$this->excel->getActiveSheet()->SetCellValue('A' . $row, $this->erp->hrld($data_row->id));
                    $this->excel->getActiveSheet()->SetCellValue('A' . $row, $data_row->dates);
					$this->excel->getActiveSheet()->SetCellValue('B' . $row, $data_row->reference_nos);
                    $this->excel->getActiveSheet()->SetCellValue('C' . $row, $data_row->billers);
                    $this->excel->getActiveSheet()->SetCellValue('D' . $row, $data_row->customers);
                    $this->excel->getActiveSheet()->SetCellValue('E' . $row, $data_row->sale_statuses);
                    $this->excel->getActiveSheet()->SetCellValue('F' . $row, lang($data_row->grand_totals));
					$this->excel->getActiveSheet()->SetCellValue('G' . $row, lang($data_row->paids));
					$this->excel->getActiveSheet()->SetCellValue('H' . $row, lang($data_row->balances));
					$this->excel->getActiveSheet()->SetCellValue('I' . $row, lang($data_row->payment_statuses));
                    //$this->excel->getActiveSheet()->SetCellValue('G' . $row, $data_row->wh);
                    $row++;
                }

                $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
                $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
                $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
                $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
                $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
				$this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
                $filename = lang('Sales List');
                $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
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
	//------------------ End export POS Sales ---------------------------------------------
	
	//--------------------Kitchen Area------------------
	function view_modal($id)
    {
        $this->erp->checkPermissions('index');
        
        $this->data['data'] = $this->pos_model->getKitchen($id);
        $this->load->view($this->theme . 'pos/view_modal', $this->data);
    }
	
	function view_complete(){
		$this->data['data'] = $this->pos_model->getComplete();
		$this->load->view($this->theme . 'pos/view_complete', $this->data);
	}
	
	function delete_item($id){
		$delete = $this->pos_model->clear_item($id);
		if($delete){
			redirect('pos/view_complete');
		}
	}
	
	public function suggestions()
    {
        $term   = $this->input->get('term', true);
        $sus_id = $this->input->get('pros', true);

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

        $rows = $this->pos_model->getProductNames($term);

        if ($rows) {
            $c = str_replace(".", "", microtime(true));
            $r = 0;
            foreach ($rows as $row) {
                $pr[] = array('id' => $row->id, 'code' => $row->code, 'label' => $row->name, 'qty' => $row->quantity);
                $r++;
            }
            echo json_encode($pr);
        } else {
            echo json_encode(array(array('id' => 0, 'label' => lang('no_match_found'), 'value' => $term)));
        }
    }
	
	function add_item(){
		$pid = $this->input->post('pro_id',true);
		$sid = $this->input->post('sus_id',true);
		$wid = $this->input->post('ware_id',true);
		$exp = explode(',', $pid);
		foreach($exp as $id){
			$result = $this->pos_model->selectProByID($id);
			$data = array(
				'suspend_id' => $sid,
				'product_id' => $result->id,
				'product_code' => $result->code,
				'product_name' => $result->name,
				'net_unit_price' => $result->price,
				'unit_price' => $result->price,
				'warehouse_id' => $wid,
				'real_unit_price' => $result->price
			);
			$res = $this->db->insert('suspended_items',$data);
		}
		redirect('pos/view_complete');
		/*
		foreach($results as $result){
			$data = array(
				'suspend_id' => $sid,
				'product_id' => $result->id,
				'product_code' => $result->code,
				'product_name' => $result->name,
				'net_unit_price' => $result->price,
				'unit_price' => $result->price,
				'warehouse_id' => $wid,
				'real_unit_price' => $result->price
			);
		}
		*/
		//$this->erp->print_arrays($data);
		//$this->db->insert('suspended_items',array('post_id'=>$product_id,'category_id'=>default_cate($user_id)));
	}
	
	function getPrincipleCustomer() {
		$pr_type = $this->input->get('pr_type');
		$principle_type = $this->pos_model->getPrincipleByTypeID($pr_type);
		
		$data = array();
		foreach($principle_type as $principle) {
			$data[] = array('period'=>$principle->period,'dateline'=>$this->erp->hrsd($principle->dateline),'principle_rate'=>$principle->value,'remark'=>$principle->remark,'rates'=>$principle->rate);
		}
		echo json_encode($data);	 
	}
	
	function delivery_added($id = NULL,$status="sale_order")
	{
        $this->erp->checkPermissions('deliveries');
		$this->form_validation->set_rules('customer', lang("customer"), 'required');
		$this->form_validation->set_rules('delivery_by', lang("delivery_by"), 'required');
		$this->form_validation->set_rules('reference_no', lang("reference_no"), 'trim|is_unique[sales.reference_no]');

        if ($this->form_validation->run() == true) {
			
        } else {
			
			$this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
			$date = date('d/m/Y H:i');
			$this->data['date'] = $date;
			$this->data['status'] = $status;
			
			if($status == 'sale_order'){
				$this->data['tax_rates'] = $this->site->getAllTaxRates();
				$div = $this->sales_model->getSaleRecordByID($id);
				$this->data['deliveries'] = $div;
				$this->data['delivery_items'] = $this->sales_model->getSaleItemBySaleID($id);
				$this->data['user'] = $this->sales_model->getUserFromSaleBySaleID($id);
				$this->data['reference'] = $this->site->getReference('do',$div->biller_id);
				if ($this->Owner || $this->Admin || !$this->session->userdata('biller_id')) {
					$biller_id = $this->site->get_setting()->default_biller;
					$this->data['reference'] = $this->site->getReference('do',$biller_id);
				} else {
					$biller_id = $this->session->userdata('biller_id');
					$this->data['reference'] = $this->site->getReference('do',$biller_id);
				}

			}
			$this->data['POS'] = "1";
			$this->data['setting'] = $this->site->get_setting();
			$this->data['drivers'] = $this->site->getDrivers();
			$this->data['modal_js'] = $this->site->modal_js();
            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('sales'), 'page' => lang('sales')), array('link' => '#', 'page' => lang('add_deliveries')));
            $meta = array('page_title' => lang('add_deliveries'), 'bc' => $bc);            
			$this->load->view('default/views/pos/delivery_added', $this->data);
        }
    }
	
	function delivery_added_old($id = NULL,$status="sale_order",$pos=1)
	{
		
        $this->erp->checkPermissions('deliveries');
		$this->form_validation->set_rules('customer', lang("customer"), 'required');
		$this->form_validation->set_rules('delivery_by', lang("delivery_by"), 'required');
		$this->form_validation->set_rules('reference_no', lang("reference_no"), 'trim|is_unique[sales.reference_no]');

        if ($this->form_validation->run() == true) {

        } else {
			
			$this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
			$date = date('d/m/Y H:i');
			$this->data['date'] = $date;
			$this->data['status'] = $status;
			
			if($status == 'sale_order'){
				$this->data['tax_rates'] = $this->site->getAllTaxRates();
				$div = $this->sales_model->getSaleRecordByID($id);
				$this->data['deliveries'] = $div;
				$this->data['delivery_items'] = $this->sales_model->getSaleItemBySaleID($id);
				$this->data['user'] = $this->sales_model->getUserFromSaleBySaleID($id);
				$this->data['reference'] = $this->site->getReference('do',$div->biller_id);
				if ($this->Owner || $this->Admin || !$this->session->userdata('biller_id')) {
					$biller_id = $this->site->get_setting()->default_biller;
					$this->data['reference'] = $this->site->getReference('do',$biller_id);
				} else {
					$biller_id = $this->session->userdata('biller_id');
					$this->data['reference'] = $this->site->getReference('do',$biller_id);
				}

			}
			$this->data['POS'] = "1";
			$this->data['setting'] = $this->site->get_setting();
			$this->data['drivers'] = $this->site->getDrivers();
			$this->data['modal_js'] = $this->site->modal_js();
            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('sales'), 'page' => lang('sales')), array('link' => '#', 'page' => lang('add_deliveries')));
            $meta = array('page_title' => lang('add_deliveries'), 'bc' => $bc);
            
			$this->load->view('default/views/pos/delivery_added', $this->data);
        }

    }
	function seperate($id)
    {
		$idd 							= $this->pos_model->getsuspendedBill_itemsByID($id);
		$this->data['suspended'] 		= $this->pos_model->getsuspendedNULL($idd->suspend_id);
		$this->data['exchange_rate'] 	= $this->pos_model->getExchange_rate('KHM');
		$this->data['suspended_items'] 	= $this->pos_model->getsuspended_items($id);
		$this->data['id'] 				= $id;
		$this->data['suspended_name'] 	= $this->pos_model->getsuspendedName($idd->suspend_id);
		$this->data['modal_js'] 		= $this->site->modal_js();
		$this->load->view($this->theme . 'pos/seperate', $this->data);
    }
	
	public function updateFoodPrinted(){
		$suppend_id = $_REQUEST['suppend_id'];
		$product_id = $_REQUEST['product_id'];
		foreach($product_id as $product){
			$drink = $this->pos_model->getCategoryByID($product);
			if($drink->code == 'FoodMenu'){
				$this->db->update('suspended_items', array('printed' => 1), array('suspend_id' => $suppend_id, 'product_id' => $drink->id));
			}
		}
		return true;
	}
	function add_seperate(){
		if($this->input->get('items')){
			$items = $this->input->get('items');
		}else{
			$items ="";
		}
		if($this->input->get('tab')){
			$tab = $this->input->get('tab');
		}else{
			$tab ="";
		}
		if($this->input->get('id')){
			$id = $this->input->get('id');
		}else{
			$id ="";
		}
		if($this->input->get('totaQty')){
			$totaQty = $this->input->get('totaQty');
		}
		
		if($items && $tab){
			if($this->pos_model->addSeperate($items,$tab,$id,$totaQty)){
				$this->session->set_flashdata('message', $this->lang->line("add_seperate"));
				echo json_encode(true);
				exit();
			}
		}
		echo json_encode(false);
		
	}
	
	public function updated_print ()
	{
		$sus_id  = $this->input->get('suspend_id');
		$item_id = $this->input->get('item_id'); 
		$this->pos_model->updateprinted($sus_id,$item_id);
		exit();
	}
	
	function deliveries($start_date = NULL, $end_date = NULL)
    {
        $this->erp->checkPermissions();
		if (!$start_date) {
            //$start = $this->db->escape(date('Y-m') . '-1');
           // $start_date = date('Y-m') . '-1';
        } else {
            $start = $this->db->escape(urldecode($start_date));
        }
        if (!$end_date) {
            //$end = $this->db->escape(date('Y-m-d H:i'));
            //$end_date = date('Y-m-d H:i');
        } else {
            $end = $this->db->escape(urldecode($end_date));
        }

        $data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
		$this->data['start'] = urldecode($start_date);
        $this->data['end'] = urldecode($end_date);
		if ($this->Owner || $this->Admin) {
            $this->data['warehouses'] = $this->site->getAllWarehouses();
            $this->data['warehouse_id'] = isset($warehouse_id);
            $this->data['warehouse'] = isset($warehouse_id) ? $this->site->getWarehouseByID($warehouse_id) : NULL;
        } else {
            $this->data['warehouses'] = NULL;
            $this->data['warehouse_id'] = $this->session->userdata('warehouse_id');

            $this->data['warehouse'] = $this->session->userdata('warehouse_id') ? $this->site->getWarehouseByID($this->session->userdata('warehouse_id')) : NULL;
        }
		
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('pos'), 'page' => lang('pos')), array('link' => '#', 'page' => lang('deliveries')));
        $meta = array('page_title' => lang('deliveries'), 'bc' => $bc);
        $this->load->view('default/views/pos/deliveries', $this->data);
    }
	
	function edit_deliveries($delivery_id = NULL)
    {
		$this->erp->checkPermissions('deliveries');
		$this->form_validation->set_rules('cust', lang("customer"), 'required');
		$this->form_validation->set_rules('delivery_reference', lang("delivery_reference"), 'required');

        if ($this->form_validation->run() == true) {

        } else {
			
			$this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
			$deliv = $this->sales_model->getDelivery($delivery_id);
			$deliv_items = $this->sales_model->getPOSDeliveryItemsByID($delivery_id, $deliv->type);
			
			$this->data['drivers'] = $this->site->getDrivers();
			if($deliv->type == 'sale_order') {
				$this->data['user_name']       = $this->site->getUser($deliv->created_by);
				$this->data['sale_order_item'] = $this->sales_model->getSaleOrderItems($deliv->sale_id);
			}else {
				$this->data['saleInfo']        = $this->sales_model->getSaleInfo($deliv->sale_id);
			}
			$this->data['delivery'] = $deliv;
			
			$this->data['delivery_items'] = $deliv_items;
			
			//$this->erp->print_arrays($deliv_items);
			
			foreach($deliv_items as $deliv_item) {
				$ditem =  $deliv_item->id;
				$productId = $deliv_item->product_id;
				$productName = $deliv_item->product_name;
				$productCode = $deliv_item->code;
				$quantity_received = $deliv_item->quantity_received;
				$quantity = $deliv_item->ord_qty;
				$balance = $deliv_item->ord_qty - $deliv_item->ord_qty_rec;
				$option_id = $deliv_item->option_id;
				$arr[] = array(
					'id' => $deliv_item->id,
					'ditem' => $ditem,
					'item_id' => $deliv_item->item_id,
					'pid' => $productId,
					'pname' => $productName,
					'warehouse_id' => $deliv_item->warehouse_id,
					'pcode' => $productCode,
					'qty' => $quantity,
					'qty_received' => $quantity_received,
					'balance' => $balance,
					'option_id' => $option_id
				);
			}

			$this->data['quantity_recs'] = $arr;
		
			//$this->erp->print_arrays($arr);
			$this->data['pos'] = 1;
			$this->data['setting'] = $this->site->get_setting();
			$this->data['modal_js'] = $this->site->modal_js();
            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('sales'), 'page' => lang('sales')), array('link' => '#', 'page' => lang('edit_deliveries')));
            $meta = array('page_title' => lang('edite_deliveries'), 'bc' => $bc);
            
			$this->load->view('default/views/pos/edit_deliveries', $this->data);
        }
		
    }
	
	
	function invoice_print_a4_ttr_combo($id = NULL)
    {
		
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
		
		$this->load->model('pos_model');
		$this->data['pos'] 			= $this->pos_model->getSetting();
		
		$this->data['error'] 		= (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        
		$inv 						= $this->sales_model->getInvoiceByID($id);
		$this->data['Settings'] 	= $this->site->get_setting();
        $this->data['customer'] 	= $this->site->getCompanyByID($inv->customer_id);
        $this->data['biller'] 		= $this->site->getCompanyByID($inv->biller_id);
        $this->data['created_by'] 	= $this->site->getUser($inv->created_by);
		$this->data['cashier'] 		= $this->site->getUser($inv->saleman_by);
        $this->data['updated_by'] 	= $inv->updated_by ? $this->site->getUser($inv->updated_by) : NULL;
        $this->data['warehouse'] 	= $this->site->getWarehouseByID($inv->warehouse_id);
        $this->data['inv'] 			= $inv;
		
		$this->data['vattin'] 		= $this->site->getTaxRateByID($inv->order_tax_id);
        $return 					= $this->sales_model->getReturnBySID($id);
        $this->data['return_sale'] 	= $return;
        $this->data['rows'] 		= $this->sales_model->getAllInvoicesItem($id);
		$this->data['payment'] 		= $this->sales_model->getPaymentBySaleID($id);
		$this->data['logo'] 		= true;
        $this->load->view($this->theme . 'pos/invoice_print_a4_ttr_combo', $this->data);
    }

    function getCusDetails()
    {
        $customer_id = $this->input->get('customer_id');
        $row = $this->pos_model->getCusDetail($customer_id);
        echo json_encode($row);
    }

}
