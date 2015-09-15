<?php

class Catalog_catagory_model extends CI_Model {
    public function __construct()   {
    $this->load->database(); 
    }

    public function get_catalog_by_type($type) {
            $query = $this->db->get_where('catalogs', array('type' => $type));
            return $query->row_array();
    }
    // public function get_catalog_by_type($name) {
    //         $query = $this->db->get_where('catalogs', array('type' => $type));
    //         return $query->row_array();
    // }
    public function get_catalogs() {
            $query = $this->db->query('select * from opds_catalogs');
            return $query->result_array();
    }
}
