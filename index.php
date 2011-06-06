<?php

// Define const values
define('ROOT', dirname(__FILE__).'/');

// Set main settings for php intrpreter
ini_set('display_errors', 'On');
error_reporting (E_ALL | E_NOTICE);
date_default_timezone_set('Poland');

//init your config class
require ROOT.'config.php';
$config = new Config;

define('URL', $config->url);
define('MEDIA', $config->media);

require ROOT.'core/core.php';
require ROOT.'core/api.php';
require ROOT.'core/cache.php';
require ROOT.'core/document.php';
require ROOT.'core/view.php';

try
{
    $core  = new Core();
    $cache = new Cache($config);    
    $API = false;

    $ID = $core->dispatch()->execute();

    //-------------------------------------
    if($ID === $config->cache_key)
    {
        $cache->reset();
        throw new Exception('reset cache!');
    }
    //-------------------------------------

    if(!($pages = $cache->get_menu()))
    {
        $API = new API($config);
        $pages = $API->get_pages();
        $cache->save_menu($pages);
    }

    if(!isset($pages[$ID]))
        throw new Exception ('404 - Not found');

    $docs  = new Document($pages[$ID], $config);

    if($cache->check_page($pages[$ID]['id']))
        $docs->get();

    else
    {
        if($API === false)
            $API = new API($config);

        $docs
            ->extract($API->get($docs->get_id()))
            ->clean_docs()
            ->save();
    }

    $content = (object) array
    (
        'menu' => $pages,
        'style' => $docs->get_style(),
        'name' => $docs->get_name(),
        'content' => $docs->get_content()
    );

    echo View::render('template', array('content' => $content));

}
//catch all error in cms
catch(Exception $e)
{
    echo '<b>'.$e->getMessage().'</b> GdocsCMS';
}
?>
