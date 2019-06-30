<?php

require_once "../functions.php";

$appointmentID = !empty($_POST['appointmentID']) ? trim($_POST['appointmentID']) : null;
$name = !empty($_POST['name']) ? trim($_POST['name']) : null;
$description = !empty($_POST['description']) ? trim($_POST['description']) : null;
$date = date("Y-m-d H:i", strtotime(!empty($_POST['date']) ? trim($_POST['date']) : null));
$query = new Query;

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
        'key' => 'appointmentID',
        'value' => $appointmentID
    )
);

$query->setQuery("UPDATE appointment SET name = :name, description = :description, date = :date, state = 1 WHERE appointmentID = :appointmentID", $bind);

header("Location: /");