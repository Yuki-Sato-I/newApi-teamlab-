<?php
require('env.php');
ini_set('display_errors', 1);
//本番
define('DSN', "mysql:host={$host}; dbname={$dbname}; charset=utf8");
define('DB_USERNAME', $dbUsername);
define('DB_PASSWORD', $dbPassword);

//local
//define('DSN', 'mysql:unix_socket=/tmp/mysql.sock;host=localhost;dbname=yuki_test_db;charset=utf8');
//define('DB_USERNAME', 'dbuser');
//define('DB_PASSWORD', '20111019');

function db_connect(){
  try{
      $dbh = new PDO(DSN, DB_USERNAME, DB_PASSWORD);
      $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      return $dbh;
    }catch (PDOException $e){
      print('Error!:'.$e->getMessage());
      die();
    }
}

?>