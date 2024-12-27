<?php

class mCategory extends CI_Model {
    public function getData() {
        $this->db->select('*');
        $this->db->from('category');
        return $this->db->get()->result();
    }

    public function getDataById($id) {
        $this->db->where('category_id', $id);
        return $this->db->get('category')->row();
    }

    public function insertData($data) {
        return $this->db->insert('category', $data);
    }

    public function updateData($id, $data) {
        $this->db->where('category_id', $id);
        return $this->db->update('category', $data);
    }

    public function deleteData($id) {
        $this->db->where('category_id', $id);
        return $this->db->delete('category');
    }

    public function checkDuplicate($category_name) {
        $this->db->where('category_name', $category_name);
        $query = $this->db->get('category');
        return $query->num_rows();
    }
}

?>