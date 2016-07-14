<?php
# ==============================================================================
# START MOODLE CONTEXT
$mdl_configFile = $_SERVER['DOCUMENT_ROOT'] . "/config.php";


if (file_exists($mdl_configFile)) {
    require_once $mdl_configFile;
    require_once($CFG->libdir . '/adminlib.php');

    require_login();
    require_capability('moodle/site:config', context_system::instance());
}
# END MOODLE CONTEXT
# ==============================================================================


# ==============================================================================
# PREFIX
# 
// define the used database table prefix
if (isset($CFG)) {
    define("DB_TABLE_PREFIX", $CFG->prefix);
} else {
    define("DB_TABLE_PREFIX", "mdl_");
}

// define an prefix to separate faculties from other institutions, the prefix
// is used as idnumber in category table
define("FOREIGN_FACULTY_PREFIX", "further");


# ==============================================================================
# MODULE CONFIG
# 
# if the name of one module changes in moodle, modify the value here to get 
# the right relation for dictionary and module clustering
$MODULE_NAMES = [
    'adobeconnect' => 'adobeconnect',
    'assign' => 'assign',
    'assignment' => 'assignment',
    'book' => 'book',
    'certificate' => 'certificate',
    'chat' => 'chat',
    'choice' => 'choice',
    'choicegroup' => 'choicegroup',
    'data' => 'data',
    'digitalization' => 'digitalization',
    'etherpadlite' => 'etherpadlite',
    'feedback' => 'feedback',
    'folder' => 'folder',
    'forum' => 'forum',
    'glossary' => 'glossary',
    'hotpot' => 'hotpot',
    'imscp' => 'imscp',
    'label' => 'label',
    'lesson' => 'lesson',
    'lightboxgallery' => 'lightboxgallery',
    'lti' => 'lti',
    'page' => 'page',
    'quiz' => 'quiz',
    'quizinvideo' => 'quizinvideo',
    'resource' => 'resource',
    'scorm' => 'scorm',
    'survey' => 'survey',
    'url' => 'url',
    'wiki' => 'wiki',
    'workshop' => 'workshop'
];

// dictionary for module names
global $DICT_MODULE;
$DICT_MODULE = [
    $MODULE_NAMES['adobeconnect'] => 'Adobe Connect',
    $MODULE_NAMES['assign'] => 'Aufgabe',
    $MODULE_NAMES['assignment'] => 'Aufgabe (alt)',
    $MODULE_NAMES['book'] => 'Buch',
    $MODULE_NAMES['certificate'] => 'Zertifikat',
    $MODULE_NAMES['chat'] => 'Chat',
    $MODULE_NAMES['choice'] => 'Abstimmung',
    $MODULE_NAMES['choicegroup'] => 'Gruppenwahl',
    $MODULE_NAMES['data'] => 'Datenbank',
    $MODULE_NAMES['digitalization'] => 'Digit. Semesterapparat',
    $MODULE_NAMES['etherpadlite'] => 'Etherpad Lite',
    $MODULE_NAMES['feedback'] => 'Feedback',
    $MODULE_NAMES['folder'] => 'Verzeichnis',
    $MODULE_NAMES['forum'] => 'Forum',
    $MODULE_NAMES['glossary'] => 'Glossar',
    $MODULE_NAMES['hotpot'] => 'HotPot',
    $MODULE_NAMES['imscp'] => 'IMS-Content',
    $MODULE_NAMES['label'] => 'Textfeld',
    $MODULE_NAMES['lesson'] => 'Lektion',
    $MODULE_NAMES['lightboxgallery'] => 'Lightbox Galerie',
    $MODULE_NAMES['lti'] => 'Externes Tool',
    $MODULE_NAMES['page'] => 'Textseite',
    $MODULE_NAMES['quiz'] => 'Test',
    $MODULE_NAMES['quizinvideo'] => 'Quiz-in-Video',
    $MODULE_NAMES['resource'] => 'Datei',
    $MODULE_NAMES['scorm'] => 'Lernpaket',
    $MODULE_NAMES['survey'] => 'Umfrage',
    $MODULE_NAMES['url'] => 'Link/URL',
    $MODULE_NAMES['wiki'] => 'Wiki',
    $MODULE_NAMES['workshop'] => 'Gegenseitige Beurteilung'
];

# the values of each cluster is the DB-entry-id and will set by modulecluster.class
$MODULE_CLUSTER = [];
$MODULE_CLUSTER['material'] = [
    $MODULE_NAMES['book'] => null,
    $MODULE_NAMES['resource'] => null,
    $MODULE_NAMES['digitalization'] => null,
    $MODULE_NAMES['lightboxgallery'] => null,
    $MODULE_NAMES['url'] => null,
    $MODULE_NAMES['label'] => null,
    $MODULE_NAMES['page'] => null,
    $MODULE_NAMES['folder'] => null
];
$MODULE_CLUSTER['orga-and-groups'] = [
    $MODULE_NAMES['choice'] => null,
    $MODULE_NAMES['choicegroup'] => null
];
$MODULE_CLUSTER['communication'] = [
    $MODULE_NAMES['adobeconnect'] => null,
    $MODULE_NAMES['chat'] => null,
    $MODULE_NAMES['feedback'] => null,
    $MODULE_NAMES['forum'] => null
];
$MODULE_CLUSTER['collaboration'] = [
    $MODULE_NAMES['data'] => null,
    $MODULE_NAMES['etherpadlite'] => null,
    $MODULE_NAMES['glossary'] => null,
    $MODULE_NAMES['lesson'] => null,
    $MODULE_NAMES['wiki'] => null
];
$MODULE_CLUSTER['grading'] = [
    $MODULE_NAMES['assign'] => null,
    $MODULE_NAMES['workshop'] => null,
    $MODULE_NAMES['quizinvideo'] => null,
    $MODULE_NAMES['quiz'] => null
];

$DICT_CLUSTERNAME = [
    'material' => 'Materialbereitstellung',
    'orga-and-groups' => 'Organisation und Gruppenbildung',
    'communication' => 'Kommunikation',
    'collaboration' => 'Zusammenarbeit',
    'grading' => 'Bewertung'
];


# ==============================================================================
# CLASSES - INSTANCES
// magic function to load classes dynamicaly
function classAutoloader($class_name) {
    include 'class/' . strtolower($class_name) . '.class.php';
}

// register auto loader function
spl_autoload_register("classAutoloader");

// create instances of all classes
/*
 * class hierachy:
 * db
 * - category
 * -- semester
 * -- course
 * --- module
 * ---- courseModule
 * --- block
 * --- forum
 * --- modulecluster
 * --- ...
 * 
 */
$db = DB::getInstance();
$user = User::getInstance();

$category = Category::getInstance();
$semester = Semester::getInstance();

$course = Course::getInstance();
$module = Module::getInstance();
$courseModule = new CourseModules($category, $course, $module);

$block = Block::getInstance();

$forum = Forum::getInstance();

$moduleCluster = ModuleCluster::getInstance();


# ==============================================================================
# ADDITIONAL FUNCTIONS
# 
// include default / additional functions
include_once './system/function.php';
