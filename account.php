<?php
require_once "head.php";
// Check of de gebruiker geen admin is
if ($userObj->getRole()) header("Location: /appointment");

?>

<main id="account">
    <div class="container-fluid">
        <div class="row justify-content-center align-items-center">
            <div class="card" style="width: 25rem" >
                <div class="card-body">
                    <h5 class="card-title"><?php echo $userObj->getFullName() ?></h5>
                    <p class="card-text">
                        Email: <?php echo $userObj->getEmail() ?><br>
                        Telefoonnummer: <?php if(!empty($userObj->getPhone())) echo $userObj->getPhone();
                        else echo "Niet ingesteld" ?>
                    </p>
                    <button data-toggle="modal" data-target="#accountChange" class="btn btn-outline-primary">Pas gegevens aan</button>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Modals -->
<div class="modal fade" id="accountChange" tabindex="-1" role="dialog" aria-labelledby="accountChange" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Gegevens aanpassen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form">
                    <div id="message">
                    </div>
                    <div class="form-group">
                        <label for="name">Voornaam</label>
                        <input type="text" class="form-control" id="firstName"
                               placeholder="Geef uw voornaam op" value="<?php echo $userObj->getFirstName() ?>">
                    </div>

                    <div class="form-group">
                        <label for="lastName">Achternaam</label>
                        <input type="text" class="form-control" id="lastName"
                               placeholder="Geef uw achternaam op" value="<?php echo $userObj->getLastName() ?>">
                    </div>

                    <div class="form-group">
                        <label for="phone">Telefoonnummer</label>
                        <input type="text" class="form-control" id="phone"
                               placeholder="Geef uw telefoonnummer op" value="<?php echo $userObj->getPhone() ?>">
                    </div>

                    <div class="form-group">
                        <label for="email">Emailadres</label>
                        <input type="email" class="form-control" id="email"
                               placeholder="Geef uw emailadres op" value="<?php echo $userObj->getEmail() ?>">
                    </div>

                    <div class="form-group">
                        <label for="password">Wachtwoord</label>
                        <input type="password" class="form-control" id="password"
                               placeholder="Geef uw wachtwoord op (ter controle)" >
                    </div>

                    <div class="form-group">
                        <label for="newPass">Nieuw wachtwoord</label>
                        <input type="password" class="form-control" id="newPass"
                               placeholder="Vul dit veld alléén in als u een nieuw wachtwoord wilt" >
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuleren</button>
                        <button type="button" name="addPet" id="submitChanges" class="btn btn-success">Pas aan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once "footer.php" ?>

<script>
    const form = {
        messages: document.getElementById('message'),
        submit: document.getElementById('submitChanges'),
        firstName: document.getElementById('firstName'),
        lastName: document.getElementById('lastName'),
        phone: document.getElementById('phone'),
        email: document.getElementById('email'),
        password: document.getElementById('password'),
        newPass: document.getElementById('newPass'),
    };
    form.submit.addEventListener('click', function () {
        while (form.messages.firstChild) form.messages.removeChild(form.messages.firstChild);
        let messageList = [];
        let error = false;

        if (form.firstName.value === "") {
            messageList.push("Voer aub uw voornaam in");
            error = true;
        }
        if (form.lastName.value === "") {
            messageList.push("Voer aub uw achternaam in");
            error = true;
        }
        if (form.phone.value === "") {
            messageList.push("Voer aub uw telefoonnummer in");
            error = true;
        }
        if (form.email.value === "") {
            messageList.push("Voer aub uw emailadres in");
            error = true;
        }
        if (form.password.value === "") {
            messageList.push("Voer aub uw wachtwoord in");
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
            const url = '/php/change_account.php';
            const formData = new FormData();
            formData.append('firstName', form.firstName.value);
            formData.append('lastName', form.lastName.value);
            formData.append('phone', form.phone.value);
            formData.append('email', form.email.value);
            formData.append('password', form.password.value);
            formData.append('newPass', form.newPass.value);

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