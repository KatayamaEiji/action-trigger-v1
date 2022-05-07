/**
 * 日程を移動する
 */
function moveDateClick(stepDate) {
    var ymdType = $('#ymdType').val();
    var ymd = $('#ymd').val();

    
    if(ymdType === 'year'){
        var dt = new Date(ymd + "/01/01");

        //1年前
        dt.setFullYear(dt.getFullYear() + stepDate);

        var year = dt.getFullYear();


        $('#ymd').val(String(year));
    }
    if(ymdType === 'month'){
        var dt = new Date(ymd + "/01");

        //1ヶ月前
        dt.setMonth(dt.getMonth() + stepDate);

        var year = dt.getFullYear();
        var month = dt.getMonth() + 1;

        month = ('0' + month).slice(-2);

        $('#ymd').val(String(year) + '/' + month);
    }
    if(ymdType === 'day'){
        var dt = new Date(ymd);

        //1日前
        dt.setDate(dt.getDate() + stepDate);

        var year = dt.getFullYear();
        var month = dt.getMonth() + 1;
        var day = dt.getDate() ;

        month = ('0' + month).slice(-2);
        day = ('0' + day).slice(-2);

        $('#ymd').val(String(year) + '/' + month + '/' + day);
    }

    $('#filter_submit').click();
}

/**
 * サブメニュークリック
 */
function subMenuClick(logType) {
    var actionUrl = "";
    var ymdType = $('#ymdType').val();
    var baseUrl = $('#baseUrl').val()

    if(logType ==='list'){
        if(ymdType === 'year'){
            actionUrl = baseUrl + 'actionlog_year_list/updFilter';    
        }
        else{
            actionUrl = baseUrl + 'actionlog_list/updFilter';
        }
    }else if(logType ==='calendar'){
        if(ymdType === 'year'){
            actionUrl = baseUrl + 'actionlog_year_calendar/updFilter';
        }
        else{
            actionUrl = baseUrl + 'actionlog_calendar/updFilter';
        }
    }else if(logType ==='graph'){
        if(ymdType === 'year'){
            actionUrl = baseUrl + 'actionlog_year_graph/updFilter';
        }
        else{
            actionUrl = baseUrl + 'actionlog_graph/updFilter';
        }
    }
    $('#filterform').attr('action', actionUrl);
    
    $('#filter_submit').click();
}

/**
 * アクションを実行する。
 * @param {*} actionId 
 * @param {*} actionTriggerId 
 */
function runAction(actionId,actionTriggerId){
    var baseUrl = $('#baseUrl').val();
    var dispId = $('#dispId').val();
    var actionFlg = 1; // アクションを即実行する。

    location.href = baseUrl + 'action_run/index/' + dispId + '/' + actionId + '/' +  actionTriggerId +  '/' + actionFlg;
}


/**
 * アクションフィルターの追加
 */
function addFilterAction(actionId){
    $('#actionId').val(actionId);
    
    $('#filter_submit').click();
}

/**
 * アクションフィルターの削除
 */
function delFilterAction(){
    $('#actionId').val('');
    
    $('#filter_submit').click();
}


/**
 * アクショントリガーフィルターの削除
 */
function delFilterActionTrigger(){
    $('#actionTriggerId').val('');
    
    $('#filter_submit').click();
}


/**
 * アクションフィルタ（日）を追加
 */
function addFilterDay(day) {
    $('#ymdType').val('day');
    $('#ymd').val(day);

    var logType = $('#logType').val();
    var baseUrl = $('#baseUrl').val();

    var actionUrl = "";
    if(logType ==='list'){
        actionUrl = baseUrl + 'actionlog_list/updFilter';
    }else if(logType ==='calendar'){
        actionUrl = baseUrl + 'actionlog_calendar/updFilter';
    }else if(logType ==='graph'){
        actionUrl = baseUrl + 'actionlog_graph/updFilter';
    }
    $('#filterform').attr('action', actionUrl);
    
    $('#filter_submit').click();
}

/**
 * 日付フィルターの削除
 */
function delFilterDay(){
    $('#ymdType').val('month');

    var ymd = $('#ymd').val();
    var ym = ymd.substr(0,7);

    $("#ymd").val(ym);

    $('#filter_submit').click();
}

/**
 * 月フィルターの削除
 */
function delFilterMonth(){
    $('#ymdType').val('year');

    var ymd = $('#ymd').val();
    var ym = ymd.substr(0,4);

    $("#ymd").val(ym);

    var baseUrl = $('#baseUrl').val();
    var logType = $('#logType').val();

    var actionUrl = "";
    if(logType ==='list'){
        actionUrl = baseUrl + 'actionlog_year_list/updFilter';
    }else if(logType ==='calendar'){
        actionUrl = baseUrl + 'actionlog_year_calendar/updFilter';
    }else if(logType ==='graph'){
        actionUrl = baseUrl + 'actionlog_year_graph/updFilter';
    }

    $('#filterform').attr('action', actionUrl);

    $('#filter_submit').click();
}

/**
 * 月フィルターの追加
 */
function addFilterMonth(month){
    var year = $('#ymd').val();

    var month = ('0' + month).slice(-2);

    $('#ymdType').val('month');
    $("#ymd").val(year + "/" + month);

    var logType = $('#logType').val();
    var baseUrl = $('#baseUrl').val();

    var actionUrl = "";
    if(logType ==='list'){
        actionUrl = baseUrl + 'actionlog_list/updFilter';
    }else if(logType ==='calendar'){
        actionUrl = baseUrl + 'actionlog_calendar/updFilter';
    }else if(logType ==='graph'){
        actionUrl = baseUrl + 'actionlog_graph/updFilter';
    }

    $('#filterform').attr('action', actionUrl);

    $('#filter_submit').click();
}