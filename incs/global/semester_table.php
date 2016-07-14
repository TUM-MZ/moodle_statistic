<div id="semesterTableView" class="sort-enabled" data-sort-itemclasses="name,sum,sumShown,sumSync,sumShownSync,facSum,facSumShown,facSumSync,facSumShownSync,sumTypesUsed,sumTypes">
    <table>
        <thead>
            <tr>
                <th rowspan="2" class="sort" data-sort="name">Semester<span class="fa fa-sort"></span></th>
                <th colspan="8" class="table-header-sum faculty-start faculty-end">Kurse</th>
                <th colspan="2">Module</th>
            </tr>
            <tr>
                <th class="faculty-start sort" data-sort="sum"><span title="Summe: alle">&sum;</span><span class="fa fa-sort"></span></th>
                <th class="sort" data-sort="sumShown"><span title="Summe: alle sichtbaren" class="fa fa-eye"></span><span class="fa fa-sort"></span></th>
                <th class="sort" data-sort="sumSync"><span title="Summe: aller synchronisierten Kurse aus TUMonline" class="fa fa-refresh"></span><span class="fa fa-sort"></span></th>
                <th class="sort" data-sort="sumShownSync"><span title="Summe: aller sichtbaren synchronisierten Kurse aus TUMonline" class="fa fa-eye"></span><span class="fa fa-sort"></span></th>
                <th class="faculty-start sort" data-sort="facSum"><span title="Summe: nur in Fakult&auml;ten vorhandene Kurse" class="fa fa-university"></span><span class="fa fa-sort"></span></th>
                <th class="sort" data-sort="facSumShown"><span title="Summe: alle sichtbaren" class="fa fa-eye"></span><span class="fa fa-sort"></span></th>
                <th class="sort" data-sort="facSumSync"><span title="Summe: aller synchronisierten Kurse aus TUMonline" class="fa fa-refresh"></span><span class="fa fa-sort"></span></th>
                <th class="faculty-end sort" data-sort="facSumShownSync"><span title="Summe: aller sichtbaren synchronisierten Kurse aus TUMonline" class="fa fa-eye"></span><span class="fa fa-sort"></span></th>
                <th class="sort" data-sort="sumTypesUsed"><span title="Summe: eingesetzte Typen">&sum;</span><span class="fa fa-sort"></span></th>
                <th class="sort" data-sort="sumTypes"><span title="Summe: Instanzen der eingesetzten Typen" class="fa fa-files-o"></span><span class="fa fa-sort"></span></th>
            </tr>
        </thead>
        <tbody class="list">
            <?php
            $i = 0;
            foreach ($semester->getAll() as $sem) {
                $i++;
                $category->setActivePath($sem->path);
                ?>
                <tr>
                    <td class="name"><span style="display: none;"><?php echo $i; ?></span><?php echo $sem->name; ?></td>
                    <td class="sum"><?php echo $course->getSumOfCat(); ?></td>
                    <td class="sumShown"><?php echo $course->getSumOfCatShown(); ?></td>
                    <td class="sumSync"><?php echo $course->getSumOfCatSync(); ?></td>
                    <td class="sumShownSync"><?php echo $course->getSumOfCatSyncShown(); ?></td>
                    <td class="facSum faculty-start"><?php echo $course->getSumOfCatFac(); ?></td>
                    <td class="facSumShown"><?php echo $course->getSumOfCatShownFac(); ?></td>
                    <td class="facSumSync"><?php echo $course->getSumOfCatSyncFac(); ?></td>
                    <td class="facSumShownSync faculty-end"><?php echo $course->getSumOfCatSyncShownFac(); ?></td>
                    <td class="sumTypesUsed"><?php echo $courseModule->getSumOfUsedTypes(); ?></td>
                    <td class="sumTypes"<?php echo $courseModule->getSumOfAlls(); ?></td>
                </tr>
<?php } ?>
        </tbody>
    </table>
</div>
