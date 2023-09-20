<?php
require_once realpath(dirname(__FILE__)) . "/Action.class.php";
class Settings extends Action{
	public function __construct(){
		parent::__construct('settings'); 
	}
}