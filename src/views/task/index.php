<?php
/**
 * @var array $tasks
 * @var array $lists
 */
?>
<a class="btn btn-success float-right mb-4" href="/task/create">Create task</a>
<?php
if ( ! empty($tasks)) {
    ?>
    <table class="table">
        <thead class="thead-dark">
        <tr>
            <th>
                ID
            </th>
            <th>
                Nombre
            </th>
            <th>
                List
            </th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($tasks as $task) {
            ?>
            <tr>
                <td><?= $task['id'] ?></td>
                <td><?= $task['name'] ?></td>
                <td><?= $lists[$task['list_id']] ?></td>
                <td>
                    <a href="/task/view/<?= $task['id'] ?>" title="View task"><i class="fa fa-eye"></i></a>
                    <a href="/task/update/<?= $task['id']?>" title="Edit task"><i class="fa fa-pencil-alt"></i></a>
                    <a href="/task/delete/<?= $task['id'] ?> " title="Delete task" onclick="return confirm('Are you sure?')"><i class="fa fa-trash-alt"></i></a>
                </td>
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>
    <?php
}
?>


