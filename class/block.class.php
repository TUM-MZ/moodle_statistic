<?php

/**
 * Description of user
 *
 * @author
 */
class Block {

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
    
    public function init() {
        $this->db = DB::getInstance();
        $this->dbTable = DB_TABLE_PREFIX . 'block';
    }
    
    public function getSumOfAll() {
        $sql = "SELECT count(id) as count FROM $this->dbTable";
        $query = $this->db->query($sql);
        return $query->fetch_object()->count;
    }
    
    public function getSumOfAvailable() {
        $sql = "SELECT count(id) as count FROM $this->dbTable WHERE visible = 1";
        $query = $this->db->query($sql);
        return $query->fetch_object()->count;
    }

}
