<?php

class User_model extends CI_Model {
    public function __construct()   {
    $this->load->database(); 
    }

    public function get_user($username) {
            $query = $this->db->query("select * from opds_users where username='$username'");
            return $query->result_array();
    }
    public function get_customer_book_subscription_by_email($email) {
        $query = $this->db->query("SELECT cbs.product_id AS product_id, pbf.file_id AS file_id FROM opds_customer_book_subscription AS cbs, oc_customer AS c, opds_product_to_book_files AS pbf WHERE cbs.customer_id=c.customer_id AND cbs.product_id = pbf.product_id AND c.email = '$email'");
        return $query->result_array();
    }
}
