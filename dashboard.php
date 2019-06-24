<?php require_once "head.php"; ?>

<main>
    <div class="container">
        <?php if (getUser()['role']) : ?>
        <?php ?>
            Hello
        <?php else: ?>
        <div class="row justify-content-center align-items-center">



            <?php foreach (getPets() as $pet) : ?>

                <div class="card" style="width: 18rem;">
                    <div class="card-img-top" style="background-image: url('<?php echo getPetPicture($pet['petID']) ?>')"></div>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $pet['name'] ?></h5>
                        <p class="card-text">
                            <span class="badge badge-info"><?php echo getBreedByID($pet['breedID'])['name'] ?></span> <br>
                            <span class="badge badge-secondary"><?php $date = date_create($pet['birth']); echo date_format($date, "j M Y") ?></span>
                        </p>
                        <a href="#" class="btn btn-outline-primary">Gegevens</a>
                        <a href="#" class="btn btn-outline-danger">Afspraken</a>
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
                    <form method="POST" action="php/addPet">
                        <div class="form-group">
                            <label for="name">Naam</label>
                            <input type="text" name="name" class="form-control" id="name" placeholder="Geef de naam van uw huisdier" required>
                        </div>
                        <div class="form-group">
                            <label for="breed">Ras</label>
                            <select name="breed" class="select" id="breed" class="form-control" required>
                                <?php foreach (getQuery("SELECT * FROM breed") as $breed) : ?>
                                    <option value="<?php echo $breed['breedID'] ?>"><?php echo $breed['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="datepicker">Geboortedatum</label>
                            <input type="text" class="form-control" name="birth" id="datepicker" placeholder="Geef uw huidier een naam" value="<?php echo date('Y-m-d') ?>" required>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuleren</button>
                            <button type="submit" name="addPet" class="btn btn-success">Registreer</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>


<?php require_once "footer.php" ?>
