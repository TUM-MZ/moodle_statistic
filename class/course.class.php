<?php

/**
 * Description of Course
 *
 * @author schlender
 */
class Course {

    protected static $instance;
    private $db;
    private $dbTable;
    private $category;
    private $courseId;
    private $courseIDsString;
    private $allCourses;
    private $sqlSelectCount;
    private $courseModule;

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
        $this->dbTable = DB_TABLE_PREFIX . 'course';

        $this->category = Category::getInstance();
        $this->courseId = [];
        $this->updateAllCoursesFromCategories();
        $this->sqlSelectCount = "SELECT count(id) as count FROM " .
                $this->dbTable .
                " WHERE category != 0";
    }

    /**
     * read all courses from possible categories
     */
    private function selectAllCoursesFromCategories() {
        $sql = "SELECT * FROM " .
                $this->dbTable .
                " WHERE category IN (" .
                $this->category->getActiveIdsString() . ")";
        return $this->db->query($sql);
    }

    /**
     * update all courses from possible categories
     */
    private function updateAllCoursesFromCategories() {
        $this->allCourses = [];
        $this->resetCourseIDsString();
        $query = $this->selectAllCoursesFromCategories();

        while ($row = $query->fetch_object()) {
            $this->allCourses[] = $row;
            $this->courseId[$row->id] = $row->id;
        }
    }

    /**
     * default function to count the courses, is called by every sumOf function
     * @param boolean $updateBefore: update the relation of category-course
     * @param string $option: SQL condition like ' AND ...'
     * @return integer
     */
    private function getSum($updateBefore = true, $option = "") {
        if ($updateBefore) {
            $this->updateAllCoursesFromCategories();
        }

        $sql = $this->sqlSelectCount . $option . " LIMIT 0,1";
        #echo $sql;
        $query = $this->db->query($sql);
        return $query->fetch_object()->count;
    }

    /*     * *************************************************************************
     * GLOBAL SUMMARIZE OF COURSES
     */

    /**
     * returns global sum of courses
     * @return integer 
     */
    public function getSumOf() {
        $count = $this->getSum(false);
        $param = str_ireplace("getSumOf", "sumOf", __FUNCTION__) . 'Course';
        $this->addCourseModuleInfo($param, $count);
        return $count;
    }

    /**
     * returns global sum of courses which are inside of faculties
     * @return integer 
     */
    public function getSumOfFac() {
        $cond = " AND category NOT IN (" .
                $this->category->getNoFacultyIdString() . ")";
        $count = $this->getSum(false, $cond);
        $param = str_ireplace("getSumOf", "sumOf", __FUNCTION__) . 'Course';
        $this->addCourseModuleInfo($param, $count);
        return $count;
    }

    /*     * *************************************************************************
     * GLOBAL SUMMARIZE OF COURSES
     * sum of visible
     */

    /**
     * returns global sum of visible courses
     * @return integer 
     */
    public function getSumOfShown() {
        $cond = " AND visible = 1";
        $count = $this->getSum(false, $cond);
        $param = str_ireplace("getSumOf", "sumOf", __FUNCTION__) . 'Course';
        $this->addCourseModuleInfo($param, $count);
        return $count;
    }

    /**
     * returns global sum of visible courses which are inside of faculties
     * @return integer 
     */
    public function getSumOfShownFac() {
        $cond = " AND visible = 1" .
                " AND category NOT IN (" .
                $this->category->getNoFacultyIdString() . ")";
        $count = $this->getSum(false, $cond);
        $param = str_ireplace("getSumOf", "sumOf", __FUNCTION__) . 'Course';
        $this->addCourseModuleInfo($param, $count);
        return $count;
    }

    /*     * *************************************************************************
     * GLOBAL SUMMARIZE OF COURSES
     * sum of synchronized with TUMonline
     */

    /**
     * returns global sum of synchronized courses
     * @return integer 
     */
    public function getSumOfSync() {
        $cond = " AND idnumber IS NOT NULL" .
                " AND concat('', idnumber * 1) > 100000000";
        $count = $this->getSum(false, $cond);
        $param = str_ireplace("getSumOf", "sumOf", __FUNCTION__) . 'Course';
        $this->addCourseModuleInfo($param, $count);
        return $count;
    }

    /**
     * returns global sum of synchronized courses which are inside of faculties
     * @return integer 
     */
    public function getSumOfSyncFac() {
        $cond = " AND idnumber IS NOT NULL" .
                " AND concat('', idnumber * 1) > 100000000" .
                " AND category NOT IN (" .
                $this->category->getNoFacultyIdString() . ")";
        $count = $this->getSum(false, $cond);
        $param = str_ireplace("getSumOf", "sumOf", __FUNCTION__) . 'Course';
        $this->addCourseModuleInfo($param, $count);
        return $count;
    }

    /*     * *************************************************************************
     * GLOBAL SUMMARIZE OF COURSES
     * sum of visible synchronized with TUMonline
     */

    /**
     * returns global sum of visible synchronized courses
     * @return integer 
     */
    public function getSumOfSyncShown() {
        $cond = " AND visible = 1" .
                " AND idnumber IS NOT NULL" .
                " AND concat('', idnumber * 1) > 100000000";
        $count = $this->getSum(false, $cond);
        $param = str_ireplace("getSumOf", "sumOf", __FUNCTION__) . 'Course';
        $this->addCourseModuleInfo($param, $count);
        return $count;
    }

    /**
     * returns global sum of visible synchronized courses which are inside of faculties
     * @return integer 
     */
    public function getSumOfSyncShownFac() {
        if ($this->category->getFacultyIdString()) {
            $cond = " AND visible = 1" .
                    " AND idnumber IS NOT NULL" .
                    " AND concat('', idnumber * 1) > 100000000" .
                    " AND category NOT IN (" .
                    $this->category->getNoFacultyIdString() . ")";
            $count = $this->getSum(false, $cond);
            $param = str_ireplace("getSumOf", "sumOf", __FUNCTION__) . 'Course';
            $this->addCourseModuleInfo($param, $count);
            return $count;
        } else {
            return '-';
        }
    }

    /*     * *************************************************************************
     * CATEGORY SPECIFIC SUMMARIZE OF COURSES
     */

    public function getSumOfCat() {
        $cond = " AND category in (" .
                $this->category->getActiveIdsString() . ")";
        $count = $this->getSum(true, $cond);
        $param = str_ireplace("getSumOf", "sumOf", __FUNCTION__) . 'Course';
        $this->addCourseModuleInfo($param, $count);
        return $count;
    }

    public function getSumOfCatFac() {
        if ($this->category->getFacultyIdString()) {
            $cond = " AND category in (" .
                    $this->category->getFacultyIdString() . ")" .
                    " AND category NOT IN (" .
                    $this->category->getNoFacultyIdString() . ")";
            $count = $this->getSum(true, $cond);
            $param = str_ireplace("getSumOf", "sumOf", __FUNCTION__) . 'Course';
            $this->addCourseModuleInfo($param, $count);
            return $count;
        } else {
            return '-';
        }
    }

    /*     * *************************************************************************
     * CATEGORY SPECIFIC SUMMARIZE OF COURSES
     * sum of visible category
     */

    public function getSumOfCatShown() {
        $cond = " AND visible = 1" .
                " AND category in (" .
                $this->category->getActiveIdsString() . ")";
        $count = $this->getSum(true, $cond);
        $param = str_ireplace("getSumOf", "sumOf", __FUNCTION__) . 'Course';
        $this->addCourseModuleInfo($param, $count);
        return $count;
    }

    public function getSumOfCatShownFac() {
        if ($this->category->getFacultyIdString()) {
            $cond = " AND visible = 1" .
                    " AND category in (" .
                    $this->category->getFacultyIdString() . ")" .
                    " AND category NOT IN (" .
                    $this->category->getNoFacultyIdString() . ")";
            $count = $this->getSum(true, $cond);
            $param = str_ireplace("getSumOf", "sumOf", __FUNCTION__) . 'Course';
            $this->addCourseModuleInfo($param, $count);
            return $count;
        } else {
            return '-';
        }
    }

    /*     * *************************************************************************
     * CATEGORY SPECIFIC SUMMARIZE OF COURSES
     * sum of synchronized with TUMonline
     */

    /**
     * returns category sum of synchronized courses
     * @return integer 
     */
    public function getSumOfCatSync() {

        $cond = " AND idnumber IS NOT NULL" .
                " AND concat('', idnumber * 1) > 100000000" .
                " AND category in (" .
                $this->category->getActiveIdsString() . ")";
        $count = $this->getSum(false, $cond);
        $param = str_ireplace("getSumOf", "sumOf", __FUNCTION__) . 'Course';
        $this->addCourseModuleInfo($param, $count);
        return $count;
    }

    /**
     * returns category sum of synchronized courses which are inside of faculties
     * @return integer 
     */
    public function getSumOfCatSyncFac() {
        if ($this->category->getFacultyIdString()) {
            $cond = " AND idnumber IS NOT NULL" .
                    " AND concat('', idnumber * 1) > 100000000" .
                    " AND category in (" .
                    $this->category->getFacultyIdString() . ")" .
                    " AND category NOT IN (" .
                    $this->category->getNoFacultyIdString() . ")";
            $count = $this->getSum(false, $cond);
            $param = str_ireplace("getSumOf", "sumOf", __FUNCTION__) . 'Course';
            $this->addCourseModuleInfo($param, $count);
            return $count;
        } else {
            return '-';
        }
    }

    /*     * *************************************************************************
     * CATEGORY SPECIFIC SUMMARIZE OF COURSES
     * sum of visible synchronized with TUMonline
     */

    /**
     * returns category sum of visible synchronized courses
     * @return integer 
     */
    public function getSumOfCatSyncShown() {
        $cond = " AND visible = 1" .
                " AND idnumber IS NOT NULL" .
                " AND concat('', idnumber * 1) > 100000000" .
                " AND category in (" .
                $this->category->getActiveIdsString() . ")";
        $count = $this->getSum(false, $cond);
        $param = str_ireplace("getSumOf", "sumOf", __FUNCTION__) . 'Course';
        $this->addCourseModuleInfo($param, $count);
        return $count;
    }

    /**
     * returns category sum of visible synchronized courses which are inside of faculties
     * @return integer 
     */
    public function getSumOfCatSyncShownFac() {
        if ($this->category->getFacultyIdString()) {
            $cond = " AND visible = 1" .
                    " AND idnumber IS NOT NULL" .
                    " AND concat('', idnumber * 1) > 100000000" .
                    " AND category in (" .
                    $this->category->getFacultyIdString() . ")" .
                    " AND category NOT IN (" .
                    $this->category->getNoFacultyIdString() . ")";
            $count = $this->getSum(false, $cond);
            $param = str_ireplace("getSumOf", "sumOf", __FUNCTION__) . 'Course';
            $this->addCourseModuleInfo($param, $count);
            return $count;
        } else {
            return '-';
        }
    }

    /*     * *************************************************************************
     * COURSE IDs
     */

    public function getIDsString() {
        if (empty($this->courseId)) {
            return false;
        } else {
            $this->setCourseIDsString(implode(",", $this->courseId));
            return $this->courseIDsString;
        }
    }

    public function setCourseIDsString($str) {
        $this->courseIDsString = $str;
    }

    private function resetCourseIDsString() {
        $this->courseId = [];
        $this->courseIDsString = '';
    }

    /*     * *************************************************************************
     * GLOBAL GETTER & SETTER
     */

    public function __get($name) {
        return $this->$name;
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }

    /*     * *************************************************************************
     * COURSE MODULES
     */

    public function getCourseModule() {
        if (!isset($this->courseModule)) {
            return false;
        } else {
            return $this->courseModule;
        }
    }

    private function addCourseModuleInfo($param, $value) {
        $this->getCourseModule()->$param = $value;
    }

}
