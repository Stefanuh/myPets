<?php
require_once "../../functions.php";
$admin = new Admin;
$treatments = new Treatment();
$appointmentID = $_POST['appointmentID'];
$pet = new Pet($admin->getAppointmentRequest($appointmentID)['petID']);
?>
<div class="form-group">
    <label for="name">Reden afspraak</label>
    <input type="text" id="appointmentView_name" class="form-control"
           value="<?php echo $admin->getAppointment($appointmentID)['name'] ?>" required>
</div>
<div class="form-group">
    <label for="datepicker">Datum</label>
    <input type="text" id="appointmentView_date" class="form-control datetimepicker"
           value="<?php echo date_format(date_create($admin->getAppointment($appointmentID)
           ['date']),"d-m-Y H:i" ); ?>" readonly="readonly">
</div>
<div class="form-group">
    <label for="name">Extra informatie</label>
    <textarea id="appointmentView_description" class="form-control" aria-label="With textarea"><?php
        echo $admin->getAppointment($appointmentID)['description'] ?></textarea>
</div>
<div class="form-group">
    <label for="appointmentView_treatment">Behandelingen:</label>
    <select class="select_mul" id="appointmentView_treatment" name="appointmentView_treatment[]" multiple="multiple">
        <?php foreach($treatments->getAllTreatments() as $treatment) : ?>
        <option value="<?php echo $treatment['treatmentID'] ?>"><?php echo $treatment['name'] ?></option>
        <?php endforeach; ?>
    </select>
</div>
<div class="appointmentInfo">
    <table class="table">
        <tbody>
        <tr>
            <th scope="row">Eigenaar</th>
            <td><?php echo $admin->getUserByPetID($admin->getAppointment($appointmentID)['petID'])['firstName'].
                    " ".$admin->getUserByPetID($admin->getAppointment($appointmentID)['petID'])['lastName']; ?></td>
        </tr>
        <tr>
            <th scope="row">Huisdier</th>
            <td><?php echo $admin->getUserByPetID($admin->getAppointment($appointmentID)['petID'])['name'] ?></td>
        </tr>
        <tr>
            <th scope="row">Ras</th>
            <td><?php echo $pet->getBreedByID($pet->getBreedID())['name'] ?></td>
        </tr>
        <tr>
            <th scope="row">Geboortedatum</th>
            <td><?php echo date_format(date_create($admin->getUserByPetID($admin->getAppointmentRequest($appointmentID)
                ['petID'])['birth']), "d M Y") ?>
            </td>
        </tr>
        </tbody>
    </table>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-danger removeAppointment" data-appointmentid="<?php echo $appointmentID ?>">
        Verwijder afspraak
    </button>
    <button type="button" id="appointmentViewSubmit" class="btn btn-success">Rond af</button>
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
    $('.select_mul').select2();

    $('#appointmentViewSubmit').click(function() {
        const form = {
            messages: document.getElementById('appointmentViewMessage'),
            appointmentID: $(this).data('id'),
            name: document.getElementById('appointmentView_name'),
            date: document.getElementById('appointmentView_date'),
            description: document.getElementById('appointmentView_description'),
        };

        while (form.messages.firstChild) form.messages.removeChild(form.messages.firstChild);
        let messageList = [];
        let error = false;

        if (form.name.value === "") {
            messageList.push("Voer aub de reden in van de afspraak");
            error = true;
        }
        if (form.date.value === "") {
            messageList.push("Voer aub een datum in");
            error = true;
        }
        if ($("#appointmentView_treatment").val().length === 0) {
            messageList.push("Voer aub minstens één behandeling in");
            error = true;
        }

        if (error) {
            messageList.forEach(function (message) {
                const li = document.createElement('div');
                li.className = 'alert alert-danger';
                li.textContent = message;
                form.messages.appendChild(li);
            });
        } else {
            const url = 'php/parts/appointment_view_update.php';
            const formData = new FormData();
            formData.append('name', form.name.value);
            formData.append('date', form.date.value);
            formData.append('treatment', $("#appointmentView_treatment").val());
            formData.append('appointmentID', <?php echo $appointmentID ?>);
            if (form.description.value !== "") formData.append('description', form.description.value);

            fetch(url, {method: 'POST', body: formData})
            .then(function (response) {
                return response.json();
            })
            .then(function (responseObject) {
                if (responseObject.ok) {
                    location.reload();
                } else {
                    console.log(responseObject);
                    while (form.messages.firstChild) form.messages.removeChild(form.messages.firstChild);
                    responseObject.message.forEach((message) => {
                        const li = document.createElement('div');
                        li.className = 'alert alert-danger';
                        li.textContent = message;
                        form.messages.appendChild(li);
                    });
                    form.message.style.display = "block";
                }
            });
        }
    });

    $('.removeAppointment').click(function() {
        // Extra controle of de afspraak verwijdert moet worden
        const confirmDelete = confirm("Weet u zeker dat deze afspraak verwijdert moet worden?");
        const appointmentID = $(this).data('appointmentid');
        let appointmentBtn = $('#appointmentViewBtn-'+appointmentID);
        if (confirmDelete) {
            $.ajax({
                type: 'POST',
                url: 'php/remove_appointment.php',
                data: { appointmentID: appointmentID },
                success: function() {
                    // Haalt een getal van de counter af
                    const appointment_view_counter = $(".appointment_view_counter");
                    let appointment_view_counter_val = parseInt(appointment_view_counter.text()) - 1;
                    appointment_view_counter.html(appointment_view_counter_val);
                    $('#appointmentView').modal('toggle');
                    appointmentBtn.remove();
                }
            })
        }
    });
</script>