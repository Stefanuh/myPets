
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
                    <nav class="nav nav-tabs card-header-tabs" role="tablist">
                        <a class="nav-item nav-link active" id="nav-appointment-tab" data-toggle="tab" href="#nav-appointment" role="tab" aria-controls="nav-appointment" aria-selected="true">Vandaag <span class="badge badge-success"><?php echo count($admin->getTodayAppointments()) ?></span></a>
                        <a class="nav-item nav-link" id="nav-treatment-tab" data-toggle="tab" href="#nav-treatment" role="tab" aria-controls="nav-treatment" aria-selected="false">Aanvragen <span class="badge badge-primary"><?php echo count($admin->getAppointmentRequests()) ?></span></a>
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
                                <button data-toggle="modal" data-target="#appointmentView"
                                        class="list-group-item list-group-item-action">
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
                                    <button data-toggle="modal" data-target="#appointmentRequest"
                                            class="list-group-item list-group-item-action">
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
                    </div>
                </div>

            </div>

        </div>

    </div>
</main>


<?php require_once "footer.php" ?>

<script src="js/jquery-ui-timepicker-addon.js"></script>
<script src="js/jquery-ui-timepicker-nl.js"></script>
<link rel="stylesheet" href="css/jquery-ui-timepicker-addon.css" />


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
                <form id="addPetForm" method="POST" enctype="multipart/form-data">
                    <div id="message">
                    </div>

                    <div class="form-group">
                        <label for="name">Reden afspraak</label>
                        <input type="text" name="name" class="form-control" id="name" required>
                    </div>

                    <div class="form-group">
                        <label for="datepicker">Datum</label>
                        <input type="text" name="date" class="form-control datetimepicker" required>
                    </div>


                    <div class="form-group">
                        <label for="name">Extra informatie</label>
                        <textarea  name="description" id="description" class="form-control" aria-label="With textarea"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="treatment">Behandelingen:</label>
                        <select class="select_mul" name="states[]" multiple="multiple" >
                            <option value="AL">Alabama</option>
                            <option value="WY">Wyoming</option>
                        </select>
                    </div>

                    <div class="appointmentInfo">
                        <table class="table">
                            <tbody>
                            <tr>
                                <th scope="row">Eigenaar</th>
                                <td>Stefan</td>
                            </tr>
                            <tr>
                                <th scope="row">Huisdier</th>
                                <td>Sky</td>
                            </tr>
                            <tr>
                                <th scope="row">Geboortedatum</th>
                                <td>5 Apr 2019</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Verwijder afspraak</button>
                        <button type="button" name="addPet" id="submitPet" class="btn btn-success">Opslaan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="appointmentRequest" tabindex="-1" role="dialog" aria-labelledby="appointmentRequest" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Aanvraag</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addPetForm" method="POST">
                    <div id="message">
                    </div>

                    <div class="form-group">
                        <label for="name">Reden afspraak</label>
                        <input type="text" name="name" class="form-control" id="name" required>
                    </div>

                    <div class="form-group">
                        <label for="datepicker">Datum</label>
                        <input type="text" name="date" class="form-control datetimepicker" required>
                    </div>

                    <div class="form-group">
                        <label for="name">Extra informatie</label>
                        <textarea  name="description" id="description" class="form-control" aria-label="With textarea"></textarea>
                    </div>

                    <div class="appointmentInfo">
                        <table class="table">
                            <tbody>
                            <tr>
                                <th scope="row">Eigenaar</th>
                                <td>Stefan</td>
                            </tr>
                            <tr>
                                <th scope="row">Huisdier</th>
                                <td>Sky</td>
                            </tr>
                            <tr>
                                <th scope="row">Geboortedatum</th>
                                <td>5 Apr 2019</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Verwijder afspraak</button>
                        <button type="button" name="addPet" id="submitPet" class="btn btn-success">Plan afspraak in</button>
                    </div>
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
                <form id="addAppointmentForm">
                    <div id="message">
                    </div>

                    <div class="form-group">
                        <label for="user">Klant</label>
                        <select name="user" id="user" class="form-control" required>
                            <option value="dog">Gebruiker</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="pet">Huisdier</label>
                        <select name="pet" id="pet" class="form-control select" required>
                            <option value="dog">Hierdier</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="name">Reden afspraak</label>
                        <input type="text" name="name" class="form-control" id="name" required>
                    </div>

                    <div class="form-group">
                        <label for="datepicker">Datum</label>
                        <input type="text" name="datepicker" class="form-control datetimepicker" value="">
                    </div>

                    <div class="form-group">
                        <label for="name">Extra informatie</label>
                        <textarea  name="description" id="description" class="form-control" aria-label="With textarea"></textarea>
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
