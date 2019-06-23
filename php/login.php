<?php

session_start();

require 'connection.php';
if(isset($_POST['login'])){

    $email = !empty($_POST['email']) ? trim($_POST['email']) : null;
    $passwordAttempt = !empty($_POST['password']) ? trim($_POST['password']) : null;

    $sql = "SELECT userID, email, password FROM user WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if($user === false){
        die('Incorrecte gegevens ingevoerd');
    } else{
        $validPassword = password_verify($passwordAttempt, $user['password']);

        if($validPassword){
            $_SESSION['userID'] = $user['userID'];
            $_SESSION['logged_in'] = time();

            header('Location: ../dashboard');
            exit;

        } else{
            die('Incorrecte gegevens ingevoerd');
        }
    }

}