<?php
$res = $courseModule->getSumOfCoursesWithMod();
$tmpNo = uniqid();
if ($res->num_rows > 0) {
    ?>

    <h3>
        <span class="fa fa-toggle-off toggle" onclick="$(this).parent().next().slideToggle(); $(this).toggleClass('fa-toggle-off fa-toggle-on');"></span> 
        <span class="headline-courseModuleRelation"><span class="fa fa-link"></span> Anzahl Kurse mit: <small>Materialien & Aktivit&auml;ten</small></span>
    </h3>

    <div class="box">

        <div class="courseModuleRelation-chart-button-panel">
            <button class="courseModuleRelation" onclick="createChart(this, '.courseModuleRelation');" data-type="column" data-yaxis-title="Anzahl Kurse" data-xaxis-title="Materialien / Aktivitäten"><span class="fa fa-bar-chart"></span></button>
            <button class="courseModuleRelation" onclick="createChart(this, '.courseModuleRelation');" data-type="doughnut" data-yaxis-title="Anzahl Kurse" data-xaxis-title="Materialien / Aktivitäten"><span class="fa fa-pie-chart"></span></button>
            <button class="courseModuleRelation" onclick="createChart(this, '.courseModuleRelation');" data-type="stackedBar" data-yaxis-title="Anzahl Kurse" data-xaxis-title="Materialien / Aktivitäten"><span class="fa fa-barcode"></span></button>
            <button class="courseModuleRelation" title="Verf&uuml;gbar für komibinierte Auswertung mit einem Datensatz" onclick="createChart(this, '.courseModuleRelation');" data-type="line" data-yaxis-title="Anzahl Kurse" data-xaxis-title="Materialien / Aktivitäten" disabled="disabled"><span class="fa fa-line-chart"></span></button>
        </div>

        <table class="courseModuleRelation sort-enabled">
            <thead>
                <tr>
                    <th>Modulname</th>
                    <th>&sum; Kurse</th>
                    <th onclick="invertSelection('.courseModuleRelation', this);"><span class="fa fa-bar-chart"></span></th>
                </tr>
            </thead>
            <tbody>
                <?php while ($c = $res->fetch_object()) { ?>
                    <tr data-type="course-<?php echo $c->name; ?>">
                        <td class="graph-title"><?php echo $DICT_MODULE[$c->name]; ?></td>
                        <td class="graph-value"><?php echo $c->courses; ?></td>
                        <td><input type="checkbox" checked="checked" data-type="course-<?php echo $c->name; ?>" onclick="updateCheckbox('.courseModuleRelation', this);"/></td>
                    </tr>
                    <?php
                }
                ?>

                <tr data-type="course-forum-xtra">
                    <td class="graph-title">Forum - davon extra</td>
                    <td class="graph-value"><?php echo $forum->getSumOfXtraCourseCat(); ?></td>
                    <td><input type="checkbox" checked="checked" data-type="course-forum-xtra" onclick="updateCheckbox('.courseModuleRelation', this);"/></td>
                </tr>
                <tr data-type="course-forum-active">
                    <td class="graph-title">Forum - davon aktiv</td>
                    <td class="graph-value"><?php echo $forum->getSumOfActiveCourseCat(); ?></td>
                    <td><input type="checkbox" checked="checked" data-type="course-forum-active" onclick="updateCheckbox('.courseModuleRelation', this);"/></td>
                </tr>
                <tr>
                    <td colspan="2">&nbsp;</td>
                    <td><input type="checkbox" checked="checked" onclick="selectAllToggle('.courseModuleRelation', this);" onclick="updateCheckbox('.courseModuleRelation', this);"/></td>
                </tr>
            </tbody>
        </table>
    </div>
    <?php
}
unset($res);
?>
