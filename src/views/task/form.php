<?php
/**
 * @var array $lists
 */

use Almacen\Core\Application;

?>
<div class="">
    <form class="form" method="POST">
        <div class="form__row">
            <label class="form__label" for="nameField"><?= Application::t('form-task', 'Nombre') ?></label>
            <input type="text" class="form__input form-control" id="nameField" name="task[name]"
                   placeholder="<?= Application::t('form-task', 'Introducir nombre...') ?>">
        </div>
        <div class="form__row">
            <label class="form__label" for="listField"><?= Application::t('form-task',
                    'Lista') ?></label>
            <select class="form__input form-control" id="listField" name="task[list_id]">
                <?php
                foreach ($lists as $list) {
                    ?>
                    <option value="<?= $list['id'] ?>"><?= $list['name'] ?></option><?php
                }
                ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Guardar</button>
    </form>
</div>
