<?php
var_dump($_REQUEST);
var_dump((isset($HTTP_RAW_POST_DATA)?$HTTP_RAW_POST_DATA:''));
var_dump((isset(file_get_contents("php://input"))?file_get_contents("php://input"):''));
var_dump($_FILES);
