<?php
/*--------------------------------------------------
サポートカード情報アイテムを取得
--------------------------------------------------*/

require_once('config.php');
require_once('functions.php');

if(!isset($_GET['supportCategoryCode'])) {
	exit("Error");
}
if(!isset($_GET['supportCardNo'])) {
	exit("Error");
}
$supportCategoryCode = $_GET['supportCategoryCode'];
$supportCardNo = $_GET['supportCardNo'];
$countryType = $_GET['countryType'];

$dbh = connectDb();

$sth = $dbh->prepare("
SELECT 
    supportCardNo,
    supportCardMax,

    supportCardTitle,
    supportCardImageURL,
    supportCardMessage

FROM 
    TSupportCard
WHERE
    supportCategoryCode = '{$supportCategoryCode}'
and supportCardNo = {$supportCardNo}
and deleteflg = 0
and countryType = {$countryType}
ORDER BY supportCardNo desc
");
$sth->execute();

if($row = $sth->fetch(PDO::FETCH_ASSOC)){
    $cardItem = array(
    'supportCardNo'=>$row['supportCardNo'],
    'supportCardMax'=>$row['supportCardMax'],
    'supportCardTitle'=>mb_convert_encoding($row['supportCardTitle'], 'UTF-8', 'EUC-JP'),
    'supportCardImageURL'=>mb_convert_encoding($row['supportCardImageURL'], 'UTF-8', 'EUC-JP'),
    'supportCardMessage'=>mb_convert_encoding($row['supportCardMessage'], 'UTF-8', 'EUC-JP')
    );
}
else{
    $cardItem = array(
    'supportCardNo'=>0,
    'supportCardMax'=>0,
    'supportCardTitle'=>' ',
    'supportCardImageURL'=>' ',
    'supportCardMessage'=>' '
    );
}

//jsonとして出力
header('Content-type: application/json');
echo json_encode($cardItem);
