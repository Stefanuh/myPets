<?php

require_once "connection.php";

$name = !empty($_POST['name']) ? trim($_POST['name']) : null;
$breed = !empty($_POST['breed']) ? trim($_POST['breed']) : null;
$birth = (!empty($_POST['birth']) ? trim($_POST['birth']) : null);
$current_date = new DateTime();
$target_dir = "../img/pets/";
$message = array();
$ok = true;

if (empty($name)) {
    $message[] = "Geef een naam op";
    $ok = false;
}

if (empty($breed)) {
    $message[] = "Geef een ras op";
    $ok = false;
}

if (empty($birth)) {
    $message[] = "Geef een geboortedatum op";
    $ok = false;
} else {
    $birth = date("Y-m-d", strtotime(!empty($_POST['birth']) ? trim($_POST['birth']) : null));
    $birthFormat = new DateTime($birth);
    if ($birthFormat > $current_date) {
        $message[] = "Geef een valide geboortedatum op";
        $ok = false;
    }
}

if (!empty($_FILES["file"]['name'])) {
    $uploadOk = true;
    $file = $_FILES["file"];
    $target_file = $target_dir . basename($file["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $check = getimagesize($file["tmp_name"]);
    if ($file["size"][0] > 5000000) {
        $message[] = "Sorry, helaas is de afbeelding te groot";
        $ok = false;
        $uploadOk = false;
    } elseif ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif") {
        $message[] = "Sorry alléén JPG, JPEG, PNG & GIF bestanden zijn toegestaan.";
        $ok = false;
        $uploadOk = false;
    }
}

if ($ok) {
    $sql = "INSERT INTO pet (name, breedID, birth, userID) VALUES (:name, :breedID, :birth, :userID)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':name', $name);
    $stmt->bindValue(':breedID', $breed);
    $stmt->bindValue(':birth', $birth);
    $stmt->bindValue(':userID', $_POST['userID']);
    $result = $stmt->execute();
    $lastID = $pdo->lastInsertId();

    if ($result && isset($uploadOk)) {
        $temp = explode(".", $file["name"]);
        $newfilename = $lastID . '.' . end($temp);
        move_uploaded_file($_FILES["file"]["tmp_name"], $target_dir . $newfilename);
    }
}

echo json_encode(
    array(
        'message' => $message,
        'ok' => $ok
    )
);