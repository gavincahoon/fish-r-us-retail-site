<?php
session_start();
if ($_SESSION['role'] === 'admin' && isset($_POST['view_as'])) {
    $_SESSION['view_as'] = $_POST['view_as'];
}
header('Location: home.php');