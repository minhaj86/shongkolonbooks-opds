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
class Catalogs extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->methods['user_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['user_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['user_delete']['limit'] = 50; // 50 requests per hour per user/key
    }

    public function catagory_get($name)
    {
        $this->load->model('catalog_catagory_model');
            $news = $this->catalog_catagory_model->get_catalog_by_type($name);
            // print_r($news);
            $id = $news['id'];
        $this->load->model('catalog_subcatagory_model');
            $newsl = $this->catalog_subcatagory_model->get_catalog($id);
            // print_r($newsl);
        $xml = new DOMDocument( "1.0", "UTF-8" );
        $xml->formatOutput = true;
        $feed = $xml->createElementNS( "http://www.w3.org/2005/Atom", "atom:feed" );
        $linkself = $xml->createElementNS( "http://www.w3.org/2005/Atom",  "atom:link");
        $linkself->setAttribute("rel", "self");
        $linkself->setAttribute("type", "application/atom+xml;profile=opds-catalog;kind=navigation");
        $linkself->setAttribute("href", $this->input->server('REQUEST_URI'));
        $linkup = $xml->createElementNS( "http://www.w3.org/2005/Atom",  "atom:link");
        $linkup->setAttribute("rel", "up");
        $linkup->setAttribute("type", "application/atom+xml;profile=opds-catalog;kind=navigation");
        $linkup->setAttribute("href", $this->input->server('REQUEST_URI'));
        $linkstart = $xml->createElementNS( "http://www.w3.org/2005/Atom",  "atom:link");
        $linkstart->setAttribute("rel", "start");
        $linkstart->setAttribute("type", "application/atom+xml;profile=opds-catalog;kind=navigation");
        $linkstart->setAttribute("href", $this->input->server('REQUEST_URI'));
        $linkrelated = $xml->createElementNS( "http://www.w3.org/2005/Atom",  "atom:link");
        $linkrelated->setAttribute("rel", "related");
        $linkrelated->setAttribute("type", "application/atom+xml;profile=opds-catalog;kind=navigation");
        $linkrelated->setAttribute("href", $this->input->server('REQUEST_URI'));

        $feed->appendChild( $linkself );
        $feed->appendChild( $linkstart );
        $feed->appendChild( $linkup );
        $feed->appendChild( $linkrelated );

        $xml->appendChild($feed);
// print_r($newsl);
      foreach ( $newsl as $e ) {
        $entry = $xml->createElementNS("http://www.w3.org/2005/Atom", "atom:entry");
        $id = $xml->createElementNS("http://www.w3.org/2005/Atom", "atom:id", "urn:uuid:".$e['id']);
        $title = $xml->createElementNS("http://www.w3.org/2005/Atom", "atom:title", $e['name']);
        // $summary = $xml->createElementNS("http://www.w3.org/2005/Atom", "atom:summary", $e['description']);
        $link = $xml->createElementNS("http://www.w3.org/2005/Atom", "atom:link");
        $link->setAttribute("href", 'http://'.$this->input->server('SERVER_NAME').'/test/api'.$e['link']);
        $link->setAttribute("rel", "self");
        $link->setAttribute("type", "application/atom+xml;profile=opds-catalog;kind=acquisition");
        $category = $xml->createElementNS("http://www.w3.org/2005/Atom", "atom:category");
        $content = $xml->createElementNS("http://www.w3.org/2005/Atom", "atom:content", "All Books from " . $e['name']);
        $content->setAttribute("type", 'text');

        // $category->setAttribute("term", $e['type']);
        // $category->setAttribute("scheme", "http://shonkolon.com/category/catalogs/types");
        // $category->setAttribute("label", $e['name']);

        $entry->appendChild($id);
        $entry->appendChild($title);
        // $entry->appendChild($summary);
        $entry->appendChild($link);
        $entry->appendChild($content);

        $feed->appendChild($entry);

      }
        $output = $xml->saveXML();
        $this->response($output, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    public function catagories_get()
    {
        $type = $this->get('type');
        $this->load->model('catalog_catagory_model');
        if ( isset($type) ) {
            $news = $this->catalog_catagory_model->get_catalog_by_type($type);
        }
        else {
            $news = $this->catalog_catagory_model->get_catalogs();
        }

        $xml = new DOMDocument( "1.0", "UTF-8" );
        $xml->formatOutput = true;

        $feed = $xml->createElementNS( "http://www.w3.org/2005/Atom", "atom:feed" );
        $linkself = $xml->createElementNS( "http://www.w3.org/2005/Atom",  "atom:link");
        $linkself->setAttribute("rel", "self");
        $linkself->setAttribute("type", "application/atom+xml;profile=opds-catalog;kind=navigation");
        $linkself->setAttribute("href", $this->input->server('REQUEST_URI'));
        $linkup = $xml->createElementNS( "http://www.w3.org/2005/Atom",  "atom:link");
        $linkup->setAttribute("rel", "up");
        $linkup->setAttribute("type", "application/atom+xml;profile=opds-catalog;kind=navigation");
        $linkup->setAttribute("href", $this->input->server('REQUEST_URI'));
        $linkstart = $xml->createElementNS( "http://www.w3.org/2005/Atom",  "atom:link");
        $linkstart->setAttribute("rel", "start");
        $linkstart->setAttribute("type", "application/atom+xml;profile=opds-catalog;kind=navigation");
        $linkstart->setAttribute("href", $this->input->server('REQUEST_URI'));
        $linkrelated = $xml->createElementNS( "http://www.w3.org/2005/Atom",  "atom:link");
        $linkrelated->setAttribute("rel", "related");
        $linkrelated->setAttribute("type", "application/atom+xml;profile=opds-catalog;kind=navigation");
        $linkrelated->setAttribute("href", $this->input->server('REQUEST_URI'));

        $feed->appendChild( $linkself );
        $feed->appendChild( $linkstart );
        $feed->appendChild( $linkup );
        $feed->appendChild( $linkrelated );

        $xml->appendChild($feed);

      foreach ( $news as $e ) {
        // $entry = $xml->createElementNS("http://www.w3.org/2005/Atom", "atom:entry");
        // $id = $xml->createElementNS("http://www.w3.org/2005/Atom", "atom:id", "urn:uuid:".$e['id']);
        // $title = $xml->createElementNS("http://www.w3.org/2005/Atom", "atom:title", $e['name']);
        // $summary = $xml->createElementNS("http://www.w3.org/2005/Atom", "atom:summary", $e['description']);
        // $link = $xml->createElementNS("http://www.w3.org/2005/Atom", "atom:link");
        // $link->setAttribute("href", "http://".$this->input->server('SERVER_NAME').$e['link']);
        // $link->setAttribute("rel", "self");
        // $link->setAttribute("type", "application/atom+xml;profile=opds-catalog;kind=navigation");
        // $category = $xml->createElementNS("http://www.w3.org/2005/Atom", "atom:category");
        // $category->setAttribute("term", $e['type']);
        // $category->setAttribute("scheme", "http://shonkolon.com/category/catalogs/types");
        // $category->setAttribute("label", $e['name']);

        // $entry->appendChild($id);
        // $entry->appendChild($title);
        // $entry->appendChild($summary);
        // $entry->appendChild($link);
        // $entry->appendChild($category);

        $entry = self::_create_entry($xml, $e);
        $feed->appendChild($entry);

      }
        $output = $xml->saveXML();
        // echo $output;
        $this->response($output, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
// // return ;
//     } 
// //             $this->set_response([
// //                 'status' => FALSE,
// //                 'message' => 'User could not be found'
// //             ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
    }

    private function _create_entry($xml,$e) {
        $entry = $xml->createElementNS("http://www.w3.org/2005/Atom", "atom:entry");
        $id = $xml->createElementNS("http://www.w3.org/2005/Atom", "atom:id", "urn:uuid:".$e['id']);
        $title = $xml->createElementNS("http://www.w3.org/2005/Atom", "atom:title", $e['name']);
        $summary = $xml->createElementNS("http://www.w3.org/2005/Atom", "atom:summary", $e['description']);
        $link = $xml->createElementNS("http://www.w3.org/2005/Atom", "atom:link");
        $link->setAttribute("href", "http://".$this->input->server('SERVER_NAME').$e['link']);
        $link->setAttribute("rel", "self");
        $link->setAttribute("type", "application/atom+xml;profile=opds-catalog;kind=navigation");
        $category = $xml->createElementNS("http://www.w3.org/2005/Atom", "atom:category");
        $category->setAttribute("term", $e['type']);
        $category->setAttribute("scheme", "http://shonkolon.com/category/catalogs/types");
        $category->setAttribute("label", $e['name']);

        $entry->appendChild($id);
        $entry->appendChild($title);
        $entry->appendChild($summary);
        $entry->appendChild($link);
        $entry->appendChild($category);
        return $entry;
    }
    
    public function _serialize_entry($entry) {
        $options = array(
        "indent"          => "    ",
        "linebreak"       => "\n",
        "typeHints"       => false,
        "encoding"        => "UTF-8",
        "rootName"        => 'entry',
//         "rootAttributes"  => array('xmlns'=>"http://www.w3.org/2005/Atom", 'xmlns:dc' => "http://purl.org/dc/terms/", 'xmlns:opds' => "http://opds-spec.org/2010/catalog"),
        "defaultTagName"  => 'link',
        "attributesArray" => "_attributes"
        );
        $serializer = new XML_Serializer($options);
        $result = $serializer->serialize($entry);
        if( $result === true ) {
            return $serializer->getSerializedData();
        }
        return null;
    }

    public function _serialize_link($link) {
        $options = array(
        "indent"          => "    ",
        "linebreak"       => "\n",
        "typeHints"       => false,
        "encoding"        => "UTF-8",
        "rootName"        => 'link',
        "rootAttributes"  => array( 'rel' => $link['rel'], 'href' => $link['href'], 'type' => $link['type']),
//         "defaultTagName"  => 'link',
//         "attributesArray" => "_attributes"
        );
        $serializer = new XML_Serializer($options);
        $result = $serializer->serialize($link);
        if( $result === true ) {
            return $serializer->getSerializedData();
        }
        return null;
    }

    public function users_post()
    {
        // $this->some_model->update_user( ... );
        $message = [
            'id' => 100, // Automatically generated by the model
            'name' => $this->post('name'),
            'email' => $this->post('email'),
            'message' => 'Added a resource'
        ];

        $this->set_response($message, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
    }

    public function users_delete()
    {
        $id = (int) $this->get('id');

        // Validate the id.
        if ($id <= 0)
        {
            // Set the response and exit
            $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }

        // $this->some_model->delete_something($id);
        $message = [
            'id' => $id,
            'message' => 'Deleted the resource'
        ];

        $this->set_response($message, REST_Controller::HTTP_NO_CONTENT); // NO_CONTENT (204) being the HTTP response code
    }

}
