<?php 

class mUser extends CI_Model {
    public function get_user($username) {
        $this->db->where('username', $username);
        return $this->db->get('user')->row();
    }

    public function getUserById($id) {
        $this->db->where('user_id', $id);
        return $this->db->get('user')->row();
    }
}

?>