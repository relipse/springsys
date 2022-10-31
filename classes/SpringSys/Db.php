<?php
/**
 * Db (Database)
 * - Load database with Config instance
 * - insert
 * - fetching
 *
 * Code Challenge
 * @author Jim A Kinsman <relipse@gmail.com>
 * @copyright 2022 Jim A Kinsman
 */
namespace SpringSys;
use \PDO;

class Db{
    public PDO $pdo;

    /**
     * New database object using config values
     * @param Config $cfg
     */
    public function __construct(Config $cfg = new Config()){
        //defaults
        $driver_options = $cfg->get('dbdriveroptions', [
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'",
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        $this->pdo = new \PDO($cfg->get('dbconnectionstring'), $cfg->get('dbuser'), $cfg->get('dbpass'), $driver_options);
    }

    /**
     * Insert into a table and return last insert id
     * @param $sql
     * @param $params
     * @return int|false last insert id
     */
    public function insert($sql, $params = []): int|false{
        $prep = $this->pdo->prepare($sql);
        $res = $prep->execute($params);
        if ($res) {
            return $this->pdo->lastInsertId();
        }else{
            return false;
        }
    }

    /**
     * Execute SQL
     * @param $sql
     * @param $params
     * @return bool
     */
    public function execute($sql, $params = []): bool{
        $prep = $this->pdo->prepare($sql);
        return $prep->execute($params);
    }

    /**
     * Fetch all
     * @param $sql
     * @param $params
     * @return array|false
     */
    public function fetchAll($sql, $params = []): array|false{
        $prep = $this->pdo->prepare($sql);
        $result = $prep->execute($params);
        if ($result){
            return $prep->fetchAll(PDO::FETCH_ASSOC);
        }else{
            return false;
        }
    }

    /**
     * Fetch first row, returning $prepared statement by reference
     * @param $sql
     * @param $params
     * @param $prep
     * @return array|false
     */
    public function fetchFirst($sql, $params = [], &$prep = null): array|false{
        $prep = $this->pdo->prepare($sql);
        $result = $prep->execute($params);
        if ($result){
            return $prep->fetch(PDO::FETCH_ASSOC);
        }else{
            return false;
        }
    }

    /**
     * Get current date in the database format
     * @return string
     */
    public function NOW(): string{
        return "'".date('Y-m-d H:i:s')."'";
    }
}
