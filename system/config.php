<?php

$mdl_configFile = $_SERVER['DOCUMENT_ROOT'] . "/config.php";


if (file_exists($mdl_configFile)) {
    require_once $mdl_configFile;
    require_once($CFG->libdir . '/adminlib.php');

    require_login();
    require_capability('moodle/site:config', context_system::instance());
}

// define the used database table prefix
if (isset($CFG)) {
    define("DB_TABLE_PREFIX", $CFG->prefix);
} else {
    define("DB_TABLE_PREFIX", "mdl_");
}

// define an prefix to separate faculties from other institutions, the prefix
// is used as idnumber in category table
define("FOREIGN_FACULTY_PREFIX", "further");


// dictionary for module names
global $DICT_MODULE;
$DICT_MODULE = [
    'adobeconnect' => 'Adobe Connect',
    'assign' => 'Aufgabe',
    'assignment' => 'Aufgabe (alt)',
    'book' => 'Buch',
    'certificate' => 'Zertifikat',
    'chat' => 'Chat',
    'choice' => 'Abstimmung',
    'choicegroup' => 'Gruppenwahl',
    'data' => 'Datenbank',
    'digitalization' => 'Digit. Semesterapparat',
    'etherpadlite' => 'Etherpad Lite',
    'feedback' => 'Feedback',
    'folder' => 'Verzeichnis',
    'forum' => 'Forum',
    'glossary' => 'Glossar',
    'hotpot' => 'HotPot',
    'imscp' => 'IMS-Content',
    'label' => 'Textfeld',
    'lesson' => 'Lektion',
    'lightboxgallery' => 'Lightbox Galerie',
    'lti' => 'Externes Tool',
    'page' => 'Textseite',
    'quiz' => 'Test',
    'quizinvideo' => 'Quiz-in-Video',
    'resource' => 'Datei',
    'scorm' => 'Lernpaket',
    'survey' => 'Umfrage',
    'url' => 'Link/URL',
    'wiki' => 'Wiki',
    'workshop' => 'Gegenseitige Beurteilung'
];

// magic function to load classes dynamicaly
function classAutoloader($class_name) {
    include 'class/' . strtolower($class_name) . '.class.php';
}
// register auto loader function
spl_autoload_register("classAutoloader");

// create instances of all classes
$db = DB::getInstance();
$user = User::getInstance();

$category = Category::getInstance();
$semester = Semester::getInstance();

$course = Course::getInstance();
$module = Module::getInstance();
$courseModule = new CourseModules($category, $course, $module);

$block = Block::getInstance();

$forum = Forum::getInstance($course);

// include default functions
include_once './system/function.php';
