<?php

class User_model extends CI_Model {


       public function checkEmailAndPassword($email = '',$password = '')
        {
                        $this->db->select('*');
                        $this->db->from('users');
                        $this->db->where('email',$email);
                        $this->db->where('password',md5($password));
                $query = $this->db->get()->row_array();
                        return ($query);
                        // return (true);
        }



}
?>