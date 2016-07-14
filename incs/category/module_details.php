<?php
$res = $courseModule->getSumOfModules();
$tmpNo = uniqid();
if ($res->num_rows > 0) {
    ?>

    <h3>
        <span class="fa fa-toggle-off toggle" onclick="$(this).parent().next().slideToggle(); $(this).toggleClass('fa-toggle-off fa-toggle-on');"></span> 
        <span class="headline-moduleInstances"><span class="fa fa-link"></span> Modulinstanzen: <small>Materialien & Aktivit&auml;ten</small></span>
    </h3>

    <div class="box moduleDetails sort-enabled" data-sort-itemclasses="graph-title,graph-value">

        <div class="moduleDetails-chart-button-panel" data-relative-to="sumOfAllCatCoursesActive">
            <button class="moduleDetails" onclick="createChart(this, '.moduleDetails');" data-type="column" data-yaxis-title="Instanzen" data-xaxis-title="Materialien / Aktivitäten"><span class="fa fa-bar-chart"></span></button>
            <button class="moduleDetails" onclick="createChart(this, '.moduleDetails');" data-type="doughnut" data-yaxis-title="Instanzen" data-xaxis-title="Materialien / Aktivitäten"><span class="fa fa-pie-chart"></span></button>
            <button class="moduleDetails" onclick="createChart(this, '.moduleDetails');" data-type="stackedBar" data-yaxis-title="Instanzen" data-xaxis-title="Materialien / Aktivitäten"><span class="fa fa-barcode"></span></button>
            <button class="moduleDetails" title="Verf&uuml;gbar für komibinierte Auswertung mit einem Datensatz" onclick="createChart(this, '.moduleDetail class="moduleDetails"s');" data-type="line" data-yaxis-title="Instanzen" data-xaxis-title="Materialien / Aktivitäten" disabled="disabled"><span class="fa fa-line-chart"></span></button>
        </div>

        <table class="moduleDetails">
            <thead>
                <tr>
                    <th class="sort" data-sort="graph-title">Modulname<span class="fa fa-sort"></span></th>
                    <th class="sort" data-sort="graph-value">&sum; Instanzen<span class="fa fa-sort"></span></th>
                    <th onclick="invertSelection('.moduleDetails', this);" title="Auswahl umkehren"><span class="fa fa-bar-chart"></span><span class="fa fa-check-square-o"></span></th>
                </tr>
            </thead>
            <tbody class="list">
                <tr>
                    <td colspan="2">&nbsp;</td>
                    <td><input type="checkbox" checked="checked" onclick="selectAllToggle('.moduleDetails', this);"/></td>
                </tr>

                <?php while ($c = $res->fetch_object()) { ?>
                    <tr data-type="module-<?php echo $c->name; ?>">
                        <td class="graph-title"><?php echo $DICT_MODULE[$c->name]; ?></td>
                        <td class="graph-value"><?php echo $c->count; ?></td>
                        <td><input type="checkbox" checked="checked" data-type="module-<?php echo $c->name; ?>" onclick="updateCheckbox('.moduleDetails', this);"/></td>
                    </tr>
                    <?php if (strtolower($c->name) === 'forum') { ?>
                        <tr data-type="module-forum-active">
                            <td>Forum (aktiv)</td>
                            <td><?php echo $forum->getSumOfActiveForumCat(); ?></td>
                            <td><input type="checkbox" checked="checked" data-type="module-forum-active" onclick="updateCheckbox('.moduleDetails', this);"/></td>
                        </tr>    
                        <tr data-type="module-forum-xtra">
                            <td class="graph-title">Forum (extra)</td>
                            <td class="graph-value"><?php echo $courseModule->getSumOfXtraForum(); ?></td>
                            <td><input type="checkbox" checked="checked" data-type="module-forum-xtra" onclick="updateCheckbox('.moduleDetails', this);"/></td>
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
?>
