<?php
class Reservation {
  // (A) PROPERTIES
  private $pdo; // PDO object
  private $stmt; // SQL statement
  public $error; // Error message

  // (B) CONSTRUCTOR - CONNECT TO DATABASE
  function __construct() {
    try {
      $this->pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET,
        DB_USER, DB_PASSWORD, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
      );
    } catch (Exception $ex) { die($ex->getMessage()); }
  }

  // (C) DESTRUCTOR - CLOSE DATABASE CONNECTION
  function __destruct() {
    $this->pdo = null;
    $this->stmt = null;
  }

  // (D) SAVE RESERVATION
  function save ($date, $slot, $name, $email, $tel, $notes="") {
    // (D1) CHECKS & RESTRICTIONS
    // @TODO - ADD YOUR OWN RULES & REGULATIONS HERE
    // MAX # OF RESERVATIONS ALLOWED?
    // USER CAN ONLY BOOK X DAYS IN ADVANCE?
    // USER CAN ONLY BOOK A MAX OF X SLOTS WITHIN Y DAYS?

    // (D2) DATABASE ENTRY
    // $res_slot = $this->pdo->query("SELECT res_slot FROM reservations")->fetch();
    // $res_date = $this->pdo->query("SELECT res_date FROM reservations")->fetch();
    // foreach ($res_slot as $r_slot) {
    //     if($r_slot == $slot){
    //        $slot_flag = True;
    //     }
    // }
    // foreach ($res_date as $r_date) {
    //     if($r_date == $date){
    //        $date_flag = True;
    //     }
    // }
    $check_reservation = $this->pdo->query("SELECT res_email FROM reservations WHERE res_slot='$slot' and res_date='$date' LIMIT 1")->fetch();
    if ($check_reservation) {
        // found
        echo "<div class='err'> This session is already booked. Please, book another slot </div>";
        return;
    }

    if($slot >'23:00'){
        echo "<div class='err'> Reservation available from 8am until 11pm </div>";
        return;
    }

    try {
      $this->stmt = $this->pdo->prepare(
        "INSERT INTO `reservations` (`res_date`, `res_slot`, `res_name`, `res_email`, `res_tel`, `res_notes`) VALUES (?,?,?,?,?,?)"
      );
      $this->stmt->execute([$date, $slot, $name, $email, $tel, $notes]);
    } catch (Exception $ex) {
      $this->error = $ex->getMessage();
      return false;
    }

    // (D3) EMAIL
    // @TODO - REMOVE IF YOU WANT TO MANUALLY CALL TO CONFIRM OR SOMETHING
    // OR EMAIL THIS TO A MANAGER OR SOMETHING
    $subject = "Reservation Received";
    $message = "Thank you, we have received your request and will process it shortly.";
    @mail($email, $subject, $message);
    return true;
  }

  // (E) GET RESERVATIONS FOR THE DAY
  function getDay ($day="") {
    // (E1) DEFAULT TO TODAY
    if ($day=="") { $day = date("Y-m-d"); }

    // (E2) GET ENTRIES
    $this->stmt = $this->pdo->prepare(
      "SELECT * FROM `reservations` WHERE `res_date`=?"
    );
    $this->stmt->execute([$day]);
    return $this->stmt->fetchAll(PDO::FETCH_NAMED);
  }
}

// (F) DATABASE SETTINGS
// ! CHANGE THESE TO YOUR OWN !
define('DB_HOST', 'localhost');
define('DB_NAME', '1-reserve');
define('DB_CHARSET', 'utf8');
define('DB_USER', 'root');
define('DB_PASSWORD', '');

// (G) NEW RESERVATION OBJECT
$_RSV = new Reservation();
