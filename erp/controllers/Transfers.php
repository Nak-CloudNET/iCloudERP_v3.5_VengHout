<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Transfers extends MY_Controller
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
        $this->lang->load('transfers', $this->Settings->language);
        $this->load->library('form_validation');
        $this->load->model('transfers_model');
        $this->load->model('products_model');
        $this->digital_upload_path = 'files/';
        $this->upload_path = 'assets/uploads/';
        $this->thumbs_path = 'assets/uploads/thumbs/';
        $this->image_types = 'gif|jpg|jpeg|png|tif';
        $this->digital_file_types = 'zip|psd|ai|rar|pdf|doc|docx|xls|xlsx|ppt|pptx|gif|jpg|jpeg|png|tif|txt';
        $this->allowed_file_size = '1024';
        $this->data['logo'] = true;
    }

    function index()
    {
        $this->erp->checkPermissions();

        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('transfers')));
        $meta = array('page_title' => lang('transfers'), 'bc' => $bc);
        $this->page_construct('transfers/index', $meta, $this->data);
    }

    function getTransfers()
    {
        $this->erp->checkPermissions('index');

        $detail_link = anchor('transfers/view/$1', '<i class="fa fa-file-text-o"></i> ' . lang('transfer_details'), 'data-toggle="modal" data-target="#myModal"');
		$view_document = anchor('transfers/view_document/$1', '<i class="fa fa-chain"></i> ' . lang('view_document'), 'data-toggle="modal" data-target="#myModal"');
		$transfer_back = anchor('transfers/transfer_back/$1', '<i class="fa fa-refresh"></i> ' . lang('transfer_back'));
        $email_link = anchor('transfers/email/$1', '<i class="fa fa-envelope"></i> ' . lang('email_transfer'), 'data-toggle="modal" data-target="#myModal"');
        $edit_link = anchor('transfers/edit/$1', '<i class="fa fa-edit"></i> ' . lang('edit_transfer'));
        $pdf_link = anchor('transfers/pdf/$1', '<i class="fa fa-file-pdf-o"></i> ' . lang('download_pdf'));
        $print_barcode = anchor('products/print_barcodes/?transfer=$1', '<i class="fa fa-print"></i> ' . lang('print_barcodes'));
        $delete_link = "<a href='#' class='tip po' title='<b>" . lang("delete_transfer") . "</b>' data-content=\"<p>"
            . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' id='a__$1' href='" . site_url('transfers/delete/$1') . "'>"
            . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i> "
            . lang('delete_transfer') . "</a>";
        $action = '<div class="text-center"><div class="btn-group text-left">'
            . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
            . lang('actions') . ' <span class="caret"></span></button>
        <ul class="dropdown-menu pull-right" role="menu">
            <li>' . $detail_link . '</li>
			<li>' . $view_document . '</li>
			<li>' . $transfer_back . '</li>
            <li>' . $edit_link . '</li>
            <li>' . $pdf_link . '</li>
            <li>' . $email_link . '</li>
            <li>' . $delete_link . '</li>
        </ul></div></div>';

        $this->load->library('datatables');

        $this->datatables
            ->select("id, date, transfer_no, from_warehouse_name as fname, from_warehouse_code as fcode, to_warehouse_name as tname,to_warehouse_code as tcode, total, total_tax, grand_total, status")
            ->from('transfers')
            ->edit_column("fname", "$1 ($2)", "fname, fcode")
            ->edit_column("tname", "$1 ($2)", "tname, tcode");

        if (!$this->Owner && !$this->Admin && !$this->session->userdata('view_right')) {
            $this->datatables->where('created_by', $this->session->userdata('user_id'));
        }

        $this->datatables->add_column("Actions", $action, "id")
            ->unset_column('fcode')
            ->unset_column('tcode');
        echo $this->datatables->generate();
    }

	function getInTransfers($warehouse_id = NULL)
    {
        $this->erp->checkPermissions('index',null,'transfers');

        if ($warehouse_id) {
            $warehouse_ids = explode('-', $warehouse_id);
        }

        $detail_link = anchor('transfers/view/$1', '<i class="fa fa-file-text-o"></i> ' . lang('transfer_details'), 'data-toggle="modal" data-target="#myModal"');
		$transfer_back = anchor('transfers/transfer_back/$1', '<i class="fa fa-refresh"></i> ' . lang('transfer_back'));
		$view_document = anchor('transfers/view_document/$1', '<i class="fa fa-chain"></i> ' . lang('view_document'), 'data-toggle="modal" data-target="#myModal"'); 
        $email_link = anchor('transfers/email/$1', '<i class="fa fa-envelope"></i> ' . lang('email_transfer'), 'data-toggle="modal" data-target="#myModal"');
        //$edit_link = anchor('transfers/edit_in_transfer/$1', '<i class="fa fa-edit"></i> ' . lang('edit_transfer'));
        $edit_link = anchor('transfers/edit/$1', '<i class="fa fa-edit"></i> ' . lang('edit_transfer'));
        $pdf_link = anchor('transfers/pdf/$1', '<i class="fa fa-file-pdf-o"></i> ' . lang('download_pdf'));
        $print_barcode = anchor('products/print_barcodes/?transfer=$1', '<i class="fa fa-print"></i> ' . lang('print_barcodes'));
        $delete_link = "<a href='#' class='tip po' title='<b>" . lang("delete_transfer") . "</b>' data-content=\"<p>"
            . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' id='a__$1' href='" . site_url('transfers/delete/$1') . "'>"
            . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i> "
            . lang('delete_transfer') . "</a>";
        $action = '<div class="text-center"><div class="btn-group text-left">'
            . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
            . lang('actions') . ' <span class="caret"></span></button>
        <ul class="dropdown-menu pull-right" role="menu">
            <li>' . $detail_link . '</li>
			<!--<li>' . $view_document . '</li>-->'
			
            .(($this->Owner || $this->Admin) ? '<li>'.$edit_link.'</li>' : ($this->GP['transfers-edit'] ? '<li>'.$edit_link.'</li>' : '')).
			 (($this->Owner || $this->Admin) ? '<li>'.$pdf_link.'</li>' : ($this->GP['transfers-export'] ? '<li>'.$pdf_link.'</li>' : '')).            
             (($this->Owner || $this->Admin) ? '<li>'.$email_link.'</li>' : ($this->GP['transfers-email'] ? '<li>'.$email_link.'</li>' : '')).
             (($this->Owner || $this->Admin) ? '<li>'.$print_barcode.'</li>' : ($this->GP['products-print_barcodes'] ? '<li>'.$print_barcode.'</li>' : '')).
			 (($this->Owner || $this->Admin) ? '<li>'.$delete_link.'</li>' : ($this->GP['transfers-delete'] ? '<li>'.$delete_link.'</li>' : '')).

        '</ul></div></div>';

        $biller_id = json_decode($this->session->userdata('biller_id'));
        $this->load->library('datatables');
		
		$ltrans = "(SELECT
						erp_transfer_items.product_id,
						erp_transfer_items.transfer_id,
						IFNULL(
							SUM(
								erp_transfer_items.quantity * erp_product_variants.qty_unit
							),
							erp_transfer_items.quantity
							) AS qty
					FROM
						`erp_transfer_items`
					LEFT JOIN `erp_products` ON `erp_products`.`id` = `erp_transfer_items`.`product_id`
					LEFT JOIN `erp_product_variants` ON `erp_product_variants`.`id` = `erp_transfer_items`.`option_id`
					GROUP BY
						`erp_transfer_items`.`id`
				    ) AS erp_tran";

		if ($warehouse_id) {
            $this->datatables
                ->select("transfers.id as id, transfers.date, transfer_no, from_warehouse_name as fname, from_warehouse_code as fcode, to_warehouse_name as tname,to_warehouse_code as tcode, sum(erp_tran.qty)")
                ->from('transfers')
                ->join('transfer_items', 'transfers.id = transfer_items.transfer_id', 'left')
    			->join($ltrans,'erp_tran.product_id = transfer_items.product_id AND erp_tran.transfer_id = transfer_items.transfer_id','left')
                ->edit_column("fname", "$1 ($2)", "fname, fcode")
                ->edit_column("tname", "$1 ($2)", "tname, tcode")
                ->where_in('transfers.biller_id', $biller_id)
                ->group_by('transfers.transfer_no');

                if (count($warehouse_ids) > 1) {
                    $this->datatables->where_in('transfers.from_warehouse_id', $warehouse_ids);
                } else {
                    $this->datatables->where('transfers.from_warehouse_id', $warehouse_id);
                }

        } else {
            $this->datatables
                ->select("transfers.id as id, transfers.date, transfer_no, from_warehouse_name as fname, from_warehouse_code as fcode, to_warehouse_name as tname,to_warehouse_code as tcode,  sum(erp_tran.qty)")
                ->from('transfers')
                ->join('transfer_items', 'transfers.id = transfer_items.transfer_id', 'left')
                ->join($ltrans,'erp_tran.product_id = transfer_items.product_id AND erp_tran.transfer_id = transfer_items.transfer_id','left')
                ->edit_column("fname", "$1 ($2)", "fname, fcode")
                ->edit_column("tname", "$1 ($2)", "tname, tcode")
                ->group_by('transfers.transfer_no');
        }

        if (!$this->Owner && !$this->Admin && !$this->session->userdata('view_right')) {
            $this->datatables->where('transfers.created_by', $this->session->userdata('user_id'));
        }

        $this->datatables->add_column("Actions", $action, "id")
            ->unset_column('fcode')
            ->unset_column('tcode');
        echo $this->datatables->generate();
    }
	
	function add()
    {
        $this->erp->checkPermissions();
        $this->form_validation->set_message('is_natural_no_zero', lang("no_zero_required"));
        $this->form_validation->set_rules('to_warehouse', lang("warehouse") . ' (' . lang("to") . ')', 'required');
        $this->form_validation->set_rules('from_warehouse', lang("warehouse") . ' (' . lang("from") . ')', 'required');
        $this->form_validation->set_rules('reference_no', lang("reference_no"), 'required|is_unique[transfers.transfer_no]');
        $this->form_validation->set_rules('biller', lang("biller"), 'required');

        if ($this->form_validation->run() == true)
        {
            $biller_id      = NULL;
            $transfer_no = $this->input->post('reference_no') ? $this->input->post('reference_no') : $this->site->getReference('to',$biller_id);
			
			if ($this->Owner || $this->Admin || $this->Settings->allow_change_date == 1) {
                $date = $this->erp->fld(trim($this->input->post('date')));
            } else {
                $date = date('Y-m-d H:i:s');
            }
            $authorize_id           = $this->input->post('authorize_id');
            $employee_id            = $this->input->post('employee_id');
            $biller_id              = $this->input->post('biller');
            $to_warehouse 			= $this->input->post('to_warehouse');
            $from_warehouse 		= $this->input->post('from_warehouse');

            $note 					= $this->input->post('note');
            $status 				= $this->input->post('status');
            $shipping 				= $this->input->post('shipping');
            $from_warehouse_details = $this->site->getWarehouseByID($from_warehouse);
            $from_warehouse_code 	= $from_warehouse_details->code;
            $from_warehouse_name 	= $from_warehouse_details->name;
            $to_warehouse_details 	= $this->site->getWarehouseByID($to_warehouse);
            $to_warehouse_code 		= $to_warehouse_details->code;
            $to_warehouse_name 		= $to_warehouse_details->name;

            $qty_qoh = 0;
            $i = isset($_POST['product_code']) ? sizeof($_POST['product_code']) : 0;
            for ($r = 0; $r < $i; $r++) {
                $item_code 		= $_POST['product_code'][$r];
				$unit_qty 		= $this->transfers_model->getProductOptionByID($_POST['product_option'][$r])->qty_unit;
				
				if($unit_qty) {
					$item_quantity = ($_POST['quantity'][$r]);
					$item_quantity_balance = ($_POST['quantity'][$r]*$unit_qty);
				  }else {
					$item_quantity = ($_POST['quantity'][$r]);
					$item_quantity_balance = ($_POST['quantity'][$r]);
				}
				
                $item_expiry 	= isset($_POST['expiry'][$r]) ? $_POST['expiry'][$r] : NULL;
                $item_option 	= (($_POST['product_option'])? $_POST['product_option'][$r]:NULL);
				
                if (isset($item_code) && isset($item_quantity)) {
                    $product_details 	= $this->transfers_model->getProductByCode($item_code);
					$warehouse_quantity = $this->transfers_model->getWarehouseProduct($from_warehouse_details->id, $product_details->id);
					
					$qty_qoh			= $warehouse_quantity->quantity;
					$setting     		= $this->site->get_setting();
					$error 				= lang("no_match_found");
					if($setting->product_expiry && $item_expiry){
						$expiry_qty 	= $this->site->checkExpiryDate($product_details->id, $item_expiry, $from_warehouse);
						$qty_qoh		= $expiry_qty->expiry_qty;
						$error			= lang("product_expiry") .' <strong>'. $item_expiry .'</strong>';
					}
					if ($qty_qoh < $item_quantity_balance) {
						$this->session->set_flashdata('error', lang("no_match_found") . " (" . lang('product_name') . " <strong>" . $product_details->name . "</strong> " . lang('product_code') . " <strong>" . $product_details->code . "</strong>)");
						redirect("transfers/add");
					}

                    $products[] = array(
                        'product_id' 		=> $product_details->id,
                        'product_code' 		=> $item_code,
                        'product_name' 		=> $product_details->name,
                        'product_type' 		=> $product_details->type,
                        'option_id' 		=> $item_option,
                        'quantity' 			=> $item_quantity,
                        'quantity_balance' 	=> $item_quantity_balance,
                        'warehouse_id' 		=> $to_warehouse,
                        'expiry' 			=> $item_expiry,
                        'date' 				=> date('Y-m-d', strtotime($date))
                    );

                }
            }
            if (empty($products)) {
                $this->form_validation->set_rules('product', lang("order_items"), 'required');
            } else {
                krsort($products);
            }

            $data = array(
				'transfer_no' 			=> $transfer_no,
                'date' 					=> $date,
                'from_warehouse_id' 	=> $from_warehouse,
                'from_warehouse_code' 	=> $from_warehouse_code,
                'from_warehouse_name' 	=> $from_warehouse_name,
                'to_warehouse_id' 		=> $to_warehouse,
                'to_warehouse_code' 	=> $to_warehouse_code,
                'to_warehouse_name' 	=> $to_warehouse_name,
                'note' 					=> htmlspecialchars($note,ENT_QUOTES),
                'created_by' 			=> $this->session->userdata('user_id'),
                'status' => "completed",//$status,
                'biller_id'             => $biller_id,
                'authorize_id'          => $authorize_id,
				'employee_id' 			=> $employee_id
            );
			
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
            }		
			//$this->erp->print_arrays($data, $products);
        }
		
        if ($this->form_validation->run() == true && $this->transfers_model->addTransfer($data, $products)) {
            optimizeTransferStock(date('Y-m-d', strtotime($data['date'])));
            $this->session->set_userdata('remove_tols', 1);
            $this->session->set_flashdata('message', lang("transfer_added"));
            redirect("transfers/list_in_transfer");
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['name'] = array('name' => 'name',
                'id' => 'name',
                'type' => 'text',
                'value' => $this->form_validation->set_value('name'),
            );
            $this->data['quantity'] = array('name' => 'quantity',
                'id' => 'quantity',
                'type' => 'text',
                'value' => $this->form_validation->set_value('quantity'),
            );
			
			if($this->Owner || $this->Admin || !$this->session->userdata("biller_id")) {
				$biller_id = $this->site->get_setting()->default_biller;
				$this->data['reference_no'] = $this->site->getReference('to',$biller_id);
			} else {
				$biller_id = $this->session->userdata("biller_id");
				$this->data['reference_no'] = $this->site->getReference('to',$biller_id);
			}
			
			if ($this->Owner || $this->Admin || !$this->session->userdata('warehouse_id')) {
				$this->data['warehouses'] = $this->site->getAllWarehouses();
			} else {
				$this->data['warehouses'] = $this->products_model->getUserWarehouses();
			}
            $warehouse_id = $this->session->userdata('warehouse_id');
            $AllUsers                   = $this->site->getAllUsers();
            $employee                   = $this->site->getAllEmployee();
            $biller                     = $this->site->getAllBiller();
            $this->data['billers']      = $biller;
            $this->data['AllUsers']     = $AllUsers; 
            $this->data['employees']    = $employee;
			$this->data['to_warehouse'] = $this->site->getAllWarehouses();
            $this->data['tax_rates']    = $this->site->getAllTaxRates();
            $this->data['rnumber'] = '';
            $this->data['warehouses_by_user'] = $this->products_model->getAllWarehousesByUser($warehouse_id);
            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('transfers'), 'page' => lang('transfers')), array('link' => '#', 'page' => lang('add_product_transfer')));
            $meta = array('page_title' => lang('add_product_transfer'), 'bc' => $bc);
            $this->page_construct('transfers/add', $meta, $this->data);
        }
    }
	
    function add_old()
    {
        $this->erp->checkPermissions();

        $this->form_validation->set_message('is_natural_no_zero', lang("no_zero_required"));
        //$this->form_validation->set_rules('reference_no', lang("reference_no"), 'required');
        $this->form_validation->set_rules('to_warehouse', lang("warehouse") . ' (' . lang("to") . ')', 'required');
        $this->form_validation->set_rules('from_warehouse', lang("warehouse") . ' (' . lang("from") . ')', 'required');
        if ($this->form_validation->run()) {
            $transfer_no = $this->input->post('reference_no') ? $this->input->post('reference_no') : $this->site->getReference('to');
            if ($this->Owner || $this->Admin) {
                $date = $this->erp->fld(trim($this->input->post('date')));
            } else {
                $date = date('Y-m-d H:i:s');
            }
            $to_warehouse = $this->input->post('to_warehouse');
            $from_warehouse = $this->input->post('from_warehouse');
            $note = $this->erp->clear_tags($this->input->post('note'));
            $shipping = $this->input->post('shipping');
            $status = $this->input->post('status');
            $from_warehouse_details = $this->site->getWarehouseByID($from_warehouse);
            $from_warehouse_code = $from_warehouse_details->code;
            $from_warehouse_name = $from_warehouse_details->name;
            $to_warehouse_details = $this->site->getWarehouseByID($to_warehouse);
            $to_warehouse_code = $to_warehouse_details->code;
            $to_warehouse_name = $to_warehouse_details->name;

            $total = 0;
            $product_tax = 0;

            $i = isset($_POST['product_code']) ? sizeof($_POST['product_code']) : 0;
            for ($r = 0; $r < $i; $r++) {
                $item_code = $_POST['product_code'][$r];
                $item_net_cost = $this->erp->formatDecimal($_POST['net_cost'][$r]);
                $unit_cost = $this->erp->formatDecimal($_POST['unit_cost'][$r]);
                $real_unit_cost = $this->erp->formatDecimal($_POST['real_unit_cost'][$r]);
				$unit_qty = $this->transfers_model->getProductOptionByID($_POST['product_option'][$r])->qty_unit;
				if($unit_qty) {
					$item_quantity = ($_POST['quantity'][$r] * $unit_qty);
				}else {
					$item_quantity = ($_POST['quantity'][$r]);
				}
                $item_tax_rate = isset($_POST['product_tax'][$r]) ? $_POST['product_tax'][$r] : NULL;
                $item_expiry = isset($_POST['expiry'][$r]) ? $this->erp->fsd($_POST['expiry'][$r]) : NULL;
                $item_option = (($_POST['product_option'])? $_POST['product_option'][$r]:NULL);
			
				if(empty($item_option) || $item_option == NULL || $item_option < 0 || isset($item_option)) {
					$item_option = '';
				}
                if (isset($item_code) && isset($real_unit_cost) && isset($unit_cost) && isset($item_quantity)) {
                    $product_details = $this->transfers_model->getProductByCode($item_code);
                    // if (!$this->Settings->overselling) {
                        $warehouse_quantity = $this->transfers_model->getWarehouseProduct($from_warehouse_details->id, $product_details->id);
                        if ($warehouse_quantity->quantity < $item_quantity) {
							
                            $this->session->set_flashdata('error', lang("no_match_found") . " (" . lang('product_name') . " <strong>" . $product_details->name . "</strong> " . lang('product_code') . " <strong>" . $product_details->code . "</strong>)");
                            redirect("transfers/add");
                        }
                   // }

                    if (isset($item_tax_rate) && $item_tax_rate != 0) {
                        $pr_tax = $item_tax_rate;
                        $tax_details = $this->site->getTaxRateByID($pr_tax);
                        if ($tax_details->type == 1 && $tax_details->rate != 0) {

                            if ($product_details && $product_details->tax_method == 1) {
                                $item_tax = $this->erp->formatDecimal((($unit_cost) * $tax_details->rate) / 100, 4);
                                $tax = $tax_details->rate . "%";
                            } else {
                                $item_tax = $this->erp->formatDecimal((($unit_cost) * $tax_details->rate) / (100 + $tax_details->rate), 4);
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

                    $item_net_cost = ($product_details && $product_details->tax_method == 1) ? $this->erp->formatDecimal($unit_cost) : $this->erp->formatDecimal($unit_cost-$item_tax);
                    $product_tax += $pr_item_tax;
                    $subtotal = (($item_net_cost * $item_quantity) + $pr_item_tax);

                    $products[] = array(
                        'product_id' => $product_details->id,
                        'product_code' => $item_code,
                        'product_name' => $product_details->name,
                        //'product_type' => $item_type,
                        'option_id' => $item_option,
                        'net_unit_cost' => $item_net_cost,
                        'unit_cost' => $this->erp->formatDecimal($item_net_cost + $item_tax),
                        'quantity' => $item_quantity,
                        'quantity_balance' => $item_quantity,
                        'warehouse_id' => $to_warehouse,
                        'item_tax' => $pr_item_tax,
                        'tax_rate_id' => $pr_tax,
                        'tax' => $tax,
                        'subtotal' => $this->erp->formatDecimal($subtotal),
                        'expiry' => $item_expiry,
                        'real_unit_cost' => $real_unit_cost,
                        'date' => date('Y-m-d', strtotime($date))
                    );
					//$this->erp->print_arrays($products);
                    $total += $item_net_cost * $item_quantity;
                }
            }
            if (empty($products)) {
                $this->form_validation->set_rules('product', lang("order_items"), 'required');
            } else {
                krsort($products);
            }

            $grand_total = $total + $shipping + $product_tax;
            $data = array('transfer_no' => $transfer_no,
                'date' => $date,
                'from_warehouse_id' => $from_warehouse,
                'from_warehouse_code' => $from_warehouse_code,
                'from_warehouse_name' => $from_warehouse_name,
                'to_warehouse_id' => $to_warehouse,
                'to_warehouse_code' => $to_warehouse_code,
                'to_warehouse_name' => $to_warehouse_name,
                'note' => $note,
                'total_tax' => $product_tax,
                'total' => $total,
                'grand_total' => $grand_total,
                'created_by' => $this->session->userdata('user_id'),
                'status' => $status,
                'shipping' => $shipping
            );

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
            }
			
			if ($_FILES['document1']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->digital_upload_path;
                $config['allowed_types'] = $this->digital_file_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = FALSE;
                $config['encrypt_name'] = TRUE;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('document1')) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                $data['attachment1'] = $photo;
            }
			
			if ($_FILES['document2']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->digital_upload_path;
                $config['allowed_types'] = $this->digital_file_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = FALSE;
                $config['encrypt_name'] = TRUE;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('document2')) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                $data['attachment2'] = $photo;
            }
			
			//echo $data['attachment1'];exit;

            //$this->erp->print_arrays($data, $products);
        }

        if ($this->form_validation->run() == true && $this->transfers_model->addTransfer($data, $products)) {
            $this->session->set_userdata('remove_tols', 1);
            $this->session->set_flashdata('message', lang("transfer_added"));
            redirect("transfers/list_in_transfer");
        } else {


            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

            $this->data['name'] = array('name' => 'name',
                'id' => 'name',
                'type' => 'text',
                'value' => $this->form_validation->set_value('name'),
            );
            $this->data['quantity'] = array('name' => 'quantity',
                'id' => 'quantity',
                'type' => 'text',
                'value' => $this->form_validation->set_value('quantity'),
            );

            $this->data['warehouses'] = $this->site->getAllWarehouses();
            $this->data['tax_rates'] = $this->site->getAllTaxRates();
            $this->data['rnumber'] = ''; //$this->site->getReference('to');
			$this->data['reference_no'] = $this->site->getReference('to');

            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('transfers'), 'page' => lang('transfers')), array('link' => '#', 'page' => lang('add_transfer')));
            $meta = array('page_title' => lang('transfer_quantity'), 'bc' => $bc);
            $this->page_construct('transfers/add', $meta, $this->data);
        }
    }
	
	function edit($id = NULL)
    {
        $this->erp->checkPermissions('edit',null,'transfers');
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        $this->form_validation->set_message('is_natural_no_zero', lang("no_zero_required"));
        $this->form_validation->set_rules('reference_no', lang("reference_no"), 'required');
        $this->form_validation->set_rules('to_warehouse', lang("warehouse") . ' (' . lang("to") . ')', 'required|is_natural_no_zero');
        $this->form_validation->set_rules('from_warehouse', lang("warehouse") . ' (' . lang("from") . ')', 'required|is_natural_no_zero');

        if ($this->form_validation->run()) {

            $transfer_no = $this->input->post('reference_no');
            if ($this->Owner || $this->Admin || $this->Settings->allow_change_date == 1) {
                $date = $this->erp->fld(trim($this->input->post('date')));
            } else {
                $date = date('Y-m-d H:i:s');
            }
			$authorize_id           = $this->input->post('authorize_id');
            $employee_id            = $this->input->post('employee_id');
            $biller_id              = $this->input->post('biller_id');
            $to_warehouse 			= $this->input->post('to_warehouse');
            $from_warehouse 		= $this->input->post('from_warehouse');
            $note 					= $this->input->post('note');
            $shipping 				= $this->input->post('shipping');
            $status 				= 'completed'; //$this->input->post('status');
            $from_warehouse_details = $this->site->getWarehouseByID($from_warehouse);
            $from_warehouse_code 	= $from_warehouse_details->code;
            $from_warehouse_name 	= $from_warehouse_details->name;
            $to_warehouse_details 	= $this->site->getWarehouseByID($to_warehouse);
            $to_warehouse_code 		= $to_warehouse_details->code;
            $to_warehouse_name 		= $to_warehouse_details->name;
			$tran_items_id          = $this->transfers_model->getTransferItemsByTransferId($id);
			
            $total = 0;

            $i = isset($_POST['product_code']) ? sizeof($_POST['product_code']) : 0;
            for ($r = 0; $r < $i; $r++) {
                $item_code 			= $_POST['product_code'][$r];
				$item_id 			= $_POST['item_id'][$r];
                $item_quantity 		= $_POST['quantity'][$r];
                $old_qty 			= $_POST['old_qty'][$r];
				$unit_qty 			= $this->transfers_model->getProductOptionByID($_POST['product_option'][$r])->qty_unit;
				if($unit_qty) {
					$quantity_balance = ($item_quantity*$unit_qty);
				  }else {
					$quantity_balance = ($item_quantity);
				}
                
                $item_expiry 		= isset($_POST['expiry'][$r]) ? $_POST['expiry'][$r] : NULL;
                $item_option 		= ((isset($_POST['product_option'][$r]) && $_POST['product_option'][$r] != 'false' && $_POST['product_option'][$r] != 'null')? $_POST['product_option'][$r]:NULL);

                if (isset($item_code) && isset($item_quantity)) {
                    $product_details 	= $this->transfers_model->getProductByCode($item_code);
					$warehouse_quantity = $this->transfers_model->getWarehouseProduct($from_warehouse_details->id, $product_details->id);
					$qty_qoh			= $warehouse_quantity->quantity;
					$setting     		= $this->site->get_setting();
					$error 				= lang("no_match_found");
					if($setting->product_expiry && $item_expiry){
						$expiry_qty 	= $this->site->checkExpiryDate($product_details->id, $item_expiry, $from_warehouse);
						$qty_qoh		= $expiry_qty->expiry_qty;
						$error			= lang("product_expiry") .' <strong>'. $item_expiry .'</strong>';
					}
					
					if (($qty_qoh + $old_qty) < $quantity_balance) {
						$this->session->set_flashdata('error', lang("no_match_found") . " (" . lang('product_name') . " <strong>" . $product_details->name . "</strong> " . lang('product_code') . " <strong>" . $product_details->code . "</strong>)");
						redirect($_SERVER["HTTP_REFERER"]);
					}
					
					$products[] = array(
                        'product_id' 		=> $product_details->id,
                        'product_code'		=> $item_code,
                        'product_name' 		=> $product_details->name,
                        'product_type' 		=> $product_details->type,
                        'option_id' 		=> $item_option,
                        'quantity' 			=> $item_quantity,
                        'quantity_balance' 	=> $quantity_balance,
                        'warehouse_id' 		=> $to_warehouse,
                        'expiry' 			=> $item_expiry,
						'date' 				=> $date
                    );
                }
            }
            if (empty($products)) {
                $this->form_validation->set_rules('product', lang("order_items"), 'required');
            } else {
                krsort($products);
            }

            $data = array(
				'transfer_no' 			=> $transfer_no,
                'date' 					=> $date,
                'from_warehouse_id' 	=> $from_warehouse,
                'from_warehouse_code' 	=> $from_warehouse_code,
                'from_warehouse_name' 	=> $from_warehouse_name,
                'to_warehouse_id' 		=> $to_warehouse,
                'to_warehouse_code' 	=> $to_warehouse_code,
                'to_warehouse_name' 	=> $to_warehouse_name,
                'note' 					=> htmlspecialchars($note,ENT_QUOTES),
                'created_by' 			=> $this->session->userdata('user_id'),
                'status'                => $status,
                'biller_id'             => $biller_id,
                'authorize_id'          => $authorize_id,
                'employee_id' 			=> $employee_id
            );

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
            }
		
			//$this->erp->print_arrays($data, $products);
        }

        if ($this->form_validation->run() == true && $this->transfers_model->updateTransfer($id, $data, $products,$tran_items_id)) {
            optimizeTransferStock(date('Y-m-d', strtotime($data['date'])));
            $this->session->set_userdata('remove_tols', 1);
            $this->session->set_flashdata('message', lang("transfer_updated"));
            redirect("transfers/list_in_transfer");
        } else {

            $this->data['error'] 	= (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['transfer'] = $this->transfers_model->getTransferByID($id);
			
			$from_warehouse_id 		= $this->transfers_model->getTransferByID($id);
			
            $transfer_items 		= $this->transfers_model->getAllTransferItems($id, $this->data['transfer']->status);
            $c 						= rand(100000, 9999999);
			$expiry_date 			= false;
            foreach ($transfer_items as $item) {
                $row = $this->site->getProductByID($item->product_id);
				$QohById = $this->transfers_model->getProductWarehouseOptionQtyByUnit($row->id, $from_warehouse_id->from_warehouse_id);
				if($QohById->qty){
					$QohById = $this->transfers_model->getProductWarehouseOptionQtyByUnit($row->id, $from_warehouse_id->from_warehouse_id);
				}else{
					$QohById = $this->transfers_model->getProductWarehouseOptionQtyByUnitOne($row->id, $from_warehouse_id->from_warehouse_id);
				}
				
                if (!$row) {
                    $row = json_decode('{}');
                } else {
                    unset($row->cost, $row->details, $row->product_details, $row->supplier1price, $row->supplier2price, $row->supplier3price, $row->supplier4price, $row->supplier5price);
                }
                $row->quantity 			= 0;
                $row->expiry 			= (($item->expiry && $item->expiry != '0000-00-00') ? $this->erp->hrsd($item->expiry) : '');
                $row->qty 				= $item->quantity;
                $row->quantity_balance 	= $item->quantity_balance;
                $row->quantity 			+= $item->quantity_balance;
                $row->option 			= $item->option_id;
				$row->pro_unit 			= $row->unit;
				$row->expiry			= $item->expiry;
                $options 				= $this->transfers_model->getProductOptions($row->id, $this->data['transfer']->from_warehouse_id, FALSE);
				
                $pis 					= $this->transfers_model->getPurchasedItems($item->product_id, $item->warehouse_id, $item->option_id);
                if($pis){
                    foreach ($pis as $pi) {
                        $row->quantity += $pi->quantity_balance;
                    }
                }
				
				$unit_qty = $this->transfers_model->getProductOptionByID($item->option_id);
				if($unit_qty)
				{
					$unit_qty = $this->transfers_model->getProductOptionByID($item->option_id);
				}else{
					$unit_qty = $this->transfers_model->getProductOptionByIDUnits($row->id);
					
				}
				
				if ($this->Settings->product_expiry) {
					$expiry_date		= $this->site->getProductExpireDate($row->id, $from_warehouse_id->from_warehouse_id);
				}
				
                $row->quantity 			+= $item->quantity;
                if ($options) {
                    $option_quantity = 0;
                    foreach ($options as $option) {
						
                        $pis = $this->transfers_model->getPurchasedItems($row->id, $from_warehouse_id->to_warehouse_id, $item->option_id);
                        if($pis){
                            foreach ($pis as $pi) {
                                $option_quantity += $pi->quantity_balance;
                            }
                        }
                        $option_quantity += $item->quantity;
                        if($option->quantity > $option_quantity) {
                            $option->quantity = $option_quantity;
                        }
                    }
                }

                $ri = $this->Settings->item_addition ? $row->id : $c;
                if ($row->tax_rate) {
                    $tax_rate 	= $this->site->getTaxRateByID($row->tax_rate);
                    $pr[$ri] 	= array('id' => $c, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'tax_rate' => $tax_rate, 'options' => $options,'QOHBYWH'=>$QohById,'unit_qty'=>$unit_qty->qty_unit,'item_idd'=>$item->id, 'expiry_date' => $expiry_date);
                } else {
                    $pr[$ri] 	= array('id' => $c, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'tax_rate' => false, 'options' => $options,'QOHBYWH'=>$QohById,'unit_qty'=>$unit_qty->qty_unit,'item_idd'=>$item->id, 'expiry_date' => $expiry_date);
                }
                $c++;
            }
			
            if ($this->session->userdata('biller_id')) {
                $biller_id = $this->session->userdata('biller_id');
            } else {
                $biller_id = $this->Settings->default_biller;
            }
            $AllUsers=$this->site->getAllUsers();
            $this->data['AllUsers']     = $AllUsers;

            $employee=$this->site->getAllEmployee();
            $this->data['employees']    = $employee;

            $biller=$this->site->getAllBiller();
            $this->data['biller']       = $biller;

            $getUsingStock              = $this->transfers_model->getUsingStockById($id);
            $this->data['using_stock']  = $getUsingStock;

            $this->data['transfer_items'] = json_encode($pr);
            $this->data['id'] = $id;
            $this->data['warehouses'] = $this->site->getAllWarehouses();
            $this->data['tax_rates'] = $this->site->getAllTaxRates();
            $this->data['rnumber'] = $this->site->getReference('to', $biller_id);

            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('transfers'), 'page' => lang('transfers')), array('link' => '#', 'page' => lang('edit_transfer')));
            $meta = array('page_title' => lang('edit_transfer_quantity'), 'bc' => $bc);
            $this->page_construct('transfers/edit', $meta, $this->data);
        }
    }
	
    function edit_old($id = NULL)
    {
        $this->erp->checkPermissions();

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $transfer = $this->transfers_model->getTransferByID($id);
        if (!$this->session->userdata('edit_right')) {
            $this->erp->view_rights($transfer->created_by);
        }
        $this->form_validation->set_message('is_natural_no_zero', lang("no_zero_required"));
        $this->form_validation->set_rules('reference_no', lang("reference_no"), 'required');
        $this->form_validation->set_rules('to_warehouse', lang("warehouse") . ' (' . lang("to") . ')', 'required|is_natural_no_zero');
        $this->form_validation->set_rules('from_warehouse', lang("warehouse") . ' (' . lang("from") . ')', 'required|is_natural_no_zero');
        //$this->form_validation->set_rules('note', lang("note"), 'xss_clean');

        if ($this->form_validation->run()) {

            $transfer_no = $this->input->post('reference_no');
            if ($this->Owner || $this->Admin) {
                $date = $this->erp->fld(trim($this->input->post('date')));
            } else {
                $date = date('Y-m-d H:i:s');
            }
            $to_warehouse = $this->input->post('to_warehouse');
            $from_warehouse = $this->input->post('from_warehouse');
            $note = $this->erp->clear_tags($this->input->post('note'));
            $shipping = $this->input->post('shipping');
            $status = $this->input->post('status');
            $from_warehouse_details = $this->site->getWarehouseByID($from_warehouse);
            $from_warehouse_code = $from_warehouse_details->code;
            $from_warehouse_name = $from_warehouse_details->name;
            $to_warehouse_details = $this->site->getWarehouseByID($to_warehouse);
            $to_warehouse_code = $to_warehouse_details->code;
            $to_warehouse_name = $to_warehouse_details->name;

            $total = 0;
            $product_tax = 0;

            $i = isset($_POST['product_code']) ? sizeof($_POST['product_code']) : 0;
            for ($r = 0; $r < $i; $r++) {
                $item_code = $_POST['product_code'][$r];
                $item_net_cost = $this->erp->formatDecimal($_POST['net_cost'][$r]);
                $unit_cost = $this->erp->formatDecimal($_POST['unit_cost'][$r]);
                $real_unit_cost = $this->erp->formatDecimal($_POST['real_unit_cost'][$r]);
                $item_quantity = $_POST['quantity'][$r];
                $quantity_balance = $_POST['quantity_balance'][$r];
                $item_tax_rate = isset($_POST['product_tax'][$r]) ? $_POST['product_tax'][$r] : NULL;
                $item_expiry = isset($_POST['expiry'][$r]) ? $this->erp->fsd($_POST['expiry'][$r]) : NULL;
                $item_option = ((isset($_POST['product_option'][$r]) && $_POST['product_option'][$r] != 'false' && $_POST['product_option'][$r] != 'null')? $_POST['product_option'][$r]:NULL);

                if (isset($item_code) && isset($real_unit_cost) && isset($unit_cost) && isset($item_quantity)) {
                    $product_details = $this->transfers_model->getProductByCode($item_code);

                    if (isset($item_tax_rate) && $item_tax_rate != 0) {
                        $pr_tax = $item_tax_rate;
                        $tax_details = $this->site->getTaxRateByID($pr_tax);
                        if ($tax_details->type == 1 && $tax_details->rate != 0) {

                            if ($product_details && $product_details->tax_method == 1) {
                                $item_tax = $this->erp->formatDecimal((($unit_cost) * $tax_details->rate) / 100, 4);
                                $tax = $tax_details->rate . "%";
                            } else {
                                $item_tax = $this->erp->formatDecimal((($unit_cost) * $tax_details->rate) / (100 + $tax_details->rate), 4);
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

                    $item_net_cost = ($product_details && $product_details->tax_method == 1) ? $this->erp->formatDecimal($unit_cost) : $this->erp->formatDecimal($unit_cost-$item_tax);
                    $product_tax += $pr_item_tax;
                    $subtotal = (($item_net_cost * $item_quantity) + $pr_item_tax);

                    $products[] = array(
                        'product_id' => $product_details->id,
                        'product_code' => $item_code,
                        'product_name' => $product_details->name,
                        //'product_type' => $item_type,
                        'option_id' => $item_option,
                        'net_unit_cost' => $item_net_cost,
                        'unit_cost' => $this->erp->formatDecimal($item_net_cost + $item_tax),
                        'quantity' => $item_quantity,
                        'quantity_balance' => $item_quantity,
                        'warehouse_id' => $to_warehouse,
                        'item_tax' => $pr_item_tax,
                        'tax_rate_id' => $pr_tax,
                        'tax' => $tax,
                        'subtotal' => $this->erp->formatDecimal($subtotal),
                        'expiry' => $item_expiry,
                        'real_unit_cost' => $real_unit_cost
                    );
                    $total += $item_net_cost * $item_quantity;
                }
            }
            if (empty($products)) {
                $this->form_validation->set_rules('product', lang("order_items"), 'required');
            } else {
                krsort($products);
            }

            $grand_total = $total + $shipping + $product_tax;
            $data = array('transfer_no' => $transfer_no,
                'date' => $date,
                'from_warehouse_id' => $from_warehouse,
                'from_warehouse_code' => $from_warehouse_code,
                'from_warehouse_name' => $from_warehouse_name,
                'to_warehouse_id' => $to_warehouse,
                'to_warehouse_code' => $to_warehouse_code,
                'to_warehouse_name' => $to_warehouse_name,
                'note' => $note,
                'total_tax' => $product_tax,
                'total' => $total,
                'grand_total' => $grand_total,
                'created_by' => $this->session->userdata('user_id'),
                'status' => $status,
                'shipping' => $shipping
            );

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
            }
			
			if ($_FILES['document1']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->digital_upload_path;
                $config['allowed_types'] = $this->digital_file_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = FALSE;
                $config['encrypt_name'] = TRUE;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('document1')) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                $data['attachment1'] = $photo;
            }
			
			if ($_FILES['document2']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->digital_upload_path;
                $config['allowed_types'] = $this->digital_file_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = FALSE;
                $config['encrypt_name'] = TRUE;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('document2')) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                $data['attachment2'] = $photo;
            }
            //$this->erp->print_arrays($data, $products);
        }

        if ($this->form_validation->run() == true && $this->transfers_model->updateTransfer($id, $data, $products)) {
            $this->session->set_userdata('remove_tols', 1);
            $this->session->set_flashdata('message', lang("transfer_updated"));
            redirect("transfers/list_in_transfer");
        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['transfer'] = $this->transfers_model->getTransferByID($id);
            $transfer_items = $this->transfers_model->getAllTransferItems($id, $this->data['transfer']->status);
            $c = rand(100000, 9999999);
            foreach ($transfer_items as $item) {
                $row = $this->site->getProductByID($item->product_id);
                if (!$row) {
                    $row = json_decode('{}');
                } else {
                    unset($row->cost, $row->details, $row->product_details, $row->supplier1price, $row->supplier2price, $row->supplier3price, $row->supplier4price, $row->supplier5price);
                }
                $row->quantity = 0;
                $row->expiry = (($item->expiry && $item->expiry != '0000-00-00') ? $this->erp->hrsd($item->expiry) : '');
                $row->qty = $item->quantity;
                $row->quantity_balance = $item->quantity_balance;
                $row->quantity += $item->quantity_balance;
                $row->cost = $item->net_unit_cost;
                $row->unit_cost = $item->net_unit_cost+($item->item_tax/$item->quantity);
                $row->real_unit_cost = $item->real_unit_cost;
                $row->tax_rate = $item->tax_rate_id;
                $row->option = $item->option_id;
                $options = $this->transfers_model->getProductOptions($row->id, $this->data['transfer']->from_warehouse_id, FALSE);
                $pis = $this->transfers_model->getPurchasedItems($item->product_id, $item->warehouse_id, $item->option_id);
                if($pis){
                    foreach ($pis as $pi) {
                        $row->quantity += $pi->quantity_balance;
                    }
                }
                $row->quantity += $item->quantity;
                if ($options) {
                    $option_quantity = 0;
                    foreach ($options as $option) {
                        $pis = $this->transfers_model->getPurchasedItems($row->id, $item->warehouse_id, $item->option_id);
                        if($pis){
                            foreach ($pis as $pi) {
                                $option_quantity += $pi->quantity_balance;
                            }
                        }
                        $option_quantity += $item->quantity;
                        if($option->quantity > $option_quantity) {
                            $option->quantity = $option_quantity;
                        }
                    }
                }

                $ri = $this->Settings->item_addition ? $row->id : $c;
                if ($row->tax_rate) {
                    $tax_rate = $this->site->getTaxRateByID($row->tax_rate);
                    $pr[$ri] = array('id' => $c, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'tax_rate' => $tax_rate, 'options' => $options);
                } else {
                    $pr[$ri] = array('id' => $c, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'tax_rate' => false, 'options' => $options);
                }
                $c++;
            }

            $this->data['transfer_items'] = json_encode($pr);
            $this->data['id'] = $id;
            $this->data['warehouses'] = $this->site->getAllWarehouses();
            $this->data['tax_rates'] = $this->site->getAllTaxRates();
            $this->data['rnumber'] = $this->site->getReference('to');

            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('transfers'), 'page' => lang('transfers')), array('link' => '#', 'page' => lang('edit_transfer')));
            $meta = array('page_title' => lang('edit_transfer_quantity'), 'bc' => $bc);
            $this->page_construct('transfers/edit', $meta, $this->data);
        }
    }

    function transfer_by_csv()
    {
        $this->erp->checkPermissions('import', true, 'transfers');
        $this->load->helper('security');
        $this->form_validation->set_message('is_natural_no_zero', lang("no_zero_required"));
        $this->form_validation->set_rules('to_warehouse', lang("warehouse") . ' (' . lang("to") . ')', 'required|is_natural_no_zero');
        $this->form_validation->set_rules('from_warehouse', lang("warehouse") . ' (' . lang("from") . ')', 'required|is_natural_no_zero');
        $this->form_validation->set_rules('userfile', lang("upload_file"), 'xss_clean');

        if ($this->form_validation->run()) {
            $transfer_no = $this->input->post('reference_no') ? $this->input->post('reference_no') : $this->site->getReference('to');
            if ($this->Owner || $this->Admin) {
                $date = $this->erp->fld(trim($this->input->post('date')));
            } else {
                $date = date('Y-m-d H:i:s');
            }

            $to_warehouse = $this->input->post('to_warehouse');
            $from_warehouse = $this->input->post('from_warehouse');
            $biller_id = $this->input->post('biller');
            $note = $this->erp->clear_tags($this->input->post('note'));
            $shipping = $this->input->post('shipping');
            $status = $this->input->post('status');
            $from_warehouse_details = $this->site->getWarehouseByID($from_warehouse);
            $from_warehouse_code = $from_warehouse_details->code;
            $from_warehouse_name = $from_warehouse_details->name;
            $to_warehouse_details = $this->site->getWarehouseByID($to_warehouse);
            $to_warehouse_code = $to_warehouse_details->code;
            $to_warehouse_name = $to_warehouse_details->name;
            $total = 0;
            $product_tax = 0;

            if (isset($_FILES["userfile"])) {
                $config['upload_path'] = $this->digital_upload_path;
                $config['allowed_types'] = 'csv';
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = TRUE;

                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                if (!$this->upload->do_upload()) {

                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect("transfers/transfer_bt_csv");
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

                $keys = array('product', 'serial', 'quantity', 'variant', 'expiry');
                $final = array();

                foreach ($arrResult as $key => $value) {
                    $final[] = array_combine($keys, $value);
                }

                $rw = 2;
                foreach ($final as $csv_pr) {

                    $item_code = $csv_pr['product'];
                    $serial = $csv_pr['serial'];
                    $item_quantity = $csv_pr['quantity'];
                    $variant = isset($csv_pr['variant']) ? $csv_pr['variant'] : NULL;
                    $date_ex 				= isset($csv_pr['expiry'])? strtr($csv_pr['expiry'], '/', '-'): NULL;
                    $item_expiry = date('Y-m-d', strtotime($date_ex));

                    if (isset($item_code) && isset($serial) && isset($item_quantity)) {

                        if (!($product_details = $this->transfers_model->getProductByCode($item_code))) {
                            $this->session->set_flashdata('error', lang("pr_not_found") . " ( " . $csv_pr['product'] . " ). " . lang("line_no") . " " . $rw);
                            redirect($_SERVER["HTTP_REFERER"]);
                        }

                        if ($variant) {
                            $variant_name = $this->transfers_model->getVariantName($variant)->name;
                            $item_option = $this->transfers_model->getProductVariantByName($variant_name, $product_details->id);

                            if (!$item_option) {
                                //$this->session->set_flashdata('error', lang("pr_not_found") . " ( " . $csv_pr['product'] . " - " . $csv_pr['variant'] . " ). " . lang("line_no") . " " . $rw);
                                //redirect($_SERVER["HTTP_REFERER"]);
                            }
                            $quantity_balance = $item_quantity * $item_option->qty_unit;

                        } else {
                            $item_option = json_decode('{}');
                            $item_option->id = NULL;
                        }
                        $warehouse_quantity = $this->transfers_model->getWarehouseProduct($from_warehouse_details->id, $product_details->id, $item_option->id);
                        if ($warehouse_quantity->quantity < $item_quantity) {
                            $this->session->set_flashdata('error', lang("no_match_found") . " (" . lang('product_name') . " <strong>" . $product_details->name . "</strong> " . lang('product_code') . " <strong>" . $product_details->code . "</strong>) " . lang("line_no") . " " . $rw);
                            redirect($_SERVER["HTTP_REFERER"]);
                        }


                        /*
                        if (isset($product_details->tax_rate)) {
                            $pr_tax = $product_details->tax_rate;
                            $tax_details = $this->site->getTaxRateByID($pr_tax);

                            if ($tax_details->type == 1 && $tax_details->rate != 0) {
                                $item_tax = ((($item_quantity * $item_net_cost) * $tax_details->rate) / 100);
                                $product_tax += $item_tax;
                            } else {
                                $item_tax = $tax_details->rate;
                                $product_tax += $item_tax;
                            }

                            if ($tax_details->type == 1)
                                $tax = $tax_details->rate . "%";
                            else
                                $tax = $tax_details->rate;
                        } else {
                            $pr_tax = 0;
                            $item_tax = 0;
                            $tax = "";
                        }
                        */

                        //$subtotal = (($item_net_cost * $item_quantity) + $item_tax);

                        $products[] = array(
                            'product_id' => $product_details->id,
                            'product_code' => $item_code,
                            'product_name' => $product_details->name,
                            'option_id' => $item_option->id,
                            //'net_unit_cost' => $item_net_cost,
                            'quantity' => $item_quantity,
                            'quantity_balance' => $quantity_balance?$quantity_balance:null,
                            //'item_tax' => $item_tax,
                            //'tax_rate_id' => $pr_tax,
                            //'tax' => $tax,
                            'expiry' => $item_expiry,
                            'product_type' => $product_details->type,
                            'date' => $date,
                            'warehouse_id' => $to_warehouse
                            //'subtotal' => $subtotal,
                            //'real_unit_cost' => $this->erp->formatDecimal($item_net_cost+($item_tax/$item_quantity))
                        );

                        //$total += $item_net_cost * $item_quantity;
                    }
                    $rw++;
                }
            }

            if (empty($products)) {
                $this->form_validation->set_rules('product', lang("order_item"), 'required');
            } else {
                krsort($products);
            }

            //$grand_total = $total + $shipping + $product_tax;
            $data = array('transfer_no' => $transfer_no,
                'date' => $date,
                'from_warehouse_id' => $from_warehouse,
                'from_warehouse_code' => $from_warehouse_code,
                'from_warehouse_name' => $from_warehouse_name,
                'to_warehouse_id' => $to_warehouse,
                'to_warehouse_code' => $to_warehouse_code,
                'to_warehouse_name' => $to_warehouse_name,
                'note' => $note,
                'biller_id' => $biller_id,
                //'total_tax' => $product_tax,
                //'total' => $total,
                //'grand_total' => $grand_total,
                'created_by' => $this->session->userdata('user_id'),
                'status' => $status,
                //'shipping' => $shipping
            );

            /*
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
            }
            */

        }

        if ($this->form_validation->run() == true && $this->transfers_model->addTransfer($data, $products)) {
            $this->session->set_userdata('remove_tols', 1);
            $this->session->set_flashdata('message', lang("transfer_added"));
            redirect("transfers/list_in_transfer");
        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

            $this->data['name'] = array('name' => 'name',
                'id' => 'name',
                'type' => 'text',
                'value' => $this->form_validation->set_value('name'),
            );
            $this->data['quantity'] = array('name' => 'quantity',
                'id' => 'quantity',
                'type' => 'text',
                'value' => $this->form_validation->set_value('quantity'),
            );

            if ($this->session->userdata('biller_id')) {
                $biller_id = $this->session->userdata('biller_id');
            } else {
                $biller_id = $this->Settings->default_biller;
            }

            $this->data['warehouses'] = $this->site->getAllWarehouses();
            $this->data['tax_rates'] = $this->site->getAllTaxRates();
            $this->data['rnumber'] = $this->site->getReference('to', $biller_id);

            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('transfers'), 'page' => lang('transfers')), array('link' => '#', 'page' => lang('transfer_by_csv')));
            $meta = array('page_title' => lang('add_transfer_by_csv'), 'bc' => $bc);
            $this->page_construct('transfers/transfer_by_csv', $meta, $this->data);
        }
    }

	function view_document($transfer_id = NULL)
    {
        $this->erp->checkPermissions('index', TRUE);
		$this->data['document'] = $this->transfers_model->getDocumentByID($transfer_id);
        $this->load->view($this->theme . 'transfers/view_document', $this->data);
    }
	
    function view($transfer_id = NULL)
    {
        $this->erp->checkPermissions('index', TRUE);

        if ($this->input->get('id')) {
            $transfer_id = $this->input->get('id');
        }
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $transfer = $this->transfers_model->getTransferByID($transfer_id);

        $this->data['rows'] = $this->transfers_model->getAllTransferItems($transfer_id, $transfer->status);
        // $this->erp->print_arrays($this->transfers_model->getAllTransferItems($transfer_id, $transfer->status));
        $this->data['from_warehouse'] = $this->site->getWarehouseByID($transfer->from_warehouse_id);
        $this->data['to_warehouse'] = $this->site->getWarehouseByID($transfer->to_warehouse_id);
        $this->data['transfer'] = $transfer;
        $this->data['tid'] = $transfer_id;
        $this->data['created_by'] = $this->site->getUser($transfer->created_by);
        $this->load->view($this->theme . 'transfers/view', $this->data);
    }

	function view_in($transfer_id = NULL)
    {
        $this->erp->checkPermissions('index', TRUE);

        if ($this->input->get('id')) {
            $transfer_id = $this->input->get('id');
        }
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $transfer = $this->transfers_model->getTransferByID($transfer_id);

        if (!$this->session->userdata('view_right')) {
            $this->erp->view_rights($transfer->created_by, true);
        }
        $this->data['rows'] = $this->transfers_model->getAllTransferItems($transfer_id, $transfer->status);
        $this->data['from_warehouse'] = $this->site->getWarehouseByID($transfer->from_warehouse_id);
        $this->data['to_warehouse'] = $this->site->getWarehouseByID($transfer->to_warehouse_id);
        $this->data['transfer'] = $transfer;
        $this->data['tid'] = $transfer_id;
        $this->data['created_by'] = $this->site->getUser($transfer->created_by);
        $this->load->view($this->theme . 'transfers/view_in', $this->data);
    }

    function pdf($transfer_id = NULL, $view = NULL, $save_bufffer = NULL)
    {
        if ($this->input->get('id')) {
            $transfer_id = $this->input->get('id');
        }

        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $transfer = $this->transfers_model->getTransferByID($transfer_id);
        if (!$this->session->userdata('view_right')) {
            $this->erp->view_rights($transfer->created_by);
        }
        $this->data['rows'] = $this->transfers_model->getAllTransferItems($transfer_id, $transfer->status);
        $this->data['from_warehouse'] = $this->site->getWarehouseByID($transfer->from_warehouse_id);
        $this->data['to_warehouse'] = $this->site->getWarehouseByID($transfer->to_warehouse_id);
        $this->data['transfer'] = $transfer;
        $this->data['tid'] = $transfer_id;
        $this->data['created_by'] = $this->site->getUser($transfer->created_by);
        $name = lang("transfer") . "_" . str_replace('/', '_', $transfer->transfer_no) . ".pdf";
        $html = $this->load->view($this->theme . 'transfers/pdf', $this->data, TRUE);
        if ($view) {
            $this->load->view($this->theme . 'transfers/pdf', $this->data);
        } elseif ($save_bufffer) {
            return $this->erp->generate_pdf($html, $name, $save_bufffer);
        } else {
            $this->erp->generate_pdf($html, $name);
        }

    }

    public function combine_pdf($transfers_id)
    {
        $this->erp->checkPermissions('combine_pdf', null, 'transfers');

        foreach ($transfers_id as $transfer_id) {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $transfer = $this->transfers_model->getTransferByID($transfer_id);
            // if (!$this->session->userdata('view_right')) {
            //     $this->erp->view_rights($transfer->created_by);
            // }
            $this->data['rows'] = $this->transfers_model->getAllTransferItems($transfer_id, $transfer->status);
            $this->data['from_warehouse'] = $this->site->getWarehouseByID($transfer->from_warehouse_id);
            $this->data['to_warehouse'] = $this->site->getWarehouseByID($transfer->to_warehouse_id);
            $this->data['transfer'] = $transfer;
            $this->data['tid'] = $transfer_id;
            $this->data['created_by'] = $this->site->getUser($transfer->created_by);

            $html[] = array(
                'content' => $this->load->view($this->theme . 'transfers/pdf', $this->data, TRUE),
                'footer' => '',
            );
        }

        $name = lang("transfers") . ".pdf";
        $this->erp->generate_pdf($html, $name);

    }

    function email($transfer_id = NULL)
    {
        $this->erp->checkPermissions();

        if ($this->input->get('id')) {
            $transfer_id = $this->input->get('id');
        }
        $transfer = $this->transfers_model->getTransferByID($transfer_id);
        $this->form_validation->set_rules('to', lang("to") . " " . lang("email"), 'trim|required|valid_email');
        $this->form_validation->set_rules('subject', lang("subject"), 'trim|required');
        $this->form_validation->set_rules('cc', lang("cc"), 'trim|valid_emails');
        $this->form_validation->set_rules('bcc', lang("bcc"), 'trim|valid_emails');
        $this->form_validation->set_rules('note', lang("message"), 'trim');

        if ($this->form_validation->run() == true) {
            if (!$this->session->userdata('view_right')) {
                $this->erp->view_rights($transfer->created_by);
            }
            $to = $this->input->post('to');
            $subject = $this->input->post('subject');
            if ($this->input->post('cc')) {
                $cc = $this->input->post('cc');
            } else {
                $cc = NULL;
            }
            if ($this->input->post('bcc')) {
                $bcc = $this->input->post('bcc');
            } else {
                $bcc = NULL;
            }

            $this->load->library('parser');
            $parse_data = array(
                'reference_number' => $transfer->transfer_no,
                'site_link' => base_url(),
                'site_name' => $this->Settings->site_name,
                'logo' => '<img src="' . base_url() . 'assets/uploads/logos/' . $this->Settings->logo . '" alt="' . $this->Settings->site_name . '"/>'
            );
            $msg = $this->input->post('note');
            $message = $this->parser->parse_string($msg, $parse_data);
            //$name = lang("transfer") . "_" . str_replace('/', '_', $transfer->transfer_no) . ".pdf";
            //$file_content = $this->pdf($transfer_id, NULL, 'S');
            //$attachment = array('file' => $file_content, 'name' => $name, 'mime' => 'application/pdf');
            $attachment = $this->pdf($transfer_id, NULL, 'S'); //delete_files($attachment);
        } elseif ($this->input->post('send_email')) {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->session->set_flashdata('error', $this->data['error']);
            redirect($_SERVER["HTTP_REFERER"]);
        }


        if ($this->form_validation->run() == true && $this->erp->send_email($to, $subject, $message, NULL, NULL, $attachment, $cc, $bcc)) {
            delete_files($attachment);
            $this->session->set_flashdata('message', lang("email_sent"));
            redirect("transfers");
        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

            if (file_exists('./themes/' . $this->theme . '/views/email_templates/transfer.html')) {
                $transfer_temp = file_get_contents('themes/' . $this->theme . '/views/email_templates/transfer.html');
            } else {
                $transfer_temp = file_get_contents('./themes/default/views/email_templates/transfer.html');
            }
            $this->data['subject'] = array('name' => 'subject',
                'id' => 'subject',
                'type' => 'text',
                'value' => $this->form_validation->set_value('subject', lang('transfer_order').' (' . $transfer->transfer_no . ') '.lang('from').' ' . $transfer->from_warehouse_name),
            );
            $this->data['note'] = array('name' => 'note',
                'id' => 'note',
                'type' => 'text',
                'value' => $this->form_validation->set_value('note', $transfer_temp),
            );
            $this->data['warehouse'] = $this->site->getWarehouseByID($transfer->to_warehouse_id);

            $this->data['id'] = $transfer_id;
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'transfers/email', $this->data);

        }
    }

    function delete($id = NULL)
    {
        $this->erp->checkPermissions('delete',null,'transfers');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        if ($this->transfers_model->deleteTransfer($id)) {
            if($this->input->is_ajax_request()) {
                echo lang("transfer_deleted"); die();
            }
            $this->session->set_flashdata('message', lang('transfer_deleted'));
            redirect('welcome');
        }
    }

    function suggestions()
    {
        $this->erp->checkPermissions('index', TRUE);
        $term 			= $this->input->get('term', TRUE);
        $warehouse_id 	= $this->input->get('warehouse_id', TRUE);

        if (strlen($term) < 1 || !$term) {
            die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . site_url('welcome') . "'; }, 10);</script>");
        }

        $analyzed 		= $this->erp->analyze_term($term);
        $sr 			= $analyzed['term'];
        $option_id 		= $analyzed['option_id'];
		$expiry_date	= FALSE;
		$options		= FALSE;
        $rows 			= $this->transfers_model->getProductNames($sr, $warehouse_id);
        if ($rows) {
            foreach ($rows as $row) {
                $option 				= FALSE;
                $row->quantity 			= 0;
                $row->item_tax_method 	= $row->tax_method;
                $row->qty 				= 1;
                $row->discount 			= '0';
                $row->expiry 			= '';
				$product_unit 			= $this->site->getUnitById($row->unit);
				$options 				= $this->transfers_model->getProductOptions($row->id,  $warehouse_id, FALSE);
				
				if($options){
					$options = $this->transfers_model->getProductOptions($row->id,  $warehouse_id, FALSE);
				}
				
				$QohById 				= $this->transfers_model->getProductWarehouseOptionQtyByUnitOne($row->id, $warehouse_id);

                if ($options) {
                    $opt 				= $options[0];
                    if (!$option) {
                        $option 		= $opt->id;
                    }
                } else {
                    $opt 				= json_decode('{}');
                    $opt->cost 			= 0;
                }
                $row->option 			= $option;
                $pis 					= $this->transfers_model->getPurchasedItems($row->id, $warehouse_id, $row->option);
                if($pis){
                    foreach ($pis as $pi) {
                        $row->quantity += $pi->quantity_balance;
                    }
                }
                if ($options) {
                    $option_quantity = 0;
                    foreach ($options as $option) {
                        $pis = $this->transfers_model->getPurchasedItems($row->id, $warehouse_id, $row->option);
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
				
				$unit_qty 				= $this->transfers_model->getProductOptionByID($row->option);
				if($unit_qty)
				{
					$unit_qty 			= $this->transfers_model->getProductOptionByID($row->option);
					
				}else{
					$unit_qty 			= $this->transfers_model->getProductOptionByIDUnits($row->id);
				}
				
                $row->pro_unit 			= $product_unit->name;
                $row->real_unit_cost 	= $row->cost;
                $combo_items 			= FALSE;
				$expiry_date			= $this->site->getProductExpireDate($row->id, $warehouse_id);
				
                if ($row->tax_rate) {
                    $tax_rate = $this->site->getTaxRateByID($row->tax_rate);
                    if ($row->type == 'combo') {
                        $combo_items = $this->sales_model->getProductComboItems($row->id, $warehouse_id);
                    }
                    $pr[] = array('id' => str_replace(".", "", microtime(true)), 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'combo_items' => $combo_items, 'tax_rate' => $tax_rate, 'options' => $options,'QOHBYWH'=>$QohById,'unit_qty'=>$unit_qty->qty_unit, 'expiry_date' => $expiry_date);
                } else {
                    $pr[] = array('id' => str_replace(".", "", microtime(true)), 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'combo_items' => $combo_items, 'tax_rate' => false, 'options' => $options, 'QOHBYWH'=>$QohById, 'unit_qty'=>$unit_qty->qty_unit, 'expiry_date' => $expiry_date);
                }
            }
            
            $this->erp->send_json($pr);
        } else {
            $this->erp->send_json(array(array('id' => 0, 'label' => lang('no_match_found'), 'value' => $term)));
        }
    }
	
    function transfer_actions($wh=null)
    {
        if($wh){
            $wh = explode('-', $wh);
        }
        // $this->erp->print_arrays($wh);
        $this->form_validation->set_rules('form_action', lang("form_action"), 'required');

        if ($this->form_validation->run() == true) {

            if (!empty($_POST['val'])) {
                if ($this->input->post('form_action') == 'delete') {

                    foreach ($_POST['val'] as $id) {
                        $this->transfers_model->deleteTransfer($id);
                    }
                    $this->session->set_flashdata('message', lang("transfers_deleted"));
                    redirect($_SERVER["HTTP_REFERER"]);

                } elseif ($this->input->post('form_action') == 'combine') {

                    $html = $this->combine_pdf($_POST['val']);

                } elseif ($this->input->post('form_action') == 'export_excel' || $this->input->post('form_action') == 'export_pdf') {
                    if($this->Owner || $this->Admin){
                    $this->load->library('excel');
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle(lang('transfers'));
                    $this->excel->getActiveSheet()->SetCellValue('A1', lang('date'));
                    $this->excel->getActiveSheet()->SetCellValue('B1', lang('reference_no'));
                    $this->excel->getActiveSheet()->SetCellValue('C1', lang('from_warehouse'));
                    $this->excel->getActiveSheet()->SetCellValue('D1', lang('to_warehouse'));
                    $this->excel->getActiveSheet()->SetCellValue('E1', lang('quantity_transfer'));
                    $this->excel->getActiveSheet()->SetCellValue('F1', lang('status'));
                    $this->excel->getActiveSheet()->SetCellValue('G1', lang('note'));
                    $styleArray = array(
                        'font'  => array(
                            'bold'  => true
                        )
                    );
                    
                    $this->excel->getActiveSheet()->getStyle('A1:G1')->applyFromArray($styleArray);
                    $row = 2;
                    $sum_quantity = 0;
                    foreach ($_POST['val'] as $id) {
                        $tansfer = $this->transfers_model->getTransferByID($id);
                        //sum quantity
                        $sum_quantity += $tansfer->qty;
                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $this->erp->hrld($tansfer->date));
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $tansfer->transfer_no." ");
                        $this->excel->getActiveSheet()->SetCellValue('C' . $row, $tansfer->fname."(".$tansfer->fcode.")");
                        $this->excel->getActiveSheet()->SetCellValue('D' . $row, $tansfer->tname."(".$tansfer->tcode.")");
                        $this->excel->getActiveSheet()->SetCellValue('E' . $row, $tansfer->qty);
                        $this->excel->getActiveSheet()->SetCellValue('F' . $row, $tansfer->status);
                        $this->excel->getActiveSheet()->SetCellValue('G' . $row, $this->erp->decode_html(strip_tags($tansfer->note)));
                        
                        $i = $row+1;
                        $this->excel->getActiveSheet()->SetCellValue('E' . $i, $sum_quantity);
						
                        $row++;
                    }
                }else {
                    // echo "user";exit();
                    $this->load->library('excel');
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle(lang('transfers'));
                    $this->excel->getActiveSheet()->SetCellValue('A1', lang('date'));
                    $this->excel->getActiveSheet()->SetCellValue('B1', lang('reference_no'));
                    $this->excel->getActiveSheet()->SetCellValue('C1', lang('from_warehouse'));
                    $this->excel->getActiveSheet()->SetCellValue('D1', lang('to_warehouse'));
                    $this->excel->getActiveSheet()->SetCellValue('E1', lang('quantity_transfer'));
                    $this->excel->getActiveSheet()->SetCellValue('F1', lang('status'));
                    $this->excel->getActiveSheet()->SetCellValue('G1', lang('note'));
                    $styleArray = array(
                        'font'  => array(
                            'bold'  => true
                        )
                    );
                    
                    $this->excel->getActiveSheet()->getStyle('A1:F1')->applyFromArray($styleArray);
                    $row = 2;
                    $sum_quantity = 0;
                    foreach ($_POST['val'] as $id) {
                        $tansfer = $this->transfers_model->getTransferByID($id,$wh);
                        //sum quantity
                        $sum_quantity += $tansfer->qty;
                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $this->erp->hrld($tansfer->date));
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $tansfer->transfer_no." ");
                        $this->excel->getActiveSheet()->SetCellValue('C' . $row, $tansfer->fname."(".$tansfer->fcode.")");
                        $this->excel->getActiveSheet()->SetCellValue('D' . $row, $tansfer->tname."(".$tansfer->tcode.")");
                        $this->excel->getActiveSheet()->SetCellValue('E' . $row, $tansfer->qty);
                        $this->excel->getActiveSheet()->SetCellValue('F' . $row, $tansfer->status);
                        $this->excel->getActiveSheet()->SetCellValue('G' . $row, $this->erp->decode_html(strip_tags($tansfer->note)));

                        $i = $row+1;
                        $this->excel->getActiveSheet()->SetCellValue('E' . $i, $sum_quantity);
                        
                        $row++;
                    }
                }

                    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
                    $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
                    $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $filename = 'list_tansfers_stock_' . date('Y_m_d_H_i_s');
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
						
						//Add style bold text in case PDF
                        $this->excel->getActiveSheet()->getStyle('E'. $i. '')->getFont()->setBold(true);

                        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'PDF');
                        return $objWriter->save('php://output');
                    }
                    if ($this->input->post('form_action') == 'export_excel') {
                        header('Content-Type: application/vnd.ms-excel');
                        header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
                        header('Cache-Control: max-age=0');
						
						//apply style border top and bold text in case excel
                        $this->excel->getActiveSheet()->getStyle('E'.$i. '')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                        $this->excel->getActiveSheet()->getStyle('E'. $i. '')->getFont()->setBold(true);

                        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
                        return $objWriter->save('php://output');
                    }

                    redirect($_SERVER["HTTP_REFERER"]);
                }
            } else {
                $this->session->set_flashdata('error', lang("no_transfer_selected"));
                redirect($_SERVER["HTTP_REFERER"]);
            }
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }

	public function list_in_transfer($warehouse_id = NULL){
		$this->erp->checkPermissions('index',null, 'transfers');
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
                //$this->erp->print_arrays(str_replace(',', '-',$this->session->userdata('warehouse_id')));
                $this->data['warehouse_id'] = str_replace(',', '-',$this->session->userdata('warehouse_id'));
                $this->data['warehouse'] = $this->session->userdata('warehouse_id') ? $this->products_model->getUserWarehouses() : NULL;
            }
        }

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('transfers'), 'page' => lang('transfers')), array('link' => '#', 'page' => lang('product_transfer_list')));
        $meta = array('page_title' => lang('product_transfer'), 'bc' => $bc);
        $this->page_construct('transfers/in_transfer', $meta, $this->data);
	}
	
	function add_in_transfer()
    {
        $this->erp->checkPermissions('add',null,'transfers');
        $this->form_validation->set_message('is_natural_no_zero', lang("no_zero_required"));
        //$this->form_validation->set_rules('reference_no', lang("reference_no"), 'required');
        $this->form_validation->set_rules('to_warehouse', lang("warehouse") . ' (' . lang("to") . ')', 'required|is_natural_no_zero');
        $this->form_validation->set_rules('from_warehouse', lang("warehouse") . ' (' . lang("from") . ')', 'required|is_natural_no_zero');

        if ($this->form_validation->run()) {

            $transfer_no = $this->input->post('reference_no') ? $this->input->post('reference_no') : $this->site->getReference('to');
            if ($this->Owner || $this->Admin) {
                $date = $this->erp->fld(trim($this->input->post('date')));
            } else {
                $date = date('Y-m-d H:i:s');
            }
            $to_warehouse = $this->input->post('to_warehouse');
            $from_warehouse = $this->input->post('from_warehouse');
            $note = $this->erp->clear_tags($this->input->post('note'));
            $shipping = $this->input->post('shipping');
            $status = $this->input->post('status');
            $from_warehouse_details = $this->site->getWarehouseByID($from_warehouse);
            $from_warehouse_code = $from_warehouse_details->code;
            $from_warehouse_name = $from_warehouse_details->name;
            $to_warehouse_details = $this->site->getWarehouseByID($to_warehouse);
            $to_warehouse_code = $to_warehouse_details->code;
            $to_warehouse_name = $to_warehouse_details->name;

            $total = 0;
            $product_tax = 0;

            $i = isset($_POST['product_code']) ? sizeof($_POST['product_code']) : 0;
            for ($r = 0; $r < $i; $r++) {
                $item_code = $_POST['product_code'][$r];
                $item_net_cost = $this->erp->formatDecimal($_POST['net_cost'][$r]);
                $unit_cost = $this->erp->formatDecimal($_POST['unit_cost'][$r]);
                $real_unit_cost = $this->erp->formatDecimal($_POST['real_unit_cost'][$r]);
                $item_quantity = $_POST['quantity'][$r];
                $item_tax_rate = isset($_POST['product_tax'][$r]) ? $_POST['product_tax'][$r] : NULL;
                $item_expiry = isset($_POST['expiry'][$r]) ? $this->erp->fsd($_POST['expiry'][$r]) : NULL;
                $item_option = isset($_POST['product_option'][$r]) && $_POST['product_option'][$r] != 'false' && $_POST['product_option'][$r] != 'null' ? $_POST['product_option'][$r] : NULL;

                if (isset($item_code) && isset($real_unit_cost) && isset($unit_cost) && isset($item_quantity)) {
                    $product_details = $this->transfers_model->getProductByCode($item_code);
                    // if (!$this->Settings->overselling) {
                        $warehouse_quantity = $this->transfers_model->getWarehouseProduct($from_warehouse_details->id, $product_details->id, $item_option);
                        if ($warehouse_quantity->quantity < $item_quantity) {
                            $this->session->set_flashdata('error', lang("no_match_found") . " (" . lang('product_name') . " <strong>" . $product_details->name . "</strong> " . lang('product_code') . " <strong>" . $product_details->code . "</strong>)");
                            redirect("transfers/add");
                        }
                   // }

                    if (isset($item_tax_rate) && $item_tax_rate != 0) {
                        $pr_tax = $item_tax_rate;
                        $tax_details = $this->site->getTaxRateByID($pr_tax);
                        if ($tax_details->type == 1 && $tax_details->rate != 0) {

                            if ($product_details && $product_details->tax_method == 1) {
                                $item_tax = $this->erp->formatDecimal((($unit_cost) * $tax_details->rate) / 100, 4);
                                $tax = $tax_details->rate . "%";
                            } else {
                                $item_tax = $this->erp->formatDecimal((($unit_cost) * $tax_details->rate) / (100 + $tax_details->rate), 4);
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

                    $item_net_cost = ($product_details && $product_details->tax_method == 1) ? $this->erp->formatDecimal($unit_cost) : $this->erp->formatDecimal($unit_cost-$item_tax);
                    $product_tax += $pr_item_tax;
                    $subtotal = (($item_net_cost * $item_quantity) + $pr_item_tax);

                    $products[] = array(
                        'product_id' => $product_details->id,
                        'product_code' => $item_code,
                        'product_name' => $product_details->name,
                        //'product_type' => $item_type,
                        'option_id' => $item_option,
                        'net_unit_cost' => $item_net_cost,
                        'unit_cost' => $this->erp->formatDecimal($item_net_cost + $item_tax),
                        'quantity' => $item_quantity,
                        'quantity_balance' => $item_quantity,
                        'warehouse_id' => $to_warehouse,
                        'item_tax' => $pr_item_tax,
                        'tax_rate_id' => $pr_tax,
                        'tax' => $tax,
                        'subtotal' => $this->erp->formatDecimal($subtotal),
                        'expiry' => $item_expiry,
                        'real_unit_cost' => $real_unit_cost,
                        'date' => date('Y-m-d', strtotime($date))
                    );
					//$this->erp->print_arrays($products);
                    $total += $item_net_cost * $item_quantity;
                }
            }
            if (empty($products)) {
                $this->form_validation->set_rules('product', lang("order_items"), 'required');
            } else {
                krsort($products);
            }

            $grand_total = $total + $shipping + $product_tax;
            $data = array('transfer_no' => $transfer_no,
                'date' => $date,
                'from_warehouse_id' => $from_warehouse,
                'from_warehouse_code' => $from_warehouse_code,
                'from_warehouse_name' => $from_warehouse_name,
                'to_warehouse_id' => $to_warehouse,
                'to_warehouse_code' => $to_warehouse_code,
                'to_warehouse_name' => $to_warehouse_name,
                'note' => $note,
                'total_tax' => $product_tax,
                'total' => $total,
                'grand_total' => $grand_total,
                'created_by' => $this->session->userdata('user_id'),
                'status' => $status,
                'shipping' => $shipping
            );

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
            }
			
			if ($_FILES['document1']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->digital_upload_path;
                $config['allowed_types'] = $this->digital_file_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = FALSE;
                $config['encrypt_name'] = TRUE;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('document1')) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                $data['attachment1'] = $photo;
            }
			
			if ($_FILES['document2']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->digital_upload_path;
                $config['allowed_types'] = $this->digital_file_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = FALSE;
                $config['encrypt_name'] = TRUE;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('document2')) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                $data['attachment2'] = $photo;
            }
			
			//echo $data['attachment1'];exit;

            //$this->erp->print_arrays($data, $products);
        }

        if ($this->form_validation->run() == true && $this->transfers_model->addTransfer($data, $products)) {
            $this->session->set_userdata('remove_tols', 1);
            $this->session->set_flashdata('message', lang("transfer_added"));
            redirect("transfers/list_in_transfer");
        } else {


            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

            $this->data['name'] = array('name' => 'name',
                'id' => 'name',
                'type' => 'text',
                'value' => $this->form_validation->set_value('name'),
            );
            $this->data['quantity'] = array('name' => 'quantity',
                'id' => 'quantity',
                'type' => 'text',
                'value' => $this->form_validation->set_value('quantity'),
            );

            $this->data['warehouses'] = $this->site->getAllWarehouses();
            $this->data['tax_rates'] = $this->site->getAllTaxRates();
            $this->data['rnumber'] = ''; //$this->site->getReference('to');
            $this->data['reference_no'] = $this->site->getReference('to'); //$this->site->getReference('to');
			
            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('products'), 'page' => lang('products')), array('link' => '#', 'page' => lang('add_transfer')));
            $meta = array('page_title' => lang('transfer_quantity'), 'bc' => $bc);
            $this->page_construct('transfers/add_in_transfer', $meta, $this->data);
        }
    }

    function edit_in_transfer($id = NULL)
    {
        $this->erp->checkPermissions();

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $transfer = $this->transfers_model->getTransferByID($id);
        if (!$this->session->userdata('edit_right')) {
            $this->erp->view_rights($transfer->created_by);
        }
        $this->form_validation->set_message('is_natural_no_zero', lang("no_zero_required"));
        $this->form_validation->set_rules('reference_no', lang("reference_no"), 'required');
        $this->form_validation->set_rules('to_warehouse', lang("warehouse") . ' (' . lang("to") . ')', 'required|is_natural_no_zero');
        $this->form_validation->set_rules('from_warehouse', lang("warehouse") . ' (' . lang("from") . ')', 'required|is_natural_no_zero');
        //$this->form_validation->set_rules('note', lang("note"), 'xss_clean');

        if ($this->form_validation->run()) {

            $transfer_no = $this->input->post('reference_no');
            if ($this->Owner || $this->Admin) {
                $date = $this->erp->fld(trim($this->input->post('date')));
            } else {
                $date = date('Y-m-d H:i:s');
            }
            $to_warehouse = $this->input->post('to_warehouse');
            $from_warehouse = $this->input->post('from_warehouse');
            $note = $this->erp->clear_tags($this->input->post('note'));
            $shipping = $this->input->post('shipping');
            $status = $this->input->post('status');
            $from_warehouse_details = $this->site->getWarehouseByID($from_warehouse);
            $from_warehouse_code = $from_warehouse_details->code;
            $from_warehouse_name = $from_warehouse_details->name;
            $to_warehouse_details = $this->site->getWarehouseByID($to_warehouse);
            $to_warehouse_code = $to_warehouse_details->code;
            $to_warehouse_name = $to_warehouse_details->name;

            $total = 0;
            $product_tax = 0;

            $i = isset($_POST['product_code']) ? sizeof($_POST['product_code']) : 0;
            for ($r = 0; $r < $i; $r++) {
                $item_code = $_POST['product_code'][$r];
                $item_net_cost = $this->erp->formatDecimal($_POST['net_cost'][$r]);
                $unit_cost = $this->erp->formatDecimal($_POST['unit_cost'][$r]);
                $real_unit_cost = $this->erp->formatDecimal($_POST['real_unit_cost'][$r]);
                $item_quantity = $_POST['quantity'][$r];
                $quantity_balance = $_POST['quantity_balance'][$r];
                $item_tax_rate = isset($_POST['product_tax'][$r]) ? $_POST['product_tax'][$r] : NULL;
                $item_expiry = isset($_POST['expiry'][$r]) ? $this->erp->fsd($_POST['expiry'][$r]) : NULL;
                $item_option = isset($_POST['product_option'][$r]) && $_POST['product_option'][$r] != 'false' && $_POST['product_option'][$r] != 'null' ? $_POST['product_option'][$r] : NULL;

                if (isset($item_code) && isset($real_unit_cost) && isset($unit_cost) && isset($item_quantity)) {
                    $product_details = $this->transfers_model->getProductByCode($item_code);

                    if (isset($item_tax_rate) && $item_tax_rate != 0) {
                        $pr_tax = $item_tax_rate;
                        $tax_details = $this->site->getTaxRateByID($pr_tax);
                        if ($tax_details->type == 1 && $tax_details->rate != 0) {

                            if ($product_details && $product_details->tax_method == 1) {
                                $item_tax = $this->erp->formatDecimal((($unit_cost) * $tax_details->rate) / 100, 4);
                                $tax = $tax_details->rate . "%";
                            } else {
                                $item_tax = $this->erp->formatDecimal((($unit_cost) * $tax_details->rate) / (100 + $tax_details->rate), 4);
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

                    $item_net_cost = ($product_details && $product_details->tax_method == 1) ? $this->erp->formatDecimal($unit_cost) : $this->erp->formatDecimal($unit_cost-$item_tax);
                    $product_tax += $pr_item_tax;
                    $subtotal = (($item_net_cost * $item_quantity) + $pr_item_tax);

                    $products[] = array(
                        'product_id' => $product_details->id,
                        'product_code' => $item_code,
                        'product_name' => $product_details->name,
                        //'product_type' => $item_type,
                        'option_id' => $item_option,
                        'net_unit_cost' => $item_net_cost,
                        'unit_cost' => $this->erp->formatDecimal($item_net_cost + $item_tax),
                        'quantity' => $item_quantity,
                        'quantity_balance' => $item_quantity,
                        'warehouse_id' => $to_warehouse,
                        'item_tax' => $pr_item_tax,
                        'tax_rate_id' => $pr_tax,
                        'tax' => $tax,
                        'subtotal' => $this->erp->formatDecimal($subtotal),
                        'expiry' => $item_expiry,
                        'real_unit_cost' => $real_unit_cost
                    );
                    $total += $item_net_cost * $item_quantity;
                }
            }
            if (empty($products)) {
                $this->form_validation->set_rules('product', lang("order_items"), 'required');
            } else {
                krsort($products);
            }

            $grand_total = $total + $shipping + $product_tax;
            $data = array('transfer_no' => $transfer_no,
                'date' => $date,
                'from_warehouse_id' => $from_warehouse,
                'from_warehouse_code' => $from_warehouse_code,
                'from_warehouse_name' => $from_warehouse_name,
                'to_warehouse_id' => $to_warehouse,
                'to_warehouse_code' => $to_warehouse_code,
                'to_warehouse_name' => $to_warehouse_name,
                'note' => $note,
                'total_tax' => $product_tax,
                'total' => $total,
                'grand_total' => $grand_total,
                'created_by' => $this->session->userdata('user_id'),
                'status' => $status,
                'shipping' => $shipping
            );

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
            }
			
			if ($_FILES['document1']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->digital_upload_path;
                $config['allowed_types'] = $this->digital_file_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = FALSE;
                $config['encrypt_name'] = TRUE;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('document1')) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                $data['attachment1'] = $photo;
            }
			
			if ($_FILES['document2']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->digital_upload_path;
                $config['allowed_types'] = $this->digital_file_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = FALSE;
                $config['encrypt_name'] = TRUE;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('document2')) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                $data['attachment2'] = $photo;
            }
            //$this->erp->print_arrays($data, $products);
        }

        if ($this->form_validation->run() == true && $this->transfers_model->updateTransfer($id, $data, $products)) {
            $this->session->set_userdata('remove_tols', 1);
            $this->session->set_flashdata('message', lang("transfer_updated"));
            redirect("transfers");
        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['transfer'] = $this->transfers_model->getTransferByID($id);
            $transfer_items = $this->transfers_model->getAllTransferItems($id, $this->data['transfer']->status);
            $c = rand(100000, 9999999);
            foreach ($transfer_items as $item) {
                $row = $this->site->getProductByID($item->product_id);
                if (!$row) {
                    $row = json_decode('{}');
                } else {
                    unset($row->cost, $row->supplier1price, $row->supplier2price, $row->supplier3price, $row->supplier4price, $row->supplier5price);
                }
                $row->quantity = 0;
                $row->expiry = (($item->expiry && $item->expiry != '0000-00-00') ? $this->erp->hrsd($item->expiry) : '');
                $row->qty = $item->quantity;
                $row->quantity_balance = $item->quantity_balance;
                $row->quantity += $item->quantity_balance;
                $row->cost = $item->net_unit_cost;
                $row->unit_cost = $item->net_unit_cost+($item->item_tax/$item->quantity);
                $row->real_unit_cost = $item->real_unit_cost;
                $row->tax_rate = $item->tax_rate_id;
                $row->option = $item->option_id;
                //$options = $this->transfers_model->getProductOptions($row->id, $this->data['transfer']->from_warehouse_id, FALSE);
				$options = $this->transfers_model->getUnitById($row->id, $this->data['transfer']->from_warehouse_id);
                $pis = $this->transfers_model->getPurchasedItems($item->product_id, $item->warehouse_id, $item->option_id);
                if($pis){
                    foreach ($pis as $pi) {
                        $row->quantity += $pi->quantity_balance;
                    }
                }
                $row->quantity += $item->quantity;
                if ($options) {
                    $option_quantity = 0;
                    foreach ($options as $option) {
                        $pis = $this->transfers_model->getPurchasedItems($row->id, $item->warehouse_id, $item->option_id);
                        if($pis){
                            foreach ($pis as $pi) {
                                $option_quantity += $pi->quantity_balance;
                            }
                        }
                        $option_quantity += $item->quantity;
                        if($option->quantity > $option_quantity) {
                            $option->quantity = $option_quantity;
                        }
                    }
                }

                $ri = $this->Settings->item_addition ? $row->id : $c;
                if ($row->tax_rate) {
                    $tax_rate = $this->site->getTaxRateByID($row->tax_rate);
                    $pr[$ri] = array('id' => $c, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'tax_rate' => $tax_rate, 'options' => $options);
                } else {
                    $pr[$ri] = array('id' => $c, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'tax_rate' => false, 'options' => $options);
                }
                $c++;
            }

            $this->data['transfer_items'] = json_encode($pr);
            $this->data['id'] = $id;
            $this->data['warehouses'] = $this->site->getAllWarehouses();
            $this->data['tax_rates'] = $this->site->getAllTaxRates();
            $this->data['rnumber'] = $this->site->getReference('to');

            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('transfers'), 'page' => lang('transfers')), array('link' => '#', 'page' => lang('edit_transfer')));
            $meta = array('page_title' => lang('edit_transfer_quantity'), 'bc' => $bc);
            $this->page_construct('transfers/edit_in_transfer', $meta, $this->data);
        }
    }

	function transfer_back($id = NULL){
		$transfer = $this->transfers_model->getTransferByID($id);
		$data = array(
			'transfer_no' => $this->site->getReference('to'),
			'date' => date('Y-m-d H:i:s'),
			'from_warehouse_id' => $transfer->to_warehouse_id,
			'from_warehouse_code' => $transfer->to_warehouse_code,
			'from_warehouse_name' => $transfer->to_warehouse_name,
			'to_warehouse_id' => $transfer->from_warehouse_id,
			'to_warehouse_code' => $transfer->from_warehouse_code,
			'to_warehouse_name' => $transfer->from_warehouse_name,
			'note' => $transfer->note,
			'total_tax' => $transfer->total_tax,
			'total' => $transfer->total,
			'grand_total' => $transfer->grand_total,
			'created_by' => $this->session->userdata('user_id'),
			'status' => $transfer->status,
			'shipping' => $transfer->shipping,
			'attachment' => $transfer->attachment,
			'attachment1' => $transfer->attachment1,
			'attachment2' => $transfer->attachment2
		);
		$transfer_items = $this->transfers_model->getAllTransferItems($id, $transfer->status);
		foreach($transfer_items as $item){
			$products[] = array(
				'product_id' => $item->id,
				'product_code' => $item->product_code,
				'product_name' => $item->product_name,
				//'transfer_id' => $item_type,
				'option_id' => $item->option_id,
				'net_unit_cost' => $item->net_unit_cost,
				'unit_cost' => $item->unit_cost,
				'quantity' => $item->quantity,
				'quantity_balance' => $item->quantity_balance,
				'warehouse_id' => $item->warehouse_id,
				'item_tax' => $item->item_tax,
				'tax_rate_id' => $item->tax_rate_id,
				'tax' => $item->tax,
				'subtotal' => $item->subtotal,
				'expiry' => $item->expiry,
				'real_unit_cost' => $item->real_unit_cost,
				'date' => date('Y-m-d')
			);
		}
		//$this->erp->print_arrays($data, $products);
		if($this->transfers_model->addTransfer($data, $products)){
			$this->session->set_userdata('remove_tols', 1);
            $this->session->set_flashdata('message', lang("transfer_backed"));
            redirect("transfers");
		}
		return false;
	}

    function invoice($id = null)
    {
       $this->erp->checkPermissions('index', TRUE);
        
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
        $inv = $this->transfers_model->getTransfersInvoiceByID($id);
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
        
        $this->data['inv'] = $inv;
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $rows = $this->data['rows'] = $this->transfers_model->getAllTransfersInvoice($id);
        $this->data['setting'] = $this->site->get_setting();
        $this->data['page_title'] = lang("delivery_order");
        $this->data['rows'] = $rows;
        
        $transfer = $this->transfers_model->getTransfersInvoiceByID($id);
        $this->data['from_warehouse'] = $this->site->getWarehouseByID($transfer->from_warehouse_id);
        $this->data['to_warehouse'] = $this->site->getWarehouseByID($transfer->to_warehouse_id);
        // $this->erp->print_arrays($this->data['from_warehouse']);

        $this->load->view($this->theme.'transfers/invoice', $this->data);
    }

    function invoice_chea_kheng($id = null)
    {
       $this->erp->checkPermissions('index', TRUE);
        
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
        $inv = $this->transfers_model->getTransfersInvoiceByID($id);
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
        
        $this->data['inv'] = $inv;
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        // $rows = $this->transfers_model->getAllTransfersInvoice($id);
        $rows = $this->transfers_model->getAllTransferItems($id);
        $this->data['setting'] = $this->site->get_setting();
        $this->data['page_title'] = lang("delivery_order");
        $this->data['rows'] = $rows;
        
        $transfer = $this->transfers_model->getTransfersInvoiceByID($id);
        $this->data['from_warehouse'] = $this->site->getWarehouseByID($transfer->from_warehouse_id);
        $this->data['to_warehouse'] = $this->site->getWarehouseByID($transfer->to_warehouse_id);
        // $this->erp->print_arrays($this->data['from_warehouse']);

        $this->load->view($this->theme.'transfers/invoice_chea_kheng', $this->data);
    }
	
	function invoice_uy_sing($id = null)
    {
       $this->erp->checkPermissions('index', TRUE);
        
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
        $inv = $this->transfers_model->getTransfersInvoiceByID($id);
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
        
        $this->data['inv'] = $inv;
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        // $rows = $this->transfers_model->getAllTransfersInvoice($id);
        $rows = $this->transfers_model->getAllTransferItems($id);
        $this->data['setting'] = $this->site->get_setting();
        $this->data['page_title'] = lang("delivery_order");
        $this->data['rows'] = $rows;
        
        $transfer = $this->transfers_model->getTransfersInvoiceByID($id);
        $this->data['from_warehouse'] = $this->site->getWarehouseByID($transfer->from_warehouse_id);
        $this->data['to_warehouse'] = $this->site->getWarehouseByID($transfer->to_warehouse_id);
        // $this->erp->print_arrays($this->data['from_warehouse']);

        $this->load->view($this->theme.'transfers/invoice_uy_sing', $this->data);
    }
	 function invoice_transfer_kh_chea_kheng($transfer_id = null)
    {
       $this->erp->checkPermissions('index', TRUE);
        
        if ($this->input->get('id')) {
            $transfer_id = $this->input->get('id');
        }
        
        $inv        = $this->transfers_model->getTransfersInvoiceByID($transfer_id);
        $transfer   = NULL;
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
        
        $this->data['inv'] = $inv;
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $rows = $this->data['rows'] = $this->transfers_model->getAllTransferItems($transfer_id, $transfer->status);
        $this->data['setting'] = $this->site->get_setting();
        $this->data['page_title'] = lang("delivery_order");
        $this->data['rows'] = $rows;
        //$this->erp->print_arrays($rows);
        $transfer = $this->transfers_model->getTransfersInvoiceByID($transfer_id);
        $this->data['from_warehouse'] = $this->site->getWarehouseByID($transfer->from_warehouse_id);
        $this->data['to_warehouse'] = $this->site->getWarehouseByID($transfer->to_warehouse_id);

        $this->load->view($this->theme.'transfers/invoice_transfer_kh_chea_kheng', $this->data);
    }
}
