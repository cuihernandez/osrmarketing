<?php
require_once realpath(dirname(__FILE__)) . "/Action.class.php";
class Update extends Action{

    public function updateStatus(?string $table, ?int $status, ?int $id) {
        return $this->query("UPDATE $table SET status = $status WHERE id=$id");
    }

    public function updateOrder(?string $table, ?int $item_order, ?int $id) {
        return $this->query("UPDATE $table SET item_order = $item_order WHERE id=$id");
    }

    public function updateDefaultLanguage(?string $table, ?int $id){
        $this->query("UPDATE $table SET isDefault = 0");
        return $this->query("UPDATE $table SET isDefault = 1 WHERE id=$id");
    }


}