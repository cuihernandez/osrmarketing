<?php
require_once realpath(dirname(__FILE__)) . "/Action.class.php";
class PostsTags extends Action{
	public function __construct(){
		parent::__construct('posts_tags'); 
	}  

    public function getListByIdTag(int $id_tag) {
        return $this->query("SELECT * FROM $this->table WHERE id_tag='$id_tag'");
    } 

    public function getListByIdArray(array $id_array) {
        $id_string = implode(',', $id_array);
        return $this->query("SELECT * FROM $this->table WHERE id IN ($id_string)");
    }

    public function getListByIdPost(int $id_post) {
        return $this->query("SELECT * FROM $this->table WHERE id_post='$id_post'");
    }  

    public function addPostTag(?int $id_post, int $id_tag) {
        return $this->query("INSERT INTO $this->table (`id_post`, `id_tag`) VALUES ('$id_post','$id_tag')");
    }

    public function deletepostTag(?int $id_post) {
        return $this->query("DELETE FROM $this->table WHERE id_post = '$id_post'");
    }    

}