<?php

function search($text){

    $dsn = "mysql:host=localhost;dbname=stefanjovanovic_nl_mypets;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    try {
        $pdo = new PDO($dsn, 'stefanjovanovic_nl_mypets', 'kg7Ry7P3jdWM', $options);
    } catch (PDOException $e) {
        throw new PDOException($e->getMessage(), (int)$e->getCode());
    }


    $text = htmlspecialchars($text);
    $get_name = $pdo->prepare("SELECT * FROM breed WHERE name LIKE concat('%', :name, '%')");
    $get_name -> execute(array('name' => $text));
    while($names = $get_name->fetch(PDO::FETCH_ASSOC)){
        echo "<option value=". $names['breedID'] .">". $names['name'] ."</option>";
    }
}
search($_GET['txt']);