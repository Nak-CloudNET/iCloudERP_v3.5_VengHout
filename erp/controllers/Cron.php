<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Cron extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
    }

    function index()
    {
        show_404();
    }

    function run()
    {
        $this->load->model('cron_model');
        if ($m = $this->cron_model->run_cron()) {
            echo '<p>Corn job successfully run</p>' . $m;
        } else {
            echo 'Corn job failed!';
        }
    }

    function log_corn($msg, $val = NULL)
    {
        $this->load->library('logs');
        return (bool)$this->logs->write('corn', $msg, $val);
    }

}
