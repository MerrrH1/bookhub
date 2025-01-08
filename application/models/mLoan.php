<?php

class mLoan extends CI_Model
{
    public function getData()
    {
        $this->db->get('l.*, u.username, b.title');
        $this->db->from('loan l');
        $this->db->join('book b', 'b.book_id = l.book_id', 'inner');
        $this->db->join('user u', 'u.user_id = l.user_id', 'inner');
        return $this->db->get()->result();
    }

    public function addLoan($data)
    {
        return $this->db->insert('loan', $data);
    }
    public function addLoanDetail($data)
    {
        return $this->db->insert('loan_detail', $data);
    }

    public function isBookAvailable($book_id)
    {
        $this->db->select('quantity');
        $this->db->where('book_id', $book_id);
        $query = $this->db->get('book');

        if ($query->num_rows() > 0) {
            $row = $query->row();
            return $row->quantity > 1;
        }
        return false;
    }

    public function saveTrans($data, $dataDetail) {
        $this->db->trans_strict(TRUE);
        $this->db->trans_start();
        $this->db->insert('loan', $data);
        $this->db->insert('loan_detail', $dataDetail);
        $this->db->trans_complete();
        if($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
            return TRUE;
        }
    }
}

?>