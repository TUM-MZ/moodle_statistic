<?php

/**
 * Description of semester
 *
 * @author schlender
 */
class Semester {

    protected static $instance;
    private $db;
    private $all;

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
        $this->all = [];
    }

    public function addToAll($semester) {
        if (empty($this->all)) {
            $this->all[$semester->id] = $semester;
        } else if (!isset($this->all[$semester->id])) {
            $this->all[$semester->id] = $semester;
        }
        arsort($this->all);
    }
    
    public function getAll($reverse = false) {
        if ($reverse) {
            return array_reverse($this->all);
        } else {
            return $this->all;
        }
    }
    public function get($id) {
        if ($this->all[$id]) {
            return $this->all[$id];
        } else {
            return false;
        }
    }
}
