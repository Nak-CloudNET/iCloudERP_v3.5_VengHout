<?php defined('BASEPATH') or exit('No direct script access allowed');

class Project_plan extends MY_Controller
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
        $this->lang->load('project_plan', $this->Settings->language);
        $this->load->library('form_validation');
        $this->load->model('project_plan_model');
        $this->digital_upload_path 	= 'files/';
        $this->upload_path 			= 'assets/uploads/';
        $this->thumbs_path 			= 'assets/uploads/thumbs/';
        $this->image_types 			= 'gif|jpg|jpeg|png|tif';
        $this->digital_file_types 	= 'zip|psd|ai|rar|pdf|doc|docx|xls|xlsx|ppt|pptx|gif|jpg|jpeg|png|tif|txt';
        $this->allowed_file_size 	= '1024';
        $this->data['logo'] 		= true;
		
		if(!$this->Owner && !$this->Admin) {
            $gp = $this->site->checkPermissions();
            $this->permission 	= $gp[0];
            $this->permission[] = $gp[0];
        } else {
            $this->permission[] = NULL;
        }
        $this->default_biller_id = $this->site->default_biller_id();
    }
	
    public function index($warehouse_id = null)
    {
        $this->erp->checkPermissions('index',NULL,'project_plan');
		
        $biller_id = $this->session->userdata('biller_id');
		
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('project_plan')));
        $meta = array('page_title' => lang('project_plan'), 'bc' => $bc);
        $this->page_construct('project_plan/index', $meta, $this->data);
    }
	
	public function add()
	{
		$this->erp->checkPermissions('add', true, 'project_plan');
		
		$this->form_validation->set_rules('plan', $this->lang->line("plan"), 'required|is_unique[project_plan.plan]');
		$this->form_validation->set_rules('reference_no', $this->lang->line("reference_no"), 'required|is_unique[project_plan.reference_no]');
		
		if ($this->form_validation->run() == true)
        {
			$date 			= $this->erp->fld($this->input->post('date'));
			$reference_no 	= $this->input->post('reference_no');
			$note 			= $this->input->post('note');
			$plan 			= $this->input->post('plan');
			
			$i 				= sizeof($_POST['product_code']);
			for ($r = 0; $r < $i; $r++) {
				$product_id = $_POST['product_id'][$r];
				$code 		= $_POST['product_code'][$r];
				$name 		= $_POST['product_name'][$r];
				$option 	= $_POST['product_option'][$r];
				$pro_vari	= $this->project_plan_model->getProductVariant($option);
				$quantity 	= $_POST['quantity'][$r];
				$balance_qty= $quantity * ($pro_vari?$pro_vari->qty_unit:1);
				$products[] = array(
					'product_id'		=> $product_id,
					'product_code' 		=> $code,
					'product_name' 		=> $name,
					'option_id'    		=> $option,
					'quantity'	   		=> $quantity,
					'quantity_balance' 	=> $balance_qty
				);
			}
			
			$data = array(
				'date' 			=> $date,
				'plan'			=> $plan,
				'reference_no' 	=> $reference_no,
				'note' 			=> $note,
				
			);
			
			
			if (empty($products)) {
				$this->session->set_flashdata('error', $this->lang->line("plz_product"));
				redirect($_SERVER["HTTP_REFERER"]);
			} else {
				krsort($products);
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
			
			//$this->erp->print_arrays($data, $products);
		}
		
		if ($this->form_validation->run() == true && $plan_id =  $this->project_plan_model->addProjectPlan($data, $products)) {
			$this->session->set_userdata('remove_s2', '1');
			$this->session->set_flashdata('message', $this->lang->line("project_plan_added"));
            redirect('project_plan/project_plan_form/'.$plan_id);
		} else {
			$this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
			
			
			if ($this->session->userdata('biller_id')) {
                $biller_id = $this->session->userdata('biller_id');
            } else {
                $biller_id = $this->Settings->default_biller;
            }
			
			$this->data['billers'] 	= $biller_id;
			$bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('project_plan'), 'page' => lang('project_plan')), array('link' => '#', 'page' => lang('add_project_plan')));
			$meta = array('page_title' => lang('add_project_plan'), 'bc' => $bc);
			$this->page_construct('project_plan/add', $meta, $this->data);
		}
	}
	
	public function edit($id){
		$this->erp->checkPermissions('edit', true, 'project_plan');
		$this->form_validation->set_rules('reference_no', $this->lang->line("reference_no"), 'required');
		if ($this->form_validation->run() == true)
        {
			$plan_items = $this->project_plan_model->getAllProjectPlan($id);
			if($plan_items->plan !== $this->input->post('plans'))
			{
				$this->form_validation->set_rules('plans', $this->lang->line("plan"), 'required|is_unique[project_plan.plan]');
				if ($this->form_validation->run() == false)
				{
					$this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
				}
			}
			
			$date 			= $this->erp->fld($this->input->post('date'));
			$reference_no 	= $this->input->post('reference_no');
			$note 			= $this->input->post('note');
			$plan 			= $this->input->post('plans');
			
			$i 				= sizeof($_POST['product_code']);
			for ($r = 0; $r < $i; $r++) {
				$item_id 	= $_POST['product_id'][$r];
				$code 		= $_POST['product_code'][$r];
				$name 		= $_POST['product_name'][$r];
				$option 	= $_POST['product_option'][$r];
				$pro_vari	= $this->project_plan_model->getProductVariant($option);
				$quantity 	= $_POST['quantity'][$r];
				$balance_qty= $quantity * ($pro_vari?$pro_vari->qty_unit:1);
				$products[] = array(
					'product_id' 		=> $item_id,
					'product_code' 		=> $code,
					'product_name' 		=> $name,
					'option_id'    		=> $option,
					'quantity'	   		=> $quantity,
					'quantity_balance' 	=> $balance_qty
				);
			}
			
			$data = array(
				'date' 			=> $date,
				'plan'			=> $plan,
				'reference_no' 	=> $reference_no,
				'note' 			=> $note,
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

		}
			
		if ($this->form_validation->run() == true && $this->project_plan_model->updateProjectPlan($id, $data, $products)) {
			$this->session->set_flashdata('message', $this->lang->line("project_plan_updated"));
            redirect('project_plan');
		} else {
			$plan_items 			 = $this->project_plan_model->getAllProjectPlan($id);
			$this->data['date'] 	 = $plan_items->date;			
			$this->data['reference'] = $plan_items->reference_no;			
			$this->data['plan'] 	 = $plan_items->plan;			
			$this->data['note'] 	 = $plan_items->note;
			$this->data['id']		 = $id;
			$this->data['setting']   = $this->site->get_setting();
			$rows 					 = $this->project_plan_model->getAllProjectPlanItem($id);
			if ($rows) {
                $c = rand(100000, 9999999);
                foreach ($rows as $row) {
                    $options 		= $this->project_plan_model->getProductOptions($row->id);

                    unset($row->details, $row->product_details, $row->file, $row->supplier1price, $row->supplier2price, $row->supplier3price, $row->supplier4price, $row->supplier5price);
                    $ri = $this->Settings->item_addition ? $row->id : $c;

                    $pr[$ri] = array('id' => $c, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'options' => $options);
                    $c++;
                }
            }
			///$this->erp->print_arrays($pr);
			$this->data['plan_item'] = json_encode($pr);
			$bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('project_plan'), 'page' => lang('project_plan')), array('link' => '#', 'page' => lang('edit_project_plan')));
			$meta = array('page_title' => lang('edit_project_plan'), 'bc' => $bc);
			$this->page_construct('project_plan/edit', $meta, $this->data);
		}
	}
	
	//============== Get Reference By Project =================//
	function getReferenceByProject($field,$biller_id){
		$reference_no = $this->site->getReference($field, $biller_id);
		echo json_encode($reference_no);
	}
	//========================= And ===========================//
	
	function suggestions(){
		$term = $this->input->get('term', true);
		
		$user_setting = $this->site->getUserSetting($this->session->userdata('user_id'));
        $rows = $this->project_plan_model->getProductNames($term, $user_setting->purchase_standard, $user_setting->purchase_combo, $user_setting->purchase_digital, $user_setting->purchase_service, $user_setting->purchase_category);
		if ($rows) {
            $c = str_replace(".", "", microtime(true));
            $r = 0;
            foreach ($rows as $row) {
                $option = false;
                $options = $this->project_plan_model->getProductOptions($row->id);
                if ($options) {
					$opt = current($options);
                    if (!$option) {
                        $option = $opt->id;
                    }
                } else {
                    $opt = json_decode('{}');
                }
                $row->option = $option;
                $row->qty = 1;
                unset($row->details, $row->product_details, $row->file, $row->supplier1price, $row->supplier2price, $row->supplier3price, $row->supplier4price, $row->supplier5price);
                if ($row->tax_rate) {
                    $tax_rate = $this->site->getTaxRateByID($row->tax_rate);
                    $pr[] = array('id' => ($c + $r), 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'options' => $options);
                } else {
                    $pr[] = array('id' => ($c + $r), 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'options' => $options);
                }
                $r++;
            }
			//$this->erp->print_arrays($pr);
            echo json_encode($pr);
        } else {
            echo json_encode(array(array('id' => 0, 'label' => lang('no_match_found'), 'value' => $term)));
        }
	}

	function getProjectPlan(){
		$this->erp->checkPermissions('index',null,'project_plan');
		
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
		$detail_link = anchor('project_plan/view/$1', '<i class="fa fa-file-text-o"></i> ' . lang('project_plan_details'));
		
        $edit_link = anchor('project_plan/edit/$1', '<i class="fa fa-edit"></i> ' . lang('edit_project_plan'));
		
		$print_link = anchor('project_plan/project_plan_form/$1', '<i class="fa fa-print"></i> ' . lang('Print_project_plan'));
		
        $delete_link = "<a href='#' class='po' title='<b>" . $this->lang->line("delete_project_plan") . "</b>' data-content=\"<p>"
        . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('project_plan/delete/$1') . "'>"
        . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i> "
        . lang('delete_project_plan') . "</a>";
        $action = '<div class="text-center"><div class="btn-group text-left">'
        . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
        . lang('actions') . ' <span class="caret"></span></button>
        <ul class="dropdown-menu pull-right" role="menu">
            <li>' . $edit_link . '</li><li>' . $print_link . '</li></ul></div></div>';
		
		$this->load->library('datatables');
		$this->datatables->select("project_plan.id, project_plan.date, project_plan.reference_no, project_plan.plan") 
						 ->from('project_plan');
		
		if ($reference_no) {
			$this->datatables->like('project_plan.reference_no', $reference_no, 'both');
		}
		if ($start_date) {
			$this->datatables->where($this->db->dbprefix('project_plan').'.date BETWEEN "' . $start_date . '" and "' . $end_date . '"');
		}
		
		$this->datatables->add_column("Actions", $action, "project_plan.id");
        echo $this->datatables->generate();	
	}
	
	public function add_address()
	{
		$this->erp->checkPermissions('add_address', true, 'project_plan');
		$this->form_validation->set_rules('plan', $this->lang->line("plan"), 'required');
		
		if ($this->form_validation->run() == true)
		{
			$plan_id = $this->input->post('plan');
			$address = $this->input->post('address');
			$data 	 = array(
				'plan_id' => $plan_id,
				'address' => preg_replace('!^<p>(.*?)</p>$!i', '$1', $address)
			);
		}
		
		if ($this->form_validation->run() == true && $this->project_plan_model->addProjectAddress($data)) {
			$this->session->set_flashdata('message', $this->lang->line("address_added"));
            redirect('project_plan/list_address');
		} else {
			$this->data['plan']		= $this->project_plan_model->getPlan();
			$this->data['modal_js'] = $this->site->modal_js();
			$this->load->view($this->theme . 'project_plan/add_address', $this->data);
		}
	}
	
	public function edit_address($id)
	{
		$this->erp->checkPermissions('add_address', true, 'project_plan');
		$this->form_validation->set_rules('plan', $this->lang->line("plan"), 'required');
		
		if ($this->form_validation->run() == true)
		{
			$plan_id = $this->input->post('plan');
			$address = $this->input->post('address');
			$data 	 = array(
				'plan_id' => $plan_id,
				'address' => $address
			);
		}
		
		if ($this->form_validation->run() == true && $this->project_plan_model->updateProjectAddress($data)) {
			$this->session->set_flashdata('message', $this->lang->line("address_added"));
            redirect('project_plan/list_address');
		} else {
			$this->data['data']		= $this->project_plan_model->getAddress($id);
			$this->data['plan']		= $this->project_plan_model->getPlan();
			$this->data['modal_js'] = $this->site->modal_js();
			$this->load->view($this->theme . 'project_plan/edit_address', $this->data);
		}
	}
	
	public function list_address(){
		$this->erp->checkPermissions('list_address',NULL,'project_plan');
		
        $biller_id = $this->session->userdata('biller_id');
		
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('list_address')));
        $meta = array('page_title' => lang('list_address'), 'bc' => $bc);
        $this->page_construct('project_plan/list_address', $meta, $this->data);
	}
	
	function getAddress(){
		$this->erp->checkPermissions('list_address',null,'project_plan');
		
		$detail_link = anchor('project_plan/address_view/$1', '<i class="fa fa-file-text-o"></i> ' . lang('address_details'));
		
        $edit_link = '<a href="project_plan/edit_address/$1" data-toggle="modal" data-target="#myModal" ><i class="fa fa-edit"></i>'.lang('edit_address').'</a>';

        $delete_link = "<a href='#' class='po' title='<b>" . $this->lang->line("delete_project_plan") . "</b>' data-content=\"<p>"
        . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('project_plan/delete/$1') . "'>"
        . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i> "
        . lang('delete_project_plan') . "</a>";
		
        $action = '<div class="text-center"><div class="btn-group text-left">'
        . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
        . lang('actions') . ' <span class="caret"></span></button>
        <ul class="dropdown-menu pull-right" role="menu">
            <li>' . $edit_link . '</li></ul></div></div>';
		
		$this->load->library('datatables');
		$this->datatables->select("plan_address.id, project_plan.plan, plan_address.address") 
                ->from('plan_address')
				->join('project_plan', 'project_plan.id = plan_address.plan_id', 'left');
		
		$this->datatables->add_column("Actions", $action, "plan_address.id");
        echo $this->datatables->generate();	
	}
	
	function project_plan_form($id=NULL)
	{
		$this->erp->checkPermissions('index');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
		$inv 									= $this->project_plan_model->getProjectPlan($id);
		$this->data['invs']				= $inv;
		//$this->erp->print_arrays($inv);
		$this->data['billers']     		= $this->site->getAllCompanies('biller');
		$records 							= $this->project_plan_model->getAllProjectPlanItem($id);
		$this->data['stock_item']	= $records;
		$this->load->view($this->theme . 'project_plan/project_plan_form', $this->data);	
	}
	
}
