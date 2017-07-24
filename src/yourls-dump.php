<?php

include_once('%INSTALL_DIR%/user/config.php');

$server_parts = explode (":", YOURLS_DB_HOST);

$db_name=YOURLS_DB_NAME;
$db_host=$server_parts[0];
$db_port=$server_parts[1];
$db_user=YOURLS_DB_USER;
$db_pass=YOURLS_DB_PASS;
$db_prefix=YOURLS_DB_PREFIX;

$http_scheme = $_SERVER['REQUEST_SCHEME'];
$http_host = $_SERVER['HTTP_HOST'];

$base_uri = "$http_scheme://$http_host";

try {
  $conn = new PDO("mysql:host=$db_host;port=$db_port;dbname=$db_name", $db_user, $db_pass);
  $conn ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = $conn -> prepare("SELECT keyword, url from ${db_prefix}url order by keyword");
  $sql -> execute();

  //$result = $sql->setFetchMode(PDO::FETCH_ASSOC);
  $result = $sql->fetchAll();
  //print_r($result);
  print "<table>\n";
  print "  <caption>Short Links on $http_host</caption>\n";
  print "  <thead>\n";
  print "    <tr>\n";
  print "      <th scope=\"col\">Short Link</th>\n";
  print "      <th scope=\"col\">Full Path</th>\n";
  print "    </tr>\n";
  print "  </thead>\n";
  print "  <tbody>\n";
  foreach ($result as $v) {
    print "    <tr>\n";
    print "      <td><a href=\"$base_uri/$v[0]\">$v[0]</a></td>\n";
    print "      <td>$v[1]</td>\n";
    print "    </tr>\n";
  }
  print "  </tbody>\n";
  print "</table>\n";
}
catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}
?>
