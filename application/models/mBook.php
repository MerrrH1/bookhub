<?php 

class mBook extends CI_Model {
    public function getData() {
        $this->db->select('b.*, c.category_name');
        $this->db->from('book b');
        $this->db->join('category c','c.category_id = b.category_id', 'inner');
        return $this->db->get()->result();
    }

    public function getBookById($id) {
        $this->db->where('book_id', $id);
        return $this->db->get('book')->row();
    }

    public function insertData($data) {
        return $this->db->insert('book', $data);
    }

    public function updateData($id, $data) {
        $this->db->where('book_id', $id);
        return $this->db->update('book', $data);
    }

    public function deleteData($id) {
        $this->db->where('book_id', $id);
        return $this->db->delete('book');
    }

    public function checkDuplicate($title) {
        $this->db->where('title', $title);
        $query = $this->db->get('book');
        return $query->num_rows();
    }
}

?>