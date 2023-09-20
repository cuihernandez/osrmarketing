<?php
if (version_compare(PHP_VERSION, '7.4.0', '<')) {
    die('This script requires PHP 7.4.0 or higher.');
}
class Db {
    private $type = 'mysql';
    private $port = 3306;
    protected $conn;
    private $host;
    private $user;
    private $password;
    private $dbname;
    protected $pdo;

    function query(string $sql) {
        $r = $this->pdo->query($sql);
        if ($r) {
            $r->setFetchMode(PDO::FETCH_OBJ);
        }
        return $r;
    }

    public function __construct() {
        require realpath(dirname(__FILE__)) . "/config.php";

        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
        $this->dbname = $dbname;
        $this->init();
    }

    private function init() {
        try {
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::ATTR_PERSISTENT         => true
            ];
            $this->pdo = new PDO($this->type . ":host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->dbname . ";charset=utf8mb4", $this->user, $this->password, $options);
        } catch (PDOException $e) {
            die("Connection failed, check your credentials.");
        }
    }

    public function getLastInsertId() {
        $stmt = $this->query("SELECT LAST_INSERT_ID()");
        $lastId = $stmt->fetch(PDO::FETCH_NUM);
        return $lastId[0];
    }

    public function getConn() {
        return $this->pdo;
    }

    /**
     * Close connection database
     **/
    public function __destruct() {
        $this->pdo = null;
    }
}
?>
