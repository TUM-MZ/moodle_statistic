"use strict";

// global object to save general data like ajax url
var GLOBALS = {
    init_callback_function_timeout: 20,
    ajax_url: '/moodle_statistic/ajax.php',
    combinedSelection: false,
    outputConfigPanel: undefined,
    sortList: [],
    chartFontsize: {
        screen: {
            title: 36,
            subTitle: 28,
            legend: 14,
            label: 12,
            axisTitle: 24,
            axisX: 13,
            axisY: 16
        },
        pres: {
            title: 42,
            subTitle: 32,
            legend: 24,
            label: 18,
            axisTitle: 34,
            axisX: 28,
            axisY: 28
        }
    }
};


// COOKIE FUNCTIONS
function setCookie(cname, cvalue, exdays) {
    var newDate, expires;
    newDate = new Date();
    if (cvalue && cvalue !== '') {
        newDate.setDate(newDate.getDate() + Number(exdays));
    } else {
        newDate.setDate(newDate.getDate() + (-1));
    }
    cvalue = encodeURIComponent(cvalue);
    expires = newDate.toUTCString();
    expires = cname + "=" + cvalue + "; expires=" + expires + ";";
    document.cookie = expires;
}

function getCookie(cname) {
    var name, response, ca, c;
    name = cname + "=";
    ca = document.cookie.split(';');

    for (var i = 0; i < ca.length; i++) {
        c = ca[i];
        while (c.charAt(0) === ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) === 0) {
            response = c.substring(name.length, c.length);
            return decodeURIComponent(response);
        }
    }
    return "";
}

function checkCookie(name) {
    var cookie = getCookie(name);
    if (cookie != "") {
        alert(name + ": " + cookie);
    } else {
        console.log("no cookie found");
    }
}

function deleteCookie(name) {
    setCookie(name, '');
}

// // global function to load content via ajax, function use jQuery
// the callback function get the target and the options object from param
// param options must be an object
function mdl_loadContent(loadURL, options, json) {
    if (json) {
        $.ajax({dataType: "json"});
    }
    $.ajax({
        type: "GET",
        url: loadURL,
        cache: false,
        success: function (responsedata, evt, responseObject) {
            if (json) {
                responsedata = JSON.parse(responsedata);
            }

            if ((typeof responsedata === 'object' && !$.isEmptyObject(responsedata)) || responsedata.length > 0) {
                if (options.push_target) {
                    $(options.push_target).html(responsedata);
                }
                if (options.callback_function) {
                    // define a object with params for further handling, includes the responsdata and the given options object
                    var callback_object = {response: responsedata, options: options};
                    // call the callback function with the callback_object
                    setTimeout(options.callback_function, GLOBALS.init_callback_function_timeout, callback_object);
                }
            }
        }
    });
}


function invertSelection(wrapper, caller) {
    if (GLOBALS.combinedSelection) {
        $(wrapper + ' input[type=checkbox]').each(function (i, obj) {
            this.checked = !this.checked;
        });
    } else {
        $(caller).parents(wrapper).find('input[type=checkbox]').each(function (i, obj) {
            this.checked = !this.checked;
        });
    }

}
function selectAllToggle(wrapper, caller) {
    if (GLOBALS.combinedSelection) {
        $(wrapper + ' input[type=checkbox]').each(function () {
            this.checked = caller.checked;
        });
    } else {
        $(caller).parents(wrapper).find('input[type=checkbox]').each(function () {
            this.checked = caller.checked;
        });
    }
}

function updateCheckbox(wrapper, caller) {
    var combined;
    if (GLOBALS.combinedSelection) {
        $(wrapper).find('tr[data-type=' + $(caller).data('type') + '] input[type=checkbox]').each(function () {
            this.checked = caller.checked;
        });
        toggleChartButtons({
            wrapper: wrapper,
            caller: caller,
            combined: GLOBALS.combinedSelection
        });
    }
}

function updateSumOfCombinedCourses() {
    var sum;
    sum = 0;
    $('.summary .countCourseAll').each(function () {
        sum += Number($(this).text());
    });
    $('#sum_combined_courses').text(sum);
}

