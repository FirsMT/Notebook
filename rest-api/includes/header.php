<?php

ini_set('display_error', 1);

header('Content-Type: application/json');


include_once '../../config/Database.php';
include_once '../../models/Note.php';


$database = new Database;
$db = $database->getConnection();