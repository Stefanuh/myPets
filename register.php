<?php
    require_once "head.php";
    require_once "header.php"
?>
<main>
    <div class="container">
        <div class="row">
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Maak een account aan</h5>
                        <p class="card-text">Vul de onderstaande gegevens in</p>
                        <div class="form">
                            <div id="message">
                            </div>
                            <input class="form-control" id="firstName" type="text" placeholder="Voornaam">
                            <input class="form-control" id="lastName" type="text" placeholder="Achternaam">
                            <input class="form-control" id="email" type="email" placeholder="Emailadres">
                            <input class="form-control" id="password" type="password" placeholder="Wachtwoord">
                            <input class="form-control" id="passwordCheck" type="password" placeholder="Wachtwoord nogmaals">
                            <button id="createAccount" class="btn btn-primary mb-2">Maak account</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Ik heb een account</h5>
                        <p class="card-text">Al klant van myPets?</p>
                        <a href="/login" class="btn btn-secondary">Log in</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php require_once "footer.php" ?>
<script>
    // Een constant met alle inputs
    const form = {
        firstName: document.getElementById('firstName'),
        lastName: document.getElementById('lastName'),
        email: document.getElementById('email'),
        password: document.getElementById('password'),
        passwordCheck: document.getElementById('passwordCheck'),
        messages: document.getElementById('message'),
        submit: document.getElementById('createAccount'),
    };

    form.submit.addEventListener('click', function () {
        // Als er al errors waren, deze eerst weghalen
        while(form.messages.firstChild) form.messages.removeChild(form.messages.firstChild);
        let messageList = [];
        let error = false;

        // Checks of de inputs niet leeg zijn
        if (form.firstName.value === "") {
            messageList.push("Vul aub uw voornaam in");
            error = true;
        }
        if (form.lastName.value === "") {
            messageList.push("Vul aub uw achternaam in");
            error = true;
        }
        if (form.email.value === "") {
            messageList.push("Vul aub uw emailadres in");
            error = true;
        }
        if (form.password.value === "") {
            messageList.push("Vul aub uw wachtwoord in");
            error = true;
        } else if (form.passwordCheck.value === "") {
            messageList.push("Vul aub de wachtwoord contole in");
            error = true;
        } else if (form.password.value !== form.passwordCheck.value) {
            messageList.push("De wachtwoorden kwamen niet overeen");
            error = true;
        }

        // Toon error melding(en) als er een error was
        if (error) {
            messageList.forEach(function(message) {
                const li = document.createElement('div');
                li.className = 'alert alert-danger';
                li.textContent = message;
                form.messages.appendChild(li);
            });
        } else {
            const url = '/php/register.php';
            const formData = new FormData();
            formData.append('firstName', form.firstName.value);
            formData.append('lastName', form.lastName.value);
            formData.append('email', form.email.value);
            formData.append('password', form.password.value);

            fetch(url, { method: 'POST', body: formData })
            .then(function (response) {
                return response.json();
            })
            .then(function (responseObject) {
                // Als registreren gelukt is dan refreshen
                if (responseObject.ok) {
                    location.reload();
                } else {
                    // Zo niet toon dan foutmeldingen
                    while(form.messages.firstChild) form.messages.removeChild(form.messages.firstChild);
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