<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Sale_order extends MY_Controller
{
	function __construct()
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
		
        $this->lang->load('sales', $this->Settings->language);
        $this->load->library('form_validation');
        $this->load->model('sales_model');
		$this->load->model('Site');
		$this->load->model('sale_order_model');
		$this->load->model('products_model'); 
		$this->load->model('reports_model'); 
		$this->load->model('pos_model');
        $this->digital_upload_path = 'files/';
        $this->upload_path = 'assets/uploads/';
        $this->thumbs_path = 'assets/uploads/thumbs/';
        $this->image_types = 'gif|jpg|jpeg|png|tif';
        $this->digital_file_types = 'zip|psd|ai|rar|pdf|doc|docx|xls|xlsx|ppt|pptx|gif|jpg|jpeg|png|tif|txt';
        $this->allowed_file_size = '10240';
        $this->data['logo'] = true;
		$this->load->model('Driver_modal');
		
		$this->load->helper('text');
        $this->pos_settings = $this->pos_model->getSetting();
        $this->pos_settings->pin_code = $this->pos_settings->pin_code ? md5($this->pos_settings->pin_code) : NULL;
        $this->data['pos_settings'] = $this->pos_settings;
        
        if(!$this->Owner && !$this->Admin) {
            $gp = $this->site->checkPermissions();
            $this->permission = $gp[0];
            $this->permission[] = $gp[0];
        } else {
            $this->permission[] = NULL;
        }
        $this->default_biller_id = $this->site->default_biller_id();
    }
	
	
	function index($warehouse_id = NULL)
    {
		$this->erp->checkPermissions('index',null,'sale_order');
		$this->load->model('reports_model');

		if(isset($_GET['d']) != ""){
			$date = $_GET['d'];
			$this->data['date'] = $date;
		}

		$this->data['users'] = $this->reports_model->getStaff();
		$this->data['products'] = $this->site->getProducts();
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
		$this->data['agencies'] = $this->site->getAllUsers();


        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('sale_order')));
        $meta = array('page_title' => lang('sale_order'), 'bc' => $bc);
        $this->page_construct('sale_order/index', $meta, $this->data);
    }
	
	function invoice_ppcp($id=null)
	{
        $inv = $this->sale_order_model->getSaleOrder($id);
		$this->data['setting'] = $this->site->get_setting();
        $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
        $this->data['user'] = $this->site->getUser($inv->created_by);
        $this->data['saleman'] = $this->site->getUser($inv->salema_by);
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
        $this->data['invs'] = $inv;		
        $this->data['rows'] = $this->sale_order_model->getAllInvoiceItemsById($id);
        $this->load->view($this->theme .'sale_order/invoice_ppcp',$this->data);
    }
	
	/*=================================Chin local updated================================*/
	function add_sale_order($quote_ID = NULL)
    {
		$this->erp->checkPermissions('add', null, 'sale_order');

		$this->load->model('quotes_model');
		if($quote_ID){
			if (($this->quotes_model->getQuotesData($quote_ID)->status) == 'pending') {
				$this->session->set_flashdata('error', lang('quote_has_not_been_approved'));
				redirect($_SERVER['HTTP_REFERER']);
			}
			if (($this->quotes_model->getQuotesData($quote_ID)->status) == 'rejected') {
				$this->session->set_flashdata('error', lang('quote_has_been_rejected'));
				redirect($_SERVER['HTTP_REFERER']);
			} 
			if (($this->quotes_model->getQuotesData($quote_ID)->issue_invoice) != 'pending') {
				$this->session->set_flashdata('error', lang('quote_has_been_created'));
				redirect($_SERVER['HTTP_REFERER']);
			}
			
			if (($this->quotes_model->getQuotesData($quote_ID)->quote_status) == 'completed') {
				$this->session->set_flashdata('error', lang('quote_has_been_created'));
				redirect($_SERVER['HTTP_REFERER']);
			}
		}
        $this->form_validation->set_rules('biller', lang("biller"), 'required');
		$this->form_validation->set_rules('reference_no', lang("reference_no"), 'trim|required|is_unique[sale_order.reference_no]');

        if ($this->form_validation->run() == true)
        {
            $quantity = "quantity";
            $product = "product";
            $unit_cost = "unit_cost";
            $tax_rate = "tax_rate";
			$biller_id = $this->input->post('biller');
            $reference = $this->input->post('reference_no') ? $this->input->post('reference_no') : $this->site->getReference('sao',$biller_id);

            if ($this->Owner || $this->Admin || $this->Settings->allow_change_date == 1) {
                $date = $this->erp->fld($this->input->post('date'));
            } else {
                $date = date('Y-m-d H:i:s');
            }
            
            $delivery_date = $this->erp->fld($this->input->post('delivery_date'));
            $warehouse_id = $this->input->post('warehouse');
            $customer_id = $this->input->post('customer');
			$amout_paid = $this->input->post('amount-paid');
			$group_area = $this->input->post('area');
			$saleman_by = $this->input->post('saleman');
            $total_items = $this->input->post('total_items');
            $payment_term = $this->input->post('payment_term');
			//$payment_status = $this->input->post('payment_status');
			$payment_status = 'due';
            $due_date = $payment_term ? date('Y-m-d', strtotime('+' . $payment_term . ' days')) : NULL;
            $shipping = $this->input->post('shipping') ? $this->input->post('shipping') : 0;
            $sale_type = $this->input->post('purchase_type');
            $tax_type = $this->input->post('tax_type');
            $customer_details = $this->site->getCompanyByID($customer_id);
            $customer = $customer_details->company ? $customer_details->company : $customer_details->name;
            $biller_details = $this->site->getCompanyByID($biller_id);
            $biller = $biller_details->company != '-' ? $biller_details->company : $biller_details->name;
            $note = $this->input->post('note');
            $staff_note = $this->input->post('staff_note');
            $delivery_by = $this->input->post('delivery_by');
			$bill_to = $this->input->post('bill_to');
			$po = $this->input->post('po');
			
			$total = 0;
            $product_tax = 0;
            $order_tax = 0;
            $product_discount = 0;
            $order_discount = 0;
            $percentage = '%';
			$g_total_txt1 = 0;
			$loans = '';
			$i = isset($_POST['product_code']) ? sizeof($_POST['product_code']) : 0;
            for ($r = 0; $r < $i; $r++) {	
                $item_id = $_POST['product_id'][$r];
                $digital_id = $_POST['digital_id'][$r];
				$group_price_id = $_POST['group_price_id'][$r];
                $item_type = $_POST['product_type'][$r];
                $item_code = $_POST['product_code'][$r];
                $item_name = $_POST['product_name'][$r];
				$item_peice     = $_POST['piece'][$r];
				$item_wpeice	= $_POST['wpiece'][$r];
				$product_note = $_POST['product_note'][$r];
                $item_option = isset($_POST['product_option'][$r]) && $_POST['product_option'][$r] != 'false' ? $_POST['product_option'][$r] : null;
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
                    $pr_item_discount = $this->erp->formatDecimal($pr_discount);
                    $product_discount += $pr_item_discount * $item_quantity;
					
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
                        'digital_id' => $digital_id,
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
						'group_price_id'=>$group_price_id,
                        'tax' => $tax,
						'product_noted' => $product_note,
                        'discount' => $item_discount,
                        'item_discount' => $pr_item_discount * $item_quantity,
                        'subtotal' => $subtotal,
                        'real_unit_price' => $real_unit_price,
                        'price_id' => $item_price_id
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
            if ($this->input->post('order_discount')) {
                $order_discount_id = $this->input->post('order_discount');
                $opos = strpos($order_discount_id, $percentage);
                if ($opos !== false) {
                    $ods = explode("%", $order_discount_id);
                    $order_discount = $this->erp->formatDecimal((($total * (Float) ($ods[0])) / 100), 4);
                } else {
                    $order_discount = $this->erp->formatDecimal(($total * $order_discount_id) / 100);
                }
            } else {
				
                $order_discount = NULL;
            }
			
            $total_discount = $this->erp->formatDecimal($order_discount + $product_discount);
			$total_no_tax = 0;
			$order_tax = 0;
           
            if ($this->Settings->tax2) {
				$order_tax_id = $this->input->post('order_tax');
                if ($order_tax_details = $this->site->getTaxRateByID($order_tax_id)) {
					$order_discount_id = $this->input->post('order_discount');
					$opos = strpos($order_discount_id, $percentage);
					
                    if ($order_tax_details->type == 2) {
						
						if ($opos !== false) {							
							$ods = explode("%", $order_discount_id);
							$or_discount = $ods[0];
							$total_no_tax = ($total - ($total * ($or_discount/100)) + $shipping);
							$order_tax = $this->erp->formatDecimal((($total_no_tax * $order_tax_details->rate) / 100), 4);
						}else{
							
							$total_no_tax = ($total - $order_discount) + $shipping;
							$order_tax = $this->erp->formatDecimal((($total_no_tax * $order_tax_details->rate) / 100), 4);
						}
						
						
                    } elseif ($order_tax_details->type == 1) {
						if ($opos !== false) {
							$ods = explode("%", $order_discount_id);
							$or_discount = $ods[0];
							$total_no_tax = ($total - ($total * ($or_discount/100)) + $shipping);
							$order_tax = $this->erp->formatDecimal((($total_no_tax * $order_tax_details->rate) / 100), 4);
							
						} else {
							$total_no_tax = ($total - $order_discount) + $shipping;
							$order_tax = $this->erp->formatDecimal((($total_no_tax * $order_tax_details->rate) / 100), 4);
							
						}
						
						
                    }
                }
				
            } else {
                $order_tax_id = null;
            }
			
            $total_tax = $this->erp->formatDecimal(($product_tax + $order_tax), 4);
			$total_balance=$this->erp->formatDecimal(($total + $total_tax + $this->erp->formatDecimal($shipping) - $order_discount), 4);
			if($sale_type==1 && $total_tax==0){
					$total_tax= ($total_balance/1.1)*(0.1);	
			}	
			
			$grand_total = $total_no_tax + $order_tax;
			
			$photo = "";
			$photo1 = "";
			$photo2 = "";
			
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
               
            }

            if (isset($_FILES['document1']['size']) > 0) {
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
                $photo1 = $this->upload->file_name;
               
            }

            if (isset($_FILES['document2']['size']) > 0) {
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
                $photo2 = $this->upload->file_name;
               
            }
		
			$data = array(
                'date' => $date,
                'delivery_date' => $delivery_date,
				'quote_id' => $quote_ID,
                'reference_no' => $reference,
                'customer_id' => $customer_id,
                'customer' => $customer,
				'group_areas_id' => $group_area,
                'biller_id' => $biller_id,
                'biller' => $biller,
                'warehouse_id' => $warehouse_id,
                'note' => $note,
                'staff_note' => $staff_note,
                'total' => $total,
                'product_discount' => $product_discount,
                'order_discount_id' => $order_discount_id,
                'order_discount' => $order_discount,
                'total_discount' => $total_discount,
                'product_tax' => $product_tax,
                'order_tax_id' => $order_tax_id,
				'bill_to' => $bill_to,
				'po' => $po,
                'order_tax' => $order_tax,
                'total_tax' => $total_tax,
                'shipping' => $shipping,
                'grand_total' => $grand_total,
                'total_items' => $total_items,
                'payment_term' => $payment_term,
				'payment_status' => $payment_status,
                'due_date' => $due_date,
                'paid' => ($amout_paid != '' || $amout_paid != 0 || $amout_paid != null)? $amout_paid : 0,
                'created_by' => $this->session->userdata('user_id'),
				'sale_status' => 'order',
				'order_status' => ($this->Settings->authorization == 'auto' ? 'completed' : 'pending'),
				'saleman_by' => $saleman_by,
				'delivery_by' => $delivery_by,
				'sale_type' => $sale_type,
				'tax_type' => $tax_type,
				'attachment' => $photo,
				'attachment1' => $photo1,
				'attachment2' => $photo2
            );
        }
		
		
        if ($this->form_validation->run() == true) {
			$paid_by = $this->input->post('paid_by');
			$quote_ID = $this->input->post('quote_ID');
			if ($quote_ID) {
                $this->db->update('quotes', array('issue_invoice' => 'sale order'), array('id' => $quote_ID));
            }

            if ($quote_ID) {
				$this->quotes_model->updateQuoteStatus($quote_ID); 
			}
			
			$amount_paid = floatval(preg_replace("/[^0-9\.]/i", "", $amout_paid));
			
			$sale_order_id = $this->sale_order_model->addSaleOrder($data, $products);
			
			$this->session->set_userdata('remove_so2', '1');
			redirect("sale_order/list_sale_order");
		
			
        } else {
			
			if($quote_ID){
				$this->load->model('sales_model');
                $quote = $this->sales_model->getQuoteByID($quote_ID);
                $this->data['quotes'] = $quote;
				//$this->erp->print_arrays($this->sales_model->getQuoteByID($quote_ID));
				$items = $this->sales_model->getAllQuoteItems($quote_ID);
				$this->data['quote_ID'] = $quote_ID;
				$this->data['type'] = "quote";
				$this->data['type_id'] = $quote_ID;
                $c = rand(100000, 9999999);
				$customer = $this->site->getCompanyByID($quote->customer_id);
				$expiry_status = 0;
				if($this->site->get_setting()->product_expiry == 1){
					$expiry_status = 1;
				}
				
                foreach ($items as $item) {
                    $row = $this->site->getProductByIDWh($item->product_id,$item->warehouse_id);
                    $dig = $this->site->getProductByID($item->digital_id);
					$expdates = $this->sales_model->getProductExpireDate($row->id, $warehouse_id);
					//$this->erp->print_arrays($row);
										
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
                    $row->id            = $item->product_id;
                    $row->code          = $item->product_code;
                    //$row->name        = $item->product_name;
					$row->piece	        = $item->piece;
					$row->wpiece        = $item->wpiece;
					$row->w_piece       = $item->wpiece;
                    $row->type          = $item->product_type;
                    $row->qty           = $item->quantity;
                    $row->discount      = $item->discount ? $item->discount : '0';
                    $row->price         = $this->erp->formatDecimal($item->net_unit_price+$this->erp->formatDecimal($item->item_discount/$item->quantity));
                    $row->unit_price    = $row->tax_method ? $item->unit_price+$this->erp->formatDecimal($item->item_discount/$item->quantity)+$this->erp->formatDecimal($item->item_tax/$item->quantity) : $item->unit_price+($item->item_discount/$item->quantity);
                    $row->real_unit_price = $item->real_unit_price;
                    $row->tax_rate      = $item->tax_rate_id;
                    $row->serial        = '';
                    $row->option        = $item->option_id;
					$row->digital_code	= "";
					$row->digital_name	= "";
					$row->digital_id	= 0;
					$row->note          = $item->product_noted;
					if($dig){
						$row->digital_code 	= $dig->code .' ['. $row->code .']';
						$row->digital_name 	= $dig->name .' ['. $row->name .']';
						$row->digital_id   	= $dig->id;
					}
					
					if($expiry_status = 1){
						$row->expdate = $expdates[0]->id;
					}
					$group_prices = $this->sales_model->getProductPriceGroup($item->product_id, $customer->price_group_id);
					
					$all_group_prices = $this->sales_model->getProductPriceGroup($item->product_id);
					$row->is_quote = 1;
					$row->price_id = $item->group_price_id;
					//$row->price_id = 0;
					
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
					
					if($group_prices)
					{
					   $curr_by_item = $this->site->getCurrencyByCode($group_prices[0]->currency_code);
					}
					
					$row->rate_item_cur   = (isset($curr_by_item->rate)?$curr_by_item->rate:0);
				
					$row->load_item   = 1;
					
                    $ri = $this->Settings->item_addition ? $row->id : $c;
                    if ($row->tax_rate) {
                        $tax_rate = $this->site->getTaxRateByID($row->tax_rate);
                        $pr[$ri] = array('id' => $c, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'combo_items' => $combo_items, 'tax_rate' => $tax_rate, 'options' => $options,'group_prices'=>$group_prices,'makeup_cost' => 0,'group_prices'=>$group_prices, 'all_group_price' => $all_group_prices);
                    } else {
                        $pr[$ri] = array('id' => $c, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'combo_items' => $combo_items, 'tax_rate' => false, 'options' => $options,'group_prices'=>$group_prices,'makeup_cost' => 0,'group_prices'=>$group_prices, 'all_group_price' => $all_group_prices);
                    }
                    $c++;
                }
				
                $this->data['sale_order_items'] = json_encode($pr);
				$this->data['payment_deposit'] = (isset($payment_deposit)?$payment_deposit:0);
				$this->data['quote_id'] = $quote_ID;
				
			}
			/* $this->data['quotes'] = array();
			$this->data['quote_id'] = ''; */
			$this->load->model('purchases_model');
			$this->data['exchange_rate'] = $this->site->getCurrencyByCode('KHM');
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['billers'] = $this->site->getAllCompanies('biller');
            $this->data['warehouses'] = $this->site->getAllWarehouses();
            $this->data['tax_rates'] = $this->site->getAllTaxRates();
			$this->data['agencies'] = $this->site->getAllUsers();
			$this->data['customers'] = $this->site->getCustomers();
			$this->data['currency'] = $this->site->getCurrency();
			$this->data['categories'] = $this->site->getAllCategories();
			$this->data['unit'] = $this->purchases_model->getUnits();
			//$this->erp->print_arrays($this->site->getReference('so'));
			$this->data['drivers'] = $this->site->getDrivers(); //$this->erp->print_arrays($this->site->getDriverByGroupId());
			$this->data['payment_term'] = $this->site->getAllPaymentTerm();
			$this->data['areas'] = $this->site->getArea();
            $this->data['slnumber'] = ''; //$this->site->getReference('so');
			//$this->data['payment_ref'] = $this->site->getReference('sp');
			$this->data['setting'] = $this->site->get_setting();
			
			if ($this->Owner || $this->Admin || !$this->session->userdata('biller_id')){
				$biller_id = $this->site->get_setting()->default_biller;
				$this->data['reference'] = $this->site->getReference('sao',$biller_id);
			}else{
				$biller_id = $this->session->userdata('biller_id');
				$this->data['reference'] = $this->site->getReference('sao',$biller_id);
			}
			
            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('sale_order'), 'page' => lang('sale_order')), array('link' => '#', 'page' => lang('add_sale_order')));
            $meta = array('page_title' => lang('add_sale_order'), 'bc' => $bc);	
            $this->page_construct('sale_order/add_sale_order', $meta, $this->data);
        }
    }
	
	function list_sale_order($sale_order_id = null, $warehouse_id = Null){
		
		$this->erp->checkPermissions('index', null, 'sale_order');
		$this->data['products'] = $this->site->getProducts();
		$this->data['users'] = $this->reports_model->getStaff();
		$this->data['agencies'] = $this->site->getAllUsers();
		
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
		
        $biller_id = $this->session->userdata('biller_id');
        $this->data['billers'] = $this->site->getAllCompanies('biller');
        $this->data['user_billers'] = $this->sale_order_model->getAllCompaniesByID($biller_id);
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('sale_order_list')));
        $meta = array('page_title' => lang('sale_order_list'), 'bc' => $bc);
        $this->page_construct('sale_order/list_sale_order', $meta, $this->data);
	}
	/*========================================end local updated====================================*/
	
	function modal_order_view($id = NULL)
    {
        $this->erp->checkPermissions('index', false, 'sale_order');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $deposit_so_id = $this->sales_model->getDeposit($id);
        $this->load->model('pos_model');
        $this->data['so_id'] =  $deposit_so_id;
        $this->data['deposit'] = $this->sales_model->getDepositByID($id);
		$this->data['pos'] = $this->pos_model->getSetting();
		$this->data['setting'] = $this->site->get_setting();
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $inv = $this->sales_model->getSaleOrder($id);
        //$this->erp->view_rights($inv->created_by, TRUE);
        $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
        $this->data['created_by'] = $this->site->getUser($inv->created_by);
        $this->data['updated_by'] = $inv->updated_by ? $this->site->getUser($inv->updated_by) : NULL;
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
        $this->data['inv'] = $inv;
        $return = $this->sales_model->getReturnBySID($id);
        $this->data['return_sale'] = $return;
        $this->data['rows'] = $this->sale_order_model->getAllInvoiceItems($id);

        $this->load->view($this->theme.'sale_order/modal_order_view', $this->data);
    }
	
	function tax_invoice1($id = NULL)
    {
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
		
		$this->load->model('pos_model');
		$this->data['pos'] = $this->pos_model->getSetting();
		$this->data['setting'] = $this->site->get_setting();
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $inv = $this->sales_model->getSaleOrder($id);
        $this->data['deposit'] = $this->sales_model->getDepositByID($id);
        $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
        $this->data['created_by'] = $this->site->getUser($inv->created_by);
        $this->data['updated_by'] = $inv->updated_by ? $this->site->getUser($inv->updated_by) : NULL;
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
        $this->data['invs'] = $inv;
        $return = $this->sales_model->getReturnBySID($id);
        $this->data['return_sale'] = $return;
        $this->data['rows'] = $this->sale_order_model->getAllInvoiceItemsById($id);
		$this->data['logo'] = true;
        $this->load->view($this->theme . 'sale_order/invoice_st_a4', $this->data);
    }

    function print_st_invoice_2($id=null)
    {
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        $this->load->model('pos_model');
        $this->data['pos'] = $this->pos_model->getSetting();
        $this->data['setting'] = $this->site->get_setting();
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $inv = $this->sales_model->getSaleOrder($id);
        $this->data['deposit'] = $this->sales_model->getDepositByID($id);
        $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
        $this->data['created_by'] = $this->site->getUser($inv->created_by);
        $this->data['updated_by'] = $inv->updated_by ? $this->site->getUser($inv->updated_by) : NULL;
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
        $this->data['invs'] = $inv;
        $return = $this->sales_model->getReturnBySID($id);
        $this->data['return_sale'] = $return;
        $this->data['rows'] = $this->sale_order_model->getAllInvoiceItemsById($id);
        $this->data['logo'] = true;
        $this->load->view($this->theme . 'sale_order/invoice_st_a4_2', $this->data);
    }

    function print_iphoto($id = NULL)
    {
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
        $this->load->model('pos_model');
        $this->data['pos'] = $this->pos_model->getSetting();
        $this->data['setting'] = $this->site->get_setting();
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $inv = $this->sales_model->getSaleOrder($id);
        $this->data['deposit'] = $this->sales_model->getDepositByID($id);
        $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
        $this->data['created_by'] = $this->site->getUser($inv->created_by);
        $this->data['updated_by'] = $inv->updated_by ? $this->site->getUser($inv->updated_by) : NULL;
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
        $this->data['invs'] = $inv;
        $return = $this->sales_model->getReturnBySID($id);
        $this->data['return_sale'] = $return;
        $this->data['rows'] = $this->sale_order_model->getAllInvoiceItemsById($id);
        $this->data['logo'] = true;
        $this->load->view($this->theme . 'sale_order/invoice_iphoto', $this->data);
    }
     function invoice_camera_city($id = NULL)
    {
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
        $this->load->model('pos_model');
        $this->data['pos'] = $this->pos_model->getSetting();
        $this->data['setting'] = $this->site->get_setting();
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $inv = $this->sales_model->getSaleOrder($id);
        $this->data['deposit'] = $this->sales_model->getDepositByID($id);
        $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
        $this->data['created_by'] = $this->site->getUser($inv->created_by);
        $this->data['updated_by'] = $inv->updated_by ? $this->site->getUser($inv->updated_by) : NULL;
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
        $this->data['invs'] = $inv;
        $return = $this->sales_model->getReturnBySID($id);
        $this->data['return_sale'] = $return;
        $this->data['rows'] = $this->sale_order_model->getAllInvoiceItemsById($id);
        $this->data['logo'] = true;
        $this->load->view($this->theme . 'sale_order/invoice_camera_city', $this->data);
    }
	
	function sale_order_thai_san($id = NULL)
    {
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
		
		$this->load->model('pos_model');
		$this->data['pos'] = $this->pos_model->getSetting();
		$this->data['setting'] = $this->site->get_setting();
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $inv = $this->sales_model->getSaleOrder($id);
        $this->data['deposit'] = $this->sales_model->getDepositByID($id);
        $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
        $this->data['created_by'] = $this->site->getUser($inv->created_by);
        $this->data['updated_by'] = $inv->updated_by ? $this->site->getUser($inv->updated_by) : NULL;
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
        $this->data['invs'] = $inv;
        $return = $this->sales_model->getReturnBySID($id);
        $this->data['return_sale'] = $return;
        $this->data['rows'] = $this->sale_order_model->getAllInvoiceItemsById($id);
		$this->data['logo'] = true;
        $this->load->view($this->theme . 'sale_order/sale_order_thai_san', $this->data);
    }

    function tax_chales($id = NULL)
    {
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $deposit_so_id = $this->sales_model->getDeposit($id);
        $this->data['invs'] = $this->sales_model->getSaleinform($deposit_so_id->id);
        $this->load->model('pos_model');
        $this->data['pos'] = $this->pos_model->getSetting();
        $this->data['setting'] = $this->site->get_setting();
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $inv = $this->sales_model->getSaleOrder($id);
        //$this->erp->view_rights($inv->created_by, TRUE);
        $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
        $this->data['created_by'] = $this->site->getUser($inv->created_by);
        $this->data['updated_by'] = $inv->updated_by ? $this->site->getUser($inv->updated_by) : NULL;
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
        $this->data['inv'] = $inv;
        $return = $this->sales_model->getReturnBySID($id);
        $this->data['return_sale'] = $return;
        // $this->data['vattin'] = sales_model->gett($id);
        $this->data['rows'] = $this->sale_order_model->getAllInvoiceItemsById($id);
        // $this->erp->print_arrays($this->sale_order_model->getAllInvoiceItemsById($id));
        $this->data['logo'] = true;
        $this->load->view($this->theme . 'sale_order/tax_chales', $this->data);
    }
	
	function getCustomersByArea($area = NULL)
    {
        if ($rows = $this->sales_order_model->getCustomersByArea($area)) {
            $data = json_encode($rows);
        } else {
            $data = false;
        }
        echo $data;
    }
	
	function assign_to_user($sale_order_id=NULL)
	{
		
		$this->form_validation->set_rules('user_id', lang("user_id"), 'required');
		$this->form_validation->set_rules('so_num', lang("so_num"), 'required');
		
        if ($this->form_validation->run() == true) {
			
              $user_id = $this->input->post('user_id');
			  $so_id   = $this->input->post('so_num');
			  $this->sale_order_model->assign_to_user($user_id,$so_id);
			  redirect("sale_order/list_sale_order");
		}else{
			
		  	$this->erp->checkPermissions('index', TRUE);
			$this->data['AllUser']    = $this->Site->getAllUsers();
			$this->data['SO_NUM']     = $this->sale_order_model->getSaleOrder($sale_order_id);
			$this->load->view($this->theme . 'sale_order/assign_to_user', $this->data);
		}
	}
	
	/*===========================================chin local updated======================================*/
	function getSaleOrder($warehouse_id = NULL)
    {
        $warehouse_ids = explode('-', $warehouse_id);

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
        $down_payment       = null;
        $edit_down_payment  = null;
        $detail_link = anchor('sale_order/view/$1', '<i class="fa fa-file-text-o"></i> ' . lang('sale_order_details'));
		//$down_payment = anchor('sale_order/down_payment/$1', '<i class="fa fa-money"></i> ' . lang('create_loan'), '');
		//$edit_down_payment = anchor('sale_order/edit_down_payment/$1', '<i class="fa fa-money"></i> ' . lang('edit_loan'), '');
        $payments_link = anchor('sales/payments/$1', '<i class="fa fa-money"></i> ' . lang('view_payments'), 'data-toggle="modal" data-target="#myModal"');
        $add_deposit = anchor('customers/add_deposit/$2/$1', '<i class="fa fa-money"></i> ' . lang('add_deposit'),'data-toggle="modal" data-target="#myModal"');
        $view_deposit = anchor('customers/deposits/$2/$1', '<i class="fa fa-money"></i> ' . lang('view_deposit'),'data-toggle="modal" data-target="#myModal"');
        $add_sale_order = anchor('sales/add/$1', '<i class="fa fa-money"></i> ' . lang('add_sale'));
		$add_purchase_order = anchor('purchases/add_purchase_order/0/$1', '<i class="fa fa-money"></i> ' . lang('add_purchase_order'));
		$add_purchase = anchor('purchases/add/0/0/$1', '<i class="fa fa-money"></i> ' . lang('add_purchase'));
		$add_payment_link = anchor('sales/add_payment/$1', '<i class="fa fa-money"></i> ' . lang('add_payment'), 'data-toggle="modal" data-target="#myModal"');
        $add_delivery_link = anchor('sales/add_delivery/$1', '<i class="fa fa-truck"></i> ' . lang('add_delivery'), 'data-toggle="modal" data-target="#myModal"');
        $email_link = anchor('sales/email/$1', '<i class="fa fa-envelope"></i> ' . lang('email_sale'), 'data-toggle="modal" data-target="#myModal"');
        $edit_link = anchor('sale_order/edit_sale_order/$1', '<i class="fa fa-edit"></i> ' . lang('edit_sale_order'), 'class="sledit"');
        $pdf_link = anchor('sale_order/pdf/$1', '<i class="fa fa-file-pdf-o"></i> ' . lang('download_pdf'));
        $return_link = anchor('sales/return_sale/$1', '<i class="fa fa-angle-double-left"></i> ' . lang('return_sale'));
        $authorization = anchor('sale_order/getAuthorization/$1', '<i class="fa fa-check"></i> ' . lang('approved'), '');
		$unapproved = anchor('sale_order/getunapproved/$1', '<i class="fa fa-angle-double-left"></i> ' . lang('unapproved'), '');
		$rejected = anchor('sale_order/getrejected/$1', '<i class="fa fa-times"></i> ' . lang('rejected'), '');
        $delete_link = "<a href='#' class='po' title='<b>" . lang("delete_sale") . "</b>' data-content=\"<p>"
            . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('sale_order/deleteSaleOrder/$1') . "'>"
            . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i> "
            . lang('delete_sale') . "</a>";
        $action = '<div class="text-center"><div class="btn-group text-left">'
            . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
            . lang('actions') . ' <span class="caret"></span></button>
        <ul class="dropdown-menu pull-right" role="menu">
            <li>' . $detail_link . '</li>'
			.
			(($this->Owner || $this->Admin) ? '<li class="approved">'.$authorization.'</li>' : ($this->GP['sale_order-authorize'] ? '<li class="approved">'.$authorization.'</li>' : '')).
			(($this->Owner || $this->Admin) ? '<li class="unapproved">'.$unapproved.'</li>' : ($this->GP['sale_order-authorize'] ? '<li class="unapproved">'.$unapproved.'</li>' : '')).
			(($this->Owner || $this->Admin) ? '<li class="rejected">'.$rejected.'</li>' : ($this->GP['sale_order-authorize'] ? '<li class="rejected">'.$rejected.'</li>' : '')).
			
			(($this->Owner || $this->Admin) ? '<li class="down_payment">'.$down_payment.'</li>' : ($this->GP['sale_order-authorize'] ? '<li class="down_payment">'.$down_payment.'</li>' : '')).
			(($this->Owner || $this->Admin) ? '<li class="edit_down_payment">'.$edit_down_payment.'</li>' : ($this->GP['sale_order-authorize'] ? '<li class="edit_down_payment">'.$edit_down_payment.'</li>' : '')).
             (($this->Owner || $this->Admin) ? '<li class="adeposit">'.$add_deposit.'</li>' : ($this->GP['sale_order-deposit'] ? '<li class="adeposit">'.$add_deposit.'</li>' : '')).
             (($this->Owner || $this->Admin) ? '<li class="vdeposit">'.$view_deposit.'</li>' : ($this->GP['sale_order-deposit'] ? '<li class="vdeposit">'.$view_deposit.'</li>' : '')).
             (($this->Owner || $this->Admin) ? '<li class="add">'.$add_sale_order.'</li>' : ($this->GP['sale_order-add'] ? '<li class="add">'.$add_sale_order.'</li>' : '')).
			 (($this->Owner || $this->Admin) ? '<li class="add">'.$add_purchase_order.'</li>' : ($this->GP['sale_order-add'] ? '<li class="add">'.$add_purchase_order.'</li>' : '')).
			 (($this->Owner || $this->Admin) ? '<li class="add">'.$add_purchase.'</li>' : ($this->GP['sale_order-add'] ? '<li class="add">'.$add_purchase.'</li>' : '')).
             (($this->Owner || $this->Admin) ? '<li class="edit">'.$edit_link.'</li>' : ($this->GP['sale_order-edit'] ? '<li class="edit">'.$edit_link.'</li>' : '')).
			 (($this->Owner || $this->Admin) ? '<li>'.$pdf_link.'</li>' : ($this->GP['sale_order-export'] ? '<li>'.$pdf_link.'</li>' : '')).
		   
        '</ul>
		</div></div>';
        $biller_id = $this->session->userdata('biller_id');
        $biller_id = json_decode($biller_id);
        $this->load->library('datatables');
		
        if (isset($warehouse_id)) {
            $this->datatables
                       ->select("
                            sale_order.id,
                            sale_order.customer_id,
                            sale_order.date,
                            quotes.reference_no as qref,
                            sale_order.reference_no,
                            sale_order.biller,
                            IF(erp_companies.company = '', erp_companies.`name`, erp_companies.company) AS customer,
                            users.username as saleman,
                            sale_order.sale_status,
                            sale_order.grand_total,
                            COALESCE(SUM(erp_deposits.amount), 0) as deposit,
                            erp_sale_order.grand_total-COALESCE(SUM(erp_deposits.amount), 0) as balance,
                            sale_order.order_status,
							sale_order.attachment,
							join_lease_id,
							frequency
                        ")
				->from('sale_order')
				->join('companies', 'companies.id = sale_order.customer_id', 'left')
                ->join('users', 'users.id = sale_order.saleman_by', 'left')
				->join('users bill', 'bill.id = sale_order.created_by', 'left')
				->join('companies as delivery', 'delivery.id = sale_order.delivery_by', 'left')
				->join('deliveries', 'deliveries.sale_id = sale_order.id', 'left')
				->join('quotes', 'quotes.id = sale_order.quote_id', 'left')
                ->join('erp_deposits', 'erp_deposits.so_id = erp_sale_order.id', 'left')
                ->where_in('sale_order.biller_id', $biller_id)
				->group_by('sale_order.id');

                if (count($warehouse_ids) > 1) {
                    $this->datatables->where_in('sale_order.warehouse_id', $warehouse_ids);
                } else {
                    $this->datatables->where('sale_order.warehouse_id', $warehouse_id);
                }
				
        } else {
			
			$this->datatables
					->select("
                            sale_order.id,
                            sale_order.customer_id,
                            sale_order.date,
                            quotes.reference_no as qref,
                            sale_order.reference_no,
                            sale_order.biller,
                            IF(erp_companies.company = '', erp_companies.`name`, erp_companies.company) AS customer,
                            users.username as saleman,
                            sale_order.sale_status,
                            sale_order.grand_total,
                            COALESCE(SUM(erp_deposits.amount), 0) as deposit,
                            erp_sale_order.grand_total-COALESCE(SUM(erp_deposits.amount), 0) as balance,
                            sale_order.order_status,
                            sale_order.attachment,
							join_lease_id,
							frequency
                        ")
				->from('sale_order')
				->join('companies', 'companies.id = sale_order.customer_id', 'left')
				->join('users', 'users.id = sale_order.saleman_by', 'left')
				->join('companies as delivery', 'delivery.id = sale_order.delivery_by', 'left')
				->join('deliveries', 'deliveries.sale_id = sale_order.id', 'left')
				->join('quotes', 'quotes.id = sale_order.quote_id', 'left')
                ->join('erp_deposits', 'erp_deposits.so_id = erp_sale_order.id', 'left')
				->group_by('sale_order.id');

			if(isset($_REQUEST['d'])){
				$date = $_GET['d'];
				$date1 = str_replace("/", "-", $date);
				$date =  date('Y-m-d', strtotime($date1));
				
				$this->datatables
						->where("date >=", $date)
						->where('DATE_SUB(date, INTERVAL 1 DAY) <= CURDATE()')
						->where('sales.payment_term <>', 0);
			}
			
        }
		if ($product_id) {
			$this->datatables->join('sale_order_items', 'sale_order_items.sale_order_id = sale_order.id', 'left');
			$this->datatables->where('sale_order_items.product_id', $product_id);
		}
		
        $this->datatables->where('sale_order.pos !=', 1);
		
		
		if (!$this->Customer && !$this->Supplier && !$this->Owner && !$this->Admin && !$this->session->userdata('view_right')) {
            $this->datatables->where('sale_order.created_by', $this->session->userdata('user_id'));
        } elseif ($this->Customer) {
            $this->datatables->where('customer_id', $this->session->userdata('user_id'));
        }
		
		if ($user_query) {
			$this->datatables->where('sale_order.created_by', $user_query);
		}
		
		if ($reference_no) {
			$this->datatables->where('sale_order.reference_no', $reference_no);
		}
		if ($biller) {
			$this->datatables->where('sale_order.biller_id', $biller);
		}
		if ($customer) {
			$this->datatables->where('sale_order.customer_id', $customer);
		}
		
		if($saleman){
			$this->datatables->where('sale_order.saleman_by', $saleman);
		}
		
		if ($warehouse) {
			$this->datatables->where('sale_order.warehouse_id', $warehouse);
		}

		if ($start_date) {
			$this->datatables->where($this->db->dbprefix('sale_order').'.date BETWEEN "' . $start_date . ' 00:00:00" and "' . $end_date . ' 23:59:00"');
		}
		$this->datatables->add_column("Actions", $action, "sale_order.id, sale_order.customer_id");
        $this->datatables->unset_column('sale_order.customer_id');
        echo $this->datatables->generate();
    }
	
	function sale_order_alerts($warehouse_id = NULL)
	{  
		$this->load->model('reports_model');
        $this->data['warehouse_id'] = $warehouse_id;
		$this->data['users'] = $this->reports_model->getStaff();
        $this->data['warehouses'] = $this->site->getAllWarehouses();
        $this->data['billers'] = $this->site->getAllCompanies('biller');
        $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
		$bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('sale_order'), 'page' => lang('sale_order')), array('link' => '#', 'page' => lang('list_sale_order_alerts')));
		$meta = array('page_title' => lang('list_sale_order_alerts'), 'bc' => $bc);
		$this->page_construct('sale_order/list_sale_order_alerts', $meta, $this->data);
    }
	
	function getSaleOrderAlerts($warehouse_id = NULL)
    {
        $warehouse_ids = explode('-', $warehouse_id);

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
		/*
        if ((! $this->Owner || ! $this->Admin) && ! $warehouse_id) {
            $user = $this->site->getUser();
            $warehouse_id = $user->warehouse_id;
        }*/
		
        $detail_link = anchor('sale_order/view/$1', '<i class="fa fa-file-text-o"></i> ' . lang('sale_order_details'));
		// $view_document = anchor('sale_order/view_document/$1', '<i class="fa fa-chain"></i> ' . lang('view_document'), 'data-toggle="modal" data-target="#myModal"');
        $payments_link = anchor('sales/payments/$1', '<i class="fa fa-money"></i> ' . lang('view_payments'), 'data-toggle="modal" data-target="#myModal"');
        $add_deposit = anchor('customers/add_deposit/$2/$1', '<i class="fa fa-money"></i> ' . lang('add_deposit'),'data-toggle="modal" data-target="#myModal"');
        $view_deposit = anchor('customers/deposits/$2/$1', '<i class="fa fa-money"></i> ' . lang('view_deposit'),'data-toggle="modal" data-target="#myModal"');
        $add_sale_order = anchor('sales/add/$1', '<i class="fa fa-money"></i> ' . lang('add_sale'));
		$add_purchase_order = anchor('purchases/add_purchase_order/0/$1', '<i class="fa fa-money"></i> ' . lang('add_purchase_order'));
		$add_purchase = anchor('purchases/add/0/0/$1', '<i class="fa fa-money"></i> ' . lang('add_purchase'));
		$add_payment_link = anchor('sales/add_payment/$1', '<i class="fa fa-money"></i> ' . lang('add_payment'), 'data-toggle="modal" data-target="#myModal"');
        $add_delivery_link = anchor('sales/add_delivery/$1', '<i class="fa fa-truck"></i> ' . lang('add_delivery'), 'data-toggle="modal" data-target="#myModal"');
        $email_link = anchor('sales/email/$1', '<i class="fa fa-envelope"></i> ' . lang('email_sale'), 'data-toggle="modal" data-target="#myModal"');
        $edit_link = anchor('sale_order/edit_sale_order/$1', '<i class="fa fa-edit"></i> ' . lang('edit_sale_order'), 'class="sledit"');
        $pdf_link = anchor('sale_order/pdf/$1', '<i class="fa fa-file-pdf-o"></i> ' . lang('download_pdf'));
        $return_link = anchor('sales/return_sale/$1', '<i class="fa fa-angle-double-left"></i> ' . lang('return_sale'));
        $authorization = anchor('sale_order/getAuthorization/$1', '<i class="fa fa-check"></i> ' . lang('approved'), '');
		// $assign_to  = anchor('sale_order/assign_to_user/$1', '<i class="fa fa-check"></i> ' . lang('assign_to_user'),'data-toggle="modal" data-target="#myModal"');
		$unapproved = anchor('sale_order/getunapproved/$1', '<i class="fa fa-angle-double-left"></i> ' . lang('unapproved'), '');
		$rejected = anchor('sale_order/getrejected/$1', '<i class="fa fa-times"></i> ' . lang('rejected'), '');
        $delete_link = "<a href='#' class='po' title='<b>" . lang("delete_sale") . "</b>' data-content=\"<p>"
            . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('sale_order/deleteSaleOrder/$1') . "'>"
            . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i> "
            . lang('delete_sale') . "</a>";
        $action = '<div class="text-center"><div class="btn-group text-left">'
            . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
            . lang('actions') . ' <span class="caret"></span></button>
        <ul class="dropdown-menu pull-right" role="menu">
            <li>' . $detail_link . '</li>'
			. /*(($this->Owner || $this->Admin) ? '<li class="assign">'.$assign_to.'</li>':"") . */
			(($this->Owner || $this->Admin) ? '<li class="approved">'.$authorization.'</li>' : ($this->GP['sale_order-authorize'] ? '<li class="approved">'.$authorization.'</li>' : '')).
			(($this->Owner || $this->Admin) ? '<li class="unapproved">'.$unapproved.'</li>' : ($this->GP['sale_order-authorize'] ? '<li class="unapproved">'.$unapproved.'</li>' : '')).
			(($this->Owner || $this->Admin) ? '<li class="rejected">'.$rejected.'</li>' : ($this->GP['sale_order-authorize'] ? '<li class="rejected">'.$rejected.'</li>' : '')).
             (($this->Owner || $this->Admin) ? '<li class="adeposit">'.$add_deposit.'</li>' : ($this->GP['sale_order-deposit'] ? '<li class="adeposit">'.$add_deposit.'</li>' : '')).
             (($this->Owner || $this->Admin) ? '<li class="vdeposit">'.$view_deposit.'</li>' : ($this->GP['sale_order-deposit'] ? '<li class="vdeposit">'.$view_deposit.'</li>' : '')).
             (($this->Owner || $this->Admin) ? '<li class="add">'.$add_sale_order.'</li>' : ($this->GP['sale_order-add'] ? '<li class="add">'.$add_sale_order.'</li>' : '')).
			 (($this->Owner || $this->Admin) ? '<li class="add">'.$add_purchase_order.'</li>' : ($this->GP['sale_order-add'] ? '<li class="add">'.$add_purchase_order.'</li>' : '')).
			 (($this->Owner || $this->Admin) ? '<li class="add">'.$add_purchase.'</li>' : ($this->GP['sale_order-add'] ? '<li class="add">'.$add_purchase.'</li>' : '')).
             (($this->Owner || $this->Admin) ? '<li class="edit">'.$edit_link.'</li>' : ($this->GP['sale_order-edit'] ? '<li class="edit">'.$edit_link.'</li>' : '')).
			 (($this->Owner || $this->Admin) ? '<li>'.$pdf_link.'</li>' : ($this->GP['sale_order-export'] ? '<li>'.$pdf_link.'</li>' : '')).
			/*(($this->Owner || $this->Admin) ? '<li class="delete">'.$delete_link.'</li>' : ($this->GP['sale_order-delete'] ? '<li class="delete">'.$delete_link.'</li>' : '')).*/
           
        '</ul>
		</div></div>';
        //$action = '<div class="text-center">' . $detail_link . ' ' . $edit_link . ' ' . $email_link . ' ' . $delete_link . '</div>';
       // $permission = $this->site->getPermission();
        
       // echo $permission->product_edit;die();
        $biller_id = $this->session->userdata('biller_id');
        $this->load->library('datatables');
		
        if (isset($warehouse_id)) {
            $this->datatables
                //->select("sale_order.id, sale_order.customer_id, sale_order.date,quotes.reference_no as qref, sale_order.reference_no, sale_order.biller, companies.name as customer, users.username as saleman, sale_order.sale_status, sale_order.grand_total, sale_order.order_status")
                ->select("
                            sale_order.id,
                            sale_order.customer_id,
                            sale_order.date,
                            quotes.reference_no as qref,
                            sale_order.reference_no,
                            sale_order.biller,
                            IF(erp_companies.company = '', erp_companies.`name`, erp_companies.company) AS customer,
                            users.username as saleman,
                            sale_order.sale_status,
                            sale_order.grand_total,
                            COALESCE(SUM(erp_deposits.amount), 0) as deposit,
                            erp_sale_order.grand_total-COALESCE(SUM(erp_deposits.amount), 0) as balance,
                            sale_order.order_status
                        ")
				->from('sale_order')
				->join('companies', 'companies.id = sale_order.customer_id', 'left')
                ->join('users', 'users.id = sale_order.saleman_by', 'left')
				->join('users bill', 'bill.id = sale_order.created_by', 'left')
				->join('companies as delivery', 'delivery.id = sale_order.delivery_by', 'left')
				->join('deliveries', 'deliveries.sale_id = sale_order.id', 'left')
				->join('quotes', 'quotes.id = sale_order.quote_id', 'left')
                ->join('erp_deposits', 'erp_deposits.so_id = erp_sale_order.id', 'left')
				->where('sale_order.order_status','pending')
                ->where('sale_order.biller_id', $biller_id)
				->group_by('sale_order.id');

                if (count($warehouse_ids) > 1) {
                    $this->datatables->where_in('sale_order.warehouse_id', $warehouse_ids);
                } else {
                    $this->datatables->where('sale_order.warehouse_id', $warehouse_id);
                }
				
        } else {
			
			$this->datatables
				//->select("sale_order.id, sale_order.date, sale_order.reference_no, quotes.reference_no as qref, sale_order.biller, companies.name AS customer, users.username AS saleman,delivery.name as delivery_man,erp_sale_order.grand_total, sale_order.paid,(erp_sale_order.grand_total-erp_sale_order.paid) as balance, sale_order.sale_status")
				->select("
                            sale_order.id,
                            sale_order.customer_id,
                            sale_order.date,
                            quotes.reference_no as qref,
                            sale_order.reference_no,
                            sale_order.biller,
                            IF(erp_companies.company = '', erp_companies.`name`, erp_companies.company) AS customer,
                            users.username as saleman,
                            sale_order.sale_status,
                            sale_order.grand_total,
                            COALESCE(SUM(erp_deposits.amount), 0) as deposit,
                            erp_sale_order.grand_total-COALESCE(SUM(erp_deposits.amount), 0) as balance,
                            sale_order.order_status
                        ")
				->from('sale_order')
				->join('companies', 'companies.id = sale_order.customer_id', 'left')
				->join('users', 'users.id = sale_order.saleman_by', 'left')
				->join('companies as delivery', 'delivery.id = sale_order.delivery_by', 'left')
				->join('deliveries', 'deliveries.sale_id = sale_order.id', 'left')
				->join('quotes', 'quotes.id = sale_order.quote_id', 'left')
                ->join('erp_deposits', 'erp_deposits.so_id = erp_sale_order.id', 'left')
				->where('sale_order.order_status','pending')
				->group_by('sale_order.id');

			if(isset($_REQUEST['d'])){
				$date = $_GET['d'];
				$date1 = str_replace("/", "-", $date);
				$date =  date('Y-m-d', strtotime($date1));
				
				$this->datatables
						->where("date >=", $date)
						->where('DATE_SUB(date, INTERVAL 1 DAY) <= CURDATE()')
						->where('sales.payment_term <>', 0);
			}
			
        }
		if ($product_id) {
			$this->datatables->join('sale_order_items', 'sale_order_items.sale_order_id = sale_order.id', 'left');
			$this->datatables->where('sale_order_items.product_id', $product_id);
		}
		
        $this->datatables->where('sale_order.pos !=', 1);
		
		
		if (!$this->Customer && !$this->Supplier && !$this->Owner && !$this->Admin && !$this->session->userdata('view_right')) {
            $this->datatables->where('sale_order.created_by', $this->session->userdata('user_id'));
        } elseif ($this->Customer) {
            $this->datatables->where('customer_id', $this->session->userdata('user_id'));
        }
		
		if ($user_query) {
			$this->datatables->where('sale_order.created_by', $user_query);
		}
		
		if ($reference_no) {
			$this->datatables->where('sale_order.reference_no', $reference_no);
		}
		if ($biller) {
			$this->datatables->where('sale_order.biller_id', $biller);
		}
		if ($customer) {
			$this->datatables->where('sale_order.customer_id', $customer);
		}
		
		if($saleman){
			$this->datatables->where('sale_order.saleman_by', $saleman);
		}
		
		if ($warehouse) {
			$this->datatables->where('sale_order.warehouse_id', $warehouse);
		}

		if ($start_date) {
			$this->datatables->where($this->db->dbprefix('sale_order').'.date BETWEEN "' . $start_date . ' 00:00:00" and "' . $end_date . ' 23:59:00"');
		}
		
        // $this->datatables->add_column("Actions", $action, "sale_order.id");
        $this->datatables->add_column("Actions", $action, "sale_order.id, sale_order.customer_id");
        $this->datatables->unset_column('sale_order.customer_id');
        echo $this->datatables->generate();
    }
	
	function add_quote_sale_order($quote_id=null)
	{
		
        $this->form_validation->set_rules('biller', lang("biller"), 'required');
		$this->form_validation->set_rules('reference_no', lang("reference_no"), 'trim|is_unique[sales.reference_no]');

        if ($this->form_validation->run() == true) {
			
            $quantity = "quantity";
            $product = "product";
            $unit_cost = "unit_cost";
            $tax_rate = "tax_rate";

            $reference = $this->input->post('reference_no') ? $this->input->post('reference_no') : $this->site->getReference('so');

            if ($this->Owner || $this->Admin) {
                $date = $this->erp->fld($this->input->post('date'));
            } else {
                $date = date('Y-m-d H:i:s');
            }

            $warehouse_id = $this->input->post('warehouse');
            $customer_id = $this->input->post('customer_1');
			$amout_paid = $this->input->post('amount-paid');
			
            $biller_id = $this->input->post('biller');
			$saleman_by = $this->input->post('saleman');
            $total_items = $this->input->post('total_items');
            $payment_term = $this->input->post('payment_term');
            $due_date = $payment_term ? date('Y-m-d', strtotime('+' . $payment_term . ' days')) : NULL;
            $shipping = $this->input->post('shipping') ? $this->input->post('shipping') : 0;
            $sale_type = $this->input->post('purchase_type');
            $tax_type = $this->input->post('tax_type');
            $customer_details = $this->site->getCompanyByID($customer_id);
            $customer = $customer_details->company ? $customer_details->company : $customer_details->name;
            $biller_details = $this->site->getCompanyByID($biller_id);
            $biller = $biller_details->company != '-' ? $biller_details->company : $biller_details->name;
            $note = $this->erp->clear_tags($this->input->post('note'));
            $staff_note = $this->erp->clear_tags($this->input->post('staff_note'));
            
			$total = 0;
            $product_tax = 0;
            $order_tax = 0;
            $product_discount = 0;
            $order_discount = 0;
            $percentage = '%';
			$g_total_txt1 = 0;
			$loans = '';
			
            $i = isset($_POST['product_code']) ? sizeof($_POST['product_code']) : 0;
            for ($r = 0; $r < $i; $r++) {
                $item_id = $_POST['product_id'][$r];
                $item_type = $_POST['product_type'][$r];
                $item_code = $_POST['product_code'][$r];
				$item_note = $_POST['product_note'][$r];
                $item_name = $_POST['product_name'][$r];
				$item_group_price_id = $_POST['group_price_id'][$r];
                $item_option = isset($_POST['product_option'][$r]) && $_POST['product_option'][$r] != 'false' ? $_POST['product_option'][$r] : NULL;
                //$option_details = $this->sales_model->getProductOptionByID($item_option);
                $real_unit_price = $this->erp->formatDecimal($_POST['real_unit_price'][$r]);
                $unit_price = $this->erp->formatDecimal($_POST['unit_price'][$r]);
				$net_price = $this->erp->formatDecimal($_POST['net_price'][$r]);
                $item_quantity = $_POST['quantity'][$r];
				$item_unit_quantity = $_POST['quantity'][$r];
                $item_serial = isset($_POST['serial'][$r]) ? $_POST['serial'][$r] : '';
                $item_tax_rate = isset($_POST['product_tax'][$r]) ? $_POST['product_tax'][$r] : NULL;
                $item_discount = isset($_POST['product_discount'][$r]) ? $_POST['product_discount'][$r] : NULL;
                
                //$g_total_txt = $_POST['grand_total'][$r];
				
				if (isset($item_code) && isset($real_unit_price) && isset($unit_price) && isset($item_quantity)) {
                    $product_details = $item_type != 'manual' ? $this->sales_model->getProductByCode($item_code) : NULL;
                    $unit_price = $real_unit_price;
                    $pr_discount = 0;

					if (isset($item_discount)) {
                        $discount = $item_discount;
                        $dpos = strpos($discount, $percentage);
                        if ($dpos !== false) {
                            $pds = explode("%", $discount);
                            $pr_discount = $this->erp->formatDecimal(((($this->erp->formatDecimal($unit_price)) * (Float) ($pds[0])) / 100), 4);
                        } else {
                            $pr_discount = $this->erp->formatDecimal($discount);
                        }
                    }
                    
                    $unit_price = $this->erp->formatDecimal($unit_price - $pr_discount, 4);
                    $item_net_price = $unit_price;
                    $pr_item_discount = $this->erp->formatDecimal($pr_discount * $item_unit_quantity);
                    $product_discount += $pr_item_discount;
                    $pr_tax = 0; $pr_item_tax = 0; $item_tax = 0; $tax = "";

                    if (isset($item_tax_rate) && $item_tax_rate != 0) {
                        $pr_tax = $item_tax_rate;
                        $tax_details = $this->site->getTaxRateByID($pr_tax);
                        if ($tax_details->type == 1 && $tax_details->rate != 0) {
                            if ($product_details && $product_details->tax_method == 1) {
                                $item_tax = $this->erp->formatDecimal((($unit_price) * $tax_details->rate) / 100, 4);
                                $tax = $tax_details->rate . "%";
                            } else {
                                $item_tax = $this->erp->formatDecimal((($unit_price) * $tax_details->rate) / (100 + $tax_details->rate), 4);
								//$item_tax = $this->erp->formatDecimal(($unit_price) * ($tax_details->rate / 100), 4);
                                $tax = $tax_details->rate . "%";
                                 $item_net_price = $unit_price - $item_tax;
                            }
                        } elseif ($tax_details->type == 2) {

                            if ($product_details && $product_details->tax_method == 1) {
                                $item_tax = $this->erp->formatDecimal((($unit_price) * $tax_details->rate) / 100, 4);
                                $tax = $tax_details->rate . "%";
                            } else {
                                $item_tax = $this->erp->formatDecimal((($unit_price) * $tax_details->rate) / (100 + $tax_details->rate), 4);
                                $tax = $tax_details->rate . "%";
                                $item_net_price = $unit_price - $item_tax;
                            }
                            $item_tax = $this->erp->formatDecimal($tax_details->rate);
                            $tax = $tax_details->rate;
                        }
                        $pr_item_tax = $this->erp->formatDecimal($item_tax * $item_unit_quantity, 4);
                    }

                    $product_tax += $pr_item_tax;
                    $subtotal = (($item_net_price * $item_unit_quantity) + $pr_item_tax);
					
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
                        'serial_no' => $item_serial,
                        'real_unit_price' => $real_unit_price,
						'product_noted' => $item_note,
						'group_price_id' => $item_group_price_id
                    );
					$total += $this->erp->formatDecimal(($item_net_price* $item_unit_quantity), 4);
                }
            }
			
            if (empty($products)) {
                $this->form_validation->set_rules('product', lang("order_items"), 'required');
            } else {
                krsort($products);
            }

            if ($this->input->post('order_discount')) {
                $order_discount_id = $this->input->post('order_discount');
                $opos = strpos($order_discount_id, $percentage);
                if ($opos !== false) {
                    $ods = explode("%", $order_discount_id);
                    $order_discount = $this->erp->formatDecimal(((($total + $product_tax) * (Float) ($ods[0])) / 100), 4);
                } else {
                    $order_discount = $this->erp->formatDecimal($order_discount_id);
                }
            } else {
                $order_discount_id = null;
            }
            $total_discount = $this->erp->formatDecimal($order_discount + $product_discount);
            //echo $this->erp->floorFigure($product_discount);die();
            if ($this->Settings->tax2) {
                $order_tax_id = $this->input->post('order_tax');
                if ($order_tax_details = $this->site->getTaxRateByID($order_tax_id)) {
                    if ($order_tax_details->type == 2) {
                        $order_tax = $this->erp->formatDecimal($order_tax_details->rate);
                    } elseif ($order_tax_details->type == 1) {
                        $order_tax = $this->erp->formatDecimal(((($shipping + $total + $product_tax - $order_discount ) * $order_tax_details->rate) / 100), 4);
                    }
                }
            } else {
                $order_tax_id = null;
            }
			
            $total_tax = $this->erp->formatDecimal(($product_tax + $order_tax), 4); 
			$total_balance=$this->erp->formatDecimal(($total + $total_tax + $this->erp->formatDecimal($shipping) - $order_discount), 4);
			if($sale_type==1 && $total_tax==0){
					$total_tax= ($total_balance/1.1)*(0.1);	
			}	
            $grand_total = $this->erp->formatDecimal(($total + $order_tax + $this->erp->formatDecimal($shipping) - $order_discount), 4);
			$photo = "";
			$photo1 = "";
			$photo2 = "";
			
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
                $photo1 = $this->upload->file_name;
               
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
                $photo2 = $this->upload->file_name;
               
            }
			
			$data = array('date' => $date,
                'reference_no' => $reference,
                'customer_id' => $customer_id,
                'customer' => $customer,
                'biller_id' => $biller_id,
                'biller' => $biller,
                'warehouse_id' => $warehouse_id,
                'note' => $note,
                'staff_note' => $staff_note,
                'total' => $this->erp->formatDecimal($total),
                'product_discount' => $this->erp->formatDecimal($product_discount),
                'order_discount_id' => $order_discount_id,
                'order_discount' => $order_discount,
                'total_discount' => $total_discount,
                'product_tax' => $this->erp->formatDecimal($product_tax),
                'order_tax_id' => $order_tax_id,
                'order_tax' => $order_tax,
                'total_tax' => $total_tax,
                'shipping' => $this->erp->formatDecimal($shipping),
                'grand_total' => $grand_total,
                'total_items' => $total_items,
                'payment_term' => $payment_term,
                'due_date' => $due_date,
                'paid' => ($amout_paid != '' || $amout_paid != 0 || $amout_paid != null)? $amout_paid : 0,
                'created_by' => $this->session->userdata('user_id'),
				'saleman_by' => $saleman_by,
				'sale_type' => $sale_type,
				'tax_type' => $tax_type,
				'attachment' => $photo,
				'attachment1' => $photo1,
				'attachment2' => $photo2
				
            );
			
			//$this->erp->print_arrays($data, $products);
        }
		
        if ($this->form_validation->run() == true) {
			
			$paid_by = $this->input->post('paid_by');
			$amount_paid = floatval(preg_replace("/[^0-9\.]/i", "", $amout_paid));
			$sale_order_id = $this->sale_order_model->addSaleOrder($data, $products);
			if($sale_order_id>0){
				//add deposit
				$deposits = array(
				'date' => $date,
				'company_id' => $customer_id,
				'amount' => $amount_paid - (2*$amount_paid),
				'paid_by' => $paid_by,
				'note' => $note,
				'created_by' => $this->session->userdata('user_id'),
				'biller_id' => $biller_id,
				'so_id' => $sale_order_id
			);
			
				if($this->sale_order_model->add_deposit($deposits,$sale_order_id)){					
					$this->session->set_flashdata('message', lang("sale_order_added"));
					redirect("sale_order/add_sale_order");
				}
			
			}
			
        } else {
			
			if($quote_id){
				$this->data['quote'] = $this->quotes_model->getQuoteByID($quote_id);
				//$this->erp->print_arrays($this->quotes_model->getQuoteByID($quote_id));
				
				$quote_items = $this->quotes_model->getQuoteItemByID($quote_id);
				$this->data['quote_id'] = $quote_id;
				
				$c = rand(100000, 9999999);
			
				foreach ($quote_items as $item) {
				
                $row = $this->site->getProductByID($item->product_id);
				//$this->erp->print_arrays($this->site->getProductByID($item->product_id));				
                if (!$row) {
                    $row = json_decode('{}');
                    $row->tax_method = 0;
                    $row->quantity = 0;
                } else {
                    unset($row->details, $row->product_details, $row->cost, $row->supplier1price, $row->supplier2price, $row->supplier3price, $row->supplier4price, $row->supplier5price);
                }
				
                $pis = $this->sales_model->getPurchasedItems($item->product_id, $item->warehouse_id, $item->option_id);
				
				if($pis){
                    foreach ($pis as $pi) {
                        $row->quantity += $pi->quantity_balance;
                    }
                }
				
                $row->id = $item->product_id;
                $row->code = $item->product_code;
                $row->name = $item->product_name;
                $row->type = $item->product_type;
                $row->qty = $item->quantity;
                $row->quantity += $item->quantity;
				$row->cost += $item->cost;
                $row->discount = $item->discount ? $item->discount : '0';
                $row->price = $this->erp->formatDecimal($item->net_unit_price+$this->erp->formatDecimal($item->item_discount/$item->quantity));
                $row->unit_price = $row->tax_method ? $item->unit_price+$this->erp->formatDecimal($item->item_discount/$item->quantity)+$this->erp->formatDecimal($item->item_tax/$item->quantity) : $item->unit_price+($item->item_discount/$item->quantity);
                $row->real_unit_price = $item->real_unit_price;
                $row->tax_rate = $item->tax_rate_id;
                $row->serial = $item->serial_no;
                $row->option = $item->option_id;
				$row->unit = $row->unit;
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
                        $option_quantity += $item->quantity;
                        if($option->quantity > $option_quantity) {
                            $option->quantity = $option_quantity;
                        }
                    }
                }

                $combo_items = FALSE;
                if ($row->type == 'combo') {
                    $combo_items = $this->sales_model->getProductComboItems($row->id, $item->warehouse_id);
                    $te = $combo_items;
                    foreach ($combo_items as $combo_item) {
                        $combo_item->quantity =  $combo_item->qty*$item->quantity;
                    }
                }
				
                $ri = $this->Settings->item_addition ? $row->id : $c;
                if ($row->tax_rate) {
                    $tax_rate = $this->site->getTaxRateByID($row->tax_rate);
                    $pr[$ri] = array('id' => $c, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'combo_items' => $combo_items, 'tax_rate' => $tax_rate, 'options' => $options, 'makeup_cost' => 0);
                } else {
                    $pr[$ri] = array('id' => $c, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'combo_items' => $combo_items, 'tax_rate' => false, 'options' => $options, 'makeup_cost' => 0);
                }
                $c++;
				
				}
				//$this->erp->print_arrays($pr);die(); 
				$this->data['quote_items'] = json_encode($pr);
				
				//$this->erp->print_arrays($this->quotes_model->getQuoteItemByID($quote_id));
			}
			
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['billers'] = $this->site->getAllCompanies('biller');
            $this->data['warehouses'] = $this->site->getAllWarehouses();
			
			//$this->erp->print_arrays($this->site->getAllWarehouses());
            $this->data['tax_rates'] = $this->site->getAllTaxRates();
			$this->data['agencies'] = $this->site->getAllUsers();
			$this->data['customers'] = $this->site->getCustomers();
			$this->data['currency'] = $this->site->getCurrency();
			$this->data['drivers'] = $this->site->getDriverByGroupId(); //$this->erp->print_arrays($this->site->getDriverByGroupId());
			$this->data['payment_term'] = $this->site->getAllPaymentTerm();
			
            //$this->data['currencies'] = $this->sales_model->getAllCurrencies();
            $this->data['slnumber'] = ''; //$this->site->getReference('so');
            $this->data['payment_ref'] = $this->site->getReference('sp');
            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('sale_order'), 'page' => lang('sale_order')), array('link' => '#', 'page' => lang('add_sale_order')));
            $meta = array('page_title' => lang('add_sale_order'), 'bc' => $bc);
			//$this->erp->print_arrays($this->data);
            $this->page_construct('sale_order/add_sale_order', $meta, $this->data);
        }		
	}
	/*=====================================end local updated======================================*/
	function sale_order_actions($wh=null)
    {
        /*if (!$this->Owner) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER["HTTP_REFERER"]);
        }*/
        if($wh) {
            $wh = explode('-', $wh);
        }
        $this->form_validation->set_rules('form_action', lang("form_action"), 'required');

        if ($this->form_validation->run() == true) {

            if (!empty($_POST['val'])) {
                if ($this->input->post('form_action') == 'delete') {
                    foreach ($_POST['val'] as $id) {
                        $this->sales_model->deleteSale($id);
                    }
					$this->session->set_flashdata('message', lang('sale_deleted'));
					redirect($_SERVER["HTTP_REFERER"]);
                }

                if ($this->input->post('form_action') == 'combine') {
                    $html = $this->combine_pdf($_POST['val']);
                }

                if ($this->input->post('form_action') == 'export_excel' || $this->input->post('form_action') == 'export_pdf') {
                    if($this->Owner || $this->Admin){
                    $this->load->library('excel');
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle(lang('sale_order'));
                    $this->excel->getActiveSheet()->SetCellValue('A1', lang('date'));
                    $this->excel->getActiveSheet()->SetCellValue('B1', lang('quotation_no'));
                    $this->excel->getActiveSheet()->SetCellValue('C1', lang('so_no'));
                    $this->excel->getActiveSheet()->SetCellValue('D1', lang('project'));
                    $this->excel->getActiveSheet()->SetCellValue('E1', lang('customer'));
                    $this->excel->getActiveSheet()->SetCellValue('F1', lang('saleman'));
                    $this->excel->getActiveSheet()->SetCellValue('G1', lang('order_status'));
                    $this->excel->getActiveSheet()->SetCellValue('H1', lang('grand_total'));
                    $this->excel->getActiveSheet()->SetCellValue('I1', lang('deposit'));
                    $this->excel->getActiveSheet()->SetCellValue('J1', lang('balance'));
                    $this->excel->getActiveSheet()->SetCellValue('K1', lang('status'));

                    $row = 2;
                    $sum_grand = $sum_deposit = $sum_balance = 0;
					if ($_POST['val'] == true) {
						foreach ($_POST['val'] as $id) {
							$sale = $this->sale_order_model->getInvoiceByID($id);
                            $sum_grand += $sale->grand_total;
                            $sum_deposit += $sale->deposit;
                            $sum_balance += $sale->balance;
                            $this->excel->getActiveSheet()->SetCellValue('A' . $row, $this->erp->hrld($sale->date));
                            $this->excel->getActiveSheet()->SetCellValue('B' . $row, $sale->quotation_no." ");
                            $this->excel->getActiveSheet()->SetCellValue('C' . $row, $sale->reference_no." ");
                            $this->excel->getActiveSheet()->SetCellValue('D' . $row, $sale->biller);
                            $this->excel->getActiveSheet()->SetCellValue('E' . $row, $sale->customer);
                            $this->excel->getActiveSheet()->SetCellValue('F' . $row, $sale->saleman);
                            $this->excel->getActiveSheet()->SetCellValue('G' . $row, $sale->sale_status);
                            $this->excel->getActiveSheet()->SetCellValue('H' . $row, $sale->grand_total);
                            $this->excel->getActiveSheet()->SetCellValue('I' . $row, $sale->deposit);
                            $this->excel->getActiveSheet()->SetCellValue('J' . $row, $sale->balance);
                            $this->excel->getActiveSheet()->SetCellValue('K' . $row, $sale->status);
                            $new_row = $row+1;
                            $this->excel->getActiveSheet()->SetCellValue('H' . $new_row, $sum_grand);
                            $this->excel->getActiveSheet()->SetCellValue('I' . $new_row, $sum_deposit);
                            $this->excel->getActiveSheet()->SetCellValue('J' . $new_row, $sum_balance);
                            $row++;
						}
                        
					}
                }else{
                    // echo "user";exit();
                    $this->load->library('excel');
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle(lang('sale_order'));
                    $this->excel->getActiveSheet()->SetCellValue('A1', lang('date'));
                    $this->excel->getActiveSheet()->SetCellValue('B1', lang('quotation_no'));
                    $this->excel->getActiveSheet()->SetCellValue('C1', lang('so_no'));
                    $this->excel->getActiveSheet()->SetCellValue('D1', lang('project'));
                    $this->excel->getActiveSheet()->SetCellValue('E1', lang('customer'));
                    $this->excel->getActiveSheet()->SetCellValue('F1', lang('saleman'));
                    $this->excel->getActiveSheet()->SetCellValue('G1', lang('order_status'));
                    $this->excel->getActiveSheet()->SetCellValue('H1', lang('grand_total'));
                    $this->excel->getActiveSheet()->SetCellValue('I1', lang('deposit'));
                    $this->excel->getActiveSheet()->SetCellValue('J1', lang('balance'));
                    $this->excel->getActiveSheet()->SetCellValue('K1', lang('status'));
                    
                    $user_row = 2;
                    $sum_grand = $sum_deposit = $sum_balance = 0;
                    if ($_POST['val'] == true) {
                        foreach ($_POST['val'] as $id) {
                            $sale = $this->sale_order_model->getInvoiceByID($id,$wh);
                            $sum_grand += $sale->grand_total;
                            $sum_deposit += $sale->deposit;
                            $sum_balance += $sale->balance;
                            $this->excel->getActiveSheet()->SetCellValue('A' . $user_row, $this->erp->hrld($sale->date));
                            $this->excel->getActiveSheet()->SetCellValue('B' . $user_row, $sale->quotation_no." ");
                            $this->excel->getActiveSheet()->SetCellValue('C' . $user_row, $sale->reference_no." ");
                            $this->excel->getActiveSheet()->SetCellValue('D' . $user_row, $sale->biller);
                            $this->excel->getActiveSheet()->SetCellValue('E' . $user_row, $sale->customer);
                            $this->excel->getActiveSheet()->SetCellValue('F' . $user_row, $sale->saleman);
                            $this->excel->getActiveSheet()->SetCellValue('G' . $user_row, $sale->sale_status);
                            $this->excel->getActiveSheet()->SetCellValue('H' . $user_row, $sale->grand_total);
                            $this->excel->getActiveSheet()->SetCellValue('I' . $user_row, $sale->deposit);
                            $this->excel->getActiveSheet()->SetCellValue('J' . $user_row, $sale->balance);
                            $this->excel->getActiveSheet()->SetCellValue('K' . $user_row, $sale->status);
                            $new_row = $user_row+1;
                            $this->excel->getActiveSheet()->SetCellValue('H' . $new_row, $sum_grand);
                            $this->excel->getActiveSheet()->SetCellValue('I' . $new_row, $sum_deposit);
                            $this->excel->getActiveSheet()->SetCellValue('J' . $new_row, $sum_balance);
                            $user_row++;
                        }
                        
                    }
                }

                    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(17);
                    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(17);
                    $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
                    $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
                    $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(12);
                    $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(12);
                    $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(12);
                    $this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(10);
                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $filename = 'sale_order_' . date('Y_m_d_H_i_s');
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
                        $this->excel->getActiveSheet()->getStyle('A1:K1')->applyFromArray($styleArray);
                        $this->excel->getActiveSheet()->getStyle('A1:K1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $this->excel->getActiveSheet()->getStyle('H' . $new_row.'')->getFont()->setBold(true);
                        $this->excel->getActiveSheet()->getStyle('I' . $new_row.'')->getFont()->setBold(true);
                        $this->excel->getActiveSheet()->getStyle('J' . $new_row.'')->getFont()->setBold(true);

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
                        $this->excel->getActiveSheet()->getStyle('A1:K1')->applyFromArray($styleArray);
                        $this->excel->getActiveSheet()->getStyle('A1:K1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $this->excel->getActiveSheet()->getStyle('H' . $new_row.'')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border:: BORDER_THIN);
                        $this->excel->getActiveSheet()->getStyle('H' . $new_row.'')->getFont()->setBold(true);
                        $this->excel->getActiveSheet()->getStyle('I' . $new_row.'')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border:: BORDER_THIN);
                        $this->excel->getActiveSheet()->getStyle('I' . $new_row.'')->getFont()->setBold(true);
                        $this->excel->getActiveSheet()->getStyle('J' . $new_row.'')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border:: BORDER_THIN);
                        $this->excel->getActiveSheet()->getStyle('J' . $new_row.'')->getFont()->setBold(true);

                        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
                        return $objWriter->save('php://output');
                    }

                    redirect($_SERVER["HTTP_REFERER"]);
                }
            }
			else {
                $this->session->set_flashdata('error', lang("no_sale_selected"));
                redirect($_SERVER["HTTP_REFERER"]);
            }
			
			/*
			// export to excel when no select
			if(empty($_POST['val'])){
				if ($this->input->post('form_action') == 'export_excel' || $this->input->post('form_action') == 'export_pdf') {

                    $this->load->library('excel');
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle(lang('sale_order'));
                    $this->excel->getActiveSheet()->SetCellValue('A1', lang('date'));
                    $this->excel->getActiveSheet()->SetCellValue('B1', lang('reference_no'));
                    $this->excel->getActiveSheet()->SetCellValue('C1', lang('shop'));
                    $this->excel->getActiveSheet()->SetCellValue('D1', lang('customer'));
					$this->excel->getActiveSheet()->SetCellValue('E1', lang('saleman'));
					$this->excel->getActiveSheet()->SetCellValue('F1', lang('driver'));
                    $this->excel->getActiveSheet()->SetCellValue('G1', lang('grand_total'));
                    $this->excel->getActiveSheet()->SetCellValue('H1', lang('deposit'));
					$this->excel->getActiveSheet()->SetCellValue('I1', lang('balance'));

                    $row = 2;
					$sales = $this->sale_order_model->getInvoice();
                    foreach ($sales as $sale) {
                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $this->erp->hrld($sale->date));
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $sale->reference_no);
                        $this->excel->getActiveSheet()->SetCellValue('C' . $row, $sale->biller);
                        $this->excel->getActiveSheet()->SetCellValue('D' . $row, $sale->customer);
						$this->excel->getActiveSheet()->SetCellValue('E' . $row, $sale->saleman);
						$this->excel->getActiveSheet()->SetCellValue('F' . $row, $sale->delivery_man);
                        $this->excel->getActiveSheet()->SetCellValue('G' . $row, $sale->grand_total);
                        $this->excel->getActiveSheet()->SetCellValue('H' . $row, $sale->paid);
						$this->excel->getActiveSheet()->SetCellValue('I' . $row, $sale->balance);
                        $row++;
                    }

                    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $filename = 'sale_order_' . date('Y_m_d_H_i_s');
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
			}
			*/
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }
	
	function deleteSaleOrder($sale_order_id = null){
		$this->erp->checkPermissions('delete',null,'sale_order');
		if($this->sale_order_model->deleteSaleOrderByID($sale_order_id)){
			$this->session->set_flashdata('message', lang("sale_order_deleted"));
			redirect("sale_order/list_sale_order");
			return false;
		}else{
			redirect($_SERVER["HTTP_REFERER"]);
		}
	}
	
	function invoice_old($id = NULL)
    {
		$this->erp->checkPermissions('index');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
		
		$this->load->model('pos_model');
		$this->data['pos'] = $this->pos_model->getSetting();
		
		$this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        
		$inv = $this->sales_model->getSaleOrderInvoice($id);
        $this->erp->view_rights($inv->created_by, TRUE);
        $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
        $this->data['created_by'] = $this->site->getUser($inv->created_by);
		$this->data['seller'] = $this->site->getUser($inv->saleman_by);
        $this->data['updated_by'] = $inv->updated_by ? $this->site->getUser($inv->updated_by) : NULL;
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
        $this->data['inv'] = $inv;
		$this->data['vattin'] = $this->site->getTaxRateByID($inv->order_tax_id);
        $return = $this->sales_model->getReturnBySID($id);
        $this->data['return_sale'] = $return;
        $this->data['rows'] = $this->sale_order_model->getSaleOrderItemsDetail($id);
		//$this->erp->print_arrays($this->data['rows']);
		$this->data['logo'] = true;
		$this->data['modal_js'] = $this->site->modal_js();
        $this->load->view($this->theme . 'sale_order/invoice', $this->data);
    }
	
	function invoice($id = NULL)
    {
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
        $this->load->model('pos_model');
        $this->data['pos'] = $this->pos_model->getSetting();
        
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        
        $inv = $this->sales_model->getSaleOrderInvoice($id);
        // $this->erp->view_rights($inv->created_by, TRUE);
        $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
        $this->data['created_by'] = $this->site->getUser($inv->created_by);
        $this->data['seller'] = $this->site->getUser($inv->saleman_by);
        $this->data['updated_by'] = $inv->updated_by ? $this->site->getUser($inv->updated_by) : NULL;
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
        $this->data['inv'] = $inv;
        $this->data['vattin'] = $this->site->getTaxRateByID($inv->order_tax_id);
        $return = $this->sales_model->getReturnBySID($id);
        $this->data['return_sale'] = $return;
        $this->data['rows'] = $this->sales_model->getSaleOrdItemsDetail($id);
        $this->data['logo'] = true;
        $this->data['modal_js'] = $this->site->modal_js();
        $this->load->view($this->theme . 'sale_order/invoice', $this->data);
    }
	function flora($id = NULL)
	{
		$months = array(
			'01' => 'មករា',
			'02' => 'កុម្ភះ',
			'03' => 'មិនា',
			'04' => 'មេសា',
			'05' => 'ឧសភា',
			'06' => 'មិថុនា',
			'07' => 'កក្កដា',
			'08' => 'សីហា',
			'09' => 'កញ្ញា',
			'10' => 'តុលា',
			'11' => 'វិច្ឆិកា',
			'12' => 'ធ្នូ',
		);
		if ($this->input->get('id')) {
			$id = $this->input->get('id');
		}
		
		$this->load->model('pos_model');
		$this->data['pos'] = $this->pos_model->getSetting();
		
		$this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
		$row = $this->sales_model->getSaleOrdItemsDetailByID($id);
		$home_s = $row->cf5;
		$size = explode("x", $home_s);
		$this->data['a'] = $size[0];
		$this->data['b'] = $size[1];
		$this->data['rows'] = $row;		
		$inv = $this->sale_order_model->getInvoiceByID($id);
		//$this->erp->print_arrays($inv);
		$datetime = $inv->date;
		$this->data['fullday']=$inv->date;
		$date_arr= explode(" ", $datetime);
		$date= $date_arr[0];
		$date_ex = explode("-", $date);
		$this->data['date_year'] = $date_ex[0];
		$month = $date_ex[1];
		$this->data['date_month'] = $date_ex[1];
		$this->data['date_day'] = $date_ex[2];
		//get size of home
		$this->data['product'] = $this->sale_order_model->getProductSaleOrder($id);
		$wid_height = $this->sale_order_model->getProductSaleOrder($id);
		$width_height = split (" x ", $wid_height->cf5);
		$this->data['height'] = $width_height[1];
		$this->data['width'] = $width_height[0];
		
		$this->erp->view_rights($inv->created_by, TRUE);
		$this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
		$this->data['jl_data'] = $this->sale_order_model->getJoinlease($id);
		$get_d_m_y = $this->sale_order_model->getJoinlease($id);
		$jl_data_date = explode("-", $get_d_m_y->date_of_birth);
		$this->data['jl_year'] = $jl_data_date[0];
		$m_index = $jl_data_date[1];
		$this->data['jl_month'] = $months[$m_index];
		$this->data['jl_date'] = $jl_data_date[2];
		
		$this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
		$this->data['created_by'] = $this->site->getUser($inv->created_by);
		$this->data['seller'] = $this->site->getUser($inv->saleman_by);
		$this->data['updated_by'] = $inv->updated_by ? $this->site->getUser($inv->updated_by) : NULL;
		$this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
		$this->data['inv'] = $inv;
		$this->data['vattin'] = $this->site->getTaxRateByID($inv->order_tax_id);
		$return = $this->sales_model->getReturnBySID($id);
		$this->data['return_sale'] = $return;
		$this->data['loan'] = $this->sale_order_model->getLoanBySaleId($id);
		$this->data['logo'] = true;
		$this->data['modal_js'] = $this->site->modal_js();
		$this->load->view($this->theme . 'sale_order/print_flora', $this->data);
	}
    function invoice_order($id=null) {
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
        $this->load->model('pos_model');
        $this->data['pos'] = $this->pos_model->getSetting();
        
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        
        $inv = $this->sales_model->getSaleOrderInvoice($id);
        // $this->erp->view_rights($inv->created_by, TRUE);
        $this->data['customer'] = $this->site->getCompanyByIDorderCus($inv->customer_id);
        $this->data['biller'] = $this->site->getCompanyByIDorder($inv->biller_id);
        $this->data['created_by'] = $this->site->getUser($inv->created_by);
        $this->data['seller'] = $this->site->getUser($inv->saleman_by);
        $this->data['updated_by'] = $inv->updated_by ? $this->site->getUser($inv->updated_by) : NULL;
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
        $this->data['quote'] = $this->site->getQuoteByID($inv->id);
        $this->data['inv'] = $inv;
		$this->data['setting'] = $this->site->get_setting();
        $this->data['vattin'] = $this->site->getTaxRateByID($inv->order_tax_id);
        $return = $this->sales_model->getReturnBySID($id);
        $this->data['return_sale'] = $return;
        $this->data['rows'] = $this->sale_order_model->getAllInvoiceItems($id);
        $this->data['logo'] = true;
        $this->data['modal_js'] = $this->site->modal_js();
        $this->load->view($this->theme . 'sale_order/order_', $this->data);
    }
	
	
	/*===================================chin local updated===========================================*/
	function edit_sale_order($id = NULL)
    {
		$this->erp->checkPermissions('edit', null, 'sale_order');
		
		if(($this->sale_order_model->getSaleOrder($id)->order_status) == 'completed'){
			$this->session->set_flashdata('error', lang("sale_order_p_approved"));
            redirect($_SERVER["HTTP_REFERER"]);
		}

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        $this->form_validation->set_message('is_natural_no_zero', lang("no_zero_required"));
        $this->form_validation->set_rules('reference_no', lang("reference_no"), 'required');
        $this->form_validation->set_rules('customer', lang("customer"), 'required');
        $this->form_validation->set_rules('biller', lang("biller"), 'required');

        if ($this->form_validation->run() == true) {
			
			if ($this->Owner || $this->Admin || $this->Settings->allow_change_date == 1) {
                $date = $this->erp->fld($this->input->post('date'));
            } else {
                $date = date('Y-m-d H:i:s');
            }
            
            $quantity = "quantity";
            $product = "product";
            $unit_cost = "unit_cost";
            $tax_rate = "tax_rate";
            $reference = $this->input->post('reference_no');
            $delivery_date = $this->erp->fld($this->input->post('delivery_date'));
            $warehouse_id = $this->input->post('warehouse');
            $customer_id = $this->input->post('customer');
			$group_area = $this->input->post('area');
            $biller_id = $this->input->post('biller');
			$saleman_by = $this->input->post('saleman');
            $total_items = $this->input->post('total_items');
			$payment_status = 'due';
            $payment_term = $this->input->post('payment_term');
			$delivery_by = $this->input->post('delivery_by');
			$delivery_id = $this->input->post('delivery_id');
            $due_date = $payment_term ? date('Y-m-d', strtotime('+' . $payment_term . ' days')) : NULL;
            $shipping = $this->input->post('shipping') ? $this->input->post('shipping') : 0;
            $customer_details = $this->site->getCompanyByID($customer_id);
            $customer = $customer_details->company ? $customer_details->company : $customer_details->name;
            $biller_details = $this->site->getCompanyByID($biller_id);
            $biller = $biller_details->company != '-' ? $biller_details->company : $biller_details->name;
            $note = $this->input->post('note');
            $staff_note = $this->input->post('staff_note');
			
			$amout_paid = $this->input->post('amount-paid');

            $total = 0;
            $product_tax = 0;
            $order_tax = 0;
            $product_discount = 0;
            $order_discount = 0;
			$ord_qty = 0;
			$rec_qty = 0;
            $percentage = '%';
            $i = isset($_POST['product_code']) ? sizeof($_POST['product_code']) : 0;
            for ($r = 0; $r < $i; $r++) {
                $item_id = $_POST['product_id'][$r];
                $digital_id = $_POST['digital_id'][$r];
                $item_type = $_POST['product_type'][$r];
                $item_code = $_POST['product_code'][$r];
                $item_name = $_POST['product_name'][$r];
				$item_peice     = $_POST['piece'][$r];
				$item_wpeice	= $_POST['wpiece'][$r];
                $product_note = $_POST['product_note'][$r];
				$group_price_id = $_POST['group_price_id'][$r];
                $item_option = isset($_POST['product_option'][$r]) && $_POST['product_option'][$r] != 'false' ? $_POST['product_option'][$r] : NULL;
                
                $real_unit_price = $this->erp->formatDecimal($_POST['real_unit_price'][$r]);
                $unit_price = $this->erp->formatDecimal($_POST['unit_price'][$r]);
				$net_price = $this->erp->formatDecimal($_POST['net_price'][$r]);
                $item_quantity = $_POST['quantity'][$r];
                $item_qty_received = $_POST['quantity_received'][$r];
				$item_unit_quantity = $_POST['quantity'][$r];
                $item_serial = isset($_POST['serial'][$r]) ? $_POST['serial'][$r] : '';
                $item_tax_rate = isset($_POST['product_tax'][$r]) ? $_POST['product_tax'][$r] : NULL;
                $item_discount = isset($_POST['product_discount'][$r]) ? $_POST['product_discount'][$r] : NULL;
				$item_price_id = $_POST['price_id'][$r];

                if (isset($item_code) && isset($real_unit_price) && isset($unit_price) && isset($item_quantity)) {
                    
					$product_details = $item_type != 'manual' ? $this->quotes_model->getProductByCode($item_code) : null;
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
					
                    $item_net_price = $unit_price - $pr_discount;
                    $pr_item_discount = $this->erp->formatDecimal($pr_discount);
                    $product_discount += $pr_item_discount * $item_quantity;
					
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
						
                        $pr_item_tax = $this->erp->formatDecimal($item_tax * $item_quantity);
                    }
					
                    $product_tax += $pr_item_tax;
					
                    $subtotal = (($item_net_price * $item_quantity) + $item_tax * $item_quantity);
					
                    $products[] = array(
                        'product_id' => $item_id,
                        'digital_id' => $digital_id,
                        'product_code' => $item_code,
                        'product_name' => $item_name,
                        'product_type' => $item_type,
                        'option_id' => $item_option,
                        'net_unit_price' => $item_net_price,
                        'unit_price' => $unit_price,
                        'quantity' => $item_quantity,
                        'quantity_received' => (($item_qty_received == NaN) ? 0 : $item_qty_received),
                        'warehouse_id' => $warehouse_id,
                        'item_tax' => $pr_item_tax,
                        'tax_rate_id' => $pr_tax,
                        'tax' => $tax,
						'piece'		=> $item_peice,
						'wpiece'	=> $item_wpeice,
                        'discount' => $item_discount,
						'group_price_id'=>$group_price_id,
                        'item_discount' => $pr_item_discount,
                        'subtotal' => $subtotal,
                        'serial_no' => $item_serial,
                        'real_unit_price' => $real_unit_price,
                        'price_id' => $item_price_id,
                        'product_noted' => $product_note,
                    );
                    $total += $subtotal;
					$ord_qty += $item_quantity;
					$rec_qty += (($item_qty_received == NaN) ? 0 : $item_qty_received);
                }
            }
			
            if (empty($products)) {
                $this->form_validation->set_rules('product', lang("order_items"), 'required');
            } else {
                krsort($products);
            }
            $total = $this->erp->formatDecimal($total);
            if ($this->input->post('order_discount')) {
                $order_discount_id = $this->input->post('order_discount');
                $opos = strpos($order_discount_id, $percentage);
                if ($opos !== false) {
                    $ods = explode("%", $order_discount_id);
                    $order_discount = $this->erp->formatDecimal(((($total) * (Float) ($ods[0])) / 100), 4);
                } else {
                    $order_discount = $this->erp->formatDecimal(($order_discount_id * $total) / 100);
                }
            } else {
                $order_discount_id = null;
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
			
			if (($ord_qty > $rec_qty) && ($rec_qty != 0)) {
				$delivery_status = 'partial';
			} elseif ($ord_qty == $rec_qty) {
				$delivery_status =  'completed';
			} else {
				$delivery_status = 'delivery';
			}

            $total_tax = ($product_tax + $order_tax); 
            $grand_total = ($total + $order_tax + $shipping - $order_discount);
        
            $data = array(
                'date' => $date,
                'delivery_date' => $delivery_date,
                'reference_no' => $reference,
                'customer_id' => $customer_id,
                'customer' => $customer,
				'group_areas_id' => $group_area,
                'biller_id' => $biller_id,
                'biller' => $biller,
                'warehouse_id' => $warehouse_id,
                'note' =>  htmlspecialchars($note,ENT_QUOTES),
                'staff_note' =>  htmlspecialchars($staff_note,ENT_QUOTES),
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
                'total_items' => $total_items,
				'delivery_status' => $delivery_status,
                'payment_status' => $payment_status,
                'payment_term' => $payment_term,
				'paid' => ($amout_paid != '' || $amout_paid != 0 || $amout_paid != null)? str_replace(',', '', $amout_paid) : 0,
                'due_date' => $due_date,
                'updated_by' => $this->session->userdata('user_id'),
                'updated_at' => date('Y-m-d H:i:s'),
				'saleman_by' => $saleman_by,
				'delivery_by' => $delivery_by
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

        if ($this->form_validation->run() == true && $this->sales_model->updateSaleOrder($id, $data, $products)) {
            $this->session->set_userdata('remove_slls', 1);
			$this->session->set_userdata('remove_so2', '1');
            $this->session->set_flashdata('message', lang("sale order update succefully."));
            redirect("sale_order/list_sale_order");
			
        } else {
			
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
			$inv = $this->sale_order_model->getSaleOrder($id);
            $inv_items = $this->sale_order_model->getSaleOrderItems($id);

            $this->data['inv'] = $inv;
			$this->data['quote'] = $this->quotes_model->getQuoteByID($inv->quote_id);
			$customer = $this->site->getCompanyByID($inv->customer_id);
            $customer_group = $this->site->getCustomerGroupByID($customer->customer_group_id);
            $c = rand(100000, 9999999);
            foreach ($inv_items as $item) {
                $row = $this->site->getProductByIDWh($item->product_id, $item->warehouse_id);
				$dig = $this->site->getProductByID($item->digital_id);

                if (!$row) {
                    $row = json_decode('{}');
                    $row->tax_method = 0;
                    $row->quantity = 0;
                } else {
                    unset($row->details, $row->product_details, $row->cost, $row->supplier1price, $row->supplier2price, $row->supplier3price, $row->supplier4price, $row->supplier5price);
                }
                $pis = $this->sales_model->getPurchasedItems($item->product_id, $item->warehouse_id, $item->option_id);
				$pending_so_qty = $this->sales_model->getPendingSOQTYByProductID($item->product_id);

				$psoqty = 0;
				if($pending_so_qty) {
					$psoqty = $pending_so_qty->psoqty;
				}				
                if($pis){
                    foreach ($pis as $pi) {
                        $row->quantity += $pi->quantity_balance;
                    }
                }
				$row->digital_code 	= "";
                $row->digital_name 	= "";
                $row->digital_id   	= "";
				if($dig){
					$row->digital_code 	= $dig->code .' ['. $row->code .']';
					$row->digital_name 	= $dig->name .' ['. $row->name .']';
					$row->digital_id   	= $dig->id;
				}
				$row->psoqty = $psoqty;
				$row->piece	 = $item->piece;
				$row->wpiece = $item->wpiece;
				$row->w_piece = $item->wpiece;
                $row->id = $item->product_id;
                $row->code = $item->product_code;
                $row->name = $item->product_name;
                $row->type = $item->product_type;
                $row->qty = $item->quantity;
                $row->qty_received = $item->quantity_received;
                $row->quantity = $row->quantity;
				//$row->cost += $item->cost;
                $row->discount = $item->discount ? $item->discount : '0';
                $row->price = $this->erp->formatDecimal($item->net_unit_price+$this->erp->formatDecimal($item->item_discount/$item->quantity));
                $row->unit_price = $row->tax_method ? $item->unit_price+$this->erp->formatDecimal($item->item_discount/$item->quantity)+$this->erp->formatDecimal($item->item_tax/$item->quantity) : $item->unit_price+($item->item_discount/$item->quantity);
                $row->real_unit_price = $item->real_unit_price;
                $row->tax_rate = $item->tax_rate_id;
                $row->serial = $item->serial_no;
                $row->option = $item->option_id;
				$row->unit = $row->unit;
				$row->note = $item->product_noted;
				$options = $this->sales_model->getProductOptions($row->id, $item->warehouse_id);
				$test = $this->sales_model->getWP2($row->id, $item->warehouse_id);
				$row->quantity = $test->quantity;

                $group_prices = $this->sales_model->getProductPriceGroup($item->product_id, $customer->price_group_id);
                $all_group_prices = $this->sales_model->getProductPriceGroup($item->product_id);
                $row->price_id = $item->price_id;
                if ($options) {
                    $option_quantity = 0;
                    foreach ($options as $option) {
                        $pis = $this->sales_model->getPurchasedItems($row->id, $item->warehouse_id, $item->option_id);
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
				if($group_prices) {
					$curr_by_item = $this->site->getCurrencyByCode($group_prices[0]->currency_code);
				}else {
					$curr_by_item = array();
				}
				$row->item_load 	  =1;
				$row->rate_item_cur   = ($curr_by_item ? $curr_by_item->rate : 0);

                $combo_items = FALSE;
                if ($row->type == 'combo') {
                    $combo_items = $this->sales_model->getProductComboItems($row->id, $item->warehouse_id);
                    $te = $combo_items;
                    foreach ($combo_items as $combo_item) {
                        $combo_item->quantity =  $combo_item->qty*$item->quantity;
                    }
                }
                $customer_percent = $customer_group->percent ? $customer_group->percent : 0;
                $ri = $this->Settings->item_addition ? $c : $c;
                if ($row->tax_rate) {
                    $tax_rate = $this->site->getTaxRateByID($row->tax_rate);
                    $pr[$ri] = array('id' => $c, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'combo_items' => $combo_items, 'tax_rate' => $tax_rate, 'options' => $options,'makeup_cost' => 0,'group_prices'=>$group_prices, 'all_group_prices' => $all_group_prices,'customer_percent' => $customer_percent);
                } else {
                    $pr[$ri] = array('id' => $c, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'combo_items' => $combo_items, 'tax_rate' => false, 'options' => $options,'makeup_cost' => 0,'group_prices'=>$group_prices, 'all_group_prices' => $all_group_prices,'customer_percent' => $customer_percent);
                }
                $c++;
			
            }
			$this->load->model('purchases_model');
			$this->data['exchange_rate'] 	= $this->site->getCurrencyByCode('KHM');
            $this->data['inv_items'] 		= json_encode($pr);
            $this->data['id'] 				= $id;
            //$this->data['currencies'] 	= $this->site->getAllCurrencies();
            $this->data['billers'] 			= ($this->Owner || $this->Admin) ? $this->site->getAllCompanies('biller') : NULL;
            $this->data['tax_rates'] 		= $this->site->getAllTaxRates();
			$this->data['agencies'] 		= $this->site->getAllUsers();
            $this->data['warehouses'] 		= $this->site->getAllWarehouses();
			//$this->data['payment_ref'] 	= (empty($this->site->getReference('IPAY'))?$this->site->getReference('IPAY'):"");
			$this->data['drivers'] 			= $this->site->getDriverByGroupId();
			$this->data['areas'] 			= $this->site->getArea();
			$this->data['categories'] 		= $this->site->getAllCategories();
			$this->data['unit'] 			= $this->purchases_model->getUnits();
			$this->data['payment_term'] 	= $this->site->getAllPaymentTerm();
			$this->data['setting'] 			= $this->site->get_setting();
			$this->session->set_userdata('remove_so2', '1');
            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('sale_order'), 'page' => lang('sale_order')), array('link' => '#', 'page' => lang('edit_sale_order')));
            $meta = array('page_title' => lang('edit_sale_order'), 'bc' => $bc);
            $this->page_construct('sale_order/edit_sale_order', $meta, $this->data);
        }
    }
	/*=========================================end local updated====================================*/
	
    /* --------------------------------------------------------------------------------------------- */

    function suggestions()
    {
        $term = $this->input->get('term', TRUE);
        $warehouse_id = $this->input->get('warehouse_id', TRUE);
        $customer_id = $this->input->get('customer_id', TRUE);
		
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
        $setting        = $this->sales_model->getSettings();
        $customer 		= $this->site->getCompanyByID($customer_id);
        $customer_group = $this->site->getCustomerGroupByID($customer->customer_group_id);
		$user_setting 	= $this->site->getUserSetting($this->session->userdata('user_id'));
        $percent        = '';
        $rows 			= $this->sales_model->getProductNames($sr, $warehouse_id, $user_setting->sales_standard, $user_setting->sales_combo, $user_setting->sales_digital, $user_setting->sales_service, $user_setting->sales_category);
        if ($rows) {
            foreach ($rows as $row) {
                $option = FALSE;
                $row->quantity = 0;
                $row->item_tax_method = $row->tax_method;
                $row->qty = 1;
                $row->discount = '0';
                $row->serial = '';
                $options = $this->sales_model->getProductOptions($row->id, $warehouse_id);

                $group_prices = $this->sales_model->getProductPriceGroupId($row->id, $customer->price_group_id);
                $all_group_prices = $this->sales_model->getProductPriceGroup($row->id);
				
                if ($options) {
                    $opt = $options[count($options)-1];
                    if (!$option) {
                        $option = $opt->id;
                    }
                } else {
                    $opt = json_decode('{}');
                    $opt->price = 0;
                }
                if ($opt_id) {
					$row->option 		= $opt_id;
				} else {
					$row->option 		= $option;
				}
                $pis = $this->sales_model->getPurchasedItems($row->id, $warehouse_id, $row->option);
                if($pis){
                    foreach ($pis as $pi) {
                        $row->quantity += $pi->quantity_balance;
                    }
                }
                if ($options) {
                    $option_quantity = 0;
                    foreach ($options as $option) {
                        $pis = $this->sales_model->getPurchasedItems($row->id, $warehouse_id, $row->option);
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
						$row->price = $group_prices[0]->price + (($group_prices[0]->price * $customer_group->percent) / 100);
					}
				}else{
					$row->price_id = 0;
				}
				
                $row->real_unit_price = $row->price;
                $combo_items = FALSE;
                if ($row->tax_rate) {
                    $tax_rate = $this->site->getTaxRateByID($row->tax_rate);
                    if ($row->type == 'combo') {
                        $combo_items = $this->sales_model->getProductComboItems($row->id, $warehouse_id);
                    }
                    $pr[] = array('id' => str_replace(".", "", microtime(true)), 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")" . " (" . $row->price . ")", 'row' => $row, 'combo_items' => $combo_items,'group_prices'=>$group_prices, 'all_group_prices' => $all_group_prices, 'tax_rate' => $tax_rate, 'options' => $options, 'makeup_cost'=>$customer_group->makeup_cost, 'customer_percent' => $customer_group->percent,'makeup_cost_percent'=>$percent->percent);
                } else {
                    $pr[] = array('id' => str_replace(".", "", microtime(true)), 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")" . " (" . $row->price . ")" , 'row' => $row, 'combo_items' => $combo_items,'group_prices'=>$group_prices, 'all_group_prices' => $all_group_prices, 'tax_rate' => false, 'options' => $options, 'makeup_cost'=>$customer_group->makeup_cost, 'customer_percent' => $customer_group->percent,'makeup_cost_percent'=>$percent->percent);
                }
            }
			//$this->erp->print_arrays($pr);
            echo json_encode($pr);
        } else {
            echo json_encode(array(array('id' => 0, 'label' => lang('no_match_found'), 'value' => $term)));
        }
    }
    
    function suggestionsSale()
    {
        $term = $this->input->get('term', TRUE);
        $warehouse_id = $this->input->get('warehouse_id', TRUE);
        $customer_id = $this->input->get('customer_id', TRUE);

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

        $customer 		= $this->site->getCompanyByID($customer_id);
        $customer_group = $this->site->getCustomerGroupByID($customer->customer_group_id);
		$user_setting 	= $this->site->getUserSetting($this->session->userdata('user_id'));
        $rows 			= $this->sale_order_model->getProductNames($sr, $warehouse_id, $user_setting->sales_standard, $user_setting->sales_combo, $user_setting->sales_digital, $user_setting->sales_service, $user_setting->sales_category);
		
        if ($rows) {
            foreach ($rows as $row) {
                $option = FALSE;
                $row->quantity = 0;
                $row->item_tax_method = $row->tax_method;
                $row->qty = 1;
                $row->discount = '0';
                $row->serial = '';
                $options = $this->sale_order_model->getProductOptions($row->id, $warehouse_id);
                $group_prices = $this->sales_model->getProductPriceGroupId($row->id, $customer->price_group_id);
                $all_group_prices = $this->sales_model->getProductPriceGroup($row->id);
				$pending_so_qty = $this->sales_model->getPendingSOQTYByProductID($row->id);
				$psoqty = 0;
			
				if($pending_so_qty) {
					$psoqty = $pending_so_qty->psoqty;
				}
                if ($options) {
                    $opt = $options[count($options)-1];
                    if (!$option) {
                        $option = $opt->id;
                    }
                } else {
                    $opt = json_decode('{}');
                    $opt->price = 0;
                }
				$row->psoqty = $psoqty;
                if ($opt_id) {
					$row->option 		= $opt_id;
				} else {
					$row->option 		= $option;
				}
                $pis = $this->sales_model->getPurchasedItems($row->id, $warehouse_id, $row->option);
                if($pis){
                    foreach ($pis as $pi) {
                       // $row->quantity += $pi->quantity_balance;
                    }
                }
				$test = $this->sales_model->getWP2($row->id, $warehouse_id);
				$row->quantity = $test->quantity;
                if ($options) {
                    $option_quantity = 0;
                    foreach ($options as $option) {
                        $pis = $this->sales_model->getPurchasedItems($row->id, $warehouse_id, $row->option);
						
                        if($pis){
                            foreach ($pis as $pi) {
                                //$option_quantity += $pi->quantity_balance;
                            }
                        }
                        if($option->quantity > $option_quantity) {
                           // $option->quantity = $option_quantity;
                        }
						//$option->quantity = $test->quantity;
                    }
                }
				
				$setting = $this->sales_model->getSettings();
				
				if($row->subcategory_id)
				{
					$percent = $this->sales_model->getCustomerMakup($customer->customer_group_id,$row->id,1);
				}else{
					$percent = $this->sales_model->getCustomerMakup($customer->customer_group_id,$row->id,0);
				}
				
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
						$row->price = $group_prices[0]->price + (($group_prices[0]->price * $customer_group->percent) / 100);
					}
				}else{
					$row->price_id = 0;
				}
				
				$row->rate_item_cur   = (isset($curr_by_item->rate)?$curr_by_item->rate:0);
				
                $row->real_unit_price = $row->price;
				$row->piece           = 0;
				$row->wpiece		  = $row->cf1;
				$row->item_load       = 0;
				$row->is_quote		  = 0;
				$row->w_piece		  = $row->cf1;
				$row->digital_id	  = 0;
				$row->digital_code	  = '';
				$row->digital_name	  = '';
                $group_price          = null;
                $combo_items          = FALSE;
                $row->note            = $row->product_details;

                $options = $options ? $options : '';
                $group_prices = $group_prices ? $group_prices : '';
                $all_group_prices = $all_group_prices ? $all_group_prices : '';
                $customer_group_makeup_cost = $customer_group ? $customer_group->makeup_cost : '';
                $customer_group_percent = $customer_group ? $customer_group->percent : '';
                $percent_percent = $percent ? $percent->percent : '';


                if ($row->tax_rate) {
                    $tax_rate = $this->site->getTaxRateByID($row->tax_rate);
                    if ($row->type == 'combo') {
                        $combo_items = $this->sales_model->getProductComboItems($row->id, $warehouse_id);
                    }
                    $pr[] = array('id' => str_replace(".", "", microtime(true)), 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'combo_items' => $combo_items, 'tax_rate' => $tax_rate, 'options' => $options, 'group_price' => $group_price, 'group_prices' => $group_prices, 'all_group_prices' => $all_group_prices, 'makeup_cost' => $customer_group_makeup_cost, 'customer_percent' => $customer_group_percent, 'makeup_cost_percent' => $percent_percent);
                    
                } else {
                    $pr[] = array('id' => str_replace(".", "", microtime(true)), 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'combo_items' => $combo_items, 'tax_rate' => false, 'options' => $options,'group_price'=>$group_price,'group_prices'=>$group_prices, 'all_group_prices' => $all_group_prices, 'makeup_cost'=>$customer_group->makeup_cost, 'customer_percent' => $customer_group->percent,'makeup_cost_percent'=>$percent->percent);
                }
            }
		
            echo json_encode($pr);
        } else {
            echo json_encode(array(array('id' => 0, 'label' => lang('no_match_found'), 'value' => $term)));
        }
    }

	function suggests()
    {
        $term = $this->input->get('term', TRUE);
        $warehouse_id = $this->input->get('warehouse_id', TRUE);
        $customer_id = $this->input->get('customer_id', TRUE);

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
		//$customer_group = $this->site->getMakeupCostByCompanyID($customer_id);
		$user_setting = $this->site->getUserSetting($this->session->userdata('user_id'));
        $rows = $this->sales_model->getProductNames($sr, $warehouse_id, $user_setting->sales_standard, $user_setting->sales_combo, $user_setting->sales_digital, $user_setting->sales_service, $user_setting->sales_category);
        $expiry_status = 0;
		if($this->site->get_setting()->product_expiry == 1){
			$expiry_status = 1;
		}
		if ($rows) {
            foreach ($rows as $row) {
                $option = FALSE;
                $row->quantity = 0;
                $row->item_tax_method = $row->tax_method;
                $row->qty = 1;
                $row->discount = '0';
                $row->serial = '';
                $options = $this->sales_model->getProductOptions($row->id, $warehouse_id);
                $group_prices = $this->sales_model->getProductPriceGroupId($row->id, $customer->price_group_id);
                $all_group_prices = $this->sales_model->getProductPriceGroup($row->id);
				$expdates = $this->sales_model->getProductExpireDate($row->id, $warehouse_id);
				
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
				if($expiry_status = 1){
					$row->expdate = $expdates[0]->id;
				}
                $pis = $this->sales_model->getPurchasedItems($row->id, $warehouse_id, $row->option);
                if($pis){
                    foreach ($pis as $pi) {
                        $row->quantity += $pi->quantity_balance;
                    }
                }
                if ($options) {
                    $option_quantity = 0;
                    foreach ($options as $option) {
                        $pis = $this->sales_model->getPurchasedItems($row->id, $warehouse_id, $row->option);
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
                if ($opt->price != 0) {
					if($customer_group->makeup_cost == 1){
						$row->price = $row->cost + (($row->cost * $customer_group->percent) / 100);
					}else{
						$row->price = $opt->price + (($opt->price * $customer_group->percent) / 100);
					}
                } else {
					if($customer_group->makeup_cost == 1){
						$row->price = $row->cost + (($row->cost * $customer_group->percent) / 100);
					}else{
						$row->price = $row->price + (($row->price * $customer_group->percent) / 100);
					}
                }
				
				if($group_prices)
				{
				   $curr_by_item = $this->site->getCurrencyByCode($group_prices[0]->currency_code);
				   $row->price_id = $group_prices[0]->id ? $group_prices[0]->id : 0;
				   $row->price = $group_prices[0]->price ? $group_prices[0]->price : 0;
				   
				   if($customer_group->makeup_cost == 1){
						$row->price = $row->cost + (($row->cost * (isset($customer_group->percent)?$customer_group->percent:0)) / 100);
					}else{
						$row->price = $group_prices[0]->price + (($group_prices[0]->price * $customer_group->percent) / 100);
					}
				}else{
					$row->price_id = 0;
				}
				//echo 'Price ID: ' . $row->price_id;
				//$this->erp->print_arrays($group_prices);
				
                $row->real_unit_price = $row->price;
                $combo_items = FALSE;
				$row->piece           = 0;
				$row->wpiece		  = $row->cf1;
				$row->item_load       = 0;
				$row->is_quote		  = 0;
				$row->w_piece		  = $row->cf1;
				$row->digital_id	  = 0;
				$row->digital_code	  = '';
				$row->digital_name	  = '';
                $percent              = '';
                if ($row->tax_rate) {
                    $tax_rate = $this->site->getTaxRateByID($row->tax_rate);
                    if ($row->type == 'combo') {
                        $combo_items = $this->sales_model->getProductComboItems($row->id, $warehouse_id);
                    }
                    $pr[] = array('id' => str_replace(".", "", microtime(true)), 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'combo_items' => $combo_items, 'tax_rate' => $tax_rate, 'options' => $options,'expdates'=>$expdates,'group_prices'=>$group_prices, 'all_group_prices' => $all_group_prices, 'makeup_cost'=>$customer_group->makeup_cost, 'customer_percent' => $customer_group->percent,'makeup_cost_percent'=>$percent->percent);
                } else {
                    $pr[] = array('id' => str_replace(".", "", microtime(true)), 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'combo_items' => $combo_items, 'tax_rate' => false, 'options' => $options,'expdates'=>$expdates,'group_prices'=>$group_prices, 'all_group_prices' => $all_group_prices, 'makeup_cost'=>$customer_group->makeup_cost, 'customer_percent' => $customer_group->percent,'makeup_cost_percent'=>$percent->percent);
                }
            }
            echo json_encode($pr);
        } else {
            echo json_encode(array(array('id' => 0, 'label' => lang('no_match_found'), 'value' => $term)));
        }
    }
	/* --------------------------------------------------------------------------------------------- */
	/*===========================================chin local add================================*/
	function getAuthorization($id) {
		$this->erp->checkPermissions('authorize', NULL, 'sale_order');
		if($this->sale_order_model->getAuthorizeSaleOrder($id)){
			$this->session->set_flashdata('message', $this->lang->line("sale_order_approved"));
			redirect($_SERVER["HTTP_REFERER"]);	
		}else{
			$this->session->set_flashdata('error', validation_errors());
			die();
		}
	}
	function getunapproved($id) {
		$this->erp->checkPermissions('authorize', NULL, 'sale_order');
		
		if(($this->sale_order_model->getSaleOrder($id)->sale_status != 'order' && $this->sale_order_model->getSaleOrder($id)->sale_status != 'delivery')) {
			$this->session->set_flashdata('error', lang("sale_order_has_been_created"));
			redirect($_SERVER["HTTP_REFERER"]);
		}
		if($this->sale_order_model->getunapproved($id)){
			$this->session->set_flashdata('message', $this->lang->line("sale_order_unapproved"));
			redirect($_SERVER["HTTP_REFERER"]);	
		}else{
			$this->session->set_flashdata('error', validation_errors());
			die();
		}
	}
	function getrejected($id) {
		$this->erp->checkPermissions('authorize', NULL, 'sale_order');
		if(($this->sale_order_model->getSaleOrder($id)->sale_status) != 'order'){
				$this->session->set_flashdata('error', lang("sale_order_has_been_created"));
				redirect($_SERVER["HTTP_REFERER"]);
			}
		if($this->sale_order_model->getrejected($id)){
			$this->session->set_flashdata('message', $this->lang->line("sale_order_rejected"));
			redirect($_SERVER["HTTP_REFERER"]);	
		}else{
			$this->session->set_flashdata('error', validation_errors());
			die();
		}
	}
	/*============================================end local add================================*/
	
	function view_document($sale_id = NULL)
    {
        $this->erp->checkPermissions('index', TRUE);
		$this->data['document'] = $this->sales_model->getDocumentByID($sale_id);
        $this->load->view($this->theme . 'sales/view_document', $this->data);
    }
	
	function pdf($id = NULL, $view = NULL, $save_bufffer = NULL)
    {

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
		
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $inv = $this->sale_order_model->getInvoiceByID($id);
        $this->data['barcode'] = "<img src='" . site_url('products/gen_barcode/' . $inv->reference_no) . "' alt='" .$inv->reference_no . "' class='pull-left' />";
        $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
        $this->data['created_by'] = $this->site->getUser($inv->created_by);
        $this->data['updated_by'] = $inv->updated_by ? $this->site->getUser($inv->updated_by) : NULL;
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
        $this->data['inv'] = $inv;
        $return = $this->sales_model->getReturnBySID($id);
        $this->data['return_sale'] = $return;
        $this->data['rows'] = $this->sale_order_model->getAllInvoiceItems($id);
        $this->data['return_items'] = $return ? $this->sales_model->getAllReturnItems($return->id) : NULL;

        $name = lang("sale_order") . "_" . str_replace('/', '_', $inv->reference_no) . ".pdf";
        $html = $this->load->view($this->theme . 'sale_order/pdf', $this->data, TRUE);
        if ($view) {
            $this->load->view($this->theme . 'sale_order/pdf', $this->data);
        } elseif ($save_bufffer) {
            return $this->erp->generate_pdf($html, $name, $save_bufffer, $this->data['biller']->invoice_footer);
        } else {
            $this->erp->generate_pdf($html, $name, FALSE, $this->data['biller']->invoice_footer);
        }
    }
	
	public function combine_pdf($sales_id)
    {
        $this->erp->checkPermissions('combine_pdf', null, 'sale_order');

        foreach ($sales_id as $id) {

            $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
            $inv = $this->sale_order_model->getInvoiceByID($id);
            if (!$this->session->userdata('view_right')) {
                $this->erp->view_rights($inv->created_by);
            }
            $this->data['barcode'] = "<img src='" . site_url('products/gen_barcode/' . $inv->reference_no) . "' alt='" . $inv->reference_no . "' class='pull-left' />";
            $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
            $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
            $this->data['created_by'] = $this->site->getUser($inv->created_by);
            $this->data['updated_by'] = $inv->updated_by ? $this->site->getUser($inv->updated_by) : NULL;
            $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
            $this->data['inv'] = $inv;
            $this->data['rows'] = $this->sale_order_model->getAllInvoiceItems($id);
            $this->data['return_sale'] = $inv->return_id ? $this->sale_order_model->getInvoiceByID($inv->return_id) : NULL;
            $this->data['return_rows'] = $inv->return_id ? $this->sale_order_model->getAllInvoiceItems($inv->return_id) : NULL;
            $html_data = $this->load->view($this->theme . 'sale_order/pdf', $this->data, true);

            if (!isset($this->Settings->barcode_img)) {
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
	
	function view($id = NULL)
    {
        $this->erp->checkPermissions('index');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
		$this->load->model('pos_model');
		$this->data['pos'] = $this->pos_model->getSetting();
		$this->data['setting'] = $this->site->get_setting();
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $inv = $this->sale_order_model->getInvoiceByID($id);
        // $this->erp->print_arrays($inv);
        $this->data['barcode'] = "<img src='" . site_url('products/gen_barcode/' . $inv->reference_no) . "' alt='" . $inv->reference_no . "' class='pull-left' />";
        $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
        $this->data['payments'] = $this->sales_model->getPaymentsForSale($id);
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
        $this->data['created_by'] = $this->site->getUser($inv->created_by);
        $this->data['updated_by'] = $inv->updated_by ? $this->site->getUser($inv->updated_by) : NULL;
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
        $this->data['inv'] = $inv;
        $return = $this->sales_model->getReturnBySID($id);
        $this->data['return_sale'] = $return;
        $this->data['deposit'] = $this->sales_model->getDepositByID($id);
		
        $this->data['rows'] = $this->sale_order_model->getAllInvoiceItems($id);
        //$this->data['return_items'] = $return ? $this->sales_model->getAllReturnItems($return->id) : NULL;
        $this->data['paypal'] = $this->sales_model->getPaypalSettings();
        $this->data['skrill'] = $this->sales_model->getSkrillSettings();
		
		$bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('sale_order'), 'page' => lang('sale_order')),  array('link' => '#', 'page' => lang('view')));
        $meta = array('page_title' => lang('sale_order'), 'bc' => $bc);
		
        $this->page_construct('sale_order/view', $meta, $this->data);
    }

    function delivery_invoice($id = NULL)
    {
        
        $this->erp->checkPermissions('add', true, 'sales');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
        $this->load->model('pos_model');
        $this->data['setting'] = $this->site->get_setting();
        $this->data['pos'] = $this->pos_model->getSetting();
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $inv = $this->sale_order_model->getDeliveriesInvoiceByID($id);
        $this->data['barcode'] = "<img src='" . site_url('products/gen_barcode/' . $inv->do_reference_no) . "' alt='" . $inv->do_reference_no . "' class='pull-left' />";
        $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
        $this->data['payments'] = $this->sales_model->getPaymentsForSale($id);
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
        $this->data['user'] = $this->site->getUser($inv->created_by);
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
        $this->data['inv'] = $inv;
        $rows = $this->sale_order_model->getAllDeliveryInvoiceItems($id);
        $this->data['inv_items'] = $rows;
        // $this->data['payment_term'] = $this->sales_model->getPaymentermID($inv->payment_term);
        
        $return = $this->sales_model->getReturnBySID($id);
        $this->data['return_sale'] = $return;
        $records = $this->sales_model->getAllInvoiceItems($id);
        
        foreach($records as $record){
            $product_option = $record->option_id;
            if($product_option != Null && $product_option != "" && $product_option != 0){
                $item_quantity = $record->quantity;
                $record->quantity = 0;
                $option_details = $this->sales_model->getProductOptionByID($product_option);
                $record->quantity = $item_quantity / ($option_details->qty_unit);
            }
        }
        $this->data['rows'] = $records;
        // $this->data['sale_order'] = $this->sales_model->getSaleOrderById($inv->type_id);
        $this->data['return_items'] = $return ? $this->sales_model->getAllReturnItems($return->id) : NULL;
        $this->data['title'] = "2";
        $this->data['sid'] = $id;
        $this->load->view($this->theme.'sale_order/delivery_invoice',$this->data);
    }
	
	function delivery_invoice_lao($id = NULL)
    {
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
        $this->load->model('pos_model');
        $this->data['setting'] = $this->site->get_setting();
        $this->data['pos'] = $this->pos_model->getSetting();
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $inv = $this->sale_order_model->getDeliveriesInvoiceByID($id);
        $this->data['barcode'] = "<img src='" . site_url('products/gen_barcode/' . $inv->do_reference_no) . "' alt='" . $inv->do_reference_no . "' class='pull-left' />";
        $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
        $this->data['payments'] = $this->sales_model->getPaymentsForSale($id);
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
        $this->data['user'] = $this->site->getUser($inv->created_by);
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
        $this->data['inv'] = $inv;
        $rows = $this->sale_order_model->getAllDeliveryInvoiceItems($id);
        $this->data['inv_items'] = $rows;
        $return = $this->sales_model->getReturnBySID($id);
        $this->data['return_sale'] = $return;
        $records = $this->sales_model->getAllInvoiceItems($id);
        
        foreach($records as $record){
            $product_option = $record->option_id;
            if($product_option != Null && $product_option != "" && $product_option != 0){
                $item_quantity = $record->quantity;
                $record->quantity = 0;
                $option_details = $this->sales_model->getProductOptionByID($product_option);
                $record->quantity = $item_quantity / ($option_details->qty_unit);
            }
        }
        $this->data['rows'] = $records;
		$this->data['return_items'] = $return ? $this->sales_model->getAllReturnItems($return->id) : NULL;
        $this->data['title'] = "2";
        $this->data['sid'] = $id;
        $this->load->view($this->theme.'sale_order/delivery_invoice_lao',$this->data);
    }
	
	function standard_delivery_invoice($id = NULL)
    {
        
        $this->erp->checkPermissions('add', true, 'sales');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
        $this->load->model('pos_model');
        $this->data['setting'] = $this->site->get_setting();
        $this->data['pos'] = $this->pos_model->getSetting();
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $inv = $this->sale_order_model->getDeliveriesInvoiceByID($id);
        $this->data['barcode'] = "<img src='" . site_url('products/gen_barcode/' . $inv->do_reference_no) . "' alt='" . $inv->do_reference_no . "' class='pull-left' />";
        $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
        $this->data['payments'] = $this->sales_model->getPaymentsForSale($id);
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
        $this->data['user'] = $this->site->getUser($inv->created_by);
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
        $this->data['inv'] = $inv;
        $rows = $this->sale_order_model->getAllDeliveryInvoiceItems($id);
        $this->data['inv_items'] = $rows;
        
        $return = $this->sales_model->getReturnBySID($id);
        $this->data['return_sale'] = $return;
        $records = $this->sales_model->getAllInvoiceItems($id);
        
        foreach($records as $record){
            $product_option = $record->option_id;
            if($product_option != Null && $product_option != "" && $product_option != 0){
                $item_quantity = $record->quantity;
                $record->quantity = 0;
                $option_details = $this->sales_model->getProductOptionByID($product_option);
                $record->quantity = $item_quantity / ($option_details->qty_unit);
            }
        }
        $this->data['rows'] = $records;       
        $this->data['return_items'] = $return ? $this->sales_model->getAllReturnItems($return->id) : NULL;
        $this->data['title'] = "2";
        $this->data['sid'] = $id;
        $this->load->view($this->theme.'sale_order/standard_delivery_invoice',$this->data);
    }
    function delivery_invoice_a5($id = NULL)
    {
        
        $this->erp->checkPermissions('add', true, 'sales');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
        $this->load->model('pos_model');
        $this->data['setting'] = $this->site->get_setting();
        $this->data['pos'] = $this->pos_model->getSetting();
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $inv = $this->sale_order_model->getDeliveriesInvoiceByID($id);
        $this->data['barcode'] = "<img src='" . site_url('products/gen_barcode/' . $inv->do_reference_no) . "' alt='" . $inv->do_reference_no . "' class='pull-left' />";
        $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
        $this->data['payments'] = $this->sales_model->getPaymentsForSale($id);
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
        $this->data['user'] = $this->site->getUser($inv->created_by);
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
        $this->data['inv'] = $inv;
        $rows = $this->sale_order_model->getAllDeliveryInvoiceItems($id);
        // $this->erp->print_arrays($rows);
         $this->data['inv_items'] = $rows;

        // $this->data['payment_term'] = $this->sales_model->getPaymentermID($inv->payment_term);
        
        $return = $this->sales_model->getReturnBySID($id);
        $this->data['return_sale'] = $return;
        $records = $this->sales_model->getAllInvoiceItems($id);
        
        foreach($records as $record){
            $product_option = $record->option_id;
            if($product_option != Null && $product_option != "" && $product_option != 0){
                $item_quantity = $record->quantity;
                $record->quantity = 0;
                $option_details = $this->sales_model->getProductOptionByID($product_option);
                $record->quantity = $item_quantity / ($option_details->qty_unit);
            }
        }
        $this->data['rows'] = $records;
        // $this->data['sale_order'] = $this->sales_model->getSaleOrderById($inv->type_id);
        $this->data['return_items'] = $return ? $this->sales_model->getAllReturnItems($return->id) : NULL;
        $this->data['title'] = "2";
        $this->data['sid'] = $id;
        $this->load->view($this->theme.'sale_order/delivery_invoice_a5',$this->data);
    }

	function delivery_note($id = NULL)
    {
        // $this->erp->print_arrays($id);
        $this->erp->checkPermissions('add', true, 'sales');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
        $this->load->model('pos_model');
        $this->data['setting'] = $this->site->get_setting();
        $this->data['pos'] = $this->pos_model->getSetting();
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $inv = $this->sale_order_model->getDeliveriesInvoiceByID($id);
        $this->data['barcode'] = "<img src='" . site_url('products/gen_barcode/' . $inv->do_reference_no) . "' alt='" . $inv->do_reference_no . "' class='pull-left' />";
        $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
        $this->data['payments'] = $this->sales_model->getPaymentsForSale($id);
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
        $this->data['user'] = $this->site->getUser($inv->created_by);
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
        $this->data['inv'] = $inv;
        $rows = $this->sale_order_model->getAllDeliveryInvoiceItems($id);
        $this->data['inv_items'] = $rows;
        $this->data['payment_term'] = $this->sales_model->getPaymentermID(isset($inv->payment_term));
        
        $return = $this->sales_model->getReturnBySID($id);
        $this->data['return_sale'] = $return;
        $records = $this->sales_model->getAllInvoiceItems($id);
        
        foreach($records as $record){
            $product_option = $record->option_id;
            if($product_option != Null && $product_option != "" && $product_option != 0){
                $item_quantity = $record->quantity;
                $record->quantity = 0;
                $option_details = $this->sales_model->getProductOptionByID($product_option);
                $record->quantity = $item_quantity / ($option_details->qty_unit);
            }
        }
        $this->data['rows'] = $records;
        $this->data['sale_order'] = $this->sales_model->getSaleOrderById(isset($inv->type_id));
        $this->data['return_items'] = $return ? $this->sales_model->getAllReturnItems($return->id) : NULL;
        $this->data['title'] = "2";
        $this->data['sid'] = $id;
        $this->load->view($this->theme.'sale_order/delivery_note',$this->data);
    }
	function delivery_note_ppcp($id = NULL)
    {
        // $this->erp->print_arrays($id);
        $this->erp->checkPermissions('add', true, 'sales');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
        $this->load->model('pos_model');
        $this->data['setting'] = $this->site->get_setting();
        $this->data['pos'] = $this->pos_model->getSetting();
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $inv = $this->sale_order_model->getDeliveriesInvoiceByID($id);
        $this->data['barcode'] = "<img src='" . site_url('products/gen_barcode/' . $inv->do_reference_no) . "' alt='" . $inv->do_reference_no . "' class='pull-left' />";
        $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
        $this->data['payments'] = $this->sales_model->getPaymentsForSale($id);
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
        $this->data['user'] = $this->site->getUser($inv->created_by);
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
        $this->data['inv'] = $inv;
        $rows = $this->sale_order_model->getAllDeliveryInvoiceItems($id);
        $this->data['inv_items'] = $rows;
        $this->data['payment_term'] = $this->sales_model->getPaymentermID(isset($inv->payment_term));
        
        $return = $this->sales_model->getReturnBySID($id);
        $this->data['return_sale'] = $return;
        $records = $this->sales_model->getAllInvoiceItems($id);
        
        foreach($records as $record){
            $product_option = $record->option_id;
            if($product_option != Null && $product_option != "" && $product_option != 0){
                $item_quantity = $record->quantity;
                $record->quantity = 0;
                $option_details = $this->sales_model->getProductOptionByID($product_option);
                $record->quantity = $item_quantity / ($option_details->qty_unit);
            }
        }
        $this->data['rows'] = $records;
        $this->data['sale_order'] = $this->sales_model->getSaleOrderById(isset($inv->type_id));
        $this->data['return_items'] = $return ? $this->sales_model->getAllReturnItems($return->id) : NULL;
        $this->data['title'] = "2";
        $this->data['sid'] = $id;
        $this->load->view($this->theme.'sale_order/delivery_note_ppcp',$this->data);
    }
	
	function delivery_tiger($id = NULL)
    {
        // $this->erp->print_arrays($id);
        $this->erp->checkPermissions('add', true, 'sales');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
        $this->load->model('pos_model');
        $this->data['setting'] = $this->site->get_setting();
        $this->data['pos'] = $this->pos_model->getSetting();
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $inv = $this->sale_order_model->getDeliveriesInvoiceByID($id);
        $this->data['barcode'] = "<img src='" . site_url('products/gen_barcode/' . $inv->do_reference_no) . "' alt='" . $inv->do_reference_no . "' class='pull-left' />";
        $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
        $this->data['payments'] = $this->sales_model->getPaymentsForSale($id);
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
        $this->data['dq'] = $this->sale_order_model->getDeliveryQuantity($id);
		$this->data['totaldq'] = $this->sale_order_model->getTotalDeliveryQuantity($inv->sale_reference_no, $inv->id);
		$this->data['row'] = $this->sale_order_model->getDeliveryTigerByID($inv->sale_id);
		$this->data['inv'] = $inv;
        $this->data['inv_items'] = $this->sale_order_model->getAllDeliveryInvoiceItems($id);
		
        $this->load->view($this->theme.'sale_order/tiger_invoice',$this->data);
    }
    function delivery_note_a5($id = NULL)
    {
        // $this->erp->print_arrays($id);
        $this->erp->checkPermissions('add', true, 'sales');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
        $this->load->model('pos_model');
        $this->data['setting'] = $this->site->get_setting();
        $this->data['pos'] = $this->pos_model->getSetting();
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $inv = $this->sale_order_model->getDeliveriesInvoiceByID($id);
        $this->data['barcode'] = "<img src='" . site_url('products/gen_barcode/' . $inv->do_reference_no) . "' alt='" . $inv->do_reference_no . "' class='pull-left' />";
        $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
        $this->data['payments'] = $this->sales_model->getPaymentsForSale($id);
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
        $this->data['user'] = $this->site->getUser($inv->created_by);
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
        $this->data['inv'] = $inv;
      //  $a = $this->sale_order_model->getAllDeliveryInvoiceItems($id);
        $this->data['inv_items']  = $this->sale_order_model->getAllDeliveryInvoiceItems($id);
     // echo count( $a );
       // $this->erp->print_arrays($this->sale_order_model->getAllDeliveryInvoiceItems($id));

        // $this->data['inv_items'] = $rows;

        $this->data['payment_term'] = $this->sales_model->getPaymentermID(isset($inv->payment_term));
        
        $return = $this->sales_model->getReturnBySID($id);
        $this->data['return_sale'] = $return;
        $records = $this->sales_model->getAllInvoiceItems($id);
        
        foreach($records as $record){
            $product_option = $record->option_id;
            if($product_option != Null && $product_option != "" && $product_option != 0){
                $item_quantity = $record->quantity;
                $record->quantity = 0;
                $option_details = $this->sales_model->getProductOptionByID($product_option);
                $record->quantity = $item_quantity / ($option_details->qty_unit);
            }
        }
        $this->data['rows'] = $records;
        $this->data['sale_order'] = $this->sales_model->getSaleOrderById(isset($inv->type_id));
        $this->data['return_items'] = $return ? $this->sales_model->getAllReturnItems($return->id) : NULL;
        $this->data['title'] = "2";
        $this->data['sid'] = $id;
        $this->load->view($this->theme.'sale_order/delivery_note_a5',$this->data);
    }
	
	function delivery_note_a5_knk($id = NULL)
    {
        // $this->erp->print_arrays($id);
        $this->erp->checkPermissions('add', true, 'sales');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
        $this->load->model('pos_model');
        $this->data['setting'] = $this->site->get_setting();
        $this->data['pos'] = $this->pos_model->getSetting();
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $inv = $this->sale_order_model->getDeliveriesInvoiceByID($id);
        $this->data['barcode'] = "<img src='" . site_url('products/gen_barcode/' . $inv->do_reference_no) . "' alt='" . $inv->do_reference_no . "' class='pull-left' />";
        $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
        $this->data['payments'] = $this->sales_model->getPaymentsForSale($id);
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
        $this->data['user'] = $this->site->getUser($inv->created_by);
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
        $this->data['inv'] = $inv;
      //  $a = $this->sale_order_model->getAllDeliveryInvoiceItems($id);
        $this->data['inv_items']  = $this->sale_order_model->getAllDeliveryInvoiceItems($id);
     // echo count( $a );
       // $this->erp->print_arrays($this->sale_order_model->getAllDeliveryInvoiceItems($id));

        // $this->data['inv_items'] = $rows;

        $this->data['payment_term'] = $this->sales_model->getPaymentermID(isset($inv->payment_term));
        
        $return = $this->sales_model->getReturnBySID($id);
        $this->data['return_sale'] = $return;
        $records = $this->sales_model->getAllInvoiceItems($id);
        
        foreach($records as $record){
            $product_option = $record->option_id;
            if($product_option != Null && $product_option != "" && $product_option != 0){
                $item_quantity = $record->quantity;
                $record->quantity = 0;
                $option_details = $this->sales_model->getProductOptionByID($product_option);
                $record->quantity = $item_quantity / ($option_details->qty_unit);
            }
        }
        $this->data['rows'] = $records;
        $this->data['sale_order'] = $this->sales_model->getSaleOrderById(isset($inv->type_id));
        $this->data['return_items'] = $return ? $this->sales_model->getAllReturnItems($return->id) : NULL;
        $this->data['title'] = "2";
        $this->data['sid'] = $id;
        $this->load->view($this->theme.'sale_order/delivery_note_a5_knk',$this->data);
    }

    function print_invoice($id){
        // echo "string";exit();
        $this->data['invs'] = $this->sales_model->getSaleOrder($id);
        $this->data['bill'] = $this->sale_order_model->getCompanyByID($id);
        $this->data['rows'] = $this->sale_order_model->getAllInvoiceItems($id);
        $this->data['deposit'] = $this->sales_model->getDepositByID($id);
        // $this->erp->print_arrays($this->data['rows']);
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('invoice_devery')));
        $meta = array('page_title' => lang('invoice_devery'), 'bc' => $bc);
        $this->page_construct('sale_order/print_invoice', $meta, $this->data);
    }
	
	function down_payment($id=null)
    {	
		$this->form_validation->set_rules('loan_amount', lang("loan_amount"), 'required');

        if ($this->form_validation->run() == true) {
			
				$so_id 	       	   = $this->input->post('sale_id');
				$biller_id         = $this->input->post('biller_id');
				$frequency         = $this->input->post('frequency');
				$customer_id	   = $this->input->post('customer_id');
				$term		       = $this->input->post('depreciation_term');
				$depre_type        = $this->input->post('depreciation_type');
				$depre_rate		   = $this->input->post('depreciation_rate1');
				$princ_type	       = $this->input->post('principle_type');
				$princ_term		   = $this->input->post('priciple_term');
				$princ_loan		   = $this->input->post('priciple_loan');
				$down		       = $this->input->post('down_payment');
				$installment_date  = $this->erp->fld(trim($this->input->post('down_date')));
				$jl_id             = $this->input->post('jl_gov_id');
				$jl_name	       = $this->input->post('jl_name');
				$jl_dob		       = $this->input->post('jl_dob');
				$jl_gender         = $this->input->post('jl_gender');
				$jl_phone	       = $this->input->post('jl_phone_1');
				$jl_address		   = $this->input->post('jl_address');
				
				$jl_data = array('group_name'=>'join_lease',
								 'name'=>$jl_name,
								 'cf1'=>$jl_id,
								 'date_of_birth'=>$this->erp->fld(trim($jl_dob)),
								 'gender'=>$jl_gender,
								 'phone'=>$jl_phone,
								 'address'=>$jl_address);
				
				$loan_data = array('biller_id'=>$biller_id,
								   'term' => $term,
								   'interest_rate'=>$depre_rate,
								   'frequency'=>$frequency,
								   'depreciation_type'=>$depre_type,
								   'principle_type'=>$princ_type,
								   'down_amount'=>$down,
								   'installment_date'=>$installment_date,
								   'principle_amount'=>$princ_loan,
								   'principle_term'=>$princ_term);
			
				$no     = sizeof($_POST['no']);
				$period = 1;
			
				for($m = 0; $m < $no; $m++){
					
					    $dateline = $this->erp->fld(trim($_POST['dateline'][$m]));
						
						$loans[] = array(
						'period' 	 => $period,
						'sale_id' 	 => $so_id,
						'interest' 	 => $_POST['interest'][$m],
						'principle'  => $_POST['principle'][$m],
						'customer_id'=> $customer_id,
						'created_by' => $this->session->userdata('user_id'),
						'payment' 	 => $_POST['payment_amt'][$m],
						'balance' 	 => $_POST['balance'][$m],
						'type' 		 => $_POST['depreciation_type'],
						'rated' 	 => $_POST['depreciation_rate1'],
						'note' 		 => $_POST['note1'][$m],
						'dateline' 	 => $dateline,
						'biller_id'  => $biller_id
					);
					$period++;
					
				}
					
					
				$join_lease	=  $this->sale_order_model->AddJoinLease($jl_data,$so_id);
				$result     =  $this->sale_order_model->Addloans($loans,$so_id,$loan_data);
				
				redirect("sale_order/list_sale_order");
				
		}else{
			
			$this->data['billers'] = $this->site->getAllCompanies('biller');

			$this->data['warehouses'] = $this->site->getAllWarehouses();

			$inv = $this->sale_order_model->getSaleOrder($id);
			
			$this->data['setting']    = $this->site->get_setting();
			$this->data['pos']        = $this->pos_model->getSetting();
			$this->data['customer']   = $this->site->getCompanyByID($inv->customer_id);
			$this->data['biller']     = $this->site->getCompanyByID($inv->biller_id);
			$this->data['created_by'] = $this->site->getUser($inv->created_by);
			$this->data['updated_by'] = $inv->updated_by ? $this->site->getUser($inv->updated_by) : NULL;
			$this->data['warehouse']  = $this->site->getWarehouseByID($inv->warehouse_id);
			$this->data['terms']      = $this->sales_model->getTerms();
			$this->data['frequency']  = $this->sales_model->getFrequency();
			$this->data['principle']  = $this->sales_model->getPrinciple();
			$this->data['inv']		  = $inv;
            $return = $this->sales_model->getReturnBySID($id);
            $this->data['return_sale'] = $return;
            $this->data['jsrows'] = json_encode($this->sales_model->getAllInvoiceItems($id));
               
			$this->data['deposit'] = $this->sales_model->getPaymentsForSaleOrderFlora($id);
            $this->data['frequen'] = $this->sales_model->getSaleOrderBySaleOrderId($id);
            $this->data['order_loans'] = $this->sales_model->getLoanBySaleOrderId($id);
            $records = $this->sales_model->getAllSOInvoiceItemsByID($id);        
            foreach($records as $record){
                $product_option = $record->option_id;
                if($product_option != Null && $product_option != "" && $product_option != 0){
                    $item_quantity = $record->quantity;
                    $option_details = $this->sales_model->getProductOptionByID($product_option);
                }
            }
            $this->data['rows'] = $records;

			$customer = $this->site->getCompanyByID($inv->customer_id);
			$customer_group = $this->site->getCustomerGroupByID($customer->customer_group_id);
			$c = rand(100000, 9999999);
			
			$this->data['id'] = $id;
			$bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('sale_order/down_payment'), 'page' => lang('down_payment')), array('link' => '#', 'page' => lang('down_payment')));
			$meta = array('page_title' => lang('down_payment'), 'bc' => $bc);
			$this->page_construct('sale_order/down_payment', $meta, $this->data);
			
		}
       
	}
	
	function edit_down_payment($id=null){
		
		$this->form_validation->set_rules('loan_amount', lang("loan_amount"), 'required');

        if ($this->form_validation->run() == true) {
			
				$so_id 	       	   = $this->input->post('sale_id');
				$biller_id         = $this->input->post('biller_id');
				$frequency         = $this->input->post('frequency');
				$term		       = $this->input->post('depreciation_term');
				$depre_type        = $this->input->post('depreciation_type');
				$depre_rate		   = $this->input->post('depreciation_rate1');
				$princ_type	       = $this->input->post('principle_type');
				$payment_reference = $this->site->getReference('sp',$biller_id);
				$down		       = $this->input->post('down_payment');
				$down_date         = $this->erp->fld(trim($this->input->post('down_date')));
				$princ_term		   = $this->input->post('priciple_term');
				$princ_loan		   = $this->input->post('priciple_loan');
				$installment_date  = $this->erp->fld(trim($this->input->post('down_date')));
	
				$jl_row			   = $this->input->post('jl_id');
				$jl_id             = $this->input->post('jl_gov_id');
				$jl_name	       = $this->input->post('jl_name');
				$jl_dob		       = $this->input->post('jl_dob');
				$jl_gender         = $this->input->post('jl_gender');
				$jl_phone	       = $this->input->post('jl_phone_1');
				$jl_address		   = $this->input->post('jl_address');
				
				
				$jl_data = array('group_name'=>'join_lease',
								 'name'=>$jl_name,
								 'cf1'=>$jl_id,
								 'date_of_birth'=>$this->erp->fld(trim($jl_dob)),
								 'gender'=>$jl_gender,
								 'phone'=>$jl_phone,
								 'address'=>$jl_address);
				
				$loan_data = array('biller_id'=>$biller_id,
								   'term' => $term,
								   'interest_rate'=>$depre_rate,
								   'frequency'=>$frequency,
								   'depreciation_type'=>$depre_type,
								   'principle_type'=>$princ_type,
								   'down_amount'=>$down,
								   'installment_date'=>$installment_date,
								   'principle_amount'=>$princ_loan,
								   'principle_term'=>$princ_term);
				
				$total_interest = 0;
				$no = sizeof($_POST['no']);
				
				$period = 1;
			
				for($m = 0; $m < $no; $m++){
					
					$dateline = $this->erp->fld(trim($_POST['dateline'][$m]));
					
						$loans[] = array(
						'period' 	=> $period,
						'sale_id' 	=> $so_id,
						'interest' 	=> $_POST['interest'][$m],
						'principle' => $_POST['principle'][$m],
						'payment' 	=> $_POST['payment_amt'][$m],
						'balance' 	=> $_POST['balance'][$m],
						'type' 		=> $_POST['depreciation_type'],
						'rated' 	=> $_POST['depreciation_rate1'],
						'note' 		=> $_POST['note1'][$m],
						'dateline' 	=> $dateline,
						'biller_id' => $biller_id
					);
					$period++;
					
				}
					
				$join_lease	=  $this->sale_order_model->AddJoinLease($jl_data,$so_id);
				$result     =  $this->sale_order_model->Addloans($loans,$so_id,$loan_data,1);
				redirect("sale_order/list_sale_order");
				
		}else{
			
			$this->data['billers'] = $this->site->getAllCompanies('biller');
			$this->data['warehouses'] = $this->site->getAllWarehouses();

			$inv = $this->sale_order_model->getSaleOrder($id);
			
			$this->data['setting']     = $this->site->get_setting();
			$this->data['pos']         = $this->pos_model->getSetting();
			$this->data['customer']    = $this->site->getCompanyByID($inv->customer_id);
			$this->data['biller']      = $this->site->getCompanyByID($inv->biller_id);
			$this->data['created_by']  = $this->site->getUser($inv->created_by);
			$this->data['updated_by']  = $inv->updated_by ? $this->site->getUser($inv->updated_by) : NULL;
			$this->data['warehouse']   = $this->site->getWarehouseByID($inv->warehouse_id);
			$this->data['terms']       = $this->sales_model->getTerms();
			$this->data['frequency']   = $this->sales_model->getFrequency();
			$this->data['principle']   = $this->sales_model->getPrinciple();
			$this->data['inv']		   = $inv;
			$down_data                 = $this->sales_model->getOrderLoan($inv->id);
			$this->data['order_down']  = $down_data;
		    $this->data['jl_data']     = $this->sales_model->jl_data($down_data->join_lease_id);
			$this->data['LoanRated']   = $this->sales_model->LoanRated($inv->id);
			
			
			
			$return = $this->sales_model->getReturnBySID($id);
			$this->data['return_sale'] = $return;
			$this->data['rows'] = $this->sale_order_model->getSaleOrderItems($id);
			$this->data['jsrows'] = json_encode($this->sales_model->getAllInvoiceItems($id));
			
			//cmt
			$customer = $this->site->getCompanyByID($inv->customer_id);
			$customer_group = $this->site->getCustomerGroupByID($customer->customer_group_id);
			$c = rand(100000, 9999999);
			
			$this->data['id'] = $id;
			$bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('sale_order/edit_down_payment'), 'page' => lang('edit_down_payment')), array('link' => '#', 'page' => lang('edit_down_payment')));
			$meta = array('page_title' => lang('edit_down_payment'), 'bc' => $bc);
			$this->page_construct('sale_order/edit_down_payment', $meta, $this->data);
			
		}
       
	}
	
	function deliverys_sbps($id = NULL)
    {
        $this->erp->checkPermissions('index');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
        $this->load->model('pos_model');
        $this->data['pos'] = $this->pos_model->getSetting();
        
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        
        $inv = $this->sales_model->getSaleDeliveryByID($id); 
		//$this->erp->print_arrays($inv );
		$deli = $this->sales_model->getSaleDeliveryByID($id);
        $this->data['delivery'] = $deli;
		
        $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
        $this->data['created_by'] = $this->site->getUser($inv->created_by);
        $this->data['updated_by'] = $inv->updated_by ? $this->site->getUser($inv->updated_by) : NULL;
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
        $this->data['inv'] = $inv;
        $this->data['vattin'] = $this->site->getTaxRateByID($inv->order_tax_id);
        $return = $this->sales_model->getReturnBySID($id);
        $this->data['return_sale'] = $return;
        $this->data['rows'] = $this->sale_order_model->getAllDeliveryInvoiceItems($id);
		//$this->erp->print_arrays($data );
		
        $this->data['logo'] = true;
        $this->load->view($this->theme.'sales/deliverys_sbps',$this->data);
    }
	
	function deliverys_nano_tech($id = NULL)
    {
        
         $this->erp->checkPermissions('add', true, 'sales');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
        $this->load->model('pos_model');
        $this->data['setting'] = $this->site->get_setting();
        $this->data['pos'] = $this->pos_model->getSetting();
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $inv = $this->sale_order_model->getDeliveriesInvoiceByID($id);
        $this->data['barcode'] = "<img src='" . site_url('products/gen_barcode/' . $inv->do_reference_no) . "' alt='" . $inv->do_reference_no . "' class='pull-left' />";
        $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
        $this->data['payments'] = $this->sales_model->getPaymentsForSale($id);
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
        $this->data['user'] = $this->site->getUser($inv->created_by);
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
        $this->data['inv'] = $inv;
        $rows = $this->sale_order_model->getAllDeliveryInvoiceItems($id);
        $this->data['inv_items'] = $rows;
        $this->data['payment_term'] = $this->sales_model->getPaymentermID(isset($inv->payment_term));
        
        $return = $this->sales_model->getReturnBySID($id);
        $this->data['return_sale'] = $return;
        $records = $this->sales_model->getAllInvoiceItems($id);
        
        foreach($records as $record){
            $product_option = $record->option_id;
            if($product_option != Null && $product_option != "" && $product_option != 0){
                $item_quantity = $record->quantity;
                $record->quantity = 0;
                $option_details = $this->sales_model->getProductOptionByID($product_option);
                $record->quantity = $item_quantity / ($option_details->qty_unit);
            }
        }
        $this->data['rows'] = $records;
        $this->data['sale_order'] = $this->sales_model->getSaleOrderById(isset($inv->type_id));
        $this->data['return_items'] = $return ? $this->sales_model->getAllReturnItems($return->id) : NULL;
        $this->data['title'] = "2";
        $this->data['sid'] = $id;
        $this->load->view($this->theme.'sale_order/deliverys_nano_tech',$this->data);
    }
	
	
}

