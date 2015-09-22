<?php

class Catalog_subcatagory_model extends CI_Model {
    public function __construct()   {
    $this->load->database(); 
    }

    public function get_catalog() {
            $query = $this->db->query("select * from oc_category_description d,oc_category c where d.category_id=c.category_id and d.language_id=1");
            return $query->result_array();
    }
}
