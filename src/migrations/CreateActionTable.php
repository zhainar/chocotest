<?php
/**
 * Created by PhpStorm.
 * User: uzer
 * Date: 15.08.2018
 * Time: 0:04
 */

namespace migrations;


use core\base\AbstractMigration;
use core\Connection;

class CreateActionTable extends AbstractMigration
{
    public function execute()
    {
        $sql = <<<EOL
create table action (
id integer unsigned primary key not null,
name varchar(255) not null,
start_date integer unsigned not null,
end_date integer unsigned not null,
status enum('On', 'Off') not null
) ENGINE = INNODB
EOL;
        Connection::instance()->query($sql);
    }
    
    public function revert()
    {
        $sql = <<<EOL
drop table action
EOL;
        Connection::instance()->query($sql);
    }
}