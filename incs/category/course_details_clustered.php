<?php
$res = $courseModule->getSumOfCoursesWithMod();
$tmpNo = uniqid();

if ($res->num_rows > 0) {
    ?>

    <h3>
        <span class="fa fa-toggle-off toggle" onclick="$(this).parent().next().slideToggle(); $(this).toggleClass('fa-toggle-off fa-toggle-on');"></span> 
        <span class="headline-courseModuleClustered"><span class="fa fa-link"></span> Anzahl Kurse mit: <small>Cluster</small></span>
    </h3>

    <div class="box courseModuleClustered sort-enabled" data-sort-itemclasses="graph-title,graph-value">

        <div class="courseModuleClustered-chart-button-panel" data-relative-to="sumOfAllCatCoursesActive">
            <button class="courseModuleClustered" onclick="createChart(this, '.courseModuleClustered');" data-type="column" data-yaxis-title="Anzahl Kurse" data-xaxis-title="Materialien / Aktivitäten"><span class="fa fa-bar-chart"></span></button>
            <button class="courseModuleClustered" onclick="createChart(this, '.courseModuleClustered');" data-type="doughnut" data-yaxis-title="Anzahl Kurse" data-xaxis-title="Materialien / Aktivitäten"><span class="fa fa-pie-chart"></span></button>
            <button class="courseModuleClustered" onclick="createChart(this, '.courseModuleClustered');" data-type="stackedBar" data-yaxis-title="Anzahl Kurse" data-xaxis-title="Materialien / Aktivitäten"><span class="fa fa-barcode"></span></button>
            <button class="courseModuleClustered" title="Verf&uuml;gbar für komibinierte Auswertung mit einem Datensatz" onclick="createChart(this, '.courseModuleClustered');" data-type="line" data-yaxis-title="Anzahl Kurse" data-xaxis-title="Materialien / Aktivitäten" disabled="disabled"><span class="fa fa-line-chart"></span></button>
        </div>

        <table class="courseModuleClustered">
            <thead>
                <tr>
                    <th class="sort" data-sort="graph-title">Cluster<span class="fa fa-sort"></span></th>
                    <th class="sort" data-sort="graph-value">&sum; Kurse<span class="fa fa-sort"></span></th>
                    <th onclick="invertSelection('.courseModuleClustered', this);" title="Auswahl umkehren"><span class="fa fa-bar-chart"></span><span class="fa fa-check-square-o"></span></th>
                </tr>
            </thead>
            <tbody class="list">
                <tr>
                    <td colspan="2">&nbsp;</td>
                    <td><input type="checkbox" checked="checked" onclick="selectAllToggle('.courseModuleClustered', this);" onclick="updateCheckbox('.courseModuleClustered', this);"/></td>
                </tr>
                <tr data-type="course-module-cluster-material" title="Umfasst: <?php $t = []; foreach($MODULE_CLUSTER['material'] as $module => $val) { $t[] = $DICT_MODULE[$module]; } echo implode(", ", $t); ?>">
                    <td class="graph-title"><?php echo $DICT_CLUSTERNAME['material']; ?></td>
                    <td class="graph-value"><?php echo $moduleCluster->getSumOfClusterCourses('material'); ?></td>
                    <td><input type="checkbox" checked="checked" data-type="course-module-cluster-material" onclick="updateCheckbox('.courseModuleClustered', this);"/></td>
                </tr>
                <tr data-type="course-module-cluster-orga-and-groups" title="Umfasst:  <?php $t = []; foreach($MODULE_CLUSTER['orga-and-groups'] as $module => $val) { $t[] = $DICT_MODULE[$module]; } echo implode(", ", $t); ?>">
                    <td class="graph-title"><?php echo $DICT_CLUSTERNAME['orga-and-groups']; ?></td>
                    <td class="graph-value"><?php echo $moduleCluster->getSumOfClusterCourses('orga-and-groups'); ?></td>
                    <td><input type="checkbox" checked="checked" data-type="course-module-cluster-orga-and-groups" onclick="updateCheckbox('.courseModuleClustered', this);"/></td>
                </tr>
                <tr data-type="course-module-cluster-communication" title="Umfasst:  <?php $t = []; foreach($MODULE_CLUSTER['communication'] as $module => $val) { $t[] = $DICT_MODULE[$module]; } echo implode(", ", $t); ?> |-&gt; Forum muss min. 1 Beitrag haben">
                    <td class="graph-title"><?php echo $DICT_CLUSTERNAME['communication']; ?></td>
                    <td class="graph-value"><?php echo $moduleCluster->getSumOfClusterCourses('communication'); ?></td>
                    <td><input type="checkbox" checked="checked" data-type="course-module-cluster-communication" onclick="updateCheckbox('.courseModuleClustered', this);"/></td>
                </tr>
                <tr data-type="course-module-cluster-collaboration" title="Umfasst:  <?php $t = []; foreach($MODULE_CLUSTER['collaboration'] as $module => $val) { $t[] = $DICT_MODULE[$module]; } echo implode(", ", $t); ?>">
                    <td class="graph-title"><?php echo $DICT_CLUSTERNAME['collaboration']; ?></td>
                    <td class="graph-value"><?php echo $moduleCluster->getSumOfClusterCourses('collaboration'); ?></td>
                    <td><input type="checkbox" checked="checked" data-type="course-module-cluster-collaboration" onclick="updateCheckbox('.courseModuleClustered', this);"/></td>
                </tr>
                <tr data-type="course-module-cluster-grading" title="Umfasst:  <?php $t = []; foreach($MODULE_CLUSTER['grading'] as $module => $val) { $t[] = $DICT_MODULE[$module]; } echo implode(", ", $t); ?>">
                    <td class="graph-title"><?php echo $DICT_CLUSTERNAME['grading']; ?></td>
                    <td class="graph-value"><?php echo $moduleCluster->getSumOfClusterCourses('grading'); ?></td>
                    <td><input type="checkbox" checked="checked" data-type="course-module-cluster-grading" onclick="updateCheckbox('.courseModuleClustered', this);"/></td>
                </tr>
            </tbody>
        </table>
    </div>
    <?php
}
unset($res);
?>
