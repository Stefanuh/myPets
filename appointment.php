<?php
    require_once "head.php";
    $admin = new Admin;
    $admin->securePage();
?>

<main id="appointment">
    <div class="container">

        <div class="row">

            <div class="card" style="width: 100%">

                <div class="card-header">
                    <nav id="adminNav" class="nav nav-tabs card-header-tabs" role="tablist">
                        <a class="nav-item nav-link active" id="nav-appointment-tab" data-toggle="tab" href="#nav-appointment" role="tab" aria-controls="nav-appointment" aria-selected="true">Vandaag <span class="badge badge-success"><?php echo count($admin->getTodayAppointments()) ?></span></a>
                        <a class="nav-item nav-link" id="nav-treatment-tab" data-toggle="tab" href="#nav-treatment" role="tab" aria-controls="nav-treatment" aria-selected="false">Aanvragen <span class="badge badge-primary"><?php echo count($admin->getAppointmentRequests()) ?></span></a>
                        <a class="nav-item nav-link" id="nav-allappointments-tab" data-toggle="tab" href="#nav-allappointments" role="tab" aria-controls="nav-allappointments" aria-selected="false">Ingeplande afspraken <span class="badge badge-info"><?php echo count($admin->getAllPlannedAppointments()) ?></span></a>
                        <button type="button" data-toggle="modal" data-target="#appointmentCreate" class="btn btn-outline-primary btn-sm add_appointment">Maak een nieuwe afspraak</button>
                    </nav>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="nav-tabContent">

                        <div class="tab-pane fade show active" id="nav-appointment" role="tabpanel" aria-labelledby="nav-appointment-tab">
                        <?php if ($admin->getTodayAppointments()) : ?>
                            <div class="list-group">
                            <?php foreach ($admin->getTodayAppointments() as $appointmentToday) : ?>
                                <?php $pet = new Pet($appointmentToday['petID']); ?>
                                <button id="appointmentViewBtn-<?php echo $appointmentToday['appointmentID']?>"
                                        data-toggle="modal" data-target="#appointmentView"
                                        data-id="<?php echo $appointmentToday['appointmentID'] ?>"
                                        class="list-group-item list-group-item-action appointmentViewBtn">
                                    <span class="badge badge-success">
                                        <?php echo date_format(date_create($appointmentToday['date']), "H:i"); ?>
                                    </span>
                                    <span class="name"><?php echo $pet->getName() ?></span>
                                    <span class="title"><?php echo $appointmentToday['name'] ?></span>
                                </button>

                            <?php endforeach; ?>
                            </div>
                        <?php else : ?>
                            Er staan voor vandaag geen afspraken meer gepland
                        <?php endif; ?>
                        </div>

                        <div class="tab-pane fade" id="nav-treatment" role="tabpanel"
                             aria-labelledby="nav-treatment-tab">
                            <?php if ($admin->getAppointmentRequests()) : ?>
                                <div class="list-group">
                                    <?php foreach ($admin->getAppointmentRequests() as $appointmentRequest) : ?>
                                    <?php $pet = new Pet($appointmentRequest['petID']); ?>
                                    <button id="appointmentRequestBtn-<?php echo $appointmentRequest['appointmentID']?>"
                                            data-toggle="modal" data-target="#appointmentRequest"
                                            data-id="<?php echo $appointmentRequest['appointmentID'] ?>"
                                            class="list-group-item list-group-item-action appointmentRequestBtn">
                                        <span class="badge badge-primary">
                                        <?php echo date_format(date_create($appointmentRequest['date']),
                                            "d M"); ?>
                                        </span>
                                        <span class="name"><?php echo $pet->getName() ?></span>
                                        <span class="title"><?php echo $appointmentRequest['name'] ?></span>
                                    </button>

                                    <?php endforeach; ?>
                                </div>
                            <?php else : ?>
                                Er zijn geen nieuwe aanvragen
                            <?php endif; ?>
                        </div>

                        <div class="tab-pane fade" id="nav-allappointments" role="tabpanel"
                             aria-labelledby="nav-allappointments-tab">
                            <?php if ($admin->getAllPlannedAppointments()) : ?>
                                <div class="list-group">
                                    <?php foreach ($admin->getAllPlannedAppointments() as $appointmentAll) : ?>
                                        <?php $pet = new Pet($appointmentAll['petID']); ?>
                                        <button id="appointmentRequestBtn-<?php echo $appointmentAll['appointmentID']?>"
                                                data-toggle="modal" data-target="#appointmentRequest"
                                                data-id="<?php echo $appointmentAll['appointmentID'] ?>"
                                                class="list-group-item list-group-item-action appointmentRequestBtn">
                                        <span class="badge badge-primary">
                                        <?php echo date_format(date_create($appointmentAll['date']),
                                            "d M"); ?>
                                        </span>
                                            <span class="name"><?php echo $pet->getName() ?></span>
                                            <span class="title"><?php echo $appointmentAll['name'] ?></span>
                                        </button>

                                    <?php endforeach; ?>
                                </div>
                            <?php else : ?>
                                Er zijn geen nieuwe aanvragen
                            <?php endif; ?>
                        </div>

                    </div>
                </div>

            </div>

        </div>

    </div>
</main>

