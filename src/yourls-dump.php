<?php

$here=dirname(__FILE__);
include_once("$here/yourls-dump.config.php");
include_once(YOURLS_CONFIG);

$server_parts = explode (":", YOURLS_DB_HOST);

$db_name   = YOURLS_DB_NAME;
$db_host   = $server_parts[0];
$db_port   = $server_parts[1];
$db_user   = YOURLS_DB_USER;
$db_pass   = YOURLS_DB_PASS;
$db_prefix = YOURLS_DB_PREFIX;

$http_scheme = $_SERVER['REQUEST_SCHEME'];
$http_host   = $_SERVER['HTTP_HOST'];

$base_uri = "$http_scheme://$http_host";

try {
  $conn = new PDO("mysql:host=$db_host;port=$db_port;dbname=$db_name", $db_user, $db_pass);
  $conn ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = $conn -> prepare("SELECT keyword, url from ${db_prefix}url order by keyword");
  $sql -> execute();

  $result = $sql->fetchAll();

  $header = fopen('yourls-dump-header.txt', 'rb');
  $item   = file_get_contents('yourls-dump-item.txt');
  $footer = fopen('yourls-dump-footer.txt', 'rb');

  while (($line = fgets($header)) !== false ) {
    printf ($line, $http_host, $header_extra);
  }
  fclose ($header);

  foreach ($result as $v) {
    printf( $item, $base_uri, $v[0], $v[1]);
  }

  while (($line = fgets($footer)) !== false ) {
    printf ($line, $http_host, $footer_extra);
  }
}
catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}
?>
