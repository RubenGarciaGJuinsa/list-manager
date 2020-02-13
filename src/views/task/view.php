<?php
/**
 * @var array $task
 * @var array $lists
 */

$fields = [];
$fields['ID'] = $task['id'];
$fields['Nombre'] = $task['name'];
$fields['List'] = $lists[$task['list_id']];

?>
<a class="btn btn-success float-right mb-4" href="/task/update/<?=$fields['ID']?>">Edit task</a>
<div class="float-left col-12">
    <?php
    foreach ($fields as $name => $value) {
        ?>
        <div class="row">
            <div class="col-sm-12 col-md-3"><?= $name ?></div>
            <div class="col-sm-12 col-md-9"><?= $value ?></div>
        </div>
        <?php
    }
    ?>
</div>
