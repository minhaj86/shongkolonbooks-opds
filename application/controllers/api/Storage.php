<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH . '/libraries/REST_Controller.php';

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */
class Storage extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->methods['file_get']['limit'] = 500; // 500 requests per hour per user/key
    }

    public function file_get($storage_id,$file_id)
    {
        // $this->load->helper('download');

        // force_download('storages/epub0/ffff.epub', NULL);
        $this->output->set_content_type('application/epub+zip')->set_output(file_get_contents('storages/epub0/ffff.epub'));
    }
}