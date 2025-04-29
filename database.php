<?php
try {
    $db = new PDO('mysql:host=localhost;port=3307;dbname=ink_panels', 'root', 'pass123');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::PARAM_STR);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}