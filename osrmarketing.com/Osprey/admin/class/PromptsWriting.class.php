<?php
require_once realpath(dirname(__FILE__)) . "/Action.class.php";
class PromptsWriting extends Action{
	public function __construct(){
		parent::__construct('prompts_writing'); 
	}

    public function getList(?string $order = NULL, int $limit = 999999) {
        return $this->query("SELECT * FROM $this->table ORDER BY item_order ASC LIMIT $limit");
    }

    public function getListFront(?string $order = NULL, int $limit = 999999) {
        return $this->query("SELECT * FROM $this->table $order WHERE status='1' ORDER BY item_order ASC");
    }   

    public function getMaxOrder() {
        return $this->query("SELECT MAX(item_order) AS max_order FROM $this->table")->Fetch();
    }    
}