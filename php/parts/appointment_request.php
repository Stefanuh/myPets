<?php
    require_once "../../functions.php";

    $admin = new Admin;
    $appointmentID = $_POST['appointmentID'];
?>

<input type="hidden" name="appointmentID" value="<?php echo $_POST['appointmentID'] ?>"

<div class="form-group">
    <label for="name">Reden afspraak</label>
    <input type="text" name="name" class="form-control" id="name" value="<?php echo $admin->getAppointmentRequest($appointmentID)['name']; ?>" required>
</div>

<div class="form-group">
    <label for="datepicker">Datum</label>
    <input type="text" name="date" class="form-control datetimepicker"  value="<?php echo $admin->getAppointmentRequest($appointmentID)['date']; ?>" readonly="readonly">
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

<div class="modal-footer">
    <button type="button" class="btn btn-danger removeAppointment" data-appointmentid="<?php echo $appointmentID ?>">Verwijder afspraak</button>
    <button type="submit" name="submitAppointmentRequest" id="submitAppointmentRequest" class="btn btn-success">Plan afspraak in</button>
</div>

<script>
    $(".datetimepicker").datetimepicker({
        controlType: 'select',
        oneLine: true,
        timeFormat: 'HH:mm',
        showButtonPanel: false,
        hourMin: 9,
        hourMax: 19,
        stepMinute: 15,
        minDate: 1,
    });


    $('.removeAppointment').click(function() {
        const confirmDelete = confirm("Weet u zeker dat deze afspraak verwijdert moet worden?");
        const appointmentID = $(this).data('appointmentid');
        let appointmentBtn = $('#appointmentRequestBtn-'+appointmentID);
        if (confirmDelete) {
            $.ajax({
                type: 'POST',
                url: 'php/remove_appointment.php',
                data: { appointmentID: appointmentID },
                success: function() {
                    $('#appointmentRequest').modal('toggle');
                    appointmentBtn.remove();
                }
            })
        }
    });
</script>