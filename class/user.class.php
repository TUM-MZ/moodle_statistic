<?php

/**
 * Description of user
 *
 * @author schlender
 */
class User {

    protected static $instance;
    private $db;
    private $dbTable;

    public function __construct() {
        $this->init();
    }

    // getInstance method
    public static function getInstance() {

        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }
    
    private function init() {
        $this->db = DB::getInstance();
        $this->dbTable = DB_TABLE_PREFIX . 'user';
    }
    
    public function getSumOfRegistered() {
        $sql = "SELECT count(id) as count FROM " .
               $this->dbTable;
        $query = $this->db->query($sql);
        return $query->fetch_object()->count;
    }
    
    public function getSumOfActive() {
        $sql = "SELECT count(id) as count FROM " .
               $this->dbTable .
               " WHERE deleted = 0";
        $query = $this->db->query($sql);
        return $query->fetch_object()->count;
    }

}