<div class="modal fade" id="appointmentView" tabindex="-1" role="dialog" aria-labelledby="appointmentView" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Afspraak</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addPetForm" method="POST">
                    <div id="appointmentViewMessage"></div>
                    <div id="appointmentViewData"></div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="appointmentRequest" tabindex="-1" role="dialog" aria-labelledby="appointmentRequest" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Afspraak</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="appointmentRequestForm" method="POST" action="php/update_appointment.php">

                    <div id="appointmentRequestMessage"></div>
                    <div id="appointmentRequestData"></div>

                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="appointmentCreate" tabindex="-1" role="dialog" aria-labelledby="appointmentCreate" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Afspraak aanmaken</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="appointmentCreateForm">
                    <div id="appointmentCreateMessage"></div>

                    <div class="form-group">
                        <label for="user">Klant</label>
                        <select name="user" id="appointmentCreate_user" class="form-control select" required>
                            <?php foreach ($admin->getAllUsers() as $user) : ?>
                                <option value="<?php echo $user['userID'] ?>"><?php echo $user['firstName']." ".$user['lastName'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="pet">Huisdier</label>
                        <select name="pet" id="appointmentCreate_pet" class="form-control select" required>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="name">Reden afspraak</label>
                        <input type="text" class="form-control" id="appointmentCreate_name" required>
                    </div>

                    <div class="form-group">
                        <label for="datepicker">Datum</label>
                        <input type="text" id="appointmentCreate_date" class="form-control datetimepicker" value="" readonly="readonly">
                    </div>

                    <div class="form-group" id="phoneElement">
                        <label for="name">Telefoonnummer</label>
                        <input type="text" id="appointmentCreate_phone" class="form-control" max="10" required>
                    </div>

                    <div class="form-group">
                        <label for="name">Extra informatie</label>
                        <textarea  name="description" id="appointmentCreate_description" class="form-control" aria-label="With textarea"></textarea>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuleren</button>
                        <button type="button" name="submitAppointment" id="appointmentCreateSubmit" class="btn btn-success">Maak afspraak</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>


<?php require_once "footer.php" ?>

<script src="js/jquery-ui-timepicker-addon.js"></script>
<script src="js/jquery-ui-timepicker-nl.js"></script>
<link rel="stylesheet" href="css/jquery-ui-timepicker-addon.css">

<script>

    $('a[data-toggle="tab"]').on('show.bs.tab', function(e) {
        localStorage.setItem('activeTab', $(e.target).attr('href'));
    });
    const activeTab = localStorage.getItem('activeTab');
    if(activeTab) $('#adminNav a[href="' + activeTab + '"]').tab('show');

    $('#appointmentCreate_user').val("");

    // Get all the pets that belong to a selected user
    $('#appointmentCreate_user').change(function() {
        $.ajax({
            type: 'POST',
            url: 'php/parts/pet_by_userID.php',
            data: { userID: $(this).val() },
            success: function(data) {
                $('#appointmentCreate_pet').html(data);
            }
        });

        $.ajax({
            type: 'POST',
            url: 'php/parts/appointment_new_phone.php',
            data: { userID: $(this).val() },
            success: function(data) {
                $('#phoneElement').html(data);
            }
        })
    });

    $('#appointmentCreateSubmit').click(function() {
        const form = {
            messages: document.getElementById('appointmentCreateMessage'),
            submit: document.getElementById('appointmentCreateSubmit'),
            user: document.getElementById('appointmentCreate_user'),
            pet: document.getElementById('appointmentCreate_pet'),
            name: document.getElementById('appointmentCreate_name'),
            date: document.getElementById('appointmentCreate_date'),
            description: document.getElementById('appointmentCreate_description'),
        };

        const phone = document.getElementById('appointmentCreate_phone');

        // Clear all messages first
        while (form.messages.firstChild) form.messages.removeChild(form.messages.firstChild);

        let messageList = [];
        let error = false;

        if (form.user.value === "") {
            messageList.push("Kies aub een gebruiker");
            error = true;
        }

        if (form.pet.value === "") {
            messageList.push("Kies aub een huisdier");
            error = true;
        }

        if (form.name.value === "") {
            messageList.push("Voer aub een reden voor de afspraak in");
            error = true;
        }

        if (form.date.value === "") {
            messageList.push("Voer aub een datum in");
            error = true;
        }

        if (phone.value === "") {
            messageList.push("Voer aub een telefoonnummer in");
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
            const url = '/php/add_appointment_admin.php';
            const formData = new FormData();
            formData.append('userID', form.user.value);
            formData.append('petID', form.pet.value);
            formData.append('name', form.name.value);
            formData.append('date', form.date.value);
            if (phone !== null ) formData.append('phone', phone.value);
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

    // Create the data for a appointment that is today
    $('.appointmentViewBtn').click(function() {
        $.ajax({
            type: 'POST',
            url: 'php/parts/appointment_view.php',
            data: { appointmentID: $(this).data('id') },
            success: function(data)
            {
                $('#appointmentViewData').html(data);
            }
        });
    });

    // Create the data for a appointment request
    $('.appointmentRequestBtn').click(function() {
        $.ajax({
            type: 'POST',
            url: 'php/parts/appointment_request.php',
            data: { appointmentID: $(this).data('id') },
            success: function(data)
            {
                $('#appointmentRequestData').html(data);
            }
        });
    });



</script>