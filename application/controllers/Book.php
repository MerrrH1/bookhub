<?php

class Book extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('mBook');
        $this->load->model('mCategory');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data['title'] = "Halaman Buku";
        $data['category'] = $this->mCategory->getData();
        $this->load->view('templates/header', $data);
        $this->load->view("book/index");
        $this->load->view('templates/footer');
    }

    public function showData()
    {
        $data = $this->mBook->getData();
        echo json_encode($data);
    }

    public function showDataById()
    {
        $book_id = $this->input->post('book_id');
        $data = $this->mBook->getDataById($book_id);
        echo json_encode($data);
    }

    public function addData()
    {
        $this->form_validation->set_rules('title', 'Judul Buku', 'required');
        $this->form_validation->set_rules('author', 'Penulis', 'required');
        $this->form_validation->set_rules('publisher', 'Penerbit', 'required');
        $this->form_validation->set_rules('category', 'Kategori', 'required');
        $this->form_validation->set_rules('year', 'Tahun Terbit', 'required');
        $this->form_validation->set_rules('isbn', 'ISBN', 'required');
        $this->form_validation->set_rules('quantity', 'Quantity', 'required');
        $this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');

        if ($this->form_validation->run() == FALSE) {
            $response = array('responce' => 'error', 'message' => validation_errors());
        } else {
            $title = $this->input->post('title');
            $validData = $this->mBook->checkDuplicate($title);
            if ($validData >= 1) {
                $response = array('responce' => 'error', 'message' => 'Buku Sudah Terdaftar..');
            } else {
                $data = array(
                    'title' => $title,
                    'category_id' => $this->input->post('category'),
                    'author' => $this->input->post('author'),
                    'publisher' => $this->input->post('publisher'),
                    'year' => $this->input->post('year'),
                    'isbn' => $this->input->post('isbn'),
                    'quantity' => $this->input->post('quantity'),
                    'created_at' => date('Y-m-d h:i:s'),
                    'last_modified' => date('Y-m-d h:i:s'),
                );
                $data = $this->security->xss_clean($data);
                $response = $this->mBook->insertData($data) ?
                    array('responce' => 'success', 'message' => 'Data berhasil ditambah...') :
                    array('responce' => 'error', 'message' => 'Terjadi kesalahan, data gagal disimpan...');
            }
        }
        echo json_encode($response);
    }

    public function modifyData()
    {
        $this->form_validation->set_rules('title', 'Judul Buku', 'required');
        $this->form_validation->set_rules('author', 'Penulis', 'required');
        $this->form_validation->set_rules('publisher', 'Penerbit', 'required');
        $this->form_validation->set_rules('category', 'Kategori', 'required');
        $this->form_validation->set_rules('year', 'Tahun Terbit', 'required');
        $this->form_validation->set_rules('isbn', 'ISBN', 'required');
        $this->form_validation->set_rules('quantity', 'Quantity', 'required');
        $this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');

        if ($this->form_validation->run() == FALSE) {
            $response = array('responce' => 'error', 'message' => validation_errors());
        } else {
            $book_id = $this->input->post('book_id');
            $data = array(
                'title' => $this->input->post('title'),
                'category_id' => $this->input->post('category'),
                'author' => $this->input->post('author'),
                'publisher' => $this->input->post('publisher'),
                'year' => $this->input->post('year'),
                'isbn' => $this->input->post('isbn'),
                'quantity' => $this->input->post('quantity'),
                'last_modified' => date('Y-m-d h:i:s'),
            );
            $data = $this->security->xss_clean($data);
            $response = $this->mBook->updateData($book_id, $data) ?
                array('responce' => 'success', 'message' => 'Data berhasil diubah...') :
                array('responce' => 'error', 'message' => 'Terjadi kesalahan, data gagal disimpan...');
        }

        echo json_encode($response);
    }

    public function removeData()
    {
        if ($this->input->is_ajax_request()) {
            $book_id = $this->input->post('book_id');
            $response = $this->mBook->deleteData($book_id) ?
                array('responce' => 'success') : array('responce' => 'error');
            echo json_encode($response);
        } else {
            echo "No direct script access allowed";
        }
    }
}

?>