<?php
require_once realpath(dirname(__FILE__)) . "/Action.class.php";
class Prompts extends Action{
	public function __construct(){
		parent::__construct('prompts'); 
	}

    public function getList(?string $order = NULL, int $limit = 999999) {
        return $this->query("SELECT * FROM $this->table ORDER BY item_order ASC LIMIT $limit");
    }

    public function getListByIDS($ids) {
        return $this->query("SELECT * FROM $this->table WHERE id IN ($ids)");
    }

    public function getListFront() {
        return $this->query("SELECT id, name, slug, image, expert FROM $this->table WHERE status='1' ORDER BY item_order ASC");
    }

    public function getMaxOrder() {
        return $this->query("SELECT MAX(item_order) AS max_order FROM $this->table")->Fetch();
    }

    public function getBySlug(?string $slug) {
        return $this->query("SELECT * FROM $this->table WHERE slug='$slug' AND status='1' LIMIT 1")->Fetch();
    }  

    public function checkVipByIdPrompt(?int $id_prompt) {
        return $this->query("SELECT * FROM prompts_credits_packs WHERE id_prompt = $id_prompt");
    }     
     
}