<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Settings_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function updateLogo($photo)
    {
        $logo = array('logo' => $photo);
        if ($this->db->update('settings', $logo)) {
            return true;
        }
        return false;
    }

    public function updateLoginLogo($photo)
    {
        $logo = array('logo2' => $photo);
        if ($this->db->update('settings', $logo)) {
            return true;
        }
        return false;
    }

    public function getSettings()
    {
        $q = $this->db->get('settings');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getAccountSettings()
    {
        $q = $this->db->get('account_settings');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    public function addGroupArea($data)
    {
        if ($this->db->insert('erp_group_areas', $data)) {
            return true;
        }
        return false;
    }
    public function deleteGroupArea($id)
    {
        if ($this->db->delete('erp_group_areas', array('areas_g_code' => $id))){
            return true;
        }
        return FALSE;
    }
    public function updateGroupArea($id, $data = array())
    {
        $this->db->where('areas_g_code', $id);
        if ($this->db->update('erp_group_areas', $data)) {
            return true;
        }
        return false;
    }
    public function getGroupAreaBy($id)
    {
        if($id!=''){
            $q = $this->db->get_where('erp_group_areas', array('areas_g_code' => $id), 1);
            if ($q->num_rows() > 0) {
                return $q->row();
            }
        }else{
            $q = $this->db->get('erp_group_areas');
            if ($q->num_rows() > 0) {
                return $q->result();
            }
        }

        return FALSE;
    }
    public function getDateFormats()
    {
        $q = $this->db->get('date_format');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function updateSetting($data)
    {
        $this->db->where('setting_id', '1');
        if ($this->db->update('settings', $data)) {
            return true;
        }
        return false;
    }

    public function addTaxRate($data)
    {
        if ($this->db->insert('tax_rates', $data)) {
            return true;
        }
        return false;
    }

    public function updateTaxRate($id, $data = array())
    {
        $this->db->where('id', $id);
        if ($this->db->update('tax_rates', $data)) {
            return true;
        }
        return false;
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
        return FALSE;
    }

    public function getTaxRateByID($id)
    {
        $q = $this->db->get_where('tax_rates', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function addWarehouse($data)
    {
        if ($this->db->insert('warehouses', $data)) {
            return true;
        }
        return false;
    }

    public function updateWarehouse($id, $data = array())
    {
        $this->db->where('id', $id);
        if ($this->db->update('warehouses', $data)) {
            return true;
        }
        return false;
    }

    public function getAllWarehouses()
    {
        $q = $this->db->get('warehouses');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getWarehouseByID($id)
    {
        $q = $this->db->get_where('warehouses', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function deleteTaxRate($id)
    {
        if ($this->db->delete('tax_rates', array('id' => $id))) {
            return true;
        }
        return FALSE;
    }

    public function deleteInvoiceType($id)
    {
        if ($this->db->delete('invoice_types', array('id' => $id))) {
            return true;
        }
        return FALSE;
    }

    public function deleteWarehouse($id)
    {
        if ($this->db->delete('warehouses', array('id' => $id)) && $this->db->delete('warehouses_products', array('warehouse_id' => $id))) {
            return true;
        }
        return FALSE;
    }

    public function addCustomerGroup($data,$categories)
    {

        if ($this->db->insert('customer_groups', $data)) {
            $group_id = $this->db->insert_id();
            foreach($categories as $cate)
            {
                $cate['customer_group_id'] = $group_id;
                $this->db->insert('categories_group', $cate);
            }


            return true;
        }
        return false;
    }

    public function addPromotion($data,$categories)
    {

        if ($this->db->insert('erp_promotions', $data)) {
            $promotion_id = $this->db->insert_id();
            foreach($categories as $cate)
            {
                $cate['promotion_id'] = $promotion_id;
                $this->db->insert('erp_promotion_categories', $cate);
            }


            return true;
        }
        return false;
    }



    public function Old_Customer_Group($id=null)
    {

        $this->db->select("categories.id,categories_group.percent,categories_group.cate_name")
            ->join('categories', 'categories.id = categories_group.cate_id', 'left');

        $this->db->where('customer_group_id', $id);
        $q = $this->db->get('categories_group');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function Old_promotions($id=null)
    {

        $this->db->select("categories.id,erp_promotion_categories.discount,categories.name")
            ->join('categories', 'categories.id = erp_promotion_categories.category_id', 'left');

        $this->db->where('promotion_id', $id);
        $q = $this->db->get('erp_promotion_categories');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function updatePromotion($id, $data = array(),$categories)
    {

        $this->db->where('id', $id);
        if ($this->db->update('erp_promotions', $data)) {
            $promotion_id = $id;
            if($this->db->delete('erp_promotion_categories', array('promotion_id' => $id)))
            {
                foreach($categories as $cate)
                {
                    $cate['promotion_id'] = $promotion_id;
                    $this->db->insert('erp_promotion_categories', $cate);
                }
                return true;
            }

        }
        return false;
    }

    public function updateCustomerGroup($id, $data = array(),$categories)
    {
        $this->db->where('id', $id);
        if ($this->db->update('customer_groups', $data)) {
            $group_id = $id;
            if($this->db->delete('categories_group', array('customer_group_id' => $id)))
            {
                foreach($categories as $cate)
                {
                    $cate['customer_group_id'] = $group_id;
                    $this->db->insert('categories_group', $cate);
                }
                return true;
            }

        }
        return false;
    }

    public function getAllCustomerGroups()
    {
        $q = $this->db->get('customer_groups');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getCustomerGroupByID($id)
    {
        $q = $this->db->get_where('customer_groups', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getPromotion($id)
    {
        $q = $this->db->get_where('erp_promotions', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function deleteCustomerGroup($id)
    {
        if ($this->db->delete('customer_groups', array('id' => $id))) {
            return true;
        }
        return FALSE;
    }

    public function deletePromotion($id)
    {
        if ($this->db->delete('erp_promotions', array('id' => $id))) {
            return true;
        }
        return FALSE;
    }


    public function getGroups()
    {
        $this->db->where('id >', 4);
        $q = $this->db->get('groups');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getGroupByID($id)
    {
        $q = $this->db->get_where('groups', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getGroupPermissions($id)
    {
        $q = $this->db->get_where('permissions', array('group_id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function GroupPermissions($id)
    {
        $q = $this->db->get_where('permissions', array('group_id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->result_array();
        }
        return FALSE;
    }

    public function updatePermissions($id, $data = array())
    {

        if ($this->db->update('permissions', $data, array('group_id' => $id)) && $this->db->update('users', array('show_price' => $data['products-price'], 'show_cost' => $data['products-cost']), array('group_id' => $id))) {
            return true;
        }
        return false;
    }


    public function addGroup($data)
    {
        if ($this->db->insert("groups", $data)) {
            $gid = $this->db->insert_id();
            $this->db->insert('permissions', array('group_id' => $gid));
            return $gid;
        }
        return false;
    }

    public function updateGroup($id, $data = array())
    {
        $this->db->where('id', $id);
        if ($this->db->update("groups", $data)) {
            return true;
        }
        return false;
    }


    public function getAllCurrencies()
    {
        $q = $this->db->get('currencies');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getCurrencyByID($id)
    {
        $q = $this->db->get_where('currencies', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function addCurrency($data)
    {
        if ($this->db->insert("currencies", $data)) {
            return true;
        }
        return false;
    }

    public function updateCurrency($id, $data = array())
    {
        $this->db->where('id', $id);
        if ($this->db->update("currencies", $data)) {
            return true;
        }
        return false;
    }

    public function deleteCurrency($id)
    {
        if ($this->db->delete("currencies", array('id' => $id))) {
            return true;
        }
        return FALSE;
    }

    public function getAllCategories()
    {
        $q = $this->db->get("categories");
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getAllSubCategories()
    {
        $q = $this->db->get("subcategories");
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getSubcategoryDetails($id)
    {
        $this->db->select("subcategories.code as code, subcategories.name as name, categories.name as parent")
            ->join('categories', 'categories.id = subcategories.category_id', 'left')
            ->group_by('subcategories.id');
        $q = $this->db->get_where("subcategories", array('subcategories.id' => $id));
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getCategoryByCode($code)
    {
        $q = $this->db->get_where('categories', array('code' => $code), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function getSubcategoryByCode($code)
    {

        $q = $this->db->get_where('subcategories', array('code' => $code), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function getSubCategoriesByCategoryID($category_id)
    {
        $q = $this->db->get_where("subcategories", array('category_id' => $category_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getAllCategoriesByAcc($id)
    {
        $this->db
            ->select('categories.*,
                        CONCAT(sale.accountcode, " | ", sale.accountname) as sale,
                        CONCAT(purchase.accountcode, " | ", purchase.accountname) as purchase,
                        CONCAT(stock.accountcode, " | ", stock.accountname) as stock,
                        CONCAT(stock_adjust.accountcode, " | ", stock_adjust.accountname) as stock_adjust,
                        CONCAT(cost.accountcode, " | ", cost.accountname) as cost,
                        CONCAT(cost_variant.accountcode, " | ", cost_variant.accountname) as cost_variant
                     ')
            ->from('categories')
            ->join('gl_charts sale', 'categories.ac_sale = sale.accountcode', 'left')
            ->join('gl_charts purchase', 'categories.ac_purchase = purchase.accountcode', 'left')
            ->join('gl_charts stock', 'categories.ac_stock = stock.accountcode', 'left')
            ->join('gl_charts stock_adjust', 'categories.ac_stock_adj = stock_adjust.accountcode', 'left')
            ->join('gl_charts cost', 'categories.ac_cost = cost.accountcode', 'left')
            ->join('gl_charts cost_variant', 'categories.ac_cost_variant = cost_variant.accountcode', 'left')
            ->where('categories.id', $id);

        $q = $this->db->get();

        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getCategoryByID($id)
    {
        $q = $this->db->get_where("categories", array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getSubCategoryByID($id)
    {
        $q = $this->db->get_where("subcategories", array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getSubCategoryByIDToExport($id)
    {
        $q = $this->db->get_where("subcategories", array('category_id' => $id));
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return FALSE;
    }

    public function addCategories($data)
    {
        if ($this->db->insert_batch('categories', $data)) {
            return true;
        }
        return false;
    }

    public function addSubCategories($data)
    {
        if ($this->db->insert_batch('subcategories', $data)) {
            return true;
        }
        return false;
    }

    public function addCategory($data)
    {
        if ($data) {
            $this->db->insert("categories", $data);
            $cid = $this->db->insert_id();
            return $cid;
        }
    }

    public function addSubCategory($category, $name, $code,$type, $photo)
    {
        if ($this->db->insert("subcategories", array('category_id' => $category, 'code' => $code,'type'=>$type, 'name' => $name, 'image' => $photo))) {
            $subcateid = $this->db->insert_id();
            return $subcateid;
            // return true;
        }
        return false;
    }

    public function updateCategory($id, $data = array(), $photo)
    {
        if ($photo) {
            $data['image'] = $photo;
        }
        if($data) {
            $this->db->update("categories", $data, array('id' => $id));
            return true;
        }
        return false;
    }

    public function updateSubCategory($id, $data = array(), $photo)
    {
        $categoryData = array(
            'category_id' => $data['category'],
            'code' => $data['code'],
            'name' => $data['name'],
        );
        if ($photo) {
            $categoryData['image'] = $photo;
        }
        $this->db->where('id', $id);
        if ($this->db->update("subcategories", $categoryData)) {
            return true;
        }
        return false;
    }

    public function deleteCategory($id)
    {
        if ($this->db->delete("categories", array('id' => $id))) {
            return true;
        }
        return FALSE;
    }

    public function deleteSubCategory($id)
    {
        if ($this->db->delete("subcategories", array('id' => $id))) {
            return true;
        }
        return FALSE;
    }

    public function getPaypalSettings()
    {
        $q = $this->db->get('paypal');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function updatePaypal($data)
    {
        $this->db->where('id', '1');
        if ($this->db->update('paypal', $data)) {
            return true;
        }
        return FALSE;
    }

    public function getSkrillSettings()
    {
        $q = $this->db->get('skrill');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function updateSkrill($data)
    {
        $this->db->where('id', '1');
        if ($this->db->update('skrill', $data)) {
            return true;
        }
        return FALSE;
    }

    public function checkGroupUsers($id)
    {
        $q = $this->db->get_where("users", array('group_id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function deleteGroup($id)
    {
        if ($this->db->delete('groups', array('id' => $id))) {
            return true;
        }
        return FALSE;
    }

    public function addVariant($data)
    {
        if ($this->db->insert('variants', $data)) {
            return true;
        }
        return false;
    }
    public function addCategoriesNote($data)
    {
        if ($this->db->insert('categories_note', $data)) {
            return true;
        }
        return false;
    }
    public function updateVariant($id, $data = array())
    {
        $this->db->where('id', $id);
        if ($this->db->update('variants', $data)) {
            return true;
        }
        return false;
    }
    public function updateCategoryNote($id, $data = array())
    {
        $this->db->where('id', $id);
        if ($this->db->update('categories_note', $data)) {
            return true;
        }
        return false;
    }
    public function getAllVariants()
    {
        $q = $this->db->get('variants');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    public function getAllCategoriesNote()
    {
        $q = $this->db->get('categories_note');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    public function getVariantByID($id)
    {
        $q = $this->db->get_where('variants', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    public function getCategoriesNoteByID($id)
    {
        $q = $this->db->get_where('categories_note', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    public function deleteVariant($id)
    {
        if ($this->db->delete('variants', array('id' => $id))) {
            return true;
        }
        return FALSE;
    }
    public function deleteCategoriesNote($id)
    {
        if ($this->db->delete('categories_note', array('id' => $id))) {
            return true;
        }
        return FALSE;
    }
    public function getProductNames($term, $limit = 50)
    {
        $this->db->select('' . $this->db->dbprefix('products') . '.id, code, 
			' . $this->db->dbprefix('products') . '.name as name, 
			' . $this->db->dbprefix('products') . '.price as price, 
			' . $this->db->dbprefix('products') . '.unit as unit, 
			' . $this->db->dbprefix('products') . '.cost as cost')
            ->where("type != 'combo' AND "
                . "(" . $this->db->dbprefix('products') . ".name LIKE '%" . $term . "%' OR code LIKE '%" . $term . "%' OR
                concat(" . $this->db->dbprefix('products') . ".name, ' (', code, ')') LIKE '%" . $term . "%')")
            ->group_by('products.id')->limit($limit);
        $q = $this->db->get('products');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    public function getVariants($id){
        $this->db->select('id, name');
        $this->db->from('product_variants');
        $this->db->where('product_id', $id);
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach ($q->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }else{
            $this->db->select('0 as id, units.name');
            $this->db->from('products');
            $this->db->join('units','products.unit=units.id','left');
            $this->db->where('products.id',$id);
            $q=$this->db->get();
            if ($q->num_rows() > 0) {
                foreach ($q->result() as $row) {
                    $data[] = $row;
                }
                return $data;
            }
        }
        return false;
    }

    public function insertBom($data)
    {
        if ($this->db->insert('bom', $data)) {
            $convert_id = $this->db->insert_id();
            return $convert_id;
        }
    }

    public function getConvertItemsById($bom_id){
        $this->db->select('bom_items.product_id, bom_items.bom_id, bom_items.quantity AS c_quantity ,(erp_products.cost * erp_bom_items.quantity) AS tcost, bom_items.status, products.cost AS p_cost, (erp_products.price * erp_bom_items.quantity) as tprice, bom_items.option_id');
        $this->db->join('products', 'products.id = bom_items.product_id', 'INNER');
        $this->db->where('bom_items.bom_id', $bom_id);
        $query = $this->db->get('bom_items');

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }

    public function getConvertItemsDeduct($bom_id){
        $this->db->select('SUM(erp_products.cost * erp_bom_items.quantity) AS tcost, bom_items.status');
        $this->db->join('products', 'products.id = bom_items.product_id', 'INNER');
        $this->db->where('bom_items.bom_id', $bom_id);
        $this->db->where('bom_items.status', 'deduct');
        $query = $this->db->get('bom_items');

        if ($query->num_rows() > 0) {
            return $query->row();
        }
        return false;
    }

    public function getConvertItemsAdd($bom_id){
        $this->db->select('bom_items.product_id, bom_items.bom_id, bom_items.quantity AS c_quantity ,erp_products.cost AS tcost, bom_items.status, erp_products.price as tprice, bom_items.option_id');
        $this->db->join('products', 'products.id = bom_items.product_id', 'INNER');
        $this->db->where('bom_items.bom_id', $bom_id);
        $this->db->where('bom_items.status', 'add');
        $query = $this->db->get('bom_items');

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }

    public function getBOmByID($id)
    {
        $this->db->select('date, name, sum(erp_bom_items.quantity) as qty, cost, noted, created_by');
        $this->db->from('bom');
        $this->db->join('bom_items', 'bom_items.bom_id = bom.id');
        $this->db->where(array('bom.id'=> $id, 'bom_items.status'=>'add'));
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->row();
        }
        return false;
    }

    public function getBOmByIDs($id)
    {
        $this->db->select('date, bom.name, bom_items.quantity, bom_items.cost, noted, created_by, status, product_name, product_code, product_variants.name as var_name, products.quantity as qoh');
        $this->db->from('bom');
        $this->db->join('bom_items', 'bom_items.bom_id = bom.id');
        $this->db->join('products', 'products.id = bom_items.product_id', 'left');
        $this->db->join('product_variants', 'bom_items.option_id = product_variants.id', 'left');
        $this->db->where('bom.id',$id);
        $this->db->group_by('bom_items.product_id');
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    public function deleteBom($id)
    {
        if ($this->db->delete('bom', array('id' => $id))) {
            return true;
        }
        return FALSE;
    }

    public function deleteBom_items($id)
    {
        if ($this->db->delete('bom_items', array('bom_id' => $id))) {
            return true;
        }
        return FALSE;
    }

    public function updateBom($id, $data)
    {
        $this->db->where('id', $id);
        if ($this->db->update('bom', $data)) {
            return true;
        }
        return FALSE;
    }

    public function updateBom_items($data)
    {
        if ($this->db->insert('bom_items', $data)) {
            return true;
        }
        return FALSE;
    }

    public function selectBomItems($bom_id, $product_id)
    {
        $q = $this->db->get_where("bom_items", array('bom_id' => $bom_id, 'product_id' => $product_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getRoomByID($id){
        $this->db->select('id,floor,name,ppl_number,description,inactive,warehouse_id');
        $this->db->from('suspended');
        $this->db->where('id' , $id);
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }
    //=============Insert Suppend===================
    public function addSuppend($data){
        //$this->erp->print_arrays($data);
        if ($this->db->insert('suspended', $data)) {
            return true;
        }
        return false;
    }
    //=============delete Suppend===================
    public function deleteSuppend($id){
        $q = $this->db->delete('suspended', array('id' => $id));
        if($q){
            return true;
        }else{
            return false;
        }
    }

    public function updateRooms($id,$data){
        //$this->erp->print_arrays($data);
        $this->db->where('id', $id);
        $q=$this->db->update('suspended', $data);
        if ($q) {
            return true;
        }
        return false;
    }


    /* New Function */
    public function getExpenseCategoryByID($id)
    {
        $q = $this->db->get_where("expense_categories", array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getExpenseCategoryByCode($code)
    {
        $q = $this->db->get_where("expense_categories", array('code' => $code), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function addExpenseCategory($data)
    {
        if ($this->db->insert("expense_categories", $data)) {
            return true;
        }
        return false;
    }

    public function addExpenseCategories($data)
    {
        if ($this->db->insert_batch("expense_categories", $data)) {
            return true;
        }
        return false;
    }

    public function updateExpenseCategory($id, $data = array())
    {
        if ($this->db->update("expense_categories", $data, array('id' => $id))) {
            return true;
        }
        return false;
    }

    public function hasExpenseCategoryRecord($id)
    {
        $this->db->where('category_id', $id);
        return $this->db->count_all_results('expenses');
    }

    public function deleteExpenseCategory($id)
    {
        if ($this->db->delete("expense_categories", array('id' => $id))) {
            return true;
        }
        return FALSE;
    }

    public function addUnit($data)
    {
        if ($this->db->insert("units", $data)) {
            $uid = $this->db->insert_id();
            return $uid;
            // return true;
        }
        return false;
    }

    public function updateUnit($id, $data = array())
    {
        if ($this->db->update("units", $data, array('id' => $id))) {
            return true;
        }
        return false;
    }

    public function getUnitTypeById($code,$product_id)
    {
        $q = $this->db->get_where('units', array('id' => $code), 1);
        if ($q->num_rows() > 0) {
            $unit_name=$q->row()->name;
            $p=$this->db->get_where('product_variants',array('product_id'=>$product_id,'name'=>$unit_name));
            if($p->num_rows() >0){
                return "variant";
            }else{
                return "unit";
            }

        }

        return FALSE;
    }

    public function deleteUnit($id)
    {
        if ($this->db->delete("units", array('id' => $id))) {
            $this->db->delete("units", array('base_unit' => $id));
            return true;
        }
        return FALSE;
    }

    public function addPriceGroup($data)
    {
        if ($this->db->insert('price_groups', $data)) {
            return true;
        }
        return false;
    }

    public function updatePriceGroup($id, $data = array())
    {
        $this->db->where('id', $id);
        if ($this->db->update('price_groups', $data)) {
            return true;
        }
        return false;
    }

    public function getAllPriceGroups()
    {
        $q = $this->db->get('price_groups');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getPriceGroupByID($id)
    {
        $q = $this->db->get_where('price_groups', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function deletePriceGroup($id)
    {
        if ($this->db->delete('price_groups', array('id' => $id)) && $this->db->delete('product_prices', array('price_group_id' => $id))) {
            return true;
        }
        return FALSE;
    }

    public function setProductPriceForPriceGroup($product_id, $group_id, $price, $currency_code = NULL, $unit_id = NULL, $unit_type = NULL)
    {
        if ($this->getGroupPrice($group_id, $product_id, $unit_id, $unit_type)) {
            if ($this->db->update('product_prices', array('price' => $price, 'currency_code' => $currency_code), array('price_group_id' => $group_id, 'product_id' => $product_id, 'unit_id' => $unit_id, 'unit_type' => $unit_type))) {
                return true;
            }
        } else {
            if ($this->db->insert('product_prices', array('price' => $price, 'price_group_id' => $group_id, 'product_id' => $product_id, 'unit_id' => $unit_id, 'unit_type' => $unit_type, 'currency_code' => $currency_code))) {
                return true;
            }
        }
        return FALSE;
    }

    public function getGroupPrice($group_id, $product_id, $unit_id = NULL, $unit_type = NULL)
    {
        $q = $this->db->get_where('product_prices', array('price_group_id' => $group_id, 'product_id' => $product_id, 'unit_id' => $unit_id, 'unit_type' => $unit_type), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getProductGroupPriceByPID($product_id, $group_id)
    {
        $pg = "(SELECT {$this->db->dbprefix('product_prices')}.price as price, {$this->db->dbprefix('product_prices')}.product_id as product_id FROM {$this->db->dbprefix('product_prices')} WHERE {$this->db->dbprefix('product_prices')}.product_id = {$product_id} AND {$this->db->dbprefix('product_prices')}.price_group_id = {$group_id}) GP";

        $this->db->select("{$this->db->dbprefix('products')}.id as id, {$this->db->dbprefix('products')}.code as code, {$this->db->dbprefix('products')}.name as name, GP.price", FALSE)
            // ->join('products', 'products.id=product_prices.product_id', 'left')
            ->join($pg, 'GP.product_id=products.id', 'left');
        $q = $this->db->get_where('products', array('products.id' => $product_id), 1);
        if ($q->num_rows() > 0) {
            return true;
        }
        return FALSE;
    }

    public function updateGroupPrices($data = array())
    {
        //by Ravy
        foreach ($data as $row) {
            $exist=$this->db->get_where('product_prices',array('product_id'=>$row['product_id'],'price_group_id'=>$row['price_group_id'],'unit_id'=>$row['unit_id']));
            if ($exist->num_rows()>0) {
                $id=$exist->row()->id;
                $this->db->update('product_prices', array('price' => $row['price']),array('id'=>$id));
            } else {
                $this->db->insert('product_prices', $row);

            }
        }
        return true;
    }

    public function deleteProductGroupPrice($product_id, $group_id)
    {
        if ($this->db->delete('product_prices', array('price_group_id' => $group_id, 'product_id' => $product_id))) {
            return TRUE;
        }
        return FALSE;
    }

    public function getBrandByName($name)
    {
        $q = $this->db->get_where('brands', array('name' => $name), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function addBrand($data)
    {
        if ($this->db->insert("brands", $data)) {
            return true;
        }
        return false;
    }

    public function addBrands($data)
    {
        if ($this->db->insert_batch('brands', $data)) {
            return true;
        }
        return false;
    }

    public function updateBrand($id, $data = array())
    {
        if ($this->db->update("brands", $data, array('id' => $id))) {
            return true;
        }
        return false;
    }

    public function deleteBrand($id)
    {
        if ($this->db->delete("brands", array('id' => $id))) {
            return true;
        }
        return FALSE;
    }

    public function addProductNote($data)
    {
        if ($data) {
            $this->db->insert("product_note", $data);
            return true;
        }
        return false;
    }

    public function getProductNoteByID($id)
    {
        $q = $this->db->get_where("product_note", array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function updateProductNote($id, $data = array(), $photo)
    {
        if ($photo) {
            $data['image'] = $photo;
        }
        if($data) {
            $this->db->update("product_note", $data, array('id' => $id));
            return true;
        }
        return false;
    }

    public function delete_product_note($id)
    {
        if ($this->db->delete("product_note", array('id' => $id))) {
            return true;
        }
        return FALSE;
    }

    public function getAllProductNote(){
        $q = $this->db->get("product_note");
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return FALSE;
    }

    public function hasCategoryInProduct($category_id){
        $this->db->where_in('category_id', $category_id);
        $q = $this->db->get("products");
        if ($q->num_rows() > 0) {
            return true;
        }
        return FALSE;
    }

    public function hasSubCategoryInProduct($subcategory_id){
        $this->db->where_in('subcategory_id', $subcategory_id);
        $q = $this->db->get("products");
        if ($q->num_rows() > 0) {
            return true;
        }
        return FALSE;
    }
    public function getprinciple_types()
    {
        $q = $this->db->get('erp_term_types');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getfrequency()
    {
        $q = $this->db->get('erp_frequency');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getterms()
    {
        $q = $this->db->get('erp_terms');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getpublic_charges()
    {
        $q = $this->db->get('define_public_charge');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getpublic_charge_details($id=NULL)
    {
        $q = $this->db->get_where("public_charge_detail", array('pub_id' => $id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function add_define_term($data){
        if($this->db->insert('erp_terms',$data)){
            return true;
        }
        return false;
    }

    public function updatedefine_term($id,$data){
        if($this->db->update('erp_terms',$data,array('id'=>$id))){
            return true;
        }
        return false;
    }

    public function gettermsBYID($id)
    {
        $q = $this->db->get_where('erp_terms',array('id'=>$id),1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function add_define_frequency($data){
        if($this->db->insert('erp_frequency',$data)){
            return true;
        }
        return false;
    }

    public function add_define_principle($data){
        if($this->db->insert('erp_term_types',$data)){
            return true;
        }
        return false;
    }
    public function getfrequencyBYID($id)
    {
        $q = $this->db->get_where('erp_frequency',array('id'=>$id),1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    public function updatedefine_frequency($id,$data){
        if($this->db->update('erp_frequency',$data,array('id'=>$id))){
            return true;
        }
        return false;
    }
    public function updatedefine_principle($id,$data){
        if($this->db->update('erp_term_types',$data,array('id'=>$id))){
            return true;
        }
        return false;
    }
    public function delete_define_principle($id){
        if($this->db->delete('erp_term_types',array('id'=>$id))){
            return true;
        }
        return false;
    }
    public function add_define_principle_rate($data){
        $b = $this->db->insert('erp_principles',$data);
        if($b){
            return true;
        }
        return false;
    }
    public function delete_define_principle_rate($id){
        if($this->db->delete('erp_principles',array('term_type_id'=>$id))){
            return true;
        }
        return false;
    }
    public function delete_define_principle_rate_byid($id){
        if($this->db->delete('erp_principles',array('id'=>$id))){
            return true;
        }
        return false;
    }
    public function update_define_principle_rate_byid($id,$data){
        if($this->db->update('erp_principles',$data,array('id'=>$id))){
            return true;
        }
        return false;
    }
    public function getAll_define_principle_rate($id)
    {
        $q = $this->db->get_where('erp_principles',array('term_type_id'=>$id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    public function getdefine_principle_ratebyid($id){
        $q = $this->db->get_where('erp_principles',array('id'=>$id),1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }
    public function getmaxid($id){
        $q = $this->db->select("MAX(period) as maxid")
            ->where("term_type_id",$id)
            ->get("erp_principles");
        if ($q->num_rows() > 0) {
            return $q->row()->maxid;
        }
        return false;
    }
    public function updateperiod($id,$period)
    {
        if($this->db->update("erp_principles",array('period'=>$period),array("id"=>$id))){
            return true;
        }
        return false;
    }
    // #chanthy -----------------------------------------------------
    public function addGroupPosition($data)
    {
        if ($data) {
            $this->db->insert("position", $data);
            return true;
        }
        return false;
    }

    public function updateGroupPosition($id, $data = array())
    {
        if($data) {
            $this->db->update("position", $data, array('id' => $id));
            return true;
        }
        return false;
    }

    public function getPositionByID($id)
    {
        $q = $this->db->get_where("position", array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function addReason($data)
    {
        if ($data) {
            $this->db->insert("reasons", $data);
            return true;
        }
        return false;
    }

    public function updateReason($id, $data = array())
    {
        if($data) {
            $this->db->update("reasons", $data, array('id' => $id));
            return true;
        }
        return false;
    }

    public function getReasonByID($id)
    {
        $q = $this->db->get_where("reasons", array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    // -/-------------------------------------------------------------

    public function getAllPositions()
    {
        $q = $this->db->get("position");
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    public function deletepayment_term($id)
    {
        if ($this->db->delete("payment_term", array('id' => $id))) {
            return true;
        }
        return FALSE;
    }
    public function addPaymentTerm($data){
        if ($this->db->insert('payment_term', $data)) {
            return true;
        }
        return false;
    }
    public function getPaymentTermById($id)
    {
        $q = $this->db->get_where('payment_term', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    public function updatePaymentTerm($id, $data = array())
    {
        $this->db->where('id', $id);
        if ($this->db->update('payment_term', $data)) {
            return true;
        }
        return false;
    }
    public function deletePaymentTerm($id){
        $this->db->delete('payment_term', array('id' => $id));
    }

    public function add_tax_exchange_rate($data){
        $i= $this->db->insert('tax_exchange_rate',$data);
        if($i){
            return true;
        }else{
            return false;
        }
    }
    public function delete_tax_exchange_rate($id){
        $this->db->where('id',$id);
        $d=$this->db->delete('tax_exchange_rate');
        if($d){
            return true;
        } else{
            return false;
        }
    }
    public function get_tax_exchange_rate(){
        $g=$this->db->get('tax_exchange_rate');
        if($g){
            return $g->result();
        } else{
            return false;
        }
    }
    public function get_one_tax_exchange_rate($id){
        $this->db->where('id',$id);
        $g=$this->db->get('tax_exchange_rate');
        if($g){
            return $g->row();
        } else{
            return false;
        }
    }
    public function update_tax_exchange_rate($id,$tax_exchange_rate){
        $this->db->where('id',$id);
        $u=$this->db->update('tax_exchange_rate',$tax_exchange_rate);
        if($u){
            return true;
        }else{
            return false;
        }
    }

    public function add_public_charge($data)
    {
        $i= $this->db->insert('define_public_charge',$data);
        if($i){
            return true;
        }else{
            return false;
        }
    }

    public function edit_public_charge($data,$id=NULL){
        $this->db->where('id',$id);
        $u=$this->db->update('define_public_charge',$data);
        if($u){
            return true;
        }else{
            return false;
        }
    }

    public function getPublicChargeById($id=NULL)
    {
        $this->db->where('id',$id);
        $g=$this->db->get('define_public_charge');
        if($g){
            return $g->row();
        } else{
            return false;
        }
    }

    public function add_public_charge_amount($data)
    {
        $i= $this->db->insert('public_charge_detail',$data);
        if($i){
            return true;
        }else{
            return false;
        }
    }

    public function getPublicChargeAmountById($id,$pub_id)
    {
        $this->db->where('id',$id);
        $this->db->where('pub_id',$pub_id);
        $u=$this->db->get('public_charge_detail');
        if($u){
            return $u->row();
        }else{
            return false;
        }
    }

    public function getprinciple_typesBYID($id)
    {
        $this->db->where('id',$id);
        $u=$this->db->get('term_types');
        if($u){
            return $u->row();
        }else{
            return false;
        }
    }

    public function edit_public_charge_amount($data,$id,$pub_id)
    {
        $this->db->where('id',$id);
        $this->db->where('pub_id',$pub_id);
        $u=$this->db->update('public_charge_detail',$data);
        if($u){
            return true;
        }else{
            return false;
        }
    }

}
