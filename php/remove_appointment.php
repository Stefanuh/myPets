<?php

require_once '../functions.php';
$appointmentID = $_POST['appointmentID'];
$query = new Query();

$bind = array (
    1 => array (
        'key' => 'appointmentID',
        'value' => $appointmentID
    )
);
$query->setQuery("DELETE FROM appointment WHERE appointmentID = :appointmentID", $bind);

