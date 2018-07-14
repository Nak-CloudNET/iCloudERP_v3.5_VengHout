<?php defined('BASEPATH') OR exit('No direct script access allowed');
if (!function_exists('getAvgCost2')) {
    function getAvgCost2($tran_date, $arr_product_id = [])
    {   //return false;
        $ci =& get_instance();

        $w_product_id = '';
        if (is_array($arr_product_id)) {
            $w_product_id = count($arr_product_id) > 0 ? " AND st.product_id in(" . implode(",", $arr_product_id) . ") " : "";
        }
        /*
                $q_date = $ci->db->query("SELECT MIN(st.tran_date) AS min_tran_date, MAX(st.tran_date) AS max_tran_date FROM
                              erp_stock_trans AS st WHERE st.is_close = 0 $w_product_id ");

                */
        $q_date = $ci->db->query("SELECT '{$tran_date}' AS min_tran_date, MAX(st.tran_date) AS max_tran_date FROM 
                      erp_stock_trans AS st WHERE st.is_close = 0 $w_product_id ");

        if ($q_date->num_rows() > 0) {
            $row_date = $q_date->row();
            $min_tran_date = $row_date->min_tran_date;

            $max_tran_date = $row_date->max_tran_date;
            $date_range = createDateRange($min_tran_date, $max_tran_date);
            if (count($date_range) > 0) {

                $total_all_qty = [];
                $total_all_cost = [];

                foreach ($date_range as $date) {

                    $q_all_product_id = $ci->db->query("SELECT st.product_id
                                    FROM erp_stock_trans AS st
                                     WHERE st.is_close = 0 AND st.tran_date = '{$date}'  GROUP BY st.product_id");


                    if ($q_all_product_id->num_rows() > 0) {

                        foreach ($q_all_product_id->result() as $row_p_id) {

                            $p_id = $row_p_id->product_id;

                            if (!isset($total_all_qty[$p_id])) {
                                $total_all_qty[$p_id] = 0;
                            }
                            if (!isset($total_all_cost[$p_id])) {
                                $total_all_cost[$p_id] = 0;
                            }

                            $q_all_purchase_stock_trans = $ci->db->query("SELECT  
                                    Sum( COALESCE (st.quantity_balance_unit, 0)) AS total_all_qty, 
                                    Sum( COALESCE ( st.total_cost * st.quantity_balance_unit )) AS total_all_cost 
                                    FROM erp_stock_trans AS st
                                     WHERE st.is_close = 0 AND st.tran_date = '{$date}' AND 
                                     st.product_id = '{$p_id}' AND
                                    st.tran_type IN ('PURCHASE', 'OPENING QUANTITY') ");

                            if ($q_all_purchase_stock_trans->num_rows() > 0) {
                                if (isset($total_all_qty[$p_id])) {
                                    $total_all_qty[$p_id] += $q_all_purchase_stock_trans->row()->total_all_qty;
                                } else {
                                    $total_all_qty[$p_id] = $q_all_purchase_stock_trans->row()->total_all_qty;
                                }
                                if (isset($total_all_cost[$p_id])) {
                                    $total_all_cost[$p_id] += $q_all_purchase_stock_trans->row()->total_all_cost;
                                } else {
                                    $total_all_cost[$p_id] = $q_all_purchase_stock_trans->row()->total_all_cost;
                                }
                            }


                            $avg_cost = $total_all_qty[$p_id] != 0 ? $total_all_cost[$p_id] / $total_all_qty[$p_id] : 0;


                            $q_all_non_purchase_stock_trans = $ci->db->query("SELECT st.product_id, 
                                    Sum( COALESCE (st.quantity_balance_unit, 0)) AS total_all_qty, 
                                    Sum( COALESCE ( " . ($avg_cost - 0) . " * st.quantity_balance_unit )) AS total_all_cost 
                                    FROM erp_stock_trans AS st
                                     WHERE st.is_close = 0 AND st.tran_date = '{$date}' AND 
                                     st.product_id = '{$p_id}' AND
                                    st.tran_type <> 'PURCHASE' ");

                            if ($q_all_non_purchase_stock_trans->num_rows() > 0) {
                                if (isset($total_all_qty[$p_id])) {
                                    $total_all_qty[$p_id] += $q_all_non_purchase_stock_trans->row()->total_all_qty;
                                } else {
                                    $total_all_qty[$p_id] = $q_all_non_purchase_stock_trans->row()->total_all_qty;
                                }
                                if (isset($total_all_cost[$p_id])) {
                                    $total_all_cost[$p_id] += $q_all_non_purchase_stock_trans->row()->total_all_cost;
                                } else {
                                    $total_all_cost[$p_id] = $q_all_non_purchase_stock_trans->row()->total_all_cost;
                                }
                            }
                            //
                            $ci->db->update('erp_products', ['cost' => $avg_cost], ['id' => $p_id]);
                            $ci->db->query("UPDATE erp_product_variants SET
                                    cost = {$avg_cost} * qty_unit WHERE product_id = '{$p_id}' ");

                            $ci->db->query("UPDATE erp_stock_trans SET
                                    total_cost = {$avg_cost} WHERE product_id = '{$p_id}' AND tran_date = '$date' ;");


                        }

                    }


                }

            }

        }


        //return $arr;
    }
}
if (!function_exists('getAvgCost')) {
    function getAvgCost($tran_date, $arr_product_id = [])
    {   //return false;
        $ci =& get_instance();

        $w_product_id = '';
        if (is_array($arr_product_id)) {
            $w_product_id = count($arr_product_id) > 0 ? " AND st.product_id in(" . implode(",", $arr_product_id) . ") " : "";
        }

        //====================================================
        //====================================================
        $q_all_purchase_stock_trans = $ci->db->query("SELECT  st.product_id,
                                    Sum( COALESCE (st.quantity_balance_unit, 0)) AS total_all_qty, 
                                    Sum( COALESCE ( st.total_cost * st.quantity_balance_unit )) AS total_all_cost 
                                    FROM erp_stock_trans AS st
                                     WHERE  st.is_close = 0 AND st.tran_date <= '{$tran_date}' {$w_product_id} AND
                                    st.tran_type IN ('PURCHASE', 'OPENING QUANTITY', 'ADJUSTMENT', 'CONVERT')  GROUP BY st.product_id ");

        if ($q_all_purchase_stock_trans->num_rows() > 0) {
            foreach ($q_all_purchase_stock_trans->result() as $row) {

                if (isset($row->product_id)) {
                    if ($row->product_id) {

                        $avg_cost = $row->total_all_qty == 0 ? 0 : ($row->total_all_cost / $row->total_all_qty);

                        $ci->db->update('erp_products', ['cost' => $avg_cost], ['id' => $row->product_id]);
                        $ci->db->query("UPDATE erp_product_variants SET
                                    cost = {$avg_cost} * qty_unit WHERE product_id = '{$row->product_id}' ");

                        $ci->db->query("UPDATE erp_stock_trans SET
                                    total_cost = {$avg_cost}, avg_cost = {$avg_cost} WHERE
                                    (tran_type <> 'PURCHASE' AND tran_type <> 'OPENING QUANTITY') AND
                                    product_id = '{$row->product_id}' AND tran_date = '{$tran_date}' ;");

                        updateAVGSale($tran_date, $avg_cost, $row->product_id);
                    }
                }
            }
        }
        //====================================================
        //====================================================
        //====================================================
        //====================================================
        //====================================================


        /*
                $q_date = $ci->db->query("SELECT MIN(st.tran_date) AS min_tran_date, MAX(st.tran_date) AS max_tran_date FROM
                              erp_stock_trans AS st WHERE st.is_close = 0 $w_product_id ");

                */


        //return $arr;
    }
}

if (!function_exists('updateAVGSale')) {
    function updateAVGSale($tran_date, $avg, $product_id)
    {
        $ci =& get_instance();

        $q_sales = $ci->db->query("SELECT * FROM erp_sales WHERE date >= '{$tran_date}'");

        if ($q_sales->num_rows() > 0) {
            foreach ($q_sales->result() as $row_sale) {
                $ci->db->query("UPDATE erp_sale_items SET unit_cost = '{$avg}' WHERE sale_id = '{$row_sale->id}' 
                                AND product_id = '{$product_id}'");

                $q_total_cost = $ci->db->query("SELECT SUM(unit_cost * quantity) AS total_cost FROM erp_sale_items 
                                                    WHERE sale_id ='{$row_sale->id}'");

                if ($q_total_cost->num_rows() > 0) {
                    $sum_total_cost = $q_total_cost->row()->total_cost;
                    $ci->db->query("UPDATE erp_sales SET total_cost = '{$sum_total_cost}', updated_count = updated_count + 1
                                     WHERE id = '{$row_sale->id}'");
                }
            }
        }
    }
}

if (!function_exists('optimizeSale')) {
    function optimizeSale($tran_date)
    {
        $ci =& get_instance();
        $arr_product_id = [];

        $q_all_sales = $ci->db->query("SELECT * FROM erp_sales
                              WHERE  DATE(`date`) >= DATE('{$tran_date}') 
                              ORDER BY `date` ASC; ");

        if ($q_all_sales->num_rows() > 0) {
            foreach ($q_all_sales->result() as $row_all_sale) {
                $status = false;
                $q_sale_detail = $ci->db->query("SELECT * FROM  erp_sale_items
                WHERE sale_id = '{$row_all_sale->id}' ");
                if ($q_sale_detail->num_rows() > 0) {
                    $ci->db->query("DELETE FROM erp_stock_trans WHERE 
                    tran_type = 'SALE' AND tran_id = '{$row_all_sale->id}'");

                    // check if sale as combo product
                    foreach ($q_sale_detail->result() as $item) {
                        if($item->product_type == 'combo'){
                            $status = true;
                        }
                    }

                    if($status == true){
                        $q_purchase_detail = $ci->db->query("SELECT * FROM  erp_purchase_items
                        WHERE transaction_type = 'SALE' and sale_id = '{$row_all_sale->id}' ");

                        foreach ($q_purchase_detail->result() as $item) {
                            if ($item->warehouse_id > 0) {
                                $ci->db->insert('stock_trans',
                                    [
                                        'biller_id' => $row_all_sale->biller_id,
                                        'purchase_item_id' => 0,
                                        'tran_date' => $row_all_sale->date,
                                        'product_id' => $item->product_id,
                                        'warehouse_id' => $item->warehouse_id,
                                        'option_id' => $item->option_id,
                                        'quantity' => $item->quantity,
                                        'quantity_balance_unit' => $item->quantity_balance,
                                        'tran_type' => 'SALE',
                                        'tran_id' => $row_all_sale->id,
                                        'manufacture_cost' => 0,
                                        'freight_cost' => 0,
                                        'total_cost' => $item->unit_cost,
                                        'expired_date' => $item->expiry,
                                        'serial' => $item->serial_no
                                    ]);

                                //Will use this function in the future
                                updateStockOnHand($item->product_id);
                                $arr_product_id[$item->product_id] = $item->product_id;

                            }
                        }

                    }else{

                        foreach ($q_sale_detail->result() as $item) {
                            if ($item->warehouse_id > 0) {

                                $ci->db->insert('stock_trans',
                                    [
                                        'biller_id' => $row_all_sale->biller_id,
                                        'purchase_item_id' => 0,
                                        'tran_date' => $row_all_sale->date,
                                        'product_id' => $item->product_id,
                                        'warehouse_id' => $item->warehouse_id,
                                        'option_id' => $item->option_id,
                                        'quantity' => (-1) * $item->quantity,
                                        'quantity_balance_unit' => (-1) * $item->quantity_balance,
                                        'tran_type' => 'SALE',
                                        'tran_id' => $row_all_sale->id,
                                        'manufacture_cost' => 0,
                                        'freight_cost' => 0,
                                        'total_cost' => $item->unit_cost,
                                        'expired_date' => $item->expiry,
                                        'serial' => $item->serial_no
                                    ]);

                                //Will use this function in the future
                                updateStockOnHand($item->product_id);
                                $arr_product_id[$item->product_id] = $item->product_id;

                            }
                        }
                    }


                }

            }

            //Will use this function in the future
            //getAvgCost($tran_date, $arr_product_id);

        }
    }
}

if (!function_exists('optimizeSaleReturn')) {
    function optimizeSaleReturn($tran_date)
    {
        $ci =& get_instance();

        $arr_product_id = [];

        $q_all_return_sales = $ci->db->query("SELECT * FROM erp_return_sales
                              WHERE  DATE(`date`) >= DATE('{$tran_date}') 
                              ORDER BY `date` ASC; ");
        if ($q_all_return_sales->num_rows() > 0) {
            foreach ($q_all_return_sales->result() as $row_return_sale) {

                $q_return_items = $ci->db->query("SELECT * FROM  erp_return_items
                      WHERE return_id = '{$row_return_sale->id}' ");

                if ($q_return_items->num_rows() > 0) {

                    $ci->db->query("DELETE FROM erp_stock_trans WHERE 
                      tran_type = 'SALE RETURN' AND tran_id = '{$row_return_sale->id}'");

                    foreach ($q_return_items->result() as $item) {
                        $option_value = 1;
                        if ($item->option_id) {
                            $q_product_variant = $ci->db->query("SELECT * FROM erp_product_variants
                                WHERE id = {$item->option_id}");
                            if ($q_product_variant->num_rows() > 0) {
                                $option_value = $q_product_variant->row()->qty_unit;
                            }
                        }
                        $ci->db->insert('stock_trans',
                            [
                                'biller_id' => $row_return_sale->biller_id,
                                'purchase_item_id' => 0,
                                'tran_date' => $row_return_sale->date,
                                'product_id' => $item->product_id,
                                'warehouse_id' => $item->warehouse_id,
                                'option_id' => $item->option_id,
                                'quantity' => $item->quantity,
                                'quantity_balance_unit' => ($option_value * $item->quantity),
                                'tran_type' => 'SALE RETURN',
                                'tran_id' => $row_return_sale->id,
                                'manufacture_cost' => 0,
                                'freight_cost' => 0,
                                'total_cost' => $item->unit_cost,
                                'expired_date' => NULL,
                                'serial' => $item->serial_no
                            ]);

                        //Will use this function in the future
                        updateStockOnHand($item->product_id);
                        $arr_product_id[$item->product_id] = $item->product_id;

                    }
                }

            }

            //Will use this function in the future
            //getAvgCost($tran_date, $arr_product_id);

        }
    }
}

if (!function_exists('optimizeDelivery')) {
    function optimizeDelivery($tran_date)
    {
        $ci =& get_instance();

        $arr_product_id = [];

        $q_all_delivery = $ci->db->query("SELECT * FROM erp_deliveries
                              WHERE  DATE(`date`) >= DATE('{$tran_date}') 
                              AND delivery_status = 'completed'
                              AND `type` = 'sale_order'
                              ORDER BY `date` ASC; ");
        if ($q_all_delivery->num_rows() > 0) {
            foreach ($q_all_delivery->result() as $row_delivery) {

                $q_del_detail = $ci->db->query("SELECT * FROM  erp_delivery_items
                      WHERE delivery_id = '{$row_delivery->id}' ");

                if ($q_del_detail->num_rows() > 0) {

                    $ci->db->query("DELETE FROM erp_stock_trans WHERE 
                      tran_type = 'DELIVERY' AND tran_id = '{$row_delivery->id}'");

                    foreach ($q_del_detail->result() as $item) {

                        $option_value = 1;
                        if ($item->warehouse_id > 0 && $item->product_id > 0) {
                            if ($item->option_id) {
                                $q_product_variant = $ci->db->query("SELECT * FROM erp_product_variants
                                WHERE id = {$item->option_id}");
                                if ($q_product_variant->num_rows() > 0) {
                                    $option_value = $q_product_variant->row()->qty_unit;
                                }
                            }

                            $ci->db->insert('stock_trans',
                                [
                                    'biller_id' => $row_delivery->biller_id,
                                    'purchase_item_id' => 0,
                                    'tran_date' => $row_delivery->date,
                                    'product_id' => $item->product_id,
                                    'warehouse_id' => $item->warehouse_id,
                                    'option_id' => $item->option_id,
                                    'quantity' => -$item->quantity,
                                    'quantity_balance_unit' => -($option_value * $item->quantity),
                                    'tran_type' => 'SALE',
                                    'tran_id' => $row_delivery->id,
                                    'manufacture_cost' => 0,
                                    'freight_cost' => 0,
                                    'total_cost' => $item->cost,
                                    'expired_date' => $item->expiry,
                                    'serial' => 0
                                ]);

                            //Will use this function in the future
                            updateStockOnHand($item->product_id);
                            $arr_product_id[$item->product_id] = $item->product_id;

                        }
                    }
                }
            }

            //Will use this function in the future
            //getAvgCost($tran_date, $arr_product_id);

        }
    }
}

if (!function_exists('optimizeUsing')) {
    function optimizeUsing($tran_date)
    {
        $ci =& get_instance();

        $arr_product_id = [];

        $q_all_using = $ci->db->query("SELECT * FROM erp_enter_using_stock
                              WHERE  DATE(`date`) >= DATE('{$tran_date}') 
                              ORDER BY `date` ASC; ");
        if ($q_all_using->num_rows() > 0) {
            foreach ($q_all_using->result() as $row_using) {

                $q_using_detail = $ci->db->query("SELECT * FROM  erp_enter_using_stock_items
                      WHERE reference_no = '{$row_using->reference_no}' ");

                if ($q_using_detail->num_rows() > 0) {

                    if ($row_using->type == 'use') {
                        $ci->db->query("DELETE FROM erp_stock_trans WHERE 
                              tran_type = 'USING STOCK'
                              AND tran_id = '{$row_using->id}'");
                    } else {
                        $ci->db->query("DELETE FROM erp_stock_trans WHERE 
                              tran_type = 'RETURN USING STOCK'
                              AND tran_id = '{$row_using->id}'");
                    }
                    foreach ($q_using_detail->result() as $item) {

                        $option_value = 1;
                        if ($item->product_id > 0 && $item->warehouse_id > 0) {
                            if ($item->option_id) {
                                $q_product_variant = $ci->db->query("SELECT * FROM erp_product_variants
                                WHERE id = {$item->option_id}");
                                if ($q_product_variant->num_rows() > 0) {
                                    $option_value = $q_product_variant->row()->qty_unit;
                                }
                            }

                            if ($row_using->type == 'use') {
                                $ci->db->insert('stock_trans',
                                    [
                                        'biller_id' => $row_using->shop,
                                        'purchase_item_id' => 0,
                                        'tran_date' => $row_using->date,
                                        'product_id' => $item->product_id,
                                        'warehouse_id' => $item->warehouse_id,
                                        'option_id' => $item->option_id,
                                        'quantity' => -$item->qty_use,
                                        'quantity_balance_unit' => -($option_value * $item->qty_use),
                                        'tran_type' => 'USING STOCK',
                                        'tran_id' => $row_using->id,
                                        'manufacture_cost' => $item->cost,
                                        'freight_cost' => 0,
                                        'total_cost' => $item->cost,
                                        'expired_date' => $item->expiry,
                                        'serial' => 0
                                    ]);
                            } else {
                                $ci->db->insert('stock_trans',
                                    [
                                        'biller_id' => $row_using->shop,
                                        'purchase_item_id' => 0,
                                        'tran_date' => $row_using->date,
                                        'product_id' => $item->product_id,
                                        'warehouse_id' => $item->warehouse_id,
                                        'option_id' => $item->option_id,
                                        'quantity' => $item->qty_use,
                                        'quantity_balance_unit' => $option_value * $item->qty_use,
                                        'tran_type' => 'RETURN USING STOCK',
                                        'tran_id' => $row_using->id,
                                        'manufacture_cost' => 0,
                                        'freight_cost' => 0,
                                        'total_cost' => $item->cost,
                                        'expired_date' => $item->expiry,
                                        'serial' => 0
                                    ]);
                            }

                            //Will use this function in the future
                            updateStockOnHand($item->product_id);
                            $arr_product_id[$item->product_id] = $item->product_id;

                        }
                    }
                }
            }

            //Will use this function in the future
            //getAvgCost($tran_date, $arr_product_id);

        }
    }
}
