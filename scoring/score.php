<?PHP
$name = $_POST['name'];
$score = $_POST['score'];
$link = @mysql_connect("mysql1.000webhost.com", "a5542276_Jason", "jss575");
if (mysql_errno()){
	exit("-2");
}

mysql_select_db('a5542276_MemPath', '$link')
if (mysql_errno()){
	exit("-3");
}
$sql = "INSERT INTO Scores(name, score) VALUES('$name', $score)";
$result = mysql_query($sql, $link);
if (mysql_errno()){
	exit("-4");
}

exit("1");

?>