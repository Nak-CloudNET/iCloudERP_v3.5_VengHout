<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Cron_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function run_cron()
    {
        $m = '';
        if ($this->resetOrderRef()) {
            $m .= '<p>Reference number has been reset.</p>';
        }
        $pendingInvoices = $this->getAllPendingInvoices();
        if (!empty($pendingInvoices)) {
            $p = 0;
            foreach ($pendingInvoices as $invoice) {
                $this->updateInvoiceStatus($invoice->id);
                $p++;
            }
            $m .= '<p>' . $p . ' pending invoices status has been changed to due.</p>';
        }
        $partialInvoices = $this->getAllPPInvoices();
        if (!empty($partialInvoices)) {
            $pp = 0;
            foreach ($partialInvoices as $invoice) {
                $this->updateInvoiceStatus($invoice->id);
                $pp++;
            }
            $m .= '<p>' . $pp . ' partially paid invoices status has been changed to due.</p>';
        }
        if ($pis = $this->get_expired_products()) {
            $e = 0; $ep = 0;
            foreach($pis as $pi) {
                $this->db->update('purchase_items', array('quantity_balance' => 0), array('id' => $pi->id));
                $e++;
                $ep += $pi->quantity_balance;
            }
            $this->site->syncQuantity(NULL, NULL, $pis);
            $m .= '<p>'.$e.' products with total quantity of '.$ep.' are expired.</p>';
        }
        $date = date('Y-m-d H:i:s', strtotime('-1 month'));
        if ($this->deleteUserLgoins($date)) {
            $m .= '<p>User login records previous to ' . $date . ' had been deleted.</p>';
        }
        if ($this->checkUpdate()) {
            $m .= '<p>New update(s) available, please visit the updates menu under settings.</p>';
        }
        $r = $m != '' ? $m : false;
        return $r;
    }

    public function getAllPendingInvoices()
    {
        $today = date('Y-m-d');
        $paid = $this->lang->line('paid');
        $canceled = $this->lang->line('cancelled');
        $q = $this->db->get_where('sales', array('due_date <=' => $today, 'due_date !=' => '1970-01-01', 'due_date !=' => NULL, 'payment_status' => 'pending'));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getAllPPInvoices()
    {
        $today = date('Y-m-d');
        $paid = $this->lang->line('paid');
        $canceled = $this->lang->line('cancelled');
        $q = $this->db->get_where('sales', array('due_date <=' => $today, 'due_date !=' => '1970-01-01', 'due_date !=' => NULL, 'payment_status' => 'partial'));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function updateInvoiceStatus($id)
    {
        if ($this->db->update('sales', array('payment_status' => 'due'), array('id' => $id))) {
            return TRUE;
        }
        return FALSE;
    }

    public function resetOrderRef()
    {
        $settings = $this->getSettings();
        if ($settings->reference_format == 1 || $settings->reference_format == 2) {
            $month = date('Y-m') . '-01';
            $year = date('Y') . '-01-01';
            if ($ref = $this->getOrderRef()) {
                $reset_ref = array('date' => $month, 'so' => 1, 'qu' => 1, 'po' => 1, 'to' => 1, 'pos' => 1, 'do' => 1, 'pay' => 1, 're' => 1, 'ex' => 1);
                if ($settings->reference_format == 1) {
                    if (strtotime($ref->date) < strtotime($year)) {
                        $this->db->update('order_ref', $reset_ref, array('ref_id' => 1));
                        return TRUE;
                    }
                } elseif ($settings->reference_format == 2) {
                    if (strtotime($ref->date) < strtotime($month)) {
                        $this->db->update('order_ref', $reset_ref, array('ref_id' => 1));
                        return TRUE;
                    }
                }
            }
        }
        return FALSE;
    }

    public function getOrderRef()
    {
        $q = $this->db->get_where('order_ref', array('ref_id' => 1), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getSettings()
    {
        $q = $this->db->get_where('settings', array('setting_id' => 1), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function deleteUserLgoins($date)
    {
        $this->db->where('time <', $date);
        if ($this->db->delete('user_logins')) {
            return true;
        }
        return FALSE;
    }

    public function checkUpdate()
    {
        $settings = $this->getSettings();
        $fields = array('version' => $settings->version, 'code' => $settings->purchase_code, 'username' => $settings->envato_username, 'site' => base_url());
        $this->load->helper('update');
        $protocol = is_https() ? 'https://' : 'http://';
        $updates = get_remote_contents($protocol.'tecdiary.com/api/v1/update/', $fields);
        $response = json_decode($updates);
        if (!empty($response->data->updates)) {
            $this->db->update('settings', array('update' => 1), array('setting_id' => 1));
            return TRUE;
        }
        return FALSE;
    }

    public function get_expired_products() {
        $settings = $this->getSettings();
        if ($settings->remove_expired) {
            $date = date('Y-m-d');
            $this->db->where('expiry <=', $date)->where('expiry !=', NULL)->where('expiry !=', '0000-00-00')->where('quantity_balance >', 0);
            $q = $this->db->get('purchase_items');
            if ($q->num_rows() > 0) {
                foreach (($q->result()) as $row) {
                    $data[] = $row;
                }
                return $data;
            }
        }
        return FALSE;
    }

}
