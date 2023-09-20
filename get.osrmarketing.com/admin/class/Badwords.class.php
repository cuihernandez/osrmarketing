<?php
require_once realpath(dirname(__FILE__)) . "/Action.class.php";
class Badwords extends Action{
	public function __construct(){
		parent::__construct('badwords'); 
	}

    public function getList() {
        return $this->query("SELECT * FROM $this->table");
    }

}