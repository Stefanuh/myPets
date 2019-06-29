<?php
    require_once "../../functions.php";
    $user = new Admin;
?>

<label for="name">Telefoonnummer</label>
<input type="text" id="appointmentCreate_phone" class="form-control" id="phone" max="10" value="<?php echo $user->getUserData($_POST['userID'])['phone']; ?>" required>
