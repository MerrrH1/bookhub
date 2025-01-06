<?php
defined('BASEPATH') or exit('No direct script access allowed');

function is_logged_in()
{
    $CI =& get_instance();
    return $CI->session->userdata('user_id') ? true : false;

}
