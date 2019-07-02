<?php

session_start();

// ! --- Pas de URL hier aan als de website van verhuisd --- !
define("root", "https://stefanjovanovic.nl");

// Een class voor de connectie met de database
class DB {
    private $connection;
    private $host;
    private $username;
    private $password;
    private $database;
    private $dns;
    private $options;

    public function __construct() {
        // ! --- Pas de onderstaande gegevens aan als de database server wordt geweizigd --- !
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

    // Een functie die gebruikt wordt in de Query class
    public function getConnection(){
        return $this->connection;
    }
}

// Een class waar de set en get querie functies aangemaakt worden
class Query {
    private $db;
    private $connection;
    private $statement;
    private $bind;

    public function __construct() {
        $this->db = new DB;
        $this->connection = $this->db->getConnection();
    }

    // Een get query die informatie uit de database haalt
    // In de statement variable geef je de select statement aan
    // In de bind array geef je alle variabelen aan die gebind moeten worden
    // de fetchAll optie zorgt voor meerdere resultaten als deze aanstaat
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

    // Een set query die data in de database zet
    // In de statement variable geef je de statement aan (update, delete)
    // In de bind array geef je alle variablen aan die gebind moeten worden
    // De return is een array met de resultaat (true, false) en de laatst ingevoerde ID
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

// Een class waar alle informatie van een normale gebruiker opgehaald kan worden
class User {
    private $userID;
    private $firstName;
    private $lastName;
    private $role;
    private $userData;
    private $query;
    private $bind;
    private $phone;
    private $email;

    public function __construct() {
        if (isset($_SESSION['userID'])) $this->userID = $_SESSION['userID'];
        $this->query = new Query;
        $this->bind = array(
            1 => array(
                "key" => "userID",
                "value" => $this->userID
            )
        );
        $this->userData = $this->query->getQuery("SELECT firstName, lastName, email, phone, role FROM user WHERE userID = :userID", $this->bind);
        $this->firstName = $this->userData['firstName'];
        $this->lastName = $this->userData['lastName'];
        $this->email = $this->userData['email'];
        $this->role = $this->userData['role'];
        $this->phone = $this->userData['phone'];
    }

    // Dit beveiligd pagina's waar alleen normale gebruikers bij kunnen (geen admins - zie Admin class onder in)
    public function regularOnly(){
        if ($this->getRole()) header("Location: /");
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

    // Een functie die de volledige naam van de gebruiker terug geeft
    public function getFullName() {
        return $this->firstName." ".$this->lastName;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getRole() {
        return $this->role;
    }
    public function getPhone() {
        return $this->phone;
    }

}

// Een class die de menu en slug klaarzet voor elke pagina
class Page {
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
        $this->secured = 0;
    }

    // Een functie die alle menu items uit de database haalt die bij de rol van de gebruiker horen
    // Als er geen gebruiker bestaat worden de menu items opgehaald die niet beveiligd zijn
    public function getMenu() {
        if (!empty($this->user->getUserID())) {
            if ($this->user->getRole()) $this->secured = 2;
            else $this->secured = 1;
        }

        $bind = array(
            1 => array (
                'key' => 'secured',
                'value' => $this->secured
            )
        );
        return $this->query->getQuery("SELECT * FROM menu WHERE secured = :secured", $bind, 1);
    }

    // De slug van de pagina wordt opgehaald, die het gedeelte na de / en voor de ?
    public function getPageSlug() {
        return $this->pageSlug;
    }
}

// Een class waar alle informatie van een dier staat
class Pet {
    private $petID;
    private $query;
    private $user;
    private $name;
    private $breedID;
    private $birth;
    private $userID;

    public function __construct($petID = 0) {
        $this->petID = $petID;
        $this->query = new Query;
        $this->user = new User;
        $bind = array(
            1 => array(
                "key" => "petID",
                "value" => $petID
            ),
        );
        $petData = $this->query->getQuery("SELECT * FROM pet WHERE petID = :petID", $bind);
        $this->name = $petData['name'];
        $this->breedID = $petData['breedID'];
        $this->birth = $petData['birth'];
        $this->userID = $petData['userID'];
    }

    public function getName() {
        return $this->name;
    }

    public function getBreedID() {
        return $this->breedID;
    }

    public function getBirth() {
        return $this->birth;
    }

    public function getUserID() {
        return $this->userID;
    }

    // Een functie die de naam van een ras vindt gekoppeld aan een breedID
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

    // Geeft alle soorten huisdieren teru
    function getBreedTypes() {
        return( $this->query->getQuery("SELECT * FROM breed_type", 0, 1));
    }

    // Deze functie zoekt op een foto van de huisdier
    // Als deze niet bestaat geeft de functie een default foto van de huisdier soort terug
    function getPicture($petID, $breedID) {
        if (!empty(glob('img/pets/'.$petID.'.*')[0])) {
            return glob('img/pets/'.$petID.'.*')[0];
        } else {
            return "img/pet_default_".$this->getBreedType($breedID)['breedTypeID'].".png";

        }
    }

    // Zoekt alle gegevens van de huisdier die bij een gebruiker hoort
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

    // Geeft alle huisdieren van een gebruiker terug
    public function getUserPets(){
        $bind = array(
            1 => array(
                "key" => "userID",
                "value" => $this->user->getUserID()
            )
        );
        return $this->query->getQuery("SELECT * FROM pet WHERE userID = :userID", $bind, $fetchAll = true);
    }

