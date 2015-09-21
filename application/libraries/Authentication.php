<?php

class Authentication  {
	public function login($username,$password) {
        $mysqli = new mysqli('localhost', 'root', '123qwe', 'opencart');
        if ($mysqli->connect_errno) {
            return FALSE;
        }
        $sql = "SELECT * FROM oc_customer WHERE email = '$username'";
        $result = $mysqli->query($sql);
        if (!$result) {
            return FALSE;
        }
        if ($result->num_rows === 0) {
            return FALSE;
        }
        $result_array = $result->fetch_assoc();
        $password = $result_array['password_cleartext'];

    // # --- ENCRYPTION ---

    // # the key should be random binary, use scrypt, bcrypt or PBKDF2 to
    // # convert a string into a key
    // # key is specified using hexadecimal
    // // $key = pack('H*', "bcb04b7e103a0cd8b54763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3");
    // $key = pack('H*', $result_array['salt']);
    
    // # show key size use either 16, 24 or 32 byte keys for AES-128, 192
    // # and 256 respectively
    // $key_size =  strlen($key);
    // echo "Key size: " . $key_size . "\n";
    
    // $plaintext = 'saikat';

    // # create a random IV to use with CBC encoding
    // $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
    // $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
    
    // # creates a cipher text compatible with AES (Rijndael block size = 128)
    // # to keep the text confidential 
    // # only suitable for encoded input that never ends with value 00h
    // # (because of default zero padding)
    // $ciphertext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key,
    //                              $plaintext, MCRYPT_MODE_CBC, $iv);

    // # prepend the IV for it to be available for decryption
    // $ciphertext = $iv . $ciphertext;
    
    // # encode the resulting cipher text so it can be represented by a string
    // $ciphertext_base64 = base64_encode($ciphertext);

    // echo "\nCIPHERTEXT\n". $ciphertext_base64 . "\n$iv_size\n";

    // # === WARNING ===

    // # Resulting cipher text has no integrity or authenticity added
    // # and is not protected against padding oracle attacks.
    
    // # --- DECRYPTION ---
    
    // $ciphertext_dec = base64_decode($result_array['password']);
    
    // # retrieves the IV, iv_size should be created using mcrypt_get_iv_size()
    // $iv_dec = substr($ciphertext_dec, 0, $iv_size);
    
    // # retrieves the cipher text (everything except the $iv_size in the front)
    // $ciphertext_dec = substr($ciphertext_dec, $iv_size);

    // # may remove 00h valued characters from end of plain text
    // $plaintext_dec = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key,
    //                                 $ciphertext_dec, MCRYPT_MODE_CBC, $iv_dec);
    // $temp = rtrim($plaintext_dec);
    
    // // echo  utf8_decode($plaintext_dec) . "\n";        
    // // echo  mb_detect_encoding($plaintext_dec) . "\n";        
    // // echo  mb_detect_encoding("saikat") . "\n";        

        // $md5str = "$username:REST API:$plaintext_dec";
        $md5str2 = "$username:REST API:$password";
        // print $md5str."\n";
        // print $md5str2."\n";
        // print_r(unpack("H*",$plaintext_dec));
        // print_r(unpack("H*",$md5str2));
		return md5($md5str2);
	}	
}
