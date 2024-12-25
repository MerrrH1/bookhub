<?php 

class mBook extends CI_Model {
    public function fetchBook() {
        $this->db->select('b.*, c.category_name');
        $this->db->from('books b');
        $this->db->join('category c','b.category_id = c.category_id');
        return $this->db->get()->result();
    }
}

?>