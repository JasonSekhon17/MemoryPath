<?php
ob_start();
session_start();

if(!isset($_SESSION['user']))
{
 header("Location: login.php");
}
else if(isset($_SESSION['user'])!="")
{
 header("Location: ../index.php");
}

if(isset($_GET['logout']))
{
 unset($_SESSION['user']);
 header("Location: login.php");
 session_destroy();
 exit();
}
ob_end_flush();
?>