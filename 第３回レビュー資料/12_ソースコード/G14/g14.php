<?php
// データベース接続設定
try {
    $pdo = new PDO(
        'mysql:host=mysql311.phy.lolipop.lan;dbname=LAA1557127-beast;charset=utf8',
        'LAA1557127',
        'Qaz73565'
    );
} catch (PDOException $e) {
    die("データベース接続失敗: " . $e->getMessage());
}

// セッションスタート
session_start();

// ログインチェック
if (!isset($_SESSION['user_id'])) {
    header('Location: ../G1/g1.php');
    exit;
}

// ログイン中のユーザーID
$user_id = $_SESSION['user_id'];

// カート内の商品を取得
$stmt = $pdo->prepare("
    SELECT ci.quantity, pp.price
    FROM cart_items ci
    JOIN cart c ON ci.cart_id = c.cart_id
    JOIN protein_products pp ON ci.product_id = pp.product_id
    WHERE c.user_id = :user_id
");
$stmt->execute(['user_id' => $user_id]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 合計金額を計算
$total_price = array_sum(array_map(fn($item) => $item['quantity'] * $item['price'], $cart_items));

// 購入確定処理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_purchase'])) {
    // カート内の商品を削除
    $stmt = $pdo->prepare("
        DELETE ci
        FROM cart_items ci
        JOIN cart c ON ci.cart_id = c.cart_id
        WHERE c.user_id = :user_id
    ");
    $stmt->execute(['user_id' => $user_id]);

    // 確定後にリダイレクト
    header('Location: ../G15/g15.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>購入確認</title>
    <!-- CSSパスを絶対パスに変更 -->
    <link rel="stylesheet" href="/G14/style.css">
</head>
<body>
    <div class="container">
        <a href="../G13/g13.php" class="back-link">&lt; 戻る</a>
        <p class="notice">まだ注文は確定しておりません</p>
        <form method="POST">
            <button type="submit" name="confirm_purchase" class="confirm-button">購入確定</button>
        </form>
        <div class="total">
            合計: ¥<?php echo number_format($total_price); ?>
        </div>
    </div>

    <div class="footer">
        <a href="../G12/index.php" class="footer-icon">
            <img src="home.png" alt="HOME">
            <span>HOME</span>
        </a>
        <a href="../G16/g16.html" class="footer-icon">
            <img src="user.png" alt="USER">
            <span>USER</span>
        </a>
        <a href="../G13/g13.php" class="footer-icon">
            <img src="cart.png" alt="CART">
            <span>CART</span>
        </a>
    </div>
</body>
</html>
