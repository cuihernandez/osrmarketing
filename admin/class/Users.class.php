<?php
require_once realpath(dirname(__FILE__)) . "/Action.class.php";
class Users extends Action{
	public function __construct(){
		parent::__construct('users'); 
	}

    public function getList(?string $order = NULL, int $limit = 999999) {
        return $this->query("SELECT * FROM $this->table ORDER BY item_order ASC LIMIT $limit");
    }

    public function updateToken(int $id, string $token) {
        return $this->query("UPDATE $this->table SET token = '$token' WHERE id='$id'");
    }     

    public function getData(string $email, string $password) {
        return $this->query("SELECT * FROM $this->table WHERE email='$email' AND password='$password' LIMIT 1")->Fetch();
    }

    public function getEmail(string $email) {
        return $this->query("SELECT * FROM $this->table WHERE email='$email' LIMIT 1")->Fetch();
    }

    public function getMaxOrder() {
        return $this->query("SELECT MAX(item_order) AS max_order FROM $this->table")->Fetch();
    }    
}