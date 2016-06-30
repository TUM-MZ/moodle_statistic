<?php

class DB extends mysqli {

    protected static $instance;
    private $host;
    private $dbuser;
    private $dbpass;
    private $dbschema;
    private $dbport;
    private $dbsock;
    
    protected function __construct() {

        // turn of error reporting
        mysqli_report(MYSQLI_REPORT_OFF);
        
        // init and check for given config
        $this->initCheck();
        
        // connect to database
        parent::__construct($this->host, $this->dbuser, $this->dbpass, $this->dbschema, $this->dbport, $this->dbsock);
        parent::set_charset("utf8");
        
        // check if a connection established
        if (mysqli_connect_errno()) {
            throw new exception(mysqli_connect_error(), mysqli_connect_errno());
        }
    }
    
    private function initCheck() {
        global $CFG;
        
        # THE $CFG is given by moodle
        if (isset($CFG)) {
            $this->host = $CFG->dbhost;
            $this->dbuser = $CFG->dbuser;
            $this->dbpass = $CFG->dbpass;
            $this->dbschema = $CFG->dbname;
        } else {
            $this->host = 'localhost';
            $this->dbuser = 'root';
            $this->dbpass = 'root';
            $this->dbschema = 'moodle';
        }
        
        $this->dbport = 3306;
        $this->dbsock = false;
    }
    // getInstance method
    public static function getInstance() {

        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }
    
    public function query($query) {
        if (!$this->real_query($query)) {
            throw new exception($this->error, $this->errno);
        }

        $result = new mysqli_result($this);
        return $result;
    }

    public function prepare($query) {
        $stmt = new mysqli_stmt($this, $query);
        return $stmt;
    }
    
}
