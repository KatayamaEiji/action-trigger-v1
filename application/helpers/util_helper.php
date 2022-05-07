<?php

/**
 * データベース：フラグ変換
 */
function convDBFlag($flg){
	if($flg == 0){
		return false;
	}
	return true;
}

/**
 * 配列カウント
 */
function arrayCount($ary){
	if (is_array($ary)) {
		return count($ary);
	}
	return 0;
}

//***************************************
// 日時の差を計算
//***************************************
function getFromToDayDiff($timeFrom, $timeTo) 
{
	// 日時差を秒数で取得
	$dif = $timeTo - $timeFrom;
	// 時間単位の差
	$difTime = date("H:i:s", $dif);
	// 日付単位の差
	$difDays = (strtotime(date("Y-m-d", $dif)) - strtotime("1970-01-01")) / 86400;
	return "{$difDays}days {$difTime}";
}

/**
 * 開始・終了の文字列
 */
function getFromToDayString($dateFrom, $dateTo) 
{
	$week = array('日', '月', '火', '水', '木', '金', '土');

	$dtNow = new DateTime();
	$dtFrom = new DateTime($dateFrom);
	$dtTo = new DateTime($dateTo);

	$wFrom = $dtFrom->format('w');
	$wTo = $dtFrom->format('w');

	if($dtTo->format('Y') == 9999){
		if($dtNow->format('Y') == $dtFrom->format('Y')){
			return $dtFrom->format('n月j日') . "(" . $week[$wFrom] . ") ～ ";
		}
		return $dtFrom->format('Y年n月j日') . "(" . $week[$wFrom] . ")";
	}

	$ret = "";
	if($dtNow->format('Y') == $dtFrom->format('Y')){
		$ret = $dtFrom->format('n月j日') . "(" . $week[$wFrom] . ") ～ ";
		$ret .= $dtTo->format('n月j日') . "(" . $week[$wTo] . ")";
	}
	else{
		$ret = $dtFrom->format('Y年n月j日') . "(" . $week[$wFrom] . ") ～ ";
		$ret .= $dtTo->format('Y年n月j日') . "(" . $week[$wTo] . ")";
	}

	return $ret;
}

/**
 * 現在の日付
 */
function getNowStr(){
	$week = array('日', '月', '火', '水', '木', '金', '土');
	$date = new DateTime('now');

	$ret ="";

	$w = $date->format('w');

	$ret = $date->format('Y年n月j日') . "(" . $week[$w] . ")";

	return $ret ;
}

/**
 * 現在の日付
 */
function getDateMDStr($dt){
	$week = array('日', '月', '火', '水', '木', '金', '土');

	$date = new DateTime($dt);
	$ret ="";

	$w = $date->format('w');
	$m = str_pad($date->format('n'), 2, " ", STR_PAD_LEFT);
	$d = str_pad($date->format('j'), 2, " ", STR_PAD_LEFT);

	$ret = $m . "月" . $d . "日 (" . $week[$w] . ")";

	return $ret ;
}

/**
 * 時間の文字を取得
 */
function getTimeNum($val){
	if(strlen($val) < 5){
		return 0;
	}
	$time = $val;
	if(strlen($val) == 5){
		// 05:00
		$time = $val . ":00";
	}
	$items = explode(":",$time);

	$ret = intval($items[0]) + (intval($items[1]) * 60) + (intval($items[2]) * 3600);

	return $ret;
}

/**
 * 時間の文字を取得
 */
function convTimeStrFromSec($val){
	$h = floor($val / 3600);
	$m = floor($val % 3600 / 60);
	$s = floor($val % 3600 % 60);

	$ret = 	str_pad($h, 2, 0, STR_PAD_LEFT) . ":" . 
			str_pad($m, 2, 0, STR_PAD_LEFT) . ":" .
			str_pad($s, 2, 0, STR_PAD_LEFT);

	return $ret;
}

/**
 * 表示用の時間文字を取得
 */
