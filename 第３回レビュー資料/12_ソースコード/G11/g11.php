<?php
// セッション開始
session_start();

// データベース接続
try {
    $pdo = new PDO(
        'mysql:host=mysql311.phy.lolipop.lan;dbname=LAA1557127-beast;charset=utf8',
        'LAA1557127',
        'Qaz73565'
    );
} catch (PDOException $e) {
    die('データベース接続失敗: ' . $e->getMessage());
}

// ログインチェック
if (!isset($_SESSION['user_id'])) {
    // ユーザーがログインしていない場合、ログインページにリダイレクト
    header('Location: ../G1/g1.html');
    exit;
}

// URLパラメータで渡されたproduct_idを取得
$product_id = isset($_GET['product_id']) ? (int)$_GET['product_id'] : 0;

if ($product_id === 0) {
    die('無効なIDです');
}

// 商品詳細情報をデータベースから取得
$stmt = $pdo->prepare('SELECT * FROM protein_products WHERE product_id = :product_id');
$stmt->execute(['product_id' => $product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    die('商品が見つかりません');
}

// カートに商品を追加
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $user_id = $_SESSION['user_id'];

    // ユーザーのカートを取得または作成
    $stmt = $pdo->prepare('SELECT * FROM cart WHERE user_id = :user_id');
    $stmt->execute(['user_id' => $user_id]);
    $cart = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$cart) {
        // カートが存在しない場合は新規作成
        $stmt = $pdo->prepare('INSERT INTO cart (user_id) VALUES (:user_id)');
        $stmt->execute(['user_id' => $user_id]);
        $cart_id = $pdo->lastInsertId();
    } else {
        $cart_id = $cart['cart_id'];
    }

    // カートアイテムを確認
    $stmt = $pdo->prepare('SELECT * FROM cart_items WHERE cart_id = :cart_id AND product_id = :product_id');
    $stmt->execute(['cart_id' => $cart_id, 'product_id' => $product_id]);
    $cart_item = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($cart_item) {
        // 商品がすでにカートにある場合、数量を更新
        $stmt = $pdo->prepare('UPDATE cart_items SET quantity = quantity + 1 WHERE cart_id = :cart_id AND product_id = :product_id');
        $stmt->execute(['cart_id' => $cart_id, 'product_id' => $product_id]);
    } else {
        // 新しい商品をカートに追加
        $stmt = $pdo->prepare('INSERT INTO cart_items (cart_id, product_id, quantity) VALUES (:cart_id, :product_id, :quantity)');
        $stmt->execute([
            'cart_id' => $cart_id,
            'product_id' => $product_id,
            'quantity' => 1
        ]);
    }

    // カートに追加後、G13/g13.php にリダイレクト
    header('Location: ../G13/g13.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?>の詳細</title>
<link rel="stylesheet" href="/G11/style.css"> <!-- 分離したCSSファイルを読み込み -->
</head>
<body>
<div class="container">
    <a href="../G12/index.php" class="back">◀戻る</a>
    <img src="../G12/image/<?php echo htmlspecialchars($product['image'], ENT_QUOTES, 'UTF-8'); ?>" alt="商品画像" class="product-image">
    <h1 class="product-name"><?php echo htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?></h1>
    <p class="product-price">¥<?php echo number_format(htmlspecialchars($product['price'], ENT_QUOTES, 'UTF-8')); ?></p>
    <div class="product-details"><?php echo nl2br(htmlspecialchars($product['shosai'], ENT_QUOTES, 'UTF-8')); ?></div>
    <div class="buttons">
        <form method="POST">
            <button type="submit" name="add_to_cart" class="cart-button">カートに入れる</button>
        </form>
    </div>
</div>
</body>
</html>
