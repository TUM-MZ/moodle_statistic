<?php

require_once(__DIR__ . "/../../config.php");

class MySQLiResultAdapter {
    private $rows;
    private $rowsiter;
    public $num_rows;

    public function __construct($rows, $length=null) {
        $this->rows = new ArrayObject($rows);
        $this->rowsiter = $this->rows->getIterator();
        if ($length == null)
            $this->num_rows = count($rows);
        else
            $this->num_rows = $length;
    }

    public function fetch_object() {
        if ($this->rowsiter->valid()) {
            $val = $this->rowsiter->current();
            $this->rowsiter->next();
            return $val;
        } else {
            return null;
        }

    }
}

class MySQLiCountRowAdapter {
    public $count;

    public function __construct($count) {
        $this->count = $count;
    }
}

class MySQLiCountAdapter {
    private $row_count;

    public function __construct($count) {
        $this->row_count = $count;
    }

    public function fetch_object() {
        return new MySQLiCountRowAdapter($this->row_count);
    }
}

class DB {

    protected static $instance;
    // getInstance method
    public static function getInstance() {

        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function query($query) {
        global $DB;

        if (substr(strtoupper($query), 0, strlen("SELECT COUNT")) == "SELECT COUNT") {
            $result = $DB->count_records_sql($query);
            return new MySQLiCountAdapter($result);
        } else {
            $result = $DB->get_records_sql($query);
            return new MySQLiResultAdapter($result);
        }

    }
}
