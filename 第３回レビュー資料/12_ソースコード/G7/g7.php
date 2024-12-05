<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
 
<body>
<?php
 $pdo=new PDO('mysql:host=mysql310.phy.lolipop.lan;
 dbname=LAA1557902-beast;charset=utf8',
 'LAA1557902',
 'yuuya1202');
$product_name=$_POST['product_name'];

$sql = $pdo->prepare('DELETE FROM protein_products WHERE name = ?');
$result = $sql->execute([$product_name]);
 $pdo = null;
 if($result) {
    echo 'データが正常に削除されました。 ';
 } else {
    echo 'データの削除に失敗しました。';
 }
 
?>
<br>
 <a href="../G4/g4.html" class="back">＜戻る</a>
</body>
</html>