<?php

require_once "../../functions.php";

$query = new Query();
$appointmentID = $_POST['appointmentID'];
$name = !empty($_POST['name']) ? trim($_POST['name']) : null;
$date = date("Y-m-d H:i", strtotime(!empty($_POST['date']) ? trim($_POST['date']) : null));
$description = !empty($_POST['description']) ? trim($_POST['description']) : null;
$treatment = !empty($_POST['treatment']) ? trim($_POST['treatment']) : null;
$treatments = explode(",", $treatment);
$message = array();
$ok = true;

$bindAppointment = array (
    1 => array (
        'key' => 'name',
        'value' => $name
    ),
    2 => array (
        'key' => 'description',
        'value' => $description
    ),
    3 => array (
        'key' => 'date',
        'value' => $date
    ),
    4 => array (
        'key' => 'appointmentID',
        'value' => $appointmentID
    )
);
foreach ($treatments as $treatmentObj) {
    $bindTreatment = array(
        1 => array(
            'key' => 'appointmentID',
            'value' => $appointmentID
        ),
        2 => array(
            'key' => 'treatmentID',
            'value' => $treatmentObj
        )
    );
    $query->setQuery("INSERT INTO appointment_treatment(appointmentID, treatmentID) 
VALUES(:appointmentID, :treatmentID)", $bindTreatment);
}

$query->setQuery("UPDATE appointment SET name = :name, description = :description, date = :date, state = 2 
WHERE appointmentID = :appointmentID", $bindAppointment);

echo json_encode(
    array(
        'message' => $message,
        'ok' => $ok
    )
);