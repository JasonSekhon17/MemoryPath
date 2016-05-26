<?PHP
session_start();
include_once dirname(__FILE__) . '/../Login/dbconnect.php';


if(isset($_POST['btn-score'])) {

	$res=mysql_query("SELECT * FROM users WHERE user_id=".$_SESSION['user']);
	$userRow=mysql_fetch_array($res);
	$name=$userRow['username'];
	$score=$_POST['score'];

	if(mysql_query("INSERT INTO scores(name,score) VALUES('$name','$score')")) {
    
        header("Location: ../index.php");
 }
}
?>