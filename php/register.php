<?php

require_once "connection.php";

if(isset($_POST['createAccount'])){

    $firstName = !empty($_POST['firstName']) ? trim($_POST['firstName']) : null;
    $lastName = !empty($_POST['lastName']) ? trim($_POST['lastName']) : null;
    $email = !empty($_POST['email']) ? trim($_POST['email']) : null;
    $pass = !empty($_POST['password']) ? trim($_POST['password']) : null;
    $passCheck = !empty($_POST['passwordCheck']) ? trim($_POST['passwordCheck']) : null;

    if ($pass !== $passCheck) die("Wachtwoorden komen niet met elkaar overeen");

    $sql = "SELECT COUNT(email) AS num FROM user WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':email', $email);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if($row['num'] > 0) die('U heeft al een account');

    $passwordHash = password_hash($pass, PASSWORD_BCRYPT, array("cost" => 12));

    $sql = "INSERT INTO user (firstName, lastName, email, password) VALUES (:firstName, :lastName, :email, :password)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':firstName', $firstName);
    $stmt->bindValue(':lastName', $lastName);
    $stmt->bindValue(':email', $email);
    $stmt->bindValue(':password', $passwordHash);
    $result = $stmt->execute();

    if($result){
        echo 'Bedankt voor het registreren';
    }

}