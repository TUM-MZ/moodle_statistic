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
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.0/css/font-awesome.min.css"/>
    </head>
    <body>
        <?php
        include_once './incs/system/course_without_forum.php';
        ?>
        
        <script type="text/javascript" src="js/jquery-2.2.3.min.js"> </script>
        <script type="text/javascript" src="js/jquery_tablesorter/jquery.tablesorter.min.js"> </script>
        <!-- script src="http://canvasjs.com/assets/script/canvasjs.min.js"> </script -->
        <script type="text/javascript" src="js/canvasjs/canvasjs.min.js"> </script>
        <script type="text/javascript" src="js/default.js"> </script>
        
    </body>
</html>
