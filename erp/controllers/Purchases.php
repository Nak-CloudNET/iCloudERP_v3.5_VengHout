<?php defined('BASEPATH') or exit('No direct script access allowed');

class Purchases extends MY_Controller
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
		$this->load->model('reports_model');
        $this->load->model('sales_model');
		$this->load->model('sale_order_model');
        $this->load->model('products_model');
        $this->load->model('quotes_model');
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
        $this->erp->checkPermissions('index',null,'purchases');
		$this->load->model('reports_model');

        $alert_id = $this->input->get('alert_id');
        $this->data['alert_id'] = $alert_id;

		if(isset($_GET['d']) != ""){
			$date = $_GET['d'];
			$this->data['date'] = $date;
		}

		$biller_id = $this->session->userdata('biller_id');
        $this->data['billers'] = $this->site->getAllCompanies('biller');
		$this->data['products'] = $this->site->getProducts();
        $this->data['user_billers'] = $this->purchases_model->getAllCompaniesByID($biller_id);
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
				$this->data['warehouse_id'] = str_replace(',', '-',$this->session->userdata('warehouse_id'));
				$this->data['warehouse'] = $this->session->userdata('warehouse_id') ? $this->products_model->getUserWarehouses() : NULL;
			}
        }

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('purchases')));
        $meta = array('page_title' => lang('purchases'), 'bc' => $bc);
        $this->page_construct('purchases/index', $meta, $this->data);
    }

    public function getPurchases_old($warehouse_id = null)
    {
        $this->erp->checkPermissions('index',null,'purchases');
		if($warehouse_id){
			$warehouse_id = explode('-', $warehouse_id);
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

        $detail_link = anchor('purchases/view/$1', '<i class="fa fa-file-text-o"></i> ' . lang('purchase_details'));
        $payments_link = anchor('purchases/payments/$1', '<i class="fa fa-money"></i> ' . lang('view_payments'), 'data-toggle="modal" data-target="#myModal"');
        $add_payment_link = anchor('purchases/add_payment/$1', '<i class="fa fa-money"></i> ' . lang('add_payment'), 'data-toggle="modal" data-target="#myModal"');
        $email_link = anchor('purchases/email/$1', '<i class="fa fa-envelope"></i> ' . lang('email_purchase'), 'data-toggle="modal" data-target="#myModal"');
        $edit_link = anchor('purchases/edit/$1', '<i class="fa fa-edit"></i> ' . lang('edit_purchase'));
        $pdf_link = anchor('purchases/pdf/$1', '<i class="fa fa-file-pdf-o"></i> ' . lang('download_pdf'));
        $print_barcode = anchor('products/print_barcodes/?purchase=$1', '<i class="fa fa-print"></i> ' . lang('print_barcodes'));
		$purchase_return = anchor('purchases/return_purchase/$1', '<i class="fa fa-angle-double-left"></i> ' . lang('return_purchase'));
		$using_stock = anchor('products/enter_using_stock/$1', '<i class="fa fa-angle-double-left"></i> ' . lang('enter_using_stock'));
        $delete_link = "<a href='#' class='po' title='<b>" . $this->lang->line("delete_purchase") . "</b>' data-content=\"<p>"
        . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('purchases/delete/$1') . "'>"
        . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i> "
        . lang('delete_purchase') . "</a>";
        $action = '<div class="text-center"><div class="btn-group text-left">'
        . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
        . lang('actions') . ' <span class="caret"></span></button>
        <ul class="dropdown-menu pull-right" role="menu">
            <li>' . $detail_link . '</li>
            <li>' . $payments_link . '</li>
           <li>' . $add_payment_link . '</li>'

		   .(($this->Owner || $this->Admin) ? '<li>'.$edit_link.'</li>' : ($this->GP['purchases-edit'] ? '<li>'.$edit_link.'</li>' : '')).

            '<li>' . $pdf_link . '</li>
            <li>' . $email_link . '</li>
            <li>' . $print_barcode . '</li>
			<li>' . $using_stock . '</li>
			<li>' . $purchase_return . '<li>'

			.(($this->Owner || $this->Admin) ? '<li>'.$delete_link.'</li>' : ($this->GP['purchases-delete'] ? '<li>'.$delete_link.'</li>' : '')).

        '</ul>
    </div></div>';
        //$action = '<div class="text-center">' . $detail_link . ' ' . $edit_link . ' ' . $email_link . ' ' . $delete_link . '</div>';

        $this->load->library('datatables');
        if ($warehouse_id) {
            $this->datatables
                ->select("purchases.id, date,request_ref,order_ref,reference_no, companies.name, grand_total,(SELECT SUM((COALESCE(p.amount,0)) FROM erp_payments as p WHERE p.purchase_id = erp_purchases.id AND p.paid_by = 'deposit' ) as total_deposit,(SELECT SUM((COALESCE(p.amount,0)) FROM erp_payments as p WHERE p.purchase_id = erp_purchases.id AND p.paid_by <> 'deposit' ) as paid, (grand_total-paid) as balance,payment_status")
                ->from('purchases')
				->join('companies', 'companies.id = purchases.supplier_id', 'inner')
                ->where_in('warehouse_id', $warehouse_id);
        } else {
			$this->datatables
                ->select("purchases.id,date,request_ref,order_ref,reference_no, companies.name,
				grand_total,(SELECT SUM(COALESCE(p.amount,0)) FROM erp_payments as p WHERE p.purchase_id = erp_purchases.id AND p.paid_by = 'deposit' ) as total_deposit,(SELECT SUM(COALESCE(p.amount,0)) FROM erp_payments as p WHERE p.purchase_id = erp_purchases.id AND p.paid_by <> 'deposit' ) as paid, (grand_total-paid) as balance,payment_status")
                ->from('purchases')
				->join('companies', 'companies.id = purchases.supplier_id', 'inner');

			if(isset($_REQUEST['d'])){
				$date_c = date('Y-m-d', strtotime('+3 months'));
				$date = $_GET['d'];
				$date1 = str_replace("/", "-", $date);
				$date =  date('Y-m-d', strtotime($date1));

				$this->datatables
				->where("date >=", $date)
				->where('DATE_SUB(date, INTERVAL 1 DAY) <= CURDATE()')
				->where('purchases.payment_term <>', 0);
			}
        }

		if (!$this->Customer && !$this->Supplier && !$this->Owner && !$this->Admin && !$this->session->userdata('view_right')) {
            $this->datatables->where('purchases.created_by', $this->session->userdata('user_id'));
        } elseif ($this->Customer) {
            $this->datatables->where('customer_id', $this->session->userdata('user_id'));
        }

		if ($user_query) {
			$this->datatables->where('purchases.created_by', $user_query);
		}

		if ($product) {
			$this->datatables->like('purchase_items.product_id', $product);
		}
		if ($supplier) {
			$this->datatables->where('purchases.supplier_id', $supplier);
		}
		if ($warehouse) {
			$this->datatables->where('purchases.warehouse_id', $warehouse);
		}
		if ($reference_no) {
			$this->datatables->like('purchases.reference_no', $reference_no, 'both');
		}
		if ($start_date) {
			$this->datatables->where($this->db->dbprefix('purchases').'.date BETWEEN "' . $start_date . '" and "' . $end_date . '"');
		}
		if ($note) {
			$this->datatables->like('purchases.note', $note, 'both');
		}
        $this->datatables->add_column("Actions", $action, "purchases.id");
        echo $this->datatables->generate();
    }

	public function getPurchases($warehouse_id = null)
    {
        $this->erp->checkPermissions('index',null,'purchases');
		if($warehouse_id){
			$warehouse_ids = explode('-', $warehouse_id);
		}

		if ($this->input->get('product_id')) {
            $product = $this->input->get('product_id');
        } else {
            $product = NULL;
        }
        if ($this->input->get('user')) {
            $user_query = $this->input->get('user');
        } else {
            $user_query = NULL;
        }
		if ($this->input->get('product_id')) {
            $product_id = $this->input->get('product_id');
        } else {
            $product_id = NULL;
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
        if ($this->input->get('project')) {
            $project = $this->input->get('project');
        } else {
            $project = NULL;
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

        $detail_link = anchor('purchases/modal_view/$1', '<i class="fa fa-file-text-o"></i> ' . lang('purchase_details'),'data-toggle="modal" data-target="#myModal"');
        $payments_link = anchor('purchases/payments/$1', '<i class="fa fa-money"></i> ' . lang('view_payments'), 'data-toggle="modal" data-target="#myModal"');
        $add_payment_link = anchor('purchases/add_payment/$1', '<i class="fa fa-money"></i> ' . lang('add_payment'), 'data-toggle="modal" data-target="#myModal"');
        $email_link = anchor('purchases/email/$1', '<i class="fa fa-envelope"></i> ' . lang('email_purchase'), 'data-toggle="modal" data-target="#myModal"');

        $edit_opening_ap_link = anchor('purchases/edit_opening_ap/$1', '<i class="fa fa-edit"></i> ' . lang('edit_purchase'), 'data-toggle="modal" data-target="#myModal"');
        $edit_link = anchor('purchases/edit/$1', '<i class="fa fa-edit"></i> ' . lang('edit_purchase'));

        //$pdf_link = anchor('purchases/pdf/$1', '<i class="fa fa-file-pdf-o"></i> ' . lang('download_pdf'));
        $print_barcode = anchor('products/print_barcodes/?purchase=$1', '<i class="fa fa-print"></i> ' . lang('print_barcodes'));
		$purchase_return = anchor('purchases/return_purchase/$1', '<i class="fa fa-angle-double-left"></i> ' . lang('return_purchase'));
		$using_stock = anchor('products/enter_using_stock/$1', '<i class="fa fa-angle-double-left"></i> ' . lang('enter_using_stock'));
        $delete_link = "<a href='#' class='po' title='<b>" . $this->lang->line("delete_purchase") . "</b>' data-content=\"<p>"
        . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('purchases/delete/$1') . "'>"
        . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i> "
        . lang('delete_purchase') . "</a>";
        $action = '<div class="text-center"><div class="btn-group text-left">'
        . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
        . lang('actions') . ' <span class="caret"></span></button>
        <ul class="dropdown-menu pull-right" role="menu">
            <li>' . $detail_link . '</li>'

           .(($this->Owner || $this->Admin) ? '<li>'.$payments_link.'</li>' : ($this->GP['purchases-payments'] ? '<li>'.$payments_link.'</li>' : '')).
           (($this->Owner || $this->Admin) ? '<li>'.$add_payment_link.'</li>' : ($this->GP['purchases-payments'] ? '<li>'.$add_payment_link.'</li>' : '')).
           (($this->Owner || $this->Admin) ? '<li class="edit">'.$edit_link.'</li>' : ($this->GP['purchases-edit'] ? '<li class="edit">'.$edit_link.'</li>' : '')).
           (($this->Owner || $this->Admin) ? '<li class="edit_opening_ap">'.$edit_opening_ap_link.'</li>' : ($this->GP['purchases-edit'] ? '<li class="edit_opening_ap">'.$edit_opening_ap_link.'</li>' : '')).
           (($this->Owner || $this->Admin) ? '<li>'.$email_link.'</li>' : ($this->GP['purchases-email'] ? '<li>'.$email_link.'</li>' : '')).
           (($this->Owner || $this->Admin) ? '<li>'.$print_barcode.'</li>' : ($this->GP['products-print_barcodes'] ? '<li>'.$print_barcode.'</li>' : '')).
    '</div></div>';

        $biller_id = $this->session->userdata('biller_id');
        $biller_id =json_decode($biller_id);
        $this->load->library('datatables');
        if ($warehouse_id) {
            $this->datatables
                ->select("
                        purchases.id,
                        purchases.date,
                        purchases_request.reference_no as request_ref,
                        order_ref,
                        purchases.reference_no,
                        erp_project.company AS project,
                        IF(erp_companies.company = '', erp_companies.name, erp_companies.company) AS company,
                        purchases.status,
                        COALESCE(erp_purchases.grand_total,0) as amount,
						COALESCE((SELECT SUM(erp_return_purchases.grand_total) 
						FROM erp_return_purchases 
						WHERE erp_return_purchases.purchase_id = erp_purchases.id), 0) as return_purchases,
                        COALESCE( (SELECT SUM(IF((erp_payments.paid_by != 'deposit' AND ISNULL(erp_payments.return_id)), 
						erp_payments.amount, IF(NOT ISNULL(erp_payments.return_id), ((-1)*erp_payments.amount), 0))) 
						FROM erp_payments WHERE erp_payments.purchase_id = erp_purchases.id),0) as paid, 
						(SELECT SUM(IF(erp_payments.paid_by = 'deposit', erp_payments.amount, 0)) 
						FROM erp_payments WHERE erp_payments.purchase_id = erp_purchases.id  ) as deposit,
						COALESCE ((SELECT SUM(erp_payments.discount)FROM erp_payments WHERE erp_payments.purchase_id=erp_purchases.id),0) AS discount, 
						(COALESCE(erp_purchases.grand_total,0)-COALESCE((SELECT SUM(erp_return_purchases.grand_total) FROM erp_return_purchases
						WHERE erp_return_purchases.purchase_id = erp_purchases.id), 0)-COALESCE( (SELECT SUM(IF((erp_payments.paid_by != 'deposit' AND ISNULL(erp_payments.return_id)), 
						erp_payments.amount, IF(NOT ISNULL(erp_payments.return_id), 
						((-1)*erp_payments.amount), 0))) 
						FROM erp_payments 
						WHERE erp_payments.purchase_id = erp_purchases.id),0)- COALESCE((SELECT SUM(IF(erp_payments.paid_by = 'deposit', 
						erp_payments.amount, 0)) FROM erp_payments 
						WHERE erp_payments.purchase_id = erp_purchases.id  ),0)-COALESCE (
							(
								SELECT
									SUM(erp_payments.discount)
								FROM
									erp_payments
								WHERE
									erp_payments.purchase_id = erp_purchases.id
							),
							0
						)) as balance,
                        purchases.payment_status,
                        purchases.opening_ap,
                        purchases.attachment")
                ->from('purchases')
                ->join('companies', 'companies.id = purchases.supplier_id', 'inner')
                ->join('companies AS erp_project', 'erp_project.id = purchases.biller_id', 'left')
                ->join('users', 'purchases.created_by = users.id', 'left')
                ->join('purchases_order', 'purchases.order_id = purchases_order.id', 'left')
				->join('payments', 'payments.purchase_id = erp_purchases.id', 'left')
                ->join('purchases_request', 'purchases_order.request_id = purchases_request.id', 'left')
                ->where_in('purchases.biller_id',$biller_id)
				->group_by('purchases.id');

                if (count($warehouse_ids) > 1) {
                    $this->datatables->where_in('erp_purchases.warehouse_id', $warehouse_ids);
                } else {
                    $this->datatables->where('erp_purchases.warehouse_id', $warehouse_id);
                }

                if (isset($_REQUEST['a'])) {
                    $alert_ids = explode('-', $_GET['a']);
                    $alert_id  = $_GET['a'];

                    if (count($alert_ids) > 1) {
                        $this->datatables->where('purchases.payment_term <>', 0);
                        $this->datatables->where('DATE_SUB(erp_purchases.date, INTERVAL 1 DAY) <= CURDATE()');
                        $this->datatables->where_in('purchases.id', $alert_ids);
                    } else {
                        $this->datatables->where('purchases.payment_term <>', 0);
                        $this->datatables->where('DATE_SUB(erp_purchases.date, INTERVAL 1 DAY) <= CURDATE()');
                        $this->datatables->where('purchases.id', $alert_id);
                    }
                }

        } else {
			$this->datatables
                ->select("
                        purchases.id,
                        purchases.date,
                        purchases_request.reference_no as request_ref,
                        order_ref,
                        purchases.reference_no,
                        erp_project.company AS project,
                        IF(erp_companies.company = '', erp_companies.name, erp_companies.company) AS company,
                        purchases.status,
                        COALESCE(erp_purchases.grand_total,0) as amount,
						COALESCE((SELECT SUM(erp_return_purchases.grand_total) 
						FROM erp_return_purchases 
						WHERE erp_return_purchases.purchase_id = erp_purchases.id), 0) as return_purchases,
                        COALESCE( (SELECT SUM(IF((erp_payments.paid_by != 'deposit' AND ISNULL(erp_payments.return_id)), 
						erp_payments.amount, IF(NOT ISNULL(erp_payments.return_id), ((-1)*erp_payments.amount), 0))) 
						FROM erp_payments WHERE erp_payments.purchase_id = erp_purchases.id),0) as paid, 
						(SELECT SUM(IF(erp_payments.paid_by = 'deposit', erp_payments.amount, 0)) 
						FROM erp_payments WHERE erp_payments.purchase_id = erp_purchases.id  ) as deposit,
						COALESCE ((SELECT SUM(erp_payments.discount)FROM erp_payments WHERE erp_payments.purchase_id=erp_purchases.id),0) AS discount, 
						(COALESCE(erp_purchases.grand_total,0)-COALESCE((SELECT SUM(erp_return_purchases.grand_total) FROM erp_return_purchases
						WHERE erp_return_purchases.purchase_id = erp_purchases.id), 0)-COALESCE( (SELECT SUM(IF((erp_payments.paid_by != 'deposit' AND ISNULL(erp_payments.return_id)), 
						erp_payments.amount, IF(NOT ISNULL(erp_payments.return_id), 
						((-1)*erp_payments.amount), 0))) 
						FROM erp_payments 
						WHERE erp_payments.purchase_id = erp_purchases.id),0)- COALESCE((SELECT SUM(IF(erp_payments.paid_by = 'deposit', 
						erp_payments.amount, 0)) FROM erp_payments 
						WHERE erp_payments.purchase_id = erp_purchases.id  ),0)-COALESCE (
							(
								SELECT
									SUM(erp_payments.discount)
								FROM
									erp_payments
								WHERE
									erp_payments.purchase_id = erp_purchases.id
							),
							0
						)) as balance,
                        purchases.payment_status,
                        purchases.opening_ap,
						purchases.attachment")
                ->from('purchases')
                ->join('companies', 'companies.id = purchases.supplier_id', 'left')
                ->join('companies AS erp_project', 'erp_project.id = purchases.biller_id', 'left')
                ->join('purchases_order', 'purchases.order_id = purchases_order.id', 'left')
				->join('payments', 'payments.purchase_id = erp_purchases.id', 'left')
                ->join('purchases_request', 'purchases_order.request_id = purchases_request.id', 'left')
				->group_by('purchases.id');

			if (isset($_REQUEST['a'])) {
                $alert_ids = explode('-', $_GET['a']);
                $alert_id  = $_GET['a'];

                if (count($alert_ids) > 1) {
                    $this->datatables->where('purchases.payment_term <>', 0);
                    $this->datatables->where('DATE_SUB(erp_purchases.date, INTERVAL 1 DAY) <= CURDATE()');
                    $this->datatables->where_in('purchases.id', $alert_ids);
                } else {
                    $this->datatables->where('purchases.payment_term <>', 0);
                    $this->datatables->where('DATE_SUB(erp_purchases.date, INTERVAL 1 DAY) <= CURDATE()');
                    $this->datatables->where('purchases.id', $alert_id);
                }
            }
        }

		if (!$this->Customer && !$this->Supplier && !$this->Owner && !$this->Admin && !$this->session->userdata('view_right')) {
            $this->datatables->where('purchases.created_by', $this->session->userdata('user_id'));
        } elseif ($this->Customer) {
            $this->datatables->where('customer_id', $this->session->userdata('user_id'));
        }

		if ($user_query) {
			$this->datatables->where('purchases.created_by', $user_query);
		}

		if ($product) {
			$this->datatables->join('purchase_items', 'purchase_items.purchase_id = purchases.id', 'left');
			$this->datatables->where('purchase_items.product_id', $product);
		}
		if ($supplier) {
			$this->datatables->where('purchases.supplier_id', $supplier);
		}
		if ($warehouse) {
			$this->datatables->where('purchases.warehouse_id', $warehouse);
		}
        if ($project) {
            $this->datatables->where('purchases.biller_id', $project);
        }
		if ($reference_no) {
			$this->datatables->like('purchases.reference_no', $reference_no, 'both');
		}
		if ($start_date) {
			$this->datatables->where($this->db->dbprefix('purchases').'.date BETWEEN "' . $start_date . ' 00:00:00" and "' . $end_date . '  23:59:00"');
		}
		if ($note) {
			$this->datatables->like('purchases.note', $note, 'both');
		}

        $this->datatables->add_column("Actions", $action, "purchases.id");
        echo $this->datatables->generate();
    }

	public function return_purchase($id = null)
    {
        $this->erp->checkPermissions('return_list',null,'purchases');
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        $this->form_validation->set_rules('reference_no', lang("reference_no"), 'required|is_unique[return_purchases.reference_no]');
        $this->form_validation->set_rules('return_surcharge', lang("return_surcharge"), 'required');

        if ($this->form_validation->run() == true) {
            $purchase = $this->purchases_model->getPurchaseByID($id);
            $quantity = "quantity";
            $product = "product";
            $unit_cost = "unit_cost";
            $tax_rate = "tax_rate";
			$podiscount = "podiscount";
			$biller_id = $this->input->post('biller');
            $reference = $this->input->post('reference_no') ? $this->input->post('reference_no') : $this->site->getReference('rep',$biller_id);

			if ($this->Owner || $this->Admin || $this->Settings->allow_change_date == 1) {
                $date = $this->erp->fld(trim($this->input->post('date')));
            } else {
                $date = date('Y-m-d H:i:s');
            }

            $return_surcharge = $this->input->post('return_surcharge') ? $this->input->post('return_surcharge') : 0;
            $note = $this->erp->clear_tags($this->input->post('note'));
            $shipping = $this->input->post('shipping') ? $this->input->post('shipping') : 0;
            $total = 0;
            $product_tax = 0;
            $order_tax = 0;
            $product_discount = 0;
            $order_discount = 0;
            $percentage = '%';
			$olo_amount = 0;
			$olo_discount = 0;
			$olo_tax = 0;
            $total_pay = $this->input->post('total_pay');
            $i = isset($_POST['product']) ? sizeof($_POST['product']) : 0;
            for ($r = 0; $r < $i; $r++) {
                $item_id = $_POST['product_id'][$r];
                $item_code = $_POST['product'][$r];
                $purchase_item_id = $_POST['purchase_item_id'][$r];
                $item_option = isset($_POST['product_option'][$r]) && !empty($_POST['product_option'][$r]) && $_POST['product_option'][$r] != 'false' ? $_POST['product_option'][$r] : null;
                $real_unit_cost = $this->erp->formatDecimal($_POST['real_unit_cost'][$r]);
                //$unit_cost = $this->erp->formatDecimal($_POST['unit_cost'][$r]);
                $unit_cost = $this->erp->formatDecimal($_POST['unit_variant_cost'][$r]);
                $item_quantity = $_POST['quantity'][$r];
                $item_expiry = isset($_POST['expiry'][$r]) ? $_POST['expiry'][$r] : '';
                $item_tax_rate = isset($_POST['product_tax'][$r]) ? $_POST['product_tax'][$r] : null;
                $item_discount = isset($_POST['product_discount'][$r]) ? $_POST['product_discount'][$r] : null;

                if (isset($item_code) && isset($real_unit_cost) && isset($unit_cost) && isset($item_quantity)) {
                    $product_details = $this->purchases_model->getProductByCode($item_code);

                    $item_type = $product_details->type;
                    $item_name = $product_details->name;

                    if (isset($item_discount)) {
                        $discount = $item_discount;
                        $dpos = strpos($discount, $percentage);
                        if ($dpos !== false) {
                            $pds = explode("%", $discount);
                            $pr_discount = (($this->erp->formatDecimal($unit_cost)) * (Float) ($pds[0])) / 100;
                        } else {
                            $pr_discount = $this->erp->formatDecimal($discount);
                        }
                    } else {
                        $pr_discount = 0;
                    }

                    $pr_item_discount = $this->erp->formatDecimal($pr_discount * $item_quantity);
                    $product_discount += $pr_item_discount;

                    if (isset($item_tax_rate) && $item_tax_rate != 0) {
                        $pr_tax = $item_tax_rate;
                        $tax_details = $this->site->getTaxRateByID($pr_tax);
                        if ($tax_details->type == 1 && $tax_details->rate != 0) {

                            if (!$product_details->tax_method) {
                                $item_tax = $this->erp->formatDecimal((($unit_cost) * $tax_details->rate) / (100 + $tax_details->rate));
                                $tax = $tax_details->rate . "%";
                            } else {
                                $item_tax = $this->erp->formatDecimal((($unit_cost) * $tax_details->rate) / 100);
                                $tax = $tax_details->rate . "%";
                            }

                        } elseif ($tax_details->type == 2) {

                            $item_tax = $this->erp->formatDecimal($tax_details->rate);
                            $tax = $tax_details->rate;

                        }
                        $pr_item_tax = $this->erp->formatDecimal($item_tax * $item_quantity);

                    } else {
                        $pr_tax = 0;
                        $pr_item_tax = 0;
                        $tax = "";
                    }

					$item_net_cost = $unit_cost;

                    $product_tax += $pr_item_tax;
                    $subtotal = (($item_net_cost * $item_quantity) + $pr_item_tax);
                    $quantity_balance = 0;
					if($item_option != 0) {
						$row = $this->purchases_model->getVariantQtyById($item_option);
						$quantity_balance = $item_quantity * $row->qty_unit;
					}else{
						$quantity_balance = $item_quantity;
					}
					$old = $this->purchases_model->getPurcahseItemByPurchaseIDProductID($id,$item_id);
					$amount_olo = $old->net_unit_cost * $item_quantity;



                    $products[] = array(
                        'product_id' => $item_id,
                        'product_code' => $item_code,
                        'product_name' => $item_name,
                        'product_type' => $item_type,
                        'option_id' => $item_option,
                        'net_unit_cost' => $item_net_cost,
                        'quantity' => $item_quantity,
						'quantity_balance' 	=> $quantity_balance,
                        'warehouse_id' => $purchase->warehouse_id,
                        'item_tax' => $pr_item_tax,
                        'tax_rate_id' => $pr_tax,
                        'tax' => $tax,
                        'discount' => $item_discount,
                        'item_discount' => $pr_item_discount?$pr_item_discount:0,
                        'subtotal' => $this->erp->formatDecimal($subtotal) ? $this->erp->formatDecimal($subtotal) : 0,
                        'real_unit_cost' => $real_unit_cost,
                        'purchase_item_id' => $purchase_item_id,
						'old_subtotal' => $amount_olo
                    );
					$olo_amount += $old->net_unit_cost * $item_quantity;
                    $total += $item_net_cost * $item_quantity;
                }
            }

            if (empty($products)) {
                $this->form_validation->set_rules('product', lang("order_items"), 'required');
            } else {
                krsort($products);
            }

            if ($this->input->post('discount')) {
                $order_discount_id = $this->input->post('order_discount');
                $opos = strpos($order_discount_id, $percentage);
                if ($opos !== false) {
                    $ods = explode("%", $order_discount_id);
                    $order_discount = $this->erp->formatDecimal((($total + $product_tax) * (Float) ($ods[0])) / 100);
					$olo_discount = $this->erp->formatDecimal((($olo_amount + $product_tax) * (Float) ($ods[0])) / 100);
                } else {
                    $order_discount = $this->erp->formatDecimal((($total + $product_tax) * (Float) ($order_discount_id)) / 100);
					$olo_discount   = $this->erp->formatDecimal((($olo_amount + $product_tax) * (Float) ($order_discount_id)) / 100);
                }
            } else {
                $order_discount_id = null;
            }
            $total_discount = $order_discount + $product_discount;

            if ($this->Settings->tax2) {
                $order_tax_id = $this->input->post('order_tax');
                if ($order_tax_details = $this->site->getTaxRateByID($order_tax_id)) {
                    if ($order_tax_details->type == 2) {
                        $order_tax = $this->erp->formatDecimal($order_tax_details->rate);

						$olo_tax = $this->erp->formatDecimal($order_tax_details->rate);
                    }
                    if ($order_tax_details->type == 1) {
                        $order_tax = $this->erp->formatDecimal((($total + $product_tax - $order_discount + $shipping) * $order_tax_details->rate) / 100);

						$olo_tax = $this->erp->formatDecimal((($olo_amount + $product_tax - $olo_discount + $shipping) * $order_tax_details->rate) / 100);
                    }
                }
            } else {
                $order_tax_id = null;
            }

            $total_tax 	 = $this->erp->formatDecimal($product_tax + $order_tax);

			$grand_total = $this->erp->formatDecimal($this->erp->formatDecimal($total) + $total_tax + $shipping - $this->erp->formatDecimal($return_surcharge) - $order_discount);

			$olo_total 	 = $this->erp->formatDecimal($this->erp->formatDecimal($olo_amount) + $olo_tax + $shipping - $this->erp->formatDecimal($return_surcharge) - $olo_discount);


            $data = array(
				'date' 				=> $date,
                'purchase_id' 		=> $id,
                'reference_no' 		=> $reference,
                'supplier_id' 		=> $purchase->supplier_id,
                'supplier' 			=> $purchase->supplier,
                'warehouse_id' 		=> $purchase->warehouse_id,
                'note' 				=> $note,
                'total' 			=> $this->erp->formatDecimal($total) ? $this->erp->formatDecimal($total):0,
                'product_discount' 	=> $this->erp->formatDecimal($product_discount),
                'order_discount_id' => $order_discount_id ? $order_discount_id : 0,
                'order_discount' 	=> $order_discount ? $order_discount : 0,
                'total_discount' 	=> $total_discount ? $total_discount : 0,
                'product_tax' 		=> $this->erp->formatDecimal($product_tax),
                'order_tax_id' 		=> $order_tax_id ? $order_tax_id : 0,
                'order_tax' 		=> $order_tax ? $order_tax : 0,
                'total_tax' 		=> $total_tax ? $total_tax : 0,
                'shipping' 			=> $shipping ? $shipping : 0,
                'surcharge' 		=> $this->erp->formatDecimal($return_surcharge),
                'grand_total' 		=> $grand_total ? $grand_total : 0,
				'old_grand_total' 	=> $olo_total ? $olo_total : 0,
                'created_by' 		=> $this->session->userdata('user_id'),
                'biller_id' 		=> $this->input->post('biller'),
				'paid' 			    => $this->erp->formatDecimal($this->input->post('amount-paid'))
            );

			if ($this->input->post('amount-paid') && $this->input->post('amount-paid') > 0) {
                $payment = array(
                    'date' 			=> $date,
                    'reference_no' 	=> $this->input->post('payment_ref'),
                    'amount' 		=> $this->erp->formatDecimal($this->input->post('amount-paid')),
                    'paid_by' 		=> $this->input->post('paid_by'),
                    'cheque_no' 	=> $this->input->post('cheque_no'),
                    'cc_no' 		=> $this->input->post('pcc_no'),
                    'cc_holder' 	=> $this->input->post('pcc_holder'),
                    'cc_month' 		=> $this->input->post('pcc_month'),
                    'cc_year' 		=> $this->input->post('pcc_year'),
                    'cc_type' 		=> $this->input->post('pcc_type'),
                    'created_by' 	=> $this->session->userdata('user_id'),
                    'type' 			=> 'returned',
                    'biller_id' 	=> $this->input->post('biller'),
					'add_payment' 	=> '1',
					'bank_account' => $this->input->post('bank_account')
                );
            } else {
                $payment = array();
            }

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

        }

        if ($this->form_validation->run() == true && $this->purchases_model->returnPurchase($data, $products, $payment)) {
            $this->session->set_flashdata('message', lang("return_purchase_added"));
            redirect("purchases/return_purchases");
        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
			$inv = $this->purchases_model->getPurchaseByID($id);

            $this->data['inv']= $inv;
            if ($this->data['inv']->status != 'received' && $this->data['inv']->status != 'partial') {
                $this->session->set_flashdata('error', lang("purchase_status_x_received"));
                redirect($_SERVER["HTTP_REFERER"]);
            }

            if ($this->data['inv']->date <= date('Y-m-d', strtotime('-3 months'))) {
                $this->session->set_flashdata('error', lang("purchase_x_edited_older_than_3_months"));
                redirect($_SERVER["HTTP_REFERER"]);
            }

            $inv_items = $this->purchases_model->getAllPurchaseItems($id);

            $c = rand(100000, 9999999);
            if (is_array($inv_items)) {
                foreach ($inv_items as $item) {
                    $row = $this->site->getProductByID($item->product_id);
                    $row->expiry = (($item->expiry && $item->expiry != '0000-00-00') ? $this->erp->fsd($item->expiry) : '');
                    $row->qty = $item->quantity;
                    $row->oqty = $item->quantity;
                    $row->purchase_item_id = $item->id;
                    $row->supplier_part_no = $item->supplier_part_no;
                    $row->received = $item->quantity_received ? $item->quantity_received : $item->quantity;
                    $row->quantity_balance = $item->quantity_balance + ($item->quantity-$row->received);
                    $row->discount = $item->discount ? $item->discount : '0';
                    $options = $this->purchases_model->getProductOptions($row->id);
                    $row->option = !empty($item->option_id) ? $item->option_id : '';
                    $row->real_unit_cost = $item->real_unit_cost;
                    $row->cost = $this->erp->formatDecimal($item->net_unit_cost + ($item->item_discount / $item->quantity));
                    $row->tax_rate = $item->tax_rate_id;
                    unset($row->details, $row->product_details, $row->price, $row->file, $row->product_group_id);
                    $ri = $this->Settings->item_addition ? $row->id : $c;
                    if ($row->tax_rate) {
                        $tax_rate = $this->site->getTaxRateByID($row->tax_rate);
                        $pr[$ri] = array('id' => $c, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'tax_rate' => $tax_rate, 'options' => $options,'item_tax'=>$item->item_tax, 'net_unit_cost' => $item->net_unit_cost, 'unit_cost' => $item->unit_cost);
                    } else {
                        $pr[$ri] = array('id' => $c, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'tax_rate' => false, 'options' => $options,'item_tax'=>$item->item_tax,'net_unit_cost'=>$item->net_unit_cost, 'unit_cost' => $item->unit_cost);
                    }
                    $c++;

                }
            }

            $this->data['inv_items'] = json_encode($pr);

            $this->data['id'] 			= $id;
			$this->data['payment_ref'] 	= $this->site->getReference('pp');
            $this->data['reference'] 	= $this->site->getReference('rep',$inv->biller_id);
			$this->data['referenceno'] 	= $this->purchases_model->getReferenceno($id);
            $this->data['billers'] 		= $this->site->getAllCompanies('biller');
            $this->data['tax_rates'] 	= $this->site->getAllTaxRates();
			$this->data['bankAccounts'] =  $this->site->getAllBankAccounts();
            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('purchases'), 'page' => lang('purchases')), array('link' => '#', 'page' => lang('return_purchase')));
            $meta = array('page_title' => lang('return_purchase'), 'bc' => $bc);
            $this->page_construct('purchases/return_purchase', $meta, $this->data);
        }
    }

	public function return_purchase_order($id = null)
    {
        $this->erp->checkPermissions('return_purchase_order');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        // $this->form_validation->set_rules('reference_no', lang("reference_no"), 'required');
        $this->form_validation->set_rules('return_surcharge', lang("return_surcharge"), 'required');

        if ($this->form_validation->run() == true) {
            $purchase = $this->purchases_model->getPurchaseByID($id);
            $quantity = "quantity";
            $product = "product";
            $unit_cost = "unit_cost";
            $tax_rate = "tax_rate";
            $reference = $this->input->post('reference_no') ? $this->input->post('reference_no') : $this->site->getReference('rep');
            if ($this->Owner || $this->Admin) {
                $date = $this->erp->fld(trim($this->input->post('date')));
            } else {
                $date = date('Y-m-d H:i:s');
            }

            $return_surcharge = $this->input->post('return_surcharge') ? $this->input->post('return_surcharge') : 0;
            $note = $this->erp->clear_tags($this->input->post('note'));
            $shipping = $this->input->post('shipping') ? $this->input->post('shipping') : 0;

            $total = 0;
            $product_tax = 0;
            $order_tax = 0;
            $product_discount = 0;
            $order_discount = 0;
            $percentage = '%';
			$olo_amount = 0;
			$olo_discount = 0;
			$olo_tax = 0;
            $total_pay = $this->input->post('total_pay');
            $i = isset($_POST['product']) ? sizeof($_POST['product']) : 0;
            for ($r = 0; $r < $i; $r++) {
                $item_id = $_POST['product_id'][$r];
                $item_code = $_POST['product'][$r];
                $purchase_item_id = $_POST['purchase_item_id'][$r];
                $item_option = isset($_POST['product_option'][$r]) && !empty($_POST['product_option'][$r]) && $_POST['product_option'][$r] != 'false' ? $_POST['product_option'][$r] : null;
                //$option_details = $this->purchases_model->getProductOptionByID($item_option);
                $real_unit_cost = $this->erp->formatDecimal($_POST['real_unit_cost'][$r]);
                $unit_cost = $this->erp->formatDecimal($_POST['unit_cost'][$r]);
                $item_quantity = $_POST['quantity'][$r];
                $item_expiry = isset($_POST['expiry'][$r]) ? $_POST['expiry'][$r] : '';
                $item_tax_rate = isset($_POST['product_tax'][$r]) ? $_POST['product_tax'][$r] : null;
                $item_discount = isset($_POST['product_discount'][$r]) ? $_POST['product_discount'][$r] : null;

                if (isset($item_code) && isset($real_unit_cost) && isset($unit_cost) && isset($item_quantity)) {
                    $product_details = $this->purchases_model->getProductByCode($item_code);

                    $item_type = $product_details->type;
                    $item_name = $product_details->name;

                    if (isset($item_discount)) {
                        $discount = $item_discount;
                        $dpos = strpos($discount, $percentage);
                        if ($dpos !== false) {
                            $pds = explode("%", $discount);
                            $pr_discount = (($this->erp->formatDecimal($unit_cost)) * (Float) ($pds[0])) / 100;
                        } else {
                            $pr_discount = $this->erp->formatDecimal($discount);
                        }
                    } else {
                        $pr_discount = 0;
                    }
                    // $unit_cost = $this->erp->formatDecimal($unit_cost - $pr_discount);
                    $pr_item_discount = $this->erp->formatDecimal($pr_discount * $item_quantity);
                    $product_discount += $pr_item_discount;

                    if (isset($item_tax_rate) && $item_tax_rate != 0) {
                        $pr_tax = $item_tax_rate;
                        $tax_details = $this->site->getTaxRateByID($pr_tax);
                        if ($tax_details->type == 1 && $tax_details->rate != 0) {

                            if (!$product_details->tax_method) {
                                $item_tax = $this->erp->formatDecimal((($unit_cost) * $tax_details->rate) / (100 + $tax_details->rate));
                                $tax = $tax_details->rate . "%";
                            } else {
                                $item_tax = $this->erp->formatDecimal((($unit_cost) * $tax_details->rate) / 100);
                                $tax = $tax_details->rate . "%";
                            }

                        } elseif ($tax_details->type == 2) {

                            $item_tax = $this->erp->formatDecimal($tax_details->rate);
                            $tax = $tax_details->rate;

                        }
                        $pr_item_tax = $this->erp->formatDecimal($item_tax * $item_quantity);

                    } else {
                        $pr_tax = 0;
                        $pr_item_tax = 0;
                        $tax = "";
                    }

					//$item_net_cost = $product_details->tax_method ? $this->erp->formatDecimal($unit_cost - $pr_discount) : $this->erp->formatDecimal($unit_cost - $item_tax - $pr_discount);
					$item_net_cost = $unit_cost;

                    $product_tax += $pr_item_tax;
                    if($purchase->payment_status == 'pending'){
                        $subtotal = 0;
                    }else{
                        $subtotal = (($item_net_cost * $item_quantity) + $pr_item_tax);
                        //$subtotal = $total_pay;
                    }

					$old = $this->purchases_model->getPurcahseItemByPurchaseIDProductID($id,$item_id);
					$amount_olo = $old->net_unit_cost * $item_quantity;

                    $products[] = array(
                        'product_id' => $item_id,
                        'product_code' => $item_code,
                        'product_name' => $item_name,
                        'product_type' => $item_type,
                        'option_id' => $item_option,
                        'net_unit_cost' => $item_net_cost,
                        // 'unit_cost' => $this->erp->formatDecimal($item_net_cost + $item_tax),
                        'quantity' => $item_quantity,
                        'warehouse_id' => $purchase->warehouse_id,
                        'item_tax' => $pr_item_tax,
                        'tax_rate_id' => $pr_tax,
                        'tax' => $tax,
                        'discount' => $item_discount,
                        'item_discount' => $pr_item_discount?$pr_item_discount:0,
                        'subtotal' => $this->erp->formatDecimal($subtotal)?$this->erp->formatDecimal($subtotal):0,
                        'real_unit_cost' => $real_unit_cost,
                        'purchase_item_id' => $purchase_item_id,
						'old_subtotal' => $amount_olo
                    );
					$olo_amount += $old->net_unit_cost * $item_quantity;
                    $total += $item_net_cost * $item_quantity;
                }
            }
            if (empty($products)) {
                $this->form_validation->set_rules('product', lang("order_items"), 'required');
            } else {
                krsort($products);
            }

            if ($this->input->post('discount')) {
                $order_discount_id = $this->input->post('order_discount');
                $opos = strpos($order_discount_id, $percentage);
                if ($opos !== false) {
                    $ods = explode("%", $order_discount_id);
                    $order_discount = $this->erp->formatDecimal((($total + $product_tax) * (Float) ($ods[0])) / 100);
					$olo_discount = $this->erp->formatDecimal((($olo_amount + $product_tax) * (Float) ($ods[0])) / 100);
                } else {
                    $order_discount = $this->erp->formatDecimal($order_discount_id);
					$olo_discount = $this->erp->formatDecimal($order_discount_id);
                }
            } else {
                $order_discount_id = null;
            }
            $total_discount = $order_discount + $product_discount;

            if ($this->Settings->tax2) {
                $order_tax_id = $this->input->post('order_tax');
                if ($order_tax_details = $this->site->getTaxRateByID($order_tax_id)) {
                    if ($order_tax_details->type == 2) {
                        $order_tax = $this->erp->formatDecimal($order_tax_details->rate);

						$olo_tax = $this->erp->formatDecimal($order_tax_details->rate);
                    }
                    if ($order_tax_details->type == 1) {
                        $order_tax = $this->erp->formatDecimal((($total + $product_tax - $order_discount + $shipping) * $order_tax_details->rate) / 100);

						$olo_tax = $this->erp->formatDecimal((($olo_amount + $product_tax - $olo_discount + $shipping) * $order_tax_details->rate) / 100);
                    }
                }
            } else {
                $order_tax_id = null;
            }

            $total_tax = $this->erp->formatDecimal($product_tax + $order_tax);
            if($purchase->payment_status == 'pending'){
                $grand_total    = 0;
				$olo_total      = 0;
                $total          = 0;
            }else{
                $grand_total = $this->erp->formatDecimal($this->erp->formatDecimal($total) + $total_tax + $shipping - $this->erp->formatDecimal($return_surcharge) - $order_discount);

				$olo_total = $this->erp->formatDecimal($this->erp->formatDecimal($olo_amount) + $olo_tax + $shipping - $this->erp->formatDecimal($return_surcharge) - $olo_discount);
                //$grand_total = $total_pay;
            }


            $data = array('date' => $date,
                'purchase_id' => $id,
                'reference_no' => $reference,
                'supplier_id' => $purchase->supplier_id,
                'supplier' => $purchase->supplier,
                'warehouse_id' => $purchase->warehouse_id,
                'note' => $note,
                'total' => $this->erp->formatDecimal($total)?$this->erp->formatDecimal($total):0,
                'product_discount' => $this->erp->formatDecimal($product_discount),
                'order_discount_id' => $order_discount_id?$order_discount_id:0,
                'order_discount' => $total_discount?$total_discount:0,
                'total_discount' => $total_discount?$total_discount:0,
                'product_tax' => $this->erp->formatDecimal($product_tax),
                'order_tax_id' => $order_tax_id?$order_tax_id:0,
                'order_tax' => $order_tax?$order_tax:0,
                'total_tax' => $total_tax?$total_tax:0,
                'shipping' => $shipping?$shipping:0,
                'surcharge' => $this->erp->formatDecimal($return_surcharge),
                'grand_total' => $grand_total?$grand_total:0,
				'old_grand_total' => $olo_total?$olo_total:0,
                'created_by' => $this->session->userdata('user_id'),
                'biller_id' => $this->input->post('biller'),
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

        if ($this->form_validation->run() == true && $this->purchases_model->returnPurchase($data, $products)) {
            $this->session->set_flashdata('message', lang("return_purchase_added"));
            redirect("purchases/return_purchases");
        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

            $this->data['inv'] = $this->purchases_model->getPurchaseByID($id);
            if ($this->data['inv']->status != 'received' && $this->data['inv']->status != 'partial') {
                $this->session->set_flashdata('error', lang("purchase_status_x_received"));
                redirect($_SERVER["HTTP_REFERER"]);
            }

            if ($this->data['inv']->date <= date('Y-m-d', strtotime('-3 months'))) {
                $this->session->set_flashdata('error', lang("purchase_x_edited_older_than_3_months"));
                redirect($_SERVER["HTTP_REFERER"]);
            }
            $inv_items = $this->purchases_model->getAllPurchaseItems($id);
            $c = rand(100000, 9999999);
            foreach ($inv_items as $item) {
                $row = $this->site->getProductByID($item->product_id);
                $row->expiry = (($item->expiry && $item->expiry != '0000-00-00') ? $this->erp->fsd($item->expiry) : '');
                $row->qty = $item->quantity;
                $row->oqty = $item->quantity;
                $row->purchase_item_id = $item->id;
                $row->supplier_part_no = $item->supplier_part_no;
                $row->received = $item->quantity_received ? $item->quantity_received : $item->quantity;
                $row->quantity_balance = $item->quantity_balance + ($item->quantity-$row->received);
                $row->discount = $item->discount ? $item->discount : '0';
                $options = $this->purchases_model->getProductOptions($row->id);
                $row->option = !empty($item->option_id) ? $item->option_id : '';
                $row->real_unit_cost = $item->real_unit_cost;
                $row->cost = $this->erp->formatDecimal($item->net_unit_cost + ($item->item_discount / $item->quantity));
                $row->tax_rate = $item->tax_rate_id;
                unset($row->details, $row->product_details, $row->price, $row->file, $row->product_group_id);
                $ri = $this->Settings->item_addition ? $row->id : $c;
                if ($row->tax_rate) {
                    $tax_rate = $this->site->getTaxRateByID($row->tax_rate);
                    $pr[$ri] = array('id' => $c, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'tax_rate' => $tax_rate, 'options' => $options);
                } else {
                    $pr[$ri] = array('id' => $c, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'tax_rate' => false, 'options' => $options);
                }
                $c++;
            }
			//$this->erp->print_arrays($pr);
            $this->data['inv_items'] = json_encode($pr);
            $this->data['id'] = $id;
            $this->data['reference'] = '';

            $this->data['billers'] = $this->site->getAllCompanies('biller');
            $this->data['tax_rates'] = $this->site->getAllTaxRates();
            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('purchases'), 'page' => lang('purchases')), array('link' => '#', 'page' => lang('return_purchase_order')));
            $meta = array('page_title' => lang('return_purchase_order'), 'bc' => $bc);
            $this->page_construct('purchases/return_purchase_order', $meta, $this->data);
        }
    }

	public function return_purchases($warehouse_id = null)
    {
        $this->erp->checkPermissions('return_list', true, 'purchases');

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
				$this->data['warehouse_id'] = str_replace(',', '-',$this->session->userdata('warehouse_id'));
				$this->data['warehouse'] = $this->session->userdata('warehouse_id') ? $this->products_model->getUserWarehouses() : NULL;
			}
        }

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('return_purchases')));
        $meta = array('page_title' => lang('return_purchases'), 'bc' => $bc);
        $this->page_construct('purchases/return_purchases', $meta, $this->data);
    }

	public function return_purchase_actions()
    {
        if (!empty($_POST['val'])) {
            $this->erp->checkPermissions();
            $returnp = $this->input->get('sales') ? $this->input->get('sales') : NULL;

            if ($this->input->post('form_action') == 'export_pdf' || $this->input->post('form_action') == 'export_excel') {
                $ware_id = $this->input->post('warehId');

                    $this->load->library('excel');
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle(lang('list purchase return'));
                    $this->excel->getActiveSheet()->SetCellValue('A1', lang('date'));
                    $this->excel->getActiveSheet()->SetCellValue('B1', lang('reference_no'));
                    $this->excel->getActiveSheet()->SetCellValue('C1', lang('purchase_reference'));
                    $this->excel->getActiveSheet()->SetCellValue('D1', lang('supplier'));
                    $this->excel->getActiveSheet()->SetCellValue('E1', lang('surcharge'));
                    $this->excel->getActiveSheet()->SetCellValue('F1', lang('grand_total'));
                    $this->excel->getActiveSheet()->SetCellValue('G1', lang('paid'));
                    $this->excel->getActiveSheet()->SetCellValue('H1', lang('balance'));
                    $styleArray = array(
                        'font'  => array(
                            'bold'  => true
                        )
                    );

                    $this->excel->getActiveSheet()->getStyle('A1:H1')->applyFromArray($styleArray);
                    $row = 2;
                    $sum_surcharge      = 0;
                    $sum_grandTotal     = 0;
                    $sum_paid           = 0;
                    $sum_balance        = 0;
                    foreach ($_POST['val'] as $id) {
                        //get balance
                        $data_row = $this->purchases_model->getReturnPurchase($id,$ware_id,$returnp);
                        $balance = $data_row->grand_total- $data_row->paid;
                        if($data_row->paid == null){
                            $data_row->paid=0;
                        }
                        //Get sum for each value
                        $sum_surcharge += $data_row->surcharge;
                        $sum_grandTotal += $data_row->grand_total;
                        $sum_paid += $data_row->paid;
                        $sum_balance += $balance;

                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $data_row->date);
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $data_row->ref." ");
                        $this->excel->getActiveSheet()->SetCellValue('C' . $row, $data_row->sal_ref." ");
                        $this->excel->getActiveSheet()->SetCellValue('D' . $row, $data_row->supplier);
                        $this->excel->getActiveSheet()->SetCellValue('E' . $row, $data_row->surcharge);
                        $this->excel->getActiveSheet()->SetCellValue('F' . $row, $data_row->grand_total);
                        $this->excel->getActiveSheet()->SetCellValue('G' . $row, $data_row->paid);
                        $this->excel->getActiveSheet()->SetCellValue('H' . $row, $balance);

                        //To display sum total
                        $i = $row+1;
                        $this->excel->getActiveSheet()->SetCellValue('E' . $i, $sum_surcharge);
                        $this->excel->getActiveSheet()->SetCellValue('F' . $i, $sum_grandTotal);
                        $this->excel->getActiveSheet()->SetCellValue('G' . $i, $sum_paid);
                        $this->excel->getActiveSheet()->SetCellValue('H' . $i, $sum_balance);

                        $row++;
                    }

                    $this->excel->getActiveSheet()->getStyle('A1:H1')->applyFromArray($styleArray);

                    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
                    $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
                    $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
                    $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(10);

                    $filename = lang('list_purchase_return');
                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    if ($this->input->post('form_action') == 'export_pdf') {
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

    					 //Add style bold text in case PDF
                        $this->excel->getActiveSheet()->getStyle('E'. $i. '')->getFont()->setBold(true);
                        $this->excel->getActiveSheet()->getStyle('F'. $i. '')->getFont()->setBold(true);
                        $this->excel->getActiveSheet()->getStyle('G'. $i. '')->getFont()->setBold(true);
                        $this->excel->getActiveSheet()->getStyle('H'. $i. '')->getFont()->setBold(true);

                        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'PDF');
                        $objWriter->save('php://output');
                        exit();
                    }
                    if ($this->input->post('form_action') == 'export_excel') {
                        ob_clean();
                        header('Content-Type: application/vnd.ms-excel');
                        header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
                        header('Cache-Control: max-age=0');

    					//apply style border top and bold text in case excel
                        $this->excel->getActiveSheet()->getStyle('E'.$i. '')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                        $this->excel->getActiveSheet()->getStyle('E'. $i. '')->getFont()->setBold(true);
                        $this->excel->getActiveSheet()->getStyle('F'.$i. '')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                        $this->excel->getActiveSheet()->getStyle('F'. $i. '')->getFont()->setBold(true);
                        $this->excel->getActiveSheet()->getStyle('G'.$i. '')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                        $this->excel->getActiveSheet()->getStyle('G'. $i. '')->getFont()->setBold(true);
                        $this->excel->getActiveSheet()->getStyle('H'.$i. '')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                        $this->excel->getActiveSheet()->getStyle('H'. $i. '')->getFont()->setBold(true);

                        ob_clean();
                        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
                        $objWriter->save('php://output');
                        exit();
                    }
                $this->session->set_flashdata('error', lang('nothing_found'));
                redirect($_SERVER["HTTP_REFERER"]);
            }
        }else {
                $this->session->set_flashdata('error', $this->lang->line("No_purchase_selected. Please select at least one."));
                redirect($_SERVER["HTTP_REFERER"]);
            }
    }
	//    Export to excell and PDF
    public function purchase_order_actions($wh = null) {
        $this->form_validation->set_rules('form_action', lang("form_action"), 'required');
        if ($this->form_validation->run() == true) {
            if (!empty($_POST['val'])) {
                if ($this->input->post('form_action') == 'delete') {

                    foreach ($_POST['val'] as $id) {
                        $this->erp->checkPermissions('delete');
                        $this->purchases_model->deletePurchase($id);
                    }
                    $this->session->set_flashdata('message', $this->lang->line("purchases_deleted"));
                    redirect($_SERVER["HTTP_REFERER"]);
                } elseif ($this->input->post('form_action') == 'combine') {

                    $html = $this->combine_pdf_order($_POST['val']);
                } elseif ($this->input->post('form_action') == 'purchase_tax') {

                    $ids = $_POST['val'];

                    $this->data['modal_js'] = $this->site->modal_js();
                    $this->data['ids'] = $ids;
                } elseif ($this->input->post('form_action') == 'export_excel' || $this->input->post('form_action') == 'export_pdf') {

                    if($this->Owner || $this->Admin || $this->GP['purchase_request-cost']) {

                        $this->erp->actionPermissions('pdf', 'purchases');
                        $this->load->library('excel');
                        $this->excel->setActiveSheetIndex(0);
                        $this->excel->getActiveSheet()->setTitle(lang('purchases_order'));
                        $this->excel->getActiveSheet()->SetCellValue('A1', lang('date'));
                        $this->excel->getActiveSheet()->SetCellValue('B1', lang('po_num'));
                        $this->excel->getActiveSheet()->SetCellValue('C1', lang('pr_num'));
                        $this->excel->getActiveSheet()->SetCellValue('D1', lang('project'));
                        $this->excel->getActiveSheet()->SetCellValue('E1', lang('supplier'));
                        $this->excel->getActiveSheet()->SetCellValue('F1', lang('grand_total'));
                        $this->excel->getActiveSheet()->SetCellValue('G1', lang('order_status'));
                        $this->excel->getActiveSheet()->SetCellValue('H1', lang('status'));
                        $row = 2;
                        $sum = 0;
                        foreach ($_POST['val'] as $id) {
                            $purchase = $this->purchases_model->getPurchaseOrderDetail($id);

                            $sum += $purchase->grand_total;
                            $this->excel->getActiveSheet()->SetCellValue('A' . $row, $this->erp->hrld($purchase->date));
                            $this->excel->getActiveSheet()->SetCellValue('B' . $row, $purchase->reference_no." ");
                            $this->excel->getActiveSheet()->SetCellValue('C' . $row, $purchase->purchase_ref." ");
                            $this->excel->getActiveSheet()->SetCellValue('D' . $row, $purchase->project);
                            $this->excel->getActiveSheet()->SetCellValue('E' . $row, $purchase->supplier);
                            $this->excel->getActiveSheet()->SetCellValue('F' . $row, $purchase->grand_total);
                            $this->excel->getActiveSheet()->SetCellValue('G' . $row, $purchase->order_status);
                            $this->excel->getActiveSheet()->SetCellValue('H' . $row, $purchase->ordered);
    //                      $this->excel->getActiveSheet()->SetCellValue('G' . $row, $this->erp->formatMoneyPurchase($purchase->grand_total));

                            //Sum grand total
                            $i = $row+1;
                            $this->excel->getActiveSheet()->SetCellValue('F' . $i, $sum);
                            $row++;
                        }
                    }else{
						if($wh) {
							$wh = explode('-', $wh);
						}
                        $this->erp->actionPermissions('pdf', 'purchases');
                        $this->load->library('excel');
                        $this->excel->setActiveSheetIndex(0);
                        $this->excel->getActiveSheet()->setTitle(lang('purchases_order'));
                        $this->excel->getActiveSheet()->SetCellValue('A1', lang('date'));
                        $this->excel->getActiveSheet()->SetCellValue('B1', lang('po_num'));
                        $this->excel->getActiveSheet()->SetCellValue('C1', lang('pr_num'));
                        $this->excel->getActiveSheet()->SetCellValue('D1', lang('project'));
                        $this->excel->getActiveSheet()->SetCellValue('E1', lang('supplier'));
                        $this->excel->getActiveSheet()->SetCellValue('F1', lang('grand_total'));
                        $this->excel->getActiveSheet()->SetCellValue('G1', lang('order_status'));
                        $this->excel->getActiveSheet()->SetCellValue('H1', lang('status'));
                        $row = 2;
                        $sum = 0;
                        foreach ($_POST['val'] as $id) {
                            $purchase = $this->purchases_model->getPurchaseOrderDetailByWarehouse($id,$wh);

                            $sum += $purchase->grand_total;
                            $this->excel->getActiveSheet()->SetCellValue('A' . $row, $this->erp->hrld($purchase->date));
                            $this->excel->getActiveSheet()->SetCellValue('B' . $row, $purchase->reference_no." ");
                            $this->excel->getActiveSheet()->SetCellValue('C' . $row, $purchase->purchase_ref." ");
                            $this->excel->getActiveSheet()->SetCellValue('D' . $row, $purchase->project);
                            $this->excel->getActiveSheet()->SetCellValue('E' . $row, $purchase->supplier);
                            $this->excel->getActiveSheet()->SetCellValue('F' . $row, $purchase->grand_total);
                            $this->excel->getActiveSheet()->SetCellValue('G' . $row, $purchase->order_status);
                            $this->excel->getActiveSheet()->SetCellValue('H' . $row, $purchase->ordered);
    //                      $this->excel->getActiveSheet()->SetCellValue('G' . $row, $this->erp->formatMoneyPurchase($purchase->grand_total));

                            //Sum grand total
                            $i = $row+1;
                            $this->excel->getActiveSheet()->SetCellValue('F' . $i, $sum);
                            $row++;
                        }
                    }

                    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);


                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $filename = 'purchases_order' . date('Y_m_d_H_i_s');
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

						$this->excel->getActiveSheet()->getStyle('A1:H1')->getFont()->setBold(true);

                        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'PDF');
                        return $objWriter->save('php://output');
                    }
                    if ($this->input->post('form_action') == 'export_excel') {
                        header('Content-Type: application/vnd.ms-excel');
                        header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
                        header('Cache-Control: max-age=0');

						//apply style border top and bold text in case excel
                        $this->excel->getActiveSheet()->getStyle('F'.$i. '')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                        $this->excel->getActiveSheet()->getStyle('F'. $i. '')->getFont()->setBold(true);
						$this->excel->getActiveSheet()->getStyle('A1:H1')->getFont()->setBold(true);

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

    /* -------------------------------------------------------------------------------- */
    function checkReturn($id){
        if($id){
            $isReturn = $this->purchases_model->getReturnPurchaseByPurchaseID($id);
            if($isReturn){
                echo true;
            }else{
                echo false;
            }
        }
    }

    public function getReturns($warehouse_id = null)
    {
        $this->erp->checkPermissions('return_list', null, 'purchases');
		if($warehouse_id){
			$warehouse_ids = explode('-', $warehouse_id);
		}

        $biller_id = $this->session->userdata('biller_id');
        $this->load->library('datatables');
        if ($warehouse_id) {
            $this->datatables
			 ->select($this->db->dbprefix('return_purchases') . ".id as id,".
				$this->db->dbprefix('return_purchases') . ".date as date, " .
					$this->db->dbprefix('return_purchases') . ".reference_no as ref, " .
					$this->db->dbprefix('purchases') . ".reference_no as sal_ref, " .
					$this->db->dbprefix('return_purchases') . ".supplier, " .
					$this->db->dbprefix('return_purchases') . ".surcharge, " .
					$this->db->dbprefix('return_purchases') . ".grand_total, COALESCE(" .
					$this->db->dbprefix('return_purchases') . ".paid, 0) as paid, 
					(COALESCE(" . $this->db->dbprefix('return_purchases') . ".grand_total, 0) - COALESCE(" . $this->db->dbprefix('return_purchases') . ".paid, 0)) AS balance"

				)
                ->from('return_purchases')
                ->join('purchases', 'purchases.id=return_purchases.purchase_id', 'left')
                ->join('users', 'return_purchases.created_by = users.id', 'left')
                ->where('return_purchases.biller_id', $biller_id)
                ->group_by('return_purchases.id');

                if (count($warehouse_ids) > 1) {
                    $this->datatables->where_in('return_purchases.warehouse_id', $warehouse_ids);
                } else {
                    $this->datatables->where('return_purchases.warehouse_id', $warehouse_id);
                }

        } else {
            $this->datatables
                ->select($this->db->dbprefix('return_purchases') . ".id as id,".
					$this->db->dbprefix('return_purchases') . ".date as date, " .
					$this->db->dbprefix('return_purchases') . ".reference_no as ref, " .
					$this->db->dbprefix('purchases') . ".reference_no as sal_ref, " .
					$this->db->dbprefix('return_purchases') . ".supplier, " .
					$this->db->dbprefix('return_purchases') . ".surcharge, " .
					$this->db->dbprefix('return_purchases') . ".grand_total, COALESCE(" .
					$this->db->dbprefix('return_purchases') . ".paid, 0) as paid, 
					(COALESCE(".$this->db->dbprefix('return_purchases').".grand_total, 0) - COALESCE(".$this->db->dbprefix('return_purchases').".paid, 0)) AS balance "

				)
                ->join('purchases', 'purchases.id=return_purchases.purchase_id', 'left')
                ->from('return_purchases')
                ->group_by('return_purchases.id');
        }

        if (!$this->Customer && !$this->Supplier && !$this->Owner && !$this->Admin && !$this->session->userdata('view_right')) {
            $this->datatables->where('return_purchases.created_by', $this->session->userdata('user_id'));
        } elseif ($this->Supplier) {
            $this->datatables->where('return_purchases.supplier_id', $this->session->userdata('supplier_id'));
        }

        echo $this->datatables->generate();
    }

	public function view_return($id = null)
    {
        $this->erp->checkPermissions('return_purchases');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $inv = $this->purchases_model->getReturnByID($id);
        if (!$this->session->userdata('view_right')) {
            $this->erp->view_rights($inv->created_by);
        }
        $this->data['barcode'] = "<img src='" . site_url('products/gen_barcode/' . $inv->reference_no) . "' alt='" . $inv->reference_no . "' class='pull-left' />";
        $this->data['supplier'] = $this->site->getCompanyByID($inv->supplier_id);
        $this->data['payments'] = $this->purchases_model->getPaymentsForPurchase($id);
        $this->data['user'] = $this->site->getUser($inv->created_by);
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
        $this->data['inv'] = $inv;
        $this->data['rows'] = $this->purchases_model->getAllReturnItems($id);
        $this->data['purchase'] = $this->purchases_model->getPurchaseByID($inv->purchase_id);
        $this->load->view($this->theme.'purchases/view_return', $this->data);
    }

	//-------------pending-------------
	public function getpending_Purchases($warehouse_id = null, $dt = null)
    {
        $this->erp->checkPermissions('index', true,'accounts');
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

        if ($this->input->get('search_id')) {
            $search_id = $this->input->get('search_id');
        } else {
            $search_id = NULL;
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
        $user_warehouse = null;
		if ((!$this->Owner || !$this->Admin) && !$warehouse_id) {
            $user_warehouse = $this->session->userdata('warehouse_id');
		}
        $detail_link = anchor('purchases/view/$1', '<i class="fa fa-file-text-o"></i> ' . lang('purchase_details'));
        $payments_link = anchor('purchases/payments/$1', '<i class="fa fa-money"></i> ' . lang('view_payments'), 'data-toggle="modal" data-target="#myModal"');
        $add_payment_link = anchor('purchases/add_payment/$1', '<i class="fa fa-money"></i> ' . lang('add_payment'), 'data-toggle="modal" data-target="#myModal"');
        $email_link = anchor('purchases/email/$1', '<i class="fa fa-envelope"></i> ' . lang('email_purchase'), 'data-toggle="modal" data-target="#myModal"');
        $edit_link = anchor('purchases/edit/$1', '<i class="fa fa-edit"></i> ' . lang('edit_purchase'));
        $pdf_link = anchor('purchases/pdf/$1', '<i class="fa fa-file-pdf-o"></i> ' . lang('download_pdf'));
        $print_barcode = anchor('products/print_barcodes/?purchase=$1', '<i class="fa fa-print"></i> ' . lang('print_barcodes'));
        $delete_link = "<a href='#' class='po' title='<b>" . $this->lang->line("delete_purchase") . "</b>' data-content=\"<p>"
        . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('purchases/delete/$1') . "'>"
        . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i> "
        . lang('delete_purchase') . "</a>";
        $action = '<div class="text-center"><div class="btn-group text-left">'
        . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
        . lang('actions') . ' <span class="caret"></span></button>
        <ul class="dropdown-menu pull-right" role="menu">
            <li>' . $detail_link . '</li>'

            .(($this->Owner || $this->Admin) ? '<li>'.$payments_link.'</li>' : ($this->GP['purchases-payments'] ? '<li>'.$payments_link.'</li>' : '')).
             (($this->Owner || $this->Admin) ? '<li>'.$add_payment_link.'</li>' : ($this->GP['purchases-payments'] ? '<li>'.$add_payment_link.'</li>' : '')).
             (($this->Owner || $this->Admin) ? '<li>'.$pdf_link.'</li>' : ($this->GP['purchases-export'] ? '<li>'.$pdf_link.'</li>' : '')).
             (($this->Owner || $this->Admin) ? '<li>'.$email_link.'</li>' : ($this->GP['purchases-email'] ? '<li>'.$email_link.'</li>' : '')).
             (($this->Owner || $this->Admin) ? '<li>'.$print_barcode.'</li>' : ($this->GP['products-print_barcodes'] ? '<li>'.$print_barcode.'</li>' : '')).

        '</ul>
    </div></div>';
        //$action = '<div class="text-center">' . $detail_link . ' ' . $edit_link . ' ' . $email_link . ' ' . $delete_link . '</div>';

        $this->load->library('datatables');
        if($user_warehouse){
            $this->datatables
                ->select("id, date, reference_no,order_ref,request_ref, supplier, status, grand_total, paid, (grand_total-paid) as balance, payment_status")
                ->from('purchases')
                ->where('payment_status !=','paid')
                ->where('status !=','returned')
                ->where_in('warehouse_id', JSON_decode($user_warehouse))
                ->where_in('biller_id',JSON_decode($this->session->userdata('biller_id')));
        }else if ($warehouse_id) {
            $this->datatables
                ->select("id, date, reference_no,order_ref,request_ref, supplier, status, grand_total, paid, (grand_total-paid) as balance, payment_status")
                ->from('purchases')
				->where('payment_status !=','paid')
				->where('status !=','returned')
                ->where('warehouse_id', $warehouse_id)
                ->where_in('biller_id',JSON_decode($this->session->userdata('biller_id')));
        } else {
			$this->datatables
                ->select("id, date, reference_no,order_ref,request_ref, supplier, status, grand_total, paid, (grand_total-paid) as balance, payment_status")
                ->from('purchases')
				->where('status !=','returned')
				->where('payment_status !=','paid');
			if(isset($_REQUEST['d'])){
				$date_c = date('Y-m-d', strtotime('+3 months'));
				$date = $_GET['d'];
				$date1 = str_replace("/", "-", $date);
				$date =  date('Y-m-d', strtotime($date1));

				$this->datatables
				->where("date >=", $date)
				->where('payment_status !=','paid')
				->where('DATE_SUB(date, INTERVAL 1 DAY) <= CURDATE()')
				->where('purchases.payment_term <>', 0);

			}

        }

		// search options

        if($search_id) {
            $this->datatables->where('purchases.id', $search_id);
        }
		if ($user_query) {
			$this->datatables->where('purchases.created_by', $user_query);
		}
		if ($product) {
			$this->datatables->like('purchase_items.product_id', $product);
		}
		if ($supplier) {
			$this->datatables->where('purchases.supplier_id', $supplier);
		}
		if ($warehouse) {
			$this->datatables->where('purchases.warehouse_id', $warehouse);
		}
		if ($reference_no) {
			$this->datatables->like('purchases.reference_no', $reference_no, 'both');
		}
		if ($start_date) {
			$this->datatables->where($this->db->dbprefix('purchases').'.date BETWEEN "' . $start_date . ' 00:00:00" and "' . $end_date . ' 23:59:00"');
		}
		if ($note) {
			$this->datatables->like('purchases.note', $note, 'both');
		}
		if($dt == 30){
			$this->datatables->where('date('. $this->db->dbprefix('purchases')  .'.date) > CURDATE() AND date('. $this->db->dbprefix('purchases')  .'.date) <= DATE_ADD(now(), INTERVAL + 30 DAY)');
		}elseif($dt == 60){
			$this->datatables->where('date('. $this->db->dbprefix('purchases')  .'.date) > DATE_ADD(now(), INTERVAL + 30 DAY) AND date('. $this->db->dbprefix('purchases')  .'.date) <= DATE_ADD(now(), INTERVAL + 60 DAY)');
		}elseif($dt == 90){
			$this->datatables->where('date('. $this->db->dbprefix('purchases')  .'.date) > DATE_ADD(now(), INTERVAL + 60 DAY) AND date('. $this->db->dbprefix('purchases')  .'.date) <= DATE_ADD(now(), INTERVAL + 90 DAY)');
		}elseif($dt == 91){
			$this->datatables->where('date(purchases.date) >= DATE_ADD(now(), INTERVAL + 90 DAY)');
		}

        /*if (!$this->Customer && !$this->Supplier && !$this->Owner && !$this->Admin && !$this->session->userdata('view_right')) {
            $this->datatables->where('created_by', $this->session->userdata('user_id'));
        } elseif ($this->Supplier) {
            $this->datatables->where('supplier_id', $this->session->userdata('user_id'));
        }*/
        $this->datatables->add_column("Actions", $action, "id");
        echo $this->datatables->generate();
    }

    /* ----------------------------------------------------------------------------- */
    public function modal_view_ap_aging($purchase_id = null, $type = NULL)
    {
        $this->erp->checkPermissions('index', true);

        if ($this->input->get('id')) {
            $purchase_id = $this->input->get('id');
        }
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $inv = $this->purchases_model->getPurchaseByID($purchase_id);
        if (!$this->session->userdata('view_right')) {
            $this->erp->view_rights($inv->created_by, true);
        }
        $this->data['rows'] = $this->purchases_model->getAllPurchaseItems($purchase_id);
        $this->data['supplier'] = $this->site->getCompanyByID($inv->supplier_id);
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
        $this->data['customer'] = $this->site->getCompanyByID($inv->supplier_id);
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
        $this->data['inv'] = $inv;
        $this->data['payments'] = $this->purchases_model->getPaymentsForPurchase($purchase_id);
        $this->data['created_by'] = $this->site->getUser($inv->created_by);
        $this->data['updated_by'] = $inv->updated_by ? $this->site->getUser($inv->updated_by) : null;
        $this->data['supplier_id'] = $inv->supplier_id;
        $this->data['type_view'] = $type;

        $this->load->view($this->theme . 'purchases/modal_view_ap_aging', $this->data);

    }

    public function modal_view($purchase_id = null)
    {
        $this->erp->checkPermissions('index', true);

        if ($this->input->get('id')) {
            $purchase_id = $this->input->get('id');
        }
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $inv = $this->purchases_model->getPurchaseByID($purchase_id);
        // $this->erp->print_arrays($inv);
		/*
        if (!$this->session->userdata('view_right')) {
            $this->erp->view_rights($inv->created_by, true);
        }
		*/
        $this->data['rows'] = $this->purchases_model->getAllPurchaseItems($purchase_id);
        $this->data['supplier'] = $this->site->getCompanyByID($inv->supplier_id);
        $this->data['warehouse'] = $this->site->getEmployees($inv->biller_id);
        $this->data['inv'] = $inv;
        $this->data['payments'] = $this->purchases_model->getPaymentsForPurchase($purchase_id);
        $this->data['created_by'] = $this->site->getUser($inv->created_by);
        $this->data['updated_by'] = $inv->updated_by ? $this->site->getUser($inv->updated_by) : null;

        $this->load->view($this->theme . 'purchases/modal_view', $this->data);

    }

	public function modal_view_purchase_order($purchase_id = null)
    {
        $this->erp->checkPermissions('index', true);

        if ($this->input->get('id')) {
            $purchase_id = $this->input->get('id');
        }
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $inv = $this->purchases_model->getPurchaseOrderByID($purchase_id);
        // $this->erp->print_arrays($inv);
        /*
		if (!$this->session->userdata('view_right')) {
            $this->erp->view_rights($inv->created_by, true);
        }
		*/
        $this->data['rows'] = $this->purchases_model->getAllPurchaseOrderItems($purchase_id);
        $this->data['supplier'] = $this->site->getCompanyByID($inv->supplier_id);
        $this->data['warehouse'] = $this->site->getEmployees($inv->biller_id);
        $this->data['inv'] = $inv;
        $this->data['payments'] = $this->purchases_model->getPaymentsForPurchase($purchase_id);
        $this->data['created_by'] = $this->site->getUser($inv->created_by);
        $this->data['updated_by'] = $inv->updated_by ? $this->site->getUser($inv->updated_by) : null;

        $this->load->view($this->theme . 'purchases/modal_view_purchase_order', $this->data);

    }

    function tcs_invoice($purchase_id = null){
        // $this->erp->print_arrays($purchase_id = null);
        // $this->erp->checkPermissions('index', true);

        // if ($this->input->get('id')) {
        //     $purchase_id = $this->input->get('id');
        // }
        // $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');

        // $inv = $this->purchases_model->getPurchaseOrderByID($purchase_id);
        // $this->data['biller'] = $this->purchases_model->getBiller($inv->biller_id);
        // // $this->erp->print_arrays($this->purchases_model->getBiller($inv->biller_id));
        // $this->data['rows'] = $this->purchases_model->getAllPurchaseOrderItems($purchase_id);
        // // $this->erp->print_arrays($this->purchases_model->getAllPurchaseOrderItems($purchase_id));
        // $this->data['supplier'] = $this->site->getCompanyByID($inv->supplier_id);
        // $this->erp->print_arrays($this->site->getCompanyByID($inv->supplier_id));
        // $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
        // $this->data['inv'] = $inv;
        // $this->data['payments'] = $this->purchases_model->getPaymentsForPurchase($purchase_id);
        // $this->data['created_by'] = $this->site->getUser($inv->created_by);
        // $this->data['updated_by'] = $inv->updated_by ? $this->site->getUser($inv->updated_by) : null;
        $this->erp->checkPermissions('index', true);

        if ($this->input->get('id')) {
            $purchase_id = $this->input->get('id');
        }
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $inv = $this->purchases_model->getPurchaseOrderByID($purchase_id);
        // $this->erp->print_arrays($this->purchases_model->getPurchaseOrderByID($purchase_id));
        /*
        if (!$this->session->userdata('view_right')) {
            $this->erp->view_rights($inv->created_by, true);
        }
        */
        $this->data['rows'] = $this->purchases_model->getAllPurchaseOrderItems($purchase_id);
        $this->data['biller'] = $this->purchases_model->getBiller($inv->biller_id);
        $this->data['supplier'] = $this->site->getCompanyByID($inv->supplier_id);
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
        $this->data['inv'] = $inv;
        $this->data['payments'] = $this->purchases_model->getPaymentsForPurchase($purchase_id);
        $this->data['created_by'] = $this->site->getUser($inv->created_by);
        $this->data['updated_by'] = $inv->updated_by ? $this->site->getUser($inv->updated_by) : null;

        $this->load->view($this->theme . 'purchases/tcs_invoice', $this->data);
    }

    function phum_meas_purchase_order($purchase_id = null){
        // $this->erp->print_arrays($purchase_id = null);
        // $this->erp->checkPermissions('index', true);

        // if ($this->input->get('id')) {
        //     $purchase_id = $this->input->get('id');
        // }
        // $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');

        // $inv = $this->purchases_model->getPurchaseOrderByID($purchase_id);
        // $this->data['biller'] = $this->purchases_model->getBiller($inv->biller_id);
        // // $this->erp->print_arrays($this->purchases_model->getBiller($inv->biller_id));
        // $this->data['rows'] = $this->purchases_model->getAllPurchaseOrderItems($purchase_id);
        // // $this->erp->print_arrays($this->purchases_model->getAllPurchaseOrderItems($purchase_id));
        // $this->data['supplier'] = $this->site->getCompanyByID($inv->supplier_id);
        // $this->erp->print_arrays($this->site->getCompanyByID($inv->supplier_id));
        // $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
        // $this->data['inv'] = $inv;
        // $this->data['payments'] = $this->purchases_model->getPaymentsForPurchase($purchase_id);
        // $this->data['created_by'] = $this->site->getUser($inv->created_by);
        // $this->data['updated_by'] = $inv->updated_by ? $this->site->getUser($inv->updated_by) : null;
        $this->erp->checkPermissions('index', true);

        if ($this->input->get('id')) {
            $purchase_id = $this->input->get('id');
        }
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $inv = $this->purchases_model->getPurchaseOrderByID($purchase_id);
        //$this->erp->print_arrays($inv);
        /*
        if (!$this->session->userdata('view_right')) {
            $this->erp->view_rights($inv->created_by, true);
        }
        */
        $this->data['rows'] = $this->purchases_model->getAllPurchaseOrderItems($purchase_id);
        $this->data['billers'] = $this->purchases_model->getBiller($inv->biller_id);
        $this->data['supplier'] = $this->site->getCompanyByID($inv->supplier_id);
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
        $this->data['invs'] = $inv;
        $this->data['payments'] = $this->purchases_model->getPaymentsForPurchase($purchase_id);
        $this->data['created_by'] = $this->site->getUser($inv->created_by);
        $this->data['updated_by'] = $inv->updated_by ? $this->site->getUser($inv->updated_by) : null;
        //$this->erp->print_arrays($this->data['rows']);
        $this->load->view($this->theme . 'purchases/invoice_st_a4', $this->data);
    }

    public function view($purchase_id = null)
    {
        $this->erp->checkPermissions('index');

        if ($this->input->get('id')) {
            $purchase_id = $this->input->get('id');
        }
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $inv = $this->purchases_model->getPurchaseByID($purchase_id);
        if (!$this->session->userdata('view_right')) {
            $this->erp->view_rights($inv->created_by);
        }
        $this->data['rows'] = $this->purchases_model->getAllPurchaseItems($purchase_id);
        $this->data['supplier'] = $this->site->getCompanyByID($inv->supplier_id);
        $this->data['warehouse'] = $this->site->getEmployees($inv->biller_id);
        $this->data['inv'] = $inv;
        $this->data['payments'] = $this->purchases_model->getPaymentsForPurchase($purchase_id);
        $this->data['created_by'] = $this->site->getUser($inv->created_by);
        $this->data['updated_by'] = $inv->updated_by ? $this->site->getUser($inv->updated_by) : null;

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('purchases'), 'page' => lang('purchases')), array('link' => '#', 'page' => lang('view')));
        $meta = array('page_title' => lang('view_purchase_details'), 'bc' => $bc);
        $this->page_construct('purchases/view', $meta, $this->data);

    }

    public function received($purchase_id = null)
    {
        $this->erp->checkPermissions('index');

        if ($this->input->get('id')) {
            $purchase_id = $this->input->get('id');
        }
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $inv = $this->purchases_model->getPurchaseByID($purchase_id);
        if (!$this->session->userdata('view_right')) {
            $this->erp->view_rights($inv->created_by);
        }
        $this->data['p_or'] = $this->purchases_model->getAllPurchasesOrder($purchase_id);
        $this->data['rows'] = $this->purchases_model->getAllPurchaseItems($purchase_id);
        $this->data['supplier'] = $this->purchases_model->getCompanyByID($purchase_id);
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
        $this->data['billers'] = $this->purchases_model->getAllBillers($purchase_id);
        $this->data['invs'] = $inv;
        $this->data['payments'] = $this->purchases_model->getPaymentsForPurchase($purchase_id);
        $this->data['created_by'] = $this->site->getUser($inv->created_by);
        $this->data['updated_by'] = $inv->updated_by ? $this->site->getUser($inv->updated_by) : null;
        //$this->erp->print_arrays($this->data);
        $this->load->view($this->theme .'purchases/invoice_receive',$this->data);
        // $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('purchases'), 'page' => lang('purchases')), array('link' => '#', 'page' => lang('view')));
        // $meta = array('page_title' => lang('view_purchase_details'), 'bc' => $bc);
        // $this->page_construct('purchases/invoice_receive', $meta, $this->data);

    }

	public function received_kh($purchase_id = null)
    {
        $this->erp->checkPermissions('index');

        if ($this->input->get('id')) {
            $purchase_id = $this->input->get('id');
        }
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $inv = $this->purchases_model->getPurchaseByID($purchase_id);
        if (!$this->session->userdata('view_right')) {
            $this->erp->view_rights($inv->created_by);
        }
        $this->data['p_or'] = $this->purchases_model->getAllPurchasesOrder($purchase_id);
        $this->data['rows'] = $this->purchases_model->getAllPurchaseItems($purchase_id);
        $this->data['supplier'] = $this->site->getSuppliersByID($inv->supplier_id);
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
        $this->data['billers'] = $this->purchases_model->getAllBillers($inv->biller_id);
        $this->data['inv'] = $inv;
        $this->data['payments'] = $this->purchases_model->getPaymentsForPurchase($purchase_id);
        $this->data['created_by'] = $this->site->getUser($inv->created_by);
        $this->data['updated_by'] = $inv->updated_by ? $this->site->getUser($inv->updated_by) : null;
        $this->load->view($this->theme .'purchases/invoice_receive_kh',$this->data);
    }

	public function view_po($purchase_id = null)
    {
        $this->erp->checkPermissions('index');

        if ($this->input->get('id')) {
            $purchase_id = $this->input->get('id');
        }
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $inv = $this->purchases_model->getPurchaseOrderByID($purchase_id);
        if (!$this->session->userdata('view_right')) {
            $this->erp->view_rights($inv->created_by);
        }
        $this->data['rows'] = $this->purchases_model->getAllPurchaseOrderItems($purchase_id);
        $this->data['supplier'] = $this->site->getCompanyByID($inv->supplier_id);
        $this->data['warehouse'] = $this->site->getEmployees($inv->biller_id);
        $this->data['inv'] = $inv;
        $this->data['payments'] = $this->purchases_model->getPaymentsForPurchase($purchase_id);
        $this->data['created_by'] = $this->site->getUser($inv->created_by);
        $this->data['updated_by'] = $inv->updated_by ? $this->site->getUser($inv->updated_by) : null;

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('purchases'), 'page' => lang('purchases_order')), array('link' => '#', 'page' => lang('view_purchase_order')));
        $meta = array('page_title' => lang('view_purchase_details'), 'bc' => $bc);
        $this->page_construct('purchases/view_po', $meta, $this->data);

    }

    /* ----------------------------------------------------------------------------- */

	//generate pdf and force to download

    public function pdf($purchase_id = null, $view = null, $save_bufffer = null)
    {
        if ($this->input->get('id')) {
            $purchase_id = $this->input->get('id');
        }

        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $inv = $this->purchases_model->getPurchaseByID($purchase_id);

        /*if (!$this->session->userdata('view_right')) {
            $this->erp->view_rights($inv->created_by);
        }*/

        $this->data['rows'] = $this->purchases_model->getAllPurchaseItems($purchase_id);
        $this->data['supplier'] = $this->site->getCompanyByID($inv->supplier_id);
        $this->data['warehouse'] = $this->site->getEmployees($inv->biller_id);
        $this->data['created_by'] = $this->site->getUser($inv->created_by);
        $this->data['inv'] = $inv;
        $name = $this->lang->line("purchase") . "_" . str_replace('/', '_', $inv->reference_no) . ".pdf";
        $html = $this->load->view($this->theme . 'purchases/pdf', $this->data, true);
        if ($view) {
            $this->load->view($this->theme . 'purchases/pdf', $this->data);
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
            $inv = $this->purchases_model->getPurchaseByID($purchase_id);
            if (!$this->session->userdata('view_right')) {
                $this->erp->view_rights($inv->created_by);
            }
            $this->data['rows'] = $this->purchases_model->getAllPurchaseItems($purchase_id);
            $this->data['supplier'] = $this->site->getCompanyByID($inv->supplier_id);
            $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
            $this->data['created_by'] = $this->site->getUser($inv->created_by);
            $this->data['inv'] = $inv;

            $html[] = array(
                'content' => $this->load->view($this->theme . 'purchases/pdf', $this->data, true),
                'footer' => '',
            );
        }

        $name = lang("purchases") . ".pdf";
        $this->erp->generate_pdf($html, $name);

    }

	public function combine_pdf_order($purchases_id)
    {
        $this->erp->checkPermissions('combine_pdf', NULL, 'purchases');

        foreach ($purchases_id as $purchase_id) {

           $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
         $inv = $this->purchases_model->getPurchaseOrderByID($purchase_id);
        if (!$this->session->userdata('view_right')) {
            $this->erp->view_rights($inv->created_by);
        }
        $this->data['rows'] = $this->purchases_model->getAllPurchaseOrderItems($purchase_id);
        $this->data['supplier'] = $this->site->getCompanyByID($inv->supplier_id);
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
        $this->data['created_by'] = $this->site->getUser($inv->created_by);
        $this->data['inv'] = $inv;
        $name = $this->lang->line("purchase_order") . "_" . str_replace('/', '_', $inv->reference_no) . ".pdf";

            $html[] = array(
                'content' => $this->load->view($this->theme . 'purchases/pdf_order', $this->data, true),
                'footer' => '',
            );
        }

        $name = lang("purchases") . ".pdf";
        $this->erp->generate_pdf($html, $name);

    }

    public function email($purchase_id = null)
    {
        $this->erp->checkPermissions('email', NULL, 'purchases');

        if ($this->input->get('id')) {
            $purchase_id = $this->input->get('id');
        }
        $inv = $this->purchases_model->getPurchaseByID($purchase_id);
        $this->form_validation->set_rules('to', $this->lang->line("to") . " " . $this->lang->line("email"), 'trim|required|valid_email');
        $this->form_validation->set_rules('subject', $this->lang->line("subject"), 'trim|required');
        $this->form_validation->set_rules('cc', $this->lang->line("cc"), 'trim');
        $this->form_validation->set_rules('bcc', $this->lang->line("bcc"), 'trim');
        $this->form_validation->set_rules('note', $this->lang->line("message"), 'trim');

        if ($this->form_validation->run() == true) {
            /*if (!$this->session->userdata('view_right')) {
                $this->erp->view_rights($inv->created_by);
            }*/
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
            $supplier = $this->site->getCompanyByID($inv->supplier_id);
            $this->load->library('parser');
            $parse_data = array(
                'reference_number' => $inv->reference_no,
                'contact_person' => $supplier->name,
                'company' => $supplier->company,
                'site_link' => base_url(),
                'site_name' => $this->Settings->site_name,
                'logo' => '<img src="' . base_url() . 'assets/uploads/logos/' . $this->Settings->logo . '" alt="' . $this->Settings->site_name . '"/>',
            );
            $msg = $this->input->post('note');
            $message = $this->parser->parse_string($msg, $parse_data);
            $attachment = $this->pdf($purchase_id, null, 'S');
        } elseif ($this->input->post('send_email')) {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->session->set_flashdata('error', $this->data['error']);
            redirect($_SERVER["HTTP_REFERER"]);
        }

        if ($this->form_validation->run() == true && $this->erp->send_email($to, $subject, $message, null, null, $attachment, $cc, $bcc)) {
            delete_files($attachment);
            $this->db->update('purchases', array('status' => 'ordered'), array('id' => $purchase_id));
            $this->session->set_flashdata('message', $this->lang->line("email_sent"));
            redirect("purchases");
        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

            if (file_exists('./themes/' . $this->theme . '/views/email_templates/purchase.html')) {
                $purchase_temp = file_get_contents('themes/' . $this->theme . '/views/email_templates/purchase.html');
            } else {
                $purchase_temp = file_get_contents('./themes/default/views/email_templates/purchase.html');
            }
            $this->data['subject'] = array('name' => 'subject',
                'id' => 'subject',
                'type' => 'text',
                'value' => $this->form_validation->set_value('subject', lang('purchase_order').' (' . $inv->reference_no . ') '.lang('from').' ' . $this->Settings->site_name),
            );
            $this->data['note'] = array('name' => 'note',
                'id' => 'note',
                'type' => 'text',
                'value' => $this->form_validation->set_value('note', $purchase_temp),
            );
            $this->data['supplier'] = $this->site->getCompanyByID($inv->supplier_id);

            $this->data['id'] = $purchase_id;
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'purchases/email', $this->data);

        }
    }

    /* --------------------------------------------------------------------- */
	function getpeoplebytype($company = null){
		if ($rows = $this->purchases_model->getpeoplebytype($company)) {
			$data = json_encode($rows);
		} else {
			$data = false;
		}
		echo $data;
	}


	/******** Nak
     ********* Update Average cost
     ********* 05/05/2017
     *********/

    public function add($purchase_order_id = null, $quote_id = null, $sale_order_id=null)
    {
        $this->erp->checkPermissions();
		$type_exp = $this->input->post('expance');
        $this->form_validation->set_message('is_natural_no_zero', $this->lang->line("no_zero_required"));

		if($type_exp == "po"){
			$this->form_validation->set_rules('warehouse', $this->lang->line("warehouse"), 'required|is_natural_no_zero');
		}

		$this->form_validation->set_rules('supplier', $this->lang->line("supplier"), 'required');
        $this->form_validation->set_rules('biller', $this->lang->line("biller"), 'required');
		$this->form_validation->set_rules('reference_no', $this->lang->line("reference_no"), 'required|is_unique[purchases.reference_no]');
		$order_referent=$this->purchases_model->getPurchasesOrderbyID($purchase_order_id);
		//$request_referent=$this->purchases_model->getPurchasesReqestbyID($purchase_order_id);

		if($quote_id){
			$sale_q = $this->quotes_model->getQuotesData($quote_id);
			$qid = $sale_q->id;
			 if (($this->quotes_model->getQuotesData($quote_id)->status) == 'pending' ) {
				$this->session->set_flashdata('error', lang('quote_has_not_been_approved_s'));
				redirect($_SERVER['HTTP_REFERER']);
			}
			 if ( ($this->quotes_model->getQuotesData($quote_id)->status) == 'rejected') {
				$this->session->set_flashdata('error', lang('quote_has_been_rejected'));
				redirect($_SERVER['HTTP_REFERER']);
			}

		}

		if($sale_order_id){
			$sale_o = $this->sale_order_model->getSaleOrder($sale_order_id);
			$qid = $sale_o->quote_id;
			$sale_q = $this->quotes_model->getQuotesData($sale_o->quote_id);
			$qid = $sale_q->quote_id;

			if(($this->sale_order_model->getSaleOrder($sale_order_id)->order_status) == 'pending'){
				$this->session->set_flashdata('error', lang("sale_order_n_approved"));
				redirect($_SERVER["HTTP_REFERER"]);
			}
			if(($this->sale_order_model->getSaleOrder($sale_order_id)->order_status) == 'rejected'){
				$this->session->set_flashdata('error', lang("sale_order_has_been_rejected"));
				redirect($_SERVER["HTTP_REFERER"]);
			}

			if(($this->sale_order_model->getSaleOrder($sale_order_id)->sale_status) != 'order'){
				$this->session->set_flashdata('error', lang("sale_order_has_been_created"));
				redirect($_SERVER["HTTP_REFERER"]);
			}
		}

        //$this->session->unset_userdata('csrf_token');
        if ($this->form_validation->run() == true)
        {

            if ($this->Owner || $this->Admin || $this->Settings->allow_change_date == 1) {
				$date = $this->erp->fld($this->input->post('date'));
            } else {
                $date = date('Y-m-d H:i:s');
            }

			$quantity                = "quantity";
            $product                 = "product";
            $unit_cost               = "unit_cost";
            $tax_rate                = "tax_rate";
			$biller_id               = $this->input->post('biller');
            $reference               = $this->input->post('reference_no') ? $this->input->post('reference_no') : $this->site->getReference('po',$biller_id);

			$payment_term            = $this->input->post('payment_term');
            $payment_term_details    = $this->site->getAllPaymentTermByID($payment_term);
            $due_date                = $payment_term_details[0]->id ? date('Y-m-d', strtotime($date . '+' . $payment_term_details[0]->due_day . ' days')) : NULL;

			$reference_order         = $this->input->post('reference_no_order');
			$reference_no_request    = $this->input->post('reference_no_request');
			$amount_o                = $this->input->post('amount_o');
			$paid_by                 = $this->input->post('paid_by')?$this->input->post('paid_by'):'';
			$sale_order_id           = $this->input->post('so_id');
            $warehouse_id            = $this->input->post('warehouse');
            $supplier_id             = $this->input->post('supplier');
			$rsupplier_id            = $this->input->post('rsupplier_id');
            $shipping                = $this->input->post('shipping') ? $this->input->post('shipping') : 0;
            $payment_status          = $this->input->post('payment_status');
			$status                  = $this->input->post('purchase_status');

			$supplier_details        = $this->site->getCompanyByID($supplier_id);
            $supplier                = $supplier_details->company != '-'  ? $supplier_details->company : $supplier_details->name;

            $note                    = $this->input->post('note');
			$variant_id              = $this->input->post('variant_id');

			//$variant_id = array_filter($variant_id);

			if($type_exp == 'po'){
				$total 				= 0;
				$product_tax 		= 0;
				$order_tax 			= 0;
				$product_discount 	= 0;
				$order_discount 	= 0;
				$stotal 			= 0;
				$percentage 		= '%';
				$amount 			= array();
				$qty 				= array();
				$i 					= sizeof($_POST['product']);
				$sum_same_pro 		= 0;
				$item_cost 			= 0;
				for ($r = 0; $r < $i; $r++) {
					$item_code 		= $_POST['product'][$r];
					$pro_id 		= $_POST['product_id'][$r];
					$item_net_cost 	= $_POST['net_cost'][$r];
					$unit_cost 		= $_POST['unit_cost'][$r];
					$unit_cost_real = $unit_cost;
					$real_unit_cost = $_POST['real_unit_cost'][$r];
					$item_quantity 	= ($_POST['received'][$r] > 0 ? $_POST['received'][$r]:$_POST['quantity'][$r]);
					$serial_no 		= $_POST['serial'][$r];
					$create_id 		= $_POST['create_id'][$r];
					$p_supplier 	= $_POST['rsupplier_id'][$r];
					$p_price 		= $_POST['price'][$r];
					$p_type 		= $_POST['type'][$r];
					$tax_method		= $_POST['tax_method'][$r];
					$pur_order_id	= $_POST['pur_order_id'][$r];
					$item_piece		= $_POST['piece'][$r];
					$item_wpiece	= $_POST['wpiece'][$r];
					$item_option 	= isset($_POST['product_option'][$r]) ? $_POST['product_option'][$r] : NULL;

					if($item_option == 'undefined'){
						$item_option = NULL;
					}

					$item_tax_rate 	= isset($_POST['product_tax'][$r]) ? $_POST['product_tax'][$r] : null;
					$item_discount 	= isset($_POST['product_discount'][$r]) ? $_POST['product_discount'][$r] : null;
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
								$pr_discount = ((($unit_cost) * (Float) ($pds[0])) / 100);
							} else {
								$pr_discount = ($discount/ $item_quantity);
							}
						}

						$unit_cost 			= ($unit_cost - $pr_discount);
						$item_net_cost 		= $unit_cost;
						$pr_item_discount 	= ($pr_discount * $item_quantity);
						$product_discount 	+= $pr_item_discount;
						$pr_tax 			= 0;
						$pr_item_tax 		= 0;
						$item_tax 			= 0;
						$cogs_inv 			= 0;
						$tax 				= "";
						$net_unit_cost		= $unit_cost;
						$ptax_method        = ($tax_method == ""? $product_details->tax_method:$tax_method);

						if (isset($item_tax_rate) && $item_tax_rate != 0) {
							$pr_tax 		= $item_tax_rate;
							$tax_details 	= $this->site->getTaxRateByID($pr_tax);

							if ($tax_details->type == 1 && $tax_details->rate != 0) {

								if ($product_details && $ptax_method == 1) {
									$item_tax 		= ($unit_cost * $tax_details->rate) / 100;
									$tax 			= $tax_details->rate . "%";
									$item_net_cost 	= $unit_cost + $item_tax;
									$net_unit_cost  = $unit_cost;
								} else {
									$item_tax 		= ($unit_cost * $tax_details->rate) / (100 + $tax_details->rate);
									$tax 			= $tax_details->rate . "%";
									$item_net_cost 	= $unit_cost;
									$net_unit_cost  = $unit_cost - $item_tax;
								}

							} elseif ($tax_details->type == 2) {

								if ($product_details && $ptax_method == 1) {
									$item_tax 		= ($unit_cost * $tax_details->rate) / 100;
									$tax 			= $tax_details->rate . "%";
									$item_net_cost 	= $unit_cost + $item_tax;
									$net_unit_cost  = $unit_cost;
								} else {
									$item_tax		= ($unit_cost * $tax_details->rate) / (100 + $tax_details->rate);
									$tax 			= $tax_details->rate . "%";
									$item_net_cost 	= $unit_cost;
									$net_unit_cost  = $unit_cost - $item_tax;
								}

								$item_tax = ($tax_details->rate);
								$tax = $tax_details->rate;
							}
							$pr_item_tax = ($item_tax * $item_quantity);
						}

						$quantity_balance = 0;
						if ($item_option != 0) {
							$row = $this->purchases_model->getVariantQtyById($item_option);
							$quantity_balance = $item_quantity * $row->qty_unit;
						} else {
							$quantity_balance = $item_quantity;
						}

						$product_tax += $pr_item_tax;

						$subtotal 	= ($item_net_cost * $item_quantity);

						$amount[] 	= $subtotal;
						$qty[] 		= $item_quantity;
						$setting 	= $this->site->get_setting();

						$products[] = array(
							'create_id' 		=> $create_id,
							'product_id' 		=> $product_details->id,
							'product_code' 		=> $item_code,
							'product_name' 		=> $product_details->name,
							'option_id' 		=> $item_option,
							'net_unit_cost' 	=> $net_unit_cost,
							'unit_cost' 		=> $unit_cost_real,
							'quantity' 			=> $item_quantity,
							'quantity_balance' 	=> $quantity_balance,
							'warehouse_id' 		=> $warehouse_id,
							'tax_method'		=> $tax_method,
							'item_tax' 			=> $pr_item_tax,
							'tax_rate_id' 		=> $pr_tax,
							'tax' 				=> $tax,
							'discount' 			=> $item_discount,
							'item_discount' 	=> $pr_item_discount,
							'subtotal' 			=> $subtotal,
							'expiry' 			=> $item_expiry,
							'real_unit_cost' 	=> $real_unit_cost,
							'date' 				=> date('Y-m-d', strtotime($date)),
							'status' 			=> "received",//$status,
							'price' 			=> $p_price?$p_price:$product_details->price,
                            'transaction_type' 	=> 'PURCHASE',
							'cb_avg'			=> $product_details->cost,
							'cb_qty'			=> $product_details->quantity,
							'product_type' 		=> $p_type,
							'pur_order_id'		=> $pur_order_id,
							'piece'				=> $item_piece,
							'wpiece'			=> $item_wpiece
						);

						$serial[] = array(
							'product_id'    	=> $product_details->id,
							'serial_number' 	=> $serial_no,
							'warehouse'     	=> $warehouse_id,
							'biller_id'     	=> $biller_id,
							'serial_status' 	=> 1
						);

						$total 		+= $subtotal;
						if($p_type != 'service') {
							$stotal += ($subtotal);
						}
						if($product_details->quantity < 0) {
							$cogs_inv += (($real_unit_cost - $product_details->cost) * $product_details->quantity);
						}

						//============== Variants For AVG ===============//
						$qty_variant = 0;
						$cos_variant = 0;
						$variant = $this->site->getUnitQuantity($item_option, $product_details->id);
						$qty_variant = $item_quantity;
						$cos_variant = $item_net_cost;

						if($variant){
							$qty_variant = $item_quantity * $variant->qty_unit;
							$cos_variant = $item_net_cost / $variant->qty_unit;
						}

						$avg_cost[] = array(
							'product_id' 	=> $product_details->id,
							'quantity'   	=> $qty_variant,
							'unit_cost'  	=> $cos_variant,
							'price'      	=> $p_price,
							'subtotal'		=> $qty_variant * $cos_variant,
							'option_id'  	=> $item_option,
						);
						//==================== End =====================//

					}
				}

				$out  = array();
				foreach ($avg_cost as $key => $value){
					if (array_key_exists($value['product_id'], $out)){
						$out[$value['product_id']]['product_id'] = $value['product_id'];
						$out[$value['product_id']]['quantity'] 	+= $value['quantity'];
						$out[$value['product_id']]['unit_cost'] += $value['unit_cost'];
						$out[$value['product_id']]['price'] 	+= $value['price'];
						$out[$value['product_id']]['subtotal'] 	+= $value['subtotal'];
						$out[$value['product_id']]['option_id']  = $value['option_id'];
					} else {
						$out[$value['product_id']] = array(
							'product_id' => $value['product_id'],
							'quantity'   => $value['quantity'],
							'unit_cost'  => $value['unit_cost'],
							'price'      => $value['price'],
							'subtotal'   => $value['subtotal'],
							'option_id'  => $value['option_id'],
						);
					}
				}

				$array_c = array_values($out);

				if($setting->accounting_method == 2 || $shipping){
					$c 					= count($array_c);
					$t_po_item_amount 	= 0;
					$total_price 		= 0;
					$a 					= 0;
					foreach($array_c as $p){
						$total_price += $p['quantity'] * $p['unit_cost'];
					}

					$avg  = array();
					$ship = array();
					for($i = 0; $i < $c; $i++){
						$item_option = $_POST['product_option'][$i];
						$unitCost = $this->erp->formatPurDecimal($_POST['unit_cost'][$i]);
						$costunit = $this->site->calculateAVGCost2017($array_c[$i]['product_id'], $shipping, $array_c[$i]['quantity'], $array_c[$i]['price'], $total_price, $array_c[$i]['unit_cost'], $item_discount, $this->input->post('discount'), $array_c[$i]['option_id'], $array_c[$i]['subtotal'], $stotal);
						$avg[$array_c[$i]['product_id']]  = $costunit['avgcost'];
						$ship[$array_c[$i]['product_id']] = $costunit['shipping_cost'];
					}
					$i = 0;
					foreach($products as $p){
						$products[$i]['real_unit_cost'] = $avg[ $p['product_id'] ];
						$i++;
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
						$order_discount = (($total * (Float) ($ods[0])) / 100);
					} else {
						$order_discount = (($total*$this->erp->formatPurDecimal($order_discount_id))/100);
					}
				} else {
					$order_discount_id = null;
				}
				$total_discount = ($order_discount + $product_discount);

				if ($this->Settings->tax2 != 0) {
					$order_tax_id = $this->input->post('order_tax');
					if ($order_tax_details = $this->site->getTaxRateByID($order_tax_id)) {
						if ($order_tax_details->type == 2) {
							$order_tax = ($order_tax_details->rate);
						}
						if ($order_tax_details->type == 1) {
							$order_tax = ((($total + $shipping - $order_discount) * $order_tax_details->rate) / 100);
						}
					}
				} else {
					$order_tax_id = null;
				}

				$total_tax = ($product_tax + $order_tax);
				$grand_total = (($total - $order_discount) + $order_tax + $shipping );
				if($payment_status=="pending" || $payment_status=="due"){
					$paid_by = '';
				}
				$data = array(
					'biller_id' 		=> $biller_id,
					'reference_no' 		=> $reference,
					'request_ref' 		=> $order_referent->purchase_ref ? $order_referent->purchase_ref :'' ,
                    'payment_term'      => $payment_term,
					'due_date' 		    => $due_date,
					'date' 				=> $date,
					'supplier_id' 		=> $supplier_id,
					'supplier' 			=> $supplier,
					'customer_id' 		=> $this->input->post('customers'),
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
					'status' 			=> "received",
					'payment_status' 	=> "due",
					'created_by' 		=> $this->session->userdata('user_id'),
					'type_of_po' 		=> $type_exp,
					'order_ref'  		=>$order_referent->reference_no ? $order_referent->reference_no :'',
					'order_id' 			=> $this->input->post('order_id'),
					'account_code' 		=>$this->input->post('bank_account'),
					'pur_refer' 		=> $this->input->post('payment_reference_no'),
					'stotal' 			=> $stotal,
					'cogs' 				=> $cogs_inv,
					'quote_id'			=> $this->input->post('quote_id')
				);
			} else{

				$tran_no 		= $this->purchases_model->getTranNo();
				$ac_ap   		= $this->purchases_model->ACC_AP();
				$ac_tax	 		= $this->purchases_model->ACC_Pur_Tax();
				$account_code 	= $this->input->post('account_section');
				$debit 			= $this->input->post('debit');
				$amount_ap 		= $this->input->post('in_calDebit');
				$amount_tax 	= $this->input->post('in_calOrdTax');
				$description 	= $this->input->post('description');
				$i 				= 0;
				$data  			= array();
				$total 			= 0;


				$data[] =  array(
						'tran_type' 	=> 'PURCHASE EXPENSE',
						'tran_no' 		=> $tran_no,
						'account_code' 	=> $ac_ap,
						'tran_date' 	=> $date,
						'reference_no' 	=> $reference,
						'description' 	=> $description,
						'amount' 		=> (-($amount_ap + $amount_tax)),
						'biller_id' 	=> $biller_id,
						'sale_id' 		=> $this->input->post('customer_invoices'),
						'customer_id' 	=> $this->input->post('customers'),
                        'created_by' 		=> $this->session->userdata('user_id'),
						);
				if($amount_tax > 0) {
					$data[] =  array(
							'tran_type' 	=> 'PURCHASE EXPENSE',
							'tran_no' 		=> $tran_no,
							'account_code' 	=> $ac_tax,
							'tran_date' 	=> $date,
							'reference_no' 	=> $reference,
							'description' 	=> $description,
							'amount' 		=> $amount_tax,
							'biller_id' 	=> $biller_id,
							'sale_id' 		=> $this->input->post('customer_invoices'),
							'customer_id' 	=> $this->input->post('customers'),
                            'created_by' 		=> $this->session->userdata('user_id'),
							);
				}
				for($i=0;$i<count($account_code);$i++) {

					if($debit[$i]>0) {
						$amount  = $debit[$i];
						$total  += $debit[$i];
					}
					$data[] = array(
						'tran_type' 	=> 'PURCHASE EXPENSE',
						'tran_no' 		=> $tran_no,
						'account_code' 	=> $account_code[$i],
						'tran_date' 	=> $date,
						'reference_no' 	=> $reference,
						'description' 	=> $description,
						'amount' 		=> $amount,
						'biller_id' 	=> $biller_id,
						'sale_id' 		=> $this->input->post('customer_invoices'),
						'customer_id' 	=> $this->input->post('customers'),
                        'created_by' 		=> $this->session->userdata('user_id'),
						);
				}

				$this->purchases_model->addJournal($data);

				if ($this->Settings->tax2 != 0) {
					$order_tax_id = $this->input->post('order_tax');
					if ($order_tax_details = $this->site->getTaxRateByID($order_tax_id)) {
						if ($order_tax_details->type == 2) {
							$order_tax = $this->erp->formatPurDecimal($order_tax_details->rate);
						}
						if ($order_tax_details->type == 1) {
							$order_tax = $this->erp->formatPurDecimal((($total) * $order_tax_details->rate) / 100);
						}
					}
				} else {
					$order_tax_id = null;
				}

				$total_tax = $this->erp->formatPurDecimal($order_tax);
				$grand_total = $this->erp->formatPurDecimal(($total) + $total_tax);
				if($payment_status=="pending" || $payment_status=="due"){
					$paid_by = '';
				}
                $total              = 0;
                $product_tax        = 0;
                $order_tax          = 0;
                $product_discount   = 0;
                $order_discount     = 0;
                $percentage         = '%';
                if ($this->input->post('discount')) {
                    $order_discount_id = $this->input->post('discount');
                    $opos = strpos($order_discount_id, $percentage);
                    if ($opos !== false) {
                        $ods = explode("%", $order_discount_id);
                        $order_discount = ((($total + $product_tax) * (Float) ($ods[0])) / 100);
                    } else {
                        $order_discount = ($order_discount_id);
                    }
                } else {
                    $order_discount_id = null;
                }
                $total_discount = ($order_discount + $product_discount);
				$data = array(
					'biller_id' 		=> $biller_id,
					'reference_no' 		=> $reference,
					'order_ref'  		=>$order_referent->reference_no ? $order_referent->reference_no :'',
					'request_ref' 		=> $order_referent->purchase_ref ? $order_referent->purchase_ref :'' ,
					'payment_term' 		=> $payment_term,
					'date' 				=> $date,
					'supplier_id' 		=> $supplier_id,
					'supplier' 			=> $supplier,
					'warehouse_id' 		=> $warehouse_id,
					'note' 				=> $description,
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
					'status' 			=> "received",
					'payment_status' 	=> "due",//$payment_status,
					'created_by' 		=> $this->session->userdata('user_id'),
					'type_of_po' 		=> $type_exp,
					//'paid_by' 		=> $paid_by,
					'order_id' 			=> $this->input->post('order_id'),
					'account_code' 		=>$this->input->post('bank_accountbank_account'),
					'pur_refer' 		=> $this->input->post('payment_reference_no'),
					'sale_id'			=> $this->input->post('customer_invoices'),
					'customer_id'		=> $this->input->post('customers'),
					'quote_id'			=> $this->input->post('quote_id')
				);

			}

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

			if ($payment_status == 'partial' || $payment_status == 'paid') {
				if ($this->input->post('paid_by') == 'gift_card') {
					$gc = $this->site->getGiftCardByNO($this->input->post('gift_card_no'));
					$amount_paying = $grand_total >= $gc->balance ? $gc->balance : $grand_total;
					$gc_balance = $gc->balance - $amount_paying;

					$payment = array(
						'date' => $date,
						'reference_no' => $this->input->post('payment_reference_no'),
						'amount' => $this->erp->formatDecimal($amount_paying),
						'paid_by' => $this->input->post('paid_by'),
						'cheque_no' => $this->input->post('cheque_no'),
						'cc_no' => $this->input->post('gift_card_no'),
						'cc_holder' => $this->input->post('pcc_holder'),
						'cc_month' => $this->input->post('pcc_month'),
						'cc_year' => $this->input->post('pcc_year'),
						'cc_type' => $this->input->post('pcc_type'),
						'created_by' => $this->session->userdata('user_id'),
						'note' => $this->input->post('payment_note'),
						'type' => 'received',
						'gc_balance' => $gc_balance,
						'biller_id' => $biller_id,
						'bank_account'=>$this->input->post('bank_account')
					);
					$data['paid'] = $this->erp->formatDecimal($amount_paying);
				}else {
					$payment = array(
						'date' => $date,
						'reference_no' => $this->input->post('payment_reference_no'),
						'amount' => $this->erp->formatDecimal($this->input->post('amount-paid')),
						'paid_by' => $this->input->post('paid_by'),
						'cheque_no' => $this->input->post('cheque_no'),
						'cc_no' => $this->input->post('pcc_no'),
						'cc_holder' => $this->input->post('pcc_holder'),
						'cc_month' => $this->input->post('pcc_month'),
						'cc_year' => $this->input->post('pcc_year'),
						'cc_type' => $this->input->post('pcc_type'),
						'created_by' => $this->session->userdata('user_id'),
						'note' => $this->input->post('payment_note'),
						'type' => 'received',
						'biller_id' => $biller_id,
						'bank_account'=>$this->input->post('bank_account')
					);
					$data['paid'] = $this->erp->formatDecimal($this->input->post('amount-paid'));
				}
				if($_POST['paid_by'] == 'depreciation') {
					$no = sizeof($_POST['no']);
					$period = 1;
					for($m = 0; $m < $no; $m++){
						$dateline = date('Y-m-d', strtotime($_POST['dateline'][$m]));
						$loans[] = array(
							'period' => $period,
							'sale_id' => '',
							'interest' => $_POST['interest'][$m],
							'principle' => $_POST['principle'][$m],
							'payment' => $_POST['payment_amt'][$m],
							'balance' => $_POST['balance'][$m],
							'type' => $_POST['depreciation_type'],
							'rated' => $_POST['depreciation_rate1'],
							'note' => $_POST['note_1'][$m],
							'dateline' => $dateline,
							'biller_id' => $biller_id
						);
						$period++;
					}

				}else{
					$loans = array();
				}

			} else {
				$payment = array();
			}
			//$this->erp->print_arrays($data, $products);
		}

        if ($this->form_validation->run() == true && $this->purchases_model->addPurchase($data, $products, $payment, $purchase_order_id, $amount_o)) {
			if ($sale_order_id) {
                $this->db->update('sale_order', array('sale_status' => 'purchase'), array('id' => $sale_order_id));
            }
			if($this->Settings->purchase_serial){
				$this->purchases_model->addSerial($serial);
			}
            optimizePurchases(date('Y-m-d', strtotime($date)));

            $this->session->set_userdata('remove_puitem', 1);
            $this->session->set_flashdata('message', $this->lang->line("purchase_added"));
            redirect('purchases');
        } else {
            if ($purchase_order_id) {
				$this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
				$order_id 			 = $this->purchases_model->getPurchaseOrderByID($purchase_order_id);
				if($order_id->order_status == "completed"){
					$this->session->set_flashdata('error', "All purchase order is completed. Can not add more.");
                    redirect('purchases/purchase_order');
				}
				else if($order_id->status == "pending"){
					$this->session->set_flashdata('error', "Purchase order is pending. Can not add to purchase.");
                    redirect('purchases/purchase_order');
                } else {
					$this->data['inv'] = $order_id;
					$pref 		= $this->purchases_model->getPaymentByPurchaseID($purchase_order_id);
					$pur 		= $this->purchases_model->getPurchaseOrderByID($purchase_order_id);
					$inv_items 	= $this->purchases_model->getAllPurchaseOrderItems_order($purchase_order_id);
                    $pr = array();
					$c = rand(100000, 9999999);
					foreach ($inv_items as $item) {
						$row 				 = $this->site->getProductByID($item->product_id);
						$row->expiry 		 = (($item->expiry && $item->expiry != '0000-00-00') ? $this->erp->fsd($item->expiry) : '');
						$row->qty 			 = $item->quantity;
						$row->received 		 = ((($item->quantity - $item->quantity_received) > 0)? ($item->quantity - $item->quantity_received) : 0);
						$row->quantity_balance 	= $item->quantity_balance + ($item->quantity-$row->received);
						$row->discount 		 = $item->discount ? $item->discount : '0';
						$options 			 = $this->purchases_model->getProductOptions($row->id);
						$row->option 		 = $item->option_id;
						$row->real_unit_cost = $item->unit_cost;
						$row->cost 			 = $item->unit_cost;
						$row->tax_rate 		 = $item->tax_rate_id;
						$row->net_cost 		 = $item->unit_cost;
						$row->price 		 = $item->price;
						$row->tax_method 	 = $item->tax_method;
						$row->net_unit_cost  = $item->net_unit_cost;
						$row->pur_order_id	 = $item->id;
						$row->piece			 = $item->piece;
						$row->wpiece		 = $item->wpiece;
                        $test                = $this->sales_model->getWP2($row->id, $pur->warehouse_id);
                        if($test->quantity) {
                            $row->quantity = $test->quantity;
                        }else {
                            $row->quantity = 0;
                        }
						$pii 				 = $this->purchases_model->getPurcahseItemByPurchaseID($purchase_order_id);

						unset($row->details, $row->product_details, $row->file, $row->product_group_id);
                        //$ri = $this->Settings->item_addition ? $row->id : $c;
                        $ri = $this->Settings->item_addition ? $c : $c;

                        if($row->received){
							if ($row->tax_rate) {
								$tax_rate = $this->site->getTaxRateByID($row->tax_rate);
								$pr[$ri] = array('id' => $c, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'tax_rate' => $tax_rate, 'options' => $options, 'suppliers' => '' , 'supplier_id' => $item->supplier_id, 'create_id'=>$item->id);
							} else {
								$pr[$ri] = array('id' => $c, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'tax_rate' => false, 'options' => $options, 'suppliers' => '' , 'supplier_id' => $item->supplier_id, 'create_id'=>$item->id);
							}
						}

						$c++;
					}
					//$this->erp->print_arrays($pr);
					$this->data['inv_items'] 		 = json_encode($pr);
					$this->data['id'] 				 = $purchase_order_id;
					$this->data['purchase_order_id'] = $purchase_order_id;
					$this->data['suppliers'] 		 = $this->site->getAllCompanies('supplier');
					$this->data['purchase'] 		 = $order_id;
				}
				//$this->erp->print_arrays($order_id);

            }

			if ($quote_id) {

				$this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
				$order_id = $this->purchases_model->getQuoteByID($quote_id);
				if($order_id->status == "pending"){
					$this->session->set_flashdata('error', "Quote order is pending. Can not add to purchase.");
                    redirect('quotes');
				}
				else{
					$this->data['inv'] = $order_id;
					$pref = $this->purchases_model->getPaymentByPurchaseID($quote_id);

					$inv_items = $this->purchases_model->getAllQuoteItems($quote_id);

					$c = rand(100000, 9999999);
					foreach ($inv_items as $item) {
						$row = $this->site->getProductByID($item->product_id);
						$row->expiry = ((isset($item->expiry) && isset($item->expiry) != '0000-00-00') ? $this->erp->fsd(isset($item->expiry)) : '');
						$row->qty = $item->quantity;

						$row->received = ((($item->quantity - $item->quantity_received) > 0)? ($item->quantity - $item->quantity_received) : 0);
						$row->quantity_balance = isset($item->quantity_balance) + ($item->quantity-$row->received);
						$row->discount = $item->discount ? $item->discount : '0';
						$options = $this->purchases_model->getProductOptions($row->id);
						$row->option = $item->option_id;
						$row->real_unit_cost = isset($item->unit_cost);
						$row->cost = isset($item->unit_cost);
						$row->tax_rate = $item->tax_rate_id;
						$row->net_cost = isset($item->unit_cost);
						$row->price = $item->net_unit_price;
						$row->net_unit_cost = isset($item->net_unit_cost);
						$row->piece = $item->piece;
						$row->wpiece = $item->wpiece;
						$pii = $this->purchases_model->getPurcahseItemByPurchaseID($quote_id);

						unset($row->details, $row->product_details, $row->file, $row->product_group_id);
						$ri = $this->Settings->item_addition ? $row->id : $c;
						if ($row->tax_rate) {
							$tax_rate = $this->site->getTaxRateByID($row->tax_rate);
							$pr[$ri] = array('id' => $c, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'tax_rate' => $tax_rate, 'options' => $options, 'suppliers' => '' ,'supplier_id' => isset($item->supplier_id),'create_id'=>$item->id);
						} else {
							$pr[$ri] = array('id' => $c, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'tax_rate' => false, 'options' => $options, 'suppliers' => '' ,'supplier_id' => $item->supplier_id,'create_id'=>$item->id);
						}
						$c++;

					}
					//$this->erp->print_arrays($pr);

					$this->data['inv_items'] = json_encode($pr);
					$this->data['qid'] = $quote_id;

					//$this->data['suppliers'] = $this->site->getAllCompanies('supplier');

					$this->data['purchase'] = $order_id;
				}
			}

			if ($sale_order_id){

                $sale_order 				 = $this->sales_model->getSaleOrder($sale_order_id);
				$this->data['sale_order'] 	 = $sale_order;
				$items 						 = $this->sales_model->getSaleOrdItems($sale_order_id);
				$this->data['sale_order_id'] = $sale_order_id;
				$this->data['type'] 		 = "sale_order";
				$this->data['type_id'] 		 = $sale_order_id;

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

                    $row->id = $item->product_id;
                    $row->code = $item->product_code;
					$row->net_cost = $row->cost;
                    $row->type = $item->product_type;
                    $row->qty = $item->quantity;
                    $row->discount = $item->discount ? $item->discount : '0';
                    $row->price = $this->erp->formatDecimal($item->net_unit_price+$this->erp->formatDecimal($item->item_discount/$item->quantity));
                    $row->unit_price = $row->tax_method ? $item->unit_price+$this->erp->formatDecimal($item->item_discount/$item->quantity)+$this->erp->formatDecimal($item->item_tax/$item->quantity) : $item->unit_price+($item->item_discount/$item->quantity);
                    $row->real_unit_price = $item->real_unit_price;
                    $row->tax_rate = $item->tax_rate_id;
                    $row->serial = '';
                    $row->option = $item->option_id;
					$row->piece	= $item->piece;
					$row->wpiece = $item->wpiece;

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
                        $tax_rate = $this->site->getTaxRateByID($row->tax_rate);
                        $pr[$ri] = array('id' => $c, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'combo_items' => $combo_items, 'tax_rate' => $tax_rate, 'options' => $options, 'makeup_cost' => 0,'group_prices'=>$group_prices, 'all_group_price' => $all_group_prices);
                    } else {
                        $pr[$ri] = array('id' => $c, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'combo_items' => $combo_items, 'tax_rate' => false, 'options' => $options, 'makeup_cost' => 0,'group_prices'=>$group_prices, 'all_group_price' => $all_group_prices);
                    }
                    $c++;
                }

				//$this->erp->print_arrays($pr);
                $payment_deposit=null;
				$this->data['sale_order_id'] = $sale_order_id;
                $this->data['sale_order_items'] = json_encode($pr);
				$this->data['payment_deposit'] = $payment_deposit;
            }

			$this->data['error'] 				= (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['quote_id'] 			= $quote_id;
			$this->data['edit_status'] 			= $quote_id;
			$this->data['categories'] 			= $this->site->getAllCategories();
            $this->data['tax_rates'] 			= $this->site->getAllTaxRates();
            $this->data['warehouses'] 			= $this->site->getAllWarehouses();
            $this->data['products'] 			= $this->site->getAllProducts();
            $this->data['unit'] 				= $this->purchases_model->getUnits();
			$this->data['customers'] 			= $this->site->getCustomers();
			$this->data['invoices'] 			= $this->site->getCustomerInvoices();
            $this->data['customer_invoices'] 	= $this->site->getCustomerInvoices();
			$this->data['type'] 				= $this->purchases_model->getAlltypes();
            $this->data['sectionacc'] 			= $this->purchases_model->getAccountSections();
            $this->data['sectionacc'] 			= $this->purchases_model->getAllChartAccount();
			$this->data['currency'] 			= $this->site->getCurrency();
            $this->data['rate'] 				= $this->purchases_model->getKHM();
            $this->data['payment_term'] 		= $this->site->getAllPaymentTerm();
            $this->data['billers'] 				= $this->site->getAllCompanies('biller');
            $this->data['suppliers'] 			= $this->site->getAllSuppliers('supplier');
			$this->data['customers'] 			= $this->site->getCustomers();
			$this->data['invoices'] 			= $this->site->getCustomerInvoices();
			$this->data['setting'] 				= $this->site->get_setting();

			if ($this->Owner || $this->Admin || !$this->session->userdata('biller_id')){
				$biller_id 						= $this->site->get_setting()->default_biller;
				 $this->data['ponumber'] 		= $this->site->getReference('po',$biller_id);
				 $this->data['payment_ref'] 	= $this->site->getReference('pp',$biller_id);
			}else{
				$biller_id 						= $this->session->userdata('biller_id');
				$this->data['ponumber'] 		= $this->site->getReference('po',$biller_id);
				$this->data['payment_ref'] 		= $this->site->getReference('pp',$biller_id);
			}

			$this->load->helper('string');
            $value 								= random_string('alnum', 20);

			$this->data['bankAccounts_1'] 		=  $this->site->getAllBankAccounts();
            $this->session->set_userdata('user_csrf', $value);
            $this->data['csrf'] 				= $this->session->userdata('user_csrf');
            //$this->erp->print_arrays($this->data);
            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('purchases'), 'page' => lang('purchases')), array('link' => '#', 'page' => lang('add_purchase')));
            $meta = array('page_title' => lang('add_purchase'), 'bc' => $bc);
            $this->page_construct('purchases/add', $meta, $this->data);
        }
    }

    /* --------------------------------------------------------------------------------- */

	public function getSearchSupplier(){
        //$code = $this->input->get('code', TRUE);
		$code = $this->input->get('limit', TRUE);
		$supplier = $this->site->getProductSupplier($code);
		$suppliers = array($supplier->supplier1, $supplier->supplier2, $supplier->supplier3,$supplier->supplier4,$supplier->supplier4);
		$rows['results'] = $this->site->getSupplierByArray($suppliers);
		//$rows['results'] = $this->site->getAllCompanies('supplier');
		//$test['results'] = 'fuck';
        //$limit['results'] = $this->input->get('limit', TRUE);
        //$rows['results'] = $this->purchases_model->getSupplierSuggestions($term, $limit);
		echo json_encode($rows);
	}

    public function getSupplierProduct(){
		$code = $this->input->get('code');
		$supplier = $this->site->getProductSupplier($code);
		$suppliers = array($supplier->supplier1, $supplier->supplier2, $supplier->supplier3,$supplier->supplier4,$supplier->supplier4);
		$rows['results'] = $this->site->getSupplierByArray($suppliers);
		echo json_encode($rows);
	}

	function supplier_balance()
    {
        $this->erp->checkPermissions('supplier_balance',NULL,'purchases');
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
		$this->data['suppliers'] = $this->site->getAllCompanies('supplier');
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('purchases'), 'page' => lang('purchases')), array('link' => '#', 'page' => lang('supplier_balance_list')));
        $meta = array('page_title' => lang('supplier_balance'), 'bc' => $bc);
        $this->page_construct('purchases/supplier_balance', $meta, $this->data);
    }

	function getSupplierBalance($pdf = NULL, $xls = NULL)
    {
		if ($this->input->get('supplier')) {
            $supplier = $this->input->get('supplier');
        } else {
            $supplier = NULL;
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
		if ($pdf || $xls) {

            $this->db
                ->select($this->db->dbprefix('companies') . ".id as id, company, name, phone, email, count(erp_purchases.id) as total, COALESCE(sum(grand_total), 0) as total_amount, COALESCE(sum(paid), 0) as paid, ( COALESCE(sum(grand_total), 0) - COALESCE(sum(paid), 0)) as balance", FALSE)
                ->from("companies")
                ->join('purchases', 'purchases.supplier_id=companies.id')
                ->where('companies.group_name', 'supplier')
                ->order_by('companies.company asc')
                ->group_by('companies.id');

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
                $this->excel->getActiveSheet()->setTitle(lang('suppliers_report'));
                $this->excel->getActiveSheet()->SetCellValue('A1', lang('company'));
                $this->excel->getActiveSheet()->SetCellValue('B1', lang('name'));
                $this->excel->getActiveSheet()->SetCellValue('C1', lang('phone'));
                $this->excel->getActiveSheet()->SetCellValue('D1', lang('email'));
                $this->excel->getActiveSheet()->SetCellValue('E1', lang('total_purchases'));
                $this->excel->getActiveSheet()->SetCellValue('F1', lang('total_amount'));
                $this->excel->getActiveSheet()->SetCellValue('G1', lang('paid'));
                $this->excel->getActiveSheet()->SetCellValue('H1', lang('balance'));

                $row = 2;
                foreach ($data as $data_row) {
                    $this->excel->getActiveSheet()->SetCellValue('A' . $row, $data_row->company);
                    $this->excel->getActiveSheet()->SetCellValue('B' . $row, $data_row->name);
                    $this->excel->getActiveSheet()->SetCellValue('C' . $row, $data_row->phone);
                    $this->excel->getActiveSheet()->SetCellValue('D' . $row, $data_row->email);
                    $this->excel->getActiveSheet()->SetCellValue('E' . $row, $this->erp->formatNumber($data_row->total));
                    $this->excel->getActiveSheet()->SetCellValue('F' . $row, $this->erp->formatMoney($data_row->total_amount));
                    $this->excel->getActiveSheet()->SetCellValue('G' . $row, $this->erp->formatMoney($data_row->paid));
                    $this->excel->getActiveSheet()->SetCellValue('H' . $row, $this->erp->formatMoney($data_row->balance));
                    $row++;
                }

                $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
                $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
                $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
                $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
                $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
                $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
                $filename = 'suppliers_report';
                $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                if ($pdf) {
                    $styleArray = array(
                        'borders' => array(
                            'allborders' => array(
                                'style' => PHPExcel_Style_Border::BORDER_THIN
                            )
                        )
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
                if ($xls) {
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
        } else {

            $sp = "(
				SELECT
					erp_purchases.id,
					erp_purchases.supplier_id,
					SUM(
						COALESCE (erp_payments.discount, 0)
					) AS discount,
					SUM(

						IF (
							erp_payments.paid_by = 'deposit',
							COALESCE (erp_payments.amount, 0),
							0
						)
					) AS deposit,
					SUM(

						IF (
							(
								erp_payments.paid_by != 'deposit'
								AND ISNULL(erp_payments.return_id)
							),
							erp_payments.amount,

						IF (
							NOT ISNULL(erp_payments.return_id),
							((- 1) * erp_payments.amount),
							0
						)
						)
					) AS payment
				FROM
					erp_payments
				LEFT JOIN erp_purchases ON erp_purchases.id = erp_payments.purchase_id
				WHERE
					erp_purchases.payment_status <> 'paid'
				AND erp_purchases.status <> 'ordered'
				GROUP BY erp_purchases.supplier_id
				) AS erp_pmt";

            $return = "(
				SELECT
					erp_purchases.id,
					erp_purchases.supplier_id,
					SUM(
						erp_return_purchases.grand_total
					) AS return_amount
				FROM
					erp_return_purchases
				LEFT JOIN erp_purchases ON erp_purchases.id = erp_return_purchases.purchase_id
				WHERE
					erp_purchases.payment_status <> 'paid'
				AND (
						erp_purchases.return_id IS NULL
						OR erp_purchases.grand_total <> erp_return_purchases.grand_total
					)
				GROUP BY
					erp_return_purchases.supplier_id
				) AS erp_total_return_purchase";

            $this->load->library('datatables');
            $this->datatables
                ->select($this->db->dbprefix('companies') . ".id as idd,
                           company,
                           name,
                           phone,
                           email,
                           count(" . $this->db->dbprefix('purchases') . ".id) as total,
                           COALESCE(sum(grand_total), 0) as total_amount,
                           total_return_purchase.return_amount as return_sale,
                           COALESCE(sum(paid), 0) as paid,
                           COALESCE(erp_pmt.deposit, 0) AS total_deposit,
					       COALESCE(erp_pmt.discount, 0) AS total_discount,
                           ( COALESCE(sum(grand_total), 0) - COALESCE(sum(paid), 0)) as balance", FALSE)
                 ->from("companies")
                 ->join('purchases', 'purchases.supplier_id=companies.id')
                ->join($sp, 'pmt.supplier_id = purchases.supplier_id', 'left')
                ->join($return, 'total_return_purchase.supplier_id = purchases.supplier_id', 'left')
                 ->where('companies.group_name', 'supplier')
                 ->where(array('purchases.status' => 'received', 'purchases.payment_status <>' => 'paid'))
                 ->group_by('companies.id')
                 ->add_column("Actions", "<div class='text-center'><a class=\"tip\" title='" . lang("view_balance") . "' href='" . site_url('purchases/view_supplier_balance/$1') . "'><span class='label label-primary'>" . lang("view_balance") . "</span></a></div>", "idd")
                ->unset_column('id');
			if($supplier){
				$this->datatables->where('purchases.supplier_id', $supplier);
			}
            if (!$this->Owner && !$this->Admin && !$this->session->userdata('view_right')) {
                $user = $this->session->userdata('user_id');
                $this->datatables->where('purchases.created_by', $user);
            }
			if($this->session->userdata('biller_id') ) {
			    $user_biller = json_decode($this->session->userdata('biller_id'));
				$this->datatables->where_in('purchases.biller_id', $user_biller );
			}
			if ($start_date) {
                $this->datatables->where($this->db->dbprefix('purchases').'.date BETWEEN "' . $start_date . '" and "' . $end_date . '"');
            }
            echo $this->datatables->generate();

        }

    }

	function view_supplier_balance($user_id = NULL, $biller_id = NULL)
    {

		 $this->erp->checkPermissions('supplier_balance',NULL,'purchases');
        if (!$user_id && $_GET['d'] == null) {
            $this->session->set_flashdata('error', lang("no_supplier_selected"));
            redirect('reports/suppliers');
        }

		if($biller_id != NULL){
			$this->data['biller_id'] = $biller_id;
		}else{
			$this->data['biller_id'] = "";
		}
        if ($this->input->get('user')) {
            $user = $this->input->get('user');
        } else {
            $user = NULL;
        }
		if(!$this->Owner && !$this->Admin) {
			if($user->biller_id){
				$this->data['billers'] = $this->site->getCompanyByArray($user->biller_id);
			}else{
				$this->data['billers'] = $this->site->getAllCompanies('biller');
			}
		}else{
			$this->data['billers'] = $this->site->getAllCompanies('biller');
		}
		$this->data['categories'] = $this->site->getAllCategories();
        $this->data['purchases'] = $this->purchases_model->getPurchasesTotals($user_id);
        $this->data['total_purchases'] = $this->purchases_model->getSupplierPurchases($user_id);
        $this->data['users'] = $this->purchases_model->getStaff();
        $this->data['warehouses'] = $this->site->getAllWarehouses();

        $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');

        $this->data['user_id'] = $user_id;
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('purchases'), 'page' => lang('purchases')), array('link' => '#', 'page' => lang('view_supplier_balance')));
        $meta = array('page_title' => lang('view_supplier_balance'), 'bc' => $bc);
        $this->page_construct('purchases/view_supplier_balance', $meta, $this->data);
    }

	function getSupplierBalance_action($user_id){

        if ($this->input->post('form_action') == 'export_excel' || $this->input->post('form_action') == 'export_pdf') {
            if ($_POST['val']) {
                $this->load->library('excel');
                $this->excel->setActiveSheetIndex(0);
				$supplier = $this->site->getCompanyNameByCustomerID($user_id);
                $this->excel->getActiveSheet()->setTitle(lang('supplier'));
				$this->excel->getActiveSheet()->SetCellValue('D1', lang('supplier_balance'));
				$this->excel->getActiveSheet()->setCellValue('A2','Supplier Name : ');
                $this->excel->getActiveSheet()->setCellValue('B2', $supplier->company);

                $this->excel->getActiveSheet()->SetCellValue('A3', lang('date'));
                $this->excel->getActiveSheet()->SetCellValue('B3', lang('due_date'));
                $this->excel->getActiveSheet()->SetCellValue('C3', lang('reference_no'));
                $this->excel->getActiveSheet()->SetCellValue('D3', lang('warehouse'));
                $this->excel->getActiveSheet()->SetCellValue('E3', lang('supplier'));
                $this->excel->getActiveSheet()->SetCellValue('F3', lang('grand_total'));
                $this->excel->getActiveSheet()->SetCellValue('G3', lang('paid'));
                $this->excel->getActiveSheet()->SetCellValue('H3', lang('balance'));
				$this->excel->getActiveSheet()->SetCellValue('I3', lang('payment_status'));
                $this->excel->getActiveSheet()->getStyle('A1:I1')->getFont()->setBold(true);
                $this->excel->getActiveSheet()->getStyle('A2:I2')->getFont()->setBold(true);
                $this->excel->getActiveSheet()->getStyle('A3:I3')->getFont()->setBold(true);
                $this->excel->getActiveSheet()->getStyle('A1:I1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $this->excel->getActiveSheet()->getStyle('A2:I2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $this->excel->getActiveSheet()->getStyle('A3:I3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $row = 4;
				$sum_grandtotal = 0;
				$sum_paid = 0;
				$sum_balance = 0;
                foreach ($_POST['val'] as $id) {
                        $supplier = $this->db
						->select($this->db->dbprefix('purchases') . ".id, ".$this->db->dbprefix('purchases') . ".date, reference_no, due_date, " .
									 $this->db->dbprefix('warehouses') . ".name as wname, supplier ,
									 grand_total, paid, (grand_total-paid) as balance, " . $this->db->dbprefix('purchases') . ".payment_status", FALSE)
						->join('purchase_items', 'purchase_items.purchase_id=purchases.id', 'left')
						->join('warehouses', 'warehouses.id=purchases.warehouse_id', 'left')
						->join('companies', 'companies.id = purchase_items.supplier_id', 'left')
						->where(array('purchases.status' => 'received', 'purchases.payment_status <>' => 'paid','purchases.id' => $id))
						->group_by('purchases.id')
						->get("purchases")->result();
                        foreach($supplier as $sup){
							$sum_grandtotal += $sup->grand_total;
							$sum_paid += $sup->paid;
							$sum_balance += $sup->balance;
                            $this->excel->getActiveSheet()->SetCellValue('A' . $row,$sup->date);
                            $this->excel->getActiveSheet()->SetCellValue('B' . $row, $sup->due_date);
                            $this->excel->getActiveSheet()->SetCellValue('C' . $row, $sup->reference_no);
                            $this->excel->getActiveSheet()->SetCellValue('D' . $row, $sup->wname);
                            $this->excel->getActiveSheet()->SetCellValue('E' . $row, $sup->supplier);
                            $this->excel->getActiveSheet()->SetCellValue('F' . $row, $this->erp->formatMoney($sup->grand_total));
                            $this->excel->getActiveSheet()->SetCellValue('G' . $row, $this->erp->formatMoney($sup->paid));
                            $this->excel->getActiveSheet()->SetCellValue('H' . $row, $this->erp->formatMoney($sup->balance));
							$this->excel->getActiveSheet()->SetCellValue('I' . $row, $sup->payment_status);

							$this->excel->getActiveSheet()->getStyle('F'. $row.':H'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
							$i = $row+1;
							$this->excel->getActiveSheet()->SetCellValue('F' . $i, $this->erp->formatMoney($sum_grandtotal));
							$this->excel->getActiveSheet()->SetCellValue('G' . $i, $this->erp->formatMoney($sum_paid));
							$this->excel->getActiveSheet()->SetCellValue('H' . $i, $this->erp->formatMoney($sum_balance));
							$row++;
                        }
                }


                $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
                $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
                $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
                $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
                $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
                $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
                $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
				$this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
				$this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(20);

                $filename = lang('supplier_banlance'). date('Y_m_d_H_i_s');
                $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                if ($this->input->post('form_action') == 'export_pdf') {
                    $styleArray = array(
                        'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN))
                    );
					$this->excel->getActiveSheet()->getStyle('A3:I3')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$rw = 4;
					foreach ($_POST['val'] as $id) {
						$this->excel->getActiveSheet()->getStyle('F' . $rw . ':H' . $rw)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$rw++;
					}

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
                if ($this->input->post('form_action') == 'export_excel') {
                    ob_clean();
					$this->excel->getActiveSheet()->getStyle('F'.$i. ':H'.$i)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$this->excel->getActiveSheet()->getStyle('F'. $i.':H'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
                    header('Cache-Control: max-age=0');

                    ob_clean();
                    $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
                    $objWriter->save('php://output');
                    exit();
                }
             }else {
                $this->session->set_flashdata('error', $this->lang->line("no_supplier_selected. Please select at least one."));
                redirect($_SERVER["HTTP_REFERER"]);
            }
        }
    }

	function supplier_balance_actions()
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
                    $this->excel->getActiveSheet()->setTitle(lang('suppliers_report'));
                    $this->excel->getActiveSheet()->mergeCells('A1:K1');
                    $this->excel->getActiveSheet()->SetCellValue('A1', lang('Suppliers_Balance'));
                    $this->excel->getActiveSheet()->SetCellValue('A2', lang('company'));
                    $this->excel->getActiveSheet()->SetCellValue('B2', lang('name'));
                    $this->excel->getActiveSheet()->SetCellValue('C2', lang('phone'));
					$this->excel->getActiveSheet()->SetCellValue('D2', lang('email_address'));
					$this->excel->getActiveSheet()->SetCellValue('E2', lang('total_purchases'));
					$this->excel->getActiveSheet()->SetCellValue('F2', lang('total_amount'));
                    $this->excel->getActiveSheet()->SetCellValue('G2', lang('total_return'));
                    $this->excel->getActiveSheet()->SetCellValue('H2', lang('total_paid'));
                    $this->excel->getActiveSheet()->SetCellValue('I2', lang('total_deposit'));
                    $this->excel->getActiveSheet()->SetCellValue('J2', lang('total_discount'));
                    $this->excel->getActiveSheet()->SetCellValue('K2', lang('total_balance'));


					 $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $this->excel->getActiveSheet()->getStyle('A1:K1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $this->excel->getActiveSheet()->getStyle('A1:K1')->getFont()
                                              ->setName('Times New Roman')
                                              ->setSize(20);
					$this->excel->getActiveSheet()->getRowDimension(1)->setRowHeight(30);
                    $this->excel->getActiveSheet()->getStyle('A2:K2')->getFont()
                                              ->setName('Times New Roman')
                                              ->setSize(13);
					$this->excel->getActiveSheet()->getRowDimension(2)->setRowHeight(20);
                    $row = 3;
                    $sum_purcese = $sum_amount = $sum_payment = $sum_return = $sum_deposit = $sum_discount = $sum_balance = 0;
                    foreach ($_POST['val'] as $id) {

                        $sc = $this->reports_model->getSupplierByID($id);
                        $sum_purcese += $sc->total;
                        $sum_amount += $sc->total_amount;
                        $sum_return += $sc->return_sale;
                        $sum_payment += $sc->paid;
                        $sum_deposit += $sc->total_deposit;
                        $sum_discount += $sc->total_discount;
                        $sum_balance += $sc->balance;

                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $sc->company);
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $sc->name);
                        $this->excel->getActiveSheet()->SetCellValue('C' . $row, $sc->phone." ");
						$this->excel->getActiveSheet()->SetCellValue('D' . $row, $sc->email);
                        $this->excel->getActiveSheet()->SetCellValue('E' . $row, $this->erp->formatMoney($sc->total));
						$this->excel->getActiveSheet()->SetCellValue('F' . $row, $this->erp->formatMoney($sc->total_amount));
                        $this->excel->getActiveSheet()->SetCellValue('G' . $row, $this->erp->formatMoney($sc->return_sale));
                        $this->excel->getActiveSheet()->SetCellValue('H' . $row, $this->erp->formatMoney($sc->paid));
                        $this->excel->getActiveSheet()->SetCellValue('I' . $row, $this->erp->formatMoney($sc->total_deposit));
                        $this->excel->getActiveSheet()->SetCellValue('J' . $row, $this->erp->formatMoney($sc->total_discount));
                        $this->excel->getActiveSheet()->SetCellValue('K' . $row, $this->erp->formatMoney($sc->balance));


						$new_row = $row+1;
                        $this->excel->getActiveSheet()->SetCellValue('E' . $new_row, $this->erp->formatDecimal($sum_purcese));
                        $this->excel->getActiveSheet()->SetCellValue('F' . $new_row, $this->erp->formatDecimal($sum_amount));
                        $this->excel->getActiveSheet()->SetCellValue('G' . $new_row, $this->erp->formatDecimal($sum_return));
                        $this->excel->getActiveSheet()->SetCellValue('H' . $new_row, $this->erp->formatDecimal($sum_payment));
                        $this->excel->getActiveSheet()->SetCellValue('I' . $new_row, $this->erp->formatDecimal($sum_deposit));
                        $this->excel->getActiveSheet()->SetCellValue('J' . $new_row, $this->erp->formatDecimal($sum_discount));
                        $this->excel->getActiveSheet()->SetCellValue('K' . $new_row, $this->erp->formatDecimal($sum_balance));
                        $row++;
                    }
					//$this->erp->print_arrays($_POST['val']);

                    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
					$this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
					$this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(20);

                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $filename = 'suppliers_balance_' . date('Y_m_d_H_i_s');
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

						$styleArray = array(
                        'font'  => array(
                            'bold'  => true
                        )
						);

                        $this->excel->getActiveSheet()->getStyle('A2:K2')->applyFromArray($styleArray);
                        $this->excel->getActiveSheet()->getStyle('A2:K2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
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
                        $this->excel->getActiveSheet()->getStyle('E' . $new_row . ':K' . $new_row)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border:: BORDER_THIN);
                        $this->excel->getActiveSheet()->getStyle('E' . $new_row . ':K' . $new_row)->getFont()->setBold(true);

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

	function getViewSupplierBalance()
    {

        if ($this->input->get('product')) {
            $product = $this->input->get('product');
        } else {
            $product = NULL;
        }

		if ($this->input->get('biller_id')) {
            $biller_id = $this->input->get('biller_id');
        } else {
            $biller_id = NULL;
        }

        if ($this->input->get('user')) {
            $user = $this->input->get('user');
        } else {
            $user = NULL;
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
        if (!$this->Owner && !$this->Admin && !$this->session->userdata('view_right')) {
            $user = $this->session->userdata('user_id');
        }
		$detail_link = anchor('purchases/view/$1', '<i class="fa fa-file-text-o"></i> ' . lang('purchase_details'));
        $payments_link = anchor('purchases/payments/$1', '<i class="fa fa-money"></i> ' . lang('view_payments'), 'data-toggle="modal" data-target="#myModal"');
        $add_payment_link = anchor('purchases/add_payment/$1', '<i class="fa fa-money"></i> ' . lang('add_payment'), 'data-toggle="modal" data-target="#myModal"');
        $add_delivery_link = anchor('sales/add_delivery/$1', '<i class="fa fa-truck"></i> ' . lang('add_delivery'), 'data-toggle="modal" data-target="#myModal"');
        $email_link = anchor('purchases/email/$1', '<i class="fa fa-envelope"></i> ' . lang('email_sale'), 'data-toggle="modal" data-target="#myModal"');
        $edit_link = anchor('sales/edit/$1', '<i class="fa fa-edit"></i> ' . lang('edit_sale'), 'class="sledit"');
        $pdf_link = anchor('purchases/pdf/$1', '<i class="fa fa-file-pdf-o"></i> ' . lang('download_pdf'));
        $delete_link = "<a href='#' class='po' title='<b>" . lang("delete_sale") . "</b>' data-content=\"<p>"
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
            <li>' . $pdf_link . '</li>
            <li>' . $email_link . '</li>            
        </ul></div></div>';
		$this->load->library('datatables');
		$this->datatables
		->select($this->db->dbprefix('purchases') . ".id, ".$this->db->dbprefix('purchases') . ".date, due_date, reference_no, " .
					 $this->db->dbprefix('warehouses') . ".name as wname, supplier ,
					 grand_total,
					 COALESCE (
						(
							SELECT
								SUM(
									erp_return_purchases.grand_total
								)
							FROM
								erp_return_purchases
							WHERE
								erp_return_purchases.purchase_id = erp_purchases.id
						),
						0
					) AS return_purchases,
					 paid,
					 (
						SELECT
							SUM(

								IF (
									erp_payments.paid_by = 'deposit',
									erp_payments.amount,
									0
								)
							)
						FROM
							erp_payments
						WHERE
							erp_payments.purchase_id = erp_purchases.id
					) AS deposit,
					COALESCE (
						(
							SELECT
								SUM(erp_payments.discount)
							FROM
								erp_payments
							WHERE
								erp_payments.purchase_id = erp_purchases.id
						),
						0
					) AS discount,
					 
					 (grand_total-paid) as balance, " . $this->db->dbprefix('purchases') . ".payment_status", FALSE)
			->from('purchases')
			->join('purchase_items', 'purchase_items.purchase_id=purchases.id', 'left')
			->join('warehouses', 'warehouses.id=purchases.warehouse_id', 'left')
			->join('companies', 'companies.id = purchase_items.supplier_id', 'left')
			->where(array('purchases.status' => 'received', 'purchases.payment_status <>' => 'paid'))
			->group_by('purchases.id');

		if ($supplier) {
			$this->datatables->where('purchases.supplier_id', $supplier);
		}
		if ($user) {
			$this->datatables->where('purchases.created_by', $user);
		}
		if(!$this->Owner && !$this->Admin && $this->session->userdata('biller_id') ) {
		    $user_biller = json_decode($this->session->userdata('biller_id'));
			$this->datatables->where_in('purchases.biller_id', $user_biller);
		}
		if ($biller_id) {
			$this->datatables->where('purchases.biller_id', $biller_id);
		}
		if ($product) {
			$this->datatables->like('purchase_items.product_id', $product);
		}
		if ($warehouse) {
			$this->datatables->where('purchases.warehouse_id', $warehouse);
		}
		if ($reference_no) {
			$this->datatables->where('purchases.reference_no', $reference_no);
		}
		if ($start_date) {
			$this->datatables->where($this->db->dbprefix('purchases').'.date BETWEEN "' . $start_date . '00:00" and "' . $end_date . '23:59"');
		}
		$this->datatables->add_column("Actions", $action, "erp_purchases.id");
		echo $this->datatables->generate();
    }

	public function combine_payment_supplier($id = null)
    {
        $this->erp->checkPermissions('payments', true, 'purchases');
        $this->load->helper('security');
        $arr = array();
        if ($this->input->get('data'))
        {
            $arr = explode(',', $this->input->get('data'));
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
			$photo = 0;
			if ($_FILES['userfile']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->upload_path;
                $config['allowed_types'] = $this->digital_file_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = false;
                $config['encrypt_name'] = true;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                //$payment['attachment'] = $photo;
            }
			$biller_id = $this->input->post('biller');
			$purchase_id_arr = $this->input->post('purchase_id');
			$amount_paid_arr = $this->input->post('amount_paid_line');
			$i = 0;
			$reference_no = $this->input->post('reference_no') ? $this->input->post('reference_no') : $this->site->getReference('pp');
			foreach($purchase_id_arr as $purchase_id){
				$payment = array(
					'date' => $date,
					'purchase_id' => $purchase_id,
					'reference_no' => $reference_no,
					'biller_id' => $biller_id,
					'amount' => $amount_paid_arr[$i],
					'paid_by' => $this->input->post('paid_by'),
					'cheque_no' => $this->input->post('cheque_no'),
					'cc_no' => $this->input->post('pcc_no'),
					'cc_holder' => $this->input->post('pcc_holder'),
					'cc_month' => $this->input->post('pcc_month'),
					'cc_year' => $this->input->post('pcc_year'),
					'cc_type' => $this->input->post('pcc_type'),
					'note' => $this->erp->clear_tags($this->input->post('note')),
					'created_by' => $this->session->userdata('user_id'),
					'type' => 'sent',
					'attachment' => $photo,
					'bank_account' => $this->input->post('bank_account')
				);
				if($payment['amount'] > 0 ){
					$this->sales_model->addPurchasePaymentMulti($payment);
				}
				$i++;
			}

            $this->session->set_flashdata('message', lang("payment_added"));
            redirect($_SERVER["HTTP_REFERER"]);

        }else {

			$setting = $this->site->get_setting();
            $purchase_id = $this->input->post('purchase_id');
            $purchase = $this->purchases_model->getPurchaseByID($purchase_id);
			if($this->Settings->system_management == 'biller') {
				if ($this->Owner || $this->Admin || !$this->session->userdata('biller_id')) {
					$biller_id = $this->site->get_setting()->default_biller;
					$this->data['biller_id'] = $biller_id;
					$this->data['payment_ref'] = $this->site->getReference('pp',$biller_id);
				} else {
					$biller_id = $this->session->userdata('biller_id');
					$this->data['biller_id'] = $biller_id;
					$this->data['payment_ref'] = $this->site->getReference('pp',$biller_id);
				}
			}else {
				$this->data['biller_id'] = $purchase->biller_id;
				$this->data['payment_ref'] = $this->site->getReference('pp',$purchase->biller_id);
			}



            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
			$combine_payment = $this->sales_model->getCombinePaymentPurById($arr);
            $this->data['combine_purchases'] = $combine_payment;
            //$this->data['payment_ref'] = $this->site->getReference('pp', $biller_id);
			$this->data['billers'] = $this->site->getAllCompanies('biller');
			$this->data['bankAccounts'] =  $this->site->getAllBankAccounts();
			$this->data['setting'] = $setting;
            $this->data['modal_js'] = $this->site->modal_js();

            $this->load->view($this->theme . 'purchases/combine_payment_supplier', $this->data);
        }
    }

	public function order_2_po($id = null)
    {
		if(($this->purchases_model->getPoquantiyById($id)->quantity_po) >= ($this->purchases_model->getPoquantiyById($id)->quantity)){
			$this->session->set_flashdata('error', lang("purchase_order_already_received"));
            redirect($_SERVER["HTTP_REFERER"]);
		}
        $this->erp->checkPermissions();

        $this->form_validation->set_message('is_natural_no_zero', $this->lang->line("no_zero_required"));
        //$this->form_validation->set_rules('reference_no', $this->lang->line("ref_no"), 'required');
        $this->form_validation->set_rules('warehouse', $this->lang->line("warehouse"), 'required|is_natural_no_zero');
        $this->form_validation->set_rules('supplier', $this->lang->line("supplier"), 'required');

        $this->session->unset_userdata('csrf_token');
        if ($this->form_validation->run() == true) {
            $quantity = "quantity";
            $product = "product";
            $unit_cost = "unit_cost";
            $tax_rate = "tax_rate";
            $reference = $this->input->post('reference_no') ? $this->input->post('reference_no') : $this->site->getReference('po');
			$payment_term = $this->input->post('payment_term');
            if ($this->Owner || $this->Admin) {
                $date = $this->erp->fld(trim($this->input->post('date')));
            } else {
                $date = date('Y-m-d H:i:s');
            }
            $warehouse_id = $this->input->post('warehouse');
            $supplier_id = $this->input->post('supplier');
			$rsupplier_id = $this->input->post('rsupplier_id');

            $status = $this->input->post('status');
            $shipping = $this->input->post('shipping') ? $this->input->post('shipping') : 0;
            $payment_status = $this->input->post('payment_status');

			$supplier_details = $this->site->getCompanyByID($supplier_id);
            $supplier = $supplier_details->company != '-'  ? $supplier_details->company : $supplier_details->name;

            $note = $this->erp->clear_tags($this->input->post('note'));
			$variant_id = $this->input->post('variant_id');

			//$variant_id = array_filter($variant_id);

            $total              = 0;
            $product_tax        = 0;
            $order_tax          = 0;
            $product_discount   = 0;
            $order_discount     = 0;
            $percentage         = '%';
            $i = sizeof($_POST['product']);
            for ($r = 0; $r < $i; $r++) {
                $item_code = $_POST['product'][$r];
                $item_net_cost = $this->erp->formatPurDecimal($_POST['net_cost'][$r]);
                $unit_cost = $this->erp->formatPurDecimal($_POST['unit_cost'][$r]);
                $real_unit_cost = $this->erp->formatPurDecimal($_POST['real_unit_cost'][$r]);
                $item_quantity = $_POST['quantity'][$r];

				$serial_no = $_POST['serial'][$r];

                $p_supplier = $_POST['rsupplier_id'][$r];

				$p_price = $_POST['price'][$r];

                $p_type = $_POST['type'][$r];

                $item_option = isset($_POST['product_option'][$r]) ? $_POST['product_option'][$r] : NULL;

				if($item_option == 'undefined'){
					$item_option = NULL;
				}

                $item_tax_rate = isset($_POST['product_tax'][$r]) ? $_POST['product_tax'][$r] : null;
                $item_discount = isset($_POST['product_discount'][$r]) ? $_POST['product_discount'][$r] : null;
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
                    $unit_cost = $real_unit_cost;
                    $pr_discount = 0;

                    if (isset($item_discount)) {
                        $discount = $item_discount;
                        $dpos = strpos($discount, $percentage);
                        if ($dpos !== false) {
                            $pds = explode("%", $discount);
                            $pr_discount = (($this->erp->formatPurDecimal($unit_cost)) * (Float) ($pds[0])) / 100;
                        } else {
                            $pr_discount = $this->erp->formatPurDecimal($discount);
                        }
                    }

                    $unit_cost = $this->erp->formatPurDecimal($unit_cost - $pr_discount);
                    $item_net_cost = $unit_cost;
                    $pr_item_discount = $this->erp->formatPurDecimal($pr_discount * $item_quantity);
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
                                $item_tax = $this->erp->formatPurDecimal((($unit_cost) * $tax_details->rate) / 100);
                                $tax = $tax_details->rate . "%";
                            } else {
                                $item_tax = $this->erp->formatPurDecimal((($unit_cost) * $tax_details->rate) / (100 + $tax_details->rate));
                                $tax = $tax_details->rate . "%";
                                $item_net_cost = $unit_cost - $item_tax;
                            }
                        } elseif ($tax_details->type == 2) {
                            if ($product_details && $product_details->tax_method == 1) {
                                $item_tax = $this->erp->formatPurDecimal((($unit_cost) * $tax_details->rate) / 100);
                                $tax = $tax_details->rate . "%";
                            } else {
                                $item_tax = $this->erp->formatPurDecimal((($unit_cost) * $tax_details->rate) / (100 + $tax_details->rate));
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
                    $subtotal = (($item_net_cost * $item_quantity) + $pr_item_tax);

					$setting = $this->site->get_setting();
					$ohmygod = $this->site->getPurchasedItems($product_details->id, $warehouse_id, $item_option);

					if(!$setting->accounting_method == 2){
						//$real_unit_costs = $this->site->calculateCosts($unit_cost, $item_quantity, $shipping);
						$real_unit_costs = $this->site->calculateCost($unit_cost, $item_quantity, $shipping);
						//echo $real_unit_costs.'he';exit;
					} else {
						$real_unit_costs = $this->site->calculateAVCosts($product_details->id, $warehouse_id, $item_net_cost, $unit_cost, $item_quantity, $product_details->name, $item_option, $item_quantity, $shipping);
						//echo $item_option.'she';exit;
					}
					if($setting->accounting_method == 2) {
						$products[] = array(
							'product_id' => $product_details->id,
							'product_code' => $item_code,
							'product_name' => $product_details->name,
							//'product_type' => $item_type,
							'option_id' => $item_option,
							'net_unit_cost' => $item_net_cost, //kiry  + $pr_discount
							'unit_cost' => $this->erp->formatPurDecimal($item_net_cost + $pr_discount + $item_tax),
							'quantity' => $item_quantity,
							'quantity_balance' => $quantity_balance,
							'warehouse_id' => $warehouse_id,
							'item_tax' => $pr_item_tax,
							'tax_rate_id' => $pr_tax,
							'tax' => $tax,
							'discount' => $item_discount,
							'item_discount' => $pr_item_discount,
							'subtotal' => $this->erp->formatPurDecimal($subtotal),
							'expiry' => $item_expiry,
							'real_unit_cost' => $real_unit_costs,
							'date' => date('Y-m-d', strtotime($date)),
							'status' => $status,
							'price' =>$p_price,
                            'supplier_id' => $p_supplier,
                            'type' => $p_type
						);
						$serial[] = array(
							'product_id'    => $product_details->id,
							'serial_number' => $serial_no,
							'warehouse'     => $warehouse_id,
							'biller_id'     => $this->session->userdata('biller_id')?$this->session->userdata('biller_id'):$this->Settings->default_biller,
							'serial_status' => 1

						);
					}
					else{
						$products[] = array(
							'product_id' => $product_details->id,
							'product_code' => $item_code,
							'product_name' => $product_details->name,
							//'product_type' => $item_type,
							'option_id' => $item_option,
							'net_unit_cost' => $item_net_cost,
							'unit_cost' => $this->erp->formatPurDecimal($item_net_cost + $item_tax  - $pr_discount), //kiry  - $pr_discount
							'quantity' => $item_quantity,
							'quantity_balance' => $quantity_balance,
							'warehouse_id' => $warehouse_id,
							'item_tax' => $pr_item_tax,
							'tax_rate_id' => $pr_tax,
							'tax' => $tax,
							'discount' => $item_discount,
							'item_discount' => $pr_item_discount,
							'subtotal' => $this->erp->formatPurDecimal($subtotal),
							'expiry' => $item_expiry,
							'real_unit_cost' => $real_unit_cost,
							'date' => date('Y-m-d', strtotime($date)),
							'status' => $status,
							'price' =>$p_price,
                            'supplier_id' => $p_supplier?$p_supplier:'',
                            'type' => $p_type
						);

						$serial[] = array(
							'product_id'    => $product_details->id,
							'serial_number' => $serial_no,
							'warehouse'     => $warehouse_id,
							'biller_id'     => $this->session->userdata('biller_id')?$this->session->userdata('biller_id'):$this->Settings->default_biller,
							'serial_status' => 1

						);
					}

                    $total += $item_net_cost * $item_quantity;
                }
            }

			if($setting->accounting_method == 2 || $shipping){

				$c = count($products);

				$t_po_item_amount = 0;
				foreach($products as $p){
					$t_po_item_amount += $p['subtotal'];
				}
				for($i = 0; $i < $c; $i++){
					$net_u_cost = (($products[$i]['net_unit_cost'] - $products[$i]['item_discount'])/$products[$i]['quantity']);
					$total_unit_cost = $products[$i]['quantity'] * $products[$i]['net_unit_cost'];
					$total_f_amt = $products[$i]['quantity'] * $products[$i]['net_unit_cost'];
					$costunit = $this->site->calculateAverageCostShipping($products[$i]['product_id'], $products[$i]['warehouse_id'], $net_u_cost, $products[$i]['quantity'], $products[$i]['option_id'], $shipping, $products[$i]['subtotal'],$t_po_item_amount);
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
                    $order_discount = $this->erp->formatPurDecimal((($total + $product_tax) * (Float) ($ods[0])) / 100);
                } else {
                    $order_discount = $this->erp->formatPurDecimal($order_discount_id);
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
            $grand_total = $this->erp->formatPurDecimal(($total - $order_discount) + $total_tax + $shipping );
            $data = array(
				'biller_id' => $this->session->userdata('biller_id')?$this->session->userdata('biller_id'):$this->Settings->default_biller,
				'reference_no' => $reference,
				'payment_term' => $payment_term,
                'date' => $date,
                'supplier_id' => $supplier_id,
                'supplier' => $supplier,
                'warehouse_id' => $warehouse_id,
                'note' => $note,
                'total' => $this->erp->formatPurDecimal($total),
                'product_discount' => $this->erp->formatPurDecimal($product_discount),
                'order_discount_id' => $order_discount_id,
                'order_discount' => $order_discount,
                'total_discount' => $total_discount,
                'product_tax' => $this->erp->formatPurDecimal($product_tax),
                'order_tax_id' => $order_tax_id,
                'order_tax' => $order_tax,
                'total_tax' => $total_tax,
                'shipping' => $this->erp->formatPurDecimal($shipping),
                'grand_total' => $grand_total,
                'status' => $status,
                'payment_status' => $payment_status,
                'created_by' => $this->session->userdata('user_id'),
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

			if ($payment_status == 'partial' || $payment_status == 'paid') {
                if ($this->input->post('paid_by') == 'gift_card') {
                    $gc = $this->site->getGiftCardByNO($this->input->post('gift_card_no'));
                    $amount_paying = $grand_total >= $gc->balance ? $gc->balance : $grand_total;
                    $gc_balance = $gc->balance - $amount_paying;

					$payment = array(
						'date' => $date,
						'reference_no' => $this->input->post('payment_reference_no'),
						'amount' => $this->erp->formatDecimal($amount_paying),
						'paid_by' => $this->input->post('paid_by'),
						'cheque_no' => $this->input->post('cheque_no'),
						'cc_no' => $this->input->post('gift_card_no'),
						'cc_holder' => $this->input->post('pcc_holder'),
						'cc_month' => $this->input->post('pcc_month'),
						'cc_year' => $this->input->post('pcc_year'),
						'cc_type' => $this->input->post('pcc_type'),
						'created_by' => $this->session->userdata('user_id'),
						'note' => $this->input->post('payment_note'),
						'type' => 'received',
						'gc_balance' => $gc_balance,
						'biller_id' => $this->session->userdata('biller_id')?$this->session->userdata('biller_id'):$this->Settings->default_biller
					);
                    $data['paid'] = $this->erp->formatDecimal($amount_paying);
                } else {
					$payment = array(
						'date' => $date,
						'reference_no' => $this->input->post('payment_reference_no'),
						'amount' => $this->erp->formatDecimal($this->input->post('amount-paid')),
						'paid_by' => $this->input->post('paid_by'),
						'cheque_no' => $this->input->post('cheque_no'),
						'cc_no' => $this->input->post('pcc_no'),
						'cc_holder' => $this->input->post('pcc_holder'),
						'cc_month' => $this->input->post('pcc_month'),
						'cc_year' => $this->input->post('pcc_year'),
						'cc_type' => $this->input->post('pcc_type'),
						'created_by' => $this->session->userdata('user_id'),
						'note' => $this->input->post('payment_note'),
						'type' => 'received',
						'biller_id' => $this->session->userdata('biller_id')?$this->session->userdata('biller_id'):$this->Settings->default_biller,
					);
                    $data['paid'] = $this->erp->formatDecimal($this->input->post('amount-paid'));
                }
				if($_POST['paid_by'] == 'depreciation') {
					$no             = sizeof($_POST['no']);
					$period         = 1;
                    $biller_id 		= $this->input->post('biller');
					for($m = 0; $m < $no; $m++){
						$dateline = date('Y-m-d', strtotime($_POST['dateline'][$m]));
						$loans[] = array(
							'period' => $period,
							'sale_id' => '',
							'interest' => $_POST['interest'][$m],
							'principle' => $_POST['principle'][$m],
							'payment' => $_POST['payment_amt'][$m],
							'balance' => $_POST['balance'][$m],
							'type' => $_POST['depreciation_type'],
							'rated' => $_POST['depreciation_rate1'],
							'note' => $_POST['note_1'][$m],
							'dateline' => $dateline,
							'biller_id' => $biller_id
						);
						$period++;
					}

				}else{
					$loans = array();
				}

            } else {
                $payment = array();
            }

        }

        if ($this->form_validation->run() == true && $this->purchases_model->addPurchase($data, $products, $payment, $id)) {
			if($this->Settings->purchase_serial){
				$this->purchases_model->addSerial($serial);
			}
            $this->session->set_userdata('remove_pols', 1);
            $this->session->set_flashdata('message', $this->lang->line("purchase_added"));
            redirect('purchases');
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['inv'] = $this->purchases_model->getPurchaseOrderByID($id);
            $ref = $this->site->getReference('pp');
            $pref = $this->purchases_model->getPaymentByPurchaseID($id);
            $this->data['payment_ref'] = $pref?$pref->reference_no:$ref;

            if ($this->data['inv']->date <= date('Y-m-d', strtotime('-3 months'))) {
                $this->session->set_flashdata('error', lang("purchase_x_edited_older_than_3_months"));
                redirect($_SERVER["HTTP_REFERER"]);
            }

            $inv_items = $this->purchases_model->getAllPurchaseOrderItems($id);

            $c = rand(100000, 9999999);
            foreach ($inv_items as $item) {
                $row = $this->site->getProductByID($item->product_id);
                $row->expiry = (($item->expiry && $item->expiry != '0000-00-00') ? $this->erp->fsd($item->expiry) : '');
                $row->qty = $item->quantity;

                $row->received = $item->quantity_received ? $item->quantity_received : $item->quantity;
                $row->quantity_balance = $item->quantity_balance + ($item->quantity-$row->received);
                $row->discount = $item->discount ? $item->discount : '0';
                $options = $this->purchases_model->getProductOptions($row->id);
                $row->option = $item->option_id;
                $row->real_unit_cost = $item->real_unit_cost;
                $row->cost = $this->erp->formatPurDecimal($item->net_unit_cost + ($item->item_discount / $item->quantity));
                $row->tax_rate = $item->tax_rate_id;
				$row->net_cost = $item->net_unit_cost;

				$pii = $this->purchases_model->getPurcahseItemByPurchaseID($id);

                unset($row->details, $row->product_details, $row->file, $row->product_group_id);
                $ri = $this->Settings->item_addition ? $row->id : $c;
                if ($row->tax_rate) {
                    $tax_rate = $this->site->getTaxRateByID($row->tax_rate);
                    $pr[$ri] = array('id' => $c, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'tax_rate' => $tax_rate, 'options' => $options, 'suppliers' => '' ,'supplier_id' => $item->supplier_id);
                } else {
                    $pr[$ri] = array('id' => $c, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'tax_rate' => false, 'options' => $options, 'suppliers' => '' ,'supplier_id' => $item->supplier_id);
                }
                $c++;
            }

            $this->data['inv_items'] = json_encode($pr);
            $this->data['id'] = $id;
            $this->data['suppliers'] = $this->site->getAllCompanies('supplier');
            $this->data['purchase'] = $this->purchases_model->getPurchaseByID($id);
            $this->data['categories'] = $this->site->getAllCategories();
            $this->data['tax_rates'] = $this->site->getAllTaxRates();
            $this->data['warehouses'] = $this->site->getAllWarehouses();
            $this->load->helper('string');
            $value = random_string('alnum', 20);
            $this->session->set_userdata('user_csrf', $value);
            $this->session->set_userdata('remove_pols', 1);
            $this->data['csrf'] = $this->session->userdata('user_csrf');
            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('purchases'), 'page' => lang('purchases')), array('link' => '#', 'page' => lang('add_purchase')));
            $meta = array('page_title' => lang('add_purchase'), 'bc' => $bc);
            $this->page_construct('purchases/order_2_po', $meta, $this->data);
        }
    }

	public function edit_purchase_order($id = null)
    {
		$this->erp->checkPermissions('edit', null, 'purchases_order');

		if(($this->purchases_model->getPoquantiyById($id)->quantity_po) > 0){
			$this->session->set_flashdata('error', lang("purchase_order_cannot_edit"));
            redirect($_SERVER["HTTP_REFERER"]);
		}
		$setting = $this->site->get_setting();
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $inv = $this->purchases_model->getPurchaseOrderByID($id);
        if (!$this->session->userdata('edit_right')) {
            $this->erp->view_rights($inv->created_by);
        }
        $this->form_validation->set_message('is_natural_no_zero', $this->lang->line("no_zero_required"));
        $this->form_validation->set_rules('reference_no', $this->lang->line("ref_no"), 'required');
        $this->form_validation->set_rules('warehouse', $this->lang->line("warehouse"), 'required|is_natural_no_zero');
        $this->form_validation->set_rules('supplier', $this->lang->line("supplier"), 'required');
		$this->form_validation->set_rules('biller', $this->lang->line("biller"), 'required');
		//$this->form_validation->set_rules('payment_term', $this->lang->line("payment_term"), 'required');

        $this->session->unset_userdata('csrf_token');
        if ($this->form_validation->run() == true) {
            $quantity 		= "quantity";
            $product 		= "product";
            $unit_cost 		= "unit_cost";
            $tax_rate 		= "tax_rate";
            $reference 		= $this->input->post('reference_no');
			$payment_term 	= $this->input->post('payment_term');
			$payment_status = $this->input->post('payment_status');
			$amount_o 		= $this->input->post('amount_o');
			$biller_id 		= $this->input->post('biller');
			$due_date 		= $payment_term ? date('Y-m-d', strtotime('+' . $payment_term . ' days')) : NULL;

            if ($this->Owner || $this->Admin || $this->Settings->allow_change_date == 1) {
                $date = $this->erp->fld(trim($this->input->post('date')));
            } else {
                $date = date('Y-m-d H:i:s');
            }

            $warehouse_id 	= $this->input->post('warehouse');
            $supplier_id 	= $this->input->post('supplier');
            $status 		= 'pending';
            $shipping 		= $this->input->post('shipping') ? $this->input->post('shipping') : 0;
            $supplier_details = $this->site->getCompanyByID($supplier_id);
            $supplier 		= $supplier_details->company != '-'  ? $supplier_details->company : $supplier_details->name;
            $note 			= $this->input->post('note');

            $total 			= 0;
            $product_tax 	= 0;
            $order_tax 		= 0;
            $product_discount = 0;
            $order_discount = 0;
            $percentage 	= '%';
            $partial 		= false;
            $i = sizeof($_POST['product']);
            for ($r = 0; $r < $i; $r++) {
                $item_code 		= $_POST['product'][$r];
                $item_net_cost 	= $_POST['net_cost'][$r];
                $unit_cost 		= $_POST['unit_cost'][$r];
				$unit_cost_real = $unit_cost;
				// Price
				$p_price 		= $_POST['price'][$r];

                // Supplier
				$p_supplier 	= $_POST['rsupplier_id'][$r];

                $real_unit_cost = $_POST['real_unit_cost'][$r];

				$received_hidden = $_POST['received_hidden'][$r];
				$current_stock 	= $_POST['rstock'][$r];
				$item_piece		= $_POST['piece'][$r];
				$item_wpiece	= $_POST['wpiece'][$r];

                $item_quantity 	= $_POST['quantity'][$r];
                $quantity_received = $_POST['received'][$r];
                $item_option 	= isset($_POST['product_option'][$r]) && $_POST['product_option'][$r] != 'false' ? $_POST['product_option'][$r] : null;
                $item_tax_rate 	= isset($_POST['product_tax'][$r]) ? $_POST['product_tax'][$r] : null;
                $item_discount 	= isset($_POST['product_discount'][$r]) ? $_POST['product_discount'][$r] : null;
                $item_expiry 	= isset($_POST['expiry'][$r]) ? $this->erp->fsd($_POST['expiry'][$r]) : null;

                $quantity_balance = $_POST['quantity_balance'][$r];
                $ordered_quantity = $_POST['ordered_quantity'][$r];
                $tax_method		  = $_POST['tax_method'][$r];
				$create_request_id = $_POST['create_request_id'][$r];
				$balance_qty = $item_quantity;
				$quantity_received = $item_quantity;

                if (isset($item_code) && isset($real_unit_cost) && isset($unit_cost) && isset($item_quantity) && isset($quantity_balance)) {
                    $product_details = $this->purchases_model->getProductByCode($item_code);

                    $unit_cost = $real_unit_cost;
                    $pr_discount = 0;

                    if (isset($item_discount)) {
						$discount = $item_discount;
						$dpos = strpos($discount, $percentage);
						if ($dpos !== false) {
							$pds = explode("%", $discount);
							$pr_discount = (($unit_cost * (Float) ($pds[0])) / 100);
						} else {
							$pr_discount = ($discount / $item_quantity);
						}
					}

                    //$unit_cost = $this->erp->formatPurDecimal($unit_cost - $pr_discount);
                    $item_net_cost 	  = $unit_cost;

                    $pr_item_discount = ($pr_discount * $item_quantity);
                    $product_discount += $pr_item_discount;
                    $pr_tax 		  = 0;
                    $pr_item_tax      = 0;
                    $item_tax         = 0;
                    $tax              = "";
					$ptax_method	 = ($tax_method == ""? $product_details->tax_method:$tax_method);

                    if (isset($item_tax_rate) && $item_tax_rate != 0) {
						$pr_tax = $item_tax_rate;
						$tax_details = $this->site->getTaxRateByID($pr_tax);

						if ($tax_details->type == 1 && $tax_details->rate != 0) {
							if ($product_details && $ptax_method == 1) {
								$item_tax = $this->erp->formatDecimal((($unit_cost - $pr_discount) * $tax_details->rate) / 100, 4);
								$tax = $tax_details->rate . "%";
							} else {
                                $item_tax = $this->erp->formatDecimal((($unit_cost) * $tax_details->rate) / (100 + $tax_details->rate));
								$tax = $tax_details->rate . "%";
								$item_net_cost = $item_net_cost - $item_tax;
							}

						} elseif ($tax_details->type == 2) {

							if ($product_details && $ptax_method == 1) {
								$item_tax = $this->erp->formatDecimal((($unit_cost - $pr_discount) * $tax_details->rate) / 100, 4);
								$tax = $tax_details->rate . "%";
							} else {
								$item_tax = $this->erp->formatDecimal((($unit_cost_real - $pr_discount) * $tax_details->rate) / (100 + $tax_details->rate), 4);
								$tax = $tax_details->rate . "%";
								$item_net_cost = $item_net_cost - $item_tax;
							}

							$item_tax = $tax_details->rate;
							$tax = $tax_details->rate;
						}
						$pr_item_tax = $this->erp->formatDecimal($item_tax * $item_quantity, 4);
					}


					$quantity_balance = 0;
					$option_cost = 0;
					if($item_option != 0) {
						$row = $this->purchases_model->getVariantQtyById($item_option);
						$quantity_balance = $item_quantity * $row->qty_unit;
						$option_cost = $row->cost * $row->qty_unit;
					}else{
						$quantity_balance = $item_quantity;
					}

                    $product_tax += $pr_item_tax;
					if($ptax_method == 0){
						if($item_option != 0) {
							if($item_net_cost == $option_cost){
								$subtotal = (($item_net_cost * ($quantity_received!=0?$quantity_received:$item_quantity)) + $pr_item_tax) - $pr_item_discount;
							}else{
								$subtotal = (($item_net_cost * ($quantity_received!=0?$quantity_received:$item_quantity))+ $pr_item_tax) - $pr_item_discount;
							}
						}else{
							$subtotal = (($item_net_cost * ($quantity_received!=0?$quantity_received:$item_quantity))+ $pr_item_tax) - $pr_item_discount;
						}

					}else{

						if($item_option != 0) {
							if($item_net_cost == $option_cost){
								$subtotal = (($item_net_cost * ($quantity_received!=0?$quantity_received:$item_quantity)) + $pr_item_tax) - $pr_item_discount;
							}else{
								$subtotal = (($item_net_cost * ($quantity_received!=0?$quantity_received:$item_quantity)) + $pr_item_tax) - $pr_item_discount;
							}
						}else{
							$subtotal = (($item_net_cost * ($quantity_received!=0?$quantity_received:$item_quantity)) + $pr_item_tax) - $pr_item_discount;
						}

					}

					$q = $this->purchases_model->getPurcahseOrderItemByPurchaseID($id);
					$real_unit_costs = $real_unit_cost;

                    $items[] = array(
                        'product_id' 		=> $product_details->id,
						'create_id' 		=> $create_request_id,
                        'product_code' 		=> $item_code,
                        'product_name' 		=> $product_details->name,
                        //'product_type' 	=> $item_type,
                        'option_id' 		=> $item_option,
                        'net_unit_cost' 	=> $item_net_cost,//$real_unit_costs,
                        'unit_cost' 		=> $unit_cost,
                        'quantity' 			=> $item_quantity,
						'quantity_balance' 	=> $quantity_balance,
                        'quantity_received' => '0',
                        'warehouse_id' 		=> $warehouse_id,
						'tax_method'		=> $tax_method,
                        'item_tax' 			=> $pr_item_tax,
                        'tax_rate_id' 		=> $pr_tax,
                        'tax' 				=> $tax,
                        'discount' 			=> $item_discount,
                        'item_discount' 	=> $pr_item_discount,
                        'subtotal' 			=> $subtotal,
                        'expiry' 			=> $item_expiry,
                        'real_unit_cost' 	=> $real_unit_costs,
                        'date' 				=> date('Y-m-d H:i:s', strtotime($date)),
                        'status' 			=> $status,
						'price' 			=> $p_price,
                        'supplier_id' 		=> $p_supplier,
						'piece'				=> $item_piece,
						'wpiece'			=> $item_wpiece
                    );
					/*if($item_option != 0) {
						if($item_net_cost == $option_cost){
							$total += $item_net_cost * ($quantity_received!=0?$quantity_received*$option_cost:$item_quantity*$option_cost);
						}else{
							$total += $item_net_cost * ($quantity_received!=0?$quantity_received:$item_quantity);
						}
					}else{
						$total += $item_net_cost * ($quantity_received?$quantity_received:$item_quantity);
					}*/
					$total += $subtotal;
                }
            }

            if (empty($items)) {
                $this->form_validation->set_rules('product', lang("order_items"), 'required');
            } else {
                foreach ($items as $item) {
                    $item["status"] = $status;
                    $products[] = $item;
                }
                krsort($products);
            }

            if ($this->input->post('discount')) {
                $order_discount_id = $this->input->post('discount');
                $opos = strpos($order_discount_id, $percentage);
                if ($opos !== false) {
                    $ods = explode("%", $order_discount_id);
                    $order_discount = (($total * (Float) ($ods[0])) / 100);
                } else {
                    $order_discount = (($total * (Float) ($order_discount_id)) / 100);
                }
            } else {
                $order_discount_id = null;
            }
            $total_discount = ($order_discount + $product_discount);

            if ($this->Settings->tax2 != 0) {
                $order_tax_id = $this->input->post('order_tax');
                if ($order_tax_details = $this->site->getTaxRateByID($order_tax_id)) {
                    if ($order_tax_details->type == 2) {
                        $order_tax = $this->erp->formatPurDecimal($order_tax_details->rate);
                    }
                    if ($order_tax_details->type == 1) {
                        $order_tax = $this->erp->formatPurDecimal((($total + $shipping - $order_discount) * $order_tax_details->rate) / 100);
                    }
                }
            } else {
                $order_tax_id = null;
            }

            $total_tax 		= ($product_tax + $order_tax);
            $grand_total 	= ($total + $order_tax + $shipping - $order_discount);

            $data = array(
				'date' 				=> $date,
				'biller_id' 		=> $biller_id,
				'reference_no' 		=> $reference,
				'payment_term' 		=> $payment_term,
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
                'status' 			=> $status,
                'payment_status' 	=> $payment_status,
                'updated_by' 		=> $this->session->userdata('user_id'),
                'updated_at' 		=> date('Y-m-d H:i:s'),
				'create_request' 	=> $create_request_id
            );

            if ($date) {
                $data['date'] = $date;
            }

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
			if ($payment_status == 'partial' || $payment_status == 'paid') {
                if ($this->input->post('paid_by') == 'gift_card') {
                    $gc = $this->site->getGiftCardByNO($this->input->post('gift_card_no'));
                    $amount_paying = $grand_total >= $gc->balance ? $gc->balance : $grand_total;
                    $gc_balance = $gc->balance - $amount_paying;

                    $payment = array(
                        'date' => $date,
                        'reference_no' => $this->input->post('payment_reference_no'),
                        'amount' => $this->erp->formatDecimal($amount_paying),
                        'paid_by' => $this->input->post('paid_by'),
                        'cheque_no' => $this->input->post('cheque_no'),
                        'cc_no' => $this->input->post('gift_card_no'),
                        'cc_holder' => $this->input->post('pcc_holder'),
                        'cc_month' => $this->input->post('pcc_month'),
                        'cc_year' => $this->input->post('pcc_year'),
                        'cc_type' => $this->input->post('pcc_type'),
                        'created_by' => $this->session->userdata('user_id'),
                        'note' => $this->input->post('payment_note'),
                        'type' => 'received',
                        'gc_balance' => $gc_balance,
                        'biller_id' => $biller_id
                    );
                    $data['paid'] = $this->erp->formatDecimal($amount_paying);
                } else {
                    $payment = array(
                        'date' => $date,
                        'reference_no' => $this->input->post('payment_reference_no'),
                        'amount' => $this->erp->formatDecimal($this->input->post('amount-paid')),
                        'paid_by' => $this->input->post('paid_by'),
                        'cheque_no' => $this->input->post('cheque_no'),
                        'cc_no' => $this->input->post('pcc_no'),
                        'cc_holder' => $this->input->post('pcc_holder'),
                        'cc_month' => $this->input->post('pcc_month'),
                        'cc_year' => $this->input->post('pcc_year'),
                        'cc_type' => $this->input->post('pcc_type'),
                        'created_by' => $this->session->userdata('user_id'),
                        'note' => $this->input->post('payment_note'),
                        'type' => 'received',
                        'biller_id' => $biller_id
                    );
                    $data['paid'] = $this->erp->formatDecimal($this->input->post('amount-paid'));
                }
                if($_POST['paid_by'] == 'depreciation') {
                    $no = sizeof($_POST['no']);
                    $period = 1;
                    for($m = 0; $m < $no; $m++){
                        $dateline = date('Y-m-d', strtotime($_POST['dateline'][$m]));
                        $loans[] = array(
                            'period' => $period,
                            'sale_id' => '',
                            'interest' => $_POST['interest'][$m],
                            'principle' => $_POST['principle'][$m],
                            'payment' => $_POST['payment_amt'][$m],
                            'balance' => $_POST['balance'][$m],
                            'type' => $_POST['depreciation_type'],
                            'rated' => $_POST['depreciation_rate1'],
                            'note' => $_POST['note_1'][$m],
                            'dateline' => $dateline,
                            'biller_id' => $biller_id
                        );
                        $period++;
                    }
                    //$this->erp->print_arrays($loans);
                }else{
                    $loans = array();
                }

            } else {
                $payment = array();
            }

            //$this->erp->print_arrays($data, $products, $payment);

        }

        if ($this->form_validation->run() == true && $this->purchases_model->updatePurchaseOrder($id, $data, $products, $payment,$amount_o)) {
           $this->session->set_userdata('remove_polso', '1');
            $this->session->set_flashdata('message', $this->lang->line("purchase_order_updated"));
            redirect('purchases/purchase_order');
        } else {

			if ($this->session->userdata('biller_id')) {
                $biller_id = $this->session->userdata('biller_id');
            } else {
                $biller_id = $this->Settings->default_biller;
            }

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['inv'] = $this->purchases_model->getPurchaseOrderByID($id);
            $ref = $this->site->getReference('pp', $biller_id);
            $this->data['payment_ref'] = $ref;
            // $this->data['payment_ref'] = $pref?$pref->reference_no:$ref;
			$this->load->model('sales_model');
            if ($this->data['inv']->date <= date('Y-m-d', strtotime('-3 months'))) {
                $this->session->set_flashdata('error', lang("purchase_x_edited_older_than_3_months"));
                redirect($_SERVER["HTTP_REFERER"]);
            }
			if($this->data['inv']->status == "approved"){
					$this->session->set_flashdata('error', "Purchase order is ordered already. Can not edit more.");
                    redirect($_SERVER["HTTP_REFERER"]);
			}
            $inv_items = $this->purchases_model->getAllPurchaseOrderItems($id);

            $c = rand(100000, 9999999);
            foreach ($inv_items as $item) {

                $row 					= $this->site->getProductByID($item->product_id);
                $row->expiry 			= (($item->expiry && $item->expiry != '0000-00-00') ? $this->erp->fsd($item->expiry) : '');
                $row->qty 				= $item->po_qty;
                $row->received 			= $item->quantity_received ? $item->quantity_received : $row->qty;
                $row->quantity_balance 	= $item->quantity_balance + ($row->qty-$row->received);
                $row->discount 			= $item->discount ? $item->discount : '0';
                $options 				= $this->purchases_model->getProductOptions($row->id);
                $row->option 			= $item->option_id;
                $row->real_unit_cost 	= $item->real_unit_cost;
                $row->cost 				= $item->unit_cost;
                $row->tax_rate 			= $item->tax_rate_id;
				$row->net_cost 			= $item->unit_cost;
				$row->price 			= $item->price;
				$test 					= $this->sales_model->getWP2($item->product_id, $item->warehouse_id);
				if ($test->quantity) {
                    $row->quantity 		= $test->quantity;
                } else {
                    $row->quantity 		= 0;
                }

				$row->tax_method		= $item->tax_method;
				$pii 					= $this->purchases_model->getPurcahseItemByPurchaseID($id);
				$row->piece	 			= $item->piece;
				$row->wpiece 			= $item->wpiece;

                unset($row->details, $row->product_details, $row->file, $row->product_group_id);
                $ri = $this->Settings->item_addition ? $row->id : $c;
                if ($row->tax_rate) {
                    $tax_rate = $this->site->getTaxRateByID($row->tax_rate);
                    $pr[$c] = array('id' => $c, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'tax_rate' => $tax_rate, 'options' => $options, 'suppliers' => '' ,'supplier_id' => $item->supplier_id,'create_request_id'=>$item->create_id);
                } else {
                    $pr[$c] = array('id' => $c, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'tax_rate' => false, 'options' => $options, 'suppliers' => '' ,'supplier_id' => $item->supplier_id,'create_request_id'=>$item->create_id);
                }
                $c++;
            }
            //$this->erp->print_arrays($pr);
            $this->data['inv_items']    = json_encode($pr);
            $this->data['id']           = $id;
			$this->data['unit']         = $this->purchases_model->getUnits();
			$this->data['edit_status']  = $id;
            $this->data['suppliers']    = $this->site->getAllCompanies('supplier');
            $this->data['purchase']     = $this->purchases_model->getPurchaseOrderByID($id);
            $this->data['categories']   = $this->site->getAllCategories();
            $this->data['tax_rates']    = $this->site->getAllTaxRates();
            $this->data['warehouses']   = $this->site->getAllWarehouses();
			$this->data['payment_term'] = $this->site->getAllPaymentTerm();
			$this->data['billers']      = $this->site->getAllCompanies('biller');
			$this->data['currency'] 	= $this->site->getCurrency();
            $this->load->helper('string');
            $value = random_string('alnum', 20);
            $this->session->set_userdata('user_csrf', $value);
            $this->session->set_userdata('remove_polso', '1');
            $this->data['csrf'] = $this->session->userdata('user_csrf');
            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('purchases'), 'page' => lang('purchases_order')), array('link' => '#', 'page' => lang('edit_purchase_order')));
            $meta = array('page_title' => lang('edit_purchase_order'), 'bc' => $bc);
            $this->page_construct('purchases/edit_order', $meta, $this->data);
        }
    }

	/******** Nak
	********* Update Average cost
	********* 05/05/2017
	*********/

	public function edit($id = null)
    {
        $this->erp->checkPermissions('edit',null,'purchases');
		$setting = $this->site->get_setting();
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        $inv = $this->purchases_model->getPurchaseByID($id);
		$type_exp = $inv->type_of_po;

		$type_exp = $this->input->post('expance');
        $this->form_validation->set_message('is_natural_no_zero', $this->lang->line("no_zero_required"));
		if($type_exp == "po"){
			$this->form_validation->set_rules('warehouse', $this->lang->line("warehouse"), 'required|is_natural_no_zero');
		}
        $this->form_validation->set_rules('supplier', $this->lang->line("supplier"), 'required');
		$this->form_validation->set_rules('biller', $this->lang->line("biller"), 'required');

		$this->session->unset_userdata('csrf_token');
        if ($this->form_validation->run() == true) {

			if ($this->Owner || $this->Admin || $this->Settings->allow_change_date == 1) {
                $date = $this->erp->fld(trim($this->input->post('date')));
            } else {
                $date = date('Y-m-d H:i:s');
            }
            isCloseDate($date);
			$quantity 		= "quantity";
            $product 		= "product";
            $unit_cost 		= "unit_cost";
            $tax_rate 		= "tax_rate";
            $reference 		= $this->input->post('reference_no');
            $payment_status = $this->input->post('payment_status');
            $paid_by        = $this->input->post('paid_by');
            $paid_o         = $this->input->post('paid_o');
            $biller_id 		= $this->input->post('biller');

			$payment_term            = $this->input->post('payment_term');
            $payment_term_details    = $this->site->getPamentTermbyID($payment_term);
            $due_date                = $payment_term_details ? date('Y-m-d', strtotime($date . '+' . $payment_term_details->due_day . ' days')) : NULL;

			$amount_o = $this->input->post('amount_o');

            $warehouse_id 		= $this->input->post('warehouse');
            $supplier_id 		= $this->input->post('supplier');
            $status 			= $this->input->post('purchase_status');
            $shipping 			= $this->input->post('shipping') ? $this->input->post('shipping') : 0;
            $order_ref 			= $this->input->post('order_ref') ? $this->input->post('order_ref') : '';
            $supplier_details 	= $this->site->getCompanyByID($supplier_id);
            $supplier 			= $supplier_details->company != '-'  ? $supplier_details->company : $supplier_details->name;
            $note 				= $this->input->post('note');

			if($type_exp=='po'){

				$total 				= 0;
				$product_tax 		= 0;
				$order_tax 			= 0;
				$product_discount 	= 0;
				$order_discount 	= 0;
				$stotal 			= 0;
				$cogs_inv 			= 0;
				$percentage 		= '%';
				$amount 			= array();
				$qty 				= array();
				$partial 			= false;

				$i 					= sizeof($_POST['product']);
				for ($r = 0; $r < $i; $r++) {
					$item_code 			= $_POST['product'][$r];
					$item_net_cost 		= $_POST['net_cost'][$r];

					$unit_cost 			= $_POST['unit_cost'][$r];
					$unit_cost_real 	= $unit_cost;
					// Price
					$p_price 			= $_POST['price'][$r];

					$pur_id 			= $_POST['pur_id'][$r];
					// Supplier
					$p_supplier 		= $_POST['rsupplier_id'][$r];
					$create_order_id 	= $_POST['create_order_id'][$r];
					$real_unit_cost 	= $_POST['real_unit_cost'][$r];

					$received_hidden 	= $_POST['received_hidden'][$r];
					$current_stock 		= $_POST['rstock'][$r];

					$item_quantity 		= $_POST['quantity'][$r];
					$quantity_received 	= $_POST['received'][$r];
					$qty_received 		= $_POST['received'][$r];
					$tax_method			= $_POST['tax_method'][$r];
					$item_piece			= $_POST['piece'][$r];
					$item_wpiece		= $_POST['wpiece'][$r];
					$item_type			= $_POST['type'][$r];
					$item_option 		= isset($_POST['product_option'][$r]) && $_POST['product_option'][$r] != 'false' ? $_POST['product_option'][$r] : null;
					$item_tax_rate 		= isset($_POST['product_tax'][$r]) ? $_POST['product_tax'][$r] : null;
					$item_discount 		= isset($_POST['product_discount'][$r]) ? $_POST['product_discount'][$r] : null;
					$item_expiry 		= (isset($_POST['expiry'][$r]) && !empty($_POST['expiry'][$r])) ? $this->erp->fsd($_POST['expiry'][$r]) : null;
					$quantity_balance 	= $_POST['quantity_balance'][$r];
					$ordered_quantity 	= $_POST['ordered_quantity'][$r];

					if ($status == 'received' || $status == 'partial') {
						if ($quantity_received < $item_quantity) {
							$partial 	= 'partial';
						} elseif ($quantity_received > $item_quantity) {
							$this->session->set_flashdata('error', lang("received_more_than_ordered"));
							redirect($_SERVER["HTTP_REFERER"]);
						}

						$balance_qty 	= $item_quantity;

					} else {
						$balance_qty = 0;
						$quantity_received = $item_quantity;
					}

					if (isset($item_code) && isset($real_unit_cost) && isset($unit_cost) && isset($item_quantity) && isset($quantity_balance)) {
						$product_details = $this->purchases_model->getProductByCode($item_code);
						$item_details    = $this->purchases_model->getPurcahseItemByPurchaseIDProductID($id, $product_details->id, $create_order_id);
						//$unit_cost = $real_unit_cost;
						$pr_discount = 0;

						if (isset($item_discount)) {
							$discount = $item_discount;
							$dpos = strpos($discount, $percentage);
							if ($dpos !== false) {
								$pds = explode("%", $discount);
								$pr_discount = ((($unit_cost) * (Float) ($pds[0])) / 100);
							} else {
								$pr_discount = ($discount/ $item_quantity);
							}
						}

						$unit_cost 			= ($unit_cost - $pr_discount);
						$item_net_cost 		= $unit_cost;
						$pr_item_discount 	= ($pr_discount * $item_quantity);
						$product_discount 	+= $pr_item_discount;
						$pr_tax 			= 0;
						$pr_item_tax 		= 0;
						$item_tax 			= 0;
						$tax 				= "";
						$ptax_method		= ($tax_method == ""? $product_details->tax_method:$tax_method);
						$net_unit_cost		= $unit_cost;

						if (isset($item_tax_rate) && $item_tax_rate != 0) {
							$pr_tax 		= $item_tax_rate;
							$tax_details 	= $this->site->getTaxRateByID($pr_tax);

							if ($tax_details->type == 1 && $tax_details->rate != 0) {

								if ($product_details && $ptax_method == 1) {
									$item_tax 		= ($unit_cost * $tax_details->rate) / 100;
									$tax 			= $tax_details->rate . "%";
									$item_net_cost 	= $unit_cost + $item_tax;
									$net_unit_cost  = $unit_cost;
								} else {
									$item_tax 		= ($unit_cost * $tax_details->rate) / (100 + $tax_details->rate);
									$tax 			= $tax_details->rate . "%";
									$item_net_cost 	= $unit_cost;
									$net_unit_cost  = $unit_cost - $item_tax;
								}

							} elseif ($tax_details->type == 2) {

								if ($product_details && $ptax_method == 1) {
									$item_tax 		= ($unit_cost * $tax_details->rate) / 100;
									$tax 			= $tax_details->rate . "%";
									$item_net_cost 	= $unit_cost + $item_tax;
									$net_unit_cost  = $unit_cost;
								} else {
									$item_tax		= ($unit_cost * $tax_details->rate) / (100 + $tax_details->rate);
									$tax 			= $tax_details->rate . "%";
									$item_net_cost 	= $unit_cost;
									$net_unit_cost  = $unit_cost - $item_tax;
								}

								$item_tax = ($tax_details->rate);
								$tax = $tax_details->rate;
							}
							$pr_item_tax = ($item_tax * $item_quantity);
						}

						$quantity_balance = 0;
						$option_cost = 0;
						if($item_option != 0) {
							$row = $this->purchases_model->getVariantQtyById($item_option);
							$quantity_balance = $item_quantity * $row->qty_unit;
							$option_cost = $row->cost * $row->qty_unit;
						}else{
							$quantity_balance = $item_quantity;
						}

						$product_tax += $pr_item_tax;
						if($item_option != 0) {
							if($item_net_cost == $option_cost){
								$subtotal = ($item_net_cost * ( $quantity_received!=0?$quantity_received : $item_quantity));
							}else{
								$subtotal = ($item_net_cost * ($quantity_received!=0?$quantity_received:$item_quantity));
							}
						}else{
							$subtotal = ($item_net_cost * ($quantity_received!=0?$quantity_received:$item_quantity));
						}

						$q = $this->purchases_model->getPurcahseItemByPurchaseID($id);

						$amount[] = $subtotal;
						$qty[] = $item_quantity;

						if($received_hidden != $quantity_received){
							$ohmygod = $this->site->getPurchasedItems($product_details->id, $warehouse_id, $item_option);
							if(!$setting->accounting_method == 2){
								$real_unit_costs = $this->site->calculateCosts($unit_cost, $item_quantity, $shipping);
							} else {
								$real_unit_costs = $this->site->calculateAVCosts($product_details->id, $warehouse_id, $item_net_cost, $unit_cost, $item_quantity, $product_details->name, $item_option, $item_quantity, $shipping);
							}
						}else{
							$real_unit_costs = $real_unit_cost;
						}

						$option_id = (isset($item_option) && !empty($item_option)) ? $item_option : NULL;
						if($option_id){
							$option = $this->site->getProductVariantOptionIDPID($item_option, $product_details->id);
							$balance_qty = $balance_qty * $option->qty_unit;
						}
						$purid[] = array(
							'pur_id' => $pur_id
						);
						$products[] = array(
							'product_id' 		=> $product_details->id,
							'create_id' 		=> $create_order_id,
							'product_code' 		=> $item_code,
							'product_name' 		=> $product_details->name,
							'product_type' 		=> $item_type,
							'option_id' 		=> $item_option,
							'net_unit_cost' 	=> $net_unit_cost,
							'unit_cost' 		=> $unit_cost_real,
							'quantity' 			=> $item_quantity,
							'quantity_balance' 	=> $quantity_balance,
							'quantity_received' => $quantity_received,
							'warehouse_id' 		=> $warehouse_id,
							'tax_method'		=> $tax_method,
							'item_tax' 			=> $pr_item_tax,
							'tax_rate_id' 		=> $pr_tax,
							'tax' 				=> $tax,
							'discount' 			=> $item_discount,
							'item_discount' 	=> $pr_item_discount,
							'subtotal' 			=> $subtotal,
							'expiry' 			=> $item_expiry,
							'real_unit_cost' 	=> $real_unit_costs,
							'date' 				=> date('Y-m-d H:i:s', strtotime($date)),
							'status' 			=> "received",
							'price' 			=> $p_price,
							'transaction_type' 	=> 'PURCHASE',
							'cb_avg'			=> $item_details->cb_avg,
							'cb_qty'			=> $item_details->cb_qty,
							'net_shipping'		=> $item_details->net_shipping,
							'supplier_id' 		=> $p_supplier,
							'old_quantity'		=> $item_details->quantity,
							'piece'				=> $item_piece,
							'wpiece'			=> $item_wpiece
						);

						$total += $subtotal;
						if($product_details->type != "service") {
							$stotal += ($subtotal);
						}
						if($item_details->cb_qty < 0) {
							$cogs_inv += (($real_unit_costs - $item_details->cb_avg) * $item_details->cb_qty);
						}

						//============== Variants For AVG ===============//
						$qty_variant = 0;
						$cos_variant = 0;
						$variant 	 = $this->site->getUnitQuantity($item_option, $product_details->id);
						$qty_variant = $item_quantity;
						$cos_variant = $item_net_cost;
						if($variant){
							$qty_variant = $item_quantity * $variant->qty_unit;
							$cos_variant = $item_net_cost / $variant->qty_unit;
						}

						$avg_cost[] = array(
							'product_id' 		=> $product_details->id,
							'quantity'   		=> $qty_variant,
							'unit_cost'  		=> $cos_variant,
							'subtotal'  		=> ($cos_variant * $qty_variant),
							'price'      		=> $p_price,
							'option_id'  		=> $item_option,
						);
						//==================== End =====================//

					}
				}

				$out  				= array();
				foreach ($avg_cost as $key => $value){
					if (array_key_exists($value['product_id'], $out)){
						$out[$value['product_id']]['product_id'] = $value['product_id'];
						$out[$value['product_id']]['quantity'] 	+= $value['quantity'];
						$out[$value['product_id']]['unit_cost'] += $value['unit_cost'];
						$out[$value['product_id']]['price'] 	+= $value['price'];
						$out[$value['product_id']]['subtotal'] 	+= $value['subtotal'];
						$out[$value['product_id']]['option_id']  = $value['option_id'];
					} else {
						$out[$value['product_id']] = array(
							'product_id' => $value['product_id'],
							'quantity'   => $value['quantity'],
							'unit_cost'  => $value['unit_cost'],
							'price'      => $value['price'],
							'subtotal'   => $value['subtotal'],
							'option_id'  => $value['option_id'],
						);
					}
				}

				$array_c 			= array_values($out);

				if($setting->accounting_method == 2 || $shipping){
					$c = count($array_c);
					$t_po_item_amount 	= 0;
					$total_price 		= 0;
					$a 					= 0;

					foreach($array_c as $p){
						$total_price += $p['quantity'] * $p['unit_cost'];
					}

					$avg  				= array();
					$ship 				= array();
					for($i = 0; $i < $c; $i++){
						$item_option 	= $_POST['product_option'][$i];
						$unitCost 		= $_POST['unit_cost'][$i];
						$costunit 		= $this->site->editCalculateAVGCost2017($array_c[$i]['product_id'], $shipping, $array_c[$i]['quantity'], $array_c[$i]['price'],$total_price, $array_c[$i]['unit_cost'], $item_discount, $this->input->post('discount'), $array_c[$i]['option_id'], $id, $array_c[$i]['subtotal'], $stotal);

						$avg[$array_c[$i]['product_id']]  = $costunit['avgcost'];
						$ship[$array_c[$i]['product_id']] = $costunit['shipping_cost'];

					}
					$i = 0;
					foreach($products as $p){
						$products[$i]['real_unit_cost'] = $avg[ $p['product_id'] ];
						$i++;
					}
				}
                $sale_order_id              = $this->input->post('so_id');
                $items                      = $this->sales_model->getSaleOrdItems($sale_order_id);
				if (empty($products)) {
					$this->form_validation->set_rules('product', lang("order_items"), 'required');
				} else {
					foreach ($items as $item) {
						$item["status"] = $status;
						$products[] = $item;
					}
					krsort($products);
				}

				if ($this->input->post('discount')) {
					$order_discount_id = $this->input->post('discount');
					$opos = strpos($order_discount_id, $percentage);
					if ($opos !== false) {
						$ods = explode("%", $order_discount_id);
						$order_discount = ((($total) * (Float) ($ods[0])) / 100);
					} else {
						$order_discount = (($total) * ($order_discount_id / 100));
					}
				} else {
					$order_discount_id = null;
				}
				$total_discount = ($order_discount + $product_discount);

				if ($this->Settings->tax2 != 0) {
					$order_tax_id = $this->input->post('order_tax');
					if ($order_tax_details = $this->site->getTaxRateByID($order_tax_id)) {
						if ($order_tax_details->type == 2) {
							$order_tax = ($order_tax_details->rate);
						}
						if ($order_tax_details->type == 1) {
							$order_tax = ((($total + $shipping - $order_discount) * $order_tax_details->rate) / 100);
						}
					}
				} else {
					$order_tax_id = null;
				}

				$total_tax = ($product_tax + $order_tax);
				$grand_total = ($total + $order_tax + $shipping - $order_discount);
				if($payment_status == "pending" || $payment_status == "due"){
					$paid_by = '';
				}
				$data = array(
					'date'				=> $date,
					'biller_id' 		=> $biller_id,
					'reference_no' 		=> $reference,
                    'payment_term'      => $payment_term,
					'due_date' 		    => $due_date,
					'supplier_id' 		=> $supplier_id,
					'supplier' 			=> $supplier,
					'customer_id' 		=> $this->input->post('customers'),
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
					'status' 			=> "received",
					'updated_by' 		=> $this->session->userdata('user_id'),
					'updated_at' 		=> date('Y-m-d H:i:s'),
					'paid_by' 			=> $paid_by,
					'type_of_po'  		=> $type_exp,
					'account_code' 		=> $this->input->post('bank_account'),
					'pur_refer' 		=> $this->input->post('payment_reference_no'),
					'stotal'			=> $stotal,
					'order_ref'			=> $order_ref,
					'cogs'				=> $cogs_inv,
					'quote_id'			=> $this->input->post('quote_id')
				);

			} else {

				$ac_ap   		= $this->purchases_model->ACC_AP();
				$ac_tax   		= $this->purchases_model->ACC_Pur_Tax();
				$ac_old_transno = $this->input->post('old_transno');
				$account_code 	= $this->input->post('account_section');
				$reference_no 	= $this->input->post('reference_no');
				$debit 			= $this->input->post('debit');
				$amount_ap 		= $this->input->post('in_calDebit');
				$amount_tax 	= $this->input->post('in_calOrdTax');
				$description 	= $this->input->post('description');
				$tran_id     	= $this->input->post('tran_id');
				$i 				= 0;
				$data 			= array();
				$total 			= 0;

				$data[] =  array(
						'tran_type' 	=> 'PURCHASE EXPENSE',
						'tran_no' 		=> $ac_old_transno,
						'account_code' 	=> $ac_ap,
						'tran_date' 	=> $date,
						'reference_no' 	=> $reference_no,
						'description'	=> $description,
						'amount' 		=> (-($amount_ap + $amount_tax)),
						'biller_id' 	=> $biller_id,
						'customer_id' => $this->input->post('customers'),
						'sale_id' => $this->input->post('customer_no'),
                        'created_by' => $this->session->userdata('user_id'),
                        'updated_by' => $this->session->userdata('user_id'),
						);
				if($amount_tax > 0) {
					$data[] =  array(
							'tran_type' 	=> 'PURCHASE EXPENSE',
							'tran_no' 		=> $ac_old_transno,
							'account_code' 	=> $ac_tax,
							'tran_date' 	=> $date,
							'reference_no' 	=> $reference_no,
							'description'	=> $description,
							'amount' 		=> $amount_tax,
							'biller_id' 	=> $biller_id,
							'customer_id' => $this->input->post('customers'),
							'sale_id' => $this->input->post('customer_no'),
                            'created_by' => $this->session->userdata('user_id'),
                            'updated_by' => $this->session->userdata('user_id'),
							);
				}

				for($i=0;$i<count($account_code);$i++) {

					if($debit[$i]>0) {
							$amount  = $debit[$i];
							$total  += $debit[$i];
					}

					if($tran_id[$i] != 0){
						$data[] = array(
							'tran_type' => 'PURCHASE EXPENSE',
							'tran_no' => $ac_old_transno,
							'tran_id' => $tran_id[$i],
							'account_code' => $account_code[$i],
							'tran_date' => $date,
							'reference_no' => $reference_no,
							'description' => $description,
							'amount' => $amount,
							'biller_id' => $biller_id,
							'customer_id' => $this->input->post('customers'),
							'sale_id' => $this->input->post('customer_no'),
                            'created_by' => $this->session->userdata('user_id'),
                            'updated_by' => $this->session->userdata('user_id'),
							);
					}else{
						$data[] = array(
							'tran_type' => 'PURCHASE EXPENSE',
							'tran_no' => $ac_old_transno,
							'account_code' => $account_code[$i],
							'tran_date' => $date,
							'reference_no' => $reference_no,
							'description' => $description,
							'amount' => $amount,
							'biller_id' => $biller_id,
							'customer_id' => $this->input->post('customers'),
							'sale_id' => $this->input->post('customer_no'),
                            'created_by' => $this->session->userdata('user_id'),
                            'updated_by' => $this->session->userdata('user_id'),
							);
					}
				}

				$this->purchases_model->updateJournal($reference_no, $data);

				if ($this->Settings->tax2 != 0) {
					$order_tax_id       = $this->input->post('order_tax');
                    $product_tax        = null;
                    $order_discount     = null;
                    $product_discount   = null;
                    $order_discount_id  = null;
                    $total_discount     = null;
					if ($order_tax_details = $this->site->getTaxRateByID($order_tax_id)) {
						if ($order_tax_details->type == 2) {
							$order_tax = ($order_tax_details->rate);
						}
						if ($order_tax_details->type == 1) {
							$order_tax = ((($total + $product_tax + $shipping - $order_discount) * $order_tax_details->rate) / 100);
						}
					}
				} else {
					$order_tax_id = null;
				}

				$total_tax = ($product_tax + $order_tax);
				$grand_total = ($total + $total_tax + $shipping - $order_discount);
				if($payment_status =="pending" || $payment_status =="due"){
					$paid_by = '';
				}
				$data = array(
					'biller_id'   		=> $biller_id,
					'reference_no' 		=> $reference,
					'payment_term' 		=> $payment_term,
					'supplier_id' 		=> $supplier_id,
					'supplier' 			=> $supplier,
					'warehouse_id'		=> $warehouse_id,
					'note' 				=> $note,
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
					'status' 			=> $status,
					//'payment_status' 	=> $payment_status,
					'updated_by' 		=> $this->session->userdata('user_id'),
					'updated_at' 		=> date('Y-m-d H:i:s'),
					'paid_by' 			=> $paid_by,
					'account_code' 		=> $this->input->post('bank_account'),
					'pur_refer' 		=> $this->input->post('payment_reference_no'),
					'customer_id'		=> $this->input->post('customers'),
					'sale_id'			=> $this->input->post('customer_no'),
					'quote_id'			=> $this->input->post('quote_id')

				);

			}

            if ($date) {
                $data['date'] = $date;
            }

            if ($_FILES['document']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path']  	= $this->digital_upload_path;
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
                $data['attachment'] = $photo;
            }

            if ($payment_status == 'partial' || $payment_status == 'paid') {
                if ($this->input->post('paid_by') == 'gift_card') {
                    $gc = $this->site->getGiftCardByNO($this->input->post('gift_card_no'));
                    $amount_paying = $grand_total >= $gc->balance ? $gc->balance : $grand_total;
                    $gc_balance = $gc->balance - $amount_paying;

                    $payment = array(
                        'date' 			=> $date,
                        'reference_no' 	=> $this->input->post('payment_reference_no'),
                        'amount' 		=> $this->erp->formatDecimal($amount_paying),
                        'paid_by' 		=> $this->input->post('paid_by'),
                        'cheque_no' 	=> $this->input->post('cheque_no'),
                        'cc_no' 		=> $this->input->post('gift_card_no'),
                        'cc_holder' 	=> $this->input->post('pcc_holder'),
                        'cc_month' 		=> $this->input->post('pcc_month'),
                        'cc_year' 		=> $this->input->post('pcc_year'),
                        'cc_type' 		=> $this->input->post('pcc_type'),
                        'created_by' 	=> $this->session->userdata('user_id'),
                        'note' 			=> $this->input->post('payment_note'),
                        'type' 			=> 'received',
                        'gc_balance' 	=> $gc_balance,
                        'biller_id' 	=> $biller_id,
						'bank_account'	=> $this->input->post('bank_account')
                    );
                    $data['paid'] = $this->erp->formatDecimal($amount_paying);
                }else{
					 $payment = array(
                        'date' 			=> $date,
                        'reference_no' 	=> $this->input->post('payment_reference_no'),
                        'amount' 		=> $this->erp->formatDecimal($this->input->post('amount-paid')),
                        'paid_by' 		=> $this->input->post('paid_by'),
                        'cheque_no' 	=> $this->input->post('cheque_no'),
                        'cc_no' 		=> $this->input->post('pcc_no'),
                        'cc_holder' 	=> $this->input->post('pcc_holder'),
                        'cc_month' 		=> $this->input->post('pcc_month'),
                        'cc_year' 		=> $this->input->post('pcc_year'),
                        'cc_type' 		=> $this->input->post('pcc_type'),
                        'created_by' 	=> $this->session->userdata('user_id'),
                        'note' 			=> $this->input->post('payment_note'),
                        'type' 			=> 'received',
                        'biller_id' 	=> $biller_id,
						'bank_account'	=> $this->input->post('bank_account')
                    );
                    $data['paid'] 		= $this->erp->formatDecimal($this->input->post('amount-paid'));
				}
                if($_POST['paid_by'] == 'depreciation') {
                    $no = sizeof($_POST['no']);
                    $period = 1;
                    for($m = 0; $m < $no; $m++){
                        $dateline = date('Y-m-d', strtotime($_POST['dateline'][$m]));
                        $loans[] = array(
                            'period' 	=> $period,
                            'sale_id' 	=> '',
                            'interest' 	=> $_POST['interest'][$m],
                            'principle' => $_POST['principle'][$m],
                            'payment' 	=> $_POST['payment_amt'][$m],
                            'balance' 	=> $_POST['balance'][$m],
                            'type' 		=> $_POST['depreciation_type'],
                            'rated' 	=> $_POST['depreciation_rate1'],
                            'note' 		=> $_POST['note_1'][$m],
                            'dateline' 	=> $dateline,
                            'biller_id' => $biller_id
                        );
                        $period++;
                    }
                    //$this->erp->print_arrays($loans);
                }else{
                    $loans = array();
                }

            } else {
                $payment = array();
            }

			if($id){
				$o_purchase = $this->purchases_model->getPurchaseByID($id);
				if($data['grand_total'] < $o_purchase->paid){
					$this->session->set_flashdata('error', lang("grand_total_less_than_paid"));
					redirect($_SERVER["HTTP_REFERER"]);
				}
			}
			//$this->erp->print_arrays($data, $products);
        }

        if ($this->form_validation->run() == true && $this->purchases_model->updatePurchase($id, $data, $products, $payment,$purid,$amount_o,$paid_o)) {
			/*
			$update_stock = array ('quantity'=>$balance_qty);
			$test= $this->db->where('code',$item_code)->update('products',$update_stock); //kiry
			*/
            optimizePurchases(date('Y-m-d', strtotime($date)));
            $this->session->set_userdata('remove_pols', '1');
            $this->session->set_flashdata('message', $this->lang->line("purchase_added"));
            redirect('purchases');
        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
			$inv = $this->purchases_model->getPurchaseByID($id);
			$this->data['sectionacc'] = array();
			$this->data['invoices']	  = 0;

			$this->load->model('sales_model');

			if($inv->type_of_po=='po')
			{
				$this->data['inv'] = $this->purchases_model->getPurchaseByID($id);

				if ($this->session->userdata('biller_id')) {
                    $biller_id = $this->session->userdata('biller_id');
                } else {
                    $biller_id = $this->Settings->default_biller;
                }

				$ref = $this->site->getReference('pp', $biller_id);
				$pref = $this->purchases_model->getPaymentByPurchaseID($id);
				$this->data['payment_ref'] = $pref?$pref->reference_no:$ref;

				if ($this->data['inv']->date <= date('Y-m-d', strtotime('-3 months'))) {
					$this->session->set_flashdata('error', lang("purchase_x_edited_older_than_3_months"));
					redirect($_SERVER["HTTP_REFERER"]);
				}

				$inv_items = $this->purchases_model->getAllPurchaseItems($id);

				$c = rand(100000, 9999999);
                if (is_array($inv_items)) {
    				foreach ($inv_items as $item) {

    					$row 					= $this->site->getProductByID($item->product_id);
    					$row->expiry 			= (($item->expiry && $item->expiry != '0000-00-00') ? $this->erp->hrsd($item->expiry) : '');

						$row->qty 				= $item->quantity;
    					$row->received 			= $item->quantity_received;//$item->quantity_received ? $item->quantity_received : $item->quantity;
    					$row->quantity_balance 	= $item->quantity_balance + ($item->quantity-$row->received);
    					$row->discount 			= $item->discount ? $item->discount : '0';
    					$options 				= $this->purchases_model->getProductOptions($row->id);
    					$row->option 			= $item->option_id;
    					$row->real_unit_cost 	= $item->real_unit_cost;
    					$row->cost 				= $this->erp->formatPurDecimal($item->net_unit_cost + ($item->item_discount / $item->quantity));
    					$row->tax_rate 			= $item->tax_rate_id;
    					$row->net_cost 			= $item->unit_cost;
						$row->tax_method 		= $item->tax_method;
						$row->piece				= $item->piece;
						$row->wpiece 			= $item->wpiece;

    					$test = $this->sales_model->getWP2($item->product_id, $item->warehouse_id);
    					$row->quantity = $test->quantity;
    					$pii = $this->purchases_model->getPurcahseItemByPurchaseID($id);

    					unset($row->details, $row->product_details, $row->file, $row->product_group_id);
    					$ri = $this->Settings->item_addition ? $row->id : $c;
    					if ($row->tax_rate) {
    						$tax_rate = $this->site->getTaxRateByID($row->tax_rate);
    						$pr[$c] = array('id' => $c, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'tax_rate' => $tax_rate, 'options' => $options, 'suppliers' => '' ,'supplier_id' => $item->supplier_id,'create_order_id'=>$item->create_id,'purid'=>$item->id);
    					} else {
    						$pr[$c] = array('id' => $c, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'tax_rate' => false, 'options' => $options, 'suppliers' => '' ,'supplier_id' => $item->supplier_id,'create_order_id'=>$item->create_id,'purid'=>$item->id);
    					}
    					$c++;
                    }
				    $this->data['inv_items'] = json_encode($pr);

                }

			}else{

				$this->data['inv'] = $this->purchases_model->getPurchaseByID($id);
				$chart_acc_details = $this->purchases_model->getAllChartAccount();

				foreach($chart_acc_details as $chart) {
					$section_id = $chart->sectionid;
				}


				$Transno = $this->purchases_model->getTranNoExp($id);

				$this->data['type'] 		= $this->purchases_model->getAlltypes();
				$this->data['supplier'] 	= $chart_acc_details;
				$this->data['sectionacc'] 	= $chart_acc_details;
				$this->data['journals'] 	= $this->purchases_model->getJournalByTranNo($Transno->tran_no);
				$this->data['subacc'] 		= $this->purchases_model->getSubAccounts($section_id);
			}

			$this->data['quote_id'] 		= $inv->quote_id;
            $this->data['id'] 				= $id;
			$this->data['edit_status'] 		= $id;
            $this->data['suppliers'] 		= $this->site->getAllCompanies('supplier');
            $this->data['purchase'] 		= $this->purchases_model->getPurchaseByID($id);
			$this->data['payment_ref'] 		= $this->site->getReference('pp', $biller_id);
            $this->data['categories'] 		= $this->site->getAllCategories();
			$this->data['unit'] 			= $this->purchases_model->getUnits();
            $this->data['tax_rates'] 		= $this->site->getAllTaxRates();
            $this->data['warehouses'] 		= $this->site->getAllWarehouses();
			$this->data['payment_term'] 	= $this->site->getAllPaymentTerm();
			$this->data['billers'] 			= $this->site->getAllCompanies('biller');
			$this->data['bankAccounts_1'] 	=  $this->site->getAllBankAccounts();
			$this->data['customers'] 		= $this->site->getCustomers();
			$this->data['invoices'] 		= $this->site->getCustomerInvoices();
			$this->data['acc_setting'] 		= $this->site->get_Acc_setting();
			$this->data['currency'] 		= $this->site->getCurrency();
            $this->load->helper('string');
            $value 							= random_string('alnum', 20);
            $this->session->set_userdata('user_csrf', $value);
             $this->session->set_userdata('remove_pols', '1');
            $this->data['csrf'] = $this->session->userdata('user_csrf');
            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('purchases'), 'page' => lang('purchases')), array('link' => '#', 'page' => lang('edit_purchase')));
            $meta = array('page_title' => lang('edit_purchase'), 'bc' => $bc);
            $this->page_construct('purchases/edit', $meta, $this->data);
        }
    }

    public function edit_opening_ap($id = null)
    {
        $this->erp->checkPermissions('edit',null,'purchases');

        $setting = $this->site->get_setting();
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        $this->form_validation->set_rules('reference_no', lang("reference_no"), 'required');

        $this->session->unset_userdata('csrf_token');
        if ($this->form_validation->run() == true) {
            if ($this->Owner || $this->Admin) {
                $date = $this->erp->fld(trim($this->input->post('date')));
            } else {
                $date = null;
            }

            $reference      = $this->input->post('reference_no');
            $biller_id = $this->input->post('biller');
            $supplier_id = $this->input->post('supplier');
            $supplier_details = $this->site->getCompanyByID($supplier_id);
            $supplier = $supplier_details->company != '-'  ? $supplier_details->company : $supplier_details->name;
            $balance = $this->input->post('balance');
            $payment_term   = $this->input->post('payment_term');

            $data = array(
                'date'              => date('Y-m-d H:i:s', strtotime($date)),
                'reference_no'      => $reference,
                'biller_id'         => $biller_id,
                'supplier_id'       => $supplier_id,
                'supplier'          => $supplier,
                'total'             => $balance,
                'grand_total'       => $balance,
                'payment_term'      => $payment_term
            );

            if ($date) {
                $data['date'] = $date;
            }

        }

        if ($this->form_validation->run() == true && $this->purchases_model->updatePurchaseOpeningAP($id, $data)) {
            $this->session->set_userdata('remove_pols', '1');
            $this->session->set_flashdata('message', $this->lang->line("purchase_added"));
            redirect('purchases');
        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

            $this->data['inv'] = $this->purchases_model->getPurchaseByID($id);

            $this->data['id'] = $id;
            $this->data['purchase'] = $this->purchases_model->getPurchaseByID($id);
            $this->data['billers'] = $this->site->getAllCompanies('biller');
            $this->data['suppliers'] = $this->site->getAllCompanies('supplier');
            // $this->erp->print_arrays($this->data['suppliers']);exit;
            $this->data['payment_term'] = $this->site->getAllPaymentTerm();

            $this->load->view($this->theme . 'purchases/opening_ap_modal_view', $this->data);
        }

    }

	//------------------- Purchases export as Excel and pdf -----------------------
	function getPurchasesAll($pdf = NULL, $excel = NULL)
    {
        $this->erp->checkPermissions('Purchases');

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
			$this->db->where('sales.reference_no NOT LIKE "SALE/POS%"');
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
                $this->excel->getActiveSheet()->setTitle(lang('Sales List'));
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

    /* --------------------------------------------------------------------------------------------- */

	public function purchase_by_csv()
    {
        $this->erp->checkPermissions('import', NULL, 'purchases');
        $this->load->helper('security');
        $this->form_validation->set_message('is_natural_no_zero', $this->lang->line("no_zero_required"));
        $this->form_validation->set_rules('userfile', $this->lang->line("upload_file"), 'xss_clean');

        if ($this->form_validation->run() == true) {
            //$this->erp->print_arrays("purchase_by_csv");
            $quantity 			= "quantity";
            $product 			= "product";
            $unit_cost 			= "unit_cost";
            $tax_rate 			= "tax_rate";
			$total 				= 0;
            $product_tax 		= 0;
            $order_tax 			= 0;
            $product_discount 	= 0;
            $order_discount 	= 0;
            $percentage 		= '%';

            if (isset($_FILES["userfile"])) {

                $this->load->library('upload');

                $config['upload_path'] = $this->digital_upload_path;
                $config['allowed_types'] = 'csv';
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = true;

                $this->upload->initialize($config);

                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect("purchases/purchase_by_csv");
                }

                $csv 		= $this->upload->file_name;

                $arrResult 	= array();
                $handle 	= fopen($this->digital_upload_path . $csv, "r");
                if ($handle) {
                    while (($row = fgetcsv($handle, 1000, ",")) !== false) {
                        $arrResult[] = $row;
                    }
                    fclose($handle);
                }
                $titles 	= array_shift($arrResult);

				$keys 		= array('date', 'reference_no', 'biller_code', 'supplier_code', 'warehouse_code', 'code', 'expiry', 'net_unit_cost', 'quantity', 'variant_id', 'product_discount', 'product_tax', 'order_discount', 'shipping', 'order_tax', 'payment_term');

                $final 		= array();
                foreach ($arrResult as $key => $value) {
                    $final[] = array_combine($keys, $value);
                }

                $rw 						= 2;
                $date 						= '';
                $reference 					= '';
                $supplier_id 				= '';
				$purchase_item_supplier_id 	= '';
                $biller_id 					= '';
                $status 					= '';
                $payment_term 				= '';
                $payment_status 			= '';
                $shipping 					= '';
                $order_discount 			= '';
                $order_tax 					= '';
                $supplier 					= '';
                $biller 					= '';
                $products 					= array();
                $pr_tax 					= '';
                $old_reference 				= '';
				$warehouse_code 			= '';
				$percentage 				= '%';
				$stotal 					= '%';
				$items						= array();

                foreach ($final as $csv_pr) {
                    if (!empty($csv_pr['code']) && !empty($csv_pr['quantity'])) {
						$product_code = rawurldecode($csv_pr['code']);
                        if ($product_details = $this->purchases_model->getProductByCode(trim($csv_pr['code']))) {

							$total 				= 0;
							$date 				= date('Y-m-d H:m:i', strtotime($csv_pr['date']));
							$reference 			= $csv_pr['reference_no'];

							$biller_details		= $this->site->getCompanyByCode($csv_pr['biller_code'], 'biller');
							$biller 			= $biller_details->company != '-' ? $biller_details->company : $biller_details->name;

							$supplier_details	= $this->site->getCompanyByCode($csv_pr['supplier_code'], 'supplier');
							$supplier 			= $supplier_details->company != '-'  ? $supplier_details->company : $supplier_details->name;

							$warehouse_code 	= $csv_pr['warehouse_code'];
							$warehouse_id 		= $this->purchases_model->getWarehouseIDByCode(trim($csv_pr['warehouse_code']));
							$item_code 			= $csv_pr['code'];
							$item_expiry 		= date('Y-m-d', strtotime($csv_pr['expiry']));
							$unit_cost 			= $csv_pr['net_unit_cost'];
							$item_quantity 		= $csv_pr['quantity'];
							$variant 			= $this->site->getVariantsById($csv_pr['variant_id']);
							$variants 			= $this->site->getProductVariantByID($product_details->id, $variant->name);
							$quantity_balance 	= $item_quantity;
							if ($variants) {
								$quantity_balance 	= $item_quantity * $variants->qty_unit;
							}

							$item_discount		= $csv_pr['product_discount'];
							if (isset($item_discount)) {
								$discount = $item_discount;
								$dpos = strpos($discount, $percentage);
								if ($dpos !== false) {
									$pds = explode("%", $discount);
									$pr_discount = ((($unit_cost) * (Float) ($pds[0])) / 100);
								} else {
									$pr_discount = ($discount/ $item_quantity);
								}
							}
							$unit_cost 			= ($unit_cost - $pr_discount);
							$pr_item_discount 	= $this->erp->formatDecimal($pr_discount * $item_quantity);
							$product_discount 	+= $pr_item_discount;

							$payment_term 		= $csv_pr['payment_term'];
							$status 			= 'received';

							$item_tax_rate		= $csv_pr['product_tax'];
							$pr_tax 			= 0;
							$pr_item_tax 		= 0;
							$item_tax 			= 0;
							$cogs_inv 			= 0;
							$tax 				= "";
							$net_unit_cost		= "";
							$ptax_method 		= $product_details->tax_method;
							if ($item_tax_rate) {
								$tax_details 	= $this->site->getTaxRateByCode($item_tax_rate);
								$pr_tax 		= $tax_details->id;
								if ($tax_details->type == 1 && $tax_details->rate != 0) {

									if ($product_details && $ptax_method == 1) {
										$item_tax 		= ($unit_cost * $tax_details->rate) / 100;
										$tax 			= $tax_details->rate . "%";
										$item_net_cost 	= $unit_cost + $item_tax;
										$net_unit_cost  = $unit_cost;
									} else {
										$item_tax 		= ($unit_cost * $tax_details->rate) / (100 + $tax_details->rate);
										$tax 			= $tax_details->rate . "%";
										$item_net_cost 	= $unit_cost;
										$net_unit_cost  = $unit_cost - $item_tax;
									}

								} elseif ($tax_details->type == 2) {

									if ($product_details && $ptax_method == 1) {
										$item_tax 		= ($unit_cost * $tax_details->rate) / 100;
										$tax 			= $tax_details->rate . "%";
										$item_net_cost 	= $unit_cost + $item_tax;
										$net_unit_cost  = $unit_cost;
									} else {
										$item_tax		= ($unit_cost * $tax_details->rate) / (100 + $tax_details->rate);
										$tax 			= $tax_details->rate . "%";
										$item_net_cost 	= $unit_cost;
										$net_unit_cost  = $unit_cost - $item_tax;
									}

									$item_tax = ($tax_details->rate);
									$tax = $tax_details->rate;
								}

								$pr_item_tax = ($item_tax * $item_quantity);

							}
							$product_tax += $pr_item_tax;

							$subtotal 	= ($item_net_cost * $item_quantity);
							$products = array(
								'product_id' 		=> $product_details->id,
								'product_code' 		=> $item_code,
								'product_name' 		=> $product_details->name,
								'option_id' 		=> $variants->id,
								'net_unit_cost' 	=> $net_unit_cost,
								'unit_cost' 		=> $csv_pr['net_unit_cost'],
								'quantity' 			=> $item_quantity,
								'quantity_balance' 	=> $quantity_balance,
								'warehouse_id' 		=> $warehouse_id,
								'tax_method'		=> $ptax_method,
								'item_tax' 			=> $pr_item_tax,
								'tax_rate_id' 		=> $pr_tax,
								'tax' 				=> $tax,
								'discount' 			=> $item_discount,
								'item_discount' 	=> $pr_item_discount,
								'subtotal' 			=> $subtotal,
								'expiry' 			=> $item_expiry,
								'real_unit_cost' 	=> $item_net_cost,
								'date' 				=> date('Y-m-d', strtotime($date)),
								'status' 			=> $status,
								'transaction_type' 	=> 'PURCHASE',
								'cb_avg'			=> $product_details->cost,
								'cb_qty'			=> $product_details->quantity,
								'type'				=> $product_details->type
							);

							$total += $subtotal;
							if($product_details->type != 'service') {
								$stotal += ($subtotal);
							}

							$shipping 			= $csv_pr['shipping'];

							$data[] = array(
								'reference_no' 		=> $reference,
								'date' 				=> $date,
								'biller_id' 		=> $biller_details->id,
								'supplier_id' 		=> $supplier_details->id,
								'supplier' 			=> $supplier,
								'total' 			=> $total,
								'order_discount_id' => $csv_pr['order_discount'],
								'product_discount'  => $product_discount,
								'order_tax_id' 		=> $csv_pr['order_tax'],
								'product_tax' 		=> $product_tax,
								'shipping' 			=> $shipping,
								'payment_term'      => $payment_term,
								'status' 			=> $status,
								'created_by' 		=> $this->session->userdata('user_id'),
								'warehouse_id' 		=> $warehouse_id,
								'type_of_po' 		=> 'po',
								'stotal'			=> $stotal
							);

							if(isset($items[$reference])){
								$items[$reference][] 	= $products;
							}else{
								$items[$reference] 		= array($products);
							}

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

                        } else {
                            $this->session->set_flashdata('error', $this->lang->line("pr_not_found") . " ( " . $csv_pr['code'] . " ). " . $this->lang->line("line_no") . " " . $rw);
                            redirect($_SERVER["HTTP_REFERER"]);
                        }
                        $rw++;
                    }
                }

				$out  = array();
				foreach ($data as $key => $value){
					if (array_key_exists($value['reference_no'], $out)){
						$out[$value['reference_no']]['reference_no'] 		= $value['reference_no'];
						$out[$value['reference_no']]['date'] 		    	= $value['date'];
						$out[$value['reference_no']]['biller_id'] 			= $value['biller_id'];
						$out[$value['reference_no']]['supplier_id'] 		= $value['supplier_id'];
						$out[$value['reference_no']]['supplier'] 		  	= $value['supplier'];
						$out[$value['reference_no']]['total'] 			  	+= $value['total'];
						$out[$value['reference_no']]['order_discount_id'] 	= $value['order_discount_id'];
						$out[$value['reference_no']]['product_discount'] 	= $value['product_discount'];
						$out[$value['reference_no']]['order_tax_id'] 		= $value['order_tax_id'];
						$out[$value['reference_no']]['product_tax'] 		= $value['product_tax'];
						$out[$value['reference_no']]['shipping'] 			= $value['shipping'];
						$out[$value['reference_no']]['payment_term'] 		= $value['payment_term'];
						$out[$value['reference_no']]['status'] 				= $value['status'];
						$out[$value['reference_no']]['created_by'] 			= $value['created_by'];
						$out[$value['reference_no']]['warehouse_id'] 		= $value['warehouse_id'];
						$out[$value['reference_no']]['type_of_po'] 			= $value['type_of_po'];
						$out[$value['reference_no']]['stotal'] 				= $value['stotal'];

					} else {
						$out[$value['reference_no']] = array(
							'reference_no' 		=> $value['reference_no'],
							'date' 		    	=> $value['date'],
							'biller_id' 		=> $value['biller_id'],
							'supplier_id' 		=> $value['supplier_id'],
							'supplier' 		  	=> $value['supplier'],
							'total'			  	=> $value['total'],
							'order_discount_id' => $value['order_discount_id'],
							'product_discount'  => $value['product_discount'],
							'order_tax_id' 		=> $value['order_tax_id'],
							'product_tax' 		=> $value['product_tax'],
							'shipping' 			=> $value['shipping'],
							'payment_term' 		=> $value['payment_term'] ,
							'status'			=> $value['status'],
							'created_by' 		=> $value['created_by'],
							'warehouse_id' 		=> $value['warehouse_id'],
							'type_of_po' 		=> $value['type_of_po'],
							'stotal'			=> $value['stotal']
						);
					}
				}

				$pur = array_values($out);
				foreach($pur as $inv){

					//===========  Validate Reference  ============//
					if($this->purchases_model->getPurchaseByRef($inv['reference_no'])){
						$this->session->set_flashdata('error', $this->lang->line("reference_no_exist"));
						redirect("purchases/purchase_by_csv");
					}
					//==================== End ====================//

					if ($inv['order_discount_id']) {
						$order_discount_id = $inv['order_discount_id'];
						$opos = strpos($order_discount_id, $percentage);
						if ($opos !== false) {
							$ods = explode("%", $order_discount_id);
							$order_discount = $this->erp->formatPurDecimal(($inv['total'] * (Float) ($ods[0])) / 100);
						} else {
							$order_discount = $this->erp->formatPurDecimal(($inv['total']*$this->erp->formatPurDecimal($order_discount_id))/100);
						}
					} else {
						$order_discount_id = null;
					}

					$total_discount = $this->erp->formatPurDecimal($order_discount + $inv['product_discount']);

					$order_tax_code 	= $inv['order_tax_id'];
					if ($this->Settings->tax2 != 0) {
						$order_tax_details = $this->site->getTaxRateByCode($order_tax_code);
						$order_tax_id = $order_tax_details->id;
						if ($order_tax_details) {
							if ($order_tax_details->type == 2) {
								$order_tax = $this->erp->formatPurDecimal($order_tax_details->rate);
							}
							if ($order_tax_details->type == 1) {
								$order_tax = $this->erp->formatPurDecimal((($inv['total'] + $inv['shipping'] - $order_discount) * $order_tax_details->rate) / 100);
							}
						}
					} else {
						$order_tax_id = null;
					}
					$total_tax 				= $this->erp->formatPurDecimal($inv['product_tax'] + $order_tax);
					$grand_total 		    = $this->erp->formatPurDecimal(($inv['total'] - $order_discount) + $order_tax + $inv['shipping']);
					$payment_term_details   = $this->site->getAllPaymentTermByID($inv['payment_term']);
					$due_date               = $payment_term_details[0]->id ? date('Y-m-d', strtotime($date . '+' . $payment_term_details[0]->due_day . ' days')) : NULL;
					$inv['order_discount'] 	= $order_discount;
					$inv['total_discount'] 	= $total_discount;
					$inv['order_tax'] 		= $order_tax;
					$inv['total_tax'] 		= $total_tax;
					$inv['payment_status'] 	= 'due';
					$inv['grand_total'] 	= $grand_total;
					$inv['order_tax_id'] 	= $order_tax_id;
					$inv['due_date'] 		= $due_date;

					$purchase[] = array(
						'data' => $inv,
						'item' => $items[$inv['reference_no'] ]
					);
				}


            }
        }
        //$this->erp->print_arrays($data,$products);

        if ($this->form_validation->run() == true && $this->purchases_model->addPurchaseImport($purchase)) {
            $this->session->set_flashdata('message', $this->lang->line("purchase_added"));
            redirect("purchases");
        } else {

            $data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

            if ($this->session->userdata('biller_id')) {
                $biller_id = $this->session->userdata('biller_id');
            } else {
                $biller_id = $this->Settings->default_biller;
            }

            $this->data['warehouses'] 	= $this->site->getAllWarehouses();
            $this->data['tax_rates'] 	= $this->site->getAllTaxRates();
            $this->data['ponumber'] 	= $this->site->getReference('po', $biller_id);

            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('purchases'), 'page' => lang('purchases')), array('link' => '#', 'page' => lang('import_purchase')));
            $meta = array('page_title' => lang('import_purchase'), 'bc' => $bc);
            $this->page_construct('purchases/purchase_by_csv', $meta, $this->data);
        }
    }

	public function payment_by_csv()
    {
        $this->erp->checkPermissions('import', NULL, 'purchases');
        $this->load->helper('security');
        $this->form_validation->set_message('is_natural_no_zero', $this->lang->line("no_zero_required"));
        $this->form_validation->set_rules('userfile', $this->lang->line("upload_file"), 'xss_clean');

        if ($this->form_validation->run() == true) {

            if (isset($_FILES["userfile"])) {

                $this->load->library('upload');

                $config['upload_path'] 		= $this->digital_upload_path;
                $config['allowed_types'] 	= 'csv';
                $config['max_size'] 		= $this->allowed_file_size;
                $config['overwrite'] 		= true;

                $this->upload->initialize($config);

                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect("purchases/payment_by_csv");
                }

                $csv 		= $this->upload->file_name;

                $arrResult 	= array();
                $handle 	= fopen($this->digital_upload_path . $csv, "r");
                if ($handle) {
                    while (($row = fgetcsv($handle, 1000, ",")) !== false) {
                        $arrResult[] = $row;
                    }
                    fclose($handle);
                }
                $titles 	= array_shift($arrResult);

				$keys 		= array('date', 'payment_ref', 'purchase_ref', 'amount', 'discount_id', 'paid_by', 'cheque_no', 'cc_no', 'cc_holder', 'cc_month', 'cc_year', 'cc_type', 'bank_account', 'note');

                $final 		= array();
                foreach ($arrResult as $key => $value) {
                    $final[] = array_combine($keys, $value);
                }

                foreach($final as $row){
					if(!$purchase = $this->purchases_model->getPurchaseByRef($row['purchase_ref'])) {
						$this->session->set_flashdata('error', 'Reference : '. $row['purchase_ref'] .'not found!');
						redirect("purchases/payment_by_csv");
					}

					$payment = array(
						'date' 			=> $this->erp->fld($row['date']),
						'purchase_id' 	=> $purchase->id,
						'reference_no' 	=> $row['payment_ref'],
						'amount' 		=> $row['amount'],
						'discount_id' 	=> $row['discount_id'],
						'discount' 		=> $row['discount_id'],
						'paid_by' 		=> $row['paid_by'],
						'cheque_no' 	=> $row['cheque_no'],
						'cc_no' 		=> $row['cc_no'],
						'cc_holder' 	=> $row['cc_holder'],
						'cc_month' 		=> $row['cc_month'],
						'cc_year' 		=> $row['cc_year'],
						'cc_type' 		=> $row['cc_type'],
						'note' 			=> $row['note'],
						'created_by' 	=> $this->session->userdata('user_id'),
						'type' 			=> 'sent',
						'biller_id'		=> $purchase->biller_id,
						'bank_account' 	=> $row['bank_account']
					);

					if ($_FILES['document']['size'] > 0) {
						$this->load->library('upload');
						$config['upload_path'] 		= $this->digital_upload_path;
						$config['allowed_types'] 	= $this->digital_file_types;
						$config['max_size'] 		= $this->allowed_file_size;
						$config['overwrite'] 		= FALSE;
						$config['encrypt_name'] 	= TRUE;
						$this->upload->initialize($config);
						if (!$this->upload->do_upload('document')) {
							$error = $this->upload->display_errors();
							$this->session->set_flashdata('error', $error);
							redirect($_SERVER["HTTP_REFERER"]);
						}
						$photo = $this->upload->file_name;
						$payment['attachment'] = $photo;
					}

					if($purchase->payment_status != 'paid') {
						if ($payment_id = $this->sales_model->addPayment($payment)) {
							if($payment_id > 0) {
								//add deposit
								if($row['paid_by'] == "deposit"){
									$deposits = array(
										'date' 			=> $this->erp->fld($row['date']),
										'reference' 	=> $row['payment_ref'],
										'company_id' 	=> $purchase->customer_id,
										'amount' 		=> (-1) * $row['amount'],
										'paid_by' 		=> $row['paid_by'],
										'note' 			=> $row['note'],
										'created_by' 	=> $this->session->userdata('user_id'),
										'biller_id' 	=> $purchase->biller_id,
										'purchase_id' 	=> $purchase->id,
										'payment_id' 	=> $payment_id,
										'status' 		=> 'paid'
									);
									$this->sales_model->add_deposit($deposits);
								}
							}
							$this->site->syncPurchasePayments($purchase->id);
						}
					}
				}

            }
        }

        if ($this->form_validation->run() == true) {
            $this->session->set_flashdata('message', $this->lang->line("payment_added"));
            redirect("purchases");
        } else {

            $data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

            if ($this->session->userdata('biller_id')) {
                $biller_id = $this->session->userdata('biller_id');
            } else {
                $biller_id = $this->Settings->default_biller;
            }

            $this->data['warehouses'] 	= $this->site->getAllWarehouses();
            $this->data['tax_rates'] 	= $this->site->getAllTaxRates();
            $this->data['ponumber'] 	= $this->site->getReference('po', $biller_id);

            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('purchases'), 'page' => lang('purchases')), array('link' => '#', 'page' => lang('add_payment_by_csv')));
            $meta = array('page_title' => lang('add_payment_by_csv'), 'bc' => $bc);
            $this->page_construct('purchases/payment_by_csv', $meta, $this->data);
        }
    }

    /* --------------------------------------------------------------------------- */

    public function delete($id = null)
    {
        $this->erp->checkPermissions('index',null,'purchases');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        if ($this->purchases_model->deletePurchase($id)) {
            if ($this->input->is_ajax_request()) {
                echo lang("purchase_deleted");die();
            }
            $this->session->set_flashdata('message', lang('purchase_deleted'));
            redirect('welcome');
        }
    }

    /* --------------------------------------------------------------------------- */

    public function suggestions()
    {
		$this->load->model('sales_model');
        $term = $this->input->get('term', true);
        $supplier_id = $this->input->get('supplier_id', true);
		$warehouse_id = $this->input->get('warehouse_id', true);

        if (strlen($term) < 1 || !$term) {
            die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . site_url('welcome') . "'; }, 10);</script>");
        }

        $spos = strpos($term, '_');
        if ($spos !== false) {
            $st 	= explode("_", $term);
            $sr 	= trim($st[0]);
            $opt_id = trim($st[1]);
        } else {
            $sr 	= $term;
            $opt_id = '';
        }
		$user_setting = $this->site->getUserSetting($this->session->userdata('user_id'));
        $rows = $this->purchases_model->getProductNames($sr, $user_setting->purchase_standard, $user_setting->purchase_combo, $user_setting->purchase_digital, $user_setting->purchase_service, $user_setting->purchase_category);

        if ($rows) {
            $c = str_replace(".", "", microtime(true));
            $r = 0;
            foreach ($rows as $row) {
                $option 				= false;
                $row->item_tax_method 	= $row->tax_method;
                $options 				= $this->purchases_model->getProductOptions($row->id);
				$row->real_cost 		= $row->cost;
                if ($options) {
					$opt = $options[count($options)-1];;
                    if (!$option) {
                        $option = $opt->id;
                    }
                } else {
                    $opt = json_decode('{}');
                    $opt->cost = 0;
                }
                if ($opt_id) {
					$row->option 		= $opt_id;
				} else {
					$row->option 		= $option;
				}
                if ($opt->cost != 0) {
                    $row->cost = $opt->cost;
                } else {
                    $row->cost = $row->cost;
                    if ($supplier_id == $row->supplier1 && (!empty($row->supplier1price)) && $row->supplier1price != 0) {


                        $row->cost = $row->supplier1price;

                    } elseif ($supplier_id == $row->supplier2 && (!empty($row->supplier2price)) && $row->supplier2price != 0) {


                        $row->cost = $row->supplier2price;

                    } elseif ($supplier_id == $row->supplier3 && (!empty($row->supplier3price)) && $row->supplier3price != 0) {


                        $row->cost = $row->supplier3price;

                    } elseif ($supplier_id == $row->supplier4 && (!empty($row->supplier4price)) && $row->supplier4price != 0) {


                        $row->cost = $row->supplier4price;

                    } elseif ($supplier_id == $row->supplier5 && (!empty($row->supplier5price)) && $row->supplier5price != 0) {


                        $row->cost = $row->supplier5price;

                    }
                }

                $row->real_unit_cost 	= $row->cost;
				$row->net_cost 			= $row->cost;
				$test = $this->sales_model->getWP2($row->id, $warehouse_id);
                $row->price = 0;
				if($test->quantity) {
					$row->quantity = $test->quantity;
				}else {
					$row->quantity = 0;
				}
				$row->piece		  	  	= 0;
				$row->wpiece	  	  	= $row->cf1;
                $row->expiry 			= '';
                $row->qty 				= 1;
				$row->pnote				= '';
                $row->quantity_balance 	= 0;
                $row->discount 			= 0;
				$row->received			= "";
                unset($row->details, $row->product_details, $row->file, $row->supplier1price, $row->supplier2price, $row->supplier3price, $row->supplier4price, $row->supplier5price);
                if ($row->tax_rate) {
                    $tax_rate = $this->site->getTaxRateByID($row->tax_rate);
                    $pr[] = array('id' => ($c + $r), 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'tax_rate' => $tax_rate, 'options' => $options);
                } else {
                    $pr[] = array('id' => ($c + $r), 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'tax_rate' => false, 'options' => $options);
                }
                $r++;
            }
			//$this->erp->print_arrays($pr);
            echo json_encode($pr);
        } else {
            echo json_encode(array(array('id' => 0, 'label' => lang('no_match_found'), 'value' => $term)));
        }
    }

	public function suggests()
    {
        $term = $this->input->get('term', true);
        $supplier_id = $this->input->get('supplier_id', true);
        $warehouse_id = $this->input->get('warehouse_id', true);

        if (strlen($term) < 1 || !$term) {
            die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . site_url('welcome') . "'; }, 10);</script>");
        }

        $spos = strpos($term, '_');
        if ($spos !== false) {
            $st = explode("_", $term);
            $sr = trim($st[0]);
            $opt_id = trim($st[1]);
        } else {
            $sr = $term;
            $opt_id = '';
        }

		$user_setting = $this->site->getUserSetting($this->session->userdata('user_id'));
        $rows = $this->purchases_model->getProductNumber($sr, $user_setting->purchase_standard, $user_setting->purchase_combo, $user_setting->purchase_digital, $user_setting->purchase_service, $user_setting->purchase_category);

        if ($rows) {
            $c = str_replace(".", "", microtime(true));
            $r = 0;
            foreach ($rows as $row) {
                $option = false;
                $row->item_tax_method = $row->tax_method;
                $options = $this->purchases_model->getProductOptions($row->id);
				$row->real_cost = $row->cost;

                if ($options) {
                    $opt = $options[count($options)-1];
                    if (!$option) {
                        $option = $opt->id;
                    }
                } else {
                    $opt = json_decode('{}');
                    $opt->cost = 0;
                }

				if ($opt_id) {
					$row->option 		= $opt_id;
				} else {
					$row->option 		= $option;
				}
                if ($opt->cost != 0) {
                    $row->cost = $opt->cost;
                } else {
                    $row->cost = $row->cost;
                    if ($supplier_id == $row->supplier1 && (!empty($row->supplier1price)) && $row->supplier1price != 0) {


                        $row->cost = $row->supplier1price;

                    } elseif ($supplier_id == $row->supplier2 && (!empty($row->supplier2price)) && $row->supplier2price != 0) {


                        $row->cost = $row->supplier2price;

                    } elseif ($supplier_id == $row->supplier3 && (!empty($row->supplier3price)) && $row->supplier3price != 0) {


                        $row->cost = $row->supplier3price;

                    } elseif ($supplier_id == $row->supplier4 && (!empty($row->supplier4price)) && $row->supplier4price != 0) {


                        $row->cost = $row->supplier4price;

                    } elseif ($supplier_id == $row->supplier5 && (!empty($row->supplier5price)) && $row->supplier5price != 0) {


                        $row->cost = $row->supplier5price;

                    }
                }
                $test = $this->sales_model->getWP2($row->id, $warehouse_id);
                if($test->quantity) {
                    $row->quantity = $test->quantity;
                }else {
                    $row->quantity = 0;
                }
				$row->net_cost 			= $row->cost;
                $row->real_unit_cost 	= $row->cost;
                $row->expiry 			= '';
                $row->qty 				= 1;
                $row->quantity_balance 	= '';
                $row->discount 			= 0;
                $row->price 			= 0;
                unset($row->details, $row->product_details, $row->file, $row->supplier1price, $row->supplier2price, $row->supplier3price, $row->supplier4price, $row->supplier5price);
                if ($row->tax_rate) {
                    $tax_rate = $this->site->getTaxRateByID($row->tax_rate);
                    $pr[] = array('id' => ($c + $r), 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'tax_rate' => $tax_rate, 'options' => $options);
                } else {
                    $pr[] = array('id' => ($c + $r), 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'tax_rate' => false, 'options' => $options);
                }
                $r++;
            }
			//$this->erp->print_arrays($pr);
            echo json_encode($pr);
        } else {
            echo json_encode(array(array('id' => 0, 'label' => lang('no_match_found'), 'value' => $term)));
        }
    }
	/* --------------------------------------------------------------------------------------------- */

	function getReferences($term = NULL, $limit = NULL)
    {
        // $this->erp->checkPermissions('index');
        if ($this->input->get('term')) {
            $term = $this->input->get('term', TRUE);
        }
        if (strlen($term) < 1) {
            return FALSE;
        }
        $limit = $this->input->get('limit', TRUE);

        $rows['results'] = $this->purchases_model->getPurchasesReferences($term, $limit);
        echo json_encode($rows);
    }

    public function purchase_actions($wh=null)
    {
        if($wh){
            $wh = explode('-', $wh);
        }
        $this->form_validation->set_rules('form_action', lang("form_action"), 'required');
        if ($this->form_validation->run() == true) {
            if (!empty($_POST['val'])) {
                if ($this->input->post('form_action') == 'delete') {

                    foreach ($_POST['val'] as $id) {
						$this->erp->checkPermissions('delete');
                        $this->purchases_model->deletePurchase($id);
                    }
                    $this->session->set_flashdata('message', $this->lang->line("purchases_deleted"));
                    redirect($_SERVER["HTTP_REFERER"]);

                } elseif ($this->input->post('form_action') == 'combine') {

                    $html = $this->combine_pdf($_POST['val']);

                } elseif ($this->input->post('form_action') == 'purchase_tax'){

					$ids = $_POST['val'];

					$this->data['modal_js'] = $this->site->modal_js();
					$this->data['ids'] = $ids;

				} elseif ($this->input->post('form_action') == 'export_excel' || $this->input->post('form_action') == 'export_pdf') {
                    if($this->Owner || $this->Admin){
					$this->erp->checkPermissions('export', true, 'purchases');
                    $this->load->library('excel');
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle(lang('purchases'));
					$this->excel->getActiveSheet()->SetCellValue('E1', lang('list_purchase'));
					$this->excel->getActiveSheet()->getStyle('E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$this->excel->getActiveSheet()->getStyle("E1")->getFont()->setSize(13);
                    $this->excel->getActiveSheet()->SetCellValue('A2', lang('date'));
                    $this->excel->getActiveSheet()->SetCellValue('B2', lang('pr_num'));
                    $this->excel->getActiveSheet()->SetCellValue('C2', lang('po_num'));
                    $this->excel->getActiveSheet()->SetCellValue('D2', lang('reference_no'));
                    $this->excel->getActiveSheet()->SetCellValue('E2', lang('supplier'));
                    $this->excel->getActiveSheet()->SetCellValue('F2', lang('status'));
                    $this->excel->getActiveSheet()->SetCellValue('G2', lang('grand_total'));
                    $this->excel->getActiveSheet()->SetCellValue('H2', lang('paid'));
                    $this->excel->getActiveSheet()->SetCellValue('I2', lang('balance'));
                    $this->excel->getActiveSheet()->SetCellValue('J2', lang('payment_status'));
                    $this->excel->getActiveSheet()->SetCellValue('K2', lang('note'));
                    $styleArray = array(
                        'font'  => array(
                            'bold'  => true
                        )
                    );

                    $this->excel->getActiveSheet()->getStyle('A1:K1')->applyFromArray($styleArray);
                    $row = 3;
                    $balance = $sum_grandTotal = $sum_paid = $sum_balance = 0;
                    foreach ($_POST['val'] as $id) {
                        $purchase        = $this->purchases_model->getPurchaseByID($id);
                        $balance         = $purchase->grand_total-$purchase->paid;
                        $sum_grandTotal += $purchase->grand_total;
                        $sum_paid       += $purchase->paid;
                        $sum_balance    += $balance;

                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $this->erp->hrld($purchase->date));
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $purchase->request_ref." ");
                        $this->excel->getActiveSheet()->SetCellValue('C' . $row, $purchase->order_ref." ");
                        $this->excel->getActiveSheet()->SetCellValue('D' . $row, $purchase->reference_no." ");
                        $this->excel->getActiveSheet()->SetCellValue('E' . $row, $purchase->name);
                        $this->excel->getActiveSheet()->SetCellValue('F' . $row, $purchase->status);
                        $this->excel->getActiveSheet()->SetCellValue('G' . $row, $this->erp->formatDecimal($purchase->grand_total));
                        $this->excel->getActiveSheet()->SetCellValue('H' . $row, $this->erp->formatDecimal($purchase->paid));
                        $this->excel->getActiveSheet()->SetCellValue('I' . $row, $this->erp->formatDecimal($balance));
                        $this->excel->getActiveSheet()->SetCellValue('J' . $row, $purchase->payment_status);
                        $this->excel->getActiveSheet()->SetCellValue('K' . $row, $this->erp->decode_html(strip_tags($purchase->note)));
                        $i = $row+1;
                        $this->excel->getActiveSheet()->SetCellValue('G' . $i, $this->erp->formatDecimal($sum_grandTotal));
                        $this->excel->getActiveSheet()->SetCellValue('H' . $i, $this->erp->formatDecimal($sum_paid));
                        $this->excel->getActiveSheet()->SetCellValue('I' . $i, $this->erp->formatDecimal($sum_balance));
                        $row++;
                    }
                }else{
                    $this->erp->checkPermissions('export', true, 'purchases');
                    $this->load->library('excel');
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle(lang('purchases'));
					$this->excel->getActiveSheet()->SetCellValue('E1', lang('list_purchase'));
					$this->excel->getActiveSheet()->getStyle('E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$this->excel->getActiveSheet()->getStyle("E1")->getFont()->setSize(13);
                    $this->excel->getActiveSheet()->SetCellValue('A2', lang('date'));
                    $this->excel->getActiveSheet()->SetCellValue('B2', lang('pr_num'));
                    $this->excel->getActiveSheet()->SetCellValue('C2', lang('po_num'));
                    $this->excel->getActiveSheet()->SetCellValue('D2', lang('reference_no'));
                    $this->excel->getActiveSheet()->SetCellValue('E2', lang('supplier'));
                    $this->excel->getActiveSheet()->SetCellValue('F2', lang('status'));
                    $this->excel->getActiveSheet()->SetCellValue('G2', lang('grand_total'));
                    $this->excel->getActiveSheet()->SetCellValue('H2', lang('paid'));
                    $this->excel->getActiveSheet()->SetCellValue('I2', lang('balance'));
                    $this->excel->getActiveSheet()->SetCellValue('J2', lang('payment_status'));
                    $this->excel->getActiveSheet()->SetCellValue('K2', lang('note'));
                    $styleArray = array(
                        'font'  => array(
                            'bold'  => true
                        )
                    );

                    $this->excel->getActiveSheet()->getStyle('A1:K1')->applyFromArray($styleArray);
                    $row = 3;
                    $balance = $sum_grandTotal = $sum_paid = $sum_balance = 0;
                    foreach ($_POST['val'] as $id) {
                        $purchase = $this->purchases_model->getPurchaseByID($id);
                        $balance = $purchase->grand_total-$purchase->paid;
                        $sum_grandTotal += $purchase->grand_total;
                        $sum_paid += $purchase->paid;
                        $sum_balance += $balance;

                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $this->erp->hrld($purchase->date));
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $purchase->request_ref." ");
                        $this->excel->getActiveSheet()->SetCellValue('C' . $row, $purchase->order_ref." ");
                        $this->excel->getActiveSheet()->SetCellValue('D' . $row, $purchase->reference_no." ");
                        $this->excel->getActiveSheet()->SetCellValue('E' . $row, $purchase->name);
                        $this->excel->getActiveSheet()->SetCellValue('F' . $row, $purchase->status);
                        $this->excel->getActiveSheet()->SetCellValue('G' . $row, $this->erp->formatDecimal($purchase->grand_total));
                        $this->excel->getActiveSheet()->SetCellValue('H' . $row, $this->erp->formatDecimal($purchase->paid));
                        $this->excel->getActiveSheet()->SetCellValue('I' . $row, $this->erp->formatDecimal($balance));
                        $this->excel->getActiveSheet()->SetCellValue('J' . $row, $purchase->payment_status);
                        $this->excel->getActiveSheet()->SetCellValue('K' . $row, $this->erp->decode_html(strip_tags($purchase->note)));
                        $i = $row+1;
                        $this->excel->getActiveSheet()->SetCellValue('G' . $i, $this->erp->formatDecimal($sum_grandTotal));
                        $this->excel->getActiveSheet()->SetCellValue('H' . $i, $this->erp->formatDecimal($sum_paid));
                        $this->excel->getActiveSheet()->SetCellValue('I' . $i, $this->erp->formatDecimal($sum_balance));
                        $row++;
                    }
                }

                    $this->excel->getActiveSheet()->getStyle('A2:K2')->applyFromArray($styleArray);
					$this->excel->getActiveSheet()->getStyle('A2:K2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
                    $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
                    $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
                    $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(10);
                    $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(20);

                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $filename = 'list_purchases_' . date('Y_m_d_H_i_s');
                    if ($this->input->post('form_action') == 'export_pdf') {
                        $styleArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
                        $this->excel->getDefaultStyle()->applyFromArray($styleArray);
						$this->excel->getActiveSheet()->getStyle("A" . $row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
						$this->excel->getActiveSheet()->getStyle('A2:K2')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

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

						//Add style bold text in case PDF
                        $this->excel->getActiveSheet()->getStyle('G'. $i. '')->getFont()->setBold(true);
                        $this->excel->getActiveSheet()->getStyle('H'. $i. '')->getFont()->setBold(true);
                        $this->excel->getActiveSheet()->getStyle('I'. $i. '')->getFont()->setBold(true);

                        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'PDF');
                        return $objWriter->save('php://output');
                    }
                    if ($this->input->post('form_action') == 'export_excel') {
                        header('Content-Type: application/vnd.ms-excel');
                        header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
                        header('Cache-Control: max-age=0');

						//apply style border top and bold text in case excel
                        $this->excel->getActiveSheet()->getStyle('G'.$i. '')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                        $this->excel->getActiveSheet()->getStyle('G'. $i. '')->getFont()->setBold(true);
                        $this->excel->getActiveSheet()->getStyle('H'.$i. '')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                        $this->excel->getActiveSheet()->getStyle('H'. $i. '')->getFont()->setBold(true);
                        $this->excel->getActiveSheet()->getStyle('I'.$i. '')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                        $this->excel->getActiveSheet()->getStyle('I'. $i. '')->getFont()->setBold(true);

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

    /* -------------------------------------------------------------------------------- */

    public function payments($id = null)
    {
        $this->erp->checkPermissions();
        $this->data['id'] = $id;
        $this->data['payments'] = $this->purchases_model->getPurchasePayments($id);
        $this->load->view($this->theme . 'purchases/payments', $this->data);
    }

    public function payment_note($pid = null, $id = null)
    {
        $curr_balance = $this->purchases_model->getPaymentAmountByPurID($pid, $id);
        $payment = $this->purchases_model->getPaymentByID($id);
        $inv = $this->purchases_model->getPurchaseByID($payment->purchase_id);
        $this->data['supplier'] = $this->site->getCompanyByID($inv->supplier_id);
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
        $this->data['inv'] = $inv;
        //$this->data['rows'] = $this->purchases_model->getAllPurchasePayment($payment->purchase_id);
        $this->data['rows'] = $this->purchases_model->getAllPaymentByReference_no($payment->reference_no);
        $this->data['curr_balance'] = $curr_balance;
        $this->data['payment'] = $payment;
        $this->data['page_title'] = $this->lang->line("payment_note");

        $this->load->view($this->theme . 'purchases/payment_note', $this->data);
    }

	function cash_receipt($id = NULL)
    {
		$this->erp->checkPermissions('index');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

		$this->load->model('pos_model');
		$this->data['pos'] = $this->pos_model->getSetting();

		$this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');

		$payment = $this->purchases_model->getPaymentByID($id);
        $inv = $this->purchases_model->getPurchaseByID($payment->purchase_id);
		$payments = $this->purchases_model->getCurrentBalance($id, $inv->id);

		$current_balance = $inv->grand_total;
		foreach($payments as $curr_pay) {
			if ($curr_pay->id < $id) {
				$current_balance -= $curr_pay->amount;
			}
		}

		$this->data['curr_balance'] = $current_balance;
		$this->data['supplier'] = $this->site->getCompanyByID($inv->supplier_id);
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
        $this->data['inv'] = $inv;
        $this->data['payment'] = $payment;
        $this->data['page_title'] = $this->lang->line("payment_note");
        $this->load->view($this->theme . 'sales/cash_receipt', $this->data);
    }

    public function add_payment($id = null)
    {
        $this->erp->checkPermissions('payments', NULL, 'purchases');
        $this->load->helper('security');
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        //$this->form_validation->set_rules('reference_no', lang("reference_no"), 'trim|required|is_unique[payments.reference_no]');
        $this->form_validation->set_rules('paid_by', lang("paid_by"), 'required');
        $this->form_validation->set_rules('userfile', lang("attachment"), 'xss_clean');
        if ($this->form_validation->run() == true) {
            if ($this->Owner || $this->Admin) {
                $date = $this->erp->fld(trim($this->input->post('date')));
            } else {
                $date = date('Y-m-d H:i:s');
            }
			$suppliers_id 	= $this->input->post('suppliers_id');
			$purchase_id = $this->input->post('purchase_id');
			$purchase = $this->purchases_model->getPurchaseByID($purchase_id);

			if($this->Settings->system_management == 'biller') {
				$biller_id = $this->input->post('biller');
			}else {
				$biller_id = $purchase->biller_id;
			}

			$reference_no_o = $this->input->post('reference_no_o');
			$paid_o 		= $this->input->post('paid_o');
			$amount_o 		= $this->input->post('amount_o');
			$paid 			= $this->input->post('paid_by');
			if($paid == "deposit"){
				$refer = $reference_no_o;
			}else{
				$refer = $this->input->post('reference_no') ? $this->input->post('reference_no') : $this->site->getReference('pp',$biller_id);
			}

            $payment = array(
                'date' 			=> $date,
				'biller_id' 	=> $biller_id,
                'purchase_id' 	=> $purchase_id,
                'reference_no' 	=> $refer,
                'amount' 		=> $this->input->post('amount-paid'),
				'discount' 		=> $this->input->post('discount'),
                'paid_by' 		=> $this->input->post('paid_by'),
                'cheque_no' 	=> $this->input->post('cheque_no'),
                'cc_no' 		=> $this->input->post('pcc_no'),
                'cc_holder' 	=> $this->input->post('pcc_holder'),
                'cc_month' 		=> $this->input->post('pcc_month'),
                'cc_year' 		=> $this->input->post('pcc_year'),
                'cc_type' 		=> $this->input->post('pcc_type'),
                'note' 			=> strip_tags($this->input->post('note')),
                'created_by' 	=> $this->session->userdata('user_id'),
				'bank_account' 	=> $this->input->post('bank_account'),
                'type' 			=> 'sent',
            );

            if ($_FILES['userfile']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->upload_path;
                $config['allowed_types'] = $this->digital_file_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = false;
                $config['encrypt_name'] = true;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                $payment['attachment'] = $photo;
            }
        } elseif ($this->input->post('add_payment')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }

        if ($this->input->post('add_payment') && $this->form_validation->run() == true && $this->purchases_model->addPayment($payment,$id,$suppliers_id,$reference_no_o,$paid_o,$amount_o)) {
            $this->session->set_flashdata('message', lang("payment_added"));
            redirect($_SERVER["HTTP_REFERER"]);
        } else {

            $this->data['error'] 			= (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $purchase 						= $this->purchases_model->getPurchaseByID($id);
            $this->data['inv'] 				= $purchase;

			if($this->Settings->system_management == 'biller') {

                if ($this->Owner || $this->Admin || !$this->session->userdata('biller_id')) {
                    $biller_id = $purchase->biller_id;
					$this->data['biller_id'] = $biller_id;
                    $test = $this->site->getReference('pp', $biller_id);
                    $this->data['payment_ref'] = $this->site->getReference('pp', $biller_id);
				} else {
                    $biller_id = $purchase->biller_id;
					$this->data['biller_id'] = $biller_id;
                    $this->data['payment_ref'] = $this->site->getReference('pp', $biller_id);
				}

			}else {
				$this->data['biller_id'] = $purchase->biller_id;
                $this->data['payment_ref'] = $this->site->getReference('pp', $purchase->biller_id);
			}

            $this->data['modal_js'] 		= $this->site->modal_js();
			$this->data['suppliers'] 		= $this->site->getSuppliers();
			$this->data['bankAccounts'] 	=  $this->site->getAllBankAccounts();
            $this->data['userBankAccounts'] =  $this->site->getAllBankAccountsByUserID();
            $this->load->view($this->theme . 'purchases/add_payment', $this->data);
        }
    }

	public function combine_payment($id = null)
    {
        $this->erp->checkPermissions('payments', NULL, 'purchases');
        $this->load->helper('security');
        $arr = array();
        if ($this->input->get('data'))
        {
            $arr = explode(',', $this->input->get('data'));
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
			$photo = 0;
			if ($_FILES['userfile']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->upload_path;
                $config['allowed_types'] = $this->digital_file_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = false;
                $config['encrypt_name'] = true;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                //$payment['attachment'] = $photo;
            }
			$biller_id = $this->input->post('biller');
			$purchase_id_arr = $this->input->post('purchase_id');
			$amount_paid_arr = $this->input->post('amount_paid_line');
			$i = 0;
			$reference_no = $this->input->post('reference_no') ? $this->input->post('reference_no') : $this->site->getReference('pp');
			foreach($purchase_id_arr as $purchase_id){
				$payment = array(
					'date' => $date,
					'purchase_id' => $purchase_id,
					'reference_no' => $reference_no,
					'biller_id' => $biller_id,
					'amount' => $amount_paid_arr[$i],
					'paid_by' => $this->input->post('paid_by'),
					'cheque_no' => $this->input->post('cheque_no'),
					'cc_no' => $this->input->post('pcc_no'),
					'cc_holder' => $this->input->post('pcc_holder'),
					'cc_month' => $this->input->post('pcc_month'),
					'cc_year' => $this->input->post('pcc_year'),
					'cc_type' => $this->input->post('pcc_type'),
					'note' => $this->erp->clear_tags($this->input->post('note')),
					'created_by' => $this->session->userdata('user_id'),
					'type' => 'sent',
					'attachment' => $photo,
					'bank_account' => $this->input->post('bank_account')
				);

				$this->purchases_model->addPaymentMulti($payment);
				$i++;
			}

			if ($this->site->getReference('sp', $biller_id) == $reference_no) {
				$this->site->updateReference('sp', $biller_id);
			}
            $this->session->set_flashdata('message', lang("payment_added"));
            redirect($_SERVER["HTTP_REFERER"]);

        }else {

			$setting = $this->site->get_setting();
			if($this->session->userdata('biller_id')) {
				$biller_id = $this->session->userdata('biller_id');
			}else {
				$biller_id = $setting->default_biller;
			}

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $purchase = $this->purchases_model->getCombinePaymentById($arr);
            $this->data['combine_purchases'] = $purchase;
            $this->data['payment_ref'] = $this->site->getReference('pp', $biller_id);
			$this->data['billers'] = $this->site->getAllCompanies('biller');
			$this->data['bankAccounts'] =  $this->site->getAllBankAccounts();
			$this->data['setting'] = $setting;
            $this->data['modal_js'] = $this->site->modal_js();

            $this->load->view($this->theme . 'purchases/combine_payment', $this->data);
        }
    }

    public function edit_payment($id = null)
    {
        $this->erp->checkPermissions('edit', true);
        $this->load->helper('security');
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        $this->form_validation->set_rules('reference_no', lang("reference_no"), 'required');
        $this->form_validation->set_rules('amount-paid', lang("amount"), 'required');
        $this->form_validation->set_rules('paid_by', lang("paid_by"), 'required');
        $this->form_validation->set_rules('userfile', lang("attachment"), 'xss_clean');
        if ($this->form_validation->run() == true) {
            if ($this->Owner || $this->Admin) {
                $date = $this->erp->fld(trim($this->input->post('date')));
            } else {
                $date = date('Y-m-d H:i:s');
            }
			$auto_ref = $this->site->getReference('pp');
			$paid = $this->input->post('paid_by');
			$paid_2 = $this->input->post('paid_2');
			$amount_2 = $this->input->post('amount_2');
			$suppliers_id = $this->input->post('suppliers_id');
			$reference_no_o = $this->input->post('reference_no_o');
			if($paid == "deposit"){
				$refer = $reference_no_o;
			}else{
				if($paid_2 == "deposit"){
					$refer = $auto_ref;
				}else{
					$refer = $this->input->post('reference_no');
				}

			}
            $payment = array(
                'date' => $date,
                'purchase_id' => $this->input->post('purchase_id'),
                'reference_no' => $refer,
                'amount' => $this->input->post('amount-paid'),
				'discount' => $this->input->post('discount'),
                'paid_by' => $this->input->post('paid_by'),
                'cheque_no' => $this->input->post('cheque_no'),
                'cc_no' => $this->input->post('pcc_no'),
                'cc_holder' => $this->input->post('pcc_holder'),
                'cc_month' => $this->input->post('pcc_month'),
                'cc_year' => $this->input->post('pcc_year'),
                'cc_type' => $this->input->post('pcc_type'),
                'note' => $this->input->post('note'),
				'bank_account' => $this->input->post('bank_account')
            );

            if ($_FILES['userfile']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->upload_path;
                $config['allowed_types'] = $this->digital_file_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = false;
                $config['encrypt_name'] = true;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                $payment['attachment'] = $photo;
            }
        } elseif ($this->input->post('edit_payment')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }

        if ($this->form_validation->run() == true && $this->purchases_model->updatePayment($id, $payment,$paid_2,$amount_2,$suppliers_id,$reference_no_o)) {
            $this->session->set_flashdata('message', lang("payment_updated"));
             redirect($_SERVER["HTTP_REFERER"]);
        } else {
			$payment = $this->purchases_model->getPaymentByID($id);
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['payment'] = $payment;
			$purchase = $this->purchases_model->getPurchaseByID($payment->purchase_id);
            $this->data['inv'] = $purchase;
            $this->data['modal_js'] = $this->site->modal_js();
			$this->data['auto_ref'] =  $this->site->getReference('pp');
			$this->data['bankAccounts'] =  $this->site->getAllBankAccounts();
            $this->load->view($this->theme . 'purchases/edit_payment', $this->data);
        }
    }

    public function delete_payment($id = null)
    {
        $this->erp->checkPermissions('delete', true);

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        if ($this->purchases_model->deletePayment($id)) {
            //echo lang("payment_deleted");
            $this->session->set_flashdata('message', lang("payment_deleted"));
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }

    /* -------------------------------------------------------------------------------- */

    public function expenses($id = null)
    {
        $this->erp->checkPermissions();
		$this->data['users'] = $this->purchases_model->getStaff();
        $this->data['billers'] = $this->site->getAllCompanies('biller');

        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('expenses')));
        $meta = array('page_title' => lang('expenses'), 'bc' => $bc);
        $this->page_construct('purchases/expenses', $meta, $this->data);
    }

    public function getExpenses($id = null)
    {
        $this->erp->checkPermissions('index');
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
        if ($this->input->get('biller')) {
            $biller = $this->input->get('biller');
        } else {
            $biller = NULL;
        }
		if ($this->input->get('note')) {
            $note = $this->input->get('note');
        } else {
            $note = NULL;
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

        $detail_link = anchor('purchases/expense_note/$1', '<i class="fa fa-file-text-o"></i> ' . lang('expense_note'), 'data-toggle="modal" data-target="#myModal2"');
        $edit_link = anchor('purchases/edit_expense/$1', '<i class="fa fa-edit"></i> ' . lang('edit_expense'), 'data-toggle="modal" data-target="#myModal"');
        //$attachment_link = '<a href="'.base_url('assets/uploads/$1').'" target="_blank"><i class="fa fa-chain"></i></a>';
        $delete_link = "<a href='#' class='po' title='<b>" . $this->lang->line("delete_expense") . "</b>' data-content=\"<p>"
        . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('purchases/delete_expense/$1') . "'>"
        . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i> "
        . lang('delete_expense') . "</a>";
        $action = '<div class="text-center"><div class="btn-group text-left">'
        . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
        . lang('actions') . ' <span class="caret"></span></button>
        <ul class="dropdown-menu pull-right" role="menu">
            <li>' . $detail_link . '</li>
            <li>' . $edit_link . '</li>
            <li class="delete">' . $delete_link . '</li>
        </ul>
    </div></div>';

        $this->load->library('datatables');

        $this->datatables
            ->select($this->db->dbprefix('expenses') . ".id as id, expenses.date, expenses.reference, gl_trans.narrative  ,expenses.amount ,expenses.note, CONCAT(" . $this->db->dbprefix('users') . ".first_name, ' ', " . $this->db->dbprefix('users') . ".last_name) as user, attachment", false)
            ->from('expenses')
            ->join('users', 'users.id=expenses.created_by', 'left')
			->join('gl_trans', 'gl_trans.account_code = expenses.account_code', 'left')
            ->group_by('expenses.id');

        if (!$this->Owner && !$this->Admin) {
            $this->datatables->where('expenses.created_by', $this->session->userdata('user_id'));
        }
		if ($user_query) {
			$this->datatables->where('expenses.created_by', $user_query);
		}
		if ($reference_no) {
			$this->datatables->where('expenses.reference', $reference_no);
		}
		if ($biller) {
			$this->datatables->where('expenses.biller_id', $biller);
		}
		if ($note) {
			$this->datatables->where("expenses.note LIKE '%" . $note . "%'");
		}
		if ($start_date) {
			$this->datatables->where($this->db->dbprefix('expenses').'.date BETWEEN "' . $start_date . '" and "' . $end_date . '"');
		}
        $this->datatables->add_column("Actions", $action, "id");
        echo $this->datatables->generate();
    }

    public function expense_note($id = null)
    {
        $expense = $this->purchases_model->getExpenseByID($id);
        $this->data['user'] = $this->site->getUser($expense->created_by);
        $this->data['expense'] = $expense;
        $this->data['page_title'] = $this->lang->line("expense_note");
        $this->load->view($this->theme . 'purchases/expense_note', $this->data);
    }

	function expense_reference_check( $reference_no )
	{
		$check_expense_reference = $this->purchases_model->check_expense_reference($reference_no);

		if ($check_expense_reference == TRUE)
		{
			$this->form_validation->set_message('reference_no', lang('reference_no_expense_exist'));
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}

    public function add_expense()
    {
        $this->erp->checkPermissions('expenses', true);
        $this->load->helper('security');

        //$this->form_validation->set_rules('reference', lang("reference"), 'required');
        $this->form_validation->set_rules('amount', lang("amount"), 'required');

		if($this->input->post('reference')){
			$this->form_validation->set_rules('reference', lang("reference"), 'trim|callback_expense_reference_check');
		}

        $this->form_validation->set_rules('userfile', lang("attachment"), 'xss_clean');
        $this->form_validation->set_rules('reference_no', lang("reference"), 'required|is_unique[expenses.reference]');

        if ($this->form_validation->run() == true)
        {
            if ($this->Owner || $this->Admin) {
                $date = $this->erp->fld(trim($this->input->post('date')));
            } else {
                $date = date('Y-m-d H:i:s');
            }

            $data_payment = array(
                            'date'          => $date,
							'biller_id'     => $this->input->post('biller'),
							'reference_no'	=> $this->site->getReference('pay'),
							'amount'        => abs($this->input->post('amount')),
                            'paid_by'       => 'cash',
                            'created_by'    => $this->session->userdata('user_id'),
                            'note'          => $this->input->post('note', true),
							'type'			=> 'sent'
                        );
            $data = array(
                'date' 			=> $date,
                'reference' 	=> $this->input->post('reference') ? $this->input->post('reference') : $this->site->getReference('ex',$this->input->post('biller')),
				//'amount'  	=> $this->input->post('amount'),
                'amount' 		=> $this->input->post('amount'),
                'created_by'	=> $this->session->userdata('user_id'),
                'note' 			=> $this->input->post('note', true),
				'account_code' 	=> $this->input->post('account_section'),
				'biller_id'		=> $this->input->post('biller'),
				'bank_code' 	=> $this->input->post('paid_by'),
				'sale_id' 		=> $this->input->post('customer_invoice_no'),
				'customer_id' 	=> $this->input->post('customer_invoice')
            );

            if ($_FILES['userfile']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->upload_path;
                $config['allowed_types'] = $this->digital_file_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = false;
                $config['encrypt_name'] = true;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                $data['attachment'] = $photo;
            }
        } elseif ($this->input->post('add_expense')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('purchases/expenses');
        }

        if ($this->form_validation->run() == true && $this->purchases_model->addExpense($data, $data_payment)) {
            $this->session->set_flashdata('message', lang("expense_added"));
            redirect('purchases/expenses');
        } else {

			$this->load->model('accounts_model');
            $this->load->model('pos_model');
            $this->pos_settings = $this->pos_model->getSetting();
            $this->data['pos_settings'] = $this->pos_settings;
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['exnumber'] = '';
			$this->data['chart_accounts'] = $this->accounts_model->getAllChartAccountIn('50,60,80');
			$this->data['paid_by'] = $this->accounts_model->getAllChartAccountBank();
			$this->data['currency'] = $this->site->getCurrency();
			$this->data['customers'] = $this->site->getCustomers();
			$this->data['invoices'] = $this->site->getCustomerInvoices();

			if ($this->Owner || $this->Admin || !$this->session->userdata('biller_id')) {
				$biller_id = $this->site->get_setting()->default_biller;
				$this->data['reference_no'] = $this->site->getReference('ex',$biller_id);
			} else {
				$biller_id = $this->session->userdata('biller_id');
				$this->data['reference_no'] = $this->site->getReference('ex',$biller_id);
			}
			$this->data['billers'] = $this->site->getAllCompanies('biller');
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'purchases/add_expense', $this->data);
        }
    }

    public function edit_expense($id = null)
    {
        $this->erp->checkPermissions('edit', true);
        $this->load->helper('security');
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        $this->form_validation->set_rules('reference', lang("reference"), 'required');
        $this->form_validation->set_rules('amount', lang("amount"), 'required');
        $this->form_validation->set_rules('userfile', lang("attachment"), 'xss_clean');
        if ($this->form_validation->run() == true) {
            if ($this->Owner || $this->Admin) {
                $date = $this->erp->fld(trim($this->input->post('date')));
            } else {
                $date = date('Y-m-d H:i:s');
            }
            $data_payment = array(
				'date'          => $date,
				'reference_no'	=> $this->site->getReference('pay'),
				'amount'        => abs($this->input->post('amount')),
				'paid_by'       => 'cash',
				'created_by'    => $this->session->userdata('user_id'),
				'note'          => $this->input->post('note', true),
				'type'			=> 'sent'
			);
            $data = array(
                'date' => $date,
                'reference' => $this->input->post('reference'),
                'amount' => $this->input->post('amount'),
                'note' => $this->input->post('note', true),
				'account_code' => $this->input->post('account_code'),
				'biller_id'	=> $this->input->post('biller'),
				'bank_code' => $this->input->post('paid_by'),
				'updated_by' => $this->session->userdata('user_id'),
				'sale_id' => $this->input->post('customer_invoice_no'),
				'customer_id' => $this->input->post('customer_invoice')
            );

            if ($_FILES['userfile']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->upload_path;
                $config['allowed_types'] = $this->digital_file_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = false;
                $config['encrypt_name'] = true;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                $data['attachment'] = $photo;
            }

        } elseif ($this->input->post('edit_expense')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }

        if ($this->form_validation->run() == true && $this->purchases_model->updateExpense($id, $data, $data_payment)) {
            $this->session->set_flashdata('message', lang("expense_updated"));
            redirect("purchases/expenses");
        } else {
			$this->load->model('accounts_model');
             $this->load->model('pos_model');
            $this->pos_settings = $this->pos_model->getSetting();
            $this->data['pos_settings'] = $this->pos_settings;
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['KHM'] = $this->purchases_model->getKHM();
            $this->data['expense'] = $this->purchases_model->getExpenseByID($id);
            $this->data['modal_js'] = $this->site->modal_js();
			$this->data['currency'] = $this->site->getCurrency();
			$this->data['chart_accounts'] = $this->accounts_model->getAllChartAccountIn('30,50,60,80');
			$this->data['paid_by'] = $this->accounts_model->getAllChartAccountBank();
			$this->data['billers'] = $this->site->getAllCompanies('biller');
			$this->data['customers'] = $this->site->getCustomers();
			$this->data['invoices'] = $this->site->getCustomerInvoices();
            $this->load->view($this->theme . 'purchases/edit_expense', $this->data);
        }
    }

    public function delete_expense($id = null)
    {
        $this->erp->checkPermissions('delete', true);

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $expense = $this->purchases_model->getExpenseByID($id);
        if ($this->purchases_model->deleteExpense($id)) {
            if ($expense->attachment) {
                unlink($this->upload_path . $expense->attachment);
            }
            echo lang("expense_deleted");
        }
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
                        $this->purchases_model->deleteExpense($id);
                    }
                    $this->session->set_flashdata('message', $this->lang->line("expenses_deleted"));
                    redirect($_SERVER["HTTP_REFERER"]);
                }

                if ($this->input->post('form_action') == 'export_excel' || $this->input->post('form_action') == 'export_pdf') {

                    $this->load->library('excel');
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle(lang('expenses'));
                    $this->excel->getActiveSheet()->SetCellValue('A1', lang('date'));
                    $this->excel->getActiveSheet()->SetCellValue('B1', lang('reference'));
					$this->excel->getActiveSheet()->SetCellValue('C1', lang('category_expense'));
                    $this->excel->getActiveSheet()->SetCellValue('D1', lang('amount'));
                    $this->excel->getActiveSheet()->SetCellValue('E1', lang('note'));
                    $this->excel->getActiveSheet()->SetCellValue('F1', lang('created_by'));
                    $styleArray = array(
                        'font'  => array(
                            'bold'  => true
                        )
                    );

                    $this->excel->getActiveSheet()->getStyle('A1:F1')->applyFromArray($styleArray);
                    $row            = 2;
                    $sum_amount     = 0;
                    foreach ($_POST['val'] as $id) {
                        $expense = $this->purchases_model->getExpenses($id);
						//Total sum amount
                        $sum_amount += $expense->amount;
						//$this->erp->print_arrays($expense);
                        $user = $this->site->getUser($expense->created_by);
                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $this->erp->hrld($expense->date));
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $expense->reference." ");
						$this->excel->getActiveSheet()->SetCellValue('C' . $row, $expense->narrative);
                        $this->excel->getActiveSheet()->SetCellValue('D' . $row, $expense->amount);
                        $this->excel->getActiveSheet()->SetCellValue('E' . $row, $this->erp->decode_html(strip_tags($expense->note)));
                        $this->excel->getActiveSheet()->SetCellValue('F' . $row, $expense->user);
						//display total sum amount
                        $i = $row+1;
                        $this->excel->getActiveSheet()->SetCellValue('D' . $i, $sum_amount);
                        $row++;
                    }
                    $this->excel->getActiveSheet()->getStyle('A1:F1')->applyFromArray($styleArray);

                    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(35);
                    $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
					$this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $filename = 'list_expenses_' . date('Y_m_d_H_i_s');
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

						//Add style bold text in case PDF
                        $this->excel->getActiveSheet()->getStyle('D'. $i. '')->getFont()->setBold(true);

                        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'PDF');
                        return $objWriter->save('php://output');
                    }
                    if ($this->input->post('form_action') == 'export_excel') {
                        header('Content-Type: application/vnd.ms-excel');
                        header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
                        header('Cache-Control: max-age=0');

						//apply style border top and bold text in case excel
                        $this->excel->getActiveSheet()->getStyle('D'.$i. '')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                        $this->excel->getActiveSheet()->getStyle('D'. $i. '')->getFont()->setBold(true);

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

	public function add_purchase_return($id = null)
    {
        $this->erp->checkPermissions('return_add',null, 'purchases');

        $this->form_validation->set_message('is_natural_no_zero', $this->lang->line("no_zero_required"));
        //$this->form_validation->set_rules('reference_no', $this->lang->line("ref_no"), 'required');
        $this->form_validation->set_rules('warehouse', $this->lang->line("warehouse"), 'required|is_natural_no_zero');
        $this->form_validation->set_rules('suppliers', $this->lang->line("suppliers"), 'required');

        $this->session->unset_userdata('csrf_token');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        // $this->form_validation->set_rules('reference_no', lang("reference_no"), 'required');
        $this->form_validation->set_rules('return_surcharge', lang("return_surcharge"), 'required');

        if ($this->form_validation->run() == true) {
            $purchase = $this->purchases_model->getPurchaseByID($id);

			$warehouse_id = $this->input->post('warehouse');
            $customer_id = $this->input->post('customer');
			$biller_id = $this->input->post('biller');

			$supplier_id = $this->input->post('suppliers');
            $status = $this->input->post('status');
            $shipping = $this->input->post('shippings') ? $this->input->post('shippings') : 0;
            $supplier_details = $this->site->getCompanyByID($supplier_id);
            $supplier = $supplier_details->company != '-'  ? $supplier_details->company : $supplier_details->name;

            $quantity = "quantity";
            $product = "product";
            $unit_cost = "unit_cost";
            $tax_rate = "tax_rate";
            if ($this->Owner || $this->Admin) {
                $date = $this->erp->fld(trim($this->input->post('date')));
            } else {
                $date = date('Y-m-d H:i:s');
            }

			$reference = $this->input->post('reference_no') ? $this->input->post('reference_no') : $this->site->getReference('rep');

            $return_surcharge = $this->input->post('return_surcharge') ? $this->input->post('return_surcharge') : 0;
            $note = $this->erp->clear_tags($this->input->post('note'));

            $total = 0;
            $product_tax = 0;
            $order_tax = 0;
            $product_discount = 0;
            $order_discount = 0;
            $percentage = '%';
			$olo_amount = 0;
			$olo_discount = 0;
			$olo_tax = 0;
            $percentage = '%';
            $i = isset($_POST['product']) ? sizeof($_POST['product']) : 0;
            for ($r = 0; $r < $i; $r++) {
                $item_id = $_POST['product_id'][$r];
                $item_code = $_POST['product'][$r];
                //$purchase_item_id = $_POST['purchase_item_id'][$r];
				$purchase_ref = $_POST['purchase_reference'][$r];

                $item_option = isset($_POST['product_option'][$r]) && !empty($_POST['product_option'][$r]) && $_POST['product_option'][$r] != 'false' ? $_POST['product_option'][$r] : null;
                //$option_details = $this->purchases_model->getProductOptionByID($item_option);
                $real_unit_cost = $this->erp->formatDecimal($_POST['real_unit_cost'][$r]);
                $unit_cost = $this->erp->formatDecimal($_POST['unit_cost'][$r]);
                $item_quantity = $_POST['quantity'][$r];
                $item_expiry = isset($_POST['expiry'][$r]) ? $_POST['expiry'][$r] : '';
                $item_tax_rate = isset($_POST['product_tax'][$r]) ? $_POST['product_tax'][$r] : null;
                $item_discount = isset($_POST['product_discount'][$r]) ? $_POST['product_discount'][$r] : null;

				$purchase_item_id = $this->purchases_model->getPurchaseIDByRef($purchase_ref);
				//$purchase_item_id = $purchase->id;

                if (isset($item_code) && isset($real_unit_cost) && isset($unit_cost) && isset($item_quantity)) {
                    $product_details = $this->purchases_model->getProductByCode($item_code);

                    $item_type = $product_details->type;
                    $item_name = $product_details->name;

                    if (isset($item_discount)) {
                        $discount = $item_discount;
                        $dpos = strpos($discount, $percentage);
                        if ($dpos !== false) {
                            $pds = explode("%", $discount);
                            $pr_discount = (($this->erp->formatDecimal($unit_cost)) * (Float) ($pds[0])) / 100;
                        } else {
                            $pr_discount = $this->erp->formatDecimal($discount);
                        }
                    } else {
                        $pr_discount = 0;
                    }
                    // $unit_cost = $this->erp->formatDecimal($unit_cost - $pr_discount);
                    $pr_item_discount = $this->erp->formatDecimal($pr_discount * $item_quantity);
                    $product_discount += $pr_item_discount;

                    if (isset($item_tax_rate) && $item_tax_rate != 0) {
                        $pr_tax = $item_tax_rate;
                        $tax_details = $this->site->getTaxRateByID($pr_tax);
                        if ($tax_details->type == 1 && $tax_details->rate != 0) {

                            if (!$product_details->tax_method) {
                                $item_tax = $this->erp->formatDecimal((($unit_cost) * $tax_details->rate) / (100 + $tax_details->rate));
                                $tax = $tax_details->rate . "%";
                            } else {
                                $item_tax = $this->erp->formatDecimal((($unit_cost) * $tax_details->rate) / 100);
                                $tax = $tax_details->rate . "%";
                            }

                        } elseif ($tax_details->type == 2) {

                            $item_tax = $this->erp->formatDecimal($tax_details->rate);
                            $tax = $tax_details->rate;

                        }
                        $pr_item_tax = $this->erp->formatDecimal($item_tax * $item_quantity);

                    } else {
                        $pr_tax = 0;
                        $pr_item_tax = 0;
                        $tax = "";
                    }

                    //$item_net_cost = $product_details->tax_method ? $this->erp->formatDecimal($unit_cost - $pr_discount) : $this->erp->formatDecimal($unit_cost - $pr_discount);
					$item_net_cost = $unit_cost;
                    $product_tax += $pr_item_tax;
                    $subtotal = (($item_net_cost * $item_quantity) + $pr_item_tax);

					$old = $this->purchases_model->getPurcahseItemByPurchaseIDProductID($id,$item_id);
					$amount_olo = $old->net_unit_cost * $item_quantity;

                    $products[] = array(
                        'product_id' => $item_id,
                        'product_code' => $item_code,
                        'product_name' => $item_name,
                        'product_type' => $item_type,
                        'option_id' => $item_option,
                        'net_unit_cost' => $item_net_cost,
                        // 'unit_cost' => $this->erp->formatDecimal($item_net_cost + $item_tax),
                        'quantity' => $item_quantity,
                        'warehouse_id' => $warehouse_id,
                        'item_tax' => $pr_item_tax,
                        'tax_rate_id' => $pr_tax,
                        'tax' => $tax,
                        'transaction_type' => 'PURCHASE RETURN',
                        'discount' => $item_discount,
                        'item_discount' => $pr_item_discount,
                        'subtotal' => $this->erp->formatDecimal($subtotal),
                        'real_unit_cost' => $real_unit_cost,
                        'purchase_item_id' => $purchase_item_id,
						'old_subtotal' => $amount_olo
                    );
					$olo_amount += $old->net_unit_cost * $item_quantity;
                    $total += $item_net_cost * $item_quantity;
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
                    $order_discount = $this->erp->formatDecimal((($total + $product_tax) * (Float) ($ods[0])) / 100);
					$olo_discount = $this->erp->formatDecimal((($olo_amount + $product_tax) * (Float) ($ods[0])) / 100);
                } else {
                    $order_discount = $this->erp->formatDecimal($order_discount_id);
					$olo_discount = $this->erp->formatDecimal($order_discount_id);
                }
            } else {
                $order_discount_id = null;
            }
            $total_discount = $order_discount + $product_discount;

            if ($this->Settings->tax2) {
                $order_tax_id = $this->input->post('order_tax');
                if ($order_tax_details = $this->site->getTaxRateByID($order_tax_id)) {
                    if ($order_tax_details->type == 2) {
                        $order_tax = $this->erp->formatDecimal($order_tax_details->rate);
						$olo_tax = $this->erp->formatDecimal($order_tax_details->rate);
                    }
                    if ($order_tax_details->type == 1) {
                        $order_tax = $this->erp->formatDecimal((($total + $product_tax - $order_discount + $shipping) * $order_tax_details->rate) / 100);

						$olo_tax = $this->erp->formatDecimal((($olo_amount + $product_tax - $olo_discount + $shipping) * $order_tax_details->rate) / 100);
                    }
                }
            } else {
                $order_tax_id = null;
            }

            $total_tax = $this->erp->formatDecimal($product_tax + $order_tax);
            $grand_total = $this->erp->formatDecimal($this->erp->formatDecimal($total) + $total_tax - $this->erp->formatDecimal($return_surcharge) - $order_discount + $shipping);

			$olo_total = $this->erp->formatDecimal($this->erp->formatDecimal($olo_amount) + $olo_tax + $shipping - $this->erp->formatDecimal($return_surcharge) - $olo_discount);
            $data = array(
                'date' => $date,
                'purchase_id' => $id,
                'reference_no' => $reference,
                'supplier_id' => $supplier_id,
                'supplier' => $supplier,
                'warehouse_id' => $warehouse_id,
                'note' => $note,
                'total' => $this->erp->formatDecimal($total),
                'product_discount' => $this->erp->formatDecimal($product_discount),
                'order_discount_id' => $order_discount_id,
                'order_discount' => $order_discount,
                'total_discount' => $total_discount,
                'product_tax' => $this->erp->formatDecimal($product_tax),
                'order_tax_id' => $order_tax_id,
                'biller_id' => $this->session->userdata('biller_id')?$this->session->userdata('biller_id'):$this->Settings->default_biller,
                'order_tax' => $order_tax,
                'total_tax' => $total_tax,
                'shipping'  => $shipping,
                'surcharge' => $this->erp->formatDecimal($return_surcharge),
                'grand_total' => $grand_total,
				'old_grand_total' => $olo_total?$olo_total:0,
                'created_by' => $this->session->userdata('user_id'),
            );

            //$this->erp->print_arrays($data, $products);
        }

        if ($this->form_validation->run() == true && $this->purchases_model->returnPurchases($data, $products)) {
            $this->session->set_flashdata('message', lang("return_purchase_added"));
            redirect("purchases/return_purchases");
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

            $this->data['inv'] = $this->purchases_model->getPurchaseByID($id);

            $inv_items = $this->purchases_model->getAllPurchaseItems($id);

            $c = rand(100000, 9999999);
            if(isset($inv_items)){
                foreach ($inv_items as $item) {
                    $row = $this->site->getProductByID($item->product_id);
                    $row->expiry = (($item->expiry && $item->expiry != '0000-00-00') ? $this->erp->fsd($item->expiry) : '');
                    $row->qty = $item->quantity;
                    $row->oqty = $item->quantity;
                    $row->purchase_item_id = $item->id;
                    $row->supplier_part_no = $item->supplier_part_no;
                    $row->received = $item->quantity_received ? $item->quantity_received : $item->quantity;
                    $row->quantity_balance = $item->quantity_balance + ($item->quantity-$row->received);
                    $row->discount = $item->discount ? $item->discount : '0';
                    $options = $this->purchases_model->getProductOptions($row->id);
                    $row->option = !empty($item->option_id) ? $item->option_id : '';
                    $row->real_unit_cost = $item->real_unit_cost;

                    if ($item->quantity > 0) {
                        $row->cost = $this->erp->formatDecimal($item->net_unit_cost + ($item->item_discount / ($item->quantity ?$item->quantity:0)));
                    }

                    $row->tax_rate = $item->tax_rate_id;
                    unset($row->details, $row->product_details, $row->price, $row->file, $row->product_group_id);
                    $ri = $this->Settings->item_addition ? $row->id : $c;
                    if ($row->tax_rate) {
                        $tax_rate = $this->site->getTaxRateByID($row->tax_rate);
                        $pr[$ri] = array('id' => $c, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'tax_rate' => $tax_rate, 'options' => $options, 'purchase_ref' => '', 'quantity_received' => 0);
                    } else {
                        $pr[$ri] = array('id' => $c, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'tax_rate' => false, 'options' => $options, 'purchase_ref' => '', 'quantity_received' => 0);
                    }
                    $c++;
                }
            }

            $this->data['inv_items'] = json_encode($pr);
            $this->data['suppliers'] = $this->site->getAllCompanies('supplier');
            $this->data['categories'] = $this->site->getAllCategories();
            $this->data['tax_rates'] = $this->site->getAllTaxRates();
            $this->data['warehouses'] = $this->site->getAllWarehouses();
            $this->data['ponumber'] = ''; //$this->site->getReference('po');
            $this->load->helper('string');
            $value = random_string('alnum', 20);
            $this->session->set_userdata('user_csrf', $value);
            $this->data['csrf'] = $this->session->userdata('user_csrf');
            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('purchases'), 'page' => lang('purchases')), array('link' => '#', 'page' => lang('add_purchase_return')));
            $meta = array('page_title' => lang('add_purchase_return'), 'bc' => $bc);
            $this->page_construct('purchases/add_purchase_return', $meta, $this->data);
        }
    }

	/* Purchase Return */
	public function add_purchase_return_old($id = null)
    {
        $this->erp->checkPermissions();

        $this->form_validation->set_message('is_natural_no_zero', $this->lang->line("no_zero_required"));
        //$this->form_validation->set_rules('reference_no', $this->lang->line("ref_no"), 'required');
        $this->form_validation->set_rules('warehouse', $this->lang->line("warehouse"), 'required|is_natural_no_zero');
        $this->form_validation->set_rules('supplier', $this->lang->line("supplier"), 'required');

        $this->session->unset_userdata('csrf_token');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        // $this->form_validation->set_rules('reference_no', lang("reference_no"), 'required');
        $this->form_validation->set_rules('return_surcharge', lang("return_surcharge"), 'required');

        if ($this->form_validation->run() == true) {
            $purchase = $this->purchases_model->getPurchaseByID($id);

			$warehouse_id = $this->input->post('warehouse');
            $customer_id = $this->input->post('customer');
			$biller_id = $this->input->post('biller');

			$supplier_id = $this->input->post('supplier');
            $status = $this->input->post('status');
            $shipping = $this->input->post('shippings') ? $this->input->post('shippings') : 0;
            $supplier_details = $this->site->getCompanyByID($supplier_id);
            $supplier = $supplier_details->company != '-'  ? $supplier_details->company : $supplier_details->name;

            $quantity = "quantity";
            $product = "product";
            $unit_cost = "unit_cost";
            $tax_rate = "tax_rate";
            if ($this->Owner || $this->Admin) {
                $date = $this->erp->fld(trim($this->input->post('date')));
            } else {
                $date = date('Y-m-d H:i:s');
            }

			$reference = $this->input->post('reference_no') ? $this->input->post('reference_no') : $this->site->getReference('rep');

            $return_surcharge = $this->input->post('return_surcharge') ? $this->input->post('return_surcharge') : 0;
            $note = $this->erp->clear_tags($this->input->post('note'));

            $total = 0;
            $product_tax = 0;
            $order_tax = 0;
            $product_discount = 0;
            $order_discount = 0;
            $percentage = '%';
			$olo_amount = 0;
			$olo_discount = 0;
			$olo_tax = 0;
            $percentage = '%';
            $i = isset($_POST['product']) ? sizeof($_POST['product']) : 0;
            for ($r = 0; $r < $i; $r++) {
                $item_id = $_POST['product_id'][$r];
                $item_code = $_POST['product'][$r];
                //$purchase_item_id = $_POST['purchase_item_id'][$r];
				$purchase_ref = $_POST['purchase_reference'][$r];

                $item_option = isset($_POST['product_option'][$r]) && !empty($_POST['product_option'][$r]) && $_POST['product_option'][$r] != 'false' ? $_POST['product_option'][$r] : null;
                //$option_details = $this->purchases_model->getProductOptionByID($item_option);
                $real_unit_cost = $this->erp->formatDecimal($_POST['real_unit_cost'][$r]);
                $unit_cost = $this->erp->formatDecimal($_POST['unit_cost'][$r]);
                $item_quantity = $_POST['quantity'][$r];
                $item_expiry = isset($_POST['expiry'][$r]) ? $_POST['expiry'][$r] : '';
                $item_tax_rate = isset($_POST['product_tax'][$r]) ? $_POST['product_tax'][$r] : null;
                $item_discount = isset($_POST['product_discount'][$r]) ? $_POST['product_discount'][$r] : null;

				$purchase_item_id = $this->purchases_model->getPurchaseIDByRef($purchase_ref);
				//$purchase_item_id = $purchase->id;

                if (isset($item_code) && isset($real_unit_cost) && isset($unit_cost) && isset($item_quantity)) {
                    $product_details = $this->purchases_model->getProductByCode($item_code);

                    $item_type = $product_details->type;
                    $item_name = $product_details->name;

                    if (isset($item_discount)) {
                        $discount = $item_discount;
                        $dpos = strpos($discount, $percentage);
                        if ($dpos !== false) {
                            $pds = explode("%", $discount);
                            $pr_discount = (($this->erp->formatDecimal($unit_cost)) * (Float) ($pds[0])) / 100;
                        } else {
                            $pr_discount = $this->erp->formatDecimal($discount);
                        }
                    } else {
                        $pr_discount = 0;
                    }
                    // $unit_cost = $this->erp->formatDecimal($unit_cost - $pr_discount);
                    $pr_item_discount = $this->erp->formatDecimal($pr_discount * $item_quantity);
                    $product_discount += $pr_item_discount;

                    if (isset($item_tax_rate) && $item_tax_rate != 0) {
                        $pr_tax = $item_tax_rate;
                        $tax_details = $this->site->getTaxRateByID($pr_tax);
                        if ($tax_details->type == 1 && $tax_details->rate != 0) {

                            if (!$product_details->tax_method) {
                                $item_tax = $this->erp->formatDecimal((($unit_cost) * $tax_details->rate) / (100 + $tax_details->rate));
                                $tax = $tax_details->rate . "%";
                            } else {
                                $item_tax = $this->erp->formatDecimal((($unit_cost) * $tax_details->rate) / 100);
                                $tax = $tax_details->rate . "%";
                            }

                        } elseif ($tax_details->type == 2) {

                            $item_tax = $this->erp->formatDecimal($tax_details->rate);
                            $tax = $tax_details->rate;

                        }
                        $pr_item_tax = $this->erp->formatDecimal($item_tax * $item_quantity);

                    } else {
                        $pr_tax = 0;
                        $pr_item_tax = 0;
                        $tax = "";
                    }

                    //$item_net_cost = $product_details->tax_method ? $this->erp->formatDecimal($unit_cost - $pr_discount) : $this->erp->formatDecimal($unit_cost - $pr_discount);
					$item_net_cost = $unit_cost;
                    $product_tax += $pr_item_tax;
                    $subtotal = (($item_net_cost * $item_quantity) + $pr_item_tax);

					$old = $this->purchases_model->getPurcahseItemByPurchaseIDProductID($id,$item_id);
					$amount_olo = $old->net_unit_cost * $item_quantity;

                    $products[] = array(
                        'product_id' => $item_id,
                        'product_code' => $item_code,
                        'product_name' => $item_name,
                        'product_type' => $item_type,
                        'option_id' => $item_option,
                        'net_unit_cost' => $item_net_cost,
                        // 'unit_cost' => $this->erp->formatDecimal($item_net_cost + $item_tax),
                        'quantity' => $item_quantity,
                        'warehouse_id' => $warehouse_id,
                        'item_tax' => $pr_item_tax,
                        'tax_rate_id' => $pr_tax,
                        'tax' => $tax,
                        'discount' => $item_discount,
                        'item_discount' => $pr_item_discount,
                        'subtotal' => $this->erp->formatDecimal($subtotal),
                        'real_unit_cost' => $real_unit_cost,
                        'purchase_item_id' => $purchase_item_id,
						'old_subtotal' => $amount_olo
                    );
					$olo_amount += $old->net_unit_cost * $item_quantity;
                    $total += $item_net_cost * $item_quantity;
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
                    $order_discount = $this->erp->formatDecimal((($total + $product_tax) * (Float) ($ods[0])) / 100);
					$olo_discount = $this->erp->formatDecimal((($olo_amount + $product_tax) * (Float) ($ods[0])) / 100);
                } else {
                    $order_discount = $this->erp->formatDecimal($order_discount_id);
					$olo_discount = $this->erp->formatDecimal($order_discount_id);
                }
            } else {
                $order_discount_id = null;
            }
            $total_discount = $order_discount + $product_discount;

            if ($this->Settings->tax2) {
                $order_tax_id = $this->input->post('order_tax');
                if ($order_tax_details = $this->site->getTaxRateByID($order_tax_id)) {
                    if ($order_tax_details->type == 2) {
                        $order_tax = $this->erp->formatDecimal($order_tax_details->rate);
						$olo_tax = $this->erp->formatDecimal($order_tax_details->rate);
                    }
                    if ($order_tax_details->type == 1) {
                        $order_tax = $this->erp->formatDecimal((($total + $product_tax - $order_discount + $shipping) * $order_tax_details->rate) / 100);

						$olo_tax = $this->erp->formatDecimal((($olo_amount + $product_tax - $olo_discount + $shipping) * $order_tax_details->rate) / 100);
                    }
                }
            } else {
                $order_tax_id = null;
            }

            $total_tax = $this->erp->formatDecimal($product_tax + $order_tax);
            $grand_total = $this->erp->formatDecimal($this->erp->formatDecimal($total) + $total_tax - $this->erp->formatDecimal($return_surcharge) - $order_discount + $shipping);

			$olo_total = $this->erp->formatDecimal($this->erp->formatDecimal($olo_amount) + $olo_tax + $shipping - $this->erp->formatDecimal($return_surcharge) - $olo_discount);
            $data = array(
                'date' => $date,
                'purchase_id' => $id,
                'reference_no' => $reference,
                'supplier_id' => $supplier_id,
                'supplier' => $supplier,
                'warehouse_id' => $warehouse_id,
                'note' => $note,
                'total' => $this->erp->formatDecimal($total),
                'product_discount' => $this->erp->formatDecimal($product_discount),
                'order_discount_id' => $order_discount_id,
                'order_discount' => $order_discount,
                'total_discount' => $total_discount,
                'product_tax' => $this->erp->formatDecimal($product_tax),
                'order_tax_id' => $order_tax_id,
                'biller_id' => $this->session->userdata('biller_id')?$this->session->userdata('biller_id'):$this->Settings->default_biller,
                'order_tax' => $order_tax,
                'total_tax' => $total_tax,
                'shipping'  => $shipping,
                'surcharge' => $this->erp->formatDecimal($return_surcharge),
                'grand_total' => $grand_total,
				'old_grand_total' => $olo_total?$olo_total:0,
                'created_by' => $this->session->userdata('user_id'),
            );

            //$this->erp->print_arrays($data, $products);
        }

        if ($this->form_validation->run() == true && $this->purchases_model->returnPurchases($data, $products)) {
            $this->session->set_flashdata('message', lang("return_purchase_added"));
            redirect("purchases/return_purchases");
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

            $this->data['inv'] = $this->purchases_model->getPurchaseByID($id);

            $inv_items = $this->purchases_model->getAllPurchaseItems($id);
            $c = rand(100000, 9999999);
            if(isset($inv_items)){
                foreach ($inv_items as $item) {
                    $row = $this->site->getProductByID($item->product_id);
                    $row->expiry = (($item->expiry && $item->expiry != '0000-00-00') ? $this->erp->fsd($item->expiry) : '');
                    $row->qty = $item->quantity;
                    $row->oqty = $item->quantity;
                    $row->purchase_item_id = $item->id;
                    $row->supplier_part_no = $item->supplier_part_no;
                    $row->received = $item->quantity_received ? $item->quantity_received : $item->quantity;
                    $row->quantity_balance = $item->quantity_balance + ($item->quantity-$row->received);
                    $row->discount = $item->discount ? $item->discount : '0';
                    $options = $this->purchases_model->getProductOptions($row->id);
                    $row->option = !empty($item->option_id) ? $item->option_id : '';
                    $row->real_unit_cost = $item->real_unit_cost;
                    $row->cost = $this->erp->formatDecimal($item->net_unit_cost + ($item->item_discount / ($item->quantity ?$item->quantity:0)));
                    $row->tax_rate = $item->tax_rate_id;
                    unset($row->details, $row->product_details, $row->price, $row->file, $row->product_group_id);
                    $ri = $this->Settings->item_addition ? $row->id : $c;
                    if ($row->tax_rate) {
                        $tax_rate = $this->site->getTaxRateByID($row->tax_rate);
                        $pr[$ri] = array('id' => $c, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'tax_rate' => $tax_rate, 'options' => $options, 'purchase_ref' => '', 'quantity_received' => 0);
                    } else {
                        $pr[$ri] = array('id' => $c, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'tax_rate' => false, 'options' => $options, 'purchase_ref' => '', 'quantity_received' => 0);
                    }
                    $c++;
                }
            }

            $this->data['inv_items'] = json_encode($pr);
            $this->data['suppliers'] = $this->site->getAllCompanies('supplier');
            $this->data['categories'] = $this->site->getAllCategories();
            $this->data['tax_rates'] = $this->site->getAllTaxRates();
            $this->data['warehouses'] = $this->site->getAllWarehouses();
            $this->data['ponumber'] = ''; //$this->site->getReference('po');
            $this->load->helper('string');
            $value = random_string('alnum', 20);
            $this->session->set_userdata('user_csrf', $value);
            $this->data['csrf'] = $this->session->userdata('user_csrf');
            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('purchases'), 'page' => lang('purchases')), array('link' => '#', 'page' => lang('add_purchase_return')));
            $meta = array('page_title' => lang('add_purchase_return'), 'bc' => $bc);
            $this->page_construct('purchases/add_purchase_return', $meta, $this->data);
        }
    }

	public function expense_by_csv()
    {

		$this->erp->checkPermissions('import_expanse', NULL, 'purchases');

        $this->load->helper('security');
        $this->form_validation->set_rules('userfile', lang("upload_file"), 'xss_clean');
        if ($this->form_validation->run() == true) {

            if (DEMO) {
                $this->session->set_flashdata('message', lang("disabled_in_demo"));
                redirect('welcome');
            }

            if (isset($_FILES["userfile"])) {

                $this->load->library('upload');

                $config['upload_path'] = $this->digital_upload_path;
                $config['allowed_types'] = 'csv';
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = TRUE;

                $this->upload->initialize($config);

                if (!$this->upload->do_upload()) {

                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect("purchases/expense_by_csv");
                }

                $csv = $this->upload->file_name;

                $arrResult = array();
                $handle = fopen($this->digital_upload_path . $csv, "r");
                if ($handle) {
                    while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        $arrResult[] = $row;
                    }
                    fclose($handle);
                }
                $titles = array_shift($arrResult);

                $keys = array('date', 'reference', 'amount', 'note', 'created_by', 'attachment', 'account_code', 'bank_code', 'biller_id', 'updated_by');

                $final = array();
                foreach ($arrResult as $key => $value) {
                    $final[] = array_combine($keys, $value);

                }
				//$this->erp->print_arrays($final);

                $rw = 2;
                foreach ($final as $csv_pr) {
					//$this->erp->print_arrays($final);
                    $data[] = array(
						'date' => $this->erp->fsd($csv_pr['date']),
						'reference' => $csv_pr['reference'],
						'amount' => $csv_pr['amount'],
						'note' => $csv_pr['note'],
						'created_by' => $csv_pr['created_by'],
						'attachment' => $csv_pr['attachment'],
						'account_code' => $csv_pr['account_code'],
						'bank_code' => $csv_pr['bank_code'],
						'note' => $csv_pr['note'],
						'biller_id' => $csv_pr['biller_id'],
						'updated_by' => $csv_pr['updated_by']
					);

					if($this->purchases_model->getExpenseByReference($csv_pr['reference'])){
						$this->session->set_flashdata('error', 'Reference ( '.$csv_pr['reference'].' ) is already exist! Line: ' . $rw);
						redirect("purchases/expense_by_csv");
					}

                    $rw++;
                }
				//$this->erp->print_arrays($data);
            }
        }

        if ($this->form_validation->run() == true && !empty($final)) {
            $this->purchases_model->addExpenses($data);
            $this->session->set_flashdata('message', lang("expense_added"));
            redirect('purchases/expenses');
        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

            $this->data['userfile'] = array('name' => 'userfile',
                'id' => 'userfile',
                'type' => 'text',
                'value' => $this->form_validation->set_value('userfile')
            );

            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('purchases'), 'page' => lang('purchases')), array('link' => '#', 'page' => lang('import_expense_csv')));
            $meta = array('page_title' => lang('import_expense_csv'), 'bc' => $bc);
            $this->page_construct('purchases/import_expense', $meta, $this->data);
        }
	}

    public function getPurchaseReturnQuantity() {
        if ($this->input->get('purchase_ref')) {
            $purchase_ref = $this->input->get('purchase_ref', TRUE);
        }
        if ($this->input->get('product_id')) {
            $product_id = $this->input->get('product_id', TRUE);
        }

        $quantity = $this->purchases_model->getPurchaseItemByRefPID($purchase_ref, $product_id);
        echo json_encode($quantity);
    }

    public function view_return_purchases($id){
		 if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $inv = $this->purchases_model->getReturnByID($id);
        //$this->erp->view_rights($inv->created_by);
        $this->data['barcode'] = "<img src='" . site_url('products/gen_barcode/' . $inv->reference_no) . "' alt='" . $inv->reference_no . "' class='pull-left' />";
        $this->data['customer'] = $this->site->getCompanyByID($inv->supplier_id);
        $this->data['payments'] = $this->purchases_model->getPaymentsForPurchase($id);
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
        $this->data['user'] = $this->site->getUser($inv->created_by);
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
        $this->data['inv'] = $inv;
        $this->data['rows'] = $this->purchases_model->getAllPurchaseReturnItems($id);
        $this->data['sale'] = $this->purchases_model->getInvoiceByID($inv->purchase_id);
		$bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('purchase'), 'page' => lang('view_return_purchases')), array('link' => '#', 'page' => lang('purchase')));
		$meta = array('page_title' => lang('view_return_purchases'), 'bc' => $bc);
		$this->page_construct('purchases/view_return_purchases', $meta, $this->data);
	}

    public function purchase_order($warehouse_id = null)
    {
        $this->erp->checkPermissions('index',NULL,'purchases_order');
		$this->load->model('reports_model');

		if(isset($_GET['d']) != ""){
			$date = $_GET['d'];
			$this->data['date'] = $date;
		}

        $biller_id = $this->session->userdata('biller_id');
        $this->data['billers'] = $this->site->getAllCompanies('biller');
        $this->data['user_billers'] = $this->purchases_model->getAllCompaniesByID($biller_id);
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
				$this->data['warehouse_id'] = str_replace(',', '-',$this->session->userdata('warehouse_id'));
				$this->data['warehouse'] = $this->session->userdata('warehouse_id') ? $this->products_model->getUserWarehouses() : NULL;
			}
        }
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('purchase_order')));
        $meta = array('page_title' => lang('purchase_order'), 'bc' => $bc);
        $this->page_construct('purchases/purchase_order', $meta, $this->data);
    }

    public function getPurchaseOrder($warehouse_id = null)
    {
        $this->erp->checkPermissions('index',null,'purchases_order');
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

        $detail_link = anchor('purchases/modal_view_purchase_order/$1', '<i class="fa fa-file-text-o"></i> ' . lang('purchase_order_details'),'data-toggle="modal" data-target="#myModal"');
        $payments_link = anchor('purchases/payments/$1', '<i class="fa fa-money"></i> ' . lang('view_payments'), 'data-toggle="modal" data-target="#myModal"');
        $add_payment_link = anchor('purchases/add_payment/$1', '<i class="fa fa-money"></i> ' . lang('add_deposit'), 'data-toggle="modal" data-target="#myModal"');
		$ordered_link = anchor('purchases/update_purchases_Order/$1', '<i class="fa fa-check"></i> ' . lang('approve'), '');
		$unordered_link = anchor('purchases/Unapproved/$1', '<i class="fa fa-check"></i> ' . lang('unapproved'), '');
		$reject = anchor('purchases/reject/$1', '<i class="fa fa-times"></i> ' . lang('reject'), '');
		$unreject = anchor('purchases/unreject/$1', '<i class="fa fa-check"></i> ' . lang('unreject'), '');
        $email_link = anchor('purchases/email/$1', '<i class="fa fa-envelope"></i> ' . lang('email_purchase'), 'data-toggle="modal" data-target="#myModal"');
        $edit_link = anchor('purchases/edit_purchase_order/$1', '<i class="fa fa-edit"></i> ' . lang('edit_purchase_order'));
        $add_link = anchor('purchases/add/$1', '<i class="fa fa-edit add_or"></i> ' . lang('add_purchase'));
		$pdf_link = anchor('purchases/pdf/$1', '<i class="fa fa-file-pdf-o"></i> ' . lang('download_pdf'));
        $print_barcode = anchor('products/print_barcodes/?purchase=$1', '<i class="fa fa-print"></i> ' . lang('print_barcodes'));
		$purchase_return = anchor('purchases/return_purchase_order/$1', '<i class="fa fa-angle-double-left"></i> ' . lang('return_purchase_order'));
        $delete_link = "<a href='#' class='po' title='<b>" . $this->lang->line("delete_purchase") . "</b>' data-content=\"<p>"
        . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('purchases/delete/$1') . "'>"
        . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i> "
        . lang('delete_purchase') . "</a>";

        $action = '<div class="text-center"><div class="btn-group text-left">'
        . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
        . lang('actions') . ' <span class="caret"></span></button>
        <ul class="dropdown-menu pull-right" role="menu">

            <li>' . $detail_link . '<li>'
            . (($this->Owner || $this->Admin) ? '<li class="approved">' . $ordered_link . '</li>' : ($this->GP['purchase_order-authorize'] ? '<li class="approved">' . $ordered_link . '</li>' : '')) .
            (($this->Owner || $this->Admin) ? '<li class="unapproved">'.$unordered_link.'</li>' : ($this->GP['purchase_order-authorize'] ? '<li class="unapproved">'.$unordered_link.'</li>' : '')).
            (($this->Owner || $this->Admin) ? '<li class="reject">' . $reject . '</li>' : ($this->GP['purchase_order-authorize'] ? '<li class="reject">' . $reject . '</li>' : ''))
            . (($this->Owner || $this->Admin) ? '<li class="edit">' . $edit_link . '</li>' : ($this->GP['purchases_order-edit'] ? '<li class="edit">' . $edit_link . '</li>' : '')) .
			(($this->Owner || $this->Admin) ? '<li class="add">'.$add_link.'</li>' : ($this->GP['purchases_order-add'] ? '<li class="add">'.$add_link.'</li>' : '')).

		'</ul>
    </div></div>';

		$v1 = "(
			SELECT
				purchase_id,
				CASE
			WHEN sum(quantity) <= sum(quantity_po) THEN
				'received'
			ELSE
				CASE
			WHEN (
				sum(quantity_po) > 0 && sum(quantity_po) < sum(quantity)
			) THEN
				'partial'
			ELSE
				'ordered'
			END
			END AS `status`
			FROM
				erp_purchase_order_items
			GROUP BY
				purchase_id
		) AS erp_purchase_order_items ";
		$v2 = "(
            SELECT
                erp_companies.id,
                erp_companies.`name`
            FROM
                erp_companies
            LEFT JOIN erp_purchases_order ON erp_purchases_order.supplier_id = erp_companies.id
            GROUP BY erp_companies.id
        ) AS erp_sup_name ";
        $biller_id = $this->session->userdata('biller_id');
        $biller_id =json_decode($biller_id);
        $this->load->library('datatables');

        if ($warehouse_id) {
            $this->datatables
				->select("purchases_order.id as id,
						purchases_order.date,
						purchases_order.reference_no,
						purchases_request.reference_no as purchase_ref,
                        companies.company as project,
						IF (erp_purchases_order.supplier = '',sup_name.name,erp_purchases_order.supplier) AS supplier,
						erp_purchase_order_items.status,
						purchases_order.grand_total,						
						purchases_order.payment_status,
                        purchases_order.order_status,
						purchases_order.status  as ordered,
						purchases_order.attachment as attachment
						")
				->from('purchases_order')
                ->join('purchases_request', 'purchases_order.request_id = purchases_request.id', 'left')
                ->join($v1, 'purchase_order_items.purchase_id = erp_purchases_order.id')
				->join($v2, 'erp_purchases_order.supplier_id = erp_sup_name.id', 'left')
                ->join('companies', 'purchases_order.biller_id = companies.id', 'left')
                ->join('users', 'purchases_order.created_by = users.id', 'left')
                ->where_in('purchases_order.biller_id', $biller_id);

                if (count($warehouse_ids) > 1) {
                    $this->datatables->where_in('erp_purchases_order.warehouse_id', $warehouse_ids);
                } else {
                    $this->datatables->where('erp_purchases_order.warehouse_id', $warehouse_id);
                }

        } else {
			$this->datatables
				->select("purchases_order.id as id,
						purchases_order.date,
						purchases_order.reference_no,
						purchases_request.reference_no as purchase_ref,
                        companies.company as project,
						IF (erp_purchases_order.supplier = '',sup_name.name,erp_purchases_order.supplier) AS supplier,
						erp_purchase_order_items.status,
						purchases_order.grand_total,						
						purchases_order.payment_status,
						purchases_order.order_status,
                        purchases_order.status as ordered,
						purchases_order.attachment as attachment")
				->from('purchases_order')
                ->join('purchases_request', 'purchases_order.request_id = purchases_request.id', 'left')
				->join($v1, 'purchase_order_items.purchase_id = erp_purchases_order.id')
                ->join($v2, 'erp_purchases_order.supplier_id = erp_sup_name.id', 'left')
                ->join('companies', 'purchases_order.biller_id = companies.id', 'left');
			if(isset($_REQUEST['d'])){
				$date_c = date('Y-m-d', strtotime('+3 months'));
				$date = $_GET['d'];
				$date1 = str_replace("/", "-", $date);
				$date =  date('Y-m-d', strtotime($date1));

				$this->datatables
				->where("date >=", $date)
				->where('DATE_SUB(date, INTERVAL 1 DAY) <= CURDATE()')
				->where('purchases_order.payment_term <>', 0);
			}
        }

		if (!$this->Customer && !$this->Supplier && !$this->Owner && !$this->Admin && !$this->session->userdata('view_right')) {
            $this->datatables->where('purchases_order.created_by', $this->session->userdata('user_id'));
        } elseif ($this->Customer) {
            $this->datatables->where('customer_id', $this->session->userdata('user_id'));
        }

		if ($user_query) {
			$this->datatables->where('purchases_order.created_by', $user_query);
		}
		if ($product) {
			$this->datatables->like('purchase_order_items.product_id', $product);
		}
		if ($supplier) {
			$this->datatables->where('purchases_order.supplier_id', $supplier);
		}
		if ($warehouse) {
			$this->datatables->where('purchases_order.warehouse_id', $warehouse);
		}
		if ($reference_no) {
			$this->datatables->like('purchases_order.reference_no', $reference_no, 'both');
		}
		if ($start_date) {
			$this->datatables->where($this->db->dbprefix('purchases_order').'.date BETWEEN "' . $start_date . ' 00:00:00" and "' . $end_date . ' 23:59:00"');
		}
		if ($note) {
			$this->datatables->like('purchases_order.note', $note, 'both');
		}

        $this->datatables->add_column("Actions", $action, "id");
        echo $this->datatables->generate();
    }

	function purchase_order_alerts($warehouse_id = NULL)
	{
		$this->load->model('reports_model');
        $this->data['warehouse_id'] = $warehouse_id;
		$this->data['users'] = $this->reports_model->getStaff();
        $this->data['warehouses'] = $this->site->getAllWarehouses();
        $this->data['billers'] = $this->site->getAllCompanies('biller');
        $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
		$bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('purchases'), 'page' => lang('purchases')), array('link' => '#', 'page' => lang('list_purchase_order_alerts')));
		$meta = array('page_title' => lang('list_purchase_order_alerts'), 'bc' => $bc);
		$this->page_construct('purchases/purchase_order_alerts', $meta, $this->data);
    }

    function getPurchaseOrderAlerts($warehouse_id = NULL)
	{
        $this->erp->checkPermissions('index',null,'purchases_order');
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

        $detail_link = anchor('purchases/view_po/$1', '<i class="fa fa-file-text-o"></i> ' . lang('purchase_order_details'));
        $payments_link = anchor('purchases/payments/$1', '<i class="fa fa-money"></i> ' . lang('view_payments'), 'data-toggle="modal" data-target="#myModal"');
        $add_payment_link = anchor('purchases/add_payment/$1', '<i class="fa fa-money"></i> ' . lang('add_deposit'), 'data-toggle="modal" data-target="#myModal"');
		$ordered_link = anchor('purchases/update_purchases_Order/$1', '<i class="fa fa-check"></i> ' . lang('approve'), '');
		$unordered_link = anchor('purchases/Unapproved/$1', '<i class="fa fa-check"></i> ' . lang('unapproved'), '');
		$reject = anchor('purchases/reject/$1', '<i class="fa fa-times"></i> ' . lang('reject'), '');
		$unreject = anchor('purchases/unreject/$1', '<i class="fa fa-check"></i> ' . lang('unreject'), '');
        $email_link = anchor('purchases/email/$1', '<i class="fa fa-envelope"></i> ' . lang('email_purchase'), 'data-toggle="modal" data-target="#myModal"');
        $edit_link = anchor('purchases/edit_purchase_order/$1', '<i class="fa fa-edit"></i> ' . lang('edit_purchase_order'));
        $add_link = anchor('purchases/add/$1', '<i class="fa fa-edit add_or"></i> ' . lang('add_purchase'));
		$pdf_link = anchor('purchases/pdf/$1', '<i class="fa fa-file-pdf-o"></i> ' . lang('download_pdf'));
        $print_barcode = anchor('products/print_barcodes/?purchase=$1', '<i class="fa fa-print"></i> ' . lang('print_barcodes'));
		$purchase_return = anchor('purchases/return_purchase_order/$1', '<i class="fa fa-angle-double-left"></i> ' . lang('return_purchase_order'));
        $delete_link = "<a href='#' class='po' title='<b>" . $this->lang->line("delete_purchase") . "</b>' data-content=\"<p>"
        . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('purchases/delete/$1') . "'>"
        . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i> "
        . lang('delete_purchase') . "</a>";

        $action = '<div class="text-center"><div class="btn-group text-left">'
        . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
        . lang('actions') . ' <span class="caret"></span></button>
        <ul class="dropdown-menu pull-right" role="menu">

            <li>' . $detail_link . '</li>'

            .(($this->Owner || $this->Admin) ? '<li class="edit">'.$edit_link.'</li>' : ($this->GP['purchases_order-edit'] ? '<li class="edit">'.$edit_link.'</li>' : '')).
            (($this->Owner || $this->Admin) ? '<li class="approved">'.$ordered_link.'</li>' : ($this->GP['purchase_order-authorize'] ? '<li class="approved">'.$ordered_link.'</li>' : '')).
            (($this->Owner || $this->Admin) ? '<li class="unapproved">'.$unordered_link.'</li>' : ($this->GP['purchase_order-authorize'] ? '<li class="unapproved">'.$unordered_link.'</li>' : '')).
            (($this->Owner || $this->Admin) ? '<li class="reject">'.$reject.'</li>' : ($this->GP['purchase_order-authorize'] ? '<li class="reject">'.$reject.'</li>' : '')).
			(($this->Owner || $this->Admin) ? '<li class="add">'.$add_link.'</li>' : ($this->GP['purchases_order-add'] ? '<li class="add">'.$add_link.'</li>' : '')).

		'</ul>
    </div></div>';

		$v1 = "(
			SELECT
				purchase_id,
				CASE
			WHEN sum(quantity) <= sum(quantity_po) THEN
				'received'
			ELSE
				CASE
			WHEN (
				sum(quantity_po) > 0 && sum(quantity_po) < sum(quantity)
			) THEN
				'partial'
			ELSE
				'ordered'
			END
			END AS `status`
			FROM
				erp_purchase_order_items
			GROUP BY
				purchase_id
		) AS erp_purchase_order_items ";
		$v2 = "(
            SELECT
                erp_companies.id,
                erp_companies.`name`
            FROM
                erp_companies
            LEFT JOIN erp_purchases_order ON erp_purchases_order.supplier_id = erp_companies.id
            GROUP BY erp_companies.id
        ) AS erp_sup_name ";
        $biller_id = $this->session->userdata('biller_id');

        $this->load->library('datatables');

        if ($warehouse_id) {
            $this->datatables
				->select("purchases_order.id as id,
						purchases_order.date,
						purchases_order.reference_no,
						purchases_request.reference_no as purchase_ref,
                        companies.name as project,
						IF (erp_purchases_order.supplier = '',sup_name.name,erp_purchases_order.supplier) AS supplier,
						erp_purchase_order_items.status,
						purchases_order.grand_total,						
						purchases_order.payment_status,
                        purchases_order.order_status,
						purchases_order.status  as ordered
						
						")
				->from('purchases_order')
                ->join('purchases_request', 'purchases_order.request_id = purchases_request.id', 'left')
                ->join($v1, 'purchase_order_items.purchase_id = erp_purchases_order.id')
				->join($v2, 'erp_purchases_order.supplier_id = erp_sup_name.id', 'left')
                ->join('companies', 'purchases_order.biller_id = companies.id', 'left')
                ->join('users', 'purchases_order.created_by = users.id', 'left')
				->where('purchases_order.status','pending')
                ->where('purchases_order.biller_id', $biller_id);

                if (count($warehouse_ids) > 1) {
                    $this->datatables->where_in('erp_purchases_order.warehouse_id', $warehouse_ids);
                } else {
                    $this->datatables->where('erp_purchases_order.warehouse_id', $warehouse_id);
                }

        } else {
			$this->datatables
				->select("purchases_order.id as id,
						purchases_order.date,
						purchases_order.reference_no,
						purchases_request.reference_no as purchase_ref,
                        companies.company as project,
						IF (erp_purchases_order.supplier = '',sup_name.name,erp_purchases_order.supplier) AS supplier,
						erp_purchase_order_items.status,
						purchases_order.grand_total,						
						purchases_order.payment_status,
						purchases_order.order_status,
                        purchases_order.status as ordered")
				->from('purchases_order')
                ->join('purchases_request', 'purchases_order.request_id = purchases_request.id', 'left')
				->join($v1, 'purchase_order_items.purchase_id = erp_purchases_order.id')
                ->join($v2, 'erp_purchases_order.supplier_id = erp_sup_name.id', 'left')
                ->join('companies', 'purchases_order.biller_id = companies.id', 'left')
				->where('purchases_order.status','pending');
			if(isset($_REQUEST['d'])){
				$date_c = date('Y-m-d', strtotime('+3 months'));
				$date = $_GET['d'];
				$date1 = str_replace("/", "-", $date);
				$date =  date('Y-m-d', strtotime($date1));

				$this->datatables
				->where("date >=", $date)
				->where('DATE_SUB(date, INTERVAL 1 DAY) <= CURDATE()')
				->where('purchases_order.payment_term <>', 0);
			}
        }

		if (!$this->Customer && !$this->Supplier && !$this->Owner && !$this->Admin && !$this->session->userdata('view_right')) {
            $this->datatables->where('purchases_order.created_by', $this->session->userdata('user_id'));
        } elseif ($this->Customer) {
            $this->datatables->where('customer_id', $this->session->userdata('user_id'));
        }

		if ($user_query) {
			$this->datatables->where('purchases_order.created_by', $user_query);
		}
		if ($product) {
			$this->datatables->like('purchase_order_items.product_id', $product);
		}
		if ($supplier) {
			$this->datatables->where('purchases_order.supplier_id', $supplier);
		}
		if ($warehouse) {
			$this->datatables->where('purchases_order.warehouse_id', $warehouse);
		}
		if ($reference_no) {
			$this->datatables->like('purchases_order.reference_no', $reference_no, 'both');
		}
		if ($start_date) {
			$this->datatables->where($this->db->dbprefix('purchases_order').'.date BETWEEN "' . $start_date . ' 00:00:00" and "' . $end_date . ' 23:59:00"');
		}
		if ($note) {
			$this->datatables->like('purchases_order.note', $note, 'both');
		}

        $this->datatables->add_column("Actions", $action, "id");
        echo $this->datatables->generate();
    }

	public function reject($id=null){
		$status='reject';
		$this->db->set('status',$status);
		$this->db->where('erp_purchases_order.id',$id);
		if($this->db->Update('erp_purchases_order')){
	      redirect($_SERVER["HTTP_REFERER"]);
		}
	}

	public function unreject($id=null){
		$status='pending';
		$this->db->set('status',$status);
		$this->db->where('erp_purchases_order.id',$id);
		if($this->db->Update('erp_purchases_order')){
			redirect($_SERVER["HTTP_REFERER"]);
		}
	}

    public function add_purchase_order($quote_id = null, $sale_order_id= null)
    {
        $this->erp->checkPermissions('add', Null, 'purchases_order');
        $this->form_validation->set_message('is_natural_no_zero', $this->lang->line("no_zero_required"));
        $this->form_validation->set_rules('warehouse', $this->lang->line("warehouse"), 'required|is_natural_no_zero');
        $this->form_validation->set_rules('supplier', $this->lang->line("supplier"), 'required');
        $this->form_validation->set_rules('biller', $this->lang->line("biller"), 'required');
		$this->form_validation->set_rules('reference_no', $this->lang->line("reference_no"), 'required|is_unique[purchases_order.reference_no]');

		$this->load->model('purchases_request_model');
        $this->session->unset_userdata('csrf_token');
        if ($this->form_validation->run() == true) {
            $quantity 	= "quantity";
            $product 	= "product";
            $unit_cost 	= "unit_cost";
            $tax_rate 	= "tax_rate";
			$biller_id 	= $this->input->post('biller');
            $reference 	= $this->input->post('reference_no') ? $this->input->post('reference_no') : $this->site->getReference('poa',$biller_id);
			$reference_no_request = $this->input->post('reference_no_request');
			// $payment_term = $this->input->post('payment_term');

            if ($this->Owner || $this->Admin || $this->Settings->allow_change_date == 1) {
                $date = $this->erp->fld(trim($this->input->post('date')));
            } else {
                $date = date('Y-m-d H:i:s');
            }

			$payment_status 	= $this->input->post('payment_status');
            $payment_term       = $this->input->post('payment_term');
            $warehouse_id 		= $this->input->post('warehouse');
            $supplier_id 		= $this->input->post('supplier');
			$rsupplier_id 		= $this->input->post('rsupplier_id');
			$sale_order_id 		= $this->input->post('sale_order_id');
            $shipping 			= $this->input->post('shipping') ? $this->input->post('shipping') : 0;
			$supplier_details 	= $this->site->getCompanyByID($supplier_id);
            $supplier 			= $supplier_details->company != '-'  ? $supplier_details->company : $supplier_details->name;
            $note 				= $this->input->post('note');
			$variant_id 		= $this->input->post('variant_id');
            $total 				= 0;
            $product_tax 		= 0;
            $order_tax 			= 0;
            $product_discount 	= 0;
            $order_discount 	= 0;
            $percentage 		= '%';
            $i = sizeof($_POST['product']);
            for ($r = 0; $r < $i; $r++) {
                $item_code 		= $_POST['product'][$r];
                $item_net_cost 	= $_POST['net_cost'][$r];
                $unit_cost 		= $_POST['unit_cost'][$r];
				$unit_cost_real = $unit_cost;
                $real_unit_cost = $_POST['real_unit_cost'][$r];

                $item_quantity 	= $_POST['quantity'][$r];
				$serial_no 		= $_POST['serial'][$r];
                $p_supplier 	= $_POST['rsupplier_id'][$r];
				$create_id 		= $_POST['create_id'][$r];
				$p_price 		= $_POST['price'][$r];
                $p_type 		= $_POST['type'][$r];
				$tax_method		= $_POST['tax_method'][$r];
				$item_piece		= $_POST['piece'][$r];
				$item_wpiece	= $_POST['wpiece'][$r];
                $item_option 	= isset($_POST['product_option'][$r]) ? $_POST['product_option'][$r] : NULL;
				if ($item_option == 'undefined') {
					$item_option = NULL;
				}
                $item_tax_rate 	= isset($_POST['product_tax'][$r]) ? $_POST['product_tax'][$r] : null;
                $item_discount 	= isset($_POST['product_discount'][$r]) ? $_POST['product_discount'][$r] : null;
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
							$pr_discount = ((($unit_cost) * (Float) ($pds[0])) / 100);
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
					$ptax_method 		= ($tax_method == ""? $product_details->tax_method:$tax_method);

                    if (isset($item_tax_rate) && $item_tax_rate != 0) {
						$pr_tax = $item_tax_rate;
						$tax_details = $this->site->getTaxRateByID($pr_tax);
						if ($tax_details->type == 1 && $tax_details->rate != 0) {

							if ($product_details && $ptax_method == 1) {
								$item_tax       = (($unit_cost) * $tax_details->rate) / 100;
								$tax            = $tax_details->rate . "%";
								$item_net_cost  = $unit_cost;
							} else {
								$item_tax       = (($unit_cost) * $tax_details->rate) / (100 + $tax_details->rate);
								$tax            = $tax_details->rate . "%";
								$item_net_cost  = $unit_cost - $item_tax;
							}

						} elseif ($tax_details->type == 2) {

							if ($product_details && $ptax_method == 1) {
								$item_tax       = (($unit_cost) * $tax_details->rate) / 100;
								$tax            = $tax_details->rate . "%";
								$item_net_cost  = $unit_cost;
							} else {
								$item_tax       = (($unit_cost) * $tax_details->rate) / (100 + $tax_details->rate);
								$tax            = $tax_details->rate . "%";
								$item_net_cost  = $unit_cost - $item_tax;
							}

							$item_tax = ($tax_details->rate);
							$tax = $tax_details->rate;
						}

						$pr_item_tax = $item_tax * $item_quantity;
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

					$products[] = array(
						'product_id' 		=> $product_details->id,
						'product_code' 		=> $item_code,
						'product_name' 		=> $product_details->name,
						//'product_type' 	=> $item_type,
						'option_id' 		=> $item_option,
						'net_unit_cost' 	=> $item_net_cost,  //$real_unit_cost,
						'unit_cost' 		=> $unit_cost_real, //kiry  - $pr_discount
						'quantity' 			=> $item_quantity,
						'quantity_received' => '0',
						'quantity_balance' 	=> $quantity_balance,
						'warehouse_id' 		=> $warehouse_id,
						'item_tax' 			=> $pr_item_tax,
						'tax_rate_id' 		=> $pr_tax,
						'tax_method'		=> $tax_method,
						'tax' 				=> $tax,
						'discount' 			=> $item_discount,
						'item_discount' 	=> $pr_item_discount,
						'subtotal' 			=> $subtotal,
						'expiry' 			=> $item_expiry,
						'real_unit_cost' 	=> $real_unit_cost,
						'date' 				=> date('Y-m-d', strtotime($date)),
                        'status' 			=> ($this->Settings->authorization == 'auto' ? 'approved' : 'pending'),
						'price' 			=> $p_price,
						'supplier_id' 		=> $p_supplier?$p_supplier:'',
                        'type'              => $p_type,
						'piece'				=> $item_piece,
						'wpiece'			=> $item_wpiece
					);

					$serial[] = array(
						'product_id'    => $product_details->id,
						'serial_number' => $serial_no,
						'warehouse'     => $warehouse_id,
                        'biller_id' => $biller_id,
						'serial_status' => 1
					);
                    $total += $subtotal;
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
                    $order_discount = (($total * (Float) ($ods[0])) / 100);
                } else {
					$order_discount = (($total * (Float) ($order_discount_id)) / 100);
                    //$order_discount = $this->erp->formatPurDecimal($order_discount_id);
                }
            } else {
                $order_discount_id = null;
            }

            $total_discount = ($order_discount + $product_discount);

            if ($this->Settings->tax2 != 0) {
                $order_tax_id = $this->input->post('order_tax');
                if ($order_tax_details = $this->site->getTaxRateByID($order_tax_id)) {
                    if ($order_tax_details->type == 2) {
                        $order_tax = ($order_tax_details->rate);
                    }
                    if ($order_tax_details->type == 1) {
                        $order_tax = ((($total + $shipping - $order_discount) * $order_tax_details->rate) / 100);
                    }
                }
            } else {
                $order_tax_id = null;
            }

            $total_tax 		= ($product_tax + $order_tax);
            $grand_total 	= ($total + $order_tax + $shipping - $order_discount);

			$data = array(
				'biller_id' 		=> $biller_id,
				'reference_no' 		=> $reference,
				'purchase_ref' 		=> $reference_no_request,
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
				'status' 			=> ($this->Settings->authorization == 'auto' ? 'approved' : 'pending'),
				'payment_status' 	=> $payment_status,
				'created_by' 		=> $this->session->userdata('user_id'),
				'request_id' 		=> $this->input->post('request_id')

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

			if ($payment_status == 'partial' || $payment_status == 'paid') {
                if ($this->input->post('paid_by') == 'gift_card') {
                    $gc = $this->site->getGiftCardByNO($this->input->post('gift_card_no'));
                    $amount_paying = $grand_total >= $gc->balance ? $gc->balance : $grand_total;
                    $gc_balance = $gc->balance - $amount_paying;

					$payment = array(
						'date' 			=> $date,
						'reference_no' 	=>'',
						'amount' 		=> ($amount_paying),
						'paid_by' 		=> $this->input->post('paid_by'),
						'cheque_no' 	=> $this->input->post('cheque_no'),
						'cc_no' 		=> $this->input->post('gift_card_no'),
						'cc_holder' 	=> $this->input->post('pcc_holder'),
						'cc_month' 		=> $this->input->post('pcc_month'),
						'cc_year' 		=> $this->input->post('pcc_year'),
						'cc_type' 		=> $this->input->post('pcc_type'),
						'created_by' 	=> $this->session->userdata('user_id'),
						'note' 			=> $this->input->post('payment_note'),
						'type' 			=> 'received',
						'gc_balance' 	=> $gc_balance,
						'biller_id' 	=> $biller_id
					);
                    $data['paid'] = $amount_paying;
                } else {
					$payment = array(
						'date' 			=> $date,
						'reference_no' 	=>'',
						'amount' 		=> $this->input->post('amount-paid'),
						'paid_by' 		=> $this->input->post('paid_by'),
						'cheque_no' 	=> $this->input->post('cheque_no'),
						'cc_no' 		=> $this->input->post('pcc_no'),
						'cc_holder' 	=> $this->input->post('pcc_holder'),
						'cc_month' 		=> $this->input->post('pcc_month'),
						'cc_year' 		=> $this->input->post('pcc_year'),
						'cc_type' 		=> $this->input->post('pcc_type'),
						'created_by' 	=> $this->session->userdata('user_id'),
						'note' 			=> $this->input->post('payment_note'),
						'type' 			=> 'received',
                        'biller_id' => $biller_id
					);
                    $data['paid'] = $this->input->post('amount-paid');
                }
				if($_POST['paid_by'] == 'depreciation') {
					$no = sizeof($_POST['no']);
					$period = 1;
					for($m = 0; $m < $no; $m++){
						$dateline = date('Y-m-d', strtotime($_POST['dateline'][$m]));
						$loans[] = array(
							'period' 	=> $period,
							'sale_id' 	=> '',
							'interest' 	=> $_POST['interest'][$m],
							'principle' => $_POST['principle'][$m],
							'payment' 	=> $_POST['payment_amt'][$m],
							'balance' 	=> $_POST['balance'][$m],
							'type' 		=> $_POST['depreciation_type'],
							'rated' 	=> $_POST['depreciation_rate1'],
							'note' 		=> $_POST['note_1'][$m],
							'dateline' 	=> $dateline,
							'biller_id' => $biller_id
						);
						$period++;
					}
				}else{
					$loans = array();
				}

            } else {
                $payment = array();
            }
            //$this->erp->print_arrays($data, $products);
		}

        if ($this->form_validation->run() == true) {

			$purchase_order_id = $this->purchases_model->addPurchaseOrder($data, $products,$payment,$quote_id);
			if ($sale_order_id) {
                $this->db->update('sale_order', array('sale_status' => 'purchase_order'), array('id' => $sale_order_id));
            }
			if($this->Settings->purchase_serial){
				$this->purchases_model->addSerial($serial);
			}
			$this->session->set_userdata('remove_polso', '1');
            $this->session->set_flashdata('message', $this->lang->line("purchase_order_added"));
            redirect('purchases/purchase_order');
        } else {

            if ($sale_order_id) {

				if(($this->sale_order_model->getSaleOrder($sale_order_id)->order_status) == 'pending'){
				$this->session->set_flashdata('error', lang("sale_order_n_approved"));
					redirect($_SERVER["HTTP_REFERER"]);
				}
				if(($this->sale_order_model->getSaleOrder($sale_order_id)->order_status) == 'rejected'){
					$this->session->set_flashdata('error', lang("sale_order_has_been_rejected"));
					redirect($_SERVER["HTTP_REFERER"]);
				}

				if(($this->sale_order_model->getSaleOrder($sale_order_id)->sale_status) != 'order'){
					$this->session->set_flashdata('error', lang("sale_order_has_been_created"));
					redirect($_SERVER["HTTP_REFERER"]);
				}


                $sale_order = $this->sales_model->getSaleOrder($sale_order_id);
				$this->data['sale_order'] = $sale_order;
				$items = $this->sales_model->getSaleOrdItems($sale_order_id);
				$this->data['sale_order_id'] = $sale_order_id;
				$this->data['type'] = "sale_order";
				$this->data['type_id'] = $sale_order_id;
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
                    $row->id = $item->product_id;
                    $row->code = $item->product_code;
                    $row->type = $item->product_type;
                    $row->qty = $item->quantity;
                    $row->discount = $item->discount ? $item->discount : '0';
                    $row->price = $this->erp->formatDecimal($item->net_unit_price+$this->erp->formatDecimal($item->item_discount/$item->quantity));
                    $row->unit_price = $row->tax_method ? $item->unit_price+$this->erp->formatDecimal($item->item_discount/$item->quantity)+$this->erp->formatDecimal($item->item_tax/$item->quantity) : $item->unit_price+($item->item_discount/$item->quantity);
                    $row->real_unit_price = $item->real_unit_price;
                    $row->tax_rate = $item->tax_rate_id;
                    $row->serial = '';
                    $row->option = $item->option_id;
					$row->piece	 = $item->piece;
					$row->wpiece = $item->wpiece;

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
                        $tax_rate = $this->site->getTaxRateByID($row->tax_rate);
                        $pr[$ri] = array('id' => $c, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'combo_items' => $combo_items, 'tax_rate' => $tax_rate, 'options' => $options, 'makeup_cost' => 0,'group_prices'=>$group_prices, 'all_group_price' => $all_group_prices);
                    } else {
                        $pr[$ri] = array('id' => $c, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'combo_items' => $combo_items, 'tax_rate' => false, 'options' => $options, 'makeup_cost' => 0,'group_prices'=>$group_prices, 'all_group_price' => $all_group_prices);
                    }
                    $c++;
                }

                $payment_deposit    = null;
				$this->data['sale_order_id'] =$sale_order_id;
                $this->data['sale_order_items'] = json_encode($pr);
				$this->data['payment_deposit'] = $payment_deposit;
            }

			if ($quote_id) {

				$this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
				$request_id = $this->purchases_request_model->getPurchaseRequestByID($quote_id);
				if($request_id->status == "requested"){
					$this->session->set_flashdata('error', "Purchase request did not approved.");
                    redirect('purchases_request');
				}
				else if($request_id->order_status == "completed"){
					$this->session->set_flashdata('error', "Purchase request is completed.");
                    redirect('purchases_request');
				}
				else{
				$this->data['inv'] = $request_id;
				$inv_items = $this->purchases_request_model->getAllPurchaseRequestItems_create($quote_id);
				$pref = $this->purchases_model->getPaymentByPurchaseID($quote_id);

				$c = rand(100000, 9999999);
				foreach ($inv_items as $item) {
					$row = $this->site->getProductByID($item->product_id);
					$row->expiry = (($item->expiry && $item->expiry != '0000-00-00') ? $this->erp->fsd($item->expiry) : '');
					$row->qty = $item->quantity;

					$row->received = $item->quantity_received ? $item->quantity_received : $item->quantity;
					$row->quantity_balance = $item->quantity_balance + ($item->quantity-$row->received);
					$row->discount 	= $item->discount ? $item->discount : '0';
					$options 		= $this->purchases_model->getProductOptions($row->id);
					$row->option 	= $item->option_id;
					$row->real_unit_cost = $item->real_unit_cost;
					$row->price 	= $item->price;
					$row->cost 		= $this->erp->formatPurDecimal($item->net_unit_cost + ($item->item_discount / $item->quantity));
					$row->tax_rate 	= $item->tax_rate_id;
					$row->net_cost 	= $item->unit_cost;
					$row->tax_method = $item->tax_method;
					$row->piece	 	= $item->piece;
					$row->wpiece 	= $item->wpiece;

					$pii = $this->purchases_model->getPurcahseItemByPurchaseID($quote_id);

					unset($row->details, $row->product_details, $row->file, $row->product_group_id);
                    $ri = $this->Settings->item_addition ? $c : $c;
					if ($row->tax_rate) {
						$tax_rate = $this->site->getTaxRateByID($row->tax_rate);
						$pr[$ri] = array('id' => $c, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'tax_rate' => $tax_rate, 'options' => $options, 'suppliers' => '' ,'supplier_id' => $item->supplier_id,'create_id'=>$item->id);
					} else {
						$pr[$ri] = array('id' => $c, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'tax_rate' => false, 'options' => $options, 'suppliers' => '' ,'supplier_id' => $item->supplier_id,'create_id'=>$item->id);
					}
					$c++;
				}

                    $this->data['quote_items'] = json_encode($pr);

				$this->data['id'] = $quote_id;

				$this->data['suppliers'] = $this->site->getAllCompanies('supplier');

				$this->data['purchase'] = $this->purchases_request_model->getPurchaseRequestByID($quote_id);


				}
            }

			$this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
			$this->data['quote_id'] 	= $quote_id;

            $this->data['categories'] 	= $this->site->getAllCategories();
			$this->data['unit'] 		= $this->purchases_model->getUnits();
			$this->data['customers']	= $this->site->getCustomers();
            $this->data['tax_rates'] 	= $this->site->getAllTaxRates();
            $this->data['warehouses'] 	= $this->site->getAllWarehouses();
            $this->data['payment_term'] = $this->site->getAllPaymentTerm();

			$this->data['currency'] 	= $this->site->getCurrency();
			$this->data['suppliers'] 	= $this->site->getAllSuppliers('supplier');
			$this->data['payment_term'] = $this->site->getAllPaymentTerm();
			$this->data['billers'] 		= $this->site->getAllCompanies('biller');

			if ($this->Owner || $this->Admin || !$this->session->userdata('biller_id')){
				$biller_id = $this->site->get_setting()->default_biller;
				$this->data['ponumber'] = $this->site->getReference('poa',$biller_id);
			}else{
				$biller_id = $this->session->userdata('biller_id');
				$this->data['ponumber'] = $this->site->getReference('poa',$biller_id);
			}

            $this->load->helper('string');
            $value = random_string('alnum', 20);
            $this->session->set_userdata('user_csrf', $value);
            $this->data['csrf'] = $this->session->userdata('user_csrf');
            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('purchases'), 'page' => lang('purchases_order')), array('link' => '#', 'page' => lang('add_purchase_order')));
            $meta = array('page_title' => lang('add_purchase_order'), 'bc' => $bc);
            $this->page_construct('purchases/add_purchase_order', $meta, $this->data);
        }
    }

	public function supplier_opening_balance()
    {
        $this->erp->checkPermissions('csv');
        $this->load->helper('security');
        $this->form_validation->set_message('is_natural_no_zero', $this->lang->line("no_zero_required"));
        $this->form_validation->set_rules('userfile', $this->lang->line("upload_file"), 'xss_clean');

        if ($this->form_validation->run() == true) {

            $quantity = "quantity";
            $product = "product";
            $unit_cost = "unit_cost";
            $tax_rate = "tax_rate";

            $total = 0;
            $product_tax = 0;
            $order_tax = 0;
            $product_discount = 0;
            $order_discount = 0;
            $percentage = '%';

            if (isset($_FILES["userfile"])) {
                $this->load->library('upload');

                $config['upload_path'] = $this->digital_upload_path;
                $config['allowed_types'] = 'csv';
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = true;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect("purchases/supplier_opening_balance");
                }

                $csv = $this->upload->file_name;

                $arrResult = array();
                $handle = fopen($this->digital_upload_path . $csv, "r");
                if ($handle) {
                    while (($row = fgetcsv($handle, 1000, ",")) !== false) {
                        $arrResult[] = $row;
                    }
                    fclose($handle);
                }
                $titles = array_shift($arrResult);

                $keys = array('supplier_no', 'reference', 'opening_date', 'shop_id', 'warehouse_id', 'payment_term', 'balance', 'deposit');

                $final = array();
                foreach ($arrResult as $key => $value) {
                    $final[] = array_combine($keys, $value);
                }

                $rw = 2;
				$dp = '';
				$syncda = array();
                foreach ($final as $csv_pr) {
                    $dp = $this->site->getDepositsByID($csv_pr['supplier_no']);
					$supplier = $this->site->getCompanyByID($csv_pr['supplier_no']);
					$biller = $this->site->getCompanyByID($csv_pr['shop_id']);

					if(trim($supplier->group_name) != 'supplier'){
						$this->session->set_flashdata('error', $this->lang->line("supplier_no_does_not_exist.") . ' (Line : ' . $rw . ')');
						redirect($_SERVER['HTTP_REFERER']);
					}

					if(trim($biller->group_name) != 'biller'){
						$this->session->set_flashdata('error', $this->lang->line("biller_no_does_not_exist.") . ' (Line : ' . $rw . ')');
						redirect($_SERVER['HTTP_REFERER']);
					}

					//$date = $this->erp->fld($csv_pr['opening_date']);
					$date = strtr($csv_pr['opening_date'], '/', '-');
                    $date = date('Y-m-d H:i:s', strtotime($date));
					$amount = $dp? $dp->deposit:0;
					$deposit = $csv_pr['deposit'];
					$deposits[] = array(
						'company_id' =>  $csv_pr['supplier_no'],
						'updated_by' =>  $this->session->userdata('user_id'),
						'updated_at' =>  date('Y-m-d h:i:s'),
						'date' 		 =>  date('Y-m-d h:i:s'),
						'created_by' => $this->session->userdata('user_id'),
						'amount'     =>  $deposit,
						'biller_id'  => $csv_pr['shop_id'],
						'reference'  => $csv_pr['reference'],
						'paid_by'	 => 'deposit',
						'note'       => 'supplier opening balance',
						'opening'	 => 1
					);


					$purchase[] = array(
						'reference_no'  => $csv_pr['reference'],
						'date'          => $date,
						'biller_id'     => $csv_pr['shop_id'],
						'supplier_id'	=> $supplier->id,
						'supplier'		=> $supplier->name,
                        'warehouse_id' => $csv_pr['warehouse_id'],
						'opening_ap'    => 1,
						'total'         => $csv_pr['balance'],
						'stotal'         => $csv_pr['balance'],
						'grand_total'   => $csv_pr['balance'],
						'status'   		=> 'received',
						'payment_status'=> 'due',
						'payment_term'  => $csv_pr['payment_term'],
						'created_by'    => $this->session->userdata('user_id')
					);
                    //$this->erp->print_arrays($purchase);
					$syncda[] = $csv_pr['supplier_no'];
                }
            }
        }

		if ($this->form_validation->run() == true ) {
			$this->purchases_model->addOpeningAP($purchase, $deposits, $syncda);
            $this->session->set_flashdata('message', $this->lang->line("supplier_opening_balance"));
            redirect("purchases/supplier_opening_balance");
        } else {

            $data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

            $this->data['warehouses'] = $this->site->getAllWarehouses();
            $this->data['tax_rates'] = $this->site->getAllTaxRates();
            $this->data['ponumber'] = $this->site->getReference('po');

            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('purchases'), 'page' => lang('purchases')), array('link' => '#', 'page' => lang('supplier_opening_balance')));
            $meta = array('page_title' => lang('supplier_opening_balance'), 'bc' => $bc);
            $this->page_construct('purchases/supplier_opening_balance', $meta, $this->data);
		}
    }

	public function pdf_order($purchase_id = null, $view = null, $save_bufffer = null)
    {

        if ($this->input->get('id')) {
            $purchase_id = $this->input->get('id');
        }

        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
         $inv = $this->purchases_model->getPurchaseOrderByID($purchase_id);
        if (!$this->session->userdata('view_right')) {
            $this->erp->view_rights($inv->created_by);
        }
        $this->data['rows'] = $this->purchases_model->getAllPurchaseOrderItems($purchase_id);
        $this->data['supplier'] = $this->site->getCompanyByID($inv->supplier_id);
        $this->data['warehouse'] = $this->site->getEmployees($inv->biller_id);
        $this->data['created_by'] = $this->site->getUser($inv->created_by);
        $this->data['inv'] = $inv;
        $name = $this->lang->line("purchase_order") . "_" . str_replace('/', '_', $inv->reference_no) . ".pdf";
        $html = $this->load->view($this->theme . 'purchases/pdf_order', $this->data, true);
        if ($view) {
            $this->load->view($this->theme . 'purchases/pdf_order', $this->data);
        } elseif ($save_bufffer) {
            return $this->erp->generate_pdf($html, $name, $save_bufffer);
        } else {
            $this->erp->generate_pdf($html, $name);
        }

    }

	public function update_purchases_Order($request_id=null){
		    $statu="approved";
		    $this->db->set('status',$statu);
			$this->db->where('id', $request_id);
			$update=$this->db->update('erp_purchases_order');
			$this->session->set_flashdata('message', $this->lang->line("purchases is ordered already."));
		redirect($_SERVER["HTTP_REFERER"]);
	}

	public function Unapproved($request_id=null){
		$status="pending";
		$this->db->set('status',$status);
		$this->db->where('id',$request_id);
		$update=$this->db->update('erp_purchases_order');
		$this->session->set_flashdata('message', $this->lang->line("purchases is ordered already."));
	redirect($_SERVER["HTTP_REFERER"]);
	}

	public function suggestionsStock()
    {
        $term 			= $this->input->get('term', true);
        $supplier_id 	= $this->input->get('supplier_id', true);
		$category_id 	= $this->input->get('category_id', true);
		$warehouse_id 	= $this->input->get('warehouse_id', true);

        if (strlen($term) < 1 || !$term) {
            die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . site_url('welcome') . "'; }, 10);</script>");
        }

        $spos = strpos($term, '_');
        if ($spos !== false) {
            $st 	= explode("_", $term);
            $sr 	= trim($st[0]);
            $opt_id = trim($st[1]);
        } else {
            $sr 	= $term;
            $opt_id = '';
        }

		$user_setting 	= $this->site->getUserSetting($this->session->userdata('user_id'));
        $rows 			= $this->purchases_model->getProductStock($sr, $user_setting->purchase_standard, $user_setting->purchase_combo, $user_setting->purchase_digital, $user_setting->purchase_service, $user_setting->purchase_category, $warehouse_id, $category_id, $opt_id);
        if ($rows) {
            $c = str_replace(".", "", microtime(true));
            $r = 0;
            foreach ($rows as $row) {
                $row->qty = 1;
                $pr[] = array('id' => ($c + $r), 'item_id' => $row->id, 'label' => $row->name , 'qty' => $row->qty, 'code' => $row->code, 'quantity' => $row->quantity, 'variant' => $row->variant, 'option_id' => $row->option_id);
                $r++;
            }
            echo json_encode($pr);
        } else {
            echo json_encode(array(array('id' => 0, 'label' => lang('no_match_found'), 'value' => $term)));
        }
    }

    function invoice_add_samet($purchase_id = null)
	{
        $this->erp->checkPermissions('index', true, 'purchases');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $this->load->model('pos_model');
        $this->data['setting'] = $this->site->get_setting();
        $this->data['pos'] = $this->pos_model->getSetting();
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
		$inv = $this->purchases_model->getPurchaseByID($purchase_id);
		//$this->erp->print_arrays($inv);


        $this->data['barcode'] = "<img src='" . site_url('products/gen_barcode/' . $inv->reference_no) . "' alt='" .$inv->reference_no . "' class='pull-left' />";
        $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
        $this->data['payments'] = $this->sales_model->getPaymentsForSale($id);
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
        $this->data['user'] = $this->site->getUser($inv->created_by);
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);

        $this->data['rows'] = $this->purchases_model->getAllPurchaseItems($purchase_id);
        $this->data['supplier'] = $this->site->getCompanyByID($inv->supplier_id);
        $this->data['warehouse'] = $this->site->getEmployees($inv->biller_id);
        $this->data['inv'] = $inv;
        $this->data['payments'] = $this->purchases_model->getPaymentsForPurchase($purchase_id);
        $this->data['created_by'] = $this->site->getUser($inv->created_by);
        $this->data['updated_by'] = $inv->updated_by ? $this->site->getUser($inv->updated_by) : null;
        $this->load->view($this->theme .'purchases/invoice_add_samet',$this->data);
    }

    function invoice_purchase_chea_kheng($purchase_id = null)
	{
        $this->erp->checkPermissions('index', true, 'purchases');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $this->load->model('pos_model');
        $this->data['setting'] = $this->site->get_setting();
        $this->data['pos'] = $this->pos_model->getSetting();
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
		$inv = $this->purchases_model->getPurchaseByID($purchase_id);
		//$this->erp->print_arrays($inv);


        $this->data['barcode'] = "<img src='" . site_url('products/gen_barcode/' . $inv->reference_no) . "' alt='" .$inv->reference_no . "' class='pull-left' />";
        $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
        $this->data['payments'] = $this->sales_model->getPaymentsForSale($id);
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
        $this->data['user'] = $this->site->getUser($inv->created_by);
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);


        $this->data['rows'] = $this->purchases_model->getAllPurchaseItems($purchase_id);
        $this->data['supplier'] = $this->site->getCompanyByID($inv->supplier_id);
        $this->data['warehouse'] = $this->site->getEmployees($inv->biller_id);
        $this->data['inv'] = $inv;
        $this->data['payments'] = $this->purchases_model->getPaymentsForPurchase($purchase_id);
        $this->data['created_by'] = $this->site->getUser($inv->created_by);
        $this->data['updated_by'] = $inv->updated_by ? $this->site->getUser($inv->updated_by) : null;
        $this->load->view($this->theme .'purchases/invoice_purchase_chea_kheng',$this->data);
    }

	public function invoice_purchase_receive_kh_chea_kheng($purchase_id = null)
    {
        $this->erp->checkPermissions('index', true, 'purchases');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $this->load->model('pos_model');
        $this->data['setting'] = $this->site->get_setting();
        $this->data['pos'] = $this->pos_model->getSetting();
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
		$inv = $this->purchases_model->getPurchaseByID($purchase_id);
		//$this->erp->print_arrays($inv);


        $this->data['barcode'] = "<img src='" . site_url('products/gen_barcode/' . $inv->reference_no) . "' alt='" .$inv->reference_no . "' class='pull-left' />";
        $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
        $this->data['payments'] = $this->sales_model->getPaymentsForSale($id);
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
        $this->data['user'] = $this->site->getUser($inv->created_by);
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);


        $this->data['rows'] = $this->purchases_model->getAllPurchaseItems($purchase_id);
        $this->data['supplier'] = $this->site->getCompanyByID($inv->supplier_id);
        $this->data['warehouse'] = $this->site->getEmployees($inv->biller_id);
        $this->data['inv'] = $inv;
        $this->data['payments'] = $this->purchases_model->getPaymentsForPurchase($purchase_id);
        $this->data['created_by'] = $this->site->getUser($inv->created_by);
        $this->data['updated_by'] = $inv->updated_by ? $this->site->getUser($inv->updated_by) : null;
        $this->load->view($this->theme .'purchases/invoice_purchase_receive_kh_chea_kheng',$this->data);

    }

    public function payment_receipt($id = null,$purchase_id = null)
    {
        $this->erp->checkPermissions();
		$inv = $this->purchases_model->getPurchaseByID($purchase_id);
		//$this->erp->print_arrays($inv);
        $this->data['id'] = $id;
		$this->data['inv'] = $inv;
        $this->data['payments'] = $this->purchases_model->getPurchasePayments($id);
        $this->load->view($this->theme . 'purchases/payment_receipt', $this->data);
    }

    public function invoice_add_purchase_order($purchase_id = null)
    {
       $this->erp->checkPermissions('index', true, 'purchases');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $this->load->model('pos_model');
        $this->data['setting'] = $this->site->get_setting();
        $this->data['pos'] = $this->pos_model->getSetting();
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
		$inv = $this->purchases_model->getPurchaseOrderByID($purchase_id);
		//$this->erp->print_arrays($inv);


        $this->data['barcode'] = "<img src='" . site_url('products/gen_barcode/' . $inv->reference_no) . "' alt='" .$inv->reference_no . "' class='pull-left' />";
        $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
        $this->data['payments'] = $this->sales_model->getPaymentsForSale($id);
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
        $this->data['user'] = $this->site->getUser($inv->created_by);
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);


       $this->data['rows'] = $this->purchases_model->getAllPurchaseOrderItems($purchase_id);
        $this->data['supplier'] = $this->site->getCompanyByID($inv->supplier_id);
        $this->data['warehouse'] = $this->site->getEmployees($inv->biller_id);
        $this->data['inv'] = $inv;
        $this->data['payments'] = $this->purchases_model->getPaymentsForPurchase($purchase_id);
        $this->data['created_by'] = $this->site->getUser($inv->created_by);
        $this->data['updated_by'] = $inv->updated_by ? $this->site->getUser($inv->updated_by) : null;
        $this->load->view($this->theme . 'purchases/invoice_add_purchase_order', $this->data);
    }

}
