<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Quotes_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getProductNames($term, $warehouse_id, $limit = 20)
    {
        $this->db->select('products.id, code, name, type, warehouses_products.quantity, warehouses_products.quantity AS qoh, price,cost, tax_rate, tax_method, promotion, promo_price, start_date, end_date, subcategory_id, cf1')
            ->join('warehouses_products', 'warehouses_products.product_id=products.id', 'left')
            ->group_by('products.id');
        // if ($this->Settings->overselling) {
			$this->db->where("warehouses_products.warehouse_id = '" . $warehouse_id . "'");
            $this->db->where("(name LIKE '%" . $term . "%' OR code LIKE '%" . $term . "%' OR  concat(name, ' (', code, ')') LIKE '%" . $term . "%')");
        // } else {
        //     $this->db->where("(products.track_quantity = 0 OR warehouses_products.quantity > 0) AND warehouses_products.warehouse_id = '" . $warehouse_id . "' AND "
        //         . "(name LIKE '%" . $term . "%' OR code LIKE '%" . $term . "%' OR  concat(name, ' (', code, ')') LIKE '%" . $term . "%')");
        // }
        $this->db->limit($limit);
        $q = $this->db->get('products');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }
	 public function getProductNamesDigital($id,$warehouse_id)
    {
		
        $this->db->select('products.id, code, name, type, warehouses_products.quantity, warehouses_products.quantity AS qoh, price,cost, tax_rate, tax_method, promotion, promo_price, start_date, end_date, subcategory_id, cf1,"1" as qty')
            ->join('warehouses_products', 'warehouses_products.product_id=erp_digital_items.digital_pro_id', 'left')
			 ->join('erp_products', 'erp_products.id=erp_digital_items.product_id', 'left')
            ->group_by('erp_digital_items.product_id');
			$this->db->where("warehouses_products.warehouse_id" , $warehouse_id );
            $this->db->where("erp_digital_items.digital_pro_id",$id);
     
        $q = $this->db->get('erp_digital_items');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
		return 0;
    }
	
	public function addDeposit($data, $cdata, $payment = array())
    {
		//$this->erp->print_arrays($data, $cdata, $payment);
        if ($this->db->insert('deposits', $data)) {
				$deposit_id = $this->db->insert_id();
				$this->db->update('companies', $cdata, array('id' => $data['company_id']));
				if($payment){
					$payment['deposit_id'] = $deposit_id;
					if ($this->db->insert('payments', $payment)) {
						if ($this->site->getReference('sp') == $payment['reference_no']) {
							$this->site->updateReference('sp');
						}
						if ($payment['paid_by'] == 'gift_card') {
							$gc = $this->site->getGiftCardByNO($payment['cc_no']);
							$this->db->update('gift_cards', array('balance' => ($gc->balance - $payment['amount'])), array('card_no' => $payment['cc_no']));
						}
						return true;
					}
				}
            return true;
        }
        return false;
    }

    public function getProductByCode($code)
    {
        $q = $this->db->get_where('products', array('code' => $code), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getWHProduct($id)
    {
        $this->db->select('products.id, code, name, warehouses_products.quantity, cost, tax_rate')
            ->join('warehouses_products', 'warehouses_products.product_id=products.id', 'left')
            ->group_by('products.id');
        $q = $this->db->get_where('products', array('warehouses_products.product_id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getItemByID($id)
    {
        $q = $this->db->get_where('quote_items', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getAllQuoteItemsWithDetails($quote_id)
    {
        $this->db->select('quote_items.id, quote_items.product_name, quote_items.product_code, quote_items.quantity, quote_items.serial_no, quote_items.tax, quote_items.unit_price, quote_items.val_tax, quote_items.discount_val, quote_items.gross_total, products.details');
        $this->db->join('products', 'products.id=quote_items.product_id', 'left');
        $this->db->order_by('id', 'asc');
        $q = $this->db->get_where('quotes_items', array('quote_id' => $quote_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    public function getQuoteByID($id=null, $wh=null)
    {
		$this->db->select('quotes.*,companies.group_areas_id AS group_area, erp_warehouses.name AS location, sale_order.reference_no as ref, users.username, tax_rates.name AS tax_name');
        $this->db->join('users','quotes.created_by = users.id', 'left');
        $this->db->join('companies','quotes.customer_id = companies.id', 'left');
        $this->db->join('warehouses','erp_quotes.warehouse_id = erp_warehouses.id', 'left');
        $this->db->join('sale_order','sale_order.quote_id = quotes.id', 'left');
        $this->db->join('tax_rates','quotes.order_tax_id = tax_rates.id', 'left');
        $this->db->where('quotes.id',$id);
        $this->db->from('quotes');
        if($wh){
            $this->db->where_in('erp_quotes.warehouse_id',$wh);
        }    
        $q = $this->db->get();
        if($q->num_rows()>0){
            return $q->row();
        }
        return false;
    }
	 public function getQuoteByID2($id)
    {
		$this->db->select('quotes.*,companies.group_areas_id AS group_area,users.username as saleman,quotes.order_tax, erp_warehouses.name AS location, erp_users.username,tax_rates.name AS tax_name');
		$this->db->join('users','quotes.saleman = users.id', 'left');
		$this->db->join('companies','quotes.customer_id = companies.id', 'left');
        $this->db->join('warehouses','erp_quotes.warehouse_id = erp_warehouses.id', 'left');
        $this->db->join('tax_rates','quotes.order_tax_id = tax_rates.id', 'left');
		$this->db->where('quotes.id',$id);
		$this->db->from('quotes');		
		$q = $this->db->get();
		if($q->num_rows()>0){
			return $q->row();
		}
		return false;
    }

    public function getAllQuoteItems($quote_id)
    {
        $this->db->select('quote_items.*, tax_rates.code as tax_code, tax_rates.name as tax_name,warehouses_products.quantity AS qoh, tax_rates.rate as tax_rate, products.unit, products.details as details, product_variants.name as variant')
            ->join('products', 'products.id=quote_items.product_id', 'left')
            ->join('product_variants', 'product_variants.id=quote_items.option_id', 'left')
			->join('warehouses_products', 'quote_items.product_id = warehouses_products.product_id', 'left')	
            ->join('tax_rates', 'tax_rates.id=quote_items.tax_rate_id', 'left')
            ->group_by('quote_items.id')
            ->order_by('id', 'asc');
        $q = $this->db->get_where('quote_items', array('quote_id' => $quote_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getAllQuoteItems2($quote_id, $warehouse_id)
    {
        $this->db->select('quote_items.*, tax_rates.code as tax_code, tax_rates.name as tax_name, tax_rates.rate as tax_rate, products.unit, products.details as details, product_variants.name as variant, warehouses_products.quantity as wquantity, products.image, units.name AS uname')
            ->join('products', 'products.id=quote_items.product_id', 'left')
            ->join('product_variants', 'product_variants.id=quote_items.option_id', 'left')
            ->join('tax_rates', 'tax_rates.id=quote_items.tax_rate_id', 'left')
			->join('quotes', 'quote_items.quote_id = quotes.id', 'left')
            ->join('warehouses_products', 'quote_items.product_id = warehouses_products.product_id', 'left')            
			->join('units', 'products.unit = units.id', 'left')			
            ->group_by('quote_items.id')
            ->order_by('id', 'asc');
			
        $q = $this->db->get_where('quote_items', array('quote_id' => $quote_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	/*public function getQOHByQuoteItemsID($quote_id) {
		$this->db->select('')
			->from('quotes')
			->join('quote_items', 'quotes.id = quote_items.quote_id', 'innter')
			->join('warehouses_products', 'quote_items.product_id = warehouses_products.product_id', 'left')
			->where('quote_items.quote_id', $quote_id)
			->group_by('warehouses_products.product_id');
			->and('warehouses_products.warehouse_id', $warehouse_id);
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}*/

    public function addQuote($data = array(), $items = array(), $payment = array())
    {
        if ($this->db->insert('quotes', $data)) {
            $quote_id = $this->db->insert_id();
			
			if ($this->site->getReference('qu',$data['biller_id']) == $data['reference_no']) {
				$this->site->updateReference('qu',$data['biller_id']);
			}
			
            foreach ($items as $item) {
                $item['quote_id'] = $quote_id;
                $this->db->insert('quote_items', $item);
            }
			
			if($payment){
				$payment['deposit_quote_id'] = $quote_id;
				$this->db->insert('payments', $payment);
				$deposit = $this->site->getDepositByCompanyID($data['customer_id']);
				$deposit_balance = $deposit->deposit_amount;
				$deposit_balance = $deposit_balance - abs($payment['amount']);
				if($this->db->update('companies', array('deposit_amount' => $deposit_balance), array('id' => $data['customer_id']))){
					$this->db->update('deposits', array('amount' => $deposit_balance), array('company_id' => $data['customer_id']));
				}
			}
			
            return $quote_id;
        }
        return false;
    }

    public function updateQuote($id, $data, $items = array(), $payment = array())
    {
		//$this->erp->print_arrays($id, $data, $items, $payment);
        if ($this->db->update('quotes', $data, array('id' => $id)) && $this->db->delete('quote_items', array('quote_id' => $id))) {
            foreach ($items as $item) {
                $item['quote_id'] = $id;
                $this->db->insert('quote_items', $item);
            }
			
			if($payment){
				$p = $this->getPaymentByQuoteID($id);
				if(!$p){
					$payment['deposit_quote_id'] = $id;
					$this->db->insert('payments', $payment);
					$deposit = $this->site->getDepositByCompanyID($data['customer_id']);
					$deposit_balance = $deposit->deposit_amount;
					$deposit_balance = $deposit_balance - abs($payment['amount']);
					if($this->db->update('companies', array('deposit_amount' => $deposit_balance), array('id' => $data['customer_id']))){
						$this->db->update('deposits', array('amount' => $deposit_balance), array('company_id' => $data['customer_id']));
					}
				}else{
					$payment['deposit_quote_id'] = $id;
					$this->db->update('payments', $payment, array('deposit_quote_id' => $id));
					$deposit = $this->site->getDepositByCompanyID($data['customer_id']);
					$deposit_balance = $deposit->deposit_amount + $p->amount;
					$deposit_balance = $deposit_balance - abs($payment['amount']);
					if($this->db->update('companies', array('deposit_amount' => $deposit_balance), array('id' => $data['customer_id']))){
						$this->db->update('deposits', array('amount' => $deposit_balance), array('company_id' => $data['customer_id']));
					}
				}
			}
			
            return true;
        }
        return false;
    }


    public function deleteQuote($id)
    {
        if ($this->db->delete('quote_items', array('quote_id' => $id)) && $this->db->delete('quotes', array('id' => $id))) {
            return true;
        }
        return FALSE;
    }
	
	public function getPaymentByQuoteID($quote_id){
		$q = $this->db->get_where('payments', array('deposit_quote_id' => $quote_id), 1);
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

    public function getWarehouseProductQuantity($warehouse_id, $product_id)
    {
        $q = $this->db->get_where('warehouses_products', array('warehouse_id' => $warehouse_id, 'product_id' => $product_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
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

    public function getProductOptions_old($product_id, $warehouse_id)
    {
        $this->db->select('product_variants.id as id, product_variants.name as name, product_variants.price as price, product_variants.quantity as total_quantity, warehouses_products_variants.quantity as quantity')
            ->join('warehouses_products_variants', 'warehouses_products_variants.option_id=product_variants.id', 'left')
            //->join('warehouses', 'warehouses.id=product_variants.warehouse_id', 'left')
            ->where('product_variants.product_id', $product_id)
            ->where('warehouses_products_variants.warehouse_id', $warehouse_id)
            //->where('warehouses_products_variants.quantity >', 0)
            ->group_by('product_variants.id');
        $q = $this->db->get('product_variants');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getProductOptions($product_id, $warehouse_id, $all = NULL)
    {
        $this->db->select('product_variants.id as id, product_variants.name as name, product_variants.price as price, product_variants.quantity as total_quantity, warehouses_products_variants.quantity as quantity,product_variants.qty_unit as qty_unit')
            ->join('warehouses_products_variants', 'warehouses_products_variants.option_id=product_variants.id', 'left')
            //->join('warehouses', 'warehouses.id=product_variants.warehouse_id', 'left')
            ->where('product_variants.product_id', $product_id)
			->where('product_variants.product_id !=', 0)
            //->where('warehouses_products_variants.warehouse_id', $warehouse_id)
            ->group_by('product_variants.id');
            if( ! $this->Settings->overselling && ! $all) {
                $this->db->where('warehouses_products_variants.quantity >', 0);
            }
		$this->db->order_by('product_variants.qty_unit', 'DESC');
        $q = $this->db->get('product_variants');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
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
	
	public function getQuoteDepositByQuoteID($quote_id){
		$q=$this->db->select("payments.amount AS deposit_amount, (erp_quotes.grand_total - erp_payments.amount) AS balance")
                ->from('quotes')
				->join('payments', 'payments.deposit_quote_id = quotes.id', 'left')
				->where('deposit_quote_id', $quote_id)
				->get();
		if($q->num_rows() > 0){
			return $q->row();
		}
		return false;
	}
	
	
	public function getAuthorizeQuotes($id) {
		if($id) {
			$this->db->update('quotes', array('status' => 'completed'), array('id' => $id));
			return true;
		}
		return false;
	}
	public function getunapproveQuotes($id) {
		if($id) {
			$this->db->update('quotes', array('status' => 'pending'), array('id' => $id));
			return true;
		}
		return false;
	}
	public function getrejectedQuotes($id) {
		if($id) {
			$this->db->update('quotes', array('status' => 'rejected'), array('id' => $id));
			return true;
		}
		return false;
	}
	public function getQuoteItemsData($quote_id) {

		$this->db->select('
				quote_items.id as id,
				quote_items.option_id,
				quote_items.product_code, 
				quote_items.product_name, 
				quote_items.quantity, 
				quote_items.unit_price, 
				quote_items.tax,
				quote_items.item_tax,
				quote_items.discount,
				quote_items.wpiece, 
				quote_items.piece, 
				quote_items.item_discount,
				quote_items.product_noted,
				quote_items.subtotal,
				tax_rates.name as taxs, 
				quotes.*, 
				IF(erp_quote_items.option_id  > 0, 
					erp_product_variants.name,
					erp_units.name
				) as product_variant,
				products.id as product_id, 
				product_variants.name as variant, 
				products.image as image'
			)
			->from('quote_items')
			->join('quotes', 'quotes.id = quote_items.quote_id', 'inner')
			->join('products', 'quote_items.product_id = products.id', 'left')
			->join('tax_rates', 'tax_rates.id = quote_items.tax_rate_id', 'left')
			->join('units', 'units.id = products.unit', 'left')
			->join('product_variants', 'product_variants.id = quote_items.option_id', 'left')
			->where('quote_items.quote_id', $quote_id);
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;

	}
	
	public function getQuotesData($quote_id=null){
		$this->db->select('erp_quotes.*, tax_rates.name as order_tax_rate')
				 ->from('erp_quotes')
				 ->join('tax_rates', 'erp_quotes.order_tax_id = tax_rates.id', 'left')
				 ->where('erp_quotes.id', $quote_id);
		$q = $this->db->get();
		if($q->num_rows()>0){
			return $q->row();
		}
		return null;
	}
	
	public function updateQuoteStatus($quote_id){
		$status = array('quote_status'=>'completed');
		$this->db->where('id',$quote_id);
		$this->db->update('quotes',$status);
	}
	
	
	
}
