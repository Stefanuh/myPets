<?php

session_start();
define("root", "https://stefanjovanovic.nl");

class DB {
    private $connection;
    private $host;
    private $username;
    private $password;
    private $database;
    private $dns;
    private $options;

    public function __construct() {
        $this->host = 'localhost';
        $this->username = 'stefanjovanovic_nl_mypets';
        $this->password = 'kg7Ry7P3jdWM';
        $this->database = $this->username;
        $this->dns = "mysql:host=".$this->host.";dbname=".$this->database.";charset=utf8mb4";
        $this->options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        try {
            $this->connection = new PDO($this->dns, $this->username, $this->password, $this->options);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public function getConnection(){
        return $this->connection;
    }
}

class Query {
    private $db;
    private $connection;
    private $statement;
    private $bind;

    public function __construct() {
        $this->db = new DB;
        $this->connection = $this->db->getConnection();
    }

    public function getQuery($statement, $bind = array(), $fetchAll = false) {
        $this->statement = $statement;
        $this->bind = $bind;
        $stmt = $this->connection->prepare($statement);
        if (!empty($bind)) {
            foreach($bind as $bind_item) $stmt->bindValue($bind_item['key'], $bind_item['value']);
        }
        $stmt->execute();
        if ($fetchAll) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

    }

    public function setQuery($statement, $bind) {
        $this->statement = $statement;
        $this->bind = $bind;
        $stmt = $this->connection->prepare($statement);
        if (!empty($bind)) {
            foreach($bind as $bind_item) $stmt->bindValue($bind_item['key'], $bind_item['value']);
        }
        $result = $stmt->execute();
        $lastID = $this->connection->lastInsertId();
        $response = array(
            "result" => $result,
            "lastID" => $lastID
        );
        return $response;
    }
}

class Page
{
    private $query;
    private $secured;
    private $fetchAll;
    private $pageSlug;
    private $user;

    public function __construct() {
        $this->query = new Query;
        $this->fetchAll = true;
        $this->pageSlug = explode("?", str_replace("/", "", $_SERVER['REQUEST_URI']))[0];
        $this->user = new User;
    }

    public function getMenu($secured = 0) {
        $this->secured = $secured;

        $bind = array(
            1 => array(
                'key' => 'secured',
                'value' => $this->secured
            )
        );
        return $this->query->getQuery("SELECT * FROM menu WHERE secured = :secured", $bind, $this->fetchAll);
    }

    public function getPageSlug() {
        return $this->pageSlug;
    }
}

class User {
    private $userID;
    private $firstName;
    private $lastName;
    private $role;
    private $userData;
    private $query;
    private $bind;

    public function __construct() {
        if (isset($_SESSION['userID'])) {
            $this->userID = $_SESSION['userID'];
        }
        $this->query = new Query;
        $this->bind = array(
            1 => array(
                "key" => "userID",
                "value" => $this->userID
            )
        );
        $this->userData = $this->query->getQuery("SELECT firstName, lastName, email, role FROM user WHERE userID = :userID", $this->bind);
        $this->firstName = $this->userData['firstName'];
        $this->lastName = $this->userData['lastName'];
        $this->role = $this->userData['role'];
    }

    public function getUserID() {
        return $this->userID;
    }

    public function getFirstName() {
        return $this->firstName;
    }

    public function getLastName() {
        return $this->lastName;
    }

    public function getFullName() {
        return $this->firstName." ".$this->lastName;
    }

    public function getRole() {
        return $this->role;
    }

}

class Pet {
    private $petID;
    private $query;
    private $user;

    public function __construct($petID = 0) {
        $this->petID = $petID;
        $this->query = new Query;
        $this->user = new User;
    }

    function getBreedType($breedID) {
        $bind = array(
            1 => array(
                "key" => "breedID",
                "value" => $breedID
            ),
        );
        return( $this->query->getQuery("SELECT bt.breedTypeID, b.breedID FROM `breed_type` bt
                                                INNER JOIN breed b ON bt.breedTypeID = b.breedTypeID
                                                WHERE breedID = :breedID", $bind));
    }

    function getPicture($petID, $breedID) {
        if (!empty(glob('img/pets/'.$petID.'.*')[0])) {
            return glob('img/pets/'.$petID.'.*')[0];
        } else {
            return "img/pet_default_".$this->getBreedType($breedID)['breedTypeID'].".png";

        }
    }

    public function getPet(){
        $bind = array(
            1 => array(
                "key" => "petID",
                "value" => $this->petID
            ),
            2 => array(
                "key" => "userID",
                "value" => $this->user->getUserID()
            )
        );
        return $this->query->getQuery("SELECT * FROM pet WHERE petID = :petID AND userID = :userID", $bind);
    }

    public function getUserPets(){
        $bind = array(
            1 => array(
                "key" => "userID",
                "value" => $this->user->getUserID()
            )
        );
        return $this->query->getQuery("SELECT * FROM pet WHERE userID = :userID", $bind, $fetchAll = true);
    }

    function getBreeds() {
        return $this->query->getQuery("SELECT * FROM breed", $bind=array(), $fetchAll = true);
    }

    function getBreedByID($breedID){
        $bind = array(
            1 => array(
                "key" => "breedID",
                "value" => $breedID
            )
        );
        return $this->query->getQuery("SELECT * FROM breed WHERE breedID = :breedID", $bind);
    }
}

class Appointment {
    private $petID;
    private $query;

    public function __construct($petID = 0) {
        $this->petID = $petID;
        $this->query = new Query;
    }


    public function getAll($state = 0) {
        $bind = array(
            1 => array(
                "key" => "petID",
                "value" => $this->petID
            ),
            2 => array (
                "key" => "state",
                "value" => $state
            )
        );
        return $this->query->getQuery("SELECT * FROM appointment a WHERE a.state = :state AND petID = :petID", $bind, $fetchAll = true);
    }

    public function getPast(){
        $bind = array(
            1 => array(
                "key" => "petID",
                "value" => $this->petID
            )
        );
        return $this->query->getQuery("SELECT * FROM appointment a
                                                INNER JOIN appointment_treatment ap ON a.appointmentID = ap.appointmentID
                                                INNER JOIN treatment t on ap.treatmentID = t.treatmentID
                                                WHERE a.state = 1 AND petID = :petID
                                                GROUP BY a.appointmentID", $bind, $fetchAll = true);
    }

    public function getAllAppointments(){
        $bind = array(
            1 => array(
                "key" => "petID",
                "value" => $this->petID
            )
        );
        return $this->query->getQuery("SELECT * FROM appointment WHERE petID = :petID", $bind, $fetchAll = true);
    }

}

class Treatment {
    private $petID;
    private $query;
    private $user;

    public function __construct($petID = 0) {
        $this->petID = $petID;
        $this->query = new Query;
        $this->user = new User;
    }

    public function getAll(){
        $bind = array(
            1 => array(
                "key" => "petID",
                "value" => $this->petID
            )
        );
        return $this->query->getQuery("SELECT * FROM appointment a
                                                INNER JOIN appointment_treatment ap ON a.appointmentID = ap.appointmentID
                                                INNER JOIN treatment t on ap.treatmentID = t.treatmentID
                                                WHERE a.state = 1 AND petID = :petID", $bind, $fetchAll = true);
    }


}

$queryObj = new Query();
$pageObj = new Page();
$userObj = new User();

// Check if user is permitted to be here
foreach ($queryObj->getQuery("SELECT * FROM menu", $bind=array(), $fetchAll = true) as $menu_all) {
    if ($menu_all['slug'] == $pageObj->getPageSlug()) {
        if ($menu_all['secured'] && !$userObj->getUserID()) {
            header("Location: /login");
        }
        if (!$menu_all['secured'] && $userObj->getUserID()) {
            header("Location: /dashboard");
        }
    }
}