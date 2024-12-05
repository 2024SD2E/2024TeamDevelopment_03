
<!DOCTYPE html>
<html lang="ja">
 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
 
<body>
    <?php
 $pdo = new PDO(
   'mysql:host=mysql311.phy.lolipop.lan;dbname=LAA1557127-beast;charset=utf8',
   'LAA1557127',
   'Qaz73565'
);

    $name = $_POST['product_name'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $shosai = $_POST['shosai'];
    $image = $_POST['image'];
    $junle = $_POST['junle_name'];

 $sql=$pdo->prepare('INSERT INTO protein_products(name, price, quantity,shosai, junle_name, image  )VALUES(?,?,?,?,?,?)');
    $sql->execute([$name,$price,$quantity,$shosai,$junle,$image]);
 $pdo = null;
 
 echo 'データが正常に挿入されました。'
    ?>
    <br>
     <a href="../G4/g4.html" class="back">＜戻る</a>
</body>
 
</html>

