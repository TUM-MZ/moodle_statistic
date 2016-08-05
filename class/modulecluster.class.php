<?php

/**
 * Description of user
 *
 * @author
 */
class ModuleCluster {

    protected static $instance;
    private $db;
    private $dbTable;
    private $course;
    private $module;
    private $cluster;
    private $clusterData;

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
        $this->dbTable = DB_TABLE_PREFIX . 'course_modules';

        $this->module = Module::getInstance();
        $this->course = Course::getInstance();
        $this->forum = Forum::getInstance();

        $this->clusterData = [];

        global $MODULE_CLUSTER;
        $this->cluster = $MODULE_CLUSTER;
        $this->configCluster();
    }

    private function configCluster() {
        foreach ($this->cluster as $clustername => $modules) {
            $names = [];
            if (is_array($modules)) {
                foreach ($modules as $modulename => $val) {
                    $names[] = $modulename;
                }
            }
            $query = $this->module->getModulesWithName($names);

            while ($entry = $query->fetch_object()) {
                $this->cluster[$clustername][$entry->name] = $entry->id;
            }
            $this->cleanCluster($clustername);
        }
    }

    private function cleanCluster($clustername) {
        foreach ($this->cluster[$clustername] as $module => $val) {
            if (!$val) {
                unset($this->cluster[$clustername][$module]);
            }
        }
    }

    private function collectCommunicationClusterModules($clustername, $moduleIds) {
        # temp array to save results
        $t = [];
        
        # SELECT all courses from forums with min one post in the category
        $sql = "SELECT DISTINCT(course)" .
                " FROM " . $this->forum->dbPostsTable .
                " WHERE course IN (" . $this->course->getIDsString() . ");";
        $q = $this->db->query($sql);
        // save the result in array
        while ($c = $q->fetch_object()) {
            $t[$c->course] = $c->course;
        }
        
        # SELECT all courses from modules without forum
        $sql = "SELECT DISTINCT(course)" .
                " FROM " . $this->dbTable .
                " WHERE course IN (" . $this->course->getIDsString() . ")" .
                " AND module IN (" . $moduleIds . ")" .
                " AND module != " . $this->module->getForumId();
        
        $q = $this->db->query($sql);
        // save the result in array
        while ($cm = $q->fetch_object()) {
            $t[$cm->course] = $cm->course;
        }
        
        # save result array into class cluster array
        $this->clusterData[$clustername]['sum'] = count($t);
        $this->clusterData[$clustername]['course-ids'] = implode(",", $t);
        unset($t);
    }

    private function selectClusterCourses($clustername) {

        $moduleIds = '';
        foreach ($this->cluster[$clustername] as $module => $val) {
            if ($moduleIds !== '') {
                $moduleIds .= ',';
            }
            $moduleIds .= $val;
        }

        switch ($clustername) {
            case 'communication':
                $this->collectCommunicationClusterModules($clustername, $moduleIds);
                break;
            default:

                $sql = "SELECT DISTINCT(course) FROM " .
                        $this->dbTable .
                        " WHERE course IN (" .
                        $this->course->getIDsString() .
                        ") AND module IN (" .
                        $moduleIds .
                        ")";

                $q = $this->db->query($sql);
                $this->clusterData[$clustername]['sum'] = $q->num_rows;
                $this->clusterData[$clustername]['course-ids'] = '';
                while ($c = $q->fetch_object()) {
                    if ($this->clusterData[$clustername]['course-ids'] !== '') {
                        $this->clusterData[$clustername]['course-ids'] .= ',';
                    }
                    $this->clusterData[$clustername]['course-ids'] .= $c->course;
                }
                break;
        }
    }

    public function getSumOfClusterCourses($clustername) {
        if (!isset($this->clusterData[$clustername])) {
            $this->selectClusterCourses($clustername);
        }
        return $this->clusterData[$clustername]['sum'];
    }

}
