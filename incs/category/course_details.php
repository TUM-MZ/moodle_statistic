<?php
$res = $courseModule->getSumOfCoursesWithMod();
$tmpNo = uniqid();
if ($res->num_rows > 0) {
    ?>

    <h3>
        <span class="fa fa-toggle-off toggle" onclick="$(this).parent().next().slideToggle(); $(this).toggleClass('fa-toggle-off fa-toggle-on');"></span> 
        <span class="headline-courseModuleRelation"><span class="fa fa-link"></span> Anzahl Kurse mit: <small>Materialien & Aktivit&auml;ten</small></span>
    </h3>

    <div class="box courseModuleRelation sort-enabled" data-sort-itemclasses="graph-title,graph-value">

        <div class="courseModuleRelation-chart-button-panel" data-relative-to="sumOfAllCatCoursesActive">
            <button class="courseModuleRelation" onclick="createChart(this, '.courseModuleRelation');" data-type="column" data-yaxis-title="Anzahl Kurse" data-xaxis-title="Materialien / Aktivitäten"><span class="fa fa-bar-chart"></span></button>
            <button class="courseModuleRelation" onclick="createChart(this, '.courseModuleRelation');" data-type="doughnut" data-yaxis-title="Anzahl Kurse" data-xaxis-title="Materialien / Aktivitäten"><span class="fa fa-pie-chart"></span></button>
            <button class="courseModuleRelation" onclick="createChart(this, '.courseModuleRelation');" data-type="stackedBar" data-yaxis-title="Anzahl Kurse" data-xaxis-title="Materialien / Aktivitäten"><span class="fa fa-barcode"></span></button>
            <button class="courseModuleRelation" title="Verf&uuml;gbar für komibinierte Auswertung mit einem Datensatz" onclick="createChart(this, '.courseModuleRelation');" data-type="line" data-yaxis-title="Anzahl Kurse" data-xaxis-title="Materialien / Aktivitäten" disabled="disabled"><span class="fa fa-line-chart"></span></button>
        </div>



        <table class="courseModuleRelation">
            <thead>
                <tr>
                    <th class="sort" data-sort="graph-title">Modulname<span class="fa fa-sort"></span></th>
                    <th class="sort" data-sort="graph-value">&sum; Kurse<span class="fa fa-sort"></span></th>
                    <th onclick="invertSelection('.courseModuleRelation', this);" title="Auswahl umkehren"><span class="fa fa-bar-chart"></span><span class="fa fa-check-square-o"></span></th>
                </tr>
            </thead>
            <tbody class="list">
                <tr>
                    <td colspan="2">
                        <div class="cluster-select-menu">
                            <script type="text/javascript">
                                var cluster;
                                cluster = <?php getJSONObject($MODULE_CLUSTER, 'echo'); ?>;
                            </script>
                            <div class="header"><a href="javascript: void(0);">Clusterselektion <span class="fa fa-arrow-down"></span></a></div>
                            <div class="body">
                                <ul>
                                    <?php
                                    foreach ($MODULE_CLUSTER as $clustername => $modules) {
                                        echo '<li onclick="selectCluster(this, \'' . $clustername . '\', cluster);">' . $DICT_CLUSTERNAME[$clustername] . '</li>';
                                    }
                                    ?>
                                </ul>
                            </div>

                        </div>
                    </td>
                    <td><input type="checkbox" checked="checked" onclick="selectAllToggle('.courseModuleRelation', this);" onclick="updateCheckbox('.courseModuleRelation', this);"/></td>
                </tr>

                <?php while ($c = $res->fetch_object()) { ?>
                    <tr data-type="course-<?php echo $c->name; ?>" data-name="<?php echo $c->name; ?>">
                        <td class="graph-title"><?php echo $DICT_MODULE[$c->name]; ?></td>
                        <td class="graph-value"><?php echo $c->courses; ?></td>
                        <td><input type="checkbox" checked="checked" data-type="course-<?php echo $c->name; ?>" onclick="updateCheckbox('.courseModuleRelation', this);"/></td>
                    </tr>
                    <?php if (strtolower($c->name) === 'forum') { ?>
                        <tr data-type="course-forum-active" data-name="<?php echo $c->name; ?>-active">
                            <td class="graph-title">Forum (aktiv)</td>
                            <td class="graph-value"><?php echo $forum->getSumOfActiveCourseCat(); ?></td>
                            <td><input type="checkbox" checked="checked" data-type="course-forum-active" onclick="updateCheckbox('.courseModuleRelation', this);"/></td>
                        </tr>
                        <tr data-type="course-forum-news-active" data-name="<?php echo $c->name; ?>-news-active">
                            <td class="graph-title">Nachrichtenforum (aktiv)</td>
                            <td class="graph-value"><?php echo $forum->getSumOfActiveNewsForumCat(); ?></td>
                            <td><input type="checkbox" checked="checked" data-type="course-forum-news-active" onclick="updateCheckbox('.courseModuleRelation', this);"/></td>
                        </tr>
                        <tr data-type="course-forum-discussion-active" data-name="<?php echo $c->name; ?>-discussion-active">
                            <td class="graph-title">Diskussionsforum (aktiv)</td>
                            <td class="graph-value"><?php echo $forum->getSumOfActiveDiscussionForumCat(); ?></td>
                            <td><input type="checkbox" checked="checked" data-type="course-forum-discussion-active" onclick="updateCheckbox('.courseModuleRelation', this);"/></td>
                        </tr>
                        <?php
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
    <?php
}
unset($res);

include_once 'course_details_clustered.php';
?>
