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

function getUser(){
    if (isset($_SESSION['userID'])) {
        $bind = array( 1 => array(
            "key" => "userID",
            "value" => $_SESSION['userID']
        )
        );
        return getQuery("SELECT * FROM user WHERE userID = :userID", $bind)[0];
    }
}

function getPageSlug() {
    return basename(preg_replace("/(.+)\.php$/", "$1", basename(__FILE__)));
}

print_r($_SERVER['REQUEST_URI']);