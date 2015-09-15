<?php

class User_model extends CI_Model {
    public function __construct()   {
    $this->load->database(); 
    }

    public function get_user($username) {
            $query = $this->db->query("select * from opds_users where username='$username'");
            return $query->result_array();
    }
    // public function get_catalogs() {
    //         $query = $this->db->query('select * from opds_catalogs');
    //         return $query->result_array();
    // }
}
