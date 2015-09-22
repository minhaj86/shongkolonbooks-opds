<?php

class Catalog_writer_model extends CI_Model {
    public function __construct()   {
    $this->load->database(); 
    }

    public function get_catalog() {
            $query = $this->db->query("select * from oc_author a,oc_author_description d where a.author_id=d.author_id and d.language_id=1");
            return $query->result_array();
    }
}
