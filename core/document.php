<?php
class Document {


    private $id;
    private $name;
    private $style;
    private $content;
    private $data_path;


    public function get_id()
    {
        return $this->id;
    }


    public function get_name()
    {
        return $this->name;
    }


    public function get_style()
    {
        return $this->style;
    }


    public function get_content()
    {
        return $this->content;
    }


    public function  __construct($page, Config $config)
    {
        $this->id = $page['id'];
        $this->name = $page['name'];
        $this->data_path = $config->data;
    }


    public function get()
    {
        $this->style = file_get_contents(ROOT.$this->data_path.md5($this->id).'/style.css');
        $this->content = file_get_contents(ROOT.$this->data_path.md5($this->id).'/content.html');
    }


    public function extract($zip_bin)
    {
        if(!file_exists(ROOT.$this->data_path.md5($this->id)))
            mkdir(ROOT.$this->data_path.md5($this->id));

        file_put_contents(ROOT.$this->data_path.md5($this->id).'/temp.zip', $zip_bin, FILE_BINARY);

        $zip = new ZipArchive; 
        $zip->open(ROOT.$this->data_path.md5($this->id).'/temp.zip');
        $zip->extractTo(ROOT.$this->data_path.md5($this->id).'/');
        $zip->close();

        $file = glob(ROOT.$this->data_path.md5($this->id).'/*.html');
        $file = $file[0];

        $this->content = file_get_contents($file);

        unlink($file);
        unlink(ROOT.$this->data_path.md5($this->id).'/temp.zip');

        return $this;
    }


    public function clean_docs()
    {
        preg_match('/<style.*>(.*)?<\/style>/', $this->content, $this->style);
        preg_match('/<body class="[-a-z0-9_]*">(.*)?<\/body>/ims', $this->content, $this->content);
        $this->style = $this->style[1];
        $this->content = str_replace('src="images/', 'src="'.MEDIA.md5($this->id).'/images/', $this->content[1]);

        return $this;
    }


    public function save()
    {
        file_put_contents(ROOT.$this->data_path.md5($this->id).'/style.css', $this->style);
        file_put_contents(ROOT.$this->data_path.md5($this->id).'/content.html', $this->content);
    }


}
?>
