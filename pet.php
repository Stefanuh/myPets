<?php
    require_once "head.php";
    $petID = $_GET['id'];

    $pet = new Pet($petID);
    $appointmentObj = new Appointment($petID);
    $treatment = new Treatment($petID);

    if (empty($pet->getPet())) header("Location: /");
?>

<main id="dashboard">
    <div class="container">
        <?php if ($userObj->getRole()) : ?>
            Hello admin
        <?php else: ?>

            <div class="row">
                <div class="card" style="width: 100%">
                    <div class="card-header">
                        <nav class="nav nav-tabs card-header-tabs" id="petNav" role="tablist">
                            <a class="nav-item nav-link active" id="nav-appointment-tab" data-toggle="tab" href="#nav-appointment" role="tab" aria-controls="nav-appointment" aria-selected="true">Afspraken</a>
                            <a class="nav-item nav-link" id="nav-treatment-tab" data-toggle="tab" href="#nav-treatment" role="tab" aria-controls="nav-treatment" aria-selected="false">Behandelingen</a>
                        </nav>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="nav-tabContent">

                            <div class="tab-pane fade show active" id="nav-appointment" role="tabpanel" aria-labelledby="nav-appointment-tab">

                                <div class="card">
                                    <h5 class="card-header">Opkomende afspraken</h5>
                                    <div class="card-body">
                                        <?php if (!empty($appointmentObj->getUpcoming())) : ?>
                                            <div id="accordion" class="mt-2">
                                                <?php foreach ($appointmentObj->getUpcoming() as $appointment) : ?>
                                                    <div class="card">

                                                        <div class="card-header" id="heading<?php echo $appointment['name'] ?>">
                                                            <h5 class="mb-0">
                                                                <button class="btn btn-link" data-toggle="collapse" data-target="#collapse<?php echo $appointment['appointmentID'] ?>" aria-expanded="true" aria-controls="collapse<?php echo $appointment['appointmentID'] ?>">


                                                                    <?php if (date_format(date_create($appointment['date']), "H") == 0) : ?>
                                                                    Aanvraag voor <?php echo date_format(date_create($appointment['date']), "j M")?>
                                                                    <?php else : ?>
                                                                    Ingepland op <?php echo date_format(date_create($appointment['date']), "j M - H:i")?>
                                                                    <?php endif; ?>
                                                                </button>
                                                            </h5>
                                                        </div>

                                                        <div id="collapse<?php echo $appointment['appointmentID'] ?>" class="collapse" aria-labelledby="heading<?php echo $appointment['appointmentID'] ?>" data-parent="#accordion">
                                                            <div class="card-body">
                                                                <h5><?php echo $appointment['name'] ?></h5>
                                                                <?php  if (!empty($appointment['description'])) : ?><p><?php echo $appointment['description']; ?></p><?php endif; ?>
                                                            </div>
                                                        </div>

                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php else : ?>
                                            Er zijn geen opkomende afspraken<br><br>
                                        <?php endif; ?>

                                        <button type="button" data-toggle="modal" data-target="#addAppointment" class="btn btn-primary">Een nieuwe afspraak maken</button>
                                    </div>
                                </div>

                                <div class="card">
                                    <h5 class="card-header">Vorige afspraken</h5>
                                    <div class="card-body">
                                        <?php if (!empty($appointmentObj->getPast())) : ?>
                                        <div id="accordion" class="mt-2">
                                            <?php foreach ($appointmentObj->getPast() as $appointment) : ?>
                                            <div class="card">

                                                <div class="card-header" id="heading<?php echo $appointment['name'] ?>">
                                                    <h5 class="mb-0">
                                                        <button class="btn btn-link" data-toggle="collapse" data-target="#collapse<?php echo $appointment['appointmentID'] ?>" aria-expanded="true" aria-controls="collapse<?php echo $appointment['appointmentID'] ?>">
                                                            <?php echo date_format(date_create($appointment['date']), "j M Y") ?>
                                                        </button>
                                                    </h5>
                                                </div>

                                                <div id="collapse<?php echo $appointment['appointmentID'] ?>" class="collapse" aria-labelledby="heading<?php echo $appointment['appointmentID'] ?>" data-parent="#accordion">
                                                    <div class="card-body">
                                                        <h5><?php echo $appointment['name'] ?></h5>
                                                        <?php  if (!empty($appointment['description'])) : ?><p><?php echo $appointment['description']; ?></p><?php endif; ?>
                                                    </div>
                                                </div>

                                            </div>
                                            <?php endforeach; ?>
                                        </div>
                                        <?php else : ?>
                                            Er zijn nog geen afspraken afgerond
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="nav-treatment" role="tabpanel" aria-labelledby="nav-treatment-tab">
                                <?php if($treatment->getAllData()) : ?>
                                    <ul class="list-group">
                                    <?php foreach ($treatment->getAllData() as $treatment) : ?>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <?php echo $treatment['name'] ?>
                                                <span class="badge badge-secondary badge-pill"><?php echo date_format(date_create($treatment['date']), "j M Y") ?></span>
                                            </li>
                                    <?php endforeach; ?>
                                    </ul>
                                <?php else : ?>
                                    Er zijn nog geen behandelingen uitgevoerd
                                <?php endif; ?>
                            </div>
                    </div>
                        </div>
                    </div>

                </div>
            </div>
        <?php endif; ?>
    </div>