    // Geeft alle rassen terug
    function getBreeds() {
        return $this->query->getQuery("SELECT * FROM breed", $bind=array(), $fetchAll = true);
    }

    // Geeft de naam vaan ras terug die gekoppeld staat een een breedID
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

// Een class waar alle gegevens van een afspraak in staat
// State 0 = aanvraag
// State 1 = ingepland
// State 2 = afgerond

class Appointment {
    private $petID;
    private $query;

    public function __construct($petID = 0) {
        $this->petID = $petID;
        $this->query = new Query;
    }

    // Geeft alle opkomende afspraken terug
    public function getUpcoming() {
        $bind = array(
            1 => array(
                "key" => "petID",
                "value" => $this->petID
            ),
        );
        return $this->query->getQuery("SELECT * FROM appointment a WHERE (a.state = 0 OR a.state = 1) AND petID = :petID", $bind, $fetchAll = true);
    }

    // Geeft alle afspraken terug die zijn afgerond
    public function getPast(){
        $bind = array(
            1 => array(
                "key" => "petID",
                "value" => $this->petID
            ),
        );
        return $this->query->getQuery("SELECT * FROM appointment a WHERE a.state = 2 AND petID = :petID", $bind, $fetchAll = true);
    }

    // Geeft alle afspraken terug los van hun status
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

// Een class waar alle behandelingen in staan
class Treatment {
    private $petID;
    private $query;
    private $user;

    public function __construct($petID = 0) {
        $this->petID = $petID;
        $this->query = new Query;
        $this->user = new User;
    }

    // Maakt gebruik van een koppeltabel om alle behandelingen bij een afgeronde afspraak op te halen - gekoppeld aan een huisdier
    public function getAllData(){
        $bind = array(
            1 => array(
                "key" => "petID",
                "value" => $this->petID
            )
        );
        return $this->query->getQuery("SELECT * FROM appointment a
                                                INNER JOIN appointment_treatment ap ON a.appointmentID = ap.appointmentID
                                                INNER JOIN treatment t on ap.treatmentID = t.treatmentID
                                                WHERE a.state = 2 AND petID = :petID", $bind, $fetchAll = true);
    }

    // Geeft alle behandelingen terug
    public function getAllTreatments(){
        return $this->query->getQuery("SELECT * FROM treatment", 0, 1);
    }


}


// Een class voor de admin gedeelte van de applicatie
class Admin {
    private $query;
    private $user;

    public function __construct() {
        $this->query = new Query;
        $this->user = new User;
    }

    // Deze functie zorgt er voor dat alle gebruikers die geen admin zijn terug worden gestuurd
    public function securePage(){
        if (!$this->user->getRole()) {
            header("Location: /");
            exit;
        }
    }


    // Geeft alle data van een afspraak terug gebonden aan de id
    public function getAppointment($appointmentID) {
        $bind = array(
            1 => array (
                'key' => 'appointmentID',
                'value' => $appointmentID
            )
        );
        return $this->query->getQuery("SELECT * FROM appointment WHERE appointmentID = :appointmentID", $bind, 0);
    }

    // Geeft alle afspraken van vandaag terug
    public function getTodayAppointments(){
        return $this->query->getQuery("SELECT * FROM appointment WHERE state = 1 AND DATE(date) = CURDATE()", 0, 1);
    }


    // Geeft alle afspraak verzoeken terug
    public function getAppointmentRequests() {
        return $this->query->getQuery("SELECT * FROM appointment WHERE state = 0", $bind= array(), $fetchAll = true);

    }

    // Geeft alle data van een afspraak verzoek terug
    public function getAppointmentRequest($appointmentID) {
        $bind = array(
            1 => array (
                'key' => 'appointmentID',
                'value' => $appointmentID
            )
        );
        return $this->query->getQuery("SELECT * FROM appointment WHERE appointmentID = :appointmentID", $bind, 0);
    }


    // Geeft alle data terug van goedgekeurde afspraken die niet vandaag plaatsvinden
    public function getAllPlannedAppointments(){
        return $this->query->getQuery("SELECT * FROM appointment WHERE state = 1 AND DATE(date) > CURDATE()",0, 1);
    }

    // Geeft alle gebruikers terug die geen admin zijn
    public function getAllUsers(){
        return $this->query->getQuery("SELECT * FROM user WHERE role = 0", 0, 1);
    }

    // Geeft alle data van een gebruiker terug
    public function getUserData($userID) {
        $bind = array(
            1 => array (
                'key' => 'userID',
                'value' => $userID
            )
        );
        return $this->query->getQuery("SELECT * FROM user WHERE userID = :userID", $bind, 0);
    }


    // Geeft alle data van een gebruiker terug die gekoppeld is aan een huisdier
    public function getUserByPetID($petID) {
        $bind = array(
            1 => array (
                'key' => 'petID',
                'value' => $petID
            )
        );
        return $this->query->getQuery("SELECT * FROM pet p
                                                INNER JOIN user u ON p.userID = u.userID
                                                WHERE p.petID = :petID", $bind, 0);
    }

    // Geeft alle huisdieren van een gebruiker terug
    public function getAllPetsFromUser($userID){
        $bind = array(
            1 => array (
                'key' => 'userID',
                'value' => $userID
            )
        );
        return $this->query->getQuery("SELECT * FROM pet WHERE userID = :userID", $bind, 1);
    }

}

// Een set aan standaard objecten voor herbruik
$queryObj = new Query();
$pageObj = new Page();
$userObj = new User();

// Zorgt er voor dat een gebruiker alleen bij kan waar hij bij mag
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