function dispTimeStrFromSec($dtFrom,$dtTo){
	$dtFrom = new DateTime($dtFrom->format('Y-m-d H:i'));
	$dtTo = new DateTime($dtTo->format('Y-m-d H:i'));

	$interval = $dtTo->diff($dtFrom);

	$h = $interval->h;
	$m = $interval->i;

	if($h != 0){
		$ret = 	 $h . "時間" . $m . "分";
	}
	else{
		$ret = 	 $m . "分";
	}
	return $ret;
}

/**
 * DB Datetimeから日付の文字を取得
 */
function convDateStrFromDBDateTime($val){
	$dt = new DateTime($val);
	return $dt->format('Y-m-d');
}
// 表示用
function convDateStrFromDBDateTime2($val){
	$dt = new DateTime($val);
	return $dt->format('Y/m/d');
}

/**
 * DB Datetimeから日付の文字を取得
 */
function convTimeStrFromDBDateTime($val){
	$dt = new DateTime($val);
	return $dt->format('H:i');
}

/*
* 複数行ある文字列をjavascript文字列に変換
*/
function js_multi_string($val){ 
	$val = str_replace(array("\r\n", "\r", "\n"), "\\r", $val);

	return $val;
}

/*
* 複数行ある文字列をjavascript文字列に変換
*/
function js_bool_string($val){ 
	return ($val)? 'true':'false';
}

/*
* 複数行ある文字列をjavascript文字列に変換
*/
function js_numeric_string($val){ 
	return ($val !== "" && $val !== null)? strval($val):'null';
}


/**
 * 現在の日付
 */
function getDateDispStr($dt){
	$week = array('日', '月', '火', '水', '木', '金', '土');
	$ret = "";

	// 2010/01
	if(preg_match("/^[0-9]{4}\/[0-9]{2}$/u", $dt)){
		
		$date = new DateTime($dt . "/01");
		$ret ="";
	
		$y = $date->format('Y');
		$m = str_pad($date->format('n'), 2, " ", STR_PAD_LEFT);
	
		$ret = $y . "年" . $m . "月";
	}
	// 2010/01/01
	else if(preg_match("/^[0-9]{4}\/[0-9]{2}\/[0-9]{2}$/u", $dt)){
		
		$date = new DateTime($dt);
		$ret ="";
	
		$w = $date->format('w');
		$y = $date->format('Y');
		$m = str_pad($date->format('n'), 2, " ", STR_PAD_LEFT);
		$d = str_pad($date->format('j'), 2, " ", STR_PAD_LEFT);
	
		$ret = $y . "年" . $m . "月" . $d . "日 (" . $week[$w] . ")";
	}
	// 2010
	else if(preg_match("/^[0-9]{4}$/u", $dt)){
		$ret = $dt . "年";
	}

	return $ret ;
}


/**
 * 日付文字列をDateTime型に変換
 */
function convDateTime($dt){
	$ret = "";

	// 2010
	if(preg_match("/^[0-9]{4}$/u", $dt)){
		$dateNow = new DateTime('now');
		$ret = new DateTime($dt . "/01/01");
		if($dateNow->format('Y') == $ret->format('Y')){
			return $dateNow;
		}
		return $ret;
	}

	// 2010/01
	if(preg_match("/^[0-9]{4}\/[0-9]{2}$/u", $dt)){
		$dateNow = new DateTime('now');
		$ret = new DateTime($dt . "/01");
		if($dateNow->format('Ym') == $ret->format('Ym')){
			return $dateNow;
		}
		return $ret;
	}
	// 2010/01/01
	if(preg_match("/^[0-9]{4}\/[0-9]{2}\/[0-9]{2}$/u", $dt)){
		
		return new DateTime($dt);
	}


	return $ret ;
}

function getItemsMaxValue($items,$fieldName){
	$maxSumTime = 0;
	foreach ($items as $item) {
		if ($maxSumTime < $item[$fieldName]) {
			$maxSumTime = $item[$fieldName];
		}
	}
	
	return $maxSumTime;
}

function getSumTimeUnit($sumTime){
	$unit = 0;
	if ($sumTime <= 3600) {
		// 最大合計値が60分以下、分単位
		$unit = 60;
	} else {
		// 時単位
		$unit = 3600;
	}

	return $unit;
}

function getUnitName($unit){
	if($unit === 60){
		return "分";
	}
	if($unit === 3600){
		return "時間";
	}
}