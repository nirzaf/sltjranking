<?php 
// DB credentials.
define('DB_HOST','sltjranking.db.14038887.75d.hostedresource.net');
define('DB_USER','sltjranking');
define('DB_PASS','Sltjfazrin@2019');
define('DB_NAME','sltjranking');
// Establish database connection.
try
{
$dbh = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME,DB_USER, DB_PASS,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
}
catch (PDOException $e)
{
exit("Error: " . $e->getMessage());
}
?>