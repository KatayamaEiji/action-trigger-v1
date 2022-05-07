<?php
/*--------------------------------------------------
お知らせトップ情報アイテムを取得
--------------------------------------------------*/

require_once('config.php');
require_once('functions.php');

if(!isset($_GET['userInfoID'])) {
	exit("Error");
}
$userInfoID = $_GET['userInfoID'];

$dbh = connectDb();

$sth = $dbh->prepare("
SELECT 
	userInfoID,
	userInfoCode,
	userInfoType,

	userInfoTitle,

	messageType,
	messageText,
	messageURL

FROM 
	TUserInfo
WHERE
    userInfoID = {$userInfoID}
and termType = 1
and CURDATE() between dispDateFrom and dispDateTo
and deleteflg = 0
ORDER BY userInfoCode desc
");
$sth->execute();

if($row = $sth->fetch(PDO::FETCH_ASSOC)){
    $userInfo=array(
    'userInfoID'=>$row['userInfoID'],
    'userInfoCode'=>$row['userInfoCode'],
    'userInfoType'=>$row['userInfoType'],
    'userInfoTitle'=>mb_convert_encoding($row['userInfoTitle'], 'UTF-8', 'EUC-JP'),
    'messageType'=>$row['messageType'],
    'messageText'=>mb_convert_encoding($row['messageText'], 'UTF-8', 'EUC-JP'),
    'messageURL'=>mb_convert_encoding($row['messageURL'], 'UTF-8', 'EUC-JP')
    );
}

//jsonとして出力
header('Content-type: application/json');
echo json_encode($userInfo);
