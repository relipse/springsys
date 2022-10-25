<?php
namespace SpringSys;
use \PDO;

class Db{
    public PDO $pdo;
    public function __construct(Config $cfg = new Config()){
        //defaults
        $driver_options = $cfg->get('dbdriveroptions', [
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'",
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        $this->pdo = new \PDO($cfg->get('dbconnectionstring'), $cfg->get('dbuser'), $cfg->get('dbpass'), $driver_options);
    }

    public function insert($sql, $params = []): int|false{
        $prep = $this->pdo->prepare($sql);
        $res = $prep->execute($params);
        if ($res) {
            return $this->pdo->lastInsertId();
        }else{
            return false;
        }
    }

    public function execute($sql, $params = []): bool{
        $prep = $this->pdo->prepare($sql);
        return $prep->execute($params);
    }

    public function fetchAll($sql, $params = []): array|false{
        $prep = $this->pdo->prepare($sql);
        $result = $prep->execute($params);
        if ($result){
            return $prep->fetchAll(PDO::FETCH_ASSOC);
        }else{
            return false;
        }
    }

    public function fetchFirst($sql, $params = [], &$prep = null): array|false{
        $prep = $this->pdo->prepare($sql);
        $result = $prep->execute($params);
        if ($result){
            return $prep->fetch(PDO::FETCH_ASSOC);
        }else{
            return false;
        }
    }

    public function NOW(): string{
        return "'".date('Y-m-d H:i:s')."'";
    }
}
