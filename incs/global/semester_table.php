<div id="semesterTableView">
    <table class="sort-enabled">
        <thead>
            <tr>
                <th rowspan="2">Semester</th>
                <th colspan="8" class="table-header-sum faculty-start faculty-end">Kurse</th>
                <th colspan="2">Module</th>
            </tr>
            <tr>
                <th class="faculty-start"><span title="Summe: alle">&sum;</span></th>
                <th><span title="Summe: alle sichtbaren" class="fa fa-eye"></span></th>
                <th><span title="Summe: aller synchronisierten Kurse aus TUMonline" class="fa fa-refresh"></span></th>
                <th><span title="Summe: aller sichtbaren synchronisierten Kurse aus TUMonline" class="fa fa-eye"></span></th>
                <th class="faculty-start"><span title="Summe: nur in Fakult&auml;ten vorhandene Kurse" class="fa fa-university"></span></th>
                <th><span title="Summe: alle sichtbaren" class="fa fa-eye"></span></th>
                <th><span title="Summe: aller synchronisierten Kurse aus TUMonline" class="fa fa-refresh"></span></th>
                <th class="faculty-end"><span title="Summe: aller sichtbaren synchronisierten Kurse aus TUMonline" class="fa fa-eye"></span></th>
                <th><span title="Summe: eingesetzte Typen">&sum;</span></th>
                <th><span title="Summe: Instanzen der eingesetzten Typen" class="fa fa-files-o"></span></th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($semester->getAll() as $sem) {
                $category->setActivePath($sem->path);
                ?>
                <tr>
                    <td><?php echo $sem->name; ?></td>
                    <td><?php echo $course->getSumOfCat(); ?></td>
                    <td><?php echo $course->getSumOfCatShown(); ?></td>
                    <td><?php echo $course->getSumOfCatSync(); ?></td>
                    <td><?php echo $course->getSumOfCatSyncShown(); ?></td>
                    <td class="faculty-start"><?php echo $course->getSumOfCatFac(); ?></td>
                    <td><?php echo $course->getSumOfCatShownFac(); ?></td>
                    <td><?php echo $course->getSumOfCatSyncFac(); ?></td>
                    <td class="faculty-end"><?php echo $course->getSumOfCatSyncShownFac(); ?></td>
                    <td><?php echo $courseModule->getSumOfUsedTypes(); ?></td>
                    <td><?php echo $courseModule->getSumOfAlls(); ?></td>
                </tr>
<?php } ?>
        </tbody>
    </table>
</div>