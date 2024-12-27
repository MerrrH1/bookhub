<?php

class Category extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('mCategory');
    }

    public function index()
    {
        $data['title'] = "Halaman Kategori";
        $this->load->view('templates/header', $data);
        $this->load->view('category/index', $data);
        $this->load->view('templates/footer');
    }
}

?>