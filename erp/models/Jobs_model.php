<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Jobs_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }
	
	public function addDevelop($data){
		if ($this->db->insert('sale_dev_items', $data)) {
            $convert_id = $this->db->insert_id();
            return true;
        }
	}
	
	public function getComputerUser(){
		$q = $this->db->query("SELECT
									id,
									username,
									first_name,
									last_name,
									group_id
								FROM
									erp_users");
		
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return FALSE;
	}
	
	public function getDevelopedProdcut($id){
		$this->db->select($this->db->dbprefix('sale_items').".id as id ," .$this->db->dbprefix('sales') . ".date, reference_no, customer ," . $this->db->dbprefix('sale_items') . ".product_name, product_id, sale_id, unit_price, quantity,".$this->db->dbprefix('sales').".warehouse_id as warehouse")
            ->from('sales')
            ->join('sale_items', 'sale_items.sale_id=sales.id', 'left')
			->where('sale_items.id',$id);
		$q = $this->db->get();
		if($q->num_rows() > 0){
            return $q->row();
		}
		return FALSE;
	}
	
	public function getDevItem($id){
		$this->db->select($this->db->dbprefix('sale_items').".id as id ," .$this->db->dbprefix('sales') . ".date, reference_no, customer ," . $this->db->dbprefix('sale_items') . ".product_name,".$this->db->dbprefix('sale_items').".quantity as fquantity,".$this->db->dbprefix('sale_dev_items').".quantity as dev_quantity, machine_name, quantity_break, quantity_index, created_at, user_1, user_2, user_3, user_4, user_5")
            ->from('sales')
            ->join('sale_items', 'sale_items.sale_id=sales.id', 'left')
			->join('sale_dev_items', 'sale_dev_items.sale_id=sale_items.sale_id', 'left')
            ->where('sale_items.id',$id);
		$q = $this->db->get();
		if($q->num_rows() > 0){
            return $q->row();
		}
		return FALSE;
	}
	
	public function getDevItems($id){
		$this->db->select("*")
            ->from('sale_dev_items')
            ->where('sale_dev_items.id',$id);
		$q = $this->db->get();
		if($q->num_rows() > 0){
            return $q->row();
		}
		return FALSE;
	}
	
	public function updateDevelop($id, $data = array())
    {
        if ($this->db->update('sale_dev_items', $data, array('sale_id' => $id))) {
            return true;
        }
        return false;
    }
	
	public function updateDevelopItem($id, $data = array())
    {
        if ($this->db->update('sale_dev_items', $data, array('id' => $id))) {
            return true;
        }
        return false;
    }
	
	public function getSaleIdByID($id){
		$q = $this->db->query("SELECT
									sale_id
								FROM
									erp_sale_items
								WHERE
									id = $id");
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function deleteDevelop($id)
    {
        if ($this->db->delete('sale_dev_items', array('sale_id' => $id))) {
            return true;
        }
        return FALSE;
    }
	
	public function getAllEmployee($id){
		$this->db->select($this->db->dbprefix('sale_dev_items').".created_at," .$this->db->dbprefix('sales') . ".reference_no, CONCAT(" . $this->db->dbprefix('users') . ".first_name, ' ',".$this->db->dbprefix('users').".last_name) as uName,".$this->db->dbprefix('sales').".customer,".$this->db->dbprefix('sale_dev_items').".grand_total as total")
            ->from('sale_dev_items')
            ->join('sales', 'sales.id=sale_dev_items.sale_id', 'left')
			->join('users', 'users.id=sale_dev_items.created_by', 'left')
            ->where('sale_dev_items.user_1 = "'.$id.'" or sale_dev_items.user_2 = "'.$id.'" or sale_dev_items.user_3 = "'.$id.'" or sale_dev_items.user_4 = "'.$id.'" or sale_dev_items.user_5 = "'.$id.'"');
		$q = $this->db->get();
		if($q->num_rows() > 0){
            return $q->result();
		}
		return FALSE;
	}
	
	public function getBiller(){
		$q = $this->db->query("SELECT
									id,
									company
								FROM
									erp_companies
								WHERE
									group_name = 'biller'");
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return FALSE;
	}
	
	public function addMarchine($data){
		if ($this->db->insert('marchine', $data)) {
            $convert_id = $this->db->insert_id();
            return true;
        }
	}
	
	public function getMarchines($id){
		$q = $this->db->query("SELECT
									name,
									description,
									type,
									biller_id,
									status,
									`13` as first, `15` as second, `25` as third, `30` as fourth, `50` as sixth, `60` as seventh, `76` as eighth, `80` as nineth, `100` as tenth, `120` as eleven, `150` as tween
								FROM
									erp_marchine
								WHERE
									id = '$id'");
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function editMarchine($id, $data = array())
    {
        if ($this->db->update('marchine', $data, array('id' => $id))) {
            return true;
        }
        return false;
    }
	
	public function deleteMarchine($id)
    {
        if ($this->db->delete('marchine', array('id' => $id))) {
            return true;
        }
        return FALSE;
    }
	
	public function getMarchineByID($id)
    {
		$this->db
				->select($this->db->dbprefix('marchine').".id as id,".$this->db->dbprefix('marchine').".name,".$this->db->dbprefix('companies').".company,".$this->db->dbprefix('marchine').".type,description")
				->from("marchine")
				->join("companies", "companies.id = marchine.biller_id")
				->where('marchine.id', $id);
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	public function getAllMarchine(){
		$q = $this->db->query("SELECT
									id,
									name
								FROM
									erp_marchine
								");
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return FALSE;
	}
	
	public function addMarchine_log($data){
		if ($this->db->insert('marchine_logs', $data)) {
            $convert_id = $this->db->insert_id();
            return true;
        }
	}
	
	public function checks_marchine($id){
		$this->db->select('id, new_number')
				 ->from('marchine_logs')
				 ->where('marchine_id',$id)
				 ->order_by('date desc')
				 ->limit(1);
		$q = $this->db->get();
		if($q->num_rows() > 0){
			 return $q->row();
		}
		return FALSE;
	}
	
	public function getJobsByID($id){
		$this->db
			->select($this->db->dbprefix('sale_items').".id as id ," .$this->db->dbprefix('sales') . ".date, reference_no, customer ," . $this->db->dbprefix('sale_items') . ".product_name,".$this->db->dbprefix('sale_items').".quantity as fquantity,".$this->db->dbprefix('sale_dev_items').".quantity as dev_quantity , COALESCE(CASE WHEN erp_sale_items.quantity > erp_sale_dev_items.quantity THEN 'processing' WHEN erp_sale_items.quantity <= erp_sale_dev_items.quantity THEN 'completed' ELSE 'pending' END, 0) AS status")
            ->from('sales')
            ->join('sale_items', 'sale_items.sale_id=sales.id', 'left')
			->join('sale_dev_items', 'sale_dev_items.sale_id=sale_items.sale_id', 'left')
			->join('products', 'products.id = sale_items.product_id', 'left')
			->join('categories', 'categories.id = products.category_id', 'left')
			->where(array('categories.auto_delivery'=>1, 'sale_items.id'=>$id))
            ->group_by('sales.id');
		$q = $this->db->get();
		if($q->num_rows() > 0){
			 return $q->row();
		}
		return FALSE;
	}
	
	public function getActivitiesByID($id){
		$this->db
				->select("id, product_name, sum(quantity) as pquantity, sum(quantity_break) as qty_break, sum(quantity_index) as qty_index, sum(quantity + quantity_break + quantity_index) as tquantity")
				->from('sale_dev_items')
				->where('id', $id)
				->group_by('product_id');
		$q = $this->db->get();
		if($q->num_rows() > 0){
			 return $q->row();
		}
		return FALSE;
	}
	
	public function getEmployeeByID($id){
		$this->db
			->select($this->db->dbprefix('users').".id as id, CONCAT(".$this->db->dbprefix('users').".first_name, ' ',".$this->db->dbprefix('users').".last_name) as name, COALESCE(CASE WHEN erp_users.id = erp_sale_dev_items.user_1 THEN 'true' ELSE 'false' END, 0) AS user1, COALESCE(CASE WHEN erp_users.id = erp_sale_dev_items.user_2 THEN 'true' ELSE 'false' END, 0) AS user2, COALESCE(CASE WHEN erp_users.id = erp_sale_dev_items.user_3 THEN 'true' ELSE 'false' END, 0) AS user3, COALESCE(CASE WHEN erp_users.id = erp_sale_dev_items.user_4 THEN 'true' ELSE 'false' END, 0) AS user4, COALESCE(CASE WHEN erp_users.id = erp_sale_dev_items.user_5 THEN 'true' ELSE 'false' END, 0) AS user5, sum(grand_total) as sum")
            ->from('users')
			->join('sale_dev_items','users.id = sale_dev_items.user_1 or users.id = sale_dev_items.user_2 or users.id = sale_dev_items.user_3 or users.id = sale_dev_items.user_4 or users.id = sale_dev_items.user_5', 'left')
			->where('users.id', $id)
			->group_by('users.id');
		$q = $this->db->get();
		if($q->num_rows() > 0){
			 return $q->row();
		}
		return FALSE;
	}
}
