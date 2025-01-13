<?php

class Fine extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('mFine');
        check_admin();
    }

    public function index()
    {
        $data['title'] = "Halaman Denda";
        $this->load->view('templates/header', $data);
        $this->load->view('fine/index');
        $this->load->view('templates/footer');
    }

    public function showData()
    {
        $data = $this->mFine->getFine();
        echo json_encode($data, JSON_PRETTY_PRINT);
    }

    public function payFine()
    {
        $fine_id = $this->input->post('fine_id');
        if ($this->input->is_ajax_request()) {
            if ($fine_id) {
                $data = [
                    'paid_date' => date('Y-m-d H:i:s'),
                    'fine_status' => 1
                ];
                if($this->mFine->updateFine($fine_id, $data)) {
                    $response = array('response' => 'success', 'message' => 'Denda sudah dibayar...');
                } else {
                    $response = array('response' => 'error', 'message' => 'Denda gagal dibayar...');
                }
            } else {
                $response = array('response' => 'error', 'message' => 'Data denda belum dipilih');
            }
        } else {
            $response = array('response' => 'error', 'message' => 'Anda tidak memiliki akses');
        }
        echo json_encode($response);
    }
}

?>