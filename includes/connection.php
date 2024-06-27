<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    $database= new mysqli("localhost","root","","edoc");
    if ($database->connect_error){
        die("Connection failed:  ".$database->connect_error);
    }
