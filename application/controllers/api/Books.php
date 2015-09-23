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
class Books extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->methods['books_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->config->load('opds', FALSE, TRUE);
    }

    public function file_get($id) {
        // print_r($this->input->request_headers());
        $this->load->model('book_model');
        $news = $this->book_model->get_file_by_book_id($id);
        $filepath = $news[0]['filepath'];
        $this->output->set_content_type('application/epub+zip')->set_output(file_get_contents($filepath));
    }

    public function all_get()
    {
        $this->load->model('book_model');
        $news = $this->book_model->get_book_all();
        $xml = new DOMDocument( "1.0", "UTF-8" );
        $xml->formatOutput = true;
        $feed = $xml->createElementNS( "http://www.w3.org/2005/Atom", "atom:feed" );
        self::_addAcquisitionLink($xml,$feed,"self",$this->input->server('REQUEST_URI'));
        self::_addAcquisitionLink($xml,$feed,"up",$this->input->server('REQUEST_URI'));
        self::_addAcquisitionLink($xml,$feed,"start",$this->input->server('REQUEST_URI'));
        self::_addAcquisitionLink($xml,$feed,"related",$this->input->server('REQUEST_URI'));
        $xml->appendChild($feed);
        foreach ( $news as $e ) {
            self::_add_book_entry($xml,$feed,$e);
        }
        $output = $xml->saveXML();
        $this->response($output, REST_Controller::HTTP_OK);
    }

    public function writer_get($id)
    {
        $this->load->model('book_model');
        $news = $this->book_model->get_book_by_writer($id);
        $xml = new DOMDocument( "1.0", "UTF-8" );
        $xml->formatOutput = true;
        $feed = $xml->createElementNS( "http://www.w3.org/2005/Atom", "atom:feed" );
        self::_addAcquisitionLink($xml,$feed,"self",$this->input->server('REQUEST_URI'));
        self::_addAcquisitionLink($xml,$feed,"up",$this->input->server('REQUEST_URI'));
        self::_addAcquisitionLink($xml,$feed,"start",$this->input->server('REQUEST_URI'));
        self::_addAcquisitionLink($xml,$feed,"related",$this->input->server('REQUEST_URI'));
        $xml->appendChild($feed);
        foreach ( $news as $e ) {
            self::_add_book_entry($xml,$feed,$e);
        }
        $output = $xml->saveXML();
        $this->response($output, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    public function category_get($id)
    {
        $this->load->model('book_model');
        $news = $this->book_model->get_book_by_category($id);
        $xml = new DOMDocument( "1.0", "UTF-8" );
        $xml->formatOutput = true;
        $feed = $xml->createElementNS( "http://www.w3.org/2005/Atom", "atom:feed" );
        self::_addAcquisitionLink($xml,$feed,"self",$this->input->server('REQUEST_URI'));
        self::_addAcquisitionLink($xml,$feed,"up",$this->input->server('REQUEST_URI'));
        self::_addAcquisitionLink($xml,$feed,"start",$this->input->server('REQUEST_URI'));
        self::_addAcquisitionLink($xml,$feed,"related",$this->input->server('REQUEST_URI'));
        $xml->appendChild($feed);
        foreach ( $news as $e ) {
            self::_add_book_entry($xml,$feed,$e);
        }
        $output = $xml->saveXML();
        $this->response($output, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    public function publisher_get($id)
    {
        $this->load->model('book_model');
        $news = $this->book_model->get_book_by_publisher($id);
        $xml = new DOMDocument( "1.0", "UTF-8" );
        $xml->formatOutput = true;
        $feed = $xml->createElementNS( "http://www.w3.org/2005/Atom", "atom:feed" );
        self::_addAcquisitionLink($xml,$feed,"self",$this->input->server('REQUEST_URI'));
        self::_addAcquisitionLink($xml,$feed,"up",$this->input->server('REQUEST_URI'));
        self::_addAcquisitionLink($xml,$feed,"start",$this->input->server('REQUEST_URI'));
        self::_addAcquisitionLink($xml,$feed,"related",$this->input->server('REQUEST_URI'));
        $xml->appendChild($feed);
        foreach ( $news as $e ) {
            self::_add_book_entry($xml,$feed,$e);
        }
        $output = $xml->saveXML();
        $this->response($output, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    public function item_get($id)
    {
        $this->load->model('book_model');
        $news = $this->book_model->get_book_by_id($id);
        $xml = new DOMDocument( "1.0", "UTF-8" );
        $xml->formatOutput = true;
        $e = $news[0];
        self::_add_book_entry($xml,$xml,$e,1);
        $output = $xml->saveXML();
        $this->response($output, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    private function _add_book_entry($xml,$feed,$e,$is_alternate=null) {
            $entry = self::_addChildElementAtom($xml, 'entry', null, $feed);
            self::_addChildElementAtom($xml, 'id', "urn:uuid:".$e['id'], $entry);
            self::_addChildElementAtom($xml, 'title', $e['title'], $entry);
            self::_addChildElementAtom($xml, 'summary', $e['summary'], $entry);
            $dctermId = self::_addChildElementDcterms($xml, 'identifier', "urn:uuid:".$e['isbn'], $entry);
            $dctermId->setAttributeNS( "http://www.w3.org/2001/XMLSchema-instance", "xsi:type", "dcterms:URI");
            $dctermSrc = self::_addChildElementDcterms($xml, 'source', "urn:uuid:".$e['isbn'], $entry);
            $dctermSrc->setAttributeNS( "http://www.w3.org/2001/XMLSchema-instance", "xsi:type", "dcterms:URI");
            self::_addChildElementAtom($xml, 'published', $e['publish_ts'], $entry);
            self::_addChildElementAtom($xml, 'updated', $e['update_ts'], $entry);
            self::_addChildElementDcterms($xml, 'language', $e['language'], $entry);
            self::_addChildElementDcterms($xml, 'publisher', $e['publisher'], $entry);
            self::_addChildElementDcterms($xml, 'issued', $e['issue_ts'], $entry);
            self::_addChildElementDcterms($xml, 'extent', $e['page']." Pages", $entry);
            self::_addChildElementDcterms($xml, 'extent', $e['size'] . " Bytes", $entry);
            if (!$is_alternate) {
                self::_addLink($xml,$entry,"alternate",self::_get_base_uri().$this->config->item('book_item_relative_path').$e['id'],"application/xml");
            }
            // self::_addLink($xml,$entry,"alternate",$e['alternate_link'],"text/html");
            self::_addLink($xml,$entry,"http://opds-spec.org/image",self::_get_base_uri().$e['image'],"image/jpeg");
            self::_addLink($xml,$entry,"http://opds-spec.org/image/thumbnail",self::_get_base_uri().$e['image'],"image/jpeg");
            $linkbuy = self::_addLink($xml,$entry,"http://opds-spec.org/acquisition/buy",self::_get_base_uri().$e['buy_link'],"text/html");
            $price = self::_addChildElementOpds($xml, 'price', '0');
            $price->setAttribute('currencycode', 'USD');
            $linkbuy->appendChild($price);
            $indirectAcquisition  = self::_addChildElementOpds($xml, 'indirectAcquisition');
            $indirectAcquisition->setAttribute('type', 'application/vnd.adobe.adept+xml');
            $indirectAcquisitionepub  = self::_addChildElementOpds($xml, 'indirectAcquisition');
            $indirectAcquisitionepub->setAttribute('type', 'application/epub+zip');
            $indirectAcquisition->appendChild($indirectAcquisitionepub);
            $linkbuy->appendChild($indirectAcquisition);
            $auths = $this->book_model->get_writer_by_book($e['id']);
            foreach ( $auths as $a ) {
                self::_add_author($xml,$entry,$a);
            }
            return $entry;
    }

    private function _get_base_uri() {
        return "http://".$this->input->server('SERVER_NAME')."";
    }

    private function _add_author($xml,$entry,$a) {
            $author = $xml->createElementNS( "http://www.w3.org/2005/Atom",  "atom:author");
            $name = $xml->createElementNS( "http://www.w3.org/2005/Atom",  "atom:name", $a['name']);
            $uri = $xml->createElementNS( "http://www.w3.org/2005/Atom",  "atom:uri" , 'http://dummy/author');
            $author->appendChild($name);
            $author->appendChild($uri);
            $entry->appendChild($author);
    }

    private function _addAcquisitionLink($xml,$feed,$rel,$href) {
        return self::_addLink($xml,$feed,$rel,$href,"application/atom+xml;profile=opds-catalog;kind=acquisition");
    }
    private function _addNavigationLink($xml,$feed,$rel,$href) {
        return self::_addLink($xml,$feed,$rel,$href,"application/atom+xml;profile=opds-catalog;kind=navigation");
    }
    private function _addLink($xml,$feed,$rel,$href,$type) {
        $link = $xml->createElementNS( "http://www.w3.org/2005/Atom",  "atom:link");
        $link->setAttribute("rel", $rel);
        $link->setAttribute("type", $type);
        $link->setAttribute("href", $href);
        $feed->appendChild( $link );
        return $link;
    }

    public function _addChildElementAtom($doc, $name, $value=null, $parent=null) {
        $child = $doc->createElementNS( "http://www.w3.org/2005/Atom",  "atom:$name", $value);
        $parent->appendChild($child);
        return $child;
    }
    public function _addChildElementDcterms($doc, $name, $value=null, $parent=null) {
        $child = $doc->createElementNS( "http://purl.org/dc/terms/",  "dcterms:$name", $value);
        $parent->appendChild($child);
        return $child;
    }
    public function _addChildElementOpds($doc, $name, $value=null, $parent=null) {
        $child = $doc->createElementNS( "http://opds-spec.org/2010/catalog",  "opds:$name", $value);
        if ($parent !== null) { 
            $parent->appendChild($child);
        }
        return $child;
    }
}
