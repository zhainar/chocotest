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
            $this->render('index', compact('migrated', 'actions', 'labels', 'random_action'));
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
     * @param $template
     * @param array $data
     * @param bool $render
     * @return \core\base\ResponseInterface
     */
    public function render($template, array $data = [], $render = true)
    {
        $response = \core\ResponseFactory::get(\core\ResponseFactory::HTML);
        $response->setTemplate($template);
        $response->setData($data);
        if ($render) {
            $response->render();
        } else {
            return $response;
        }
    }
}