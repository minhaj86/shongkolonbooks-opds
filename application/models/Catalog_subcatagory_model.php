<?php

class Catalog_subcatagory_model extends CI_Model {
    public function __construct()   {
    $this->load->database(); 
    }

    public function get_catalog($id) {
            $query = $this->db->query("select * from opds_catagory where catalog_id=$id");
            return $query->result_array();
    }
}
