<?php
    require_once "../../functions.php";

    $admin = new Admin;
    $appointmentID = $_POST['appointmentID'];

?>

<script src="../../js/jquery-ui-timepicker-addon.js"></script>
<script src="../../js/jquery-ui-timepicker-nl.js"></script>

<input type="hidden" name="appointmentID" value="<?php echo $_POST['appointmentID'] ?>"

<div class="form-group">
    <label for="name">Reden afspraak</label>
    <input type="text" name="name" class="form-control" id="name" value="<?php echo $admin->getAppointmentRequest($appointmentID)['name']; ?>" required>
</div>

<div class="form-group">
    <label for="datepicker">Datum</label>
    <input type="text" name="date" class="form-control datetimepicker"  value="<?php echo $admin->getAppointmentRequest($appointmentID)['date']; ?>" required>
</div>

<div class="form-group">
    <label for="name">Extra informatie</label>
    <textarea name="description" id="description" class="form-control" aria-label="With textarea"> <?php echo $admin->getAppointmentRequest($appointmentID)['description']; ?></textarea>
</div>

<div class="appointmentInfo">
    <table class="table">
        <tbody>
        <tr>
            <th scope="row">Eigenaar</th>
            <td><?php echo $admin->getUserByPetID($admin->getAppointmentRequest($appointmentID)['petID'])['firstName'].
                    " ".$admin->getUserByPetID($admin->getAppointmentRequest($appointmentID)['petID'])['lastName'] ?></td>
        </tr>
        <tr>
            <th scope="row">Telefoon</th>
            <td><?php echo $admin->getUserByPetID($admin->getAppointmentRequest($appointmentID)['petID'])['phone'] ?></td>
        </tr>
        <tr>
            <th scope="row">Huisdier</th>
            <td><?php echo $admin->getUserByPetID($admin->getAppointmentRequest($appointmentID)['petID'])['name'] ?></td>
        </tr>
        <tr>
            <th scope="row">Geboortedatum</th>
            <td><?php echo date_format(date_create($admin->getUserByPetID($admin->getAppointmentRequest($appointmentID)['petID'])['birth']), "d M Y") ?></td>

        </tr>
        </tbody>
    </table>
</div>