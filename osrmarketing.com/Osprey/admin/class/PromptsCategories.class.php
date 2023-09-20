<?php
require_once realpath(dirname(__FILE__)) . "/Action.class.php";
class PromptsCategories extends Action{
	public function __construct(){
		parent::__construct('prompts_categories'); 
	}  

    public function getListByIdCategory(int $id_category) {
        return $this->query("SELECT * FROM $this->table WHERE id_category='$id_category'");
    }  

    public function getListByIdPrompt(int $id_prompt) {
        return $this->query("SELECT * FROM $this->table WHERE id_prompt='$id_prompt'");
    }  

    public function addPromptCategory(?int $id_prompt, int $id_category) {
        return $this->query("INSERT INTO $this->table (`id_prompt`, `id_category`) VALUES ('$id_prompt','$id_category')");
    }

    public function deletePromptCategory(?int $id_prompt) {
        return $this->query("DELETE FROM $this->table WHERE id_prompt = '$id_prompt'");
    }    

}