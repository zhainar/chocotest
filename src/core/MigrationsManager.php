<?php
/**
 * Created by PhpStorm.
 * User: uzer
 * Date: 15.08.2018
 * Time: 0:18
 */

namespace core;


use core\base\MigrationInterface;

class MigrationsManager
{
    /**
     * @var string
     */
    private $migrations_path;

    /**
     * @var string
     */
    private $log_path;

    /**
     * @var array
     */
    private $migrated = [];

    /**
     * MigrationsManager constructor.
     */
    public function __construct()
    {
        $this->migrations_path = BASEPATH . '/src/migrations';
        $this->log_path = $this->migrations_path . '/log';
        if (!file_exists($this->log_path)) {
            if (!mkdir($this->log_path)) {
                throw new \Exception('Could not create log folder');
            }
        }
    }

    /**
     * @throws \Exception
     */
    public function check()
    {
        foreach ((new \DirectoryIterator($this->migrations_path)) as $item) {
            if (preg_match('/\.php$/', $item->getPathname())) {
                $this->_checkMigration($item->getFilename());
            }
        }
    }

    /**
     * @param $filename
     * @throws \Exception
     */
    private function _checkMigration($filename)
    {
        $classname = str_replace('.php', '', 'migrations\\' . $filename);
        $hash = md5($classname);
        $hashpath = $this->log_path . '/' . $hash;
        if (!file_exists($hashpath)) {
            $migration = new $classname();
            $this->_execute($migration);
            if (!file_put_contents($hashpath, $filename)) {
                $this->_revert($migration);
                throw new \Exception("Migration {$classname} reverted. Could not create file.");
            }
        } else {
            $this->migrated[] = $filename;
        }
    }

    /**
     * @return array
     */
    public function getMigrated()
    {
        return $this->migrated;
    }

    /**
     * @param MigrationInterface $migration
     */
    private function _execute(MigrationInterface $migration)
    {
        $migration->execute();
    }

    /**
     * @param MigrationInterface $migration
     */
    private function _revert(MigrationInterface $migration)
    {
        $migration->revert();
    }
}