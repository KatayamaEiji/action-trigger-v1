<?php
/*--------------------------------------------------
  広告情報アイテムを取得
--------------------------------------------------*/
require_once('config.php');
require_once('functions.php');

if(!isset($_GET['countryType'])) {
	exit("Error");
}
$countryType = $_GET['countryType'];

$dbh = connectDb();

$sth = $dbh->prepare("
SELECT 
	adInfoID,
	adInfoCode,
	adInfoType,

	adInfoTitle,

    adInfoTitle,
    adImageURL,
    adURL

FROM 
	TAdInfo
WHERE
    countryType = {$countryType}
and CURDATE() between dispDateFrom and dispDateTo
and deleteflg = 0
ORDER BY RAND() LIMIT 1
");
$sth->execute();

while($row = $sth->fetch(PDO::FETCH_ASSOC)){
    $adInfoItems[]=array(
    'adInfoID'=>$row['adInfoID'],
    'adInfoCode'=>$row['adInfoCode'],
    'adInfoType'=>$row['adInfoType'],
    'adInfoTitle'=>mb_convert_encoding($row['adInfoTitle'], 'UTF-8', 'EUC-JP'),
    'adImageURL'=>mb_convert_encoding($row['adImageURL'], 'UTF-8', 'EUC-JP'),
    'adURL'=>mb_convert_encoding($row['adURL'], 'UTF-8', 'EUC-JP')
    );
}
$adInfos=array(
'items'=>$adInfoItems
);

//jsonとして出力
header('Content-type: application/json');
echo json_encode($adInfos);
