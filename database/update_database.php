<?php 
//file: update_database.php
include 'includes/config.php';

$sql = "ALTER TABLE user
    ADD COLUMN email varchar(100) after nama,
    ADD COLUMN no_hp varchar(20) after email,
    ADD COLUMN alamat text after no_hp,
    ADD COLUMN verification_code varchar(100) after role,
    ADD COLUMN is_verified tinyint(1) default 0 after verification_code,
    ADD COLUMN last_login timestamp null after is_verified";


if ($conn->multi_query($sql)) {
    echo "Database updated successfully";
} else {
    echo "Error updating database: " . $conn->error;
}


?>