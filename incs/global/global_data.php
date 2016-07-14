<div id="globalSumOfTypes" class="sort-enabled" data-sort-itemclasses="type,typeSum,sumAvailable,initcount">
    <span class="sort" data-sort="initcount" title="Sortierung zurücksetzen"><span class="fa fa-sort"> <span class="fa fa-history"></span></span></span>
    <table>
        <thead>
            <tr><th>&nbsp;</th>
                <th class="sort" data-sort="type">Typ<span class="fa fa-sort"></span></th>
                <th class="sort" data-sort="typeSum"><span title="Anzahl gesamt">&sum;</span><span class="fa fa-sort"></span></th>
                <th class="sort" data-sort="sumAvailable"><span title="Anzahl verf&uuml;gbar" class="fa fa-eye"></span><span class="fa fa-sort"></span></th>
            </tr>
        </thead>
        <tbody class="list">
            <tr>
                <td class="initcount"><span class="hidden">1</span></td>
                <td title="Anzahl aller Kurse" class="type">Kurse</td>
                <td class="typeSum"><?php echo $course->getSumOf(); ?></td>
                <td class="sumAvailable"><?php echo $course->getSumOfShown(); ?></td>
            </tr>
            <tr>
                <td class="initcount"><span class="hidden">2</span></td>
                <td title="Synchronisierte Kurse aus TUMonline" class="type">
                    <span class="fa fa-refresh"></span> Kurse</td>
                <td class="typeSum"><?php echo $course->getSumOfSync(); ?></td>
                <td class="sumAvailable"><?php echo $course->getSumOfSyncShown(); ?></td>
            </tr>
            <tr>
                <td class="initcount"><span class="hidden">3</span></td>
                <td title="Fakult&auml;tskurse" class="type">
                    <span class="fa fa-university"></span> Kurse</td>
                <td class="typeSum"><?php echo $course->getSumOfFac(); ?></td>
                <td class="sumAvailable"><?php echo $course->getSumOfShownFac(); ?></td>
            </tr>
            <tr>
                <td class="initcount"><span class="hidden">4</span></td>
                <td title="Synchronisierte Fakult&auml;tskurse aus TUMonline" class="type">
                    <span class="fa fa-university"></span>
                    <span class="fa fa-refresh"></span> Kurse</td>
                <td class="typeSum"><?php echo $course->getSumOfSyncFac(); ?></td>
                <td class="sumAvailable"><?php echo $course->getSumOfSyncShownFac(); ?></td>
            </tr>
            <tr>
                <td class="initcount"><span class="hidden">5</span></td>
                <td class="type">Module</td>
                <td class="typeSum"><?php echo $module->getSumOfTypesAll(); ?></td>
                <td class="sumAvailable"><?php echo $module->getSumOfTypesAvailable(); ?></td>
            </tr>
            <tr>
                <td class="initcount"><span class="hidden">6</span></td>
                <td class="type">Bl&ouml;cke</td>
                <td class="typeSum"><?php echo $block->getSumOfAll(); ?></td>
                <td class="sumAvailable"><?php echo $block->getSumOfAvailable(); ?></td>
            </tr>
            <tr>
                <td class="initcount"><span class="hidden">7</span></td>
                <td class="type">Nutzer</td>
                <td class="typeSum"><?php echo $user->getSumOfRegistered(); ?></td>
                <td class="sumAvailable"><?php echo $user->getSumOfActive(); ?></td>
            </tr>
            <tr>
                <td class="initcount"><span class="hidden">8</span></td>
                <td class="type">Kurse mit aktiven Foren</td>
                <td class="typeSum"><?php echo $forum->getSumOfActiveCourse(); ?></td>
                <td class="sumAvailable">&nbsp;</td>
            </tr>
            <tr>
                <td class="initcount"><span class="hidden">9</span></td>
                <td class="type">Aktiven Foren</td>
                <td class="typeSum"><?php echo $forum->getSumOfActiveForum(); ?></td>
                <td class="sumAvailable">&nbsp;</td>
            </tr>
            
        </tbody>
    </table>
</div>
