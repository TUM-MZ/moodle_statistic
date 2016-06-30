<?php

include_once './system/config.php';

if (isset($_GET)) {
    clearGETParam();
    $ajax_response = [];
    $activeView = 'course';

    if (count($_GET) > 0) {

        // # GLOBAL DATA
        if (isset($_GET['new'])) {
            if (isset($_GET['style'])) {
                $activeView = $_GET['style'];
            }
            
            include_once "incs/category/output_category_$activeView.php";
        }

        if (isset($_GET['replace'])) {
            $activePath = $_GET['replace'];
            if (isset($_GET['style'])) {
                $activeView = $_GET['style'];
            }

            include_once "incs/category/output_category_$activeView.php";
        }
    }
}