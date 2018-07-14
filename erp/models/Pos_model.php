<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Pos_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    function getExchange_rate($code = "KHM")
    {
        $this->db->where(array('code' => $code));
        $q = $this->db->get('currencies');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    function getCustomerByID($cus_id = null)
    {
        $this->db->where(array('id' => $cus_id));
        $q = $this->db->get('companies');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }


    function getExchange_rates()
    {
        $q = $this->db->get('currencies', array('code' => 'KHM'), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }


    function getSetting()
    {
        $q = $this->db->get('pos_settings');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function updateSetting($data)
    {
        $this->db->where('pos_id', '1');
        if ($this->db->update('pos_settings', $data)) {
            return true;
        }
        return false;
    }

    public function products_count($category_id, $subcategory_id = NULL)
    {
        $this->db->where('category_id', $category_id)->from('products');
        if ($subcategory_id) {
            $this->db->where('subcategory_id', $subcategory_id);
        }
        return $this->db->count_all_results();
    }
    public function getQtyOrder($product_id){
        $this->db->select('erp_sale_order_items.quantity')
            ->join('erp_sale_order_items','erp_sale_order.id = erp_sale_order_items.sale_order_id','left')
            ->where('erp_sale_order.order_status <> "completed" AND erp_sale_order_items.product_id = "'.$product_id.'"')
            ->from('erp_sale_order');
        $q=$this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;

    }
    public function fetch_products($category_id, $limit, $start, $subcategory_id = NULL)
    {
        $this->db->limit($limit, $start);
        $this->db->where('category_id', $category_id);
        if ($subcategory_id) {
            $this->db->where('subcategory_id', $subcategory_id);
        }
        if(!$this->Settings->overselling) {
            $this->db->where('products.quantity >', 0);
        }
        $this->db->where('inactived', 0);
        $this->db->order_by("name", "asc");
        $query = $this->db->get("products");

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }

    public function fetch_products_permission($category_id, $limit, $start, $subcategory_id = NULL)
    {
        $user               = $this->site->getUser();
        $sales_standard     = $user->sales_standard;
        $sales_combo        = $user->sales_combo;
        $sales_digital      = $user->sales_digital;
        $sales_service      = $user->sales_service;
        $sales_category     = $user->sales_category;
        $this->db->limit($limit, $start);
        if($category_id){
            $this->db->where('category_id', $category_id);
        }
        if ($subcategory_id) {
            $this->db->where('subcategory_id', $subcategory_id);
        }
        if(!$this->Settings->overselling) {
            $this->db->where('products.quantity >', 0);
        }
        if(!$this->Owner and !$this->Admin){
            if($sales_standard != ""){
                $this->db->where("products.type <> 'standard' ");
            }
            if($sales_combo != ""){
                $this->db->where("products.type <> 'combo' ");
            }
            if($sales_digital != ""){
                $this->db->where("products.type <> 'digital' ");
            }
            if ($category_id != "") {
                $this->db->where("products.category_id", $category_id);
            }
            if($sales_service != ""){
                $this->db->where("products.type <> 'service' ");
            }
            if ($sales_category != "") {
                $this->db->where("products.category_id NOT IN (" . $sales_category . ") ");
            }
        }
        $this->db->where('inactived', 0);
        $this->db->order_by("name", "asc");
        $query = $this->db->get("products");

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }

    public function registerData($user_id)
    {
        if (!$user_id) {
            $user_id = $this->session->userdata('user_id');
        }
        $q = $this->db->get_where('pos_register', array('user_id' => $user_id, 'status' => 'open'), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function OpenRegisterData($id)
    {
        if (!$user_id) {
            $user_id = $this->session->userdata('user_id');
        }
        $q = $this->db->get_where('pos_register', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function closeRegisterData($id)
    {
        if (!$user_id) {
            $user_id = $this->session->userdata('user_id');
        }
        $q = $this->db->get_where('pos_register', array('id' => $id, 'status' => 'close'), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function openRegister($data)
    {
		if ($this->db->insert('pos_register', $data)) {
            return true;
        }
        return FALSE;
    }

    public function getOpenRegisters()
    {
        $this->db->select("date, user_id, cash_in_hand, CONCAT(" . $this->db->dbprefix('users') . ".first_name, ' ', " . $this->db->dbprefix('users') . ".last_name, ' - ', " . $this->db->dbprefix('users') . ".email) as user", FALSE)
            ->join('users', 'users.id=pos_register.user_id', 'left');
        $q = $this->db->get_where('pos_register', array('status' => 'open'));
        if ($q->num_rows() > 0) {
            foreach ($q->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;

    }

    public function closeRegister($rid, $user_id, $data)
    {
        if (!$rid) {
            $rid = $this->session->userdata('register_id');
        }
        if (!$user_id) {
            $user_id = $this->session->userdata('user_id');
        }
        if ($data['transfer_opened_bills'] == -1) {
            $this->db->delete('suspended_bills', array('created_by' => $user_id));
        } elseif ($data['transfer_opened_bills'] != 0) {
            $this->db->update('suspended_bills', array('created_by' => $data['transfer_opened_bills']), array('created_by' => $user_id));
        }
        if ($this->db->update('pos_register', $data, array('id' => $rid, 'user_id' => $user_id))) {
            return true;
        }
        return FALSE;
    }

    public function getUsers()
    {
        $q = $this->db->get_where('users', array('company_id' => NULL));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    public function getUsersById($id = NULL)
    {
        $q = $this->db->get_where('users', array('company_id' => NULL, 'id' => $id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    public function getProductsByCode($code)
    {
        $this->db->like('code', $code, 'both')->order_by("code");
        $q = $this->db->get('products');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    public function getSESuspend($code, $warehouse_id)
    {
        $this->db->select('product_id')
            ->join('suspended_bills', 'suspended_bills.id=suspended_items.suspend_id', 'left')
            ->join('suspended', 'erp_suspended.name=suspended_bills.suspend_name', 'left');
        $q = $this->db->get_where("suspended_items", array('suspended.id' => $code));
        if ($q->num_rows() > 0) {
            return $q->result_array();
        }

        return FALSE;
    }

    public function getSEProduct($code, $warehouse_id)
    {
        $exp = explode(',',$code);
        foreach($exp as $id){
            $this->db->select('products.id, code, name, type, warehouses_products.quantity, price, tax_rate, tax_method,image')
                ->join('warehouses_products', 'warehouses_products.product_id=products.id', 'left')
                ->group_by('products.id');
            $q = $this->db->get_where("products", array('products.id' => $id));
            $data[] = $q->row();
        }
        return $data;
        return FALSE;
    }

    public function getWHProduct($code, $warehouse_id)
    {
        $this->db->select('products.id,products.cost, products.code, products.name, products.type,categories.type AS cate_type,warehouses_products.product_id, warehouses_products.quantity, warehouses_products.quantity as qoh, price, tax_rate, tax_method,products.image,subcategory_id,cf1, COALESCE((SELECT GROUP_CONCAT(sp.`serial_number`) 
					FROM erp_serial as sp
				 WHERE sp.product_id='.$this->db->dbprefix('products').'.id
				), "") as sep')
            ->join('categories', 'categories.id=products.category_id', 'left')
            ->join('warehouses_products', 'warehouses_products.product_id=products.id', 'left')
            ->where('warehouses_products.warehouse_id', $warehouse_id)
            ->group_by('products.id');
        $q = $this->db->get_where("products", array('products.code' => $code));
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function getProductOptions($product_id, $warehouse_id)
    {
        $this->db->select('product_variants.id as id, product_variants.name as name, product_variants.price as price, product_variants.quantity as total_quantity, warehouses_products_variants.quantity as quantity,product_variants.qty_unit as qty_unit')
            ->join('warehouses_products_variants', 'warehouses_products_variants.option_id=product_variants.id', 'left')
            ->where('product_variants.product_id', $product_id)
            //  ->where('warehouses_products_variants.warehouse_id', $warehouse_id)
            ->group_by('product_variants.id');
        if(! $this->Settings->overselling) {
            $this->db->where('warehouses_products_variants.quantity >', 0);
        }
        $q = $this->db->get('product_variants');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getProductComboItems($pid, $warehouse_id)
    {
        $this->db->select('products.id as id, combo_items.item_code as code, combo_items.quantity as qty, products.name as name, products.type as type, warehouses_products.quantity as quantity')
            ->join('products', 'products.code=combo_items.item_code', 'left')
            ->join('warehouses_products', 'warehouses_products.product_id=products.id', 'left')
            ->where('warehouses_products.warehouse_id', $warehouse_id)
            ->group_by('combo_items.id');
        $q = $this->db->get_where('combo_items', array('combo_items.product_id' => $pid));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
        return FALSE;
    }

    public function updateOptionQuantity($option_id, $quantity)
    {
        if ($option = $this->getProductOptionByID($option_id)) {
            $nq = $option->quantity - $quantity;
            if ($this->db->update('product_variants', array('quantity' => $nq), array('id' => $option_id))) {
                return TRUE;
            }
        }
        return FALSE;
    }

    public function addOptionQuantity($option_id, $quantity)
    {
        if ($option = $this->getProductOptionByID($option_id)) {
            $nq = $option->quantity + $quantity;
            if ($this->db->update('product_variants', array('quantity' => $nq), array('id' => $option_id))) {
                return TRUE;
            }
        }
        return FALSE;
    }

    public function getProductOptionByID($id)
    {
        $q = $this->db->get_where('product_variants', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getPurchasedItems($product_id, $warehouse_id, $option_id = NULL)
    {
        $orderby = ($this->Settings->accounting_method == 1) ? 'asc' : 'desc';
        $this->db->select('id, quantity, quantity_balance, net_unit_cost, item_tax');
        $this->db->where('product_id', $product_id)->where('warehouse_id', $warehouse_id)->where('quantity_balance !=', 0);
        if ($option_id) {
            $this->db->where('option_id', $option_id);
        }
        $this->db->group_by('id');
        $this->db->order_by('date', $orderby);
        $this->db->order_by('purchase_id', $orderby);
        $q = $this->db->get('purchase_items');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getProductWarehouseOptionQty($option_id, $warehouse_id)
    {
        $q = $this->db->get_where('warehouses_products_variants', array('option_id' => $option_id, 'warehouse_id' => $warehouse_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function updateProductOptionQuantity($option_id, $warehouse_id, $quantity, $product_id)
    {
        if ($option = $this->getProductWarehouseOptionQty($option_id, $warehouse_id)) {
            $nq = $option->quantity - $quantity;
            if ($this->db->update('warehouses_products_variants', array('quantity' => $nq), array('option_id' => $option_id, 'warehouse_id' => $warehouse_id))) {
                $this->site->syncVariantQty($option_id, $warehouse_id);
                return TRUE;
            }
        } else {
            $nq = 0 - $quantity;
            if ($this->db->insert('warehouses_products_variants', array('option_id' => $option_id, 'product_id' => $product_id, 'warehouse_id' => $warehouse_id, 'quantity' => $nq))) {
                $this->site->syncVariantQty($option_id, $warehouse_id);
                return TRUE;
            }
        }
        return FALSE;
    }

    public function addSale($data = array(), $items = array(), $payments = array(), $sid = NULL, $loans = array(), $combine_table = NULL)
    {
        $this->load->model('sales_model');
        if ($data['sale_status'] == 'completed') {
            $cost = $this->site->costing($items);
        }

        foreach($items as $g){
            $totalCostProducts = $this->getTotalCostProducts($g['product_id'], $g['quantity']);
            $product_variants = $this->site->getProductVariant($g['option_id'], $g['product_id']);
            if($product_variants) {
                $data['total_cost'] += $totalCostProducts->total_cost * $product_variants->qty_unit;
            }else {
                $data['total_cost'] += $totalCostProducts->total_cost;
            }
        }

        $real_qty = 0;
        if($loans) {
            $data['grand_total'] = $data['paid'];
            foreach ($loans as $loan) {
                $data['grand_total'] += $loan['principle'];
            }
        }

        if ($data['sale_status'] == 'ordered') {
            $data['payment_status'] = 'due';
        }
        if ($this->db->insert('sales', $data)) {
            $sale_id = $this->db->insert_id();
            if ($this->site->getReference('pos',$data['biller_id']) == $data['reference_no']) {
                $this->site->updateReference('pos',$data['biller_id']);
            }

            $i = 0;
            foreach ($items as $item) {
                $product            = $this->site->getProductByID($item['product_id']);
                $item['unit_cost'] 	= $product->cost;
                $real_qty           = $item['quantity'];
                if ($item['option_id']) {
                    $option = $this->site->getProductVariantOptionIDPID($item['option_id'], $item['product_id']);
                    if ($option->qty_unit > 0) {
                        $qty = $option->qty_unit;
                    }
                    $item['quantity_balance'] = $item['quantity'] * $qty;
                } else {
                    $item['quantity_balance'] = $item['quantity'];
                }
                $item['sale_id'] = $sale_id;
                $this->db->insert('sale_items', $item);
                $sale_item_id = $this->db->insert_id();
                $items[$i]['transaction_type'] 	= 'SALE';
                $items[$i]['transaction_id'] 	= $sale_item_id;
                $items[$i]['status'] 			= ($data['sale_status'] == 'completed'?'received':'');
                if($this->Settings->product_serial == 1){
                    $this->db->update('serial', array('serial_status'=>0), array('product_id'=>$item['product_id'], 'serial_number'=>$item['serial_no']));
                }
                $sale_item_id = $this->db->insert_id();

                if ($data['sale_status'] == 'completed' && $this->site->getProductByID($item['product_id'])) {
                    $item_costs = $this->site->item_costing($item);
                    foreach ($item_costs as $item_cost) {
                        $item_cost['sale_item_id'] = $sale_item_id;
                        $item_cost['sale_id'] = $sale_id;
                        unset($item_cost['product_name']);
                        unset($item_cost['product_type']);
                        if(isset($data['date'])){
                            $item_cost['date'] = $data['date'];
                        }

                        if(! isset($item_cost['pi_overselling'])) {
                            $this->db->insert('costing', $item_cost);
                        }
                    }
                }
                $i++;
            }

            foreach($loans as $loan) {
                $loan['sale_id'] = $sale_id;
                $this->db->insert('loans', $loan);
            }

            $cost = $this->site->costing($items);

            if ($data['sale_status'] == 'completed') {
                $this->site->syncPurchaseItems($cost);
            }

            $msg = array();
            if ($data['sale_status'] == 'completed') {
                if (!empty($payments)) {

                    $paid = 0;

                    foreach ($payments as $payment) {
                        if (!empty($payment) && isset($payment['amount']) && $payment['amount'] > 0) {

                            $payment['sale_id'] = $sale_id;

                            if ($payment['paid_by'] == 'ppp') {
                                $card_info = array("number" => $payment['cc_no'], "exp_month" => $payment['cc_month'], "exp_year" => $payment['cc_year'], "cvc" => $payment['cc_cvv2'], 'type' => $payment['cc_type']);
                                $result = $this->paypal($payment['amount'], $card_info);
                                if (!isset($result['error'])) {
                                    $payment['transaction_id'] = $result['transaction_id'];
                                    $payment['date'] = $this->erp->fld($result['created_at']);
                                    $payment['amount'] = $result['amount'];
                                    $payment['currency'] = $result['currency'];
                                    unset($payment['cc_cvv2']);
                                    $this->db->insert('payments', $payment);
                                    $paid += $payment['amount'];


                                } else {
                                    $msg[] = lang('payment_failed');
                                    if (!empty($result['message'])) {
                                        foreach ($result['message'] as $m) {
                                            $msg[] = '<p class="text-danger">' . $m['L_ERRORCODE'] . ': ' . $m['L_LONGMESSAGE'] . '</p>';
                                        }
                                    } else {
                                        $msg[] = lang('paypal_empty_error');
                                    }
                                }
                            } elseif ($payment['paid_by'] == 'stripe') {
                                $card_info = array("number" => $payment['cc_no'], "exp_month" => $payment['cc_month'], "exp_year" => $payment['cc_year'], "cvc" => $payment['cc_cvv2'], 'type' => $payment['cc_type']);
                                $result = $this->stripe($payment['amount'], $card_info);
                                if (!isset($result['error'])) {
                                    $payment['transaction_id'] = $result['transaction_id'];
                                    $payment['date'] = $this->erp->fld($result['created_at']);
                                    $payment['amount'] = $result['amount'];
                                    $payment['currency'] = $result['currency'];
                                    unset($payment['cc_cvv2']);
                                    $this->db->insert('payments', $payment);
                                    $this->site->updateReference('sp');
                                    $paid += $payment['amount'];
                                } else {
                                    $msg[] = lang('payment_failed');
                                    $msg[] = '<p class="text-danger">' . $result['code'] . ': ' . $result['message'] . '</p>';
                                }
                            } else {

                                if ($payment['paid_by'] == 'gift_card') {
                                    $this->db->update('gift_cards', array('balance' => $payment['pos_balance']), array('card_no' => $payment['cc_no']));
                                }
                                unset($payment['cc_cvv2']);
                                $this->db->insert('payments', $payment);
                                $this->site->updateReference('sp', $payment['biller_id']);
                                $paid += $payment['amount'];
                            }

                            if($payment['paid_by'] == 'deposit'){
                                $deposit = $this->site->getDepositByCompanyID($data['customer_id']);
                                $deposit_balance = $deposit->deposit_amount;
                                $deposit_balance = $deposit_balance - abs($payment['pos_paid']);
                                if($this->db->update('companies', array('deposit_amount' => $deposit_balance), array('id' => $data['customer_id']))){

                                }
                            }

                            if($payment['paid_by'] == 'Voucher'){
                                if ($this->site->getReference('sp') == $payment['reference_no']) {
                                    $this->site->updateReference('sp');
                                }
                            }
                        }
                    }
                    $this->site->syncSalePaymentsCur($sale_id);
                }
            }

            if ($data['sale_status'] != 'ordered') {
                $this->site->syncQuantity($sale_id);
            }

            if ($sid || $combine_table) {
                if ($this->pos_settings->show_suspend_bar == 1) {
                    if($combine_table){
                        $table_id = explode('_', $combine_table);
                        $this->deleteBillByTableId($table_id);
                    }else{
                        $this->deleteBill($sid);
                    }
                }
            }

            if ($data['sale_status'] != 'order') {
                $this->erp->update_award_points($data['grand_total'], $data['customer_id'], $data['created_by'], NULL ,$data['saleman_by']);
            }
            return array('sale_id' => $sale_id, 'message' => $msg);
        }
        return false;
    }

    public function calculateSaleTotalsReturn($id, $return_id, $surcharge,$payment_status =NULL)
    {
        $sale = $this->getInvoiceByID($id);
        $items = $this->getAllInvoiceItems($id);

        if (!empty($items)) {
            $this->erp->update_award_points($sale->grand_total, $sale->customer_id, $sale->created_by, TRUE);
            $total = 0;
            $product_tax = 0;
            $order_tax = 0;
            $product_discount = 0;
            $order_discount = 0;
            $total_items = 0;
            foreach ($items as $item) {
                $total_items += $item->quantity;
                $product_tax += $item->item_tax;
                $product_discount += $item->item_discount;
                $total += $item->net_unit_price * $item->quantity;
            }
            if ($sale->order_discount_id) {
                $percentage = '%';
                $order_discount_id = $sale->order_discount_id;
                $opos = strpos($order_discount_id, $percentage);
                if ($opos !== false) {
                    $ods = explode("%", $order_discount_id);
                    $order_discount = (($total + $product_tax) * (Float)($ods[0])) / 100;
                } else {
                    $order_discount = $order_discount_id;
                }
            }
            if ($sale->order_tax_id) {
                $order_tax_id = $sale->order_tax_id;
                if ($order_tax_details = $this->site->getTaxRateByID($order_tax_id)) {
                    if ($order_tax_details->type == 2) {
                        $order_tax = $order_tax_details->rate;
                    }
                    if ($order_tax_details->type == 1) {
                        $order_tax = (($total + $product_tax - $order_discount) * $order_tax_details->rate) / 100;
                    }
                }
            }
            $total_discount = $order_discount + $product_discount;
            $total_tax = $product_tax + $order_tax;
            $grand_total = $total + $total_tax + $sale->shipping - $order_discount + $surcharge;
            if($payment_status){
                $data = array(
                    //'total' => $total,
                    //'product_discount' => $product_discount,
                    //'order_discount' => $order_discount,
                    //'total_discount' => $total_discount,
                    //'product_tax' => $product_tax,
                    //'order_tax' => $order_tax,
                    //'total_tax' => $total_tax,
                    //'grand_total' => $grand_total,
                    //'total_items' => $total_items,
                    'return_id' => $return_id,
                    //'surcharge' => $surcharge,
                    'payment_status' => $payment_status
                );
            }else{
                $data = array(
                    //'total' => $total,
                    //'product_discount' => $product_discount,
                    //'order_discount' => $order_discount,
                    //'total_discount' => $total_discount,
                    //'product_tax' => $product_tax,
                    //'order_tax' => $order_tax,
                    //'total_tax' => $total_tax,
                    //'grand_total' => $grand_total,
                    //'total_items' => $total_items,
                    'return_id' => $return_id,
                    //'surcharge' => $surcharge
                );
            }

            if ($this->db->update('sales', $data, array('id' => $id))) {
                $this->erp->update_award_points($data['grand_total'], $sale->customer_id, $sale->created_by);
                return true;
            }
        } else {
            //$this->db->delete('sales', array('id' => $id));
            //$this->db->delete('payments', array('sale_id' => $id, 'return_id !=' => $return_id));
        }
        return FALSE;
    }

    public function getProductByCode($code)
    {
        $q = $this->db->get_where('products', array('code' => $code), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getProductByName($name)
    {
        $q = $this->db->get_where('products', array('name' => $name), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function getAllBillerCompanies()
    {
        $q = $this->db->get_where('companies', array('group_name' => 'biller'));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }

    public function getAllCustomerCompanies()
    {
        $q = $this->db->get_where('companies', array('group_name' => 'customer'));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }

    public function getCompanyByID($id)
    {

        $q = $this->db->get_where('companies', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function getAllProducts()
    {
        $q = $this->db->query('SELECT * FROM products ORDER BY id');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }

    public function getProductByID($id)
    {

        $q = $this->db->get_where('products', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function getAllTaxRates()
    {
        $q = $this->db->get('tax_rates');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }

    public function getTaxRateByID($id)
    {
        $q = $this->db->get_where('tax_rates', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function updateProductQuantity($product_id, $warehouse_id, $quantity)
    {

        if ($this->addQuantity($product_id, $warehouse_id, $quantity)) {
            return true;
        }

        return false;
    }

    public function addQuantity($product_id, $warehouse_id, $quantity)
    {
        if ($warehouse_quantity = $this->getProductQuantity($product_id, $warehouse_id)) {
            $new_quantity = $warehouse_quantity['quantity'] - $quantity;
            if ($this->updateQuantity($product_id, $warehouse_id, $new_quantity)) {
                $this->site->syncProductQty($product_id, $warehouse_id);
                return TRUE;
            }
        } else {
            if ($this->insertQuantity($product_id, $warehouse_id, -$quantity)) {
                $this->site->syncProductQty($product_id, $warehouse_id);
                return TRUE;
            }
        }
        return FALSE;
    }

    public function insertQuantity($product_id, $warehouse_id, $quantity)
    {
        if ($this->db->insert('warehouses_products', array('product_id' => $product_id, 'warehouse_id' => $warehouse_id, 'quantity' => $quantity))) {
            return true;
        }
        return false;
    }

    public function updateQuantity($product_id, $warehouse_id, $quantity)
    {
        if ($this->db->update('warehouses_products', array('quantity' => $quantity), array('product_id' => $product_id, 'warehouse_id' => $warehouse_id))) {
            return true;
        }
        return false;
    }

    public function getProductQuantity($product_id, $warehouse)
    {
        $q = $this->db->get_where('warehouses_products', array('product_id' => $product_id, 'warehouse_id' => $warehouse), 1);
        if ($q->num_rows() > 0) {
            return $q->row_array(); //$q->row();
        }
        return FALSE;
    }

    public function getItemByID($id)
    {
        $q = $this->db->get_where('sale_items', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getAllSales()
    {
        $q = $this->db->get('sales');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function sales_count()
    {
        return $this->db->count_all("sales");
    }

    public function fetch_sales($limit, $start)
    {
        $this->db->limit($limit, $start);
        $this->db->order_by("id", "desc");
        $query = $this->db->get("sales");

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }

    public function getAllInvoiceItems($sale_id)
    {
        $this->db->select('sale_items.*, tax_rates.code as tax_code, tax_rates.name as tax_name, tax_rates.rate as tax_rate, product_variants.name as variant, products.name_kh,products.id AS pro_id,products.name AS pro_name,products.category_id AS cat_id,
	 categories.name As cat_name')
            ->join('tax_rates', 'tax_rates.id=sale_items.tax_rate_id', 'left')
            ->join('products', 'sale_items.product_id = products.id', 'left')
            ->join('categories', 'products.category_id = categories.id', 'left')
            ->join('product_variants', 'product_variants.id=sale_items.option_id', 'left')
            ->group_by('sale_items.id')
            ->order_by('id', 'asc');
        $q = $this->db->get_where('sale_items', array('sale_id' => $sale_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getSuspendedSaleItems($id)
    {
        $q = $this->db->get_where('suspended_items', array('suspend_id' => $id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }

    public function getSuspendedSales($user_id = NULL)
    {
        if (!$user_id) {
            $user_id = $this->session->userdata('user_id');
        }
        $q = $this->db->get_where('suspended_bills', array('created_by' => $user_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }

    public function getOpenBillByID($id)
    {
        $q = $this->db->get_where('suspended_bills', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getSuspended($id)
    {
        $this->db->select('companies.name, suspended.customer_id')
            ->join('suspended', 'suspended.id=suspended_bills.suspend_id', 'left')
            ->join('companies', 'companies.id=suspended.customer_id', 'left')
            ->where('suspended_bills.suspend_id', $id);

        $q = $this->db->get('suspended_bills');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getInvoiceByID($id)
    {
        $q = $this->db->get_where('sales', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function getInvoicePosByID($id)
    {
        $this->db->select('sales.*, users.username,erp_tax_rates.name AS tax,erp_payments.paid_by,erp_users.phone,erp_payments.cheque_no,erp_payments.cc_no,erp_payments.cc_type,erp_warehouses.name AS ware, erp_payments.pos_balance, erp_payments.pos_paid_other_rate,user2.username AS customer_name');
        $this->db->join('users','users.id = sales.created_by', 'left');
        $this->db->join('erp_tax_rates','erp_sales.order_tax_id = erp_tax_rates.id', 'left');
        $this->db->join('erp_payments','erp_payments.sale_id = erp_sales.id', 'left');
        $this->db->join('erp_warehouses','erp_sales.warehouse_id = erp_warehouses.id', 'left');
        $this->db->join('erp_users AS user2','erp_sales.customer_id = user2.id', 'left');
        $this->db->from('sales');
        $this->db->where(array('sales.id' => $id),1);
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function bills_count()
    {
        if (!$this->Owner && !$this->Admin) {
            $this->db->where('created_by', $this->session->userdata('user_id'));
        }
        return $this->db->count_all_results("suspended_bills");
    }

    public function fetch_bills($limit, $start)
    {
        if (!$this->Owner && !$this->Admin) {
            $this->db->where('created_by', $this->session->userdata('user_id'));
        }
        $this->db->limit($limit, $start);
        $this->db->order_by("id", "asc");
        $query = $this->db->get("suspended_bills");

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }

    public function getTodaySales()
    {
        $date = date('Y-m-d 00:00:00');
        $this->db->select('SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( amount, 0 ) ) AS paid', FALSE)
            ->join('sales', 'sales.id=payments.sale_id', 'left')
            ->where('payments.type', 'received')->where('payments.date >', $date);

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getCosting()
    {
        $date = date('Y-m-d');
        $this->db->select('SUM( COALESCE( purchase_unit_cost, 0 ) * quantity ) AS cost, SUM( COALESCE( sale_unit_price, 0 ) * quantity ) AS sales, SUM( COALESCE( purchase_net_unit_cost, 0 ) * quantity ) AS net_cost, SUM( COALESCE( sale_net_unit_price, 0 ) * quantity ) AS net_sales', FALSE)
            ->where('date', $date);

        $q = $this->db->get('costing');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getTodayCCSales()
    {
        $date = date('Y-m-d 00:00:00');
        $this->db->select('COUNT(' . $this->db->dbprefix('payments') . '.id) as total_cc_slips, SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( amount, 0 ) ) AS paid', FALSE)
            ->join('sales', 'sales.id=payments.sale_id', 'left')
            ->where('payments.type', 'received')->where('payments.date >', $date)->where('paid_by', 'CC');

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getTodayCashSales()
    {
        $date = date('Y-m-d 00:00:00');
        $this->db->select('SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( amount, 0 ) ) AS paid', FALSE)
            ->join('sales', 'sales.id=payments.sale_id', 'left')
            ->where('payments.type', 'received')->where('payments.date >', $date)->where('paid_by', 'cash');

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getTodayRefunds()
    {
        $date = date('Y-m-d 00:00:00');
        $this->db->select('SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( amount, 0 ) ) AS returned', FALSE)
            ->join('return_sales', 'return_sales.id=payments.return_id', 'left')
            ->where('payments.type', 'returned')->where('payments.date >', $date);

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getTodayExpenses()
    {
        $date = date('Y-m-d 00:00:00');
        $this->db->select('SUM( COALESCE( amount, 0 ) ) AS total', FALSE)
            ->where('date >', $date);

        $q = $this->db->get('expenses');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getTodayCashRefunds()
    {
        $date = date('Y-m-d 00:00:00');
        $this->db->select('SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( amount, 0 ) ) AS returned', FALSE)
            ->join('return_sales', 'return_sales.id=payments.return_id', 'left')
            ->where('payments.type', 'returned')->where('payments.date >', $date)->where('paid_by', 'cash');

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getTodayChSales()
    {
        $date = date('Y-m-d 00:00:00');
        $this->db->select('COUNT(' . $this->db->dbprefix('payments') . '.id) as total_cheques, SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( amount, 0 ) ) AS paid', FALSE)
            ->join('sales', 'sales.id=payments.sale_id', 'left')
            ->where('payments.type', 'received')->where('payments.date >', $date)->where('paid_by', 'Cheque');

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getTodayPPPSales()
    {
        $date = date('Y-m-d 00:00:00');
        $this->db->select('COUNT(' . $this->db->dbprefix('payments') . '.id) as total_cheques, SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( amount, 0 ) ) AS paid', FALSE)
            ->join('sales', 'sales.id=payments.sale_id', 'left')
            ->where('payments.type', 'received')->where('payments.date >', $date)->where('paid_by', 'ppp');

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    /*
	public function getTotalCostProducts($sale_id){
		$this->db->select('SUM(purchase_unit_cost*quantity) AS total_cost ');
		$q = $this->db->get_where('costing', array('sale_id' => $sale_id));
		if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
    */

    public function getTotalCostProducts($product_id, $quantity){
        $this->db->select("SUM(cost* CASE WHEN $quantity <> 0 THEN $quantity ELSE 0 END ) AS total_cost ");
        $q = $this->db->get_where('products', array('id' => $product_id));
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getTodayStripeSales()
    {
        $date = date('Y-m-d 00:00:00');
        $this->db->select('COUNT(' . $this->db->dbprefix('payments') . '.id) as total_cheques, SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( amount, 0 ) ) AS paid', FALSE)
            ->join('sales', 'sales.id=payments.sale_id', 'left')
            ->where('payments.type', 'received')->where('payments.date >', $date)->where('paid_by', 'stripe');

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getRegisterSales($date, $user_id = NULL)
    {
        if (!$date) {
            $date = $this->session->userdata('register_open_time');
        }
        if (!$user_id) {
            $user_id = $this->session->userdata('user_id');
        }
        $this->db->select('SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( amount, 0 ) ) AS paid', FALSE)
            ->join('sales', 'sales.id=payments.sale_id', 'left')
            ->where('payments.type', 'received')->where('payments.date >', $date);
        $this->db->where('payments.created_by', $user_id);

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }


    public function getRegisterCCSales($date, $user_id = NULL)
    {
        if (!$date) {
            $date = $this->session->userdata('register_open_time');
        }
        if (!$user_id) {
            $user_id = $this->session->userdata('user_id');
        }
        $this->db->select('COUNT(' . $this->db->dbprefix('payments') . '.id) as total_cc_slips, SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( amount, 0 ) ) AS paid', FALSE)
            ->join('sales', 'sales.id=payments.sale_id', 'left')
            ->where('payments.type', 'received')
            ->where('payments.date >', $date)
            ->where('paid_by', 'CC');
        $this->db->where('payments.created_by', $user_id);

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getRegisterCashSales($date, $user_id = NULL)
    {
        if (!$date) {
            $date = $this->session->userdata('register_open_time');
        }
        if (!$user_id) {
            $user_id = $this->session->userdata('user_id');
        }
        $this->db->select('SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( amount, 0 ) ) AS paid', FALSE)
            ->join('sales', 'sales.id=payments.sale_id')
            ->where('payments.type', 'received')->where('payments.date >', $date)->where('paid_by', 'cash');
        $this->db->where('payments.created_by', $user_id);

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getRegisterRefunds($date, $user_id = NULL)
    {
        if (!$date) {
            $date = $this->session->userdata('register_open_time');
        }
        if (!$user_id) {
            $user_id = $this->session->userdata('user_id');
        }
        $this->db->select('SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( amount, 0 ) ) AS returned', FALSE)
            ->join('return_sales', 'return_sales.id=payments.return_id', 'left')
            ->where('type', 'returned')->where('payments.date >', $date);
        $this->db->where('payments.created_by', $user_id);

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getRegisterCashRefunds($date, $user_id = NULL)
    {
        if (!$date) {
            $date = $this->session->userdata('register_open_time');
        }
        if (!$user_id) {
            $user_id = $this->session->userdata('user_id');
        }
        $this->db->select('SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( amount, 0 ) ) AS returned', FALSE)
            ->join('return_sales', 'return_sales.id=payments.return_id', 'left')
            ->where('type', 'returned')->where('payments.date >', $date)->where('paid_by', 'cash');
        $this->db->where('payments.created_by', $user_id);

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getRegisterExpenses($date, $user_id = NULL)
    {
        if (!$date) {
            $date = $this->session->userdata('register_open_time');
        }
        if (!$user_id) {
            $user_id = $this->session->userdata('user_id');
        }
        $this->db->select('SUM( COALESCE( amount, 0 ) ) AS total', FALSE)
            ->where('date >', $date);
        $this->db->where('created_by', $user_id);

        $q = $this->db->get('expenses');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getRegisterChSales($date, $user_id = NULL)
    {
        if (!$date) {
            $date = $this->session->userdata('register_open_time');
        }
        if (!$user_id) {
            $user_id = $this->session->userdata('user_id');
        }
        $this->db->select('COUNT(' . $this->db->dbprefix('payments') . '.id) as total_cheques, SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( amount, 0 ) ) AS paid', FALSE)
            ->join('sales', 'sales.id=payments.sale_id', 'left')
            ->where('payments.type', 'received')->where('payments.date >', $date)->where('paid_by', 'Cheque');
        $this->db->where('payments.created_by', $user_id);

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getRegisterMemSales($date, $user_id = NULL)
    {
        if (!$date) {
            $date = $this->session->userdata('register_open_time');
        }
        if (!$user_id) {
            $user_id = $this->session->userdata('user_id');
        }

        $this->db->select('COUNT(' . $this->db->dbprefix('payments') . '.id) as total_mem, SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( amount, 0 ) ) AS paid', FALSE)
            ->join('sales', 'sales.id=payments.sale_id', 'left')
            ->where('payments.type', 'received')->where('payments.date >', $date)->where('paid_by', 'gift_card');
        $this->db->where('payments.created_by', $user_id);

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getRegisterVoucherSales($date, $user_id = NULL)
    {
        if (!$date) {
            $date = $this->session->userdata('register_open_time');
        }
        if (!$user_id) {
            $user_id = $this->session->userdata('user_id');
        }
        $this->db->select('COUNT(' . $this->db->dbprefix('payments') . '.id) as total_voucher, SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( amount, 0 ) ) AS paid', FALSE)
            ->join('sales', 'sales.id=payments.sale_id', 'left')
            ->where('payments.type', 'received')->where('payments.date >', $date)->where('paid_by', 'Voucher');
        $this->db->where('payments.created_by', $user_id);

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }



    public function getRegisterPPPSales($date, $user_id = NULL)
    {
        if (!$date) {
            $date = $this->session->userdata('register_open_time');
        }
        if (!$user_id) {
            $user_id = $this->session->userdata('user_id');
        }
        $this->db->select('COUNT(' . $this->db->dbprefix('payments') . '.id) as total_cheques, SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( amount, 0 ) ) AS paid', FALSE)
            ->join('sales', 'sales.id=payments.sale_id', 'left')
            ->where('payments.type', 'received')->where('payments.date >', $date)->where('paid_by', 'ppp');
        $this->db->where('payments.created_by', $user_id);

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getRegisterStripeSales($date, $user_id = NULL)
    {
        if (!$date) {
            $date = $this->session->userdata('register_open_time');
        }
        if (!$user_id) {
            $user_id = $this->session->userdata('user_id');
        }
        $this->db->select('COUNT(' . $this->db->dbprefix('payments') . '.id) as total_cheques, SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( amount, 0 ) ) AS paid', FALSE)
            ->join('sales', 'sales.id=payments.sale_id', 'left')
            ->where('payments.type', 'received')->where('payments.date >', $date)->where('paid_by', 'stripe');
        $this->db->where('payments.created_by', $user_id);

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getDailySales($year, $month)
    {

        $myQuery = "SELECT DATE_FORMAT( date,  '%e' ) AS date, SUM( COALESCE( total, 0 ) ) AS total
        FROM " . $this->db->dbprefix('sales') . "
        WHERE DATE_FORMAT( date,  '%Y-%m' ) =  '{$year}-{$month}'
        GROUP BY DATE_FORMAT( date,  '%e' )";
        $q = $this->db->query($myQuery, false);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }

    public function getMonthlySales($year)
    {

        $myQuery = "SELECT DATE_FORMAT( date,  '%c' ) AS date, SUM( COALESCE( total, 0 ) ) AS total
        FROM " . $this->db->dbprefix('sales') . "
        WHERE DATE_FORMAT( date,  '%Y' ) =  '{$year}'
        GROUP BY date_format( date, '%c' ) ORDER BY date_format( date, '%c' ) ASC";
        $q = $this->db->query($myQuery, false);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }
    public function suspendItem_($sData, $did = "", $items = array()){
        if ( $this->db->update('suspended_bills', $sData, array('id' => $did)) && $this->db->delete('suspended_items', array('suspend_id' => $did)) ) {

            $addOn = array('suspend_id' => $did);
            end($addOn);
            foreach ($items as &$var) {
                $var = array_merge($addOn, $var);
            }
            if ($this->db->insert_batch('suspended_items', $items)) {
                return TRUE;
            }
        }
        return TRUE;
    }
    public function suspendSale($data = array(), $items = array(), $did = NULL)
    {
        $suspend_id = 0;
        $have_item  = 0;

        if(($suspend_id == $did) || $did-0==0) {
            $sData = array(
                'count' => $data['total_items'],
                'biller_id' => $data['biller_id'],
                'customer_id' => $data['customer_id'],
                'warehouse_id' => $data['warehouse_id'],
                'customer' => $data['customer'],
                'date' => $data['date'],
                'suspend_id' => $data['suspend_id'],
                'suspend_name' => $data['suspend_name'],
                'total' => $data['grand_total'],
                'order_tax_id' => $data['order_tax_id'],
                'order_discount_id' => $data['order_discount_id'],
                'created_by' => $this->session->userdata('user_id')
            );
        }else{
            $sData = array(
                'count' => 0,
                'biller_id' => $data['biller_id'],
                'customer_id' => $data['customer_id'],
                'warehouse_id' => $data['warehouse_id'],
                'customer' => $data['customer'],
                'date' => $data['date'],
                'suspend_id' => $data['suspend_id'],
                'suspend_name' => $data['suspend_name'],
                'total' => 0,
                'order_tax_id' => $data['order_tax_id'],
                'order_discount_id' => $data['order_discount_id'],
                'created_by' => $this->session->userdata('user_id')
            );
        }

        /* echo json_encode( $sData);
         exit();*/

        for($i = 0 ; $i < count($items); $i++){
            unset($items[$i]['expiry']);
            unset($items[$i]['expiry_id']);
        }
        if (false) {
//        if ($did) {
            $bi = $this->getOpenBillByID($did);
            $old_sus_id = $bi->suspend_id;
            $suspend_id = $bi->suspend_id;
            if ($this->db->update('suspended_bills', $sData, array('id' => $did)) && $this->db->delete('suspended_items', array('suspend_id' => $did))) {
                $addOn = array('suspend_id' => $did);
                end($addOn);
                foreach ($items as &$var) {
                    $var = array_merge($addOn, $var);
                }
                if ($this->db->insert_batch('suspended_items', $items)) {
                    if($this->db->update('suspended', array('status' => 1,'customer_id'=>$data['customer_id']), array('id' => $data['suspend_id']))){
                        $this->db->update('suspended', array('status' => 0,'customer_id'=>null), array('id' => $old_sus_id ));
                    }
                    return TRUE;
                }
            }
        } else {
            if ($this->db->insert('suspended_bills', $sData)) {
                $suspend_id = $this->db->insert_id();
                $this->db->update('suspended', array('status' => 1,'customer_id'=>$data['customer_id']), array('id' =>$data['suspend_id'] ));
                $addOn = array('suspend_id' => $suspend_id);
                end($addOn);
                foreach ($items as &$var) {
                    $var = array_merge($addOn, $var);
                }

                if(($suspend_id == $did) || $did-0==0) {
                    if (isset($items)) {
                        if (count($items) > 0) {
                            if ($this->db->insert_batch('suspended_items', $items)) {
                                $have_item = 1;
                            }
                        }
                    }
                }
                return array('suppend_id' => $suspend_id, 'have_item' => $have_item);
            }
        }
        return array('suppend_id' => $suspend_id, 'have_item' => $have_item);
    }

    public function updateSuspendactive($id){
        $data = array(
            'inactive' => 1
        );
        $this->db->where('id', $id);
        if ($this->db->update('suspended', $data)) {
            return true;
        }
        return false;
    }

    public function getSuspendID($id){
        $this->db->select('suspend_id');
        $q = $this->db->get_where('suspended_bills', array('id' => $id));
        if ($q->num_rows() > 0) {
            $r = $q->row();
            return $r->suspend_id;
        }
        return false;
    }

    public function deleteBill($id)
    {
        $suspend_id = $this->getSuspendID($id);
        if ($this->db->delete('suspended_items', array('suspend_id' => $id)) && $this->db->delete('suspended_bills', array('id' => $id))) {
            $this->db->update('suspended', array('status' => 0), array('id' => $suspend_id));
            return true;
        }
        return FALSE;
    }

    public function deleteBillByTableId($id = array())
    {
        if($id){
            for($i = 0 ; $i < sizeof($id); $i++){
                $suspend_id = $this->getSuspendID($id[$i]);
                if ($this->db->delete('suspended_items', array('suspend_id' => $id[$i])) && $this->db->delete('suspended_bills', array('id' => $id[$i]))) {
                    $this->db->update('suspended', array('status' => 0), array('id' => $suspend_id));
                }
            }
            return true;
        }
        return FALSE;
    }
    public function deleteBillRoom($id)
    {
        if ($this->db->delete('suspended_items', array('suspend_id' => $id)) && $this->db->delete('suspended_bills', array('suspend_id' => $id))) {
            $this->db->update('suspended', array('status' => 0, 'startdate' => '', 'enddate' => '', 'note' => ''), array('id' => $id));
            return true;
        }
        return FALSE;
    }

    public function getSubCategoriesByCategoryID($category_id)
    {
        $this->db->order_by('name');
        $q = $this->db->get_where("subcategories", array('category_id' => $category_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }

        return FALSE;
    }

    public function getInvoicePayments($sale_id)
    {
        $q = $this->db->get_where("payments", array('sale_id' => $sale_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getInvoicePaymentsPOS($sale_id)
    {
        $this->db->where('return_id IS NULL');
        $q = $this->db->get_where("payments", array('sale_id' => $sale_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }

        return FALSE;
    }

    function stripe($amount = 0, $card_info = array(), $desc = '')
    {
        $this->load->model('stripe_payments');
        //$card_info = array( "number" => "4242424242424242", "exp_month" => 1, "exp_year" => 2016, "cvc" => "314" );
        //$amount = $amount ? $amount*100 : 3000;
        $amount = $amount * 100;
        if ($amount && !empty($card_info)) {
            $token_info = $this->stripe_payments->create_card_token($card_info);
            if (!isset($token_info['error'])) {
                $token = $token_info->id;
                $data = $this->stripe_payments->insert($token, $desc, $amount, $this->default_currency->code);
                if (!isset($data['error'])) {
                    $result = array('transaction_id' => $data->id,
                        'created_at' => date($this->dateFormats['php_ldate'], $data->created),
                        'amount' => ($data->amount / 100),
                        'currency' => strtoupper($data->currency)
                    );
                    return $result;
                } else {
                    return $data;
                }
            } else {
                return $token_info;
            }
        }
        return false;
    }

    function paypal($amount = NULL, $card_info = array(), $desc = '')
    {
        $this->load->model('paypal_payments');
        //$card_info = array( "number" => "5522340006063638", "exp_month" => 2, "exp_year" => 2016, "cvc" => "456", 'type' => 'MasterCard' );
        //$amount = $amount ? $amount : 30.00;
        if ($amount && !empty($card_info)) {
            $data = $this->paypal_payments->Do_direct_payment($amount, $this->default_currency->code, $card_info, $desc);
            if (!isset($data['error'])) {
                $result = array('transaction_id' => $data['TRANSACTIONID'],
                    'created_at' => date($this->dateFormats['php_ldate'], strtotime($data['TIMESTAMP'])),
                    'amount' => $data['AMT'],
                    'currency' => strtoupper($data['CURRENCYCODE'])
                );
                return $result;
            } else {
                return $data;
            }
        }
        return false;
    }

    public function addPayment($payment = array())
    {
        //$this->erp->print_arrays($payment);
        $payment_id = 0;
        if (isset($payment['sale_id']) && isset($payment['paid_by']) && isset($payment['amount'])) {
            $payment['pos_paid'] = $payment['amount'];
            $inv = $this->getInvoiceByID($payment['sale_id']);
            $paid = $inv->paid + $payment['amount'];
            if ($payment['paid_by'] == 'ppp') {
                $card_info = array("number" => $payment['cc_no'], "exp_month" => $payment['cc_month'], "exp_year" => $payment['cc_year'], "cvc" => $payment['cc_cvv2'], 'type' => $payment['cc_type']);
                $result = $this->paypal($payment['amount'], $card_info);
                if (!isset($result['error'])) {
                    $payment['transaction_id'] = $result['transaction_id'];
                    $payment['date'] = $this->erp->fld($result['created_at']);
                    $payment['amount'] = $result['amount'];
                    $payment['currency'] = $result['currency'];
                    unset($payment['cc_cvv2']);
                    $this->db->insert('payments', $payment);
                    $payment_id = $this->db->insert_id();
                    $paid += $payment['amount'];
                } else {
                    $msg[] = lang('payment_failed');
                    if (!empty($result['message'])) {
                        foreach ($result['message'] as $m) {
                            $msg[] = '<p class="text-danger">' . $m['L_ERRORCODE'] . ': ' . $m['L_LONGMESSAGE'] . '</p>';
                        }
                    } else {
                        $msg[] = lang('paypal_empty_error');
                    }
                }
            } elseif ($payment['paid_by'] == 'stripe') {
                $card_info = array("number" => $payment['cc_no'], "exp_month" => $payment['cc_month'], "exp_year" => $payment['cc_year'], "cvc" => $payment['cc_cvv2'], 'type' => $payment['cc_type']);
                $result = $this->stripe($payment['amount'], $card_info);
                if (!isset($result['error'])) {
                    $payment['transaction_id'] = $result['transaction_id'];
                    $payment['date'] = $this->erp->fld($result['created_at']);
                    $payment['amount'] = $result['amount'];
                    $payment['currency'] = $result['currency'];
                    unset($payment['cc_cvv2']);
                    $this->db->insert('payments', $payment);
                    $payment_id = $this->db->insert_id();
                    $paid += $payment['amount'];
                } else {
                    $msg[] = lang('payment_failed');
                    $msg[] = '<p class="text-danger">' . $result['code'] . ': ' . $result['message'] . '</p>';
                }
            } else {
                if ($payment['paid_by'] == 'gift_card') {
                    $gc = $this->site->getGiftCardByNO($payment['cc_no']);
                    $this->db->update('gift_cards', array('balance' => ($gc->balance - $payment['amount'])), array('card_no' => $payment['cc_no']));
                }
                unset($payment['cc_cvv2']);
                $this->db->insert('payments', $payment);
                $payment_id = $this->db->insert_id();
                $paid += $payment['amount'];
            }

            if($payment['paid_by'] == 'deposit'){
                $customer_id = '';
                if(isset($_POST['customer'])){
                    $customer_id = $_POST['customer'];
                }
                $deposit = $this->site->getDepositByCompanyID($customer_id);
                $deposit_balance = $deposit->deposit_amount;
                $deposit_balance = $deposit_balance - abs($payment['amount']);
                if($this->db->update('companies', array('deposit_amount' => $deposit_balance), array('id' => $customer_id))){
                    $this->db->update('deposits', array('amount' => $deposit_balance), array('company_id' => customer_id));
                }
            }

            if (!isset($msg)) {
                if ($this->site->getReference('sp',$payment['biller_id']) == $payment['reference_no']) {
                    $this->site->updateReference('sp',$payment['biller_id']);
                }

                $this->site->syncSalePayments($payment['sale_id']);
                return array('status' => 1, 'msg' => '');
            }
            return array('status' => 0, 'msg' => $msg,'payment_id'=>$payment_id);

        }
        return false;
    }

    public function getKHMCurrencyRate(){
        $this->db->where('code', 'KHM');
        $q = $this->db->get('currencies');
        if($q->num_rows() > 0){
            return $q->row();
        }
        return FALSE;
    }

    public function get_suppendName($id){
        $this->db->where('id', $id);
        $q = $this->db->get('suspended_bills');
        if($q->num_rows() > 0){
            return $q->row();
        }
        return FALSE;
    }

    public function delivers_count()
    {
        $this->db->select('COALESCE (CASE WHEN erp_sale_items.quantity > erp_sale_dev_items.quantity THEN "processing" WHEN erp_sale_items.quantity <= erp_sale_dev_items.quantity THEN "completed" ELSE "pending" END, 0) AS `status`')
            ->from('sales')
            ->join('sale_items', 'sale_items.sale_id=sales.id', 'left')
            ->join('sale_dev_items', 'sale_dev_items.sale_id=sale_items.sale_id', 'left')
            ->join('products', 'products.id = sale_items.product_id', 'left')
            ->join('categories', 'categories.id = products.category_id', 'left')
            ->where('categories.auto_delivery',1)
            ->group_by('sales.id');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return false;
    }

    public function getDelivers(){
        $this->db->select('erp_suspended_items.id as idd, product_code, product_name, erp_products.image, erp_suspended_items.quantity, erp_suspended_bills.suspend_name as table ')
            ->from('suspended_items')
            ->join('products', 'products.id = suspended_items.product_id', 'left')
            ->join('suspended_bills', 'suspended_bills.id = suspended_items.suspend_id', 'left')
            ->where('suspended_items.status IS NULL OR suspended_items.status = 0')
            ->limit(18)
            ->group_by('suspended_items.id');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return false;
    }


    public function getQtyByID($id){
        $this->db->select($this->db->dbprefix('sale_items') . ".product_name, product_id, sale_id, unit_price, quantity,".$this->db->dbprefix('sales').".warehouse_id as warehouse")
            ->from('sales')
            ->join('sale_items', 'sale_items.sale_id=sales.id', 'left')
            ->where('sales.id',$id);
        $q = $this->db->get();
        if($q->num_rows() > 0){
            return $q->row();
        }
        return FALSE;
    }

    public function kitchen_complete($id, $data){
        $this->db->where('id', $id);
        if ($this->db->update('suspended_items', $data)) {
            return true;
        }
        return false;
    }

    public function getKitchen($id){
        $this->db->select('erp_suspended_items.id as idd, product_code, product_name, erp_products.image, erp_suspended_items.quantity, erp_suspended_bills.suspend_name as table ')
            ->from('suspended_items')
            ->join('products', 'products.id = suspended_items.product_id', 'left')
            ->join('suspended_bills', 'suspended_bills.id = suspended_items.suspend_id', 'left')
            ->where('erp_suspended_items.id', $id);
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getComplete(){
        $this->db->select('erp_suspended.id, name, floor, erp_suspended_bills.id as idd, erp_suspended_bills.warehouse_id as ware')
            ->from('suspended')
            ->join('suspended_bills', 'erp_suspended.id = erp_suspended_bills.suspend_id', 'right')
            ->where('status', 1);
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return false;
    }

    public function clear_item($id){
        $delete = $this->db->delete('suspended_items', array('id' => $id));
        if($delete){
            return true;
        }
        return false;
    }

    public function getProductNames($term, $limit = 5)
    {

        $this->db->where("name LIKE '%" . $term . "%' OR code LIKE '%" . $term . "%' OR  concat(name, ' (', code, ')') LIKE '%" . $term . "%'");

        $this->db->order_by('code', 'DESC');
        $this->db->limit($limit);
        $q = $this->db->get('products');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function selectProByID($id){
        /*
        $exp = explode(',', $id);
        $imp = array();
        foreach($exp as $ip){
            $imp[] = $ip;
        }
        */
        //$this->db->where_in("id", $imp);
        $this->db->where("id", $id);
        $q = $this->db->get('products');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getPosLayout($id){
        $q = $this->db->get_where('users', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    public function getPrincipleByTypeID($pr_id) {
        $q = $this->db->get_where('principles',array('term_type_id'=>$pr_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getOpenBillByArrayID($id)
    {
        $arr_id = explode('_', $id);
        $this->db->select()
            ->from('suspended_bills')
            ->where_in('id', $arr_id);
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getCategoryByID($id)
    {
        $this->db->select('products.id, categories.code')
            ->from('products')
            ->join('categories', 'categories.id = products.category_id', 'left')
            ->where('products.id', $id);
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function getSuspendedSaleItemsByArr($id)
    {
        $arr_id = explode('_', $id);
        $this->db->select()
            ->from('suspended_items')
            ->where_in('suspend_id', $arr_id);
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }
    public function getsuspended_items($id)
    {
        $this->db->select("suspended_items.*")
            ->from('suspended_items')
            ->where('suspend_id', $id);
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }
    public function getsuspendedNULL($id)
    {
        if(!$this->Owner && !$this->Admin){
            $warehouses = explode(',', $this->session->userdata('warehouse_id'));
        }

        $this->db->select("suspended.*")
            ->from('suspended')
            ->where('id!=', $id);
        if(!$this->Owner && !$this->Admin){
            $this->db->where_in('warehouse_id',$warehouses);
        }
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    public function getsuspendedName($id)
    {
        $this->db->select("erp_suspended.*")
            ->from('erp_suspended')
            ->where('id', $id);
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
    }
    public function getsuspended_itemsByID($id)
    {
        $this->db->select("suspended_items.*")
            ->from('suspended_items')
            ->where('id', $id);
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
    }

    public function getsuspendedBill_itemsByID($id)
    {
        $this->db->select("suspended_bills.*")
            ->from('erp_suspended_bills')
            ->where('id', $id);
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
    }
    public function getSUMsuspended_itemsByID($id)
    {
        $this->db->select("SUM(subtotal) as gtotal")
            ->from('suspended_items')
            ->where('suspend_id', $id);
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
    }
    public function getsuspendedBill_PreByID($id)
    {
        $this->db->select("suspended_bills.*")
            ->from('erp_suspended_bills')
            ->where('suspend_id', $id);
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }
    public function addSeperate($items,$tab,$id,$totaQty){

        if($items && $tab){
            $dataBill = $this->getsuspendedBill_itemsByID($id);
            if($dataBill->id){
                unset($dataBill->id);
                $this->db->update("erp_suspended",array("status"=>"1","customer_id"=>$dataBill->customer_id),array("id"=>$tab));
                $dd = $this->getsuspendedBill_PreByID($tab);
                $suspend_name = $this->getsuspendedName($tab);
                if($dd){
                    //$this->db->insert("erp_suspended_bills",$dataBill);
                    //$billid = $this->db->insert_id();
                    foreach($items as $item){
                        $data = $this->getsuspended_itemsByID($item['id']);
                        if($data->quantity == $item['qty']){
                            $this->db->update("suspended_items",array('suspend_id'=>$dd->id),array("id"=>$item['id']));
                        }else{
                            $data->quantity = $item['qty'];
                            $sub = ($item['qty']*$data->unit_price);
                            $data->subtotal = $data->subtotal - $sub;
                            $data->suspend_id = $dd->id;
                            unset($data->id);
                            if($this->db->insert("suspended_items",$data)){
                                $s = $this->getsuspended_itemsByID($item['id']);
                                $s->subtotal = $s->subtotal - $data->subtotal;
                                $qq = ($s->quantity-$item['qty']);
                                $this->db->update("suspended_items",array('quantity'=>$qq,'subtotal'=>$s->subtotal),array("id"=>$item['id']));
                            }
                        }
                    }
                    $newCount = $dd->count + $totaQty;
                    $data_sum = $this->getSUMsuspended_itemsByID($dd->id);
                    $this->db->update("erp_suspended_bills",array("total"=>($dd->total+$data_sum->gtotal),'suspend_name'=>$suspend_name->name,'count'=>$newCount),array("id"=>$dd->id));
                    $dataBill2 = $this->getsuspendedBill_itemsByID($id);
                    $lastCount = $dataBill2->count - $totaQty;
                    $this->db->update("erp_suspended_bills",array("total"=>($dataBill2->total - $data_sum->gtotal),'count'=>$lastCount),array("id"=>$id));

                }else{

                    $this->db->insert("erp_suspended_bills",$dataBill);
                    $billid = $this->db->insert_id();
                    $sub = 0;
                    $qq = 0;
                    foreach($items as $item){
                        $data = $this->getsuspended_itemsByID($item['id']);
                        unset($data->id);
                        $data->suspend_id = $billid;
                        if($data->quantity == $item['qty']){
                            if($this->db->insert("suspended_items",$data)){
                                $this->db->delete("suspended_items",array("id"=>$item['id']));
                            }
                        }else{
                            $data->quantity = $item['qty'];
                            $sub = ($item['qty']*$data->unit_price);
                            $data->subtotal = $data->subtotal - $sub;
                            if($this->db->insert("suspended_items",$data)){
                                $s = $this->getsuspended_itemsByID($item['id']);
                                $s->subtotal = $s->subtotal - $data->subtotal;
                                $qq = ($s->quantity-$item['qty']);
                                $this->db->update("suspended_items",array('quantity'=>$qq,'subtotal'=>$s->subtotal),array("id"=>$item['id']));
                            }
                        }
                    }

                    $data_sum = $this->getSUMsuspended_itemsByID($billid);
                    $this->db->update("erp_suspended_bills",array("total"=>$data_sum->gtotal,'suspend_id'=>$tab,'suspend_name'=>$suspend_name->name,'count'=>$totaQty),array("id"=>$billid));
                    $dataBill2 = $this->getsuspendedBill_itemsByID($id);
                    $lastCount = $dataBill2->count - $totaQty;
                    $this->db->update("erp_suspended_bills",array("total"=>($dataBill2->total - $data_sum->gtotal),"count"=>$lastCount),array("id"=>$id));
                }
            }
            return true;
        }
        return false;
    }
    public function printed_update($susp_id,$item_id)
    {
        $this->db->select("suspended_items.printed")
            ->from('suspended_items')
            ->where('suspend_id', $susp_id)
            ->where('product_code', $item_id);
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function updateprinted($susp_id,$item_id)
    {
        $this->db->where('suspend_id', $susp_id);
        $this->db->where_in('product_code',$item_id);
        if ($this->db->update('suspended_items',array('printed'=>1))) {
            return true;
        }
        return false;
    }

    public function getCusDetail($customer_id)
    {
        $this->db->select('companies.id as id, customer_groups.order_discount');
        $this->db->join('customer_groups', 'companies.customer_group_id = customer_groups.id', 'left');
        $this->db->where('companies.id = ' . $customer_id);
        $result = $this->db->get('companies')->row();
        return $result;
    }


}
