<?php
require_once('config.php');
require_once('functions.php');

/*
http://imadakeha.sakura.ne.jp/actiontrigger/isUserInfo.php?id=0&countryType=1
*/

if(!isset($_GET['id'])) {
	exit("Error");
}
if(!isset($_GET['countryType'])) {
	exit("Error");
}

$id = $_GET['id'];
$countryType = $_GET['countryType'];

$dbh = connectDb();

insAccessLog($dbh,1);

$sth = $dbh->prepare("
SELECT 
   max(userInfoID) userInfoID
FROM TUserInfo
WHERE userInfoID > {$id}
and termType = 1
and countryType = {$countryType}
and CURDATE() between dispDateFrom and dispDateTo
and deleteflg = 0");



$sth->execute();

if($row = $sth->fetch(PDO::FETCH_ASSOC)){
    $retData=array(
    'userInfoID'=>$row['userInfoID']
    );
}

//jsonとして出力
header('Content-type: application/json');
echo json_encode($retData);
