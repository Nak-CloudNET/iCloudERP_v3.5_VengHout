<?php defined('BASEPATH') OR exit('No direct script access allowed');
if (!function_exists('dd')) {

    /**
     * Dumps a given variable along with some additional data.
     *
     * @param mixed $var
     * @param bool  $pretty
     */
    function dd($var, $pretty = true)
    {
        $backtrace = debug_backtrace();

        echo "\n<pre>\n";

        if (isset($backtrace[0]['file'])) {
            echo $backtrace[0]['file'] . "\n\n";
        }

        echo "Type: " . gettype($var) . "\n";
        echo "Time: " . date('c') . "\n";
        echo "---------------------------------\n\n";

        ($pretty) ? print_r($var) : var_dump($var);

        echo "</pre>\n";

        die;
    }

    /*function dd($data) {
        $ci =& get_instance();

        return $ci->erp->print_arrays($data);

    }*/

}