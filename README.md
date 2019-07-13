# API 仕様書

## GET送信
"https://ifive.sakura.ne.jp/yuki/yuki_goods.php"  
クエリパラメータなしだと全件取得  
クエリパラメータありだと条件にあった商品を抽出  

id:商品id  
shop:商品を保有する店  
title:商品の名前  
priceLower:最低価格  
priceUpper:最高価格  
※最低価格と最高価格はセットで使う    

例  
https://ifive.sakura.ne.jp/yuki/yuki_goods.php?shop=yuki&priceLower=0&priceUpper=10000


## POST送信
"https://ifive.sakura.ne.jp/yuki/yuki_goods.php"  
json形式送信する.  

{  
  "image":[base64でエンコードしたものを入力(先頭にデータ形式も追加すること”data:image/png;base64,”とか)]  
  "title":[商品名]  
  "content":[商品説明]  
  "price":[商品の値段intで]  
  "shop":[店名]  
}  　


## PUT送信
"https://ifive.sakura.ne.jp/yuki/yuki_goods.php"  
{  
  "id":[編集したい商品id]  
  "image":[base64でエンコードしたものを入力(先頭にデータ形式も追加すること”data:image/png;base64,”とか)]  
  "title":[商品名]  
  "content":[商品説明]  
  "price":[商品の値段intで]  
  "shop":[店名]  
}  


## DELETE送信
"https://ifive.sakura.ne.jp/yuki/yuki_goods.php?id=[ここに商品id]"

id:消去したい商品id
