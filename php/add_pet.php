<?php

require_once "../functions.php";

$name = !empty($_POST['name']) ? trim($_POST['name']) : null;
$breed = !empty($_POST['breed']) ? trim($_POST['breed']) : null;
$birth = date("Y-m-d", strtotime(!empty($_POST['birth']) ? trim($_POST['birth']) : null));
$current_date = new DateTime();
$target_dir = "../img/pets/";
$message = array();
$ok = true;
$insertQuery = new Query();
$bind = array (
    1 => array (
        'key' => 'name',
        'value' => $name
    ),
    2 => array (
        'key' => 'breedID',
        'value' => $breed
    ),
    3 => array (
        'key' => 'birth',
        'value' => $birth
    ),
    4 => array (
        'key' => 'userID',
        'value' => $userObj->getUserID()
    )

);

if (!empty($_FILES["file"]['name'])) {
    $uploadOk = true;
    $file = $_FILES["file"];
    $target_file = $target_dir . basename($file["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $check = getimagesize($file["tmp_name"]);
}


$insert = $insertQuery->setQuery("INSERT INTO pet (name, breedID, birth, userID) VALUES (:name, :breedID, :birth, :userID)", $bind);

if ($insert['result'] && isset($uploadOk)) {
    $temp = explode(".", $file["name"]);
    $newfilename = $insert['lastID'] . '.jpg';
    move_uploaded_file($_FILES["file"]["tmp_name"], $target_dir . $newfilename);
}

echo json_encode(
    array(
        'message' => $message,
        'ok' => $ok
    )
);