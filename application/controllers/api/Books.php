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
    }

    public function all_get()
    {
        $this->load->model('book_model');
        $news = $this->book_model->get_book_all();

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
            self::_addChildElementDcterms($xml, 'extent', $e['no_of_pages'], $entry);
            self::_addChildElementDcterms($xml, 'extent', $e['size'], $entry);
            // self::_addChildElementAtom($xml, 'summary', $e['summary'], $entry);
            $linkalternate = $xml->createElementNS( "http://www.w3.org/2005/Atom",  "atom:link");
            $linkalternate->setAttribute("type", "text/html");
            $linkalternate->setAttribute("title", $e['title']);
            $linkalternate->setAttribute("href", $e['alternate_link']);
            $linkalternate->setAttribute("rel", "alternate");
            $entry->appendChild($linkalternate);

            $linkimage = $xml->createElementNS( "http://www.w3.org/2005/Atom",  "atom:link");
            $linkimage->setAttribute("type", "image/jpeg");
            $linkimage->setAttribute("href", $e['main_image']);
            $linkimage->setAttribute("rel", "http://opds-spec.org/image");
            $entry->appendChild($linkimage);

            $linkthumb = $xml->createElementNS( "http://www.w3.org/2005/Atom",  "atom:link");
            $linkthumb->setAttribute("type", "image/jpeg");
            $linkthumb->setAttribute("href", $e['thumb_image']);
            $linkthumb->setAttribute("rel", "http://opds-spec.org/image/thumbnail");
            $entry->appendChild($linkthumb);

            $linkbuy = $xml->createElementNS( "http://www.w3.org/2005/Atom",  "atom:link");
            $linkbuy->setAttribute("type", "text/html");
            $linkbuy->setAttribute("href", $e['buy_link']);
            $linkbuy->setAttribute("rel", "http://opds-spec.org/acquisition/buy");

            $price = self::_addChildElementOpds($xml, 'price', '0');
            $price->setAttribute('currencycode', 'USD');
            $indirectAcquisition  = self::_addChildElementOpds($xml, 'indirectAcquisition');
            $indirectAcquisition->setAttribute('type', 'application/vnd.adobe.adept+xml');
            $indirectAcquisitionepub  = self::_addChildElementOpds($xml, 'indirectAcquisition');
            $indirectAcquisitionepub->setAttribute('type', 'application/epub+zip');
            $indirectAcquisition->appendChild($indirectAcquisitionepub);

            $linkbuy->appendChild($price);
            $linkbuy->appendChild($indirectAcquisition);



            $entry->appendChild($linkbuy);

         // <opds:price currencycode="USD">69.49</opds:price>
         // <opds:indirectAcquisition type="application/vnd.adobe.adept+xml">
         //    <opds:indirectAcquisition type="application/epub+zip" />
         // </opds:indirectAcquisition>

        }
        $output = $xml->saveXML();
        $this->response($output, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    public function writer_get($id)
    {
        $this->load->model('book_model');
        $news = $this->book_model->get_book_by_writer($id);

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
            self::_addChildElementDcterms($xml, 'extent', $e['no_of_pages'], $entry);
            self::_addChildElementDcterms($xml, 'extent', $e['size'], $entry);
            // self::_addChildElementAtom($xml, 'summary', $e['summary'], $entry);
            $linkalternate = $xml->createElementNS( "http://www.w3.org/2005/Atom",  "atom:link");
            $linkalternate->setAttribute("type", "text/html");
            $linkalternate->setAttribute("title", $e['title']);
            $linkalternate->setAttribute("href", $e['alternate_link']);
            $linkalternate->setAttribute("rel", "alternate");
            $entry->appendChild($linkalternate);

            $linkimage = $xml->createElementNS( "http://www.w3.org/2005/Atom",  "atom:link");
            $linkimage->setAttribute("type", "image/jpeg");
            $linkimage->setAttribute("href", $e['main_image']);
            $linkimage->setAttribute("rel", "http://opds-spec.org/image");
            $entry->appendChild($linkimage);

            $linkthumb = $xml->createElementNS( "http://www.w3.org/2005/Atom",  "atom:link");
            $linkthumb->setAttribute("type", "image/jpeg");
            $linkthumb->setAttribute("href", $e['thumb_image']);
            $linkthumb->setAttribute("rel", "http://opds-spec.org/image/thumbnail");
            $entry->appendChild($linkthumb);

            $linkbuy = $xml->createElementNS( "http://www.w3.org/2005/Atom",  "atom:link");
            $linkbuy->setAttribute("type", "text/html");
            $linkbuy->setAttribute("href", $e['buy_link']);
            $linkbuy->setAttribute("rel", "http://opds-spec.org/acquisition/buy");

            $price = self::_addChildElementOpds($xml, 'price', '0');
            $price->setAttribute('currencycode', 'USD');
            $indirectAcquisition  = self::_addChildElementOpds($xml, 'indirectAcquisition');
            $indirectAcquisition->setAttribute('type', 'application/vnd.adobe.adept+xml');
            $indirectAcquisitionepub  = self::_addChildElementOpds($xml, 'indirectAcquisition');
            $indirectAcquisitionepub->setAttribute('type', 'application/epub+zip');
            $indirectAcquisition->appendChild($indirectAcquisitionepub);

            $linkbuy->appendChild($price);
            $linkbuy->appendChild($indirectAcquisition);



            $entry->appendChild($linkbuy);

         // <opds:price currencycode="USD">69.49</opds:price>
         // <opds:indirectAcquisition type="application/vnd.adobe.adept+xml">
         //    <opds:indirectAcquisition type="application/epub+zip" />
         // </opds:indirectAcquisition>

        }
        $output = $xml->saveXML();
        $this->response($output, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    private function _create_entry($xml,$e) {
        
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