<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>登録完了</title>
    <link rel="stylesheet" href="style.2.css">
</head>

<body>
    <?php
    try {
        $pdo = new PDO(
            'mysql:host=mysql311.phy.lolipop.lan;dbname=LAA1557127-beast;charset=utf8',
            'LAA1557127',
            'Qaz73565'
        );
    } catch (PDOException $e) {
        die('データベース接続失敗: ' . $e->getMessage());
    }

    // POSTデータを取得
    $mail = $_POST['mail'];
    $password = $_POST['password'];
    $zyusyo = $_POST['zyusyo'];
    $payment = $_POST['payment'];
    $name = $_POST['name'];

    // payment を ID に変換
    $payment_ids = [
        "キャリア決済" => 1,
        "コンビニ決済" => 2,
        "クレジット決済" => 3,
    ];

    $payment_id = $payment_ids[$payment] ?? null;

    // 登録処理
    if ($payment_id !== null) {
        $sql = $pdo->prepare('INSERT INTO protein_user (mail, password, zyusyo, payment_id, name) VALUES (?, ?, ?, ?, ?)');
        $sql->execute([$mail, $password, $zyusyo, $payment_id, $name]);
    } else {
        echo '<p style="color:red;">無効なお支払い方法が選択されました。</p>';
    }
    ?>
    <div class="container">
        <h1 class="title">登録完了</h1>
        <p>登録が正常に完了しました！</p>
        <form action="../G1/g1.html" method="post">
            <button class="styled-button">ログイン画面に戻る</button>
        </form>
    </div>
</body>

</html>
