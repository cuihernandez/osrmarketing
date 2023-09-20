<?php
require_once realpath(dirname(__FILE__)) . "/Action.class.php";
class Languages extends Action{
	public function __construct(){
		parent::__construct('languages'); 
	}

    public function updateDefaultAllOff() {
        return $this->query("UPDATE $this->table SET isDefault = 0");
    }

    public function getList(?string $order = NULL, int $limit = 999999) {
        return $this->query("SELECT * FROM $this->table ORDER BY item_order ASC LIMIT $limit");
    }

    public function getListDefault() {
        return $this->query("SELECT * FROM $this->table WHERE isDefault='1' LIMIT 1")->Fetch();
    }

    public function getMaxOrder() {
        return $this->query("SELECT MAX(item_order) AS max_order FROM $this->table")->Fetch();
    }    

}