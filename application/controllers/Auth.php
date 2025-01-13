<?php // application/controllers/Auth.php
class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('auth_helper');
        $this->load->model('mUser');
        $this->load->library('form_validation');
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

            if ($user && password_verify($password, $user->password)) {
                $this->session->set_userdata('user_id', $user->user_id);
                $user_data = $this->mUser->getUserById($user->user_id);
                $this->session->set_userdata('username', $user_data->username);
                $this->session->set_userdata('user_id', $user_data->user_id);
                $this->session->set_userdata('role', $user_data->role);
                $this->session->set_userdata('books', []);
                header('Location: ' . base_url('dashboard'));
            } else {
                $this->session->set_flashdata('error', 'Username atau password salah');
                redirect('auth/login');
            }
        }

    }

    public function register()
    {
        $this->load->view('auth/register');
    }

    public function processRegister()
    {
        $this->form_validation->set_rules('first_name', 'First Name', 'required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required');
        $this->form_validation->set_rules('username', 'Username', 'required|is_unique[user.username]'); 
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[user.email]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[8]');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[password]');

        if ($this->form_validation->run() == FALSE) {
            $response = array('response' => 'error', 'message' => validation_errors());
            log_message('error', validation_errors());
        } else {
            $username = $this->input->post('username');
            $validData = $this->mUser->checkDuplicate($username);
            if ($validData) {
                $response = array('response' => 'error', 'message' => 'Username sudah terdaftar...');
            } else {
                if ($this->input->post('password') != $this->input->post('confirm_password')) {
                    $response = array('response' => 'error', 'message' => 'Password dan Password Ulang tidak sama...');
                } else {
                    $data = array(
                        'first_name' => $this->input->post('first_name'),
                        'last_name' => $this->input->post('last_name'),
                        'username' => $username,
                        'email' => $this->input->post('email'),
                        'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT)
                    );
                    if ($this->mUser->register($data)) {
                        $response = array('response' => 'success', 'message' => 'Pendaftaran Berhasil...');
                    } else {
                        $response = array('response' => 'error', 'message' => 'Pendaftaram gagal, silahkan coba lagi...');
                    }
                }
            }
        }
        echo json_encode($response, JSON_PRETTY_PRINT);
    }

    public function logout()
    {
        $this->session->unset_userdata('user_id');
        $this->session->sess_destroy();
        redirect('auth/login');
    }
} ?>