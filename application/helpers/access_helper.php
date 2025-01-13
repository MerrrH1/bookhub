<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('check_admin')) {
    function check_admin()
    {
        $CI =& get_instance();
        if (!$CI->session->userdata('user_id')) {
            redirect('login');
        }

        if ($CI->session->userdata('role') !== 'admin') {
            show_404();
            exit;
        }
    }
}