function toggleChartButtons(options) {
    var lineChartBtn, checkedBoxes;
    lineChartBtn = $('button' + options.wrapper + '[data-type="line"]');
    checkedBoxes = $(options.caller).parents('table').find('input[type=checkbox]:checked');

    if (options.combined && checkedBoxes.length === 1) {
        lineChartBtn.each(function () {
            $(this).removeAttr("disabled");
        });
    } else {
        lineChartBtn.attr("disabled", "disabled");
    }
}

/**
 * activate table sorting by class .sort-enabled and the wrapper
 * @param {object} wrapper
 * @returns {Boolean}
 */
function activateTableSort(wrapper) {
    var selector;

    if (!wrapper) {
        return false;
    }

    selector = $(wrapper).find('.sort-enabled');
    selector.each(function () {
        var options;
        options = {};
        if ($(this).data('sort-itemclasses')) {
            options = {valueNames: $(this).data('sort-itemclasses').split(",")};
            GLOBALS.sortList[GLOBALS.sortList.length] = new List(this, options);
        }
    });

    addSyncTableSort(wrapper);
}


/**
 * add the sort functionality to the given context
 * @param {object} wrapper
 * @returns {undefined}
 */
function addSyncTableSort(wrapper) {
    $(wrapper).find('table .sort').click(function () {
        var listContainer, listContainerClass, order, data;

        order = $(this).hasClass('asc') ? 'asc' : 'desc';
        data = $(this).data('sort');

        listContainer = $(this).parents('.sort-enabled');
        listContainerClass = listContainer.attr('class');

        for (var l in GLOBALS.sortList) {
            // sync should only work for category outputs
            if ($(GLOBALS.sortList[l].listContainer).parents('.category-output')) {
                // if the current container not the iterated one
                if (GLOBALS.sortList[l].listContainer !== listContainer.get(0)) {
                    // order the container with the right context
                    if ($(GLOBALS.sortList[l].listContainer).attr("class") === listContainerClass) {
                        GLOBALS.sortList[l].sort(data, {order: order});
                    }
                }
            }
        }
    });
}


/**
 * enables the placement sorting of category output panels
 */
function activatePlacementSorting() {
    $("#arrangeable").sortable({
        handle: '.move'
    });
}

/**
 * function adds an throbber for loading animation on position xyz
 * @param {String} selector for reference object
 * @param {String} position, default = before
 * @returns {Boolean}
 */
function addThrobber(selector, position) {
    var throbbr;
    if (selector === 'undefined' || selector === undefined) {
        return false;
    }
    if (position !== 'undefined' && position !== undefined) {
        position = position.toString().toLowerCase();
    }

    throbbr = '<div class="throbber">';
    throbbr += 'Loading&#8230;</div>';
    switch (position) {
        case 'inside-replace':
            $(selector).html(throbbr);
            break;
        case 'before':
        default:
            $(selector).before(throbbr);
            break;
    }
}


/*******************************************************************************
 * NEW OUTPUT FOR A NEW CATEGORY
 */
/**
 * 
 * @param {string} reference selector for positioning
 * @param {string} position like before, after / default: before
 */
function addNewCategoryOutput(button, position) {
    var option, u, catout, thrbCnt;
    if (button === 'undefined' || button === undefined) {
        button = '#addCategoryOutput';
    }
    if (position === 'undefined' || position === undefined) {
        position = 'before';
    }

// create a new category-output container
    catout = document.createElement('div');
    catout.setAttribute("class", "category-output");
    // create the throbber container wrapper
    thrbCnt = document.createElement('div');
    thrbCnt.setAttribute("style", 'padding-top: 150px; min-width:300px; text-align: center;');
    // append the throbber container wrapper to category-output
    catout.appendChild(thrbCnt);
    // place the new category output container in front of "add button"
    $(button).before(catout);
    // add throbber for waiting animation
    addThrobber(catout, 'inside-replace');
    option = {
        url: GLOBALS.ajax_url,
        wrapperObj: catout,
        callback_function: replaceCategoryOutput
    }
    u = option.url + '?new=category-output';
    mdl_loadContent(u, option);
}

/**
 * open the current panel after loading, based on cookie
 * @returns {undefined}
 */
