<?php
require_once realpath(dirname(__FILE__)) . "/Action.class.php";
class Theme extends Action{
	public function __construct(){
		parent::__construct('theme'); 
	}
}