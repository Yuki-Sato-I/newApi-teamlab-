<?php
require_once('yuki_db_connect.php');
$dbh = db_connect();

$limit = 10;
$page  = 1;

$method = $_SERVER["REQUEST_METHOD"];

switch ($method) {
  case 'GET':
    try{
      $dbh->beginTransaction();
      if(!$dbh->inTransaction()){
        throw new Exception('transaction error');
      }
      try{
        $sql = "SELECT * FROM goods";
        $count = 0;
        //検索条件title
        if(isset($_GET["title"])){
          $title = $_GET["title"];
          if($count == 0){
            $count += 1;
            $sql .= " WHERE";
          }
          $sql .= " title= BINARY '{$title}'";
        }
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
        //shop検索
        if(isset($_GET["shop"])){
          $shop = $_GET["shop"];
          if($count == 0){
            $count += 1;
            $sql .= " WHERE";
          }else{
            $sql .= " AND";
          }
          $sql .= " shop= BINARY '{$shop}'";
        }
        //価格帯検索
        if(isset($_GET["priceLower"]) && isset($_GET["priceUpper"])){
          $priceLower = $_GET["priceLower"];
          $priceUpper = $_GET["priceUpper"];
          if($count == 0){
            $count += 1;
            $sql .= " WHERE";
          }else{
            $sql .= " AND";
          }
          $sql .= " price BETWEEN {$priceLower} AND {$priceUpper}";
        }

        //ページネーション
        if(isset($_GET["page"])){
          $page = (int)$_GET["page"];
        }

        $offset = ($page - 1) * $limit;
        $sql .= " LIMIT {$offset}, {$limit}";

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

  case 'POST':
    try{
      $dbh->beginTransaction();
      if (!$dbh->inTransaction()) {
          throw new Exception('transaction error');
      }
      try{
          $request = json_decode(file_get_contents('php://input'), true);

          $stmt = $dbh->prepare("INSERT INTO goods (image, title, content, price, shop) VALUES (:image, :title, :content, :price, :shop)");
          $stmt->bindValue(':image', $request["image"], PDO::PARAM_STR);//商品画像
          $stmt->bindValue(':title', $request["title"], PDO::PARAM_STR);//商品タイトル
          $stmt->bindValue(':content', $request["content"], PDO::PARAM_STR);//商品説明
          $stmt->bindValue(':price', $request["price"], PDO::PARAM_INT);//商品の値段
          $stmt->bindValue(':shop', $request["shop"], PDO::PARAM_STR);//商品の店舗名
          $stmt->execute();
          $dbh->commit();
          //データベース接続の切断
          $dbh = null;
          echo json_encode(["status" => "OK"]);
      } catch(PDOException $e){
        $dbh->rollBack();
        throw $e;
      }
    } catch(PDOException $e) {
      exit($e->getMessage());
    }
    break;

  case 'PUT':
    try{
      $dbh->beginTransaction();
      if (!$dbh->inTransaction()) {
          throw new Exception('transaction error');
      }
      try{
          $request = json_decode(file_get_contents('php://input'), true);
          $id = $request["id"];
          $image = $request["image"];
          $title = $request["title"];
          $content = $request["content"];
          $price = $request["price"];
          $shop = $request["shop"];

          $sql = "UPDATE goods SET image=:image, title=:title, content=:content, price=:price, shop=:shop WHERE id=:id";
          $stmt = $dbh->prepare($sql);
          $stmt->bindValue(':image', $image, PDO::PARAM_LOB);//商品画像
          $stmt->bindValue(':title', $title, PDO::PARAM_STR);//商品タイトル
          $stmt->bindValue(':content', $content, PDO::PARAM_STR);//商品説明
          $stmt->bindValue(':price', $price, PDO::PARAM_INT);//商品の値段
          $stmt->bindValue(':id', $id, PDO::PARAM_INT);//商品のid
          $stmt->bindValue(':shop', $shop, PDO::PARAM_STR);//商品の店舗名
          $stmt->execute();
          $dbh->commit();
          //データベース接続の切断
          $dbh = null;
          echo json_encode(["status" => "OK"]);
      } catch(PDOException $e){
        $dbh->rollBack();
        throw $e;
      }
    } catch(PDOException $e) {
      exit($e->getMessage());
    }
    break;

  case 'DELETE':
    try{
      $dbh->beginTransaction();
      if (!$dbh->inTransaction()) {
          throw new Exception('transaction error');
      }
      try{
        $id = $_GET["id"];
        $sql = "DELETE FROM goods where id={$id}";
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        $dbh->commit();
        //データベース接続の切断
        $dbh = null;
        echo json_encode(["status" => "OK"]);
      } catch(PDOException $e){
        $dbh->rollBack();
        throw $e;
      }
    } catch(PDOException $e) {
      exit($e->getMessage());
    }
    break;

  default:
    http_response_code(400) ;
    echo json_encode(["status" => "NG", "message" => "このapiはGET,POST,PUT,DELETEにしか対応していません."]);
    break;
}

?>