<?php
/**
 * @var \Almacen\models\MenuElement $menuElement
 */
?>
<li class="nav-item <?= $menuElement->class ?>">
    <a class="nav-link <?= $menuElement->linkClass ?>" href="<?= $menuElement->href ?>"><?= $menuElement->name ?></a>
</li>
