<?php
    require_once "head.php";
    require_once "header.php";
?>
<main id="login">
    <div class="container">
        <div class="row">
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Ik heb een account</h5>
                        <p class="card-text">Voer uw gegevens hieronder in</p>
                        <div class="form">
                            <div id="message">
                            </div>
                            <input type="email" id="email" class="form-control" placeholder="Emailadres">
                            <input type="password" id="password" class="form-control" placeholder="Wachtwoord">
                            <button id="submitLogin" class="btn btn-primary">Log in</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Ik heb nog geen account</h5>
                        <p class="card-text">Nog geen klant van myPets?</p>
                        <a href="/register" class="btn btn-secondary">Maak een account aan</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php require_once "footer.php" ?>
<script>
    // Weghalen van de geheugen tab
    localStorage.removeItem('activeTab');

    // Een constant met alle inputs
    const form = {
        email: document.getElementById('email'),
        password: document.getElementById('password'),
        messages: document.getElementById('message'),
        submit: document.getElementById('submitLogin'),
    };

    form.submit.addEventListener('click', function (e) {
        // Als er al errors waren, deze eerst weghalen
        while(form.messages.firstChild) form.messages.removeChild(form.messages.firstChild);
        let messageList = [];
        let error = false;

        // Checks of de inputs niet leeg zijn
        if (form.email.value === "") {
            messageList.push("Vul aub uw emailadres in");
            error = true;
        }
        if (form.password.value === "") {
            messageList.push("Vul aub uw wachtwoord in");
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
            const url = '/php/login.php';
            const formData = new FormData();
            formData.append('email', form.email.value);
            formData.append('password', form.password.value);

            fetch(url, { method: 'POST', body: formData })
            .then(function (response) {
                return response.json();
            })
            .then(function (responseObject) {
                if (responseObject.ok) {
                    // Als registreren gelukt is stuur door naar dashboard
                    location.href = 'dashboard';
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