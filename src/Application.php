<?php

/**
 * Created by PhpStorm.
 * User: uzer
 * Date: 14.08.2018
 * Time: 23:43
 */
class Application
{
    public function run()
    {
        try
        {
            $migration_manager = new \core\MigrationsManager();
            $migration_manager->check();
            $migrated = $migration_manager->getMigrated();
            if (isset($_POST['save']) && $_POST['save'] == 'Y') {
                $this->handlePostSave();
            } elseif (isset($_POST['random']) && $_POST['random'] == 'Y') {
                $random_action = $this->handlePostRandom();
            }
            $actions = \models\Action::findAll();
            $labels = \models\Action::labels();
            $this->render(compact('migrated', 'actions', 'labels', 'random_action'));
        }
        catch (Exception $e)
        {
            if (defined('DEBUG') && DEBUG) {
                echo '<pre>';
                echo $e->getMessage() . PHP_EOL . PHP_EOL . $e->getTraceAsString();
                echo '</pre>';
                exit;
            }
        }
    }

    /**
     * @throws Exception
     */
    private function handlePostSave()
    {
        if (!isset($_FILES['csv'])) {
            throw new Exception('Csv file not found');
        }
        $csv_data = $_FILES['csv'];
        if ($csv_data['error'] == UPLOAD_ERR_OK) {
            $csv = array_map(function ($input) { return str_getcsv($input, ';'); }, file($csv_data['tmp_name']));
            array_shift($csv);
            if (empty($csv)) {
                throw new Exception('Empty file');
            }

            try
            {
                \core\Connection::instance()->transaction();
                foreach ($csv as $item) {
                    $action = new \models\Action();
                    $action->setId($item[0]);
                    $action->setName($item[1]);
                    $action->setStartDate($item[2]);
                    $action->setEndDate($item[3]);
                    $action->setStatus($item[4]);
                    $action->save();
                }
                \core\Connection::instance()->commit();
            }
            catch (Exception $e)
            {
                \core\Connection::instance()->rollback();
                throw $e;
            }
        } else {
            throw new Exception('File upload error code: ' . $csv_data['error']);
        }
    }

    /**
     * @return null|static
     * @throws Exception
     */
    private function handlePostRandom()
    {
        $all_ids = \core\Connection::instance()->query('select id from ' . \models\Action::tableName())->fetchAll();
        if (empty($all_ids)) {
            throw new Exception('Records not found');
        }

        $random_id = $all_ids[mt_rand(0, count($all_ids) - 1)]['id'];
        $random_action = \models\Action::findById($random_id);

        if (empty($random_action)) {
            throw new Exception("Record {$random_id} not found");
        }

        try
        {
            \core\Connection::instance()->transaction();
            $random_action->setStatus($random_action->getStatus() == 'On' ? 'Off' : 'On');
            $random_action->save();
            \core\Connection::instance()->commit();
        }
        catch (Exception $e)
        {
            \core\Connection::instance()->rollback();
            throw $e;
        }
        
        return $random_action;
    }

    /**
     * @param array $data
     */
    public function render(array $data)
    {
        extract($data);
        ?>
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
        <hr>
<?php
    }
}