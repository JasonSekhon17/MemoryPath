<?php
session_start();
include_once 'dbconnect.php';

if(isset($_SESSION['user'])!="")
{
 header("Location: ../index.php");
}
if(isset($_POST['btn-login']))
{
 $name = mysql_real_escape_string($_POST['name']);
 $pass = mysql_real_escape_string($_POST['pass']);
 $res=mysql_query("SELECT * FROM users WHERE username='$name'");
 $row=mysql_fetch_array($res);
 if($row['password']==($pass))
 {
  $_SESSION['user'] = $row['user_id'];
  header("Location: ../index.php");
 }
 else
 {
  ?>
        <script>alert('wrong details');</script>
        <?php
 }
 
}
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Memory Path - Login</title>
<link rel="stylesheet" href="style.css" type="text/css" />
</head>
<body>
<div id="login-form">
<form method="post">
<table>
<tr>
<td><input type="text" name="name" placeholder="Your Username" required /></td>
</tr>
<tr>
<td><input type="password" name="pass" placeholder="Your Password" required /></td>
</tr>
<tr>
<td><button type="submit" name="btn-login">Sign In</button></td>
</tr>
<tr>
<td><a href="register.php">Sign Up Here</a></td>
</tr>
</table>
</form>
</div>
</body>
</html>