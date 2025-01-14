<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Loan extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('mLoan');
        $this->load->model('mFine');
        $this->load->library('session');
        $this->load->helper('url');
    }

    public function index()
    {
        $data['title'] = "Halaman Peminjaman";
        $this->load->view('templates/header', $data);
        $this->load->view('loan/index');
        $this->load->view('templates/footer');
    }

    public function showData()
    {
        $data = $this->mLoan->getLoan();
        echo json_encode($data, JSON_PRETTY_PRINT);
    }

    public function showDataByUser()
    {
        $data = $this->mLoan->getLoanByUser($this->session->userdata('user_id'));
        echo json_encode($data, JSON_PRETTY_PRINT);
    }

    public function confirmLoan()
    {
        $loan_id = $this->input->post('loan_id');
        if ($loan_id) {
            $data = [
                'loan_date' => date('Y-m-d H:i:s'),
                'status' => 'borrowed'
            ];
            if ($this->mLoan->updateLoan($loan_id, $data)) {
                $response = array('response' => 'success', 'message' => 'Peminjamanan berhasil...');
            } else {
                $response = array('response' => 'error', 'message' => 'Peminjaman gagal...');
            }
        } else {
            $response = array('response' => 'error', 'message' => 'Peminjaman belum dipilih...');
        }
        echo json_encode($response);
    }

    public function returnBook()
    {
        $loan_id = $this->input->post('loan_id');
        $loan_date = new DateTime($this->input->post('loan_date'));
        $return_date = new DateTime();
        $interval = $loan_date->diff($return_date)->days;

        if ($loan_id) {
            $data = [
                'return_date' => date('Y-m-d H:i:s'),
                'status' => 'returned'
            ];
            $this->db->trans_start();
            if ($this->mLoan->updateLoan($loan_id, $data)) {
                if ($interval > 5) {
                    $fineData = [
                        'loan_id' => $loan_id,
                        'total' => ($interval - 5) * 5000,
                        'status' => 0
                    ];
                    if (!$this->mFine->addFine($fineData)) {
                        $this->db->trans_rollback();
                        $response = array('response' => 'error', 'message' => 'Terjadi kesalahan saat menambahkan denda.');
                        echo json_encode($response);
                        return;
                    }
                }
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $response = array('response' => 'error', 'message' => 'Transaksi gagal, silakan coba lagi.');
                } else {
                    $response = array('response' => 'success', 'message' => $interval > 5 ? 'Buku berhasil dikembalikan dengan denda...' : 'Buku berhasil dikembalikan...');
                }
            } else {
                $this->db->trans_rollback();
                $response = array('response' => 'error', 'message' => 'Gagal mengembalikan buku.');
            }
        } else {
            $response = array('response' => 'error', 'message' => 'Peminjaman belum dipilih...');
        }
        echo json_encode($response);
    }


    public function cancelLoan()
    {
        if ($this->input->is_ajax_request()) {
            $loan_id = $this->input->post('loan_id');
            if ($loan_id) {
                $data = ['status' => 'canceled'];
                if ($this->mLoan->updateLoan($loan_id, $data)) {
                    $response = array('response' => 'success', 'message' => 'Peminjamanan berhasil dibatalkan...');
                } else {
                    $response = array('response' => 'error', 'message' => 'Peminjaman gagal dibatalkan...');
                }
            } else {
                $response = array('response' => 'error', 'message' => 'Peminjaman belum dipilih...');
            }
        } else {
            $response = array('response' => 'error', 'message' => 'Anda tidak memiliki akses...');
        }
        echo json_encode($response);
    }

}
