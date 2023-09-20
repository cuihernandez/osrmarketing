<?php
require_once "Db.class.php"; //Database connection class

class Action extends Db {
    protected $array;
    protected $table;
    protected $primaryKey;
    private $debugMode;
    private $post;
    private $pdoObject;
    public $queryStr;
    protected $view;
    protected $getDataFromView;

	function __construct(string $table = "", string $view = ""){
        parent::__construct();
        $this->debugMode = false;
        $this->array = array();
        $this->primaryKey = "id";
        $this->table = $table;
        $this->view = $view;
        $this->getDataFromView = false;
        return $this;
	}

	/**
	* Start / End debug mode
	* @param boolean $debug
	*
	*/
    public function debug(bool $debug = true){
        $this->debugMode = $debug;
    }


	/**
	* Add to specified table
	*
	* @param Mixed $array
	* @return boolean
	*/
	public function add(): bool {
		$this->prepareData();
		$qr = "INSERT INTO $this->table(";
		$tam = sizeof($this->array);
		$penultimo = $tam-1;

		$i=0;
		foreach ($this->array as $c=>$v){
			if(is_array($v)){
				foreach ($v as $e=>$z) {
						$qr .= "`$e`".", ";
						$i++;
				}
			}else{
				if($c != "" && $v != ""){
					$qr .= "`$c`".", ";

					$i++;
				}
			}
		}

		$qr .= ") VALUES(";
		//Remove the last string
		$qr = str_replace(", )", ")", $qr);


		$i=0;
		foreach ($this->array as $c=>$v){
			
			if(is_array($v)){
				foreach ($v as $e=>$x) {
					$x = addslashes($x);
						if(trim($x) != "" && trim($x) != "*")
							$qr .= "'$x'".", ";
						else
							$qr .= "NULL, ";
						$i++;
				}
			} else{
				if($c != "" && trim($v) != "" && trim($v) != "*"){
					$qr .= "'$v',";
					$i++;
				} elseif($c != "" && trim($v) == ""){
					$qr .= 'NULL, ';
				}
			}
		}
		$qr .= ")";

		//Remove the last comma
		$qr = str_replace(", )", ")", $qr);
		$this->queryStr = $qr;
		if(!$this->debugMode)
			    return $this->pdo->exec($qr);

		else{
			die("Query String: ".$this->queryStr);
		}
	}

	/**
	* Changes the registration of the given element in the table
	*
	* @param Int $id
	* @return Bool
	*/
	public function update(int $id): bool {
	    $this->prepareUpdate();
	    $qr = "UPDATE $this->table SET ";

	    $fieldsToUpdate = [];

	    foreach ($this->array as $c) {
	        foreach ($c as $d => $v) {
	            if ($v === null || $v === 'null') {
	                $fieldsToUpdate[] = "$d = NULL";
	            } elseif ($v !== '') {
	                $fieldsToUpdate[] = "$d = '$v'";
	            }
	        }
	    }

	    $qr .= implode(', ', $fieldsToUpdate);
	    $qr .= " WHERE $this->primaryKey = '$id' ";

	    $this->queryStr = $qr;

	    try {
	        if (!$this->debugMode) {
	            $this->pdo->exec($qr);
	            return true;
	        } else {
	            die("Query String: " . $this->queryStr);
	        }
	    } catch (Exception $e) {
	        return false;
	    }
	}



	/**
	* Deletes the registration of an element
	*
	* @param Int $id
	*/
	 public function delete(int $id): bool {
		if(!is_array($id)){
			if(!$this->debugMode)
				return $this->pdo->exec("DELETE FROM $this->table WHERE $this->primaryKey = '$id' LIMIT 1");
			else
				echo "Physically delete the item $id da table: $this->table<br />";
		}
		else{
			//If it's an array, delete them all at once
			$ids = "'0'";
			foreach ($id as $i){
				$ids .= ", '$i' ";
			}
			if($this->debugMode)
				return $this->pdo->exec("DELETE FROM  $this->table WHERE $this->primaryKey IN($ids)");
			else{
				echo "Physically delete ".sizeof($ids). "item table $this->table. The items are:";
				$is = "";
				foreach ($ids as $f) {
					$is .=  "$f, ";
				}
				$is .= "END";
				echo str_replace(", END", "")."<br />";
				return true;
			}
		}
		return false;
	}


