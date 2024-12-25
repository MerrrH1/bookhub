<?php 

class Book extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('mBook');
    }

    public function index() {
        $data['title'] = "Halaman Buku";
        $data['book'] = $this->mBook->fetchBook();
        $this->load->view('templates/header', $data);
        $this->load->view("book/index");
        $this->load->view('templates/footer');
    }
}

?>