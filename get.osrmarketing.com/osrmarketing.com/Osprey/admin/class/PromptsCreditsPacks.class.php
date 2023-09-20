<?php
require_once realpath(dirname(__FILE__)) . "/Action.class.php";
class PromptsCreditsPacks extends Action{
	public function __construct(){
		parent::__construct('prompts_credits_packs'); 
	}  

    public function getListByIdCreditPack(int $id_credits_pack) {
        return $this->query("SELECT * FROM $this->table WHERE id_credits_pack='$id_credits_pack'");
    }  

    public function getListByIdPrompt(int $id_prompt) {
        return $this->query("SELECT * FROM $this->table WHERE id_prompt='$id_prompt'");
    }  

    public function getAllFreePrompts() {
        return $this->query("SELECT prompts.id, prompts.name, prompts.image FROM prompts LEFT JOIN prompts_credits_packs ON prompts.id = prompts_credits_packs.id_prompt WHERE prompts_credits_packs.id_prompt IS NULL");
    }
    public function getAllFreePromptsByCreditPackId(int $id_credits_pack) {
        return $this->query("SELECT prompts.id, prompts.name, prompts.image, credits_packs.tier FROM prompts JOIN prompts_credits_packs ON prompts.id = prompts_credits_packs.id_prompt JOIN credits_packs ON prompts_credits_packs.id_credits_pack = credits_packs.id WHERE prompts_credits_packs.id_credits_pack = $id_credits_pack");
    }  

    public function addPromptCreditPack(?int $id_prompt, int $id_credits_pack) {
        return $this->query("INSERT INTO $this->table (`id_prompt`, `id_credits_pack`) VALUES ('$id_prompt','$id_credits_pack')");
    }

    public function deleteCreditPack(?int $id_credits_pack) {
        return $this->query("DELETE FROM $this->table WHERE id_credits_pack = '$id_credits_pack'");
    }    

}