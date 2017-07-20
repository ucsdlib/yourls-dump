<?php

include_once('%INSTALL_DIR%/user/config.php');

$server_parts = explode (":", YOURLS_DB_HOST);

$db=YOURLS_DB_NAME;
$host=$server_parts[0];
$port=$server_parts[1];
$user=YOURLS_DB_USER;
$pass=YOURLS_DB_PASS;
$prefix=YOURLS_DB_PREFIX;

try {
	$conn = new PDO("mysql:host=$host;port=$port;dbname=$db", $user, $pass);
	$conn ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = $conn -> prepare("SELECT keyword, url from ${prefix}url order by keyword");
	$sql -> execute();

	//$result = $sql->setFetchMode(PDO::FETCH_ASSOC);
	$result = $sql->fetchAll();
	//print_r($result);
	print "<table>\n";
	foreach ($result as $v) {
		print ("<tr><td>$v[0]</td><td>$v[1]</td>\n");
	}
	print "</table>\n";
}
catch(PDOException $e) {
	echo "Connection failed: " . $e->getMessage();
}
?>
