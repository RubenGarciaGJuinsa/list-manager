<?php
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
                List id
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
                <td><?= $task['list_id'] ?></td>
                <td>
                    <a href="/task/view/<?= $task['id'] ?>"><i class="fa fa-eye"></i></a>
                    <i class="fa fa-pencil-alt"></i>
                    <i class="fa fa-trash-alt"></i>
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


