<?php

$creatTable1Sql1 = "CREATE TABLE IF NOT EXISTS sites_viewed (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    LINK_NAME VARCHAR(200) NOT NULL,
    PAGE_CONTENT MEDIUMTEXT
    )";

$creatTable1Sql2 = "CREATE TABLE IF NOT EXISTS sites_to_view (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    LINK_NAME VARCHAR(200) NOT NULL
    )";


function createTable($sql, $conn) {
    if ($conn->query($sql) !== TRUE) {
        echo "\nError creating table: " . $conn->error;
    }
}

function createConnection($host, $user,$passwd)
{
    $conn = new mysqli($host, $user, $passwd,'mig');

    if ($conn->connect_error) {
        $conn->close();
        die("\nConnection failed: " . $conn->connect_error);
    }

    return $conn;
}

function closeConnection($conn){
    $conn->close();
}


function createDatabase($dbName,$host, $user) {
    $conn = createConnection($host, $user);
    $sql = "CREATE DATABASE IF NOT EXISTS $dbName";
    if ($conn->query($sql) !== TRUE) {
        echo "\nError creating database: " . $conn->error;
    }
    closeConnection($conn);
}


function createDatabaseWithTables($dbName,$host, $user,$passw) {
    $conn = createConnection($host, $user, $passw);
    $sql = "CREATE DATABASE IF NOT EXISTS $dbName";
    if ($conn->query($sql) !== TRUE) {
        echo "\nError creating database: " . $conn->error;
    }

    $sql1 = "USE $dbName";
    if ($conn->query($sql1) !== TRUE) {
        echo "\nError creating database: " . $conn->error;
    }
    global $creatTable1Sql1, $creatTable1Sql2;

    createTable($creatTable1Sql1, $conn);
    createTable($creatTable1Sql2, $conn);
}

function saveToDB($sql, $conn){

    if ($conn->query($sql) !== TRUE) {
        echo "$sql";
        die("\nError during save sql to DB: " . $conn->connect_errno);
    }
}



function selectFromDatabase($name, $conn){
    if($name == 'sites_to_view'){
        $tableToSelect = 'sites_to_view';
    }else{
        $tableToSelect = 'sites_viewed';
    }

    $new_array[] = '';

    $select= "SELECT LINK_NAME FROM $tableToSelect";
    $result = mysqli_query($conn,$select);
    $new_array[] = array('');
    while($row = mysqli_fetch_array($result)){
        $new_array[]=$row['LINK_NAME'];
    }

    if($new_array == null){
        return compact('');
    }
    return $new_array;
}

?>