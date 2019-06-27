<header>

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <a class="navbar-brand" href="<?php echo root ?>"><img alt="myPets" src="img/logo.svg">myPets</a> <?php if (isset($subTitle)) echo $subTitle; ?>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main_menu" aria-controls="main_menu" aria-expanded="false">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="main_menu">
            <ul class="navbar-nav ml-auto">

                <?php
                    if ($userObj->getUserID()) $menu_items = $pageObj->getMenu(1);
                    else $menu_items = $pageObj->getMenu();
                ?>

                    <?php foreach($menu_items as $menu_item) : ?>
                            <li class="nav-item <?php if ($menu_item['slug'] == $pageObj->getPageSlug()) echo "active" ?>">
                                <a class="nav-link" href="/<?php echo $menu_item['slug'] ?>"><?php echo $menu_item['name']  ?> <?php if ($menu_item['slug'] == $pageObj->getPageSlug()) echo "<span class=\"sr-only\">(current)</span>" ?></a>
                            </li>
                    <?php endforeach; ?>
            </ul>

        </div>
    </nav>
</header>