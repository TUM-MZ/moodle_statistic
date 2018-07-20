<?php
$legendText = '';
$default = null;

require_once(__DIR__ . "/../../config.php");
require_once($CFG->dirroot . '/moodle_statistic/class/block.class.php');
require_once($CFG->dirroot . '/moodle_statistic/class/category.class.php');
require_once($CFG->dirroot . '/moodle_statistic/class/course.class.php');
require_once($CFG->dirroot . '/moodle_statistic/class/coursemodules.class.php');
require_once($CFG->dirroot . '/moodle_statistic/class/db.class.php');
require_once($CFG->dirroot . '/moodle_statistic/class/forum.class.php');
require_once($CFG->dirroot . '/moodle_statistic/class/module.class.php');
require_once($CFG->dirroot . '/moodle_statistic/class/modulecluster.class.php');
require_once($CFG->dirroot . '/moodle_statistic/class/semester.class.php');
require_once($CFG->dirroot . '/moodle_statistic/class/user.class.php');
?>
<header>
    <div class="action-panel" style="text-align:right;">
        <span class="fa fa-arrows move" title="verschieben"></span>
        <button onclick="$(this).parents('.category-output').remove();" title="l&ouml;schen"><span class="fa fa-trash"></span></button>
    </div>

    <div class="select_panel">
        <select onchange="switchCategory(this);">
            <option>...Kategorieauswahl...</option>
            <?php
            $semstr = $semester->getAll();
            foreach ($category->getAll() as $cat) {
                $padding = ' style="padding-left:' . (($cat->depth - 1) * $padding_base) . 'px;"';
                $selected = '';

                if (isset($activePath) && $activePath === $cat->path) {
                    $selected = ' selected="selected"';
                    $default = $cat;
                }
                $info = '';
                foreach ($semstr as $s) {

                    if (stripos($cat->path, $s->path) === 0) {
                        if ($cat->name === $s->name) {
                            $info = '';
                        } else {
                            $info = ' (' . $s->name . ')';
                        }

                        if (isset($activePath) && $activePath === $cat->path) {
                            $legendText = $s->name;
                        }
                    }
                }
                ?>
                <option <?php echo $padding . $selected; ?> data-depth="<?php echo $cat->depth; ?>" value="<?php echo $cat->path; ?>"><?php echo $cat->name . $info; ?></option>
            <?php } ?>
        </select>
        <br/>
        <select onchange="switchCategory(this);">
            <option>...Semesterauswahl...</option>
            <?php
            $index = 0;
            foreach ($semstr as $sem) {
                $selected = '';
                # activePath is set in ajax.php
                if (!isset($activePath) && $index === 0) {
                    $default = $sem;
                    $selected = ' selected="selected"';
                } else if ($activePath === $sem->path) {
                    if (!$default) {
                        $default = $sem;
                    }
                    $selected = ' selected="selected"';
                    $legendText = $sem->name;
                }
                $index += 1;
                ?>
                <option <?php echo $selected; ?> data-depth="<?php echo $sem->depth; ?>" value="<?php echo $sem->path; ?>"><?php echo $sem->name; ?></option>
            <?php }
            ?>
        </select>
    </div>



    <div class="select_panel">
        <label>Filter: </label>
        <select onchange="switchView(this, '<?php echo $default->path; ?>');">
            <option value="all" <?php
            if (isset($activeView) && $activeView === 'all') {
                echo 'selected="selected"';
            }
            ?>>Alles</option>
            <option value="course" <?php
            if (!isset($activeView) || (isset($activeView) && $activeView === 'course')) {
                echo 'selected="selected"';
            }
            ?>>Allgemein + Kurse</option>
            <option value="module" <?php
            if (isset($activeView) && $activeView === 'module') {
                echo 'selected="selected"';
            }
            ?>>Allgemein + Modulinstanzen</option>
        </select>
    </div>
</header>
<?php
# the activePath was set in ajax.php
$category->setActivePath($default->path);

# set the legendText
if ($legendText != $default->name) {
    if ($legendText === '') {
        $legendText = $default->name;
    } else {
        $legendText .= ' - ' . $default->name;
    }
}
?>
<input type="hidden" class="legend_text" value="<?php echo $legendText; ?>"/>
