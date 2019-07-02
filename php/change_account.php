<?php

require_once "../functions.php";

$firstName = !empty($_POST['firstName']) ? trim($_POST['firstName']) : null;
$lastName = !empty($_POST['lastName']) ? trim($_POST['lastName']) : null;
$phone = !empty($_POST['phone']) ? trim($_POST['phone']) : null;
$email = !empty($_POST['email']) ? trim($_POST['email']) : null;
$passwordAttempt = !empty($_POST['password']) ? trim($_POST['password']) : null;
$newPass = !empty($_POST['newPass']) ? trim($_POST['newPass']) : null;
$message = array();
$ok = false;

$query = new Query();
$bind = array(
    1 => array (
        'key' => 'email',
        'value' => $email
    )
);
$user = $query->getQuery("SELECT userID, email, password FROM user WHERE email = :email", $bind);

if($user){
    $validPassword = password_verify($passwordAttempt, $user['password']);
    if($validPassword){
        if (isset($newPass)) {
            $ok = true;
            $passwordHash = password_hash($newPass, PASSWORD_BCRYPT, array("cost" => 12));
            $bind = array(
                1 => array(
                    'key' => 'firstName',
                    'value' => $firstName,
                ),
                2 => array(
                    'key' => 'lastName',
                    'value' => $lastName,
                ),
                3 => array(
                    'key' => 'phone',
                    'value' => $phone,
                ),
                4 => array(
                    'key' => 'password',
                    'value' => $passwordHash,
                ),
                5 => array(
                    'key' => 'userID',
                    'value' => $userObj->getUserID()
                )
            );
            $query->setQuery("UPDATE user 
SET firstName = :firstName, lastName = :lastName, phone = :phone, password = :password WHERE userID = :userID", $bind);
        } else {
            $ok = true;
            $bind = array(
                1 => array(
                    'key' => 'firstName',
                    'value' => $firstName,
                ),
                2 => array(
                    'key' => 'lastName',
                    'value' => $lastName,
                ),
                3 => array(
                    'key' => 'phone',
                    'value' => $phone,
                ),
                4 => array(
                    'key' => 'userID',
                    'value' => $userObj->getUserID()
                )
            );

            $query->setQuery("UPDATE user 
SET firstName = :firstName, lastName = :lastName, phone = :phone WHERE userID = :userID", $bind);
        }
    } else{
        $message[] = "De ingevoerde wachtwoord was onjuist";
    }
} else {
    $message[] =  "De ingevoerde emailadres klopt niet, gebruik uw huidige emailadres";
}

echo json_encode(
    array(
        'message' => $message,
        'ok' => $ok
    )
);