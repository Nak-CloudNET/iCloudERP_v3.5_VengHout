<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Employees extends MY_Controller
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
        $this->lang->load('employee', $this->Settings->language);
        $this->load->library('form_validation');
        $this->load->model('employee_modal');
        $this->load->model('companies_model');
		$this->load->library('ion_auth');
    }
	
	function index()
    {
        $this->erp->checkPermissions();

        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        // $this->data['action'] = $action;
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('list_employees')));
        $meta = array('page_title' => lang('list_employees'), 'bc' => $bc);
        $this->page_construct('employees/index', $meta, $this->data);
    }
	
	function getEmployees()
    {
        if ( ! $this->Owner) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            $this->erp->md();
        }

        $edit_employee = anchor('employees/profile/$1', '<i class="fa fa-edit"></i> ' . lang('edit_employee'));
        $add_cash_advance_link = anchor('employees/add_cash_advance/$1', '<i class="fa fa-money"></i> '. lang('add_cash_advance'), 'data-toggle="modal" data-target="#myModal"');

        $action = '<div class="text-center"><div class="btn-group text-left">'
                    . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
                    . lang('actions') . ' <span class="caret"></span></button>
                        <ul class="dropdown-menu pull-right" role="menu">
                            <li>' . $edit_employee . '</li>
                            <li>' . $add_cash_advance_link . '</li>
                        </ul>
                    </div></div>';

        $this->load->library('datatables');
        $this->datatables
            ->select($this->db->dbprefix('users').".id as id, " . $this->db->dbprefix('users') . ".emp_code, CONCAT(" . $this->db->dbprefix('users') . ".first_name, ' ' ," . $this->db->dbprefix('users') . ".last_name) AS fullname, " . $this->db->dbprefix('users') . ".gender, nationality, position, employeed_date, phone, company, active")
            ->from("users")
            ->join('groups', 'users.group_id=groups.id', 'left')
            ->group_by('users.id')
            ->where('company_id', NULL)
            ->where('user_type', 'employee')
			->order_by('id', 'DESC')
            ->edit_column('active', '$1__$2', 'active, id')
            ->add_column("Actions", $action, "id");

        if (!$this->Owner) {
            $this->datatables->unset_column('id');
        }
        echo $this->datatables->generate();
    }
	
	function getAllEmployees(){
		
        $edit_link = anchor('employees/edit_employee/$1', '<i class="fa fa-edit"></i> ' . lang('edit_employee'), 'class="sledit"');
        $delete_link = "<a href='#' class='po' title='<b>" . lang("delete_employees") . "</b>' data-content=\"<p>"
            . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('employees/delete_employee/$1') . "'>"
            . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i> "
            . lang('delete_employees') . "</a>";
        $action = '<div class="text-center"><div class="btn-group text-left">'
            . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
            . lang('actions') . ' <span class="caret"></span></button>
        <ul class="dropdown-menu pull-right" role="menu">
            <li>' . $edit_link . '</li>
			<li>' . $delete_link . '</li>
        </ul>
		</div></div>';
		
        $this->load->library('datatables');
        $this->datatables
            ->select("id,name,name_kh,company,company_kh,gender,phone,email,position")
            ->from("erp_companies")
			->where("erp_companies.group_name='employee'")
            ->add_column("Actions", $action, "id");
        echo $this->datatables->generate();
	}
	
	function employees_actions()
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
                        $this->employee_modal->delete_employee($id);
                    }
                    $this->session->set_flashdata('message', lang("users_deleted"));
                    redirect($_SERVER["HTTP_REFERER"]);
                }

                if ($this->input->post('form_action') == 'export_excel' || $this->input->post('form_action') == 'export_pdf') {

                    $this->load->library('excel');
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle(lang('sales'));
                    $this->excel->getActiveSheet()->SetCellValue('A1', lang('name'));
                    $this->excel->getActiveSheet()->SetCellValue('B1', lang('name_kh'));
                    $this->excel->getActiveSheet()->SetCellValue('C1', lang('company'));
                    $this->excel->getActiveSheet()->SetCellValue('D1', lang('company_kh'));
                    $this->excel->getActiveSheet()->SetCellValue('E1', lang('gender'));
                    $this->excel->getActiveSheet()->SetCellValue('F1', lang('phone'));
                    $this->excel->getActiveSheet()->SetCellValue('G1', lang('email'));
                    $this->excel->getActiveSheet()->SetCellValue('H1', lang('position'));

                    $row = 2;
                    foreach ($_POST['val'] as $id) {
                        $user = $this->site->getEmployees($id);
                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $user->name);
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $user->name_kh);
                        $this->excel->getActiveSheet()->SetCellValue('C' . $row, $user->company);
                        $this->excel->getActiveSheet()->SetCellValue('D' . $row, $user->company_kh);
                        $this->excel->getActiveSheet()->SetCellValue('E' . $row, $user->gender);
                        $this->excel->getActiveSheet()->SetCellValue('F' . $row, $user->phone." ");
						$this->excel->getActiveSheet()->SetCellValue('G' . $row, $user->email);
                        $this->excel->getActiveSheet()->SetCellValue('H' . $row, $user->position);
                        $row++;
                    }

                    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
                    $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
                    $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $filename = 'employees_' . date('Y_m_d_H_i_s');
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
                $this->session->set_flashdata('error', lang("no_user_selected"));
                redirect($_SERVER["HTTP_REFERER"]);
            }
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }
	
	function add_old()
    {
        if (!$this->Owner) {
            $this->session->set_flashdata('warning', lang("access_denied"));
            redirect($_SERVER["HTTP_REFERER"]);
        }

        $this->data['title'] = "Create Employees";
        //$this->form_validation->set_rules('username', lang("username"), 'trim|is_unique[users.username]');
        $this->form_validation->set_rules('email', lang("email"), 'trim|is_unique[users.email]');
        //$this->form_validation->set_rules('status', lang("status"), 'trim|required');
        //$this->form_validation->set_rules('group', lang("group"), 'trim|required');

        if ($this->form_validation->run() == true) {

            //$username = strtolower($this->input->post('username'));
            $email = strtolower($this->input->post('email'));
            //$password = $this->input->post('password');
            //$notify = $this->input->post('notify');

            $additional_data = array(
                'code' => $this->input->post('code'),
                'name' => $this->input->post('name'),
                'name_kh' => $this->input->post('name_kh'),
                'company' => $this->input->post('company'),
                'company_kh' => $this->input->post('company_kh'),
                'phone' => $this->input->post('phone'),
                'gender' => $this->input->post('gender'),
				'email' => $email,
				'position' => $this->input->post('position'),
				'group_name' => 'employee',
                //'group_id' => $this->input->post('group') ? $this->input->post('group') : '3',
                //'biller_id' => $this->input->post('biller'),
                //'warehouse_id' => $this->input->post('warehouse'),
                //'view_right' => $this->input->post('view_right'),
                //'edit_right' => $this->input->post('edit_right'),
                //'allow_discount' => $this->input->post('allow_discount')
            );
			
			//$this->erp->print_arrays($email);
            //$active = $this->input->post('status');
        }
        if ($this->form_validation->run() == true && $this->companies_model->addCompany($additional_data)) {
            $this->session->set_flashdata('message', $this->ion_auth->messages());
            redirect("employees");

        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('error')));
            $this->data['employee'] = $this->employee_modal->getEmployee();
            $this->data['groups'] = $this->ion_auth->groups()->result_array();
            $this->data['billers'] = $this->site->getAllCompanies('biller');
            $this->data['warehouses'] = $this->site->getAllWarehouses();
            $this->data['reference'] = $this->site->getReference('emp');
            $bc = array(array('link' => site_url('home'), 'page' => lang('home')), array('link' => site_url('employees'), 'page' => lang('list_employees')), array('link' => '#', 'page' => lang('add_employee')));
            $meta = array('page_title' => lang('users'), 'bc' => $bc);
            $this->page_construct('employees/create_employee', $meta, $this->data);
        }
    }
	function add()
    {
        if (!$this->Owner) {
            $this->session->set_flashdata('warning', lang("access_denied"));
            redirect($_SERVER["HTTP_REFERER"]);
        }

        $this->data['title'] = "Create Employees";
        $this->form_validation->set_rules('username', lang("username"), 'trim|is_unique[users.username]');
        $this->form_validation->set_rules('email', lang("email"), 'trim|is_unique[users.email]');
        $this->form_validation->set_rules('status', lang("status"), 'trim|required');
        $this->form_validation->set_rules('group', lang("group"), 'trim|required');

        if ($this->form_validation->run() == true) {

            $username = strtolower($this->input->post('username'));
            $email = strtolower($this->input->post('email'));
            $password = $this->input->post('password');
            $notify = $this->input->post('notify');
			
			$additional_data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'company' => $this->input->post('company'),
                'phone' => $this->input->post('phone'),
                'gender' => $this->input->post('gender'),
                'group_id' => $this->input->post('group') ? $this->input->post('group') : '3',
                'biller_id' => $this->input->post('biller'),
                'warehouse_id' => $this->input->post('warehouse'),
                'view_right' => $this->input->post('view_right'),
                'edit_right' => $this->input->post('edit_right'),
                'allow_discount' => $this->input->post('allow_discount'),
				'date_of_birth' => $this->erp->fsd($this->input->post('date_of_birth')),
				'nationality' => $this->input->post('nationality'),
				'position' => $this->input->post('position'),
				'salary' => $this->input->post('salary'),
				'allowance' => $this->input->post('allowance_'),
				'spouse' => $this->input->post('spouse'),
				'number_of_child' => $this->input->post('number_of_child'),
				'address' => $this->input->post('address'),
				'employeed_date' => $this->erp->fsd($this->input->post('employeed_date')),
				'last_paid' => $this->erp->fsd($this->input->post('last_paid')),
				'annualLeave' => $this->input->post('annual_leave'),
				'sickday' => $this->input->post('annual_sick_days'),
				'note' => $this->input->post('note'),
				'emergency_contact' => $this->input->post('emergency_contact'),
				'emp_code' => $this->input->post('emp_code'),
				'emp_type' => $this->input->post('empType'),
				'tax_salary_type' => $this->input->post('fringe_benefit'),
				'user_type' => 'employee'
            );
            $active = $this->input->post('status');
        }
        if ($this->form_validation->run() == true && $this->ion_auth->register($username, $password, $email, $additional_data, $active, $notify)) {

            $this->session->set_flashdata('message', $this->ion_auth->messages());
            redirect("employees");

        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('error')));
            $this->data['groups'] = $this->ion_auth->groups()->result_array();
            $this->data['billers'] = $this->site->getAllCompanies('biller');
            $this->data['warehouses'] = $this->site->getAllWarehouses();
            $bc = array(array('link' => site_url('home'), 'page' => lang('home')), array('link' => site_url('employees'), 'page' => lang('list_employees')), array('link' => '#', 'page' => lang('add_employee')));
            $meta = array('page_title' => lang('users'), 'bc' => $bc);
            $this->page_construct('employees/create_employee', $meta, $this->data);
        }
    }
	
	function profile($id = NULL)
    {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->in_group('owner') && $id != $this->session->userdata('user_id')) {
            $this->session->set_flashdata('warning', lang("access_denied"));
            redirect($_SERVER["HTTP_REFERER"]);
        }
        if (!$id || empty($id)) {
            redirect('employees');
        }

        $this->data['title'] = lang('profile');

        $user = $this->ion_auth->user($id)->row();
        $groups = $this->ion_auth->groups()->result_array();
        $this->data['csrf'] = $this->_get_csrf_nonce();
        $this->data['user'] = $user;
        $this->data['groups'] = $groups;
        $this->data['billers'] = $this->site->getAllCompanies('biller');
        $this->data['warehouses'] = $this->site->getAllWarehouses();

        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['password'] = array(
            'name' => 'password',
            'id' => 'password',
            'class' => 'form-control',
            'type' => 'password',
            'value' => ''
        );
        $this->data['password_confirm'] = array(
            'name' => 'password_confirm',
            'id' => 'password_confirm',
            'class' => 'form-control',
            'type' => 'password',
            'value' => ''
        );
        $this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
        $this->data['old_password'] = array(
            'name' => 'old',
            'id' => 'old',
            'class' => 'form-control',
            'type' => 'password',
        );
        $this->data['new_password'] = array(
            'name' => 'new',
            'id' => 'new',
            'type' => 'password',
            'class' => 'form-control',
            'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
        );
        $this->data['new_password_confirm'] = array(
            'name' => 'new_confirm',
            'id' => 'new_confirm',
            'type' => 'password',
            'class' => 'form-control',
            'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
        );
        $this->data['user_id'] = array(
            'name' => 'user_id',
            'id' => 'user_id',
            'type' => 'hidden',
            'value' => $user->id,
        );

        $this->data['id'] = $id;

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('employees'), 'page' => lang('list_employees')), array('link' => '#', 'page' => lang('profile')));
        $meta = array('page_title' => lang('profile'), 'bc' => $bc);
        $this->page_construct('employees/profile', $meta, $this->data);
    }

	function _get_csrf_nonce()
    {
        $this->load->helper('string');
        $key = random_string('alnum', 8);
        $value = random_string('alnum', 20);
        $this->session->set_flashdata('csrfkey', $key);
        $this->session->set_flashdata('csrfvalue', $value);

        return array($key => $value);
    }
	
	public function create_employee_salary(){
		
		$this->data['employees'] = $this->employee_modal->getEmployees();
		$this->data['settings'] = $this->site->get_setting();
		
		$bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('create_employee_salary')));
		$meta = array('page_title' => lang('create_employee_salary'), 'bc' => $bc);
	    $this->page_construct('employees/create_employee_salary', $meta, $this->data);
	}
	
	function getEmployeesSalary()
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
		
		if ($this->input->get('isCompany')) {
            $isCompany = $this->input->get('isCompany');
        } else {
            $isCompany = 0;
        }
		
		if ($this->input->get('year')) {
            $year = $this->input->get('year');
        } else {
            $year = date('Y');
        }

		$searchDate = $year . '-' . $month . '-' . date('d');
		$searchDate = date('Y-m-d', strtotime($searchDate));
		$tabcheck = $this->input->get('tabcheck');
		$tabCheckData = 0;
		if(isset($tabcheck) && ($tabcheck == 2 || $tabcheck == 3)){
			$tabCheckData = 20;
		}
		
        $this->load->library('datatables');
		
		$isData = $this->employee_modal->getSalaryTaxTriggerByDate($year . '-' . $month);
		
		
		if($isData){
			$this->datatables
            ->select($this->db->dbprefix('users').".id as idd,
				".$this->db->dbprefix('users').".id as id, 
				CONCAT(" . $this->db->dbprefix('users') . ".first_name, ' ' ," . $this->db->dbprefix('users') . ".last_name) AS fullname,
				nationality, 
				employee_salary_tax.position,
				COALESCE (
					(
						SELECT
							emp_tax.basic_salary
						FROM
							erp_employee_salary_tax emp_tax
						WHERE
							DATE_FORMAT(
								emp_tax.date_insert,
								'%Y-%m'
							) = '". $year . '-' . $month ."'
						AND emp_tax.employee_id = erp_employee_salary_tax.employee_id
					),
					0
				) AS salary,
				COALESCE (
					(SELECT emp_tax.amount_usd
						FROM erp_employee_salary_tax emp_tax
						WHERE DATE_FORMAT(emp_tax.date_insert, '%Y-%m') = '". $year . '-' . $month ."'
						AND emp_tax.employee_id = erp_employee_salary_tax.employee_id
					),
					0
				) AS salary_tax,
				IFNULL(erp_employee_salary_tax.allowance,'0') as emp_allowance,
				IFNULL(erp_employee_salary_tax.allowance_tax,'0') as emp_allowance_tax,
				0 as salary_paid,
				COALESCE(erp_employee_salary_tax.spouse,0) as spouse, 
				COALESCE(erp_employee_salary_tax.minor_children,0) as number_of_child, 
				0 as allowance, 
				0 as salary_cal, 
				".$tabCheckData." as tax_rate, 
				0 as salary_riel, 
				IF(erp_employee_salary_tax.declare_tax = '1', 'Declared', 'Non Declared') as declared_tax,
				IFNULL(erp_employee_salary_tax.remark,'') AS remark,
				IFNULL(erp_employee_salary_tax.remark_fb,'') AS remarkfb,
				");
		}else{
			$this->datatables
            ->select($this->db->dbprefix('users').".id as idd,
				".$this->db->dbprefix('users').".id as id, 
				CONCAT(" . $this->db->dbprefix('users') . ".first_name, ' ' ," . $this->db->dbprefix('users') . ".last_name) AS fullname,
				nationality, 
				employee_salary_tax.position,
				COALESCE(erp_users.salary,0) as salary,
				COALESCE (
					(SELECT emp_tax.amount_usd
						FROM erp_employee_salary_tax emp_tax
						WHERE DATE_FORMAT(emp_tax.date_insert, '%Y-%m') = '". $year . '-' . $month ."'
						AND emp_tax.employee_id = erp_employee_salary_tax.employee_id
					),
					0
				) AS salary_tax,
				IFNULL(erp_employee_salary_tax.allowance,'0') as emp_allowance,
				IFNULL(erp_employee_salary_tax.allowance_tax,'0') as emp_allowance_tax,
				0 as salary_paid,
				COALESCE(erp_employee_salary_tax.spouse,0) as spouse, 
				COALESCE(erp_employee_salary_tax.minor_children,0) as number_of_child, 
				0 as allowance, 
				0 as salary_cal, 
				".$tabCheckData." as tax_rate, 
				0 as salary_riel, 
				IF(erp_employee_salary_tax.declare_tax = '1', 'Declared', 'Non Declare') as declared_tax,
				IFNULL(erp_employee_salary_tax.remark,'') AS remark,
				IFNULL(erp_employee_salary_tax.remark_fb,'') AS remarkfb,
				");
		}
        
				
            $this->datatables->from("users")
			->join('employee_salary_tax', 'employee_salary_tax.employee_id = users.id 
					AND DATE_FORMAT(erp_employee_salary_tax.date_insert,"%Y-%m") = "' . $year . "-" . $month . '" ', 'left')
			->join('employee_salary_tax_trigger', 'employee_salary_tax_trigger.id=employee_salary_tax.trigger_id', 'left')
            ->join('groups', 'users.group_id=groups.id', 'left')
            ->group_by('users.id')
            ->where('company_id', NULL)
            ->where('users.user_type', 'employee')
			//->where('employee_salary_tax_trigger.isCompany', $isCompany)
			->order_by('id', 'ASC')
			->edit_column("salary", "$1__$2__$3", 'id, salary, "sa"')
			->edit_column("salary_tax", "$1__$2__$3", 'id, salary_tax, "st"')
			//->edit_column("salary_paid", "$1__$2__$3", 'id, salary_paid, "sp"')
			->edit_column("spouse", "$1__$2__$3", 'id, spouse, "s"')
			->edit_column("number_of_child", "$1__$2__$3", 'id, number_of_child, "nc"');
			//->add_column("Actions", "<div class=\"text-center\"><button class=\"btn btn-primary btn-xs form-submit\" type=\"button\"><i class=\"fa fa-floppy-o\"></i></button></div>");
			//->edit_column("allowance", "$1__$2__$3", 'id, allowance, "al"')
			//->edit_column("salary_cal", "$1__$2__$3", 'id, salary_cal, "sc"')
			//->edit_column("tax_rate", "$1__$2__$3", 'id, tax_rate, "tr"')
			//->edit_column("salary_riel", "$1__$2__$3", 'id, salary_riel, "sr"');
		if($this->input->get('show_tax')!=1){ 
			$this->datatables->where('users.hide_row<>' , '1');
		}
		if($employee_id){
			$this->datatables->where('users.id' , $employee_id);
		}
		if($tabcheck==1){
			$this->datatables->where('users.emp_type' , 'res');
		}elseif($tabcheck==2){
			$this->datatables->where('users.emp_type' , 'nres');
		}
		$this->data['tabcheck'] = $tabcheck;
        echo $this->datatables->generate();
    }
	
	function update_employee_salary($cond){
		//--Resident--//
		if($cond==1){
			if($this->input->post('items')){
				$items = $this->input->post('items');
			}else{
				$items = NULL;
			}
			print_r($_REQUEST['items']);
			die();
			$data_items = array();
			
			$d_year_month = '';
			$d_isCompany = '';
			$d_total_salary_usd = 0;
			$d_total_salary_tax_usd = 0;
			$date= '';
			$this->erp->print_arrays($items);
			if($items){
				foreach($items as $item){
					$id = $item['id'];
					$basic_salary = $item['basic_salary'];
					$salary_tax = $item['amount_usd'];
					$spouse = $item['spouse'];
					$minor_child = $item['minor_child'];
					$date_insert = $item['date_insert'];
					$remark_note = $item['remark'];
					$tax_rate = $item['tax_rate'];
					$salary_tobe_paid = $item['salary_tobe_paid'];
					
					$salary_tax_calulation_base = $item['salary_tax_calulation_base'];
					$salary_tax_cal_riel = $item['salary_tax_cal_riel'];
					$date = $item['date'];

					/* For Data to Trigger */
					$d_year_month = $date_insert;
					$d_isCompany = $item['isCompany'];

					if($date_insert){
						$date_insert = $date_insert . '-' . date('d');
						$searchDate = date('Y-m-d', strtotime($date_insert));
					}else{
						$searchDate = date('Y-m-d');
					}
					
					$user = $this->site->getEmployeeByID($id);
				
					$data_items[] = array(
						'employee_id' => $id,
						'basic_salary' => $basic_salary,
						'amount_usd' => $salary_tax,
						'spouse' => $spouse,
						'minor_children' => $minor_child,
						'position' => $user->position,
						'date_insert' => $searchDate,
						'status' => 'active',
						'date_print' => date('Y-m-d'),
						'remark' => $remark_note,
						'tax_rate' => $tax_rate,
						'salary_tax' => $salary_tax_cal_riel,
						'salary_tobe_paid' => $salary_tobe_paid
					);
			
					$d_total_salary_usd += $basic_salary;
					$d_total_salary_tax_usd += $item['salary_tax_cal'];
				}
				if(!$date){
					$date = date('Y-m-d 00:00:00');
				}else{
					$date = $this->erp->fsd($date);
				}
				
				$data = array(
					'reference_no' => $this->employee_modal->getSalaryTaxReference(),
					'year_month' => $d_year_month,
					'isCompany' => $d_isCompany,
					'created_by' => $this->session->userdata('user_id'),
					'total_salary_usd' => $d_total_salary_usd,
					'total_salary_tax_usd' => $d_total_salary_tax_usd,
					'total_salary_tax_cal_base_riel' => $salary_tax_calulation_base,
					'total_salary_tax_riel' => $salary_tax_cal_riel,
					'date' => $date
				);			
				
				if($this->employee_modal->insert_employee_salary($data, $data_items, $cond)){
					$this->erp->send_json(array('status' => 1));
				}
			}
			$this->erp->send_json(array('status' => 0));
			
		//--Non-resident--//
		}elseif($cond==2){
			if($this->input->post('items')){
				$items = $this->input->post('items');
			}else{
				$items = NULL;
			}
			
			$data_items = array();
			
			$d_year_month = '';
			$d_isCompany = '';
			$d_total_salary_usd = 0;
			$d_total_salary_tax_usd = 0;
			//$date= '';
			//$this->erp->print_arrays($items);
			if($items){
				foreach($items as $item){
					$id = $item['id'];
					$basic_salary = $item['basic_salary'];
					$salary_tax = $item['amount_usd'];
					$date_insert = $item['date_insert'];
					$remark_note = $item['remark'];
					$tax_rate = $item['tax_rate'];
					$salary_tobe_paid = $item['salary_tobe_paid'];
					
					$salary_tax_cal_riel = $item['salary_tax_cal_riel'];
					$date = $item['date'];
					/* For Data to Trigger */
					$d_year_month = $date_insert;
					$d_isCompany = $item['isCompany'];

					if($date_insert){
						$date_insert = $date_insert . '-' . date('d');
						$searchDate = date('Y-m-d', strtotime($date_insert));
					}else{
						$searchDate = date('Y-m-d');
					}
					
					$user = $this->site->getEmployeeByID($id);

					$data_items[] = array(
						'employee_id' => $id,
						'basic_salary' => $basic_salary,
						'amount_usd' => $salary_tax,
						'position' => $user->position,
						'date_insert' => $searchDate,
						'status' => 'active',
						'date_print' => date('Y-m-d'),
						'remark' => $remark_note,
						'tax_rate' => $tax_rate,
						'salary_tax' => $salary_tax_cal_riel,
						'salary_tobe_paid' => $salary_tobe_paid
					);

					$d_total_salary_usd += $basic_salary;
					$d_total_salary_tax_usd += $item['salary_tax_cal'];
				}
				
				$data = array(
					'reference_no' => $this->employee_modal->getSalaryTaxReference(),
					'year_month' => $d_year_month,
					'isCompany' => $d_isCompany,
					'created_by' => $this->session->userdata('user_id'),
					'total_salary_usd' => $d_total_salary_usd,
					'total_salary_tax_usd' => $d_total_salary_tax_usd,
					'total_salary_tax_riel' => $salary_tax_cal_riel,
					'date' => $this->erp->fsd($date)
				);			
				
				if($this->employee_modal->insert_employee_salary($data, $data_items, $cond)){
					$this->erp->send_json(array('status' => 1));
				}
			}
			$this->erp->send_json(array('status' => 0));
			
			
		//--Fringe Benefit--//
		}else{
			if($this->input->post('items')){
				$items = $this->input->post('items');
			}else{
				$items = NULL;
			}
			
			$data_items = array();
			$total_allowance=0;
			$d_year_month = '';
			$d_isCompany = '';
			//$date= '';
			//$this->erp->print_arrays($items);
            $total_allowance_tax=0;
            $d_total_salary_usd=0;
            $d_total_salary_tax_usd=0;
			if($items){
				foreach($items as $item){
					$id = $item['id'];
					$basic_salary = $item['basic_salary'];
					$salary_tax = $item['amount_usd'];
					$date_insert = $item['date_insert'];
					$remark_note = $item['remark'];
					$tax_rate = $item['tax_rate'];
					$salary_tobe_paid = $item['salary_tobe_paid'];
					
					$salary_tax_cal_riel = $item['salary_tax_cal_riel'];
					$date = $item['date'];
					/* For Data to Trigger */
					$d_year_month = $date_insert;
					$d_isCompany = $item['isCompany'];

					if($date_insert){
						$date_insert = $date_insert . '-' . date('d');
						$searchDate = date('Y-m-d', strtotime($date_insert));
					}else{
						$searchDate = date('Y-m-d');
					}
					
					$user = $this->site->getEmployeeByID($id);

					$data_items[] = array(
						'employee_id' => $id,
						'allowance' => $basic_salary,
						'allowance_tax' => $salary_tax,
						'position' => $user->position,
						'date_insert' => $searchDate,
						'status' => 'active',
						'date_print' => date('Y-m-d'),
						'remark_fb' => $remark_note,
						'tax_rate' => $tax_rate,
						'salary_tax' => $salary_tax_cal_riel,
						'salary_tobe_paid' => $salary_tobe_paid
					);
					
					$total_allowance_tax+=$salary_tax;
					$d_total_salary_usd += $basic_salary;
					$d_total_salary_tax_usd += $item['salary_tax_cal'];
				}
			
				$data = array(
					'reference_no' => $this->employee_modal->getSalaryTaxReference(),
					'year_month' => $d_year_month,
					'isCompany' => $d_isCompany,
					'created_by' => $this->session->userdata('user_id'),
					'total_salary_usd' => $d_total_salary_usd,
					'total_salary_tax_usd' => $d_total_salary_tax_usd,
					'total_salary_tax_riel' => $salary_tax_cal_riel,
					'total_allowance_tax' => $total_allowance_tax,
					'date' => $this->erp->fsd($date)
				);			
				
				if($this->employee_modal->insert_employee_salary($data, $data_items, $cond)){
					$this->erp->send_json(array('status' => 1));
				}
			}
			$this->erp->send_json(array('status' => 0));
		}
			
	}
	
	function delete_employee($id){
		if($this->companies_model->delete_employee($id)){
			if($this->input->is_ajax_request()) {
                echo lang("employee_deleted"); die();
            }
            redirect('employees');
			$this->session->set_flashdata('message', lang('employee_deleted'));
		}
	}
	
	function edit_employee($id){
			$email = strtolower($this->input->post('email'));
			$this->form_validation->set_rules('name', lang("name"), 'required');
			$employee_id = $this->input->post('employee_id');
            $additional_data = array(
                'name' => $this->input->post('name'),
                'name_kh' => $this->input->post('name_kh'),
                'company' => $this->input->post('company'),
                'company_kh' => $this->input->post('company_kh'),
                'phone' => $this->input->post('phone'),
                'gender' => $this->input->post('gender'),
				'email' => $email,
				'position' => $this->input->post('position'),
				'group_name' => 'employee',
            );
			
		
		if ($this->form_validation->run() == true && $this->companies_model->edit_employee($employee_id,$additional_data)) {
            $this->session->set_flashdata('message', 'employee edited');
            redirect("employees");
        }else{
            // $this->data['employee'] = $this->companies_model->getEmployeeById($id);
			$employee = $this->companies_model->getEmployeeById($id);
			$this->data['gender'] = $employee->gender;
            $this->data['employee'] = $employee;
			$this->data['error'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('error')));
            $this->data['groups'] = $this->ion_auth->groups()->result_array();
            $this->data['billers'] = $this->site->getAllCompanies('biller');
            $this->data['warehouses'] = $this->site->getAllWarehouses();
            $bc = array(array('link' => site_url('home'), 'page' => lang('home')), array('link' => site_url('employees'), 'page' => lang('list_employees')), array('link' => '#', 'page' => lang('edit_employee')));
            $meta = array('page_title' => lang('users'), 'bc' => $bc);
            $this->page_construct('employees/edit_employee', $meta, $this->data);	
		}
		
	}

    function add_cash_advance($id = NULL)
    {
        $this->load->helper('security');
        $this->form_validation->set_rules('paid_by', lang("paid_by"), 'required');
        $this->form_validation->set_rules('reference_no', lang("reference_no"), 'required');
        $this->form_validation->set_rules('amount-paid', lang("amount-paid"), 'required');
        $this->form_validation->set_rules('bank_account', lang("bank_account"), 'required');
        $this->form_validation->set_rules('date', lang("date"), 'required');
        $this->form_validation->set_rules('biller', lang("biller"), 'required');

        if ($this->form_validation->run() == true) {
            if ($this->input->post('id')) {
                $id = $this->input->post('id');
            }
            $date = $this->erp->fld($this->input->post('date'));
            $biller_id = $this->input->post('biller');
            $bank_account = $this->input->post('bank_account');
            $reference_no = $this->input->post('reference_no');
            $paid_by = $this->input->post('paid_by');
            $amount_paid = $this->input->post('amount-paid');
            $note  = $this->input->post('note');
            $employee_advance = array(
                'emp_id' =>$id,
                'date' =>$date,
                'biller_id' =>$biller_id,
                'reference' =>$reference_no,
                'amount' =>$amount_paid,
                'paid_by' =>$paid_by,
                'bank_code' =>$bank_account,
                'created_by' =>$this->session->userdata('user_id'),
                'status' => 'cash',
                'note' => $note
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

            }
            $employee_advance['attachment'] = $photo;

            if($advance_id = $this->companies_model->addCashAdvance($employee_advance)){
                $payment = array(
                    'cash_advance_id' => $advance_id,
                    'date' =>$date,
                    'biller_id' =>$biller_id,
                    'reference_no' =>$reference_no,
                    'amount' =>$amount_paid,
                    'paid_by' =>$paid_by,
                    'bank_account' => $bank_account,
                    'note' => $note,
                    'type' => 'cash advance',
                    'created_by' => $this->session->userdata('user_id')
                );
                if($payment != NULL){
                    if($this->companies_model->addAdvancePayment($payment)){
                        redirect('employees');
                    }
                }
            }



        }else{

            $this->data['bankAccounts'] 	=  $this->site->getAssetsBankAccounts();
            $this->data['userBankAccounts'] =  $this->site->getAllBankAccountsByUserID();
            $this->data['employee_id']      =  $id;
            if ($this->Owner || $this->Admin || !$this->session->userdata('biller_id')){
                $biller_id 						= $this->site->get_setting()->default_biller;
                $this->data['ponumber'] 		= $this->site->getReference('po',$biller_id);
                $this->data['payment_ref'] 	= $this->site->getReference('pp',$biller_id);
            }else{
                $biller_id 						= $this->session->userdata('biller_id');
                $this->data['ponumber'] 		= $this->site->getReference('po',$biller_id);
                $this->data['payment_ref'] 		= $this->site->getReference('pp',$biller_id);
            }
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'employees/add_cash_advance', $this->data);

        }

    }
	
}
?>