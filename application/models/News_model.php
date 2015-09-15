<?php

class News_model extends CI_Model {
public function __construct()	{
  $this->load->database(); 
}

public function get_news($id) {
  if($id != FALSE) {
    $query = $this->db->get_where('news', array('id' => $id));
    return $query->row_array();
  }
  else {
    return FALSE;
  }
}
}
