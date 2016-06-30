<?php

/**
 * Description of user
 *
 * @author schlender
 */
class Forum {

    protected static $instance;
    private $db;
    private $dbTable, $dbTableDiscussion;
    private $course;
    private $all, $allCat;
    private $sumOfCourse, $sumOfCourseCat;
    private $sumOfAll, $sumOfAllCat;
    private $sumOfActiveCourse, $sumOfActiveCourseCat;
    private $sumOfXtraCourseCat;
    private $sumOfActiveForum, $sumOfActiveForumCat;
    
    protected function __construct($course) {
        $this->course = $course;
        $this->init();
    }

    /**
     * getInstance method
     */
    public static function getInstance($course) {
        
        if (!self::$instance) {
            self::$instance = new self($course);
        }

        return self::$instance;
    }
    
    /**
     * initialize the default settings
     */
    private function init() {
        $this->db = DB::getInstance();
        $this->dbTable = DB_TABLE_PREFIX . 'forum';
        $this->dbTableDiscussion = DB_TABLE_PREFIX . 'forum_discussions';
    }
    
    /**
     * select all forum, independent of posts and categories
     */
    private function selectAllForum() {
        // exclude the course id 1, because it's the default course for central
        // news forum etc.
        $sql = "SELECT *, count(*) as sumOf FROM ".
                $this->dbTable .
                " WHERE course > 1" .
                " GROUP BY course ORDER BY course ASC";
        $query = $this->db->query($sql);
        
        $this->all = [];
        $this->sumOfCourse = $query->num_rows;
        $this->sumOfAll = 0;
        while ($row = $query->fetch_object()) {
            $this->all[] = $row;
            $this->sumOfAll += $row->sumOf;
        }
    }
    
    /**
     * select all forum which are in setted categories
     */
    private function selectAllForumCat() {
        // exclude the course id 1, because it's the default course for central
        // news forum etc.
        $sql = "SELECT *, count(*) as sumOf FROM ".
                $this->dbTable .
                " WHERE course > 1" .
                " AND course IN (" . $this->course->getIDsString(). ")" .
                " GROUP BY course ORDER BY course ASC";
        $query = $this->db->query($sql);
        
        $this->allCat = [];
        $this->sumOfCourseCat = $query->num_rows;
        $this->sumOfAllCat = 0;
        while ($row = $query->fetch_object()) {
            $this->allCat[] = $row;
            $this->sumOfAllCat += $row->sumOf;
        }
    }
    
    /**
     * select all forum with minimum one post
     */
    private function selectActiveForum() {
        $sql = "SELECT DISTINCT(forum) FROM " .
                $this->dbTableDiscussion . ";";
        $query = $this->db->query($sql);
        if ($query->num_rows) {
            $this->sumOfActiveForum = $query->num_rows;
        } else {
            $this->sumOfActiveForum = 0;
        }
    }
    
    /**
     * select forum with minimum one post, which are in setted categories
     */
    private function selectActiveForumCat() {
        $sql = "SELECT DISTINCT(forum) FROM " .
                $this->dbTableDiscussion .
                " WHERE course IN (" . $this->course->getIDsString(). ");";
        $query = $this->db->query($sql);
        if ($query->num_rows) {
            $this->sumOfActiveForumCat = $query->num_rows;
        } else {
            $this->sumOfActiveForumCat = 0;
        }
    }
    
    /**
     * select all courses with active forum (minimum one post)
     */
    private function selectActiveCourse() {
        $sql = "SELECT DISTINCT(course) FROM " .
                $this->dbTableDiscussion . ";";
        $query = $this->db->query($sql);
        if ($query->num_rows) {
            $this->sumOfActiveCourse = $query->num_rows;
        } else {
            $this->sumOfActiveCourse = 0;
        }
    }
    
    /**
     * select course with active forum (minimum one post), which are in setted
     * categories
     */
    private function selectActiveCourseCat() {
        $sql = "SELECT DISTINCT(course) FROM " .
                $this->dbTableDiscussion .
                " WHERE course IN (" . $this->course->getIDsString(). ");";
        $query = $this->db->query($sql);
        if ($query->num_rows) {
            $this->sumOfActiveCourseCat = $query->num_rows;
        } else {
            $this->sumOfActiveCourseCat = 0;
        }
    }
    
    /* global getter */
    /**
     * returns the sum of all forum
     * @return int
     */
    public function getSumOfAll() {
        if (!isset($this->sumOfAll)) {
            $this->selectAllForum();
        }
        return $this->sumOfAll;
    }
    
    /**
     * returns the sum of all courses with minimum one forum
     * @return int
     */
    public function getSumOfCourse() {
        if (!isset($this->sumOfCourse)) {
            $this->selectAllForum();
        }
        return $this->sumOfCourse;
    }
    
    /**
     * returns the sum of all courses, which have forum with one or more posts
     * @return int
     */
    public function getSumOfActiveCourse() {
        if (!isset($this->sumOfActiveCourse)) {
            $this->selectActiveCourse();
        }
        return $this->sumOfActiveCourse;
    }
    
    /**
     * returns the sum of all forum, which have one or more posts
     * @return int
     */
    public function getSumOfActiveForum() {
        if (!isset($this->sumOfActiveForum)) {
            $this->selectActiveForum();
        }
        return $this->sumOfActiveForum;
    }
    
    
    /* category getter */
    public function getSumOfAllCat() {
        if (!isset($this->sumOfAllCat)) {
            $this->selectAllForumCat();
        }
        return $this->sumOfAllCat;
    }
    
    /**
     * returns the sum of all courses with forum, which are in setted categories
     * @return int
     */
    public function getSumOfCourseCat() {
        if (!isset($this->sumOfCourseCat)) {
            $this->selectAllForumCat();
        }
        return $this->sumOfCourseCat;
    }
    
    /**
     * returns the sum of all courses with forum (minimum one post)
     * @return int
     */
    public function getSumOfActiveCourseCat() {
        if (!isset($this->sumOfActiveCourseCat)) {
            $this->selectActiveCourseCat();
        }
        return $this->sumOfActiveCourseCat;
    }
    
    /**
     * returns the sum of forum with minimum one post, which are in setted
     * categories
     * @return int
     */
    public function getSumOfActiveForumCat() {
        if (!isset($this->sumOfActiveForumCat)) {
            $this->selectActiveForumCat();
        }
        return $this->sumOfActiveForumCat;
    }
    
    /**
     * filter all courses with more than one forum
     */
    private function selectXtraForumCat() {
        $this->sumOfXtraCourseCat = 0;
        if (!isset($this->allCat)) {
            $this->selectAllForumCat();
        }
        if (count($this->allCat) > 0) {
            foreach ($this->allCat as $f) {
                if ($f->sumOf > 1) {
                    $this->sumOfXtraCourseCat++;
                }
            }
        }
    }
    /**
     * returns sum of courses with more than one forum
     */
    public function getSumOfXtraCourseCat() {
        if (!isset($this->sumOfXtraCourseCat)) {
            $this->selectXtraForumCat();
        }
        return $this->sumOfXtraCourseCat;
    }
}