<?php

require_once "../functions.php";

$firstName = !empty($_POST['firstName']) ? trim($_POST['firstName']) : null;
$lastName = !empty($_POST['lastName']) ? trim($_POST['lastName']) : null;
$email = !empty($_POST['email']) ? trim($_POST['email']) : null;
$pass = !empty($_POST['password']) ? trim($_POST['password']) : null;
$message = array();
$ok = false;
$registerObj = new Query();

$bind = array(
    1 => array (
        'key' => 'email',
        'value' => $email
    )
);

$checkUser = $registerObj->getQuery("SELECT COUNT(email) AS num FROM user WHERE email = :email", $bind);

if($checkUser['num'] > 0) {
    $message[] = 'Er is al een account registreert met dit emailadres';
} else {
    $ok = true;
}

$passwordHash = password_hash($pass, PASSWORD_BCRYPT, array("cost" => 12));

$bind = array(
    1 => array (
        'key' => 'firstName',
        'value' => $firstName
    ),
    2 => array (
        'key' => 'lastName',
        'value' => $lastName
    ),
    3 => array (
        'key' => 'email',
        'value' => $email
    ),
    4 => array (
        'key' => 'password',
        'value' => $passwordHash
    ),
);

if ($ok) {
    $result = $registerObj->setQuery("INSERT INTO user (firstName, lastName, email, password) VALUES (:firstName, :lastName, :email, :password)", $bind);
    if (!$result) {
        $message[] = "Oops... er ging iets mis, probeer het later opnieuw";
        $ok = false;
    }
}

echo json_encode(
    array(
        'message' => $message,
        'ok' => $ok
    )
);