function openCategoryPanel() {
    var openPanel;
    openPanel = getCookie('openPanel').replace(/ /g, ".");
    if (openPanel) {
        $('.' + openPanel).each(function () {
            if (!$(this).is(':visible')) {
                $(this).show();
            }
        });
    }
}

/**
 * sorting function for selectboxes
 * @param {type} selElem
 * @returns {undefined}
 */
function sortSelect(selElem) {
    var tmpAry = new Array();
    for (var i = 0; i < selElem.options.length; i++) {
        tmpAry[i] = new Array();
        tmpAry[i][0] = selElem.options[i].text;
        tmpAry[i][1] = selElem.options[i].value;
        tmpAry[i][2] = selElem.options[i].selected;
    }
    tmpAry.sort();
    while (selElem.options.length > 0) {
        selElem.options[0] = null;
    }
    for (var i = 0; i < tmpAry.length; i++) {
        var op = new Option(tmpAry[i][0], tmpAry[i][1]);
        if (tmpAry[i][2] === true) {
            op.selected = 'selected';
        }
        selElem.options[i] = op;
    }
    return;
}
/**
 * init function to sort the category selectboxes
 * @returns {undefined}
 */
function sortCategorySelectBox(wrapper) {
    var selects = $(wrapper).find('.select_panel select:first-child');
    selects.each(function () {
        sortSelect(this);
    });
}

/*******************************************************************************
 * UPDATE CATEGORY CONTAINER
 */

function replaceCategoryOutput(dataObj) {
    var wrapper;

    wrapper = $(dataObj.options.wrapperObj);
    wrapper.html($(dataObj.response).html());
    // initialize / update the table sorter for new and old content
    activateTableSort(wrapper);
    activatePlacementSorting();
    addAccordionToOutputs();
    updateSumOfCombinedCourses();
    handleChartOptions();
    openCategoryPanel();
    sortCategorySelectBox(wrapper);
}

/**
 * handle the category content as accordion
 * @returns {undefined}
 */
function addAccordionToOutputs() {

    var allPanels, allHeadlines, outputs;
    outputs = $('.category-output');
    allPanels = outputs.find('h3 + .box');

    allHeadlines = allPanels.prev().find('span[class*=headline]');

    allHeadlines.unbind("click");

    //if (outputs.length === 1) {
    allHeadlines.click(function (evt) {
        var curPanel, visible, headlines;

        curPanel = $(this).parent().next();
        visible = curPanel.is(':visible');

        headlines = $('h3 > span[class*="headline"]:not(.' + this.className + ')');

        headlines.parent().find('.toggle').removeClass('fa-toggle-on fa-toggle-off').addClass('fa-toggle-off');
        headlines.parent().next().hide("slow");

        $('h3 span.' + this.className).parent().next('.box').each(
                function () {

                    if (visible) {
                        $(this).slideUp("slow");
                        $(this).prev().find('.toggle').removeClass('fa-toggle-on fa-toggle-off').addClass('fa-toggle-off');
                        deleteCookie("openPanel");
                    } else {
                        $(this).slideDown("slow");
                        $(this).prev().find('.toggle').removeClass('fa-toggle-on fa-toggle-off').addClass('fa-toggle-on');
                        setCookie("openPanel", this.className, 7);
                    }
                }
        );


    });
}

/**
 * sync all views after change of category
 * @returns {undefined}
 */
function handleChartOptions() {
    var categoryOutputs;
    categoryOutputs = $('.category-output');

    // if the combined selection is active, hide the category chart options
    if (GLOBALS.combinedSelection) {
        categoryOutputs.find('.chart-options').hide();
    }
}
/**
 * 
 * @param {string} reference selector for positioning
 * @param {string} position like before, after / default: before
 */
function switchCategory(selectbox) {
    var option, u, selector, container, thrbCnt, style;
    selector = '.category-output';
    container = $(selectbox).parents(selector);
    style = (container.data('style')) ? container.data('style') : 'course';
    // create the throbber container wrapper
    thrbCnt = document.createElement('div');
    thrbCnt.setAttribute("style", 'padding-top: 150px; min-width:300px; text-align: center;');
    // place the throbber wrapper container instead of current content
    container.html(thrbCnt);
    // add throbber for waiting animation
    addThrobber(thrbCnt, 'inside-replace');
    option = {
        url: GLOBALS.ajax_url,
        wrapperObj: container,
        callback_function: replaceCategoryOutput
    }
    u = option.url + '?replace=' + selectbox.value + '&style=' + style;
    mdl_loadContent(u, option);
}

