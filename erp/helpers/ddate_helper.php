<?php defined('BASEPATH') OR exit('No direct script access allowed');

if(! function_exists('dateAdd')) {
    function createDateRange($startDate, $endDate, $format = "Y-m-d")
    {
        $begin = new \DateTime($startDate);

        $_end = dateAdd($endDate,'DAY',1);

        $end = new \DateTime($_end);

        $interval = new \DateInterval('P1D'); // 1 Day
        $dateRange = new \DatePeriod($begin, $interval, $end);

        $range = [];
        foreach ($dateRange as $date) {
            if($format == null){
                $range[] = $date;
            }else{
                $range[] = $date->format($format);
            }

        }


        return $range;
    }

}

if(! function_exists('dateAdd')) {
    function dateAdd($date, $unit, $num_unit)
    {
        $ci =& get_instance();
        $sql = "SELECT DATE_ADD('{$date}', INTERVAL {$num_unit} {$unit}) as d";
        $d = $ci->db->query($sql);
        if ($d->num_rows()>0) {
            return $d->row()->d;
        } else {
            return null;
        }
    }
}

if(! function_exists('dateDiff')) {
    function dateDiff($f_date, $t_date)
    {
        $ci =& get_instance();
        $sql = "SELECT DATEDIFF('{$t_date}', '{$f_date}') as d";
        $d = $ci->db->query($sql);
        if ($d->num_rows()>0) {
            return $d->row()->d;
        } else {
            return null;
        }
    }
}
