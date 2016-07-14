<?php

/**
 * Controller class for course and modules, which creates the objects
 *
 * @author schlender
 */
class CourseModules {

    protected static $instance;
    private $db;
    private $dbTable;
    private $category;
    private $course;
    private $modules;
    private $forum;
    private $sumOfCourse, $sumOfFacCourse,
            $sumOfShownCourse, $sumOfShownFacCourse,
            $sumOfSyncCourse, $sumOfSyncFacCourse,
            $sumOfSyncShownCourse, $sumOfSyncShownFacCourse,
            $sumOfCatCourse, $sumOfCatFacCourse,
            $sumOfCatShownCourse, $sumOfCatShownFacCourse,
            $sumOfCatSyncCourse, $sumOfCatSyncFacCourse,
            $sumOfCatSyncShownCourse, $sumOfCatSyncShownFacCourse;
    private $xtraForum, $sumOfXtraForumCourses, $sumOfXtraForum;
    private $courseWithoutForum, $sumOfCourseWithoutForum;
    private $forumCoursesCat, $emptyForumCoursesCat;

    # catModCourses contains all courses from course_modules with current categeory
    private $catModCourses, $sumOfCatModCourses, $sumOfEmptyCatModCourses;

    public function __construct($category, $course, $modules) {
        $this->category = $category;
        $this->course = $course;
        $this->modules = $modules;
        $this->init();
    }

    // getInstance method
    public static function getInstance() {

        if (!self::$instance) {
            return false;
        }

        return self::$instance;
    }

    public function init() {
        $this->db = DB::getInstance();
        $this->dbTable = DB_TABLE_PREFIX . 'course_modules';
        $this->course->courseModule = $this;
        $this->modules->courseModule = $this;
        $this->emptyForumCoursesCat = null;
    }

    public function getTranslationTableString() {
        global $DICT_MODULE;
        $translations = array();
        foreach ($DICT_MODULE as $engl => $ger) {
            array_push($translations, "SELECT '$engl' as english_name, '$ger' as german_name");
        }
        return implode(" UNION ALL ", $translations);
    }

    public function getSumOfCoursesWithMod() {
        $ids = $this->course->getIDsString();

        if ($ids && strlen($ids) > 0) {
            $courseTmpTable = implode(" UNION ALL ", array_map(
                            function($course) {
                        return "SELECT $course as courseid";
                    }, $this->course->courseId
                    )
            );

            $sql = "SELECT m.name, m.id as mod_id" .
                    ", cm.course as course_id, count(cm.course) as courses" .
                    " FROM (" . $courseTmpTable . ") as courses" .
                    " CROSS JOIN " . $this->modules->dbTable . " as m" .
                    " JOIN (" . $this->getTranslationTableString() . ") as translations" .
                    " ON translations.english_name = m.name" .
                    " LEFT JOIN (" .
                    " SELECT DISTINCT(course), module FROM " .
                    $this->dbTable . ") as cm" .
                    " ON (cm.module = m.id and cm.course = courses.courseid)" .
                    " GROUP BY m.id" .
                    " ORDER BY translations.german_name";
            $query = $this->db->query($sql);
            return $query;
        } else {
            return null;
        }
    }

    /* +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
     * Course which have only forum wihtout posts
     */

    /**
     * select all courses which have only forums without posts
     */
    private function defineEmptyForumCoursesCat() {
        // reset the param
        $this->forumCoursesCat = [];
        $this->emptyForumCoursesCat = 0;

        // SELECT only courses with forum from forum table
        $sql = "SELECT *" .
                " FROM " . $this->getForumClass()->dbTable .
                " WHERE course IN (" . $this->course->getIDsString() . ")" .
                " AND course NOT IN (" .
                "  SELECT DISTINCT(course) FROM " . $this->dbTable .
                "  WHERE module != " . $this->modules->getForumId() .
                "  AND course IN (" . $this->course->getIDsString() . ")" .
                " );";
        $q = $this->db->query($sql);

        if ($q->num_rows > 0) {
            $forumIdStr = '';
            $courseCount = 0;
            while ($r = $q->fetch_object()) {
                if (!isset($this->forumCoursesCat[$r->course])) {
                    $this->forumCoursesCat[$r->course] = [];
                    $courseCount++;
                }
                $this->forumCoursesCat[$r->course][$r->id] = $r;
                $forumIdStr .= ($forumIdStr === '') ? $r->id : ',' . $r->id;
            }
            
            $sql = "SELECT DISTINCT(course)" .
                    " FROM " . $this->getForumClass()->dbPostsTable .
                    " WHERE forum IN (" . $forumIdStr . ")";
            $q = $this->db->query($sql);
            
            $coursePostCount = $q->num_rows;
            
            // calc the sum of empty courses by: 
            //   courses with only forum 
            // - sum of courses with posts from courses with only forum
            $this->emptyForumCoursesCat = ($courseCount - $coursePostCount);
        }
    }
    /**
     * returns a number of all courses which have only forums and 
     * the forums haven't posts
     * - depends on the selected category
     * @return int
     */
    public function getEmptyForumCoursesCat() {
        if ($this->emptyForumCoursesCat === null) {
            $this->defineEmptyForumCoursesCat();
        }
        return $this->emptyForumCoursesCat;
    }

