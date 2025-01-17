<?php

class mLoan extends CI_Model
{
    public function getLoan()
    {
        $this->db->select('loan.*, book.title, user.username, user.first_name, user.last_name, fine.fine_amount, fine.fine_status');
        $this->db->from('loan');
        $this->db->join('book', 'book.book_id = loan.book_id', 'inner');
        $this->db->join('user', 'user.user_id = loan.user_id', 'inner');
        $this->db->join('fine', 'fine.loan_id = loan.loan_id', 'left outer');
        $this->db->order_by('loan_date', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }

    public function getLoanByUser($user_id)
    {
        $this->db->select('loan.*, book.title, user.username, fine.fine_amount, fine.fine_status');
        $this->db->from('loan');
        $this->db->join('book', 'book.book_id = loan.book_id', 'inner');
        $this->db->join('user', 'user.user_id = loan.user_id', 'inner');
        $this->db->join('fine', 'fine.loan_id = loan.loan_id', 'left outer');
        $this->db->where('user.user_id', $user_id);
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

    public function updateLoan($loan_id, $data)
    {
        $this->db->where('loan_id', $loan_id);
        return $this->db->update('loan', $data);
    }
}

?>