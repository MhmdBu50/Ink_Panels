<?php
try {
    $db = new PDO('mysql:host=localhost;dbname=ink_panels', 'root', '0509219409');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::PARAM_STR);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}