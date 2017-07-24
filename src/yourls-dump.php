<?php

include_once('%INSTALL_DIR%/user/config.php');

$server_parts = explode (":", YOURLS_DB_HOST);

$db=YOURLS_DB_NAME;
$host=$server_parts[0];
$port=$server_parts[1];
$user=YOURLS_DB_USER;
$pass=YOURLS_DB_PASS;
$prefix=YOURLS_DB_PREFIX;

$base_uri = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'];

try {
  $conn = new PDO("mysql:host=$host;port=$port;dbname=$db", $user, $pass);
  $conn ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = $conn -> prepare("SELECT keyword, url from ${prefix}url order by keyword");
  $sql -> execute();

  //$result = $sql->setFetchMode(PDO::FETCH_ASSOC);
  $result = $sql->fetchAll();
  //print_r($result);
  print "<table>\n";
  print "  <caption>Short Links on lib.ucsd.edu</caption>\n";
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
