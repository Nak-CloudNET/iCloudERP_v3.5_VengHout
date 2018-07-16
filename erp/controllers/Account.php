<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Account extends MY_Controller
{
	//********* Kindly to inform for beautiful code first before coding , invoid from messy coding ******/

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
		$this->lang->load('accounts', $this->Settings->language);
		$this->load->library('form_validation');
		$this->load->model('companies_model');
		$this->load->model('accounts_model');
		$this->load->model('reports_model');
		$this->load->model('sales_model');
		$this->load->model('purchases_model');
		$this->load->model('products_model');

		if(!$this->Owner && !$this->Admin) {
			$gp = $this->site->checkPermissions();
			$this->permission = $gp[0];
			$this->permission[] = $gp[0];
		} else {
			$this->permission[] = NULL;
		}
	}

	function index($action = NULL)
	{
		$this->erp->checkPermissions('index', true, 'accounts');

		$this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
		$this->data['action'] = $action;
		$bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('accounts')));
		$meta = array('page_title' => lang('accounts'), 'bc' => $bc);
		$this->page_construct('accounts/index', $meta, $this->data);
	}

	function settings()
	{
		if($this->input->post('update_settings')){
			if($this->input->post('biller') == null){
				$biller = $this->input->post('biller_id');
			}else{
				$biller = $this->input->post('biller');
			}
			if($this->input->post('default_open_balance') == null){
				$open_balance = $this->input->post('open_balance');
			}else{
				$open_balance = $this->input->post('default_open_balance');
			}
			if($this->input->post('default_sale') == null){
				$sale = $this->input->post('sales');
			}else{
				$sale = $this->input->post('default_sale');
			}
			if($this->input->post('default_sale_discount') == null){
				$sale_discount = $this->input->post('sale_discount');
			}else{
				$sale_discount = $this->input->post('default_sale_discount');
			}
			if($this->input->post('default_sale_tax') == null){
				$sale_tax = $this->input->post('dsale_tax');
			}else{
				$sale_tax = $this->input->post('default_sale_tax');
			}
			if($this->input->post('default_receivable') == null){
				$receivable = $this->input->post('receivable');
			}else{
				$receivable = $this->input->post('default_receivable');
			}
			if($this->input->post('default_purchase') == null){
				$dpurchase = $this->input->post('dpurchase');
			}else{
				$dpurchase = $this->input->post('default_purchase');
			}
			if($this->input->post('default_purchase_discount') == null){
				$dpurchase_discount = $this->input->post('dpurchase_discount');
			}else{
				$dpurchase_discount = $this->input->post('default_purchase_discount');
			}
			if($this->input->post('default_purchase_tax') == null){
				$dpurchase_tax = $this->input->post('dpurchase_tax');
			}else{
				$dpurchase_tax = $this->input->post('default_purchase_tax');
			}
			if($this->input->post('default_payable') == null){
				$dpayable = $this->input->post('dpayable');
			}else{
				$dpayable = $this->input->post('default_payable');
			}
			if($this->input->post('default_sale_freight') == null){
				$dsale_freight = $this->input->post('dsale_freight');
			}else{
				$dsale_freight = $this->input->post('default_sale_freight');
			}
			if($this->input->post('default_purchase_freight') == null){
				$dpurchase_freight = $this->input->post('dpurchase_freight');
			}else{
				$dpurchase_freight = $this->input->post('default_purchase_freight');
			}
			if($this->input->post('default_cost') == null){
				$dcost = $this->input->post('dcost');
			}else{
				$dcost = $this->input->post('default_cost');
			}
			if($this->input->post('default_stock') == null){
				$dstock = $this->input->post('dstock');
			}else{
				$dstock = $this->input->post('default_stock');
			}
			if($this->input->post('default_stock_adjust') == null){
				$dstock_adjust = $this->input->post('dstock_adjust');
			}else{
				$dstock_adjust = $this->input->post('default_stock_adjust');
			}
			if($this->input->post('default_payroll') == null){
				$dpayroll = $this->input->post('dpayroll');
			}else{
				$dpayroll = $this->input->post('default_payroll');
			}
			if($this->input->post('default_cash') == null){
				$dcash = $this->input->post('dcash');
			}else{
				$dcash = $this->input->post('default_cash');
			}
			if($this->input->post('default_credit_card') == null){
				$dcredit_card = $this->input->post('dcredit_card');
			}else{
				$dcredit_card = $this->input->post('default_credit_card');
			}
			if($this->input->post('default_gift_card') == null){
				$dgift_card = $this->input->post('dgift_card');
			}else{
				$dgift_card = $this->input->post('default_gift_card');
			}
			if($this->input->post('default_sale_deposit') == null){
				$dsale_deposit = $this->input->post('dsale_deposit');
			}else{
				$dsale_deposit = $this->input->post('default_sale_deposit');
			}
			if($this->input->post('default_purchase_deposit') == null){
				$dpurchase_deposit = $this->input->post('dpurchase_deposit');
			}else{
				$dpurchase_deposit = $this->input->post('default_purchase_deposit');
			}
			if($this->input->post('default_cheque') == null){
				$dcheque = $this->input->post('dcheque');
			}else{
				$dcheque = $this->input->post('default_cheque');
			}
			if($this->input->post('default_loan') == null){
				$dloan = $this->input->post('dloan');
			}else{
				$dloan = $this->input->post('default_loan');
			}
			if($this->input->post('default_retained_earnings') == null){
				$dretained_earning = $this->input->post('dretained_earning');
			}else{
				$dretained_earning = $this->input->post('default_retained_earnings');
			}
			if($this->input->post('default_cost_variant') == null){
				$cost_of_variance = $this->input->post('cost_variant');
			}else{
				$cost_of_variance = $this->input->post('default_cost_variant');
			}
			if($this->input->post('default_interest_income') == null){
				$default_interest_income = $this->input->post('interest_income');
			}else{
				$default_interest_income = $this->input->post('default_interest_income');
			}
			if($this->input->post('default_transfer_owner') == null){
				$default_transfer_owner = $this->input->post('transfer_owner');
			}else{
				$default_transfer_owner = $this->input->post('default_transfer_owner');
			}
			$data = array(
				'biller_id'            => $biller,
				'default_open_balance' => $open_balance,
				'default_sale'         => $sale,
				'default_sale_discount'=> $sale_discount,
				'default_sale_tax'     => $sale_tax,
				'default_receivable'   => $receivable,
				'default_purchase'     => $dpurchase,
				'default_purchase_discount' => $dpurchase_discount,
				'default_purchase_tax' => $dpurchase_tax,
				'default_payable'      => $dpayable,
				'default_sale_freight'      => $dsale_freight,
				'default_purchase_freight'  => $dpurchase_freight,
				'default_cost'         => $dcost,
				'default_stock' 	   => $dstock,
				'default_stock_adjust' => $dstock_adjust,
				'default_payroll'      => $dpayroll,
				'default_cash'         => $dcash,
				'default_credit_card'  => $dcredit_card,
				'default_gift_card'    => $dgift_card,
				'default_sale_deposit' => $dsale_deposit,
				'default_purchase_deposit' => $dpurchase_deposit,
				'default_cheque'       => $dcheque,
				'default_loan'         => $dloan,
				'default_retained_earnings'	=> $dretained_earning,
				'default_cost_variant'	=> $cost_of_variance,
				'default_interest_income' => $default_interest_income,
				'default_transfer_owner' => $default_transfer_owner
			);
			//$this->erp->print_arrays($data);
			$this->accounts_model->updateSetting($data);
		}
	
		$this->data['default'] = $this->companies_model->getDefaults();
		$this->data['get_biller'] = $this->accounts_model->getCustomers();
		$this->data['get_biller_name'] = $this->accounts_model->getBillers();
		$this->data['chart_accounts'] = $this->accounts_model->getAllChartAccounts();
		$this->data['sale_name'] = $this->accounts_model->getSalename();
		$this->data['sale_discount'] = $this->accounts_model->getsalediscount();
		$this->data['sale_tax'] = $this->accounts_model->getsale_tax();
		$this->data['receivable'] = $this->accounts_model->getreceivable();
		$this->data['purchases'] = $this->accounts_model->getpurchases();
		$this->data['purchase_tax'] = $this->accounts_model->getpurchase_tax();
		$this->data['purchasediscount'] = $this->accounts_model->getpurchasediscount();
		$this->data['payable'] = $this->accounts_model->getpayable();
		$this->data['get_sale_freight'] = $this->accounts_model->get_sale_freights();
		$this->data['get_purchase_freight'] = $this->accounts_model->get_purchase_freights();
		$this->data['getstock'] = $this->accounts_model->getstocks();
		$this->data['stock_adjust'] = $this->accounts_model->getstock_adjust();
		$this->data['getcost'] = $this->accounts_model->get_cost();
		$this->data['getpayroll'] = $this->accounts_model->getpayrolls();
		$this->data['get_cashs'] = $this->accounts_model->get_cash();
		$this->data['credit_card'] = $this->accounts_model->getcredit_card();
		$this->data['sale_deposit'] = $this->accounts_model->get_sale_deposit();
		$this->data['purchased_eposit'] = $this->accounts_model->get_purchase_deposit();
		$this->data['gift_card'] = $this->accounts_model->getgift_card();
		$this->data['cheque'] = $this->accounts_model->getcheque();
		$this->data['loan'] = $this->accounts_model->get_loan();
		$this->data['retained_earning'] = $this->accounts_model->get_retained_earning();
		$this->data['cost_of_variance'] = $this->accounts_model->get_cost_of_variance();
		$this->data['interest_income'] = $this->accounts_model->getInterestIncome();
		$this->data['transfer_owner'] = $this->accounts_model->getTransferOwner();
		$this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        //$this->data['action'] = $action;
		$bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('accounts')));
		$meta = array('page_title' => lang('acount_settings'), 'bc' => $bc);
		$this->page_construct('accounts/settings', $meta, $this->data);
	}

	function list_ac_recevable($warehouse_id = NULL, $datetime = NULL)
	{
		$this->erp->checkPermissions('index', true, 'accounts');
		$this->load->model('reports_model');

		if(isset($_GET['d']) != ""){
			$date = $_GET['d'];
		}else{
			$date = NULL;
		}

		$search_id = NULL;
		if($this->input->get('id')){
			$search_id = $this->input->get('id');
		}

		$this->data['users'] = $this->reports_model->getStaff();
		$this->data['warehouses'] = $this->site->getAllWarehouses();
		$this->data['billers'] = $this->site->getAllCompanies('biller');

		$this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
		if ($this->Owner || $this->Admin) {
			$this->data['warehouses'] = $this->site->getAllWarehouses();
			$this->data['warehouse_id'] = $warehouse_id;
			$this->data['warehouse'] = $warehouse_id ? $this->site->getWarehouseByID($warehouse_id) : NULL;
		} else {
			$this->data['warehouses'] = NULL;
			$this->data['warehouse_id'] = $this->session->userdata('warehouse_id');
			$this->data['warehouse'] = $this->session->userdata('warehouse_id') ? $this->site->getWarehouseByID($this->session->userdata('warehouse_id')) : NULL;
		}
		$this->data['dt'] = $datetime;
		$this->data['date'] = $date;

		$this->data['search_id'] = $search_id;

		$bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('accounts')));
		$meta = array('page_title' => lang('sales'), 'bc' => $bc);
		$this->page_construct('accounts/acc_receivable', $meta, $this->data);
	}

	function list_ar_aging($warehouse_id = NULL)
	{

		$this->data['users'] = $this->reports_model->getStaff();
		$this->data['billers'] = $this->site->getAllCompanies('biller');
		$this->data['user_id'] = isset($user_id);
		$this->data['warehouses'] = $this->site->getAllWarehouses();

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
				//$this->erp->print_arrays(str_replace(',', '-',$this->session->userdata('warehouse_id')));
				$this->data['warehouse_id'] = str_replace(',', '-',$this->session->userdata('warehouse_id'));
				$this->data['warehouse'] = $this->session->userdata('warehouse_id') ? $this->products_model->getUserWarehouses() : NULL;
			}
        }

		$bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('reports'), 'page' => lang('accounts')), array('link' => '#', 'page' => lang('list_ar_aging')));
		$meta = array('page_title' => lang('list_ar_aging'), 'bc' => $bc);
		$this->page_construct('accounts/list_ar_aging', $meta, $this->data);
	}

	function getSales_pending($warehouse_id = NULL, $date = NULL)
	{
		if($warehouse_id){
			$warehouse_id = explode('-', $warehouse_id);
		}
		// $this->erp->print_arrays($warehouse_id);

		$this->erp->checkPermissions('list_ar_aging', null, 'account');

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

		if ((! $this->Owner || ! $this->Admin) && ! $warehouse_id) {
			$user = $this->site->getUser();
			$warehouse_id = $user->warehouse_id;
		}
		$this->load->library('datatables');
		if ($warehouse_id) {
			$this->datatables
			->select("customer_id,customer,SUM(IFNULL(grand_total, 0)) AS grand_total,SUM(IFNULL(paid, 0)) AS paid,SUM(IFNULL(grand_total - paid, 0)) AS balance,COUNT(id) AS ar_number")
			->from('sales')
			->where('payment_status !=', 'paid')
			->where('payment_status !=', 'Returned')
			->where('DATE_SUB('. $this->db->dbprefix('sales')  .'.date, INTERVAL 1 DAY) <= CURDATE()')
			->where_in('warehouse_id', $warehouse_id)
			->group_by('customer');
		} else {
			$lsale = "(SELECT
							s.customer_id,
							s.date,
							SUM(IFNULL(s.grand_total, 0)) AS grand_total2,
							SUM(IFNULL(s.paid, 0)) AS paid2,
							SUM(IFNULL(s.grand_total - paid, 0)) AS balance2,
							COUNT(s.id) AS ar_number2
						FROM
							".$this->db->dbprefix('sales')." AS s
						WHERE
							s.payment_status != 'Returned'
						AND s.payment_status != 'paid'
						AND DATE_SUB(
							s.date,
							INTERVAL 1 DAY
							) <= CURDATE()
						AND (s.grand_total - s.paid) <> 0
						GROUP BY s.customer_id
					) as erp_gsale ";

			$this->datatables
			->select("erp_companies.id, erp_sales.customer,
					grand_total2,
					paid2,
					balance2,
					ar_number2,
					sales.date
				", FALSE)
			->from('sales')
			->join('companies','sales.customer_id = companies.id', 'left')
			->join($lsale,'companies.id = '.$this->db->dbprefix("gsale").'.customer_id','left');
			if(isset($_REQUEST['d'])){
				$date = $_GET['d'];
				$date1 = str_replace("/", "-", $date);
				$date =  date('Y-m-d', strtotime($date1));

				$this->datatables
				->where("date >=", $date)
				->where('sales.payment_term <>', 0);
			}
			$this->datatables->group_by('erp_companies.id');
		}

		if ($this->permission['sales-index'] = ''){
			if (!$this->Customer && !$this->Supplier && !$this->Owner && !$this->Admin) {
				$this->datatables->where('created_by', $this->session->userdata('user_id'));
			} elseif ($this->Customer) {
				$this->datatables->where('customer_id', $this->session->userdata('user_id'));
			}
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
		if ($warehouse) {
			$this->datatables->where('sales.warehouse_id', $warehouse);
		}

		if ($start_date) {
			$this->datatables->where($this->db->dbprefix('sales').'.date BETWEEN "' . $start_date . '" and "' . $end_date . '"');
		}
		$this->datatables->add_column("Actions", "<div class=\"text-center\"><a class=\"tip\" title='" . $this->lang->line("'list_ac_recevable'") . "' href='" . site_url('account/list_ac_recevable') . "'><span class=\"label label-primary\">View</span></a></div>");
		echo $this->datatables->generate();
	}

	function list_ar_aging_0_30($warehouse_id = NULL)
	{
		$this->erp->checkPermissions('list_ar_aging', null, 'account');

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

		if ((! $this->Owner || ! $this->Admin) && ! $warehouse_id) {
			$user = $this->site->getUser();
			$warehouse_id = $user->warehouse_id;
		}

	$this->load->library('datatables');
	if ($warehouse_id) {
		$this->datatables
		->select("id, customer,
				SUM(IFNULL(grand_total, 0) + IFNULL(grand_total, 0)) as grand_total,
				SUM(IFNULL(paid, 0) + IFNULL(paid, 0)) as paid,
				SUM(IFNULL(grand_total-paid, 0) + IFNULL(grand_total-paid, 0)) as balance,COUNT(id) AS ar_number")
			->from('sales')
			->where('payment_status !=', 'paid')
			->where('payment_status !=', 'Returned')
			->where('DATE(erp_sales.date) BETWEEN curdate() - INTERVAL 30 DAY AND curdate() - INTERVAL 0 DAY')
			->where('warehouse_id', $warehouse_id)
			->group_by('customer');
		// ->select("companies.id, customer,
		// 	SUM(
		// 		IFNULL(grand_total, 0)
		// 	) as grand_total,
		// 	SUM(
		// 		IFNULL(paid, 0)
		// 	) as paid,
		// 	SUM(
		// 		IFNULL(grand_total-paid, 0)
		// 	) as balance,
		// 	COUNT(
		// 		sales.id
		// 	) as ar_number
		// 	")
		// ->from('sales')
		// ->join('erp_companies.bill')
		// ->where('payment_status !=', 'paid')
		// ->where('payment_status !=', 'Returned')
		// ->where('warehouse_id', $warehouse_id)
		// ->where('DATE(erp_sales.date) BETWEEN curdate() - INTERVAL 30 DAY AND curdate() - INTERVAL 0 DAY')
		// ->group_by('customer');
	} else {
		$this->datatables
		->select("companies.id, customer,
			SUM(
				IFNULL(grand_total, 0)
			) as grand_total,
			SUM(
				IFNULL(paid, 0)
			) as paid,
			SUM(
				IFNULL(grand_total-paid, 0)
			) as balance,
			COUNT(
				erp_sales.id
			) as ar_number
			")
		->from('sales')
		->join ('companies', 'sales.customer_id = companies.id', 'left')
		->where('payment_status !=', 'Returned')
		->where('payment_status !=', 'paid')
		->where('DATE(erp_sales.date) BETWEEN curdate() - INTERVAL 30 DAY AND curdate() - INTERVAL 0 DAY')
		->where('(grand_total-paid) <> ', 0);
		if(isset($_REQUEST['d'])){
			$date = $_GET['d'];
			$date1 = str_replace("/", "-", $date);
			$date =  date('Y-m-d', strtotime($date1));

			$this->datatables
			->where("date >=", $date)
			->where('sales.payment_term <>', 0);
		}
		$this->datatables->group_by('customer');
	}

	if ($this->permission['sales-index'] = ''){
		if (!$this->Customer && !$this->Supplier && !$this->Owner && !$this->Admin) {
			$this->datatables->where('created_by', $this->session->userdata('user_id'));
		} elseif ($this->Customer) {
			$this->datatables->where('customer_id', $this->session->userdata('user_id'));
		}
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
	if ($warehouse) {
		$this->datatables->where('sales.warehouse_id', $warehouse);
	}

	if ($start_date) {
		$this->datatables->where($this->db->dbprefix('sales').'.date BETWEEN "' . $start_date . '" and "' . $end_date . '"');
	}

	$this->datatables->add_column("Actions", "<div class=\"text-center\"><a class=\"tip\" title='" . $this->lang->line("'list_ac_recevable'") . "' href='" . site_url('account/list_ac_recevable/0/30') . "'><span class=\"label label-primary\">View</span></a></div>");
	echo $this->datatables->generate();
}

	function list_ar_aging_30_60($warehouse_id = NULL)
	{
		$this->erp->checkPermissions('list_ar_aging', null, 'account');

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

		if ((! $this->Owner || ! $this->Admin) && ! $warehouse_id) {
			$user = $this->site->getUser();
			$warehouse_id = $user->warehouse_id;
		}
		$this->load->library('datatables');
		if ($warehouse_id) {
			$this->datatables
			->select("id, customer,
				SUM(IFNULL(grand_total, 0) + IFNULL(grand_total, 0)) as grand_total,
				SUM(IFNULL(paid, 0) + IFNULL(paid, 0)) as paid,
				SUM(IFNULL(grand_total-paid, 0) + IFNULL(grand_total-paid, 0)) as balance,COUNT(id) AS ar_number")
			->from('sales')
			->where('payment_status !=', 'paid')
			->where('payment_status !=', 'Returned')
			->where('DATE(erp_sales.date) BETWEEN curdate() - INTERVAL 60 DAY AND curdate() - INTERVAL 30 DAY')
			->where('warehouse_id', $warehouse_id)
			->group_by('customer');
			// ->select("id, customer,
			// 	SUM(
			// 		IFNULL(grand_total, 0)
			// 	) as grand_total,
			// 	SUM(
			// 		IFNULL(paid, 0)
			// 	) as paid,
			// 	SUM(
			// 		IFNULL(grand_total-paid, 0) + IFNULL(grand_total-paid, 0)
			// 	) as balance
			// 	COUNT(
			// 		id
			// 	) as ar_number
			// 	")
			// ->from('sales')
			// ->where('payment_status !=', 'paid')
			// ->where('payment_status !=', 'Returned')
			// ->where('DATE(erp_sales.date) BETWEEN curdate() - INTERVAL 60 DAY AND curdate() - INTERVAL 30 DAY')
			// ->where('warehouse_id', $warehouse_id)
			// ->group_by('customer');
		} else {
			$this->datatables
			->select("id, customer,
				SUM(
					IFNULL(grand_total, 0)
				) as grand_total,
				SUM(
					IFNULL(paid, 0)
				) as paid,
				SUM(
					IFNULL(grand_total-paid, 0)
				) as balance,
				COUNT(
					id
				) as ar_number
				")
			->from('sales')
			->where('payment_status !=', 'Returned')
			->where('payment_status !=', 'paid')
			->where('DATE(erp_sales.date) BETWEEN curdate() - INTERVAL 60 DAY AND curdate() - INTERVAL 30 DAY')
			->where('(grand_total-paid) <> ', 0);
			if(isset($_REQUEST['d'])){
				$date = $_GET['d'];
				$date1 = str_replace("/", "-", $date);
				$date =  date('Y-m-d', strtotime($date1));
				$this->datatables
				->where("date >=", $date)
				->where($this->db->dbprefix('sales') . '.payment_term <>', 0);
			}
			$this->datatables->group_by('customer');

		}

		if ($this->permission['sales-index'] = ''){
			if (!$this->Customer && !$this->Supplier && !$this->Owner && !$this->Admin) {
				$this->datatables->where('created_by', $this->session->userdata('user_id'));
			} elseif ($this->Customer) {
				$this->datatables->where('customer_id', $this->session->userdata('user_id'));
			}
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
		if ($warehouse) {
			$this->datatables->where('sales.warehouse_id', $warehouse);
		}

		if ($start_date) {
			$this->datatables->where($this->db->dbprefix('sales').'.date BETWEEN "' . $start_date . '" and "' . $end_date . '"');
		}

		$this->datatables->add_column("Actions", "<div class=\"text-center\"><a class=\"tip\" title='" . $this->lang->line("'list_ac_recevable'") . "' href='" . site_url('account/list_ac_recevable/0/60') . "'><span class=\"label label-primary\">View</span></a></div>");
		echo $this->datatables->generate();
	}

	function list_ar_aging_60_90($warehouse_id = NULL)
	{
		$this->erp->checkPermissions('list_ar_aging', null, 'account');

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

		if ((! $this->Owner || ! $this->Admin) && ! $warehouse_id) {
			$user = $this->site->getUser();
			$warehouse_id = $user->warehouse_id;
		}


		$this->load->library('datatables');
		if ($warehouse_id) {
			$this->datatables
			->select("id, customer,
				SUM(IFNULL(grand_total, 0) + IFNULL(grand_total, 0)) as grand_total,
				SUM(IFNULL(paid, 0) + IFNULL(paid, 0)) as paid,
				SUM(IFNULL(grand_total-paid, 0) + IFNULL(grand_total-paid, 0)) as balance")
			->from('sales')
			->where('payment_status !=', 'paid')
			->where('payment_status !=', 'Returned')
			->where('DATE(erp_sales.date) BETWEEN curdate() - INTERVAL 90 DAY AND curdate() - INTERVAL 60 DAY')
			->where('warehouse_id', $warehouse_id)
			->group_by('customer');
		} else {
			$this->datatables
			->select("id, customer,
				SUM(
					IFNULL(grand_total, 0)
				) as grand_total,
				SUM(
					IFNULL(paid, 0)
				) as paid,
				SUM(
					IFNULL(grand_total-paid, 0)
				) as balance,
				COUNT(
					id
				) as ar_number
				")
			->from('sales')
			->where('payment_status !=', 'Returned')
			->where('payment_status !=', 'paid')
			->where('DATE(erp_sales.date) BETWEEN curdate() - INTERVAL 90 DAY AND curdate() - INTERVAL 60 DAY')
			->where('(grand_total-paid) <> ', 0);
			if(isset($_REQUEST['d'])){
				$date = $_GET['d'];
				$date1 = str_replace("/", "-", $date);
				$date =  date('Y-m-d', strtotime($date1));

				$this->datatables
				->where("date >=", $date)
				->where('sales.payment_term <>', 0);
			}

			$this->datatables->group_by('customer');
		}

				//$this->datatables->where('pos !=', 1);
		if ($this->permission['sales-index'] = ''){
			if (!$this->Customer && !$this->Supplier && !$this->Owner && !$this->Admin) {
				$this->datatables->where('created_by', $this->session->userdata('user_id'));
			} elseif ($this->Customer) {
				$this->datatables->where('customer_id', $this->session->userdata('user_id'));
			}
		}

		if ($user_query) {
			$this->datatables->where('sales.created_by', $user_query);
		}
		if ($customer) {
			$this->datatables->where('sales.id', $customer);
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
		if ($warehouse) {
			$this->datatables->where('sales.warehouse_id', $warehouse);
		}

		if ($start_date) {
			$this->datatables->where($this->db->dbprefix('sales').'.date BETWEEN "' . $start_date . '" and "' . $end_date . '"');
		}

		$this->datatables->add_column("Actions", "<div class=\"text-center\"><a class=\"tip\" title='" . $this->lang->line("'list_ac_recevable'") . "' href='" . site_url('account/list_ac_recevable/0/90') . "'><span class=\"label label-primary\">View</span></a></div>");
		echo $this->datatables->generate();
	}

	function list_ar_aging_over_90($warehouse_id = NULL)
	{
		$this->erp->checkPermissions('list_ar_aging', null, 'account');

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

		if ((! $this->Owner || ! $this->Admin) && ! $warehouse_id) {
			$user = $this->site->getUser();
			$warehouse_id = $user->warehouse_id;
		}

		$this->load->library('datatables');
		if ($warehouse_id) {
			$this->datatables
			->select("id, customer,
				SUM(
					IFNULL(grand_total, 0)
				) as grand_total,
				SUM(
					IFNULL(paid, 0)
				) as paid,
				SUM(
					IFNULL(grand_total-paid, 0)
				) as balance,
				COUNT(
					id
				) as ar_number
				")
			->from('sales')
			->where('payment_status !=', 'paid')
			->where('payment_status !=', 'Returned')
			->where('DATE(erp_sales.date) BETWEEN curdate() - INTERVAL 10000 DAY AND curdate() - INTERVAL 90 DAY')
			->where('warehouse_id', $warehouse_id)
			->group_by('customer');
		} else {
			$this->datatables
			->select("id, customer,
				SUM(
					IFNULL(grand_total, 0)
				) as grand_total,
				SUM(
					IFNULL(paid, 0)
				) as paid,
				SUM(
					IFNULL(grand_total-paid, 0)
				) as balance,
				COUNT(
					id
				) as ar_number
				")
			->from('sales')
			->where('payment_status !=', 'Returned')
			->where('payment_status !=', 'paid')
			->where('(grand_total-paid) <> ', 0)
			->where('DATE(erp_sales.date) BETWEEN curdate() - INTERVAL 10000 DAY AND curdate() - INTERVAL 90 DAY');
			if(isset($_REQUEST['d'])){
				$date = $_GET['d'];
				$date1 = str_replace("/", "-", $date);
				$date =  date('Y-m-d', strtotime($date1));

				$this->datatables
				->where("date >=", $date)
				->where('sales.payment_term <>', 0);
			}
			$this->datatables->group_by('customer');
		}

		if ($this->permission['sales-index'] = ''){
			if (!$this->Customer && !$this->Supplier && !$this->Owner && !$this->Admin) {
				$this->datatables->where('created_by', $this->session->userdata('user_id'));
			} elseif ($this->Customer) {
				$this->datatables->where('customer_id', $this->session->userdata('user_id'));
			}
		}

		if ($user_query) {
			$this->datatables->where('sales.created_by', $user_query);
		}
		if ($customer) {
			$this->datatables->where('sales.id', $customer);
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
		if ($warehouse) {
			$this->datatables->where('sales.warehouse_id', $warehouse);
		}

		if ($start_date) {
			$this->datatables->where($this->db->dbprefix('sales').'.date BETWEEN "' . $start_date . '" and "' . $end_date . '"');
		}

		$this->datatables->add_column("Actions", "<div class=\"text-center\"><a class=\"tip\" title='" . $this->lang->line("'list_ac_recevable'") . "' href='" . site_url('account/list_ac_recevable/0/91') . "'><span class=\"label label-primary\">View</span></a></div>");
		echo $this->datatables->generate();
	}

	function list_ac_payable($warehouse_id = null, $rows = NULL, $dt = NULL)
	{
		$search_id = NULL;
		if($this->input->get('id')){
			$search_id = $this->input->get('id');
		}

		$this->erp->checkPermissions('index', true, 'accounts');
		$this->load->model('reports_model');

		if(isset($_GET['d']) != ""){
			$date = $_GET['d'];
		}else{
			$date = NULL;
		}

		$this->data['users'] = $this->reports_model->getStaff();
		$this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
		if ($this->Owner || $this->Admin || !$this->session->userdata('warehouse_id')) {
			$this->data['warehouses'] = $this->site->getAllWarehouses();
			$this->data['warehouse_id'] = $warehouse_id;
			$this->data['warehouse'] = $warehouse_id ? $this->site->getWarehouseByID($warehouse_id) : null;
		} else {
			$this->data['warehouses'] = $this->site->getWarehouseByArrayID($this->session->userdata('warehouse_id'));
			$this->data['warehouse_id'] = $warehouse_id;
			$this->data['warehouse'] = $warehouse_id ? $this->site->getWarehouseByID($warehouse_id) : null;
		}
		$this->data['dt'] = $dt;
		$this->data['date'] = $date;
		$this->data['search_id'] = $search_id;

		$bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('accounts')));
		$meta = array('page_title' => lang('list_ac_payable'), 'bc' => $bc);
		$this->page_construct('accounts/acc_payable', $meta, $this->data);
	}

	function getChartAccount()
	{
		$this->erp->checkPermissions('index', true, 'accounts');

		$this->load->library('datatables');
		$this->datatables
		->select("(erp_gl_charts.accountcode) as id,erp_gl_charts.accountcode, erp_gl_charts.accountname, erp_gl_charts.parent_acc, erp_gl_sections.sectionname")
		->from("erp_gl_charts")
		->join("erp_gl_sections","erp_gl_charts.sectionid=erp_gl_sections.sectionid","INNER")
		->add_column("Actions", "<center><a class=\"tip\" title='" . $this->lang->line("edit_account") . "' href='" . site_url('account/edit/$1') . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-edit\"></i></a>  <a href='#' class='tip po' title='<b>" . $this->lang->line("delete_account") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('account/delete/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></center>", "erp_gl_charts.accountcode");
		echo $this->datatables->generate();
	}

	function billReceipt()
	{
		$this->erp->checkPermissions('bill_receipt', null, 'account');

		$this->load->model('reports_model');
		$this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
		$this->data['users'] = $this->reports_model->getStaff();
		$this->data['billers'] = $this->site->getAllCompanies('biller');
		$bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('accounts'), 'page' => lang('accounts')), array('link' => '#', 'page' => lang('Bill Receipt')));
		$meta = array('page_title' => lang('Account'), 'bc' => $bc);
		$this->page_construct('accounts/bill_reciept', $meta, $this->data);
	}

	function getBillReciept($pdf = NULL, $xls = NULL)
	{
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
		if ($this->input->get('customer')) {
			$customer = $this->input->get('customer');
		} else {
			$customer = NULL;
		}
		if ($this->input->get('biller')) {
			$biller = $this->input->get('biller');
		} else {
			$biller = NULL;
		}
		if ($this->input->get('payment_ref')) {
			$payment_ref = $this->input->get('payment_ref');
		} else {
			$payment_ref = NULL;
		}
		if ($this->input->get('sale_ref')) {
			$sale_ref = $this->input->get('sale_ref');
		} else {
			$sale_ref = NULL;
		}
		if ($this->input->get('purchase_ref')) {
			$purchase_ref = $this->input->get('purchase_ref');
		} else {
			$purchase_ref = NULL;
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
			$start_date = $this->erp->fsd($start_date);
			$end_date = $this->erp->fsd($end_date);
		}

		if ($this->input->get('inv_start_date')) {
			$inv_start_date = $this->input->get('inv_start_date');
		} else {
			$inv_start_date = NULL;
		}

		if ($this->input->get('inv_end_date')) {
			$inv_end_date = $this->input->get('inv_end_date');
		} else {
			$inv_end_date = NULL;
		}

		if ($inv_start_date) {
			$inv_start_date = $this->erp->fsd($inv_start_date);
			$inv_end_date = $this->erp->fsd($inv_end_date);
		}

		/*if (!$this->Owner && !$this->Admin) {
			$user = $this->session->userdata('user_id');
		}*/
		if ($pdf || $xls) {

			$this->db
			->select("" . $this->db->dbprefix('payments') . ".date,
				" . $this->db->dbprefix('payments') . ".reference_no as payment_ref,
				" . $this->db->dbprefix('sales') . ".reference_no as sale_ref,customer,paid_by, amount, type")
			->from('payments')
			->join('sales', 'payments.sale_id=sales.id', 'left')
			->join('purchases', 'payments.purchase_id=purchases.id', 'left')
			->group_by('payments.id')
			->order_by('payments.date asc');
			$this->db->where('payments.type != "sent"');
				//	$this->db->where('sales.customer !=""');

			if ($user) {
				$this->db->where('payments.created_by', $user);
			}
			if ($customer) {
				$this->db->where('sales.customer_id', $customer);
			}
			if ($supplier) {
				$this->db->where('purchases.supplier_id', $supplier);
			}
			if ($biller) {
				$this->db->where('sales.biller_id', $biller);
			}
			if ($customer) {
				$this->db->where('sales.customer_id', $customer);
			}
			if ($payment_ref) {
				$this->db->like('payments.reference_no', $payment_ref, 'both');
			}
			if ($sale_ref) {
				$this->db->like('sales.reference_no', $sale_ref, 'both');
			}
			if ($purchase_ref) {
				$this->db->like('purchases.reference_no', $purchase_ref, 'both');
			}
			if ($start_date) {
				$this->db->where($this->db->dbprefix('payments').'.date BETWEEN "' . $start_date . '" and "' . $end_date . '"');
			}
			if ($inv_start_date) {
				$this->db->where('DATE('.$this->db->dbprefix('sales').'.date) BETWEEN "' . $inv_start_date . '" and "' . $inv_end_date . '"');
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
				$this->excel->getActiveSheet()->setTitle(lang('payments_report'));
				$this->excel->getActiveSheet()->SetCellValue('A1', lang('date'));
				$this->excel->getActiveSheet()->SetCellValue('B1', lang('payment_reference'));
				$this->excel->getActiveSheet()->SetCellValue('C1', lang('sale_reference'));
				$this->excel->getActiveSheet()->SetCellValue('D1', lang('customer'));
				$this->excel->getActiveSheet()->SetCellValue('E1', lang('paid_by'));
				$this->excel->getActiveSheet()->SetCellValue('F1', lang('amount'));
				$this->excel->getActiveSheet()->SetCellValue('G1', lang('type'));

				$row = 2;
				$total = 0;
				foreach ($data as $data_row) {
					$this->excel->getActiveSheet()->SetCellValue('A' . $row, $this->erp->hrld($data_row->date));
					$this->excel->getActiveSheet()->SetCellValue('B' . $row, $data_row->payment_ref);
					$this->excel->getActiveSheet()->SetCellValue('C' . $row, $data_row->sale_ref);
					$this->excel->getActiveSheet()->SetCellValue('D' . $row, $data_row->customer);
					$this->excel->getActiveSheet()->SetCellValue('E' . $row, lang($data_row->paid_by));
					$this->excel->getActiveSheet()->SetCellValue('F' . $row, $data_row->amount);
					$this->excel->getActiveSheet()->SetCellValue('G' . $row, $data_row->type);
					if ($data_row->type == 'returned' || $data_row->type == 'sent') {
						$total -= $data_row->amount;
					} else {
						$total += $data_row->amount;
					}
					$row++;
				}
				$this->excel->getActiveSheet()->getStyle("F" . $row)->getBorders()
				->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
				$this->excel->getActiveSheet()->SetCellValue('F' . $row, $total);

				$this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
				$this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
				$this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
				$this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
				$this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
				$this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
				$this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
				$filename = 'payments_report';
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
			$acc = $this->session->userdata('group_id');

			$this->load->library('datatables');
			if ($this->Owner || $this->Admin || $acc == 10) {
				$this->datatables
					->select($this->db->dbprefix('payments') . ".id,
						" . $this->db->dbprefix('payments') . ".date AS date,
						" . $this->db->dbprefix('sales') . ".date AS sale_date,
						" . $this->db->dbprefix('payments') . ".reference_no as payment_ref,
						" . $this->db->dbprefix('sales') . ".reference_no as sale_ref, customer,
						(
						CASE
						WHEN " . $this->db->dbprefix('payments') . ".note = ' ' THEN
						".$this->db->dbprefix('sales') . ".suspend_note
						WHEN " . $this->db->dbprefix('sales') . ".suspend_note != ''  THEN
						CONCAT(".$this->db->dbprefix('sales') . ".suspend_note, ' - ',  " . $this->db->dbprefix('payments') . ".note)
						ELSE " . $this->db->dbprefix('payments') . ".note END
						),
						" . $this->db->dbprefix('payments') . ".paid_by, IF(erp_payments.type = 'returned', CONCAT('-', erp_payments.amount), erp_payments.amount) as amount, " . $this->db->dbprefix('payments') . ".type, erp_sales.sale_status")
					->from('payments')
					->join('sales', 'payments.sale_id=sales.id', 'left')
					->join('purchases', 'payments.purchase_id=purchases.id', 'left')
					->group_by('payments.id')
					->order_by('sales.id desc');
					if($this->session->userdata('biller_id')){
						$this->datatables->where('payments.biller_id',$this->session->userdata('biller_id'));
					}
					$this->db->where('payments.type != "sent"');
					$this->db->where('sales.customer !=""');
			} else {
				$this->datatables
					->select($this->db->dbprefix('payments') . ".id,
						" . $this->db->dbprefix('payments') . ".date AS date,
						" . $this->db->dbprefix('sales') . ".date AS sale_date,
						" . $this->db->dbprefix('payments') . ".reference_no as payment_ref,
						" . $this->db->dbprefix('sales') . ".reference_no as sale_ref, customer,
						(
						CASE
						WHEN " . $this->db->dbprefix('payments') . ".note = ' ' THEN
						".$this->db->dbprefix('sales') . ".suspend_note
						WHEN " . $this->db->dbprefix('sales') . ".suspend_note != ''  THEN
						CONCAT(".$this->db->dbprefix('sales') . ".suspend_note, ' - ',  " . $this->db->dbprefix('payments') . ".note)
						ELSE " . $this->db->dbprefix('payments') . ".note END
						),
						" . $this->db->dbprefix('payments') . ".paid_by, IF(erp_payments.type = 'returned', CONCAT('-', erp_payments.amount), erp_payments.amount) as amount, " . $this->db->dbprefix('payments') . ".type")
					->from('payments')
					->join('sales', 'payments.sale_id=sales.id', 'left')
					->join('purchases', 'payments.purchase_id=purchases.id', 'left')
					->group_by('payments.id')
					->order_by('sales.id desc');
					if($this->session->userdata('biller_id')){
						$this->datatables->where('payments.biller_id',$this->session->userdata('biller_id'));
					}
					$this->db->where('payments.type != "sent"');
					$this->db->where('sales.customer !=""');
					if($this->session->userdata('user_id')){
						$this->datatables->where('payments.created_by', $this->session->userdata('user_id'));
					}
			}

			if (isset($user)) {
				$this->datatables->where('payments.created_by', $user);
			}
			if (isset($customer)) {
				$this->datatables->where('sales.customer_id', $customer);
			}
			if (isset($supplier)) {
				$this->datatables->where('purchases.supplier_id', $supplier);
			}
			if (isset($biller)) {
				$this->datatables->where('sales.biller_id', $biller);
			}
			if (isset($customer)) {
				$this->datatables->where('sales.customer_id', $customer);
			}
			if (isset($payment_ref)) {
				$this->datatables->like('payments.reference_no', $payment_ref, 'both');
			}
			if (isset($sale_ref)) {
				$this->datatables->like('sales.reference_no', $sale_ref, 'both');
			}
			if (isset($customers)){
				$this->datatables->like('sales.customers',$customers,'both');
			}
			if (isset($purchase_ref)) {
				$this->datatables->like('payments.paid_bys', $purchase_ref, 'both');
			}
			if (isset($grand_total)) {
				$this->datatables->like('sales.grand_total', $grand_total, 'both');
			}
			if (isset($start_date)) {
				$this->datatables->where($this->db->dbprefix('payments').'.date BETWEEN "' . $start_date . '" and "' . $end_date . '"');
			}
			if (isset($inv_start_date)) {
				$this->db->where('DATE('.$this->db->dbprefix('sales').'.date) BETWEEN "' . $inv_start_date . '" and "' . $inv_end_date . '"');
			}


			echo $this->datatables->generate();

		}

	}

	function list_ap_aging($warehouse_id = NULL)
	{

		$this->data['user_id'] = isset($user_id);
        if (!$this->Customer && !$this->Supplier && !$this->Owner && !$this->Admin ){
            $this->data['billers']  = $this->data['billers'] = $this->site->getCompanyByArray($this->session->userdata('biller_id'));
        }else{
            $this->data['billers']  = $this->data['billers'] = $this->site->getAllCompanies('biller');
        }
        $this->data['users']        = $this->site->getAllUsers();
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
				//$this->erp->print_arrays(str_replace(',', '-',$this->session->userdata('warehouse_id')));
				$this->data['warehouse_id'] = str_replace(',', '-',$this->session->userdata('warehouse_id'));
				$this->data['warehouse'] = $this->session->userdata('warehouse_id') ? $this->products_model->getUserWarehouses() : NULL;
			}
        }

		$bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('reports'), 'page' => lang('accounts')), array('link' => '#', 'page' => lang('list_ap_aging')));
		$meta = array('page_title' => lang('list_ap_aging'), 'bc' => $bc);
		$this->page_construct('accounts/list_ap_aging', $meta, $this->data);
	}

	public function getpending_Purchases($warehouse_id = null)
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
			$user = $this->site->getUser();
			$warehouse_id = $user->warehouse_id;
		}


		$this->load->library('datatables');
		if ($warehouse_id) {

			$this->datatables
			->select("purchases.id, companies.name,
				SUM(
					IFNULL(grand_total, 0)
				) AS grand_total,
				SUM(
					IFNULL(paid, 0)
				) AS paid,
				SUM(
					IFNULL(grand_total - paid, 0)
				) AS balance,
				COUNT(
					erp_purchases.id
				) as ap_number,purchases.date
				")
			->from('purchases')
			->join('companies', 'companies.id = purchases.supplier_id', 'inner')
			->where('payment_status !=','paid')
			->where('warehouse_id', $warehouse_id)
			->where('DATE_SUB('. $this->db->dbprefix('purchases')  .'.date, INTERVAL 1 DAY) <= CURDATE()')
			->group_by('purchases.supplier_id');
		} else {
			$this->datatables
			->select("purchases.id, companies.name,
				SUM(
					IFNULL(grand_total, 0)
				) AS grand_total,
				SUM(
					IFNULL(paid, 0)
				) AS paid,
				SUM(
					IFNULL(grand_total - paid, 0)
				) AS balance,
				COUNT(
					erp_purchases.id
				) as ap_number,
				purchases.date
				")
			->from('purchases')
			->join('companies', 'companies.id = purchases.supplier_id', 'inner')
			->where('payment_status !=','paid')
			->where('DATE_SUB('. $this->db->dbprefix('purchases')  .'.date, INTERVAL 1 DAY) <= CURDATE()');


			if(isset($_REQUEST['d'])){
				$date_c = date('Y-m-d', strtotime('+3 months'));
				$date = $_GET['d'];
				$date1 = str_replace("/", "-", $date);
				$date =  date('Y-m-d', strtotime($date1));

				$this->datatables
				->where("date >=", $date)
				->where('payment_status !=','paid')
				->where('purchases.payment_term <>', 0)
				->group_by('purchases.supplier_id');

			}else{
				$this->datatables->group_by('purchases.supplier_id');
			}

		}
        if (!$this->Customer && !$this->Supplier && !$this->Owner && !$this->Admin && !$this->session->userdata('view_right')){
            $this->datatables->where('purchases.created_by', $this->session->userdata('user_id'));
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

		// if (!$this->Customer && !$this->Supplier && !$this->Owner && !$this->Admin && !$this->session->userdata('view_right')) {
  //           $this->datatables->where('purchases.created_by', $this->session->userdata('user_id'));
  //       }

		$this->datatables->add_column("Actions", "<div class=\"text-center\"><a class=\"tip\" title='" . $this->lang->line("'list_ac_recevable'") . "' href='" . site_url('account/list_ac_payable') . "'><span class=\"label label-primary\">View</span></a></div>");
		echo $this->datatables->generate();
	}

	public function list_ap_aging_0_30($warehouse_id = null)
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
			$user = $this->site->getUser();
			$warehouse_id = $user->warehouse_id;
		}

		$this->load->library('datatables');
		if ($warehouse_id) {

			$this->datatables
			->select("purchases.id, companies.name,
						SUM(
							IFNULL(grand_total, 0)
						) AS grand_total,
						SUM(
							IFNULL(paid, 0)
						) AS paid,
						SUM(
							IFNULL(grand_total - paid, 0)
						) AS balance,
						COUNT(
							erp_purchases.id
						) as ap_number
						")
			->from('purchases')
			->join('companies', 'companies.id = purchases.supplier_id', 'inner')
			->where('payment_status !=','paid')
			->where('DATE(erp_purchases.date) BETWEEN curdate() - INTERVAL 30 DAY AND curdate() - INTERVAL 0 DAY')
			->where('warehouse_id', $warehouse_id)
			->group_by('purchases.supplier_id');
		} else {
			$this->datatables
			->select("purchases.id, companies.name,
						SUM(
							IFNULL(grand_total, 0)
						) AS grand_total,
						SUM(
							IFNULL(paid, 0)
						) AS paid,
						SUM(
							IFNULL(grand_total - paid, 0)
						) AS balance,
						COUNT(
							erp_purchases.id
						) as ap_number
						")
			->from('purchases')
			->join('companies', 'companies.id = purchases.supplier_id', 'inner')
			->where('payment_status !=','paid')
			->where('DATE(erp_purchases.date) BETWEEN curdate() - INTERVAL 30 DAY AND curdate() - INTERVAL 0 DAY');

			if(isset($_REQUEST['d'])){
				$date_c = date('Y-m-d', strtotime('+3 months'));
				$date = $_GET['d'];
				$date1 = str_replace("/", "-", $date);
				$date =  date('Y-m-d', strtotime($date1));
				$this->datatables
				->where("date >=", $date)
				->where('payment_status !=','paid')
				->where('purchases.payment_term <>', 0)
				->group_by('purchases.supplier_id');
			}else{
				$this->datatables->group_by('purchases.supplier_id');
			}

		}

		// search options

		if ($user_query) {
			$this->datatables->where('purchases.created_by', $user_query);
		}
        if (!$this->Customer && !$this->Supplier && !$this->Owner && !$this->Admin && !$this->session->userdata('view_right')){
            $this->datatables->where('purchases.created_by', $this->session->userdata('user_id'));
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

		$this->datatables->add_column("Actions", "<div class=\"text-center\"><a class=\"tip\" title='" . $this->lang->line("'list_ac_recevable'") . "' href='" . site_url('account/list_ac_payable/0/0/30') . "'><span class=\"label label-primary\">View</span></a></div>");
		echo $this->datatables->generate();
	}

	public function list_ap_aging_30_60($warehouse_id = null)
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
			$user = $this->site->getUser();
			$warehouse_id = $user->warehouse_id;
		}

		$this->load->library('datatables');
		if ($warehouse_id) {

			$this->datatables
			->select("purchases.id, date, companies.name,
								SUM(
									IFNULL(grand_total, 0)
								) AS grand_total,
								SUM(
									IFNULL(paid, 0)
								) AS paid,
								SUM(
									IFNULL(grand_total - paid, 0)
								) AS balance,
								COUNT(
									erp_purchases.id
								) as ap_number
								")
			->from('purchases')
			->join('companies', 'companies.id = purchases.supplier_id', 'inner')
			->where('payment_status !=','paid')
			->where('DATE(erp_purchases.date) BETWEEN curdate() - INTERVAL 60 DAY AND curdate() - INTERVAL 30 DAY')
			->where('warehouse_id', $warehouse_id);
		} else {
			$this->datatables
			->select("purchases.id, date, companies.name,
								SUM(
									IFNULL(grand_total, 0)
								) AS grand_total,
								SUM(
									IFNULL(paid, 0)
								) AS paid,
								SUM(
									IFNULL(grand_total - paid, 0)
								) AS balance,
								COUNT(
									erp_purchases.id
								) as ap_number
								")
			->from('purchases')
			->join('companies', 'companies.id = purchases.supplier_id', 'inner')
			->where('payment_status !=','paid')
			->where('DATE(erp_purchases.date) BETWEEN curdate() - INTERVAL 60 DAY AND curdate() - INTERVAL 30 DAY');

			if(isset($_REQUEST['d'])){
				$date_c = date('Y-m-d', strtotime('+3 months'));
				$date = $_GET['d'];
				$date1 = str_replace("/", "-", $date);
				$date =  date('Y-m-d', strtotime($date1));

				$this->datatables
				->where("date >=", $date)
				->where('payment_status !=','paid')
				->where('purchases.payment_term <>', 0)
				->group_by('purchases.supplier_id');

			}else{
				$this->datatables->group_by('purchases.supplier_id');
			}
		}

		// search options

		if ($user_query) {
			$this->datatables->where('purchases.created_by', $user_query);
		}
        if (!$this->Customer && !$this->Supplier && !$this->Owner && !$this->Admin && !$this->session->userdata('view_right')){
            $this->datatables->where('purchases.created_by', $this->session->userdata('user_id'));
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

        /*if (!$this->Customer && !$this->Supplier && !$this->Owner && !$this->Admin && !$this->session->userdata('view_right')) {
            $this->datatables->where('created_by', $this->session->userdata('user_id'));
        } elseif ($this->Supplier) {
            $this->datatables->where('supplier_id', $this->session->userdata('user_id'));
        }*/
        $this->datatables->add_column("Actions", "<div class=\"text-center\"><a class=\"tip\" title='" . $this->lang->line("'list_ac_recevable'") . "' href='" . site_url('account/list_ac_payable/0/0/60') . "'><span class=\"label label-primary\">View</span></a></div>");
        echo $this->datatables->generate();
    }

    public function list_ap_aging_60_90($warehouse_id = null)
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
    		$user = $this->site->getUser();
    		$warehouse_id = $user->warehouse_id;
    	}

		$this->load->library('datatables');
		if ($warehouse_id) {

			$this->datatables
			->select("purchases.id, date, companies.name,
								SUM(
									IFNULL(grand_total, 0)
								) AS grand_total,
								SUM(
									IFNULL(paid, 0)
								) AS paid,
								SUM(
									IFNULL(grand_total - paid, 0)
								) AS balance,
								COUNT(
									erp_purchases.id
								) as ap_number
								")
			->from('purchases')
			->join('companies', 'companies.id = purchases.supplier_id', 'inner')
			->where('payment_status !=','paid')
			->where('DATE(erp_purchases.date) BETWEEN curdate() - INTERVAL 90 DAY AND curdate() - INTERVAL 60 DAY')
			->where('warehouse_id', $warehouse_id)
			->group_by('purchases.supplier_id');
		} else {
			$this->datatables
			->select("purchases.id, date, companies.name,
								SUM(
									IFNULL(grand_total, 0)
								) AS grand_total,
								SUM(
									IFNULL(paid, 0)
								) AS paid,
								SUM(
									IFNULL(grand_total - paid, 0)
								) AS balance,
								COUNT(
									erp_purchases.id
								) as ap_number
								")
			->from('purchases')
			->join('companies', 'companies.id = purchases.supplier_id', 'inner')
			->where('payment_status !=','paid')
			->where('DATE(erp_purchases.date) BETWEEN curdate() - INTERVAL 90 DAY AND curdate() - INTERVAL 60 DAY');

			if(isset($_REQUEST['d'])){
				$date_c = date('Y-m-d', strtotime('+3 months'));
				$date = $_GET['d'];
				$date1 = str_replace("/", "-", $date);
				$date =  date('Y-m-d', strtotime($date1));

				$this->datatables
				->where("date >=", $date)
				->where('payment_status !=','paid')
				->where('purchases.payment_term <>', 0)
				->group_by('purchases.supplier_id');

			}else{
				$this->datatables->group_by('purchases.supplier_id');
			}

		}

			// search options

		if ($user_query) {
			$this->datatables->where('purchases.created_by', $user_query);
		}
        if (!$this->Customer && !$this->Supplier && !$this->Owner && !$this->Admin && !$this->session->userdata('view_right')){
            $this->datatables->where('purchases.created_by', $this->session->userdata('user_id'));
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

        /*if (!$this->Customer && !$this->Supplier && !$this->Owner && !$this->Admin && !$this->session->userdata('view_right')) {
            $this->datatables->where('created_by', $this->session->userdata('user_id'));
        } elseif ($this->Supplier) {
            $this->datatables->where('supplier_id', $this->session->userdata('user_id'));
        }*/
        $this->datatables->add_column("Actions", "<div class=\"text-center\"><a class=\"tip\" title='" . $this->lang->line("'list_ac_recevable'") . "' href='" . site_url('account/list_ac_payable/0/0/90') . "'><span class=\"label label-primary\">View</span></a></div>");
        echo $this->datatables->generate();
    }

    public function list_ap_aging_over_90($warehouse_id = null)
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
    		$user = $this->site->getUser();
    		$warehouse_id = $user->warehouse_id;
    	}

		$this->load->library('datatables');
		if ($warehouse_id) {

			$this->datatables
			->select("purchases.id, date, companies.name,
								SUM(
									IFNULL(grand_total, 0)
								) AS grand_total,
								SUM(
									IFNULL(paid, 0)
								) AS paid,
								SUM(
									IFNULL(grand_total - paid, 0)
								) AS balance,
								COUNT(
									erp_purchases.id
								) as ap_number
								")
			->from('purchases')
			->join('companies', 'companies.id = purchases.supplier_id', 'inner')
			->where('payment_status !=','paid')
			->where('DATE(erp_purchases.date) BETWEEN curdate() - INTERVAL 10000 DAY AND curdate() - INTERVAL 90 DAY')
			->where('warehouse_id', $warehouse_id)
			->group_by('purchases.supplier_id');
		} else {
			$this->datatables
			->select("purchases.id, date, companies.name,
								SUM(
									IFNULL(grand_total, 0)
								) AS grand_total,
								SUM(
									IFNULL(paid, 0)
								) AS paid,
								SUM(
									IFNULL(grand_total - paid, 0)
								) AS balance,
								COUNT(
									erp_purchases.id
								) as ap_number
								")
			->from('purchases')
			->join('companies', 'companies.id = purchases.supplier_id', 'inner')
			->where('payment_status !=','paid')
			->where('DATE(erp_purchases.date) BETWEEN curdate() - INTERVAL 10000 DAY AND curdate() - INTERVAL 90 DAY');

			if(isset($_REQUEST['d'])){
				$date_c = date('Y-m-d', strtotime('+3 months'));
				$date = $_GET['d'];
				$date1 = str_replace("/", "-", $date);
				$date =  date('Y-m-d', strtotime($date1));

				$this->datatables
				->where("date >=", $date)
				->where('payment_status !=','paid')
				->where('purchases.payment_term <>', 0)
				->group_by('purchases.supplier_id');

			}else{
				$this->datatables->group_by('purchases.supplier_id');
			}

		}

		if ($user_query) {
			$this->datatables->where('purchases.created_by', $user_query);
		}
        if (!$this->Customer && !$this->Supplier && !$this->Owner && !$this->Admin && !$this->session->userdata('view_right')){
            $this->datatables->where('purchases.created_by', $this->session->userdata('user_id'));
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

        $this->datatables->add_column("Actions", "<div class=\"text-center\"><a class=\"tip\" title='" . $this->lang->line("'list_ac_recevable'") . "' href='" . site_url('account/list_ac_payable/0/0/91') . "'><span class=\"label label-primary\">View</span></a></div>");
        echo $this->datatables->generate();
    }

    function payment_note($id = NULL)
    {
    	$this->load->model('sales_model');
    	$payment = $this->sales_model->getPaymentByID($id);
    	$inv = $this->sales_model->getInvoiceByID($payment->sale_id);
    	$this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
    	$this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
    	$this->data['inv'] = $inv;
    	$this->data['payment'] = $payment;
		$this->data['rowpay'] = $this->sales_model->getPayments($payment->reference_no);
    	$this->data['page_title'] = $this->lang->line("payment_note");
    	$this->load->view($this->theme . 'accounts/payment_note', $this->data);
    }

	function bill_reciept_form($id = NULL){
		$this->load->model('sales_model');
    	$payment = $this->sales_model->getPaymentByID($id);
    	$inv = $this->sales_model->getInvoiceByID($payment->sale_id);
    	$this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
    	$this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
    	$this->data['inv'] = $inv;
    	$this->data['payment'] = $payment;
		$this->data['products'] = $this->sales_model->getProductNew($payment->sale_id);
		$this->data['jl_data'] = $this->sales_model->getJoinlease($payment->sale_id);
		$this->data['rowpay'] = $this->sales_model->getPayments($payment->reference_no);
		$this->load->view($this->theme . 'accounts/bill_reciept_form', $this->data);
	}

	function bill_reciept_tps($id = NULL){
		$this->load->model('sales_model');
    	$payment = $this->sales_model->getPaymentByID($id);
    	$inv = $this->sales_model->getInvoiceByID($payment->sale_id);
    	$this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
    	$this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
    	$this->data['inv'] = $inv;
    	$this->data['payment'] = $payment;
		$this->data['products'] = $this->sales_model->getProductNew($payment->sale_id);
		$this->data['jl_data'] = $this->sales_model->getJoinlease($payment->sale_id);
		$this->data['rowpay'] = $this->sales_model->getPayments($payment->reference_no);
		$this->load->view($this->theme . 'accounts/bill_reciept_tps', $this->data);
	}

    function purchase_note($id = NULL)
    {
    	$this->load->model('sales_model');
    	$purchase = $this->sales_model->getPurchaseByID($id);
    	$inv = $this->sales_model->getInvoiceByID($purchase->id);
    	$this->data['biller'] = $this->site->getCompanyByID($purchase->biller_id);
    	$this->data['customer'] = $this->site->getCompanyByID($purchase->supplier_id);
    	$this->data['inv'] = $inv;
    	$this->data['payment'] = $purchase;
    	$this->data['page_title'] = $this->lang->line("purchase_note");

    	$this->load->view($this->theme . 'accounts/purchase_note', $this->data);
    }

    function account_head($id = NULL)
    {
		$this->data['id'] = $id;
		$this->data['page_title'] = $this->lang->line("account_head");
		$this->load->view($this->theme . 'accounts/account_head', $this->data);
	}

	function dataLedger()
	{
		$output = "";
		$start_date = $this->erp->fsd($_GET['start_date']);
		$end_date = $this->erp->fsd($_GET['end_date']);
		$id = $_GET['id'];
		$this->db->select('*')->from('gl_charts');
		$this->db->where('accountcode', $id);

		$acc = $this->db->get()->result();
		foreach($acc as $val){
			$gl_tranStart = $this->db->select('sum(amount) as startAmount')->from('gl_trans');
			$gl_tranStart->where(array('tran_date < '=> $this->erp->fld($this->input->post('start_date')), 'account_code'=> $val->accountcode));
			$startAmount = $gl_tranStart->get()->row();

			$endAccountBalance = 0;
			$getListGLTran = $this->db->select("*")->from('gl_trans')->where('account_code =', $val->accountcode);
			if ($this->input->post('start_date')) {
				$getListGLTran->where('tran_date >=', $this->erp->fld($this->input->post('start_date')) );
			}
			if ($this->input->post('end_date')) {
				$getListGLTran->where('tran_date <=', $this->erp->fld($this->input->post('end_date')) );
			}
			$gltran_list = $getListGLTran->get()->result();
			if($gltran_list)
			{
				$output.='<tr>';
				$output.='<td colspan="4">Account:'.$val->accountcode . ' ' .$val->accountname.'</td>';
				$output.='<td colspan="2">Begining Account Balance: </td>';
				$output.='<td colspan="2" style="text-align: center;">';
				$output.='$'.abs($startAmount->startAmount);
				$output.='</td>';
				$output.='</tr>';
				foreach($gltran_list as $rw)
				{
					$endAccountBalance += $rw->amount;
					$output.='<tr>';
					$output.='<td>'.$rw->tran_id.'</td>';
					$output.='<td>'.$rw->reference_no.'</td>';
					$output.='<td>'.$rw->tran_no.'</td>';
					$output.='<td>'.$rw->narrative.'</td>';
					$output.='<td>'.$rw->tran_date.'</td>';
					$output.='<td>'.$rw->tran_type.'</td>';
					$output.='<td>'.($rw->amount > 0 ? $rw->amount : '0.00').'</td>';
					$output.='<td>'.($rw->amount < 1 ? abs($rw->amount) : '0.00').'</td>';
					$output.='</tr>';
				}
				$output.='<tr>';
				$output.='<td colspan="4"> </td>';
				$output.='<td colspan="2">Ending Account Balance: </td>';
				$output.='<td colspan="2">$ '.abs($endAccountBalance).'</td>';
				$output.='</tr>';
			}else{
				$output.='<tr>';
				$output.='<td colspan="8" class="dataTables_empty">No Data</td>';
				$output.='</tr>';
			}
		}
		echo json_encode($output);
	}

	function billPayable()
	{
		$this->erp->checkPermissions('index', true, 'accounts');
		$this->load->model('reports_model');
		$this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
		$this->data['users'] = $this->reports_model->getStaff();
		$this->data['billers'] = $this->site->getAllCompanies('biller');
		$bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('accounts'), 'page' => lang('accounts')), array('link' => '#', 'page' => lang('Bill Payable Report')));
		$meta = array('page_title' => lang('payments_report'), 'bc' => $bc);
		$this->page_construct('accounts/bill_payable', $meta, $this->data);
	}

	function getBillPaymentReport($pdf = NULL, $xls = NULL)
	{
		$this->erp->checkPermissions('index', true, 'accounts');
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
		if ($this->input->get('customer')) {
			$customer = $this->input->get('customer');
		} else {
			$customer = NULL;
		}
		if ($this->input->get('biller')) {
			$biller = $this->input->get('biller');
		} else {
			$biller = NULL;
		}
		if ($this->input->get('payment_ref')) {
			$payment_ref = $this->input->get('payment_ref');
		} else {
			$payment_ref = NULL;
		}
		if ($this->input->get('sale_ref')) {
			$sale_ref = $this->input->get('sale_ref');
		} else {
			$sale_ref = NULL;
		}
		if ($this->input->get('purchase_ref')) {
			$purchase_ref = $this->input->get('purchase_ref');
		} else {
			$purchase_ref = NULL;
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
			$start_date = $this->erp->fsd($start_date);
			$end_date = $this->erp->fsd($end_date);
		}
		if (!$this->Owner && !$this->Admin) {
			$user = $this->session->userdata('user_id');
		}
		if ($pdf || $xls) {

			$this->db
			->select($this->db->dbprefix('purchases') . ".id,
				" . $this->db->dbprefix('purchases') . ".date,
				" . $this->db->dbprefix('purchases') . ".reference_no,
				" . $this->db->dbprefix('purchases') . ".supplier as purchase_ref,
				" . $this->db->dbprefix('payments') . ".paid_by,
				" . $this->db->dbprefix('payments') . ".note,
				" . $this->db->dbprefix('purchases') . ".paid,
				" . $this->db->dbprefix('purchases') . ".payment_status")
			->from('purchases')
			->JOIN('payments','purchases.id=payments.purchase_id','left');
                //->group_by('purchases.id');
			if ($this->permission['accounts-index'] = ''){
				if ($user) {
					$this->db->where('payments.created_by', $user);
				}
			}
			if ($customer) {
				$this->db->where('sales.customer_id', $customer);
			}
			if ($supplier) {
				$this->db->where('purchases.supplier_id', $supplier);
			}
			if ($biller) {
				$this->db->where('sales.biller_id', $biller);
			}
			if ($customer) {
				$this->db->where('sales.customer_id', $customer);
			}
			if ($payment_ref) {
				$this->db->like('payments.reference_no', $payment_ref, 'both');
			}
			if ($sale_ref) {
				$this->db->like('sales.reference_no', $sale_ref, 'both');
			}
			if ($purchase_ref) {
				$this->db->like('purchases.reference_no', $purchase_ref, 'both');
			}
			if ($start_date) {
				$this->db->where($this->db->dbprefix('payments').'.date BETWEEN "' . $start_date . '" and "' . $end_date . '"');
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
				$this->excel->getActiveSheet()->setTitle(lang('bill_payable'));
				$this->excel->getActiveSheet()->SetCellValue('A1', lang('date'));
				$this->excel->getActiveSheet()->SetCellValue('B1', lang('reference_no'));
				$this->excel->getActiveSheet()->SetCellValue('C1', lang('supplier'));
				$this->excel->getActiveSheet()->SetCellValue('D1', lang('paid_by'));
				$this->excel->getActiveSheet()->SetCellValue('E1', lang('paid'));
                //$this->excel->getActiveSheet()->SetCellValue('F1', lang('amount'));
				$this->excel->getActiveSheet()->SetCellValue('F1', lang('type'));

				$row = 2;
				$total = 0;
				$paid=0;
				foreach ($data as $data_row) {
					$this->excel->getActiveSheet()->SetCellValue('A' . $row, $this->erp->hrld($data_row->date));
					$this->excel->getActiveSheet()->SetCellValue('B' . $row, $data_row->reference_no);
					$this->excel->getActiveSheet()->SetCellValue('C' . $row, $data_row->supplier);
					$this->excel->getActiveSheet()->SetCellValue('D' . $row, lang($data_row->paid_by));
					$this->excel->getActiveSheet()->SetCellValue('E' . $row, $data_row->paid);
					$this->excel->getActiveSheet()->SetCellValue('F' . $row, $data_row->grand_total);
					$this->excel->getActiveSheet()->SetCellValue('G' . $row, $data_row->type);
					$paid+=$data_row->paid;
					$total+=$data_row->grand_total;
					$row++;
				}
				$this->excel->getActiveSheet()->getStyle("F" . $row)->getBorders()
				->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
				$this->excel->getActiveSheet()->setCellValue('E'.$row,$paid);
				$this->excel->getActiveSheet()->SetCellValue('F' . $row, $total);

				$this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
				$this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
				$this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
				$this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
				$this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
				$this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
				$this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
				$filename = 'payments_report';
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

			$this->load->library('datatables');
			$this->datatables
			->select($this->db->dbprefix('payments') . ".id as pid,
				" . $this->db->dbprefix('payments') . ".date,
				" . $this->db->dbprefix('purchases') . ".date as purchase_date,
				" . $this->db->dbprefix('purchases') . ".reference_no as payment_ref,
				" . $this->db->dbprefix('purchases') . ".supplier as purchase_ref,
				" . $this->db->dbprefix('payments') . ".paid_by,
				" . $this->db->dbprefix('payments') . ".note,
				" . $this->db->dbprefix('payments') . ".amount,
				'paid' as payment_status")
			->from('purchases')
			->where('purchases.paid != 0')
			->JOIN('payments','purchases.id=payments.purchase_id','left');
                //->group_by('purchases.id');
			if ($this->permission['accounts-index'] = ''){
				if ($user) {
					$this->datatables->where('payments.created_by', $user);
				}
			}
			if ($customer) {
				$this->datatables->where('sales.customer_id', $customer);
			}
			if ($supplier) {
				$this->datatables->where('purchases.supplier_id', $supplier);
			}
			if ($biller) {
				$this->datatables->where('sales.biller_id', $biller);
			}
			if ($customer) {
				$this->datatables->where('sales.customer_id', $customer);
			}
			if ($payment_ref) {
				$this->datatables->like('payments.reference_no', $payment_ref, 'both');
			}
			if ($sale_ref) {
				$this->datatables->like('sales.reference_no', $sale_ref, 'both');
			}
			if ($purchase_ref) {
				$this->datatables->like('purchases.reference_no', $purchase_ref, 'both');
			}
			if ($start_date) {
				$this->datatables->where($this->db->dbprefix('payments').'.date BETWEEN "' . $start_date . '" and "' . $end_date . '"');
			}

			echo $this->datatables->generate();

		}

	}

	function listJournal()
	{
		$this->erp->checkPermissions('index', true, 'accounts');
		$this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
		$this->data['biller_id'] = $this->session->userdata('biller_id');
		$bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('accounts')));
		$meta = array('page_title' => lang('Account'), 'bc' => $bc);
		$this->page_construct('accounts/list_journal', $meta, $this->data);
	}

	function getJournalList()
	{
		$this->erp->checkPermissions('index', true, 'accounts');
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

		$this->load->library('datatables');
		$this->datatables
		->select("gt.tran_id, gt.tran_no AS g_tran_no, gt.tran_type, gt.tran_date,
			gt.reference_no, companies.company,
			(
				CASE
				WHEN gt.tran_type = 'SALES' THEN
					IF(gt.bank = '1', (
						SELECT
							erp_companies.company
						FROM
							erp_payments
						INNER JOIN erp_sales ON erp_sales.id = erp_payments.sale_id
						INNER JOIN erp_companies ON erp_companies.id = erp_sales.customer_id
						WHERE
							erp_payments.reference_no = gt.reference_no
						LIMIT 0,1
					), (
						SELECT
							erp_companies.company
						FROM
							erp_sales
						INNER JOIN erp_companies ON erp_companies.id = erp_sales.customer_id
						WHERE
							erp_sales.reference_no = gt.reference_no
						LIMIT 0,1
					))
				WHEN gt.tran_type = 'PURCHASES' OR gt.tran_type = 'PURCHASE EXPENSE' THEN
					IF(gt.bank = 1, (
						SELECT
							erp_companies.company
						FROM
							erp_payments
						INNER JOIN erp_purchases ON erp_purchases.id = erp_payments.purchase_id
						INNER JOIN erp_companies ON erp_companies.id = erp_purchases.supplier_id
						WHERE
							erp_payments.reference_no = gt.reference_no
						LIMIT 0,1
					), (
						SELECT
							erp_companies.company
						FROM
							erp_purchases
						INNER JOIN erp_companies ON erp_companies.id = erp_purchases.supplier_id
						WHERE
							erp_purchases.reference_no = gt.reference_no
						LIMIT 0,1
					))
				WHEN gt.tran_type = 'SALES-RETURN' THEN
					(
						SELECT
							erp_return_sales.customer
						FROM
							erp_return_sales
						WHERE
							erp_return_sales.reference_no = gt.reference_no
						LIMIT 0,1
					)
				WHEN gt.tran_type = 'PURCHASES-RETURN' THEN
					(
						SELECT
							erp_return_purchases.supplier
						FROM
							erp_return_purchases
						WHERE
							erp_return_purchases.reference_no = gt.reference_no
						LIMIT 0,1
					)
				WHEN gt.tran_type = 'DELIVERY' THEN
					(
                        SELECT
                            erp_companies.company as customer
                        FROM
                            erp_deliveries
                        INNER JOIN erp_companies ON erp_companies.id = erp_deliveries.customer_id
                        WHERE
                            erp_deliveries.do_reference_no = gt.reference_no
                        LIMIT 0,1
                    )
				WHEN gt.tran_type = 'PRINCIPLE' THEN
					(
						SELECT
							erp_companies.company
						FROM
							erp_payments
						LEFT JOIN erp_loans ON erp_loans.id = erp_payments.loan_id
						INNER JOIN erp_sales ON erp_loans.sale_id = erp_sales.id
						INNER JOIN erp_companies ON erp_companies.id = erp_sales.customer_id
						WHERE
							erp_payments.reference_no = gt.reference_no
						LIMIT 0,1
					)
				ELSE
					created_name
				END
			) AS name,
			gt.account_code,
			gt.narrative,gt.description as note,
			(IF(gt.amount > 0, gt.amount, IF(gt.amount = 0, 0, null))) as debit,
			(IF(gt.amount < 0, abs(gt.amount), null)) as credit,
			users.username,
			")
		->from("erp_gl_trans gt")
		->join('companies', 'companies.id = (gt.biller_id)', 'left')
		->join('users', 'users.id = created_by', 'left')
		->order_by('gt.tran_id','DESC');
		if($this->session->userdata('biller_id')){
            $this->datatables->where_in('gt.biller_id', JSON_decode($this->session->userdata('biller_id')));
		}
		if ($reference_no) {
			$this->datatables->like('gt.reference_no', $reference_no, 'both');
		}
		if ($start_date) {
			$this->datatables->where('gt.tran_date BETWEEN "' . $start_date .' 00:00:00" AND "' . $end_date .' 23:59:00"');
		}
		if (!$this->Customer && !$this->Supplier && !$this->Owner && !$this->Admin && !$this->session->userdata('view_right')) {
            $this->datatables->where('created_by', $this->session->userdata('user_id'));
        }

		$this->datatables->add_column("Actions", "<center><a class=\"tip\" title='" . $this->lang->line("edit_journal") . "' href='" . site_url('account/edit_journal/$1') . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-edit\"></i></a></center>", "g_tran_no");
		echo $this->datatables->generate();
	}

	public function edit_journal($tran_no)
	{
		$this->erp->checkPermissions('edit', true, 'accounts');
		$chart_acc_details = $this->accounts_model->getAllChartAccount();
		foreach($chart_acc_details as $chart){
			$section_id = $chart->sectionid;
		}

		$this->data['type'] = $this->accounts_model->getAlltypes();
		$this->data['supplier'] = $chart_acc_details;
		$this->data['sectionacc'] = $chart_acc_details;
		$this->data['journals'] = $this->accounts_model->getJournalByTranNo($tran_no);
		$this->data['subacc'] = $this->accounts_model->getSubAccounts($section_id);
		$this->data['billers'] = $this->site->getAllCompanies('biller');
		$this->data['customers'] = $this->site->getCustomers();
		$this->data['invoices'] = $this->site->getCustomerInvoices();
		$this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
		$this->data['modal_js'] = $this->site->modal_js();
		$this->load->view($this->theme . 'accounts/edit_journal', $this->data);
	}

	public function add_journal()
	{
		$this->erp->checkPermissions('add', true, 'accounts');
		$this->data['type'] = $this->accounts_model->getAlltypes();
		$this->data['sectionacc'] = $this->accounts_model->getAccountSections();
		$this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
		$this->data['modal_js'] = $this->site->modal_js();
		$this->data['sectionacc'] = $this->accounts_model->getAllChartAccount();
		$this->data['billers'] = $this->site->getAllCompanies('biller');
		$this->data['customers'] = $this->site->getCustomers();
		$this->data['invoices'] = $this->site->getCustomerInvoices();

		if ($this->Owner || $this->Admin || !$this->session->userdata('biller_id')) {
			$biller_id = $this->site->get_setting()->default_biller;
			$this->data['biller_id'] = $biller_id;
			$this->data['reference_no'] = $this->site->getReference('jr',$biller_id);

		}else{

			$biller_id = $this->session->userdata("biller_id");
			$this->data['biller_id'] = $biller_id;
			$this->data['reference_no'] = $this->site->getReference('jr',$biller_id);
		}

		$this->data['rate'] = $this->accounts_model->getKHM();
		$this->load->view($this->theme . 'accounts/add_journal', $this->data);
	}

	function save_journal()
	{
		$account_code 		= $this->input->post('account_section');
		$biller_id 			= $this->input->post('biller_id');
		$reference_no 		= ($this->input->post('reference')? $this->input->post('reference') : $this->site->getReference('jr',$biller_id));
		$date 				= $this->input->post('date');
		$tran_date 			= strtr($date, '/', '-');
		$tran_date 			= date('Y-m-d h:m', strtotime($tran_date));
		$description 		= $this->input->post('description');
		$note 				= $this->input->post('note');
		$debit 				= $this->input->post('debit');
		$credit 			= $this->input->post('credit');
		$created_by_name 	= $this->input->post('name');
		$created_type 		= $this->input->post('type');
		$sale_id 			= $this->input->post('customer_invoice_no');
		$customer_id 		= $this->input->post('customer_invoice');
		$i = 0;

		if ($created_type == 3) {
			$customer 		= $this->site->getCompanyByName($created_by_name, $created_type);
			$customer_id 	= $customer->id;
		}

		$tran_no = $this->accounts_model->getTranNo();
		$data = array();
		for($i=0;$i<count($account_code);$i++) {
			if($debit[$i]>0) {
				$amount = $debit[$i];
			}
			elseif($credit[$i]>0) {
				$amount = -$credit[$i];
			}
			if(!empty($note[$i]) || $note[$i] != '' || $note[$i]) {
				$description_ = $note[$i];
			}else {
				$description_ = $description;
			}
			$data[] = array(
				'tran_type' 	=> 'JOURNAL',
				'tran_no' 		=> $tran_no,
				'account_code' 	=> $account_code[$i],
				'tran_date' 	=> $tran_date,
				'reference_no' 	=> $reference_no,
				'description' 	=> $description_,
				'amount' 		=> $amount,
				'biller_id' 	=> $biller_id,
				'created_name' 	=> $created_by_name,
				'created_type'	=> $created_type,
				'sale_id' 		=> $sale_id,
				'customer_id' 	=> $customer_id,
				'created_by' 	=> $this->session->userdata('user_id'),
			);
		}
		//$this->erp->print_arrays($data);

		$this->accounts_model->addJournal($data);

		$this->session->set_flashdata('message', $this->lang->line("journal_added"));
		redirect('account/listJournal');
	}

	public function updateJournal()
	{
		$this->erp->checkPermissions('edit', true, 'accounts');
		$account_code 		= $this->input->post('account_section');
		$reference_no 		= $this->input->post('reference_no');
		$old_reference_no 	= $this->input->post('temp_reference_no');
		$biller_id 			= $this->input->post('biller_id');
		$date 				= $this->input->post('date');
		$tran_date 			= strtr($date, '/', '-');
		$tran_date 			= date('Y-m-d h:m', strtotime($tran_date));
		$tran_id 			= $this->input->post('tran_id');
		$description 		= $this->input->post('description');
		$note 				= $this->input->post('note');
		$debit 				= $this->input->post('debit');
		$credit 			= $this->input->post('credit');
		$created_name 		= $this->input->post('name');
		$created_type 		= $this->input->post('type');
		$sale_id 			= $this->input->post('customer_invoice_no');
		$customer_id 		= $this->input->post('customer_invoice');
		$i 					= 0;
		$tran_type 			= '';
		$tran_no_old 		= $this->accounts_model->getTranNoByRef($old_reference_no);
		$tran_type 			= $this->accounts_model->getTranTypeByRef($old_reference_no);
		if(!$tran_type){
			$tran_type = 'JOURNAL';
		}
		if ($created_type == 3) {
			$customer 		= $this->site->getCompanyByName($created_name, $created_type);
			$customer_id 	= $customer->id;
		}
		$gltans = $this->accounts_model->getJournalByTranNo($tran_no_old);
		$not_account = array();
		foreach($gltans as $key => $gltran){
			if($gltran->account_code != $account_code[$key] && $tran_id[$key] == 0){
				$not_account[] = $gltran->tran_id;
			}
		}
		$data = array();
		for($i=0; $i < count($account_code); $i++){

			if($debit[$i]>0) {
				$amount = $debit[$i];
			}
			elseif($credit[$i]>0 ) {
				$amount = -$credit[$i];
			}else{
				$amount = 0;
			}
			if(!empty($note[$i]) || $note[$i] != '' || $note[$i]) {
				$description_ = $note[$i];
			}else {
				$description_ = $description;
			}

			if($tran_id[$i] != 0){
				$data[] = array(
					'tran_type' 	=> $tran_type,
					'tran_no' 		=> $tran_no_old,
					'tran_id' 		=> $tran_id[$i],
					'account_code' 	=> $account_code[$i],
					'tran_date' 	=> $tran_date,
					'reference_no' 	=> $reference_no,
					'description' 	=> $description_,
					'amount' 		=> $amount,
					'biller_id' 	=> $biller_id,
					'created_name' 	=> $created_name,
					'created_type' 	=> $created_type,
					'sale_id' 		=> $sale_id,
					'customer_id' 	=> $customer_id,
					'updated_by' 	=> $this->session->userdata('user_id'),
				);
			}else{
				$data[] = array(
					'tran_type' 	=> $tran_type,
					'tran_no' 		=> $tran_no_old,
					'account_code' 	=> $account_code[$i],
					'tran_date' 	=> $tran_date,
					'reference_no' 	=> $reference_no,
					'description' 	=> $description_,
					'amount' 		=> $amount,
					'biller_id' 	=> $biller_id,
					'created_name' 	=> $created_name,
					'created_type' 	=> $created_type,
					'sale_id' 		=> $sale_id,
					'customer_id' 	=> $customer_id,
					'created_by' 	=> $this->session->userdata('user_id'),
				);
			}
		}
		//$this->erp->print_arrays($data);
		$this->accounts_model->updateJournal($data, $old_reference_no);
		$this->accounts_model->deleteGltranByAccount($not_account);
		$this->session->set_flashdata('message', $this->lang->line("journal_updated"));
		redirect('account/listJournal');
	}

	public function deleteJournal($id)
	{
		$this->erp->checkPermissions(NULL, TRUE);

		if ($this->input->get('id')) {
			$id = $this->input->get('id');
		}

		if ($this->accounts_model->deleteJournalById($id)) {
			echo $this->lang->line("deleted_journal");
		} else {
			$this->session->set_flashdata('warning', lang('journal_x_deleted_have_account'));
			die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . (isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : site_url('welcome')) . "'; }, 0);</script>");
		}
	}

	public function getSubAccount($section_code = null)
	{
		if ($rows = $this->accounts_model->getSubAccounts($section_code)) {
			$data = json_encode($rows);
		} else {
			$data = false;
		}
		echo $data;
	}

	public function getpeoplebytype($company = null)
	{
		if ($rows = $this->accounts_model->getpeoplebytype($company)) {
			$data = json_encode($rows);
		} else {
			$data = false;
		}
		echo $data;
	}

	public function getCustomerInvoices($customer = null)
	{
		if ($rows = $this->site->getCustomerInvoices($customer)) {
			$data = json_encode($rows);
		} else {
			$data = false;
		}
		echo $data;
	}

	public function add()
	{
		$this->erp->checkPermissions('add', null, 'accounts');

		$this->form_validation->set_rules('email', $this->lang->line("email_address"), 'is_unique[companies.email]');
		//$this->form_validation->set_rules('account_code', $this->lang->line("account_code"), 'is_unique[gl_charts.accountcode]');

		if ($this->form_validation->run('account/add') == true) {

			$data = array('accountcode' => $this->input->post('account_code'),
				'accountname' => $this->input->post('account_name'),
				'parent_acc' => $this->input->post('sub_account'),
				'sectionid' => $this->input->post('account_section'),
				'bank' => $this->input->post('bank_account')
				);
		}

		if ($this->form_validation->run() == true && $sid = $this->accounts_model->addChartAccount($data)) {
			$this->session->set_flashdata('message', $this->lang->line("accound_added"));
			$ref = isset($_SERVER["HTTP_REFERER"]) ? explode('?', $_SERVER["HTTP_REFERER"]) : NULL;
			redirect($ref[0] . '?account=' . $sid);
		} else {
			$this->data['sectionacc'] = $this->accounts_model->getAccountSections();
			$this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
			$this->data['modal_js'] = $this->site->modal_js();
			$this->load->view($this->theme . 'accounts/add', $this->data);
		}
	}

	public function updateAccount()
	{
		$parent_account = $this->input->post('sub_acc');
		$acc_code = $this->input->post('account_code');
		if($this->input->post('sub_account') != '' || $this->input->post('sub_account') != null){
			$parent_account = $this->input->post('sub_account');
		}

		$data = array('accountcode' => $acc_code,
			'accountname' => $this->input->post('account_name'),
			'parent_acc' => $parent_account,
			'sectionid' => $this->input->post('account_section'),
			'bank' => $this->input->post('bank_account')
			);

		$this->accounts_model->updateChartAccount($acc_code, $data);
		$this->session->set_flashdata('message', $this->lang->line("accound_updated"));
		redirect('account');
	}

	public function edit($id = NULL)
	{
		$this->erp->checkPermissions(false, true);

		$chart_acc_details = $this->accounts_model->getChartAccountByID($id);
		$section_id = $chart_acc_details->sectionid;

		$this->data['supplier'] = $chart_acc_details;
		$this->data['sectionacc'] = $this->accounts_model->getAccountSections();
		$this->data['subacc'] = $this->accounts_model->getSubAccounts($section_id);
		$this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
		$this->data['modal_js'] = $this->site->modal_js();
		$this->load->view($this->theme . 'accounts/edit', $this->data);
	}

	public function users($company_id = NULL)
	{
		$this->erp->checkPermissions(false, true);

		if ($this->input->get('id')) {
			$company_id = $this->input->get('id');
		}


		$this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
		$this->data['modal_js'] = $this->site->modal_js();
		$this->data['company'] = $this->companies_model->getCompanyByID($company_id);
		$this->data['users'] = $this->companies_model->getCompanyUsers($company_id);
		$this->load->view($this->theme . 'suppliers/users', $this->data);

	}

	public function add_user($company_id = NULL)
	{
		$this->erp->checkPermissions(false, true);

		if ($this->input->get('id')) {
			$company_id = $this->input->get('id');
		}
		$company = $this->companies_model->getCompanyByID($company_id);

		$this->form_validation->set_rules('email', $this->lang->line("email_address"), 'is_unique[users.email]');
		$this->form_validation->set_rules('password', $this->lang->line('password'), 'required|min_length[8]|max_length[20]|matches[password_confirm]');
		$this->form_validation->set_rules('password_confirm', $this->lang->line('confirm_password'), 'required');

		if ($this->form_validation->run('companies/add_user') == true) {
			$active = $this->input->post('status');
			$notify = $this->input->post('notify');
			list($username, $domain) = explode("@", $this->input->post('email'));
			$email = strtolower($this->input->post('email'));
			$password = $this->input->post('password');
			$additional_data = array(
				'first_name' => $this->input->post('first_name'),
				'last_name' => $this->input->post('last_name'),
				'phone' => $this->input->post('phone'),
				'gender' => $this->input->post('gender'),
				'company_id' => $company->id,
				'company' => $company->company,
				'group_id' => 3
				);
			$this->load->library('ion_auth');
		} elseif ($this->input->post('add_user')) {
			$this->session->set_flashdata('error', validation_errors());
			redirect('suppliers');
		}

		if ($this->form_validation->run() == true && $this->ion_auth->register($username, $password, $email, $additional_data, $active, $notify)) {
			$this->session->set_flashdata('message', $this->lang->line("user_added"));
			redirect("suppliers");
		} else {
			$this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
			$this->data['modal_js'] = $this->site->modal_js();
			$this->data['company'] = $company;
			$this->load->view($this->theme . 'suppliers/add_user', $this->data);
		}
	}

	public function import_csv()
	{
		$this->erp->checkPermissions();
		$this->load->helper('security');
		$this->form_validation->set_rules('csv_file', $this->lang->line("upload_file"), 'xss_clean');

		if ($this->form_validation->run() == true) {

			if (DEMO) {
				$this->session->set_flashdata('warning', $this->lang->line("disabled_in_demo"));
				redirect($_SERVER["HTTP_REFERER"]);
			}

			if (isset($_FILES["csv_file"])) /* if($_FILES['userfile']['size'] > 0) */ {

				$this->load->library('upload');

				$config['upload_path'] = 'assets/uploads/csv/';
				$config['allowed_types'] = 'csv';
				$config['max_size'] = '15360';
				$config['overwrite'] = TRUE;

				$this->upload->initialize($config);

				if (!$this->upload->do_upload('csv_file')) {

					$error = $this->upload->display_errors();
					$this->session->set_flashdata('error', $error);
					redirect("suppliers");
				}

				$csv = $this->upload->file_name;

				$arrResult = array();
				$handle = fopen("assets/uploads/csv/" . $csv, "r");
				if ($handle) {
					while (($row = fgetcsv($handle, 5001, ",")) !== FALSE) {
						$arrResult[] = $row;
					}
					fclose($handle);
				}
				$titles = array_shift($arrResult);

				$keys = array('company', 'name', 'email', 'phone', 'address', 'city', 'state', 'postal_code', 'country', 'vat_no', 'cf1', 'cf2', 'cf3', 'cf4', 'cf5', 'cf6');

				$final = array();

				foreach ($arrResult as $key => $value) {
					$final[] = array_combine($keys, $value);
				}
				$rw = 2;
				foreach ($final as $csv) {
					if ($this->companies_model->getCompanyByEmail($csv['email'])) {
						$this->session->set_flashdata('error', $this->lang->line("check_supplier_email") . " (" . $csv['email'] . "). " . $this->lang->line("supplier_already_exist") . " (" . $this->lang->line("line_no") . " " . $rw . ")");
						redirect("suppliers");
					}
					$rw++;
				}
				foreach ($final as $record) {
					$record['group_id'] = 4;
					$record['group_name'] = 'supplier';
					$data[] = $record;
				}
                //$this->erp->print_arrays($data);
			}

		} elseif ($this->input->post('import')) {
			$this->session->set_flashdata('error', validation_errors());
			redirect('customers');
		}

		if ($this->form_validation->run() == true && !empty($data)) {
			if ($this->companies_model->addCompanies($data)) {
				$this->session->set_flashdata('message', $this->lang->line("suppliers_added"));
				redirect('suppliers');
			}
		} else {

			$this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
			$this->data['modal_js'] = $this->site->modal_js();
			$this->load->view($this->theme . 'suppliers/import', $this->data);
		}
	}

	public function delete($id = NULL)
	{
		$this->erp->checkPermissions(NULL, TRUE);

		if ($this->input->get('id')) {
			$id = $this->input->get('id');
		}

		if ($this->accounts_model->deleteChartAccount($id)) {
			echo $this->lang->line("deleted_chart_account");
		} else {
			$this->session->set_flashdata('warning', lang('chart_account_x_deleted_have_account'));
			die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . (isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : site_url('welcome')) . "'; }, 0);</script>");
		}
	}

	public function suggestions($term = NULL, $limit = NULL)
	{
        // $this->erp->checkPermissions('index');
		if ($this->input->get('term')) {
			$term = $this->input->get('term', TRUE);
		}
		$limit = $this->input->get('limit', TRUE);
		$rows['results'] = $this->companies_model->getSupplierSuggestions($term, $limit);
		echo json_encode($rows);
	}

	public function getSupplier($id = NULL)
	{
        // $this->erp->checkPermissions('index');
		$row = $this->companies_model->getCompanyByID($id);
		echo json_encode(array(array('id' => $row->id, 'text' => $row->company)));
	}

	public function account_actions()
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
						if (!$this->accounts_model->deleteChartAccount($id)) {
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
					$this->excel->getActiveSheet()->setTitle(lang('account'));
					$this->excel->getActiveSheet()->SetCellValue('A1', lang('account_code'));
					$this->excel->getActiveSheet()->SetCellValue('B1', lang('account_name'));
					$this->excel->getActiveSheet()->SetCellValue('C1', lang('parent_account'));
					$this->excel->getActiveSheet()->SetCellValue('D1', lang('account_section'));
					$styleArray = array(
                        'font'  => array(
                            'bold'  => true
                        )
                    );

                    $this->excel->getActiveSheet()->getStyle('A1:D1')->applyFromArray($styleArray);
					$row = 2;
					foreach ($_POST['val'] as $id) {
						$account = $this->site->getAccountByID($id);
						$this->excel->getActiveSheet()->SetCellValue('A' . $row, $account->accountcode);
						$this->excel->getActiveSheet()->SetCellValue('B' . $row, $account->accountname);
						$this->excel->getActiveSheet()->SetCellValue('C' . $row, $account->parent_acc);
						$this->excel->getActiveSheet()->SetCellValue('D' . $row, $account->sectionname);
						$row++;
					}

					$this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
					$this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
					$this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
					$this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
					$this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$filename = 'Account_' . date('Y_m_d_H_i_s');
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

	public function receivable_actions($wh=null)
	{
		if($wh){
			$wh = explode('-', $wh);
		}
		// $this->erp->print_arrays($wh);
		$this->form_validation->set_rules('form_action', lang("form_action"), 'required');

		if ($this->form_validation->run() == true) {

			if (!empty($_POST['val'])) {
				if ($this->input->post('form_action') == 'delete') {

					$error = false;
					foreach ($_POST['val'] as $id) {
						if (!$this->accounts_model->deleteChartAccount($id)) {
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
				if ($this->input->post('form_action') == 'combine') {
                    $html = $this->combine_pdf($_POST['val']);
                }

				if ($this->input->post('form_action') == 'export_excel' || $this->input->post('form_action') == 'export_pdf') {
					if($this->Owner || $this->Admin){
					$this->load->library('excel');
					$this->excel->setActiveSheetIndex(0);
					$this->excel->getActiveSheet()->setTitle(lang('acc_receivable'));
					$this->excel->getActiveSheet()->SetCellValue('A1', lang('date'));
					$this->excel->getActiveSheet()->SetCellValue('B1', lang('reference_no'));
					$this->excel->getActiveSheet()->SetCellValue('C1', lang('shop'));
					$this->excel->getActiveSheet()->SetCellValue('D1', lang('customer'));
					$this->excel->getActiveSheet()->SetCellValue('E1', lang('sale_status'));
					$this->excel->getActiveSheet()->SetCellValue('F1', lang('grand_total'));
					$this->excel->getActiveSheet()->SetCellValue('G1', lang('paid'));
					$this->excel->getActiveSheet()->SetCellValue('H1', lang('balance'));
					$this->excel->getActiveSheet()->SetCellValue('I1', lang('payment_status'));
					 $styleArray = array(
                        'font'  => array(
                            'bold'  => true
                        )
                    );

                    $this->excel->getActiveSheet()->getStyle('A1:I1')->applyFromArray($styleArray);

					$row = 2;
					$sum_grand = $sum_paid = $sum_balance = 0;
					foreach ($_POST['val'] as $id) {
						$account = $this->site->getReceivableByID($id);
						$sum_grand += $account->grand_total;
						$sum_paid += $account->paid;
						$sum_balance += $account->balance;
						$this->excel->getActiveSheet()->SetCellValue('A' . $row, $account->date);
						$this->excel->getActiveSheet()->SetCellValue('B' . $row, $account->reference_no." ");
						$this->excel->getActiveSheet()->SetCellValue('C' . $row, $account->biller);
						$this->excel->getActiveSheet()->SetCellValue('D' . $row, $account->customer);
						$this->excel->getActiveSheet()->SetCellValue('E' . $row, $account->sale_status);
						$this->excel->getActiveSheet()->SetCellValue('F' . $row, $account->grand_total);
						$this->excel->getActiveSheet()->SetCellValue('G' . $row, $account->paid);
						$this->excel->getActiveSheet()->SetCellValue('H' . $row, $account->balance);
						$this->excel->getActiveSheet()->SetCellValue('I' . $row, $account->payment_status);
						$new_row = $row+1;
						$this->excel->getActiveSheet()->SetCellValue('F' . $new_row, $sum_grand);
						$this->excel->getActiveSheet()->SetCellValue('G' . $new_row, $sum_paid);
						$this->excel->getActiveSheet()->SetCellValue('H' . $new_row, $sum_balance);

						$row++;
					}
				}else{
					// echo "user";exit();
					$this->load->library('excel');
					$this->excel->setActiveSheetIndex(0);
					$this->excel->getActiveSheet()->setTitle(lang('acc_receivable'));
					$this->excel->getActiveSheet()->SetCellValue('A1', lang('date'));
					$this->excel->getActiveSheet()->SetCellValue('B1', lang('reference_no'));
					$this->excel->getActiveSheet()->SetCellValue('C1', lang('shop'));
					$this->excel->getActiveSheet()->SetCellValue('D1', lang('customer'));
					$this->excel->getActiveSheet()->SetCellValue('E1', lang('sale_status'));
					$this->excel->getActiveSheet()->SetCellValue('F1', lang('grand_total'));
					$this->excel->getActiveSheet()->SetCellValue('G1', lang('paid'));
					$this->excel->getActiveSheet()->SetCellValue('H1', lang('balance'));
					$this->excel->getActiveSheet()->SetCellValue('I1', lang('payment_status'));
					 $styleArray = array(
                        'font'  => array(
                            'bold'  => true
                        )
                    );

                    $this->excel->getActiveSheet()->getStyle('A1:I1')->applyFromArray($styleArray);

					$row = 2;
					$sum_grand = $sum_paid = $sum_balance = 0;
					foreach ($_POST['val'] as $id) {
						$account = $this->site->getReceivableByID($id,$wh);
						$sum_grand += $account->grand_total;
						$sum_paid += $account->paid;
						$sum_balance += $account->balance;
						$this->excel->getActiveSheet()->SetCellValue('A' . $row, $account->date);
						$this->excel->getActiveSheet()->SetCellValue('B' . $row, $account->reference_no." ");
						$this->excel->getActiveSheet()->SetCellValue('C' . $row, $account->biller);
						$this->excel->getActiveSheet()->SetCellValue('D' . $row, $account->customer);
						$this->excel->getActiveSheet()->SetCellValue('E' . $row, $account->sale_status);
						$this->excel->getActiveSheet()->SetCellValue('F' . $row, $account->grand_total);
						$this->excel->getActiveSheet()->SetCellValue('G' . $row, $account->paid);
						$this->excel->getActiveSheet()->SetCellValue('H' . $row, $account->balance);
						$this->excel->getActiveSheet()->SetCellValue('I' . $row, $account->payment_status);
						$new_row = $row+1;
						$this->excel->getActiveSheet()->SetCellValue('F' . $new_row, $sum_grand);
						$this->excel->getActiveSheet()->SetCellValue('G' . $new_row, $sum_paid);
						$this->excel->getActiveSheet()->SetCellValue('H' . $new_row, $sum_balance);

						$row++;
					}
				}

					$this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(19);
					$this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
					$this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
					$this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
					$this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
					$this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
					$this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
					$this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
					$this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
					$this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$filename = 'Acc_Receivable_' . date('Y_m_d_H_i_s');
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

						$this->excel->getActiveSheet()->getStyle('F' . $new_row.'')->getFont()->setBold(true);
						$this->excel->getActiveSheet()->getStyle('G' . $new_row.'')->getFont()->setBold(true);
						$this->excel->getActiveSheet()->getStyle('H' . $new_row.'')->getFont()->setBold(true);

						$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'PDF');
						return $objWriter->save('php://output');
					}
					if ($this->input->post('form_action') == 'export_excel') {
						header('Content-Type: application/vnd.ms-excel');
						header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
						header('Cache-Control: max-age=0');

						$this->excel->getActiveSheet()->getStyle('F' . $new_row.'')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border:: BORDER_THIN);
                    	$this->excel->getActiveSheet()->getStyle('F' . $new_row.'')->getFont()->setBold(true);
                    	$this->excel->getActiveSheet()->getStyle('G' . $new_row.'')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border:: BORDER_THIN);
                    	$this->excel->getActiveSheet()->getStyle('G' . $new_row.'')->getFont()->setBold(true);
                    	$this->excel->getActiveSheet()->getStyle('H' . $new_row.'')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border:: BORDER_THIN);
                    	$this->excel->getActiveSheet()->getStyle('H' . $new_row.'')->getFont()->setBold(true);

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

	public function combine_pdf($val)
    {
        $this->erp->checkPermissions('combine_pdf', null, 'sales');

        foreach ($val as $id) {
            $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
            $inv = $this->sales_model->getInvoiceByID($id);

            $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
            $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
            $this->data['created_by'] = $this->site->getUser($inv->created_by);
            $this->data['updated_by'] = $inv->updated_by ? $this->site->getUser($inv->updated_by) : NULL;
            $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
            $this->data['inv'] = $inv;
            $return = $this->sales_model->getReturnBySID($id);
            $this->data['return_sale'] = $return;
            $this->data['rows'] = $this->sales_model->getAllInvoiceItems($id);
            $this->data['return_rows'] = $inv->return_id ? $this->sale_order_model->getAllInvoiceItems($inv->return_id) : NULL;
            $html_data = $this->load->view($this->theme . 'sales/pdf', $this->data, true);
            if (! $this->Settings->barcode_img) {
                $html_data = preg_replace("'\<\?xml(.*)\?\>'", '', $html_data);
            }

            $html[] = array(
                'content' => $html_data,
                'footer' => $this->data['biller']->invoice_footer,
            );
        }

        $name = lang("sales") . ".pdf";
        $this->erp->generate_pdf($html, $name);

    }

	public function reciept_actions()
	{
		$this->form_validation->set_rules('form_action', lang("form_action"), 'required');

		if ($this->form_validation->run() == true) {
			$from_date = $this->input->post('from_date');
			$to_date = $this->input->post('to_date');
			$inv_from_date = $this->input->post('inv_from_date');
			$inv_to_date = $this->input->post('inv_to_date');

			if (!empty($_POST['val'])) {
				if ($this->input->post('form_action') == 'delete') {

					$error = false;
					foreach ($_POST['val'] as $id) {
						if (!$this->accounts_model->deleteChartAccount($id)) {
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

					$this->excel->getActiveSheet()->mergeCells('A1:I1');
                    $this->excel->getActiveSheet()->setCellValue('A1','Bill Receipt');

                    if ($from_date && $to_date) {
                    	$this->excel->getActiveSheet()->mergeCells('A2:I2');

                    	if ($inv_from_date && $inv_to_date) {
                    		$this->excel->getActiveSheet()->setCellValue('A2','From: '.$from_date .' To: '. $to_date .'  And  From Inv Date: '. $inv_from_date .' To Inv Date '. $inv_to_date);
                    	} else {
                    		$this->excel->getActiveSheet()->setCellValue('A2','From: '.$from_date .' To: '. $to_date);
                    	}

                    	$this->excel->getActiveSheet()->setTitle(lang('bill_reciept'));
						$this->excel->getActiveSheet()->SetCellValue('A3', lang('date'));
						$this->excel->getActiveSheet()->SetCellValue('B3', lang('invoice_date'));
						$this->excel->getActiveSheet()->SetCellValue('C3', lang('payment_ref'));
						$this->excel->getActiveSheet()->SetCellValue('D3', lang('sale_ref'));
						$this->excel->getActiveSheet()->SetCellValue('E3', lang('customer'));
						$this->excel->getActiveSheet()->SetCellValue('F3', lang('note'));
						$this->excel->getActiveSheet()->SetCellValue('G3', lang('paid_by'));
						$this->excel->getActiveSheet()->SetCellValue('H3', lang('amount'));
						$this->excel->getActiveSheet()->SetCellValue('I3', lang('type'));
                    } else {
						$this->excel->getActiveSheet()->setTitle(lang('bill_reciept'));
						$this->excel->getActiveSheet()->SetCellValue('A2', lang('date'));
						$this->excel->getActiveSheet()->SetCellValue('B2', lang('invoice_date'));
						$this->excel->getActiveSheet()->SetCellValue('C2', lang('payment_ref'));
						$this->excel->getActiveSheet()->SetCellValue('D2', lang('sale_ref'));
						$this->excel->getActiveSheet()->SetCellValue('E2', lang('customer'));
						$this->excel->getActiveSheet()->SetCellValue('F2', lang('note'));
						$this->excel->getActiveSheet()->SetCellValue('G2', lang('paid_by'));
						$this->excel->getActiveSheet()->SetCellValue('H2', lang('amount'));
						$this->excel->getActiveSheet()->SetCellValue('I2', lang('type'));

                    }


					$styleArray = array(
                        'font'  => array(
                            'bold'  => true,
                            'size'  => 16
                        )
                    );

                    $styleArray2 = array(
                        'font'  => array(
                            'bold'  => true,
                            'size'  => 12
                        )
                    );

                   	if ($from_date && $to_date) {
                   		$this->excel->getActiveSheet()->getStyle('A1:I1')->applyFromArray($styleArray);
		                $this->excel->getActiveSheet()->getStyle('A2:I2')->applyFromArray($styleArray2);
		                $this->excel->getActiveSheet()->getStyle('A3:I3')->applyFromArray($styleArray2);
		                $this->excel->getActiveSheet()->getStyle('A1:I1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		                $this->excel->getActiveSheet()->getStyle('A3:I3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		                $this->excel->getActiveSheet()->getStyle('A2:I2')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border:: BORDER_THIN);
						$row = 4;
                   	} else {
                   		$this->excel->getActiveSheet()->getStyle('A1:I1')->applyFromArray($styleArray);
		                $this->excel->getActiveSheet()->getStyle('A2:I2')->applyFromArray($styleArray2);
		                $this->excel->getActiveSheet()->getStyle('A1:I1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		                $this->excel->getActiveSheet()->getStyle('A2:I2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		                $this->excel->getActiveSheet()->getStyle('A1:I1')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border:: BORDER_THIN);
						$row = 3;
                   	}
                    $sum_amount=0;
					foreach ($_POST['val'] as $id) {
						$account = $this->site->getRecieptByID($id);

						if ($account->type == 'sent' || $account->type == 'received' || $account->sale_status == 'returned') {
							$sum_amount += $account->amount;
						}

						$this->excel->getActiveSheet()->SetCellValue('A' . $row, $this->erp->hrld($account->date));
						$this->excel->getActiveSheet()->SetCellValue('B' . $row, $this->erp->hrld($account->inv_date));
						$this->excel->getActiveSheet()->SetCellValue('C' . $row, $account->payment_ref." ");
						$this->excel->getActiveSheet()->SetCellValue('D' . $row, $account->sale_ref." ");
						$this->excel->getActiveSheet()->SetCellValue('E' . $row, $account->customer);
						$this->excel->getActiveSheet()->SetCellValue('F' . $row, strip_tags($account->noted));
						$this->excel->getActiveSheet()->SetCellValue('G' . $row, $account->paid_by);
						$this->excel->getActiveSheet()->SetCellValue('H' . $row, $account->amount);
						$this->excel->getActiveSheet()->SetCellValue('I' . $row, $account->type);
						$new_row = $row+1;
						$this->excel->getActiveSheet()->SetCellValue('H' . $new_row, $this->erp->formatMoney($sum_amount));

						$row++;
					}

					$this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
					$this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
					$this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
					$this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
					$this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
					$this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
					$this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
					$this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
					$this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
					$this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$filename = 'Bill_Reciept_' . date('Y_m_d_H_i_s');
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

						$this->excel->getActiveSheet()->getStyle('H' . $new_row.'')->getFont()->setBold(true);

						$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'PDF');
						return $objWriter->save('php://output');
					}
					if ($this->input->post('form_action') == 'export_excel') {
						header('Content-Type: application/vnd.ms-excel');
						header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
						header('Cache-Control: max-age=0');

						$this->excel->getActiveSheet()->getStyle('H' . $new_row.'')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border:: BORDER_THIN);
                    	$this->excel->getActiveSheet()->getStyle('H' . $new_row.'')->getFont()->setBold(true);

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

	public function getBillRecieptAction($pdf = NULL, $xls = null, $biller_id = NULL, $from_date = null, $to_date = null)
	{

		$this->form_validation->set_rules('form_action', lang("form_action"), 'required');

		$from_date = $this->input->post('from_date');
		$to_date = $this->input->post('to_date');
		$this->erp->print_arrays($xls);

		if ($this->form_validation->run() == true) {

			if (!empty($_POST['val'])) {
				if ($this->input->post('form_action') == 'delete') {

					$error = false;
					foreach ($_POST['val'] as $id) {
						if (!$this->accounts_model->deleteChartAccount($id)) {
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

					$this->excel->getActiveSheet()->mergeCells('A1:I1');
                    $this->excel->getActiveSheet()->setCellValue('A1','Bill Receipt');

					$this->excel->getActiveSheet()->setTitle(lang('bill_reciept'));
					$this->excel->getActiveSheet()->SetCellValue('A2', lang('date'));
					$this->excel->getActiveSheet()->SetCellValue('B2', lang('invoice_date'));
					$this->excel->getActiveSheet()->SetCellValue('C2', lang('payment_ref'));
					$this->excel->getActiveSheet()->SetCellValue('D2', lang('sale_ref'));
					$this->excel->getActiveSheet()->SetCellValue('E2', lang('customer'));
					$this->excel->getActiveSheet()->SetCellValue('F2', lang('note'));
					$this->excel->getActiveSheet()->SetCellValue('G2', lang('paid_by'));
					$this->excel->getActiveSheet()->SetCellValue('H2', lang('amount'));
					$this->excel->getActiveSheet()->SetCellValue('I2', lang('type'));

					$styleArray = array(
                        'font'  => array(
                            'bold'  => true,
                            'size'  => 14
                        )
                    );

                    $styleArray2 = array(
                        'font'  => array(
                            'bold'  => true,
                            'size'  => 12
                        )
                    );

                    $this->excel->getActiveSheet()->getStyle('A1:I1')->applyFromArray($styleArray);
                    $this->excel->getActiveSheet()->getStyle('A2:I2')->applyFromArray($styleArray2);
                    $this->excel->getActiveSheet()->getStyle('A2:I2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);




					$row = 3;
                    $sum_amount=0;
					foreach ($_POST['val'] as $id) {
						$account = $this->site->getRecieptByID($id);
						$sum_amount += $account->amount;
						$this->excel->getActiveSheet()->SetCellValue('A' . $row, $this->erp->hrld($account->date));
						$this->excel->getActiveSheet()->SetCellValue('B' . $row, $this->erp->hrld($account->inv_date));
						$this->excel->getActiveSheet()->SetCellValue('C' . $row, $account->payment_ref." ");
						$this->excel->getActiveSheet()->SetCellValue('D' . $row, $account->sale_ref." ");
						$this->excel->getActiveSheet()->SetCellValue('E' . $row, $account->customer);
						$this->excel->getActiveSheet()->SetCellValue('F' . $row, $account->noted);
						$this->excel->getActiveSheet()->SetCellValue('G' . $row, $account->paid_by);
						$this->excel->getActiveSheet()->SetCellValue('H' . $row, $account->amount);
						$this->excel->getActiveSheet()->SetCellValue('I' . $row, $account->type);
						$new_row = $row+1;
						$this->excel->getActiveSheet()->SetCellValue('H' . $new_row, $sum_amount);

						$row++;
					}

					$this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
					$this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
					$this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
					$this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
					$this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
					$this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
					$this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
					$this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
					$this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
					$this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$filename = 'Bill_Reciept_' . date('Y_m_d_H_i_s');
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

						$this->excel->getActiveSheet()->getStyle('H' . $new_row.'')->getFont()->setBold(true);

						$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'PDF');
						return $objWriter->save('php://output');
					}
					if ($this->input->post('form_action') == 'export_excel') {
						header('Content-Type: application/vnd.ms-excel');
						header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
						header('Cache-Control: max-age=0');

						$this->excel->getActiveSheet()->getStyle('H' . $new_row.'')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border:: BORDER_THIN);
                    	$this->excel->getActiveSheet()->getStyle('H' . $new_row.'')->getFont()->setBold(true);

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

	public function payable_actions($wh=null)
	{
		if($wh){
			$wh = explode('-', $wh);
		}
		// $this->erp->print_arrays($wh);

		$this->form_validation->set_rules('form_action', lang("form_action"), 'required');

		if ($this->form_validation->run() == true) {

			if (!empty($_POST['val'])) {
				if ($this->input->post('form_action') == 'delete') {

					$error = false;
					foreach ($_POST['val'] as $id) {
						if (!$this->accounts_model->deleteChartAccount($id)) {
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
				if ($this->input->post('form_action') == 'combine') {
                    $html = $this->combine_pdf_pay($_POST['val']);
                }
				if ($this->input->post('form_action') == 'export_excel' || $this->input->post('form_action') == 'export_pdf') {
					if($this->Owner || $this->Admin){
					$this->load->library('excel');
					$this->excel->setActiveSheetIndex(0);
					$this->excel->getActiveSheet()->setTitle(lang('acc_payable'));
					$this->excel->getActiveSheet()->SetCellValue('A1', lang('date'));
					$this->excel->getActiveSheet()->SetCellValue('B1', lang('reference_no'));
					$this->excel->getActiveSheet()->SetCellValue('C1', lang('po_no'));
					$this->excel->getActiveSheet()->SetCellValue('D1', lang('pr_no'));
					$this->excel->getActiveSheet()->SetCellValue('E1', lang('supplier'));
					$this->excel->getActiveSheet()->SetCellValue('F1', lang('purchase_status'));
					$this->excel->getActiveSheet()->SetCellValue('G1', lang('grand_total'));
					$this->excel->getActiveSheet()->SetCellValue('H1', lang('paid'));
					$this->excel->getActiveSheet()->SetCellValue('I1', lang('balance'));
					$this->excel->getActiveSheet()->SetCellValue('J1', lang('payment_status'));
					 $styleArray = array(
                        'font'  => array(
                            'bold'  => true
                        )
                    );

                    $this->excel->getActiveSheet()->getStyle('A1:J1')->applyFromArray($styleArray);

					$row = 2;
					$sum_paid = $sum_balance = $sum_grand = 0;
					foreach ($_POST['val'] as $id) {
						$account = $this->site->getPayableByID($id);
						$sum_paid += $account->paid;
						$sum_balance += $account->balance;
						$sum_grand += $account->grand_total;
						$this->excel->getActiveSheet()->SetCellValue('A' . $row, $account->date);
						$this->excel->getActiveSheet()->SetCellValue('B' . $row, $account->reference_no." ");
						$this->excel->getActiveSheet()->SetCellValue('C' . $row, $account->order_ref." ");
						$this->excel->getActiveSheet()->SetCellValue('D' . $row, $account->request_ref." ");
						$this->excel->getActiveSheet()->SetCellValue('E' . $row, $account->supplier);
						$this->excel->getActiveSheet()->SetCellValue('F' . $row, $account->status);
						$this->excel->getActiveSheet()->SetCellValue('G' . $row, $account->grand_total);
						$this->excel->getActiveSheet()->SetCellValue('H' . $row, $account->paid);
						$this->excel->getActiveSheet()->SetCellValue('I' . $row, $account->balance);
						$this->excel->getActiveSheet()->SetCellValue('J' . $row, $account->payment_status);
						$new_row = $row+1;
						$this->excel->getActiveSheet()->SetCellValue('G' . $new_row, $sum_grand);
						$this->excel->getActiveSheet()->SetCellValue('H' . $new_row, $sum_paid);
						$this->excel->getActiveSheet()->SetCellValue('I' . $new_row, $sum_balance);

						$row++;
					}
				}else{
					// echo "user";exit();
					$this->load->library('excel');
					$this->excel->setActiveSheetIndex(0);
					$this->excel->getActiveSheet()->setTitle(lang('acc_payable'));
					$this->excel->getActiveSheet()->SetCellValue('A1', lang('date'));
					$this->excel->getActiveSheet()->SetCellValue('B1', lang('reference_no'));
					$this->excel->getActiveSheet()->SetCellValue('C1', lang('po_no'));
					$this->excel->getActiveSheet()->SetCellValue('D1', lang('pr_no'));
					$this->excel->getActiveSheet()->SetCellValue('E1', lang('supplier'));
					$this->excel->getActiveSheet()->SetCellValue('F1', lang('purchase_status'));
					$this->excel->getActiveSheet()->SetCellValue('G1', lang('grand_total'));
					$this->excel->getActiveSheet()->SetCellValue('H1', lang('paid'));
					$this->excel->getActiveSheet()->SetCellValue('I1', lang('balance'));
					$this->excel->getActiveSheet()->SetCellValue('J1', lang('payment_status'));
					 $styleArray = array(
                        'font'  => array(
                            'bold'  => true
                        )
                    );

                    $this->excel->getActiveSheet()->getStyle('A1:J1')->applyFromArray($styleArray);

					$row = 2;
					$sum_paid = $sum_balance = $sum_grand = 0;
					foreach ($_POST['val'] as $id) {
						$account = $this->site->getPayableByID($id,$wh);
						$sum_paid += $account->paid;
						$sum_balance += $account->balance;
						$sum_grand += $account->grand_total;
						$this->excel->getActiveSheet()->SetCellValue('A' . $row, $account->date);
						$this->excel->getActiveSheet()->SetCellValue('B' . $row, $account->reference_no." ");
						$this->excel->getActiveSheet()->SetCellValue('C' . $row, $account->order_ref." ");
						$this->excel->getActiveSheet()->SetCellValue('D' . $row, $account->request_ref." ");
						$this->excel->getActiveSheet()->SetCellValue('E' . $row, $account->supplier);
						$this->excel->getActiveSheet()->SetCellValue('F' . $row, $account->status);
						$this->excel->getActiveSheet()->SetCellValue('G' . $row, $account->grand_total);
						$this->excel->getActiveSheet()->SetCellValue('H' . $row, $account->paid);
						$this->excel->getActiveSheet()->SetCellValue('I' . $row, $account->balance);
						$this->excel->getActiveSheet()->SetCellValue('J' . $row, $account->payment_status);
						$new_row = $row+1;
						$this->excel->getActiveSheet()->SetCellValue('G' . $new_row, $sum_grand);
						$this->excel->getActiveSheet()->SetCellValue('H' . $new_row, $sum_paid);
						$this->excel->getActiveSheet()->SetCellValue('I' . $new_row, $sum_balance);

						$row++;
					}
				}

					$this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
					$this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
					$this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
					$this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
					$this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
					$this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
					$this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(17);
					$this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
					$this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(10);
					$this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
					$this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$filename = 'Acc_Payable_' . date('Y_m_d_H_i_s');
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

						$this->excel->getActiveSheet()->getStyle('G' . $new_row.'')->getFont()->setBold(true);
						$this->excel->getActiveSheet()->getStyle('H' . $new_row.'')->getFont()->setBold(true);
						$this->excel->getActiveSheet()->getStyle('I' . $new_row.'')->getFont()->setBold(true);

						$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'PDF');
						return $objWriter->save('php://output');
					}
					if ($this->input->post('form_action') == 'export_excel') {
						header('Content-Type: application/vnd.ms-excel');
						header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
						header('Cache-Control: max-age=0');

						$this->excel->getActiveSheet()->getStyle('G' . $new_row.'')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border:: BORDER_THIN);
                    	$this->excel->getActiveSheet()->getStyle('G' . $new_row.'')->getFont()->setBold(true);
                    	$this->excel->getActiveSheet()->getStyle('H' . $new_row.'')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border:: BORDER_THIN);
                    	$this->excel->getActiveSheet()->getStyle('H' . $new_row.'')->getFont()->setBold(true);
                    	$this->excel->getActiveSheet()->getStyle('I' . $new_row.'')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border:: BORDER_THIN);
                    	$this->excel->getActiveSheet()->getStyle('I' . $new_row.'')->getFont()->setBold(true);

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

	public function combine_pdf_pay($val)
	{
		$this->erp->checkPermissions('combine_pdf', NULL, 'purchases');

        foreach ($val as $purchase_id) {

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

	public function journal_actions()
	{

		// $this->erp->print_arrays($biller_id);

		$this->form_validation->set_rules('form_action', lang("form_action"), 'required');

		if ($this->form_validation->run() == true) {

			if (!empty($_POST['val'])) {
				if ($this->input->post('form_action') == 'delete') {

					$error = false;
					foreach ($_POST['val'] as $id) {
						if (!$this->accounts_model->deleteChartAccount($id)) {
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
					$this->excel->getActiveSheet()->setTitle(lang('journal'));
					$this->excel->getActiveSheet()->SetCellValue('A1', lang('no'));
					$this->excel->getActiveSheet()->SetCellValue('B1', lang('type'));
					$this->excel->getActiveSheet()->SetCellValue('C1', lang('date'));
					$this->excel->getActiveSheet()->SetCellValue('D1', lang('reference_no'));
					$this->excel->getActiveSheet()->SetCellValue('E1', lang('project'));
					$this->excel->getActiveSheet()->SetCellValue('F1', lang('name'));
					$this->excel->getActiveSheet()->SetCellValue('G1', lang('account_code'));
					$this->excel->getActiveSheet()->SetCellValue('H1', lang('account_name'));
					$this->excel->getActiveSheet()->SetCellValue('I1', lang('note'));
					$this->excel->getActiveSheet()->SetCellValue('J1', lang('debit'));
					$this->excel->getActiveSheet()->SetCellValue('K1', lang('credit'));
					$styleArray = array(
                        'font'  => array(
                            'bold'  => true
                        )
                    );
                    $this->excel->getActiveSheet()->getStyle('A1:K1')->applyFromArray($styleArray);

					$row = 2;
					$sum_debit = $sum_credit = 0;
					foreach ($_POST['val'] as $id) {
						$account = $this->site->getJournalByID($id);
						$sum_debit += $account->debit;
						$sum_credit += $account->credit;
						$this->excel->getActiveSheet()->SetCellValue('A' . $row, $account->g_tran_no." ");
						$this->excel->getActiveSheet()->SetCellValue('B' . $row, $account->tran_type);
						$this->excel->getActiveSheet()->SetCellValue('C' . $row, $account->tran_date);
						$this->excel->getActiveSheet()->SetCellValue('D' . $row, $account->reference_no." ");
						$this->excel->getActiveSheet()->SetCellValue('E' .$row, $account->company);
						$this->excel->getActiveSheet()->SetCellValue('F' .$row, $account->NAME);
						$this->excel->getActiveSheet()->SetCellValue('G' . $row, $account->account_code." ");
						$this->excel->getActiveSheet()->SetCellValue('H' . $row, $account->narrative);
						$this->excel->getActiveSheet()->SetCellValue('I' . $row, $account->description);
						$this->excel->getActiveSheet()->SetCellValue('J' . $row, $account->debit);
						$this->excel->getActiveSheet()->SetCellValue('K' . $row, $account->credit);
						// $this->excel->getActiveSheet()->getStyle('A'.$row.':J'.$row)->applyFromArray($BoStyle);
						$new_row = $row+1;
						$this->excel->getActiveSheet()->SetCellValue('J' . $new_row, $sum_debit);
						$this->excel->getActiveSheet()->SetCellValue('K' . $new_row, $sum_credit);

						$row++;
					}
					// $this->excel->getActiveSheet()->getStyle('A1:J1')->applyFromArray($BoStyle);
					$this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(8);
					$this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
					$this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
					$this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
					$this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
					$this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(13);
					$this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(17);
					$this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
					$this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(10);
					$this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(10);
					$this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(10);
					$this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$filename = 'Journal_' . date('Y_m_d_H_i_s');
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

						$this->excel->getActiveSheet()->getStyle('J' . $new_row.'')->getFont()->setBold(true);
						$this->excel->getActiveSheet()->getStyle('K' . $new_row.'')->getFont()->setBold(true);

						$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'PDF');
						return $objWriter->save('php://output');
					}
					if ($this->input->post('form_action') == 'export_excel') {
						header('Content-Type: application/vnd.ms-excel');
						header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
						header('Cache-Control: max-age=0');

						$this->excel->getActiveSheet()->getStyle('J' . $new_row.'')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border:: BORDER_THIN);
                    	$this->excel->getActiveSheet()->getStyle('J' . $new_row.'')->getFont()->setBold(true);
                    	$this->excel->getActiveSheet()->getStyle('K' . $new_row.'')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border:: BORDER_THIN);
                    	$this->excel->getActiveSheet()->getStyle('K' . $new_row.'')->getFont()->setBold(true);

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

	public function import_journal_csv()
	{
		$this->erp->checkPermissions('import', null, 'accounts');

		$this->load->helper('security');
		$this->form_validation->set_rules('csv_file', $this->lang->line("upload_file"), 'xss_clean');

		if ($this->form_validation->run() == true) {

			if (DEMO) {
				$this->session->set_flashdata('warning', $this->lang->line("disabled_in_demo"));
				redirect($_SERVER["HTTP_REFERER"]);
			}

			if (isset($_FILES["csv_file"])) /* if($_FILES['userfile']['size'] > 0) */ {

				$this->load->library('upload');

				$config['upload_path'] = 'assets/uploads/csv/';
				$config['allowed_types'] = 'csv';
				$config['max_size'] = $this->allowed_file_size;
				$config['overwrite'] = TRUE;

				$this->upload->initialize($config);

				if (!$this->upload->do_upload('csv_file')) {

					$error = $this->upload->display_errors();
					$this->session->set_flashdata('error', $error);
					redirect("account/listJournal");
				}

				$csv = $this->upload->file_name;

				$arrResult = array();
				$handle = fopen("assets/uploads/csv/" . $csv, "r");
				if ($handle) {
					while (($row = fgetcsv($handle, 5000, ",")) !== FALSE) {
						$arrResult[] = $row;
					}
					fclose($handle);
				}
				$titles = array_shift($arrResult);
			//$this->erp->print_arrays($arrResult);
				$keys = array('tran_type', 'tran_no', 'tran_date','account_code', 'narrative', 'amount', 'reference_no', 'description', 'biller_id', 'created_by');

				$final = array();

				foreach ($arrResult as $key => $value) {
					$final[] = array_combine($keys, $value);
				}

                /*$rw = 2;
                foreach ($final as $csv) {
                    if ($this->companies_model->getCompanyByEmail($csv['email'])) {
                        $this->session->set_flashdata('error', $this->lang->line("check_supplier_email") . " (" . $csv['email'] . "). " . $this->lang->line("supplier_already_exist") . " (" . $this->lang->line("line_no") . " " . $rw . ")");
                        redirect("suppliers");
                    }
                    $rw++;
                }*/

				$first = 1;
				$refer = "";
				$i = 0;
				$tr = $this->accounts_model->increaseTranNo();


                foreach ($final as $record) {

						$record['sectionid'] = $this->accounts_model->getSectionIdByCode(trim($record['account_code']));
						//$date = strtr($record['tran_date'], '/', '-');

						$record['tran_date'] = $this->erp->fld(date('d-m-Y', strtotime($record['tran_date'])));
						if($first == 1){
							$tr = $tr + 1;
							$refer = trim($record['reference_no']);
							$first = 2;
						}
						if($refer == trim($record['reference_no'])){
							if($record['tran_no'] == "" || $record['tran_no']  <= $tr){
								$record['tran_no'] = $tr;
								$i = $tr;
								$refer = trim($record['reference_no']);
							}
						}else{
							$i = $i + 1;
							$record['tran_no'] = $i;
							$tr = $i;
							$refer = trim($record['reference_no']);
						}

						$data[] = $record;

                }

				$this->accounts_model->UpdateincreaseTranNo($tr);
                //$this->erp->print_arrays($data);
            }

        }

        if ($this->form_validation->run() == true && !empty($data)) {
        	if ($this->accounts_model->addJournals($data)) {
        		$this->session->set_flashdata('message', $this->lang->line("journal_added"));
        		redirect('account/listJournal');
        	}
        } else {
        	$this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        	$this->data['modal_js'] = $this->site->modal_js();
        	$this->load->view($this->theme . 'accounts/import_journal_csv', $this->data);
        }
    }

    public function import_chart_csv()
    {
    	$this->erp->checkPermissions();
    	$this->load->helper('security');
    	$this->form_validation->set_rules('csv_file', $this->lang->line("upload_file"), 'xss_clean');

    	if ($this->form_validation->run() == true) {

    		if (DEMO) {
    			$this->session->set_flashdata('warning', $this->lang->line("disabled_in_demo"));
    			redirect($_SERVER["HTTP_REFERER"]);
    		}

    		if (isset($_FILES["csv_file"])) /* if($_FILES['userfile']['size'] > 0) */ {

    			$this->load->library('upload');

    			$config['upload_path'] = 'assets/uploads/csv/';
    			$config['allowed_types'] = 'csv';
    			$config['max_size'] = '15360';
    			$config['overwrite'] = TRUE;

    			$this->upload->initialize($config);

    			if (!$this->upload->do_upload('csv_file')) {

    				$error = $this->upload->display_errors();
    				$this->session->set_flashdata('error', $error);
    				redirect("account");
    			}

    			$csv = $this->upload->file_name;

    			$arrResult = array();
    			$handle = fopen("assets/uploads/csv/" . $csv, "r");
    			if ($handle) {
    				while (($row = fgetcsv($handle, 5001, ",")) !== FALSE) {
    					$arrResult[] = $row;
    				}
    				fclose($handle);
    			}
    			$titles = array_shift($arrResult);

    			$keys = array('accountcode','accountname','parent_acc','sectionid','bank','account_tax_id','acc_level','lineage');

    			$final = array();

    			foreach ($arrResult as $key => $value) {
    				$final[] = array_combine($keys, $value);
    			}
                /*$rw = 2;
                foreach ($final as $csv) {
                    if ($this->companies_model->getCompanyByEmail($csv['email'])) {
                        $this->session->set_flashdata('error', $this->lang->line("check_supplier_email") . " (" . $csv['email'] . "). " . $this->lang->line("supplier_already_exist") . " (" . $this->lang->line("line_no") . " " . $rw . ")");
                        redirect("suppliers");
                    }
                    $rw++;
                }*/
                foreach ($final as $record) {
                	$data[] = $record;
                }
                //$this->erp->print_arrays($data);
            }

        }

        if ($this->form_validation->run() == true && !empty($data)) {
        	if ($this->accounts_model->addCharts($data)) {
        		$this->session->set_flashdata('message', $this->lang->line("Chart_Account_Added"));
        		redirect('account');
        	}
        } else {

        	$this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        	$this->data['modal_js'] = $this->site->modal_js();
        	$this->load->view($this->theme . 'accounts/import_chart_csv', $this->data);
        }
    }

    public function checkAccount()
	{
    	$accountcode = $this->input->get('code', TRUE);
    	$row = $this->accounts_model->getAccountCode($accountcode);
    	if ($row) {
    		echo 1;
    	} else {
    		echo 0;
    	}
    }

    public function selling_tax($warehouse_id = NULL)
    {
    	$this->erp->checkPermissions();
    	$this->load->model('reports_model');



    	$this->data['users'] = $this->reports_model->getStaff();
    	$this->data['warehouses'] = $this->site->getAllWarehouses();
    	$this->data['billers'] = $this->site->getAllCompanies('biller');

    	$this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
    	if ($this->Owner || $this->Admin) {
    		$this->data['warehouses'] = $this->site->getAllWarehouses();
    		$this->data['warehouse_id'] = $warehouse_id;
    		$this->data['warehouse'] = $warehouse_id ? $this->site->getWarehouseByID($warehouse_id) : NULL;
    	} else {
    		$this->data['warehouses'] = NULL;
    		$this->data['warehouse_id'] = $this->session->userdata('warehouse_id');
    		$this->data['warehouse'] = $this->session->userdata('warehouse_id') ? $this->site->getWarehouseByID($this->session->userdata('warehouse_id')) : NULL;
    	}


    	$bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('selling_tax')));
    	$meta = array('page_title' => lang('selling_tax'), 'bc' => $bc);
    	$this->page_construct('accounts/selling_tax', $meta, $this->data);
    }

    public function selling_actions()
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
    					if (!$this->accounts_model->deleteChartAccount($id)) {
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
    				$this->excel->getActiveSheet()->setTitle(lang('selling_tax'));
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
    				foreach ($_POST['val'] as $id) {
    					$account = $this->site->getSellingByID($id);
    					$this->excel->getActiveSheet()->SetCellValue('A' . $row, $account->date);
    					$this->excel->getActiveSheet()->SetCellValue('B' . $row, $account->reference_no);
    					$this->excel->getActiveSheet()->SetCellValue('C' . $row, $account->biller);
    					$this->excel->getActiveSheet()->SetCellValue('D' . $row, $account->customer);
    					$this->excel->getActiveSheet()->SetCellValue('E' . $row, $account->sale_status);
    					$this->excel->getActiveSheet()->SetCellValue('F' . $row, $account->grand_total);
    					$this->excel->getActiveSheet()->SetCellValue('G' . $row, $account->paid);
    					$this->excel->getActiveSheet()->SetCellValue('H' . $row, $account->balance);
    					$this->excel->getActiveSheet()->SetCellValue('I' . $row, $account->payment_status);
    					$row++;
    				}

    				$this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
    				$this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
    				$this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
    				$this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    				$filename = 'Selling_Tax_' . date('Y_m_d_H_i_s');
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

    public function purchasing_tax($warehouse_id = NULL)
    {
    	$this->erp->checkPermissions();
    	$this->load->model('reports_model');

    	if(isset($_GET['d']) != ""){
    		$date = $_GET['d'];
    		$this->data['date'] = $date;
    	}

    	$this->data['users'] = $this->reports_model->getStaff();
    	$this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
    	if ($this->Owner || $this->Admin || !$this->session->userdata('warehouse_id')) {
    		$this->data['warehouses'] = $this->site->getAllWarehouses();
    		$this->data['warehouse_id'] = $warehouse_id;
    		$this->data['warehouse'] = $warehouse_id ? $this->site->getWarehouseByID($warehouse_id) : null;
    	} else {
    		$this->data['warehouses'] = null;
    		$this->data['warehouse_id'] = $this->session->userdata('warehouse_id');
    		$this->data['warehouse'] = $this->session->userdata('warehouse_id') ? $this->site->getWarehouseByID($this->session->userdata('warehouse_id')) : null;
    	}

    	$bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('purchasing_tax')));
    	$meta = array('page_title' => lang('purchasing_tax'), 'bc' => $bc);
    	$this->page_construct('accounts/purchasing_tax', $meta, $this->data);
    }

    public function deposits($action = NULL)
    {
    	$this->erp->checkPermissions('index', true, 'accounts');

    	$this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
    	$this->data['action'] = $action;
    	$bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('accounts')));
    	$meta = array('page_title' => lang('deposits'), 'bc' => $bc);
    	$this->page_construct('accounts/deposits', $meta, $this->data);
    }

    public function getDeposits()
	{

    	$return_deposit = anchor('customers/return_deposit/$1', '<i class="fa fa-reply"></i> ' . lang('return_deposit'), 'data-toggle="modal" data-target="#myModal2"');
    	$deposit_note = anchor('customers/deposit_note/$1', '<i class="fa fa-file-text-o"></i> ' . lang('deposit_note'), 'data-toggle="modal" data-target="#myModal2"');
    	$edit_deposit = anchor('customers/edit_deposit/$1', '<i class="fa fa-edit"></i> ' . lang('edit_deposit'), 'data-toggle="modal" data-target="#myModal2"');
    	$delete_deposit = "<a href='#' class='po' title='<b>" . lang("delete_deposit") . "</b>' data-content=\"<p>"
    	. lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('customers/delete_deposit/$1') . "'>"
    	. lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i> "
    	. lang('delete_deposit') . "</a>";

    	$action = '<div class="text-center"><div class="btn-group text-left">'
    	. '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
    	. lang('actions') . ' <span class="caret"></span></button>
    	<ul class="dropdown-menu pull-right" role="menu">
    		<li>' . $deposit_note . '</li>
    		<li>' . $edit_deposit . '</li>
    		<li>' . $return_deposit . '</li>
    		<li>' . $delete_deposit . '</li>
    		<ul>
    		</div></div>';

    		$this->load->library('datatables');
    		$this->datatables
    		->select("deposits.id as dep_id, companies.id AS id , date, reference,companies.name,
			 deposits.amount, deposits.note, CONCAT({$this->db->dbprefix('users')}.first_name, ' ', {$this->db->dbprefix('users')}.last_name) as created_by", false)
    		->from("deposits")
			->join('companies', 'companies.id = deposits.company_id', 'left')
    		->join('users', 'users.id=deposits.created_by', 'left')
    		->where('deposits.amount <>', 0)
    		->add_column("Actions", $action, "dep_id")
			->unset_column('dep_id');

    		echo $this->datatables->generate();
    }

	public function exchange_rate_tax()
	{
	    $action=null;
		$this->erp->checkPermissions('index', true, 'accounts');

		$this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
		$this->data['action'] = $action;
		$this->data['condition_tax']=$this->accounts_model->getConditionTax();
		$bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('accounts')));
		$meta = array('page_title' => lang('exchange_rate_tax'), 'bc' => $bc);
		$this->page_construct('accounts/exchange_rate_tax', $meta, $this->data);
	}

	public function edit_condition_tax($id)
	{
		$this->erp->checkPermissions(false, true);

		$this->data['condition_tax'] = $this->accounts_model->getConditionTaxById($id);
		$this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
		$this->data['modal_js'] = $this->site->modal_js();
		$this->load->view($this->theme . 'accounts/edit_condition_tax', $this->data);
	}

	public function update_exchange_tax_rate($id)
	{
		$data=array(
			'rate'=>$this->input->post('rate')
			);
		$update=$this->accounts_model->update_exchange_tax_rate($id,$data);
		if($update){
			redirect('account/exchange_rate_tax');
		}
	}

	function condition_tax()
	{
		$this->erp->checkPermissions();
		$this->form_validation->set_rules('name', $this->lang->line("name"), 'required');

		if ($this->form_validation->run('account/add_condition_tax') == true) {

			$data = array(
				'code' => 'Salary',
				'name' => $this->input->post('name'),
				'rate' => $this->input->post('rate'),
				'reduct_tax' => $this->input->post('reduct_tax'),
				'min_salary' => $this->input->post('min_salary'),
				'max_salary' => $this->input->post('max_salary')
			);

		}

		if ($this->form_validation->run() == true && $this->accounts_model->addConditionTax($data)) {
			$this->session->set_flashdata('message', $this->lang->line("condition_tax_added"));
			redirect('account/condition_tax');
		} else {
			$this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
			$bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('accounts')));
			$meta = array('page_title' => lang('condition_tax'), 'bc' => $bc);
			$this->page_construct('accounts/condition_tax', $meta, $this->data);
		}
	}

	function getConditionTax()
	{
		$this->erp->checkPermissions('index', true, 'accounts');

		$this->load->library('datatables');
		$this->datatables->select("id,code, name, rate, min_salary, max_salary, reduct_tax")
		->from("condition_tax")
		->where("code", "Salary")
		->add_column("Actions", "<center><a class=\"tip\" title='" . $this->lang->line("edit_condition_tax") . "' href='" . site_url('account/edit_condition/$1') . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-edit\"></i></a>  <a href='#' class='tip po' title='<b>" . $this->lang->line("delete_condition_tax") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('account/delete_condition_tax/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></center>", "id");
		echo $this->datatables->generate();
	}

	function add_condition_tax()
	{
		$this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
		$this->data['modal_js'] = $this->site->modal_js();
		$this->load->view($this->theme . 'accounts/add_condition_tax', $this->data);
	}

	function edit_condition($id = null)
	{
		$this->erp->checkPermissions();
		$this->form_validation->set_rules('name', $this->lang->line("name"), 'required');
		if ($this->form_validation->run('account/edit_condition_tax') == true) {
			$data = array(
				'code' => 'Salary',
				'name' => $this->input->post('name'),
				'rate' => $this->input->post('rate'),
				'reduct_tax' => $this->input->post('reduct_tax'),
				'min_salary' => $this->input->post('min_salary'),
				'max_salary' => $this->input->post('max_salary')
			);

			$ids = $this->input->post('id');
		}
		if ($this->form_validation->run() == true && $this->accounts_model->update_exchange_tax_rate($ids,$data)) {
			$this->session->set_flashdata('message', $this->lang->line("condition_tax_updateed"));
			redirect('account/condition_tax');
		} else {
			$this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
			$this->data['modal_js'] = $this->site->modal_js();
			$this->data['id'] = $id;
			$this->data['data'] = $this->accounts_model->getConditionTaxById($id);
			$this->load->view($this->theme . 'accounts/update_condition_tax', $this->data);
		}
	}

	function delete_condition_tax($id)
	{
		$this->erp->checkPermissions();

		if ($this->accounts_model->deleteConditionTax($id)) {
			$this->session->set_flashdata('message', $this->lang->line("condition_tax_deleted"));
			redirect('account/condition_tax');
		} else {
			$this->session->set_flashdata('message', $this->lang->line("connot_deleted"));
			redirect('account/condition_tax');
		}
	}

	function deposits_action()
	{
		if (!$this->Owner) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER["HTTP_REFERER"]);
        }

        $this->form_validation->set_rules('form_action', lang("form_action"), 'required');

        if ($this->form_validation->run() == true) {

            if (!empty($_POST['val'])) {

                if ($this->input->post('form_action') == 'export_excel' || $this->input->post('form_action') == 'export_pdf') {

                    $this->load->library('excel');
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle(lang('deposits'));
                    $this->excel->getActiveSheet()->SetCellValue('A1', lang('date'));
                    $this->excel->getActiveSheet()->SetCellValue('B1', lang('customer'));
					$this->excel->getActiveSheet()->SetCellValue('C1', lang('amount'));
                    $this->excel->getActiveSheet()->SetCellValue('D1', lang('paid_by'));
                    $this->excel->getActiveSheet()->SetCellValue('E1', lang('created_by'));

                    $row = 2;
					$total_amount = 0;
                    foreach ($_POST['val'] as $id) {
                        $dep = $this->accounts_model->getCustomersDepositByCustomerID($id);
                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $this->erp->hrld($dep->date));
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $dep->name);
                        $this->excel->getActiveSheet()->SetCellValue('C' . $row, $dep->amount);
                        $this->excel->getActiveSheet()->SetCellValue('D' . $row, $dep->paid_by);
						$this->excel->getActiveSheet()->SetCellValue('E' . $row, $dep->created_by);
						$total_amount += $dep->amount;
                        $row++;
                    }

					$this->excel->getActiveSheet()->getStyle("C" . $row . ":C" . $row)->getBorders()
						->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
					$this->excel->getActiveSheet()->SetCellValue('C' . $row, $total_amount);

                    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
					$this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
					$this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(18);
					$this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(25);

                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $filename = 'deposits_' . date('Y_m_d_H_i_s');
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
                $this->session->set_flashdata('error', lang("no_deposit_selected"));
                redirect($_SERVER["HTTP_REFERER"]);
            }
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
	}

	/*function ar_by_customer()
	{
		if($this->input->post('start_date')){
			$start_date =  $this->erp->fld($this->input->post('start_date'));
			$this->data['start_date2'] = trim($start_date);
		}else{
			$start_date =null;
			$this->data['start_date2'] = 0;
		}

		if($this->input->post('end_date')){
			$end_date = $this->erp->fld($this->input->post('end_date'));
			$this->data['end_date2'] = trim($end_date);
		}else{
			$end_date = null;
			$this->data['end_date2'] = 0;
		}

		if($this->input->post('customer')){
			$customer = $this->input->post('customer');
			$this->data['customer2'] = $customer;
		}else{
			$customer = null;
			$this->data['customer2'] = 0;
		}

		if($this->input->post('balance')){
			$balance = $this->input->post('balance');
			$this->data['balance2'] = $balance;
		}else{
			$balance = 'all';
			$this->data['balance2'] = 'all';
		}

		$cust_data[] = "";
		$customers = $this->accounts_model->ar_by_customer($start_date, $end_date, $customer, $balance, 'customer');

		$i=0;
		foreach($customers as $cus){
			$customerDatas = $this->accounts_model->ar_by_customer($cus->start_date, $cus->end_date, $cus->customer_id, $cus->balance, 'detail');
			foreach($customerDatas as $cusData){
				$k = 0;
				foreach($customerDatas as $cusDt) {
					$customerDatas[$k]->payments = $this->accounts_model->ar_by_customer($cus->start_date, $cus->end_date, $cus->customer_id, $cus->balance, 'payment', $cusDt->id);
					$k++;
				}
				$cust_data[$i] = array(
								"customerName" => $cus->customer,
								"customerDatas" => array(
													"supplierId" => $cusData->id,
													"custSO" => $customerDatas
													)

								);
			}
			$i++;
		}

		$this->data['cust_data'] = $cust_data;
		$bc = array( array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('accounts')) );
		$meta = array('page_title' => lang('ar_by_customer'), 'bc' => $bc);
		$this->page_construct('accounts/ar_by_customer', $meta, $this->data);
	}*/
	function ar_by_customer()
	{
		if($this->input->post('start_date')){
			$start_date =  $this->erp->fld($this->input->post('start_date'));
			$this->data['start_date2'] = trim($start_date);
		}else{
			$start_date =null;
			$this->data['start_date2'] = 0;
		}

		if($this->input->post('end_date')){
			$end_date = $this->erp->fld($this->input->post('end_date'));
			$this->data['end_date2'] = trim($end_date);
		}else{
			$end_date = null;
			$this->data['end_date2'] = 0;
		}

		if($this->input->post('customer')){
			$customer = $this->input->post('customer');
			$this->data['customer2'] = $customer;
		}else{
			$customer = null;
			$this->data['customer2'] = 0;
		}

		if($this->input->post('balance')){
			$balance = $this->input->post('balance');
			$this->data['balance2'] = $balance;
		}else{
			$balance = 'all';
			$this->data['balance2'] = 'all';
		}

		$cust_data[] = "";
		$customers = $this->accounts_model->ar_by_customerV2($start_date, $end_date, $customer, $balance);


		$this->data['customers'] = $customers;
		$bc = array( array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('accounts')) );
		$meta = array('page_title' => lang('ar_by_customer'), 'bc' => $bc);
		$this->page_construct('accounts/ar_by_customer', $meta, $this->data);
	}

	function ap_by_supplier()
	{
		if($this->input->post('start_date')){
			$start_date =  $this->erp->fld($this->input->post('start_date'));
			$this->data['start_date2'] = trim($start_date);
		}else{
			$start_date =null;
			$this->data['start_date2'] = 0;
		}

		if($this->input->post('end_date')){
			$end_date = $this->erp->fld($this->input->post('end_date'));
			$this->data['end_date2'] = trim($end_date);
		}else{
			$end_date = null;
			$this->data['end_date2'] = 0;
		}

		if($this->input->post('supplier')){
			$supplier = $this->input->post('supplier');
			$this->data['supplier2'] = $supplier;
		}else{
			$supplier = null;
			$this->data['supplier2'] = 0;
		}

		if($this->input->post('balance')){
			$balance = $this->input->post('balance');
			$this->data['balance2'] = $balance;
		}else{
			$balance = 'all';
			$this->data['balance2'] = 'all';
		}


		$my_data[] = "";
		$suppliers = $this->accounts_model->ap_by_supplier($start_date, $end_date, $supplier, $balance, 'supplier');
		$i=0;
		foreach($suppliers as $sup){
			$supplierDatas = $this->accounts_model->ap_by_supplier($sup->start_date, $sup->end_date, $sup->supplier_id, $sup->balance, 'detail');
			foreach($supplierDatas as $suppData){
				$k = 0;
				foreach($supplierDatas as $sppD) {
					$supplierDatas[$k]->payments = $this->accounts_model->ap_by_supplier($sup->start_date, $sup->end_date, $sup->supplier_id, $sup->balance, 'payment', $sppD->id);;
					$k++;
				}
				$my_data[$i] = array(
								"supplierName" => $sup->supplier,
								"supplierDatas" => array(
													"supplierId" => $suppData->id,
													"suppPO" => $supplierDatas
													)

								);

			}
			$i++;
		}

		$this->data['my_data'] = $my_data;
		$bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('accounts')));
		$meta = array('page_title' => lang('ap_by_supplier'), 'bc' => $bc);
		$this->page_construct('accounts/ap_by_supplier', $meta, $this->data);
	}

	public function checkrefer()
	{
		if($this->input->get('items')){
			$items=$this->input->get('items');
		}else{
			$items = '';
		}

		if(is_array($items)){
			$isAuth = 0;
			$first = 1;

			for($i=0;$i<sizeof($items);$i++){
				$id = $items[$i]['id'];
				$data=$this->accounts_model->checkrefer($id);
				$new_data = $data->customer;

				if($first == 1){
					$str_old = $new_data;
				}

				if($str_old != $new_data){
					$isAuth = 1;
				}

				$first++;
			}
			echo json_encode(array('isAuth'=>$isAuth));
			exit();
		}
		echo json_encode(2);
	}

	public function checkreferPur()
	{
		if($this->input->get('items')){
			$items=$this->input->get('items');
		}else{
			$items = '';
		}

		if(is_array($items)){
			$isAuth = 0;
			$first = 1;

			for($i=0;$i<sizeof($items);$i++){
				$id = $items[$i]['id'];
				$data=$this->accounts_model->checkreferPur($id);
				$new_data = $data->supplier;

				if($first == 1){
					$str_old = $new_data;
				}

				if($str_old != $new_data){
					$isAuth = 1;
				}

				$first++;
			}
			echo json_encode(array('isAuth'=>$isAuth));
			exit();
		}
		echo json_encode(2);
	}

	function arByCustomer($pdf=null, $excel=null,$customer2=null,$start_date2=null,$end_date2=null,$balance2=null)
	{
        if ($pdf || $excel) {

		        $cust_data[] = "";
				$customers = $this->accounts_model->ar_by_customer($start_date2, $end_date2, $customer2, $balance2, 'customer');
				$i=0;

				foreach($customers as $cus){
					$customerDatas = $this->accounts_model->ar_by_customer($cus->start_date, $cus->end_date, $cus->customer_id, $cus->balance, 'detail');
					foreach($customerDatas as $cusData){
						$k = 0;
						foreach($customerDatas as $cusDt) {
							$customerDatas[$k]->payments = $this->accounts_model->ar_by_customer($cus->start_date, $cus->end_date, $cus->customer_id, $cus->balance, 'payment', $cusDt->id);
							$k++;
						}
						$cust_data[$i] = array(
										"customerName" => $cus->customer,
										"customerDatas" => array(
															"supplierId" => $cusData->id,
															"custSO" => $customerDatas
															)
										);
					}
					$i++;
				}

            if (!empty($cust_data)) {
                $this->load->library('excel');
                $this->excel->setActiveSheetIndex(0);
                $this->excel->getActiveSheet()->setTitle(lang('Sales List'));
				$this->excel->getActiveSheet()->SetCellValue('A1', lang('reference_no'));
                $this->excel->getActiveSheet()->SetCellValue('B1', lang('date'));
                $this->excel->getActiveSheet()->SetCellValue('C1', lang('type'));
                $this->excel->getActiveSheet()->SetCellValue('D1', lang('amount'));
                $this->excel->getActiveSheet()->SetCellValue('E1', lang('return'));
                $this->excel->getActiveSheet()->SetCellValue('F1', lang('paid'));
                $this->excel->getActiveSheet()->SetCellValue('G1', lang('deposit'));
                $this->excel->getActiveSheet()->SetCellValue('H1', lang('discount'));
				$this->excel->getActiveSheet()->SetCellValue('I1', lang('balance'));
				 $styleArray = array(
                        'font'  => array(
                            'bold'  => true
                        )
                    );

                    $this->excel->getActiveSheet()->getStyle('A1:I1')->applyFromArray($styleArray);

                $row = 2;
                foreach ($cust_data as $data_row) {
                    $this->excel->getActiveSheet()->SetCellValue('A' . $row, "Customer >> ". $data_row["customerName"]);
                    $this->excel->getActiveSheet()->mergeCells('A'.$row.':I'.$row);

                    $subTotal = $subReturn = $subDeposit = $subPaid = $subDiscount = $gbalance = 0;
                    foreach ($data_row['customerDatas']['custSO'] as $value) {
                    	$row++;
                    	$subTotal += $value->grand_total;
						$subReturn += $value->amount_return;
						$subDeposit += $value->amount_deposit;
						$subDiscount += $value->order_discount;
                    	$sub_balance = ($value->grand_total - $value->amount_return - $value->amount_deposit - $value->order_discount);
                    	$gbalance	+= $sub_balance;
                    	$type = (explode('-', $value->reference_no)[0]=='INV'?"Invoice":(explode('/', $value->reference_no)[0]=='SALE'?"Sale":"Not Assigned"));
                    	$this->excel->getActiveSheet()->SetCellValue('A' . $row, $value->reference_no." ");
                    	$this->excel->getActiveSheet()->SetCellValue('B' . $row, $value->date);
                    	$this->excel->getActiveSheet()->SetCellValue('C' . $row, $type);
                    	$this->excel->getActiveSheet()->SetCellValue('D' . $row, $this->erp->formatDecimal($value->grand_total));
                    	$this->excel->getActiveSheet()->SetCellValue('E' . $row, $this->erp->formatDecimal($value->amount_return));
                    	$this->excel->getActiveSheet()->SetCellValue('F' . $row, '');
                    	$this->excel->getActiveSheet()->SetCellValue('G' . $row, $this->erp->formatDecimal($value->amount_deposit));
                    	$this->excel->getActiveSheet()->SetCellValue('H' . $row, $this->erp->formatDecimal($value->order_discount));
                    	$this->excel->getActiveSheet()->SetCellValue('I' . $row, $this->erp->formatDecimal($sub_balance));
                    	if(is_array($value->payments)){
	                    	foreach ($value->payments as $cusPmt) {
	                    		$row++;
	                    		$subPaid += abs($cusPmt->amount);
	                    		$typeRV = (explode('/', $cusPmt->reference_no)[0]=='RV'?"Payment":(explode('-', $cusPmt->reference_no)[0]=='RV'?"Payment":"Not Assigned"));
	                    		$this->excel->getActiveSheet()->SetCellValue('A' . $row, $cusPmt->reference_no);
	                    		$this->excel->getActiveSheet()->SetCellValue('B' . $row, $cusPmt->date);
	                    		$this->excel->getActiveSheet()->SetCellValue('C' . $row, $typeRV);
	                    		$this->excel->getActiveSheet()->SetCellValue('D' . $row, '');
	                    		$this->excel->getActiveSheet()->SetCellValue('E' . $row, '');
	                    		$this->excel->getActiveSheet()->SetCellValue('F' . $row, $this->erp->formatDecimal($cusPmt->amount));
	                    		$this->excel->getActiveSheet()->SetCellValue('G' . $row, '');
	                    		$this->excel->getActiveSheet()->SetCellValue('H' . $row, '');
	                    		$this->excel->getActiveSheet()->SetCellValue('I' . $row, $this->erp->formatDecimal($sub_balance - abs($cusPmt->amount)));
	                    	}
                    	}
                   }

                    $row++;
                    $gbalance -= abs($cusPmt->amount);
					$sub_balance -= abs($cusPmt->amount);

                    $this->excel->getActiveSheet()->SetCellValue('C' . $row, 'Total >>');
                    $this->excel->getActiveSheet()->SetCellValue('D' . $row, $this->erp->formatDecimal($subTotal));
                    $this->excel->getActiveSheet()->SetCellValue('E' . $row, $this->erp->formatDecimal($subReturn));
                    $this->excel->getActiveSheet()->SetCellValue('F' . $row, $this->erp->formatDecimal($subPaid));
                    $this->excel->getActiveSheet()->SetCellValue('G' . $row, $this->erp->formatDecimal($subDeposit));
                    $this->excel->getActiveSheet()->SetCellValue('H' . $row, $this->erp->formatDecimal($subDiscount));
                    $this->excel->getActiveSheet()->SetCellValue('I' . $row, $this->erp->formatDecimal($gbalance));
                    if($excel){
	                	$this->excel->getActiveSheet()->getStyle('C' . $row.'')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border:: BORDER_THIN);
	                    $this->excel->getActiveSheet()->getStyle('C' . $row.'')->getFont()->setBold(true);
	                    $this->excel->getActiveSheet()->getStyle('D' . $row.'')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border:: BORDER_THIN);
	                    $this->excel->getActiveSheet()->getStyle('D' . $row.'')->getFont()->setBold(true);
	                    $this->excel->getActiveSheet()->getStyle('E' . $row.'')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border:: BORDER_THIN);
	                    $this->excel->getActiveSheet()->getStyle('E' . $row.'')->getFont()->setBold(true);
	                    $this->excel->getActiveSheet()->getStyle('F' . $row.'')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border:: BORDER_THIN);
	                    $this->excel->getActiveSheet()->getStyle('F' . $row.'')->getFont()->setBold(true);
	                    $this->excel->getActiveSheet()->getStyle('G' . $row.'')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border:: BORDER_THIN);
	                    $this->excel->getActiveSheet()->getStyle('G' . $row.'')->getFont()->setBold(true);
	                    $this->excel->getActiveSheet()->getStyle('H' . $row.'')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border:: BORDER_THIN);
	                    $this->excel->getActiveSheet()->getStyle('H' . $row.'')->getFont()->setBold(true);
	                    $this->excel->getActiveSheet()->getStyle('I' . $row.'')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border:: BORDER_THIN);
	                    $this->excel->getActiveSheet()->getStyle('I' . $row.'')->getFont()->setBold(true);
	                }

	                $row++;
                }

                $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
                $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
                $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
                $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
                $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
				$this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
				$this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
                $filename = lang('ar_by_customer');
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

	function list_ar_aging_actions($wh=null)
	{
		if($wh){
			$wh = explode('-', $wh);
		}
		// $this->erp->print_arrays($wh);

		$this->form_validation->set_rules('form_action', lang("form_action"), 'required');
		if ($this->form_validation->run() == true) {
			$ware = $this->input->post('warehouse2');
			$created = $this->input->post('created_by2');
			$biller = $this->input->post('biller2');
			if($this->input->post('start_date2')){
				$Sdate = $this->erp->fld($this->input->post('start_date2'));
			}else{
				$Sdate = null;
			}
			if($this->input->post('end_date2')){
				$Edate = $this->erp->fld($this->input->post('end_date2'));
			}else{
				$Edate = null;
			}


    		if (!empty($_POST['val'])) {
        		if ($this->input->post('form_action') == 'export_excel1'|| $this->input->post('form_action') == 'export_pdf1') {
        			if($this->Owner || $this->Admin){
        				// echo "owner";exit();
        			$this->load->library('excel');
					$this->excel->setActiveSheetIndex(0);
					$this->excel->getActiveSheet()->setTitle(lang('list_ar_aging'));
					$this->excel->getActiveSheet()->SetCellValue('A1', lang('customer'));
					$this->excel->getActiveSheet()->SetCellValue('B1', lang('grand_total'));
					$this->excel->getActiveSheet()->SetCellValue('C1', lang('paid'));
					$this->excel->getActiveSheet()->SetCellValue('D1', lang('balance'));
					$this->excel->getActiveSheet()->SetCellValue('E1', lang('ar_number'));
					$this->excel->getActiveSheet()->SetCellValue('F1', lang('date'));
					 $styleArray = array(
                        'font'  => array(
                            'bold'  => true
                        )
                    );

                    $this->excel->getActiveSheet()->getStyle('A1:F1')->applyFromArray($styleArray);
                   $row = 2;
                   $sum_grand = $sum_paid = $sum_balance = $sum_arNum = 0;
					foreach ($_POST['val'] as $id) {
						$account = $this->site->getARaging($id,$ware,$created,$biller,$Sdate,$Edate);
						$sum_grand += $account->grand_total;
						$sum_paid += $account->paid;
						$sum_balance += $account->balance;
						$sum_arNum += $account->ar_number;
						$this->excel->getActiveSheet()->SetCellValue('A' . $row, $account->customer);
						$this->excel->getActiveSheet()->SetCellValue('B' . $row, $this->erp->formatDecimal($account->grand_total));
						$this->excel->getActiveSheet()->SetCellValue('C' . $row, $this->erp->formatDecimal($account->paid));
						$this->excel->getActiveSheet()->SetCellValue('D' . $row, $this->erp->formatDecimal($account->balance));
						$this->excel->getActiveSheet()->SetCellValue('E' . $row, $account->ar_number);
						$new_row = $row+1;
						$this->excel->getActiveSheet()->SetCellValue('B' . $new_row, $this->erp->formatDecimal($sum_grand));
						$this->excel->getActiveSheet()->SetCellValue('C' . $new_row, $this->erp->formatDecimal($sum_paid));
						$this->excel->getActiveSheet()->SetCellValue('D' . $new_row, $this->erp->formatDecimal($sum_balance));
						$this->excel->getActiveSheet()->SetCellValue('E' . $new_row, $sum_arNum);

						$row++;
                	}
                }else{
                	// echo "user";exit();
                	$this->load->library('excel');
					$this->excel->setActiveSheetIndex(0);
					$this->excel->getActiveSheet()->setTitle(lang('list_ar_aging'));
					$this->excel->getActiveSheet()->SetCellValue('A1', lang('customer'));
					$this->excel->getActiveSheet()->SetCellValue('B1', lang('grand_total'));
					$this->excel->getActiveSheet()->SetCellValue('C1', lang('paid'));
					$this->excel->getActiveSheet()->SetCellValue('D1', lang('balance'));
					$this->excel->getActiveSheet()->SetCellValue('E1', lang('ar_number'));
					$this->excel->getActiveSheet()->SetCellValue('F1', lang('date'));
					 $styleArray = array(
                        'font'  => array(
                            'bold'  => true
                        )
                    );

                    $this->excel->getActiveSheet()->getStyle('A1:F1')->applyFromArray($styleArray);
                   $row = 2;
                   $sum_grand = $sum_paid = $sum_balance = $sum_arNum = 0;
					foreach ($_POST['val'] as $id) {
						$account = $this->site->getARaging($id,$ware,$created,$biller,$Sdate,$Edate,$wh);
						$sum_grand += $account->grand_total;
						$sum_paid += $account->paid;
						$sum_balance += $account->balance;
						$sum_arNum += $account->ar_number;
						$this->excel->getActiveSheet()->SetCellValue('A' . $row, $account->customer);
						$this->excel->getActiveSheet()->SetCellValue('B' . $row, $this->erp->formatDecimal($account->grand_total));
						$this->excel->getActiveSheet()->SetCellValue('C' . $row, $this->erp->formatDecimal($account->paid));
						$this->excel->getActiveSheet()->SetCellValue('D' . $row, $this->erp->formatDecimal($account->balance));
						$this->excel->getActiveSheet()->SetCellValue('E' . $row, $account->ar_number);
						$new_row = $row+1;
						$this->excel->getActiveSheet()->SetCellValue('B' . $new_row, $this->erp->formatDecimal($sum_grand));
						$this->excel->getActiveSheet()->SetCellValue('C' . $new_row, $this->erp->formatDecimal($sum_paid));
						$this->excel->getActiveSheet()->SetCellValue('D' . $new_row, $this->erp->formatDecimal($sum_balance));
						$this->excel->getActiveSheet()->SetCellValue('E' . $new_row, $sum_arNum);

						$row++;
                	}
                }

					$this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(18);
					$this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
					$this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
					$this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
					$this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(14);
					$this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(20);

                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $filename = 'list_ar_aging_' . date('Y_m_d_H_i_s');
					if ($this->input->post('form_action') == 'export_pdf1') {
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

						$this->excel->getActiveSheet()->getStyle('B' . $new_row.'')->getFont()->setBold(true);
						$this->excel->getActiveSheet()->getStyle('C' . $new_row.'')->getFont()->setBold(true);
						$this->excel->getActiveSheet()->getStyle('D' . $new_row.'')->getFont()->setBold(true);
						$this->excel->getActiveSheet()->getStyle('E' . $new_row.'')->getFont()->setBold(true);

						$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'PDF');
						return $objWriter->save('php://output');
					}
					if ($this->input->post('form_action') == 'export_excel1') {
						header('Content-Type: application/vnd.ms-excel');
						header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
						header('Cache-Control: max-age=0');

						$this->excel->getActiveSheet()->getStyle('B' . $new_row.'')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border:: BORDER_THIN);
						$this->excel->getActiveSheet()->getStyle('B' . $new_row.'')->getFont()->setBold(true);
						$this->excel->getActiveSheet()->getStyle('C' . $new_row.'')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border:: BORDER_THIN);
						$this->excel->getActiveSheet()->getStyle('C' . $new_row.'')->getFont()->setBold(true);
						$this->excel->getActiveSheet()->getStyle('D' . $new_row.'')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border:: BORDER_THIN);
						$this->excel->getActiveSheet()->getStyle('D' . $new_row.'')->getFont()->setBold(true);
						$this->excel->getActiveSheet()->getStyle('E' . $new_row.'')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border:: BORDER_THIN);
						$this->excel->getActiveSheet()->getStyle('E' . $new_row.'')->getFont()->setBold(true);

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

	function list_ar_aging_actions2()
	{
	 $this->form_validation->set_rules('form_action2', lang("form_action"), 'required');
		 if ($this->form_validation->run() == true) {
    		if (!empty($_POST['val'])) {
        		if ($this->input->post('form_action2') == 'export_excel2'|| $this->input->post('form_action2') == 'export_pdf2') {

        			$this->load->library('excel');
					$this->excel->setActiveSheetIndex(0);
					$this->excel->getActiveSheet()->setTitle(lang('list_ar_aging'));
					$this->excel->getActiveSheet()->SetCellValue('A1', lang('customer'));
					$this->excel->getActiveSheet()->SetCellValue('B1', lang('grand_total'));
					$this->excel->getActiveSheet()->SetCellValue('C1', lang('paid'));
					$this->excel->getActiveSheet()->SetCellValue('D1', lang('balance'));
					$this->excel->getActiveSheet()->SetCellValue('E1', lang('ar_number'));

                   $row = 2;$sum_grand=0;$sum_paid=0;$sum_balance=0;$sum_arNum=0;
					foreach ($_POST['val'] as $id) {
						$account = $this->site->getARaging2($id);
						$sum_grand += $account->grand_total;
						$sum_paid += $account->paid;
						$sum_balance += $account->balance;
						$sum_arNum += $account->ar_number;

						$this->excel->getActiveSheet()->SetCellValue('A' . $row, $account->customer);
						$this->excel->getActiveSheet()->SetCellValue('B' . $row, $account->grand_total);
						$this->excel->getActiveSheet()->SetCellValue('C' . $row, $account->paid);
						$this->excel->getActiveSheet()->SetCellValue('D' . $row, $account->balance);
						$this->excel->getActiveSheet()->SetCellValue('E' . $row, $account->ar_number);
						$new_row = $row+1;
						$this->excel->getActiveSheet()->SetCellValue('B' . $new_row, $sum_grand);
						$this->excel->getActiveSheet()->SetCellValue('C' . $new_row, $sum_paid);
						$this->excel->getActiveSheet()->SetCellValue('D' . $new_row, $sum_balance);
						$this->excel->getActiveSheet()->SetCellValue('E' . $new_row, $sum_arNum);

						$row++;
                	}

					$this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
					$this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
					$this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
					$this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
					$this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
					$this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(30);

                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $filename = 'Bill_Reciept_' . date('Y_m_d_H_i_s');

					if ($this->input->post('form_action') == 'export_pdf2') {
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

						$this->excel->getActiveSheet()->getStyle('B' . $new_row.'')->getFont()->setBold(true);
						$this->excel->getActiveSheet()->getStyle('C' . $new_row.'')->getFont()->setBold(true);
						$this->excel->getActiveSheet()->getStyle('D' . $new_row.'')->getFont()->setBold(true);
						$this->excel->getActiveSheet()->getStyle('E' . $new_row.'')->getFont()->setBold(true);

						$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'PDF');
						return $objWriter->save('php://output');
					}
					if ($this->input->post('form_action2') == 'export_excel2') {
						header('Content-Type: application/vnd.ms-excel');
						header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
						header('Cache-Control: max-age=0');

						$this->excel->getActiveSheet()->getStyle('B' . $new_row.'')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border:: BORDER_THIN);
						$this->excel->getActiveSheet()->getStyle('B' . $new_row.'')->getFont()->setBold(true);
						$this->excel->getActiveSheet()->getStyle('C' . $new_row.'')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border:: BORDER_THIN);
						$this->excel->getActiveSheet()->getStyle('C' . $new_row.'')->getFont()->setBold(true);
						$this->excel->getActiveSheet()->getStyle('D' . $new_row.'')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border:: BORDER_THIN);
						$this->excel->getActiveSheet()->getStyle('D' . $new_row.'')->getFont()->setBold(true);
						$this->excel->getActiveSheet()->getStyle('E' . $new_row.'')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border:: BORDER_THIN);
						$this->excel->getActiveSheet()->getStyle('E' . $new_row.'')->getFont()->setBold(true);

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

	function list_ar_aging_actions3()
	{
		echo "num3";exit();
	 $this->form_validation->set_rules('form_action2', lang("form_action"), 'required');
		 if ($this->form_validation->run() == true) {
    		if (!empty($_POST['val'])) {
        		if ($this->input->post('form_action2') == 'export_excel2'|| $this->input->post('form_action2') == 'export_pdf2') {

        			$this->load->library('excel');
					$this->excel->setActiveSheetIndex(0);
					$this->excel->getActiveSheet()->setTitle(lang('list_ar_aging'));
					$this->excel->getActiveSheet()->SetCellValue('A1', lang('customer'));
					$this->excel->getActiveSheet()->SetCellValue('B1', lang('grand_total'));
					$this->excel->getActiveSheet()->SetCellValue('C1', lang('paid'));
					$this->excel->getActiveSheet()->SetCellValue('D1', lang('balance'));
					$this->excel->getActiveSheet()->SetCellValue('E1', lang('ar_number'));

                   $row = 2;
					foreach ($_POST['val'] as $id) {
						$account = $this->site->getARaging2($id);
						$sum_grand += $account->grand_total;
						$sum_paid += $account->paid;
						$sum_balance += $account->balance;
						$sum_arNum += $account->ar_number;

						$this->excel->getActiveSheet()->SetCellValue('A' . $row, $account->customer);
						$this->excel->getActiveSheet()->SetCellValue('B' . $row, $account->grand_total);
						$this->excel->getActiveSheet()->SetCellValue('C' . $row, $account->paid);
						$this->excel->getActiveSheet()->SetCellValue('D' . $row, $account->balance);
						$this->excel->getActiveSheet()->SetCellValue('E' . $row, $account->ar_number);
						$new_row = $row+1;
						$this->excel->getActiveSheet()->SetCellValue('B' . $new_row, $sum_grand);
						$this->excel->getActiveSheet()->SetCellValue('C' . $new_row, $sum_paid);
						$this->excel->getActiveSheet()->SetCellValue('D' . $new_row, $sum_balance);
						$this->excel->getActiveSheet()->SetCellValue('E' . $new_row, $sum_arNum);

						$row++;
                	}

					$this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
					$this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
					$this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
					$this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
					$this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
					$this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(30);

                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $filename = 'Bill_Reciept_' . date('Y_m_d_H_i_s');

					if ($this->input->post('form_action') == 'export_pdf2') {
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

						$this->excel->getActiveSheet()->getStyle('B' . $new_row.'')->getFont()->setBold(true);
						$this->excel->getActiveSheet()->getStyle('C' . $new_row.'')->getFont()->setBold(true);
						$this->excel->getActiveSheet()->getStyle('D' . $new_row.'')->getFont()->setBold(true);
						$this->excel->getActiveSheet()->getStyle('E' . $new_row.'')->getFont()->setBold(true);

						$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'PDF');
						return $objWriter->save('php://output');
					}
					if ($this->input->post('form_action2') == 'export_excel2') {
						header('Content-Type: application/vnd.ms-excel');
						header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
						header('Cache-Control: max-age=0');

						$this->excel->getActiveSheet()->getStyle('B' . $new_row.'')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border:: BORDER_THIN);
						$this->excel->getActiveSheet()->getStyle('B' . $new_row.'')->getFont()->setBold(true);
						$this->excel->getActiveSheet()->getStyle('C' . $new_row.'')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border:: BORDER_THIN);
						$this->excel->getActiveSheet()->getStyle('C' . $new_row.'')->getFont()->setBold(true);
						$this->excel->getActiveSheet()->getStyle('D' . $new_row.'')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border:: BORDER_THIN);
						$this->excel->getActiveSheet()->getStyle('D' . $new_row.'')->getFont()->setBold(true);
						$this->excel->getActiveSheet()->getStyle('E' . $new_row.'')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border:: BORDER_THIN);
						$this->excel->getActiveSheet()->getStyle('E' . $new_row.'')->getFont()->setBold(true);

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

	function list_ar_aging_actions4()
	{
	echo "num4";exit();
	 $this->form_validation->set_rules('form_action2', lang("form_action"), 'required');
		 if ($this->form_validation->run() == true) {
    		if (!empty($_POST['val'])) {
        		if ($this->input->post('form_action2') == 'export_excel2'|| $this->input->post('form_action2') == 'export_pdf2') {

        			$this->load->library('excel');
					$this->excel->setActiveSheetIndex(0);
					$this->excel->getActiveSheet()->setTitle(lang('list_ar_aging'));
					$this->excel->getActiveSheet()->SetCellValue('A1', lang('customer'));
					$this->excel->getActiveSheet()->SetCellValue('B1', lang('grand_total'));
					$this->excel->getActiveSheet()->SetCellValue('C1', lang('paid'));
					$this->excel->getActiveSheet()->SetCellValue('D1', lang('balance'));
					$this->excel->getActiveSheet()->SetCellValue('E1', lang('ar_number'));

                   $row = 2;
					foreach ($_POST['val'] as $id) {
						$account = $this->site->getARaging2($id);
						$sum_grand += $account->grand_total;
						$sum_paid += $account->paid;
						$sum_balance += $account->balance;
						$sum_arNum += $account->ar_number;

						$this->excel->getActiveSheet()->SetCellValue('A' . $row, $account->customer);
						$this->excel->getActiveSheet()->SetCellValue('B' . $row, $account->grand_total);
						$this->excel->getActiveSheet()->SetCellValue('C' . $row, $account->paid);
						$this->excel->getActiveSheet()->SetCellValue('D' . $row, $account->balance);
						$this->excel->getActiveSheet()->SetCellValue('E' . $row, $account->ar_number);
						$new_row = $row+1;
						$this->excel->getActiveSheet()->SetCellValue('B' . $new_row, $sum_grand);
						$this->excel->getActiveSheet()->SetCellValue('C' . $new_row, $sum_paid);
						$this->excel->getActiveSheet()->SetCellValue('D' . $new_row, $sum_balance);
						$this->excel->getActiveSheet()->SetCellValue('E' . $new_row, $sum_arNum);

						$row++;
                	}

					$this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
					$this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
					$this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
					$this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
					$this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
					$this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(30);

                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $filename = 'Bill_Reciept_' . date('Y_m_d_H_i_s');

					if ($this->input->post('form_action') == 'export_pdf2') {
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

						$this->excel->getActiveSheet()->getStyle('B' . $new_row.'')->getFont()->setBold(true);
						$this->excel->getActiveSheet()->getStyle('C' . $new_row.'')->getFont()->setBold(true);
						$this->excel->getActiveSheet()->getStyle('D' . $new_row.'')->getFont()->setBold(true);
						$this->excel->getActiveSheet()->getStyle('E' . $new_row.'')->getFont()->setBold(true);

						$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'PDF');
						return $objWriter->save('php://output');
					}
					if ($this->input->post('form_action2') == 'export_excel2') {
						header('Content-Type: application/vnd.ms-excel');
						header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
						header('Cache-Control: max-age=0');

						$this->excel->getActiveSheet()->getStyle('B' . $new_row.'')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border:: BORDER_THIN);
						$this->excel->getActiveSheet()->getStyle('B' . $new_row.'')->getFont()->setBold(true);
						$this->excel->getActiveSheet()->getStyle('C' . $new_row.'')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border:: BORDER_THIN);
						$this->excel->getActiveSheet()->getStyle('C' . $new_row.'')->getFont()->setBold(true);
						$this->excel->getActiveSheet()->getStyle('D' . $new_row.'')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border:: BORDER_THIN);
						$this->excel->getActiveSheet()->getStyle('D' . $new_row.'')->getFont()->setBold(true);
						$this->excel->getActiveSheet()->getStyle('E' . $new_row.'')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border:: BORDER_THIN);
						$this->excel->getActiveSheet()->getStyle('E' . $new_row.'')->getFont()->setBold(true);

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

	function list_ar_aging_actions5()
	{
	echo "num5";exit();
	 $this->form_validation->set_rules('form_action2', lang("form_action"), 'required');
		 if ($this->form_validation->run() == true) {
    		if (!empty($_POST['val'])) {
        		if ($this->input->post('form_action2') == 'export_excel2'|| $this->input->post('form_action2') == 'export_pdf2') {

        			$this->load->library('excel');
					$this->excel->setActiveSheetIndex(0);
					$this->excel->getActiveSheet()->setTitle(lang('list_ar_aging'));
					$this->excel->getActiveSheet()->SetCellValue('A1', lang('customer'));
					$this->excel->getActiveSheet()->SetCellValue('B1', lang('grand_total'));
					$this->excel->getActiveSheet()->SetCellValue('C1', lang('paid'));
					$this->excel->getActiveSheet()->SetCellValue('D1', lang('balance'));
					$this->excel->getActiveSheet()->SetCellValue('E1', lang('ar_number'));

                   $row = 2;
					foreach ($_POST['val'] as $id) {
						$account = $this->site->getARaging2($id);
						$sum_grand += $account->grand_total;
						$sum_paid += $account->paid;
						$sum_balance += $account->balance;
						$sum_arNum += $account->ar_number;

						$this->excel->getActiveSheet()->SetCellValue('A' . $row, $account->customer);
						$this->excel->getActiveSheet()->SetCellValue('B' . $row, $account->grand_total);
						$this->excel->getActiveSheet()->SetCellValue('C' . $row, $account->paid);
						$this->excel->getActiveSheet()->SetCellValue('D' . $row, $account->balance);
						$this->excel->getActiveSheet()->SetCellValue('E' . $row, $account->ar_number);
						$new_row = $row+1;
						$this->excel->getActiveSheet()->SetCellValue('B' . $new_row, $sum_grand);
						$this->excel->getActiveSheet()->SetCellValue('C' . $new_row, $sum_paid);
						$this->excel->getActiveSheet()->SetCellValue('D' . $new_row, $sum_balance);
						$this->excel->getActiveSheet()->SetCellValue('E' . $new_row, $sum_arNum);

						$row++;
                	}

					$this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
					$this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
					$this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
					$this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
					$this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
					$this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(30);

                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $filename = 'Bill_Reciept_' . date('Y_m_d_H_i_s');

					if ($this->input->post('form_action') == 'export_pdf2') {
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

						$this->excel->getActiveSheet()->getStyle('B' . $new_row.'')->getFont()->setBold(true);
						$this->excel->getActiveSheet()->getStyle('C' . $new_row.'')->getFont()->setBold(true);
						$this->excel->getActiveSheet()->getStyle('D' . $new_row.'')->getFont()->setBold(true);
						$this->excel->getActiveSheet()->getStyle('E' . $new_row.'')->getFont()->setBold(true);

						$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'PDF');
						return $objWriter->save('php://output');
					}
					if ($this->input->post('form_action2') == 'export_excel2') {
						header('Content-Type: application/vnd.ms-excel');
						header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
						header('Cache-Control: max-age=0');

						$this->excel->getActiveSheet()->getStyle('B' . $new_row.'')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border:: BORDER_THIN);
						$this->excel->getActiveSheet()->getStyle('B' . $new_row.'')->getFont()->setBold(true);
						$this->excel->getActiveSheet()->getStyle('C' . $new_row.'')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border:: BORDER_THIN);
						$this->excel->getActiveSheet()->getStyle('C' . $new_row.'')->getFont()->setBold(true);
						$this->excel->getActiveSheet()->getStyle('D' . $new_row.'')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border:: BORDER_THIN);
						$this->excel->getActiveSheet()->getStyle('D' . $new_row.'')->getFont()->setBold(true);
						$this->excel->getActiveSheet()->getStyle('E' . $new_row.'')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border:: BORDER_THIN);
						$this->excel->getActiveSheet()->getStyle('E' . $new_row.'')->getFont()->setBold(true);

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

	function apBySupplier($pdf=null, $excel=null,$start_date2=null, $end_date2=null, $supplier2=null, $balance2=null)
	{
		if ($pdf || $excel) {
			$my_data[] = "";
			$suppliers = $this->accounts_model->ap_by_supplier($start_date2, $end_date2, $supplier2, $balance2, 'supplier', null);

			$i=0;
			foreach($suppliers as $sup){
				$supplierDatas = $this->accounts_model->ap_by_supplier($sup->start_date, $sup->end_date, $sup->supplier_id, $sup->balance, 'detail');
				foreach($supplierDatas as $suppData){

					$k = 0;
					foreach($supplierDatas as $sppD) {
						$supplierDatas[$k]->payments = $this->accounts_model->ap_by_supplier($sup->start_date, $sup->end_date, $sup->supplier_id, $sup->balance, 'payment', $sppD->id);;
						$k++;
					}
					$my_data[$i] = array(
									"supplierName" => $sup->supplier,
									"supplierDatas" => array(
														"supplierId" => $suppData->id,
														"suppPO" => $supplierDatas
														)
									);
				}
				$i++;
			}
			// $this->erp->print_arrays($my_data);
			if (!empty($my_data)) {
				$this->load->library('excel');
				$this->excel->setActiveSheetIndex(0);
				$this->excel->getActiveSheet()->setTitle(lang('Sales List'));
				$this->excel->getActiveSheet()->SetCellValue('A1', lang('reference_no'));
				$this->excel->getActiveSheet()->SetCellValue('B1', lang('date'));
				$this->excel->getActiveSheet()->SetCellValue('C1', lang('type'));
				$this->excel->getActiveSheet()->SetCellValue('D1', lang('amount'));
				$this->excel->getActiveSheet()->SetCellValue('E1', lang('return'));
				$this->excel->getActiveSheet()->SetCellValue('F1', lang('paid'));
				$this->excel->getActiveSheet()->SetCellValue('G1', lang('deposit'));
				$this->excel->getActiveSheet()->SetCellValue('H1', lang('discount'));
				$this->excel->getActiveSheet()->SetCellValue('I1', lang('balance'));
				$styleArray = array(
                        'font'  => array(
                            'bold'  => true
                        )
                    );

                    $this->excel->getActiveSheet()->getStyle('A1:I1')->applyFromArray($styleArray);

				$row = 2;
				foreach ($my_data as $data_row) {
					$this->excel->getActiveSheet()->SetCellValue('A' . $row, "Supplier >> ". $data_row["supplierName"]);
					$this->excel->getActiveSheet()->mergeCells('A'.$row.':I'.$row);

					$subTotal = $subReturn = $subDeposit = $subPaid = $subDiscount = $gbalance= 0;

					foreach ($data_row['supplierDatas']['suppPO'] as $value) {
						$row++;
						$subTotal += $value->grand_total;
						$subReturn += $value->amount_return;
						$subDeposit += $value->amount_deposit;
						$subDiscount += $value->order_discount;
						$sub_balance = ($value->grand_total - $value->amount_return - $value->amount_deposit - $value->order_discount);
						$gbalance += $sub_balance;
						$type = (explode('/', $value->reference_no)[0]=='PO'?"Purchase":(explode('/', $value->reference_no)[0]=='PV'?"Payment":"Not Assigned"));
						$this->excel->getActiveSheet()->SetCellValue('A' . $row, $value->reference_no." ");
						$this->excel->getActiveSheet()->SetCellValue('B' . $row, $value->date);
						$this->excel->getActiveSheet()->SetCellValue('C' . $row, $type);
						$this->excel->getActiveSheet()->SetCellValue('D' . $row, $value->grand_total);
						$this->excel->getActiveSheet()->SetCellValue('E' . $row, $value->amount_return);
						$this->excel->getActiveSheet()->SetCellValue('F' . $row,'');
						$this->excel->getActiveSheet()->SetCellValue('G' . $row, $value->amount_deposit);
						$this->excel->getActiveSheet()->SetCellValue('H' . $row, $value->order_discount);
						$this->excel->getActiveSheet()->SetCellValue('I' . $row, $sub_balance);

						if (is_array($value->payments)) {
							foreach ($value->payments as $cusPmt) {
								$row++;
								$subPaid += abs($cusPmt->amount);

								$typePV = (explode('/', $cusPmt->reference_no)[0]=='PO'?"Purchase":(explode('/', $cusPmt->reference_no)[0]=='PV'?"Payment":"Not Assigned"));

								$this->excel->getActiveSheet()->SetCellValue('A' . $row, $cusPmt->reference_no." ");
								$this->excel->getActiveSheet()->SetCellValue('B' . $row, $cusPmt->date);
								$this->excel->getActiveSheet()->SetCellValue('C' . $row, $typePV);
								$this->excel->getActiveSheet()->SetCellValue('D' . $row, '');
								$this->excel->getActiveSheet()->SetCellValue('E' . $row, '');
								$this->excel->getActiveSheet()->SetCellValue('F' . $row, $cusPmt->amount);
								$this->excel->getActiveSheet()->SetCellValue('G' . $row, '');
								$this->excel->getActiveSheet()->SetCellValue('H' . $row, '');
								$this->excel->getActiveSheet()->SetCellValue('I' . $row, $sub_balance - abs($cusPmt->amount));
							}
						}
					}

					$row++;
					$gbalance -= abs($subPaid);
					$sub_balance -= abs($cusPmt->amount);

					$this->excel->getActiveSheet()->SetCellValue('C' . $row, 'Total >>');
					$this->excel->getActiveSheet()->SetCellValue('D' . $row, $subTotal);
					$this->excel->getActiveSheet()->SetCellValue('E' . $row, $subReturn);
					$this->excel->getActiveSheet()->SetCellValue('F' . $row, $subPaid);
					$this->excel->getActiveSheet()->SetCellValue('G' . $row, $subDeposit);
					$this->excel->getActiveSheet()->SetCellValue('H' . $row, $subDiscount);
					$this->excel->getActiveSheet()->SetCellValue('I' . $row, $gbalance);

					if($excel){
						$this->excel->getActiveSheet()->getStyle('C' . $row.'')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border:: BORDER_THIN);
						$this->excel->getActiveSheet()->getStyle('C' . $row.'')->getFont()->setBold(true);
						$this->excel->getActiveSheet()->getStyle('D' . $row.'')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border:: BORDER_THIN);
						$this->excel->getActiveSheet()->getStyle('D' . $row.'')->getFont()->setBold(true);
						$this->excel->getActiveSheet()->getStyle('E' . $row.'')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border:: BORDER_THIN);
						$this->excel->getActiveSheet()->getStyle('E' . $row.'')->getFont()->setBold(true);
						$this->excel->getActiveSheet()->getStyle('F' . $row.'')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border:: BORDER_THIN);
						$this->excel->getActiveSheet()->getStyle('F' . $row.'')->getFont()->setBold(true);
						$this->excel->getActiveSheet()->getStyle('G' . $row.'')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border:: BORDER_THIN);
						$this->excel->getActiveSheet()->getStyle('G' . $row.'')->getFont()->setBold(true);
						$this->excel->getActiveSheet()->getStyle('H' . $row.'')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border:: BORDER_THIN);
						$this->excel->getActiveSheet()->getStyle('H' . $row.'')->getFont()->setBold(true);
						$this->excel->getActiveSheet()->getStyle('I' . $row.'')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border:: BORDER_THIN);
						$this->excel->getActiveSheet()->getStyle('I' . $row.'')->getFont()->setBold(true);
					}

					$row++;
				}

				$this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
				$this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
				$this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
				$this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
				$this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
				$this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
				$this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
				$this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
				$this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
				$filename = lang('A/P_by_supplier');
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
					$this->session->unset_userdata('Sdate');
					$this->session->unset_userdata('Edate');
					$this->session->unset_userdata('supplier');
					$this->session->unset_userdata('balance');
					exit();
				}

			}

			$this->session->set_flashdata('error', lang('nothing_found'));
			redirect($_SERVER["HTTP_REFERER"]);

		}
	}
}
