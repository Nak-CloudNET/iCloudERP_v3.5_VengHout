<?php defined('BASEPATH') or exit('No direct script access allowed');

class Purchases_request extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            redirect('login');
        }
        if ($this->Customer) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER["HTTP_REFERER"]);
        }
        $this->lang->load('purchases', $this->Settings->language);
        $this->load->library('form_validation');
        $this->load->model('purchases_model');
        $this->load->model('products_model');
		$this->load->model('purchases_request_model');
        $this->digital_upload_path = 'files/';
        $this->upload_path = 'assets/uploads/';
        $this->thumbs_path = 'assets/uploads/thumbs/';
        $this->image_types = 'gif|jpg|jpeg|png|tif';
        $this->digital_file_types = 'zip|psd|ai|rar|pdf|doc|docx|xls|xlsx|ppt|pptx|gif|jpg|jpeg|png|tif|txt';
        $this->allowed_file_size = '1024';
        $this->data['logo'] = true;
		
		if(!$this->Owner && !$this->Admin) {
            $gp = $this->site->checkPermissions();
            $this->permission = $gp[0];
            $this->permission[] = $gp[0];
        } else {
            $this->permission[] = NULL;
        }
        $this->default_biller_id = $this->site->default_biller_id();
    }

    /* ------------------------------------------------------------------------- */

    public function index($warehouse_id = null)
    {
        $this->erp->checkPermissions('index',NULL,'purchase_request');
		$this->load->model('reports_model');
		
		if(isset($_GET['d']) != ""){
			$date = $_GET['d'];
			$this->data['date'] = $date;
		}
        
        $biller_id = $this->session->userdata('biller_id');
        $this->data['billers'] = $this->site->getAllCompanies('biller');
		$this->data['user_billers'] = $this->purchases_request_model->getAllCompaniesByID($biller_id);
		$this->data['users'] = $this->reports_model->getStaff();
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
				$this->data['warehouse_id'] = NULL;
				$this->data['warehouse'] = $this->session->userdata('warehouse_id') ? $this->products_model->getUserWarehouses() : NULL;
			}
        }
		
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('purchase_request')));
        $meta = array('page_title' => lang('purchase_request'), 'bc' => $bc);
        $this->page_construct('purchase_request/index', $meta, $this->data);
    }
	
	//################# Get Purchases Request
	public function update_purchases_request($request_id=null){
		    $statu="approved";
		    $this->db->set('status',$statu);   
			$this->db->where('id', $request_id);  
			$update=$this->db->update('purchases_request'); 
			
		redirect($_SERVER["HTTP_REFERER"]);	 
	}
	public function Unapproved($request_id=null){
          $status="requested";
          $this->db->set('status',$status);
          $this->db->where('id',$request_id);
          $update=$this->db->Update('purchases_request');
       redirect($_SERVER["HTTP_REFERER"]);		  
	}

    public function getPurchasesRequest($warehouse_id = null)
    {
        $this->erp->checkPermissions('index',NULL,'purchase_request');
		if($warehouse_id){
			$warehouse_ids = explode('-', $warehouse_id);
		}
		
		if ($this->input->get('product')) {
            $product = $this->input->get('product');
        } else {
            $product = NULL;
        }
        if ($this->input->get('user')) {
            $user_query = $this->input->get('user');
        } else {
            $user_query = NULL;
        }
        if ($this->input->get('supplier')) {
            $supplier = $this->input->get('supplier');
        } else {
            $supplier = NULL;
        }
        if ($this->input->get('project')) {
            $project = $this->input->get('project');
        } else {
            $project = NULL;
        }

        if ($this->input->get('warehouse')) {
            $warehouse = $this->input->get('warehouse');
        } else {
            $warehouse = NULL;
        }
        if ($this->input->get('reference_no')) {
            $reference_no = $this->input->get('reference_no');
        } else {
            $reference_no = NULL;
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
		if ($this->input->get('note')) {
            $note = $this->input->get('note');
        } else {
            $note = NULL;
        }
		
        if ((!$this->Owner || !$this->Admin) && !$warehouse_id) {
            //$user = $this->site->getUser();
            //$warehouse_id = $user->warehouse_id; 
        }
		;
        $detail_link = anchor('purchases_request/modal_view/$1', '<i class="fa fa-file-text-o"></i> ' . lang('Purchase_Request_Details'),'data-toggle="modal" data-target="#myModal"');
        $edit_link = anchor('purchases_request/edit/$1', '<i class="fa fa-edit"></i> ' . lang('Edit_Purchase_Request'), array('class' => 'auth'));
		$auth_link = anchor('purchases_request/update_purchases_request/$1', '<i class="fa fa-check"></i> ' . lang('approve'));
		$unauth_link = anchor('purchases_request/Unapproved/$1', '<i class="fa fa-check"></i> ' . lang('unapproved'));
		$reject = anchor('purchases_request/reject/$1', '<i class="fa fa-times" aria-hidden="true"></i> ' . lang('reject'));
		$unreject = anchor('purchases_request/unreject/$1', '<i class="fa fa-check"></i> ' . lang('unreject'));
		$create_link = anchor('purchases/add_purchase_order/$1', '<i class="fa fa-plus-circle"></i> ' . lang('create_order'));
		$create_purchase = anchor('purchases/add/$1', '<i class="fa fa-plus-circle"></i> ' . lang('creat_puchases'), array('class' => 'disabled-link'));
		
        $delete_link = "<a href='#' class='po' title='<b>" . $this->lang->line("delete_purchase_request") . "</b>' data-content=\"<p>"
        . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('purchases_request/delete/$1') . "'>"
        . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i> "
        . lang('Delete_Purchase_Request') . "</a>";
        $action = '<div class="text-center"><div class="btn-group text-left">'
        . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
        . lang('actions') . ' <span class="caret"></span></button>
        <ul class="dropdown-menu pull-right" role="menu">

            <li>' . $detail_link . '</li>'

            .(($this->Owner || $this->Admin) ? '<li class="approved">'.$auth_link.'</li>' : ($this->GP['purchase_request-authorize'] ? '<li class="approved">'.$auth_link.'</li>' : '')).
            (($this->Owner || $this->Admin) ? '<li class="unapproved">'.$unauth_link.'</li>' : ($this->GP['purchase_request-authorize'] ? '<li class="unapproved">'.$unauth_link.'</li>' : '')).
            (($this->Owner || $this->Admin) ? '<li class="reject">'.$reject.'</li>' : ($this->GP['purchase_request-authorize'] ? '<li class="reject">'.$reject.'</li>' : '')).
            (($this->Owner || $this->Admin) ? '<li class="create">'.$create_link.'</li>' : ($this->GP['purchases_order-add'] ? '<li class="create">'.$create_link.'</li>' : '')).
			(($this->Owner || $this->Admin) ? '<li class="edit">'.$edit_link.'</li>' : ($this->GP['purchase_request-edit'] ? '<li class="edit">'.$edit_link.'</li>' : '')).
            
        '</ul>
    </div></div>';

        $biller_id = $this->session->userdata('biller_id');
        $biller_id =json_decode($biller_id);
        $this->load->library('datatables');
        if ($warehouse_id) {
            $this->datatables
                ->select("purchases_request.id as id, date, reference_no, companies.company as project, supplier, order_status, grand_total, purchases_request.status")
                ->from('purchases_request')
                ->join('companies', 'purchases_request.biller_id = companies.id', 'left')
                ->join('users', 'purchases_request.created_by = users.id', 'left')
                ->where_in('purchases_request.biller_id', $biller_id);

            if (count($warehouse_ids) > 1) {
                $this->datatables->where_in('purchases_request.warehouse_id', $warehouse_ids);
            } else {
                $this->datatables->where('purchases_request.warehouse_id', $warehouse_id);
            }

        } else {
            $this->datatables
                ->select("purchases_request.id as id, date, reference_no, companies.company as project, supplier, order_status, grand_total, purchases_request.status")
                ->from('purchases_request')
                ->join('companies', 'purchases_request.biller_id = companies.id', 'left')
                ->join('users', 'purchases_request.created_by = users.id', 'left')
                ->where_in('purchases_request.biller_id', $biller_id);

            /*$this->datatables
                ->select("purchases_request.id as id, date, reference_no, companies.company as project, IF(erp_sup_com.company = '', erp_sup_com.name, erp_sup_com.company) AS supplier, order_status, grand_total, purchases_request.status")
                ->from('purchases_request')
                ->join('companies', 'purchases_request.biller_id = companies.id', 'left')
                ->join('companies as erp_sup_com', 'purchases_request.supplier_id = erp_sup_com.id', 'left');
			if(isset($_REQUEST['d'])){
				$date_c = date('Y-m-d', strtotime('+3 months'));
				$date = $_GET['d'];
				$date1 = str_replace("/", "-", $date);
				$date =  date('Y-m-d', strtotime($date1));

				$this->datatables
				->where("date >=", $date)
				->where('DATE_SUB(date, INTERVAL 1 DAY) <= CURDATE()')
				->where('purchases_request.payment_term <>', 0);
                if (count($warehouse_ids) > 1) {
                    $this->datatables->where_in('purchases_request.warehouse_id', $warehouse_ids);
                } else {
                    $this->datatables->where('purchases_request.warehouse_id', $warehouse_id);
                }
			}*/

        }
		if (!$this->Customer && !$this->Supplier && !$this->Owner && !$this->Admin && !$this->session->userdata('view_right')) {
            $this->datatables->where('purchases_request.created_by', $this->session->userdata('user_id'));
        } elseif ($this->Customer) {
            $this->datatables->where('customer_id', $this->session->userdata('user_id'));
        }

		if ($user_query) {
			$this->datatables->where('purchases_request.created_by', $user_query);
		}
		
		if ($product) {
			$this->datatables->like('purchase_request_items.product_id', $product);
		}
		if ($supplier) {
			$this->datatables->where('purchases_request.supplier_id', $supplier);
		}
        if ($project) {
            $this->datatables->where('purchases_request.biller_id', $project);
        }
		if ($warehouse) {
			$this->datatables->where('purchases_request.warehouse_id', $warehouse);
		}
		if ($reference_no) {
			$this->datatables->like('purchases_request.reference_no', $reference_no, 'both');
		}
		if ($start_date) {
			$this->datatables->where($this->db->dbprefix('purchases_request').'.date BETWEEN "' . $start_date . ' 00:00:00" and "' . $end_date . ' 23:59:00"');
		}
		if ($note) {
			$this->datatables->like('purchases_request.note', $note, 'both');
		}

        $this->datatables->add_column("Actions", $action, "id");
        echo $this->datatables->generate();
    }
	
	function purchases_request_alerts($warehouse_id = NULL)
	{  
		$this->load->model('reports_model');
        $this->data['warehouse_id'] = $warehouse_id;
		$this->data['users'] = $this->reports_model->getStaff();
        $this->data['warehouses'] = $this->site->getAllWarehouses();
        $this->data['billers'] = $this->site->getAllCompanies('biller');
        $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
		$bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('purchase_request'), 'page' => lang('purchase_request')), array('link' => '#', 'page' => lang('list_purchase_request_alerts')));
		$meta = array('page_title' => lang('list_purchase_request_alerts'), 'bc' => $bc);
		$this->page_construct('purchase_request/purchases_request_alerts', $meta, $this->data);
    }
	
	function getPurchasesRequestAlerts($warehouse_id = null)
    {
        $this->erp->checkPermissions('index',NULL,'purchase_request');
		if($warehouse_id){
			$warehouse_ids = explode('-', $warehouse_id);
		}
		
		if ($this->input->get('product')) {
            $product = $this->input->get('product');
        } else {
            $product = NULL;
        }
        if ($this->input->get('user')) {
            $user_query = $this->input->get('user');
        } else {
            $user_query = NULL;
        }
        if ($this->input->get('supplier')) {
            $supplier = $this->input->get('supplier');
        } else {
            $supplier = NULL;
        }
        if ($this->input->get('warehouse')) {
            $warehouse = $this->input->get('warehouse');
        } else {
            $warehouse = NULL;
        }
        if ($this->input->get('reference_no')) {
            $reference_no = $this->input->get('reference_no');
        } else {
            $reference_no = NULL;
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
		if ($this->input->get('note')) {
            $note = $this->input->get('note');
        } else {
            $note = NULL;
        }
		
        if ((!$this->Owner || !$this->Admin) && !$warehouse_id) {
            //$user = $this->site->getUser();
            //$warehouse_id = $user->warehouse_id; 
        }
		;
        $detail_link = anchor('purchases_request/view/$1', '<i class="fa fa-file-text-o"></i> ' . lang('Purchase_Request_Details'));
        $edit_link = anchor('purchases_request/edit/$1', '<i class="fa fa-edit"></i> ' . lang('Edit_Purchase_Request'), array('class' => 'auth'));
		$auth_link = anchor('purchases_request/update_purchases_request/$1', '<i class="fa fa-check"></i> ' . lang('approve'));
		$unauth_link = anchor('purchases_request/Unapproved/$1', '<i class="fa fa-check"></i> ' . lang('unapproved'));
		$reject = anchor('purchases_request/reject/$1', '<i class="fa fa-times" aria-hidden="true"></i> ' . lang('reject'));
		$unreject = anchor('purchases_request/unreject/$1', '<i class="fa fa-check"></i> ' . lang('unreject'));
		$create_link = anchor('purchases/add_purchase_order/$1', '<i class="fa fa-plus-circle"></i> ' . lang('create_order'));
		$create_purchase = anchor('purchases/add/$1', '<i class="fa fa-plus-circle"></i> ' . lang('creat_puchases'), array('class' => 'disabled-link'));
		
        $delete_link = "<a href='#' class='po' title='<b>" . $this->lang->line("delete_purchase_request") . "</b>' data-content=\"<p>"
        . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('purchases_request/delete/$1') . "'>"
        . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i> "
        . lang('Delete_Purchase_Request') . "</a>";
        $action = '<div class="text-center"><div class="btn-group text-left">'
        . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
        . lang('actions') . ' <span class="caret"></span></button>
        <ul class="dropdown-menu pull-right" role="menu">

            <li>' . $detail_link . '</li>'

            .(($this->Owner || $this->Admin) ? '<li class="approved">'.$auth_link.'</li>' : ($this->GP['purchase_request-authorize'] ? '<li class="approved">'.$auth_link.'</li>' : '')).
            (($this->Owner || $this->Admin) ? '<li class="unapproved">'.$unauth_link.'</li>' : ($this->GP['purchase_request-authorize'] ? '<li class="unapproved">'.$unauth_link.'</li>' : '')).
            (($this->Owner || $this->Admin) ? '<li class="reject">'.$reject.'</li>' : ($this->GP['purchase_request-authorize'] ? '<li class="reject">'.$reject.'</li>' : '')).
            (($this->Owner || $this->Admin) ? '<li class="create">'.$create_link.'</li>' : ($this->GP['purchases_order-add'] ? '<li class="create">'.$create_link.'</li>' : '')).
			(($this->Owner || $this->Admin) ? '<li class="edit">'.$edit_link.'</li>' : ($this->GP['purchase_request-edit'] ? '<li class="edit">'.$edit_link.'</li>' : '')).
            
        '</ul>
    </div></div>';

        $biller_id = $this->session->userdata('biller_id');

        $this->load->library('datatables');
        if ($warehouse_id) {
            $this->datatables
                ->select("purchases_request.id as id, date, reference_no, companies.company as project, supplier, order_status, grand_total, purchases_request.status")
                ->from('purchases_request')
                ->join('companies', 'purchases_request.biller_id = companies.id', 'left')
                ->join('users', 'purchases_request.created_by = users.id', 'left')
				->where('purchases_request.status','requested')
                ->where('purchases_request.biller_id', $biller_id);

                if (count($warehouse_ids) > 1) {
                    $this->datatables->where_in('purchases_request.warehouse_id', $warehouse_ids);
                } else {
                    $this->datatables->where('purchases_request.warehouse_id', $warehouse_id);
                }

        } else {
            $this->datatables
                ->select("purchases_request.id as id, date, reference_no, companies.company as project, IF(erp_sup_com.company = '', erp_sup_com.name, erp_sup_com.company) AS supplier, order_status, grand_total, purchases_request.status")
                ->from('purchases_request')
                ->join('companies', 'purchases_request.biller_id = companies.id', 'left')
                ->join('companies as erp_sup_com', 'purchases_request.supplier_id = erp_sup_com.id', 'left')
				->where('purchases_request.status','requested');
			if(isset($_REQUEST['d'])){
				$date_c = date('Y-m-d', strtotime('+3 months'));
				$date = $_GET['d'];
				$date1 = str_replace("/", "-", $date);
				$date =  date('Y-m-d', strtotime($date1));
				
				$this->datatables
				->where("date >=", $date)
				->where('DATE_SUB(date, INTERVAL 1 DAY) <= CURDATE()')
				->where('purchases_request.payment_term <>', 0);
			}
        }
		
		if (!$this->Customer && !$this->Supplier && !$this->Owner && !$this->Admin && !$this->session->userdata('view_right')) {
            $this->datatables->where('purchases_request.created_by', $this->session->userdata('user_id'));
        } elseif ($this->Customer) {
            $this->datatables->where('customer_id', $this->session->userdata('user_id'));
        }

		if ($user_query) {
			$this->datatables->where('purchases_request.created_by', $user_query);
		}
		
		if ($product) {
			$this->datatables->like('purchase_request_items.product_id', $product);
		}
		if ($supplier) {
			$this->datatables->where('purchases_request.supplier_id', $supplier);
		}
		if ($warehouse) {
			$this->datatables->where('purchases_request.warehouse_id', $warehouse);
		}
		if ($reference_no) {
			$this->datatables->like('purchases_request.reference_no', $reference_no, 'both');
		}
		if ($start_date) {
			$this->datatables->where($this->db->dbprefix('purchases_request').'.date BETWEEN "' . $start_date . ' 00:00:00" and "' . $end_date . ' 23:59:00"');
		}
		if ($note) {
			$this->datatables->like('purchases_request.note', $note, 'both');
		}
		
        $this->datatables->add_column("Actions", $action, "id");
        echo $this->datatables->generate();
    }
	

	public function reject($id=null){ 
			$status="reject";
			$this->db->set('status',$status);
			$this->db->where('id',$id);
			if($this->db->update('purchases_request')){
				redirect($_SERVER["HTTP_REFERER"]);
			}
	}
	public function unreject($id=null){
			$status=$this->purchases_request_model->getPurchaseRequestStatus($id); 
			if($status->status=="reject"){
				$this->db->set('status','requested');
				$this->db->where('id',$id);
				if($this->db->update('purchases_request')){
					redirect($_SERVER["HTTP_REFERER"]);
				}
			}
	}
	
	public function modal_view($purchase_id = null)
    {
        $this->erp->checkPermissions('index', true, 'purchase_request');

        if ($this->input->get('id')) {
            $purchase_id 			= $this->input->get('id');
        }
        $this->data['error'] 		= (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $inv 						= $this->purchases_request_model->getPurchaseRequestByID($purchase_id);
        // $this->erp->print_arrays($inv);
        $this->data['rows'] 		= $this->purchases_request_model->getAllPurchaseRequestItems($purchase_id);
        $this->data['supplier'] 	= $this->site->getCompanyByID($inv->supplier_id);
        $this->data['warehouse'] 	= $this->site->getEmployees($inv->biller_id);
        // $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
        // $this->erp->print_arrays($this->site->getWarehouseByID($inv->warehouse_id));
        $this->data['inv'] 			= $inv;
        $this->data['payments'] 	= $this->purchases_model->getPaymentsForPurchase($purchase_id);
        $this->data['created_by'] 	= $this->site->getUser($inv->created_by);
        $this->data['updated_by'] 	= $inv->updated_by ? $this->site->getUser($inv->updated_by) : null;

        $this->load->view($this->theme . 'purchase_request/modal_view', $this->data);
	}
	
	//################# Add Purchases Request
	
	/**************** Nak 
	***************** Update Function add
	***************** 05/06/2017
	*****************/
	
	public function add($quote_id = null)
    {
        $this->erp->checkPermissions('add', NULL, 'purchase_request');

        $this->form_validation->set_message('is_natural_no_zero', $this->lang->line("no_zero_required"));
        $this->form_validation->set_rules('warehouse', $this->lang->line("warehouse"), 'required|is_natural_no_zero');
        $this->form_validation->set_rules('supplier', $this->lang->line("supplier"), 'required');
        $this->form_validation->set_rules('biller', $this->lang->line("biller"), 'required');
		$this->form_validation->set_rules('reference_no', $this->lang->line("reference_no"), 'required|is_unique[purchases_request.reference_no]');
		
		$this->session->unset_userdata('csrf_token');
		
        if ($this->form_validation->run() == true) {
            $quantity 			= "quantity";
            $product 			= "product";
            $unit_cost 			= "unit_cost";
            $tax_rate 			= "tax_rate";
            $biller_id 			= $this->input->post('biller');
            $reference 			= $this->input->post('reference_no') ? $this->input->post('reference_no') : $this->site->getReference('pq',$biller_id);
			$payment_term 		= $this->input->post('payment_term');
			
            if ($this->Owner || $this->Admin || $this->Settings->allow_change_date == 1) {
                $date 		= $this->erp->fld(trim($this->input->post('date')));
            } else {
                $date 		= date('Y-m-d H:i:s');
            }
            $warehouse_id 		= $this->input->post('warehouse');
            $supplier_id 		= $this->input->post('supplier'); 
			$rsupplier_id 		= $this->input->post('rsupplier_id');
			
			//$this->erp->print_arrays($rsupplier_id);
            $status 			= $this->input->post('status') ? $this->input->post('status') : 'requested';
            $shipping 			= $this->input->post('shipping') ? $this->input->post('shipping') : 0;
            $payment_status 	= $this->input->post('payment_status');
            
			$supplier_details 	= $this->site->getCompanyByID($supplier_id);
            $supplier 			= $supplier_details->company != ''  ? $supplier_details->company : $supplier_details->name;
            $note 				= $this->input->post('note');
			$variant_id 		= $this->input->post('variant_id');

			//$variant_id 		= array_filter($variant_id);
			
            $total 				= 0;
            $product_tax 		= 0;
            $order_tax 			= 0;
            $product_discount 	= 0;
            $order_discount 	= 0;
            $percentage 		= '%';
            $i 					= sizeof($_POST['product']);
            for ($r = 0; $r < $i; $r++) {
                $item_code 		= $_POST['product'][$r];
                $unit_cost 		= $this->erp->formatPurDecimal($_POST['unit_cost'][$r]);
				$unit_cost_real = $unit_cost;
                $real_unit_cost = $this->erp->formatPurDecimal($_POST['real_unit_cost'][$r]);
                $item_quantity  = $_POST['quantity'][$r];
				
				$serial_no  	= $_POST['serial'][$r];
                $p_supplier 	= $_POST['rsupplier_id'][$r];
				$p_price    	= $_POST['price'][$r];
                $p_type     	= $_POST['type'][$r];
                $pro_note     	= $_POST['pro_note'][$r];
				$tax_method		= $_POST['tax_method'][$r];
				$item_peice 	= $_POST['piece'][$r];
				$item_wpeice 	= $_POST['wpiece'][$r];

                $item_option 	= isset($_POST['product_option'][$r]) ? $_POST['product_option'][$r] : NULL;
				
				if($item_option == 'undefined'){
					$item_option = NULL;
				}
				
                $item_tax_rate 	= isset($_POST['product_tax'][$r]) ? $_POST['product_tax'][$r] : null;
                $item_discount 	= isset($_POST['rdiscount'][$r]) ? $_POST['rdiscount'][$r] : null;
				
                $item_expiry 	= (isset($_POST['expiry'][$r]) && !empty($_POST['expiry'][$r])) ? $this->erp->fsd($_POST['expiry'][$r]) : null;

                if (isset($item_code) && isset($real_unit_cost) && isset($unit_cost) && isset($item_quantity)) {
					$product_details = $this->purchases_model->getProductByCode($item_code);
                    if ($item_expiry) {
                        $today = date('Y-m-d');
                        if ($item_expiry <= $today) {
                            $this->session->set_flashdata('error', lang('product_expiry_date_issue') . ' (' . $product_details->name . ')');
                            redirect($_SERVER["HTTP_REFERER"]);
                        }
                    }
                    //$unit_cost = $real_unit_cost;
                    $pr_discount = 0;

                    if (isset($item_discount)) {
                        $discount = $item_discount;
                        $dpos = strpos($discount, $percentage);
                        if ($dpos !== false) {
                            $pds = explode("%", $discount);
                            $pr_discount = (($this->erp->formatPurDecimal($unit_cost)) * (Float) ($pds[0])) / 100;
                        } else {
                            $pr_discount = $this->erp->formatPurDecimal($discount / $item_quantity);
                        }
                    }

                    $unit_cost 			= $this->erp->formatPurDecimal($unit_cost - $pr_discount);
                    $item_net_cost 		= $unit_cost_real;
                    $pr_item_discount 	= $this->erp->formatPurDecimal($pr_discount * $item_quantity);
                    $product_discount 	+= $pr_item_discount;
                    $pr_tax 			= 0;
                    $pr_item_tax 		= 0;
                    $item_tax 			= 0;
                    $tax 				= "";
					$ptax_method 		= ($tax_method == "" ? $product_details->tax_method : $tax_method);
					
                    if (isset($item_tax_rate) && $item_tax_rate != 0) {
                        $pr_tax = $item_tax_rate;
                        $tax_details = $this->site->getTaxRateByID($pr_tax);
                        if ($tax_details->type == 1 && $tax_details->rate != 0) {

                            if ($product_details && $ptax_method == 1) {
                                $item_tax = (($unit_cost) * $tax_details->rate) / 100;
                                $tax = $tax_details->rate . "%";
								$item_net_cost = $unit_cost;
                            } else {
                                $item_tax = (($unit_cost) * $tax_details->rate) / (100 + $tax_details->rate);
                                $tax = $tax_details->rate . "%";
								$item_net_cost = $unit_cost - $item_tax;
								
                            }
							
                        } elseif ($tax_details->type == 2) {
							
                            if ($product_details && $ptax_method == 1) {
                                $item_tax = (($unit_cost) * $tax_details->rate) / 100;
                                $tax = $tax_details->rate . "%";
								$item_net_cost = $unit_cost;
                            } else {
                                $item_tax = (($unit_cost) * $tax_details->rate) / (100 + $tax_details->rate);
                                $tax = $tax_details->rate . "%";
                                $item_net_cost = $unit_cost - $item_tax;
                            }
                            $item_tax = $this->erp->formatPurDecimal($tax_details->rate);
                            $tax = $tax_details->rate;
                        }
						
                        $pr_item_tax = $this->erp->formatPurDecimal($item_tax * $item_quantity);
                    }
					
					$quantity_balance = 0;
					if($item_option != 0) {
						$row = $this->purchases_model->getVariantQtyById($item_option);
						$quantity_balance = $item_quantity * $row->qty_unit;
					}else{
						$quantity_balance = $item_quantity;
					}
                    $product_tax += $pr_item_tax;
					
					if( $ptax_method == 0){
						$subtotal = (($unit_cost_real * $item_quantity) ) - $pr_item_discount;
					}else{
						$subtotal = (($unit_cost_real * $item_quantity) + $pr_item_tax) - $pr_item_discount;
					}
					$setting = $this->site->get_setting();
					$ohmygod = $this->site->getPurchasedItems($product_details->id, $warehouse_id, $item_option);
					
					if(!$setting->accounting_method == 2){
						$real_unit_costs = $this->site->calculateCost($unit_cost, $item_quantity, $shipping);
					} else {
						$real_unit_costs = $this->site->calculateAVCosts($product_details->id, $warehouse_id, $item_net_cost, $unit_cost, $item_quantity, $product_details->name, $item_option, $item_quantity, $shipping);
					}
					
					$products[] = array(
						'product_id' 		=> $product_details->id,
						'product_code' 		=> $item_code,
						'product_name'		=> $product_details->name,
						'option_id' 		=> $item_option,
						'net_unit_cost' 	=> $item_net_cost,
						'unit_cost' 		=> $unit_cost_real,
						'quantity' 			=> $item_quantity,
						'quantity_balance' 	=> $quantity_balance,
						'warehouse_id' 		=> $warehouse_id,
						'item_tax' 			=> $pr_item_tax,
						'tax_rate_id' 		=> $pr_tax,
						'tax' 				=> $tax,
						'discount' 			=> $item_discount,
						'item_discount' 	=> $pr_item_discount,
						'subtotal' 			=> $this->erp->formatPurDecimal($subtotal),
						'expiry' 			=> $item_expiry,
						'real_unit_cost' 	=> $real_unit_costs,
						'date' 				=> date('Y-m-d', strtotime($date)),
						'tax_method'		=> $tax_method,
						'status' 			=> $status,
						'price' 			=> $p_price,
						'note' 				=> $pro_note,
						'type' 				=> $p_type,
						'piece'				=> $item_peice,
						'wpiece' 			=> $item_wpeice
					);
					
					$serial[] = array(
						'product_id'    	=> $product_details->id,
						'serial_number' 	=> $serial_no,
						'warehouse'     	=> $warehouse_id,
						'biller_id'     	=> $this->session->userdata('biller_id')?$this->session->userdata('biller_id'):$this->Settings->default_biller,
						'serial_status' 	=> 1
						
					);
					
					//$this->erp->print_arrays($products);
                    $total += $subtotal;
                }
            }
			
			if($setting->accounting_method == 2 || $shipping){
				$c = count($products);
				
				$t_po_item_amount = 0;
				foreach($products as $p){
					$t_po_item_amount += $p['subtotal'];
				}
				for($i = 0; $i < $c; $i++){
					$item_option = $_POST['product_option'][$i];
					$unitCost = $this->erp->formatPurDecimal($_POST['unit_cost'][$i]);
					$costunit = $this->site->calculateAverageCost($products[$i]['product_id'], $unitCost, $products[$i]['quantity'], $c, $products[$i]['item_discount'], $order_discount, $shipping, $t_po_item_amount, $item_option);
					$products[$i]['real_unit_cost'] = $costunit;
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
                    $order_discount = $this->erp->formatPurDecimal((($total) * (Float) ($ods[0])) / 100);
                } else {
                    $order_discount = $this->erp->formatPurDecimal($total * $order_discount_id);
                }
            } else {
                $order_discount_id = null;
            }
            
			$total_discount = $this->erp->formatPurDecimal($order_discount + $product_discount);
			
            if ($this->Settings->tax2 != 0) {
                $order_tax_id = $this->input->post('order_tax');
                if ($order_tax_details = $this->site->getTaxRateByID($order_tax_id)) {
                    if ($order_tax_details->type == 2) {
                        $order_tax = $this->erp->formatPurDecimal($order_tax_details->rate);
                    }
                    if ($order_tax_details->type == 1) {
                        $order_tax = $this->erp->formatPurDecimal((($total + $product_tax + $shipping - $order_discount) * $order_tax_details->rate) / 100);
                    }
                }
            } else {
                $order_tax_id = null;
            }
			
            $total_tax = $this->erp->formatPurDecimal($product_tax + $order_tax);
            $grand_total = $this->erp->formatPurDecimal($total);
            $data = array(
				'biller_id' 		=> $biller_id,  
				'reference_no' 		=> $reference,
				'payment_term' 		=> $payment_term,
                'date' 				=> $date,
                'supplier_id' 		=> $supplier_id,
                'supplier' 			=> $supplier,
                'warehouse_id' 		=> $warehouse_id,
                'note' 				=> htmlspecialchars($note,ENT_QUOTES),
                'total' 			=> $this->erp->formatPurDecimal($total),
                'product_discount' 	=> $this->erp->formatPurDecimal($product_discount),
                'order_discount_id' => $order_discount_id,
                'order_discount' 	=> $order_discount,
                'total_discount' 	=> $total_discount,
                'product_tax' 		=> $this->erp->formatPurDecimal($product_tax),
                'order_tax_id' 		=> $order_tax_id,
                'order_tax' 		=> $order_tax,
                'total_tax' 		=> $total_tax,
                'shipping' 			=> $this->erp->formatPurDecimal($shipping),
                'grand_total' 		=> $grand_total,
                'status' 			=> ($this->Settings->authorization == 'auto' ? 'approved' : 'requested'),
                'payment_status' 	=> $payment_status,
                'created_by' 		=> $this->session->userdata('user_id'),
            );
			
            if ($_FILES['document']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] 		= $this->digital_upload_path;
                $config['allowed_types'] 	= $this->digital_file_types;
                $config['max_size'] 		= $this->allowed_file_size;
                $config['overwrite'] 		= false;
                $config['encrypt_name'] 	= true;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('document')) {
                    $error 					= $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                $data['attachment'] 		= $photo;
            } 
			//$this->erp->print_arrays($data, $products);
        } 
        
		if ($this->form_validation->run() == true && $this->purchases_request_model->addPurchaseRequest($data, $products)) {
			if($this->Settings->purchase_serial){
				$this->purchases_model->addSerial($serial);
			}
            $this->session->set_userdata('remove_polsr', '1');
            $this->session->set_flashdata('message', $this->lang->line("purchase_added"));
            redirect('purchases_request');
        } else {
            if ($quote_id) {
                $this->data['quote'] = $this->purchases_model->getQuoteByID($quote_id);
                $items = $this->purchases_model->getAllQuoteItems($quote_id);
                $c = rand(100000, 9999999);
                foreach ($items as $item) {
                    $row = $this->site->getProductByID($item->product_id);
					
                    if ($row->type == 'combo') {
                        $combo_items = $this->purchases_model->getProductComboItems($row->id, $warehouse_id);
                        foreach ($combo_items as $citem) {
                            $crow = $this->site->getProductByID($citem->product_id);
                            if (!$crow) {
                                $crow = json_decode('{}');
                                $crow->quantity = 0;
                            } else {
                                unset($crow->details, $crow->product_details);
                            }
                            $crow->discount = $item->discount ? $item->discount : '0';
                            $crow->cost = $crow->cost ? $crow->cost : 0;
                            $crow->tax_rate = $item->tax_rate_id;
                            $crow->real_unit_cost = $crow->cost ? $crow->cost : 0;
                            $crow->expiry = '';
                            $options = $this->purchases_model->getProductOptions($crow->id);

                            $ri = $this->Settings->item_addition ? $crow->id : $c;
                            if ($crow->tax_rate) {
                                $tax_rate = $this->site->getTaxRateByID($crow->tax_rate);
                                $pr[$ri] = array('id' => $c, 'item_id' => $crow->id, 'label' => $crow->name . " (" . $crow->code . ")", 'row' => $crow, 'tax_rate' => $tax_rate, 'options' => $options, 'suppliers' => '' ,'supplier_id' => '');
                            } else {
                                $pr[$ri] = array('id' => $c, 'item_id' => $crow->id, 'label' => $crow->name . " (" . $crow->code . ")", 'row' => $crow, 'tax_rate' => false, 'options' => $options, 'suppliers' => '' ,'supplier_id' => '');
                            }
                            $c++;
                        }
                    } elseif ($row->type == 'standard') {
                        if (!$row) {
                            $row = json_decode('{}');
                            $row->quantity = 0;
                        } else {
                            unset($row->details, $row->product_details);
                        }

                        $row->id = $item->product_id;
                        $row->code = $item->product_code;
                        $row->name = $item->product_name;
                        $row->qty = $item->quantity;
                        $row->option = $item->option_id;
                        $row->discount = $item->discount ? $item->discount : '0';
                        $row->cost = $row->cost ? $row->cost : 0;
                        $row->tax_rate = $item->tax_rate_id;
                        $row->expiry = '';
                        $row->real_unit_cost = $row->cost ? $row->cost : 0;

                        $options = $this->purchases_model->getProductOptions($row->id);

                        $ri = $this->Settings->item_addition ? $row->id : $c;
                        if ($row->tax_rate) {
                            $tax_rate = $this->site->getTaxRateByID($row->tax_rate);
                            $pr[$ri] = array('id' => $c, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'tax_rate' => $tax_rate, 'options' => $options, 'suppliers' => '' ,'supplier_id' => '');
                        } else {
                            $pr[$ri] = array('id' => $c, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'tax_rate' => false, 'options' => $options, 'suppliers' => '' ,'supplier_id' => '');
                        }
						
                        $c++;
                    }
                }
                $this->data['quote_items'] = json_encode($pr);
            }
			
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['quote_id'] = $quote_id;
            
			//$this->erp->print_arrays($psupplier);
            $this->data['categories'] = $this->site->getAllCategories();
			$this->data['unit'] = $this->purchases_model->getUnits();
			$this->data['customers'] = $this->site->getCustomers();
            $this->data['tax_rates'] = $this->site->getAllTaxRates();
            $this->data['warehouses'] = $this->site->getAllWarehouses();
			$this->data['payment_term'] = $this->site->getAllPaymentTerm();
			$this->data['billers'] = $this->site->getAllCompanies('biller');
			$this->data['suppliers'] = $this->site->getAllSuppliers('supplier');

            if ($this->Owner || $this->Admin || !$this->session->userdata('biller_id')){
				$biller_id = $this->site->get_setting()->default_biller;
				$this->data['ponumber'] = $this->site->getReference('pq',$biller_id);
				$this->data['payment_ref'] = $this->site->getReference('pp',$biller_id);
			}else{
				$biller_id = $this->session->userdata('biller_id');
				$this->data['ponumber'] = $this->site->getReference('pq',$biller_id);
				$this->data['payment_ref'] = $this->site->getReference('pp',$biller_id);
			}
			
            $this->load->helper('string');
            $value = random_string('alnum', 20);
			
            $this->session->set_userdata('user_csrf', $value);
            $this->data['csrf'] = $this->session->userdata('user_csrf');
            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('purchase_request'), 'page' => lang('purchase_request')), array('link' => '#', 'page' => lang('add_purchase_request')));
            $meta = array('page_title' => lang('add_purchase_request'), 'bc' => $bc);
            $this->page_construct('purchase_request/add', $meta, $this->data);
        }
    }
	
	/**************** Nak 
	***************** Update Function edit
	***************** 05/09/2017
	*****************/
	
	public function edit($id = null)
    {
        $this->erp->checkPermissions('edit', NULL, 'purchase_request');

        $this->form_validation->set_message('is_natural_no_zero', $this->lang->line("no_zero_required"));
        $this->form_validation->set_rules('warehouse', $this->lang->line("warehouse"), 'required|is_natural_no_zero');
        $this->form_validation->set_rules('supplier', $this->lang->line("supplier"), 'required');
		$this->form_validation->set_rules('biller', $this->lang->line("biller"), 'required');
		
        $this->session->unset_userdata('csrf_token');
        if ($this->form_validation->run() == true) {
            $quantity = "quantity";
            $product = "product";
            $unit_cost = "unit_cost";
            $tax_rate = "tax_rate";
            $reference = $this->input->post('reference_no') ? $this->input->post('reference_no') : $this->site->getReference('pq');
			$payment_term = $this->input->post('payment_term');
			
            if ($this->Owner || $this->Admin || $this->Settings->allow_change_date == 1) {
                $date = $this->erp->fld(trim($this->input->post('date')));
            } else {
                $date = date('Y-m-d H:i:s');
            }
			
            $warehouse_id = $this->input->post('warehouse');
            $supplier_id = $this->input->post('supplier');
			$rsupplier_id = $this->input->post('rsupplier_id');
			$biller_id = $this->input->post('biller');
            $status = $this->input->post('status') ? $this->input->post('status') : 'requested';
            $shipping = $this->input->post('shipping') ? $this->input->post('shipping') : 0;
            $payment_status = $this->input->post('payment_status');
            
			$supplier_details = $this->site->getCompanyByID($supplier_id);
            $supplier = $supplier_details->company != '-'  ? $supplier_details->company : $supplier_details->name;
            
            $note = $this->input->post('note');
			$variant_id = $this->input->post('variant_id');
			
            $total = 0;
            $product_tax = 0;
            $order_tax = 0;
            $product_discount = 0;
            $order_discount = 0;
            $percentage = '%';
            $i = sizeof($_POST['product']);
            for ($r = 0; $r < $i; $r++) {
                $item_code 		= $_POST['product'][$r];
                $item_net_cost 	= $_POST['net_cost'][$r];
                $unit_cost 		= $_POST['unit_cost'][$r];
				$unit_cost_real = $unit_cost;
                $real_unit_cost = $_POST['real_unit_cost'][$r];
                $item_quantity 	= $_POST['quantity'][$r];
				
				$serial_no 		= isset($_POST['serial'][$r])?$_POST['serial'][$r]:null;
                $p_supplier 	= $_POST['rsupplier_id'][$r];
				$p_price 		= isset($_POST['price'][$r])?$_POST['price'][$r]:0;
                $p_type 		= $_POST['type'][$r];
				$tax_method		= $_POST['tax_method'][$r];
				$item_peice		= $_POST['piece'][$r];
				$item_wpeice	= $_POST['wpiece'][$r];
				
                $item_option 	= isset($_POST['product_option'][$r]) ? $_POST['product_option'][$r] : NULL;
				
				if($item_option == 'undefined'){
					$item_option = NULL;
				}
				
                $item_tax_rate = isset($_POST['product_tax'][$r]) ? $_POST['product_tax'][$r] : null;
                $item_discount = isset($_POST['rdiscount'][$r]) ? $_POST['rdiscount'][$r] : null;
                $item_expiry = (isset($_POST['expiry'][$r]) && !empty($_POST['expiry'][$r])) ? $this->erp->fsd($_POST['expiry'][$r]) : null;

                if (isset($item_code) && isset($real_unit_cost) && isset($unit_cost) && isset($item_quantity)) {
					$product_details = $this->purchases_model->getProductByCode($item_code);
                    if ($item_expiry) {
                        $today = date('Y-m-d');
                        if ($item_expiry <= $today) {
                            $this->session->set_flashdata('error', lang('product_expiry_date_issue') . ' (' . $product_details->name . ')');
                            redirect($_SERVER["HTTP_REFERER"]);
                        }
                    }
                    //$unit_cost = $real_unit_cost;
                    $pr_discount = 0;

                    if (isset($item_discount)) {
						$discount = $item_discount;
						$dpos = strpos($discount, $percentage);
						if ($dpos !== false) {
							$pds = explode("%", $discount);
							$pr_discount = (((($unit_cost)) * (Float) ($pds[0])) / 100);
						} else {
							$pr_discount = ($discount/ $item_quantity);
						}
					}

                    $unit_cost 			= ($unit_cost - $pr_discount);
                    $item_net_cost 		= $unit_cost_real;
                    $pr_item_discount 	= ($pr_discount * $item_quantity);
                    $product_discount 	+= $pr_item_discount;
                    $pr_tax 			= 0;
                    $pr_item_tax 		= 0;
                    $item_tax 			= 0;
                    $tax 				= "";
					$ptax_method		= ($tax_method == ""? $product_details->tax_method:$tax_method);
					if (isset($item_tax_rate) && $item_tax_rate != 0) {
						$pr_tax = $item_tax_rate;
						$tax_details = $this->site->getTaxRateByID($pr_tax);
						if ($tax_details->type == 1 && $tax_details->rate != 0) {

							if ($product_details && $ptax_method == 1) {
								$item_tax = ((($unit_cost) * $tax_details->rate) / 100);
								$tax = $tax_details->rate . "%";
								$item_net_cost = $unit_cost;
							} else {
								$item_tax = (($unit_cost) * $tax_details->rate) / (100 + $tax_details->rate);
								$tax = $tax_details->rate . "%";
								$item_net_cost = $unit_cost - $item_tax;
							}
							
						} elseif ($tax_details->type == 2) {
							
							if ($product_details && $ptax_method== 1) {
								$item_tax = ((($unit_cost) * $tax_details->rate) / 100);
								$tax = $tax_details->rate . "%";
								$item_net_cost = $unit_cost;
							} else {
								$item_tax = ((($unit_cost) * $tax_details->rate) / (100 + $tax_details->rate));
								$tax = $tax_details->rate . "%";
								$item_net_cost = $unit_cost - $item_tax;
							}
							
							$item_tax = ($tax_details->rate);
							$tax = $tax_details->rate;
						}
						$pr_item_tax = ($item_tax * $item_quantity);
					}
					
					$quantity_balance = 0;
					if($item_option != 0) {
						$row = $this->purchases_model->getVariantQtyById($item_option);
						$quantity_balance = $item_quantity * $row->qty_unit;
					}else{
						$quantity_balance = $item_quantity;
					}
                    $product_tax += $pr_item_tax;
					
					if( $ptax_method == 0){
						$subtotal = (($unit_cost_real * $item_quantity) ) - $pr_item_discount;
					}else{
						$subtotal = (($unit_cost_real * $item_quantity) + $pr_item_tax) - $pr_item_discount;
					}
					

                   // $subtotal = (($unit_cost_real * $item_quantity) + $pr_item_tax) - $pr_item_discount;
					
					$setting = $this->site->get_setting();
					$ohmygod = $this->site->getPurchasedItems($product_details->id, $warehouse_id, $item_option);
					
					if(!$setting->accounting_method == 2){
						$real_unit_costs = $this->site->calculateCost($unit_cost, $item_quantity, $shipping);
					} else {
						$real_unit_costs = $this->site->calculateAVCosts($product_details->id, $warehouse_id, $item_net_cost, $unit_cost, $item_quantity, $product_details->name, $item_option, $item_quantity, $shipping);
					}
					
					$products[] = array(
						'product_id' 		=> $product_details->id,
						'product_code' 		=> $item_code,
						'product_name' 		=> $product_details->name,
						'option_id' 		=> $item_option,
						'net_unit_cost' 	=> $item_net_cost,
						'unit_cost' 		=> $unit_cost_real, 
						'quantity' 			=> $item_quantity,
						'quantity_balance' 	=> $quantity_balance,
						'warehouse_id' 		=> $warehouse_id,
						'item_tax' 			=> $pr_item_tax,
						'tax_rate_id' 		=> $pr_tax,
						'tax' 				=> $tax,
						'discount' 			=> $item_discount,
						'item_discount' 	=> $pr_item_discount,
						'tax_method'		=> $tax_method,
						'subtotal' 			=> $subtotal,//$this->erp->formatPurDecimal($subtotal),
						'expiry' 			=> $item_expiry,
						'real_unit_cost' 	=> $real_unit_costs,
						'date' 				=> date('Y-m-d', strtotime($date)),
						'status' 			=> $status,
						'price'  			=> $p_price,
						'supplier_id' 		=> $p_supplier,
						'type' 				=> $p_type,
						'piece'				=> $item_peice,
						'wpiece'			=> $item_wpeice
					);
					
					$serial[] = array(
						'product_id'    => $product_details->id,
						'serial_number' => $serial_no,
						'warehouse'     => $warehouse_id,
						'biller_id'     => $biller_id,
						'serial_status' => 1
						
					);
					
                    $total += $subtotal;
                }
            }
			
			if($setting->accounting_method == 2 || $shipping){
				$c = count($products);
				
				$t_po_item_amount = 0;
				foreach($products as $p){
					$t_po_item_amount += $p['subtotal'];
				}
				for($i = 0; $i < $c; $i++){
					$item_option = $_POST['product_option'][$i];
					$unitCost = $this->erp->formatPurDecimal($_POST['unit_cost'][$i]);
					$costunit = $this->site->calculateAverageCost($products[$i]['product_id'], $unitCost, $products[$i]['quantity'], $c, $products[$i]['item_discount'], $order_discount, $shipping, $t_po_item_amount, $item_option);
					$products[$i]['real_unit_cost'] = $costunit;
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
                    $order_discount = $this->erp->formatPurDecimal((($total) * (Float) ($ods[0])) / 100);
                } else {
                    $order_discount = $this->erp->formatPurDecimal(($total) * $order_discount_id);
                }
            } else {
                $order_discount_id = null;
            }
            $total_discount = $this->erp->formatPurDecimal($order_discount + $product_discount);
			
            if ($this->Settings->tax2 != 0) {
                $order_tax_id = $this->input->post('order_tax');
                if ($order_tax_details = $this->site->getTaxRateByID($order_tax_id)) {
                    if ($order_tax_details->type == 2) {
                        $order_tax = $this->erp->formatPurDecimal($order_tax_details->rate);
                    }
                    if ($order_tax_details->type == 1) {
                        $order_tax = $this->erp->formatPurDecimal((($total + $product_tax + $shipping - $order_discount) * $order_tax_details->rate) / 100);
                    }
                }
            } else {
                $order_tax_id = null;
            }
			
            $total_tax = ($product_tax + $order_tax);
            $grand_total = (($total - $order_discount) + $order_tax + $shipping );
            $data = array( 
				'biller_id' 		=> $biller_id,
				'reference_no' 		=> $reference,
				'payment_term' 		=> $payment_term,
                'date' 				=> $date,
                'supplier_id' 		=> $supplier_id,
                'supplier' 			=> $supplier,
                'warehouse_id' 		=> $warehouse_id,
                'note' 				=> htmlspecialchars($note,ENT_QUOTES),
                'total' 			=> $total,
                'product_discount' 	=> $product_discount,
                'order_discount_id' => $order_discount_id,
                'order_discount' 	=> $order_discount,
                'total_discount' 	=> $total_discount,
                'product_tax' 		=> $product_tax,
                'order_tax_id' 		=> $order_tax_id,
                'order_tax' 		=> $order_tax,
                'total_tax' 		=> $total_tax,
                'shipping' 			=> $shipping,
                'grand_total' 		=> $grand_total,
                'status' 			=> 'requested',
                'payment_status' 	=> $payment_status,
                'created_by' 		=> $this->session->userdata('user_id'),
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

			//$this->erp->print_arrays($data, $products);
        }
		
        if ($this->form_validation->run() == true && $this->purchases_request_model->UpdatePurchaseRequest($id,$data, $products)) {
			if($this->Settings->purchase_serial){
				$this->purchases_model->addSerial($serial);
			}
            $this->session->set_userdata('remove_polsr', '1');
            $this->session->set_flashdata('message', $this->lang->line("purchase_added"));
            redirect('purchases_request');
        } else {
			if($id){
				
				$request_id = $this->purchases_request_model->getPurchaseRequestByID($id);
				$this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
				
				if($request_id->status == "approved"){
					$this->session->set_flashdata('error', "Purchase request is approved already. Can not edit more.");
                    redirect('purchases_request');
				}else{
					$this->data['inv'] = $request_id;
					$pref = $this->purchases_model->getPaymentByPurchaseID($id);

					$inv_items = $this->purchases_request_model->getAllPurchaseRequestItems($id);
					
					//$this->erp->print_arrays($inv_items);
					
					$c = rand(100000, 9999999);
					foreach ($inv_items as $item) {
						$row 			= $this->site->getProductByID($item->product_id);
						$row->expiry 	= (($item->expiry && $item->expiry != '0000-00-00') ? $this->erp->fsd($item->expiry) : '');
						$row->qty 		= $item->quantity;

						$row->received 	= $item->quantity_received ? $item->quantity_received : $item->quantity;
						$row->quantity_balance = $item->quantity_balance + ($item->quantity-$row->received);
						$row->discount 	= $item->discount ? $item->discount : '0';
						$options 		= $this->purchases_model->getProductOptions($row->id);
						$row->option 	= $item->option_id;
						$row->real_unit_cost = $item->real_unit_cost;
						$row->cost 		= $this->erp->formatPurDecimal($item->net_unit_cost + ($item->item_discount / $item->quantity));
						$row->tax_rate 	= $item->tax_rate_id;
						$row->net_cost 	= $item->unit_cost;
						$row->price    	= $item->price?$item->price:'0';
						$row->tax_method = $item->tax_method;
						$row->piece		= $item->piece;
						$row->wpiece	= $item->wpiece;
						
						$pii = $this->purchases_model->getPurcahseItemByPurchaseID($id);
						
						unset($row->details, $row->product_details, $row->file, $row->product_group_id);
						$ri = $this->Settings->item_addition ? $row->id : $c;
						if ($row->tax_rate) {
							$tax_rate = $this->site->getTaxRateByID($row->tax_rate);
							$pr[$c] = array('id' => $c, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'tax_rate' => $tax_rate, 'options' => $options, 'suppliers' => '' ,'supplier_id' => $item->supplier_id);
						} else {
							$pr[$c] = array('id' => $c, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'tax_rate' => false, 'options' => $options, 'suppliers' => '' ,'supplier_id' => $item->supplier_id);
						}
						$c++;
					}
					
					
					//$this->erp->print_arrays($pr);
					$this->data['inv_items'] = json_encode($pr);
					
					//$this->data['id'] = $id;
				}
			}

            $biller_id = $this->session->userdata('biller_id');

			$this->load->model('purchases_model');
			$this->data['id'] = $id;
            $this->data['suppliers'] = $this->site->getAllCompanies('supplier');
            $this->data['purchase'] = $this->purchases_request_model->getPurchaseRequestByID($id);
			$this->data['edit_status'] = $id;
            $this->data['categories'] = $this->site->getAllCategories();
			$this->data['unit'] = $this->purchases_model->getUnits();
			$this->data['customers'] = $this->site->getCustomers();
            $this->data['tax_rates'] = $this->site->getAllTaxRates();
            $this->data['warehouses'] = $this->site->getAllWarehouses();
            $this->data['ponumber'] = ''; //$this->site->getReference('po');
			$this->data['payment_term'] = $this->site->getAllPaymentTerm();
            $this->data['billers'] = $this->site->getAllCompanies('biller');

            $this->load->helper('string');
            $value = random_string('alnum', 20);
			$this->session->set_userdata('remove_polsr', '1');
            $this->session->set_userdata('user_csrf', $value);
            $this->data['csrf'] = $this->session->userdata('user_csrf');
            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('purchase_request'), 'page' => lang('purchase_request')), array('link' => '#', 'page' => lang('edit_purchase_request')));
            $meta = array('page_title' => lang('edit_purchase_request'), 'bc' => $bc);
            $this->page_construct('purchase_request/edit', $meta, $this->data);
        }
    }
	
	public function view($purchase_id = null)
    {
        $this->erp->checkPermissions('index', NULL, 'purchase_request');

        if ($this->input->get('id')) {
            $purchase_id = $this->input->get('id');
        }
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $inv = $this->purchases_request_model->getPurchaseRequestByID($purchase_id);
        if (!$this->session->userdata('view_right')) {
            $this->erp->view_rights($inv->created_by);
        }
        $this->data['rows'] 		= $this->purchases_request_model->getAllPurchaseRequestItems($purchase_id);
        $this->data['supplier'] 	= $this->site->getCompanyByID($inv->supplier_id);
        $this->data['warehouse'] 	= $this->site->getEmployees($inv->biller_id);
        $this->data['inv'] 			= $inv;
        $this->data['payments'] 	= $this->purchases_model->getPaymentsForPurchase($purchase_id);
        $this->data['created_by'] 	= $this->site->getUser($inv->created_by);
        $this->data['updated_by'] 	= $inv->updated_by ? $this->site->getUser($inv->updated_by) : null;

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('purchases_request'), 'page' => lang('purchases_request')), array('link' => '#', 'page' => lang('view')));
        $meta = array('page_title' => lang('view_purchase_request_details'), 'bc' => $bc);
        $this->page_construct('purchase_request/view', $meta, $this->data);

    }
	public function invoice($purchase_id = null)
    {

        if ($this->input->get('id')) {
            $purchase_id = $this->input->get('id');
        }
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $inv = $this->purchases_request_model->getPurchaseRequestByID($purchase_id);
        $this->data['rows'] = $this->purchases_request_model->getAllPurchaseRequestItems($purchase_id);
        $this->data['supplier'] = $this->site->getCompanyByID($inv->supplier_id);
        $this->data['billers'] = $this->purchases_request_model->getAllBiller($purchase_id);
        $this->data['warehouse'] = $this->purchases_request_model->getWarehouseByID($inv->warehouse_id);
        $this->data['inv'] = $inv;
        $this->data['payments'] = $this->purchases_model->getPaymentsForPurchase($purchase_id);
        $this->data['created_by'] = $this->site->getUser($inv->created_by);
        $this->data['updated_by'] = $inv->updated_by ? $this->site->getUser($inv->updated_by) : null;
        $this->load->view($this->theme . 'purchase_request/invoice_pr', $this->data);

    }
	public function invoice_phum_meas($purchase_id = null)
    {

        if ($this->input->get('id')) {
            $purchase_id = $this->input->get('id');
        }
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $inv = $this->purchases_request_model->getPurchaseRequestByID($purchase_id);
        $this->data['rows'] = $this->purchases_request_model->getAllPurchaseRequestItems($purchase_id);
        $this->data['supplier'] = $this->site->getCompanyByID($inv->supplier_id);
        $this->data['billers'] = $this->purchases_request_model->getAllBiller($purchase_id);
        $this->data['warehouse'] = $this->purchases_request_model->getWarehouseByID($inv->warehouse_id);
        $this->data['invs'] = $inv;
        $this->data['payments'] = $this->purchases_model->getPaymentsForPurchase($purchase_id);
        $this->data['created_by'] = $this->site->getUser($inv->created_by);
        $this->data['updated_by'] = $inv->updated_by ? $this->site->getUser($inv->updated_by) : null;
        $this->load->view($this->theme . 'purchase_request/invoice_st_a4', $this->data);

    }
	public function delete($id = null)
    {
        $this->erp->checkPermissions('index',null,'purchase_request');
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        if ($this->purchases_request_model->deletePurchaseRequest($id)) {
            if ($this->input->is_ajax_request()) {
                echo lang("purchase_request_deleted");die();
            }
            $this->session->set_flashdata('message', lang('purchase_request_deleted'));
            redirect('welcome');
        }
    }
	// 123
	public function purchase_request_actions()
    {

        $this->form_validation->set_rules('form_action', lang("form_action"), 'required');
        if ($this->form_validation->run() == true) {
            if (!empty($_POST['val'])) {
                if ($this->input->post('form_action') == 'delete') {

                    foreach ($_POST['val'] as $id) {
						$this->erp->checkPermissions('delete');
                        $this->purchases_request_model->deletePurchaseRequest($id);
                    }
                    $this->session->set_flashdata('message', $this->lang->line("purchases_request_deleted"));
                    redirect($_SERVER["HTTP_REFERER"]);

                } elseif ($this->input->post('form_action') == 'combine') {

                    $html = $this->combine_pdf($_POST['val']);

                } elseif ($this->input->post('form_action') == 'purchase_tax'){
					
					$ids = $_POST['val'];
					
					$this->data['modal_js'] = $this->site->modal_js();
					$this->data['ids'] = $ids;

				} elseif ($this->input->post('form_action') == 'export_excel' || $this->input->post('form_action') == 'export_pdf') {
					$row = 2;
					$this->erp->actionPermissions('pdf', 'purchases');
                    $this->load->library('excel');
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle(lang('purchases'));
                    $this->excel->getActiveSheet()->SetCellValue('A1', lang('date'));
                    $this->excel->getActiveSheet()->SetCellValue('B1', lang('reference_no'));
                    $this->excel->getActiveSheet()->SetCellValue('C1', lang('project'));
                    $this->excel->getActiveSheet()->SetCellValue('D1', lang('supplier'));
                    $this->excel->getActiveSheet()->SetCellValue('E1', lang('request_status'));
					$this->excel->getActiveSheet()->SetCellValue('F1', lang('grand_total'));
					$this->excel->getActiveSheet()->SetCellValue('G1', lang('status'));

                    $sum = 0;
                    foreach ($_POST['val'] as $id) {
                        $purchase = $this->purchases_request_model->getPurchaseRequestId($id);
						$sum += $purchase->grand_total;
                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $purchase->date);
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $purchase->reference_no." ");
                        $this->excel->getActiveSheet()->SetCellValue('C' . $row, $purchase->project);
                        $this->excel->getActiveSheet()->SetCellValue('D' . $row, $purchase->supplier);
						$this->excel->getActiveSheet()->SetCellValue('E' . $row, $purchase->order_status);
                        $this->excel->getActiveSheet()->SetCellValue('F' . $row, $this->erp->formatMoney($purchase->grand_total));
                        $this->excel->getActiveSheet()->SetCellValue('G' . $row, $purchase->status);
						
						//Sum grand total   
                        $i = $row+1;                        
                        $this->excel->getActiveSheet()->SetCellValue('F' . $i, $this->erp->formatMoney($sum));
                        $row++;
                    }
					

                    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
					$this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
					$this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
					$this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
					$this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
                    $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);

                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $filename = 'purchases_' . date('Y_m_d_H_i_s');
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
						
						//apply style bold in case PDF
						$this->excel->getActiveSheet()->getStyle('A1:G1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$this->excel->getActiveSheet()->getStyle('A1:G1')->getFont()->setBold(true);
                        $this->excel->getActiveSheet()->getStyle('E'. $i. '')->getFont()->setBold(true);
						$this->excel->getActiveSheet()->getStyle('F'. $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
						$this->excel->getActiveSheet()->getStyle('G'. $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

                        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'PDF');
                        return $objWriter->save('php://output');
                    }
                    if ($this->input->post('form_action') == 'export_excel') {
                        header('Content-Type: application/vnd.ms-excel');
                        header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
                        header('Cache-Control: max-age=0');
						
						//apply style border and bold text in case excel
						$this->excel->getActiveSheet()->getStyle('A1:G1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$this->excel->getActiveSheet()->getStyle('A1:G1')->getFont()->setBold(true);
                        $this->excel->getActiveSheet()->getStyle('F'.$i. '')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                        $this->excel->getActiveSheet()->getStyle('F'. $i. '')->getFont()->setBold(true);
						$this->excel->getActiveSheet()->getStyle('F'. $row. '')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
						$this->excel->getActiveSheet()->getStyle('G'. $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						

                        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
                        return $objWriter->save('php://output');
                    }

                    redirect($_SERVER["HTTP_REFERER"]);
                }
            } else {
                $this->session->set_flashdata('error', $this->lang->line("no_purchase_selected"));
                redirect($_SERVER["HTTP_REFERER"]);
            }
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }

	 public function pdf($purchase_id = null, $view = null, $save_bufffer = null)
    {

        if ($this->input->get('id')) {
            $purchase_id = $this->input->get('id');
        }

        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $inv = $this->purchases_request_model->getPurchaseRequestByID($purchase_id);
        if (!$this->session->userdata('view_right')) {
            $this->erp->view_rights($inv->created_by);
        }
        $this->data['rows'] = $this->purchases_request_model->getAllPurchaseRequestItems($purchase_id);
        $this->data['supplier'] = $this->site->getCompanyByID($inv->supplier_id);
        $this->data['warehouse']    = $this->site->getEmployees($inv->biller_id);
        $this->data['created_by'] = $this->site->getUser($inv->created_by);
        $this->data['inv'] = $inv;
        $name = $this->lang->line("purchase_request") . "_" . str_replace('/', '_', $inv->reference_no) . ".pdf";
        $html = $this->load->view($this->theme . 'purchase_request/pdf', $this->data, true);
        if ($view) {
            $this->load->view($this->theme . 'purchase_request/pdf', $this->data);
        } elseif ($save_bufffer) {
            return $this->erp->generate_pdf($html, $name, $save_bufffer);
        } else {
            $this->erp->generate_pdf($html, $name);
        }
		

    }
     public function combine_pdf($purchases_id)
    {
        $this->erp->checkPermissions('combine_pdf', NULL, 'purchases');

        foreach ($purchases_id as $purchase_id) {

            $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $inv = $this->purchases_request_model->getPurchaseRequestByID($purchase_id);
        if (!$this->session->userdata('view_right')) {
            $this->erp->view_rights($inv->created_by);
        }
        $this->data['rows'] = $this->purchases_request_model->getAllPurchaseRequestItems($purchase_id);
        $this->data['supplier'] = $this->site->getCompanyByID($inv->supplier_id);
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
        $this->data['created_by'] = $this->site->getUser($inv->created_by);
        $this->data['inv'] = $inv;
        $name = $this->lang->line("purchase_request") . "_" . str_replace('/', '_', $inv->reference_no) . ".pdf";

            $html[] = array(
                'content' => $this->load->view($this->theme . 'purchase_request/pdf', $this->data, true),
                'footer' => '',
            );
        }

        $name = lang("purchases") . ".pdf";
        $this->erp->generate_pdf($html, $name);

    }

}
