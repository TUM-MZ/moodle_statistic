<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once './system/config.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Statistik</title>
        <link rel="stylesheet" href="style/app.css"/>
        <link rel="stylesheet" href="style/font-awesome-4.6.3/css/font-awesome.min.css"/>
    </head>
    <body>
        
        <?php
        include_once 'menu/mainmenu_index.html';
        include_once 'page/index.html';
        ?>
        
        <script type="text/javascript" src="js/jquery-2.2.3.min.js"> </script>
        <script type="text/javascript" src="js/jquery-ui-1.12.0/jquery-ui.min.js"></script>
        
        <script type="text/javascript" src="js/listjs/list.min.js"></script>
        
        <script type="text/javascript" src="js/canvasjs/canvasjs.min.js"> </script>
        
        <script type="text/javascript" src="js/default.js"> </script>
    </body>
</html>
