<?php


namespace Igor\Api\Models;

use \PDO;

require_once (__DIR__."/../DB/db-config.php");

class Model
{
    protected \PDO $conn;
}