/**
 * change the filter on category output
 * @param {string} reference selector for positioning
 * @param {string} position like before, after / default: before
 */
function switchView(selectbox, path) {
    var option, u, selector, container, thrbCnt, style;
    selector = '.category-output';
    container = $(selectbox).parents(selector);
    style = (container.data('style')) ? container.data('style') : 'course';
    // create the throbber container wrapper
    thrbCnt = document.createElement('div');
    thrbCnt.setAttribute("style", 'padding-top: 150px; min-width:300px; text-align: center;');
    // place the throbber wrapper container instead of current content
    container.html(thrbCnt);
    // add throbber for waiting animation
    addThrobber(thrbCnt, 'inside-replace');
    option = {
        url: GLOBALS.ajax_url,
        wrapperObj: container,
        callback_function: replaceCategoryOutput
    }
    u = option.url + '?replace=' + path + '&style=' + selectbox.value;
    mdl_loadContent(u, option);
}





/*******************************************************************************
 * CHARTING / GRAPHS
 */
function createModalChartContainer() {
    var modalChart, chartContainer;
    modalChart = $('#modalChart');
    chartContainer = '<div id="chartContainer" style="height: 900px; width: 1600px;"></div>';
    chartContainer += '<button onclick="$(\'#modalChart\').slideToggle(500, remove);">X</button><hr/>';
    if (modalChart.length < 1) {
        $('h2[data-section=output-area]').after('<div id="modalChart">' + chartContainer + '</div>');
    } else {
// clear the chart container for new content
        $('#chartContainer').html("");
    }
}

/**
 * check if a minimum of one type selected
 * @param {object/string} caller
 * @param {string} reference = class, id
 * @returns {Boolean}
 */
function checkForDataSelection(wrapper, reference) {
    if ($(wrapper).find(reference + ' input[type="checkbox"]:checked').length > 0) {
        return true;
    }
    return false;
}

function getSumOfSelectedData(wrapper, reference) {
    var sum;
    sum = $(wrapper).find(reference + ' input[type="checkbox"]:checked').length;
    return sum;
}

/**
 * filters only valid rows with values and summarize the values
 * @param {Array} slctTableRow
 * @returns {Object|with selection and sum}
 */
function getSumOfSelectedModules(slctTableRow) {
    var moduleSelection, sum;
    moduleSelection = [];
    sum = 0;
    // calculate the sum of values
    slctTableRow.each(function (i, obj) {
        if ($(this).find('input[type=checkbox]:checked').length > 0) {
            var val = $(this).find('.graph-value');
            if (val.length > 0) {
                moduleSelection[moduleSelection.length] = this;
                sum += Number(val.text());
            }
        }
    });
    return {selection: moduleSelection, sum: sum};
}

/**
 * filters only the valid rows with values inside
 * @param {Array} slctTableRow
 * @returns {Array|filterSelectedModules.moduleSelection}
 */
function filterSelectedModules(slctTableRow) {
    var moduleSelection;
    moduleSelection = [];
    // calculate the sum of values
    slctTableRow.each(function (i, obj) {
        if ($(this).find('input[type=checkbox]:checked').length > 0) {
            var val = $(this).find('.graph-value');
            if (val.length > 0) {
                moduleSelection[moduleSelection.length] = this;
            }
        }
    });
    return moduleSelection;
}



/**
 * update all empty chart font size inputs with selected defaults
 * @param {object} caller = input click event object
 * @returns {undefined}
 */
