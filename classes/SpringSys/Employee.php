<?php
/**
 * Employee (Employees)
 * - fetch all (with company)
 *
 * Code Challenge
 * @author Jim A Kinsman <relipse@gmail.com>
 * @copyright 2022 Jim A Kinsman
 */

namespace SpringSys;

class Employee {
     protected Db $db;

     /**
      * Constructor
      * @param Db $db
      */
     public function __construct(Db $db = new Db()){
         $this->db = $db;
     }

     /**
      * Get all employees with their company
      * @return array|false
      */
     public function getAll(): array|false{
         $sql = <<<EOD
SELECT e.firstname, e.middlename, e.lastname, c.name AS company FROM employees e LEFT JOIN companies c ON  c.id = e.company_id; 
EOD;
         return $this->db->fetchAll($sql);
     }
}
