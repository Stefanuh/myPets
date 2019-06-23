<header>

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <a class="navbar-brand" href="<?php echo root ?>"><img src="img/logo.svg">myPets</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main_menu" aria-controls="main_menu" aria-expanded="false">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="main_menu">
            <ul class="navbar-nav ml-auto">


                <?php if (isset($user)) : ?>

                    <?php foreach($pages as $page) : ?>

                        <?php if ($page['secure']) : ?>

                            <?php if(isset($page['sub'])) : ?>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="dropdown_<?php echo $page['slug'] ?>" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <?php echo $page['title'] ?>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="dropbown_<?php echo $page['slug'] ?>">
                                        <?php foreach ($page['sub'] as $subpage) : ?>
                                            <a class="dropdown-item" href="<?php echo $subpage['slug'] ?>"><?php echo $subpage['title'] ?></a>
                                        <?php endforeach; ?>
                                    </div>
                                </li>
                            <?php else : ?>
                                <li class="nav-item <?php if (("/" . $page['slug'] . ".php") == $_SERVER['PHP_SELF']) echo "active" ?>">
                                    <a class="nav-link" href="/<?php echo $page['slug'] ?>"><?php echo $page['title']  ?> <?php if (("/".$page['slug'].".php") == $_SERVER['PHP_SELF']) echo "<span class=\"sr-only\">(current)</span>" ?></a>
                                </li>
                            <?php endif; ?>

                        <?php endif ?>

                    <?php endforeach; ?>

                <?php else: ?>

                <?php foreach($pages as $page) : ?>

                <?php if (!$page['secure']) : ?>

                <?php if(isset($page['sub'])) : ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="dropdown_<?php echo $page['slug'] ?>" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?php echo $page['title'] ?>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="dropbown_<?php echo $page['slug'] ?>">
                        <?php foreach ($page['sub'] as $subpage) : ?>
                            <a class="dropdown-item" href="<?php echo $subpage['slug'] ?>"><?php echo $subpage['title'] ?></a>
                        <?php endforeach; ?>
                    </div>
                </li>
                <?php else : ?>
                <li class="nav-item <?php if (("/" . $page['slug'] . ".php") == $_SERVER['PHP_SELF']) echo "active" ?>">
                    <a class="nav-link" href="/<?php echo $page['slug'] ?>"><?php echo $page['title']  ?> <?php if (("/".$page['slug'].".php") == $_SERVER['PHP_SELF']) echo "<span class=\"sr-only\">(current)</span>" ?></a>
                </li>
                <?php endif; ?>

                <?php endif ?>

                <?php endforeach; ?>
                <?php endif; ?>


            </ul>
        </div>
    </nav>
</header>