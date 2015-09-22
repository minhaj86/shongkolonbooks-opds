<?php

class Catalog_publisher_model extends CI_Model {
    public function __construct()   {
    $this->load->database(); 
    }

    public function get_catalog() {
            $query = $this->db->query("select * from oc_manufacturer");
            return $query->result_array();
    }
}
