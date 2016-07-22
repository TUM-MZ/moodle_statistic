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



    <div class="chart-options-config">

        <div class="chart-theme">
            <label for="chart-theme-<?php echo $uid; ?>">Theme:</label> 
            <select id="chart-theme-<?php echo $uid; ?>" class="chart-theme-seletor">
                <option value="theme1">1</option>
                <option value="theme2" selected="selected">2</option>
                <option value="theme3">3</option>
            </select>
        </div>

        <div class="chart-title">
            <b>Beschriftungen / Titel:</b> <button onclick="$(this).parent().find('input').val('');"><span class="fa fa-trash"></span></button>
            <dl>
                <dt><label for="chart-title-input-<?php echo $uid; ?>">Titel:</label></dt>
                <dd><input type="text" id="chart-title-input-<?php echo $uid; ?>" class="input-chart-title" placeholder='Optional: Chart Titel'/></dd>

                <dt><label for="chart-subtitle-input-<?php echo $uid; ?>">Untertitel:</label></dt>
                <dd><input type="text" id="chart-subtitle-input-<?php echo $uid; ?>" class="input-chart-subtitle" placeholder='Optional: Chart Untertitel'/></dd>

                <dt><label for="chart-x-axis-title-input-<?php echo $uid; ?>">X-Achse:</label></dt>
                <dd><input type="text" id="chart-x-axis-title-input-<?php echo $uid; ?>" class="input-x-axis-title" placeholder="Optional: Titel X-Achse"/></dd>

                <dt><label for="chart-y-axis-title-input-<?php echo $uid; ?>">Y-Achse:</label></dt>
                <dd><input type="text" id="chart-y-axis-title-input-<?php echo $uid; ?>" class="input-y-axis-title" placeholder="Optional: Titel Y-Achse"/></dd>

                <dt>Label:</dt>
                <dd>
                    <input id="chart-label-sum-<?php echo $uid; ?>" class="show-label-sum" type="checkbox" name="label-sum"/> <label for="chart-label-sum-<?php echo $uid; ?>">Summe</label>
                    <input id="chart-label-percent-<?php echo $uid; ?>" class="show-label-percent" type="checkbox" name="label-percent" checked="checked"/> <label for="chart-label-percent-<?php echo $uid; ?>">Prozent</label>
                </dd>
            </dl>
        </div>

        <div class="x-axis-options">
            <b>Optionen X-Achse:</b> <button onclick="$(this).parent().find('input').each(function() { this.value = $(this).data('default'); });"><span class="fa fa-trash"></span></button>
            <dl>
                <dt><label for="chart-axis-x-angle-<?php echo $uid; ?>">X-Achse Drehung Index (grad):</label></dt>
                <dd><input id="chart-axis-x-angle-<?php echo $uid; ?>" type="number" data-default="0" class="input-chart-x-angle" value="0" /></dd>
                
                <dt><label for="chart-axis-x-maxw-<?php echo $uid; ?>">X-Achse max Breite:</label></dt>
                <dd><input id="chart-axis-x-maxw-<?php echo $uid; ?>" type="number" data-default="180" class="input-chart-x-max-width" value="180" /></dd>
            </dl>
        </div>
        
        <div class="relation-base">
            <b>Relationsbasis:</b>
            <ul>
                <li>
                    <input id="chart-course-base-all-<?php echo $uid; ?>" type="radio" name="course-base-<?php echo $uid; ?>" value="sumOfAllCatCourses" onchange="changeRelativeTo(this);"/>
                    <label for="chart-course-base-all-<?php echo $uid; ?>">Alle Kurse</label>
                </li>
                <li>
                    <input id="chart-course-base-active-<?php echo $uid; ?>" type="radio" name="course-base-<?php echo $uid; ?>" value="sumOfAllCatCoursesActive" checked="checked"  onchange="changeRelativeTo(this);"/>
                    <label for="chart-course-base-active-<?php echo $uid; ?>">Aktive Kurse</label>
                </li>
                <li>
                    <input id="chart-course-base-selection-<?php echo $uid; ?>" type="radio" name="course-base-<?php echo $uid; ?>" value="sumOfAllCatCoursesSelection" onchange="changeRelativeTo(this);"/>
                    <label for="chart-course-base-selection-<?php echo $uid; ?>">Selektion</label>
                </li>
            </ul>
        </div>

        <div class="fontsize-settings">
            <b>Schriftgr&ouml;ße:</b> <button onclick="$(this).next().find('input').val('');"><span class="fa fa-trash"></span></button>
            <dl>
                <dt><label for="chart-title-fontsize-<?php echo $uid; ?>">Diagramm-&Uuml;berschrift (px)</label></dt>
                <dd><input id="chart-title-fontsize-<?php echo $uid; ?>" type="number" class="chart-title-fontsize" name="chart-title-fontsize-<?php echo $uid; ?>" value="" min="16" placeholder="Schriftgr&ouml;&szlig;e in px"/></dd>
                
                <dt><label for="chart-subtitle-fontsize-<?php echo $uid; ?>">Diagramm-Untertitel (px)</label></dt>
                <dd><input id="chart-subtitle-fontsize-<?php echo $uid; ?>" type="number" class="chart-subtitle-fontsize" name="chart-subtitle-fontsize-<?php echo $uid; ?>" value="" min="14" placeholder="Schriftgr&ouml;&szlig;e in px"/></dd>
                
                <dt><label for="chart-axis-x-index-fontsize-<?php echo $uid; ?>">Index X-Achse (px)</label></dt>
                <dd><input id="chart-axis-x-index-fontsize-<?php echo $uid; ?>" type="number" class="chart-axis-x-index-fontsize" name="chart-axis-x-index-fontsize-<?php echo $uid; ?>" value="" min="9" placeholder="Schriftgr&ouml;&szlig;e in px"/></dd>
                
                <dt><label for="chart-axis-y-index-fontsize-<?php echo $uid; ?>">Index Y-Achse (px)</label></dt>
                <dd><input id="chart-axis-y-index-fontsize-<?php echo $uid; ?>" type="number" class="chart-axis-y-index-fontsize" name="chart-axis-y-index-fontsize-<?php echo $uid; ?>" value="" min="9" placeholder="Schriftgr&ouml;&szlig;e in px"/></dd>
                
                <dt><label for="chart-axis-title-fontsize-<?php echo $uid; ?>">Achsentitel (px)</label></dt>
                <dd><input id="chart-axis-title-fontsize-<?php echo $uid; ?>" type="number" class="chart-axis-title-fontsize" name="chart-axis-title-fontsize-<?php echo $uid; ?>" value="" min="9" placeholder="Schriftgr&ouml;&szlig;e in px"/></dd>
                
                <dt><label for="chart-legend-fontsize-<?php echo $uid; ?>">Legende (px)</label></dt>
                <dd><input id="chart-legend-fontsize-<?php echo $uid; ?>" type="number" class="chart-legend-fontsize" name="chart-legend-fontsize-<?php echo $uid; ?>" value="" min="9" placeholder="Schriftgr&ouml;&szlig;e in px"/></dd>
                
                <dt><label for="chart-description-fontsize-<?php echo $uid; ?>">Beschriftung (px)</label></dt>
                <dd><input id="chart-description-fontsize-<?php echo $uid; ?>" type="number" class="chart-description-fontsize" name="chart-description-fontsize-<?php echo $uid; ?>" value="" min="9" placeholder="Schriftgr&ouml;&szlig;e in px"/></dd>
                
            </dl>
        </div>

        <div class="chart-dimension">
            <b>Chartgr&ouml;&szlig;e:</b> <button onclick="$(this).parent().find('input').each(function() { this.value = $(this).data('default'); });"><span class="fa fa-trash"></span></button>
            <dl>
                <dt><label for="chart-dimension-height-<?php echo $uid; ?>">H&ouml;he</label></dt>
                <dd><input id="chart-dimension-height-<?php echo $uid; ?>" type="number" data-default="900" class="chart-dimension-height" min="90" value="900"/></dd>
                
                <dt><label for="chart-dimension-width-<?php echo $uid; ?>">Breite</label></dt>
                <dd><input id="chart-dimension-width-<?php echo $uid; ?>" type="number" data-default="1600" class="chart-dimension-width" min="160" value="1600"/></dd>
            </dl>
        </div>
    </div>

</div>
