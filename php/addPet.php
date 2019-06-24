<?php

require_once "../functions.php";

if(isset($_POST['addPet'])){

    $name = !empty($_POST['name']) ? trim($_POST['name']) : null;
    $breed = !empty($_POST['breed']) ? trim($_POST['breed']) : null;
    $birth = !empty($_POST['birth']) ? trim($_POST['birth']) : null;

    $sql = "INSERT INTO pet (name, breedID, birth, userID) VALUES (:name, :breedID, :birth, :userID)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':name', $name);
    $stmt->bindValue(':breedID', $breed);
    $stmt->bindValue(':birth', $birth);
    $stmt->bindValue(':userID', getUser()['userID']);
    $result = $stmt->execute();

    if($result){
        header("Location: /dashboard");
        exit();
    }

}