	/**
	 * Returns the data of an element of the table.
	 *
	 * @param Int $id
	 * @return Array
	 */
		public function get(int $id) {
			if($this->debugMode)
				echo "Pegando os dados de $id da table $this->table<br />";
			if(!$this->getDataFromView){
				return $this->query("SELECT * FROM $this->table WHERE $this->primaryKey = '$id' LIMIT 1")->fetch();
			}else{
				return $this->query("SELECT * FROM $this->view WHERE $this->primaryKey = '$id' LIMIT 1")->fetch();
			}
		}

	/**
	 * List the elements of a table in the order given
	 *
	 * @return Query Id
	 */
	public function getList() {
		if(!$this->getDataFromView)
		$qr = "SELECT * FROM $this->table";
		else
		$qr = "SELECT * FROM $this->view";

		if($order != null)
			$qr.= " ORDER BY $order ";

		if($this->debugMode){
			echo "LISTING THE DATA OF $this->table<br />";
			echo "QUERY: <strong>$qr</strong><br />";
		}

		return $this->query($qr." LIMIT $limit");
	}


	/**
	 * Prepare the $_POST array by applying some standard filters and mapping them with existing fields in the database table
	 * @param optional boolean $update default=false
	 */
	public function prepareData(bool $update = false): void {
	    $campos = array();
	    $fields = $this->descFields();
	    foreach($fields as $f){
	        $campos[] = $f->Field;
	        if($this->debugMode)
	            echo "<br />Field (DB): ".$f->Field;
	    }

	    $i=0;
	    foreach ($campos as $c) {
	        if (array_key_exists($c, $_POST)) {
	            $v = $_POST[$c];
	            if($update){
	                if(trim($v) == "" && $c != 'image') { 
	                    $this->array[] = array($c => '');
	                } elseif (trim($v) == "*") {
	                    $this->array[] = array($c => null);
	                } else {
	                    $this->array[] = array($c => ($v));
	                }
	            } else {
	                $this->array[] = array($c => ($v));
	                $i++;
	            }
	            if($this->debugMode){
	                echo "<br />Combines: $c => $v";
	            }
	        } else {
	            if($update && $c != 'image') { 
	                $this->array[] = array($c => '');
	            }
	        }
	    }

	    if($this->debugMode){
	        echo "<br /><br />Data coming from the form: ";
	        foreach ($_POST as $p => $v){
	            echo "<br />$p => $v";
	        }

	        echo "<h3>generated array: </h3>";
	        foreach ($this->array as $e => $v){
	            foreach ($v as $d=>$f){
	                echo "$d => $f<br />";
	            }
	        }
	    }
	}


	/**
	 * Prepare $_POST array for data change
     */
	public function prepareUpdate(): void {
	    if($this->debugMode)
	    echo "<h2>Preparing to update.</h2>";
	    $this->prepareData(true);
	}


	public function prepareDelete(): bool {
		return true;
	}

	/**
	 * Describes table fields
	 *
	 * @return array
     */
	public function descFields(){
		return $this->query("DESC $this->table");
	}

	/**
	 * Run a query on the database
	 * @param String $qr
	 * @return PDOObject (ou booleano)
     */
	public function execute($qr){
		$this->queryStr = $qr;

		if(eregi("^SELECT", $qr)){
			$r = $this->query($qr);
			$this->pdoObject = $r;
		}
		else{
			$r = $this->pdo->exec($qr);
			$this->pdoObject = $r;
		}
		return $r;
	}


	/**
	 * Returns the number of lines of a PDO object
	 * @param optional PDOStatement $obj
	 *
	 * @return int
     	*/
	 public function linhas(?PDOStatement $obj = null): int {
		return $obj == null ? $this->pdoObject->rowCount() : $obj->rowCount();
	}

	/**
	 * Returns the ID generated by the last insertion into the database
	 *
	 * @return int
	 */
	public function getLastInsertId(): int {
	    return $this->pdo->lastInsertId();
	}


	/**
	 * Returns an array (one-dimensional) with the row of a given query
	 *
	 * @return array
     	*/
	public function getArray(): array {
		if($this->pdoObject)
			return $this->pdoObject->fetch();
		return array();
	}


}