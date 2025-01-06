<?php // application/controllers/Auth.php
class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('auth_helper');
        $this->load->model('mUser');
    }

    public function index()
    {
        if (is_logged_in()) {
            redirect('dashboard');
        }
        $this->load->view('auth/login');
    }

    public function login()
    {
        $this->form_validation->set_rules('username', 'Username', 'required|trim');
        $this->form_validation->set_rules('password', 'Password', 'required|trim');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('auth/login');
        } else {
            $username = $this->input->post('username');
            $password = $this->input->post('password');

            $user = $this->mUser->get_user($username);

            if ($user) {
                $this->session->set_userdata('user_id', $user->user_id);
                $user_data = $this->mUser->getUserById($user->user_id);
                $this->session->set_userdata('username', $user_data->username);
                $this->session->set_userdata('user_id', $user_data->user_id);
                $this->session->set_userdata('role', $user_data->role);
                header('Location: ' . base_url('dashboard'));
            } else {
                $this->session->set_flashdata('error', 'Username atau password salah');
                redirect('auth/login');
            }
        }

    }

    public function logout()
    {
        $this->session->unset_userdata('user_id');
        $this->session->sess_destroy();
        redirect('auth/login');
    }
} ?>