function updateChartFontsizeInputs(caller) {
    var chartOptCon, fontInputs, callerValue;

    callerValue = $('input[name=' + caller.name + ']:checked').val();
    chartOptCon = $(caller).parents('.chart-options');

    chartOptCon.find('.fontsize-options input').each(function () {

        switch (this.className) {
            case 'chart-title-fontsize':
                this.value = GLOBALS.chartFontsize[callerValue].title;
                break;
            case 'chart-subtitle-fontsize':
                this.value = GLOBALS.chartFontsize[callerValue].subTitle;
                break;
            case 'chart-axis-x-index-fontsize':
                this.value = GLOBALS.chartFontsize[callerValue].axisX;
                break;
            case 'chart-axis-y-index-fontsize':
                this.value = GLOBALS.chartFontsize[callerValue].axisY;
                break;
            case 'chart-axis-title-fontsize':
                this.value = GLOBALS.chartFontsize[callerValue].axisTitle;
                break;
            case 'chart-legend-fontsize':
                this.value = GLOBALS.chartFontsize[callerValue].legend;
                break;
            case 'chart-description-fontsize':
                this.value = GLOBALS.chartFontsize[callerValue].label;
                break;
        }
    });
}


function prepareDataPoints(options) {
    var dataPoints;
    dataPoints = [];
    // fill the datasets
    $(options.selection).each(function () {
        var title, val, percent, pos, label;
        title = $(this).find('.graph-title').text();
        val = Number($(this).find('.graph-value').text());
        percent = Math.round((100 * val) / options.sum);
        pos = dataPoints.length;
        label = '';
        if (options.chartOptions.showLabelSum) {
            label = val.toString();
        }

        if (options.chartOptions.showLabelPercent) {
            if (label !== '') {
                label += ' (' + percent + '%)';
            } else {
                label = percent + '%';
            }
        }

        switch (options.chartType) {
            case 'doughnut':
                dataPoints[pos] = {
                    label: title,
                    legendText: title + ' ' + percent + '%',
                    y: val
                };
                if (label !== '') {
                    dataPoints[pos].indexLabel = title + ': ' + label;
                } else {
                    dataPoints[pos].indexLabel = title;
                }
                break;
            default:
                dataPoints[pos] = {
                    label: title,
                    legendText: val + '|' + percent + '%',
                    y: val
                };
                if (label !== '') {
                    dataPoints[pos].indexLabel = label;
                }
                break;
        }
    });
    return dataPoints;
}

function collectGraphDatas(options) {
    var dataPoints, modules, prepareOptions;
    dataPoints = [];
    modules = {};

    switch (options.chartOptions.relationBase) {
        case 'sumOfAllCatCourses':
            modules.selection = filterSelectedModules(options.contentTableRows);
            modules.sum = options.sumOfAllCatCourses;
            break;
        case 'sumOfAllCatCoursesActive':
            modules.selection = filterSelectedModules(options.contentTableRows);
            modules.sum = options.sumOfAllCatCoursesActive;
            break;
        default:
            modules = getSumOfSelectedModules(options.contentTableRows);
            break;
    }

    prepareOptions = {
        selection: modules.selection,
        chartType: options.chartType,
        sum: modules.sum,
        chartOptions: options.chartOptions
    };
    dataPoints = prepareDataPoints(prepareOptions);
    return dataPoints;
}

function getChartOptions(options) {
    var oC, response;
    oC = options.optionsContainer;
    response = {
        chartOptimizedFor: oC.find('.target-out-choice input[type=radio]:checked').val(),
        chartTitle: $.trim(oC.find('.input-chart-title').val()),
        xAxisTitle: $.trim(oC.find('.input-x-axis-title').val()),
        xAxisAngle: Number($.trim(oC.find('.input-chart-x-angle').val())),
        xAxisMaxWidth: Number($.trim(oC.find('.input-chart-x-max-width').val())),
        yAxisTitle: $.trim(oC.find('.input-y-axis-title').val()),
        showLabelSum: oC.find('.show-label-sum').is(':checked'),
        showLabelPercent: oC.find('.show-label-percent').is(':checked'),
        theme: oC.find('select.chart-theme-seletor').val(),
        relationBase: oC.find('.relation-base input[type=radio]:checked').val(),
        fontsizeIndexXAxis: Number($.trim(oC.find('.fontsize-options .chart-axis-x-index-fontsize').val())),
        fontsizeIndexYAxis: Number($.trim(oC.find('.fontsize-options .chart-axis-y-index-fontsize').val())),
        fontsizeTitleAxis: Number($.trim(oC.find('.fontsize-options .chart-axis-title-fontsize').val())),
        fontsizeTitle: Number($.trim(oC.find('.fontsize-options .chart-title-fontsize').val())),
        fontsizeSubtitle: Number($.trim(oC.find('.fontsize-options .chart-subtitle-fontsize').val())),
        fontsizeLegend: Number($.trim(oC.find('.fontsize-options .chart-legend-fontsize').val())),
        fontsizeDescription: Number($.trim(oC.find('.fontsize-options .chart-description-fontsize').val()))
    };
    return response;
}

