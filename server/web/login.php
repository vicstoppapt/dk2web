<?
session_start();
require_once("config.php");

/* DATABASE CONNECTION AND QUERIES COMMENTED OUT FOR VIEW MODE
$link = @mysql_connect($db_host, $db_user, $db_pass) or exit("Error:".mysql_error());
$db = @mysql_select_db($db_name) or exit(mysql_error());
$lang = @mysql_query("SET NAMES latin1");
*/

$uid = $_POST['uid'];
$pwd= $_POST['pwd'];

/* SQL QUERY COMMENTED OUT FOR VIEW MODE
$idcheck = mysql_query("SELECT * FROM `Player` WHERE `PlayerID` = '{$uid}' AND `Password` = PASSWORD('{$pwd}')");
*/

	if(!$uid OR !$pwd)
	{
		exit("An error occurred will be redirected");
	}

/* DATABASE CHECK COMMENTED OUT FOR VIEW MODE
	if (mysql_num_rows($idcheck) <= 0)
	{
		echo "
				<script language='javascript'>
				alert('Error: Incorrect Data.')
				history.back(1)
			</script>
		";
		exit();
	}
*/

	if(!isset($_SESSION['user_id']))
	{
		$_SESSION['user_id'] = $uid;
		echo "
				<script language='javascript'>
				alert('You have successfully logged')
				location.replace('index.php')
			</script>
		";
	}
	else
	{
		session_destroy();
		echo "
				<script language='javascript'>
				alert('An error occurred will be redirected.')
				location.replace('index.php')
			</script>
		";
		exit();
	}

?>
