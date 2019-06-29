<?php

require_once "../functions.php";

$userID = !empty($_POST['userID']) ? trim($_POST['userID']) : null;
$name = !empty($_POST['name']) ? trim($_POST['name']) : null;
$date = date("Y-m-d H:i", strtotime(!empty($_POST['date']) ? trim($_POST['date']) : null));
$petID = !empty($_POST['petID']) ? trim($_POST['petID']) : null;
$description = !empty($_POST['description']) ? trim($_POST['description']) : null;
$phone = !empty($_POST['phone']) ? trim($_POST['phone']) : null;
$current_date = new DateTime();
$message = array();
$ok = true;
$insertQuery = new Query();


if (!empty($phone)) {
    $bind = array (
        1 => array (
            'key' => 'phone',
            'value' => $phone
        ),
        2 => array (
            'key' => 'userID',
            'value' => $userID
        )
    );
    $insert = $insertQuery->setQuery("UPDATE user SET phone = :phone WHERE userID = :userID", $bind);
}


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