</main>

<div class="modal fade" id="addAppointment" tabindex="-1" role="dialog" aria-labelledby="addAppointment" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Afspraak maken</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addAppointmentForm">
                    <div id="message">
                    </div>
                    <div class="form-group">
                        <label for="name">Reden afspraak</label>
                        <input type="text" name="name" class="form-control" id="name" placeholder="Geef een reden voor de afspraak" required>
                    </div>

                    <div class="form-group">
                        <label for="datepicker">Voorkeursdatum</label>
                        <input type="text" name="date" id="date" class="form-control datepicker-future" placeholder="Geef een voorkeursdag voor de afspraak" readonly="readonly">
                    </div>

                    <?php if (!$userObj->getPhone()) : ?>
                    <div class="form-group">
                        <label for="name">Telefoonnummer</label>
                        <input type="text" name="phone" class="form-control" id="phone" placeholder="Geef uw telefoonnummer" max="10" required>
                    </div>
                    <?php endif; ?>

                    <div class="form-group">
                        <label for="name">Extra informatie (optioneel)</label>
                        <textarea  name="description" id="description" class="form-control" aria-label="With textarea" placeholder="Geef wat extra informatie over de reden van uw afspraak"></textarea>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuleren</button>
                        <button type="button" name="submitAppointment" id="submitAppointment" class="btn btn-success">Maak afspraak</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once "footer.php" ?>

<script>

    $('a[data-toggle="tab"]').on('show.bs.tab', function(e) {
        localStorage.setItem('activeTab', $(e.target).attr('href'));
    });
    const activeTab = localStorage.getItem('activeTab');
    if(activeTab) $('#petNav a[href="' + activeTab + '"]').tab('show');

    const form = {
        messages: document.getElementById('message'),
        submit: document.getElementById('submitAppointment'),
        name: document.getElementById('name'),
        date: document.getElementById('date'),
        description: document.getElementById('description'),
    };

    const phone = document.getElementById('phone');

    form.submit.addEventListener('click', function (e) {
        e.preventDefault();
        // Clear all messages first
        while (form.messages.firstChild) form.messages.removeChild(form.messages.firstChild);

        let messageList = [];
        let error = false;

        // Check if name is empty
        if (form.name.value === "") {
            messageList.push("Voer aub de reden in van uw afspraak");
            error = true;
        }

        if (form.date.value === "") {
            messageList.push("Voer aub een voorkeursdatum in");
            error = true;
        }

        if (phone !== null)  {
            if (phone.value === "") {
                messageList.push("Voer aub uw telefoonnummer in");
                error = true;
            }
        }

        if (error) {
            messageList.forEach(function (message) {
                const li = document.createElement('div');
                li.className = 'alert alert-danger';
                li.textContent = message;
                form.messages.appendChild(li);
            });
        } else {
            const url = '/php/add_appointment.php';
            const formData = new FormData();
            formData.append('userID', <?php echo $userObj->getUserID() ?>);
            formData.append('name', form.name.value);
            formData.append('date', form.date.value);
            formData.append('petID', <?php echo $petID ?>);
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
</script>