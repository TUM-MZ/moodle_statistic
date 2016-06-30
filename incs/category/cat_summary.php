<h3>
    <span class="fa fa-toggle-off toggle" onclick="$(this).parent().next().slideToggle(); $(this).toggleClass('fa-toggle-off fa-toggle-on');"></span> 
    <span class="headline-summary"><span class="fa fa-link"></span> Allgemein / Zusammenfassung</span></h3>

<div class="box">
    <div class="catSummary-chart-button-panel">
        <button class="summary" onclick="createChart(this, '.summary');" data-type="column" data-yaxis-title="Anzahl" data-xaxis-title="Kurse"><span class="fa fa-bar-chart"></span></button>
        <!-- button class="summary" onclick="createChart(this, '.summary');" data-type="doughnut" data-yaxis-title="Anzahl" data-xaxis-title="Kurse"><span class="fa fa-pie-chart"></span></button -->
        <button class="summary" onclick="createChart(this, '.summary');" data-type="stackedBar" data-yaxis-title="Anzahl" data-xaxis-title=""><span class="fa fa-barcode"></span></button>
        <button class="summary" title="Verf&uuml;gbar für komibinierte Auswertung mit einem Datensatz" onclick="createChart(this, '.summary');" data-type="line" data-yaxis-title="Anzahl" data-xaxis-title="Kurse" disabled="disabled"><span class="fa fa-line-chart"></span></button>
    </div>

    <table class="summary sort-enabled" cellpadding="0" cellspacing="5">
        <thead>
            <tr>
                <th>Art</th>
                <th>&sum;</th>
                <th><span class="fa fa-bar-chart"></span></th>
                <th><span title="Summe: ausschlie&szlig;lich in Fakult&auml;ten" class="fa fa-university"></span></th>
            </tr>
        </thead>
        <tbody>
            <tr data-type="summary-course-all">
                <td class="graph-title">Kurse</td>
                <td class="countCourseAll graph-value"><?php echo $course->getSumOfCat(); ?></td>
                <td><input type="checkbox" checked="checked" data-type="summary-course-all" onclick="updateCheckbox('.summary', this);"/></td>
                <td class="countCourseFaculty"><?php echo $course->getSumOfCatFac(); ?></td>
            </tr>
            <tr data-type="summary-course-sync">
                <td class="graph-title"><span class="fa fa-refresh"><span style="display: none;">Synchronisierte</span></span> Kurse</td>
                <td class="countCourseSync graph-value"><?php echo $course->getSumOfCatSync(); ?></td>
                <td><input type="checkbox" checked="checked" data-type="summary-course-sync" onclick="updateCheckbox('.summary', this);"/></td>
                <td class="countCourseSyncFaculty"><?php echo $course->getSumOfCatSyncFac(); ?></td>
            </tr>
            <tr data-type="summary-course-visible">
                <td class="graph-title"><span class="fa fa-eye"><span style="display: none;">Sichtbare</span></span> Kurse</td>
                <td class="countCourseVisible graph-value"><?php echo $course->getSumOfCatShown(); ?></td>
                <td><input type="checkbox" checked="checked" data-type="summary-course-visible" onclick="updateCheckbox('.summary', this);"/></td>
                <td class="countCourseVisibleFaculty"><?php echo $course->getSumOfCatShownFac(); ?></td>
            </tr>
            <tr data-type="summary-course-sync-visible">
                <td class="graph-title"><span class="fa fa-eye"><span style="display: none;">Sichtbare Synchronisierte</span></span> <span class="fa fa-refresh"></span> Kurse</td>
                <td class="countCourseSyncVisible graph-value"><?php echo $course->getSumOfCatSyncShown(); ?></td>
                <td><input type="checkbox" checked="checked" data-type="summary-course-sync-visible" onclick="updateCheckbox('.summary', this);"/></td>
                <td class="countCourseSyncFacultyVisible"><?php echo $course->getSumOfCatSyncShownFac(); ?></td>
            </tr>
            <tr data-type="summary-used-module-types">
                <td class="graph-title">verwendete Modultypen</td>
                <td class="countModuleTypesUsed graph-value"><?php echo $courseModule->getSumOfUsedTypes(); ?></td>
                <td><input type="checkbox" checked="checked" data-type="summary-used-module-types" onclick="updateCheckbox('.summary', this);"/></td>
                <td>&nbsp;</td>
            </tr>
            <tr data-type="summary-sum-module-instances">
                <td class="graph-title">Modulinstanzen</td>
                <td class="countModulesCourse graph-value"><?php echo $courseModule->getSumOfAlls(); ?></td>
                <td><input type="checkbox" checked="checked" data-type="summary-sum-module-instances" onclick="updateCheckbox('.summary', this);"/></td>
                <td>&nbsp;</td>
            </tr>
        </tbody>
    </table>
</div>
