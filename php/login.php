<?php

require_once "../functions.php";

$email = !empty($_POST['email']) ? trim($_POST['email']) : null;
$passwordAttempt = !empty($_POST['password']) ? trim($_POST['password']) : null;
$message = array();
$ok = false;
$loginAttempt = new Query;
$bind = array(
    1 => array (
        'key' => 'email',
        'value' => $email
    )
);
$user = $loginAttempt->getQuery("SELECT userID, email, password FROM user WHERE email = :email", $bind);

if($user){
    $validPassword = password_verify($passwordAttempt, $user['password']);
    if($validPassword){
        $ok = true;
        $_SESSION['userID'] = $user['userID'];
    } else{
        $message[] = "De ingevoerde gegevens waren onjuist";
    }
} else {
    $message[] =  "De ingevoerde gegevens waren onjuist";
}

echo json_encode(
    array(
        'message' => $message,
        'ok' => $ok
    )
);