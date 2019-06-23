<?php

    require_once "head.php";
    require_once "header.php"

?>

    <main id="login">

        <div class="container h-100">
            <div class="row">
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Ik heb een account</h5>
                            <p class="card-text">Voer uw gegevens hieronder in</p>
                            <form method="POST" action="php/login.php">
                                <input class="form-control" name="email" type="email" placeholder="Emailadres">
                                <input class="form-control" name="password" type="password" placeholder="Wachtwoord">
                                <button type="submit" name="login" class="btn btn-primary mb-2">Log in</button>
                            </form>
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