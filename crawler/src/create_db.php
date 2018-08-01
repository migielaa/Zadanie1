<?php

$creatTable1Sql1 = "CREATE TABLE IF NOT EXISTS sites_viewed (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    url VARCHAR(200) NOT NULL,
    page_content MEDIUMTEXT
    )";

$creatTable1Sql2 = "CREATE TABLE IF NOT EXISTS sites_to_view (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    url VARCHAR(200) NOT NULL
    )";

function createTable($sql, $conn) {
    if ($conn->query($sql) !== TRUE) {
        echo "\nError creating table: " . $conn->error;
    }
}

function createConnection($host, $user)
{
    $conn =mysqli_connect($host=$host, $user = $user);
    if ($conn->connect_error) {
        $conn->close();
        die("\nConnection failed: " . $conn->connect_error);
    }
    return $conn;
}

function closeConnection($conn){
    mysqli_close($conn);
}


function createDatabase($dbName,$host, $user) {
    $conn = createConnection($host, $user);
    $sql = "CREATE DATABASE IF NOT EXISTS $dbName";
    if ($conn->query($sql) !== TRUE) {
        echo "\nError creating database: " . $conn->error;
    }
}


function createDatabaseWithTables($dbName,$host, $user) {
    $conn = createConnection($host, $user);
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

function saveToDB($sql,$conn){
    if ($conn->query($sql) !== TRUE) {
        echo "$sql";
        die("\nError during save sql to DB: " . $conn->connect_error);
    }}

?>