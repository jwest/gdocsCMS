<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xml:lang="en" lang="pl" xml‎ns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title><?php echo $content->name //get page name ?> - GdocsCMS - v.0.1</title>
        <meta http-equiv="Content-Language" content="pl" />
        <meta http-equiv="Reply-to" content="jwest@jwest.pl" />

        <style type="text/css">
            body{margin: 0;}
            <?php echo $content->style; // get style from gdocs ?>
        </style>

    </head>
    <body>

        <div style="background-color: #333; color: #fff;">

            <h2 style="color: #fff; padding: 5px;"><?php echo $content->name //get page name ?></h2>

            <?php foreach($content->menu as $id => $item): //show menu?>
                <a style="padding: 3px 5px; color: #ddd; text-decoration: none; font-weight: bold;" href="<?php echo URL.'page/'.$id ?>"><?php echo $item['name'] ?></a> |
            <?php endforeach; //end show menu?>
                
        </div>

        <div style="margin: 5px 50px;">

            <?php echo $content->content; //show page content?>

        </div>

    </body>
</html>
