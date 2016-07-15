<?php $uid = uniqid(); ?>

<div class="chart-options">
    <button class="toggle hide-options" type="button" onClick="toggleChartOptions(this)" title="Chart Optionen"><span class="fa fa-cog"></span></button>

    <div class="target-out-choice">
        Chart optimiert f&uuml;r:
        <input type="radio" value="screen" name="target-out-choice_<?php echo $uid; ?>" id="targetOutScreen_<?php echo $uid; ?>" checked="checked" onclick="updateChartFontsizeInputs(this, this.value);"/>
        <label for="targetOutScreen_<?php echo $uid; ?>"><span class="fa fa-desktop"></span></label>
        <input type="radio" value="pres" name="target-out-choice_<?php echo $uid; ?>" id="targetOutPres_<?php echo $uid; ?>" onclick="updateChartFontsizeInputs(this);"/>
        <label for="targetOutPres_<?php echo $uid; ?>"><span class="fa fa-newspaper-o"></span></label>
    </div>

    <div class='chart-options-config'>
        <div><label for="chart-title-input-<?php echo $uid; ?>">Chart Titel:</label> <input id="chart-title-input-<?php echo $uid; ?>" type="text" class="input-chart-title" placeholder='Optional'/></div>
        <div><label for="chart-x-axis-title-input-<?php echo $uid; ?>">Titel X-Achse:</label> <input id="chart-x-axis-title-input-<?php echo $uid; ?>" type="text" class="input-x-axis-title" placeholder='Optional: Titel X-Achse'/></div>
        <div><label for="chart-y-axis-title-input-<?php echo $uid; ?>">Titel Y-Achse:</label> <input id="chart-y-axis-title-input-<?php echo $uid; ?>" type="text" class="input-y-axis-title" placeholder='Optional: Titel Y-Achse'/></div>
        <div><label for="chart-axis-x-angle-<?php echo $uid; ?>">X-Achse Drehung Index (grad):</label> <input id="chart-axis-x-angle-<?php echo $uid; ?>" type="number" class="input-chart-x-angle" value="0" /></div>
        <div><label for="chart-axis-x-maxw-<?php echo $uid; ?>">X-Achse max Breite:</label> <input id="chart-axis-x-maxw-<?php echo $uid; ?>" type="number" class="input-chart-x-max-width" value="180" /></div>
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
        
        <div>
            <b>Schriftgr&ouml;ße:</b> <span class="fa fa-trash" onclick="$(this).next().find('input').val('');" style="cursor: pointer;"></span>
            <ul style="padding-left: 0;" class="fontsize-options">
                <li>
                    <label for="chart-title-fontsize-<?php echo $uid; ?>">Diagramm-&Uuml;berschrift (px)</label>
                    <input type="number" class="chart-title-fontsize" name="chart-title-fontsize-<?php echo $uid; ?>" id="chart-title-fontsize-<?php echo $uid; ?>" value="" min="16" placeholder="Schriftgr&ouml;&szlig;e in px"/>
                </li>
                <li>
                    <label for="chart-subtitle-fontsize-<?php echo $uid; ?>">Diagramm-Untertitel (px)</label>
                    <input type="number" class="chart-subtitle-fontsize" name="chart-subtitle-fontsize-<?php echo $uid; ?>" id="chart-subtitle-fontsize-<?php echo $uid; ?>" value="" min="14" placeholder="Schriftgr&ouml;&szlig;e in px"/>
                </li>
                <li>
                    <label for="chart-axis-x-index-fontsize-<?php echo $uid; ?>">Index X-Achse (px)</label>
                    <input type="number" class="chart-axis-x-index-fontsize" name="chart-axis-x-index-fontsize-<?php echo $uid; ?>" id="chart-axis-x-index-fontsize-<?php echo $uid; ?>" value="" min="9" placeholder="Schriftgr&ouml;&szlig;e in px"/>
                </li>
                <li>
                    <label for="chart-axis-y-index-fontsize-<?php echo $uid; ?>">Index Y-Achse (px)</label>
                    <input type="number" class="chart-axis-y-index-fontsize" name="chart-axis-y-index-fontsize-<?php echo $uid; ?>" id="chart-axis-y-index-fontsize-<?php echo $uid; ?>" value="" min="9" placeholder="Schriftgr&ouml;&szlig;e in px"/>
                </li>
                <li>
                    <label for="chart-axis-title-fontsize-<?php echo $uid; ?>">Achsentitel (px)</label>
                    <input type="number" class="chart-axis-title-fontsize" name="chart-axis-title-fontsize-<?php echo $uid; ?>" id="chart-axis-title-fontsize-<?php echo $uid; ?>" value="" min="9" placeholder="Schriftgr&ouml;&szlig;e in px"/>
                </li>
                <li>
                    <label for="chart-legend-fontsize-<?php echo $uid; ?>">Legende (px)</label>
                    <input type="number" class="chart-legend-fontsize" name="chart-legend-fontsize-<?php echo $uid; ?>" id="chart-legend-fontsize-<?php echo $uid; ?>" value="" min="9" placeholder="Schriftgr&ouml;&szlig;e in px"/>
                </li>
                <li>
                    <label for="chart-description-fontsize-<?php echo $uid; ?>">Beschriftung (px)</label>
                    <input type="number" class="chart-description-fontsize" name="chart-description-fontsize-<?php echo $uid; ?>" id="chart-description-fontsize-<?php echo $uid; ?>" value="" min="9" placeholder="Schriftgr&ouml;&szlig;e in px"/>
                </li>
            </ul>
        </div>
    </div>

</div>
