<?php

class Book extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('mBook');
        $this->load->model('mCategory');
        $this->load->model('mLoan');
        $this->load->library('form_validation');
        is_logged_in();
    }

    public function index()
    {
        $data['book'] = $this->mBook->getData();
        $data['title'] = "Halaman Buku";
        $data['category'] = $this->mCategory->getData();
        $this->load->view('templates/header', $data);
        $this->load->view("book/index");
        $this->load->view('templates/footer');
    }

    public function showData()
    {
        $data = $this->mBook->getData();
        echo json_encode($data, JSON_PRETTY_PRINT);
    }

    public function showDataById()
    {
        $book_id = $this->input->post('book_id');
        $data = $this->mBook->getBookById($book_id);
        echo json_encode($data);
    }

    public function addData()
    {
        $this->form_validation->set_rules('title', 'Judul Buku', 'required');
        $this->form_validation->set_rules('author', 'Penulis', 'required');
        $this->form_validation->set_rules('publisher', 'Penerbit', 'required');
        $this->form_validation->set_rules('category', 'Kategori', 'required');
        $this->form_validation->set_rules('year', 'Tahun Terbit', 'required');
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
                    'quantity' => $this->input->post('quantity')
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
                'quantity' => $this->input->post('quantity')
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

    public function pinjamBuku()
    {
        if ($this->input->is_ajax_request()) {
            $book_id = $this->input->post('book_id');
            if (!$book_id) {
                $response = array('response' => 'error', 'message' => 'Buku belum dipilih...');
            } else {
                $data = array(
                    'user_id' => $this->session->userdata('user_id'),
                    'book_id' => $book_id,
                    'status' => 'pending',
                    'loan_date' => NULL,
                    'return_date' => NULL
                );
                if ($this->mLoan->addLoan($data)) {
                    $response = array('response' => 'success', 'message' => 'Buku berhasil dipinjam', 'misc' => 'Sedang menunggu konfirmasi...');
                } else {
                    $response = array('response' => 'error', 'message' => 'Buku gagal dipinjam...');
                }
            }
        } else {
            $response = array('response' => 'error', 'message' => 'Anda tidak memiliki akses...');
        }
        echo json_encode($response);
    }

    public function showTempBook() {
        $data = $this->session->userdata('books');
        echo json_encode($data);
    }

    public function addTempBook()
    {
        if ($this->input->is_ajax_request()) {
            $book_id = $this->input->post('book_id');
            $books = $this->session->userdata('books');
            $book_title = $this->mBook->getBookById($book_id)->title;
            if (!$book_id) {
                $response = array('response' => 'error', 'message' => 'Anda belum memilih buku...');
            } else {
                if (!in_array($book_id, $books)) {
                    array_push($books, array('id' => $book_id, 'title' => $book_title));
                    $this->session->set_userdata('books', $books);
                    $response = array('response' => 'success', 'message' => 'Buku berhasil dipilih...');
                } else {
                    $response = array('response' => 'error', 'message' => 'Buku sudah dipilih...');
                }
            }
        } else {
            $response = array('response' => 'error', 'message' => 'Anda tidak memiliki akses...');
        }
        echo json_encode($response);
    }

}
?>