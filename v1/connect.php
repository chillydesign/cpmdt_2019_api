<?php


try {

    $conn = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME ,   DB_USER , DB_PASSWORD );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->query("SET CHARACTER SET utf8");

} catch(PDOException $e) {
    echo 'ERROR: ' . $e->getMessage();
}


?>