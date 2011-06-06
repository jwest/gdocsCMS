<?php
/**
 * Cache
 * @author jwest
 */
class Cache {


   /**
    * Config object instance
    * @var object
    */
    private $config;


    public function __construct(Config $config)
    {
        $this->config = $config;
    }


   /**
    * Reset cache (delete all cache files)
    * @return bool
    */
    public function reset()
    {
        foreach(glob(ROOT.$this->config->data.'*') as $file)
        {
            $this->delete_page($file);
        }

        return true;
    }


   /**
    * Check page
    * @param string $id id for page
    * @return bool
    */
    public function check_page($id)
    {
        $file = ROOT.$this->config->data.md5($id).'/content.html';

        if(file_exists($file))
        {
            if(filemtime($file)+$this->config->cache_time < time() and $this->config->cache_time != 0)
                return false;

            else
                return true;
        }
        else
            return false;
    }


    private function delete_page($path)
    {
        foreach(glob($path.'*') as $file)
        {
            if(is_file($file))
                unlink($file);
            else
                $this->delete_page($file.'/');
        }

        @rmdir($path);
        return true;
    }


    /**
    * Get menu from cache
    * @return bool
    */
    public function get_menu()
    {
        $file = ROOT.$this->config->data.'menu.dat';

        if(file_exists($file))
        {
            if(filemtime($file)+$this->config->cache_time < time() and $this->config->cache_time != 0)
                return false;

            else
                return unserialize(file_get_contents($file));
        }
        else
            return false;
    }


   /**
    * Save menu in cache
    */
    public function save_menu($menu)
    {
        file_put_contents(ROOT.$this->config->data.'menu.dat', serialize($menu));
    }
    

}
?>
