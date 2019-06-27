<?php

require_once "../functions.php";

$name = !empty($_POST['name']) ? trim($_POST['name']) : null;
$date = date("Y-m-d", strtotime(!empty($_POST['date']) ? trim($_POST['date']) : null));
$petID = !empty($_POST['petID']) ? trim($_POST['petID']) : null;
$description = !empty($_POST['description']) ? trim($_POST['description']) : null;

$current_date = new DateTime();
$message = array();
$ok = true;
$insertQuery = new Query();

$bind = array (
    1 => array (
        'key' => 'name',
        'value' => $name
    ),
    2 => array (
        'key' => 'description',
        'value' => $description
    ),
    3 => array (
        'key' => 'date',
        'value' => $date
    ),
    4 => array (
        'key' => 'petID',
        'value' => $petID
    )

);
$insert = $insertQuery->setQuery("INSERT INTO appointment (name, description, date, petID) VALUES (:name, :description, :date, :petID)", $bind);

echo json_encode(
    array(
        'message' => $message,
        'ok' => $ok
    )
);