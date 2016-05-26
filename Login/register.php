<?php
session_start();
if(isset($_SESSION['user'])!="")
{
 header("Location: home.php");
}
include_once 'dbconnect.php';

if(isset($_POST['btn-signup']))
{
 $name = mysql_real_escape_string($_POST['name']);
 $pass = mysql_real_escape_string($_POST['pass']);
 
 if(mysql_query("INSERT INTO users(username,password) VALUES('$name','$pass')"))
 {
  ?>
        <script>alert('successfully registered ');</script>
        <?php
        header("Location: login.php");
 }
 else
 {
  ?>
        <script>alert('error while registering you...');</script>
        <?php
 }
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Memory Path - Register</title>
<link rel="stylesheet" href="registerStyle.css" type="text/css" />
<meta name="viewport" content="width=device-width, initial-scale=1">


</head>
<body>
<div id="login-form">
<form method="post">
<table>
<tr class="header"><td>Register</td></tr>
<tr>
<td><input type="text" name="name" placeholder="User Name" required /></td>
</tr>
<tr>
<td><input type="password" name="pass" placeholder="Your Password" required /></td>
</tr>
<tr>
<td><button type="submit" name="btn-signup">Sign Me Up</button></td>
</tr>
<tr>
<td><a href="login.php">Sign In Here</a></td>
</tr>
</table>
</form>
</div>
</body>
</html>