function getExportFilename() {
    var fn, now;
    now = new Date();
    fn = 'Moodle_Chart_' + now.getFullYear() + '-' +
            (now.getMonth() + 1) + '-' + now.getDate() + '-' +
            now.getHours() + now.getMinutes() + now.getSeconds();
    return fn;
}

function transformData(options) {
    var data;
    data = [];
    if (options.data[0].dataPoints.length === 1) {
        for (var i = 0; i < options.data.length; i++) {


            if (i === 0) {
                data[0] = options.data[0];
            } else {
                data[0].dataPoints[i] = options.data[i].dataPoints[0];
            }
            data[0].dataPoints[i].label = options.data[i].legendText;
        }
        data[0].showInLegend = false;
        // return the new sorted data array
        return data;
    }

// return options data in case of more than one data selection is given
    return options.data;
}

/**
 * rewrite the 'data-relative-to' / output type on each chart-button-panel
 * @param {dom object} radioBtn
 * @returns {undefined}
 */
function changeRelativeTo(radioBtn) {
    var catCon, btnPanel;

    catCon = $(radioBtn).parents('.category-output');
    btnPanel = catCon.find('div[data-relative-to]');

    btnPanel.each(function () {
        $(this).data('relative-to', radioBtn.value);
    });
}

/**
 * shortcut function to select elements of the whished cluster
 * cluster object is given in html template
 * @param {string} name = clustername
 * @returns {undefined}
 */
function selectCluster(caller, name, cluster) {
    var catCon, checkBoxes, search, modules;

    if (!GLOBALS.combinedSelection) {
        catCon = $(caller).parents('.category-output');
    } else {
        catCon = $('.category-output');
    }

    checkBoxes = catCon.find('.courseModuleRelation input[type=checkbox]');
    checkBoxes.attr("checked", false);

    search = [];
    modules = Object.keys(cluster[name]);
    for (var module in modules) {
        search[module] = '.courseModuleRelation tr[data-type=course-' + modules[module] + '] input[type=checkbox]';
    }
    search = search.join(", ");

    catCon.find(search).each(function () {
        this.checked = true;
    });

}

/**
 * creates a canvas chart for selected context
 * @param {dom-object} caller: like this
 * @param {string} reference: like class or id
 * @returns {Boolean}
 */
