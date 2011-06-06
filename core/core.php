<?php
class Core {


    private $param = array();


    public function dispatch($delimiter = '/')
    {
        $url = (isset($_SERVER['PATH_INFO'])) ? substr($_SERVER['PATH_INFO'], 1) : null;
        
        if($url[strlen($url)-1]=='/')
            $url = substr($url, 0, strlen($url)-1);

        $this->param = explode($delimiter, $url);

        return $this;
    }


    public function execute()
    {
        if(isset($this->param[1]))
            return $this->param[1];
        else
            return 0;
    }


}

?>
