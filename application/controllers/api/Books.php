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
class Encryption
{
    const CIPHER = MCRYPT_RIJNDAEL_128; // Rijndael-128 is AES
    const MODE   = MCRYPT_MODE_CBC;

    /* Cryptographic key of length 16, 24 or 32. NOT a password! */
    private $key;
    public function __construct($key) {
        $this->key = $key;
    }

    public function encrypt($plaintext) {
        $ivSize = mcrypt_get_iv_size(self::CIPHER, self::MODE);
        $iv = mcrypt_create_iv($ivSize, MCRYPT_DEV_RANDOM);
        $ciphertext = mcrypt_encrypt(self::CIPHER, $this->key, $plaintext, self::MODE, $iv);
        return base64_encode($iv.$ciphertext);
    }

    public function decrypt($ciphertext) {
        $ciphertext = base64_decode($ciphertext);
        $ivSize = mcrypt_get_iv_size(self::CIPHER, self::MODE);
        if (strlen($ciphertext) < $ivSize) {
            throw new Exception('Missing initialization vector');
        }

        $iv = substr($ciphertext, 0, $ivSize);
        $ciphertext = substr($ciphertext, $ivSize);
        $plaintext = mcrypt_decrypt(self::CIPHER, $this->key, $ciphertext, self::MODE, $iv);
        return rtrim($plaintext, "\0");
    }
}

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

    public function passphrase_get($id) {
        // print_r($this->input->request_headers());
        // log_message('info', 'Request headers: '.$this->input->request_headers());

        foreach ($this->input->request_headers() as $key => $value) {
            log_message('info', "Request header: $key => $value");
        }
        $this->load->model('book_model');
        $news = $this->book_model->get_file_by_book_id($id);
        // print_r($news);
        $this->load->helper('array');
        $file_entry = element(0, $news, null);
        if (!$file_entry) {
            $this->output->set_status_header(500);
            return;
        }
        $password = element('password', $file_entry, null);
        $this->output->set_content_type('application/epub+zip');
        $this->output->set_header('X-Passphrase: '.$password.'');
    }

    public function file_get($id) {
        // print_r($this->input->request_headers());
        // log_message('info', 'Request headers: '.$this->input->request_headers());
        $this->load->helper('array');
        $email = self::_get_user_email();
        $this->load->model('user_model');
        $subscribed_books = $this->user_model->get_customer_book_subscription_by_email_and_id($email,$id);
        $bookfile = element(0, $subscribed_books, null);
        if ($bookfile) {

        } else {
            $this->output->set_status_header(500);
            return;
        }

        $device_id = null;
        foreach ($this->input->request_headers() as $key => $value) {
            log_message('info', "Request header: $key => $value");
            if (strcmp($key,'X-Device-Id') == 0) {
                $device_id = $value;
            }
        }
        if ($device_id == null) {
            $this->output->set_status_header(500);
            return;
        }
        $this->load->model('book_model');
        $news = $this->book_model->get_file_by_book_id($id);
        // print_r($news);
        $this->load->helper('array');
        $file_entry = element(0, $news, null);
        if (!$file_entry) {
            $this->output->set_status_header(500);
            return;
        }
        $filepath = element('filepath', $file_entry, null);
        $password = element('password', $file_entry, null);
        $password = $device_id.$password;
        if (!$filepath) {
            $this->output->set_status_header(500);
            return;
        }
        $this->output->set_content_type('application/epub+zip');
        $this->output->set_header('Content-Disposition: attachment; filename="'.$id.'.epub"');
        $encrytedContent = file_get_contents($filepath);
        // require_once 'AESCryptFileLib.php';
        // $this->load->library('AESCryptFileLib');

        //Include an AES256 Implementation
        // require_once 'aes256/MCryptAES256Implementation.php';
        // $this->load->library('aes256/MCryptAES256Implementation');

        //Construct the implementation
        $mcrypt = new MCryptAES256Implementation();

        //Use this to instantiate the encryption library class
        $lib = new AESCryptFileLib($mcrypt);
        $encrytedContent = $lib->doEncryptContent($filepath, $password);


        $this->output->set_output($encrytedContent);
    }

    public function encryptFile($filepath,$key) {
        // global $cryptastic;

        $msg = file_get_contents($filepath);
        // $key = /* CRYPTOGRAPHIC!!! key */;
        $crypt = new Encryption($key);
        $encrypted = $crypt->encrypt($msg);
        // $encrypted = $cryptastic->encrypt($msg, $key) or die("Failed to complete encryption.");
        return $encrypted;
    }

    public function decryptFile($content,$key) {
        // $key = /* CRYPTOGRAPHIC!!! key */;
        $crypt = new Encryption($key);
        // $encrypted_string = $crypt->encrypt('this is a test');
        $decrypted_string = $crypt->decrypt($content); // this is a test        
    }

    public function fileold_get($id) {
        $this->load->helper('download');
        $this->load->model('book_model');
        $news = $this->book_model->get_file_by_book_id($id);
        $this->load->helper('array');
        $file_entry = element(0, $news, null);
        if (!$file_entry) {
            $this->output->set_status_header(500);
            return;
        }
        $filepath = element('filepath', $file_entry, null);
        if (!$filepath) {
            $this->output->set_status_header(500);
            return;
        }
        force_download($filepath, NULL);
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
        $email = self::_get_user_email();
        $this->load->model('user_model');
        $subscribed_books = $this->user_model->get_customer_book_subscription_by_email($email);
        // print_r($subscribed_books);
        self::_add_book_entry($xml,$xml,$e,1,$subscribed_books);
        $output = $xml->saveXML();
        $this->response($output, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    private function _add_book_entry($xml,$feed,$e,$is_alternate=null,$subscribed_books=null) {
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
                self::_addLink($xml,$entry,"alternate",self::_get_base_uri().$this->config->item('book_item_relative_path').$e['id'],"application/atom+xml;type=entry;profile=opds-catalog");
            } else {
                self::_addChildElementAtom($xml, 'content', $e['summary'], $entry);
                self::_addLink($xml,$entry,"self",self::_get_base_uri().$this->config->item('book_item_relative_path').$e['id'],"application/atom+xml;type=entry;profile=opds-catalog");
            }
            // self::_addLink($xml,$entry,"alternate",$e['alternate_link'],"text/html");opencart_image_relative_path
            self::_addLink($xml,$entry,"http://opds-spec.org/image",self::_get_base_uri().$this->config->item('opencart_image_relative_path').$e['image'],"image/jpeg");
            self::_addLink($xml,$entry,"http://opds-spec.org/image/thumbnail",self::_get_base_uri().$this->config->item('opencart_image_relative_path').$e['image'],"image/jpeg");
            $is_purchased = 0;
            if (isset($subscribed_books)) {
                foreach ($subscribed_books as $key => $value) {
                    if($value['product_id'] == $e['id']) {
                        $is_purchased = 1;
                        break;
                    }
                }
            }
            if($is_purchased) {
                self::_addLink($xml,$entry,"http://opds-spec.org/acquisition",self::_get_base_uri().$this->config->item('book_file_relative_path').$e['id'],"application/epub+zip");
            } else {
                // self::_addLink($xml,$entry,"http://opds-spec.org/acquisition",self::_get_base_uri().$this->config->item('book_file_relative_path').$e['id'],"application/epub+zip");
                $linksample = self::_addLink($xml,$entry,"http://opds-spec.org/acquisition/sample",self::_get_base_uri().$e['buy_link'],"application/epub+zip");
                // self::_addLink($xml,$entry,"alternate",self::_get_base_uri().$e['buy_link'],"text/html");
                $linkbuy = self::_addLink($xml,$entry,"http://opds-spec.org/acquisition/buy",self::_get_base_uri().$e['buy_link'],"text/html");
                $price = self::_addChildElementOpds($xml, 'price', $e['price']);
                $price->setAttribute('currencycode', 'USD');
                $linkbuy->appendChild($price);
                // $indirectAcquisition  = self::_addChildElementOpds($xml, 'indirectAcquisition');
                // $indirectAcquisition->setAttribute('type', 'application/vnd.adobe.adept+xml');
                $indirectAcquisitionepub  = self::_addChildElementOpds($xml, 'indirectAcquisition');
                $indirectAcquisitionepub->setAttribute('type', 'application/epub+zip');
                $linkbuy->appendChild($indirectAcquisitionepub);
            }
            // $linkbuy->appendChild($indirectAcquisition);
            $auths = $this->book_model->get_writer_by_book($e['id']);
            foreach ( $auths as $a ) {
                self::_add_author($xml,$entry,$a);
            }
            return $entry;
    }

    public function playground_get() {
        // $subject = "abcdef";
        // $pattern = '/def/';
        // preg_match($pattern, $subject, $matches);
        // print_r($matches);        
        self::_get_userid();
        // echo "asdfasdfasdfa\n\n";
    }

    private function _get_base_uri() {
        return "http://".$this->input->server('SERVER_NAME')."";
    }

    private function _get_user_email() {
        $auth = $this->input->get_request_header('Authorization');
        foreach ($this->input->request_headers() as $key => $value) {
            log_message('info', "Request header: $key => $value");
        }
        $h = $this->input->request_headers();
        $auth = $h['Authorization'];
        $pattern = '/Digest username="([^"]*)"/';
        $found = preg_match($pattern, $auth, $matches);
        // print_r($matches);
        if($found and isset($matches[1])) {
            $email = $matches[1];
        }        
        return $email;
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

class AESCryptFileLib
{
    const ENCRYPTED_FILE_EXTENSION = "aes";
    
    //http://www.leaseweblabs.com/2014/02/aes-php-mcrypt-key-padding/
    //Only "Rijndael-128" in "Cipher-block chaining" (CBC) mode is defined as the Advanced Encryption Standard (AES).
    //The file format specifies IV length of 128 bits (the block length) and key length of 256 bits
    //These are assumed to be implemented properly in all AES256 interfaces
    private $aes_impl;
    
    private $use_dynamic_filenaming;
    
    private $debugging = false;
    
    public function __construct(AES256Implementation $aes_impl, $use_dynamic_filenaming = true) {
        $this->aes_impl = $aes_impl;
        $this->use_dynamic_filenaming = $use_dynamic_filenaming;
        
        try {
            $this->aes_impl->checkDependencies();
        } catch (Exception $e) {
            throw new AESCryptMissingDependencyException($e->getMessage());
        }
    }
        
    public function enableDebugging()
    {
        $this->debugging = true;
    }
    
    public function encryptFile($source_file, $passphrase, $dest_file = NULL, $ext_data = NULL)
    {
        //Check we can read the source file
        $this->checkSourceExistsAndReadable($source_file);
        
        //Check any ext_data is formatted correctly
        $this->checkExtensionData($ext_data);
        
        //Check that the password is a string (it cannot be NULL)
        $this->checkPassphraseIsValid($passphrase);
        
        //Actually do the encryption here
        $dest_fh = $this->doEncryptFile($source_file, $passphrase, $dest_file, $ext_data);
        
        //Return encrypted file location
        $meta_data = stream_get_meta_data($dest_fh);
        fclose($dest_fh);
        $filename = realpath($meta_data["uri"]);
        return $filename;
    }
    
    public function readExtensionBlocks($source_file)
    {
        //Check we can read the source file
        $this->checkSourceExistsAndReadable($source_file);
        
        //Attempt to parse and return the extension blocks only
        //Open the file
        $source_fh = fopen($source_file, "rb");
        if ($source_fh === false) {
            throw new AESCryptFileAccessException("Cannot open file for reading: " . $source_file);
        }
        
        $this->readChunk($source_fh, 3, "file header", NULL, "AES");
        $version_chunk = $this->readChunk($source_fh, 1, "version byte", "C");
        $extension_blocks = array();
        if (bin2hex($version_chunk) === dechex(ord("0"))) {
            //This file uses version 0 of the standard
            //Extension blocks dont exist in this versions spec
            $extension_blocks = NULL;
        } else if (bin2hex($version_chunk) === dechex(ord("1"))) {
            //This file uses version 1 of the standard
            //Extension blocks dont exist in this versions spec
            $extension_blocks = NULL;
        } else if (bin2hex($version_chunk) === dechex(ord("2"))) {
            
            //This file uses version 2 of the standard (The latest standard at the time of writing)
            $this->readChunk($source_fh, 1, "reserved byte", "C", 0);
            $eb_index = 0;
            while (true) {
                //Read ext length
                $ext_length = $this->readChunk($source_fh, 2, "extension length", "n");
                if ($ext_length == 0) {
                    break;
                } else {
                    $ext_content = $this->readChunk($source_fh, $ext_length, "extension content");
                    
                    //Find the first NULL splitter character
                    $null_index = self::bin_strpos($ext_content, "\x00");
                    if ($null_index === false) {
                        throw new AESCryptCorruptedFileException("Extension block data at index {$eb_index} has no null splitter byte: " . $source_file);
                    }
                    
                    $identifier = self::bin_substr($ext_content, 0, $null_index);
                    $contents = self::bin_substr($ext_content, $null_index+1);
                    
                    if ($identifier != "") {
                        $extension_blocks[$eb_index] = array(
                            "identifier" => $identifier,
                            "contents" => $contents
                        );
                        $eb_index++;
                    }
                }
            }
        } else {
            throw new AESCryptCorruptedFileException("Unknown version: " . bin2hex($version_chunk));
        }
        return $extension_blocks;
    }
    
    public function decryptFile($source_file, $passphrase, $dest_file = NULL)
    {
        //Check we can read the source file
        $this->checkSourceExistsAndReadable($source_file);
        
        //Check whether the passphrase is correct before decrypting the keys and validating with HMAC1
        //If it is, attempt to decrypt the file using these keys and write to destination file
        $dest_fh = $this->doDecryptFile($source_file, $passphrase, $dest_file);
        
        //Return encrypted file location
        $meta_data = stream_get_meta_data($dest_fh);
        fclose($dest_fh);
        $filename = realpath($meta_data["uri"]);
        return $filename;
    }
    
    private function checkSourceExistsAndReadable($source_file)
    {
        //Source file must exist
        if (!file_exists($source_file)) {
            throw new AESCryptFileMissingException($source_file);
        }
        
        //Source file must be readable
        if (!is_readable($source_file)) {
            throw new AESCryptFileAccessException("Cannot read: " . $source_file);
        }
    }
    
    private function openDestinationFile($source_file, $dest_file, $encrypting = true) {
        
        //Please use checkSourceExistsAndReadable on the source before running this function as we assume it exists here
        $source_info = pathinfo($source_file);
        
        if ($dest_file === NULL) {
            if (!$encrypting) {
                //We are decrypting without a known destination file
                //We should check for a double extension in the file name e.g. (filename.docx.aes)
                //Actually, we just check it ends with .aes and strip off the rest
                if (preg_match("/^(.+)\." . self::ENCRYPTED_FILE_EXTENSION . "$/i", $source_info['basename'], $matches)) {
                    //Yes, source is an .aes file
                    //We remove the .aes part and use a destination file in the same source directory
                    $dest_file = $source_info['dirname'] . DIRECTORY_SEPARATOR . $matches[1];
                } else {
                    throw new AESCryptCannotInferDestinationException($source_file);
                }
                
            } else {
                //We are encrypting, use .aes as destination file extension
                $dest_file = $source_file . "." . self::ENCRYPTED_FILE_EXTENSION;
            }
        }
        
        if ($this->use_dynamic_filenaming) {
            //Try others until it doesnt exist
            $dest_info = pathinfo($dest_file);
            
            $duplicate_id = 1;
            while (file_exists($dest_file)) {
                //Check the destination file doesn't exist (We never overwrite)
                $dest_file = $dest_info['dirname'] . DIRECTORY_SEPARATOR . $dest_info['filename'] . "({$duplicate_id})." . $dest_info['extension'];
                $duplicate_id++;
            }
        } else {
            if (file_exists($dest_file)) {
                throw new AESCryptFileExistsException($dest_file);
            }
        }
        
        //Now that we found a non existing file, attempt to open it for writing
        $dest_fh = fopen($dest_file, "xb");
        if ($dest_fh === false) {
            throw new AESCryptFileAccessException("Cannot create for writing:" . $dest_file);
        }
                
        return $dest_fh;
    }
    
    private function readChunk($source_fh, $num_bytes, $chunk_name, $unpack_format = NULL, $expected_value = NULL)
    {
        $read_data = fread($source_fh, $num_bytes);
        if ($read_data === false) {
            throw new AESCryptFileAccessException("Could not read chunk " . $chunk_name . " of " . $num_bytes . " bytes");
        }
        
        if (self::bin_strlen($read_data) != $num_bytes) {
            throw new AESCryptCorruptedFileException("Could not read chunk " . $chunk_name . " of " . $num_bytes . " bytes, only found " . self::bin_strlen($read_data) . " bytes");
        }
        
        if ($unpack_format !== NULL) {
            $read_data = unpack($unpack_format, $read_data);
            if (is_array($read_data)) {
                $read_data = $read_data[1];
            }
        }
        
        
        if ($expected_value !== NULL) {
            if ($read_data !== $expected_value) {
                throw new AESCryptCorruptedFileException("The chunk " . $chunk_name . " was expected to be " . bin2hex($expected_value) . " but found " . bin2hex($read_data));
            }
        }
        return $read_data;
    }
    
    private function checkExtensionData($ext_data)
    {
        if ($ext_data === NULL) {
            return;
        }
        if (!is_array($ext_data)) {
            throw new AESCryptInvalidExtensionException("Must be NULL or an array (containing 'extension block' arrays)");
        }
        
        //Ignore associative arrays
        $ext_data = array_values($ext_data);
        
        $unique_identifiers = array();
        foreach ($ext_data as $index => $eb) {
            if (!is_array($eb)) {
                throw new AESCryptInvalidExtensionException("Extension block at index {$index} must be an array");
            }
            //Each block must contain the array keys 'identifier' and 'contents'
            if (!array_key_exists("identifier", $eb)) {
                throw new AESCryptInvalidExtensionException("Extension block at index {$index} must contain the key 'identifier'");
            }
            if (!array_key_exists("contents", $eb)) {
                throw new AESCryptInvalidExtensionException("Extension block at index {$index} must contain the key 'contents'");
            }
            
            $identifier = $eb['identifier'];
            $contents = $eb['contents'];
            if (!is_string($identifier)) {
                throw new AESCryptInvalidExtensionException("Extension block at index {$index} has a bad 'identifier' value.  It must be a string.");
            }
            if (!is_string($contents)) {
                throw new AESCryptInvalidExtensionException("Extension block at index {$index} has a bad 'contents' value.  It must be a string.");
            }
            
            if (in_array($identifier, $unique_identifiers)) {
                throw new AESCryptInvalidExtensionException("Extension block at index {$index} contains an 'identifier' which has already been used.  Make sure they are unique.");
            } else {
                $unique_identifiers[] = $identifier;
            }
        }
    }
    
    private function checkPassphraseIsValid($passphrase)
    {
        if ($passphrase === NULL) {
            throw new AESCryptInvalidPassphraseException("NULL passphrase not allowed");
        }
    }
    
    private function doEncryptFile($source_file, $passphrase, $dest_file, $ext_data)
    {
        $this->debug("ENCRYPTION", "Started");
        
        $header = "AES";
        $header .= pack("H*", "02"); //Version
        $header .= pack("H*", "00"); //Reserved
        
        //Generate the extension data
        $extdat_binary = $this->getBinaryExtensionData($ext_data);
        
        //Create a random IV using the aes implementation
        //IV is based on the block size which is 128 bits (16 bytes) for AES
        $iv_1 = $this->aes_impl->createIV();
        if (self::bin_strlen($iv_1) != 16) {
            throw new AESCryptImplementationException("Returned an IV which is not 16 bytes long: " . bin2hex($iv_1));
        }
        $this->debug("IV1", bin2hex($iv_1));
        
        //Use this IV and password to generate the first encryption key
        //We dont need to use AES for this as its just lots of sha hashing
        $passphrase = iconv(mb_internal_encoding(), 'UTF-16LE', $passphrase);
        $this->debug("PASSPHRASE", $passphrase);
        $enc_key_1 = $this->createKeyUsingIVAndPassphrase($iv_1, $passphrase);
        if (self::bin_strlen($enc_key_1) != 32) {
            throw new Exception("Returned a passphrase which is not 32 bytes long: " . bin2hex($enc_key_1));
        }
        $this->debug("KEY1", bin2hex($enc_key_1));
        
        //Create another set of keys to do the actual file encryption
        $iv_2 = $this->aes_impl->createIV();
        if (self::bin_strlen($iv_2) != 16) {
            throw new AESCryptImplementationException("Returned an IV which is not 16 bytes long: " . bin2hex($iv_2));
        }
        $this->debug("IV2", bin2hex($iv_2));
        
        //The file format uses AES 256 (which is the key length)
        $enc_key_2 = $this->aes_impl->createRandomKey();
        if (self::bin_strlen($enc_key_2) != 32) {
            throw new AESCryptImplementationException("Returned a random key which is not 32 bytes long: " . bin2hex($enc_key_2));
        }
        $this->debug("KEY2", bin2hex($enc_key_2));
        
        //Encrypt the second set of keys using the first keys
        $file_encryption_keys = $iv_2 . $enc_key_2;
        
        $encrypted_keys = $this->aes_impl->encryptData($file_encryption_keys, $iv_1, $enc_key_1);
        if (self::bin_strlen($encrypted_keys) != 48) {
            throw new Exception("Assertion 1 failed");
        }
        $this->debug("ENCRYPTED KEYS", bin2hex($encrypted_keys));
        //$this->assertLength($encrypted_keys, 48);
        
        //Calculate HMAC1 using the first enc key
        $hmac_1 = hash_hmac("sha256", $encrypted_keys, $enc_key_1, true);
        if (self::bin_strlen($hmac_1) != 32) {
            throw new Exception("Assertion 2 failed");
        }
        $this->debug("HMAC 1", bin2hex($hmac_1));
        //$this->assertLength($hmac_1, 32);
        
        //Now do file encryption
        $source_contents = file_get_contents($source_file);
        $encrypted_file_data = $this->aes_impl->encryptData($source_contents, $iv_2, $enc_key_2);
        
        $file_size_modulo = pack("C", self::bin_strlen($source_contents)%16);
        
        $this->debug("FS MODULO", bin2hex($file_size_modulo));
        
        //HMAC the encrypted data too
        $this->debug("MD5 of ENC DATA", md5($encrypted_file_data));
        $hmac_2 = hash_hmac("sha256", $encrypted_file_data, $enc_key_2, true);
        
        $this->debug("HMAC2", bin2hex($hmac_2));
        
        //Actaully write it to the dest fh
        $enc_data = $header . $extdat_binary . $iv_1 . $encrypted_keys . $hmac_1 . $encrypted_file_data . $file_size_modulo . $hmac_2;
        
        //Open destination file for writing
        $dest_fh = $this->openDestinationFile($source_file, $dest_file, true);
        $written = fwrite($dest_fh, $enc_data);
        if ($written === false) {
            throw new AESCryptFileAccessException("Could not write encrypted data to file.  Tried to write " . self::bin_strlen($enc_data) . " bytes");
        }
        $this->debug("ENCRYPTION", "Complete");
        
        return $dest_fh;
    }
    
    public function doEncryptContent($source_file, $passphrase, $dest_file=null, $ext_data=null)
    {
        $this->debug("ENCRYPTION", "Started");
        
        $header = "AES";
        $header .= pack("H*", "02"); //Version
        $header .= pack("H*", "00"); //Reserved
        
        //Generate the extension data
        $extdat_binary = $this->getBinaryExtensionData($ext_data);
        
        //Create a random IV using the aes implementation
        //IV is based on the block size which is 128 bits (16 bytes) for AES
        $iv_1 = $this->aes_impl->createIV();
        if (self::bin_strlen($iv_1) != 16) {
            throw new AESCryptImplementationException("Returned an IV which is not 16 bytes long: " . bin2hex($iv_1));
        }
        $this->debug("IV1", bin2hex($iv_1));
        
        //Use this IV and password to generate the first encryption key
        //We dont need to use AES for this as its just lots of sha hashing
        $passphrase = iconv(mb_internal_encoding(), 'UTF-16LE', $passphrase);
        $this->debug("PASSPHRASE", $passphrase);
        $enc_key_1 = $this->createKeyUsingIVAndPassphrase($iv_1, $passphrase);
        if (self::bin_strlen($enc_key_1) != 32) {
            throw new Exception("Returned a passphrase which is not 32 bytes long: " . bin2hex($enc_key_1));
        }
        $this->debug("KEY1", bin2hex($enc_key_1));
        
        //Create another set of keys to do the actual file encryption
        $iv_2 = $this->aes_impl->createIV();
        if (self::bin_strlen($iv_2) != 16) {
            throw new AESCryptImplementationException("Returned an IV which is not 16 bytes long: " . bin2hex($iv_2));
        }
        $this->debug("IV2", bin2hex($iv_2));
        
        //The file format uses AES 256 (which is the key length)
        $enc_key_2 = $this->aes_impl->createRandomKey();
        if (self::bin_strlen($enc_key_2) != 32) {
            throw new AESCryptImplementationException("Returned a random key which is not 32 bytes long: " . bin2hex($enc_key_2));
        }
        $this->debug("KEY2", bin2hex($enc_key_2));
        
        //Encrypt the second set of keys using the first keys
        $file_encryption_keys = $iv_2 . $enc_key_2;
        
        $encrypted_keys = $this->aes_impl->encryptData($file_encryption_keys, $iv_1, $enc_key_1);
        if (self::bin_strlen($encrypted_keys) != 48) {
            throw new Exception("Assertion 1 failed");
        }
        $this->debug("ENCRYPTED KEYS", bin2hex($encrypted_keys));
        //$this->assertLength($encrypted_keys, 48);
        
        //Calculate HMAC1 using the first enc key
        $hmac_1 = hash_hmac("sha256", $encrypted_keys, $enc_key_1, true);
        if (self::bin_strlen($hmac_1) != 32) {
            throw new Exception("Assertion 2 failed");
        }
        $this->debug("HMAC 1", bin2hex($hmac_1));
        //$this->assertLength($hmac_1, 32);
        
        //Now do file encryption
        $source_contents = file_get_contents($source_file);
        $encrypted_file_data = $this->aes_impl->encryptData($source_contents, $iv_2, $enc_key_2);
        
        $file_size_modulo = pack("C", self::bin_strlen($source_contents)%16);
        
        $this->debug("FS MODULO", bin2hex($file_size_modulo));
        
        //HMAC the encrypted data too
        $this->debug("MD5 of ENC DATA", md5($encrypted_file_data));
        $hmac_2 = hash_hmac("sha256", $encrypted_file_data, $enc_key_2, true);
        
        $this->debug("HMAC2", bin2hex($hmac_2));
        
        //Actaully write it to the dest fh
        $enc_data = $header . $extdat_binary . $iv_1 . $encrypted_keys . $hmac_1 . $encrypted_file_data . $file_size_modulo . $hmac_2;

        return $enc_data;
        
        // //Open destination file for writing
        // $dest_fh = $this->openDestinationFile($source_file, $dest_file, true);
        // $written = fwrite($dest_fh, $enc_data);
        // if ($written === false) {
        //     throw new AESCryptFileAccessException("Could not write encrypted data to file.  Tried to write " . self::bin_strlen($enc_data) . " bytes");
        // }
        // $this->debug("ENCRYPTION", "Complete");
        
        // return $dest_fh;
    }
    
    private function doDecryptFile($source_file, $passphrase, $dest_file)
    {
        $this->debug("DECRYPTION", "Started");
        
        //Check we can read the source file
        $this->checkSourceExistsAndReadable($source_file);
        
        //Attempt to parse and return the extension blocks only
        //Open the file
        $source_fh = fopen($source_file, "rb");
        if ($source_fh === false) {
            throw new AESCryptFileAccessException("Cannot open file for reading: " . $source_file);
        }
        
        $this->readChunk($source_fh, 3, "file header", NULL, "AES");
        $version_chunk = $this->readChunk($source_fh, 1, "version byte", "C");
        $extension_blocks = array();
        if ($version_chunk === 0) {
            //This file uses version 0 of the standard
            $file_size_modulos = $this->readChunk($source_fh, 1, "file size modulo", "C", 0);
            if ($file_size_modulos === false) {
                throw new Exception("Could not decode file size modulos");
            }
            if ($file_size_modulos < 0 || $file_size_modulos >= 16) {
                throw new Exception("Invalid file size modulos: " . $file_size_modulos);
            }
            
            $iv = $this->readChunk($source_fh, 16, "IV");
            
            $rest_of_data = "";
            while (!feof($source_fh)) {
                $rest_of_data .= fread($source_fh, 8192); //Read in 8K chunks
            }
            $encrypted_data = self::bin_substr($rest_of_data, 0, -32);
            $hmac = self::bin_substr($rest_of_data, -32, 32);
            
            //Convert the passphrase to UTF-16LE
            $passphrase = iconv(mb_internal_encoding(), 'UTF-16LE', $passphrase);
            $this->debug("PASSPHRASE", bin2hex($passphrase));
            $enc_key = $this->createKeyUsingIVAndPassphrase($iv, $passphrase);
            $this->debug("ENCKEYFROMPASSWORD", bin2hex($enc_key));
            
            //We simply use this enc key to decode the payload
            //We do not know if it is correct yet until we finish decrypting the data
            
            $decrypted_data = $this->aes_impl->decryptData($encrypted_data, $iv, $enc_key);
            if ($file_size_modulos > 0) {
                $decrypted_data = self::bin_substr($decrypted_data, 0, ((16 - $file_size_modulos) * -1));
            }
            
            //Here the HMAC is (probably) used to verify the decrypted data
            //TODO: Test this using known encrypted files using version 0
            $this->validateHMAC($enc_key, $decrypted_data, $hmac, "HMAC");
            
            //Open destination file for writing
            $dest_fh = $this->openDestinationFile($source_file, $dest_file, false);
            
            $result = fwrite($dest_fh, $decrypted_data);
            if ($result === false) {
                throw new Exception("Could not write back file");
            }
            if ($result != self::bin_strlen($decrypted_data)) {
                throw new Exception("Could not write back file");
            }
            $this->debug("DECRYPTION", "Completed");
            return $dest_fh;
            
        } else if ($version_chunk === 1 || $version_chunk === 2) {
            if ($version_chunk === 1) {
                //This file uses version 1 of the standard
                $this->readChunk($source_fh, 1, "reserved byte", "C", 0);
            } else if ($version_chunk === 2) {
                //This file uses version 2 of the standard (The latest standard at the time of writing)
                $this->readChunk($source_fh, 1, "reserved byte", "C", 0);
                while (true) {
                    //Read ext length
                    $ext_length = $this->readChunk($source_fh, 2, "extension length", "n");
                    if ($ext_length == 0) {
                        break;
                    } else {
                        $this->readChunk($source_fh, $ext_length, "extension content");
                    }
                }
            }
            
            $iv_1 = $this->readChunk($source_fh, 16, "IV 1");
            $this->debug("IV1", bin2hex($iv_1));
            $enc_keys = $this->readChunk($source_fh, 48, "Encrypted Keys");
            $this->debug("ENCRYPTED KEYS", bin2hex($enc_keys));
            $hmac_1 = $this->readChunk($source_fh, 32, "HMAC 1");
            $this->debug("HMAC1", bin2hex($hmac_1));
            
            //Convert the passphrase to UTF-16LE
            $passphrase = iconv(mb_internal_encoding(), 'UTF-16LE', $passphrase);
            $this->debug("PASSPHRASE", bin2hex($passphrase));
            $enc_key_1 = $this->createKeyUsingIVAndPassphrase($iv_1, $passphrase);
            $this->debug("ENCKEY1FROMPASSWORD", bin2hex($enc_key_1));
            
            $this->validateHMAC($enc_key_1, $enc_keys, $hmac_1, "HMAC 1");
            
            
            $rest_of_data = "";
            while (!feof($source_fh)) {
                $rest_of_data .= fread($source_fh, 8192); //Read in 8K chunks
            }
            $encrypted_data = self::bin_substr($rest_of_data, 0, -33);
            $file_size_modulos = unpack("C", self::bin_substr($rest_of_data, -33, 1));
            $file_size_modulos = $file_size_modulos[1];
            if ($file_size_modulos === false) {
                throw new Exception("Could not decode file size modulos");
            }
            if ($file_size_modulos < 0 || $file_size_modulos >= 16) {
                throw new Exception("Invalid file size modulos: " . $file_size_modulos);
            }
            
            $hmac_2 = self::bin_substr($rest_of_data, -32);
            $this->debug("HMAC2", bin2hex($hmac_2));
            
            $decrypted_keys = $this->aes_impl->decryptData($enc_keys, $iv_1, $enc_key_1);
            $this->debug("DECRYPTED_KEYS", bin2hex($decrypted_keys));
            
            $iv_2 = self::bin_substr($decrypted_keys, 0, 16);
            $enc_key_2 = self::bin_substr($decrypted_keys, 16);
            
            $this->debug("MD5 of ENC DATA", md5($encrypted_data));
            
            $this->validateHMAC($enc_key_2, $encrypted_data, $hmac_2, "HMAC 2");
            //All keys were correct, we can be sure that the decrypted data will be correct
            $decrypted_data = $this->aes_impl->decryptData($encrypted_data, $iv_2, $enc_key_2);
            //Modulos tells us how many bytes to trim from the end
            if ($file_size_modulos > 0) {
                $decrypted_data = self::bin_substr($decrypted_data, 0, ((16 - $file_size_modulos) * -1));
            }
            
            //Open destination file for writing
            $dest_fh = $this->openDestinationFile($source_file, $dest_file, false);
            
            $result = fwrite($dest_fh, $decrypted_data);
            if ($result === false) {
                throw new Exception("Could not write back file");
            }
            if ($result != self::bin_strlen($decrypted_data)) {
                throw new Exception("Could not write back file");
            }
            $this->debug("DECRYPTION", "Completed");
            return $dest_fh;
        } else {
            throw new Exception("Invalid version chunk: " . $version_chunk);
        }
        throw new Exception("Not implemented");
    }
    
    //Converts the given extension data in to binary data
    private function getBinaryExtensionData($ext_data)
    {
        $this->checkExtensionData($ext_data);
        
        if ($ext_data === NULL) {
            $ext_data = array();
        }
        
        $output = "";
        foreach ($ext_data as $ext) {
            $ident = $ext['identifier'];
            $contents = $ext['contents'];
            $data = $ident . pack("C", 0) . $contents;
            $output .= pack("n", self::bin_strlen($data));
            $output .= $data;
        }
        
        //Also insert a 128 byte container
        $data = str_repeat(pack("C", 0), 128);
        $output .= pack("n", self::bin_strlen($data));
        $output .= $data;
        
        //2 finishing NULL bytes to signify end of extensions
        $output .= pack("C", 0);
        $output .= pack("C", 0);
        return $output;
    }
    
    //This is sha256 by standard and should always returns 256bits (32 bytes) of hash data
    //Looking at the java implementation, it seems we should iterate the hasing 8192 times
    private function createKeyUsingIVAndPassphrase($iv, $passphrase) 
    {
        //Start with the IV padded to 32 bytes
        $aes_key = str_pad($iv, 32, hex2bin("00"));
        $iterations = 8192;
        for($i=0; $i<$iterations; $i++)
        {
            $hash = hash_init("sha256");
            hash_update($hash, $aes_key);
            hash_update($hash, $passphrase);
            $aes_key = hash_final($hash, true);
        }
        return $aes_key;
    }
    
    private function validateHMAC($key, $data, $hash, $name)
    {
        $calculated = hash_hmac("sha256", $data, $key, true);
        $actual = $hash;
        if ($calculated != $actual) {
            $this->debug("CALCULATED", bin2hex($calculated));
            $this->debug("ACTUAL", bin2hex($actual));
            if ($name == "HMAC 1") {
                throw new AESCryptInvalidPassphraseException("{$name} failed to validate integrity of encryption keys.  Incorrect password or file corrupted.");
            } else {
                throw new AESCryptCorruptedFileException("{$name} failed to validate integrity of encrypted data.  The file is corrupted and should not be trusted.");
            }
        }
    }
    
    private function debug($name, $msg) {
        if ($this->debugging) {
            echo "<br/>";
            echo $name . " - " . $msg;
            echo "<br/>";
        }
    }
    
    //http://php.net/manual/en/mbstring.overload.php
    //String functions which may be overloaded are: mail, strlen, strpos, strrpos, substr, 
    //strtolower, strtoupper, stripos, strripos, strstr, stristr, strrchr, 
    //substr_count, ereg, eregi, ereg_replace, eregi_replace, split
    //
    //Since we use some of these str_ php functions to manipulate binary data,
    //to prevent accidental multibyte string functions thinking binary data is a
    //multibyte string and breaking the engine, we use the 8bit mode 
    //with the mb_ equivalents if they exist.
    
    //Functions we use and so must wrap: strlen, strpos, substr
    
    public static function bin_strlen($string) {
        if (function_exists('mb_strlen')) {
            return mb_strlen($string, '8bit');
        } else {
            return strlen($string);
        }
    }
    
    public static function bin_strpos($haystack, $needle, $offset = 0) {
        if (function_exists('mb_strpos')) {
            return mb_strpos($haystack, $needle, $offset, '8bit');
        } else {
            return strpos($haystack, $needle, $offset);
        }
    }
    
    public static function bin_substr($string, $start, $length = NULL) {
        if (function_exists('mb_substr')) {
            return mb_substr($string, $start, $length, '8bit');
        } else {
            return substr($string, $start, $length);
        }
    }
    
}

class AESCryptMissingDependencyException extends Exception {} //E.g. missing mcrypt
class AESCryptCorruptedFileException extends Exception {} //E.g. when file looks corrupted or wont parse
class AESCryptFileMissingException extends Exception {} //E.g. cant read file to encrypt
class AESCryptFileAccessException extends Exception {} //E.g. read/write error on files
class AESCryptFileExistsException extends Exception {} //E.g. when a destination file exists (we never overwrite)
class AESCryptInvalidExtensionException extends Exception {} //E.g. when an extension array is invalid
class AESCryptInvalidPassphraseException extends Exception {} //E.g. when the password is wrong
class AESCryptCannotInferDestinationException extends Exception {} //E.g. when we try to decrypt a 3rd party written file which doesnt have the standard file name convention
class AESCryptImplementationException extends Exception {} //For generic exceptions by the aes implementation used

interface AES256Implementation
{
    public function checkDependencies();
    public function createIV();
    public function createRandomKey();
    public function encryptData($the_data, $iv, $enc_key);
    public function decryptData($the_data, $iv, $enc_key);
}

class MCryptAES256Implementation implements AES256Implementation
{
    const BLOCK_SIZE = 16; // 128 bits
    const KEY_SIZE = 32; // 256 bits
    const MY_MCRYPT_CIPHER = MCRYPT_RIJNDAEL_128; //AES
    const MY_MCRYPT_MODE = MCRYPT_MODE_CBC; //AES
    
    public function checkDependencies()
    {
        $function_list = array(
            "mcrypt_create_iv",
            "mcrypt_encrypt",
            "mcrypt_decrypt",
        );
        foreach ($function_list as $func) {
            if (!function_exists($func)) {
                throw new Exception("Missing function dependency: " . $func);
            }
        }
    }
    
    public function createIV()
    {
        return mcrypt_create_iv( self::BLOCK_SIZE, MCRYPT_RAND );
    }
    
    public function createRandomKey()
    {
        return mcrypt_create_iv( self::KEY_SIZE, MCRYPT_RAND );
    }
    
    public function encryptData($the_data, $iv, $enc_key)
    {
        return mcrypt_encrypt( self::MY_MCRYPT_CIPHER, $enc_key, $the_data , self::MY_MCRYPT_MODE , $iv );
    }
    
    public function decryptData($the_data, $iv, $enc_key)
    {
        return mcrypt_decrypt( self::MY_MCRYPT_CIPHER, $enc_key, $the_data , self::MY_MCRYPT_MODE , $iv );
    }
}


