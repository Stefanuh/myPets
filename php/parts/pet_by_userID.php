<?php
require_once "../../functions.php";
$adminObj = new Admin;
$userID = $_POST['userID'];
foreach ($adminObj->getAllPetsFromUser($userID) as $userPet) : ?>
<option value="<?php echo $userPet['petID'] ?>"><?php echo $userPet['name'] ?></option>
<?php endforeach; ?>