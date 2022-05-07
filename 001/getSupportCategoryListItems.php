<?php
/*--------------------------------------------------
お知らせカテゴリーリスト情報アイテムを取得
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
    supportCategoryID,
    supportCategoryCode,

    supportCategoryName,
    supportCategoryMessage
FROM 
	TSupportCategory
WHERE countryType = {$countryType}
and deleteflg = 0
ORDER BY orderNo desc
");
$sth->execute();

while($row = $sth->fetch(PDO::FETCH_ASSOC)){
    $supportCategoryItems[]=array(
    'supportCategoryID'=>$row['supportCategoryID'],
    'supportCategoryCode'=>mb_convert_encoding($row['supportCategoryCode'], 'UTF-8', 'EUC-JP'),
    'countryType'=>mb_convert_encoding($row['countryType'], 'UTF-8', 'EUC-JP'),
    'supportCategoryName'=>mb_convert_encoding($row['supportCategoryName'], 'UTF-8', 'EUC-JP'),
    'supportCategoryMessage'=>mb_convert_encoding($row['supportCategoryMessage'], 'UTF-8', 'EUC-JP')
    );
}
$supportCategoryInfos=array(
'items'=>$supportCategoryItems
);

//jsonとして出力
header('Content-type: application/json');
echo json_encode($supportCategoryInfos);
