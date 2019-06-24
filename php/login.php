<?php

session_start();
require_once "connection.php";

$email = !empty($_POST['email']) ? trim($_POST['email']) : null;
$passwordAttempt = !empty($_POST['password']) ? trim($_POST['password']) : null;
$message = array();
$valid = true;
$ok = false;
$sql = "SELECT userID, email, password FROM user WHERE email = :email";


$stmt = $pdo->prepare($sql);
$stmt->bindValue(':email', $email);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (empty($email)) {
    $message[] = "Voer een emailadres in";
    $valid = false;
}
if (empty($passwordAttempt)) {
    $message[] = "Voer een wachtwoord in";
    $valid = false;
}

if($user === false && $valid){
    $message[] =  "De ingevoerde gegevens waren onjuist";
} elseif ($valid){
    $validPassword = password_verify($passwordAttempt, $user['password']);
    if($validPassword){
        $ok = true;
        $_SESSION['userID'] = $user['userID'];
    } else{
        $message[] = "De ingevoerde gegevens waren onjuist";
    }
}

echo json_encode(
    array(
        'message' => $message,
        'ok' => $ok
    )
);
