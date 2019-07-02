<?php
    require_once "head.php";
    // Check of de gebruiker geen admin is
    if ($userObj->getRole()) header("Location: /appointment");
    $petObj = new Pet();
?>
<main id="dashboard">
    <div class="container-fluid">
        <div class="row justify-content-center align-items-center">
            <?php foreach ($petObj->getUserPets() as $pet) : ?>
            <div class="card" style="width: 18rem;">
                <div class="card-img-top" style="background-image: url('<?php echo $petObj->getPicture($pet['petID'], $pet['breedID']) ?>')"></div>
                <div class="card-body">
                    <h5 class="card-title"><?php echo $pet['name'] ?></h5>
                    <p class="card-text">
                        <span class="badge badge-info"><?php echo $petObj->getBreedByID($pet['breedID'])['name'] ?>
                        </span>
                        <br>
                        <span class="badge badge-secondary">
                            <?php $date = date_create($pet['birth']); echo date_format($date, "j M Y") ?>
                        </span>
                    </p>
                    <a href="/pet?id=<?php echo $pet['petID'] ?>" class="btn btn-outline-primary appointment">
                        Bekijk <?php echo $pet['name'] ?>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
            <div class="card" style="width: 18rem;">
                <div class="card-img-top" style="background-image: url('img/pets.jpg')"></div>
                <div class="card-body">
                    <h5 class="card-title">Huisdier registreren</h5>
                    <p class="card-text">
                        Een huisdier toevoegen?<br>
                        Klik op de onderstaande knop
                    </p>
                    <button  data-toggle="modal" data-target="#registerPet" class="btn btn-outline-success">
                        Huisdier registreren
                    </button>
                </div>
            </div>
        </div>
    </div>
</main>
<!-- Modals -->
<div class="modal fade" id="registerPet" tabindex="-1" role="dialog" aria-labelledby="registerPet" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Huisdier registreren</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addPetForm" method="POST" enctype="multipart/form-data">
                    <div id="message">
                    </div>
                    <div class="form-group">
                        <label for="name">Naam</label>
                        <input type="text" name="name" class="form-control" id="name"
                               placeholder="Geef de naam van uw huisdier" required>
                    </div>
                    <div class="form-group">
                        <label for="breedType">Soort</label>
                        <select name="breedType" id="breedType" class="form-control" required>
                            <?php foreach ($petObj->getBreedTypes() as $breedTypes) : ?>
                                <option value="<?php echo $breedTypes['breedTypeID'] ?>"><?php echo $breedTypes['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="breed">Ras</label>
                        <select name="breed" id="breed" class="form-control select" required>
                            <?php foreach ($petObj->getBreeds() as $breed) : ?>
                                <option value="<?php echo $breed['breedID'] ?>"><?php echo $breed['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="datepicker">Geboortedatum</label>
                        <input type="text" name="birth" id="birth" class="form-control datepicker"
                               placeholder="Geef de geboortedatum van uw huisdier"
                               value="<?php echo date('d-m-Y') ?>" readonly="readonly">
                    </div>
                    <div class="form-group">
                        <label>Foto</label>
                        <div class="custom-file">
                            <input type="file" name="file" class="custom-file-input" id="picture" accept="image/*">
                            <label class="custom-file-label" for="petPicture">Kies een foto (optioneel)</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuleren</button>
                        <button type="button" name="addPet" id="submitPet" class="btn btn-success">Registreer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php require_once "footer.php" ?>
<script>
    // Haal de juiste rassen bij de gekozen soort huisdier op
    $( "#breedType" ).change(function() {
        $.ajax({
            type: 'POST',
            url: 'php/parts/pet_breeds.php',
            data: { breedTypeID: $(this).val() },
            success: function(data) {
                $('#breed').html(data);
            }
        });
    });
    const form = {
        messages: document.getElementById('message'),
        submitPet: document.getElementById('submitPet'),
        name: document.getElementById('name'),
        birth: document.getElementById('birth'),
        breed: document.getElementById('breed'),
        file: document.getElementById('picture')
    };
    form.submitPet.addEventListener('click', function () {
        while (form.messages.firstChild) form.messages.removeChild(form.messages.firstChild);
        let messageList = [];
        let error = false;

        if (form.name.value === "") {
            messageList.push("Voer aub de naam in van uw huisdier");
            error = true;
        }
        if (form.breed.value === "") {
            messageList.push("Voer aub de ras in van uw huisdier");
            error = true;
        }
        if (form.birth.value === "") {
            messageList.push("Voor aub de geboortedatum in van uw huisdier");
            error = true;
        }
        // Een check om te kijken of een foto is geupload
        if (form.file.files[0]) {
            // Check of de foto groter is als 5 mb
            if (form.file.files[0].size > 5000000) {
                messageList.push("De afbeelding is helaas te groot om te uploaden");
                error = true;
            }
            // Check of de foto de juiste bestandstype is
            if (form.file.files[0].type === "image/jpeg" || form.file.files[0].type === "image/png") {
            } else {
                messageList.push("Sorry alléén JPG, JPEG & PNG bestanden zijn toegestaan.");
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
            const url = '/php/add_pet.php';
            const formData = new FormData();
            formData.append('name', form.name.value);
            formData.append('breed', form.breed.value);
            formData.append('birth', form.birth.value);
            formData.append('file', form.file.files[0]);

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