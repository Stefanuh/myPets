<?php require_once "head.php"; ?>

<main>
    <div class="container">
        <?php if ($user['role']) : ?>
        <?php ?>
            Hello
        <?php else: ?>


        <div class="row justify-content-center align-items-center">
            <div class="card" style="width: 18rem;">
                <div class="card-img-top" style="background-image: url('img/pets/1.jpg')"></div>
                <div class="card-body">
                    <h5 class="card-title">Sky</h5>
                    <p class="card-text">
                        <span class="badge badge-info">Australische herder</span> <br>
                        <span class="badge badge-secondary">12 maanden</span>
                    </p>
                    <a href="#" class="btn btn-outline-primary">Gegevens</a>
                    <a href="#" class="btn btn-outline-danger">Afspraken</a>
                </div>
            </div>

            <div class="card" style="width: 18rem;">
                <div class="card-img-top" style="background-image: url('img/pets.jpg')"></div>
                <div class="card-body">
                    <h5 class="card-title">Huisdier registreren</h5>
                    <p class="card-text">
                        Nog een huisdier toevoegen?<br>
                        Klik op de onderstaande knop
                    </p>
                    <button  data-toggle="modal" data-target="#registerPet" class="btn btn-outline-success">Huisdier registreren</button>
                </div>
            </div>

        </div>

        <?php endif; ?>
    </div>
</main>

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
                    <form>
                        <div class="form-group">
                            <label for="name">Naam</label>
                            <input type="email" class="form-control" id="name" placeholder="Geef de naam van uw huisdier">
                        </div>
                        <div class="form-group">
                            <label for="birth">Ras</label>
                            <select name="breeds" class="select" id="birth" class="form-control">
                                <?php foreach (getQuery("SELECT * FROM breed") as $breed) : ?>
                                    <option value="<?php echo $breed['breedID'] ?>"><?php echo $breed['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="datepicker">Geboortedatum</label>
                            <input type="text" class=" form-control" name="birth" id="datepicker" placeholder="Geef uw huidier een naam" value="<?php echo date('Y-m-d') ?>">
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuleren</button>
                            <button type="submit" class="btn btn-success">Registreer</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>


<?php require_once "footer.php" ?>
