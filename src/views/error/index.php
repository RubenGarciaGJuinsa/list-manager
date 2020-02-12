<?php
/**
 * @var Exception $exception
 */
?>
<p class="display-1 text-center">Ups!</p>
<?php
if ( ! empty($exception->getMessage())) {
    ?><h2 class="display-4"><?= $exception->getMessage() ?></h2><?php
}
?>
<h4><?= get_class($exception) ?><small
        class="text-muted"><?= ' in '.$exception->getFile().' on line '.$exception->getLine() ?></small></h4>
<p><?= $exception->getTraceAsString() ?></p>