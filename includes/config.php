<?php
// DB credentials.
//define('DB_HOST','sltjranking.db.14038887.75d.hostedresource.net');
//define('DB_USER','sltjranking');
//define('DB_PASS','Sltjfazrin@2019');
//define('DB_NAME','sltjranking');
// Establish database connection.

//local host
define('DB_HOST','localhost');
define('DB_USER','root');
define('DB_PASS','');
define('DB_NAME','sltjranking');

try
{
$dbh = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME,DB_USER, DB_PASS,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e)
{
exit("Error: " . $e->getMessage());
}
?>