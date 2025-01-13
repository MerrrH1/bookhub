<?php 

class mUser extends CI_Model {
    public function getMember() {
        $this->db->select('*');
        $this->db->from('user');
        $this->db->where('role', 'member');
        return $this->db->get()->result();
    }

    public function get_user($username) {
        $this->db->where('username', $username);
        return $this->db->get('user')->row();
    }

    public function getUserById($id) {
        $this->db->where('user_id', $id);
        return $this->db->get('user')->row();
    }

    public function checkDuplicate($username) {
        $this->db->where('username', $username);
        $query = $this->db->get('user');
        return $query->num_rows() > 0;
    }

    public function register($data) {
        return $this->db->insert('user', $data);
    }
}

?>