<?PHP
	session_start();
	include_once '../Login/dbconnect.php';
	mysql_query("UPDATE users SET easter='1' WHERE user_id=".$_SESSION['user']);
?>