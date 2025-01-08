<?php 

class Dashboard extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('mBook');
        $this->load->model('mUser');
    }

    public function index() {
        $data['title'] = "Dashboard";
        $data['book'] = $this->mBook->getData();
        $this->load->view('templates/header', $data);
        $this->load->view('dashboard', $data);
        $this->load->view('templates/footer');
    }
}

?>