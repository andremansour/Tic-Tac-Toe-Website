<?php
include('../inc/head.php');

session_destroy();
session_unset();
header('Location: '.$_SERVER['HTTP_REFERER']);
?>