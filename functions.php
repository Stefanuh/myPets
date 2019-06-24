<?php

session_start();

define("root", "https://stefanjovanovic.nl");
require_once "php/connection.php";

function getQuery($statement, $bind = "") {
    global $pdo;
    $sql = $statement;
    $stmt = $pdo->prepare($sql);
    if (!empty($bind)) { foreach($bind as $bind_item) $stmt->bindValue($bind_item['key'], $bind_item['value']); }
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getMenu($secured = 0){
    return getQuery("SELECT * FROM menu WHERE secured = ".$secured);
}

function getPageSlug() {
    return explode("?", str_replace("/", "", $_SERVER['REQUEST_URI']))[0];
}

function getUser(){
    if (isset($_SESSION['userID'])) {
        $bind = array(
            1 => array(
                "key" => "userID",
                "value" => $_SESSION['userID']
            )
        );
        return getQuery("SELECT firstName, lastName, email, role FROM user WHERE userID = :userID", $bind)[0];
    }
}

function getPets(){
    $bind = array(
        1 => array(
            "key" => "userID",
            "value" => $_SESSION['userID']
        )
    );
    return getQuery("SELECT * FROM pet WHERE userID = :userID", $bind);
}

function getBreedByID($bindValue){
    $bind = array(
        1 => array(
            "key" => "breedID",
            "value" => $bindValue
        )
    );

    return getQuery("SELECT * FROM breed", $bind)[0];
}
