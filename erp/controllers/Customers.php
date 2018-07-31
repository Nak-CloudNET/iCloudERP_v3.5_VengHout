<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Customers extends MY_Controller
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
        $this->lang->load('customers', $this->Settings->language);
        $this->load->library('form_validation');
        $this->load->model('companies_model');
    }

    function index($action = NULL)
    {
        $this->erp->checkPermissions();

        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['action'] = $action;
		$this->data['setting'] = $this->site->get_setting();
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('customers')));
        $meta = array('page_title' => lang('customers'), 'bc' => $bc);
        $this->page_construct('customers/index', $meta, $this->data);
    }
	/*===================================chin local updated===============================*/
    function getCustomers()
    {
        $this->erp->checkPermissions('index');
		$setting = $this->site->get_setting();
        $this->load->library('datatables');
		$this->datatables
            ->select("companies.id, companies.id as cid, (code) AS code, company, name, email, phone, customer_group_name, address, group_areas.areas_group as group_area, invoice_footer, (SELECT SUM(erp_deposits.amount) FROM erp_deposits WHERE erp_companies.id = erp_deposits.company_id) as deposit_amount, award_points")
            ->from("companies")
			->join('group_areas', 'companies.group_areas_id = group_areas.areas_g_code', 'left')
            ->where('group_name', 'customer')
			->group_by('companies.id')
            ->add_column("Actions", "<div class=\"text-center\">
            <a class=\"tip\" title='" . lang("attachment") . "' href='" . site_url('customers/attachment/$1') . "' data-toggle='modal' data-target='#myModal'>
                <i class=\"fa fa-chain\"></i>
            </a>
            <a class=\"tip\" title='" . lang("list_users") . "' href='" . site_url('customers/users/$1') . "' data-toggle='modal' data-target='#myModal'>
                <i class=\"fa fa-users\"></i>
            </a>
            <a class=\"tip\" title='" . lang("add_user") . "' href='" . site_url('customers/add_user/$1') . "' data-toggle='modal' data-target='#myModal'>
            <i class=\"fa fa-user-plus\"></i>
            </a>
            <a class=\"tip\" title='" . lang("list_deposits") . "' href='" . site_url('customers/deposits/$1') . "' data-toggle='modal' data-target='#myModal'>
            <i class=\"fa fa-money\"></i>
            </a>
            <a class=\"tip\" title='" . lang("add_deposit") . "' href='" . site_url('customers/add_deposit/$1') . "' data-toggle='modal' data-target='#myModal'>
            <i class=\"fa fa-plus\"></i>
            </a>
            <a class=\"tip\" title='" . lang("edit_customer") . "' href='" . site_url('customers/edit/$1') . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-edit\"></i></a> <a href='#' class='tip po' title='<b>" . lang("delete_customer") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('customers/delete/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></div>", "companies.id");

        echo $this->datatables->generate();
    }
	/*===============================end local updated====================================*/
    function view($id = NULL)
    {
        $this->erp->checkPermissions('index', true);
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['customer'] = $this->companies_model->getCompanyByID($id);
        $this->load->view($this->theme.'customers/view',$this->data);
    }

	function add($sale = NULL)
    {
        $this->erp->checkPermissions('add', null, 'customers');

        $this->form_validation->set_rules('email', lang("email_address"), 'is_unique[companies.email]');
        $this->form_validation->set_rules('code', lang("code"), 'required|is_unique[companies.code]');

        if ($this->form_validation->run('companies/add') == true) {
            $cg = $this->site->getCustomerGroupByID($this->input->post('customer_group'));
            $data = array(
                'code' => $this->input->post('code'),
                'name' => $this->input->post('name'),
                'email' => $this->input->post('email'),
                'group_id' => '3',
                'group_name' => 'customer',
                'customer_group_id' => $this->input->post('customer_group'),
                'customer_group_name' => $cg->name,
                'company' => $this->input->post('company'),
                'group_areas_id' => $this->input->post('group_area'),
                'address' => $this->input->post('address'),
                'address_1' => $this->input->post('address1'),
                'address_2' => $this->input->post('address2'),
                'address_3' => $this->input->post('address3'),
                'address_4' => $this->input->post('address4'),
                'address_5' => $this->input->post('address5'),
                'vat_no' => $this->input->post('vat_no'),
                'street' => $this->input->post('street'),
                'village' => $this->input->post('village'),
                'sangkat' => $this->input->post('sangkat'),
                'district' => $this->input->post('district'),
                'city' => $this->input->post('city'),
                'state' => $this->input->post('state'),
                'postal_code' => $this->input->post('postal_code'),
                'country' => $this->input->post('country'),
                'phone' => $this->input->post('phone'),
                'cf3' => $this->input->post('cf3'),
                'cf2' 				=> $this->input->post('cf2'),
                'cf6' 				=> $this->input->post('cf6'),
                'gender' => $this->input->post('gender'),
                'status' => $this->input->post('status'),
                'date_of_birth' => $this->erp->fld(trim($this->input->post('date_of_birth'))),
                'status' => $this->input->post('status'),
                'end_date' => $this->erp->fld(trim($this->input->post('end_date'))),
                'start_date' => $this->erp->fld(trim($this->input->post('start_date'))),
                'credit_limited' => $this->input->post('credit_limit'),
                'award_points' => $this->input->post('award_points'),
                'price_group_id' => $this->input->post('price_groups'),
                'payment_term' => $this->input->post('payment_term'),
                'company_kh' => $this->input->post('company_kh'),
                'name_kh' => $this->input->post('name_kh'),
                'address_kh' => $this->input->post('address_kh'),
                'public_charge_id'=> $this->input->post('public_charge'),
                'sale_man' => $this->input->post('saleman'),
                'invoice_footer' => $this->input->post('note'),
                'identify_date' =>$this->erp->fld(trim($this->input->post('identify_date'))),
                'public_charge_id' => ''
            );

            if ($_FILES['userfile']['size'][0] != "") {
                $this->load->library('upload');
                $config['upload_path'] = 'assets/uploads/';
                $config['allowed_types'] = 'zip|psd|ai|rar|pdf|doc|docx|xls|xlsx|ppt|pptx|gif|jpg|jpeg|png|tif|txt';
                $config['max_size'] = '1024';
                $config['overwrite'] = false;
                $config['encrypt_name'] = true;
                $files = $_FILES;
                $cpt = count($_FILES['userfile']['name']);
                $photo = array();
                for ($i = 0; $i < $cpt; $i++) {
                    $_FILES['userfile']['name'] = $files['userfile']['name'][$i];
                    $_FILES['userfile']['type'] = $files['userfile']['type'][$i];
                    $_FILES['userfile']['tmp_name'] = $files['userfile']['tmp_name'][$i];
                    $_FILES['userfile']['error'] = $files['userfile']['error'][$i];
                    $_FILES['userfile']['size'] = $files['userfile']['size'][$i];
                    $this->upload->initialize($config);
                    if (!$this->upload->do_upload()) {
                        $error = $this->upload->display_errors();
                        $this->session->set_flashdata('error', $error);
                        redirect($_SERVER["HTTP_REFERER"]);
                    }
                    array_push($photo,$this->upload->file_name);

                }
                $data['attachment'] = json_encode($photo);

            }
            //$this->erp->print_arrays($data);exit;
        } elseif ($this->input->post('add_customer')) {

            $this->session->set_flashdata('error', validation_errors());

                redirect('customers');

        }

        if ($this->form_validation->run() == true && $cid = $this->companies_model->addCompany($data)) {
            $this->session->set_flashdata('message', lang("customer_added"));
            if ($sale == "sale") {
                redirect('sales/add?customer='.$cid);
            }
            elseif ($sale == "saleorder") {
                redirect('sale_order/add_sale_order?customer='.$cid);
            }
            elseif ($sale == "aquote") {
                redirect('quotes/add?customer='.$cid);
            }
            elseif ($sale == 'event2do') {
                redirect('reports/event_to_do?customer='.$cid);
            }
            else{
                redirect('customers');
            }
        } else {

            $setting = $this->site->get_setting();
            if($this->session->userdata('biller_id')) {
                $biller_id = $this->session->userdata('biller_id');
            }else {
                $biller_id = $setting->default_biller;
            }

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['modal_js'] = $this->site->modal_js();
            $this->data['agencies'] = $this->site->getAllUsers();
            $this->data['price_groups'] = $this->site->getPriceGroups();
            $this->data['customer_groups'] = $this->companies_model->getAllCustomerGroups();
            $this->data['group_areas'] = $this->companies_model->getGroupAreas();
            $this->data['public_charge'] = $this->companies_model->getPublicCharge();
            $this->data['setting'] = $this->site->get_setting();
            $this->data['sale'] = $sale;
            $this->data['reference'] = $this->site->getReference('cus');
            $this->load->view($this->theme . 'customers/add', $this->data);
        }
    }

    function add_customer_pos($sale = NULL)
    {
       $this->erp->checkPermissions('add', null, 'customers');

        $this->form_validation->set_rules('email', lang("email_address"), 'is_unique[companies.email]');
        $this->form_validation->set_rules('name', lang("customer_name"), 'trim|required');

        if ($this->form_validation->run('companies/add') == true) {
            $cg = $this->site->getCustomerGroupByID($this->input->post('customer_group'));
            $data = array(
                'code' => $this->input->post('code'),
                'name' => $this->input->post('name'),
                'email' => $this->input->post('email'),
                'group_id' => '3',
                'group_name' => 'customer',
                'customer_group_id' => $this->input->post('customer_group'),
                'customer_group_name' => $cg->name,
                'company' => $this->input->post('company'),
                'address' => $this->input->post('address'),
                'phone' => $this->input->post('phone'),
                'public_charge_id' => ''
            );

            if ($_FILES['userfile']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = 'assets/uploads/';
                $config['allowed_types'] = 'zip|psd|ai|rar|pdf|doc|docx|xls|xlsx|ppt|pptx|gif|jpg|jpeg|png|tif|txt';
                $config['max_size'] = '1024';
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
        } elseif ($this->input->post('add_customer')) {

            $this->session->set_flashdata('error', validation_errors());
                redirect($_SERVER["HTTP_REFERER"]);

        }

        if ($this->form_validation->run() == true && $cid = $this->companies_model->addCompany($data)) {
            $this->session->set_flashdata('message', lang("customer_added"));

            // redirect to customer list when it done added
            $ref = isset($_SERVER["HTTP_REFERER"]) ? explode('?', $_SERVER["HTTP_REFERER"]) : NULL;
            redirect($ref[0] . '?customer=' . $cid);

        } else {
            $setting = $this->site->get_setting();
            if($this->session->userdata('biller_id')) {
                $biller_id = $this->session->userdata('biller_id');
            }else {
                $biller_id = $setting->default_biller;
            }

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['modal_js'] = $this->site->modal_js();
            $this->data['agencies'] = $this->site->getAllUsers();
            $this->data['price_groups'] = $this->site->getPriceGroups();
            $this->data['customer_groups'] = $this->companies_model->getAllCustomerGroups();
            $this->data['group_areas'] = $this->companies_model->getGroupAreas();
            $this->data['setting'] = $this->site->get_setting();
            $this->data['sale'] = $sale;
            $this->data['reference'] = $this->site->getReference('cus', $biller_id);
            $this->load->view($this->theme . 'customers/add_customer_pos', $this->data);
        }
    }

    function edit($id = NULL)
    {
        $this->erp->checkPermissions('edit', NULL, 'customers');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $this->form_validation->set_rules('name', lang("name"), 'trim|required');
        $company_details = $this->companies_model->getCompanyByID($id);
        if ($this->input->post('email') != $company_details->email) {
            $this->form_validation->set_rules('email', lang("email_address"), 'is_unique[companies.email]');
        }

        if ($this->form_validation->run('companies/edit') == true) {
            $cg = $this->site->getCustomerGroupByID($this->input->post('customer_group'));
            $data = array(
                'code' => $this->input->post('code'),
                'name' => $this->input->post('name'),
                'name_kh' => $this->input->post('name_kh'),
                'email' => $this->input->post('email'),
                'group_id' => '3',
                'group_name' => 'customer',
                'customer_group_id' => $this->input->post('customer_group'),
                'customer_group_name' => $cg->name,
                'company' => $this->input->post('company'),
                'group_areas_id' => $this->input->post('group_area'),
                'address' => $this->input->post('address'),
                'address_1' => $this->input->post('address1'),
                'address_2' => $this->input->post('address2'),
                'address_3' => $this->input->post('address3'),
                'address_4' => $this->input->post('address4'),
                'address_5' => $this->input->post('address5'),
                'public_charge_id'=> $this->input->post('public_charge'),
                'vat_no' => $this->input->post('vat_no'),
                'street' => $this->input->post('street'),
                'village' => $this->input->post('village'),
                'sangkat' => $this->input->post('sangkat'),
                'district' => $this->input->post('district'),
                'city' => $this->input->post('city'),
                'state' => $this->input->post('state'),
                'postal_code' => $this->input->post('postal_code'),
                'country' => $this->input->post('country'),
                'phone' => $this->input->post('phone'),
                'cf3' => $this->input->post('cf3'),
                'cf2' => $this->input->post('cf2'),
                'cf6' => $this->input->post('cf6'),
                'gender' => $this->input->post('gender'),
                'status' => $this->input->post('status'),
                'date_of_birth' => $this->erp->fld(trim($this->input->post('date_of_birth'))),
                'status' => $this->input->post('status'),
                'credit_limited' => $this->input->post('credit_limit'),
                'award_points' => $this->input->post('award_points'),
                'end_date' => $this->erp->fld(trim($this->input->post('end_date'))),
                'start_date' => $this->erp->fld(trim($this->input->post('start_date'))),
                'price_group_id' => $this->input->post('price_groups'),
                'payment_term' => $this->input->post('payment_term'),
                'company_kh' => $this->input->post('company_kh'),
                'name_kh' => $this->input->post('name_kh'),
                'address_kh' => $this->input->post('address_kh'),
                'sale_man' => $this->input->post('saleman'),
                'invoice_footer' => $this->input->post('note'),
                'identify_date' =>$this->erp->fld(trim($this->input->post('identify_date'))),
                'public_charge_id' => ''
            );
            //$this->erp->print_arrays($data);
            // attachment
            if ($_FILES['userfile']['size'][0] !=0) {
                $this->load->library('upload');
                $config['upload_path'] = 'assets/uploads/';
                $config['allowed_types'] = 'zip|psd|ai|rar|pdf|doc|docx|xls|xlsx|ppt|pptx|gif|jpg|jpeg|png|tif|txt';
                $config['max_size'] = '1024';
                $config['overwrite'] = false;
                $config['encrypt_name'] = true;
                $files = $_FILES;
                $cpt = count($_FILES['userfile']['name']);
                $old_photo=$this->companies_model->getCompanyAttachment($id);
                $photo = json_decode($old_photo[0]->attachment);
                if(empty($photo)){$photo = array();}
                for ($i = 0; $i < $cpt; $i++) {
                    $_FILES['userfile']['name'] = $files['userfile']['name'][$i];
                    $_FILES['userfile']['type'] = $files['userfile']['type'][$i];
                    $_FILES['userfile']['tmp_name'] = $files['userfile']['tmp_name'][$i];
                    $_FILES['userfile']['error'] = $files['userfile']['error'][$i];
                    $_FILES['userfile']['size'] = $files['userfile']['size'][$i];
                    $this->upload->initialize($config);
                    if (!$this->upload->do_upload()) {
                        $error = $this->upload->display_errors();
                        $this->session->set_flashdata('error', $error);
                        redirect($_SERVER["HTTP_REFERER"]);
                    }
                    array_push($photo,$this->upload->file_name);

                }
                $data['attachment'] = json_encode($photo);
            }


        }
        elseif ($this->input->post('edit_customer')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }

        if ($this->form_validation->run() == true && $this->companies_model->updateCompany($id, $data)) {
            $this->session->set_flashdata('message', lang("customer_updated"));
            redirect($_SERVER["HTTP_REFERER"]);
        } else {
            $this->data['customer'] = $company_details;
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['modal_js'] = $this->site->modal_js();
            $this->data['agencies'] = $this->site->getAllUsers();
            $this->data['price_groups'] = $this->site->getPriceGroups();
            $this->data['public_charge'] = $this->companies_model->getPublicCharge();
            $this->data['customer_groups'] = $this->companies_model->getAllCustomerGroups();
            $this->data['group_areas'] = $this->companies_model->getGroupAreas();
            $this->data['setting'] = $this->site->get_setting();
            $this->load->view($this->theme . 'customers/edit', $this->data);
        }
    }

    function users($company_id = NULL)
    {
        $this->erp->checkPermissions(false, true);

        if ($this->input->get('id')) {
            $company_id = $this->input->get('id');
        }


        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['modal_js'] = $this->site->modal_js();
        $this->data['company'] = $this->companies_model->getCompanyByID($company_id);
        $this->data['users'] = $this->companies_model->getCompanyUsers($company_id);
        $this->load->view($this->theme . 'customers/users', $this->data);

    }
    function attachment($company_id = NULL)
    {
        $this->erp->checkPermissions(false, true);

        if ($this->input->get('id')) {
            $company_id = $this->input->get('id');
        }
        $this->data['customer_id']  = $company_id;
        $this->data['attachments']  = $this->companies_model->getCompanyAttachment($company_id);
        $this->data['error']        = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['modal_js']     = $this->site->modal_js();
        $this->data['company']      = $this->companies_model->getCompanyByID($company_id);
        $this->data['users']        = $this->companies_model->getCompanyUsers($company_id);
        $this->load->view($this->theme . 'customers/attachment', $this->data);

    }

    function deleteAttachment($company_id = NULL,$name=NULL)
    {
        $this->erp->checkPermissions(false, true);
        if ($this->input->get('id')) {
            $company_id = $this->input->get('id');
        }
        $attachment=$this->companies_model->getCompanyAttachment($company_id);
        $image=json_decode($attachment[0]->attachment);
        $key = array_search($name, $image);
        unset($image[$key]);
        $image = array_values($image);
        $this->db->set('attachment',json_encode($image));
        $this->db->where('id',$company_id);
        if($this->db->update('erp_companies')){
            echo true;
        }
        echo false;

    }

    function add_user($company_id = NULL)
    {
        $this->erp->checkPermissions(false, true);

        if ($this->input->get('id')) {
            $company_id = $this->input->get('id');
        }
        $company = $this->companies_model->getCompanyByID($company_id);

        $this->form_validation->set_rules('email', lang("email_address"), 'is_unique[users.email]');
        $this->form_validation->set_rules('password', lang('password'), 'required|min_length[8]|max_length[20]|matches[password_confirm]');
        $this->form_validation->set_rules('password_confirm', lang('confirm_password'), 'required');

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
            redirect('customers');
        }

        if ($this->form_validation->run() == true && $this->ion_auth->register($username, $password, $email, $additional_data, $active, $notify)) {
            $this->session->set_flashdata('message', lang("user_added"));
            redirect("customers");
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['modal_js'] = $this->site->modal_js();
            $this->data['company'] = $company;
            $this->load->view($this->theme . 'customers/add_user', $this->data);
        }
    }

    function import_csv()
    {
        $this->erp->checkPermissions('import', true, 'customers');
        $this->load->helper('security');
        $this->form_validation->set_rules('csv_file', lang("upload_file"), 'xss_clean');

        if ($this->form_validation->run() == true) {

            if (DEMO) {
                $this->session->set_flashdata('warning', lang("disabled_in_demo"));
                redirect($_SERVER["HTTP_REFERER"]);
            }

            if (isset($_FILES["csv_file"])) /* if($_FILES['userfile']['size'] > 0) */ {

                $this->load->library('upload');

                $config['upload_path'] = 'assets/uploads/csv/';
                $config['allowed_types'] = 'csv';
                $config['max_size'] = '2000';
                $config['overwrite'] = TRUE;

                $this->upload->initialize($config);

                if (!$this->upload->do_upload('csv_file')) {

                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect("customers");
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

                $keys = array('company', 'code', 'name','email', 'phone', 'address', 'city', 'state', 'postal_code', 'country','group_areas_id', 'customer_group_id','vat_no', 'cf1', 'cf2', 'cf3', 'cf4', 'cf5', 'cf6');
                $final = array();
                foreach ($arrResult as $key => $value) {
                    $final[] = array_combine($keys, $value);
                }

                $setting = $this->site->get_setting();
                if($this->session->userdata('biller_id')) {
                    $biller_id = $this->session->userdata('biller_id');
                }else {
                    $biller_id = $setting->default_biller;
                }

                foreach ($final as $record) {
                    $customer_group = $this->site->get_customer_groups($record['customer_group_id']);
                    $record['group_id'] = 3;
                    $record['group_name'] = 'customer';
                    $record['customer_group_id'] = $customer_group->id;
                    $record['customer_group_name'] = $customer_group->name;

                    if ($arrResult[0][1] != '') {
                            $record['code'] = $record['code'];
                    } else {
                        $record['code'] = $this->data['reference'] = $this->site->getReference('cus', $biller_id);
                    }

                    $data[] = $record;
                    $this->site->updateReference('cus');
                }
            }

        } elseif ($this->input->post('import')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('customers');
        }

        if ($this->form_validation->run() == true && !empty($data)) {
            if ($this->companies_model->addCompanies($data)) {
                $this->session->set_flashdata('message', lang("customers_added"));
                redirect('customers');
            }
        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'customers/import', $this->data);
        }
    }

    function delete($id = NULL)
    {
        $this->erp->checkPermissions(NULL, TRUE);

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        if ($this->input->get('id') == 1) {
            $this->session->set_flashdata('error', lang('customer_x_deleted'));
            die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . (isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : site_url('welcome')) . "'; }, 0);</script>");
        }

        if ($this->companies_model->deleteCustomer($id)) {
            echo lang("customer_deleted");
        } else {
            $this->session->set_flashdata('warning', lang('customer_x_deleted_have_sales'));
            die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . (isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : site_url('welcome')) . "'; }, 0);</script>");
        }
    }

    function suggestions($term = NULL, $limit = NULL)
    {
        if ($this->input->get('term')) {
            $term = $this->input->get('term', TRUE);
        }
        if (strlen($term) < 1) {
            return FALSE;
        }

		$limit 			 = $this->input->get('limit', TRUE);
        //$result = $this->companies_model->getCustomerSuggestions(trim($term), $limit);
        //$rows['discount'] = $result[0]->order_discount ? $result[0]->order_discount : $result[1]->order_discount;
        $rows['results'] = $this->companies_model->getCustomerSuggestions($term, $limit);

		if ($this->Settings->member_card_expiry) {
			$gift_card = $this->companies_model->getGiftCardByCardNUM($term);
			if( $gift_card ){
				if($gift_card->expiry < date('Y-m-d') ){
					$rows['results'] = '';
				}
			}
		}
        echo json_encode($rows);
    }

	function balance_suggest($term = NULL, $limit = NULL)
    {
        // $this->erp->checkPermissions('index');
        if ($this->input->get('term')) {
            $term = $this->input->get('term', TRUE);
        }
        if (strlen($term) < 1) {
            return FALSE;
        }
        $limit = $this->input->get('limit', TRUE);
        $rows['result'] = $this->companies_model->getBalanceSuggestions($term, $limit);
        echo json_encode($rows);
    }

    function getCustomer($id = NULL)
    {
		$row = $this->companies_model->getCompanyByID($id);
        echo json_encode(array(array('id' => $row->id, 'text' => ($row->company == '' ? $row->code . ' - ' . $row->name : $row->code . ' - ' . $row->company), 'order_discount' => $row->order_discount)));
    }

    function get_award_points($id = NULL)
    {
        $this->erp->checkPermissions('index');
        $row = $this->companies_model->getCompanyByID($id);
        echo json_encode(array('ca_points' => $row->award_points));
    }

    function customer_actions()
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
                        if (!$this->companies_model->deleteCustomer($id)) {
                            $error = true;
                        }
                    }
                    if ($error) {
                        $this->session->set_flashdata('warning', lang('customers_x_deleted_have_sales'));
                    } else {
                        $this->session->set_flashdata('message', lang("customers_deleted"));
                    }
                    redirect($_SERVER["HTTP_REFERER"]);
                }

                if ($this->input->post('form_action') == 'export_excel' || $this->input->post('form_action') == 'export_pdf') {

                    $this->load->library('excel');
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle(lang('customer'));
                    $this->excel->getActiveSheet()->SetCellValue('A1', lang('no'));
                    $this->excel->getActiveSheet()->SetCellValue('B1', lang('code'));
                    $this->excel->getActiveSheet()->SetCellValue('C1', lang('company'));
                    $this->excel->getActiveSheet()->SetCellValue('D1', lang('name'));
                    $this->excel->getActiveSheet()->SetCellValue('E1', lang('email_address'));
                    $this->excel->getActiveSheet()->SetCellValue('F1', lang('phone'));
                    $this->excel->getActiveSheet()->SetCellValue('G1', lang('customer_group'));
                    $this->excel->getActiveSheet()->SetCellValue('H1', lang('address'));
                    $this->excel->getActiveSheet()->SetCellValue('I1', lang('address1'));
                    $this->excel->getActiveSheet()->SetCellValue('J1', lang('address2'));
                    $this->excel->getActiveSheet()->SetCellValue('K1', lang('address3'));
                    $this->excel->getActiveSheet()->SetCellValue('L1', lang('address4'));
                    $this->excel->getActiveSheet()->SetCellValue('M1', lang('address5'));
                    $this->excel->getActiveSheet()->SetCellValue('N1', lang('group_area'));
                    $this->excel->getActiveSheet()->SetCellValue('O1', lang('note'));
                    $this->excel->getActiveSheet()->SetCellValue('P1', lang('deposit'));
                    $this->excel->getActiveSheet()->SetCellValue('Q1', lang('award_points'));

                    $this->excel->getActiveSheet()->getStyle('A1:Q1')->getFont()
                                ->setName('Times New Roman')
                                ->setSize(12);
                    $this->excel->getActiveSheet()->getStyle('A1:Q1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $this->excel->getActiveSheet()->getStyle('A1:Q1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

                    $row = 2;
                    foreach ($_POST['val'] as $id) {
                        $customer = $this->site->getCompanyByID($id);
                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $customer->id);
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $customer->code." ");
                        $this->excel->getActiveSheet()->SetCellValue('C' . $row, $customer->company);
                        $this->excel->getActiveSheet()->SetCellValue('D' . $row, $customer->name);
                        $this->excel->getActiveSheet()->SetCellValue('E' . $row, $customer->email);
                        $this->excel->getActiveSheet()->SetCellValue('F' . $row, $customer->phone." ");
                        $this->excel->getActiveSheet()->SetCellValue('G' . $row, $customer->customer_group_name);

                        $this->excel->getActiveSheet()->SetCellValue('H' . $row, $customer->address);
                        $this->excel->getActiveSheet()->SetCellValue('I' . $row, $customer->address_1);
                        $this->excel->getActiveSheet()->SetCellValue('J' . $row, $customer->address_2);
                        $this->excel->getActiveSheet()->SetCellValue('K' . $row, $customer->address_3);
                        $this->excel->getActiveSheet()->SetCellValue('L' . $row, $customer->address_4);
                        $this->excel->getActiveSheet()->SetCellValue('M' . $row, $customer->address_5);

                        $this->excel->getActiveSheet()->SetCellValue('N' . $row, $customer->areas_group);
                        $this->excel->getActiveSheet()->SetCellValue('O' . $row, $customer->invoice_footer);
                        $this->excel->getActiveSheet()->SetCellValue('P' . $row, $customer->deposit_amount);
                        $this->excel->getActiveSheet()->SetCellValue('Q' . $row, $customer->award_points);

                        $row++;

                        $this->excel->getActiveSheet()->getStyle('A' . $row . ':Q' . $row)->getFont()
                                ->setName('Times New Roman')
                                ->setSize(12);
                        $this->excel->getActiveSheet()->getStyle('A' . $row . ':Q' . $row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    }

                    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
                    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
                    $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
                    $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
                    $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('M')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('N')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('O')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('P')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('Q')->setWidth(20);
                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $filename = 'customers_' . date('Y_m_d_H_i_s');
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

                        $this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
                        $this->excel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
                        $this->excel->getActiveSheet()->getPageSetup()->setFitToPage(true);
                        $this->excel->getActiveSheet()->getPageSetup()->setFitToWidth(1);
                        $this->excel->getActiveSheet()->getPageSetup()->setFitToHeight(1);

                        //Margins:
                        $this->excel->getActiveSheet()->getPageMargins()->setTop(0.25);
                        $this->excel->getActiveSheet()->getPageMargins()->setRight(0.25);
                        $this->excel->getActiveSheet()->getPageMargins()->setLeft(0.25);
                        $this->excel->getActiveSheet()->getPageMargins()->setBottom(0.25);

                        header('Content-Type: application/pdf');
                        header('Content-Disposition: attachment;filename="' . $filename . '.pdf"');
                        header('Cache-Control: max-age=0');

                        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'PDF');
                        return $objWriter->save('php://output');
                    }
                    if ($this->input->post('form_action') == 'export_excel') {

                        $this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
                        $this->excel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
                        $this->excel->getActiveSheet()->getPageSetup()->setFitToPage(true);
                        $this->excel->getActiveSheet()->getPageSetup()->setFitToWidth(1);
                        $this->excel->getActiveSheet()->getPageSetup()->setFitToHeight(1);

                        //Margins:
                        $this->excel->getActiveSheet()->getPageMargins()->setTop(0.25);
                        $this->excel->getActiveSheet()->getPageMargins()->setRight(0.25);
                        $this->excel->getActiveSheet()->getPageMargins()->setLeft(0.25);
                        $this->excel->getActiveSheet()->getPageMargins()->setBottom(0.25);

                        header('Content-Type: application/vnd.ms-excel');
                        header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
                        header('Cache-Control: max-age=0');

                        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
                        return $objWriter->save('php://output');
                    }

                    redirect($_SERVER["HTTP_REFERER"]);
                }
            } else {
                $this->session->set_flashdata('error', lang("no_customer_selected"));
                redirect($_SERVER["HTTP_REFERER"]);
            }
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }
	/*=====================================chin local updated========================================*/
    function deposits($company_id = NULL, $so_id = NULL)
    {
        if ($this->input->get('id')) {
            $company_id = $this->input->get('id');
        }

        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['modal_js'] = $this->site->modal_js();
        $this->data['company'] = $this->companies_model->getCompanyByID($company_id);
		$this->data['so_id'] = $so_id;
        $this->load->view($this->theme . 'customers/deposits', $this->data);
    }

    function get_deposits($id = NULL, $so_id = NULL)
    {
    	$deposit_note = anchor('customers/deposit_note/$1', '<i class="fa fa-file-text-o"></i> ' . lang('deposit_note'), 'data-toggle="modal" data-target="#myModal2"');

    	$deposit_receipt = anchor('customers/deposit_receipt/$1', '<i class="fa fa-file-text-o"></i> ' . lang('deposit_receipt'), 'data-toggle="modal" data-target="#myModal2"');
        $inv_charles = anchor('customers/invoice_charles/$1', '<i class="fa fa-file-text-o"></i> ' . lang('inv_charles'), 'target="_blank"');
    	$edit_deposit = anchor('customers/edit_deposit/$1', '<i class="fa fa-edit"></i> ' . lang('edit_deposit'), 'data-toggle="modal" data-target="#myModal2"');
		$return_deposit = anchor('customers/return_deposit/$1', '<i class="fa fa-reply"></i> ' . lang('return_deposit'), 'data-toggle="modal" data-target="#myModal2"');
    	$delete_deposit = "<a href='#' class='po' title='<b>" . lang("delete_deposit") . "</b>' data-content=\"<p>"
    	. lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('customers/delete_deposit/$1') . "'>"
    	. lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i> "
    	. lang('delete_deposit') . "</a>";
		$action = '<div class="text-center"><div class="btn-group text-left">'
							. '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
							. lang('actions') . ' <span class="caret"></span></button>
						<ul class="dropdown-menu pull-right" role="menu">
							<!-- <li>' . $deposit_note . '</li> -->
							<li>' . $deposit_receipt . '</li>
                            <!-- <li>' . $inv_charles . '</li> -->
							<li class="edit">' . $edit_deposit . '</li>';
						if(!$so_id) {
							$action .= '<li>' . $return_deposit . '</li>';
						}
						$action .= '<!--<li>' . $delete_deposit . '</li>-->
						</ul>
					</div></div>';

        $this->load->library('datatables');
        $this->datatables
            ->select("deposits.id as id, deposits.date, deposits.reference, deposits.amount, {$this->db->dbprefix('deposits')}.note, CONCAT({$this->db->dbprefix('users')}.first_name, ' ', {$this->db->dbprefix('users')}.last_name) as created_by, sale_order.sale_status", false)
            ->from("deposits")
            ->join('users', 'users.id=deposits.created_by', 'left')
			->join('sale_order', 'sale_order.id = deposits.so_id', 'left')
			->where('deposits.company_id', $id);
		if($so_id) {
			$this->datatables->where('deposits.so_id', $so_id);
		}
		$this->datatables
            ->add_column("Actions", $action, "id")
        ->unset_column('id');
        echo $this->datatables->generate();
    }

    function add_deposit($company_id = NULL, $so_id = NULL)
    {
		if ($this->input->get('id')) {
            $company_id = $this->input->get('id');
        }
        $company = $this->companies_model->getCompanyByID($company_id);
        if ($this->Owner || $this->Admin) {
            $this->form_validation->set_rules('date', lang("date"), 'required');
        }
        $this->form_validation->set_rules('amount', lang("amount"), 'required|numeric');
        $this->form_validation->set_rules('reference_no', lang("reference_no"), 'trim|required|is_unique[deposits.reference]');

        if ($this->form_validation->run() == true) {

            if ($this->Owner || $this->Admin) {
                $date = $this->erp->fld(trim($this->input->post('date')));
            } else {
                $date = date('Y-m-d H:i:s');
            }
            $biller_id = $this->input->post('biller_id');
            $reference = (($this->site->getReference('sp',$biller_id) == $this->input->post('reference_no'))? $this->site->getReference('sp',$biller_id): $this->input->post('reference_no'));

            $data = array(
				'reference' => $reference,
                'date' => $date,
                'amount' => $this->input->post('amount'),
                'paid_by' => $this->input->post('paid_by'),
                'note' => $this->input->post('note') ? $this->input->post('note') : $company->name,
                'company_id' => $company->id,
                'created_by' => $this->session->userdata('user_id'),
				'biller_id' => $biller_id,
				'bank_code' => $this->input->post('bank_account'),
				'status' => 'deposit',
                'so_id' => $so_id
            );
            $sale_id=null;
			$payment = array(
				'date' => $date,
				'sale_id' => $sale_id,
				'reference_no' => $reference,
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
				'biller_id'	=> $biller_id,
				'bank_account' => $this->input->post('bank_account')
			);
            $cdata = array(
                'deposit_amount' => ($company->deposit_amount+$this->input->post('amount'))
            );

        } elseif ($this->input->post('add_deposit')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('customers');
        }
        if ($this->form_validation->run() == true && $this->companies_model->addDeposit($data, $cdata, $payment)){
            $deposit = $this->input->post('amount');

            if($deposit > 0 && $so_id){
                $this->companies_model->updateSaleOrderDeposit($deposit,$so_id);
                $this->session->set_flashdata('message', lang("deposit_added"));
                redirect("sale_order/list_sale_order");
            }else{
                $this->session->set_flashdata('message', lang("deposit_added"));
                redirect("customers");
            }

        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
			$this->data['billers'] = $this->site->getAllCompanies('biller');
            $this->data['modal_js'] = $this->site->modal_js();

            if ($this->Owner || $this->Admin || !$this->session->userdata('biller_id')){
                if($this->Settings->system_management == 'biller') {
                    if($so_id > 0) {
                        $sale_order = $this->site->getSaleOrderByID($so_id);
                        $biller_id = $sale_order->biller_id;
                        $this->data['biller_id'] = $biller_id;
                        $this->data['reference'] = $this->site->getReference('sp',$biller_id);
                    }else {
                        $biller_id = $this->site->get_setting()->default_biller;
                        $this->data['biller_id'] = $biller_id;
                        $this->data['reference'] = $this->site->getReference('sp',$biller_id);
                    }
                }

            }else{
                if($this->Settings->system_management == 'biller') {
                    if($so_id > 0) {
                        $sale_order = $this->site->getSaleOrderByID($so_id);
                        $biller_id = $sale_order->biller_id;
                        $this->data['biller_id'] = $biller_id;
                        $this->data['reference'] = $this->site->getReference('sp',$biller_id);
                    }else {
                        $biller_ids = $this->session->userdata('biller_id');
                        $arr = json_decode($biller_ids);
                        $biller_id = $arr[0];
                        $this->data['biller_id'] = $biller_id;
                        $this->data['reference'] = $this->site->getReference('sp',$biller_id);
                    }
                }
            }

			$this->data['sale_order'] = $this->site->getSaleOrderByID($so_id);
			$this->data['so_id'] = $so_id;
            $this->data['company'] = $company;
			$this->data['bankAccounts'] =  $this->site->getAllBankAccounts();
            $this->data['userBankAccounts'] =  $this->site->getAllBankAccountsByUserID();
            $this->load->view($this->theme . 'customers/add_deposit', $this->data);
        }
    }

    function edit_deposit($id = NULL)
    {
        $this->erp->checkPermissions('deposit', null, 'sale_order');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $deposit = $this->companies_model->getDepositByID($id);
        $company = $this->companies_model->getCompanyByID($deposit->company_id);

        if ($this->Owner || $this->Admin) {
            $this->form_validation->set_rules('date', lang("date"), 'required');
        }
        $this->form_validation->set_rules('amount', lang("amount"), 'required|numeric');

        if ($this->form_validation->run() == true) {

            if ($this->Owner || $this->Admin) {
                $date = $this->erp->fld(trim($this->input->post('date')));
            } else {
                $date = $deposit->date;
            }
			$amount = $this->input->post('amount');
			$deposit_amount = $amount - $deposit->amount;
            $data = array(
                'date' => $date,
                'amount' => $amount,
                'paid_by' => $this->input->post('paid_by'),
                'note' => $this->input->post('note'),
                'company_id' => $deposit->company_id,
                'updated_by' => $this->session->userdata('user_id'),
                'updated_at' => $date = date('Y-m-d H:i:s'),
				'biller_id' => $this->input->post('biller'),
				'bank_code' => $this->input->post('bank_account'),
				'status' => $this->input->post('status')
            );

			$payment = array(
				'date' => $date,
				'amount' => $amount,
				'paid_by' => $this->input->post('paid_by'),
				'cheque_no' => $this->input->post('cheque_no'),
				'cc_no' => $this->input->post('paid_by') == 'gift_card' ? $this->input->post('gift_card_no') : $this->input->post('pcc_no'),
				'cc_holder' => $this->input->post('pcc_holder'),
				'cc_month' => $this->input->post('pcc_month'),
				'cc_year' => $this->input->post('pcc_year'),
				'cc_type' => $this->input->post('pcc_type'),
				'note' => $this->input->post('note') ? $this->input->post('note') : $company->name,
				'biller_id'	=> $this->input->post('biller'),
				'bank_account' => $this->input->post('bank_account')
			);

            $cdata = array(
                'deposit_amount' => ($company->deposit_amount-$this->input->post('amount'))
            );
			//$this->erp->print_arrays($id, $data, $cdata, $payment);
        } elseif ($this->input->post('edit_deposit')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }

        if ($this->form_validation->run() == true && $this->companies_model->updateDeposit($id, $data, $cdata, $payment)) {
			if($amount > 0 && $deposit->so_id) {
                $this->companies_model->updateSaleOrderDeposit($deposit_amount,$deposit->so_id);
                $this->session->set_flashdata('message', lang("deposit_updated"));
                redirect("sale_order/list_sale_order");
            }else {
                $this->session->set_flashdata('message', lang("deposit_updated"));
                redirect("customers");
            }
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
			$this->data['billers'] = $this->site->getAllCompanies('biller');
            $this->data['modal_js'] = $this->site->modal_js();
			$this->data['sale_order'] = $this->site->getSaleOrderByID($deposit->so_id);
            $this->data['company'] = $company;
            $this->data['deposit'] = $deposit;
			$this->data['bankAccounts'] =  $this->site->getAllBankAccounts();
            $this->load->view($this->theme . 'customers/edit_deposit', $this->data);
        }
    }

    function invoice_charles($id = null){
        $deposit = $this->companies_model->getDeposit($id);
        $this->data['sale_order'] = $this->companies_model->getSale_Order($deposit->so_id);
        $this->data['deposit'] = $deposit;
		$this->data['customer'] = $this->site->getCompanyByID($deposit->company_id);
        $this->data['rows'] = $this->companies_model->getSale_Order_Items($deposit->so_id);
        $this->data['inv'] = $this->companies_model->getheader($deposit->so_id);
        $this->load->view($this->theme . 'customers/print_invoice_charles', $this->data);
    }

    public function delete_deposit($id)
    {
        $this->erp->checkPermissions('deposit', null, 'sale_order');

        if ($this->companies_model->deleteDeposit($id)) {
            echo lang("deposit_deleted");
        }
    }

    public function deposit_note($id = null)
    {
        $this->erp->checkPermissions('deposit', null, 'sale_order');
        $deposit = $this->companies_model->getDepositByID($id);
        $this->data['customer'] = $this->companies_model->getCompanyByID($deposit->company_id);
        $this->data['deposit'] = $deposit;
        $this->data['page_title'] = $this->lang->line("deposit_note");
        $this->load->view($this->theme . 'customers/deposit_note', $this->data);
    }
	public function deposit_receipt($id = null)
    {
        $this->erp->checkPermissions('deposit', null, 'sale_order');
        $deposit = $this->companies_model->getDepositByID($id);

        $this->data['customer'] = $this->companies_model->getCompanyByID($deposit->company_id);
        $this->data['deposit'] = $deposit;
        $this->data['sale_order'] = $this->companies_model->getSale_Order($deposit->so_id);
        $this->data['biller'] = $this->companies_model->getCompanyByID($deposit->biller_id);
        $this->data['payments'] = $this->companies_model->getPaymentByDepositID($deposit->id);
        $this->data['page_title'] = $this->lang->line("deposit_receipt");
        $this->load->view($this->theme . 'customers/deposit_receipt', $this->data);
    }

	public function return_deposit($id){
		$this->erp->checkPermissions('deposit', null, 'sale_order');
		if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $deposit = $this->companies_model->getDepositByID($id);
        $company = $this->companies_model->getCompanyByID($deposit->company_id);
		$reference = (($this->site->getReference('pp') == $this->input->post('reference_no'))? $this->site->getReference('pp'): $this->input->post('reference_no'));
		if ($this->Owner || $this->Admin) {
            $this->form_validation->set_rules('date', lang("date"), 'required');
        }
        $this->form_validation->set_rules('amount', lang("amount"), 'required|numeric');
		if($this->form_validation->run() == true){
			if ($this->Owner || $this->Admin) {
                $date = $this->erp->fld(trim($this->input->post('date')));
            } else {
                $date = $deposit->date;
            }

			$data = array(
				'reference' => $reference,
                'date' => $date,
                'amount' => (-1) * $this->input->post('amount'),
                'paid_by' => $this->input->post('paid_by'),
                'note' => $this->input->post('note') ? $this->input->post('note') : $company->name,
                'company_id' => $company->id,
                'created_by' => $this->session->userdata('user_id'),
				'biller_id' => $this->input->post('biller'),
				'bank_code' => $this->input->post('bank_account'),
				'status' => 'returned',
				'deposit_id' => $id
            );

			$payment = array(
				'date' => $date,
				'reference_no' => $reference,
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
				'type' => 'returned',
				'biller_id'	=> $this->input->post('biller'),
				'bank_account' => $this->input->post('bank_account')
			);

            $cdata = array(
                'deposit_amount' => (($deposit->amount - $this->input->post('amount')))
            );

		} elseif ($this->input->post('return_deposit')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
		if ($this->form_validation->run() == true && $this->companies_model->ReturnDeposit($data, $cdata, $payment)) {
            $this->session->set_flashdata('message', lang("deposit_returned"));
            redirect($_SERVER["HTTP_REFERER"]);
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
			$this->data['billers'] = $this->site->getAllCompanies('biller');
            $this->data['modal_js'] = $this->site->modal_js();
			$this->data['reference'] = $this->site->getReference('pp');
            $this->data['company'] = $company;
            $this->data['deposit'] = $deposit;
			$this->data['bankAccounts'] =  $this->site->getAllBankAccounts();
            $this->load->view($this->theme . 'customers/return_deposit', $this->data);
        }
	}
	/*=======================================end local updated================================*/
	function customer_view($company_id=null){
		$this->erp->checkPermissions(false, true);

        if ($this->input->get('id')) {
            $company_id = $this->input->get('id');
        }


        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['modal_js'] = $this->site->modal_js();
        $this->data['company'] = $this->companies_model->getCompanyByID($company_id);
		$this->load->library('datatables');
		$this->data['customer_info']=$this->db
			->select('id,group_name,name,company,address,city,state,country,phone,email,gender,DATE_FORMAT(date_of_birth,"%d/%b/%Y") AS dob,status')
			->from('companies')
			->where('group_name','customer')
			->Where('id',$company_id)
			->get();
        //$this->data['users'] = $this->companies_model->getCompanyUsers($company_id);
		$this->load->view($this->theme.'customers/customer_views',$this->data);
	}
	function makeUpCost($id=null){
        $warehouses = $this->site->getMakeupCost($id);
        $arr = array();
        for($i=0;$i<sizeof($warehouses);$i++){
            $arr[] = $warehouses[$i];
        }
        echo json_encode($arr);

    }

}
