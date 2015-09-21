<?php

class Book_model extends CI_Model {
    public function __construct()   {
        $this->load->database(); 
    }

    public function get_book_all() {
        $oc_get_all_sql = "SELECT p.product_id as id,d.name as title,p.isbn,p.date_published as publish_ts,p.date_modified as update_ts,p.book_language as language,p.manufacturer_id,m.name as publisher,p.date_added as issue_ts,d.description as summary,p.page,p.size,p.image from oc_product as p, oc_product_description as d, oc_manufacturer as m  where p.product_id = d.product_id and p.manufacturer_id=m.manufacturer_id and d.language_id=1";
        $query = $this->db->query($oc_get_all_sql);
        // $query = $this->db->query('select * from opds_books');
        return $query->result_array();
    }
    public function get_book_by_writer($id) {
            $query = $this->db->query("SELECT * FROM opds_books WHERE id IN (SELECT book_id FROM opencart.opds_book_to_author WHERE author_id=$id)");
            return $query->result_array();
    }
    public function get_writer_by_book($id) {
        $oc_get_author_by_book = "SELECT auth.author_id,auth.image,des.name as name  FROM oc_author as auth, oc_author_description as des, oc_author_attribute as attr where auth.author_id=des.author_id and des.language_id=1 and attr.language_id=1 and attr.name = 'Author' and auth.author_id in (SELECT author_id FROM oc_product_to_author WHERE product_id = $id ) ";
        $query = $this->db->query($oc_get_author_by_book);
        // $query = $this->db->query("SELECT * FROM opds_authors WHERE id IN (SELECT author_id FROM opds_book_to_author WHERE book_id=$id)");
        return $query->result_array();
    }
    public function get_book_by_id($id) {
            $oc_get_by_id_sql = "SELECT p.product_id as id,d.name as title,p.isbn,p.date_published as publish_ts,p.date_modified as update_ts,p.book_language as language,p.manufacturer_id,m.name as publisher,p.date_added as issue_ts,d.description as summary,p.page,p.size,p.image from oc_product as p, oc_product_description as d, oc_manufacturer as m  where p.product_id = d.product_id and p.manufacturer_id=m.manufacturer_id and d.language_id=1 and p.product_id=$id";
            $query = $this->db->query($oc_get_by_id_sql);
            // $query = $this->db->query("select * from opds_books where id=$id");
            return $query->result_array();
    }
    public function get_file_by_book_id($id) {
        $sql = "SELECT CONCAT(s.mountpoint,f.name) AS filepath  FROM opds_files AS f, opds_storages AS s WHERE f.storage_id = s.id AND f.id IN (SELECT file_id FROM opds_books WHERE id = $id )";
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    // public function get_catalogs() {
    //         $query = $this->db->query('select * from opds_catalogs');
    //         return $query->result_array();
    // }
}
