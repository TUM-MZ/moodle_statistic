<?php

/**
 * Description of user
 *
 * @author schlender
 */
class Module {

    protected static $instance;
    private $db;
    private $dbTable;
    private $course;
    private $forumId;

    protected function __construct() {
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
        $this->course = Course::getInstance();
        $this->dbTable = DB_TABLE_PREFIX . 'modules';
        $this->instance = [];
        $this->selectForumId();
    }

    
    private function selectForumId() {
        $sql = "SELECT * FROM " .
               $this->dbTable .
               " WHERE name LIKE 'forum' LIMIT 0,1";
        $query = $this->db->query($sql);
        $this->forumId = $query->fetch_object()->id;
    }
    
    public function getSumOfTypesAll() {
        $sql = "SELECT count(id) as count FROM " .
                $this->dbTable;
        $query = $this->db->query($sql);
        return $query->fetch_object()->count;
    }

    public function getSumOfTypesAvailable() {
        $sql = "SELECT count(id) as count FROM " .
                $this->dbTable .
                " WHERE visible = 1";
        $query = $this->db->query($sql);
        return $query->fetch_object()->count;
    }
    
    public function getForumId() {
        if (!isset($this->forumId)) {
            $this->selectForumId();
        }
        return $this->forumId;
    }
    
    /***************************************************************************
     * GLOBAL GETTER & SETTER
     */
    public function __get($name) {
        return $this->$name;
    }
    
    public function __set($name, $value) {
        $this->$name = $value;
    }
    
}
