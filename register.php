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
                            <form method="POST" action="php/register.php">
                                <input class="form-control" name="firstName" type="text" placeholder="Voornaam">
                                <input class="form-control" name="lastName" type="text" placeholder="Achternaam">
                                <input class="form-control" name="email" type="email" placeholder="Emailadres">
                                <input class="form-control" name="password" type="password" placeholder="Wachtwoord">
                                <input class="form-control" name="passwordCheck" type="password" placeholder="Wachtwoord nogmaals">
                                <button type="submit" name="createAccount" class="btn btn-primary mb-2">Maak account</button>
                            </form>
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