<?php
    $host = "localhost";
    $database = "db_fixedgearcult";
    $user = "root";
    $password = "";
    $dsn = "mysql:host={$host};dbname={$database};";

    try
    {
        $conn = new PDO($dsn, $user, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch (PDOException $th)
    {
        // Log the error but don't display it to users
        error_log("Database connection failed: " . $th->getMessage());
        $conn = null;
    }
?>