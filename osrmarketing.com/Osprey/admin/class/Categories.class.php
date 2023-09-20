<?php
require_once realpath(dirname(__FILE__)) . "/Action.class.php";
class Categories extends Action{
	public function __construct(){
		parent::__construct('categories'); 
	}

    public function getList(?string $order = NULL, int $limit = 999999) {
        return $this->query("SELECT * FROM $this->table ORDER BY item_order ASC LIMIT $limit");
    }

    public function getListFront() {
        return $this->query("SELECT c.*, COUNT(pc.id_prompt) as amount_prompt FROM $this->table as c LEFT JOIN prompts_categories as pc ON c.id = pc.id_category WHERE c.status='1' GROUP BY c.id ORDER BY c.item_order ASC");
    } 
    
    public function getMaxOrder() {
        return $this->query("SELECT MAX(item_order) AS max_order FROM $this->table")->Fetch();
    }

    public function getListFrontLimit($limit) {
        return $this->query("SELECT c.*, COUNT(pc.id_prompt) as amount_prompt FROM $this->table as c LEFT JOIN prompts_categories as pc ON c.id = pc.id_category WHERE c.status='1' GROUP BY c.id ORDER BY c.item_order ASC LIMIT $limit");
    }  

    public function getBySlug(?string $slug) {
        return $this->query("SELECT * FROM $this->table WHERE slug='$slug'")->Fetch();
    }    
}