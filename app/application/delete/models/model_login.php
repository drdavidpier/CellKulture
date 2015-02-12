<?php

class Model_login extends CI_Model {
    
    private $salt = 'r4nd0m';
    
    public function email_exists($email)
    {
        $sql = "SELECT name, email FROM user WHERE email = '{$email}' LIMIT 1";
        $result = $this->db->query($sql);
        $row = $result->row();
        
        return ($result->num_rows() === 1 && $row->email) ? $row->name : false;
    }
}