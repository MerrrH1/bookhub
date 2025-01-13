<?php

class Category extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('mCategory');
        is_logged_in();
        check_admin();
    }

    public function index()
    {
        $data['title'] = "Halaman Kategori";
        $this->load->view('templates/header', $data);
        $this->load->view('category/index');
        $this->load->view('templates/footer');
    }

    public function showData()
    {
        $data = $this->mCategory->getData();
        echo json_encode($data);
    }

    public function showDataById()
    {
        $category_id = $this->input->post('category_id');
        $data = $this->mCategory->getDataById($category_id);
        echo json_encode($data);
    }

    public function addData()
    {
        $this->form_validation->set_rules('category_name', 'Kategori', 'required');
        $this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');

        if ($this->form_validation->run() == FALSE) {
            $response = array('responce' => 'error', 'message' => validation_errors());
        } else {
            $title = $this->input->post('title');
            $validData = $this->mCategory->checkDuplicate($title);
            if ($validData >= 1) {
                $response = array('responce' => 'error', 'message' => 'Kategori Sudah Terdaftar..');
            } else {
                $data = array(
                    'category_name' => $this->input->post('category_name')
                );
                $data = $this->security->xss_clean($data);
                $response = $this->mCategory->insertData($data) ?
                    array('responce' => 'success', 'message' => 'Data berhasil ditambah...') :
                    array('responce' => 'error', 'message' => 'Terjadi kesalahan, data gagal disimpan...');
            }
        }
        echo json_encode($response);
    }

    public function modifyData()
    {
        $this->form_validation->set_rules('category_name', 'Kategori', 'required');
        $this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');

        if ($this->form_validation->run() == FALSE) {
            $response = array('responce' => 'error', 'message' => validation_errors());
        } else {
            $category_id = $this->input->post('category_id');
            $data = array(
                'category_name' => $this->input->post('category_name')
            );
            $data = $this->security->xss_clean($data);
            $response = $this->mCategory->updateData($category_id, $data) ?
                array('responce' => 'success', 'message' => 'Data berhasil diubah...') :
                array('responce' => 'error', 'message' => 'Terjadi kesalahan, data gagal disimpan...');
        }

        echo json_encode($response);
    }

    public function removeData()
    {
        if ($this->input->is_ajax_request()) {
            $category_id = $this->input->post('category_id');
            $response = $this->mCategory->deleteData($category_id) ?
                array('responce' => 'success') : array('responce' => 'error');
            echo json_encode($response);
        } else {
            echo "No direct script access allowed";
        }
    }
}

?>