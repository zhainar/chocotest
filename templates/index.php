<?php if (isset($migrated) && is_array($migrated)): ?>
    <ul>
        <?php foreach ($migrated as $item): ?>
            <li>Migration <?= $item ?> exists</li>
        <?php endforeach; ?>
    </ul>
    <hr>
<?php endif; ?>
<h3>Загрузить данные</h3>
<form enctype="multipart/form-data" method="post">
    <input type="file" name="csv">
    <button name="save" value="Y">Загрузить</button>
</form>
<hr>
<h3>Загруженные данные</h3>
<table border="1" cellpadding="6" style="border-collapse: collapse">
    <?php if (isset($labels) && is_array($labels)): ?>
        <thead>
        <tr>
            <?php foreach ($labels as $label): ?>
                <th><?= $label ?></th>
            <?php endforeach; ?>
            <th>URL</th>
        </tr>
        </thead>
    <?php endif; ?>
    <?php if (isset($actions) && is_array($actions)): ?>
        <tbody>
        <?php
        /** @var \models\Action $action */
        foreach ($actions as $action): ?>
            <tr>
                <td><?= $action->getId() ?></td>
                <td><?= $action->getName() ?></td>
                <td><?= $action->getStartDate()->format('d-m-Y') ?></td>
                <td><?= $action->getEndDate()->format('d-m-Y') ?></td>
                <td><?= $action->getStatus() ?></td>
                <td>
                    <?php $link = $action->getUrl() ?>
                    <a href="<?= $link ?>"><?= $link ?></a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    <?php endif; ?>
</table>
<hr>
<form method="post">
    <button name="random" value="Y">Рандомно сменить статус</button>
</form>
<?php if (isset($random_action) && !is_null($random_action)): ?>
    <h4>Случайно измененная запись</h4>
    <table border="1" cellpadding="6" style="border-collapse: collapse">
        <thead>
        <tr>
            <?php foreach ($labels as $label): ?>
                <th><?= $label ?></th>
            <?php endforeach; ?>
            <th>URL</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td><?= $random_action->getId() ?></td>
            <td><?= $random_action->getName() ?></td>
            <td><?= $random_action->getStartDate()->format('d-m-Y') ?></td>
            <td><?= $random_action->getEndDate()->format('d-m-Y') ?></td>
            <td><?= $random_action->getStatus() ?></td>
            <td>
                <?php $link = $random_action->getUrl() ?>
                <a href="<?= $link ?>"><?= $link ?></a>
            </td>
        </tr>
        </tbody>
    </table>
<?php endif; ?>