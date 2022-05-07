/**
 * 
 * @param {*} editDialogID 
 * @param {*} validationErrorFlg 
 */
function actionTrigger_init(editDialogID, validationErrorFlg) {
    if (validationErrorFlg) {
        $(editDialogID).modal('show');
    }

    // メッセージ内の×ボタンクリックでメッセージを非表示にする
    $('.alert .close').on('click', function () {
        $(this).parents('.alert').hide();
    });
}

/**
 * 確認メッセージ
 * @param {確認メッセージ} message 
 */
function comConfirm(message) {
    return window.confirm(message);
}

/*
* 画面操作を無効にする
*/
function lockScreen(id, path) {
    /*
    * 現在画面を覆い隠すためのDIVタグを作成する
    */
    var divTag = $('<div class="lockScreen" />').attr("id", id);

    divTag.append("<h1>処理中です。しばらくお待ち下さい。");

    /*
    * BODYタグに作成したDIVタグを追加
    */
    $('body').append(divTag);
}

/*
* 画面操作無効を解除する
*/
function unlockScreen(id) {

    /*
    * 画面を覆っているタグを削除する
    */
    $("#" + id).remove();
}

// 表示ボタンクリックでメッセージを表示
function feadMessage(title, message) {
    var divTag = $('<div class="feadMessage alert-info" />');

    divTag.append('<button type="button" class="close" data-dismiss="feadMessage">×</button>');
    divTag.append('<strong>' + title + '</strong><br />');
    divTag.append(message);

	/*
		* 1秒かけてメッセージを表示し、
		* その後2秒間何もせず、
		* その後2秒かけてメッセージを非表示にする
		*/
    $('body').append(divTag);

    $('.feadMessage').fadeIn(1000).delay(2000).fadeOut(2000);
}

// アクションメニュー表示
function actionMenuModal(event) {
    var button = $(event.relatedTarget); //モーダルを呼び出すときに使われたボタンを取得
    var actionTitle = button.data('action_title');
    var actionTriggerId = button.data('action_trigger_id');
    var actionId = button.data('action_id');
    var dispId = button.data('disp_id');
    var addActionFlg = button.data('add_action_flg');
    var createFlg = button.data('create_flg');

    var modal = $(this);  //モーダルを取得

    $('#actionMenu_actionConf').attr('style', ''); // アクションの確認
    $('#actionMenu_actionLog').attr('style', '');
    $('#actionMenu_actionEdit').attr('style', '');
    $('#actionMenu_actionTriggerEdit').attr('style', '');
    $('#actionMenu_delNowAction').attr('style', '');
    $('#actionMenu_addNowAction').attr('style', '');

    if (actionTriggerId == 0) {
        $('#actionMenu_delNowAction').attr('style', 'display:none');
        $('#actionMenu_addNowAction').attr('style', '');
        $('#actionMenu_actionTriggerEdit').attr('style', 'display:none');
    }
    else {
        $('#actionMenu_delNowAction').attr('style', '');
        $('#actionMenu_addNowAction').attr('style', 'display:none');
        $('#actionMenu_actionTriggerEdit').attr('style', '');
    }

    if (!createFlg) {
        $('#actionMenu_actionEdit').attr('style', 'display:none');
    }

    if (!addActionFlg) {
        $('#actionMenu_actionLog').attr('style', 'display:none');
        $('#actionMenu_actionEdit').attr('style', 'display:none');
        $('#actionMenu_actionTriggerEdit').attr('style', 'display:none');
        $('#actionMenu_delNowAction').attr('style', 'display:none');
        $('#actionMenu_addNowAction').attr('style', 'display:none');
    }

    $('#actionMenuModal_actionId').val(actionId);
    $('#actionMenuModal_actionTriggerId').val(actionTriggerId);
    $('#actionMenuModal_dispId').val(dispId);
    $('#actionMenuModal_actionTitle').text(actionTitle); //モーダルのタイトルに値を表示

}

function getDispDateString(ymdString) {
    // 曜日の配列を作る
    var WeekChars = ["日", "月", "火", "水", "木", "金", "土"];

    // 入力された数値から日付オブジェクトを作る
    var userDate = new Date(ymdString);

    // 日付と曜日を表示する
    return userDate.getFullYear() + "年" +
        (userDate.getMonth() + 1) + "月" +
        userDate.getDate() + "日(" +
        WeekChars[userDate.getDay()] + ")";
}

/*
* Date型を2020/01形式の文字列に変換する
*/
function getYearMonthString(dt) {
    var year = dt.getFullYear();
    var month = dt.getMonth() + 1;

    month = ('0' + month).slice(-2);

    // 日付と曜日を表示する
    return year + "/" + month;
}


//日付から文字列に変換する関数
function getStringFromDate(date) {

    var year_str = date.getFullYear();
    //月だけ+1すること
    var month_str = 1 + date.getMonth();
    var day_str = date.getDate();
    var hour_str = date.getHours();
    var minute_str = date.getMinutes();
    var second_str = date.getSeconds();

    month_str = ('0' + month_str).slice(-2);
    day_str = ('0' + day_str).slice(-2);
    hour_str = ('0' + hour_str).slice(-2);
    minute_str = ('0' + minute_str).slice(-2);
    second_str = ('0' + second_str).slice(-2);

    format_str = 'YYYY-MM-DD hh:mm:ss';
    format_str = format_str.replace(/YYYY/g, year_str);
    format_str = format_str.replace(/MM/g, month_str);
    format_str = format_str.replace(/DD/g, day_str);
    format_str = format_str.replace(/hh/g, hour_str);
    format_str = format_str.replace(/mm/g, minute_str);
    format_str = format_str.replace(/ss/g, second_str);

    return format_str;
};