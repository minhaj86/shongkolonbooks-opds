<?php

class Book_model extends CI_Model {
    public function __construct()   {
    $this->load->database(); 
    }

    public function get_book_all() {
            $query = $this->db->query('select * from opds_books');
            return $query->result_array();
    }
    public function get_book_by_writer($id) {
            $query = $this->db->query("SELECT * FROM opds_books WHERE id IN (SELECT book_id FROM opencart.opds_book_to_author WHERE author_id=$id)");
            return $query->result_array();
    }
    public function get_writer_by_book($id) {
            $query = $this->db->query("SELECT * FROM opds_authors WHERE id IN (SELECT author_id FROM opds_book_to_author WHERE book_id=$id)");
            return $query->result_array();
    }
    // public function get_catalogs() {
    //         $query = $this->db->query('select * from opds_catalogs');
    //         return $query->result_array();
    // }
}
