<?php

class mFine extends CI_Model
{
    public function getFine()
    {
        $this->db->select('fine.*, user.first_name, user.last_name, loan.user_id');
        $this->db->from('fine');
        $this->db->join('loan', 'loan.loan_id = fine.loan_id');
        $this->db->join('user', 'user.user_id = loan.user_id');
        $query = $this->db->get();
        return $query->result();
    }

    public function addFine($data)
    {
        return $this->db->insert('fine', $data);
    }

    public function updateFine($fine_id, $data) {
        $this->db->where('fine_id', $fine_id);
        return $this->db->update('fine', $data);
    }
}

?>