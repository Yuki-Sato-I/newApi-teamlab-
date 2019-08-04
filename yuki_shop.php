<?php
require_once('yuki_db_connect.php');
//ローカル用
//$_POST = ["image" => null, "title" => "aaaa", "content" => "cccc", "price" => 12];
//$_GET = ["priceLower" => 0];
$dbh = db_connect();

$method = $_SERVER["REQUEST_METHOD"];

switch ($method) {
  case 'GET':
    try{
      $dbh->beginTransaction();
      if(!$dbh->inTransaction()){
        throw new Exception('transaction error');
      }
      try{
        $sql = "SELECT * FROM shops";
        $count = 0;
        //id検索
        if(isset($_GET["id"])){
          $id = $_GET["id"];
          if($count == 0){
            $count += 1;
            $sql .= " WHERE";
          }else{
            $sql .= " AND";
          }
          $sql .= " id={$id}";
        }
        //shop名検索
        if(isset($_GET["name"])){
          $name = $_GET["name"];
          if($count == 0){
            $count += 1;
            $sql .= " WHERE";
          }else{
            $sql .= " AND";
          }
          $sql .= " name= BINARY '{$name}'";
        }

        $stmt = $dbh->prepare($sql);
        $stmt->execute();

        $goods = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($goods);
        //データベース接続の切断
        $dbh = null;
      }catch(PDOException $e){
          $dbh->rollBack();
          throw $e;
      }
    }catch(PDOException $e){
        exit($e->getMessage());
    }
    break;
  default:
    http_response_code(400) ;
    echo json_encode(["status" => "NG", "message" => "このapiはGETにしか対応していません."]);
    break;
}

?>