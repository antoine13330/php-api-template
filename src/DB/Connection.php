<?php


namespace AntoineD\Api\DB;

use \PDO;

class Connection
{
    public \PDO $conn;

    public function connect()
    {
        $conn = new \PDO('pgsql:host=localhost;dbname=webservice', 'postgres', 'admin');
        if($conn){
            $conn->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
            return $conn;
        }else{
            return false;
        }
    }
}