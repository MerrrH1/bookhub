<?php 

class User extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('mUser');
    }

    public function index() {
        $data['title'] = "Halaman Member";
        $this->load->view('templates/header', $data);
        $this->load->view('user/index');
        $this->load->view('templates/footer');
    }

    public function showUser(){
        $data = $this->mUser->getAllUser();
        echo json_encode($data, JSON_PRETTY_PRINT);
    }
}

?>