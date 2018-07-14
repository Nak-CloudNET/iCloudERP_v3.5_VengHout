<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Taxes_reports extends MY_Controller
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

        $this->lang->load('taxes', $this->Settings->language);
		$this->lang->load('accounts', $this->Settings->language);
        $this->load->library('form_validation');
        $this->load->model('reports_model');
		$this->load->model('taxes_reports_model');
		$this->load->model('sales_model');
		$this->load->model('companies_model');
		$this->load->model('accounts_model');
		$this->load->model('taxes_model');
        
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
        
    }
	
	function purchase_journal_list(){
		$this->erp->checkPermissions();
        $this->data['confirm_tax'] = $this->taxes_reports_model->getConfirmTax_purch();
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('Purchase Journal List')));
        $meta = array('page_title' => lang('purchase_journal_list'), 'bc' => $bc);
        $this->page_construct('taxes_reports/purchase_journal_list', $meta, $this->data);
	}
	
	function sales_journal_list(){
		$this->erp->checkPermissions();
        
		$this->data['confirm_tax'] = $this->taxes_reports_model->getConfirmTax();
		
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('Sales Journal List')));
        $meta = array('page_title' => lang('sales_journal_list'), 'bc' => $bc);
        $this->page_construct('taxes_reports/sales_journal_list', $meta, $this->data);
	}
	
	function sales_jounal_view_form($month=NULL,$year=NULL,$group_id=NULL){
		$this->erp->checkPermissions();
        $this->data['confirm_tax']   = $this->taxes_reports_model->getConfirmTax();
		$this->data['company']       = $this->taxes_reports_model->company_info($group_id);
		$this->data['sales_list']    = $this->taxes_reports_model->v_sale_journal_list($month,$year,$group_id);
		$this->data['exchange_rate'] = $this->taxes_model->getExchangeRate('KHM');
        $this->load->view($this->theme .'taxes_reports/sales_journal_form', $this->data);
	}
	
	
	function purc_jounal_view_form($month=NULL,$year=NULL,$group_id=NULL) {
		$this->erp->checkPermissions();
        $this->data['confirm_tax'] = $this->taxes_reports_model->getConfirmTax_purch();
		$this->data['company'] = $this->taxes_reports_model->company_info($group_id);
		$this->data['purc_list'] = $this->taxes_reports_model->v_purch_journal_list($month,$year,$group_id);
		$this->data['exchange_rate'] = $this->taxes_model->getExchangeRate('KHM');
        $this->load->view($this->theme .'taxes_reports/purchase_journal_form', $this->data);
	}
	
	function update_journal_date(){	
		$this->erp->checkPermissions();
		$data['date']  = $this->input->get('journal_date');
		$data['month'] = $this->input->get('month');;
		$data['year']  = $this->input->get('year');
		$type 		   = $this->input->get('type');
		if($type=='PURC'){
			$help=$this->taxes_reports_model->update_journal_date_pur($data);
		}else if($type=='SALE'){
			$help=$this->taxes_reports_model->update_journal_date($data);
		}
			 
	}
	
	function update_journal_loc(){	
		$this->erp->checkPermissions();
		$data['location']  = $this->input->get('location');
		$data['month']     = $this->input->get('month');
		$data['year']      = $this->input->get('year');
		$type 			   = $this->input->get('type');
		if($type=='PURC'){
			$help=$this->taxes_reports_model->update_journal_loc_pur($data);
		}else if($type=='SALE'){
			$help=$this->taxes_reports_model->update_journal_loc($data);
		}
	}
	
	function tax_salary_list(){
		$this->erp->checkPermissions();
		
        $this->data['salary_taxes'] = $this->taxes_reports_model->getSalaryTaxList();
		
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('tax_salary_list')));
        $meta = array('page_title' => lang('tax_salary_list'), 'bc' => $bc);
        $this->page_construct('taxes_reports/tax_salary_list', $meta, $this->data);
	}
	
	function getSalaryTax() {
		
		$detail_link = anchor('taxes_reports/salary_tax_report/$1', '<i class="fa fa-file-text-o"></i> ' . lang('view_report'), array('target' => '_blank'));
        $edit_link = anchor('taxes/salary_tax_edit/$1', '<i class="fa fa-edit"></i> ' . lang('edit_salary_tax'), 'class="sledit"');
        $delete_link = "<a href='#' class='po' title='" . lang("delete_report") . "' data-content=\"<p>"
            . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('taxes_reports/salary_tax_delete/$1') . "'>"
            . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i> "
            . lang('delete_report') . "</a>";
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
		$this->datatables->select(
							$this->db->dbprefix('salary_tax').'.id, '.
							$this->db->dbprefix('companies').'.company, '.
							$this->db->dbprefix('salary_tax').'.month, '.
							$this->db->dbprefix('salary_tax').'.year, '.
							$this->db->dbprefix('salary_tax').'.covreturn_start, '.
							$this->db->dbprefix('salary_tax').'.covreturn_end, '.
							$this->db->dbprefix('salary_tax').'.created_date '
						 );
		$this->datatables->join('companies',$this->db->dbprefix('salary_tax').'.group_id = '.$this->db->dbprefix('companies').'.id','INNER');
		$this->datatables->group_by('salary_tax.id');
		$this->datatables->from('salary_tax');
		
		$this->datatables->add_column("Actions", $action, $this->db->dbprefix('salary_tax').'.id');
		
        echo $this->datatables->generate();
	}

	function salary_tax_report($id=NULL){
		$this->erp->checkPermissions();
		
        $this->data['salary_tax'] = $this->taxes_reports_model->getSalaryTaxByID($id);
		$this->data['RES'] = $this->taxes_reports_model->getSalaryTaxFrontByID($id,'RE');
		$this->data['NRES'] = $this->taxes_reports_model->getSalaryTaxFrontByID($id,'NRE');
		$this->data['FBS'] = $this->taxes_reports_model->getSalaryTaxFrontByID($id,'FB');
		$this->data['REBS'] = $this->taxes_reports_model->getSalaryTaxBackByID($id,'REB');
		$this->data['FBBS'] = $this->taxes_reports_model->getSalaryTaxBackByID($id,'FBB');
		
        $this->load->view($this->theme .'taxes_reports/salary_tax_report', $this->data);
	}
	
	function value_added_tax() {
		$this->erp->checkPermissions();
		
        //$this->data['value_add_taxes'] = $this->taxes_reports_model->getValueAddTaxList();
		
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('value_added_tax')));
        $meta = array('page_title' => lang('value_added_tax'), 'bc' => $bc);
        $this->page_construct('taxes_reports/value_added_tax', $meta, $this->data);
	}
	
	function getValueAddTaxList()
	{
		$detail_link = anchor('taxes_reports/value_add_tax_report/$1', '<i class="fa fa-file-text-o"></i> ' . lang('view_report'), array('target' => '_blank'));
        $edit_link = anchor('taxes/value_added_tax_edit/$1', '<i class="fa fa-edit"></i> ' . lang('edit_sale'), 'class="sledit"');
        $delete_link = "<a href='#' class='po' title='" . lang("delete_report") . "' data-content=\"<p>"
            . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('taxes_reports/value_add_tax_delete/$1') . "'>"
            . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i> "
            . lang('delete_report') . "</a>";
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
		$this->datatables->select(
							$this->db->dbprefix('return_value_added_tax').'.id, '.
							$this->db->dbprefix('companies').'.company, '.
							$this->db->dbprefix('return_value_added_tax').'.month, '.
							$this->db->dbprefix('return_value_added_tax').'.year, '.
							$this->db->dbprefix('return_value_added_tax').'.covreturn_start, '.
							$this->db->dbprefix('return_value_added_tax').'.covreturn_end, '.
							$this->db->dbprefix('return_value_added_tax').'.created_date '
						 );
		$this->datatables->join('companies',$this->db->dbprefix('return_value_added_tax').'.group_id = '.$this->db->dbprefix('companies').'.id','INNER');
		$this->datatables->group_by('return_value_added_tax.id');
		$this->datatables->from('return_value_added_tax');
		
		$this->datatables->add_column("Actions", $action, $this->db->dbprefix('return_value_added_tax').'.id');
		
        echo $this->datatables->generate();
	}

	function value_add_tax_report($id=NULL) {
		$this->erp->checkPermissions();
		
        $this->data['front'] = $this->taxes_reports_model->getInfoFrontPage($id);
		$this->data['back_20'] = $this->taxes_reports_model->getInfoBackPage($id,'20');
		$this->data['back_21'] = $this->taxes_reports_model->getInfoBackPage($id,'21');
		$this->data['back_22'] = $this->taxes_reports_model->getInfoBackPage($id,'22');
		

        $this->load->view($this->theme .'taxes_reports/value_add_tax_report', $this->data);
	}
	function value_add_tax_edit($id=NULL) {
		$this->erp->checkPermissions();
		
        $this->data['front'] = $this->taxes_reports_model->getInfoFrontPage($id);
		$this->data['back_20'] = $this->taxes_reports_model->getInfoBackPage($id,'20');
		$this->data['back_21'] = $this->taxes_reports_model->getInfoBackPage($id,'21');
		$this->data['back_22'] = $this->taxes_reports_model->getInfoBackPage($id,'22');
		

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('value_added_tax')));
        $meta = array('page_title' => lang('value_added_tax'), 'bc' => $bc);
		
       $this->page_construct('taxes/value_added_tax_edit', $meta, $this->data);
	}
	function withholding_tax(){
		$this->erp->checkPermissions();
		//$this->data['withholding_tax'] = $this->taxes_reports_model->getWithholdingTaxList();
		
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('withholding_tax')));
        $meta = array('page_title' => lang('withholding_tax'), 'bc' => $bc);
        $this->page_construct('taxes_reports/withholding_tax', $meta, $this->data);
		
	}
	
	function getWithholdingTaxList()
	{
		$detail_link = anchor('taxes_reports/withholding_tax_report/$1', '<i class="fa fa-file-text-o"></i> ' . lang('view_report'), array('target' => '_blank'));
        $edit_link = anchor('sales/edit/$1', '<i class="fa fa-edit"></i> ' . lang('edit_sale'), 'class="sledit"');
        $delete_link = "<a href='#' class='po' title='<b>" . lang("delete_report") . "</b>' data-content=\"<p>"
            . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('taxes_reports/withholding_tax_report_delete/$1') . "'>"
            . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i> "
            . lang('delete_report') . "</a>";
        $action = '<div class="text-center"><div class="btn-group text-left">'
            . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
            . lang('actions') . ' <span class="caret"></span></button>
        <ul class="dropdown-menu pull-right" role="menu">
            <li>' . $detail_link . '</li>
            <li>' . $delete_link . '</li>
        </ul>
		</div></div>';
		
		$this->load->library('datatables');
		$this->datatables->select(
							$this->db->dbprefix('return_withholding_tax').'.id, '.
							$this->db->dbprefix('companies').'.company, '.
							$this->db->dbprefix('return_withholding_tax').'.month, '.
							$this->db->dbprefix('return_withholding_tax').'.year, '.
							$this->db->dbprefix('return_withholding_tax').'.covreturn_start, '.
							$this->db->dbprefix('return_withholding_tax').'.covreturn_end, '.
							$this->db->dbprefix('return_withholding_tax').'.created_date '
						 );
		$this->datatables->join('companies',$this->db->dbprefix('return_withholding_tax').'.group_id = '.$this->db->dbprefix('companies').'.id','INNER');
		$this->datatables->group_by('return_withholding_tax.id');
		$this->datatables->from('return_withholding_tax');
		
		$this->datatables->add_column("Actions", $action, $this->db->dbprefix('return_withholding_tax').'.id');
		
        echo $this->datatables->generate();
	}
	
	function withholding_tax_report($id=NULL) {
		$this->erp->checkPermissions();
		
        $this->data['front'] = $this->taxes_reports_model->getInfoFrontPageWHT($id);
		$this->data['front_25'] = $this->taxes_reports_model->getWithholdingTaxFrontByID($id,'TOR25');
		//$this->erp->print_arrays($this->taxes_reports_model->getWithholdingTaxFrontByID($id,'TOR25'));
		$this->data['front_26'] = $this->taxes_reports_model->getWithholdingTaxFrontByID($id,'TOR26');
		$this->data['back_DWTRT'] = $this->taxes_reports_model->getWithholdingTaxBackByID($id,'DWTRT');
		
		$this->data['back_DWTRNT'] = $this->taxes_reports_model->getWithholdingTaxBackByID($id,'DWTRNT');
		//$this->erp->print_arrays($this->taxes_reports_model->getInfoBackPage($group_id,'20'));

        $this->load->view($this->theme .'taxes_reports/withholding_tax_report', $this->data);
	}
	
	
	function prepayment_profit_tax_list()
	{
		$this->erp->checkPermissions();
		
        //$this->data['return_taxes'] = $this->taxes_reports_model->getReturnTaxList();
		
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('prepayment_of_profit_tax_list')));
        $meta = array('page_title' => lang('prepayment_of_profit_tax_list'), 'bc' => $bc);
        $this->page_construct('taxes_reports/prepayment_profit_tax_list', $meta, $this->data);
	}
	
	function getPrepaymentList()
	{
		$detail_link = anchor('taxes_reports/prepayment_profit_tax_report/$1', '<i class="fa fa-file-text-o"></i> ' . lang('view_report'), array('target' => '_blank'));
        $edit_link = anchor('sales/edit/$1', '<i class="fa fa-edit"></i> ' . lang('edit_sale'), 'class="sledit"');
        $delete_link = "<a href='#' class='po' title='<b>" . lang("delete_report") . "</b>' data-content=\"<p>"
            . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('taxes_reports/prepayment_profit_tax_delete/$1') . "'>"
            . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i> "
            . lang('delete_report') . "</a>";
        $action = '<div class="text-center"><div class="btn-group text-left">'
            . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
            . lang('actions') . ' <span class="caret"></span></button>
        <ul class="dropdown-menu pull-right" role="menu">
            <li>' . $detail_link . '</li>
			<li>' . $delete_link . '</li>
        </ul>
		</div></div>';
		
		$this->load->library('datatables');
		$this->datatables->select(
							$this->db->dbprefix('return_tax_front').'.id, '.
							$this->db->dbprefix('companies').'.company, '.
							$this->db->dbprefix('return_tax_front').'.month, '.
							$this->db->dbprefix('return_tax_front').'.year, '.
							$this->db->dbprefix('return_tax_front').'.covreturn_start, '.
							$this->db->dbprefix('return_tax_front').'.covreturn_end, '.
							$this->db->dbprefix('return_tax_front').'.created_date '
						 );
		$this->datatables->join('companies',$this->db->dbprefix('return_tax_front').'.group_id = '.$this->db->dbprefix('companies').'.id','INNER');
		$this->datatables->group_by('return_tax_front.id');
		$this->datatables->from('return_tax_front');
		
		$this->datatables->add_column("Actions", $action, $this->db->dbprefix('return_tax_front').'.id');
		
        echo $this->datatables->generate();
	}
	
	function prepayment_profit_tax_report($id=NULL)
	{
		$this->erp->checkPermissions();
		
        $this->data['front'] = $this->taxes_reports_model->getReturnTaxFront($id);
		$this->data['back_SGP'] = $this->taxes_reports_model->getReturnTaxBack($id,'SGP');
		$this->data['back_SS'] = $this->taxes_reports_model->getReturnTaxBack($id,'SS');
		//$this->erp->print_arrays($this->taxes_reports_model->getReturnTaxBack($id,'SS'));

        $this->load->view($this->theme .'taxes_reports/prepayment_profit_tax_report', $this->data);
	}
	function value_add_tax_delete($id){
		
		$this->erp->checkPermissions(NULL, TRUE);

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        if ($this->taxes_reports_model->delete_value_add_tax($id)) {
			if($this->input->is_ajax_request()) {
                echo lang("value_add_tax_deleted"); die();
            }
            $this->session->set_flashdata('message', lang('value_add_tax_deleted'));
            redirect('taxes_reports/value_added_tax');
        }
	}
	
	
	function salary_tax_delete($id){
		$this->erp->checkPermissions(NULL, TRUE);

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        if ($this->taxes_reports_model->salary_tax_delete($id)) {
			if($this->input->is_ajax_request()) {
                echo lang("salary_tax_deleted"); die();
            }
            $this->session->set_flashdata('message', lang('salary_tax_deleted'));
            redirect('taxes_reports/tax_salary_list');
        }
	}
	
	function prepayment_profit_tax_delete($id){
		$this->erp->checkPermissions(NULL, TRUE);

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        if ($this->taxes_reports_model->prepayment_profit_tax_delete($id)) {
			if($this->input->is_ajax_request()) {
                echo lang("prepayment_profit_tax_deleted"); die();
            }
            $this->session->set_flashdata('message', lang('prepayment_profit_tax_deleted'));
            redirect('taxes_reports/prepayment_profit_tax_list');
        }
	}
	
	function withholding_tax_report_delete($id){
		$this->erp->checkPermissions(NULL, TRUE);

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        if ($this->taxes_reports_model->withholding_tax_report_delete($id)) {
			if($this->input->is_ajax_request()) {
                echo lang("withholding_tax_report_deleted"); die();
            }
            $this->session->set_flashdata('message', lang('withholding_tax_report_deleted'));
            redirect('taxes_reports/withholding_tax');
        }
	}
	
	function employee_salary_tax(){
		$this->load->model('employee_modal');
		if($this->input->post('employee')){
			$employee_id = $this->input->post('employee');
		}else{
			$employee_id = NULL;
		}
		
		if ($this->input->post('month')) {
            $month = $this->input->post('month');
        } else {
            $month = date('m');
        }
		
		if ($this->input->post('year')) {
            $year = $this->input->post('year');
        } else {
            $year = date('Y');
        }
		if($this->input->post('epm_type')){
			$epm_type = $this->input->post('epm_type');
		}else{
			$epm_type = NULL;
		}
		if ($this->input->post('emp_id')){
			$emp_id = $this->input->post('emp_id');	
		}else{
			$emp_id = NULL;
		}
		/*if($sub_where!=''){
			$sub_where = str_replace('"', "'", $sub_where);
		}*/
		$this->erp->checkPermissions();
		$this->load->model('employee_modal');
		$this->data['employee_salary_taxes'] = $this->taxes_reports_model->getEmployeeSalaryTaxes($emp_id);
		$this->data['empid'] = $employee_id;
		$this->data['employee_salary_taxesHeader'] = $this->taxes_reports_model->getEmployeeSalaryTaxesHeader($employee_id,$year,$month,$epm_type);
		$this->data['employees'] = $this->employee_modal->getEmployees();
		
		//$arr = $this->taxes_reports_model->getEmployeeSalaryTaxes();
		//$this->erp->print_arrays($arr);
		$this->data['types'] = $this->site->getemployeetyp();
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('employee_salary_tax')));
        $meta = array('page_title' => lang('employee_salary_tax'), 'bc' => $bc);
        $this->page_construct('taxes_reports/employee_salary_tax', $meta, $this->data);
	}
	
	function getEmployeeSalaryTaxesReport()
    {
        if ( ! $this->Owner) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            $this->erp->md();
        }
		
		if($this->input->get('employee')){
			$employee_id = $this->input->get('employee');
		}else{
			$employee_id = NULL;
		}
		
		if ($this->input->get('month')) {
            $month = $this->input->get('month');
        } else {
            $month = date('m');
        }
		
		if ($this->input->get('year')) {
            $year = $this->input->get('year');
        } else {
            $year = date('Y');
        }
		
		if ($this->input->get('isCompany')) {
            $isCompany = $this->input->get('isCompany');
        } else {
            $isCompany = 0;
        }
		if($this->input->get('epm_type')){
			$epm_type = $this->input->get('epm_type');
		}else{
			$epm_type = NULL;
		}
		$searchDate = $year . '-' . $month . '-' . date('d');
		$searchDate = date('Y-m-d', strtotime($searchDate));

        $this->load->library('datatables');
        $this->datatables
            ->select("CONCAT(" . $this->db->dbprefix('users') . ".first_name, ' ' ," . $this->db->dbprefix('users') . ".last_name) AS fullname,
				DATE_FORMAT(erp_employee_salary_tax.date_insert, '%Y-%m') AS date,
				erp_employee_salary_tax.position,
				COALESCE(erp_employee_salary_tax.basic_salary,0) as salary,
				COALESCE (
					(SELECT emp_tax.amount_usd
						FROM erp_employee_salary_tax emp_tax
						WHERE DATE_FORMAT(emp_tax.date_insert, '%Y-%m') = '". $year . '-' . $month ."'
						AND emp_tax.employee_id = erp_employee_salary_tax.employee_id
					),
					0
				) AS salary_tax,
				0 as salary_paid,
				COALESCE(erp_employee_salary_tax.spouse,0) as spouse, 
				COALESCE(erp_employee_salary_tax.minor_children,0) as number_of_child, 
				0 as allowance, 
				0 as salary_cal, 
				0 as tax_rate, 
				0 as salary_riel, 
				'' as remark")
            ->from("users")
			->join('employee_salary_tax', 'employee_salary_tax.employee_id = users.id', 'left')
			->join('employee_salary_tax_trigger', 'employee_salary_tax_trigger.id=employee_salary_tax.trigger_id', 'left')
            ->join('groups', 'users.group_id=groups.id', 'left')
            ->group_by('users.id')
            ->where('company_id', NULL)
			//->where('employee_salary_tax_trigger.isCompany', $isCompany)
			->order_by('users.id', 'ASC');
			//->add_column("Actions", "<div class=\"text-center\"><button class=\"btn btn-primary btn-xs form-submit\" type=\"button\"><i class=\"fa fa-floppy-o\"></i></button></div>");
			//->edit_column("allowance", "$1__$2__$3", 'id, allowance, "al"')
			//->edit_column("salary_cal", "$1__$2__$3", 'id, salary_cal, "sc"')
			//->edit_column("tax_rate", "$1__$2__$3", 'id, tax_rate, "tr"')
			//->edit_column("salary_riel", "$1__$2__$3", 'id, salary_riel, "sr"');
		
		if($employee_id){
			$this->datatables->where('users.id' , $employee_id);
		}
		if ($epm_type) {
			$this->datatables->where('users.emp_group', $epm_type);
		}
		if($month && $year){
			$this->datatables->where("DATE_FORMAT(erp_employee_salary_tax.date_insert, '%Y-%m') = " ,  $year . '-' . $month);
		}
        echo $this->datatables->generate();
    }
	
	function vat_gov_tax_report($month=NULL,$year=NULL,$group_id=NULL) {	
		$opening_balance=null;
		$month =date('m');
		$year =date('Y');
		/*
		if($this->input->post('start_date')){
		$date= explode("/",$this->input->post('start_date'));  ;
			$month =$date[0];
			$year =$date[1];
		}
		*/
		if($this->input->post('month')&&$this->input->post('year')){
		$month =$this->input->post('month');
		$year =$this->input->post('year');
		}
		$date_time_for_gl_tran= $year.'-'.$month.'-31 00:00:00';
		$this->erp->checkPermissions();
		$this->data['vat_input'] = $this->taxes_reports_model->v_purch_journal_list($month,$year,$group_id,$tax_type=2);	
		$this->data['vat_output']    = $this->taxes_reports_model->v_sale_journal_list($month,$year,$group_id);
							$getF=$this->taxes_reports_model->get_set_forward($date_time_for_gl_tran);
							if($getF){
							foreach($getF->result() as $get){
								$opening_balance+=$get->amount;
							}}
		$date_time= $year.'-'.$month.'-01 00:00:00';
		$total_input=$this->erp->taxes_reports_model->sum_input($date_time);
		$total_output=$this->erp->taxes_reports_model->sum_output($date_time);
		foreach($total_input as $t_i){
			$get_input=$t_i->sum_input;
		}
		foreach($total_output as $t_o){
			$get_output=$t_o->sum_output;
		}
		
		$opening_balance = abs($opening_balance);				
			if (($get_input+$opening_balance) < $get_output){
				$previous_vat = 0;
			}else{
				$previous_vat = ($get_input+$opening_balance) - $get_output;
			}

		$this->data['previous_vat']=$previous_vat;
		
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('vat_gov_tax_report')));
        $meta = array('page_title' => lang('vat_gov_tax_report'), 'bc' => $bc);
        $this->page_construct('taxes_reports/vat_gov_tax_report', $meta, $this->data);
	}
}