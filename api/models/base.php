<?php
function getDbConn() {
    $server = "mysql:host=" . MYSQL_HOSTNAME . ";dbname=" .MYSQL_DBNAME.";charset=utf8";
    $conn = new PDO ( $server, MYSQL_USERNAME, MYSQL_PASSWORD, array (
        PDO::ATTR_PERSISTENT => true
    ) );

    $conn->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

    return $conn;
}

function fetchAssocWithoutCache($sql, array $params) {
    $dbconn = getDbConn ();

    if (! ($dbconn instanceof PDO)) {
            throw new Exception ( BACKEND_DOWN );
    }

    $dbconn->setAttribute ( PDO::ATTR_EMULATE_PREPARES, false );
    $statement = $dbconn->prepare ( $sql );
    $statement->execute ( $params );
    $result = $statement->fetchAll ( PDO::FETCH_ASSOC );

    // $db->closeDbConn();
    return $result;
}

function dmlFunctions($cql, array $params) {
    $dbconn = getDbConn ();
    
    if (! ($dbconn instanceof PDO)) {
            throw new Exception ( BACKEND_DOWN );
    }

    // Prepare statement
    $statement = $dbconn->prepare ( $cql );

    // Execute Statement
    $result = $statement->execute ( $params );

    // Return Array
    return $dbconn->lastInsertId();
}


