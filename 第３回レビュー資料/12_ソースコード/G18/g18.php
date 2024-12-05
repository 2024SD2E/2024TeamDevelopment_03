<?php
session_start();

// エラーをブラウザに表示する（デバッグ用）
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    $pdo = new PDO(
        'mysql:host=mysql311.phy.lolipop.lan;dbname=LAA1557127-beast;charset=utf8',
        'LAA1557127',
        'Qaz73565'
    );
} catch (PDOException $e) {
    die('データベース接続失敗: ' . $e->getMessage());
}

// 現在のユーザーIDをセッションから取得
if (!isset($_SESSION['user_id'])) {
    header('Location: ../G1/g1.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// 現在のユーザー情報を取得
$stmt = $pdo->prepare('SELECT mail, name, zyusyo, payment_id FROM protein_user WHERE user_id = :user_id');
$stmt->execute(['user_id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die('ユーザー情報が見つかりません');
}

// 現在の情報を変数に格納
$current_mail = $user['mail'];
$current_name = $user['name'];
$current_zyusyo = $user['zyusyo'];
$current_payment_id = $user['payment_id'];

// フォーム送信時の処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_mail = $_POST['new_mail'] ?? '';
    $password = $_POST['password'] ?? '';
    $name = $_POST['name'] ?? '';
    $zyusyo = $_POST['zyusyo'] ?? '';
    $payment_id = $_POST['payment'] ?? '';

    // 入力チェック
    if (empty($password)) {
        die('パスワードを入力してください');
    }

    // ユーザー情報の更新
    $stmt = $pdo->prepare('UPDATE protein_user SET mail = :new_mail, password = :password, name = :name, zyusyo = :zyusyo, payment_id = :payment_id WHERE user_id = :user_id');
    $result = $stmt->execute([
        'new_mail' => $new_mail ?: $current_mail, // 新しいメールが未入力なら現在のメール
        'password' => $password,
        'name' => $name ?: $current_name,       // 新しい名前が未入力なら現在の名前
        'zyusyo' => $zyusyo ?: $current_zyusyo, // 新しい住所が未入力なら現在の住所
        'payment_id' => $payment_id ?: $current_payment_id, // 新しい支払い方法が未入力なら現在の方法
        'user_id' => $user_id,
    ]);

    if ($result) {
        // 完了画面にリダイレクト
        header('Location: success.html');
        exit;
    } else {
        die('情報の更新に失敗しました');
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ユーザー情報変更</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>ユーザー情報変更</h1>
    <form action="g18.php" method="post">
        <label for="mail">現在のメールアドレス</label><br>
        <input type="email" id="mail" name="mail" value="<?= htmlspecialchars($current_mail, ENT_QUOTES, 'UTF-8'); ?>" readonly><br><br>

        <label for="new_mail">新しいメールアドレス</label><br>
        <input type="text" id="new_mail" name="new_mail" placeholder="新しいメールアドレスを入力"><br><br>

        <label for="password">新しいパスワード</label><br>
        <input type="password" id="password" name="password"><br><br>

        <label for="name">名前</label><br>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($current_name, ENT_QUOTES, 'UTF-8'); ?>"><br><br>

        <label for="zyusyo">住所</label><br>
        <input type="text" id="zyusyo" name="zyusyo" value="<?= htmlspecialchars($current_zyusyo, ENT_QUOTES, 'UTF-8'); ?>"><br><br>

        <label for="payment">お支払い方法</label><br>
        <select id="payment" name="payment">
            <option value="1" <?= $current_payment_id == 1 ? 'selected' : ''; ?>>キャリア決済</option>
            <option value="2" <?= $current_payment_id == 2 ? 'selected' : ''; ?>>コンビニ決済</option>
            <option value="3" <?= $current_payment_id == 3 ? 'selected' : ''; ?>>カード決済</option>
        </select><br><br>

        <button type="submit">完了</button>
    </form>
</body>
</html>
