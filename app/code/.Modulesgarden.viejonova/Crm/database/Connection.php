<?php

namespace Modulesgarden\Crm\database;

use \Illuminate\Database\Capsule\Manager as DatabaseManager;

class Connection
{

    protected $db;
    private $connectionDetails;

    public function __construct()
    {
        require_once dirname(__DIR__) . '/vendor/autoload.php';
        $this->connectionDetails = include dirname(dirname(dirname(dirname(__DIR__)))) . '/etc/env.php';
        $this->db = new DatabaseManager;
        $this->db->addConnection(
                array
                    (
                    'driver' => 'mysql',
                    //fix me
                    'host' => $this->connectionDetails['db']['connection']['default']['host'],
                    'database' => $this->connectionDetails['db']['connection']['default']['dbname'],
                    'username' => $this->connectionDetails['db']['connection']['default']['username'],
                    'password' => $this->connectionDetails['db']['connection']['default']['password'],
                    'charset' => 'utf8',
                    'collation' => 'utf8_unicode_ci',
                    'prefix' => '',
        ));
        $this->db->setAsGlobal();
        $this->db->bootEloquent();    // we want to have connetion to DB from this point !
    }

    public function getDB()
    {
        return $this->db;
    }

}
