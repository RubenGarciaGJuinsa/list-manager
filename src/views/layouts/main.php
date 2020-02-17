<?php

use Almacen\Core\Application;

/**
 * @var string $content
 * @var \Almacen\core\View $this
 */
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <title><?= $this->context->title ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link href="/css/normalize.css" rel="stylesheet">
        <link href="/css/bootstrap.min.css" rel="stylesheet">
        <link href="/css/custom.css" rel="stylesheet">
        <script src="/js/jquery-3.4.1.slim.min.js"></script>
        <script src="/js/jquery.validate.min.js"></script>
    </head>

    <body>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse"
                    data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand" href="/"><?= Application::getInstance()->getName() ?></a>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <?php
                    foreach ($this->context->menuElements as $menuElement) {
                        echo $this->renderFile(__DIR__.'/menuElement.php', ['menuElement' => $menuElement]);
                    }
                    ?>
                </ul>
            </div>
        </nav>
        <div class="container mt-4">
            <?php
            if ( ! empty($this->context->alertMessages)) {
                ?>
                <div class="mb-5">
                    <?php
                    foreach ($this->context->alertMessages as $level => $messages) {
                        foreach ($messages as $message) {
                            ?>
                            <div class="alert alert-<?= $level ?> alert-dismissible fade show" role="alert">
                                <?= $message ?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>
                <?php
            }
            ?>
            <div><?= $content ?></div>
        </div>
        <script src="/js/popper.min.js"></script>
        <script src="/js/bootstrap.min.js"></script>
        <script src="https://kit.fontawesome.com/44a934414c.js" crossorigin="anonymous"></script>
    </body>
</html>