<?php
/**
 * Description of API
 *
 * @author jwest
 */
class API {


   /**
    * CURL data
    * @var curl
    */
    private $curl;


   /**
    * Google auth key
    * @var string
    */
    private $auth;


   /**
    * Folder id for your page storage
    * @var string
    */
    private $folder_id;


   /**
    * Initialize API
    * @param string $auth
    * @param curl $curl
    */
    public function __construct(Config $c)
    {
        $this->folder_id = $c->folder_id;        
        $this->auth($c->account_type, $c->email, $c->password);
    }


   /**
    * Return headers for requests
    * @return array
    */
    private function _headers()
    {
        return array
        (
            "Authorization: GoogleLogin auth=" . $this->auth,
            "GData-Version: 3.0",
        );
    }


   /**
    * Authentyfication users in google ClinetLogin curl
    */
    private function auth($type, $username, $password)
    {
        $this->curl = curl_init('https://www.google.com/accounts/ClientLogin');
        
        $post = array
        (
            "accountType" => $type,
            "Email" => $username,
            "Passwd" => $password,
            "service" => 'writely',
            "source" => "GdocsCMS",
        );
        
        // Set some options (some for SHTTP)
        curl_setopt($this->curl, CURLOPT_POST, true);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $post);
        curl_setopt($this->curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);

        // Execute
        $response = curl_exec($this->curl);

        // Get the Auth string and save it
        preg_match("/Auth=([a-z0-9_\-]+)/i", $response, $matches);
        $this->auth = $matches[1];
    }


   /**
    * Get pages lists
    * @return response
    */
    public function get_pages()
    {
        curl_setopt($this->curl, CURLOPT_URL, 'http://docs.google.com/feeds/default/private/full/folder%3A'.$this->folder_id.'/contents/');
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $this->_headers());
        curl_setopt($this->curl, CURLOPT_POST, false);

        $xml = simplexml_load_string(curl_exec($this->curl));
        $pages = array();

        foreach($xml->entry as $page)
        {
            $page_ex = explode('.', $page->title[0], 2);
            $pages[$page_ex[0]] = array
            (
                'id' => str_replace('http://docs.google.com/feeds/id/document%3A', '', $page->id),
                'name' => $page_ex[1],
                'sort' => $page_ex[0],
            );
        }

        ksort($pages);
        return $pages;
    }


   /**
    * Get docs as zip package with html and images
    * @param string $id_resource
    * @return binary
    */
    public function get($resource_id)
    {
        curl_setopt($this->curl, CURLOPT_URL, 'http://docs.google.com/feeds/download/documents/Export?id='.$resource_id.'&exportFormat=zip&format=zip');
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $this->_headers());
        curl_setopt($this->curl, CURLOPT_POST, false);
        curl_setopt($this->curl, CURLOPT_BINARYTRANSFER,true);

        return curl_exec($this->curl);
    }


}
?>
