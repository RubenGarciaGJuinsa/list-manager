<?php
/**
 * @var array $lists
 */

use Almacen\Core\Application;

?>
<div class="">
    <form id="task-form" class="form" method="POST">
        <div class="form__row">
            <label class="form__label" for="nameField"><?= Application::t('form-task', 'Nombre') ?></label>
            <input type="text" class="form__input form-control" id="nameField" name="task[name]"
                   value="<?= ! empty($task['name']) ? $task['name'] : '' ?>"
                   placeholder="<?= Application::t('form-task', 'Introducir nombre...') ?>">
        </div>
        <div class="form__row">
            <label class="form__label" for="listField"><?= Application::t('form-task',
                    'Lista') ?></label>
            <select class="form__input form-control" id="listField" name="task[list_id]">
                <?php
                foreach ($lists as $list) {
                    ?>
                    <option value="<?= $list['id'] ?>"
                    <?= ( ! empty($task['list_id']) && $task['list_id'] == $list['id']) ? 'selected="selected"' : '' ?>
                    ><?= $list['name'] ?></option><?php
                }
                ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Guardar</button>
    </form>
</div>
<script type="text/javascript">
    (function($) {
        $("#task-form").validate({
            errorClass: "is-invalid",
            rules: {
                "task[name]": {
                    required: true,
                    maxlength: 255
                }
            },
            messages: {
                "task[name]": {
                    required: "The name is required",
                    maxlength: "The max length of the name is {0} characters",
                }
            }
        });
    })(jQuery);
</script>