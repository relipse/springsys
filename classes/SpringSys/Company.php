<?php
/**
 * Company (Companies)
 * - add
 * - get
 * - fetch all
 * - add employee to company
 *
 * Code Challenge
 * @author Jim A Kinsman <relipse@gmail.com>
 * @copyright 2022 Jim A Kinsman
 */

namespace SpringSys;

class Company {
    protected Db $db;

    /**
     * Constructor
     * @param Db $db
     */
    public function __construct(Db $db = new Db()){
        $this->db = $db;
    }

    /**
     * Add a company to database, stripping out tags etc
     * @param string $name
     * @param string $street1
     * @param string $street2
     * @param string $city
     * @param string $state_province
     * @param string $zip
     * @return int
     */
    public function add(string $name, string $street1, string $street2, string $city, string $state_province, string $zip) : int{
        $params = func_get_args();
        foreach($params as $param){
            if ($this->hasURL($param)){
                //may not contain urls
                throw new \Exception('You may not submit URLS');
            }
        }
        $sql = <<<EOD
INSERT INTO companies (name, street1, street2, city, state_province, zip) 
                VALUES(:name, :street1, :street2, :city, :state_province, :zip)
EOD;
        //yeah, we are not going to allow html tags in names and addresses
        $ins_ary = ['name'=>strip_tags(trim($name)), 'street1'=>strip_tags(trim($street1)), 'street2'=>strip_tags(trim($street2)),
            'city'=>strip_tags(trim($city)), 'state_province'=>strip_tags(trim($state_province)), 'zip'=>strip_tags(trim($zip))];
        $insert_id = $this->db->insert($sql, $ins_ary);
        if ($insert_id !== false){
            return $insert_id;
        }else{
            return -1;
        }
    }

    /**
     * Delete company (and all employees) with
     * given id
     * @param int $id
     * @return bool
     */
    public function remove(int $id): bool{
        $sql = <<<EOD
DELETE FROM companies WHERE id = :company_id;
EOD;
        $sql2 = <<<EOD
DELETE FROM employees WHERE company_id = :company_id;
EOD;

        $res1 = $this->db->execute($sql, ['company_id'=>$id]);
        $res2 = $this->db->execute($sql2, ['company_id'=> $id]);
        return $res1 && $res2;
    }

    /**
     * Get all companies and num employees
     * @return array|false
     */
    public function getAll(): array|false{
        $sql = <<<EOD
SELECT c.id, c.name, street1, street2, city, state_province, zip, COUNT(e.id) AS num_employees from companies c LEFT join employees e ON e.company_id = c.id GROUP BY c.id; 
EOD;
        return $this->db->fetchAll($sql);
    }

    /**
     * Get one company
     * @param int $company_id
     * @return array|false
     */
    public function get(int $company_id): array|false {
        $sql = <<<EOD
SELECT id, name, street1, street2, city, state_province, zip FROM companies WHERE id = :id
EOD;
        $params = ['id'=>$company_id];
        $row = $this->db->fetchFirst($sql, $params);
        return $row;
    }

    /**
     * Count the number of employees in this company
     * @param int $company_id
     * @return int
     */
    public function countEmployees(int $company_id) : int {
        $sql = <<<EOD
SELECT COUNT(id) AS num_employees FROM employees WHERE employees.company_id = :company_id
EOD;
        $params = ['company_id'=>$company_id];
        $row = $this->db->fetchFirst($sql, $params);
        if ($row){
            return $row['num_employees'];
        }else{
            return -1;
        }
    }


    /**
     * Does this string contain a URL (we don't want spammers putting URLs in our database)
     * @param string $string
     * @return bool
     */
    public function hasURL(string $string): bool{
        return strpos($string, '://') !== false;
    }

    /**
     * Add employee to company
     * @param int $companyid
     * @param string $firstname
     * @param string $middle
     * @param string $lastname
     * @return int
     */
    public function addEmployee(int $companyid, string $firstname, string $middle, string $lastname): int{
        $params = func_get_args();
        foreach($params as $param){
            if ($this->hasURL($param)){
                //may not contain urls
                throw new \Exception('You may not submit URLS');
            }
        }
        $sql = <<<EOD
INSERT INTO employees(company_id, firstname, middlename, lastname)
VALUES(:companyid, :firstname, :middlename, :lastname);
EOD;
        $ins_ary = ['companyid'=>$companyid,
            'firstname'=>strip_tags(trim($firstname)),
            'middlename'=>strip_tags(trim($middle)),
            'lastname'=>strip_tags(trim($lastname))];
        $insert_id = $this->db->insert($sql, $ins_ary);
        if ($insert_id !== false){
            return $insert_id;
        }else{
            return -1;
        }
    }
}