<?php
namespace SpringSys;

class Company {
    protected Db $db;
    public function __construct(Db $db = new Db()){
        $this->db = $db;
    }
    public function add(string $name, string $street1, string $street2, string $city, string $state_province, string $zip) : int{
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

    public function getAll(): array|false{
        $sql = <<<EOD
SELECT c.name, street1, street2, city, state_province, zip, COUNT(e.id) AS num_employees from companies c LEFT join employees e ON e.company_id = c.id GROUP BY c.id; 
EOD;
        return $this->db->fetchAll($sql);
    }

    public function get(int $company_id): array|false {
        $sql = <<<EOD
SELECT id, name, street1, street2, city, state_province, zip FROM companies WHERE id = :id
EOD;
        $params = ['id'=>$company_id];
        $row = $this->db->fetchFirst($sql, $params);
        return $row;
    }

    public function addEmployee(int $companyid, string $firstname, string $middle, string $lastname): int{
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