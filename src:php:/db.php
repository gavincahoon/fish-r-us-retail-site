<?php
if (!isset($pdo)) {
  $pdo = new PDO('mysql:host=localhost;dbname=Fish R US;charset=utf8mb4', 'root', 'root', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
  ]);
}
?>