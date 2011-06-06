<?php
/**
 * GdocsCMS config
 */
class Config{


    //main url
    public $url = 'http://localhost/index.php/';
    public $media = 'http://localhost/data/';


    //cache and data
    public $data = 'data/';


    //Google account
    public $account_type = 'HOSTED_OR_GOOGLE';
    public $email = '';
    public $password = '';

    
    //Google docs folder id
    public $folder_id = '';


    //set cache life time (in sec.). Change to 0 if you have only manual cache reset.
    public $cache_time = 0;


    //your private key to reset cache (http://yourpage.com/key)
    public $cache_key = 'reset';
    

}
?>
