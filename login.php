<?php

    require_once "head.php";
    require_once "header.php";

?>

    <main id="login">

        <div class="container h-100">
            <div class="row">
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Ik heb een account</h5>
                            <p class="card-text">Voer uw gegevens hieronder in</p>

                            <div class="form">
                                <div id="message">
                                </div>
                                <input type="email"  id="email" class="form-control" placeholder="Emailadres" required>
                                </label><input type="password"  id="password" class="form-control" placeholder="Wachtwoord" required>
                                <button type="submit" id="submitLogin" class="btn btn-primary">Log in</button>
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
    const form = {
        email: document.getElementById('email'),
        password: document.getElementById('password'),
        message: document.getElementById('message'),
        submit: document.getElementById('submitLogin'),
    };

    form.submit.addEventListener('click', () => {
       const request = new XMLHttpRequest();

       request.onload = () => {
           let responseObject = null;

           try {
               responseObject = JSON.parse(request.responseText);
           } catch(e) {
                console.error('Could not parse JSON');
           }
           if (responseObject) {
               handleResponse(responseObject);
           }

       };

       const requestData = `email=${form.email.value}&password=${form.password.value}`;

       request.open('post', 'php/login.php');
       request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
       request.send(requestData);
    });

    function handleResponse(responseObject) {
        if (responseObject.ok) {
            location.href = 'dashboard';
        } else {
            while(form.message.firstChild) form.message.removeChild(form.message.firstChild);

            responseObject.message.forEach((message) => {
                const li = document.createElement('div');
                li.className = 'alert alert-danger';
                li.textContent = message;
                form.message.appendChild(li);
            });

            form.message.style.display = "block";
        }
    }
</script>
