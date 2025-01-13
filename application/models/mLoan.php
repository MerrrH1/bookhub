<?php

class mLoan extends CI_Model
{
    public function getLoan()
    {
        $this->db->select('*');
        $this->db->from('loan');
        $this->db->join('book', 'book.book_id = loan.book_id', 'inner');
        $this->db->join('user', 'user.user_id = loan.user_id', 'inner');
        $this->db->join('fine', 'fine.loan_id = loan.loan_id', 'left');
        $query = $this->db->get();
        return $query->result();
    }

    public function getLoanByUser($user_id) {
        $this->db->select('*');
        $this->db->from('loan');
        $this->db->join('book', 'book.book_id = loan.book_id', 'inner');
        $this->db->join('user', 'user.user_id = loan.user_id', 'inner');
        $this->db->join('fine', 'fine.loan_id = loan.loan_id', 'left');
        $this->db->where('loan.user_id', $user_id);
        $query = $this->db->get();
        return $query->result();
    }

    public function addLoan($data)
    {
        return $this->db->insert('loan', $data);
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

    public function updateLoan($loan_id, $data) {
        $this->db->where('loan_id', $loan_id);
        return $this->db->update('loan', $data);
    }
}

?>