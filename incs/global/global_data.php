<div id="globalSumOfTypes">

    <table class="sort-enabled">
        <thead>
            <tr>
                <th>Typ</th>
                <th><span title="Anzahl verf&uuml;gbar">&sum;</span></th>
                <th><span title="Anzahl verf&uuml;gbar" class="fa fa-eye"></span></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Bl&ouml;cke</td>
                <td><?php echo $block->getSumOfAll(); ?></td>
                <td><?php echo $block->getSumOfAvailable(); ?></td>
            </tr>
            <tr>
                <td title="Anzahl aller Kurse">Kurse</td>
                <td><?php echo $course->getSumOf(); ?></td>
                <td><?php echo $course->getSumOfShown(); ?></td>
            </tr>
            <tr>
                <td title="Synchronisierte Kurse aus TUMonline">
                    <span class="fa fa-refresh"></span> Kurse</td>
                <td><?php echo $course->getSumOfSync(); ?></td>
                <td><?php echo $course->getSumOfSyncShown(); ?></td>
            </tr>
            <tr>
                <td title="Fakult&auml;tskurse">
                    <span class="fa fa-university"></span> Kurse</td>
                <td><?php echo $course->getSumOfFac(); ?></td>
                <td><?php echo $course->getSumOfShownFac(); ?></td>
            </tr>
            <tr>
                <td title="Synchronisierte Fakult&auml;tskurse aus TUMonline">
                    <span class="fa fa-university"></span>
                    <span class="fa fa-refresh"></span> Kurse</td>
                <td><?php echo $course->getSumOfSyncFac(); ?></td>
                <td><?php echo $course->getSumOfSyncShownFac(); ?></td>
            </tr>
            <tr>
                <td>Module</td>
                <td><?php echo $module->getSumOfTypesAll(); ?></td>
                <td><?php echo $module->getSumOfTypesAvailable(); ?></td>
            </tr>
            <tr>
                <td>Nutzer</td>
                <td><?php echo $user->getSumOfRegistered(); ?></td>
                <td><?php echo $user->getSumOfActive(); ?></td>
            </tr>
            <tr>
                <td>Kurse mit aktiven Foren</td>
                <td><?php echo $forum->getSumOfActiveCourse(); ?></td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>Aktiven Foren</td>
                <td><?php echo $forum->getSumOfActiveForum(); ?></td>
                <td>&nbsp;</td>
            </tr>
            
        </tbody>
    </table>
</div>