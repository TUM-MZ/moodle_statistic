<h3>
    <span class="fa fa-toggle-off toggle" onclick="$(this).parent().next().slideToggle(); $(this).toggleClass('fa-toggle-off fa-toggle-on');"></span> 
    <span class="headline-summary"><span class="fa fa-link"></span> Allgemein / Zusammenfassung</span></h3>

<div class="box catSummary sort-enabled" data-sort-itemclasses="graph-title,graph-value,faculty-value">
    <div class="catSummary-chart-button-panel" data-relative-to="sumOfAllCatCoursesActive">
        <button class="summary" onclick="createChart(this, '.summary');" data-type="column" data-yaxis-title="Anzahl" data-xaxis-title="Kurse"><span class="fa fa-bar-chart"></span></button>
        <!-- button class="summary" onclick="createChart(this, '.summary');" data-type="doughnut" data-yaxis-title="Anzahl" data-xaxis-title="Kurse"><span class="fa fa-pie-chart"></span></button -->
        <button class="summary" onclick="createChart(this, '.summary');" data-type="stackedBar" data-yaxis-title="Anzahl" data-xaxis-title=""><span class="fa fa-barcode"></span></button>
        <button class="summary" title="Verf&uuml;gbar für komibinierte Auswertung mit einem Datensatz" onclick="createChart(this, '.summary');" data-type="line" data-yaxis-title="Anzahl" data-xaxis-title="Kurse" disabled="disabled"><span class="fa fa-line-chart"></span></button>
    </div>

    <table class="summary" cellpadding="0" cellspacing="5">
        <thead>
            <tr>
                <th class="sort" data-sort="graph-title">Art<span class="fa fa-sort"></span></th>
                <th class="sort" data-sort="graph-value">&sum;<span class="fa fa-sort"></span></th>
                <th onclick="invertSelection('.summary', this);" title="Auswahl umkehren"><span class="fa fa-bar-chart"></span><span class="fa fa-check-square-o"></span></th>
                <th class="sort" data-sort="faculty-value"><span title="Summe: ausschlie&szlig;lich in Fakult&auml;ten" class="fa fa-university"></span><span class="fa fa-sort"></span></th>
            </tr>
        </thead>
        <tbody class="list">
            <tr>
                <td colspan="2">&nbsp;</td>
                <td><input type="checkbox" checked="checked" onclick="selectAllToggle('.summary', this);" onclick="updateCheckbox('.summary', this);"/></td>
                <td class="faculty-value">&nbsp;</td>
            </tr>
            <tr data-type="summary-course-all" title="Alle Kurse">
                <td class="graph-title">Kurse</td>
                <td class="countCourseAll graph-value"><?php echo $course->getSumOfCat(); ?></td>
                <td><input type="checkbox" checked="checked" data-type="summary-course-all" onclick="updateCheckbox('.summary', this);"/></td>
                <td class="faculty-value countCourseFaculty"><?php echo $course->getSumOfCatFac(); ?></td>
            </tr>
            <tr data-type="summary-course-sync" title="Synchronisierte Kurse">
                <td class="graph-title"><span class="fa fa-refresh"><span style="display: none;">Synchronisierte</span></span> Kurse</td>
                <td class="countCourseSync graph-value"><?php echo $course->getSumOfCatSync(); ?></td>
                <td><input type="checkbox" checked="checked" data-type="summary-course-sync" onclick="updateCheckbox('.summary', this);"/></td>
                <td class="faculty-value countCourseSyncFaculty"><?php echo $course->getSumOfCatSyncFac(); ?></td>
            </tr>
            <tr data-type="summary-course-visible" title="Sichtbare Kurse">
                <td class="graph-title"><span class="fa fa-eye"><span style="display: none;">Sichtbare</span></span> Kurse</td>
                <td class="countCourseVisible graph-value"><?php echo $course->getSumOfCatShown(); ?></td>
                <td><input type="checkbox" checked="checked" data-type="summary-course-visible" onclick="updateCheckbox('.summary', this);"/></td>
                <td class="faculty-value countCourseVisibleFaculty"><?php echo $course->getSumOfCatShownFac(); ?></td>
            </tr>
            <tr data-type="summary-course-sync-visible" title="Sichtbare synchronisierte Kurse">
                <td class="graph-title"><span class="fa fa-eye"><span style="display: none;">Sichtbare Synchronisierte</span></span> <span class="fa fa-refresh"></span> Kurse</td>
                <td class="countCourseSyncVisible graph-value"><?php echo $course->getSumOfCatSyncShown(); ?></td>
                <td><input type="checkbox" checked="checked" data-type="summary-course-sync-visible" onclick="updateCheckbox('.summary', this);"/></td>
                <td class="faculty-value countCourseSyncFacultyVisible"><?php echo $course->getSumOfCatSyncShownFac(); ?></td>
            </tr>
            <tr data-type="summary-course-empty" title="Leere Kurse">
                <td class="graph-title"><span class="fa fa-battery-empty"><span style="display: none;">Leere</span></span> Kurse</td>
                <td class="countCourseEmpty graph-value"><?php echo $courseModule->getSumOfEmptyCatCourses(); ?></td>
                <td><input type="checkbox" checked="checked" data-type="summary-course-empty" onclick="updateCheckbox('.summary', this);"/></td>
                <td class="faculty-value">&nbsp;</td>
            </tr>
            <tr data-type="summary-course-unused" title="Ungenutzte Kurse: Kurse haben nur das Modul Forum (min. 1) &amp; Die Foren haben KEINE Beitr&auml;ge">
                <td class="graph-title"><span class="fa fa-battery-1"><span style="display: none;">Ungenutzte</span></span> Kurse</td>
                <td class="countCourseUnused graph-value"><?php echo $courseModule->getEmptyForumCoursesCat(); ?></td>
                <td><input type="checkbox" checked="checked" data-type="summary-course-unused" onclick="updateCheckbox('.summary', this);"/></td>
                <td class="faculty-value">&nbsp;</td>
            </tr>
            <tr data-type="summary-course-active" title="Aktive Kurse: Summe = Kurse - Leere Kurse - Ungenutzte Kurse">
                <td class="graph-title"><span class="fa fa-battery-full"><span style="display: none;">Aktive</span></span> Kurse</td>
                <td class="countCourseActive graph-value"><?php
        $activeCourse = $course->getSumOfCat() - $courseModule->getEmptyForumCoursesCat() - $courseModule->getSumOfEmptyCatCourses();
        echo $activeCourse;
        ?></td>
                <td><input type="checkbox" checked="checked" data-type="summary-course-active" onclick="updateCheckbox('.summary', this);"/></td>
                <td class="faculty-value">&nbsp;</td>
            </tr>
            <tr data-type="summary-used-module-types" title="Verwendete Modultypen">
                <td class="graph-title">verwendete Modultypen</td>
                <td class="countModuleTypesUsed graph-value"><?php echo $courseModule->getSumOfUsedTypes(); ?></td>
                <td><input type="checkbox" checked="checked" data-type="summary-used-module-types" onclick="updateCheckbox('.summary', this);"/></td>
                <td class="faculty-value">&nbsp;</td>
            </tr>
            <tr data-type="summary-sum-module-instances" title="Modulinstanzen">
                <td class="graph-title">Modulinstanzen</td>
                <td class="countModulesCourse graph-value"><?php echo $courseModule->getSumOfAlls(); ?></td>
                <td><input type="checkbox" checked="checked" data-type="summary-sum-module-instances" onclick="updateCheckbox('.summary', this);"/></td>
                <td class="faculty-value">&nbsp;</td>
            </tr>
        </tbody>
    </table>
</div>
