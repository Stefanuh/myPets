<?php
    require_once "../../functions.php";
    $admin = new Admin;
    $appointmentID = $_POST['appointmentID'];
?>
<div id="appointmentCreateMessage"></div>
<div class="form-group">
    <label for="user">Klant</label>
    <select name="user" id="userNewAppointment" class="form-control select" required>
        <?php foreach ($admin->getAllUsers() as $user) : ?>
            <option value="<?php echo $user['userID'] ?>"><?php echo $user['firstName']." ".$user['lastName'] ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>
<div class="form-group">
    <label for="pet">Huisdier</label>
    <select name="pet" id="userPetsNewAppointment" class="form-control select" required>
    </select>
</div>
<div class="form-group">
    <label for="name">Reden afspraak</label>
    <input type="text" name="name" class="form-control" id="name" required>
</div>
<div class="form-group">
    <label for="datepicker">Datum</label>
    <input type="text" name="datepicker" class="form-control datetimepicker" readonly="readonly">
</div>
<div class="form-group">
    <label for="name">Telefoonnummer</label>
    <input type="text" name="phone" class="form-control" id="phone" placeholder="Geef uw telefoonnummer" max="10"
           required>
</div>

<div class="form-group">
    <label for="name">Extra informatie</label>
    <textarea  name="description" id="description" class="form-control" aria-label="With textarea"></textarea>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuleren</button>
    <button type="button" name="submitAppointment" id="submitNewAppointment" class="btn btn-success">Maak afspraak
    </button>
</div>