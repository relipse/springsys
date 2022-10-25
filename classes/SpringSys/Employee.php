<?php
 namespace SpringSys;

 class Employee {
     protected Db $db;
     public function __construct(Db $db = new Db()){
         $this->db = $db;
     }

     public function getAll(): array|false{
         $sql = <<<EOD
SELECT e.firstname, e.middlename, e.lastname, c.name AS company FROM employees e LEFT JOIN companies c ON  c.id = e.company_id; 
EOD;
         return $this->db->fetchAll($sql);
     }
 }
