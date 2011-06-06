<?php
class View{


   /**
    * Render page view
    * @param string $file
    * @param array $data
    */
    public static function render($file, $data = array())
    {
        ob_start();

        extract($data);

        if(!file_exists(ROOT.$file.'.php'))
            throw new Exception('Template not found');

        include ROOT.$file.'.php';

        $buffer = ob_get_contents();
        ob_end_clean();

        return $buffer;
    }


}
?>
