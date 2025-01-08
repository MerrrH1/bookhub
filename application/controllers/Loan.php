<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Loan extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Load necessary models
        $this->load->model('mLoan');
        $this->load->library('session');
        $this->load->helper('url');
    }

    public function index() {
        $data['title'] = "Halaman Peminjaman";
        $this->load->view('templates/header', $data);
        $this->load->view('loan/index');
        $this->load->view('templates/footer');
    }

    public function showData() {
        $data = $this->mLoan->getData();
        echo json_encode($data);
    }

    public function simpanBuku() {
        $book_id = $this->input->post('book_id');
        if (!$this->mLoan->isBookAvailable($book_id)) {
            $response = array('response' => 'success', 'message' => 'Buku tidak tersedia');
        } else {
            $data = [
                'user_id' => $this->session->userdata('user_id'),
                'loan_data'=> date('Y-m-d H:i:s'),
                'return_date' => NULL
            ];
            $data = $this->security->xss_clean($data);
            try {
                if($this->mLoan->addLoan($data)) {
                    $response = array('response' => 'success', 'message' => 'Berhasil disimpan');
                } else {
                    $respomnse = array('response' => 'error', 'message' => 'Terjadi kesalaham. data gagal disimpan');
                }
            } catch(Exception $e) {}
            $response = array('response' => 'error', 'message' => $e->getMessage());
        }
        echo json_encode($response);
    }
}
