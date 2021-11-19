<?php
    $conn = new mysqli("localhost", "root", "", "opcs", "3306");

    if ($conn->connect_errno) {
        echo "Failed to connect to the database: " . $conn->connect_error;
    }
?>