function createChart(caller, reference) {
    var categoryContainer, graphData, data, chartType, chartOptions,
            btn, exportFilename, sumOfCatSelection;
    var sumOfCoursesActive;
    // fontsizes for different media targets
    var font, showLegend, subTitle;
    btn = $(caller);
    chartType = btn.data("type");
    categoryContainer = btn.parents('.category-output');
    if (!checkForDataSelection(categoryContainer, reference)) {
        return false;
    }

    sumOfCatSelection = getSumOfSelectedData(categoryContainer, reference);
    showLegend = (!GLOBALS.combinedSelection || chartType === 'doughnut') ? false : true;
    subTitle = (!showLegend) ? $(categoryContainer).find('.legend_text').val() : '';

    if (GLOBALS.combinedSelection) {
        chartOptions = getChartOptions({optionsContainer: GLOBALS.outputConfigPanel.find('.additional .chart-options')});
    } else {
        chartOptions = getChartOptions({optionsContainer: categoryContainer.find('.chart-options')});
    }

    // default definition of fontsize, based on screen
    font = {
        family: '"Helvetica Neue TUM", Arial, sans-serif',
        weight: {
            axisTitle: 'normal',
            axisX: 'normal',
            axisY: 'normal',
            subTitle: 'normal',
            title: 'bold'
        },
        size: {
            title: chartOptions.fontsizeTitle || GLOBALS.chartFontsize.screen.title,
            subTitle: chartOptions.fontsizeSubtitle || GLOBALS.chartFontsize.screen.subTitle,
            legend: chartOptions.fontsizeLegend || GLOBALS.chartFontsize.screen.legend,
            label: chartOptions.fontsizeDescription || GLOBALS.chartFontsize.screen.label,
            axisTitle: chartOptions.fontsizeTitleAxis || GLOBALS.chartFontsize.screen.axisTitle,
            axisX: chartOptions.fontsizeIndexXAxis || GLOBALS.chartFontsize.screen.axisX,
            axisY: chartOptions.fontsizeIndexYAxis || GLOBALS.chartFontsize.screen.axisY
        }
    };
    if (chartType === 'doughnut') {
        font.size.label = chartOptions.fontsizeIndexXAxis || GLOBALS.chartFontsize.screen.axisX || chartOptions.fontsizeIndexYAxis || GLOBALS.chartFontsize.screen.axisY;
    }

    switch (chartOptions.chartOptimizedFor) {
        case 'pres':
            font = {
                family: '"Helvetica Neue TUM", Arial, sans-serif',
                weight: {
                    axisTitle: 'bold',
                    axisX: 'normal',
                    axisY: 'normal',
                    subTitle: 'normal',
                    title: 'bold'
                },
                size: {
                    title: chartOptions.fontsizeTitle || GLOBALS.chartFontsize.pres.title,
                    subTitle: chartOptions.fontsizeSubtitle || GLOBALS.chartFontsize.pres.subTitle,
                    legend: chartOptions.fontsizeLegend || GLOBALS.chartFontsize.pres.legend,
                    label: chartOptions.fontsizeDescription || GLOBALS.chartFontsize.pres.label,
                    axisTitle: chartOptions.fontsizeTitleAxis || GLOBALS.chartFontsize.pres.axisTitle,
                    axisX: chartOptions.fontsizeIndexXAxis || GLOBALS.chartFontsize.pres.axisX,
                    axisY: chartOptions.fontsizeIndexYAxis || GLOBALS.chartFontsize.pres.axisY
                }
            };
            if (chartType === 'doughnut') {
                font.size.label = chartOptions.fontsizeIndexXAxis || chartOptions.fontsizeIndexYAxis || 26;
            }
            break;
    }

    exportFilename = getExportFilename();
    /*
     * graph creation depends on GLOBALS.combinedSelection selection
     */
    var catCaller;
    if (GLOBALS.combinedSelection && chartType !== 'doughnut') {
        catCaller = $('button[data-type=' + btn.data('type') + '][class=' + btn.get(0).className + ']');
    } else {
        catCaller = $(caller);
    }

    data = [];
    catCaller.each(function () {
        var dataPointOptions, catCon, rows, legendLabel, sumOfCourses;
        catCon = $(this).parents('.category-output');
        rows = catCon.find(reference + ' tbody tr');
        legendLabel = catCon.find('.legend_text').val();

        sumOfCourses = Number(catCon.find('.summary .countCourseAll').text()) || 0;
        sumOfCoursesActive = Number(catCon.find('.summary .countCourseActive').text()) || 0;



        dataPointOptions = {
            chartType: chartType,
            sumOfAllCatCourses: sumOfCourses,
            sumOfAllCatCoursesActive: sumOfCoursesActive,
            contentTableRows: rows,
            chartOptions: chartOptions
        };

        data[data.length] = {
            type: chartType,
            indexLabelFontFamily: font.family,
            indexLabelFontSize: font.size.label,
            indexLabelFontColor: 'black',
            showInLegend: showLegend,
            legendText: legendLabel,
            connectNullData: true,
            dataPoints: collectGraphDatas(dataPointOptions)
        };
    });
    var dataOptions = {
        data: data
    };
    // in case of GLOBALS.combinedSelection statistic, split data group to single groups, if the 
    // selection is only one data
    if (GLOBALS.combinedSelection && sumOfCatSelection === 1) {
        data = transformData(dataOptions);
    }

    CanvasJS.addCultureInfo("de",
            {
                decimalSeparator: ",",
                digitGroupSeparator: ".",
                days: ["Montag", "Dienstag", "Mittwoch", "Donnerstag", "Freitag", "Samstag", "Sonntag"],
                shortDays: ["Mo", "Di", "Mi", "Do", "Fr", "Sa", "So"],
                months: ["Januar", "Februar", "März", "April", "Mai", "Juni", "Juli", "August", "September", "Oktober", "November", "Dezember"],
                shortMonths: ["Jan", "Feb", "Mär", "Apr", "Mai", "Jun", "Jul", "Aug", "Sep", "Okt", "Nov", "Dez"],
                savePNGText: '<span class="fa fa-download"></span> PNG',
                saveJPGText: '<span class="fa fa-download"></span> JPG',
                menuText: 'Optionen',
                panText: 'Ausschnitt verschieben',
                resetText: 'Zurücksetzen'
            });
    graphData = {
        animationEnabled: true,
        axisX: {
            labelAutoFit: true,
            labelMaxWidth: chartOptions.xAxisMaxWidth,
            labelAngle: chartOptions.xAxisAngle,
            labelFontSize: font.size.axisX,
            labelFontWeight: font.weight.axisX,
            labelFontColor: '#002143',
            labelFontFamily: font.family, 
           title: chartOptions.xAxisTitle || btn.data('xaxis-title'),
            titleFontSize: font.size.axisTitle,
            titleFontWeight: font.weight.axisTitle
        },
        axisY: {
            labelFontSize: font.size.axisY,
            labelFontWeight: font.weight.axisY,
            labelFontColor: '#333',
            labelFontFamily: font.family,
            gridColor: '#DDD',
            title: chartOptions.yAxisTitle || btn.data('yaxis-title'),
            titleFontSize: font.size.axisTitle,
            titleFontWeight: font.weight.axisTitle
        },
        culture: 'de',
        exportEnabled: true,
        exportFileName: exportFilename,
        legend: {
            fontSize: font.size.legend,
            verticalAlign: 'bottom',
            horizontalAlign: 'left',
            itemclick: function (e) {
                if (typeof (e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
                    e.dataSeries.visible = false;
                } else {
                    e.dataSeries.visible = true;
                }
                chart.render();
            },
            cursor: 'pointer',
            maxWidth: 1450
        },
        theme: chartOptions.theme,
        title: {
            text: chartOptions.chartTitle,
            fontSize: font.size.title,
            fontWeight: font.weight.title
        },
        zoomEnabled: true,
        data: data
    };
    if (subTitle !== '') {
        graphData.subtitles = [{
                text: subTitle
            }];
    }

    // create chart container
    createModalChartContainer();

    // create and render chart
    var chart = new CanvasJS.Chart('chartContainer', graphData);
    chart.render();
}

/**
 * save the drawed graph as picture
 * @param {dom-object} button like this
 */
function saveGraph(button) {
    var canvas;
    canvas = $('#chartContainer canvas').get(0);
    button.href = canvas.toDataURL('image/png');
}

/**
 * Show the graph options
 * @param {dom-object} show options button
 */
function toggleChartOptions(button) {
    $(button).parents('.chart-options').find('.chart-options-config').slideToggle();
}

/**
 * watch the status of combined selection and handle dependencies
 * @param {event} evt
 * @returns {undefined}
 */
function watchCombinedSelection(evt) {
    GLOBALS.combinedSelection = $(this).is(':checked');

    if (GLOBALS.combinedSelection) {
        GLOBALS.outputConfigPanel.find('.additional').show();
        $('.category-output .chart-options').hide();
    } else {
        GLOBALS.outputConfigPanel.find('.additional').hide();
        $('.category-output .chart-options').show();
    }
}


/*
 * DOCUMENT READY ACTION
 */
(function ($) {
    activateTableSort('#global_info');
    addNewCategoryOutput();
    GLOBALS.outputConfigPanel = $('.output-config');
    GLOBALS.outputConfigPanel.find('#combined_selection').change(watchCombinedSelection);

})(jQuery);
