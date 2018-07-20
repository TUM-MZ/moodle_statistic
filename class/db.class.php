<?php

require_once(__DIR__ . "/../../config.php");

class MySQLiResultAdapter {
    private $rows;
    private $num_rows;

    public function __construct($rows, $length=null) {
        $this->rows = $rows;
        if ($length == null)
            $this->num_rows = count($rows);
        else
            $this->num_rows = $length;
    }

    public function fetch_object() {
        return $this->rows;
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

        if (substr($query, 0, strlen("SELECT COUNT")) == "SELECT COUNT") {
            $result = $DB->count_records_sql($query);
            return new MySQLiResultAdapter([], $result);
        } else {
            $result = $DB->get_records_sql($query);
            return new MySQLiResultAdapter($result);
        }

    }
}
