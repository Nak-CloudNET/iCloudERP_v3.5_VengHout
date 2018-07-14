<?php defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('getLastCloseDate')) {
    function getLastCloseDate()
    {
        $ci =& get_instance();

        $q = $ci->db->query("SELECT close_date FROM erp_stock_trans
                              WHERE is_close = '1' ORDER BY close_date DESC LIMIT 1");
        if ($q->num_rows() > 0) {
            return $q->row()->close_date;
        }
        return false;
    }
}

if (!function_exists('isCloseDate')) {
    function isCloseDate($tran_date)
    {
        $ci =& get_instance();
        $re = true;
        $close_date = getLastCloseDate();
        if($close_date == false){ }else{
            $dateDif = dateDiff($close_date,$tran_date);
            if($dateDif != null){
                $re = $dateDif > 0;
            }
        }
        return $re;
    }
}

if (!function_exists('getProductInfo')) {
    function getProductInfo($product_id)
    {
        $ci =& get_instance();

        $q = $ci->db->query("SELECT * FROM erp_products
                              WHERE id = '{$product_id}' LIMIT 1");
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }
}

if (!function_exists('updateStockOnHand')) {
    function updateStockOnHand($product_id = NULL, $warehouse_id = NULL)
    {
        $ci =& get_instance();

        $where          = ($product_id == NULL ? '' : 'WHERE st.product_id = '. ($product_id-0));
        $where_ware_id  = ($warehouse_id == NULL ? '' : ' AND st.warehouse_id = ' . ($warehouse_id - 0));

        //        $q = $ci->db->query("SELECT
        //                st.product_id,
        //                st.option_id,
        //                st.warehouse_id,
        //                Sum(COALESCE (st.quantity_balance_unit, 0) ) AS qty
        //                FROM
        //                erp_stock_trans AS st
        //                {$where}
        //                GROUP BY
        //                st.product_id,
        //                st.option_id,
        //                st.warehouse_id");

        //===================================
        //===================================
        //===================================
        //===================================
        $q3 = $ci->db->query("SELECT
                st.product_id,
                pv.id as option_id,
                st.warehouse_id,
                Sum(COALESCE (st.quantity_balance, 0) ) AS qty
                FROM
                erp_product_variants pv 
                LEFT JOIN erp_purchase_items st ON pv.product_id = st.product_id
                {$where}{$where_ware_id}
                GROUP BY
                pv.id,
                st.warehouse_id");

        $q2 = $ci->db->query("SELECT
                st.product_id,
                st.warehouse_id,
                Sum(COALESCE (st.quantity_balance, 0) ) AS qty
                FROM
                erp_purchase_items AS st
                {$where}{$where_ware_id}
                GROUP BY
                st.product_id,
                st.warehouse_id");

        $q1 = $ci->db->query("SELECT
                st.product_id,
                Sum(COALESCE (st.quantity_balance, 0) ) AS qty
                FROM
                erp_purchase_items AS st
                {$where}{$where_ware_id}
                GROUP BY
                st.product_id
                ");

        if ($q3->num_rows() > 0) {
            foreach ($q3->result() as $row) {
                $ci->db->update('warehouses_products_variants', array('quantity' => $row->qty),
                    array('product_id' => $row->product_id,
                        'warehouse_id' => $row->warehouse_id,
                        'option_id' => $row->option_id));
            }
        }

        if ($q2->num_rows() > 0) {
            foreach ($q2->result() as $row) {
                $ci->db->update('warehouses_products', array('quantity' => $row->qty),
                    array('product_id' => $row->product_id,
                        'warehouse_id' => $row->warehouse_id));
            }
        }

        if ($q1->num_rows() > 0) {
            foreach ($q1->result() as $row) {
                $ci->db->update('products', array('quantity' => $row->qty),
                    array('id' => $row->product_id));
            }
        }
        //===================================
        //===================================
        //===================================
        //===================================

        return false;
    }
}

if (!function_exists('optimizeOpeningQuantity')) {
    function optimizeOpeningQuantity($tran_date)
    {
        $ci =& get_instance();

        $arr_product_id = [];

        $q_open_items = $ci->db->query("SELECT * FROM erp_purchase_items
                            WHERE transaction_type = 'OPENING QUANTITY' AND DATE(`date`) >= DATE('{$tran_date}')");

             if(count($q_open_items)>0){

                 $ci->db->query("DELETE FROM erp_stock_trans WHERE tran_type = 'OPENING QUANTITY' AND DATE(`tran_date`) >= DATE('{$tran_date}')");

                 foreach ($q_open_items->result() as $item) {
                     if ($item->product_id > 0) {
                         $ci->db->insert('stock_trans',
                             [
                                 'biller_id' => $ci->Settings->default_biller,
                                 'purchase_item_id' => $item->id,
                                 'tran_date' => $item->date,
                                 'product_id' => $item->product_id,
                                 'warehouse_id' => $item->warehouse_id,
                                 'option_id' => $item->option_id,
                                 'quantity' => $item->quantity,
                                 'quantity_balance_unit' => $item->quantity_balance,
                                 'tran_type' => 'OPENING QUANTITY',
                                 'tran_id' => $item->id,
                                 'raw_cost' => $item->real_unit_cost,
                                 'freight_cost' => 0,
                                 'total_cost' => $item->real_unit_cost,
                                 'expired_date' => $item->expiry,
                                 'serial' => $item->serial_no
                             ]);

                         //Will use this function in the future
                         updateStockOnHand($item->product_id, $item->warehouse_id);
                         $arr_product_id[$item->product_id] = $item->product_id;
                     }
                 }
             }
            //Will use this function in the future
            if(count($arr_product_id)>0)
            getAvgCost($tran_date, $arr_product_id);


    }
}

if (!function_exists('optimizePurchases')) {
    function optimizePurchases($tran_date)
    {
        $ci =& get_instance();

        $arr_product_id = [];

        $q_all_purchases = $ci->db->query("SELECT * FROM erp_purchases 
                            WHERE DATE(`date`) >= DATE('{$tran_date}') ORDER BY `date` ASC; ");

        if ($q_all_purchases->num_rows() > 0) {
            foreach ($q_all_purchases->result() as $row_purchase) {
                $q_purchase_items = $ci->db->query("SELECT * FROM erp_purchase_items
                                    WHERE purchase_id = '{$row_purchase->id}' 
                                    AND transaction_type = 'PURCHASE'; ");


                if ($q_purchase_items->num_rows() > 0) {

                    $items = [];
                    $services = [];
                    $total_services = ($row_purchase->shipping - 0);
                    $total_item_cost = 0;

                    foreach ($q_purchase_items->result() as $row_purchase_item) {
                        $product_id = $row_purchase_item->product_id;
                        $product_info = getProductInfo($product_id);
                        if ($product_info->type == 'service') {
                            if ($product_info->service_type - 0 == 1) {
                                $services[] = $row_purchase_item;
                                $total_services += ($row_purchase_item->subtotal - 0);
                            }
                        } else {
                            $items[] = $row_purchase_item;
                            $total_item_cost += ($row_purchase_item->subtotal - 0);
                        }
                    }

                    $ci->db->query("DELETE FROM erp_stock_trans WHERE tran_type = 'PURCHASE' AND tran_id = '{$row_purchase->id}'");

                    foreach ($items as $item) {
                        $percentage_item = ($item->subtotal / $total_item_cost);
                        $product_cost_ship = $percentage_item * $total_services;

                        if ($item->quantity_balance - 0 > 0) {
                            $product_unit_cost = ($item->subtotal + $product_cost_ship) / $item->quantity_balance;
                        } else {
                            $product_unit_cost = ($item->subtotal + $product_cost_ship);
                        }

                        $ci->db->update('purchase_items',
                            ['real_unit_cost' => $product_unit_cost], ['id' => $item->id]);

                        $ci->db->insert('stock_trans',
                            [
                                'biller_id'             => $row_purchase->biller_id,
                                'purchase_item_id'      => $item->id,
                                'tran_date'             => $row_purchase->date,
                                'reference'             => $row_purchase->reference_no,
                                'product_id'            => $item->product_id,
                                'warehouse_id'          => $item->warehouse_id,
                                'option_id'             => $item->option_id,
                                'quantity'              => $item->quantity,
                                'quantity_balance_unit' => $item->quantity_balance,
                                'tran_type'             => 'PURCHASE',
                                'tran_id'               => $row_purchase->id,
                                'manufacture_cost'      => $item->net_unit_cost,
                                'freight_cost'          => $product_cost_ship,
                                'total_cost'            => $product_unit_cost,
                                'expired_date'          => $item->expiry,
                                'serial'                => $item->serial_no
                            ]);

                        //Will use this function in the future
                        updateStockOnHand($item->product_id);
                        $arr_product_id[$item->product_id] = $item->product_id;

                    }
                }
            }

            //Will use this function in the future
            getAvgCost($tran_date, $arr_product_id);

        }
    }
}

if (!function_exists('optimizeStockAdjustment')) {
    function optimizeStockAdjustment($tran_date)
    {
        $ci =& get_instance();

        $arr_product_id = [];

        $q_all_adj = $ci->db->query("SELECT * FROM erp_adjustments 
                            WHERE DATE(`date`) >= DATE('{$tran_date}') ORDER BY `date` ASC; ");
        if($q_all_adj->num_rows() > 0) {
            foreach($q_all_adj->result() as $row_adj) {
                $q_adj_items = $ci->db->query("SELECT * FROM erp_adjustment_items 
                    WHERE adjust_id = {$row_adj->id}");

                $ci->db->query("DELETE FROM erp_stock_trans WHERE 
                      tran_type = 'ADJUSTMENT' AND tran_id = '{$row_adj->id}'");

                if($q_adj_items->num_rows() > 0) {
                    foreach($q_adj_items->result() as $row_adj_item) {
                        $option_value = 1;
                        if($row_adj_item->option_id) {
                            $q_product_variant = $ci->db->query("SELECT * FROM erp_product_variants
                                WHERE id = {$row_adj_item->option_id}");
                            if($q_product_variant->num_rows() > 0) {
                                $option_value = $q_product_variant->row()->qty_unit;
                            }
                        }
                        $ci->db->insert('stock_trans',
                            [
                                'biller_id'             => $row_adj->biller_id,
                                'purchase_item_id'      => 0,
                                'tran_date'             => $row_adj->date,
                                'reference'             => $row_adj->reference_no,
                                'product_id'            => $row_adj_item->product_id,
                                'warehouse_id'          => $row_adj->warehouse_id,
                                'option_id'             => $row_adj_item->option_id,
                                'quantity'              => $row_adj_item->quantity,
                                'quantity_balance_unit' => $option_value * $row_adj_item->quantity,
                                'tran_type'             => 'ADJUSTMENT',
                                'tran_id'               => $row_adj->id,
                                'manufacture_cost'      => 0,
                                'freight_cost'          => 0,
                                'total_cost'            => $row_adj_item->cost,
                                'expired_date'          => $row_adj_item->expiry,
                                'serial'                => $row_adj_item->serial_no
                            ]);

                        //Will use this function in the future
                        updateStockOnHand($row_adj_item->product_id);
                        $arr_product_id[$row_adj_item->product_id] = $row_adj_item->product_id;

                    }
                }
            }

            //Will use this function in the future
            //getAvgCost($tran_date, $arr_product_id);

        }
    }
}

if (!function_exists('optimizeTransferStock')) {
    function optimizeTransferStock($tran_date)
    {
        $ci =& get_instance();

        $arr_product_id = [];

        $q_all_tran = $ci->db->query("SELECT * FROM erp_transfers 
                            WHERE DATE(`date`) >= DATE('{$tran_date}') ORDER BY `date` ASC; ");
        if($q_all_tran->num_rows() > 0) {
            foreach($q_all_tran->result() as $row_tran) {
                $q_tran_items = $ci->db->query("SELECT * FROM erp_transfer_items 
                    WHERE transfer_id = {$row_tran->id}");

                $ci->db->query("DELETE FROM erp_stock_trans WHERE 
                      tran_type = 'TRANSFER' AND tran_id = '{$row_tran->id}'");

                if($q_tran_items->num_rows() > 0) {
                    foreach($q_tran_items->result() as $row_tran_item) {
                        $option_value = 1;
                        if($row_tran_item->option_id) {
                            $q_product_variant = $ci->db->query("SELECT * FROM erp_product_variants
                                WHERE id = {$row_tran_item->option_id}");
                            if($q_product_variant->num_rows() > 0) {
                                $option_value = $q_product_variant->row()->qty_unit;
                            }
                        }
                        $ci->db->insert('stock_trans',
                            [
                                'biller_id'             => $row_tran->biller_id,
                                'purchase_item_id'      => 0,
                                'tran_date'             => $row_tran->date,
                                'reference'             => $row_tran->reference_no,
                                'product_id'            => $row_tran_item->product_id,
                                'warehouse_id'          => $row_tran->from_warehouse_id,
                                'option_id'             => $row_tran_item->option_id,
                                'quantity'              => -$row_tran_item->quantity,
                                'quantity_balance_unit' => -($option_value * $row_tran_item->quantity),
                                'tran_type'             => 'TRANSFER',
                                'tran_id'               => $row_tran->id,
                                'manufacture_cost'      => 0,
                                'freight_cost'          => 0,
                                'total_cost'            => $row_tran_item->unit_cost,
                                'expired_date'          => $row_tran_item->expiry,
                                'serial'                => 0
                            ]);
                        $ci->db->insert('stock_trans',
                            [
                                'biller_id'             => $row_tran->biller_id,
                                'purchase_item_id'      => 0,
                                'tran_date'             => $row_tran->date,
                                'reference'             => $row_tran->reference_no,
                                'product_id'            => $row_tran_item->product_id,
                                'warehouse_id'          => $row_tran->to_warehouse_id,
                                'option_id'             => $row_tran_item->option_id,
                                'quantity'              => $row_tran_item->quantity,
                                'quantity_balance_unit' => $option_value * $row_tran_item->quantity,
                                'tran_type'             => 'TRANSFER',
                                'tran_id'               => $row_tran->id,
                                'manufacture_cost'      => 0,
                                'freight_cost'          => 0,
                                'total_cost'            => $row_tran_item->unit_cost,
                                'expired_date'          => $row_tran_item->expiry,
                                'serial'                => 0
                            ]);

                        //Will use this function in the future
                        updateStockOnHand($row_tran_item->product_id);
                        $arr_product_id[$row_tran_item->product_id] = $row_tran_item->product_id;

                    }
                }
            }

            //Will use this function in the future
            //getAvgCost($tran_date, $arr_product_id);

        }
    }
}

if (!function_exists('optimizeConvert')) {
    function optimizeConvert($tran_date)
    {
        $ci =& get_instance();

        $arr_product_id = [];

        $q_all_convert= $ci->db->query("SELECT * FROM erp_convert 
                            WHERE DATE(`date`) >= DATE('{$tran_date}') ORDER BY `date` ASC; ");
        if($q_all_convert->num_rows() > 0) {
            foreach($q_all_convert->result() as $row_convert) {
                $q_convert_items = $ci->db->query("SELECT * FROM erp_convert_items 
                    WHERE convert_id = {$row_convert->id}");

                $ci->db->query("DELETE FROM erp_stock_trans WHERE 
                      tran_type = 'CONVERT' AND tran_id = '{$row_convert->id}'");

                if($q_convert_items->num_rows() > 0) {
                    foreach($q_convert_items->result() as $row_convert_item) {
                        $option_value = 1;
                        if($row_convert_item->option_id) {
                            $q_product_variant = $ci->db->query("SELECT * FROM erp_product_variants
                                WHERE id = {$row_convert_item->option_id}");
                            if($q_product_variant->num_rows() > 0) {
                                $option_value = $q_product_variant->row()->qty_unit;
                            }
                        }
                        if($row_convert_item->status == 'deduct') {
                            $ci->db->insert('stock_trans',
                                [
                                    'biller_id'             => $row_convert->biller_id,
                                    'purchase_item_id'      => 0,
                                    'tran_date'             => $row_convert->date,
                                    'reference'             => $row_convert->reference_no,
                                    'product_id'            => $row_convert_item->product_id,
                                    'warehouse_id'          => $row_convert->warehouse_id,
                                    'option_id'             => $row_convert_item->option_id,
                                    'quantity'              => -$row_convert_item->quantity,
                                    'quantity_balance_unit' => -($option_value * $row_convert_item->quantity),
                                    'tran_type'             => 'CONVERT',
                                    'tran_id'               => $row_convert->id,
                                    'manufacture_cost'      => 0,
                                    'freight_cost'          => 0,
                                    'total_cost'            => $row_convert_item->cost,
                                    'expired_date'          => NULL,
                                    'serial'                => 0
                                ]);
                        }else {
                            $ci->db->insert('stock_trans',
                                [
                                    'purchase_item_id'      => 0,
                                    'tran_date'             => $row_convert->date,
                                    'reference'             => $row_convert->reference_no,
                                    'product_id'            => $row_convert_item->product_id,
                                    'warehouse_id'          => $row_convert->warehouse_id,
                                    'option_id'             => $row_convert_item->option_id,
                                    'quantity'              => $row_convert_item->quantity,
                                    'quantity_balance_unit' => $option_value * $row_convert_item->quantity,
                                    'tran_type'             => 'CONVERT',
                                    'tran_id'               => $row_convert->id,
                                    'manufacture_cost'      => 0,
                                    'freight_cost'          => 0,
                                    'total_cost'            => $row_convert_item->cost,
                                    'expired_date'          => NULL,
                                    'serial'                => 0
                                ]);
                        }

                        //Will use this function in the future
                        updateStockOnHand($row_convert_item->product_id);
                        $arr_product_id[$row_convert_item->product_id] = $row_convert_item->product_id;

                    }
                }
            }

            //Will use this function in the future
            getAvgCost($tran_date, $arr_product_id);

        }
    }
}
