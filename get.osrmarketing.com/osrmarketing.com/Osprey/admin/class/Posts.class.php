<?php
require_once realpath(dirname(__FILE__)) . "/Action.class.php";
class Posts extends Action{
	public function __construct(){
		parent::__construct('posts'); 
	}

    public function getList() {
        return $this->query("SELECT * FROM $this->table ORDER BY publication_date ASC");
    }

    public function getListByIdArray($id_array, int $page = 1, int $postsPerPage = 9) {
        $id_string = implode(',', $id_array);
        $offset = ($page - 1) * $postsPerPage;
        return $this->query("SELECT * FROM $this->table WHERE id IN ($id_string) AND publication_date <= NOW() ORDER BY publication_date ASC LIMIT $postsPerPage OFFSET $offset");
    }
  

    public function getListFront(int $page = 1, int $postsPerPage = 9) {
        $offset = ($page - 1) * $postsPerPage;
        return $this->query("SELECT * FROM $this->table WHERE status='1' AND publication_date <= NOW() ORDER BY publication_date ASC LIMIT $postsPerPage OFFSET $offset");
    }

    public function getTotalPosts() {
        return $this->query("SELECT * FROM $this->table WHERE status='1' AND publication_date <= NOW()")->rowCount();
    }
    
    public function getTotalPostsTags($id_array) {
        $id_string = implode(',', $id_array);
        return $this->query("SELECT * FROM $this->table WHERE id IN ($id_string) AND publication_date <= NOW()")->rowCount();
    }

    public function getBySlug($slug) {
        return $this->query("SELECT * FROM $this->table WHERE status='1' AND slug='$slug' AND publication_date <= NOW()")->fetch();
    }

}