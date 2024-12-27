<?php

class mCategory extends CI_Model {
    public function getData() {
        $this->db->select('*');
        $this->db->from('category');
        return $this->db->get()->result();
    }
}

?>