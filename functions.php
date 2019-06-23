<?php

session_start();

define("root", "https://stefanjovanovic.nl");
require_once "php/connection.php";

$pages = array(
    "login" => array (
        "title" => "Login",
        "slug" => "login",
        "secure" => 0,
    ),
    "register" => array (
        "title" => "Account aanmaken",
        "slug" => "register",
        "secure" => 0,
    ),
    "dashboard" => array (
        "title" => "Overzicht",
        "slug" => "dashboard",
        "secure" => 1,
    ),
    "account" => array (
        "title" => "Mijn account",
        "slug" => "account",
        "secure" => 1,
        "sub" => array(
            "profile" => array (
                "title" => "Profiel",
                "slug" => "profile",
                "secure" => 1,
            ),
            "logout" => array (
                "title" => "Uitloggen",
                "slug" => "php/logout",
                "secure" => 1,
            ),
        )
    )

);

function getQuery($statement, $bind = "") {
    global $pdo;

    $sql = $statement;
    $stmt = $pdo->prepare($sql);
    if (!empty($bind)) {
        foreach($bind as $bind_item) $stmt->bindValue($bind_item['key'], $bind_item['value']);
    }
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

if (isset($_SESSION['userID'])) {
    $bind = array( 1 => array(
            "key" => "userID",
            "value" => $_SESSION['userID']
        )
    );
    $user = getQuery("SELECT * FROM user WHERE userID = :userID", $bind)[0];
}

