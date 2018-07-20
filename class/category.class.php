<?php
/**
 * Organize all course categories to handle semester, faculties and further
 * institutions. Class course, module, semester, ... depends on the results of
 * these class
 *
 */
class Category {

    protected static $instance;
    private $db;
    private $dbTable;
    private $semester;
    private $course;
    private $topLevel;
    private $all;
    private $active;
    private $activePath;
    private $activeChilds;
    private $activeId;
    private $activeIDsString;
    private $selectedActive;
    private $noFacultyId;
    private $facultyId;

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

    protected function init() {
        $this->db = DB::getInstance();
        $this->dbTable = DB_TABLE_PREFIX . 'course_categories';
        $this->semester = Semester::getInstance();
        $this->topLevel = [];
        $this->all = [];
        $this->active = [];
        $this->activePath = '/';
        $this->activeChilds = [];
        $this->activeId = [];
        $this->activeIDsString = '';
        $this->noFacultyId = [];
        $this->facultyId = [];
        $this->updateTopLevel();
        $this->updateAll();
    }

    public function selectByPath($pathStart, $option = "", $echo = false) {
        $sql = "SELECT * FROM $this->dbTable WHERE "
                . "path LIKE '" . $pathStart . "%'";
        if ($option) {
            $sql .= " " . $option;
        }
        if ($echo) {
            echo '<br/>'.$sql.'<br/>';
        }
        return $this->db->query($sql);
    }

    private function selectByParentId($parentId = null, $option = null) {
        $sql = "SELECT * FROM $this->dbTable";

        if ($parentId !== null && is_numeric($parentId)) {
            $sql .= " WHERE parent = " . $parentId;
        }

        if ($option !== null && trim($option)) {
            $sql .= " " . trim($option);
        }

        return $this->db->query($sql);
    }

    # ==========================================================================
    # ALL CATEGORIES
    # ==========================================================================

    public function addToAll($cat) {
        $this->all[$cat->path] = $cat;
    }

    public function getAll() {
        return $this->all;
    }

    private function updateAll() {
        # TUM has only two top level categories, current semester and other
        $curSem = $this->getTopLevel(0);
        # the semester depth is one, because it's the current semester
        $this->setActivePath($curSem->path, 1);
        $curChilds = $this->getActiveChilds();
        $this->all[$curSem->path] = $curSem;
        foreach ($curChilds as $child) {
            $this->addToAll($child);
        }
        unset($curChilds);
        unset($curSem);

        $otherSem = $this->getTopLevel(1);
        # The semester depth is two, because they inherits to other category
        $this->setActivePath($otherSem->path, 2);
        $otherChilds = $this->getActiveChilds();
        $this->all[$otherSem->path] = $otherSem;
        foreach ($otherChilds as $child) {
            $this->addToAll($child);
        }
        unset($otherChilds);
        unset($otherSem);
    }


    # ==========================================================================
    # TOP LEVEL CATEGORIES
    # ==========================================================================
    /**
     * fill class array topLevel with all category entries for top level
     */
    private function updateTopLevel() {
        $query = $this->selectByParentId(0, "ORDER BY id DESC");
        while ($r = $query->fetch_object()) {
            $this->topLevel[] = $r;
        }
    }

    /**
     * returns the top level category object(s), dependent on given $pos
     * @param integer $pos
     * @return array/object
     */
    public function getTopLevel($pos = null) {
        if ($pos !== null && is_numeric($pos)) {

            if ($pos <= (count($this->topLevel) - 1)) {
                return $this->topLevel[$pos];
            } else {
                return array_slice($this->topLevel, -1)[0];
            }
        } else {
            return $this->topLevel;
        }
    }

    # ==========================================================================
    # ACTIVE CATEGORIES
    # ==========================================================================

    /**
     * fill the class array active and if semester category is given
     * add the semester object to semester class
     * @param string $pth
     * @param integer $semesterCat
     */
    private function updateActiveByPath($pth = '', $semesterCat = null) {
        $path = (trim($pth) !== '') ? $pth : $this->getActivePath();

        $query = $this->selectByPath($path, "ORDER BY path ASC, depth ASC");

        while ($r = $query->fetch_object()) {
            $this->addActive($r);

            if ($semesterCat &&
                    is_numeric($semesterCat) &&
                    intval($r->depth) === $semesterCat) {
                $this->semester->addToAll($r);
            }


            if (substr($r->idnumber, 0, strlen(FOREIGN_FACULTY_PREFIX)) === FOREIGN_FACULTY_PREFIX) {
                // none faculty found
                // select all ids from them
                $this->selectNoFacultyByObject($r);
            }
        }

        $this->resetActiveIdsString();
    }

    private function selectNoFacultyByObject($obj) {
        $query = $this->selectByPath($obj->path);

        while ($nf = $query->fetch_object()) {
            $t[$nf->id] = $nf->id;
            $this->setNoFacultyId($nf->id);
        }
        $this->updateFacultyId();
    }

    /**
     * fill the array of no faculty ids
     * @param integer $id
     */
    private function setNoFacultyId($id) {
        $this->noFacultyId[$id] = $id;
    }

    /**
     * transform the faculty ids array to an string of ids
     * @return string
     */
    public function getNoFacultyIdString() {
        return implode(",", $this->noFacultyId);
    }

    private function updateFacultyId() {
        $t = explode(",", $this->getActiveIdsString());
        $this->facultyId = array_diff($t, $this->noFacultyId);
    }
    public function getFacultyIdString() {
        return implode(",", $this->facultyId);
    }

    /**
     * truncate the active array of categories
     */
    private function resetActive() {
        $this->active = [];
    }

    private function addActive($obj) {
        if (empty($this->active)) {
            $this->selectedActive = $obj;
        }
        $this->active[$obj->id] = $obj;
    }

    /**
     * returns an array of all active categories
     * @return array
     */
    public function getActive() {
        return $this->active;
    }

    public function getActivePath() {
        return $this->activePath;
    }

    public function setActivePath($path, $semesterDepth = null) {
        $this->activePath = $path;
        $this->resetActive();
        $this->updateActiveByPath('', $semesterDepth);
        $this->updateActiveChilds();
    }

    /**
     * represents the selected object from dropdown choice
     * - used to filter for view
     */
    public function getSelectedActive() {
        return $this->selectedActive;
    }

    /* ACTIVE CHILDs */
    #=====================================================================

    private function updateActiveChilds() {
        $this->activeChilds = [];

        if (trim($this->activePath)) {

            if (count($this->active) > 0) {
                $childPath = ($this->activePath . '/');

                foreach ($this->active as $cat) {
                    if (strpos($cat->path, $childPath) === 0) {
                        $this->activeChilds[] = $cat;
                    }
                }
            } else {
                $this->updateActiveByPath();
                $this->updateActiveChilds();
            }
        }
    }

    /**
     * return all children from active category
     * @return array
     */
    public function getActiveChilds() {
	#asort($this->activeChilds);
        return $this->activeChilds;
    }

    /* ACTIVE IDs */
    #=====================================================================

    private function resetActiveIdsString() {
        $this->activeIDsString = '';
        $this->activeId = [];
        foreach ($this->active as $index => $obj) {
            $this->activeId[$index] = $index;
        }
        $this->updateActiveIdString();
        $this->updateFacultyId();
    }

    /**
     * @param string/array $id
     * if string/number is given, the id will add to the string
     * if array is given, the array will be transformed to the new active ids string
     */
    private function updateActiveIdString() {
        $this->activeIDsString = implode(",", $this->activeId);
    }

    public function getActiveIdsString() {
        return $this->activeIDsString;
    }

}
