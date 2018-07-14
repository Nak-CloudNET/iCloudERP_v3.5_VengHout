<?php defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('get_dropdown_project')) {
    function get_dropdown_project($name, $id, $default_value = null)
    {
        $ci =& get_instance();
        $html = '';

        $html .= lang("biller", $id);
        $ci->db->select("*,IF(erp_companies.company = '',erp_companies.name,erp_companies.company) as username");
        $ci->db->where('erp_companies.group_name', 'biller');
        if ($ci->Owner || $ci->Admin || !$ci->session->userdata('biller_id')) {
        } else {
            $arr_biller_id = json_decode($ci->session->userdata('biller_id'));
            $ci->db->where_in('id', $arr_biller_id);
        }

        $q = $ci->db->get('companies');
        $billers = $q->result();
        $bl[""] = "";
        foreach ($billers as $biller) {
            $bl[$biller->id] = $biller->company != '-' ? $biller->code . '-' . $biller->company : $biller->name;
        }

        $default_value = $default_value == null ? $ci->Settings->default_biller : $default_value;

        $html .= form_dropdown($name, $bl, (isset($_POST[$name]) ? $_POST[$name] :
            $default_value), 'id="' . $id . '" 
                    data-placeholder="' . lang("select") . ' ' . lang("biller") .
            '" required="required" class="form-control input-tip select" style="width:100%;"');


        return $html;

    }
}

if (!function_exists('optimizeSystem')) {
    function optimizeSystem($tran_date)
    {
        //return null;
        $ci =& get_instance();

        optimizePurchases($tran_date);
        optimizeOpeningQuantity($tran_date);
        optimizeStockAdjustment($tran_date);
        optimizeTransferStock($tran_date);
        optimizeConvert($tran_date);
        optimizeSale($tran_date);
        optimizeSaleReturn($tran_date);
        optimizeDelivery($tran_date);
        optimizeUsing($tran_date);

        //Will remove this function in the future
        //        updateStockOnHand();
        //        getAvgCost($tran_date);
    }
}

if (!function_exists('getUserIdPermission')) {
    function getUserIdPermission()
    {
        $ci =& get_instance();
        $str_ur = "";
        $arr_ware = array();
        $users = $ci->db->query("SELECT * FROM erp_users;");
        $ci->db->update('warehouses_products', array('rack' => ""));
        if ($users->num_rows() > 0) {
            foreach ($users->result() as $user) {
                if ($user->warehouse_id) {
                    $arr_war = explode(',', $user->warehouse_id);
                    if (count($arr_war) > 0) {
                        foreach ($arr_war as $ware_id) {
                            if ($ware_id > 0) {
                                $arr_ware[] = $ware_id;
                            }
                        }
                    }
                }
            }
        }
        foreach (array_unique($arr_ware) as $aw) {
            $wares = 0;
            $ware = $ci->db->query("SELECT * FROM erp_users WHERE FIND_IN_SET('{$aw}', warehouse_id);");
            if ($ware->num_rows() > 0) {
                foreach ($ware->result() as $ur) {
                    if ($wares == $aw) {
                        $str_ur .= '##' . $ur->id;
                    } else {
                        $str_ur = '##' . $ur->id;
                    }

                    $wares = $aw;
                }
                //echo $aw .'=='. $str_ur.'<br/>';
                $ci->db->update('warehouses_products', array('rack' => ($str_ur . '##')), array('warehouse_id' => $aw));
            }
        }
        //exit;
    }
}

if (!function_exists('getScalarValue')) {
    function getScalarValue($sql, $field_name)
    {
        $ci =& get_instance();

        $q = $ci->db->query($sql);
        if ($q->num_rows() > 0) {
            return $q->row()->$field_name;
        }
        return "";
    }
}

