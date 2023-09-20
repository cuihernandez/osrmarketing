<?php
require_once realpath(dirname(__FILE__)) . "/Action.class.php";
class Seo extends Action{
	public function __construct(){
		parent::__construct('seo'); 
	}
}