<?php

namespace App\Services;

use PDO;
use Dotenv\Dotenv;
use PDOException;

class DatabaseService
{
    protected $dbConnection;

    public function __construct()
    {
        $dotenv = Dotenv::createImmutable(base_path());
        $dotenv->load();

        $dbPath = $_ENV['DB_PATH'];

        try {
            $this->dbConnection = new PDO('sqlite:' . base_path($dbPath));
            $this->dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new \Exception("Error connecting to the database:" . $e->getMessage());
        }
    }

    public function getConnection()
    {
        return $this->dbConnection;
    }
}
