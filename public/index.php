<?php

try {
    ini_set('error_reporting', E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    require_once('../core/init.php');
} catch (Exception $e) {
    if (!env('DEBUG')) {
        exit(500);
    }
}





