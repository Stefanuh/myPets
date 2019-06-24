<?php

// Start a session, make a connection and set defines
session_start();
define("root", "https://stefanjovanovic.nl");
require_once "php/connection.php";

// A simple secured query function
function getQuery($statement, $bind = "") {
    global $pdo;
    $sql = $statement;
    $stmt = $pdo->prepare($sql);
    if (!empty($bind)) { foreach($bind as $bind_item) $stmt->bindValue($bind_item['key'], $bind_item['value']); }
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get menu by security level
function getMenu($secured = 0){
    return getQuery("SELECT * FROM menu WHERE secured = ".$secured);
}

// Get the slug from the current page
function getPageSlug() {
    return explode("?", str_replace("/", "", $_SERVER['REQUEST_URI']))[0];
}

// Get all usable data from user
function getUser(){
    if (isset($_SESSION['userID'])) {
        $bind = array(
            1 => array(
                "key" => "userID",
                "value" => $_SESSION['userID']
            )
        );
        return getQuery("SELECT userID, firstName, lastName, email, role FROM user WHERE userID = :userID", $bind)[0];
    }
}

// Get all pets from the user
function getPets(){
    $bind = array(
        1 => array(
            "key" => "userID",
            "value" => $_SESSION['userID']
        )
    );
    return getQuery("SELECT * FROM pet WHERE userID = :userID", $bind);
}

// Get a breed by ID
function getBreedByID($bindValue){
    $bind = array(
        1 => array(
            "key" => "breedID",
            "value" => $bindValue
        )
    );
    return getQuery("SELECT * FROM breed WHERE breedID = :breedID", $bind)[0];
}

// Check if user is premited to enter page
foreach (getQuery("SELECT * FROM menu") as $menu_all) {
    if ($menu_all['slug'] == getPageSlug()) {
        if ($menu_all['secured'] && !getUser()) {
            header("Location: /login");
        }
        if (!$menu_all['secured'] && getUser()) {
            header("Location: /dashboard");
        }
    }
}

function getPetPicture($petID) {

    if (!empty(glob('img/pets/'.$petID.'.*')[0])) {
        return glob('img/pets/'.$petID.'.*')[0];
    } else {
        return "img/pet_default.png";
    }
}
