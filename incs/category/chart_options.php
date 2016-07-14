<?php $uid = uniqid(); ?>

<div class="chart-options">
    <button class="toggle hide-options" type="button" onClick="toggleChartOptions(this)" title="Chart Optionen"><span class="fa fa-cog"></span></button>

    <div class="target-out-choice">
        Chart optimiert f&uuml;r:
        <input type="radio" value="screen" name="target-out-choice_<?php echo $uid; ?>" id="targetOutScreen_<?php echo $uid; ?>" checked="checked"/>
        <label for="targetOutScreen_<?php echo $uid; ?>"><span class="fa fa-desktop"></span></label>
        <input type="radio" value="pres" name="target-out-choice_<?php echo $uid; ?>" id="targetOutPres_<?php echo $uid; ?>"/>
        <label for="targetOutPres_<?php echo $uid; ?>"><span class="fa fa-newspaper-o"></span></label>
    </div>

    <div class='chart-options-config'>
        <div><label for="chart-title-input-<?php echo $uid; ?>">Chart title:</label> <input id="chart-title-input-<?php echo $uid; ?>" type="text" class="input-chart-title" placeholder='Optional'/></div>
        <div><label for="chart-axis-x-angle-<?php echo $uid; ?>">Axis X angle:</label> <input id="chart-axis-x-angle-<?php echo $uid; ?>" type="text" class="input-chart-x-angle" value="0" /></div>
        <div><label for="chart-axis-x-maxw-<?php echo $uid; ?>">Axis X max width:</label> <input id="chart-axis-x-maxw-<?php echo $uid; ?>" type="text" class="input-chart-x-max-width" value="180" /></div>
        <div><label for="chart-label-sum-<?php echo $uid; ?>">Label (Sum): </label> <input id="chart-label-sum-<?php echo $uid; ?>" class="show-label-sum" type="checkbox" name="label-sum"/></div>
        <div><label for="chart-label-percent-<?php echo $uid; ?>">Label (Percent): </label> <input id="chart-label-percent-<?php echo $uid; ?>" class="show-label-percent" type="checkbox" name="label-percent" checked="checked"/></div>
        <div><label for="chart-theme-<?php echo $uid; ?>">Theme:</label> 
            <select id="chart-theme-<?php echo $uid; ?>" class="chart-theme-seletor">
                <option value="theme1">1</option>
                <option value="theme2" selected="selected">2</option>
                <option value="theme3">3</option>
            </select>
        </div>
        <div>
            Relationsbasis:
            <ul class="relation-base">
                <li>
                    <input type="radio" name="course-base-<?php echo $uid; ?>" value="sumOfAllCatCourses" id="chart-course-base-all-<?php echo $uid; ?>" onchange="changeRelativeTo(this);"/>
                    <label for="chart-course-base-all-<?php echo $uid; ?>">Alle Kurse</label>
                </li>
                <li>
                    <input type="radio" name="course-base-<?php echo $uid; ?>" value="sumOfAllCatCoursesActive" id="chart-course-base-active-<?php echo $uid; ?>" checked="checked"  onchange="changeRelativeTo(this);"/>
                    <label for="chart-course-base-active-<?php echo $uid; ?>">Aktive Kurse</label>
                </li>
                <li>
                    <input type="radio" name="course-base-<?php echo $uid; ?>" value="sumOfAllCatCoursesSelection" id="chart-course-base-selection-<?php echo $uid; ?>" onchange="changeRelativeTo(this);"/>
                    <label for="chart-course-base-selection-<?php echo $uid; ?>">Selektion</label>
                </li>
            </ul>
        </div>
    </div>

</div>
