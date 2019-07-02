<?php
    require_once "../../functions.php";
    $breedTypeID = $_POST['breedTypeID'];
    $petObj = new Pet;
    foreach ($petObj->getBreeds() as $breed) : ?>
    <?php if ($breed['breedTypeID'] == $breedTypeID) : ?>
        <option value="<?php echo $breed['breedID'] ?>"><?php echo $breed['name'] ?></option>
    <?php endif; ?>
<?php endforeach ?>
