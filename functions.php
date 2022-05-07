<?php
function connectDb() {
    try {
        return new PDO(DSN, DB_USER, DB_PASSWORD);
    } catch (PDOException $e) {
        echo $e->getMessage();
        exit;
    }
}

function insAccessLog($dbh,$logType) {
	$sth = $dbh->prepare("
	INSERT INTO TAccessLog(logType,createDate) 
	VALUES ({$logType},CURRENT_TIMESTAMP())
	");
	$sth->execute();

	return 0;
}
