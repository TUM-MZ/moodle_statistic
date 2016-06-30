




// global object to save general data like ajax url
var GLOBALS = {
    init_callback_function_timeout: 20,
    ajax_url: '/moodle_statistic/ajax.php'
};
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
    if ($('#combined_selection').is(':checked')) {
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
    if ($('#combined_selection').is(':checked')) {
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
    combined = $('#combined_selection').is(':checked');
    if (combined) {
        $(wrapper).find('tr[data-type=' + $(caller).data('type') + '] input[type=checkbox]').each(function () {
            this.checked = caller.checked;
        });
        toggleChartButtons({
            wrapper: wrapper,
            caller: caller,
            combined: combined
        });
    }
}

function updateSumOfCombinedCourses() {
    var sum;
    sum = 0;
    $('.summary .countCourseAll').each(function(){
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
 * removes the tablesorter events, important for updates
 * - called by activateTableSort
 * @returns {undefined}
 */
function removeTableShorter() {
    $('table.sort-enabled')
            .unbind('appendCache applyWidgetId applyWidgets sorton update updateCell')
            .removeClass('tablesorter')
            .find('thead th')
            .unbind('click mousedown')
            .removeClass('header headerSortDown headerSortUp');
}

/**
 * enables the tablesorter for all tables with class 'sort-enabled'
 * @returns {undefined}
 */
function activateTableSort() {
    var sort_tables, heads;
    sort_tables = $('table.sort-enabled');
    removeTableShorter();
    sort_tables.tablesorter();
    heads = sort_tables.find("thead th");
    heads.each(function (i, obj) {
        if ($(this).find('.fa-sort').length === 0) {
            $(this).append('<span class="fa fa-sort"></span>');
        }
    });
}

/**
 * enables the placement sorting of category output panels
 */
function activatePlacementSorting() {
    $("#sortable").sortable({
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


/*******************************************************************************
 * UPDATE CATEGORY CONTAINER
 */

function replaceCategoryOutput(dataObj) {
    var wrapper;
    wrapper = $(dataObj.options.wrapperObj);
    wrapper.html($(dataObj.response).html());
    // initialize / update the table sorter for new and old content
    activateTableSort();
    activatePlacementSorting();
    addAccordionToOutputs();
    updateSumOfCombinedCourses();
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
        
        headlines =  $('h3 > span[class*="headline"]:not(.' + this.className + ')');
        
        headlines.parent().find('.toggle').removeClass('fa-toggle-on fa-toggle-off').addClass('fa-toggle-off');
        headlines.parent().next().hide("slow");
        
        $('h3 span.' + this.className).parent().next('.box').each(
                function () {
                    if (visible) {
                        $(this).slideUp("slow");
                        $(this).prev().find('.toggle').removeClass('fa-toggle-on fa-toggle-off').addClass('fa-toggle-off');
                    } else {
                        $(this).slideDown("slow");
                        $(this).prev().find('.toggle').removeClass('fa-toggle-on fa-toggle-off').addClass('fa-toggle-on');
                    }
                }
        );
    });
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
    switch (options.report) {
        case 'instances':
            modules = getSumOfSelectedModules(options.contentTableRows);
            break;
        case 'course':
        case 'all':
            modules.selection = filterSelectedModules(options.contentTableRows);
            modules.sum = options.countCourses;
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
        chartTitle: $.trim(oC.find('.input-chart-title').val()),
        xAxisAngle: Number($.trim(oC.find('.input-chart-x-angle').val())),
        xAxisMaxWidth: Number($.trim(oC.find('.input-chart-x-max-width').val())),
        showLabelSum: oC.find('.show-label-sum').is(':checked'),
        showLabelPercent: oC.find('.show-label-percent').is(':checked'),
        theme: oC.find('select.chart-theme-seletor').val()
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
 * creates a canvas chart for selected context
 * @param {dom-object} caller: like this
 * @param {string} reference: like class or id
 * @returns {Boolean}
 */
function createChart(caller, reference) {
    var categoryContainer, graphData, data, chartType, chartOptions,
            btn, exportFilename, combined, reportType, sumOfCatSelection;
    // fontsizes for different media targets
    var font, showLegend, optimizedFor, subTitle;
    btn = $(caller);
    chartType = btn.data("type");
    categoryContainer = btn.parents('.category-output');
    if (!checkForDataSelection(categoryContainer, reference)) {
        return false;
    }

    sumOfCatSelection = getSumOfSelectedData(categoryContainer, reference);
    optimizedFor = categoryContainer.find('.target-out-choice input[type=radio]:checked').val();
    combined = ($('#combined_selection').is(':checked')) ? true : false;
    showLegend = (!combined || chartType === 'doughnut') ? false : true;
    subTitle = (!showLegend) ? $(categoryContainer).find('.legend_text').val() : '';
    chartOptions = getChartOptions({optionsContainer: categoryContainer.find('.chart-options')});
    switch (reference) {
        case '.moduleDetails':
            reportType = 'instances';
            break;
        case '.courseModuleRelation':
            reportType = 'course';
            break;
        default:
            reportType = 'all';
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
            title: 36,
            subTitle: 28,
            legend: 14,
            label: 12,
            axisTitle: 24,
            axisX: 13,
            axisY: 16
        }
    };
    if (chartType === 'doughnut') {
        font.size.label = 16;
    }

    switch (optimizedFor) {
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
                    title: 42,
                    subTitle: 32,
                    legend: 24,
                    label: 18,
                    axisTitle: 34,
                    axisX: 28,
                    axisY: 28
                }
            };
            if (chartType === 'doughnut') {
                font.size.label = 26;
            }
            break;
    }

    exportFilename = getExportFilename();
    /*
     * graph creation depends on combined selection
     */
    var catCaller;
    if (combined && chartType !== 'doughnut') {
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
        dataPointOptions = {
            report: reportType,
            chartType: chartType,
            countCourses: sumOfCourses,
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
    // in case of combined statistic, split data group to single groups, if the 
    // selection is only one data
    if (combined && sumOfCatSelection === 1) {
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
            title: btn.data('xaxis-title'),
            titleFontSize: font.size.axisTitle,
            titleFontWeight: font.weight.axisTitle
        },
        axisY: {
            labelFontSize: font.size.axisY,
            labelFontWeight: font.weight.axisY,
            labelFontColor: '#333',
            labelFontFamily: font.family,
            gridColor: '#DDD',
            title: btn.data('yaxis-title'),
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
            cursor: 'pointer'
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
    $(button).parents('.chart-options').find('.chart-options-slider').slideToggle();
}





/*
 * DOCUMENT READY ACTION
 */
(function ($) {

    activateTableSort();
    addNewCategoryOutput();




})(jQuery);
