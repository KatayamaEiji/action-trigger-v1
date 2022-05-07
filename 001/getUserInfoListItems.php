<?php
/*--------------------------------------------------
お知らせリスト情報アイテムを取得
--------------------------------------------------*/

require_once('config.php');
require_once('functions.php');

if(!isset($_GET['countryType'])) {
	exit("Error");
}
$countryType = $_GET['countryType'];
$termType = $_GET['termType'];

$dbh = connectDb();

$sth = $dbh->prepare("
SELECT 
	userInfoID,
	userInfoCode,
	userInfoType,

	userInfoTitle,
	userInfoShortMessage
FROM 
	TUserInfo
WHERE 
    countryType = {$countryType}
and termType = {$termType}
and CURDATE() between dispDateFrom and dispDateTo
and deleteflg = 0
ORDER BY userInfoCode desc
");
$sth->execute();

while($row = $sth->fetch(PDO::FETCH_ASSOC)){
    $userInfoItems[]=array(
    'userInfoID'=>$row['userInfoID'],
    'userInfoCode'=>$row['userInfoCode'],
    'userInfoType'=>$row['userInfoType'],
    'userInfoTitle'=>mb_convert_encoding($row['userInfoTitle'], 'UTF-8', 'EUC-JP'),
    'userInfoShortMessage'=>mb_convert_encoding($row['userInfoShortMessage'], 'UTF-8', 'EUC-JP')
    );
}
$userInfos=array(
'items'=>$userInfoItems
);

//jsonとして出力
header('Content-type: application/json');
echo json_encode($userInfos);