    private function selectXtraForum() {
        $ids = $this->course->getIDsString();
        if ($ids && strlen($ids) > 0) {
            $sql = "SELECT course, module, count, (count - 1) as xtraForum FROM" .
                    " (SELECT course, module, count(*) as count FROM " .
                    $this->dbTable .
                    " WHERE module = " . $this->modules->getForumId() .
                    " AND course IN (" . $ids . ")" .
                    " GROUP BY course ORDER BY course ASC" .
                    ") as t WHERE count > 1;";
            $query = $this->db->query($sql);
            $this->sumOfXtraForumCourses = $query->num_rows;
            $this->xtraForum = [];
            $this->sumOfXtraForum = 0;
            while ($row = $query->fetch_object()) {
                $this->xtraForum[] = $row;
                $this->sumOfXtraForum += $row->xtraForum;
            }
        }
    }

    /**
     * returns sum of courses with more than one forum
     */
    public function getSumOfXtraForumCourses() {
        if (!isset($this->sumOfXtraForumCourses)) {
            $this->selectXtraForum();
        }
        return $this->sumOfXtraForumCourses;
    }

    public function getSumOfXtraForum() {
        if (!isset($this->sumOfXtraForum)) {
            $this->selectXtraForum();
        }
        return $this->sumOfXtraForum;
    }

    public function getXtraForumCourses() {
        return $this->xtraForum;
    }

    public function getSumOfModules() {
        $ids = $this->course->getIDsString();
        if ($ids && strlen($ids) > 0) {
            $courseTmpTable = implode(" UNION ALL ", array_map(
                            function($course) {
                        return "SELECT $course as courseid";
                    }, $this->course->courseId
                    )
            );
            $sql = "SELECT m.name, m.id as mod_id" .
                    ", cm.course as course_id, count(cm.id) as count" .
                    " FROM (" . $courseTmpTable . ") as courses " .
                    " CROSS JOIN " . $this->modules->dbTable . " as m" .
                    " JOIN (" . $this->getTranslationTableString() . ") as translations" .
                    " ON translations.english_name = m.name" .
                    " LEFT JOIN " . $this->dbTable . " as cm" .
                    " ON (cm.module = m.id and cm.course = courses.courseid)" .
                    " GROUP BY m.id" .
                    " ORDER BY translations.german_name";
            $query = $this->db->query($sql);
            return $query;
        } else {
            return null;
        }
    }

    public function getSumOfUsedTypes() {
        if ($this->course->getSumOfCat() > 0) {
            $sql = "SELECT count(DISTINCT(module)) as count FROM " .
                    $this->dbTable .
                    " WHERE course IN" .
                    " (" . $this->course->getIDsString() . ")";
            $q = $this->db->query($sql);
            $count = $q->fetch_object()->count;

            return $count;
        }
        return 0;
    }

    public function getSumOfAlls() {
        if ($this->course->getSumOfCat() > 0) {
            $sql = "SELECT count(*) as sum FROM " .
                    $this->dbTable .
                    " WHERE course IN (" . $this->course->getIDsString() . ")";
            $q = $this->db->query($sql);
            return $q->fetch_object()->sum;
        }
        return 0;
    }

    /**
     * read all courses which related in course_modules from the current category
     */
    private function selectCatModCourses() {
        # reset the array catModCourses
        $this->catModCourses = [];

        # read all courses from current category with module relation
        $sql = "SELECT DISTINCT(course) FROM " .
                $this->dbTable .
                " WHERE course IN " .
                " (" . $this->course->getIDsString() . ")";
        $q = $this->db->query($sql);

        # save the sum of courses with minimum one module
        $this->sumOfCatModCourses = $q->num_rows;

        if ($q->num_rows > 0) {
            while ($c = $q->fetch_object()) {
                $this->catModCourses[$c->course] = $c;
            }
        }
    }

    private function calcSumOfEmptyCatCourses() {
        if (!isset($this->sumOfEmptyCatModCourses)) {
            $this->selectCatModCourses();
        }
        $sumOfCourses = count($this->course->allCourses);
        $this->sumOfEmptyCatModCourses = $sumOfCourses - $this->sumOfCatModCourses;
    }

    public function getSumOfEmptyCatCourses() {
        if (!isset($this->sumOfEmptyCatModCourses)) {
            $this->calcSumOfEmptyCatCourses();
        }
        return $this->sumOfEmptyCatModCourses;
    }

    /* +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
     * ????????????????????????????????????????????????????
     */

    public function findCourseWithoutModule() {
        $sql = "SELECT * FROM " .
                $this->course->dbTable .
                " WHERE id NOT IN (" .
                " SELECT DISTINCT(course) FROM " .
                $this->dbTable .
                ")";
        $query = $this->db->query($sql);
        $this->sumOfCourseWithoutForum = $query->num_rows;

        $this->courseWithoutForum = [];
        while ($row = $query->fetch_object()) {
            $this->courseWithoutForum[] = $row;
        }

        return $this->courseWithoutForum;
    }

    public function findCourseDiffFromModuleForum() {
        /* find all courses with forum */
        $sql = "SELECT course FROM " . $this->dbTable . " WHERE module = " . $this->modules->getForumId() . " ORDER BY course ASC";
        /* find alle courses which have no forum */
        $sql = "SELECT * FROM " . $this->course->dbTable . " WHERE id NOT in (" . $sql . ") ORDER BY id ASC";
        $query = $this->db->query($sql);
        return $query;
    }

    public function findModuleCourseIdWithoutForum() {
        $sql = "SELECT * FROM " .
                $this->dbTable .
                "WHERE course NOT IN (SELECT DISTINCT(course) FROM " .
                $this->dbTable .
                " WHERE module = " . $this->modules->getForumId() .
                " ORDER BY course ASC)";
    }

    private function getForumClass() {
        if (!$this->forum) {
            $this->forum = Forum::getInstance();
        }
        return $this->forum;
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

}
