<?php
    require_once "../../functions.php";
    $petObj = new Pet;
?>
<div id="dog">
    <?php foreach ($petObj->getBreeds() as $breed) : ?>
        <?php if ($breed['breedTypeID'] == 1) : ?>
            <option value="<?php echo $breed['breedID'] ?>"><?php echo $breed['name'] ?></option>
        <?php endif; ?>
    <?php endforeach ?>
</div>
<div id="cat">
    <?php foreach ($petObj->getBreeds() as $breed) : ?>
        <?php if ($breed['breedTypeID'] == 2) : ?>
            <option value="<?php echo $breed['breedID'] ?>"><?php echo $breed['name'] ?></option>
        <?php endif; ?>
    <?php endforeach ?>
</div>